# App-Icons

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [App-Icons generieren](#generating-app-icons "App-Icons generieren")
- [Eigenes App-Icon hinzufügen](#adding-your-app-icon "Eigenes App-Icon hinzufügen")
- [Anforderungen an App-Icons](#app-icon-requirements "Anforderungen an App-Icons")
- [Konfiguration](#configuration "Konfiguration")
- [Badge-Zähler](#badge-count "Badge-Zähler")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 verwendet <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a>, um App-Icons für iOS und Android aus einem einzigen Quellbild zu generieren.

Ihr App-Icon sollte im Verzeichnis `assets/app_icon/` abgelegt werden und eine Größe von **1024x1024 Pixeln** haben.

<div id="generating-app-icons"></div>

## App-Icons generieren

Führen Sie den folgenden Befehl aus, um Icons für alle Plattformen zu generieren:

``` bash
dart run flutter_launcher_icons
```

Dieser Befehl liest Ihr Quell-Icon aus `assets/app_icon/` und generiert:
- iOS-Icons in `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Android-Icons in `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Eigenes App-Icon hinzufügen

1. Erstellen Sie Ihr Icon als **1024x1024 PNG**-Datei
2. Platzieren Sie es in `assets/app_icon/` (z.B. `assets/app_icon/icon.png`)
3. Aktualisieren Sie bei Bedarf den `image_path` in Ihrer `pubspec.yaml`:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Führen Sie den Befehl zur Icon-Generierung aus

<div id="app-icon-requirements"></div>

## Anforderungen an App-Icons

| Eigenschaft | Wert |
|-------------|------|
| Format | PNG |
| Größe | 1024x1024 Pixel |
| Ebenen | Flach ohne Transparenz |

### Dateibenennung

Halten Sie Dateinamen einfach und ohne Sonderzeichen:
- `app_icon.png`
- `icon.png`

### Plattform-Richtlinien

Für detaillierte Anforderungen beachten Sie die offiziellen Plattform-Richtlinien:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Konfiguration

Passen Sie die Icon-Generierung in Ihrer `pubspec.yaml` an:

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

Siehe die <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons Dokumentation</a> für alle verfügbaren Optionen.

<div id="badge-count"></div>

## Badge-Zähler

{{ config('app.name') }} bietet Hilfsfunktionen zur Verwaltung von App-Badge-Zählern (die auf dem App-Icon angezeigte Zahl):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Plattformunterstützung

Badge-Zähler werden unterstützt auf:
- **iOS**: Native Unterstützung
- **Android**: Erfordert Launcher-Unterstützung (die meisten Launcher unterstützen dies)
- **Web**: Nicht unterstützt

### Anwendungsfälle

Häufige Szenarien für Badge-Zähler:
- Ungelesene Benachrichtigungen
- Ausstehende Nachrichten
- Artikel im Warenkorb
- Unerledigte Aufgaben

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

