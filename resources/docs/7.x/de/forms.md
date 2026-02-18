# Formulare

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Erste Schritte
  - [Ein Formular erstellen](#creating-forms "Ein Formular erstellen")
  - [Ein Formular anzeigen](#displaying-a-form "Ein Formular anzeigen")
  - [Ein Formular absenden](#submitting-a-form "Ein Formular absenden")
- Feldtypen
  - [Textfelder](#text-fields "Textfelder")
  - [Zahlenfelder](#number-fields "Zahlenfelder")
  - [Passwortfelder](#password-fields "Passwortfelder")
  - [E-Mail-Felder](#email-fields "E-Mail-Felder")
  - [URL-Felder](#url-fields "URL-Felder")
  - [Textbereich-Felder](#text-area-fields "Textbereich-Felder")
  - [Telefonnummer-Felder](#phone-number-fields "Telefonnummer-Felder")
  - [Wörter großschreiben](#capitalize-words-fields "Wörter großschreiben")
  - [Sätze großschreiben](#capitalize-sentences-fields "Sätze großschreiben")
  - [Datumsfelder](#date-fields "Datumsfelder")
  - [Datum-Zeit-Felder](#datetime-fields "Datum-Zeit-Felder")
  - [Maskierte Eingabefelder](#masked-input-fields "Maskierte Eingabefelder")
  - [Währungsfelder](#currency-fields "Währungsfelder")
  - [Checkbox-Felder](#checkbox-fields "Checkbox-Felder")
  - [Switch-Box-Felder](#switch-box-fields "Switch-Box-Felder")
  - [Picker-Felder](#picker-fields "Picker-Felder")
  - [Radio-Felder](#radio-fields "Radio-Felder")
  - [Chip-Felder](#chip-fields "Chip-Felder")
  - [Slider-Felder](#slider-fields "Slider-Felder")
  - [Range-Slider-Felder](#range-slider-fields "Range-Slider-Felder")
  - [Benutzerdefinierte Felder](#custom-fields "Benutzerdefinierte Felder")
  - [Widget-Felder](#widget-fields "Widget-Felder")
- [FormCollection](#form-collection "FormCollection")
- [Formularvalidierung](#form-validation "Formularvalidierung")
- [Formulardaten verwalten](#managing-form-data "Formulardaten verwalten")
  - [Anfangsdaten](#initial-data "Anfangsdaten")
  - [Feldwerte setzen](#setting-field-values "Feldwerte setzen")
  - [Feldoptionen setzen](#setting-field-options "Feldoptionen setzen")
  - [Formulardaten lesen](#reading-form-data "Formulardaten lesen")
  - [Daten löschen](#clearing-data "Daten löschen")
  - [Felder aktualisieren](#finding-and-updating-fields "Felder aktualisieren")
- [Absende-Button](#submit-button "Absende-Button")
- [Formularlayout](#form-layout "Formularlayout")
- [Feldsichtbarkeit](#field-visibility "Feldsichtbarkeit")
- [Feldgestaltung](#field-styling "Feldgestaltung")
- [NyFormWidget statische Methoden](#ny-form-widget-static-methods "NyFormWidget statische Methoden")
- [NyFormWidget Konstruktor-Referenz](#ny-form-widget-constructor-reference "NyFormWidget Konstruktor-Referenz")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Alle Feldtypen-Referenz](#all-field-types-reference "Alle Feldtypen-Referenz")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet ein Formularsystem, das um `NyFormWidget` aufgebaut ist. Ihre Formularklasse erweitert `NyFormWidget` und **ist** das Widget -- kein separater Wrapper nötig. Formulare unterstützen integrierte Validierung, viele Feldtypen, Gestaltung und Datenverwaltung.

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

## Ein Formular erstellen

Verwenden Sie die Metro CLI, um ein neues Formular zu erstellen:

``` bash
metro make:form LoginForm
```

Dies erstellt `lib/app/forms/login_form.dart`:

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

Formulare erweitern `NyFormWidget` und überschreiben die Methode `fields()`, um Formularfelder zu definieren. Jedes Feld verwendet einen benannten Konstruktor wie `Field.text()`, `Field.email()` oder `Field.password()`. Der `static NyFormActions get actions`-Getter bietet eine bequeme Möglichkeit, von überall in Ihrer App mit dem Formular zu interagieren.


<div id="displaying-a-form"></div>

## Ein Formular anzeigen

Da Ihre Formularklasse `NyFormWidget` erweitert, **ist** sie das Widget. Verwenden Sie es direkt in Ihrem Widget-Baum:

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

## Ein Formular absenden

Es gibt drei Möglichkeiten, ein Formular abzusenden:

### Verwendung von onSubmit und submitButton

Übergeben Sie `onSubmit` und einen `submitButton` beim Erstellen des Formulars. {{ config('app.name') }} bietet vorgefertigte Buttons, die als Absende-Buttons funktionieren:

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

Verfügbare Button-Stile: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Verwendung von NyFormActions

Verwenden Sie den `actions`-Getter, um von überall abzusenden:

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

### Verwendung der statischen Methode NyFormWidget.submit()

Senden Sie ein Formular über seinen Namen von überall ab:

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

Beim Absenden validiert das Formular alle Felder. Wenn gültig, wird `onSuccess` mit einer `Map<String, dynamic>` der Felddaten aufgerufen (Schlüssel sind snake_case-Versionen der Feldnamen). Wenn ungültig, wird standardmäßig ein Toast-Fehler angezeigt und `onFailure` aufgerufen, falls angegeben.


<div id="field-types"></div>

## Feldtypen

{{ config('app.name') }} v7 bietet 22 Feldtypen über benannte Konstruktoren der `Field`-Klasse. Alle Feldkonstruktoren teilen diese gemeinsamen Parameter:

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `key` | `String` | Erforderlich | Der Feldbezeichner (positionell) |
| `label` | `String?` | `null` | Benutzerdefiniertes Anzeigelabel (Standard ist key in Titel-Schreibweise) |
| `value` | `dynamic` | `null` | Anfangswert |
| `validator` | `FormValidator?` | `null` | Validierungsregeln |
| `autofocus` | `bool` | `false` | Automatischer Fokus beim Laden |
| `dummyData` | `String?` | `null` | Test-/Entwicklungsdaten |
| `header` | `Widget?` | `null` | Widget oberhalb des Feldes |
| `footer` | `Widget?` | `null` | Widget unterhalb des Feldes |
| `titleStyle` | `TextStyle?` | `null` | Benutzerdefinierter Label-Textstil |
| `hidden` | `bool` | `false` | Feld verstecken |
| `readOnly` | `bool?` | `null` | Feld schreibgeschützt machen |
| `style` | `FieldStyle?` | Variiert | Feldspezifische Stilkonfiguration |
| `onChanged` | `Function(dynamic)?` | `null` | Wertänderungs-Callback |

<div id="text-fields"></div>

### Textfelder

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Stiltyp: `FieldStyleTextField`

<div id="number-fields"></div>

### Zahlenfelder

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

Der Parameter `decimal` steuert, ob Dezimaleingaben erlaubt sind. Stiltyp: `FieldStyleTextField`

<div id="password-fields"></div>

### Passwortfelder

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

Der Parameter `viewable` fügt einen Anzeigen/Verbergen-Schalter hinzu. Stiltyp: `FieldStyleTextField`

<div id="email-fields"></div>

### E-Mail-Felder

``` dart
Field.email("Email", validator: FormValidator.email())
```

Setzt automatisch den E-Mail-Tastaturtyp und filtert Leerzeichen. Stiltyp: `FieldStyleTextField`

<div id="url-fields"></div>

### URL-Felder

``` dart
Field.url("Website", validator: FormValidator.url())
```

Setzt den URL-Tastaturtyp. Stiltyp: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Textbereich-Felder

``` dart
Field.textArea("Description")
```

Mehrzeilige Texteingabe. Stiltyp: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Telefonnummer-Felder

``` dart
Field.phoneNumber("Mobile Phone")
```

Formatiert automatisch die Telefonnummerneingabe. Stiltyp: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Wörter großschreiben

``` dart
Field.capitalizeWords("Full Name")
```

Großschreibung des ersten Buchstabens jedes Wortes. Stiltyp: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Sätze großschreiben

``` dart
Field.capitalizeSentences("Bio")
```

Großschreibung des ersten Buchstabens jedes Satzes. Stiltyp: `FieldStyleTextField`

<div id="date-fields"></div>

### Datumsfelder

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Disable the clear button
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Custom clear icon
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Öffnet einen Datumswähler. Standardmäßig zeigt das Feld einen Löschen-Button, mit dem Benutzer den Wert zurücksetzen können. Setzen Sie `canClear: false`, um ihn auszublenden, oder verwenden Sie `clearIconData`, um das Symbol zu ändern. Stiltyp: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Datum-Zeit-Felder

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Öffnet einen Datum- und Zeitwähler. Sie können `firstDate`, `lastDate`, `dateFormat` und `initialPickerDateTime` direkt als Parameter der obersten Ebene setzen. Stiltyp: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Maskierte Eingabefelder

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

Das `#`-Zeichen in der Maske wird durch Benutzereingaben ersetzt. Verwenden Sie `match`, um die erlaubten Zeichen zu steuern. Wenn `maskReturnValue` `true` ist, enthält der zurückgegebene Wert die Maskenformatierung.

<div id="currency-fields"></div>

### Währungsfelder

``` dart
Field.currency("Price", currency: "usd")
```

Der Parameter `currency` ist erforderlich und bestimmt das Währungsformat. Stiltyp: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Checkbox-Felder

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Stiltyp: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Switch-Box-Felder

``` dart
Field.switchBox("Enable Notifications")
```

Stiltyp: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Picker-Felder

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

Der Parameter `options` erfordert eine `FormCollection` (keine rohe Liste). Siehe [FormCollection](#form-collection) für Details. Stiltyp: `FieldStylePicker`

#### Listenkachel-Stile

Sie können das Erscheinungsbild von Elementen im Bottom Sheet des Pickers mit `PickerListTileStyle` anpassen. Standardmäßig zeigt das Bottom Sheet einfache Text-Kacheln an. Verwenden Sie die integrierten Vorlagen, um Auswahlmarkierungen hinzuzufügen, oder stellen Sie einen vollständig benutzerdefinierten Builder bereit.

**Radio-Stil** — zeigt ein Radio-Button-Symbol als führendes Widget:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// With a custom active color
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Häkchen-Stil** — zeigt ein Häkchen-Symbol als nachfolgendes Widget bei Auswahl:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Benutzerdefinierter Builder** — volle Kontrolle über das Widget jeder Kachel:

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

Beide vordefinierten Stile unterstützen auch `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` und `selectedTileColor`:

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

### Radio-Felder

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Der Parameter `options` erfordert eine `FormCollection`. Stiltyp: `FieldStyleRadio`

<div id="chip-fields"></div>

### Chip-Felder

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

Ermöglicht Mehrfachauswahl über Chip-Widgets. Der Parameter `options` erfordert eine `FormCollection`. Stiltyp: `FieldStyleChip`

<div id="slider-fields"></div>

### Slider-Felder

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

Stiltyp: `FieldStyleSlider` -- konfigurieren Sie `min`, `max`, `divisions`, Farben, Wertanzeige und mehr.

<div id="range-slider-fields"></div>

### Range-Slider-Felder

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

Gibt ein `RangeValues`-Objekt zurück. Stiltyp: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Benutzerdefinierte Felder

Verwenden Sie `Field.custom()`, um Ihr eigenes zustandsbehaftetes Widget bereitzustellen:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Der Parameter `child` erfordert ein Widget, das `NyFieldStatefulWidget` erweitert. Dies gibt Ihnen volle Kontrolle über das Rendering und Verhalten des Feldes.

<div id="widget-fields"></div>

### Widget-Felder

Verwenden Sie `Field.widget()`, um jedes Widget in das Formular einzubetten, ohne dass es ein Formularfeld ist:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Widget-Felder nehmen nicht an der Validierung oder Datensammlung teil. Sie dienen ausschließlich dem Layout.


<div id="form-collection"></div>

## FormCollection

Picker-, Radio- und Chip-Felder erfordern eine `FormCollection` für ihre Optionen. `FormCollection` bietet eine einheitliche Schnittstelle für verschiedene Optionsformate.

### Eine FormCollection erstellen

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

`FormCollection.from()` erkennt automatisch das Datenformat und delegiert an den entsprechenden Konstruktor.

### FormOption

Jede Option in einer `FormCollection` ist eine `FormOption` mit den Eigenschaften `value` und `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Optionen abfragen

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

## Formularvalidierung

Fügen Sie jedem Feld Validierung hinzu, indem Sie den Parameter `validator` mit `FormValidator` verwenden:

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

Beim Absenden eines Formulars werden alle Validatoren geprüft. Bei einem Fehler wird ein Toast mit der ersten Fehlermeldung angezeigt und der `onFailure`-Callback aufgerufen.

**Siehe auch:** Eine vollständige Liste der verfügbaren Validatoren finden Sie auf der Seite [Validierung](/docs/7.x/validation#validation-rules).


<div id="managing-form-data"></div>

## Formulardaten verwalten

<div id="initial-data"></div>

### Anfangsdaten

Es gibt zwei Möglichkeiten, Anfangsdaten für ein Formular zu setzen.

**Option 1: Überschreiben des `init`-Getters in Ihrer Formularklasse**

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

Der `init`-Getter kann entweder eine synchrone `Map` oder eine asynchrone `Future<Map>` zurückgeben. Schlüssel werden über snake_case-Normalisierung den Feldnamen zugeordnet, sodass `"First Name"` einem Feld mit dem Schlüssel `"First Name"` zugeordnet wird.

#### Verwendung von `define()` in init

Verwenden Sie den `define()`-Helfer, wenn Sie **Optionen** (oder sowohl einen Wert als auch Optionen) für ein Feld in `init` setzen müssen. Dies ist nützlich für Picker-, Chip- und Radio-Felder, bei denen die Optionen von einer API oder einer anderen asynchronen Quelle stammen.

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

`define()` akzeptiert zwei benannte Parameter:

| Parameter | Beschreibung |
|-----------|-------------|
| `value` | Der Anfangswert für das Feld |
| `options` | Die Optionen für Picker-, Chip- oder Radio-Felder |

``` dart
// Set only options (no initial value)
"Category": define(options: categories),

// Set only an initial value
"Price": define(value: "100"),

// Set both a value and options
"Country": define(value: "us", options: countries),

// Plain values still work for simple fields
"Name": "John",
```

An `define()` übergebene Optionen können eine `List`, `Map` oder `FormCollection` sein. Sie werden automatisch in eine `FormCollection` umgewandelt, wenn sie angewendet werden.

**Option 2: `initialData` an das Formular-Widget übergeben**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Feldwerte setzen

Verwenden Sie `NyFormActions`, um Feldwerte von überall zu setzen:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Feldoptionen setzen

Optionen bei Picker-, Chip- oder Radio-Feldern dynamisch aktualisieren:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Formulardaten lesen

Formulardaten werden über den `onSubmit`-Callback beim Absenden des Formulars abgerufen, oder über den `onChanged`-Callback für Echtzeit-Updates:

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

### Daten löschen

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Felder aktualisieren

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Absende-Button

Übergeben Sie einen `submitButton` und einen `onSubmit`-Callback beim Erstellen des Formulars:

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

Der `submitButton` wird automatisch unterhalb der Formularfelder angezeigt. Sie können jeden der integrierten Button-Stile oder ein benutzerdefiniertes Widget verwenden.

Sie können auch jedes Widget als Absende-Button verwenden, indem Sie es als `footer` übergeben:

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

## Formularlayout

Platzieren Sie Felder nebeneinander, indem Sie sie in eine `List` einschließen:

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

Felder in einer `List` werden in einer `Row` mit gleichen `Expanded`-Breiten gerendert. Der Abstand zwischen den Feldern wird durch den Parameter `crossAxisSpacing` auf `NyFormWidget` gesteuert.


<div id="field-visibility"></div>

## Feldsichtbarkeit

Felder programmatisch ein- oder ausblenden mit den Methoden `hide()` und `show()` auf `Field`. Sie können innerhalb Ihrer Formularklasse oder über den `onChanged`-Callback auf Felder zugreifen:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

Versteckte Felder werden in der UI nicht gerendert, existieren aber weiterhin in der Feldliste des Formulars.


<div id="field-styling"></div>

## Feldgestaltung

Jeder Feldtyp hat eine entsprechende `FieldStyle`-Unterklasse für die Gestaltung:

| Feldtyp | Stilklasse |
|---------|-----------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Übergeben Sie ein Stil-Objekt an den Parameter `style` eines beliebigen Feldes:

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

## NyFormWidget statische Methoden

`NyFormWidget` bietet statische Methoden, um mit Formularen über ihren Namen von überall in Ihrer App zu interagieren:

| Methode | Beschreibung |
|---------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Formular über seinen Namen absenden |
| `NyFormWidget.stateRefresh(name)` | UI-Status des Formulars aktualisieren |
| `NyFormWidget.stateSetValue(name, key, value)` | Feldwert über Formularnamen setzen |
| `NyFormWidget.stateSetOptions(name, key, options)` | Feldoptionen über Formularnamen setzen |
| `NyFormWidget.stateClearData(name)` | Alle Felder über Formularnamen löschen |
| `NyFormWidget.stateRefreshForm(name)` | Formularfelder aktualisieren (ruft `fields()` erneut auf) |

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

> **Tipp:** Verwenden Sie bevorzugt `NyFormActions` (siehe unten) anstatt diese statischen Methoden direkt aufzurufen -- es ist kürzer und weniger fehleranfällig.


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget Konstruktor-Referenz

Beim Erweitern von `NyFormWidget` sind dies die Konstruktor-Parameter, die Sie übergeben können:

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

Der `onChanged`-Callback empfängt das geänderte `Field` und seinen neuen Wert:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` bietet eine bequeme Möglichkeit, von überall in Ihrer App mit einem Formular zu interagieren. Definieren Sie es als statischen Getter in Ihrer Formularklasse:

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

### Verfügbare Aktionen

| Methode | Beschreibung |
|---------|-------------|
| `actions.updateField(key, value)` | Wert eines Feldes setzen |
| `actions.clearField(key)` | Bestimmtes Feld löschen |
| `actions.clear()` | Alle Felder löschen |
| `actions.refresh()` | UI-Status des Formulars aktualisieren |
| `actions.refreshForm()` | `fields()` erneut aufrufen und neu aufbauen |
| `actions.setOptions(key, options)` | Optionen bei Picker/Chip/Radio-Feldern setzen |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Absenden mit Validierung |

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

### NyFormWidget-Überschreibungen

Methoden, die Sie in Ihrer `NyFormWidget`-Unterklasse überschreiben können:

| Überschreibung | Beschreibung |
|----------|-------------|
| `fields()` | Formularfelder definieren (erforderlich) |
| `init` | Anfangsdaten bereitstellen (synchron oder asynchron) |
| `onChange(field, data)` | Feldänderungen intern behandeln |


<div id="all-field-types-reference"></div>

## Alle Feldtypen-Referenz

| Konstruktor | Schlüsselparameter | Beschreibung |
|-------------|-------------------|-------------|
| `Field.text()` | -- | Standard-Texteingabe |
| `Field.email()` | -- | E-Mail-Eingabe mit Tastaturtyp |
| `Field.password()` | `viewable` | Passwort mit optionalem Sichtbarkeitsumschalter |
| `Field.number()` | `decimal` | Numerische Eingabe, optionale Dezimalstellen |
| `Field.currency()` | `currency` (erforderlich) | Währungsformatierte Eingabe |
| `Field.capitalizeWords()` | -- | Texteingabe mit Titelschreibung |
| `Field.capitalizeSentences()` | -- | Texteingabe mit Satzschreibung |
| `Field.textArea()` | -- | Mehrzeilige Texteingabe |
| `Field.phoneNumber()` | -- | Automatisch formatierte Telefonnummer |
| `Field.url()` | -- | URL-Eingabe mit Tastaturtyp |
| `Field.mask()` | `mask` (erforderlich), `match`, `maskReturnValue` | Maskierte Texteingabe |
| `Field.date()` | -- | Datumswähler |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Datum- und Zeitwähler |
| `Field.checkbox()` | -- | Boolesche Checkbox |
| `Field.switchBox()` | -- | Boolescher Kippschalter |
| `Field.picker()` | `options` (erforderlich `FormCollection`) | Einzelauswahl aus Liste |
| `Field.radio()` | `options` (erforderlich `FormCollection`) | Radiobutton-Gruppe |
| `Field.chips()` | `options` (erforderlich `FormCollection`) | Mehrfachauswahl-Chips |
| `Field.slider()` | -- | Einzelwert-Slider |
| `Field.rangeSlider()` | -- | Bereichswert-Slider |
| `Field.custom()` | `child` (erforderlich `NyFieldStatefulWidget`) | Benutzerdefiniertes zustandsbehaftetes Widget |
| `Field.widget()` | `child` (erforderlich `Widget`) | Beliebiges Widget einbetten (kein Feld) |

