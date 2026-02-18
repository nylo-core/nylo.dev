# TextTr

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe uzycie](#basic-usage "Podstawowe uzycie")
- [Interpolacja ciagow](#string-interpolation "Interpolacja ciagow")
- [Stylizowane konstruktory](#styled-constructors "Stylizowane konstruktory")
- [Parametry](#parameters "Parametry")


<div id="introduction"></div>

## Wprowadzenie

Widget **TextTr** to wygodny wrapper wokol widgetu `Text` Flutter, ktory automatycznie tlumaczy swoja zawartosc za pomoca systemu lokalizacji {{ config('app.name') }}.

Zamiast pisac:

``` dart
Text("hello_world".tr())
```

Mozesz napisac:

``` dart
TextTr("hello_world")
```

To sprawia, ze Twoj kod jest czystszy i bardziej czytelny, szczegolnie przy pracy z wieloma tlumaczonymi ciagami.

<div id="basic-usage"></div>

## Podstawowe uzycie

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

Widget wyszuka klucz tlumaczenia w Twoich plikach jezykowych (np. `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Interpolacja ciagow

Uzyj parametru `arguments`, aby wstawic dynamiczne wartosci do tlumaczen:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

W pliku jezykowym:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Wynik: **Hello, John!**

### Wiele argumentow

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

Wynik: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Stylizowane konstruktory

`TextTr` udostepnia nazwane konstruktory, ktore automatycznie stosuja style tekstu z motywu:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Uzywa stylu `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Uzywa stylu `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Uzywa stylu `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Uzywa stylu `Theme.of(context).textTheme.labelLarge`.

### Przyklad ze stylizowanymi konstruktorami

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

## Parametry

`TextTr` obsluguje wszystkie standardowe parametry widgetu `Text`:

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `data` | `String` | Klucz tlumaczenia do wyszukania |
| `arguments` | `Map<String, String>?` | Pary klucz-wartosc do interpolacji ciagow |
| `style` | `TextStyle?` | Stylizacja tekstu |
| `textAlign` | `TextAlign?` | Sposob wyrownania tekstu |
| `maxLines` | `int?` | Maksymalna liczba linii |
| `overflow` | `TextOverflow?` | Obsluga przepelnienia |
| `softWrap` | `bool?` | Czy zawijac tekst w miekkich przerwach |
| `textDirection` | `TextDirection?` | Kierunek tekstu |
| `locale` | `Locale?` | Lokalizacja do renderowania tekstu |
| `semanticsLabel` | `String?` | Etykieta dostepnosci |

## Porownanie

| Podejscie | Kod |
|----------|------|
| Tradycyjne | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Z argumentami | `TextTr("hello", arguments: {"name": "John"})` |
| Stylizowane | `TextTr.headlineLarge("title")` |