# Validasi

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Dasar-Dasar
  - [Memvalidasi Data dengan check()](#validating-data "Memvalidasi Data dengan check()")
  - [Hasil Validasi](#validation-results "Hasil Validasi")
- FormValidator
  - [Menggunakan FormValidator](#using-form-validator "Menggunakan FormValidator")
  - [Konstruktor Bernama FormValidator](#form-validator-named-constructors "Konstruktor Bernama FormValidator")
  - [Merangkai Aturan Validasi](#chaining-validation-rules "Merangkai Aturan Validasi")
  - [Validasi Kustom](#custom-validation "Validasi Kustom")
  - [Menggunakan FormValidator dengan Field](#form-validator-with-fields "Menggunakan FormValidator dengan Field")
- [Aturan Validasi yang Tersedia](#validation-rules "Aturan Validasi yang Tersedia")
- [Membuat Aturan Validasi Kustom](#creating-custom-validation-rules "Membuat Aturan Validasi Kustom")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan sistem validasi yang dibangun di sekitar class `FormValidator`. Anda dapat memvalidasi data di dalam halaman menggunakan metode `check()`, atau menggunakan `FormValidator` secara langsung untuk validasi mandiri dan tingkat field.

``` dart
// Validate data in a NyPage/NyState using check()
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// FormValidator with form fields
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## Memvalidasi Data dengan check()

Di dalam `NyPage` mana pun, gunakan metode `check()` untuk memvalidasi data. Metode ini menerima callback yang menerima daftar validator. Gunakan `.that()` untuk menambahkan data dan merangkai aturan:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // All validations passed
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Validation failed
      print(bag.firstErrorMessage);
    });
  }
}
```

### Cara Kerja check()

1. `check()` membuat `List<FormValidator>` kosong
2. Callback Anda menggunakan `.that(data)` untuk menambahkan `FormValidator` baru dengan data ke daftar
3. Setiap `.that()` mengembalikan `FormValidator` yang dapat Anda rangkai aturannya
4. Setelah callback, setiap validator dalam daftar diperiksa
5. Hasil dikumpulkan ke dalam `FormValidationResponseBag`

### Memvalidasi Beberapa Field

``` dart
check((validate) {
  validate.that(_nameController.text, label: "Name").notEmpty().capitalized();
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_phoneController.text, label: "Phone").phoneNumberUs();
  validate.that(_ageController.text, label: "Age").numeric().minValue(18);
}, onSuccess: () {
  _submitForm();
});
```

Parameter `label` opsional mengatur nama yang mudah dibaca yang digunakan dalam pesan error (misalnya, "The Email must be a valid email address.").

<div id="validation-results"></div>

## Hasil Validasi

Metode `check()` mengembalikan `FormValidationResponseBag` (sebuah `List<FormValidationResult>`), yang juga dapat Anda periksa secara langsung:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // Get the first error message
  String? error = bag.firstErrorMessage;
  print(error);

  // Get all failed results
  List<FormValidationResult> errors = bag.validationErrors;

  // Get all successful results
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Setiap `FormValidationResult` merepresentasikan hasil validasi satu nilai:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // First error message
  String? message = result.getFirstErrorMessage();

  // All error messages
  List<String> messages = result.errorMessages();

  // Error responses
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## Menggunakan FormValidator

`FormValidator` dapat digunakan secara mandiri atau dengan form field.

### Penggunaan Mandiri

``` dart
// Using a named constructor
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### Dengan Data di Constructor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Konstruktor Bernama FormValidator

{{ config('app.name') }} v7 menyediakan konstruktor bernama untuk validasi umum:

``` dart
// Email validation
FormValidator.email(message: "Please enter a valid email")

// Password validation (strength 1 or 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// Phone numbers
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL validation
FormValidator.url()

// Length constraints
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// Value constraints
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// Size constraints (for lists/strings)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// Not empty
FormValidator.notEmpty(message: "This field is required")

// Contains values
FormValidator.contains(['option1', 'option2'])

// Starts/ends with
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Boolean checks
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// Numeric
FormValidator.numeric()

// Date validations
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// Text case
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Location formats
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Regex pattern
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// Custom validation
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## Merangkai Aturan Validasi

Rangkai beberapa aturan secara lancar menggunakan metode instance. Setiap metode mengembalikan `FormValidator`, memungkinkan Anda membangun aturan:

``` dart
FormValidator validator = FormValidator()
    .notEmpty()
    .email()
    .maxLength(50);

FormValidationResult result = validator.check("user@example.com");

if (!result.isValid) {
  List<String> errors = result.errorMessages();
  print(errors);
}
```

Semua konstruktor bernama memiliki metode instance yang dapat dirangkai:

``` dart
FormValidator()
    .notEmpty(message: "Required")
    .email(message: "Invalid email")
    .minLength(5, message: "Too short")
    .maxLength(100, message: "Too long")
    .beginsWith("user", message: "Must start with 'user'")
    .lowercase(message: "Must be lowercase")
```

<div id="custom-validation"></div>

## Validasi Kustom

### Aturan Kustom (Inline)

Gunakan `.custom()` untuk menambahkan logika validasi inline:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

Atau rangkai dengan aturan lain:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Validasi Kesetaraan

Periksa apakah nilai cocok dengan yang lain:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Menggunakan FormValidator dengan Field

`FormValidator` terintegrasi dengan widget `Field` di form. Berikan validator ke parameter `validator`:

``` dart
class RegisterForm extends NyFormData {
  RegisterForm({String? name}) : super(name ?? "register");

  @override
  fields() => [
        Field.text(
          "Name",
          autofocus: true,
          validator: FormValidator.notEmpty(),
        ),
        Field.email("Email", validator: FormValidator.email()),
        Field.password(
          "Password",
          validator: FormValidator.password(strength: 1),
        ),
      ];
}
```

Anda juga dapat menggunakan validator berantai dengan field:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Username is required")
      .minLength(3, message: "At least 3 characters")
      .maxLength(20, message: "At most 20 characters"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
)
```

<div id="validation-rules"></div>

## Aturan Validasi yang Tersedia

Semua aturan yang tersedia untuk `FormValidator`, baik sebagai konstruktor bernama maupun metode yang dapat dirangkai:

| Aturan | Konstruktor Bernama | Metode Berantai | Deskripsi |
|--------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Memvalidasi format email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Strength 1: 8+ karakter, 1 huruf besar, 1 angka. Strength 2: + 1 karakter spesial |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Tidak boleh kosong |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Panjang string minimum |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Panjang string maksimum |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Nilai numerik minimum (juga bekerja pada panjang string, panjang list, panjang map) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Nilai numerik maksimum |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Ukuran minimum untuk list/string |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Ukuran maksimum untuk list/string |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Nilai harus mengandung salah satu dari nilai yang diberikan |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | String harus dimulai dengan prefix |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | String harus diakhiri dengan suffix |
| URL | `FormValidator.url()` | `.url()` | Memvalidasi format URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Harus berupa angka |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Harus `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Harus `false` |
| Date | `FormValidator.date()` | `.date()` | Harus tanggal yang valid |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Tanggal harus di masa lalu |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Tanggal harus di masa depan |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Usia harus lebih tua dari N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Usia harus lebih muda dari N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | Huruf pertama harus huruf besar |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Semua karakter harus huruf kecil |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Semua karakter harus huruf besar |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Format nomor telepon AS |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Format nomor telepon UK |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Format zipcode AS |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Format postcode UK |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Harus cocok dengan pola regex |
| Equals | — | `.equals(otherValue)` | Harus sama dengan nilai lain |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Fungsi validasi kustom |

Semua aturan menerima parameter `message` opsional untuk menyesuaikan pesan error.

<div id="creating-custom-validation-rules"></div>

## Membuat Aturan Validasi Kustom

Untuk membuat aturan validasi yang dapat digunakan kembali, extend class `FormRule`:

``` dart
class FormRuleUsername extends FormRule {
  @override
  String? rule = "username";

  @override
  String? message = "The @{{attribute}} must be a valid username.";

  FormRuleUsername({String? message}) {
    if (message != null) {
      this.message = message;
    }
  }

  @override
  bool validate(data) {
    if (data is! String) return false;
    // Username: alphanumeric, underscores, 3-20 chars
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

Gunakan `@{{attribute}}` sebagai placeholder di `message` -- akan diganti dengan label field saat runtime.

### Menggunakan FormRule Kustom

Tambahkan aturan kustom Anda ke `FormValidator` menggunakan `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Atau gunakan metode `.custom()` untuk aturan sekali pakai tanpa membuat class:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
