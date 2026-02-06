# Lokale Benachrichtigungen

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Geplante Benachrichtigungen](#scheduled-notifications "Geplante Benachrichtigungen")
- [Builder-Pattern](#builder-pattern "Builder-Pattern")
- [Plattformkonfiguration](#platform-configuration "Plattformkonfiguration")
  - [Android-Konfiguration](#android-configuration "Android-Konfiguration")
  - [iOS-Konfiguration](#ios-configuration "iOS-Konfiguration")
- [Benachrichtigungen verwalten](#managing-notifications "Benachrichtigungen verwalten")
- [Berechtigungen](#permissions "Berechtigungen")
- [Plattform-Setup](#platform-setup "Plattform-Setup")
- [API-Referenz](#api-reference "API-Referenz")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} bietet ein lokales Benachrichtigungssystem über die Klasse `LocalNotification`. Dies ermöglicht es Ihnen, sofortige oder geplante Benachrichtigungen mit reichhaltigen Inhalten auf iOS und Android zu senden.

> Lokale Benachrichtigungen werden im Web nicht unterstützt. Der Versuch, sie im Web zu verwenden, löst eine `NotificationException` aus.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Senden Sie eine Benachrichtigung mit dem Builder-Pattern oder der statischen Methode:

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

## Geplante Benachrichtigungen

Planen Sie eine Benachrichtigung zur Zustellung zu einem bestimmten Zeitpunkt:

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

## Builder-Pattern

Die Klasse `LocalNotification` bietet eine fluent Builder-API. Alle verkettbaren Methoden geben die `LocalNotification`-Instanz zurück:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Verkettbare Methoden

| Methode | Parameter | Beschreibung |
|---------|-----------|-------------|
| `addPayload` | `String payload` | Setzt einen benutzerdefinierten Daten-String für die Benachrichtigung |
| `addId` | `int id` | Setzt einen eindeutigen Bezeichner für die Benachrichtigung |
| `addSubtitle` | `String subtitle` | Setzt den Untertiteltext |
| `addBadgeNumber` | `int badgeNumber` | Setzt die App-Icon-Badge-Nummer |
| `addSound` | `String sound` | Setzt einen benutzerdefinierten Sound-Dateinamen |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Fügt einen Anhang hinzu (nur iOS, Download von URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Setzt Android-spezifische Konfiguration |
| `setIOSConfig` | `IOSNotificationConfig config` | Setzt iOS-spezifische Konfiguration |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Wenn `at` angegeben wird, wird die Benachrichtigung für diesen Zeitpunkt geplant. Andernfalls wird sie sofort angezeigt.

<div id="platform-configuration"></div>

## Plattformkonfiguration

In v7 werden plattformspezifische Optionen über `AndroidNotificationConfig`- und `IOSNotificationConfig`-Objekte konfiguriert.

<div id="android-configuration"></div>

### Android-Konfiguration

Übergeben Sie eine `AndroidNotificationConfig` an `setAndroidConfig()`:

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

#### AndroidNotificationConfig-Eigenschaften

| Eigenschaft | Typ | Standard | Beschreibung |
|------------|-----|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | Benachrichtigungskanal-ID |
| `channelName` | `String?` | `'Default Channel'` | Benachrichtigungskanal-Name |
| `channelDescription` | `String?` | `'Default Channel'` | Kanalbeschreibung |
| `importance` | `Importance?` | `Importance.max` | Wichtigkeitsstufe der Benachrichtigung |
| `priority` | `Priority?` | `Priority.high` | Priorität der Benachrichtigung |
| `ticker` | `String?` | `'ticker'` | Ticker-Text für Barrierefreiheit |
| `icon` | `String?` | `'app_icon'` | Kleines Icon-Ressourcenname |
| `playSound` | `bool?` | `true` | Ob ein Sound abgespielt werden soll |
| `enableVibration` | `bool?` | `true` | Ob Vibration aktiviert werden soll |
| `vibrationPattern` | `List<int>?` | - | Benutzerdefiniertes Vibrationsmuster (ms) |
| `groupKey` | `String?` | - | Gruppenschlüssel für Benachrichtigungsgruppierung |
| `setAsGroupSummary` | `bool?` | `false` | Ob dies die Gruppenzusammenfassung ist |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Alarmverhalten für Gruppen |
| `autoCancel` | `bool?` | `true` | Automatisches Verwerfen beim Antippen |
| `ongoing` | `bool?` | `false` | Kann vom Benutzer nicht verworfen werden |
| `silent` | `bool?` | `false` | Stille Benachrichtigung |
| `color` | `Color?` | - | Akzentfarbe |
| `largeIcon` | `String?` | - | Großes Icon-Ressourcenpfad |
| `onlyAlertOnce` | `bool?` | `false` | Nur beim ersten Anzeigen alarmieren |
| `showWhen` | `bool?` | `true` | Zeitstempel anzeigen |
| `when` | `int?` | - | Benutzerdefinierter Zeitstempel (ms seit Epoch) |
| `usesChronometer` | `bool?` | `false` | Chronometer-Anzeige verwenden |
| `chronometerCountDown` | `bool?` | `false` | Chronometer zählt herunter |
| `channelShowBadge` | `bool?` | `true` | Badge auf Kanal anzeigen |
| `showProgress` | `bool?` | `false` | Fortschrittsanzeige anzeigen |
| `maxProgress` | `int?` | `0` | Maximaler Fortschrittswert |
| `progress` | `int?` | `0` | Aktueller Fortschrittswert |
| `indeterminate` | `bool?` | `false` | Unbestimmter Fortschritt |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Kanalaktion |
| `enableLights` | `bool?` | `false` | Benachrichtigungs-LED aktivieren |
| `ledColor` | `Color?` | - | LED-Farbe |
| `ledOnMs` | `int?` | - | LED-Ein-Dauer (ms) |
| `ledOffMs` | `int?` | - | LED-Aus-Dauer (ms) |
| `visibility` | `NotificationVisibility?` | - | Sperrbildschirm-Sichtbarkeit |
| `timeoutAfter` | `int?` | - | Automatisches Verwerfen-Timeout (ms) |
| `fullScreenIntent` | `bool?` | `false` | Als Vollbildabsicht starten |
| `shortcutId` | `String?` | - | Verknüpfungs-ID |
| `additionalFlags` | `List<int>?` | - | Zusätzliche Flags |
| `tag` | `String?` | - | Benachrichtigungs-Tag |
| `actions` | `List<AndroidNotificationAction>?` | - | Aktionsschaltflächen |
| `colorized` | `bool?` | `false` | Kolorierung aktivieren |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Audio-Attribute-Verwendung |

<div id="ios-configuration"></div>

### iOS-Konfiguration

Übergeben Sie eine `IOSNotificationConfig` an `setIOSConfig()`:

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

#### IOSNotificationConfig-Eigenschaften

| Eigenschaft | Typ | Standard | Beschreibung |
|------------|-----|---------|-------------|
| `presentList` | `bool?` | `true` | In Benachrichtigungsliste anzeigen |
| `presentAlert` | `bool?` | `true` | Alarm anzeigen |
| `presentBadge` | `bool?` | `true` | App-Badge aktualisieren |
| `presentSound` | `bool?` | `true` | Sound abspielen |
| `presentBanner` | `bool?` | `true` | Banner anzeigen |
| `sound` | `String?` | - | Sound-Dateiname |
| `badgeNumber` | `int?` | - | Badge-Nummer |
| `threadIdentifier` | `String?` | - | Thread-Bezeichner für Gruppierung |
| `categoryIdentifier` | `String?` | - | Kategorie-Bezeichner für Aktionen |
| `interruptionLevel` | `InterruptionLevel?` | - | Unterbrechungsstufe |

### Anhänge (nur iOS)

Fügen Sie Bild-, Audio- oder Videoanhänge zu iOS-Benachrichtigungen hinzu. Anhänge werden von einer URL heruntergeladen:

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

## Benachrichtigungen verwalten

### Eine bestimmte Benachrichtigung abbrechen

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Alle Benachrichtigungen abbrechen

``` dart
await LocalNotification.cancelAllNotifications();
```

### Badge-Zähler zurücksetzen

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Berechtigungen

Fordern Sie Benachrichtigungsberechtigungen vom Benutzer an:

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

Berechtigungen werden beim ersten Senden einer Benachrichtigung automatisch über `NyScheduler.taskOnce` angefordert.

<div id="platform-setup"></div>

## Plattform-Setup

### iOS-Setup

Fügen Sie zu Ihrer `Info.plist` hinzu:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android-Setup

Fügen Sie zu Ihrer `AndroidManifest.xml` hinzu:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API-Referenz

### Statische Methoden

| Methode | Signatur | Beschreibung |
|---------|----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Benachrichtigung mit allen Optionen senden |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Eine bestimmte Benachrichtigung abbrechen |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Alle Benachrichtigungen abbrechen |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Benachrichtigungsberechtigungen anfordern |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | iOS-Badge-Zähler zurücksetzen |

### Instanzmethoden (verkettbar)

| Methode | Parameter | Rückgabewert | Beschreibung |
|---------|-----------|-------------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | Payload-Daten setzen |
| `addId` | `int id` | `LocalNotification` | Benachrichtigungs-ID setzen |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Untertitel setzen |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Badge-Nummer setzen |
| `addSound` | `String sound` | `LocalNotification` | Benutzerdefinierten Sound setzen |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Anhang hinzufügen (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Android-Konfiguration setzen |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | iOS-Konfiguration setzen |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Benachrichtigung senden |

