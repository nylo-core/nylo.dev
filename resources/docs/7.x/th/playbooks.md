# Playbooks

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ Playbooks")
- [การสร้าง Playbook](#creating-a-playbook "การสร้าง playbook")
- [Steps](#steps "steps ที่ playbook ทำได้")
- [Targets](#targets "การค้นหาสิ่งต่างๆ บนหน้าจอ")
  - [สตริงธรรมดา](#plain-strings "วิธีจับคู่สตริงธรรมดา")
  - [Find](#find "target ที่แม่นยำด้วย Find")
- [ข้อมูลและการตั้งค่า](#data-and-setup "Data, setUp และ tearDown")
- [การเล่น Playbook อื่น](#playing-other-playbooks "การรัน playbook จาก playbook อื่น")
- [ความเร็ว](#speed "PlaybookSpeed")
- [นิ้วและคำบรรยาย](#finger-and-caption "นิ้วและคำบรรยายของ step")
- [การลงทะเบียน Playbook](#registering-playbooks "การลงทะเบียน playbook กับ Nylo")
- [การรัน Playbook](#running-a-playbook "การรัน playbook")
  - [จาก Metro](#running-from-metro "การรันจาก Metro")
  - [ตอนบูต](#running-on-boot "การรันตอนบูต")
  - [จาก Dart](#running-from-dart "การรันจาก Dart")
- [รายงาน](#reports "PlaybookReport")
- [Release Build](#release-builds "Playbook ใน release build")
- [การทดสอบ](#testing "การทดสอบ playbook")

<div id="introduction"></div>

## บทนำ

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**playbook** คือคลาสที่ระบุรายการการแตะ การพิมพ์ การเลื่อน และการเปลี่ยนหน้าของ user journey แอปที่กำลังทำงานของคุณจะทำสิ่งเหล่านั้นด้วยตัวเอง และสามารถแสดงนิ้วกับคำบรรยายสำหรับแต่ละ step ได้ -- สะดวกสำหรับการสาธิต การบันทึกหน้าจอ และการตรวจสอบ journey ตั้งแต่ต้นจนจบ

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

import `package:nylo_framework/playbook.dart` ทุกที่ที่คุณเขียนหรือลงทะเบียน playbook

<div id="creating-a-playbook"></div>

## การสร้าง Playbook

ใช้ Metro เพื่อสร้าง:

``` bash
metro make:playbook sign_up
```

คำสั่งนี้จะสร้าง `lib/app/playbooks/sign_up_playbook.dart` และลงทะเบียนกับแอปของคุณ ดู [Make Playbook](/docs/{{ $version }}/metro#make-playbook) สำหรับตัวเลือกของคำสั่ง

<div id="steps"></div>

## Steps

`steps` ของ playbook สร้างขึ้นด้วย verb ที่สืบทอดมา:

| Step | คำอธิบาย |
|------|-------------|
| `tap(target)` | แตะ target |
| `longPress(target)` | กด target ค้างไว้ |
| `type(target, text)` | พิมพ์ลงใน field โดยจะล้างค่าก่อน เว้นแต่ใช้ `clear: false`; `submit: true` จะกดปุ่ม action ของคีย์บอร์ด; `secret: true` จะซ่อนข้อความในคำบรรยายและรายงาน |
| `scrollTo(target)` | เลื่อนจนกว่า target จะอยู่บนหน้าจอ ส่ง `within:` เพื่อเลื่อน scrollable ที่ระบุ |
| `see(target)` | ตรวจสอบว่า target อยู่บนหน้าจอ โดยรอให้ปรากฏ |
| `dontSee(target)` | ตรวจสอบว่า target ไม่อยู่บนหน้าจอ โดยรอให้หายไป |
| `waitFor(target)` | รอ target ได้นานสุดตาม `timeout` (ค่าเริ่มต้น 10 วินาที) |
| `waitForPage(path)` | รอหน้าที่ตรงกับ route path หรือ `RouteView` (เช่น `HomePage.path`) |
| `visit(path)` | เปิดหน้าด้วย route path หรือ `RouteView` พร้อม `data:` เป็นตัวเลือก |
| `back()` | ย้อนกลับหนึ่งหน้า |
| `pause(seconds: 1)` | หยุดนิ่ง เช่น เพื่อให้การสาธิตได้พักหายใจ |
| `step(title, action)` | รันโค้ดใดก็ได้ด้วย verb เดียวกัน ผ่าน `PlaybookActor` |
| `scene(title, steps)` | จัดกลุ่ม step ภายใต้ชื่อเรื่อง คำบรรยายของ step เหล่านั้นจะขึ้นต้นด้วยชื่อนี้ และผลลัพธ์จะซ้อนอยู่ในรายงาน |
| `play(playbook)` | รัน playbook อื่น -- ดู [การเล่น Playbook อื่น](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

steps รันตามลำดับและหยุดที่ความล้มเหลวแรก step ที่เหลือจะถูกรายงานว่าถูกข้าม

<div id="targets"></div>

## Targets

ส่วนใหญ่ step จะรับ target: สิ่งที่จะแตะ พิมพ์ลงไป หรือมองหา

<div id="plain-strings"></div>

### สตริงธรรมดา

สตริงธรรมดาจะถูกจับคู่เหมือนที่คนอ่านหน้าจอ โดยจะลองตามลำดับนี้:

1. ข้อความที่มองเห็น
2. hint หรือ label ของ field
3. tooltip
4. semantics label
5. `ValueKey`
6. ข้อความที่มีสตริงนั้นอยู่

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

สามารถส่ง `Key`, `Type` หรือ `IconData` ให้ step ได้โดยตรงเช่นกัน

<div id="find"></div>

### Find

ใช้ `Find` เมื่อสตริงธรรมดาไม่แม่นยำพอ:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | จับคู่กับ |
|--------|---------|
| `Find.text(text)` | ข้อความที่มองเห็น |
| `Find.textContaining(text)` | ข้อความที่มีสตริงนั้นอยู่ |
| `Find.icon(icon)` | ไอคอน |
| `Find.type<T>()` | widget ชนิด `T` |
| `Find.key(key)` | widget ที่มี key นั้น |
| `Find.field(name)` | field ของ `NyForm` ตามชื่อ |
| `Find.input(text)` | text field ตาม hint, label หรือ placeholder |
| `Find.tooltip(message)` | tooltip |
| `Find.semantics(label)` | semantics label |
| `Find.where(test)` | widget ที่ผ่านการทดสอบของคุณ |

จำกัด finder ใดก็ได้ให้แคบลงด้วย `.inside(ancestor)`, `.at(index)` (นับจาก 0), `.first` หรือ `.last`

การแตะจะถูกปฏิเสธเมื่อมีสิ่งที่ไม่เกี่ยวข้อง เช่น barrier ของ dialog บัง target อยู่

<div id="data-and-setup"></div>

## ข้อมูลและการตั้งค่า

`defaults` เก็บข้อมูลที่ steps ใช้เริ่มต้น อ่านผ่าน `data` ภายใน `steps` `setUp` รันก่อน steps และ `tearDown` รันหลัง steps แม้ว่า step จะล้มเหลวก็ตาม:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` รัน [Seeders](/docs/{{ $version }}/metro-live#seeders) (instance หรือชื่อที่ลงทะเบียนไว้) และบันทึกเหมือน `seed` ใน `metro live`

<div id="playing-other-playbooks"></div>

## การเล่น Playbook อื่น

`play()` รัน playbook อื่นทั้งหมด พร้อม `setUp` และ `tearDown` ของมันเอง ส่ง instance หรือชื่อที่ลงทะเบียนไว้:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Data** ผสานตามลำดับนี้: `defaults` ของลูก, จากนั้น `data` ของพ่อแม่, แล้วจึง `data:` ที่ส่งให้ `play`
- **Speed** สืบทอดจากพ่อแม่ เว้นแต่คุณส่ง `speed:`
- **ผลลัพธ์** จะซ้อนอยู่ใต้ step ในรายงาน
- **ลูกที่ล้มเหลว** ทำให้ step ล้มเหลว
- **วงจร** จะถูกปฏิเสธ และ playbook ซ้อนกันได้ลึกสุด 8 ชั้น

<div id="speed"></div>

## ความเร็ว

`PlaybookSpeed` กำหนดจังหวะของทุกการรอ:

| ความเร็ว | คำอธิบาย |
|-------|-------------|
| `PlaybookSpeed.instant` | ไม่มีการรอนอกจากที่การเลื่อนต้องใช้ ออกแบบมาสำหรับเทสต์ |
| `PlaybookSpeed.fast` | เร็ว แต่ยังตามดูได้ง่าย |
| `PlaybookSpeed.natural` | จังหวะของคนที่ใช้แอป เป็นค่าเริ่มต้น |
| `PlaybookSpeed.slow` | ช้าพอที่จะบรรยายประกอบได้ |

ปรับสเกลความเร็วใดก็ได้ด้วย `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // ช้าลงสองเท่า
```

ความเร็วที่ส่งให้ `run()`, `--speed` จาก Metro หรือความเร็วของ playbook ที่เล่น playbook นี้ จะมีผลเหนือความเร็วของ playbook เอง

<div id="finger-and-caption"></div>

## นิ้วและคำบรรยาย

นิ้วและคำบรรยายของ step ถูกปิดไว้โดยค่าเริ่มต้น override `showFinger` และ `caption` เพื่อเปิดใช้งาน:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // หรือ .top
```

`showFinger` มีค่าเริ่มต้นเป็น `false` และ `caption` เป็น `PlaybookCaption.hidden`; `PlaybookCaption` คือ `top`, `bottom` หรือ `hidden` การแตะและการปัดเกิดขึ้นไม่ว่าจะเปิดหรือไม่ playbook ที่ถูกเล่นโดย playbook นี้ด้วย `play()` จะใช้การตั้งค่าของตัวเอง และการตั้งค่าของ playbook นี้จะกลับมาเมื่อเล่นจบ หากต้องการซ่อนทั้งสองอย่างสำหรับการรันครั้งหนึ่ง ให้ใช้ `--no-overlay` จาก Metro หรือ `run(overlay: false)` จาก Dart

ก่อน nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0) นิ้วและคำบรรยายด้านล่างเปิดอยู่โดยค่าเริ่มต้น เพิ่ม override ด้านบนเพื่อให้ได้ลักษณะเดิม

<div id="registering-playbooks"></div>

## การลงทะเบียน Playbook

ลงทะเบียน playbook ของคุณเพื่อให้ Metro และ `play('name')` หาเจอ:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` คืนค่าสิ่งที่ลงทะเบียนไว้ คุณยังส่งให้ได้ขณะตั้งค่า Nylo ใน `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` ทำสิ่งนี้ให้คุณ โดยเก็บ registry ไว้ใน `lib/bootstrap/playbooks.dart` เพิ่ม playbook ใหม่แต่ละตัวเข้าไป และส่ง `playbooks` ให้ `nylo.configure(...)` registry เป็น map ว่างใน release build:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

playbook ที่ลงทะเบียนไว้จะรันภายใต้ key ของมัน ส่วนที่ไม่ได้ลงทะเบียนจะรันภายใต้ `name` ของมัน หรือชื่อคลาสในรูป snake case (`SignUpPlaybook` คือ `sign_up`)

<div id="running-a-playbook"></div>

## การรัน Playbook

<div id="running-from-metro"></div>

### จาก Metro

เมื่อแอปของคุณรันในโหมด debug:

``` bash
metro live:run playbook sign_up --speed fast
```

ภายใน `metro live` ให้พิมพ์ `playbook sign_up` ดู [Metro Live](/docs/{{ $version }}/metro-live#playbooks) สำหรับทุกตัวเลือก เพิ่ม `--record` เพื่อบันทึกวิดีโอของการรัน ดู [การบันทึก](/docs/{{ $version }}/metro-live#recording-playbooks)

<div id="running-on-boot"></div>

### ตอนบูต

เพิ่ม key เหล่านี้ลงในไฟล์ `.env` เพื่อเล่น playbook ทันทีที่แอปปรากฏบนหน้าจอ:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` คือรายการชื่อ playbook คั่นด้วยจุลภาค และ `PLAYBOOK_SPEED` เป็นหนึ่งใน `instant`, `fast`, `natural` หรือ `slow` ทั้งสองมีผลเฉพาะใน debug และ profile build เท่านั้น

<div id="running-from-dart"></div>

### จาก Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` รับ `speed`, `data`, `clock`, `overlay` (ค่าเริ่มต้น `true`) และ `onEvent` step ที่ล้มเหลวไม่ throw -- ให้ตรวจสอบ `report.passed` รันได้ทีละ playbook เท่านั้น ใช้ `play()` เพื่อรันตัวหนึ่งภายในอีกตัว

หยุด playbook ที่กำลังรันด้วย `Playbook.stop()` step ที่เหลือจะถูกข้าม แต่ `tearDown` ยังคงรัน `Playbook.isRunning` บอกว่ามี playbook กำลังรันอยู่หรือไม่

<div id="reports"></div>

## รายงาน

`run()` คืนค่า `PlaybookReport` ที่มี `name`, `speed`, เวลา `elapsed` ของ playbook, ค่าว่าถูก `stopped` หรือไม่ และรายการ `steps` ของ `PlaybookStepResult` (แต่ละตัวมี `title`, `status`, `elapsed`, `error` และ `steps` ที่ซ้อนอยู่) `report.passed` เป็น true เมื่อทุก step ผ่าน และการพิมพ์ report จะอ่านได้เหมือนผลลัพธ์ของ Metro `report.toJson()` ให้ข้อมูลเดียวกันในรูป JSON

<div id="release-builds"></div>

## Release Build

Playbook จะไม่รันใน release build เพื่อไม่ให้ journey ควบคุมแอปของผู้ใช้ได้:

- `Playbook.run()` จะ throw `UnsupportedError`
- `addPlaybooks` จะถูกเพิกเฉย
- registry `lib/bootstrap/playbooks.dart` ที่ Metro สร้างให้จะว่างเปล่า

`Playbook.isAvailable` บอกว่า build ปัจจุบันรัน playbook ได้หรือไม่

<div id="testing"></div>

## การทดสอบ

รัน playbook ใน widget test ด้วย `NyTesterClock` จากนั้น assert รายงานของมันด้วย `expectPlaybookPassed`:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

ดู [การทดสอบ Playbook](/docs/{{ $version }}/testing#testing-playbooks) สำหรับ integration test
