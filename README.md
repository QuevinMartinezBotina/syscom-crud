```markdown
# Gestión de Usuarios y Roles – CRUD con API en Laravel

Este proyecto es un CRUD desarrollado en **Laravel** para la gestión de usuarios y roles, que además genera contratos en formato PDF mediante una API. La arquitectura se ha diseñado para no limitarse a un modelo tradicional MVC, permitiendo que los endpoints puedan ser consumidos por otros clientes (por ejemplo, aplicaciones móviles) y manteniendo la lógica de negocio centralizada.

Además, se implementa la **eliminación suave** (soft delete) para los usuarios. Cuando se "elimina" un usuario, en realidad se establece una fecha de eliminación, ocultando el registro en la vista principal pero conservándolo en la base de datos para auditoría e historial.

---

## Requisitos del Sistema

Para ejecutar este proyecto se requiere:

- **PHP 7.4+**
- **Composer**
- **MySQL** (u otro gestor de bases de datos compatible)
- **XAMPP**, **WAMP** o similar para entorno local
- **Node.js y npm** (opcional, si se utilizan herramientas como Vite o Laravel Mix para compilar assets)

---

## Instalación y Configuración

Sigue estos pasos para instalar y configurar el proyecto en tu computador:

1. **Clonar el repositorio**

   ```bash
   git clone [https://github.com/tu-usuario/tu-repositorio.git](https://github.com/QuevinMartinezBotina/syscom-crud.git)
   cd tu-repositorio
   ```

2. **Instalar las dependencias de Composer**

   ```bash
   composer install
   ```

3. **Configurar el archivo .env**

   Copia el archivo de ejemplo y edítalo:

   ```bash
   cp .env.example .env
   ```

   Configura los siguientes parámetros según tu entorno:

   ```dotenv
   APP_NAME=GestiónUsuariosRoles
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_basedatos
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

4. **Generar la clave de la aplicación**

   ```bash
   php artisan key:generate
   ```

5. **Ejecutar las migraciones**

   Esto creará las tablas necesarias (usuarios y roles):

   ```bash
   php artisan migrate
   ```

   Si dispones de un backup de la base de datos, inclúyelo en la carpeta `backup/` y restaura la base de datos.

6. **Crear el enlace simbólico para el almacenamiento**

   Esto es necesario para acceder a los archivos generados (por ejemplo, los PDFs de contrato):

   ```bash
   php artisan storage:link
   ```

7. **Instalar dependencias de Node (opcional)**

   Si el proyecto utiliza Vite o Laravel Mix para compilar assets:

   ```bash
   npm install
   npm run dev
   ```

8. **Iniciar el servidor de desarrollo**

   ```bash
   php artisan serve
   ```

   El proyecto estará disponible en `http://127.0.0.1:8000`.

---

## Uso del Proyecto

- **Gestión de Usuarios:**  
  - Permite crear, actualizar, listar y "eliminar" usuarios.  
  - Al crear o actualizar un usuario se genera un contrato en PDF con la información actualizada.  
  - La eliminación es suave: en lugar de borrar el registro, se establece una fecha de eliminación, ocultando el usuario en la vista sin perder la información en la base de datos.

- **Gestión de Roles:**  
  - Permite gestionar (crear, editar y eliminar) roles que se asignan a los usuarios.  
  - Los roles se cargan dinámicamente en los formularios de creación y edición de usuarios, permitiendo que cualquier cambio en los roles se refleje automáticamente.

- **Arquitectura API:**  
  Los endpoints están disponibles en:
  - Usuarios: `/api/usuarios`
  - Roles: `/api/roles`  
  Esto permite que los endpoints sean consumidos por otros clientes o aplicaciones, sin depender únicamente de la interfaz web.

---

## Librerías Utilizadas

- **[Laravel](https://laravel.com/):** Framework PHP para el desarrollo.
- **[dompdf/dompdf](https://packagist.org/packages/dompdf/dompdf):** Para la generación de PDFs de contratos.
- **[Carbon](https://carbon.nesbot.com/):** Manejo y cálculo de fechas.
- **[Axios](https://axios-http.com/):** Para realizar peticiones HTTP desde el frontend.
- **[jQuery](https://jquery.com/):** Para la manipulación del DOM y base de DataTables.
- **[DataTables](https://datatables.net/):** Para la presentación, filtrado y búsqueda en las tablas.
- **[Bootstrap](https://getbootstrap.com/):** Framework CSS para el diseño responsivo.

---

## Comandos Importantes

- **Instalación de dependencias de Composer:**

  ```bash
  composer install
  ```

- **Generar clave de la aplicación:**

  ```bash
  php artisan key:generate
  ```

- **Ejecutar migraciones:**

  ```bash
  php artisan migrate
  ```

- **Crear enlace simbólico para storage:**

  ```bash
  php artisan storage:link
  ```

- **Iniciar el servidor de desarrollo:**

  ```bash
  php artisan serve
  ```

- **Instalar dependencias de Node y compilar assets (si aplica):**

  ```bash
  npm install
  npm run dev
  ```

---

## Backup de la Base de Datos

El backup de la base de datos se incluye en la carpeta `backup/`. Para restaurarlo:

1. Abre tu herramienta de gestión (phpMyAdmin, MySQL Workbench, etc.).
2. Crea o utiliza la base de datos configurada en tu archivo `.env`.
3. Importa el archivo SQL ubicado en `backup/database.sql`.

---

## Notas Adicionales

- **Eliminación Suave:**  
  Los usuarios no se eliminan físicamente; en cambio, se actualiza el campo `fecha_eliminacion` para ocultarlos de la vista principal. Esto permite conservar información para auditorías e historiales, siguiendo buenas prácticas.

- **Modularidad y Escalabilidad:**  
  La API se ha diseñado para que sus endpoints puedan ser consumidos por otros clientes, permitiendo ampliar o integrar nuevas funcionalidades sin depender exclusivamente de la interfaz web.

- **Instrucciones de Uso:**  
  Sigue los pasos anteriores para instalar, configurar y ejecutar el proyecto en tu entorno local. Si encuentras errores, revisa los logs en `storage/logs/laravel.log` y asegúrate de que el archivo `.env` esté configurado correctamente.
