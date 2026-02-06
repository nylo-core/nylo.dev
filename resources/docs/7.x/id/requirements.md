# Persyaratan

---

<a name="section-1"></a>
- [Persyaratan Sistem](#system-requirements "Persyaratan Sistem")
- [Menginstal Flutter](#installing-flutter "Menginstal Flutter")
- [Memverifikasi Instalasi Anda](#verifying-installation "Memverifikasi Instalasi Anda")
- [Menyiapkan Editor](#set-up-an-editor "Menyiapkan Editor")


<div id="system-requirements"></div>

## Persyaratan Sistem

{{ config('app.name') }} v7 memerlukan versi minimum berikut:

| Persyaratan | Versi Minimum |
|-------------|-----------------|
| **Flutter** | 3.24.0 atau lebih tinggi |
| **Dart SDK** | 3.10.7 atau lebih tinggi |

### Dukungan Platform

{{ config('app.name') }} mendukung semua platform yang didukung Flutter:

| Platform | Dukungan |
|----------|---------|
| iOS | ✓ Dukungan penuh |
| Android | ✓ Dukungan penuh |
| Web | ✓ Dukungan penuh |
| macOS | ✓ Dukungan penuh |
| Windows | ✓ Dukungan penuh |
| Linux | ✓ Dukungan penuh |

<div id="installing-flutter"></div>

## Menginstal Flutter

Jika Anda belum menginstal Flutter, ikuti panduan instalasi resmi untuk sistem operasi Anda:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Panduan Instalasi Flutter</a>

<div id="verifying-installation"></div>

## Memverifikasi Instalasi Anda

Setelah menginstal Flutter, verifikasi pengaturan Anda:

### Cek Versi Flutter

``` bash
flutter --version
```

Anda akan melihat output yang mirip dengan:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Perbarui Flutter (jika diperlukan)

Jika versi Flutter Anda di bawah 3.24.0, perbarui ke rilis stabil terbaru:

``` bash
flutter channel stable
flutter upgrade
```

### Jalankan Flutter Doctor

Verifikasi bahwa lingkungan pengembangan Anda dikonfigurasi dengan benar:

``` bash
flutter doctor -v
```

Perintah ini memeriksa:
- Instalasi Flutter SDK
- Android toolchain (untuk pengembangan Android)
- Xcode (untuk pengembangan iOS/macOS)
- Perangkat yang terhubung
- Plugin IDE

Perbaiki masalah yang dilaporkan sebelum melanjutkan instalasi {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Menyiapkan Editor

Pilih IDE dengan dukungan Flutter:

### Visual Studio Code (Direkomendasikan)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> ringan dan memiliki dukungan Flutter yang sangat baik.

1. Instal <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Instal <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">ekstensi Flutter</a>
3. Instal <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">ekstensi Dart</a>

Panduan pengaturan: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Pengaturan Flutter VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> menyediakan IDE lengkap dengan dukungan emulator bawaan.

1. Instal <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Instal plugin Flutter (Preferences → Plugins → Flutter)
3. Instal plugin Dart

Panduan pengaturan: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Pengaturan Flutter Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community atau Ultimate) juga mendukung pengembangan Flutter.

1. Instal IntelliJ IDEA
2. Instal plugin Flutter (Preferences → Plugins → Flutter)
3. Instal plugin Dart

Setelah editor Anda dikonfigurasi, Anda siap untuk [menginstal {{ config('app.name') }}](/docs/7.x/installation).
