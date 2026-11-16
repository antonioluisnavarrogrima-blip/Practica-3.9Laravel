<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos</title>
    <!-- Estilos CSS para el formateo de la tabla y estados visuales -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse; /* Colapsa los bordes dobles en una línea única */
            margin-top: 15px;
            font-family: Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #ccc;    /* Borde gris para delimitar celdas */
            padding: 10px 12px;        /* Espacio interno para legibilidad */
            text-align: left;          /* Alineación a la izquierda */
        }

        tr:hover {
            background-color: #f1f5f9; /* Resalta la fila al pasar el cursor */
        }
    </style>
</head>

<body>
    <!-- Barra superior de autenticación / Estado de sesión del usuario -->
    <div style="float: right;">
    @auth
        <!-- Directiva auth: Se ejecuta únicamente si el usuario está autenticado -->
        <span>Has iniciado sesión como: <strong>{{ Auth::user()->name }}</strong></span>
        
        <!-- Formulario para cierre de sesión vía POST a route('logout') con token CSRF -->
        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
    @else
        <!-- Directiva else (o guest): Se muestra si el visitante no tiene sesión activa -->
        <a href="{{ route('login') }}"><button>Iniciar Sesión</button></a>
        <a href="{{ route('register') }}"><button>Registrarse</button></a>
    @endauth
    </div>
    <br style="clear: both;">
    <br style="clear: right;">

    <!-- Título principal de la página -->
    <h1>Proyectos:</h1>
    <br>

    <!-- Botón para navegar al formulario de creación de un nuevo proyecto -->
    <a style="float: right; background-color: blue; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px;"
        href="{{ route('projects.create') }}">Crear proyecto </a> 
    <br style="clear: both">

    <!-- Tabla que lista los proyectos del sistema -->
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
        <tbody>
        {{-- forelse: Itera sobre la colección de proyectos o muestra el bloque empty si no hay registros --> --}}
        @forelse ($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>{{ $project->description }}</td>
                <td>{{ $project->due_date }}</td>
                <td>{{ $project->status }}</td>
                <td>
                    <!-- Acción: Ver detalles del proyecto individual -->
                    <a href="{{ route('projects.show', $project) }}"><button>Ver</button></a>

                    <!-- Acción: Editar proyecto (abre el formulario con datos precargados) -->
                    <a href="{{ route('projects.edit', $project) }}"><button>Editar</button></a>

                    <!-- Acción: Eliminar proyecto mediante formulario seguro con verbo DELETE -->
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE') <!-- Le indica a Laravel que trate esta petición POST como DELETE -->
                        <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar este proyecto?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <!-- Mensaje alternativo cuando no existen filas en la tabla 'projects' -->
            <tr>
                <td colspan="5" style="text-align: center;">No hay proyectos registrados aún.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</body>

</html>
