# Push Notifications

---

- [Introduction](#introduction)
- Usage
  - [Basic Usage](#basic-usage)
  - [Scheduled Notifications](#scheduled-notifications)
  - [Customizing Notifications](#customizing-notifications)
  - [Platform Specific Features](#platform-specific-features)
- [Managing Notifications](#managing-notifications)
  - [Canceling Notifications](#canceling-notifications)
  - [Requesting Permissions](#requesting-permissions)
- [Platform Setup](#platform-setup)
- [Available Methods](#available-methods)
- [API Reference](#api-reference)
  - [Main Properties](#main-properties)
  - [Android Specific Properties](#android-specific-properties)
  - [iOS Specific Properties](#ios-specific-properties)

<div id="introduction"></div>

## Introduction

The Push Notification system in Nylo provides a powerful way to send local notifications to users. This feature allows you to send immediate or scheduled notifications with rich content and customizable behaviors for both iOS and Android platforms.

<div id="basic-usage"></div>

## Basic Usage

Here's how to send a basic push notification:

```dart
// Simple notification
await PushNotification(
  title: "Hello",
  body: "This is a basic notification"
).send();

// Using the helper function
await pushNotification("Hello", "This is a basic notification").send();

// Using the static method
await PushNotification.sendNotification(
  title: "Hello",
  body: "This is a basic notification"
);
```

<div id="scheduled-notifications"></div>

## Scheduled Notifications

You can schedule notifications to be delivered at a specific time:

```dart
// Schedule a notification for tomorrow
final tomorrow = DateTime.now().add(Duration(days: 1));
await PushNotification(
  title: "Reminder",
  body: "Don't forget your appointment!"
).send(at: tomorrow);

// Using the static method
await PushNotification.sendNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
  at: tomorrow
);
```

<div id="customizing-notifications"></div>

## Customizing Notifications

### Adding Attachments
```dart
await PushNotification(
  title: "New Photo",
  body: "Check out this image!"
)
  .addAttachment(
    "https://example.com/image.jpg",
    "photo.jpg"
  )
  .send();
```

### Setting Priority and Importance
```dart
await PushNotification(
  title: "Urgent Message",
  body: "This is important!"
)
  .addPriority(Priority.high)
  .addImportance(Importance.max)
  .send();
```

### Adding Actions
```dart
final actions = [
  AndroidNotificationAction(
    'accept',
    'Accept',
    showsUserInterface: true,
  ),
  AndroidNotificationAction(
    'decline',
    'Decline',
    showsUserInterface: true,
  ),
];

await PushNotification(
  title: "Meeting Request",
  body: "Would you like to attend?"
)
  .addActions(actions)
  .send();
```

<div id="platform-specific-features"></div>
## Platform Specific Features

### iOS Specific Features
```dart
await PushNotification(
  title: "iOS Notification",
  body: "This is an iOS specific notification"
)
  .addBadgeNumber(1)
  .addSound("custom_sound.wav")
  .addThreadIdentifier("thread_1")
  .addInterruptionLevel(InterruptionLevel.active)
  .send();
```

### Android Specific Features
```dart
await PushNotification(
  title: "Android Notification",
  body: "This is an Android specific notification"
)
  .addChannelId("custom_channel")
  .addChannelName("Custom Channel")
  .addChannelDescription("Notifications from custom channel")
  .addVibrationPattern([0, 1000, 500, 1000])
  .addLedColor(Color(0xFF00FF00))
  .send();
```

<div id="managing-notifications"></div>

## Managing Notifications

You can cancel specific notifications or all notifications, as well as request permissions for notifications.

<div id="canceling-notifications"></div>

### Canceling Notifications
```dart
// Cancel a specific notification
await PushNotification.cancelNotification(1);

// Cancel all notifications
await PushNotification.cancelAllNotifications();
```

<div id="requesting-permissions"></div>

### Requesting Permissions
```dart
// Request basic permissions
await PushNotification.requestPermissions();

// Request specific permissions
await PushNotification.requestPermissions(
  alert: true,
  badge: true,
  sound: true,
  provisional: false,
  critical: false,
  vibrate: true
);
```

<div id="platform-setup"></div>
## Platform Setup

### iOS Setup
1. Add the following to your `Info.plist`:
```json
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android Setup
1. Add the following permissions to your `AndroidManifest.xml`:
```json
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="available-methods"></div>
## Available Methods

### General Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Adds an attachment to the notification |
| `addPayload` | `String payload` | Adds custom data to the notification |
| `addId` | `int id` | Sets a unique identifier for the notification |
| `addSubtitle` | `String subtitle` | Adds a subtitle to the notification |
| `addBadgeNumber` | `int badgeNumber` | Sets the badge number for the app icon |
| `addSound` | `String sound` | Sets a custom sound for the notification |

### Channel Configuration Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addChannelId` | `String channelId` | Sets the notification channel ID |
| `addChannelName` | `String channelName` | Sets the notification channel name |
| `addChannelDescription` | `String channelDescription` | Sets the notification channel description |
| `addImportance` | `Importance importance` | Sets the importance level of the notification |
| `addPriority` | `Priority priority` | Sets the priority level of the notification |

### Visual Customization Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addTicker` | `String ticker` | Sets the ticker text for Android |
| `addIcon` | `String icon` | Sets the notification icon |
| `addColor` | `Color color` | Sets the notification color |
| `addLargeIcon` | `String largeIcon` | Sets a large icon for the notification |
| `addShowWhen` | `bool showWhen` | Controls whether to show the timestamp |
| `addWhen` | `int when` | Sets a custom timestamp for the notification |

### Behavior Configuration Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addPlaySound` | `bool playSound` | Controls whether to play a sound |
| `addEnableVibration` | `bool enableVibration` | Controls vibration |
| `addVibrationPattern` | `List<int> vibrationPattern` | Sets a custom vibration pattern |
| `addAutoCancel` | `bool autoCancel` | Controls if notification dismisses on tap |
| `addOngoing` | `bool ongoing` | Makes the notification ongoing |
| `addSilent` | `bool silent` | Makes the notification silent |

### Progress Indicator Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addShowProgress` | `bool showProgress` | Shows/hides progress indicator |
| `addMaxProgress` | `int maxProgress` | Sets maximum progress value |
| `addProgress` | `int progress` | Sets current progress value |
| `addIndeterminate` | `bool indeterminate` | Sets progress as indeterminate |

### LED Configuration Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addEnableLights` | `bool enableLights` | Enables notification LED |
| `addLedColor` | `Color ledColor` | Sets LED color |
| `addLedOnMs` | `int ledOnMs` | Sets LED on duration |
| `addLedOffMs` | `int ledOffMs` | Sets LED off duration |

### iOS Specific Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addPresentList` | `bool presentList` | Controls list presentation |
| `addPresentAlert` | `bool presentAlert` | Controls alert presentation |
| `addPresentBadge` | `bool presentBadge` | Controls badge presentation |
| `addPresentSound` | `bool presentSound` | Controls sound presentation |
| `addPresentBanner` | `bool presentBanner` | Controls banner presentation |
| `addThreadIdentifier` | `String threadIdentifier` | Sets thread identifier |
| `addCategoryIdentifier` | `String categoryIdentifier` | Sets category identifier |
| `addInterruptionLevel` | `InterruptionLevel interruptionLevel` | Sets interruption level |

### Android Specific Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `addGroupKey` | `String groupKey` | Sets notification group |
| `addSetAsGroupSummary` | `bool setAsGroupSummary` | Sets as group summary |
| `addGroupAlertBehavior` | `GroupAlertBehavior groupAlertBehavior` | Sets group alert behavior |
| `addOnlyAlertOnce` | `bool onlyAlertOnce` | Controls alert frequency |
| `addUsesChronometer` | `bool usesChronometer` | Enables chronometer |
| `addChronometerCountDown` | `bool chronometerCountDown` | Sets chronometer countdown |
| `addChannelShowBadge` | `bool channelShowBadge` | Controls channel badge |
| `addChannelAction` | `AndroidNotificationChannelAction channelAction` | Sets channel action |
| `addVisibility` | `NotificationVisibility visibility` | Sets notification visibility |
| `addTimeoutAfter` | `int timeoutAfter` | Sets notification timeout |
| `addFullScreenIntent` | `bool fullScreenIntent` | Enables full screen intent |
| `addShortcutId` | `String shortcutId` | Sets shortcut ID |
| `addAdditionalFlags` | `List<int> additionalFlags` | Adds additional flags |
| `addTag` | `String tag` | Adds notification tag |
| `addActions` | `List<AndroidNotificationAction> actions` | Adds notification actions |
| `addColorized` | `bool colorized` | Enables colorization |
| `addAudioAttributesUsage` | `AudioAttributesUsage audioAttributesUsage` | Sets audio attributes |

<div id="api-reference"></div>
## API Reference

This section provides a reference for the properties and methods available for the `PushNotification` class.

<div id="main-properties"></div>

### Main Properties

| Property | Type | Description |
|----------|------|-------------|
| title | String | The title of the notification |
| body | String | The content of the notification |
| payload | String? | Custom data to be passed with the notification |
| id | int? | Unique identifier for the notification |
| subtitle | String? | Additional text shown below the title (iOS) |
| sound | String? | Custom sound file name |

<div id="android-specific-properties"></div>

### Android Specific Properties

| Property | Type | Description |
|----------|------|-------------|
| channelId | String? | Identifier for the notification channel |
| channelName | String? | Display name for the notification channel |
| importance | Importance? | The importance level of the notification |
| priority | Priority? | The priority level of the notification |
| ticker | String? | Text shown in the status bar when the notification arrives |

<div id="ios-specific-properties"></div>

### iOS Specific Properties

| Property | Type | Description |
|----------|------|-------------|
| badgeNumber | int? | Number to display on the app icon |
| threadIdentifier | String? | Identifier for grouping related notifications |
| interruptionLevel | InterruptionLevel? | Controls how the system presents the notification |
| presentAlert | bool? | Whether to display an alert |
| presentSound | bool? | Whether to play a sound |