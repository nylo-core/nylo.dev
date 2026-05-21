# Deep Links

---

<a name="section-1"></a>

- [Pengantar](#introduction "Pengantar")
- [Mulai](#getting-started "Mulai")
  - [Aktifkan Deep Links](#enable-deep-links "Aktifkan Deep Links")
  - [Buat Provider](#generate-a-provider "Buat Provider")
- [Konfigurasi Platform](#platform-configuration "Konfigurasi Platform")
  - [Konfigurasi Android](#android-configuration "Konfigurasi Android")
  - [Konfigurasi iOS](#ios-configuration "Konfigurasi iOS")
- [Mencegat Link](#intercepting-links "Mencegat Link")
- [Rute Fallback](#fallback-route "Rute Fallback")
- [Pengujian](#testing "Pengujian")
- [Referensi API](#api-reference "Referensi API")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} hadir dengan dukungan deep link kelas pertama yang didukung oleh [`app_links`](https://pub.dev/packages/app_links). URI yang masuk dari Android App Links, iOS Universal Links, custom URL scheme, dan URL web ditangkap secara otomatis dan dirutekan melalui rute yang telah Anda daftarkan.

Satu baris mengaktifkan seluruh pipeline:

```dart
nylo.useDeepLinks();
```

Ketika URI tiba, {{ config('app.name') }} mengekstrak path dan query parameter lalu memanggil `routeTo()` dengan data tersebut. Peluncuran cold-start (aplikasi dibuka melalui tautan) diputar ulang setelah navigator siap, dan tautan warm-start (diterima saat aplikasi berjalan) ditangani dengan cara yang sama.

<div id="getting-started"></div>

## Mulai

<div id="enable-deep-links"></div>

### Aktifkan Deep Links

Di dalam method `setup` provider mana pun, panggil `useDeepLinks()`:

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

Daftarkan provider di `config/providers.dart` agar berjalan saat boot aplikasi.

<div id="generate-a-provider"></div>

### Buat Provider

Metro membuat scaffolding provider untuk Anda:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Ini membuat `lib/app/providers/deep_link_provider.dart` dengan `useDeepLinks()` yang sudah terhubung dan contoh `onIncomingLink` yang dikomentari. Provider juga ditambahkan ke `config/providers.dart` secara otomatis.

<div id="platform-configuration"></div>

## Konfigurasi Platform

<div id="android-configuration"></div>

### Konfigurasi Android

Tambahkan `<intent-filter>` ke `<activity>` utama Anda di `android/app/src/main/AndroidManifest.xml`. Contoh di bawah menerima Android App Links (`https://example.com/...`) dan custom scheme (`myapp://...`):

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

Untuk App Links yang diverifikasi, host file `assetlinks.json` di `https://example.com/.well-known/assetlinks.json`. Lihat <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">panduan Flutter App Links</a> untuk detail selengkapnya.

<div id="ios-configuration"></div>

### Konfigurasi iOS

Untuk **custom URL scheme**, tambahkan entri `CFBundleURLTypes` ke `ios/Runner/Info.plist`:

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

Untuk **Universal Links**, aktifkan kemampuan Associated Domains di Xcode dan host file `apple-app-site-association` di domain Anda. Lihat <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">panduan Flutter Universal Links</a> untuk panduan lengkapnya.

<div id="intercepting-links"></div>

## Mencegat Link

Gunakan `onIncomingLink` untuk menjalankan logika sebelum {{ config('app.name') }} merutekan URI. Kembalikan `true` untuk membiarkan {{ config('app.name') }} merutekan secara otomatis, atau `false` untuk menangani URI sendiri:

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

Callback menerima `Uri` lengkap, termasuk scheme, host, path, dan query parameter.

<div id="fallback-route"></div>

## Rute Fallback

Berikan `fallbackRoute` ke `useDeepLinks()` agar pengguna diarahkan ke tempat yang sesuai ketika path URI yang masuk tidak terdaftar:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Ketika path URI cocok dengan rute yang terdaftar, {{ config('app.name') }} akan merutekan ke sana. Jika tidak, pengguna akan diarahkan ke rute fallback.

<div id="testing"></div>

## Pengujian

Simulasikan deep link yang masuk dari command line:

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

Tutup aplikasi di antara pengujian untuk memverifikasi perilaku cold-start, dan picu URL saat aplikasi ada di foreground untuk memverifikasi warm-start.

<div id="api-reference"></div>

## Referensi API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Mengaktifkan penangkapan deep link platform. Panggil ini di dalam method `setup` sebuah provider.

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `fallbackRoute` | `String?` | Rute untuk dinavigasi ketika path URI yang masuk tidak terdaftar. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Mendaftarkan callback yang dipanggil untuk setiap URI yang masuk. Kembalikan `true` untuk membiarkan {{ config('app.name') }} merutekan secara otomatis, atau `false` untuk menekan perutean otomatis.

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `callback` | `Future<bool> Function(Uri uri)` | Menerima URI yang masuk. |
