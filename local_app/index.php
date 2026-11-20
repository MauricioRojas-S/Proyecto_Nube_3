
<?php
$nombreSistema = "Proyecto Corte 3";
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= $nombreSistema ?> | Panel administrativo</title>

    <link rel="stylesheet"
          href="assets/css/estilos.css">

    <script src="assets/js/aplicacion.js" defer></script>

</head>

<body>

<div class="contenedor">

    <!-- BARRA LATERAL -->

    <aside class="barra-lateral">

        <div class="marca">

            <div class="logo">
                <i class="simbolo">P3</i>
            </div>

            <div>
                <h2>Proyecto Corte 3</h2>
                <span>Mauricio Alejandro Rojas</span>
                <span>Valeria Guevara</span>
            </div>

        </div>


        <div class="separador"></div>


        <p class="titulo-menu">MENÚ PRINCIPAL</p>

        <nav class="navegacion">

            <a href="#inicio" class="elemento-menu activo">
                <span>▦</span>
                Panel principal
            </a>

            <a href="#inventario" class="elemento-menu">
                <span>▣</span>
                Productos
            </a>

            <a href="#formulario" class="elemento-menu">
                <span>＋</span>
                Registrar producto
            </a>

            <a href="#estadisticas" class="elemento-menu">
                <span>▤</span>
                Estadísticas
            </a>

        </nav>


        <div class="estado-sistema">

            <div class="indicador" id="indicadorConexion"></div>

            <div>
                <strong id="estadoConexion">Verificando conexión</strong>
                <small>Microservicio de productos</small>
            </div>

        </div>


        <div class="pie-lateral">
            <span>Computacion en la Nube</span>
            <span>Parcial corte 3</span>
        </div>

    </aside>


    <!-- CONTENIDO PRINCIPAL -->

    <main class="contenido">

        <header class="encabezado">

            <div>
                <p class="ruta">INICIO / PANEL PRINCIPAL</p>
                <h1>Panel administrativo</h1>
                <p class="subtitulo">
                    Administra tus productos desde la nube.
                </p>
            </div>

            <div class="usuario">
                <div class="avatar">AD</div>

                <div>
                    <strong>Administrador</strong>
                    <small>Gestión de inventario</small>
                </div>
            </div>

        </header>


        <!-- BIENVENIDA -->

        <section class="bienvenida" id="inicio">

            <div>
                <span class="etiqueta">PROYECTO CORTE 3</span>

                <h2>Aplicación Microsevicio Corte 3</h2>

                <p>
                    Gestiona, consulta y administra tus productos
                    mediante una arquitectura de servicios en la nube.
                </p>
            </div>

            <div class="icono-nube">☁</div>

        </section>


        <!-- ESTADISTICAS -->

        <section class="estadisticas" id="estadisticas">

            <article class="tarjeta-estadistica">

                <div class="icono-tarjeta azul">▣</div>

                <div>
                    <p>Productos registrados</p>
                    <h2 id="totalProductos">--</h2>
                    <small>Registros en PostgreSQL</small>
                </div>

            </article>


            <article class="tarjeta-estadistica">

                <div class="icono-tarjeta verde">$</div>

                <div>
                    <p>Valor del inventario</p>
                    <h2 id="valorInventario">--</h2>
                    <small>Precio por cantidad</small>
                </div>

            </article>


            <article class="tarjeta-estadistica">

                <div class="icono-tarjeta morado">✓</div>

                <div>
                    <p>Productos activos</p>
                    <h2 id="productosActivos">--</h2>
                    <small>Disponibles en el sistema</small>
                </div>

            </article>

        </section>


        <!-- GESTION DE PRODUCTOS -->

        <section class="panel" id="inventario">

            <div class="titulo-panel">

                <div>
                    <h2>Inventario de productos</h2>
                    <p>Consulta y administra tus registros.</p>
                </div>

                <button class="boton-principal"
                        onclick="mostrarFormulario()">
                    + Nuevo producto
                </button>

            </div>


            <div class="herramientas">

                <input
                    type="text"
                    id="buscarProducto"
                    placeholder="Buscar por nombre o categoría..."
                >

                <button class="boton-secundario"
                        onclick="cargarProductos()">
                    ↻ Actualizar
                </button>

            </div>


            <div class="tabla-contenedor">

                <table>

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>PRODUCTO</th>
                            <th>CATEGORÍA</th>
                            <th>PRECIO</th>
                            <th>CANTIDAD</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>

                    </thead>

                    <tbody id="tablaProductos">

                        <tr>
                            <td colspan="7" class="mensaje-tabla">
                                Cargando productos...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- FORMULARIO -->

        <section class="panel formulario-panel"
                 id="formulario">

            <div class="titulo-panel">

                <div>
                    <h2 id="tituloFormulario">Registrar producto</h2>
                    <p>
                        Introduce la información del producto.
                    </p>
                </div>

                <span class="etiqueta-formulario">
                    BASE DE DATOS EN LA NUBE
                </span>

            </div>


            <form id="formularioProducto">

                <input type="hidden" id="productoId">

                <div class="campos">

                    <div class="campo">

                        <label for="nombre">Nombre del producto</label>

                        <input
                            type="text"
                            id="nombre"
                            maxlength="100"
                            required
                            placeholder="Ej. Portátil Lenovo"
                        >

                    </div>


                    <div class="campo">

                        <label for="categoria">Categoría</label>

                        <input
                            type="text"
                            id="categoria"
                            maxlength="60"
                            required
                            placeholder="Ej. Tecnología"
                        >

                    </div>


                    <div class="campo">

                        <label for="precio">Precio</label>

                        <input
                            type="number"
                            id="precio"
                            min="0"
                            step="0.01"
                            required
                            placeholder="2500000"
                        >

                    </div>


                    <div class="campo">

                        <label for="cantidad">Cantidad</label>

                        <input
                            type="number"
                            id="cantidad"
                            min="0"
                            step="1"
                            required
                            placeholder="10"
                        >

                    </div>


                    <div class="campo campo-completo">

                        <label for="descripcion">Descripción</label>

                        <textarea
                            id="descripcion"
                            rows="3"
                            placeholder="Descripción del producto..."
                        ></textarea>

                    </div>


                    <div class="campo campo-completo">

                        <label class="check-activo">
                            <input type="checkbox"
                                   id="activo"
                                   checked>
                            Producto activo
                        </label>

                    </div>

                </div>


                <div class="acciones-formulario">

                    <button type="button"
                            class="boton-secundario"
                            onclick="limpiarFormulario()">
                        Limpiar
                    </button>

                    <button type="submit"
                            class="boton-principal"
                            id="botonGuardar">
                        Guardar producto
                    </button>

                </div>

            </form>

        </section>


        <footer class="pie-contenido">

            <span>Fundación Universitaria Los Libertadores</span>

            <span>
                Aplicación local conectada a microservicios en la nube
            </span>

        </footer>

    </main>

</div>

<div id="notificacion" class="notificacion"></div>

</body>
</html>
