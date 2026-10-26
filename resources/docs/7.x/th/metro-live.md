# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ Nylo Live")
- Shell ของ metro live
  - [ตัวเลือกที่ใช้ร่วมกัน](#shared-options "ตัวเลือกที่คำสั่ง live ทุกตัวรองรับ")
  - [Tab Completion และประวัติ](#tab-completion-and-history "Tab completion และประวัติ")
  - [การรันสคริปต์](#running-a-script "การรันสคริปต์ .live")
- [live:devices](#live-devices "แสดงรายการแอปที่กำลังทำงาน")
- [live:status](#live-status "แสดงสถานะแอป")
- [live:run](#live-run "รันคำสั่ง live ในตัว")
- [Playbook](#playbooks "การเล่น playbook จาก Metro")
- [การบันทึก Journey](#recording-a-journey "การบันทึกสิ่งที่คุณทำเป็น playbook")
- [Screenshots](#screenshots "การบันทึกภาพหน้าจอของแอปที่กำลังทำงาน")
- คำสั่ง Live
  - [การสร้างคำสั่ง Live](#creating-a-live-command "การสร้างคำสั่ง live")
  - [การรันคำสั่ง Live](#running-a-live-command "การรันคำสั่ง live")
- Seeders
  - [การสร้าง Seeder](#creating-a-seeder "การสร้าง seeder")
  - [Seeding และ Rolling Back](#seeding-and-rolling-back "การ seed และ roll back")
- Storage Snapshots
  - [การ Export Snapshot](#exporting-a-snapshot "การ export snapshot")
  - [การโหลด Snapshot](#loading-a-snapshot "การโหลด snapshot")
- [Page Actions](#page-actions "การเรียกใช้หน้าจากภายนอก")

<div id="introduction"></div>

## บทนำ

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** ช่วยให้ Metro เข้าถึงแอปของคุณได้ในขณะที่มันกำลังทำงานในโหมด debug (หรือ profile) ผ่าน Dart VM service มันค้นหาแอปผ่าน Dart Tooling Daemon โดยไม่ต้องตั้งค่าใดๆ และจะเชื่อมต่อเฉพาะกับแอปที่ build จากโปรเจกต์ปัจจุบันเท่านั้น

Nylo Live เปิดใช้งานโดยค่าเริ่มต้นใน debug และ profile build; release build จะไม่ลงทะเบียนอะไรเลย คำสั่งที่อ่านข้อมูลแอปเท่านั้น (`live:status`, `live:run data`, `live:run routes`, ...) ทำงานได้ใน profile build ด้วย คำสั่งที่เปลี่ยนแปลงแอป (`route`, `storage`, `auth`, seeders, ...) ต้องใช้ debug build

ปิดใช้งานเองได้ ก่อนที่สิ่งอื่นใดจะทำงาน จาก provider:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## Shell ของ metro live

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

เปิด shell ที่เชื่อมต่อกับแอปที่กำลังทำงานของคุณ:

``` bash
metro live
```

Nylo Live จะค้นหาแอปที่กำลังทำงานของโปรเจกต์และเชื่อมต่อกับแอปหนึ่ง -- มันจะถามให้คุณเลือกเมื่อมีมากกว่าหนึ่งแอปทำงานอยู่ เมื่อเชื่อมต่อแล้ว prompt จะแสดง device และ route ที่อยู่บนหน้าจอปัจจุบัน:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

คำสั่ง live จะตัดคำนำหน้า `metro live:` ออกภายใน shell -- ใช้ `status` แทน `metro live:status`, `route /profile` แทน `metro live:run route /profile` มีเพียงไม่กี่คำสั่งที่รันได้เฉพาะภายใน shell เท่านั้น: `seed`, `seed:rollback`, `export`, `record`, `reload` และ `restart` พิมพ์ `help` เพื่อแสดงรายการคำสั่งทั้งหมด หรือ `help <command>` เพื่อดูตัวเลือกของคำสั่งใดคำสั่งหนึ่ง พิมพ์ `exit` (หรือ Ctrl+D) เพื่อออก

หากแอป restart ขณะที่คุณเชื่อมต่ออยู่ shell จะเชื่อมต่อใหม่ให้เอง

<div id="shared-options"></div>

### ตัวเลือกที่ใช้ร่วมกัน

คำสั่ง live ทุกตัว -- ไม่ว่าจะอยู่ภายใน shell หรือเป็น `metro live:*` -- รองรับตัวเลือกเดียวกัน:

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `-d, --device <#\|name>` | เจาะจงแอปด้วยหมายเลขใน `metro live:devices` หรือชื่อ device |
| `--all` | รันบนทุกแอปที่กำลังทำงานของโปรเจกต์นี้ |
| `--json` | พิมพ์ JSON ที่เครื่องอ่านได้ |
| `--uri` | ใช้ VM service address นี้แทนการค้นหาแอป |
| `--timeout` | จำนวนวินาทีที่รอแต่ละแอประหว่างการค้นหา (ค่าเริ่มต้น `3`) |

ค่าตัวเลือกที่เขียนเป็น `@path` จะถูกอ่านจากไฟล์นั้น -- มีประโยชน์สำหรับ payload `--data` ขนาดใหญ่:

``` bash
metro live:run route /profile --data @user.json
```

เริ่มค่าด้วย `@@` เมื่อค่านั้นขึ้นต้นด้วย `@` จริงๆ

<div id="tab-completion-and-history"></div>

### Tab Completion และประวัติ

ภายใน shell, Tab จะเติมชื่อคำสั่ง, ตัวเลือก, route, คีย์ storage และ Backpack, ชื่อ seeder และชื่อ playbook ให้สมบูรณ์โดยอ้างอิงจากแอปที่เชื่อมต่ออยู่ ประวัติจะถูกเก็บไว้ใน `.dart_tool/nylo/live_history` และคงอยู่ข้ามแต่ละ session

<div id="running-a-script"></div>

### การรันสคริปต์

Pipe ไฟล์เข้าไปที่ `metro live` เพื่อรันเป็นสคริปต์ ทีละคำสั่งต่อบรรทัด และหยุดที่คำสั่งแรกที่ล้มเหลว:

``` bash
metro live < seeds/demo.live
```

บรรทัดว่างและบรรทัดที่ขึ้นต้นด้วย `#` จะถูกข้าม

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

แสดงรายการแอปที่กำลังทำงานของโปรเจกต์นี้ พร้อมหมายเลขสำหรับใช้กับ `-d`:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

แสดงแอป, device, build mode, route ปัจจุบันและ stack, locale, ธีม และผู้ใช้ signed in อยู่หรือไม่:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

รันคำสั่งในตัวที่ตรวจสอบหรือขับเคลื่อนแอป:

``` bash
metro live:run <command> [arguments]
```

| คำสั่ง | คำอธิบาย |
|---------|-------------|
| `data` | แสดงข้อมูลและ fields ของหน้าที่อยู่บนหน้าจอ |
| `routes` | แสดงรายการ route ที่ลงทะเบียนไว้ |
| `route` | เปิดหน้า พร้อม `--data` เป็นตัวเลือก |
| `back` | ย้อนกลับหนึ่งหน้า หรือกลับไปยัง route ที่ระบุ |
| `deeplink` | เปิด deep link หรือแสดงวิธีตั้งค่า |
| `storage` | แสดงรายการ local storage หรือแสดง บันทึก หรือลบค่าหนึ่งค่า |
| `storage:clear` | ล้าง local storage พร้อม `--keep` เป็นตัวเลือก |
| `backpack` | แสดงรายการค่าของ Backpack หรือแสดง บันทึก หรือลบค่าหนึ่งค่า |
| `auth` | แสดงว่าใคร signed in อยู่ หรือ sign in และ sign out |
| `locale` | เปลี่ยนภาษาของแอป |
| `theme` | เปลี่ยนธีมของแอป |
| `state` | ส่งข้อมูลไปยัง state ด้วย `updateState` |
| `event` | ยิง event หนึ่งของคุณ |
| `toast` | แสดง toast notification |
| `playbook` | เล่น [playbook](/docs/{{ $version }}/playbooks) ของคุณ หรือแสดงรายการ |
| `screenshot` | บันทึก[ภาพหน้าจอ](#screenshots)ของแอป หรือของ `ScreenshotArea` หนึ่งรายการ |

ภายใน `metro live` ให้ตัดส่วน `live:run` ออก -- ใช้ `route /profile` แทน `metro live:run route /profile`:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` ยังอ่าน fields ที่ state ของหน้าคุณประกาศไว้ ตรงจากแอปที่กำลังทำงานอยู่ -- เนื่องจากไม่มี reflection ใน Flutter สิ่งนี้จึงทำงานได้เฉพาะกับ state ที่อยู่บนหน้าจอจริงๆ เท่านั้น:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbook

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

เล่น [playbook](/docs/{{ $version }}/playbooks) หนึ่งรายการหรือมากกว่าบนแอปที่กำลังทำงานอยู่ แต่ละ step จะถูกพิมพ์ออกมาเมื่อเกิดขึ้น:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

ระบุหลายชื่อเพื่อเล่นต่อเนื่องกัน หากไม่ระบุชื่อ หรือใช้ `--list` คำสั่งจะแสดงรายการ playbook ของแอป

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | เล่นด้วยจังหวะนี้แทนจังหวะของแต่ละ playbook เอง |
| `--data <json>` | JSON object ที่ทุก playbook ในการรันนี้อ่านได้ใน `data` โดยมีผลเหนือค่าเริ่มต้นของมัน |
| `--no-overlay` | ซ่อนการนับถอยหลัง รวมถึงนิ้วและคำบรรยายของ playbook ที่แสดงสิ่งเหล่านี้ |
| `--loop` | เล่น playbook ซ้ำไปเรื่อยๆ จนกว่าจะกด Ctrl+C |
| `--record[=<file>]` | บันทึกหน้าจอของอุปกรณ์ตลอดการรัน บันทึกไปที่ `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (หรือ `.gif`) เลือกไฟล์ โดยอิงจากโปรเจกต์ ต้องใช้ ffmpeg |
| `--no-captions` | ไม่สร้างไฟล์คำบรรยาย `.vtt` และ chapter ของการบันทึก |
| `--clean-status-bar` | แสดง 9:41 พร้อมสัญญาณและแบตเตอรี่เต็มขณะ playbook เล่น แล้วคืนค่าเดิม ใช้ได้โดยไม่ต้องมี `--record` ด้วย |
| `--countdown <seconds>` | นับถอยหลัง 0-10 วินาทีบนหน้าจอก่อน step แรก |
| `--screenshots[=<mode>]` | บันทึกหน้าจอของทุก step ด้วย `<mode>` คือ `all`, `auto`, `marked`, `failures` หรือ `off` ดู [Screenshots ของ Playbook](#playbook-screenshots) |
| `--scale <n>` | ขนาดของภาพหน้าจอเทียบกับขนาดของอุปกรณ์เอง สูงสุด 4 เช่น `0.5` สำหรับครึ่งหนึ่ง |
| `--locales <list>` | เล่นหนึ่งครั้งในแต่ละ locale เหล่านี้ เช่น `en,es` หรือ `all` ของแอป |
| `--themes <list>` | เล่นหนึ่งครั้งในแต่ละธีมเหล่านี้ เช่น `light,dark` หรือ `all` ของแอป |
| `--store <id>` | วาดภาพหน้าจอในขนาดของ app store เช่น `ios-6.9` |
| `--frame` | วาดแต่ละหน้าจอภายในกรอบโทรศัพท์ |
| `--background <hex>` | สีรอบหน้าจอ เช่น `"#1B2027"` |
| `--list` | แสดงรายการ playbook ของแอปแทนการเล่น |

Ctrl+C จะขอให้แอปหยุด: step ที่เหลือจะถูกข้าม แต่ `tearDown` ยังคงรัน กด Ctrl+C อีกครั้งเพื่อเลิกรอ

หากไม่มี step ใดไปถึง Metro ภายใน 20 วินาที Metro จะหยุดรอและทำให้การรันล้มเหลว แอปกำลังเล่น playbook แต่ event ของมันไม่ได้เข้ามา ซึ่งเกิดขึ้นเมื่อแอปเริ่มด้วย `flutter run --no-dds` ให้รันด้วย `flutter run` ธรรมดาแล้วลองอีกครั้ง

ภายใน shell ของ `metro live` คำสั่งคือ `playbook <name>` และ Tab จะเติมชื่อ playbook ให้สมบูรณ์:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>
  
  ### การบันทึก
  
  <x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />
  
  <!-- uncertain: new Recording section; no prior locale context for recording terms -->
  เพิ่ม `--record` เพื่อบันทึกวิดีโอของการรัน:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

ภายใน shell ของ `metro live` ให้พิมพ์ `playbook sign_up --record`

การบันทึกต้องใช้ ffmpeg (เช่น `brew install ffmpeg`) Metro จะตรวจสอบก่อนที่อะไรจะเริ่มเล่น

- **iOS Simulator** -- บันทึกด้วย `xcrun simctl`
- **Android emulator หรืออุปกรณ์** -- บันทึกด้วย `adb screenrecord` ซึ่งหยุดหลังจาก 3 นาที หรือด้วย `scrcpy` เมื่อติดตั้งไว้ (ไม่จำกัดเวลา และบันทึกเสียงได้บน Android 11+) หากมีอุปกรณ์ Android เชื่อมต่อหลายเครื่องและ Metro ไม่ทราบว่าเครื่องไหนรันแอป ให้ตั้งค่า `ANDROID_SERIAL`
- **iPhone จริง** -- บันทึกจากบรรทัดคำสั่งไม่ได้ ให้บันทึกด้วย QuickTime หรือ Control Center และใช้ `--countdown 3` เพื่อให้มีเวลาเริ่ม

ผลลัพธ์คือไฟล์ `.mp4` (H.264, 60 fps คงที่) ที่ยาวเท่ากับการรันพอดี หรือไฟล์ `.gif` กว้าง 480px ที่ 15 fps แต่ละ step จะกลายเป็นคำบรรยายในไฟล์ `.vtt` ข้างไฟล์ `.mp4` และเป็น chapter ภายในไฟล์ ไฟล์เหล่านี้สร้างจาก step ของการรัน ไม่ว่า playbook จะแสดงคำบรรยายบนหน้าจอหรือไม่ก็ตาม ใส่ `--no-captions` เพื่อข้าม

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

playbook ที่ล้มเหลวหรือถูกหยุดจะยังถูกบันทึก ดังนั้นการบันทึกจึงใช้เป็นรายงานบั๊กได้ด้วย Ctrl+C จะหยุด playbook (`tearDown` ยังคงรัน) และการบันทึกจะยังถูกเก็บไว้

`--clean-status-bar` จะคง status bar ที่คุณตั้งไว้แล้วด้วย `simctl status_bar` บน Simulator บน Android จะใช้ demo mode และปิดหลังจากนั้น เมื่อมีหลายแอป (`--all`) แต่ละอุปกรณ์จะได้ไฟล์ของตัวเองที่มีชื่ออุปกรณ์อยู่ในชื่อไฟล์

โดยค่าเริ่มต้น playbook จะซ่อนนิ้วและคำบรรยาย สำหรับวิดีโอสาธิต ให้ตั้งค่า `showFinger` และ `caption` บน playbook -- ดู [นิ้วและคำบรรยาย](/docs/{{ $version }}/playbooks#finger-and-caption)

<div id="playbook-screenshots"></div>

### Screenshots

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

[step `screenshot()`](/docs/{{ $version }}/playbooks#screenshots) ของ playbook จะถูกบันทึกใน `screenshots/<playbook>/` โดยไม่ต้องใช้ flag เพิ่มตัวเลือกเพื่อถ่ายในทุกภาษาและทุกธีม หรือปรับขนาดสำหรับหน้ารายการใน store:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` ยังบันทึกหน้าจอหลังทุก step ของ playbook ที่ไม่มี step `screenshot()` และ `--screenshots=all` ทำเช่นนั้นกับทุก playbook หน้าจอของ step ที่ล้มเหลวจะถูกบันทึกใน `build/playbooks/failures` และ `--screenshots=off` จะปิดสิ่งนั้น

`--locales` และ `--themes` เล่น playbook หนึ่งครั้งในแต่ละรายการ และ locale กับธีมของแอปเองจะถูกคืนค่าหลังจากนั้น `--store` จะแสดงรายการขนาดเมื่อไม่มีค่า และ `--frame` วาดแต่ละหน้าจอในกรอบโทรศัพท์พร้อม `caption:` ของ step เหนือกรอบ `--clean-status-bar` แสดง 9:41 พร้อมสัญญาณและแบตเตอรี่เต็มในภาพที่ Metro ถ่ายด้วย iOS Simulator หรือ `adb`

<div id="playbook-live-commands"></div>

### เอกสารอ้างอิงคำสั่ง Live

คำสั่งเบื้องหลังคือ `playbooks.list`, `playbooks.run` และ `playbooks.stop` การรันจะคืน id ทันทีและสตรีมแต่ละ step เป็น event `nylo.playbook` จึงสามารถใช้เวลานานกว่า timeout ของคำสั่งได้ `live:status` จะแสดงรายการ playbook ของแอป Playbook เริ่มมีใน Nylo Live protocol เวอร์ชัน 9 การรองรับการบันทึก -- `countdown` ใน `playbooks.run` (พร้อม event `countdown` ทุกวินาที) และ `simulator` (UDID ของ iOS Simulator) ใน `status` -- เริ่มมีใน protocol เวอร์ชัน 10

<div id="recording-a-journey"></div>

## การบันทึก Journey

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` เขียนสิ่งที่คุณทำในแอปที่กำลังทำงานเป็น [playbook](/docs/{{ $version }}/playbooks#recording-a-playbook) มันรันได้เฉพาะภายใน shell ของ `metro live`:

``` plaintext
› record sign_up
```

ใช้งานแอป แล้วกด Enter เมื่อ journey จบ playbook จะอยู่ที่ `lib/app/playbooks/sign_up_playbook.dart` และลงทะเบียนใน `lib/bootstrap/playbooks.dart` และ Metro จะเสนอให้ hot restart แอปและเล่นกลับที่ความเร็ว `fast`

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `--description <text>` | คำอธิบายหนึ่งบรรทัด ที่แสดงเมื่อ Metro แสดงรายการ playbook |
| `--no-data` | เขียนค่าที่พิมพ์ตามที่พิมพ์จริง แทนที่จะเป็น placeholder `data[...]` พร้อมค่าเริ่มต้น |
| `--no-replay` | ข้ามคำถามเกี่ยวกับการเล่นกลับ |
| `--force`, `-f` | แทนที่ไฟล์ playbook ที่มีอยู่แล้ว |

ขณะบันทึก พิมพ์ `a` แล้วกด Enter เพื่อให้การแตะครั้งถัดไปเป็น `see()`, `d` สำหรับ `dontSee()`, `s <title>` เพื่อเริ่ม scene, `u` เพื่อยกเลิก step ล่าสุด และ `p` เพื่อหยุดชั่วคราว Ctrl+C จะทิ้งการบันทึก `metro make:playbook sign_up --record` เปิด shell และเริ่มบันทึกในครั้งเดียว

คำสั่งเบื้องหลังคือ `record.start`, `record.stop`, `record.undo`, `record.mark` และ `record.status` การบันทึกจะสตรีมแต่ละ step เป็น event `nylo.record` การบันทึกเริ่มมีใน Nylo Live protocol เวอร์ชัน 11

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

บันทึก PNG ของแอปที่กำลังทำงานของคุณ:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

แต่ละไฟล์จะถูกบันทึกใน `screenshots/` ในโปรเจกต์ของคุณ โดยตั้งชื่อเป็น `<mode>-<device>-<date>-<time>.png` บน iOS Simulator และ Android Metro จับภาพทั้งหน้าจอของอุปกรณ์ด้วย Simulator หรือ `adb` ดังนั้นจึงมี status bar และคีย์บอร์ดอยู่ในภาพ อุปกรณ์อื่นใดจะใช้การจับภาพของแอปเอง ซึ่งคือหน้าของแอปตามที่ผู้ใช้เห็น

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `--area <name>` | จับภาพเฉพาะ `ScreenshotArea` ที่มีชื่อนี้ |
| `--scale <n>` | ขนาดเทียบกับขนาดของอุปกรณ์เอง สูงสุด 4 เช่น `0.5` สำหรับครึ่งหนึ่ง |
| `--locales <list>` | จับภาพหน้าจอในแต่ละ locale เหล่านี้ เช่น `en,es` หรือ `all` ของแอป |
| `--themes <list>` | จับภาพหน้าจอในแต่ละธีมเหล่านี้ เช่น `light,dark` หรือ `all` ของแอป |
| `--store <id>` | วาดในขนาดภาพหน้าจอของ app store เช่น `ios-6.9` เมื่อใช้ `--store` เพียงอย่างเดียวจะแสดงรายการขนาด |
| `--frame` | วาดหน้าจอภายในกรอบโทรศัพท์ บนพื้นหลัง |
| `--caption <text>` | ข้อความเหนือ `--frame` หรือ translation key |
| `--background <hex>` | สีรอบหน้าจอ หลัง `--frame` หรือข้างขนาด `--store` ที่มีรูปทรงอื่น เช่น `"#1B2027"` |
| `--out <path>` | บันทึกเป็นไฟล์ `.png` นี้ หรือลงในโฟลเดอร์ที่ลงท้ายด้วย `/` (ค่าเริ่มต้น: `screenshots/`) |
| `--open` | เปิดภาพหน้าจอเมื่อบันทึกแล้ว |
| `--copy` | คัดลอกภาพหน้าจอไปยังคลิปบอร์ด |
| `--native` | จับภาพด้วย iOS Simulator หรือ `adb` นี่คือค่าเริ่มต้นสำหรับการจับภาพทั้งหน้าจอ `--no-native` ใช้การจับภาพของแอปเอง |
| `--clean-status-bar` | แสดง 9:41 พร้อมสัญญาณและแบตเตอรี่เต็มในการจับภาพแบบ `--native` |

เมื่อใช้ `--locales` หรือ `--themes` Metro จะบันทึก PNG หนึ่งไฟล์สำหรับแต่ละคู่ในโฟลเดอร์ ตั้งชื่อเป็น `es.png`, `dark.png` หรือ `es-dark.png` โดยสลับแอปในที่เดิมและคืนค่าหลังจากนั้น `--area`, `--scale`, `--store` และ `--frame` ใช้การจับภาพของแอปเอง

ภายใน shell ของ `metro live` พิมพ์ `screenshot --open` หากต้องการถ่ายภาพหน้าจอจากโค้ดของแอปเอง ดู [Screenshots](/docs/{{ $version }}/screenshots)

<div id="live-commands"></div>

## คำสั่ง Live

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

ลงทะเบียนคำสั่งของคุณเองเพื่อรันภายในแอป ควบคู่ไปกับคำสั่งในตัวด้านบน

<div id="creating-a-live-command"></div>

### การสร้างคำสั่ง Live

``` bash
metro make:command seed_cart --live
```

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `--category`, `-c` | หมวดหมู่สำหรับคำสั่ง (ค่าเริ่มต้น: `app`) |
| `--description` | คำอธิบายสั้นหนึ่งบรรทัดที่แสดงโดย `--help` ของคำสั่ง |
| `--force`, `-f` | สร้างไฟล์แม้ว่าจะมีอยู่แล้ว |

สิ่งนี้จะสร้าง `LiveCommand` ใน `lib/app/commands/`, ทำเครื่องหมายว่า `"type": "live"` ใน `commands.json`, ลงทะเบียนใน `lib/bootstrap/live_commands.dart`, และเชื่อม `liveCommands: liveCommands` เข้ากับ `nylo.configure(...)` ใน `app_provider.dart`:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` มีรูปแบบ `builder`/`handle` เหมือนกับคำสั่งของ Metro เอง แต่ `handle` จะรัน **ภายในแอป** ดังนั้นมันสามารถใช้ `NyStorage`, `routeTo`, `Auth` และ model ของคุณได้ `package:nylo_framework/live.dart` export `LiveCommand` ควบคู่กับ `Seeder`, `StorageSnapshot`, `LiveException` และ `LiveOutput`

<div id="running-a-live-command"></div>

### การรันคำสั่ง Live

คำสั่ง live ใหม่ต้อง hot restart ก่อนที่แอปที่กำลังทำงานจะรันมันได้ จากนั้น จาก terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeders

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`Seeder` ทำให้แอปที่กำลังทำงานของคุณอยู่ในสถานะที่รู้จัก -- signed in, onboarding เสร็จแล้ว, ข้อมูลตัวอย่าง -- และนำมันกลับออกมาได้

<div id="creating-a-seeder"></div>

### การสร้าง Seeder

``` bash
metro make:seeder demo_user
```

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `--description` | คำอธิบายสั้นหนึ่งบรรทัด แสดงเมื่อ `seed` แสดงรายการ seeder |
| `--force`, `-f` | สร้างไฟล์แม้ว่าจะมีอยู่แล้ว |

สิ่งนี้จะสร้าง `Seeder` ใน `lib/app/seeders/`, ลงทะเบียนใน `lib/bootstrap/seeders.dart`, และเชื่อม `seeders: seeders` เข้ากับ `nylo.configure(...)`:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

ทุกค่า storage และ Backpack ที่ `up()` เปลี่ยนแปลงจะถูกบันทึกโดยอัตโนมัติ ดังนั้น `down()` เริ่มต้น (`restore()`) จะนำแต่ละค่ากลับไปเหมือนเดิมทุกประการ ตามลำดับย้อนกลับ -- แม้ข้าม hot restart เรียก `seed(otherSeeders)` จากภายใน seeder เพื่อรันตัวอื่นเป็นส่วนหนึ่งของมัน; การเปลี่ยนแปลงของพวกมันจะถูกบันทึกและ roll back ด้วยเช่นกัน

<div id="seeding-and-rolling-back"></div>

### Seeding และ Rolling Back

การ seed, roll back และ export จะรันได้เฉพาะภายใน shell `metro live` เท่านั้น:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` เพียงลำพังจะแสดงรายการ seeder ที่ลงทะเบียนไว้ `--fresh` จะล้าง storage และ Backpack ก่อน seed และ `--restart` จะ hot restart แอปหลังจากนั้น เพื่อให้เริ่มทำงานโดยที่ seed ไว้แล้ว:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Storage Snapshots

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` บันทึกทุกอย่างที่แอปเก็บไว้ใน storage และ Backpack ในขณะนั้น -- ในรูปแบบ seeder หรือไฟล์ JSON แบบพกพา -- และ `seed <file>` จะโหลดมันกลับ

<div id="exporting-a-snapshot"></div>

### การ Export Snapshot

รันโดยไม่มีชื่อเพื่อดูตัวอย่างว่าจะจับอะไรบ้าง:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

ตั้งชื่อเพื่อบันทึกเป็น seeder ใน `lib/app/seeders/` (เหมือนกับ `make:seeder` แต่กรอกค่าที่จับมาไว้ให้แล้ว):

``` plaintext
› export pro_user
```

หรือเขียนลงไฟล์แทน:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| ตัวเลือก | คำอธิบาย |
|--------|-------------|
| `--to <path>` | เขียน snapshot ลงไฟล์ JSON นี้แทนที่จะเป็น seeder |
| `--description` | คำอธิบายสั้นหนึ่งบรรทัดสำหรับ seeder |
| `--only <keys>` | คีย์ที่คั่นด้วยจุลภาคเพื่อ export; `*` จับคู่ได้กับทุกอย่าง เช่น `--only SK_USER,onboarding_*` |
| `--except <keys>` | คีย์ที่คั่นด้วยจุลภาคเพื่อไม่รวม เช่น `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | รวมค่าจาก Backpack ด้วย (ค่าเริ่มต้น: รวม) |
| `--force`, `-f` | แทนที่ seeder หรือไฟล์ที่มีอยู่แล้ว |

`Model` หรือคลาสใดก็ตามที่มี decoder ลงทะเบียนไว้ จะถูกแท็กด้วยชื่อคลาส เพื่อให้มันกลับมาผ่าน decoder ของมันเอง แทนที่จะเป็น plain map `export` จะเตือนเมื่อ snapshot มีคีย์ที่ดูเหมือนข้อมูลรับรอง (credentials) และเมื่อมันใหญ่พอที่คุณน่าจะต้องการ `--except`

<div id="loading-a-snapshot"></div>

### การโหลด Snapshot

``` plaintext
› seed snapshots/pro_user.json
```

การโหลดไฟล์จะถูกบันทึกในลักษณะเดียวกับ seeder ดังนั้น `seed:rollback <name>` (ชื่อไฟล์ หรือชื่อที่ `--as` กำหนดให้) จะยกเลิกมันได้เช่นกัน จากในโค้ด seeder สามารถนำ snapshot ที่จับไว้มาใช้ได้ด้วย `importSnapshot`:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` เขียนออกมาในรูปแบบนี้เป๊ะๆ ดังนั้นไฟล์ที่เขียนด้วย `--to` สามารถวางลงใน field `snapshot` ของ seeder ได้โดยตรง

<div id="page-actions"></div>

## Page Actions

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` บนหน้าให้ `PageStateActions` แก่คุณสำหรับเรียกใช้หน้านั้นจากภายนอก โดยไม่ต้องสร้างการเรียก `stateAction(...)` เอง:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

ดู [การเรียกใช้ Actions ของหน้า](/docs/{{ $version }}/state-management#page-actions-shortcut) สำหรับชุด actions ในตัวทั้งหมดและวิธีประกาศ actions แบบมีชนิดข้อมูลของคุณเอง Nylo Live อ่านและใช้ actions ชุดเดียวกันนี้: `metro live:run data` จะแสดงรายการสิ่งที่หน้าบนหน้าจอเปิดเผยภายใต้ `actions` และ `metro live:run state` จะส่งข้อมูลไปยังหน้าโดยตรงผ่านชื่อ state ของมัน
