# InputField

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Validasi](#validation "Validasi")
- Varian
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Masking Input](#masking "Masking Input")
- [Header dan Footer](#header-footer "Header dan Footer")
- [Input yang Dapat Dihapus](#clearable "Input yang Dapat Dihapus")
- [Manajemen State](#state-management "Manajemen State")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Pengantar

Widget **InputField** adalah field teks yang disempurnakan dari {{ config('app.name') }} dengan dukungan bawaan untuk:

- Validasi dengan pesan error yang dapat dikustomisasi
- Toggle visibilitas password
- Masking input (nomor telepon, kartu kredit, dll.)
- Widget header dan footer
- Input yang dapat dihapus
- Integrasi manajemen state
- Data dummy untuk pengembangan

<div id="basic-usage"></div>

## Penggunaan Dasar

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## Validasi

Gunakan parameter `formValidator` untuk menambahkan aturan validasi:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Field akan divalidasi saat pengguna memindahkan fokus dari field tersebut.

### Handler Validasi Kustom

Tangani error validasi secara programatis:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

Lihat semua aturan validasi yang tersedia di dokumentasi [Validasi](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Field password yang sudah dikonfigurasi dengan teks tersembunyi dan toggle visibilitas:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Menyesuaikan Visibilitas Password

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Field email yang sudah dikonfigurasi dengan keyboard email dan autofocus:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Secara otomatis mengkapitalkan huruf pertama setiap kata:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Masking Input

Terapkan mask input untuk data berformat seperti nomor telepon atau kartu kredit:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Parameter | Deskripsi |
|-----------|-----------|
| `mask` | Pola mask menggunakan `#` sebagai placeholder |
| `maskMatch` | Pola regex untuk karakter input yang valid |
| `maskedReturnValue` | Jika true, mengembalikan nilai berformat; jika false, mengembalikan input mentah |

<div id="header-footer"></div>

## Header dan Footer

Tambahkan widget di atas atau di bawah field input:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## Input yang Dapat Dihapus

Tambahkan tombol hapus untuk mengosongkan field dengan cepat:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## Manajemen State

Berikan nama state pada field input Anda untuk mengontrolnya secara programatis:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Aksi State

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Parameter

### Parameter Umum

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `controller` | `TextEditingController` | wajib | Mengontrol teks yang sedang diedit |
| `labelText` | `String?` | - | Label yang ditampilkan di atas field |
| `hintText` | `String?` | - | Teks placeholder |
| `formValidator` | `FormValidator?` | - | Aturan validasi |
| `validateOnFocusChange` | `bool` | `true` | Validasi saat fokus berubah |
| `obscureText` | `bool` | `false` | Sembunyikan input (untuk password) |
| `keyboardType` | `TextInputType` | `text` | Tipe keyboard |
| `autoFocus` | `bool` | `false` | Auto-fokus saat build |
| `readOnly` | `bool` | `false` | Jadikan field hanya-baca |
| `enabled` | `bool?` | - | Aktifkan/nonaktifkan field |
| `maxLines` | `int?` | `1` | Jumlah baris maksimum |
| `maxLength` | `int?` | - | Jumlah karakter maksimum |

### Parameter Styling

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `backgroundColor` | `Color?` | Warna latar belakang field |
| `borderRadius` | `BorderRadius?` | Radius border |
| `border` | `InputBorder?` | Border default |
| `focusedBorder` | `InputBorder?` | Border saat fokus |
| `enabledBorder` | `InputBorder?` | Border saat aktif |
| `contentPadding` | `EdgeInsetsGeometry?` | Padding internal |
| `style` | `TextStyle?` | Gaya teks |
| `labelStyle` | `TextStyle?` | Gaya teks label |
| `hintStyle` | `TextStyle?` | Gaya teks hint |
| `prefixIcon` | `Widget?` | Ikon sebelum input |

### Parameter Masking

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `mask` | `String?` | Pola mask (contoh: "###-####") |
| `maskMatch` | `String?` | Regex untuk karakter yang valid |
| `maskedReturnValue` | `bool?` | Mengembalikan nilai dengan mask atau mentah |

### Parameter Fitur

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `header` | `Widget?` | Widget di atas field |
| `footer` | `Widget?` | Widget di bawah field |
| `clearable` | `bool?` | Tampilkan tombol hapus |
| `clearIcon` | `Widget?` | Ikon hapus kustom |
| `passwordVisible` | `bool?` | Tampilkan toggle password |
| `passwordViewable` | `bool?` | Izinkan toggle visibilitas password |
| `dummyData` | `String?` | Data palsu untuk pengembangan |
| `stateName` | `String?` | Nama untuk manajemen state |
| `onChanged` | `Function(String)?` | Dipanggil saat nilai berubah |
