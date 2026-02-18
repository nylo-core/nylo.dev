# Struktur Direktori

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Direktori Root](#root-directory "Direktori Root")
- [Direktori lib](#lib-directory "Direktori lib")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [Direktori Assets](#assets-directory "Direktori Assets")
- [Helper Aset](#asset-helpers "Helper Aset")


<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} menggunakan struktur direktori yang bersih dan terorganisir yang terinspirasi oleh <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Struktur ini membantu menjaga konsistensi di seluruh proyek dan memudahkan pencarian file.

<div id="root-directory"></div>

## Direktori Root

```
nylo_app/
├── android/          # Android platform files
├── assets/           # Images, fonts, and other assets
├── ios/              # iOS platform files
├── lang/             # Language/translation JSON files
├── lib/              # Dart application code
├── test/             # Test files
├── .env              # Environment variables
├── pubspec.yaml      # Dependencies and project config
└── ...
```

<div id="lib-directory"></div>

## Direktori lib

Folder `lib/` berisi semua kode aplikasi Dart Anda:

```
lib/
├── app/              # Application logic
├── bootstrap/        # Boot configuration
├── config/           # Configuration files
├── resources/        # UI components
├── routes/           # Route definitions
└── main.dart         # Application entry point
```

<div id="app-directory"></div>

### app/

Direktori `app/` berisi logika inti aplikasi Anda:

| Direktori | Tujuan |
|-----------|--------|
| `commands/` | Perintah Metro CLI kustom |
| `controllers/` | Controller halaman untuk logika bisnis |
| `events/` | Kelas event untuk sistem event |
| `forms/` | Kelas form dengan validasi |
| `models/` | Kelas model data |
| `networking/` | Layanan API dan konfigurasi jaringan |
| `networking/dio/interceptors/` | Interceptor HTTP Dio |
| `providers/` | Provider layanan yang di-boot saat aplikasi dimulai |
| `services/` | Kelas layanan umum |

<div id="bootstrap-directory"></div>

### bootstrap/

Direktori `bootstrap/` berisi file yang mengkonfigurasi cara aplikasi Anda boot:

| File | Tujuan |
|------|--------|
| `boot.dart` | Konfigurasi urutan boot utama |
| `decoders.dart` | Registrasi decoder model dan API |
| `env.g.dart` | Konfigurasi environment terenkripsi yang dihasilkan |
| `events.dart` | Registrasi event |
| `extensions.dart` | Extension kustom |
| `helpers.dart` | Fungsi helper kustom |
| `providers.dart` | Registrasi provider |
| `theme.dart` | Konfigurasi tema |

<div id="config-directory"></div>

### config/

Direktori `config/` berisi konfigurasi aplikasi:

| File | Tujuan |
|------|--------|
| `app.dart` | Pengaturan aplikasi inti |
| `design.dart` | Desain aplikasi (font, logo, loader) |
| `localization.dart` | Pengaturan bahasa dan locale |
| `storage_keys.dart` | Definisi kunci penyimpanan lokal |
| `toast_notification.dart` | Gaya notifikasi toast |

<div id="resources-directory"></div>

### resources/

Direktori `resources/` berisi komponen UI:

| Direktori | Tujuan |
|-----------|--------|
| `pages/` | Widget halaman (layar) |
| `themes/` | Definisi tema |
| `themes/light/` | Warna tema terang |
| `themes/dark/` | Warna tema gelap |
| `widgets/` | Komponen widget yang dapat digunakan kembali |
| `widgets/buttons/` | Widget tombol kustom |
| `widgets/bottom_sheet_modals/` | Widget modal bottom sheet |

<div id="routes-directory"></div>

### routes/

Direktori `routes/` berisi konfigurasi routing:

| File/Direktori | Tujuan |
|----------------|--------|
| `router.dart` | Definisi rute |
| `guards/` | Kelas route guard |

<div id="assets-directory"></div>

## Direktori Assets

Direktori `assets/` menyimpan file statis:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Mendaftarkan Assets

Assets didaftarkan di `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helper Aset

{{ config('app.name') }} menyediakan helper untuk bekerja dengan aset.

### Aset Gambar

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Aset Umum

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### File Bahasa

File bahasa disimpan di `lang/` di root proyek:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Lihat [Lokalisasi](/docs/7.x/localization) untuk detail lebih lanjut.
