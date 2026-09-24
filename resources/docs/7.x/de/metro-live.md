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

Live-Befehle verlieren innerhalb der Shell ihr `metro live:`-Präfix -- `status` statt `metro live:status`, `route /profile` statt `metro live:run route /profile`. Einige Befehle laufen nur innerhalb der Shell: `seed`, `seed:rollback`, `export`, `reload` und `restart`. Geben Sie `help` ein, um alle Befehle aufzulisten, oder `help <command>`, um die Optionen eines Befehls zu sehen. Geben Sie `exit` ein (oder Ctrl+D), um die Shell zu verlassen.

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

Innerhalb der Shell vervollständigt Tab Befehlsnamen, Optionen, Routen, Speicher- und Backpack-Schlüssel sowie Seeder-Namen anhand der verbundenen App. Der Verlauf wird in `.dart_tool/nylo/live_history` gespeichert und bleibt über Sitzungen hinweg erhalten.

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
