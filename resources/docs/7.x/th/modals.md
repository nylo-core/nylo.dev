# Modals

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [สร้าง Modal](#creating-a-modal "สร้าง Modal")
- [การใช้งานพื้นฐาน](#basic-usage "การใช้งานพื้นฐาน")
- [สร้าง Modal](#creating-a-modal "สร้าง Modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [พารามิเตอร์](#parameters "พารามิเตอร์")
  - [การดำเนินการ](#actions "การดำเนินการ")
  - [ส่วนหัว](#header "ส่วนหัว")
  - [ปุ่มปิด](#close-button "ปุ่มปิด")
  - [การตกแต่งที่กำหนดเอง](#custom-decoration "การตกแต่งที่กำหนดเอง")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [ตัวอย่าง](#examples "ตัวอย่างที่ใช้ได้จริง")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มีระบบ modal ที่สร้างขึ้นรอบ **bottom sheet modals**

คลาส `BottomSheetModal` ให้ API ที่ยืดหยุ่นสำหรับการแสดงเนื้อหาซ้อนทับพร้อมการดำเนินการ ส่วนหัว ปุ่มปิด และการจัดรูปแบบที่กำหนดเอง

Modals มีประโยชน์สำหรับ:
- กล่องยืนยัน (เช่น ออกจากระบบ ลบ)
- แบบฟอร์มป้อนข้อมูลด่วน
- แผ่นการดำเนินการพร้อมตัวเลือกหลายรายการ
- ข้อมูลซ้อนทับ

<div id="creating-a-modal"></div>

## สร้าง Modal

คุณสามารถสร้าง modal ใหม่โดยใช้ Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

สิ่งนี้จะสร้างสองอย่าง:

1. **widget เนื้อหา modal** ที่ `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **เมธอดแบบ static** ที่เพิ่มลงในคลาส `BottomSheetModal` ของคุณใน `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

จากนั้นคุณสามารถแสดง modal จากที่ไหนก็ได้:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

หาก modal ที่มีชื่อเดียวกันมีอยู่แล้ว ให้ใช้แฟล็ก `--force` เพื่อเขียนทับ:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

แสดง modal โดยใช้ `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## สร้าง Modal

รูปแบบที่แนะนำคือการสร้างคลาส `BottomSheetModal` พร้อมเมธอดแบบ static สำหรับแต่ละประเภท modal โครงร่างพื้นฐานมีโครงสร้างนี้:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

เรียกใช้จากที่ไหนก็ได้:

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` เป็นเมธอดหลักสำหรับการแสดง modals

<div id="parameters"></div>

### พารามิเตอร์

| พารามิเตอร์ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | จำเป็น | build context สำหรับ modal |
| `child` | `Widget` | จำเป็น | widget เนื้อหาหลัก |
| `actionsRow` | `List<Widget>` | `[]` | widget การดำเนินการที่แสดงในแถวแนวนอน |
| `actionsColumn` | `List<Widget>` | `[]` | widget การดำเนินการที่แสดงในแนวตั้ง |
| `height` | `double?` | null | ความสูงคงที่สำหรับ modal |
| `header` | `Widget?` | null | widget ส่วนหัวด้านบน |
| `useSafeArea` | `bool` | `true` | ครอบเนื้อหาด้วย SafeArea |
| `isScrollControlled` | `bool` | `false` | อนุญาตให้ modal เลื่อนได้ |
| `showCloseButton` | `bool` | `false` | แสดงปุ่มปิด X |
| `headerPadding` | `EdgeInsets?` | null | ระยะห่างเมื่อมีส่วนหัว |
| `backgroundColor` | `Color?` | null | สีพื้นหลัง modal |
| `showHandle` | `bool` | `true` | แสดงที่จับลากด้านบน |
| `closeButtonColor` | `Color?` | null | สีพื้นหลังปุ่มปิด |
| `closeButtonIconColor` | `Color?` | null | สีไอคอนปุ่มปิด |
| `modalDecoration` | `BoxDecoration?` | null | การตกแต่งที่กำหนดเองสำหรับคอนเทนเนอร์ modal |
| `handleColor` | `Color?` | null | สีของที่จับลาก |

<div id="actions"></div>

### การดำเนินการ

การดำเนินการคือปุ่มที่แสดงด้านล่างของ modal

**การดำเนินการแบบแถว** จะอยู่เคียงข้างกัน แต่ละปุ่มใช้พื้นที่เท่ากัน:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**การดำเนินการแบบคอลัมน์** จะซ้อนกันในแนวตั้ง:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### ส่วนหัว

เพิ่มส่วนหัวที่อยู่เหนือเนื้อหาหลัก:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### ปุ่มปิด

แสดงปุ่มปิดที่มุมขวาบน:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### การตกแต่งที่กำหนดเอง

ปรับแต่งลักษณะของคอนเทนเนอร์ modal:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` กำหนดค่าลักษณะของ bottom sheet modals ที่ใช้โดยตัวเลือกฟอร์มและส่วนประกอบอื่นๆ:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| คุณสมบัติ | ประเภท | คำอธิบาย |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | สีพื้นหลังของ modal |
| `barrierColor` | `NyColor?` | สีของฉากหลังด้านหลัง modal |
| `useRootNavigator` | `bool` | ใช้ root navigator (ค่าเริ่มต้น: `false`) |
| `routeSettings` | `RouteSettings?` | การตั้งค่าเส้นทางสำหรับ modal |
| `titleStyle` | `TextStyle?` | สไตล์สำหรับข้อความชื่อเรื่อง |
| `itemStyle` | `TextStyle?` | สไตล์สำหรับข้อความรายการ |
| `clearButtonStyle` | `TextStyle?` | สไตล์สำหรับข้อความปุ่มล้าง |

<div id="examples"></div>

## ตัวอย่าง

### Modal ยืนยัน

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### Modal เนื้อหาที่เลื่อนได้

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### แผ่นการดำเนินการ

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
