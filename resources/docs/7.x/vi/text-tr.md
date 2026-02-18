# TextTr

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Nội suy chuỗi](#string-interpolation "Nội suy chuỗi")
- [Constructor có kiểu dáng](#styled-constructors "Constructor có kiểu dáng")
- [Tham số](#parameters "Tham số")


<div id="introduction"></div>

## Giới thiệu

Widget **TextTr** là một trình bọc tiện lợi xung quanh widget `Text` của Flutter, tự động dịch nội dung bằng hệ thống bản địa hóa của {{ config('app.name') }}.

Thay vì viết:

``` dart
Text("hello_world".tr())
```

Bạn có thể viết:

``` dart
TextTr("hello_world")
```

Điều này giúp mã của bạn gọn gàng và dễ đọc hơn, đặc biệt khi xử lý nhiều chuỗi được dịch.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

Widget sẽ tra cứu khóa dịch trong các tệp ngôn ngữ của bạn (ví dụ: `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Nội suy chuỗi

Sử dụng tham số `arguments` để chèn các giá trị động vào bản dịch:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

Trong tệp ngôn ngữ:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Kết quả: **Hello, John!**

### Nhiều đối số

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

Kết quả: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Constructor có kiểu dáng

`TextTr` cung cấp các constructor được đặt tên để tự động áp dụng kiểu văn bản từ theme:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Sử dụng kiểu `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Sử dụng kiểu `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Sử dụng kiểu `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Sử dụng kiểu `Theme.of(context).textTheme.labelLarge`.

### Ví dụ với constructor có kiểu dáng

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## Tham số

`TextTr` hỗ trợ tất cả các tham số tiêu chuẩn của widget `Text`:

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `data` | `String` | Khóa dịch để tra cứu |
| `arguments` | `Map<String, String>?` | Các cặp khóa-giá trị cho nội suy chuỗi |
| `style` | `TextStyle?` | Kiểu văn bản |
| `textAlign` | `TextAlign?` | Cách căn chỉnh văn bản |
| `maxLines` | `int?` | Số dòng tối đa |
| `overflow` | `TextOverflow?` | Cách xử lý tràn |
| `softWrap` | `bool?` | Có ngắt dòng tại điểm mềm hay không |
| `textDirection` | `TextDirection?` | Hướng văn bản |
| `locale` | `Locale?` | Locale cho hiển thị văn bản |
| `semanticsLabel` | `String?` | Nhãn trợ năng |

## So sánh

| Cách tiếp cận | Mã |
|----------|------|
| Truyền thống | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Với đối số | `TextTr("hello", arguments: {"name": "John"})` |
| Có kiểu dáng | `TextTr.headlineLarge("title")` |
