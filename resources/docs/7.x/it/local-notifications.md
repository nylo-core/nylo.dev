# Notifiche Locali

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Notifiche Programmate](#scheduled-notifications "Notifiche Programmate")
- [Pattern Builder](#builder-pattern "Pattern Builder")
- [Configurazione della Piattaforma](#platform-configuration "Configurazione della Piattaforma")
  - [Configurazione Android](#android-configuration "Configurazione Android")
  - [Configurazione iOS](#ios-configuration "Configurazione iOS")
- [Gestione delle Notifiche](#managing-notifications "Gestione delle Notifiche")
- [Permessi](#permissions "Permessi")
- [Configurazione della Piattaforma](#platform-setup "Configurazione della Piattaforma")
- [Riferimento API](#api-reference "Riferimento API")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} fornisce un sistema di notifiche locali attraverso la classe `LocalNotification`. Questo ti permette di inviare notifiche immediate o programmate con contenuti ricchi su iOS e Android.

> Le notifiche locali non sono supportate sul web. Il tentativo di usarle sul web generera' un `NotificationException`.

<div id="basic-usage"></div>

## Utilizzo Base

Invia una notifica usando il pattern builder o il metodo statico:

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

## Notifiche Programmate

Programma una notifica da consegnare a un orario specifico:

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

## Pattern Builder

La classe `LocalNotification` fornisce un'API builder fluente. Tutti i metodi concatenabili restituiscono l'istanza `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Metodi Concatenabili

| Metodo | Parametri | Descrizione |
|--------|------------|-------------|
| `addPayload` | `String payload` | Imposta una stringa dati personalizzata per la notifica |
| `addId` | `int id` | Imposta un identificatore unico per la notifica |
| `addSubtitle` | `String subtitle` | Imposta il testo del sottotitolo |
| `addBadgeNumber` | `int badgeNumber` | Imposta il numero del badge dell'icona dell'app |
| `addSound` | `String sound` | Imposta un file audio personalizzato |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Aggiunge un allegato (solo iOS, scarica da URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Imposta la configurazione specifica per Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Imposta la configurazione specifica per iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Se `at` e' fornito, programma la notifica per quell'orario. Altrimenti la mostra immediatamente.

<div id="platform-configuration"></div>

## Configurazione della Piattaforma

Le opzioni specifiche della piattaforma sono configurate tramite gli oggetti `AndroidNotificationConfig` e `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Configurazione Android

Passa un `AndroidNotificationConfig` a `setAndroidConfig()`:

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

#### Proprieta' di AndroidNotificationConfig

| Proprieta' | Tipo | Predefinito | Descrizione |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | ID del canale di notifica |
| `channelName` | `String?` | `'Default Channel'` | Nome del canale di notifica |
| `channelDescription` | `String?` | `'Default Channel'` | Descrizione del canale |
| `importance` | `Importance?` | `Importance.max` | Livello di importanza della notifica |
| `priority` | `Priority?` | `Priority.high` | Priorita' della notifica |
| `ticker` | `String?` | `'ticker'` | Testo ticker per l'accessibilita' |
| `icon` | `String?` | `'app_icon'` | Nome risorsa icona piccola |
| `playSound` | `bool?` | `true` | Se riprodurre un suono |
| `enableVibration` | `bool?` | `true` | Se abilitare la vibrazione |
| `vibrationPattern` | `List<int>?` | - | Pattern di vibrazione personalizzato (ms) |
| `groupKey` | `String?` | - | Chiave di gruppo per il raggruppamento delle notifiche |
| `setAsGroupSummary` | `bool?` | `false` | Se questa e' il riepilogo del gruppo |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Comportamento degli avvisi per i gruppi |
| `autoCancel` | `bool?` | `true` | Eliminazione automatica al tocco |
| `ongoing` | `bool?` | `false` | Non puo' essere eliminata dall'utente |
| `silent` | `bool?` | `false` | Notifica silenziosa |
| `color` | `Color?` | - | Colore accento |
| `largeIcon` | `String?` | - | Percorso risorsa icona grande |
| `onlyAlertOnce` | `bool?` | `false` | Avvisa solo alla prima visualizzazione |
| `showWhen` | `bool?` | `true` | Mostra timestamp |
| `when` | `int?` | - | Timestamp personalizzato (ms dall'epoca) |
| `usesChronometer` | `bool?` | `false` | Usa visualizzazione cronometro |
| `chronometerCountDown` | `bool?` | `false` | Il cronometro conta alla rovescia |
| `channelShowBadge` | `bool?` | `true` | Mostra badge sul canale |
| `showProgress` | `bool?` | `false` | Mostra indicatore di progresso |
| `maxProgress` | `int?` | `0` | Valore massimo del progresso |
| `progress` | `int?` | `0` | Valore corrente del progresso |
| `indeterminate` | `bool?` | `false` | Progresso indeterminato |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Azione del canale |
| `enableLights` | `bool?` | `false` | Abilita LED di notifica |
| `ledColor` | `Color?` | - | Colore LED |
| `ledOnMs` | `int?` | - | Durata LED acceso (ms) |
| `ledOffMs` | `int?` | - | Durata LED spento (ms) |
| `visibility` | `NotificationVisibility?` | - | Visibilita' sulla schermata di blocco |
| `timeoutAfter` | `int?` | - | Timeout di eliminazione automatica (ms) |
| `fullScreenIntent` | `bool?` | `false` | Avvia come intent a schermo intero |
| `shortcutId` | `String?` | - | ID scorciatoia |
| `additionalFlags` | `List<int>?` | - | Flag aggiuntivi |
| `tag` | `String?` | - | Tag della notifica |
| `actions` | `List<AndroidNotificationAction>?` | - | Pulsanti di azione |
| `colorized` | `bool?` | `false` | Abilita colorazione |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Utilizzo attributi audio |

<div id="ios-configuration"></div>

### Configurazione iOS

Passa un `IOSNotificationConfig` a `setIOSConfig()`:

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

#### Proprieta' di IOSNotificationConfig

| Proprieta' | Tipo | Predefinito | Descrizione |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | Mostra nell'elenco delle notifiche |
| `presentAlert` | `bool?` | `true` | Mostra un avviso |
| `presentBadge` | `bool?` | `true` | Aggiorna il badge dell'app |
| `presentSound` | `bool?` | `true` | Riproduci suono |
| `presentBanner` | `bool?` | `true` | Mostra banner |
| `sound` | `String?` | - | Nome file audio |
| `badgeNumber` | `int?` | - | Numero del badge |
| `threadIdentifier` | `String?` | - | Identificatore del thread per il raggruppamento |
| `categoryIdentifier` | `String?` | - | Identificatore della categoria per le azioni |
| `interruptionLevel` | `InterruptionLevel?` | - | Livello di interruzione |

### Allegati (Solo iOS)

Aggiungi allegati immagine, audio o video alle notifiche iOS. Gli allegati vengono scaricati da un URL:

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

## Gestione delle Notifiche

### Annullare una Notifica Specifica

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Annullare Tutte le Notifiche

``` dart
await LocalNotification.cancelAllNotifications();
```

### Cancellare il Conteggio dei Badge

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Permessi

Richiedi i permessi per le notifiche all'utente:

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

I permessi vengono richiesti automaticamente al primo invio di notifica tramite `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Configurazione della Piattaforma

### Configurazione iOS

Aggiungi al tuo `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Configurazione Android

Aggiungi al tuo `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Riferimento API

### Metodi Statici

| Metodo | Firma | Descrizione |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Invia una notifica con tutte le opzioni |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Annulla una notifica specifica |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Annulla tutte le notifiche |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Richiedi i permessi per le notifiche |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Cancella il conteggio dei badge iOS |

### Metodi di Istanza (Concatenabili)

| Metodo | Parametri | Ritorna | Descrizione |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | Imposta i dati del payload |
| `addId` | `int id` | `LocalNotification` | Imposta l'ID della notifica |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Imposta il sottotitolo |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Imposta il numero del badge |
| `addSound` | `String sound` | `LocalNotification` | Imposta il suono personalizzato |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Aggiungi allegato (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Imposta la configurazione Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Imposta la configurazione iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Invia la notifica |

