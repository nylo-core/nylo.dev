# 라우터

---

<a name="section-1"></a>

- [소개](#introduction "소개")
- 기본
  - [라우트 추가](#adding-routes "라우트 추가")
  - [페이지로 이동](#navigating-to-pages "페이지로 이동")
  - [초기 라우트](#initial-route "초기 라우트")
  - [미리보기 라우트](#preview-route "미리보기 라우트")
  - [인증된 라우트](#authenticated-route "인증된 라우트")
  - [알 수 없는 라우트](#unknown-route "알 수 없는 라우트")
- 다른 페이지로 데이터 전달
  - [다른 페이지로 데이터 전달](#passing-data-to-another-page "다른 페이지로 데이터 전달")
- 네비게이션
  - [네비게이션 타입](#navigation-types "네비게이션 타입")
  - [뒤로 이동](#navigating-back "뒤로 이동")
  - [조건부 네비게이션](#conditional-navigation "조건부 네비게이션")
  - [페이지 전환](#page-transitions "페이지 전환")
  - [라우트 히스토리](#route-history "라우트 히스토리")
  - [라우트 스택 업데이트](#update-route-stack "라우트 스택 업데이트")
- 라우트 매개변수
  - [라우트 매개변수 사용](#route-parameters "라우트 매개변수 사용")
  - [쿼리 매개변수](#query-parameters "쿼리 매개변수")
- Route Guard
  - [Route Guard 생성](#route-guards "Route Guard 생성")
  - [NyRouteGuard 라이프사이클](#nyroute-guard-lifecycle "NyRouteGuard 라이프사이클")
  - [Guard 헬퍼 메서드](#guard-helper-methods "Guard 헬퍼 메서드")
  - [매개변수화된 Guard](#parameterized-guards "매개변수화된 Guard")
  - [Guard 스택](#guard-stacks "Guard 스택")
  - [조건부 Guard](#conditional-guards "조건부 Guard")
- 라우트 그룹
  - [라우트 그룹](#route-groups "라우트 그룹")
- [딥 링킹](#deep-linking "딥 링킹")
- [고급](#advanced "고급")



<div id="introduction"></div>

## 소개

라우트를 사용하면 앱의 다양한 페이지를 정의하고 페이지 간 이동을 할 수 있습니다.

라우트가 필요한 경우:
- 앱에서 사용 가능한 페이지 정의
- 화면 간 사용자 이동
- 인증 뒤에 페이지 보호
- 한 페이지에서 다른 페이지로 데이터 전달
- URL에서의 딥 링크 처리

`lib/routes/router.dart` 파일에서 라우트를 추가할 수 있습니다.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // 라우트 추가
  // router.add(AccountPage.path);

});
```

> **팁:** 라우트를 수동으로 만들거나 <a href="/docs/{{ $version }}/metro">Metro</a> CLI 도구를 사용하여 자동으로 생성할 수 있습니다.

다음은 Metro를 사용하여 'account' 페이지를 만드는 예시입니다.

``` bash
metro make:page account_page
```

``` dart
// 새 라우트가 자동으로 /lib/routes/router.dart에 추가됩니다
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

한 뷰에서 다른 뷰로 데이터를 전달해야 할 수도 있습니다. {{ config('app.name') }}에서는 `NyStatefulWidget`(내장된 라우트 데이터 접근 기능이 있는 Stateful Widget)을 사용하여 이를 할 수 있습니다. 이에 대해 더 자세히 설명하겠습니다.


<div id="adding-routes"></div>

## 라우트 추가

프로젝트에 새 라우트를 추가하는 가장 쉬운 방법입니다.

아래 명령어를 실행하여 새 페이지를 생성합니다.

```bash
metro make:page profile_page
```

위 명령어를 실행하면, `ProfilePage`라는 새 Widget이 생성되어 `resources/pages/` 디렉토리에 추가됩니다.
또한 새 라우트가 `lib/routes/router.dart` 파일에 추가됩니다.

파일: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // 새 라우트
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## 페이지로 이동

`routeTo` 헬퍼를 사용하여 새 페이지로 이동할 수 있습니다.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## 초기 라우트

라우터에서 `.initialRoute()` 메서드를 사용하여 처음 로드될 페이지를 정의할 수 있습니다.

초기 라우트를 설정하면, 앱을 열 때 처음으로 로드되는 페이지가 됩니다.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // 새 초기 라우트
});
```


### 조건부 초기 라우트

`when` 매개변수를 사용하여 조건부 초기 라우트를 설정할 수도 있습니다:

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

### 초기 라우트로 이동

`routeToInitial()`을 사용하여 앱의 초기 라우트로 이동합니다:

``` dart
void _goHome() {
    routeToInitial();
}
```

이는 `.initialRoute()`로 표시된 라우트로 이동하고 네비게이션 스택을 초기화합니다.

<div id="preview-route"></div>

## 미리보기 라우트

개발 중에 초기 라우트를 영구적으로 변경하지 않고 특정 페이지를 빠르게 미리보기할 수 있습니다. `.previewRoute()`를 사용하여 임시로 라우트를 초기 라우트로 만듭니다:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // 개발 중 먼저 표시됩니다
});
```

`previewRoute()` 메서드:
- 기존 `initialRoute()` 및 `authenticatedRoute()` 설정을 무시합니다
- 지정된 라우트를 초기 라우트로 만듭니다
- 개발 중 특정 페이지를 빠르게 테스트하는 데 유용합니다

> **경고:** 앱을 출시하기 전에 `.previewRoute()`를 제거하세요!

<div id="authenticated-route"></div>

## 인증된 라우트

앱에서 사용자가 인증되었을 때 초기 라우트가 될 라우트를 정의할 수 있습니다.
이는 자동으로 기본 초기 라우트를 무시하고 로그인한 사용자가 처음 보는 페이지가 됩니다.

먼저, `Auth.authenticate({...})` 헬퍼를 사용하여 사용자를 로그인해야 합니다.

이제 앱을 열면 정의한 라우트가 사용자가 로그아웃할 때까지 기본 페이지가 됩니다.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // 인증 페이지
});
```

### 조건부 인증된 라우트

조건부 인증된 라우트를 설정할 수도 있습니다:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### 인증된 라우트로 이동

`routeToAuthenticatedRoute()` 헬퍼를 사용하여 인증된 페이지로 이동할 수 있습니다:

``` dart
routeToAuthenticatedRoute();
```

**참고:** 사용자 인증 및 세션 관리에 대한 자세한 내용은 [인증](/docs/{{ $version }}/authentication)을 확인하세요.


<div id="unknown-route"></div>

## 알 수 없는 라우트

`.unknownRoute()`를 사용하여 404/찾을 수 없는 시나리오를 처리하는 라우트를 정의할 수 있습니다:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

사용자가 존재하지 않는 라우트로 이동하면, 알 수 없는 라우트 페이지가 표시됩니다.


<div id="route-guards"></div>

## Route Guard

Route Guard는 무단 접근으로부터 페이지를 보호합니다. 네비게이션이 완료되기 전에 실행되어 조건에 따라 사용자를 리디렉션하거나 접근을 차단할 수 있습니다.

Route Guard가 필요한 경우:
- 인증되지 않은 사용자로부터 페이지 보호
- 접근 허용 전 권한 확인
- 조건에 따라 사용자 리디렉션 (예: 온보딩 미완료)
- 페이지 조회 로깅 또는 추적

새 Route Guard를 생성하려면, 아래 명령어를 실행합니다.

``` bash
metro make:route_guard dashboard
```

다음으로, 새 Route Guard를 라우트에 추가합니다.

``` dart
// 파일: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Guard 추가
    ]
  ); // 제한된 페이지
});
```

`addRouteGuard` 메서드를 사용하여 Route Guard를 설정할 수도 있습니다:

``` dart
// 파일: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // 또는 여러 Guard 추가

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard 라이프사이클

v7에서 Route Guard는 세 가지 라이프사이클 메서드를 가진 `NyRouteGuard` 클래스를 사용합니다:

- **`onBefore(RouteContext context)`** - 네비게이션 전에 호출됩니다. `next()`를 반환하여 계속, `redirect()`로 다른 곳으로 이동, 또는 `abort()`로 중지합니다.
- **`onAfter(RouteContext context)`** - 라우트로의 네비게이션이 성공한 후 호출됩니다.

### 기본 예시

파일: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // 페이지 접근 가능 여부 확인
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // 네비게이션 성공 후 페이지 조회 추적
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

`RouteContext` 클래스는 네비게이션 정보에 대한 접근을 제공합니다:

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `context` | `BuildContext?` | 현재 빌드 컨텍스트 |
| `data` | `dynamic` | 라우트에 전달된 데이터 |
| `queryParameters` | `Map<String, String>` | URL 쿼리 매개변수 |
| `routeName` | `String` | 라우트 이름/경로 |
| `originalRouteName` | `String?` | 변환 전 원래 라우트 이름 |

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

## Guard 헬퍼 메서드

### next()

다음 Guard 또는 라우트로 계속 진행합니다:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // 네비게이션 계속 허용
}
```

### redirect()

다른 라우트로 리디렉션합니다:

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

`redirect()` 메서드가 받는 매개변수:

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `path` | `Object` | 라우트 경로 또는 RouteView |
| `data` | `dynamic` | 라우트에 전달할 데이터 |
| `queryParameters` | `Map<String, dynamic>?` | 쿼리 매개변수 |
| `navigationType` | `NavigationType` | 네비게이션 타입 (기본값: pushReplace) |
| `transitionType` | `TransitionType?` | 페이지 전환 |
| `onPop` | `Function(dynamic)?` | 라우트 팝 시 콜백 |

### abort()

리디렉션 없이 네비게이션을 중지합니다:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // 사용자가 현재 라우트에 머무름
  }
  return next();
}
```

### setData()

후속 Guard 및 라우트에 전달되는 데이터를 수정합니다:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## 매개변수화된 Guard

라우트별로 Guard 동작을 설정해야 할 때 `ParameterizedGuard`를 사용합니다:

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

// 사용법:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Guard 스택

`GuardStack`을 사용하여 여러 Guard를 재사용 가능한 단일 Guard로 조합합니다:

``` dart
// 재사용 가능한 Guard 조합 생성
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## 조건부 Guard

조건에 따라 Guard를 적용합니다:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## 다른 페이지로 데이터 전달

이 섹션에서는 한 Widget에서 다른 Widget으로 데이터를 전달하는 방법을 보여줍니다.

Widget에서 `routeTo` 헬퍼를 사용하고 새 페이지에 보낼 `data`를 전달합니다.

``` dart
// HomePage Widget
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage Widget (다른 페이지)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // 또는
    print(data()); // Hello World
  };
```

추가 예시

``` dart
// 홈 페이지 Widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// 프로필 페이지 Widget (다른 페이지)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## 라우트 그룹

라우트 그룹은 관련 라우트를 조직하고 공유 설정을 적용합니다. 여러 라우트에 동일한 Guard, URL 접두사 또는 전환 스타일이 필요할 때 유용합니다.

라우트 그룹이 필요한 경우:
- 여러 페이지에 동일한 Route Guard 적용
- 라우트 집합에 URL 접두사 추가 (예: `/admin/...`)
- 관련 라우트에 동일한 페이지 전환 설정

아래 예시처럼 라우트 그룹을 정의할 수 있습니다.

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

#### 라우트 그룹의 선택적 설정:

| 설정 | 타입 | 설명 |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | 그룹의 모든 라우트에 Route Guard 적용 |
| `prefix` | `String` | 그룹의 모든 라우트 경로에 접두사 추가 |
| `transition_type` | `TransitionType` | 그룹의 모든 라우트에 전환 설정 |
| `transition` | `PageTransitionType` | 페이지 전환 타입 설정 (사용 중단, transition_type 사용) |
| `transition_settings` | `PageTransitionSettings` | 전환 설정 지정 |


<div id="route-parameters"></div>

## 라우트 매개변수 사용

새 페이지를 생성할 때, 라우트를 매개변수를 받도록 업데이트할 수 있습니다.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

이제 페이지로 이동할 때 `userId`를 전달할 수 있습니다.

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

새 페이지에서 매개변수에 다음과 같이 접근할 수 있습니다.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## 쿼리 매개변수

새 페이지로 이동할 때 쿼리 매개변수도 제공할 수 있습니다.

살펴보겠습니다.

```dart
  // 홈 페이지
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // 프로필 페이지로 이동

  ...

  // 프로필 페이지
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // 또는
    print(queryParameters()); // {"user": 7}
  };
```

> **참고:** 페이지 Widget이 `NyStatefulWidget`과 `NyPage` 클래스를 확장하는 한, `widget.queryParameters()`를 호출하여 라우트 이름에서 모든 쿼리 매개변수를 가져올 수 있습니다.

```dart
// 예시 페이지
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// 홈 페이지
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // 또는
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **팁:** 쿼리 매개변수는 HTTP 프로토콜을 따라야 합니다. 예: /account?userId=1&tab=2


<div id="page-transitions"></div>

## 페이지 전환

`router.dart` 파일을 수정하여 한 페이지에서 다른 페이지로 이동할 때 전환 효과를 추가할 수 있습니다.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // 아래에서 위로
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // 페이드
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### 사용 가능한 페이지 전환

#### 기본 전환
- **`TransitionType.fade()`** - 새 페이지가 페이드인되면서 이전 페이지가 페이드아웃
- **`TransitionType.theme()`** - 앱 테마의 페이지 전환 테마 사용

#### 방향 슬라이드 전환
- **`TransitionType.rightToLeft()`** - 화면 오른쪽 가장자리에서 슬라이드
- **`TransitionType.leftToRight()`** - 화면 왼쪽 가장자리에서 슬라이드
- **`TransitionType.topToBottom()`** - 화면 위쪽 가장자리에서 슬라이드
- **`TransitionType.bottomToTop()`** - 화면 아래쪽 가장자리에서 슬라이드

#### 페이드 포함 슬라이드 전환
- **`TransitionType.rightToLeftWithFade()`** - 오른쪽 가장자리에서 슬라이드 및 페이드
- **`TransitionType.leftToRightWithFade()`** - 왼쪽 가장자리에서 슬라이드 및 페이드

#### 변환 전환
- **`TransitionType.scale(alignment: ...)`** - 지정된 정렬 지점에서 확대
- **`TransitionType.rotate(alignment: ...)`** - 지정된 정렬 지점을 중심으로 회전
- **`TransitionType.size(alignment: ...)`** - 지정된 정렬 지점에서 성장

#### 결합 전환 (현재 Widget 필요)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - 현재 페이지가 오른쪽으로 나가면서 새 페이지가 왼쪽에서 진입
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - 현재 페이지가 왼쪽으로 나가면서 새 페이지가 오른쪽에서 진입
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - 현재 페이지가 아래로 나가면서 새 페이지가 위에서 진입
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - 현재 페이지가 위로 나가면서 새 페이지가 아래에서 진입

#### 팝 전환 (현재 Widget 필요)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - 현재 페이지가 오른쪽으로 나가고, 새 페이지는 제자리
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - 현재 페이지가 왼쪽으로 나가고, 새 페이지는 제자리
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - 현재 페이지가 아래로 나가고, 새 페이지는 제자리
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - 현재 페이지가 위로 나가고, 새 페이지는 제자리

#### Material Design 공유 축 전환
- **`TransitionType.sharedAxisHorizontal()`** - 수평 슬라이드 및 페이드 전환
- **`TransitionType.sharedAxisVertical()`** - 수직 슬라이드 및 페이드 전환
- **`TransitionType.sharedAxisScale()`** - 스케일 및 페이드 전환

#### 커스터마이징 매개변수
각 전환은 다음의 선택적 매개변수를 받습니다:

| 매개변수 | 설명 | 기본값 |
|-----------|-------------|---------|
| `curve` | 애니메이션 커브 | 플랫폼별 커브 |
| `duration` | 애니메이션 지속 시간 | 플랫폼별 지속 시간 |
| `reverseDuration` | 역방향 애니메이션 지속 시간 | duration과 동일 |
| `fullscreenDialog` | 라우트가 전체 화면 대화상자인지 여부 | `false` |
| `opaque` | 라우트가 불투명한지 여부 | `false` |


``` dart
// 홈 페이지 Widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## 네비게이션 타입

네비게이션할 때, `routeTo` 헬퍼를 사용하는 경우 다음 중 하나를 지정할 수 있습니다.

| 타입 | 설명 |
|------|-------------|
| `NavigationType.push` | 앱의 라우트 스택에 새 페이지 추가 |
| `NavigationType.pushReplace` | 현재 라우트를 교체하고, 새 라우트가 완료되면 이전 라우트 삭제 |
| `NavigationType.popAndPushNamed` | 현재 라우트를 네비게이터에서 제거하고 명명된 라우트로 대체 |
| `NavigationType.pushAndRemoveUntil` | 조건이 참이 될 때까지 라우트를 추가하고 제거 |
| `NavigationType.pushAndForgetAll` | 새 페이지로 이동하고 라우트 스택의 다른 모든 페이지 삭제 |

``` dart
// 홈 페이지 Widget
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

## 뒤로 이동

새 페이지에서 `pop()` 헬퍼를 사용하여 이전 페이지로 돌아갈 수 있습니다.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // 또는
    Navigator.pop(context);
  }
...
```

이전 Widget에 값을 반환하려면, 아래 예시처럼 `result`를 제공합니다.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// onPop 매개변수를 사용하여 이전 Widget에서 값 가져오기
// HomePage Widget
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## 조건부 네비게이션

`routeIf()`를 사용하여 조건이 충족될 때만 이동합니다:

``` dart
// 사용자가 로그인한 경우에만 이동
routeIf(isLoggedIn, DashboardPage.path);

// 추가 옵션 포함
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

조건이 `false`이면 네비게이션이 발생하지 않습니다.


<div id="route-history"></div>

## 라우트 히스토리

{{ config('app.name') }}에서 아래 헬퍼를 사용하여 라우트 히스토리 정보에 접근할 수 있습니다.

``` dart
// 라우트 히스토리 가져오기
Nylo.getRouteHistory(); // List<dynamic>

// 현재 라우트 가져오기
Nylo.getCurrentRoute(); // Route<dynamic>?

// 이전 라우트 가져오기
Nylo.getPreviousRoute(); // Route<dynamic>?

// 현재 라우트 이름 가져오기
Nylo.getCurrentRouteName(); // String?

// 이전 라우트 이름 가져오기
Nylo.getPreviousRouteName(); // String?

// 현재 라우트 인자 가져오기
Nylo.getCurrentRouteArguments(); // dynamic

// 이전 라우트 인자 가져오기
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## 라우트 스택 업데이트

`NyNavigator.updateStack()`을 사용하여 프로그래밍 방식으로 네비게이션 스택을 업데이트할 수 있습니다:

``` dart
// 라우트 목록으로 스택 업데이트
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// 특정 라우트에 데이터 전달
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

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | 필수 | 이동할 라우트 경로 목록 |
| `replace` | `bool` | `true` | 현재 스택 교체 여부 |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | 특정 라우트에 전달할 데이터 |

다음과 같은 경우에 유용합니다:
- 딥 링킹 시나리오
- 네비게이션 상태 복원
- 복잡한 네비게이션 플로우 구축


<div id="deep-linking"></div>

## 딥 링킹

딥 링킹을 사용하면 URL을 통해 앱 내 특정 콘텐츠로 직접 이동할 수 있습니다. 다음과 같은 경우에 유용합니다:
- 특정 앱 콘텐츠에 대한 직접 링크 공유
- 특정 인앱 기능을 대상으로 하는 마케팅 캠페인
- 특정 앱 화면을 열어야 하는 알림 처리
- 원활한 웹-앱 전환

## 설정

앱에서 딥 링킹을 구현하기 전에, 프로젝트가 올바르게 설정되어 있는지 확인하세요:

### 1. 플랫폼 설정

**iOS**: Xcode 프로젝트에서 Universal Links 설정
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter Universal Links 설정 가이드</a>

**Android**: AndroidManifest.xml에서 App Links 설정
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter App Links 설정 가이드</a>

### 2. 라우트 정의

딥 링크를 통해 접근 가능해야 하는 모든 라우트는 라우터 설정에 등록되어야 합니다:

```dart
// 파일: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // 기본 라우트
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // 매개변수가 있는 라우트
  router.add(HotelBookingPage.path);
});
```

## 딥 링크 사용

설정이 완료되면 앱에서 다양한 형식의 수신 URL을 처리할 수 있습니다:

### 기본 딥 링크

특정 페이지로의 간단한 네비게이션:

``` bash
https://yourdomain.com/profile       // 프로필 페이지 열기
https://yourdomain.com/settings      // 설정 페이지 열기
```

앱 내에서 프로그래밍 방식으로 이러한 네비게이션을 트리거하려면:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### 경로 매개변수

경로의 일부로 동적 데이터가 필요한 라우트:

#### 라우트 정의

```dart
class HotelBookingPage extends NyStatefulWidget {
  // 매개변수 자리표시자 {id}가 있는 라우트 정의
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // 경로 매개변수 접근
    final hotelId = queryParameters()["id"]; // URL ../hotel/87/booking에 대해 "87" 반환
    print("Loading hotel ID: $hotelId");

    // ID를 사용하여 호텔 데이터 가져오기 또는 작업 수행
  };

  // 나머지 페이지 구현
}
```

#### URL 형식

``` bash
https://yourdomain.com/hotel/87/booking
```

#### 프로그래밍 방식 네비게이션

```dart
// 매개변수와 함께 이동
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### 쿼리 매개변수

선택적 매개변수 또는 여러 동적 값이 필요한 경우:

#### URL 형식

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### 쿼리 매개변수 접근

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // 모든 쿼리 매개변수 가져오기
    final params = queryParameters();

    // 특정 매개변수 접근
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // 대안 접근 방법
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### 쿼리 매개변수와 함께 프로그래밍 방식 네비게이션

```dart
// 쿼리 매개변수와 함께 이동
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// 경로 매개변수와 쿼리 매개변수 결합
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## 딥 링크 처리

`RouteProvider`에서 딥 링크 이벤트를 처리할 수 있습니다:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // 딥 링크 처리
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // 딥 링크를 위한 라우트 스택 업데이트
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

### 딥 링크 테스트

개발 및 테스트 시, ADB(Android) 또는 xcrun(iOS)을 사용하여 딥 링크 활성화를 시뮬레이션할 수 있습니다:

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (시뮬레이터)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### 디버깅 팁

- init 메서드에서 모든 매개변수를 출력하여 올바른 파싱을 확인하세요
- 다양한 URL 형식을 테스트하여 앱이 올바르게 처리하는지 확인하세요
- 쿼리 매개변수는 항상 문자열로 수신되므로, 필요에 따라 적절한 타입으로 변환하세요

---

## 일반적인 패턴

### 매개변수 타입 변환

모든 URL 매개변수는 문자열로 전달되므로, 변환이 자주 필요합니다:

```dart
// 문자열 매개변수를 적절한 타입으로 변환
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### 선택적 매개변수

매개변수가 없을 수 있는 경우 처리:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // 특정 사용자 프로필 로드
} else {
  // 현재 사용자 프로필 로드
}

// 또는 hasQueryParameter 확인
if (hasQueryParameter('status')) {
  // status 매개변수로 작업 수행
} else {
  // 매개변수 부재 처리
}
```


<div id="advanced"></div>

## 고급

### 라우트 존재 여부 확인

라우터에 라우트가 등록되어 있는지 확인할 수 있습니다:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter 메서드

`NyRouter` 클래스는 몇 가지 유용한 메서드를 제공합니다:

| 메서드 | 설명 |
|--------|-------------|
| `getRegisteredRouteNames()` | 모든 등록된 라우트 이름을 목록으로 가져오기 |
| `getRegisteredRoutes()` | 모든 등록된 라우트를 맵으로 가져오기 |
| `containsRoutes(routes)` | 라우터에 모든 지정된 라우트가 포함되어 있는지 확인 |
| `getInitialRouteName()` | 초기 라우트 이름 가져오기 |
| `getAuthRouteName()` | 인증된 라우트 이름 가져오기 |
| `getUnknownRouteName()` | 알 수 없는/404 라우트 이름 가져오기 |

### 라우트 인자 가져오기

`NyRouter.args<T>()`를 사용하여 라우트 인자를 가져올 수 있습니다:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // 타입이 지정된 인자 가져오기
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument와 NyQueryParameters

라우트 간에 전달되는 데이터는 다음 클래스로 래핑됩니다:

``` dart
// NyArgument는 라우트 데이터를 포함
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters는 URL 쿼리 매개변수를 포함
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
