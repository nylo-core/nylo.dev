# 플레이북
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [소개](#introduction "플레이북 소개")
- [플레이북 생성](#creating-a-playbook "플레이북 생성")
- [스텝](#steps "플레이북이 수행할 수 있는 스텝")
- [타깃](#targets "화면에서 요소 찾기")
  - [일반 문자열](#plain-strings "일반 문자열이 매칭되는 방식")
  - [Find](#find "Find로 정밀하게 타깃 지정")
- [데이터와 설정](#data-and-setup "데이터, setUp, tearDown")
- [다른 플레이북 실행](#playing-other-playbooks "플레이북에서 다른 플레이북 실행하기")
- [속도](#speed "PlaybookSpeed")
- [손가락과 캡션](#finger-and-caption "손가락과 스텝 캡션")
- [플레이북 등록](#registering-playbooks "Nylo에 플레이북 등록하기")
- [플레이북 실행](#running-a-playbook "플레이북 실행")
  - [Metro에서](#running-from-metro "Metro에서 실행")
  - [부팅 시](#running-on-boot "부팅 시 실행")
  - [Dart에서](#running-from-dart "Dart에서 실행")
- [리포트](#reports "PlaybookReport")
- [릴리스 빌드](#release-builds "릴리스 빌드에서의 플레이북")
- [테스트](#testing "플레이북 테스트")

<div id="introduction"></div>

## 소개

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**플레이북**은 사용자 여정의 탭, 입력, 스크롤, 페이지 이동을 나열한 클래스입니다. 실행 중인 앱이 이를 스스로 수행하며, 손가락과 각 스텝의 캡션을 표시할 수도 있습니다 -- 데모, 화면 녹화, 여정을 처음부터 끝까지 확인할 때 유용합니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

플레이북을 작성하거나 등록하는 곳에서는 항상 `package:nylo_framework/playbook.dart`를 import하세요.

<div id="creating-a-playbook"></div>

## 플레이북 생성

Metro를 사용해 생성합니다:

``` bash
metro make:playbook sign_up
```

이 명령은 `lib/app/playbooks/sign_up_playbook.dart`를 생성하고 앱에 등록합니다. 명령어의 옵션은 [플레이북 생성](/docs/{{ $version }}/metro#make-playbook)을 참고하세요.

<div id="steps"></div>

## 스텝

플레이북의 `steps`는 상속받은 동사들로 구성합니다:

| 스텝 | 설명 |
|------|-------------|
| `tap(target)` | 타깃을 탭 |
| `longPress(target)` | 타깃을 길게 누름 |
| `type(target, text)` | 필드에 입력. `clear: false`가 아니면 먼저 비웁니다. `submit: true`는 키보드의 액션 버튼을 누르고, `secret: true`는 캡션과 리포트에서 텍스트를 숨깁니다 |
| `scrollTo(target)` | 타깃이 화면에 나타날 때까지 스크롤. 특정 스크롤 영역을 스크롤하려면 `within:`을 전달합니다 |
| `see(target)` | 타깃이 화면에 있는지 확인 (나타날 때까지 대기) |
| `dontSee(target)` | 타깃이 화면에 없는지 확인 (사라질 때까지 대기) |
| `waitFor(target)` | `timeout`(기본 10초)까지 타깃을 대기 |
| `waitForPage(path)` | 라우트 경로 또는 `RouteView`(예: `HomePage.path`)의 페이지를 대기 |
| `visit(path)` | 라우트 경로 또는 `RouteView`로 페이지 열기 (선택적으로 `data:` 사용 가능) |
| `back()` | 한 페이지 뒤로 이동 |
| `pause(seconds: 1)` | 가만히 멈춤. 예: 데모에 여유를 주기 위해 |
| `step(title, action)` | `PlaybookActor`를 통해 같은 동사들로 임의의 코드 실행 |
| `scene(title, steps)` | 스텝을 제목으로 그룹화. 캡션이 제목으로 시작하고 결과는 리포트에서 중첩됩니다 |
| `play(playbook)` | 다른 플레이북 실행 -- [다른 플레이북 실행](#playing-other-playbooks) 참고 |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

스텝은 순서대로 실행되며 첫 번째 실패에서 멈춥니다. 나머지 스텝은 건너뜀으로 보고됩니다.

<div id="targets"></div>

## 타깃

대부분의 스텝은 타깃을 받습니다. 무엇을 탭하고, 어디에 입력하고, 무엇을 찾을지를 의미합니다.

<div id="plain-strings"></div>

### 일반 문자열

일반 문자열은 사람이 화면을 읽는 방식으로 매칭됩니다. 다음 순서로 시도합니다:

1. 화면에 표시된 텍스트
2. 필드의 힌트 또는 라벨
3. 툴팁
4. 시맨틱스 라벨
5. `ValueKey`
6. 해당 문자열을 포함하는 텍스트

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

`Key`, `Type`, `IconData`도 스텝에 바로 전달할 수 있습니다.

<div id="find"></div>

### Find

일반 문자열로 충분히 정밀하지 않을 때는 `Find`를 사용합니다:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| 파인더 | 매칭 대상 |
|--------|---------|
| `Find.text(text)` | 화면에 표시된 텍스트 |
| `Find.textContaining(text)` | 해당 문자열을 포함하는 텍스트 |
| `Find.icon(icon)` | 아이콘 |
| `Find.type<T>()` | `T` 타입의 위젯 |
| `Find.key(key)` | 해당 키를 가진 위젯 |
| `Find.field(name)` | 이름으로 지정하는 `NyForm` 필드 |
| `Find.input(text)` | 힌트, 라벨, 플레이스홀더로 지정하는 텍스트 필드 |
| `Find.tooltip(message)` | 툴팁 |
| `Find.semantics(label)` | 시맨틱스 라벨 |
| `Find.where(test)` | 테스트를 통과하는 위젯 |

어떤 파인더든 `.inside(ancestor)`, `.at(index)`(0부터 셈), `.first`, `.last`로 범위를 좁힐 수 있습니다.

다이얼로그 배리어처럼 관련 없는 요소가 타깃을 가리고 있으면 탭은 거부됩니다.

<div id="data-and-setup"></div>

## 데이터와 설정

`defaults`는 스텝이 처음 사용하는 데이터를 담습니다. `steps` 안에서는 `data`를 통해 읽습니다. `setUp`은 스텝 전에, `tearDown`은 스텝이 실패했더라도 스텝 후에 실행됩니다:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])`는 [시더](/docs/{{ $version }}/metro-live#seeders)(인스턴스 또는 등록된 이름)를 실행하고, `metro live`의 `seed`처럼 기록합니다.

<div id="playing-other-playbooks"></div>

## 다른 플레이북 실행

`play()`는 다른 플레이북을 자체 `setUp`과 `tearDown`을 포함해 전체 실행합니다. 인스턴스 또는 등록된 이름을 전달합니다:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **데이터**는 다음 순서로 병합됩니다: 자식의 `defaults`, 부모의 `data`, 그리고 `play`에 전달한 `data:`.
- **속도**는 `speed:`를 전달하지 않으면 부모로부터 상속됩니다.
- **결과**는 리포트에서 해당 스텝 아래에 중첩됩니다.
- **자식이 실패**하면 해당 스텝도 실패합니다.
- **순환**은 거부되며, 플레이북은 최대 8단계까지 중첩할 수 있습니다.

<div id="speed"></div>

## 속도

`PlaybookSpeed`는 모든 대기의 속도를 설정합니다:

| 속도 | 설명 |
|-------|-------------|
| `PlaybookSpeed.instant` | 스크롤에 필요한 것 외에는 대기하지 않습니다. 테스트용입니다 |
| `PlaybookSpeed.fast` | 빠르지만 여전히 따라가기 쉽습니다 |
| `PlaybookSpeed.natural` | 사람이 앱을 사용하는 속도. 기본값입니다 |
| `PlaybookSpeed.slow` | 내레이션을 얹을 수 있을 만큼 느립니다 |

`.times(n)`으로 어떤 속도든 배율을 조정할 수 있습니다:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // 두 배 느리게
```

`run()`에 전달한 속도, Metro의 `--speed`, 또는 이 플레이북을 실행하는 플레이북의 속도가 플레이북 자체의 속도보다 우선합니다.

<div id="finger-and-caption"></div>

## 손가락과 캡션

손가락과 스텝 캡션은 기본적으로 꺼져 있습니다. `showFinger`와 `caption`을 오버라이드하여 켭니다:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // 또는 .top
```

`showFinger`의 기본값은 `false`, `caption`의 기본값은 `PlaybookCaption.hidden`입니다. `PlaybookCaption`은 `top`, `bottom`, `hidden` 중 하나입니다. 탭과 스와이프는 어느 쪽이든 그대로 수행됩니다. 이 플레이북이 `play()`로 실행한 플레이북은 자체 설정을 사용하며, 끝나면 이 플레이북의 설정으로 돌아옵니다. 한 번의 실행에서 둘 다 숨기려면 Metro에서는 `--no-overlay`, Dart에서는 `run(overlay: false)`를 사용합니다.

nylo_support 7.33.0({{ config('app.name') }} 7.4.0) 이전에는 손가락과 하단 캡션이 기본적으로 켜져 있었습니다. 이전 모습을 유지하려면 위의 오버라이드를 추가하세요.

<div id="registering-playbooks"></div>

## 플레이북 등록

Metro와 `play('name')`이 찾을 수 있도록 플레이북을 등록합니다:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()`는 등록된 것들을 반환합니다. `AppProvider`에서 Nylo를 구성할 때 전달할 수도 있습니다:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook`이 이 작업을 대신 수행합니다. 레지스트리를 `lib/bootstrap/playbooks.dart`에 유지하고, 새 플레이북을 여기에 추가하며, `nylo.configure(...)`에 `playbooks`를 전달합니다. 레지스트리는 릴리스 빌드에서는 빈 맵입니다:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

등록된 플레이북은 해당 키 이름으로 실행됩니다. 등록되지 않은 플레이북은 `name` 또는 스네이크 케이스로 바꾼 클래스 이름(`SignUpPlaybook`은 `sign_up`)으로 실행됩니다.

<div id="running-a-playbook"></div>

## 플레이북 실행

<div id="running-from-metro"></div>

### Metro에서

앱이 디버그 모드로 실행 중인 상태에서:

``` bash
metro live:run playbook sign_up --speed fast
```

`metro live` 안에서는 `playbook sign_up`을 입력합니다. 모든 옵션은 [Metro Live](/docs/{{ $version }}/metro-live#playbooks)를 참고하세요. `--record`를 추가하면 실행 영상을 저장합니다. [녹화](/docs/{{ $version }}/metro-live#recording-playbooks)를 참고하세요.

<div id="running-on-boot"></div>

### 부팅 시

앱이 화면에 나타나자마자 플레이북을 재생하려면 `.env` 파일에 다음 키를 추가합니다:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT`는 플레이북 이름을 쉼표로 구분한 목록이고, `PLAYBOOK_SPEED`는 `instant`, `fast`, `natural`, `slow` 중 하나입니다. 둘 다 디버그 및 프로파일 빌드에서만 적용됩니다.

<div id="running-from-dart"></div>

### Dart에서

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()`은 `speed`, `data`, `clock`, `overlay`(기본값 `true`), `onEvent`를 받습니다. 스텝이 실패해도 예외를 던지지 않습니다 -- `report.passed`를 확인하세요. 한 번에 하나의 플레이북만 실행할 수 있으며, 플레이북 안에서 다른 플레이북을 실행하려면 `play()`를 사용합니다.

실행 중인 플레이북은 `Playbook.stop()`으로 중지합니다. 나머지 스텝은 건너뛰지만 `tearDown`은 계속 실행됩니다. `Playbook.isRunning`으로 실행 중인지 확인할 수 있습니다.

<div id="reports"></div>

## 리포트

`run()`은 `PlaybookReport`를 반환하며, 여기에는 플레이북의 `name`, `speed`, 경과 시간 `elapsed`, `stopped` 여부, 그리고 `PlaybookStepResult`의 `steps` 리스트(각각 `title`, `status`, `elapsed`, `error`, 중첩된 `steps`를 가짐)가 포함됩니다. `report.passed`는 모든 스텝이 통과하면 true이며, 리포트를 출력하면 Metro의 출력처럼 읽힙니다. `report.toJson()`은 같은 데이터를 JSON으로 제공합니다.

<div id="release-builds"></div>

## 릴리스 빌드

플레이북은 릴리스 빌드에서 절대 실행되지 않으므로, 여정이 사용자의 앱을 조작할 수 없습니다:

- `Playbook.run()`은 `UnsupportedError`를 던집니다.
- `addPlaybooks`는 무시됩니다.
- Metro가 생성한 `lib/bootstrap/playbooks.dart` 레지스트리는 비어 있습니다.

`Playbook.isAvailable`로 현재 빌드에서 플레이북을 실행할 수 있는지 확인할 수 있습니다.

<div id="testing"></div>

## 테스트

위젯 테스트에서 `NyTesterClock`으로 플레이북을 실행한 다음, `expectPlaybookPassed`로 리포트를 검증합니다:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

통합 테스트는 [플레이북 테스트](/docs/{{ $version }}/testing#testing-playbooks)를 참고하세요.
