# Styled Text

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Modo Children](#children-mode "Modo Children")
- [Modo Template](#template-mode "Modo Template")
  - [Estilizando Placeholders](#styling-placeholders "Estilizando Placeholders")
  - [Callbacks de Toque](#tap-callbacks "Callbacks de Toque")
  - [Chaves Separadas por Pipe](#pipe-keys "Chaves Separadas por Pipe")
  - [Chaves de Localização](#localization-keys "Chaves de Localização")
- [Parâmetros](#parameters "Parâmetros")
- [Extensões de Texto](#text-extensions "Extensões de Texto")
  - [Estilos de Tipografia](#typography-styles "Estilos de Tipografia")
  - [Métodos Utilitários](#utility-methods "Métodos Utilitários")
- [Exemplos](#examples "Exemplos Práticos")

<div id="introduction"></div>

## Introdução

**StyledText** é um widget para exibir texto rico com estilos mistos, callbacks de toque e eventos de ponteiro. Ele renderiza como um widget `RichText` com múltiplos filhos `TextSpan`, dando controle detalhado sobre cada segmento de texto.

O StyledText suporta dois modos:

1. **Modo children** -- passe uma lista de widgets `Text`, cada um com seu próprio estilo
2. **Modo template** -- use a sintaxe `@{{placeholder}}` em uma string e mapeie placeholders para estilos e ações

<div id="basic-usage"></div>

## Uso Básico

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

## Modo Children

Passe uma lista de widgets `Text` para compor texto estilizado:

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

O `style` base é aplicado a qualquer filho que não tenha seu próprio estilo.

### Eventos de Ponteiro

Detecte quando o ponteiro entra ou sai de um segmento de texto:

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

## Modo Template

Use `StyledText.template()` com a sintaxe `@{{placeholder}}`:

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

O texto entre `@{{ }}` é tanto o **texto exibido** quanto a **chave** usada para buscar estilos e callbacks de toque.

<div id="styling-placeholders"></div>

### Estilizando Placeholders

Mapeie nomes de placeholder para objetos `TextStyle`:

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

### Callbacks de Toque

Mapeie nomes de placeholder para handlers de toque:

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

### Chaves Separadas por Pipe

Aplique o mesmo estilo ou callback a múltiplos placeholders usando chaves separadas por pipe `|`:

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

Isso mapeia o mesmo estilo e callback para todos os três placeholders.

<div id="localization-keys"></div>

### Chaves de Localização

Use a sintaxe `@{{key:text}}` para separar a **chave de busca** do **texto exibido**. Isso é útil para localização -- a chave permanece a mesma em todos os idiomas enquanto o texto exibido muda por idioma.

``` dart
// In your locale files:
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
// EN renders: "Learn Languages, Reading and Speaking in AppName"
// ES renders: "Aprende Idiomas, Lectura y Habla en AppName"
```

A parte antes de `:` é a **chave** usada para buscar estilos e callbacks de toque. A parte após `:` é o **texto exibido** que é renderizado na tela. Sem `:`, o placeholder se comporta exatamente como antes -- totalmente compatível com versões anteriores.

Isso funciona com todos os recursos existentes, incluindo [chaves separadas por pipe](#pipe-keys) e [callbacks de toque](#tap-callbacks).

<div id="parameters"></div>

## Parâmetros

### StyledText (Modo Children)

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | obrigatório | Lista de widgets Text |
| `style` | `TextStyle?` | null | Estilo base para todos os filhos |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Callback de entrada do ponteiro |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Callback de saída do ponteiro |
| `spellOut` | `bool?` | null | Soletrar texto caractere por caractere |
| `softWrap` | `bool` | `true` | Habilitar quebra suave |
| `textAlign` | `TextAlign` | `TextAlign.start` | Alinhamento do texto |
| `textDirection` | `TextDirection?` | null | Direção do texto |
| `maxLines` | `int?` | null | Máximo de linhas |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Comportamento de overflow |
| `locale` | `Locale?` | null | Localidade do texto |
| `strutStyle` | `StrutStyle?` | null | Estilo strut |
| `textScaler` | `TextScaler?` | null | Escala do texto |
| `selectionColor` | `Color?` | null | Cor de destaque da seleção |

### StyledText.template (Modo Template)

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `text` | `String` | obrigatório | Texto template com sintaxe `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | Mapa de nomes de placeholder para estilos |
| `onTap` | `Map<String, VoidCallback>?` | null | Mapa de nomes de placeholder para callbacks de toque |
| `style` | `TextStyle?` | null | Estilo base para texto fora dos placeholders |

Todos os outros parâmetros (`softWrap`, `textAlign`, `maxLines`, etc.) são os mesmos do construtor children.

<div id="text-extensions"></div>

## Extensões de Texto

{{ config('app.name') }} estende o widget `Text` do Flutter com métodos de tipografia e utilitários.

<div id="typography-styles"></div>

### Estilos de Tipografia

Aplique estilos de tipografia Material Design a qualquer widget `Text`:

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

Cada um aceita substituições opcionais:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Substituições disponíveis** (iguais para todos os métodos de tipografia):

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `color` | `Color?` | Cor do texto |
| `fontSize` | `double?` | Tamanho da fonte |
| `fontWeight` | `FontWeight?` | Peso da fonte |
| `fontStyle` | `FontStyle?` | Itálico/normal |
| `letterSpacing` | `double?` | Espaçamento entre letras |
| `wordSpacing` | `double?` | Espaçamento entre palavras |
| `height` | `double?` | Altura da linha |
| `decoration` | `TextDecoration?` | Decoração do texto |
| `decorationColor` | `Color?` | Cor da decoração |
| `decorationStyle` | `TextDecorationStyle?` | Estilo da decoração |
| `decorationThickness` | `double?` | Espessura da decoração |
| `fontFamily` | `String?` | Família da fonte |
| `shadows` | `List<Shadow>?` | Sombras do texto |
| `overflow` | `TextOverflow?` | Comportamento de overflow |

<div id="utility-methods"></div>

### Métodos Utilitários

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

## Exemplos

### Link de Termos e Condições

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

### Exibição de Versão

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

### Parágrafo com Estilos Mistos

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

### Cadeia de Tipografia

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
