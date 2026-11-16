<?php

namespace App\Http\Controllers;

// Importamos los modelos involucrados en las relaciones
use App\Models\Project;
use App\Models\User;
// Importamos la clase Request que contiene los datos de la petición HTTP
use Illuminate\Http\Request;
// Importamos la fachada Auth para gestionar y comprobar la sesión del usuario actual
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Muestra el listado de todos los proyectos.
     * Ruta: GET /projects (Nombre: projects.index)
     */
    public function index()
    {
        // 1. Consulta todos los registros de la tabla 'projects' mediante Eloquent
        $projects = Project::all();

        // 2. Retorna la vista 'resources/views/projects/index.blade.php' inyectándole la variable $projects
        return view('projects.index', compact('projects'));
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto.
     * Ruta: GET /projects/create (Nombre: projects.create)
     */
    public function create()
    {
        // 1. Capa de seguridad: Verifica si el usuario ha iniciado sesión
        if (!Auth::check()) {
            return redirect()->route('register')->with('error', 'Necesitas una cuenta para poder crear un proyecto.');
        }

        // 2. Consulta todos los usuarios del sistema para permitir asignarlos como participantes en el formulario
        $users = User::all();

        // 3. Retorna la vista 'resources/views/projects/create.blade.php' con la lista de usuarios
        return view('projects.create', compact('users'));
    }

    /**
     * Almacena un nuevo proyecto en la base de datos.
     * Ruta: POST /projects (Nombre: projects.store)
     */
    public function store(Request $request)
    {
        // 1. Verificación de sesión: Debe estar autenticado para crear proyectos
        if (!Auth::check()) {
            return redirect()->route('register')->with('error', 'Necesitas una cuenta para poder crear un proyecto.');
        }

        // 2. Validación de entradas del formulario:
        // 'title' obligatorio; descripción y fecha opcionales; 'status' restringido a los 4 estados permitidos
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'required|string|in:pendiente,en_progreso,completado,cancelado',
        ]);

        // 3. Asignación del creador: Se inyecta el ID del usuario actual autenticado (Relación 1:N)
        $validated['user_id'] = Auth::id();

        // 4. Creación del proyecto en la tabla 'projects' utilizando asignación masiva ($fillable)
        $project = Project::create($validated);

        // 5. Gestión de la relación N:N (Participantes en tabla intermedia 'project_user'):
        // Si el usuario seleccionó uno o varios participantes en el select múltiple 'users[]'
        if ($request->has('users')) {
            // attach() inserta los registros en la tabla pivote asignando rol y horas por defecto
            $project->users()->attach($request->users, [
                'role'               => 'Colaborador',
                'contribution_hours' => 0,
            ]);
        }

        // 6. Redirige al listado principal con mensaje de éxito en la sesión flash
        return redirect()->route('projects.index')->with('success', 'Proyecto creado con éxito.');
    }

    /**
     * Muestra el detalle de un proyecto específico y sus participantes.
     * Ruta: GET /projects/{project} (Nombre: projects.show)
     */
    public function show(Project $project)
    {
        $isCreator=false;
        
        
        // 1. Autorización: Solo el creador del proyecto puede visualizar los detalles
        if ($project->user_id == Auth::id()) {
            $isCreator=true;
            
        }
        

        // 2. Eager Loading: Carga las relaciones 'users' (tabla intermedia) y 'creator' (autor) de forma optimizada
        $users = User::all();
        $project->load('users', 'creator');

        // 3. Retorna la vista 'resources/views/projects/show.blade.php' con el objeto $project
        return view('projects.show', compact('project', 'isCreator', 'users'));
    }

    /**
     * Muestra el formulario para editar un proyecto existente.
     * Ruta: GET /projects/{project}/edit (Nombre: projects.edit)
     */
    public function edit(Project $project)
    {
        // 1. Control de acceso (IDOR): Solo el creador del proyecto tiene permiso para ver el formulario de edición
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para editar este proyecto.');
        }

        // 2. Carga los participantes actuales que ya tiene asignados el proyecto
        $project->load('users');

        // 3. Obtiene todos los usuarios disponibles para permitir vincular nuevos participantes
        $users = User::all();

        // 4. Retorna la vista 'resources/views/projects/edit.blade.php' con el proyecto y los usuarios
        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Actualiza los datos de un proyecto y sus participantes en la base de datos.
     * Ruta: PUT/PATCH /projects/{project} (Nombre: projects.update)
     */
    public function update(Request $request, Project $project)
    {
        // 1. Control de acceso estricto en backend: Solo el dueño puede persistir cambios
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos de edición');
        }

        // 2. Validación de campos principales del proyecto
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'required|string|in:pendiente,en_progreso,completado,cancelado',
        ]);

        // 3. Actualiza las columnas principales de la tabla 'projects'
        $project->update($validated);

        // 4. Actualización de participantes existentes (Edición de rol y horas en la tabla pivote):
        if ($request->has('existing_users')) {
            // Itera sobre cada usuario editado: $userId es la clave del array y $pivotData contiene 'role' y 'contribution_hours'
            foreach ($request->existing_users as $userId => $pivotData) {
                // updateExistingPivot() actualiza únicamente la fila correspondiente en la tabla intermedia 'project_user'
                $project->users()->updateExistingPivot($userId, [
                    'role'               => $pivotData['role'],
                    'contribution_hours' => $pivotData['contribution_hours'],
                ]);
            }
        }

        // 5. Incorporación de nuevos participantes seleccionados en el desplegable
        if ($request->has('users')) {
            // attach() vincula los nuevos IDs seleccionados con valores base
            $project->users()->attach($request->users, [
                'role'               => 'Colaborador',
                'contribution_hours' => 0,
            ]);
        }

        // 6. Redirige al listado principal con confirmación de actualización
        return redirect()->route('projects.index')->with('success', 'Proyecto y participantes actualizados.');
    }

    /**
     * Elimina un proyecto de la base de datos.
     * Ruta: DELETE /projects/{project} (Nombre: projects.destroy)
     */
    public function destroy(Project $project)
    {
        // 1. Control de autorización: Solo el dueño del proyecto puede eliminarlo
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para eliminar este proyecto.');
        }

        // 2. Elimina el proyecto de la tabla 'projects' (y sus relaciones en 'project_user' por borrado en cascada)
        $project->delete();

        // 3. Redirige al listado principal con mensaje de eliminación satisfactoria
        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
