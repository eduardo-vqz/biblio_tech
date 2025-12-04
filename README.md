# Bibliotech

Aplicación web en PHP para gestionar una biblioteca académica. Permite administrar libros, autores, categorías, usuarios y préstamos con control de acceso por roles.

## Requisitos
- PHP 8.1+ con extensiones `pdo` y `pdo_mysql` activadas.
- Servidor web capaz de servir el directorio `public/` (Apache/Nginx) o el servidor embebido de PHP.
- MySQL/MariaDB.

## Configuración inicial
1. Clona el repositorio y ubica el proyecto en el directorio raíz de tu servidor.
2. Copia el archivo `app/Config/config.php` y ajusta las constantes de conexión (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_CHARSET`) y la `BASE_URL` de acuerdo con tu entorno.【F:app/Config/config.php†L6-L18】
3. Crea una base de datos llamada `biblio_tech` (o el nombre que hayas configurado) y genera las tablas:
   - `usuarios` (nombre, apellido, email, password_hash, tipo_usuario, estado, fecha_registro)
   - `libros` (titulo, isbn, anio_publicacion, id_categoria, descripcion, stock_total, stock_disponible, estado, fecha_registro)
   - `categorias`
   - `autores`
   - `libros_autores` (tabla pivote libro ↔ autor)
   - `prestamos` (id_usuario, id_libro, fechas de préstamo/devolución, estado, observaciones)
4. Crea usuarios con contraseñas generadas mediante `password_hash`. Guarda sus roles en `tipo_usuario` (`ADMIN`, `BIBLIOTECARIO`, `ESTUDIANTE`). Puedes tomar como referencia los correos listados en `USUARIOS.txt` para poblar datos de ejemplo.【F:USUARIOS.txt†L1-L9】

### Usuarios por defecto

En la base de datos se incluyen cuentas de ejemplo (almacenan la contraseña como hash con `password_hash`). Los registros iniciales listados en `USUARIOS.txt` utilizan hashes simbólicos (`HASH_FAKE_*`); actualízalos con el hash de la contraseña en texto plano que definas para cada usuario.【F:USUARIOS.txt†L1-L9】

| Usuario (email)                 | Rol            | Hash inicial (USUARIOS.txt) |
|---------------------------------|----------------|-----------------------------|
| ana.lopez@bibliotech.com        | ESTUDIANTE     | `HASH_FAKE_1`               |
| carlos.perez@bibliotech.com     | ESTUDIANTE     | `HASH_FAKE_2`               |
| laura.martinez@bibliotech.com   | BIBLIOTECARIO  | `HASH_FAKE_3`               |
| admin@bibliotech.com            | ADMIN          | `HASH_FAKE_4`               |

## Ejecución
- Con servidor embebido de PHP:
  ```bash
  php -S localhost:8000 -t public
  ```
  Luego abre `http://localhost:8000`.
- Con Apache/Nginx apunta el DocumentRoot al directorio `public/` y asegúrate de que las rutas con parámetros `c` (controlador) y `a` (acción) queden disponibles.

## Estructura principal
- `public/index.php`: punto de entrada que enruta a los controladores según los parámetros `c` y `a` y verifica autenticación y permisos.【F:public/index.php†L1-L59】
- `app/Config/`: configuración global (`config.php`) y clase de conexión `Cnn` para crear la instancia PDO.【F:app/Config/Cnn.php†L1-L42】
- `app/Controllers/`: controladores para libros, autores, categorías, préstamos, usuarios, autenticación y página de inicio.
- `app/Repositories/`: capa de acceso a datos que interactúa con las tablas; por ejemplo `PrestamoRepository` gestiona CRUD y estados de préstamos.【F:app/Repositories/PrestamoRepository.php†L1-L131】
- `app/Models/`: modelos simples que representan entidades de la base de datos, como `Libro`, `Usuario` o `Prestamo`.【F:app/Models/Libro.php†L1-L97】
- `view/`: plantillas PHP para cada módulo y un layout común en `view/layout/main_layout.php`.

## Roles y permisos
El control de acceso se centraliza en `App\Security\AccessControl`. Los permisos predeterminados son:
- **ADMIN**: acceso completo a todos los controladores/acciones.
- **BIBLIOTECARIO**: CRUD de libros, categorías, autores y préstamos; sin gestión de usuarios.【F:app/Security/AccessControl.php†L17-L46】
- **ESTUDIANTE**: lectura de libros y creación/consulta de sus propios préstamos.【F:app/Security/AccessControl.php†L48-L66】

## Flujo de autenticación
- Las rutas públicas están limitadas al controlador `auth`. Cualquier usuario no autenticado es redirigido a `AuthController::login`.【F:public/index.php†L23-L34】
- El método `authenticate` valida el correo y contraseña usando `password_verify` y almacena los datos mínimos en sesión antes de redirigir al inicio.【F:app/Controllers/AuthController.php†L25-L63】
- El cierre de sesión limpia la variable de sesión `usuario` y regenera el ID de sesión.【F:app/Controllers/AuthController.php†L65-L73】

## Funcionalidades clave
- Listado, creación, edición y eliminación de libros, incluyendo asignación de autores y categorías.【F:app/Controllers/LibroController.php†L23-L120】
- Gestión de autores, categorías y usuarios mediante sus respectivos repositorios y controladores.
- Registro y seguimiento de préstamos con cambio de estado a devuelto o atrasado desde `PrestamoRepository`.【F:app/Repositories/PrestamoRepository.php†L93-L131】

## Notas de desarrollo
- El autoloading se resuelve manualmente en `app/autoload.php` sin Composer.【F:app/autoload.php†L1-L22】
- Para añadir nuevas rutas, define el controlador en `public/index.php` y crea la clase correspondiente en `app/Controllers/` junto con su vista.

## Licencia
Este proyecto es de uso educativo. Ajusta o reemplaza esta sección con la licencia que corresponda a tu caso de uso.