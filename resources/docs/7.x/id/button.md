# Button

---

<a name="section-1"></a>
- [Pendahuluan](#introduction "Pendahuluan")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Tipe Button yang Tersedia](#button-types "Tipe Button yang Tersedia")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Status Loading Async](#async-loading "Status Loading Async")
- [Gaya Animasi](#animation-styles "Gaya Animasi")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Gaya Splash](#splash-styles "Gaya Splash")
- [Gaya Loading](#loading-styles "Gaya Loading")
- [Pengiriman Formulir](#form-submission "Pengiriman Formulir")
- [Menyesuaikan Button](#customizing-buttons "Menyesuaikan Button")
- [Referensi Parameter](#parameters "Referensi Parameter")


<div id="introduction"></div>

## Pendahuluan

{{ config('app.name') }} menyediakan kelas `Button` dengan delapan gaya button siap pakai. Setiap button dilengkapi dengan dukungan bawaan untuk:

- **Status loading async** -- kembalikan `Future` dari `onPressed` dan button secara otomatis menampilkan indikator loading
- **Gaya animasi** -- pilih dari efek clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph, dan shake
- **Gaya splash** -- tambahkan umpan balik sentuh ripple, highlight, glow, atau ink
- **Pengiriman formulir** -- hubungkan button langsung ke instance `NyFormData`

Anda dapat menemukan definisi button aplikasi Anda di `lib/resources/widgets/buttons/buttons.dart`. File ini berisi kelas `Button` dengan method statis untuk setiap tipe button, memudahkan Anda untuk menyesuaikan nilai default proyek Anda.

<div id="basic-usage"></div>

## Penggunaan Dasar

Gunakan kelas `Button` di mana saja dalam widget Anda. Berikut contoh sederhana di dalam sebuah halaman:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Setiap tipe button mengikuti pola yang sama -- berikan label `text` dan callback `onPressed`.

<div id="button-types"></div>

## Tipe Button yang Tersedia

Semua button diakses melalui kelas `Button` menggunakan method statis.

<div id="primary"></div>

### Primary

Button terisi dengan bayangan, menggunakan warna primer tema Anda. Terbaik untuk elemen call-to-action utama.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Button terisi dengan warna permukaan yang lebih lembut dan bayangan halus. Cocok untuk aksi sekunder di samping button primer.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Button transparan dengan garis tepi. Berguna untuk aksi yang kurang menonjol atau button batal.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Anda dapat menyesuaikan warna garis tepi dan teks:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Button minimal tanpa latar belakang atau garis tepi. Ideal untuk aksi inline atau tautan.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Anda dapat menyesuaikan warna teks:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Button terisi yang menampilkan ikon di samping teks. Ikon muncul sebelum teks secara default.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Anda dapat menyesuaikan warna latar belakang:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Button dengan latar belakang gradien linear. Menggunakan warna primer dan tersier tema Anda secara default.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Anda dapat memberikan warna gradien kustom:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Button berbentuk pil dengan sudut yang sepenuhnya membulat. Radius border secara default adalah setengah dari tinggi button.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Anda dapat menyesuaikan warna latar belakang dan radius border:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Button bergaya kaca buram dengan efek blur latar belakang. Bekerja dengan baik saat ditempatkan di atas gambar atau latar belakang berwarna.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Anda dapat menyesuaikan warna teks:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Status Loading Async

Salah satu fitur paling kuat dari button {{ config('app.name') }} adalah **manajemen status loading otomatis**. Ketika callback `onPressed` Anda mengembalikan `Future`, button akan secara otomatis menampilkan indikator loading dan menonaktifkan interaksi hingga operasi selesai.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Selama operasi async berjalan, button akan menampilkan efek skeleton loading (secara default). Setelah `Future` selesai, button kembali ke status normalnya.

Ini bekerja dengan operasi async apa pun -- panggilan API, penulisan database, upload file, atau apa pun yang mengembalikan `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

Tidak perlu mengelola variabel state `isLoading`, memanggil `setState`, atau membungkus apa pun dalam `StatefulWidget` -- {{ config('app.name') }} menangani semuanya untuk Anda.

### Cara Kerjanya

Ketika button mendeteksi bahwa `onPressed` mengembalikan `Future`, ia menggunakan mekanisme `lockRelease` untuk:

1. Menampilkan indikator loading (dikendalikan oleh `LoadingStyle`)
2. Menonaktifkan button untuk mencegah ketukan ganda
3. Menunggu `Future` selesai
4. Mengembalikan button ke status normalnya

<div id="animation-styles"></div>

## Gaya Animasi

Button mendukung animasi tekan melalui `ButtonAnimationStyle`. Animasi ini memberikan umpan balik visual saat pengguna berinteraksi dengan button. Anda dapat mengatur gaya animasi saat menyesuaikan button Anda di `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Efek tekan 3D bergaya Duolingo. Button bergerak ke bawah saat ditekan dan memantul kembali saat dilepas. Terbaik untuk aksi utama dan UX bergaya permainan.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Mengecilkan button saat ditekan dan memantul kembali saat dilepas. Terbaik untuk button tambah-ke-keranjang, suka, dan favorit.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Denyut skala halus yang berkelanjutan saat button ditahan. Terbaik untuk aksi tekan-lama atau menarik perhatian.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Memampatkan button secara horizontal dan memperluasnya secara vertikal saat ditekan. Terbaik untuk UI yang menyenangkan dan interaktif.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Efek deformasi elastis yang bergoyang. Terbaik untuk aplikasi yang menyenangkan, kasual, atau hiburan.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Sorotan mengkilap yang menyapu melintasi button saat ditekan. Terbaik untuk fitur premium atau CTA yang ingin Anda tonjolkan.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Efek riak yang diperbesar yang meluas dari titik sentuh. Terbaik untuk penekanan Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

Radius border button bertambah saat ditekan, menciptakan efek perubahan bentuk. Terbaik untuk umpan balik yang halus dan elegan.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Animasi goyang horizontal. Terbaik untuk status error atau aksi tidak valid -- goyangkan button untuk memberi sinyal bahwa ada yang salah.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Sesuaikan efeknya:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Menonaktifkan Animasi

Untuk menggunakan button tanpa animasi:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Mengubah Animasi Default

Untuk mengubah animasi default untuk tipe button, ubah file `lib/resources/widgets/buttons/buttons.dart` Anda:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Gaya Splash

Efek splash memberikan umpan balik sentuh visual pada button. Konfigurasikan melalui `ButtonSplashStyle`. Gaya splash dapat dikombinasikan dengan gaya animasi untuk umpan balik berlapis.

### Gaya Splash yang Tersedia

| Splash | Factory | Deskripsi |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Riak Material standar dari titik sentuh |
| Highlight | `ButtonSplashStyle.highlight()` | Sorotan halus tanpa animasi riak |
| Glow | `ButtonSplashStyle.glow()` | Cahaya lembut yang memancar dari titik sentuh |
| Ink | `ButtonSplashStyle.ink()` | Percikan tinta cepat, lebih cepat dan lebih responsif |
| None | `ButtonSplashStyle.none()` | Tanpa efek splash |
| Custom | `ButtonSplashStyle.custom()` | Kontrol penuh atas factory splash |

### Contoh

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Anda dapat menyesuaikan warna splash dan opasitas:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Gaya Loading

Indikator loading yang ditampilkan selama operasi async dikendalikan oleh `LoadingStyle`. Anda dapat mengaturnya per tipe button di file buttons Anda.

### Skeletonizer (Default)

Menampilkan efek shimmer skeleton pada button:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Menampilkan widget loading (secara default loader aplikasi):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Menjaga button tetap terlihat tetapi menonaktifkan interaksi selama loading:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Pengiriman Formulir

Semua button mendukung parameter `submitForm`, yang menghubungkan button ke `NyForm`. Saat diketuk, button akan memvalidasi formulir dan memanggil handler sukses Anda dengan data formulir.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

Parameter `submitForm` menerima record dengan dua nilai:
1. Instance `NyFormData` (atau nama formulir sebagai `String`)
2. Callback yang menerima data yang telah divalidasi

Secara default, `showToastError` bernilai `true`, yang menampilkan notifikasi toast saat validasi formulir gagal. Atur ke `false` untuk menangani error secara diam-diam:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Ketika callback `submitForm` mengembalikan `Future`, button akan secara otomatis menampilkan status loading hingga operasi async selesai.

<div id="customizing-buttons"></div>

## Menyesuaikan Button

Semua default button didefinisikan di proyek Anda di `lib/resources/widgets/buttons/buttons.dart`. Setiap tipe button memiliki kelas widget yang sesuai di `lib/resources/widgets/buttons/partials/`.

### Mengubah Gaya Default

Untuk mengubah tampilan default sebuah button, edit kelas `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Menyesuaikan Widget Button

Untuk mengubah tampilan visual dari tipe button, edit widget yang sesuai di `lib/resources/widgets/buttons/partials/`. Misalnya, untuk mengubah radius border atau bayangan button primer:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Membuat Tipe Button Baru

Untuk menambahkan tipe button baru:

1. Buat file widget baru di `lib/resources/widgets/buttons/partials/` yang meng-extend `StatefulAppButton`.
2. Implementasikan method `buildButton`.
3. Tambahkan method statis di kelas `Button`.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Kemudian daftarkan di kelas `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Referensi Parameter

### Parameter Umum (Semua Tipe Button)

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `text` | `String` | wajib | Teks label button |
| `onPressed` | `VoidCallback?` | `null` | Callback saat button diketuk. Kembalikan `Future` untuk status loading otomatis |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Record pengiriman formulir (instance formulir, callback sukses) |
| `onFailure` | `Function(dynamic)?` | `null` | Dipanggil saat validasi formulir gagal |
| `showToastError` | `bool` | `true` | Tampilkan notifikasi toast saat error validasi formulir |
| `width` | `double?` | `null` | Lebar button (default lebar penuh) |

### Parameter Khusus Tipe

#### Button.outlined

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Warna outline tema | Warna garis tepi |
| `textColor` | `Color?` | Warna primer tema | Warna teks |

#### Button.textOnly

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Warna primer tema | Warna teks |

#### Button.icon

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `icon` | `Widget` | wajib | Widget ikon yang ditampilkan |
| `color` | `Color?` | Warna primer tema | Warna latar belakang |

#### Button.gradient

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Warna primer dan tersier | Titik warna gradien |

#### Button.rounded

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Warna primary container tema | Warna latar belakang |
| `borderRadius` | `BorderRadius?` | Bentuk pil (tinggi / 2) | Radius sudut |

#### Button.transparency

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `color` | `Color?` | Adaptif tema | Warna teks |

### Parameter ButtonAnimationStyle

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Bervariasi per tipe | Durasi animasi |
| `curve` | `Curve` | Bervariasi per tipe | Kurva animasi |
| `enableHapticFeedback` | `bool` | Bervariasi per tipe | Memicu umpan balik haptik saat ditekan |
| `translateY` | `double` | `4.0` | Clickable: jarak tekan vertikal |
| `shadowOffset` | `double` | `4.0` | Clickable: kedalaman bayangan |
| `scaleMin` | `double` | `0.92` | Bounce: skala minimum saat ditekan |
| `pulseScale` | `double` | `1.05` | Pulse: skala maksimum selama denyut |
| `squeezeX` | `double` | `0.95` | Squeeze: kompresi horizontal |
| `squeezeY` | `double` | `1.05` | Squeeze: ekspansi vertikal |
| `jellyStrength` | `double` | `0.15` | Jelly: intensitas goyangan |
| `shineColor` | `Color` | `Colors.white` | Shine: warna sorotan |
| `shineWidth` | `double` | `0.3` | Shine: lebar pita kilau |
| `rippleScale` | `double` | `2.0` | Ripple: skala ekspansi |
| `morphRadius` | `double` | `24.0` | Morph: radius border target |
| `shakeOffset` | `double` | `8.0` | Shake: perpindahan horizontal |
| `shakeCount` | `int` | `3` | Shake: jumlah osilasi |

### Parameter ButtonSplashStyle

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Warna surface tema | Warna efek splash |
| `highlightColor` | `Color?` | Warna surface tema | Warna efek highlight |
| `splashOpacity` | `double` | `0.12` | Opasitas splash |
| `highlightOpacity` | `double` | `0.06` | Opasitas highlight |
| `borderRadius` | `BorderRadius?` | `null` | Radius clip splash |
