# InputField

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Validierung](#validation "Validierung")
- Varianten
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Eingabe-Maskierung](#masking "Eingabe-Maskierung")
- [Header und Footer](#header-footer "Header und Footer")
- [Löschbare Eingabe](#clearable "Löschbare Eingabe")
- [State-Management](#state-management "State-Management")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Einleitung

Das **InputField**-Widget ist {{ config('app.name') }}s erweitertes Textfeld mit integrierter Unterstützung für:

- Validierung mit anpassbaren Fehlermeldungen
- Passwort-Sichtbarkeitsumschalter
- Eingabe-Maskierung (Telefonnummern, Kreditkarten usw.)
- Header- und Footer-Widgets
- Löschbare Eingabe
- State-Management-Integration
- Dummy-Daten für die Entwicklung

<div id="basic-usage"></div>

## Grundlegende Verwendung

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

## Validierung

Verwenden Sie den Parameter `formValidator`, um Validierungsregeln hinzuzufügen:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Das Feld wird validiert, wenn der Benutzer den Fokus vom Feld weg bewegt.

### Benutzerdefinierter Validierungs-Handler

Behandeln Sie Validierungsfehler programmatisch:

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

Alle verfügbaren Validierungsregeln finden Sie in der [Validierung](/docs/7.x/validation)-Dokumentation.

<div id="password"></div>

## InputField.password

Ein vorkonfiguriertes Passwortfeld mit verdecktem Text und Sichtbarkeitsumschalter:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Passwortsichtbarkeit anpassen

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Ein vorkonfiguriertes E-Mail-Feld mit E-Mail-Tastatur und Autofokus:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Schreibt automatisch den ersten Buchstaben jedes Wortes groß:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Eingabe-Maskierung

Wenden Sie Eingabemasken für formatierte Daten wie Telefonnummern oder Kreditkarten an:

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

| Parameter | Beschreibung |
|-----------|-------------|
| `mask` | Das Maskenmuster mit `#` als Platzhalter |
| `maskMatch` | Regex-Muster für gültige Eingabezeichen |
| `maskedReturnValue` | Bei true wird der formatierte Wert zurückgegeben; bei false die Roheingabe |

<div id="header-footer"></div>

## Header und Footer

Fügen Sie Widgets über oder unter dem Eingabefeld hinzu:

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

## Löschbare Eingabe

Fügen Sie einen Lösch-Button hinzu, um das Feld schnell zu leeren:

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

## State-Management

Geben Sie Ihrem Eingabefeld einen State-Namen, um es programmatisch zu steuern:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### State-Aktionen

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

## Parameter

### Allgemeine Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `controller` | `TextEditingController` | erforderlich | Steuert den bearbeiteten Text |
| `labelText` | `String?` | - | Beschriftung über dem Feld |
| `hintText` | `String?` | - | Platzhaltertext |
| `formValidator` | `FormValidator?` | - | Validierungsregeln |
| `validateOnFocusChange` | `bool` | `true` | Validieren bei Fokuswechsel |
| `obscureText` | `bool` | `false` | Eingabe verbergen (für Passwörter) |
| `keyboardType` | `TextInputType` | `text` | Tastaturtyp |
| `autoFocus` | `bool` | `false` | Autofokus beim Aufbau |
| `readOnly` | `bool` | `false` | Feld schreibgeschützt machen |
| `enabled` | `bool?` | - | Feld aktivieren/deaktivieren |
| `maxLines` | `int?` | `1` | Maximale Zeilen |
| `maxLength` | `int?` | - | Maximale Zeichen |

### Styling-Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `backgroundColor` | `Color?` | Hintergrundfarbe des Feldes |
| `borderRadius` | `BorderRadius?` | Rahmenradius |
| `border` | `InputBorder?` | Standard-Rahmen |
| `focusedBorder` | `InputBorder?` | Rahmen bei Fokus |
| `enabledBorder` | `InputBorder?` | Rahmen bei Aktivierung |
| `contentPadding` | `EdgeInsetsGeometry?` | Innerer Abstand |
| `style` | `TextStyle?` | Textstil |
| `labelStyle` | `TextStyle?` | Beschriftungs-Textstil |
| `hintStyle` | `TextStyle?` | Hinweis-Textstil |
| `prefixIcon` | `Widget?` | Symbol vor der Eingabe |

### Maskierungs-Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `mask` | `String?` | Maskenmuster (z.B. "###-####") |
| `maskMatch` | `String?` | Regex für gültige Zeichen |
| `maskedReturnValue` | `bool?` | Maskierten oder Rohwert zurückgeben |

### Funktions-Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `header` | `Widget?` | Widget über dem Feld |
| `footer` | `Widget?` | Widget unter dem Feld |
| `clearable` | `bool?` | Lösch-Button anzeigen |
| `clearIcon` | `Widget?` | Benutzerdefiniertes Lösch-Symbol |
| `passwordVisible` | `bool?` | Passwort-Umschalter anzeigen |
| `passwordViewable` | `bool?` | Passwort-Sichtbarkeitsumschalter erlauben |
| `dummyData` | `String?` | Testdaten für die Entwicklung |
| `stateName` | `String?` | Name für State-Management |
| `onChanged` | `Function(String)?` | Wird bei Wertänderung aufgerufen |
