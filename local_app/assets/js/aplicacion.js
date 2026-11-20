
const URL_LOCAL = "api.php";

let productos = [];


// ==========================================
// FORMATO DE MONEDA COLOMBIANA
// ==========================================

function formatoMoneda(valor) {

    return new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP",
        maximumFractionDigits: 2
    }).format(valor);

}


// ==========================================
// NOTIFICACIONES
// ==========================================

function notificar(mensaje, error = false) {

    const elemento = document.getElementById("notificacion");

    elemento.textContent = mensaje;

    elemento.classList.toggle("error", error);

    elemento.classList.add("visible");

    setTimeout(() => {
        elemento.classList.remove("visible");
    }, 3500);

}


// ==========================================
// SOLICITUDES AL MICRO SERVICIO
// ==========================================

async function solicitar(ruta, metodo = "GET", datos = null) {

    const opciones = {
        method: metodo,
        headers: {
            "Content-Type": "application/json"
        }
    };

    if (datos !== null) {
        opciones.body = JSON.stringify(datos);
    }

    const parametros = new URLSearchParams({
        ruta: ruta
    });

    const respuesta = await fetch(
        `${URL_LOCAL}?${parametros.toString()}`,
        opciones
    );

    const resultado = await respuesta.json();

    if (!respuesta.ok) {

        throw new Error(
            resultado.detail ||
            resultado.error ||
            "Ocurrió un error en la solicitud."
        );

    }

    return resultado;

}


// ==========================================
// VERIFICAR CONEXION
// ==========================================

async function verificarConexion() {

    const indicador = document.getElementById(
        "indicadorConexion"
    );

    const estado = document.getElementById(
        "estadoConexion"
    );

    try {

        await solicitar("salud");

        indicador.className = "indicador conectado";

        estado.textContent = "Servicio conectado";

    } catch (error) {

        indicador.className = "indicador desconectado";

        estado.textContent = "Servicio desconectado";

    }

}


// ==========================================
// CONSULTAR PRODUCTOS
// ==========================================

async function cargarProductos() {

    const tabla = document.getElementById(
        "tablaProductos"
    );

    tabla.innerHTML = `
        <tr>
            <td colspan="7" class="mensaje-tabla">
                Consultando información en la nube...
            </td>
        </tr>
    `;

    try {

        productos = await solicitar("productos");

        mostrarProductos(productos);

        actualizarEstadisticas();

        verificarConexion();

    } catch (error) {

        tabla.innerHTML = `
            <tr>
                <td colspan="7" class="mensaje-tabla">
                    No fue posible consultar los productos.
                </td>
            </tr>
        `;

        notificar(error.message, true);

        verificarConexion();

    }

}


// ==========================================
// MOSTRAR PRODUCTOS
// ==========================================

function mostrarProductos(lista) {

    const tabla = document.getElementById(
        "tablaProductos"
    );

    if (lista.length === 0) {

        tabla.innerHTML = `
            <tr>
                <td colspan="7" class="mensaje-tabla">
                    No hay productos registrados.
                </td>
            </tr>
        `;

        return;

    }

    tabla.innerHTML = lista.map(producto => `

        <tr>

            <td>#${producto.id}</td>

            <td>
                <strong>${escaparHTML(producto.nombre)}</strong>
            </td>

            <td>${escaparHTML(producto.categoria)}</td>

            <td>${formatoMoneda(producto.precio)}</td>

            <td>${producto.cantidad}</td>

            <td>
                <span class="${producto.activo
                    ? "estado-activo"
                    : "estado-inactivo"}">

                    ${producto.activo ? "Activo" : "Inactivo"}

                </span>
            </td>

            <td>

                <div class="acciones-tabla">

                    <button
                        class="boton-accion"
                        onclick="editarProducto(${producto.id})">
                        Editar
                    </button>

                    <button
                        class="boton-accion boton-eliminar"
                        onclick="eliminarProducto(${producto.id})">
                        Eliminar
                    </button>

                </div>

            </td>

        </tr>

    `).join("");

}


// ==========================================
// ESCAPAR TEXTO HTML
// ==========================================

function escaparHTML(valor) {

    const elemento = document.createElement("div");

    elemento.textContent = valor ?? "";

    return elemento.innerHTML;

}


// ==========================================
// ACTUALIZAR ESTADISTICAS
// ==========================================

