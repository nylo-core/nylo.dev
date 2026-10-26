# 플레이북
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [소개](#introduction "플레이북 소개")
- [플레이북 생성](#creating-a-playbook "플레이북 생성")
- [플레이북 녹화](#recording-a-playbook "실행 중인 앱에서 플레이북 녹화")
- [스텝](#steps "플레이북이 수행할 수 있는 스텝")
- [타깃](#targets "화면에서 요소 찾기")
  - [일반 문자열](#plain-strings "일반 문자열이 매칭되는 방식")
  - [Find](#find "Find로 정밀하게 타깃 지정")
- [데이터와 설정](#data-and-setup "데이터, setUp, tearDown")
- [다른 플레이북 실행](#playing-other-playbooks "플레이북에서 다른 플레이북 실행하기")
- [속도](#speed "PlaybookSpeed")
- [손가락과 캡션](#finger-and-caption "손가락과 스텝 캡션")
- [스크린샷](#screenshots "플레이북 중 스크린샷 캡처")
  - [로케일과 테마](#screenshots-locales-and-themes "모든 로케일과 테마에서 화면 캡처")
  - [코드에서 수집](#collecting-screenshots "onScreenshot으로 스크린샷 수집")
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

라우터가 추가한 모든 페이지에서 시작하려면 `--from-routes`를 전달하세요:


``` bash
metro make:playbook tour --from-routes
```

Metro는 각 페이지에 대해 `visit()`와 [`screenshot()`](#screenshots)을 작성하므로, 한 번 실행하면 앱 전체를 촬영합니다.

<div id="recording-a-playbook"></div>

## 플레이북 녹화

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

스텝을 직접 작성하는 대신 녹화할 수 있습니다. 앱이 디버그 모드로 실행 중인 상태에서 `metro live`를 열고 다음을 입력하세요:

``` plaintext
› record sign_up
```

그런 다음 앱을 사용하세요. 탭, 길게 누르기, 입력한 필드, 스크롤, 페이지 전환이 발생하는 순간 각각 스텝으로 기록되며 진행하는 동안 출력됩니다:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Enter를 눌러 종료하세요. 플레이북은 `make:playbook`이 만드는 것과 같은 구조로 `lib/app/playbooks/sign_up_playbook.dart`에 작성되고 등록되므로, 앱을 재시작하자마자 재생됩니다. `metro make:playbook sign_up --record`는 터미널에서 같은 작업을 수행합니다: `metro live`를 열고 곧바로 녹화를 시작합니다.

녹화 중에는 키를 입력하고 Enter를 누르세요:

| 키 | 설명 |
|-----|-------------|
| `a` | 다음 탭을 탭 대신 `see(...)`로 기록 |
| `d` | 다음 탭을 `dontSee(...)`로 기록 |
| `s <title>` | 해당 제목의 `scene`을 시작하며, 이후의 스텝이 그 안에 중첩됩니다 |
| `u` | 마지막 스텝 제거 |
| `p` | 녹화 일시정지(예: 실제 자격 증명으로 로그인할 때). `p`를 다시 누르면 재개 |
| Enter | 종료하고 플레이북 작성 |
| Ctrl+C | 녹화 폐기 |

타깃은 플레이어가 읽는 방식대로 이름이 지정되며 보이는 텍스트가 우선하고, 각 이름은 기록되기 전에 실행 중인 앱에서 확인되므로 녹화된 스텝은 다시 재생됩니다. 일반 문자열이 둘 이상의 위젯과 일치하면 스텝은 `Find`로 범위를 좁힙니다: `tap(Find.text('Add').at(1))`. 텍스트나 툴팁이 없는 아이콘 버튼은 `tap(Find.icon(Icons.add))`가 됩니다. 이름으로 지정할 수 없는 위젯은 생성된 파일과 줄을 알려주는 `// TODO` 주석과 함께 `tap(Find.type<GestureDetector>().at(3))`로 기록되며, 녹화가 끝나면 Metro가 이를 나열합니다. 해당 위젯에 `Key`를 지정한 다음 그 키를 대신 사용하세요.

- **입력**은 `data[...]` 플레이스홀더를 읽는 `type` 스텝이 되며, 입력한 값은 `defaults`에 들어갑니다. 키보드의 액션 키로 남겨둔 필드에는 `submit: true`가 붙습니다. 가려진 필드에는 `secret: true`가 붙고, 값은 `defaults`에 들어가며 커밋하기 전에 변경하라는 경고가 표시됩니다. 값을 입력한 그대로 기록하려면 `--no-data`를 전달하세요.
- 탭 이후의 **페이지 전환**은 페이지 중 하나가 해당 라우트를 선언한 경우 `waitForPage(SignUpPage.path)`가 되고, 그렇지 않으면 `waitForPage('/sign-up')`이 됩니다. 뒤로 가기는 `back()`이 됩니다.
- **스크롤**은 다음에 탭하는 대상에 대한 `scrollTo(...)`로 기록되며, 스크롤로 인해 해당 대상이 트리에 나타난 경우에만 기록됩니다. 그 외의 모든 것은 플레이어가 알아서 드러냅니다.
- 스크롤이 아닌 **스와이프** -- `Dismissible`, `PageView`, `Slider` -- 는 아직 플레이북 동사가 없으므로 시작점과 끝점이 포함된 `// TODO` 주석으로 남습니다.

녹화에는 디버그 빌드가 필요하며 시뮬레이터, 에뮬레이터, 실제 기기 모두에서 동작합니다. 플레이북 자체의 터치는 기록되지 않습니다. 명령어의 옵션은 [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey)를 참고하세요.

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
| `screenshot(name)` | 화면 캡처 -- [스크린샷](#screenshots) 참고 |
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
| `Find.icon(icon, label:)` | 아이콘. `label:`로 스텝 캡션에 표시할 이름을 붙입니다 |
| `Find.type<T>()` | `T` 타입의 위젯 |
| `Find.key(key)` | 해당 키를 가진 위젯 |
| `Find.field(name)` | 이름으로 지정하는 `NyForm` 필드 |
| `Find.input(text)` | 힌트, 라벨, 플레이스홀더로 지정하는 텍스트 필드 |
| `Find.tooltip(message)` | 툴팁 |
| `Find.semantics(label)` | 시맨틱스 라벨 |
| `Find.where(test)` | 테스트를 통과하는 위젯 |

아이콘에 `label`을 지정하면 스텝 캡션이 `Tap icon f37d` 대신 `Tap the bag icon`으로 표시됩니다:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

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

<div id="screenshots"></div>

## 스크린샷

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

보관할 가치가 있는 화면마다 `screenshot` 스텝을 추가하세요:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>`은 플래그 없이도 각각을 `screenshots/<playbook>/<name>.png`로 저장합니다. 코드에서 실행하면 [`onScreenshot`](#collecting-screenshots)으로 수집하며, `PLAYBOOK_ON_BOOT` 같은 그 외의 실행은 화면이 안정될 때까지 기다리기만 합니다. 이름은 문자 또는 숫자로 시작하며 문자, 숫자, `-`, `_`, `.`을 사용합니다.

| 매개변수 | 설명 |
|-----------|-------------|
| `name` | 스크린샷이 저장되는 이름 |
| `locales` | 지정한 각 언어 코드에서 한 번씩 캡처. `Screenshot.allLocales`는 앱에 번들된 모든 언어입니다 |
| `themes` | 지정한 각 테마 id에서 한 번씩 캡처. `Screenshot.allThemes`는 등록된 모든 테마입니다 |
| `caption` | 실행이 스크린샷에 프레임을 그릴 때 화면 위에 표시되는 텍스트, 또는 번역 키 |
| `native` | iOS 시뮬레이터와 Android에서 Metro로 캡처 (기본값 `true`) |

iOS 시뮬레이터와 Android에서는 Metro가 기기의 전체 화면을 캡처하므로 상태 표시줄과 키보드가 포함되며, 캡처하는 동안 손가락과 캡션은 숨겨집니다. 실행에 `--clean-status-bar`를 추가하면 9:41, 신호와 배터리 가득 찬 상태로 표시됩니다. 그 외의 환경과 `--store`, `--frame` 실행에서는 앱이 자체 화면을 캡처합니다. 항상 앱의 캡처를 사용하려면 `native: false`를 전달하세요:

``` dart
screenshot('checkout', native: false),
```

모든 스텝의 화면을 대신 저장하려면 `metro live:run playbook <name> --screenshots`를 실행하세요. App Store와 Google Play에 맞게 스크린샷 크기를 조정하는 옵션은 [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots)를, 그 뒤에 있는 `Screenshot` 클래스는 [스크린샷](/docs/{{ $version }}/screenshots)을 참고하세요.

<div id="screenshots-locales-and-themes"></div>

### 로케일과 테마

`screenshot` 스텝에 `locales`, `themes` 또는 둘 다를 지정하면 같은 화면을 각각에서 한 번씩 캡처합니다:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

앱은 페이지와 상태를 유지한 채 제자리에서 전환되며, 다음 스텝 전에 다시 원래대로 돌아갑니다. 각 캡처는 로케일과 테마의 이름을 딴 폴더에 저장됩니다:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

플레이북 전체를 모든 언어와 테마에서 재생하려면 `metro live:run playbook`에 `--locales`와 `--themes`를 전달하세요.

<div id="collecting-screenshots"></div>

### 코드에서 수집

`run()`은 `onScreenshot`을 받아 실행의 스크린샷을 `PlaybookShot`으로 수신하며, 테스트나 자체 도구에 유용합니다:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots`는 캡처할 화면을 선택합니다:

| 모드 | 캡처 대상 |
|------|----------|
| `PlaybookScreenshots.off` | 없음 |
| `PlaybookScreenshots.failures` | 실패한 스텝의 화면만 |
| `PlaybookScreenshots.marked` | `screenshot()` 스텝과 실패한 스텝 |
| `PlaybookScreenshots.auto` | `screenshot()` 스텝이 있는 플레이북은 그 스텝들, 없는 플레이북은 모든 스텝. 실패한 스텝도 포함. 기본값 |
| `PlaybookScreenshots.all` | 모든 스텝, `screenshot()` 스텝, 실패한 스텝 |

`pixelRatio`는 기기 대비 스크린샷 크기를 정합니다(기본값 `1.0`). 각 `PlaybookShot`에는 [저장](/docs/{{ $version }}/screenshots#saving-a-screenshot)하거나 `bytes`를 읽을 수 있는 `screenshot`, `kind`, 그리고 촬영 시 사용된 `name`, `locale`, `theme`이 있습니다.

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

`metro live` 안에서는 `playbook sign_up`을 입력합니다. 모든 옵션은 [Metro Live](/docs/{{ $version }}/metro-live#playbooks)를 참고하세요. `--record`를 추가하면 실행 영상을 저장합니다. [녹화](/docs/{{ $version }}/metro-live#recording-playbooks)를 참고하세요. 실행이 20초 후 대기를 멈춘다면 앱이 `flutter run --no-dds`로 시작되었을 가능성이 큽니다. [Metro Live](/docs/{{ $version }}/metro-live#playbooks)를 참고하세요.

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

`run()`은 `speed`, `data`, `clock`, `overlay`(기본값 `true`), `onEvent`를 받으며, [스크린샷 수집](#collecting-screenshots)을 위한 `onScreenshot`, `screenshots`, `pixelRatio`도 받습니다. 스텝이 실패해도 예외를 던지지 않습니다 -- `report.passed`를 확인하세요. 한 번에 하나의 플레이북만 실행할 수 있으며, 플레이북 안에서 다른 플레이북을 실행하려면 `play()`를 사용합니다.

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
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

통합 테스트는 [플레이북 테스트](/docs/{{ $version }}/testing#testing-playbooks)를 참고하세요.
