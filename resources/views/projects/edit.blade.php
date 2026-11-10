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

        <!-- Botones de Acción -->
        <div class="form-group">
            <button type="submit" class="btn">Actualizar Proyecto</button>
            <a href="{{ route('projects.index') }}" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>
    
</body>
</html>