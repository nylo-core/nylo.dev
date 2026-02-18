# Requirements

---

<a name="section-1"></a>
- [Системные требования](#system-requirements "Системные требования")
- [Установка Flutter](#installing-flutter "Установка Flutter")
- [Проверка установки](#verifying-installation "Проверка установки")
- [Настройка редактора](#set-up-an-editor "Настройка редактора")


<div id="system-requirements"></div>

## Системные требования

{{ config('app.name') }} v7 требует следующие минимальные версии:

| Требование | Минимальная версия |
|-------------|-----------------|
| **Flutter** | 3.24.0 или выше |
| **Dart SDK** | 3.10.7 или выше |

### Поддержка платформ

{{ config('app.name') }} поддерживает все платформы, которые поддерживает Flutter:

| Платформа | Поддержка |
|----------|---------|
| iOS | ✓ Полная поддержка |
| Android | ✓ Полная поддержка |
| Web | ✓ Полная поддержка |
| macOS | ✓ Полная поддержка |
| Windows | ✓ Полная поддержка |
| Linux | ✓ Полная поддержка |

<div id="installing-flutter"></div>

## Установка Flutter

Если у вас не установлен Flutter, следуйте официальному руководству по установке для вашей операционной системы:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Руководство по установке Flutter</a>

<div id="verifying-installation"></div>

## Проверка установки

После установки Flutter проверьте вашу настройку:

### Проверка версии Flutter

``` bash
flutter --version
```

Вы должны увидеть вывод, похожий на:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Обновление Flutter (при необходимости)

Если ваша версия Flutter ниже 3.24.0, обновите до последней стабильной версии:

``` bash
flutter channel stable
flutter upgrade
```

### Запуск Flutter Doctor

Убедитесь, что ваша среда разработки правильно настроена:

``` bash
flutter doctor -v
```

Эта команда проверяет:
- Установку Flutter SDK
- Инструментарий Android (для разработки под Android)
- Xcode (для разработки под iOS/macOS)
- Подключённые устройства
- Плагины IDE

Исправьте все обнаруженные проблемы перед тем, как приступить к установке {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Настройка редактора

Выберите IDE с поддержкой Flutter:

### Visual Studio Code (рекомендуется)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> --- лёгкий редактор с отличной поддержкой Flutter.

1. Установите <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Установите <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">расширение Flutter</a>
3. Установите <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">расширение Dart</a>

Руководство по настройке: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Настройка VS Code для Flutter</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> предоставляет полнофункциональную IDE со встроенной поддержкой эмулятора.

1. Установите <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Установите плагин Flutter (Preferences → Plugins → Flutter)
3. Установите плагин Dart

Руководство по настройке: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Настройка Android Studio для Flutter</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community или Ultimate) также поддерживает разработку на Flutter.

1. Установите IntelliJ IDEA
2. Установите плагин Flutter (Preferences → Plugins → Flutter)
3. Установите плагин Dart

После настройки редактора вы готовы [установить {{ config('app.name') }}](/docs/7.x/installation).
