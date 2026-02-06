# Doğrulama

---

<a name="section-1"></a>
- [Giriş](#introduction "Doğrulamaya giriş")
- Temel Bilgiler
  - [check() ile Veri Doğrulama](#validating-data "check() ile veri doğrulama")
  - [Doğrulama Sonuçları](#validation-results "Doğrulama sonuçları")
- FormValidator
  - [FormValidator Kullanımı](#using-form-validator "FormValidator Kullanımı")
  - [FormValidator Adlandırılmış Yapıcılar](#form-validator-named-constructors "FormValidator adlandırılmış yapıcılar")
  - [Doğrulama Kurallarını Zincirleme](#chaining-validation-rules "Doğrulama kurallarını zincirleme")
  - [Özel Doğrulama](#custom-validation "Özel doğrulama")
  - [FormValidator'ı Alanlarla Kullanma](#form-validator-with-fields "FormValidator'ı Alanlarla Kullanma")
- [Mevcut Doğrulama Kuralları](#validation-rules "Doğrulama kuralları")
- [Özel Doğrulama Kuralları Oluşturma](#creating-custom-validation-rules "Özel doğrulama kuralları oluşturma")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7, `FormValidator` sınıfı etrafında oluşturulmuş bir doğrulama sistemi sunar. Sayfalarınızda `check()` metodunu kullanarak verileri doğrulayabilir veya bağımsız ve alan seviyesinde doğrulama için `FormValidator`'ı doğrudan kullanabilirsiniz.

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

## check() ile Veri Doğrulama

Herhangi bir `NyPage` içinde veri doğrulamak için `check()` metodunu kullanın. Doğrulayıcı listesi alan bir callback kabul eder. Veri eklemek ve kuralları zincirlemek için `.that()` kullanın:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // Tüm doğrulamalar geçti
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Doğrulama başarısız
      print(bag.firstErrorMessage);
    });
  }
}
```

### check() Nasıl Çalışır

1. `check()` boş bir `List<FormValidator>` oluşturur
2. Callback'iniz, listeye veriyle yeni bir `FormValidator` eklemek için `.that(data)` kullanır
3. Her `.that()`, kuralları zincirleme olarak ekleyebileceğiniz bir `FormValidator` döndürür
4. Callback'ten sonra, listedeki her doğrulayıcı kontrol edilir
5. Sonuçlar bir `FormValidationResponseBag` içinde toplanır

### Birden Fazla Alanı Doğrulama

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

İsteğe bağlı `label` parametresi, hata mesajlarında kullanılan insan tarafından okunabilir bir ad ayarlar (örn. "The Email must be a valid email address.").

<div id="validation-results"></div>

## Doğrulama Sonuçları

`check()` metodu bir `FormValidationResponseBag` (`List<FormValidationResult>`) döndürür ve bunu doğrudan inceleyebilirsiniz:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // İlk hata mesajını al
  String? error = bag.firstErrorMessage;
  print(error);

  // Başarısız tüm sonuçları al
  List<FormValidationResult> errors = bag.validationErrors;

  // Başarılı tüm sonuçları al
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Her `FormValidationResult`, tek bir değerin doğrulanmasının sonucunu temsil eder:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // İlk hata mesajı
  String? message = result.getFirstErrorMessage();

  // Tüm hata mesajları
  List<String> messages = result.errorMessages();

  // Hata yanıtları
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## FormValidator Kullanımı

`FormValidator` bağımsız olarak veya form alanlarıyla birlikte kullanılabilir.

### Bağımsız Kullanım

``` dart
// Adlandırılmış yapıcı kullanarak
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### Yapıcıda Veri ile

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator Adlandırılmış Yapıcılar

{{ config('app.name') }} v7, yaygın doğrulamalar için adlandırılmış yapıcılar sunar:

``` dart
// E-posta doğrulama
FormValidator.email(message: "Please enter a valid email")

// Parola doğrulama (güç 1 veya 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// Telefon numaraları
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL doğrulama
FormValidator.url()

// Uzunluk kısıtlamaları
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// Değer kısıtlamaları
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// Boyut kısıtlamaları (listeler/dizeler için)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// Boş olmama
FormValidator.notEmpty(message: "This field is required")

// Değer içerme
FormValidator.contains(['option1', 'option2'])

// Başlangıç/bitiş ile
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Boolean kontrolleri
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// Sayısal
FormValidator.numeric()

// Tarih doğrulamaları
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// Metin büyük/küçük harf
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Konum formatları
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Regex deseni
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// Özel doğrulama
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## Doğrulama Kurallarını Zincirleme

Örnek metotları kullanarak birden fazla kuralı akıcı şekilde zincirleyin. Her metot `FormValidator` döndürür ve kuralları biriktirmenize olanak tanır:

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

Tüm adlandırılmış yapıcıların karşılık gelen zincirleme örnek metotları vardır:

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

## Özel Doğrulama

### Özel Kural (Satır İçi)

Satır içi doğrulama mantığı eklemek için `.custom()` kullanın:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Geçerliyse true, geçersizse false döndürün
    return !_takenUsernames.contains(data);
  },
)
```

Veya diğer kurallarla zincirleyin:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Eşitlik Doğrulaması

Bir değerin başka bir değerle eşleşip eşleşmediğini kontrol edin:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## FormValidator'ı Alanlarla Kullanma

`FormValidator`, formlardaki `Field` widget'larıyla entegre olur. `validator` parametresine bir doğrulayıcı geçirin:

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

Alanlarla zincirleme doğrulayıcılar da kullanabilirsiniz:

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

## Mevcut Doğrulama Kuralları

`FormValidator` için hem adlandırılmış yapıcılar hem de zincirleme metotlar olarak mevcut tüm kurallar:

| Kural | Adlandırılmış Yapıcı | Zincirleme Metot | Açıklama |
|-------|---------------------|-----------------|----------|
| Email | `FormValidator.email()` | `.email()` | E-posta formatını doğrular |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Güç 1: 8+ karakter, 1 büyük harf, 1 rakam. Güç 2: + 1 özel karakter |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Boş olamaz |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Minimum dize uzunluğu |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Maksimum dize uzunluğu |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Minimum sayısal değer (dize uzunluğu, liste uzunluğu, map uzunluğu için de çalışır) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Maksimum sayısal değer |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Listeler/dizeler için minimum boyut |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Listeler/dizeler için maksimum boyut |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Değer verilen değerlerden birini içermelidir |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | Dize önekle başlamalıdır |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | Dize sonekle bitmelidir |
| URL | `FormValidator.url()` | `.url()` | URL formatını doğrular |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Sayı olmalıdır |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | `true` olmalıdır |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | `false` olmalıdır |
| Date | `FormValidator.date()` | `.date()` | Geçerli bir tarih olmalıdır |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Tarih geçmişte olmalıdır |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Tarih gelecekte olmalıdır |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Yaş N'den büyük olmalıdır |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Yaş N'den küçük olmalıdır |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | İlk harf büyük olmalıdır |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Tüm karakterler küçük harf olmalıdır |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Tüm karakterler büyük harf olmalıdır |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | ABD telefon numarası formatı |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Birleşik Krallık telefon numarası formatı |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | ABD posta kodu formatı |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Birleşik Krallık posta kodu formatı |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Regex deseniyle eşleşmelidir |
| Equals | — | `.equals(otherValue)` | Başka bir değere eşit olmalıdır |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Özel doğrulama fonksiyonu |

Tüm kurallar, hata mesajını özelleştirmek için isteğe bağlı bir `message` parametresi kabul eder.

<div id="creating-custom-validation-rules"></div>

## Özel Doğrulama Kuralları Oluşturma

Yeniden kullanılabilir bir doğrulama kuralı oluşturmak için `FormRule` sınıfını genişletin:

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
    // Kullanıcı adı: alfasayısal, alt çizgi, 3-20 karakter
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

`message` içinde yer tutucu olarak `@{{attribute}}` kullanın - çalışma zamanında alanın etiketiyle değiştirilecektir.

### Özel FormRule Kullanımı

Özel kuralınızı `FormValidator.rule()` kullanarak bir `FormValidator`'a ekleyin:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Veya sınıf oluşturmadan tek seferlik kurallar için `.custom()` metodunu kullanın:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
