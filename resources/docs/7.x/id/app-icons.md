# Ikon Aplikasi

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Membuat Ikon Aplikasi](#generating-app-icons "Membuat ikon aplikasi")
- [Menambahkan Ikon Aplikasi Anda](#adding-your-app-icon "Menambahkan ikon aplikasi Anda")
- [Persyaratan Ikon Aplikasi](#app-icon-requirements "Persyaratan ikon aplikasi")
- [Konfigurasi](#configuration "Konfigurasi")
- [Jumlah Badge](#badge-count "Jumlah badge")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menggunakan <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> untuk membuat ikon aplikasi untuk iOS dan Android dari satu gambar sumber.

Ikon aplikasi Anda harus ditempatkan di direktori `assets/app_icon/` dengan ukuran **1024x1024 piksel**.

<div id="generating-app-icons"></div>

## Membuat Ikon Aplikasi

Jalankan perintah berikut untuk membuat ikon untuk semua platform:

``` bash
dart run flutter_launcher_icons
```

Perintah ini membaca ikon sumber Anda dari `assets/app_icon/` dan menghasilkan:
- Ikon iOS di `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Ikon Android di `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Menambahkan Ikon Aplikasi Anda

1. Buat ikon Anda sebagai file **PNG 1024x1024**
2. Letakkan di `assets/app_icon/` (contoh: `assets/app_icon/icon.png`)
3. Perbarui `image_path` di `pubspec.yaml` Anda jika diperlukan:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Jalankan perintah pembuatan ikon

<div id="app-icon-requirements"></div>

## Persyaratan Ikon Aplikasi

| Atribut | Nilai |
|---------|-------|
| Format | PNG |
| Ukuran | 1024x1024 piksel |
| Layer | Diratakan tanpa transparansi |

### Penamaan File

Gunakan nama file yang sederhana tanpa karakter khusus:
- `app_icon.png`
- `icon.png`

### Panduan Platform

Untuk persyaratan detail, lihat panduan platform resmi:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Konfigurasi

Sesuaikan pembuatan ikon di `pubspec.yaml` Anda:

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

Lihat <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">dokumentasi flutter_launcher_icons</a> untuk semua opsi yang tersedia.

<div id="badge-count"></div>

## Jumlah Badge

{{ config('app.name') }} menyediakan fungsi helper untuk mengelola jumlah badge aplikasi (angka yang ditampilkan pada ikon aplikasi):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Dukungan Platform

Jumlah badge didukung pada:
- **iOS**: Dukungan native
- **Android**: Memerlukan dukungan launcher (sebagian besar launcher mendukung ini)
- **Web**: Tidak didukung

### Kasus Penggunaan

Skenario umum untuk jumlah badge:
- Notifikasi yang belum dibaca
- Pesan tertunda
- Item di keranjang
- Tugas yang belum selesai

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

