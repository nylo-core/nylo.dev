# InputField

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Walidacja](#validation "Walidacja")
- Warianty
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Maskowanie danych wejściowych](#masking "Maskowanie danych wejściowych")
- [Nagłówek i stopka](#header-footer "Nagłówek i stopka")
- [Pole z możliwością czyszczenia](#clearable "Pole z możliwością czyszczenia")
- [Zarządzanie stanem](#state-management "Zarządzanie stanem")
- [Parametry](#parameters "Parametry")


<div id="introduction"></div>

## Wprowadzenie

Widget **InputField** to rozszerzone pole tekstowe {{ config('app.name') }} z wbudowaną obsługą:

- Walidacji z konfigurowalnymi komunikatami o błędach
- Przełącznika widoczności hasła
- Maskowania danych wejściowych (numery telefonów, karty kredytowe itp.)
- Widgetów nagłówka i stopki
- Pola z możliwością czyszczenia
- Integracji z zarządzaniem stanem
- Danych testowych do celów deweloperskich

<div id="basic-usage"></div>

## Podstawowe użycie

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

## Walidacja

Użyj parametru `formValidator`, aby dodać reguły walidacji:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Pole zostanie zwalidowane, gdy użytkownik przeniesie fokus z pola.

### Niestandardowa obsługa walidacji

Obsługuj błędy walidacji programowo:

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

Zobacz wszystkie dostępne reguły walidacji w dokumentacji [Walidacja](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Wstępnie skonfigurowane pole hasła z zamaskowanym tekstem i przełącznikiem widoczności:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Personalizacja widoczności hasła

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Wstępnie skonfigurowane pole e-mail z klawiaturą e-mail i autofokusem:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Automatycznie zamienia pierwszą literę każdego wyrazu na wielką:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Maskowanie danych wejściowych

Zastosuj maski do formatowanych danych, takich jak numery telefonów lub karty kredytowe:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Parametr | Opis |
|----------|------|
| `mask` | Wzorzec maski z użyciem `#` jako symbolu zastępczego |
| `maskMatch` | Wzorzec regex dla prawidłowych znaków wejściowych |
| `maskedReturnValue` | Jeśli true, zwraca sformatowaną wartość; jeśli false, zwraca surowe dane wejściowe |

<div id="header-footer"></div>

## Nagłówek i stopka

Dodaj widgety powyżej lub poniżej pola tekstowego:

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

## Pole z możliwością czyszczenia

Dodaj przycisk czyszczenia, aby szybko wyczyścić pole:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## Zarządzanie stanem

Nadaj polu tekstowemu nazwę stanu, aby kontrolować je programowo:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Akcje stanu

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Parametry

### Wspólne parametry

| Parametr | Typ | Domyślna wartość | Opis |
|----------|-----|------------------|------|
| `controller` | `TextEditingController` | wymagany | Kontroler edytowanego tekstu |
| `labelText` | `String?` | - | Etykieta wyświetlana nad polem |
| `hintText` | `String?` | - | Tekst zastępczy |
| `formValidator` | `FormValidator?` | - | Reguły walidacji |
| `validateOnFocusChange` | `bool` | `true` | Walidacja przy zmianie fokusu |
| `obscureText` | `bool` | `false` | Ukrywanie tekstu (dla haseł) |
| `keyboardType` | `TextInputType` | `text` | Typ klawiatury |
| `autoFocus` | `bool` | `false` | Autofokus przy budowaniu |
| `readOnly` | `bool` | `false` | Pole tylko do odczytu |
| `enabled` | `bool?` | - | Włączenie/wyłączenie pola |
| `maxLines` | `int?` | `1` | Maksymalna liczba linii |
| `maxLength` | `int?` | - | Maksymalna liczba znaków |

### Parametry stylizacji

| Parametr | Typ | Opis |
|----------|-----|------|
| `backgroundColor` | `Color?` | Kolor tła pola |
| `borderRadius` | `BorderRadius?` | Zaokrąglenie krawędzi |
| `border` | `InputBorder?` | Domyślna krawędź |
| `focusedBorder` | `InputBorder?` | Krawędź przy fokusie |
| `enabledBorder` | `InputBorder?` | Krawędź gdy włączone |
| `contentPadding` | `EdgeInsetsGeometry?` | Wewnętrzne wypełnienie |
| `style` | `TextStyle?` | Styl tekstu |
| `labelStyle` | `TextStyle?` | Styl tekstu etykiety |
| `hintStyle` | `TextStyle?` | Styl tekstu wskazówki |
| `prefixIcon` | `Widget?` | Ikona przed polem |

### Parametry maskowania

| Parametr | Typ | Opis |
|----------|-----|------|
| `mask` | `String?` | Wzorzec maski (np. "###-####") |
| `maskMatch` | `String?` | Regex dla prawidłowych znaków |
| `maskedReturnValue` | `bool?` | Zwraca wartość zamaskowaną lub surową |

### Parametry funkcji

| Parametr | Typ | Opis |
|----------|-----|------|
| `header` | `Widget?` | Widget powyżej pola |
| `footer` | `Widget?` | Widget poniżej pola |
| `clearable` | `bool?` | Wyświetlanie przycisku czyszczenia |
| `clearIcon` | `Widget?` | Niestandardowa ikona czyszczenia |
| `passwordVisible` | `bool?` | Przełącznik widoczności hasła |
| `passwordViewable` | `bool?` | Zezwolenie na przełączanie widoczności hasła |
| `dummyData` | `String?` | Dane testowe do celów deweloperskich |
| `stateName` | `String?` | Nazwa do zarządzania stanem |
| `onChanged` | `Function(String)?` | Wywołanie przy zmianie wartości |
