<x-guest-layout>
    <!-- Estado de la sesión (mensajes de status como confirmaciones de reseteo de clave) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Formulario de inicio de sesión con envío POST a la ruta 'login' -->
    <form method="POST" action="{{ route('login') }}">
        <!-- Token de protección obligatoria contra ataques Cross-Site Request Forgery (CSRF) -->
        @csrf

        <!-- Campo: Correo Electrónico -->
        <div>
            <!-- Etiqueta del campo email -->
            <x-input-label for="email" :value="__('Email')" />
            <!-- Input de texto tipado 'email' que recupera el valor introducido previamente mediante old('email') -->
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <!-- Muestra mensajes de error de validación asociados al email si falló la autenticación -->
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Campo: Contraseña -->
        <div class="mt-4">
            <!-- Etiqueta del campo contraseña -->
            <x-input-label for="password" :value="__('Password')" />
            <!-- Input de tipo password para ocultar los caracteres al teclear -->
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <!-- Muestra errores de validación de la contraseña si los hubiera -->
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Checkbox: Recordar sesión (Remember Me) -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <!-- Checkbox que indica a Laravel mantener la cookie de autenticación persistente -->
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Fila de acciones principales: Enlace a recuperar contraseña y Botón de Login -->
        <div class="flex items-center justify-between mt-6">
            <!-- Route::has('password.request') verifica si la ruta de recuperación de contraseña está habilitada -->
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <!-- Botón principal de envío del formulario de inicio de sesión -->
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Sección inferior para usuarios sin cuenta: Separador y llamada a Registrarse -->
        <div class="mt-6 pt-5 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">
                {{ __("Don't have an account yet?") }}
                <!-- Comprueba si la ruta de registro está activa y muestra el enlace con estilo destacado -->
                @if (Route::has('register'))
                    <a class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline ms-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                        {{ __('Register here') }} &rarr;
                    </a>
                @endif
            </p>
        </div>
    </form>
</x-guest-layout>
