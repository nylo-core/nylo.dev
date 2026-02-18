# Button

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [사용 가능한 버튼 타입](#button-types "사용 가능한 버튼 타입")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [비동기 로딩 상태](#async-loading "비동기 로딩 상태")
- [애니메이션 스타일](#animation-styles "애니메이션 스타일")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [스플래시 스타일](#splash-styles "스플래시 스타일")
- [로딩 스타일](#loading-styles "로딩 스타일")
- [폼 제출](#form-submission "폼 제출")
- [버튼 커스터마이징](#customizing-buttons "버튼 커스터마이징")
- [매개변수 참조](#parameters "매개변수 참조")


<div id="introduction"></div>

## 소개

{{ config('app.name') }}은 8가지 사전 빌드된 버튼 스타일이 포함된 `Button` 클래스를 기본 제공합니다. 각 버튼에는 다음이 내장되어 있습니다:

- **비동기 로딩 상태** -- `onPressed`에서 `Future`를 반환하면 버튼이 자동으로 로딩 인디케이터를 표시합니다
- **애니메이션 스타일** -- clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph, shake 효과 중 선택
- **스플래시 스타일** -- ripple, highlight, glow 또는 ink 터치 피드백 추가
- **폼 제출** -- 버튼을 `NyFormData` 인스턴스에 직접 연결

앱의 버튼 정의는 `lib/resources/widgets/buttons/buttons.dart`에서 찾을 수 있습니다. 이 파일에는 각 버튼 타입에 대한 정적 메서드가 있는 `Button` 클래스가 포함되어 있어 프로젝트의 기본값을 쉽게 커스터마이즈할 수 있습니다.

<div id="basic-usage"></div>

## 기본 사용법

위젯 어디에서든 `Button` 클래스를 사용할 수 있습니다. 페이지 내부의 간단한 예제입니다:

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

모든 버튼 타입은 동일한 패턴을 따릅니다 -- `text` 라벨과 `onPressed` 콜백을 전달합니다.

<div id="button-types"></div>

## 사용 가능한 버튼 타입

모든 버튼은 정적 메서드를 사용하여 `Button` 클래스를 통해 접근합니다.

<div id="primary"></div>

### Primary

테마의 기본 색상을 사용하는 그림자가 있는 채워진 버튼입니다. 주요 행동 유도(call-to-action) 요소에 가장 적합합니다.

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

부드러운 표면 색상과 미묘한 그림자가 있는 채워진 버튼입니다. Primary 버튼과 함께 보조 액션에 적합합니다.

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

테두리 선이 있는 투명 버튼입니다. 덜 눈에 띄는 액션이나 취소 버튼에 유용합니다.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

테두리와 텍스트 색상을 커스터마이즈할 수 있습니다:

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

배경이나 테두리가 없는 최소한의 버튼입니다. 인라인 액션이나 링크에 이상적입니다.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

텍스트 색상을 커스터마이즈할 수 있습니다:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

텍스트와 함께 아이콘을 표시하는 채워진 버튼입니다. 기본적으로 아이콘은 텍스트 앞에 나타납니다.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

배경색을 커스터마이즈할 수 있습니다:

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

선형 그라데이션 배경이 있는 버튼입니다. 기본적으로 테마의 primary와 tertiary 색상을 사용합니다.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

커스텀 그라데이션 색상을 제공할 수 있습니다:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

완전히 둥근 모서리가 있는 알약 모양 버튼입니다. 테두리 반경은 기본적으로 버튼 높이의 절반입니다.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

배경색과 테두리 반경을 커스터마이즈할 수 있습니다:

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

배경 블러 효과가 있는 프로스트 글라스 스타일 버튼입니다. 이미지나 색상 배경 위에 배치할 때 잘 어울립니다.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

텍스트 색상을 커스터마이즈할 수 있습니다:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## 비동기 로딩 상태

{{ config('app.name') }} 버튼의 가장 강력한 기능 중 하나는 **자동 로딩 상태 관리**입니다. `onPressed` 콜백이 `Future`를 반환하면 버튼은 자동으로 로딩 인디케이터를 표시하고 작업이 완료될 때까지 상호작용을 비활성화합니다.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

비동기 작업이 실행되는 동안 버튼은 (기본적으로) 스켈레톤 로딩 효과를 표시합니다. `Future`가 완료되면 버튼은 정상 상태로 돌아갑니다.

이것은 모든 비동기 작업에서 동작합니다 -- API 호출, 데이터베이스 쓰기, 파일 업로드 또는 `Future`를 반환하는 모든 것:

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

`isLoading` 상태 변수를 관리하거나, `setState`를 호출하거나, `StatefulWidget`으로 감쌀 필요가 없습니다 -- {{ config('app.name') }}이 모두 처리해줍니다.

### 작동 방식

버튼이 `onPressed`가 `Future`를 반환하는 것을 감지하면 `lockRelease` 메커니즘을 사용하여:

1. 로딩 인디케이터 표시 (`LoadingStyle`로 제어)
2. 중복 탭을 방지하기 위해 버튼 비활성화
3. `Future` 완료 대기
4. 버튼을 정상 상태로 복원

<div id="animation-styles"></div>

## 애니메이션 스타일

버튼은 `ButtonAnimationStyle`을 통해 누르기 애니메이션을 지원합니다. 이 애니메이션은 사용자가 버튼과 상호작용할 때 시각적 피드백을 제공합니다. `lib/resources/widgets/buttons/buttons.dart`에서 버튼을 커스터마이즈할 때 애니메이션 스타일을 설정할 수 있습니다.

<div id="anim-clickable"></div>

### Clickable

Duolingo 스타일의 3D 누르기 효과입니다. 버튼이 누를 때 아래로 이동하고 놓으면 다시 튕깁니다. 주요 액션과 게임 같은 UX에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

효과를 세밀하게 조정합니다:

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

누를 때 버튼을 축소하고 놓으면 다시 튕기는 효과입니다. 장바구니 추가, 좋아요, 즐겨찾기 버튼에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

효과를 세밀하게 조정합니다:

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

버튼을 누르고 있는 동안 미묘한 연속 스케일 펄스 효과입니다. 길게 누르기 액션이나 주의를 끌 때 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

효과를 세밀하게 조정합니다:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

누를 때 버튼을 수평으로 압축하고 수직으로 확장합니다. 재미있고 인터랙티브한 UI에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

효과를 세밀하게 조정합니다:

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

흔들리는 탄성 변형 효과입니다. 재미있고 캐주얼한 엔터테인먼트 앱에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

효과를 세밀하게 조정합니다:

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

누를 때 버튼을 가로지르는 광택 하이라이트 효과입니다. 프리미엄 기능이나 주의를 끌고 싶은 CTA에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

효과를 세밀하게 조정합니다:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

터치 포인트에서 확장되는 강화된 리플 효과입니다. Material Design 강조에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

효과를 세밀하게 조정합니다:

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

누를 때 버튼의 테두리 반경이 증가하여 형태 변환 효과를 만듭니다. 미묘하고 우아한 피드백에 가장 적합합니다.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

효과를 세밀하게 조정합니다:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

수평 흔들기 애니메이션입니다. 오류 상태나 잘못된 액션에 가장 적합합니다 -- 버튼을 흔들어 문제가 발생했음을 알립니다.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

효과를 세밀하게 조정합니다:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### 애니메이션 비활성화

애니메이션 없이 버튼을 사용하려면:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### 기본 애니메이션 변경

버튼 타입의 기본 애니메이션을 변경하려면 `lib/resources/widgets/buttons/buttons.dart` 파일을 수정합니다:

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

## 스플래시 스타일

스플래시 효과는 버튼에 시각적 터치 피드백을 제공합니다. `ButtonSplashStyle`을 통해 설정합니다. 스플래시 스타일은 애니메이션 스타일과 결합하여 레이어드 피드백을 제공할 수 있습니다.

### 사용 가능한 스플래시 스타일

| 스플래시 | 팩토리 | 설명 |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | 터치 포인트에서 시작하는 표준 Material 리플 |
| Highlight | `ButtonSplashStyle.highlight()` | 리플 애니메이션 없는 미묘한 하이라이트 |
| Glow | `ButtonSplashStyle.glow()` | 터치 포인트에서 발산하는 부드러운 글로우 |
| Ink | `ButtonSplashStyle.ink()` | 빠르고 반응성 높은 잉크 스플래시 |
| None | `ButtonSplashStyle.none()` | 스플래시 효과 없음 |
| Custom | `ButtonSplashStyle.custom()` | 스플래시 팩토리에 대한 완전한 제어 |

### 예제

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

스플래시 색상과 불투명도를 커스터마이즈할 수 있습니다:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## 로딩 스타일

비동기 작업 중에 표시되는 로딩 인디케이터는 `LoadingStyle`로 제어됩니다. 버튼 파일에서 버튼 타입별로 설정할 수 있습니다.

### Skeletonizer (기본값)

버튼 위에 시머 스켈레톤 효과를 표시합니다:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

로딩 위젯을 표시합니다 (기본값은 앱 로더):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

로딩 중에 버튼을 표시하지만 상호작용을 비활성화합니다:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## 폼 제출

모든 버튼은 `submitForm` 매개변수를 지원하며, 이는 버튼을 `NyForm`에 연결합니다. 탭하면 버튼이 폼의 유효성을 검사하고 폼 데이터와 함께 성공 핸들러를 호출합니다.

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

`submitForm` 매개변수는 두 값을 가진 레코드를 받습니다:
1. `NyFormData` 인스턴스 (또는 `String`으로 된 폼 이름)
2. 검증된 데이터를 받는 콜백

기본적으로 `showToastError`는 `true`이며, 폼 유효성 검사가 실패하면 토스트 알림을 표시합니다. 오류를 조용히 처리하려면 `false`로 설정합니다:

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

`submitForm` 콜백이 `Future`를 반환하면 버튼은 비동기 작업이 완료될 때까지 자동으로 로딩 상태를 표시합니다.

<div id="customizing-buttons"></div>

## 버튼 커스터마이징

모든 버튼 기본값은 프로젝트의 `lib/resources/widgets/buttons/buttons.dart`에 정의되어 있습니다. 각 버튼 타입은 `lib/resources/widgets/buttons/partials/`에 해당하는 위젯 클래스가 있습니다.

### 기본 스타일 변경

버튼의 기본 외관을 수정하려면 `Button` 클래스를 편집합니다:

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

### 버튼 위젯 커스터마이징

버튼 타입의 시각적 외관을 변경하려면 `lib/resources/widgets/buttons/partials/`에서 해당 위젯을 편집합니다. 예를 들어, Primary 버튼의 테두리 반경이나 그림자를 변경하려면:

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

### 새 버튼 타입 생성

새 버튼 타입을 추가하려면:

1. `lib/resources/widgets/buttons/partials/`에 `StatefulAppButton`을 확장하는 새 위젯 파일을 생성합니다.
2. `buildButton` 메서드를 구현합니다.
3. `Button` 클래스에 정적 메서드를 추가합니다.

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

그런 다음 `Button` 클래스에 등록합니다:

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

## 매개변수 참조

### 공통 매개변수 (모든 버튼 타입)

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `text` | `String` | 필수 | 버튼 라벨 텍스트 |
| `onPressed` | `VoidCallback?` | `null` | 버튼 탭 시 콜백. 자동 로딩 상태를 위해 `Future`를 반환 |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | 폼 제출 레코드 (폼 인스턴스, 성공 콜백) |
| `onFailure` | `Function(dynamic)?` | `null` | 폼 유효성 검사 실패 시 호출 |
| `showToastError` | `bool` | `true` | 폼 유효성 검사 오류 시 토스트 알림 표시 |
| `width` | `double?` | `null` | 버튼 너비 (기본값은 전체 너비) |

### 타입별 매개변수

#### Button.outlined

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | 테마 외곽선 색상 | 테두리 선 색상 |
| `textColor` | `Color?` | 테마 기본 색상 | 텍스트 색상 |

#### Button.textOnly

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | 테마 기본 색상 | 텍스트 색상 |

#### Button.icon

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `icon` | `Widget` | 필수 | 표시할 아이콘 위젯 |
| `color` | `Color?` | 테마 기본 색상 | 배경색 |

#### Button.gradient

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Primary 및 tertiary 색상 | 그라데이션 색상 정지점 |

#### Button.rounded

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | 테마 primary container 색상 | 배경색 |
| `borderRadius` | `BorderRadius?` | 알약 모양 (높이 / 2) | 모서리 반경 |

#### Button.transparency

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `color` | `Color?` | 테마 적응형 | 텍스트 색상 |

### ButtonAnimationStyle 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `duration` | `Duration` | 타입별 상이 | 애니메이션 지속 시간 |
| `curve` | `Curve` | 타입별 상이 | 애니메이션 커브 |
| `enableHapticFeedback` | `bool` | 타입별 상이 | 누를 때 햅틱 피드백 트리거 |
| `translateY` | `double` | `4.0` | Clickable: 수직 누르기 거리 |
| `shadowOffset` | `double` | `4.0` | Clickable: 그림자 깊이 |
| `scaleMin` | `double` | `0.92` | Bounce: 누를 때 최소 스케일 |
| `pulseScale` | `double` | `1.05` | Pulse: 펄스 중 최대 스케일 |
| `squeezeX` | `double` | `0.95` | Squeeze: 수평 압축 |
| `squeezeY` | `double` | `1.05` | Squeeze: 수직 확장 |
| `jellyStrength` | `double` | `0.15` | Jelly: 흔들림 강도 |
| `shineColor` | `Color` | `Colors.white` | Shine: 하이라이트 색상 |
| `shineWidth` | `double` | `0.3` | Shine: 샤인 밴드 너비 |
| `rippleScale` | `double` | `2.0` | Ripple: 확장 스케일 |
| `morphRadius` | `double` | `24.0` | Morph: 대상 테두리 반경 |
| `shakeOffset` | `double` | `8.0` | Shake: 수평 변위 |
| `shakeCount` | `int` | `3` | Shake: 진동 횟수 |

### ButtonSplashStyle 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | 테마 표면 색상 | 스플래시 효과 색상 |
| `highlightColor` | `Color?` | 테마 표면 색상 | 하이라이트 효과 색상 |
| `splashOpacity` | `double` | `0.12` | 스플래시 불투명도 |
| `highlightOpacity` | `double` | `0.06` | 하이라이트 불투명도 |
| `borderRadius` | `BorderRadius?` | `null` | 스플래시 클립 반경 |
