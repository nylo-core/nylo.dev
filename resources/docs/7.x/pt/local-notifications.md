# Local Notifications

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Uso Basico](#basic-usage "Uso Basico")
- [Notificacoes Agendadas](#scheduled-notifications "Notificacoes Agendadas")
- [Padrao Builder](#builder-pattern "Padrao Builder")
- [Configuracao de Plataforma](#platform-configuration "Configuracao de Plataforma")
  - [Configuracao Android](#android-configuration "Configuracao Android")
  - [Configuracao iOS](#ios-configuration "Configuracao iOS")
- [Gerenciando Notificacoes](#managing-notifications "Gerenciando Notificacoes")
- [Permissoes](#permissions "Permissoes")
- [Configuracao de Plataforma](#platform-setup "Configuracao de Plataforma")
- [Referencia da API](#api-reference "Referencia da API")

<div id="introduction"></div>

## Introducao

{{ config('app.name') }} fornece um sistema de notificacoes locais atraves da classe `LocalNotification`. Isso permite que voce envie notificacoes imediatas ou agendadas com conteudo rico no iOS e Android.

> Notificacoes locais nao sao suportadas na web. Tentar usa-las na web lancara uma `NotificationException`.

<div id="basic-usage"></div>

## Uso Basico

Envie uma notificacao usando o padrao builder ou o metodo estatico:

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

## Notificacoes Agendadas

Agende uma notificacao para ser entregue em um horario especifico:

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

## Padrao Builder

A classe `LocalNotification` fornece uma API builder fluente. Todos os metodos encadeaveis retornam a instancia `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Metodos Encadeaveis

| Metodo | Parametros | Descricao |
|--------|------------|-----------|
| `addPayload` | `String payload` | Define uma string de dados personalizados para a notificacao |
| `addId` | `int id` | Define um identificador unico para a notificacao |
| `addSubtitle` | `String subtitle` | Define o texto do subtitulo |
| `addBadgeNumber` | `int badgeNumber` | Define o numero do badge no icone do app |
| `addSound` | `String sound` | Define um arquivo de som personalizado |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Adiciona um anexo (apenas iOS, baixa de uma URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Define configuracao especifica do Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Define configuracao especifica do iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Se `at` for fornecido, agenda a notificacao para aquele horario. Caso contrario, exibe imediatamente.

<div id="platform-configuration"></div>

## Configuracao de Plataforma

No v7, opcoes especificas de plataforma sao configuradas atraves dos objetos `AndroidNotificationConfig` e `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Configuracao Android

Passe um `AndroidNotificationConfig` para `setAndroidConfig()`:

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

#### Propriedades de AndroidNotificationConfig

| Propriedade | Tipo | Padrao | Descricao |
|-------------|------|--------|-----------|
| `channelId` | `String?` | `'default_channel'` | ID do canal de notificacao |
| `channelName` | `String?` | `'Default Channel'` | Nome do canal de notificacao |
| `channelDescription` | `String?` | `'Default Channel'` | Descricao do canal |
| `importance` | `Importance?` | `Importance.max` | Nivel de importancia da notificacao |
| `priority` | `Priority?` | `Priority.high` | Prioridade da notificacao |
| `ticker` | `String?` | `'ticker'` | Texto do ticker para acessibilidade |
| `icon` | `String?` | `'app_icon'` | Nome do recurso do icone pequeno |
| `playSound` | `bool?` | `true` | Se deve reproduzir um som |
| `enableVibration` | `bool?` | `true` | Se deve habilitar vibracao |
| `vibrationPattern` | `List<int>?` | - | Padrao de vibracao personalizado (ms) |
| `groupKey` | `String?` | - | Chave de grupo para agrupamento de notificacoes |
| `setAsGroupSummary` | `bool?` | `false` | Se esta e o resumo do grupo |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Comportamento de alerta para grupos |
| `autoCancel` | `bool?` | `true` | Dispensar automaticamente ao tocar |
| `ongoing` | `bool?` | `false` | Nao pode ser dispensada pelo usuario |
| `silent` | `bool?` | `false` | Notificacao silenciosa |
| `color` | `Color?` | - | Cor de destaque |
| `largeIcon` | `String?` | - | Caminho do recurso do icone grande |
| `onlyAlertOnce` | `bool?` | `false` | Alertar apenas na primeira exibicao |
| `showWhen` | `bool?` | `true` | Mostrar timestamp |
| `when` | `int?` | - | Timestamp personalizado (ms desde epoch) |
| `usesChronometer` | `bool?` | `false` | Usar exibicao de cronometro |
| `chronometerCountDown` | `bool?` | `false` | Cronometro em contagem regressiva |
| `channelShowBadge` | `bool?` | `true` | Mostrar badge no canal |
| `showProgress` | `bool?` | `false` | Mostrar indicador de progresso |
| `maxProgress` | `int?` | `0` | Valor maximo de progresso |
| `progress` | `int?` | `0` | Valor atual de progresso |
| `indeterminate` | `bool?` | `false` | Progresso indeterminado |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Acao do canal |
| `enableLights` | `bool?` | `false` | Habilitar LED de notificacao |
| `ledColor` | `Color?` | - | Cor do LED |
| `ledOnMs` | `int?` | - | Duracao do LED ligado (ms) |
| `ledOffMs` | `int?` | - | Duracao do LED desligado (ms) |
| `visibility` | `NotificationVisibility?` | - | Visibilidade na tela de bloqueio |
| `timeoutAfter` | `int?` | - | Timeout para dispensar automaticamente (ms) |
| `fullScreenIntent` | `bool?` | `false` | Lancar como intent de tela cheia |
| `shortcutId` | `String?` | - | ID do atalho |
| `additionalFlags` | `List<int>?` | - | Flags adicionais |
| `tag` | `String?` | - | Tag da notificacao |
| `actions` | `List<AndroidNotificationAction>?` | - | Botoes de acao |
| `colorized` | `bool?` | `false` | Habilitar colorizacao |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Uso de atributos de audio |

<div id="ios-configuration"></div>

### Configuracao iOS

Passe um `IOSNotificationConfig` para `setIOSConfig()`:

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

#### Propriedades de IOSNotificationConfig

| Propriedade | Tipo | Padrao | Descricao |
|-------------|------|--------|-----------|
| `presentList` | `bool?` | `true` | Apresentar na lista de notificacoes |
| `presentAlert` | `bool?` | `true` | Apresentar um alerta |
| `presentBadge` | `bool?` | `true` | Atualizar o badge do app |
| `presentSound` | `bool?` | `true` | Reproduzir som |
| `presentBanner` | `bool?` | `true` | Apresentar banner |
| `sound` | `String?` | - | Nome do arquivo de som |
| `badgeNumber` | `int?` | - | Numero do badge |
| `threadIdentifier` | `String?` | - | Identificador de thread para agrupamento |
| `categoryIdentifier` | `String?` | - | Identificador de categoria para acoes |
| `interruptionLevel` | `InterruptionLevel?` | - | Nivel de interrupcao |

### Anexos (apenas iOS)

Adicione anexos de imagem, audio ou video as notificacoes do iOS. Os anexos sao baixados de uma URL:

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

## Gerenciando Notificacoes

### Cancelar uma Notificacao Especifica

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Cancelar Todas as Notificacoes

``` dart
await LocalNotification.cancelAllNotifications();
```

### Limpar Contagem de Badge

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Permissoes

Solicite permissoes de notificacao ao usuario:

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

As permissoes sao solicitadas automaticamente no primeiro envio de notificacao via `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Configuracao de Plataforma

### Configuracao iOS

Adicione ao seu `Info.plist`:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Configuracao Android

Adicione ao seu `AndroidManifest.xml`:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Referencia da API

### Metodos Estaticos

| Metodo | Assinatura | Descricao |
|--------|------------|-----------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Enviar uma notificacao com todas as opcoes |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Cancelar uma notificacao especifica |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Cancelar todas as notificacoes |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Solicitar permissoes de notificacao |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Limpar a contagem de badge do iOS |

### Metodos de Instancia (Encadeaveis)

| Metodo | Parametros | Retorno | Descricao |
|--------|------------|---------|-----------|
| `addPayload` | `String payload` | `LocalNotification` | Definir dados de payload |
| `addId` | `int id` | `LocalNotification` | Definir ID da notificacao |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Definir subtitulo |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Definir numero do badge |
| `addSound` | `String sound` | `LocalNotification` | Definir som personalizado |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Adicionar anexo (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Definir config Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Definir config iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Enviar a notificacao |
