# Styled Text

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe uzycie](#basic-usage "Podstawowe uzycie")
- [Tryb potomkow](#children-mode "Tryb potomkow")
- [Tryb szablonu](#template-mode "Tryb szablonu")
  - [Stylizowanie symboli zastepczych](#styling-placeholders "Stylizowanie symboli zastepczych")
  - [Wywolania zwrotne dotkniec](#tap-callbacks "Wywolania zwrotne dotkniec")
  - [Klucze rozdzielone pionowa kreska](#pipe-keys "Klucze rozdzielone pionowa kreska")
  - [Klucze lokalizacji](#localization-keys "Klucze lokalizacji")
- [Parametry](#parameters "Parametry")
- [Rozszerzenia tekstu](#text-extensions "Rozszerzenia tekstu")
  - [Style typograficzne](#typography-styles "Style typograficzne")
  - [Metody narzedziowe](#utility-methods "Metody narzedziowe")
- [Przyklady](#examples "Przyklady praktyczne")

<div id="introduction"></div>

## Wprowadzenie

**StyledText** to widget do wyswietlania bogatego tekstu z mieszanymi stylami, wywolaniami zwrotnymi dotkniec i zdarzeniami wskaznika. Renderuje sie jako widget `RichText` z wieloma potomkami `TextSpan`, dajac precyzyjna kontrole nad kazdym segmentem tekstu.

StyledText obsluguje dwa tryby:

1. **Tryb potomkow** -- przekaz liste widgetow `Text`, kazdy z wlasnym stylem
2. **Tryb szablonu** -- uzyj skladni `@{{symbol_zastepczy}}` w ciagu i mapuj symbole zastepczye na style i akcje

<div id="basic-usage"></div>

## Podstawowe uzycie

``` dart
// Tryb potomkow - lista widgetow Text
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Tryb szablonu - skladnia symboli zastepczych
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Tryb potomkow

Przekaz liste widgetow `Text`, aby skomponowac stylizowany tekst:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

Bazowy `style` jest stosowany do kazdego potomka, ktory nie ma wlasnego stylu.

### Zdarzenia wskaznika

Wykrywaj, gdy wskaznik wchodzi lub opuszcza segment tekstu:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Najezdzanie na: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Opuszczono: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Tryb szablonu

Uzyj `StyledText.template()` ze skladnia `@{{symbol_zastepczy}}`:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

Tekst miedzy `@{{ }}` jest zarowno **wyswietlanym tekstem**, jak i **kluczem** uzywanym do wyszukiwania stylow i wywolan zwrotnych dotkniec.

<div id="styling-placeholders"></div>

### Stylizowanie symboli zastepczych

Mapuj nazwy symboli zastepczych na obiekty `TextStyle`:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### Wywolania zwrotne dotkniec

Mapuj nazwy symboli zastepczych na obsluge dotkniec:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### Klucze rozdzielone pionowa kreska

Zastosuj ten sam styl lub wywolanie zwrotne do wielu symboli zastepczych za pomoca kluczy rozdzielonych pionowa kreska `|`:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

To mapuje ten sam styl i wywolanie zwrotne na wszystkie trzy symbole zastepczye.

<div id="localization-keys"></div>

### Klucze lokalizacji

Uzyj skladni `@{{klucz:tekst}}`, aby oddzielic **klucz wyszukiwania** od **wyswietlanego tekstu**. Jest to przydatne dla lokalizacji -- klucz pozostaje taki sam we wszystkich lokalizacjach, podczas gdy wyswietlany tekst zmienia sie w zaleznosci od jezyka.

``` dart
// W plikach lokalizacji:
// en.json → "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} in @{{app:AppName}}"
// es.json → "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}} en @{{app:AppName}}"

StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(
      color: Colors.blue,
      fontWeight: FontWeight.bold,
    ),
    "app": TextStyle(color: Colors.green),
  },
  onTap: {
    "app": () => routeTo("/about"),
  },
)
// EN renderuje: "Learn Languages, Reading and Speaking in AppName"
// ES renderuje: "Aprende Idiomas, Lectura y Habla en AppName"
```

Czesc przed `:` to **klucz** uzywany do wyszukiwania stylow i wywolan zwrotnych dotkniec. Czesc po `:` to **wyswietlany tekst** renderowany na ekranie. Bez `:` symbol zastepczy zachowuje sie dokladnie jak wczesniej -- w pelni wstecznie kompatybilny.

To dziala ze wszystkimi istniejacymi funkcjami, w tym [kluczami rozdzielonymi pionowa kreska](#pipe-keys) i [wywolaniami zwrotnymi dotkniec](#tap-callbacks).

<div id="parameters"></div>

## Parametry

### StyledText (tryb potomkow)

| Parametr | Typ | Domyslnie | Opis |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | wymagany | Lista widgetow Text |
| `style` | `TextStyle?` | null | Bazowy styl dla wszystkich potomkow |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Wywolanie zwrotne wejscia wskaznika |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Wywolanie zwrotne wyjscia wskaznika |
| `spellOut` | `bool?` | null | Literuj tekst znak po znaku |
| `softWrap` | `bool` | `true` | Wlacz miekkie zawijanie |
| `textAlign` | `TextAlign` | `TextAlign.start` | Wyrownanie tekstu |
| `textDirection` | `TextDirection?` | null | Kierunek tekstu |
| `maxLines` | `int?` | null | Maksymalna liczba linii |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Zachowanie przy przepelnieniu |
| `locale` | `Locale?` | null | Lokalizacja tekstu |
| `strutStyle` | `StrutStyle?` | null | Styl strut |
| `textScaler` | `TextScaler?` | null | Skaler tekstu |
| `selectionColor` | `Color?` | null | Kolor podswietlenia zaznaczenia |

### StyledText.template (tryb szablonu)

| Parametr | Typ | Domyslnie | Opis |
|-----------|------|---------|-------------|
| `text` | `String` | wymagany | Tekst szablonu ze skladnia `@{{symbol_zastepczy}}` |
| `styles` | `Map<String, TextStyle>?` | null | Mapa nazw symboli zastepczych na style |
| `onTap` | `Map<String, VoidCallback>?` | null | Mapa nazw symboli zastepczych na wywolania zwrotne dotkniec |
| `style` | `TextStyle?` | null | Bazowy styl dla tekstu poza symbolami zastepczymi |

Wszystkie inne parametry (`softWrap`, `textAlign`, `maxLines`, itp.) sa takie same jak w konstruktorze potomkow.

<div id="text-extensions"></div>

## Rozszerzenia tekstu

{{ config('app.name') }} rozszerza widget `Text` Flutter o metody typograficzne i narzedziowe.

<div id="typography-styles"></div>

### Style typograficzne

Zastosuj style typografii Material Design do dowolnego widgetu `Text`:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

Kazdy akceptuje opcjonalne nadpisania:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Dostepne nadpisania** (takie same dla wszystkich metod typograficznych):

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `color` | `Color?` | Kolor tekstu |
| `fontSize` | `double?` | Rozmiar czcionki |
| `fontWeight` | `FontWeight?` | Grubosc czcionki |
| `fontStyle` | `FontStyle?` | Kursywa/normalna |
| `letterSpacing` | `double?` | Odstepy miedzy literami |
| `wordSpacing` | `double?` | Odstepy miedzy slowami |
| `height` | `double?` | Wysokosc linii |
| `decoration` | `TextDecoration?` | Dekoracja tekstu |
| `decorationColor` | `Color?` | Kolor dekoracji |
| `decorationStyle` | `TextDecorationStyle?` | Styl dekoracji |
| `decorationThickness` | `double?` | Grubosc dekoracji |
| `fontFamily` | `String?` | Rodzina czcionek |
| `shadows` | `List<Shadow>?` | Cienie tekstu |
| `overflow` | `TextOverflow?` | Zachowanie przy przepelnieniu |

<div id="utility-methods"></div>

### Metody narzedziowe

``` dart
// Grubosc czcionki
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Wyrownanie
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Maksymalna liczba linii
Text("Long text...").setMaxLines(2)

// Rodzina czcionek
Text("Custom font").setFontFamily("Roboto")

// Rozmiar czcionki
Text("Big text").setFontSize(24)

// Niestandardowy styl
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Kopiuj z modyfikacjami
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Przyklady

### Odniesienie do regulaminu

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### Wyswietlanie wersji

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### Akapit z mieszanymi stylami

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### Lancuch typografii

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```