<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista del formulario de registro de nuevos usuarios.
     * Ruta: GET /register (Nombre: register)
     */
    public function create(): View
    {
        // Retorna la vista Blade ubicada en resources/views/auth/register.blade.php
        return view('auth.register');
    }

    /**
     * Procesa la solicitud de registro y almacena un nuevo usuario en la base de datos.
     * Ruta: POST /register (Nombre: register)
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Valida las entradas:
        // 'name' obligatorio y texto; 'email' obligatorio, en minúsculas, válido y único en la tabla 'users';
        // 'password' obligatorio, coincidente con password_confirmation y sujeto a las reglas seguras por defecto
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Crea el registro en la base de datos aplicando Hash::make para almacenar la contraseña de forma segura
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Dispara el evento oficial de Laravel para usuarios registrados (ej. para enviar correo de verificación)
        event(new Registered($user));

        // 4. Inicia la sesión automáticamente para el nuevo usuario recién creado
        Auth::login($user);

        // 5. Redirige al listado principal de proyectos una vez autenticado
        return redirect(route('projects.index', absolute: false));
    }
}
