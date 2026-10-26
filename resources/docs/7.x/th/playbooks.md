# Playbooks

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ Playbooks")
- [การสร้าง Playbook](#creating-a-playbook "การสร้าง playbook")
- [การบันทึก Playbook](#recording-a-playbook "การบันทึก playbook จากแอปที่กำลังทำงาน")
- [Steps](#steps "steps ที่ playbook ทำได้")
- [Targets](#targets "การค้นหาสิ่งต่างๆ บนหน้าจอ")
  - [สตริงธรรมดา](#plain-strings "วิธีจับคู่สตริงธรรมดา")
  - [Find](#find "target ที่แม่นยำด้วย Find")
- [ข้อมูลและการตั้งค่า](#data-and-setup "Data, setUp และ tearDown")
- [การเล่น Playbook อื่น](#playing-other-playbooks "การรัน playbook จาก playbook อื่น")
- [ความเร็ว](#speed "PlaybookSpeed")
- [นิ้วและคำบรรยาย](#finger-and-caption "นิ้วและคำบรรยายของ step")
- [Screenshots](#screenshots "การถ่ายภาพหน้าจอระหว่าง playbook")
  - [Locale และธีม](#screenshots-locales-and-themes "การถ่ายภาพหน้าจอในทุก locale และธีม")
  - [การรวบรวมจากโค้ด](#collecting-screenshots "การรวบรวมภาพหน้าจอด้วย onScreenshot")
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

หากต้องการเริ่มจากทุกหน้าที่ router ของคุณเพิ่มไว้ ให้ส่ง `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

Metro เขียน `visit()` และ [`screenshot()`](#screenshots) ให้แต่ละหน้า การรันครั้งเดียวจึงถ่ายภาพทั้งแอปของคุณ

<div id="recording-a-playbook"></div>

## การบันทึก Playbook

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

แทนที่จะเขียน step ให้บันทึกมัน เมื่อแอปของคุณรันในโหมด debug ให้เปิด `metro live` แล้วพิมพ์:

``` plaintext
› record sign_up
```

จากนั้นใช้งานแอป การแตะ การกดค้าง ฟิลด์ที่พิมพ์ การเลื่อน และการเปลี่ยนหน้าแต่ละครั้งจะถูกเขียนเป็น step ทันทีที่เกิดขึ้น และพิมพ์ให้เห็นระหว่างทาง:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

กด Enter เพื่อจบ playbook จะถูกเขียนไปที่ `lib/app/playbooks/sign_up_playbook.dart` โดยจัดรูปแบบเหมือนที่ `make:playbook` สร้าง และลงทะเบียนแล้ว จึงเล่นได้ทันทีที่แอปรีสตาร์ต `metro make:playbook sign_up --record` ทำสิ่งเดียวกันจากเทอร์มินัล: เปิด `metro live` และเริ่มบันทึกทันที

ขณะบันทึก ให้พิมพ์ปุ่มแล้วกด Enter:

| ปุ่ม | คำอธิบาย |
|-----|-------------|
| `a` | การแตะครั้งถัดไปจะถูกเขียนเป็น `see(...)` แทนการแตะ |
| `d` | การแตะครั้งถัดไปจะถูกเขียนเป็น `dontSee(...)` |
| `s <title>` | เริ่ม `scene` ด้วยชื่อนั้น step ที่ตามมาจะซ้อนอยู่ภายใน |
| `u` | ลบ step ล่าสุด |
| `p` | หยุดการบันทึกชั่วคราว เช่น เพื่อลงชื่อเข้าใช้ด้วยข้อมูลรับรองจริง กด `p` อีกครั้งเพื่อทำต่อ |
| Enter | จบและเขียน playbook |
| Ctrl+C | ทิ้งการบันทึก |

target จะถูกตั้งชื่อตามที่ผู้เล่นอ่าน โดยเริ่มจากข้อความที่มองเห็น และแต่ละชื่อจะถูกตรวจสอบกับแอปที่กำลังทำงานก่อนเขียน เพื่อให้ step ที่บันทึกเล่นซ้ำได้ เมื่อสตริงธรรมดาตรงกับมากกว่าหนึ่ง widget step จะจำกัดให้แคบลงด้วย `Find`: `tap(Find.text('Add').at(1))` ปุ่มไอคอนที่ไม่มีข้อความหรือ tooltip จะกลายเป็น `tap(Find.icon(Icons.add))` widget ที่ไม่มีอะไรตั้งชื่อให้จะถูกเขียนเป็น `tap(Find.type<GestureDetector>().at(3))` ภายใต้คอมเมนต์ `// TODO` พร้อมไฟล์และบรรทัดที่สร้างมัน และ Metro จะแสดงรายการเมื่อการบันทึกจบ ให้ `Key` กับมัน แล้วใช้ key แทน

- **การพิมพ์** กลายเป็น step `type` ที่อ่าน placeholder `data[...]` โดยมีค่าที่พิมพ์ไว้ใน `defaults` ฟิลด์ที่ปล่อยไว้กับปุ่ม action ของคีย์บอร์ดจะได้ `submit: true` ฟิลด์ที่ซ่อนข้อความจะได้ `secret: true` โดยมีค่าอยู่ใน `defaults` และมีคำเตือนให้เปลี่ยนก่อน commit ส่ง `--no-data` เพื่อเขียนค่าตามที่พิมพ์จริง
- **การเปลี่ยนหน้า** หลังการแตะจะกลายเป็น `waitForPage(SignUpPage.path)` เมื่อหนึ่งในหน้าของคุณประกาศ route นั้น หรือ `waitForPage('/sign-up')` ในกรณีอื่น การย้อนกลับจะกลายเป็น `back()`
- **การเลื่อน** ถูกเขียนเป็น `scrollTo(...)` บนสิ่งที่คุณแตะถัดไป และเฉพาะเมื่อการเลื่อนทำให้มันเข้ามาอยู่ใน tree เท่านั้น ผู้เล่นจะเปิดเผยทุกอย่างที่เหลือเอง
- **การปัดที่ไม่ใช่การเลื่อน** -- `Dismissible`, `PageView`, `Slider` -- ยังไม่มีคำกริยาของ playbook จึงถูกทิ้งไว้เป็นคอมเมนต์ `// TODO` พร้อมจุดเริ่มต้นและจุดสิ้นสุด

การบันทึกต้องใช้ debug build และทำงานบน simulator, emulator และอุปกรณ์จริงเหมือนกัน การสัมผัสของ playbook เองจะไม่ถูกบันทึก ดู [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) สำหรับตัวเลือกของคำสั่ง

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
| `screenshot(name)` | ถ่ายภาพหน้าจอ -- ดู [Screenshots](#screenshots) |
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
| `Find.icon(icon, label:)` | ไอคอน `label:` ตั้งชื่อให้ในคำบรรยายของ step |
| `Find.type<T>()` | widget ชนิด `T` |
| `Find.key(key)` | widget ที่มี key นั้น |
| `Find.field(name)` | field ของ `NyForm` ตามชื่อ |
| `Find.input(text)` | text field ตาม hint, label หรือ placeholder |
| `Find.tooltip(message)` | tooltip |
| `Find.semantics(label)` | semantics label |
| `Find.where(test)` | widget ที่ผ่านการทดสอบของคุณ |

ให้ `label` กับไอคอน แล้วคำบรรยายของ step จะอ่านว่า `Tap the bag icon` แทน `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

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

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

เพิ่ม step `screenshot` ในจุดที่หน้าจอควรค่าแก่การเก็บไว้:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` บันทึกแต่ละภาพเป็น `screenshots/<playbook>/<name>.png` โดยไม่ต้องใช้ flag การรันจากโค้ดจะรวบรวมภาพด้วย [`onScreenshot`](#collecting-screenshots) และการรันแบบอื่น เช่น `PLAYBOOK_ON_BOOT` จะเพียงรอให้หน้าจอนิ่ง ชื่อต้องขึ้นต้นด้วยตัวอักษรหรือตัวเลข และใช้ตัวอักษร ตัวเลข `-`, `_` และ `.`

| พารามิเตอร์ | คำอธิบาย |
|-----------|-------------|
| `name` | ชื่อที่ใช้บันทึกภาพหน้าจอ |
| `locales` | ถ่ายหนึ่งครั้งในแต่ละรหัสภาษาเหล่านี้ `Screenshot.allLocales` คือทุกภาษาที่แอปรวมมา |
| `themes` | ถ่ายหนึ่งครั้งในแต่ละ id ธีมเหล่านี้ `Screenshot.allThemes` คือทุกธีมที่ลงทะเบียนไว้ |
| `caption` | ข้อความเหนือหน้าจอเมื่อการรันใส่กรอบให้ภาพหน้าจอ หรือ translation key |
| `native` | ถ่ายด้วย Metro บน iOS Simulator และ Android (ค่าเริ่มต้น `true`) |

บน iOS Simulator และ Android Metro จับภาพทั้งหน้าจอของอุปกรณ์ จึงมี status bar และคีย์บอร์ดอยู่ในภาพ และนิ้วกับคำบรรยายจะถูกซ่อนระหว่างนั้น เพิ่ม `--clean-status-bar` ให้การรันเพื่อให้ได้ 9:41 พร้อมสัญญาณและแบตเตอรี่เต็ม ในที่อื่น และในการรันแบบ `--store` และ `--frame` แอปจะจับภาพหน้าจอของตัวเอง ส่ง `native: false` เพื่อใช้การจับภาพของแอปเสมอ:

``` dart
screenshot('checkout', native: false),
```

หากต้องการบันทึกหน้าจอของทุก step แทน ให้รัน `metro live:run playbook <name> --screenshots` ดู [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) สำหรับตัวเลือกที่ปรับขนาดภาพหน้าจอสำหรับ App Store และ Google Play และ [Screenshots](/docs/{{ $version }}/screenshots) สำหรับคลาส `Screenshot` ที่อยู่เบื้องหลัง

<div id="screenshots-locales-and-themes"></div>

### Locale และธีม

ให้ step `screenshot` มี `locales`, `themes` หรือทั้งสองอย่าง เพื่อถ่ายหน้าจอเดียวกันหนึ่งครั้งในแต่ละรายการ:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

แอปสลับในที่เดิม โดยคงหน้าและ state ไว้ และสลับกลับก่อน step ถัดไป แต่ละภาพจะถูกบันทึกในโฟลเดอร์ที่ตั้งชื่อตาม locale และธีมของมัน:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

หากต้องการเล่นทั้ง playbook ในทุกภาษาและทุกธีมแทน ให้ส่ง `--locales` และ `--themes` ให้ `metro live:run playbook`

<div id="collecting-screenshots"></div>

### การรวบรวมจากโค้ด

`run()` รับ `onScreenshot` เพื่อรับภาพหน้าจอของการรันในรูป `PlaybookShot` ซึ่งสะดวกสำหรับเทสต์หรือเครื่องมือของคุณเอง:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` เลือกว่าจะจับภาพหน้าจอใด:

| โหมด | จับภาพ |
|------|----------|
| `PlaybookScreenshots.off` | ไม่มีอะไร |
| `PlaybookScreenshots.failures` | เฉพาะหน้าจอที่ step ที่ล้มเหลว |
| `PlaybookScreenshots.marked` | step `screenshot()` และ step ที่ล้มเหลว |
| `PlaybookScreenshots.auto` | step `screenshot()` ของ playbook ที่มี และทุก step ของ playbook ที่ไม่มี รวมถึง step ที่ล้มเหลวด้วย ค่าเริ่มต้น |
| `PlaybookScreenshots.all` | ทุก step, step `screenshot()` และ step ที่ล้มเหลว |

`pixelRatio` กำหนดขนาดภาพหน้าจอเทียบกับของอุปกรณ์ (ค่าเริ่มต้น `1.0`) แต่ละ `PlaybookShot` มี `screenshot` ให้[บันทึก](/docs/{{ $version }}/screenshots#saving-a-screenshot)หรืออ่าน `bytes` จาก `kind` และ `name`, `locale` และ `theme` ที่ใช้ถ่าย

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

ภายใน `metro live` ให้พิมพ์ `playbook sign_up` ดู [Metro Live](/docs/{{ $version }}/metro-live#playbooks) สำหรับทุกตัวเลือก เพิ่ม `--record` เพื่อบันทึกวิดีโอของการรัน ดู [การบันทึก](/docs/{{ $version }}/metro-live#recording-playbooks) หากการรันหยุดรอหลังจาก 20 วินาที แอปน่าจะเริ่มด้วย `flutter run --no-dds` ดู [Metro Live](/docs/{{ $version }}/metro-live#playbooks)

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

`run()` รับ `speed`, `data`, `clock`, `overlay` (ค่าเริ่มต้น `true`), `onEvent` และ `onScreenshot`, `screenshots` และ `pixelRatio` สำหรับ[การรวบรวมภาพหน้าจอ](#collecting-screenshots) step ที่ล้มเหลวไม่ throw -- ให้ตรวจสอบ `report.passed` รันได้ทีละ playbook เท่านั้น ใช้ `play()` เพื่อรันตัวหนึ่งภายในอีกตัว

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
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

ดู [การทดสอบ Playbook](/docs/{{ $version }}/testing#testing-playbooks) สำหรับ integration test
