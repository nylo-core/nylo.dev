# App Icons

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Generating App Icons](#generating-app-icons "Generating app icons")
- [Adding Your App Icon](#adding-your-app-icon "Adding your app icon")
- [App Icon Requirements](#app-icon-requirements "App icon requirements")
- [Configuration](#configuration "Configuration")
- [Badge Count](#badge-count "Badge count")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 uses <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> to generate app icons for iOS and Android from a single source image.

Your app icon should be placed in the `assets/app_icon/` directory with a size of **1024x1024 pixels**.

<div id="generating-app-icons"></div>

## Generating App Icons

Run the following command to generate icons for all platforms:

``` bash
dart run flutter_launcher_icons
```

This reads your source icon from `assets/app_icon/` and generates:
- iOS icons in `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Android icons in `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Adding Your App Icon

1. Create your icon as a **1024x1024 PNG** file
2. Place it in `assets/app_icon/` (e.g., `assets/app_icon/icon.png`)
3. Update the `image_path` in your `pubspec.yaml` if needed:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Run the icon generation command

<div id="app-icon-requirements"></div>

## App Icon Requirements

| Attribute | Value |
|-----------|-------|
| Format | PNG |
| Size | 1024x1024 pixels |
| Layers | Flattened with no transparency |

### File Naming

Keep filenames simple without special characters:
- `app_icon.png`
- `icon.png`

### Platform Guidelines

For detailed requirements, refer to the official platform guidelines:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Configuration

Customize icon generation in your `pubspec.yaml`:

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

See the <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons documentation</a> for all available options.

<div id="badge-count"></div>

## Badge Count

{{ config('app.name') }} provides helper functions to manage app badge counts (the number shown on the app icon):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Platform Support

Badge counts are supported on:
- **iOS**: Native support
- **Android**: Requires launcher support (most launchers support this)
- **Web**: Not supported

### Use Cases

Common scenarios for badge counts:
- Unread notifications
- Pending messages
- Items in cart
- Incomplete tasks

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

