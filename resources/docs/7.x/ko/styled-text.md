# Styled Text

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [Children 모드](#children-mode "Children 모드")
- [Template 모드](#template-mode "Template 모드")
  - [플레이스홀더 스타일링](#styling-placeholders "플레이스홀더 스타일링")
  - [탭 콜백](#tap-callbacks "탭 콜백")
  - [파이프 구분 키](#pipe-keys "파이프 구분 키")
  - [현지화 키](#localization-keys "현지화 키")
- [매개변수](#parameters "매개변수")
- [Text 확장](#text-extensions "Text 확장")
  - [타이포그래피 스타일](#typography-styles "타이포그래피 스타일")
  - [유틸리티 메서드](#utility-methods "유틸리티 메서드")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

**StyledText**는 혼합 스타일, 탭 콜백, 포인터 이벤트가 포함된 리치 텍스트를 표시하기 위한 위젯입니다. 여러 `TextSpan` 자식이 있는 `RichText` 위젯으로 렌더링되어 각 텍스트 세그먼트를 세밀하게 제어할 수 있습니다.

StyledText는 두 가지 모드를 지원합니다:

1. **Children 모드** -- 각각 고유한 스타일을 가진 `Text` 위젯 목록을 전달
2. **Template 모드** -- 문자열에 `@{{placeholder}}` 구문을 사용하고 플레이스홀더를 스타일과 액션에 매핑

<div id="basic-usage"></div>

## 기본 사용법

``` dart
// Children 모드 - Text 위젯 목록
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template 모드 - 플레이스홀더 구문
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Children 모드

`Text` 위젯 목록을 전달하여 스타일이 적용된 텍스트를 구성합니다:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

기본 `style`은 자체 스타일이 없는 모든 자식에 적용됩니다.

### 포인터 이벤트

포인터가 텍스트 세그먼트에 들어오거나 나갈 때 감지합니다:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Template 모드

`@{{placeholder}}` 구문과 함께 `StyledText.template()`을 사용합니다:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

`@{{ }}` 사이의 텍스트는 **표시 텍스트**이자 스타일과 탭 콜백을 찾는 데 사용되는 **키**입니다.

<div id="styling-placeholders"></div>

### 플레이스홀더 스타일링

플레이스홀더 이름을 `TextStyle` 객체에 매핑합니다:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### 탭 콜백

플레이스홀더 이름을 탭 핸들러에 매핑합니다:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### 파이프 구분 키

파이프 `|`로 구분된 키를 사용하여 여러 플레이스홀더에 동일한 스타일이나 콜백을 적용합니다:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

이렇게 하면 세 플레이스홀더 모두에 동일한 스타일과 콜백이 매핑됩니다.

<div id="localization-keys"></div>

### 현지화 키

`@{{key:text}}` 구문을 사용하여 **조회 키**와 **표시 텍스트**를 분리합니다. 이는 현지화에 유용합니다 -- 키는 모든 로케일에서 동일하게 유지되고 표시 텍스트는 언어별로 변경됩니다.

``` dart
// 로케일 파일에서:
// en.json -> "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} in @{{app:AppName}}"
// es.json -> "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}} en @{{app:AppName}}"

StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(
      color: Colors.blue,
      fontWeight: FontWeight.bold,
    ),
    "app": TextStyle(color: Colors.green),
  },
  onTap: {
    "app": () => routeTo("/about"),
  },
)
// EN 렌더링: "Learn Languages, Reading and Speaking in AppName"
// ES 렌더링: "Aprende Idiomas, Lectura y Habla en AppName"
```

`:` 앞부분은 스타일과 탭 콜백을 조회하는 데 사용되는 **키**입니다. `:` 뒷부분은 화면에 렌더링되는 **표시 텍스트**입니다. `:` 없이 사용하면 플레이스홀더는 이전과 완전히 동일하게 동작합니다 -- 완벽한 하위 호환성.

이것은 [파이프 구분 키](#pipe-keys)와 [탭 콜백](#tap-callbacks)을 포함한 모든 기존 기능과 함께 작동합니다.

<div id="parameters"></div>

## 매개변수

### StyledText (Children 모드)

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | 필수 | Text 위젯 목록 |
| `style` | `TextStyle?` | null | 모든 자식에 대한 기본 스타일 |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | 포인터 진입 콜백 |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | 포인터 이탈 콜백 |
| `spellOut` | `bool?` | null | 텍스트를 문자별로 철자하기 |
| `softWrap` | `bool` | `true` | 소프트 줄바꿈 활성화 |
| `textAlign` | `TextAlign` | `TextAlign.start` | 텍스트 정렬 |
| `textDirection` | `TextDirection?` | null | 텍스트 방향 |
| `maxLines` | `int?` | null | 최대 줄 수 |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | 오버플로 동작 |
| `locale` | `Locale?` | null | 텍스트 로케일 |
| `strutStyle` | `StrutStyle?` | null | Strut 스타일 |
| `textScaler` | `TextScaler?` | null | 텍스트 스케일러 |
| `selectionColor` | `Color?` | null | 선택 강조 색상 |

### StyledText.template (Template 모드)

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `text` | `String` | 필수 | `@{{placeholder}}` 구문이 포함된 템플릿 텍스트 |
| `styles` | `Map<String, TextStyle>?` | null | 플레이스홀더 이름에서 스타일로의 매핑 |
| `onTap` | `Map<String, VoidCallback>?` | null | 플레이스홀더 이름에서 탭 콜백으로의 매핑 |
| `style` | `TextStyle?` | null | 플레이스홀더가 아닌 텍스트의 기본 스타일 |

다른 모든 매개변수 (`softWrap`, `textAlign`, `maxLines` 등)는 children 생성자와 동일합니다.

<div id="text-extensions"></div>

## Text 확장

{{ config('app.name') }}은 Flutter의 `Text` 위젯을 타이포그래피와 유틸리티 메서드로 확장합니다.

<div id="typography-styles"></div>

### 타이포그래피 스타일

모든 `Text` 위젯에 Material Design 타이포그래피 스타일을 적용합니다:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

각각 선택적 오버라이드를 허용합니다:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**사용 가능한 오버라이드** (모든 타이포그래피 메서드에서 동일):

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `color` | `Color?` | 텍스트 색상 |
| `fontSize` | `double?` | 글꼴 크기 |
| `fontWeight` | `FontWeight?` | 글꼴 두께 |
| `fontStyle` | `FontStyle?` | 이탤릭/일반 |
| `letterSpacing` | `double?` | 자간 |
| `wordSpacing` | `double?` | 단어 간격 |
| `height` | `double?` | 줄 높이 |
| `decoration` | `TextDecoration?` | 텍스트 장식 |
| `decorationColor` | `Color?` | 장식 색상 |
| `decorationStyle` | `TextDecorationStyle?` | 장식 스타일 |
| `decorationThickness` | `double?` | 장식 두께 |
| `fontFamily` | `String?` | 글꼴 패밀리 |
| `shadows` | `List<Shadow>?` | 텍스트 그림자 |
| `overflow` | `TextOverflow?` | 오버플로 동작 |

<div id="utility-methods"></div>

### 유틸리티 메서드

``` dart
// 글꼴 두께
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// 정렬
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// 최대 줄 수
Text("Long text...").setMaxLines(2)

// 글꼴 패밀리
Text("Custom font").setFontFamily("Roboto")

// 글꼴 크기
Text("Big text").setFontSize(24)

// 커스텀 스타일
Text("Styled").setStyle(TextStyle(color: Colors.red))

// 패딩
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// 수정된 복사본
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## 예제

### 이용약관 링크

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### 버전 표시

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### 혼합 스타일 문단

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### 타이포그래피 체인

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
