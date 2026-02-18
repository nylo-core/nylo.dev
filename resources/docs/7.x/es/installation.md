# Installation

---

<a name="section-1"></a>
- [Instalar](#install "Instalar")
- [Ejecutar el proyecto](#running-the-project "Ejecutar el proyecto")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Instalar

### 1. Instalar nylo_installer globalmente

``` bash
dart pub global activate nylo_installer
```

Esto instala la herramienta CLI de {{ config('app.name') }} globalmente en tu sistema.

### 2. Crear un nuevo proyecto

``` bash
nylo new my_app
```

Este comando clona la plantilla de {{ config('app.name') }}, configura el proyecto con el nombre de tu aplicación e instala todas las dependencias automáticamente.

### 3. Configurar el alias de Metro CLI

``` bash
cd my_app
nylo init
```

Esto configura el comando `metro` para tu proyecto, permitiéndote usar los comandos de Metro CLI sin la sintaxis completa de `dart run`.

Después de la instalación, tendrás una estructura de proyecto Flutter completa con:
- Enrutamiento y navegación preconfigurados
- Plantilla de servicio API
- Configuración de temas y localización
- Metro CLI para generación de código


<div id="running-the-project"></div>

## Ejecutar el proyecto

Los proyectos de {{ config('app.name') }} se ejecutan como cualquier aplicación estándar de Flutter.

### Usando la terminal

``` bash
flutter run
```

### Usando un IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Ejecución y depuración</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Ejecutar aplicación sin puntos de interrupción</a>

Si la compilación es exitosa, la aplicación mostrará la pantalla de inicio predeterminada de {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} incluye una herramienta CLI llamada **Metro** para generar archivos del proyecto.

### Ejecutar Metro

``` bash
metro
```

Esto muestra el menú de Metro:

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referencia de comandos de Metro

| Comando | Descripción |
|---------|-------------|
| `make:page` | Crear una nueva página |
| `make:stateful_widget` | Crear un widget con estado |
| `make:stateless_widget` | Crear un widget sin estado |
| `make:state_managed_widget` | Crear un widget con gestión de estado |
| `make:navigation_hub` | Crear un hub de navegación (nav inferior) |
| `make:journey_widget` | Crear un widget de recorrido para hub de navegación |
| `make:bottom_sheet_modal` | Crear un modal de hoja inferior |
| `make:button` | Crear un widget de botón personalizado |
| `make:form` | Crear un formulario con validación |
| `make:model` | Crear una clase de modelo |
| `make:provider` | Crear un proveedor |
| `make:api_service` | Crear un servicio API |
| `make:controller` | Crear un controlador |
| `make:event` | Crear un evento |
| `make:theme` | Crear un tema |
| `make:route_guard` | Crear un guard de ruta |
| `make:config` | Crear un archivo de configuración |
| `make:interceptor` | Crear un interceptor de red |
| `make:command` | Crear un comando personalizado de Metro |
| `make:env` | Generar configuración de entorno desde .env |

### Ejemplo de uso

``` bash
# Crear una nueva página
metro make:page settings_page

# Crear un modelo
metro make:model User

# Crear un servicio API
metro make:api_service user_api_service
```
