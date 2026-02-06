# Penggunaan Aplikasi

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Pengaturan](#setup "Mengatur penggunaan aplikasi")
- Pemantauan
    - [Peluncuran aplikasi](#monitoring-app-launches "Memantau peluncuran aplikasi")
    - [Tanggal peluncuran pertama](#monitoring-app-first-launch-date "Memantau tanggal peluncuran pertama aplikasi")
    - [Total hari sejak peluncuran pertama](#monitoring-app-total-days-since-first-launch "Memantau total hari sejak peluncuran pertama aplikasi")

<div id="introduction"></div>

## Pengantar

Nylo memungkinkan Anda memantau penggunaan aplikasi secara langsung, tetapi pertama-tama Anda perlu mengaktifkan fitur ini di salah satu app provider Anda.

Saat ini, Nylo dapat memantau hal-hal berikut:

- Peluncuran aplikasi
- Tanggal peluncuran pertama

Setelah membaca dokumentasi ini, Anda akan belajar cara memantau penggunaan aplikasi Anda.

<div id="setup"></div>

## Pengaturan

Buka file `app/providers/app_provider.dart` Anda.

Kemudian, tambahkan kode berikut ke method `boot` Anda.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Ini akan mengaktifkan pemantauan penggunaan aplikasi di aplikasi Anda. Jika Anda perlu memeriksa apakah pemantauan penggunaan aplikasi diaktifkan, Anda dapat menggunakan method `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Memantau Peluncuran Aplikasi

Anda dapat memantau jumlah kali aplikasi Anda telah diluncurkan menggunakan method `Nylo.appLaunchCount`.

> Peluncuran aplikasi dihitung setiap kali aplikasi dibuka dari keadaan tertutup.

Contoh sederhana cara menggunakan method ini:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Memantau Tanggal Peluncuran Pertama Aplikasi

Anda dapat memantau tanggal aplikasi Anda pertama kali diluncurkan menggunakan method `Nylo.appFirstLaunchDate`.

Berikut contoh cara menggunakan method ini:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Memantau Total Hari Sejak Peluncuran Pertama Aplikasi

Anda dapat memantau total hari sejak aplikasi Anda pertama kali diluncurkan menggunakan method `Nylo.appTotalDaysSinceFirstLaunch`.

Berikut contoh cara menggunakan method ini:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
