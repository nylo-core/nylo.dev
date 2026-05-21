# Deep Links

---

<a name="section-1"></a>

- [Einleitung](#introduction "Einleitung")
- [Erste Schritte](#getting-started "Erste Schritte")
  - [Deep Links aktivieren](#enable-deep-links "Deep Links aktivieren")
  - [Einen Provider generieren](#generate-a-provider "Einen Provider generieren")
- [Plattformkonfiguration](#platform-configuration "Plattformkonfiguration")
  - [Android-Konfiguration](#android-configuration "Android-Konfiguration")
  - [iOS-Konfiguration](#ios-configuration "iOS-Konfiguration")
- [Links abfangen](#intercepting-links "Links abfangen")
- [Fallback-Route](#fallback-route "Fallback-Route")
- [Testen](#testing "Testen")
- [API-Referenz](#api-reference "API-Referenz")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} bietet erstklassige Deep-Link-Unterstützung mit [`app_links`](https://pub.dev/packages/app_links). Eingehende URIs von Android App Links, iOS Universal Links, benutzerdefinierten URL-Schemata und Web-URLs werden automatisch erfasst und über Ihre registrierten Routen weitergeleitet.

Eine einzige Zeile aktiviert die gesamte Pipeline:

```dart
nylo.useDeepLinks();
```

Wenn eine URI eintrifft, extrahiert {{ config('app.name') }} den Pfad und die Query-Parameter und ruft `routeTo()` mit ihnen auf. Cold-Start-Starts (die App wird über einen Link geöffnet) werden wiedergegeben, sobald der Navigator bereit ist, und Warm-Start-Links (empfangen während die App läuft) werden auf dieselbe Weise behandelt.

<div id="getting-started"></div>

## Erste Schritte

<div id="enable-deep-links"></div>

### Deep Links aktivieren

Rufen Sie in der `setup`-Methode eines beliebigen Providers `useDeepLinks()` auf:

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

Registrieren Sie den Provider in `config/providers.dart`, damit er beim App-Start ausgeführt wird.

<div id="generate-a-provider"></div>

### Einen Provider generieren

Metro erstellt den Provider für Sie:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Dies erstellt `lib/app/providers/deep_link_provider.dart` mit vorkonfiguriertem `useDeepLinks()` und einem kommentierten `onIncomingLink`-Beispiel. Der Provider wird außerdem automatisch zu `config/providers.dart` hinzugefügt.

<div id="platform-configuration"></div>

## Plattformkonfiguration

<div id="android-configuration"></div>

### Android-Konfiguration

Fügen Sie einen `<intent-filter>` zu Ihrer Haupt-`<activity>` in `android/app/src/main/AndroidManifest.xml` hinzu. Das folgende Beispiel akzeptiert sowohl Android App Links (`https://example.com/...`) als auch ein benutzerdefiniertes Schema (`myapp://...`):

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

Für verifizierte App Links hosten Sie eine `assetlinks.json`-Datei unter `https://example.com/.well-known/assetlinks.json`. Weitere Details finden Sie im <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links-Leitfaden</a>.

<div id="ios-configuration"></div>

### iOS-Konfiguration

Für **benutzerdefinierte URL-Schemata** fügen Sie einen `CFBundleURLTypes`-Eintrag zu `ios/Runner/Info.plist` hinzu:

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

Für **Universal Links** aktivieren Sie die Funktion „Associated Domains" in Xcode und hosten eine `apple-app-site-association`-Datei auf Ihrer Domain. Die vollständige Anleitung finden Sie im <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links-Leitfaden</a>.

<div id="intercepting-links"></div>

## Links abfangen

Verwenden Sie `onIncomingLink`, um Logik auszuführen, bevor {{ config('app.name') }} die URI weiterleitet. Geben Sie `true` zurück, damit {{ config('app.name') }} automatisch weiterleitet, oder `false`, um die URI selbst zu behandeln:

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

Der Callback erhält die vollständige `Uri`, einschließlich Schema, Host, Pfad und Query-Parameter.

<div id="fallback-route"></div>

## Fallback-Route

Übergeben Sie `fallbackRoute` an `useDeepLinks()`, um den Benutzer an einen sinnvollen Ort weiterzuleiten, wenn der Pfad einer eingehenden URI nicht registriert ist:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Wenn der Pfad der URI einer registrierten Route entspricht, leitet {{ config('app.name') }} dorthin weiter. Andernfalls landet der Benutzer auf der Fallback-Route.

<div id="testing"></div>

## Testen

Simulieren Sie eingehende Deep Links über die Befehlszeile:

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

Beenden Sie die App zwischen den Tests, um das Cold-Start-Verhalten zu verifizieren, und lösen Sie die URL mit der App im Vordergrund aus, um das Warm-Start-Verhalten zu verifizieren.

<div id="api-reference"></div>

## API-Referenz

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Aktiviert die plattformspezifische Deep-Link-Erfassung. Rufen Sie dies in der `setup`-Methode eines Providers auf.

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Route, zu der navigiert wird, wenn der Pfad einer eingehenden URI nicht registriert ist. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Registriert einen Callback, der für jede eingehende URI aufgerufen wird. Geben Sie `true` zurück, damit {{ config('app.name') }} automatisch weiterleitet, oder `false`, um das automatische Weiterleiten zu unterdrücken.

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Erhält die eingehende URI. |
