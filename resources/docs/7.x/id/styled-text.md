# Styled Text

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Mode Children](#children-mode "Mode Children")
- [Mode Template](#template-mode "Mode Template")
  - [Styling Placeholder](#styling-placeholders "Styling Placeholder")
  - [Callback Tap](#tap-callbacks "Callback Tap")
  - [Key Dipisahkan Pipe](#pipe-keys "Key Dipisahkan Pipe")
- [Parameter](#parameters "Parameter")
- [Extension Text](#text-extensions "Extension Text")
  - [Gaya Tipografi](#typography-styles "Gaya Tipografi")
  - [Metode Utilitas](#utility-methods "Metode Utilitas")
- [Contoh](#examples "Contoh Praktis")

<div id="introduction"></div>

## Pengantar

**StyledText** adalah widget untuk menampilkan rich text dengan gaya campuran, callback tap, dan event pointer. Widget ini dirender sebagai widget `RichText` dengan beberapa child `TextSpan`, memberi Anda kontrol detail atas setiap segmen teks.

StyledText mendukung dua mode:

1. **Mode Children** -- meneruskan daftar widget `Text`, masing-masing dengan gaya sendiri
2. **Mode Template** -- menggunakan sintaks `@{{placeholder}}` dalam string dan memetakan placeholder ke gaya dan aksi

<div id="basic-usage"></div>

## Penggunaan Dasar

``` dart
// Children mode - list of Text widgets
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template mode - placeholder syntax
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Mode Children

Teruskan daftar widget `Text` untuk menyusun styled text:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

`style` dasar diterapkan ke child apa pun yang tidak memiliki gaya sendiri.

### Event Pointer

Mendeteksi ketika pointer masuk atau keluar dari segmen teks:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Mode Template

Gunakan `StyledText.template()` dengan sintaks `@{{placeholder}}`:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

Teks di antara `@{{ }}` berfungsi sebagai **teks tampilan** sekaligus **key** yang digunakan untuk mencari gaya dan callback tap.

<div id="styling-placeholders"></div>

### Styling Placeholder

Petakan nama placeholder ke objek `TextStyle`:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### Callback Tap

Petakan nama placeholder ke handler tap:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### Key Dipisahkan Pipe

Terapkan gaya atau callback yang sama ke beberapa placeholder menggunakan key yang dipisahkan pipe `|`:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

Ini memetakan gaya dan callback yang sama ke ketiga placeholder.

<div id="parameters"></div>

## Parameter

### StyledText (Mode Children)

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | required | Daftar widget Text |
| `style` | `TextStyle?` | null | Gaya dasar untuk semua children |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Callback pointer masuk |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Callback pointer keluar |
| `spellOut` | `bool?` | null | Mengeja teks karakter per karakter |
| `softWrap` | `bool` | `true` | Mengaktifkan soft wrapping |
| `textAlign` | `TextAlign` | `TextAlign.start` | Perataan teks |
| `textDirection` | `TextDirection?` | null | Arah teks |
| `maxLines` | `int?` | null | Jumlah baris maksimum |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Perilaku overflow |
| `locale` | `Locale?` | null | Locale teks |
| `strutStyle` | `StrutStyle?` | null | Strut style |
| `textScaler` | `TextScaler?` | null | Text scaler |
| `selectionColor` | `Color?` | null | Warna sorotan seleksi |

### StyledText.template (Mode Template)

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `text` | `String` | required | Teks template dengan sintaks `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | Peta nama placeholder ke gaya |
| `onTap` | `Map<String, VoidCallback>?` | null | Peta nama placeholder ke callback tap |
| `style` | `TextStyle?` | null | Gaya dasar untuk teks non-placeholder |

Semua parameter lainnya (`softWrap`, `textAlign`, `maxLines`, dll.) sama dengan konstruktor children.

<div id="text-extensions"></div>

## Extension Text

{{ config('app.name') }} memperluas widget `Text` Flutter dengan metode tipografi dan utilitas.

<div id="typography-styles"></div>

### Gaya Tipografi

Terapkan gaya tipografi Material Design ke widget `Text` apa pun:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

Masing-masing menerima override opsional:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Override yang tersedia** (sama untuk semua metode tipografi):

| Parameter | Tipe | Deskripsi |
|-----------|------|-------------|
| `color` | `Color?` | Warna teks |
| `fontSize` | `double?` | Ukuran font |
| `fontWeight` | `FontWeight?` | Ketebalan font |
| `fontStyle` | `FontStyle?` | Italic/normal |
| `letterSpacing` | `double?` | Jarak antar huruf |
| `wordSpacing` | `double?` | Jarak antar kata |
| `height` | `double?` | Tinggi baris |
| `decoration` | `TextDecoration?` | Dekorasi teks |
| `decorationColor` | `Color?` | Warna dekorasi |
| `decorationStyle` | `TextDecorationStyle?` | Gaya dekorasi |
| `decorationThickness` | `double?` | Ketebalan dekorasi |
| `fontFamily` | `String?` | Keluarga font |
| `shadows` | `List<Shadow>?` | Bayangan teks |
| `overflow` | `TextOverflow?` | Perilaku overflow |

<div id="utility-methods"></div>

### Metode Utilitas

``` dart
// Font weight
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alignment
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Max lines
Text("Long text...").setMaxLines(2)

// Font family
Text("Custom font").setFontFamily("Roboto")

// Font size
Text("Big text").setFontSize(24)

// Custom style
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copy with modifications
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Contoh

### Tautan Syarat dan Ketentuan

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### Tampilan Versi

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### Paragraf dengan Gaya Campuran

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### Rangkaian Tipografi

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
