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
  - [Estendendo estilos de cor](#extending-color-styles "Estendendo estilos de cor")
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

Cada tema reside em seu próprio subdiretório em `lib/resources/themes/`:

- Tema claro – `lib/resources/themes/light/light_theme.dart`
- Cores claras – `lib/resources/themes/light/light_theme_colors.dart`
- Tema escuro – `lib/resources/themes/dark/dark_theme.dart`
- Cores escuras – `lib/resources/themes/dark/dark_theme_colors.dart`

Ambos os temas compartilham um builder comum em `lib/resources/themes/base_theme.dart` e a interface `ColorStyles` em `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Criando um tema

Se você deseja ter múltiplos temas para seu app, crie os arquivos de tema manualmente em `lib/resources/themes/`. Os passos abaixo usam `bright` como exemplo — substitua pelo nome do seu tema.

**Passo 1:** Crie o arquivo de tema em `lib/resources/themes/bright/bright_theme.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Passo 2:** Crie o arquivo de cores em `lib/resources/themes/bright/bright_theme_colors.dart`:

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

**Passo 3:** Registre o novo tema em `lib/bootstrap/theme.dart`.

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

Você pode ajustar as cores em `bright_theme_colors.dart` para corresponder ao seu design.

<div id="theme-colors"></div>

## Cores do Tema

Para gerenciar as cores do tema no seu projeto, verifique os diretórios `lib/resources/themes/light/` e `lib/resources/themes/dark/`. Cada um contém o arquivo de cores para seu tema — `light_theme_colors.dart` e `dark_theme_colors.dart`.

Os valores de cores são organizados em grupos (`general`, `appBar`, `bottomTabBar`) definidos pelo framework. A classe de cores do seu tema estende `ColorStyles` e fornece uma instância de cada grupo:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Cores para uso geral.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Cores para a barra do app.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Cores para a barra de abas inferior.
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

## Usando cores em widgets

Use o helper `nyColorStyle<T>(context)` para ler as cores do tema ativo. Passe o tipo `ColorStyles` do seu projeto para que a chamada seja totalmente tipada:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// dentro do build de um widget:
final colors = nyColorStyle<ColorStyles>(context);

// a cor de fundo do tema ativo
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Leia cores de um tema específico (independentemente de qual está ativo):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Estilos Base

Estilos base permitem que você descreva cada tema através de uma única interface. {{ config('app.name') }} vem com `lib/resources/themes/color_styles.dart`, que é o contrato implementado por `light_theme_colors.dart` e `dark_theme_colors.dart`.

`ColorStyles` estende `ThemeColor` do framework, que expõe três grupos de cores predefinidos: `GeneralColors`, `AppBarColors` e `BottomTabBarColors`. O builder do tema base (`lib/resources/themes/base_theme.dart`) lê esses grupos ao construir `ThemeData`, então tudo que você colocar neles é conectado automaticamente aos widgets correspondentes.

<br>

Arquivo `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Cores para uso geral.
  @override
  GeneralColors get general;

  /// Cores para a barra do app.
  @override
  AppBarColors get appBar;

  /// Cores para a barra de abas inferior.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Os três grupos expõem os seguintes campos:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Para adicionar campos além desses padrões — seus próprios botões, ícones, badges, etc. — veja [Estendendo estilos de cor](#extending-color-styles).

<div id="extending-color-styles"></div>

## Estendendo estilos de cor

<!-- uncertain: new section "Extending color styles" with new anchor #extending-color-styles — not present in the previous locale file -->
Os três grupos padrão (`general`, `appBar`, `bottomTabBar`) são um ponto de partida, não um limite rígido. `lib/resources/themes/color_styles.dart` é seu para modificar — adicione novos grupos de cores (ou campos individuais) além dos padrões, e então implemente-os na classe de cores de cada tema.

**1. Defina um grupo de cores personalizado**

Agrupe cores relacionadas em uma pequena classe imutável:

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

**2. Adicione-o ao `ColorStyles`**

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

**3. Implemente-o na classe de cores de cada tema**

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

Repita o mesmo override de `icons` em `dark_theme_colors.dart` com os valores do modo escuro.

**4. Use-o nos seus widgets**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Alternando tema

{{ config('app.name') }} suporta a capacidade de alternar temas em tempo real.

Por exemplo, se você precisa alternar o tema quando um usuário toca em um botão para ativar o "tema escuro".

Você pode fazer isso da seguinte forma:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // define o tema para usar o "tema escuro"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// ou

TextButton(onPressed: () {

    // define o tema para usar o "tema claro"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Fontes

Atualizar sua fonte principal em todo o app é fácil no {{ config('app.name') }}. Abra `lib/config/design.dart` e atualize `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Incluímos a biblioteca <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> no repositório, então você pode começar a usar todas as fontes com pouco esforço. Para mudar para uma fonte Google diferente, basta alterar a chamada:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Confira as fontes na biblioteca oficial do <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> para entender mais.

Precisa usar uma fonte personalizada? Confira este guia - https://flutter.dev/docs/cookbook/design/fonts

Uma vez que você adicionou sua fonte, altere a variável como no exemplo abaixo.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo usado como exemplo para a fonte personalizada
```

<div id="design"></div>

## Design

O arquivo **lib/config/design.dart** é usado para gerenciar os elementos de design do seu app. Tudo é exposto através da classe `DesignConfig`:

`DesignConfig.appFont` contém a fonte do seu app.

`DesignConfig.logo` é usado para exibir o Logo do seu app.

Você pode modificar **lib/resources/widgets/logo_widget.dart** para personalizar como deseja exibir seu Logo.

`DesignConfig.loader` é usado para exibir um loader. {{ config('app.name') }} usará esta variável em alguns métodos auxiliares como o widget loader padrão.

Você pode modificar **lib/resources/widgets/loader_widget.dart** para personalizar como deseja exibir seu Loader.

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
// Cor dos seus colorStyles
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
