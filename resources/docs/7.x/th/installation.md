# Installation

---

<a name="section-1"></a>
- [ติดตั้ง](#install "ติดตั้ง")
- [การรันโปรเจกต์](#running-the-project "การรันโปรเจกต์")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [การล้างโปรเจกต์](#nylo-clean "การล้างโปรเจกต์")
  - [การรีเฟรช iOS pods](#nylo-ios-pod-refresh "การรีเฟรช iOS pods")
  - [การรัน Metro โดยไม่ใช้ alias](#nylo-metro "การรัน Metro โดยไม่ใช้ alias")
  - [การอัปเดต CLI](#nylo-self-update "การอัปเดต CLI")

<div id="install"></div>

คำสั่งสามคำสั่งจะพาคุณจากโฟลเดอร์ว่างไปยังแอป Flutter ที่ทำงานได้ พร้อมระบบเส้นทาง เครือข่าย ธีม และการสร้างโค้ดที่ตั้งค่าไว้แล้ว

<x-doc-strip label="ก่อนเริ่มต้น" items="ติดตั้ง Flutter SDK แล้ว, Dart 3" linkText="ความต้องการทั้งหมด" linkHref="/th/docs/{{ $version }}/requirements" />

## ติดตั้ง

เรียกใช้คำสั่งเหล่านี้ตามลำดับ แต่ละคำสั่งสามารถเรียกใช้ซ้ำได้อย่างปลอดภัย

<x-doc-steps>
<x-doc-step number="1" title="ติดตั้ง Nylo CLI แบบ global">
ติดตั้งคำสั่ง `nylo` ในระบบของคุณ

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="สร้างโปรเจกต์ใหม่">
คำสั่งนี้จะ clone เทมเพลต {{ config('app.name') }} ตั้งค่าโปรเจกต์ด้วยชื่อแอปของคุณ และติดตั้ง dependencies ทั้งหมดโดยอัตโนมัติ

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="ตั้งค่า Metro alias">
คำสั่งนี้จะตั้งค่าคำสั่ง `metro` สำหรับโปรเจกต์ของคุณ ช่วยให้คุณใช้คำสั่ง Metro CLI ได้โดยไม่ต้องพิมพ์ `dart run` เต็มรูปแบบ

``` bash
cd my_app
nylo init
```

จากนั้นรีสตาร์ทเทอร์มินัลของคุณ หรือเรียกใช้คำสั่ง `source` ที่ `nylo init` แสดงผลออกมา
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="สิ่งที่คุณจะได้รับ" items="การกำหนดเส้นทางและการนำทางที่ตั้งค่าไว้ล่วงหน้า
โครงร่าง API service
การตั้งค่าธีมและการแปลภาษา
Metro CLI สำหรับการสร้างโค้ด" />


<div id="running-the-project"></div>

## การรันโปรเจกต์

โปรเจกต์ {{ config('app.name') }} รันเหมือนกับแอป Flutter มาตรฐาน

<x-doc-tabs tabs="เทอร์มินัล, Android Studio, VS Code">
<x-doc-tab label="เทอร์มินัล">

``` bash
flutter run
```

หากการ build สำเร็จ แอปจะแสดงหน้าจอเริ่มต้นของ {{ config('app.name') }}
</x-doc-tab>

<x-doc-tab label="Android Studio">
เปิดโฟลเดอร์โปรเจกต์ เลือกอุปกรณ์จากตัวเลือกเป้าหมาย แล้วกด **Run**

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">เอกสาร Flutter: การรันและการดีบัก ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
เปิดโฟลเดอร์โปรเจกต์ แล้วเรียกใช้ **Debug: Start Without Debugging** จาก command palette

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">เอกสาร Flutter: รันโดยไม่มี breakpoint ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro จะสร้างไฟล์โปรเจกต์ให้คุณ เรียกใช้โดยไม่มีอาร์กิวเมนต์เพื่อดูเมนู หรือเรียกคำสั่งโดยตรง

``` plaintext
$ metro

Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### ข้อมูลอ้างอิงคำสั่ง

แต่ละคำสั่งรับชื่อ เช่น `metro make:page settings_page`

<x-doc-commands title="คำสั่ง Widget" rows="
metro make:page | สร้างหน้าใหม่
metro make:stateful_widget | สร้าง stateful widget
metro make:stateless_widget | สร้าง stateless widget
metro make:state_managed_widget | สร้าง state-managed widget
metro make:navigation_hub | สร้าง navigation hub (แถบนำทางด้านล่าง)
metro make:journey_widget | สร้า journey widget สำหรับ navigation hub
metro make:bottom_sheet_modal | สร้าง bottom sheet modal
metro make:button | สร้าง widget ปุ่มที่กำหนดเอง
metro make:form | สร้างฟอร์มพร้อมการตรวจสอบ
" />

<x-doc-commands title="คำสั่ง Helper" rows="
metro make:model | สร้างคลาสโมเดล
metro make:provider | สร้าง provider
metro make:api_service | สร้าง API service
metro make:controller | สร้าง controller
metro make:event | สร้าง event
metro make:route_guard | สร้าง route guard
metro make:config | สร้างไฟล์ config
metro make:interceptor | สร้าง network interceptor
metro make:command | สร้างคำสั่ง Metro ที่กำหนดเอง
metro make:env | สร้าง environment config จาก .env
" />

### ตัวอย่างการใช้งาน

``` bash
# สร้างหน้าใหม่
metro make:page settings_page

# สร้างโมเดล
metro make:model User

# สร้าง API service
metro make:api_service user_api_service
```


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: label "Requires" has no established translation in this locale file's existing x-doc-strip usage -->
<x-doc-strip label="ข้อกำหนด" items="nylo_installer 1.8.0+" linkText="อัปเดต CLI" linkHref="#nylo-self-update" />

คำสั่ง `nylo` จากขั้นตอนที่ 1 ทำได้มากกว่าแค่สร้างโปรเจกต์ เรียกใช้จากไดเรกทอรีโปรเจกต์ของคุณเพื่อล้าง build รีเฟรช iOS pods รันเทสต์ และตรวจสอบคำแปลของคุณ

<x-doc-commands title="คำสั่งโปรเจกต์" rows="
nylo new | สร้างโปรเจกต์ใหม่
nylo init | ตั้งค่า alias metro ใน shell ของคุณ
nylo clean | รัน flutter clean และ flutter pub get
nylo ios:pod-refresh | ติดตั้ง iOS pods ของคุณใหม่
nylo metro | รันคำสั่ง Metro โดยไม่ใช้ alias
" />

<x-doc-commands title="การทดสอบและการแปลภาษา" rows="
nylo test | จัดรูปแบบและรันเทสต์ของคุณ
nylo locale:find-untranslated | ค้นหาข้อความที่ยังไม่ได้ครอบด้วย .tr()
nylo locale:check-missing-keys | ค้นหาคีย์ที่หายไปจากไฟล์ภาษาของคุณ
" />

<x-doc-commands title="ตัว CLI เอง" rows="
nylo self-update | อัปเดต nylo เป็นเวอร์ชันล่าสุด
nylo --version | แสดงเวอร์ชันที่ติดตั้งอยู่
" />

คำสั่งที่มีตัวเลือกจะแสดงรายการด้วย `-h` เช่น `nylo test -h` การทดสอบมีอธิบายไว้ใน [การรันเทสต์](/docs/{{ $version }}/testing#running-tests) และคำสั่ง `locale:` ใน [การตรวจสอบคำแปล](/docs/{{ $version }}/localization#auditing-translations)

<div id="nylo-clean"></div>

### การล้างโปรเจกต์

`nylo clean` จะรัน `flutter clean` แล้วตามด้วย `flutter pub get` เพิ่ม flag แพลตฟอร์มเมื่อการล้างแบบปกติไม่เพียงพอ:

``` bash
# flutter clean จากนั้น flutter pub get
nylo clean

# ลบ ios/Pods, ios/.symlinks และ ios/Podfile.lock ด้วย จากนั้นรัน pod install --repo-update
nylo clean --ios

# รัน gradlew clean ใน android/ ด้วย
nylo clean --android

# ทั้งสองอย่างข้างต้น
nylo clean --all
```

หากโปรเจกต์ไม่มีโฟลเดอร์ `ios/` หรือ `android/` แพลตฟอร์มนั้นจะถูกข้ามไปพร้อมคำเตือน `pod install` จะรันเฉพาะบน macOS เท่านั้น

<div id="nylo-ios-pod-refresh"></div>

### การรีเฟรช iOS pods

เมื่อ CocoaPods ไม่ซิงค์กัน `nylo ios:pod-refresh` จะลบ `ios/Pods`, `ios/.symlinks` และ `ios/Podfile.lock` จากนั้นรัน `pod install --repo-update` ในโฟลเดอร์ `ios/`:

``` bash
nylo ios:pod-refresh
```

เรียกใช้จาก root ของโปรเจกต์บน macOS ต่างจาก `nylo clean --ios` คำสั่งนี้จะไม่รัน `flutter clean` หรือ `flutter pub get`

<div id="nylo-metro"></div>

### การรัน Metro โดยไม่ใช้ alias

`nylo metro` จะส่งทุกอย่างที่ตามหลังไปยัง Metro (`dart run nylo_framework:main`) ใช้คำสั่งนี้ก่อนที่คุณจะรัน `nylo init` หรือบน Windows ที่ซึ่ง `nylo init` ไม่สามารถเพิ่ม alias `metro` ให้คุณได้:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### การอัปเดต CLI

``` bash
nylo self-update
```

คำสั่งนี้จะตรวจสอบ pub.dev และติดตั้ง `nylo_installer` ใหม่หากมีเวอร์ชันใหม่กว่า หลังจากคำสั่งทำงานเสร็จ `nylo` จะบอกคุณด้วยเมื่อมีอัปเดตออกมา มันจะเงียบเมื่อ output ไม่ได้ส่งไปยังเทอร์มินัล เพื่อไม่ให้ข้อความแจ้งเตือนไปปรากฏใน CI log หรือรายงานที่ถูก pipe

หากต้องการดูว่าคุณติดตั้งเวอร์ชันใดอยู่:

``` bash
nylo --version
```
