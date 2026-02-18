# Local Notifications

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Запланированные уведомления](#scheduled-notifications "Запланированные уведомления")
- [Паттерн построителя](#builder-pattern "Паттерн построителя")
- [Конфигурация платформы](#platform-configuration "Конфигурация платформы")
  - [Конфигурация Android](#android-configuration "Конфигурация Android")
  - [Конфигурация iOS](#ios-configuration "Конфигурация iOS")
- [Управление уведомлениями](#managing-notifications "Управление уведомлениями")
- [Разрешения](#permissions "Разрешения")
- [Настройка платформы](#platform-setup "Настройка платформы")
- [Справочник API](#api-reference "Справочник API")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} предоставляет систему локальных уведомлений через класс `LocalNotification`. Это позволяет отправлять мгновенные или запланированные уведомления с насыщенным содержимым на iOS и Android.

> Локальные уведомления не поддерживаются в вебе. Попытка использовать их в вебе вызовет `NotificationException`.

<div id="basic-usage"></div>

## Базовое использование

Отправка уведомления с помощью паттерна построителя или статического метода:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Builder pattern
await LocalNotification(title: "Hello", body: "World").send();

// Using the helper function
await localNotification("Hello", "World").send();

// Using the static method
await LocalNotification.sendNotification(
  title: "Hello",
  body: "World",
);
```

<div id="scheduled-notifications"></div>

## Запланированные уведомления

Планирование уведомления для доставки в определённое время:

``` dart
// Schedule for tomorrow
final tomorrow = DateTime.now().add(Duration(days: 1));

await LocalNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
).send(at: tomorrow);

// Using the static method
await LocalNotification.sendNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
  at: tomorrow,
);
```

<div id="builder-pattern"></div>

## Паттерн построителя

Класс `LocalNotification` предоставляет текучий API построителя. Все цепочечные методы возвращают экземпляр `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Цепочечные методы

| Метод | Параметры | Описание |
|-------|-----------|----------|
| `addPayload` | `String payload` | Устанавливает пользовательскую строку данных для уведомления |
| `addId` | `int id` | Устанавливает уникальный идентификатор уведомления |
| `addSubtitle` | `String subtitle` | Устанавливает текст подзаголовка |
| `addBadgeNumber` | `int badgeNumber` | Устанавливает номер значка иконки приложения |
| `addSound` | `String sound` | Устанавливает пользовательский звуковой файл |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Добавляет вложение (только iOS, загружает по URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Устанавливает конфигурацию для Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Устанавливает конфигурацию для iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Если указан `at`, планирует уведомление на это время. В противном случае показывает немедленно.

<div id="platform-configuration"></div>

## Конфигурация платформы

В v7 специфичные для платформы опции настраиваются через объекты `AndroidNotificationConfig` и `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Конфигурация Android

Передайте `AndroidNotificationConfig` в `setAndroidConfig()`:

``` dart
await LocalNotification(
  title: "Android Notification",
  body: "With custom configuration",
)
.setAndroidConfig(AndroidNotificationConfig(
  channelId: "custom_channel",
  channelName: "Custom Channel",
  channelDescription: "Notifications from custom channel",
  importance: Importance.max,
  priority: Priority.high,
  enableVibration: true,
  vibrationPattern: [0, 1000, 500, 1000],
  enableLights: true,
  ledColor: Color(0xFF00FF00),
))
.send();
```

#### Свойства AndroidNotificationConfig

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `channelId` | `String?` | `'default_channel'` | ID канала уведомлений |
| `channelName` | `String?` | `'Default Channel'` | Название канала уведомлений |
| `channelDescription` | `String?` | `'Default Channel'` | Описание канала |
| `importance` | `Importance?` | `Importance.max` | Уровень важности уведомления |
| `priority` | `Priority?` | `Priority.high` | Приоритет уведомления |
| `ticker` | `String?` | `'ticker'` | Текст бегущей строки для доступности |
| `icon` | `String?` | `'app_icon'` | Имя ресурса маленькой иконки |
| `playSound` | `bool?` | `true` | Воспроизводить ли звук |
| `enableVibration` | `bool?` | `true` | Включить ли вибрацию |
| `vibrationPattern` | `List<int>?` | - | Пользовательский паттерн вибрации (мс) |
| `groupKey` | `String?` | - | Ключ группы для группировки уведомлений |
| `setAsGroupSummary` | `bool?` | `false` | Является ли это сводкой группы |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Поведение оповещения для групп |
| `autoCancel` | `bool?` | `true` | Автоматическое скрытие при нажатии |
| `ongoing` | `bool?` | `false` | Не может быть отклонено пользователем |
| `silent` | `bool?` | `false` | Тихое уведомление |
| `color` | `Color?` | - | Акцентный цвет |
| `largeIcon` | `String?` | - | Путь к ресурсу большой иконки |
| `onlyAlertOnce` | `bool?` | `false` | Оповещать только при первом показе |
| `showWhen` | `bool?` | `true` | Показывать временную метку |
| `when` | `int?` | - | Пользовательская временная метка (мс с эпохи) |
| `usesChronometer` | `bool?` | `false` | Использовать отображение хронометра |
| `chronometerCountDown` | `bool?` | `false` | Хронометр считает в обратную сторону |
| `channelShowBadge` | `bool?` | `true` | Показывать значок на канале |
| `showProgress` | `bool?` | `false` | Показывать индикатор прогресса |
| `maxProgress` | `int?` | `0` | Максимальное значение прогресса |
| `progress` | `int?` | `0` | Текущее значение прогресса |
| `indeterminate` | `bool?` | `false` | Неопределённый прогресс |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Действие канала |
| `enableLights` | `bool?` | `false` | Включить светодиод уведомления |
| `ledColor` | `Color?` | - | Цвет светодиода |
| `ledOnMs` | `int?` | - | Длительность включения светодиода (мс) |
| `ledOffMs` | `int?` | - | Длительность выключения светодиода (мс) |
| `visibility` | `NotificationVisibility?` | - | Видимость на экране блокировки |
| `timeoutAfter` | `int?` | - | Тайм-аут автоматического скрытия (мс) |
| `fullScreenIntent` | `bool?` | `false` | Запуск как полноэкранное намерение |
| `shortcutId` | `String?` | - | ID ярлыка |
| `additionalFlags` | `List<int>?` | - | Дополнительные флаги |
| `tag` | `String?` | - | Тег уведомления |
| `actions` | `List<AndroidNotificationAction>?` | - | Кнопки действий |
| `colorized` | `bool?` | `false` | Включить раскрашивание |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Использование аудиоатрибутов |

<div id="ios-configuration"></div>

### Конфигурация iOS

Передайте `IOSNotificationConfig` в `setIOSConfig()`:

``` dart
await LocalNotification(
  title: "iOS Notification",
  body: "With custom configuration",
)
.setIOSConfig(IOSNotificationConfig(
  presentAlert: true,
  presentBanner: true,
  presentSound: true,
  threadIdentifier: "thread_1",
  interruptionLevel: InterruptionLevel.active,
))
.addBadgeNumber(1)
.addSound("custom_sound.wav")
.send();
```

#### Свойства IOSNotificationConfig

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `presentList` | `bool?` | `true` | Показывать в списке уведомлений |
| `presentAlert` | `bool?` | `true` | Показывать оповещение |
| `presentBadge` | `bool?` | `true` | Обновлять значок приложения |
| `presentSound` | `bool?` | `true` | Воспроизводить звук |
| `presentBanner` | `bool?` | `true` | Показывать баннер |
| `sound` | `String?` | - | Имя звукового файла |
| `badgeNumber` | `int?` | - | Номер значка |
| `threadIdentifier` | `String?` | - | Идентификатор потока для группировки |
| `categoryIdentifier` | `String?` | - | Идентификатор категории для действий |
| `interruptionLevel` | `InterruptionLevel?` | - | Уровень прерывания |

### Вложения (только iOS)

Добавление изображений, аудио или видеовложений к уведомлениям iOS. Вложения загружаются по URL:

``` dart
await LocalNotification(
  title: "New Photo",
  body: "Check out this image!",
)
.addAttachment(
  "https://example.com/image.jpg",
  "photo.jpg",
  showThumbnail: true,
)
.send();
```

<div id="managing-notifications"></div>

## Управление уведомлениями

### Отмена конкретного уведомления

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Отмена всех уведомлений

``` dart
await LocalNotification.cancelAllNotifications();
```

### Сброс счётчика значка

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Разрешения

Запрос разрешений на уведомления у пользователя:

``` dart
// Request with defaults
await LocalNotification.requestPermissions();

// Request with specific options
await LocalNotification.requestPermissions(
  alert: true,
  badge: true,
  sound: true,
  provisional: false,
  critical: false,
  vibrate: true,
  enableLights: true,
  channelId: 'default_notification_channel_id',
  channelName: 'Default Notification Channel',
);
```

Разрешения автоматически запрашиваются при первой отправке уведомления через `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Настройка платформы

### Настройка iOS

Добавьте в `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Настройка Android

Добавьте в `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Справочник API

### Статические методы

| Метод | Сигнатура | Описание |
|-------|-----------|----------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Отправка уведомления со всеми параметрами |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Отмена конкретного уведомления |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Отмена всех уведомлений |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Запрос разрешений на уведомления |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Сброс счётчика значка iOS |

### Методы экземпляра (цепочечные)

| Метод | Параметры | Возвращает | Описание |
|-------|-----------|------------|----------|
| `addPayload` | `String payload` | `LocalNotification` | Установить данные payload |
| `addId` | `int id` | `LocalNotification` | Установить ID уведомления |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Установить подзаголовок |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Установить номер значка |
| `addSound` | `String sound` | `LocalNotification` | Установить пользовательский звук |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Добавить вложение (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Установить конфигурацию Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Установить конфигурацию iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Отправить уведомление |
