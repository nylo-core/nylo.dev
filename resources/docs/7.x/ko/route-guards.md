# Route Guards

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [Route Guard 생성](#creating-a-route-guard "Route Guard 생성")
- [Guard 생명주기](#guard-lifecycle "Guard 생명주기")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Guard 액션](#guard-actions "Guard 액션")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [라우트에 Guard 적용](#applying-guards "라우트에 Guard 적용")
- [그룹 Guard](#group-guards "그룹 Guard")
- [Guard 조합](#guard-composition "Guard 조합")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

Route Guard는 {{ config('app.name') }}에서 **내비게이션 미들웨어**를 제공합니다. 라우트 전환을 가로채서 사용자가 페이지에 접근할 수 있는지 제어하거나, 다른 곳으로 리다이렉트하거나, 라우트에 전달되는 데이터를 수정할 수 있습니다.

일반적인 사용 사례:
- **인증 확인** -- 인증되지 않은 사용자를 로그인 페이지로 리다이렉트
- **역할 기반 접근** -- 관리자 사용자에게만 페이지 제한
- **데이터 유효성 검사** -- 내비게이션 전 필수 데이터 존재 확인
- **데이터 보강** -- 라우트에 추가 데이터 첨부

Guard는 내비게이션 발생 전 **순서대로** 실행됩니다. Guard가 `handled`를 반환하면 내비게이션이 중지됩니다 (리다이렉트 또는 중단).

<div id="creating-a-route-guard"></div>

## Route Guard 생성

Metro CLI를 사용하여 Route Guard를 생성합니다:

``` bash
metro make:route_guard auth
```

Guard 파일이 생성됩니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Guard 로직을 여기에 추가
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Guard 생명주기

모든 Route Guard에는 세 가지 생명주기 메서드가 있습니다:

<div id="on-before"></div>

### onBefore

내비게이션 발생 **전**에 호출됩니다. 여기서 조건을 확인하고 내비게이션을 허용, 리다이렉트 또는 중단할지 결정합니다.

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

반환값:
- `next()` -- 다음 Guard로 진행하거나 라우트로 내비게이션
- `redirect(path)` -- 다른 라우트로 리다이렉트
- `abort()` -- 내비게이션 완전 취소

<div id="on-after"></div>

### onAfter

성공적인 내비게이션 **후**에 호출됩니다. 분석, 로깅 또는 내비게이션 후 부수 효과에 사용합니다.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // 페이지 뷰 기록
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

사용자가 라우트를 **떠날 때** 호출됩니다. `false`를 반환하면 사용자가 떠나는 것을 방지합니다.

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // 확인 대화상자 표시
    return await showConfirmDialog();
  }
  return true; // 떠나기 허용
}
```

<div id="route-context"></div>

## RouteContext

`RouteContext` 객체는 모든 Guard 생명주기 메서드에 전달되며 내비게이션에 대한 정보를 포함합니다:

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `context` | `BuildContext?` | 현재 빌드 컨텍스트 |
| `data` | `dynamic` | 라우트에 전달된 데이터 |
| `queryParameters` | `Map<String, String>` | URL 쿼리 매개변수 |
| `routeName` | `String` | 대상 라우트의 이름/경로 |
| `originalRouteName` | `String?` | 변환 전 원래 라우트 이름 |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // 라우트 정보 접근
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Route Context 변환

다른 데이터로 복사본을 생성합니다:

``` dart
// 데이터 타입 변경
RouteContext<User> userContext = context.withData<User>(currentUser);

// 수정된 필드로 복사
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Guard 액션

<div id="next"></div>

### next

체인의 다음 Guard로 진행하거나, 마지막 Guard인 경우 라우트로 내비게이션합니다:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

사용자를 다른 라우트로 리다이렉트합니다:

``` dart
return redirect(LoginPage.path);
```

추가 옵션과 함께:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `path` | `Object` | 필수 | 라우트 경로 문자열 또는 RouteView |
| `data` | `dynamic` | null | 리다이렉트 라우트에 전달할 데이터 |
| `queryParameters` | `Map<String, dynamic>?` | null | 쿼리 매개변수 |
| `navigationType` | `NavigationType` | `pushReplace` | 내비게이션 방식 |
| `result` | `dynamic` | null | 반환할 결과 |
| `removeUntilPredicate` | `Function?` | null | 라우트 제거 조건 |
| `transitionType` | `TransitionType?` | null | 페이지 전환 타입 |
| `onPop` | `Function(dynamic)?` | null | pop 시 콜백 |

<div id="abort"></div>

### abort

리다이렉트 없이 내비게이션을 취소합니다. 사용자는 현재 페이지에 머물게 됩니다:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

후속 Guard와 대상 라우트에 전달될 데이터를 수정합니다:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // 라우트 데이터 보강
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## 라우트에 Guard 적용

라우터 파일에서 개별 라우트에 Guard를 추가합니다:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // 단일 Guard 추가
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // 다중 Guard 추가 (순서대로 실행)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## 그룹 Guard

라우트 그룹을 사용하여 여러 라우트에 한 번에 Guard를 적용합니다:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // 이 그룹의 모든 라우트는 인증이 필요합니다
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

## Guard 조합

{{ config('app.name') }}은 재사용 가능한 패턴을 위해 Guard를 함께 조합하는 도구를 제공합니다.

<div id="guard-stack"></div>

### GuardStack

여러 Guard를 하나의 재사용 가능한 Guard로 결합합니다:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// 라우트에 스택 사용
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack`은 Guard를 순서대로 실행합니다. Guard가 `handled`를 반환하면 나머지 Guard는 건너뜁니다.

<div id="conditional-guard"></div>

### ConditionalGuard

조건이 참일 때만 Guard를 적용합니다:

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

조건이 `false`를 반환하면 Guard를 건너뛰고 내비게이션이 계속됩니다.

<div id="parameterized-guard"></div>

### ParameterizedGuard

설정 매개변수를 받는 Guard를 생성합니다:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = 허용된 역할

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// 사용법
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## 예제

### 인증 Guard

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

### 매개변수가 있는 구독 Guard

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

// 프리미엄 또는 프로 구독 필요
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### 로깅 Guard

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
