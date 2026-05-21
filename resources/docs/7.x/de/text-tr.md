# TextTr

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [String-Interpolation](#string-interpolation "String-Interpolation")
- [Stil-Konstruktoren](#styled-constructors "Stil-Konstruktoren")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Einleitung

Das **TextTr**-Widget ist ein praktischer Wrapper um Flutters `Text`-Widget, der seinen Inhalt automatisch mithilfe des Lokalisierungssystems von {{ config('app.name') }} übersetzt.

Anstatt zu schreiben:

``` dart
Text("hello_world".tr())
```

Können Sie schreiben:

``` dart
TextTr("hello_world")
```

Dies macht Ihren Code sauberer und besser lesbar, besonders wenn Sie mit vielen übersetzten Strings arbeiten.

<div id="basic-usage"></div>

## Grundlegende Verwendung

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

Das Widget sucht den Übersetzungsschlüssel in Ihren Sprachdateien (z.B. `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

Wenn ein Schlüssel in der Datei des aktiven Locale fehlt, sucht {{ config('app.name') }} ihn automatisch in der Fallback-Sprache (konfiguriert in `lib/config/localization.dart`), bevor der rohe Schlüssel-String zurückgegeben wird. Dies gilt sowohl für Schlüssel auf oberster Ebene als auch für punktnotierte verschachtelte Schlüssel.

<div id="string-interpolation"></div>

## String-Interpolation

Verwenden Sie den Parameter `arguments`, um dynamische Werte in Ihre Übersetzungen einzufügen:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

In Ihrer Sprachdatei:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Ausgabe: **Hello, John!**

### Mehrere Argumente

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

Ausgabe: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Stil-Konstruktoren

`TextTr` bietet benannte Konstruktoren, die automatisch Textstile aus Ihrem Theme anwenden:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Verwendet den Stil `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Verwendet den Stil `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Verwendet den Stil `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Verwendet den Stil `Theme.of(context).textTheme.labelLarge`.

### Beispiel mit Stil-Konstruktoren

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## Parameter

`TextTr` unterstützt alle Standard-Parameter des `Text`-Widgets:

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `data` | `String` | Der nachzuschlagende Übersetzungsschlüssel |
| `arguments` | `Map<String, String>?` | Schlüssel-Wert-Paare für die String-Interpolation |
| `style` | `TextStyle?` | Textstil |
| `textAlign` | `TextAlign?` | Wie der Text ausgerichtet werden soll |
| `maxLines` | `int?` | Maximale Zeilenanzahl |
| `overflow` | `TextOverflow?` | Wie Überlauf behandelt werden soll |
| `softWrap` | `bool?` | Ob Text an weichen Umbrüchen umbrechen soll |
| `textDirection` | `TextDirection?` | Richtung des Textes |
| `locale` | `Locale?` | Locale für die Textdarstellung |
| `semanticsLabel` | `String?` | Barrierefreiheits-Label |

## Vergleich

| Ansatz | Code |
|--------|------|
| Traditionell | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Mit Argumenten | `TextTr("hello", arguments: {"name": "John"})` |
| Gestylt | `TextTr.headlineLarge("title")` |
