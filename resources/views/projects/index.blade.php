<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            /* Colapsa los bordes dobles en uno solo */
            margin-top: 15px;
            font-family: Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #ccc;
            /* Borde fino y gris */
            padding: 10px 12px;
            /* Espacio interno para que el texto respire */
            text-align: left;
            /* Alineación del texto a la izquierda */
        }

        tr:hover {
            background-color: #f1f5f9;
            /* Resalta la fila al pasar el ratón */
        }
    </style>
</head>

<body>
    <h1>Proyectos:</h1>
    <br>
    <a style="float: right; background-color: blue; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px;"
        href="{{ route('projects.create') }}">Crear proyecto </a> <br style="clear: both">
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha Límite</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        @forelse ($projects as $project)
            <tr>
                <td>{{$project->title}}</td>
                <td>{{$project->description}}</td>
                <td>{{$project->due_date}}</td>
                <td>{{$project->status}}</td>
                <td>
                    <a href="{{ route('projects.show', $project) }}"><button>Ver</button></a>
                    <a href="{{  route('projects.index')}}"><button>Editar</button></a>
                    <a href="{{ route('projects.index')  }}"><button>Eliminar</button></a>

                </td>

            </tr>
        @empty
            <li>No hay proyectos registrados aún.</li>

        @endforelse
    </table>

</body>

</html>