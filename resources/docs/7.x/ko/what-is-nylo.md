# {{ config('app.name') }}란 무엇인가?

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 앱 개발
    - [Flutter가 처음이신가요?](#new-to-flutter "Flutter가 처음이신가요?")
    - [유지보수 및 릴리스 일정](#maintenance-and-release-schedule "유지보수 및 릴리스 일정")
- 크레딧
    - [프레임워크 의존성](#framework-dependencies "프레임워크 의존성")
    - [기여자](#contributors "기여자")


<div id="introduction"></div>

## 소개

{{ config('app.name') }}는 앱 개발을 간소화하기 위해 설계된 Flutter용 마이크로 프레임워크입니다. 사전 구성된 필수 요소가 포함된 구조화된 보일러플레이트를 제공하여, 인프라 설정 대신 앱 기능 구축에 집중할 수 있습니다.

{{ config('app.name') }}에 기본으로 포함된 기능:

- **라우팅** - 가드와 딥 링킹을 지원하는 간단하고 선언적인 라우트 관리
- **네트워킹** - Dio를 활용한 API 서비스, Interceptor 및 응답 변환
- **상태 관리** - NyState와 전역 상태 업데이트를 통한 반응형 상태 관리
- **다국어 지원** - JSON 번역 파일을 사용한 다국어 지원
- **테마** - 테마 전환이 가능한 라이트/다크 모드
- **로컬 스토리지** - Backpack과 NyStorage를 활용한 안전한 저장소
- **폼** - 유효성 검사와 필드 타입을 지원하는 폼 처리
- **푸시 알림** - 로컬 및 원격 알림 지원
- **CLI 도구 (Metro)** - 페이지, Controller, Model 등의 코드 생성

<div id="new-to-flutter"></div>

## Flutter가 처음이신가요?

Flutter가 처음이라면, 공식 리소스부터 시작하세요:

- <a href="https://flutter.dev" target="_BLANK">Flutter 문서</a> - 종합 가이드 및 API 참조
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube 채널</a> - 튜토리얼 및 업데이트
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - 일반적인 작업을 위한 실용적인 레시피

Flutter 기본에 익숙해지면, {{ config('app.name') }}는 표준 Flutter 패턴을 기반으로 하므로 직관적으로 느껴질 것입니다.


<div id="maintenance-and-release-schedule"></div>

## 유지보수 및 릴리스 일정

{{ config('app.name') }}는 <a href="https://semver.org" target="_BLANK">시맨틱 버저닝</a>을 따릅니다:

- **메이저 릴리스** (7.x → 8.x) - 호환성이 깨지는 변경 사항을 위해 연 1회
- **마이너 릴리스** (7.0 → 7.1) - 새로운 기능, 하위 호환 유지
- **패치 릴리스** (7.0.0 → 7.0.1) - 버그 수정 및 소규모 개선

버그 수정 및 보안 패치는 GitHub 저장소를 통해 신속하게 처리됩니다.


<div id="framework-dependencies"></div>

## 프레임워크 의존성

{{ config('app.name') }} v7은 다음 오픈소스 패키지를 기반으로 합니다:

### 핵심 의존성

| 패키지 | 용도 |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | API 요청을 위한 HTTP 클라이언트 |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | 안전한 로컬 스토리지 |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | 국제화 및 포매팅 |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | 스트림을 위한 반응형 확장 |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | 객체의 값 동등성 비교 |

### UI 및 Widget

| 패키지 | 용도 |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | 스켈레톤 로딩 효과 |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | 토스트 알림 |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | 당겨서 새로고침 기능 |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | 엇갈린 그리드 레이아웃 |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | 날짜 선택 필드 |

### 알림 및 연결

| 패키지 | 용도 |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | 로컬 푸시 알림 |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | 네트워크 연결 상태 |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | 앱 아이콘 배지 |

### 유틸리티

| 패키지 | 용도 |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | URL 및 앱 열기 |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | 문자열 대소문자 변환 |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID 생성 |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | 파일 시스템 경로 |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | 입력 마스킹 |


<div id="contributors"></div>

## 기여자

{{ config('app.name') }}에 기여해 주신 모든 분께 감사드립니다! 기여하신 분은 <a href="mailto:support@nylo.dev">support@nylo.dev</a>로 연락해 주시면 여기에 추가해 드리겠습니다.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (창시자)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
