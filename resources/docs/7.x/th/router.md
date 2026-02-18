# Router

---

<a name="section-1"></a>

- [บทนำ](#introduction "บทนำ")
- พื้นฐาน
  - [การเพิ่ม routes](#adding-routes "การเพิ่ม routes")
  - [การนำทางไปยังหน้า](#navigating-to-pages "การนำทางไปยังหน้า")
  - [Route เริ่มต้น](#initial-route "Route เริ่มต้น")
  - [Route แสดงตัวอย่าง](#preview-route "Route แสดงตัวอย่าง")
  - [Route ที่ต้องยืนยันตัวตน](#authenticated-route "Route ที่ต้องยืนยันตัวตน")
  - [Route ที่ไม่รู้จัก](#unknown-route "Route ที่ไม่รู้จัก")
- การส่งข้อมูลไปยังหน้าอื่น
  - [การส่งข้อมูลไปยังหน้าอื่น](#passing-data-to-another-page "การส่งข้อมูลไปยังหน้าอื่น")
- การนำทาง
  - [ประเภทการนำทาง](#navigation-types "ประเภทการนำทาง")
  - [การนำทางกลับ](#navigating-back "การนำทางกลับ")
  - [การนำทางแบบมีเงื่อนไข](#conditional-navigation "การนำทางแบบมีเงื่อนไข")
  - [การเปลี่ยนหน้า](#page-transitions "การเปลี่ยนหน้า")
  - [ประวัติ Route](#route-history "ประวัติ Route")
  - [อัปเดต Route Stack](#update-route-stack "อัปเดต Route Stack")
- พารามิเตอร์ Route
  - [การใช้พารามิเตอร์ Route](#route-parameters "พารามิเตอร์ Route")
  - [พารามิเตอร์ Query](#query-parameters "พารามิเตอร์ Query")
- Route Guards
  - [การสร้าง Route Guards](#route-guards "Route Guards")
  - [วงจรชีวิต NyRouteGuard](#nyroute-guard-lifecycle "วงจรชีวิต NyRouteGuard")
  - [เมธอดช่วยเหลือของ Guard](#guard-helper-methods "เมธอดช่วยเหลือของ Guard")
  - [Parameterized Guards](#parameterized-guards "Parameterized Guards")
  - [Guard Stacks](#guard-stacks "Guard Stacks")
  - [Conditional Guards](#conditional-guards "Conditional Guards")
- กลุ่ม Route
  - [กลุ่ม Route](#route-groups "กลุ่ม Route")
- [Deep linking](#deep-linking "Deep linking")
- [ขั้นสูง](#advanced "ขั้นสูง")



<div id="introduction"></div>

## บทนำ

Routes อนุญาตให้คุณกำหนดหน้าต่างๆ ในแอปของคุณและนำทางระหว่างหน้าเหล่านั้น

ใช้ routes เมื่อคุณต้องการ:
- กำหนดหน้าที่พร้อมใช้งานในแอปของคุณ
- นำทางผู้ใช้ระหว่างหน้าจอต่างๆ
- ป้องกันหน้าด้วยการยืนยันตัวตน
- ส่งข้อมูลจากหน้าหนึ่งไปยังอีกหน้าหนึ่ง
- จัดการ deep links จาก URL

คุณสามารถเพิ่ม routes ภายในไฟล์ `lib/routes/router.dart`

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // เพิ่ม routes เพิ่มเติม
  // router.add(AccountPage.path);

});
```

> **เคล็ดลับ:** คุณสามารถสร้าง routes ด้วยตนเองหรือใช้เครื่องมือ <a href="/docs/{{ $version }}/metro">Metro</a> CLI เพื่อสร้างให้คุณ

นี่คือตัวอย่างการสร้างหน้า 'account' โดยใช้ Metro

``` bash
metro make:page account_page
```

``` dart
// เพิ่ม route ใหม่ของคุณโดยอัตโนมัติไปที่ /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

คุณอาจต้องส่งข้อมูลจากมุมมองหนึ่งไปยังอีกมุมมองหนึ่ง ใน {{ config('app.name') }} สิ่งนี้เป็นไปได้โดยใช้ `NyStatefulWidget` (stateful widget ที่มีการเข้าถึงข้อมูล route ในตัว) เราจะอธิบายเพิ่มเติมเกี่ยวกับวิธีการทำงาน


<div id="adding-routes"></div>

## การเพิ่ม routes

นี่เป็นวิธีที่ง่ายที่สุดในการเพิ่ม routes ใหม่ไปยังโปรเจกต์ของคุณ

เรียกใช้คำสั่งด้านล่างเพื่อสร้างหน้าใหม่

```bash
metro make:page profile_page
```

หลังจากเรียกใช้คำสั่งด้านบน จะสร้าง Widget ใหม่ชื่อ `ProfilePage` และเพิ่มไปในไดเรกทอรี `resources/pages/` ของคุณ
นอกจากนี้ยังเพิ่ม route ใหม่ไปในไฟล์ `lib/routes/router.dart` ของคุณ

ไฟล์: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Route ใหม่ของฉัน
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## การนำทางไปยังหน้า

คุณสามารถนำทางไปยังหน้าใหม่โดยใช้ helper `routeTo`

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Route เริ่มต้น

ใน routers ของคุณ คุณสามารถกำหนดหน้าแรกที่ควรโหลดโดยใช้เมธอด `.initialRoute()`

เมื่อคุณตั้งค่า route เริ่มต้นแล้ว มันจะเป็นหน้าแรกที่โหลดเมื่อคุณเปิดแอป

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // route เริ่มต้นใหม่
});
```


### Route เริ่มต้นแบบมีเงื่อนไข

คุณยังสามารถตั้งค่า route เริ่มต้นแบบมีเงื่อนไขโดยใช้พารามิเตอร์ `when`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### นำทางไปยัง Route เริ่มต้น

ใช้ `routeToInitial()` เพื่อนำทางไปยัง route เริ่มต้นของแอป:

``` dart
void _goHome() {
    routeToInitial();
}
```

คำสั่งนี้จะนำทางไปยัง route ที่ทำเครื่องหมายด้วย `.initialRoute()` และล้าง navigation stack

<div id="preview-route"></div>

## Route แสดงตัวอย่าง

ระหว่างการพัฒนา คุณอาจต้องการดูตัวอย่างหน้าเฉพาะอย่างรวดเร็วโดยไม่ต้องเปลี่ยน route เริ่มต้นถาวร ใช้ `.previewRoute()` เพื่อทำให้ route ใดๆ เป็น route เริ่มต้นชั่วคราว:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // หน้านี้จะแสดงก่อนระหว่างการพัฒนา
});
```

เมธอด `previewRoute()`:
- แทนที่การตั้งค่า `initialRoute()` และ `authenticatedRoute()` ที่มีอยู่
- ทำให้ route ที่ระบุเป็น route เริ่มต้น
- มีประโยชน์สำหรับการทดสอบหน้าเฉพาะอย่างรวดเร็วระหว่างการพัฒนา

> **คำเตือน:** อย่าลืมลบ `.previewRoute()` ก่อนปล่อยแอปของคุณ!

<div id="authenticated-route"></div>

## Route ที่ต้องยืนยันตัวตน

ในแอปของคุณ คุณสามารถกำหนด route ให้เป็น route เริ่มต้นเมื่อผู้ใช้ยืนยันตัวตนแล้ว
สิ่งนี้จะแทนที่ route เริ่มต้นโดยอัตโนมัติและเป็นหน้าแรกที่ผู้ใช้เห็นเมื่อเข้าสู่ระบบ

ก่อนอื่น ผู้ใช้ของคุณควรเข้าสู่ระบบโดยใช้ helper `Auth.authenticate({...})`

ตอนนี้ เมื่อพวกเขาเปิดแอป route ที่คุณกำหนดจะเป็นหน้าเริ่มต้นจนกว่าพวกเขาจะออกจากระบบ

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // หน้าที่ต้องยืนยันตัวตน
});
```

### Route ที่ต้องยืนยันตัวตนแบบมีเงื่อนไข

คุณยังสามารถตั้งค่า route ที่ต้องยืนยันตัวตนแบบมีเงื่อนไข:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### นำทางไปยัง Route ที่ต้องยืนยันตัวตน

คุณสามารถนำทางไปยังหน้าที่ต้องยืนยันตัวตนโดยใช้ helper `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**ดูเพิ่มเติม:** [การยืนยันตัวตน](/docs/{{ $version }}/authentication) สำหรับรายละเอียดเกี่ยวกับการยืนยันตัวตนผู้ใช้และการจัดการ sessions


<div id="unknown-route"></div>

## Route ที่ไม่รู้จัก

คุณสามารถกำหนด route เพื่อจัดการสถานการณ์ 404/ไม่พบโดยใช้ `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

เมื่อผู้ใช้นำทางไปยัง route ที่ไม่มีอยู่ พวกเขาจะเห็นหน้า route ที่ไม่รู้จัก


<div id="route-guards"></div>

## Route guards

Route guards ป้องกันหน้าจากการเข้าถึงที่ไม่ได้รับอนุญาต มันทำงานก่อนการนำทางเสร็จสิ้น ช่วยให้คุณเปลี่ยนเส้นทางผู้ใช้หรือบล็อกการเข้าถึงตามเงื่อนไข

ใช้ route guards เมื่อคุณต้องการ:
- ป้องกันหน้าจากผู้ใช้ที่ไม่ได้ยืนยันตัวตน
- ตรวจสอบสิทธิ์ก่อนอนุญาตการเข้าถึง
- เปลี่ยนเส้นทางผู้ใช้ตามเงื่อนไข (เช่น การแนะนำการใช้งานยังไม่เสร็จ)
- บันทึกหรือติดตามการเข้าชมหน้า

เพื่อสร้าง Route Guard ใหม่ ให้เรียกใช้คำสั่งด้านล่าง

``` bash
metro make:route_guard dashboard
```

จากนั้น เพิ่ม Route Guard ใหม่ไปยัง route ของคุณ

``` dart
// ไฟล์: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // เพิ่ม guard ของคุณ
    ]
  ); // หน้าที่จำกัดการเข้าถึง
});
```

คุณยังสามารถตั้งค่า route guards โดยใช้เมธอด `addRouteGuard`:

``` dart
// ไฟล์: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // หรือเพิ่มหลาย guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## วงจรชีวิต NyRouteGuard

ใน v7 route guards ใช้คลาส `NyRouteGuard` พร้อมเมธอดวงจรชีวิตสามเมธอด:

- **`onBefore(RouteContext context)`** - ถูกเรียกก่อนการนำทาง ส่งคืน `next()` เพื่อดำเนินต่อ `redirect()` เพื่อไปที่อื่น หรือ `abort()` เพื่อหยุด
- **`onAfter(RouteContext context)`** - ถูกเรียกหลังจากนำทางไปยัง route สำเร็จ

### ตัวอย่างเบื้องต้น

ไฟล์: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // ตรวจสอบว่าพวกเขาสามารถเข้าถึงหน้าได้หรือไม่
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // ติดตามการเข้าชมหน้าหลังจากนำทางสำเร็จ
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

คลาส `RouteContext` ให้การเข้าถึงข้อมูลการนำทาง:

| คุณสมบัติ | ประเภท | คำอธิบาย |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context ปัจจุบัน |
| `data` | `dynamic` | ข้อมูลที่ส่งไปยัง route |
| `queryParameters` | `Map<String, String>` | พารามิเตอร์ query ของ URL |
| `routeName` | `String` | ชื่อ/เส้นทางของ route |
| `originalRouteName` | `String?` | ชื่อ route ดั้งเดิมก่อนการแปลง |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## เมธอดช่วยเหลือของ Guard

### next()

ดำเนินต่อไปยัง guard ถัดไปหรือไปยัง route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // อนุญาตให้การนำทางดำเนินต่อ
}
```

### redirect()

เปลี่ยนเส้นทางไปยัง route อื่น:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

เมธอด `redirect()` รับ:

| พารามิเตอร์ | ประเภท | คำอธิบาย |
|-----------|------|-------------|
| `path` | `Object` | เส้นทาง route หรือ RouteView |
| `data` | `dynamic` | ข้อมูลที่จะส่งไปยัง route |
| `queryParameters` | `Map<String, dynamic>?` | พารามิเตอร์ query |
| `navigationType` | `NavigationType` | ประเภทการนำทาง (ค่าเริ่มต้น: pushReplace) |
| `transitionType` | `TransitionType?` | การเปลี่ยนหน้า |
| `onPop` | `Function(dynamic)?` | Callback เมื่อ route ถูก pop |

### abort()

หยุดการนำทางโดยไม่เปลี่ยนเส้นทาง:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // ผู้ใช้อยู่ที่ route ปัจจุบัน
  }
  return next();
}
```

### setData()

แก้ไขข้อมูลที่ส่งไปยัง guards ถัดไปและ route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Parameterized Guards

ใช้ `ParameterizedGuard` เมื่อคุณต้องการกำหนดค่าพฤติกรรม guard ต่อ route:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// การใช้งาน:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Guard Stacks

ประกอบหลาย guards เข้าเป็น guard เดียวที่นำกลับมาใช้ซ้ำได้โดยใช้ `GuardStack`:

``` dart
// สร้างชุด guard ที่นำกลับมาใช้ซ้ำได้
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Conditional Guards

ใช้ guards แบบมีเงื่อนไขตามเงื่อนไข:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## การส่งข้อมูลไปยังหน้าอื่น

ในส่วนนี้ เราจะแสดงวิธีส่งข้อมูลจากวิดเจ็ตหนึ่งไปยังอีกวิดเจ็ตหนึ่ง

จากวิดเจ็ตของคุณ ใช้ helper `routeTo` และส่ง `data` ที่คุณต้องการส่งไปยังหน้าใหม่

``` dart
// วิดเจ็ต HomePage
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// วิดเจ็ต SettingsPage (หน้าอื่น)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // หรือ
    print(data()); // Hello World
  };
```

ตัวอย่างเพิ่มเติม

``` dart
// วิดเจ็ตหน้าหลัก
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// วิดเจ็ตหน้าโปรไฟล์ (หน้าอื่น)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## กลุ่ม Route

กลุ่ม Route จัดระเบียบ routes ที่เกี่ยวข้องและใช้การตั้งค่าร่วมกัน มีประโยชน์เมื่อหลาย routes ต้องการ guards เดียวกัน คำนำหน้า URL หรือสไตล์การเปลี่ยนหน้าเดียวกัน

ใช้กลุ่ม route เมื่อคุณต้องการ:
- ใช้ route guard เดียวกันกับหลายหน้า
- เพิ่มคำนำหน้า URL ให้กับชุด routes (เช่น `/admin/...`)
- ตั้งค่าการเปลี่ยนหน้าเดียวกันสำหรับ routes ที่เกี่ยวข้อง

คุณสามารถกำหนดกลุ่ม route เหมือนในตัวอย่างด้านล่าง

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### การตั้งค่าเสริมสำหรับกลุ่ม route:

| การตั้งค่า | ประเภท | คำอธิบาย |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | ใช้ route guards กับทุก routes ในกลุ่ม |
| `prefix` | `String` | เพิ่มคำนำหน้าให้กับเส้นทาง route ทั้งหมดในกลุ่ม |
| `transition_type` | `TransitionType` | ตั้งค่าการเปลี่ยนหน้าสำหรับทุก routes ในกลุ่ม |
| `transition` | `PageTransitionType` | ตั้งค่าประเภทการเปลี่ยนหน้า (เลิกใช้แล้ว ใช้ transition_type) |
| `transition_settings` | `PageTransitionSettings` | ตั้งค่าการเปลี่ยนหน้า |


<div id="route-parameters"></div>

## การใช้พารามิเตอร์ Route

เมื่อคุณสร้างหน้าใหม่ คุณสามารถอัปเดต route เพื่อรับพารามิเตอร์

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

ตอนนี้ เมื่อคุณนำทางไปยังหน้า คุณสามารถส่ง `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

คุณสามารถเข้าถึงพารามิเตอร์ในหน้าใหม่แบบนี้

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## พารามิเตอร์ Query

เมื่อนำทางไปยังหน้าใหม่ คุณยังสามารถให้พารามิเตอร์ query

มาดูกัน

```dart
  // หน้าหลัก
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // นำทางไปยังหน้าโปรไฟล์

  ...

  // หน้าโปรไฟล์
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // หรือ
    print(queryParameters()); // {"user": 7}
  };
```

> **หมายเหตุ:** ตราบใดที่วิดเจ็ตหน้าของคุณสืบทอดจากคลาส `NyStatefulWidget` และ `NyPage` คุณสามารถเรียก `widget.queryParameters()` เพื่อดึงพารามิเตอร์ query ทั้งหมดจากชื่อ route

```dart
// หน้าตัวอย่าง
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// หน้าหลัก
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // หรือ
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **เคล็ดลับ:** พารามิเตอร์ query ต้องเป็นไปตามโปรโตคอล HTTP เช่น /account?userId=1&tab=2


<div id="page-transitions"></div>

## การเปลี่ยนหน้า

คุณสามารถเพิ่มการเปลี่ยนหน้าเมื่อนำทางจากหน้าหนึ่งโดยแก้ไขไฟล์ `router.dart` ของคุณ

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### การเปลี่ยนหน้าที่มีให้

#### การเปลี่ยนพื้นฐาน
- **`TransitionType.fade()`** - เฟดหน้าใหม่เข้าขณะเฟดหน้าเก่าออก
- **`TransitionType.theme()`** - ใช้ธีมการเปลี่ยนหน้าของธีมแอป

#### การเปลี่ยนแบบเลื่อนตามทิศทาง
- **`TransitionType.rightToLeft()`** - เลื่อนจากขอบขวาของหน้าจอ
- **`TransitionType.leftToRight()`** - เลื่อนจากขอบซ้ายของหน้าจอ
- **`TransitionType.topToBottom()`** - เลื่อนจากขอบบนของหน้าจอ
- **`TransitionType.bottomToTop()`** - เลื่อนจากขอบล่างของหน้าจอ

#### การเปลี่ยนแบบเลื่อนพร้อมเฟด
- **`TransitionType.rightToLeftWithFade()`** - เลื่อนและเฟดจากขอบขวา
- **`TransitionType.leftToRightWithFade()`** - เลื่อนและเฟดจากขอบซ้าย

#### การเปลี่ยนแบบแปลง
- **`TransitionType.scale(alignment: ...)`** - ขยายจากจุดจัดตำแหน่งที่ระบุ
- **`TransitionType.rotate(alignment: ...)`** - หมุนรอบจุดจัดตำแหน่งที่ระบุ
- **`TransitionType.size(alignment: ...)`** - ขยายจากจุดจัดตำแหน่งที่ระบุ

#### การเปลี่ยนแบบเชื่อมต่อ (ต้องการวิดเจ็ตปัจจุบัน)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - หน้าปัจจุบันออกทางขวาขณะหน้าใหม่เข้าจากซ้าย
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - หน้าปัจจุบันออกทางซ้ายขณะหน้าใหม่เข้าจากขวา
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - หน้าปัจจุบันออกทางล่างขณะหน้าใหม่เข้าจากบน
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - หน้าปัจจุบันออกทางบนขณะหน้าใหม่เข้าจากล่าง

#### การเปลี่ยนแบบ Pop (ต้องการวิดเจ็ตปัจจุบัน)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - หน้าปัจจุบันออกทางขวา หน้าใหม่อยู่กับที่
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - หน้าปัจจุบันออกทางซ้าย หน้าใหม่อยู่กับที่
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - หน้าปัจจุบันออกทางล่าง หน้าใหม่อยู่กับที่
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - หน้าปัจจุบันออกทางบน หน้าใหม่อยู่กับที่

#### การเปลี่ยนแบบ Material Design Shared Axis
- **`TransitionType.sharedAxisHorizontal()`** - การเลื่อนแนวนอนและเฟด
- **`TransitionType.sharedAxisVertical()`** - การเลื่อนแนวตั้งและเฟด
- **`TransitionType.sharedAxisScale()`** - การขยายและเฟด

#### พารามิเตอร์การปรับแต่ง
แต่ละการเปลี่ยนรับพารามิเตอร์เสริมดังต่อไปนี้:

| พารามิเตอร์ | คำอธิบาย | ค่าเริ่มต้น |
|-----------|-------------|---------|
| `curve` | เส้นโค้งแอนิเมชัน | เส้นโค้งเฉพาะแพลตฟอร์ม |
| `duration` | ระยะเวลาแอนิเมชัน | ระยะเวลาเฉพาะแพลตฟอร์ม |
| `reverseDuration` | ระยะเวลาแอนิเมชันย้อนกลับ | เท่ากับ duration |
| `fullscreenDialog` | route เป็นกล่องโต้ตอบแบบเต็มจอหรือไม่ | `false` |
| `opaque` | route เป็นแบบทึบหรือไม่ | `false` |


``` dart
// วิดเจ็ตหน้าหลัก
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## ประเภทการนำทาง

เมื่อนำทาง คุณสามารถระบุหนึ่งในประเภทต่อไปนี้หากคุณใช้ helper `routeTo`

| ประเภท | คำอธิบาย |
|------|-------------|
| `NavigationType.push` | Push หน้าใหม่ไปยัง route stack ของแอป |
| `NavigationType.pushReplace` | แทนที่ route ปัจจุบัน กำจัด route ก่อนหน้าเมื่อ route ใหม่เสร็จสิ้น |
| `NavigationType.popAndPushNamed` | Pop route ปัจจุบันออกจาก navigator และ push named route แทน |
| `NavigationType.pushAndRemoveUntil` | Push และลบ routes จนกว่าเงื่อนไขจะเป็นจริง |
| `NavigationType.pushAndForgetAll` | Push ไปยังหน้าใหม่และกำจัดหน้าอื่นทั้งหมดบน route stack |

``` dart
// วิดเจ็ตหน้าหลัก
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## การนำทางกลับ

เมื่อคุณอยู่ในหน้าใหม่ คุณสามารถใช้ helper `pop()` เพื่อกลับไปยังหน้าที่มีอยู่

``` dart
// วิดเจ็ต SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // หรือ
    Navigator.pop(context);
  }
...
```

หากคุณต้องการส่งคืนค่าไปยังวิดเจ็ตก่อนหน้า ให้ใส่ `result` ตามตัวอย่างด้านล่าง

``` dart
// วิดเจ็ต SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// รับค่าจากวิดเจ็ตก่อนหน้าโดยใช้พารามิเตอร์ `onPop`
// วิดเจ็ต HomePage
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## การนำทางแบบมีเงื่อนไข

ใช้ `routeIf()` เพื่อนำทางเฉพาะเมื่อเงื่อนไขเป็นจริง:

``` dart
// นำทางเฉพาะเมื่อผู้ใช้เข้าสู่ระบบแล้ว
routeIf(isLoggedIn, DashboardPage.path);

// พร้อมตัวเลือกเพิ่มเติม
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

หากเงื่อนไขเป็น `false` จะไม่มีการนำทางเกิดขึ้น


<div id="route-history"></div>

## ประวัติ Route

ใน {{ config('app.name') }} คุณสามารถเข้าถึงข้อมูลประวัติ route โดยใช้ helpers ด้านล่าง

``` dart
// ดูประวัติ route
Nylo.getRouteHistory(); // List<dynamic>

// ดู route ปัจจุบัน
Nylo.getCurrentRoute(); // Route<dynamic>?

// ดู route ก่อนหน้า
Nylo.getPreviousRoute(); // Route<dynamic>?

// ดูชื่อ route ปัจจุบัน
Nylo.getCurrentRouteName(); // String?

// ดูชื่อ route ก่อนหน้า
Nylo.getPreviousRouteName(); // String?

// ดู arguments ของ route ปัจจุบัน
Nylo.getCurrentRouteArguments(); // dynamic

// ดู arguments ของ route ก่อนหน้า
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## อัปเดต Route Stack

คุณสามารถอัปเดต navigation stack ด้วยโปรแกรมโดยใช้ `NyNavigator.updateStack()`:

``` dart
// อัปเดต stack ด้วยรายการ routes
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// ส่งข้อมูลไปยัง routes เฉพาะ
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| พารามิเตอร์ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | จำเป็น | รายการเส้นทาง route ที่จะนำทางไป |
| `replace` | `bool` | `true` | จะแทนที่ stack ปัจจุบันหรือไม่ |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | ข้อมูลที่จะส่งไปยัง routes เฉพาะ |

สิ่งนี้มีประโยชน์สำหรับ:
- สถานการณ์ deep linking
- การกู้คืนสถานะการนำทาง
- การสร้างขั้นตอนการนำทางที่ซับซ้อน


<div id="deep-linking"></div>

## Deep Linking

Deep linking อนุญาตให้ผู้ใช้นำทางโดยตรงไปยังเนื้อหาเฉพาะภายในแอปของคุณโดยใช้ URL สิ่งนี้มีประโยชน์สำหรับ:
- การแชร์ลิงก์ตรงไปยังเนื้อหาเฉพาะในแอป
- แคมเปญการตลาดที่มุ่งเป้าไปที่ฟีเจอร์เฉพาะในแอป
- การจัดการการแจ้งเตือนที่ควรเปิดหน้าจอเฉพาะในแอป
- การเปลี่ยนจากเว็บเป็นแอปอย่างราบรื่น

## การตั้งค่า

ก่อนใช้งาน deep linking ในแอปของคุณ ตรวจสอบว่าโปรเจกต์ของคุณตั้งค่าอย่างถูกต้อง:

### 1. การตั้งค่าแพลตฟอร์ม

**iOS**: ตั้งค่า universal links ในโปรเจกต์ Xcode ของคุณ
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">คู่มือการตั้งค่า Flutter Universal Links</a>

**Android**: ตั้งค่า app links ใน AndroidManifest.xml ของคุณ
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">คู่มือการตั้งค่า Flutter App Links</a>

### 2. กำหนด Routes ของคุณ

ทุก routes ที่ควรเข้าถึงได้ผ่าน deep links ต้องลงทะเบียนในการตั้งค่า router ของคุณ:

```dart
// ไฟล์: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Routes พื้นฐาน
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route พร้อมพารามิเตอร์
  router.add(HotelBookingPage.path);
});
```

## การใช้ Deep Links

เมื่อตั้งค่าเรียบร้อยแล้ว แอปของคุณสามารถจัดการ URL ที่เข้ามาในรูปแบบต่างๆ:

### Deep Links พื้นฐาน

การนำทางอย่างง่ายไปยังหน้าเฉพาะ:

``` bash
https://yourdomain.com/profile       // เปิดหน้าโปรไฟล์
https://yourdomain.com/settings      // เปิดหน้าการตั้งค่า
```

เพื่อทริกเกอร์การนำทางเหล่านี้ด้วยโปรแกรมภายในแอปของคุณ:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### พารามิเตอร์เส้นทาง

สำหรับ routes ที่ต้องการข้อมูลแบบไดนามิกเป็นส่วนหนึ่งของเส้นทาง:

#### การกำหนด Route

```dart
class HotelBookingPage extends NyStatefulWidget {
  // กำหนด route พร้อม placeholder พารามิเตอร์ {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // เข้าถึงพารามิเตอร์เส้นทาง
    final hotelId = queryParameters()["id"]; // ส่งคืน "87" สำหรับ URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // ใช้ ID เพื่อดึงข้อมูลโรงแรมหรือดำเนินการ
  };

  // ส่วนที่เหลือของการใช้งานหน้า
}
```

#### รูปแบบ URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### การนำทางด้วยโปรแกรม

```dart
// นำทางพร้อมพารามิเตอร์
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### พารามิเตอร์ Query

สำหรับพารามิเตอร์ที่เป็นตัวเลือกหรือเมื่อต้องการค่าแบบไดนามิกหลายค่า:

#### รูปแบบ URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### การเข้าถึงพารามิเตอร์ Query

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // ดึงพารามิเตอร์ query ทั้งหมด
    final params = queryParameters();

    // เข้าถึงพารามิเตอร์เฉพาะ
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // วิธีเข้าถึงทางเลือก
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### การนำทางด้วยโปรแกรมพร้อมพารามิเตอร์ Query

```dart
// นำทางพร้อมพารามิเตอร์ query
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// รวมพารามิเตอร์เส้นทางและพารามิเตอร์ query
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## การจัดการ Deep Links

คุณสามารถจัดการเหตุการณ์ deep link ใน `RouteProvider` ของคุณ:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // จัดการ deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // อัปเดต route stack สำหรับ deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### การทดสอบ Deep Links

สำหรับการพัฒนาและทดสอบ คุณสามารถจำลองการเปิดใช้งาน deep link โดยใช้ ADB (Android) หรือ xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### เคล็ดลับการดีบัก

- พิมพ์พารามิเตอร์ทั้งหมดในเมธอด init ของคุณเพื่อตรวจสอบการแยกวิเคราะห์ที่ถูกต้อง
- ทดสอบรูปแบบ URL ต่างๆ เพื่อให้แน่ใจว่าแอปของคุณจัดการได้ถูกต้อง
- จำไว้ว่าพารามิเตอร์ query จะได้รับเป็นสตริงเสมอ ให้แปลงเป็นประเภทที่เหมาะสมตามต้องการ

---

## รูปแบบทั่วไป

### การแปลงประเภทพารามิเตอร์

เนื่องจากพารามิเตอร์ URL ทั้งหมดส่งเป็นสตริง คุณมักจะต้องแปลง:

```dart
// แปลงพารามิเตอร์สตริงเป็นประเภทที่เหมาะสม
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### พารามิเตอร์ที่เป็นตัวเลือก

จัดการกรณีที่พารามิเตอร์อาจหายไป:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // โหลดโปรไฟล์ผู้ใช้เฉพาะ
} else {
  // โหลดโปรไฟล์ผู้ใช้ปัจจุบัน
}

// หรือตรวจสอบ hasQueryParameter
if (hasQueryParameter('status')) {
  // ทำบางอย่างกับพารามิเตอร์ status
} else {
  // จัดการกรณีที่ไม่มีพารามิเตอร์
}
```


<div id="advanced"></div>

## ขั้นสูง

### ตรวจสอบว่า Route มีอยู่หรือไม่

คุณสามารถตรวจสอบว่า route ลงทะเบียนอยู่ใน router ของคุณหรือไม่:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### เมธอด NyRouter

คลาส `NyRouter` มีเมธอดที่มีประโยชน์หลายรายการ:

| เมธอด | คำอธิบาย |
|--------|-------------|
| `getRegisteredRouteNames()` | ดูชื่อ route ที่ลงทะเบียนทั้งหมดเป็นรายการ |
| `getRegisteredRoutes()` | ดู routes ที่ลงทะเบียนทั้งหมดเป็น map |
| `containsRoutes(routes)` | ตรวจสอบว่า router มี routes ที่ระบุทั้งหมดหรือไม่ |
| `getInitialRouteName()` | ดูชื่อ route เริ่มต้น |
| `getAuthRouteName()` | ดูชื่อ route ที่ต้องยืนยันตัวตน |
| `getUnknownRouteName()` | ดูชื่อ route ที่ไม่รู้จัก/404 |

### การดึง Arguments ของ Route

คุณสามารถดึง arguments ของ route โดยใช้ `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // ดึง arguments ที่มีประเภท
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument และ NyQueryParameters

ข้อมูลที่ส่งระหว่าง routes จะถูกห่อในคลาสเหล่านี้:

``` dart
// NyArgument เก็บข้อมูล route
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters เก็บพารามิเตอร์ query ของ URL
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
