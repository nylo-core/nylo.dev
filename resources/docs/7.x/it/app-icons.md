# Icone dell'App

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Generare le Icone dell'App](#generating-app-icons "Generare le Icone dell'App")
- [Aggiungere la Tua Icona dell'App](#adding-your-app-icon "Aggiungere la Tua Icona dell'App")
- [Requisiti dell'Icona dell'App](#app-icon-requirements "Requisiti dell'Icona dell'App")
- [Configurazione](#configuration "Configurazione")
- [Conteggio Badge](#badge-count "Conteggio Badge")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 utilizza <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> per generare le icone dell'app per iOS e Android da una singola immagine sorgente.

L'icona della tua app dovrebbe essere posizionata nella directory `assets/app_icon/` con una dimensione di **1024x1024 pixel**.

<div id="generating-app-icons"></div>

## Generare le Icone dell'App

Esegui il seguente comando per generare le icone per tutte le piattaforme:

``` bash
dart run flutter_launcher_icons
```

Questo legge la tua icona sorgente da `assets/app_icon/` e genera:
- Icone iOS in `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Icone Android in `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Aggiungere la Tua Icona dell'App

1. Crea la tua icona come file **PNG 1024x1024**
2. Posizionala in `assets/app_icon/` (es. `assets/app_icon/icon.png`)
3. Aggiorna il `image_path` nel tuo `pubspec.yaml` se necessario:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Esegui il comando di generazione delle icone

<div id="app-icon-requirements"></div>

## Requisiti dell'Icona dell'App

| Attributo | Valore |
|-----------|--------|
| Formato | PNG |
| Dimensione | 1024x1024 pixel |
| Livelli | Appiattiti senza trasparenza |

### Denominazione dei File

Mantieni i nomi dei file semplici senza caratteri speciali:
- `app_icon.png`
- `icon.png`

### Linee Guida della Piattaforma

Per requisiti dettagliati, consulta le linee guida ufficiali della piattaforma:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Configurazione

Personalizza la generazione delle icone nel tuo `pubspec.yaml`:

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

Consulta la <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">documentazione di flutter_launcher_icons</a> per tutte le opzioni disponibili.

<div id="badge-count"></div>

## Conteggio Badge

{{ config('app.name') }} fornisce funzioni helper per gestire il conteggio dei badge dell'app (il numero mostrato sull'icona dell'app):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Supporto Piattaforma

I conteggi badge sono supportati su:
- **iOS**: Supporto nativo
- **Android**: Richiede il supporto del launcher (la maggior parte dei launcher lo supporta)
- **Web**: Non supportato

### Casi d'Uso

Scenari comuni per i conteggi badge:
- Notifiche non lette
- Messaggi in sospeso
- Articoli nel carrello
- Attivita' incomplete

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

