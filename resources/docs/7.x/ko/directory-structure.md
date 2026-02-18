# 디렉토리 구조

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [루트 디렉토리](#root-directory "루트 디렉토리")
- [lib 디렉토리](#lib-directory "lib 디렉토리")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [Assets 디렉토리](#assets-directory "Assets 디렉토리")
- [Asset 헬퍼](#asset-helpers "Asset 헬퍼")


<div id="introduction"></div>

## 소개

{{ config('app.name') }}는 <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>에서 영감을 받은 깔끔하고 체계적인 디렉토리 구조를 사용합니다. 이 구조는 프로젝트 간 일관성을 유지하고 파일을 쉽게 찾을 수 있도록 도와줍니다.

<div id="root-directory"></div>

## 루트 디렉토리

```
nylo_app/
├── android/          # Android 플랫폼 파일
├── assets/           # 이미지, 폰트 및 기타 에셋
├── ios/              # iOS 플랫폼 파일
├── lang/             # 언어/번역 JSON 파일
├── lib/              # Dart 애플리케이션 코드
├── test/             # 테스트 파일
├── .env              # 환경 변수
├── pubspec.yaml      # 의존성 및 프로젝트 설정
└── ...
```

<div id="lib-directory"></div>

## lib 디렉토리

`lib/` 폴더에는 모든 Dart 애플리케이션 코드가 포함됩니다:

```
lib/
├── app/              # 애플리케이션 로직
├── bootstrap/        # 부트 설정
├── config/           # 설정 파일
├── resources/        # UI 컴포넌트
├── routes/           # 라우트 정의
└── main.dart         # 애플리케이션 진입점
```

<div id="app-directory"></div>

### app/

`app/` 디렉토리에는 애플리케이션의 핵심 로직이 포함됩니다:

| 디렉토리 | 용도 |
|-----------|---------|
| `commands/` | 커스텀 Metro CLI 명령어 |
| `controllers/` | 비즈니스 로직을 위한 페이지 Controller |
| `events/` | 이벤트 시스템을 위한 Event 클래스 |
| `forms/` | 유효성 검사가 포함된 Form 클래스 |
| `models/` | 데이터 Model 클래스 |
| `networking/` | API 서비스 및 네트워크 설정 |
| `networking/dio/interceptors/` | Dio HTTP Interceptor |
| `providers/` | 앱 시작 시 부팅되는 서비스 Provider |
| `services/` | 일반 서비스 클래스 |

<div id="bootstrap-directory"></div>

### bootstrap/

`bootstrap/` 디렉토리에는 앱 부팅 방법을 설정하는 파일이 포함됩니다:

| 파일 | 용도 |
|------|---------|
| `boot.dart` | 메인 부트 시퀀스 설정 |
| `decoders.dart` | Model 및 API Decoder 등록 |
| `env.g.dart` | 생성된 암호화 환경 설정 |
| `events.dart` | Event 등록 |
| `extensions.dart` | 커스텀 확장 |
| `helpers.dart` | 커스텀 헬퍼 함수 |
| `providers.dart` | Provider 등록 |
| `theme.dart` | 테마 설정 |

<div id="config-directory"></div>

### config/

`config/` 디렉토리에는 애플리케이션 설정이 포함됩니다:

| 파일 | 용도 |
|------|---------|
| `app.dart` | 핵심 앱 설정 |
| `design.dart` | 앱 디자인 (폰트, 로고, 로더) |
| `localization.dart` | 언어 및 로케일 설정 |
| `storage_keys.dart` | 로컬 스토리지 키 정의 |
| `toast_notification.dart` | 토스트 알림 스타일 |

<div id="resources-directory"></div>

### resources/

`resources/` 디렉토리에는 UI 컴포넌트가 포함됩니다:

| 디렉토리 | 용도 |
|-----------|---------|
| `pages/` | 페이지 Widget (화면) |
| `themes/` | 테마 정의 |
| `themes/light/` | 라이트 테마 색상 |
| `themes/dark/` | 다크 테마 색상 |
| `widgets/` | 재사용 가능한 Widget 컴포넌트 |
| `widgets/buttons/` | 커스텀 버튼 Widget |
| `widgets/bottom_sheet_modals/` | Bottom Sheet Modal Widget |

<div id="routes-directory"></div>

### routes/

`routes/` 디렉토리에는 라우팅 설정이 포함됩니다:

| 파일/디렉토리 | 용도 |
|----------------|---------|
| `router.dart` | 라우트 정의 |
| `guards/` | Route Guard 클래스 |

<div id="assets-directory"></div>

## Assets 디렉토리

`assets/` 디렉토리에는 정적 파일이 저장됩니다:

```
assets/
├── app_icon/         # 앱 아이콘 소스
├── fonts/            # 커스텀 폰트
└── images/           # 이미지 에셋
```

### 에셋 등록

에셋은 `pubspec.yaml`에 등록됩니다:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Asset 헬퍼

{{ config('app.name') }}는 에셋 작업을 위한 헬퍼를 제공합니다.

### 이미지 에셋

``` dart
// 표준 Flutter 방식
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// LocalAsset Widget 사용
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### 일반 에셋

``` dart
// 에셋 경로 가져오기
String fontPath = getAsset('fonts/custom.ttf');

// 비디오 예시
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### 언어 파일

언어 파일은 프로젝트 루트의 `lang/`에 저장됩니다:

```
lang/
├── en.json           # 영어
├── es.json           # 스페인어
├── fr.json           # 프랑스어
└── ...
```

자세한 내용은 [다국어 지원](/docs/7.x/localization)을 참조하세요.
