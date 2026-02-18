# Local Notifications

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- [Uso básico](#basic-usage "Uso básico")
- [Notificaciones programadas](#scheduled-notifications "Notificaciones programadas")
- [Patrón builder](#builder-pattern "Patrón builder")
- [Configuración de plataforma](#platform-configuration "Configuración de plataforma")
  - [Configuración de Android](#android-configuration "Configuración de Android")
  - [Configuración de iOS](#ios-configuration "Configuración de iOS")
- [Gestión de notificaciones](#managing-notifications "Gestión de notificaciones")
- [Permisos](#permissions "Permisos")
- [Configuración de plataforma](#platform-setup "Configuración de plataforma")
- [Referencia de API](#api-reference "Referencia de API")

<div id="introduction"></div>

## Introducción

{{ config('app.name') }} proporciona un sistema de notificaciones locales a través de la clase `LocalNotification`. Esto te permite enviar notificaciones inmediatas o programadas con contenido enriquecido en iOS y Android.

> Las notificaciones locales no son compatibles con la web. Intentar usarlas en la web lanzará una `NotificationException`.

<div id="basic-usage"></div>

## Uso básico

Envía una notificación usando el patrón builder o el método estático:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Patrón builder
await LocalNotification(title: "Hello", body: "World").send();

// Usando la función helper
await localNotification("Hello", "World").send();

// Usando el método estático
await LocalNotification.sendNotification(
  title: "Hello",
  body: "World",
);
```

<div id="scheduled-notifications"></div>

## Notificaciones programadas

Programa una notificación para que se entregue en un momento específico:

``` dart
// Programar para mañana
final tomorrow = DateTime.now().add(Duration(days: 1));

await LocalNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
).send(at: tomorrow);

// Usando el método estático
await LocalNotification.sendNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
  at: tomorrow,
);
```

<div id="builder-pattern"></div>

## Patrón builder

La clase `LocalNotification` proporciona una API builder fluida. Todos los métodos encadenables devuelven la instancia de `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Métodos encadenables

| Método | Parámetros | Descripción |
|--------|------------|-------------|
| `addPayload` | `String payload` | Establece una cadena de datos personalizados para la notificación |
| `addId` | `int id` | Establece un identificador único para la notificación |
| `addSubtitle` | `String subtitle` | Establece el texto del subtítulo |
| `addBadgeNumber` | `int badgeNumber` | Establece el número de insignia del ícono de la aplicación |
| `addSound` | `String sound` | Establece un nombre de archivo de sonido personalizado |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Agrega un adjunto (solo iOS, descarga desde URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Establece la configuración específica de Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Establece la configuración específica de iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Si se proporciona `at`, programa la notificación para ese momento. De lo contrario, la muestra inmediatamente.

<div id="platform-configuration"></div>

## Configuración de plataforma

En v7, las opciones específicas de plataforma se configuran a través de los objetos `AndroidNotificationConfig` e `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Configuración de Android

Pasa un `AndroidNotificationConfig` a `setAndroidConfig()`:

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

#### Propiedades de AndroidNotificationConfig

| Propiedad | Tipo | Predeterminado | Descripción |
|-----------|------|----------------|-------------|
| `channelId` | `String?` | `'default_channel'` | ID del canal de notificación |
| `channelName` | `String?` | `'Default Channel'` | Nombre del canal de notificación |
| `channelDescription` | `String?` | `'Default Channel'` | Descripción del canal |
| `importance` | `Importance?` | `Importance.max` | Nivel de importancia de la notificación |
| `priority` | `Priority?` | `Priority.high` | Prioridad de la notificación |
| `ticker` | `String?` | `'ticker'` | Texto del ticker para accesibilidad |
| `icon` | `String?` | `'app_icon'` | Nombre del recurso del ícono pequeño |
| `playSound` | `bool?` | `true` | Si se reproduce un sonido |
| `enableVibration` | `bool?` | `true` | Si se habilita la vibración |
| `vibrationPattern` | `List<int>?` | - | Patrón de vibración personalizado (ms) |
| `groupKey` | `String?` | - | Clave de grupo para agrupación de notificaciones |
| `setAsGroupSummary` | `bool?` | `false` | Si esta es el resumen del grupo |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Comportamiento de alerta para grupos |
| `autoCancel` | `bool?` | `true` | Descarte automático al tocar |
| `ongoing` | `bool?` | `false` | No puede ser descartada por el usuario |
| `silent` | `bool?` | `false` | Notificación silenciosa |
| `color` | `Color?` | - | Color de acento |
| `largeIcon` | `String?` | - | Ruta del recurso del ícono grande |
| `onlyAlertOnce` | `bool?` | `false` | Solo alertar en la primera visualización |
| `showWhen` | `bool?` | `true` | Mostrar marca de tiempo |
| `when` | `int?` | - | Marca de tiempo personalizada (ms desde epoch) |
| `usesChronometer` | `bool?` | `false` | Usar visualización de cronómetro |
| `chronometerCountDown` | `bool?` | `false` | Cronómetro cuenta regresiva |
| `channelShowBadge` | `bool?` | `true` | Mostrar insignia en el canal |
| `showProgress` | `bool?` | `false` | Mostrar indicador de progreso |
| `maxProgress` | `int?` | `0` | Valor máximo de progreso |
| `progress` | `int?` | `0` | Valor actual de progreso |
| `indeterminate` | `bool?` | `false` | Progreso indeterminado |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Acción del canal |
| `enableLights` | `bool?` | `false` | Habilitar LED de notificación |
| `ledColor` | `Color?` | - | Color del LED |
| `ledOnMs` | `int?` | - | Duración del LED encendido (ms) |
| `ledOffMs` | `int?` | - | Duración del LED apagado (ms) |
| `visibility` | `NotificationVisibility?` | - | Visibilidad en pantalla de bloqueo |
| `timeoutAfter` | `int?` | - | Tiempo de descarte automático (ms) |
| `fullScreenIntent` | `bool?` | `false` | Lanzar como intent de pantalla completa |
| `shortcutId` | `String?` | - | ID del acceso directo |
| `additionalFlags` | `List<int>?` | - | Flags adicionales |
| `tag` | `String?` | - | Etiqueta de la notificación |
| `actions` | `List<AndroidNotificationAction>?` | - | Botones de acción |
| `colorized` | `bool?` | `false` | Habilitar colorización |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Uso de atributos de audio |

<div id="ios-configuration"></div>

### Configuración de iOS

Pasa un `IOSNotificationConfig` a `setIOSConfig()`:

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

#### Propiedades de IOSNotificationConfig

| Propiedad | Tipo | Predeterminado | Descripción |
|-----------|------|----------------|-------------|
| `presentList` | `bool?` | `true` | Presentar en la lista de notificaciones |
| `presentAlert` | `bool?` | `true` | Presentar una alerta |
| `presentBadge` | `bool?` | `true` | Actualizar insignia de la aplicación |
| `presentSound` | `bool?` | `true` | Reproducir sonido |
| `presentBanner` | `bool?` | `true` | Presentar banner |
| `sound` | `String?` | - | Nombre del archivo de sonido |
| `badgeNumber` | `int?` | - | Número de insignia |
| `threadIdentifier` | `String?` | - | Identificador de hilo para agrupación |
| `categoryIdentifier` | `String?` | - | Identificador de categoría para acciones |
| `interruptionLevel` | `InterruptionLevel?` | - | Nivel de interrupción |

### Adjuntos (solo iOS)

Agrega adjuntos de imagen, audio o video a las notificaciones de iOS. Los adjuntos se descargan desde una URL:

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

## Gestión de notificaciones

### Cancelar una notificación específica

``` dart
await LocalNotification.cancelNotification(42);

// Con una etiqueta (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Cancelar todas las notificaciones

``` dart
await LocalNotification.cancelAllNotifications();
```

### Limpiar contador de insignias

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Permisos

Solicita permisos de notificación al usuario:

``` dart
// Solicitar con valores predeterminados
await LocalNotification.requestPermissions();

// Solicitar con opciones específicas
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

Los permisos se solicitan automáticamente en el primer envío de notificación mediante `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Configuración de plataforma

### Configuración de iOS

Agrega a tu `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Configuración de Android

Agrega a tu `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Referencia de API

### Métodos estáticos

| Método | Firma | Descripción |
|--------|-------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Enviar una notificación con todas las opciones |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Cancelar una notificación específica |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Cancelar todas las notificaciones |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Solicitar permisos de notificación |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Limpiar el contador de insignias de iOS |

### Métodos de instancia (encadenables)

| Método | Parámetros | Devuelve | Descripción |
|--------|------------|----------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | Establecer datos de carga útil |
| `addId` | `int id` | `LocalNotification` | Establecer ID de notificación |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Establecer subtítulo |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Establecer número de insignia |
| `addSound` | `String sound` | `LocalNotification` | Establecer sonido personalizado |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Agregar adjunto (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Establecer configuración de Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Establecer configuración de iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Enviar la notificación |
