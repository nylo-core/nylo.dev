# Playbooki
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do playbooków")
- [Tworzenie playbooka](#creating-a-playbook "Tworzenie playbooka")
- [Kroki](#steps "Kroki, które może wykonać playbook")
- [Cele](#targets "Znajdowanie elementów na ekranie")
  - [Zwykłe ciągi znaków](#plain-strings "Jak dopasowywany jest zwykły ciąg znaków")
  - [Find](#find "Precyzyjne cele z Find")
- [Dane i przygotowanie](#data-and-setup "Dane, setUp i tearDown")
- [Odtwarzanie innych playbooków](#playing-other-playbooks "Uruchamianie playbooka z innego playbooka")
- [Tempo](#speed "PlaybookSpeed")
- [Palec i podpis](#finger-and-caption "Palec i podpis kroku")
- [Rejestrowanie playbooków](#registering-playbooks "Rejestrowanie playbooków w Nylo")
- [Uruchamianie playbooka](#running-a-playbook "Uruchamianie playbooka")
  - [Z Metro](#running-from-metro "Uruchamianie z Metro")
  - [Przy starcie](#running-on-boot "Uruchamianie przy starcie")
  - [Z Darta](#running-from-dart "Uruchamianie z Darta")
- [Raporty](#reports "PlaybookReport")
- [Buildy release](#release-builds "Playbooki w buildach release")
- [Testowanie](#testing "Testowanie playbooka")

<div id="introduction"></div>

## Wprowadzenie

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**Playbook** to klasa, która wylicza tapnięcia, pisanie, przewijanie i zmiany stron w ścieżce użytkownika. Uruchomiona aplikacja wykonuje je sama na sobie i może pokazywać palec oraz podpis każdego kroku -- przydatne do dem, nagrań ekranu i sprawdzania ścieżki od początku do końca.

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

Importuj `package:nylo_framework/playbook.dart` wszędzie tam, gdzie piszesz lub rejestrujesz playbooki.

<div id="creating-a-playbook"></div>

## Tworzenie playbooka

Użyj Metro, aby go utworzyć:

``` bash
metro make:playbook sign_up
```

Spowoduje to utworzenie `lib/app/playbooks/sign_up_playbook.dart` i zarejestrowanie go w Twojej aplikacji. Opcje polecenia znajdziesz w [Generowanie playbooka](/docs/{{ $version }}/metro#make-playbook).

<div id="steps"></div>

## Kroki

`steps` playbooka buduje się z odziedziczonych czasowników:

| Krok | Opis |
|------|-------------|
| `tap(target)` | Tapnij cel |
| `longPress(target)` | Naciśnij i przytrzymaj cel |
| `type(target, text)` | Wpisz tekst w pole. Najpierw je czyści, chyba że `clear: false`; `submit: true` naciska przycisk akcji klawiatury; `secret: true` ukrywa tekst w podpisie i raporcie |
| `scrollTo(target)` | Przewijaj, aż cel znajdzie się na ekranie. Przekaż `within:`, aby przewijać konkretny element przewijalny |
| `see(target)` | Sprawdź, czy cel jest na ekranie, czekając, aż się pojawi |
| `dontSee(target)` | Sprawdź, czy celu nie ma na ekranie, czekając, aż zniknie |
| `waitFor(target)` | Poczekaj na cel, maksymalnie `timeout` (domyślnie 10 sekund) |
| `waitForPage(path)` | Poczekaj na stronę o ścieżce trasy lub `RouteView` (np. `HomePage.path`) |
| `visit(path)` | Otwórz stronę po ścieżce trasy lub `RouteView`, z opcjonalnym `data:` |
| `back()` | Wróć o stronę wstecz |
| `pause(seconds: 1)` | Zatrzymaj się w miejscu, np. aby demo mogło odetchnąć |
| `step(title, action)` | Uruchom dowolny kod z tymi samymi czasownikami, za pomocą `PlaybookActor` |
| `scene(title, steps)` | Zgrupuj kroki pod tytułem; ich podpisy zaczynają się od niego, a ich wyniki są zagnieżdżone w raporcie |
| `play(playbook)` | Uruchom inny playbook -- zobacz [Odtwarzanie innych playbooków](#playing-other-playbooks) |

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

Kroki są wykonywane po kolei i zatrzymują się na pierwszym niepowodzeniu. Pozostałe kroki są raportowane jako pominięte.

<div id="targets"></div>

## Cele

Większość kroków przyjmuje cel: co tapnąć, w co wpisać tekst lub czego szukać.

<div id="plain-strings"></div>

### Zwykłe ciągi znaków

Zwykły ciąg znaków jest dopasowywany tak, jak człowiek czyta ekran. Próbuje po kolei:

1. Widoczny tekst
2. Podpowiedź lub etykieta pola
3. Tooltip
4. Etykieta semantyczna
5. `ValueKey`
6. Tekst zawierający ten ciąg

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

`Key`, `Type` lub `IconData` można także przekazać bezpośrednio do kroku.

<div id="find"></div>

### Find

Użyj `Find`, gdy zwykły ciąg znaków nie jest wystarczająco precyzyjny:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Dopasowuje |
|--------|---------|
| `Find.text(text)` | Widoczny tekst |
| `Find.textContaining(text)` | Tekst zawierający ciąg |
| `Find.icon(icon)` | Ikonę |
| `Find.type<T>()` | Widget typu `T` |
| `Find.key(key)` | Widget z danym kluczem |
| `Find.field(name)` | Pole `NyForm` według nazwy |
| `Find.input(text)` | Pole tekstowe według podpowiedzi, etykiety lub placeholdera |
| `Find.tooltip(message)` | Tooltip |
| `Find.semantics(label)` | Etykietę semantyczną |
| `Find.where(test)` | Widgety, które przechodzą Twój test |

Zawęź dowolny finder za pomocą `.inside(ancestor)`, `.at(index)` (liczone od 0), `.first` lub `.last`.

Tapnięcie jest odrzucane, gdy cel zasłania coś niepowiązanego, np. bariera okna dialogowego.

<div id="data-and-setup"></div>

## Dane i przygotowanie

`defaults` przechowuje dane, z którymi startują kroki. Odczytuj je przez `data` wewnątrz `steps`. `setUp` uruchamia się przed krokami, a `tearDown` po nich, nawet gdy któryś krok się nie powiódł:

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

`seed([...])` uruchamia [Seedery](/docs/{{ $version }}/metro-live#seeders) (instancje lub zarejestrowane nazwy) i zapisuje je tak jak `seed` w `metro live`.

<div id="playing-other-playbooks"></div>

## Odtwarzanie innych playbooków

`play()` uruchamia inny playbook w całości, z jego własnymi `setUp` i `tearDown`. Przekaż instancję lub nazwę, pod którą jest zarejestrowany:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Dane** łączą się w tej kolejności: `defaults` potomka, potem `data` rodzica, a na końcu `data:` przekazane do `play`.
- **Tempo** jest dziedziczone od rodzica, chyba że przekażesz `speed:`.
- **Wyniki** są zagnieżdżone pod krokiem w raporcie.
- **Nieudany potomek** oblewa krok.
- **Cykle** są odrzucane, a playbooki zagnieżdżają się maksymalnie na 8 poziomów.

<div id="speed"></div>

## Tempo

`PlaybookSpeed` ustawia tempo każdego oczekiwania:

| Tempo | Opis |
|-------|-------------|
| `PlaybookSpeed.instant` | Brak oczekiwań poza tymi, których wymaga przewijanie. Przeznaczone do testów |
| `PlaybookSpeed.fast` | Szybko, ale wciąż łatwo nadążyć |
| `PlaybookSpeed.natural` | Tempo osoby korzystającej z aplikacji. Wartość domyślna |
| `PlaybookSpeed.slow` | Wystarczająco wolno, by komentować na żywo |

Przeskaluj dowolne tempo za pomocą `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // dwa razy wolniej
```

Tempo przekazane do `run()`, `--speed` z Metro albo tempo playbooka, który odtwarza ten playbook, ma pierwszeństwo przed tempem właściwym dla playbooka.

<div id="finger-and-caption"></div>

## Palec i podpis

Palec i podpisy kroków są domyślnie wyłączone. Nadpisz `showFinger` i `caption`, aby je włączyć:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // lub .top
```

`showFinger` domyślnie ma wartość `false`, a `caption` -- `PlaybookCaption.hidden`; `PlaybookCaption` to `top`, `bottom` lub `hidden`. Tapnięcia i przesunięcia odbywają się w obu przypadkach. Playbook odtwarzany z tego playbooka przez `play()` używa własnego ustawienia, a ustawienie tego playbooka wraca po jego zakończeniu. Aby ukryć oba na czas jednego uruchomienia, użyj `--no-overlay` z Metro lub `run(overlay: false)` z Darta.

Przed nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0) palec i dolny podpis były domyślnie włączone. Dodaj powyższe nadpisania, aby zachować ten wygląd.

<div id="registering-playbooks"></div>

## Rejestrowanie playbooków

Zarejestruj swoje playbooki, aby Metro i `play('name')` mogły je znaleźć:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` zwraca zarejestrowane playbooki. Możesz je też przekazać podczas konfigurowania Nylo w swoim `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` robi to za Ciebie. Przechowuje rejestr w `lib/bootstrap/playbooks.dart`, dodaje do niego każdy nowy playbook i przekazuje `playbooks` do `nylo.configure(...)`. W buildach release rejestr jest pustą mapą:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Zarejestrowany playbook działa pod swoim kluczem. Niezarejestrowany działa pod swoim `name` albo nazwą klasy w snake case (`SignUpPlaybook` to `sign_up`).

<div id="running-a-playbook"></div>

## Uruchamianie playbooka

<div id="running-from-metro"></div>

### Z Metro

Gdy aplikacja działa w trybie debug:

``` bash
metro live:run playbook sign_up --speed fast
```

Wewnątrz `metro live` wpisz `playbook sign_up`. Wszystkie opcje znajdziesz w [Metro Live](/docs/{{ $version }}/metro-live#playbooks). Dodaj `--record`, aby zapisać wideo z przebiegu; zobacz [Nagrywanie](/docs/{{ $version }}/metro-live#recording-playbooks).

<div id="running-on-boot"></div>

### Przy starcie

Dodaj te klucze do pliku `.env`, aby odtwarzać playbooki, gdy tylko aplikacja pojawi się na ekranie:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` to lista nazw playbooków oddzielonych przecinkami, a `PLAYBOOK_SPEED` to jedno z `instant`, `fast`, `natural` lub `slow`. Oba działają tylko w buildach debug i profile.

<div id="running-from-dart"></div>

### Z Darta

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` przyjmuje `speed`, `data`, `clock`, `overlay` (domyślnie `true`) i `onEvent`. Nieudany krok nie rzuca wyjątku -- sprawdź `report.passed`. Naraz może działać tylko jeden playbook; użyj `play()`, aby uruchomić jeden wewnątrz drugiego.

Zatrzymaj działający playbook za pomocą `Playbook.stop()`. Pozostałe kroki są pomijane, ale `tearDown` nadal się wykonuje. `Playbook.isRunning` mówi, czy któryś działa.

<div id="reports"></div>

## Raporty

`run()` zwraca `PlaybookReport` z `name` playbooka, `speed`, czasem `elapsed`, informacją, czy został `stopped`, oraz listą `steps` z `PlaybookStepResult` (każdy z `title`, `status`, `elapsed`, `error` i zagnieżdżonymi `steps`). `report.passed` jest true, gdy każdy krok przeszedł, a wydrukowanie raportu wygląda jak wynik Metro. `report.toJson()` zwraca te same dane jako JSON.

<div id="release-builds"></div>

## Buildy release

Playbooki nigdy nie działają w buildzie release, więc ścieżka nie może sterować aplikacją użytkownika:

- `Playbook.run()` rzuca `UnsupportedError`.
- `addPlaybooks` jest ignorowane.
- Rejestr `lib/bootstrap/playbooks.dart` generowany przez Metro jest pusty.

`Playbook.isAvailable` mówi, czy bieżący build może uruchamiać playbooki.

<div id="testing"></div>

## Testowanie

Uruchom playbook w teście widgetu za pomocą `NyTesterClock`, a następnie sprawdź jego raport przez `expectPlaybookPassed`:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Testy integracyjne opisano w [Testowanie playbooków](/docs/{{ $version }}/testing#testing-playbooks).
