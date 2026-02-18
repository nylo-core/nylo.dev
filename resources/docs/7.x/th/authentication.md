# Authentication

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับการยืนยันตัวตน")
- พื้นฐาน
  - [การยืนยันตัวตนผู้ใช้](#authenticating-users "การยืนยันตัวตนผู้ใช้")
  - [การดึงข้อมูลการยืนยันตัวตน](#retrieving-auth-data "การดึงข้อมูลการยืนยันตัวตน")
  - [การอัปเดตข้อมูลการยืนยันตัวตน](#updating-auth-data "การอัปเดตข้อมูลการยืนยันตัวตน")
  - [การออกจากระบบ](#logging-out "การออกจากระบบ")
  - [การตรวจสอบการยืนยันตัวตน](#checking-authentication "การตรวจสอบการยืนยันตัวตน")
- ขั้นสูง
  - [หลายเซสชัน](#multiple-sessions "หลายเซสชัน")
  - [Device ID](#device-id "Device ID")
  - [การซิงค์ไปยัง Backpack](#syncing-to-backpack "การซิงค์ไปยัง Backpack")
- การกำหนดค่าเส้นทาง
  - [เส้นทางเริ่มต้น](#initial-route "เส้นทางเริ่มต้น")
  - [เส้นทางที่ยืนยันตัวตนแล้ว](#authenticated-route "เส้นทางที่ยืนยันตัวตนแล้ว")
  - [เส้นทางตัวอย่าง](#preview-route "เส้นทางตัวอย่าง")
  - [เส้นทางที่ไม่รู้จัก](#unknown-route "เส้นทางที่ไม่รู้จัก")
- [ฟังก์ชันช่วยเหลือ](#helper-functions "ฟังก์ชันช่วยเหลือ")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีระบบยืนยันตัวตนที่ครอบคลุมผ่านคลาส `Auth` ซึ่งจัดการการเก็บข้อมูลประจำตัวผู้ใช้อย่างปลอดภัย การจัดการเซสชัน และรองรับหลายเซสชันที่มีชื่อสำหรับบริบทการยืนยันตัวตนที่แตกต่างกัน

ข้อมูลการยืนยันตัวตนจะถูกจัดเก็บอย่างปลอดภัยและซิงค์ไปยัง Backpack (ที่เก็บ key-value ในหน่วยความจำ) เพื่อการเข้าถึงแบบ synchronous ที่รวดเร็วทั่วทั้งแอปของคุณ

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Authenticate a user
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// Check if authenticated
bool loggedIn = await Auth.isAuthenticated(); // true

// Get auth data
dynamic token = Auth.data(field: 'token'); // "abc123"

// Logout
await Auth.logout();
```


<div id="authenticating-users"></div>

## การยืนยันตัวตนผู้ใช้

ใช้ `Auth.authenticate()` เพื่อจัดเก็บข้อมูลเซสชันผู้ใช้:

``` dart
// With a Map
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// With a Model class
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// Without data (stores a timestamp)
await Auth.authenticate();
```

### ตัวอย่างในโลกจริง

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. Call your API to authenticate
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. Store the authenticated user
    await Auth.authenticate(data: user);

    // 3. Navigate to home
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## การดึงข้อมูลการยืนยันตัวตน

ดึงข้อมูลการยืนยันตัวตนที่จัดเก็บไว้โดยใช้ `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

เมธอด `Auth.data()` อ่านจาก Backpack (ที่เก็บ key-value ในหน่วยความจำของ {{ config('app.name') }}) เพื่อการเข้าถึงแบบ synchronous ที่รวดเร็ว ข้อมูลจะถูกซิงค์ไปยัง Backpack โดยอัตโนมัติเมื่อคุณยืนยันตัวตน


<div id="updating-auth-data"></div>

## การอัปเดตข้อมูลการยืนยันตัวตน

{{ config('app.name') }} v7 เปิดตัว `Auth.set()` เพื่ออัปเดตข้อมูลการยืนยันตัวตน:

``` dart
// Update a specific field
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// Add new fields
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// Replace entire data
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## การออกจากระบบ

ลบผู้ใช้ที่ยืนยันตัวตนแล้วด้วย `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### ออกจากระบบจากทุกเซสชัน

เมื่อใช้หลายเซสชัน ล้างทั้งหมด:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## การตรวจสอบการยืนยันตัวตน

ตรวจสอบว่าผู้ใช้ได้ยืนยันตัวตนอยู่หรือไม่:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // User is authenticated
  routeTo(HomePage.path);
} else {
  // User needs to login
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## หลายเซสชัน

{{ config('app.name') }} v7 รองรับหลายเซสชันการยืนยันตัวตนที่มีชื่อสำหรับบริบทที่แตกต่างกัน สิ่งนี้มีประโยชน์เมื่อคุณต้องการติดตามประเภทการยืนยันตัวตนที่แตกต่างกันแยกจากกัน (เช่น การเข้าสู่ระบบผู้ใช้ vs การลงทะเบียนอุปกรณ์ vs การเข้าถึงผู้ดูแลระบบ)

``` dart
// Default user session
await Auth.authenticate(data: user);

// Device authentication session
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// Admin session
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### การอ่านจากเซสชันที่มีชื่อ

``` dart
// Default session
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// Device session
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// Admin session
dynamic adminData = Auth.data(session: 'admin');
```

### การออกจากระบบเฉพาะเซสชัน

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### ตรวจสอบการยืนยันตัวตนต่อเซสชัน

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Device ID

{{ config('app.name') }} v7 มีตัวระบุอุปกรณ์ที่ไม่ซ้ำกันซึ่งคงอยู่ตลอดเซสชันแอป:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

Device ID คือ:
- สร้างขึ้นครั้งเดียวและจัดเก็บถาวร
- ไม่ซ้ำกันสำหรับแต่ละอุปกรณ์/การติดตั้ง
- มีประโยชน์สำหรับการลงทะเบียนอุปกรณ์ การวิเคราะห์ หรือการแจ้งเตือนแบบ push

``` dart
// Example: Register device with backend
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // Store device auth
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## การซิงค์ไปยัง Backpack

ข้อมูลการยืนยันตัวตนจะถูกซิงค์ไปยัง Backpack โดยอัตโนมัติเมื่อคุณยืนยันตัวตน หากต้องการซิงค์ด้วยตนเอง (เช่น เมื่อเริ่มต้นแอป):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

สิ่งนี้มีประโยชน์ในลำดับการเริ่มต้นแอปของคุณเพื่อให้แน่ใจว่าข้อมูลการยืนยันตัวตนพร้อมใช้งานใน Backpack สำหรับการเข้าถึงแบบ synchronous ที่รวดเร็ว


<div id="initial-route"></div>

## เส้นทางเริ่มต้น

เส้นทางเริ่มต้นคือหน้าแรกที่ผู้ใช้เห็นเมื่อเปิดแอปของคุณ ตั้งค่าโดยใช้ `.initialRoute()` ในเราเตอร์ของคุณ:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

คุณยังสามารถตั้งค่าเส้นทางเริ่มต้นแบบมีเงื่อนไขโดยใช้พารามิเตอร์ `when`:

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

นำทางกลับไปยังเส้นทางเริ่มต้นจากที่ใดก็ได้โดยใช้ `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## เส้นทางที่ยืนยันตัวตนแล้ว

เส้นทางที่ยืนยันตัวตนแล้วจะแทนที่เส้นทางเริ่มต้นเมื่อผู้ใช้เข้าสู่ระบบ ตั้งค่าโดยใช้ `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

เมื่อแอปเริ่มต้น:
- `Auth.isAuthenticated()` ส่งคืน `true` → ผู้ใช้เห็น **เส้นทางที่ยืนยันตัวตนแล้ว** (HomePage)
- `Auth.isAuthenticated()` ส่งคืน `false` → ผู้ใช้เห็น **เส้นทางเริ่มต้น** (LoginPage)

คุณยังสามารถตั้งค่าเส้นทางที่ยืนยันตัวตนแล้วแบบมีเงื่อนไข:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

นำทางไปยังเส้นทางที่ยืนยันตัวตนแล้วโดยทางโปรแกรมโดยใช้ `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**ดูเพิ่มเติม:** [Router](/docs/{{ $version }}/router) สำหรับเอกสารการกำหนดเส้นทางฉบับสมบูรณ์รวมถึง guards และ deep linking


<div id="preview-route"></div>

## เส้นทางตัวอย่าง

ในระหว่างการพัฒนา คุณอาจต้องการดูตัวอย่างหน้าเฉพาะอย่างรวดเร็วโดยไม่ต้องเปลี่ยนเส้นทางเริ่มต้นหรือเส้นทางที่ยืนยันตัวตนแล้ว ใช้ `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` แทนที่ **ทั้ง** `initialRoute()` และ `authenticatedRoute()` ทำให้เส้นทางที่ระบุเป็นหน้าแรกที่แสดงโดยไม่คำนึงถึงสถานะการยืนยันตัวตน

> **คำเตือน:** ลบ `.previewRoute()` ก่อนเผยแพร่แอปของคุณ


<div id="unknown-route"></div>

## เส้นทางที่ไม่รู้จัก

กำหนดหน้าสำรองสำหรับเมื่อผู้ใช้นำทางไปยังเส้นทางที่ไม่มีอยู่ ตั้งค่าโดยใช้ `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### รวมทุกอย่างเข้าด้วยกัน

นี่คือการตั้งค่าเราเตอร์ที่สมบูรณ์พร้อมเส้นทางทุกประเภท:

``` dart
appRouter() => nyRoutes((router) {
  // First page for unauthenticated users
  router.add(LoginPage.path).initialRoute();

  // First page for authenticated users
  router.add(HomePage.path).authenticatedRoute();

  // 404 page
  router.add(NotFoundPage.path).unknownRoute();

  // Regular routes
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| เมธอดเส้นทาง | วัตถุประสงค์ |
|--------------|---------|
| `.initialRoute()` | หน้าแรกที่แสดงให้ผู้ใช้ที่ไม่ได้ยืนยันตัวตน |
| `.authenticatedRoute()` | หน้าแรกที่แสดงให้ผู้ใช้ที่ยืนยันตัวตนแล้ว |
| `.previewRoute()` | แทนที่ทั้งสองในระหว่างการพัฒนา |
| `.unknownRoute()` | แสดงเมื่อไม่พบเส้นทาง |


<div id="helper-functions"></div>

## ฟังก์ชันช่วยเหลือ

{{ config('app.name') }} v7 มีฟังก์ชันช่วยเหลือที่สอดคล้องกับเมธอดของคลาส `Auth`:

| ฟังก์ชันช่วยเหลือ | เทียบเท่า |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | คีย์การจัดเก็บสำหรับเซสชันเริ่มต้น |
| `authDeviceId()` | `Auth.deviceId()` |

ฟังก์ชันช่วยเหลือทั้งหมดรับพารามิเตอร์เดียวกับเมธอดของคลาส `Auth` รวมถึงพารามิเตอร์ `session` ที่เป็นตัวเลือก:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
