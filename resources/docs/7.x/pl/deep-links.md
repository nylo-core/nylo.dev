# Deep Links

---

<a name="section-1"></a>

- [Wprowadzenie](#introduction "Wprowadzenie")
- [Pierwsze kroki](#getting-started "Pierwsze kroki")
  - [Włącz Deep Links](#enable-deep-links "Włącz Deep Links")
  - [Wygeneruj provider](#generate-a-provider "Wygeneruj provider")
- [Konfiguracja platformy](#platform-configuration "Konfiguracja platformy")
  - [Konfiguracja Android](#android-configuration "Konfiguracja Android")
  - [Konfiguracja iOS](#ios-configuration "Konfiguracja iOS")
- [Przechwytywanie linków](#intercepting-links "Przechwytywanie linków")
- [Trasa zastępcza](#fallback-route "Trasa zastępcza")
- [Testowanie](#testing "Testowanie")
- [Dokumentacja API](#api-reference "Dokumentacja API")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} oferuje natywne wsparcie dla deep linków dzięki [`app_links`](https://pub.dev/packages/app_links). Przychodzące URI z Android App Links, iOS Universal Links, niestandardowych schematów URL i adresów web są przechwytywane automatycznie i kierowane przez zarejestrowane trasy.

Jedna linia aktywuje cały mechanizm:

```dart
nylo.useDeepLinks();
```

Gdy pojawi się URI, {{ config('app.name') }} wyodrębnia ścieżkę i parametry zapytania, a następnie wywołuje `routeTo()` z tymi danymi. Uruchomienia zimne (otwarcie aplikacji przez link) są odtwarzane po przygotowaniu nawigatora, a linki ciepłe (otrzymane gdy aplikacja działa) są obsługiwane w ten sam sposób.

<div id="getting-started"></div>

## Pierwsze kroki

<div id="enable-deep-links"></div>

### Włącz Deep Links

Wewnątrz metody `setup` dowolnego providera wywołaj `useDeepLinks()`:

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

Zarejestruj provider w `config/providers.dart`, aby był uruchamiany podczas startu aplikacji.

<div id="generate-a-provider"></div>

### Wygeneruj provider

Metro tworzy provider za Ciebie:

```bash
dart run nylo_framework:main make:deep_link_provider
```

To tworzy `lib/app/providers/deep_link_provider.dart` z gotowym `useDeepLinks()` i skomentowanym przykładem `onIncomingLink`. Provider jest też automatycznie dodawany do `config/providers.dart`.

<div id="platform-configuration"></div>

## Konfiguracja platformy

<div id="android-configuration"></div>

### Konfiguracja Android

Dodaj `<intent-filter>` do głównej `<activity>` w `android/app/src/main/AndroidManifest.xml`. Poniższy przykład akceptuje zarówno Android App Links (`https://example.com/...`), jak i niestandardowy schemat (`myapp://...`):

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

W przypadku zweryfikowanych App Links umieść plik `assetlinks.json` pod adresem `https://example.com/.well-known/assetlinks.json`. Szczegóły znajdziesz w <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">przewodniku Flutter App Links</a>.

<div id="ios-configuration"></div>

### Konfiguracja iOS

W przypadku **niestandardowych schematów URL** dodaj wpis `CFBundleURLTypes` do `ios/Runner/Info.plist`:

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

W przypadku **Universal Links** włącz funkcję Associated Domains w Xcode i umieść plik `apple-app-site-association` na swojej domenie. Pełny opis kroków znajdziesz w <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">przewodniku Flutter Universal Links</a>.

<div id="intercepting-links"></div>

## Przechwytywanie linków

Użyj `onIncomingLink`, aby uruchomić logikę przed przekierowaniem URI przez {{ config('app.name') }}. Zwróć `true`, aby {{ config('app.name') }} przekierował automatycznie, lub `false`, aby samodzielnie obsłużyć URI:

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

Callback otrzymuje pełne `Uri`, w tym schemat, host, ścieżkę i parametry zapytania.

<div id="fallback-route"></div>

## Trasa zastępcza

Przekaż `fallbackRoute` do `useDeepLinks()`, aby skierować użytkownika w sensowne miejsce, gdy ścieżka przychodzącej URI nie jest zarejestrowana:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Gdy ścieżka URI odpowiada zarejestrowanej trasie, {{ config('app.name') }} do niej przekierowuje. W przeciwnym razie użytkownik trafia na trasę zastępczą.

<div id="testing"></div>

## Testowanie

Symuluj przychodzące deep linki z wiersza poleceń:

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

Zamknij aplikację między testami, aby zweryfikować zachowanie przy zimnym starcie, i wyzwól URL gdy aplikacja działa na pierwszym planie, aby zweryfikować zachowanie przy ciepłym starcie.

<div id="api-reference"></div>

## Dokumentacja API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Włącza przechwytywanie deep linków platformy. Wywołaj to wewnątrz metody `setup` providera.

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Trasa, do której następuje nawigacja, gdy ścieżka przychodzącej URI nie jest zarejestrowana. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Rejestruje callback wywoływany dla każdej przychodzącej URI. Zwróć `true`, aby {{ config('app.name') }} przekierował automatycznie, lub `false`, aby wyłączyć automatyczne przekierowanie.

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Otrzymuje przychodzącą URI. |
