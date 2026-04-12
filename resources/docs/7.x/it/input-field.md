# InputField

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Validazione](#validation "Validazione")
- Varianti
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Mascheramento dell'Input](#masking "Mascheramento dell'Input")
- [Intestazione e Pie' di Pagina](#header-footer "Intestazione e Pie' di Pagina")
- [Input Cancellabile](#clearable "Input Cancellabile")
- [Gestione dello Stato](#state-management "Gestione dello Stato")
- [Parametri](#parameters "Parametri")


<div id="introduction"></div>

## Introduzione

Il widget **InputField** e' il campo di testo avanzato di {{ config('app.name') }} con supporto integrato per:

- Validazione con messaggi di errore personalizzabili
- Toggle di visibilita' della password
- Mascheramento dell'input (numeri di telefono, carte di credito, ecc.)
- Widget intestazione e pie' di pagina
- Input cancellabile
- Integrazione con la gestione dello stato
- Dati fittizi per lo sviluppo

<div id="basic-usage"></div>

## Utilizzo Base

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

## Validazione

Usa il parametro `formValidator` per aggiungere regole di validazione:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Il campo si validera' quando l'utente sposta il focus altrove.

### Gestore di Validazione Personalizzato

Gestisci gli errori di validazione in modo programmatico:

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

Consulta tutte le regole di validazione disponibili nella documentazione [Validazione](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Un campo password preconfigurato con testo oscurato e toggle di visibilita':

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Personalizzazione della Visibilita' della Password

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Icona toggle mostra/nascondi
  passwordViewable: true, // Consenti all'utente di alternare la visibilita'
)
```

<div id="email-address"></div>

## InputField.emailAddress

Un campo email preconfigurato con tastiera email e autofocus:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Capitalizza automaticamente la prima lettera di ogni parola:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Mascheramento dell'Input

Applica maschere di input per dati formattati come numeri di telefono o carte di credito:

``` dart
// Maschera numero di telefono
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Restituisce il valore non mascherato: 1234567890
)

// Maschera carta di credito
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Restituisce il valore mascherato: 1234 5678 9012 3456
)
```

| Parametro | Descrizione |
|-----------|-------------|
| `mask` | Il modello di maschera usando `#` come segnaposto |
| `maskMatch` | Pattern regex per i caratteri di input validi |
| `maskedReturnValue` | Se true, restituisce il valore formattato; se false, restituisce l'input grezzo |

<div id="header-footer"></div>

## Intestazione e Pie' di Pagina

Aggiungi widget sopra o sotto il campo di input:

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

## Input Cancellabile

Aggiungi un pulsante di cancellazione per svuotare rapidamente il campo:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Icona di cancellazione personalizzata
  onChanged: (value) {
    // Gestisci la ricerca
  },
)
```

<div id="state-management"></div>

## Gestione dello Stato

Assegna un nome di stato al tuo campo di input per controllarlo in modo programmatico:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Azioni sullo Stato

``` dart
final actions = InputField.stateActions("username_field");

// Cancella il campo
actions.clear();

// Imposta un valore
actions.setValue("new_value");

// Sposta il focus sul campo
actions.focus();

// Rimuovi il focus dal campo
actions.unfocus();

// Alterna la visibilita' della password (per InputField.password)
actions.toggleObscure();
```

<div id="parameters"></div>

## Parametri

### Parametri Comuni

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | obbligatorio | Controlla il testo in fase di modifica |
| `labelText` | `String?` | - | Etichetta mostrata sopra il campo |
| `hintText` | `String?` | - | Testo segnaposto |
| `formValidator` | `FormValidator?` | - | Regole di validazione |
| `validateOnFocusChange` | `bool` | `true` | Valida al cambio di focus |
| `obscureText` | `bool` | `false` | Nascondi l'input (per le password) |
| `keyboardType` | `TextInputType` | `text` | Tipo di tastiera |
| `autoFocus` | `bool` | `false` | Focus automatico alla creazione |
| `readOnly` | `bool` | `false` | Rendi il campo di sola lettura |
| `enabled` | `bool?` | - | Abilita/disabilita il campo |
| `maxLines` | `int?` | `1` | Numero massimo di righe |
| `maxLength` | `int?` | - | Numero massimo di caratteri |

### Parametri di Stile

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | Colore di sfondo del campo |
| `borderRadius` | `BorderRadius?` | Raggio del bordo |
| `border` | `InputBorder?` | Bordo predefinito |
| `focusedBorder` | `InputBorder?` | Bordo quando in focus |
| `enabledBorder` | `InputBorder?` | Bordo quando abilitato |
| `contentPadding` | `EdgeInsetsGeometry?` | Padding interno |
| `style` | `TextStyle?` | Stile del testo |
| `labelStyle` | `TextStyle?` | Stile del testo dell'etichetta |
| `hintStyle` | `TextStyle?` | Stile del testo segnaposto |
| `prefixIcon` | `Widget?` | Icona prima dell'input |
| `suffixIcon` | `Widget?` | Icona dopo l'input |

### Parametri di Mascheramento

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `mask` | `String?` | Modello di maschera (es. "###-####") |
| `maskMatch` | `String?` | Regex per i caratteri validi |
| `maskedReturnValue` | `bool?` | Restituisci valore mascherato o grezzo |

### Parametri delle Funzionalita'

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `header` | `Widget?` | Widget sopra il campo |
| `footer` | `Widget?` | Widget sotto il campo |
| `clearable` | `bool?` | Mostra pulsante di cancellazione |
| `clearIcon` | `Widget?` | Icona di cancellazione personalizzata |
| `passwordVisible` | `bool?` | Mostra toggle della password |
| `passwordViewable` | `bool?` | Consenti il toggle della visibilita' della password |
| `dummyData` | `String?` | Dati fittizi per lo sviluppo |
| `stateName` | `String?` | Nome per la gestione dello stato |
| `enableInteractiveSelection` | `bool` | Abilita la selezione del testo (predefinito: `true`) |
| `onChanged` | `Function(String)?` | Chiamato quando il valore cambia |
