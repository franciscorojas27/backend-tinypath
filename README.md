<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
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
- **Redis** (Proximamente)

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

## 📡 API Endpoints

| Método | Ruta                             | Descripción                                        |
|--------|----------------------------------|----------------------------------------------------|
| POST   | /api/login                       | Iniciar sesión                                    |
| POST   | /api/logout                      | Cerrar sesión                                     |
| POST   | /api/register                    | Registrar un nuevo usuario                        |
| GET|HEAD | /api/shortLinks                  | Obtener todos los enlaces cortos del usuario      |
| POST   | /api/shortLinks                  | Crear un nuevo enlace corto                       |
| PUT    | /api/shortLinks/{shortLink}      | Actualizar un enlace corto                        |
| DELETE | /api/shortLinks/{shortLink}      | Eliminar un enlace corto                          |
| GET|HEAD | /api/user                        | Obtener los datos del usuario                     |
| PUT    | /api/user                        | Actualizar los datos del usuario                  |
| DELETE | /api/user                        | Eliminar el usuario                               |
| GET|HEAD | /api/verify-token                | Verificar el estado del token (opcional)          |
| GET|HEAD | /sanctum/csrf-cookie             | Obtener el cookie CSRF                            |
| GET|HEAD | /storage/{path}                  | Acceder a archivos en almacenamiento              |
| GET|HEAD | /up                              | Verificar si el servidor está en línea            |
| GET|HEAD | /{shortLink}                     | Redirigir al enlace corto                         |

### Ejemplo de respuesta para `/api/shortLinks`:

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
- Email: franciscorjscontacto@gmail.com
- GitHub: [Francisco Rojas](https://github.com/franciscorojas27)

---
Desarrollado con ❤️ por Francisco Rojas
>>>>>>> eab6152d6adcb81174725f6a30b67755a82ed97b
