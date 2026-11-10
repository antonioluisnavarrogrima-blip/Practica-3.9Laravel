<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects= Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'required|string|in:pendiente,en_progreso,completado,cancelado',
        ]);
        $validated['user_id']=Auth::id();
        Project::create($validated);
        return redirect()->route('projects.index')->with('success', 'Proyecto creado correctamente.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //El enrutador recibe el id del proyecto que se quiere ver
        
        return view('projects.show', compact('project'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes autorización para editar este proyecto.');
        }
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes autorización para editar este proyecto.');
        }
        //// 1. Validar los datos recibidos
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'required|string|in:pendiente,en_progreso,completado,cancelado',
        ]);
        // 2. Actualizar el proyecto en la base de datos
        $project->update($validated);
        // 3. Redirigir al listado con un mensaje de éxito
        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes autorización para eliminar este proyecto.');
        }
         $project->delete();
        // 3. Redirigir de vuelta al listado
        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado correctamente.');

    }
}
