# Penjadwal

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Jadwalkan Sekali](#schedule-once "Jadwalkan tugas untuk berjalan sekali")
- [Jadwalkan Sekali Setelah Tanggal](#schedule-once-after-date "Jadwalkan tugas untuk berjalan sekali setelah tanggal tertentu")
- [Jadwalkan Sekali Harian](#schedule-once-daily "Jadwalkan tugas untuk berjalan sekali setiap hari")

<div id="introduction"></div>

## Pengantar

Nylo memungkinkan Anda menjadwalkan tugas di aplikasi Anda untuk terjadi sekali, harian, atau setelah tanggal tertentu.

Setelah membaca dokumentasi ini, Anda akan belajar cara menjadwalkan tugas di aplikasi Anda.

<div id="schedule-once"></div>

## Jadwalkan Sekali

Anda dapat menjadwalkan tugas untuk berjalan sekali menggunakan metode `Nylo.scheduleOnce`.

Contoh sederhana cara menggunakan metode ini:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Jadwalkan Sekali Setelah Tanggal

Anda dapat menjadwalkan tugas untuk berjalan sekali setelah tanggal tertentu menggunakan metode `Nylo.scheduleOnceAfterDate`.

Contoh sederhana cara menggunakan metode ini:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Jadwalkan Sekali Harian

Anda dapat menjadwalkan tugas untuk berjalan sekali setiap hari menggunakan metode `Nylo.scheduleOnceDaily`.

Contoh sederhana cara menggunakan metode ini:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
