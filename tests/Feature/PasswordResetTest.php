<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles required by users migration (role_id FK)
        Role::insert([
            ['id' => 1, 'name' => 'Administrador', 'description' => 'Admin del sistema'],
            ['id' => 2, 'name' => 'Docente',        'description' => 'Docente de materia'],
            ['id' => 3, 'name' => 'Coordinador',    'description' => 'Coordinador'],
            ['id' => 4, 'name' => 'Autoridad',      'description' => 'Autoridad'],
        ]);
    }

    /**
     * Helper: crea un usuario con datos mínimos requeridos.
     */
    private function createUser(array $overrides = []): User
    {
        $index = DB::table('users')->count();

        return User::create(array_merge([
            'role_id'  => 1,
            'name'     => 'Test User ' . ($index + 1),
            'username' => 'testuser' . ($index + 1) . '_' . uniqid(),
            'email'    => 'user' . ($index + 1) . '_' . uniqid() . '@cup.edu.bo',
            'password' => Hash::make('old-password'),
            'status'   => true,
        ], $overrides));
    }

    // ═══════════════════════════════════════════════════════════════
    // ✅ HAPPY PATH
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function test_forgot_password_page_loads(): void
    {
        $this->get(route('password.request'))
             ->assertOk()
             ->assertSee('Recuperar');
    }

    /** @test */
    public function test_reset_link_can_be_requested(): void
    {
        $user = $this->createUser();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors()
                 ->assertRedirect();

        // Verify token was stored in password_reset_tokens table
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function test_reset_password_page_loads_with_valid_token(): void
    {
        $token = 'reset-token-abc123';

        $this->get(route('password.reset', ['token' => $token]))
             ->assertOk()
             ->assertSee('Restablecer');
    }

    /** @test */
    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::create([
            'role_id'  => 1,
            'name'     => 'Password Reset User',
            'username' => 'resetuser_' . uniqid(),
            'email'    => 'reset_' . uniqid() . '@cup.edu.bo',
            'password' => Hash::make('old-password'),
            'status'   => true,
        ]);

        $plainToken = 'my-reset-token-xyz';

        // Insert token manually: the DB stores Hash::make($plainToken)
        DB::table('password_reset_tokens')->insert([
            'email'      => $user->email,
            'token'      => Hash::make($plainToken),
            'created_at' => Carbon::now(),
        ]);

        $newPassword = 'new-secure-password';

        $response = $this->post(route('password.update'), [
            'token'                 => $plainToken,
            'email'                 => $user->email,
            'password'              => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Contraseña restablecida correctamente.');

        // Verify can log in with new password
        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => $newPassword,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    // ═══════════════════════════════════════════════════════════════
    // ❌ SAD PATH — VALIDACIÓN
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function test_forgot_password_requires_valid_email(): void
    {
        $this->post(route('password.email'), ['email' => ''])
             ->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_forgot_password_rejects_invalid_email_format(): void
    {
        $this->post(route('password.email'), ['email' => 'not-an-email'])
             ->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_reset_password_requires_all_fields(): void
    {
        $this->post(route('password.update'), [
            'token'                 => '',
            'email'                 => '',
            'password'              => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors(['token', 'email', 'password']);
    }

    /** @test */
    public function test_reset_password_requires_minimum_length(): void
    {
        $this->post(route('password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'user@cup.edu.bo',
            'password'              => '123',        // menos de 6 caracteres
            'password_confirmation' => '123',
        ])->assertSessionHasErrors('password');
    }

    /** @test */
    public function test_reset_password_requires_confirmation_match(): void
    {
        $this->post(route('password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'user@cup.edu.bo',
            'password'              => '12345678',
            'password_confirmation' => 'diferente',
        ])->assertSessionHasErrors('password');
    }

    // ═══════════════════════════════════════════════════════════════
    // ❌ SAD PATH — SEGURIDAD
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function test_reset_password_fails_with_invalid_token(): void
    {
        $user = $this->createUser();

        $this->post(route('password.update'), [
            'token'                 => 'token-que-no-existe',
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_csrf_bypassed_in_testing_environment(): void
    {
        // Laravel desactiva VerifyCsrfToken automáticamente en entorno 'testing'.
        // POST sin _token explícito NO debe devolver 419, sino procesarse.
        // Con email inexistente, el controller redirige back con error.
        $this->post(route('password.email'), ['email' => 'user@cup.edu.bo'])
             ->assertRedirect()           // 302, no 419
             ->assertSessionHasErrors();  // email inexistente
    }

    /** @test */
    public function test_authenticated_user_redirected_from_forgot_password(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->get(route('password.request'))
             ->assertRedirect('/dashboard');
    }
}
