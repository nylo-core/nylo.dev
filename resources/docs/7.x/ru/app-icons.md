# App Icons

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Генерация иконок приложения](#generating-app-icons "Генерация иконок приложения")
- [Добавление иконки приложения](#adding-your-app-icon "Добавление иконки приложения")
- [Требования к иконке приложения](#app-icon-requirements "Требования к иконке приложения")
- [Конфигурация](#configuration "Конфигурация")
- [Счётчик значков](#badge-count "Счётчик значков")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 использует <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> для генерации иконок приложения для iOS и Android из одного исходного изображения.

Иконка приложения должна быть размещена в директории `assets/app_icon/` с размером **1024x1024 пикселей**.

<div id="generating-app-icons"></div>

## Генерация иконок приложения

Выполните следующую команду для генерации иконок для всех платформ:

``` bash
dart run flutter_launcher_icons
```

Эта команда считывает исходную иконку из `assets/app_icon/` и генерирует:
- Иконки iOS в `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Иконки Android в `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Добавление иконки приложения

1. Создайте иконку в формате **PNG 1024x1024**
2. Поместите её в `assets/app_icon/` (например, `assets/app_icon/icon.png`)
3. При необходимости обновите `image_path` в вашем `pubspec.yaml`:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Запустите команду генерации иконок

<div id="app-icon-requirements"></div>

## Требования к иконке приложения

| Атрибут | Значение |
|---------|----------|
| Формат | PNG |
| Размер | 1024x1024 пикселей |
| Слои | Сведённые, без прозрачности |

### Именование файлов

Используйте простые имена файлов без специальных символов:
- `app_icon.png`
- `icon.png`

### Рекомендации платформ

Для получения подробных требований обратитесь к официальным рекомендациям платформ:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Конфигурация

Настройте генерацию иконок в вашем `pubspec.yaml`:

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

Смотрите <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">документацию flutter_launcher_icons</a> для получения информации обо всех доступных параметрах.

<div id="badge-count"></div>

## Счётчик значков

{{ config('app.name') }} предоставляет вспомогательные функции для управления счётчиком значков приложения (число, отображаемое на иконке приложения):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Поддержка платформ

Счётчики значков поддерживаются на:
- **iOS**: Нативная поддержка
- **Android**: Требуется поддержка лаунчера (большинство лаунчеров поддерживают)
- **Web**: Не поддерживается

### Сценарии использования

Типичные сценарии использования счётчиков значков:
- Непрочитанные уведомления
- Ожидающие сообщения
- Товары в корзине
- Незавершённые задачи

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
