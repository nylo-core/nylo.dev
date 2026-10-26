# Screenshot

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar Screenshot")
- [Menangkap Layar](#capturing-the-screen "Menangkap seluruh layar")
- [Menangkap Sebuah Area](#capturing-an-area "Menangkap satu bagian layar dengan ScreenshotArea")
- [Menyimpan Screenshot](#saving-a-screenshot "Menyimpan screenshot ke disk")
- [Listing Toko](#store-listings "Mengukur screenshot untuk App Store dan Google Play")
  - [Ukuran Toko](#store-sizes "Ukuran ScreenshotStore")
- [Menyembunyikan Konten](#hiding-content "Menyembunyikan konten dari screenshot")
- [Menangkap Widget](#capturing-a-widget "Menangkap widget tanpa menampilkannya")
- [Locale dan Tema](#locales-and-themes "Menangkap dalam locale atau tema lain")
- [Dari Metro](#from-metro "Mengambil screenshot dari Metro")

<div id="introduction"></div>

## Pengantar

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` mengambil PNG dari layar aplikasi Anda, atau dari salah satu bagiannya:

``` dart
// Seluruh layar, termasuk dialog dan sheet
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Satu bagian layar
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Penangkapan berfungsi di setiap mode build, sehingga dapat mendukung fitur di aplikasi Anda, seperti membagikan struk atau melampirkan layar ke laporan bug. [Metro](#from-metro) dan [playbook](/docs/{{ $version }}/playbooks#screenshots) menggunakan kode yang sama.

`Screenshot` menyimpan PNG sebagai `bytes`, dengan `width` dan `height` dalam piksel fisik.

<div id="capturing-the-screen"></div>

## Menangkap Layar

`Screenshot.capture()` membaca seluruh layar aplikasi seperti yang dilihat pengguna:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parameter | Deskripsi |
|-----------|-------------|
| `pixelRatio` | Ukuran relatif terhadap piksel logis. Default-nya mengikuti perangkat, sehingga PNG memiliki resolusi asli layar |
| `settle` | Tunggu frame berikutnya sebelum menangkap (default `true`). Berikan `false` saat sebuah frame baru saja digambar |
| `locale` | Tampilkan layar dalam bahasa ini untuk penangkapan, mis. `'es'` |
| `theme` | Tampilkan layar dalam id tema ini untuk penangkapan, mis. `'dark'` |

Aplikasi yang dibangun dengan `NyApp.materialApp` menangkap persis halamannya. Jika Anda membangun `MaterialApp` sendiri, tambahkan `ScreenshotRoot` ke `builder`-nya:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Menangkap Sebuah Area

Bungkus sebagian layar dalam `ScreenshotArea` dan beri nama:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Lalu tangkap dengan nama itu:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` menerima parameter `pixelRatio`, `settle`, `locale`, dan `theme` yang sama seperti `capture`. `Screenshot.areas` mendaftar nama area yang ada di layar.

<div id="saving-a-screenshot"></div>

## Menyimpan Screenshot

`store` menyimpan PNG ke disk penyimpanan file dan mengembalikan path-nya:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Tanpa `disk`, ia menyimpan ke disk default. Untuk membagikan gambar atau mengunggahnya, baca `shot.bytes`.

<div id="store-listings"></div>

## Listing Toko

`forStore` mengembalikan screenshot baru pada ukuran persis yang diminta sebuah app store:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Layar dengan bentuk yang hampir sama memenuhi ukuran tersebut, dan yang lain dimuat di dalamnya. Dengan `frame: true`, layar digambar dalam bingkai ponsel di bawah `caption`.

| Parameter | Deskripsi |
|-----------|-------------|
| `frame` | Gambar layar di dalam bingkai ponsel |
| `caption` | Teks di atas ponsel, digunakan bersama `frame` |
| `background` | `Color` di sekitar layar |

Gunakan `framed()` untuk menggambar bingkai pada ukuran screenshot itu sendiri:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Ukuran Toko

| Ukuran | Id | Piksel |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` mendaftar setiap ukuran, dan `ScreenshotStore.named('ios-6.9')` menemukan satu ukuran berdasarkan id-nya.

<div id="hiding-content"></div>

## Menyembunyikan Konten

Bungkus apa pun yang privat dalam `ScreenshotHidden`. Konten tampil seperti biasa di layar, dan blok abu-abu menutupinya di setiap screenshot:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Berikan `replacement` untuk menggambar placeholder Anda sendiri sebagai pengganti blok abu-abu.

<div id="capturing-a-widget"></div>

## Menangkap Widget

`Screenshot.widget` menangkap widget tanpa menampilkannya, seperti kartu berbagi yang dibuat khusus untuk gambar:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG sebesar widget-nya, jadi bungkus dalam `SizedBox` untuk ukuran yang pasti. Berikan `context` agar widget memakai tema dan lokalisasi context itu, `size` untuk mengatur ruang tempat widget ditata, dan `delay` untuk memberi gambar waktu dimuat.

<div id="locales-and-themes"></div>

## Locale dan Tema

Berikan `locale` atau `theme` untuk menampilkan layar dengan cara lain pada satu penangkapan:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

Aplikasi berganti di tempat, mempertahankan halaman, state-nya, dan stack navigasi, lalu langsung kembali. Tidak ada yang disimpan, sehingga pilihan pengguna sendiri tetap terjaga. Locale membutuhkan file bahasa di aset Anda, dan tema membutuhkan id tema yang terdaftar.

<div id="from-metro"></div>

## Dari Metro

Simpan screenshot aplikasi Anda yang sedang berjalan dari terminal:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Lihat [Metro Live](/docs/{{ $version }}/metro-live#screenshots) untuk setiap opsi. Sebuah [playbook](/docs/{{ $version }}/playbooks#screenshots) dapat mengambil screenshot seluruh journey, di setiap bahasa dan tema.
