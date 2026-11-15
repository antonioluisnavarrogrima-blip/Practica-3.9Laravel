<?php

// Importamos el controlador de perfil generado por Laravel Breeze
use App\Http\Controllers\ProfileController;
// Importamos la fachada Route encargada de gestionar el enrutamiento HTTP
use Illuminate\Support\Facades\Route;
// Importamos nuestro controlador de proyectos para conectar las rutas con sus métodos
use App\Http\Controllers\ProjectController;

// 1. Redirección de la raíz del sitio web:
// Cualquier petición que llegue a '/' (raíz) se redirige automáticamente por HTTP a '/projects'.
Route::redirect('/', '/projects');

// 2. Rutas del recurso 'projects' (CRUD RESTful completo):
// Route::resource genera las 7 rutas estándar: index, create, store, show, edit, update, destroy.
// ->middleware('auth'): Protege el acceso. Si un usuario no autenticado intenta acceder a cualquier
// ruta de proyectos, Laravel lo redirige de inmediato a la pantalla de login.
Route::resource('projects', ProjectController::class)->middleware('auth');

// 3. Rutas de gestión de Perfil (Breeze):
// Agrupadas bajo el middleware 'auth' para que solo usuarios logueados puedan editar, actualizar o eliminar su perfil.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Carga las rutas de autenticación definidas por Breeze (login, register, logout, password reset)
require __DIR__.'/auth.php';
