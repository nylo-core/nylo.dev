# Local Notifications

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Scheduled Notifications](#scheduled-notifications "Scheduled Notifications")
- [Builder Pattern](#builder-pattern "Builder Pattern")
- [Platform Configuration](#platform-configuration "Platform Configuration")
  - [Android Configuration](#android-configuration "Android Configuration")
  - [iOS Configuration](#ios-configuration "iOS Configuration")
- [Managing Notifications](#managing-notifications "Managing Notifications")
- [Permissions](#permissions "Permissions")
- [Platform Setup](#platform-setup "Platform Setup")
- [API Reference](#api-reference "API Reference")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} provides a local notification system through the `LocalNotification` class. This allows you to send immediate or scheduled notifications with rich content on iOS and Android.

> Local notifications are not supported on the web. Attempting to use them on web will throw a `NotificationException`.

<div id="basic-usage"></div>

## Basic Usage

Send a notification using the builder pattern or the static method:

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

## Scheduled Notifications

Schedule a notification to be delivered at a specific time:

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

## Builder Pattern

The `LocalNotification` class provides a fluent builder API. All chainable methods return the `LocalNotification` instance:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Chainable Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addPayload` | `String payload` | Sets custom data string for the notification |
| `addId` | `int id` | Sets a unique identifier for the notification |
| `addSubtitle` | `String subtitle` | Sets the subtitle text |
| `addBadgeNumber` | `int badgeNumber` | Sets the app icon badge number |
| `addSound` | `String sound` | Sets a custom sound file name |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Adds an attachment (iOS only, downloads from URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Sets Android-specific configuration |
| `setIOSConfig` | `IOSNotificationConfig config` | Sets iOS-specific configuration |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

If `at` is provided, schedules the notification for that time. Otherwise shows it immediately.

<div id="platform-configuration"></div>

## Platform Configuration

In v7, platform-specific options are configured through `AndroidNotificationConfig` and `IOSNotificationConfig` objects.

<div id="android-configuration"></div>

### Android Configuration

Pass an `AndroidNotificationConfig` to `setAndroidConfig()`:

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

#### AndroidNotificationConfig Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | Notification channel ID |
| `channelName` | `String?` | `'Default Channel'` | Notification channel name |
| `channelDescription` | `String?` | `'Default Channel'` | Channel description |
| `importance` | `Importance?` | `Importance.max` | Notification importance level |
| `priority` | `Priority?` | `Priority.high` | Notification priority |
| `ticker` | `String?` | `'ticker'` | Ticker text for accessibility |
| `icon` | `String?` | `'app_icon'` | Small icon resource name |
| `playSound` | `bool?` | `true` | Whether to play a sound |
| `enableVibration` | `bool?` | `true` | Whether to enable vibration |
| `vibrationPattern` | `List<int>?` | - | Custom vibration pattern (ms) |
| `groupKey` | `String?` | - | Group key for notification grouping |
| `setAsGroupSummary` | `bool?` | `false` | Whether this is the group summary |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Alert behavior for groups |
| `autoCancel` | `bool?` | `true` | Auto-dismiss on tap |
| `ongoing` | `bool?` | `false` | Cannot be dismissed by user |
| `silent` | `bool?` | `false` | Silent notification |
| `color` | `Color?` | - | Accent color |
| `largeIcon` | `String?` | - | Large icon resource path |
| `onlyAlertOnce` | `bool?` | `false` | Only alert on first show |
| `showWhen` | `bool?` | `true` | Show timestamp |
| `when` | `int?` | - | Custom timestamp (ms since epoch) |
| `usesChronometer` | `bool?` | `false` | Use chronometer display |
| `chronometerCountDown` | `bool?` | `false` | Chronometer counts down |
| `channelShowBadge` | `bool?` | `true` | Show badge on channel |
| `showProgress` | `bool?` | `false` | Show progress indicator |
| `maxProgress` | `int?` | `0` | Maximum progress value |
| `progress` | `int?` | `0` | Current progress value |
| `indeterminate` | `bool?` | `false` | Indeterminate progress |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Channel action |
| `enableLights` | `bool?` | `false` | Enable notification LED |
| `ledColor` | `Color?` | - | LED color |
| `ledOnMs` | `int?` | - | LED on duration (ms) |
| `ledOffMs` | `int?` | - | LED off duration (ms) |
| `visibility` | `NotificationVisibility?` | - | Lock screen visibility |
| `timeoutAfter` | `int?` | - | Auto-dismiss timeout (ms) |
| `fullScreenIntent` | `bool?` | `false` | Launch as full screen intent |
| `shortcutId` | `String?` | - | Shortcut ID |
| `additionalFlags` | `List<int>?` | - | Additional flags |
| `tag` | `String?` | - | Notification tag |
| `actions` | `List<AndroidNotificationAction>?` | - | Action buttons |
| `colorized` | `bool?` | `false` | Enable colorization |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Audio attributes usage |

<div id="ios-configuration"></div>

### iOS Configuration

Pass an `IOSNotificationConfig` to `setIOSConfig()`:

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

#### IOSNotificationConfig Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | Present in notification list |
| `presentAlert` | `bool?` | `true` | Present an alert |
| `presentBadge` | `bool?` | `true` | Update app badge |
| `presentSound` | `bool?` | `true` | Play sound |
| `presentBanner` | `bool?` | `true` | Present banner |
| `sound` | `String?` | - | Sound file name |
| `badgeNumber` | `int?` | - | Badge number |
| `threadIdentifier` | `String?` | - | Thread identifier for grouping |
| `categoryIdentifier` | `String?` | - | Category identifier for actions |
| `interruptionLevel` | `InterruptionLevel?` | - | Interruption level |

### Attachments (iOS Only)

Add image, audio, or video attachments to iOS notifications. Attachments are downloaded from a URL:

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

## Managing Notifications

### Cancel a Specific Notification

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Cancel All Notifications

``` dart
await LocalNotification.cancelAllNotifications();
```

### Clear Badge Count

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Permissions

Request notification permissions from the user:

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

Permissions are automatically requested on the first notification send via `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Platform Setup

### iOS Setup

Add to your `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android Setup

Add to your `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API Reference

### Static Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Send a notification with all options |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Cancel a specific notification |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Cancel all notifications |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Request notification permissions |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Clear the iOS badge count |

### Instance Methods (Chainable)

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | Set payload data |
| `addId` | `int id` | `LocalNotification` | Set notification ID |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Set subtitle |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Set badge number |
| `addSound` | `String sound` | `LocalNotification` | Set custom sound |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Add attachment (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Set Android config |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Set iOS config |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Send the notification |

