# Alerts

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
- [สไตล์ที่มีให้ใช้งาน](#built-in-styles "สไตล์ที่มีให้ใช้งาน")
- [แสดงการแจ้งเตือนจากหน้าเพจ](#from-pages "แสดงการแจ้งเตือนจากหน้าเพจ")
- [แสดงการแจ้งเตือนจาก Controller](#from-controllers "แสดงการแจ้งเตือนจาก Controller")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [การกำหนดตำแหน่ง](#positioning "การกำหนดตำแหน่ง")
- [สไตล์ Toast แบบกำหนดเอง](#custom-styles "สไตล์ Toast แบบกำหนดเอง")
  - [การลงทะเบียนสไตล์](#registering-styles "การลงทะเบียนสไตล์")
  - [การสร้าง Style Factory](#creating-a-style-factory "การสร้าง Style Factory")
- [AlertTab](#alert-tab "AlertTab")
- [ตัวอย่าง](#examples "ตัวอย่างการใช้งานจริง")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มีระบบการแจ้งเตือนแบบ toast สำหรับแสดงการแจ้งเตือนให้ผู้ใช้ โดยมีสไตล์ที่มีให้ใช้งาน 4 แบบ ได้แก่ **success**, **warning**, **info** และ **danger** และรองรับสไตล์ที่กำหนดเองผ่านรูปแบบ registry

การแจ้งเตือนสามารถเรียกใช้ได้จากหน้าเพจ, controller หรือที่ใดก็ได้ที่คุณมี `BuildContext`

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

แสดงการแจ้งเตือน toast โดยใช้เมธอดลัดในหน้า `NyState` ใดก็ได้:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

หรือใช้ฟังก์ชันแบบ global พร้อม style ID:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## สไตล์ที่มีให้ใช้งาน

{{ config('app.name') }} ลงทะเบียนสไตล์ toast เริ่มต้น 4 แบบ:

| Style ID | ไอคอน | สี | ชื่อเริ่มต้น |
|----------|------|-------|---------------|
| `success` | เครื่องหมายถูก | เขียว | Success |
| `warning` | เครื่องหมายตกใจ | ส้ม | Warning |
| `info` | ไอคอนข้อมูล | เขียวน้ำเงิน | Info |
| `danger` | ไอคอนเตือน | แดง | Error |

สิ่งเหล่านี้ถูกกำหนดค่าใน `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## แสดงการแจ้งเตือนจากหน้าเพจ

ในหน้าเพจใดก็ได้ที่สืบทอดจาก `NyState` หรือ `NyBaseState` ใช้เมธอดลัดเหล่านี้:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### เมธอด Toast ทั่วไป

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## แสดงการแจ้งเตือนจาก Controller

Controller ที่สืบทอดจาก `NyController` มีเมธอดลัดเดียวกัน:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

เมธอดที่ใช้ได้: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`

<div id="show-toast-notification"></div>

## showToastNotification

ฟังก์ชัน global `showToastNotification()` แสดง toast จากที่ใดก็ได้ที่คุณมี `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### พารามิเตอร์

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | จำเป็น | Build context |
| `id` | `String` | `'success'` | ID ของสไตล์ toast |
| `title` | `String?` | null | แทนที่ชื่อเริ่มต้น |
| `description` | `String?` | null | ข้อความอธิบาย |
| `duration` | `Duration?` | null | ระยะเวลาที่ toast แสดง |
| `position` | `ToastNotificationPosition?` | null | ตำแหน่งบนหน้าจอ |
| `action` | `VoidCallback?` | null | callback เมื่อแตะ |
| `onDismiss` | `VoidCallback?` | null | callback เมื่อปิด |
| `onShow` | `VoidCallback?` | null | callback เมื่อแสดง |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` รวมข้อมูลทั้งหมดสำหรับการแจ้งเตือน toast:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### คุณสมบัติ

| คุณสมบัติ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | widget ไอคอน |
| `title` | `String` | `''` | ข้อความชื่อเรื่อง |
| `style` | `String` | `''` | ตัวระบุสไตล์ |
| `description` | `String` | `''` | ข้อความอธิบาย |
| `color` | `Color?` | null | สีพื้นหลังสำหรับส่วนไอคอน |
| `action` | `VoidCallback?` | null | callback เมื่อแตะ |
| `dismiss` | `VoidCallback?` | null | callback ปุ่มปิด |
| `onDismiss` | `VoidCallback?` | null | callback เมื่อปิดอัตโนมัติ/ด้วยตนเอง |
| `onShow` | `VoidCallback?` | null | callback เมื่อมองเห็น |
| `duration` | `Duration` | 5 วินาที | ระยะเวลาแสดง |
| `position` | `ToastNotificationPosition` | `top` | ตำแหน่งบนหน้าจอ |
| `metaData` | `Map<String, dynamic>?` | null | ข้อมูลเมตาแบบกำหนดเอง |

### copyWith

สร้างสำเนาที่แก้ไขของ `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## การกำหนดตำแหน่ง

ควบคุมตำแหน่งที่ toast แสดงบนหน้าจอ:

``` dart
// ด้านบนของหน้าจอ (ค่าเริ่มต้น)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// ด้านล่างของหน้าจอ
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// กลางหน้าจอ
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## สไตล์ Toast แบบกำหนดเอง

<div id="registering-styles"></div>

### การลงทะเบียนสไตล์

ลงทะเบียนสไตล์ที่กำหนดเองใน `AppProvider` ของคุณ:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

หรือเพิ่มสไตล์ได้ตลอดเวลา:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

จากนั้นใช้งาน:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### การสร้าง Style Factory

`ToastNotification.style()` สร้าง `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `icon` | `Widget` | widget ไอคอนสำหรับ toast |
| `color` | `Color` | สีพื้นหลังสำหรับส่วนไอคอน |
| `defaultTitle` | `String` | ชื่อที่แสดงเมื่อไม่ได้ระบุ |
| `position` | `ToastNotificationPosition?` | ตำแหน่งเริ่มต้น |
| `duration` | `Duration?` | ระยะเวลาเริ่มต้น |
| `builder` | `Widget Function(ToastMeta)?` | builder widget แบบกำหนดเองสำหรับการควบคุมทั้งหมด |

### Builder แบบกำหนดเองเต็มรูปแบบ

สำหรับการควบคุม toast widget อย่างสมบูรณ์:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` เป็น widget ป้ายกำกับสำหรับเพิ่มตัวบ่งชี้การแจ้งเตือนในแท็บนำทาง แสดงป้ายกำกับที่สามารถสลับเปิดปิดได้และบันทึกสถานะลงที่เก็บข้อมูลได้

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### พารามิเตอร์

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `state` | `String` | จำเป็น | ชื่อ state สำหรับติดตาม |
| `alertEnabled` | `bool?` | null | แสดงป้ายกำกับหรือไม่ |
| `rememberAlert` | `bool?` | `true` | บันทึกสถานะป้ายกำกับลงที่เก็บข้อมูล |
| `icon` | `Widget?` | null | ไอคอนแท็บ |
| `backgroundColor` | `Color?` | null | พื้นหลังแท็บ |
| `textColor` | `Color?` | null | สีข้อความป้ายกำกับ |
| `alertColor` | `Color?` | null | สีพื้นหลังป้ายกำกับ |
| `smallSize` | `double?` | null | ขนาดป้ายกำกับเล็ก |
| `largeSize` | `double?` | null | ขนาดป้ายกำกับใหญ่ |
| `textStyle` | `TextStyle?` | null | สไตล์ข้อความป้ายกำกับ |
| `padding` | `EdgeInsetsGeometry?` | null | padding ของป้ายกำกับ |
| `alignment` | `Alignment?` | null | การจัดตำแหน่งป้ายกำกับ |
| `offset` | `Offset?` | null | offset ของป้ายกำกับ |
| `isLabelVisible` | `bool?` | `true` | แสดงป้ายกำกับ |

### Factory Constructor

สร้างจาก `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## ตัวอย่าง

### การแจ้งเตือนสำเร็จหลังบันทึก

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Toast แบบโต้ตอบพร้อม Action

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### แจ้งเตือนตำแหน่งด้านล่าง

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
