# Alat CLI Metro

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Instalasi](#install "Instalasi")
- Perintah Make
  - [Membuat controller](#make-controller "Membuat controller")
  - [Membuat model](#make-model "Membuat model")
  - [Membuat halaman](#make-page "Membuat halaman")
  - [Membuat stateless widget](#make-stateless-widget "Membuat stateless widget")
  - [Membuat stateful widget](#make-stateful-widget "Membuat stateful widget")
  - [Membuat journey widget](#make-journey-widget "Membuat journey widget")
  - [Membuat API Service](#make-api-service "Membuat API Service")
  - [Membuat Event](#make-event "Membuat Event")
  - [Membuat Provider](#make-provider "Membuat Provider")
  - [Membuat Theme](#make-theme "Membuat Theme")
  - [Membuat Forms](#make-forms "Membuat Forms")
  - [Membuat Route Guard](#make-route-guard "Membuat Route Guard")
  - [Membuat Config File](#make-config-file "Membuat Config File")
  - [Membuat Command](#make-command "Membuat Command")
  - [Membuat State Managed Widget](#make-state-managed-widget "Membuat State Managed Widget")
  - [Membuat Navigation Hub](#make-navigation-hub "Membuat Navigation Hub")
  - [Membuat Bottom Sheet Modal](#make-bottom-sheet-modal "Membuat Bottom Sheet Modal")
  - [Membuat Button](#make-button "Membuat Button")
  - [Membuat Interceptor](#make-interceptor "Membuat Interceptor")
  - [Membuat Env File](#make-env-file "Membuat Env File")
  - [Membuat Key](#make-key "Membuat Key")
- App Icons
  - [Membangun App Icons](#build-app-icons "Membangun App Icons")
- Perintah Kustom
  - [Membuat perintah kustom](#creating-custom-commands "Membuat perintah kustom")
  - [Menjalankan Perintah Kustom](#running-custom-commands "Menjalankan Perintah Kustom")
  - [Menambahkan opsi ke perintah](#adding-options-to-custom-commands "Menambahkan opsi ke perintah")
  - [Menambahkan flag ke perintah](#adding-flags-to-custom-commands "Menambahkan flag ke perintah")
  - [Metode helper](#custom-command-helper-methods "Metode helper")
  - [Metode input interaktif](#interactive-input-methods "Metode input interaktif")
  - [Format output](#output-formatting "Format output")
  - [Helper file system](#file-system-helpers "Helper file system")
  - [Helper JSON dan YAML](#json-yaml-helpers "Helper JSON dan YAML")
  - [Helper konversi case](#case-conversion-helpers "Helper konversi case")
  - [Helper path proyek](#project-path-helpers "Helper path proyek")
  - [Helper platform](#platform-helpers "Helper platform")
  - [Perintah Dart dan Flutter](#dart-flutter-commands "Perintah Dart dan Flutter")
  - [Manipulasi file Dart](#dart-file-manipulation "Manipulasi file Dart")
  - [Helper direktori](#directory-helpers "Helper direktori")
  - [Helper validasi](#validation-helpers "Helper validasi")
  - [Scaffolding file](#file-scaffolding "Scaffolding file")
  - [Task runner](#task-runner "Task runner")
  - [Output tabel](#table-output "Output tabel")
  - [Progress bar](#progress-bar "Progress bar")


<div id="introduction"></div>

## Pengantar

Metro adalah alat CLI yang bekerja di balik layar framework {{ config('app.name') }}.
Alat ini menyediakan banyak tools yang berguna untuk mempercepat pengembangan.

<div id="install"></div>

## Instalasi

Ketika Anda membuat proyek Nylo baru menggunakan `nylo init`, perintah `metro` akan otomatis dikonfigurasi untuk terminal Anda. Anda dapat langsung menggunakannya di proyek Nylo mana pun.

Jalankan `metro` dari direktori proyek Anda untuk melihat semua perintah yang tersedia:

``` bash
metro
```

Anda akan melihat output yang mirip dengan di bawah ini.

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
  make:key
```

<div id="make-controller"></div>

## Membuat controller

- [Membuat controller baru](#making-a-new-controller "Membuat controller baru dengan Metro")
- [Membuat controller secara paksa](#forcefully-make-a-controller "Membuat controller baru secara paksa dengan Metro")
<div id="making-a-new-controller"></div>

### Membuat controller baru

Anda dapat membuat controller baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:controller profile_controller
```

Ini akan membuat controller baru jika belum ada di dalam direktori `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Membuat controller secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa controller yang sudah ada jika sudah ada.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Membuat model

- [Membuat model baru](#making-a-new-model "Membuat model baru dengan Metro")
- [Membuat model dari JSON](#make-model-from-json "Membuat model baru dari JSON dengan Metro")
- [Membuat model secara paksa](#forcefully-make-a-model "Membuat model baru secara paksa dengan Metro")
<div id="making-a-new-model"></div>

### Membuat model baru

Anda dapat membuat model baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:model product
```

Ini akan menempatkan model yang baru dibuat di `lib/app/models/`.

<div id="make-model-from-json"></div>

### Membuat model dari JSON

**Argumen:**

Menggunakan flag `--json` atau `-j` akan membuat model baru dari payload JSON.

``` bash
metro make:model product --json
```

Kemudian, Anda dapat menempelkan JSON Anda ke terminal dan itu akan menghasilkan model untuk Anda.

<div id="forcefully-make-a-model"></div>

### Membuat model secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa model yang sudah ada jika sudah ada.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Membuat halaman

- [Membuat halaman baru](#making-a-new-page "Membuat halaman baru dengan Metro")
- [Membuat halaman dengan controller](#create-a-page-with-a-controller "Membuat halaman baru dengan controller dengan Metro")
- [Membuat halaman auth](#create-an-auth-page "Membuat halaman auth baru dengan Metro")
- [Membuat halaman initial](#create-an-initial-page "Membuat halaman initial baru dengan Metro")
- [Membuat halaman secara paksa](#forcefully-make-a-page "Membuat halaman baru secara paksa dengan Metro")

<div id="making-a-new-page"></div>

### Membuat halaman baru

Anda dapat membuat halaman baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:page product_page
```

Ini akan membuat halaman baru jika belum ada di dalam direktori `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Membuat halaman dengan controller

Anda dapat membuat halaman baru dengan controller dengan menjalankan perintah berikut di terminal.

**Argumen:**

Menggunakan flag `--controller` atau `-c` akan membuat halaman baru dengan controller.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Membuat halaman auth

Anda dapat membuat halaman auth baru dengan menjalankan perintah berikut di terminal.

**Argumen:**

Menggunakan flag `--auth` atau `-a` akan membuat halaman auth baru.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Membuat halaman initial

Anda dapat membuat halaman initial baru dengan menjalankan perintah berikut di terminal.

**Argumen:**

Menggunakan flag `--initial` atau `-i` akan membuat halaman initial baru.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Membuat halaman secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa halaman yang sudah ada jika sudah ada.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Membuat stateless widget

- [Membuat stateless widget baru](#making-a-new-stateless-widget "Membuat stateless widget baru dengan Metro")
- [Membuat stateless widget secara paksa](#forcefully-make-a-stateless-widget "Membuat stateless widget baru secara paksa dengan Metro")
<div id="making-a-new-stateless-widget"></div>

### Membuat stateless widget baru

Anda dapat membuat stateless widget baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:stateless_widget product_rating_widget
```

Perintah di atas akan membuat widget baru jika belum ada di dalam direktori `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Membuat stateless widget secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa widget yang sudah ada jika sudah ada.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Membuat stateful widget

- [Membuat stateful widget baru](#making-a-new-stateful-widget "Membuat stateful widget baru dengan Metro")
- [Membuat stateful widget secara paksa](#forcefully-make-a-stateful-widget "Membuat stateful widget baru secara paksa dengan Metro")

<div id="making-a-new-stateful-widget"></div>

### Membuat stateful widget baru

Anda dapat membuat stateful widget baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:stateful_widget product_rating_widget
```

Perintah di atas akan membuat widget baru jika belum ada di dalam direktori `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Membuat stateful widget secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa widget yang sudah ada jika sudah ada.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Membuat journey widget

- [Membuat journey widget baru](#making-a-new-journey-widget "Membuat journey widget baru dengan Metro")
- [Membuat journey widget secara paksa](#forcefully-make-a-journey-widget "Membuat journey widget baru secara paksa dengan Metro")

<div id="making-a-new-journey-widget"></div>

### Membuat journey widget baru

Anda dapat membuat journey widget baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Perintah di atas akan membuat widget baru jika belum ada di dalam direktori `lib/resources/widgets/`.

Argumen `--parent` digunakan untuk menentukan widget induk tempat journey widget baru akan ditambahkan.

Contoh

``` bash
metro make:navigation_hub onboarding
```

Selanjutnya, tambahkan journey widget baru.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Membuat journey widget secara paksa
**Argumen:**
Menggunakan flag `--force` atau `-f` akan menimpa widget yang sudah ada jika sudah ada.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Membuat API Service

- [Membuat API Service baru](#making-a-new-api-service "Membuat API Service baru dengan Metro")
- [Membuat API Service baru dengan model](#making-a-new-api-service-with-a-model "Membuat API Service baru dengan model dengan Metro")
- [Membuat API Service menggunakan Postman](#make-api-service-using-postman "Membuat API service dengan Postman")
- [Membuat API Service secara paksa](#forcefully-make-an-api-service "Membuat API Service baru secara paksa dengan Metro")

<div id="making-a-new-api-service"></div>

### Membuat API Service baru

Anda dapat membuat API service baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:api_service user_api_service
```

Ini akan menempatkan API service yang baru dibuat di `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Membuat API Service baru dengan model

Anda dapat membuat API service baru dengan model dengan menjalankan perintah berikut di terminal.

**Argumen:**

Menggunakan opsi `--model` atau `-m` akan membuat API service baru dengan model.

``` bash
metro make:api_service user --model="User"
```

Ini akan menempatkan API service yang baru dibuat di `lib/app/networking/`.

### Membuat API Service secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa API Service yang sudah ada jika sudah ada.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Membuat event

- [Membuat event baru](#making-a-new-event "Membuat event baru dengan Metro")
- [Membuat event secara paksa](#forcefully-make-an-event "Membuat event baru secara paksa dengan Metro")

<div id="making-a-new-event"></div>

### Membuat event baru

Anda dapat membuat event baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:event login_event
```

Ini akan membuat event baru di `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Membuat event secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa event yang sudah ada jika sudah ada.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Membuat provider

- [Membuat provider baru](#making-a-new-provider "Membuat provider baru dengan Metro")
- [Membuat provider secara paksa](#forcefully-make-a-provider "Membuat provider baru secara paksa dengan Metro")

<div id="making-a-new-provider"></div>

### Membuat provider baru

Buat provider baru dalam aplikasi Anda menggunakan perintah di bawah ini.

``` bash
metro make:provider firebase_provider
```

Ini akan menempatkan provider yang baru dibuat di `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Membuat provider secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa provider yang sudah ada jika sudah ada.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Membuat theme

- [Membuat theme baru](#making-a-new-theme "Membuat theme baru dengan Metro")
- [Membuat theme secara paksa](#forcefully-make-a-theme "Membuat theme baru secara paksa dengan Metro")

<div id="making-a-new-theme"></div>

### Membuat theme baru

Anda dapat membuat theme dengan menjalankan perintah berikut di terminal.

``` bash
metro make:theme bright_theme
```

Ini akan membuat theme baru di `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Membuat theme secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa theme yang sudah ada jika sudah ada.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Membuat Forms

- [Membuat form baru](#making-a-new-form "Membuat form baru dengan Metro")
- [Membuat form secara paksa](#forcefully-make-a-form "Membuat form baru secara paksa dengan Metro")

<div id="making-a-new-form"></div>

### Membuat form baru

Anda dapat membuat form baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:form car_advert_form
```

Ini akan membuat form baru di `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Membuat form secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa form yang sudah ada jika sudah ada.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Membuat Route Guard

- [Membuat route guard baru](#making-a-new-route-guard "Membuat route guard baru dengan Metro")
- [Membuat route guard secara paksa](#forcefully-make-a-route-guard "Membuat route guard baru secara paksa dengan Metro")

<div id="making-a-new-route-guard"></div>

### Membuat route guard baru

Anda dapat membuat route guard dengan menjalankan perintah berikut di terminal.

``` bash
metro make:route_guard premium_content
```

Ini akan membuat route guard baru di `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Membuat route guard secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa route guard yang sudah ada jika sudah ada.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Membuat Config File

- [Membuat config file baru](#making-a-new-config-file "Membuat config file baru dengan Metro")
- [Membuat config file secara paksa](#forcefully-make-a-config-file "Membuat config file baru secara paksa dengan Metro")

<div id="making-a-new-config-file"></div>

### Membuat config file baru

Anda dapat membuat config file baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:config shopping_settings
```

Ini akan membuat config file baru di `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Membuat config file secara paksa

**Argumen:**

Menggunakan flag `--force` atau `-f` akan menimpa config file yang sudah ada jika sudah ada.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Membuat Command

- [Membuat command baru](#making-a-new-command "Membuat command baru dengan Metro")
- [Membuat command secara paksa](#forcefully-make-a-command "Membuat command baru secara paksa dengan Metro")

<div id="making-a-new-command"></div>

### Membuat command baru

Anda dapat membuat command baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:command my_command
```

Ini akan membuat command baru di `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Membuat command secara paksa

**Argumen:**
Menggunakan flag `--force` atau `-f` akan menimpa command yang sudah ada jika sudah ada.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Membuat State Managed Widget

Anda dapat membuat state managed widget baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:state_managed_widget product_rating_widget
```

Perintah di atas akan membuat widget baru di `lib/resources/widgets/`.

Menggunakan flag `--force` atau `-f` akan menimpa widget yang sudah ada jika sudah ada.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Membuat Navigation Hub

Anda dapat membuat navigation hub baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:navigation_hub dashboard
```

Ini akan membuat navigation hub baru di `lib/resources/pages/` dan menambahkan route secara otomatis.

**Argumen:**

| Flag | Singkat | Deskripsi |
|------|-------|-------------|
| `--auth` | `-a` | Buat sebagai halaman auth |
| `--initial` | `-i` | Buat sebagai halaman initial |
| `--force` | `-f` | Timpa jika sudah ada |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Membuat Bottom Sheet Modal

Anda dapat membuat bottom sheet modal baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:bottom_sheet_modal payment_options
```

Ini akan membuat bottom sheet modal baru di `lib/resources/widgets/`.

Menggunakan flag `--force` atau `-f` akan menimpa modal yang sudah ada jika sudah ada.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Membuat Button

Anda dapat membuat widget button baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:button checkout_button
```

Ini akan membuat widget button baru di `lib/resources/widgets/`.

Menggunakan flag `--force` atau `-f` akan menimpa button yang sudah ada jika sudah ada.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Membuat Interceptor

Anda dapat membuat network interceptor baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:interceptor auth_interceptor
```

Ini akan membuat interceptor baru di `lib/app/networking/dio/interceptors/`.

Menggunakan flag `--force` atau `-f` akan menimpa interceptor yang sudah ada jika sudah ada.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Membuat Env File

Anda dapat membuat file environment baru dengan menjalankan perintah berikut di terminal.

``` bash
metro make:env .env.staging
```

Ini akan membuat file `.env` baru di root proyek Anda.

<div id="make-key"></div>

## Membuat Key

Generate `APP_KEY` yang aman untuk enkripsi environment. Ini digunakan untuk file `.env` terenkripsi di v7.

``` bash
metro make:key
```

**Argumen:**

| Flag / Opsi | Singkat | Deskripsi |
|---------------|-------|-------------|
| `--force` | `-f` | Timpa APP_KEY yang sudah ada |
| `--file` | `-e` | File .env target (default: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Membangun App Icons

Anda dapat menghasilkan semua app icons untuk IOS dan Android dengan menjalankan perintah di bawah ini.

``` bash
dart run flutter_launcher_icons:main
```

Ini menggunakan konfigurasi <b>flutter_icons</b> di file `pubspec.yaml` Anda.

<div id="custom-commands"></div>

## Perintah Kustom

Perintah kustom memungkinkan Anda memperluas CLI Nylo dengan perintah khusus proyek Anda sendiri. Fitur ini memungkinkan Anda mengotomatisasi tugas berulang, menerapkan alur kerja deployment, atau menambahkan fungsionalitas kustom apa pun langsung ke alat command-line proyek Anda.

- [Membuat perintah kustom](#creating-custom-commands)
- [Menjalankan Perintah Kustom](#running-custom-commands)
- [Menambahkan opsi ke perintah](#adding-options-to-custom-commands)
- [Menambahkan flag ke perintah](#adding-flags-to-custom-commands)
- [Metode helper](#custom-command-helper-methods)

> **Catatan:** Saat ini Anda tidak dapat mengimpor nylo_framework.dart di perintah kustom Anda, silakan gunakan ny_cli.dart sebagai gantinya.

<div id="creating-custom-commands"></div>

## Membuat Perintah Kustom

Untuk membuat perintah kustom baru, Anda dapat menggunakan fitur `make:command`:

```bash
metro make:command current_time
```

Anda dapat menentukan kategori untuk perintah Anda menggunakan opsi `--category`:

```bash
# Specify a category
metro make:command current_time --category="project"
```

Ini akan membuat file perintah baru di `lib/app/commands/current_time.dart` dengan struktur berikut:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

Perintah akan secara otomatis terdaftar di file `lib/app/commands/commands.json`, yang berisi daftar semua perintah yang terdaftar:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Menjalankan Perintah Kustom

Setelah dibuat, Anda dapat menjalankan perintah kustom Anda menggunakan shorthand Metro atau perintah Dart lengkap:

```bash
metro app:current_time
```

Ketika Anda menjalankan `metro` tanpa argumen, Anda akan melihat perintah kustom Anda tercantum di menu di bawah bagian "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Untuk menampilkan informasi bantuan untuk perintah Anda, gunakan flag `--help` atau `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Menambahkan Opsi ke Perintah

Opsi memungkinkan perintah Anda menerima input tambahan dari pengguna. Anda dapat menambahkan opsi ke perintah Anda di metode `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Add an option with a default value
  command.addOption(
    'environment',     // option name
    abbr: 'e',         // short form abbreviation
    help: 'Target deployment environment', // help text
    defaultValue: 'development',  // default value
    allowed: ['development', 'staging', 'production'] // allowed values
  );

  return command;
}
```

Kemudian akses nilai opsi di metode `handle` perintah Anda:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Command implementation...
}
```

Contoh penggunaan:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Menambahkan Flag ke Perintah

Flag adalah opsi boolean yang dapat diaktifkan atau dinonaktifkan. Tambahkan flag ke perintah Anda menggunakan metode `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // flag name
    abbr: 'v',       // short form abbreviation
    help: 'Enable verbose output', // help text
    defaultValue: false  // default to off
  );

  return command;
}
```

Kemudian periksa status flag di metode `handle` perintah Anda:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Additional logging...
  }

  // Command implementation...
}
```

Contoh penggunaan:

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Metode Helper

Kelas dasar `NyCustomCommand` menyediakan beberapa metode helper untuk membantu tugas umum:

#### Mencetak Pesan

Berikut adalah beberapa metode untuk mencetak pesan dengan warna berbeda:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Cetak pesan info dalam teks biru |
| [`error`](#custom-command-helper-formatting)     | Cetak pesan error dalam teks merah |
| [`success`](#custom-command-helper-formatting)   | Cetak pesan sukses dalam teks hijau |
| [`warning`](#custom-command-helper-formatting)   | Cetak pesan peringatan dalam teks kuning |

#### Menjalankan Proses

Jalankan proses dan tampilkan outputnya di konsol:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Tambahkan paket ke `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Tambahkan beberapa paket ke `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Jalankan proses eksternal dan tampilkan output di konsol |
| [`prompt`](#custom-command-helper-prompt)    | Kumpulkan input teks dari pengguna |
| [`confirm`](#custom-command-helper-confirm)   | Ajukan pertanyaan ya/tidak dan dapatkan hasil boolean |
| [`select`](#custom-command-helper-select)    | Tampilkan daftar opsi dan biarkan pengguna memilih satu |
| [`multiSelect`](#custom-command-helper-multi-select) | Biarkan pengguna memilih beberapa opsi dari daftar |

#### Permintaan Jaringan

Membuat permintaan jaringan melalui konsol:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Buat panggilan API menggunakan klien API Nylo |


#### Spinner Loading

Tampilkan spinner loading saat menjalankan fungsi:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Tampilkan spinner loading saat menjalankan fungsi |
| [`createSpinner`](#manual-spinner-control) | Buat instance spinner untuk kontrol manual |

#### Helper Perintah Kustom

Anda juga dapat menggunakan metode helper berikut untuk mengelola argumen perintah:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Dapatkan nilai string dari argumen perintah |
| [`getBool`](#custom-command-helper-get-bool)   | Dapatkan nilai boolean dari argumen perintah |
| [`getInt`](#custom-command-helper-get-int)    | Dapatkan nilai integer dari argumen perintah |
| [`sleep`](#custom-command-helper-sleep) | Jeda eksekusi selama durasi tertentu |


### Menjalankan Proses Eksternal

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### Manajemen Paket

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Add a package to pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Add a dev package to pubspec.yaml
addPackage('build_runner', dev: true);

// Add multiple packages at once
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Format Output

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## Metode Input Interaktif

Kelas dasar `NyCustomCommand` menyediakan beberapa metode untuk mengumpulkan input pengguna di terminal. Metode-metode ini memudahkan pembuatan antarmuka command-line interaktif untuk perintah kustom Anda.

<div id="custom-command-helper-prompt"></div>

### Input Teks

```dart
String prompt(String question, {String defaultValue = ''})
```

Menampilkan pertanyaan kepada pengguna dan mengumpulkan respons teks mereka.

**Parameter:**
- `question`: Pertanyaan atau prompt yang ditampilkan
- `defaultValue`: Nilai default opsional jika pengguna hanya menekan Enter

**Mengembalikan:** Input pengguna sebagai string, atau nilai default jika tidak ada input yang diberikan

**Contoh:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Konfirmasi

```dart
bool confirm(String question, {bool defaultValue = false})
```

Mengajukan pertanyaan ya/tidak kepada pengguna dan mengembalikan hasil boolean.

**Parameter:**
- `question`: Pertanyaan ya/tidak yang diajukan
- `defaultValue`: Jawaban default (true untuk ya, false untuk tidak)

**Mengembalikan:** `true` jika pengguna menjawab ya, `false` jika menjawab tidak

**Contoh:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // User confirmed or pressed Enter (accepting the default)
  await runProcess('flutter pub get');
} else {
  // User declined
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Seleksi Tunggal

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Menampilkan daftar opsi dan membiarkan pengguna memilih satu.

**Parameter:**
- `question`: Prompt seleksi
- `options`: Daftar opsi yang tersedia
- `defaultOption`: Seleksi default opsional

**Mengembalikan:** Opsi yang dipilih sebagai string

**Contoh:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Seleksi Ganda

```dart
List<String> multiSelect(String question, List<String> options)
```

Memungkinkan pengguna memilih beberapa opsi dari daftar.

**Parameter:**
- `question`: Prompt seleksi
- `options`: Daftar opsi yang tersedia

**Mengembalikan:** Daftar opsi yang dipilih

**Contoh:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Metode Helper API

Metode helper `api` menyederhanakan pembuatan permintaan jaringan dari perintah kustom Anda.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Contoh Penggunaan Dasar

### Permintaan GET

```dart
// Fetch data
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Permintaan POST

```dart
// Create a resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Permintaan PUT

```dart
// Update a resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Permintaan DELETE

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Permintaan PATCH

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Dengan Parameter Query

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Dengan Spinner

```dart
// Using with spinner for better UI
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Process the data
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Fungsionalitas Spinner

Spinner memberikan umpan balik visual selama operasi yang berjalan lama di perintah kustom Anda. Mereka menampilkan indikator animasi beserta pesan saat perintah Anda mengeksekusi tugas asinkron, meningkatkan pengalaman pengguna dengan menunjukkan progres dan status.

- [Menggunakan withSpinner](#using-with-spinner)
- [Kontrol spinner manual](#manual-spinner-control)
- [Contoh](#spinner-examples)

<div id="using-with-spinner"></div>

## Menggunakan withSpinner

Metode `withSpinner` memungkinkan Anda membungkus tugas asinkron dengan animasi spinner yang secara otomatis dimulai saat tugas dimulai dan berhenti saat selesai atau gagal:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parameter:**
- `task`: Fungsi asinkron yang akan dieksekusi
- `message`: Teks yang ditampilkan saat spinner berjalan
- `successMessage`: Pesan opsional yang ditampilkan saat berhasil
- `errorMessage`: Pesan opsional yang ditampilkan jika tugas gagal

**Mengembalikan:** Hasil dari fungsi tugas

**Contoh:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Run a task with a spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Long-running task (e.g., analyzing project files)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Continue with the results
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Kontrol Spinner Manual

Untuk skenario yang lebih kompleks di mana Anda perlu mengontrol status spinner secara manual, Anda dapat membuat instance spinner:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parameter:**
- `message`: Teks yang ditampilkan saat spinner berjalan

**Mengembalikan:** Instance `ConsoleSpinner` yang dapat Anda kontrol secara manual

**Contoh dengan kontrol manual:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a spinner instance
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // First task
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Second task
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Third task
    await runProcess('./deploy.sh', silent: true);

    // Complete successfully
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Handle failure
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Contoh

### Tugas Sederhana dengan Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Install dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Beberapa Operasi Berurutan

```dart
@override
Future<void> handle(CommandResult result) async {
  // First operation with spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Second operation with spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Third operation with spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Alur Kerja Kompleks dengan Kontrol Manual

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Run multiple steps with status updates
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Complete the process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Menggunakan spinner di perintah kustom Anda memberikan umpan balik visual yang jelas kepada pengguna selama operasi yang berjalan lama, menciptakan pengalaman command-line yang lebih rapi dan profesional.

<div id="custom-command-helper-get-string"></div>

### Mendapatkan nilai string dari opsi

```dart
String getString(String name, {String defaultValue = ''})
```

**Parameter:**

- `name`: Nama opsi yang akan diambil
- `defaultValue`: Nilai default opsional jika opsi tidak disediakan

**Mengembalikan:** Nilai opsi sebagai string

**Contoh:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Mendapatkan nilai bool dari opsi

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parameter:**
- `name`: Nama opsi yang akan diambil
- `defaultValue`: Nilai default opsional jika opsi tidak disediakan

**Mengembalikan:** Nilai opsi sebagai boolean


**Contoh:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Mendapatkan nilai int dari opsi

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parameter:**
- `name`: Nama opsi yang akan diambil
- `defaultValue`: Nilai default opsional jika opsi tidak disediakan

**Mengembalikan:** Nilai opsi sebagai integer

**Contoh:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Sleep selama durasi tertentu

```dart
void sleep(int seconds)
```

**Parameter:**
- `seconds`: Jumlah detik untuk sleep

**Mengembalikan:** Tidak ada

**Contoh:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Format Output

Selain metode dasar `info`, `error`, `success`, dan `warning`, `NyCustomCommand` menyediakan helper output tambahan:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Print plain text (no color)
  line('Processing your request...');

  // Print blank lines
  newLine();       // one blank line
  newLine(3);      // three blank lines

  // Print a muted comment (gray text)
  comment('This is a background note');

  // Print a prominent alert box
  alert('Important: Please read carefully');

  // Ask is an alias for prompt
  final name = ask('What is your name?');

  // Hidden input for sensitive data (e.g., passwords, API keys)
  final apiKey = promptSecret('Enter your API key:');

  // Abort the command with an error message and exit code
  if (name.isEmpty) {
    abort('Name is required');  // exits with code 1
  }
}
```

| Metode | Deskripsi |
|--------|-------------|
| `line(String message)` | Cetak teks biasa tanpa warna |
| `newLine([int count = 1])` | Cetak baris kosong |
| `comment(String message)` | Cetak teks abu-abu/muted |
| `alert(String message)` | Cetak kotak peringatan yang menonjol |
| `ask(String question, {String defaultValue})` | Alias untuk `prompt` |
| `promptSecret(String question)` | Input tersembunyi untuk data sensitif |
| `abort([String? message, int exitCode = 1])` | Keluar dari perintah dengan error |

<div id="file-system-helpers"></div>

## Helper File System

`NyCustomCommand` menyertakan helper file system bawaan sehingga Anda tidak perlu mengimpor `dart:io` secara manual untuk operasi umum.

### Membaca dan Menulis File

```dart
@override
Future<void> handle(CommandResult result) async {
  // Check if a file exists
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Check if a directory exists
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Read a file (async)
  String content = await readFile('pubspec.yaml');

  // Read a file (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // Write to a file (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Write to a file (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Append content to a file
  await appendFile('log.txt', 'New log entry\n');

  // Ensure a directory exists (creates it if missing)
  await ensureDirectory('lib/generated');

  // Delete a file
  await deleteFile('lib/generated/output.dart');

  // Copy a file
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Metode | Deskripsi |
|--------|-------------|
| `fileExists(String path)` | Mengembalikan `true` jika file ada |
| `directoryExists(String path)` | Mengembalikan `true` jika direktori ada |
| `readFile(String path)` | Baca file sebagai string (async) |
| `readFileSync(String path)` | Baca file sebagai string (sync) |
| `writeFile(String path, String content)` | Tulis konten ke file (async) |
| `writeFileSync(String path, String content)` | Tulis konten ke file (sync) |
| `appendFile(String path, String content)` | Tambahkan konten ke file |
| `ensureDirectory(String path)` | Buat direktori jika belum ada |
| `deleteFile(String path)` | Hapus file |
| `copyFile(String source, String destination)` | Salin file |

<div id="json-yaml-helpers"></div>

## Helper JSON dan YAML

Baca dan tulis file JSON dan YAML dengan helper bawaan.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Read a JSON file as a Map
  Map<String, dynamic> config = await readJson('config.json');

  // Read a JSON file as a List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Write data to a JSON file (pretty printed by default)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Write compact JSON
  await writeJson('output.json', data, pretty: false);

  // Append an item to a JSON array file
  // If the file contains [{"name": "a"}], this adds to that array
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // prevents duplicates by this key
  );

  // Read a YAML file as a Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Metode | Deskripsi |
|--------|-------------|
| `readJson(String path)` | Baca file JSON sebagai `Map<String, dynamic>` |
| `readJsonArray(String path)` | Baca file JSON sebagai `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Tulis data sebagai JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Tambahkan ke file array JSON |
| `readYaml(String path)` | Baca file YAML sebagai `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Helper Konversi Case

Konversi string antar konvensi penamaan tanpa mengimpor paket `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Metode | Format Output | Contoh |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helper Path Proyek

Getter untuk direktori proyek {{ config('app.name') }} standar. Ini mengembalikan path relatif terhadap root proyek.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Build a custom path relative to the project root
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Properti | Path |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Resolusi path relatif dalam proyek |

<div id="platform-helpers"></div>

## Helper Platform

Periksa platform dan akses variabel environment.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Platform checks
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Current working directory
  info('Working in: $workingDirectory');

  // Read system environment variables
  String home = env('HOME', '/default/path');
}
```

| Properti / Metode | Deskripsi |
|-------------------|-------------|
| `isWindows` | `true` jika berjalan di Windows |
| `isMacOS` | `true` jika berjalan di macOS |
| `isLinux` | `true` jika berjalan di Linux |
| `workingDirectory` | Path direktori kerja saat ini |
| `env(String key, [String defaultValue = ''])` | Baca variabel environment sistem |

<div id="dart-flutter-commands"></div>

## Perintah Dart dan Flutter

Jalankan perintah CLI Dart dan Flutter umum sebagai metode helper. Masing-masing mengembalikan kode exit proses.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Format a Dart file or directory
  await dartFormat('lib/app/models/user.dart');

  // Run dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Run flutter pub get
  await flutterPubGet();

  // Run flutter clean
  await flutterClean();

  // Build for a target with additional args
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Run flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // specific directory
}
```

| Metode | Deskripsi |
|--------|-------------|
| `dartFormat(String path)` | Jalankan `dart format` pada file atau direktori |
| `dartAnalyze([String? path])` | Jalankan `dart analyze` |
| `flutterPubGet()` | Jalankan `flutter pub get` |
| `flutterClean()` | Jalankan `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Jalankan `flutter build <target>` |
| `flutterTest([String? path])` | Jalankan `flutter test` |

<div id="dart-file-manipulation"></div>

## Manipulasi File Dart

Helper untuk mengedit file Dart secara programatik, berguna saat membangun alat scaffolding.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Add an import statement to a Dart file (avoids duplicates)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Insert code before the last closing brace in a file
  // Useful for adding entries to registration maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Check if a file contains a specific string
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Check if a file matches a regex pattern
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Metode | Deskripsi |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Tambahkan import ke file Dart (lewati jika sudah ada) |
| `insertBeforeClosingBrace(String filePath, String code)` | Sisipkan kode sebelum `}` terakhir di file |
| `fileContains(String filePath, String identifier)` | Periksa apakah file mengandung string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Periksa apakah file cocok dengan pola |

<div id="directory-helpers"></div>

## Helper Direktori

Helper untuk bekerja dengan direktori dan menemukan file.

```dart
@override
Future<void> handle(CommandResult result) async {
  // List directory contents
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // List recursively
  var allEntities = listDirectory('lib/', recursive: true);

  // Find files matching criteria
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Find files by name pattern
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Delete a directory recursively
  await deleteDirectory('build/');

  // Copy a directory (recursive)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Metode | Deskripsi |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Daftar isi direktori |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Temukan file yang cocok dengan kriteria |
| `deleteDirectory(String path)` | Hapus direktori secara rekursif |
| `copyDirectory(String source, String destination)` | Salin direktori secara rekursif |

<div id="validation-helpers"></div>

## Helper Validasi

Helper untuk memvalidasi dan membersihkan input pengguna untuk pembuatan kode.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validate a Dart identifier
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Require a non-empty first argument
  String name = requireArgument(result, message: 'Please provide a name');

  // Clean a class name (PascalCase, remove suffixes)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Returns: 'User'

  // Clean a file name (snake_case with extension)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Returns: 'user_model.dart'
}
```

| Metode | Deskripsi |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validasi nama identifier Dart |
| `requireArgument(CommandResult result, {String? message})` | Wajibkan argumen pertama non-kosong atau batalkan |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Bersihkan dan PascalCase nama kelas |
| `cleanFileName(String name, {String extension = '.dart'})` | Bersihkan dan snake_case nama file |

<div id="file-scaffolding"></div>

## Scaffolding File

Buat satu atau banyak file dengan konten menggunakan sistem scaffolding.

### File Tunggal

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // don't overwrite if exists
    successMessage: 'AuthService created',
  );
}
```

### Banyak File

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

Kelas `ScaffoldFile` menerima:

| Properti | Tipe | Deskripsi |
|----------|------|-------------|
| `path` | `String` | Path file yang akan dibuat |
| `content` | `String` | Konten file |
| `successMessage` | `String?` | Pesan yang ditampilkan saat berhasil |

<div id="task-runner"></div>

## Task Runner

Jalankan serangkaian tugas bernama dengan output status otomatis.

### Task Runner Dasar

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // stop pipeline if this fails (default)
    ),
  ]);
}
```

### Task Runner dengan Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

Kelas `CommandTask` menerima:

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-------------|
| `name` | `String` | wajib | Nama tugas yang ditampilkan di output |
| `action` | `Future<void> Function()` | wajib | Fungsi async yang akan dieksekusi |
| `stopOnError` | `bool` | `true` | Apakah akan menghentikan tugas tersisa jika ini gagal |

<div id="table-output"></div>

## Output Tabel

Tampilkan tabel ASCII terformat di konsol.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Output:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Progress Bar

Tampilkan progress bar untuk operasi dengan jumlah item yang diketahui.

### Progress Bar Manual

```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a progress bar for 100 items
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // increment by 1
  }

  progress.complete('All files processed');
}
```

### Memproses Item dengan Progress

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Process items with automatic progress tracking
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // process each file
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Progress Sinkron

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // synchronous processing
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

Kelas `ConsoleProgressBar` menyediakan:

| Metode | Deskripsi |
|--------|-------------|
| `start()` | Mulai progress bar |
| `tick([int amount = 1])` | Naikkan progress |
| `update(int value)` | Set progress ke nilai tertentu |
| `updateMessage(String newMessage)` | Ubah pesan yang ditampilkan |
| `complete([String? completionMessage])` | Selesaikan dengan pesan opsional |
| `stop()` | Berhenti tanpa menyelesaikan |
| `current` | Nilai progress saat ini (getter) |
| `percentage` | Progress sebagai persentase (getter) |