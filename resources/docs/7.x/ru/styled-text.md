# Styled Text

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Режим Children](#children-mode "Режим Children")
- [Режим Template](#template-mode "Режим Template")
  - [Стилизация плейсхолдеров](#styling-placeholders "Стилизация плейсхолдеров")
  - [Обратные вызовы нажатий](#tap-callbacks "Обратные вызовы нажатий")
  - [Ключи через разделитель Pipe](#pipe-keys "Ключи через разделитель Pipe")
  - [Стили Wildcard](#wildcard-styles "Стили Wildcard")
  - [Ключи локализации](#localization-keys "Ключи локализации")
- [Параметры](#parameters "Параметры")
- [Расширения Text](#text-extensions "Расширения Text")
  - [Типографские стили](#typography-styles "Типографские стили")
  - [Утилитарные методы](#utility-methods "Утилитарные методы")
- [Примеры](#examples "Практические примеры")

<div id="introduction"></div>

## Введение

**StyledText** --- это виджет для отображения форматированного текста со смешанными стилями, обратными вызовами нажатий и событиями указателя. Он рендерится как виджет `RichText` с несколькими дочерними `TextSpan`, что даёт вам точный контроль над каждым сегментом текста.

StyledText поддерживает два режима:

1. **Режим Children** --- передайте список виджетов `Text`, каждый со своим стилем
2. **Режим Template** --- используйте синтаксис `@{{placeholder}}` в строке и сопоставьте плейсхолдеры со стилями и действиями

<div id="basic-usage"></div>

## Базовое использование

``` dart
// Режим children - список виджетов Text
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Режим template - синтаксис плейсхолдеров
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Режим Children

Передайте список виджетов `Text` для компоновки стилизованного текста:

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

Базовый `style` применяется к любому дочернему элементу, у которого нет собственного стиля.

### События указателя

Обнаружение входа и выхода указателя из текстового сегмента:

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

## Режим Template

Используйте `StyledText.template()` с синтаксисом `@{{placeholder}}`:

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

Текст между `@{{ }}` является одновременно **отображаемым текстом** и **ключом** для поиска стилей и обратных вызовов нажатий.

<div id="styling-placeholders"></div>

### Стилизация плейсхолдеров

Сопоставьте имена плейсхолдеров с объектами `TextStyle`:

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

### Обратные вызовы нажатий

Сопоставьте имена плейсхолдеров с обработчиками нажатий:

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

### Ключи через разделитель Pipe

Применение одного стиля или обратного вызова к нескольким плейсхолдерам с помощью ключей, разделённых `|`:

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

Это сопоставляет один и тот же стиль и обратный вызов со всеми тремя плейсхолдерами.

<div id="wildcard-styles"></div>

### Стили Wildcard

Используйте `"*"` в качестве ключа для применения стиля или обратного вызова нажатия к любому плейсхолдеру, у которого нет собственного конкретного ключа:

``` dart
StyledText.template(
  "Hello @{{name}}, welcome to @{{app}}!",
  styles: {
    "*": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

И `name`, и `app` получают стиль wildcard. Если у плейсхолдера также есть явный ключ, явный ключ имеет приоритет над `"*"`.

``` dart
StyledText.template(
  "Click @{{here}} or @{{cancel}}.",
  styles: {
    "here": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "*": TextStyle(color: Colors.grey), // применяется только к "cancel"
  },
  onTap: {
    "*": () => Navigator.pop(context), // нажатие на любой плейсхолдер без соответствия
  },
)
```

<div id="localization-keys"></div>

### Ключи локализации

Используйте синтаксис `@{{key:text}}` для разделения **ключа поиска** и **отображаемого текста**. Это полезно для локализации --- ключ остаётся одинаковым для всех локалей, а отображаемый текст меняется в зависимости от языка.

``` dart
// В ваших файлах локали:
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
// EN отображает: "Learn Languages, Reading and Speaking in AppName"
// ES отображает: "Aprende Idiomas, Lectura y Habla en AppName"
```

Часть перед `:` --- это **ключ**, используемый для поиска стилей и обратных вызовов нажатий. Часть после `:` --- это **отображаемый текст**, который рендерится на экране. Без `:` плейсхолдер работает точно так же, как и раньше --- полная обратная совместимость.

Это работает со всеми существующими функциями, включая [ключи через разделитель Pipe](#pipe-keys) и [обратные вызовы нажатий](#tap-callbacks).

<div id="parameters"></div>

## Параметры

### StyledText (режим Children)

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | обязательный | Список виджетов Text |
| `style` | `TextStyle?` | null | Базовый стиль для всех дочерних элементов |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Обратный вызов входа указателя |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Обратный вызов выхода указателя |
| `spellOut` | `bool?` | null | Побуквенное озвучивание текста |
| `softWrap` | `bool` | `true` | Включить мягкий перенос |
| `textAlign` | `TextAlign` | `TextAlign.start` | Выравнивание текста |
| `textDirection` | `TextDirection?` | null | Направление текста |
| `maxLines` | `int?` | null | Максимальное количество строк |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Поведение при переполнении |
| `locale` | `Locale?` | null | Локаль текста |
| `strutStyle` | `StrutStyle?` | null | Стиль стойки |
| `textScaler` | `TextScaler?` | null | Масштабирование текста |
| `selectionColor` | `Color?` | null | Цвет выделения |

### StyledText.template (режим Template)

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `text` | `String` | обязательный | Текст шаблона с синтаксисом `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | Сопоставление имён плейсхолдеров со стилями |
| `onTap` | `Map<String, VoidCallback>?` | null | Сопоставление имён плейсхолдеров с обратными вызовами нажатий |
| `style` | `TextStyle?` | null | Базовый стиль для текста вне плейсхолдеров |

Все остальные параметры (`softWrap`, `textAlign`, `maxLines` и т.д.) такие же, как в конструкторе children.

<div id="text-extensions"></div>

## Расширения Text

{{ config('app.name') }} расширяет виджет `Text` из Flutter типографскими и утилитарными методами.

<div id="typography-styles"></div>

### Типографские стили

Применение типографских стилей Material Design к любому виджету `Text`:

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

Каждый принимает необязательные переопределения:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Доступные переопределения** (одинаковые для всех типографских методов):

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `color` | `Color?` | Цвет текста |
| `fontSize` | `double?` | Размер шрифта |
| `fontWeight` | `FontWeight?` | Насыщенность шрифта |
| `fontStyle` | `FontStyle?` | Курсив/обычный |
| `letterSpacing` | `double?` | Межбуквенное расстояние |
| `wordSpacing` | `double?` | Межсловное расстояние |
| `height` | `double?` | Высота строки |
| `decoration` | `TextDecoration?` | Декорация текста |
| `decorationColor` | `Color?` | Цвет декорации |
| `decorationStyle` | `TextDecorationStyle?` | Стиль декорации |
| `decorationThickness` | `double?` | Толщина декорации |
| `fontFamily` | `String?` | Семейство шрифтов |
| `shadows` | `List<Shadow>?` | Тени текста |
| `overflow` | `TextOverflow?` | Поведение при переполнении |

<div id="utility-methods"></div>

### Утилитарные методы

``` dart
// Насыщенность шрифта
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Выравнивание
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Максимальное количество строк
Text("Long text...").setMaxLines(2)

// Семейство шрифтов
Text("Custom font").setFontFamily("Roboto")

// Размер шрифта
Text("Big text").setFontSize(24)

// Пользовательский стиль
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Отступ
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Копировать с изменениями
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Примеры

### Ссылка на условия использования

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

### Отображение версии

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

### Абзац со смешанными стилями

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

### Цепочка типографики

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
