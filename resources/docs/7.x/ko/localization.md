# 다국어 지원

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [설정](#configuration "설정")
- [다국어 파일 추가](#adding-localized-files "다국어 파일 추가")
- 기본 사항
  - [텍스트 다국어화](#localizing-text "텍스트 다국어화")
    - [인수](#arguments "인수")
    - [StyledText Placeholder](#styled-text-placeholders "StyledText Placeholder")
  - [로케일 업데이트](#updating-the-locale "로케일 업데이트")
  - [기본 로케일 설정](#setting-a-default-locale "기본 로케일 설정")
- 고급
  - [지원되는 로케일](#supported-locales "지원되는 로케일")
  - [폴백 언어](#fallback-language "폴백 언어")
  - [RTL 지원](#rtl-support "RTL 지원")
  - [누락된 키 디버그](#debug-missing-keys "누락된 키 디버그")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper")
  - [Controller에서 언어 변경](#changing-language-from-controller "Controller에서 언어 변경")


<div id="introduction"></div>

## 소개

다국어 지원을 사용하면 앱을 여러 언어로 제공할 수 있습니다. {{ config('app.name') }} v7은 JSON 언어 파일을 사용하여 텍스트를 쉽게 다국어화할 수 있습니다.

간단한 예시입니다:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**Widget에서:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## 설정

다국어 지원은 `lib/config/localization.dart`에서 설정합니다:

``` dart
final class LocalizationConfig {
  // 기본 언어 코드 (JSON 파일과 일치, 예: 'en'은 lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - 기기의 언어 설정 사용
  // LocaleType.asDefined - 위의 languageCode 사용
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // 언어 JSON 파일이 있는 디렉토리
  static const String assetsDirectory = 'lang/';

  // 지원되는 로케일 목록
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // 필요에 따라 더 많은 로케일 추가
  ];

  // 활성 로케일에서 키를 찾지 못할 때의 폴백
  static const String fallbackLanguageCode = 'en';

  // RTL 언어 코드
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // 누락된 번역 키에 대한 경고 로그
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## 다국어 파일 추가

`lang/` 디렉토리에 언어 JSON 파일을 추가합니다:

```
lang/
├── en.json   # 영어
├── es.json   # 스페인어
├── fr.json   # 프랑스어
└── ...
```

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "settings": "Settings",
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

**lang/es.json**
``` json
{
  "welcome": "Bienvenido",
  "settings": "Configuración",
  "navigation": {
    "home": "Inicio",
    "profile": "Perfil"
  }
}
```

### pubspec.yaml에 등록

언어 파일이 `pubspec.yaml`에 포함되어 있는지 확인합니다:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## 텍스트 다국어화

`.tr()` Extension 또는 `trans()` 헬퍼를 사용하여 문자열을 번역합니다:

``` dart
// .tr() Extension 사용
"welcome".tr()

// trans() 헬퍼 사용
trans("welcome")
```

### 중첩 키

점 표기법을 사용하여 중첩된 JSON 키에 접근합니다:

**lang/en.json**
``` json
{
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

``` dart
"navigation.home".tr()       // "Home"
trans("navigation.profile")  // "Profile"
```

<div id="arguments"></div>

### 인수

`@{{key}}` 구문을 사용하여 번역에 동적 값을 전달합니다:

**lang/en.json**
``` json
{
  "greeting": "Hello @{{name}}",
  "items_count": "You have @{{count}} items"
}
```

``` dart
"greeting".tr(arguments: {"name": "Anthony"})
// "Hello Anthony"

trans("items_count", arguments: {"count": "5"})
// "You have 5 items"
```

<div id="styled-text-placeholders"></div>

### StyledText Placeholder

`StyledText.template`과 다국어 문자열을 함께 사용할 때 `@{{key:text}}` 구문을 사용할 수 있습니다. 이렇게 하면 **key**가 모든 로케일에서 안정적으로 유지되므로 (스타일과 탭 핸들러가 항상 일치함) **text**는 로케일별로 번역됩니다.

**lang/en.json**
``` json
{
  "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} skills",
  "already_have_account": "Already have an account? @{{login:Login}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? @{{login:Iniciar sesión}}"
}
```

**Widget에서:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

`lang`, `read`, `speak` 키는 모든 로케일 파일에서 동일하므로 스타일 맵이 모든 언어에서 작동합니다. `:` 뒤의 표시 텍스트는 사용자가 보는 내용입니다 - 영어에서는 "Languages", 스페인어에서는 "Idiomas" 등.

`onTap`과 함께 사용할 수도 있습니다:

``` dart
StyledText.template(
  "already_have_account".tr(),
  styles: {
    "login": TextStyle(fontWeight: FontWeight.bold),
  },
  onTap: {
    "login": () => routeTo(LoginPage.path),
  },
)
```

> **참고:** `@{{key}}` 구문 (`@` 접두사 포함)은 번역 시 `.tr(arguments:)`로 대체되는 인수용입니다. `@{{key:text}}` 구문 (`@` 없이)은 렌더링 시 파싱되는 `StyledText` Placeholder용입니다. 혼동하지 마세요 - 동적 값에는 `@{{}}`를, 스타일이 적용된 범위에는 `@{{}}`를 사용하세요.

<div id="updating-the-locale"></div>

## 로케일 업데이트

런타임에 앱의 언어를 변경합니다:

``` dart
// NyLocalization을 직접 사용
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // JSON 파일 이름과 일치해야 함 (es.json)
);
```

Widget이 `NyPage`를 확장하는 경우 `changeLanguage` 헬퍼를 사용합니다:

``` dart
class _SettingsPageState extends NyPage<SettingsPage> {

  @override
  Widget view(BuildContext context) {
    return ListView(
      children: [
        ListTile(
          title: Text("English"),
          onTap: () => changeLanguage('en'),
        ),
        ListTile(
          title: Text("Español"),
          onTap: () => changeLanguage('es'),
        ),
      ],
    );
  }
}
```

<div id="setting-a-default-locale"></div>

## 기본 로케일 설정

`.env` 파일에서 기본 언어를 설정합니다:

``` bash
DEFAULT_LOCALE="en"
```

또는 기기의 로케일을 사용하려면 다음과 같이 설정합니다:

``` bash
LOCALE_TYPE="device"
```

`.env`를 변경한 후 환경 설정을 재생성합니다:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## 지원되는 로케일

`LocalizationConfig`에서 앱이 지원하는 로케일을 정의합니다:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

이 목록은 Flutter의 `MaterialApp.supportedLocales`에서 사용됩니다.

<div id="fallback-language"></div>

## 폴백 언어

활성 로케일에서 번역 키를 찾지 못하면 {{ config('app.name') }}는 지정된 언어로 폴백합니다:

``` dart
static const String fallbackLanguageCode = 'en';
```

이렇게 하면 번역이 누락된 경우에도 앱에 원시 키가 표시되지 않습니다.

<div id="rtl-support"></div>

## RTL 지원

{{ config('app.name') }} v7은 오른쪽에서 왼쪽(RTL) 언어에 대한 내장 지원을 포함합니다:

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// 현재 언어가 RTL인지 확인
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // RTL 레이아웃 처리
}
```

<div id="debug-missing-keys"></div>

## 누락된 키 디버그

개발 중에 누락된 번역 키에 대한 경고를 활성화합니다:

`.env` 파일에서:
``` bash
DEBUG_TRANSLATIONS="true"
```

이렇게 하면 `.tr()`이 키를 찾지 못할 때 경고를 기록하여 번역되지 않은 문자열을 찾는 데 도움이 됩니다.

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization`은 모든 다국어 지원을 관리하는 싱글톤입니다. 기본 `translate()` 메서드 외에 여러 추가 메서드를 제공합니다:

### 번역 존재 여부 확인

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// 현재 언어 파일에 키가 존재하면 true

// 중첩 키에서도 작동
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### 모든 번역 키 가져오기

로드된 키를 확인하는 데 유용한 디버깅 도구:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### 재시작 없이 로케일 변경

앱을 재시작하지 않고 로케일을 조용히 변경하려면:

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

새 언어 파일을 로드하지만 앱을 재시작하지 **않습니다**. UI 업데이트를 직접 처리하려는 경우에 유용합니다.

### RTL 방향 확인

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### 현재 로케일 접근

``` dart
// 현재 언어 코드 가져오기
String code = NyLocalization.instance.languageCode;  // 예: 'en'

// 현재 Locale 객체 가져오기
Locale currentLocale = NyLocalization.instance.locale;

// Flutter 다국어 지원 delegate 가져오기 (MaterialApp에서 사용)
var delegates = NyLocalization.instance.delegates;
```

### 전체 API 참조

| 메서드 / 속성 | 반환 타입 | 설명 |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | 싱글톤 인스턴스 |
| `translate(key, [arguments])` | `String` | 선택적 인수와 함께 키 번역 |
| `hasTranslation(key)` | `bool` | 번역 키 존재 여부 확인 |
| `getAllKeys()` | `List<String>` | 로드된 모든 번역 키 가져오기 |
| `setLanguage(context, {language, restart})` | `Future<void>` | 언어 변경, 선택적으로 재시작 |
| `setLocale({locale})` | `Future<void>` | 재시작 없이 로케일 변경 |
| `setDebugMissingKeys(enabled)` | `void` | 누락된 키 로깅 활성화/비활성화 |
| `isDirectionRTL(context)` | `bool` | 현재 방향이 RTL인지 확인 |
| `restart(context)` | `void` | 앱 재시작 |
| `languageCode` | `String` | 현재 언어 코드 |
| `locale` | `Locale` | 현재 Locale 객체 |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter 다국어 지원 delegate |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper`는 로케일 작업을 위한 정적 유틸리티 클래스입니다. 현재 로케일 감지, RTL 지원 확인, Locale 객체 생성을 위한 메서드를 제공합니다.

``` dart
// 현재 시스템 로케일 가져오기
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// 언어 및 국가 코드 가져오기
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' 또는 null

// 현재 로케일이 일치하는지 확인
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// RTL 감지
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// 텍스트 방향 가져오기
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// 문자열에서 Locale 생성
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### 전체 API 참조

| 메서드 | 반환 타입 | 설명 |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | 현재 시스템 로케일 가져오기 |
| `getLanguageCode({context})` | `String` | 현재 언어 코드 가져오기 |
| `getCountryCode({context})` | `String?` | 현재 국가 코드 가져오기 |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | 현재 로케일이 일치하는지 확인 |
| `isRtlLanguage(languageCode)` | `bool` | 언어 코드가 RTL인지 확인 |
| `isCurrentLocaleRtl({context})` | `bool` | 현재 로케일이 RTL인지 확인 |
| `getTextDirection(languageCode)` | `TextDirection` | 언어의 TextDirection 가져오기 |
| `getCurrentTextDirection({context})` | `TextDirection` | 현재 로케일의 TextDirection 가져오기 |
| `toLocale(languageCode, [countryCode])` | `Locale` | 문자열에서 Locale 생성 |

`rtlLanguages` 상수에 포함된 언어: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Controller에서 언어 변경

페이지에 Controller를 사용하는 경우 `NyController`에서 언어를 변경할 수 있습니다:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es');
  }

  void switchToEnglishNoRestart() {
    changeLanguage('en', restartState: false);
  }
}
```

`restartState` 파라미터는 언어 변경 후 앱을 재시작할지 여부를 제어합니다. UI 업데이트를 직접 처리하려면 `false`로 설정하세요.
