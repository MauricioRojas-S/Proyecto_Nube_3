from pydantic import BaseModel, Field, ConfigDict
from decimal import Decimal
from datetime import datetime


# ==========================================
# ESQUEMA PARA REGISTRAR PRODUCTOS
# ==========================================

class ProductoCrear(BaseModel):

    nombre: str = Field(
        min_length=1,
        max_length=100
    )

    descripcion: str | None = None

    precio: Decimal = Field(
        ge=0,
        max_digits=10,
        decimal_places=2
    )

    cantidad: int = Field(
        ge=0
    )

    categoria: str = Field(
        min_length=1,
        max_length=60
    )

    activo: bool = True


# ==========================================
# ESQUEMA PARA ACTUALIZAR PRODUCTOS
# ==========================================

class ProductoActualizar(ProductoCrear):
    pass


# ==========================================
# ESQUEMA PARA DEVOLVER PRODUCTOS
# ==========================================

class ProductoRespuesta(BaseModel):

    model_config = ConfigDict(from_attributes=True)

    id: int
    nombre: str
    descripcion: str | None
    precio: Decimal
    cantidad: int
    categoria: str
    activo: bool
    fecha_creacion: datetime
    fecha_actualizacion: datetime