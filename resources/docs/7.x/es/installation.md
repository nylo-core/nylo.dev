# Installation

---

<a name="section-1"></a>
- [Instalar](#install "Instalar")
- [Ejecutar el proyecto](#running-the-project "Ejecutar el proyecto")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [Limpiar un proyecto](#nylo-clean "Limpiar un proyecto")
  - [Actualizar los pods de iOS](#nylo-ios-pod-refresh "Actualizar los pods de iOS")
  - [Ejecutar Metro sin el alias](#nylo-metro "Ejecutar Metro sin el alias")
  - [Actualizar la CLI](#nylo-self-update "Actualizar la CLI")

<div id="install"></div>

Tres comandos te llevan de una carpeta vacía a una aplicación Flutter en ejecución, con enrutamiento, red, temas y generación de código ya configurados.

<x-doc-strip label="Antes de empezar" items="Flutter SDK instalado, Dart 3" linkText="Requisitos completos" linkHref="/es/docs/{{ $version }}/requirements" />

## Instalar

Ejecuta estos comandos en orden. Puedes volver a ejecutar cualquiera de ellos de forma segura.

<x-doc-steps>
<x-doc-step number="1" title="Instalar Nylo CLI globalmente">
Instala el comando `nylo` en tu sistema.

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

Reinicia tu terminal después, o ejecuta el comando `source` que `nylo init` imprime.
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


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: la etiqueta "Requires" del x-doc-strip no tiene una traducción establecida en esta locale -->
<x-doc-strip label="Requiere" items="nylo_installer 1.8.0+" linkText="Actualizar la CLI" linkHref="#nylo-self-update" />

El comando `nylo` del paso 1 hace más que crear proyectos. Ejecútalo desde el directorio de tu proyecto para limpiar builds, actualizar los pods de iOS, ejecutar pruebas y verificar tus traducciones.

<x-doc-commands title="Comandos de proyecto" rows="
nylo new | Crear un nuevo proyecto
nylo init | Configurar el alias de metro en tu shell
nylo clean | Ejecutar flutter clean y flutter pub get
nylo ios:pod-refresh | Reinstalar tus pods de iOS
nylo metro | Ejecutar un comando de Metro sin el alias
" />

<x-doc-commands title="Pruebas y traducciones" rows="
nylo test | Formatear y ejecutar tus pruebas
nylo locale:find-untranslated | Encontrar texto que no está envuelto en .tr()
nylo locale:check-missing-keys | Encontrar claves que faltan en tus archivos de idioma
" />

<x-doc-commands title="La CLI en sí misma" rows="
nylo self-update | Actualizar nylo a la última versión
nylo --version | Mostrar la versión instalada
" />

Los comandos que aceptan opciones las listan con `-h`, por ejemplo `nylo test -h`. Las pruebas se explican en [Ejecutar pruebas](/docs/{{ $version }}/testing#running-tests), y los comandos `locale:` en [Auditar traducciones](/docs/{{ $version }}/localization#auditing-translations).

<div id="nylo-clean"></div>

### Limpiar un proyecto

`nylo clean` ejecuta `flutter clean` y luego `flutter pub get`. Agrega un flag de plataforma cuando una limpieza normal no sea suficiente:

``` bash
# flutter clean, luego flutter pub get
nylo clean

# También elimina ios/Pods, ios/.symlinks y ios/Podfile.lock, luego ejecuta pod install --repo-update
nylo clean --ios

# También ejecuta gradlew clean en android/
nylo clean --android

# Ambas cosas anteriores
nylo clean --all
```

Si un proyecto no tiene carpeta `ios/` o `android/`, esa plataforma se omite con una advertencia. `pod install` solo se ejecuta en macOS.

<div id="nylo-ios-pod-refresh"></div>

### Actualizar los pods de iOS

Cuando CocoaPods se desincroniza, `nylo ios:pod-refresh` elimina `ios/Pods`, `ios/.symlinks` y `ios/Podfile.lock`, y luego ejecuta `pod install --repo-update` en la carpeta `ios/`:

``` bash
nylo ios:pod-refresh
```

Ejecútalo desde la raíz de tu proyecto en macOS. A diferencia de `nylo clean --ios`, no ejecuta `flutter clean` ni `flutter pub get`.

<div id="nylo-metro"></div>

### Ejecutar Metro sin el alias

`nylo metro` pasa todo lo que sigue a Metro (`dart run nylo_framework:main`). Úsalo antes de haber ejecutado `nylo init`, o en Windows, donde `nylo init` no puede agregar el alias `metro` por ti:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### Actualizar la CLI

``` bash
nylo self-update
```

Esto verifica pub.dev y reinstala `nylo_installer` si hay una versión más reciente disponible. Al finalizar un comando, `nylo` también te avisa cuando hay una actualización disponible. Permanece en silencio cuando la salida no va a una terminal, para que el aviso nunca termine en logs de CI o en informes redirigidos.

Para ver qué versión tienes instalada:

``` bash
nylo --version
```
