# Backend TinyPath - OpenSource

TinyPath es un backend desarrollado con Laravel para gestionar un servicio de acortamiento de enlaces. Permite a los usuarios generar enlaces cortos, hacer seguimiento de métricas y gestionar sus propios enlaces.

## 🚀 Características

- Generación de enlaces cortos con expiración opcional.
- Redirección automática de enlaces acortados.
- Estadísticas básicas de acceso (clics, origen, fecha y hora).
- Gestión de enlaces para usuarios registrados.
- Acceso para usuarios anónimos con restricciones.
- API REST para la integración con el frontend en Angular.

## 📌 Tecnologías Usadas

- **Laravel 12** (Framework PHP)
- **MySQL** (Base de datos relacional)
- **Sanctum** (Autenticación de API)
- **Redis** (Próximamente)

## 🔧 Instalación y Configuración

### 1. Clonar el repositorio

```bash
 git clone https://github.com/franciscorojas27/backend-tinypath.git
 cd backend-tinypath
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el entorno

Copia el archivo `.env.example` a `.env` y configura la base de datos y otros parámetros:

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` y ajusta las credenciales de la base de datos y otros servicios.

### 4. Migrar la base de datos

```bash
php artisan migrate --seed
```

### 5. Ejecutar el servidor de desarrollo

```bash
php artisan serve
```

El backend estará disponible en `http://127.0.0.1:8000`

## 📡 API Endpoints

| Método | Ruta                             | Descripción                                       |
|--------|----------------------------------|---------------------------------------------------|
| POST   | /api/login                       | Iniciar sesión                                    |
| POST   | /api/logout                      | Cerrar sesión                                     |
| POST   | /api/register                    | Registrar un nuevo usuario                        |
| GET/HEAD | /api/shortLinks                  | Obtener todos los enlaces cortos del usuario    |
| POST   | /api/shortLinks                  | Crear un nuevo enlace corto                       |
| PUT    | /api/shortLinks/{shortLink}      | Actualizar un enlace corto                        |
| DELETE | /api/shortLinks/{shortLink}      | Eliminar un enlace corto                          |
| GET/HEAD | /api/user                        | Obtener los datos del usuario                   |
| PUT    | /api/user                        | Actualizar los datos del usuario                  |
| DELETE | /api/user                        | Eliminar el usuario                               |
| GET/HEAD | /api/verify-token              | Verificar el estado del token                     |
| GET/HEAD | /sanctum/csrf-cookie           | Obtener el cookie CSRF                            |
| GET/HEAD | /storage/{path}                | Acceder a archivos en almacenamiento              |
| GET/HEAD | /up                            | Verificar si el servidor está en línea            |
| GET/HEAD | /{shortLink}                   | Redirigir al enlace corto                         |

### Ejemplo de respuesta para `/api/shortLinks`

```json
{
  "shortLink": "abc123",
  "original_url": "https://example.com",
  "created_at": "2025-03-13T12:00:00Z",
  "stats": {
    "clicks": 123,
    "unique_visitors": 45,
    "created_at": "2025-03-13T12:00:00Z"
  }
}
```

Para más detalles sobre los endpoints, consulta la documentación en la API (Próximamente con Swagger).

## 📄 Licencia

Este proyecto está licenciado bajo la **MIT License** - consulta el archivo [LICENSE](LICENSE) para más detalles.

## 💡 Contribuciones

¡Las contribuciones son bienvenidas! Si deseas colaborar, por favor abre un issue o envía un pull request.

## 📞 Contacto

- Email: <franciscorjscontacto@gmail.com>
- GitHub: [Francisco Rojas](https://github.com/franciscorojas27)

---
Desarrollado con ❤️ por Francisco Rojas
