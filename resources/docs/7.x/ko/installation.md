# 설치

---

<a name="section-1"></a>
- [설치하기](#install "설치하기")
- [프로젝트 실행](#running-the-project "프로젝트 실행")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

세 개의 명령으로 빈 폴더에서 라우팅, 네트워킹, 테마 및 코드 생성이 미리 구성된 Flutter 앱을 실행할 수 있습니다.

<x-doc-strip label="시작하기 전에" items="Flutter SDK 설치됨, Dart 3" linkText="전체 요구 사항" linkHref="/ko/docs/{{ $version }}/requirements" />

## 설치하기

다음 명령을 순서대로 실행하세요. 각 명령은 안전하게 다시 실행할 수 있습니다.

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI 전역 설치">
{{ config('app.name') }} CLI 도구를 시스템에 전역으로 설치합니다.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="새 프로젝트 생성">
이 명령어는 {{ config('app.name') }} 템플릿을 클론하고, 앱 이름으로 프로젝트를 구성하며, 모든 의존성을 자동으로 설치합니다.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Metro 별칭 설정">
프로젝트에 `metro` 명령어를 구성하여, 전체 `dart run` 구문 없이 Metro CLI 명령어를 사용할 수 있습니다.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="제공되는 항목" items="사전 구성된 라우팅 및 네비게이션
API 서비스 보일러플레이트
테마 및 다국어 설정
코드 생성을 위한 Metro CLI" />


<div id="running-the-project"></div>

## 프로젝트 실행

{{ config('app.name') }} 프로젝트는 표준 Flutter 앱처럼 실행됩니다.

<x-doc-tabs tabs="터미널, Android Studio, VS Code">
<x-doc-tab label="터미널">

``` bash
flutter run
```

빌드가 성공하면 {{ config('app.name') }}의 기본 랜딩 화면이 표시됩니다.
</x-doc-tab>

<x-doc-tab label="Android Studio">
프로젝트 폴더를 열고 대상 선택기에서 기기를 선택한 다음 **Run**을 누르세요.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter 문서: 실행 및 디버깅 ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
프로젝트 폴더를 연 다음 명령 팔레트에서 **Debug: Start Without Debugging**을 실행하세요.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter 문서: 중단점 없이 실행 ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro가 프로젝트 파일을 생성합니다. 인수 없이 실행하여 메뉴를 보거나 명령을 직접 호출하세요.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### 명령어 참조

각 명령에는 이름을 지정합니다. 예: `metro make:page settings_page`

<x-doc-commands title="위젯 명령" rows="
metro make:page | 새 페이지 생성
metro make:stateful_widget | Stateful Widget 생성
metro make:stateless_widget | Stateless Widget 생성
metro make:state_managed_widget | 상태 관리 Widget 생성
metro make:navigation_hub | Navigation Hub (하단 네비게이션) 생성
metro make:journey_widget | Navigation Hub용 Journey Widget 생성
metro make:bottom_sheet_modal | Bottom Sheet Modal 생성
metro make:button | 커스텀 버튼 Widget 생성
metro make:form | 유효성 검사가 포함된 폼 생성
" />

<x-doc-commands title="도우미 명령" rows="
metro make:model | Model 클래스 생성
metro make:provider | Provider 생성
metro make:api_service | API 서비스 생성
metro make:controller | Controller 생성
metro make:event | Event 생성
metro make:route_guard | Route Guard 생성
metro make:config | 설정 파일 생성
metro make:interceptor | 네트워크 Interceptor 생성
metro make:command | 커스텀 Metro 명령어 생성
metro make:env | .env에서 환경 설정 생성
" />

### 사용 예시

``` bash
# 새 페이지 생성
metro make:page settings_page

# 모델 생성
metro make:model User

# API 서비스 생성
metro make:api_service user_api_service
```
