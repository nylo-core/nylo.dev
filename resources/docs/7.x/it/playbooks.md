# Playbook
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione ai Playbook")
- [Creare un Playbook](#creating-a-playbook "Creare un playbook")
- [Step](#steps "Gli step che un playbook puo' eseguire")
- [Target](#targets "Trovare elementi sullo schermo")
  - [Stringhe Semplici](#plain-strings "Come viene associata una stringa semplice")
  - [Find](#find "Target precisi con Find")
- [Dati e Setup](#data-and-setup "Dati, setUp e tearDown")
- [Eseguire Altri Playbook](#playing-other-playbooks "Eseguire un playbook da un altro playbook")
- [Velocita'](#speed "PlaybookSpeed")
- [Dito e Didascalia](#finger-and-caption "Il dito e la didascalia dello step")
- [Registrare i Playbook](#registering-playbooks "Registrare i playbook in Nylo")
- [Eseguire un Playbook](#running-a-playbook "Eseguire un playbook")
  - [Da Metro](#running-from-metro "Esecuzione da Metro")
  - [All'Avvio](#running-on-boot "Esecuzione all'avvio")
  - [Da Dart](#running-from-dart "Esecuzione da Dart")
- [Report](#reports "PlaybookReport")
- [Build di Release](#release-builds "I playbook nelle build di release")
- [Testing](#testing "Testare un playbook")

<div id="introduction"></div>

## Introduzione

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Un **playbook** e' una classe che elenca i tap, la digitazione, lo scorrimento e i cambi di pagina di un percorso utente. La tua app in esecuzione li esegue su se stessa, e puo' mostrare un dito e una didascalia per ogni step -- utile per demo, registrazioni dello schermo e per verificare un percorso end to end.

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

Importa `package:nylo_framework/playbook.dart` ovunque scrivi o registri playbook.

<div id="creating-a-playbook"></div>

## Creare un Playbook

Usa Metro per crearne uno:

``` bash
metro make:playbook sign_up
```

Questo crea `lib/app/playbooks/sign_up_playbook.dart` e lo registra nella tua app. Vedi [Make Playbook](/docs/{{ $version }}/metro#make-playbook) per le opzioni del comando.

<div id="steps"></div>

## Step

Gli `steps` di un playbook si costruiscono con i verbi che eredita:

| Step | Descrizione |
|------|-------------|
| `tap(target)` | Tocca un target |
| `longPress(target)` | Tiene premuto un target |
| `type(target, text)` | Digita in un campo. Lo svuota prima a meno di `clear: false`; `submit: true` preme il pulsante di azione della tastiera; `secret: true` nasconde il testo nella didascalia e nel report |
| `scrollTo(target)` | Scorre finche' un target e' sullo schermo. Passa `within:` per scorrere uno scrollable specifico |
| `see(target)` | Verifica che un target sia sullo schermo, attendendo che compaia |
| `dontSee(target)` | Verifica che un target non sia sullo schermo, attendendo che scompaia |
| `waitFor(target)` | Attende un target, fino a `timeout` (10 secondi per impostazione predefinita) |
| `waitForPage(path)` | Attende la pagina a un route path o `RouteView` (es. `HomePage.path`) |
| `visit(path)` | Apre una pagina tramite route path o `RouteView`, con `data:` opzionale |
| `back()` | Torna alla pagina precedente |
| `pause(seconds: 1)` | Resta fermo, ad es. per lasciare respirare una demo |
| `step(title, action)` | Esegue codice qualsiasi con gli stessi verbi, tramite un `PlaybookActor` |
| `scene(title, steps)` | Raggruppa gli step sotto un titolo; le loro didascalie iniziano con esso e i loro risultati sono annidati nel report |
| `play(playbook)` | Esegue un altro playbook -- vedi [Eseguire Altri Playbook](#playing-other-playbooks) |

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

Gli step vengono eseguiti in ordine e si fermano al primo fallimento. Gli step rimanenti vengono segnalati come saltati.

<div id="targets"></div>

## Target

La maggior parte degli step accetta un target: cosa toccare, in cosa digitare o cosa cercare.

<div id="plain-strings"></div>

### Stringhe Semplici

Una stringa semplice viene associata come la leggerebbe una persona sullo schermo. Prova in quest'ordine:

1. Testo visibile
2. L'hint o l'etichetta di un campo
3. Un tooltip
4. Un'etichetta semantics
5. Una `ValueKey`
6. Testo che contiene la stringa

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

Anche una `Key`, un `Type` o un `IconData` possono essere passati direttamente a uno step.

<div id="find"></div>

### Find

Usa `Find` quando una stringa semplice non e' abbastanza precisa:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Corrisponde a |
|--------|---------|
| `Find.text(text)` | Testo visibile |
| `Find.textContaining(text)` | Testo che contiene la stringa |
| `Find.icon(icon)` | Un'icona |
| `Find.type<T>()` | Un widget di tipo `T` |
| `Find.key(key)` | Un widget con la key |
| `Find.field(name)` | Un campo `NyForm` per nome |
| `Find.input(text)` | Un campo di testo per hint, etichetta o placeholder |
| `Find.tooltip(message)` | Un tooltip |
| `Find.semantics(label)` | Un'etichetta semantics |
| `Find.where(test)` | Widget che superano il tuo test |

Restringi qualsiasi finder con `.inside(ancestor)`, `.at(index)` (si conta da 0), `.first` o `.last`.

Un tap viene rifiutato quando qualcosa di non correlato, come la barriera di un dialog, copre il suo target.

<div id="data-and-setup"></div>

## Dati e Setup

`defaults` contiene i dati con cui partono gli step. Leggili tramite `data` dentro `steps`. `setUp` viene eseguito prima degli step e `tearDown` dopo di essi, anche quando uno step e' fallito:

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

`seed([...])` esegue i [Seeder](/docs/{{ $version }}/metro-live#seeders) (istanze o nomi registrati) e li registra come `seed` in `metro live`.

<div id="playing-other-playbooks"></div>

## Eseguire Altri Playbook

`play()` esegue un altro playbook per intero, con i propri `setUp` e `tearDown`. Passa un'istanza o il nome con cui e' registrato:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Dati** vengono uniti in quest'ordine: i `defaults` del figlio, poi i `data` del genitore, poi i `data:` passati a `play`.
- **Velocita'** viene ereditata dal genitore a meno che non passi `speed:`.
- **Risultati** vengono annidati sotto lo step nel report.
- **Un figlio che fallisce** fa fallire lo step.
- **I cicli** vengono rifiutati e i playbook possono annidarsi per al massimo 8 livelli.

<div id="speed"></div>

## Velocita'

`PlaybookSpeed` imposta il ritmo di ogni attesa:

| Velocita' | Descrizione |
|-------|-------------|
| `PlaybookSpeed.instant` | Nessuna attesa oltre a quella necessaria per lo scorrimento. Pensato per i test |
| `PlaybookSpeed.fast` | Veloce, ma ancora facile da seguire |
| `PlaybookSpeed.natural` | Il ritmo di una persona che usa l'app. Il valore predefinito |
| `PlaybookSpeed.slow` | Abbastanza lento da poterci narrare sopra |

Scala qualsiasi velocita' con `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // lento il doppio
```

Una velocita' passata a `run()`, `--speed` da Metro, o la velocita' di un playbook che esegue questo ha la precedenza su quella del playbook stesso.

<div id="finger-and-caption"></div>

## Dito e Didascalia

Il dito e le didascalie degli step sono disattivati per impostazione predefinita. Sovrascrivi `showFinger` e `caption` per attivarli:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // oppure .top
```

`showFinger` e' `false` per impostazione predefinita e `caption` e' `PlaybookCaption.hidden`; `PlaybookCaption` e' `top`, `bottom` o `hidden`. I tap e gli swipe avvengono in entrambi i casi. Un playbook eseguito da questo con `play()` usa la propria impostazione, e quella di questo ritorna al termine. Per nascondere entrambi durante un'esecuzione, usa `--no-overlay` da Metro o `run(overlay: false)` da Dart.

Prima di nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0), il dito e la didascalia in basso erano attivi per impostazione predefinita. Aggiungi gli override qui sopra per mantenere quell'aspetto.

<div id="registering-playbooks"></div>

## Registrare i Playbook

Registra i tuoi playbook cosi' che Metro e `play('name')` possano trovarli:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` restituisce cio' che e' registrato. Puoi anche passarli mentre configuri Nylo nel tuo `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` lo fa per te. Mantiene il registro in `lib/bootstrap/playbooks.dart`, vi aggiunge ogni nuovo playbook e passa `playbooks` a `nylo.configure(...)`. Nelle build di release il registro e' una mappa vuota:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Un playbook registrato viene eseguito con la sua chiave. Uno non registrato viene eseguito con il suo `name`, o con il nome della classe in snake case (`SignUpPlaybook` diventa `sign_up`).

<div id="running-a-playbook"></div>

## Eseguire un Playbook

<div id="running-from-metro"></div>

### Da Metro

Con la tua app in esecuzione in modalita' debug:

``` bash
metro live:run playbook sign_up --speed fast
```

All'interno di `metro live`, digita `playbook sign_up`. Vedi [Metro Live](/docs/{{ $version }}/metro-live#playbooks) per tutte le opzioni. Aggiungi `--record` per salvare un video dell'esecuzione; vedi [Registrazione](/docs/{{ $version }}/metro-live#recording-playbooks).

<div id="running-on-boot"></div>

### All'Avvio

Aggiungi queste chiavi al tuo file `.env` per eseguire i playbook appena l'app e' sullo schermo:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` e' un elenco di nomi di playbook separati da virgole e `PLAYBOOK_SPEED` e' uno tra `instant`, `fast`, `natural` o `slow`. Entrambi valgono solo nelle build debug e profile.

<div id="running-from-dart"></div>

### Da Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` accetta `speed`, `data`, `clock`, `overlay` (predefinito `true`) e `onEvent`. Uno step che fallisce non lancia eccezioni -- controlla `report.passed`. Solo un playbook alla volta puo' essere in esecuzione; usa `play()` per eseguirne uno dentro un altro.

Ferma un playbook in esecuzione con `Playbook.stop()`. Gli step rimanenti vengono saltati ma `tearDown` viene comunque eseguito. `Playbook.isRunning` indica se ne e' in esecuzione uno.

<div id="reports"></div>

## Report

`run()` restituisce un `PlaybookReport` con il `name` del playbook, la `speed`, il tempo `elapsed`, se e' stato fermato (`stopped`) e una lista `steps` di `PlaybookStepResult` (ognuno con `title`, `status`, `elapsed`, `error` e `steps` annidati). `report.passed` e' true quando ogni step e' passato, e stampare un report si legge come l'output di Metro. `report.toJson()` fornisce gli stessi dati come JSON.

<div id="release-builds"></div>

## Build di Release

I playbook non vengono mai eseguiti in una build di release, cosi' un percorso non puo' pilotare l'app di un utente:

- `Playbook.run()` lancia un `UnsupportedError`.
- `addPlaybooks` viene ignorato.
- Il registro `lib/bootstrap/playbooks.dart` generato da Metro e' vuoto.

`Playbook.isAvailable` indica se la build corrente puo' eseguire i playbook.

<div id="testing"></div>

## Testing

Esegui un playbook in un widget test con `NyTesterClock`, poi verifica il suo report con `expectPlaybookPassed`:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Vedi [Testare i Playbook](/docs/{{ $version }}/testing#testing-playbooks) per i test di integrazione.
