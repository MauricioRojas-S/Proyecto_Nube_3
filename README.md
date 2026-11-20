# PROYECTO DE NUBE CORTE 3
## Mauricio Rojas y Valeria Guevara
### Parcial de Computación en la Nube

---

# 1. INTRODUCCIÓN AL PROYECTO

Se realizo una aplicación web de gestión de inventarios desarrollada utilizando conceptos de computación en la nube y arquitectura de microservicios.

Su función principal es permitir que un usuario administre productos mediante cuatro operaciones fundamentales:

- Crear productos.
- Consultar productos.
- Actualizar productos.
- Eliminar productos.

La información administrada por la aplicación se almacena en una base de datos PostgreSQL alojada en Supabase, una plataforma que proporciona servicios de bases de datos en la nube.

El proyecto integra diferentes tecnologías, entre ellas PHP, Python, FastAPI, JavaScript y PostgreSQL.

## 1.1. Objetivo general

Desarrollar una aplicación web para la gestión de inventarios mediante una arquitectura basada en microservicios, integrando una base de datos alojada en la nube para almacenar y administrar información de productos.

## 1.2. Objetivos específicos

1. Construir una interfaz web para la administración de inventarios.
2. Desarrollar un microservicio utilizando Python y FastAPI.
3. Implementar una API REST para comunicar los componentes.
4. Conectar el backend con una base de datos PostgreSQL en Supabase.
5. Implementar las operaciones CRUD.
6. Aplicar conceptos de computación en la nube, modularidad y separación de responsabilidades.

---

# 2. ARQUITECTURA GENERAL

Esta es una de las partes más importantes de la sustentación.

Se utiliza una arquitectura modular en la que cada componente cumple una función específica.

## 2.1. Componentes principales

### A. Frontend

El frontend es la parte visual de la aplicación. Es decir, todo aquello con lo que interactúa el usuario.

Está desarrollado utilizando:

- HTML: estructura de la página.
- CSS: diseño visual y presentación.
- JavaScript: interacción y comunicación con el backend.
- PHP: procesamiento de las solicitudes mediante el archivo intermediario.

Su función es permitir que el usuario administre los productos sin tener que escribir instrucciones directamente en la base de datos.

### B. Intermediario PHP

El archivo `api.php` funciona como intermediario entre el frontend y el microservicio.

Cuando el usuario realiza una operación, JavaScript envía una solicitud a PHP.

PHP recibe esa solicitud y la reenvía al microservicio de FastAPI.

Por ejemplo:

Cuando un usuario registra un producto:

1. Completa el formulario.
2. JavaScript recoge los datos.
3. JavaScript envía una solicitud POST a `api.php`.
4. PHP recibe la información.
5. PHP reenvía la solicitud a FastAPI.
6. FastAPI procesa la información.
7. El microservicio registra el producto en Supabase.
8. La respuesta regresa a la interfaz.

### C. Microservicio de productos

Es el componente encargado de procesar las operaciones relacionadas con el inventario.

Está desarrollado utilizando:

- Python.
- FastAPI.
- Pydantic.
- Psycopg.

Sus responsabilidades son:

- Recibir solicitudes HTTP.
- Validar los datos.
- Ejecutar la lógica de negocio.
- Consultar y modificar la base de datos.
- Devolver respuestas en formato JSON.

El microservicio se ejecuta localmente en el puerto 8000.

### D. Base de datos en la nube

Utilizamos Supabase para alojar una base de datos PostgreSQL.

En ella se almacenan los productos registrados mediante la aplicación.

La base de datos permite conservar la información independientemente de que se cierre el navegador o se detenga la aplicación local.

---

## 2.2. Diagrama de arquitectura

El siguiente esquema representa el funcionamiento general:

```text
                 USUARIO
                    |
                    v
        +------------------------+
        |       FRONTEND         |
        |                        |
        | PHP / HTML / CSS       |
        | JavaScript             |
        +------------------------+
                    |
                    v
        +------------------------+
        |    INTERMEDIARIO PHP   |
        |                        |
        |       api.php          |
        +------------------------+
                    |
                    | HTTP / JSON
                    |
                    v
        +------------------------+
        |    MICROSERVICIO       |
        |       PRODUCTOS        |
        |                        |
        |   Python + FastAPI     |
        |                        |
        |      Puerto 8000       |
        +------------------------+
                    |
                    v
        +------------------------+
        |        PSYCOPG         |
        |                        |
        | Conexión con PostgreSQL|
        +------------------------+
                    |
                    | Conexión remota
                    v
        +------------------------+
        |       SUPABASE         |
        |                        |
        |      PostgreSQL        |
        |                        |
        | Base de datos cloud    |
        +------------------------+
```

## 2.3. ¿Qué significa este diagrama?

El diagrama muestra cómo se comunican los componentes.

El usuario no accede directamente a la base de datos.

Primero utiliza el frontend, después PHP recibe la solicitud y la dirige al microservicio.

Finalmente, FastAPI se comunica con PostgreSQL en Supabase.

Esto permite separar la interfaz, la lógica de negocio y el almacenamiento de información.

---

# 3. EXPLICACIÓN DE LAS TECNOLOGÍAS

Es importante comprender por qué se utilizó cada tecnología y cuál es su responsabilidad.

## 3.1. PHP

PHP es un lenguaje de programación utilizado principalmente para desarrollar aplicaciones web del lado del servidor.

En este proyecto se utiliza para:

- Procesar las solicitudes provenientes del frontend.
- Actuar como intermediario entre JavaScript y FastAPI.
- Redirigir las solicitudes al microservicio.
- Devolver las respuestas al frontend.

El archivo más importante es `api.php`.

