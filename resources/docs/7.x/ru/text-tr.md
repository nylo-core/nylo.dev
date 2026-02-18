# TextTr

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Интерполяция строк](#string-interpolation "Интерполяция строк")
- [Стилизованные конструкторы](#styled-constructors "Стилизованные конструкторы")
- [Параметры](#parameters "Параметры")


<div id="introduction"></div>

## Введение

Виджет **TextTr** --- это удобная обёртка над виджетом `Text` из Flutter, которая автоматически переводит своё содержимое, используя систему локализации {{ config('app.name') }}.

Вместо того чтобы писать:

``` dart
Text("hello_world".tr())
```

Вы можете написать:

``` dart
TextTr("hello_world")
```

Это делает ваш код чище и читаемее, особенно при работе с большим количеством переводимых строк.

<div id="basic-usage"></div>

## Базовое использование

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

Виджет ищет ключ перевода в ваших языковых файлах (например, `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Интерполяция строк

Используйте параметр `arguments` для подстановки динамических значений в переводы:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

В вашем языковом файле:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Результат: **Hello, John!**

### Множественные аргументы

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

Результат: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Стилизованные конструкторы

`TextTr` предоставляет именованные конструкторы, которые автоматически применяют стили текста из вашей темы:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Использует стиль `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Использует стиль `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Использует стиль `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Использует стиль `Theme.of(context).textTheme.labelLarge`.

### Пример со стилизованными конструкторами

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

## Параметры

`TextTr` поддерживает все стандартные параметры виджета `Text`:

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `data` | `String` | Ключ перевода для поиска |
| `arguments` | `Map<String, String>?` | Пары ключ-значение для интерполяции строк |
| `style` | `TextStyle?` | Стилизация текста |
| `textAlign` | `TextAlign?` | Выравнивание текста |
| `maxLines` | `int?` | Максимальное количество строк |
| `overflow` | `TextOverflow?` | Обработка переполнения |
| `softWrap` | `bool?` | Перенос текста по мягким разрывам |
| `textDirection` | `TextDirection?` | Направление текста |
| `locale` | `Locale?` | Локаль для отрисовки текста |
| `semanticsLabel` | `String?` | Метка доступности |

## Сравнение

| Подход | Код |
|----------|------|
| Традиционный | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| С аргументами | `TextTr("hello", arguments: {"name": "John"})` |
| Стилизованный | `TextTr.headlineLarge("title")` |
