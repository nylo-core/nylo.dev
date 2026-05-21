# Liens Profonds

---

<a name="section-1"></a>

- [Introduction](#introduction "Introduction")
- [Prise en main](#getting-started "Prise en main")
  - [Activer les liens profonds](#enable-deep-links "Activer les liens profonds")
  - [Generer un provider](#generate-a-provider "Generer un provider")
- [Configuration des plateformes](#platform-configuration "Configuration des plateformes")
  - [Configuration Android](#android-configuration "Configuration Android")
  - [Configuration iOS](#ios-configuration "Configuration iOS")
- [Intercepter les liens](#intercepting-links "Intercepter les liens")
- [Route de secours](#fallback-route "Route de secours")
- [Tests](#testing "Tests")
- [Reference API](#api-reference "Reference API")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} integre une prise en charge native des liens profonds grace a [`app_links`](https://pub.dev/packages/app_links). Les URI entrants provenant des Android App Links, des Universal Links iOS, des schemas d'URL personnalises et des URL web sont captures automatiquement et acheminés via vos routes enregistrees.

Une seule ligne active le pipeline complet :

```dart
nylo.useDeepLinks();
```

Lorsqu'un URI arrive, {{ config('app.name') }} extrait le chemin et les parametres de requete, puis appelle `routeTo()` avec ces valeurs. Les lancements a froid (l'application ouverte par un lien) sont rejoues une fois que le navigateur est pret, et les liens en demarrage a chaud (recus pendant que l'application tourne) sont traites de la meme facon.

<div id="getting-started"></div>

## Prise en main

<div id="enable-deep-links"></div>

### Activer les liens profonds

Dans la methode `setup` de n'importe quel provider, appelez `useDeepLinks()` :

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

Enregistrez le provider dans `config/providers.dart` pour qu'il s'execute au demarrage de l'application.

<div id="generate-a-provider"></div>

### Generer un provider

Metro genere le provider pour vous :

```bash
dart run nylo_framework:main make:deep_link_provider
```

Cela cree `lib/app/providers/deep_link_provider.dart` avec `useDeepLinks()` pre-configure et un exemple `onIncomingLink` commente. Le provider est egalement ajoute automatiquement dans `config/providers.dart`.

<div id="platform-configuration"></div>

## Configuration des plateformes

<div id="android-configuration"></div>

### Configuration Android

Ajoutez un `<intent-filter>` a votre `<activity>` principale dans `android/app/src/main/AndroidManifest.xml`. L'exemple ci-dessous accepte a la fois les Android App Links (`https://example.com/...`) et un schema personnalise (`myapp://...`) :

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

Pour les App Links verifies, hebergez un fichier `assetlinks.json` a l'adresse `https://example.com/.well-known/assetlinks.json`. Consultez le <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">guide Flutter App Links</a> pour tous les details.

<div id="ios-configuration"></div>

### Configuration iOS

Pour les **schemas d'URL personnalises**, ajoutez une entree `CFBundleURLTypes` dans `ios/Runner/Info.plist` :

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

Pour les **Universal Links**, activez la capacite Associated Domains dans Xcode et hebergez un fichier `apple-app-site-association` sur votre domaine. Consultez le <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">guide Flutter Universal Links</a> pour le parcours complet.

<div id="intercepting-links"></div>

## Intercepter les liens

Utilisez `onIncomingLink` pour executer de la logique avant que {{ config('app.name') }} n'achemine l'URI. Retournez `true` pour laisser {{ config('app.name') }} router automatiquement, ou `false` pour gerer l'URI vous-meme :

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

Le callback recoit le `Uri` complet, incluant le schema, l'hote, le chemin et les parametres de requete.

<div id="fallback-route"></div>

## Route de secours

Passez `fallbackRoute` a `useDeepLinks()` pour rediriger vers une page coherente lorsque le chemin d'un URI entrant n'est pas enregistre :

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Lorsque le chemin de l'URI correspond a une route enregistree, {{ config('app.name') }} y navigue. Sinon, l'utilisateur arrive sur la route de secours.

<div id="testing"></div>

## Tests

Simulez des liens profonds entrants depuis la ligne de commande :

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

Fermez l'application entre les tests pour verifier le comportement au demarrage a froid, et declenchez l'URL avec l'application au premier plan pour verifier le demarrage a chaud.

<div id="api-reference"></div>

## Reference API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Active la capture des liens profonds sur la plateforme. Appelez cette methode dans la methode `setup` d'un provider.

| Parametre | Type | Description |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Route vers laquelle naviguer lorsque le chemin d'un URI entrant n'est pas enregistre. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Enregistre un callback invoque pour chaque URI entrant. Retournez `true` pour laisser {{ config('app.name') }} router automatiquement, ou `false` pour supprimer le routage automatique.

| Parametre | Type | Description |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Recoit l'URI entrant. |
