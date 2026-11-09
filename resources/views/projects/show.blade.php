<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }}</title>
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
    <div class="form-group">
            <label for="title">Título del Proyecto:</label>
            <input value="{{ $project->title }}" disabled>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea disabled >{{ $project->description }}</textarea>
        </div>

        <!-- Fecha Límite -->
        <div class="form-group">
            <label for="due_date">Fecha Límite:</label>
            <input value="{{ $project->due_date }}" disabled>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="status">Estado:</label>
            <select disabled>
                <option>{{ $project->status }}</option>
            </select>
        </div>
    
</body>
</html>