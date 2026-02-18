# App Icons

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Generar iconos de aplicacion](#generating-app-icons "Generar iconos de aplicacion")
- [Agregar tu icono de aplicacion](#adding-your-app-icon "Agregar tu icono de aplicacion")
- [Requisitos del icono de aplicacion](#app-icon-requirements "Requisitos del icono de aplicacion")
- [Configuracion](#configuration "Configuracion")
- [Contador de insignias](#badge-count "Contador de insignias")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 utiliza <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> para generar iconos de aplicacion para iOS y Android a partir de una sola imagen fuente.

Tu icono de aplicacion debe colocarse en el directorio `assets/app_icon/` con un tamano de **1024x1024 pixeles**.

<div id="generating-app-icons"></div>

## Generar iconos de aplicacion

Ejecuta el siguiente comando para generar iconos para todas las plataformas:

``` bash
dart run flutter_launcher_icons
```

Esto lee tu icono fuente de `assets/app_icon/` y genera:
- Iconos de iOS en `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Iconos de Android en `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Agregar tu icono de aplicacion

1. Crea tu icono como un archivo **PNG de 1024x1024**
2. Colocalo en `assets/app_icon/` (por ejemplo, `assets/app_icon/icon.png`)
3. Actualiza el `image_path` en tu `pubspec.yaml` si es necesario:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Ejecuta el comando de generacion de iconos

<div id="app-icon-requirements"></div>

## Requisitos del icono de aplicacion

| Atributo | Valor |
|-----------|-------|
| Formato | PNG |
| Tamano | 1024x1024 pixeles |
| Capas | Aplanado sin transparencia |

### Nombres de archivos

Manten los nombres de archivos simples sin caracteres especiales:
- `app_icon.png`
- `icon.png`

### Directrices de plataforma

Para requisitos detallados, consulta las directrices oficiales de cada plataforma:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Configuracion

Personaliza la generacion de iconos en tu `pubspec.yaml`:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

Consulta la <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">documentacion de flutter_launcher_icons</a> para todas las opciones disponibles.

<div id="badge-count"></div>

## Contador de insignias

{{ config('app.name') }} proporciona funciones auxiliares para gestionar los contadores de insignias de la aplicacion (el numero que se muestra en el icono de la app):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Soporte de plataforma

Los contadores de insignias son compatibles con:
- **iOS**: Soporte nativo
- **Android**: Requiere soporte del launcher (la mayoria de los launchers lo soportan)
- **Web**: No soportado

### Casos de uso

Escenarios comunes para contadores de insignias:
- Notificaciones no leidas
- Mensajes pendientes
- Articulos en el carrito
- Tareas incompletas

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
