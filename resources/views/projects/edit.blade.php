<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar: {{ $project->title }}</title>
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

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Título -->
        <div class="form-group">
            <label for="title">Título del Proyecto:</label>
            <input type="text" id="title" name="title" value="{{ $project->title }}" required>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" rows="4" required>{{ $project->description }}</textarea>
        </div>

        <!-- Fecha Límite -->
        <div class="form-group">
            <label for="due_date">Fecha Límite:</label>
            <input type="date" id="due_date" name="due_date" value="{{ $project->due_date }}" required>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="status">Estado:</label>
            <select id="status" name="status" required>
                <option value="pendiente" @selected($project->status == 'pendiente')>Pendiente</option>
                <option value="en_progreso" @selected($project->status == 'en_progreso')>En Progreso</option>
                <option value="completado" @selected($project->status == 'completado')>Completado</option>
                <option value="cancelado" @selected($project->status == 'cancelado')>Cancelado</option>
            </select>
        </div>
                <!-- Creador -->
        <p>Creado por: <strong>{{ $project->creator->name }}</strong> ({{ $project->creator->email }})</p>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">

               <!-- Sección 1: Editar participantes ya existentes -->
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
                    @foreach ($project->users as $user)
                        <tr>
                            <!-- Nombre y email del participante -->
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <strong>{{ $user->name }}</strong><br>
                                <small style="color: #666;">{{ $user->email }}</small>
                            </td>

                            <!-- Input editable para el ROL de este usuario -->
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <input type="text" 
                                       name="existing_users[{{ $user->id }}][role]" 
                                       value="{{ $user->pivot->role }}" 
                                       required 
                                       style="width: 90%; padding: 6px;">
                            </td>

                            <!-- Input editable para las HORAS de este usuario -->
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

        <!-- Sección 2: Añadir un participante NUEVO si se desea -->
        <h3>Añadir Nuevo Participante:</h3>
        <div class="form-group">
            <label for="new_user_id">Seleccionar Usuario:</label>
            <select name="users[]" id="users" multiple>
                @foreach ($users as $user)
                    <!-- Opcional: no mostrar en el desplegable a los que ya son participantes -->
                    @if (!$project->users->contains($user->id))
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="new_role">Rol del nuevo usuario:</label>
            <input type="text" name="new_role" id="new_role" placeholder="Ej. Tester, Diseñador...">
        </div>

        <div class="form-group">
            <label for="new_contribution_hours">Horas del nuevo usuario:</label>
            <input type="number" name="new_contribution_hours" id="new_contribution_hours" value="0" min="0">
        </div>

        <div class="form-group">
            <label for="role">Rol en el proyecto:</label>
            <input type="text" name="role" id="role" placeholder="Ej. Desarrollador Frontend, Diseñador UI...">
        </div>

        <div class="form-group">
            <label for="contribution_hours">Horas estimadas de contribución:</label>
            <input type="number" name="contribution_hours" id="contribution_hours" value="0" min="0">
        </div>

        <!-- Botones de Acción -->
        <div class="form-group">
            <button type="submit" class="btn">Actualizar Proyecto</button>
            <a href="{{ route('projects.index') }}" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>

</body>

</html>