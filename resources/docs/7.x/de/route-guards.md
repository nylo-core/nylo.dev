# Route Guards

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Einen Route Guard erstellen](#creating-a-route-guard "Einen Route Guard erstellen")
- [Guard-Lebenszyklus](#guard-lifecycle "Guard-Lebenszyklus")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Guard-Aktionen](#guard-actions "Guard-Aktionen")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Guards auf Routen anwenden](#applying-guards "Guards auf Routen anwenden")
- [Gruppen-Guards](#group-guards "Gruppen-Guards")
- [Guard-Komposition](#guard-composition "Guard-Komposition")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Beispiele](#examples "Beispiele")

<div id="introduction"></div>

## Einleitung

Route Guards bieten **Middleware für die Navigation** in {{ config('app.name') }}. Sie fangen Routenübergänge ab und ermöglichen es Ihnen zu steuern, ob ein Benutzer auf eine Seite zugreifen darf, ihn auf eine andere Seite umzuleiten oder die an eine Route übergebenen Daten zu ändern.

Häufige Anwendungsfälle sind:
- **Authentifizierungsprüfungen** -- nicht authentifizierte Benutzer auf eine Anmeldeseite umleiten
- **Rollenbasierter Zugriff** -- Seiten auf Admin-Benutzer beschränken
- **Datenvalidierung** -- sicherstellen, dass erforderliche Daten vor der Navigation vorhanden sind
- **Datenanreicherung** -- zusätzliche Daten an eine Route anfügen

Guards werden **der Reihe nach** ausgeführt, bevor die Navigation stattfindet. Wenn ein Guard `handled` zurückgibt, wird die Navigation gestoppt (entweder durch Umleitung oder Abbruch).

<div id="creating-a-route-guard"></div>

## Einen Route Guard erstellen

Erstellen Sie einen Route Guard mit der Metro-CLI:

``` bash
metro make:route_guard auth
```

Dies erzeugt eine Guard-Datei:

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

## Guard-Lebenszyklus

Jeder Route Guard hat drei Lebenszyklusmethoden:

<div id="on-before"></div>

### onBefore

Wird **vor** der Navigation aufgerufen. Hier prüfen Sie Bedingungen und entscheiden, ob die Navigation erlaubt, umgeleitet oder abgebrochen werden soll.

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

Rückgabewerte:
- `next()` -- zum nächsten Guard fortfahren oder zur Route navigieren
- `redirect(path)` -- zu einer anderen Route umleiten
- `abort()` -- Navigation vollständig abbrechen

<div id="on-after"></div>

### onAfter

Wird **nach** erfolgreicher Navigation aufgerufen. Verwenden Sie dies für Analysen, Protokollierung oder Nebeneffekte nach der Navigation.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Wird aufgerufen, wenn der Benutzer eine Route **verlässt**. Geben Sie `false` zurück, um das Verlassen zu verhindern.

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

Das `RouteContext`-Objekt wird an alle Guard-Lebenszyklusmethoden übergeben und enthält Informationen über die Navigation:

| Eigenschaft | Typ | Beschreibung |
|-------------|-----|-------------|
| `context` | `BuildContext?` | Aktueller Build-Kontext |
| `data` | `dynamic` | An die Route übergebene Daten |
| `queryParameters` | `Map<String, String>` | URL-Abfrageparameter |
| `routeName` | `String` | Name/Pfad der Zielroute |
| `originalRouteName` | `String?` | Ursprünglicher Routenname vor Transformationen |

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

### RouteContext transformieren

Erstellen Sie eine Kopie mit anderen Daten:

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

## Guard-Aktionen

<div id="next"></div>

### next

Zum nächsten Guard in der Kette fortfahren oder zur Route navigieren, wenn dies der letzte Guard ist:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Den Benutzer zu einer anderen Route umleiten:

``` dart
return redirect(LoginPage.path);
```

Mit zusätzlichen Optionen:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `path` | `Object` | erforderlich | Routenpfad-String oder RouteView |
| `data` | `dynamic` | null | Daten, die an die Umleitungsroute übergeben werden |
| `queryParameters` | `Map<String, dynamic>?` | null | Abfrageparameter |
| `navigationType` | `NavigationType` | `pushReplace` | Navigationsmethode |
| `result` | `dynamic` | null | Zurückzugebendes Ergebnis |
| `removeUntilPredicate` | `Function?` | null | Prädikat zur Routenentfernung |
| `transitionType` | `TransitionType?` | null | Seitenübergangstyp |
| `onPop` | `Function(dynamic)?` | null | Callback beim Zurücknavigieren |

<div id="abort"></div>

### abort

Navigation abbrechen, ohne umzuleiten. Der Benutzer bleibt auf seiner aktuellen Seite:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Die Daten ändern, die an nachfolgende Guards und die Zielroute übergeben werden:

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

## Guards auf Routen anwenden

Fügen Sie Guards zu einzelnen Routen in Ihrer Router-Datei hinzu:

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

## Gruppen-Guards

Wenden Sie Guards auf mehrere Routen gleichzeitig an, indem Sie Routengruppen verwenden:

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

## Guard-Komposition

{{ config('app.name') }} bietet Werkzeuge, um Guards für wiederverwendbare Muster zusammenzusetzen.

<div id="guard-stack"></div>

### GuardStack

Kombinieren Sie mehrere Guards zu einem einzigen wiederverwendbaren Guard:

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

`GuardStack` führt Guards der Reihe nach aus. Wenn ein Guard `handled` zurückgibt, werden die verbleibenden Guards übersprungen.

<div id="conditional-guard"></div>

### ConditionalGuard

Wenden Sie einen Guard nur an, wenn eine Bedingung erfüllt ist:

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

Wenn die Bedingung `false` zurückgibt, wird der Guard übersprungen und die Navigation fortgesetzt.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Erstellen Sie Guards, die Konfigurationsparameter akzeptieren:

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

## Beispiele

### Authentifizierungs-Guard

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

### Abonnement-Guard mit Parametern

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

### Protokollierungs-Guard

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
