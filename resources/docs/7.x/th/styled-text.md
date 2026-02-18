# Styled Text

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
- [โหมด Children](#children-mode "โหมด Children")
- [โหมด Template](#template-mode "โหมด Template")
  - [การจัดสไตล์ Placeholder](#styling-placeholders "การจัดสไตล์ Placeholder")
  - [Tap Callback](#tap-callbacks "Tap Callback")
  - [คีย์แบบ Pipe-Separated](#pipe-keys "คีย์แบบ Pipe-Separated")
  - [คีย์สำหรับการแปลภาษา](#localization-keys "คีย์สำหรับการแปลภาษา")
- [พารามิเตอร์](#parameters "พารามิเตอร์")
- [Text Extensions](#text-extensions "Text Extensions")
  - [สไตล์ Typography](#typography-styles "สไตล์ Typography")
  - [เมธอดอรรถประโยชน์](#utility-methods "เมธอดอรรถประโยชน์")
- [ตัวอย่าง](#examples "ตัวอย่างการใช้งานจริง")

<div id="introduction"></div>

## บทนำ

**StyledText** เป็น widget สำหรับแสดง rich text ที่มีสไตล์ผสม, tap callback และ pointer event โดยจะ render เป็น `RichText` widget พร้อม `TextSpan` children หลายตัว ให้คุณควบคุมรายละเอียดแต่ละส่วนของข้อความได้อย่างละเอียด

StyledText รองรับสองโหมด:

1. **โหมด Children** -- ส่ง list ของ `Text` widget แต่ละตัวมีสไตล์เป็นของตัวเอง
2. **โหมด Template** -- ใช้ไวยากรณ์ `@{{placeholder}}` ใน string แล้ว map placeholder ไปยังสไตล์และ action

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

``` dart
// โหมด Children - list ของ Text widget
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// โหมด Template - ไวยากรณ์ placeholder
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## โหมด Children

ส่ง list ของ `Text` widget เพื่อประกอบข้อความที่มีสไตล์:

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

`style` พื้นฐานจะถูกนำไปใช้กับ child ใดก็ตามที่ไม่มีสไตล์เป็นของตัวเอง

### Pointer Event

ตรวจจับเมื่อ pointer เข้าหรือออกจากส่วนของข้อความ:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## โหมด Template

ใช้ `StyledText.template()` พร้อมไวยากรณ์ `@{{placeholder}}`:

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

ข้อความระหว่าง `@{{ }}` เป็นทั้ง **ข้อความที่แสดง** และ **คีย์** ที่ใช้ค้นหาสไตล์และ tap callback

<div id="styling-placeholders"></div>

### การจัดสไตล์ Placeholder

Map ชื่อ placeholder ไปยังออบเจ็กต์ `TextStyle`:

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

### Tap Callback

Map ชื่อ placeholder ไปยัง tap handler:

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

### คีย์แบบ Pipe-Separated

ใช้สไตล์หรือ callback เดียวกันกับ placeholder หลายตัวโดยใช้คีย์แยกด้วย pipe `|`:

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

สิ่งนี้จะ map สไตล์และ callback เดียวกันไปยัง placeholder ทั้งสามตัว

<div id="localization-keys"></div>

### คีย์สำหรับการแปลภาษา

ใช้ไวยากรณ์ `@{{key:text}}` เพื่อแยก **คีย์สำหรับค้นหา** ออกจาก **ข้อความที่แสดง** สิ่งนี้มีประโยชน์สำหรับการแปลภาษา เพราะคีย์จะคงเดิมในทุก locale ในขณะที่ข้อความที่แสดงจะเปลี่ยนตามภาษา

``` dart
// ในไฟล์ locale ของคุณ:
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
// EN renders: "Learn Languages, Reading and Speaking in AppName"
// ES renders: "Aprende Idiomas, Lectura y Habla en AppName"
```

ส่วนก่อน `:` คือ **คีย์** ที่ใช้ค้นหาสไตล์และ tap callback ส่วนหลัง `:` คือ **ข้อความที่แสดง** บนหน้าจอ หากไม่มี `:` placeholder จะทำงานเหมือนเดิม ซึ่งเข้ากันได้อย่างสมบูรณ์กับเวอร์ชันก่อนหน้า

สามารถใช้งานร่วมกับฟีเจอร์ที่มีอยู่ทั้งหมดรวมถึง [คีย์แบบ pipe-separated](#pipe-keys) และ [tap callback](#tap-callbacks)

<div id="parameters"></div>

## พารามิเตอร์

### StyledText (โหมด Children)

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | จำเป็น | รายการ Text widget |
| `style` | `TextStyle?` | null | สไตล์พื้นฐานสำหรับ children ทั้งหมด |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | callback เมื่อ pointer เข้า |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | callback เมื่อ pointer ออก |
| `spellOut` | `bool?` | null | สะกดข้อความทีละตัวอักษร |
| `softWrap` | `bool` | `true` | เปิดใช้การตัดบรรทัดอัตโนมัติ |
| `textAlign` | `TextAlign` | `TextAlign.start` | การจัดตำแหน่งข้อความ |
| `textDirection` | `TextDirection?` | null | ทิศทางข้อความ |
| `maxLines` | `int?` | null | จำนวนบรรทัดสูงสุด |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | พฤติกรรมเมื่อล้น |
| `locale` | `Locale?` | null | locale ของข้อความ |
| `strutStyle` | `StrutStyle?` | null | strut style |
| `textScaler` | `TextScaler?` | null | ตัวปรับขนาดข้อความ |
| `selectionColor` | `Color?` | null | สีไฮไลท์เมื่อเลือก |

### StyledText.template (โหมด Template)

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `text` | `String` | จำเป็น | ข้อความ template พร้อมไวยากรณ์ `@{{placeholder}}` |
| `styles` | `Map<String, TextStyle>?` | null | map ของชื่อ placeholder ไปยังสไตล์ |
| `onTap` | `Map<String, VoidCallback>?` | null | map ของชื่อ placeholder ไปยัง tap callback |
| `style` | `TextStyle?` | null | สไตล์พื้นฐานสำหรับข้อความที่ไม่ใช่ placeholder |

พารามิเตอร์อื่นๆ ทั้งหมด (`softWrap`, `textAlign`, `maxLines` ฯลฯ) เหมือนกับ constructor แบบ children

<div id="text-extensions"></div>

## Text Extensions

{{ config('app.name') }} ขยาย `Text` widget ของ Flutter ด้วยเมธอด typography และอรรถประโยชน์

<div id="typography-styles"></div>

### สไตล์ Typography

ใช้สไตล์ typography ของ Material Design กับ `Text` widget ใดก็ได้:

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

แต่ละเมธอดรับค่า override เพิ่มเติมได้:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**ค่า override ที่ใช้ได้** (เหมือนกันสำหรับเมธอด typography ทั้งหมด):

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `color` | `Color?` | สีข้อความ |
| `fontSize` | `double?` | ขนาดฟอนต์ |
| `fontWeight` | `FontWeight?` | น้ำหนักฟอนต์ |
| `fontStyle` | `FontStyle?` | ตัวเอียง/ปกติ |
| `letterSpacing` | `double?` | ระยะห่างตัวอักษร |
| `wordSpacing` | `double?` | ระยะห่างคำ |
| `height` | `double?` | ความสูงบรรทัด |
| `decoration` | `TextDecoration?` | การตกแต่งข้อความ |
| `decorationColor` | `Color?` | สีการตกแต่ง |
| `decorationStyle` | `TextDecorationStyle?` | สไตล์การตกแต่ง |
| `decorationThickness` | `double?` | ความหนาการตกแต่ง |
| `fontFamily` | `String?` | ตระกูลฟอนต์ |
| `shadows` | `List<Shadow>?` | เงาข้อความ |
| `overflow` | `TextOverflow?` | พฤติกรรมเมื่อล้น |

<div id="utility-methods"></div>

### เมธอดอรรถประโยชน์

``` dart
// น้ำหนักฟอนต์
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// การจัดตำแหน่ง
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// จำนวนบรรทัดสูงสุด
Text("Long text...").setMaxLines(2)

// ตระกูลฟอนต์
Text("Custom font").setFontFamily("Roboto")

// ขนาดฟอนต์
Text("Big text").setFontSize(24)

// สไตล์กำหนดเอง
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// คัดลอกพร้อมแก้ไข
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## ตัวอย่าง

### ลิงก์ข้อกำหนดและเงื่อนไข

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

### แสดงเวอร์ชัน

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

### ย่อหน้าสไตล์ผสม

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

### ต่อเชื่อม Typography

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
