# InputField

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Валидация](#validation "Валидация")
- Варианты
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Маскирование ввода](#masking "Маскирование ввода")
- [Верхний и нижний колонтитулы](#header-footer "Верхний и нижний колонтитулы")
- [Очищаемый ввод](#clearable "Очищаемый ввод")
- [Управление состоянием](#state-management "Управление состоянием")
- [Параметры](#parameters "Параметры")


<div id="introduction"></div>

## Введение

Виджет **InputField** -- это улучшенное текстовое поле {{ config('app.name') }} со встроенной поддержкой:

- Валидации с настраиваемыми сообщениями об ошибках
- Переключения видимости пароля
- Маскирования ввода (номера телефонов, кредитные карты и т.д.)
- Верхних и нижних виджетов
- Очищаемого ввода
- Интеграции с управлением состоянием
- Тестовых данных для разработки

<div id="basic-usage"></div>

## Базовое использование

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## Валидация

Используйте параметр `formValidator` для добавления правил валидации:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Поле будет валидироваться, когда пользователь переведёт фокус на другой элемент.

### Пользовательский обработчик валидации

Обработка ошибок валидации программно:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

Все доступные правила валидации смотрите в документации [Валидация](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Предварительно настроенное поле пароля со скрытым текстом и переключателем видимости:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Настройка видимости пароля

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Предварительно настроенное поле email с клавиатурой email и автофокусом:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Автоматически делает заглавной первую букву каждого слова:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Маскирование ввода

Применение масок ввода для форматированных данных, таких как номера телефонов или кредитные карты:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Параметр | Описание |
|----------|----------|
| `mask` | Шаблон маски с использованием `#` в качестве заполнителя |
| `maskMatch` | Regex-шаблон для допустимых символов ввода |
| `maskedReturnValue` | Если true, возвращает отформатированное значение; если false, возвращает исходный ввод |

<div id="header-footer"></div>

## Верхний и нижний колонтитулы

Добавление виджетов над или под полем ввода:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## Очищаемый ввод

Добавление кнопки очистки для быстрого удаления содержимого поля:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## Управление состоянием

Присвойте полю ввода имя состояния для программного управления:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Действия над состоянием

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Параметры

### Основные параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `controller` | `TextEditingController` | обязательный | Управляет редактируемым текстом |
| `labelText` | `String?` | - | Метка над полем |
| `hintText` | `String?` | - | Текст-подсказка |
| `formValidator` | `FormValidator?` | - | Правила валидации |
| `validateOnFocusChange` | `bool` | `true` | Валидация при изменении фокуса |
| `obscureText` | `bool` | `false` | Скрытие ввода (для паролей) |
| `keyboardType` | `TextInputType` | `text` | Тип клавиатуры |
| `autoFocus` | `bool` | `false` | Автофокус при построении |
| `readOnly` | `bool` | `false` | Только для чтения |
| `enabled` | `bool?` | - | Включение/отключение поля |
| `maxLines` | `int?` | `1` | Максимальное количество строк |
| `maxLength` | `int?` | - | Максимальное количество символов |

### Параметры стилизации

| Параметр | Тип | Описание |
|----------|-----|----------|
| `backgroundColor` | `Color?` | Цвет фона поля |
| `borderRadius` | `BorderRadius?` | Радиус скругления |
| `border` | `InputBorder?` | Граница по умолчанию |
| `focusedBorder` | `InputBorder?` | Граница при фокусе |
| `enabledBorder` | `InputBorder?` | Граница при включённом состоянии |
| `contentPadding` | `EdgeInsetsGeometry?` | Внутренние отступы |
| `style` | `TextStyle?` | Стиль текста |
| `labelStyle` | `TextStyle?` | Стиль текста метки |
| `hintStyle` | `TextStyle?` | Стиль текста подсказки |
| `prefixIcon` | `Widget?` | Иконка перед вводом |

### Параметры маскирования

| Параметр | Тип | Описание |
|----------|-----|----------|
| `mask` | `String?` | Шаблон маски (например, "###-####") |
| `maskMatch` | `String?` | Regex для допустимых символов |
| `maskedReturnValue` | `bool?` | Возвращать маскированное или исходное значение |

### Параметры функциональности

| Параметр | Тип | Описание |
|----------|-----|----------|
| `header` | `Widget?` | Виджет над полем |
| `footer` | `Widget?` | Виджет под полем |
| `clearable` | `bool?` | Показывать кнопку очистки |
| `clearIcon` | `Widget?` | Пользовательская иконка очистки |
| `passwordVisible` | `bool?` | Показывать переключатель пароля |
| `passwordViewable` | `bool?` | Разрешить переключение видимости пароля |
| `dummyData` | `String?` | Тестовые данные для разработки |
| `stateName` | `String?` | Имя для управления состоянием |
| `onChanged` | `Function(String)?` | Вызывается при изменении значения |
