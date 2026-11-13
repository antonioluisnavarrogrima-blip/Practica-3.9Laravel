<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('register')->with('error', 'Necesitas una cuenta para poder crear un proyecto.');
        }
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Guardar los datos
        if (!Auth::check()) {
            return redirect()->route('register')->with('error', 'Necesitas una cuenta para poder crear un proyecto.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:pendiente,en_progreso,completado,cancelado',
        ]);

        $validated['user_id'] = Auth::id();
        $project = Project::create($validated);

        // Si seleccionó participantes:
        if ($request->has('users')) {
            $project->users()->attach($request->users, [
                'role' => 'Colaborador',
                'contribution_hours' => 0,
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Proyecto creado con éxito.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //El enrutador recibe el id del proyecto que se quiere ver
        if ($project->user_id !== Auth::id()) {
            abort(401, 'No tienes autorización para editar este proyecto.');
        }
        $project->load('users', 'creator');
        return view('projects.show', compact('project'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para editar este proyecto.');
        }
        $project->load('users'); // Carga los participantes actuales
        $users = User::all();    // Todos los usuarios para poder añadir nuevos
        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos de edición');
        }

        // 1. Validar los campos del proyecto
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:pendiente,en_progreso,completado,cancelado',
        ]);

        $project->update($validated);

        // 2. ACTUALIZAR los participantes existentes que se hayan editado
        if ($request->has('existing_users')) {
            foreach ($request->existing_users as $userId => $pivotData) {
                $project->users()->updateExistingPivot($userId, [
                    'role' => $pivotData['role'],
                    'contribution_hours' => $pivotData['contribution_hours'],
                ]);
            }
        }

        // Si seleccionó al menos un usuario:
        if ($request->has('users')) {
            // Asocia los IDs [1, 3, 5] con los valores por defecto del pivote
            $project->users()->attach($request->users, [
                'role' => 'Colaborador',
                'contribution_hours' => 0,
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Proyecto y participantes actualizados.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para eliminar este proyecto.');
        }
        $project->delete();
        // 3. Redirigir de vuelta al listado
        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado correctamente.');

    }
}
