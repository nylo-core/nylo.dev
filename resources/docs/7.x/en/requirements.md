# Requirements

---

<a name="section-1"></a>
- [System Requirements](#system-requirements "System Requirements")
- [Installing Flutter](#installing-flutter "Installing Flutter")
- [Verifying Your Installation](#verifying-installation "Verifying Your Installation")
- [Set Up an Editor](#set-up-an-editor "Set Up an Editor")


<div id="system-requirements"></div>

## System Requirements

{{ config('app.name') }} v7 requires the following minimum versions:

| Requirement | Minimum Version |
|-------------|-----------------|
| **Flutter** | 3.24.0 or higher |
| **Dart SDK** | 3.10.7 or higher |

### Platform Support

{{ config('app.name') }} supports all platforms that Flutter supports:

| Platform | Support |
|----------|---------|
| iOS | ✓ Full support |
| Android | ✓ Full support |
| Web | ✓ Full support |
| macOS | ✓ Full support |
| Windows | ✓ Full support |
| Linux | ✓ Full support |

<div id="installing-flutter"></div>

## Installing Flutter

If you don't have Flutter installed, follow the official installation guide for your operating system:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter Installation Guide</a>

<div id="verifying-installation"></div>

## Verifying Your Installation

After installing Flutter, verify your setup:

### Check Flutter Version

``` bash
flutter --version
```

You should see output similar to:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Update Flutter (if needed)

If your Flutter version is below 3.24.0, upgrade to the latest stable release:

``` bash
flutter channel stable
flutter upgrade
```

### Run Flutter Doctor

Verify your development environment is properly configured:

``` bash
flutter doctor -v
```

This command checks for:
- Flutter SDK installation
- Android toolchain (for Android development)
- Xcode (for iOS/macOS development)
- Connected devices
- IDE plugins

Fix any issues reported before proceeding with {{ config('app.name') }} installation.

<div id="set-up-an-editor"></div>

## Set Up an Editor

Choose an IDE with Flutter support:

### Visual Studio Code (Recommended)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> is lightweight and has excellent Flutter support.

1. Install <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Install the <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter extension</a>
3. Install the <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart extension</a>

Setup guide: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter Setup</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> provides a full-featured IDE with built-in emulator support.

1. Install <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Install the Flutter plugin (Preferences → Plugins → Flutter)
3. Install the Dart plugin

Setup guide: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter Setup</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community or Ultimate) also supports Flutter development.

1. Install IntelliJ IDEA
2. Install the Flutter plugin (Preferences → Plugins → Flutter)
3. Install the Dart plugin

Once your editor is configured, you're ready to [install {{ config('app.name') }}](/docs/7.x/installation).
