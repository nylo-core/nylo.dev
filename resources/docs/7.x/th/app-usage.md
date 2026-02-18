# App Usage

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การตั้งค่า](#setup "การตั้งค่าการติดตามการใช้งานแอป")
- การตรวจสอบ
    - [จำนวนครั้งที่เปิดแอป](#monitoring-app-launches "การตรวจสอบจำนวนครั้งที่เปิดแอป")
    - [วันที่เปิดแอปครั้งแรก](#monitoring-app-first-launch-date "การตรวจสอบวันที่เปิดแอปครั้งแรก")
    - [จำนวนวันทั้งหมดนับจากการเปิดแอปครั้งแรก](#monitoring-app-total-days-since-first-launch "การตรวจสอบจำนวนวันทั้งหมดนับจากการเปิดแอปครั้งแรก")

<div id="introduction"></div>

## บทนำ

Nylo ช่วยให้คุณตรวจสอบการใช้งานแอปได้ทันทีที่ติดตั้ง แต่ก่อนอื่นคุณต้องเปิดใช้งานฟีเจอร์นี้ใน app provider ตัวใดตัวหนึ่ง

ปัจจุบัน Nylo สามารถตรวจสอบสิ่งต่อไปนี้ได้:

- จำนวนครั้งที่เปิดแอป
- วันที่เปิดแอปครั้งแรก

หลังจากอ่านเอกสารนี้ คุณจะเรียนรู้วิธีการตรวจสอบการใช้งานแอปของคุณ

<div id="setup"></div>

## การตั้งค่า

เปิดไฟล์ `app/providers/app_provider.dart` ของคุณ

จากนั้นเพิ่มโค้ดต่อไปนี้ในเมธอด `boot` ของคุณ

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

นี่จะเปิดใช้งานการตรวจสอบการใช้งานแอปในแอปของคุณ หากคุณต้องการตรวจสอบว่าการตรวจสอบการใช้งานแอปเปิดใช้งานอยู่หรือไม่ คุณสามารถใช้เมธอด `Nylo.instance.shouldMonitorAppUsage()`

<div id="monitoring-app-launches"></div>

## การตรวจสอบจำนวนครั้งที่เปิดแอป

คุณสามารถตรวจสอบจำนวนครั้งที่แอปถูกเปิดได้โดยใช้เมธอด `Nylo.appLaunchCount`

> จำนวนครั้งที่เปิดแอปจะนับทุกครั้งที่แอปถูกเปิดจากสถานะปิดสนิท

ตัวอย่างง่ายๆ ของวิธีใช้เมธอดนี้:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## การตรวจสอบวันที่เปิดแอปครั้งแรก

คุณสามารถตรวจสอบวันที่แอปถูกเปิดครั้งแรกได้โดยใช้เมธอด `Nylo.appFirstLaunchDate`

นี่คือตัวอย่างวิธีใช้เมธอดนี้:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## การตรวจสอบจำนวนวันทั้งหมดนับจากการเปิดแอปครั้งแรก

คุณสามารถตรวจสอบจำนวนวันทั้งหมดนับจากวันที่แอปถูกเปิดครั้งแรกได้โดยใช้เมธอด `Nylo.appTotalDaysSinceFirstLaunch`

นี่คือตัวอย่างวิธีใช้เมธอดนี้:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
