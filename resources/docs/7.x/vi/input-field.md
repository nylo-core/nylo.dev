# InputField

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Xác thực](#validation "Xác thực")
- Biến thể
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Mặt nạ nhập liệu](#masking "Mặt nạ nhập liệu")
- [Header và Footer](#header-footer "Header và Footer")
- [Đầu vào có thể xóa](#clearable "Đầu vào có thể xóa")
- [Quản lý trạng thái](#state-management "Quản lý trạng thái")
- [Tham số](#parameters "Tham số")


<div id="introduction"></div>

## Giới thiệu

Widget **InputField** là trường nhập văn bản nâng cao của {{ config('app.name') }} với hỗ trợ tích hợp cho:

- Xác thực với thông báo lỗi tùy chỉnh
- Bật/tắt hiển thị mật khẩu
- Mặt nạ nhập liệu (số điện thoại, thẻ tín dụng, v.v.)
- Widget header và footer
- Đầu vào có thể xóa
- Tích hợp quản lý trạng thái
- Dữ liệu giả cho phát triển

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

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

## Xác thực

Sử dụng tham số `formValidator` để thêm quy tắc xác thực:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Trường sẽ xác thực khi người dùng chuyển focus ra khỏi nó.

### Xử lý lỗi xác thực tùy chỉnh

Xử lý lỗi xác thực theo chương trình:

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

Xem tất cả quy tắc xác thực có sẵn trong tài liệu [Xác thực](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Trường mật khẩu được cấu hình sẵn với văn bản ẩn và bật/tắt hiển thị:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Tùy chỉnh hiển thị mật khẩu

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Trường email được cấu hình sẵn với bàn phím email và autofocus:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Tự động viết hoa chữ cái đầu tiên của mỗi từ:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Mặt nạ nhập liệu

Áp dụng mặt nạ nhập liệu cho dữ liệu định dạng như số điện thoại hoặc thẻ tín dụng:

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

| Tham số | Mô tả |
|---------|-------|
| `mask` | Mẫu mặt nạ sử dụng `#` làm placeholder |
| `maskMatch` | Mẫu regex cho các ký tự đầu vào hợp lệ |
| `maskedReturnValue` | Nếu true, trả về giá trị đã định dạng; nếu false, trả về đầu vào thô |

<div id="header-footer"></div>

## Header và Footer

Thêm widget phía trên hoặc phía dưới trường nhập:

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

## Đầu vào có thể xóa

Thêm nút xóa để nhanh chóng xóa trống trường:

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

## Quản lý trạng thái

Đặt tên trạng thái cho trường nhập để điều khiển theo chương trình:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Hành động trạng thái

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

## Tham số

### Tham số thường dùng

| Tham số | Kiểu | Mặc định | Mô tả |
|---------|------|----------|-------|
| `controller` | `TextEditingController` | bắt buộc | Điều khiển văn bản đang chỉnh sửa |
| `labelText` | `String?` | - | Nhãn hiển thị phía trên trường |
| `hintText` | `String?` | - | Văn bản placeholder |
| `formValidator` | `FormValidator?` | - | Quy tắc xác thực |
| `validateOnFocusChange` | `bool` | `true` | Xác thực khi thay đổi focus |
| `obscureText` | `bool` | `false` | Ẩn đầu vào (cho mật khẩu) |
| `keyboardType` | `TextInputType` | `text` | Loại bàn phím |
| `autoFocus` | `bool` | `false` | Tự động focus khi build |
| `readOnly` | `bool` | `false` | Đặt trường chỉ đọc |
| `enabled` | `bool?` | - | Bật/tắt trường |
| `maxLines` | `int?` | `1` | Số dòng tối đa |
| `maxLength` | `int?` | - | Số ký tự tối đa |

### Tham số tạo kiểu

| Tham số | Kiểu | Mô tả |
|---------|------|-------|
| `backgroundColor` | `Color?` | Màu nền trường |
| `borderRadius` | `BorderRadius?` | Bo góc |
| `border` | `InputBorder?` | Viền mặc định |
| `focusedBorder` | `InputBorder?` | Viền khi focus |
| `enabledBorder` | `InputBorder?` | Viền khi bật |
| `contentPadding` | `EdgeInsetsGeometry?` | Padding nội bộ |
| `style` | `TextStyle?` | Kiểu văn bản |
| `labelStyle` | `TextStyle?` | Kiểu văn bản nhãn |
| `hintStyle` | `TextStyle?` | Kiểu văn bản gợi ý |
| `prefixIcon` | `Widget?` | Biểu tượng trước đầu vào |

### Tham số mặt nạ

| Tham số | Kiểu | Mô tả |
|---------|------|-------|
| `mask` | `String?` | Mẫu mặt nạ (ví dụ: "###-####") |
| `maskMatch` | `String?` | Regex cho ký tự hợp lệ |
| `maskedReturnValue` | `bool?` | Trả về giá trị có mặt nạ hoặc thô |

### Tham số tính năng

| Tham số | Kiểu | Mô tả |
|---------|------|-------|
| `header` | `Widget?` | Widget phía trên trường |
| `footer` | `Widget?` | Widget phía dưới trường |
| `clearable` | `bool?` | Hiển thị nút xóa |
| `clearIcon` | `Widget?` | Biểu tượng xóa tùy chỉnh |
| `passwordVisible` | `bool?` | Hiển thị bật/tắt mật khẩu |
| `passwordViewable` | `bool?` | Cho phép bật/tắt hiển thị mật khẩu |
| `dummyData` | `String?` | Dữ liệu giả cho phát triển |
| `stateName` | `String?` | Tên cho quản lý trạng thái |
| `onChanged` | `Function(String)?` | Gọi khi giá trị thay đổi |
