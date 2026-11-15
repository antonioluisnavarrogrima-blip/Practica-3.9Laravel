<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }}</title>
    <!-- Estilos de maquetación para la vista de consulta -->
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
    </style>
</head>

<body>
    <!-- Campo: Título del Proyecto (Deshabilitado para solo lectura) -->
    <div class="form-group">
        <label for="title">Título del Proyecto:</label>
        <input value="{{ $project->title }}" disabled>
    </div>

    <!-- Campo: Descripción del Proyecto (Deshabilitado) -->
    <div class="form-group">
        <label for="description">Descripción:</label>
        <textarea disabled>{{ $project->description }}</textarea>
    </div>

    <!-- Campo: Fecha Límite (Deshabilitado) -->
    <div class="form-group">
        <label for="due_date">Fecha Límite:</label>
        <input value="{{ $project->due_date }}" disabled>
    </div>

    <!-- Campo: Estado actual del proyecto (Desplegable deshabilitado) -->
    <div class="form-group">
        <label for="status">Estado:</label>
        <select disabled>
            <option>{{ $project->status }}</option>
        </select>
    </div>

    <!-- Información del creador del proyecto (Relación 1:N con la tabla users vía $project->creator) -->
    <p>Creado por: <strong>{{ $project->creator->name }}</strong> ({{ $project->creator->email }})</p>

    <!-- Lista de usuarios participantes (Relación N:N obtenida desde la tabla intermedia 'project_user') -->
    <h3>Participantes:</h3>
    <ul>
        <!-- Itera sobre los participantes cargados en $project->users -->
        @forelse ($project->users as $user)
            <li>
                <!-- Muestra nombre, email y los campos guardados en la tabla pivote: role y contribution_hours -->
                {{ $user->name }} ({{ $user->email }}) - Rol: <strong>{{ $user->pivot->role }}</strong> | Horas: <strong>{{ $user->pivot->contribution_hours }} hrs</strong>
            </li>
        @empty
            <!-- Mensaje mostrado si ningún usuario está asociado en la tabla pivote -->
            <li>No hay participantes asignados a este proyecto.</li>
        @endforelse
    </ul>

    <!-- Enlace de retorno al listado principal -->
    <div style="margin-top: 20px;">
        <a href="{{ route('projects.index') }}">Volver al listado de proyectos</a>
    </div>

</body>

</html>
