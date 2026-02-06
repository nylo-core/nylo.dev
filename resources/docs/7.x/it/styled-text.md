# Styled Text

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Modalita' Children](#children-mode "Modalita' Children")
- [Modalita' Template](#template-mode "Modalita' Template")
  - [Stilizzare i Placeholder](#styling-placeholders "Stilizzare i Placeholder")
  - [Callback al Tap](#tap-callbacks "Callback al Tap")
  - [Chiavi Separate da Pipe](#pipe-keys "Chiavi Separate da Pipe")
- [Parametri](#parameters "Parametri")
- [Estensioni Text](#text-extensions "Estensioni Text")
  - [Stili Tipografici](#typography-styles "Stili Tipografici")
  - [Metodi di Utilita'](#utility-methods "Metodi di Utilita'")
- [Esempi](#examples "Esempi Pratici")

<div id="introduction"></div>

## Introduzione

**StyledText** e' un widget per visualizzare testo ricco con stili misti, callback al tap ed eventi del puntatore. Viene renderizzato come un widget `RichText` con piu' figli `TextSpan`, dandoti un controllo dettagliato su ogni segmento di testo.

StyledText supporta due modalita':

1. **Modalita' children** -- passa una lista di widget `Text`, ciascuno con il proprio stile
2. **Modalita' template** -- usa la sintassi `@{{placeholder}}` in una stringa e mappa i placeholder a stili e azioni

<div id="basic-usage"></div>

## Utilizzo Base

``` dart
// Children mode - list of Text widgets
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template mode - placeholder syntax
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Modalita' Children

Passa una lista di widget `Text` per comporre testo stilizzato:

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

Lo `style` di base viene applicato a qualsiasi figlio che non ha un proprio stile.

### Eventi del Puntatore

Rileva quando il puntatore entra o esce da un segmento di testo:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Modalita' Template

Usa `StyledText.template()` con la sintassi `@{{placeholder}}`:

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

Il testo tra `@{{ }}` e' sia il **testo visualizzato** che la **chiave** usata per cercare stili e callback al tap.

<div id="styling-placeholders"></div>

### Stilizzare i Placeholder

Mappa i nomi dei placeholder a oggetti `TextStyle`:

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

### Callback al Tap

Mappa i nomi dei placeholder a gestori di tap:

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

### Chiavi Separate da Pipe

Applica lo stesso stile o callback a piu' placeholder usando chiavi separate dal carattere pipe `|`:

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

Questo mappa lo stesso stile e callback a tutti e tre i placeholder.

<div id="parameters"></div>

## Parametri

### StyledText (Modalita' Children)

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | obbligatorio | Lista di widget Text |
| `style` | `TextStyle?` | null | Stile di base per tutti i figli |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Callback entrata puntatore |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Callback uscita puntatore |
| `spellOut` | `bool?` | null | Compita il testo carattere per carattere |
| `softWrap` | `bool` | `true` | Abilita il soft wrapping |
| `textAlign` | `TextAlign` | `TextAlign.start` | Allineamento del testo |
| `textDirection` | `TextDirection?` | null | Direzione del testo |
| `maxLines` | `int?` | null | Numero massimo di righe |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Comportamento di overflow |
| `locale` | `Locale?` | null | Locale del testo |
| `strutStyle` | `StrutStyle?` | null | Stile strut |
| `textScaler` | `TextScaler?` | null | Scalatore del testo |
| `selectionColor` | `Color?` | null | Colore di evidenziazione della selezione |

### StyledText.template (Modalita' Template)

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `text` | `String` | obbligatorio | Testo template con sintassi `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | Mappa dei nomi dei placeholder agli stili |
| `onTap` | `Map<String, VoidCallback>?` | null | Mappa dei nomi dei placeholder ai callback al tap |
| `style` | `TextStyle?` | null | Stile di base per il testo non-placeholder |

Tutti gli altri parametri (`softWrap`, `textAlign`, `maxLines`, ecc.) sono gli stessi del costruttore children.

<div id="text-extensions"></div>

## Estensioni Text

{{ config('app.name') }} estende il widget `Text` di Flutter con metodi tipografici e di utilita'.

<div id="typography-styles"></div>

### Stili Tipografici

Applica stili tipografici Material Design a qualsiasi widget `Text`:

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

Ciascuno accetta override opzionali:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Override disponibili** (uguali per tutti i metodi tipografici):

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `color` | `Color?` | Colore del testo |
| `fontSize` | `double?` | Dimensione del font |
| `fontWeight` | `FontWeight?` | Peso del font |
| `fontStyle` | `FontStyle?` | Corsivo/normale |
| `letterSpacing` | `double?` | Spaziatura tra le lettere |
| `wordSpacing` | `double?` | Spaziatura tra le parole |
| `height` | `double?` | Altezza della riga |
| `decoration` | `TextDecoration?` | Decorazione del testo |
| `decorationColor` | `Color?` | Colore della decorazione |
| `decorationStyle` | `TextDecorationStyle?` | Stile della decorazione |
| `decorationThickness` | `double?` | Spessore della decorazione |
| `fontFamily` | `String?` | Famiglia del font |
| `shadows` | `List<Shadow>?` | Ombre del testo |
| `overflow` | `TextOverflow?` | Comportamento di overflow |

<div id="utility-methods"></div>

### Metodi di Utilita'

``` dart
// Font weight
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alignment
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Max lines
Text("Long text...").setMaxLines(2)

// Font family
Text("Custom font").setFontFamily("Roboto")

// Font size
Text("Big text").setFontSize(24)

// Custom style
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copy with modifications
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Esempi

### Link Termini e Condizioni

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

### Visualizzazione della Versione

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

### Paragrafo con Stili Misti

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

### Catena Tipografica

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
