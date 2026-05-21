# Deep Links

---

<a name="section-1"></a>

- [Введение](#introduction "Введение")
- [Начало работы](#getting-started "Начало работы")
  - [Включение Deep Links](#enable-deep-links "Включение Deep Links")
  - [Генерация провайдера](#generate-a-provider "Генерация провайдера")
- [Настройка платформы](#platform-configuration "Настройка платформы")
  - [Настройка Android](#android-configuration "Настройка Android")
  - [Настройка iOS](#ios-configuration "Настройка iOS")
- [Перехват ссылок](#intercepting-links "Перехват ссылок")
- [Резервный маршрут](#fallback-route "Резервный маршрут")
- [Тестирование](#testing "Тестирование")
- [Справочник API](#api-reference "Справочник API")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} поставляется со встроенной поддержкой deep links на основе [`app_links`](https://pub.dev/packages/app_links). Входящие URI от Android App Links, iOS Universal Links, пользовательских схем URL и веб-адресов захватываются автоматически и направляются через зарегистрированные маршруты.

Одна строка включает весь конвейер обработки:

```dart
nylo.useDeepLinks();
```

Когда поступает URI, {{ config('app.name') }} извлекает путь и параметры запроса и вызывает `routeTo()` с ними. Запуски при холодном старте (когда приложение открывается по ссылке) воспроизводятся после готовности навигатора, а ссылки при тёплом старте (полученные во время работы приложения) обрабатываются аналогично.

<div id="getting-started"></div>

## Начало работы

<div id="enable-deep-links"></div>

### Включение Deep Links

Внутри метода `setup` любого провайдера вызовите `useDeepLinks()`:

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

Зарегистрируйте провайдер в `config/providers.dart`, чтобы он запускался при загрузке приложения.

<div id="generate-a-provider"></div>

### Генерация провайдера

Metro создаст провайдер за вас:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Команда создаёт `lib/app/providers/deep_link_provider.dart` с уже подключённым `useDeepLinks()` и закомментированным примером `onIncomingLink`. Провайдер также автоматически добавляется в `config/providers.dart`.

<div id="platform-configuration"></div>

## Настройка платформы

<div id="android-configuration"></div>

### Настройка Android

Добавьте `<intent-filter>` к основной `<activity>` в файле `android/app/src/main/AndroidManifest.xml`. Приведённый пример принимает как Android App Links (`https://example.com/...`), так и пользовательскую схему (`myapp://...`):

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

Для верифицированных App Links разместите файл `assetlinks.json` по адресу `https://example.com/.well-known/assetlinks.json`. Подробности см. в <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">руководстве Flutter App Links</a>.

<div id="ios-configuration"></div>

### Настройка iOS

Для **пользовательских схем URL** добавьте запись `CFBundleURLTypes` в файл `ios/Runner/Info.plist`:

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

Для **Universal Links** включите возможность Associated Domains в Xcode и разместите файл `apple-app-site-association` на вашем домене. Полное пошаговое руководство см. в <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">руководстве Flutter Universal Links</a>.

<div id="intercepting-links"></div>

## Перехват ссылок

Используйте `onIncomingLink` для выполнения логики до того, как {{ config('app.name') }} направит URI. Верните `true`, чтобы {{ config('app.name') }} выполнил маршрутизацию автоматически, или `false`, чтобы обработать URI самостоятельно:

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

Коллбэк получает полный `Uri`, включая схему, хост, путь и параметры запроса.

<div id="fallback-route"></div>

## Резервный маршрут

Передайте `fallbackRoute` в `useDeepLinks()`, чтобы направить пользователя в нужное место, когда путь входящего URI не зарегистрирован:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Если путь URI совпадает с зарегистрированным маршрутом, {{ config('app.name') }} переходит по нему. В противном случае пользователь попадает на резервный маршрут.

<div id="testing"></div>

## Тестирование

Имитируйте входящие deep links из командной строки:

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

Закрывайте приложение между тестами для проверки поведения при холодном старте, и открывайте URL при запущенном приложении для проверки тёплого старта.

<div id="api-reference"></div>

## Справочник API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Включает захват deep links на уровне платформы. Вызывайте внутри метода `setup` провайдера.

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Маршрут для перехода, когда путь входящего URI не зарегистрирован. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Регистрирует коллбэк, вызываемый для каждого входящего URI. Верните `true`, чтобы {{ config('app.name') }} выполнил маршрутизацию автоматически, или `false` для подавления автоматической маршрутизации.

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Получает входящий URI. |
