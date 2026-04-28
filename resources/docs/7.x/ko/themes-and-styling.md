# 테마 및 스타일링

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 테마
  - [라이트 & 다크 테마](#light-and-dark-themes "라이트 & 다크 테마")
  - [테마 생성](#creating-a-theme "테마 생성")
- 설정
  - [테마 색상](#theme-colors "테마 색상")
  - [색상 사용](#using-colors "색상 사용")
  - [기본 스타일](#base-styles "기본 스타일")
  - [색상 스타일 확장](#extending-color-styles "색상 스타일 확장")
  - [테마 전환](#switching-theme "테마 전환")
  - [폰트](#fonts "폰트")
  - [디자인](#design "디자인")
- [텍스트 Extension](#text-extensions "텍스트 Extension")


<div id="introduction"></div>

## 소개

테마를 사용하여 애플리케이션의 UI 스타일을 관리할 수 있습니다. 테마를 사용하면 텍스트의 글꼴 크기, 버튼 모양, 애플리케이션의 전반적인 외관 등을 변경할 수 있습니다.

테마에 익숙하지 않은 경우, Flutter 웹사이트의 예시가 시작하는 데 도움이 될 것입니다. <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">여기</a>를 참조하세요.

기본적으로 {{ config('app.name') }}에는 `Light mode`와 `Dark mode`를 위한 사전 구성된 테마가 포함되어 있습니다.

기기가 <b>'라이트/다크'</b> 모드에 진입하면 테마도 자동으로 업데이트됩니다.

<div id="light-and-dark-themes"></div>

## 라이트 & 다크 테마

각 테마는 `lib/resources/themes/` 아래 자체 하위 디렉토리에 위치합니다:

- 라이트 테마 – `lib/resources/themes/light/light_theme.dart`
- 라이트 색상 – `lib/resources/themes/light/light_theme_colors.dart`
- 다크 테마 – `lib/resources/themes/dark/dark_theme.dart`
- 다크 색상 – `lib/resources/themes/dark/dark_theme_colors.dart`

두 테마 모두 `lib/resources/themes/base_theme.dart`의 공통 빌더와 `lib/resources/themes/color_styles.dart`의 `ColorStyles` 인터페이스를 공유합니다.



<div id="creating-a-theme"></div>

## 테마 생성

앱에 여러 테마를 갖고 싶다면 쉬운 방법을 제공합니다. 테마에 익숙하지 않다면 따라해 보세요.

먼저 터미널에서 아래 명령어를 실행합니다

``` bash
dart run nylo_framework:main make:theme bright_theme
```

<b>참고:</b> **bright_theme**을 새 테마 이름으로 바꾸세요.

이렇게 하면 `lib/resources/themes/bright/`에 `bright_theme.dart`와 `bright_theme_colors.dart`를 모두 포함하는 새 테마 디렉토리가 생성되고, `lib/bootstrap/theme.dart`에 등록됩니다.

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>( // 새 테마가 자동으로 추가됨
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

새 테마의 색상은 **lib/resources/themes/bright/bright_theme_colors.dart** 파일에서 수정할 수 있습니다.

<div id="theme-colors"></div>

## 테마 색상

프로젝트에서 테마 색상을 관리하려면 `lib/resources/themes/light/`와 `lib/resources/themes/dark/` 디렉토리를 확인하세요. 각각에는 해당 테마의 색상 파일인 `light_theme_colors.dart`와 `dark_theme_colors.dart`가 포함되어 있습니다.

색상 값은 프레임워크에서 정의된 그룹(`general`, `appBar`, `bottomTabBar`)으로 구성됩니다. 테마의 색상 클래스는 `ColorStyles`를 확장하고 각 그룹의 인스턴스를 제공합니다:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// 일반 색상
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// 앱 바 색상
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// 하단 탭 바 색상
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## Widget에서 색상 사용

`nyColorStyle<T>(context)` 헬퍼를 사용하여 활성 테마의 색상을 읽습니다. 완전한 타입 지정을 위해 프로젝트의 `ColorStyles` 타입을 전달합니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// 위젯 빌드 내부:
final colors = nyColorStyle<ColorStyles>(context);

// 활성 테마의 배경색
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// 특정 테마의 색상 읽기 (활성 테마와 무관하게):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## 기본 스타일

기본 스타일을 사용하면 단일 인터페이스로 모든 테마를 설명할 수 있습니다. {{ config('app.name') }}는 `lib/resources/themes/color_styles.dart`를 제공하며, 이것은 `light_theme_colors.dart`와 `dark_theme_colors.dart` 모두가 구현하는 계약입니다.

`ColorStyles`는 프레임워크의 `ThemeColor`를 확장하며, 세 가지 미리 정의된 색상 그룹(`GeneralColors`, `AppBarColors`, `BottomTabBarColors`)을 노출합니다. 기본 테마 빌더(`lib/resources/themes/base_theme.dart`)는 `ThemeData`를 구성할 때 이 그룹들을 읽으므로, 여기에 추가하는 항목은 해당 위젯에 자동으로 연결됩니다.

<br>

파일 `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// 일반 색상
  @override
  GeneralColors get general;

  /// 앱 바 색상
  @override
  AppBarColors get appBar;

  /// 하단 탭 바 색상
  @override
  BottomTabBarColors get bottomTabBar;
}
```

세 그룹은 다음 필드를 노출합니다:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

이 기본값 이외의 필드(버튼, 아이콘, 배지 등)를 추가하려면 [색상 스타일 확장](#extending-color-styles)을 참조하세요.

<div id="extending-color-styles"></div>

## 색상 스타일 확장

세 가지 기본 그룹(`general`, `appBar`, `bottomTabBar`)은 시작점이지 한계가 아닙니다. `lib/resources/themes/color_styles.dart`는 자유롭게 수정할 수 있습니다. 기본값 위에 새 색상 그룹(또는 단일 필드)을 추가한 다음 각 테마의 색상 클래스에서 구현하세요.

**1. 커스텀 색상 그룹 정의**

관련 색상을 작은 불변 클래스로 묶습니다:

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. `ColorStyles`에 추가**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // 커스텀 그룹
  IconColors get icons;
}
```

**3. 각 테마의 색상 클래스에서 구현**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...기존 오버라이드...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

`dark_theme_colors.dart`에서도 다크 모드 값으로 동일한 `icons` 오버라이드를 반복하세요.

**4. Widget에서 사용**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## 테마 전환

{{ config('app.name') }}는 즉시 테마를 전환하는 기능을 지원합니다.

예를 들어, 사용자가 "다크 테마"를 활성화하기 위해 버튼을 탭할 때 테마를 전환해야 하는 경우입니다.

아래와 같이 할 수 있습니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // "다크 테마"를 사용하도록 테마 설정
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// 또는

TextButton(onPressed: () {

    // "라이트 테마"를 사용하도록 테마 설정
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## 폰트

{{ config('app.name') }}에서 앱 전체의 기본 폰트를 업데이트하는 것은 쉽습니다. `lib/config/design.dart`를 열고 `DesignConfig.appFont`를 업데이트합니다.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

저장소에 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> 라이브러리가 포함되어 있으므로 적은 노력으로 모든 폰트를 사용할 수 있습니다. 다른 Google 폰트로 전환하려면 호출을 변경하기만 하면 됩니다:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

공식 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> 라이브러리에서 폰트를 확인하여 더 자세히 알아보세요.

커스텀 폰트를 사용해야 하나요? 이 가이드를 확인하세요 - https://flutter.dev/docs/cookbook/design/fonts

폰트를 추가한 후 아래 예시처럼 변수를 변경합니다.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo는 커스텀 폰트의 예시로 사용됨
```

<div id="design"></div>

## 디자인

**lib/config/design.dart** 파일은 앱의 디자인 요소를 관리하는 데 사용됩니다. 모든 것은 `DesignConfig` 클래스를 통해 노출됩니다:

`DesignConfig.appFont`에는 앱의 폰트가 포함됩니다.

`DesignConfig.logo`는 앱의 로고를 표시하는 데 사용됩니다.

**lib/resources/widgets/logo_widget.dart**를 수정하여 로고 표시 방법을 커스터마이즈할 수 있습니다.

`DesignConfig.loader`는 로더를 표시하는 데 사용됩니다. {{ config('app.name') }}는 일부 헬퍼 메서드에서 이 변수를 기본 로더 Widget으로 사용합니다.

**lib/resources/widgets/loader_widget.dart**를 수정하여 로더 표시 방법을 커스터마이즈할 수 있습니다.

<div id="text-extensions"></div>

## 텍스트 Extension

{{ config('app.name') }}에서 사용할 수 있는 텍스트 Extension입니다.

| 규칙 이름   | 사용법 | 정보 |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | **displayLarge** textTheme 적용 |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | **displayMedium** textTheme 적용 |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | **displaySmall** textTheme 적용 |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | **headingLarge** textTheme 적용 |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | **headingMedium** textTheme 적용 |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | **headingSmall** textTheme 적용 |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | **titleLarge** textTheme 적용 |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | **titleMedium** textTheme 적용 |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | **titleSmall** textTheme 적용 |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | **bodyLarge** textTheme 적용 |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | **bodyMedium** textTheme 적용 |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | **bodySmall** textTheme 적용 |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | **labelLarge** textTheme 적용 |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | **labelMedium** textTheme 적용 |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | **labelSmall** textTheme 적용 |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Text Widget에 굵은 글꼴 적용 |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Text Widget에 가벼운 글꼴 적용 |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Text Widget에 다른 텍스트 색상 설정 |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | 폰트를 왼쪽으로 정렬 |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | 폰트를 오른쪽으로 정렬 |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | 폰트를 가운데로 정렬 |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Text Widget의 최대 줄 수 설정 |

<br>


<div id="text-extension-display-large"></div>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// colorStyles에서의 Color
```

<div id="text-extension-align-left"></div>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
