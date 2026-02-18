# Themes & Styling

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a los temas")
- Temas
  - [Temas claro y oscuro](#light-and-dark-themes "Temas claro y oscuro")
  - [Crear un tema](#creating-a-theme "Crear un tema")
- Configuracion
  - [Colores del tema](#theme-colors "Colores del tema")
  - [Usar colores](#using-colors "Usar colores")
  - [Estilos base](#base-styles "Estilos base")
  - [Cambiar tema](#switching-theme "Cambiar tema")
  - [Fuentes](#fonts "Fuentes")
  - [Diseno](#design "Diseno")
- [Extensiones de texto](#text-extensions "Extensiones de texto")


<div id="introduction"></div>

## Introduccion

Puedes gestionar los estilos de la interfaz de tu aplicacion usando temas. Los temas nos permiten cambiar, por ejemplo, el tamano de fuente del texto, como aparecen los botones y la apariencia general de nuestra aplicacion.

Si eres nuevo en los temas, los ejemplos en el sitio web de Flutter te ayudaran a comenzar <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">aqui</a>.

De forma predeterminada, {{ config('app.name') }} incluye temas preconfigurados para `Modo claro` y `Modo oscuro`.

El tema tambien se actualizara si el dispositivo entra en modo <b>'claro/oscuro'</b>.

<div id="light-and-dark-themes"></div>

## Temas claro y oscuro

- Tema claro - `lib/resources/themes/light_theme.dart`
- Tema oscuro - `lib/resources/themes/dark_theme.dart`

Dentro de estos archivos, encontraras el ThemeData y ThemeStyle predefinidos.



<div id="creating-a-theme"></div>

## Crear un tema

Si deseas tener multiples temas para tu aplicacion, tenemos una forma facil de hacerlo. Si eres nuevo en los temas, sigue las instrucciones.

Primero, ejecuta el siguiente comando desde la terminal

``` bash
metro make:theme bright_theme
```

<b>Nota:</b> reemplaza **bright_theme** con el nombre de tu nuevo tema.

Esto crea un nuevo tema en tu directorio `/resources/themes/` y tambien un archivo de colores del tema en `/resources/themes/styles/`.

``` dart
// App Themes
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

Puedes modificar los colores de tu nuevo tema en el archivo **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Colores del tema

Para gestionar los colores del tema en tu proyecto, revisa el directorio `lib/resources/themes/styles`.
Este directorio contiene los colores de estilo para light_theme_colors.dart y dark_theme_colors.dart.

En este archivo, deberas tener algo similar a lo siguiente.

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // app bar
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // bottom tab bar
  @override
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // toast notification
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## Usar colores en widgets

``` dart
import 'package:flutter_app/config/theme.dart';
...

// gets the light/dark background colour depending on the theme
ThemeColor.get(context).background

// e.g. of using the "ThemeColor" class
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// or

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>

## Estilos base

Los estilos base te permiten personalizar los colores de varios widgets desde un solo lugar en tu codigo.

{{ config('app.name') }} incluye estilos base preconfigurados para tu proyecto ubicados en `lib/resources/themes/styles/color_styles.dart`.

Estos estilos proporcionan una interfaz para los colores de tu tema en `light_theme_colors.dart` y `dart_theme_colors.dart`.

<br>

Archivo `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // general
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // app bar
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // bottom tab bar
  @override
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // toast notification
  Color get toastNotificationBackground;
}
```

Puedes agregar estilos adicionales aqui y luego implementar los colores en tu tema.

<div id="switching-theme"></div>

## Cambiar tema

{{ config('app.name') }} soporta la capacidad de cambiar temas en tiempo real.

Ej. Si necesitas cambiar el tema cuando un usuario toca un boton para activar el "tema oscuro".

Puedes soportar eso haciendo lo siguiente:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Fuentes

Actualizar tu fuente principal en toda la aplicacion es facil en {{ config('app.name') }}. Abre el archivo `lib/config/design.dart` y actualiza lo siguiente.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Incluimos la libreria <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> en el repositorio, asi que puedes comenzar a usar todas las fuentes con poco esfuerzo.
Para actualizar la fuente a otra, puedes hacer lo siguiente:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Revisa las fuentes en la libreria oficial de <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> para entender mas

Necesitas usar una fuente personalizada? Revisa esta guia - https://flutter.dev/docs/cookbook/design/fonts

Una vez que hayas agregado tu fuente, cambia la variable como en el siguiente ejemplo.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Diseno

El archivo **config/design.dart** se usa para gestionar los elementos de diseno de tu aplicacion.

La variable `appFont` contiene la fuente de tu aplicacion.

La variable `logo` se usa para mostrar el logotipo de tu aplicacion.

Puedes modificar **resources/widgets/logo_widget.dart** para personalizar como deseas mostrar tu logotipo.

La variable `loader` se usa para mostrar un cargador. {{ config('app.name') }} usara esta variable en algunos metodos helper como el widget cargador por defecto.

Puedes modificar **resources/widgets/loader_widget.dart** para personalizar como deseas mostrar tu cargador.

<div id="text-extensions"></div>

## Extensiones de texto

Aqui estan las extensiones de texto disponibles que puedes usar en {{ config('app.name') }}.

| Nombre de regla   | Uso | Informacion |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Aplica el textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Aplica el textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Aplica el textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Aplica el textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Aplica el textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Aplica el textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Aplica el textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Aplica el textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Aplica el textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Aplica el textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Aplica el textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Aplica el textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Aplica el textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Aplica el textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Aplica el textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Aplica peso de fuente negrita a un widget Text |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Aplica peso de fuente ligero a un widget Text |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Establece un color de texto diferente en el widget Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Alinea la fuente a la izquierda |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Alinea la fuente a la derecha |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Alinea la fuente al centro |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Establece el maximo de lineas para el widget de texto |

<br>


<div id="text-extension-display-large"></div>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
