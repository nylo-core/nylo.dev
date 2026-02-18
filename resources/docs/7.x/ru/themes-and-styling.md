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

- Светлая тема - `lib/resources/themes/light_theme.dart`
- Тёмная тема - `lib/resources/themes/dark_theme.dart`

Внутри этих файлов вы найдёте предварительно определённые ThemeData и ThemeStyle.



<div id="creating-a-theme"></div>

## Создание темы

Если вы хотите иметь несколько тем для вашего приложения, у нас есть простой способ это сделать. Если вы новичок в работе с темами, следуйте инструкции.

Сначала выполните следующую команду в терминале

``` bash
metro make:theme bright_theme
```

<b>Примечание:</b> замените **bright_theme** на название вашей новой темы.

Это создаст новую тему в директории `/resources/themes/` и файл цветов темы в `/resources/themes/styles/`.

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

  BaseThemeConfig<ColorStyles>( // новая тема добавлена автоматически
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

Вы можете изменить цвета для вашей новой темы в файле **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Цвета темы

Для управления цветами темы в вашем проекте обратитесь к директории `lib/resources/themes/styles`.
Эта директория содержит цвета стилей для light_theme_colors.dart и dark_theme_colors.dart.

В этом файле у вас должно быть что-то подобное.

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

## Использование цветов в виджетах

``` dart
import 'package:flutter_app/config/theme.dart';
...

// получает цвет фона светлой/тёмной темы в зависимости от текущей темы
ThemeColor.get(context).background

// пример использования класса "ThemeColor"
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// или

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Цвета светлой темы - primary content
  ),
),
```

<div id="base-styles"></div>

## Базовые стили

Базовые стили позволяют настраивать цвета различных виджетов из одного места в коде.

{{ config('app.name') }} поставляется с предварительно настроенными базовыми стилями для вашего проекта, расположенными в `lib/resources/themes/styles/color_styles.dart`.

Эти стили предоставляют интерфейс для цветов вашей темы в `light_theme_colors.dart` и `dart_theme_colors.dart`.

<br>

Файл `lib/resources/themes/styles/color_styles.dart`

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

Вы можете добавить дополнительные стили здесь и затем реализовать цвета в вашей теме.

<div id="switching-theme"></div>

## Переключение темы

{{ config('app.name') }} поддерживает возможность переключения тем на лету.

Например, если вам нужно переключить тему, когда пользователь нажимает кнопку для активации "тёмной темы".

Вы можете реализовать это следующим образом:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
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

Обновить основной шрифт во всём приложении очень просто в {{ config('app.name') }}. Откройте файл `lib/config/design.dart` и измените следующее.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Мы включаем библиотеку <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> в репозиторий, чтобы вы могли начать использовать все шрифты с минимальными усилиями.
Чтобы обновить шрифт на другой, вы можете сделать следующее:
``` dart
// СТАРЫЙ
// final TextStyle appThemeFont = GoogleFonts.lato();

// НОВЫЙ
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Ознакомьтесь со шрифтами в официальной библиотеке <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a>, чтобы узнать больше

Нужно использовать пользовательский шрифт? Ознакомьтесь с этим руководством - https://flutter.dev/docs/cookbook/design/fonts

После добавления шрифта измените переменную, как в примере ниже.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo используется как пример пользовательского шрифта
```

<div id="design"></div>

## Дизайн

Файл **config/design.dart** используется для управления элементами дизайна вашего приложения.

Переменная `appFont` содержит шрифт вашего приложения.

Переменная `logo` используется для отображения логотипа вашего приложения.

Вы можете изменить **resources/widgets/logo_widget.dart**, чтобы настроить отображение логотипа.

Переменная `loader` используется для отображения индикатора загрузки. {{ config('app.name') }} будет использовать эту переменную в некоторых вспомогательных методах как виджет загрузки по умолчанию.

Вы можете изменить **resources/widgets/loader_widget.dart**, чтобы настроить отображение индикатора загрузки.

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
