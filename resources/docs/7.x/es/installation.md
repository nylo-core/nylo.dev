# Installation

---

<a name="section-1"></a>
- [Instalar](#install "Instalar")
- [Ejecutar el proyecto](#running-the-project "Ejecutar el proyecto")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Tres comandos te llevan de una carpeta vacía a una aplicación Flutter en ejecución, con enrutamiento, red, temas y generación de código ya configurados.

<x-doc-strip label="Antes de empezar" items="Flutter SDK instalado, Dart 3" linkText="Requisitos completos" linkHref="/es/docs/{{ $version }}/requirements" />

## Instalar

Ejecuta estos comandos en orden. Puedes volver a ejecutar cualquiera de ellos de forma segura.

<x-doc-steps>
<x-doc-step number="1" title="Instalar Nylo CLI globalmente">
Esto instala la herramienta CLI de {{ config('app.name') }} globalmente en tu sistema.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Crear un nuevo proyecto">
Este comando clona la plantilla de {{ config('app.name') }}, configura el proyecto con el nombre de tu aplicación e instala todas las dependencias automáticamente.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Configurar el alias de Metro">
Esto configura el comando `metro` para tu proyecto, permitiéndote usar los comandos de Metro CLI sin la sintaxis completa de `dart run`.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Qué obtienes" items="Enrutamiento y navegación preconfigurados
Plantilla de servicio API
Configuración de temas y localización
Metro CLI para generación de código" />


<div id="running-the-project"></div>

## Ejecutar el proyecto

Los proyectos de {{ config('app.name') }} se ejecutan como cualquier aplicación estándar de Flutter.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Si la compilación es exitosa, la aplicación mostrará la pantalla de inicio predeterminada de {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Abre la carpeta del proyecto, elige un dispositivo en el selector de destino y pulsa **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Documentación de Flutter: ejecución y depuración ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Abre la carpeta del proyecto y ejecuta **Debug: Start Without Debugging** desde la paleta de comandos.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Documentación de Flutter: ejecutar sin puntos de interrupción ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro genera archivos del proyecto por ti. Ejecútalo sin argumentos para ver el menú o llama directamente a un comando.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referencia de comandos

Cada comando recibe un nombre, por ejemplo, `metro make:page settings_page`

<x-doc-commands title="Comandos de widgets" rows="
metro make:page | Crear una nueva página
metro make:stateful_widget | Crear un widget con estado
metro make:stateless_widget | Crear un widget sin estado
metro make:state_managed_widget | Crear un widget con gestión de estado
metro make:navigation_hub | Crear un hub de navegación (nav inferior)
metro make:journey_widget | Crear un widget de recorrido para hub de navegación
metro make:bottom_sheet_modal | Crear un modal de hoja inferior
metro make:button | Crear un widget de botón personalizado
metro make:form | Crear un formulario con validación
" />

<x-doc-commands title="Comandos auxiliares" rows="
metro make:model | Crear una clase de modelo
metro make:provider | Crear un proveedor
metro make:api_service | Crear un servicio API
metro make:controller | Crear un controlador
metro make:event | Crear un evento
metro make:route_guard | Crear un guard de ruta
metro make:config | Crear un archivo de configuración
metro make:interceptor | Crear un interceptor de red
metro make:command | Crear un comando personalizado de Metro
metro make:env | Generar configuración de entorno desde .env
" />

### Ejemplo de uso

``` bash
# Crear una nueva página
metro make:page settings_page

# Crear un modelo
metro make:model User

# Crear un servicio API
metro make:api_service user_api_service
```
