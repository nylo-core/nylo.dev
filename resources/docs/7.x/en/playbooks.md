# Playbooks

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to Playbooks")
- [Creating a Playbook](#creating-a-playbook "Creating a playbook")
- [Recording a Playbook](#recording-a-playbook "Recording a playbook from the running app")
- [Steps](#steps "The steps a playbook can perform")
- [Targets](#targets "Finding things on screen")
  - [Plain Strings](#plain-strings "How a plain string is matched")
  - [Find](#find "Precise targets with Find")
- [Data and Setup](#data-and-setup "Data, setUp and tearDown")
- [Playing Other Playbooks](#playing-other-playbooks "Running a playbook from another playbook")
- [Speed](#speed "PlaybookSpeed")
- [Finger and Caption](#finger-and-caption "The finger and the step caption")
- [Screenshots](#screenshots "Capturing screenshots during a playbook")
  - [Locales and Themes](#screenshots-locales-and-themes "Capturing a screen in every locale and theme")
  - [Collecting from Code](#collecting-screenshots "Collecting screenshots with onScreenshot")
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

To start from every page your router adds, pass `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

Metro writes a `visit()` and a [`screenshot()`](#screenshots) for each page, so one run photographs your whole app.

<div id="recording-a-playbook"></div>

## Recording a Playbook

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Instead of writing the steps, record them. With your app running in debug mode, open `metro live` and type:

``` plaintext
› record sign_up
```

Then use the app. Each tap, long press, typed field, scroll and page change is written as a step the moment it happens, and printed as you go:

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

Press Enter to finish. The playbook is written to `lib/app/playbooks/sign_up_playbook.dart`, laid out like one `make:playbook` creates, and registered, so it plays as soon as the app restarts. `metro make:playbook sign_up --record` does the same from the terminal: it opens `metro live` and starts recording straight away.

While recording, type a key and press Enter:

| Key | Description |
|-----|-------------|
| `a` | The next tap is written as `see(...)` instead of a tap |
| `d` | The next tap is written as `dontSee(...)` |
| `s <title>` | Starts a `scene` with that title; the steps that follow nest inside it |
| `u` | Removes the last step |
| `p` | Pauses recording, e.g. to sign in with real credentials; `p` again resumes |
| Enter | Finishes and writes the playbook |
| Ctrl+C | Discards the recording |

Targets are named the way the player reads them, visible text first, and each name is checked against the running app before it's written, so a recorded step replays. When a plain string matches more than one widget, the step narrows it with `Find`: `tap(Find.text('Add').at(1))`. An icon button with no text or tooltip becomes `tap(Find.icon(Icons.add))`. A widget nothing names is written as `tap(Find.type<GestureDetector>().at(3))` under a `// TODO` comment with the file and line it was created in, and Metro lists it when the recording ends. Give it a `Key`, then use the key instead.

- **Typing** becomes a `type` step reading a `data[...]` placeholder, with the typed value in `defaults`. A field left with the keyboard's action key gets `submit: true`. An obscured field gets `secret: true`, its value in `defaults` and a warning to change it before you commit. Pass `--no-data` to write values as they were typed.
- **Page changes** after a tap become `waitForPage(SignUpPage.path)` when one of your pages declares the route, or `waitForPage('/sign-up')` otherwise. Going back becomes `back()`.
- **Scrolling** is written as `scrollTo(...)` on the thing you tap next, and only when scrolling brought it into the tree. The player reveals everything else on its own.
- **Swipes that aren't scrolls** -- a `Dismissible`, a `PageView`, a `Slider` -- have no playbook verb yet, so they're left as a `// TODO` comment with the start and end points.

Recording needs a debug build and works on simulators, emulators and devices alike. A playbook's own touches are never recorded. See [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) for the command's options.

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
| `screenshot(name)` | Capture the screen -- see [Screenshots](#screenshots) |
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
| `Find.icon(icon, label:)` | An icon. `label:` names it in step captions |
| `Find.type<T>()` | A widget of type `T` |
| `Find.key(key)` | A widget with the key |
| `Find.field(name)` | A `NyForm` field by name |
| `Find.input(text)` | A text field by hint, label or placeholder |
| `Find.tooltip(message)` | A tooltip |
| `Find.semantics(label)` | A semantics label |
| `Find.where(test)` | Widgets that pass your test |

Give an icon a `label` and its step captions read `Tap the bag icon` instead of `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

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

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Add a `screenshot` step wherever the screen is worth keeping:

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

`metro live:run playbook <name>` saves each one as `screenshots/<playbook>/<name>.png`, with no flag needed. A run from code collects them with [`onScreenshot`](#collecting-screenshots), and any other run, such as `PLAYBOOK_ON_BOOT`, just waits for the screen to settle. A name starts with a letter or digit and uses letters, digits, `-`, `_` and `.`.

| Parameter | Description |
|-----------|-------------|
| `name` | The name the screenshot is saved under |
| `locales` | Capture once in each of these language codes. `Screenshot.allLocales` is every language the app bundles |
| `themes` | Capture once in each of these theme ids. `Screenshot.allThemes` is every registered theme |
| `caption` | The text above the screen when the run frames its screenshots, or a translation key |
| `native` | Capture with Metro, on the iOS Simulator and Android (default `true`) |

On the iOS Simulator and Android, Metro captures the device's whole screen, so the status bar and keyboard are in it, and the finger and caption are hidden while it does. Add `--clean-status-bar` to the run for 9:41 with full signal and battery. Elsewhere, and in `--store` and `--frame` runs, the app captures its own screen. Pass `native: false` to always use the app's capture:

``` dart
screenshot('checkout', native: false),
```

To save every step's screen instead, run `metro live:run playbook <name> --screenshots`. See [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) for the options that size screenshots for the App Store and Google Play, and [Screenshots](/docs/{{ $version }}/screenshots) for the `Screenshot` class behind them.

<div id="screenshots-locales-and-themes"></div>

### Locales and Themes

Give a `screenshot` step `locales`, `themes` or both to capture the same screen once in each:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

The app switches in place, keeping the page and its state, and switches back before the next step. Each capture is saved in a folder named for its locale and theme:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

To play a whole playbook in every language and theme instead, pass `--locales` and `--themes` to `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Collecting from Code

`run()` takes `onScreenshot` to receive a run's screenshots as `PlaybookShot`s, which is handy for a test or your own tooling:

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

`screenshots` chooses which screens are captured:

| Mode | Captures |
|------|----------|
| `PlaybookScreenshots.off` | Nothing |
| `PlaybookScreenshots.failures` | Only the screen at a failing step |
| `PlaybookScreenshots.marked` | The `screenshot()` steps, and failing steps |
| `PlaybookScreenshots.auto` | The `screenshot()` steps of a playbook that has any, and every step of one that has none. Failing steps too. The default |
| `PlaybookScreenshots.all` | Every step, the `screenshot()` steps, and failing steps |

`pixelRatio` sizes the screenshots relative to the device's (default `1.0`). Each `PlaybookShot` has a `screenshot` to [save](/docs/{{ $version }}/screenshots#saving-a-screenshot) or read `bytes` from, a `kind`, and the `name`, `locale` and `theme` it was taken with.

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

Inside `metro live`, type `playbook sign_up`. See [Metro Live](/docs/{{ $version }}/metro-live#playbooks) for every option. Add `--record` to save a video of the run; see [Recording](/docs/{{ $version }}/metro-live#recording-playbooks). If the run stops waiting after 20 seconds, the app was probably started with `flutter run --no-dds`; see [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

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

`run()` accepts `speed`, `data`, `clock`, `overlay` (default `true`), `onEvent`, and `onScreenshot`, `screenshots` and `pixelRatio` for [collecting screenshots](#collecting-screenshots). A failing step doesn't throw -- check `report.passed`. Only one playbook can run at a time; use `play()` to run one inside another.

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
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

See [Testing Playbooks](/docs/{{ $version }}/testing#testing-playbooks) for integration tests.
