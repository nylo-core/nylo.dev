# Form

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione ai form")
- Per Iniziare
  - [Creare un Form](#creating-forms "Creare un form")
  - [Visualizzare un Form](#displaying-a-form "Visualizzare un form")
  - [Inviare un Form](#submitting-a-form "Inviare un form")
- Tipi di Campo
  - [Campi Testo](#text-fields "Campi testo")
  - [Campi Numero](#number-fields "Campi numero")
  - [Campi Password](#password-fields "Campi password")
  - [Campi Email](#email-fields "Campi email")
  - [Campi URL](#url-fields "Campi URL")
  - [Campi Area di Testo](#text-area-fields "Campi area di testo")
  - [Campi Numero di Telefono](#phone-number-fields "Campi numero di telefono")
  - [Iniziali Maiuscole per Parole](#capitalize-words-fields "Campi iniziali maiuscole per parole")
  - [Iniziale Maiuscola per Frasi](#capitalize-sentences-fields "Campi iniziale maiuscola per frasi")
  - [Campi Data](#date-fields "Campi data")
  - [Campi Data e Ora](#datetime-fields "Campi data e ora")
  - [Campi con Input Mascherato](#masked-input-fields "Campi con input mascherato")
  - [Campi Valuta](#currency-fields "Campi valuta")
  - [Campi Checkbox](#checkbox-fields "Campi checkbox")
  - [Campi Switch](#switch-box-fields "Campi switch")
  - [Campi Picker](#picker-fields "Campi picker")
  - [Campi Radio](#radio-fields "Campi radio")
  - [Campi Chip](#chip-fields "Campi chip")
  - [Campi Slider](#slider-fields "Campi slider")
  - [Campi Range Slider](#range-slider-fields "Campi range slider")
  - [Campi Personalizzati](#custom-fields "Campi personalizzati")
  - [Campi Widget](#widget-fields "Campi widget")
- [FormCollection](#form-collection "FormCollection")
- [Validazione del Form](#form-validation "Validazione del form")
- [Gestione dei Dati del Form](#managing-form-data "Gestione dei dati del form")
  - [Dati Iniziali](#initial-data "Dati iniziali")
  - [Impostare i Valori dei Campi](#setting-field-values "Impostare i valori dei campi")
  - [Impostare le Opzioni dei Campi](#setting-field-options "Impostare le opzioni dei campi")
  - [Leggere i Dati del Form](#reading-form-data "Leggere i dati del form")
  - [Cancellare i Dati](#clearing-data "Cancellare i dati")
  - [Aggiornare i Campi](#finding-and-updating-fields "Aggiornare i campi")
- [Pulsante di Invio](#submit-button "Pulsante di invio")
- [Layout del Form](#form-layout "Layout del form")
- [Visibilita' dei Campi](#field-visibility "Visibilita' dei campi")
- [Stile dei Campi](#field-styling "Stile dei campi")
- [Metodi Statici di NyFormWidget](#ny-form-widget-static-methods "Metodi statici di NyFormWidget")
- [Riferimento Costruttore di NyFormWidget](#ny-form-widget-constructor-reference "Riferimento costruttore di NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Riferimento Tutti i Tipi di Campo](#all-field-types-reference "Riferimento tutti i tipi di campo")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce un sistema di form costruito attorno a `NyFormWidget`. La tua classe form estende `NyFormWidget` ed **e'** il widget -- non serve un wrapper separato. I form supportano validazione integrata, molti tipi di campo, stile e gestione dei dati.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Creare un Form

Usa la CLI Metro per creare un nuovo form:

``` bash
metro make:form LoginForm
```

Questo crea `lib/app/forms/login_form.dart`:

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

I form estendono `NyFormWidget` e sovrascrivono il metodo `fields()` per definire i campi del form. Ogni campo usa un costruttore nominato come `Field.text()`, `Field.email()` o `Field.password()`. Il getter `static NyFormActions get actions` fornisce un modo conveniente per interagire con il form da qualsiasi punto della tua app.


<div id="displaying-a-form"></div>

## Visualizzare un Form

Poiche' la tua classe form estende `NyFormWidget`, **e'** il widget. Usalo direttamente nel tuo albero di widget:

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

## Inviare un Form

Ci sono tre modi per inviare un form:

### Utilizzando onSubmit e submitButton

Passa `onSubmit` e un `submitButton` quando costruisci il form. {{ config('app.name') }} fornisce pulsanti predefiniti che funzionano come pulsanti di invio:

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

Stili di pulsante disponibili: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Utilizzando NyFormActions

Usa il getter `actions` per inviare da qualsiasi punto:

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

### Utilizzando il metodo statico NyFormWidget.submit()

Invia un form per nome da qualsiasi punto:

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

Quando inviato, il form valida tutti i campi. Se valido, `onSuccess` viene chiamato con una `Map<String, dynamic>` dei dati dei campi (le chiavi sono versioni snake_case dei nomi dei campi). Se non valido, viene mostrato un toast di errore per impostazione predefinita e `onFailure` viene chiamato se fornito.


<div id="field-types"></div>

## Tipi di Campo

{{ config('app.name') }} v7 fornisce 22 tipi di campo tramite costruttori nominati sulla classe `Field`. Tutti i costruttori dei campi condividono questi parametri comuni:

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|-------------|-------------|
| `key` | `String` | Obbligatorio | L'identificatore del campo (posizionale) |
| `label` | `String?` | `null` | Etichetta personalizzata (predefinita alla chiave in title case) |
| `value` | `dynamic` | `null` | Valore iniziale |
| `validator` | `FormValidator?` | `null` | Regole di validazione |
| `autofocus` | `bool` | `false` | Auto-focus al caricamento |
| `dummyData` | `String?` | `null` | Dati di test/sviluppo |
| `header` | `Widget?` | `null` | Widget visualizzato sopra il campo |
| `footer` | `Widget?` | `null` | Widget visualizzato sotto il campo |
| `titleStyle` | `TextStyle?` | `null` | Stile personalizzato del testo dell'etichetta |
| `hidden` | `bool` | `false` | Nasconde il campo |
| `readOnly` | `bool?` | `null` | Rende il campo in sola lettura |
| `style` | `FieldStyle?` | Varia | Configurazione di stile specifica del campo |
| `onChanged` | `Function(dynamic)?` | `null` | Callback di cambio valore |

<div id="text-fields"></div>

### Campi Testo

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Tipo di stile: `FieldStyleTextField`

<div id="number-fields"></div>

### Campi Numero

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

Il parametro `decimal` controlla se l'input decimale e' consentito. Tipo di stile: `FieldStyleTextField`

<div id="password-fields"></div>

### Campi Password

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

Il parametro `viewable` aggiunge un toggle mostra/nascondi. Tipo di stile: `FieldStyleTextField`

<div id="email-fields"></div>

### Campi Email

``` dart
Field.email("Email", validator: FormValidator.email())
```

Imposta automaticamente il tipo di tastiera email e filtra gli spazi vuoti. Tipo di stile: `FieldStyleTextField`

<div id="url-fields"></div>

### Campi URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Imposta il tipo di tastiera URL. Tipo di stile: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Campi Area di Testo

``` dart
Field.textArea("Description")
```

Input di testo multi-riga. Tipo di stile: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Campi Numero di Telefono

``` dart
Field.phoneNumber("Mobile Phone")
```

Formatta automaticamente l'input del numero di telefono. Tipo di stile: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Iniziali Maiuscole per Parole

``` dart
Field.capitalizeWords("Full Name")
```

Rende maiuscola la prima lettera di ogni parola. Tipo di stile: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Iniziale Maiuscola per Frasi

``` dart
Field.capitalizeSentences("Bio")
```

Rende maiuscola la prima lettera di ogni frase. Tipo di stile: `FieldStyleTextField`

<div id="date-fields"></div>

### Campi Data

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)
```

Apre un selettore di data. Tipo di stile: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Campi Data e Ora

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment", dummyData: "2025-01-01 10:00")
```

Apre un selettore di data e ora. Tipo di stile: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Campi con Input Mascherato

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

Il carattere `#` nella maschera viene sostituito dall'input dell'utente. Usa `match` per controllare i caratteri consentiti. Quando `maskReturnValue` e' `true`, il valore restituito include la formattazione della maschera.

<div id="currency-fields"></div>

### Campi Valuta

``` dart
Field.currency("Price", currency: "usd")
```

Il parametro `currency` e' obbligatorio e determina il formato della valuta. Tipo di stile: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Campi Checkbox

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Tipo di stile: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Campi Switch

``` dart
Field.switchBox("Enable Notifications")
```

Tipo di stile: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Campi Picker

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

Il parametro `options` richiede una `FormCollection` (non una lista grezza). Vedi [FormCollection](#form-collection) per i dettagli. Tipo di stile: `FieldStylePicker`

<div id="radio-fields"></div>

### Campi Radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Il parametro `options` richiede una `FormCollection`. Tipo di stile: `FieldStyleRadio`

<div id="chip-fields"></div>

### Campi Chip

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Permette la selezione multipla tramite widget chip. Il parametro `options` richiede una `FormCollection`. Tipo di stile: `FieldStyleChip`

<div id="slider-fields"></div>

### Campi Slider

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

Tipo di stile: `FieldStyleSlider` -- configura `min`, `max`, `divisions`, colori, visualizzazione del valore e altro.

<div id="range-slider-fields"></div>

### Campi Range Slider

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

Restituisce un oggetto `RangeValues`. Tipo di stile: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Campi Personalizzati

Usa `Field.custom()` per fornire il tuo widget stateful personalizzato:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Il parametro `child` richiede un widget che estende `NyFieldStatefulWidget`. Questo ti da il pieno controllo sulla renderizzazione e il comportamento del campo.

<div id="widget-fields"></div>

### Campi Widget

Usa `Field.widget()` per incorporare qualsiasi widget all'interno del form senza che sia un campo del form:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

I campi widget non partecipano alla validazione o alla raccolta dati. Sono puramente per il layout.


<div id="form-collection"></div>

## FormCollection

I campi picker, radio e chip richiedono una `FormCollection` per le loro opzioni. `FormCollection` fornisce un'interfaccia unificata per gestire diversi formati di opzioni.

### Creare una FormCollection

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` rileva automaticamente il formato dei dati e delega al costruttore appropriato.

### FormOption

Ogni opzione in una `FormCollection` e' un `FormOption` con proprieta' `value` e `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Interrogare le Opzioni

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

## Validazione del Form

Aggiungi la validazione a qualsiasi campo utilizzando il parametro `validator` con `FormValidator`:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

Quando un form viene inviato, tutti i validatori vengono controllati. Se qualcuno fallisce, viene mostrato un toast di errore con il primo messaggio di errore e viene chiamato il callback `onFailure`.

**Vedi anche:** Per un elenco completo dei validatori disponibili, consulta la pagina [Validazione](/docs/7.x/validation#validation-rules).


<div id="managing-form-data"></div>

## Gestione dei Dati del Form

<div id="initial-data"></div>

### Dati Iniziali

Ci sono due modi per impostare i dati iniziali su un form.

**Opzione 1: Sovrascrivere il getter `init` nella classe del form**

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

Il getter `init` puo' restituire sia una `Map` sincrona che una `Future<Map>` asincrona. Le chiavi vengono abbinate ai nomi dei campi utilizzando la normalizzazione snake_case, quindi `"First Name"` si mappa a un campo con chiave `"First Name"`.

**Opzione 2: Passare `initialData` al widget del form**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Impostare i Valori dei Campi

Usa `NyFormActions` per impostare i valori dei campi da qualsiasi punto:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Impostare le Opzioni dei Campi

Aggiorna le opzioni sui campi picker, chip o radio dinamicamente:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Leggere i Dati del Form

I dati del form vengono acceduti tramite il callback `onSubmit` quando il form viene inviato, o tramite il callback `onChanged` per aggiornamenti in tempo reale:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Cancellare i Dati

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Aggiornare i Campi

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Pulsante di Invio

Passa un `submitButton` e un callback `onSubmit` quando costruisci il form:

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

Il `submitButton` viene automaticamente visualizzato sotto i campi del form. Puoi usare qualsiasi stile di pulsante integrato o un widget personalizzato.

Puoi anche usare qualsiasi widget come pulsante di invio passandolo come `footer`:

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

## Layout del Form

Posiziona i campi affiancati racchiudendoli in una `List`:

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

I campi in una `List` vengono renderizzati in una `Row` con larghezze `Expanded` uguali. La spaziatura tra i campi e' controllata dal parametro `crossAxisSpacing` su `NyFormWidget`.


<div id="field-visibility"></div>

## Visibilita' dei Campi

Mostra o nascondi i campi programmaticamente utilizzando i metodi `hide()` e `show()` su `Field`. Puoi accedere ai campi all'interno della tua classe form o tramite il callback `onChanged`:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

I campi nascosti non vengono renderizzati nell'UI ma esistono ancora nella lista dei campi del form.


<div id="field-styling"></div>

## Stile dei Campi

Ogni tipo di campo ha una sottoclasse `FieldStyle` corrispondente per lo stile:

| Tipo di Campo | Classe di Stile |
|--------------|-----------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Passa un oggetto di stile al parametro `style` di qualsiasi campo:

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

## Metodi Statici di NyFormWidget

`NyFormWidget` fornisce metodi statici per interagire con i form per nome da qualsiasi punto della tua app:

| Metodo | Descrizione |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Invia un form per nome |
| `NyFormWidget.stateRefresh(name)` | Aggiorna lo stato dell'UI del form |
| `NyFormWidget.stateSetValue(name, key, value)` | Imposta il valore di un campo per nome del form |
| `NyFormWidget.stateSetOptions(name, key, options)` | Imposta le opzioni di un campo per nome del form |
| `NyFormWidget.stateClearData(name)` | Cancella tutti i campi per nome del form |
| `NyFormWidget.stateRefreshForm(name)` | Aggiorna i campi del form (richiama `fields()`) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **Suggerimento:** Preferisci usare `NyFormActions` (vedi sotto) invece di chiamare direttamente questi metodi statici -- e' piu' conciso e meno soggetto a errori.


<div id="ny-form-widget-constructor-reference"></div>

## Riferimento Costruttore di NyFormWidget

Quando estendi `NyFormWidget`, questi sono i parametri del costruttore che puoi passare:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

Il callback `onChanged` riceve il `Field` che e' cambiato e il suo nuovo valore:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` fornisce un modo conveniente per interagire con un form da qualsiasi punto della tua app. Definiscilo come getter statico nella tua classe form:

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

### Azioni Disponibili

| Metodo | Descrizione |
|--------|-------------|
| `actions.updateField(key, value)` | Imposta il valore di un campo |
| `actions.clearField(key)` | Cancella un campo specifico |
| `actions.clear()` | Cancella tutti i campi |
| `actions.refresh()` | Aggiorna lo stato dell'UI del form |
| `actions.refreshForm()` | Richiama `fields()` e ricostruisci |
| `actions.setOptions(key, options)` | Imposta le opzioni sui campi picker/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Invia con validazione |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Override di NyFormWidget

Metodi che puoi sovrascrivere nella tua sottoclasse di `NyFormWidget`:

| Override | Descrizione |
|----------|-------------|
| `fields()` | Definisci i campi del form (obbligatorio) |
| `init` | Fornisci dati iniziali (sincroni o asincroni) |
| `onChange(field, data)` | Gestisci i cambiamenti dei campi internamente |


<div id="all-field-types-reference"></div>

## Riferimento Tutti i Tipi di Campo

| Costruttore | Parametri Chiave | Descrizione |
|-------------|-----------------|-------------|
| `Field.text()` | -- | Input di testo standard |
| `Field.email()` | -- | Input email con tipo di tastiera |
| `Field.password()` | `viewable` | Password con toggle di visibilita' opzionale |
| `Field.number()` | `decimal` | Input numerico, decimale opzionale |
| `Field.currency()` | `currency` (obbligatorio) | Input formattato come valuta |
| `Field.capitalizeWords()` | -- | Input testo in title case |
| `Field.capitalizeSentences()` | -- | Input testo con iniziale maiuscola per frase |
| `Field.textArea()` | -- | Input testo multi-riga |
| `Field.phoneNumber()` | -- | Numero di telefono auto-formattato |
| `Field.url()` | -- | Input URL con tipo di tastiera |
| `Field.mask()` | `mask` (obbligatorio), `match`, `maskReturnValue` | Input testo mascherato |
| `Field.date()` | -- | Selettore data |
| `Field.datetime()` | -- | Selettore data e ora |
| `Field.checkbox()` | -- | Checkbox booleano |
| `Field.switchBox()` | -- | Toggle switch booleano |
| `Field.picker()` | `options` (obbligatorio `FormCollection`) | Selezione singola da lista |
| `Field.radio()` | `options` (obbligatorio `FormCollection`) | Gruppo di pulsanti radio |
| `Field.chips()` | `options` (obbligatorio `FormCollection`) | Chip a selezione multipla |
| `Field.slider()` | -- | Slider a valore singolo |
| `Field.rangeSlider()` | -- | Slider a intervallo di valori |
| `Field.custom()` | `child` (obbligatorio `NyFieldStatefulWidget`) | Widget stateful personalizzato |
| `Field.widget()` | `child` (obbligatorio `Widget`) | Incorpora qualsiasi widget (non-campo) |

