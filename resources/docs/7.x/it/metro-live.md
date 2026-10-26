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
- [Registrare un Percorso](#recording-a-journey "Registrare cio' che fai come playbook")
- [Screenshot](#screenshots "Salvare screenshot dell'app in esecuzione")
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

I comandi live perdono il prefisso `metro live:` all'interno della shell -- `status` invece di `metro live:status`, `route /profile` invece di `metro live:run route /profile`. Alcuni comandi funzionano solo all'interno della shell: `seed`, `seed:rollback`, `export`, `record`, `reload` e `restart`. Digita `help` per elencare tutti i comandi, oppure `help <command>` per vedere le opzioni di un comando specifico. Digita `exit` (o Ctrl+D) per uscire.

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
| `screenshot` | Salva uno [screenshot](#screenshots) dell'app, o di una singola `ScreenshotArea` |

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
| `--no-overlay` | Nasconde il conto alla rovescia, e il dito e le didascalie dei playbook che li mostrano |
| `--loop` | Riesegue i playbook all'infinito finche' non premi Ctrl+C |
| `--record[=<file>]` | Registra lo schermo del dispositivo durante l'esecuzione. Salvato in `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (o `.gif`) sceglie il file, relativo al progetto. Richiede ffmpeg |
| `--no-captions` | Esclude il file di didascalie `.vtt` e i capitoli di una registrazione |
| `--clean-status-bar` | Mostra 9:41 con segnale e batteria al massimo mentre i playbook vengono eseguiti, poi lo ripristina. Funziona anche senza `--record` |
| `--countdown <seconds>` | Mostra un conto alla rovescia di 0-10 secondi sullo schermo prima del primo step |
| `--screenshots[=<mode>]` | Salva anche lo schermo di ogni step. `<mode>` e' `all`, `auto`, `marked`, `failures` oppure `off`; vedi [Screenshot dei Playbook](#playbook-screenshots) |
| `--scale <n>` | La dimensione degli screenshot rispetto a quella del dispositivo, fino a 4, ad es. `0.5` per la meta' |
| `--locales <list>` | Esegue una volta in ciascuna di queste lingue, ad es. `en,es`, oppure `all` quelle dell'app |
| `--themes <list>` | Esegue una volta in ciascuno di questi temi, ad es. `light,dark`, oppure `all` quelli dell'app |
| `--store <id>` | Disegna gli screenshot alla dimensione di un app store, ad es. `ios-6.9` |
| `--frame` | Disegna ogni schermata dentro il contorno di un telefono |
| `--background <hex>` | Il colore intorno alla schermata, ad es. `"#1B2027"` |
| `--list` | Elenca i playbook dell'app invece di eseguirne uno |

Ctrl+C chiede all'app di fermarsi: gli step rimanenti vengono saltati, ma `tearDown` viene comunque eseguito. Premi Ctrl+C una seconda volta per smettere di attendere.

Se nessuno step raggiunge Metro entro 20 secondi, Metro smette di attendere e fa fallire l'esecuzione. L'app sta eseguendo il playbook, ma i suoi eventi non arrivano, cosa che accade quando l'app e' stata avviata con `flutter run --no-dds`. Eseguila con un semplice `flutter run` e riprova.

All'interno della shell `metro live` il comando e' `playbook <name>`, e Tab completa i nomi dei playbook:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

<!-- uncertain: newly added Recording section; no prior locale context for register or terminology (e.g. chapters, status bar) -->

### Registrazione

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Aggiungi `--record` per salvare un video dell'esecuzione:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

All'interno della shell `metro live`, digita `playbook sign_up --record`.

La registrazione richiede ffmpeg (ad esempio `brew install ffmpeg`). Metro lo verifica prima che parta qualsiasi cosa.

- **iOS Simulator** -- registrato con `xcrun simctl`.
- **Emulatore o dispositivo Android** -- registrato con `adb screenrecord`, che si ferma dopo 3 minuti, oppure con `scrcpy` se e' installato (nessun limite, e registra l'audio su Android 11+). Se sono collegati piu' dispositivi Android e Metro non riesce a capire quale esegue l'app, imposta `ANDROID_SERIAL`.
- **iPhone fisico** -- non puo' essere registrato dalla riga di comando. Registra con QuickTime o con il Centro di Controllo e usa `--countdown 3` per avere il tempo di iniziare.

Il risultato e' un `.mp4` (H.264, 60 fps costanti) che dura esattamente quanto l'esecuzione, oppure una `.gif` larga 480px a 15 fps. Ogni step diventa una didascalia in un file `.vtt` accanto al `.mp4` e un capitolo al suo interno. Vengono generati dagli step dell'esecuzione, indipendentemente dal fatto che il playbook mostri le didascalie a schermo. Passa `--no-captions` per ometterli.

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

Un playbook che fallisce o viene interrotto viene comunque salvato, quindi una registrazione funge anche da segnalazione di bug. Ctrl+C ferma il playbook (`tearDown` viene comunque eseguito) e la registrazione viene salvata comunque.

`--clean-status-bar` mantiene una barra di stato che hai gia' impostato con `simctl status_bar` sul Simulator. Su Android usa la modalita' demo e la disattiva alla fine. Con piu' app (`--all`), ogni dispositivo ottiene il proprio file con il nome del dispositivo.

Un playbook nasconde per impostazione predefinita il dito e le didascalie. Per un video dimostrativo, imposta `showFinger` e `caption` sul playbook -- vedi [Dito e Didascalia](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Screenshot

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Gli [step `screenshot()`](/docs/{{ $version }}/playbooks#screenshots) di un playbook vengono salvati in `screenshots/<playbook>/` senza bisogno di alcun flag. Aggiungi opzioni per catturarli in ogni lingua e tema, o per dimensionarli per la scheda di uno store:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` salva anche lo schermo dopo ogni step di un playbook che non ha step `screenshot()`, e `--screenshots=all` lo fa per ogni playbook. Lo schermo di uno step fallito viene salvato in `build/playbooks/failures`; `--screenshots=off` lo disattiva.

`--locales` e `--themes` eseguono il playbook una volta in ciascuno, e la lingua e il tema dell'app vengono poi ripristinati. `--store` senza valore elenca le dimensioni, e `--frame` disegna ogni schermata dentro il contorno di un telefono con il `caption:` dello step sopra. `--clean-status-bar` mostra 9:41 con segnale e batteria al massimo nelle catture che Metro effettua con l'iOS Simulator o `adb`.

<div id="playbook-live-commands"></div>

### Riferimento dei Live Command

I comandi alla base sono `playbooks.list`, `playbooks.run` e `playbooks.stop`. Un'esecuzione restituisce subito il proprio id e trasmette ogni step come evento `nylo.playbook`, quindi puo' superare il timeout di un comando. `live:status` elenca i playbook dell'app. I playbook sono arrivati nella versione 9 del protocollo Nylo Live. Il supporto alla registrazione -- `countdown` in `playbooks.run` (con un evento `countdown` ogni secondo) e `simulator` (l'UDID dell'iOS Simulator) in `status` -- e' arrivato nella versione 10 del protocollo.

<div id="recording-a-journey"></div>

## Registrare un Percorso

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

<!-- uncertain: new section; "record" and "hot restart" kept in English -->
`record` scrive cio' che fai nell'app in esecuzione come [playbook](/docs/{{ $version }}/playbooks#recording-a-playbook). Funziona solo all'interno della shell `metro live`:

``` plaintext
› record sign_up
```

Usa l'app e premi Invio quando il percorso e' finito. Il playbook viene creato in `lib/app/playbooks/sign_up_playbook.dart`, registrato in `lib/bootstrap/playbooks.dart`, e Metro propone di fare un hot restart dell'app e di eseguirlo a velocita' `fast`.

| Opzione | Descrizione |
|--------|-------------|
| `--description <text>` | Una descrizione di una riga, mostrata quando Metro elenca i playbook |
| `--no-data` | Scrive i valori digitati cosi' come sono stati digitati, invece dei segnaposto `data[...]` con i loro valori predefiniti |
| `--no-replay` | Salta la domanda sull'esecuzione successiva |
| `--force`, `-f` | Sostituisce un file di playbook gia' esistente |

Durante la registrazione, digita `a` e Invio per rendere il prossimo tap un `see()`, `d` per un `dontSee()`, `s <title>` per iniziare una scena, `u` per annullare l'ultimo step e `p` per mettere in pausa. Ctrl+C scarta la registrazione. `metro make:playbook sign_up --record` apre la shell e avvia la registrazione in un colpo solo.

I comandi alla base sono `record.start`, `record.stop`, `record.undo`, `record.mark` e `record.status`. Una registrazione trasmette ogni step come evento `nylo.record`. La registrazione e' arrivata nella versione 11 del protocollo Nylo Live.

<div id="screenshots"></div>

## Screenshot

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Salva un PNG della tua app in esecuzione:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Ogni file viene salvato in `screenshots/` nel tuo progetto, con il nome `<mode>-<device>-<date>-<time>.png`. Su iOS Simulator e Android, Metro cattura l'intero schermo del dispositivo con il Simulator o `adb`, quindi la barra di stato e la tastiera sono incluse. Qualsiasi altro dispositivo usa la cattura propria dell'app, cioe' le pagine dell'app come le vede l'utente.

| Opzione | Descrizione |
|--------|-------------|
| `--area <name>` | Cattura solo la `ScreenshotArea` con questo nome |
| `--scale <n>` | La dimensione rispetto a quella del dispositivo, fino a 4, ad es. `0.5` per la meta' |
| `--locales <list>` | Cattura lo schermo in ciascuna di queste lingue, ad es. `en,es`, oppure `all` quelle dell'app |
| `--themes <list>` | Cattura lo schermo in ciascuno di questi temi, ad es. `light,dark`, oppure `all` quelli dell'app |
| `--store <id>` | Disegna alla dimensione di screenshot di un app store, ad es. `ios-6.9`. Da solo, `--store` elenca le dimensioni |
| `--frame` | Disegna lo schermo dentro il contorno di un telefono, su uno sfondo |
| `--caption <text>` | Testo sopra il `--frame`, o una chiave di traduzione |
| `--background <hex>` | Il colore intorno allo schermo, dietro un `--frame` o accanto a una dimensione `--store` di forma diversa, ad es. `"#1B2027"` |
| `--out <path>` | Salva in questo `.png`, o in una cartella che termina con `/` (predefinito: `screenshots/`) |
| `--open` | Apre lo screenshot dopo il salvataggio |
| `--copy` | Copia lo screenshot negli appunti |
| `--native` | Cattura con l'iOS Simulator o `adb`. E' il comportamento predefinito per una cattura a schermo intero; `--no-native` usa la cattura propria dell'app |
| `--clean-status-bar` | Mostra 9:41 con segnale e batteria al massimo in una cattura `--native` |

Con `--locales` o `--themes`, Metro salva un PNG per ogni coppia in una cartella, con nomi come `es.png`, `dark.png` o `es-dark.png`, cambiando l'app sul posto e ripristinandola poi. `--area`, `--scale`, `--store` e `--frame` usano la cattura propria dell'app.

Dentro la shell `metro live`, digita `screenshot --open`. Per fare screenshot dal codice della tua app, vedi [Screenshot](/docs/{{ $version }}/screenshots).

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
