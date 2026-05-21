# Deep Links

---

<a name="section-1"></a>

- [Introduccion](#introduction "Introduccion")
- [Primeros pasos](#getting-started "Primeros pasos")
  - [Activar los deep links](#enable-deep-links "Activar los deep links")
  - [Generar un proveedor](#generate-a-provider "Generar un proveedor")
- [Configuracion de plataformas](#platform-configuration "Configuracion de plataformas")
  - [Configuracion Android](#android-configuration "Configuracion Android")
  - [Configuracion iOS](#ios-configuration "Configuracion iOS")
- [Interceptar enlaces](#intercepting-links "Interceptar enlaces")
- [Ruta de reserva](#fallback-route "Ruta de reserva")
- [Pruebas](#testing "Pruebas")
- [Referencia de la API](#api-reference "Referencia de la API")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} incluye soporte de primera clase para deep links impulsado por [`app_links`](https://pub.dev/packages/app_links). Los URI entrantes de Android App Links, Universal Links de iOS, esquemas de URL personalizados y URLs web se capturan automaticamente y se enrutan a traves de tus rutas registradas.

Una sola linea activa el pipeline completo:

```dart
nylo.useDeepLinks();
```

Cuando llega un URI, {{ config('app.name') }} extrae la ruta y los parametros de consulta y llama a `routeTo()` con ellos. Los lanzamientos en frio (la app abierta por un enlace) se repiten una vez que el navegador esta listo, y los enlaces en caliente (recibidos mientras la app esta en ejecucion) se manejan de la misma forma.

<div id="getting-started"></div>

## Primeros pasos

<div id="enable-deep-links"></div>

### Activar los deep links

Dentro del metodo `setup` de cualquier proveedor, llama a `useDeepLinks()`:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

Registra el proveedor en `config/providers.dart` para que se ejecute durante el inicio de la app.

<div id="generate-a-provider"></div>

### Generar un proveedor

Metro genera el proveedor por ti:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Esto crea `lib/app/providers/deep_link_provider.dart` con `useDeepLinks()` pre-configurado y un ejemplo de `onIncomingLink` comentado. El proveedor tambien se agrega automaticamente a `config/providers.dart`.

<div id="platform-configuration"></div>

## Configuracion de plataformas

<div id="android-configuration"></div>

### Configuracion Android

Agrega un `<intent-filter>` a tu `<activity>` principal en `android/app/src/main/AndroidManifest.xml`. El ejemplo a continuacion acepta tanto Android App Links (`https://example.com/...`) como un esquema personalizado (`myapp://...`):

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

Para los App Links verificados, aloja un archivo `assetlinks.json` en `https://example.com/.well-known/assetlinks.json`. Consulta la <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">guia de Flutter App Links</a> para todos los detalles.

<div id="ios-configuration"></div>

### Configuracion iOS

Para los **esquemas de URL personalizados**, agrega una entrada `CFBundleURLTypes` en `ios/Runner/Info.plist`:

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

Para los **Universal Links**, habilita la capacidad Associated Domains en Xcode y aloja un archivo `apple-app-site-association` en tu dominio. Consulta la <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">guia de Flutter Universal Links</a> para el recorrido completo.

<div id="intercepting-links"></div>

## Interceptar enlaces

Usa `onIncomingLink` para ejecutar logica antes de que {{ config('app.name') }} enrute el URI. Devuelve `true` para dejar que {{ config('app.name') }} enrute automaticamente, o `false` para gestionar el URI tu mismo:

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

El callback recibe el `Uri` completo, incluyendo esquema, host, ruta y parametros de consulta.

<div id="fallback-route"></div>

## Ruta de reserva

Pasa `fallbackRoute` a `useDeepLinks()` para redirigir a un lugar coherente cuando la ruta de un URI entrante no esta registrada:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Cuando la ruta del URI coincide con una ruta registrada, {{ config('app.name') }} navega hacia ella. De lo contrario, el usuario llega a la ruta de reserva.

<div id="testing"></div>

## Pruebas

Simula deep links entrantes desde la linea de comandos:

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

Cierra la app entre pruebas para verificar el comportamiento en arranque en frio, y activa la URL con la app en primer plano para verificar el arranque en caliente.

<div id="api-reference"></div>

## Referencia de la API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Activa la captura de deep links en la plataforma. Llama a este metodo dentro del metodo `setup` de un proveedor.

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Ruta a la que navegar cuando la ruta de un URI entrante no esta registrada. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Registra un callback invocado para cada URI entrante. Devuelve `true` para dejar que {{ config('app.name') }} enrute automaticamente, o `false` para suprimir el enrutamiento automatico.

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Recibe el URI entrante. |
