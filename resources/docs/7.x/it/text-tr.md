# TextTr

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Interpolazione di Stringhe](#string-interpolation "Interpolazione di Stringhe")
- [Costruttori con Stile](#styled-constructors "Costruttori con Stile")
- [Parametri](#parameters "Parametri")


<div id="introduction"></div>

## Introduzione

Il widget **TextTr** e' un wrapper di convenienza attorno al widget `Text` di Flutter che traduce automaticamente il suo contenuto usando il sistema di localizzazione di {{ config('app.name') }}.

Invece di scrivere:

``` dart
Text("hello_world".tr())
```

Puoi scrivere:

``` dart
TextTr("hello_world")
```

Questo rende il tuo codice piu' pulito e leggibile, specialmente quando hai a che fare con molte stringhe tradotte.

<div id="basic-usage"></div>

## Utilizzo Base

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

Il widget cerchera' la chiave di traduzione nei tuoi file di lingua (es. `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Interpolazione di Stringhe

Usa il parametro `arguments` per inserire valori dinamici nelle tue traduzioni:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

Nel tuo file di lingua:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Output: **Hello, John!**

### Argomenti Multipli

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

Output: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Costruttori con Stile

`TextTr` fornisce costruttori nominati che applicano automaticamente stili di testo dal tuo tema:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Usa lo stile `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Usa lo stile `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Usa lo stile `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Usa lo stile `Theme.of(context).textTheme.labelLarge`.

### Esempio con Costruttori con Stile

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

## Parametri

`TextTr` supporta tutti i parametri standard del widget `Text`:

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `data` | `String` | La chiave di traduzione da cercare |
| `arguments` | `Map<String, String>?` | Coppie chiave-valore per l'interpolazione di stringhe |
| `style` | `TextStyle?` | Stile del testo |
| `textAlign` | `TextAlign?` | Come il testo deve essere allineato |
| `maxLines` | `int?` | Numero massimo di righe |
| `overflow` | `TextOverflow?` | Come gestire l'overflow |
| `softWrap` | `bool?` | Se mandare a capo il testo ai punti di interruzione morbidi |
| `textDirection` | `TextDirection?` | Direzione del testo |
| `locale` | `Locale?` | Locale per il rendering del testo |
| `semanticsLabel` | `String?` | Etichetta per l'accessibilita' |

## Confronto

| Approccio | Codice |
|----------|------|
| Tradizionale | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Con argomenti | `TextTr("hello", arguments: {"name": "John"})` |
| Con stile | `TextTr.headlineLarge("title")` |
