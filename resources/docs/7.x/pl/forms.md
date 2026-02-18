# Forms

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do formularzy")
- Rozpoczęcie pracy
  - [Tworzenie formularza](#creating-forms "Tworzenie formularzy")
  - [Wyświetlanie formularza](#displaying-a-form "Wyświetlanie formularza")
  - [Wysyłanie formularza](#submitting-a-form "Wysyłanie formularza")
- Typy pól
  - [Pola tekstowe](#text-fields "Pola tekstowe")
  - [Pola numeryczne](#number-fields "Pola numeryczne")
  - [Pola hasła](#password-fields "Pola hasła")
  - [Pola e-mail](#email-fields "Pola e-mail")
  - [Pola URL](#url-fields "Pola URL")
  - [Pola tekstowe wielowierszowe](#text-area-fields "Pola tekstowe wielowierszowe")
  - [Pola numeru telefonu](#phone-number-fields "Pola numeru telefonu")
  - [Wielkie litery wyrazów](#capitalize-words-fields "Wielkie litery wyrazów")
  - [Wielkie litery zdań](#capitalize-sentences-fields "Wielkie litery zdań")
  - [Pola daty](#date-fields "Pola daty")
  - [Pola daty i czasu](#datetime-fields "Pola daty i czasu")
  - [Pola z maską](#masked-input-fields "Pola z maską")
  - [Pola waluty](#currency-fields "Pola waluty")
  - [Pola wyboru (checkbox)](#checkbox-fields "Pola wyboru")
  - [Pola przełącznika](#switch-box-fields "Pola przełącznika")
  - [Pola listy wyboru](#picker-fields "Pola listy wyboru")
  - [Pola radio](#radio-fields "Pola radio")
  - [Pola chip](#chip-fields "Pola chip")
  - [Pola suwaka](#slider-fields "Pola suwaka")
  - [Pola suwaka zakresu](#range-slider-fields "Pola suwaka zakresu")
  - [Pola niestandardowe](#custom-fields "Pola niestandardowe")
  - [Pola widgetów](#widget-fields "Pola widgetów")
- [FormCollection](#form-collection "FormCollection")
- [Walidacja formularza](#form-validation "Walidacja formularza")
- [Zarządzanie danymi formularza](#managing-form-data "Zarządzanie danymi formularza")
  - [Dane początkowe](#initial-data "Dane początkowe")
  - [Ustawianie wartości pól](#setting-field-values "Ustawianie wartości pól")
  - [Ustawianie opcji pól](#setting-field-options "Ustawianie opcji pól")
  - [Odczytywanie danych formularza](#reading-form-data "Odczytywanie danych formularza")
  - [Czyszczenie danych](#clearing-data "Czyszczenie danych")
  - [Aktualizowanie pól](#finding-and-updating-fields "Aktualizowanie pól")
- [Przycisk wysyłania](#submit-button "Przycisk wysyłania")
- [Układ formularza](#form-layout "Układ formularza")
- [Widoczność pól](#field-visibility "Widoczność pól")
- [Stylizacja pól](#field-styling "Stylizacja pól")
- [Metody statyczne NyFormWidget](#ny-form-widget-static-methods "Metody statyczne NyFormWidget")
- [Referencja konstruktora NyFormWidget](#ny-form-widget-constructor-reference "Referencja konstruktora NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Referencja wszystkich typów pól](#all-field-types-reference "Referencja wszystkich typów pól")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 udostępnia system formularzy oparty na `NyFormWidget`. Twoja klasa formularza rozszerza `NyFormWidget` i **jest** widgetem — nie potrzeba osobnego wrappera. Formularze obsługują wbudowaną walidację, wiele typów pól, stylizację i zarządzanie danymi.

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

## Tworzenie formularza

Użyj Metro CLI, aby utworzyć nowy formularz:

``` bash
metro make:form LoginForm
```

To tworzy plik `lib/app/forms/login_form.dart`:

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

Formularze rozszerzają `NyFormWidget` i nadpisują metodę `fields()`, aby zdefiniować pola formularza. Każde pole używa nazwanego konstruktora, takiego jak `Field.text()`, `Field.email()` lub `Field.password()`. Getter `static NyFormActions get actions` zapewnia wygodny sposób interakcji z formularzem z dowolnego miejsca w aplikacji.


<div id="displaying-a-form"></div>

## Wyświetlanie formularza

Ponieważ Twoja klasa formularza rozszerza `NyFormWidget`, **jest** widgetem. Użyj go bezpośrednio w drzewie widgetów:

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

## Wysyłanie formularza

Istnieją trzy sposoby wysyłania formularza:

### Użycie onSubmit i submitButton

Przekaż `onSubmit` i `submitButton` podczas tworzenia formularza. {{ config('app.name') }} udostępnia gotowe przyciski, które działają jako przyciski wysyłania:

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

Dostępne style przycisków: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Użycie NyFormActions

Użyj gettera `actions` do wysyłania z dowolnego miejsca:

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

### Użycie metody statycznej NyFormWidget.submit()

Wyślij formularz po nazwie z dowolnego miejsca:

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

Podczas wysyłania formularz waliduje wszystkie pola. Jeśli walidacja przejdzie pomyślnie, wywoływany jest `onSuccess` z mapą `Map<String, dynamic>` danych pól (klucze to wersje snake_case nazw pól). Jeśli walidacja się nie powiedzie, domyślnie wyświetlany jest komunikat o błędzie toast, a jeśli podano `onFailure`, zostanie on wywołany.


<div id="field-types"></div>

## Typy pól

{{ config('app.name') }} v7 udostępnia 22 typy pól poprzez nazwane konstruktory klasy `Field`. Wszystkie konstruktory pól współdzielą następujące wspólne parametry:

| Parametr | Typ | Domyślna wartość | Opis |
|----------|-----|------------------|------|
| `key` | `String` | Wymagany | Identyfikator pola (pozycyjny) |
| `label` | `String?` | `null` | Niestandardowa etykieta wyświetlania (domyślnie klucz w formacie tytułowym) |
| `value` | `dynamic` | `null` | Wartość początkowa |
| `validator` | `FormValidator?` | `null` | Reguły walidacji |
| `autofocus` | `bool` | `false` | Automatyczne ustawienie fokusu przy ładowaniu |
| `dummyData` | `String?` | `null` | Dane testowe/deweloperskie |
| `header` | `Widget?` | `null` | Widget wyświetlany powyżej pola |
| `footer` | `Widget?` | `null` | Widget wyświetlany poniżej pola |
| `titleStyle` | `TextStyle?` | `null` | Niestandardowy styl tekstu etykiety |
| `hidden` | `bool` | `false` | Ukrycie pola |
| `readOnly` | `bool?` | `null` | Pole tylko do odczytu |
| `style` | `FieldStyle?` | Różne | Konfiguracja stylu specyficzna dla pola |
| `onChanged` | `Function(dynamic)?` | `null` | Callback przy zmianie wartości |

<div id="text-fields"></div>

### Pola tekstowe

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Typ stylu: `FieldStyleTextField`

<div id="number-fields"></div>

### Pola numeryczne

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

Parametr `decimal` kontroluje, czy dozwolone jest wprowadzanie liczb dziesiętnych. Typ stylu: `FieldStyleTextField`

<div id="password-fields"></div>

### Pola hasła

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

Parametr `viewable` dodaje przełącznik pokaż/ukryj. Typ stylu: `FieldStyleTextField`

<div id="email-fields"></div>

### Pola e-mail

``` dart
Field.email("Email", validator: FormValidator.email())
```

Automatycznie ustawia typ klawiatury e-mail i filtruje białe znaki. Typ stylu: `FieldStyleTextField`

<div id="url-fields"></div>

### Pola URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Ustawia typ klawiatury URL. Typ stylu: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Pola tekstowe wielowierszowe

``` dart
Field.textArea("Description")
```

Wielowierszowe pole tekstowe. Typ stylu: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Pola numeru telefonu

``` dart
Field.phoneNumber("Mobile Phone")
```

Automatycznie formatuje wprowadzany numer telefonu. Typ stylu: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Wielkie litery wyrazów

``` dart
Field.capitalizeWords("Full Name")
```

Zamienia pierwszą literę każdego wyrazu na wielką. Typ stylu: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Wielkie litery zdań

``` dart
Field.capitalizeSentences("Bio")
```

Zamienia pierwszą literę każdego zdania na wielką. Typ stylu: `FieldStyleTextField`

<div id="date-fields"></div>

### Pola daty

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

Otwiera selektor daty. Domyślnie pole wyświetla przycisk czyszczenia, który pozwala użytkownikom zresetować wartość. Ustaw `canClear: false`, aby go ukryć, lub użyj `clearIconData`, aby zmienić ikonę. Typ stylu: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Pola daty i czasu

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Otwiera selektor daty i czasu. Możesz ustawić `firstDate`, `lastDate`, `dateFormat` i `initialPickerDateTime` bezpośrednio jako parametry najwyższego poziomu. Typ stylu: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Pola z maską

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

Znak `#` w masce jest zastępowany przez dane wejściowe użytkownika. Użyj `match`, aby kontrolować dozwolone znaki. Gdy `maskReturnValue` jest ustawione na `true`, zwracana wartość zawiera formatowanie maski.

<div id="currency-fields"></div>

### Pola waluty

``` dart
Field.currency("Price", currency: "usd")
```

Parametr `currency` jest wymagany i określa format waluty. Typ stylu: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Pola wyboru (checkbox)

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Typ stylu: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Pola przełącznika

``` dart
Field.switchBox("Enable Notifications")
```

Typ stylu: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Pola listy wyboru

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

Parametr `options` wymaga `FormCollection` (nie surowej listy). Zobacz [FormCollection](#form-collection), aby poznać szczegóły. Typ stylu: `FieldStylePicker`

#### Style kafelków listy

Możesz dostosować wygląd elementów w dolnym arkuszu selektora za pomocą `PickerListTileStyle`. Domyślnie dolny arkusz pokazuje zwykłe kafelki tekstowe. Użyj wbudowanych presetów, aby dodać wskaźniki zaznaczenia, lub zapewnij w pełni niestandardowy builder.

**Styl radio** — wyświetla ikonę przycisku radio jako widget wiodący:

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

**Styl ze znacznikiem** — wyświetla ikonę zaznaczenia jako widget końcowy, gdy element jest wybrany:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Niestandardowy builder** — pełna kontrola nad widgetem każdego kafelka:

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

Oba style presetowe obsługują również `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` i `selectedTileColor`:

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

### Pola radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Parametr `options` wymaga `FormCollection`. Typ stylu: `FieldStyleRadio`

<div id="chip-fields"></div>

### Pola chip

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

Umożliwia wielokrotny wybór za pomocą widgetów chip. Parametr `options` wymaga `FormCollection`. Typ stylu: `FieldStyleChip`

<div id="slider-fields"></div>

### Pola suwaka

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

Typ stylu: `FieldStyleSlider` — konfiguruj `min`, `max`, `divisions`, kolory, wyświetlanie wartości i więcej.

<div id="range-slider-fields"></div>

### Pola suwaka zakresu

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

Zwraca obiekt `RangeValues`. Typ stylu: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Pola niestandardowe

Użyj `Field.custom()`, aby dostarczyć własny widget stanowy:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Parametr `child` wymaga widgetu rozszerzającego `NyFieldStatefulWidget`. Daje to pełną kontrolę nad renderowaniem i zachowaniem pola.

<div id="widget-fields"></div>

### Pola widgetów

Użyj `Field.widget()`, aby osadzić dowolny widget wewnątrz formularza bez bycia polem formularza:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Pola widgetów nie uczestniczą w walidacji ani zbieraniu danych. Służą wyłącznie do układu.


<div id="form-collection"></div>

## FormCollection

Pola picker, radio i chip wymagają `FormCollection` dla swoich opcji. `FormCollection` zapewnia ujednolicony interfejs do obsługi różnych formatów opcji.

### Tworzenie FormCollection

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

`FormCollection.from()` automatycznie wykrywa format danych i deleguje do odpowiedniego konstruktora.

### FormOption

Każda opcja w `FormCollection` to `FormOption` z właściwościami `value` i `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Zapytania o opcje

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

## Walidacja formularza

Dodaj walidację do dowolnego pola za pomocą parametru `validator` z `FormValidator`:

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

Podczas wysyłania formularza sprawdzane są wszystkie walidatory. Jeśli którykolwiek nie przejdzie, wyświetlany jest komunikat o błędzie toast z pierwszym komunikatem o błędzie, a callback `onFailure` zostaje wywołany.

**Zobacz także:** [Walidacja](/docs/7.x/validation#validation-rules), aby zobaczyć pełną listę dostępnych walidatorów.


<div id="managing-form-data"></div>

## Zarządzanie danymi formularza

<div id="initial-data"></div>

### Dane początkowe

Istnieją dwa sposoby ustawienia danych początkowych formularza.

**Opcja 1: Nadpisanie gettera `init` w klasie formularza**

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

Getter `init` może zwrócić synchroniczną `Map` lub asynchroniczną `Future<Map>`. Klucze są dopasowywane do nazw pól za pomocą normalizacji snake_case, więc `"First Name"` mapuje się na pole z kluczem `"First Name"`.

#### Użycie `define()` w init

Użyj helpera `define()`, gdy musisz ustawić **opcje** (lub zarówno wartość, jak i opcje) dla pola w `init`. Jest to przydatne dla pól picker, chip i radio, gdzie opcje pochodzą z API lub innego asynchronicznego źródła.

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

`define()` przyjmuje dwa nazwane parametry:

| Parametr | Opis |
|----------|------|
| `value` | Wartość początkowa pola |
| `options` | Opcje dla pól picker, chip lub radio |

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

Opcje przekazywane do `define()` mogą być `List`, `Map` lub `FormCollection`. Są automatycznie konwertowane do `FormCollection` podczas stosowania.

**Opcja 2: Przekazanie `initialData` do widgetu formularza**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Ustawianie wartości pól

Użyj `NyFormActions`, aby ustawić wartości pól z dowolnego miejsca:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Ustawianie opcji pól

Dynamicznie aktualizuj opcje w polach picker, chip lub radio:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Odczytywanie danych formularza

Dane formularza są dostępne przez callback `onSubmit` podczas wysyłania formularza lub przez callback `onChanged` do aktualizacji w czasie rzeczywistym:

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

### Czyszczenie danych

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Aktualizowanie pól

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Przycisk wysyłania

Przekaż `submitButton` i callback `onSubmit` podczas tworzenia formularza:

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

`submitButton` jest automatycznie wyświetlany pod polami formularza. Możesz użyć dowolnego wbudowanego stylu przycisku lub niestandardowego widgetu.

Możesz również użyć dowolnego widgetu jako przycisku wysyłania, przekazując go jako `footer`:

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

## Układ formularza

Umieść pola obok siebie, owijając je w `List`:

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

Pola w `List` są renderowane w `Row` z równymi szerokościami `Expanded`. Odstępy między polami są kontrolowane przez parametr `crossAxisSpacing` w `NyFormWidget`.


<div id="field-visibility"></div>

## Widoczność pól

Pokaż lub ukryj pola programowo za pomocą metod `hide()` i `show()` na `Field`. Możesz uzyskać dostęp do pól wewnątrz klasy formularza lub przez callback `onChanged`:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

Ukryte pola nie są renderowane w interfejsie, ale nadal istnieją na liście pól formularza.


<div id="field-styling"></div>

## Stylizacja pól

Każdy typ pola ma odpowiadającą mu podklasę `FieldStyle` do stylizacji:

| Typ pola | Klasa stylu |
|----------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Przekaż obiekt stylu do parametru `style` dowolnego pola:

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

## Metody statyczne NyFormWidget

`NyFormWidget` udostępnia metody statyczne do interakcji z formularzami po nazwie z dowolnego miejsca w aplikacji:

| Metoda | Opis |
|--------|------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Wyślij formularz po nazwie |
| `NyFormWidget.stateRefresh(name)` | Odśwież stan UI formularza |
| `NyFormWidget.stateSetValue(name, key, value)` | Ustaw wartość pola po nazwie formularza |
| `NyFormWidget.stateSetOptions(name, key, options)` | Ustaw opcje pola po nazwie formularza |
| `NyFormWidget.stateClearData(name)` | Wyczyść wszystkie pola po nazwie formularza |
| `NyFormWidget.stateRefreshForm(name)` | Odśwież pola formularza (ponownie wywołuje `fields()`) |

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

> **Wskazówka:** Preferuj użycie `NyFormActions` (zobacz poniżej) zamiast bezpośredniego wywoływania tych metod statycznych — jest to bardziej zwięzłe i mniej podatne na błędy.


<div id="ny-form-widget-constructor-reference"></div>

## Referencja konstruktora NyFormWidget

Rozszerzając `NyFormWidget`, możesz przekazać następujące parametry konstruktora:

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

Callback `onChanged` otrzymuje `Field`, który się zmienił, oraz jego nową wartość:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` zapewnia wygodny sposób interakcji z formularzem z dowolnego miejsca w aplikacji. Zdefiniuj go jako statyczny getter w klasie formularza:

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

### Dostępne akcje

| Metoda | Opis |
|--------|------|
| `actions.updateField(key, value)` | Ustaw wartość pola |
| `actions.clearField(key)` | Wyczyść konkretne pole |
| `actions.clear()` | Wyczyść wszystkie pola |
| `actions.refresh()` | Odśwież stan UI formularza |
| `actions.refreshForm()` | Ponownie wywołaj `fields()` i przebuduj |
| `actions.setOptions(key, options)` | Ustaw opcje w polach picker/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Wyślij z walidacją |

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

### Nadpisania NyFormWidget

Metody, które możesz nadpisać w podklasie `NyFormWidget`:

| Nadpisanie | Opis |
|------------|------|
| `fields()` | Definiuje pola formularza (wymagane) |
| `init` | Dostarcza dane początkowe (synchroniczne lub asynchroniczne) |
| `onChange(field, data)` | Obsługuje zmiany pól wewnętrznie |


<div id="all-field-types-reference"></div>

## Referencja wszystkich typów pól

| Konstruktor | Kluczowe parametry | Opis |
|-------------|-------------------|------|
| `Field.text()` | — | Standardowe pole tekstowe |
| `Field.email()` | — | Pole e-mail z typem klawiatury |
| `Field.password()` | `viewable` | Hasło z opcjonalnym przełącznikiem widoczności |
| `Field.number()` | `decimal` | Pole numeryczne, opcjonalnie dziesiętne |
| `Field.currency()` | `currency` (wymagany) | Pole z formatowaniem waluty |
| `Field.capitalizeWords()` | — | Pole tekstowe z wielkimi literami wyrazów |
| `Field.capitalizeSentences()` | — | Pole tekstowe z wielkimi literami zdań |
| `Field.textArea()` | — | Wielowierszowe pole tekstowe |
| `Field.phoneNumber()` | — | Automatycznie formatowany numer telefonu |
| `Field.url()` | — | Pole URL z typem klawiatury |
| `Field.mask()` | `mask` (wymagany), `match`, `maskReturnValue` | Pole tekstowe z maską |
| `Field.date()` | — | Selektor daty |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Selektor daty i czasu |
| `Field.checkbox()` | — | Pole wyboru (boolean) |
| `Field.switchBox()` | — | Przełącznik (boolean) |
| `Field.picker()` | `options` (wymagany `FormCollection`) | Wybór jednego elementu z listy |
| `Field.radio()` | `options` (wymagany `FormCollection`) | Grupa przycisków radio |
| `Field.chips()` | `options` (wymagany `FormCollection`) | Wielokrotny wybór chipów |
| `Field.slider()` | — | Suwak pojedynczej wartości |
| `Field.rangeSlider()` | — | Suwak zakresu wartości |
| `Field.custom()` | `child` (wymagany `NyFieldStatefulWidget`) | Niestandardowy widget stanowy |
| `Field.widget()` | `child` (wymagany `Widget`) | Osadzenie dowolnego widgetu (nie-pole) |
