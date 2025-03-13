# API REST en Laravel

Esta es una API REST desarrollada en Laravel que permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) sobre la tabla **students** (estudiantes).

## Características

- **Crear estudiante**: Permite agregar un nuevo registro de estudiante en la base de datos.
- **Eliminar estudiante**: Permite eliminar un estudiante de la base de datos mediante su ID.
- **Actualizar estudiante**: Permite actualizar los datos completos de un estudiante existente.
- **Mostrar estudiantes**: Permite mostrar todos los estudiantes o un solo estudiante mediante su ID.
- **Actualización parcial**: Permite actualizar únicamente algunos campos de un estudiante sin necesidad de modificar todo su registro.

## Endpoints

- **GET /students**: Obtiene todos los estudiantes registrados.
- **POST /students**: Registra un nuevo estudiante.
- **GET /students/{id}**: Obtiene un solo estudiante mediante su ID.
- **PUT /students/{id}**: Actualiza todos los datos de un estudiante específico por su ID.
- **PATCH /students/{id}**: Actualiza parcialmente los datos de un estudiante específico por su ID.
- **DELETE /students/{id}**: Elimina un estudiante por su ID.

## Requisitos

- Laravel (última versión)
- Composer instalado

## Instalación

1. Clona el repositorio en tu máquina local
2. Instalar composer
- composer install

3.**USO**
- php artisan migrate
- php artisan serve

