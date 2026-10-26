# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einführung in Playbooks")
- [Ein Playbook erstellen](#creating-a-playbook "Ein Playbook erstellen")
- [Ein Playbook aufzeichnen](#recording-a-playbook "Ein Playbook aus der laufenden App aufzeichnen")
- [Schritte](#steps "Die Schritte, die ein Playbook ausführen kann")
- [Ziele](#targets "Dinge auf dem Bildschirm finden")
  - [Einfache Strings](#plain-strings "Wie ein einfacher String gefunden wird")
  - [Find](#find "Präzise Ziele mit Find")
- [Daten und Setup](#data-and-setup "Daten, setUp und tearDown")
- [Andere Playbooks abspielen](#playing-other-playbooks "Ein Playbook aus einem anderen Playbook ausführen")
- [Tempo](#speed "PlaybookSpeed")
- [Finger und Beschriftung](#finger-and-caption "Der Finger und die Schrittbeschriftung")
- [Screenshots](#screenshots "Screenshots während eines Playbooks aufnehmen")
  - [Sprachen und Themes](#screenshots-locales-and-themes "Einen Bildschirm in jeder Sprache und jedem Theme aufnehmen")
  - [Aus Code sammeln](#collecting-screenshots "Screenshots mit onScreenshot sammeln")
- [Playbooks registrieren](#registering-playbooks "Playbooks bei Nylo registrieren")
- [Ein Playbook ausführen](#running-a-playbook "Ein Playbook ausführen")
  - [Über Metro](#running-from-metro "Über Metro ausführen")
  - [Beim Start](#running-on-boot "Beim Start ausführen")
  - [Über Dart](#running-from-dart "Über Dart ausführen")
- [Berichte](#reports "PlaybookReport")
- [Release-Builds](#release-builds "Playbooks in Release-Builds")
- [Testen](#testing "Ein Playbook testen")

<div id="introduction"></div>

## Einleitung

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Ein **Playbook** ist eine Klasse, die die Taps, Eingaben, Scrollvorgänge und Seitenwechsel einer User Journey auflistet. Ihre laufende App führt sie selbst aus, und kann einen Finger und eine Beschriftung für jeden Schritt anzeigen -- praktisch für Demos, Bildschirmaufnahmen und das Prüfen einer Journey von Anfang bis Ende.

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

Importieren Sie `package:nylo_framework/playbook.dart` überall dort, wo Sie Playbooks schreiben oder registrieren.

<div id="creating-a-playbook"></div>

## Ein Playbook erstellen

Verwenden Sie Metro, um eines zu erstellen:

``` bash
metro make:playbook sign_up
```

Dies erstellt `lib/app/playbooks/sign_up_playbook.dart` und registriert es in Ihrer App. Die Optionen des Befehls finden Sie unter [Playbook erstellen](/docs/{{ $version }}/metro#make-playbook).

Um mit jeder Seite zu beginnen, die Ihr Router hinzufügt, übergeben Sie `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

Metro schreibt für jede Seite ein `visit()` und einen [`screenshot()`](#screenshots), sodass ein Durchlauf Ihre ganze App fotografiert.

<div id="recording-a-playbook"></div>

## Ein Playbook aufzeichnen

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Statt die Schritte zu schreiben, zeichnen Sie sie auf. Öffnen Sie bei laufender App im Debug-Modus `metro live` und geben Sie ein:

``` plaintext
› record sign_up
```

Verwenden Sie dann die App. Jeder Tap, langes Drücken, getippte Feld, Scrollen und Seitenwechsel wird in dem Moment, in dem er passiert, als Schritt geschrieben und laufend ausgegeben:

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

Drücken Sie Enter, um abzuschließen. Das Playbook wird nach `lib/app/playbooks/sign_up_playbook.dart` geschrieben, aufgebaut wie eines, das `make:playbook` erstellt, und registriert, sodass es abgespielt wird, sobald die App neu startet. `metro make:playbook sign_up --record` tut dasselbe vom Terminal aus: Es öffnet `metro live` und startet sofort die Aufnahme.

Geben Sie während der Aufnahme eine Taste ein und drücken Sie Enter:

| Taste | Beschreibung |
|-------|--------------|
| `a` | Der nächste Tap wird als `see(...)` statt als Tap geschrieben |
| `d` | Der nächste Tap wird als `dontSee(...)` geschrieben |
| `s <title>` | Beginnt eine `scene` mit diesem Titel; die folgenden Schritte werden darin verschachtelt |
| `u` | Entfernt den letzten Schritt |
| `p` | Pausiert die Aufnahme, z. B. um sich mit echten Zugangsdaten anzumelden; erneutes `p` setzt sie fort |
| Enter | Beendet und schreibt das Playbook |
| Strg+C | Verwirft die Aufnahme |

Ziele werden so benannt, wie der Player sie liest, sichtbarer Text zuerst, und jeder Name wird vor dem Schreiben an der laufenden App geprüft, sodass ein aufgezeichneter Schritt wiedergegeben werden kann. Passt ein einfacher String auf mehr als ein Widget, grenzt der Schritt ihn mit `Find` ein: `tap(Find.text('Add').at(1))`. Ein Icon-Button ohne Text oder Tooltip wird zu `tap(Find.icon(Icons.add))`. Ein Widget, das nichts benennt, wird als `tap(Find.type<GestureDetector>().at(3))` unter einem `// TODO`-Kommentar mit der Datei und Zeile geschrieben, in der es erstellt wurde, und Metro listet es auf, wenn die Aufnahme endet. Geben Sie ihm einen `Key` und verwenden Sie dann stattdessen den Key.

- **Tippen** wird zu einem `type`-Schritt, der einen `data[...]`-Platzhalter liest, mit dem getippten Wert in `defaults`. Ein Feld, das mit der Aktionstaste der Tastatur verlassen wird, erhält `submit: true`. Ein verdecktes Feld erhält `secret: true`, seinen Wert in `defaults` und eine Warnung, ihn zu ändern, bevor Sie committen. Übergeben Sie `--no-data`, um Werte so zu schreiben, wie sie getippt wurden.
- **Seitenwechsel** nach einem Tap werden zu `waitForPage(SignUpPage.path)`, wenn eine Ihrer Seiten die Route deklariert, sonst zu `waitForPage('/sign-up')`. Zurückgehen wird zu `back()`.
- **Scrollen** wird als `scrollTo(...)` auf das geschrieben, was Sie als Nächstes antippen, und nur, wenn das Scrollen es in den Baum gebracht hat. Alles andere deckt der Player selbst auf.
- **Wischgesten, die kein Scrollen sind** -- ein `Dismissible`, eine `PageView`, ein `Slider` -- haben noch kein Playbook-Verb, daher bleiben sie als `// TODO`-Kommentar mit Start- und Endpunkt stehen.

Die Aufnahme braucht einen Debug-Build und funktioniert auf Simulatoren, Emulatoren und Geräten gleichermaßen. Die eigenen Berührungen eines Playbooks werden nie aufgezeichnet. Die Optionen des Befehls finden Sie unter [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey).

<div id="steps"></div>

## Schritte

Die `steps` eines Playbooks werden mit den geerbten Verben aufgebaut:

| Schritt | Beschreibung |
|------|-------------|
| `tap(target)` | Ein Ziel antippen |
| `longPress(target)` | Ein Ziel gedrückt halten |
| `type(target, text)` | In ein Feld tippen. Leert es zuerst, außer bei `clear: false`; `submit: true` drückt die Aktionstaste der Tastatur; `secret: true` verbirgt den Text in der Beschriftung und im Bericht |
| `scrollTo(target)` | Scrollen, bis ein Ziel auf dem Bildschirm ist. Übergeben Sie `within:`, um eine bestimmte scrollbare Fläche zu scrollen |
| `see(target)` | Prüfen, dass ein Ziel auf dem Bildschirm ist, und darauf warten, dass es erscheint |
| `dontSee(target)` | Prüfen, dass ein Ziel nicht auf dem Bildschirm ist, und darauf warten, dass es verschwindet |
| `waitFor(target)` | Auf ein Ziel warten, bis zu `timeout` (standardmäßig 10 Sekunden) |
| `waitForPage(path)` | Auf die Seite unter einem Routenpfad oder `RouteView` warten (z. B. `HomePage.path`) |
| `visit(path)` | Eine Seite per Routenpfad oder `RouteView` öffnen, mit optionalem `data:` |
| `back()` | Eine Seite zurückgehen |
| `pause(seconds: 1)` | Innehalten, z. B. damit eine Demo atmen kann |
| `step(title, action)` | Beliebigen Code mit denselben Verben ausführen, über einen `PlaybookActor` |
| `scene(title, steps)` | Schritte unter einem Titel gruppieren; ihre Beschriftungen beginnen damit und ihre Ergebnisse werden im Bericht verschachtelt |
| `screenshot(name)` | Den Bildschirm aufnehmen -- siehe [Screenshots](#screenshots) |
| `play(playbook)` | Ein anderes Playbook ausführen -- siehe [Andere Playbooks abspielen](#playing-other-playbooks) |

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

Die Schritte laufen der Reihe nach und stoppen beim ersten Fehler. Die übrigen Schritte werden als übersprungen gemeldet.

<div id="targets"></div>

## Ziele

Die meisten Schritte nehmen ein Ziel entgegen: was angetippt, in was getippt oder wonach gesucht werden soll.

<div id="plain-strings"></div>

### Einfache Strings

Ein einfacher String wird so gefunden, wie ein Mensch den Bildschirm liest. Er versucht der Reihe nach:

1. Sichtbarer Text
2. Hint oder Label eines Feldes
3. Ein Tooltip
4. Ein Semantics-Label
5. Ein `ValueKey`
6. Text, der den String enthält

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

Ein `Key`, ein `Type` oder ein `IconData` kann auch direkt an einen Schritt übergeben werden.

<div id="find"></div>

### Find

Verwenden Sie `Find`, wenn ein einfacher String nicht präzise genug ist:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Findet |
|--------|---------|
| `Find.text(text)` | Sichtbaren Text |
| `Find.textContaining(text)` | Text, der den String enthält |
| `Find.icon(icon, label:)` | Ein Icon. `label:` benennt es in den Schrittbeschriftungen |
| `Find.type<T>()` | Ein Widget vom Typ `T` |
| `Find.key(key)` | Ein Widget mit dem Key |
| `Find.field(name)` | Ein `NyForm`-Feld nach Namen |
| `Find.input(text)` | Ein Textfeld nach Hint, Label oder Platzhalter |
| `Find.tooltip(message)` | Einen Tooltip |
| `Find.semantics(label)` | Ein Semantics-Label |
| `Find.where(test)` | Widgets, die Ihren Test bestehen |

Geben Sie einem Icon ein `label`, und seine Schrittbeschriftungen lauten `Tap the bag icon` statt `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

Schränken Sie jeden Finder mit `.inside(ancestor)`, `.at(index)` (Zählung ab 0), `.first` oder `.last` ein.

Ein Tap wird abgelehnt, wenn etwas Unzusammenhängendes, wie die Barriere eines Dialogs, sein Ziel verdeckt.

<div id="data-and-setup"></div>

## Daten und Setup

`defaults` enthält die Daten, mit denen die Schritte beginnen. Lesen Sie sie innerhalb von `steps` über `data`. `setUp` läuft vor den Schritten und `tearDown` danach, auch wenn ein Schritt fehlgeschlagen ist:

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

`seed([...])` führt [Seeder](/docs/{{ $version }}/metro-live#seeders) aus (Instanzen oder registrierte Namen) und zeichnet sie auf wie `seed` in `metro live`.

<div id="playing-other-playbooks"></div>

## Andere Playbooks abspielen

`play()` führt ein anderes Playbook vollständig aus, mit eigenem `setUp` und `tearDown`. Übergeben Sie eine Instanz oder den Namen, unter dem es registriert ist:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Daten** werden in dieser Reihenfolge zusammengeführt: die `defaults` des Kindes, dann die `data` des Elternteils, dann das an `play` übergebene `data:`.
- **Tempo** wird vom Elternteil geerbt, sofern Sie nicht `speed:` übergeben.
- **Ergebnisse** werden im Bericht unter dem Schritt verschachtelt.
- **Ein fehlschlagendes Kind** lässt den Schritt fehlschlagen.
- **Zyklen** werden abgelehnt, und Playbooks verschachteln sich höchstens 8 Ebenen tief.

<div id="speed"></div>

## Tempo

`PlaybookSpeed` legt das Tempo jeder Wartezeit fest:

| Tempo | Beschreibung |
|-------|-------------|
| `PlaybookSpeed.instant` | Keine Wartezeiten über das hinaus, was das Scrollen braucht. Gedacht für Tests |
| `PlaybookSpeed.fast` | Schnell, aber noch gut nachzuvollziehen |
| `PlaybookSpeed.natural` | Das Tempo eines Menschen, der die App benutzt. Der Standard |
| `PlaybookSpeed.slow` | Langsam genug, um darüber zu sprechen |

Skalieren Sie jedes Tempo mit `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // doppelt so langsam
```

Ein an `run()` übergebenes Tempo, `--speed` von Metro oder das Tempo eines Playbooks, das dieses abspielt, hat Vorrang vor dem eigenen Tempo des Playbooks.

<div id="finger-and-caption"></div>

## Finger und Beschriftung

Der Finger und die Schrittbeschriftungen sind standardmäßig aus. Überschreiben Sie `showFinger` und `caption`, um sie einzuschalten:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // oder .top
```

`showFinger` ist standardmäßig `false` und `caption` standardmäßig `PlaybookCaption.hidden`; `PlaybookCaption` ist `top`, `bottom` oder `hidden`. Taps und Wischgesten finden in beiden Fällen statt. Ein Playbook, das von diesem per `play()` abgespielt wird, verwendet seine eigene Einstellung, und die von diesem kehrt zurück, wenn es fertig ist. Um beides für einen Durchlauf auszublenden, verwenden Sie `--no-overlay` von Metro oder `run(overlay: false)` aus Dart.

Vor nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0) waren der Finger und die untere Beschriftung standardmäßig an. Fügen Sie die obigen Overrides hinzu, um diese Darstellung beizubehalten.

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Fügen Sie einen `screenshot`-Schritt überall dort ein, wo der Bildschirm aufhebenswert ist:

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

`metro live:run playbook <name>` speichert jeden als `screenshots/<playbook>/<name>.png`, ohne dass ein Flag nötig ist. Ein Durchlauf aus Code sammelt sie mit [`onScreenshot`](#collecting-screenshots), und jeder andere Durchlauf, etwa `PLAYBOOK_ON_BOOT`, wartet nur, bis sich der Bildschirm beruhigt hat. Ein Name beginnt mit einem Buchstaben oder einer Ziffer und verwendet Buchstaben, Ziffern, `-`, `_` und `.`.

| Parameter | Beschreibung |
|-----------|--------------|
| `name` | Der Name, unter dem der Screenshot gespeichert wird |
| `locales` | Einmal in jedem dieser Sprachcodes aufnehmen. `Screenshot.allLocales` ist jede Sprache, die die App bündelt |
| `themes` | Einmal in jeder dieser Theme-IDs aufnehmen. `Screenshot.allThemes` ist jedes registrierte Theme |
| `caption` | Der Text über dem Bildschirm, wenn der Durchlauf seine Screenshots einrahmt, oder ein Übersetzungsschlüssel |
| `native` | Mit Metro aufnehmen, im iOS Simulator und unter Android (Standard `true`) |

Im iOS Simulator und unter Android nimmt Metro den gesamten Bildschirm des Geräts auf, sodass Statusleiste und Tastatur enthalten sind, und Finger und Beschriftung werden dabei ausgeblendet. Fügen Sie dem Durchlauf `--clean-status-bar` hinzu, um 9:41 mit vollem Signal und voller Batterie zu erhalten. Anderswo und in `--store`- und `--frame`-Durchläufen nimmt die App ihren eigenen Bildschirm auf. Übergeben Sie `native: false`, um stets die Aufnahme der App zu verwenden:

``` dart
screenshot('checkout', native: false),
```

Um stattdessen den Bildschirm jedes Schritts zu speichern, führen Sie `metro live:run playbook <name> --screenshots` aus. Unter [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) finden Sie die Optionen, die Screenshots für den App Store und Google Play dimensionieren, und unter [Screenshots](/docs/{{ $version }}/screenshots) die `Screenshot`-Klasse dahinter.

<div id="screenshots-locales-and-themes"></div>

### Sprachen und Themes

Geben Sie einem `screenshot`-Schritt `locales`, `themes` oder beides, um denselben Bildschirm jeweils einmal in jedem aufzunehmen:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

Die App wechselt an Ort und Stelle, behält Seite und Zustand und wechselt vor dem nächsten Schritt zurück. Jede Aufnahme wird in einem Ordner gespeichert, der nach ihrer Sprache und ihrem Theme benannt ist:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Um stattdessen ein ganzes Playbook in jeder Sprache und jedem Theme abzuspielen, übergeben Sie `--locales` und `--themes` an `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Aus Code sammeln

`run()` akzeptiert `onScreenshot`, um die Screenshots eines Durchlaufs als `PlaybookShot`s zu erhalten, was für einen Test oder Ihre eigenen Werkzeuge praktisch ist:

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

`screenshots` wählt, welche Bildschirme aufgenommen werden:

| Modus | Nimmt auf |
|-------|-----------|
| `PlaybookScreenshots.off` | Nichts |
| `PlaybookScreenshots.failures` | Nur den Bildschirm bei einem fehlschlagenden Schritt |
| `PlaybookScreenshots.marked` | Die `screenshot()`-Schritte und fehlschlagende Schritte |
| `PlaybookScreenshots.auto` | Die `screenshot()`-Schritte eines Playbooks, das welche hat, und jeden Schritt eines Playbooks, das keine hat. Auch fehlschlagende Schritte. Der Standard |
| `PlaybookScreenshots.all` | Jeden Schritt, die `screenshot()`-Schritte und fehlschlagende Schritte |

`pixelRatio` dimensioniert die Screenshots relativ zu denen des Geräts (Standard `1.0`). Jeder `PlaybookShot` hat einen `screenshot`, den Sie [speichern](/docs/{{ $version }}/screenshots#saving-a-screenshot) oder aus dem Sie `bytes` lesen können, eine `kind` sowie `name`, `locale` und `theme`, mit denen er aufgenommen wurde.

<div id="registering-playbooks"></div>

## Playbooks registrieren

Registrieren Sie Ihre Playbooks, damit Metro und `play('name')` sie finden können:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` gibt zurück, was registriert ist. Sie können sie auch beim Konfigurieren von Nylo in Ihrem `AppProvider` übergeben:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` erledigt das für Sie. Es hält die Registry in `lib/bootstrap/playbooks.dart`, fügt jedes neue Playbook dort hinzu und übergibt `playbooks` an `nylo.configure(...)`. Die Registry ist in Release-Builds eine leere Map:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Ein registriertes Playbook läuft unter seinem Key. Ein nicht registriertes läuft unter seinem `name` oder seinem Klassennamen in Snake Case (`SignUpPlaybook` wird zu `sign_up`).

<div id="running-a-playbook"></div>

## Ein Playbook ausführen

<div id="running-from-metro"></div>

### Über Metro

Bei laufender App im Debug-Modus:

``` bash
metro live:run playbook sign_up --speed fast
```

Geben Sie innerhalb von `metro live` `playbook sign_up` ein. Alle Optionen finden Sie unter [Metro Live](/docs/{{ $version }}/metro-live#playbooks). Fügen Sie `--record` hinzu, um ein Video des Durchlaufs zu speichern; siehe [Aufnahme](/docs/{{ $version }}/metro-live#recording-playbooks). Hört der Durchlauf nach 20 Sekunden auf zu warten, wurde die App wahrscheinlich mit `flutter run --no-dds` gestartet; siehe [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

<div id="running-on-boot"></div>

### Beim Start

Fügen Sie diese Schlüssel zu Ihrer `.env`-Datei hinzu, um Playbooks abzuspielen, sobald die App auf dem Bildschirm ist:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` ist eine kommagetrennte Liste von Playbook-Namen und `PLAYBOOK_SPEED` ist eines von `instant`, `fast`, `natural` oder `slow`. Beide gelten nur in Debug- und Profile-Builds.

<div id="running-from-dart"></div>

### Über Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` akzeptiert `speed`, `data`, `clock`, `overlay` (Standard `true`), `onEvent` sowie `onScreenshot`, `screenshots` und `pixelRatio` zum [Sammeln von Screenshots](#collecting-screenshots). Ein fehlschlagender Schritt wirft keine Exception -- prüfen Sie `report.passed`. Es kann immer nur ein Playbook gleichzeitig laufen; verwenden Sie `play()`, um eines innerhalb eines anderen auszuführen.

Stoppen Sie ein laufendes Playbook mit `Playbook.stop()`. Die übrigen Schritte werden übersprungen, `tearDown` läuft aber trotzdem. `Playbook.isRunning` zeigt an, ob eines läuft.

<div id="reports"></div>

## Berichte

`run()` gibt einen `PlaybookReport` mit dem `name` des Playbooks, `speed`, der `elapsed`-Zeit, der Angabe, ob es `stopped` wurde, und einer `steps`-Liste von `PlaybookStepResult`s zurück (jeweils mit `title`, `status`, `elapsed`, `error` und verschachtelten `steps`). `report.passed` ist true, wenn jeder Schritt bestanden hat, und die Ausgabe eines Berichts liest sich wie die Ausgabe von Metro. `report.toJson()` liefert dieselben Daten als JSON.

<div id="release-builds"></div>

## Release-Builds

Playbooks laufen nie in einem Release-Build, sodass eine Journey die App eines Nutzers nicht steuern kann:

- `Playbook.run()` wirft einen `UnsupportedError`.
- `addPlaybooks` wird ignoriert.
- Die von Metro generierte Registry `lib/bootstrap/playbooks.dart` ist leer.

`Playbook.isAvailable` zeigt an, ob der aktuelle Build Playbooks ausführen kann.

<div id="testing"></div>

## Testen

Führen Sie ein Playbook in einem Widget-Test mit `NyTesterClock` aus und prüfen Sie dann seinen Bericht mit `expectPlaybookPassed`:

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Integrationstests finden Sie unter [Playbooks testen](/docs/{{ $version }}/testing#testing-playbooks).
