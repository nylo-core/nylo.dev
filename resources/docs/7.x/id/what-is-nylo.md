# Apa itu {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Pengembangan Aplikasi
    - [Baru mengenal Flutter?](#new-to-flutter "Baru mengenal Flutter?")
    - [Jadwal Pemeliharaan dan Rilis](#maintenance-and-release-schedule "Jadwal Pemeliharaan dan Rilis")
- Kredit
    - [Dependensi Framework](#framework-dependencies "Dependensi Framework")
    - [Kontributor](#contributors "Kontributor")


<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} adalah micro-framework untuk Flutter yang dirancang untuk membantu menyederhanakan pengembangan aplikasi. Framework ini menyediakan boilerplate terstruktur dengan konfigurasi esensial yang sudah disiapkan sehingga Anda dapat fokus pada membangun fitur aplikasi Anda alih-alih menyiapkan infrastruktur.

Secara langsung, {{ config('app.name') }} menyertakan:

- **Routing** - Manajemen route yang sederhana dan deklaratif dengan guard dan deep linking
- **Networking** - API service dengan Dio, interceptor, dan response morphing
- **State Management** - State reaktif dengan NyState dan pembaruan state global
- **Localization** - Dukungan multi-bahasa dengan file terjemahan JSON
- **Themes** - Mode terang/gelap dengan perpindahan tema
- **Local Storage** - Penyimpanan aman dengan Backpack dan NyStorage
- **Forms** - Penanganan form dengan validasi dan tipe field
- **Push Notifications** - Dukungan notifikasi lokal dan remote
- **CLI Tool (Metro)** - Generate halaman, controller, model, dan lainnya

<div id="new-to-flutter"></div>

## Baru mengenal Flutter?

Jika Anda baru mengenal Flutter, mulailah dengan sumber daya resmi:

- <a href="https://flutter.dev" target="_BLANK">Dokumentasi Flutter</a> - Panduan komprehensif dan referensi API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Channel YouTube Flutter</a> - Tutorial dan pembaruan
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Resep praktis untuk tugas umum

Setelah Anda nyaman dengan dasar-dasar Flutter, {{ config('app.name') }} akan terasa intuitif karena dibangun di atas pola Flutter standar.


<div id="maintenance-and-release-schedule"></div>

## Jadwal Pemeliharaan dan Rilis

{{ config('app.name') }} mengikuti <a href="https://semver.org" target="_BLANK">Semantic Versioning</a>:

- **Rilis major** (7.x → 8.x) - Sekali per tahun untuk perubahan yang tidak kompatibel
- **Rilis minor** (7.0 → 7.1) - Fitur baru, kompatibel ke belakang
- **Rilis patch** (7.0.0 → 7.0.1) - Perbaikan bug dan peningkatan kecil

Perbaikan bug dan patch keamanan ditangani dengan cepat melalui repositori GitHub.


<div id="framework-dependencies"></div>

## Dependensi Framework

{{ config('app.name') }} v7 dibangun di atas package open source berikut:

### Dependensi Utama

| Package | Tujuan |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | HTTP client untuk permintaan API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Penyimpanan lokal yang aman |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internasionalisasi dan pemformatan |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Ekstensi reaktif untuk stream |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Persamaan nilai untuk objek |

### UI & Widget

| Package | Tujuan |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Efek skeleton loading |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Notifikasi toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Fungsionalitas pull-to-refresh |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Layout grid staggered |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Field pemilih tanggal |

### Notifikasi & Konektivitas

| Package | Tujuan |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Notifikasi push lokal |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Status konektivitas jaringan |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Badge ikon aplikasi |

### Utilitas

| Package | Tujuan |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Membuka URL dan aplikasi |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Konversi case string |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Generasi UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Path file system |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Masking input |


<div id="contributors"></div>

## Kontributor

Terima kasih kepada semua yang telah berkontribusi pada {{ config('app.name') }}! Jika Anda telah berkontribusi, hubungi melalui <a href="mailto:support@nylo.dev">support@nylo.dev</a> untuk ditambahkan di sini.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Pembuat)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
