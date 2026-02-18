# Validazione

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Fondamenti
  - [Validare i Dati con check()](#validating-data "Validare i Dati con check()")
  - [Risultati della Validazione](#validation-results "Risultati della Validazione")
- FormValidator
  - [Utilizzare FormValidator](#using-form-validator "Utilizzare FormValidator")
  - [Costruttori Nominati di FormValidator](#form-validator-named-constructors "Costruttori Nominati di FormValidator")
  - [Concatenare Regole di Validazione](#chaining-validation-rules "Concatenare Regole di Validazione")
  - [Validazione Personalizzata](#custom-validation "Validazione Personalizzata")
  - [Utilizzare FormValidator con i Campi](#form-validator-with-fields "Utilizzare FormValidator con i Campi")
- [Regole di Validazione Disponibili](#validation-rules "Regole di Validazione Disponibili")
- [Creare Regole di Validazione Personalizzate](#creating-custom-validation-rules "Creare Regole di Validazione Personalizzate")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce un sistema di validazione costruito attorno alla classe `FormValidator`. Puoi validare i dati all'interno delle pagine utilizzando il metodo `check()`, o usare `FormValidator` direttamente per la validazione standalone e a livello di campo.

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

## Validare i Dati con check()

All'interno di qualsiasi `NyPage`, usa il metodo `check()` per validare i dati. Accetta una callback che riceve una lista di validatori. Usa `.that()` per aggiungere dati e concatenare regole:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // Tutte le validazioni superate
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Validazione fallita
      print(bag.firstErrorMessage);
    });
  }
}
```

### Come Funziona check()

1. `check()` crea una `List<FormValidator>` vuota
2. La tua callback usa `.that(data)` per aggiungere un nuovo `FormValidator` con dati alla lista
3. Ogni `.that()` restituisce un `FormValidator` su cui puoi concatenare regole
4. Dopo la callback, ogni validatore nella lista viene controllato
5. I risultati vengono raccolti in un `FormValidationResponseBag`

### Validare Campi Multipli

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

Il parametro opzionale `label` imposta un nome leggibile utilizzato nei messaggi di errore (es. "The Email must be a valid email address.").

<div id="validation-results"></div>

## Risultati della Validazione

Il metodo `check()` restituisce un `FormValidationResponseBag` (una `List<FormValidationResult>`), che puoi anche ispezionare direttamente:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // Ottieni il primo messaggio di errore
  String? error = bag.firstErrorMessage;
  print(error);

  // Ottieni tutti i risultati falliti
  List<FormValidationResult> errors = bag.validationErrors;

  // Ottieni tutti i risultati riusciti
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Ogni `FormValidationResult` rappresenta il risultato della validazione di un singolo valore:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // Primo messaggio di errore
  String? message = result.getFirstErrorMessage();

  // Tutti i messaggi di errore
  List<String> messages = result.errorMessages();

  // Risposte di errore
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## Utilizzare FormValidator

`FormValidator` puo' essere utilizzato standalone o con i campi del form.

### Utilizzo Standalone

``` dart
// Utilizzando un costruttore nominato
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### Con Dati nel Costruttore

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Costruttori Nominati di FormValidator

{{ config('app.name') }} v7 fornisce costruttori nominati per le validazioni comuni:

``` dart
// Validazione email
FormValidator.email(message: "Please enter a valid email")

// Validazione password (forza 1 o 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// Numeri di telefono
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// Validazione URL
FormValidator.url()

// Vincoli di lunghezza
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// Vincoli di valore
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// Vincoli di dimensione (per liste/stringhe)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// Non vuoto
FormValidator.notEmpty(message: "This field is required")

// Contiene valori
FormValidator.contains(['option1', 'option2'])

// Inizia/finisce con
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Controlli booleani
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// Numerico
FormValidator.numeric()

// Validazioni di data
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// Maiuscole/minuscole
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Formati di localizzazione
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Pattern regex
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// Validazione personalizzata
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## Concatenare Regole di Validazione

Concatena piu' regole in modo fluente utilizzando i metodi di istanza. Ogni metodo restituisce il `FormValidator`, permettendoti di costruire le regole:

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

Tutti i costruttori nominati hanno metodi di istanza concatenabili corrispondenti:

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

## Validazione Personalizzata

### Regola Personalizzata (Inline)

Usa `.custom()` per aggiungere logica di validazione inline:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Restituisci true se valido, false se non valido
    return !_takenUsernames.contains(data);
  },
)
```

Oppure concatenala con altre regole:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Validazione di Uguaglianza

Verifica se un valore corrisponde a un altro:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Utilizzare FormValidator con i Campi

`FormValidator` si integra con i widget `Field` nei form. Passa un validatore al parametro `validator`:

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

Puoi anche usare validatori concatenati con i campi:

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

## Regole di Validazione Disponibili

Tutte le regole disponibili per `FormValidator`, sia come costruttori nominati che come metodi concatenabili:

| Regola | Costruttore Nominato | Metodo Concatenabile | Descrizione |
|--------|---------------------|---------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Valida il formato email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Forza 1: 8+ caratteri, 1 maiuscola, 1 cifra. Forza 2: + 1 carattere speciale |
| Non Vuoto | `FormValidator.notEmpty()` | `.notEmpty()` | Non puo' essere vuoto |
| Lunghezza Min | `FormValidator.minLength(5)` | `.minLength(5)` | Lunghezza minima della stringa |
| Lunghezza Max | `FormValidator.maxLength(100)` | `.maxLength(100)` | Lunghezza massima della stringa |
| Valore Min | `FormValidator.minValue(18)` | `.minValue(18)` | Valore numerico minimo (funziona anche su lunghezza stringa, lunghezza lista, lunghezza mappa) |
| Valore Max | `FormValidator.maxValue(100)` | `.maxValue(100)` | Valore numerico massimo |
| Dimensione Min | `FormValidator.minSize(2)` | `.minSize(2)` | Dimensione minima per liste/stringhe |
| Dimensione Max | `FormValidator.maxSize(5)` | `.maxSize(5)` | Dimensione massima per liste/stringhe |
| Contiene | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Il valore deve contenere uno dei valori dati |
| Inizia Con | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | La stringa deve iniziare con il prefisso |
| Finisce Con | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | La stringa deve finire con il suffisso |
| URL | `FormValidator.url()` | `.url()` | Valida il formato URL |
| Numerico | `FormValidator.numeric()` | `.numeric()` | Deve essere un numero |
| Booleano Vero | `FormValidator.booleanTrue()` | `.booleanTrue()` | Deve essere `true` |
| Booleano Falso | `FormValidator.booleanFalse()` | `.booleanFalse()` | Deve essere `false` |
| Data | `FormValidator.date()` | `.date()` | Deve essere una data valida |
| Data nel Passato | `FormValidator.dateInPast()` | `.dateInPast()` | La data deve essere nel passato |
| Data nel Futuro | `FormValidator.dateInFuture()` | `.dateInFuture()` | La data deve essere nel futuro |
| Eta' Maggiore | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | L'eta' deve essere maggiore di N |
| Eta' Minore | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | L'eta' deve essere minore di N |
| Capitalizzato | `FormValidator.capitalized()` | `.capitalized()` | La prima lettera deve essere maiuscola |
| Minuscolo | `FormValidator.lowercase()` | `.lowercase()` | Tutti i caratteri devono essere minuscoli |
| Maiuscolo | `FormValidator.uppercase()` | `.uppercase()` | Tutti i caratteri devono essere maiuscoli |
| Telefono US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Formato numero di telefono US |
| Telefono UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Formato numero di telefono UK |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Formato zipcode US |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Formato postcode UK |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Deve corrispondere al pattern regex |
| Uguale | -- | `.equals(otherValue)` | Deve essere uguale a un altro valore |
| Personalizzato | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Funzione di validazione personalizzata |

Tutte le regole accettano un parametro opzionale `message` per personalizzare il messaggio di errore.

<div id="creating-custom-validation-rules"></div>

## Creare Regole di Validazione Personalizzate

Per creare una regola di validazione riutilizzabile, estendi la classe `FormRule`:

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
    // Username: alfanumerico, underscore, 3-20 caratteri
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

Usa `@{{attribute}}` come segnaposto nel `message` -- verra' sostituito con l'etichetta del campo a runtime.

### Utilizzare un FormRule Personalizzato

Aggiungi la tua regola personalizzata a un `FormValidator` utilizzando `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Oppure usa il metodo `.custom()` per regole una tantum senza creare una classe:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
