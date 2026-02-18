# Instalasi

---

<a name="section-1"></a>
- [Instalasi](#install "Instalasi")
- [Menjalankan Proyek](#running-the-project "Menjalankan Proyek")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Instalasi

### 1. Instal nylo_installer secara global

``` bash
dart pub global activate nylo_installer
```

Ini menginstal alat CLI {{ config('app.name') }} secara global di sistem Anda.

### 2. Buat proyek baru

``` bash
nylo new my_app
```

Perintah ini meng-clone template {{ config('app.name') }}, mengkonfigurasi proyek dengan nama aplikasi Anda, dan menginstal semua dependensi secara otomatis.

### 3. Siapkan alias Metro CLI

``` bash
cd my_app
nylo init
```

Ini mengkonfigurasi perintah `metro` untuk proyek Anda, memungkinkan Anda menggunakan perintah Metro CLI tanpa sintaks `dart run` lengkap.

Setelah instalasi, Anda akan memiliki struktur proyek Flutter lengkap dengan:
- Routing dan navigasi yang sudah dikonfigurasi
- Boilerplate layanan API
- Pengaturan tema dan lokalisasi
- Metro CLI untuk pembuatan kode


<div id="running-the-project"></div>

## Menjalankan Proyek

Proyek {{ config('app.name') }} berjalan seperti aplikasi Flutter standar pada umumnya.

### Menggunakan Terminal

``` bash
flutter run
```

### Menggunakan IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Running and debugging</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Run app without breakpoints</a>

Jika build berhasil, aplikasi akan menampilkan layar landing default {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} menyertakan alat CLI bernama **Metro** untuk menghasilkan file proyek.

### Menjalankan Metro

``` bash
metro
```

Ini menampilkan menu Metro:

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referensi Perintah Metro

| Perintah | Deskripsi |
|----------|-----------|
| `make:page` | Buat halaman baru |
| `make:stateful_widget` | Buat widget stateful |
| `make:stateless_widget` | Buat widget stateless |
| `make:state_managed_widget` | Buat widget yang dikelola state |
| `make:navigation_hub` | Buat navigation hub (navigasi bawah) |
| `make:journey_widget` | Buat widget journey untuk navigation hub |
| `make:bottom_sheet_modal` | Buat modal bottom sheet |
| `make:button` | Buat widget tombol kustom |
| `make:form` | Buat form dengan validasi |
| `make:model` | Buat kelas model |
| `make:provider` | Buat provider |
| `make:api_service` | Buat layanan API |
| `make:controller` | Buat controller |
| `make:event` | Buat event |
| `make:theme` | Buat tema |
| `make:route_guard` | Buat route guard |
| `make:config` | Buat file konfigurasi |
| `make:interceptor` | Buat interceptor jaringan |
| `make:command` | Buat perintah Metro kustom |
| `make:env` | Generate konfigurasi environment dari .env |

### Contoh Penggunaan

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
