# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar Nylo Live")
- Shell metro live
  - [Opsi Bersama](#shared-options "Opsi yang diterima setiap perintah live")
  - [Pelengkapan Tab dan Riwayat](#tab-completion-and-history "Pelengkapan tab dan riwayat")
  - [Menjalankan Skrip](#running-a-script "Menjalankan skrip .live")
- [live:devices](#live-devices "Daftar aplikasi yang berjalan")
- [live:status](#live-status "Tampilkan status aplikasi")
- [live:run](#live-run "Jalankan perintah live bawaan")
- [Playbook](#playbooks "Memutar playbook dari Metro")
- [Merekam Journey](#recording-a-journey "Merekam apa yang Anda lakukan sebagai playbook")
- [Screenshot](#screenshots "Menyimpan screenshot aplikasi yang berjalan")
- Perintah Live
  - [Membuat Perintah Live](#creating-a-live-command "Membuat perintah live")
  - [Menjalankan Perintah Live](#running-a-live-command "Menjalankan perintah live")
- Seeder
  - [Membuat Seeder](#creating-a-seeder "Membuat seeder")
  - [Seeding dan Rollback](#seeding-and-rolling-back "Seeding dan rollback")
- Snapshot Penyimpanan
  - [Mengekspor Snapshot](#exporting-a-snapshot "Mengekspor snapshot")
  - [Memuat Snapshot](#loading-a-snapshot "Memuat snapshot")
- [Aksi Halaman](#page-actions "Memanggil halaman dari luar dirinya")

<div id="introduction"></div>

## Pengantar

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** memungkinkan Metro menjangkau aplikasi Anda saat sedang berjalan dalam mode debug (atau profile), melalui Dart VM service. Metro menemukan aplikasi melalui Dart Tooling Daemon tanpa perlu konfigurasi apa pun, dan hanya akan terhubung ke aplikasi yang dibangun dari proyek saat ini.

Nylo Live aktif secara default pada build debug dan profile; build release tidak mendaftarkan apa pun. Perintah yang hanya membaca aplikasi (`live:status`, `live:run data`, `live:run routes`, ...) juga berfungsi pada build profile. Perintah yang mengubah aplikasi (`route`, `storage`, `auth`, seeder, ...) memerlukan build debug.

Nonaktifkan sendiri, sebelum hal lain berjalan, dari sebuah provider:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## Shell metro live

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Buka shell yang terhubung ke aplikasi Anda yang sedang berjalan:

``` bash
metro live
```

Nylo Live menemukan aplikasi proyek yang sedang berjalan dan terhubung ke salah satunya -- Anda akan diminta memilih jika ada lebih dari satu yang berjalan. Setelah terhubung, prompt menampilkan perangkat dan route yang sedang tampil di layar:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Perintah live menghilangkan prefiks `metro live:` di dalam shell -- `status` alih-alih `metro live:status`, `route /profile` alih-alih `metro live:run route /profile`. Beberapa perintah hanya berjalan di dalam shell: `seed`, `seed:rollback`, `export`, `record`, `reload`, dan `restart`. Ketik `help` untuk melihat daftar semua perintah, atau `help <command>` untuk melihat opsi satu perintah. Ketik `exit` (atau Ctrl+D) untuk keluar.

Jika aplikasi restart saat Anda terhubung, shell akan menghubungkan kembali dengan sendirinya.

<div id="shared-options"></div>

### Opsi Bersama

Setiap perintah live -- di dalam shell atau sebagai `metro live:*` -- menerima opsi yang sama:

| Opsi | Deskripsi |
|--------|-------------|
| `-d, --device <#\|name>` | Menarget aplikasi berdasarkan nomornya di `metro live:devices` atau nama perangkatnya |
| `--all` | Jalankan pada setiap aplikasi proyek ini yang sedang berjalan |
| `--json` | Cetak JSON yang dapat dibaca mesin |
| `--uri` | Gunakan alamat VM service ini alih-alih menemukan aplikasi |
| `--timeout` | Detik untuk menunggu setiap aplikasi saat menemukan (default `3`) |

Nilai opsi yang ditulis `@path` akan dibaca dari file tersebut -- berguna untuk payload `--data` yang besar:

``` bash
metro live:run route /profile --data @user.json
```

Awali nilai dengan `@@` jika nilai tersebut memang dimulai dengan `@`.

<div id="tab-completion-and-history"></div>

### Pelengkapan Tab dan Riwayat

Di dalam shell, Tab melengkapi nama perintah, opsi, route, storage dan key Backpack, nama seeder, serta nama playbook terhadap aplikasi yang terhubung. Riwayat disimpan di `.dart_tool/nylo/live_history` dan tetap ada di antara sesi.

<div id="running-a-script"></div>

### Menjalankan Skrip

Alirkan sebuah file ke `metro live` untuk menjalankannya sebagai skrip, satu perintah per baris, berhenti pada perintah pertama yang gagal:

``` bash
metro live < seeds/demo.live
```

Baris kosong dan baris yang dimulai dengan `#` dilewati.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Menampilkan daftar aplikasi proyek ini yang sedang berjalan, dinomori untuk `-d`:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Menampilkan aplikasi, perangkat, mode build, route dan stack saat ini, locale, tema, dan apakah pengguna sedang masuk:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Menjalankan perintah bawaan yang memeriksa atau menggerakkan aplikasi:

``` bash
metro live:run <command> [arguments]
```

| Perintah | Deskripsi |
|---------|-------------|
| `data` | Tampilkan data dan field dari halaman yang sedang tampil |
| `routes` | Tampilkan daftar route yang terdaftar |
| `route` | Buka sebuah halaman, dengan `--data` opsional |
| `back` | Kembali satu halaman, atau kembali ke sebuah route |
| `deeplink` | Buka sebuah deep link, atau tampilkan cara mengaturnya |
| `storage` | Tampilkan daftar local storage, atau tampilkan, simpan, atau hapus satu nilai |
| `storage:clear` | Bersihkan local storage, dengan `--keep` opsional |
| `backpack` | Tampilkan daftar nilai Backpack, atau tampilkan, simpan, atau hapus satu nilai |
| `auth` | Tampilkan siapa yang sedang masuk, atau masuk dan keluar |
| `locale` | Ganti bahasa aplikasi |
| `theme` | Ganti tema aplikasi |
| `state` | Kirim data ke sebuah state dengan `updateState` |
| `event` | Picu salah satu event Anda |
| `toast` | Tampilkan notifikasi toast |
| `playbook` | Putar [playbook](/docs/{{ $version }}/playbooks) Anda, atau tampilkan daftarnya |
| `screenshot` | Simpan [screenshot](#screenshots) aplikasi, atau satu `ScreenshotArea` |

Di dalam `metro live`, hilangkan bagian `live:run` -- `route /profile` alih-alih `metro live:run route /profile`:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` juga membaca field yang dideklarasikan oleh state halaman Anda, langsung dari aplikasi yang sedang berjalan -- tidak ada reflection di Flutter, jadi ini hanya berfungsi untuk state yang benar-benar sedang tampil di layar:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbook

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Putar satu atau lebih [playbook](/docs/{{ $version }}/playbooks) pada aplikasi Anda yang sedang berjalan. Setiap langkah dicetak saat terjadi:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Sebutkan beberapa playbook untuk memutarnya berurutan. Tanpa nama, atau dengan `--list`, perintah ini menampilkan daftar playbook aplikasi.

| Opsi | Deskripsi |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Putar dengan kecepatan ini, bukan kecepatan bawaan masing-masing playbook |
| `--data <json>` | Objek JSON yang dibaca setiap playbook dalam proses ini di `data`, di atas nilai defaultnya |
| `--no-overlay` | Sembunyikan hitung mundur, serta jari dan keterangan dari playbook yang menampilkannya |
| `--loop` | Putar playbook berulang kali hingga Ctrl+C |
| `--record[=<file>]` | Rekam layar perangkat selama proses. Disimpan ke `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (atau `.gif`) memilih file, relatif terhadap proyek. Memerlukan ffmpeg |
| `--no-captions` | Lewati file keterangan `.vtt` dan chapter pada rekaman |
| `--clean-status-bar` | Tampilkan 9:41 dengan sinyal dan baterai penuh saat playbook diputar, lalu kembalikan seperti semula. Juga berfungsi tanpa `--record` |
| `--countdown <seconds>` | Hitung mundur 0-10 detik di layar sebelum langkah pertama |
| `--screenshots[=<mode>]` | Simpan juga layar setiap langkah. `<mode>` adalah `all`, `auto`, `marked`, `failures`, atau `off`; lihat [Screenshot Playbook](#playbook-screenshots) |
| `--scale <n>` | Ukuran screenshot relatif terhadap ukuran asli perangkat, hingga 4, mis. `0.5` untuk setengah |
| `--locales <list>` | Putar sekali di setiap locale ini, mis. `en,es`, atau `all` milik aplikasi |
| `--themes <list>` | Putar sekali di setiap tema ini, mis. `light,dark`, atau `all` milik aplikasi |
| `--store <id>` | Gambar screenshot pada ukuran app store, mis. `ios-6.9` |
| `--frame` | Gambar setiap layar di dalam bingkai ponsel |
| `--background <hex>` | Warna di sekitar layar, mis. `"#1B2027"` |
| `--list` | Tampilkan daftar playbook aplikasi alih-alih memutar salah satunya |

Ctrl+C meminta aplikasi berhenti: langkah yang tersisa dilewati, tetapi `tearDown` tetap berjalan. Tekan Ctrl+C sekali lagi untuk berhenti menunggu.

Jika tidak ada langkah yang sampai ke Metro dalam 20 detik, Metro berhenti menunggu dan menggagalkan proses. Aplikasi sedang memutar playbook, tetapi event-nya tidak sampai, yang terjadi saat aplikasi dijalankan dengan `flutter run --no-dds`. Jalankan dengan `flutter run` biasa dan coba lagi.

Di dalam shell `metro live` perintahnya adalah `playbook <name>`, dan Tab melengkapi nama playbook:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>
  
  ### Merekam
  
  <x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />
  
  <!-- uncertain: new Recording section; no prior locale context for recording terms -->
  Tambahkan `--record` untuk menyimpan video dari proses:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Di dalam shell `metro live`, ketik `playbook sign_up --record`.

Perekaman memerlukan ffmpeg (misalnya `brew install ffmpeg`). Metro memeriksanya sebelum apa pun diputar.

- **iOS Simulator** -- direkam dengan `xcrun simctl`.
- **Emulator atau perangkat Android** -- direkam dengan `adb screenrecord`, yang berhenti setelah 3 menit, atau dengan `scrcpy` bila terpasang (tanpa batas, dan merekam suara di Android 11+). Jika beberapa perangkat Android terhubung dan Metro tidak tahu mana yang menjalankan aplikasi, atur `ANDROID_SERIAL`.
- **iPhone fisik** -- tidak dapat direkam dari command line. Rekam dengan QuickTime atau Control Center dan gunakan `--countdown 3` agar ada waktu untuk memulai.

Hasilnya adalah file `.mp4` (H.264, 60 fps stabil) yang berdurasi persis sama dengan prosesnya, atau `.gif` selebar 480px dengan 15 fps. Setiap langkah menjadi keterangan dalam file `.vtt` di samping `.mp4` dan menjadi chapter di dalamnya. Keduanya dibuat dari langkah-langkah proses, baik playbook menampilkan keterangan di layar maupun tidak. Berikan `--no-captions` untuk melewatinya.

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

Playbook yang gagal atau dihentikan tetap disimpan, sehingga rekaman juga berfungsi sebagai laporan bug. Ctrl+C menghentikan playbook (`tearDown` tetap berjalan) dan rekaman tetap disimpan.

`--clean-status-bar` mempertahankan status bar yang sudah Anda atur dengan `simctl status_bar` di Simulator. Di Android, ini memakai mode demo dan mematikannya sesudahnya. Dengan beberapa aplikasi (`--all`), setiap perangkat mendapat file sendiri dengan nama perangkat di dalamnya.

Secara default playbook menyembunyikan jari dan keterangannya. Untuk video demo, atur `showFinger` dan `caption` pada playbook -- lihat [Jari dan Keterangan](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Screenshot

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

[Langkah `screenshot()`](/docs/{{ $version }}/playbooks#screenshots) pada playbook disimpan di `screenshots/<playbook>/` tanpa perlu flag. Tambahkan opsi untuk mengambilnya di setiap bahasa dan tema, atau untuk mengukurnya sesuai listing toko:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` juga menyimpan layar setelah setiap langkah pada playbook yang tidak memiliki langkah `screenshot()`, dan `--screenshots=all` melakukannya untuk setiap playbook. Layar dari langkah yang gagal disimpan di `build/playbooks/failures`; `--screenshots=off` mematikannya.

`--locales` dan `--themes` memutar playbook sekali di masing-masing, dan locale serta tema milik aplikasi dikembalikan setelahnya. `--store` menampilkan daftar ukuran jika tanpa nilai, dan `--frame` menggambar setiap layar dalam bingkai ponsel dengan `caption:` langkah di atasnya. `--clean-status-bar` menampilkan 9:41 dengan sinyal dan baterai penuh pada tangkapan yang diambil Metro dengan iOS Simulator atau `adb`.

<div id="playbook-live-commands"></div>

### Referensi Perintah Live

Perintah di baliknya adalah `playbooks.list`, `playbooks.run`, dan `playbooks.stop`. Sebuah proses mengembalikan id-nya segera dan mengalirkan setiap langkah sebagai event `nylo.playbook`, sehingga dapat berlangsung lebih lama dari timeout perintah. `live:status` menampilkan daftar playbook aplikasi. Playbook hadir pada Nylo Live protocol versi 9. Dukungan perekaman -- `countdown` pada `playbooks.run` (dengan event `countdown` setiap detik) dan `simulator` (UDID iOS Simulator) pada `status` -- hadir pada protocol versi 10.

<div id="recording-a-journey"></div>

## Merekam Journey

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` menulis apa yang Anda lakukan di aplikasi yang berjalan sebagai [playbook](/docs/{{ $version }}/playbooks#recording-a-playbook). Perintah ini hanya berjalan di dalam shell `metro live`:

``` plaintext
› record sign_up
```

Gunakan aplikasi, lalu tekan Enter saat journey selesai. Playbook tersimpan di `lib/app/playbooks/sign_up_playbook.dart`, terdaftar di `lib/bootstrap/playbooks.dart`, dan Metro menawarkan untuk hot restart aplikasi lalu memutarnya kembali dengan kecepatan `fast`.

| Opsi | Deskripsi |
|--------|-------------|
| `--description <text>` | Deskripsi satu baris, ditampilkan saat Metro menampilkan daftar playbook |
| `--no-data` | Tulis nilai yang diketik apa adanya, alih-alih placeholder `data[...]` dengan nilai defaultnya |
| `--no-replay` | Lewati pertanyaan tentang memutarnya kembali |
| `--force`, `-f` | Ganti file playbook yang sudah ada |

Saat merekam, ketik `a` lalu Enter agar ketukan berikutnya menjadi `see()`, `d` untuk `dontSee()`, `s <title>` untuk memulai sebuah scene, `u` untuk membatalkan langkah terakhir, dan `p` untuk jeda. Ctrl+C membuang rekaman. `metro make:playbook sign_up --record` membuka shell dan mulai merekam sekaligus.

Perintah di baliknya adalah `record.start`, `record.stop`, `record.undo`, `record.mark`, dan `record.status`. Rekaman mengalirkan setiap langkah sebagai event `nylo.record`. Perekaman hadir pada Nylo Live protocol versi 11.

<div id="screenshots"></div>

## Screenshot

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Simpan PNG dari aplikasi Anda yang berjalan:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Setiap file disimpan di `screenshots/` dalam proyek Anda, dengan nama `<mode>-<device>-<date>-<time>.png`. Di iOS Simulator dan Android, Metro menangkap seluruh layar perangkat dengan Simulator atau `adb`, sehingga status bar dan keyboard ikut masuk. Perangkat lain memakai tangkapan bawaan aplikasi, yaitu halaman aplikasi seperti yang dilihat pengguna.

| Opsi | Deskripsi |
|--------|-------------|
| `--area <name>` | Tangkap hanya `ScreenshotArea` dengan nama ini |
| `--scale <n>` | Ukuran relatif terhadap ukuran asli perangkat, hingga 4, mis. `0.5` untuk setengah |
| `--locales <list>` | Tangkap layar di setiap locale ini, mis. `en,es`, atau `all` milik aplikasi |
| `--themes <list>` | Tangkap layar di setiap tema ini, mis. `light,dark`, atau `all` milik aplikasi |
| `--store <id>` | Gambar pada ukuran screenshot app store, mis. `ios-6.9`. Jika berdiri sendiri, `--store` menampilkan daftar ukuran |
| `--frame` | Gambar layar di dalam bingkai ponsel, pada sebuah latar belakang |
| `--caption <text>` | Teks di atas `--frame`, atau kunci terjemahan |
| `--background <hex>` | Warna di sekitar layar, di belakang `--frame` atau di samping ukuran `--store` dengan bentuk lain, mis. `"#1B2027"` |
| `--out <path>` | Simpan ke `.png` ini, atau ke folder yang berakhiran `/` (default: `screenshots/`) |
| `--open` | Buka screenshot setelah disimpan |
| `--copy` | Salin screenshot ke clipboard |
| `--native` | Tangkap dengan iOS Simulator atau `adb`. Ini default untuk tangkapan seluruh layar; `--no-native` memakai tangkapan bawaan aplikasi |
| `--clean-status-bar` | Tampilkan 9:41 dengan sinyal dan baterai penuh pada tangkapan `--native` |

Dengan `--locales` atau `--themes`, Metro menyimpan satu PNG untuk setiap pasangan dalam sebuah folder, dengan nama `es.png`, `dark.png`, atau `es-dark.png`, mengganti aplikasi di tempat lalu mengembalikannya setelahnya. `--area`, `--scale`, `--store`, dan `--frame` memakai tangkapan bawaan aplikasi.

Di dalam shell `metro live`, ketik `screenshot --open`. Untuk mengambil screenshot dari kode aplikasi Anda sendiri, lihat [Screenshot](/docs/{{ $version }}/screenshots).

<div id="live-commands"></div>

## Perintah Live

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Daftarkan perintah Anda sendiri untuk dijalankan di dalam aplikasi, berdampingan dengan perintah bawaan di atas.

<div id="creating-a-live-command"></div>

### Membuat Perintah Live

``` bash
metro make:command seed_cart --live
```

| Opsi | Deskripsi |
|--------|-------------|
| `--category`, `-c` | Kategori untuk perintah (default: `app`) |
| `--description` | Deskripsi satu baris yang ditampilkan oleh `--help` perintah tersebut |
| `--force`, `-f` | Membuat file meskipun sudah ada |

Ini membuat sebuah `LiveCommand` di `lib/app/commands/`, menandainya `"type": "live"` di `commands.json`, mendaftarkannya di `lib/bootstrap/live_commands.dart`, dan menghubungkan `liveCommands: liveCommands` ke `nylo.configure(...)` di `app_provider.dart`:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` berbagi bentuk `builder`/`handle` yang sama dengan perintah Metro sendiri, tetapi `handle` berjalan **di dalam aplikasi**, sehingga dapat menggunakan `NyStorage`, `routeTo`, `Auth`, dan model Anda. `package:nylo_framework/live.dart` mengekspor `LiveCommand` bersama `Seeder`, `StorageSnapshot`, `LiveException`, dan `LiveOutput`.

<div id="running-a-live-command"></div>

### Menjalankan Perintah Live

Perintah live yang baru memerlukan hot restart sebelum aplikasi yang sedang berjalan dapat menjalankannya. Kemudian, dari terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeder

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Sebuah `Seeder` membawa aplikasi Anda yang sedang berjalan ke keadaan yang diketahui -- sudah masuk, onboarding selesai, data contoh -- dan mengembalikannya lagi.

<div id="creating-a-seeder"></div>

### Membuat Seeder

``` bash
metro make:seeder demo_user
```

| Opsi | Deskripsi |
|--------|-------------|
| `--description` | Deskripsi satu baris, ditampilkan saat `seed` menampilkan daftar seeder |
| `--force`, `-f` | Membuat file meskipun sudah ada |

Ini membuat sebuah `Seeder` di `lib/app/seeders/`, mendaftarkannya di `lib/bootstrap/seeders.dart`, dan menghubungkan `seeders: seeders` ke `nylo.configure(...)`:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

Setiap nilai storage dan Backpack yang diubah oleh `up()` dicatat secara otomatis, sehingga `down()` default (`restore()`) mengembalikan masing-masing persis seperti semula, dalam urutan terbalik -- bahkan melewati hot restart. Panggil `seed(otherSeeders)` dari dalam sebuah seeder untuk menjalankan seeder lain sebagai bagiannya; perubahan mereka juga dicatat dan dapat di-rollback.

<div id="seeding-and-rolling-back"></div>

### Seeding dan Rollback

Seeding, rollback, dan ekspor hanya berjalan di dalam shell `metro live`:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` tanpa argumen menampilkan daftar seeder yang terdaftar. `--fresh` membersihkan storage dan Backpack sebelum seeding, dan `--restart` melakukan hot restart pada aplikasi setelahnya, sehingga aplikasi langsung berjalan dengan data yang sudah di-seed:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Snapshot Penyimpanan

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` menyimpan semua yang saat ini dipegang aplikasi di storage dan Backpack -- sebagai seeder, atau sebagai file JSON portabel -- dan `seed <file>` memuatnya kembali.

<div id="exporting-a-snapshot"></div>

### Mengekspor Snapshot

Jalankan tanpa nama untuk melihat pratinjau apa yang akan ditangkap:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

Beri nama untuk menyimpannya sebagai seeder di `lib/app/seeders/` (sama seperti `make:seeder`, tetapi sudah terisi dengan nilai yang ditangkap):

``` plaintext
› export pro_user
```

Atau tulis ke sebuah file:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Opsi | Deskripsi |
|--------|-------------|
| `--to <path>` | Tulis snapshot ke file JSON ini alih-alih ke seeder |
| `--description` | Deskripsi satu baris untuk seeder |
| `--only <keys>` | Key yang dipisahkan koma untuk diekspor; `*` cocok dengan apa saja, mis. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Key yang dipisahkan koma untuk dikecualikan, mis. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Sertakan nilai Backpack (default: disertakan) |
| `--force`, `-f` | Ganti seeder atau file yang sudah ada |

Sebuah `Model`, atau class apa pun dengan decoder terdaftar, ditandai berdasarkan nama class sehingga kembali melalui decoder-nya sendiri alih-alih sebagai map biasa. `export` memberi peringatan ketika snapshot memuat key yang terlihat seperti kredensial, dan ketika snapshotnya cukup besar sehingga Anda mungkin memerlukan `--except`.

<div id="loading-a-snapshot"></div>

### Memuat Snapshot

``` plaintext
› seed snapshots/pro_user.json
```

Memuat sebuah file dicatat dengan cara yang sama seperti seeder, sehingga `seed:rollback <name>` (nama file, atau apa pun yang diberikan `--as`) juga membatalkannya. Dari kode, sebuah seeder dapat menerapkan snapshot yang ditangkap dengan `importSnapshot`:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` menulis persis dengan bentuk ini, sehingga file yang ditulis dengan `--to` dapat langsung ditempelkan ke field `snapshot` sebuah seeder.

<div id="page-actions"></div>

## Aksi Halaman

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` pada sebuah halaman memberi Anda `PageStateActions` untuk memanggil halaman tersebut dari luar dirinya, tanpa harus membangun sendiri sebuah panggilan `stateAction(...)`:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Lihat [Memanggil Aksi Sebuah Halaman](/docs/{{ $version }}/state-management#page-actions-shortcut) untuk kumpulan lengkap aksi bawaan dan cara mendeklarasikan aksi bertipe milik Anda sendiri. Nylo Live membaca dan menggunakan aksi yang sama: `metro live:run data` menampilkan apa yang diekspos oleh halaman yang sedang tampil di bawah `actions`, dan `metro live:run state` mengirim data halaman langsung berdasarkan nama state-nya.
