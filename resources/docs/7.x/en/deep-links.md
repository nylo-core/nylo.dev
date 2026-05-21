# Deep Links

---

<a name="section-1"></a>

- [Introduction](#introduction "Introduction")
- [Getting Started](#getting-started "Getting Started")
  - [Enable Deep Links](#enable-deep-links "Enable Deep Links")
  - [Generate a Provider](#generate-a-provider "Generate a Provider")
- [Platform Configuration](#platform-configuration "Platform Configuration")
  - [Android Configuration](#android-configuration "Android Configuration")
  - [iOS Configuration](#ios-configuration "iOS Configuration")
- [Intercepting Links](#intercepting-links "Intercepting Links")
- [Fallback Route](#fallback-route "Fallback Route")
- [Testing](#testing "Testing")
- [API Reference](#api-reference "API Reference")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} ships with first-class deep link support powered by [`app_links`](https://pub.dev/packages/app_links). Incoming URIs from Android App Links, iOS Universal Links, custom URL schemes, and web URLs are captured automatically and routed through your registered routes.

A single line enables the full pipeline:

```dart
nylo.useDeepLinks();
```

When a URI arrives, {{ config('app.name') }} extracts the path and query parameters and calls `routeTo()` with them. Cold-start launches (the app being opened by a link) are replayed once the navigator is ready, and warm-start links (received while the app is running) are handled the same way.

<div id="getting-started"></div>

## Getting Started

<div id="enable-deep-links"></div>

### Enable Deep Links

Inside any provider's `setup` method, call `useDeepLinks()`:

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

Register the provider in `config/providers.dart` so it runs during app boot.

<div id="generate-a-provider"></div>

### Generate a Provider

Metro scaffolds the provider for you:

```bash
dart run nylo_framework:main make:deep_link_provider
```

This creates `lib/app/providers/deep_link_provider.dart` with `useDeepLinks()` pre-wired and a commented `onIncomingLink` example. The provider is also added to `config/providers.dart` automatically.

<div id="platform-configuration"></div>

## Platform Configuration

<div id="android-configuration"></div>

### Android Configuration

Add an `<intent-filter>` to your main `<activity>` in `android/app/src/main/AndroidManifest.xml`. The example below accepts both Android App Links (`https://example.com/...`) and a custom scheme (`myapp://...`):

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

For verified App Links, host an `assetlinks.json` file at `https://example.com/.well-known/assetlinks.json`. See the <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links guide</a> for full details.

<div id="ios-configuration"></div>

### iOS Configuration

For **custom URL schemes**, add a `CFBundleURLTypes` entry to `ios/Runner/Info.plist`:

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

For **Universal Links**, enable the Associated Domains capability in Xcode and host an `apple-app-site-association` file on your domain. See the <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links guide</a> for the full walkthrough.

<div id="intercepting-links"></div>

## Intercepting Links

Use `onIncomingLink` to run logic before {{ config('app.name') }} routes the URI. Return `true` to let {{ config('app.name') }} route automatically, or `false` to handle the URI yourself:

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

The callback receives the full `Uri`, including scheme, host, path, and query parameters.

<div id="fallback-route"></div>

## Fallback Route

Pass `fallbackRoute` to `useDeepLinks()` to land somewhere sensible when an incoming URI's path is not registered:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

When the URI's path matches a registered route, {{ config('app.name') }} routes to it. Otherwise, the user lands on the fallback.

<div id="testing"></div>

## Testing

Simulate incoming deep links from the command line:

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

Kill the app between tests to verify cold-start behavior, and trigger the URL with the app foregrounded to verify warm-start.

<div id="api-reference"></div>

## API Reference

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Enables platform deep-link capture. Call this inside a provider's `setup` method.

| Parameter | Type | Description |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Route to navigate to when an incoming URI's path is not registered. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Registers a callback invoked for every incoming URI. Return `true` to let {{ config('app.name') }} route automatically, or `false` to suppress automatic routing.

| Parameter | Type | Description |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Receives the incoming URI. |
