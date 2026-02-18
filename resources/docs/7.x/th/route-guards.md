# Route Guards

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การสร้าง Route Guard](#creating-a-route-guard "การสร้าง Route Guard")
- [วงจรชีวิตของ Guard](#guard-lifecycle "วงจรชีวิตของ Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [การกระทำของ Guard](#guard-actions "การกระทำของ Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [การใช้ Guards กับ Routes](#applying-guards "การใช้ Guards กับ Routes")
- [Group Guards](#group-guards "Group Guards")
- [การประกอบ Guard](#guard-composition "การประกอบ Guard")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [ตัวอย่าง](#examples "ตัวอย่างจริง")

<div id="introduction"></div>

## บทนำ

Route guards ให้ **middleware สำหรับการนำทาง** ใน {{ config('app.name') }} มันดักจับการเปลี่ยน route และอนุญาตให้คุณควบคุมว่าผู้ใช้สามารถเข้าถึงหน้าได้หรือไม่ เปลี่ยนเส้นทางไปที่อื่น หรือแก้ไขข้อมูลที่ส่งไปยัง route

กรณีใช้งานทั่วไป ได้แก่:
- **การตรวจสอบการยืนยันตัวตน** -- เปลี่ยนเส้นทางผู้ใช้ที่ไม่ได้ยืนยันตัวตนไปยังหน้าเข้าสู่ระบบ
- **การเข้าถึงตามบทบาท** -- จำกัดหน้าสำหรับผู้ใช้ที่เป็นผู้ดูแลระบบ
- **การตรวจสอบข้อมูล** -- ตรวจสอบว่ามีข้อมูลที่จำเป็นก่อนการนำทาง
- **การเพิ่มข้อมูล** -- แนบข้อมูลเพิ่มเติมไปยัง route

Guards จะถูกดำเนินการ **ตามลำดับ** ก่อนที่การนำทางจะเกิดขึ้น หาก guard ใดส่งคืน `handled` การนำทางจะหยุด (ไม่ว่าจะเปลี่ยนเส้นทางหรือยกเลิก)

<div id="creating-a-route-guard"></div>

## การสร้าง Route Guard

สร้าง route guard โดยใช้ Metro CLI:

``` bash
metro make:route_guard auth
```

คำสั่งนี้จะสร้างไฟล์ guard:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // เพิ่มตรรกะ guard ของคุณที่นี่
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## วงจรชีวิตของ Guard

route guard ทุกตัวมีเมธอดวงจรชีวิตสามเมธอด:

<div id="on-before"></div>

### onBefore

ถูกเรียก **ก่อน** การนำทางเกิดขึ้น นี่คือที่ที่คุณตรวจสอบเงื่อนไขและตัดสินใจว่าจะอนุญาต เปลี่ยนเส้นทาง หรือยกเลิกการนำทาง

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

ค่าที่ส่งคืน:
- `next()` -- ดำเนินการต่อไปยัง guard ถัดไปหรือนำทางไปยัง route
- `redirect(path)` -- เปลี่ยนเส้นทางไปยัง route อื่น
- `abort()` -- ยกเลิกการนำทางทั้งหมด

<div id="on-after"></div>

### onAfter

ถูกเรียก **หลัง** การนำทางสำเร็จ ใช้สำหรับการวิเคราะห์ การบันทึกล็อก หรือผลข้างเคียงหลังการนำทาง

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // บันทึกการเข้าชมหน้า
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

ถูกเรียกเมื่อผู้ใช้ **กำลังออก** จาก route ส่งคืน `false` เพื่อป้องกันไม่ให้ผู้ใช้ออก

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // แสดงกล่องโต้ตอบยืนยัน
    return await showConfirmDialog();
  }
  return true; // อนุญาตให้ออก
}
```

<div id="route-context"></div>

## RouteContext

อ็อบเจ็กต์ `RouteContext` จะถูกส่งไปยังเมธอดวงจรชีวิตของ guard ทั้งหมดและมีข้อมูลเกี่ยวกับการนำทาง:

| คุณสมบัติ | ประเภท | คำอธิบาย |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context ปัจจุบัน |
| `data` | `dynamic` | ข้อมูลที่ส่งไปยัง route |
| `queryParameters` | `Map<String, String>` | พารามิเตอร์ query ของ URL |
| `routeName` | `String` | ชื่อ/เส้นทางของ route เป้าหมาย |
| `originalRouteName` | `String?` | ชื่อ route ดั้งเดิมก่อนการแปลง |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // เข้าถึงข้อมูล route
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### การแปลง Route Context

สร้างสำเนาพร้อมข้อมูลที่แตกต่าง:

``` dart
// เปลี่ยนประเภทข้อมูล
RouteContext<User> userContext = context.withData<User>(currentUser);

// คัดลอกพร้อมฟิลด์ที่แก้ไข
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## การกระทำของ Guard

<div id="next"></div>

### next

ดำเนินการต่อไปยัง guard ถัดไปในสาย หรือนำทางไปยัง route หากนี่เป็น guard สุดท้าย:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

เปลี่ยนเส้นทางผู้ใช้ไปยัง route อื่น:

``` dart
return redirect(LoginPage.path);
```

พร้อมตัวเลือกเพิ่มเติม:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| พารามิเตอร์ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `path` | `Object` | จำเป็น | สตริงเส้นทาง route หรือ RouteView |
| `data` | `dynamic` | null | ข้อมูลที่จะส่งไปยัง route เปลี่ยนเส้นทาง |
| `queryParameters` | `Map<String, dynamic>?` | null | พารามิเตอร์ query |
| `navigationType` | `NavigationType` | `pushReplace` | วิธีการนำทาง |
| `result` | `dynamic` | null | ผลลัพธ์ที่จะส่งคืน |
| `removeUntilPredicate` | `Function?` | null | เงื่อนไขการลบ route |
| `transitionType` | `TransitionType?` | null | ประเภทการเปลี่ยนหน้า |
| `onPop` | `Function(dynamic)?` | null | Callback เมื่อ pop |

<div id="abort"></div>

### abort

ยกเลิกการนำทางโดยไม่เปลี่ยนเส้นทาง ผู้ใช้จะอยู่ที่หน้าปัจจุบัน:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

แก้ไขข้อมูลที่จะส่งไปยัง guards ถัดไปและ route เป้าหมาย:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // เพิ่มข้อมูลให้กับ route
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## การใช้ Guards กับ Routes

เพิ่ม guards ให้กับ routes แต่ละรายการในไฟล์ router ของคุณ:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // เพิ่ม guard ตัวเดียว
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // เพิ่มหลาย guards (ดำเนินการตามลำดับ)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Group Guards

ใช้ guards กับหลาย routes พร้อมกันโดยใช้ route groups:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // ทุก routes ในกลุ่มนี้ต้องผ่านการยืนยันตัวตน
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## การประกอบ Guard

{{ config('app.name') }} มีเครื่องมือสำหรับประกอบ guards เข้าด้วยกันเพื่อรูปแบบที่นำกลับมาใช้ซ้ำได้

<div id="guard-stack"></div>

### GuardStack

รวมหลาย guards เข้าเป็น guard เดียวที่นำกลับมาใช้ซ้ำได้:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// ใช้ stack กับ route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` ดำเนินการ guards ตามลำดับ หาก guard ใดส่งคืน `handled` guards ที่เหลือจะถูกข้าม

<div id="conditional-guard"></div>

### ConditionalGuard

ใช้ guard เฉพาะเมื่อเงื่อนไขเป็นจริง:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

หากเงื่อนไขส่งคืน `false` guard จะถูกข้ามและการนำทางจะดำเนินต่อไป

<div id="parameterized-guard"></div>

### ParameterizedGuard

สร้าง guards ที่รับพารามิเตอร์การตั้งค่า:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = บทบาทที่อนุญาต

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// การใช้งาน
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## ตัวอย่าง

### Guard การยืนยันตัวตน

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### Guard การสมัครสมาชิกพร้อมพารามิเตอร์

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// ต้องมีการสมัครสมาชิก premium หรือ pro
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Guard การบันทึกล็อก

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
