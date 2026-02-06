# Yerel Bildirimler

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Zamanlanmış Bildirimler](#scheduled-notifications "Zamanlanmış Bildirimler")
- [Builder Deseni](#builder-pattern "Builder Deseni")
- [Platform Yapılandırması](#platform-configuration "Platform Yapılandırması")
  - [Android Yapılandırması](#android-configuration "Android Yapılandırması")
  - [iOS Yapılandırması](#ios-configuration "iOS Yapılandırması")
- [Bildirimleri Yönetme](#managing-notifications "Bildirimleri Yönetme")
- [İzinler](#permissions "İzinler")
- [Platform Kurulumu](#platform-setup "Platform Kurulumu")
- [API Referansı](#api-reference "API Referansı")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }}, `LocalNotification` sınıfı aracılığıyla yerel bir bildirim sistemi sunar. Bu, iOS ve Android üzerinde zengin içerikli anlık veya zamanlanmış bildirimler göndermenize olanak tanır.

> Yerel bildirimler web'de desteklenmez. Web'de kullanmaya çalışmak bir `NotificationException` fırlatır.

<div id="basic-usage"></div>

## Temel Kullanım

Builder deseni veya statik metodu kullanarak bir bildirim gönderin:

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

## Zamanlanmış Bildirimler

Belirli bir zamanda teslim edilecek bir bildirim zamanlayın:

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

## Builder Deseni

`LocalNotification` sınıfı akıcı bir builder API sunar. Tüm zincirleme metotlar `LocalNotification` örneğini döndürür:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Zincirleme Metotlar

| Metot | Parametreler | Açıklama |
|-------|-------------|----------|
| `addPayload` | `String payload` | Bildirim için özel veri dizesi ayarlar |
| `addId` | `int id` | Bildirim için benzersiz bir tanımlayıcı ayarlar |
| `addSubtitle` | `String subtitle` | Alt başlık metnini ayarlar |
| `addBadgeNumber` | `int badgeNumber` | Uygulama simgesi rozet numarasını ayarlar |
| `addSound` | `String sound` | Özel bir ses dosyası adı ayarlar |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Ek ekler (yalnızca iOS, URL'den indirir) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Android'e özgü yapılandırmayı ayarlar |
| `setIOSConfig` | `IOSNotificationConfig config` | iOS'a özgü yapılandırmayı ayarlar |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

`at` sağlanırsa, bildirimi o zaman için zamanlar. Aksi takdirde hemen gösterir.

<div id="platform-configuration"></div>

## Platform Yapılandırması

Platforma özgü seçenekler `AndroidNotificationConfig` ve `IOSNotificationConfig` nesneleri aracılığıyla yapılandırılır.

<div id="android-configuration"></div>

### Android Yapılandırması

`setAndroidConfig()` metoduna bir `AndroidNotificationConfig` geçirin:

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

#### AndroidNotificationConfig Özellikleri

| Özellik | Tür | Varsayılan | Açıklama |
|---------|-----|-----------|----------|
| `channelId` | `String?` | `'default_channel'` | Bildirim kanalı ID'si |
| `channelName` | `String?` | `'Default Channel'` | Bildirim kanalı adı |
| `channelDescription` | `String?` | `'Default Channel'` | Kanal açıklaması |
| `importance` | `Importance?` | `Importance.max` | Bildirim önem seviyesi |
| `priority` | `Priority?` | `Priority.high` | Bildirim önceliği |
| `ticker` | `String?` | `'ticker'` | Erişilebilirlik için ticker metni |
| `icon` | `String?` | `'app_icon'` | Küçük simge kaynak adı |
| `playSound` | `bool?` | `true` | Ses çalınıp çalınmayacağı |
| `enableVibration` | `bool?` | `true` | Titreşimin etkinleştirilip etkinleştirilmeyeceği |
| `vibrationPattern` | `List<int>?` | - | Özel titreşim deseni (ms) |
| `groupKey` | `String?` | - | Bildirim gruplama için grup anahtarı |
| `setAsGroupSummary` | `bool?` | `false` | Bunun grup özeti olup olmadığı |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Gruplar için uyarı davranışı |
| `autoCancel` | `bool?` | `true` | Dokunulduğunda otomatik kapatma |
| `ongoing` | `bool?` | `false` | Kullanıcı tarafından kapatılamaz |
| `silent` | `bool?` | `false` | Sessiz bildirim |
| `color` | `Color?` | - | Vurgu rengi |
| `largeIcon` | `String?` | - | Büyük simge kaynak yolu |
| `onlyAlertOnce` | `bool?` | `false` | Yalnızca ilk gösterimde uyar |
| `showWhen` | `bool?` | `true` | Zaman damgasını göster |
| `when` | `int?` | - | Özel zaman damgası (epoch'tan ms) |
| `usesChronometer` | `bool?` | `false` | Kronometre gösterimi kullan |
| `chronometerCountDown` | `bool?` | `false` | Kronometre geri sayım yapar |
| `channelShowBadge` | `bool?` | `true` | Kanalda rozet göster |
| `showProgress` | `bool?` | `false` | İlerleme göstergesini göster |
| `maxProgress` | `int?` | `0` | Maksimum ilerleme değeri |
| `progress` | `int?` | `0` | Mevcut ilerleme değeri |
| `indeterminate` | `bool?` | `false` | Belirsiz ilerleme |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Kanal eylemi |
| `enableLights` | `bool?` | `false` | Bildirim LED'ini etkinleştir |
| `ledColor` | `Color?` | - | LED rengi |
| `ledOnMs` | `int?` | - | LED açık süresi (ms) |
| `ledOffMs` | `int?` | - | LED kapalı süresi (ms) |
| `visibility` | `NotificationVisibility?` | - | Kilit ekranı görünürlüğü |
| `timeoutAfter` | `int?` | - | Otomatik kapatma zaman aşımı (ms) |
| `fullScreenIntent` | `bool?` | `false` | Tam ekran intent olarak başlat |
| `shortcutId` | `String?` | - | Kısayol ID'si |
| `additionalFlags` | `List<int>?` | - | Ek bayraklar |
| `tag` | `String?` | - | Bildirim etiketi |
| `actions` | `List<AndroidNotificationAction>?` | - | Eylem düğmeleri |
| `colorized` | `bool?` | `false` | Renklendirmeyi etkinleştir |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Ses öznitelikleri kullanımı |

<div id="ios-configuration"></div>

### iOS Yapılandırması

`setIOSConfig()` metoduna bir `IOSNotificationConfig` geçirin:

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

#### IOSNotificationConfig Özellikleri

| Özellik | Tür | Varsayılan | Açıklama |
|---------|-----|-----------|----------|
| `presentList` | `bool?` | `true` | Bildirim listesinde göster |
| `presentAlert` | `bool?` | `true` | Uyarı göster |
| `presentBadge` | `bool?` | `true` | Uygulama rozetini güncelle |
| `presentSound` | `bool?` | `true` | Ses çal |
| `presentBanner` | `bool?` | `true` | Banner göster |
| `sound` | `String?` | - | Ses dosyası adı |
| `badgeNumber` | `int?` | - | Rozet numarası |
| `threadIdentifier` | `String?` | - | Gruplama için iş parçacığı tanımlayıcısı |
| `categoryIdentifier` | `String?` | - | Eylemler için kategori tanımlayıcısı |
| `interruptionLevel` | `InterruptionLevel?` | - | Kesinti seviyesi |

### Ekler (Yalnızca iOS)

iOS bildirimlerine resim, ses veya video ekleri ekleyin. Ekler bir URL'den indirilir:

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

## Bildirimleri Yönetme

### Belirli Bir Bildirimi İptal Etme

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Tüm Bildirimleri İptal Etme

``` dart
await LocalNotification.cancelAllNotifications();
```

### Rozet Sayısını Temizleme

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## İzinler

Kullanıcıdan bildirim izinleri isteyin:

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

İzinler, ilk bildirim gönderiminde `NyScheduler.taskOnce` aracılığıyla otomatik olarak istenir.

<div id="platform-setup"></div>

## Platform Kurulumu

### iOS Kurulumu

`Info.plist` dosyanıza ekleyin:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android Kurulumu

`AndroidManifest.xml` dosyanıza ekleyin:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API Referansı

### Statik Metotlar

| Metot | İmza | Açıklama |
|-------|------|----------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Tüm seçeneklerle bildirim gönder |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Belirli bir bildirimi iptal et |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Tüm bildirimleri iptal et |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Bildirim izinlerini iste |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | iOS rozet sayısını temizle |

### Örnek Metotlar (Zincirleme)

| Metot | Parametreler | Döndürür | Açıklama |
|-------|-------------|---------|----------|
| `addPayload` | `String payload` | `LocalNotification` | Yük verisi ayarla |
| `addId` | `int id` | `LocalNotification` | Bildirim ID'si ayarla |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Alt başlık ayarla |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Rozet numarası ayarla |
| `addSound` | `String sound` | `LocalNotification` | Özel ses ayarla |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Ek ekle (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Android yapılandırması ayarla |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | iOS yapılandırması ayarla |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Bildirimi gönder |

