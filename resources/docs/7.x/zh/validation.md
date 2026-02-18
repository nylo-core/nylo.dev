# 验证

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 基础
  - [使用 check() 验证数据](#validating-data "使用 check() 验证数据")
  - [验证结果](#validation-results "验证结果")
- FormValidator
  - [使用 FormValidator](#using-form-validator "使用 FormValidator")
  - [FormValidator 命名构造函数](#form-validator-named-constructors "FormValidator 命名构造函数")
  - [链式验证规则](#chaining-validation-rules "链式验证规则")
  - [自定义验证](#custom-validation "自定义验证")
  - [将 FormValidator 与字段一起使用](#form-validator-with-fields "将 FormValidator 与字段一起使用")
- [可用验证规则](#validation-rules "可用验证规则")
- [创建自定义验证规则](#creating-custom-validation-rules "创建自定义验证规则")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 提供了围绕 `FormValidator` 类构建的验证系统。您可以在页面内使用 `check()` 方法验证数据，也可以直接使用 `FormValidator` 进行独立和字段级验证。

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

## 使用 check() 验证数据

在任何 `NyPage` 内，使用 `check()` 方法验证数据。它接受一个回调函数，该函数接收一个验证器列表。使用 `.that()` 添加数据并链式调用规则：

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

### check() 的工作原理

1. `check()` 创建一个空的 `List<FormValidator>`
2. 您的回调使用 `.that(data)` 向列表添加带有数据的新 `FormValidator`
3. 每个 `.that()` 返回一个可以链式调用规则的 `FormValidator`
4. 回调执行后，列表中的每个验证器都会被检查
5. 结果被收集到 `FormValidationResponseBag` 中

### 验证多个字段

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

可选的 `label` 参数设置在错误消息中使用的可读名称（例如"Email 必须是有效的电子邮件地址。"）。

<div id="validation-results"></div>

## 验证结果

`check()` 方法返回一个 `FormValidationResponseBag`（即 `List<FormValidationResult>`），您也可以直接检查它：

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

每个 `FormValidationResult` 代表验证单个值的结果：

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

## 使用 FormValidator

`FormValidator` 可以独立使用或与表单字段一起使用。

### 独立使用

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

### 在构造函数中传入数据

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator 命名构造函数

{{ config('app.name') }} v7 提供了常用验证的命名构造函数：

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

## 链式验证规则

使用实例方法流畅地链式调用多个规则。每个方法返回 `FormValidator`，允许您逐步构建规则：

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

所有命名构造函数都有对应的链式实例方法：

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

## 自定义验证

### 自定义规则（内联）

使用 `.custom()` 添加内联验证逻辑：

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

或与其他规则链式调用：

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### 等值验证

检查值是否与另一个值匹配：

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## 将 FormValidator 与字段一起使用

`FormValidator` 与表单中的 `Field` 组件集成。将验证器传递给 `validator` 参数：

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

您也可以在字段中使用链式验证器：

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

## 可用验证规则

`FormValidator` 的所有可用规则，包括命名构造函数和链式方法：

| 规则 | 命名构造函数 | 链式方法 | 描述 |
|------|-------------------|------------------|-------------|
| 电子邮件 | `FormValidator.email()` | `.email()` | 验证电子邮件格式 |
| 密码 | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | 强度 1：8+ 字符，1 个大写字母，1 个数字。强度 2：+ 1 个特殊字符 |
| 非空 | `FormValidator.notEmpty()` | `.notEmpty()` | 不能为空 |
| 最小长度 | `FormValidator.minLength(5)` | `.minLength(5)` | 最小字符串长度 |
| 最大长度 | `FormValidator.maxLength(100)` | `.maxLength(100)` | 最大字符串长度 |
| 最小值 | `FormValidator.minValue(18)` | `.minValue(18)` | 最小数值（也适用于字符串长度、列表长度、映射长度） |
| 最大值 | `FormValidator.maxValue(100)` | `.maxValue(100)` | 最大数值 |
| 最小尺寸 | `FormValidator.minSize(2)` | `.minSize(2)` | 列表/字符串的最小尺寸 |
| 最大尺寸 | `FormValidator.maxSize(5)` | `.maxSize(5)` | 列表/字符串的最大尺寸 |
| 包含 | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | 值必须包含给定值之一 |
| 以...开头 | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | 字符串必须以前缀开头 |
| 以...结尾 | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | 字符串必须以后缀结尾 |
| URL | `FormValidator.url()` | `.url()` | 验证 URL 格式 |
| 数字 | `FormValidator.numeric()` | `.numeric()` | 必须是数字 |
| 布尔真 | `FormValidator.booleanTrue()` | `.booleanTrue()` | 必须为 `true` |
| 布尔假 | `FormValidator.booleanFalse()` | `.booleanFalse()` | 必须为 `false` |
| 日期 | `FormValidator.date()` | `.date()` | 必须是有效日期 |
| 过去日期 | `FormValidator.dateInPast()` | `.dateInPast()` | 日期必须在过去 |
| 未来日期 | `FormValidator.dateInFuture()` | `.dateInFuture()` | 日期必须在未来 |
| 年龄大于 | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | 年龄必须大于 N |
| 年龄小于 | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | 年龄必须小于 N |
| 首字母大写 | `FormValidator.capitalized()` | `.capitalized()` | 首字母必须大写 |
| 全小写 | `FormValidator.lowercase()` | `.lowercase()` | 所有字符必须为小写 |
| 全大写 | `FormValidator.uppercase()` | `.uppercase()` | 所有字符必须为大写 |
| 美国电话 | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | 美国电话号码格式 |
| 英国电话 | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | 英国电话号码格式 |
| 美国邮编 | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | 美国邮编格式 |
| 英国邮编 | `FormValidator.postcodeUk()` | `.postcodeUk()` | 英国邮编格式 |
| 正则表达式 | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | 必须匹配正则模式 |
| 等值 | — | `.equals(otherValue)` | 必须等于另一个值 |
| 自定义 | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | 自定义验证函数 |

所有规则都接受可选的 `message` 参数来自定义错误消息。

<div id="creating-custom-validation-rules"></div>

## 创建自定义验证规则

要创建可重用的验证规则，请扩展 `FormRule` 类：

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

在 `message` 中使用 `@{{attribute}}` 作为占位符——它将在运行时被字段的标签替换。

### 使用自定义 FormRule

使用 `FormValidator.rule()` 将自定义规则添加到 `FormValidator`：

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

或使用 `.custom()` 方法进行一次性规则而无需创建类：

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
