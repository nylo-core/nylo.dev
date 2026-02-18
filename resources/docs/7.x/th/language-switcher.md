# LanguageSwitcher

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- การใช้งาน
    - [widget แบบเลื่อนลง](#usage-dropdown "widget แบบเลื่อนลง")
    - [Bottom Sheet Modal](#usage-bottom-modal "Bottom Sheet Modal")
- [ตัวสร้างเลื่อนลงที่กำหนดเอง](#custom-builder "ตัวสร้างเลื่อนลงที่กำหนดเอง")
- [พารามิเตอร์](#parameters "พารามิเตอร์")
- [เมธอดแบบ Static](#methods "เมธอดแบบ Static")


<div id="introduction"></div>

## บทนำ

widget **LanguageSwitcher** ให้วิธีง่ายๆ ในการจัดการการสลับภาษาในโปรเจกต์ {{ config('app.name') }} ของคุณ มันตรวจจับภาษาที่มีอยู่ในไดเรกทอรี `/lang` ของคุณโดยอัตโนมัติและแสดงให้ผู้ใช้เห็น

**LanguageSwitcher ทำอะไรได้บ้าง?**

- แสดงภาษาที่มีอยู่จากไดเรกทอรี `/lang` ของคุณ
- สลับภาษาของแอปเมื่อผู้ใช้เลือก
- บันทึกภาษาที่เลือกไว้ข้ามการเริ่มต้นแอปใหม่
- อัปเดต UI โดยอัตโนมัติเมื่อภาษาเปลี่ยน

> **หมายเหตุ**: หากแอปของคุณยังไม่ได้แปลภาษา เรียนรู้วิธีการได้ในเอกสาร [การแปลภาษา](/docs/7.x/localization) ก่อนใช้ widget นี้

<div id="usage-dropdown"></div>

## widget แบบเลื่อนลง

วิธีที่ง่ายที่สุดในการใช้ `LanguageSwitcher` คือเป็นเมนูเลื่อนลงใน app bar ของคุณ:

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

เมื่อผู้ใช้แตะเมนูเลื่อนลง จะเห็นรายการภาษาที่มีอยู่ หลังจากเลือกภาษา แอปจะสลับและอัปเดต UI โดยอัตโนมัติ

<div id="usage-bottom-modal"></div>

## Bottom Sheet Modal

คุณยังสามารถแสดงภาษาใน bottom sheet modal ได้:

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

bottom modal จะแสดงรายการภาษาพร้อมเครื่องหมายถูกข้างภาษาที่เลือกอยู่ในปัจจุบัน

### การปรับแต่งความสูงของ Modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## ตัวสร้างเลื่อนลงที่กำหนดเอง

ปรับแต่งลักษณะของแต่ละตัวเลือกภาษาในเมนูเลื่อนลง:

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

### การจัดการการเปลี่ยนภาษา

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## พารามิเตอร์

| พารามิเตอร์ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | ไอคอนที่กำหนดเองสำหรับปุ่มเลื่อนลง |
| `iconEnabledColor` | `Color?` | - | สีของไอคอนเลื่อนลง |
| `iconSize` | `double` | `24` | ขนาดของไอคอนเลื่อนลง |
| `dropdownBgColor` | `Color?` | - | สีพื้นหลังของเมนูเลื่อนลง |
| `hint` | `Widget?` | - | widget คำใบ้เมื่อไม่มีภาษาที่เลือก |
| `itemHeight` | `double` | `kMinInteractiveDimension` | ความสูงของแต่ละรายการเลื่อนลง |
| `elevation` | `int` | `8` | ระดับเงาของเมนูเลื่อนลง |
| `padding` | `EdgeInsetsGeometry?` | - | ระยะห่างรอบเมนูเลื่อนลง |
| `borderRadius` | `BorderRadius?` | - | รัศมีขอบของเมนูเลื่อนลง |
| `textStyle` | `TextStyle?` | - | สไตล์ข้อความสำหรับรายการเลื่อนลง |
| `langPath` | `String` | `'lang'` | เส้นทางไปยังไฟล์ภาษาใน assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | ตัวสร้างที่กำหนดเองสำหรับรายการเลื่อนลง |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | การจัดตำแหน่งของรายการเลื่อนลง |
| `dropdownOnTap` | `Function()?` | - | callback เมื่อแตะรายการเลื่อนลง |
| `onTap` | `Function()?` | - | callback เมื่อแตะปุ่มเลื่อนลง |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | callback เมื่อเปลี่ยนภาษา |

<div id="methods"></div>

## เมธอดแบบ Static

### ดึงภาษาปัจจุบัน

ดึงข้อมูลภาษาที่เลือกอยู่ในปัจจุบัน:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### บันทึกภาษา

บันทึกภาษาที่ต้องการด้วยตนเอง:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### ล้างภาษา

ลบภาษาที่บันทึกไว้:

``` dart
await LanguageSwitcher.clearLanguage();
```

### ดึงข้อมูลภาษา

ดึงข้อมูลภาษาจากรหัส locale:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### ดึงรายการภาษา

ดึงภาษาทั้งหมดที่มีจากไดเรกทอรี `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### แสดง Bottom Modal

แสดง modal สำหรับเลือกภาษา:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Locale ที่รองรับ

widget `LanguageSwitcher` รองรับรหัส locale หลายร้อยรหัสพร้อมชื่อที่อ่านได้ง่าย ตัวอย่างบางส่วน:

| รหัส Locale | ชื่อภาษา |
|-------------|---------------|
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

รายการเต็มรวมถึงรูปแบบภูมิภาคสำหรับภาษาส่วนใหญ่
