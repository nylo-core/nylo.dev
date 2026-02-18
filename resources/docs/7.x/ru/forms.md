# Forms

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Начало работы
  - [Создание формы](#creating-forms "Создание формы")
  - [Отображение формы](#displaying-a-form "Отображение формы")
  - [Отправка формы](#submitting-a-form "Отправка формы")
- Типы полей
  - [Текстовые поля](#text-fields "Текстовые поля")
  - [Числовые поля](#number-fields "Числовые поля")
  - [Поля пароля](#password-fields "Поля пароля")
  - [Поля электронной почты](#email-fields "Поля электронной почты")
  - [Поля URL](#url-fields "Поля URL")
  - [Текстовые области](#text-area-fields "Текстовые области")
  - [Поля номера телефона](#phone-number-fields "Поля номера телефона")
  - [Заглавные буквы в словах](#capitalize-words-fields "Заглавные буквы в словах")
  - [Заглавные буквы в предложениях](#capitalize-sentences-fields "Заглавные буквы в предложениях")
  - [Поля даты](#date-fields "Поля даты")
  - [Поля даты и времени](#datetime-fields "Поля даты и времени")
  - [Поля с маской ввода](#masked-input-fields "Поля с маской ввода")
  - [Поля валюты](#currency-fields "Поля валюты")
  - [Поля флажков](#checkbox-fields "Поля флажков")
  - [Поля переключателей](#switch-box-fields "Поля переключателей")
  - [Поля выбора](#picker-fields "Поля выбора")
  - [Радио-поля](#radio-fields "Радио-поля")
  - [Поля-чипы](#chip-fields "Поля-чипы")
  - [Поля ползунков](#slider-fields "Поля ползунков")
  - [Поля диапазонных ползунков](#range-slider-fields "Поля диапазонных ползунков")
  - [Пользовательские поля](#custom-fields "Пользовательские поля")
  - [Поля виджетов](#widget-fields "Поля виджетов")
- [FormCollection](#form-collection "FormCollection")
- [Валидация формы](#form-validation "Валидация формы")
- [Управление данными формы](#managing-form-data "Управление данными формы")
  - [Начальные данные](#initial-data "Начальные данные")
  - [Установка значений полей](#setting-field-values "Установка значений полей")
  - [Установка параметров полей](#setting-field-options "Установка параметров полей")
  - [Чтение данных формы](#reading-form-data "Чтение данных формы")
  - [Очистка данных](#clearing-data "Очистка данных")
  - [Обновление полей](#finding-and-updating-fields "Обновление полей")
- [Кнопка отправки](#submit-button "Кнопка отправки")
- [Макет формы](#form-layout "Макет формы")
- [Видимость полей](#field-visibility "Видимость полей")
- [Стилизация полей](#field-styling "Стилизация полей")
- [Статические методы NyFormWidget](#ny-form-widget-static-methods "Статические методы NyFormWidget")
- [Справочник конструктора NyFormWidget](#ny-form-widget-constructor-reference "Справочник конструктора NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Справочник всех типов полей](#all-field-types-reference "Справочник всех типов полей")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет систему форм, построенную на основе `NyFormWidget`. Ваш класс формы наследует `NyFormWidget` и **является** виджетом — отдельная обёртка не нужна. Формы поддерживают встроенную валидацию, множество типов полей, стилизацию и управление данными.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Определение формы
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Отображение и отправка
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Создание формы

Используйте Metro CLI для создания новой формы:

``` bash
metro make:form LoginForm
```

Это создаст файл `lib/app/forms/login_form.dart`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

Формы наследуют `NyFormWidget` и переопределяют метод `fields()` для определения полей формы. Каждое поле использует именованный конструктор, такой как `Field.text()`, `Field.email()` или `Field.password()`. Геттер `static NyFormActions get actions` предоставляет удобный способ взаимодействия с формой из любого места вашего приложения.


<div id="displaying-a-form"></div>

## Отображение формы

Поскольку ваш класс формы наследует `NyFormWidget`, он **является** виджетом. Используйте его напрямую в дереве виджетов:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## Отправка формы

Существует три способа отправки формы:

### Использование onSubmit и submitButton

Передайте `onSubmit` и `submitButton` при создании формы. {{ config('app.name') }} предоставляет готовые кнопки, которые работают как кнопки отправки:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Доступные стили кнопок: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Использование NyFormActions

Используйте геттер `actions` для отправки из любого места:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### Использование статического метода NyFormWidget.submit()

Отправка формы по имени из любого места:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

При отправке форма проверяет все поля. Если валидация прошла успешно, вызывается `onSuccess` с `Map<String, dynamic>` данных полей (ключи — версии имён полей в snake_case). Если валидация не прошла, по умолчанию отображается уведомление об ошибке, и вызывается `onFailure`, если он предоставлен.


<div id="field-types"></div>

## Типы полей

{{ config('app.name') }} v7 предоставляет 22 типа полей через именованные конструкторы класса `Field`. Все конструкторы полей имеют следующие общие параметры:

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `key` | `String` | Обязательный | Идентификатор поля (позиционный) |
| `label` | `String?` | `null` | Пользовательская метка (по умолчанию — key в формате Title Case) |
| `value` | `dynamic` | `null` | Начальное значение |
| `validator` | `FormValidator?` | `null` | Правила валидации |
| `autofocus` | `bool` | `false` | Автофокус при загрузке |
| `dummyData` | `String?` | `null` | Тестовые данные для разработки |
| `header` | `Widget?` | `null` | Виджет над полем |
| `footer` | `Widget?` | `null` | Виджет под полем |
| `titleStyle` | `TextStyle?` | `null` | Пользовательский стиль текста метки |
| `hidden` | `bool` | `false` | Скрыть поле |
| `readOnly` | `bool?` | `null` | Сделать поле только для чтения |
| `style` | `FieldStyle?` | Зависит от типа | Конфигурация стиля поля |
| `onChanged` | `Function(dynamic)?` | `null` | Обратный вызов при изменении значения |

<div id="text-fields"></div>

### Текстовые поля

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Тип стиля: `FieldStyleTextField`

<div id="number-fields"></div>

### Числовые поля

``` dart
Field.number("Age")

// Десятичные числа
Field.number("Score", decimal: true)
```

Параметр `decimal` определяет, разрешён ли ввод десятичных чисел. Тип стиля: `FieldStyleTextField`

<div id="password-fields"></div>

### Поля пароля

``` dart
Field.password("Password")

// С переключателем видимости
Field.password("Password", viewable: true)
```

Параметр `viewable` добавляет переключатель показа/скрытия пароля. Тип стиля: `FieldStyleTextField`

<div id="email-fields"></div>

### Поля электронной почты

``` dart
Field.email("Email", validator: FormValidator.email())
```

Автоматически устанавливает тип клавиатуры для email и фильтрует пробелы. Тип стиля: `FieldStyleTextField`

<div id="url-fields"></div>

### Поля URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Устанавливает тип клавиатуры для URL. Тип стиля: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Текстовые области

``` dart
Field.textArea("Description")
```

Многострочный текстовый ввод. Тип стиля: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Поля номера телефона

``` dart
Field.phoneNumber("Mobile Phone")
```

Автоматически форматирует ввод номера телефона. Тип стиля: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Заглавные буквы в словах

``` dart
Field.capitalizeWords("Full Name")
```

Делает заглавной первую букву каждого слова. Тип стиля: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Заглавные буквы в предложениях

``` dart
Field.capitalizeSentences("Bio")
```

Делает заглавной первую букву каждого предложения. Тип стиля: `FieldStyleTextField`

<div id="date-fields"></div>

### Поля даты

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Отключить кнопку очистки
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Пользовательская иконка очистки
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Открывает выбор даты. По умолчанию поле показывает кнопку очистки, позволяющую пользователям сбросить значение. Установите `canClear: false`, чтобы скрыть её, или используйте `clearIconData` для изменения иконки. Тип стиля: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Поля даты и времени

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Открывает выбор даты и времени. Вы можете задать `firstDate`, `lastDate`, `dateFormat` и `initialPickerDateTime` напрямую как параметры верхнего уровня. Тип стиля: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Поля с маской ввода

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Возвращает отформатированное значение
)
```

Символ `#` в маске заменяется пользовательским вводом. Используйте `match` для управления допустимыми символами. Когда `maskReturnValue` установлен в `true`, возвращаемое значение включает форматирование маски.

<div id="currency-fields"></div>

### Поля валюты

``` dart
Field.currency("Price", currency: "usd")
```

Параметр `currency` является обязательным и определяет формат валюты. Тип стиля: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Поля флажков

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Тип стиля: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Поля переключателей

``` dart
Field.switchBox("Enable Notifications")
```

Тип стиля: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Поля выбора

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// С парами ключ-значение
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

Параметр `options` требует `FormCollection` (не обычный список). Подробнее см. [FormCollection](#form-collection). Тип стиля: `FieldStylePicker`

#### Стили элементов списка

Вы можете настроить отображение элементов в нижнем модальном окне выбора с помощью `PickerListTileStyle`. По умолчанию нижнее модальное окно показывает простые текстовые элементы. Используйте встроенные пресеты для добавления индикаторов выбора или предоставьте полностью пользовательский построитель.

**Стиль радио** — показывает иконку радиокнопки в качестве ведущего виджета:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// С пользовательским цветом активного состояния
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Стиль галочки** — показывает иконку галочки в качестве завершающего виджета при выборе:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Пользовательский построитель** — полный контроль над виджетом каждого элемента:

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

Оба пресетных стиля также поддерживают `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` и `selectedTileColor`:

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### Радио-поля

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Параметр `options` требует `FormCollection`. Тип стиля: `FieldStyleRadio`

<div id="chip-fields"></div>

### Поля-чипы

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// С парами ключ-значение
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Позволяет множественный выбор через виджеты-чипы. Параметр `options` требует `FormCollection`. Тип стиля: `FieldStyleChip`

<div id="slider-fields"></div>

### Поля ползунков

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Тип стиля: `FieldStyleSlider` — настройте `min`, `max`, `divisions`, цвета, отображение значения и многое другое.

<div id="range-slider-fields"></div>

### Поля диапазонных ползунков

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Возвращает объект `RangeValues`. Тип стиля: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Пользовательские поля

Используйте `Field.custom()` для предоставления собственного stateful-виджета:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Параметр `child` требует виджет, наследующий `NyFieldStatefulWidget`. Это даёт вам полный контроль над отрисовкой и поведением поля.

<div id="widget-fields"></div>

### Поля виджетов

Используйте `Field.widget()` для встраивания любого виджета внутрь формы без создания поля формы:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Поля виджетов не участвуют в валидации или сборе данных. Они предназначены исключительно для компоновки.


<div id="form-collection"></div>

## FormCollection

Поля выбора, радио и чипы требуют `FormCollection` для своих параметров. `FormCollection` предоставляет единый интерфейс для работы с различными форматами параметров.

### Создание FormCollection

``` dart
// Из списка строк (значение и метка совпадают)
FormCollection.from(["Red", "Green", "Blue"])

// То же самое, явно
FormCollection.fromArray(["Red", "Green", "Blue"])

// Из словаря (ключ = значение, значение = метка)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// Из структурированных данных (полезно для ответов API)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` автоматически определяет формат данных и делегирует соответствующему конструктору.

### FormOption

Каждый параметр в `FormCollection` является `FormOption` со свойствами `value` и `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Запросы к параметрам

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## Валидация формы

Добавьте валидацию к любому полю с помощью параметра `validator` и `FormValidator`:

``` dart
// Именованный конструктор
Field.email("Email", validator: FormValidator.email())

// Цепочка правил
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Пароль с уровнем сложности
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Булева валидация
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Пользовательская встроенная валидация
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

При отправке формы проверяются все валидаторы. Если какой-либо из них не проходит, отображается уведомление с первым сообщением об ошибке и вызывается обратный вызов `onFailure`.

**См. также:** [Валидация](/docs/7.x/validation#validation-rules) для полного списка доступных валидаторов.


<div id="managing-form-data"></div>

## Управление данными формы

<div id="initial-data"></div>

### Начальные данные

Существует два способа задать начальные данные для формы.

**Вариант 1: Переопределите геттер `init` в вашем классе формы**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

Геттер `init` может возвращать как синхронный `Map`, так и асинхронный `Future<Map>`. Ключи сопоставляются с именами полей с использованием нормализации snake_case, поэтому `"First Name"` соответствует полю с ключом `"First Name"`.

#### Использование `define()` в init

Используйте вспомогательный метод `define()`, когда нужно задать **параметры** (или одновременно значение и параметры) для поля в `init`. Это полезно для полей выбора, чипов и радио, где параметры приходят из API или другого асинхронного источника.

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` принимает два именованных параметра:

| Параметр | Описание |
|----------|----------|
| `value` | Начальное значение поля |
| `options` | Параметры для полей выбора, чипов или радио |

``` dart
// Задать только параметры (без начального значения)
"Category": define(options: categories),

// Задать только начальное значение
"Price": define(value: "100"),

// Задать и значение, и параметры
"Country": define(value: "us", options: countries),

// Простые значения по-прежнему работают для обычных полей
"Name": "John",
```

Параметры, переданные в `define()`, могут быть `List`, `Map` или `FormCollection`. Они автоматически преобразуются в `FormCollection` при применении.

**Вариант 2: Передайте `initialData` в виджет формы**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Установка значений полей

Используйте `NyFormActions` для установки значений полей из любого места:

``` dart
// Установить значение одного поля
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Установка параметров полей

Динамическое обновление параметров для полей выбора, чипов или радио:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Чтение данных формы

Данные формы доступны через обратный вызов `onSubmit` при отправке формы или через обратный вызов `onChanged` для обновлений в реальном времени:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data — это Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Очистка данных

``` dart
// Очистить все поля
EditAccountForm.actions.clear();

// Очистить конкретное поле
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Обновление полей

``` dart
// Обновить значение поля
EditAccountForm.actions.updateField("First Name", "Jane");

// Обновить UI формы
EditAccountForm.actions.refresh();

// Обновить поля формы (повторный вызов fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Кнопка отправки

Передайте `submitButton` и обратный вызов `onSubmit` при создании формы:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

`submitButton` автоматически отображается под полями формы. Вы можете использовать любой из встроенных стилей кнопок или пользовательский виджет.

Вы также можете использовать любой виджет в качестве кнопки отправки, передав его как `footer`:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## Макет формы

Размещайте поля рядом друг с другом, обернув их в `List`:

``` dart
@override
fields() => [
  // Одно поле (полная ширина)
  Field.text("Title"),

  // Два поля в ряд
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Ещё одно поле
  Field.textArea("Bio"),

  // Ползунок и диапазонный ползунок в ряд
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Встраивание виджета, не являющегося полем
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Поля в `List` отображаются в `Row` с равными ширинами `Expanded`. Расстояние между полями контролируется параметром `crossAxisSpacing` в `NyFormWidget`.


<div id="field-visibility"></div>

## Видимость полей

Показывайте или скрывайте поля программно с помощью методов `hide()` и `show()` класса `Field`. Вы можете обращаться к полям внутри класса формы или через обратный вызов `onChanged`:

``` dart
// Внутри вашего подкласса NyFormWidget или обратного вызова onChanged
Field nameField = ...;

// Скрыть поле
nameField.hide();

// Показать поле
nameField.show();
```

Скрытые поля не отображаются в UI, но по-прежнему существуют в списке полей формы.


<div id="field-styling"></div>

## Стилизация полей

Каждый тип поля имеет соответствующий подкласс `FieldStyle` для стилизации:

| Тип поля | Класс стиля |
|----------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Передайте объект стиля в параметр `style` любого поля:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## Статические методы NyFormWidget

`NyFormWidget` предоставляет статические методы для взаимодействия с формами по имени из любого места вашего приложения:

| Метод | Описание |
|-------|----------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Отправить форму по имени |
| `NyFormWidget.stateRefresh(name)` | Обновить состояние UI формы |
| `NyFormWidget.stateSetValue(name, key, value)` | Установить значение поля по имени формы |
| `NyFormWidget.stateSetOptions(name, key, options)` | Установить параметры поля по имени формы |
| `NyFormWidget.stateClearData(name)` | Очистить все поля по имени формы |
| `NyFormWidget.stateRefreshForm(name)` | Обновить поля формы (повторный вызов `fields()`) |

``` dart
// Отправить форму с именем "LoginForm" из любого места
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Обновить значение поля удалённо
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Очистить все данные формы
NyFormWidget.stateClearData("LoginForm");
```

> **Совет:** Предпочитайте использовать `NyFormActions` (см. ниже) вместо прямого вызова этих статических методов — это более лаконично и менее подвержено ошибкам.


<div id="ny-form-widget-constructor-reference"></div>

## Справочник конструктора NyFormWidget

При наследовании `NyFormWidget` вы можете передать следующие параметры конструктора:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Горизонтальный отступ между полями в ряду
  double mainAxisSpacing = 10,   // Вертикальный отступ между полями
  Map<String, dynamic>? initialData, // Начальные значения полей
  Function(Field field, dynamic value)? onChanged, // Обратный вызов при изменении поля
  Widget? header,                // Виджет над формой
  Widget? submitButton,          // Виджет кнопки отправки
  Widget? footer,                // Виджет под формой
  double headerSpacing = 10,     // Отступ после заголовка
  double submitButtonSpacing = 10, // Отступ после кнопки отправки
  double footerSpacing = 10,     // Отступ перед подвалом
  LoadingStyle? loadingStyle,    // Стиль индикатора загрузки
  bool locked = false,           // Делает форму только для чтения
  Function(dynamic data)? onSubmit,   // Вызывается с данными формы при успешной валидации
  Function(dynamic error)? onFailure, // Вызывается с ошибками при неудачной валидации
)
```

Обратный вызов `onChanged` получает изменённый `Field` и его новое значение:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` предоставляет удобный способ взаимодействия с формой из любого места вашего приложения. Определите его как статический геттер в вашем классе формы:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### Доступные действия

| Метод | Описание |
|-------|----------|
| `actions.updateField(key, value)` | Установить значение поля |
| `actions.clearField(key)` | Очистить конкретное поле |
| `actions.clear()` | Очистить все поля |
| `actions.refresh()` | Обновить состояние UI формы |
| `actions.refreshForm()` | Повторно вызвать `fields()` и перестроить |
| `actions.setOptions(key, options)` | Установить параметры для полей выбора/чипов/радио |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Отправить с валидацией |

``` dart
// Обновить значение поля
LoginForm.actions.updateField("Email", "new@email.com");

// Очистить все данные формы
LoginForm.actions.clear();

// Отправить форму
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Переопределения NyFormWidget

Методы, которые можно переопределить в вашем подклассе `NyFormWidget`:

| Переопределение | Описание |
|-----------------|----------|
| `fields()` | Определить поля формы (обязательно) |
| `init` | Предоставить начальные данные (синхронно или асинхронно) |
| `onChange(field, data)` | Обработать изменения полей внутренне |


<div id="all-field-types-reference"></div>

## Справочник всех типов полей

| Конструктор | Ключевые параметры | Описание |
|-------------|-------------------|----------|
| `Field.text()` | — | Стандартный текстовый ввод |
| `Field.email()` | — | Ввод email с типом клавиатуры |
| `Field.password()` | `viewable` | Пароль с опциональным переключателем видимости |
| `Field.number()` | `decimal` | Числовой ввод, опциональные десятичные |
| `Field.currency()` | `currency` (обязательный) | Ввод в формате валюты |
| `Field.capitalizeWords()` | — | Ввод текста с заглавными буквами в словах |
| `Field.capitalizeSentences()` | — | Ввод текста с заглавными буквами в предложениях |
| `Field.textArea()` | — | Многострочный текстовый ввод |
| `Field.phoneNumber()` | — | Автоматическое форматирование номера телефона |
| `Field.url()` | — | Ввод URL с типом клавиатуры |
| `Field.mask()` | `mask` (обязательный), `match`, `maskReturnValue` | Ввод с маской |
| `Field.date()` | — | Выбор даты |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Выбор даты и времени |
| `Field.checkbox()` | — | Булев флажок |
| `Field.switchBox()` | — | Булев тумблер |
| `Field.picker()` | `options` (обязательный `FormCollection`) | Одиночный выбор из списка |
| `Field.radio()` | `options` (обязательный `FormCollection`) | Группа радиокнопок |
| `Field.chips()` | `options` (обязательный `FormCollection`) | Множественный выбор чипами |
| `Field.slider()` | — | Ползунок одного значения |
| `Field.rangeSlider()` | — | Ползунок диапазона значений |
| `Field.custom()` | `child` (обязательный `NyFieldStatefulWidget`) | Пользовательский stateful-виджет |
| `Field.widget()` | `child` (обязательный `Widget`) | Встраивание любого виджета (не поле) |
