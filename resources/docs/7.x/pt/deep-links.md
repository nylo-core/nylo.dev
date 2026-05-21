# Deep Links

---

<a name="section-1"></a>

- [Introdução](#introduction "Introdução")
- [Primeiros Passos](#getting-started "Primeiros Passos")
  - [Ativar Deep Links](#enable-deep-links "Ativar Deep Links")
  - [Gerar um Provider](#generate-a-provider "Gerar um Provider")
- [Configuração de Plataforma](#platform-configuration "Configuração de Plataforma")
  - [Configuração Android](#android-configuration "Configuração Android")
  - [Configuração iOS](#ios-configuration "Configuração iOS")
- [Interceptando Links](#intercepting-links "Interceptando Links")
- [Rota de Fallback](#fallback-route "Rota de Fallback")
- [Testes](#testing "Testes")
- [Referência de API](#api-reference "Referência de API")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} oferece suporte de primeira classe a deep links com o [`app_links`](https://pub.dev/packages/app_links). URIs recebidas do Android App Links, iOS Universal Links, esquemas de URL personalizados e URLs web são capturadas automaticamente e roteadas pelas suas rotas registradas.

Uma única linha ativa o pipeline completo:

```dart
nylo.useDeepLinks();
```

Quando uma URI chega, {{ config('app.name') }} extrai o caminho e os parâmetros de query e chama `routeTo()` com eles. Inicializações a frio (o app sendo aberto por um link) são reproduzidas assim que o navigator estiver pronto, e links a quente (recebidos enquanto o app está em execução) são tratados da mesma forma.

<div id="getting-started"></div>

## Primeiros Passos

<div id="enable-deep-links"></div>

### Ativar Deep Links

Dentro do método `setup` de qualquer provider, chame `useDeepLinks()`:

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

Registre o provider em `config/providers.dart` para que ele seja executado durante a inicialização do app.

<div id="generate-a-provider"></div>

### Gerar um Provider

O Metro cria o provider para você:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Isso cria `lib/app/providers/deep_link_provider.dart` com `useDeepLinks()` já configurado e um exemplo comentado de `onIncomingLink`. O provider também é adicionado ao `config/providers.dart` automaticamente.

<div id="platform-configuration"></div>

## Configuração de Plataforma

<div id="android-configuration"></div>

### Configuração Android

Adicione um `<intent-filter>` à sua `<activity>` principal em `android/app/src/main/AndroidManifest.xml`. O exemplo abaixo aceita tanto Android App Links (`https://example.com/...`) quanto um esquema personalizado (`myapp://...`):

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

Para App Links verificadas, hospede um arquivo `assetlinks.json` em `https://example.com/.well-known/assetlinks.json`. Veja o <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">guia de Flutter App Links</a> para mais detalhes.

<div id="ios-configuration"></div>

### Configuração iOS

Para **esquemas de URL personalizados**, adicione uma entrada `CFBundleURLTypes` ao `ios/Runner/Info.plist`:

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

Para **Universal Links**, ative a capacidade Associated Domains no Xcode e hospede um arquivo `apple-app-site-association` no seu domínio. Veja o <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">guia de Flutter Universal Links</a> para o passo a passo completo.

<div id="intercepting-links"></div>

## Interceptando Links

Use `onIncomingLink` para executar lógica antes que o {{ config('app.name') }} roteie a URI. Retorne `true` para deixar o {{ config('app.name') }} rotear automaticamente, ou `false` para tratar a URI você mesmo:

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

O callback recebe a `Uri` completa, incluindo esquema, host, caminho e parâmetros de query.

<div id="fallback-route"></div>

## Rota de Fallback

Passe `fallbackRoute` para `useDeepLinks()` para redirecionar o usuário a algum lugar adequado quando o caminho de uma URI recebida não estiver registrado:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Quando o caminho da URI corresponde a uma rota registrada, o {{ config('app.name') }} roteia para ela. Caso contrário, o usuário é direcionado ao fallback.

<div id="testing"></div>

## Testes

Simule deep links recebidas a partir da linha de comando:

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

Encerre o app entre os testes para verificar o comportamento a frio, e acione a URL com o app em primeiro plano para verificar o comportamento a quente.

<div id="api-reference"></div>

## Referência de API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Ativa a captura de deep links da plataforma. Chame isso dentro do método `setup` de um provider.

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Rota para navegar quando o caminho de uma URI recebida não estiver registrado. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Registra um callback invocado para cada URI recebida. Retorne `true` para deixar o {{ config('app.name') }} rotear automaticamente, ou `false` para suprimir o roteamento automático.

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Recebe a URI recebida. |
