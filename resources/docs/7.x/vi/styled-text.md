# Styled Text

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Chế độ Children](#children-mode "Chế độ Children")
- [Chế độ Template](#template-mode "Chế độ Template")
  - [Tạo kiểu cho Placeholder](#styling-placeholders "Tạo kiểu cho Placeholder")
  - [Callback khi nhấn](#tap-callbacks "Callback khi nhấn")
  - [Khóa phân tách bằng Pipe](#pipe-keys "Khóa phân tách bằng Pipe")
  - [Khóa bản địa hóa](#localization-keys "Khóa bản địa hóa")
- [Tham số](#parameters "Tham số")
- [Text Extensions](#text-extensions "Text Extensions")
  - [Kiểu Typography](#typography-styles "Kiểu Typography")
  - [Phương thức tiện ích](#utility-methods "Phương thức tiện ích")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

**StyledText** là widget hiển thị văn bản rich text với các kiểu hỗn hợp, callback khi nhấn và sự kiện con trỏ. Nó render dưới dạng widget `RichText` với nhiều `TextSpan` con, cho bạn quyền kiểm soát chi tiết từng đoạn văn bản.

StyledText hỗ trợ hai chế độ:

1. **Chế độ Children** -- truyền danh sách widget `Text`, mỗi widget có kiểu riêng
2. **Chế độ Template** -- sử dụng cú pháp `@{{placeholder}}` trong chuỗi và ánh xạ placeholder đến kiểu và hành động

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

``` dart
// Chế độ Children - danh sách widget Text
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Chế độ Template - cú pháp placeholder
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Chế độ Children

Truyền danh sách widget `Text` để soạn văn bản có kiểu:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

`style` cơ sở được áp dụng cho bất kỳ con nào không có kiểu riêng.

### Sự kiện con trỏ

Phát hiện khi con trỏ di vào hoặc rời khỏi đoạn văn bản:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Đang di chuột qua: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Đã rời: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Chế độ Template

Sử dụng `StyledText.template()` với cú pháp `@{{placeholder}}`:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

Văn bản giữa `@{{ }}` vừa là **văn bản hiển thị** vừa là **khóa** dùng để tra cứu kiểu và callback khi nhấn.

<div id="styling-placeholders"></div>

### Tạo kiểu cho Placeholder

Ánh xạ tên placeholder đến đối tượng `TextStyle`:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### Callback khi nhấn

Ánh xạ tên placeholder đến handler khi nhấn:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### Khóa phân tách bằng Pipe

Áp dụng cùng kiểu hoặc callback cho nhiều placeholder bằng khóa phân tách bởi dấu `|`:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

Điều này ánh xạ cùng kiểu và callback cho cả ba placeholder.

<div id="localization-keys"></div>

### Khóa bản địa hóa

Sử dụng cú pháp `@{{key:text}}` để tách **khóa tra cứu** khỏi **văn bản hiển thị**. Điều này hữu ích cho bản địa hóa -- khóa giữ nguyên trong tất cả locale trong khi văn bản hiển thị thay đổi theo ngôn ngữ.

``` dart
// Trong tệp locale:
// en.json → "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} in @{{app:AppName}}"
// es.json → "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}} en @{{app:AppName}}"

StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(
      color: Colors.blue,
      fontWeight: FontWeight.bold,
    ),
    "app": TextStyle(color: Colors.green),
  },
  onTap: {
    "app": () => routeTo("/about"),
  },
)
// EN render: "Learn Languages, Reading and Speaking in AppName"
// ES render: "Aprende Idiomas, Lectura y Habla en AppName"
```

Phần trước `:` là **khóa** dùng để tra cứu kiểu và callback khi nhấn. Phần sau `:` là **văn bản hiển thị** render trên màn hình. Không có `:`, placeholder hoạt động giống như trước -- hoàn toàn tương thích ngược.

Tính năng này hoạt động với tất cả tính năng hiện có bao gồm [khóa phân tách bằng pipe](#pipe-keys) và [callback khi nhấn](#tap-callbacks).

<div id="parameters"></div>

## Tham số

### StyledText (Chế độ Children)

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | bắt buộc | Danh sách widget Text |
| `style` | `TextStyle?` | null | Kiểu cơ sở cho tất cả children |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Callback con trỏ đi vào |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Callback con trỏ rời đi |
| `spellOut` | `bool?` | null | Đánh vần văn bản từng ký tự |
| `softWrap` | `bool` | `true` | Bật ngắt dòng mềm |
| `textAlign` | `TextAlign` | `TextAlign.start` | Căn chỉnh văn bản |
| `textDirection` | `TextDirection?` | null | Hướng văn bản |
| `maxLines` | `int?` | null | Số dòng tối đa |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Hành vi tràn |
| `locale` | `Locale?` | null | Locale văn bản |
| `strutStyle` | `StrutStyle?` | null | Kiểu strut |
| `textScaler` | `TextScaler?` | null | Trình chia tỷ lệ văn bản |
| `selectionColor` | `Color?` | null | Màu highlight khi chọn |

### StyledText.template (Chế độ Template)

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `text` | `String` | bắt buộc | Văn bản template với cú pháp `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | Ánh xạ tên placeholder đến kiểu |
| `onTap` | `Map<String, VoidCallback>?` | null | Ánh xạ tên placeholder đến callback khi nhấn |
| `style` | `TextStyle?` | null | Kiểu cơ sở cho văn bản không phải placeholder |

Tất cả tham số khác (`softWrap`, `textAlign`, `maxLines`, v.v.) giống như constructor children.

<div id="text-extensions"></div>

## Text Extensions

{{ config('app.name') }} mở rộng widget `Text` của Flutter với các phương thức typography và tiện ích.

<div id="typography-styles"></div>

### Kiểu Typography

Áp dụng kiểu typography Material Design cho bất kỳ widget `Text` nào:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

Mỗi phương thức chấp nhận các tùy chỉnh ghi đè:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Các tùy chỉnh ghi đè có sẵn** (giống cho tất cả phương thức typography):

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `color` | `Color?` | Màu văn bản |
| `fontSize` | `double?` | Cỡ chữ |
| `fontWeight` | `FontWeight?` | Độ đậm chữ |
| `fontStyle` | `FontStyle?` | Nghiêng/bình thường |
| `letterSpacing` | `double?` | Khoảng cách chữ |
| `wordSpacing` | `double?` | Khoảng cách từ |
| `height` | `double?` | Chiều cao dòng |
| `decoration` | `TextDecoration?` | Trang trí văn bản |
| `decorationColor` | `Color?` | Màu trang trí |
| `decorationStyle` | `TextDecorationStyle?` | Kiểu trang trí |
| `decorationThickness` | `double?` | Độ dày trang trí |
| `fontFamily` | `String?` | Họ font |
| `shadows` | `List<Shadow>?` | Bóng văn bản |
| `overflow` | `TextOverflow?` | Hành vi tràn |

<div id="utility-methods"></div>

### Phương thức tiện ích

``` dart
// Độ đậm chữ
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Căn chỉnh
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Số dòng tối đa
Text("Long text...").setMaxLines(2)

// Họ font
Text("Custom font").setFontFamily("Roboto")

// Cỡ chữ
Text("Big text").setFontSize(24)

// Kiểu tùy chỉnh
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Sao chép với sửa đổi
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Ví dụ

### Liên kết điều khoản và điều kiện

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### Hiển thị phiên bản

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### Đoạn văn kiểu hỗn hợp

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### Chuỗi Typography

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
