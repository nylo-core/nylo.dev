# Validation

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về validation")
- Cơ bản
  - [Xác thực dữ liệu với check()](#validating-data "Xác thực dữ liệu với check")
  - [Kết quả Validation](#validation-results "Kết quả validation")
- FormValidator
  - [Sử dụng FormValidator](#using-form-validator "Sử dụng FormValidator")
  - [Constructor được đặt tên của FormValidator](#form-validator-named-constructors "Constructor được đặt tên của FormValidator")
  - [Chuỗi quy tắc Validation](#chaining-validation-rules "Chuỗi quy tắc validation")
  - [Validation tùy chỉnh](#custom-validation "Validation tùy chỉnh")
  - [Sử dụng FormValidator với Fields](#form-validator-with-fields "Sử dụng FormValidator với Fields")
- [Các quy tắc Validation có sẵn](#validation-rules "Quy tắc validation")
- [Tạo quy tắc Validation tùy chỉnh](#creating-custom-validation-rules "Tạo quy tắc validation tùy chỉnh")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp hệ thống validation được xây dựng xung quanh class `FormValidator`. Bạn có thể xác thực dữ liệu bên trong trang bằng phương thức `check()`, hoặc sử dụng `FormValidator` trực tiếp cho validation độc lập và cấp trường.

``` dart
// Xác thực dữ liệu trong NyPage/NyState bằng check()
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("Tất cả validation đã thông qua!");
});

// FormValidator với form fields
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## Xác thực dữ liệu với check()

Bên trong bất kỳ `NyPage` nào, sử dụng phương thức `check()` để xác thực dữ liệu. Nó nhận callback nhận danh sách validators. Sử dụng `.that()` để thêm dữ liệu và chuỗi quy tắc:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // Tất cả validation đã thông qua
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Validation thất bại
      print(bag.firstErrorMessage);
    });
  }
}
```

### Cách check() hoạt động

1. `check()` tạo một `List<FormValidator>` trống
2. Callback của bạn sử dụng `.that(data)` để thêm `FormValidator` mới với dữ liệu vào danh sách
3. Mỗi `.that()` trả về `FormValidator` bạn có thể chuỗi quy tắc
4. Sau callback, mọi validator trong danh sách được kiểm tra
5. Kết quả được thu thập vào `FormValidationResponseBag`

### Xác thực nhiều trường

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

Tham số `label` tùy chọn đặt tên dễ đọc được sử dụng trong thông báo lỗi (ví dụ: "The Email must be a valid email address.").

<div id="validation-results"></div>

## Kết quả Validation

Phương thức `check()` trả về `FormValidationResponseBag` (một `List<FormValidationResult>`), bạn cũng có thể kiểm tra trực tiếp:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("Tất cả hợp lệ!");
} else {
  // Lấy thông báo lỗi đầu tiên
  String? error = bag.firstErrorMessage;
  print(error);

  // Lấy tất cả kết quả thất bại
  List<FormValidationResult> errors = bag.validationErrors;

  // Lấy tất cả kết quả thành công
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Mỗi `FormValidationResult` đại diện cho kết quả xác thực một giá trị:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Hợp lệ!");
} else {
  // Thông báo lỗi đầu tiên
  String? message = result.getFirstErrorMessage();

  // Tất cả thông báo lỗi
  List<String> messages = result.errorMessages();

  // Các phản hồi lỗi
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## Sử dụng FormValidator

`FormValidator` có thể được sử dụng độc lập hoặc với form fields.

### Sử dụng độc lập

``` dart
// Sử dụng constructor được đặt tên
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email hợp lệ");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Lỗi: $errorMessage");
}
```

### Với dữ liệu trong Constructor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Constructor được đặt tên của FormValidator

{{ config('app.name') }} v7 cung cấp các constructor được đặt tên cho validation phổ biến:

``` dart
// Validation email
FormValidator.email(message: "Vui lòng nhập email hợp lệ")

// Validation mật khẩu (độ mạnh 1 hoặc 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Mật khẩu quá yếu")

// Số điện thoại
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// Validation URL
FormValidator.url()

// Ràng buộc độ dài
FormValidator.minLength(5, message: "Quá ngắn")
FormValidator.maxLength(100, message: "Quá dài")

// Ràng buộc giá trị
FormValidator.minValue(18, message: "Phải từ 18 trở lên")
FormValidator.maxValue(100)

// Ràng buộc kích thước (cho danh sách/chuỗi)
FormValidator.minSize(2, message: "Chọn ít nhất 2")
FormValidator.maxSize(5)

// Không rỗng
FormValidator.notEmpty(message: "Trường này là bắt buộc")

// Chứa giá trị
FormValidator.contains(['option1', 'option2'])

// Bắt đầu/kết thúc bằng
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Kiểm tra boolean
FormValidator.booleanTrue(message: "Phải chấp nhận điều khoản")
FormValidator.booleanFalse()

// Số
FormValidator.numeric()

// Validation ngày
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Phải từ 18 tuổi trở lên")
FormValidator.dateAgeIsYounger(65)

// Kiểu chữ
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Định dạng địa chỉ
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Mẫu regex
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Định dạng không hợp lệ")

// Validation tùy chỉnh
FormValidator.custom(
  message: "Giá trị không hợp lệ",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## Chuỗi quy tắc Validation

Chuỗi nhiều quy tắc một cách mượt mà bằng phương thức instance. Mỗi phương thức trả về `FormValidator`, cho phép bạn xây dựng quy tắc:

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

Tất cả constructor được đặt tên đều có phương thức instance có thể chuỗi tương ứng:

``` dart
FormValidator()
    .notEmpty(message: "Bắt buộc")
    .email(message: "Email không hợp lệ")
    .minLength(5, message: "Quá ngắn")
    .maxLength(100, message: "Quá dài")
    .beginsWith("user", message: "Phải bắt đầu bằng 'user'")
    .lowercase(message: "Phải là chữ thường")
```

<div id="custom-validation"></div>

## Validation tùy chỉnh

### Quy tắc tùy chỉnh (Inline)

Sử dụng `.custom()` để thêm logic validation inline:

``` dart
FormValidator.custom(
  message: "Tên người dùng đã được sử dụng",
  validate: (data) {
    // Trả về true nếu hợp lệ, false nếu không hợp lệ
    return !_takenUsernames.contains(data);
  },
)
```

Hoặc chuỗi với các quy tắc khác:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Phải bắt đầu bằng chữ cái",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Validation bằng nhau

Kiểm tra xem giá trị có khớp với giá trị khác:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Mật khẩu phải khớp",
    )
```

<div id="form-validator-with-fields"></div>

## Sử dụng FormValidator với Fields

`FormValidator` tích hợp với widget `Field` trong form. Truyền validator đến tham số `validator`:

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

Bạn cũng có thể sử dụng validator chuỗi với fields:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Tên người dùng là bắt buộc")
      .minLength(3, message: "Ít nhất 3 ký tự")
      .maxLength(20, message: "Tối đa 20 ký tự"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Đánh giá phải ít nhất 4"),
)
```

<div id="validation-rules"></div>

## Các quy tắc Validation có sẵn

Tất cả quy tắc có sẵn cho `FormValidator`, dưới dạng constructor được đặt tên và phương thức có thể chuỗi:

| Quy tắc | Constructor được đặt tên | Phương thức có thể chuỗi | Mô tả |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Xác thực định dạng email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Độ mạnh 1: 8+ ký tự, 1 chữ hoa, 1 số. Độ mạnh 2: + 1 ký tự đặc biệt |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Không được rỗng |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Độ dài chuỗi tối thiểu |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Độ dài chuỗi tối đa |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Giá trị số tối thiểu (cũng hoạt động trên độ dài chuỗi, danh sách, map) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Giá trị số tối đa |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Kích thước tối thiểu cho danh sách/chuỗi |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Kích thước tối đa cho danh sách/chuỗi |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Giá trị phải chứa một trong các giá trị cho |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | Chuỗi phải bắt đầu bằng tiền tố |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | Chuỗi phải kết thúc bằng hậu tố |
| URL | `FormValidator.url()` | `.url()` | Xác thực định dạng URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Phải là số |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Phải là `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Phải là `false` |
| Date | `FormValidator.date()` | `.date()` | Phải là ngày hợp lệ |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Ngày phải trong quá khứ |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Ngày phải trong tương lai |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Tuổi phải lớn hơn N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Tuổi phải nhỏ hơn N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | Chữ cái đầu phải viết hoa |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Tất cả ký tự phải viết thường |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Tất cả ký tự phải viết hoa |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Định dạng số điện thoại US |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Định dạng số điện thoại UK |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Định dạng zipcode US |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Định dạng postcode UK |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Phải khớp mẫu regex |
| Equals | -- | `.equals(otherValue)` | Phải bằng giá trị khác |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Hàm validation tùy chỉnh |

Tất cả quy tắc chấp nhận tham số `message` tùy chọn để tùy chỉnh thông báo lỗi.

<div id="creating-custom-validation-rules"></div>

## Tạo quy tắc Validation tùy chỉnh

Để tạo quy tắc validation có thể tái sử dụng, kế thừa class `FormRule`:

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
    // Username: chữ-số, gạch dưới, 3-20 ký tự
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

Sử dụng `@{{attribute}}` làm placeholder trong `message` -- nó sẽ được thay thế bằng nhãn của trường khi chạy.

### Sử dụng FormRule tùy chỉnh

Thêm quy tắc tùy chỉnh vào `FormValidator` bằng `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Hoặc sử dụng phương thức `.custom()` cho quy tắc một lần mà không cần tạo class:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Tên người dùng phải bắt đầu bằng chữ cái",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
