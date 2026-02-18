# TextTr

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso Basico](#basic-usage "Uso Basico")
- [Interpolacion de Cadenas](#string-interpolation "Interpolacion de Cadenas")
- [Constructores con Estilo](#styled-constructors "Constructores con Estilo")
- [Parametros](#parameters "Parametros")


<div id="introduction"></div>

## Introduccion

El widget **TextTr** es un envoltorio de conveniencia alrededor del widget `Text` de Flutter que traduce automaticamente su contenido usando el sistema de localizacion de {{ config('app.name') }}.

En lugar de escribir:

``` dart
Text("hello_world".tr())
```

Puedes escribir:

``` dart
TextTr("hello_world")
```

Esto hace tu codigo mas limpio y legible, especialmente cuando trabajas con muchas cadenas traducidas.

<div id="basic-usage"></div>

## Uso Basico

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

El widget buscara la clave de traduccion en tus archivos de idioma (por ejemplo, `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Interpolacion de Cadenas

Usa el parametro `arguments` para inyectar valores dinamicos en tus traducciones:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

En tu archivo de idioma:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Resultado: **Hello, John!**

### Multiples Argumentos

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

Resultado: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Constructores con Estilo

`TextTr` proporciona constructores con nombre que aplican automaticamente estilos de texto de tu tema:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Usa el estilo `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Usa el estilo `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Usa el estilo `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Usa el estilo `Theme.of(context).textTheme.labelLarge`.

### Ejemplo con Constructores con Estilo

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

## Parametros

`TextTr` soporta todos los parametros estandar del widget `Text`:

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `data` | `String` | La clave de traduccion a buscar |
| `arguments` | `Map<String, String>?` | Pares clave-valor para interpolacion de cadenas |
| `style` | `TextStyle?` | Estilo del texto |
| `textAlign` | `TextAlign?` | Como debe alinearse el texto |
| `maxLines` | `int?` | Numero maximo de lineas |
| `overflow` | `TextOverflow?` | Como manejar el desbordamiento |
| `softWrap` | `bool?` | Si se debe envolver el texto en saltos suaves |
| `textDirection` | `TextDirection?` | Direccion del texto |
| `locale` | `Locale?` | Locale para la representacion del texto |
| `semanticsLabel` | `String?` | Etiqueta de accesibilidad |

## Comparacion

| Enfoque | Codigo |
|----------|------|
| Tradicional | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Con argumentos | `TextTr("hello", arguments: {"name": "John"})` |
| Con estilo | `TextTr.headlineLarge("title")` |
