# Metro Live

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to Nylo Live")
- The metro live Shell
  - [Shared Options](#shared-options "Options every live command accepts")
  - [Tab Completion and History](#tab-completion-and-history "Tab completion and history")
  - [Running a Script](#running-a-script "Running a .live script")
- [live:devices](#live-devices "List running apps")
- [live:status](#live-status "Show app status")
- [live:run](#live-run "Run a built-in live command")
- [Playbooks](#playbooks "Playing playbooks from Metro")
- [Recording a Journey](#recording-a-journey "Recording what you do as a playbook")
- [Screenshots](#screenshots "Saving screenshots of the running app")
- Live Commands
  - [Creating a Live Command](#creating-a-live-command "Creating a live command")
  - [Running a Live Command](#running-a-live-command "Running a live command")
- Seeders
  - [Creating a Seeder](#creating-a-seeder "Creating a seeder")
  - [Seeding and Rolling Back](#seeding-and-rolling-back "Seeding and rolling back")
- Storage Snapshots
  - [Exporting a Snapshot](#exporting-a-snapshot "Exporting a snapshot")
  - [Loading a Snapshot](#loading-a-snapshot "Loading a snapshot")
- [Page Actions](#page-actions "Calling a page from outside it")

<div id="introduction"></div>

## Introduction

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** lets Metro reach into your app while it's running in debug (or profile) mode, over the Dart VM service. It finds the app through the Dart Tooling Daemon with no setup, and only ever connects to apps built from the current project.

Nylo Live is on by default in debug and profile builds; a release build registers nothing. Commands that only read the app (`live:status`, `live:run data`, `live:run routes`, ...) work in profile builds too. Commands that change the app (`route`, `storage`, `auth`, seeders, ...) need a debug build.

Turn it off yourself, before anything else runs, from a provider:

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

## The metro live Shell

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Open a shell connected to your running app:

``` bash
metro live
```

Nylo Live discovers the project's running apps and connects to one -- it asks you to pick when there's more than one running. Once connected, the prompt shows the device and the route currently on screen:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Live commands drop their `metro live:` prefix inside the shell -- `status` instead of `metro live:status`, `route /profile` instead of `metro live:run route /profile`. A few commands only run inside the shell: `seed`, `seed:rollback`, `export`, `record`, `reload` and `restart`. Type `help` to list every command, or `help <command>` to see one command's options. Type `exit` (or Ctrl+D) to leave.

If the app restarts while you're connected, the shell reconnects on its own.

<div id="shared-options"></div>

### Shared Options

Every live command -- inside the shell or as `metro live:*` -- accepts the same options:

| Option | Description |
|--------|-------------|
| `-d, --device <#\|name>` | Target an app by its number in `metro live:devices` or its device name |
| `--all` | Run on every running app of this project |
| `--json` | Print machine-readable JSON |
| `--uri` | Use this VM service address instead of discovering apps |
| `--timeout` | Seconds to wait for each app while discovering (default `3`) |

An option value written `@path` is read from that file -- handy for a large `--data` payload:

``` bash
metro live:run route /profile --data @user.json
```

Start the value with `@@` when it really begins with `@`.

<div id="tab-completion-and-history"></div>

### Tab Completion and History

Inside the shell, Tab completes command names, options, routes, storage and Backpack keys, seeder names and playbook names against the connected app. History is kept in `.dart_tool/nylo/live_history` and survives between sessions.

<div id="running-a-script"></div>

### Running a Script

Pipe a file into `metro live` to run it as a script, one command per line, stopping at the first command that fails:

``` bash
metro live < seeds/demo.live
```

Blank lines and lines starting with `#` are skipped.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Lists this project's running apps, numbered for `-d`:

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

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Shows the app, device, build mode, current route and stack, locale, theme, and whether a user is signed in:

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

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Runs a built-in command that inspects or drives the app:

``` bash
metro live:run <command> [arguments]
```

| Command | Description |
|---------|-------------|
| `data` | Show the data and fields of the page on screen |
| `routes` | List the registered routes |
| `route` | Open a page, with optional `--data` |
| `back` | Go back a page, or back to a route |
| `deeplink` | Open a deep link, or show how they are set up |
| `storage` | List local storage, or show, save or delete one value |
| `storage:clear` | Clear local storage, with optional `--keep` |
| `backpack` | List Backpack values, or show, save or delete one |
| `auth` | Show who is signed in, or sign in and out |
| `locale` | Switch the app's language |
| `theme` | Switch the app's theme |
| `state` | Send data to a state with `updateState` |
| `event` | Fire one of your events |
| `toast` | Show a toast notification |
| `playbook` | Play your [playbooks](/docs/{{ $version }}/playbooks), or list them |
| `screenshot` | Save a [screenshot](#screenshots) of the app, or of one `ScreenshotArea` |

Inside `metro live`, drop the `live:run` part -- `route /profile` instead of `metro live:run route /profile`:

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

`data` also reads the fields your page's state declares, straight out of the running app -- there's no reflection in Flutter, so this only works for a state that's actually on screen:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbooks

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Play one or more [playbooks](/docs/{{ $version }}/playbooks) on your running app. Each step prints as it happens:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Name several playbooks to play them back to back. With no names, or with `--list`, the command lists the app's playbooks.

| Option | Description |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Play at this pace instead of each playbook's own |
| `--data <json>` | A JSON object every playbook in the run reads in `data`, over its defaults |
| `--no-overlay` | Hide the countdown, and the finger and captions of playbooks that show them |
| `--loop` | Play the playbooks again and again until Ctrl+C |
| `--record[=<file>]` | Record the device's screen around the run. Saved to `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (or `.gif`) picks the file, relative to the project. Needs ffmpeg |
| `--no-captions` | Leave out the `.vtt` captions file and the chapters of a recording |
| `--clean-status-bar` | Show 9:41 with full signal and battery while the playbooks play, then put it back. Works without `--record` too |
| `--countdown <seconds>` | Count down 0-10 seconds on screen before the first step |
| `--screenshots[=<mode>]` | Save every step's screen too. `<mode>` is `all`, `auto`, `marked`, `failures` or `off`; see [Playbook Screenshots](#playbook-screenshots) |
| `--scale <n>` | The size of screenshots relative to the device's own, up to 4, e.g. `0.5` for half |
| `--locales <list>` | Play once in each of these locales, e.g. `en,es`, or `all` of the app's |
| `--themes <list>` | Play once in each of these themes, e.g. `light,dark`, or `all` of the app's |
| `--store <id>` | Draw screenshots at an app store's size, e.g. `ios-6.9` |
| `--frame` | Draw each screen inside a phone outline |
| `--background <hex>` | The color around the screen, e.g. `"#1B2027"` |
| `--list` | List the app's playbooks instead of playing one |

Ctrl+C asks the app to stop: the remaining steps are skipped, but `tearDown` still runs. Press Ctrl+C a second time to stop waiting.

If no step reaches Metro within 20 seconds, Metro stops waiting and fails the run. The app is playing the playbook, but its events aren't arriving, which happens when the app was started with `flutter run --no-dds`. Run it with a plain `flutter run` and try again.

Inside the `metro live` shell the command is `playbook <name>`, and Tab completes playbook names:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### Recording

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Add `--record` to save a video of the run:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Inside the `metro live` shell, type `playbook sign_up --record`.

Recording needs ffmpeg (for example `brew install ffmpeg`). Metro checks for it before anything plays.

- **iOS Simulator** -- recorded with `xcrun simctl`.
- **Android emulator or device** -- recorded with `adb screenrecord`, which stops after 3 minutes, or with `scrcpy` when it's installed (no limit, and it records sound on Android 11+). If several Android devices are connected and Metro can't tell which one runs the app, set `ANDROID_SERIAL`.
- **Physical iPhone** -- can't be recorded from the command line. Record with QuickTime or Control Center and use `--countdown 3` for time to start.

The result is an `.mp4` (H.264, steady 60 fps) lasting exactly as long as the run, or a 480px-wide 15 fps `.gif`. Each step becomes a caption in a `.vtt` file beside the `.mp4` and a chapter inside it. They are generated from the run's steps whether or not the playbook shows on-screen captions. Pass `--no-captions` to skip them.

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

A playbook that fails or is stopped is still saved, so a recording doubles as a bug report. Ctrl+C stops the playbook (`tearDown` still runs) and the recording is still saved.

`--clean-status-bar` keeps a status bar you already set with `simctl status_bar` on the Simulator. On Android it uses demo mode and turns it off afterwards. With several apps (`--all`), each device gets its own file with the device name in it.

A playbook hides its finger and captions by default. For a demo video, set `showFinger` and `caption` on the playbook -- see [Finger and Caption](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Screenshots

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

A playbook's [`screenshot()` steps](/docs/{{ $version }}/playbooks#screenshots) are saved in `screenshots/<playbook>/` with no flag needed. Add options to capture them in every language and theme, or to size them for a store listing:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` also saves the screen after every step of a playbook that has no `screenshot()` steps, and `--screenshots=all` does it for every playbook. A failing step's screen is saved in `build/playbooks/failures`; `--screenshots=off` turns that off.

`--locales` and `--themes` play the playbook once in each, and the app's own locale and theme are put back afterwards. `--store` lists the sizes when it has no value, and `--frame` draws each screen in a phone outline with the step's `caption:` above it. `--clean-status-bar` shows 9:41 with full signal and battery in the captures Metro takes with the iOS Simulator or `adb`.

<div id="playbook-live-commands"></div>

### Live Command Reference

The commands behind it are `playbooks.list`, `playbooks.run` and `playbooks.stop`. A run returns its id straight away and streams each step as a `nylo.playbook` event, so it can outlast a command's timeout. `live:status` lists the app's playbooks. Playbooks arrived in Nylo Live protocol version 9. Recording support -- `countdown` in `playbooks.run` (with a `countdown` event each second) and `simulator` (the iOS Simulator UDID) in `status` -- arrived in protocol version 10.

<div id="recording-a-journey"></div>

## Recording a Journey

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` writes what you do in the running app as a [playbook](/docs/{{ $version }}/playbooks#recording-a-playbook). It only runs inside the `metro live` shell:

``` plaintext
› record sign_up
```

Use the app, and press Enter when the journey is done. The playbook lands in `lib/app/playbooks/sign_up_playbook.dart`, registered in `lib/bootstrap/playbooks.dart`, and Metro offers to hot restart the app and play it back at `fast` speed.

| Option | Description |
|--------|-------------|
| `--description <text>` | A one-line description, shown when Metro lists playbooks |
| `--no-data` | Write typed values as they were typed, instead of `data[...]` placeholders with their defaults |
| `--no-replay` | Skip the question about playing it back |
| `--force`, `-f` | Replace a playbook file that already exists |

While it records, type `a` and Enter to make the next tap a `see()`, `d` for a `dontSee()`, `s <title>` to start a scene, `u` to undo the last step and `p` to pause. Ctrl+C discards the recording. `metro make:playbook sign_up --record` opens the shell and starts recording in one go.

The commands behind it are `record.start`, `record.stop`, `record.undo`, `record.mark` and `record.status`. A recording streams each step as a `nylo.record` event. Recording arrived in Nylo Live protocol version 11.

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Save a PNG of your running app:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Each file is saved in `screenshots/` in your project, named `<mode>-<device>-<date>-<time>.png`. On the iOS Simulator and Android, Metro captures the device's whole screen with the Simulator or `adb`, so the status bar and keyboard are in it. Any other device gets the app's own capture, which is the app's pages as the user sees them.

| Option | Description |
|--------|-------------|
| `--area <name>` | Capture only the `ScreenshotArea` with this name |
| `--scale <n>` | The size relative to the device's own, up to 4, e.g. `0.5` for half |
| `--locales <list>` | Capture the screen in each of these locales, e.g. `en,es`, or `all` of the app's |
| `--themes <list>` | Capture the screen in each of these themes, e.g. `light,dark`, or `all` of the app's |
| `--store <id>` | Draw at an app store's screenshot size, e.g. `ios-6.9`. On its own, `--store` lists the sizes |
| `--frame` | Draw the screen inside a phone outline, on a background |
| `--caption <text>` | Text above the `--frame`, or a translation key |
| `--background <hex>` | The color around the screen, behind a `--frame` or beside a `--store` size of another shape, e.g. `"#1B2027"` |
| `--out <path>` | Save to this `.png`, or into a folder ending in `/` (default: `screenshots/`) |
| `--open` | Open the screenshot once it's saved |
| `--copy` | Copy the screenshot to the clipboard |
| `--native` | Capture with the iOS Simulator or `adb`. This is the default for a whole-screen capture; `--no-native` uses the app's own capture |
| `--clean-status-bar` | Show 9:41 with full signal and battery in a `--native` capture |

With `--locales` or `--themes`, Metro saves one PNG for each pair in a folder, named `es.png`, `dark.png` or `es-dark.png`, switching the app in place and putting it back afterwards. `--area`, `--scale`, `--store` and `--frame` use the app's own capture.

Inside the `metro live` shell, type `screenshot --open`. To take screenshots from your app's own code, see [Screenshots](/docs/{{ $version }}/screenshots).

<div id="live-commands"></div>

## Live Commands

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Register your own commands to run inside the app, alongside the built-in ones above.

<div id="creating-a-live-command"></div>

### Creating a Live Command

``` bash
metro make:command seed_cart --live
```

| Option | Description |
|--------|-------------|
| `--category`, `-c` | The category for the command (default: `app`) |
| `--description` | A one-line description shown by the command's `--help` |
| `--force`, `-f` | Creates the file even if it already exists |

This creates a `LiveCommand` in `lib/app/commands/`, marks it `"type": "live"` in `commands.json`, registers it in `lib/bootstrap/live_commands.dart`, and wires `liveCommands: liveCommands` into `nylo.configure(...)` in `app_provider.dart`:

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

`LiveCommand` shares the `builder`/`handle` shape with Metro's own commands, but `handle` runs **inside the app**, so it can use `NyStorage`, `routeTo`, `Auth` and your models. `package:nylo_framework/live.dart` exports `LiveCommand` alongside `Seeder`, `StorageSnapshot`, `LiveException` and `LiveOutput`.

<div id="running-a-live-command"></div>

### Running a Live Command

A new live command needs a hot restart before the running app can run it. Then, from the terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeders

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

A `Seeder` puts your running app into a known state -- signed in, onboarding done, sample records -- and takes it back out again.

<div id="creating-a-seeder"></div>

### Creating a Seeder

``` bash
metro make:seeder demo_user
```

| Option | Description |
|--------|-------------|
| `--description` | A one-line description, shown when `seed` lists seeders |
| `--force`, `-f` | Creates the file even if it already exists |

This creates a `Seeder` in `lib/app/seeders/`, registers it in `lib/bootstrap/seeders.dart`, and wires `seeders: seeders` into `nylo.configure(...)`:

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

Every storage and Backpack value `up()` changes is recorded automatically, so the default `down()` (`restore()`) puts each one back exactly as it was, in reverse order -- even across a hot restart. Call `seed(otherSeeders)` from inside a seeder to run others as part of it; their changes are recorded and rolled back too.

<div id="seeding-and-rolling-back"></div>

### Seeding and Rolling Back

Seeding, rolling back and exporting only run inside the `metro live` shell:

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

`seed` on its own lists the registered seeders. `--fresh` clears storage and Backpack before seeding, and `--restart` hot restarts the app afterwards, so it starts up already seeded:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Storage Snapshots

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` saves everything the app currently holds in storage and Backpack -- as a seeder, or as a portable JSON file -- and `seed <file>` loads it back.

<div id="exporting-a-snapshot"></div>

### Exporting a Snapshot

Run it with no name to preview what would be captured:

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

Name it to save it as a seeder in `lib/app/seeders/` (the same as `make:seeder`, but already filled in with the captured values):

``` plaintext
› export pro_user
```

Or write it to a file instead:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Option | Description |
|--------|-------------|
| `--to <path>` | Write the snapshot to this JSON file instead of a seeder |
| `--description` | A one-line description for the seeder |
| `--only <keys>` | Comma-separated keys to export; `*` matches anything, e.g. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Comma-separated keys to leave out, e.g. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Include Backpack values (default: included) |
| `--force`, `-f` | Replace a seeder or file that already exists |

A `Model`, or any class with a registered decoder, is tagged by class name so it comes back through its own decoder rather than as a plain map. `export` warns when the snapshot holds keys that look like credentials, and when it's large enough that you probably want `--except`.

<div id="loading-a-snapshot"></div>

### Loading a Snapshot

``` plaintext
› seed snapshots/pro_user.json
```

Loading a file is recorded the same way a seeder is, so `seed:rollback <name>` (the file name, or whatever `--as` gave it) undoes it too. From code, a seeder can apply a captured snapshot with `importSnapshot`:

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

`export` writes exactly this shape, so a file written with `--to` can be pasted straight into a seeder's `snapshot` field.

<div id="page-actions"></div>

## Page Actions

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` on a page gives you a `PageStateActions` for calling that page from outside it, without hand-building a `stateAction(...)` call:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

See [Calling a Page's Actions](/docs/{{ $version }}/state-management#page-actions-shortcut) for the full set of built-in actions and how to declare typed actions of your own. Nylo Live reads and uses the same actions: `metro live:run data` lists what a page on screen exposes under `actions`, and `metro live:run state` sends a page data directly by its state name.
