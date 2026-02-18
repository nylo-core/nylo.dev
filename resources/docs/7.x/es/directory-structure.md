# Directory Structure

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a la estructura de directorios")
- [Directorio raiz](#root-directory "Directorio raiz")
- [El directorio lib](#lib-directory "El directorio lib")
  - [app](#app-directory "Directorio app")
  - [bootstrap](#bootstrap-directory "Directorio bootstrap")
  - [config](#config-directory "Directorio config")
  - [resources](#resources-directory "Directorio resources")
  - [routes](#routes-directory "Directorio routes")
- [Directorio assets](#assets-directory "Directorio assets")
- [Helpers de assets](#asset-helpers "Helpers de assets")


<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} utiliza una estructura de directorios limpia y organizada inspirada en <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Esta estructura ayuda a mantener la consistencia entre proyectos y facilita encontrar archivos.

<div id="root-directory"></div>

## Directorio raiz

```
nylo_app/
├── android/          # Android platform files
├── assets/           # Images, fonts, and other assets
├── ios/              # iOS platform files
├── lang/             # Language/translation JSON files
├── lib/              # Dart application code
├── test/             # Test files
├── .env              # Environment variables
├── pubspec.yaml      # Dependencies and project config
└── ...
```

<div id="lib-directory"></div>

## El directorio lib

La carpeta `lib/` contiene todo el codigo Dart de tu aplicacion:

```
lib/
├── app/              # Application logic
├── bootstrap/        # Boot configuration
├── config/           # Configuration files
├── resources/        # UI components
├── routes/           # Route definitions
└── main.dart         # Application entry point
```

<div id="app-directory"></div>

### app/

El directorio `app/` contiene la logica principal de tu aplicacion:

| Directorio | Proposito |
|-----------|---------|
| `commands/` | Comandos personalizados de Metro CLI |
| `controllers/` | Controladores de pagina para logica de negocio |
| `events/` | Clases de eventos para el sistema de eventos |
| `forms/` | Clases de formularios con validacion |
| `models/` | Clases de modelos de datos |
| `networking/` | Servicios API y configuracion de red |
| `networking/dio/interceptors/` | Interceptores HTTP de Dio |
| `providers/` | Proveedores de servicio iniciados al arrancar la app |
| `services/` | Clases de servicios generales |

<div id="bootstrap-directory"></div>

### bootstrap/

El directorio `bootstrap/` contiene archivos que configuran como arranca tu aplicacion:

| Archivo | Proposito |
|------|---------|
| `boot.dart` | Configuracion de la secuencia de arranque principal |
| `decoders.dart` | Registro de decoders de modelos y API |
| `env.g.dart` | Configuracion de entorno cifrada generada |
| `events.dart` | Registro de eventos |
| `extensions.dart` | Extensiones personalizadas |
| `helpers.dart` | Funciones auxiliares personalizadas |
| `providers.dart` | Registro de proveedores |
| `theme.dart` | Configuracion de temas |

<div id="config-directory"></div>

### config/

El directorio `config/` contiene la configuracion de la aplicacion:

| Archivo | Proposito |
|------|---------|
| `app.dart` | Configuracion principal de la app |
| `design.dart` | Diseno de la app (fuente, logo, loader) |
| `localization.dart` | Configuracion de idioma y locale |
| `storage_keys.dart` | Definiciones de claves de almacenamiento local |
| `toast_notification.dart` | Estilos de notificaciones toast |

<div id="resources-directory"></div>

### resources/

El directorio `resources/` contiene componentes de UI:

| Directorio | Proposito |
|-----------|---------|
| `pages/` | Widgets de pagina (pantallas) |
| `themes/` | Definiciones de temas |
| `themes/light/` | Colores del tema claro |
| `themes/dark/` | Colores del tema oscuro |
| `widgets/` | Componentes de widgets reutilizables |
| `widgets/buttons/` | Widgets de botones personalizados |
| `widgets/bottom_sheet_modals/` | Widgets de modales de hoja inferior |

<div id="routes-directory"></div>

### routes/

El directorio `routes/` contiene la configuracion de enrutamiento:

| Archivo/Directorio | Proposito |
|----------------|---------|
| `router.dart` | Definiciones de rutas |
| `guards/` | Clases de route guard |

<div id="assets-directory"></div>

## Directorio assets

El directorio `assets/` almacena archivos estaticos:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Registrar assets

Los assets se registran en `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helpers de assets

{{ config('app.name') }} proporciona helpers para trabajar con assets.

### Assets de imagenes

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Assets generales

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Archivos de idioma

Los archivos de idioma se almacenan en `lang/` en la raiz del proyecto:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Consulta [Localizacion](/docs/7.x/localization) para mas detalles.
