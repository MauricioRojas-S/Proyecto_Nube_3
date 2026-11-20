from fastapi import FastAPI, HTTPException
import psycopg

from database import get_connection
from schemas import (
    ProductoCrear,
    ProductoActualizar,
    ProductoRespuesta
)


# ==========================================
# CONFIGURACION GENERAL
# ==========================================

app = FastAPI(
    title="Proyecto Corte 3 - Microservicios Nube",
    description="Parcial computacion en la nube.",
    version="1.0.0",
    docs_url="/documentacion",
    redoc_url="/redoc"
)


# ==========================================
# RESPUESTA DE ERROR DE BASE DE DATOS
# ==========================================

def error_base_datos():
    raise HTTPException(
        status_code=503,
        detail="No fue posible comunicarse con la base de datos."
    )


# ==========================================
# PAGINA PRINCIPAL
# ==========================================

@app.get("/")
def inicio():

    return {
        "servicio": "Proyecto Corte 3 - Microservicios Nube",
        "estado": "Funcionando",
        "version": "1.0.0"
    }


# ==========================================
# VERIFICAR ESTADO DEL SERVICIO
# ==========================================

@app.get("/salud")
def verificar_servicio():

    return {
        "estado": "Funcionando",
        "servicio": "Servicio de Productos"
    }


# ==========================================
# VERIFICAR CONEXION CON POSTGRESQL
# ==========================================

@app.get("/salud/base-datos")
def verificar_base_datos():

    try:

        with get_connection() as conexion:
            conexion.execute("SELECT 1")

        return {
            "estado": "Conectado",
            "base_datos": "PostgreSQL",
            "proveedor": "Supabase"
        }

    except (psycopg.Error, RuntimeError):
        error_base_datos()


# ==========================================
# REGISTRAR PRODUCTO
# ==========================================

@app.post(
    "/productos",
    response_model=ProductoRespuesta,
    status_code=201
)
def registrar_producto(producto: ProductoCrear):

    try:

        with get_connection() as conexion:

            with conexion.cursor() as cursor:

                cursor.execute(
                    """
                    INSERT INTO public.productos
                    (
                        nombre,
                        descripcion,
                        precio,
                        cantidad,
                        categoria,
                        activo
                    )
                    VALUES (%s, %s, %s, %s, %s, %s)
                    RETURNING *
                    """,
                    (
                        producto.nombre.strip(),
                        producto.descripcion,
                        producto.precio,
                        producto.cantidad,
                        producto.categoria.strip(),
                        producto.activo
                    )
                )

                resultado = cursor.fetchone()

        return resultado

    except psycopg.Error:
        error_base_datos()


# ==========================================
# CONSULTAR TODOS LOS PRODUCTOS
# ==========================================

@app.get(
    "/productos",
    response_model=list[ProductoRespuesta]
)
def consultar_productos():

    try:

        with get_connection() as conexion:

            with conexion.cursor() as cursor:

                cursor.execute(
                    """
                    SELECT *
                    FROM public.productos
                    ORDER BY id DESC
                    """
                )

                productos = cursor.fetchall()

        return productos

    except psycopg.Error:
        error_base_datos()


# ==========================================
# CONSULTAR PRODUCTO POR ID
# ==========================================

@app.get(
    "/productos/{id_producto}",
    response_model=ProductoRespuesta
)
def consultar_producto(id_producto: int):

    try:

        with get_connection() as conexion:

            with conexion.cursor() as cursor:

                cursor.execute(
                    """
                    SELECT *
                    FROM public.productos
                    WHERE id = %s
                    """,
                    (id_producto,)
                )

                producto = cursor.fetchone()

        if producto is None:

            raise HTTPException(
                status_code=404,
                detail="El producto solicitado no existe."
            )

        return producto

    except psycopg.Error:
        error_base_datos()


# ==========================================
# ACTUALIZAR PRODUCTO
# ==========================================

@app.put(
    "/productos/{id_producto}",
    response_model=ProductoRespuesta
)
def actualizar_producto(
    id_producto: int,
    producto: ProductoActualizar
):

    try:

        with get_connection() as conexion:

            with conexion.cursor() as cursor:

                cursor.execute(
                    """
                    UPDATE public.productos
                    SET
                        nombre = %s,
                        descripcion = %s,
                        precio = %s,
                        cantidad = %s,
                        categoria = %s,
                        activo = %s,
                        fecha_actualizacion = NOW()
                    WHERE id = %s
                    RETURNING *
                    """,
                    (
                        producto.nombre.strip(),
                        producto.descripcion,
                        producto.precio,
                        producto.cantidad,
                        producto.categoria.strip(),
                        producto.activo,
                        id_producto
                    )
                )

                resultado = cursor.fetchone()

        if resultado is None:

            raise HTTPException(
                status_code=404,
                detail="El producto que deseas actualizar no existe."
            )

        return resultado

    except psycopg.Error:
        error_base_datos()


# ==========================================
# ELIMINAR PRODUCTO
# ==========================================

@app.delete("/productos/{id_producto}")
def eliminar_producto(id_producto: int):

    try:

        with get_connection() as conexion:

            with conexion.cursor() as cursor:

                cursor.execute(
                    """
                    DELETE FROM public.productos
                    WHERE id = %s
                    RETURNING id
                    """,
                    (id_producto,)
                )

                resultado = cursor.fetchone()

        if resultado is None:

            raise HTTPException(
                status_code=404,
                detail="El producto que deseas eliminar no existe."
            )

        return {
            "mensaje": "Producto eliminado correctamente.",
            "id": resultado["id"]
        }

    except psycopg.Error:
        error_base_datos()
