# Playbooks

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to Playbooks")
- [Creating a Playbook](#creating-a-playbook "Creating a playbook")
- [Steps](#steps "The steps a playbook can perform")
- [Targets](#targets "Finding things on screen")
  - [Plain Strings](#plain-strings "How a plain string is matched")
  - [Find](#find "Precise targets with Find")
- [Data and Setup](#data-and-setup "Data, setUp and tearDown")
- [Playing Other Playbooks](#playing-other-playbooks "Running a playbook from another playbook")
- [Speed](#speed "PlaybookSpeed")
- [Finger and Caption](#finger-and-caption "The finger and the step caption")
- [Registering Playbooks](#registering-playbooks "Registering playbooks with Nylo")
- [Running a Playbook](#running-a-playbook "Running a playbook")
  - [From Metro](#running-from-metro "Running from Metro")
  - [On Boot](#running-on-boot "Running on boot")
  - [From Dart](#running-from-dart "Running from Dart")
- [Reports](#reports "PlaybookReport")
- [Release Builds](#release-builds "Playbooks in release builds")
- [Testing](#testing "Testing a playbook")

<div id="introduction"></div>

## Introduction

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

A **playbook** is a class that lists the taps, typing, scrolling and page changes of a user journey. Your running app performs them on itself, and can show a finger and a caption for each step -- handy for demos, screen recordings and checking a journey end to end.

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

Import `package:nylo_framework/playbook.dart` wherever you write or register playbooks.

<div id="creating-a-playbook"></div>

## Creating a Playbook

Use Metro to create one:

``` bash
metro make:playbook sign_up
```

This creates `lib/app/playbooks/sign_up_playbook.dart` and registers it with your app. See [Make Playbook](/docs/{{ $version }}/metro#make-playbook) for the command's options.

<div id="steps"></div>

## Steps

A playbook's `steps` are built with the verbs it inherits:

| Step | Description |
|------|-------------|
| `tap(target)` | Tap a target |
| `longPress(target)` | Press and hold a target |
| `type(target, text)` | Type into a field. Clears it first unless `clear: false`; `submit: true` presses the keyboard's action button; `secret: true` hides the text in the caption and report |
| `scrollTo(target)` | Scroll until a target is on screen. Pass `within:` to scroll a specific scrollable |
| `see(target)` | Check a target is on screen, waiting for it to show up |
| `dontSee(target)` | Check a target isn't on screen, waiting for it to go |
| `waitFor(target)` | Wait for a target, up to `timeout` (10 seconds by default) |
| `waitForPage(path)` | Wait for the page at a route path or `RouteView` (e.g. `HomePage.path`) |
| `visit(path)` | Open a page by route path or `RouteView`, with optional `data:` |
| `back()` | Go back a page |
| `pause(seconds: 1)` | Hold still, e.g. to let a demo breathe |
| `step(title, action)` | Run any code with the same verbs, via a `PlaybookActor` |
| `scene(title, steps)` | Group steps under a title; their captions lead with it and their results nest in the report |
| `play(playbook)` | Run another playbook -- see [Playing Other Playbooks](#playing-other-playbooks) |

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

Steps run in order and stop at the first failure. The remaining steps are reported as skipped.

<div id="targets"></div>

## Targets

Most steps take a target: what to tap, type into or look for.

<div id="plain-strings"></div>

### Plain Strings

A plain string is matched the way a person reads the screen. It tries these in order:

1. Visible text
2. A field's hint or label
3. A tooltip
4. A semantics label
5. A `ValueKey`
6. Text that contains the string

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

A `Key`, a `Type` or an `IconData` can also be passed straight to a step.

<div id="find"></div>

### Find

Use `Find` when a plain string isn't precise enough:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Matches |
|--------|---------|
| `Find.text(text)` | Visible text |
| `Find.textContaining(text)` | Text that contains the string |
| `Find.icon(icon)` | An icon |
| `Find.type<T>()` | A widget of type `T` |
| `Find.key(key)` | A widget with the key |
| `Find.field(name)` | A `NyForm` field by name |
| `Find.input(text)` | A text field by hint, label or placeholder |
| `Find.tooltip(message)` | A tooltip |
| `Find.semantics(label)` | A semantics label |
| `Find.where(test)` | Widgets that pass your test |

Narrow any finder with `.inside(ancestor)`, `.at(index)` (counting from 0), `.first` or `.last`.

A tap is refused when something unrelated, like a dialog barrier, covers its target.

<div id="data-and-setup"></div>

## Data and Setup

`defaults` holds the data the steps start with. Read it through `data` inside `steps`. `setUp` runs before the steps and `tearDown` runs after them, even when a step failed:

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

`seed([...])` runs [Seeders](/docs/{{ $version }}/metro-live#seeders) (instances or registered names) and records them like `seed` in `metro live`.

<div id="playing-other-playbooks"></div>

## Playing Other Playbooks

`play()` runs another playbook in full, with its own `setUp` and `tearDown`. Pass an instance or the name it's registered under:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Data** merges in this order: the child's `defaults`, then the parent's `data`, then `data:` passed to `play`.
- **Speed** is inherited from the parent unless you pass `speed:`.
- **Results** nest under the step in the report.
- **A failing child** fails the step.
- **Cycles** are refused, and playbooks nest at most 8 deep.

<div id="speed"></div>

## Speed

`PlaybookSpeed` sets the pace of every wait:

| Speed | Description |
|-------|-------------|
| `PlaybookSpeed.instant` | No waits beyond what scrolling needs. Meant for tests |
| `PlaybookSpeed.fast` | Quick, but still easy to follow |
| `PlaybookSpeed.natural` | The pace of a person using the app. The default |
| `PlaybookSpeed.slow` | Slow enough to narrate over |

Scale any speed with `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // twice as slow
```

A speed passed to `run()`, `--speed` from Metro, or the speed of a playbook that plays this one wins over the playbook's own.

<div id="finger-and-caption"></div>

## Finger and Caption

The finger and the step captions are off by default. Override `showFinger` and `caption` to switch them on:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // or .top
```

`showFinger` defaults to `false` and `caption` to `PlaybookCaption.hidden`; `PlaybookCaption` is `top`, `bottom` or `hidden`. Taps and swipes happen either way. A playbook played by this one with `play()` uses its own setting, and this one's comes back when it finishes. To hide both for a run, use `--no-overlay` from Metro or `run(overlay: false)` from Dart.

Before nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0), the finger and the bottom caption were on by default. Add the overrides above to keep that look.

<div id="registering-playbooks"></div>

## Registering Playbooks

Register your playbooks so Metro and `play('name')` can find them:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` returns what's registered. You can also pass them while configuring Nylo in your `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` does this for you. It keeps the registry in `lib/bootstrap/playbooks.dart`, adds each new playbook to it, and passes `playbooks` to `nylo.configure(...)`. The registry is an empty map in release builds:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

A registered playbook runs under its key. An unregistered one runs under its `name`, or its class name in snake case (`SignUpPlaybook` is `sign_up`).

<div id="running-a-playbook"></div>

## Running a Playbook

<div id="running-from-metro"></div>

### From Metro

With your app running in debug mode:

``` bash
metro live:run playbook sign_up --speed fast
```

Inside `metro live`, type `playbook sign_up`. See [Metro Live](/docs/{{ $version }}/metro-live#playbooks) for every option. Add `--record` to save a video of the run; see [Recording](/docs/{{ $version }}/metro-live#recording-playbooks).

<div id="running-on-boot"></div>

### On Boot

Add these keys to your `.env` file to play playbooks as soon as the app is on screen:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` is a comma separated list of playbook names and `PLAYBOOK_SPEED` is one of `instant`, `fast`, `natural` or `slow`. Both apply in debug and profile builds only.

<div id="running-from-dart"></div>

### From Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` accepts `speed`, `data`, `clock`, `overlay` (default `true`) and `onEvent`. A failing step doesn't throw -- check `report.passed`. Only one playbook can run at a time; use `play()` to run one inside another.

Stop a running playbook with `Playbook.stop()`. The remaining steps are skipped but `tearDown` still runs. `Playbook.isRunning` tells you whether one is running.

<div id="reports"></div>

## Reports

`run()` returns a `PlaybookReport` with the playbook's `name`, `speed`, `elapsed` time, whether it was `stopped`, and a `steps` list of `PlaybookStepResult`s (each with a `title`, `status`, `elapsed`, `error` and nested `steps`). `report.passed` is true when every step passed, and printing a report reads like Metro's output. `report.toJson()` gives the same data as JSON.

<div id="release-builds"></div>

## Release Builds

Playbooks never run in a release build, so a journey can't drive a user's app:

- `Playbook.run()` throws an `UnsupportedError`.
- `addPlaybooks` is ignored.
- Metro's generated `lib/bootstrap/playbooks.dart` registry is empty.

`Playbook.isAvailable` tells you whether the current build can run playbooks.

<div id="testing"></div>

## Testing

Run a playbook in a widget test with `NyTesterClock`, then assert on its report with `expectPlaybookPassed`:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

See [Testing Playbooks](/docs/{{ $version }}/testing#testing-playbooks) for integration tests.
