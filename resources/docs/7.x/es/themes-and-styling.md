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
  - [Extender estilos de color](#extending-color-styles "Extender estilos de color")
  - [Cambiar tema](#switching-theme "Cambiar tema")
  - [Fuentes](#fonts "Fuentes")
  - [Diseno](#design "Diseno")
- [Extensiones de texto](#text-extensions "Extensiones de texto")


<div id="introduction"></div>

## Introduccion

Puedes gestionar los estilos de la interfaz de tu aplicacion usando temas. Los temas nos permiten cambiar, por ejemplo, el tamano de fuente del texto, como aparecen los botones y la apariencia general de nuestra aplicacion.

Si eres nuevo en los temas, los ejemplos en el sitio web de Flutter te ayudaran a comenzar <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">aqui</a>.

De forma predeterminada, {{ config('app.name') }} incluye temas preconfigurados para `Light mode` y `Dark mode`.

El tema tambien se actualizara si el dispositivo entra en modo <b>'claro/oscuro'</b>.

<div id="light-and-dark-themes"></div>

## Temas claro y oscuro

Cada tema vive en su propio subdirectorio bajo `lib/resources/themes/`:

- Tema claro – `lib/resources/themes/light/light_theme.dart`
- Colores claros – `lib/resources/themes/light/light_theme_colors.dart`
- Tema oscuro – `lib/resources/themes/dark/dark_theme.dart`
- Colores oscuros – `lib/resources/themes/dark/dark_theme_colors.dart`

Ambos temas comparten un builder comun en `lib/resources/themes/base_theme.dart` y la interfaz `ColorStyles` en `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Crear un tema

Si deseas tener multiples temas para tu aplicacion, crea los archivos de tema manualmente bajo `lib/resources/themes/`. Los pasos a continuacion usan `bright` como ejemplo — reemplazalo con el nombre de tu tema.

**Paso 1:** Crea el archivo de tema en `lib/resources/themes/bright/bright_theme.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Paso 2:** Crea el archivo de colores en `lib/resources/themes/bright/bright_theme_colors.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/color_styles.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BrightThemeColors extends ColorStyles {
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFDE7),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFFFBC02D),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  @override
  AppBarColors get appBar => const AppBarColors(
        background: Color(0xFFFBC02D),
        content: Colors.white,
      );

  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Color(0xFFFBC02D),
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

**Paso 3:** Registra el nuevo tema en `lib/bootstrap/theme.dart`.

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>(
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

Puedes ajustar los colores en `bright_theme_colors.dart` para que coincidan con tu diseno.

<div id="theme-colors"></div>

## Colores del tema

Para gestionar los colores del tema en tu proyecto, revisa los directorios `lib/resources/themes/light/` y `lib/resources/themes/dark/`. Cada uno contiene el archivo de colores de su tema — `light_theme_colors.dart` y `dark_theme_colors.dart`.

Los valores de color estan organizados en grupos (`general`, `appBar`, `bottomTabBar`) definidos por el framework. La clase de colores de tu tema extiende `ColorStyles` y proporciona una instancia de cada grupo:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Colores para uso general.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Colores para la barra de la aplicacion.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Colores para la barra de pestanas inferior.
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## Usar colores en widgets

Usa el helper `nyColorStyle<T>(context)` para leer los colores del tema activo. Pasa el tipo `ColorStyles` de tu proyecto para que la llamada sea completamente tipada:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// dentro del build de un widget:
final colors = nyColorStyle<ColorStyles>(context);

// el color de fondo del tema activo
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Leer colores de un tema especifico (independientemente de cual este activo):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Estilos base

Los estilos base te permiten describir cada tema a traves de una sola interfaz. {{ config('app.name') }} incluye `lib/resources/themes/color_styles.dart`, que es el contrato que implementan tanto `light_theme_colors.dart` como `dark_theme_colors.dart`.

`ColorStyles` extiende `ThemeColor` del framework, que expone tres grupos de colores predefinidos: `GeneralColors`, `AppBarColors` y `BottomTabBarColors`. El builder de tema base (`lib/resources/themes/base_theme.dart`) lee estos grupos al construir `ThemeData`, por lo que todo lo que coloques en ellos se conecta automaticamente a los widgets correspondientes.

<br>

Archivo `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Colores para uso general.
  @override
  GeneralColors get general;

  /// Colores para la barra de la aplicacion.
  @override
  AppBarColors get appBar;

  /// Colores para la barra de pestanas inferior.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Los tres grupos exponen los siguientes campos:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Para agregar campos mas alla de estos valores predeterminados — tus propios botones, iconos, insignias, etc. — ver [Extender estilos de color](#extending-color-styles).

<div id="extending-color-styles"></div>

## Extender estilos de color

<!-- uncertain: new section "Extending color styles" not present in previous locale file -->
Los tres grupos predeterminados (`general`, `appBar`, `bottomTabBar`) son un punto de partida, no un limite fijo. `lib/resources/themes/color_styles.dart` es tuyo para modificar — agrega nuevos grupos de colores (o campos individuales) ademas de los predeterminados, luego implementalos en la clase de colores de cada tema.

**1. Definir un grupo de colores personalizado**

Agrupa colores relacionados en una pequena clase inmutable:

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. Agregarlo a `ColorStyles`**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // Grupos personalizados
  IconColors get icons;
}
```

**3. Implementarlo en la clase de colores de cada tema**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...overrides existentes...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Repite el mismo override de `icons` en `dark_theme_colors.dart` con los valores del modo oscuro.

**4. Usarlo en tus widgets**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Cambiar tema

{{ config('app.name') }} soporta la capacidad de cambiar temas en tiempo real.

Ej. Si necesitas cambiar el tema cuando un usuario toca un boton para activar el "tema oscuro".

Puedes soportar eso haciendo lo siguiente:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
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

Actualizar tu fuente principal en toda la aplicacion es facil en {{ config('app.name') }}. Abre `lib/config/design.dart` y actualiza `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Incluimos la libreria <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> en el repositorio, asi que puedes comenzar a usar todas las fuentes con poco esfuerzo. Para cambiar a una fuente de Google diferente, simplemente cambia la llamada:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Revisa las fuentes en la libreria oficial de <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> para entender mas.

Necesitas usar una fuente personalizada? Revisa esta guia - https://flutter.dev/docs/cookbook/design/fonts

Una vez que hayas agregado tu fuente, cambia la variable como en el siguiente ejemplo.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Diseno

El archivo **lib/config/design.dart** se usa para gestionar los elementos de diseno de tu aplicacion. Todo esta expuesto a traves de la clase `DesignConfig`:

`DesignConfig.appFont` contiene la fuente de tu aplicacion.

`DesignConfig.logo` se usa para mostrar el logotipo de tu aplicacion.

Puedes modificar **lib/resources/widgets/logo_widget.dart** para personalizar como deseas mostrar tu logotipo.

`DesignConfig.loader` se usa para mostrar un cargador. {{ config('app.name') }} usara esta variable en algunos metodos helper como el widget cargador por defecto.

Puedes modificar **lib/resources/widgets/loader_widget.dart** para personalizar como deseas mostrar tu cargador.

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
