# Validation

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в валидацию")
- Основы
  - [Валидация данных с помощью check()](#validating-data "Валидация данных с помощью check")
  - [Результаты валидации](#validation-results "Результаты валидации")
- FormValidator
  - [Использование FormValidator](#using-form-validator "Использование FormValidator")
  - [Именованные конструкторы FormValidator](#form-validator-named-constructors "Именованные конструкторы FormValidator")
  - [Цепочка правил валидации](#chaining-validation-rules "Цепочка правил валидации")
  - [Пользовательская валидация](#custom-validation "Пользовательская валидация")
  - [Использование FormValidator с полями](#form-validator-with-fields "Использование FormValidator с полями")
- [Доступные правила валидации](#validation-rules "Правила валидации")
- [Создание пользовательских правил валидации](#creating-custom-validation-rules "Создание пользовательских правил валидации")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет систему валидации, построенную на классе `FormValidator`. Вы можете валидировать данные внутри страниц с помощью метода `check()` или использовать `FormValidator` напрямую для автономной валидации и валидации на уровне полей.

``` dart
// Validate data in a NyPage/NyState using check()
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// FormValidator with form fields
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## Валидация данных с помощью check()

Внутри любой `NyPage` используйте метод `check()` для валидации данных. Он принимает callback, который получает список валидаторов. Используйте `.that()` для добавления данных и цепочки правил:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // All validations passed
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Validation failed
      print(bag.firstErrorMessage);
    });
  }
}
```

### Как работает check()

1. `check()` создаёт пустой `List<FormValidator>`
2. Ваш callback использует `.that(data)` для добавления нового `FormValidator` с данными в список
3. Каждый `.that()` возвращает `FormValidator`, к которому вы можете привязать цепочку правил
4. После callback каждый валидатор в списке проверяется
5. Результаты собираются в `FormValidationResponseBag`

### Валидация нескольких полей

``` dart
check((validate) {
  validate.that(_nameController.text, label: "Name").notEmpty().capitalized();
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_phoneController.text, label: "Phone").phoneNumberUs();
  validate.that(_ageController.text, label: "Age").numeric().minValue(18);
}, onSuccess: () {
  _submitForm();
});
```

Необязательный параметр `label` задаёт человеко-читаемое имя, используемое в сообщениях об ошибках (например, "The Email must be a valid email address.").

<div id="validation-results"></div>

## Результаты валидации

Метод `check()` возвращает `FormValidationResponseBag` (`List<FormValidationResult>`), который вы также можете проверить напрямую:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // Get the first error message
  String? error = bag.firstErrorMessage;
  print(error);

  // Get all failed results
  List<FormValidationResult> errors = bag.validationErrors;

  // Get all successful results
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Каждый `FormValidationResult` представляет результат валидации одного значения:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // First error message
  String? message = result.getFirstErrorMessage();

  // All error messages
  List<String> messages = result.errorMessages();

  // Error responses
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## Использование FormValidator

`FormValidator` может использоваться автономно или с полями формы.

### Автономное использование

``` dart
// Using a named constructor
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### С данными в конструкторе

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Именованные конструкторы FormValidator

{{ config('app.name') }} v7 предоставляет именованные конструкторы для типовых валидаций:

``` dart
// Email validation
FormValidator.email(message: "Please enter a valid email")

// Password validation (strength 1 or 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// Phone numbers
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL validation
FormValidator.url()

// Length constraints
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// Value constraints
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// Size constraints (for lists/strings)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// Not empty
FormValidator.notEmpty(message: "This field is required")

// Contains values
FormValidator.contains(['option1', 'option2'])

// Starts/ends with
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Boolean checks
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// Numeric
FormValidator.numeric()

// Date validations
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// Text case
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Location formats
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Regex pattern
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// Custom validation
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## Цепочка правил валидации

Объединяйте несколько правил в цепочку, используя методы экземпляра. Каждый метод возвращает `FormValidator`, что позволяет наращивать правила:

``` dart
FormValidator validator = FormValidator()
    .notEmpty()
    .email()
    .maxLength(50);

FormValidationResult result = validator.check("user@example.com");

if (!result.isValid) {
  List<String> errors = result.errorMessages();
  print(errors);
}
```

Все именованные конструкторы имеют соответствующие цепочечные методы экземпляра:

``` dart
FormValidator()
    .notEmpty(message: "Required")
    .email(message: "Invalid email")
    .minLength(5, message: "Too short")
    .maxLength(100, message: "Too long")
    .beginsWith("user", message: "Must start with 'user'")
    .lowercase(message: "Must be lowercase")
```

<div id="custom-validation"></div>

## Пользовательская валидация

### Пользовательское правило (инлайн)

Используйте `.custom()` для добавления инлайн-логики валидации:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

Или объедините его в цепочку с другими правилами:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Валидация на равенство

Проверка соответствия значения другому:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Использование FormValidator с полями

`FormValidator` интегрируется с виджетами `Field` в формах. Передайте валидатор в параметр `validator`:

``` dart
class RegisterForm extends NyFormData {
  RegisterForm({String? name}) : super(name ?? "register");

  @override
  fields() => [
        Field.text(
          "Name",
          autofocus: true,
          validator: FormValidator.notEmpty(),
        ),
        Field.email("Email", validator: FormValidator.email()),
        Field.password(
          "Password",
          validator: FormValidator.password(strength: 1),
        ),
      ];
}
```

Вы также можете использовать цепочечные валидаторы с полями:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Username is required")
      .minLength(3, message: "At least 3 characters")
      .maxLength(20, message: "At most 20 characters"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
)
```

<div id="validation-rules"></div>

## Доступные правила валидации

Все доступные правила для `FormValidator`, как именованные конструкторы и цепочечные методы:

| Правило | Именованный конструктор | Цепочечный метод | Описание |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Валидация формата email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Уровень 1: 8+ символов, 1 заглавная, 1 цифра. Уровень 2: + 1 спецсимвол |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Не может быть пустым |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Минимальная длина строки |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Максимальная длина строки |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Минимальное числовое значение (также работает с длиной строки, списка, карты) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Максимальное числовое значение |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Минимальный размер для списков/строк |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Максимальный размер для списков/строк |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Значение должно содержать одно из указанных |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | Строка должна начинаться с префикса |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | Строка должна заканчиваться суффиксом |
| URL | `FormValidator.url()` | `.url()` | Валидация формата URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Должно быть числом |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Должно быть `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Должно быть `false` |
| Date | `FormValidator.date()` | `.date()` | Должна быть валидная дата |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Дата должна быть в прошлом |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Дата должна быть в будущем |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Возраст должен быть старше N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Возраст должен быть младше N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | Первая буква должна быть заглавной |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Все символы должны быть строчными |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Все символы должны быть заглавными |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Формат телефонного номера США |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Формат телефонного номера Великобритании |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Формат почтового индекса США |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Формат почтового индекса Великобритании |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Должно соответствовать регулярному выражению |
| Equals | --- | `.equals(otherValue)` | Должно быть равно другому значению |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Пользовательская функция валидации |

Все правила принимают необязательный параметр `message` для настройки сообщения об ошибке.

<div id="creating-custom-validation-rules"></div>

## Создание пользовательских правил валидации

Для создания переиспользуемого правила валидации расширьте класс `FormRule`:

``` dart
class FormRuleUsername extends FormRule {
  @override
  String? rule = "username";

  @override
  String? message = "The @{{attribute}} must be a valid username.";

  FormRuleUsername({String? message}) {
    if (message != null) {
      this.message = message;
    }
  }

  @override
  bool validate(data) {
    if (data is! String) return false;
    // Username: alphanumeric, underscores, 3-20 chars
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

Используйте `@{{attribute}}` как плейсхолдер в `message` --- он будет заменён на метку поля во время выполнения.

### Использование пользовательского FormRule

Добавьте пользовательское правило в `FormValidator` с помощью `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Или используйте метод `.custom()` для одноразовых правил без создания класса:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
