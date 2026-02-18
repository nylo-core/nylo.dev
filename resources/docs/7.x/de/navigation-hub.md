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
  - [Badges zu Tabs hinzufuegen](#adding-badges-to-tabs "Badges zu Tabs hinzufuegen")
  - [Alerts zu Tabs hinzufuegen](#adding-alerts-to-tabs "Alerts zu Tabs hinzufuegen")
- [Initialer Index](#initial-index "Initialer Index")
- [State beibehalten](#maintaining-state "State beibehalten")
- [onTap](#on-tap "onTap")
- [State-Aktionen](#state-actions "State-Aktionen")
- [Ladestil](#loading-style "Ladestil")

<div id="introduction"></div>

## Einleitung

Navigation Hubs sind ein zentraler Ort, an dem Sie die Navigation fuer all Ihre Widgets **verwalten** koennen.
Standardmaessig koennen Sie in Sekundenschnelle untere, obere und Journey-Navigations-Layouts erstellen.

Stellen wir uns **vor**, Sie haben eine App und moechten eine untere Navigationsleiste hinzufuegen, die es den Benutzern ermoeglicht, zwischen verschiedenen Tabs in Ihrer App zu navigieren.

Sie koennen einen Navigation Hub verwenden, um dies umzusetzen.

Schauen wir uns an, wie Sie einen Navigation Hub in Ihrer App verwenden koennen.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Sie koennen einen Navigation Hub mit dem folgenden Befehl erstellen.

``` bash
metro make:navigation_hub base
```

Der Befehl fuehrt Sie durch ein interaktives Setup:

1. **Layout-Typ waehlen** - Waehlen Sie zwischen `navigation_tabs` (untere Navigation) oder `journey_states` (sequenzieller Ablauf).
2. **Tab-/State-Namen eingeben** - Geben Sie kommagetrennte Namen fuer Ihre Tabs oder Journey-States an.

Dadurch werden Dateien unter Ihrem `resources/pages/navigation_hubs/base/`-Verzeichnis erstellt:
- `base_navigation_hub.dart` - Das Haupt-Hub-Widget
- `tabs/` oder `states/` - Enthaelt die untergeordneten Widgets fuer jeden Tab oder Journey-State

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

Sie sehen, dass der Navigation Hub **zwei** Tabs hat: Home und Settings.

Die `layout`-Methode gibt den Layout-Typ fuer den Hub zurueck. Sie erhaelt einen `BuildContext`, sodass Sie beim Konfigurieren Ihres Layouts auf Theme-Daten und Media Queries zugreifen koennen.

Sie koennen weitere Tabs erstellen, indem Sie `NavigationTab`s zum Navigation Hub hinzufuegen.

Zuerst muessen Sie ein neues Widget mit Metro erstellen.

``` bash
metro make:stateful_widget news_tab
```

Sie koennen auch mehrere Widgets gleichzeitig erstellen.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Dann koennen Sie das neue Widget zum Navigation Hub hinzufuegen.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Um den Navigation Hub zu verwenden, fuegen Sie ihn als initiale Route zu Ihrem Router hinzu:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Es gibt **noch viel mehr**, was Sie mit einem Navigation Hub machen koennen. Schauen wir uns einige der Funktionen an.

<div id="bottom-navigation"></div>

### Untere Navigation

Sie koennen das Layout auf eine untere Navigationsleiste setzen, indem Sie `NavigationHubLayout.bottomNav` aus der `layout`-Methode zurueckgeben.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Sie koennen die untere Navigationsleiste anpassen, indem Sie Eigenschaften wie folgt setzen:

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

Sie koennen einen vordefinierten Stil auf Ihre untere Navigationsleiste anwenden, indem Sie den Parameter `style` verwenden.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Benutzerdefinierter Nav-Bar-Builder

Fuer die vollstaendige Kontrolle ueber Ihre Navigationsleiste koennen Sie den Parameter `navBarBuilder` verwenden.

Dies ermoeglicht es Ihnen, ein beliebiges benutzerdefiniertes Widget zu erstellen und dabei die Navigationsdaten zu erhalten.

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

Das `NavBarData`-Objekt enthaelt:

| Eigenschaft | Typ | Beschreibung |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Die Navigationsleisten-Elemente |
| `currentIndex` | `int` | Der aktuell ausgewaehlte Index |
| `onTap` | `ValueChanged<int>` | Callback wenn ein Tab angetippt wird |

Hier ist ein Beispiel fuer eine vollstaendig benutzerdefinierte Glass-Navigationsleiste:

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

Sie koennen das Layout auf eine obere Navigationsleiste aendern, indem Sie `NavigationHubLayout.topNav` aus der `layout`-Methode zurueckgeben.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Sie koennen die obere Navigationsleiste anpassen, indem Sie Eigenschaften wie folgt setzen:

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

Sie koennen das Layout auf eine Journey-Navigation aendern, indem Sie `NavigationHubLayout.journey` aus der `layout`-Methode zurueckgeben.

Dies eignet sich hervorragend fuer Onboarding-Flows oder mehrstufige Formulare.

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

Sie koennen auch einen `backgroundGradient` fuer das Journey-Layout setzen:

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

Wenn Sie das Journey-Navigations-Layout verwenden moechten, sollten Ihre **Widgets** `JourneyState` verwenden, da es viele Hilfsmethoden zur Verwaltung der Journey enthaelt.

Sie koennen die gesamte Journey mit dem Befehl `make:navigation_hub` und dem Layout `journey_states` erstellen:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Dadurch werden der Hub und alle Journey-State-Widgets unter `resources/pages/navigation_hubs/onboarding/states/` erstellt.

Alternativ koennen Sie einzelne Journey-Widgets erstellen mit:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Anschliessend koennen Sie die neuen Widgets zum Navigation Hub hinzufuegen.

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

Sie koennen den Fortschrittsanzeige-Stil mit der Klasse `JourneyProgressStyle` anpassen.

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

Sie koennen die folgenden Fortschrittsindikatoren verwenden:

- `JourneyProgressIndicator.none()`: Rendert nichts -- nuetzlich, um den Indikator bei einem bestimmten Tab auszublenden.
- `JourneyProgressIndicator.linear()`: Linearer Fortschrittsbalken.
- `JourneyProgressIndicator.dots()`: Punkte-basierter Fortschrittsindikator.
- `JourneyProgressIndicator.numbered()`: Nummerierter Schritt-Fortschrittsindikator.
- `JourneyProgressIndicator.segments()`: Segmentierter Fortschrittsbalken-Stil.
- `JourneyProgressIndicator.circular()`: Kreisfoermiger Fortschrittsindikator.
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

Sie koennen die Position und den Abstand des Fortschrittsindikators innerhalb des `JourneyProgressStyle` anpassen:

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

Sie koennen die folgenden Positionen fuer den Fortschrittsindikator verwenden:

- `ProgressIndicatorPosition.top`: Fortschrittsindikator oben auf dem Bildschirm.
- `ProgressIndicatorPosition.bottom`: Fortschrittsindikator unten auf dem Bildschirm.

#### Pro-Tab Fortschrittsstil-Ueberschreibung

Sie koennen den `progressStyle` auf Layout-Ebene fuer einzelne Tabs ueberschreiben, indem Sie `NavigationTab.journey(progressStyle: ...)` verwenden. Tabs ohne eigenen `progressStyle` erben den Layout-Standard. Tabs ohne Layout-Standard und ohne eigenen Stil zeigen keinen Fortschrittsindikator an.

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

Die `JourneyState`-Klasse erweitert `NyState` um Journey-spezifische Funktionalitaet und erleichtert die Erstellung von Onboarding-Flows und mehrstufigen Journeys.

Um einen neuen `JourneyState` zu erstellen, koennen Sie den folgenden Befehl verwenden.

``` bash
metro make:journey_widget onboard_user_dob
```

Oder wenn Sie mehrere Widgets gleichzeitig erstellen moechten, koennen Sie den folgenden Befehl verwenden.

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

Sie werden bemerken, dass die **JourneyState**-Klasse `nextStep` verwendet, um vorwaerts zu navigieren, und `onBackPressed`, um zurueckzugehen.

Die `nextStep`-Methode durchlaeuft den vollstaendigen Validierungs-Lebenszyklus: `canContinue()` -> `onBeforeNext()` -> Navigation (oder `onComplete()` beim letzten Schritt) -> `onAfterNext()`.

Sie koennen auch `buildJourneyContent` verwenden, um ein strukturiertes Layout mit optionalen Navigations-Buttons zu erstellen:

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

Hier sind die Eigenschaften, die Sie in der `buildJourneyContent`-Methode verwenden koennen.

| Eigenschaft | Typ | Beschreibung |
| --- | --- | --- |
| `content` | `Widget` | Der Hauptinhalt der Seite. |
| `nextButton` | `Widget?` | Das Weiter-Button-Widget. |
| `backButton` | `Widget?` | Das Zurueck-Button-Widget. |
| `contentPadding` | `EdgeInsetsGeometry` | Der Abstand fuer den Inhalt. |
| `header` | `Widget?` | Das Header-Widget. |
| `footer` | `Widget?` | Das Footer-Widget. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Die Querachsen-Ausrichtung des Inhalts. |

<div id="journey-state-helper-methods"></div>

### JourneyState-Hilfsmethoden

Die `JourneyState`-Klasse verfuegt ueber Hilfsmethoden und Eigenschaften, mit denen Sie das Verhalten Ihrer Journey anpassen koennen.

| Methode / Eigenschaft | Beschreibung |
| --- | --- |
| [`nextStep()`](#next-step) | Navigiert zum naechsten Schritt mit Validierung. Gibt `Future<bool>` zurueck. |
| [`previousStep()`](#previous-step) | Navigiert zum vorherigen Schritt. Gibt `Future<bool>` zurueck. |
| [`onBackPressed()`](#on-back-pressed) | Einfacher Helfer zum Navigieren zum vorherigen Schritt. |
| [`onComplete()`](#on-complete) | Wird aufgerufen, wenn die Journey abgeschlossen ist (am letzten Schritt). |
| [`onBeforeNext()`](#on-before-next) | Wird aufgerufen, bevor zum naechsten Schritt navigiert wird. |
| [`onAfterNext()`](#on-after-next) | Wird aufgerufen, nachdem zum naechsten Schritt navigiert wurde. |
| [`canContinue()`](#can-continue) | Validierungspruefung vor dem Navigieren zum naechsten Schritt. |
| [`isFirstStep`](#is-first-step) | Gibt true zurueck, wenn dies der erste Schritt in der Journey ist. |
| [`isLastStep`](#is-last-step) | Gibt true zurueck, wenn dies der letzte Schritt in der Journey ist. |
| [`currentStep`](#current-step) | Gibt den aktuellen Schritt-Index zurueck (0-basiert). |
| [`totalSteps`](#total-steps) | Gibt die Gesamtanzahl der Schritte zurueck. |
| [`completionPercentage`](#completion-percentage) | Gibt den Fortschritt in Prozent zurueck (0.0 bis 1.0). |
| [`goToStep(int index)`](#go-to-step) | Springt zu einem bestimmten Schritt anhand des Index. |
| [`goToNextStep()`](#go-to-next-step) | Springt zum naechsten Schritt (ohne Validierung). |
| [`goToPreviousStep()`](#go-to-previous-step) | Springt zum vorherigen Schritt (ohne Validierung). |
| [`goToFirstStep()`](#go-to-first-step) | Springt zum ersten Schritt. |
| [`goToLastStep()`](#go-to-last-step) | Springt zum letzten Schritt. |
| [`exitJourney()`](#exit-journey) | Verlaesst die Journey durch Pop des Root-Navigators. |
| [`resetCurrentStep()`](#reset-current-step) | Setzt den State des aktuellen Schritts zurueck. |
| [`onJourneyComplete`](#on-journey-complete) | Callback wenn die Journey abgeschlossen wird (im letzten Schritt ueberschreiben). |
| [`buildJourneyPage()`](#build-journey-page) | Erstellt eine Vollbild-Journey-Seite mit Scaffold. |


<div id="next-step"></div>

#### nextStep

Die `nextStep`-Methode navigiert mit vollstaendiger Validierung zum naechsten Schritt. Sie durchlaeuft den Lebenszyklus: `canContinue()` -> `onBeforeNext()` -> Navigation oder `onComplete()` -> `onAfterNext()`.

Sie koennen `force: true` uebergeben, um die Validierung zu umgehen und direkt zu navigieren.

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

Um die Validierung zu ueberspringen:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Die `previousStep`-Methode navigiert zum vorherigen Schritt. Gibt `true` bei Erfolg zurueck, `false` wenn bereits beim ersten Schritt.

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

Die `onComplete`-Methode wird aufgerufen, wenn `nextStep()` beim letzten Schritt ausgeloest wird (nachdem die Validierung bestanden wurde).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Die `onBeforeNext`-Methode wird aufgerufen, bevor zum naechsten Schritt navigiert wird.

Z.B. wenn Sie Daten speichern moechten, bevor zum naechsten Schritt navigiert wird, koennen Sie dies hier tun.

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

Die `onAfterNext`-Methode wird aufgerufen, nachdem zum naechsten Schritt navigiert wurde.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Die `canContinue`-Methode wird aufgerufen, wenn `nextStep()` ausgeloest wird. Geben Sie `false` zurueck, um die Navigation zu verhindern.

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

Die `isFirstStep`-Eigenschaft gibt true zurueck, wenn dies der erste Schritt in der Journey ist.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

Die `isLastStep`-Eigenschaft gibt true zurueck, wenn dies der letzte Schritt in der Journey ist.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

Die `currentStep`-Eigenschaft gibt den aktuellen Schritt-Index zurueck (0-basiert).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

Die `totalSteps`-Eigenschaft gibt die Gesamtanzahl der Schritte in der Journey zurueck.

<div id="completion-percentage"></div>

#### completionPercentage

Die `completionPercentage`-Eigenschaft gibt den Fortschritt als Wert von 0.0 bis 1.0 zurueck.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Die `goToStep`-Methode springt direkt zu einem bestimmten Schritt anhand des Index. Dies loest **keine** Validierung aus.

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

Die `goToNextStep`-Methode springt ohne Validierung zum naechsten Schritt. Wenn bereits beim letzten Schritt, passiert nichts.

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

Die `exitJourney`-Methode verlaesst die Journey durch Pop des Root-Navigators.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Die `resetCurrentStep`-Methode setzt den State des aktuellen Schritts zurueck.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Der `onJourneyComplete`-Getter kann im **letzten Schritt** Ihrer Journey ueberschrieben werden, um festzulegen, was passiert, wenn der Benutzer den Flow abschliesst.

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
| `backButton` | `Widget?` | Das Zurueck-Button-Widget. |
| `contentPadding` | `EdgeInsetsGeometry` | Der Abstand fuer den Inhalt. |
| `header` | `Widget?` | Das Header-Widget. |
| `footer` | `Widget?` | Das Footer-Widget. |
| `backgroundColor` | `Color?` | Die Hintergrundfarbe des Scaffold. |
| `appBar` | `Widget?` | Ein optionales AppBar-Widget. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Die Querachsen-Ausrichtung des Inhalts. |

<div id="navigating-within-a-tab"></div>

## Innerhalb eines Tabs zu Widgets navigieren

Sie koennen innerhalb eines Tabs zu Widgets navigieren, indem Sie den `pushTo`-Helfer verwenden.

Innerhalb Ihres Tabs koennen Sie den `pushTo`-Helfer verwenden, um zu einem anderen Widget zu navigieren.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Sie koennen auch Daten an das Widget uebergeben, zu dem Sie navigieren.

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

## Tabs

Tabs sind die Hauptbausteine eines Navigation Hub.

Sie koennen Tabs zu einem Navigation Hub hinzufuegen, indem Sie die `NavigationTab`-Klasse und ihre benannten Konstruktoren verwenden.

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

Im obigen Beispiel haben wir zwei Tabs zum Navigation Hub hinzugefuegt: Home und Settings.

Sie koennen verschiedene Arten von Tabs verwenden:

- `NavigationTab.tab()` - Ein Standard-Navigations-Tab.
- `NavigationTab.badge()` - Ein Tab mit Badge-Zaehler.
- `NavigationTab.alert()` - Ein Tab mit Alert-Indikator.
- `NavigationTab.journey()` - Ein Tab fuer Journey-Navigations-Layouts.

<div id="adding-badges-to-tabs"></div>

## Badges zu Tabs hinzufuegen

Wir haben es einfach gemacht, Badges zu Ihren Tabs hinzuzufuegen.

Badges sind eine grossartige Moeglichkeit, Benutzern zu zeigen, dass es etwas Neues in einem Tab gibt.

Wenn Sie beispielsweise eine Chat-App haben, koennen Sie die Anzahl ungelesener Nachrichten im Chat-Tab anzeigen.

Um ein Badge zu einem Tab hinzuzufuegen, koennen Sie den Konstruktor `NavigationTab.badge` verwenden.

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

Im obigen Beispiel haben wir ein Badge zum Chat-Tab mit einer anfaenglichen Anzahl von 10 hinzugefuegt.

Sie koennen die Badge-Anzahl auch programmatisch aktualisieren.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Standardmaessig wird die Badge-Anzahl gespeichert. Wenn Sie die Badge-Anzahl bei jeder Sitzung **zuruecksetzen** moechten, koennen Sie `rememberCount` auf `false` setzen.

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

## Alerts zu Tabs hinzufuegen

Sie koennen Alerts zu Ihren Tabs hinzufuegen.

Manchmal moechten Sie keine Badge-Anzahl anzeigen, sondern dem Benutzer lediglich einen Alert-Indikator anzeigen.

Um einen Alert zu einem Tab hinzuzufuegen, koennen Sie den Konstruktor `NavigationTab.alert` verwenden.

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

Dies fuegt dem Chat-Tab einen Alert mit roter Farbe hinzu.

Sie koennen den Alert auch programmatisch aktualisieren.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Initialer Index

Standardmaessig startet der Navigation Hub beim ersten Tab (Index 0). Sie koennen dies aendern, indem Sie den `initialIndex`-Getter ueberschreiben.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## State beibehalten

Standardmaessig wird der State des Navigation Hub beibehalten.

Das bedeutet, dass wenn Sie zu einem Tab navigieren, der State des Tabs erhalten bleibt.

Wenn Sie den State des Tabs bei jedem Navigieren zuruecksetzen moechten, koennen Sie `maintainState` auf `false` setzen.

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

Sie koennen die `onTap`-Methode ueberschreiben, um benutzerdefinierte Logik hinzuzufuegen, wenn ein Tab angetippt wird.

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

## State-Aktionen

State-Aktionen sind eine Moeglichkeit, von ueberall in Ihrer App mit dem Navigation Hub zu interagieren.

Hier sind die State-Aktionen, die Sie verwenden koennen:

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

Um eine State-Aktion zu verwenden, koennen Sie Folgendes tun:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Ladestil

Standardmaessig zeigt der Navigation Hub Ihr **Standard**-Lade-Widget (resources/widgets/loader_widget.dart) an, wenn der Tab geladen wird.

Sie koennen den `loadingStyle` anpassen, um den Ladestil zu aendern.

| Stil | Beschreibung |
| --- | --- |
| normal | Standard-Ladestil |
| skeletonizer | Skeleton-Ladestil |
| none | Kein Ladestil |

Sie koennen den Ladestil wie folgt aendern:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Wenn Sie das Lade-Widget in einem der Stile aendern moechten, koennen Sie ein `child` an den `LoadingStyle` uebergeben.

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

Um einen Navigation Hub zu erstellen, koennen Sie [Metro](/docs/{{$version}}/metro) verwenden. Nutzen Sie den folgenden Befehl.

``` bash
metro make:navigation_hub base
```

Der Befehl fuehrt Sie durch ein interaktives Setup, bei dem Sie den Layout-Typ waehlen und Ihre Tabs oder Journey-States definieren koennen.

Dadurch wird eine `base_navigation_hub.dart`-Datei in Ihrem `resources/pages/navigation_hubs/base/`-Verzeichnis erstellt, mit untergeordneten Widgets in den Unterordnern `tabs/` oder `states/`.
