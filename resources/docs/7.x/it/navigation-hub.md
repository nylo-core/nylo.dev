# Navigation Hub

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
  - [Creazione di un Navigation Hub](#creating-a-navigation-hub "Creazione di un Navigation Hub")
  - [Creazione delle Schede di Navigazione](#creating-navigation-tabs "Creazione delle Schede di Navigazione")
  - [Navigazione Inferiore](#bottom-navigation "Navigazione Inferiore")
    - [Stili della Navigazione Inferiore](#bottom-nav-styles "Stili della Navigazione Inferiore")
    - [Nav Bar Builder Personalizzato](#custom-nav-bar-builder "Nav Bar Builder Personalizzato")
  - [Navigazione Superiore](#top-navigation "Navigazione Superiore")
  - [Navigazione Journey](#journey-navigation "Navigazione Journey")
    - [Stili di Progresso](#journey-progress-styles "Stili di Progresso")
    - [Stili dei Pulsanti](#journey-button-styles "Stili dei Pulsanti")
    - [JourneyState](#journey-state "JourneyState")
    - [Metodi Helper di JourneyState](#journey-state-helper-methods "Metodi Helper di JourneyState")
- [Navigazione all'interno di una scheda](#navigating-within-a-tab "Navigazione all'interno di una scheda")
- [Schede](#tabs "Schede")
  - [Aggiungere Badge alle Schede](#adding-badges-to-tabs "Aggiungere Badge alle Schede")
  - [Aggiungere Avvisi alle Schede](#adding-alerts-to-tabs "Aggiungere Avvisi alle Schede")
- [Mantenimento dello stato](#maintaining-state "Mantenimento dello stato")
- [Azioni sullo Stato](#state-actions "Azioni sullo Stato")
- [Stile di Caricamento](#loading-style "Stile di Caricamento")
- [Creazione di un Navigation Hub](#creating-a-navigation-hub "Creazione di un Navigation Hub")

<div id="introduction"></div>

## Introduzione

I Navigation Hub sono un punto centrale dove puoi **gestire** la navigazione per tutti i tuoi widget.
Puoi creare layout di navigazione inferiore, superiore e journey in pochi secondi.

**Immaginiamo** di avere un'app e di voler aggiungere una barra di navigazione inferiore che permetta agli utenti di navigare tra diverse schede nell'app.

Puoi usare un Navigation Hub per costruire tutto questo.

Vediamo come utilizzare un Navigation Hub nella tua app.

<div id="basic-usage"></div>

## Utilizzo Base

Puoi creare un Navigation Hub usando il comando seguente.

``` bash
metro make:navigation_hub base
```

Questo creera' un file **base_navigation_hub.dart** nella tua directory `resources/pages/`.

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

Puoi vedere che il Navigation Hub ha **due** schede, Home e Settings.

Puoi creare altre schede aggiungendo NavigationTab al Navigation Hub.

Per prima cosa, devi creare un nuovo widget usando Metro.

``` bash
metro make:stateful_widget create_advert_tab
```

Puoi anche creare piu' widget contemporaneamente.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Quindi, puoi aggiungere il nuovo widget al Navigation Hub.

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

Ci sono molte **altre** cose che puoi fare con un Navigation Hub, esaminiamo alcune delle funzionalita'.

<div id="bottom-navigation"></div>

### Navigazione Inferiore

Puoi cambiare il layout in una barra di navigazione inferiore impostando il **layout** su `NavigationHubLayout.bottomNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

Puoi personalizzare la barra di navigazione inferiore impostando proprieta' come le seguenti:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // customize the bottomNav layout properties
    );
```

<div id="bottom-nav-styles"></div>

### Stili della Navigazione Inferiore

Puoi applicare stili preimpostati alla tua barra di navigazione inferiore usando il parametro `style`.

Nylo fornisce diversi stili integrati:

- `BottomNavStyle.material()` - Stile material Flutter predefinito
- `BottomNavStyle.glass()` - Effetto vetro smerigliato stile iOS 26 con sfocatura
- `BottomNavStyle.floating()` - Barra di navigazione a pillola flottante con angoli arrotondati

#### Stile Glass

Lo stile glass crea un bellissimo effetto vetro smerigliato, perfetto per design moderni ispirati a iOS 26.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

Puoi personalizzare l'effetto glass:

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

#### Stile Floating

Lo stile floating crea una barra di navigazione a forma di pillola che galleggia sopra il bordo inferiore.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

Puoi personalizzare lo stile floating:

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

### Nav Bar Builder Personalizzato

Per il controllo completo sulla tua barra di navigazione, puoi usare il parametro `navBarBuilder`.

Questo ti permette di costruire qualsiasi widget personalizzato continuando a ricevere i dati di navigazione.

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

L'oggetto `NavBarData` contiene:

| Proprieta' | Tipo | Descrizione |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Gli elementi della barra di navigazione |
| `currentIndex` | `int` | L'indice attualmente selezionato |
| `onTap` | `ValueChanged<int>` | Callback quando una scheda viene toccata |

Ecco un esempio di una barra di navigazione glass completamente personalizzata:

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

> **Nota:** Quando si usa `navBarBuilder`, il parametro `style` viene ignorato.

<div id="top-navigation"></div>

### Navigazione Superiore

Puoi cambiare il layout in una barra di navigazione superiore impostando il **layout** su `NavigationHubLayout.topNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

Puoi personalizzare la barra di navigazione superiore impostando proprieta' come le seguenti:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### Navigazione Journey

Puoi cambiare il layout in una navigazione journey impostando il **layout** su `NavigationHubLayout.journey`.

Questo e' ottimo per flussi di onboarding o moduli a piu' passaggi.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

Se vuoi usare il layout di navigazione journey, i tuoi **widget** dovrebbero usare `JourneyState` poiche' contiene molti metodi helper per aiutarti a gestire il percorso.

Puoi creare un JourneyState usando il comando seguente.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
Questo creera' i seguenti file nella tua directory **resources/widgets/**: `welcome.dart`, `phone_number_step.dart` e `add_photos_step.dart`.

Puoi quindi aggiungere i nuovi widget al Navigation Hub.

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

Il layout di navigazione journey gestira' automaticamente i pulsanti indietro e avanti se definisci un `buttonStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

Puoi anche personalizzare la logica nei tuoi widget.

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

Puoi sovrascrivere qualsiasi metodo nella classe `JourneyState`.

<div id="journey-progress-styles"></div>

### Stili di Progresso del Journey

Puoi personalizzare lo stile dell'indicatore di progresso usando la classe `JourneyProgressStyle`.

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

Puoi usare i seguenti stili di progresso:

- `JourneyProgressIndicator.none`: Non renderizza nulla — utile per nascondere l'indicatore su una scheda specifica.
- `JourneyProgressIndicator.linear`: Indicatore di progresso lineare.
- `JourneyProgressIndicator.dots`: Indicatore di progresso a punti.
- `JourneyProgressIndicator.numbered`: Indicatore di progresso con numeri dei passaggi.
- `JourneyProgressIndicator.segments`: Barra di progresso a segmenti.
- `JourneyProgressIndicator.circular`: Indicatore di progresso circolare.
- `JourneyProgressIndicator.timeline`: Indicatore di progresso stile timeline.
- `JourneyProgressIndicator.custom`: Indicatore di progresso personalizzato usando una funzione builder.

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

Puoi personalizzare la posizione e il padding dell'indicatore di progresso all'interno del `JourneyProgressStyle`:

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

Puoi usare le seguenti posizioni dell'indicatore di progresso:

- `ProgressIndicatorPosition.top`: Indicatore di progresso nella parte superiore dello schermo.
- `ProgressIndicatorPosition.bottom`: Indicatore di progresso nella parte inferiore dello schermo.

#### Override dello Stile di Progresso Per-Tab

Puoi sovrascrivere il `progressStyle` a livello di layout su singole schede usando `NavigationTab.journey(progressStyle: ...)`. Le schede senza il proprio `progressStyle` ereditano il default del layout. Le schede senza default del layout e senza stile per-tab non mostreranno un indicatore di progresso.

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

### Stili dei Pulsanti del Journey

Se vuoi costruire un flusso di onboarding, puoi impostare la proprieta' `buttonStyle` nella classe `NavigationHubLayout.journey`.

Sono disponibili i seguenti stili di pulsanti:

- `JourneyButtonStyle.standard`: Stile pulsante standard con proprieta' personalizzabili.
- `JourneyButtonStyle.minimal`: Stile pulsante minimale solo con icone.
- `JourneyButtonStyle.outlined`: Stile pulsante con contorno.
- `JourneyButtonStyle.contained`: Stile pulsante contenuto.
- `JourneyButtonStyle.custom`: Stile pulsante personalizzato usando funzioni builder.

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

La classe `JourneyState` contiene molti metodi helper per aiutarti a gestire il percorso.

Per creare un nuovo `JourneyState`, puoi usare il comando seguente.

``` bash
metro make:journey_widget onboard_user_dob
```

Oppure, se vuoi creare piu' widget contemporaneamente, puoi usare il comando seguente.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Questo creera' i seguenti file nella tua directory **resources/widgets/**: `welcome.dart`, `phone_number_step.dart` e `add_photos_step.dart`.

Puoi quindi aggiungere i nuovi widget al Navigation Hub.

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

Se guardiamo la classe `WelcomeStep`, possiamo vedere che estende la classe `JourneyState`.

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

Noterai che la classe **JourneyState** usa `buildJourneyContent` per costruire il contenuto della pagina.

Ecco un elenco delle proprieta' che puoi usare nel metodo `buildJourneyContent`.

| Proprieta' | Tipo | Descrizione |
| --- | --- | --- |
| `content` | `Widget` | Il contenuto principale della pagina. |
| `nextButton` | `Widget?` | Il widget del pulsante avanti. |
| `backButton` | `Widget?` | Il widget del pulsante indietro. |
| `contentPadding` | `EdgeInsetsGeometry` | Il padding per il contenuto. |
| `header` | `Widget?` | Il widget intestazione. |
| `footer` | `Widget?` | Il widget pie' di pagina. |
| `crossAxisAlignment` | `CrossAxisAlignment` | L'allineamento dell'asse trasversale del contenuto. |


<div id="journey-state-helper-methods"></div>

### Metodi Helper di JourneyState

La classe `JourneyState` ha alcuni metodi helper che puoi usare per personalizzare il comportamento del tuo percorso.

| Metodo | Descrizione |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | Chiamato quando il pulsante avanti viene premuto. |
| [`onBackPressed()`](#on-back-pressed) | Chiamato quando il pulsante indietro viene premuto. |
| [`onComplete()`](#on-complete) | Chiamato quando il percorso e' completato (all'ultimo passaggio). |
| [`onBeforeNext()`](#on-before-next) | Chiamato prima di navigare al passaggio successivo. |
| [`onAfterNext()`](#on-after-next) | Chiamato dopo aver navigato al passaggio successivo. |
| [`onCannotContinue()`](#on-cannot-continue) | Chiamato quando il percorso non puo' continuare (canContinue restituisce false). |
| [`canContinue()`](#can-continue) | Chiamato quando l'utente tenta di navigare al passaggio successivo. |
| [`isFirstStep`](#is-first-step) | Restituisce true se questo e' il primo passaggio del percorso. |
| [`isLastStep`](#is-last-step) | Restituisce true se questo e' l'ultimo passaggio del percorso. |
| [`goToStep(int index)`](#go-to-step) | Naviga al passaggio con l'indice specificato. |
| [`goToNextStep()`](#go-to-next-step) | Naviga al passaggio successivo. |
| [`goToPreviousStep()`](#go-to-previous-step) | Naviga al passaggio precedente. |
| [`goToFirstStep()`](#go-to-first-step) | Naviga al primo passaggio. |
| [`goToLastStep()`](#go-to-last-step) | Naviga all'ultimo passaggio. |


<div id="on-next-pressed"></div>

#### onNextPressed

Il metodo `onNextPressed` viene chiamato quando il pulsante avanti viene premuto.

Es. Puoi usare questo metodo per attivare il passaggio successivo nel percorso.

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

Il metodo `onBackPressed` viene chiamato quando il pulsante indietro viene premuto.

Es. Puoi usare questo metodo per attivare il passaggio precedente nel percorso.

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

Il metodo `onComplete` viene chiamato quando il percorso e' completato (all'ultimo passaggio).

Es. Se questo widget e' l'ultimo passaggio del percorso, questo metodo verra' chiamato.

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Il metodo `onBeforeNext` viene chiamato prima di navigare al passaggio successivo.

Es. Se vuoi salvare dei dati prima di navigare al passaggio successivo, puoi farlo qui.

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

Il metodo `isFirstStep` restituisce true se questo e' il primo passaggio del percorso.

Es. Puoi usare questo metodo per disabilitare il pulsante indietro se questo e' il primo passaggio.

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

Il metodo `isLastStep` restituisce true se questo e' l'ultimo passaggio del percorso.

Es. Puoi usare questo metodo per disabilitare il pulsante avanti se questo e' l'ultimo passaggio.

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

Il metodo `goToStep` viene usato per navigare a un passaggio specifico nel percorso.

Es. Puoi usare questo metodo per navigare a un passaggio specifico nel percorso.

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

Il metodo `goToNextStep` viene usato per navigare al passaggio successivo nel percorso.

Es. Puoi usare questo metodo per navigare al passaggio successivo nel percorso.

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

Il metodo `goToPreviousStep` viene usato per navigare al passaggio precedente nel percorso.

Es. Puoi usare questo metodo per navigare al passaggio precedente nel percorso.

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

Il metodo `onAfterNext` viene chiamato dopo aver navigato al passaggio successivo.


Es. Se vuoi eseguire un'azione dopo aver navigato al passaggio successivo, puoi farlo qui.

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

Il metodo `onCannotContinue` viene chiamato quando il percorso non puo' continuare (canContinue restituisce false).

Es. Se vuoi mostrare un messaggio di errore quando l'utente tenta di navigare al passaggio successivo senza compilare i campi obbligatori, puoi farlo qui.

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

Il metodo `canContinue` viene chiamato quando l'utente tenta di navigare al passaggio successivo.

Es. Se vuoi eseguire una validazione prima di navigare al passaggio successivo, puoi farlo qui.

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Il metodo `goToFirstStep` viene usato per navigare al primo passaggio del percorso.


Es. Puoi usare questo metodo per navigare al primo passaggio del percorso.

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

Il metodo `goToLastStep` viene usato per navigare all'ultimo passaggio del percorso.

Es. Puoi usare questo metodo per navigare all'ultimo passaggio del percorso.

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

## Navigazione verso widget all'interno di una scheda

Puoi navigare verso widget all'interno di una scheda usando l'helper `pushTo`.

All'interno della tua scheda, puoi usare l'helper `pushTo` per navigare verso un altro widget.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Puoi anche passare dati al widget verso cui stai navigando.

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

## Schede

Le schede sono i blocchi principali di un Navigation Hub.

Puoi aggiungere schede a un Navigation Hub usando la classe `NavigationTab`.

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

Nell'esempio sopra, abbiamo aggiunto due schede al Navigation Hub, Home e Settings.

Puoi usare diversi tipi di schede come `NavigationTab`, `NavigationTab.badge` e `NavigationTab.alert`.

- La classe `NavigationTab.badge` viene usata per aggiungere badge alle schede.
- La classe `NavigationTab.alert` viene usata per aggiungere avvisi alle schede.
- La classe `NavigationTab` viene usata per aggiungere una scheda normale.

<div id="adding-badges-to-tabs"></div>

## Aggiungere Badge alle Schede

Abbiamo reso facile aggiungere badge alle tue schede.

I badge sono un ottimo modo per mostrare agli utenti che c'e' qualcosa di nuovo in una scheda.

Ad esempio, se hai un'app di chat, puoi mostrare il numero di messaggi non letti nella scheda della chat.

Per aggiungere un badge a una scheda, puoi usare la classe `NavigationTab.badge`.

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

Nell'esempio sopra, abbiamo aggiunto un badge alla scheda Chat con un conteggio iniziale di 10.

Puoi anche aggiornare il conteggio del badge in modo programmatico.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Per impostazione predefinita, il conteggio del badge verra' ricordato. Se vuoi **cancellare** il conteggio del badge ad ogni sessione, puoi impostare `rememberCount` su `false`.

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

## Aggiungere Avvisi alle Schede

Puoi aggiungere avvisi alle tue schede.

A volte potresti non voler mostrare un conteggio badge, ma vuoi mostrare un avviso all'utente.

Per aggiungere un avviso a una scheda, puoi usare la classe `NavigationTab.alert`.

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

Questo aggiungera' un avviso alla scheda Chat con un colore rosso.

Puoi anche aggiornare l'avviso in modo programmatico.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="maintaining-state"></div>

## Mantenimento dello stato

Per impostazione predefinita, lo stato del Navigation Hub viene mantenuto.

Questo significa che quando navighi verso una scheda, lo stato della scheda viene preservato.

Se vuoi cancellare lo stato della scheda ogni volta che navighi verso di essa, puoi impostare `maintainState` su `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## Azioni sullo Stato

Le azioni sullo stato sono un modo per interagire con il Navigation Hub da qualsiasi punto della tua app.

Ecco alcune delle azioni sullo stato che puoi usare:

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

Per usare un'azione sullo stato, puoi fare quanto segue:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## Stile di Caricamento

Per impostazione predefinita, il Navigation Hub mostrera' il tuo Widget di caricamento **predefinito** (resources/widgets/loader_widget.dart) quando la scheda e' in fase di caricamento.

Puoi personalizzare il `loadingStyle` per aggiornare lo stile di caricamento.

Ecco una tabella per i diversi stili di caricamento che puoi usare:
// normal, skeletonizer, none

| Stile | Descrizione |
| --- | --- |
| normal | Stile di caricamento predefinito |
| skeletonizer | Stile di caricamento a scheletro |
| none | Nessuno stile di caricamento |

Puoi cambiare lo stile di caricamento in questo modo:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Se vuoi aggiornare il Widget di caricamento in uno degli stili, puoi passare un `child` al `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Ora, quando la scheda e' in fase di caricamento, verra' mostrato il testo "Loading...".

Esempio qui sotto:

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

## Creazione di un Navigation Hub

Per creare un Navigation Hub, puoi usare [Metro](/docs/{{$version}}/metro), usa il comando seguente.

``` bash
metro make:navigation_hub base
```

Questo creera' un file base_navigation_hub.dart nella tua directory `resources/pages/` e aggiungera' il Navigation Hub al tuo file `routes/router.dart`.
