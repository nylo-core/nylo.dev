# Metro Live

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do Nylo Live")
- Powłoka metro live
  - [Opcje wspólne](#shared-options "Opcje akceptowane przez każde polecenie live")
  - [Uzupełnianie tabulatorem i historia](#tab-completion-and-history "Uzupełnianie tabulatorem i historia poleceń")
  - [Uruchamianie skryptu](#running-a-script "Uruchamianie skryptu .live")
- [live:devices](#live-devices "Lista uruchomionych aplikacji")
- [live:status](#live-status "Status aplikacji")
- [live:run](#live-run "Uruchamianie wbudowanego polecenia live")
- [Playbooki](#playbooks "Odtwarzanie playbooków z Metro")
- Polecenia Live
  - [Tworzenie polecenia Live](#creating-a-live-command "Tworzenie polecenia live")
  - [Uruchamianie polecenia Live](#running-a-live-command "Uruchamianie polecenia live")
- Seedery
  - [Tworzenie Seedera](#creating-a-seeder "Tworzenie seedera")
  - [Seedowanie i wycofywanie](#seeding-and-rolling-back "Seedowanie i wycofywanie zmian")
- Migawki pamięci
  - [Eksportowanie migawki](#exporting-a-snapshot "Eksportowanie migawki")
  - [Wczytywanie migawki](#loading-a-snapshot "Wczytywanie migawki")
- [Akcje strony](#page-actions "Wywoływanie strony spoza niej")

<div id="introduction"></div>

## Wprowadzenie

<!-- uncertain: newly created translation; needs full human review -->

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: "Nylo Live" traktowane jako nazwa własna funkcji (jak "Backpack"/"Metro") i pozostawione bez tłumaczenia; podobnie słowo "Live" jako określenie funkcji w dalszej części pliku -->

**Nylo Live** pozwala Metro sięgnąć do Twojej aplikacji, gdy ta działa w trybie debug (lub profile), poprzez usługę Dart VM. Znajduje aplikację za pomocą Dart Tooling Daemon bez żadnej konfiguracji i łączy się wyłącznie z aplikacjami zbudowanymi z bieżącego projektu.

Nylo Live jest domyślnie włączone w kompilacjach debug i profile; kompilacja release nie rejestruje niczego. Polecenia, które tylko odczytują aplikację (`live:status`, `live:run data`, `live:run routes`, ...) działają też w kompilacjach profile. Polecenia zmieniające aplikację (`route`, `storage`, `auth`, seedery, ...) wymagają kompilacji debug.

Wyłącz je samodzielnie, zanim cokolwiek innego się uruchomi, z poziomu providera:

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

## Powłoka metro live

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Otwórz powłokę połączoną z Twoją uruchomioną aplikacją:

``` bash
metro live
```

Nylo Live wykrywa uruchomione aplikacje projektu i łączy się z jedną z nich -- gdy działa więcej niż jedna, poprosi Cię o wybór. Po połączeniu, prompt pokazuje urządzenie i trasę aktualnie widoczną na ekranie:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Polecenia live tracą swój prefiks `metro live:` wewnątrz powłoki -- `status` zamiast `metro live:status`, `route /profile` zamiast `metro live:run route /profile`. Kilka poleceń działa tylko wewnątrz powłoki: `seed`, `seed:rollback`, `export`, `reload` i `restart`. Wpisz `help`, aby zobaczyć wszystkie polecenia, albo `help <command>`, aby zobaczyć opcje jednego polecenia. Wpisz `exit` (albo Ctrl+D), aby wyjść.

Jeśli aplikacja zrestartuje się, gdy jesteś połączony, powłoka sama nawiąże połączenie ponownie.

<div id="shared-options"></div>

### Opcje wspólne

Każde polecenie live -- wewnątrz powłoki albo jako `metro live:*` -- akceptuje te same opcje:

| Opcja | Opis |
|--------|-------------|
| `-d, --device <#\|name>` | Wskaż aplikację po jej numerze w `metro live:devices` albo po nazwie urządzenia |
| `--all` | Uruchom na każdej uruchomionej aplikacji tego projektu |
| `--json` | Wypisz w formacie JSON nadającym się do przetwarzania maszynowego |
| `--uri` | Użyj tego adresu usługi VM zamiast wykrywania aplikacji |
| `--timeout` | Liczba sekund oczekiwania na każdą aplikację podczas wykrywania (domyślnie `3`) |

Wartość opcji zapisana jako `@path` jest odczytywana z tego pliku -- przydatne dla dużego ładunku `--data`:

``` bash
metro live:run route /profile --data @user.json
```

Zacznij wartość od `@@`, jeśli naprawdę ma zaczynać się od `@`.

<div id="tab-completion-and-history"></div>

### Uzupełnianie tabulatorem i historia

Wewnątrz powłoki Tab uzupełnia nazwy poleceń, opcje, trasy, klucze pamięci i Backpacka, nazwy seederów oraz nazwy playbooków względem połączonej aplikacji. Historia jest przechowywana w `.dart_tool/nylo/live_history` i zachowuje się między sesjami.

<div id="running-a-script"></div>

### Uruchamianie skryptu

Przekaż plik do `metro live`, aby uruchomić go jako skrypt, jedno polecenie na linię, zatrzymując się na pierwszym poleceniu, które się nie powiedzie:

``` bash
metro live < seeds/demo.live
```

Puste linie i linie zaczynające się od `#` są pomijane.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Wyświetla listę uruchomionych aplikacji tego projektu, ponumerowanych na potrzeby `-d`:

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

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Pokazuje aplikację, urządzenie, tryb kompilacji, bieżącą trasę i stos, język, motyw oraz czy użytkownik jest zalogowany:

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

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Uruchamia wbudowane polecenie, które sprawdza lub steruje aplikacją:

``` bash
metro live:run <command> [arguments]
```

| Polecenie | Opis |
|---------|-------------|
| `data` | Pokaż dane i pola strony widocznej na ekranie |
| `routes` | Wyświetl listę zarejestrowanych tras |
| `route` | Otwórz stronę, z opcjonalnym `--data` |
| `back` | Wróć o stronę wstecz, albo do wskazanej trasy |
| `deeplink` | Otwórz deep link, albo pokaż, jak są skonfigurowane |
| `storage` | Wyświetl pamięć lokalną, albo pokaż, zapisz lub usuń jedną wartość |
| `storage:clear` | Wyczyść pamięć lokalną, z opcjonalnym `--keep` |
| `backpack` | Wyświetl wartości Backpacka, albo pokaż, zapisz lub usuń jedną |
| `auth` | Pokaż, kto jest zalogowany, albo zaloguj i wyloguj |
| `locale` | Przełącz język aplikacji |
| `theme` | Przełącz motyw aplikacji |
| `state` | Wyślij dane do stanu za pomocą `updateState` |
| `event` | Wywołaj jedno ze swoich zdarzeń |
| `toast` | Pokaż powiadomienie toast |
| `playbook` | Odtwórz swoje [playbooki](/docs/{{ $version }}/playbooks) albo wyświetl ich listę |

Wewnątrz `metro live` pomiń część `live:run` -- `route /profile` zamiast `metro live:run route /profile`:

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

`data` odczytuje też pola zadeklarowane przez stan Twojej strony, bezpośrednio z uruchomionej aplikacji -- we Flutterze nie ma refleksji, więc działa to tylko dla stanu, który faktycznie jest aktualnie na ekranie:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbooki

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Odtwórz jeden lub więcej [playbooków](/docs/{{ $version }}/playbooks) w uruchomionej aplikacji. Każdy krok jest wypisywany na bieżąco:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Podaj kilka nazw playbooków, aby odtworzyć je jeden po drugim. Bez nazw, albo z `--list`, polecenie wyświetla listę playbooków aplikacji.

| Opcja | Opis |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Odtwarzaj w tym tempie zamiast w tempie właściwym dla każdego playbooka |
| `--data <json>` | Obiekt JSON, który każdy playbook w tym przebiegu odczytuje w `data`, nadpisując swoje wartości domyślne |
| `--no-overlay` | Ukryj palec i podpis kroku |
| `--loop` | Odtwarzaj playbooki w kółko do naciśnięcia Ctrl+C |
| `--list` | Wyświetl listę playbooków aplikacji zamiast odtwarzać jeden |

Ctrl+C prosi aplikację o zatrzymanie: pozostałe kroki są pomijane, ale `tearDown` nadal się wykonuje. Naciśnij Ctrl+C drugi raz, aby przestać czekać.

Wewnątrz powłoki `metro live` polecenie to `playbook <name>`, a Tab uzupełnia nazwy playbooków:

``` plaintext
› playbook sign_up --speed fast
```

<div id="playbook-live-commands"></div>

### Opis poleceń Live

Polecenia, które za tym stoją, to `playbooks.list`, `playbooks.run` i `playbooks.stop`. Uruchomienie od razu zwraca swój identyfikator i przesyła każdy krok jako zdarzenie `nylo.playbook`, więc może trwać dłużej niż limit czasu polecenia. `live:status` wyświetla playbooki aplikacji. Playbooki pojawiły się w wersji 9 protokołu Nylo Live.

<div id="live-commands"></div>

## Polecenia Live

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Zarejestruj własne polecenia do uruchamiania wewnątrz aplikacji, obok wbudowanych powyżej.

<div id="creating-a-live-command"></div>

### Tworzenie polecenia Live

``` bash
metro make:command seed_cart --live
```

| Opcja | Opis |
|--------|-------------|
| `--category`, `-c` | Kategoria polecenia (domyślnie: `app`) |
| `--description` | Jednolinijkowy opis wyświetlany przez `--help` polecenia |
| `--force`, `-f` | Tworzy plik, nawet jeśli już istnieje |

To tworzy `LiveCommand` w `lib/app/commands/`, oznacza go jako `"type": "live"` w `commands.json`, rejestruje go w `lib/bootstrap/live_commands.dart` i podłącza `liveCommands: liveCommands` w `nylo.configure(...)` w `app_provider.dart`:

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

`LiveCommand` współdzieli kształt `builder`/`handle` z własnymi poleceniami Metro, ale `handle` działa **wewnątrz aplikacji**, więc może korzystać z `NyStorage`, `routeTo`, `Auth` i Twoich modeli. `package:nylo_framework/live.dart` eksportuje `LiveCommand` razem z `Seeder`, `StorageSnapshot`, `LiveException` i `LiveOutput`.

<div id="running-a-live-command"></div>

### Uruchamianie polecenia Live

Nowe polecenie live wymaga hot restart, zanim uruchomiona aplikacja będzie mogła je uruchomić. Następnie, z terminala:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seedery

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: nowy termin Nylo "Seeder" (oraz pochodne "seedować"/"seedowanie") nie występował wcześniej w tym pliku lokalnym; zachowano jako zapożyczenie odmieniane jak rzeczownik męski, na wzór "kontroler" -->

`Seeder` wprowadza Twoją uruchomioną aplikację w znany stan -- zalogowany, ukończony onboarding, przykładowe rekordy -- i wyprowadza ją z powrotem.

<div id="creating-a-seeder"></div>

### Tworzenie Seedera

``` bash
metro make:seeder demo_user
```

| Opcja | Opis |
|--------|-------------|
| `--description` | Jednolinijkowy opis, wyświetlany, gdy `seed` wypisuje listę seederów |
| `--force`, `-f` | Tworzy plik, nawet jeśli już istnieje |

To tworzy `Seeder` w `lib/app/seeders/`, rejestruje go w `lib/bootstrap/seeders.dart` i podłącza `seeders: seeders` w `nylo.configure(...)`:

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

Każda wartość pamięci i Backpacka zmieniona przez `up()` jest automatycznie zapisywana, więc domyślne `down()` (`restore()`) przywraca każdą z nich dokładnie tak, jak było, w odwrotnej kolejności -- nawet po hot restart. Wywołaj `seed(otherSeeders)` wewnątrz seedera, aby uruchomić inne jako jego część; ich zmiany są również zapisywane i wycofywane.

<div id="seeding-and-rolling-back"></div>

### Seedowanie i wycofywanie

Seedowanie, wycofywanie i eksportowanie działają tylko wewnątrz powłoki `metro live`:

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

Samo `seed` wypisuje listę zarejestrowanych seederów. `--fresh` czyści pamięć i Backpack przed seedowaniem, a `--restart` wykonuje hot restart aplikacji po zakończeniu, więc uruchamia się już zaseedowana:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Migawki pamięci

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: nowy termin "Storage Snapshots" przetłumaczony jako "Migawki pamięci" (migawka = snapshot); brak wcześniejszego wystąpienia w tym pliku lokalnym -->

`export` zapisuje wszystko, co aplikacja aktualnie przechowuje w pamięci i Backpacku -- jako seeder, albo jako przenośny plik JSON -- a `seed <file>` wczytuje to z powrotem.

<div id="exporting-a-snapshot"></div>

### Eksportowanie migawki

Uruchom bez nazwy, aby zobaczyć podgląd tego, co zostałoby przechwycone:

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

Nadaj nazwę, aby zapisać jako seeder w `lib/app/seeders/` (tak samo jak `make:seeder`, ale już wypełniony przechwyconymi wartościami):

``` plaintext
› export pro_user
```

Albo zapisz od razu do pliku:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Opcja | Opis |
|--------|-------------|
| `--to <path>` | Zapisz migawkę do tego pliku JSON zamiast do seedera |
| `--description` | Jednolinijkowy opis dla seedera |
| `--only <keys>` | Klucze rozdzielone przecinkami do wyeksportowania; `*` pasuje do wszystkiego, np. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Klucze rozdzielone przecinkami do pominięcia, np. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Uwzględnij wartości Backpacka (domyślnie: uwzględnione) |
| `--force`, `-f` | Zastąp seeder albo plik, który już istnieje |

`Model`, albo dowolna klasa z zarejestrowanym dekoderem, jest oznaczana nazwą klasy, więc wraca przez swój własny dekoder zamiast jako zwykła mapa. `export` ostrzega, gdy migawka zawiera klucze wyglądające jak dane uwierzytelniające, oraz gdy jest na tyle duża, że prawdopodobnie warto użyć `--except`.

<div id="loading-a-snapshot"></div>

### Wczytywanie migawki

``` plaintext
› seed snapshots/pro_user.json
```

Wczytanie pliku jest zapisywane tak samo jak seeder, więc `seed:rollback <name>` (nazwa pliku, albo to, co nadało mu `--as`) również to cofa. Z poziomu kodu, seeder może zastosować przechwyconą migawkę za pomocą `importSnapshot`:

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

`export` zapisuje dokładnie w tym kształcie, więc plik zapisany za pomocą `--to` można wkleić bezpośrednio do pola `snapshot` seedera.

<div id="page-actions"></div>

## Akcje strony

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` na stronie daje Ci `PageStateActions` do wywoływania tej strony spoza niej, bez ręcznego budowania wywołania `stateAction(...)`:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Zobacz [Wywoływanie akcji strony](/docs/{{ $version }}/state-management#page-actions-shortcut), aby poznać pełny zestaw wbudowanych akcji oraz jak zadeklarować własne akcje typowane. Nylo Live odczytuje i korzysta z tych samych akcji: `metro live:run data` wypisuje, co strona widoczna na ekranie udostępnia pod `actions`, a `metro live:run state` wysyła dane do strony bezpośrednio po nazwie jej stanu.
