# Deep Link

---

<a name="section-1"></a>

- [Introduzione](#introduction "Introduzione")
- [Per Iniziare](#getting-started "Per Iniziare")
  - [Abilitare i Deep Link](#enable-deep-links "Abilitare i Deep Link")
  - [Generare un Provider](#generate-a-provider "Generare un Provider")
- [Configurazione delle Piattaforme](#platform-configuration "Configurazione delle Piattaforme")
  - [Configurazione Android](#android-configuration "Configurazione Android")
  - [Configurazione iOS](#ios-configuration "Configurazione iOS")
- [Intercettare i Link](#intercepting-links "Intercettare i Link")
- [Rotta di Fallback](#fallback-route "Rotta di Fallback")
- [Test](#testing "Test")
- [Riferimento API](#api-reference "Riferimento API")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} include il supporto nativo ai deep link grazie a [`app_links`](https://pub.dev/packages/app_links). Gli URI in entrata da Android App Links, Universal Links iOS, schemi URL personalizzati e URL web vengono catturati automaticamente e instradati attraverso le tue rotte registrate.

Una singola riga attiva l'intero pipeline:

```dart
nylo.useDeepLinks();
```

Quando arriva un URI, {{ config('app.name') }} estrae il percorso e i parametri di query e chiama `routeTo()` con essi. I lanci a freddo (l'app aperta da un link) vengono riprodotti una volta che il navigator e' pronto, e i link a caldo (ricevuti mentre l'app e' in esecuzione) vengono gestiti allo stesso modo.

<div id="getting-started"></div>

## Per Iniziare

<div id="enable-deep-links"></div>

### Abilitare i Deep Link

All'interno del metodo `setup` di qualsiasi provider, chiama `useDeepLinks()`:

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

Registra il provider in `config/providers.dart` in modo che venga eseguito durante l'avvio dell'app.

<div id="generate-a-provider"></div>

### Generare un Provider

Metro genera il provider per te:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Questo crea `lib/app/providers/deep_link_provider.dart` con `useDeepLinks()` gia' configurato e un esempio `onIncomingLink` commentato. Il provider viene anche aggiunto automaticamente a `config/providers.dart`.

<div id="platform-configuration"></div>

## Configurazione delle Piattaforme

<div id="android-configuration"></div>

### Configurazione Android

Aggiungi un `<intent-filter>` alla tua `<activity>` principale in `android/app/src/main/AndroidManifest.xml`. L'esempio seguente accetta sia Android App Links (`https://example.com/...`) che uno schema personalizzato (`myapp://...`):

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

Per gli App Links verificati, ospita un file `assetlinks.json` all'indirizzo `https://example.com/.well-known/assetlinks.json`. Consulta la <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">guida Flutter App Links</a> per tutti i dettagli.

<div id="ios-configuration"></div>

### Configurazione iOS

Per gli **schemi URL personalizzati**, aggiungi una voce `CFBundleURLTypes` in `ios/Runner/Info.plist`:

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

Per gli **Universal Links**, abilita la capacita' Associated Domains in Xcode e ospita un file `apple-app-site-association` sul tuo dominio. Consulta la <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">guida Flutter Universal Links</a> per la procedura completa.

<div id="intercepting-links"></div>

## Intercettare i Link

Usa `onIncomingLink` per eseguire logica prima che {{ config('app.name') }} instradi l'URI. Restituisci `true` per lasciare che {{ config('app.name') }} instradi automaticamente, o `false` per gestire l'URI tu stesso:

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

Il callback riceve il `Uri` completo, inclusi schema, host, percorso e parametri di query.

<div id="fallback-route"></div>

## Rotta di Fallback

Passa `fallbackRoute` a `useDeepLinks()` per atterrare su una pagina sensata quando il percorso di un URI in entrata non e' registrato:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Quando il percorso dell'URI corrisponde a una rotta registrata, {{ config('app.name') }} vi naviga. In caso contrario, l'utente atterra sulla rotta di fallback.

<div id="testing"></div>

## Test

Simula deep link in entrata dalla riga di comando:

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

Chiudi l'app tra un test e l'altro per verificare il comportamento all'avvio a freddo, e attiva l'URL con l'app in primo piano per verificare l'avvio a caldo.

<div id="api-reference"></div>

## Riferimento API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Abilita la cattura dei deep link sulla piattaforma. Chiama questo metodo all'interno del metodo `setup` di un provider.

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Rotta verso cui navigare quando il percorso di un URI in entrata non e' registrato. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Registra un callback invocato per ogni URI in entrata. Restituisci `true` per lasciare che {{ config('app.name') }} instradi automaticamente, o `false` per sopprimere l'instradamento automatico.

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Riceve l'URI in entrata. |
