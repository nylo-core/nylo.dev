# Validation

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a la validacion")
- Basicos
  - [Validar Datos con check()](#validating-data "Validar datos con check")
  - [Resultados de Validacion](#validation-results "Resultados de validacion")
- FormValidator
  - [Usando FormValidator](#using-form-validator "Usando FormValidator")
  - [Constructores con Nombre de FormValidator](#form-validator-named-constructors "Constructores con nombre de FormValidator")
  - [Encadenar Reglas de Validacion](#chaining-validation-rules "Encadenar reglas de validacion")
  - [Validacion Personalizada](#custom-validation "Validacion personalizada")
  - [Usando FormValidator con Campos](#form-validator-with-fields "Usando FormValidator con Campos")
- [Reglas de Validacion Disponibles](#validation-rules "Reglas de validacion")
- [Crear Reglas de Validacion Personalizadas](#creating-custom-validation-rules "Crear reglas de validacion personalizadas")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 proporciona un sistema de validacion construido alrededor de la clase `FormValidator`. Puedes validar datos dentro de paginas usando el metodo `check()`, o usar `FormValidator` directamente para validacion independiente y a nivel de campo.

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

## Validar Datos con check()

Dentro de cualquier `NyPage`, usa el metodo `check()` para validar datos. Acepta un callback que recibe una lista de validadores. Usa `.that()` para agregar datos y encadenar reglas:

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

### Como Funciona check()

1. `check()` crea una `List<FormValidator>` vacia
2. Tu callback usa `.that(data)` para agregar un nuevo `FormValidator` con datos a la lista
3. Cada `.that()` retorna un `FormValidator` al que puedes encadenar reglas
4. Despues del callback, cada validador en la lista se verifica
5. Los resultados se recopilan en un `FormValidationResponseBag`

### Validar Multiples Campos

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

El parametro opcional `label` establece un nombre legible usado en los mensajes de error (ej., "The Email must be a valid email address.").

<div id="validation-results"></div>

## Resultados de Validacion

El metodo `check()` retorna un `FormValidationResponseBag` (una `List<FormValidationResult>`), que tambien puedes inspeccionar directamente:

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

Cada `FormValidationResult` representa el resultado de validar un unico valor:

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

## Usando FormValidator

`FormValidator` se puede usar de forma independiente o con campos de formulario.

### Uso Independiente

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

### Con Datos en el Constructor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Constructores con Nombre de FormValidator

{{ config('app.name') }} v7 proporciona constructores con nombre para validaciones comunes:

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

## Encadenar Reglas de Validacion

Encadena multiples reglas de forma fluida usando metodos de instancia. Cada metodo retorna el `FormValidator`, permitiendote construir reglas:

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

Todos los constructores con nombre tienen metodos de instancia encadenables correspondientes:

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

## Validacion Personalizada

### Regla Personalizada (En Linea)

Usa `.custom()` para agregar logica de validacion en linea:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

O encadenala con otras reglas:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Validacion de Igualdad

Verifica si un valor coincide con otro:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Usando FormValidator con Campos

`FormValidator` se integra con widgets `Field` en formularios. Pasa un validador al parametro `validator`:

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

Tambien puedes usar validadores encadenados con campos:

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

## Reglas de Validacion Disponibles

Todas las reglas disponibles para `FormValidator`, tanto como constructores con nombre como metodos encadenables:

| Regla | Constructor con Nombre | Metodo Encadenable | Descripcion |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Valida formato de email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Fuerza 1: 8+ caracteres, 1 mayuscula, 1 digito. Fuerza 2: + 1 caracter especial |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | No puede estar vacio |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Longitud minima de cadena |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Longitud maxima de cadena |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Valor numerico minimo (tambien funciona con longitud de cadena, lista y mapa) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Valor numerico maximo |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Tamano minimo para listas/cadenas |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Tamano maximo para listas/cadenas |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | El valor debe contener uno de los valores dados |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | La cadena debe comenzar con el prefijo |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | La cadena debe terminar con el sufijo |
| URL | `FormValidator.url()` | `.url()` | Valida formato de URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Debe ser un numero |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Debe ser `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Debe ser `false` |
| Date | `FormValidator.date()` | `.date()` | Debe ser una fecha valida |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | La fecha debe estar en el pasado |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | La fecha debe estar en el futuro |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | La edad debe ser mayor que N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | La edad debe ser menor que N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | La primera letra debe ser mayuscula |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Todos los caracteres deben ser minusculas |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Todos los caracteres deben ser mayusculas |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Formato de telefono de EE.UU. |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Formato de telefono del Reino Unido |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Formato de codigo postal de EE.UU. |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Formato de codigo postal del Reino Unido |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Debe coincidir con el patron regex |
| Equals | — | `.equals(otherValue)` | Debe ser igual a otro valor |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Funcion de validacion personalizada |

Todas las reglas aceptan un parametro opcional `message` para personalizar el mensaje de error.

<div id="creating-custom-validation-rules"></div>

## Crear Reglas de Validacion Personalizadas

Para crear una regla de validacion reutilizable, extiende la clase `FormRule`:

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

Usa `@{{attribute}}` como marcador de posicion en el `message` -- sera reemplazado con la etiqueta del campo en tiempo de ejecucion.

### Usando un FormRule Personalizado

Agrega tu regla personalizada a un `FormValidator` usando `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

O usa el metodo `.custom()` para reglas unicas sin crear una clase:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
