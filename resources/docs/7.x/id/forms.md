# Form

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Memulai
  - [Membuat Form](#creating-forms "Membuat Form")
  - [Menampilkan Form](#displaying-a-form "Menampilkan Form")
  - [Mengirim Form](#submitting-a-form "Mengirim Form")
- Tipe Field
  - [Field Teks](#text-fields "Field Teks")
  - [Field Angka](#number-fields "Field Angka")
  - [Field Password](#password-fields "Field Password")
  - [Field Email](#email-fields "Field Email")
  - [Field URL](#url-fields "Field URL")
  - [Field Area Teks](#text-area-fields "Field Area Teks")
  - [Field Nomor Telepon](#phone-number-fields "Field Nomor Telepon")
  - [Kapitalisasi Kata](#capitalize-words-fields "Kapitalisasi Kata")
  - [Kapitalisasi Kalimat](#capitalize-sentences-fields "Kapitalisasi Kalimat")
  - [Field Tanggal](#date-fields "Field Tanggal")
  - [Field Tanggal Waktu](#datetime-fields "Field Tanggal Waktu")
  - [Field Input Masked](#masked-input-fields "Field Input Masked")
  - [Field Mata Uang](#currency-fields "Field Mata Uang")
  - [Field Checkbox](#checkbox-fields "Field Checkbox")
  - [Field Switch Box](#switch-box-fields "Field Switch Box")
  - [Field Picker](#picker-fields "Field Picker")
  - [Field Radio](#radio-fields "Field Radio")
  - [Field Chip](#chip-fields "Field Chip")
  - [Field Slider](#slider-fields "Field Slider")
  - [Field Range Slider](#range-slider-fields "Field Range Slider")
  - [Field Kustom](#custom-fields "Field Kustom")
  - [Field Builder](#builder-fields "Field Builder")
  - [Field Widget](#widget-fields "Field Widget")
- [FormCollection](#form-collection "FormCollection")
- [Validasi Form](#form-validation "Validasi Form")
- [Mengelola Data Form](#managing-form-data "Mengelola Data Form")
  - [Data Awal](#initial-data "Data Awal")
  - [Mengatur Nilai Field](#setting-field-values "Mengatur Nilai Field")
  - [Mengatur Opsi Field](#setting-field-options "Mengatur Opsi Field")
  - [Membaca Data Form](#reading-form-data "Membaca Data Form")
  - [Menghapus Data](#clearing-data "Menghapus Data")
  - [Memperbarui Field](#finding-and-updating-fields "Memperbarui Field")
- [Tombol Submit](#submit-button "Tombol Submit")
- [Tata Letak Form](#form-layout "Tata Letak Form")
- [Visibilitas Field](#field-visibility "Visibilitas Field")
- [Styling Field](#field-styling "Styling Field")
- [Method Statis NyFormWidget](#ny-form-widget-static-methods "Method Statis NyFormWidget")
- [Referensi Konstruktor NyFormWidget](#ny-form-widget-constructor-reference "Referensi Konstruktor NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Referensi Semua Tipe Field](#all-field-types-reference "Referensi Semua Tipe Field")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan sistem form yang dibangun di sekitar `NyFormWidget`. Kelas form Anda meng-extend `NyFormWidget` dan **merupakan** widget itu sendiri -- tidak perlu wrapper terpisah. Form mendukung validasi bawaan, banyak tipe field, styling, dan manajemen data.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Definisikan sebuah form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Tampilkan dan kirim
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Membuat Form

Gunakan Metro CLI untuk membuat form baru:

``` bash
metro make:form LoginForm
```

Ini membuat `lib/app/forms/login_form.dart`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

Form meng-extend `NyFormWidget` dan meng-override method `fields()` untuk mendefinisikan field form. Setiap field menggunakan konstruktor bernama seperti `Field.text()`, `Field.email()`, atau `Field.password()`. Getter `static NyFormActions get actions` menyediakan cara mudah untuk berinteraksi dengan form dari mana saja di aplikasi Anda.


<div id="displaying-a-form"></div>

## Menampilkan Form

Karena kelas form Anda meng-extend `NyFormWidget`, ia **merupakan** widget itu sendiri. Gunakan langsung di widget tree Anda:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## Mengirim Form

Ada tiga cara untuk mengirim form:

### Menggunakan onSubmit dan submitButton

Kirim `onSubmit` dan `submitButton` saat membuat form. {{ config('app.name') }} menyediakan tombol bawaan yang berfungsi sebagai tombol submit:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Gaya tombol yang tersedia: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Menggunakan NyFormActions

Gunakan getter `actions` untuk mengirim dari mana saja:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### Menggunakan method statis NyFormWidget.submit()

Kirim form berdasarkan namanya dari mana saja:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

Saat dikirim, form memvalidasi semua field. Jika valid, `onSuccess` dipanggil dengan `Map<String, dynamic>` data field (kunci adalah versi snake_case dari nama field). Jika tidak valid, error toast ditampilkan secara default dan `onFailure` dipanggil jika disediakan.


<div id="field-types"></div>

## Tipe Field

{{ config('app.name') }} v7 menyediakan 22 tipe field melalui konstruktor bernama pada kelas `Field`. Semua konstruktor field berbagi parameter umum ini:

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `key` | `String` | Wajib | Identifier field (posisional) |
| `label` | `String?` | `null` | Label tampilan kustom (default ke key dalam title case) |
| `value` | `dynamic` | `null` | Nilai awal |
| `validator` | `FormValidator?` | `null` | Aturan validasi |
| `autofocus` | `bool` | `false` | Auto-fokus saat dimuat |
| `dummyData` | `String?` | `null` | Data tes/pengembangan |
| `header` | `Widget?` | `null` | Widget ditampilkan di atas field |
| `footer` | `Widget?` | `null` | Widget ditampilkan di bawah field |
| `titleStyle` | `TextStyle?` | `null` | Gaya teks label kustom |
| `hidden` | `bool` | `false` | Sembunyikan field |
| `readOnly` | `bool?` | `null` | Buat field hanya-baca |
| `style` | `FieldStyle?` | Bervariasi | Konfigurasi gaya khusus field |
| `onChanged` | `Function(dynamic)?` | `null` | Callback perubahan nilai |

<div id="text-fields"></div>

### Field Teks

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Tipe gaya: `FieldStyleTextField`

<div id="number-fields"></div>

### Field Angka

``` dart
Field.number("Age")

// Angka desimal
Field.number("Score", decimal: true)
```

Parameter `decimal` mengontrol apakah input desimal diizinkan. Tipe gaya: `FieldStyleTextField`

<div id="password-fields"></div>

### Field Password

``` dart
Field.password("Password")

// Dengan toggle visibilitas
Field.password("Password", viewable: true)
```

Parameter `viewable` menambahkan toggle tampilkan/sembunyikan. Tipe gaya: `FieldStyleTextField`

<div id="email-fields"></div>

### Field Email

``` dart
Field.email("Email", validator: FormValidator.email())
```

Secara otomatis mengatur tipe keyboard email dan memfilter spasi. Tipe gaya: `FieldStyleTextField`

<div id="url-fields"></div>

### Field URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Mengatur tipe keyboard URL. Tipe gaya: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Field Area Teks

``` dart
Field.textArea("Description")
```

Input teks multi-baris. Tipe gaya: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Field Nomor Telepon

``` dart
Field.phoneNumber("Mobile Phone")
```

Secara otomatis memformat input nomor telepon. Tipe gaya: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Kapitalisasi Kata

``` dart
Field.capitalizeWords("Full Name")
```

Mengkapitalkan huruf pertama setiap kata. Tipe gaya: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Kapitalisasi Kalimat

``` dart
Field.capitalizeSentences("Bio")
```

Mengkapitalkan huruf pertama setiap kalimat. Tipe gaya: `FieldStyleTextField`

<div id="date-fields"></div>

### Field Tanggal

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Nonaktifkan tombol hapus
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Ikon hapus kustom
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Membuka date picker. Secara default, field menampilkan tombol hapus yang memungkinkan pengguna mereset nilai. Atur `canClear: false` untuk menyembunyikannya, atau gunakan `clearIconData` untuk mengubah ikon. Tipe gaya: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Field Tanggal Waktu

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Membuka picker tanggal dan waktu. Anda dapat mengatur `firstDate`, `lastDate`, `dateFormat`, dan `initialPickerDateTime` langsung sebagai parameter tingkat atas. Tipe gaya: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Field Input Masked

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Mengembalikan nilai yang diformat
)
```

Karakter `#` dalam mask digantikan oleh input pengguna. Gunakan `match` untuk mengontrol karakter yang diizinkan. Ketika `maskReturnValue` adalah `true`, nilai yang dikembalikan mencakup format mask.

<div id="currency-fields"></div>

### Field Mata Uang

``` dart
Field.currency("Price", currency: "usd")
```

Parameter `currency` wajib dan menentukan format mata uang. Tipe gaya: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Field Checkbox

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Tipe gaya: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Field Switch Box

``` dart
Field.switchBox("Enable Notifications")
```

Tipe gaya: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Field Picker

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// Dengan pasangan key-value
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

Parameter `options` memerlukan `FormCollection` (bukan list mentah). Lihat [FormCollection](#form-collection) untuk detail. Tipe gaya: `FieldStylePicker`

#### Gaya List Tile

Anda dapat menyesuaikan tampilan item di bottom sheet picker menggunakan `PickerListTileStyle`. Secara default, bottom sheet menampilkan tile teks polos. Gunakan preset bawaan untuk menambahkan indikator pemilihan, atau sediakan builder kustom sepenuhnya.

**Gaya radio** — menampilkan ikon tombol radio sebagai widget utama:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// Dengan warna aktif kustom
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Gaya centang** — menampilkan ikon centang sebagai widget trailing saat dipilih:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Builder kustom** — kontrol penuh atas widget setiap tile:

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

Kedua gaya preset juga mendukung `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor`, dan `selectedTileColor`:

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### Field Radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Parameter `options` memerlukan `FormCollection`. Tipe gaya: `FieldStyleRadio`

<div id="chip-fields"></div>

### Field Chip

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// Dengan pasangan key-value
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Memungkinkan multi-seleksi melalui widget chip. Parameter `options` memerlukan `FormCollection`. Tipe gaya: `FieldStyleChip`

<div id="slider-fields"></div>

### Field Slider

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Tipe gaya: `FieldStyleSlider` -- konfigurasikan `min`, `max`, `divisions`, warna, tampilan nilai, dan lainnya.

<div id="range-slider-fields"></div>

### Field Range Slider

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Mengembalikan objek `RangeValues`. Tipe gaya: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Field Kustom

Gunakan `Field.custom()` untuk menyediakan widget stateful Anda sendiri:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Parameter `child` memerlukan widget yang meng-extend `NyFieldStatefulWidget`. Ini memberi Anda kontrol penuh atas rendering dan perilaku field.

<div id="builder-fields"></div>

### Field Builder

<!-- uncertain: new Nylo-specific term "NyFieldBuilder" dan "NyFieldBuilderLegacy" — not seen in existing locale file -->
Gunakan `Field.builder()` untuk membuat field form inline kustom tanpa men-subclass `NyFieldStatefulWidget`. Fungsi builder menerima nilai saat ini, callback `onChanged` untuk melaporkan perubahan nilai ke form, dan callback `setState` untuk memicu rebuild UI.

``` dart
Field.builder(
  "Favorite Color",
  builder: (context, onChanged, value, setState) {
    return ColorPicker(
      selected: value,
      onColorChanged: (color) {
        onChanged(color);
        setState(); // rebuild widget field
      },
    );
  },
  value: Colors.blue,
)
```

Parameter ketiga adalah nilai field saat ini dan yang keempat adalah `setState`. Jika builder Anda tidak memerlukan `setState`, Anda dapat menggunakan tanda tangan 3-argumen legacy (`NyFieldBuilderLegacy`), yang masih didukung:

``` dart
Field.builder(
  "Rating",
  builder: (context, onChanged, value) {
    return StarRatingWidget(
      rating: value ?? 0,
      onRatingChanged: onChanged,
    );
  },
)
```

<div id="widget-fields"></div>

### Field Widget

Gunakan `Field.widget()` untuk menyematkan widget apa pun di dalam form tanpa menjadi field form:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Field widget tidak berpartisipasi dalam validasi atau pengumpulan data. Mereka murni untuk tata letak.


<div id="form-collection"></div>

## FormCollection

Field picker, radio, dan chip memerlukan `FormCollection` untuk opsi mereka. `FormCollection` menyediakan antarmuka terpadu untuk menangani format opsi yang berbeda.

### Membuat FormCollection

``` dart
// Dari daftar string (value dan label sama)
FormCollection.from(["Red", "Green", "Blue"])

// Sama seperti di atas, eksplisit
FormCollection.fromArray(["Red", "Green", "Blue"])

// Dari map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// Dari data terstruktur (berguna untuk respons API)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` secara otomatis mendeteksi format data dan mendelegasikan ke konstruktor yang sesuai.

### FormOption

Setiap opsi dalam `FormCollection` adalah `FormOption` dengan properti `value` dan `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Mengkueri Opsi

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## Validasi Form

Tambahkan validasi ke field apa pun menggunakan parameter `validator` dengan `FormValidator`:

``` dart
// Named constructor (konstruktor bernama)
Field.email("Email", validator: FormValidator.email())

// Aturan berantai
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password dengan level kekuatan
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Validasi boolean
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Validasi inline kustom
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)

// Nullable — validasi lolos ketika field kosong
Field.text("Nickname",
  validator: FormValidator().minLength(3).nullable(),
)
```

Field yang ditandai `nullable()` akan melewati semua validasi jika nilainya kosong atau null. Ini berguna untuk field opsional yang hanya perlu divalidasi jika diisi.

Saat form dikirim, semua validator diperiksa. Jika ada yang gagal, error toast menampilkan pesan error pertama dan callback `onFailure` dipanggil.

**Lihat juga:** Untuk daftar lengkap validator yang tersedia, lihat halaman [Validasi](/docs/7.x/validation#validation-rules).


<div id="managing-form-data"></div>

## Mengelola Data Form

<div id="initial-data"></div>

### Data Awal

Ada dua cara untuk mengatur data awal pada form.

**Opsi 1: Override getter `init` di kelas form Anda**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

Getter `init` dapat mengembalikan `Map` sinkron atau `Future<Map>` async. Kunci dicocokkan dengan nama field menggunakan normalisasi snake_case, jadi `"First Name"` dipetakan ke field dengan kunci `"First Name"`.

#### Menggunakan `define()` di init

Gunakan helper `define()` ketika Anda perlu mengatur **options** (atau nilai dan options sekaligus) untuk sebuah field di `init`. Ini berguna untuk field picker, chip, dan radio di mana options berasal dari API atau sumber async lainnya.

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` menerima dua parameter bernama:

| Parameter | Deskripsi |
|-----------|-------------|
| `value` | Nilai awal untuk field |
| `options` | Options untuk field picker, chip, atau radio |

``` dart
// Atur hanya options (tanpa nilai awal)
"Category": define(options: categories),

// Atur hanya nilai awal
"Price": define(value: "100"),

// Atur nilai dan options sekaligus
"Country": define(value: "us", options: countries),

// Nilai biasa tetap berfungsi untuk field sederhana
"Name": "John",
```

Options yang dikirim ke `define()` dapat berupa `List`, `Map`, atau `FormCollection`. Mereka secara otomatis dikonversi menjadi `FormCollection` saat diterapkan.

**Opsi 2: Kirim `initialData` ke widget form**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Mengatur Nilai Field

Gunakan `NyFormActions` untuk mengatur nilai field dari mana saja:

``` dart
// Atur nilai field tunggal
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Mengatur Opsi Field

Perbarui opsi pada field picker, chip, atau radio secara dinamis:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Membaca Data Form

Data form diakses melalui callback `onSubmit` saat form dikirim, atau melalui callback `onChanged` untuk pembaruan real-time:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data adalah Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Menghapus Data

``` dart
// Hapus semua field
EditAccountForm.actions.clear();

// Hapus field tertentu
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Memperbarui Field

``` dart
// Perbarui nilai field
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh UI form
EditAccountForm.actions.refresh();

// Refresh field form (memanggil ulang fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Tombol Submit

Kirim `submitButton` dan callback `onSubmit` saat membuat form:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

`submitButton` secara otomatis ditampilkan di bawah field form. Anda dapat menggunakan gaya tombol bawaan atau widget kustom.

Anda juga dapat menggunakan widget apa pun sebagai tombol submit dengan mengirimnya sebagai `footer`:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## Tata Letak Form

Tempatkan field berdampingan dengan membungkusnya dalam `List`:

``` dart
@override
fields() => [
  // Field tunggal (lebar penuh)
  Field.text("Title"),

  // Dua field dalam satu baris
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Field tunggal lainnya
  Field.textArea("Bio"),

  // Slider dan range slider dalam satu baris
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Sematkan widget non-field
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Field dalam `List` dirender dalam `Row` dengan lebar `Expanded` yang sama. Jarak antar field dikontrol oleh parameter `crossAxisSpacing` pada `NyFormWidget`.


<div id="field-visibility"></div>

## Visibilitas Field

Tampilkan atau sembunyikan field secara programatis menggunakan method `hide()` dan `show()` pada `Field`. Anda dapat mengakses field di dalam kelas form Anda atau melalui callback `onChanged`:

``` dart
// Di dalam subkelas NyFormWidget atau callback onChanged Anda
Field nameField = ...;

// Sembunyikan field
nameField.hide();

// Tampilkan field
nameField.show();
```

Field tersembunyi tidak dirender di UI tetapi masih ada dalam daftar field form.


<div id="field-styling"></div>

## Styling Field

Setiap tipe field memiliki subkelas `FieldStyle` yang sesuai untuk styling:

| Tipe Field | Kelas Gaya |
|------------|------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Kirim objek gaya ke parameter `style` dari field apa pun:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## Method Statis NyFormWidget

`NyFormWidget` menyediakan method statis untuk berinteraksi dengan form berdasarkan nama dari mana saja di aplikasi Anda:

| Method | Deskripsi |
|--------|-----------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Kirim form berdasarkan namanya |
| `NyFormWidget.stateRefresh(name)` | Refresh state UI form |
| `NyFormWidget.stateSetValue(name, key, value)` | Atur nilai field berdasarkan nama form |
| `NyFormWidget.stateSetOptions(name, key, options)` | Atur opsi field berdasarkan nama form |
| `NyFormWidget.stateClearData(name)` | Hapus semua field berdasarkan nama form |
| `NyFormWidget.stateRefreshForm(name)` | Refresh field form (memanggil ulang `fields()`) |

``` dart
// Kirim form bernama "LoginForm" dari mana saja
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Perbarui nilai field dari jarak jauh
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Hapus semua data form
NyFormWidget.stateClearData("LoginForm");
```

> **Tips:** Lebih baik gunakan `NyFormActions` (lihat di bawah) daripada memanggil method statis ini secara langsung -- lebih ringkas dan minim kesalahan.


<div id="ny-form-widget-constructor-reference"></div>

## Referensi Konstruktor NyFormWidget

Saat meng-extend `NyFormWidget`, ini adalah parameter konstruktor yang dapat Anda kirim:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Jarak horizontal antar field dalam baris
  double mainAxisSpacing = 10,   // Jarak vertikal antar field
  Map<String, dynamic>? initialData, // Nilai field awal
  Function(Field field, dynamic value)? onChanged, // Callback perubahan field
  Widget? header,                // Widget di atas form
  Widget? submitButton,          // Widget tombol submit
  Widget? footer,                // Widget di bawah form
  double headerSpacing = 10,     // Jarak setelah header
  double submitButtonSpacing = 10, // Jarak setelah tombol submit
  double footerSpacing = 10,     // Jarak sebelum footer
  LoadingStyle? loadingStyle,    // Gaya indikator loading
  bool locked = false,           // Membuat form hanya-baca
  Function(dynamic data)? onSubmit,   // Dipanggil dengan data form saat validasi berhasil
  Function(dynamic error)? onFailure, // Dipanggil dengan error saat validasi gagal
)
```

Callback `onChanged` menerima `Field` yang berubah dan nilai barunya:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` menyediakan cara mudah untuk berinteraksi dengan form dari mana saja di aplikasi Anda. Definisikan sebagai getter statis di kelas form Anda:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### Aksi yang Tersedia

| Method | Deskripsi |
|--------|-----------|
| `actions.updateField(key, value)` | Atur nilai field |
| `actions.clearField(key)` | Hapus field tertentu |
| `actions.clear()` | Hapus semua field |
| `actions.refresh()` | Refresh state UI form |
| `actions.refreshForm()` | Panggil ulang `fields()` dan rebuild |
| `actions.setOptions(key, options)` | Atur opsi pada field picker/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Kirim dengan validasi |

``` dart
// Perbarui nilai field
LoginForm.actions.updateField("Email", "new@email.com");

// Hapus semua data form
LoginForm.actions.clear();

// Kirim form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Override NyFormWidget

Method yang dapat Anda override di subkelas `NyFormWidget` Anda:

| Override | Deskripsi |
|----------|-----------|
| `fields()` | Definisikan field form (wajib) |
| `init` | Sediakan data awal (sinkron atau async) |
| `onChange(field, data)` | Tangani perubahan field secara internal |


<div id="all-field-types-reference"></div>

## Referensi Semua Tipe Field

| Konstruktor | Parameter Kunci | Deskripsi |
|-------------|-----------------|-----------|
| `Field.text()` | -- | Input teks standar |
| `Field.email()` | -- | Input email dengan tipe keyboard |
| `Field.password()` | `viewable` | Password dengan toggle visibilitas opsional |
| `Field.number()` | `decimal` | Input numerik, desimal opsional |
| `Field.currency()` | `currency` (wajib) | Input berformat mata uang |
| `Field.capitalizeWords()` | -- | Input teks title case |
| `Field.capitalizeSentences()` | -- | Input teks sentence case |
| `Field.textArea()` | -- | Input teks multi-baris |
| `Field.phoneNumber()` | -- | Nomor telepon terformat otomatis |
| `Field.url()` | -- | Input URL dengan tipe keyboard |
| `Field.mask()` | `mask` (wajib), `match`, `maskReturnValue` | Input teks masked |
| `Field.date()` | -- | Date picker |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Picker tanggal dan waktu |
| `Field.checkbox()` | -- | Checkbox boolean |
| `Field.switchBox()` | -- | Toggle switch boolean |
| `Field.picker()` | `options` (wajib `FormCollection`) | Seleksi tunggal dari daftar |
| `Field.radio()` | `options` (wajib `FormCollection`) | Grup tombol radio |
| `Field.chips()` | `options` (wajib `FormCollection`) | Chip multi-seleksi |
| `Field.slider()` | -- | Slider nilai tunggal |
| `Field.rangeSlider()` | -- | Slider nilai rentang |
| `Field.custom()` | `child` (wajib `NyFieldStatefulWidget`) | Widget stateful kustom |
| `Field.builder()` | `builder` (wajib `NyFieldBuilder` atau `NyFieldBuilderLegacy`) | Field inline kustom tanpa subclass |
| `Field.widget()` | `child` (wajib `Widget`) | Sematkan widget apa pun (non-field) |

