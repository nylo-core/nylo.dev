# 유효성 검사

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 기본
  - [check()로 데이터 유효성 검사](#validating-data "check()로 데이터 유효성 검사")
  - [유효성 검사 결과](#validation-results "유효성 검사 결과")
- FormValidator
  - [FormValidator 사용](#using-form-validator "FormValidator 사용")
  - [FormValidator Named Constructor](#form-validator-named-constructors "FormValidator Named Constructor")
  - [유효성 검사 규칙 체이닝](#chaining-validation-rules "유효성 검사 규칙 체이닝")
  - [커스텀 유효성 검사](#custom-validation "커스텀 유효성 검사")
  - [Field에서 FormValidator 사용](#form-validator-with-fields "Field에서 FormValidator 사용")
- [사용 가능한 유효성 검사 규칙](#validation-rules "사용 가능한 유효성 검사 규칙")
- [커스텀 유효성 검사 규칙 만들기](#creating-custom-validation-rules "커스텀 유효성 검사 규칙 만들기")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 `FormValidator` 클래스를 중심으로 구축된 유효성 검사 시스템을 제공합니다. `check()` 메서드를 사용하여 페이지 내에서 데이터를 유효성 검사하거나, 독립형 및 필드 레벨 유효성 검사를 위해 `FormValidator`를 직접 사용할 수 있습니다.

``` dart
// NyPage/NyState에서 check()를 사용한 데이터 유효성 검사
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// Form 필드에서 FormValidator 사용
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## check()로 데이터 유효성 검사

모든 `NyPage` 내에서 `check()` 메서드를 사용하여 데이터를 유효성 검사합니다. Validator 목록을 받는 콜백을 받습니다. `.that()`을 사용하여 데이터를 추가하고 규칙을 체이닝합니다:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // 모든 유효성 검사 통과
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // 유효성 검사 실패
      print(bag.firstErrorMessage);
    });
  }
}
```

### check() 작동 방식

1. `check()`는 빈 `List<FormValidator>`를 생성합니다
2. 콜백에서 `.that(data)`를 사용하여 데이터가 포함된 새 `FormValidator`를 목록에 추가합니다
3. 각 `.that()`는 규칙을 체이닝할 수 있는 `FormValidator`를 반환합니다
4. 콜백 후 목록의 모든 Validator가 검사됩니다
5. 결과는 `FormValidationResponseBag`에 수집됩니다

### 여러 필드 유효성 검사

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

선택적 `label` 매개변수는 오류 메시지에 사용되는 사람이 읽을 수 있는 이름을 설정합니다 (예: "The Email must be a valid email address.").

<div id="validation-results"></div>

## 유효성 검사 결과

`check()` 메서드는 `FormValidationResponseBag` (`List<FormValidationResult>`)를 반환하며, 직접 검사할 수도 있습니다:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // 첫 번째 오류 메시지 가져오기
  String? error = bag.firstErrorMessage;
  print(error);

  // 모든 실패 결과 가져오기
  List<FormValidationResult> errors = bag.validationErrors;

  // 모든 성공 결과 가져오기
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

각 `FormValidationResult`는 단일 값의 유효성 검사 결과를 나타냅니다:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // 첫 번째 오류 메시지
  String? message = result.getFirstErrorMessage();

  // 모든 오류 메시지
  List<String> messages = result.errorMessages();

  // 오류 응답
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## FormValidator 사용

`FormValidator`는 독립적으로 또는 Form 필드와 함께 사용할 수 있습니다.

### 독립 사용

``` dart
// Named Constructor 사용
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### 생성자에서 데이터 전달

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator Named Constructor

{{ config('app.name') }} v7은 일반적인 유효성 검사를 위한 Named Constructor를 제공합니다:

``` dart
// 이메일 유효성 검사
FormValidator.email(message: "Please enter a valid email")

// 비밀번호 유효성 검사 (강도 1 또는 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// 전화번호
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL 유효성 검사
FormValidator.url()

// 길이 제약
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// 값 제약
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// 크기 제약 (리스트/문자열용)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// 비어있지 않음
FormValidator.notEmpty(message: "This field is required")

// 값 포함
FormValidator.contains(['option1', 'option2'])

// 시작/끝 문자
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// 부울 검사
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// 숫자
FormValidator.numeric()

// 날짜 유효성 검사
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// 텍스트 대소문자
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// 위치 형식
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// 정규식 패턴
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// 커스텀 유효성 검사
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## 유효성 검사 규칙 체이닝

인스턴스 메서드를 사용하여 여러 규칙을 유연하게 체이닝합니다. 각 메서드는 `FormValidator`를 반환하므로 규칙을 계속 추가할 수 있습니다:

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

모든 Named Constructor에는 대응하는 체이닝 가능한 인스턴스 메서드가 있습니다:

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

## 커스텀 유효성 검사

### 커스텀 규칙 (인라인)

`.custom()`을 사용하여 인라인 유효성 검사 로직을 추가합니다:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // 유효하면 true, 유효하지 않으면 false 반환
    return !_takenUsernames.contains(data);
  },
)
```

또는 다른 규칙과 체이닝합니다:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Equals 유효성 검사

값이 다른 값과 일치하는지 확인합니다:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Field에서 FormValidator 사용

`FormValidator`는 Form의 `Field` Widget과 통합됩니다. `validator` 매개변수에 Validator를 전달합니다:

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

필드에 체이닝된 Validator를 사용할 수도 있습니다:

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

## 사용 가능한 유효성 검사 규칙

Named Constructor와 체이닝 가능한 메서드로 제공되는 `FormValidator`의 모든 사용 가능한 규칙:

| 규칙 | Named Constructor | 체이닝 가능 메서드 | 설명 |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | 이메일 형식 유효성 검사 |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | 강도 1: 8자 이상, 대문자 1개, 숫자 1개. 강도 2: + 특수문자 1개 |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | 비어있을 수 없음 |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | 최소 문자열 길이 |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | 최대 문자열 길이 |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | 최소 숫자 값 (문자열 길이, 리스트 길이, 맵 길이에서도 작동) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | 최대 숫자 값 |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | 리스트/문자열의 최소 크기 |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | 리스트/문자열의 최대 크기 |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | 주어진 값 중 하나를 포함해야 함 |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | 문자열이 접두사로 시작해야 함 |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | 문자열이 접미사로 끝나야 함 |
| URL | `FormValidator.url()` | `.url()` | URL 형식 유효성 검사 |
| Numeric | `FormValidator.numeric()` | `.numeric()` | 숫자여야 함 |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | `true`여야 함 |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | `false`여야 함 |
| Date | `FormValidator.date()` | `.date()` | 유효한 날짜여야 함 |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | 날짜가 과거여야 함 |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | 날짜가 미래여야 함 |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | 나이가 N보다 많아야 함 |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | 나이가 N보다 적어야 함 |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | 첫 글자가 대문자여야 함 |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | 모든 문자가 소문자여야 함 |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | 모든 문자가 대문자여야 함 |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | 미국 전화번호 형식 |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | 영국 전화번호 형식 |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | 미국 우편번호 형식 |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | 영국 우편번호 형식 |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | 정규식 패턴과 일치해야 함 |
| Equals | -- | `.equals(otherValue)` | 다른 값과 동일해야 함 |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | 커스텀 유효성 검사 함수 |

모든 규칙은 오류 메시지를 커스터마이즈하기 위한 선택적 `message` 매개변수를 받습니다.

<div id="creating-custom-validation-rules"></div>

## 커스텀 유효성 검사 규칙 만들기

재사용 가능한 유효성 검사 규칙을 만들려면 `FormRule` 클래스를 확장합니다:

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
    // 사용자 이름: 영숫자, 밑줄, 3-20자
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

`message`에서 `@{{attribute}}`를 플레이스홀더로 사용하세요 -- 런타임에 필드의 라벨로 대체됩니다.

### 커스텀 FormRule 사용

`FormValidator.rule()`을 사용하여 `FormValidator`에 커스텀 규칙을 추가합니다:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

또는 클래스를 만들지 않고 일회용 규칙에 `.custom()` 메서드를 사용합니다:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
