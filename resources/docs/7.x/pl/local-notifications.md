# Local Notifications

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Zaplanowane powiadomienia](#scheduled-notifications "Zaplanowane powiadomienia")
- [Wzorzec builder](#builder-pattern "Wzorzec builder")
- [Konfiguracja platformy](#platform-configuration "Konfiguracja platformy")
  - [Konfiguracja Android](#android-configuration "Konfiguracja Android")
  - [Konfiguracja iOS](#ios-configuration "Konfiguracja iOS")
- [Zarządzanie powiadomieniami](#managing-notifications "Zarządzanie powiadomieniami")
- [Uprawnienia](#permissions "Uprawnienia")
- [Konfiguracja platformy](#platform-setup "Konfiguracja platformy")
- [Referencja API](#api-reference "Referencja API")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} udostępnia system powiadomień lokalnych poprzez klasę `LocalNotification`. Pozwala to wysyłać natychmiastowe lub zaplanowane powiadomienia z bogatą zawartością na iOS i Android.

> Powiadomienia lokalne nie są obsługiwane w przeglądarce. Próba użycia ich w wersji webowej spowoduje wyjątek `NotificationException`.

<div id="basic-usage"></div>

## Podstawowe użycie

Wyślij powiadomienie za pomocą wzorca builder lub metody statycznej:

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

## Zaplanowane powiadomienia

Zaplanuj powiadomienie do dostarczenia o określonym czasie:

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

## Wzorzec builder

Klasa `LocalNotification` udostępnia płynne API buildera. Wszystkie metody łańcuchowe zwracają instancję `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Metody łańcuchowe

| Metoda | Parametry | Opis |
|--------|-----------|------|
| `addPayload` | `String payload` | Ustawia niestandardowy ciąg danych dla powiadomienia |
| `addId` | `int id` | Ustawia unikalny identyfikator powiadomienia |
| `addSubtitle` | `String subtitle` | Ustawia tekst podtytułu |
| `addBadgeNumber` | `int badgeNumber` | Ustawia numer odznaki ikony aplikacji |
| `addSound` | `String sound` | Ustawia niestandardowy plik dźwiękowy |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Dodaje załącznik (tylko iOS, pobiera z URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Ustawia konfigurację specyficzną dla Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Ustawia konfigurację specyficzną dla iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Jeśli podano `at`, planuje powiadomienie na ten czas. W przeciwnym razie wyświetla je natychmiast.

<div id="platform-configuration"></div>

## Konfiguracja platformy

W v7 opcje specyficzne dla platformy są konfigurowane za pomocą obiektów `AndroidNotificationConfig` i `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Konfiguracja Android

Przekaż `AndroidNotificationConfig` do `setAndroidConfig()`:

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

#### Właściwości AndroidNotificationConfig

| Właściwość | Typ | Domyślna wartość | Opis |
|------------|-----|------------------|------|
| `channelId` | `String?` | `'default_channel'` | ID kanału powiadomień |
| `channelName` | `String?` | `'Default Channel'` | Nazwa kanału powiadomień |
| `channelDescription` | `String?` | `'Default Channel'` | Opis kanału |
| `importance` | `Importance?` | `Importance.max` | Poziom ważności powiadomienia |
| `priority` | `Priority?` | `Priority.high` | Priorytet powiadomienia |
| `ticker` | `String?` | `'ticker'` | Tekst tickera dla dostępności |
| `icon` | `String?` | `'app_icon'` | Nazwa zasobu małej ikony |
| `playSound` | `bool?` | `true` | Czy odtwarzać dźwięk |
| `enableVibration` | `bool?` | `true` | Czy włączyć wibracje |
| `vibrationPattern` | `List<int>?` | - | Niestandardowy wzorzec wibracji (ms) |
| `groupKey` | `String?` | - | Klucz grupy do grupowania powiadomień |
| `setAsGroupSummary` | `bool?` | `false` | Czy to jest podsumowanie grupy |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Zachowanie alertów dla grup |
| `autoCancel` | `bool?` | `true` | Automatyczne zamknięcie po dotknięciu |
| `ongoing` | `bool?` | `false` | Nie może być zamknięte przez użytkownika |
| `silent` | `bool?` | `false` | Ciche powiadomienie |
| `color` | `Color?` | - | Kolor akcentu |
| `largeIcon` | `String?` | - | Ścieżka zasobu dużej ikony |
| `onlyAlertOnce` | `bool?` | `false` | Alert tylko przy pierwszym wyświetleniu |
| `showWhen` | `bool?` | `true` | Wyświetlanie znacznika czasu |
| `when` | `int?` | - | Niestandardowy znacznik czasu (ms od epoki) |
| `usesChronometer` | `bool?` | `false` | Użycie wyświetlania chronometru |
| `chronometerCountDown` | `bool?` | `false` | Chronometr odlicza w dół |
| `channelShowBadge` | `bool?` | `true` | Wyświetlanie odznaki na kanale |
| `showProgress` | `bool?` | `false` | Wyświetlanie wskaźnika postępu |
| `maxProgress` | `int?` | `0` | Maksymalna wartość postępu |
| `progress` | `int?` | `0` | Aktualna wartość postępu |
| `indeterminate` | `bool?` | `false` | Nieokreślony postęp |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Akcja kanału |
| `enableLights` | `bool?` | `false` | Włączenie diody LED powiadomień |
| `ledColor` | `Color?` | - | Kolor diody LED |
| `ledOnMs` | `int?` | - | Czas włączenia LED (ms) |
| `ledOffMs` | `int?` | - | Czas wyłączenia LED (ms) |
| `visibility` | `NotificationVisibility?` | - | Widoczność na ekranie blokady |
| `timeoutAfter` | `int?` | - | Automatyczne zamknięcie po czasie (ms) |
| `fullScreenIntent` | `bool?` | `false` | Uruchomienie jako intent pełnoekranowy |
| `shortcutId` | `String?` | - | ID skrótu |
| `additionalFlags` | `List<int>?` | - | Dodatkowe flagi |
| `tag` | `String?` | - | Tag powiadomienia |
| `actions` | `List<AndroidNotificationAction>?` | - | Przyciski akcji |
| `colorized` | `bool?` | `false` | Włączenie koloryzacji |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Użycie atrybutów audio |

<div id="ios-configuration"></div>

### Konfiguracja iOS

Przekaż `IOSNotificationConfig` do `setIOSConfig()`:

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

#### Właściwości IOSNotificationConfig

| Właściwość | Typ | Domyślna wartość | Opis |
|------------|-----|------------------|------|
| `presentList` | `bool?` | `true` | Wyświetlanie na liście powiadomień |
| `presentAlert` | `bool?` | `true` | Wyświetlanie alertu |
| `presentBadge` | `bool?` | `true` | Aktualizacja odznaki aplikacji |
| `presentSound` | `bool?` | `true` | Odtwarzanie dźwięku |
| `presentBanner` | `bool?` | `true` | Wyświetlanie baneru |
| `sound` | `String?` | - | Nazwa pliku dźwiękowego |
| `badgeNumber` | `int?` | - | Numer odznaki |
| `threadIdentifier` | `String?` | - | Identyfikator wątku do grupowania |
| `categoryIdentifier` | `String?` | - | Identyfikator kategorii dla akcji |
| `interruptionLevel` | `InterruptionLevel?` | - | Poziom przerwania |

### Załączniki (tylko iOS)

Dodaj załączniki obrazów, audio lub wideo do powiadomień iOS. Załączniki są pobierane z URL:

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

## Zarządzanie powiadomieniami

### Anulowanie konkretnego powiadomienia

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Anulowanie wszystkich powiadomień

``` dart
await LocalNotification.cancelAllNotifications();
```

### Czyszczenie licznika odznaki

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Uprawnienia

Poproś użytkownika o uprawnienia do powiadomień:

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

Uprawnienia są automatycznie żądane przy pierwszym wysłaniu powiadomienia za pomocą `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Konfiguracja platformy

### Konfiguracja iOS

Dodaj do pliku `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Konfiguracja Android

Dodaj do pliku `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Referencja API

### Metody statyczne

| Metoda | Sygnatura | Opis |
|--------|-----------|------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Wyślij powiadomienie ze wszystkimi opcjami |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Anuluj konkretne powiadomienie |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Anuluj wszystkie powiadomienia |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Poproś o uprawnienia do powiadomień |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Wyczyść licznik odznaki iOS |

### Metody instancji (łańcuchowe)

| Metoda | Parametry | Zwraca | Opis |
|--------|-----------|--------|------|
| `addPayload` | `String payload` | `LocalNotification` | Ustaw dane payload |
| `addId` | `int id` | `LocalNotification` | Ustaw ID powiadomienia |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Ustaw podtytuł |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Ustaw numer odznaki |
| `addSound` | `String sound` | `LocalNotification` | Ustaw niestandardowy dźwięk |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Dodaj załącznik (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Ustaw konfigurację Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Ustaw konfigurację iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Wyślij powiadomienie |
