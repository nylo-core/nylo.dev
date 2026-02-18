# 인증

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 기본 사항
  - [사용자 인증](#authenticating-users "사용자 인증")
  - [인증 데이터 가져오기](#retrieving-auth-data "인증 데이터 가져오기")
  - [인증 데이터 업데이트](#updating-auth-data "인증 데이터 업데이트")
  - [로그아웃](#logging-out "로그아웃")
  - [인증 확인](#checking-authentication "인증 확인")
- 고급
  - [다중 세션](#multiple-sessions "다중 세션")
  - [Device ID](#device-id "Device ID")
  - [Backpack에 동기화](#syncing-to-backpack "Backpack에 동기화")
- 라우트 설정
  - [초기 라우트](#initial-route "초기 라우트")
  - [인증된 라우트](#authenticated-route "인증된 라우트")
  - [미리보기 라우트](#preview-route "미리보기 라우트")
  - [알 수 없는 라우트](#unknown-route "알 수 없는 라우트")
- [헬퍼 함수](#helper-functions "헬퍼 함수")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 `Auth` 클래스를 통해 포괄적인 인증 시스템을 제공합니다. 사용자 자격 증명의 안전한 저장, 세션 관리를 처리하며, 다양한 인증 컨텍스트를 위한 여러 명명된 세션을 지원합니다.

인증 데이터는 안전하게 저장되고 앱 전체에서 빠른 동기 접근을 위해 Backpack(인메모리 키-값 저장소)에 동기화됩니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 사용자 인증
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// 인증 여부 확인
bool loggedIn = await Auth.isAuthenticated(); // true

// 인증 데이터 가져오기
dynamic token = Auth.data(field: 'token'); // "abc123"

// 로그아웃
await Auth.logout();
```


<div id="authenticating-users"></div>

## 사용자 인증

`Auth.authenticate()`를 사용하여 사용자 세션 데이터를 저장합니다:

``` dart
// Map 사용
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// Model 클래스 사용
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// 데이터 없이 (타임스탬프 저장)
await Auth.authenticate();
```

### 실제 사용 예시

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. API를 호출하여 인증
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "유효하지 않은 자격 증명");
      return;
    }

    // 2. 인증된 사용자 저장
    await Auth.authenticate(data: user);

    // 3. 홈으로 이동
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## 인증 데이터 가져오기

`Auth.data()`를 사용하여 저장된 인증 데이터를 가져옵니다:

``` dart
// 모든 인증 데이터 가져오기
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// 특정 필드 가져오기
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

`Auth.data()` 메서드는 빠른 동기 접근을 위해 Backpack({{ config('app.name') }}의 인메모리 키-값 저장소)에서 읽습니다. 인증 시 데이터가 자동으로 Backpack에 동기화됩니다.


<div id="updating-auth-data"></div>

## 인증 데이터 업데이트

{{ config('app.name') }} v7에서는 인증 데이터를 업데이트하기 위해 `Auth.set()`이 도입되었습니다:

``` dart
// 특정 필드 업데이트
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// 새 필드 추가
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// 전체 데이터 교체
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## 로그아웃

`Auth.logout()`으로 인증된 사용자를 제거합니다:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // 로그인 페이지로 이동
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### 모든 세션에서 로그아웃

다중 세션을 사용하는 경우 모두 초기화합니다:

``` dart
// 기본 및 모든 명명된 세션에서 로그아웃
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## 인증 확인

현재 사용자가 인증되었는지 확인합니다:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // 사용자가 인증됨
  routeTo(HomePage.path);
} else {
  // 사용자가 로그인해야 함
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## 다중 세션

{{ config('app.name') }} v7은 다양한 컨텍스트를 위한 여러 명명된 인증 세션을 지원합니다. 이것은 서로 다른 유형의 인증을 별도로 추적해야 할 때 유용합니다 (예: 사용자 로그인 vs 기기 등록 vs 관리자 접근).

``` dart
// 기본 사용자 세션
await Auth.authenticate(data: user);

// 기기 인증 세션
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// 관리자 세션
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### 명명된 세션에서 읽기

``` dart
// 기본 세션
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// 기기 세션
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// 관리자 세션
dynamic adminData = Auth.data(session: 'admin');
```

### 세션별 로그아웃

``` dart
// 기본 세션에서만 로그아웃
await Auth.logout();

// 기기 세션에서만 로그아웃
await Auth.logout(session: 'device');

// 모든 세션에서 로그아웃
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### 세션별 인증 확인

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Device ID

{{ config('app.name') }} v7은 앱 세션 간에 유지되는 고유한 기기 식별자를 제공합니다:

``` dart
String deviceId = await Auth.deviceId();
// 예: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

Device ID는:
- 한 번 생성되어 영구적으로 저장됩니다
- 각 기기/설치에 고유합니다
- 기기 등록, 분석 또는 푸시 알림에 유용합니다

``` dart
// 예: 백엔드에 기기 등록
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // 기기 인증 저장
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## Backpack에 동기화

인증 데이터는 인증 시 자동으로 Backpack에 동기화됩니다. 수동으로 동기화하려면 (예: 앱 부팅 시):

``` dart
// 기본 세션 동기화
await Auth.syncToBackpack();

// 특정 세션 동기화
await Auth.syncToBackpack(session: 'device');

// 모든 세션 동기화
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

이것은 앱의 부팅 시퀀스에서 빠른 동기 접근을 위해 Backpack에 인증 데이터를 사용할 수 있도록 하는 데 유용합니다.


<div id="initial-route"></div>

## 초기 라우트

초기 라우트는 사용자가 앱을 열 때 처음 보는 페이지입니다. 라우터에서 `.initialRoute()`를 사용하여 설정합니다:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

`when` 파라미터를 사용하여 조건부 초기 라우트를 설정할 수도 있습니다:

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

어디서나 `routeToInitial()`을 사용하여 초기 라우트로 돌아갑니다:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## 인증된 라우트

인증된 라우트는 사용자가 로그인했을 때 초기 라우트를 재정의합니다. `.authenticatedRoute()`를 사용하여 설정합니다:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

앱이 부팅될 때:
- `Auth.isAuthenticated()`가 `true`를 반환 → 사용자가 **인증된 라우트** (HomePage)를 봅니다
- `Auth.isAuthenticated()`가 `false`를 반환 → 사용자가 **초기 라우트** (LoginPage)를 봅니다

조건부 인증된 라우트를 설정할 수도 있습니다:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

프로그래밍 방식으로 `routeToAuthenticatedRoute()`를 사용하여 인증된 라우트로 이동합니다:

``` dart
// 로그인 후
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**참고:** Guard 및 딥 링크를 포함한 전체 라우팅 문서는 [Router](/docs/{{ $version }}/router)를 참조하세요.


<div id="preview-route"></div>

## 미리보기 라우트

개발 중에 초기 라우트나 인증된 라우트를 변경하지 않고 특정 페이지를 빠르게 미리볼 수 있습니다. `.previewRoute()`를 사용합니다:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // 개발 중 가장 먼저 열림
});
```

`previewRoute()`는 `initialRoute()`와 `authenticatedRoute()` **모두**를 재정의하여 인증 상태에 관계없이 지정된 라우트를 첫 페이지로 표시합니다.

> **경고:** 앱을 릴리스하기 전에 `.previewRoute()`를 제거하세요.


<div id="unknown-route"></div>

## 알 수 없는 라우트

존재하지 않는 라우트로 사용자가 이동할 때 표시할 폴백 페이지를 정의합니다. `.unknownRoute()`를 사용하여 설정합니다:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### 모두 합치기

모든 라우트 타입이 포함된 완전한 라우터 설정입니다:

``` dart
appRouter() => nyRoutes((router) {
  // 인증되지 않은 사용자를 위한 첫 페이지
  router.add(LoginPage.path).initialRoute();

  // 인증된 사용자를 위한 첫 페이지
  router.add(HomePage.path).authenticatedRoute();

  // 404 페이지
  router.add(NotFoundPage.path).unknownRoute();

  // 일반 라우트
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| 라우트 메서드 | 용도 |
|--------------|---------|
| `.initialRoute()` | 인증되지 않은 사용자에게 표시되는 첫 페이지 |
| `.authenticatedRoute()` | 인증된 사용자에게 표시되는 첫 페이지 |
| `.previewRoute()` | 개발 중 둘 다 재정의 |
| `.unknownRoute()` | 라우트를 찾을 수 없을 때 표시 |


<div id="helper-functions"></div>

## 헬퍼 함수

{{ config('app.name') }} v7은 `Auth` 클래스 메서드를 미러링하는 헬퍼 함수를 제공합니다:

| 헬퍼 함수 | 동등한 메서드 |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | 기본 세션의 스토리지 키 |
| `authDeviceId()` | `Auth.deviceId()` |

모든 헬퍼는 선택적 `session` 파라미터를 포함하여 `Auth` 클래스의 대응 메서드와 동일한 파라미터를 받습니다:

``` dart
// 명명된 세션으로 인증
await authAuthenticate(data: device, session: 'device');

// 명명된 세션에서 읽기
dynamic deviceData = authData(session: 'device');

// 명명된 세션 확인
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
