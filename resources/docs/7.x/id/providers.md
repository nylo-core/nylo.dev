# Provider

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Membuat provider](#create-a-provider "Membuat provider")
- [Objek provider](#provider-object "Objek provider")


<div id="introduction"></div>

## Pengantar Provider

Di {{ config('app.name') }}, provider di-boot pertama kali dari file <b>main.dart</b> saat aplikasi Anda berjalan. Semua provider Anda berada di `/lib/app/providers/*`, Anda dapat memodifikasi file-file ini atau membuat provider Anda menggunakan <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Provider dapat digunakan ketika Anda perlu menginisialisasi class, package, atau membuat sesuatu sebelum aplikasi dimuat pertama kali. Misalnya, class `route_provider.dart` bertanggung jawab untuk menambahkan semua route ke {{ config('app.name') }}.

### Penjelasan mendalam

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Siklus Hidup

- `Boot.{{ config('app.name') }}` akan melakukan loop melalui provider yang terdaftar di dalam file <b>config/providers.dart</b> dan mem-boot-nya.

- `Boot.Finished` dipanggil tepat setelah **"Boot.{{ config('app.name') }}"** selesai, metode ini akan mengikat instance {{ config('app.name') }} ke `Backpack` dengan nilai 'nylo'.

Contoh: Backpack.instance.read('nylo'); // instance {{ config('app.name') }}


<div id="create-a-provider"></div>

## Membuat Provider Baru

Anda dapat membuat provider baru dengan menjalankan perintah berikut di terminal.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Objek Provider

Provider Anda akan memiliki dua metode, `setup(Nylo nylo)` dan `boot(Nylo nylo)`.

Saat aplikasi berjalan untuk pertama kali, kode apa pun di dalam metode **setup** Anda akan dieksekusi terlebih dahulu. Anda juga dapat memanipulasi objek `Nylo` seperti pada contoh di bawah ini.

Contoh: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Siklus Hidup

1. `setup(Nylo nylo)` - Inisialisasi provider Anda. Kembalikan instance `Nylo` atau `null`.
2. `boot(Nylo nylo)` - Dipanggil setelah semua provider selesai setup. Gunakan ini untuk inisialisasi yang bergantung pada provider lain yang sudah siap.

> Di dalam metode `setup`, Anda harus **mengembalikan** instance `Nylo` atau `null` seperti di atas.
