# Themes & Styling

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos temas")
- Temas
  - [Temas claro e escuro](#light-and-dark-themes "Temas claro e escuro")
  - [Criando um tema](#creating-a-theme "Criando um tema")
- Configuração
  - [Cores do tema](#theme-colors "Cores do tema")
  - [Usando cores](#using-colors "Usando cores")
  - [Estilos base](#base-styles "Estilos base")
  - [Alternando tema](#switching-theme "Alternando tema")
  - [Fontes](#fonts "Fontes")
  - [Design](#design "Design")
- [Extensões de Texto](#text-extensions "Extensões de texto")


<div id="introduction"></div>

## Introdução

Você pode gerenciar os estilos de UI da sua aplicação usando temas. Temas permitem alterar, por exemplo, o tamanho da fonte do texto, como os botões aparecem e a aparência geral da nossa aplicação.

Se você é novo em temas, os exemplos no site do Flutter ajudarão você a começar <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">aqui</a>.

Por padrão, {{ config('app.name') }} inclui temas pré-configurados para `Modo claro` e `Modo escuro`.

O tema também será atualizado se o dispositivo entrar no modo <b>'claro/escuro'</b>.

<div id="light-and-dark-themes"></div>

## Temas Claro e Escuro

- Tema claro - `lib/resources/themes/light_theme.dart`
- Tema escuro - `lib/resources/themes/dark_theme.dart`

Dentro desses arquivos, você encontrará o ThemeData e o ThemeStyle pré-definidos.



<div id="creating-a-theme"></div>

## Criando um tema

Se você deseja ter múltiplos temas para seu app, temos uma maneira fácil para você fazer isso. Se você é novo em temas, acompanhe.

Primeiro, execute o comando abaixo no terminal

``` bash
metro make:theme bright_theme
```

<b>Nota:</b> substitua **bright_theme** pelo nome do seu novo tema.

Isso cria um novo tema no seu diretório `/resources/themes/` e também um arquivo de cores do tema em `/resources/themes/styles/`.

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

Você pode modificar as cores do seu novo tema no arquivo **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Cores do Tema

Para gerenciar as cores do tema no seu projeto, verifique o diretório `lib/resources/themes/styles`.
Este diretório contém as cores de estilo para light_theme_colors.dart e dark_theme_colors.dart.

Neste arquivo, você deve ter algo semelhante ao abaixo.

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

## Usando cores em widgets

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

## Estilos Base

Estilos base permitem que você personalize várias cores de widgets a partir de uma área do seu código.

{{ config('app.name') }} vem com estilos base pré-configurados para o seu projeto localizados em `lib/resources/themes/styles/color_styles.dart`.

Esses estilos fornecem uma interface para as cores do seu tema em `light_theme_colors.dart` e `dart_theme_colors.dart`.

<br>

Arquivo `lib/resources/themes/styles/color_styles.dart`

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

Você pode adicionar estilos adicionais aqui e então implementar as cores no seu tema.

<div id="switching-theme"></div>

## Alternando tema

{{ config('app.name') }} suporta a capacidade de alternar temas em tempo real.

Por exemplo, se você precisa alternar o tema quando um usuário toca em um botão para ativar o "tema escuro".

Você pode fazer isso da seguinte forma:

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

## Fontes

Atualizar sua fonte principal em todo o app é fácil no {{ config('app.name') }}. Abra o arquivo `lib/config/design.dart` e atualize o abaixo.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Incluímos a biblioteca <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> no repositório, então você pode começar a usar todas as fontes com pouco esforço.
Para atualizar a fonte para outra, você pode fazer o seguinte:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Confira as fontes na biblioteca oficial do <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> para entender mais

Precisa usar uma fonte personalizada? Confira este guia - https://flutter.dev/docs/cookbook/design/fonts

Uma vez que você adicionou sua fonte, altere a variável como no exemplo abaixo.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Design

O arquivo **config/design.dart** é usado para gerenciar os elementos de design do seu app.

A variável `appFont` contém a fonte do seu app.

A variável `logo` é usada para exibir o Logo do seu app.

Você pode modificar **resources/widgets/logo_widget.dart** para personalizar como deseja exibir seu Logo.

A variável `loader` é usada para exibir um loader. {{ config('app.name') }} usará esta variável em alguns métodos auxiliares como o widget loader padrão.

Você pode modificar **resources/widgets/loader_widget.dart** para personalizar como deseja exibir seu Loader.

<div id="text-extensions"></div>

## Extensões de Texto

Aqui estão as extensões de texto disponíveis que você pode usar no {{ config('app.name') }}.

| Nome da Regra   | Uso | Info |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Aplica o textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Aplica o textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Aplica o textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Aplica o textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Aplica o textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Aplica o textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Aplica o textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Aplica o textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Aplica o textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Aplica o textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Aplica o textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Aplica o textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Aplica o textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Aplica o textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Aplica o textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Aplica peso da fonte negrito a um widget Text |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Aplica peso da fonte leve a um widget Text |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Define uma cor de texto diferente no widget Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Alinha a fonte à esquerda |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Alinha a fonte à direita |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Alinha a fonte ao centro |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Define o máximo de linhas para o widget text |

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

#### Alinhar à esquerda

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Alinhar à direita

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Alinhar ao centro

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Definir máximo de linhas

``` dart
Text("Hello World").setMaxLines(5)
```
