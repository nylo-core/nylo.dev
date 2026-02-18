# 설정

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 환경
  - [.env 파일](#env-file ".env 파일")
  - [환경 설정 생성](#generating-env "환경 설정 생성")
  - [값 가져오기](#retrieving-values "값 가져오기")
  - [Config 클래스 생성](#creating-config-classes "Config 클래스 생성")
  - [변수 타입](#variable-types "변수 타입")
- [환경 플레이버](#environment-flavours "환경 플레이버")
- [빌드 타임 주입](#build-time-injection "빌드 타임 주입")


<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 안전한 환경 설정 시스템을 사용합니다. 환경 변수는 `.env` 파일에 저장된 후, 앱에서 사용할 수 있도록 생성된 Dart 파일(`env.g.dart`)로 암호화됩니다.

이 접근 방식은 다음을 제공합니다:
- **보안**: 환경 값이 컴파일된 앱에서 XOR 암호화됩니다
- **타입 안전성**: 값이 자동으로 적절한 타입으로 파싱됩니다
- **빌드 타임 유연성**: 개발, 스테이징, 프로덕션에 대한 다른 설정

<div id="env-file"></div>

## .env 파일

프로젝트 루트의 `.env` 파일에 설정 변수가 포함됩니다:

``` bash
# 환경 설정
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### 사용 가능한 변수

| 변수 | 설명 |
|----------|-------------|
| `APP_KEY` | **필수**. 암호화를 위한 32자 비밀 키 |
| `APP_NAME` | 애플리케이션 이름 |
| `APP_ENV` | 환경: `developing` 또는 `production` |
| `APP_DEBUG` | 디버그 모드 활성화 (`true`/`false`) |
| `APP_URL` | 앱의 URL |
| `API_BASE_URL` | API 요청의 기본 URL |
| `ASSET_PATH` | 에셋 폴더 경로 |
| `DEFAULT_LOCALE` | 기본 언어 코드 |

<div id="generating-env"></div>

## 환경 설정 생성

{{ config('app.name') }} v7에서는 앱이 환경 값에 접근하기 전에 암호화된 환경 파일을 생성해야 합니다.

### 1단계: APP_KEY 생성

먼저 안전한 APP_KEY를 생성합니다:

``` bash
metro make:key
```

`.env` 파일에 32자의 `APP_KEY`가 추가됩니다.

### 2단계: env.g.dart 생성

암호화된 환경 파일을 생성합니다:

``` bash
metro make:env
```

암호화된 환경 변수가 포함된 `lib/bootstrap/env.g.dart`가 생성됩니다.

앱이 시작될 때 환경이 자동으로 등록됩니다 — `main.dart`의 `Nylo.init(env: Env.get, ...)`이 이를 처리합니다. 추가 설정은 필요하지 않습니다.

### 변경 후 재생성

`.env` 파일을 수정한 후 설정을 재생성합니다:

``` bash
metro make:env --force
```

`--force` 플래그는 기존 `env.g.dart`를 덮어씁니다.

<div id="retrieving-values"></div>

## 값 가져오기

환경 값에 접근하는 권장 방법은 **Config 클래스**를 통하는 것입니다. `lib/config/app.dart` 파일은 `getEnv()`를 사용하여 환경 값을 타입이 지정된 정적 필드로 노출합니다:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

앱 코드에서 Config 클래스를 통해 값에 접근합니다:

``` dart
// 앱 어디서나
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

이 패턴은 환경 접근을 Config 클래스에 집중시킵니다. `getEnv()` 헬퍼는 앱 코드에서 직접 사용하기보다 Config 클래스 내에서 사용해야 합니다.

<div id="creating-config-classes"></div>

## Config 클래스 생성

Metro를 사용하여 서드파티 서비스나 기능별 설정을 위한 커스텀 Config 클래스를 만들 수 있습니다:

``` bash
metro make:config RevenueCat
```

`lib/config/revenue_cat_config.dart`에 새 설정 파일이 생성됩니다:

``` dart
final class RevenueCatConfig {
  // 설정 값을 여기에 추가하세요
}
```

### 예시: RevenueCat 설정

**1단계:** `.env` 파일에 환경 변수를 추가합니다:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**2단계:** Config 클래스를 업데이트하여 이 값을 참조합니다:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**3단계:** 환경 설정을 재생성합니다:

``` bash
metro make:env --force
```

**4단계:** 앱에서 Config 클래스를 사용합니다:

``` dart
import '/config/revenue_cat_config.dart';

// RevenueCat 초기화
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// 구독 확인
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // 프리미엄 접근 권한 부여
}
```

이 접근 방식은 API 키와 설정 값을 안전하고 중앙 집중적으로 관리하여, 환경별로 다른 값을 쉽게 관리할 수 있게 합니다.

<div id="variable-types"></div>

## 변수 타입

`.env` 파일의 값은 자동으로 파싱됩니다:

| .env 값 | Dart 타입 | 예시 |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (빈 문자열) |


<div id="environment-flavours"></div>

## 환경 플레이버

개발, 스테이징, 프로덕션을 위한 다른 설정을 만듭니다.

### 1단계: 환경 파일 생성

별도의 `.env` 파일을 생성합니다:

``` bash
.env                  # 개발 (기본값)
.env.staging          # 스테이징
.env.production       # 프로덕션
```

`.env.production` 예시:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### 2단계: 환경 설정 생성

특정 환경 파일에서 생성합니다:

``` bash
# 프로덕션용
metro make:env --file=".env.production" --force

# 스테이징용
metro make:env --file=".env.staging" --force
```

### 3단계: 앱 빌드

적절한 설정으로 빌드합니다:

``` bash
# 개발
flutter run

# 프로덕션 빌드
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## 빌드 타임 주입

보안 강화를 위해, 소스 코드에 포함하는 대신 빌드 시점에 APP_KEY를 주입할 수 있습니다.

### --dart-define 모드로 생성

``` bash
metro make:env --dart-define
```

APP_KEY를 포함하지 않은 `env.g.dart`가 생성됩니다.

### APP_KEY 주입으로 빌드

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# 실행
flutter run --dart-define=APP_KEY=your-secret-key
```

이 접근 방식은 APP_KEY를 소스 코드에서 제외하며, 다음과 같은 경우에 유용합니다:
- 시크릿이 주입되는 CI/CD 파이프라인
- 오픈소스 프로젝트
- 강화된 보안 요구 사항

### 모범 사례

1. **`.env`를 버전 관리에 커밋하지 마세요** - `.gitignore`에 추가하세요
2. **`.env-example`을 사용하세요** - 민감한 값 없이 템플릿을 커밋하세요
3. **변경 후 재생성하세요** - `.env` 수정 후 항상 `metro make:env --force`를 실행하세요
4. **환경별로 다른 키 사용** - 개발, 스테이징, 프로덕션에 고유한 APP_KEY를 사용하세요
