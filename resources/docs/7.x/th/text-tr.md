# TextTr

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
- [การแทรกค่าลงในสตริง](#string-interpolation "การแทรกค่าลงในสตริง")
- [Styled Constructor](#styled-constructors "Styled Constructor")
- [พารามิเตอร์](#parameters "พารามิเตอร์")


<div id="introduction"></div>

## บทนำ

**TextTr** widget เป็น wrapper ที่สะดวกรอบ `Text` widget ของ Flutter ซึ่งจะแปลเนื้อหาโดยอัตโนมัติโดยใช้ระบบแปลภาษาของ {{ config('app.name') }}

แทนที่จะเขียน:

``` dart
Text("hello_world".tr())
```

คุณสามารถเขียน:

``` dart
TextTr("hello_world")
```

สิ่งนี้ทำให้โค้ดของคุณสะอาดและอ่านง่ายขึ้น โดยเฉพาะเมื่อจัดการกับสตริงที่ต้องแปลจำนวนมาก

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

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

widget จะค้นหาคีย์การแปลในไฟล์ภาษาของคุณ (เช่น `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## การแทรกค่าลงในสตริง

ใช้พารามิเตอร์ `arguments` เพื่อแทรกค่าแบบไดนามิกลงในการแปลของคุณ:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

ในไฟล์ภาษาของคุณ:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

ผลลัพธ์: **Hello, John!**

### อาร์กิวเมนต์หลายตัว

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

ผลลัพธ์: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Styled Constructor

`TextTr` มี named constructor ที่ใช้สไตล์ข้อความจาก theme ของคุณโดยอัตโนมัติ:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

ใช้สไตล์ `Theme.of(context).textTheme.displayLarge`

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

ใช้สไตล์ `Theme.of(context).textTheme.headlineLarge`

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

ใช้สไตล์ `Theme.of(context).textTheme.bodyLarge`

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

ใช้สไตล์ `Theme.of(context).textTheme.labelLarge`

### ตัวอย่างการใช้ Styled Constructor

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

## พารามิเตอร์

`TextTr` รองรับพารามิเตอร์มาตรฐานทั้งหมดของ `Text` widget:

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `data` | `String` | คีย์การแปลที่ต้องการค้นหา |
| `arguments` | `Map<String, String>?` | คู่คีย์-ค่าสำหรับการแทรกค่าลงในสตริง |
| `style` | `TextStyle?` | สไตล์ข้อความ |
| `textAlign` | `TextAlign?` | การจัดตำแหน่งข้อความ |
| `maxLines` | `int?` | จำนวนบรรทัดสูงสุด |
| `overflow` | `TextOverflow?` | วิธีจัดการเมื่อข้อความล้น |
| `softWrap` | `bool?` | ตัดบรรทัดที่จุดตัดอัตโนมัติหรือไม่ |
| `textDirection` | `TextDirection?` | ทิศทางของข้อความ |
| `locale` | `Locale?` | locale สำหรับการ render ข้อความ |
| `semanticsLabel` | `String?` | ป้ายกำกับสำหรับการเข้าถึง |

## การเปรียบเทียบ

| วิธีการ | โค้ด |
|----------|------|
| แบบดั้งเดิม | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| พร้อมอาร์กิวเมนต์ | `TextTr("hello", arguments: {"name": "John"})` |
| แบบมีสไตล์ | `TextTr.headlineLarge("title")` |
