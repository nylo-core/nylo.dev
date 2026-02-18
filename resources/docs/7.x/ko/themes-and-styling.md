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

- 라이트 테마 - `lib/resources/themes/light_theme.dart`
- 다크 테마 - `lib/resources/themes/dark_theme.dart`

이 파일들 안에는 ThemeData와 ThemeStyle이 사전 정의되어 있습니다.



<div id="creating-a-theme"></div>

## 테마 생성

앱에 여러 테마를 갖고 싶다면 쉬운 방법을 제공합니다. 테마에 익숙하지 않다면 따라해 보세요.

먼저 터미널에서 아래 명령어를 실행합니다

``` bash
metro make:theme bright_theme
```

<b>참고:</b> **bright_theme**을 새 테마 이름으로 바꾸세요.

이렇게 하면 `/resources/themes/` 디렉토리에 새 테마가 생성되고 `/resources/themes/styles/`에 테마 색상 파일도 생성됩니다.

``` dart
// 앱 테마
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // 새 테마가 자동으로 추가됨
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

새 테마의 색상은 **/resources/themes/styles/bright_theme_colors.dart** 파일에서 수정할 수 있습니다.

<div id="theme-colors"></div>

## 테마 색상

프로젝트에서 테마 색상을 관리하려면 `lib/resources/themes/styles` 디렉토리를 확인하세요.
이 디렉토리에는 light_theme_colors.dart와 dark_theme_colors.dart의 스타일 색상이 포함되어 있습니다.

이 파일에서 아래와 유사한 내용을 볼 수 있습니다.

``` dart
// 예: 라이트 테마 색상
class LightThemeColors implements ColorStyles {
  // 일반
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // 앱 바
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // 버튼
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // 하단 탭 바
  @override
  Color get bottomTabBarBackground => Colors.white;

  // 하단 탭 바 - 아이콘
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // 하단 탭 바 - 라벨
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // 토스트 알림
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## Widget에서 색상 사용

``` dart
import 'package:flutter_app/config/theme.dart';
...

// 테마에 따라 라이트/다크 배경색 가져오기
ThemeColor.get(context).background

// "ThemeColor" 클래스 사용 예시
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// 또는

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // 라이트 테마 색상 - primary content
  ),
),
```

<div id="base-styles"></div>

## 기본 스타일

기본 스타일을 사용하면 코드의 한 곳에서 다양한 Widget 색상을 커스터마이즈할 수 있습니다.

{{ config('app.name') }}에는 `lib/resources/themes/styles/color_styles.dart`에 프로젝트를 위한 사전 구성된 기본 스타일이 포함되어 있습니다.

이 스타일은 `light_theme_colors.dart`와 `dart_theme_colors.dart`의 테마 색상에 대한 인터페이스를 제공합니다.

<br>

파일 `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // 일반
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // 앱 바
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // 하단 탭 바
  @override
  Color get bottomTabBarBackground;

  // 하단 탭 바 - 아이콘
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // 하단 탭 바 - 라벨
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // 토스트 알림
  Color get toastNotificationBackground;
}
```

여기에 추가 스타일을 추가한 후 테마에서 색상을 구현할 수 있습니다.

<div id="switching-theme"></div>

## 테마 전환

{{ config('app.name') }}는 즉시 테마를 전환하는 기능을 지원합니다.

예를 들어, 사용자가 "다크 테마"를 활성화하기 위해 버튼을 탭할 때 테마를 전환해야 하는 경우입니다.

아래와 같이 할 수 있습니다:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
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

{{ config('app.name') }}에서 앱 전체의 기본 폰트를 업데이트하는 것은 쉽습니다. `lib/config/design.dart` 파일을 열고 아래를 업데이트합니다.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

저장소에 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> 라이브러리가 포함되어 있으므로 적은 노력으로 모든 폰트를 사용할 수 있습니다.
폰트를 다른 것으로 업데이트하려면 다음과 같이 할 수 있습니다:
``` dart
// 이전
// final TextStyle appThemeFont = GoogleFonts.lato();

// 새로운
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

공식 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> 라이브러리에서 폰트를 확인하여 더 자세히 알아보세요

커스텀 폰트를 사용해야 하나요? 이 가이드를 확인하세요 - https://flutter.dev/docs/cookbook/design/fonts

폰트를 추가한 후 아래 예시처럼 변수를 변경합니다.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo는 커스텀 폰트의 예시로 사용됨
```

<div id="design"></div>

## 디자인

**config/design.dart** 파일은 앱의 디자인 요소를 관리하는 데 사용됩니다.

`appFont` 변수에는 앱의 폰트가 포함됩니다.

`logo` 변수는 앱의 로고를 표시하는 데 사용됩니다.

**resources/widgets/logo_widget.dart**를 수정하여 로고 표시 방법을 커스터마이즈할 수 있습니다.

`loader` 변수는 로더를 표시하는 데 사용됩니다. {{ config('app.name') }}는 일부 헬퍼 메서드에서 이 변수를 기본 로더 Widget으로 사용합니다.

**resources/widgets/loader_widget.dart**를 수정하여 로더 표시 방법을 커스터마이즈할 수 있습니다.

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
