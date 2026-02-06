# Navigation Hub

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
  - [Einen Navigation Hub erstellen](#creating-a-navigation-hub "Einen Navigation Hub erstellen")
  - [Navigations-Tabs erstellen](#creating-navigation-tabs "Navigations-Tabs erstellen")
  - [Untere Navigation](#bottom-navigation "Untere Navigation")
    - [Stile fuer die untere Navigation](#bottom-nav-styles "Stile fuer die untere Navigation")
    - [Benutzerdefinierter Nav-Bar-Builder](#custom-nav-bar-builder "Benutzerdefinierter Nav-Bar-Builder")
  - [Obere Navigation](#top-navigation "Obere Navigation")
  - [Journey-Navigation](#journey-navigation "Journey-Navigation")
    - [Fortschrittsstile](#journey-progress-styles "Journey-Fortschrittsstile")
    - [Button-Stile](#journey-button-styles "Button-Stile")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState-Hilfsmethoden](#journey-state-helper-methods "JourneyState-Hilfsmethoden")
- [Innerhalb eines Tabs navigieren](#navigating-within-a-tab "Innerhalb eines Tabs navigieren")
- [Tabs](#tabs "Tabs")
  - [Badges zu Tabs hinzufuegen](#adding-badges-to-tabs "Badges zu Tabs hinzufuegen")
  - [Alerts zu Tabs hinzufuegen](#adding-alerts-to-tabs "Alerts zu Tabs hinzufuegen")
- [State beibehalten](#maintaining-state "State beibehalten")
- [State-Aktionen](#state-actions "State-Aktionen")
- [Ladestil](#loading-style "Ladestil")
- [Einen Navigation Hub erstellen](#creating-a-navigation-hub "Einen Navigation Hub erstellen")

<div id="introduction"></div>

## Einleitung

Navigation Hubs sind ein zentraler Ort, an dem Sie die Navigation fuer all Ihre Widgets **verwalten** koennen.
Standardmaessig koennen Sie in Sekunden untere, obere und Journey-Navigations-Layouts erstellen.

Stellen wir uns **vor**, Sie haben eine App und moechten eine untere Navigationsleiste hinzufuegen, die es den Benutzern ermoeglicht, zwischen verschiedenen Tabs in Ihrer App zu navigieren.

Sie koennen einen Navigation Hub verwenden, um dies zu erstellen.

Schauen wir uns an, wie Sie einen Navigation Hub in Ihrer App verwenden koennen.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Sie koennen einen Navigation Hub mit dem folgenden Befehl erstellen.

``` bash
metro make:navigation_hub base
```

Dies erstellt eine **base_navigation_hub.dart**-Datei in Ihrem `resources/pages/`-Verzeichnis.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

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

  /// Layouts:
  /// - [NavigationHubLayout.bottomNav] Bottom navigation
  /// - [NavigationHubLayout.topNav] Top navigation
  /// - [NavigationHubLayout.journey] Journey navigation
  NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
    // backgroundColor: Colors.white,
  );

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// Navigation pages
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
    };
  });
}
```

Sie sehen, dass der Navigation Hub **zwei** Tabs hat: Home und Settings.

Sie koennen weitere Tabs erstellen, indem Sie NavigationTabs zum Navigation Hub hinzufuegen.

Zuerst muessen Sie ein neues Widget mit Metro erstellen.

``` bash
metro make:stateful_widget create_advert_tab
```

Sie koennen auch mehrere Widgets gleichzeitig erstellen.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Dann koennen Sie das neue Widget zum Navigation Hub hinzufuegen.

``` dart
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
      2: NavigationTab(
         title: "News",
         page: NewsTab(),
         icon: Icon(Icons.newspaper),
         activeIcon: Icon(Icons.newspaper),
      ),
    };
  });

import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Es gibt **viel mehr**, was Sie mit einem Navigation Hub machen koennen. Schauen wir uns einige der Funktionen an.

<div id="bottom-navigation"></div>

### Untere Navigation

Sie koennen das Layout zu einer unteren Navigationsleiste aendern, indem Sie das **layout** auf `NavigationHubLayout.bottomNav` setzen.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

Sie koennen die untere Navigationsleiste anpassen, indem Sie Eigenschaften wie folgt setzen:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // customize the bottomNav layout properties
    );
```

<div id="bottom-nav-styles"></div>

### Stile fuer die untere Navigation

Sie koennen vorgefertigte Stile auf Ihre untere Navigationsleiste anwenden, indem Sie den Parameter `style` verwenden.

Nylo bietet mehrere eingebaute Stile:

- `BottomNavStyle.material()` - Standard Flutter Material-Stil
- `BottomNavStyle.glass()` - iOS 26-artiger Milchglas-Effekt mit Unschaerfe
- `BottomNavStyle.floating()` - Schwebende Pillen-Navigationsleiste mit abgerundeten Ecken

#### Glass-Stil

Der Glass-Stil erzeugt einen schoenen Milchglas-Effekt, perfekt fuer moderne iOS 26-inspirierte Designs.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

Sie koennen den Glass-Effekt anpassen:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.glass(
        blur: 15.0,                              // Blur intensity
        opacity: 0.7,                            // Background opacity
        borderRadius: BorderRadius.circular(20), // Rounded corners
        margin: EdgeInsets.all(16),              // Float above the edge
        backgroundColor: Colors.white.withValues(alpha: 0.8),
    ),
)
```

#### Floating-Stil

Der Floating-Stil erzeugt eine pillenfoermige Navigationsleiste, die ueber dem unteren Rand schwebt.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

Sie koennen den Floating-Stil anpassen:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.floating(
        borderRadius: BorderRadius.circular(30),
        margin: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shadow: BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
        ),
        backgroundColor: Colors.white,
    ),
)
```

<div id="custom-nav-bar-builder"></div>

### Benutzerdefinierter Nav-Bar-Builder

Fuer die vollstaendige Kontrolle ueber Ihre Navigationsleiste koennen Sie den Parameter `navBarBuilder` verwenden.

Dies ermoeglicht es Ihnen, ein beliebiges benutzerdefiniertes Widget zu erstellen und dabei die Navigationsdaten zu erhalten.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
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

Sie koennen das Layout zu einer oberen Navigationsleiste aendern, indem Sie das **layout** auf `NavigationHubLayout.topNav` setzen.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

Sie koennen die obere Navigationsleiste anpassen, indem Sie Eigenschaften wie folgt setzen:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### Journey-Navigation

Sie koennen das Layout zu einer Journey-Navigation aendern, indem Sie das **layout** auf `NavigationHubLayout.journey` setzen.

Dies eignet sich hervorragend fuer Onboarding-Flows oder mehrstufige Formulare.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

Wenn Sie das Journey-Navigations-Layout verwenden moechten, sollten Ihre **Widgets** `JourneyState` verwenden, da es viele Hilfsmethoden enthaelt, um die Journey zu verwalten.

Sie koennen einen JourneyState mit dem folgenden Befehl erstellen.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
Dies erstellt die folgenden Dateien in Ihrem **resources/widgets/**-Verzeichnis: `welcome.dart`, `phone_number_step.dart` und `add_photos_step.dart`.

Sie koennen dann die neuen Widgets zum Navigation Hub hinzufuegen.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

Das Journey-Navigations-Layout behandelt automatisch die Zurueck- und Weiter-Buttons fuer Sie, wenn Sie einen `buttonStyle` definieren.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

Sie koennen auch die Logik in Ihren Widgets anpassen.

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class WelcomeStep extends StatefulWidget {
  const WelcomeStep({super.key});

  @override
  createState() => _WelcomeStepState();
}

class _WelcomeStepState extends JourneyState<WelcomeStep> {
  _WelcomeStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeStep', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: onNextPressed,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
  }

  /// Check if the journey can continue to the next step
  /// Override this method to add validation logic
  Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
  }

  /// Called when unable to continue (canContinue returns false)
  /// Override this method to handle validation failures
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// Called before navigating to the next step
  /// Override this method to perform actions before continuing
  Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
  }

  /// Called after navigating to the next step
  /// Override this method to perform actions after continuing
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// Called when the journey is complete (at the last step)
  /// Override this method to perform completion actions
  Future<void> onComplete() async {}
}
```

Sie koennen jede der Methoden in der `JourneyState`-Klasse ueberschreiben.

<div id="journey-progress-styles"></div>

### Journey-Fortschrittsstile

Sie koennen den Fortschrittsanzeige-Stil mit der Klasse `JourneyProgressStyle` anpassen.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(
            activeColor: Colors.blue,
            inactiveColor: Colors.grey,
            thickness: 4.0,
        ),
    );
```

Sie koennen die folgenden Fortschrittsstile verwenden:

- `JourneyProgressIndicator.none`: Rendert nichts — nuetzlich, um den Indikator bei einem bestimmten Tab auszublenden.
- `JourneyProgressIndicator.linear`: Linearer Fortschrittsindikator.
- `JourneyProgressIndicator.dots`: Punkte-basierter Fortschrittsindikator.
- `JourneyProgressIndicator.numbered`: Nummerierter Schritt-Fortschrittsindikator.
- `JourneyProgressIndicator.segments`: Segmentierter Fortschrittsbalken-Stil.
- `JourneyProgressIndicator.circular`: Kreisfoermiger Fortschrittsindikator.
- `JourneyProgressIndicator.timeline`: Timeline-artiger Fortschrittsindikator.
- `JourneyProgressIndicator.custom`: Benutzerdefinierter Fortschrittsindikator mit einer Builder-Funktion.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    );
```

Sie koennen die Position und den Abstand des Fortschrittsindikators innerhalb des `JourneyProgressStyle` anpassen:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.dots(),
            position: ProgressIndicatorPosition.bottom,
            padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        ),
    );
```

Sie koennen die folgenden Positionen des Fortschrittsindikators verwenden:

- `ProgressIndicatorPosition.top`: Fortschrittsindikator oben auf dem Bildschirm.
- `ProgressIndicatorPosition.bottom`: Fortschrittsindikator unten auf dem Bildschirm.

#### Pro-Tab Fortschrittsstil-Ueberschreibung

Sie koennen den `progressStyle` auf Layout-Ebene fuer einzelne Tabs ueberschreiben, indem Sie `NavigationTab.journey(progressStyle: ...)` verwenden. Tabs ohne eigenen `progressStyle` erben den Layout-Standard. Tabs ohne Layout-Standard und ohne eigenen Stil zeigen keinen Fortschrittsindikator an.

``` dart
_MyNavigationHubState() : super(() async {
    return {
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
    };
});
```

<div id="journey-button-styles">
<br>

### Journey-Button-Stile

Wenn Sie einen Onboarding-Flow erstellen moechten, koennen Sie die Eigenschaft `buttonStyle` in der Klasse `NavigationHubLayout.journey` setzen.

Standardmaessig koennen Sie die folgenden Button-Stile verwenden:

- `JourneyButtonStyle.standard`: Standard-Button-Stil mit anpassbaren Eigenschaften.
- `JourneyButtonStyle.minimal`: Minimaler Button-Stil nur mit Icons.
- `JourneyButtonStyle.outlined`: Umrandeter Button-Stil.
- `JourneyButtonStyle.contained`: Ausgefuellter Button-Stil.
- `JourneyButtonStyle.custom`: Benutzerdefinierter Button-Stil mit Builder-Funktionen.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

Die `JourneyState`-Klasse enthaelt viele Hilfsmethoden, um Ihnen bei der Verwaltung der Journey zu helfen.

Um einen neuen `JourneyState` zu erstellen, koennen Sie den folgenden Befehl verwenden.

``` bash
metro make:journey_widget onboard_user_dob
```

Oder wenn Sie mehrere Widgets gleichzeitig erstellen moechten, koennen Sie den folgenden Befehl verwenden.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Dies erstellt die folgenden Dateien in Ihrem **resources/widgets/**-Verzeichnis: `welcome.dart`, `phone_number_step.dart` und `add_photos_step.dart`.

Sie koennen dann die neuen Widgets zum Navigation Hub hinzufuegen.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

Wenn wir die `WelcomeStep`-Klasse betrachten, sehen wir, dass sie die `JourneyState`-Klasse erweitert.

``` dart
...
class _WelcomeTabState extends JourneyState<WelcomeTab> {
  _WelcomeTabState() : super(
      navigationHubState: BaseNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeTab', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
    );
  }
```

Sie werden bemerken, dass die **JourneyState**-Klasse `buildJourneyContent` verwendet, um den Inhalt der Seite zu erstellen.

Hier ist eine Liste der Eigenschaften, die Sie in der `buildJourneyContent`-Methode verwenden koennen.

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

Die `JourneyState`-Klasse hat einige Hilfsmethoden, die Sie verwenden koennen, um das Verhalten Ihrer Journey anzupassen.

| Methode | Beschreibung |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | Wird aufgerufen, wenn der Weiter-Button gedrueckt wird. |
| [`onBackPressed()`](#on-back-pressed) | Wird aufgerufen, wenn der Zurueck-Button gedrueckt wird. |
| [`onComplete()`](#on-complete) | Wird aufgerufen, wenn die Journey abgeschlossen ist (am letzten Schritt). |
| [`onBeforeNext()`](#on-before-next) | Wird aufgerufen, bevor zum naechsten Schritt navigiert wird. |
| [`onAfterNext()`](#on-after-next) | Wird aufgerufen, nachdem zum naechsten Schritt navigiert wurde. |
| [`onCannotContinue()`](#on-cannot-continue) | Wird aufgerufen, wenn die Journey nicht fortgesetzt werden kann (canContinue gibt false zurueck). |
| [`canContinue()`](#can-continue) | Wird aufgerufen, wenn der Benutzer versucht, zum naechsten Schritt zu navigieren. |
| [`isFirstStep`](#is-first-step) | Gibt true zurueck, wenn dies der erste Schritt in der Journey ist. |
| [`isLastStep`](#is-last-step) | Gibt true zurueck, wenn dies der letzte Schritt in der Journey ist. |
| [`goToStep(int index)`](#go-to-step) | Zum naechsten Schritt-Index navigieren. |
| [`goToNextStep()`](#go-to-next-step) | Zum naechsten Schritt navigieren. |
| [`goToPreviousStep()`](#go-to-previous-step) | Zum vorherigen Schritt navigieren. |
| [`goToFirstStep()`](#go-to-first-step) | Zum ersten Schritt navigieren. |
| [`goToLastStep()`](#go-to-last-step) | Zum letzten Schritt navigieren. |


<div id="on-next-pressed"></div>

#### onNextPressed

Die `onNextPressed`-Methode wird aufgerufen, wenn der Weiter-Button gedrueckt wird.

Z.B. Sie koennen diese Methode verwenden, um den naechsten Schritt in der Journey auszuloesen.

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
            onPressed: onNextPressed, // this will attempt to navigate to the next step
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

Die `onBackPressed`-Methode wird aufgerufen, wenn der Zurueck-Button gedrueckt wird.

Z.B. Sie koennen diese Methode verwenden, um den vorherigen Schritt in der Journey auszuloesen.

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
            onPressed: onBackPressed, // this will attempt to navigate to the previous step
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

Die `onComplete`-Methode wird aufgerufen, wenn die Journey abgeschlossen ist (am letzten Schritt).

Z.B. wenn dieses Widget der letzte Schritt in der Journey ist, wird diese Methode aufgerufen.

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Die `onBeforeNext`-Methode wird aufgerufen, bevor zum naechsten Schritt navigiert wird.

Z.B. wenn Sie Daten speichern moechten, bevor Sie zum naechsten Schritt navigieren, koennen Sie dies hier tun.

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

Die `isFirstStep`-Methode gibt true zurueck, wenn dies der erste Schritt in der Journey ist.

Z.B. Sie koennen diese Methode verwenden, um den Zurueck-Button zu deaktivieren, wenn dies der erste Schritt ist.

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
        backButton: isFirstStep ? null : Button.textOnly( // Example of disabling the back button
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

Die `isLastStep`-Methode gibt true zurueck, wenn dies der letzte Schritt in der Journey ist.

Z.B. Sie koennen diese Methode verwenden, um den Weiter-Button zu deaktivieren, wenn dies der letzte Schritt ist.

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
            text: isLastStep ? "Get Started" : "Continue", // Example updating the next button text
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

Die `goToStep`-Methode wird verwendet, um zu einem bestimmten Schritt in der Journey zu navigieren.

Z.B. Sie koennen diese Methode verwenden, um zu einem bestimmten Schritt in der Journey zu navigieren.

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
            text: "Add photos"
            onPressed: () {
                goToStep(2); // this will navigate to the step with index 2
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

Die `goToNextStep`-Methode wird verwendet, um zum naechsten Schritt in der Journey zu navigieren.

Z.B. Sie koennen diese Methode verwenden, um zum naechsten Schritt in der Journey zu navigieren.

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
            text: "Continue",
            onPressed: () {
                goToNextStep(); // this will navigate to the next step
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Die `goToPreviousStep`-Methode wird verwendet, um zum vorherigen Schritt in der Journey zu navigieren.

Z.B. Sie koennen diese Methode verwenden, um zum vorherigen Schritt in der Journey zu navigieren.

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
            onPressed: () {
                goToPreviousStep(); // this will navigate to the previous step
            },
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

Die `onAfterNext`-Methode wird aufgerufen, nachdem zum naechsten Schritt navigiert wurde.


Z.B. wenn Sie eine Aktion ausfuehren moechten, nachdem zum naechsten Schritt navigiert wurde, koennen Sie dies hier tun.

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

Die `onCannotContinue`-Methode wird aufgerufen, wenn die Journey nicht fortgesetzt werden kann (canContinue gibt false zurueck).

Z.B. wenn Sie eine Fehlermeldung anzeigen moechten, wenn der Benutzer versucht, zum naechsten Schritt zu navigieren, ohne die erforderlichen Felder auszufuellen, koennen Sie dies hier tun.

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

Die `canContinue`-Methode wird aufgerufen, wenn der Benutzer versucht, zum naechsten Schritt zu navigieren.

Z.B. wenn Sie vor dem Navigieren zum naechsten Schritt eine Validierung durchfuehren moechten, koennen Sie dies hier tun.

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Die `goToFirstStep`-Methode wird verwendet, um zum ersten Schritt in der Journey zu navigieren.


Z.B. Sie koennen diese Methode verwenden, um zum ersten Schritt in der Journey zu navigieren.

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
            text: "Continue",
            onPressed: () {
                goToFirstStep(); // this will navigate to the first step
            },
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

Die `goToLastStep`-Methode wird verwendet, um zum letzten Schritt in der Journey zu navigieren.

Z.B. Sie koennen diese Methode verwenden, um zum letzten Schritt in der Journey zu navigieren.

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
            text: "Continue",
            onPressed: () {
                goToLastStep(); // this will navigate to the last step
            },
        ),
    );
}
```

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

Sie koennen Tabs zu einem Navigation Hub hinzufuegen, indem Sie die `NavigationTab`-Klasse verwenden.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab(
                title: "Home",
                page: HomeTab(),
                icon: Icon(Icons.home),
                activeIcon: Icon(Icons.home),
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Im obigen Beispiel haben wir zwei Tabs zum Navigation Hub hinzugefuegt: Home und Settings.

Sie koennen verschiedene Arten von Tabs verwenden wie `NavigationTab`, `NavigationTab.badge` und `NavigationTab.alert`.

- Die Klasse `NavigationTab.badge` wird verwendet, um Badges zu Tabs hinzuzufuegen.
- Die Klasse `NavigationTab.alert` wird verwendet, um Alerts zu Tabs hinzuzufuegen.
- Die Klasse `NavigationTab` wird verwendet, um einen normalen Tab hinzuzufuegen.

<div id="adding-badges-to-tabs"></div>

## Badges zu Tabs hinzufuegen

Wir haben es einfach gemacht, Badges zu Ihren Tabs hinzuzufuegen.

Badges sind eine grossartige Moeglichkeit, Benutzern zu zeigen, dass es etwas Neues in einem Tab gibt.

Wenn Sie beispielsweise eine Chat-App haben, koennen Sie die Anzahl ungelesener Nachrichten im Chat-Tab anzeigen.

Um ein Badge zu einem Tab hinzuzufuegen, koennen Sie die Klasse `NavigationTab.badge` verwenden.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
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
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
                rememberCount: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

<div id="adding-alerts-to-tabs"></div>

## Alerts zu Tabs hinzufuegen

Sie koennen Alerts zu Ihren Tabs hinzufuegen.

Manchmal moechten Sie keine Badge-Anzahl anzeigen, sondern dem Benutzer einen Alert anzeigen.

Um einen Alert zu einem Tab hinzuzufuegen, koennen Sie die Klasse `NavigationTab.alert` verwenden.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.alert(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                alertColor: Colors.red,
                alertEnabled: true,
                rememberAlert: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
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

<div id="maintaining-state"></div>

## State beibehalten

Standardmaessig wird der State des Navigation Hub beibehalten.

Das bedeutet, dass wenn Sie zu einem Tab navigieren, der State des Tabs erhalten bleibt.

Wenn Sie den State des Tabs bei jedem Navigieren loeschen moechten, koennen Sie `maintainState` auf `false` setzen.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## State-Aktionen

State-Aktionen sind eine Moeglichkeit, mit dem Navigation Hub von ueberall in Ihrer App zu interagieren.

Hier sind einige der State-Aktionen, die Sie verwenden koennen:

``` dart
  /// Reset the tab
  /// E.g. MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Update the badge count
  /// E.g. MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Increment the badge count
  /// E.g. MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Clear the badge count
  /// E.g. MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

Um eine State-Aktion zu verwenden, koennen Sie Folgendes tun:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## Ladestil

Standardmaessig zeigt der Navigation Hub Ihr **Standard**-Lade-Widget (resources/widgets/loader_widget.dart) an, wenn der Tab laedt.

Sie koennen den `loadingStyle` anpassen, um den Ladestil zu aktualisieren.

Hier ist eine Tabelle fuer die verschiedenen Ladestile, die Sie verwenden koennen:
// normal, skeletonizer, none

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

Wenn Sie das Lade-Widget in einem der Stile aktualisieren moechten, koennen Sie ein `child` an den `LoadingStyle` uebergeben.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Wenn der Tab laedt, wird der Text "Loading..." angezeigt.

Beispiel unten:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
     _BaseNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab(
          title: "Home",
          page: HomeTab(),
          icon: Icon(Icons.home),
          activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab(
          title: "Settings",
          page: SettingsTab(),
          icon: Icon(Icons.settings),
          activeIcon: Icon(Icons.settings),
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

Dies erstellt eine base_navigation_hub.dart-Datei in Ihrem `resources/pages/`-Verzeichnis und fuegt den Navigation Hub zu Ihrer `routes/router.dart`-Datei hinzu.
