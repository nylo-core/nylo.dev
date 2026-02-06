# Router

---

<a name="section-1"></a>

- [Introduzione](#introduction "Introduzione")
- Fondamenti
  - [Aggiungere rotte](#adding-routes "Aggiungere rotte")
  - [Navigare verso le pagine](#navigating-to-pages "Navigare verso le pagine")
  - [Rotta iniziale](#initial-route "Rotta iniziale")
  - [Rotta di Anteprima](#preview-route "Rotta di Anteprima")
  - [Rotta autenticata](#authenticated-route "Rotta autenticata")
  - [Rotta Sconosciuta](#unknown-route "Rotta Sconosciuta")
- Invio di dati a un'altra pagina
  - [Passaggio di dati a un'altra pagina](#passing-data-to-another-page "Passaggio di dati a un'altra pagina")
- Navigazione
  - [Tipi di navigazione](#navigation-types "Tipi di navigazione")
  - [Navigare indietro](#navigating-back "Navigare indietro")
  - [Navigazione Condizionale](#conditional-navigation "Navigazione Condizionale")
  - [Transizioni di pagina](#page-transitions "Transizioni di pagina")
  - [Cronologia delle Rotte](#route-history "Cronologia delle Rotte")
  - [Aggiornare lo Stack delle Rotte](#update-route-stack "Aggiornare lo Stack delle Rotte")
- Parametri delle rotte
  - [Utilizzo dei Parametri delle Rotte](#route-parameters "Parametri delle Rotte")
  - [Parametri Query](#query-parameters "Parametri Query")
- Route Guard
  - [Creazione di Route Guard](#route-guards "Route Guard")
  - [Ciclo di Vita NyRouteGuard](#nyroute-guard-lifecycle "Ciclo di Vita NyRouteGuard")
  - [Metodi Helper dei Guard](#guard-helper-methods "Metodi Helper dei Guard")
  - [Guard Parametrizzati](#parameterized-guards "Guard Parametrizzati")
  - [Stack di Guard](#guard-stacks "Stack di Guard")
  - [Guard Condizionali](#conditional-guards "Guard Condizionali")
- Gruppi di Rotte
  - [Gruppi di Rotte](#route-groups "Gruppi di Rotte")
- [Deep linking](#deep-linking "Deep linking")
- [Avanzato](#advanced "Avanzato")



<div id="introduction"></div>

## Introduzione

Le rotte ti permettono di definire le diverse pagine nella tua app e navigare tra di esse.

Usa le rotte quando hai bisogno di:
- Definire le pagine disponibili nella tua app
- Navigare gli utenti tra le schermate
- Proteggere le pagine dietro l'autenticazione
- Passare dati da una pagina all'altra
- Gestire i deep link dagli URL

Puoi aggiungere rotte all'interno del file `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **Suggerimento:** Puoi creare le tue rotte manualmente o utilizzare lo strumento CLI <a href="/docs/{{ $version }}/metro">Metro</a> per crearle automaticamente.

Ecco un esempio di creazione di una pagina 'account' utilizzando Metro.

``` bash
metro make:page account_page
```

``` dart
// Adds your new route automatically to /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Potresti anche aver bisogno di passare dati da una vista all'altra. In {{ config('app.name') }}, questo e possibile utilizzando `NyStatefulWidget` (un widget con stato che integra l'accesso ai dati di rotta). Approfondiremo questo argomento per spiegare come funziona.


<div id="adding-routes"></div>

## Aggiungere rotte

Questo e il modo piu semplice per aggiungere nuove rotte al tuo progetto.

Esegui il comando seguente per creare una nuova pagina.

```bash
metro make:page profile_page
```

Dopo aver eseguito il comando sopra, verra creato un nuovo Widget chiamato `ProfilePage` e aggiunto alla directory `resources/pages/`.
Verra anche aggiunta la nuova rotta al file `lib/routes/router.dart`.

File: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Navigare verso le pagine

Puoi navigare verso nuove pagine utilizzando l'helper `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Rotta iniziale

Nei tuoi router, puoi definire la prima pagina che deve caricarsi utilizzando il metodo `.initialRoute()`.

Una volta impostata la rotta iniziale, sara la prima pagina che si carica quando apri l'app.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### Rotta Iniziale Condizionale

Puoi anche impostare una rotta iniziale condizionale utilizzando il parametro `when`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### Navigare alla Rotta Iniziale

Usa `routeToInitial()` per navigare alla rotta iniziale dell'app:

``` dart
void _goHome() {
    routeToInitial();
}
```

Questo navighera alla rotta contrassegnata con `.initialRoute()` e cancellera lo stack di navigazione.

<div id="preview-route"></div>

## Rotta di Anteprima

Durante lo sviluppo, potresti voler visualizzare rapidamente una pagina specifica senza cambiare permanentemente la tua rotta iniziale. Usa `.previewRoute()` per rendere temporaneamente qualsiasi rotta la rotta iniziale:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

Il metodo `previewRoute()`:
- Sovrascrive qualsiasi impostazione esistente di `initialRoute()` e `authenticatedRoute()`
- Rende la rotta specificata la rotta iniziale
- Utile per testare rapidamente pagine specifiche durante lo sviluppo

> **Attenzione:** Ricorda di rimuovere `.previewRoute()` prima di rilasciare la tua app!

<div id="authenticated-route"></div>

## Rotta Autenticata

Nella tua app, puoi definire una rotta come rotta iniziale quando un utente e autenticato.
Questo sovrascrivera automaticamente la rotta iniziale predefinita e sara la prima pagina che l'utente vede quando effettua il login.

Per prima cosa, il tuo utente dovrebbe essere autenticato utilizzando l'helper `Auth.authenticate({...})`.

Ora, quando aprono l'app, la rotta che hai definito sara la pagina predefinita fino a quando non effettuano il logout.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### Rotta Autenticata Condizionale

Puoi anche impostare una rotta autenticata condizionale:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Navigare alla Rotta Autenticata

Puoi navigare alla pagina autenticata utilizzando l'helper `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Vedi anche:** [Autenticazione](/docs/{{ $version }}/authentication) per i dettagli sull'autenticazione degli utenti e la gestione delle sessioni.


<div id="unknown-route"></div>

## Rotta Sconosciuta

Puoi definire una rotta per gestire scenari 404/non trovato utilizzando `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Quando un utente naviga verso una rotta che non esiste, verra mostrata la pagina della rotta sconosciuta.


<div id="route-guards"></div>

## Route guard

I route guard proteggono le pagine da accessi non autorizzati. Si eseguono prima che la navigazione venga completata, permettendoti di reindirizzare gli utenti o bloccare l'accesso in base a condizioni.

Usa i route guard quando hai bisogno di:
- Proteggere le pagine da utenti non autenticati
- Verificare le autorizzazioni prima di consentire l'accesso
- Reindirizzare gli utenti in base a condizioni (es. onboarding non completato)
- Registrare o tracciare le visualizzazioni di pagina

Per creare un nuovo Route Guard, esegui il comando seguente.

``` bash
metro make:route_guard dashboard
```

Successivamente, aggiungi il nuovo Route Guard alla tua rotta.

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Add your guard
    ]
  ); // restricted page
});
```

Puoi anche impostare route guard utilizzando il metodo `addRouteGuard`:

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // or add multiple guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## Ciclo di Vita NyRouteGuard

Nella v7, i route guard utilizzano la classe `NyRouteGuard` con tre metodi del ciclo di vita:

- **`onBefore(RouteContext context)`** - Chiamato prima della navigazione. Restituisce `next()` per continuare, `redirect()` per andare altrove, o `abort()` per fermarsi.
- **`onAfter(RouteContext context)`** - Chiamato dopo la navigazione riuscita verso la rotta.

### Esempio Base

File: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Perform a check if they can access the page
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Track page view after successful navigation
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

La classe `RouteContext` fornisce accesso alle informazioni di navigazione:

| Proprieta | Tipo | Descrizione |
|-----------|------|-------------|
| `context` | `BuildContext?` | Contesto di build corrente |
| `data` | `dynamic` | Dati passati alla rotta |
| `queryParameters` | `Map<String, String>` | Parametri query dell'URL |
| `routeName` | `String` | Nome/percorso della rotta |
| `originalRouteName` | `String?` | Nome originale della rotta prima delle trasformazioni |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## Metodi Helper dei Guard

### next()

Prosegui verso il guard successivo o verso la rotta:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

Reindirizza verso una rotta diversa:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

Il metodo `redirect()` accetta:

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `path` | `Object` | Percorso della rotta o RouteView |
| `data` | `dynamic` | Dati da passare alla rotta |
| `queryParameters` | `Map<String, dynamic>?` | Parametri query |
| `navigationType` | `NavigationType` | Tipo di navigazione (predefinito: pushReplace) |
| `transitionType` | `TransitionType?` | Transizione di pagina |
| `onPop` | `Function(dynamic)?` | Callback quando la rotta viene rimossa |

### abort()

Ferma la navigazione senza reindirizzare:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // User stays on current route
  }
  return next();
}
```

### setData()

Modifica i dati passati ai guard successivi e alla rotta:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Guard Parametrizzati

Usa `ParameterizedGuard` quando hai bisogno di configurare il comportamento del guard per ogni rotta:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Usage:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Stack di Guard

Componi piu guard in un singolo guard riutilizzabile utilizzando `GuardStack`:

``` dart
// Create reusable guard combinations
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Guard Condizionali

Applica guard condizionalmente basandoti su un predicato:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Passaggio di dati a un'altra pagina

In questa sezione, mostreremo come puoi passare dati da un widget all'altro.

Dal tuo Widget, usa l'helper `routeTo` e passa i `data` che vuoi inviare alla nuova pagina.

``` dart
// HomePage Widget
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage Widget (other page)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // or
    print(data()); // Hello World
  };
```

Altri esempi

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Profile page widget (other page)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Gruppi di Rotte

I gruppi di rotte organizzano le rotte correlate e applicano impostazioni condivise. Sono utili quando piu rotte necessitano degli stessi guard, prefisso URL o stile di transizione.

Usa i gruppi di rotte quando hai bisogno di:
- Applicare lo stesso route guard a piu pagine
- Aggiungere un prefisso URL a un insieme di rotte (es. `/admin/...`)
- Impostare la stessa transizione di pagina per rotte correlate

Puoi definire un gruppo di rotte come nell'esempio seguente.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### Impostazioni opzionali per i gruppi di rotte sono:

| Impostazione | Tipo | Descrizione |
|--------------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Applica route guard a tutte le rotte del gruppo |
| `prefix` | `String` | Aggiunge un prefisso a tutti i percorsi delle rotte del gruppo |
| `transition_type` | `TransitionType` | Imposta la transizione per tutte le rotte del gruppo |
| `transition` | `PageTransitionType` | Imposta il tipo di transizione della pagina (deprecato, usa transition_type) |
| `transition_settings` | `PageTransitionSettings` | Imposta le impostazioni di transizione |


<div id="route-parameters"></div>

## Utilizzo dei Parametri delle Rotte

Quando crei una nuova pagina, puoi aggiornare la rotta per accettare parametri.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Ora, quando navighi verso la pagina, puoi passare l'`userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Puoi accedere ai parametri nella nuova pagina in questo modo.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Parametri Query

Quando navighi verso una nuova pagina, puoi anche fornire parametri query.

Vediamo come.

```dart
  // Home page
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // navigate to profile page

  ...

  // Profile Page
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // or
    print(queryParameters()); // {"user": 7}
  };
```

> **Nota:** Finche il tuo widget di pagina estende `NyStatefulWidget` e la classe `NyPage`, puoi chiamare `widget.queryParameters()` per ottenere tutti i parametri query dal nome della rotta.

```dart
// Example page
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Home page
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // or
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Suggerimento:** I parametri query devono seguire il protocollo HTTP, Es. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Transizioni di Pagina

Puoi aggiungere transizioni quando navighi da una pagina modificando il tuo file `router.dart`.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### Transizioni di Pagina Disponibili

#### Transizioni Base
- **`TransitionType.fade()`** - Dissolve la nuova pagina in entrata mentre dissolve la vecchia pagina in uscita
- **`TransitionType.theme()`** - Usa il tema delle transizioni di pagina del tema dell'app

#### Transizioni a Scorrimento Direzionale
- **`TransitionType.rightToLeft()`** - Scorre dal bordo destro dello schermo
- **`TransitionType.leftToRight()`** - Scorre dal bordo sinistro dello schermo
- **`TransitionType.topToBottom()`** - Scorre dal bordo superiore dello schermo
- **`TransitionType.bottomToTop()`** - Scorre dal bordo inferiore dello schermo

#### Transizioni Scorrimento con Dissolvenza
- **`TransitionType.rightToLeftWithFade()`** - Scorre e dissolve dal bordo destro
- **`TransitionType.leftToRightWithFade()`** - Scorre e dissolve dal bordo sinistro

#### Transizioni di Trasformazione
- **`TransitionType.scale(alignment: ...)`** - Scala dal punto di allineamento specificato
- **`TransitionType.rotate(alignment: ...)`** - Ruota attorno al punto di allineamento specificato
- **`TransitionType.size(alignment: ...)`** - Cresce dal punto di allineamento specificato

#### Transizioni Congiunte (Richiede il widget corrente)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - La pagina corrente esce a destra mentre la nuova pagina entra da sinistra
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - La pagina corrente esce a sinistra mentre la nuova pagina entra da destra
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - La pagina corrente esce verso il basso mentre la nuova pagina entra dall'alto
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - La pagina corrente esce verso l'alto mentre la nuova pagina entra dal basso

#### Transizioni Pop (Richiede il widget corrente)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - La pagina corrente esce a destra, la nuova pagina resta ferma
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - La pagina corrente esce a sinistra, la nuova pagina resta ferma
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - La pagina corrente esce verso il basso, la nuova pagina resta ferma
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - La pagina corrente esce verso l'alto, la nuova pagina resta ferma

#### Transizioni Material Design Shared Axis
- **`TransitionType.sharedAxisHorizontal()`** - Transizione con scorrimento e dissolvenza orizzontale
- **`TransitionType.sharedAxisVertical()`** - Transizione con scorrimento e dissolvenza verticale
- **`TransitionType.sharedAxisScale()`** - Transizione con scala e dissolvenza

#### Parametri di Personalizzazione
Ogni transizione accetta i seguenti parametri opzionali:

| Parametro | Descrizione | Predefinito |
|-----------|-------------|-------------|
| `curve` | Curva di animazione | Curve specifiche della piattaforma |
| `duration` | Durata dell'animazione | Durate specifiche della piattaforma |
| `reverseDuration` | Durata dell'animazione inversa | Uguale a duration |
| `fullscreenDialog` | Se la rotta e un dialogo a schermo intero | `false` |
| `opaque` | Se la rotta e opaca | `false` |


``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Tipi di Navigazione

Durante la navigazione, puoi specificare uno dei seguenti se stai utilizzando l'helper `routeTo`.

| Tipo | Descrizione |
|------|-------------|
| `NavigationType.push` | Inserisce una nuova pagina nello stack delle rotte della tua app |
| `NavigationType.pushReplace` | Sostituisce la rotta corrente, eliminando la rotta precedente una volta che la nuova rotta e completata |
| `NavigationType.popAndPushNamed` | Rimuove la rotta corrente dal navigatore e inserisce una rotta nominata al suo posto |
| `NavigationType.pushAndRemoveUntil` | Inserisce e rimuove le rotte fino a quando il predicato restituisce true |
| `NavigationType.pushAndForgetAll` | Naviga verso una nuova pagina e elimina qualsiasi altra pagina nello stack delle rotte |

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## Navigare indietro

Una volta che sei nella nuova pagina, puoi utilizzare l'helper `pop()` per tornare alla pagina precedente.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // or
    Navigator.pop(context);
  }
...
```

Se vuoi restituire un valore al widget precedente, fornisci un `result` come nell'esempio seguente.

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Get the value from the previous widget using the `onPop` parameter
// HomePage Widget
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Navigazione Condizionale

Usa `routeIf()` per navigare solo quando una condizione e soddisfatta:

``` dart
// Only navigate if the user is logged in
routeIf(isLoggedIn, DashboardPage.path);

// With additional options
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

Se la condizione e `false`, non avviene alcuna navigazione.


<div id="route-history"></div>

## Cronologia delle Rotte

In {{ config('app.name') }}, puoi accedere alle informazioni sulla cronologia delle rotte utilizzando gli helper seguenti.

``` dart
// Get route history
Nylo.getRouteHistory(); // List<dynamic>

// Get the current route
Nylo.getCurrentRoute(); // Route<dynamic>?

// Get the previous route
Nylo.getPreviousRoute(); // Route<dynamic>?

// Get the current route name
Nylo.getCurrentRouteName(); // String?

// Get the previous route name
Nylo.getPreviousRouteName(); // String?

// Get the current route arguments
Nylo.getCurrentRouteArguments(); // dynamic

// Get the previous route arguments
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Aggiornare lo Stack delle Rotte

Puoi aggiornare lo stack di navigazione programmaticamente utilizzando `NyNavigator.updateStack()`:

``` dart
// Update the stack with a list of routes
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Pass data to specific routes
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|-------------|-------------|
| `routes` | `List<String>` | obbligatorio | Lista dei percorsi delle rotte verso cui navigare |
| `replace` | `bool` | `true` | Se sostituire lo stack corrente |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Dati da passare a rotte specifiche |

Questo e utile per:
- Scenari di deep linking
- Ripristino dello stato di navigazione
- Costruzione di flussi di navigazione complessi


<div id="deep-linking"></div>

## Deep Linking

Il deep linking consente agli utenti di navigare direttamente a contenuti specifici all'interno della tua app utilizzando URL. Questo e utile per:
- Condividere link diretti a contenuti specifici dell'app
- Campagne di marketing che mirano a funzionalita specifiche nell'app
- Gestione di notifiche che dovrebbero aprire schermate specifiche dell'app
- Transizioni fluide dal web all'app

## Configurazione

Prima di implementare il deep linking nella tua app, assicurati che il tuo progetto sia configurato correttamente:

### 1. Configurazione della Piattaforma

**iOS**: Configura i link universali nel tuo progetto Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Guida alla Configurazione dei Link Universali Flutter</a>

**Android**: Configura gli app link nel tuo AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Guida alla Configurazione degli App Link Flutter</a>

### 2. Definisci le Tue Rotte

Tutte le rotte che dovrebbero essere accessibili tramite deep link devono essere registrate nella configurazione del router:

```dart
// File: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Basic routes
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route with parameters
  router.add(HotelBookingPage.path);
});
```

## Utilizzo dei Deep Link

Una volta configurata, la tua app puo gestire URL in arrivo in vari formati:

### Deep Link Base

Navigazione semplice verso pagine specifiche:

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

Per attivare queste navigazioni programmaticamente all'interno della tua app:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Parametri del Percorso

Per rotte che richiedono dati dinamici come parte del percorso:

#### Definizione della Rotta

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Define a route with a parameter placeholder {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Access the path parameter
    final hotelId = queryParameters()["id"]; // Returns "87" for URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Use the ID to fetch hotel data or perform operations
  };

  // Rest of your page implementation
}
```

#### Formato dell'URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Navigazione Programmatica

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Parametri Query

Per parametri opzionali o quando sono necessari piu valori dinamici:

#### Formato dell'URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Accesso ai Parametri Query

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Get all query parameters
    final params = queryParameters();

    // Access specific parameters
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Alternative access method
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Navigazione Programmatica con Parametri Query

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Gestione dei Deep Link

Puoi gestire gli eventi di deep link nel tuo `RouteProvider`:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Handle deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Update the route stack for deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### Test dei Deep Link

Per lo sviluppo e il test, puoi simulare l'attivazione dei deep link utilizzando ADB (Android) o xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Suggerimenti per il Debug

- Stampa tutti i parametri nel tuo metodo init per verificare il parsing corretto
- Testa diversi formati di URL per assicurarti che la tua app li gestisca correttamente
- Ricorda che i parametri query vengono sempre ricevuti come stringhe, convertili nel tipo appropriato secondo necessita

---

## Pattern Comuni

### Conversione del Tipo dei Parametri

Poiche tutti i parametri URL vengono passati come stringhe, spesso avrai bisogno di convertirli:

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Parametri Opzionali

Gestisci i casi in cui i parametri potrebbero mancare:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Load specific user profile
} else {
  // Load current user profile
}

// Or check hasQueryParameter
if (hasQueryParameter('status')) {
  // Do something with the status parameter
} else {
  // Handle absence of the parameter
}
```


<div id="advanced"></div>

## Avanzato

### Verificare se una Rotta Esiste

Puoi verificare se una rotta e registrata nel tuo router:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Metodi NyRouter

La classe `NyRouter` fornisce diversi metodi utili:

| Metodo | Descrizione |
|--------|-------------|
| `getRegisteredRouteNames()` | Ottieni tutti i nomi delle rotte registrate come lista |
| `getRegisteredRoutes()` | Ottieni tutte le rotte registrate come mappa |
| `containsRoutes(routes)` | Verifica se il router contiene tutte le rotte specificate |
| `getInitialRouteName()` | Ottieni il nome della rotta iniziale |
| `getAuthRouteName()` | Ottieni il nome della rotta autenticata |
| `getUnknownRouteName()` | Ottieni il nome della rotta sconosciuta/404 |

### Ottenere gli Argomenti della Rotta

Puoi ottenere gli argomenti della rotta utilizzando `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Get typed arguments
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument e NyQueryParameters

I dati passati tra le rotte sono incapsulati in queste classi:

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
