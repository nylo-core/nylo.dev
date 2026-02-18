# Styled Text

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Children-Modus](#children-mode "Children-Modus")
- [Template-Modus](#template-mode "Template-Modus")
  - [Platzhalter stylen](#styling-placeholders "Platzhalter stylen")
  - [Tipp-Callbacks](#tap-callbacks "Tipp-Callbacks")
  - [Pipe-getrennte Schluessel](#pipe-keys "Pipe-getrennte Schluessel")
- [Parameter](#parameters "Parameter")
- [Text-Erweiterungen](#text-extensions "Text-Erweiterungen")
  - [Typografie-Stile](#typography-styles "Typografie-Stile")
  - [Hilfsmethoden](#utility-methods "Hilfsmethoden")
- [Beispiele](#examples "Beispiele")

<div id="introduction"></div>

## Einleitung

**StyledText** ist ein Widget zur Anzeige von Rich Text mit gemischten Stilen, Tipp-Callbacks und Pointer-Events. Es wird als `RichText`-Widget mit mehreren `TextSpan`-Kindern gerendert und bietet Ihnen feinkoernige Kontrolle ueber jedes Textsegment.

StyledText unterstuetzt zwei Modi:

1. **Children-Modus** -- uebergeben Sie eine Liste von `Text`-Widgets, jedes mit eigenem Stil
2. **Template-Modus** -- verwenden Sie `@{{Platzhalter}}`-Syntax in einem String und ordnen Sie Platzhaltern Stile und Aktionen zu

<div id="basic-usage"></div>

## Grundlegende Verwendung

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

## Children-Modus

Uebergeben Sie eine Liste von `Text`-Widgets, um gestylten Text zusammenzusetzen:

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

Der Basis-`style` wird auf jedes Kind angewendet, das keinen eigenen Stil hat.

### Pointer-Events

Erkennen Sie, wenn der Pointer ein Textsegment betritt oder verlaesst:

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

## Template-Modus

Verwenden Sie `StyledText.template()` mit der `@{{Platzhalter}}`-Syntax:

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

Der Text zwischen `@{{ }}` ist sowohl der **Anzeigetext** als auch der **Schluessel**, der zum Nachschlagen von Stilen und Tipp-Callbacks verwendet wird.

<div id="styling-placeholders"></div>

### Platzhalter stylen

Ordnen Sie Platzhalternamen `TextStyle`-Objekten zu:

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

### Tipp-Callbacks

Ordnen Sie Platzhalternamen Tipp-Handlern zu:

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

### Pipe-getrennte Schluessel

Wenden Sie denselben Stil oder Callback auf mehrere Platzhalter an, indem Sie durch Pipe `|` getrennte Schluessel verwenden:

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

Dies ordnet allen drei Platzhaltern denselben Stil und Callback zu.

<div id="parameters"></div>

## Parameter

### StyledText (Children-Modus)

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `children` | `List<Text>` | erforderlich | Liste von Text-Widgets |
| `style` | `TextStyle?` | null | Basisstil fuer alle Kinder |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Pointer-Enter-Callback |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Pointer-Exit-Callback |
| `spellOut` | `bool?` | null | Text Zeichen fuer Zeichen buchstabieren |
| `softWrap` | `bool` | `true` | Weichen Zeilenumbruch aktivieren |
| `textAlign` | `TextAlign` | `TextAlign.start` | Textausrichtung |
| `textDirection` | `TextDirection?` | null | Textrichtung |
| `maxLines` | `int?` | null | Maximale Zeilenanzahl |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Ueberlaufverhalten |
| `locale` | `Locale?` | null | Text-Locale |
| `strutStyle` | `StrutStyle?` | null | Strut-Stil |
| `textScaler` | `TextScaler?` | null | Textskalierung |
| `selectionColor` | `Color?` | null | Hervorhebungsfarbe bei Auswahl |

### StyledText.template (Template-Modus)

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `text` | `String` | erforderlich | Template-Text mit `@{{Platzhalter}}`-Syntax |
| `styles` | `Map<String, TextStyle>?` | null | Zuordnung von Platzhalternamen zu Stilen |
| `onTap` | `Map<String, VoidCallback>?` | null | Zuordnung von Platzhalternamen zu Tipp-Callbacks |
| `style` | `TextStyle?` | null | Basisstil fuer Nicht-Platzhalter-Text |

Alle anderen Parameter (`softWrap`, `textAlign`, `maxLines` usw.) sind identisch mit dem Children-Konstruktor.

<div id="text-extensions"></div>

## Text-Erweiterungen

{{ config('app.name') }} erweitert Flutters `Text`-Widget um Typografie- und Hilfsmethoden.

<div id="typography-styles"></div>

### Typografie-Stile

Wenden Sie Material-Design-Typografiestile auf jedes `Text`-Widget an:

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

Jede akzeptiert optionale Ueberschreibungen:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Verfuegbare Ueberschreibungen** (identisch fuer alle Typografie-Methoden):

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `color` | `Color?` | Textfarbe |
| `fontSize` | `double?` | Schriftgroesse |
| `fontWeight` | `FontWeight?` | Schriftstaerke |
| `fontStyle` | `FontStyle?` | Kursiv/Normal |
| `letterSpacing` | `double?` | Zeichenabstand |
| `wordSpacing` | `double?` | Wortabstand |
| `height` | `double?` | Zeilenhoehe |
| `decoration` | `TextDecoration?` | Textdekoration |
| `decorationColor` | `Color?` | Dekorationsfarbe |
| `decorationStyle` | `TextDecorationStyle?` | Dekorationsstil |
| `decorationThickness` | `double?` | Dekorationsstaerke |
| `fontFamily` | `String?` | Schriftfamilie |
| `shadows` | `List<Shadow>?` | Textschatten |
| `overflow` | `TextOverflow?` | Ueberlaufverhalten |

<div id="utility-methods"></div>

### Hilfsmethoden

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

## Beispiele

### AGB- und Datenschutz-Link

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

### Versionsanzeige

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

### Absatz mit gemischten Stilen

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

### Typografie-Verkettung

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
