<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar: {{ $project->title }}</title>
    <!-- Estilos de maquetación del formulario de edición -->
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            background-color: #2563eb;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #1d4ed8;
        }

        .btn-cancel {
            background-color: #6b7280;
            margin-left: 8px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>

<body>
    <h1>Editar Proyecto</h1>

    <!-- Formulario de actualización: apunta a 'projects.update' pasando el modelo $project -->
    <form action="{{ route('projects.update', $project) }}" method="POST">
        <!-- Token de protección obligatoria CSRF -->
        @csrf
        <!-- method('PUT'): Instruye a Laravel para que interprete esta petición POST como método HTTP PUT -->
        @method('PUT')

        <!-- Campo: Título -->
        <div class="form-group">
            <label for="title">Título del Proyecto:</label>
            <input type="text" id="title" name="title" value="{{ $project->title }}" required>
        </div>

        <!-- Campo: Descripción -->
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" rows="4" required>{{ $project->description }}</textarea>
        </div>

        <!-- Campo: Fecha Límite -->
        <div class="form-group">
            <label for="due_date">Fecha Límite:</label>
            <input type="date" id="due_date" name="due_date" value="{{ $project->due_date }}" required>
        </div>

        <!-- Campo: Estado (Usa selected para marcar dinámicamente el estado actual) -->
        <div class="form-group">
            <label for="status">Estado:</label>
            <select id="status" name="status" required>
                <option value="pendiente" @selected($project->status == 'pendiente')>Pendiente</option>
                <option value="en_progreso" @selected($project->status == 'en_progreso')>En Progreso</option>
                <option value="completado" @selected($project->status == 'completado')>Completado</option>
                <option value="cancelado" @selected($project->status == 'cancelado')>Cancelado</option>
            </select>
        </div>

        <!-- Identificación del creador del proyecto -->
        <p>Creado por: <strong>{{ $project->creator->name }}</strong> ({{ $project->creator->email }})</p>

        <!-- SECCIÓN 1: EDICIÓN EN LOTE DE PARTICIPANTES YA ASIGNADOS -->
        <h3>Participantes Actuales:</h3>
        @if ($project->users->isNotEmpty())
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Usuario</th>
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Rol</th>
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Horas</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Itera sobre los participantes para crear inputs editables indexados por su ID de usuario -->
                    @foreach ($project->users as $user)
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <strong>{{ $user->name }}</strong><br>
                                <small style="color: #666;">{{ $user->email }}</small>
                            </td>

                            <!-- Input para editar el Rol del participante en la tabla pivote -->
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <input type="text" 
                                       name="existing_users[{{ $user->id }}][role]" 
                                       value="{{ $user->pivot->role }}" 
                                       required 
                                       style="width: 90%; padding: 6px;">
                            </td>

                            <!-- Input para editar las Horas del participante en la tabla pivote -->
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <input type="number" 
                                       name="existing_users[{{ $user->id }}][contribution_hours]" 
                                       value="{{ $user->pivot->contribution_hours }}" 
                                       min="0" 
                                       required 
                                       style="width: 70px; padding: 6px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay participantes asignados a este proyecto todavía.</p>
        @endif

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">

        <!-- SECCIÓN 2: ASIGNACIÓN DE NUEVOS PARTICIPANTES -->
        <h3>Añadir Nuevos Participantes:</h3>
        <div class="form-group">
            <label for="users">Seleccionar Usuarios (puedes seleccionar varios):</label>
            <!-- Select múltiple que envía un array 'users[]' con los IDs a asociar en el pivote -->
            <select name="users[]" id="users" multiple style="height: 100px;">
                @foreach ($users as $user)
                    <!-- Filtra en la vista para no volver a listar usuarios que ya están participando -->
                    @if (!$project->users->contains($user->id))
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Botones de acción del formulario -->
        <div class="form-group">
            <button type="submit" class="btn">Actualizar Proyecto</button>
            <a href="{{ route('projects.index') }}" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>

</body>

</html>
