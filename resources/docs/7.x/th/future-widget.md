# FutureWidget

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานพื้นฐาน](#basic-usage "การใช้งานพื้นฐาน")
- [การปรับแต่งสถานะการโหลด](#customizing-loading "การปรับแต่งสถานะการโหลด")
    - [รูปแบบการโหลดปกติ](#normal-loading "รูปแบบการโหลดปกติ")
    - [รูปแบบการโหลด Skeletonizer](#skeletonizer-loading "รูปแบบการโหลด Skeletonizer")
    - [ไม่มีรูปแบบการโหลด](#no-loading "ไม่มีรูปแบบการโหลด")
- [การจัดการข้อผิดพลาด](#error-handling "การจัดการข้อผิดพลาด")


<div id="introduction"></div>

## บทนำ

**FutureWidget** เป็นวิธีง่ายๆ ในการเรนเดอร์ `Future` ในโปรเจกต์ {{ config('app.name') }} ของคุณ มันครอบ `FutureBuilder` ของ Flutter และให้ API ที่สะอาดขึ้นพร้อมสถานะการโหลดในตัว

เมื่อ Future ของคุณกำลังทำงาน จะแสดงตัวโหลด เมื่อ Future เสร็จสมบูรณ์ ข้อมูลจะถูกส่งกลับผ่าน callback `child`

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

นี่คือตัวอย่างง่ายๆ ของการใช้ `FutureWidget`:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

widget จะจัดการสถานะการโหลดโดยอัตโนมัติสำหรับผู้ใช้ของคุณจนกว่า Future จะเสร็จสมบูรณ์

<div id="customizing-loading"></div>

## การปรับแต่งสถานะการโหลด

คุณสามารถปรับแต่งลักษณะของสถานะการโหลดได้โดยใช้พารามิเตอร์ `loadingStyle`

<div id="normal-loading"></div>

### รูปแบบการโหลดปกติ

ใช้ `LoadingStyle.normal()` เพื่อแสดง widget การโหลดมาตรฐาน คุณสามารถเลือกที่จะกำหนด widget ลูกได้:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

หากไม่ได้กำหนด child จะแสดงตัวโหลดเริ่มต้นของแอป {{ config('app.name') }}

<div id="skeletonizer-loading"></div>

### รูปแบบการโหลด Skeletonizer

ใช้ `LoadingStyle.skeletonizer()` เพื่อแสดงเอฟเฟกต์การโหลดแบบโครงร่าง เหมาะสำหรับการแสดง UI ตัวแทนที่ตรงกับเลย์เอาต์เนื้อหาของคุณ:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

เอฟเฟกต์โครงร่างที่มีให้เลือก:
- `SkeletonizerEffect.shimmer` - เอฟเฟกต์แสงวิบวับแบบเคลื่อนไหว (ค่าเริ่มต้น)
- `SkeletonizerEffect.pulse` - เอฟเฟกต์แอนิเมชันแบบเต้นเป็นจังหวะ
- `SkeletonizerEffect.solid` - เอฟเฟกต์สีทึบ

<div id="no-loading"></div>

### ไม่มีรูปแบบการโหลด

ใช้ `LoadingStyle.none()` เพื่อซ่อนตัวบ่งชี้การโหลดทั้งหมด:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## การจัดการข้อผิดพลาด

คุณสามารถจัดการข้อผิดพลาดจาก Future ได้โดยใช้ callback `onError`:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

หากไม่ได้กำหนด callback `onError` และเกิดข้อผิดพลาด จะแสดง `SizedBox.shrink()` ว่างเปล่า

### พารามิเตอร์

| พารามิเตอร์ | ประเภท | คำอธิบาย |
|-----------|------|-------------|
| `future` | `Future<T>?` | Future ที่ต้องรอ |
| `child` | `Widget Function(BuildContext, T?)` | ฟังก์ชันตัวสร้างที่เรียกเมื่อ Future เสร็จสมบูรณ์ |
| `loadingStyle` | `LoadingStyle?` | ปรับแต่งตัวบ่งชี้การโหลด |
| `onError` | `Widget Function(AsyncSnapshot)?` | ฟังก์ชันตัวสร้างที่เรียกเมื่อ Future มีข้อผิดพลาด |
