# Notifikasi Lokal

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Notifikasi Terjadwal](#scheduled-notifications "Notifikasi Terjadwal")
- [Pola Builder](#builder-pattern "Pola Builder")
- [Konfigurasi Platform](#platform-configuration "Konfigurasi Platform")
  - [Konfigurasi Android](#android-configuration "Konfigurasi Android")
  - [Konfigurasi iOS](#ios-configuration "Konfigurasi iOS")
- [Mengelola Notifikasi](#managing-notifications "Mengelola Notifikasi")
- [Izin](#permissions "Izin")
- [Pengaturan Platform](#platform-setup "Pengaturan Platform")
- [Referensi API](#api-reference "Referensi API")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} menyediakan sistem notifikasi lokal melalui class `LocalNotification`. Ini memungkinkan Anda mengirim notifikasi segera atau terjadwal dengan konten kaya di iOS dan Android.

> Notifikasi lokal tidak didukung di web. Mencoba menggunakannya di web akan melempar `NotificationException`.

<div id="basic-usage"></div>

## Penggunaan Dasar

Kirim notifikasi menggunakan pola builder atau metode statis:

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

## Notifikasi Terjadwal

Jadwalkan notifikasi untuk dikirim pada waktu tertentu:

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

## Pola Builder

Class `LocalNotification` menyediakan API builder yang lancar. Semua metode berantai mengembalikan instance `LocalNotification`:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### Metode Berantai

| Metode | Parameter | Deskripsi |
|--------|------------|-------------|
| `addPayload` | `String payload` | Mengatur string data kustom untuk notifikasi |
| `addId` | `int id` | Mengatur identifier unik untuk notifikasi |
| `addSubtitle` | `String subtitle` | Mengatur teks subtitle |
| `addBadgeNumber` | `int badgeNumber` | Mengatur nomor badge ikon aplikasi |
| `addSound` | `String sound` | Mengatur nama file suara kustom |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | Menambahkan lampiran (khusus iOS, mengunduh dari URL) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Mengatur konfigurasi khusus Android |
| `setIOSConfig` | `IOSNotificationConfig config` | Mengatur konfigurasi khusus iOS |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

Jika `at` diberikan, jadwalkan notifikasi untuk waktu tersebut. Jika tidak, tampilkan segera.

<div id="platform-configuration"></div>

## Konfigurasi Platform

Opsi khusus platform dikonfigurasi melalui objek `AndroidNotificationConfig` dan `IOSNotificationConfig`.

<div id="android-configuration"></div>

### Konfigurasi Android

Berikan `AndroidNotificationConfig` ke `setAndroidConfig()`:

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

#### Properti AndroidNotificationConfig

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | ID channel notifikasi |
| `channelName` | `String?` | `'Default Channel'` | Nama channel notifikasi |
| `channelDescription` | `String?` | `'Default Channel'` | Deskripsi channel |
| `importance` | `Importance?` | `Importance.max` | Tingkat kepentingan notifikasi |
| `priority` | `Priority?` | `Priority.high` | Prioritas notifikasi |
| `ticker` | `String?` | `'ticker'` | Teks ticker untuk aksesibilitas |
| `icon` | `String?` | `'app_icon'` | Nama resource ikon kecil |
| `playSound` | `bool?` | `true` | Apakah akan memutar suara |
| `enableVibration` | `bool?` | `true` | Apakah akan mengaktifkan getaran |
| `vibrationPattern` | `List<int>?` | - | Pola getaran kustom (ms) |
| `groupKey` | `String?` | - | Kunci grup untuk pengelompokan notifikasi |
| `setAsGroupSummary` | `bool?` | `false` | Apakah ini adalah ringkasan grup |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | Perilaku alert untuk grup |
| `autoCancel` | `bool?` | `true` | Otomatis hilang saat diketuk |
| `ongoing` | `bool?` | `false` | Tidak dapat dihilangkan oleh pengguna |
| `silent` | `bool?` | `false` | Notifikasi diam |
| `color` | `Color?` | - | Warna aksen |
| `largeIcon` | `String?` | - | Path resource ikon besar |
| `onlyAlertOnce` | `bool?` | `false` | Hanya alert pada tampilan pertama |
| `showWhen` | `bool?` | `true` | Tampilkan timestamp |
| `when` | `int?` | - | Timestamp kustom (ms sejak epoch) |
| `usesChronometer` | `bool?` | `false` | Gunakan tampilan chronometer |
| `chronometerCountDown` | `bool?` | `false` | Chronometer menghitung mundur |
| `channelShowBadge` | `bool?` | `true` | Tampilkan badge pada channel |
| `showProgress` | `bool?` | `false` | Tampilkan indikator progress |
| `maxProgress` | `int?` | `0` | Nilai progress maksimum |
| `progress` | `int?` | `0` | Nilai progress saat ini |
| `indeterminate` | `bool?` | `false` | Progress tidak tentu |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | Aksi channel |
| `enableLights` | `bool?` | `false` | Aktifkan LED notifikasi |
| `ledColor` | `Color?` | - | Warna LED |
| `ledOnMs` | `int?` | - | Durasi LED menyala (ms) |
| `ledOffMs` | `int?` | - | Durasi LED mati (ms) |
| `visibility` | `NotificationVisibility?` | - | Visibilitas layar kunci |
| `timeoutAfter` | `int?` | - | Timeout otomatis hilang (ms) |
| `fullScreenIntent` | `bool?` | `false` | Luncurkan sebagai full screen intent |
| `shortcutId` | `String?` | - | ID shortcut |
| `additionalFlags` | `List<int>?` | - | Flag tambahan |
| `tag` | `String?` | - | Tag notifikasi |
| `actions` | `List<AndroidNotificationAction>?` | - | Tombol aksi |
| `colorized` | `bool?` | `false` | Aktifkan pewarnaan |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | Penggunaan atribut audio |

<div id="ios-configuration"></div>

### Konfigurasi iOS

Berikan `IOSNotificationConfig` ke `setIOSConfig()`:

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

#### Properti IOSNotificationConfig

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | Tampilkan di daftar notifikasi |
| `presentAlert` | `bool?` | `true` | Tampilkan alert |
| `presentBadge` | `bool?` | `true` | Perbarui badge aplikasi |
| `presentSound` | `bool?` | `true` | Putar suara |
| `presentBanner` | `bool?` | `true` | Tampilkan banner |
| `sound` | `String?` | - | Nama file suara |
| `badgeNumber` | `int?` | - | Nomor badge |
| `threadIdentifier` | `String?` | - | Identifier thread untuk pengelompokan |
| `categoryIdentifier` | `String?` | - | Identifier kategori untuk aksi |
| `interruptionLevel` | `InterruptionLevel?` | - | Tingkat interupsi |

### Lampiran (Khusus iOS)

Tambahkan lampiran gambar, audio, atau video ke notifikasi iOS. Lampiran diunduh dari URL:

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

## Mengelola Notifikasi

### Membatalkan Notifikasi Tertentu

``` dart
await LocalNotification.cancelNotification(42);

// With a tag (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### Membatalkan Semua Notifikasi

``` dart
await LocalNotification.cancelAllNotifications();
```

### Menghapus Jumlah Badge

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## Izin

Minta izin notifikasi dari pengguna:

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

Izin secara otomatis diminta pada pengiriman notifikasi pertama melalui `NyScheduler.taskOnce`.

<div id="platform-setup"></div>

## Pengaturan Platform

### Pengaturan iOS

Tambahkan ke `Info.plist` Anda:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Pengaturan Android

Tambahkan ke `AndroidManifest.xml` Anda:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## Referensi API

### Metode Statis

| Metode | Signature | Deskripsi |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | Kirim notifikasi dengan semua opsi |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | Batalkan notifikasi tertentu |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | Batalkan semua notifikasi |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | Minta izin notifikasi |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | Hapus jumlah badge iOS |

### Metode Instance (Berantai)

| Metode | Parameter | Return | Deskripsi |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | Atur data payload |
| `addId` | `int id` | `LocalNotification` | Atur ID notifikasi |
| `addSubtitle` | `String subtitle` | `LocalNotification` | Atur subtitle |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | Atur nomor badge |
| `addSound` | `String sound` | `LocalNotification` | Atur suara kustom |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | Tambah lampiran (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Atur konfigurasi Android |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | Atur konfigurasi iOS |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | Kirim notifikasi |

