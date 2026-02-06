# Route Guards

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Creare un Route Guard](#creating-a-route-guard "Creare un Route Guard")
- [Ciclo di Vita del Guard](#guard-lifecycle "Ciclo di Vita del Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Azioni del Guard](#guard-actions "Azioni del Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Applicare i Guard alle Route](#applying-guards "Applicare i Guard alle Route")
- [Guard di Gruppo](#group-guards "Guard di Gruppo")
- [Composizione dei Guard](#guard-composition "Composizione dei Guard")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Esempi](#examples "Esempi Pratici")

<div id="introduction"></div>

## Introduzione

I route guard forniscono un **middleware per la navigazione** in {{ config('app.name') }}. Intercettano le transizioni tra route e ti permettono di controllare se un utente puo' accedere a una pagina, reindirizzarlo altrove o modificare i dati passati a una route.

Casi d'uso comuni includono:
- **Controlli di autenticazione** -- reindirizzare gli utenti non autenticati a una pagina di login
- **Accesso basato sui ruoli** -- limitare le pagine agli utenti admin
- **Validazione dei dati** -- assicurarsi che i dati richiesti esistano prima della navigazione
- **Arricchimento dei dati** -- aggiungere dati supplementari a una route

I guard vengono eseguiti **in ordine** prima che avvenga la navigazione. Se un guard restituisce `handled`, la navigazione si interrompe (reindirizzando o annullando).

<div id="creating-a-route-guard"></div>

## Creare un Route Guard

Crea un route guard usando la CLI Metro:

``` bash
metro make:route_guard auth
```

Questo genera un file guard:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Add your guard logic here
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Ciclo di Vita del Guard

Ogni route guard ha tre metodi del ciclo di vita:

<div id="on-before"></div>

### onBefore

Chiamato **prima** che avvenga la navigazione. Qui verifichi le condizioni e decidi se consentire, reindirizzare o annullare la navigazione.

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

Valori di ritorno:
- `next()` -- continua al guard successivo o naviga verso la route
- `redirect(path)` -- reindirizza a una route diversa
- `abort()` -- annulla completamente la navigazione

<div id="on-after"></div>

### onAfter

Chiamato **dopo** una navigazione riuscita. Usalo per analytics, logging o effetti collaterali post-navigazione.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Chiamato quando l'utente sta **lasciando** una route. Restituisci `false` per impedire all'utente di uscire.

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Show confirmation dialog
    return await showConfirmDialog();
  }
  return true; // Allow leaving
}
```

<div id="route-context"></div>

## RouteContext

L'oggetto `RouteContext` viene passato a tutti i metodi del ciclo di vita del guard e contiene informazioni sulla navigazione:

| Proprieta' | Tipo | Descrizione |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context corrente |
| `data` | `dynamic` | Dati passati alla route |
| `queryParameters` | `Map<String, String>` | Parametri di query dell'URL |
| `routeName` | `String` | Nome/percorso della route di destinazione |
| `originalRouteName` | `String?` | Nome originale della route prima delle trasformazioni |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Access route information
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Trasformare il RouteContext

Crea una copia con dati diversi:

``` dart
// Change the data type
RouteContext<User> userContext = context.withData<User>(currentUser);

// Copy with modified fields
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Azioni del Guard

<div id="next"></div>

### next

Continua al guard successivo nella catena, o naviga verso la route se questo e' l'ultimo guard:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Reindirizza l'utente a una route diversa:

``` dart
return redirect(LoginPage.path);
```

Con opzioni aggiuntive:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `path` | `Object` | obbligatorio | Stringa del percorso della route o RouteView |
| `data` | `dynamic` | null | Dati da passare alla route di reindirizzamento |
| `queryParameters` | `Map<String, dynamic>?` | null | Parametri di query |
| `navigationType` | `NavigationType` | `pushReplace` | Metodo di navigazione |
| `result` | `dynamic` | null | Risultato da restituire |
| `removeUntilPredicate` | `Function?` | null | Predicato di rimozione route |
| `transitionType` | `TransitionType?` | null | Tipo di transizione della pagina |
| `onPop` | `Function(dynamic)?` | null | Callback al pop |

<div id="abort"></div>

### abort

Annulla la navigazione senza reindirizzare. L'utente rimane sulla pagina corrente:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Modifica i dati che verranno passati ai guard successivi e alla route di destinazione:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Enrich the route data
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## Applicare i Guard alle Route

Aggiungi i guard alle singole route nel tuo file router:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Add a single guard
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Add multiple guards (executed in order)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Guard di Gruppo

Applica i guard a piu' route contemporaneamente usando i gruppi di route:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // All routes in this group require authentication
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## Composizione dei Guard

{{ config('app.name') }} fornisce strumenti per comporre i guard insieme in pattern riutilizzabili.

<div id="guard-stack"></div>

### GuardStack

Combina piu' guard in un singolo guard riutilizzabile:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Use the stack on a route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` esegue i guard in ordine. Se un guard restituisce `handled`, i guard rimanenti vengono saltati.

<div id="conditional-guard"></div>

### ConditionalGuard

Applica un guard solo quando una condizione e' vera:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

Se la condizione restituisce `false`, il guard viene saltato e la navigazione continua.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Crea guard che accettano parametri di configurazione:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = allowed roles

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Usage
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## Esempi

### Guard di Autenticazione

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### Guard di Abbonamento con Parametri

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// Require premium or pro subscription
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Guard di Logging

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
