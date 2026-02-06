# Notifications locales

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Notifications planifiees](#scheduled-notifications "Notifications planifiees")
- [Patron de construction](#builder-pattern "Patron de construction")
- [Configuration par plateforme](#platform-configuration "Configuration par plateforme")
  - [Configuration Android](#android-configuration "Configuration Android")
  - [Configuration iOS](#ios-configuration "Configuration iOS")
- [Gestion des notifications](#managing-notifications "Gestion des notifications")
- [Permissions](#permissions "Permissions")
- [Configuration de la plateforme](#platform-setup "Configuration de la plateforme")
- [Reference API](#api-reference "Reference API")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} fournit un systeme de notifications locales via la classe `LocalNotification`. Cela vous permet d'envoyer des notifications immediates ou planifiees avec du contenu riche sur iOS et Android.

> Les notifications locales ne sont pas prises en charge sur le web. Tenter de les utiliser sur le web lancera une `NotificationException`.

<div id="basic-usage"></div>

## Utilisation de base

Envoyez une notification en utilisant le patron de construction ou la methode statique :

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

## Notifications planifiees

Planifiez une notification pour qu'elle soit livree a un moment specifique :

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

## Patron de construction

La classe `LocalNotification` fournit une API de construction fluide. Toutes les methodes chainables retournent l'instance `LocalNotification` :

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Methodes chainables

| Methode | Parametres | Description |
|---------|------------|-------------|
| `addPayload` | `String payload` | Definit une chaine de donnees personnalisees pour la notification |
| `addId` | `int id` | Definit un identifiant unique pour la notification |
| `addSubtitle` | `String subtitle` | Definit le texte du sous-titre |
| `addBadgeNumber` | `int badgeNumber` | Definit le numero de badge de l'icone de l'application |
| `addSound` | `String sound` | Definit un fichier sonore personnalise |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Ajoute une piece jointe (iOS uniquement, telecharge depuis une URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Definit la configuration specifique a Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Definit la configuration specifique a iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Si `at` est fourni, planifie la notification pour ce moment. Sinon l'affiche immediatement.

<div id="platform-configuration"></div>

## Configuration par plateforme

Les options specifiques a la plateforme sont configurees via les objets `AndroidNotificationConfig` et `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Configuration Android

Passez un `AndroidNotificationConfig` a `setAndroidConfig()` :

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

#### Proprietes de AndroidNotificationConfig

| Propriete | Type | Par defaut | Description |
|-----------|------|------------|-------------|
| `channelId` | `String?` | `'default_channel'` | ID du canal de notification |
| `channelName` | `String?` | `'Default Channel'` | Nom du canal de notification |
| `channelDescription` | `String?` | `'Default Channel'` | Description du canal |
| `importance` | `Importance?` | `Importance.max` | Niveau d'importance de la notification |
| `priority` | `Priority?` | `Priority.high` | Priorite de la notification |
| `ticker` | `String?` | `'ticker'` | Texte du ticker pour l'accessibilite |
| `icon` | `String?` | `'app_icon'` | Nom de la ressource de la petite icone |
| `playSound` | `bool?` | `true` | Jouer ou non un son |
| `enableVibration` | `bool?` | `true` | Activer ou non la vibration |
| `vibrationPattern` | `List<int>?` | - | Motif de vibration personnalise (ms) |
| `groupKey` | `String?` | - | Cle de groupe pour le regroupement des notifications |
| `setAsGroupSummary` | `bool?` | `false` | Si c'est le resume du groupe |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Comportement d'alerte pour les groupes |
| `autoCancel` | `bool?` | `true` | Supprimer automatiquement au toucher |
| `ongoing` | `bool?` | `false` | Ne peut pas etre supprime par l'utilisateur |
| `silent` | `bool?` | `false` | Notification silencieuse |
| `color` | `Color?` | - | Couleur d'accentuation |
| `largeIcon` | `String?` | - | Chemin de la ressource de la grande icone |
| `onlyAlertOnce` | `bool?` | `false` | Alerter uniquement au premier affichage |
| `showWhen` | `bool?` | `true` | Afficher l'horodatage |
| `when` | `int?` | - | Horodatage personnalise (ms depuis epoch) |
| `usesChronometer` | `bool?` | `false` | Utiliser l'affichage chronometre |
| `chronometerCountDown` | `bool?` | `false` | Le chronometre decompte |
| `channelShowBadge` | `bool?` | `true` | Afficher le badge sur le canal |
| `showProgress` | `bool?` | `false` | Afficher l'indicateur de progression |
| `maxProgress` | `int?` | `0` | Valeur maximale de progression |
| `progress` | `int?` | `0` | Valeur actuelle de progression |
| `indeterminate` | `bool?` | `false` | Progression indeterminee |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Action du canal |
| `enableLights` | `bool?` | `false` | Activer la LED de notification |
| `ledColor` | `Color?` | - | Couleur de la LED |
| `ledOnMs` | `int?` | - | Duree d'allumage de la LED (ms) |
| `ledOffMs` | `int?` | - | Duree d'extinction de la LED (ms) |
| `visibility` | `NotificationVisibility?` | - | Visibilite sur l'ecran de verrouillage |
| `timeoutAfter` | `int?` | - | Delai de suppression automatique (ms) |
| `fullScreenIntent` | `bool?` | `false` | Lancer en intention plein ecran |
| `shortcutId` | `String?` | - | ID du raccourci |
| `additionalFlags` | `List<int>?` | - | Drapeaux supplementaires |
| `tag` | `String?` | - | Tag de la notification |
| `actions` | `List<AndroidNotificationAction>?` | - | Boutons d'action |
| `colorized` | `bool?` | `false` | Activer la colorisation |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Utilisation des attributs audio |

<div id="ios-configuration"></div>

### Configuration iOS

Passez un `IOSNotificationConfig` a `setIOSConfig()` :

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

#### Proprietes de IOSNotificationConfig

| Propriete | Type | Par defaut | Description |
|-----------|------|------------|-------------|
| `presentList` | `bool?` | `true` | Presenter dans la liste des notifications |
| `presentAlert` | `bool?` | `true` | Presenter une alerte |
| `presentBadge` | `bool?` | `true` | Mettre a jour le badge de l'application |
| `presentSound` | `bool?` | `true` | Jouer un son |
| `presentBanner` | `bool?` | `true` | Presenter une banniere |
| `sound` | `String?` | - | Nom du fichier sonore |
| `badgeNumber` | `int?` | - | Numero de badge |
| `threadIdentifier` | `String?` | - | Identifiant de thread pour le regroupement |
| `categoryIdentifier` | `String?` | - | Identifiant de categorie pour les actions |
| `interruptionLevel` | `InterruptionLevel?` | - | Niveau d'interruption |

### Pieces jointes (iOS uniquement)

Ajoutez des pieces jointes image, audio ou video aux notifications iOS. Les pieces jointes sont telechargees depuis une URL :

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

## Gestion des notifications

### Annuler une notification specifique

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Annuler toutes les notifications

``` dart
await LocalNotification.cancelAllNotifications();
```

### Effacer le compteur de badges

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Permissions

Demandez les permissions de notification a l'utilisateur :

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

Les permissions sont automatiquement demandees lors du premier envoi de notification via `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Configuration de la plateforme

### Configuration iOS

Ajoutez a votre `Info.plist` :

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Configuration Android

Ajoutez a votre `AndroidManifest.xml` :

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Reference API

### Methodes statiques

| Methode | Signature | Description |
|---------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Envoyer une notification avec toutes les options |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Annuler une notification specifique |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Annuler toutes les notifications |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Demander les permissions de notification |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Effacer le compteur de badges iOS |

### Methodes d'instance (chainables)

| Methode | Parametres | Retour | Description |
|---------|------------|--------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | Definir les donnees utiles |
| `addId` | `int id` | `LocalNotification` | Definir l'ID de notification |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Definir le sous-titre |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Definir le numero de badge |
| `addSound` | `String sound` | `LocalNotification` | Definir un son personnalise |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Ajouter une piece jointe (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Definir la config Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Definir la config iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Envoyer la notification |

