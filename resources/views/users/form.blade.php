@if ($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Nombre
        </label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name',$user->name ?? '') }}"
            required
        >

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Username
        </label>

        <input
            type="text"
            name="username"
            class="form-control"
            value="{{ old('username',$user->username ?? '') }}"
            required
        >

    </div>

</div>


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old('email',$user->email ?? '') }}"
            required
        >

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Password
        </label>

        <input
            type="password"
            name="password"
            class="form-control"
        >

        @isset($user)

            <small class="text-muted">
                Dejar vacío para mantener la contraseña actual
            </small>

        @endisset

    </div>

</div>


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Rol
        </label>

        <select
            name="role"
            class="form-select"
            required
        >

            <option value="">
                Seleccionar rol
            </option>

            @foreach($roles as $role)

                <option
                    value="{{ $role->name }}"
                    @selected(
                        old(
                            'role',
                            $user->roles->first()->name ?? ''
                        ) == $role->name
                    )
                >

                    {{ $role->name }}

                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-6 mb-3 d-flex align-items-center">

        <div class="form-check mt-4">

            <input
                type="checkbox"
                name="status"
                class="form-check-input"
                value="1"
                @checked(
                    old(
                        'status',
                        $user->status ?? true
                    )
                )
            >

            <label class="form-check-label">

                Usuario activo

            </label>

        </div>

    </div>

</div>


<div class="mt-4">

    <button
        type="submit"
        class="btn btn-success"
    >
        Guardar
    </button>

    <a
        href="{{ route('users.index') }}"
        class="btn btn-secondary"
    >
        Cancelar
    </a>

</div>