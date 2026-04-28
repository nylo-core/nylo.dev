# Themes & Styling

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в темы")
- Темы
  - [Светлая и тёмная темы](#light-and-dark-themes "Светлая и тёмная темы")
  - [Создание темы](#creating-a-theme "Создание темы")
- Настройка
  - [Цвета темы](#theme-colors "Цвета темы")
  - [Использование цветов](#using-colors "Использование цветов")
  - [Базовые стили](#base-styles "Базовые стили")
  - [Расширение стилей цветов](#extending-color-styles "Расширение стилей цветов")
  - [Переключение темы](#switching-theme "Переключение темы")
  - [Шрифты](#fonts "Шрифты")
  - [Дизайн](#design "Дизайн")
- [Расширения текста](#text-extensions "Расширения текста")


<div id="introduction"></div>

## Введение

Вы можете управлять стилями пользовательского интерфейса вашего приложения с помощью тем. Темы позволяют изменять, например, размер шрифта текста, внешний вид кнопок и общий облик приложения.

Если вы новичок в работе с темами, примеры на сайте Flutter помогут вам начать <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">здесь</a>.

Из коробки {{ config('app.name') }} включает предварительно настроенные темы для `светлого режима` и `тёмного режима`.

Тема также будет обновляться, если устройство переключается в <b>'светлый/тёмный'</b> режим.

<div id="light-and-dark-themes"></div>

## Светлая и тёмная темы

Каждая тема находится в собственном поддиректории внутри `lib/resources/themes/`:

- Светлая тема – `lib/resources/themes/light/light_theme.dart`
- Цвета светлой темы – `lib/resources/themes/light/light_theme_colors.dart`
- Тёмная тема – `lib/resources/themes/dark/dark_theme.dart`
- Цвета тёмной темы – `lib/resources/themes/dark/dark_theme_colors.dart`

Обе темы используют общий builder в `lib/resources/themes/base_theme.dart` и интерфейс `ColorStyles` в `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Создание темы

Если вы хотите иметь несколько тем для вашего приложения, у нас есть простой способ это сделать. Если вы новичок в работе с темами, следуйте инструкции.

Сначала выполните следующую команду в терминале

``` bash
dart run nylo_framework:main make:theme bright_theme
```

<b>Примечание:</b> замените **bright_theme** на название вашей новой темы.

Это создаст новую директорию темы в `lib/resources/themes/bright/`, содержащую `bright_theme.dart` и `bright_theme_colors.dart`, и зарегистрирует её в `lib/bootstrap/theme.dart`.

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

  BaseThemeConfig<ColorStyles>( // новая тема добавлена автоматически
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

Вы можете изменить цвета для вашей новой темы в файле **lib/resources/themes/bright/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Цвета темы

Для управления цветами темы в вашем проекте обратитесь к директориям `lib/resources/themes/light/` и `lib/resources/themes/dark/`. Каждая содержит файл цветов для своей темы — `light_theme_colors.dart` и `dark_theme_colors.dart`.

Значения цветов организованы в группы (`general`, `appBar`, `bottomTabBar`), определённые фреймворком. Класс цветов вашей темы расширяет `ColorStyles` и предоставляет экземпляр каждой группы:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Цвета для общего использования.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Цвета для панели приложения.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Цвета для нижней панели вкладок.
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

## Использование цветов в виджетах

Используйте хелпер `nyColorStyle<T>(context)` для чтения цветов активной темы. Передайте тип `ColorStyles` вашего проекта, чтобы вызов был полностью типизирован:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// внутри метода build виджета:
final colors = nyColorStyle<ColorStyles>(context);

// цвет фона активной темы
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Чтение цветов из конкретной темы (независимо от активной):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Базовые стили

Базовые стили позволяют описать каждую тему через единый интерфейс. {{ config('app.name') }} поставляется с `lib/resources/themes/color_styles.dart` — это контракт, который реализуют как `light_theme_colors.dart`, так и `dark_theme_colors.dart`.

`ColorStyles` расширяет `ThemeColor` из фреймворка, который предоставляет три предопределённые группы цветов: `GeneralColors`, `AppBarColors` и `BottomTabBarColors`. Builder базовой темы (`lib/resources/themes/base_theme.dart`) считывает эти группы при построении `ThemeData`, поэтому всё, что вы помещаете в них, автоматически подключается к соответствующим виджетам.

<br>

Файл `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Цвета для общего использования.
  @override
  GeneralColors get general;

  /// Цвета для панели приложения.
  @override
  AppBarColors get appBar;

  /// Цвета для нижней панели вкладок.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Три группы предоставляют следующие поля:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Чтобы добавить поля помимо этих стандартных — собственные кнопки, иконки, бейджи и т.д. — см. [Расширение стилей цветов](#extending-color-styles).

<div id="extending-color-styles"></div>

## Расширение стилей цветов

<!-- uncertain: new section "Extending color styles" with new anchor #extending-color-styles — not present in the previous locale file -->
Три группы по умолчанию (`general`, `appBar`, `bottomTabBar`) — это отправная точка, а не жёсткое ограничение. `lib/resources/themes/color_styles.dart` открыт для ваших изменений — добавляйте новые группы цветов (или отдельные поля) поверх стандартных, а затем реализуйте их в классе цветов каждой темы.

**1. Определите пользовательскую группу цветов**

Сгруппируйте связанные цвета в небольшой неизменяемый класс:

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

**2. Добавьте её в `ColorStyles`**

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

  // Пользовательские группы
  IconColors get icons;
}
```

**3. Реализуйте её в классе цветов каждой темы**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...существующие overrides...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Повторите тот же override `icons` в `dark_theme_colors.dart` со значениями для тёмного режима.

**4. Используйте в своих виджетах**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Переключение темы

{{ config('app.name') }} поддерживает возможность переключения тем на лету.

Например, если вам нужно переключить тему, когда пользователь нажимает кнопку для активации "тёмной темы".

Вы можете реализовать это следующим образом:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // установить тёмную тему
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// или

TextButton(onPressed: () {

    // установить светлую тему
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Шрифты

Обновить основной шрифт во всём приложении очень просто в {{ config('app.name') }}. Откройте `lib/config/design.dart` и обновите `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Мы включаем библиотеку <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> в репозиторий, чтобы вы могли начать использовать все шрифты с минимальными усилиями. Чтобы переключиться на другой шрифт Google, просто измените вызов:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Ознакомьтесь со шрифтами в официальной библиотеке <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a>, чтобы узнать больше.

Нужно использовать пользовательский шрифт? Ознакомьтесь с этим руководством - https://flutter.dev/docs/cookbook/design/fonts

После добавления шрифта измените переменную, как в примере ниже.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo используется как пример пользовательского шрифта
```

<div id="design"></div>

## Дизайн

Файл **lib/config/design.dart** используется для управления элементами дизайна вашего приложения. Всё доступно через класс `DesignConfig`:

`DesignConfig.appFont` содержит шрифт вашего приложения.

`DesignConfig.logo` используется для отображения логотипа вашего приложения.

Вы можете изменить **lib/resources/widgets/logo_widget.dart**, чтобы настроить отображение логотипа.

`DesignConfig.loader` используется для отображения индикатора загрузки. {{ config('app.name') }} будет использовать эту переменную в некоторых вспомогательных методах как виджет загрузки по умолчанию.

Вы можете изменить **lib/resources/widgets/loader_widget.dart**, чтобы настроить отображение индикатора загрузки.

<div id="text-extensions"></div>

## Расширения текста

Вот доступные расширения текста, которые вы можете использовать в {{ config('app.name') }}.

| Название правила   | Использование | Описание |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Применяет текстовую тему **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Применяет текстовую тему **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Применяет текстовую тему **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Применяет текстовую тему **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Применяет текстовую тему **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Применяет текстовую тему **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Применяет текстовую тему **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Применяет текстовую тему **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Применяет текстовую тему **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Применяет текстовую тему **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Применяет текстовую тему **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Применяет текстовую тему **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Применяет текстовую тему **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Применяет текстовую тему **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Применяет текстовую тему **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Применяет жирное начертание к текстовому виджету |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Применяет лёгкое начертание к текстовому виджету |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Устанавливает другой цвет текста для виджета Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Выравнивание текста по левому краю |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Выравнивание текста по правому краю |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Выравнивание текста по центру |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Установить максимальное количество строк для текстового виджета |

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

#### Жирное начертание

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Лёгкое начертание

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Установка цвета

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Цвет из ваших colorStyles
```

<div id="text-extension-align-left"></div>

#### Выравнивание по левому краю

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Выравнивание по правому краю

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Выравнивание по центру

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Установка максимального количества строк

``` dart
Text("Hello World").setMaxLines(5)
```
