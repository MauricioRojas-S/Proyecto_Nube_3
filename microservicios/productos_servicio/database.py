import os

import psycopg
from psycopg.rows import dict_row
from dotenv import load_dotenv

# Cargar variables de entorno
load_dotenv()

DATABASE_URL = os.getenv("DATABASE_URL")


def get_connection():
    """
    Establece una conexión con PostgreSQL
    alojado en Supabase.
    """

    if not DATABASE_URL:
        raise RuntimeError(
            "No se encontró la variable DATABASE_URL."
        )

    return psycopg.connect(
        DATABASE_URL,
        row_factory=dict_row,
        connect_timeout=10
    )