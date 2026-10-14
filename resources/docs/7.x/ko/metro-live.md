# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [소개](#introduction "Nylo Live 소개")
- metro live 셸
  - [공통 옵션](#shared-options "모든 라이브 명령어가 지원하는 옵션")
  - [탭 완성과 기록](#tab-completion-and-history "탭 완성과 기록")
  - [스크립트 실행](#running-a-script ".live 스크립트 실행")
- [live:devices](#live-devices "실행 중인 앱 목록 표시")
- [live:status](#live-status "앱 상태 표시")
- [live:run](#live-run "내장 라이브 명령어 실행")
- [플레이북](#playbooks "Metro에서 플레이북 재생")
- 라이브 명령어
  - [라이브 명령어 생성](#creating-a-live-command "라이브 명령어 생성")
  - [라이브 명령어 실행](#running-a-live-command "라이브 명령어 실행")
- 시더
  - [시더 생성](#creating-a-seeder "시더 생성")
  - [시딩과 롤백](#seeding-and-rolling-back "시딩과 롤백")
- 스토리지 스냅샷
  - [스냅샷 내보내기](#exporting-a-snapshot "스냅샷 내보내기")
  - [스냅샷 불러오기](#loading-a-snapshot "스냅샷 불러오기")
- [페이지 액션](#page-actions "외부에서 페이지 호출하기")

<div id="introduction"></div>

## 소개

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live**는 Dart VM 서비스를 통해 디버그(또는 프로필) 모드로 실행 중인 앱에 Metro가 접근할 수 있게 해줍니다. 별도의 설정 없이 Dart Tooling Daemon을 통해 앱을 찾으며, 현재 프로젝트에서 빌드된 앱에만 연결합니다.

Nylo Live는 디버그 빌드와 프로필 빌드에서 기본적으로 활성화되어 있으며, 릴리스 빌드에서는 아무것도 등록되지 않습니다. 앱을 읽기만 하는 명령어(`live:status`, `live:run data`, `live:run routes` 등)는 프로필 빌드에서도 동작합니다. 앱을 변경하는 명령어(`route`, `storage`, `auth`, 시더 등)에는 디버그 빌드가 필요합니다.

다른 무언가가 실행되기 전에, 프로바이더에서 직접 꺼둘 수도 있습니다:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## metro live 셸

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

실행 중인 앱에 연결된 셸을 엽니다:

``` bash
metro live
```

Nylo Live는 프로젝트의 실행 중인 앱을 찾아 연결합니다 -- 실행 중인 앱이 여러 개면 어떤 것에 연결할지 선택하라는 메시지가 표시됩니다. 연결되면 프롬프트에 현재 디바이스와 화면에 표시된 라우트가 나타납니다:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

라이브 명령어는 셸 안에서 `metro live:` 접두사를 생략합니다 -- `metro live:status` 대신 `status`, `metro live:run route /profile` 대신 `route /profile`처럼 입력합니다. 일부 명령어는 셸 안에서만 실행됩니다: `seed`, `seed:rollback`, `export`, `reload`, `restart`입니다. `help`를 입력하면 모든 명령어가 나열되고, `help <command>`로 특정 명령어의 옵션을 확인할 수 있습니다. `exit`(또는 Ctrl+D)를 입력하면 셸을 종료합니다.

연결되어 있는 동안 앱이 재시작되면, 셸은 자동으로 다시 연결됩니다.

<div id="shared-options"></div>

### 공통 옵션

모든 라이브 명령어는 -- 셸 안에서든 `metro live:*`로든 -- 동일한 옵션을 지원합니다:

| 옵션 | 설명 |
|--------|-------------|
| `-d, --device <#\|name>` | `metro live:devices`의 번호 또는 디바이스 이름으로 앱을 지정 |
| `--all` | 이 프로젝트의 실행 중인 모든 앱에서 실행 |
| `--json` | 기계가 읽을 수 있는 JSON으로 출력 |
| `--uri` | 앱을 탐색하는 대신 이 VM 서비스 주소를 사용 |
| `--timeout` | 탐색 시 각 앱을 기다리는 시간(초 단위, 기본값 `3`) |

`@path` 형식으로 작성된 옵션 값은 해당 파일에서 읽어옵니다 -- 큰 `--data` 페이로드에 유용합니다:

``` bash
metro live:run route /profile --data @user.json
```

값이 실제로 `@`로 시작하는 경우 `@@`로 시작하세요.

<div id="tab-completion-and-history"></div>

### 탭 완성과 기록

셸 안에서 Tab 키를 누르면 연결된 앱을 기준으로 명령어 이름, 옵션, 라우트, 스토리지와 Backpack 키, 시더 이름, 플레이북 이름이 완성됩니다. 기록은 `.dart_tool/nylo/live_history`에 저장되며 세션 간에도 유지됩니다.

<div id="running-a-script"></div>

### 스크립트 실행

파일을 `metro live`에 파이프로 전달하면 한 줄당 하나의 명령어로 스크립트를 실행하며, 처음 실패하는 명령어에서 멈춥니다:

``` bash
metro live < seeds/demo.live
```

빈 줄과 `#`으로 시작하는 줄은 건너뜁니다.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

이 프로젝트의 실행 중인 앱을 `-d`에 사용할 번호와 함께 나열합니다:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

앱, 디바이스, 빌드 모드, 현재 라우트와 스택, 로케일, 테마, 사용자 로그인 여부를 표시합니다:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

앱을 살펴보거나 제어하는 내장 명령어를 실행합니다:

``` bash
metro live:run <command> [arguments]
```

| 명령어 | 설명 |
|---------|-------------|
| `data` | 화면에 표시된 페이지의 데이터와 필드를 표시 |
| `routes` | 등록된 라우트 목록 표시 |
| `route` | 페이지 열기(선택적으로 `--data` 사용 가능) |
| `back` | 한 페이지 뒤로 가기, 또는 특정 라우트로 돌아가기 |
| `deeplink` | 딥링크 열기, 또는 설정 방법 표시 |
| `storage` | 로컬 스토리지 목록 표시, 또는 값 하나를 표시·저장·삭제 |
| `storage:clear` | 로컬 스토리지 초기화(선택적으로 `--keep` 사용 가능) |
| `backpack` | Backpack 값 목록 표시, 또는 값 하나를 표시·저장·삭제 |
| `auth` | 로그인한 사용자 표시, 또는 로그인·로그아웃 |
| `locale` | 앱의 언어 전환 |
| `theme` | 앱의 테마 전환 |
| `state` | `updateState`로 state에 데이터 전송 |
| `event` | 자신이 정의한 이벤트 중 하나를 발생 |
| `toast` | 토스트 알림 표시 |
| `playbook` | [플레이북](/docs/{{ $version }}/playbooks) 재생, 또는 목록 표시 |

`metro live` 안에서는 `live:run` 부분을 생략합니다 -- `metro live:run route /profile` 대신 `route /profile`처럼 입력합니다:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data`는 실행 중인 앱에서 직접, 페이지의 state가 선언한 필드도 읽어옵니다 -- Flutter에는 리플렉션이 없으므로, 이는 실제로 화면에 표시되어 있는 state에서만 동작합니다:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## 플레이북

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

실행 중인 앱에서 하나 이상의 [플레이북](/docs/{{ $version }}/playbooks)을 재생합니다. 각 스텝은 실행되는 대로 출력됩니다:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

여러 플레이북 이름을 지정하면 연달아 재생됩니다. 이름을 지정하지 않거나 `--list`를 사용하면 앱의 플레이북 목록을 표시합니다.

| 옵션 | 설명 |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | 각 플레이북 자체의 속도 대신 이 속도로 재생 |
| `--data <json>` | 실행 중인 모든 플레이북이 `data`에서 읽는 JSON 객체. 기본값보다 우선합니다 |
| `--no-overlay` | 카운트다운과, 이를 표시하는 플레이북의 손가락 및 캡션을 숨김 |
| `--loop` | Ctrl+C를 누를 때까지 플레이북을 계속 반복 재생 |
| `--record[=<file>]` | 실행 전후를 포함해 기기의 화면을 녹화합니다. `build/playbooks/<playbooks>-<device>-<time>.mp4`에 저장되며, `--record=demo.mp4`(또는 `.gif`)로 프로젝트 기준 상대 경로의 파일을 지정할 수 있습니다. ffmpeg가 필요합니다 |
| `--no-captions` | 녹화의 `.vtt` 캡션 파일과 챕터를 생성하지 않음 |
| `--clean-status-bar` | 플레이북이 재생되는 동안 상태 표시줄을 9:41, 신호와 배터리 가득 찬 상태로 표시하고 끝나면 원래대로 되돌림. `--record` 없이도 동작합니다 |
| `--countdown <seconds>` | 첫 번째 스텝 전에 화면에서 0~10초 카운트다운 |
| `--list` | 플레이북을 재생하는 대신 앱의 플레이북 목록 표시 |

Ctrl+C는 앱에 중지를 요청합니다. 나머지 스텝은 건너뛰지만 `tearDown`은 계속 실행됩니다. Ctrl+C를 한 번 더 누르면 대기를 중단합니다.

`metro live` 셸 안에서는 명령어가 `playbook <name>`이며, Tab 키로 플레이북 이름이 완성됩니다:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### 녹화

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

`--record`를 추가하면 실행 영상을 저장합니다:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

`metro live` 셸 안에서는 `playbook sign_up --record`를 입력합니다.

녹화에는 ffmpeg가 필요합니다(예: `brew install ffmpeg`). Metro는 무엇이든 재생하기 전에 설치 여부를 확인합니다.

- **iOS 시뮬레이터** -- `xcrun simctl`로 녹화합니다.
- **Android 에뮬레이터 또는 기기** -- 3분 후 중지되는 `adb screenrecord`, 또는 설치되어 있으면 `scrcpy`(시간 제한 없음, Android 11 이상에서는 소리도 녹음)로 녹화합니다. 여러 Android 기기가 연결되어 있고 Metro가 어느 기기에서 앱이 실행 중인지 알 수 없다면 `ANDROID_SERIAL`을 설정하세요.
- **실제 iPhone** -- 명령줄에서는 녹화할 수 없습니다. QuickTime 또는 제어 센터로 녹화하고, 시작할 시간을 확보하려면 `--countdown 3`을 사용하세요.

결과물은 실행 시간과 정확히 같은 길이의 `.mp4`(H.264, 일정한 60 fps) 또는 너비 480px, 15 fps의 `.gif`입니다. 각 스텝은 `.mp4` 옆의 `.vtt` 파일에 캡션으로, `.mp4` 안에는 챕터로 만들어집니다. 이들은 플레이북이 화면에 캡션을 표시하는지와 상관없이 실행의 스텝에서 생성됩니다. 건너뛰려면 `--no-captions`를 전달하세요.

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

실패했거나 중지된 플레이북도 저장되므로, 녹화본은 곧바로 버그 리포트로 쓸 수 있습니다. Ctrl+C는 플레이북을 중지하며(`tearDown`은 계속 실행됩니다) 녹화도 저장됩니다.

`--clean-status-bar`는 시뮬레이터에서 `simctl status_bar`로 이미 설정해 둔 상태 표시줄을 그대로 유지합니다. Android에서는 데모 모드를 사용하고 끝나면 끕니다. 여러 앱(`--all`)의 경우 각 기기마다 기기 이름이 포함된 별도의 파일이 만들어집니다.

플레이북은 기본적으로 손가락과 캡션을 숨깁니다. 데모 영상에서는 플레이북에 `showFinger`와 `caption`을 설정하세요 -- [손가락과 캡션](/docs/{{ $version }}/playbooks#finger-and-caption)을 참고하세요.

<div id="playbook-live-commands"></div>

### 라이브 명령어 레퍼런스

그 뒤에 있는 명령어는 `playbooks.list`, `playbooks.run`, `playbooks.stop`입니다. 실행은 ID를 즉시 반환하고 각 스텝을 `nylo.playbook` 이벤트로 스트리밍하므로, 명령어의 타임아웃보다 오래 실행될 수 있습니다. `live:status`는 앱의 플레이북 목록을 표시합니다. 플레이북은 Nylo Live 프로토콜 버전 9에서 도입되었습니다. 녹화 지원 -- `playbooks.run`의 `countdown`(매초 `countdown` 이벤트 전송)과 `status`의 `simulator`(iOS 시뮬레이터 UDID) -- 은 프로토콜 버전 10에서 도입되었습니다.

<div id="live-commands"></div>

## 라이브 명령어

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

위의 내장 명령어와 함께, 앱 안에서 실행할 자신만의 명령어를 등록할 수 있습니다.

<div id="creating-a-live-command"></div>

### 라이브 명령어 생성

``` bash
metro make:command seed_cart --live
```

| 옵션 | 설명 |
|--------|-------------|
| `--category`, `-c` | 명령어의 카테고리(기본값: `app`) |
| `--description` | 명령어의 `--help`에 표시되는 한 줄 설명 |
| `--force`, `-f` | 이미 존재하더라도 파일을 생성 |

이렇게 하면 `lib/app/commands/`에 `LiveCommand`가 생성되고, `commands.json`에 `"type": "live"`로 표시되며, `lib/bootstrap/live_commands.dart`에 등록되고, `app_provider.dart`의 `nylo.configure(...)`에 `liveCommands: liveCommands`가 연결됩니다:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand`는 Metro 자체 명령어와 `builder`/`handle` 형태를 공유하지만, `handle`은 **앱 내부에서** 실행되므로 `NyStorage`, `routeTo`, `Auth`와 여러분의 모델을 사용할 수 있습니다. `package:nylo_framework/live.dart`는 `LiveCommand`를 `Seeder`, `StorageSnapshot`, `LiveException`, `LiveOutput`과 함께 내보냅니다.

<div id="running-a-live-command"></div>

### 라이브 명령어 실행

새로 만든 라이브 명령어는 실행 중인 앱에서 실행되기 전에 핫 리스타트가 필요합니다. 그런 다음 터미널에서:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## 시더

<!-- uncertain: "Seeder" is new Nylo-specific terminology -- translated as 시더 (transliteration), following the common ko Laravel-docs convention for "Seeder"; the class name itself stays verbatim as `Seeder` -->

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`Seeder`는 실행 중인 앱을 알려진 상태 -- 로그인 완료, 온보딩 완료, 샘플 레코드 등 -- 로 만들고, 다시 원래대로 되돌립니다.

<div id="creating-a-seeder"></div>

### 시더 생성

``` bash
metro make:seeder demo_user
```

| 옵션 | 설명 |
|--------|-------------|
| `--description` | `seed`가 시더 목록을 표시할 때 나타나는 한 줄 설명 |
| `--force`, `-f` | 이미 존재하더라도 파일을 생성 |

이렇게 하면 `lib/app/seeders/`에 `Seeder`가 생성되고, `lib/bootstrap/seeders.dart`에 등록되며, `nylo.configure(...)`에 `seeders: seeders`가 연결됩니다:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

`up()`이 변경하는 모든 스토리지와 Backpack 값은 자동으로 기록되므로, 기본 `down()`(`restore()`)은 핫 리스타트를 거치더라도 각각을 역순으로 정확히 원래대로 되돌립니다. 시더 내부에서 `seed(otherSeeders)`를 호출하면 다른 시더를 그 일부로 실행할 수 있습니다 -- 그 변경 사항도 함께 기록되고 롤백됩니다.

<div id="seeding-and-rolling-back"></div>

### 시딩과 롤백

시딩, 롤백, 내보내기는 `metro live` 셸 안에서만 실행됩니다:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed`만 단독으로 실행하면 등록된 시더 목록이 표시됩니다. `--fresh`는 시딩하기 전에 스토리지와 Backpack을 초기화하고, `--restart`는 이후 앱을 핫 리스타트하여 시작할 때 이미 시딩된 상태가 되도록 합니다:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## 스토리지 스냅샷

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export`는 앱이 현재 스토리지와 Backpack에 보관하고 있는 모든 것을 -- 시더로, 또는 이동 가능한 JSON 파일로 -- 저장하고, `seed <file>`은 이를 다시 불러옵니다.

<div id="exporting-a-snapshot"></div>

### 스냅샷 내보내기

이름 없이 실행하면 무엇이 캡처될지 미리 볼 수 있습니다:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

이름을 지정하면 `lib/app/seeders/`에 시더로 저장됩니다(`make:seeder`와 동일하지만 캡처된 값이 이미 채워져 있습니다):

``` plaintext
› export pro_user
```

또는 대신 파일로 저장할 수도 있습니다:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| 옵션 | 설명 |
|--------|-------------|
| `--to <path>` | 시더 대신 이 JSON 파일에 스냅샷을 저장 |
| `--description` | 시더에 대한 한 줄 설명 |
| `--only <keys>` | 내보낼 키를 쉼표로 구분한 목록. `*`는 아무 문자열이나 매칭(예: `--only SK_USER,onboarding_*`) |
| `--except <keys>` | 제외할 키를 쉼표로 구분한 목록(예: `--except 'cache_*'`) |
| `--backpack` / `--no-backpack` | Backpack 값 포함 여부(기본값: 포함) |
| `--force`, `-f` | 이미 존재하는 시더나 파일을 교체 |

`Model` 또는 등록된 디코더가 있는 모든 클래스는 클래스 이름으로 태그가 지정되어, 단순한 map이 아니라 자체 디코더를 통해 복원됩니다. 스냅샷에 인증 정보처럼 보이는 키가 포함된 경우, 또는 `--except`를 사용하는 것이 좋을 만큼 스냅샷이 큰 경우 `export`가 경고를 표시합니다.

<div id="loading-a-snapshot"></div>

### 스냅샷 불러오기

``` plaintext
› seed snapshots/pro_user.json
```

파일을 불러오는 것도 시더와 동일한 방식으로 기록되므로, `seed:rollback <name>`(파일 이름, 또는 `--as`로 지정한 이름)으로 이 역시 되돌릴 수 있습니다. 코드에서는 시더가 `importSnapshot`으로 캡처된 스냅샷을 적용할 수 있습니다:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export`는 정확히 이 형식으로 저장하므로, `--to`로 저장한 파일을 그대로 시더의 `snapshot` 필드에 붙여넣을 수 있습니다.

<div id="page-actions"></div>

## 페이지 액션

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

페이지의 `path.actions`를 사용하면, `stateAction(...)` 호출을 직접 만들지 않아도 외부에서 해당 페이지를 호출할 수 있는 `PageStateActions`를 얻을 수 있습니다:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

내장 액션의 전체 목록과 자신만의 타입이 지정된 액션을 선언하는 방법은 [페이지의 액션 호출하기](/docs/{{ $version }}/state-management#page-actions-shortcut)를 참고하세요. Nylo Live도 동일한 액션을 읽고 사용합니다: `metro live:run data`는 화면에 있는 페이지가 `actions` 아래에 공개하는 항목을 나열하고, `metro live:run state`는 페이지 데이터를 해당 state 이름으로 직접 전송합니다.
