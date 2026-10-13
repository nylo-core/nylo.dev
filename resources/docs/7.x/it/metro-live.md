# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione a Nylo Live")
- La Shell metro live
  - [Opzioni Condivise](#shared-options "Opzioni accettate da ogni comando live")
  - [Completamento Tab e Cronologia](#tab-completion-and-history "Completamento tab e cronologia")
  - [Eseguire uno Script](#running-a-script "Eseguire uno script .live")
- [live:devices](#live-devices "Elenca le app in esecuzione")
- [live:status](#live-status "Mostra lo stato dell'app")
- [live:run](#live-run "Eseguire un comando live integrato")
- [Playbook](#playbooks "Eseguire i playbook da Metro")
- Comandi Live
  - [Creare un Comando Live](#creating-a-live-command "Creare un comando live")
  - [Eseguire un Comando Live](#running-a-live-command "Eseguire un comando live")
- Seeder
  - [Creare un Seeder](#creating-a-seeder "Creare un seeder")
  - [Seeding e Rollback](#seeding-and-rolling-back "Eseguire il seeding e il rollback")
- Snapshot dello Storage
  - [Esportare uno Snapshot](#exporting-a-snapshot "Esportare uno snapshot")
  - [Caricare uno Snapshot](#loading-a-snapshot "Caricare uno snapshot")
- [Azioni della Pagina](#page-actions "Chiamare una pagina dall'esterno")

<div id="introduction"></div>

## Introduzione

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** consente a Metro di accedere alla tua app mentre e' in esecuzione in modalita' debug (o profile), tramite il Dart VM service. Trova l'app attraverso il Dart Tooling Daemon senza alcuna configurazione, e si connette solo alle app compilate dal progetto corrente.

Nylo Live e' attivo per impostazione predefinita nelle build debug e profile; una build release non registra nulla. I comandi che si limitano a leggere l'app (`live:status`, `live:run data`, `live:run routes`, ...) funzionano anche nelle build profile. I comandi che modificano l'app (`route`, `storage`, `auth`, seeder, ...) richiedono una build debug.

Disattivalo tu stesso, prima che qualsiasi altra cosa venga eseguita, da un provider:

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

## La Shell metro live

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Apri una shell connessa alla tua app in esecuzione:

``` bash
metro live
```

Nylo Live individua le app in esecuzione del progetto e si connette a una di esse -- ti chiede di scegliere quando ce n'e' piu' di una in esecuzione. Una volta connesso, il prompt mostra il dispositivo e la route attualmente visualizzata:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

I comandi live perdono il prefisso `metro live:` all'interno della shell -- `status` invece di `metro live:status`, `route /profile` invece di `metro live:run route /profile`. Alcuni comandi funzionano solo all'interno della shell: `seed`, `seed:rollback`, `export`, `reload` e `restart`. Digita `help` per elencare tutti i comandi, oppure `help <command>` per vedere le opzioni di un comando specifico. Digita `exit` (o Ctrl+D) per uscire.

Se l'app si riavvia mentre sei connesso, la shell si riconnette automaticamente.

<div id="shared-options"></div>

### Opzioni Condivise

Ogni comando live -- all'interno della shell o come `metro live:*` -- accetta le stesse opzioni:

| Opzione | Descrizione |
|--------|-------------|
| `-d, --device <#\|name>` | Seleziona un'app tramite il suo numero in `metro live:devices` o il nome del dispositivo |
| `--all` | Esegui su ogni app in esecuzione di questo progetto |
| `--json` | Stampa JSON leggibile da una macchina |
| `--uri` | Usa questo indirizzo del VM service invece di scoprire le app |
| `--timeout` | Secondi di attesa per ogni app durante la scoperta (predefinito `3`) |

Un valore di opzione scritto come `@path` viene letto da quel file -- utile per un payload `--data` di grandi dimensioni:

``` bash
metro live:run route /profile --data @user.json
```

Inizia il valore con `@@` quando inizia realmente con `@`.

<div id="tab-completion-and-history"></div>

### Completamento Tab e Cronologia

All'interno della shell, Tab completa nomi dei comandi, opzioni, route, chiavi di storage e Backpack, nomi dei seeder e nomi dei playbook in base all'app connessa. La cronologia viene mantenuta in `.dart_tool/nylo/live_history` e persiste tra le sessioni.

<div id="running-a-script"></div>

### Eseguire uno Script

Fai un pipe di un file in `metro live` per eseguirlo come script, un comando per riga, fermandosi al primo comando che fallisce:

``` bash
metro live < seeds/demo.live
```

Le righe vuote e quelle che iniziano con `#` vengono saltate.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Elenca le app in esecuzione di questo progetto, numerate per `-d`:

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

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Mostra l'app, il dispositivo, la modalita' di build, la route e lo stack correnti, la locale, il tema, e se un utente e' autenticato:

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

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Esegue un comando integrato che ispeziona o pilota l'app:

``` bash
metro live:run <command> [arguments]
```

| Comando | Descrizione |
|---------|-------------|
| `data` | Mostra i dati e i campi della pagina a schermo |
| `routes` | Elenca le route registrate |
| `route` | Apre una pagina, con `--data` opzionale |
| `back` | Torna indietro di una pagina, o a una route |
| `deeplink` | Apre un deep link, o mostra come sono configurati |
| `storage` | Elenca lo storage locale, oppure mostra, salva o elimina un valore |
| `storage:clear` | Cancella lo storage locale, con `--keep` opzionale |
| `backpack` | Elenca i valori di Backpack, oppure mostra, salva o eliminane uno |
| `auth` | Mostra chi e' autenticato, oppure esegue login e logout |
| `locale` | Cambia la lingua dell'app |
| `theme` | Cambia il tema dell'app |
| `state` | Invia dati a uno stato con `updateState` |
| `event` | Attiva uno dei tuoi eventi |
| `toast` | Mostra una notifica toast |
| `playbook` | Esegue i tuoi [playbook](/docs/{{ $version }}/playbooks), oppure li elenca |

All'interno di `metro live`, ometti la parte `live:run` -- `route /profile` invece di `metro live:run route /profile`:

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

`data` legge anche i campi dichiarati dallo stato della tua pagina, direttamente dall'app in esecuzione -- non esiste reflection in Flutter, quindi funziona solo per uno stato che e' effettivamente a schermo:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbook

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Esegui uno o piu' [playbook](/docs/{{ $version }}/playbooks) sulla tua app in esecuzione. Ogni step viene stampato man mano che avviene:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Indica piu' playbook per eseguirli uno dopo l'altro. Senza nomi, o con `--list`, il comando elenca i playbook dell'app.

| Opzione | Descrizione |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Esegue a questo ritmo invece di quello proprio di ogni playbook |
| `--data <json>` | Un oggetto JSON che ogni playbook dell'esecuzione legge in `data`, sopra i suoi valori predefiniti |
| `--no-overlay` | Nasconde il dito e la didascalia dello step |
| `--loop` | Riesegue i playbook all'infinito finche' non premi Ctrl+C |
| `--list` | Elenca i playbook dell'app invece di eseguirne uno |

Ctrl+C chiede all'app di fermarsi: gli step rimanenti vengono saltati, ma `tearDown` viene comunque eseguito. Premi Ctrl+C una seconda volta per smettere di attendere.

All'interno della shell `metro live` il comando e' `playbook <name>`, e Tab completa i nomi dei playbook:

``` plaintext
› playbook sign_up --speed fast
```

<div id="playbook-live-commands"></div>

### Riferimento dei Live Command

I comandi alla base sono `playbooks.list`, `playbooks.run` e `playbooks.stop`. Un'esecuzione restituisce subito il proprio id e trasmette ogni step come evento `nylo.playbook`, quindi puo' superare il timeout di un comando. `live:status` elenca i playbook dell'app. I playbook sono arrivati nella versione 9 del protocollo Nylo Live.

<div id="live-commands"></div>

## Comandi Live

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Registra i tuoi comandi personalizzati da eseguire all'interno dell'app, insieme a quelli integrati sopra.

<div id="creating-a-live-command"></div>

### Creare un Comando Live

``` bash
metro make:command seed_cart --live
```

| Opzione | Descrizione |
|--------|-------------|
| `--category`, `-c` | La categoria per il comando (predefinito: `app`) |
| `--description` | Una descrizione di una riga mostrata dal `--help` del comando |
| `--force`, `-f` | Crea il file anche se esiste gia' |

Questo crea un `LiveCommand` in `lib/app/commands/`, lo contrassegna come `"type": "live"` in `commands.json`, lo registra in `lib/bootstrap/live_commands.dart`, e collega `liveCommands: liveCommands` in `nylo.configure(...)` in `app_provider.dart`:

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

`LiveCommand` condivide la struttura `builder`/`handle` con i comandi nativi di Metro, ma `handle` viene eseguito **all'interno dell'app**, quindi puo' usare `NyStorage`, `routeTo`, `Auth` e i tuoi modelli. `package:nylo_framework/live.dart` esporta `LiveCommand` insieme a `Seeder`, `StorageSnapshot`, `LiveException` e `LiveOutput`.

<div id="running-a-live-command"></div>

### Eseguire un Comando Live

Un nuovo comando live richiede un hot restart prima che l'app in esecuzione possa eseguirlo. Poi, dal terminale:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeder

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Un `Seeder` porta la tua app in esecuzione in uno stato noto -- autenticato, onboarding completato, record di esempio -- e la riporta indietro.

<div id="creating-a-seeder"></div>

### Creare un Seeder

``` bash
metro make:seeder demo_user
```

| Opzione | Descrizione |
|--------|-------------|
| `--description` | Una descrizione di una riga, mostrata quando `seed` elenca i seeder |
| `--force`, `-f` | Crea il file anche se esiste gia' |

Questo crea un `Seeder` in `lib/app/seeders/`, lo registra in `lib/bootstrap/seeders.dart`, e collega `seeders: seeders` in `nylo.configure(...)`:

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

Ogni valore di storage e Backpack modificato da `up()` viene registrato automaticamente, quindi il `down()` predefinito (`restore()`) riporta ciascuno esattamente come era, in ordine inverso -- anche dopo un hot restart. Chiama `seed(otherSeeders)` dall'interno di un seeder per eseguirne altri come parte di esso; anche le loro modifiche vengono registrate e annullate.

<div id="seeding-and-rolling-back"></div>

### Seeding e Rollback

Il seeding, il rollback e l'esportazione funzionano solo all'interno della shell `metro live`:

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

`seed` da solo elenca i seeder registrati. `--fresh` cancella storage e Backpack prima del seeding, e `--restart` esegue un hot restart dell'app subito dopo, cosi' si avvia gia' seedata:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Snapshot dello Storage

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` salva tutto cio' che l'app contiene attualmente in storage e Backpack -- come seeder, o come file JSON portabile -- e `seed <file>` lo ricarica.

<div id="exporting-a-snapshot"></div>

### Esportare uno Snapshot

Eseguilo senza nome per vedere in anteprima cosa verrebbe catturato:

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

Assegnagli un nome per salvarlo come seeder in `lib/app/seeders/` (lo stesso di `make:seeder`, ma gia' precompilato con i valori catturati):

``` plaintext
› export pro_user
```

Oppure scrivilo su un file:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Opzione | Descrizione |
|--------|-------------|
| `--to <path>` | Scrive lo snapshot in questo file JSON invece che in un seeder |
| `--description` | Una descrizione di una riga per il seeder |
| `--only <keys>` | Chiavi separate da virgola da esportare; `*` corrisponde a qualsiasi cosa, es. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Chiavi separate da virgola da escludere, es. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Include i valori di Backpack (predefinito: incluso) |
| `--force`, `-f` | Sostituisce un seeder o un file gia' esistente |

Un `Model`, o qualsiasi classe con un decoder registrato, viene contrassegnato con il nome della classe cosi' torna indietro tramite il proprio decoder invece che come semplice map. `export` avvisa quando lo snapshot contiene chiavi che sembrano credenziali, e quando e' abbastanza grande da far pensare che ti serva `--except`.

<div id="loading-a-snapshot"></div>

### Caricare uno Snapshot

``` plaintext
› seed snapshots/pro_user.json
```

Il caricamento di un file viene registrato allo stesso modo di un seeder, quindi anche `seed:rollback <name>` (il nome del file, o quello assegnato da `--as`) lo annulla. Dal codice, un seeder puo' applicare uno snapshot catturato con `importSnapshot`:

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

`export` scrive esattamente questa struttura, quindi un file scritto con `--to` puo' essere incollato direttamente nel campo `snapshot` di un seeder.

<div id="page-actions"></div>

## Azioni della Pagina

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` su una pagina ti fornisce un `PageStateActions` per chiamare quella pagina dall'esterno, senza costruire manualmente una chiamata `stateAction(...)`:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Consulta [Chiamare le Azioni di una Pagina](/docs/{{ $version }}/state-management#page-actions-shortcut) per l'elenco completo delle azioni integrate e come dichiarare azioni tipizzate personalizzate. Nylo Live legge e usa le stesse azioni: `metro live:run data` elenca cio' che una pagina a schermo espone sotto `actions`, e `metro live:run state` invia dati a una pagina direttamente tramite il nome del suo stato.
