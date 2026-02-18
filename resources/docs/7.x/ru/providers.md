# Providers

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Создание провайдера](#create-a-provider "Создание провайдера")
- [Объект провайдера](#provider-object "Объект провайдера")


<div id="introduction"></div>

## Введение в провайдеры

В {{ config('app.name') }} провайдеры загружаются при запуске приложения из файла <b>main.dart</b>. Все провайдеры находятся в `/lib/app/providers/*`, вы можете изменять эти файлы или создавать собственные провайдеры с помощью <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Провайдеры используются, когда необходимо инициализировать класс, пакет или создать что-то до первоначальной загрузки приложения. Например, класс `route_provider.dart` отвечает за добавление всех маршрутов в {{ config('app.name') }}.

### Подробный разбор

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Жизненный цикл

- `Boot.{{ config('app.name') }}` перебирает зарегистрированные провайдеры в файле <b>config/providers.dart</b> и загружает их.

- `Boot.Finished` вызывается сразу после завершения **"Boot.{{ config('app.name') }}"**, этот метод привязывает экземпляр {{ config('app.name') }} к `Backpack` со значением 'nylo'.

Например: Backpack.instance.read('nylo'); // экземпляр {{ config('app.name') }}


<div id="create-a-provider"></div>

## Создание нового провайдера

Вы можете создавать новые провайдеры, выполнив следующую команду в терминале.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Объект провайдера

Ваш провайдер будет содержать два метода: `setup(Nylo nylo)` и `boot(Nylo nylo)`.

При первом запуске приложения любой код внутри метода **setup** будет выполнен первым. Вы также можете модифицировать объект `Nylo`, как показано в примере ниже.

Пример: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Жизненный цикл

1. `setup(Nylo nylo)` -- инициализация провайдера. Верните экземпляр `Nylo` или `null`.
2. `boot(Nylo nylo)` -- вызывается после завершения setup всех провайдеров. Используйте этот метод для инициализации, которая зависит от готовности других провайдеров.

> Внутри метода `setup` вы должны **вернуть** экземпляр `Nylo` или `null`, как показано выше.