## 3.2. HTML

HTML significa HyperText Markup Language.

Es el lenguaje utilizado para construir la estructura de una página web.

La aplicacion permite organizar:

- Formularios.
- Tablas.
- Botones.
- Menús.
- Secciones del panel de administración.

## 3.3. CSS

CSS significa Cascading Style Sheets.

Se utiliza para definir la apariencia de la aplicación.

En la aplicacion controla:

- Colores.
- Tipografía.
- Distribución de elementos.
- Tamaños.
- Espaciados.
- Diseño adaptable.

## 3.4. JavaScript

JavaScript permite agregar comportamiento dinámico a la interfaz.

En la aplicacion se encarga de:

- Capturar los datos de los formularios.
- Enviar solicitudes al intermediario PHP.
- Recibir respuestas JSON.
- Actualizar las tablas sin reconstruir manualmente la página.
- Mostrar notificaciones.
- Realizar búsquedas.
- Actualizar las estadísticas del inventario.

Un concepto importante es `fetch()`.

`fetch()` permite realizar solicitudes HTTP desde JavaScript.

Por ejemplo:

```javascript
fetch("api.php?ruta=productos")
```

Esta instrucción realiza una solicitud al intermediario PHP para consultar los productos.

## 3.5. Python

Python es el lenguaje utilizado para desarrollar el microservicio.

Se caracteriza por su sintaxis sencilla y por disponer de numerosas bibliotecas para el desarrollo de aplicaciones.

Se utiliza para implementar la lógica del backend.

## 3.6. FastAPI

FastAPI es un framework de Python utilizado para construir API.

Una API permite que diferentes aplicaciones o componentes se comuniquen mediante solicitudes y respuestas.

En la aplicacion FastAPI se encarga de recibir las solicitudes relacionadas con los productos y ejecutar las operaciones correspondientes.

También proporciona documentación automática mediante Swagger UI.

## 3.7. Pydantic

Pydantic es una biblioteca utilizada para validar y estructurar los datos.

Nos permite comprobar que la información enviada para crear o actualizar productos tenga los formatos y restricciones esperados.

Por ejemplo:

- El nombre debe ser una cadena de texto.
- El precio debe ser numérico y no negativo.
- La cantidad debe ser un número entero no negativo.
- El estado debe ser verdadero o falso.

Esto ayuda a evitar que ingresen datos incorrectos al backend.

## 3.8. Psycopg

Psycopg es una biblioteca que permite conectar aplicaciones Python con bases de datos PostgreSQL.

En CloudStore permite que FastAPI consulte y modifique la información almacenada en Supabase.

Es el puente de comunicación entre Python y PostgreSQL.

## 3.9. PostgreSQL

PostgreSQL es un sistema gestor de bases de datos relacional.

Permite almacenar información organizada en tablas y relacionar diferentes conjuntos de datos.

Se utiliza para almacenar los productos.

## 3.10. Supabase

Supabase es una plataforma que proporciona servicios de desarrollo de aplicaciones, incluyendo bases de datos PostgreSQL administradas.

Se utiliza principalmente como proveedor de base de datos en la nube.

Su función es almacenar remotamente los datos del inventario y permitir que el microservicio se conecte a ellos.

## 3.11. XAMPP

XAMPP es un entorno de desarrollo local que incluye Apache, PHP y otros componentes.

Permite ejecutar la interfaz y el intermediario PHP desde nuestro computador.

Es importante comprender que XAMPP no es el servicio cloud del proyecto: es la herramienta utilizada para ejecutar localmente la parte web.

---

# 4. EXPLICACIÓN DE LA ESTRUCTURA DEL PROYECTO

La estructura de carpetas permite organizar los componentes del Proyecto.

```text
ProyectoNube/
│
├── local_app/
│   │
│   ├── index.php
│   ├── api.php
│   │
│   ├── config/
│   │   └── api.php
│   │
│   └── assets/
│       │
│       ├── css/
│       │   └── estilos.css
│       │
│       └── js/
│           └── aplicacion.js
│
├── microservicios/
│   │
│   └── productos_servicio/
│       │
│       ├── main.py
│       ├── database.py
│       ├── schemas.py
│       ├── requirements.txt
│       └── .env
│
├── .gitignore
└── README.md
```

## 4.1. Carpeta local_app

Contiene los archivos de la aplicación web.

### index.php

Es el archivo principal que presenta la interfaz.

Contiene la estructura del panel y los elementos que permiten administrar los productos.

### api.php

Es el intermediario que recibe las solicitudes del frontend y las reenvía al microservicio.

### config/api.php

Contiene la configuración de las direcciones de los servicios.

Por ejemplo, la dirección local del microservicio de productos.

### assets/css/estilos.css

Contiene los estilos visuales de la aplicación.

### assets/js/aplicacion.js

Contiene la lógica de interacción del frontend.

Se encarga de consultar, crear, actualizar y eliminar productos mediante solicitudes HTTP.

## 4.2. Carpeta productos_servicio

Contiene el backend desarrollado en Python.

### main.py

Es el archivo principal de FastAPI.

Aquí se definen los endpoints y las operaciones CRUD.

### database.py

Contiene la lógica de conexión con PostgreSQL.

Utiliza Psycopg y obtiene la dirección de conexión desde las variables de entorno.

### schemas.py

Define los esquemas de datos mediante Pydantic.

Permite validar la información recibida y estructurar las respuestas.

### requirements.txt

Contiene las dependencias necesarias para ejecutar el microservicio.

### .env

Contiene las variables de entorno, especialmente la dirección de conexión a la base de datos.

Este archivo debe permanecer privado y no debe publicarse en GitHub.

