# Metro CLI tool

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- [Instalar](#install "Instalar el alias de Metro para {{ config('app.name') }}")
- Comandos Make
  - [Make controller](#make-controller "Crear un nuevo controlador")
  - [Make model](#make-model "Crear un nuevo modelo")
  - [Make page](#make-page "Crear una nueva página")
  - [Make stateless widget](#make-stateless-widget "Crear un nuevo widget sin estado")
  - [Make stateful widget](#make-stateful-widget "Crear un nuevo widget con estado")
  - [Make journey widget](#make-journey-widget "Crear un nuevo widget de recorrido")
  - [Make API Service](#make-api-service "Crear un nuevo servicio API")
  - [Make Event](#make-event "Crear un nuevo evento")
  - [Make Provider](#make-provider "Crear un nuevo proveedor")
  - [Make Theme](#make-theme "Crear un nuevo tema")
  - [Make Forms](#make-forms "Crear un nuevo formulario")
  - [Make Route Guard](#make-route-guard "Crear un nuevo guard de ruta")
  - [Make Config File](#make-config-file "Crear un nuevo archivo de configuración")
  - [Make Command](#make-command "Crear un nuevo comando")
  - [Make State Managed Widget](#make-state-managed-widget "Crear un nuevo widget con gestión de estado")
  - [Make Navigation Hub](#make-navigation-hub "Crear un nuevo navigation hub")
  - [Make Bottom Sheet Modal](#make-bottom-sheet-modal "Crear un nuevo modal de hoja inferior")
  - [Make Button](#make-button "Crear un nuevo botón")
  - [Make Interceptor](#make-interceptor "Crear un nuevo interceptor")
  - [Make Env File](#make-env-file "Crear un nuevo archivo env")
  - [Make Key](#make-key "Generar APP_KEY")
- Íconos de aplicación
  - [Generar íconos de aplicación](#build-app-icons "Generar íconos de aplicación con Metro")
- Comandos personalizados
  - [Crear comandos personalizados](#creating-custom-commands "Crear comandos personalizados")
  - [Ejecutar comandos personalizados](#running-custom-commands "Ejecutar comandos personalizados")
  - [Agregar opciones a comandos](#adding-options-to-custom-commands "Agregar opciones a comandos personalizados")
  - [Agregar flags a comandos](#adding-flags-to-custom-commands "Agregar flags a comandos personalizados")
  - [Métodos helper](#custom-command-helper-methods "Métodos helper de comandos personalizados")
  - [Métodos de entrada interactiva](#interactive-input-methods "Métodos de entrada interactiva")
  - [Formato de salida](#output-formatting "Formato de salida")
  - [Helpers del sistema de archivos](#file-system-helpers "Helpers del sistema de archivos")
  - [Helpers de JSON y YAML](#json-yaml-helpers "Helpers de JSON y YAML")
  - [Helpers de conversión de caso](#case-conversion-helpers "Helpers de conversión de caso")
  - [Helpers de rutas del proyecto](#project-path-helpers "Helpers de rutas del proyecto")
  - [Helpers de plataforma](#platform-helpers "Helpers de plataforma")
  - [Comandos de Dart y Flutter](#dart-flutter-commands "Comandos de Dart y Flutter")
  - [Manipulación de archivos Dart](#dart-file-manipulation "Manipulación de archivos Dart")
  - [Helpers de directorios](#directory-helpers "Helpers de directorios")
  - [Helpers de validación](#validation-helpers "Helpers de validación")
  - [Scaffolding de archivos](#file-scaffolding "Scaffolding de archivos")
  - [Ejecutor de tareas](#task-runner "Ejecutor de tareas")
  - [Salida de tabla](#table-output "Salida de tabla")
  - [Barra de progreso](#progress-bar "Barra de progreso")


<div id="introduction"></div>

## Introducción

Metro es una herramienta CLI que funciona internamente en el framework {{ config('app.name') }}.
Proporciona muchas herramientas útiles para acelerar el desarrollo.

<div id="install"></div>

## Instalar

Cuando creas un nuevo proyecto Nylo usando `nylo init`, el comando `metro` se configura automáticamente para tu terminal. Puedes empezar a usarlo inmediatamente en cualquier proyecto Nylo.

Ejecuta `metro` desde el directorio de tu proyecto para ver todos los comandos disponibles:

``` bash
metro
```

Deberías ver una salida similar a la siguiente.

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
  make:key
```

<div id="make-controller"></div>

## Make controller

- [Crear un nuevo controlador](#making-a-new-controller "Crear un nuevo controlador con Metro")
- [Crear controlador forzadamente](#forcefully-make-a-controller "Crear un nuevo controlador forzadamente con Metro")
<div id="making-a-new-controller"></div>

### Crear un nuevo controlador

Puedes crear un nuevo controlador ejecutando lo siguiente en la terminal.

``` bash
metro make:controller profile_controller
```

Esto creará un nuevo controlador si no existe dentro del directorio `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Crear controlador forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un controlador existente si ya existe.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Make model

- [Crear un nuevo modelo](#making-a-new-model "Crear un nuevo modelo con Metro")
- [Crear modelo desde JSON](#make-model-from-json "Crear un nuevo modelo desde JSON con Metro")
- [Crear modelo forzadamente](#forcefully-make-a-model "Crear un nuevo modelo forzadamente con Metro")
<div id="making-a-new-model"></div>

### Crear un nuevo modelo

Puedes crear un nuevo modelo ejecutando lo siguiente en la terminal.

``` bash
metro make:model product
```

Colocará el modelo recién creado en `lib/app/models/`.

<div id="make-model-from-json"></div>

### Crear un modelo desde JSON

**Argumentos:**

Usar el flag `--json` o `-j` creará un nuevo modelo desde un payload JSON.

``` bash
metro make:model product --json
```

Luego, puedes pegar tu JSON en la terminal y generará un modelo para ti.

<div id="forcefully-make-a-model"></div>

### Crear modelo forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un modelo existente si ya existe.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Make page

- [Crear una nueva página](#making-a-new-page "Crear una nueva página con Metro")
- [Crear una página con controlador](#create-a-page-with-a-controller "Crear una nueva página con controlador con Metro")
- [Crear una página de autenticación](#create-an-auth-page "Crear una nueva página de autenticación con Metro")
- [Crear una página inicial](#create-an-initial-page "Crear una nueva página inicial con Metro")
- [Crear página forzadamente](#forcefully-make-a-page "Crear una nueva página forzadamente con Metro")

<div id="making-a-new-page"></div>

### Crear una nueva página

Puedes crear una nueva página ejecutando lo siguiente en la terminal.

``` bash
metro make:page product_page
```

Esto creará una nueva página si no existe dentro del directorio `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Crear una página con controlador

Puedes crear una nueva página con un controlador ejecutando lo siguiente en la terminal.

**Argumentos:**

Usar el flag `--controller` o `-c` creará una nueva página con un controlador.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Crear una página de autenticación

Puedes crear una nueva página de autenticación ejecutando lo siguiente en la terminal.

**Argumentos:**

Usar el flag `--auth` o `-a` creará una nueva página de autenticación.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Crear una página inicial

Puedes crear una nueva página inicial ejecutando lo siguiente en la terminal.

**Argumentos:**

Usar el flag `--initial` o `-i` creará una nueva página inicial.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Crear página forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá una página existente si ya existe.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Make stateless widget

- [Crear un nuevo widget sin estado](#making-a-new-stateless-widget "Crear un nuevo widget sin estado con Metro")
- [Crear widget sin estado forzadamente](#forcefully-make-a-stateless-widget "Crear un nuevo widget sin estado forzadamente con Metro")
<div id="making-a-new-stateless-widget"></div>

### Crear un nuevo widget sin estado

Puedes crear un nuevo widget sin estado ejecutando lo siguiente en la terminal.

``` bash
metro make:stateless_widget product_rating_widget
```

Lo anterior creará un nuevo widget si no existe dentro del directorio `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Crear widget sin estado forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un widget existente si ya existe.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Make stateful widget

- [Crear un nuevo widget con estado](#making-a-new-stateful-widget "Crear un nuevo widget con estado con Metro")
- [Crear widget con estado forzadamente](#forcefully-make-a-stateful-widget "Crear un nuevo widget con estado forzadamente con Metro")

<div id="making-a-new-stateful-widget"></div>

### Crear un nuevo widget con estado

Puedes crear un nuevo widget con estado ejecutando lo siguiente en la terminal.

``` bash
metro make:stateful_widget product_rating_widget
```

Lo anterior creará un nuevo widget si no existe dentro del directorio `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Crear widget con estado forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un widget existente si ya existe.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Make journey widget

- [Crear un nuevo widget de recorrido](#making-a-new-journey-widget "Crear un nuevo widget de recorrido con Metro")
- [Crear widget de recorrido forzadamente](#forcefully-make-a-journey-widget "Crear un nuevo widget de recorrido forzadamente con Metro")

<div id="making-a-new-journey-widget"></div>

### Crear un nuevo widget de recorrido

Puedes crear un nuevo widget de recorrido ejecutando lo siguiente en la terminal.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Ejemplo completo si tienes un BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Lo anterior creará un nuevo widget si no existe dentro del directorio `lib/resources/widgets/`.

El argumento `--parent` se usa para especificar el widget padre al que se agregará el nuevo widget de recorrido.

Ejemplo

``` bash
metro make:navigation_hub onboarding
```

Luego, agrega los nuevos widgets de recorrido.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Crear widget de recorrido forzadamente
**Argumentos:**
Usar el flag `--force` o `-f` sobrescribirá un widget existente si ya existe.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Make API Service

- [Crear un nuevo servicio API](#making-a-new-api-service "Crear un nuevo servicio API con Metro")
- [Crear un nuevo servicio API con modelo](#making-a-new-api-service-with-a-model "Crear un nuevo servicio API con modelo con Metro")
- [Crear servicio API usando Postman](#make-api-service-using-postman "Crear servicios API con Postman")
- [Crear servicio API forzadamente](#forcefully-make-an-api-service "Crear un nuevo servicio API forzadamente con Metro")

<div id="making-a-new-api-service"></div>

### Crear un nuevo servicio API

Puedes crear un nuevo servicio API ejecutando lo siguiente en la terminal.

``` bash
metro make:api_service user_api_service
```

Colocará el servicio API recién creado en `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Crear un nuevo servicio API con modelo

Puedes crear un nuevo servicio API con un modelo ejecutando lo siguiente en la terminal.

**Argumentos:**

Usar la opción `--model` o `-m` creará un nuevo servicio API con un modelo.

``` bash
metro make:api_service user --model="User"
```

Colocará el servicio API recién creado en `lib/app/networking/`.

### Crear servicio API forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un servicio API existente si ya existe.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Make event

- [Crear un nuevo evento](#making-a-new-event "Crear un nuevo evento con Metro")
- [Crear evento forzadamente](#forcefully-make-an-event "Crear un nuevo evento forzadamente con Metro")

<div id="making-a-new-event"></div>

### Crear un nuevo evento

Puedes crear un nuevo evento ejecutando lo siguiente en la terminal.

``` bash
metro make:event login_event
```

Esto creará un nuevo evento en `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Crear evento forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un evento existente si ya existe.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Make provider

- [Crear un nuevo proveedor](#making-a-new-provider "Crear un nuevo proveedor con Metro")
- [Crear proveedor forzadamente](#forcefully-make-a-provider "Crear un nuevo proveedor forzadamente con Metro")

<div id="making-a-new-provider"></div>

### Crear un nuevo proveedor

Crea nuevos proveedores en tu aplicación usando el siguiente comando.

``` bash
metro make:provider firebase_provider
```

Colocará el proveedor recién creado en `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Crear proveedor forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un proveedor existente si ya existe.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Make theme

- [Crear un nuevo tema](#making-a-new-theme "Crear un nuevo tema con Metro")
- [Crear tema forzadamente](#forcefully-make-a-theme "Crear un nuevo tema forzadamente con Metro")

<div id="making-a-new-theme"></div>

### Crear un nuevo tema

Puedes crear temas ejecutando lo siguiente en la terminal.

``` bash
metro make:theme bright_theme
```

Esto creará un nuevo tema en `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Crear tema forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un tema existente si ya existe.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Make Forms

- [Crear un nuevo formulario](#making-a-new-form "Crear un nuevo formulario con Metro")
- [Crear formulario forzadamente](#forcefully-make-a-form "Crear un nuevo formulario forzadamente con Metro")

<div id="making-a-new-form"></div>

### Crear un nuevo formulario

Puedes crear un nuevo formulario ejecutando lo siguiente en la terminal.

``` bash
metro make:form car_advert_form
```

Esto creará un nuevo formulario en `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Crear formulario forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un formulario existente si ya existe.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Make Route Guard

- [Crear un nuevo guard de ruta](#making-a-new-route-guard "Crear un nuevo guard de ruta con Metro")
- [Crear guard de ruta forzadamente](#forcefully-make-a-route-guard "Crear un nuevo guard de ruta forzadamente con Metro")

<div id="making-a-new-route-guard"></div>

### Crear un nuevo guard de ruta

Puedes crear un guard de ruta ejecutando lo siguiente en la terminal.

``` bash
metro make:route_guard premium_content
```

Esto creará un nuevo guard de ruta en `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Crear guard de ruta forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un guard de ruta existente si ya existe.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Make Config File

- [Crear un nuevo archivo de configuración](#making-a-new-config-file "Crear un nuevo archivo de configuración con Metro")
- [Crear archivo de configuración forzadamente](#forcefully-make-a-config-file "Crear un nuevo archivo de configuración forzadamente con Metro")

<div id="making-a-new-config-file"></div>

### Crear un nuevo archivo de configuración

Puedes crear un nuevo archivo de configuración ejecutando lo siguiente en la terminal.

``` bash
metro make:config shopping_settings
```

Esto creará un nuevo archivo de configuración en `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Crear archivo de configuración forzadamente

**Argumentos:**

Usar el flag `--force` o `-f` sobrescribirá un archivo de configuración existente si ya existe.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Make Command

- [Crear un nuevo comando](#making-a-new-command "Crear un nuevo comando con Metro")
- [Crear comando forzadamente](#forcefully-make-a-command "Crear un nuevo comando forzadamente con Metro")

<div id="making-a-new-command"></div>

### Crear un nuevo comando

Puedes crear un nuevo comando ejecutando lo siguiente en la terminal.

``` bash
metro make:command my_command
```

Esto creará un nuevo comando en `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Crear comando forzadamente

**Argumentos:**
Usar el flag `--force` o `-f` sobrescribirá un comando existente si ya existe.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Make State Managed Widget

Puedes crear un nuevo widget con gestión de estado ejecutando lo siguiente en la terminal.

``` bash
metro make:state_managed_widget product_rating_widget
```

Lo anterior creará un nuevo widget en `lib/resources/widgets/`.

Usar el flag `--force` o `-f` sobrescribirá un widget existente si ya existe.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Make Navigation Hub

Puedes crear un nuevo navigation hub ejecutando lo siguiente en la terminal.

``` bash
metro make:navigation_hub dashboard
```

Esto creará un nuevo navigation hub en `lib/resources/pages/` y agregará la ruta automáticamente.

**Argumentos:**

| Flag | Corto | Descripción |
|------|-------|-------------|
| `--auth` | `-a` | Crear como página de autenticación |
| `--initial` | `-i` | Crear como página inicial |
| `--force` | `-f` | Sobrescribir si existe |

``` bash
# Crear como página inicial
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Make Bottom Sheet Modal

Puedes crear un nuevo modal de hoja inferior ejecutando lo siguiente en la terminal.

``` bash
metro make:bottom_sheet_modal payment_options
```

Esto creará un nuevo modal de hoja inferior en `lib/resources/widgets/`.

Usar el flag `--force` o `-f` sobrescribirá un modal existente si ya existe.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Make Button

Puedes crear un nuevo widget de botón ejecutando lo siguiente en la terminal.

``` bash
metro make:button checkout_button
```

Esto creará un nuevo widget de botón en `lib/resources/widgets/`.

Usar el flag `--force` o `-f` sobrescribirá un botón existente si ya existe.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Make Interceptor

Puedes crear un nuevo interceptor de red ejecutando lo siguiente en la terminal.

``` bash
metro make:interceptor auth_interceptor
```

Esto creará un nuevo interceptor en `lib/app/networking/dio/interceptors/`.

Usar el flag `--force` o `-f` sobrescribirá un interceptor existente si ya existe.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Make Env File

Puedes crear un nuevo archivo de entorno ejecutando lo siguiente en la terminal.

``` bash
metro make:env .env.staging
```

Esto creará un nuevo archivo `.env` en la raíz de tu proyecto.

<div id="make-key"></div>

## Make Key

Genera una `APP_KEY` segura para encriptación de entorno. Esto se usa para archivos `.env` encriptados en v7.

``` bash
metro make:key
```

**Argumentos:**

| Flag / Opción | Corto | Descripción |
|---------------|-------|-------------|
| `--force` | `-f` | Sobrescribir APP_KEY existente |
| `--file` | `-e` | Archivo .env de destino (predeterminado: `.env`) |

``` bash
# Generar clave y sobrescribir existente
metro make:key --force

# Generar clave para un archivo env específico
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Generar íconos de aplicación

Puedes generar todos los íconos de aplicación para iOS y Android ejecutando el siguiente comando.

``` bash
dart run flutter_launcher_icons:main
```

Esto usa la configuración de <b>flutter_icons</b> en tu archivo `pubspec.yaml`.

<div id="custom-commands"></div>

## Comandos personalizados

Los comandos personalizados te permiten extender la CLI de Nylo con tus propios comandos específicos del proyecto. Esta funcionalidad te permite automatizar tareas repetitivas, implementar flujos de trabajo de despliegue o agregar cualquier funcionalidad personalizada directamente en las herramientas de línea de comandos de tu proyecto.

- [Crear comandos personalizados](#creating-custom-commands)
- [Ejecutar comandos personalizados](#running-custom-commands)
- [Agregar opciones a comandos](#adding-options-to-custom-commands)
- [Agregar flags a comandos](#adding-flags-to-custom-commands)
- [Métodos helper](#custom-command-helper-methods)

> **Nota:** Actualmente no puedes importar nylo_framework.dart en tus comandos personalizados, por favor usa ny_cli.dart en su lugar.

<div id="creating-custom-commands"></div>

## Crear comandos personalizados

Para crear un nuevo comando personalizado, puedes usar la funcionalidad `make:command`:

```bash
metro make:command current_time
```

Puedes especificar una categoría para tu comando usando la opción `--category`:

```bash
# Especificar una categoría
metro make:command current_time --category="project"
```

Esto creará un nuevo archivo de comando en `lib/app/commands/current_time.dart` con la siguiente estructura:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

El comando se registrará automáticamente en el archivo `lib/app/commands/commands.json`, que contiene una lista de todos los comandos registrados:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Ejecutar comandos personalizados

Una vez creado, puedes ejecutar tu comando personalizado usando el atajo de Metro o el comando completo de Dart:

```bash
metro app:current_time
```

Cuando ejecutas `metro` sin argumentos, verás tus comandos personalizados listados en el menú bajo la sección "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Para mostrar información de ayuda de tu comando, usa el flag `--help` o `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Agregar opciones a comandos

Las opciones permiten que tu comando acepte entrada adicional de los usuarios. Puedes agregar opciones a tu comando en el método `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Agregar una opción con valor predeterminado
  command.addOption(
    'environment',     // nombre de la opción
    abbr: 'e',         // abreviación de forma corta
    help: 'Target deployment environment', // texto de ayuda
    defaultValue: 'development',  // valor predeterminado
    allowed: ['development', 'staging', 'production'] // valores permitidos
  );

  return command;
}
```

Luego accede al valor de la opción en el método `handle` de tu comando:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Implementación del comando...
}
```

Ejemplo de uso:

```bash
metro project:deploy --environment=production
# o usando abreviación
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Agregar flags a comandos

Los flags son opciones booleanas que se pueden activar o desactivar. Agrega flags a tu comando usando el método `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // nombre del flag
    abbr: 'v',       // abreviación de forma corta
    help: 'Enable verbose output', // texto de ayuda
    defaultValue: false  // desactivado por defecto
  );

  return command;
}
```

Luego verifica el estado del flag en el método `handle` de tu comando:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Registro adicional...
  }

  // Implementación del comando...
}
```

Ejemplo de uso:

```bash
metro project:deploy --verbose
# o usando abreviación
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Métodos helper

La clase base `NyCustomCommand` proporciona varios métodos helper para asistir con tareas comunes:

#### Imprimir mensajes

Aquí hay algunos métodos para imprimir mensajes en diferentes colores:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Imprimir un mensaje informativo en texto azul |
| [`error`](#custom-command-helper-formatting)     | Imprimir un mensaje de error en texto rojo |
| [`success`](#custom-command-helper-formatting)   | Imprimir un mensaje de éxito en texto verde |
| [`warning`](#custom-command-helper-formatting)   | Imprimir un mensaje de advertencia en texto amarillo |

#### Ejecutar procesos

Ejecutar procesos y mostrar su salida en la consola:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Agregar un paquete a `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Agregar múltiples paquetes a `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Ejecutar un proceso externo y mostrar salida en la consola |
| [`prompt`](#custom-command-helper-prompt)    | Recopilar entrada de usuario como texto |
| [`confirm`](#custom-command-helper-confirm)   | Hacer una pregunta sí/no y devolver un resultado booleano |
| [`select`](#custom-command-helper-select)    | Presentar una lista de opciones y dejar que el usuario seleccione una |
| [`multiSelect`](#custom-command-helper-multi-select) | Permitir al usuario seleccionar múltiples opciones de una lista |

#### Solicitudes de red

Realizar solicitudes de red desde la consola:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Realizar una llamada API usando el cliente API de Nylo |


#### Spinner de carga

Mostrar un spinner de carga mientras se ejecuta una función:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Mostrar un spinner de carga mientras se ejecuta una función |
| [`createSpinner`](#manual-spinner-control) | Crear una instancia de spinner para control manual |

#### Helpers de comandos personalizados

También puedes usar los siguientes métodos helper para gestionar argumentos de comandos:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Obtener un valor de cadena de los argumentos del comando |
| [`getBool`](#custom-command-helper-get-bool)   | Obtener un valor booleano de los argumentos del comando |
| [`getInt`](#custom-command-helper-get-int)    | Obtener un valor entero de los argumentos del comando |
| [`sleep`](#custom-command-helper-sleep) | Pausar la ejecución por una duración especificada |


### Ejecutar procesos externos

```dart
// Ejecutar un proceso con salida mostrada en la consola
await runProcess('flutter build web --release');

// Ejecutar un proceso silenciosamente
await runProcess('flutter pub get', silent: true);

// Ejecutar un proceso en un directorio específico
await runProcess('git pull', workingDirectory: './my-project');
```

### Gestión de paquetes

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Agregar un paquete a pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Agregar un paquete de desarrollo a pubspec.yaml
addPackage('build_runner', dev: true);

// Agregar múltiples paquetes a la vez
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Formato de salida

```dart
// Imprimir mensajes de estado con codificación de colores
info('Processing files...');    // Texto azul
error('Operation failed');      // Texto rojo
success('Deployment complete'); // Texto verde
warning('Outdated package');    // Texto amarillo
```

<div id="interactive-input-methods"></div>

## Métodos de entrada interactiva

La clase base `NyCustomCommand` proporciona varios métodos para recopilar entrada del usuario en la terminal. Estos métodos facilitan la creación de interfaces de línea de comandos interactivas para tus comandos personalizados.

<div id="custom-command-helper-prompt"></div>

### Entrada de texto

```dart
String prompt(String question, {String defaultValue = ''})
```

Muestra una pregunta al usuario y recopila su respuesta de texto.

**Parámetros:**
- `question`: La pregunta o indicación a mostrar
- `defaultValue`: Valor predeterminado opcional si el usuario solo presiona Enter

**Devuelve:** La entrada del usuario como cadena, o el valor predeterminado si no se proporcionó entrada

**Ejemplo:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Confirmación

```dart
bool confirm(String question, {bool defaultValue = false})
```

Hace al usuario una pregunta sí/no y devuelve un resultado booleano.

**Parámetros:**
- `question`: La pregunta sí/no a hacer
- `defaultValue`: La respuesta predeterminada (true para sí, false para no)

**Devuelve:** `true` si el usuario respondió sí, `false` si respondió no

**Ejemplo:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // El usuario confirmó o presionó Enter (aceptando el predeterminado)
  await runProcess('flutter pub get');
} else {
  // El usuario declinó
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Selección única

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Presenta una lista de opciones y permite al usuario seleccionar una.

**Parámetros:**
- `question`: La indicación de selección
- `options`: Lista de opciones disponibles
- `defaultOption`: Selección predeterminada opcional

**Devuelve:** La opción seleccionada como cadena

**Ejemplo:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Selección múltiple

```dart
List<String> multiSelect(String question, List<String> options)
```

Permite al usuario seleccionar múltiples opciones de una lista.

**Parámetros:**
- `question`: La indicación de selección
- `options`: Lista de opciones disponibles

**Devuelve:** Una lista de las opciones seleccionadas

**Ejemplo:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Método helper de API

El método helper `api` simplifica la realización de solicitudes de red desde tus comandos personalizados.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Ejemplos de uso básico

### Solicitud GET

```dart
// Obtener datos
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Solicitud POST

```dart
// Crear un recurso
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Solicitud PUT

```dart
// Actualizar un recurso
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Solicitud DELETE

```dart
// Eliminar un recurso
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Solicitud PATCH

```dart
// Actualizar parcialmente un recurso
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Con parámetros de consulta

```dart
// Agregar parámetros de consulta
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Con spinner

```dart
// Usar con spinner para mejor interfaz
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Procesar los datos
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Funcionalidad de spinner

Los spinners proporcionan retroalimentación visual durante operaciones de larga duración en tus comandos personalizados. Muestran un indicador animado junto con un mensaje mientras tu comando ejecuta tareas asíncronas, mejorando la experiencia del usuario al mostrar progreso y estado.

- [Usar con spinner](#using-with-spinner)
- [Control manual de spinner](#manual-spinner-control)
- [Ejemplos](#spinner-examples)

<div id="using-with-spinner"></div>

## Usar con spinner

El método `withSpinner` te permite envolver una tarea asíncrona con una animación de spinner que se inicia automáticamente cuando la tarea comienza y se detiene cuando se completa o falla:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parámetros:**
- `task`: La función asíncrona a ejecutar
- `message`: Texto a mostrar mientras el spinner está en ejecución
- `successMessage`: Mensaje opcional a mostrar al completarse exitosamente
- `errorMessage`: Mensaje opcional a mostrar si la tarea falla

**Devuelve:** El resultado de la función de tarea

**Ejemplo:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Ejecutar una tarea con spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Tarea de larga duración (ej., analizar archivos del proyecto)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Continuar con los resultados
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Control manual de spinner

Para escenarios más complejos donde necesitas controlar el estado del spinner manualmente, puedes crear una instancia de spinner:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parámetros:**
- `message`: Texto a mostrar mientras el spinner está en ejecución

**Devuelve:** Una instancia de `ConsoleSpinner` que puedes controlar manualmente

**Ejemplo con control manual:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Crear una instancia de spinner
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // Primera tarea
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Segunda tarea
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Tercera tarea
    await runProcess('./deploy.sh', silent: true);

    // Completar exitosamente
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Manejar fallo
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Ejemplos

### Tarea simple con spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Instalar dependencias
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Múltiples operaciones consecutivas

```dart
@override
Future<void> handle(CommandResult result) async {
  // Primera operación con spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Segunda operación con spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Tercera operación con spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Flujo de trabajo complejo con control manual

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Ejecutar múltiples pasos con actualizaciones de estado
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Completar el proceso
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Usar spinners en tus comandos personalizados proporciona retroalimentación visual clara a los usuarios durante operaciones de larga duración, creando una experiencia de línea de comandos más pulida y profesional.

<div id="custom-command-helper-get-string"></div>

### Obtener un valor de cadena de opciones

```dart
String getString(String name, {String defaultValue = ''})
```

**Parámetros:**

- `name`: El nombre de la opción a recuperar
- `defaultValue`: Valor predeterminado opcional si la opción no se proporciona

**Devuelve:** El valor de la opción como cadena

**Ejemplo:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Obtener un valor booleano de opciones

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parámetros:**
- `name`: El nombre de la opción a recuperar
- `defaultValue`: Valor predeterminado opcional si la opción no se proporciona

**Devuelve:** El valor de la opción como booleano


**Ejemplo:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Obtener un valor entero de opciones

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parámetros:**
- `name`: El nombre de la opción a recuperar
- `defaultValue`: Valor predeterminado opcional si la opción no se proporciona

**Devuelve:** El valor de la opción como entero

**Ejemplo:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Pausar por una duración especificada

```dart
void sleep(int seconds)
```

**Parámetros:**
- `seconds`: El número de segundos a pausar

**Devuelve:** Nada

**Ejemplo:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Formato de salida

Más allá de los métodos básicos `info`, `error`, `success` y `warning`, `NyCustomCommand` proporciona helpers de salida adicionales:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Imprimir texto plano (sin color)
  line('Processing your request...');

  // Imprimir líneas en blanco
  newLine();       // una línea en blanco
  newLine(3);      // tres líneas en blanco

  // Imprimir un comentario silenciado (texto gris)
  comment('This is a background note');

  // Imprimir un cuadro de alerta prominente
  alert('Important: Please read carefully');

  // Ask es un alias para prompt
  final name = ask('What is your name?');

  // Entrada oculta para datos sensibles (ej., contraseñas, claves API)
  final apiKey = promptSecret('Enter your API key:');

  // Abortar el comando con un mensaje de error y código de salida
  if (name.isEmpty) {
    abort('Name is required');  // sale con código 1
  }
}
```

| Método | Descripción |
|--------|-------------|
| `line(String message)` | Imprimir texto plano sin color |
| `newLine([int count = 1])` | Imprimir líneas en blanco |
| `comment(String message)` | Imprimir texto silenciado/gris |
| `alert(String message)` | Imprimir un cuadro de alerta prominente |
| `ask(String question, {String defaultValue})` | Alias para `prompt` |
| `promptSecret(String question)` | Entrada oculta para datos sensibles |
| `abort([String? message, int exitCode = 1])` | Salir del comando con un error |

<div id="file-system-helpers"></div>

## Helpers del sistema de archivos

`NyCustomCommand` incluye helpers integrados del sistema de archivos para que no necesites importar manualmente `dart:io` para operaciones comunes.

### Leer y escribir archivos

```dart
@override
Future<void> handle(CommandResult result) async {
  // Verificar si existe un archivo
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Verificar si existe un directorio
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Leer un archivo (async)
  String content = await readFile('pubspec.yaml');

  // Leer un archivo (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // Escribir en un archivo (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Escribir en un archivo (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Agregar contenido a un archivo
  await appendFile('log.txt', 'New log entry\n');

  // Asegurar que un directorio existe (lo crea si falta)
  await ensureDirectory('lib/generated');

  // Eliminar un archivo
  await deleteFile('lib/generated/output.dart');

  // Copiar un archivo
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Método | Descripción |
|--------|-------------|
| `fileExists(String path)` | Devuelve `true` si el archivo existe |
| `directoryExists(String path)` | Devuelve `true` si el directorio existe |
| `readFile(String path)` | Leer archivo como cadena (async) |
| `readFileSync(String path)` | Leer archivo como cadena (sync) |
| `writeFile(String path, String content)` | Escribir contenido en archivo (async) |
| `writeFileSync(String path, String content)` | Escribir contenido en archivo (sync) |
| `appendFile(String path, String content)` | Agregar contenido a archivo |
| `ensureDirectory(String path)` | Crear directorio si no existe |
| `deleteFile(String path)` | Eliminar un archivo |
| `copyFile(String source, String destination)` | Copiar un archivo |

<div id="json-yaml-helpers"></div>

## Helpers de JSON y YAML

Lee y escribe archivos JSON y YAML con helpers integrados.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Leer un archivo JSON como Map
  Map<String, dynamic> config = await readJson('config.json');

  // Leer un archivo JSON como List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Escribir datos en un archivo JSON (con formato legible por defecto)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Escribir JSON compacto
  await writeJson('output.json', data, pretty: false);

  // Agregar un elemento a un archivo de arreglo JSON
  // Si el archivo contiene [{"name": "a"}], esto agrega a ese arreglo
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // evita duplicados por esta clave
  );

  // Leer un archivo YAML como Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Método | Descripción |
|--------|-------------|
| `readJson(String path)` | Leer archivo JSON como `Map<String, dynamic>` |
| `readJsonArray(String path)` | Leer archivo JSON como `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Escribir datos como JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Agregar a un archivo de arreglo JSON |
| `readYaml(String path)` | Leer archivo YAML como `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Helpers de conversión de caso

Convierte cadenas entre convenciones de nomenclatura sin importar el paquete `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Método | Formato de salida | Ejemplo |
|--------|-------------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helpers de rutas del proyecto

Getters para directorios estándar de proyectos {{ config('app.name') }}. Estos devuelven rutas relativas a la raíz del proyecto.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Construir una ruta personalizada relativa a la raíz del proyecto
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Propiedad | Ruta |
|-----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Resuelve una ruta relativa dentro del proyecto |

<div id="platform-helpers"></div>

## Helpers de plataforma

Verifica la plataforma y accede a variables de entorno.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Verificaciones de plataforma
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Directorio de trabajo actual
  info('Working in: $workingDirectory');

  // Leer variables de entorno del sistema
  String home = env('HOME', '/default/path');
}
```

| Propiedad / Método | Descripción |
|---------------------|-------------|
| `isWindows` | `true` si se ejecuta en Windows |
| `isMacOS` | `true` si se ejecuta en macOS |
| `isLinux` | `true` si se ejecuta en Linux |
| `workingDirectory` | Ruta del directorio de trabajo actual |
| `env(String key, [String defaultValue = ''])` | Leer variable de entorno del sistema |

<div id="dart-flutter-commands"></div>

## Comandos de Dart y Flutter

Ejecuta comandos comunes de CLI de Dart y Flutter como métodos helper. Cada uno devuelve el código de salida del proceso.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Formatear un archivo o directorio Dart
  await dartFormat('lib/app/models/user.dart');

  // Ejecutar dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Ejecutar flutter pub get
  await flutterPubGet();

  // Ejecutar flutter clean
  await flutterClean();

  // Compilar para un objetivo con argumentos adicionales
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Ejecutar flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // directorio específico
}
```

| Método | Descripción |
|--------|-------------|
| `dartFormat(String path)` | Ejecutar `dart format` en un archivo o directorio |
| `dartAnalyze([String? path])` | Ejecutar `dart analyze` |
| `flutterPubGet()` | Ejecutar `flutter pub get` |
| `flutterClean()` | Ejecutar `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Ejecutar `flutter build <target>` |
| `flutterTest([String? path])` | Ejecutar `flutter test` |

<div id="dart-file-manipulation"></div>

## Manipulación de archivos Dart

Helpers para editar archivos Dart programáticamente, útiles al construir herramientas de scaffolding.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Agregar una declaración de importación a un archivo Dart (evita duplicados)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Insertar código antes de la última llave de cierre en un archivo
  // Útil para agregar entradas a mapas de registro
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Verificar si un archivo contiene una cadena específica
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Verificar si un archivo coincide con un patrón regex
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Método | Descripción |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Agregar importación a archivo Dart (omite si ya está presente) |
| `insertBeforeClosingBrace(String filePath, String code)` | Insertar código antes de la última `}` en el archivo |
| `fileContains(String filePath, String identifier)` | Verificar si el archivo contiene una cadena |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verificar si el archivo coincide con un patrón |

<div id="directory-helpers"></div>

## Helpers de directorios

Helpers para trabajar con directorios y encontrar archivos.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Listar contenido del directorio
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // Listar recursivamente
  var allEntities = listDirectory('lib/', recursive: true);

  // Encontrar archivos que coincidan con criterios
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Encontrar archivos por patrón de nombre
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Eliminar un directorio recursivamente
  await deleteDirectory('build/');

  // Copiar un directorio (recursivo)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Método | Descripción |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Listar contenido del directorio |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Encontrar archivos que coincidan con criterios |
| `deleteDirectory(String path)` | Eliminar directorio recursivamente |
| `copyDirectory(String source, String destination)` | Copiar directorio recursivamente |

<div id="validation-helpers"></div>

## Helpers de validación

Helpers para validar y limpiar entrada del usuario para generación de código.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validar un identificador Dart
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Requerir un primer argumento no vacío
  String name = requireArgument(result, message: 'Please provide a name');

  // Limpiar un nombre de clase (PascalCase, eliminar sufijos)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Devuelve: 'User'

  // Limpiar un nombre de archivo (snake_case con extensión)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Devuelve: 'user_model.dart'
}
```

| Método | Descripción |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validar un nombre de identificador Dart |
| `requireArgument(CommandResult result, {String? message})` | Requerir primer argumento no vacío o abortar |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Limpiar y convertir a PascalCase un nombre de clase |
| `cleanFileName(String name, {String extension = '.dart'})` | Limpiar y convertir a snake_case un nombre de archivo |

<div id="file-scaffolding"></div>

## Scaffolding de archivos

Crea uno o varios archivos con contenido usando el sistema de scaffolding.

### Archivo único

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // no sobrescribir si existe
    successMessage: 'AuthService created',
  );
}
```

### Múltiples archivos

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

La clase `ScaffoldFile` acepta:

| Propiedad | Tipo | Descripción |
|-----------|------|-------------|
| `path` | `String` | Ruta del archivo a crear |
| `content` | `String` | Contenido del archivo |
| `successMessage` | `String?` | Mensaje mostrado al completarse |

<div id="task-runner"></div>

## Ejecutor de tareas

Ejecuta una serie de tareas con nombre con salida de estado automática.

### Ejecutor de tareas básico

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // detener la cadena si esto falla (predeterminado)
    ),
  ]);
}
```

### Ejecutor de tareas con spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

La clase `CommandTask` acepta:

| Propiedad | Tipo | Predeterminado | Descripción |
|-----------|------|----------------|-------------|
| `name` | `String` | requerido | Nombre de la tarea mostrado en la salida |
| `action` | `Future<void> Function()` | requerido | Función async a ejecutar |
| `stopOnError` | `bool` | `true` | Si se deben detener las tareas restantes si esta falla |

<div id="table-output"></div>

## Salida de tabla

Muestra tablas ASCII formateadas en la consola.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Salida:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Barra de progreso

Muestra una barra de progreso para operaciones con conteos de elementos conocidos.

### Barra de progreso manual

```dart
@override
Future<void> handle(CommandResult result) async {
  // Crear una barra de progreso para 100 elementos
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // incrementar en 1
  }

  progress.complete('All files processed');
}
```

### Procesar elementos con progreso

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Procesar elementos con seguimiento automático de progreso
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // procesar cada archivo
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Progreso síncrono

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // procesamiento síncrono
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

La clase `ConsoleProgressBar` proporciona:

| Método | Descripción |
|--------|-------------|
| `start()` | Iniciar la barra de progreso |
| `tick([int amount = 1])` | Incrementar progreso |
| `update(int value)` | Establecer progreso a un valor específico |
| `updateMessage(String newMessage)` | Cambiar el mensaje mostrado |
| `complete([String? completionMessage])` | Completar con mensaje opcional |
| `stop()` | Detener sin completar |
| `current` | Valor actual de progreso (getter) |
| `percentage` | Progreso como porcentaje (getter) |
