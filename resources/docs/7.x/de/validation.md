# Validierung

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Grundlagen
  - [Daten mit check() validieren](#validating-data "Daten mit check() validieren")
  - [Validierungsergebnisse](#validation-results "Validierungsergebnisse")
- FormValidator
  - [FormValidator verwenden](#using-form-validator "FormValidator verwenden")
  - [Benannte FormValidator-Konstruktoren](#form-validator-named-constructors "Benannte FormValidator-Konstruktoren")
  - [Validierungsregeln verketten](#chaining-validation-rules "Validierungsregeln verketten")
  - [Benutzerdefinierte Validierung](#custom-validation "Benutzerdefinierte Validierung")
  - [FormValidator mit Feldern verwenden](#form-validator-with-fields "FormValidator mit Feldern verwenden")
- [Verfuegbare Validierungsregeln](#validation-rules "Verfuegbare Validierungsregeln")
- [Benutzerdefinierte Validierungsregeln erstellen](#creating-custom-validation-rules "Benutzerdefinierte Validierungsregeln erstellen")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet ein Validierungssystem, das auf der Klasse `FormValidator` aufgebaut ist. Sie koennen Daten innerhalb von Seiten mit der `check()`-Methode validieren oder `FormValidator` direkt fuer eigenstaendige und feldbasierte Validierung verwenden.

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

## Daten mit check() validieren

Verwenden Sie innerhalb jeder `NyPage` die `check()`-Methode, um Daten zu validieren. Sie akzeptiert einen Callback, der eine Liste von Validatoren erhaelt. Verwenden Sie `.that()`, um Daten hinzuzufuegen und Regeln zu verketten:

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

### Wie check() funktioniert

1. `check()` erstellt eine leere `List<FormValidator>`
2. Ihr Callback verwendet `.that(data)`, um einen neuen `FormValidator` mit Daten zur Liste hinzuzufuegen
3. Jedes `.that()` gibt einen `FormValidator` zurueck, an den Sie Regeln verketten koennen
4. Nach dem Callback wird jeder Validator in der Liste geprueft
5. Die Ergebnisse werden in einer `FormValidationResponseBag` gesammelt

### Mehrere Felder validieren

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

Der optionale `label`-Parameter setzt einen lesbaren Namen, der in Fehlermeldungen verwendet wird (z.B. "The Email must be a valid email address.").

<div id="validation-results"></div>

## Validierungsergebnisse

Die `check()`-Methode gibt eine `FormValidationResponseBag` (eine `List<FormValidationResult>`) zurueck, die Sie auch direkt inspizieren koennen:

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

Jedes `FormValidationResult` repraesentiert das Ergebnis der Validierung eines einzelnen Wertes:

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

## FormValidator verwenden

`FormValidator` kann eigenstaendig oder mit Formularfeldern verwendet werden.

### Eigenstaendige Verwendung

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

### Mit Daten im Konstruktor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Benannte FormValidator-Konstruktoren

{{ config('app.name') }} v7 bietet benannte Konstruktoren fuer gaengige Validierungen:

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

## Validierungsregeln verketten

Verketten Sie mehrere Regeln fliessend mit Instanzmethoden. Jede Methode gibt den `FormValidator` zurueck, sodass Sie Regeln aufbauen koennen:

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

Alle benannten Konstruktoren haben entsprechende verkettbare Instanzmethoden:

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

## Benutzerdefinierte Validierung

### Benutzerdefinierte Regel (Inline)

Verwenden Sie `.custom()`, um Inline-Validierungslogik hinzuzufuegen:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

Oder verketten Sie es mit anderen Regeln:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Equals-Validierung

Pruefen Sie, ob ein Wert einem anderen entspricht:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## FormValidator mit Feldern verwenden

`FormValidator` integriert sich mit `Field`-Widgets in Formularen. Uebergeben Sie einen Validator an den `validator`-Parameter:

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

Sie koennen auch verkettete Validatoren mit Feldern verwenden:

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

## Verfuegbare Validierungsregeln

Alle verfuegbaren Regeln fuer `FormValidator`, sowohl als benannte Konstruktoren als auch als verkettbare Methoden:

| Regel | Benannter Konstruktor | Verkettbare Methode | Beschreibung |
|-------|----------------------|---------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Validiert E-Mail-Format |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Staerke 1: 8+ Zeichen, 1 Grossbuchstabe, 1 Ziffer. Staerke 2: + 1 Sonderzeichen |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Darf nicht leer sein |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Minimale String-Laenge |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Maximale String-Laenge |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Minimaler numerischer Wert (funktioniert auch mit String-Laenge, Listen-Laenge, Map-Laenge) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Maximaler numerischer Wert |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Mindestgroesse fuer Listen/Strings |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Maximalgroesse fuer Listen/Strings |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Wert muss einen der angegebenen Werte enthalten |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | String muss mit Praefix beginnen |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | String muss mit Suffix enden |
| URL | `FormValidator.url()` | `.url()` | Validiert URL-Format |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Muss eine Zahl sein |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Muss `true` sein |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Muss `false` sein |
| Date | `FormValidator.date()` | `.date()` | Muss ein gueltiges Datum sein |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Datum muss in der Vergangenheit liegen |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Datum muss in der Zukunft liegen |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Alter muss aelter als N sein |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Alter muss juenger als N sein |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | Erster Buchstabe muss gross sein |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Alle Zeichen muessen Kleinbuchstaben sein |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Alle Zeichen muessen Grossbuchstaben sein |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | US-Telefonnummernformat |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | UK-Telefonnummernformat |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | US-Postleitzahlformat |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | UK-Postleitzahlformat |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Muss dem Regex-Muster entsprechen |
| Equals | -- | `.equals(otherValue)` | Muss einem anderen Wert entsprechen |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Benutzerdefinierte Validierungsfunktion |

Alle Regeln akzeptieren einen optionalen `message`-Parameter, um die Fehlermeldung anzupassen.

<div id="creating-custom-validation-rules"></div>

## Benutzerdefinierte Validierungsregeln erstellen

Um eine wiederverwendbare Validierungsregel zu erstellen, erweitern Sie die Klasse `FormRule`:

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

Verwenden Sie `@{{attribute}}` als Platzhalter in der `message` -- er wird zur Laufzeit durch das Label des Feldes ersetzt.

### Eine benutzerdefinierte FormRule verwenden

Fuegen Sie Ihre benutzerdefinierte Regel zu einem `FormValidator` mit `FormValidator.rule()` hinzu:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Oder verwenden Sie die `.custom()`-Methode fuer einmalige Regeln, ohne eine Klasse zu erstellen:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
