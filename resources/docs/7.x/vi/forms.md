# Forms

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về forms")
- Bắt đầu
  - [Tạo một Form](#creating-forms "Tạo forms")
  - [Hiển thị một Form](#displaying-a-form "Hiển thị form")
  - [Gửi một Form](#submitting-a-form "Gửi form")
- Các loại trường
  - [Trường văn bản](#text-fields "Trường văn bản")
  - [Trường số](#number-fields "Trường số")
  - [Trường mật khẩu](#password-fields "Trường mật khẩu")
  - [Trường email](#email-fields "Trường email")
  - [Trường URL](#url-fields "Trường URL")
  - [Trường vùng văn bản](#text-area-fields "Trường vùng văn bản")
  - [Trường số điện thoại](#phone-number-fields "Trường số điện thoại")
  - [Viết hoa từ](#capitalize-words-fields "Trường viết hoa từ")
  - [Viết hoa câu](#capitalize-sentences-fields "Trường viết hoa câu")
  - [Trường ngày](#date-fields "Trường ngày")
  - [Trường ngày giờ](#datetime-fields "Trường ngày giờ")
  - [Trường nhập có mặt nạ](#masked-input-fields "Trường nhập có mặt nạ")
  - [Trường tiền tệ](#currency-fields "Trường tiền tệ")
  - [Trường checkbox](#checkbox-fields "Trường checkbox")
  - [Trường switch box](#switch-box-fields "Trường switch box")
  - [Trường picker](#picker-fields "Trường picker")
  - [Trường radio](#radio-fields "Trường radio")
  - [Trường chip](#chip-fields "Trường chip")
  - [Trường slider](#slider-fields "Trường slider")
  - [Trường range slider](#range-slider-fields "Trường range slider")
  - [Trường tùy chỉnh](#custom-fields "Trường tùy chỉnh")
  - [Trường widget](#widget-fields "Trường widget")
- [FormCollection](#form-collection "FormCollection")
- [Xác thực Form](#form-validation "Xác thực Form")
- [Quản lý dữ liệu Form](#managing-form-data "Quản lý dữ liệu Form")
  - [Dữ liệu ban đầu](#initial-data "Dữ liệu ban đầu")
  - [Đặt giá trị trường](#setting-field-values "Đặt giá trị trường")
  - [Đặt tùy chọn trường](#setting-field-options "Đặt tùy chọn trường")
  - [Đọc dữ liệu Form](#reading-form-data "Đọc dữ liệu Form")
  - [Xóa dữ liệu](#clearing-data "Xóa dữ liệu")
  - [Cập nhật trường](#finding-and-updating-fields "Cập nhật trường")
- [Nút gửi](#submit-button "Nút gửi")
- [Bố cục Form](#form-layout "Bố cục Form")
- [Hiển thị trường](#field-visibility "Hiển thị trường")
- [Tạo kiểu trường](#field-styling "Tạo kiểu trường")
- [Phương thức tĩnh NyFormWidget](#ny-form-widget-static-methods "Phương thức tĩnh NyFormWidget")
- [Tham chiếu constructor NyFormWidget](#ny-form-widget-constructor-reference "Tham chiếu constructor NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Tham chiếu tất cả loại trường](#all-field-types-reference "Tham chiếu tất cả loại trường")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp hệ thống form được xây dựng xung quanh `NyFormWidget`. Lớp form của bạn kế thừa `NyFormWidget` và **chính là** widget - không cần wrapper riêng. Forms hỗ trợ xác thực tích hợp, nhiều loại trường, tạo kiểu và quản lý dữ liệu.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Tạo một Form

Sử dụng Metro CLI để tạo form mới:

``` bash
metro make:form LoginForm
```

Lệnh này tạo `lib/app/forms/login_form.dart`:

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

Forms kế thừa `NyFormWidget` và ghi đè phương thức `fields()` để định nghĩa các trường form. Mỗi trường sử dụng một constructor được đặt tên như `Field.text()`, `Field.email()`, hoặc `Field.password()`. Getter `static NyFormActions get actions` cung cấp cách thuận tiện để tương tác với form từ bất kỳ đâu trong ứng dụng.


<div id="displaying-a-form"></div>

## Hiển thị một Form

Vì lớp form của bạn kế thừa `NyFormWidget`, nó **chính là** widget. Sử dụng trực tiếp trong cây widget:

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

## Gửi một Form

Có ba cách để gửi form:

### Sử dụng onSubmit và submitButton

Truyền `onSubmit` và `submitButton` khi tạo form. {{ config('app.name') }} cung cấp các nút dựng sẵn hoạt động như nút gửi:

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

Các kiểu nút có sẵn: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Sử dụng NyFormActions

Sử dụng getter `actions` để gửi từ bất kỳ đâu:

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

### Sử dụng phương thức tĩnh NyFormWidget.submit()

Gửi form theo tên từ bất kỳ đâu:

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

Khi gửi, form xác thực tất cả các trường. Nếu hợp lệ, `onSuccess` được gọi với `Map<String, dynamic>` dữ liệu trường (khóa là phiên bản snake_case của tên trường). Nếu không hợp lệ, thông báo lỗi toast sẽ hiển thị mặc định và `onFailure` được gọi nếu được cung cấp.


<div id="field-types"></div>

## Các loại trường

{{ config('app.name') }} v7 cung cấp 22 loại trường qua các constructor được đặt tên trên lớp `Field`. Tất cả constructor trường đều chia sẻ các tham số chung sau:

| Tham số | Kiểu | Mặc định | Mô tả |
|---------|------|----------|-------|
| `key` | `String` | Bắt buộc | Định danh trường (positional) |
| `label` | `String?` | `null` | Nhãn hiển thị tùy chỉnh (mặc định là key ở dạng title case) |
| `value` | `dynamic` | `null` | Giá trị ban đầu |
| `validator` | `FormValidator?` | `null` | Quy tắc xác thực |
| `autofocus` | `bool` | `false` | Tự động focus khi tải |
| `dummyData` | `String?` | `null` | Dữ liệu thử nghiệm/phát triển |
| `header` | `Widget?` | `null` | Widget hiển thị phía trên trường |
| `footer` | `Widget?` | `null` | Widget hiển thị phía dưới trường |
| `titleStyle` | `TextStyle?` | `null` | Kiểu văn bản nhãn tùy chỉnh |
| `hidden` | `bool` | `false` | Ẩn trường |
| `readOnly` | `bool?` | `null` | Đặt trường chỉ đọc |
| `style` | `FieldStyle?` | Thay đổi | Cấu hình kiểu riêng cho trường |
| `onChanged` | `Function(dynamic)?` | `null` | Callback thay đổi giá trị |

<div id="text-fields"></div>

### Trường văn bản

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Loại kiểu: `FieldStyleTextField`

<div id="number-fields"></div>

### Trường số

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

Tham số `decimal` kiểm soát việc cho phép nhập số thập phân hay không. Loại kiểu: `FieldStyleTextField`

<div id="password-fields"></div>

### Trường mật khẩu

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

Tham số `viewable` thêm nút bật/tắt hiển thị. Loại kiểu: `FieldStyleTextField`

<div id="email-fields"></div>

### Trường email

``` dart
Field.email("Email", validator: FormValidator.email())
```

Tự động đặt loại bàn phím email và lọc khoảng trắng. Loại kiểu: `FieldStyleTextField`

<div id="url-fields"></div>

### Trường URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Đặt loại bàn phím URL. Loại kiểu: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Trường vùng văn bản

``` dart
Field.textArea("Description")
```

Nhập văn bản nhiều dòng. Loại kiểu: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Trường số điện thoại

``` dart
Field.phoneNumber("Mobile Phone")
```

Tự động định dạng đầu vào số điện thoại. Loại kiểu: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Viết hoa từ

``` dart
Field.capitalizeWords("Full Name")
```

Viết hoa chữ cái đầu tiên của mỗi từ. Loại kiểu: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Viết hoa câu

``` dart
Field.capitalizeSentences("Bio")
```

Viết hoa chữ cái đầu tiên của mỗi câu. Loại kiểu: `FieldStyleTextField`

<div id="date-fields"></div>

### Trường ngày

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Disable the clear button
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Custom clear icon
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Mở trình chọn ngày. Mặc định, trường hiển thị nút xóa cho phép người dùng đặt lại giá trị. Đặt `canClear: false` để ẩn nó, hoặc sử dụng `clearIconData` để thay đổi biểu tượng. Loại kiểu: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Trường ngày giờ

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Mở trình chọn ngày và giờ. Bạn có thể đặt `firstDate`, `lastDate`, `dateFormat`, và `initialPickerDateTime` trực tiếp như tham số cấp cao nhất. Loại kiểu: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Trường nhập có mặt nạ

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

Ký tự `#` trong mặt nạ được thay thế bằng đầu vào của người dùng. Sử dụng `match` để kiểm soát các ký tự được phép. Khi `maskReturnValue` là `true`, giá trị trả về bao gồm định dạng mặt nạ.

<div id="currency-fields"></div>

### Trường tiền tệ

``` dart
Field.currency("Price", currency: "usd")
```

Tham số `currency` là bắt buộc và xác định định dạng tiền tệ. Loại kiểu: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Trường checkbox

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Loại kiểu: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Trường switch box

``` dart
Field.switchBox("Enable Notifications")
```

Loại kiểu: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Trường picker

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

Tham số `options` yêu cầu `FormCollection` (không phải list thô). Xem [FormCollection](#form-collection) để biết chi tiết. Loại kiểu: `FieldStylePicker`

#### Kiểu List Tile

Bạn có thể tùy chỉnh cách các mục hiển thị trong bottom sheet của picker bằng `PickerListTileStyle`. Mặc định, bottom sheet hiển thị các tile văn bản thuần. Sử dụng các preset tích hợp để thêm indicator chọn, hoặc cung cấp builder hoàn toàn tùy chỉnh.

**Kiểu radio** - hiển thị biểu tượng nút radio làm widget dẫn đầu:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// With a custom active color
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Kiểu checkmark** - hiển thị biểu tượng dấu kiểm làm widget theo sau khi được chọn:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Builder tùy chỉnh** - kiểm soát hoàn toàn widget của mỗi tile:

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

Cả hai kiểu preset cũng hỗ trợ `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor`, và `selectedTileColor`:

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

### Trường radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Tham số `options` yêu cầu `FormCollection`. Loại kiểu: `FieldStyleRadio`

<div id="chip-fields"></div>

### Trường chip

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Cho phép chọn nhiều qua widget chip. Tham số `options` yêu cầu `FormCollection`. Loại kiểu: `FieldStyleChip`

<div id="slider-fields"></div>

### Trường slider

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

Loại kiểu: `FieldStyleSlider` - cấu hình `min`, `max`, `divisions`, màu sắc, hiển thị giá trị, và nhiều hơn.

<div id="range-slider-fields"></div>

### Trường range slider

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

Trả về đối tượng `RangeValues`. Loại kiểu: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Trường tùy chỉnh

Sử dụng `Field.custom()` để cung cấp widget stateful của riêng bạn:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Tham số `child` yêu cầu widget kế thừa `NyFieldStatefulWidget`. Điều này cho bạn toàn quyền kiểm soát việc hiển thị và hành vi của trường.

<div id="widget-fields"></div>

### Trường widget

Sử dụng `Field.widget()` để nhúng bất kỳ widget nào vào form mà không phải trường form:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Trường widget không tham gia xác thực hoặc thu thập dữ liệu. Chúng chỉ dùng cho bố cục.


<div id="form-collection"></div>

## FormCollection

Các trường picker, radio, và chip yêu cầu `FormCollection` cho tùy chọn của chúng. `FormCollection` cung cấp giao diện thống nhất để xử lý các định dạng tùy chọn khác nhau.

### Tạo FormCollection

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` tự động phát hiện định dạng dữ liệu và ủy quyền cho constructor phù hợp.

### FormOption

Mỗi tùy chọn trong `FormCollection` là `FormOption` với thuộc tính `value` và `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Truy vấn tùy chọn

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

## Xác thực Form

Thêm xác thực cho bất kỳ trường nào bằng tham số `validator` với `FormValidator`:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
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

Khi form được gửi, tất cả validator được kiểm tra. Nếu có lỗi, thông báo lỗi toast hiển thị lỗi đầu tiên và callback `onFailure` được gọi.

**Xem thêm:** [Xác thực](/docs/7.x/validation#validation-rules) để xem danh sách đầy đủ các validator có sẵn.


<div id="managing-form-data"></div>

## Quản lý dữ liệu Form

<div id="initial-data"></div>

### Dữ liệu ban đầu

Có hai cách để đặt dữ liệu ban đầu cho form.

**Cách 1: Ghi đè getter `init` trong lớp form**

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

Getter `init` có thể trả về `Map` đồng bộ hoặc `Future<Map>` bất đồng bộ. Khóa được khớp với tên trường bằng chuẩn hóa snake_case, nên `"First Name"` ánh xạ đến trường có key `"First Name"`.

#### Sử dụng `define()` trong init

Sử dụng helper `define()` khi bạn cần đặt **tùy chọn** (hoặc cả giá trị và tùy chọn) cho trường trong `init`. Điều này hữu ích cho trường picker, chip, và radio khi tùy chọn đến từ API hoặc nguồn bất đồng bộ khác.

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

`define()` chấp nhận hai tham số được đặt tên:

| Tham số | Mô tả |
|---------|-------|
| `value` | Giá trị ban đầu cho trường |
| `options` | Tùy chọn cho trường picker, chip, hoặc radio |

``` dart
// Set only options (no initial value)
"Category": define(options: categories),

// Set only an initial value
"Price": define(value: "100"),

// Set both a value and options
"Country": define(value: "us", options: countries),

// Plain values still work for simple fields
"Name": "John",
```

Tùy chọn truyền cho `define()` có thể là `List`, `Map`, hoặc `FormCollection`. Chúng được tự động chuyển đổi thành `FormCollection` khi áp dụng.

**Cách 2: Truyền `initialData` cho widget form**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Đặt giá trị trường

Sử dụng `NyFormActions` để đặt giá trị trường từ bất kỳ đâu:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Đặt tùy chọn trường

Cập nhật tùy chọn trên trường picker, chip, hoặc radio một cách động:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Đọc dữ liệu Form

Dữ liệu form được truy cập qua callback `onSubmit` khi form được gửi, hoặc qua callback `onChanged` cho cập nhật thời gian thực:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Xóa dữ liệu

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Cập nhật trường

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Nút gửi

Truyền `submitButton` và callback `onSubmit` khi tạo form:

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

`submitButton` được tự động hiển thị bên dưới các trường form. Bạn có thể sử dụng bất kỳ kiểu nút tích hợp hoặc widget tùy chỉnh.

Bạn cũng có thể sử dụng bất kỳ widget nào làm nút gửi bằng cách truyền nó như `footer`:

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

## Bố cục Form

Đặt các trường cạnh nhau bằng cách bọc chúng trong `List`:

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Các trường trong `List` được hiển thị trong `Row` với chiều rộng `Expanded` bằng nhau. Khoảng cách giữa các trường được kiểm soát bởi tham số `crossAxisSpacing` trên `NyFormWidget`.


<div id="field-visibility"></div>

## Hiển thị trường

Hiển thị hoặc ẩn trường theo chương trình bằng phương thức `hide()` và `show()` trên `Field`. Bạn có thể truy cập trường bên trong lớp form hoặc qua callback `onChanged`:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

Trường ẩn không được hiển thị trong giao diện nhưng vẫn tồn tại trong danh sách trường của form.


<div id="field-styling"></div>

## Tạo kiểu trường

Mỗi loại trường có lớp con `FieldStyle` tương ứng để tạo kiểu:

| Loại trường | Lớp kiểu |
|-------------|----------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Truyền đối tượng style cho tham số `style` của bất kỳ trường nào:

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

## Phương thức tĩnh NyFormWidget

`NyFormWidget` cung cấp các phương thức tĩnh để tương tác với form theo tên từ bất kỳ đâu trong ứng dụng:

| Phương thức | Mô tả |
|-------------|-------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Gửi form theo tên |
| `NyFormWidget.stateRefresh(name)` | Làm mới trạng thái UI form |
| `NyFormWidget.stateSetValue(name, key, value)` | Đặt giá trị trường theo tên form |
| `NyFormWidget.stateSetOptions(name, key, options)` | Đặt tùy chọn trường theo tên form |
| `NyFormWidget.stateClearData(name)` | Xóa tất cả trường theo tên form |
| `NyFormWidget.stateRefreshForm(name)` | Làm mới trường form (gọi lại `fields()`) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **Mẹo:** Ưu tiên sử dụng `NyFormActions` (xem bên dưới) thay vì gọi trực tiếp các phương thức tĩnh này - nó ngắn gọn hơn và ít lỗi hơn.


<div id="ny-form-widget-constructor-reference"></div>

## Tham chiếu constructor NyFormWidget

Khi kế thừa `NyFormWidget`, đây là các tham số constructor bạn có thể truyền:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

Callback `onChanged` nhận `Field` đã thay đổi và giá trị mới của nó:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` cung cấp cách thuận tiện để tương tác với form từ bất kỳ đâu trong ứng dụng. Định nghĩa nó như getter tĩnh trên lớp form:

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

### Các hành động có sẵn

| Phương thức | Mô tả |
|-------------|-------|
| `actions.updateField(key, value)` | Đặt giá trị trường |
| `actions.clearField(key)` | Xóa một trường cụ thể |
| `actions.clear()` | Xóa tất cả trường |
| `actions.refresh()` | Làm mới trạng thái UI form |
| `actions.refreshForm()` | Gọi lại `fields()` và xây dựng lại |
| `actions.setOptions(key, options)` | Đặt tùy chọn cho trường picker/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Gửi có xác thực |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Ghi đè NyFormWidget

Các phương thức bạn có thể ghi đè trong lớp con `NyFormWidget`:

| Ghi đè | Mô tả |
|--------|-------|
| `fields()` | Định nghĩa các trường form (bắt buộc) |
| `init` | Cung cấp dữ liệu ban đầu (đồng bộ hoặc bất đồng bộ) |
| `onChange(field, data)` | Xử lý thay đổi trường nội bộ |


<div id="all-field-types-reference"></div>

## Tham chiếu tất cả loại trường

| Constructor | Tham số chính | Mô tả |
|-------------|---------------|-------|
| `Field.text()` | — | Nhập văn bản chuẩn |
| `Field.email()` | — | Nhập email với loại bàn phím |
| `Field.password()` | `viewable` | Mật khẩu với tùy chọn bật/tắt hiển thị |
| `Field.number()` | `decimal` | Nhập số, tùy chọn thập phân |
| `Field.currency()` | `currency` (bắt buộc) | Nhập định dạng tiền tệ |
| `Field.capitalizeWords()` | — | Nhập văn bản viết hoa từ |
| `Field.capitalizeSentences()` | — | Nhập văn bản viết hoa câu |
| `Field.textArea()` | — | Nhập văn bản nhiều dòng |
| `Field.phoneNumber()` | — | Số điện thoại tự động định dạng |
| `Field.url()` | — | Nhập URL với loại bàn phím |
| `Field.mask()` | `mask` (bắt buộc), `match`, `maskReturnValue` | Nhập văn bản có mặt nạ |
| `Field.date()` | — | Trình chọn ngày |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Trình chọn ngày và giờ |
| `Field.checkbox()` | — | Checkbox boolean |
| `Field.switchBox()` | — | Công tắc bật/tắt boolean |
| `Field.picker()` | `options` (bắt buộc `FormCollection`) | Chọn đơn từ danh sách |
| `Field.radio()` | `options` (bắt buộc `FormCollection`) | Nhóm nút radio |
| `Field.chips()` | `options` (bắt buộc `FormCollection`) | Chọn nhiều chip |
| `Field.slider()` | — | Slider giá trị đơn |
| `Field.rangeSlider()` | — | Slider giá trị phạm vi |
| `Field.custom()` | `child` (bắt buộc `NyFieldStatefulWidget`) | Widget stateful tùy chỉnh |
| `Field.widget()` | `child` (bắt buộc `Widget`) | Nhúng bất kỳ widget (không phải trường) |
