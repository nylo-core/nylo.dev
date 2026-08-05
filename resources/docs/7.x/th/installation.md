# Installation

---

<a name="section-1"></a>
- [ติดตั้ง](#install "ติดตั้ง")
- [การรันโปรเจกต์](#running-the-project "การรันโปรเจกต์")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

คำสั่งสามคำสั่งจะพาคุณจากโฟลเดอร์ว่างไปยังแอป Flutter ที่ทำงานได้ พร้อมระบบเส้นทาง เครือข่าย ธีม และการสร้างโค้ดที่ตั้งค่าไว้แล้ว

<x-doc-strip label="ก่อนเริ่มต้น" items="ติดตั้ง Flutter SDK แล้ว, Dart 3" linkText="ความต้องการทั้งหมด" linkHref="/th/docs/{{ $version }}/requirements" />

## ติดตั้ง

เรียกใช้คำสั่งเหล่านี้ตามลำดับ แต่ละคำสั่งสามารถเรียกใช้ซ้ำได้อย่างปลอดภัย

<x-doc-steps>
<x-doc-step number="1" title="ติดตั้ง Nylo CLI แบบ global">
คำสั่งนี้จะติดตั้งเครื่องมือ CLI ของ {{ config('app.name') }} แบบ global บนระบบของคุณ

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
