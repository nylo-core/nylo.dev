# Metro CLI 도구

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [설치](#install "설치")
- Make 명령어
  - [Controller 생성](#make-controller "Controller 생성")
  - [Model 생성](#make-model "Model 생성")
  - [Page 생성](#make-page "Page 생성")
  - [Stateless 위젯 생성](#make-stateless-widget "Stateless 위젯 생성")
  - [Stateful 위젯 생성](#make-stateful-widget "Stateful 위젯 생성")
  - [Journey 위젯 생성](#make-journey-widget "Journey 위젯 생성")
  - [API Service 생성](#make-api-service "API Service 생성")
  - [Event 생성](#make-event "Event 생성")
  - [Provider 생성](#make-provider "Provider 생성")
  - [Theme 생성](#make-theme "Theme 생성")
  - [Form 생성](#make-forms "Form 생성")
  - [Route Guard 생성](#make-route-guard "Route Guard 생성")
  - [Config 파일 생성](#make-config-file "Config 파일 생성")
  - [Command 생성](#make-command "Command 생성")
  - [State Managed 위젯 생성](#make-state-managed-widget "State Managed 위젯 생성")
  - [Navigation Hub 생성](#make-navigation-hub "Navigation Hub 생성")
  - [Bottom Sheet Modal 생성](#make-bottom-sheet-modal "Bottom Sheet Modal 생성")
  - [Button 생성](#make-button "Button 생성")
  - [Interceptor 생성](#make-interceptor "Interceptor 생성")
  - [Env 파일 생성](#make-env-file "Env 파일 생성")
  - [Key 생성](#make-key "Key 생성")
- App 아이콘
  - [App 아이콘 빌드](#build-app-icons "App 아이콘 빌드")
- 커스텀 명령어
  - [커스텀 명령어 생성](#creating-custom-commands "커스텀 명령어 생성")
  - [커스텀 명령어 실행](#running-custom-commands "커스텀 명령어 실행")
  - [명령어에 옵션 추가](#adding-options-to-custom-commands "명령어에 옵션 추가")
  - [명령어에 플래그 추가](#adding-flags-to-custom-commands "명령어에 플래그 추가")
  - [헬퍼 메서드](#custom-command-helper-methods "헬퍼 메서드")
  - [대화형 입력 메서드](#interactive-input-methods "대화형 입력 메서드")
  - [출력 포맷팅](#output-formatting "출력 포맷팅")
  - [파일 시스템 헬퍼](#file-system-helpers "파일 시스템 헬퍼")
  - [JSON 및 YAML 헬퍼](#json-yaml-helpers "JSON 및 YAML 헬퍼")
  - [케이스 변환 헬퍼](#case-conversion-helpers "케이스 변환 헬퍼")
  - [프로젝트 경로 헬퍼](#project-path-helpers "프로젝트 경로 헬퍼")
  - [플랫폼 헬퍼](#platform-helpers "플랫폼 헬퍼")
  - [Dart 및 Flutter 명령어](#dart-flutter-commands "Dart 및 Flutter 명령어")
  - [Dart 파일 조작](#dart-file-manipulation "Dart 파일 조작")
  - [디렉토리 헬퍼](#directory-helpers "디렉토리 헬퍼")
  - [유효성 검사 헬퍼](#validation-helpers "유효성 검사 헬퍼")
  - [파일 스캐폴딩](#file-scaffolding "파일 스캐폴딩")
  - [태스크 러너](#task-runner "태스크 러너")
  - [테이블 출력](#table-output "테이블 출력")
  - [프로그레스 바](#progress-bar "프로그레스 바")


<div id="introduction"></div>

## 소개

Metro는 {{ config('app.name') }} 프레임워크 내부에서 동작하는 CLI 도구입니다.
개발 속도를 높이는 다양한 유용한 도구를 제공합니다.

<div id="install"></div>

## 설치

`nylo init`을 사용하여 새 Nylo 프로젝트를 생성하면 `metro` 명령어가 터미널에 자동으로 구성됩니다. 모든 Nylo 프로젝트에서 바로 사용할 수 있습니다.

프로젝트 디렉토리에서 `metro`를 실행하여 사용 가능한 모든 명령어를 확인하세요:

``` bash
metro
```

아래와 유사한 출력을 확인할 수 있습니다.

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
  make:key
```

<div id="make-controller"></div>

## Controller 생성

- [새 Controller 만들기](#making-a-new-controller "Make a new controller with Metro")
- [Controller 강제 생성](#forcefully-make-a-controller "Forcefully make a new controller with Metro")
<div id="making-a-new-controller"></div>

### 새 Controller 만들기

터미널에서 아래 명령어를 실행하여 새 Controller를 생성할 수 있습니다.

``` bash
metro make:controller profile_controller
```

`lib/app/controllers/` 디렉토리 내에 해당 Controller가 존재하지 않으면 새로 생성됩니다.

<div id="forcefully-make-a-controller"></div>

### Controller 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Controller를 덮어씁니다.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Model 생성

- [새 Model 만들기](#making-a-new-model "Make a new model with Metro")
- [JSON으로 Model 생성](#make-model-from-json "Make a new model from JSON with Metro")
- [Model 강제 생성](#forcefully-make-a-model "Forcefully make a new model with Metro")
<div id="making-a-new-model"></div>

### 새 Model 만들기

터미널에서 아래 명령어를 실행하여 새 Model을 생성할 수 있습니다.

``` bash
metro make:model product
```

새로 생성된 Model은 `lib/app/models/`에 배치됩니다.

<div id="make-model-from-json"></div>

### JSON으로 Model 생성

**인수:**

`--json` 또는 `-j` 플래그를 사용하면 JSON 페이로드로부터 새 Model을 생성합니다.

``` bash
metro make:model product --json
```

그런 다음 터미널에 JSON을 붙여넣으면 Model이 자동으로 생성됩니다.

<div id="forcefully-make-a-model"></div>

### Model 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Model을 덮어씁니다.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Page 생성

- [새 Page 만들기](#making-a-new-page "Make a new page with Metro")
- [Controller가 포함된 Page 생성](#create-a-page-with-a-controller "Make a new page with a controller with Metro")
- [Auth Page 생성](#create-an-auth-page "Make a new auth page with Metro")
- [Initial Page 생성](#create-an-initial-page "Make a new initial page with Metro")
- [Page 강제 생성](#forcefully-make-a-page "Forcefully make a new page with Metro")

<div id="making-a-new-page"></div>

### 새 Page 만들기

터미널에서 아래 명령어를 실행하여 새 Page를 생성할 수 있습니다.

``` bash
metro make:page product_page
```

`lib/resources/pages/` 디렉토리 내에 해당 Page가 존재하지 않으면 새로 생성됩니다.

<div id="create-a-page-with-a-controller"></div>

### Controller가 포함된 Page 생성

터미널에서 아래 명령어를 실행하여 Controller가 포함된 새 Page를 생성할 수 있습니다.

**인수:**

`--controller` 또는 `-c` 플래그를 사용하면 Controller가 포함된 새 Page를 생성합니다.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Auth Page 생성

터미널에서 아래 명령어를 실행하여 새 Auth Page를 생성할 수 있습니다.

**인수:**

`--auth` 또는 `-a` 플래그를 사용하면 새 Auth Page를 생성합니다.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Initial Page 생성

터미널에서 아래 명령어를 실행하여 새 Initial Page를 생성할 수 있습니다.

**인수:**

`--initial` 또는 `-i` 플래그를 사용하면 새 Initial Page를 생성합니다.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Page 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Page를 덮어씁니다.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Stateless 위젯 생성

- [새 Stateless 위젯 만들기](#making-a-new-stateless-widget "Make a new stateless widget with Metro")
- [Stateless 위젯 강제 생성](#forcefully-make-a-stateless-widget "Forcefully make a new stateless widget with Metro")
<div id="making-a-new-stateless-widget"></div>

### 새 Stateless 위젯 만들기

터미널에서 아래 명령어를 실행하여 새 Stateless 위젯을 생성할 수 있습니다.

``` bash
metro make:stateless_widget product_rating_widget
```

위 명령어는 `lib/resources/widgets/` 디렉토리 내에 해당 위젯이 존재하지 않으면 새로 생성합니다.

<div id="forcefully-make-a-stateless-widget"></div>

### Stateless 위젯 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 위젯을 덮어씁니다.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Stateful 위젯 생성

- [새 Stateful 위젯 만들기](#making-a-new-stateful-widget "Make a new stateful widget with Metro")
- [Stateful 위젯 강제 생성](#forcefully-make-a-stateful-widget "Forcefully make a new stateful widget with Metro")

<div id="making-a-new-stateful-widget"></div>

### 새 Stateful 위젯 만들기

터미널에서 아래 명령어를 실행하여 새 Stateful 위젯을 생성할 수 있습니다.

``` bash
metro make:stateful_widget product_rating_widget
```

위 명령어는 `lib/resources/widgets/` 디렉토리 내에 해당 위젯이 존재하지 않으면 새로 생성합니다.

<div id="forcefully-make-a-stateful-widget"></div>

### Stateful 위젯 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 위젯을 덮어씁니다.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Journey 위젯 생성

- [새 Journey 위젯 만들기](#making-a-new-journey-widget "Make a new journey widget with Metro")
- [Journey 위젯 강제 생성](#forcefully-make-a-journey-widget "Forcefully make a new journey widget with Metro")

<div id="making-a-new-journey-widget"></div>

### 새 Journey 위젯 만들기

터미널에서 아래 명령어를 실행하여 새 Journey 위젯을 생성할 수 있습니다.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# BaseNavigationHub가 있는 경우의 전체 예제
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

위 명령어는 `lib/resources/widgets/` 디렉토리 내에 해당 위젯이 존재하지 않으면 새로 생성합니다.

`--parent` 인수는 새 Journey 위젯이 추가될 부모 위젯을 지정하는 데 사용됩니다.

예제

``` bash
metro make:navigation_hub onboarding
```

다음으로, 새 Journey 위젯을 추가합니다.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Journey 위젯 강제 생성
**인수:**
`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 위젯을 덮어씁니다.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## API Service 생성

- [새 API Service 만들기](#making-a-new-api-service "Make a new API Service with Metro")
- [Model이 포함된 API Service 생성](#making-a-new-api-service-with-a-model "Make a new API Service with a model with Metro")
- [Postman으로 API Service 생성](#make-api-service-using-postman "Create API services with Postman")
- [API Service 강제 생성](#forcefully-make-an-api-service "Forcefully make a new API Service with Metro")

<div id="making-a-new-api-service"></div>

### 새 API Service 만들기

터미널에서 아래 명령어를 실행하여 새 API Service를 생성할 수 있습니다.

``` bash
metro make:api_service user_api_service
```

새로 생성된 API Service는 `lib/app/networking/`에 배치됩니다.

<div id="making-a-new-api-service-with-a-model"></div>

### Model이 포함된 API Service 생성

터미널에서 아래 명령어를 실행하여 Model이 포함된 새 API Service를 생성할 수 있습니다.

**인수:**

`--model` 또는 `-m` 옵션을 사용하면 Model이 포함된 새 API Service를 생성합니다.

``` bash
metro make:api_service user --model="User"
```

새로 생성된 API Service는 `lib/app/networking/`에 배치됩니다.

### API Service 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 API Service를 덮어씁니다.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Event 생성

- [새 Event 만들기](#making-a-new-event "Make a new event with Metro")
- [Event 강제 생성](#forcefully-make-an-event "Forcefully make a new event with Metro")

<div id="making-a-new-event"></div>

### 새 Event 만들기

터미널에서 아래 명령어를 실행하여 새 Event를 생성할 수 있습니다.

``` bash
metro make:event login_event
```

`lib/app/events`에 새 Event가 생성됩니다.

<div id="forcefully-make-an-event"></div>

### Event 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Event를 덮어씁니다.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Provider 생성

- [새 Provider 만들기](#making-a-new-provider "Make a new provider with Metro")
- [Provider 강제 생성](#forcefully-make-a-provider "Forcefully make a new provider with Metro")

<div id="making-a-new-provider"></div>

### 새 Provider 만들기

아래 명령어를 사용하여 애플리케이션에 새 Provider를 생성합니다.

``` bash
metro make:provider firebase_provider
```

새로 생성된 Provider는 `lib/app/providers/`에 배치됩니다.

<div id="forcefully-make-a-provider"></div>

### Provider 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Provider를 덮어씁니다.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Theme 생성

- [새 Theme 만들기](#making-a-new-theme "Make a new theme with Metro")
- [Theme 강제 생성](#forcefully-make-a-theme "Forcefully make a new theme with Metro")

<div id="making-a-new-theme"></div>

### 새 Theme 만들기

터미널에서 아래 명령어를 실행하여 Theme를 생성할 수 있습니다.

``` bash
metro make:theme bright_theme
```

`lib/resources/themes/`에 새 Theme가 생성됩니다.

<div id="forcefully-make-a-theme"></div>

### Theme 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Theme를 덮어씁니다.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Form 생성

- [새 Form 만들기](#making-a-new-form "Make a new form with Metro")
- [Form 강제 생성](#forcefully-make-a-form "Forcefully make a new form with Metro")

<div id="making-a-new-form"></div>

### 새 Form 만들기

터미널에서 아래 명령어를 실행하여 새 Form을 생성할 수 있습니다.

``` bash
metro make:form car_advert_form
```

`lib/app/forms`에 새 Form이 생성됩니다.

<div id="forcefully-make-a-form"></div>

### Form 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Form을 덮어씁니다.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Route Guard 생성

- [새 Route Guard 만들기](#making-a-new-route-guard "Make a new route guard with Metro")
- [Route Guard 강제 생성](#forcefully-make-a-route-guard "Forcefully make a new route guard with Metro")

<div id="making-a-new-route-guard"></div>

### 새 Route Guard 만들기

터미널에서 아래 명령어를 실행하여 Route Guard를 생성할 수 있습니다.

``` bash
metro make:route_guard premium_content
```

`lib/app/route_guards`에 새 Route Guard가 생성됩니다.

<div id="forcefully-make-a-route-guard"></div>

### Route Guard 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Route Guard를 덮어씁니다.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Config 파일 생성

- [새 Config 파일 만들기](#making-a-new-config-file "Make a new config file with Metro")
- [Config 파일 강제 생성](#forcefully-make-a-config-file "Forcefully make a new config file with Metro")

<div id="making-a-new-config-file"></div>

### 새 Config 파일 만들기

터미널에서 아래 명령어를 실행하여 새 Config 파일을 생성할 수 있습니다.

``` bash
metro make:config shopping_settings
```

`lib/app/config`에 새 Config 파일이 생성됩니다.

<div id="forcefully-make-a-config-file"></div>

### Config 파일 강제 생성

**인수:**

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Config 파일을 덮어씁니다.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Command 생성

- [새 Command 만들기](#making-a-new-command "Make a new command with Metro")
- [Command 강제 생성](#forcefully-make-a-command "Forcefully make a new command with Metro")

<div id="making-a-new-command"></div>

### 새 Command 만들기

터미널에서 아래 명령어를 실행하여 새 Command를 생성할 수 있습니다.

``` bash
metro make:command my_command
```

`lib/app/commands`에 새 Command가 생성됩니다.

<div id="forcefully-make-a-command"></div>

### Command 강제 생성

**인수:**
`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Command를 덮어씁니다.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## State Managed 위젯 생성

터미널에서 아래 명령어를 실행하여 새 State Managed 위젯을 생성할 수 있습니다.

``` bash
metro make:state_managed_widget product_rating_widget
```

위 명령어는 `lib/resources/widgets/`에 새 위젯을 생성합니다.

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 위젯을 덮어씁니다.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Navigation Hub 생성

터미널에서 아래 명령어를 실행하여 새 Navigation Hub를 생성할 수 있습니다.

``` bash
metro make:navigation_hub dashboard
```

`lib/resources/pages/`에 새 Navigation Hub가 생성되고 라우트가 자동으로 추가됩니다.

**인수:**

| 플래그 | 약어 | 설명 |
|------|-------|-------------|
| `--auth` | `-a` | Auth Page로 생성 |
| `--initial` | `-i` | Initial Page로 생성 |
| `--force` | `-f` | 이미 존재하면 덮어쓰기 |

``` bash
# Initial Page로 생성
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Bottom Sheet Modal 생성

터미널에서 아래 명령어를 실행하여 새 Bottom Sheet Modal을 생성할 수 있습니다.

``` bash
metro make:bottom_sheet_modal payment_options
```

`lib/resources/widgets/`에 새 Bottom Sheet Modal이 생성됩니다.

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Modal을 덮어씁니다.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Button 생성

터미널에서 아래 명령어를 실행하여 새 Button 위젯을 생성할 수 있습니다.

``` bash
metro make:button checkout_button
```

`lib/resources/widgets/`에 새 Button 위젯이 생성됩니다.

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Button을 덮어씁니다.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Interceptor 생성

터미널에서 아래 명령어를 실행하여 새 네트워크 Interceptor를 생성할 수 있습니다.

``` bash
metro make:interceptor auth_interceptor
```

`lib/app/networking/dio/interceptors/`에 새 Interceptor가 생성됩니다.

`--force` 또는 `-f` 플래그를 사용하면 이미 존재하는 Interceptor를 덮어씁니다.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Env 파일 생성

터미널에서 아래 명령어를 실행하여 새 환경 파일을 생성할 수 있습니다.

``` bash
metro make:env .env.staging
```

프로젝트 루트에 새 `.env` 파일이 생성됩니다.

<div id="make-key"></div>

## Key 생성

환경 암호화를 위한 보안 `APP_KEY`를 생성합니다. v7에서 암호화된 `.env` 파일에 사용됩니다.

``` bash
metro make:key
```

**인수:**

| 플래그 / 옵션 | 약어 | 설명 |
|---------------|-------|-------------|
| `--force` | `-f` | 기존 APP_KEY 덮어쓰기 |
| `--file` | `-e` | 대상 .env 파일 (기본값: `.env`) |

``` bash
# Key 생성 및 기존 키 덮어쓰기
metro make:key --force

# 특정 env 파일에 Key 생성
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## App 아이콘 빌드

아래 명령어를 실행하여 iOS와 Android의 모든 앱 아이콘을 생성할 수 있습니다.

``` bash
dart run flutter_launcher_icons:main
```

이 명령어는 `pubspec.yaml` 파일의 <b>flutter_icons</b> 설정을 사용합니다.

<div id="custom-commands"></div>

## 커스텀 명령어

커스텀 명령어를 사용하면 프로젝트별 명령어로 Nylo의 CLI를 확장할 수 있습니다. 이 기능을 통해 반복 작업을 자동화하고, 배포 워크플로우를 구현하거나, 프로젝트의 커맨드라인 도구에 커스텀 기능을 직접 추가할 수 있습니다.

- [커스텀 명령어 생성](#creating-custom-commands)
- [커스텀 명령어 실행](#running-custom-commands)
- [명령어에 옵션 추가](#adding-options-to-custom-commands)
- [명령어에 플래그 추가](#adding-flags-to-custom-commands)
- [헬퍼 메서드](#custom-command-helper-methods)

> **참고:** 현재 커스텀 명령어에서는 nylo_framework.dart를 임포트할 수 없습니다. 대신 ny_cli.dart를 사용하세요.

<div id="creating-custom-commands"></div>

## 커스텀 명령어 생성

새 커스텀 명령어를 생성하려면 `make:command` 기능을 사용합니다:

```bash
metro make:command current_time
```

`--category` 옵션을 사용하여 명령어의 카테고리를 지정할 수 있습니다:

```bash
# 카테고리 지정
metro make:command current_time --category="project"
```

이 명령어는 `lib/app/commands/current_time.dart`에 다음과 같은 구조의 새 명령어 파일을 생성합니다:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

명령어는 등록된 모든 명령어 목록이 포함된 `lib/app/commands/commands.json` 파일에 자동으로 등록됩니다:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## 커스텀 명령어 실행

생성된 커스텀 명령어는 Metro 약어 또는 전체 Dart 명령어를 사용하여 실행할 수 있습니다:

```bash
metro app:current_time
```

인수 없이 `metro`를 실행하면 "Custom Commands" 섹션에 커스텀 명령어가 나열됩니다:

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

명령어의 도움말 정보를 표시하려면 `--help` 또는 `-h` 플래그를 사용합니다:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## 명령어에 옵션 추가

옵션을 사용하면 사용자가 명령어에 추가 입력을 전달할 수 있습니다. `builder` 메서드에서 명령어에 옵션을 추가할 수 있습니다:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // 기본값이 있는 옵션 추가
  command.addOption(
    'environment',     // 옵션 이름
    abbr: 'e',         // 약어
    help: 'Target deployment environment', // 도움말 텍스트
    defaultValue: 'development',  // 기본값
    allowed: ['development', 'staging', 'production'] // 허용 값
  );

  return command;
}
```

명령어의 `handle` 메서드에서 옵션 값에 접근합니다:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // 명령어 구현...
}
```

사용 예:

```bash
metro project:deploy --environment=production
# 또는 약어 사용
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## 명령어에 플래그 추가

플래그는 켜거나 끌 수 있는 불리언 옵션입니다. `addFlag` 메서드를 사용하여 명령어에 플래그를 추가합니다:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // 플래그 이름
    abbr: 'v',       // 약어
    help: 'Enable verbose output', // 도움말 텍스트
    defaultValue: false  // 기본값 off
  );

  return command;
}
```

명령어의 `handle` 메서드에서 플래그 상태를 확인합니다:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // 추가 로깅...
  }

  // 명령어 구현...
}
```

사용 예:

```bash
metro project:deploy --verbose
# 또는 약어 사용
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## 헬퍼 메서드

`NyCustomCommand` 기본 클래스는 일반적인 작업을 지원하는 여러 헬퍼 메서드를 제공합니다:

#### 메시지 출력

다양한 색상으로 메시지를 출력하는 메서드:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | 파란색 텍스트로 정보 메시지 출력 |
| [`error`](#custom-command-helper-formatting)     | 빨간색 텍스트로 오류 메시지 출력 |
| [`success`](#custom-command-helper-formatting)   | 녹색 텍스트로 성공 메시지 출력 |
| [`warning`](#custom-command-helper-formatting)   | 노란색 텍스트로 경고 메시지 출력 |

#### 프로세스 실행

프로세스를 실행하고 콘솔에 출력을 표시합니다:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | `pubspec.yaml`에 패키지 추가 |
| [`addPackages`](#custom-command-helper-add-packages) | `pubspec.yaml`에 여러 패키지 추가 |
| [`runProcess`](#custom-command-helper-run-process) | 외부 프로세스를 실행하고 콘솔에 출력 표시 |
| [`prompt`](#custom-command-helper-prompt)    | 사용자 입력을 텍스트로 수집 |
| [`confirm`](#custom-command-helper-confirm)   | 예/아니오 질문을 하고 불리언 결과 반환 |
| [`select`](#custom-command-helper-select)    | 옵션 목록을 표시하고 사용자가 하나를 선택 |
| [`multiSelect`](#custom-command-helper-multi-select) | 사용자가 목록에서 여러 옵션을 선택 |

#### 네트워크 요청

콘솔에서 네트워크 요청 수행:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Nylo API 클라이언트를 사용하여 API 호출 |


#### 로딩 스피너

함수를 실행하는 동안 로딩 스피너를 표시합니다:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | 함수를 실행하는 동안 로딩 스피너 표시 |
| [`createSpinner`](#manual-spinner-control) | 수동 제어를 위한 스피너 인스턴스 생성 |

#### 커스텀 명령어 헬퍼

명령어 인수를 관리하기 위한 다음 헬퍼 메서드도 사용할 수 있습니다:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | 명령어 인수에서 문자열 값 가져오기 |
| [`getBool`](#custom-command-helper-get-bool)   | 명령어 인수에서 불리언 값 가져오기 |
| [`getInt`](#custom-command-helper-get-int)    | 명령어 인수에서 정수 값 가져오기 |
| [`sleep`](#custom-command-helper-sleep) | 지정된 시간 동안 실행 일시 중지 |


### 외부 프로세스 실행

```dart
// 콘솔에 출력을 표시하면서 프로세스 실행
await runProcess('flutter build web --release');

// 조용히 프로세스 실행
await runProcess('flutter pub get', silent: true);

// 특정 디렉토리에서 프로세스 실행
await runProcess('git pull', workingDirectory: './my-project');
```

### 패키지 관리

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// pubspec.yaml에 패키지 추가
addPackage('firebase_core', version: '^2.4.0');

// pubspec.yaml에 dev 패키지 추가
addPackage('build_runner', dev: true);

// 여러 패키지를 한 번에 추가
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### 출력 포맷팅

```dart
// 색상 코딩으로 상태 메시지 출력
info('Processing files...');    // 파란색 텍스트
error('Operation failed');      // 빨간색 텍스트
success('Deployment complete'); // 녹색 텍스트
warning('Outdated package');    // 노란색 텍스트
```

<div id="interactive-input-methods"></div>

## 대화형 입력 메서드

`NyCustomCommand` 기본 클래스는 터미널에서 사용자 입력을 수집하는 여러 메서드를 제공합니다. 이 메서드들을 사용하면 커스텀 명령어에서 대화형 커맨드라인 인터페이스를 쉽게 만들 수 있습니다.

<div id="custom-command-helper-prompt"></div>

### 텍스트 입력

```dart
String prompt(String question, {String defaultValue = ''})
```

사용자에게 질문을 표시하고 텍스트 응답을 수집합니다.

**매개변수:**
- `question`: 표시할 질문 또는 프롬프트
- `defaultValue`: 사용자가 Enter만 누른 경우의 선택적 기본값

**반환값:** 사용자의 입력 문자열, 또는 입력이 없는 경우 기본값

**예제:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### 확인

```dart
bool confirm(String question, {bool defaultValue = false})
```

사용자에게 예/아니오 질문을 하고 불리언 결과를 반환합니다.

**매개변수:**
- `question`: 예/아니오 질문
- `defaultValue`: 기본 답변 (true이면 예, false이면 아니오)

**반환값:** 사용자가 예라고 답하면 `true`, 아니오라고 답하면 `false`

**예제:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // 사용자가 확인했거나 Enter를 눌러 기본값 수락
  await runProcess('flutter pub get');
} else {
  // 사용자가 거부
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### 단일 선택

```dart
String select(String question, List<String> options, {String? defaultOption})
```

옵션 목록을 표시하고 사용자가 하나를 선택할 수 있게 합니다.

**매개변수:**
- `question`: 선택 프롬프트
- `options`: 사용 가능한 옵션 목록
- `defaultOption`: 선택적 기본 선택

**반환값:** 선택된 옵션 문자열

**예제:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### 다중 선택

```dart
List<String> multiSelect(String question, List<String> options)
```

사용자가 목록에서 여러 옵션을 선택할 수 있게 합니다.

**매개변수:**
- `question`: 선택 프롬프트
- `options`: 사용 가능한 옵션 목록

**반환값:** 선택된 옵션 목록

**예제:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## API 헬퍼 메서드

`api` 헬퍼 메서드는 커스텀 명령어에서 네트워크 요청을 간편하게 만듭니다.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## 기본 사용 예제

### GET 요청

```dart
// 데이터 가져오기
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST 요청

```dart
// 리소스 생성
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT 요청

```dart
// 리소스 업데이트
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE 요청

```dart
// 리소스 삭제
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH 요청

```dart
// 리소스 부분 업데이트
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### 쿼리 매개변수 사용

```dart
// 쿼리 매개변수 추가
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### 스피너와 함께 사용

```dart
// 더 나은 UI를 위해 스피너와 함께 사용
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // 데이터 처리
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## 스피너 기능

스피너는 커스텀 명령어에서 장시간 실행되는 작업 중에 시각적 피드백을 제공합니다. 비동기 작업을 실행하는 동안 메시지와 함께 애니메이션 표시기를 보여주어 진행 상태를 나타내며 사용자 경험을 향상시킵니다.

- [withSpinner 사용](#using-with-spinner)
- [수동 스피너 제어](#manual-spinner-control)
- [예제](#spinner-examples)

<div id="using-with-spinner"></div>

## withSpinner 사용

`withSpinner` 메서드를 사용하면 비동기 작업을 스피너 애니메이션으로 감싸서 작업 시작 시 자동으로 시작되고 완료 또는 실패 시 자동으로 중지됩니다:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**매개변수:**
- `task`: 실행할 비동기 함수
- `message`: 스피너 실행 중 표시할 텍스트
- `successMessage`: 성공적으로 완료 시 표시할 선택적 메시지
- `errorMessage`: 작업 실패 시 표시할 선택적 메시지

**반환값:** task 함수의 결과

**예제:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // 스피너와 함께 작업 실행
  final projectFiles = await withSpinner(
    task: () async {
      // 장시간 실행 작업 (예: 프로젝트 파일 분석)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // 결과를 사용하여 계속
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## 수동 스피너 제어

스피너 상태를 수동으로 제어해야 하는 더 복잡한 시나리오에서는 스피너 인스턴스를 생성할 수 있습니다:

```dart
ConsoleSpinner createSpinner(String message)
```

**매개변수:**
- `message`: 스피너 실행 중 표시할 텍스트

**반환값:** 수동으로 제어할 수 있는 `ConsoleSpinner` 인스턴스

**수동 제어 예제:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // 스피너 인스턴스 생성
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // 첫 번째 작업
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // 두 번째 작업
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // 세 번째 작업
    await runProcess('./deploy.sh', silent: true);

    // 성공적으로 완료
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // 실패 처리
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## 예제

### 스피너를 사용한 간단한 작업

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // 의존성 설치
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### 여러 연속 작업

```dart
@override
Future<void> handle(CommandResult result) async {
  // 스피너를 사용한 첫 번째 작업
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // 스피너를 사용한 두 번째 작업
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // 스피너를 사용한 세 번째 작업
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### 수동 제어를 사용한 복잡한 워크플로우

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // 상태 업데이트와 함께 여러 단계 실행
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // 프로세스 완료
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

커스텀 명령어에서 스피너를 사용하면 장시간 실행되는 작업 중에 사용자에게 명확한 시각적 피드백을 제공하여 더 세련되고 전문적인 커맨드라인 경험을 만들 수 있습니다.

<div id="custom-command-helper-get-string"></div>

### 옵션에서 문자열 값 가져오기

```dart
String getString(String name, {String defaultValue = ''})
```

**매개변수:**

- `name`: 가져올 옵션의 이름
- `defaultValue`: 옵션이 제공되지 않은 경우의 선택적 기본값

**반환값:** 옵션의 값(문자열)

**예제:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### 옵션에서 불리언 값 가져오기

```dart
bool getBool(String name, {bool defaultValue = false})
```

**매개변수:**
- `name`: 가져올 옵션의 이름
- `defaultValue`: 옵션이 제공되지 않은 경우의 선택적 기본값

**반환값:** 옵션의 값(불리언)


**예제:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### 옵션에서 정수 값 가져오기

```dart
int getInt(String name, {int defaultValue = 0})
```

**매개변수:**
- `name`: 가져올 옵션의 이름
- `defaultValue`: 옵션이 제공되지 않은 경우의 선택적 기본값

**반환값:** 옵션의 값(정수)

**예제:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### 지정된 시간 동안 대기

```dart
void sleep(int seconds)
```

**매개변수:**
- `seconds`: 대기할 초 수

**반환값:** 없음

**예제:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## 출력 포맷팅

기본적인 `info`, `error`, `success`, `warning` 메서드 외에도 `NyCustomCommand`는 추가적인 출력 헬퍼를 제공합니다:

```dart
@override
Future<void> handle(CommandResult result) async {
  // 일반 텍스트 출력 (색상 없음)
  line('Processing your request...');

  // 빈 줄 출력
  newLine();       // 빈 줄 하나
  newLine(3);      // 빈 줄 세 개

  // 음소거된 코멘트 출력 (회색 텍스트)
  comment('This is a background note');

  // 눈에 띄는 알림 상자 출력
  alert('Important: Please read carefully');

  // ask는 prompt의 별칭
  final name = ask('What is your name?');

  // 민감한 데이터에 대한 숨김 입력 (예: 비밀번호, API 키)
  final apiKey = promptSecret('Enter your API key:');

  // 오류 메시지와 종료 코드로 명령어 중단
  if (name.isEmpty) {
    abort('Name is required');  // 종료 코드 1로 종료
  }
}
```

| 메서드 | 설명 |
|--------|-------------|
| `line(String message)` | 색상 없이 일반 텍스트 출력 |
| `newLine([int count = 1])` | 빈 줄 출력 |
| `comment(String message)` | 음소거/회색 텍스트 출력 |
| `alert(String message)` | 눈에 띄는 알림 상자 출력 |
| `ask(String question, {String defaultValue})` | `prompt`의 별칭 |
| `promptSecret(String question)` | 민감한 데이터에 대한 숨김 입력 |
| `abort([String? message, int exitCode = 1])` | 오류와 함께 명령어 종료 |

<div id="file-system-helpers"></div>

## 파일 시스템 헬퍼

`NyCustomCommand`에는 일반적인 작업을 위해 `dart:io`를 수동으로 임포트할 필요가 없는 내장 파일 시스템 헬퍼가 포함되어 있습니다.

### 파일 읽기 및 쓰기

```dart
@override
Future<void> handle(CommandResult result) async {
  // 파일 존재 여부 확인
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // 디렉토리 존재 여부 확인
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // 파일 읽기 (비동기)
  String content = await readFile('pubspec.yaml');

  // 파일 읽기 (동기)
  String contentSync = readFileSync('pubspec.yaml');

  // 파일 쓰기 (비동기)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // 파일 쓰기 (동기)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // 파일에 내용 추가
  await appendFile('log.txt', 'New log entry\n');

  // 디렉토리가 존재하는지 확인 (없으면 생성)
  await ensureDirectory('lib/generated');

  // 파일 삭제
  await deleteFile('lib/generated/output.dart');

  // 파일 복사
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| 메서드 | 설명 |
|--------|-------------|
| `fileExists(String path)` | 파일이 존재하면 `true` 반환 |
| `directoryExists(String path)` | 디렉토리가 존재하면 `true` 반환 |
| `readFile(String path)` | 파일을 문자열로 읽기 (비동기) |
| `readFileSync(String path)` | 파일을 문자열로 읽기 (동기) |
| `writeFile(String path, String content)` | 파일에 내용 쓰기 (비동기) |
| `writeFileSync(String path, String content)` | 파일에 내용 쓰기 (동기) |
| `appendFile(String path, String content)` | 파일에 내용 추가 |
| `ensureDirectory(String path)` | 디렉토리가 없으면 생성 |
| `deleteFile(String path)` | 파일 삭제 |
| `copyFile(String source, String destination)` | 파일 복사 |

<div id="json-yaml-helpers"></div>

## JSON 및 YAML 헬퍼

내장 헬퍼로 JSON 및 YAML 파일을 읽고 쓸 수 있습니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // JSON 파일을 Map으로 읽기
  Map<String, dynamic> config = await readJson('config.json');

  // JSON 파일을 List로 읽기
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // JSON 파일에 데이터 쓰기 (기본적으로 정렬된 출력)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // 압축된 JSON 쓰기
  await writeJson('output.json', data, pretty: false);

  // JSON 배열 파일에 항목 추가
  // 파일에 [{"name": "a"}]가 포함되어 있으면 해당 배열에 추가
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // 이 키로 중복 방지
  );

  // YAML 파일을 Map으로 읽기
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| 메서드 | 설명 |
|--------|-------------|
| `readJson(String path)` | JSON 파일을 `Map<String, dynamic>`으로 읽기 |
| `readJsonArray(String path)` | JSON 파일을 `List<dynamic>`으로 읽기 |
| `writeJson(String path, dynamic data, {bool pretty = true})` | 데이터를 JSON으로 쓰기 |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON 배열 파일에 추가 |
| `readYaml(String path)` | YAML 파일을 `Map<String, dynamic>`으로 읽기 |

<div id="case-conversion-helpers"></div>

## 케이스 변환 헬퍼

`recase` 패키지를 임포트하지 않고도 네이밍 규칙 간에 문자열을 변환할 수 있습니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| 메서드 | 출력 형식 | 예제 |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## 프로젝트 경로 헬퍼

표준 {{ config('app.name') }} 프로젝트 디렉토리의 Getter입니다. 프로젝트 루트를 기준으로 한 상대 경로를 반환합니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // 프로젝트 루트를 기준으로 커스텀 경로 빌드
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| 속성 | 경로 |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | 프로젝트 내의 상대 경로를 확인 |

<div id="platform-helpers"></div>

## 플랫폼 헬퍼

플랫폼을 확인하고 환경 변수에 접근합니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // 플랫폼 확인
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // 현재 작업 디렉토리
  info('Working in: $workingDirectory');

  // 시스템 환경 변수 읽기
  String home = env('HOME', '/default/path');
}
```

| 속성 / 메서드 | 설명 |
|-------------------|-------------|
| `isWindows` | Windows에서 실행 중이면 `true` |
| `isMacOS` | macOS에서 실행 중이면 `true` |
| `isLinux` | Linux에서 실행 중이면 `true` |
| `workingDirectory` | 현재 작업 디렉토리 경로 |
| `env(String key, [String defaultValue = ''])` | 시스템 환경 변수 읽기 |

<div id="dart-flutter-commands"></div>

## Dart 및 Flutter 명령어

일반적인 Dart 및 Flutter CLI 명령어를 헬퍼 메서드로 실행합니다. 각각 프로세스 종료 코드를 반환합니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dart 파일 또는 디렉토리 포맷
  await dartFormat('lib/app/models/user.dart');

  // dart analyze 실행
  int analyzeResult = await dartAnalyze('lib/');

  // flutter pub get 실행
  await flutterPubGet();

  // flutter clean 실행
  await flutterClean();

  // 추가 인수와 함께 대상 빌드
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // flutter test 실행
  await flutterTest();
  await flutterTest('test/unit/');  // 특정 디렉토리
}
```

| 메서드 | 설명 |
|--------|-------------|
| `dartFormat(String path)` | 파일 또는 디렉토리에서 `dart format` 실행 |
| `dartAnalyze([String? path])` | `dart analyze` 실행 |
| `flutterPubGet()` | `flutter pub get` 실행 |
| `flutterClean()` | `flutter clean` 실행 |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` 실행 |
| `flutterTest([String? path])` | `flutter test` 실행 |

<div id="dart-file-manipulation"></div>

## Dart 파일 조작

스캐폴딩 도구를 빌드할 때 유용한 Dart 파일 프로그래밍 편집 헬퍼입니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dart 파일에 import 문 추가 (중복 방지)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // 파일의 마지막 닫는 중괄호 앞에 코드 삽입
  // 등록 맵에 항목을 추가할 때 유용
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // 파일에 특정 문자열이 포함되어 있는지 확인
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // 파일이 정규식 패턴과 일치하는지 확인
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| 메서드 | 설명 |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart 파일에 import 추가 (이미 있으면 건너뜀) |
| `insertBeforeClosingBrace(String filePath, String code)` | 파일의 마지막 `}` 앞에 코드 삽입 |
| `fileContains(String filePath, String identifier)` | 파일에 문자열이 포함되어 있는지 확인 |
| `fileContainsPattern(String filePath, Pattern pattern)` | 파일이 패턴과 일치하는지 확인 |

<div id="directory-helpers"></div>

## 디렉토리 헬퍼

디렉토리 작업 및 파일 검색을 위한 헬퍼입니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // 디렉토리 내용 나열
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // 재귀적으로 나열
  var allEntities = listDirectory('lib/', recursive: true);

  // 조건에 맞는 파일 찾기
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // 이름 패턴으로 파일 찾기
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // 디렉토리 재귀적 삭제
  await deleteDirectory('build/');

  // 디렉토리 복사 (재귀)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| 메서드 | 설명 |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | 디렉토리 내용 나열 |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | 조건에 맞는 파일 찾기 |
| `deleteDirectory(String path)` | 디렉토리 재귀적 삭제 |
| `copyDirectory(String source, String destination)` | 디렉토리 재귀적 복사 |

<div id="validation-helpers"></div>

## 유효성 검사 헬퍼

코드 생성을 위한 사용자 입력 유효성 검사 및 정리 헬퍼입니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dart 식별자 유효성 검사
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // 비어있지 않은 첫 번째 인수 필수
  String name = requireArgument(result, message: 'Please provide a name');

  // 클래스 이름 정리 (PascalCase, 접미사 제거)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // 반환값: 'User'

  // 파일 이름 정리 (snake_case, 확장자 포함)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // 반환값: 'user_model.dart'
}
```

| 메서드 | 설명 |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart 식별자 이름 유효성 검사 |
| `requireArgument(CommandResult result, {String? message})` | 비어있지 않은 첫 번째 인수 필수 또는 중단 |
| `cleanClassName(String name, {List<String> removeSuffixes})` | 클래스 이름 정리 및 PascalCase 변환 |
| `cleanFileName(String name, {String extension = '.dart'})` | 파일 이름 정리 및 snake_case 변환 |

<div id="file-scaffolding"></div>

## 파일 스캐폴딩

스캐폴딩 시스템을 사용하여 하나 또는 여러 파일을 내용과 함께 생성합니다.

### 단일 파일

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // 이미 존재하면 덮어쓰지 않음
    successMessage: 'AuthService created',
  );
}
```

### 여러 파일

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

`ScaffoldFile` 클래스가 받는 매개변수:

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `path` | `String` | 생성할 파일 경로 |
| `content` | `String` | 파일 내용 |
| `successMessage` | `String?` | 성공 시 표시할 메시지 |

<div id="task-runner"></div>

## 태스크 러너

자동 상태 출력과 함께 일련의 명명된 작업을 실행합니다.

### 기본 태스크 러너

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // 실패 시 파이프라인 중지 (기본값)
    ),
  ]);
}
```

### 스피너를 사용한 태스크 러너

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

`CommandTask` 클래스가 받는 매개변수:

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `name` | `String` | 필수 | 출력에 표시되는 작업 이름 |
| `action` | `Future<void> Function()` | 필수 | 실행할 비동기 함수 |
| `stopOnError` | `bool` | `true` | 이 작업이 실패하면 나머지 작업을 중지할지 여부 |

<div id="table-output"></div>

## 테이블 출력

콘솔에 포맷된 ASCII 테이블을 표시합니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

출력:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## 프로그레스 바

알려진 항목 수가 있는 작업에 대해 프로그레스 바를 표시합니다.

### 수동 프로그레스 바

```dart
@override
Future<void> handle(CommandResult result) async {
  // 100개 항목에 대한 프로그레스 바 생성
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // 1 증가
  }

  progress.complete('All files processed');
}
```

### 프로그레스와 함께 항목 처리

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // 자동 프로그레스 추적과 함께 항목 처리
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // 각 파일 처리
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### 동기 프로그레스

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // 동기 처리
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

`ConsoleProgressBar` 클래스가 제공하는 메서드:

| 메서드 | 설명 |
|--------|-------------|
| `start()` | 프로그레스 바 시작 |
| `tick([int amount = 1])` | 프로그레스 증가 |
| `update(int value)` | 특정 값으로 프로그레스 설정 |
| `updateMessage(String newMessage)` | 표시되는 메시지 변경 |
| `complete([String? completionMessage])` | 선택적 메시지와 함께 완료 |
| `stop()` | 완료하지 않고 중지 |
| `current` | 현재 프로그레스 값 (getter) |
| `percentage` | 백분율로 표시된 프로그레스 (getter) |