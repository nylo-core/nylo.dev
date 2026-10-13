# Playbooks

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar Playbook")
- [Membuat Playbook](#creating-a-playbook "Membuat playbook")
- [Langkah](#steps "Langkah yang dapat dilakukan playbook")
- [Target](#targets "Menemukan elemen di layar")
  - [String Biasa](#plain-strings "Cara string biasa dicocokkan")
  - [Find](#find "Target presisi dengan Find")
- [Data dan Setup](#data-and-setup "Data, setUp dan tearDown")
- [Memutar Playbook Lain](#playing-other-playbooks "Menjalankan playbook dari playbook lain")
- [Kecepatan](#speed "PlaybookSpeed")
- [Jari dan Keterangan](#finger-and-caption "Jari dan keterangan langkah")
- [Mendaftarkan Playbook](#registering-playbooks "Mendaftarkan playbook ke Nylo")
- [Menjalankan Playbook](#running-a-playbook "Menjalankan playbook")
  - [Dari Metro](#running-from-metro "Menjalankan dari Metro")
  - [Saat Boot](#running-on-boot "Menjalankan saat boot")
  - [Dari Dart](#running-from-dart "Menjalankan dari Dart")
- [Laporan](#reports "PlaybookReport")
- [Build Release](#release-builds "Playbook pada build release")
- [Testing](#testing "Menguji playbook")

<div id="introduction"></div>

## Pengantar

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Sebuah **playbook** adalah class yang mendaftar tap, pengetikan, scroll, dan perpindahan halaman dari sebuah alur perjalanan pengguna. Aplikasi Anda yang sedang berjalan melakukannya sendiri, dengan jari yang terlihat dan keterangan untuk setiap langkah -- berguna untuk demo, rekaman layar, dan memeriksa sebuah alur dari awal hingga akhir.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

Impor `package:nylo_framework/playbook.dart` di mana pun Anda menulis atau mendaftarkan playbook.

<div id="creating-a-playbook"></div>

## Membuat Playbook

Gunakan Metro untuk membuatnya:

``` bash
metro make:playbook sign_up
```

Ini membuat `lib/app/playbooks/sign_up_playbook.dart` dan mendaftarkannya ke aplikasi Anda. Lihat [Membuat Playbook](/docs/{{ $version }}/metro#make-playbook) untuk opsi perintahnya.

<div id="steps"></div>

## Langkah

`steps` sebuah playbook disusun dengan kata kerja yang diwarisinya:

| Langkah | Deskripsi |
|------|-------------|
| `tap(target)` | Tap sebuah target |
| `longPress(target)` | Tekan dan tahan sebuah target |
| `type(target, text)` | Ketik ke dalam field. Dikosongkan dahulu kecuali `clear: false`; `submit: true` menekan tombol aksi keyboard; `secret: true` menyembunyikan teks di keterangan dan laporan |
| `scrollTo(target)` | Scroll hingga target terlihat di layar. Berikan `within:` untuk men-scroll scrollable tertentu |
| `see(target)` | Pastikan target ada di layar, menunggunya muncul |
| `dontSee(target)` | Pastikan target tidak ada di layar, menunggunya hilang |
| `waitFor(target)` | Tunggu sebuah target, hingga `timeout` (10 detik secara default) |
| `waitForPage(path)` | Tunggu halaman pada route path atau `RouteView` (mis. `HomePage.path`) |
| `visit(path)` | Buka halaman berdasarkan route path atau `RouteView`, dengan `data:` opsional |
| `back()` | Kembali satu halaman |
| `pause(seconds: 1)` | Diam sejenak, mis. agar demo tidak terburu-buru |
| `step(title, action)` | Jalankan kode apa pun dengan kata kerja yang sama, melalui `PlaybookActor` |
| `scene(title, steps)` | Kelompokkan langkah di bawah sebuah judul; keterangannya diawali judul itu dan hasilnya bersarang di laporan |
| `play(playbook)` | Jalankan playbook lain -- lihat [Memutar Playbook Lain](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

Langkah berjalan berurutan dan berhenti pada kegagalan pertama. Langkah yang tersisa dilaporkan sebagai dilewati.

<div id="targets"></div>

## Target

Sebagian besar langkah menerima target: apa yang di-tap, diketik, atau dicari.

<div id="plain-strings"></div>

### String Biasa

String biasa dicocokkan seperti cara seseorang membaca layar. Urutan percobaannya:

1. Teks yang terlihat
2. Hint atau label sebuah field
3. Tooltip
4. Label semantics
5. `ValueKey`
6. Teks yang mengandung string tersebut

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

`Key`, `Type`, atau `IconData` juga dapat langsung diberikan ke sebuah langkah.

<div id="find"></div>

### Find

Gunakan `Find` ketika string biasa kurang presisi:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Mencocokkan |
|--------|---------|
| `Find.text(text)` | Teks yang terlihat |
| `Find.textContaining(text)` | Teks yang mengandung string tersebut |
| `Find.icon(icon)` | Sebuah ikon |
| `Find.type<T>()` | Widget bertipe `T` |
| `Find.key(key)` | Widget dengan key tersebut |
| `Find.field(name)` | Field `NyForm` berdasarkan nama |
| `Find.input(text)` | Text field berdasarkan hint, label, atau placeholder |
| `Find.tooltip(message)` | Sebuah tooltip |
| `Find.semantics(label)` | Label semantics |
| `Find.where(test)` | Widget yang lolos test Anda |

Persempit finder apa pun dengan `.inside(ancestor)`, `.at(index)` (dihitung dari 0), `.first`, atau `.last`.

Sebuah tap ditolak ketika sesuatu yang tidak terkait, seperti barrier dialog, menutupi targetnya.

<div id="data-and-setup"></div>

## Data dan Setup

`defaults` menyimpan data awal untuk langkah-langkah. Bacalah melalui `data` di dalam `steps`. `setUp` berjalan sebelum langkah dan `tearDown` berjalan setelahnya, bahkan ketika sebuah langkah gagal:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` menjalankan [Seeder](/docs/{{ $version }}/metro-live#seeders) (instance atau nama terdaftar) dan mencatatnya seperti `seed` di `metro live`.

<div id="playing-other-playbooks"></div>

## Memutar Playbook Lain

`play()` menjalankan playbook lain secara penuh, dengan `setUp` dan `tearDown`-nya sendiri. Berikan instance atau nama tempat ia didaftarkan:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Data** digabungkan dengan urutan ini: `defaults` milik child, lalu `data` milik parent, lalu `data:` yang diberikan ke `play`.
- **Kecepatan** diwarisi dari parent kecuali Anda memberikan `speed:`.
- **Hasil** bersarang di bawah langkah tersebut dalam laporan.
- **Child yang gagal** menggagalkan langkah.
- **Siklus** ditolak, dan playbook bersarang paling dalam 8 tingkat.

<div id="speed"></div>

## Kecepatan

`PlaybookSpeed` mengatur tempo setiap penantian:

| Kecepatan | Deskripsi |
|-------|-------------|
| `PlaybookSpeed.instant` | Tanpa penantian selain yang dibutuhkan scroll. Ditujukan untuk test |
| `PlaybookSpeed.fast` | Cepat, tetapi tetap mudah diikuti |
| `PlaybookSpeed.natural` | Tempo seseorang yang menggunakan aplikasi. Default |
| `PlaybookSpeed.slow` | Cukup lambat untuk dinarasikan |

Skalakan kecepatan apa pun dengan `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // dua kali lebih lambat
```

Kecepatan yang diberikan ke `run()`, `--speed` dari Metro, atau kecepatan playbook yang memutar playbook ini lebih diutamakan daripada kecepatan bawaan playbook.

<div id="finger-and-caption"></div>

## Jari dan Keterangan

Override `showFinger` dan `caption` untuk mengubah apa yang digambar overlay:

``` dart
@override
bool get showFinger => false;

@override
PlaybookCaption get caption => PlaybookCaption.top;
```

`PlaybookCaption` adalah `top`, `bottom` (default), atau `hidden`. Tap dan swipe tetap terjadi tanpa jari. Untuk mematikan seluruh overlay pada satu proses, gunakan `--no-overlay` dari Metro atau `run(overlay: false)` dari Dart.

<div id="registering-playbooks"></div>

## Mendaftarkan Playbook

Daftarkan playbook Anda agar Metro dan `play('name')` dapat menemukannya:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` mengembalikan apa yang terdaftar. Anda juga dapat memberikannya saat mengonfigurasi Nylo di `AppProvider` Anda:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` melakukannya untuk Anda. Perintah ini menyimpan registry di `lib/bootstrap/playbooks.dart`, menambahkan setiap playbook baru ke dalamnya, dan meneruskan `playbooks` ke `nylo.configure(...)`. Registry-nya berupa map kosong pada build release:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Playbook terdaftar berjalan dengan key-nya. Yang tidak terdaftar berjalan dengan `name`-nya, atau nama class-nya dalam snake case (`SignUpPlaybook` menjadi `sign_up`).

<div id="running-a-playbook"></div>

## Menjalankan Playbook

<div id="running-from-metro"></div>

### Dari Metro

Dengan aplikasi Anda berjalan dalam mode debug:

``` bash
metro live:run playbook sign_up --speed fast
```

Di dalam `metro live`, ketik `playbook sign_up`. Lihat [Metro Live](/docs/{{ $version }}/metro-live#playbooks) untuk semua opsi.

<div id="running-on-boot"></div>

### Saat Boot

Tambahkan key berikut ke file `.env` Anda untuk memutar playbook begitu aplikasi tampil di layar:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` adalah daftar nama playbook yang dipisahkan koma dan `PLAYBOOK_SPEED` adalah salah satu dari `instant`, `fast`, `natural`, atau `slow`. Keduanya hanya berlaku pada build debug dan profile.

<div id="running-from-dart"></div>

### Dari Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` menerima `speed`, `data`, `clock`, `overlay` (default `true`), dan `onEvent`. Langkah yang gagal tidak melempar exception -- periksa `report.passed`. Hanya satu playbook yang dapat berjalan pada satu waktu; gunakan `play()` untuk menjalankan satu playbook di dalam yang lain.

Hentikan playbook yang sedang berjalan dengan `Playbook.stop()`. Langkah yang tersisa dilewati tetapi `tearDown` tetap berjalan. `Playbook.isRunning` memberi tahu apakah ada yang sedang berjalan.

<div id="reports"></div>

## Laporan

`run()` mengembalikan `PlaybookReport` berisi `name`, `speed`, waktu `elapsed` playbook, apakah ia `stopped`, dan daftar `steps` berisi `PlaybookStepResult` (masing-masing dengan `title`, `status`, `elapsed`, `error`, dan `steps` bersarang). `report.passed` bernilai true ketika setiap langkah lolos, dan mencetak laporan terbaca seperti keluaran Metro. `report.toJson()` memberikan data yang sama dalam bentuk JSON.

<div id="release-builds"></div>

## Build Release

Playbook tidak pernah berjalan pada build release, sehingga sebuah alur tidak dapat menggerakkan aplikasi pengguna:

- `Playbook.run()` melempar `UnsupportedError`.
- `addPlaybooks` diabaikan.
- Registry `lib/bootstrap/playbooks.dart` yang dihasilkan Metro kosong.

`Playbook.isAvailable` memberi tahu apakah build saat ini dapat menjalankan playbook.

<div id="testing"></div>

## Testing

Jalankan playbook dalam widget test dengan `NyTesterClock`, lalu periksa laporannya dengan `expectPlaybookPassed`:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Lihat [Menguji Playbook](/docs/{{ $version }}/testing#testing-playbooks) untuk integration test.
