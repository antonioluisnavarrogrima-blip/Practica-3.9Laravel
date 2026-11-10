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
    <div style="float: right;">
    @auth
        <!-- Si el usuario YA ha iniciado sesión: muestra su nombre y botón de salir -->
        <span>Has iniciado sesión como:<strong>{{ Auth::user()->name }}</strong></span>
        
        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
    @else
        <!-- Si el usuario es un invitado (NO ha iniciado sesión) -->
        <a href="{{ route('login') }}"><button>Iniciar Sesión</button></a>
        <a href="{{ route('register') }}"><button>Registrarse</button></a>
    @endauth
</div>
<br style="clear: both;">
    <br style="clear:right;">
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
                    <a href="{{  route('projects.edit', $project)}}"><button>Editar</button></a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar este proyecto?')">
                            Eliminar
                        </button>
                    </form>

                </td>

            </tr>
        @empty
            <li>No hay proyectos registrados aún.</li>

        @endforelse
    </table>

</body>

</html>