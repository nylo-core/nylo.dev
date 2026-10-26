# Screenshots

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ Screenshots")
- [การจับภาพหน้าจอ](#capturing-the-screen "การจับภาพทั้งหน้าจอ")
- [การจับภาพพื้นที่](#capturing-an-area "การจับภาพส่วนหนึ่งของหน้าจอด้วย ScreenshotArea")
- [การบันทึกภาพหน้าจอ](#saving-a-screenshot "การบันทึกภาพหน้าจอลง disk")
- [หน้ารายการใน Store](#store-listings "การปรับขนาดภาพหน้าจอสำหรับ App Store และ Google Play")
  - [ขนาดของ Store](#store-sizes "ขนาด ScreenshotStore")
- [การซ่อนเนื้อหา](#hiding-content "การซ่อนเนื้อหาจากภาพหน้าจอ")
- [การจับภาพ Widget](#capturing-a-widget "การจับภาพ widget โดยไม่แสดงมัน")
- [Locale และธีม](#locales-and-themes "การจับภาพใน locale หรือธีมอื่น")
- [จาก Metro](#from-metro "การถ่ายภาพหน้าจอจาก Metro")

<div id="introduction"></div>

## บทนำ

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` ถ่าย PNG ของหน้าจอแอปของคุณ หรือของส่วนหนึ่งของหน้าจอ:

``` dart
// The whole screen, dialogs and sheets included
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// One part of the screen
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

การจับภาพทำงานได้ในทุกโหมดการบิลด์ จึงใช้เป็นพื้นฐานของฟีเจอร์ในแอปของคุณได้ เช่น การแชร์ใบเสร็จหรือการแนบหน้าจอไปกับรายงานบั๊ก [Metro](#from-metro) และ [playbook](/docs/{{ $version }}/playbooks#screenshots) ใช้โค้ดเดียวกัน

`Screenshot` เก็บ PNG เป็น `bytes` พร้อม `width` และ `height` เป็นพิกเซลจริง

<div id="capturing-the-screen"></div>

## การจับภาพหน้าจอ

`Screenshot.capture()` อ่านทั้งหน้าจอของแอปตามที่ผู้ใช้เห็น:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| พารามิเตอร์ | คำอธิบาย |
|-----------|-------------|
| `pixelRatio` | ขนาดเทียบกับ logical pixel ค่าเริ่มต้นเป็นของอุปกรณ์ ดังนั้น PNG จึงมีความละเอียดเดิมของหน้าจอ |
| `settle` | รอเฟรมถัดไปก่อนจับภาพ (ค่าเริ่มต้น `true`) ส่ง `false` เมื่อเพิ่งวาดเฟรมเสร็จ |
| `locale` | แสดงหน้าจอในภาษานี้ระหว่างการจับภาพ เช่น `'es'` |
| `theme` | แสดงหน้าจอใน id ธีมนี้ระหว่างการจับภาพ เช่น `'dark'` |

แอปที่สร้างด้วย `NyApp.materialApp` จะจับภาพเฉพาะหน้าของมันอย่างแม่นยำ หากคุณสร้าง `MaterialApp` เอง ให้เพิ่ม `ScreenshotRoot` ใน `builder` ของมัน:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## การจับภาพพื้นที่

ห่อส่วนหนึ่งของหน้าจอด้วย `ScreenshotArea` และตั้งชื่อให้:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

จากนั้นจับภาพด้วยชื่อนั้น:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` รับพารามิเตอร์ `pixelRatio`, `settle`, `locale` และ `theme` เหมือนกับ `capture` `Screenshot.areas` แสดงรายการชื่อของพื้นที่ที่อยู่บนหน้าจอ

<div id="saving-a-screenshot"></div>

## การบันทึกภาพหน้าจอ

`store` บันทึก PNG ลงใน disk ของ file storage และคืนค่า path:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

หากไม่ระบุ `disk` จะบันทึกลง disk เริ่มต้น หากต้องการแชร์หรืออัปโหลดรูปภาพ ให้อ่าน `shot.bytes`

<div id="store-listings"></div>

## หน้ารายการใน Store

`forStore` คืนค่าภาพหน้าจอใหม่ในขนาดที่ตรงกับที่ app store ต้องการ:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

หน้าจอที่มีรูปทรงใกล้เคียงกันจะเติมเต็มขนาดนั้น และหน้าจออื่นจะถูกปรับให้พอดีภายใน เมื่อใช้ `frame: true` หน้าจอจะถูกวาดในกรอบโทรศัพท์ใต้ `caption`

| พารามิเตอร์ | คำอธิบาย |
|-----------|-------------|
| `frame` | วาดหน้าจอภายในกรอบโทรศัพท์ |
| `caption` | ข้อความเหนือโทรศัพท์ ใช้ร่วมกับ `frame` |
| `background` | `Color` รอบหน้าจอ |

ใช้ `framed()` เพื่อวาดกรอบในขนาดของภาพหน้าจอเอง:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### ขนาดของ Store

| ขนาด | Id | พิกเซล |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` แสดงรายการทุกขนาด และ `ScreenshotStore.named('ios-6.9')` ค้นหาขนาดหนึ่งด้วย id ของมัน

<div id="hiding-content"></div>

## การซ่อนเนื้อหา

ห่อสิ่งที่เป็นส่วนตัวด้วย `ScreenshotHidden` มันแสดงบนหน้าจอตามปกติ และบล็อกสีเทาจะปิดทับมันในทุกภาพหน้าจอ:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

ส่ง `replacement` เพื่อวาด placeholder ของคุณเองแทนบล็อกสีเทา

<div id="capturing-a-widget"></div>

## การจับภาพ Widget

`Screenshot.widget` จับภาพ widget โดยไม่แสดงมัน เช่น การ์ดแชร์ที่สร้างขึ้นเพื่อรูปภาพโดยเฉพาะ:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG มีขนาดเท่ากับ widget ดังนั้นให้ห่อด้วย `SizedBox` เพื่อกำหนดขนาดที่แน่นอน ส่ง `context` เพื่อให้ widget ใช้ธีมและ localization ของ context นั้น `size` เพื่อกำหนดพื้นที่ที่ใช้จัดวาง และ `delay` เพื่อให้รูปภาพมีเวลาโหลด

<div id="locales-and-themes"></div>

## Locale และธีม

ส่ง `locale` หรือ `theme` เพื่อแสดงหน้าจอในอีกแบบหนึ่งสำหรับการจับภาพหนึ่งครั้ง:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

แอปสลับในที่เดิม โดยคงหน้า state และ navigation stack ไว้ และสลับกลับทันทีหลังจากนั้น ไม่มีอะไรถูกบันทึก ตัวเลือกของผู้ใช้เองจึงยังคงอยู่ locale ต้องมีไฟล์ภาษาใน assets ของคุณ และธีมต้องมี id ธีมที่ลงทะเบียนไว้

<div id="from-metro"></div>

## จาก Metro

บันทึกภาพหน้าจอของแอปที่กำลังทำงานของคุณจากเทอร์มินัล:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

ดู [Metro Live](/docs/{{ $version }}/metro-live#screenshots) สำหรับทุกตัวเลือก [playbook](/docs/{{ $version }}/playbooks#screenshots) สามารถถ่ายภาพหน้าจอของ journey ทั้งหมดในทุกภาษาและทุกธีมได้
