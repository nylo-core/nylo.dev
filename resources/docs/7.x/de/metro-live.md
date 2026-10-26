# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einführung in Nylo Live")
- Die metro live-Shell
  - [Gemeinsame Optionen](#shared-options "Optionen, die jeder Live-Befehl akzeptiert")
  - [Tab-Vervollständigung und Verlauf](#tab-completion-and-history "Tab-Vervollständigung und Verlauf")
  - [Ein Skript ausführen](#running-a-script "Ein .live-Skript ausführen")
- [live:devices](#live-devices "Laufende Apps auflisten")
- [live:status](#live-status "App-Status anzeigen")
- [live:run](#live-run "Einen integrierten Live-Befehl ausführen")
- [Playbooks](#playbooks "Playbooks von Metro aus abspielen")
- [Eine Journey aufzeichnen](#recording-a-journey "Aufzeichnen, was Sie tun, als Playbook")
- [Screenshots](#screenshots "Screenshots der laufenden App speichern")
- Live Commands
  - [Einen Live Command erstellen](#creating-a-live-command "Einen Live Command erstellen")
  - [Einen Live Command ausführen](#running-a-live-command "Einen Live Command ausführen")
- Seeder
  - [Einen Seeder erstellen](#creating-a-seeder "Einen Seeder erstellen")
  - [Seeden und Rollback](#seeding-and-rolling-back "Seeden und Rollback")
- Speicher-Snapshots
  - [Einen Snapshot exportieren](#exporting-a-snapshot "Einen Snapshot exportieren")
  - [Einen Snapshot laden](#loading-a-snapshot "Einen Snapshot laden")
- [Seiten-Aktionen](#page-actions "Eine Seite von außerhalb aufrufen")

<div id="introduction"></div>

## Einleitung

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** ermöglicht es Metro, auf Ihre App zuzugreifen, während sie im Debug- (oder Profile-)Modus läuft, über den Dart-VM-Service. Es findet die App über den Dart Tooling Daemon ohne jede Einrichtung und verbindet sich stets nur mit Apps, die aus dem aktuellen Projekt gebaut wurden.

Nylo Live ist in Debug- und Profile-Builds standardmäßig aktiviert; ein Release-Build registriert nichts. Befehle, die die App nur lesen (`live:status`, `live:run data`, `live:run routes`, ...), funktionieren auch in Profile-Builds. Befehle, die die App verändern (`route`, `storage`, `auth`, Seeder, ...), benötigen einen Debug-Build.

Schalten Sie es selbst aus, bevor irgendetwas anderes läuft, aus einem Provider heraus:

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

## Die metro live-Shell

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Öffnen Sie eine Shell, die mit Ihrer laufenden App verbunden ist:

``` bash
metro live
```

Nylo Live entdeckt die laufenden Apps des Projekts und verbindet sich mit einer davon -- läuft mehr als eine, werden Sie zur Auswahl aufgefordert. Nach der Verbindung zeigt der Prompt das Gerät und die aktuell angezeigte Route:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Live-Befehle verlieren innerhalb der Shell ihr `metro live:`-Präfix -- `status` statt `metro live:status`, `route /profile` statt `metro live:run route /profile`. Einige Befehle laufen nur innerhalb der Shell: `seed`, `seed:rollback`, `export`, `record`, `reload` und `restart`. Geben Sie `help` ein, um alle Befehle aufzulisten, oder `help <command>`, um die Optionen eines Befehls zu sehen. Geben Sie `exit` ein (oder Ctrl+D), um die Shell zu verlassen.

Wenn die App neu startet, während Sie verbunden sind, verbindet sich die Shell von selbst neu.

<div id="shared-options"></div>

### Gemeinsame Optionen

Jeder Live-Befehl -- innerhalb der Shell oder als `metro live:*` -- akzeptiert dieselben Optionen:

| Option | Beschreibung |
|--------|-------------|
| `-d, --device <#\|name>` | Eine App anhand ihrer Nummer in `metro live:devices` oder ihres Gerätenamens ansprechen |
| `--all` | Auf jeder laufenden App dieses Projekts ausführen |
| `--json` | Maschinenlesbares JSON ausgeben |
| `--uri` | Diese VM-Service-Adresse verwenden, anstatt Apps zu entdecken |
| `--timeout` | Sekunden, die bei der Entdeckung auf jede App gewartet wird (Standard `3`) |

Ein Optionswert, der als `@path` geschrieben wird, wird aus dieser Datei gelesen -- praktisch für eine große `--data`-Nutzlast:

``` bash
metro live:run route /profile --data @user.json
```

Beginnen Sie den Wert mit `@@`, wenn er tatsächlich mit `@` beginnen soll.

<div id="tab-completion-and-history"></div>

### Tab-Vervollständigung und Verlauf

Innerhalb der Shell vervollständigt Tab Befehlsnamen, Optionen, Routen, Speicher- und Backpack-Schlüssel Seeder-Namen sowie Playbook-Namen anhand der verbundenen App. Der Verlauf wird in `.dart_tool/nylo/live_history` gespeichert und bleibt über Sitzungen hinweg erhalten.

<div id="running-a-script"></div>

### Ein Skript ausführen

Leiten Sie eine Datei per Pipe in `metro live`, um sie als Skript auszuführen -- ein Befehl pro Zeile, wobei beim ersten fehlschlagenden Befehl gestoppt wird:

``` bash
metro live < seeds/demo.live
```

Leere Zeilen und Zeilen, die mit `#` beginnen, werden übersprungen.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Listet die laufenden Apps dieses Projekts auf, nummeriert für `-d`:

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

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Zeigt die App, das Gerät, den Build-Modus, die aktuelle Route und den Stack, die Locale, das Theme und ob ein Benutzer angemeldet ist:

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

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Führt einen integrierten Befehl aus, der die App untersucht oder steuert:

``` bash
metro live:run <command> [arguments]
```

| Command | Beschreibung |
|---------|-------------|
| `data` | Die Daten und Felder der aktuell angezeigten Seite anzeigen |
| `routes` | Die registrierten Routen auflisten |
| `route` | Eine Seite öffnen, mit optionalem `--data` |
| `back` | Eine Seite zurückgehen, oder zurück zu einer Route |
| `deeplink` | Einen Deep Link öffnen, oder zeigen, wie sie eingerichtet sind |
| `storage` | Lokalen Speicher auflisten, oder einen Wert anzeigen, speichern oder löschen |
| `storage:clear` | Lokalen Speicher leeren, mit optionalem `--keep` |
| `backpack` | Backpack-Werte auflisten, oder einen anzeigen, speichern oder löschen |
| `auth` | Zeigen, wer angemeldet ist, oder an- und abmelden |
| `locale` | Die Sprache der App wechseln |
| `theme` | Das Theme der App wechseln |
| `state` | Daten an einen State senden mit `updateState` |
| `event` | Eines Ihrer Events auslösen |
| `toast` | Eine Toast-Benachrichtigung anzeigen |
| `playbook` | Ihre [Playbooks](/docs/{{ $version }}/playbooks) abspielen oder auflisten |
| `screenshot` | Einen [Screenshot](#screenshots) der App oder einer `ScreenshotArea` speichern |

Innerhalb von `metro live` entfällt der Teil `live:run` -- `route /profile` statt `metro live:run route /profile`:

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

`data` liest außerdem die Felder, die der State Ihrer Seite deklariert, direkt aus der laufenden App aus -- da es in Flutter keine Reflection gibt, funktioniert dies nur für einen State, der tatsächlich gerade angezeigt wird:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbooks

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Spielen Sie ein oder mehrere [Playbooks](/docs/{{ $version }}/playbooks) auf Ihrer laufenden App ab. Jeder Schritt wird ausgegeben, sobald er passiert:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Nennen Sie mehrere Playbooks, um sie nacheinander abzuspielen. Ohne Namen oder mit `--list` listet der Befehl die Playbooks der App auf.

| Option | Beschreibung |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Mit diesem Tempo abspielen statt mit dem des jeweiligen Playbooks |
| `--data <json>` | Ein JSON-Objekt, das jedes Playbook des Durchlaufs in `data` liest, über seinen Standardwerten |
| `--no-overlay` | Den Countdown sowie den Finger und die Beschriftungen von Playbooks ausblenden, die sie anzeigen |
| `--loop` | Die Playbooks immer wieder abspielen, bis Sie Strg+C drücken |
| `--record[=<file>]` | Den Bildschirm des Geräts rund um den Durchlauf aufzeichnen. Gespeichert unter `build/playbooks/<playbooks>-<device>-<time>.mp4`; mit `--record=demo.mp4` (oder `.gif`) wählen Sie die Datei, relativ zum Projekt. Benötigt ffmpeg |
| `--no-captions` | Die `.vtt`-Untertiteldatei und die Kapitel einer Aufnahme weglassen |
| `--clean-status-bar` | 9:41 mit vollem Signal und voller Batterie anzeigen, solange die Playbooks laufen, und danach wiederherstellen. Funktioniert auch ohne `--record` |
| `--countdown <seconds>` | Vor dem ersten Schritt 0-10 Sekunden lang auf dem Bildschirm herunterzählen |
| `--screenshots[=<mode>]` | Auch den Bildschirm jedes Schritts speichern. `<mode>` ist `all`, `auto`, `marked`, `failures` oder `off`; siehe [Playbook-Screenshots](#playbook-screenshots) |
| `--scale <n>` | Die Größe der Screenshots relativ zu der des Geräts, bis zu 4, z. B. `0.5` für die Hälfte |
| `--locales <list>` | Einmal in jeder dieser Sprachen abspielen, z. B. `en,es`, oder `all` der App |
| `--themes <list>` | Einmal in jedem dieser Themes abspielen, z. B. `light,dark`, oder `all` der App |
| `--store <id>` | Screenshots in der Größe eines App Stores zeichnen, z. B. `ios-6.9` |
| `--frame` | Jeden Bildschirm in einem Handy-Umriss zeichnen |
| `--background <hex>` | Die Farbe um den Bildschirm, z. B. `"#1B2027"` |
| `--list` | Die Playbooks der App auflisten, statt eines abzuspielen |

Strg+C bittet die App, anzuhalten: Die übrigen Schritte werden übersprungen, `tearDown` läuft aber trotzdem. Drücken Sie ein zweites Mal Strg+C, um nicht mehr zu warten.

Erreicht innerhalb von 20 Sekunden kein Schritt Metro, hört Metro auf zu warten und lässt den Durchlauf fehlschlagen. Die App spielt das Playbook ab, aber ihre Events kommen nicht an, was passiert, wenn die App mit `flutter run --no-dds` gestartet wurde. Starten Sie sie mit einem normalen `flutter run` und versuchen Sie es erneut.

Innerhalb der `metro live`-Shell lautet der Befehl `playbook <name>`, und Tab vervollständigt Playbook-Namen:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### Aufnahme

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Fügen Sie `--record` hinzu, um ein Video des Durchlaufs zu speichern:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Geben Sie innerhalb der `metro live`-Shell `playbook sign_up --record` ein.

Die Aufnahme benötigt ffmpeg (zum Beispiel `brew install ffmpeg`). Metro prüft das, bevor etwas abgespielt wird.

- **iOS Simulator** -- aufgenommen mit `xcrun simctl`.
- **Android-Emulator oder -Gerät** -- aufgenommen mit `adb screenrecord`, das nach 3 Minuten endet, oder mit `scrcpy`, sofern installiert (ohne Limit, und ab Android 11+ wird auch Ton aufgenommen). Wenn mehrere Android-Geräte verbunden sind und Metro nicht erkennen kann, auf welchem die App läuft, setzen Sie `ANDROID_SERIAL`.
- **Physisches iPhone** -- kann nicht über die Befehlszeile aufgenommen werden. Nehmen Sie mit QuickTime oder dem Kontrollzentrum auf und verwenden Sie `--countdown 3`, um Zeit zum Starten zu haben.

Das Ergebnis ist eine `.mp4` (H.264, konstant 60 fps), die genau so lang ist wie der Durchlauf, oder ein `.gif` mit 480px Breite und 15 fps. Jeder Schritt wird zu einer Beschriftung in einer `.vtt`-Datei neben der `.mp4` und zu einem Kapitel darin. Sie werden aus den Schritten des Durchlaufs erzeugt, unabhängig davon, ob das Playbook Beschriftungen auf dem Bildschirm anzeigt. Übergeben Sie `--no-captions`, um sie zu überspringen.

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

Ein Playbook, das fehlschlägt oder angehalten wird, wird trotzdem gespeichert, sodass eine Aufnahme auch als Fehlerbericht dient. Strg+C stoppt das Playbook (`tearDown` läuft trotzdem), und die Aufnahme wird dennoch gespeichert.

`--clean-status-bar` behält eine Statusleiste bei, die Sie im Simulator bereits mit `simctl status_bar` gesetzt haben. Unter Android wird der Demo-Modus verwendet und danach wieder ausgeschaltet. Bei mehreren Apps (`--all`) erhält jedes Gerät eine eigene Datei mit dem Gerätenamen darin.

Ein Playbook blendet Finger und Beschriftungen standardmäßig aus. Für ein Demovideo setzen Sie `showFinger` und `caption` im Playbook -- siehe [Finger und Beschriftung](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Screenshots

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Die [`screenshot()`-Schritte](/docs/{{ $version }}/playbooks#screenshots) eines Playbooks werden ohne Flag in `screenshots/<playbook>/` gespeichert. Fügen Sie Optionen hinzu, um sie in jeder Sprache und jedem Theme aufzunehmen oder für einen Store-Eintrag zu dimensionieren:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` speichert außerdem den Bildschirm nach jedem Schritt eines Playbooks ohne `screenshot()`-Schritte, und `--screenshots=all` tut das für jedes Playbook. Der Bildschirm eines fehlschlagenden Schritts wird in `build/playbooks/failures` gespeichert; `--screenshots=off` schaltet das aus.

`--locales` und `--themes` spielen das Playbook jeweils einmal in jedem ab, und die eigene Sprache und das eigene Theme der App werden danach wiederhergestellt. `--store` listet die Größen auf, wenn es keinen Wert hat, und `--frame` zeichnet jeden Bildschirm in einen Handy-Umriss mit der `caption:` des Schritts darüber. `--clean-status-bar` zeigt 9:41 mit vollem Signal und voller Batterie in den Aufnahmen, die Metro mit dem iOS Simulator oder `adb` macht.

<div id="playbook-live-commands"></div>

### Referenz der Live Commands

Die zugrunde liegenden Commands sind `playbooks.list`, `playbooks.run` und `playbooks.stop`. Ein Durchlauf gibt sofort seine ID zurück und streamt jeden Schritt als `nylo.playbook`-Event, sodass er das Timeout eines Commands überdauern kann. `live:status` listet die Playbooks der App auf. Playbooks kamen mit Nylo-Live-Protokollversion 9. Die Unterstützung für Aufnahmen -- `countdown` in `playbooks.run` (mit einem `countdown`-Event pro Sekunde) und `simulator` (die UDID des iOS Simulators) in `status` -- kam mit Protokollversion 10.

<div id="recording-a-journey"></div>

## Eine Journey aufzeichnen

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` schreibt auf, was Sie in der laufenden App tun, als [Playbook](/docs/{{ $version }}/playbooks#recording-a-playbook). Es läuft nur innerhalb der `metro live`-Shell:

``` plaintext
› record sign_up
```

Verwenden Sie die App und drücken Sie Enter, wenn die Journey beendet ist. Das Playbook landet in `lib/app/playbooks/sign_up_playbook.dart`, registriert in `lib/bootstrap/playbooks.dart`, und Metro bietet an, die App per Hot Restart neu zu starten und es mit `fast`-Geschwindigkeit abzuspielen.

| Option | Beschreibung |
|--------|--------------|
| `--description <text>` | Eine einzeilige Beschreibung, die angezeigt wird, wenn Metro Playbooks auflistet |
| `--no-data` | Eingegebene Werte so schreiben, wie sie eingegeben wurden, statt `data[...]`-Platzhalter mit ihren Standardwerten |
| `--no-replay` | Die Frage nach dem Abspielen überspringen |
| `--force`, `-f` | Eine bereits vorhandene Playbook-Datei ersetzen |

Während der Aufnahme machen Sie mit `a` und Enter den nächsten Tap zu einem `see()`, mit `d` zu einem `dontSee()`, beginnen mit `s <title>` eine Szene, machen mit `u` den letzten Schritt rückgängig und pausieren mit `p`. Strg+C verwirft die Aufnahme. `metro make:playbook sign_up --record` öffnet die Shell und startet die Aufnahme in einem Zug.

Die zugrunde liegenden Commands sind `record.start`, `record.stop`, `record.undo`, `record.mark` und `record.status`. Eine Aufnahme streamt jeden Schritt als `nylo.record`-Event. Die Aufnahme kam mit Nylo-Live-Protokollversion 11.

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Speichern Sie ein PNG Ihrer laufenden App:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Jede Datei wird in `screenshots/` in Ihrem Projekt gespeichert und heißt `<mode>-<device>-<date>-<time>.png`. Im iOS Simulator und unter Android nimmt Metro den gesamten Bildschirm des Geräts mit dem Simulator oder `adb` auf, sodass Statusleiste und Tastatur enthalten sind. Jedes andere Gerät erhält die eigene Aufnahme der App, also die Seiten der App, wie der Benutzer sie sieht.

| Option | Beschreibung |
|--------|--------------|
| `--area <name>` | Nur die `ScreenshotArea` mit diesem Namen aufnehmen |
| `--scale <n>` | Die Größe relativ zu der des Geräts, bis zu 4, z. B. `0.5` für die Hälfte |
| `--locales <list>` | Den Bildschirm in jeder dieser Sprachen aufnehmen, z. B. `en,es`, oder `all` der App |
| `--themes <list>` | Den Bildschirm in jedem dieser Themes aufnehmen, z. B. `light,dark`, oder `all` der App |
| `--store <id>` | In der Screenshot-Größe eines App Stores zeichnen, z. B. `ios-6.9`. Allein listet `--store` die Größen auf |
| `--frame` | Den Bildschirm in einem Handy-Umriss auf einem Hintergrund zeichnen |
| `--caption <text>` | Text über dem `--frame` oder ein Übersetzungsschlüssel |
| `--background <hex>` | Die Farbe um den Bildschirm, hinter einem `--frame` oder neben einer `--store`-Größe mit anderer Form, z. B. `"#1B2027"` |
| `--out <path>` | In dieses `.png` speichern oder in einen Ordner, der auf `/` endet (Standard: `screenshots/`) |
| `--open` | Den Screenshot nach dem Speichern öffnen |
| `--copy` | Den Screenshot in die Zwischenablage kopieren |
| `--native` | Mit dem iOS Simulator oder `adb` aufnehmen. Das ist der Standard für eine Aufnahme des ganzen Bildschirms; `--no-native` verwendet die eigene Aufnahme der App |
| `--clean-status-bar` | 9:41 mit vollem Signal und voller Batterie in einer `--native`-Aufnahme anzeigen |

Mit `--locales` oder `--themes` speichert Metro für jedes Paar ein PNG in einem Ordner, benannt `es.png`, `dark.png` oder `es-dark.png`, wobei die App an Ort und Stelle umgeschaltet und danach wiederhergestellt wird. `--area`, `--scale`, `--store` und `--frame` verwenden die eigene Aufnahme der App.

Geben Sie innerhalb der `metro live`-Shell `screenshot --open` ein. Informationen zu Screenshots aus dem eigenen Code Ihrer App finden Sie unter [Screenshots](/docs/{{ $version }}/screenshots).

<div id="live-commands"></div>

## Live Commands

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Registrieren Sie eigene Befehle, die innerhalb der App laufen, neben den oben genannten integrierten.

<div id="creating-a-live-command"></div>

### Einen Live Command erstellen

``` bash
metro make:command seed_cart --live
```

| Option | Beschreibung |
|--------|-------------|
| `--category`, `-c` | Die Kategorie für den Befehl (Standard: `app`) |
| `--description` | Eine einzeilige Beschreibung, die im `--help` des Befehls angezeigt wird |
| `--force`, `-f` | Erstellt die Datei, auch wenn sie bereits existiert |

Dies erstellt einen `LiveCommand` in `lib/app/commands/`, markiert ihn als `"type": "live"` in `commands.json`, registriert ihn in `lib/bootstrap/live_commands.dart` und verdrahtet `liveCommands: liveCommands` in `nylo.configure(...)` in `app_provider.dart`:

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

`LiveCommand` teilt sich die `builder`/`handle`-Form mit Metros eigenen Befehlen, aber `handle` läuft **innerhalb der App**, sodass es `NyStorage`, `routeTo`, `Auth` und Ihre Models verwenden kann. `package:nylo_framework/live.dart` exportiert `LiveCommand` zusammen mit `Seeder`, `StorageSnapshot`, `LiveException` und `LiveOutput`.

<div id="running-a-live-command"></div>

### Einen Live Command ausführen

Ein neuer Live-Befehl benötigt einen Hot Restart, bevor die laufende App ihn ausführen kann. Dann, im Terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeder

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Ein `Seeder` versetzt Ihre laufende App in einen bekannten Zustand -- angemeldet, Onboarding abgeschlossen, Beispieldatensätze -- und macht dies auch wieder rückgängig.

<div id="creating-a-seeder"></div>

### Einen Seeder erstellen

``` bash
metro make:seeder demo_user
```

| Option | Beschreibung |
|--------|-------------|
| `--description` | Eine einzeilige Beschreibung, die angezeigt wird, wenn `seed` die Seeder auflistet |
| `--force`, `-f` | Erstellt die Datei, auch wenn sie bereits existiert |

Dies erstellt einen `Seeder` in `lib/app/seeders/`, registriert ihn in `lib/bootstrap/seeders.dart` und verdrahtet `seeders: seeders` in `nylo.configure(...)`:

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

Jeder Speicher- und Backpack-Wert, den `up()` ändert, wird automatisch aufgezeichnet, sodass die Standard-`down()` (`restore()`) jeden davon exakt so wiederherstellt, wie er war -- in umgekehrter Reihenfolge, sogar über einen Hot Restart hinweg. Rufen Sie `seed(otherSeeders)` innerhalb eines Seeders auf, um andere als Teil davon auszuführen; deren Änderungen werden ebenfalls aufgezeichnet und zurückgerollt.

<div id="seeding-and-rolling-back"></div>

### Seeden und Rollback

Seeden, Rollback und Exportieren funktionieren nur innerhalb der `metro live`-Shell:

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

`seed` allein listet die registrierten Seeder auf. `--fresh` leert Speicher und Backpack vor dem Seeden, und `--restart` führt danach einen Hot Restart der App aus, sodass sie bereits geseedet startet:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Speicher-Snapshots

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` speichert alles, was die App aktuell in Speicher und Backpack vorhält -- als Seeder oder als portable JSON-Datei -- und `seed <file>` lädt es wieder.

<div id="exporting-a-snapshot"></div>

### Einen Snapshot exportieren

Führen Sie es ohne Namen aus, um eine Vorschau dessen zu erhalten, was erfasst würde:

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

Benennen Sie es, um es als Seeder in `lib/app/seeders/` zu speichern (dasselbe wie `make:seeder`, aber bereits mit den erfassten Werten ausgefüllt):

``` plaintext
› export pro_user
```

Oder schreiben Sie es stattdessen in eine Datei:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Option | Beschreibung |
|--------|-------------|
| `--to <path>` | Den Snapshot in diese JSON-Datei schreiben statt in einen Seeder |
| `--description` | Eine einzeilige Beschreibung für den Seeder |
| `--only <keys>` | Kommagetrennte Schlüssel zum Exportieren; `*` erfasst alles, z. B. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Kommagetrennte Schlüssel, die ausgelassen werden, z. B. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Backpack-Werte einschließen (Standard: eingeschlossen) |
| `--force`, `-f` | Einen bereits vorhandenen Seeder oder eine vorhandene Datei ersetzen |

Ein `Model`, oder jede Klasse mit einem registrierten Decoder, wird mit dem Klassennamen markiert, sodass es über seinen eigenen Decoder zurückkommt statt als einfache Map. `export` warnt, wenn der Snapshot Schlüssel enthält, die wie Zugangsdaten aussehen, und wenn er groß genug ist, dass Sie wahrscheinlich `--except` verwenden möchten.

<div id="loading-a-snapshot"></div>

### Einen Snapshot laden

``` plaintext
› seed snapshots/pro_user.json
```

Das Laden einer Datei wird genauso aufgezeichnet wie bei einem Seeder, sodass `seed:rollback <name>` (der Dateiname, oder was auch immer `--as` vergeben hat) es ebenfalls rückgängig macht. Aus Code heraus kann ein Seeder einen erfassten Snapshot mit `importSnapshot` anwenden:

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

`export` schreibt genau diese Struktur, sodass eine mit `--to` geschriebene Datei direkt in das `snapshot`-Feld eines Seeders eingefügt werden kann.

<div id="page-actions"></div>

## Seiten-Aktionen

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` auf einer Seite liefert Ihnen eine `PageStateActions`, um diese Seite von außerhalb aufzurufen, ohne einen `stateAction(...)`-Aufruf von Hand zu bauen:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Siehe [Aktionen einer Seite aufrufen](/docs/{{ $version }}/state-management#page-actions-shortcut) für die vollständige Liste der integrierten Aktionen und wie Sie eigene typisierte Aktionen deklarieren. Nylo Live liest und verwendet dieselben Aktionen: `metro live:run data` listet auf, was eine angezeigte Seite unter `actions` bereitstellt, und `metro live:run state` sendet einer Seite Daten direkt anhand ihres State-Namens.
