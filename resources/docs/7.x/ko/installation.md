# 설치

---

<a name="section-1"></a>
- [설치하기](#install "설치하기")
- [프로젝트 실행](#running-the-project "프로젝트 실행")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## 설치하기

### 1. nylo_installer를 전역으로 설치

``` bash
dart pub global activate nylo_installer
```

{{ config('app.name') }} CLI 도구를 시스템에 전역으로 설치합니다.

### 2. 새 프로젝트 생성

``` bash
nylo new my_app
```

이 명령어는 {{ config('app.name') }} 템플릿을 클론하고, 앱 이름으로 프로젝트를 구성하며, 모든 의존성을 자동으로 설치합니다.

### 3. Metro CLI 별칭 설정

``` bash
cd my_app
nylo init
```

프로젝트에 `metro` 명령어를 구성하여, 전체 `dart run` 구문 없이 Metro CLI 명령어를 사용할 수 있습니다.

설치 후, 다음이 포함된 완전한 Flutter 프로젝트 구조를 갖게 됩니다:
- 사전 구성된 라우팅 및 네비게이션
- API 서비스 보일러플레이트
- 테마 및 다국어 설정
- 코드 생성을 위한 Metro CLI


<div id="running-the-project"></div>

## 프로젝트 실행

{{ config('app.name') }} 프로젝트는 표준 Flutter 앱처럼 실행됩니다.

### 터미널 사용

``` bash
flutter run
```

### IDE 사용

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">실행 및 디버깅</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">브레이크포인트 없이 앱 실행</a>

빌드가 성공하면 {{ config('app.name') }}의 기본 랜딩 화면이 표시됩니다.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }}에는 프로젝트 파일을 생성하기 위한 **Metro**라는 CLI 도구가 포함되어 있습니다.

### Metro 실행

``` bash
metro
```

Metro 메뉴가 표시됩니다:

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Metro 명령어 참조

| 명령어 | 설명 |
|---------|-------------|
| `make:page` | 새 페이지 생성 |
| `make:stateful_widget` | Stateful Widget 생성 |
| `make:stateless_widget` | Stateless Widget 생성 |
| `make:state_managed_widget` | 상태 관리 Widget 생성 |
| `make:navigation_hub` | Navigation Hub (하단 네비게이션) 생성 |
| `make:journey_widget` | Navigation Hub용 Journey Widget 생성 |
| `make:bottom_sheet_modal` | Bottom Sheet Modal 생성 |
| `make:button` | 커스텀 버튼 Widget 생성 |
| `make:form` | 유효성 검사가 포함된 폼 생성 |
| `make:model` | Model 클래스 생성 |
| `make:provider` | Provider 생성 |
| `make:api_service` | API 서비스 생성 |
| `make:controller` | Controller 생성 |
| `make:event` | Event 생성 |
| `make:theme` | 테마 생성 |
| `make:route_guard` | Route Guard 생성 |
| `make:config` | 설정 파일 생성 |
| `make:interceptor` | 네트워크 Interceptor 생성 |
| `make:command` | 커스텀 Metro 명령어 생성 |
| `make:env` | .env에서 환경 설정 생성 |

### 사용 예시

``` bash
# 새 페이지 생성
metro make:page settings_page

# 모델 생성
metro make:model User

# API 서비스 생성
metro make:api_service user_api_service
```
