# LanguageSwitcher

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- Cách sử dụng
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Bottom Sheet Modal](#usage-bottom-modal "Bottom Sheet Modal")
- [Builder Dropdown tùy chỉnh](#custom-builder "Builder Dropdown tùy chỉnh")
- [Tham số](#parameters "Tham số")
- [Phương thức tĩnh](#methods "Phương thức tĩnh")


<div id="introduction"></div>

## Giới thiệu

Widget **LanguageSwitcher** cung cấp cách dễ dàng để xử lý chuyển đổi ngôn ngữ trong các dự án {{ config('app.name') }} của bạn. Nó tự động phát hiện các ngôn ngữ có sẵn trong thư mục `/lang` và hiển thị chúng cho người dùng.

**LanguageSwitcher làm gì?**

- Hiển thị các ngôn ngữ có sẵn từ thư mục `/lang`
- Chuyển đổi ngôn ngữ ứng dụng khi người dùng chọn
- Lưu trữ ngôn ngữ đã chọn qua các lần khởi động lại ứng dụng
- Tự động cập nhật giao diện khi ngôn ngữ thay đổi

> **Lưu ý**: Nếu ứng dụng của bạn chưa được bản địa hóa, hãy tìm hiểu cách thực hiện trong tài liệu [Bản địa hóa](/docs/7.x/localization) trước khi sử dụng widget này.

<div id="usage-dropdown"></div>

## Widget Dropdown

Cách đơn giản nhất để sử dụng `LanguageSwitcher` là dạng dropdown trong thanh ứng dụng:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Khi người dùng nhấn vào dropdown, họ sẽ thấy danh sách các ngôn ngữ có sẵn. Sau khi chọn ngôn ngữ, ứng dụng sẽ tự động chuyển đổi và cập nhật giao diện.

<div id="usage-bottom-modal"></div>

## Bottom Sheet Modal

Bạn cũng có thể hiển thị ngôn ngữ trong bottom sheet modal:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

Bottom modal hiển thị danh sách ngôn ngữ với dấu kiểm bên cạnh ngôn ngữ hiện đang được chọn.

### Tùy chỉnh chiều cao Modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## Builder Dropdown tùy chỉnh

Tùy chỉnh cách mỗi tùy chọn ngôn ngữ hiển thị trong dropdown:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### Xử lý thay đổi ngôn ngữ

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## Tham số

| Tham số | Kiểu | Mặc định | Mô tả |
|---------|------|----------|-------|
| `icon` | `Widget?` | - | Biểu tượng tùy chỉnh cho nút dropdown |
| `iconEnabledColor` | `Color?` | - | Màu biểu tượng dropdown |
| `iconSize` | `double` | `24` | Kích thước biểu tượng dropdown |
| `dropdownBgColor` | `Color?` | - | Màu nền menu dropdown |
| `hint` | `Widget?` | - | Widget gợi ý khi chưa chọn ngôn ngữ |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Chiều cao mỗi mục dropdown |
| `elevation` | `int` | `8` | Độ nổi menu dropdown |
| `padding` | `EdgeInsetsGeometry?` | - | Padding xung quanh dropdown |
| `borderRadius` | `BorderRadius?` | - | Bo góc menu dropdown |
| `textStyle` | `TextStyle?` | - | Kiểu văn bản cho mục dropdown |
| `langPath` | `String` | `'lang'` | Đường dẫn đến file ngôn ngữ trong assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Builder tùy chỉnh cho mục dropdown |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Alignment của mục dropdown |
| `dropdownOnTap` | `Function()?` | - | Callback khi mục dropdown được nhấn |
| `onTap` | `Function()?` | - | Callback khi nút dropdown được nhấn |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback khi ngôn ngữ được thay đổi |

<div id="methods"></div>

## Phương thức tĩnh

### Lấy ngôn ngữ hiện tại

Lấy ngôn ngữ đang được chọn:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Lưu ngôn ngữ

Lưu tùy chọn ngôn ngữ thủ công:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Xóa ngôn ngữ

Xóa tùy chọn ngôn ngữ đã lưu:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Lấy dữ liệu ngôn ngữ

Lấy thông tin ngôn ngữ từ mã locale:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Lấy danh sách ngôn ngữ

Lấy tất cả ngôn ngữ có sẵn từ thư mục `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Hiển thị Bottom Modal

Hiển thị modal chọn ngôn ngữ:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Các Locale được hỗ trợ

Widget `LanguageSwitcher` hỗ trợ hàng trăm mã locale với tên dễ đọc. Một số ví dụ:

| Mã Locale | Tên ngôn ngữ |
|-----------|--------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

Danh sách đầy đủ bao gồm các biến thể khu vực cho hầu hết các ngôn ngữ.