function actualizarEstadisticas() {

    const total = productos.length;

    const activos = productos.filter(
        producto => producto.activo
    ).length;

    const valor = productos.reduce(
        (acumulado, producto) =>
            acumulado + (
                Number(producto.precio) *
                Number(producto.cantidad)
            ),
        0
    );

    document.getElementById(
        "totalProductos"
    ).textContent = total;

    document.getElementById(
        "productosActivos"
    ).textContent = activos;

    document.getElementById(
        "valorInventario"
    ).textContent = formatoMoneda(valor);

}


// ==========================================
// REGISTRAR O ACTUALIZAR PRODUCTO
// ==========================================

document.getElementById(
    "formularioProducto"
).addEventListener("submit", async function(event) {

    event.preventDefault();

    const id = document.getElementById(
        "productoId"
    ).value;

    const datos = {

        nombre: document.getElementById(
            "nombre"
        ).value.trim(),

        descripcion: document.getElementById(
            "descripcion"
        ).value.trim() || null,

        precio: Number(
            document.getElementById("precio").value
        ),

        cantidad: Number(
            document.getElementById("cantidad").value
        ),

        categoria: document.getElementById(
            "categoria"
        ).value.trim(),

        activo: document.getElementById(
            "activo"
        ).checked

    };

    const boton = document.getElementById(
        "botonGuardar"
    );

    boton.disabled = true;

    boton.textContent = "Procesando...";

    try {

        if (id) {

            await solicitar(
                `productos/${id}`,
                "PUT",
                datos
            );

            notificar("Producto actualizado correctamente.");

        } else {

            await solicitar(
                "productos",
                "POST",
                datos
            );

            notificar("Producto registrado correctamente.");

        }

        limpiarFormulario();

        await cargarProductos();

    } catch (error) {

        notificar(error.message, true);

    } finally {

        boton.disabled = false;

        boton.textContent = "Guardar producto";

    }

});


// ==========================================
// EDITAR PRODUCTO
// ==========================================

function editarProducto(id) {

    const producto = productos.find(
        item => item.id === id
    );

    if (!producto) return;

    document.getElementById(
        "productoId"
    ).value = producto.id;

    document.getElementById(
        "nombre"
    ).value = producto.nombre;

    document.getElementById(
        "descripcion"
    ).value = producto.descripcion || "";

    document.getElementById(
        "precio"
    ).value = producto.precio;

    document.getElementById(
        "cantidad"
    ).value = producto.cantidad;

    document.getElementById(
        "categoria"
    ).value = producto.categoria;

    document.getElementById(
        "activo"
    ).checked = producto.activo;

    document.getElementById(
        "tituloFormulario"
    ).textContent = "Actualizar producto";

    document.getElementById(
        "botonGuardar"
    ).textContent = "Guardar cambios";

    mostrarFormulario();

}


// ==========================================
// ELIMINAR PRODUCTO
// ==========================================

async function eliminarProducto(id) {

    const confirmar = confirm(
        "¿Estás seguro de que deseas eliminar este producto?"
    );

    if (!confirmar) return;

    try {

        await solicitar(
            `productos/${id}`,
            "DELETE"
        );

        notificar("Producto eliminado correctamente.");

        await cargarProductos();

    } catch (error) {

        notificar(error.message, true);

    }

}


// ==========================================
// LIMPIAR FORMULARIO
// ==========================================

function limpiarFormulario() {

    document.getElementById(
        "formularioProducto"
    ).reset();

    document.getElementById(
        "productoId"
    ).value = "";

    document.getElementById(
        "activo"
    ).checked = true;

    document.getElementById(
        "tituloFormulario"
    ).textContent = "Registrar producto";

    document.getElementById(
        "botonGuardar"
    ).textContent = "Guardar producto";

}


// ==========================================
// MOSTRAR FORMULARIO
// ==========================================

function mostrarFormulario() {

    document.getElementById(
        "formulario"
    ).scrollIntoView({
        behavior: "smooth"
    });

}


// ==========================================
// BUSCAR PRODUCTOS
// ==========================================

document.getElementById(
    "buscarProducto"
).addEventListener("input", function() {

    const busqueda = this.value.toLowerCase();

    const filtrados = productos.filter(producto => {

        return (
            producto.nombre.toLowerCase().includes(busqueda) ||
            producto.categoria.toLowerCase().includes(busqueda)
        );

    });

    mostrarProductos(filtrados);

});


// ==========================================
// INICIAR APLICACION
// ==========================================

document.addEventListener("DOMContentLoaded", () => {

    verificarConexion();

    cargarProductos();

});
