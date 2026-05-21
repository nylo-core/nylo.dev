# Navigation Hub

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
  - [Einen Navigation Hub erstellen](#creating-a-navigation-hub "Einen Navigation Hub erstellen")
  - [Navigations-Tabs erstellen](#creating-navigation-tabs "Navigations-Tabs erstellen")
  - [Untere Navigation](#bottom-navigation "Untere Navigation")
    - [Benutzerdefinierter Nav-Bar-Builder](#custom-nav-bar-builder "Benutzerdefinierter Nav-Bar-Builder")
  - [Obere Navigation](#top-navigation "Obere Navigation")
  - [Journey-Navigation](#journey-navigation "Journey-Navigation")
    - [Fortschrittsstile](#journey-progress-styles "Fortschrittsstile")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState-Hilfsmethoden](#journey-state-helper-methods "JourneyState-Hilfsmethoden")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Innerhalb eines Tabs navigieren](#navigating-within-a-tab "Innerhalb eines Tabs navigieren")
- [Tabs](#tabs "Tabs")
  - [Badges zu Tabs hinzufügen](#adding-badges-to-tabs "Badges zu Tabs hinzufügen")
  - [Alerts zu Tabs hinzufügen](#adding-alerts-to-tabs "Alerts zu Tabs hinzufügen")
- [Initialer Index](#initial-index "Initialer Index")
- [State beibehalten](#maintaining-state "State beibehalten")
- [onTap](#on-tap "onTap")
- [State-Aktionen](#state-actions "State-Aktionen")
- [Ladestil](#loading-style "Ladestil")

<div id="introduction"></div>

## Einleitung

Navigation Hubs sind ein zentraler Ort, an dem Sie die Navigation für all Ihre Widgets **verwalten** können.
Standardmäßig können Sie in Sekundenschnelle untere, obere und Journey-Navigations-Layouts erstellen.

Stellen wir uns **vor**, Sie haben eine App und möchten eine untere Navigationsleiste hinzufügen, die es den Benutzern ermöglicht, zwischen verschiedenen Tabs in Ihrer App zu navigieren.

Sie können einen Navigation Hub verwenden, um dies umzusetzen.

Schauen wir uns an, wie Sie einen Navigation Hub in Ihrer App verwenden können.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Sie können einen Navigation Hub mit dem folgenden Befehl erstellen.

``` bash
metro make:navigation_hub base
```

Der Befehl führt Sie durch ein interaktives Setup:

1. **Layout-Typ wählen** - Wählen Sie zwischen `navigation_tabs` (untere Navigation) oder `journey_states` (sequenzieller Ablauf).
2. **Tab-/State-Namen eingeben** - Geben Sie kommagetrennte Namen für Ihre Tabs oder Journey-States an.

Dadurch werden Dateien unter Ihrem `resources/pages/navigation_hubs/base/`-Verzeichnis erstellt:
- `base_navigation_hub.dart` - Das Haupt-Hub-Widget
- `tabs/` oder `states/` - Enthält die untergeordneten Widgets für jeden Tab oder Journey-State

So sieht ein generierter Navigation Hub aus:

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

  /// State-Aktionen
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layout-Builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Soll der State beibehalten werden
  @override
  bool get maintainState => true;

  /// Der initiale Index
  @override
  int get initialIndex => 0;

  /// Navigations-Seiten
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Tap-Ereignis behandeln
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Sie sehen, dass der Navigation Hub **zwei** Tabs hat: Home und Settings.

Die `layout`-Methode gibt den Layout-Typ für den Hub zurück. Sie erhält einen `BuildContext`, sodass Sie beim Konfigurieren Ihres Layouts auf Theme-Daten und Media Queries zugreifen können.

Sie können weitere Tabs erstellen, indem Sie `NavigationTab`s zum Navigation Hub hinzufügen.

Zuerst müssen Sie ein neues Widget mit Metro erstellen.

``` bash
metro make:stateful_widget news_tab
```

Sie können auch mehrere Widgets gleichzeitig erstellen.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Dann können Sie das neue Widget zum Navigation Hub hinzufügen.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Um den Navigation Hub zu verwenden, fügen Sie ihn als initiale Route zu Ihrem Router hinzu:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Es gibt **noch viel mehr**, was Sie mit einem Navigation Hub machen können. Schauen wir uns einige der Funktionen an.

<div id="bottom-navigation"></div>

### Untere Navigation

Sie können das Layout auf eine untere Navigationsleiste setzen, indem Sie `NavigationHubLayout.bottomNav` aus der `layout`-Methode zurückgeben.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Sie können die untere Navigationsleiste anpassen, indem Sie Eigenschaften wie folgt setzen:

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

Sie können einen vordefinierten Stil auf Ihre untere Navigationsleiste anwenden, indem Sie den Parameter `style` verwenden.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Standard Flutter Material-Stil
);
```

<div id="custom-nav-bar-builder"></div>

### Benutzerdefinierter Nav-Bar-Builder

Für die vollständige Kontrolle über Ihre Navigationsleiste können Sie den Parameter `navBarBuilder` verwenden.

Dies ermöglicht es Ihnen, ein beliebiges benutzerdefiniertes Widget zu erstellen und dabei die Navigationsdaten zu erhalten.

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

Das `NavBarData`-Objekt enthält:

| Eigenschaft | Typ | Beschreibung |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Die Navigationsleisten-Elemente |
| `currentIndex` | `int` | Der aktuell ausgewählte Index |
| `onTap` | `ValueChanged<int>` | Callback wenn ein Tab angetippt wird |

Hier ist ein Beispiel für eine vollständig benutzerdefinierte Glass-Navigationsleiste:

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

> **Hinweis:** Bei Verwendung von `navBarBuilder` wird der Parameter `style` ignoriert.

<div id="top-navigation"></div>

### Obere Navigation

Sie können das Layout auf eine obere Navigationsleiste ändern, indem Sie `NavigationHubLayout.topNav` aus der `layout`-Methode zurückgeben.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Sie können die obere Navigationsleiste anpassen, indem Sie Eigenschaften wie folgt setzen:

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

### Journey-Navigation

Sie können das Layout auf eine Journey-Navigation ändern, indem Sie `NavigationHubLayout.journey` aus der `layout`-Methode zurückgeben.

Dies eignet sich hervorragend für Onboarding-Flows oder mehrstufige Formulare.

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

Sie können auch einen `backgroundGradient` für das Journey-Layout setzen:

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

> **Hinweis:** Wenn `backgroundGradient` gesetzt ist, hat es Vorrang vor `backgroundColor`.

Wenn Sie das Journey-Navigations-Layout verwenden möchten, sollten Ihre **Widgets** `JourneyState` verwenden, da es viele Hilfsmethoden zur Verwaltung der Journey enthält.

Sie können die gesamte Journey mit dem Befehl `make:navigation_hub` und dem Layout `journey_states` erstellen:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Dadurch werden der Hub und alle Journey-State-Widgets unter `resources/pages/navigation_hubs/onboarding/states/` erstellt.

Alternativ können Sie einzelne Journey-Widgets erstellen mit:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Anschließend können Sie die neuen Widgets zum Navigation Hub hinzufügen.

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

### Journey-Fortschrittsstile

Sie können den Fortschrittsanzeige-Stil mit der Klasse `JourneyProgressStyle` anpassen.

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

Sie können die folgenden Fortschrittsindikatoren verwenden:

- `JourneyProgressIndicator.none()`: Rendert nichts -- nützlich, um den Indikator bei einem bestimmten Tab auszublenden.
- `JourneyProgressIndicator.linear()`: Linearer Fortschrittsbalken.
- `JourneyProgressIndicator.dots()`: Punkte-basierter Fortschrittsindikator.
- `JourneyProgressIndicator.numbered()`: Nummerierter Schritt-Fortschrittsindikator.
- `JourneyProgressIndicator.segments()`: Segmentierter Fortschrittsbalken-Stil.
- `JourneyProgressIndicator.circular()`: Kreisförmiger Fortschrittsindikator.
- `JourneyProgressIndicator.timeline()`: Timeline-artiger Fortschrittsindikator.
- `JourneyProgressIndicator.custom()`: Benutzerdefinierter Fortschrittsindikator mit einer Builder-Funktion.

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

Sie können die Position und den Abstand des Fortschrittsindikators innerhalb des `JourneyProgressStyle` anpassen:

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

Sie können die folgenden Positionen für den Fortschrittsindikator verwenden:

- `ProgressIndicatorPosition.top`: Fortschrittsindikator oben auf dem Bildschirm.
- `ProgressIndicatorPosition.bottom`: Fortschrittsindikator unten auf dem Bildschirm.

#### Pro-Tab Fortschrittsstil-Überschreibung

Sie können den `progressStyle` auf Layout-Ebene für einzelne Tabs überschreiben, indem Sie `NavigationTab.journey(progressStyle: ...)` verwenden. Tabs ohne eigenen `progressStyle` erben den Layout-Standard. Tabs ohne Layout-Standard und ohne eigenen Stil zeigen keinen Fortschrittsindikator an.

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

Die `JourneyState`-Klasse erweitert `NyState` um Journey-spezifische Funktionalität und erleichtert die Erstellung von Onboarding-Flows und mehrstufigen Journeys.

Um einen neuen `JourneyState` zu erstellen, können Sie den folgenden Befehl verwenden.

``` bash
metro make:journey_widget onboard_user_dob
```

Oder wenn Sie mehrere Widgets gleichzeitig erstellen möchten, können Sie den folgenden Befehl verwenden.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

So sieht ein generiertes JourneyState-Widget aus:

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
    // Ihre Initialisierungslogik hier
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

          // Navigations-Schaltflächen
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

  /// Prüfen, ob die Journey zum nächsten Schritt fortfahren kann
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Wird vor der Navigation zum nächsten Schritt aufgerufen
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Wird aufgerufen, wenn die Journey abgeschlossen ist (beim letzten Schritt)
  @override
  Future<void> onComplete() async {}
}
```

Sie werden bemerken, dass die **JourneyState**-Klasse `nextStep` verwendet, um vorwärts zu navigieren, und `onBackPressed`, um zurückzugehen.

Die `nextStep`-Methode durchläuft den vollständigen Validierungs-Lebenszyklus: `canContinue()` -> `onBeforeNext()` -> Navigation (oder `onComplete()` beim letzten Schritt) -> `onAfterNext()`.

Sie können auch `buildJourneyContent` verwenden, um ein strukturiertes Layout mit optionalen Navigations-Buttons zu erstellen:

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

Hier sind die Eigenschaften, die Sie in der `buildJourneyContent`-Methode verwenden können.

| Eigenschaft | Typ | Beschreibung |
| --- | --- | --- |
| `content` | `Widget` | Der Hauptinhalt der Seite. |
| `nextButton` | `Widget?` | Das Weiter-Button-Widget. |
| `backButton` | `Widget?` | Das Zurück-Button-Widget. |
| `contentPadding` | `EdgeInsetsGeometry` | Der Abstand für den Inhalt. |
| `header` | `Widget?` | Das Header-Widget. |
| `footer` | `Widget?` | Das Footer-Widget. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Die Querachsen-Ausrichtung des Inhalts. |

<div id="journey-state-helper-methods"></div>

### JourneyState-Hilfsmethoden

Die `JourneyState`-Klasse verfügt über Hilfsmethoden und Eigenschaften, mit denen Sie das Verhalten Ihrer Journey anpassen können.

| Methode / Eigenschaft | Beschreibung |
| --- | --- |
| [`nextStep()`](#next-step) | Navigiert zum nächsten Schritt mit Validierung. Gibt `Future<bool>` zurück. |
| [`previousStep()`](#previous-step) | Navigiert zum vorherigen Schritt. Gibt `Future<bool>` zurück. |
| [`onBackPressed()`](#on-back-pressed) | Einfacher Helfer zum Navigieren zum vorherigen Schritt. |
| [`onComplete()`](#on-complete) | Wird aufgerufen, wenn die Journey abgeschlossen ist (am letzten Schritt). |
| [`onBeforeNext()`](#on-before-next) | Wird aufgerufen, bevor zum nächsten Schritt navigiert wird. |
| [`onAfterNext()`](#on-after-next) | Wird aufgerufen, nachdem zum nächsten Schritt navigiert wurde. |
| [`canContinue()`](#can-continue) | Validierungsprüfung vor dem Navigieren zum nächsten Schritt. |
| [`isFirstStep`](#is-first-step) | Gibt true zurück, wenn dies der erste Schritt in der Journey ist. |
| [`isLastStep`](#is-last-step) | Gibt true zurück, wenn dies der letzte Schritt in der Journey ist. |
| [`currentStep`](#current-step) | Gibt den aktuellen Schritt-Index zurück (0-basiert). |
| [`totalSteps`](#total-steps) | Gibt die Gesamtanzahl der Schritte zurück. |
| [`completionPercentage`](#completion-percentage) | Gibt den Fortschritt in Prozent zurück (0.0 bis 1.0). |
| [`goToStep(int index)`](#go-to-step) | Springt zu einem bestimmten Schritt anhand des Index. |
| [`goToNextStep()`](#go-to-next-step) | Springt zum nächsten Schritt (ohne Validierung). |
| [`goToPreviousStep()`](#go-to-previous-step) | Springt zum vorherigen Schritt (ohne Validierung). |
| [`goToFirstStep()`](#go-to-first-step) | Springt zum ersten Schritt. |
| [`goToLastStep()`](#go-to-last-step) | Springt zum letzten Schritt. |
| [`exitJourney()`](#exit-journey) | Verlässt die Journey durch Pop des Root-Navigators. |
| [`resetCurrentStep()`](#reset-current-step) | Setzt den State des aktuellen Schritts zurück. |
| [`onJourneyComplete`](#on-journey-complete) | Callback wenn die Journey abgeschlossen wird (im letzten Schritt überschreiben). |
| [`buildJourneyPage()`](#build-journey-page) | Erstellt eine Vollbild-Journey-Seite mit Scaffold. |


<div id="next-step"></div>

#### nextStep

Die `nextStep`-Methode navigiert mit vollständiger Validierung zum nächsten Schritt. Sie durchläuft den Lebenszyklus: `canContinue()` -> `onBeforeNext()` -> Navigation oder `onComplete()` -> `onAfterNext()`.

Sie können `force: true` übergeben, um die Validierung zu umgehen und direkt zu navigieren.

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

Um die Validierung zu überspringen:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Die `previousStep`-Methode navigiert zum vorherigen Schritt. Gibt `true` bei Erfolg zurück, `false` wenn bereits beim ersten Schritt.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Bereits beim ersten Schritt
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

Die `onBackPressed`-Methode ist ein einfacher Helfer, der intern `previousStep()` aufruft.

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

Die `onComplete`-Methode wird aufgerufen, wenn `nextStep()` beim letzten Schritt ausgelöst wird (nachdem die Validierung bestanden wurde).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Die `onBeforeNext`-Methode wird aufgerufen, bevor zum nächsten Schritt navigiert wird.

Z.B. wenn Sie Daten speichern möchten, bevor zum nächsten Schritt navigiert wird, können Sie dies hier tun.

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

Die `onAfterNext`-Methode wird aufgerufen, nachdem zum nächsten Schritt navigiert wurde.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Die `canContinue`-Methode wird aufgerufen, wenn `nextStep()` ausgelöst wird. Geben Sie `false` zurück, um die Navigation zu verhindern.

``` dart
@override
Future<bool> canContinue() async {
    // Ihre Validierungslogik hier ausführen
    // True zurückgeben, wenn die Journey fortfahren kann, andernfalls false
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

Die `isFirstStep`-Eigenschaft gibt true zurück, wenn dies der erste Schritt in der Journey ist.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

Die `isLastStep`-Eigenschaft gibt true zurück, wenn dies der letzte Schritt in der Journey ist.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

Die `currentStep`-Eigenschaft gibt den aktuellen Schritt-Index zurück (0-basiert).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

Die `totalSteps`-Eigenschaft gibt die Gesamtanzahl der Schritte in der Journey zurück.

<div id="completion-percentage"></div>

#### completionPercentage

Die `completionPercentage`-Eigenschaft gibt den Fortschritt als Wert von 0.0 bis 1.0 zurück.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Die `goToStep`-Methode springt direkt zu einem bestimmten Schritt anhand des Index. Dies löst **keine** Validierung aus.

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

Die `goToNextStep`-Methode springt ohne Validierung zum nächsten Schritt. Wenn bereits beim letzten Schritt, passiert nichts.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Die `goToPreviousStep`-Methode springt ohne Validierung zum vorherigen Schritt. Wenn bereits beim ersten Schritt, passiert nichts.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Die `goToFirstStep`-Methode springt zum ersten Schritt.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

Die `goToLastStep`-Methode springt zum letzten Schritt.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

Die `exitJourney`-Methode verlässt die Journey durch Pop des Root-Navigators.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Die `resetCurrentStep`-Methode setzt den State des aktuellen Schritts zurück.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Der `onJourneyComplete`-Getter kann im **letzten Schritt** Ihrer Journey überschrieben werden, um festzulegen, was passiert, wenn der Benutzer den Flow abschließt.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback wenn die Journey abgeschlossen ist
  @override
  void Function()? get onJourneyComplete => () {
    // Zur Startseite oder nächsten Seite navigieren
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

Die `buildJourneyPage`-Methode erstellt eine Vollbild-Journey-Seite, die in ein `Scaffold` mit `SafeArea` eingebettet ist.

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

| Eigenschaft | Typ | Beschreibung |
| --- | --- | --- |
| `content` | `Widget` | Der Hauptinhalt der Seite. |
| `nextButton` | `Widget?` | Das Weiter-Button-Widget. |
| `backButton` | `Widget?` | Das Zurück-Button-Widget. |
| `contentPadding` | `EdgeInsetsGeometry` | Der Abstand für den Inhalt. |
| `header` | `Widget?` | Das Header-Widget. |
| `footer` | `Widget?` | Das Footer-Widget. |
| `backgroundColor` | `Color?` | Die Hintergrundfarbe des Scaffold. |
| `appBar` | `Widget?` | Ein optionales AppBar-Widget. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Die Querachsen-Ausrichtung des Inhalts. |

<div id="navigating-within-a-tab"></div>

## Innerhalb eines Tabs zu Widgets navigieren

Sie können innerhalb eines Tabs zu Widgets navigieren, indem Sie den `pushTo`-Helfer verwenden.

Innerhalb Ihres Tabs können Sie den `pushTo`-Helfer verwenden, um zu einem anderen Widget zu navigieren.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Sie können auch Daten an das Widget übergeben, zu dem Sie navigieren.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

Wenn Sie aus einem verschachtelten Navigator innerhalb eines Tabs herausspringen, verwenden Sie `rootNavigator: true`, um aus dem Root-Navigator statt aus dem lokalen Navigator des Tabs herausspringen:

``` dart
// Aus dem lokalen Navigator des Tabs herausspringen (Standard)
pop();

// Aus dem Root-Navigator herausspringen -- verwenden Sie dies, wenn ein Modal oder Overlay
// über den Root-Navigator geöffnet wurde
pop(rootNavigator: true);
```

Der Parameter `rootNavigator` ist für `pop()` in `NyState`, `NyController`, `StateAction.pop()` und der `BuildContext`-Erweiterung verfügbar.

<div id="tabs"></div>

## Tabs

Tabs sind die Hauptbausteine eines Navigation Hub.

Sie können Tabs zu einem Navigation Hub hinzufügen, indem Sie die `NavigationTab`-Klasse und ihre benannten Konstruktoren verwenden.

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

Im obigen Beispiel haben wir zwei Tabs zum Navigation Hub hinzugefügt: Home und Settings.

Sie können verschiedene Arten von Tabs verwenden:

- `NavigationTab.tab()` - Ein Standard-Navigations-Tab.
- `NavigationTab.badge()` - Ein Tab mit Badge-Zähler.
- `NavigationTab.alert()` - Ein Tab mit Alert-Indikator.
- `NavigationTab.journey()` - Ein Tab für Journey-Navigations-Layouts.

<div id="adding-badges-to-tabs"></div>

## Badges zu Tabs hinzufügen

Wir haben es einfach gemacht, Badges zu Ihren Tabs hinzuzufügen.

Badges sind eine großartige Möglichkeit, Benutzern zu zeigen, dass es etwas Neues in einem Tab gibt.

Wenn Sie beispielsweise eine Chat-App haben, können Sie die Anzahl ungelesener Nachrichten im Chat-Tab anzeigen.

Um ein Badge zu einem Tab hinzuzufügen, können Sie den Konstruktor `NavigationTab.badge` verwenden.

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

Im obigen Beispiel haben wir ein Badge zum Chat-Tab mit einer anfänglichen Anzahl von 10 hinzugefügt.

Sie können die Badge-Anzahl auch programmatisch aktualisieren.

``` dart
/// Badge-Anzahl erhöhen
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Badge-Anzahl aktualisieren
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Badge-Anzahl zurücksetzen
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Standardmäßig wird die Badge-Anzahl gespeichert. Wenn Sie die Badge-Anzahl bei jeder Sitzung **zurücksetzen** möchten, können Sie `rememberCount` auf `false` setzen.

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

## Alerts zu Tabs hinzufügen

Sie können Alerts zu Ihren Tabs hinzufügen.

Manchmal möchten Sie keine Badge-Anzahl anzeigen, sondern dem Benutzer lediglich einen Alert-Indikator anzeigen.

Um einen Alert zu einem Tab hinzuzufügen, können Sie den Konstruktor `NavigationTab.alert` verwenden.

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

Dies fügt dem Chat-Tab einen Alert mit roter Farbe hinzu.

Sie können den Alert auch programmatisch aktualisieren.

``` dart
/// Alert aktivieren
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Alert deaktivieren
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Initialer Index

Standardmäßig startet der Navigation Hub beim ersten Tab (Index 0). Sie können dies ändern, indem Sie den `initialIndex`-Getter überschreiben.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Beim zweiten Tab starten
    ...
}
```

<div id="maintaining-state"></div>

## State beibehalten

Standardmäßig wird der State des Navigation Hub beibehalten.

Das bedeutet, dass wenn Sie zu einem Tab navigieren, der State des Tabs erhalten bleibt.

Wenn Sie den State des Tabs bei jedem Navigieren zurücksetzen möchten, können Sie `maintainState` auf `false` setzen.

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

Sie können die `onTap`-Methode überschreiben, um benutzerdefinierte Logik hinzuzufügen, wenn ein Tab angetippt wird.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Benutzerdefinierte Logik hier hinzufügen
        // Z.B. Analytics verfolgen, Bestätigung anzeigen usw.
        super.onTap(index); // Super immer aufrufen, um den Tab-Wechsel zu verarbeiten
    }
}
```

<div id="state-actions"></div>

## State-Aktionen

State-Aktionen sind eine Möglichkeit, von überall in Ihrer App mit dem Navigation Hub zu interagieren.

Hier sind die State-Aktionen, die Sie verwenden können:

``` dart
/// Tab bei einem bestimmten Index zurücksetzen
/// Z.B. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Den aktuellen Tab programmatisch wechseln
/// Z.B. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Badge-Anzahl aktualisieren
/// Z.B. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Badge-Anzahl erhöhen
/// Z.B. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Badge-Anzahl zurücksetzen
/// Z.B. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Alert für einen Tab aktivieren
/// Z.B. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Alert für einen Tab deaktivieren
/// Z.B. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Zur nächsten Seite in einem Journey-Layout navigieren
/// Z.B. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Zur vorherigen Seite in einem Journey-Layout navigieren
/// Z.B. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();

/// Einen bestimmten Tab aktualisieren, sodass er neu aufgebaut wird
/// Z.B. MyNavigationHub.stateActions.refreshTab(0);
void refreshTab(int tabIndex);

/// Alle Tabs aktualisieren, sodass sie neu aufgebaut werden
/// Z.B. MyNavigationHub.stateActions.refresh();
void refresh();
```

Um eine State-Aktion zu verwenden, können Sie Folgendes tun:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Zum Tab 2 wechseln

await MyNavigationHub.stateActions.nextPage(); // Journey: zur nächsten Seite navigieren

MyNavigationHub.stateActions.refreshTab(0); // Tab 0 zum Neu-Aufbau zwingen

MyNavigationHub.stateActions.refresh(); // Alle Tabs zum Neu-Aufbau zwingen
```

<div id="loading-style"></div>

## Ladestil

Standardmäßig zeigt der Navigation Hub Ihr **Standard**-Lade-Widget (resources/widgets/loader_widget.dart) an, wenn der Tab geladen wird.

Sie können den `loadingStyle` anpassen, um den Ladestil zu ändern.

| Stil | Beschreibung |
| --- | --- |
| normal | Standard-Ladestil |
| skeletonizer | Skeleton-Ladestil |
| none | Kein Ladestil |

Sie können den Ladestil wie folgt ändern:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Wenn Sie das Lade-Widget in einem der Stile ändern möchten, können Sie ein `child` an den `LoadingStyle` übergeben.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Wenn der Tab geladen wird, wird nun der Text "Loading..." angezeigt.

Beispiel:

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

## Einen Navigation Hub erstellen

Um einen Navigation Hub zu erstellen, können Sie [Metro](/docs/{{$version}}/metro) verwenden. Nutzen Sie den folgenden Befehl.

``` bash
metro make:navigation_hub base
```

Der Befehl führt Sie durch ein interaktives Setup, bei dem Sie den Layout-Typ wählen und Ihre Tabs oder Journey-States definieren können.

Dadurch wird eine `base_navigation_hub.dart`-Datei in Ihrem `resources/pages/navigation_hubs/base/`-Verzeichnis erstellt, mit untergeordneten Widgets in den Unterordnern `tabs/` oder `states/`.
