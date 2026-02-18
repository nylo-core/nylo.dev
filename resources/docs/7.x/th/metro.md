# Metro CLI tool

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การติดตั้ง](#install "การติดตั้ง Metro Alias สำหรับ {{ config('app.name') }}")
- คำสั่ง Make
  - [Make controller](#make-controller "สร้าง controller ใหม่")
  - [Make model](#make-model "สร้าง model ใหม่")
  - [Make page](#make-page "สร้างหน้าใหม่")
  - [Make stateless widget](#make-stateless-widget "สร้าง stateless widget ใหม่")
  - [Make stateful widget](#make-stateful-widget "สร้าง stateful widget ใหม่")
  - [Make journey widget](#make-journey-widget "สร้าง journey widget ใหม่")
  - [Make API Service](#make-api-service "สร้าง API Service ใหม่")
  - [Make Event](#make-event "สร้าง Event ใหม่")
  - [Make Provider](#make-provider "สร้าง provider ใหม่")
  - [Make Theme](#make-theme "สร้างธีมใหม่")
  - [Make Forms](#make-forms "สร้างฟอร์มใหม่")
  - [Make Route Guard](#make-route-guard "สร้าง route guard ใหม่")
  - [Make Config File](#make-config-file "สร้างไฟล์ config ใหม่")
  - [Make Command](#make-command "สร้างคำสั่งใหม่")
  - [Make State Managed Widget](#make-state-managed-widget "สร้าง state managed widget ใหม่")
  - [Make Navigation Hub](#make-navigation-hub "สร้าง navigation hub ใหม่")
  - [Make Bottom Sheet Modal](#make-bottom-sheet-modal "สร้าง bottom sheet modal ใหม่")
  - [Make Button](#make-button "สร้างปุ่มใหม่")
  - [Make Interceptor](#make-interceptor "สร้าง interceptor ใหม่")
  - [Make Env File](#make-env-file "สร้างไฟล์ env ใหม่")
  - [Make Key](#make-key "สร้าง APP_KEY")
- App Icons
  - [การสร้าง App Icons](#build-app-icons "การสร้าง App Icons ด้วย Metro")
- คำสั่งแบบกำหนดเอง
  - [การสร้างคำสั่งแบบกำหนดเอง](#creating-custom-commands "การสร้างคำสั่งแบบกำหนดเอง")
  - [การรันคำสั่งแบบกำหนดเอง](#running-custom-commands "การรันคำสั่งแบบกำหนดเอง")
  - [การเพิ่ม options ให้คำสั่ง](#adding-options-to-custom-commands "การเพิ่ม options ให้คำสั่งแบบกำหนดเอง")
  - [การเพิ่ม flags ให้คำสั่ง](#adding-flags-to-custom-commands "การเพิ่ม flags ให้คำสั่งแบบกำหนดเอง")
  - [เมธอดตัวช่วย](#custom-command-helper-methods "เมธอดตัวช่วยของคำสั่งแบบกำหนดเอง")
  - [เมธอด interactive input](#interactive-input-methods "เมธอด Interactive Input")
  - [การจัดรูปแบบ output](#output-formatting "การจัดรูปแบบ Output")
  - [ตัวช่วยระบบไฟล์](#file-system-helpers "ตัวช่วยระบบไฟล์")
  - [ตัวช่วย JSON และ YAML](#json-yaml-helpers "ตัวช่วย JSON และ YAML")
  - [ตัวช่วยแปลง case](#case-conversion-helpers "ตัวช่วยแปลง Case")
  - [ตัวช่วย path โปรเจกต์](#project-path-helpers "ตัวช่วย Path โปรเจกต์")
  - [ตัวช่วย platform](#platform-helpers "ตัวช่วย Platform")
  - [คำสั่ง Dart และ Flutter](#dart-flutter-commands "คำสั่ง Dart และ Flutter")
  - [การจัดการไฟล์ Dart](#dart-file-manipulation "การจัดการไฟล์ Dart")
  - [ตัวช่วยไดเรกทอรี](#directory-helpers "ตัวช่วยไดเรกทอรี")
  - [ตัวช่วยการตรวจสอบ](#validation-helpers "ตัวช่วยการตรวจสอบ")
  - [การสร้างโครงไฟล์](#file-scaffolding "การสร้างโครงไฟล์")
  - [Task runner](#task-runner "Task Runner")
  - [การแสดงผลแบบตาราง](#table-output "การแสดงผลแบบตาราง")
  - [แถบความคืบหน้า](#progress-bar "แถบความคืบหน้า")


<div id="introduction"></div>

## บทนำ

Metro เป็นเครื่องมือ CLI ที่ทำงานภายใต้ framework {{ config('app.name') }}
มันมีเครื่องมือที่มีประโยชน์มากมายเพื่อเร่งการพัฒนา

<div id="install"></div>

## การติดตั้ง

เมื่อคุณสร้างโปรเจกต์ Nylo ใหม่โดยใช้ `nylo init` คำสั่ง `metro` จะถูกตั้งค่าสำหรับ terminal ของคุณโดยอัตโนมัติ คุณสามารถเริ่มใช้งานได้ทันทีในทุกโปรเจกต์ Nylo

รัน `metro` จากไดเรกทอรีโปรเจกต์ของคุณเพื่อดูคำสั่งทั้งหมดที่มี:

``` bash
metro
```

คุณควรเห็น output คล้ายกับด้านล่าง

``` bash
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
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
  make:key
```

<div id="make-controller"></div>

## Make controller

- [การสร้าง controller ใหม่](#making-a-new-controller "สร้าง controller ใหม่ด้วย Metro")
- [บังคับสร้าง controller](#forcefully-make-a-controller "บังคับสร้าง controller ใหม่ด้วย Metro")
<div id="making-a-new-controller"></div>

### การสร้าง controller ใหม่

คุณสามารถสร้าง controller ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:controller profile_controller
```

สิ่งนี้จะสร้าง controller ใหม่หากยังไม่มีอยู่ภายในไดเรกทอรี `lib/app/controllers/`

<div id="forcefully-make-a-controller"></div>

### บังคับสร้าง controller

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ controller ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Make model

- [การสร้าง model ใหม่](#making-a-new-model "สร้าง model ใหม่ด้วย Metro")
- [สร้าง model จาก JSON](#make-model-from-json "สร้าง model ใหม่จาก JSON ด้วย Metro")
- [บังคับสร้าง model](#forcefully-make-a-model "บังคับสร้าง model ใหม่ด้วย Metro")
<div id="making-a-new-model"></div>

### การสร้าง model ใหม่

คุณสามารถสร้าง model ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:model product
```

มันจะวาง model ที่สร้างใหม่ใน `lib/app/models/`

<div id="make-model-from-json"></div>

### สร้าง model จาก JSON

**อาร์กิวเมนต์:**

การใช้ flag `--json` หรือ `-j` จะสร้าง model ใหม่จาก JSON payload

``` bash
metro make:model product --json
```

จากนั้น คุณสามารถวาง JSON ของคุณลงใน terminal แล้วมันจะสร้าง model ให้คุณ

<div id="forcefully-make-a-model"></div>

### บังคับสร้าง model

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ model ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Make page

- [การสร้างหน้าใหม่](#making-a-new-page "สร้างหน้าใหม่ด้วย Metro")
- [สร้างหน้าพร้อม controller](#create-a-page-with-a-controller "สร้างหน้าใหม่พร้อม controller ด้วย Metro")
- [สร้างหน้า auth](#create-an-auth-page "สร้างหน้า auth ใหม่ด้วย Metro")
- [สร้างหน้าเริ่มต้น](#create-an-initial-page "สร้างหน้าเริ่มต้นใหม่ด้วย Metro")
- [บังคับสร้างหน้า](#forcefully-make-a-page "บังคับสร้างหน้าใหม่ด้วย Metro")

<div id="making-a-new-page"></div>

### การสร้างหน้าใหม่

คุณสามารถสร้างหน้าใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:page product_page
```

สิ่งนี้จะสร้างหน้าใหม่หากยังไม่มีอยู่ภายในไดเรกทอรี `lib/resources/pages/`

<div id="create-a-page-with-a-controller"></div>

### สร้างหน้าพร้อม controller

คุณสามารถสร้างหน้าใหม่พร้อม controller โดยรันคำสั่งด้านล่างใน terminal

**อาร์กิวเมนต์:**

การใช้ flag `--controller` หรือ `-c` จะสร้างหน้าใหม่พร้อม controller

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### สร้างหน้า auth

คุณสามารถสร้างหน้า auth ใหม่โดยรันคำสั่งด้านล่างใน terminal

**อาร์กิวเมนต์:**

การใช้ flag `--auth` หรือ `-a` จะสร้างหน้า auth ใหม่

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### สร้างหน้าเริ่มต้น

คุณสามารถสร้างหน้าเริ่มต้นใหม่โดยรันคำสั่งด้านล่างใน terminal

**อาร์กิวเมนต์:**

การใช้ flag `--initial` หรือ `-i` จะสร้างหน้าเริ่มต้นใหม่

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### บังคับสร้างหน้า

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับหน้าที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Make stateless widget

- [การสร้าง stateless widget ใหม่](#making-a-new-stateless-widget "สร้าง stateless widget ใหม่ด้วย Metro")
- [บังคับสร้าง stateless widget](#forcefully-make-a-stateless-widget "บังคับสร้าง stateless widget ใหม่ด้วย Metro")
<div id="making-a-new-stateless-widget"></div>

### การสร้าง stateless widget ใหม่

คุณสามารถสร้าง stateless widget ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:stateless_widget product_rating_widget
```

คำสั่งด้านบนจะสร้าง widget ใหม่หากยังไม่มีอยู่ภายในไดเรกทอรี `lib/resources/widgets/`

<div id="forcefully-make-a-stateless-widget"></div>

### บังคับสร้าง stateless widget

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ widget ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Make stateful widget

- [การสร้าง stateful widget ใหม่](#making-a-new-stateful-widget "สร้าง stateful widget ใหม่ด้วย Metro")
- [บังคับสร้าง stateful widget](#forcefully-make-a-stateful-widget "บังคับสร้าง stateful widget ใหม่ด้วย Metro")

<div id="making-a-new-stateful-widget"></div>

### การสร้าง stateful widget ใหม่

คุณสามารถสร้าง stateful widget ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:stateful_widget product_rating_widget
```

คำสั่งด้านบนจะสร้าง widget ใหม่หากยังไม่มีอยู่ภายในไดเรกทอรี `lib/resources/widgets/`

<div id="forcefully-make-a-stateful-widget"></div>

### บังคับสร้าง stateful widget

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ widget ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Make journey widget

- [การสร้าง journey widget ใหม่](#making-a-new-journey-widget "สร้าง journey widget ใหม่ด้วย Metro")
- [บังคับสร้าง journey widget](#forcefully-make-a-journey-widget "บังคับสร้าง journey widget ใหม่ด้วย Metro")

<div id="making-a-new-journey-widget"></div>

### การสร้าง journey widget ใหม่

คุณสามารถสร้าง journey widget ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# ตัวอย่างเต็มหากคุณมี BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

คำสั่งด้านบนจะสร้าง widget ใหม่หากยังไม่มีอยู่ภายในไดเรกทอรี `lib/resources/widgets/`

อาร์กิวเมนต์ `--parent` ใช้เพื่อระบุ widget หลักที่ journey widget ใหม่จะถูกเพิ่มเข้าไป

ตัวอย่าง

``` bash
metro make:navigation_hub onboarding
```

จากนั้น เพิ่ม journey widget ใหม่
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### บังคับสร้าง journey widget
**อาร์กิวเมนต์:**
การใช้ flag `--force` หรือ `-f` จะเขียนทับ widget ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Make API Service

- [การสร้าง API Service ใหม่](#making-a-new-api-service "สร้าง API Service ใหม่ด้วย Metro")
- [การสร้าง API Service ใหม่พร้อม model](#making-a-new-api-service-with-a-model "สร้าง API Service ใหม่พร้อม model ด้วย Metro")
- [สร้าง API Service โดยใช้ Postman](#make-api-service-using-postman "สร้าง API service ด้วย Postman")
- [บังคับสร้าง API Service](#forcefully-make-an-api-service "บังคับสร้าง API Service ใหม่ด้วย Metro")

<div id="making-a-new-api-service"></div>

### การสร้าง API Service ใหม่

คุณสามารถสร้าง API service ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:api_service user_api_service
```

มันจะวาง API service ที่สร้างใหม่ใน `lib/app/networking/`

<div id="making-a-new-api-service-with-a-model"></div>

### การสร้าง API Service ใหม่พร้อม model

คุณสามารถสร้าง API service ใหม่พร้อม model โดยรันคำสั่งด้านล่างใน terminal

**อาร์กิวเมนต์:**

การใช้ option `--model` หรือ `-m` จะสร้าง API service ใหม่พร้อม model

``` bash
metro make:api_service user --model="User"
```

มันจะวาง API service ที่สร้างใหม่ใน `lib/app/networking/`

### บังคับสร้าง API Service

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ API Service ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Make event

- [การสร้าง event ใหม่](#making-a-new-event "สร้าง event ใหม่ด้วย Metro")
- [บังคับสร้าง event](#forcefully-make-an-event "บังคับสร้าง event ใหม่ด้วย Metro")

<div id="making-a-new-event"></div>

### การสร้าง event ใหม่

คุณสามารถสร้าง event ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:event login_event
```

สิ่งนี้จะสร้าง event ใหม่ใน `lib/app/events`

<div id="forcefully-make-an-event"></div>

### บังคับสร้าง event

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ event ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Make provider

- [การสร้าง provider ใหม่](#making-a-new-provider "สร้าง provider ใหม่ด้วย Metro")
- [บังคับสร้าง provider](#forcefully-make-a-provider "บังคับสร้าง provider ใหม่ด้วย Metro")

<div id="making-a-new-provider"></div>

### การสร้าง provider ใหม่

สร้าง provider ใหม่ในแอปพลิเคชันของคุณโดยใช้คำสั่งด้านล่าง

``` bash
metro make:provider firebase_provider
```

มันจะวาง provider ที่สร้างใหม่ใน `lib/app/providers/`

<div id="forcefully-make-a-provider"></div>

### บังคับสร้าง provider

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ provider ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Make theme

- [การสร้างธีมใหม่](#making-a-new-theme "สร้างธีมใหม่ด้วย Metro")
- [บังคับสร้างธีม](#forcefully-make-a-theme "บังคับสร้างธีมใหม่ด้วย Metro")

<div id="making-a-new-theme"></div>

### การสร้างธีมใหม่

คุณสามารถสร้างธีมโดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:theme bright_theme
```

สิ่งนี้จะสร้างธีมใหม่ใน `lib/resources/themes/`

<div id="forcefully-make-a-theme"></div>

### บังคับสร้างธีม

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับธีมที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Make Forms

- [การสร้างฟอร์มใหม่](#making-a-new-form "สร้างฟอร์มใหม่ด้วย Metro")
- [บังคับสร้างฟอร์ม](#forcefully-make-a-form "บังคับสร้างฟอร์มใหม่ด้วย Metro")

<div id="making-a-new-form"></div>

### การสร้างฟอร์มใหม่

คุณสามารถสร้างฟอร์มใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:form car_advert_form
```

สิ่งนี้จะสร้างฟอร์มใหม่ใน `lib/app/forms`

<div id="forcefully-make-a-form"></div>

### บังคับสร้างฟอร์ม

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับฟอร์มที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Make Route Guard

- [การสร้าง route guard ใหม่](#making-a-new-route-guard "สร้าง route guard ใหม่ด้วย Metro")
- [บังคับสร้าง route guard](#forcefully-make-a-route-guard "บังคับสร้าง route guard ใหม่ด้วย Metro")

<div id="making-a-new-route-guard"></div>

### การสร้าง route guard ใหม่

คุณสามารถสร้าง route guard โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:route_guard premium_content
```

สิ่งนี้จะสร้าง route guard ใหม่ใน `lib/app/route_guards`

<div id="forcefully-make-a-route-guard"></div>

### บังคับสร้าง route guard

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับ route guard ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Make Config File

- [การสร้างไฟล์ config ใหม่](#making-a-new-config-file "สร้างไฟล์ config ใหม่ด้วย Metro")
- [บังคับสร้างไฟล์ config](#forcefully-make-a-config-file "บังคับสร้างไฟล์ config ใหม่ด้วย Metro")

<div id="making-a-new-config-file"></div>

### การสร้างไฟล์ config ใหม่

คุณสามารถสร้างไฟล์ config ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:config shopping_settings
```

สิ่งนี้จะสร้างไฟล์ config ใหม่ใน `lib/app/config`

<div id="forcefully-make-a-config-file"></div>

### บังคับสร้างไฟล์ config

**อาร์กิวเมนต์:**

การใช้ flag `--force` หรือ `-f` จะเขียนทับไฟล์ config ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Make Command

- [การสร้างคำสั่งใหม่](#making-a-new-command "สร้างคำสั่งใหม่ด้วย Metro")
- [บังคับสร้างคำสั่ง](#forcefully-make-a-command "บังคับสร้างคำสั่งใหม่ด้วย Metro")

<div id="making-a-new-command"></div>

### การสร้างคำสั่งใหม่

คุณสามารถสร้างคำสั่งใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:command my_command
```

สิ่งนี้จะสร้างคำสั่งใหม่ใน `lib/app/commands`

<div id="forcefully-make-a-command"></div>

### บังคับสร้างคำสั่ง

**อาร์กิวเมนต์:**
การใช้ flag `--force` หรือ `-f` จะเขียนทับคำสั่งที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Make State Managed Widget

คุณสามารถสร้าง state managed widget ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:state_managed_widget product_rating_widget
```

คำสั่งด้านบนจะสร้าง widget ใหม่ใน `lib/resources/widgets/`

การใช้ flag `--force` หรือ `-f` จะเขียนทับ widget ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Make Navigation Hub

คุณสามารถสร้าง navigation hub ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:navigation_hub dashboard
```

สิ่งนี้จะสร้าง navigation hub ใหม่ใน `lib/resources/pages/` และเพิ่ม route โดยอัตโนมัติ

**อาร์กิวเมนต์:**

| Flag | ย่อ | คำอธิบาย |
|------|-------|-------------|
| `--auth` | `-a` | สร้างเป็นหน้า auth |
| `--initial` | `-i` | สร้างเป็นหน้าเริ่มต้น |
| `--force` | `-f` | เขียนทับหากมีอยู่แล้ว |

``` bash
# สร้างเป็นหน้าเริ่มต้น
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Make Bottom Sheet Modal

คุณสามารถสร้าง bottom sheet modal ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:bottom_sheet_modal payment_options
```

สิ่งนี้จะสร้าง bottom sheet modal ใหม่ใน `lib/resources/widgets/`

การใช้ flag `--force` หรือ `-f` จะเขียนทับ modal ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Make Button

คุณสามารถสร้าง widget ปุ่มใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:button checkout_button
```

สิ่งนี้จะสร้าง widget ปุ่มใหม่ใน `lib/resources/widgets/`

การใช้ flag `--force` หรือ `-f` จะเขียนทับปุ่มที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Make Interceptor

คุณสามารถสร้าง network interceptor ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:interceptor auth_interceptor
```

สิ่งนี้จะสร้าง interceptor ใหม่ใน `lib/app/networking/dio/interceptors/`

การใช้ flag `--force` หรือ `-f` จะเขียนทับ interceptor ที่มีอยู่แล้วหากมันมีอยู่

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Make Env File

คุณสามารถสร้างไฟล์ environment ใหม่โดยรันคำสั่งด้านล่างใน terminal

``` bash
metro make:env .env.staging
```

สิ่งนี้จะสร้างไฟล์ `.env` ใหม่ใน root ของโปรเจกต์

<div id="make-key"></div>

## Make Key

สร้าง `APP_KEY` ที่ปลอดภัยสำหรับการเข้ารหัส environment ใช้สำหรับไฟล์ `.env` ที่เข้ารหัสใน v7

``` bash
metro make:key
```

**อาร์กิวเมนต์:**

| Flag / Option | ย่อ | คำอธิบาย |
|---------------|-------|-------------|
| `--force` | `-f` | เขียนทับ APP_KEY ที่มีอยู่ |
| `--file` | `-e` | ไฟล์ .env เป้าหมาย (ค่าเริ่มต้น: `.env`) |

``` bash
# สร้าง key และเขียนทับที่มีอยู่
metro make:key --force

# สร้าง key สำหรับไฟล์ env เฉพาะ
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## การสร้าง App Icons

คุณสามารถสร้าง app icon ทั้งหมดสำหรับ IOS และ Android โดยรันคำสั่งด้านล่าง

``` bash
dart run flutter_launcher_icons:main
```

สิ่งนี้ใช้การตั้งค่า <b>flutter_icons</b> ในไฟล์ `pubspec.yaml` ของคุณ

<div id="custom-commands"></div>

## คำสั่งแบบกำหนดเอง

คำสั่งแบบกำหนดเองช่วยให้คุณขยาย CLI ของ Nylo ด้วยคำสั่งเฉพาะโปรเจกต์ของคุณเอง ฟีเจอร์นี้ช่วยให้คุณสามารถทำงานซ้ำๆ แบบอัตโนมัติ ใช้งาน workflow การ deploy หรือเพิ่มฟังก์ชันแบบกำหนดเองใดๆ เข้าไปในเครื่องมือ command-line ของโปรเจกต์โดยตรง

- [การสร้างคำสั่งแบบกำหนดเอง](#creating-custom-commands)
- [การรันคำสั่งแบบกำหนดเอง](#running-custom-commands)
- [การเพิ่ม options ให้คำสั่ง](#adding-options-to-custom-commands)
- [การเพิ่ม flags ให้คำสั่ง](#adding-flags-to-custom-commands)
- [เมธอดตัวช่วย](#custom-command-helper-methods)

> **หมายเหตุ:** ปัจจุบันคุณไม่สามารถ import nylo_framework.dart ในคำสั่งแบบกำหนดเอง กรุณาใช้ ny_cli.dart แทน

<div id="creating-custom-commands"></div>

## การสร้างคำสั่งแบบกำหนดเอง

เพื่อสร้างคำสั่งแบบกำหนดเองใหม่ คุณสามารถใช้ฟีเจอร์ `make:command`:

```bash
metro make:command current_time
```

คุณสามารถระบุหมวดหมู่สำหรับคำสั่งของคุณโดยใช้ option `--category`:

```bash
# ระบุหมวดหมู่
metro make:command current_time --category="project"
```

สิ่งนี้จะสร้างไฟล์คำสั่งใหม่ที่ `lib/app/commands/current_time.dart` ด้วยโครงสร้างต่อไปนี้:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // รับเวลาปัจจุบัน
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // จัดรูปแบบเวลาปัจจุบัน
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

คำสั่งจะถูกลงทะเบียนโดยอัตโนมัติในไฟล์ `lib/app/commands/commands.json` ซึ่งประกอบด้วยรายการคำสั่งทั้งหมดที่ลงทะเบียน:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## การรันคำสั่งแบบกำหนดเอง

เมื่อสร้างแล้ว คุณสามารถรันคำสั่งแบบกำหนดเองโดยใช้ Metro shorthand หรือคำสั่ง Dart เต็ม:

```bash
metro app:current_time
```

เมื่อคุณรัน `metro` โดยไม่มีอาร์กิวเมนต์ คุณจะเห็นคำสั่งแบบกำหนดเองแสดงอยู่ในเมนูภายใต้ส่วน "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

เพื่อแสดงข้อมูลช่วยเหลือสำหรับคำสั่งของคุณ ใช้ flag `--help` หรือ `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## การเพิ่ม Options ให้คำสั่ง

Options ช่วยให้คำสั่งของคุณรับ input เพิ่มเติมจากผู้ใช้ คุณสามารถเพิ่ม options ให้คำสั่งของคุณในเมธอด `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // เพิ่ม option พร้อมค่าเริ่มต้น
  command.addOption(
    'environment',     // ชื่อ option
    abbr: 'e',         // ตัวย่อ
    help: 'Target deployment environment', // ข้อความช่วยเหลือ
    defaultValue: 'development',  // ค่าเริ่มต้น
    allowed: ['development', 'staging', 'production'] // ค่าที่อนุญาต
  );

  return command;
}
```

จากนั้นเข้าถึงค่า option ในเมธอด `handle` ของคำสั่ง:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // การใช้งานคำสั่ง...
}
```

ตัวอย่างการใช้งาน:

```bash
metro project:deploy --environment=production
# หรือใช้ตัวย่อ
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## การเพิ่ม Flags ให้คำสั่ง

Flags เป็น options แบบ boolean ที่สามารถเปิดหรือปิดได้ เพิ่ม flags ให้คำสั่งของคุณโดยใช้เมธอด `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // ชื่อ flag
    abbr: 'v',       // ตัวย่อ
    help: 'Enable verbose output', // ข้อความช่วยเหลือ
    defaultValue: false  // ค่าเริ่มต้นเป็นปิด
  );

  return command;
}
```

จากนั้นตรวจสอบสถานะ flag ในเมธอด `handle` ของคำสั่ง:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // การ logging เพิ่มเติม...
  }

  // การใช้งานคำสั่ง...
}
```

ตัวอย่างการใช้งาน:

```bash
metro project:deploy --verbose
# หรือใช้ตัวย่อ
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## เมธอดตัวช่วย

คลาสฐาน `NyCustomCommand` มีเมธอดตัวช่วยหลายตัวเพื่อช่วยงานทั่วไป:

#### การพิมพ์ข้อความ

นี่คือเมธอดบางส่วนสำหรับพิมพ์ข้อความในสีต่างๆ:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | พิมพ์ข้อความ info ด้วยสีน้ำเงิน |
| [`error`](#custom-command-helper-formatting)     | พิมพ์ข้อความ error ด้วยสีแดง |
| [`success`](#custom-command-helper-formatting)   | พิมพ์ข้อความ success ด้วยสีเขียว |
| [`warning`](#custom-command-helper-formatting)   | พิมพ์ข้อความ warning ด้วยสีเหลือง |

#### การรัน Process

รัน process และแสดง output ในคอนโซล:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | เพิ่ม package ลง `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | เพิ่มหลาย package ลง `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | รัน process ภายนอกและแสดง output ในคอนโซล |
| [`prompt`](#custom-command-helper-prompt)    | รับ input จากผู้ใช้เป็นข้อความ |
| [`confirm`](#custom-command-helper-confirm)   | ถามคำถาม yes/no และคืนค่า boolean |
| [`select`](#custom-command-helper-select)    | แสดงรายการตัวเลือกให้ผู้ใช้เลือกหนึ่งรายการ |
| [`multiSelect`](#custom-command-helper-multi-select) | ให้ผู้ใช้เลือกหลายรายการจากรายการ |

#### Network Requests

การทำ network requests ผ่านคอนโซล:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | ทำ API call โดยใช้ Nylo API client |


#### Loading Spinner

แสดง loading spinner ขณะรันฟังก์ชัน:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | แสดง loading spinner ขณะรันฟังก์ชัน |
| [`createSpinner`](#manual-spinner-control) | สร้าง spinner instance สำหรับควบคุมแบบ manual |

#### ตัวช่วยคำสั่งแบบกำหนดเอง

คุณยังสามารถใช้เมธอดตัวช่วยต่อไปนี้เพื่อจัดการอาร์กิวเมนต์ของคำสั่ง:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | รับค่า string จากอาร์กิวเมนต์ของคำสั่ง |
| [`getBool`](#custom-command-helper-get-bool)   | รับค่า boolean จากอาร์กิวเมนต์ของคำสั่ง |
| [`getInt`](#custom-command-helper-get-int)    | รับค่า integer จากอาร์กิวเมนต์ของคำสั่ง |
| [`sleep`](#custom-command-helper-sleep) | หยุดการทำงานตามระยะเวลาที่กำหนด |


### การรัน External Processes

```dart
// รัน process พร้อมแสดง output ในคอนโซล
await runProcess('flutter build web --release');

// รัน process แบบเงียบ
await runProcess('flutter pub get', silent: true);

// รัน process ในไดเรกทอรีเฉพาะ
await runProcess('git pull', workingDirectory: './my-project');
```

### การจัดการ Package

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// เพิ่ม package ลง pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// เพิ่ม dev package ลง pubspec.yaml
addPackage('build_runner', dev: true);

// เพิ่มหลาย package พร้อมกัน
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### การจัดรูปแบบ Output

```dart
// พิมพ์ข้อความสถานะพร้อมรหัสสี
info('Processing files...');    // ข้อความสีน้ำเงิน
error('Operation failed');      // ข้อความสีแดง
success('Deployment complete'); // ข้อความสีเขียว
warning('Outdated package');    // ข้อความสีเหลือง
```

<div id="interactive-input-methods"></div>

## เมธอด Interactive Input

คลาสฐาน `NyCustomCommand` มีเมธอดหลายตัวสำหรับรับ input จากผู้ใช้ใน terminal เมธอดเหล่านี้ทำให้ง่ายต่อการสร้าง command-line interface แบบ interactive สำหรับคำสั่งแบบกำหนดเอง

<div id="custom-command-helper-prompt"></div>

### Text Input

```dart
String prompt(String question, {String defaultValue = ''})
```

แสดงคำถามให้ผู้ใช้และรับคำตอบเป็นข้อความ

**พารามิเตอร์:**
- `question`: คำถามหรือ prompt ที่จะแสดง
- `defaultValue`: ค่าเริ่มต้นที่เป็นตัวเลือกหากผู้ใช้กด Enter เลย

**คืนค่า:** input ของผู้ใช้เป็น string หรือค่าเริ่มต้นหากไม่มี input

**ตัวอย่าง:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### การยืนยัน

```dart
bool confirm(String question, {bool defaultValue = false})
```

ถามคำถาม yes/no ให้ผู้ใช้และคืนค่า boolean

**พารามิเตอร์:**
- `question`: คำถาม yes/no ที่จะถาม
- `defaultValue`: คำตอบเริ่มต้น (true สำหรับ yes, false สำหรับ no)

**คืนค่า:** `true` หากผู้ใช้ตอบ yes, `false` หากตอบ no

**ตัวอย่าง:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // ผู้ใช้ยืนยันหรือกด Enter (ยอมรับค่าเริ่มต้น)
  await runProcess('flutter pub get');
} else {
  // ผู้ใช้ปฏิเสธ
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### การเลือกรายการเดียว

```dart
String select(String question, List<String> options, {String? defaultOption})
```

แสดงรายการตัวเลือกให้ผู้ใช้เลือกหนึ่งรายการ

**พารามิเตอร์:**
- `question`: ข้อความ prompt สำหรับการเลือก
- `options`: รายการตัวเลือกที่มี
- `defaultOption`: ตัวเลือกเริ่มต้นที่เป็นตัวเลือก

**คืนค่า:** ตัวเลือกที่ถูกเลือกเป็น string

**ตัวอย่าง:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### การเลือกหลายรายการ

```dart
List<String> multiSelect(String question, List<String> options)
```

ให้ผู้ใช้เลือกหลายรายการจากรายการ

**พารามิเตอร์:**
- `question`: ข้อความ prompt สำหรับการเลือก
- `options`: รายการตัวเลือกที่มี

**คืนค่า:** รายการตัวเลือกที่ถูกเลือก

**ตัวอย่าง:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## เมธอดตัวช่วย API

เมธอดตัวช่วย `api` ทำให้การทำ network requests จากคำสั่งแบบกำหนดเองง่ายขึ้น

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## ตัวอย่างการใช้งานเบื้องต้น

### GET Request

```dart
// ดึงข้อมูล
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST Request

```dart
// สร้าง resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT Request

```dart
// อัปเดต resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE Request

```dart
// ลบ resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH Request

```dart
// อัปเดต resource บางส่วน
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### พร้อม Query Parameters

```dart
// เพิ่ม query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### พร้อม Spinner

```dart
// ใช้กับ spinner เพื่อ UI ที่ดีขึ้น
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // ประมวลผลข้อมูล
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## ฟังก์ชัน Spinner

Spinner ให้ feedback ที่มองเห็นได้ระหว่างการดำเนินการที่ใช้เวลานานในคำสั่งแบบกำหนดเอง มันแสดงตัวบอกแบบ animated พร้อมข้อความขณะที่คำสั่งรัน task แบบ asynchronous ช่วยเพิ่มประสบการณ์ผู้ใช้โดยแสดงความคืบหน้าและสถานะ

- [การใช้ with spinner](#using-with-spinner)
- [การควบคุม spinner แบบ manual](#manual-spinner-control)
- [ตัวอย่าง](#spinner-examples)

<div id="using-with-spinner"></div>

## การใช้ with spinner

เมธอด `withSpinner` ให้คุณครอบ task แบบ asynchronous ด้วย animation spinner ที่เริ่มโดยอัตโนมัติเมื่อ task เริ่มและหยุดเมื่อเสร็จสมบูรณ์หรือล้มเหลว:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**พารามิเตอร์:**
- `task`: ฟังก์ชัน asynchronous ที่จะรัน
- `message`: ข้อความที่แสดงขณะ spinner กำลังทำงาน
- `successMessage`: ข้อความที่เป็นตัวเลือกที่จะแสดงเมื่อเสร็จสมบูรณ์
- `errorMessage`: ข้อความที่เป็นตัวเลือกที่จะแสดงหาก task ล้มเหลว

**คืนค่า:** ผลลัพธ์ของฟังก์ชัน task

**ตัวอย่าง:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // รัน task พร้อม spinner
  final projectFiles = await withSpinner(
    task: () async {
      // task ที่ใช้เวลานาน (เช่น วิเคราะห์ไฟล์โปรเจกต์)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // ดำเนินการกับผลลัพธ์
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## การควบคุม Spinner แบบ Manual

สำหรับสถานการณ์ที่ซับซ้อนมากขึ้นที่คุณต้องควบคุม state ของ spinner แบบ manual คุณสามารถสร้าง spinner instance:

```dart
ConsoleSpinner createSpinner(String message)
```

**พารามิเตอร์:**
- `message`: ข้อความที่แสดงขณะ spinner กำลังทำงาน

**คืนค่า:** instance `ConsoleSpinner` ที่คุณสามารถควบคุมแบบ manual

**ตัวอย่างการควบคุมแบบ manual:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // สร้าง spinner instance
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // task แรก
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // task ที่สอง
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // task ที่สาม
    await runProcess('./deploy.sh', silent: true);

    // เสร็จสมบูรณ์
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // จัดการความล้มเหลว
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## ตัวอย่าง

### Task แบบง่ายพร้อม Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // ติดตั้ง dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### การดำเนินการต่อเนื่องหลายรายการ

```dart
@override
Future<void> handle(CommandResult result) async {
  // การดำเนินการแรกพร้อม spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // การดำเนินการที่สองพร้อม spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // การดำเนินการที่สามพร้อม spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Workflow ที่ซับซ้อนพร้อมการควบคุมแบบ Manual

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // รันหลายขั้นตอนพร้อมอัปเดตสถานะ
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // เสร็จสิ้น process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

การใช้ spinner ในคำสั่งแบบกำหนดเองให้ feedback ที่มองเห็นได้ชัดเจนแก่ผู้ใช้ระหว่างการดำเนินการที่ใช้เวลานาน สร้างประสบการณ์ command-line ที่สวยงามและเป็นมืออาชีพมากขึ้น

<div id="custom-command-helper-get-string"></div>

### รับค่า string จาก options

```dart
String getString(String name, {String defaultValue = ''})
```

**พารามิเตอร์:**

- `name`: ชื่อของ option ที่จะดึง
- `defaultValue`: ค่าเริ่มต้นที่เป็นตัวเลือกหาก option ไม่ได้ถูกระบุ

**คืนค่า:** ค่าของ option เป็น string

**ตัวอย่าง:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### รับค่า bool จาก options

```dart
bool getBool(String name, {bool defaultValue = false})
```

**พารามิเตอร์:**
- `name`: ชื่อของ option ที่จะดึง
- `defaultValue`: ค่าเริ่มต้นที่เป็นตัวเลือกหาก option ไม่ได้ถูกระบุ

**คืนค่า:** ค่าของ option เป็น boolean


**ตัวอย่าง:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### รับค่า int จาก options

```dart
int getInt(String name, {int defaultValue = 0})
```

**พารามิเตอร์:**
- `name`: ชื่อของ option ที่จะดึง
- `defaultValue`: ค่าเริ่มต้นที่เป็นตัวเลือกหาก option ไม่ได้ถูกระบุ

**คืนค่า:** ค่าของ option เป็น integer

**ตัวอย่าง:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### หยุดตามระยะเวลาที่กำหนด

```dart
void sleep(int seconds)
```

**พารามิเตอร์:**
- `seconds`: จำนวนวินาทีที่จะหยุด

**คืนค่า:** ไม่มี

**ตัวอย่าง:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## การจัดรูปแบบ Output

นอกเหนือจากเมธอด `info`, `error`, `success` และ `warning` เบื้องต้น `NyCustomCommand` ยังมีตัวช่วย output เพิ่มเติม:

```dart
@override
Future<void> handle(CommandResult result) async {
  // พิมพ์ข้อความธรรมดา (ไม่มีสี)
  line('Processing your request...');

  // พิมพ์บรรทัดว่าง
  newLine();       // หนึ่งบรรทัดว่าง
  newLine(3);      // สามบรรทัดว่าง

  // พิมพ์ comment แบบ muted (ข้อความสีเทา)
  comment('This is a background note');

  // พิมพ์กล่อง alert ที่โดดเด่น
  alert('Important: Please read carefully');

  // Ask เป็น alias สำหรับ prompt
  final name = ask('What is your name?');

  // input ที่ซ่อนสำหรับข้อมูลที่ sensitive (เช่น passwords, API keys)
  final apiKey = promptSecret('Enter your API key:');

  // ยกเลิกคำสั่งด้วยข้อความ error และ exit code
  if (name.isEmpty) {
    abort('Name is required');  // ออกด้วย code 1
  }
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `line(String message)` | พิมพ์ข้อความธรรมดาไม่มีสี |
| `newLine([int count = 1])` | พิมพ์บรรทัดว่าง |
| `comment(String message)` | พิมพ์ข้อความ muted/สีเทา |
| `alert(String message)` | พิมพ์กล่อง alert ที่โดดเด่น |
| `ask(String question, {String defaultValue})` | Alias สำหรับ `prompt` |
| `promptSecret(String question)` | input ที่ซ่อนสำหรับข้อมูลที่ sensitive |
| `abort([String? message, int exitCode = 1])` | ออกจากคำสั่งด้วย error |

<div id="file-system-helpers"></div>

## ตัวช่วยระบบไฟล์

`NyCustomCommand` มีตัวช่วยระบบไฟล์ในตัวเพื่อให้คุณไม่ต้อง import `dart:io` ด้วยตนเองสำหรับการดำเนินการทั่วไป

### การอ่านและเขียนไฟล์

```dart
@override
Future<void> handle(CommandResult result) async {
  // ตรวจสอบว่าไฟล์มีอยู่หรือไม่
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // ตรวจสอบว่าไดเรกทอรีมีอยู่หรือไม่
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // อ่านไฟล์ (async)
  String content = await readFile('pubspec.yaml');

  // อ่านไฟล์ (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // เขียนลงไฟล์ (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // เขียนลงไฟล์ (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // เพิ่มเนื้อหาต่อท้ายไฟล์
  await appendFile('log.txt', 'New log entry\n');

  // ตรวจสอบให้แน่ใจว่าไดเรกทอรีมีอยู่ (สร้างหากไม่มี)
  await ensureDirectory('lib/generated');

  // ลบไฟล์
  await deleteFile('lib/generated/output.dart');

  // คัดลอกไฟล์
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `fileExists(String path)` | คืนค่า `true` หากไฟล์มีอยู่ |
| `directoryExists(String path)` | คืนค่า `true` หากไดเรกทอรีมีอยู่ |
| `readFile(String path)` | อ่านไฟล์เป็น string (async) |
| `readFileSync(String path)` | อ่านไฟล์เป็น string (sync) |
| `writeFile(String path, String content)` | เขียนเนื้อหาลงไฟล์ (async) |
| `writeFileSync(String path, String content)` | เขียนเนื้อหาลงไฟล์ (sync) |
| `appendFile(String path, String content)` | เพิ่มเนื้อหาต่อท้ายไฟล์ |
| `ensureDirectory(String path)` | สร้างไดเรกทอรีหากไม่มี |
| `deleteFile(String path)` | ลบไฟล์ |
| `copyFile(String source, String destination)` | คัดลอกไฟล์ |

<div id="json-yaml-helpers"></div>

## ตัวช่วย JSON และ YAML

อ่านและเขียนไฟล์ JSON และ YAML ด้วยตัวช่วยในตัว

```dart
@override
Future<void> handle(CommandResult result) async {
  // อ่านไฟล์ JSON เป็น Map
  Map<String, dynamic> config = await readJson('config.json');

  // อ่านไฟล์ JSON เป็น List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // เขียนข้อมูลลงไฟล์ JSON (pretty printed เป็นค่าเริ่มต้น)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // เขียน JSON แบบกระชับ
  await writeJson('output.json', data, pretty: false);

  // เพิ่มรายการลงไฟล์ JSON array
  // หากไฟล์ประกอบด้วย [{"name": "a"}] จะเพิ่มเข้าไปใน array นั้น
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // ป้องกันการซ้ำตาม key นี้
  );

  // อ่านไฟล์ YAML เป็น Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `readJson(String path)` | อ่านไฟล์ JSON เป็น `Map<String, dynamic>` |
| `readJsonArray(String path)` | อ่านไฟล์ JSON เป็น `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | เขียนข้อมูลเป็น JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | เพิ่มลงไฟล์ JSON array |
| `readYaml(String path)` | อ่านไฟล์ YAML เป็น `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## ตัวช่วยแปลง Case

แปลง string ระหว่าง naming conventions โดยไม่ต้อง import package `recase`

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| เมธอด | รูปแบบ Output | ตัวอย่าง |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## ตัวช่วย Path โปรเจกต์

Getters สำหรับไดเรกทอรีมาตรฐานของโปรเจกต์ {{ config('app.name') }} คืนค่า path สัมพัทธ์กับ root ของโปรเจกต์

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // สร้าง path แบบกำหนดเองสัมพัทธ์กับ root ของโปรเจกต์
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| คุณสมบัติ | Path |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | แปลง path สัมพัทธ์ภายในโปรเจกต์ |

<div id="platform-helpers"></div>

## ตัวช่วย Platform

ตรวจสอบ platform และเข้าถึง environment variables

```dart
@override
Future<void> handle(CommandResult result) async {
  // ตรวจสอบ platform
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // ไดเรกทอรีทำงานปัจจุบัน
  info('Working in: $workingDirectory');

  // อ่าน system environment variables
  String home = env('HOME', '/default/path');
}
```

| คุณสมบัติ / เมธอด | คำอธิบาย |
|-------------------|-------------|
| `isWindows` | `true` หากรันบน Windows |
| `isMacOS` | `true` หากรันบน macOS |
| `isLinux` | `true` หากรันบน Linux |
| `workingDirectory` | path ไดเรกทอรีทำงานปัจจุบัน |
| `env(String key, [String defaultValue = ''])` | อ่าน system environment variable |

<div id="dart-flutter-commands"></div>

## คำสั่ง Dart และ Flutter

รันคำสั่ง Dart และ Flutter CLI ทั่วไปเป็นเมธอดตัวช่วย แต่ละตัวคืนค่า process exit code

```dart
@override
Future<void> handle(CommandResult result) async {
  // จัดรูปแบบไฟล์หรือไดเรกทอรี Dart
  await dartFormat('lib/app/models/user.dart');

  // รัน dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // รัน flutter pub get
  await flutterPubGet();

  // รัน flutter clean
  await flutterClean();

  // Build สำหรับเป้าหมายพร้อม args เพิ่มเติม
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // รัน flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // ไดเรกทอรีเฉพาะ
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `dartFormat(String path)` | รัน `dart format` บนไฟล์หรือไดเรกทอรี |
| `dartAnalyze([String? path])` | รัน `dart analyze` |
| `flutterPubGet()` | รัน `flutter pub get` |
| `flutterClean()` | รัน `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | รัน `flutter build <target>` |
| `flutterTest([String? path])` | รัน `flutter test` |

<div id="dart-file-manipulation"></div>

## การจัดการไฟล์ Dart

ตัวช่วยสำหรับการแก้ไขไฟล์ Dart แบบ programmatic มีประโยชน์เมื่อสร้างเครื่องมือสร้างโครง

```dart
@override
Future<void> handle(CommandResult result) async {
  // เพิ่ม import statement ลงไฟล์ Dart (หลีกเลี่ยงการซ้ำ)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // แทรกโค้ดก่อนวงเล็บปิดสุดท้ายในไฟล์
  // มีประโยชน์สำหรับการเพิ่มรายการลง registration maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // ตรวจสอบว่าไฟล์ประกอบด้วย string เฉพาะ
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // ตรวจสอบว่าไฟล์ตรงกับ regex pattern
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | เพิ่ม import ลงไฟล์ Dart (ข้ามหากมีอยู่แล้ว) |
| `insertBeforeClosingBrace(String filePath, String code)` | แทรกโค้ดก่อน `}` สุดท้ายในไฟล์ |
| `fileContains(String filePath, String identifier)` | ตรวจสอบว่าไฟล์ประกอบด้วย string |
| `fileContainsPattern(String filePath, Pattern pattern)` | ตรวจสอบว่าไฟล์ตรงกับ pattern |

<div id="directory-helpers"></div>

## ตัวช่วยไดเรกทอรี

ตัวช่วยสำหรับการทำงานกับไดเรกทอรีและการค้นหาไฟล์

```dart
@override
Future<void> handle(CommandResult result) async {
  // แสดงเนื้อหาไดเรกทอรี
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // แสดงแบบ recursive
  var allEntities = listDirectory('lib/', recursive: true);

  // ค้นหาไฟล์ที่ตรงตามเกณฑ์
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // ค้นหาไฟล์ตามรูปแบบชื่อ
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // ลบไดเรกทอรีแบบ recursive
  await deleteDirectory('build/');

  // คัดลอกไดเรกทอรี (recursive)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | แสดงเนื้อหาไดเรกทอรี |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | ค้นหาไฟล์ที่ตรงตามเกณฑ์ |
| `deleteDirectory(String path)` | ลบไดเรกทอรีแบบ recursive |
| `copyDirectory(String source, String destination)` | คัดลอกไดเรกทอรีแบบ recursive |

<div id="validation-helpers"></div>

## ตัวช่วยการตรวจสอบ

ตัวช่วยสำหรับการตรวจสอบและทำความสะอาด input ของผู้ใช้สำหรับการสร้างโค้ด

```dart
@override
Future<void> handle(CommandResult result) async {
  // ตรวจสอบ Dart identifier
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // ต้องการอาร์กิวเมนต์แรกที่ไม่ว่าง
  String name = requireArgument(result, message: 'Please provide a name');

  // ทำความสะอาดชื่อคลาส (PascalCase ลบ suffixes)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // คืนค่า: 'User'

  // ทำความสะอาดชื่อไฟล์ (snake_case พร้อมนามสกุล)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // คืนค่า: 'user_model.dart'
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `isValidDartIdentifier(String name)` | ตรวจสอบชื่อ Dart identifier |
| `requireArgument(CommandResult result, {String? message})` | ต้องการอาร์กิวเมนต์แรกที่ไม่ว่างหรือ abort |
| `cleanClassName(String name, {List<String> removeSuffixes})` | ทำความสะอาดและ PascalCase ชื่อคลาส |
| `cleanFileName(String name, {String extension = '.dart'})` | ทำความสะอาดและ snake_case ชื่อไฟล์ |

<div id="file-scaffolding"></div>

## การสร้างโครงไฟล์

สร้างหนึ่งหรือหลายไฟล์พร้อมเนื้อหาโดยใช้ระบบสร้างโครง

### ไฟล์เดียว

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // ไม่เขียนทับหากมีอยู่
    successMessage: 'AuthService created',
  );
}
```

### หลายไฟล์

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

คลาส `ScaffoldFile` รับ:

| คุณสมบัติ | ชนิด | คำอธิบาย |
|----------|------|-------------|
| `path` | `String` | path ของไฟล์ที่จะสร้าง |
| `content` | `String` | เนื้อหาไฟล์ |
| `successMessage` | `String?` | ข้อความที่แสดงเมื่อสำเร็จ |

<div id="task-runner"></div>

## Task Runner

รันชุดของ task ที่มีชื่อพร้อม output สถานะอัตโนมัติ

### Task Runner เบื้องต้น

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // หยุด pipeline หาก task นี้ล้มเหลว (ค่าเริ่มต้น)
    ),
  ]);
}
```

### Task Runner พร้อม Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

คลาส `CommandTask` รับ:

| คุณสมบัติ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `name` | `String` | จำเป็น | ชื่อ task ที่แสดงใน output |
| `action` | `Future<void> Function()` | จำเป็น | ฟังก์ชัน async ที่จะรัน |
| `stopOnError` | `bool` | `true` | หยุด task ที่เหลือหาก task นี้ล้มเหลวหรือไม่ |

<div id="table-output"></div>

## การแสดงผลแบบตาราง

แสดงตาราง ASCII ที่จัดรูปแบบแล้วในคอนโซล

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Output:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## แถบความคืบหน้า

แสดงแถบความคืบหน้าสำหรับการดำเนินการที่ทราบจำนวนรายการ

### แถบความคืบหน้าแบบ Manual

```dart
@override
Future<void> handle(CommandResult result) async {
  // สร้างแถบความคืบหน้าสำหรับ 100 รายการ
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // เพิ่มทีละ 1
  }

  progress.complete('All files processed');
}
```

### ประมวลผลรายการพร้อมความคืบหน้า

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // ประมวลผลรายการพร้อมการติดตามความคืบหน้าอัตโนมัติ
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // ประมวลผลแต่ละไฟล์
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### ความคืบหน้าแบบ Synchronous

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // การประมวลผลแบบ synchronous
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

คลาส `ConsoleProgressBar` มี:

| เมธอด | คำอธิบาย |
|--------|-------------|
| `start()` | เริ่มแถบความคืบหน้า |
| `tick([int amount = 1])` | เพิ่มความคืบหน้า |
| `update(int value)` | ตั้งค่าความคืบหน้าเป็นค่าเฉพาะ |
| `updateMessage(String newMessage)` | เปลี่ยนข้อความที่แสดง |
| `complete([String? completionMessage])` | เสร็จสมบูรณ์พร้อมข้อความที่เป็นตัวเลือก |
| `stop()` | หยุดโดยไม่เสร็จสมบูรณ์ |
| `current` | ค่าความคืบหน้าปัจจุบัน (getter) |
| `percentage` | ความคืบหน้าเป็นเปอร์เซ็นต์ (getter) |
