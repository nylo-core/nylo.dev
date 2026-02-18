# Button

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
- [ประเภทปุ่มที่มีให้ใช้งาน](#button-types "ประเภทปุ่มที่มีให้ใช้งาน")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [สถานะโหลดแบบ Async](#async-loading "สถานะโหลดแบบ Async")
- [สไตล์แอนิเมชัน](#animation-styles "สไตล์แอนิเมชัน")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [สไตล์ Splash](#splash-styles "สไตล์ Splash")
- [สไตล์การโหลด](#loading-styles "สไตล์การโหลด")
- [การส่งฟอร์ม](#form-submission "การส่งฟอร์ม")
- [การปรับแต่งปุ่ม](#customizing-buttons "การปรับแต่งปุ่ม")
- [พารามิเตอร์อ้างอิง](#parameters "พารามิเตอร์อ้างอิง")


<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มีคลาส `Button` พร้อมสไตล์ปุ่มสำเร็จรูป 8 แบบ แต่ละปุ่มมาพร้อมกับการรองรับในตัวสำหรับ:

- **สถานะโหลดแบบ async** — คืนค่า `Future` จาก `onPressed` แล้วปุ่มจะแสดงตัวบ่งชี้การโหลดโดยอัตโนมัติ
- **สไตล์แอนิเมชัน** — เลือกจากเอฟเฟกต์ clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph และ shake
- **สไตล์ Splash** — เพิ่ม ripple, highlight, glow หรือ ink สำหรับการตอบสนองการสัมผัส
- **การส่งฟอร์ม** — เชื่อมต่อปุ่มโดยตรงกับ instance ของ `NyFormData`

คุณสามารถค้นหาคำจำกัดความของปุ่มในแอปของคุณได้ที่ `lib/resources/widgets/buttons/buttons.dart` ไฟล์นี้มีคลาส `Button` พร้อมเมธอดแบบ static สำหรับแต่ละประเภทปุ่ม ทำให้ง่ายต่อการปรับแต่งค่าเริ่มต้นสำหรับโปรเจกต์ของคุณ

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

ใช้คลาส `Button` ได้ทุกที่ใน widget ของคุณ นี่คือตัวอย่างง่ายๆ ภายในหน้าเพจ:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

ทุกประเภทปุ่มใช้รูปแบบเดียวกัน — ส่ง `text` เป็นป้ายชื่อและ callback `onPressed`

<div id="button-types"></div>

## ประเภทปุ่มที่มีให้ใช้งาน

ปุ่มทั้งหมดเข้าถึงได้ผ่านคลาส `Button` โดยใช้เมธอดแบบ static

<div id="primary"></div>

### Primary

ปุ่มแบบเติมสีพร้อมเงา ใช้สีหลักของธีม เหมาะสำหรับองค์ประกอบ call-to-action หลัก

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

ปุ่มแบบเติมสีด้วยสีพื้นผิวที่อ่อนกว่าและเงาที่ละเอียดอ่อน เหมาะสำหรับการกระทำรองควบคู่กับปุ่ม primary

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

ปุ่มโปร่งใสพร้อมเส้นขอบ เหมาะสำหรับการกระทำที่ไม่โดดเด่นมากหรือปุ่มยกเลิก

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

คุณสามารถปรับแต่งสีเส้นขอบและสีข้อความได้:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

ปุ่มแบบน้อยชิ้นไม่มีพื้นหลังหรือเส้นขอบ เหมาะสำหรับการกระทำแบบอินไลน์หรือลิงก์

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

คุณสามารถปรับแต่งสีข้อความได้:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

ปุ่มแบบเติมสีที่แสดงไอคอนควบคู่กับข้อความ ไอคอนจะปรากฏก่อนข้อความตามค่าเริ่มต้น

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

คุณสามารถปรับแต่งสีพื้นหลังได้:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

ปุ่มที่มีพื้นหลังแบบ linear gradient ใช้สี primary และ tertiary ของธีมตามค่าเริ่มต้น

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

คุณสามารถกำหนดสี gradient แบบกำหนดเองได้:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

ปุ่มรูปทรงยาวรีพร้อมมุมโค้งมนเต็มที่ border radius ตามค่าเริ่มต้นจะเป็นครึ่งหนึ่งของความสูงปุ่ม

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

คุณสามารถปรับแต่งสีพื้นหลังและ border radius ได้:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

ปุ่มสไตล์กระจกฝ้าพร้อมเอฟเฟกต์ backdrop blur ทำงานได้ดีเมื่อวางบนรูปภาพหรือพื้นหลังที่มีสี

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

คุณสามารถปรับแต่งสีข้อความได้:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## สถานะโหลดแบบ Async

หนึ่งในฟีเจอร์ที่ทรงพลังที่สุดของปุ่ม {{ config('app.name') }} คือ **การจัดการสถานะโหลดอัตโนมัติ** เมื่อ callback `onPressed` ของคุณคืนค่า `Future` ปุ่มจะแสดงตัวบ่งชี้การโหลดและปิดการโต้ตอบโดยอัตโนมัติจนกว่าการดำเนินการจะเสร็จสิ้น

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

ขณะที่การดำเนินการ async กำลังทำงาน ปุ่มจะแสดงเอฟเฟกต์โหลดแบบ skeleton (ตามค่าเริ่มต้น) เมื่อ `Future` เสร็จสิ้น ปุ่มจะกลับสู่สถานะปกติ

ใช้ได้กับการดำเนินการ async ใดก็ได้ — การเรียก API, การเขียนฐานข้อมูล, การอัปโหลดไฟล์ หรืออะไรก็ตามที่คืนค่า `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

ไม่จำเป็นต้องจัดการตัวแปรสถานะ `isLoading` เรียก `setState` หรือครอบอะไรใน `StatefulWidget` — {{ config('app.name') }} จัดการทุกอย่างให้คุณ

### วิธีการทำงาน

เมื่อปุ่มตรวจพบว่า `onPressed` คืนค่า `Future` จะใช้กลไก `lockRelease` เพื่อ:

1. แสดงตัวบ่งชี้การโหลด (ควบคุมโดย `LoadingStyle`)
2. ปิดการใช้งานปุ่มเพื่อป้องกันการแตะซ้ำ
3. รอจนกว่า `Future` จะเสร็จสิ้น
4. คืนค่าปุ่มกลับสู่สถานะปกติ

<div id="animation-styles"></div>

## สไตล์แอนิเมชัน

ปุ่มรองรับแอนิเมชันการกดผ่าน `ButtonAnimationStyle` แอนิเมชันเหล่านี้ให้การตอบสนองทางภาพเมื่อผู้ใช้โต้ตอบกับปุ่ม คุณสามารถตั้งค่าสไตล์แอนิเมชันเมื่อปรับแต่งปุ่มใน `lib/resources/widgets/buttons/buttons.dart`

<div id="anim-clickable"></div>

### Clickable

เอฟเฟกต์การกดแบบ 3D สไตล์ Duolingo ปุ่มจะเลื่อนลงเมื่อกดและสปริงกลับเมื่อปล่อย เหมาะสำหรับการกระทำหลักและ UX แบบเกม

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

ย่อขนาดปุ่มลงเมื่อกดและสปริงกลับเมื่อปล่อย เหมาะสำหรับปุ่มเพิ่มลงตะกร้า ถูกใจ และรายการโปรด

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

เอฟเฟกต์ขยาย-หดขนาดอย่างต่อเนื่องขณะกดปุ่มค้างไว้ เหมาะสำหรับการกระทำแบบกดค้างหรือการดึงดูดความสนใจ

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

บีบอัดปุ่มในแนวนอนและขยายในแนวตั้งเมื่อกด เหมาะสำหรับ UI ที่สนุกสนานและโต้ตอบได้

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

เอฟเฟกต์การเปลี่ยนรูปแบบยืดหยุ่นสั่นไหว เหมาะสำหรับแอปที่สนุก ไม่เป็นทางการ หรือบันเทิง

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

เอฟเฟกต์ไฮไลท์เงาวาวที่กวาดผ่านปุ่มเมื่อกด เหมาะสำหรับฟีเจอร์ premium หรือ CTA ที่คุณต้องการดึงดูดความสนใจ

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

เอฟเฟกต์ ripple ที่ปรับปรุงแล้วขยายออกจากจุดสัมผัส เหมาะสำหรับการเน้นแบบ Material Design

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

border radius ของปุ่มเพิ่มขึ้นเมื่อกด สร้างเอฟเฟกต์การเปลี่ยนรูปทรง เหมาะสำหรับการตอบสนองที่ละเอียดอ่อนและสวยงาม

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

แอนิเมชันสั่นในแนวนอน เหมาะสำหรับสถานะข้อผิดพลาดหรือการกระทำที่ไม่ถูกต้อง — สั่นปุ่มเพื่อส่งสัญญาณว่ามีบางอย่างผิดพลาด

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

ปรับแต่งเอฟเฟกต์อย่างละเอียด:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### ปิดแอนิเมชัน

เพื่อใช้ปุ่มโดยไม่มีแอนิเมชัน:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### เปลี่ยนแอนิเมชันเริ่มต้น

เพื่อเปลี่ยนแอนิเมชันเริ่มต้นสำหรับประเภทปุ่ม แก้ไขไฟล์ `lib/resources/widgets/buttons/buttons.dart` ของคุณ:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## สไตล์ Splash

เอฟเฟกต์ Splash ให้การตอบสนองทางภาพเมื่อสัมผัสบนปุ่ม กำหนดค่าผ่าน `ButtonSplashStyle` สไตล์ Splash สามารถรวมกับสไตล์แอนิเมชันสำหรับการตอบสนองแบบหลายชั้นได้

### สไตล์ Splash ที่มีให้ใช้งาน

| Splash | Factory | คำอธิบาย |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Ripple แบบ Material มาตรฐานจากจุดสัมผัส |
| Highlight | `ButtonSplashStyle.highlight()` | ไฮไลท์อ่อนๆ โดยไม่มีแอนิเมชัน ripple |
| Glow | `ButtonSplashStyle.glow()` | แสงเรืองอ่อนๆ จากจุดสัมผัส |
| Ink | `ButtonSplashStyle.ink()` | Splash หมึกเร็ว ตอบสนองไวกว่า |
| None | `ButtonSplashStyle.none()` | ไม่มีเอฟเฟกต์ splash |
| Custom | `ButtonSplashStyle.custom()` | ควบคุม splash factory ได้อย่างเต็มที่ |

### ตัวอย่าง

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

คุณสามารถปรับแต่งสีและความทึบของ splash ได้:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## สไตล์การโหลด

ตัวบ่งชี้การโหลดที่แสดงระหว่างการดำเนินการ async ถูกควบคุมโดย `LoadingStyle` คุณสามารถตั้งค่าต่อประเภทปุ่มในไฟล์ปุ่มของคุณ

### Skeletonizer (ค่าเริ่มต้น)

แสดงเอฟเฟกต์ shimmer skeleton บนปุ่ม:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

แสดง widget โหลด (ค่าเริ่มต้นเป็น app loader):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

ให้ปุ่มมองเห็นได้แต่ปิดการโต้ตอบระหว่างโหลด:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## การส่งฟอร์ม

ปุ่มทั้งหมดรองรับพารามิเตอร์ `submitForm` ซึ่งเชื่อมต่อปุ่มกับ `NyForm` เมื่อแตะ ปุ่มจะตรวจสอบความถูกต้องของฟอร์มและเรียก handler สำเร็จพร้อมข้อมูลฟอร์ม

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

พารามิเตอร์ `submitForm` รับ record ที่มีสองค่า:
1. instance ของ `NyFormData` (หรือชื่อฟอร์มเป็น `String`)
2. callback ที่รับข้อมูลที่ผ่านการตรวจสอบแล้ว

ตามค่าเริ่มต้น `showToastError` เป็น `true` ซึ่งจะแสดงการแจ้งเตือน toast เมื่อการตรวจสอบฟอร์มล้มเหลว ตั้งค่าเป็น `false` เพื่อจัดการข้อผิดพลาดแบบเงียบ:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

เมื่อ callback `submitForm` คืนค่า `Future` ปุ่มจะแสดงสถานะโหลดโดยอัตโนมัติจนกว่าการดำเนินการ async จะเสร็จสิ้น

<div id="customizing-buttons"></div>

## การปรับแต่งปุ่ม

ค่าเริ่มต้นของปุ่มทั้งหมดถูกกำหนดในโปรเจกต์ของคุณที่ `lib/resources/widgets/buttons/buttons.dart` แต่ละประเภทปุ่มมีคลาส widget ที่สอดคล้องกันใน `lib/resources/widgets/buttons/partials/`

### เปลี่ยนสไตล์เริ่มต้น

เพื่อแก้ไขรูปลักษณ์เริ่มต้นของปุ่ม แก้ไขคลาส `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### ปรับแต่ง Widget ปุ่ม

เพื่อเปลี่ยนรูปลักษณ์ของประเภทปุ่ม แก้ไข widget ที่สอดคล้องกันใน `lib/resources/widgets/buttons/partials/` ตัวอย่างเช่น เพื่อเปลี่ยน border radius หรือเงาของปุ่ม primary:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### สร้างประเภทปุ่มใหม่

เพื่อเพิ่มประเภทปุ่มใหม่:

1. สร้างไฟล์ widget ใหม่ใน `lib/resources/widgets/buttons/partials/` ที่สืบทอดจาก `StatefulAppButton`
2. ใช้เมธอด `buildButton`
3. เพิ่มเมธอดแบบ static ในคลาส `Button`

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

จากนั้นลงทะเบียนในคลาส `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## พารามิเตอร์อ้างอิง

### พารามิเตอร์ทั่วไป (ทุกประเภทปุ่ม)

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `text` | `String` | จำเป็น | ข้อความป้ายชื่อปุ่ม |
| `onPressed` | `VoidCallback?` | `null` | Callback เมื่อแตะปุ่ม คืนค่า `Future` เพื่อใช้สถานะโหลดอัตโนมัติ |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Record สำหรับส่งฟอร์ม (instance ฟอร์ม, callback สำเร็จ) |
| `onFailure` | `Function(dynamic)?` | `null` | เรียกเมื่อการตรวจสอบฟอร์มล้มเหลว |
| `showToastError` | `bool` | `true` | แสดงการแจ้งเตือน toast เมื่อการตรวจสอบฟอร์มผิดพลาด |
| `width` | `double?` | `null` | ความกว้างปุ่ม (ค่าเริ่มต้นเป็นเต็มความกว้าง) |

### พารามิเตอร์เฉพาะประเภท

#### Button.outlined

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | สีเส้นขอบของธีม | สีเส้นขอบ |
| `textColor` | `Color?` | สีหลักของธีม | สีข้อความ |

#### Button.textOnly

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | สีหลักของธีม | สีข้อความ |

#### Button.icon

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `icon` | `Widget` | จำเป็น | widget ไอคอนที่จะแสดง |
| `color` | `Color?` | สีหลักของธีม | สีพื้นหลัง |

#### Button.gradient

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | สี primary และ tertiary | จุดหยุดสี gradient |

#### Button.rounded

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | สี primary container ของธีม | สีพื้นหลัง |
| `borderRadius` | `BorderRadius?` | รูปทรงยาวรี (ความสูง / 2) | รัศมีมุม |

#### Button.transparency

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `color` | `Color?` | ปรับตามธีม | สีข้อความ |

### พารามิเตอร์ ButtonAnimationStyle

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `duration` | `Duration` | แตกต่างตามประเภท | ระยะเวลาแอนิเมชัน |
| `curve` | `Curve` | แตกต่างตามประเภท | เส้นโค้งแอนิเมชัน |
| `enableHapticFeedback` | `bool` | แตกต่างตามประเภท | เรียกการสั่นเมื่อกด |
| `translateY` | `double` | `4.0` | Clickable: ระยะกดในแนวตั้ง |
| `shadowOffset` | `double` | `4.0` | Clickable: ความลึกเงา |
| `scaleMin` | `double` | `0.92` | Bounce: ขนาดขั้นต่ำเมื่อกด |
| `pulseScale` | `double` | `1.05` | Pulse: ขนาดสูงสุดระหว่าง pulse |
| `squeezeX` | `double` | `0.95` | Squeeze: การบีบอัดแนวนอน |
| `squeezeY` | `double` | `1.05` | Squeeze: การขยายแนวตั้ง |
| `jellyStrength` | `double` | `0.15` | Jelly: ความเข้มของการสั่นไหว |
| `shineColor` | `Color` | `Colors.white` | Shine: สีไฮไลท์ |
| `shineWidth` | `double` | `0.3` | Shine: ความกว้างของแถบแสง |
| `rippleScale` | `double` | `2.0` | Ripple: ขนาดการขยาย |
| `morphRadius` | `double` | `24.0` | Morph: border radius เป้าหมาย |
| `shakeOffset` | `double` | `8.0` | Shake: การเลื่อนแนวนอน |
| `shakeCount` | `int` | `3` | Shake: จำนวนครั้งที่สั่น |

### พารามิเตอร์ ButtonSplashStyle

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | สีพื้นผิวของธีม | สีเอฟเฟกต์ splash |
| `highlightColor` | `Color?` | สีพื้นผิวของธีม | สีเอฟเฟกต์ไฮไลท์ |
| `splashOpacity` | `double` | `0.12` | ความทึบของ splash |
| `highlightOpacity` | `double` | `0.06` | ความทึบของไฮไลท์ |
| `borderRadius` | `BorderRadius?` | `null` | รัศมีตัดของ splash |
