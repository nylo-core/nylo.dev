# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einführung in Playbooks")
- [Ein Playbook erstellen](#creating-a-playbook "Ein Playbook erstellen")
- [Schritte](#steps "Die Schritte, die ein Playbook ausführen kann")
- [Ziele](#targets "Dinge auf dem Bildschirm finden")
  - [Einfache Strings](#plain-strings "Wie ein einfacher String gefunden wird")
  - [Find](#find "Präzise Ziele mit Find")
- [Daten und Setup](#data-and-setup "Daten, setUp und tearDown")
- [Andere Playbooks abspielen](#playing-other-playbooks "Ein Playbook aus einem anderen Playbook ausführen")
- [Tempo](#speed "PlaybookSpeed")
- [Finger und Beschriftung](#finger-and-caption "Der Finger und die Schrittbeschriftung")
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

Ein **Playbook** ist eine Klasse, die die Taps, Eingaben, Scrollvorgänge und Seitenwechsel einer User Journey auflistet. Ihre laufende App führt sie selbst aus, mit einem sichtbaren Finger und einer Beschriftung für jeden Schritt -- praktisch für Demos, Bildschirmaufnahmen und das Prüfen einer Journey von Anfang bis Ende.

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
| `Find.icon(icon)` | Ein Icon |
| `Find.type<T>()` | Ein Widget vom Typ `T` |
| `Find.key(key)` | Ein Widget mit dem Key |
| `Find.field(name)` | Ein `NyForm`-Feld nach Namen |
| `Find.input(text)` | Ein Textfeld nach Hint, Label oder Platzhalter |
| `Find.tooltip(message)` | Einen Tooltip |
| `Find.semantics(label)` | Ein Semantics-Label |
| `Find.where(test)` | Widgets, die Ihren Test bestehen |

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

Überschreiben Sie `showFinger` und `caption`, um zu ändern, was das Overlay zeichnet:

``` dart
@override
bool get showFinger => false;

@override
PlaybookCaption get caption => PlaybookCaption.top;
```

`PlaybookCaption` ist `top`, `bottom` (der Standard) oder `hidden`. Taps und Wischgesten finden auch ohne den Finger statt. Um das gesamte Overlay für einen Durchlauf auszuschalten, verwenden Sie `--no-overlay` von Metro oder `run(overlay: false)` aus Dart.

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

Geben Sie innerhalb von `metro live` `playbook sign_up` ein. Alle Optionen finden Sie unter [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

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

`run()` akzeptiert `speed`, `data`, `clock`, `overlay` (Standard `true`) und `onEvent`. Ein fehlschlagender Schritt wirft keine Exception -- prüfen Sie `report.passed`. Es kann immer nur ein Playbook gleichzeitig laufen; verwenden Sie `play()`, um eines innerhalb eines anderen auszuführen.

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
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Integrationstests finden Sie unter [Playbooks testen](/docs/{{ $version }}/testing#testing-playbooks).
