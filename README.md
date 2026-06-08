# 📚 Library Management System - Code Challenge

Este es un sistema de gestión de biblioteca desarrollado en **Laravel** que permite administrar libros, categorías y usuarios. Además del CRUD estándar, el sistema implementa reglas de negocio estrictas para la validación de datos y un sistema avanzado de **préstamos de libros con lista de espera automática bajo la lógica FIFO (First In, First Out)**.

---

## 🛠️ Tecnologías y Características Principales

- **Framework:** Laravel 8.83.29 (PHP 7.4.32)
- **Base de Datos:** MySQL (Entorno local con Laragon/XAMPP)
- **Frontend:** Bootstrap 5 (UI limpia, responsiva y organizada en tablas)
- **Pruebas Automatizadas:** PHPUnit con base de datos SQLite en memoria (`:memory:`).
- **Arquitectura:** * Uso de *Form Requests\* dinámicos e inteligentes para desacoplar la validación de los controladores.
    - Desacoplamiento de lógica de notificaciones mediante un servicio dedicado (`MessageSender`).
    - Paginación nativa de 5 en 5 elementos para un rendimiento óptimo.

---

## 📋 Reglas de Negocio Implementadas

1.  **Categorías sin Números:** El nombre de las categorías está validado mediante expresiones regulares estrictas (`regex:/^[\pL\s\-]+$/u`) para asegurar que no contenga números ni caracteres especiales inválidos.
2.  **Validación Inteligente de Usuarios:** El sistema de edición de usuarios permite actualizar los datos manteniendo el mismo correo electrónico (evitando el error común de duplicidad de Laravel), pero bloquea la acción si se intenta usar el email de otro usuario existente.
3.  **Sistema FIFO de Lista de Espera:** \* Si un libro está **Disponible**, cualquier usuario registrado puede tomarlo prestado.
    - Si un libro está **Prestado**, se habilita una sección para hacer fila en la **Lista de Espera**.
    - Cuando el libro es **Devuelto**, el sistema identifica automáticamente quién fue el **primer usuario en anotarse** (First In, First Out), lo remueve de la lista de espera, libera el libro y simula el envío de una notificación (guardando un registro detallado en `storage/logs/laravel.log`).

---

## 🚀 Instrucciones de Despliegue Local

Sigue estos sencillos pasos para clonar, configurar y ejecutar el proyecto en tu entorno local (Laragon, XAMPP, etc.):

### 1. Clonar el repositorio e instalar dependencias

Abre tu terminal en tu carpeta de servidores locales (`C:\laragon\www\` o equivalente) y ejecuta:

```bash
git clone <URL_DE_ESTE_REPOSITORIO> library-challenge
cd library-challenge
composer install
npm install && npm run dev
```

### 2. Configurar el archivo de entorno

Copia el archivo de ejemplo .env.example y renómbralo a .env:

```bash
cp .env.example .env
```

Abre el archivo .env recién creado y configura tus credenciales de base de datos local:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_challenge
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generar la clave de la aplicación y correr migraciones

Genera la llave de seguridad de Laravel y ejecuta las migraciones junto con los seeders para tener datos de prueba de inmediato (usuarios, libros y categorías pre-cargados):

```bash
php artisan key:generate
php artisan migrate --seed
```

### 4. Levantar el servidor local

Si estás usando Laragon, el proyecto se creará automáticamente en un dominio local como http://library-challenge.test. Si prefieres usar el servidor embebido de Laravel, ejecuta:

```bash
php artisan serve
```

### La aplicación estará disponible en: http://127.0.0.1:8000

Ejecución de la Suite de Pruebas (Testing)
El proyecto cuenta con 11 pruebas automatizadas de integración y unitarias que cubren el 100% de los flujos críticos (creación, edición con exclusión de IDs, restricciones de regex en categorías, el flujo de colas FIFO y las relaciones Muchos a Muchos).

Para garantizar la velocidad y no alterar tu base de datos de desarrollo, las pruebas se ejecutan automáticamente sobre SQLite en memoria.

Para correr las pruebas, ejecuta en tu consola:

```bash
php artisan test
```

Deberías ver una salida limpia con todas las aserciones en verde (PASS):

- BookCrudTest: Registro, sincronización de categorías vía tabla pivote (sync) y eliminación.
- BookWaitingListTest: Validación del flujo FIFO de la lista de espera e inyección de logs.
- CategoryTest: Validación de creación sin números, actualización y borrado.
- UserValidationTest: Alta de usuarios, encriptación de contraseñas mediante bcrypt y validación de correos únicos.

## Verificación de Logs (Flujo FIFO)

Para comprobar el correcto funcionamiento del servicio de notificaciones simulado al liberar un libro en la fila de espera, puedes revisar el archivo de logs tras hacer una devolución:

```bash
tail -f storage/logs/laravel.log
```

Verás un registro estructurado como este:

```bash
[2026-06-08 18:56:11] local.INFO: ===============================
[2026-06-08 18:56:11] local.INFO: Enviando mensaje a: wiza.triston@example.org
[2026-06-08 18:56:11] local.INFO: Mensaje: ¡Buenas noticias, Alf Kihn III act! El libro 'Omnis sapiente blanditiis.' que estabas esperando ya se encuentra disponible para renta.
[2026-06-08 18:56:11] local.INFO: ===============================
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
