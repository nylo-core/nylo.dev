# 설치

---

<a name="section-1"></a>
- [설치하기](#install "설치하기")
- [프로젝트 실행](#running-the-project "프로젝트 실행")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [프로젝트 정리](#nylo-clean "프로젝트 정리")
  - [iOS Pod 새로고침](#nylo-ios-pod-refresh "iOS Pod 새로고침")
  - [별칭 없이 Metro 실행](#nylo-metro "별칭 없이 Metro 실행")
  - [CLI 업데이트](#nylo-self-update "CLI 업데이트")

<div id="install"></div>

세 개의 명령으로 빈 폴더에서 라우팅, 네트워킹, 테마 및 코드 생성이 미리 구성된 Flutter 앱을 실행할 수 있습니다.

<x-doc-strip label="시작하기 전에" items="Flutter SDK 설치됨, Dart 3" linkText="전체 요구 사항" linkHref="/ko/docs/{{ $version }}/requirements" />

## 설치하기

다음 명령을 순서대로 실행하세요. 각 명령은 안전하게 다시 실행할 수 있습니다.

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI 전역 설치">
`nylo` 명령을 시스템에 설치합니다.

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

이후 터미널을 재시작하거나, `nylo init`이 출력하는 `source` 명령어를 실행하세요.
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


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: strip label "Requires" — no established translation in this locale's existing files -->
<x-doc-strip label="필수" items="nylo_installer 1.8.0+" linkText="CLI 업데이트" linkHref="#nylo-self-update" />

1단계에서 사용한 `nylo` 명령어는 프로젝트 생성 외에도 다양한 기능을 제공합니다. 프로젝트 디렉토리에서 실행하면 빌드 정리, iOS Pod 새로고침, 테스트 실행, 번역 확인을 할 수 있습니다.

<x-doc-commands title="프로젝트 명령" rows="
nylo new | 새 프로젝트 생성
nylo init | 셸에 metro 별칭 설정
nylo clean | flutter clean과 flutter pub get 실행
nylo ios:pod-refresh | iOS Pod 재설치
nylo metro | 별칭 없이 Metro 명령어 실행
" />

<x-doc-commands title="테스트 및 번역" rows="
nylo test | 테스트를 포맷하고 실행
nylo locale:find-untranslated | .tr()로 감싸지 않은 텍스트 찾기
nylo locale:check-missing-keys | lang 파일에서 누락된 키 찾기
" />

<x-doc-commands title="CLI 자체" rows="
nylo self-update | nylo를 최신 버전으로 업데이트
nylo --version | 설치된 버전 표시
" />

옵션을 받는 명령어는 `-h`로 목록을 확인할 수 있습니다. 예: `nylo test -h`. 테스트에 관한 내용은 [테스트 실행](/docs/{{ $version }}/testing#running-tests)에서, `locale:` 명령어에 관한 내용은 [번역 감사](/docs/{{ $version }}/localization#auditing-translations)에서 확인할 수 있습니다.

<div id="nylo-clean"></div>

### 프로젝트 정리

`nylo clean`은 `flutter clean`을 실행한 후 `flutter pub get`을 실행합니다. 일반적인 클린으로 충분하지 않을 때는 플랫폼 플래그를 추가하세요:

``` bash
# flutter clean 실행 후 flutter pub get 실행
nylo clean

# ios/Pods, ios/.symlinks, ios/Podfile.lock도 삭제한 후 pod install --repo-update 실행
nylo clean --ios

# android/에서 gradlew clean도 실행
nylo clean --android

# 위 두 가지 모두
nylo clean --all
```

프로젝트에 `ios/` 또는 `android/` 폴더가 없으면 해당 플랫폼은 경고와 함께 건너뜁니다. `pod install`은 macOS에서만 실행됩니다.

<div id="nylo-ios-pod-refresh"></div>

### iOS Pod 새로고침

CocoaPods 동기화가 어긋나면 `nylo ios:pod-refresh`가 `ios/Pods`, `ios/.symlinks`, `ios/Podfile.lock`을 삭제한 후 `ios/` 폴더에서 `pod install --repo-update`를 실행합니다:

``` bash
nylo ios:pod-refresh
```

macOS에서 프로젝트 루트 디렉토리에서 실행하세요. `nylo clean --ios`와 달리 `flutter clean`이나 `flutter pub get`은 실행하지 않습니다.

<div id="nylo-metro"></div>

### 별칭 없이 Metro 실행

`nylo metro`는 그 뒤에 오는 모든 내용을 Metro(`dart run nylo_framework:main`)에 전달합니다. `nylo init`을 아직 실행하지 않았거나, `nylo init`이 `metro` 별칭을 추가할 수 없는 Windows에서 사용하세요:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### CLI 업데이트

``` bash
nylo self-update
```

이 명령어는 pub.dev를 확인하여 새 버전이 있으면 `nylo_installer`를 다시 설치합니다. 명령어 실행이 끝나면 `nylo`는 업데이트가 있을 때도 알려줍니다. 출력이 터미널로 가지 않을 때는 조용히 동작하므로, 이 알림이 CI 로그나 파이프된 결과에 섞이는 일은 없습니다.

설치된 버전을 확인하려면:

``` bash
nylo --version
```
