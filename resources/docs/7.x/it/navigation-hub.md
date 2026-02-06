# Navigation Hub

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
  - [Creazione di un Navigation Hub](#creating-a-navigation-hub "Creazione di un Navigation Hub")
  - [Creazione dei Tab di Navigazione](#creating-navigation-tabs "Creazione dei Tab di Navigazione")
  - [Navigazione Inferiore](#bottom-navigation "Navigazione Inferiore")
    - [Nav Bar Builder Personalizzata](#custom-nav-bar-builder "Nav Bar Builder Personalizzata")
  - [Navigazione Superiore](#top-navigation "Navigazione Superiore")
  - [Navigazione Journey](#journey-navigation "Navigazione Journey")
    - [Stili di Avanzamento](#journey-progress-styles "Stili di Avanzamento")
    - [JourneyState](#journey-state "JourneyState")
    - [Metodi Helper di JourneyState](#journey-state-helper-methods "Metodi Helper di JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Navigazione all'interno di un tab](#navigating-within-a-tab "Navigazione all'interno di un tab")
- [Tab](#tabs "Tab")
  - [Aggiungere Badge ai Tab](#adding-badges-to-tabs "Aggiungere Badge ai Tab")
  - [Aggiungere Alert ai Tab](#adding-alerts-to-tabs "Aggiungere Alert ai Tab")
- [Indice Iniziale](#initial-index "Indice Iniziale")
- [Mantenimento dello stato](#maintaining-state "Mantenimento dello stato")
- [onTap](#on-tap "onTap")
- [State Actions](#state-actions "State Actions")
- [Stile di Caricamento](#loading-style "Stile di Caricamento")

<div id="introduction"></div>

## Introduzione

I Navigation Hub sono un punto centrale in cui puoi **gestire** la navigazione di tutti i tuoi widget.
Fin da subito puoi creare layout di navigazione inferiore, superiore e journey in pochi secondi.

**Immaginiamo** di avere un'app e di voler aggiungere una barra di navigazione inferiore per permettere agli utenti di spostarsi tra i diversi tab dell'app.

Puoi utilizzare un Navigation Hub per realizzarlo.

Vediamo nel dettaglio come usare un Navigation Hub nella tua app.

<div id="basic-usage"></div>

## Utilizzo Base

Puoi creare un Navigation Hub usando il comando seguente.

``` bash
metro make:navigation_hub base
```

Il comando ti guiderà attraverso una configurazione interattiva:

1. **Scegli un tipo di layout** - Seleziona tra `navigation_tabs` (navigazione inferiore) o `journey_states` (flusso sequenziale).
2. **Inserisci i nomi dei tab/state** - Fornisci i nomi dei tuoi tab o journey state separati da virgola.

Questo creerà i file nella directory `resources/pages/navigation_hubs/base/`:
- `base_navigation_hub.dart` - Il widget hub principale
- `tabs/` o `states/` - Contiene i widget figli per ogni tab o journey state

Ecco come appare un Navigation Hub generato:

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

Come puoi vedere, il Navigation Hub ha **due** tab, Home e Settings.

Il metodo `layout` restituisce il tipo di layout per l'hub. Riceve un `BuildContext` così puoi accedere ai dati del tema e alle media query durante la configurazione del layout.

Puoi creare ulteriori tab aggiungendo `NavigationTab` al Navigation Hub.

Per prima cosa, devi creare un nuovo widget usando Metro.

``` bash
metro make:stateful_widget news_tab
```

Puoi anche creare più widget contemporaneamente.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Poi, puoi aggiungere il nuovo widget al Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Per utilizzare il Navigation Hub, aggiungilo al tuo router come route iniziale:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// oppure naviga verso il Navigation Hub da qualsiasi punto della tua app

routeTo(BaseNavigationHub.path);
```

Ci sono **molte** altre cose che puoi fare con un Navigation Hub, vediamo alcune delle funzionalità disponibili.

<div id="bottom-navigation"></div>

### Navigazione Inferiore

Puoi impostare il layout su una barra di navigazione inferiore restituendo `NavigationHubLayout.bottomNav` dal metodo `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Puoi personalizzare la barra di navigazione inferiore impostando proprietà come le seguenti:

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

Puoi applicare uno stile preimpostato alla barra di navigazione inferiore utilizzando il parametro `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Nav Bar Builder Personalizzata

Per avere il controllo completo sulla tua barra di navigazione, puoi utilizzare il parametro `navBarBuilder`.

Questo ti permette di costruire qualsiasi widget personalizzato ricevendo comunque i dati di navigazione.

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

L'oggetto `NavBarData` contiene:

| Proprieta' | Tipo | Descrizione |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Gli elementi della barra di navigazione |
| `currentIndex` | `int` | L'indice attualmente selezionato |
| `onTap` | `ValueChanged<int>` | Callback quando un tab viene toccato |

Ecco un esempio di una nav bar personalizzata con effetto vetro:

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

> **Nota:** Quando si utilizza `navBarBuilder`, il parametro `style` viene ignorato.

<div id="top-navigation"></div>

### Navigazione Superiore

Puoi cambiare il layout in una barra di navigazione superiore restituendo `NavigationHubLayout.topNav` dal metodo `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Puoi personalizzare la barra di navigazione superiore impostando proprietà come le seguenti:

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

### Navigazione Journey

Puoi cambiare il layout in una navigazione journey restituendo `NavigationHubLayout.journey` dal metodo `layout`.

Questo e' ideale per flussi di onboarding o form con piu' passaggi.

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

Puoi anche impostare un `backgroundGradient` per il layout journey:

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

> **Nota:** Quando `backgroundGradient` e' impostato, ha la precedenza su `backgroundColor`.

Se vuoi utilizzare il layout di navigazione journey, i tuoi **widget** dovrebbero usare `JourneyState` poiche' contiene molti metodi helper per gestire il journey.

Puoi creare l'intero journey usando il comando `make:navigation_hub` con il layout `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Seleziona: journey_states
# Inserisci: welcome, personal_info, add_photos
```

Questo creera' l'hub e tutti i widget journey state nella directory `resources/pages/navigation_hubs/onboarding/states/`.

Oppure puoi creare singoli widget journey usando:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Poi puoi aggiungere i nuovi widget al Navigation Hub.

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

### Stili di Avanzamento del Journey

Puoi personalizzare lo stile dell'indicatore di avanzamento usando la classe `JourneyProgressStyle`.

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

Puoi utilizzare i seguenti indicatori di avanzamento:

- `JourneyProgressIndicator.none()`: Non mostra nulla - utile per nascondere l'indicatore su un tab specifico.
- `JourneyProgressIndicator.linear()`: Barra di avanzamento lineare.
- `JourneyProgressIndicator.dots()`: Indicatore di avanzamento a punti.
- `JourneyProgressIndicator.numbered()`: Indicatore di avanzamento con numeri.
- `JourneyProgressIndicator.segments()`: Barra di avanzamento segmentata.
- `JourneyProgressIndicator.circular()`: Indicatore di avanzamento circolare.
- `JourneyProgressIndicator.timeline()`: Indicatore di avanzamento stile timeline.
- `JourneyProgressIndicator.custom()`: Indicatore di avanzamento personalizzato tramite una funzione builder.

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

Puoi personalizzare la posizione e il padding dell'indicatore di avanzamento all'interno di `JourneyProgressStyle`:

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

Puoi utilizzare le seguenti posizioni per l'indicatore di avanzamento:

- `ProgressIndicatorPosition.top`: Indicatore di avanzamento nella parte superiore dello schermo.
- `ProgressIndicatorPosition.bottom`: Indicatore di avanzamento nella parte inferiore dello schermo.

#### Override dello Stile di Avanzamento per Singolo Tab

Puoi sovrascrivere il `progressStyle` a livello di layout su singoli tab usando `NavigationTab.journey(progressStyle: ...)`. I tab senza un proprio `progressStyle` ereditano quello predefinito del layout. I tab senza un valore predefinito a livello di layout e senza stile specifico non mostreranno un indicatore di avanzamento.

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

La classe `JourneyState` estende `NyState` con funzionalita' specifiche per i journey, rendendo piu' semplice la creazione di flussi di onboarding e percorsi multi-step.

Per creare un nuovo `JourneyState`, puoi usare il comando seguente.

``` bash
metro make:journey_widget onboard_user_dob
```

Oppure, se vuoi creare piu' widget contemporaneamente, puoi usare il comando seguente.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Ecco come appare un widget JourneyState generato:

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

Noterai che la classe **JourneyState** usa `nextStep` per navigare avanti e `onBackPressed` per tornare indietro.

Il metodo `nextStep` esegue l'intero ciclo di vita della validazione: `canContinue()` -> `onBeforeNext()` -> naviga (oppure `onComplete()` se si e' all'ultimo step) -> `onAfterNext()`.

Puoi anche usare `buildJourneyContent` per costruire un layout strutturato con pulsanti di navigazione opzionali:

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

Ecco le proprieta' che puoi usare nel metodo `buildJourneyContent`.

| Proprieta' | Tipo | Descrizione |
| --- | --- | --- |
| `content` | `Widget` | Il contenuto principale della pagina. |
| `nextButton` | `Widget?` | Il widget del pulsante avanti. |
| `backButton` | `Widget?` | Il widget del pulsante indietro. |
| `contentPadding` | `EdgeInsetsGeometry` | Il padding del contenuto. |
| `header` | `Widget?` | Il widget dell'header. |
| `footer` | `Widget?` | Il widget del footer. |
| `crossAxisAlignment` | `CrossAxisAlignment` | L'allineamento dell'asse trasversale del contenuto. |

<div id="journey-state-helper-methods"></div>

### Metodi Helper di JourneyState

La classe `JourneyState` dispone di metodi helper e proprieta' che puoi usare per personalizzare il comportamento del tuo journey.

| Metodo / Proprieta' | Descrizione |
| --- | --- |
| [`nextStep()`](#next-step) | Naviga allo step successivo con validazione. Restituisce `Future<bool>`. |
| [`previousStep()`](#previous-step) | Naviga allo step precedente. Restituisce `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Helper semplice per navigare allo step precedente. |
| [`onComplete()`](#on-complete) | Chiamato quando il journey e' completato (all'ultimo step). |
| [`onBeforeNext()`](#on-before-next) | Chiamato prima di navigare allo step successivo. |
| [`onAfterNext()`](#on-after-next) | Chiamato dopo aver navigato allo step successivo. |
| [`canContinue()`](#can-continue) | Controllo di validazione prima di navigare allo step successivo. |
| [`isFirstStep`](#is-first-step) | Restituisce true se questo e' il primo step del journey. |
| [`isLastStep`](#is-last-step) | Restituisce true se questo e' l'ultimo step del journey. |
| [`currentStep`](#current-step) | Restituisce l'indice dello step corrente (base 0). |
| [`totalSteps`](#total-steps) | Restituisce il numero totale di step. |
| [`completionPercentage`](#completion-percentage) | Restituisce la percentuale di completamento (da 0.0 a 1.0). |
| [`goToStep(int index)`](#go-to-step) | Salta a uno step specifico tramite indice. |
| [`goToNextStep()`](#go-to-next-step) | Salta allo step successivo (senza validazione). |
| [`goToPreviousStep()`](#go-to-previous-step) | Salta allo step precedente (senza validazione). |
| [`goToFirstStep()`](#go-to-first-step) | Salta al primo step. |
| [`goToLastStep()`](#go-to-last-step) | Salta all'ultimo step. |
| [`exitJourney()`](#exit-journey) | Esce dal journey eseguendo il pop del navigator principale. |
| [`resetCurrentStep()`](#reset-current-step) | Resetta lo stato dello step corrente. |
| [`onJourneyComplete`](#on-journey-complete) | Callback al completamento del journey (da sovrascrivere nell'ultimo step). |
| [`buildJourneyPage()`](#build-journey-page) | Costruisce una pagina journey a schermo intero con Scaffold. |


<div id="next-step"></div>

#### nextStep

Il metodo `nextStep` naviga allo step successivo con validazione completa. Esegue il ciclo di vita: `canContinue()` -> `onBeforeNext()` -> naviga oppure `onComplete()` -> `onAfterNext()`.

Puoi passare `force: true` per saltare la validazione e navigare direttamente.

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

Per saltare la validazione:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Il metodo `previousStep` naviga allo step precedente. Restituisce `true` in caso di successo, `false` se si e' gia' al primo step.

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

Il metodo `onBackPressed` e' un helper semplice che chiama internamente `previousStep()`.

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

Il metodo `onComplete` viene chiamato quando `nextStep()` viene invocato sull'ultimo step (dopo che la validazione e' superata).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Il metodo `onBeforeNext` viene chiamato prima di navigare allo step successivo.

Ad esempio, se vuoi salvare dei dati prima di passare allo step successivo, puoi farlo qui.

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

Il metodo `onAfterNext` viene chiamato dopo aver navigato allo step successivo.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Il metodo `canContinue` viene chiamato quando `nextStep()` viene invocato. Restituisci `false` per impedire la navigazione.

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

La proprieta' `isFirstStep` restituisce true se questo e' il primo step del journey.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

La proprieta' `isLastStep` restituisce true se questo e' l'ultimo step del journey.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

La proprieta' `currentStep` restituisce l'indice dello step corrente (base 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

La proprieta' `totalSteps` restituisce il numero totale di step nel journey.

<div id="completion-percentage"></div>

#### completionPercentage

La proprieta' `completionPercentage` restituisce la percentuale di completamento come valore da 0.0 a 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Il metodo `goToStep` salta direttamente a uno step specifico tramite indice. Questo **non** attiva la validazione.

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

Il metodo `goToNextStep` salta allo step successivo senza validazione. Se si e' gia' all'ultimo step, non fa nulla.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Il metodo `goToPreviousStep` salta allo step precedente senza validazione. Se si e' gia' al primo step, non fa nulla.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Il metodo `goToFirstStep` salta al primo step.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

Il metodo `goToLastStep` salta all'ultimo step.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

Il metodo `exitJourney` esce dal journey eseguendo il pop del navigator principale.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Il metodo `resetCurrentStep` resetta lo stato dello step corrente.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Il getter `onJourneyComplete` puo' essere sovrascritto nell'**ultimo step** del tuo journey per definire cosa succede quando l'utente completa il flusso.

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

Il metodo `buildJourneyPage` costruisce una pagina journey a schermo intero racchiusa in uno `Scaffold` con `SafeArea`.

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

| Proprieta' | Tipo | Descrizione |
| --- | --- | --- |
| `content` | `Widget` | Il contenuto principale della pagina. |
| `nextButton` | `Widget?` | Il widget del pulsante avanti. |
| `backButton` | `Widget?` | Il widget del pulsante indietro. |
| `contentPadding` | `EdgeInsetsGeometry` | Il padding del contenuto. |
| `header` | `Widget?` | Il widget dell'header. |
| `footer` | `Widget?` | Il widget del footer. |
| `backgroundColor` | `Color?` | Il colore di sfondo dello Scaffold. |
| `appBar` | `Widget?` | Un widget AppBar opzionale. |
| `crossAxisAlignment` | `CrossAxisAlignment` | L'allineamento dell'asse trasversale del contenuto. |

<div id="navigating-within-a-tab"></div>

## Navigazione verso widget all'interno di un tab

Puoi navigare verso widget all'interno di un tab usando l'helper `pushTo`.

All'interno del tuo tab, puoi usare l'helper `pushTo` per navigare verso un altro widget.

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

## Tab

I tab sono gli elementi fondamentali di un Navigation Hub.

Puoi aggiungere tab a un Navigation Hub usando la classe `NavigationTab` e i suoi costruttori nominati.

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

Nell'esempio sopra, abbiamo aggiunto due tab al Navigation Hub, Home e Settings.

Puoi usare diversi tipi di tab:

- `NavigationTab.tab()` - Un tab di navigazione standard.
- `NavigationTab.badge()` - Un tab con contatore badge.
- `NavigationTab.alert()` - Un tab con indicatore di alert.
- `NavigationTab.journey()` - Un tab per layout di navigazione journey.

<div id="adding-badges-to-tabs"></div>

## Aggiungere Badge ai Tab

Abbiamo reso semplice aggiungere badge ai tuoi tab.

I badge sono un ottimo modo per mostrare agli utenti che c'e' qualcosa di nuovo in un tab.

Ad esempio, se hai un'app di chat, puoi mostrare il numero di messaggi non letti nel tab della chat.

Per aggiungere un badge a un tab, puoi usare il costruttore `NavigationTab.badge`.

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

Nell'esempio sopra, abbiamo aggiunto un badge al tab Chat con un conteggio iniziale di 10.

Puoi anche aggiornare il conteggio del badge in modo programmatico.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Per impostazione predefinita, il conteggio del badge viene memorizzato. Se vuoi **azzerare** il conteggio del badge ad ogni sessione, puoi impostare `rememberCount` su `false`.

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

## Aggiungere Alert ai Tab

Puoi aggiungere alert ai tuoi tab.

A volte potresti non voler mostrare un contatore badge, ma vuoi mostrare un indicatore di alert all'utente.

Per aggiungere un alert a un tab, puoi usare il costruttore `NavigationTab.alert`.

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

Questo aggiungera' un alert al tab Chat con colore rosso.

Puoi anche aggiornare l'alert in modo programmatico.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Indice Iniziale

Per impostazione predefinita, il Navigation Hub parte dal primo tab (indice 0). Puoi cambiarlo sovrascrivendo il getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Mantenimento dello stato

Per impostazione predefinita, lo stato del Navigation Hub viene mantenuto.

Questo significa che quando navighi verso un tab, lo stato del tab viene preservato.

Se vuoi cancellare lo stato del tab ogni volta che ci navighi, puoi impostare `maintainState` su `false`.

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

Puoi sovrascrivere il metodo `onTap` per aggiungere logica personalizzata quando un tab viene toccato.

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

## State Actions

Le state actions sono un modo per interagire con il Navigation Hub da qualsiasi punto della tua app.

Ecco le state actions che puoi utilizzare:

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

Per utilizzare una state action, puoi fare quanto segue:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Stile di Caricamento

Fin da subito, il Navigation Hub mostrera' il tuo Widget di caricamento **predefinito** (resources/widgets/loader_widget.dart) durante il caricamento del tab.

Puoi personalizzare il `loadingStyle` per aggiornare lo stile di caricamento.

| Stile | Descrizione |
| --- | --- |
| normal | Stile di caricamento predefinito |
| skeletonizer | Stile di caricamento skeleton |
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

Ora, quando il tab e' in caricamento, verra' visualizzato il testo "Loading...".

Esempio qui sotto:

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

## Creazione di un Navigation Hub

Per creare un Navigation Hub, puoi usare [Metro](/docs/{{$version}}/metro), utilizza il comando seguente.

``` bash
metro make:navigation_hub base
```

Il comando ti guidera' attraverso una configurazione interattiva in cui puoi scegliere il tipo di layout e definire i tuoi tab o journey state.

Questo creera' un file `base_navigation_hub.dart` nella directory `resources/pages/navigation_hubs/base/` con i widget figli organizzati nelle sottocartelle `tabs/` o `states/`.
