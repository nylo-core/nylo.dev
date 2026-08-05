# Instalasi

---

<a name="section-1"></a>
- [Instalasi](#install "Instalasi")
- [Menjalankan Proyek](#running-the-project "Menjalankan Proyek")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Tiga perintah membawa Anda dari folder kosong ke aplikasi Flutter yang berjalan, dengan routing, networking, tema, dan pembuatan kode yang sudah disiapkan.

<x-doc-strip label="Sebelum memulai" items="Flutter SDK terinstal, Dart 3" linkText="Persyaratan lengkap" linkHref="/id/docs/{{ $version }}/requirements" />

## Instalasi

Jalankan perintah berikut secara berurutan. Setiap perintah aman untuk dijalankan ulang.

<x-doc-steps>
<x-doc-step number="1" title="Instal Nylo CLI secara global">
Ini menginstal alat CLI {{ config('app.name') }} secara global di sistem Anda.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Buat proyek baru">
Perintah ini meng-clone template {{ config('app.name') }}, mengkonfigurasi proyek dengan nama aplikasi Anda, dan menginstal semua dependensi secara otomatis.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Siapkan alias Metro">
Ini mengkonfigurasi perintah `metro` untuk proyek Anda, memungkinkan Anda menggunakan perintah Metro CLI tanpa sintaks `dart run` lengkap.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Yang Anda dapatkan" items="Routing dan navigasi yang sudah dikonfigurasi
Boilerplate layanan API
Pengaturan tema dan lokalisasi
Metro CLI untuk pembuatan kode" />


<div id="running-the-project"></div>

## Menjalankan Proyek

Proyek {{ config('app.name') }} berjalan seperti aplikasi Flutter standar pada umumnya.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Jika build berhasil, aplikasi akan menampilkan layar landing default {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Buka folder proyek, pilih perangkat dari pemilih target, lalu tekan **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Dokumentasi Flutter: menjalankan dan debugging ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Buka folder proyek, lalu jalankan **Debug: Start Without Debugging** dari command palette.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Dokumentasi Flutter: jalankan tanpa breakpoint ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro membuat file proyek untuk Anda. Jalankan tanpa argumen untuk melihat menu, atau panggil perintah secara langsung.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referensi perintah

Setiap perintah menerima nama, misalnya `metro make:page settings_page`

<x-doc-commands title="Perintah widget" rows="
metro make:page | Buat halaman baru
metro make:stateful_widget | Buat widget stateful
metro make:stateless_widget | Buat widget stateless
metro make:state_managed_widget | Buat widget yang dikelola state
metro make:navigation_hub | Buat navigation hub (navigasi bawah)
metro make:journey_widget | Buat widget journey untuk navigation hub
metro make:bottom_sheet_modal | Buat modal bottom sheet
metro make:button | Buat widget tombol kustom
metro make:form | Buat form dengan validasi
" />

<x-doc-commands title="Perintah helper" rows="
metro make:model | Buat kelas model
metro make:provider | Buat provider
metro make:api_service | Buat layanan API
metro make:controller | Buat controller
metro make:event | Buat event
metro make:route_guard | Buat route guard
metro make:config | Buat file konfigurasi
metro make:interceptor | Buat interceptor jaringan
metro make:command | Buat perintah Metro kustom
metro make:env | Generate konfigurasi environment dari .env
" />

### Contoh Penggunaan

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
