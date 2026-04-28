# Tema & Styling

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Tema
  - [Tema terang & gelap](#light-and-dark-themes "Tema terang & gelap")
  - [Membuat tema](#creating-a-theme "Membuat tema")
- Konfigurasi
  - [Warna tema](#theme-colors "Warna tema")
  - [Menggunakan warna](#using-colors "Menggunakan warna")
  - [Style dasar](#base-styles "Style dasar")
  - [Memperluas style warna](#extending-color-styles "Memperluas style warna")
  - [Mengganti tema](#switching-theme "Mengganti tema")
  - [Font](#fonts "Font")
  - [Desain](#design "Desain")
- [Ekstensi Text](#text-extensions "Ekstensi Text")


<div id="introduction"></div>

## Pengantar

Anda dapat mengelola style UI aplikasi Anda menggunakan tema. Tema memungkinkan kita mengubah misalnya ukuran font teks, tampilan tombol, dan tampilan umum aplikasi kita.

Jika Anda baru mengenal tema, contoh di situs web Flutter akan membantu Anda memulai <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">di sini</a>.

Secara langsung, {{ config('app.name') }} menyertakan tema yang sudah dikonfigurasi untuk `Mode Terang` dan `Mode Gelap`.

Tema juga akan diperbarui jika perangkat memasuki mode <b>'terang/gelap'</b>.

<div id="light-and-dark-themes"></div>

## Tema terang & gelap

Setiap tema berada di subdirektori miliknya sendiri di bawah `lib/resources/themes/`:

- Tema terang – `lib/resources/themes/light/light_theme.dart`
- Warna terang – `lib/resources/themes/light/light_theme_colors.dart`
- Tema gelap – `lib/resources/themes/dark/dark_theme.dart`
- Warna gelap – `lib/resources/themes/dark/dark_theme_colors.dart`

Kedua tema berbagi builder bersama di `lib/resources/themes/base_theme.dart` dan interface `ColorStyles` di `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Membuat tema

Jika Anda ingin memiliki beberapa tema untuk aplikasi Anda, kami memiliki cara mudah untuk melakukannya. Jika Anda baru mengenal tema, ikuti langkah berikut.

Pertama, jalankan perintah di bawah ini dari terminal

``` bash
dart run nylo_framework:main make:theme bright_theme
```

<b>Catatan:</b> ganti **bright_theme** dengan nama tema baru Anda.

Ini membuat direktori tema baru di `lib/resources/themes/bright/` yang berisi `bright_theme.dart` dan `bright_theme_colors.dart`, kemudian mendaftarkannya di `lib/bootstrap/theme.dart`.

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

Anda dapat memodifikasi warna untuk tema baru Anda di file **lib/resources/themes/bright/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Warna Tema

Untuk mengelola warna tema di proyek Anda, lihat direktori `lib/resources/themes/light/` dan `lib/resources/themes/dark/`. Masing-masing berisi file warna untuk temanya — `light_theme_colors.dart` dan `dark_theme_colors.dart`.

Nilai warna diorganisir ke dalam grup (`general`, `appBar`, `bottomTabBar`) yang didefinisikan oleh framework. Kelas warna tema Anda memperluas `ColorStyles` dan menyediakan instance dari setiap grup:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Warna untuk penggunaan umum.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Warna untuk app bar.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Warna untuk bottom tab bar.
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## Menggunakan warna di widget

Gunakan helper `nyColorStyle<T>(context)` untuk membaca warna tema yang aktif. Berikan tipe `ColorStyles` proyek Anda agar pemanggilan sepenuhnya bertipe:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// di dalam widget build:
final colors = nyColorStyle<ColorStyles>(context);

// warna latar belakang tema yang aktif
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Baca warna dari tema tertentu (tanpa memperhatikan tema yang aktif):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Style dasar

Style dasar memungkinkan Anda mendeskripsikan setiap tema melalui satu interface. {{ config('app.name') }} dilengkapi dengan `lib/resources/themes/color_styles.dart`, yang merupakan kontrak yang diimplementasikan oleh `light_theme_colors.dart` dan `dark_theme_colors.dart`.

`ColorStyles` memperluas `ThemeColor` dari framework, yang mengekspos tiga grup warna yang sudah didefinisikan: `GeneralColors`, `AppBarColors`, dan `BottomTabBarColors`. Builder tema dasar (`lib/resources/themes/base_theme.dart`) membaca grup-grup ini saat membangun `ThemeData`, sehingga apa pun yang Anda masukkan ke dalamnya secara otomatis terhubung ke widget yang sesuai.

<br>

File `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Warna untuk penggunaan umum.
  @override
  GeneralColors get general;

  /// Warna untuk app bar.
  @override
  AppBarColors get appBar;

  /// Warna untuk bottom tab bar.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Tiga grup mengekspos field berikut:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Untuk menambahkan field di luar default ini — tombol, ikon, lencana, dll. milik Anda — lihat [Memperluas style warna](#extending-color-styles).

<div id="extending-color-styles"></div>

## Memperluas style warna

<!-- uncertain: new section "Extending color styles" — not present in existing id locale file -->
Tiga grup default (`general`, `appBar`, `bottomTabBar`) adalah titik awal, bukan batas keras. `lib/resources/themes/color_styles.dart` adalah milik Anda untuk dimodifikasi — tambahkan grup warna baru (atau field tunggal) di atas default, lalu implementasikan di kelas warna setiap tema.

**1. Definisikan grup warna kustom**

Kelompokkan warna terkait ke dalam kelas kecil yang tidak dapat diubah:

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. Tambahkan ke `ColorStyles`**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // Grup kustom
  IconColors get icons;
}
```

**3. Implementasikan di kelas warna setiap tema**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...override yang sudah ada...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Ulangi override `icons` yang sama di `dark_theme_colors.dart` dengan nilai dark-mode.

**4. Gunakan di widget Anda**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Mengganti tema

{{ config('app.name') }} mendukung kemampuan untuk mengganti tema secara langsung.

Misalnya, Jika Anda perlu mengganti tema jika pengguna mengetuk tombol untuk mengaktifkan "tema gelap".

Anda dapat mendukung itu dengan melakukan hal berikut:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Font

Memperbarui font utama di seluruh aplikasi mudah dilakukan di {{ config('app.name') }}. Buka file `lib/config/design.dart` dan perbarui `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Kami menyertakan library <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> di repositori, sehingga Anda dapat mulai menggunakan semua font dengan sedikit usaha. Untuk beralih ke Google Font yang berbeda, cukup ubah pemanggilan:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Lihat font di library <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> resmi untuk memahami lebih lanjut.

Perlu menggunakan font kustom? Lihat panduan ini - https://flutter.dev/docs/cookbook/design/fonts

Setelah Anda menambahkan font Anda, ubah variabel seperti contoh di bawah ini.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Desain

File **lib/config/design.dart** digunakan untuk mengelola elemen desain untuk aplikasi Anda. Semuanya diekspos melalui kelas `DesignConfig`:

`DesignConfig.appFont` berisi font untuk aplikasi Anda.

`DesignConfig.logo` digunakan untuk menampilkan Logo aplikasi Anda.

Anda dapat memodifikasi **lib/resources/widgets/logo_widget.dart** untuk menyesuaikan cara Anda ingin menampilkan Logo Anda.

`DesignConfig.loader` digunakan untuk menampilkan loader. {{ config('app.name') }} akan menggunakan variabel ini di beberapa metode helper sebagai widget loader default.

Anda dapat memodifikasi **lib/resources/widgets/loader_widget.dart** untuk menyesuaikan cara Anda ingin menampilkan Loader Anda.

<div id="text-extensions"></div>

## Ekstensi Text

Berikut adalah ekstensi text yang tersedia yang dapat Anda gunakan di {{ config('app.name') }}.

| Nama Aturan   | Penggunaan | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Menerapkan textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Menerapkan textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Menerapkan textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Menerapkan textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Menerapkan textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Menerapkan textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Menerapkan textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Menerapkan textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Menerapkan textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Menerapkan textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Menerapkan textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Menerapkan textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Menerapkan textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Menerapkan textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Menerapkan textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Menerapkan font weight bold ke widget Text |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Menerapkan font weight light ke widget Text |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Mengatur warna teks yang berbeda pada widget Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Menyejajarkan font ke kiri |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Menyejajarkan font ke kanan |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Menyejajarkan font ke tengah |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Mengatur baris maksimum untuk widget text |

<br>


<div id="text-extension-display-large"></div>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
