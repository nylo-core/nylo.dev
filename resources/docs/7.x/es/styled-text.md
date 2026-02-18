# Styled Text

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Modo hijos](#children-mode "Modo hijos")
- [Modo plantilla](#template-mode "Modo plantilla")
  - [Estilizar marcadores de posicion](#styling-placeholders "Estilizar marcadores de posicion")
  - [Callbacks de toque](#tap-callbacks "Callbacks de toque")
  - [Claves separadas por pipe](#pipe-keys "Claves separadas por pipe")
  - [Claves de localizacion](#localization-keys "Claves de localizacion")
- [Parametros](#parameters "Parametros")
- [Extensiones de texto](#text-extensions "Extensiones de texto")
  - [Estilos de tipografia](#typography-styles "Estilos de tipografia")
  - [Metodos de utilidad](#utility-methods "Metodos de utilidad")
- [Ejemplos](#examples "Ejemplos practicos")

<div id="introduction"></div>

## Introduccion

**StyledText** es un widget para mostrar texto enriquecido con estilos mixtos, callbacks de toque y eventos de puntero. Se renderiza como un widget `RichText` con multiples hijos `TextSpan`, dandote control detallado sobre cada segmento de texto.

StyledText soporta dos modos:

1. **Modo hijos** -- pasa una lista de widgets `Text`, cada uno con su propio estilo
2. **Modo plantilla** -- usa la sintaxis `@{{placeholder}}` en un string y mapea los marcadores de posicion a estilos y acciones

<div id="basic-usage"></div>

## Uso basico

``` dart
// Modo hijos - lista de widgets Text
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Modo plantilla - sintaxis de marcador de posicion
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Modo hijos

Pasa una lista de widgets `Text` para componer texto estilizado:

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

El `style` base se aplica a cualquier hijo que no tenga su propio estilo.

### Eventos de puntero

Detectar cuando el puntero entra o sale de un segmento de texto:

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

## Modo plantilla

Usa `StyledText.template()` con la sintaxis `@{{placeholder}}`:

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

El texto entre `@{{ }}` es tanto el **texto mostrado** como la **clave** usada para buscar estilos y callbacks de toque.

<div id="styling-placeholders"></div>

### Estilizar marcadores de posicion

Mapea nombres de marcadores de posicion a objetos `TextStyle`:

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

### Callbacks de toque

Mapea nombres de marcadores de posicion a manejadores de toque:

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

### Claves separadas por pipe

Aplica el mismo estilo o callback a multiples marcadores de posicion usando claves separadas por pipe `|`:

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

Esto mapea el mismo estilo y callback a los tres marcadores de posicion.

<div id="localization-keys"></div>

### Claves de localizacion

Usa la sintaxis `@{{key:text}}` para separar la **clave de busqueda** del **texto mostrado**. Esto es util para localizacion -- la clave permanece igual en todos los idiomas mientras que el texto mostrado cambia por idioma.

``` dart
// En tus archivos de idioma:
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
// EN renderiza: "Learn Languages, Reading and Speaking in AppName"
// ES renderiza: "Aprende Idiomas, Lectura y Habla en AppName"
```

La parte antes de `:` es la **clave** usada para buscar estilos y callbacks de toque. La parte despues de `:` es el **texto mostrado** que se renderiza en pantalla. Sin `:`, el marcador de posicion se comporta exactamente como antes -- totalmente compatible con versiones anteriores.

Esto funciona con todas las funciones existentes incluyendo [claves separadas por pipe](#pipe-keys) y [callbacks de toque](#tap-callbacks).

<div id="parameters"></div>

## Parametros

### StyledText (Modo hijos)

| Parametro | Tipo | Por defecto | Descripcion |
|-----------|------|-------------|-------------|
| `children` | `List<Text>` | requerido | Lista de widgets Text |
| `style` | `TextStyle?` | null | Estilo base para todos los hijos |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Callback de entrada del puntero |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Callback de salida del puntero |
| `spellOut` | `bool?` | null | Deletrear texto caracter por caracter |
| `softWrap` | `bool` | `true` | Habilitar ajuste de linea suave |
| `textAlign` | `TextAlign` | `TextAlign.start` | Alineacion del texto |
| `textDirection` | `TextDirection?` | null | Direccion del texto |
| `maxLines` | `int?` | null | Lineas maximas |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Comportamiento de desbordamiento |
| `locale` | `Locale?` | null | Idioma del texto |
| `strutStyle` | `StrutStyle?` | null | Estilo strut |
| `textScaler` | `TextScaler?` | null | Escalador de texto |
| `selectionColor` | `Color?` | null | Color de resaltado de seleccion |

### StyledText.template (Modo plantilla)

| Parametro | Tipo | Por defecto | Descripcion |
|-----------|------|-------------|-------------|
| `text` | `String` | requerido | Texto de plantilla con sintaxis `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | Mapa de nombres de marcadores de posicion a estilos |
| `onTap` | `Map<String, VoidCallback>?` | null | Mapa de nombres de marcadores de posicion a callbacks de toque |
| `style` | `TextStyle?` | null | Estilo base para texto sin marcador de posicion |

Todos los demas parametros (`softWrap`, `textAlign`, `maxLines`, etc.) son los mismos que en el constructor de hijos.

<div id="text-extensions"></div>

## Extensiones de texto

{{ config('app.name') }} extiende el widget `Text` de Flutter con metodos de tipografia y utilidad.

<div id="typography-styles"></div>

### Estilos de tipografia

Aplica estilos de tipografia Material Design a cualquier widget `Text`:

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

Cada uno acepta sobreescrituras opcionales:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Sobreescrituras disponibles** (iguales para todos los metodos de tipografia):

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `color` | `Color?` | Color del texto |
| `fontSize` | `double?` | Tamano de fuente |
| `fontWeight` | `FontWeight?` | Peso de fuente |
| `fontStyle` | `FontStyle?` | Italica/normal |
| `letterSpacing` | `double?` | Espaciado entre letras |
| `wordSpacing` | `double?` | Espaciado entre palabras |
| `height` | `double?` | Altura de linea |
| `decoration` | `TextDecoration?` | Decoracion de texto |
| `decorationColor` | `Color?` | Color de decoracion |
| `decorationStyle` | `TextDecorationStyle?` | Estilo de decoracion |
| `decorationThickness` | `double?` | Grosor de decoracion |
| `fontFamily` | `String?` | Familia de fuente |
| `shadows` | `List<Shadow>?` | Sombras de texto |
| `overflow` | `TextOverflow?` | Comportamiento de desbordamiento |

<div id="utility-methods"></div>

### Metodos de utilidad

``` dart
// Peso de fuente
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alineacion
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Lineas maximas
Text("Long text...").setMaxLines(2)

// Familia de fuente
Text("Custom font").setFontFamily("Roboto")

// Tamano de fuente
Text("Big text").setFontSize(24)

// Estilo personalizado
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Relleno
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copiar con modificaciones
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Ejemplos

### Enlace de terminos y condiciones

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

### Mostrar version

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

### Parrafo de estilos mixtos

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

### Cadena de tipografia

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
