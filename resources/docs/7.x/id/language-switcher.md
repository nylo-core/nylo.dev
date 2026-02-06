# LanguageSwitcher

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Penggunaan
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Modal Bottom Sheet](#usage-bottom-modal "Modal Bottom Sheet")
- [Builder Dropdown Kustom](#custom-builder "Builder Dropdown Kustom")
- [Parameter](#parameters "Parameter")
- [Method Statis](#methods "Method Statis")


<div id="introduction"></div>

## Pengantar

Widget **LanguageSwitcher** menyediakan cara mudah untuk menangani perpindahan bahasa di proyek {{ config('app.name') }} Anda. Widget ini secara otomatis mendeteksi bahasa yang tersedia di direktori `/lang` Anda dan menampilkannya kepada pengguna.

**Apa yang dilakukan LanguageSwitcher?**

- Menampilkan bahasa yang tersedia dari direktori `/lang` Anda
- Mengganti bahasa aplikasi saat pengguna memilih salah satu
- Menyimpan bahasa yang dipilih agar bertahan saat aplikasi dimulai ulang
- Secara otomatis memperbarui UI saat bahasa berubah

> **Catatan**: Jika aplikasi Anda belum dilokalkan, pelajari cara melakukannya di dokumentasi [Lokalisasi](/docs/7.x/localization) sebelum menggunakan widget ini.

<div id="usage-dropdown"></div>

## Widget Dropdown

Cara paling sederhana untuk menggunakan `LanguageSwitcher` adalah sebagai dropdown di app bar Anda:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Saat pengguna mengetuk dropdown, mereka akan melihat daftar bahasa yang tersedia. Setelah memilih bahasa, aplikasi akan secara otomatis beralih dan memperbarui UI.

<div id="usage-bottom-modal"></div>

## Modal Bottom Sheet

Anda juga dapat menampilkan bahasa dalam modal bottom sheet:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

Modal bottom menampilkan daftar bahasa dengan tanda centang di sebelah bahasa yang sedang dipilih.

### Menyesuaikan Tinggi Modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## Builder Dropdown Kustom

Sesuaikan tampilan setiap opsi bahasa di dropdown:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### Menangani Perubahan Bahasa

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## Parameter

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `icon` | `Widget?` | - | Ikon kustom untuk tombol dropdown |
| `iconEnabledColor` | `Color?` | - | Warna ikon dropdown |
| `iconSize` | `double` | `24` | Ukuran ikon dropdown |
| `dropdownBgColor` | `Color?` | - | Warna latar belakang menu dropdown |
| `hint` | `Widget?` | - | Widget hint saat tidak ada bahasa yang dipilih |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Tinggi setiap item dropdown |
| `elevation` | `int` | `8` | Elevasi menu dropdown |
| `padding` | `EdgeInsetsGeometry?` | - | Padding di sekitar dropdown |
| `borderRadius` | `BorderRadius?` | - | Radius border menu dropdown |
| `textStyle` | `TextStyle?` | - | Gaya teks untuk item dropdown |
| `langPath` | `String` | `'lang'` | Path ke file bahasa di assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Builder kustom untuk item dropdown |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Alignment item dropdown |
| `dropdownOnTap` | `Function()?` | - | Callback saat item dropdown diketuk |
| `onTap` | `Function()?` | - | Callback saat tombol dropdown diketuk |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback saat bahasa diubah |

<div id="methods"></div>

## Method Statis

### Mendapatkan Bahasa Saat Ini

Ambil bahasa yang sedang dipilih:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Menyimpan Bahasa

Simpan preferensi bahasa secara manual:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Menghapus Bahasa

Hapus preferensi bahasa yang tersimpan:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Mendapatkan Data Bahasa

Dapatkan informasi bahasa dari kode locale:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Mendapatkan Daftar Bahasa

Dapatkan semua bahasa yang tersedia dari direktori `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Menampilkan Modal Bottom

Tampilkan modal pemilihan bahasa:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Locale yang Didukung

Widget `LanguageSwitcher` mendukung ratusan kode locale dengan nama yang mudah dibaca. Beberapa contoh:

| Kode Locale | Nama Bahasa |
|-------------|-------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

Daftar lengkapnya mencakup varian regional untuk sebagian besar bahasa.
