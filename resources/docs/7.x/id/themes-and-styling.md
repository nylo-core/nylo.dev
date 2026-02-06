# Tema & Styling

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar tema")
- Tema
  - [Tema terang & gelap](#light-and-dark-themes "Tema terang dan gelap")
  - [Membuat tema](#creating-a-theme "Membuat tema")
- Konfigurasi
  - [Warna tema](#theme-colors "Warna tema")
  - [Menggunakan warna](#using-colors "Menggunakan warna")
  - [Style dasar](#base-styles "Style dasar")
  - [Mengganti tema](#switching-theme "Mengganti tema")
  - [Font](#fonts "Font")
  - [Desain](#design "Desain")
- [Ekstensi Text](#text-extensions "Ekstensi text")


<div id="introduction"></div>

## Pengantar

Anda dapat mengelola style UI aplikasi Anda menggunakan tema. Tema memungkinkan kita mengubah misalnya ukuran font teks, tampilan tombol, dan tampilan umum aplikasi kita.

Jika Anda baru mengenal tema, contoh di situs web Flutter akan membantu Anda memulai <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">di sini</a>.

Secara langsung, {{ config('app.name') }} menyertakan tema yang sudah dikonfigurasi untuk `Mode Terang` dan `Mode Gelap`.

Tema juga akan diperbarui jika perangkat memasuki mode <b>'terang/gelap'</b>.

<div id="light-and-dark-themes"></div>

## Tema terang & gelap

- Tema terang - `lib/resources/themes/light_theme.dart`
- Tema gelap - `lib/resources/themes/dark_theme.dart`

Di dalam file-file ini, Anda akan menemukan ThemeData dan ThemeStyle yang sudah didefinisikan.



<div id="creating-a-theme"></div>

## Membuat tema

Jika Anda ingin memiliki beberapa tema untuk aplikasi Anda, kami memiliki cara mudah untuk melakukannya. Jika Anda baru mengenal tema, ikuti langkah berikut.

Pertama, jalankan perintah di bawah ini dari terminal

``` bash
metro make:theme bright_theme
```

<b>Catatan:</b> ganti **bright_theme** dengan nama tema baru Anda.

Ini membuat tema baru di direktori `/resources/themes/` Anda dan juga file warna tema di `/resources/themes/styles/`.

``` dart
// App Themes
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

Anda dapat memodifikasi warna untuk tema baru Anda di file **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Warna Tema

Untuk mengelola warna tema di proyek Anda, lihat direktori `lib/resources/themes/styles`.
Direktori ini berisi style warna untuk light_theme_colors.dart dan dark_theme_colors.dart.

Di file ini, Anda seharusnya memiliki sesuatu yang mirip dengan di bawah ini.

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // app bar
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // bottom tab bar
  @override
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // toast notification
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## Menggunakan warna di widget

``` dart
import 'package:flutter_app/config/theme.dart';
...

// gets the light/dark background colour depending on the theme
ThemeColor.get(context).background

// e.g. of using the "ThemeColor" class
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// or

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>

## Style dasar

Style dasar memungkinkan Anda menyesuaikan berbagai warna widget dari satu area di kode Anda.

{{ config('app.name') }} disertai dengan style dasar yang sudah dikonfigurasi untuk proyek Anda yang terletak di `lib/resources/themes/styles/color_styles.dart`.

Style ini menyediakan antarmuka untuk warna tema Anda di `light_theme_colors.dart` dan `dart_theme_colors.dart`.

<br>

File `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // general
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // app bar
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // bottom tab bar
  @override
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // toast notification
  Color get toastNotificationBackground;
}
```

Anda dapat menambahkan style tambahan di sini dan kemudian mengimplementasikan warna di tema Anda.

<div id="switching-theme"></div>

## Mengganti tema

{{ config('app.name') }} mendukung kemampuan untuk mengganti tema secara langsung.

Misalnya, Jika Anda perlu mengganti tema jika pengguna mengetuk tombol untuk mengaktifkan "tema gelap".

Anda dapat mendukung itu dengan melakukan hal berikut:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
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

Memperbarui font utama di seluruh aplikasi mudah dilakukan di {{ config('app.name') }}. Buka file `lib/config/design.dart` dan perbarui yang di bawah ini.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Kami menyertakan library <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> di repositori, sehingga Anda dapat mulai menggunakan semua font dengan sedikit usaha.
Untuk memperbarui font ke yang lain, Anda dapat melakukan hal berikut:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Lihat font di library <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> resmi untuk memahami lebih lanjut

Perlu menggunakan font kustom? Lihat panduan ini - https://flutter.dev/docs/cookbook/design/fonts

Setelah Anda menambahkan font Anda, ubah variabel seperti contoh di bawah ini.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Desain

File **config/design.dart** digunakan untuk mengelola elemen desain untuk aplikasi Anda.

Variabel `appFont` berisi font untuk aplikasi Anda.

Variabel `logo` digunakan untuk menampilkan Logo aplikasi Anda.

Anda dapat memodifikasi **resources/widgets/logo_widget.dart** untuk menyesuaikan cara Anda ingin menampilkan Logo Anda.

Variabel `loader` digunakan untuk menampilkan loader. {{ config('app.name') }} akan menggunakan variabel ini di beberapa metode helper sebagai widget loader default.

Anda dapat memodifikasi **resources/widgets/loader_widget.dart** untuk menyesuaikan cara Anda ingin menampilkan Loader Anda.

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
