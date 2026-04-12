# LanguageSwitcher

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 사용법
    - [드롭다운 위젯](#usage-dropdown "드롭다운 위젯")
    - [하단 시트 모달](#usage-bottom-modal "하단 시트 모달")
- [애니메이션 스타일](#animation-style "애니메이션 스타일")
- [커스텀 드롭다운 빌더](#custom-builder "커스텀 드롭다운 빌더")
- [상태 액션](#state-actions "상태 액션")
- [매개변수](#parameters "매개변수")
- [정적 메서드](#methods "정적 메서드")


<div id="introduction"></div>

## 소개

**LanguageSwitcher** 위젯은 {{ config('app.name') }} 프로젝트에서 언어 전환을 쉽게 처리할 수 있는 방법을 제공합니다. `/lang` 디렉토리에서 사용 가능한 언어를 자동으로 감지하여 사용자에게 표시합니다.

**LanguageSwitcher는 무엇을 하나요?**

- `/lang` 디렉토리에서 사용 가능한 언어를 표시합니다
- 사용자가 언어를 선택하면 앱 언어를 전환합니다
- 앱 재시작 시에도 선택한 언어를 유지합니다
- 언어가 변경되면 자동으로 UI를 업데이트합니다

> **참고**: 앱이 아직 현지화되지 않은 경우, 이 위젯을 사용하기 전에 [현지화](/docs/7.x/localization) 문서에서 방법을 알아보세요.

<div id="usage-dropdown"></div>

## 드롭다운 위젯

`LanguageSwitcher`를 사용하는 가장 간단한 방법은 앱 바에 드롭다운으로 추가하는 것입니다:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // 앱 바에 추가
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

사용자가 드롭다운을 탭하면 사용 가능한 언어 목록이 표시됩니다. 언어를 선택하면 앱이 자동으로 전환되고 UI가 업데이트됩니다.

<div id="usage-bottom-modal"></div>

## 하단 시트 모달

하단 시트 모달에 언어를 표시할 수도 있습니다:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

하단 모달은 현재 선택된 언어 옆에 체크 표시와 함께 언어 목록을 표시합니다.

### 모달 커스터마이징

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300,
  useRootNavigator: true, // 탭 바를 포함한 모든 라우트 위에 모달을 표시
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
);
```

<div id="animation-style"></div>

## 애니메이션 스타일

`animationStyle` 매개변수는 드롭다운 트리거와 하단 모달 목록 아이템 모두의 전환 애니메이션을 제어합니다. 네 가지 프리셋을 사용할 수 있습니다:

``` dart
// 애니메이션 없음
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.none(),
)

// 섬세하고 정제된 애니메이션 (대부분의 앱에 권장)
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
)

// 유쾌하고 탄력 있는 애니메이션
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.bouncy(),
)

// 부드러운 스케일과 함께하는 페이드인
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.fadeIn(),
)
```

개별 매개변수와 함께 커스텀 `LanguageSwitcherAnimationStyle()`을 전달하거나, `copyWith`를 사용하여 프리셋을 조정할 수도 있습니다.

동일한 `animationStyle` 매개변수가 `LanguageSwitcher.showBottomModal`에서도 지원됩니다.

<div id="custom-builder"></div>

## 커스텀 드롭다운 빌더

드롭다운에서 각 언어 옵션이 표시되는 방식을 커스터마이징합니다:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // 예: "English"
        // language['locale']에는 로케일 코드가 포함됩니다 (예: "en")
      ],
    );
  },
)
```

### 언어 변경 처리

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // 언어 변경 시 추가 작업 수행
  },
)
```

<div id="state-actions"></div>

## 상태 액션

`stateActions()`를 사용하여 `LanguageSwitcher`를 프로그래밍 방식으로 제어합니다:

``` dart
// 언어 목록 새로고침 (사용 가능한 언어 재읽기)
LanguageSwitcher.stateActions().refresh();

// 로케일 코드로 언어 전환
LanguageSwitcher.stateActions().setLanguage("es");
LanguageSwitcher.stateActions().setLanguage("fr");
```

이는 사용자 상호작용 없이 앱 언어를 변경하고 싶을 때, 예를 들어 사용자 기본 설정으로 로그인 후에 유용합니다.

<div id="parameters"></div>

## 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | 드롭다운 버튼의 커스텀 아이콘 |
| `iconEnabledColor` | `Color?` | - | 드롭다운 아이콘 색상 |
| `iconSize` | `double` | `24` | 드롭다운 아이콘 크기 |
| `dropdownBgColor` | `Color?` | - | 드롭다운 메뉴 배경 색상 |
| `hint` | `Widget?` | - | 언어가 선택되지 않았을 때의 힌트 위젯 |
| `itemHeight` | `double` | `kMinInteractiveDimension` | 각 드롭다운 항목의 높이 |
| `elevation` | `int` | `8` | 드롭다운 메뉴의 엘리베이션 |
| `padding` | `EdgeInsetsGeometry?` | - | 드롭다운 주변의 패딩 |
| `borderRadius` | `BorderRadius?` | - | 드롭다운 메뉴의 테두리 반경 |
| `textStyle` | `TextStyle?` | - | 드롭다운 항목의 텍스트 스타일 |
| `langPath` | `String` | `'lang'` | assets 내 언어 파일 경로 |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | 드롭다운 항목의 커스텀 빌더 |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | 드롭다운 항목 정렬 |
| `dropdownOnTap` | `Function()?` | - | 드롭다운 항목 탭 시 콜백 |
| `onTap` | `Function()?` | - | 드롭다운 버튼 탭 시 콜백 |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | 언어 변경 시 콜백 |

<div id="methods"></div>

## 정적 메서드

### 현재 언어 가져오기

현재 선택된 언어를 가져옵니다:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// 반환값: {"en": "English"} 또는 설정되지 않은 경우 null
```

### 언어 저장

수동으로 언어 기본 설정을 저장합니다:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### 언어 삭제

저장된 언어 기본 설정을 제거합니다:

``` dart
await LanguageSwitcher.clearLanguage();
```

### 언어 데이터 가져오기

로케일 코드에서 언어 정보를 가져옵니다:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// 반환값: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// 반환값: {"fr_CA": "French (Canada)"}
```

### 언어 목록 가져오기

`/lang` 디렉토리에서 사용 가능한 모든 언어를 가져옵니다:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// 반환값: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### 하단 모달 표시

언어 선택 모달을 표시합니다:

``` dart
await LanguageSwitcher.showBottomModal(context);

// 옵션과 함께
await LanguageSwitcher.showBottomModal(
  context,
  height: 400,
  useRootNavigator: true,
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
);
```

## 지원되는 로케일

`LanguageSwitcher` 위젯은 사람이 읽을 수 있는 이름과 함께 수백 개의 로케일 코드를 지원합니다. 예시:

| 로케일 코드 | 언어 이름 |
|-------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

전체 목록에는 대부분의 언어에 대한 지역 변형이 포함되어 있습니다.
