# LanguageSwitcher

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Использование
    - [Выпадающий виджет](#usage-dropdown "Выпадающий виджет")
    - [Модальное нижнее окно](#usage-bottom-modal "Модальное нижнее окно")
- [Пользовательский построитель выпадающего списка](#custom-builder "Пользовательский построитель выпадающего списка")
- [Параметры](#parameters "Параметры")
- [Статические методы](#methods "Статические методы")


<div id="introduction"></div>

## Введение

Виджет **LanguageSwitcher** предоставляет простой способ управления переключением языков в ваших проектах {{ config('app.name') }}. Он автоматически определяет доступные языки в директории `/lang` и отображает их пользователю.

**Что делает LanguageSwitcher?**

- Отображает доступные языки из директории `/lang`
- Переключает язык приложения при выборе пользователем
- Сохраняет выбранный язык между перезапусками приложения
- Автоматически обновляет интерфейс при смене языка

> **Примечание**: Если ваше приложение ещё не локализовано, узнайте, как это сделать, в документации [Локализация](/docs/7.x/localization), прежде чем использовать этот виджет.

<div id="usage-dropdown"></div>

## Выпадающий виджет

Простейший способ использования `LanguageSwitcher` -- в качестве выпадающего списка в панели приложения:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Когда пользователь нажимает на выпадающий список, он видит перечень доступных языков. После выбора языка приложение автоматически переключится и обновит интерфейс.

<div id="usage-bottom-modal"></div>

## Модальное нижнее окно

Вы также можете отображать языки в модальном нижнем окне:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

Модальное нижнее окно отображает список языков с галочкой рядом с текущим выбранным языком.

### Настройка высоты модального окна

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## Пользовательский построитель выпадающего списка

Настройте внешний вид каждого варианта языка в выпадающем списке:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### Обработка смены языка

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## Параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `icon` | `Widget?` | - | Пользовательская иконка для кнопки выпадающего списка |
| `iconEnabledColor` | `Color?` | - | Цвет иконки выпадающего списка |
| `iconSize` | `double` | `24` | Размер иконки выпадающего списка |
| `dropdownBgColor` | `Color?` | - | Цвет фона меню выпадающего списка |
| `hint` | `Widget?` | - | Виджет-подсказка при отсутствии выбранного языка |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Высота каждого элемента выпадающего списка |
| `elevation` | `int` | `8` | Возвышение меню выпадающего списка |
| `padding` | `EdgeInsetsGeometry?` | - | Отступы вокруг выпадающего списка |
| `borderRadius` | `BorderRadius?` | - | Радиус скругления меню выпадающего списка |
| `textStyle` | `TextStyle?` | - | Стиль текста элементов выпадающего списка |
| `langPath` | `String` | `'lang'` | Путь к языковым файлам в ресурсах |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Пользовательский построитель элементов выпадающего списка |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Выравнивание элементов выпадающего списка |
| `dropdownOnTap` | `Function()?` | - | Колбэк при нажатии на элемент выпадающего списка |
| `onTap` | `Function()?` | - | Колбэк при нажатии на кнопку выпадающего списка |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Колбэк при смене языка |

<div id="methods"></div>

## Статические методы

### Получение текущего языка

Получение текущего выбранного языка:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Сохранение языка

Ручное сохранение языковых предпочтений:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Очистка языка

Удаление сохранённых языковых предпочтений:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Получение данных о языке

Получение информации о языке по коду локали:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Получение списка языков

Получение всех доступных языков из директории `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Показ модального нижнего окна

Отображение модального окна выбора языка:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Поддерживаемые локали

Виджет `LanguageSwitcher` поддерживает сотни кодов локалей с понятными для человека названиями. Некоторые примеры:

| Код локали | Название языка |
|------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

Полный список включает региональные варианты для большинства языков.
