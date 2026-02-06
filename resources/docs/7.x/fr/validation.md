# Validation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction a la validation")
- Les bases
  - [Valider des donnees avec check()](#validating-data "Valider des donnees avec check")
  - [Resultats de validation](#validation-results "Resultats de validation")
- FormValidator
  - [Utiliser FormValidator](#using-form-validator "Utiliser FormValidator")
  - [Constructeurs nommes de FormValidator](#form-validator-named-constructors "Constructeurs nommes de FormValidator")
  - [Chainer les regles de validation](#chaining-validation-rules "Chainer les regles de validation")
  - [Validation personnalisee](#custom-validation "Validation personnalisee")
  - [Utiliser FormValidator avec les champs](#form-validator-with-fields "Utiliser FormValidator avec les champs")
- [Regles de validation disponibles](#validation-rules "Regles de validation")
- [Creer des regles de validation personnalisees](#creating-custom-validation-rules "Creer des regles de validation personnalisees")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit un systeme de validation construit autour de la classe `FormValidator`. Vous pouvez valider des donnees dans les pages en utilisant la methode `check()`, ou utiliser `FormValidator` directement pour la validation autonome et au niveau des champs.

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

## Valider des donnees avec check()

Dans n'importe quel `NyPage`, utilisez la methode `check()` pour valider des donnees. Elle accepte un callback qui recoit une liste de validateurs. Utilisez `.that()` pour ajouter des donnees et chainer les regles :

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

### Fonctionnement de check()

1. `check()` cree une `List<FormValidator>` vide
2. Votre callback utilise `.that(data)` pour ajouter un nouveau `FormValidator` avec des donnees a la liste
3. Chaque `.that()` retourne un `FormValidator` sur lequel vous pouvez chainer des regles
4. Apres le callback, chaque validateur de la liste est verifie
5. Les resultats sont collectes dans un `FormValidationResponseBag`

### Valider plusieurs champs

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

Le parametre optionnel `label` definit un nom lisible utilise dans les messages d'erreur (par exemple, "The Email must be a valid email address.").

<div id="validation-results"></div>

## Resultats de validation

La methode `check()` retourne un `FormValidationResponseBag` (une `List<FormValidationResult>`), que vous pouvez aussi inspecter directement :

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

Chaque `FormValidationResult` represente le resultat de la validation d'une seule valeur :

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

## Utiliser FormValidator

`FormValidator` peut etre utilise de maniere autonome ou avec des champs de formulaire.

### Utilisation autonome

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

### Avec des donnees dans le constructeur

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Constructeurs nommes de FormValidator

{{ config('app.name') }} v7 fournit des constructeurs nommes pour les validations courantes :

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

## Chainer les regles de validation

Chainez plusieurs regles de maniere fluide en utilisant les methodes d'instance. Chaque methode retourne le `FormValidator`, vous permettant d'accumuler les regles :

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

Tous les constructeurs nommes ont des methodes d'instance chainables correspondantes :

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

## Validation personnalisee

### Regle personnalisee (en ligne)

Utilisez `.custom()` pour ajouter une logique de validation en ligne :

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

Ou chainez-la avec d'autres regles :

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Validation d'egalite

Verifiez si une valeur correspond a une autre :

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Utiliser FormValidator avec les champs

`FormValidator` s'integre avec les widgets `Field` dans les formulaires. Passez un validateur au parametre `validator` :

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

Vous pouvez egalement utiliser des validateurs chaines avec les champs :

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

## Regles de validation disponibles

Toutes les regles disponibles pour `FormValidator`, a la fois en tant que constructeurs nommes et methodes chainables :

| Regle | Constructeur nomme | Methode chainable | Description |
|-------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Valide le format d'email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Force 1 : 8+ caracteres, 1 majuscule, 1 chiffre. Force 2 : + 1 caractere special |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Ne peut pas etre vide |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Longueur minimale de la chaine |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Longueur maximale de la chaine |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Valeur numerique minimale (fonctionne aussi sur la longueur des chaines, listes, maps) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Valeur numerique maximale |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Taille minimale pour les listes/chaines |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Taille maximale pour les listes/chaines |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | La valeur doit contenir l'une des valeurs donnees |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | La chaine doit commencer par le prefixe |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | La chaine doit se terminer par le suffixe |
| URL | `FormValidator.url()` | `.url()` | Valide le format d'URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Doit etre un nombre |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Doit etre `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Doit etre `false` |
| Date | `FormValidator.date()` | `.date()` | Doit etre une date valide |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | La date doit etre dans le passe |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | La date doit etre dans le futur |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | L'age doit etre superieur a N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | L'age doit etre inferieur a N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | La premiere lettre doit etre en majuscule |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Tous les caracteres doivent etre en minuscules |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Tous les caracteres doivent etre en majuscules |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Format de numero de telephone americain |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Format de numero de telephone britannique |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Format de code postal americain |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Format de code postal britannique |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Doit correspondre au motif regex |
| Equals | — | `.equals(otherValue)` | Doit etre egal a une autre valeur |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Fonction de validation personnalisee |

Toutes les regles acceptent un parametre optionnel `message` pour personnaliser le message d'erreur.

<div id="creating-custom-validation-rules"></div>

## Creer des regles de validation personnalisees

Pour creer une regle de validation reutilisable, etendez la classe `FormRule` :

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

Utilisez `@{{attribute}}` comme espace reserve dans le `message` — il sera remplace par le label du champ au moment de l'execution.

### Utiliser un FormRule personnalise

Ajoutez votre regle personnalisee a un `FormValidator` en utilisant `FormValidator.rule()` :

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Ou utilisez la methode `.custom()` pour des regles ponctuelles sans creer de classe :

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
