# Navigation Hub

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
  - [Tworzenie Navigation Hub](#creating-a-navigation-hub "Tworzenie Navigation Hub")
  - [Tworzenie zakładek nawigacji](#creating-navigation-tabs "Tworzenie zakładek nawigacji")
  - [Nawigacja dolna](#bottom-navigation "Nawigacja dolna")
    - [Niestandardowy builder paska nawigacji](#custom-nav-bar-builder "Niestandardowy builder paska nawigacji")
  - [Nawigacja górna](#top-navigation "Nawigacja górna")
  - [Nawigacja Journey](#journey-navigation "Nawigacja Journey")
    - [Style postępu](#journey-progress-styles "Style postępu Journey")
    - [JourneyState](#journey-state "JourneyState")
    - [Metody pomocnicze JourneyState](#journey-state-helper-methods "Metody pomocnicze JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Nawigacja wewnątrz zakładki](#navigating-within-a-tab "Nawigacja wewnątrz zakładki")
- [Zakładki](#tabs "Zakładki")
  - [Dodawanie odznak do zakładek](#adding-badges-to-tabs "Dodawanie odznak do zakładek")
  - [Dodawanie alertów do zakładek](#adding-alerts-to-tabs "Dodawanie alertów do zakładek")
- [Początkowy indeks](#initial-index "Początkowy indeks")
- [Zachowanie stanu](#maintaining-state "Zachowanie stanu")
- [onTap](#on-tap "onTap")
- [Akcje stanu](#state-actions "Akcje stanu")
- [Styl ładowania](#loading-style "Styl ładowania")

<div id="introduction"></div>

## Wprowadzenie

Navigation Hubs to centralne miejsce, w którym możesz **zarządzać** nawigacją dla wszystkich swoich widgetów.
Od razu po instalacji możesz tworzyć dolne, górne i journey układy nawigacji w kilka sekund.

Wyobraźmy sobie, że masz aplikację i chcesz dodać dolny pasek nawigacji, aby użytkownicy mogli przechodzić między różnymi zakładkami w Twojej aplikacji.

Możesz użyć Navigation Hub, aby to zbudować.

Zobaczmy, jak możesz użyć Navigation Hub w swojej aplikacji.

<div id="basic-usage"></div>

## Podstawowe użycie

Możesz utworzyć Navigation Hub za pomocą poniższego polecenia.

``` bash
metro make:navigation_hub base
```

Polecenie przeprowadzi Cię przez interaktywną konfigurację:

1. **Wybierz typ układu** - Wybierz między `navigation_tabs` (nawigacja dolna) lub `journey_states` (sekwencyjny przepływ).
2. **Wprowadź nazwy zakładek/stanów** - Podaj oddzielone przecinkami nazwy dla swoich zakładek lub stanów journey.

Utworzy to pliki w katalogu `resources/pages/navigation_hubs/base/`:
- `base_navigation_hub.dart` - Główny widget huba
- `tabs/` lub `states/` - Zawiera widgety potomne dla każdej zakładki lub stanu journey

Oto jak wygląda wygenerowany Navigation Hub:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Jak widać, Navigation Hub ma **dwie** zakładki: Home i Settings.

Metoda `layout` zwraca typ układu dla huba. Otrzymuje `BuildContext`, dzięki czemu możesz uzyskać dostęp do danych motywu i zapytań o media podczas konfigurowania układu.

Możesz tworzyć więcej zakładek, dodając `NavigationTab` do Navigation Hub.

Najpierw musisz utworzyć nowy widget za pomocą Metro.

``` bash
metro make:stateful_widget news_tab
```

Możesz również utworzyć wiele widgetów naraz.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Następnie możesz dodać nowy widget do Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Aby użyć Navigation Hub, dodaj go do routera jako trasę początkową:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Navigation Hub oferuje **wiele** więcej możliwości, przyjrzyjmy się niektórym funkcjom.

<div id="bottom-navigation"></div>

### Nawigacja dolna

Możesz ustawić układ na dolny pasek nawigacji, zwracając `NavigationHubLayout.bottomNav` z metody `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Możesz dostosować dolny pasek nawigacji, ustawiając właściwości, takie jak poniższe:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

Możesz zastosować gotowy styl do dolnego paska nawigacji za pomocą parametru `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Niestandardowy builder paska nawigacji

Aby uzyskać pełną kontrolę nad paskiem nawigacji, możesz użyć parametru `navBarBuilder`.

Pozwala to zbudować dowolny niestandardowy widget, jednocześnie otrzymując dane nawigacji.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

Obiekt `NavBarData` zawiera:

| Właściwość | Typ | Opis |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Elementy paska nawigacji |
| `currentIndex` | `int` | Aktualnie wybrany indeks |
| `onTap` | `ValueChanged<int>` | Callback po dotknięciu zakładki |

Oto przykład w pełni niestandardowego szklanego paska nawigacji:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Uwaga:** Gdy używasz `navBarBuilder`, parametr `style` jest ignorowany.

<div id="top-navigation"></div>

### Nawigacja górna

Możesz zmienić układ na górny pasek nawigacji, zwracając `NavigationHubLayout.topNav` z metody `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Możesz dostosować górny pasek nawigacji, ustawiając właściwości, takie jak poniższe:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Nawigacja Journey

Możesz zmienić układ na nawigację journey, zwracając `NavigationHubLayout.journey` z metody `layout`.

Jest to idealne rozwiązanie dla przepływów onboardingu lub formularzy wielokrokowych.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

Możesz również ustawić `backgroundGradient` dla układu journey:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Uwaga:** Gdy `backgroundGradient` jest ustawiony, ma pierwszeństwo przed `backgroundColor`.

Jeśli chcesz użyć układu nawigacji journey, Twoje **widgety** powinny używać `JourneyState`, ponieważ zawiera wiele metod pomocniczych do zarządzania podróżą.

Możesz utworzyć całą podróż za pomocą polecenia `make:navigation_hub` z układem `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Utworzy to hub i wszystkie widgety stanów journey w katalogu `resources/pages/navigation_hubs/onboarding/states/`.

Możesz też tworzyć pojedyncze widgety journey za pomocą:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Następnie możesz dodać nowe widgety do Navigation Hub.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Style postępu Journey

Możesz dostosować styl wskaźnika postępu za pomocą klasy `JourneyProgressStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

Dostępne wskaźniki postępu:

- `JourneyProgressIndicator.none()`: Nie renderuje niczego - przydatne do ukrywania wskaźnika na konkretnej zakładce.
- `JourneyProgressIndicator.linear()`: Liniowy pasek postępu.
- `JourneyProgressIndicator.dots()`: Wskaźnik postępu oparty na kropkach.
- `JourneyProgressIndicator.numbered()`: Numerowany wskaźnik postępu krokowego.
- `JourneyProgressIndicator.segments()`: Segmentowy styl paska postępu.
- `JourneyProgressIndicator.circular()`: Kołowy wskaźnik postępu.
- `JourneyProgressIndicator.timeline()`: Wskaźnik postępu w stylu osi czasu.
- `JourneyProgressIndicator.custom()`: Niestandardowy wskaźnik postępu za pomocą funkcji buildera.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

Możesz dostosować pozycję i padding wskaźnika postępu w `JourneyProgressStyle`:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

Dostępne pozycje wskaźnika postępu:

- `ProgressIndicatorPosition.top`: Wskaźnik postępu na górze ekranu.
- `ProgressIndicatorPosition.bottom`: Wskaźnik postępu na dole ekranu.

#### Nadpisanie stylu postępu per zakładka

Możesz nadpisać `progressStyle` na poziomie układu dla poszczególnych zakładek za pomocą `NavigationTab.journey(progressStyle: ...)`. Zakładki bez własnego `progressStyle` dziedziczą domyślny styl układu. Zakładki bez domyślnego stylu układu i bez stylu per zakładka nie będą wyświetlać wskaźnika postępu.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // overrides the layout default for this tab only
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

Klasa `JourneyState` rozszerza `NyState` o funkcjonalność specyficzną dla podróży, ułatwiając tworzenie przepływów onboardingu i wielokrokowych podróży.

Aby utworzyć nowy `JourneyState`, możesz użyć poniższego polecenia.

``` bash
metro make:journey_widget onboard_user_dob
```

Jeśli chcesz utworzyć wiele widgetów naraz, możesz użyć następującego polecenia.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Oto jak wygląda wygenerowany widget JourneyState:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

Zauważ, że klasa **JourneyState** używa `nextStep` do nawigacji do przodu i `onBackPressed` do cofania się.

Metoda `nextStep` przechodzi przez pełny cykl życia walidacji: `canContinue()` -> `onBeforeNext()` -> nawigacja (lub `onComplete()` jeśli na ostatnim kroku) -> `onAfterNext()`.

Możesz też użyć `buildJourneyContent` do zbudowania ustrukturyzowanego układu z opcjonalnymi przyciskami nawigacji:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

Oto właściwości, których możesz użyć w metodzie `buildJourneyContent`.

| Właściwość | Typ | Opis |
| --- | --- | --- |
| `content` | `Widget` | Główna treść strony. |
| `nextButton` | `Widget?` | Widget przycisku dalej. |
| `backButton` | `Widget?` | Widget przycisku wstecz. |
| `contentPadding` | `EdgeInsetsGeometry` | Padding dla treści. |
| `header` | `Widget?` | Widget nagłówka. |
| `footer` | `Widget?` | Widget stopki. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Wyrównanie treści w osi poprzecznej. |

<div id="journey-state-helper-methods"></div>

### Metody pomocnicze JourneyState

Klasa `JourneyState` posiada metody pomocnicze i właściwości, które możesz wykorzystać do dostosowania zachowania podróży.

| Metoda / Właściwość | Opis |
| --- | --- |
| [`nextStep()`](#next-step) | Nawigacja do następnego kroku z walidacją. Zwraca `Future<bool>`. |
| [`previousStep()`](#previous-step) | Nawigacja do poprzedniego kroku. Zwraca `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Prosty helper do nawigacji do poprzedniego kroku. |
| [`onComplete()`](#on-complete) | Wywoływany gdy podróż jest zakończona (na ostatnim kroku). |
| [`onBeforeNext()`](#on-before-next) | Wywoływany przed nawigacją do następnego kroku. |
| [`onAfterNext()`](#on-after-next) | Wywoływany po nawigacji do następnego kroku. |
| [`canContinue()`](#can-continue) | Sprawdzenie walidacji przed nawigacją do następnego kroku. |
| [`isFirstStep`](#is-first-step) | Zwraca true, jeśli to pierwszy krok podróży. |
| [`isLastStep`](#is-last-step) | Zwraca true, jeśli to ostatni krok podróży. |
| [`currentStep`](#current-step) | Zwraca indeks bieżącego kroku (od 0). |
| [`totalSteps`](#total-steps) | Zwraca całkowitą liczbę kroków. |
| [`completionPercentage`](#completion-percentage) | Zwraca procent ukończenia (od 0.0 do 1.0). |
| [`goToStep(int index)`](#go-to-step) | Przejdź do konkretnego kroku po indeksie. |
| [`goToNextStep()`](#go-to-next-step) | Przejdź do następnego kroku (bez walidacji). |
| [`goToPreviousStep()`](#go-to-previous-step) | Przejdź do poprzedniego kroku (bez walidacji). |
| [`goToFirstStep()`](#go-to-first-step) | Przejdź do pierwszego kroku. |
| [`goToLastStep()`](#go-to-last-step) | Przejdź do ostatniego kroku. |
| [`exitJourney()`](#exit-journey) | Wyjdź z podróży przez pop głównego nawigatora. |
| [`resetCurrentStep()`](#reset-current-step) | Zresetuj stan bieżącego kroku. |
| [`onJourneyComplete`](#on-journey-complete) | Callback po zakończeniu podróży (nadpisz w ostatnim kroku). |
| [`buildJourneyPage()`](#build-journey-page) | Zbuduj pełnoekranową stronę journey ze Scaffold. |


<div id="next-step"></div>

#### nextStep

Metoda `nextStep` nawiguje do następnego kroku z pełną walidacją. Przechodzi przez cykl życia: `canContinue()` -> `onBeforeNext()` -> nawigacja lub `onComplete()` -> `onAfterNext()`.

Możesz przekazać `force: true`, aby pominąć walidację i bezpośrednio nawigować.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

Aby pominąć walidację:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Metoda `previousStep` nawiguje do poprzedniego kroku. Zwraca `true` w przypadku powodzenia, `false` jeśli jesteś już na pierwszym kroku.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

Metoda `onBackPressed` to prosty helper, który wewnętrznie wywołuje `previousStep()`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

Metoda `onComplete` jest wywoływana, gdy `nextStep()` zostanie uruchomiony na ostatnim kroku (po przejściu walidacji).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Metoda `onBeforeNext` jest wywoływana przed nawigacją do następnego kroku.

Np. jeśli chcesz zapisać dane przed przejściem do następnego kroku, możesz to zrobić tutaj.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

Metoda `onAfterNext` jest wywoływana po nawigacji do następnego kroku.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Metoda `canContinue` jest wywoływana, gdy uruchamiany jest `nextStep()`. Zwróć `false`, aby zapobiec nawigacji.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

Właściwość `isFirstStep` zwraca true, jeśli to pierwszy krok podróży.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

Właściwość `isLastStep` zwraca true, jeśli to ostatni krok podróży.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

Właściwość `currentStep` zwraca indeks bieżącego kroku (od 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

Właściwość `totalSteps` zwraca całkowitą liczbę kroków w podróży.

<div id="completion-percentage"></div>

#### completionPercentage

Właściwość `completionPercentage` zwraca procent ukończenia jako wartość od 0.0 do 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Metoda `goToStep` przeskakuje bezpośrednio do konkretnego kroku po indeksie. **Nie** uruchamia walidacji.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

Metoda `goToNextStep` przeskakuje do następnego kroku bez walidacji. Jeśli jesteś już na ostatnim kroku, nic nie robi.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Metoda `goToPreviousStep` przeskakuje do poprzedniego kroku bez walidacji. Jeśli jesteś już na pierwszym kroku, nic nie robi.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Metoda `goToFirstStep` przeskakuje do pierwszego kroku.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

Metoda `goToLastStep` przeskakuje do ostatniego kroku.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

Metoda `exitJourney` wychodzi z podróży przez pop głównego nawigatora.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Metoda `resetCurrentStep` resetuje stan bieżącego kroku.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Getter `onJourneyComplete` może być nadpisany w **ostatnim kroku** Twojej podróży, aby określić, co się stanie, gdy użytkownik ukończy przepływ.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

Metoda `buildJourneyPage` buduje pełnoekranową stronę journey opakowaną w `Scaffold` z `SafeArea`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Właściwość | Typ | Opis |
| --- | --- | --- |
| `content` | `Widget` | Główna treść strony. |
| `nextButton` | `Widget?` | Widget przycisku dalej. |
| `backButton` | `Widget?` | Widget przycisku wstecz. |
| `contentPadding` | `EdgeInsetsGeometry` | Padding dla treści. |
| `header` | `Widget?` | Widget nagłówka. |
| `footer` | `Widget?` | Widget stopki. |
| `backgroundColor` | `Color?` | Kolor tła Scaffold. |
| `appBar` | `Widget?` | Opcjonalny widget AppBar. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Wyrównanie treści w osi poprzecznej. |

<div id="navigating-within-a-tab"></div>

## Nawigacja do widgetów wewnątrz zakładki

Możesz nawigować do widgetów wewnątrz zakładki za pomocą helpera `pushTo`.

Wewnątrz zakładki możesz użyć helpera `pushTo`, aby przejść do innego widgetu.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Możesz też przekazać dane do widgetu, do którego nawigujesz.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## Zakładki

Zakładki to główne bloki budulcowe Navigation Hub.

Możesz dodawać zakładki do Navigation Hub za pomocą klasy `NavigationTab` i jej nazwanych konstruktorów.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

W powyższym przykładzie dodaliśmy dwie zakładki do Navigation Hub: Home i Settings.

Możesz używać różnych rodzajów zakładek:

- `NavigationTab.tab()` - Standardowa zakładka nawigacji.
- `NavigationTab.badge()` - Zakładka z licznikiem odznak.
- `NavigationTab.alert()` - Zakładka ze wskaźnikiem alertu.
- `NavigationTab.journey()` - Zakładka dla układów nawigacji journey.

<div id="adding-badges-to-tabs"></div>

## Dodawanie odznak do zakładek

Ułatwiliśmy dodawanie odznak do zakładek.

Odznaki to świetny sposób na pokazanie użytkownikom, że w zakładce jest coś nowego.

Na przykład, jeśli masz aplikację do czatu, możesz pokazać liczbę nieprzeczytanych wiadomości w zakładce czatu.

Aby dodać odznakę do zakładki, możesz użyć konstruktora `NavigationTab.badge`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

W powyższym przykładzie dodaliśmy odznakę do zakładki Chat z początkowym licznikiem 10.

Możesz też programowo aktualizować licznik odznak.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Domyślnie licznik odznak jest zapamiętywany. Jeśli chcesz **czyścić** licznik odznak w każdej sesji, możesz ustawić `rememberCount` na `false`.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Dodawanie alertów do zakładek

Możesz dodawać alerty do swoich zakładek.

Czasami możesz nie chcieć wyświetlać licznika odznak, ale chcesz pokazać użytkownikowi wskaźnik alertu.

Aby dodać alert do zakładki, możesz użyć konstruktora `NavigationTab.alert`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

To doda alert do zakładki Chat w kolorze czerwonym.

Możesz też programowo aktualizować alert.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Początkowy indeks

Domyślnie Navigation Hub zaczyna od pierwszej zakładki (indeks 0). Możesz to zmienić, nadpisując getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Zachowanie stanu

Domyślnie stan Navigation Hub jest zachowywany.

Oznacza to, że gdy nawigujesz do zakładki, stan zakładki jest zachowany.

Jeśli chcesz czyścić stan zakładki za każdym razem, gdy do niej nawigujesz, możesz ustawić `maintainState` na `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

Możesz nadpisać metodę `onTap`, aby dodać niestandardową logikę po dotknięciu zakładki.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## Akcje stanu

Akcje stanu to sposób na interakcję z Navigation Hub z dowolnego miejsca w aplikacji.

Oto dostępne akcje stanu:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Aby użyć akcji stanu, możesz zrobić tak:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Styl ładowania

Od razu po instalacji Navigation Hub wyświetli Twój **domyślny** widget ładowania (resources/widgets/loader_widget.dart) podczas ładowania zakładki.

Możesz dostosować `loadingStyle`, aby zaktualizować styl ładowania.

| Styl | Opis |
| --- | --- |
| normal | Domyślny styl ładowania |
| skeletonizer | Styl ładowania szkieletowego |
| none | Brak stylu ładowania |

Możesz zmienić styl ładowania w ten sposób:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Jeśli chcesz zaktualizować widget ładowania w jednym ze stylów, możesz przekazać `child` do `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Teraz, gdy zakładka się ładuje, będzie wyświetlany tekst "Loading...".

Przykład poniżej:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Tworzenie Navigation Hub

Aby utworzyć Navigation Hub, możesz użyć [Metro](/docs/{{$version}}/metro), użyj poniższego polecenia.

``` bash
metro make:navigation_hub base
```

Polecenie przeprowadzi Cię przez interaktywną konfigurację, w której możesz wybrać typ układu i zdefiniować swoje zakładki lub stany journey.

Utworzy to plik `base_navigation_hub.dart` w katalogu `resources/pages/navigation_hubs/base/` z widgetami potomnymi zorganizowanymi w podkatalogach `tabs/` lub `states/`.
