# Validation

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับการตรวจสอบความถูกต้อง")
- พื้นฐาน
  - [การตรวจสอบข้อมูลด้วย check()](#validating-data "การตรวจสอบข้อมูลด้วย check")
  - [ผลการตรวจสอบ](#validation-results "ผลการตรวจสอบ")
- FormValidator
  - [การใช้งาน FormValidator](#using-form-validator "การใช้งาน FormValidator")
  - [FormValidator Named Constructor](#form-validator-named-constructors "FormValidator Named Constructor")
  - [การต่อเชื่อมกฎการตรวจสอบ](#chaining-validation-rules "การต่อเชื่อมกฎการตรวจสอบ")
  - [การตรวจสอบแบบกำหนดเอง](#custom-validation "การตรวจสอบแบบกำหนดเอง")
  - [การใช้ FormValidator กับ Field](#form-validator-with-fields "การใช้ FormValidator กับ Field")
- [กฎการตรวจสอบที่ใช้ได้](#validation-rules "กฎการตรวจสอบ")
- [การสร้างกฎการตรวจสอบแบบกำหนดเอง](#creating-custom-validation-rules "การสร้างกฎการตรวจสอบแบบกำหนดเอง")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีระบบตรวจสอบความถูกต้องที่สร้างรอบคลาส `FormValidator` คุณสามารถตรวจสอบข้อมูลภายในหน้าเพจโดยใช้เมธอด `check()` หรือใช้ `FormValidator` โดยตรงสำหรับการตรวจสอบแบบ standalone และระดับฟิลด์

``` dart
// ตรวจสอบข้อมูลใน NyPage/NyState โดยใช้ check()
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// FormValidator กับ form field
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## การตรวจสอบข้อมูลด้วย check()

ภายใน `NyPage` ใดก็ได้ ใช้เมธอด `check()` เพื่อตรวจสอบข้อมูล เมธอดนี้รับ callback ที่รับ list ของ validator ใช้ `.that()` เพื่อเพิ่มข้อมูลและต่อเชื่อมกฎ:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // การตรวจสอบทั้งหมดผ่าน
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // การตรวจสอบล้มเหลว
      print(bag.firstErrorMessage);
    });
  }
}
```

### วิธีการทำงานของ check()

1. `check()` สร้าง `List<FormValidator>` ว่าง
2. callback ของคุณใช้ `.that(data)` เพื่อเพิ่ม `FormValidator` ใหม่พร้อมข้อมูลไปยัง list
3. แต่ละ `.that()` ส่งกลับ `FormValidator` ที่คุณสามารถต่อเชื่อมกฎได้
4. หลัง callback ทุก validator ใน list จะถูกตรวจสอบ
5. ผลลัพธ์ถูกรวบรวมเป็น `FormValidationResponseBag`

### การตรวจสอบหลายฟิลด์

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

พารามิเตอร์ `label` ที่เป็นตัวเลือกจะตั้งชื่อที่อ่านง่ายสำหรับใช้ในข้อความผิดพลาด (เช่น "The Email must be a valid email address.")

<div id="validation-results"></div>

## ผลการตรวจสอบ

เมธอด `check()` ส่งกลับ `FormValidationResponseBag` (`List<FormValidationResult>`) ซึ่งคุณสามารถตรวจสอบโดยตรงได้:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // ดึงข้อความผิดพลาดแรก
  String? error = bag.firstErrorMessage;
  print(error);

  // ดึงผลลัพธ์ที่ล้มเหลวทั้งหมด
  List<FormValidationResult> errors = bag.validationErrors;

  // ดึงผลลัพธ์ที่สำเร็จทั้งหมด
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

แต่ละ `FormValidationResult` แทนผลลัพธ์ของการตรวจสอบค่าเดียว:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // ข้อความผิดพลาดแรก
  String? message = result.getFirstErrorMessage();

  // ข้อความผิดพลาดทั้งหมด
  List<String> messages = result.errorMessages();

  // response ผิดพลาด
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## การใช้งาน FormValidator

`FormValidator` สามารถใช้แบบ standalone หรือกับ form field ได้

### การใช้งานแบบ Standalone

``` dart
// ใช้ named constructor
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### พร้อมข้อมูลใน Constructor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator Named Constructor

{{ config('app.name') }} v7 มี named constructor สำหรับการตรวจสอบทั่วไป:

``` dart
// ตรวจสอบอีเมล
FormValidator.email(message: "Please enter a valid email")

// ตรวจสอบรหัสผ่าน (ความแข็งแกร่ง 1 หรือ 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// หมายเลขโทรศัพท์
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// ตรวจสอบ URL
FormValidator.url()

// ข้อจำกัดความยาว
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// ข้อจำกัดค่า
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// ข้อจำกัดขนาด (สำหรับ list/string)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// ไม่ว่าง
FormValidator.notEmpty(message: "This field is required")

// มีค่า
FormValidator.contains(['option1', 'option2'])

// ขึ้นต้น/ลงท้ายด้วย
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// ตรวจสอบ boolean
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// ตัวเลข
FormValidator.numeric()

// ตรวจสอบวันที่
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// รูปแบบตัวอักษร
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// รูปแบบสถานที่
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// รูปแบบ regex
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// การตรวจสอบแบบกำหนดเอง
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## การต่อเชื่อมกฎการตรวจสอบ

ต่อเชื่อมหลายกฎอย่างลื่นไหลโดยใช้ instance method แต่ละเมธอดส่งกลับ `FormValidator` ช่วยให้คุณสร้างกฎขึ้นได้:

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

named constructor ทั้งหมดมี instance method ที่ต่อเชื่อมได้ตรงกัน:

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

## การตรวจสอบแบบกำหนดเอง

### กฎกำหนดเอง (Inline)

ใช้ `.custom()` เพื่อเพิ่มลอจิกการตรวจสอบแบบ inline:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // ส่งกลับ true หากถูกต้อง, false หากไม่ถูกต้อง
    return !_takenUsernames.contains(data);
  },
)
```

หรือต่อเชื่อมกับกฎอื่น:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### การตรวจสอบค่าเท่ากัน

ตรวจสอบว่าค่าตรงกับค่าอื่น:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## การใช้ FormValidator กับ Field

`FormValidator` รวมเข้ากับ `Field` widget ในฟอร์ม ส่ง validator ไปยังพารามิเตอร์ `validator`:

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

คุณยังสามารถใช้ validator ที่ต่อเชื่อมกับ field ได้:

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

## กฎการตรวจสอบที่ใช้ได้

กฎทั้งหมดที่ใช้ได้สำหรับ `FormValidator` ทั้งแบบ named constructor และ chainable method:

| กฎ | Named Constructor | Chainable Method | คำอธิบาย |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | ตรวจสอบรูปแบบอีเมล |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | ความแข็งแกร่ง 1: 8+ ตัวอักษร, 1 ตัวพิมพ์ใหญ่, 1 ตัวเลข ความแข็งแกร่ง 2: + 1 อักขระพิเศษ |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | ต้องไม่ว่าง |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | ความยาว string ขั้นต่ำ |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | ความยาว string สูงสุด |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | ค่าตัวเลขขั้นต่ำ (ใช้ได้กับความยาว string, list, map ด้วย) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | ค่าตัวเลขสูงสุด |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | ขนาดขั้นต่ำสำหรับ list/string |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | ขนาดสูงสุดสำหรับ list/string |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | ค่าต้องมีค่าใดค่าหนึ่งที่กำหนด |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | string ต้องขึ้นต้นด้วย prefix |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | string ต้องลงท้ายด้วย suffix |
| URL | `FormValidator.url()` | `.url()` | ตรวจสอบรูปแบบ URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | ต้องเป็นตัวเลข |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | ต้องเป็น `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | ต้องเป็น `false` |
| Date | `FormValidator.date()` | `.date()` | ต้องเป็นวันที่ที่ถูกต้อง |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | วันที่ต้องอยู่ในอดีต |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | วันที่ต้องอยู่ในอนาคต |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | อายุต้องมากกว่า N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | อายุต้องน้อยกว่า N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | ตัวอักษรแรกต้องเป็นตัวพิมพ์ใหญ่ |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | ตัวอักษรทั้งหมดต้องเป็นตัวพิมพ์เล็ก |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | ตัวอักษรทั้งหมดต้องเป็นตัวพิมพ์ใหญ่ |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | รูปแบบหมายเลขโทรศัพท์สหรัฐ |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | รูปแบบหมายเลขโทรศัพท์อังกฤษ |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | รูปแบบ zipcode สหรัฐ |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | รูปแบบ postcode อังกฤษ |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | ต้องตรงกับรูปแบบ regex |
| Equals | -- | `.equals(otherValue)` | ต้องเท่ากับค่าอื่น |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | ฟังก์ชันตรวจสอบแบบกำหนดเอง |

กฎทั้งหมดรับพารามิเตอร์ `message` ที่เป็นตัวเลือกเพื่อปรับแต่งข้อความผิดพลาด

<div id="creating-custom-validation-rules"></div>

## การสร้างกฎการตรวจสอบแบบกำหนดเอง

ในการสร้างกฎการตรวจสอบที่นำกลับมาใช้ได้ ให้ extend คลาส `FormRule`:

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
    // Username: ตัวอักษรและตัวเลข, ขีดล่าง, 3-20 ตัวอักษร
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

ใช้ `@{{attribute}}` เป็น placeholder ใน `message` -- จะถูกแทนที่ด้วย label ของฟิลด์ในขณะรันไทม์

### การใช้ FormRule กำหนดเอง

เพิ่มกฎกำหนดเองของคุณไปยัง `FormValidator` โดยใช้ `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

หรือใช้เมธอด `.custom()` สำหรับกฎที่ใช้ครั้งเดียวโดยไม่ต้องสร้างคลาส:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
