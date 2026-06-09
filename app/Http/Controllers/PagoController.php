<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PagoController extends Controller
{
    /**
     * Manejar webhook de Stripe para validación automática de pagos.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            return response()->json(['status' => 'invalid signature'], 400);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $pagoId = $session->client_reference_id;

            \Log::info('--- INICIO WEBHOOK STRIPE ---');
            \Log::info('ID Recibido (client_reference_id): ' . ($pagoId ?? 'NULO'));

            if ($pagoId) {
                $pago = \App\Models\Pago::find($pagoId);
                if ($pago) {
                    $pago->estado = 'PAGADO';
                    $pago->fecha_pago = now();
                    $pago->save();
                    \Log::info('Pago actualizado en BD a PAGADO');

                    $postulante = \App\Models\Postulante::find($pago->postulante_id);
                    if ($postulante) {
                        \DB::table('postulantes')
                            ->where('id', $postulante->id)
                            ->update(['estado_final' => 'HABILITADO']);

                        \Log::info('EXITO: Postulante ' . $postulante->id . ' actualizado a HABILITADO');
                    } else {
                        \Log::error('Fallo: No se encontró el Postulante asociado al pago');
                    }
                } else {
                    \Log::error('Fallo: No se encontró el Pago en la BD con el ID: ' . $pagoId);
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Crear sesión de checkout de Stripe para el postulante autenticado.
     */
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $postulante = auth()->user()->postulante;

        if (!$postulante) {
            return back()->with('error', 'No tienes un perfil de postulante asociado.');
        }

        $pago = \App\Models\Pago::firstOrCreate(
            ['postulante_id' => $postulante->id],
            [
                'monto'       => config('services.stripe.monto_inscripcion'),
                'estado'      => 'PENDIENTE',
                'metodo_pago' => 'Tarjeta',
            ]
        );

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode'                 => 'payment',
            'client_reference_id'  => (string) $pago->id,
            'success_url'          => route('dashboard') . '?pago=exitoso',
            'cancel_url'           => route('dashboard') . '?pago=cancelado',
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => [
                        'name' => 'Matrícula CUP — ' . $postulante->nombre_completo,
                    ],
                    'unit_amount' => config('services.stripe.monto_inscripcion') * 100,
                ],
                'quantity' => 1,
            ]],
        ]);

        return redirect()->away($session->url);
    }
}
