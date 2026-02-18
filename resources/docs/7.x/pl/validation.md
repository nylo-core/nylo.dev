# Validation

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do walidacji")
- Podstawy
  - [Walidacja danych za pomocą check()](#validating-data "Walidacja danych za pomocą check")
  - [Wyniki walidacji](#validation-results "Wyniki walidacji")
- FormValidator
  - [Używanie FormValidator](#using-form-validator "Używanie FormValidator")
  - [Nazwane konstruktory FormValidator](#form-validator-named-constructors "Nazwane konstruktory FormValidator")
  - [Łączenie reguł walidacji](#chaining-validation-rules "Łączenie reguł walidacji")
  - [Niestandardowa walidacja](#custom-validation "Niestandardowa walidacja")
  - [Używanie FormValidator z polami](#form-validator-with-fields "Używanie FormValidator z polami")
- [Dostępne reguły walidacji](#validation-rules "Reguły walidacji")
- [Tworzenie niestandardowych reguł walidacji](#creating-custom-validation-rules "Tworzenie niestandardowych reguł walidacji")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 udostępnia system walidacji oparty na klasie `FormValidator`. Możesz walidować dane wewnątrz stron za pomocą metody `check()` lub używać `FormValidator` bezpośrednio do samodzielnej walidacji i walidacji na poziomie pól.

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

## Walidacja danych za pomocą check()

Wewnątrz dowolnej strony `NyPage` użyj metody `check()` do walidacji danych. Przyjmuje callback, który otrzymuje listę walidatorów. Użyj `.that()`, aby dodać dane i łączyć reguły:

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

### Jak działa check()

1. `check()` tworzy pustą `List<FormValidator>`
2. Twój callback używa `.that(data)`, aby dodać nowy `FormValidator` z danymi do listy
3. Każde `.that()` zwraca `FormValidator`, na którym możesz łączyć reguły
4. Po callbacku każdy walidator na liście jest sprawdzany
5. Wyniki są zbierane w `FormValidationResponseBag`

### Walidacja wielu pól

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

Opcjonalny parametr `label` ustawia czytelną dla człowieka nazwę używaną w komunikatach błędów (np. "The Email must be a valid email address.").

<div id="validation-results"></div>

## Wyniki walidacji

Metoda `check()` zwraca `FormValidationResponseBag` (czyli `List<FormValidationResult>`), który możesz również bezpośrednio sprawdzić:

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

Każdy `FormValidationResult` reprezentuje wynik walidacji pojedynczej wartości:

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

## Używanie FormValidator

`FormValidator` może być używany samodzielnie lub z polami formularza.

### Samodzielne użycie

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

### Z danymi w konstruktorze

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Nazwane konstruktory FormValidator

{{ config('app.name') }} v7 udostępnia nazwane konstruktory dla typowych walidacji:

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

## Łączenie reguł walidacji

Łącz wiele reguł płynnie za pomocą metod instancji. Każda metoda zwraca `FormValidator`, pozwalając budować zestaw reguł:

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

Wszystkie nazwane konstruktory mają odpowiadające im łańcuchowe metody instancji:

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

## Niestandardowa walidacja

### Niestandardowa reguła (inline)

Użyj `.custom()`, aby dodać inline logikę walidacji:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

Lub połącz ją z innymi regułami:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Walidacja równości

Sprawdź, czy wartość pasuje do innej:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Używanie FormValidator z polami

`FormValidator` integruje się z widgetami `Field` w formularzach. Przekaż walidator do parametru `validator`:

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

Możesz również używać łańcuchowych walidatorów z polami:

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

## Dostępne reguły walidacji

Wszystkie dostępne reguły dla `FormValidator`, zarówno jako nazwane konstruktory, jak i łańcuchowe metody:

| Reguła | Nazwany konstruktor | Łańcuchowa metoda | Opis |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Waliduje format email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Siła 1: 8+ znaków, 1 wielka litera, 1 cyfra. Siła 2: + 1 znak specjalny |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Nie może być puste |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Minimalna długość ciągu znaków |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Maksymalna długość ciągu znaków |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Minimalna wartość liczbowa (działa też na długość ciągu, listy, mapy) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Maksymalna wartość liczbowa |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Minimalny rozmiar dla list/ciągów |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Maksymalny rozmiar dla list/ciągów |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Wartość musi zawierać jedną z podanych wartości |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | Ciąg musi zaczynać się od prefiksu |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | Ciąg musi kończyć się sufiksem |
| URL | `FormValidator.url()` | `.url()` | Waliduje format URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Musi być liczbą |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Musi być `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Musi być `false` |
| Date | `FormValidator.date()` | `.date()` | Musi być prawidłową datą |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Data musi być w przeszłości |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Data musi być w przyszłości |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Wiek musi być starszy niż N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Wiek musi być młodszy niż N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | Pierwsza litera musi być wielka |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Wszystkie znaki muszą być małe |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Wszystkie znaki muszą być wielkie |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Format numeru telefonu US |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Format numeru telefonu UK |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Format kodu pocztowego US |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Format kodu pocztowego UK |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Musi pasować do wzorca regex |
| Equals | — | `.equals(otherValue)` | Musi być równe innej wartości |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Niestandardowa funkcja walidacji |

Wszystkie reguły przyjmują opcjonalny parametr `message` do dostosowania komunikatu błędu.

<div id="creating-custom-validation-rules"></div>

## Tworzenie niestandardowych reguł walidacji

Aby utworzyć wielokrotnie używaną regułę walidacji, rozszerz klasę `FormRule`:

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

Użyj `@{{attribute}}` jako symbolu zastępczego w `message` -- zostanie on zastąpiony etykietą pola w czasie wykonywania.

### Używanie niestandardowej reguły FormRule

Dodaj swoją niestandardową regułę do `FormValidator` za pomocą `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Lub użyj metody `.custom()` do jednorazowych reguł bez tworzenia klasy:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
