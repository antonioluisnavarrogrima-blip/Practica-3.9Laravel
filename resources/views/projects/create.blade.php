<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proyecto</title>
    <!-- Estilos de maquetación del formulario de creación -->
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
    <h1>Creación de un nuevo proyecto</h1>

    <!-- Formulario de creación: envía por POST a route('projects.store') sin parámetros en URL -->
    <form action="{{ route('projects.store') }}" method="POST">
        <!-- Token de seguridad CSRF obligatorio en Laravel -->
        @csrf

        <!-- Campo: Título -->
        <div class="form-group">
            <label for="title">Título del Proyecto:</label>
            <input type="text" id="title" name="title" required placeholder="Ej. Sistema de Inventario">
        </div>

        <!-- Campo: Descripción -->
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" rows="4" placeholder="Detalles y objetivos del proyecto..."></textarea>
        </div>

        <!-- Campo: Fecha Límite -->
        <div class="form-group">
            <label for="due_date">Fecha Límite:</label>
            <input type="date" id="due_date" name="due_date">
        </div>

        <!-- Campo: Estado inicial del proyecto -->
        <div class="form-group">
            <label for="status">Estado:</label>
            <select id="status" name="status" required>
                <option value="pendiente">Pendiente</option>
                <option value="en_progreso">En Progreso</option>
                <option value="completado">Completado</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        <!-- Selección de participantes iniciales -->
        <h3>Añadir Participantes Iniciales:</h3>
        <div class="form-group">
            <label for="users">Seleccionar Usuarios (puedes seleccionar varios):</label>
            <!-- Select múltiple con nombre 'users[]' para enviar un array de IDs a store() -->
            <select name="users[]" id="users" multiple style="height: 100px;">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <!-- Botones de Acción -->
        <div class="form-group">
            <button type="submit" class="btn">Guardar Proyecto</button>
            <a href="{{ route('projects.index') }}" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>

</body>

</html>
