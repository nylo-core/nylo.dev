# Navigation Hub

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
  - [Navigation Hub 생성](#creating-a-navigation-hub "Navigation Hub 생성")
  - [Navigation Tab 생성](#creating-navigation-tabs "Navigation Tab 생성")
  - [하단 내비게이션](#bottom-navigation "하단 내비게이션")
    - [커스텀 Nav Bar 빌더](#custom-nav-bar-builder "커스텀 Nav Bar 빌더")
  - [상단 내비게이션](#top-navigation "상단 내비게이션")
  - [Journey 내비게이션](#journey-navigation "Journey 내비게이션")
    - [Progress 스타일](#journey-progress-styles "Progress 스타일")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState 헬퍼 메서드](#journey-state-helper-methods "JourneyState 헬퍼 메서드")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [탭 내에서 내비게이션](#navigating-within-a-tab "탭 내에서 내비게이션")
- [탭](#tabs "탭")
  - [탭에 배지 추가](#adding-badges-to-tabs "탭에 배지 추가")
  - [탭에 알림 추가](#adding-alerts-to-tabs "탭에 알림 추가")
- [초기 인덱스](#initial-index "초기 인덱스")
- [상태 유지](#maintaining-state "상태 유지")
- [onTap](#on-tap "onTap")
- [State 액션](#state-actions "State 액션")
- [로딩 스타일](#loading-style "로딩 스타일")

<div id="introduction"></div>

## 소개

Navigation Hub는 모든 위젯의 내비게이션을 **관리**할 수 있는 중앙 허브입니다.
기본 제공되는 기능으로 하단, 상단 및 Journey 내비게이션 레이아웃을 몇 초 만에 생성할 수 있습니다.

앱에 하단 내비게이션 바를 추가하고 사용자가 앱의 다른 탭 간에 내비게이션할 수 있도록 하려는 상황을 **상상**해 보세요.

Navigation Hub를 사용하여 이를 구현할 수 있습니다.

앱에서 Navigation Hub를 사용하는 방법을 알아보겠습니다.

<div id="basic-usage"></div>

## 기본 사용법

아래 명령어를 사용하여 Navigation Hub를 생성할 수 있습니다.

``` bash
metro make:navigation_hub base
```

이 명령어는 대화형 설정 과정을 안내합니다:

1. **레이아웃 타입 선택** - `navigation_tabs` (하단 내비게이션) 또는 `journey_states` (순차적 흐름) 중 선택합니다.
2. **탭/상태 이름 입력** - 탭 또는 Journey 상태의 이름을 쉼표로 구분하여 입력합니다.

이렇게 하면 `resources/pages/navigation_hubs/base/` 디렉토리 아래에 파일이 생성됩니다:
- `base_navigation_hub.dart` - 메인 Hub 위젯
- `tabs/` 또는 `states/` - 각 탭 또는 Journey 상태를 위한 자식 위젯 포함

생성된 Navigation Hub의 모습입니다:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Navigation Hub에 **두 개의** 탭(Home과 Settings)이 있는 것을 볼 수 있습니다.

`layout` 메서드는 Hub의 레이아웃 타입을 반환합니다. 레이아웃을 구성할 때 테마 데이터와 미디어 쿼리에 접근할 수 있도록 `BuildContext`를 받습니다.

Navigation Hub에 `NavigationTab`을 추가하여 더 많은 탭을 생성할 수 있습니다.

먼저 Metro를 사용하여 새 위젯을 생성해야 합니다.

``` bash
metro make:stateful_widget news_tab
```

여러 위젯을 한 번에 생성할 수도 있습니다.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

그런 다음 새 위젯을 Navigation Hub에 추가할 수 있습니다.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Navigation Hub를 사용하려면 라우터에 초기 라우트로 추가합니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Navigation Hub로 할 수 있는 것이 **더 많습니다**. 몇 가지 기능을 살펴보겠습니다.

<div id="bottom-navigation"></div>

### 하단 내비게이션

`layout` 메서드에서 `NavigationHubLayout.bottomNav`를 반환하여 하단 내비게이션 바로 레이아웃을 설정할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

다음과 같은 속성을 설정하여 하단 내비게이션 바를 커스터마이징할 수 있습니다:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

`style` 매개변수를 사용하여 하단 내비게이션 바에 프리셋 스타일을 적용할 수 있습니다.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### 커스텀 Nav Bar 빌더

내비게이션 바를 완전히 제어하려면 `navBarBuilder` 매개변수를 사용할 수 있습니다.

이를 통해 내비게이션 데이터를 받으면서 모든 커스텀 위젯을 빌드할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` 객체에는 다음이 포함됩니다:

| 속성 | 타입 | 설명 |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | 내비게이션 바 항목 |
| `currentIndex` | `int` | 현재 선택된 인덱스 |
| `onTap` | `ValueChanged<int>` | 탭 탭 시 콜백 |

완전한 커스텀 글래스 Nav Bar의 예시입니다:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **참고:** `navBarBuilder`를 사용하면 `style` 매개변수는 무시됩니다.

<div id="top-navigation"></div>

### 상단 내비게이션

`layout` 메서드에서 `NavigationHubLayout.topNav`를 반환하여 상단 내비게이션 바로 레이아웃을 변경할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

다음과 같은 속성을 설정하여 상단 내비게이션 바를 커스터마이징할 수 있습니다:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Journey 내비게이션

`layout` 메서드에서 `NavigationHubLayout.journey`를 반환하여 Journey 내비게이션으로 레이아웃을 변경할 수 있습니다.

이는 온보딩 플로우 또는 다단계 폼에 적합합니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

Journey 레이아웃에 `backgroundGradient`를 설정할 수도 있습니다:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **참고:** `backgroundGradient`가 설정되면 `backgroundColor`보다 우선합니다.

Journey 내비게이션 레이아웃을 사용하려면 **위젯**에서 `JourneyState`를 사용해야 합니다. Journey를 관리하는 데 도움이 되는 많은 헬퍼 메서드가 포함되어 있습니다.

`make:navigation_hub` 명령어에서 `journey_states` 레이아웃을 선택하여 전체 Journey를 생성할 수 있습니다:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

이렇게 하면 Hub와 모든 Journey 상태 위젯이 `resources/pages/navigation_hubs/onboarding/states/` 아래에 생성됩니다.

또는 다음을 사용하여 개별 Journey 위젯을 생성할 수 있습니다:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

그런 다음 새 위젯을 Navigation Hub에 추가할 수 있습니다.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Journey Progress 스타일

`JourneyProgressStyle` 클래스를 사용하여 Progress 표시기 스타일을 커스터마이징할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

다음 Progress 표시기를 사용할 수 있습니다:

- `JourneyProgressIndicator.none()`: 아무것도 렌더링하지 않음 - 특정 탭에서 표시기를 숨기는 데 유용합니다.
- `JourneyProgressIndicator.linear()`: 선형 Progress 바.
- `JourneyProgressIndicator.dots()`: 도트 기반 Progress 표시기.
- `JourneyProgressIndicator.numbered()`: 번호가 매겨진 단계 Progress 표시기.
- `JourneyProgressIndicator.segments()`: 세그먼트형 Progress 바 스타일.
- `JourneyProgressIndicator.circular()`: 원형 Progress 표시기.
- `JourneyProgressIndicator.timeline()`: 타임라인 스타일 Progress 표시기.
- `JourneyProgressIndicator.custom()`: 빌더 함수를 사용한 커스텀 Progress 표시기.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

`JourneyProgressStyle` 내에서 Progress 표시기의 위치와 패딩을 커스터마이징할 수 있습니다:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

다음 Progress 표시기 위치를 사용할 수 있습니다:

- `ProgressIndicatorPosition.top`: 화면 상단에 Progress 표시기.
- `ProgressIndicatorPosition.bottom`: 화면 하단에 Progress 표시기.

#### 탭별 Progress 스타일 오버라이드

`NavigationTab.journey(progressStyle: ...)`를 사용하여 개별 탭에서 레이아웃 수준의 `progressStyle`을 오버라이드할 수 있습니다. 자체 `progressStyle`이 없는 탭은 레이아웃 기본값을 상속합니다. 레이아웃 기본값과 탭별 스타일이 모두 없는 탭은 Progress 표시기를 표시하지 않습니다.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // overrides the layout default for this tab only
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` 클래스는 온보딩 플로우와 다단계 Journey를 더 쉽게 만들 수 있도록 Journey 전용 기능으로 `NyState`를 확장합니다.

새 `JourneyState`를 생성하려면 아래 명령어를 사용할 수 있습니다.

``` bash
metro make:journey_widget onboard_user_dob
```

여러 위젯을 한 번에 생성하려면 다음 명령어를 사용할 수 있습니다.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

생성된 JourneyState 위젯의 모습입니다:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

**JourneyState** 클래스는 앞으로 이동하는 데 `nextStep`을 사용하고 뒤로 이동하는 데 `onBackPressed`를 사용하는 것을 볼 수 있습니다.

`nextStep` 메서드는 전체 유효성 검사 생명주기를 실행합니다: `canContinue()` -> `onBeforeNext()` -> 내비게이션 (마지막 단계인 경우 `onComplete()`) -> `onAfterNext()`.

`buildJourneyContent`를 사용하여 선택적 내비게이션 버튼이 포함된 구조화된 레이아웃을 빌드할 수도 있습니다:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

`buildJourneyContent` 메서드에서 사용할 수 있는 속성입니다.

| 속성 | 타입 | 설명 |
| --- | --- | --- |
| `content` | `Widget` | 페이지의 메인 콘텐츠. |
| `nextButton` | `Widget?` | 다음 버튼 위젯. |
| `backButton` | `Widget?` | 뒤로 버튼 위젯. |
| `contentPadding` | `EdgeInsetsGeometry` | 콘텐츠의 패딩. |
| `header` | `Widget?` | 헤더 위젯. |
| `footer` | `Widget?` | 푸터 위젯. |
| `crossAxisAlignment` | `CrossAxisAlignment` | 콘텐츠의 교차 축 정렬. |

<div id="journey-state-helper-methods"></div>

### JourneyState 헬퍼 메서드

`JourneyState` 클래스에는 Journey의 동작을 커스터마이징하는 데 사용할 수 있는 헬퍼 메서드와 속성이 있습니다.

| 메서드 / 속성 | 설명 |
| --- | --- |
| [`nextStep()`](#next-step) | 유효성 검사와 함께 다음 단계로 이동. `Future<bool>`을 반환. |
| [`previousStep()`](#previous-step) | 이전 단계로 이동. `Future<bool>`을 반환. |
| [`onBackPressed()`](#on-back-pressed) | 이전 단계로 이동하는 간단한 헬퍼. |
| [`onComplete()`](#on-complete) | Journey가 완료될 때 호출 (마지막 단계에서). |
| [`onBeforeNext()`](#on-before-next) | 다음 단계로 이동하기 전에 호출. |
| [`onAfterNext()`](#on-after-next) | 다음 단계로 이동한 후 호출. |
| [`canContinue()`](#can-continue) | 다음 단계로 이동 전 유효성 검사 확인. |
| [`isFirstStep`](#is-first-step) | Journey의 첫 번째 단계인 경우 true 반환. |
| [`isLastStep`](#is-last-step) | Journey의 마지막 단계인 경우 true 반환. |
| [`currentStep`](#current-step) | 현재 단계 인덱스 반환 (0부터 시작). |
| [`totalSteps`](#total-steps) | 총 단계 수 반환. |
| [`completionPercentage`](#completion-percentage) | 완료 백분율 반환 (0.0 ~ 1.0). |
| [`goToStep(int index)`](#go-to-step) | 인덱스로 특정 단계로 이동. |
| [`goToNextStep()`](#go-to-next-step) | 다음 단계로 이동 (유효성 검사 없음). |
| [`goToPreviousStep()`](#go-to-previous-step) | 이전 단계로 이동 (유효성 검사 없음). |
| [`goToFirstStep()`](#go-to-first-step) | 첫 번째 단계로 이동. |
| [`goToLastStep()`](#go-to-last-step) | 마지막 단계로 이동. |
| [`exitJourney()`](#exit-journey) | 루트 네비게이터를 팝하여 Journey 종료. |
| [`resetCurrentStep()`](#reset-current-step) | 현재 단계의 상태 리셋. |
| [`onJourneyComplete`](#on-journey-complete) | Journey 완료 시 콜백 (마지막 단계에서 오버라이드). |
| [`buildJourneyPage()`](#build-journey-page) | Scaffold가 포함된 전체 화면 Journey 페이지 빌드. |


<div id="next-step"></div>

#### nextStep

`nextStep` 메서드는 전체 유효성 검사와 함께 다음 단계로 이동합니다. 생명주기를 실행합니다: `canContinue()` -> `onBeforeNext()` -> 내비게이션 또는 `onComplete()` -> `onAfterNext()`.

`force: true`를 전달하여 유효성 검사를 건너뛰고 직접 이동할 수 있습니다.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

유효성 검사를 건너뛰려면:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

`previousStep` 메서드는 이전 단계로 이동합니다. 성공하면 `true`를 반환하고, 이미 첫 번째 단계인 경우 `false`를 반환합니다.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` 메서드는 내부적으로 `previousStep()`을 호출하는 간단한 헬퍼입니다.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` 메서드는 마지막 단계에서 `nextStep()`이 트리거될 때 (유효성 검사 통과 후) 호출됩니다.

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` 메서드는 다음 단계로 이동하기 전에 호출됩니다.

예를 들어, 다음 단계로 이동하기 전에 데이터를 저장하려면 여기서 할 수 있습니다.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` 메서드는 다음 단계로 이동한 후에 호출됩니다.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` 메서드는 `nextStep()`이 트리거될 때 호출됩니다. `false`를 반환하면 내비게이션을 방지합니다.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` 속성은 Journey의 첫 번째 단계인 경우 true를 반환합니다.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` 속성은 Journey의 마지막 단계인 경우 true를 반환합니다.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

`currentStep` 속성은 현재 단계 인덱스를 반환합니다 (0부터 시작).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

`totalSteps` 속성은 Journey의 총 단계 수를 반환합니다.

<div id="completion-percentage"></div>

#### completionPercentage

`completionPercentage` 속성은 0.0에서 1.0 사이의 완료 백분율을 반환합니다.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` 메서드는 인덱스로 특정 단계로 직접 이동합니다. 이것은 유효성 검사를 트리거하지 **않습니다**.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` 메서드는 유효성 검사 없이 다음 단계로 이동합니다. 이미 마지막 단계인 경우 아무것도 하지 않습니다.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` 메서드는 유효성 검사 없이 이전 단계로 이동합니다. 이미 첫 번째 단계인 경우 아무것도 하지 않습니다.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` 메서드는 첫 번째 단계로 이동합니다.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` 메서드는 마지막 단계로 이동합니다.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

`exitJourney` 메서드는 루트 네비게이터를 팝하여 Journey를 종료합니다.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

`resetCurrentStep` 메서드는 현재 단계의 상태를 리셋합니다.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

`onJourneyComplete` getter는 Journey의 **마지막 단계**에서 오버라이드하여 사용자가 플로우를 완료할 때 일어나는 일을 정의할 수 있습니다.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

`buildJourneyPage` 메서드는 `Scaffold`와 `SafeArea`로 래핑된 전체 화면 Journey 페이지를 빌드합니다.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| 속성 | 타입 | 설명 |
| --- | --- | --- |
| `content` | `Widget` | 페이지의 메인 콘텐츠. |
| `nextButton` | `Widget?` | 다음 버튼 위젯. |
| `backButton` | `Widget?` | 뒤로 버튼 위젯. |
| `contentPadding` | `EdgeInsetsGeometry` | 콘텐츠의 패딩. |
| `header` | `Widget?` | 헤더 위젯. |
| `footer` | `Widget?` | 푸터 위젯. |
| `backgroundColor` | `Color?` | Scaffold의 배경 색상. |
| `appBar` | `Widget?` | 선택적 AppBar 위젯. |
| `crossAxisAlignment` | `CrossAxisAlignment` | 콘텐츠의 교차 축 정렬. |

<div id="navigating-within-a-tab"></div>

## 탭 내에서 위젯으로 내비게이션

`pushTo` 헬퍼를 사용하여 탭 내의 위젯으로 내비게이션할 수 있습니다.

탭 내부에서 `pushTo` 헬퍼를 사용하여 다른 위젯으로 내비게이션할 수 있습니다.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

내비게이션하는 위젯에 데이터를 전달할 수도 있습니다.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## 탭

탭은 Navigation Hub의 주요 구성 요소입니다.

`NavigationTab` 클래스와 네임드 생성자를 사용하여 Navigation Hub에 탭을 추가할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

위의 예시에서 Navigation Hub에 Home과 Settings 두 개의 탭을 추가했습니다.

다양한 종류의 탭을 사용할 수 있습니다:

- `NavigationTab.tab()` - 표준 내비게이션 탭.
- `NavigationTab.badge()` - 배지 카운트가 있는 탭.
- `NavigationTab.alert()` - 알림 표시기가 있는 탭.
- `NavigationTab.journey()` - Journey 내비게이션 레이아웃용 탭.

<div id="adding-badges-to-tabs"></div>

## 탭에 배지 추가

탭에 배지를 쉽게 추가할 수 있습니다.

배지는 사용자에게 탭에 새로운 것이 있다는 것을 보여주는 좋은 방법입니다.

예를 들어, 채팅 앱이 있다면 채팅 탭에 읽지 않은 메시지 수를 표시할 수 있습니다.

탭에 배지를 추가하려면 `NavigationTab.badge` 생성자를 사용할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

위의 예시에서 초기 카운트가 10인 배지를 Chat 탭에 추가했습니다.

프로그래밍 방식으로 배지 카운트를 업데이트할 수도 있습니다.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

기본적으로 배지 카운트는 기억됩니다. 각 세션마다 배지 카운트를 **초기화**하려면 `rememberCount`를 `false`로 설정할 수 있습니다.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## 탭에 알림 추가

탭에 알림을 추가할 수 있습니다.

때로는 배지 카운트를 표시하고 싶지 않지만 사용자에게 알림 표시기를 보여주고 싶을 수 있습니다.

탭에 알림을 추가하려면 `NavigationTab.alert` 생성자를 사용할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

이렇게 하면 빨간색의 알림이 Chat 탭에 추가됩니다.

프로그래밍 방식으로 알림을 업데이트할 수도 있습니다.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## 초기 인덱스

기본적으로 Navigation Hub는 첫 번째 탭 (인덱스 0)에서 시작합니다. `initialIndex` getter를 오버라이드하여 변경할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## 상태 유지

기본적으로 Navigation Hub의 상태는 유지됩니다.

이는 탭으로 이동할 때 탭의 상태가 보존된다는 것을 의미합니다.

탭으로 이동할 때마다 탭의 상태를 초기화하려면 `maintainState`를 `false`로 설정할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

`onTap` 메서드를 오버라이드하여 탭을 탭할 때 커스텀 로직을 추가할 수 있습니다.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## State 액션

State 액션은 앱 어디에서나 Navigation Hub와 상호작용하는 방법입니다.

사용할 수 있는 State 액션입니다:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

State 액션을 사용하려면 다음과 같이 하면 됩니다:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## 로딩 스타일

기본적으로 Navigation Hub는 탭이 로딩 중일 때 **기본** 로딩 Widget (resources/widgets/loader_widget.dart)을 표시합니다.

`loadingStyle`을 커스터마이징하여 로딩 스타일을 변경할 수 있습니다.

| 스타일 | 설명 |
| --- | --- |
| normal | 기본 로딩 스타일 |
| skeletonizer | 스켈레톤 로딩 스타일 |
| none | 로딩 스타일 없음 |

로딩 스타일은 다음과 같이 변경할 수 있습니다:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

스타일 중 하나의 로딩 Widget을 변경하려면 `LoadingStyle`에 `child`를 전달할 수 있습니다.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

이제 탭이 로딩 중일 때 "Loading..." 텍스트가 표시됩니다.

아래 예시:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Navigation Hub 생성

Navigation Hub를 생성하려면 [Metro](/docs/{{$version}}/metro)를 사용하여 아래 명령어를 실행합니다.

``` bash
metro make:navigation_hub base
```

이 명령어는 레이아웃 타입을 선택하고 탭 또는 Journey 상태를 정의할 수 있는 대화형 설정을 안내합니다.

이렇게 하면 `resources/pages/navigation_hubs/base/` 디렉토리에 `base_navigation_hub.dart` 파일이 생성되며, 자식 위젯은 `tabs/` 또는 `states/` 하위 폴더에 구성됩니다.
