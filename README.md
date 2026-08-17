# Ticket System

Sistema web de gestión de tickets de soporte desarrollado con **Laravel 12, Jetstream y Livewire**.

El proyecto permite a usuarios registrar incidencias, consultar sus tickets y dar seguimiento a solicitudes de soporte, mientras administradores y técnicos cuentan con diferentes niveles de acceso para gestionar y atender los tickets.

## Características actuales

### Autenticación

* Registro de usuarios
* Inicio y cierre de sesión
* Gestión de perfil mediante Laravel Jetstream
* Protección de rutas autenticadas

### Roles

El sistema cuenta actualmente con tres roles:

* **Administrador**
* **Técnico**
* **Usuario**

Cada rol tiene diferentes permisos dentro de la aplicación.

#### Administrador

* Visualiza todos los tickets
* Administra categorías
* Puede acceder a cualquier ticket
* Asigna tickets a técnicos
* Puede reasignar tickets

#### Técnico

* Visualiza los tickets que tiene asignados
* Puede acceder únicamente a tickets asignados a él

#### Usuario

* Crea nuevos tickets
* Visualiza sus propios tickets
* Puede acceder únicamente a tickets creados por él

## Gestión de categorías

Los administradores pueden:

* Crear categorías
* Editar categorías
* Activar o desactivar categorías
* Eliminar categorías

Ejemplos:

* Hardware
* Software
* Redes
* Accesos
* Correo electrónico

## Gestión de tickets

Cada ticket contiene:

* Número único
* Usuario que reportó el problema
* Categoría
* Técnico asignado
* Título
* Descripción
* Prioridad
* Estado
* Fecha de resolución
* Fecha de cierre

### Prioridades

* Baja
* Media
* Alta
* Crítica

### Estados

* Abierto
* Asignado
* En proceso
* En espera
* Resuelto
* Cerrado

Actualmente, cuando un administrador asigna un técnico a un ticket abierto, el ticket cambia automáticamente de:

```text
OPEN → ASSIGNED
```

## Listado de tickets

El listado utiliza **Livewire** para proporcionar una interfaz dinámica.

Incluye:

* Búsqueda por número de ticket
* Búsqueda por título
* Filtro por estado
* Filtro por prioridad
* Filtro por categoría
* Paginación
* Actualización dinámica sin recargar completamente la página

La información mostrada depende del rol:

```text
Administrador → todos los tickets

Técnico → tickets asignados

Usuario → tickets creados por él
```

## Seguridad y autorización

El proyecto utiliza:

* Middleware de roles
* Laravel Policies
* Route Model Binding
* Validación mediante Form Requests
* Validación de datos en componentes Livewire
* Enums para roles, estados y prioridades

La autorización de tickets está centralizada mediante `TicketPolicy`.

Un ticket puede ser consultado por:

```text
Administrador
      ✅

Usuario propietario
      ✅

Técnico asignado
      ✅

Otro usuario
      ❌
```

Esto evita que un usuario pueda acceder a tickets ajenos simplemente modificando la URL.

## Tecnologías

* PHP 8.2+
* Laravel 12
* Laravel Jetstream
* Livewire
* Blade
* Tailwind CSS
* MySQL
* Vite
* Node.js
* Composer

## Requisitos

Antes de instalar el proyecto necesitas:

```text
PHP >= 8.2
Composer
Node.js
npm
MySQL
```

## Instalación

Clona el repositorio:

```bash
git clone [URL_DEL_REPOSITORIO](https://github.com/21Edvan/ticket-system)
```

Entra al proyecto:

```bash
cd ticket-system
```

Instala las dependencias de PHP:

```bash
composer install
```

Instala las dependencias frontend:

```bash
npm install
```

Crea el archivo de entorno:

```bash
cp .env.example .env
```

En Windows también puedes copiar manualmente:

```text
.env.example → .env
```

Genera la clave de Laravel:

```bash
php artisan key:generate
```

## Base de datos

Crea una base de datos MySQL, por ejemplo:

```text
ticket_system
```

Configura `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_system
DB_USERNAME=root
DB_PASSWORD=
```

Ejecuta las migraciones:

```bash
php artisan migrate
```

## Ejecutar el proyecto

Compila los recursos frontend:

```bash
npm run build
```

Para desarrollo también puedes utilizar:

```bash
npm run dev
```

Levanta Laravel:

```bash
php artisan serve
```

La aplicación estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

## Estructura principal

```text
app/
├── Enums/
│   ├── TicketPriority.php
│   ├── TicketStatus.php
│   └── UserRole.php
│
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
│
├── Livewire/
│   └── Tickets/
│
├── Models/
│   ├── Category.php
│   ├── Ticket.php
│   └── User.php
│
└── Policies/
    └── TicketPolicy.php
```

## Flujo actual

```text
Usuario
   │
   └── Crea ticket
           │
           ▼
         OPEN
           │
           │ Administrador asigna técnico
           ▼
       ASSIGNED
           │
           ▼
        Técnico
```

## Roadmap

Próximas funcionalidades previstas:

* [ ] Cambio de estado por parte de técnicos
* [ ] Comentarios en tickets
* [ ] Historial de actividad
* [ ] Archivos adjuntos
* [ ] Notificaciones
* [ ] Dashboard para administradores
* [ ] Dashboard para técnicos
* [ ] Estadísticas y reportes
* [ ] Gestión administrativa de usuarios
* [ ] Especialidades de técnicos por categoría
* [ ] Asignación automática de tickets
* [ ] Distribución automática según carga de trabajo
* [ ] Reasignación automática
* [ ] SLA y tiempos de respuesta
* [ ] Tests automatizados para permisos y tickets

### Asignación automática

Una de las funcionalidades futuras principales será evitar que la asignación dependa exclusivamente de un administrador.

El flujo previsto es:

```text
Nuevo ticket
     │
     ▼
Buscar técnicos compatibles
     │
     ▼
Evaluar disponibilidad
     │
     ▼
Evaluar carga de trabajo
     │
     ▼
Seleccionar técnico
     │
     ▼
Asignación automática
```

La asignación manual seguirá disponible como mecanismo de control y reasignación.

## Estado del proyecto

El proyecto se encuentra actualmente **en desarrollo** y está siendo construido progresivamente con fines de aprendizaje y práctica de arquitectura de aplicaciones con Laravel.

## Licencia

Proyecto desarrollado con fines educativos y de práctica.
