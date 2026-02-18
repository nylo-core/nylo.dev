# Router

---

<a name="section-1"></a>

- [Einleitung](#introduction "Einleitung")
- Grundlagen
  - [Routen hinzufuegen](#adding-routes "Routen hinzufuegen")
  - [Zu Seiten navigieren](#navigating-to-pages "Zu Seiten navigieren")
  - [Initiale Route](#initial-route "Initiale Route")
  - [Vorschau-Route](#preview-route "Vorschau-Route")
  - [Authentifizierte Route](#authenticated-route "Authentifizierte Route")
  - [Unbekannte Route](#unknown-route "Unbekannte Route")
- Daten an eine andere Seite senden
  - [Daten an eine andere Seite uebergeben](#passing-data-to-another-page "Daten an eine andere Seite uebergeben")
- Navigation
  - [Navigationstypen](#navigation-types "Navigationstypen")
  - [Zurueck navigieren](#navigating-back "Zurueck navigieren")
  - [Bedingte Navigation](#conditional-navigation "Bedingte Navigation")
  - [Seitenuebergaenge](#page-transitions "Seitenuebergaenge")
  - [Routen-Verlauf](#route-history "Routen-Verlauf")
  - [Routen-Stack aktualisieren](#update-route-stack "Routen-Stack aktualisieren")
- Routen-Parameter
  - [Routen-Parameter verwenden](#route-parameters "Routen-Parameter verwenden")
  - [Query-Parameter](#query-parameters "Query-Parameter")
- Route Guards
  - [Route Guards erstellen](#route-guards "Route Guards erstellen")
  - [NyRouteGuard-Lebenszyklus](#nyroute-guard-lifecycle "NyRouteGuard-Lebenszyklus")
  - [Guard-Hilfsmethoden](#guard-helper-methods "Guard-Hilfsmethoden")
  - [Parametrisierte Guards](#parameterized-guards "Parametrisierte Guards")
  - [Guard Stacks](#guard-stacks "Guard Stacks")
  - [Bedingte Guards](#conditional-guards "Bedingte Guards")
- Routen-Gruppen
  - [Routen-Gruppen](#route-groups "Routen-Gruppen")
- [Deep Linking](#deep-linking "Deep Linking")
- [Erweitert](#advanced "Erweitert")



<div id="introduction"></div>

## Einleitung

Routen ermoeglichen es Ihnen, die verschiedenen Seiten in Ihrer App zu definieren und zwischen ihnen zu navigieren.

Verwenden Sie Routen, wenn Sie:
- Die in Ihrer App verfuegbaren Seiten definieren muessen
- Benutzer zwischen Bildschirmen navigieren lassen moechten
- Seiten hinter einer Authentifizierung schuetzen moechten
- Daten von einer Seite an eine andere uebergeben muessen
- Deep Links von URLs behandeln muessen

Sie koennen Routen in der Datei `lib/routes/router.dart` hinzufuegen.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **Tipp:** Sie koennen Ihre Routen manuell erstellen oder das <a href="/docs/{{ $version }}/metro">Metro</a> CLI-Tool verwenden, um sie fuer Sie zu erstellen.

Hier ist ein Beispiel fuer die Erstellung einer 'account'-Seite mit Metro.

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

Moeglicherweise muessen Sie auch Daten von einer Ansicht an eine andere uebergeben. In {{ config('app.name') }} ist das mit dem `NyStatefulWidget` (einem Stateful Widget mit integriertem Zugriff auf Routendaten) moeglich. Wir werden tiefer darauf eingehen, um zu erklaeren, wie es funktioniert.


<div id="adding-routes"></div>

## Routen hinzufuegen

Dies ist der einfachste Weg, neue Routen zu Ihrem Projekt hinzuzufuegen.

Fuehren Sie den folgenden Befehl aus, um eine neue Seite zu erstellen.

```bash
metro make:page profile_page
```

Nach der Ausfuehrung wird ein neues Widget namens `ProfilePage` erstellt und in Ihrem Verzeichnis `resources/pages/` abgelegt.
Es wird auch die neue Route zu Ihrer Datei `lib/routes/router.dart` hinzufuegen.

Datei: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Zu Seiten navigieren

Sie koennen mit dem `routeTo`-Helfer zu neuen Seiten navigieren.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Initiale Route

In Ihren Routern koennen Sie die erste Seite festlegen, die geladen werden soll, indem Sie die `.initialRoute()`-Methode verwenden.

Sobald Sie die initiale Route festgelegt haben, wird sie die erste Seite sein, die beim Oeffnen der App geladen wird.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### Bedingte initiale Route

Sie koennen auch eine bedingte initiale Route mit dem `when`-Parameter festlegen:

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

### Zur initialen Route navigieren

Verwenden Sie `routeToInitial()`, um zur initialen Route der App zu navigieren:

``` dart
void _goHome() {
    routeToInitial();
}
```

Dies navigiert zur Route, die mit `.initialRoute()` markiert ist, und leert den Navigationsstapel.

<div id="preview-route"></div>

## Vorschau-Route

Waehrend der Entwicklung moechten Sie moeglicherweise schnell eine bestimmte Seite in der Vorschau anzeigen, ohne Ihre initiale Route dauerhaft zu aendern. Verwenden Sie `.previewRoute()`, um eine Route temporaer zur initialen Route zu machen:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

Die `previewRoute()`-Methode:
- Ueberschreibt alle bestehenden `initialRoute()`- und `authenticatedRoute()`-Einstellungen
- Macht die angegebene Route zur initialen Route
- Nuetzlich zum schnellen Testen bestimmter Seiten waehrend der Entwicklung

> **Warnung:** Denken Sie daran, `.previewRoute()` vor der Veroeffentlichung Ihrer App zu entfernen!

<div id="authenticated-route"></div>

## Authentifizierte Route

In Ihrer App koennen Sie eine Route definieren, die die initiale Route ist, wenn ein Benutzer authentifiziert ist.
Dies ueberschreibt automatisch die Standard-initiale Route und ist die erste Seite, die der Benutzer sieht, wenn er sich anmeldet.

Zuerst sollte Ihr Benutzer mit dem `Auth.authenticate({...})`-Helfer eingeloggt werden.

Wenn er nun die App oeffnet, wird die von Ihnen definierte Route die Standardseite sein, bis er sich abmeldet.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### Bedingte authentifizierte Route

Sie koennen auch eine bedingte authentifizierte Route festlegen:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Zur authentifizierten Route navigieren

Sie koennen mit dem `routeToAuthenticatedRoute()`-Helfer zur authentifizierten Seite navigieren:

``` dart
routeToAuthenticatedRoute();
```

**Siehe auch:** [Authentifizierung](/docs/{{ $version }}/authentication) fuer Details zur Benutzerauthentifizierung und Sitzungsverwaltung.


<div id="unknown-route"></div>

## Unbekannte Route

Sie koennen eine Route definieren, die 404/Nicht-gefunden-Szenarien mit `.unknownRoute()` behandelt:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Wenn ein Benutzer zu einer Route navigiert, die nicht existiert, wird ihm die Seite der unbekannten Route angezeigt.


<div id="route-guards"></div>

## Route Guards

Route Guards schuetzen Seiten vor unbefugtem Zugriff. Sie werden vor Abschluss der Navigation ausgefuehrt und ermoeglichen es Ihnen, Benutzer umzuleiten oder den Zugriff basierend auf Bedingungen zu blockieren.

Verwenden Sie Route Guards, wenn Sie:
- Seiten vor nicht authentifizierten Benutzern schuetzen muessen
- Berechtigungen vor dem Zugriff pruefen muessen
- Benutzer basierend auf Bedingungen umleiten moechten (z.B. Onboarding nicht abgeschlossen)
- Seitenaufrufe protokollieren oder verfolgen moechten

Um einen neuen Route Guard zu erstellen, fuehren Sie den folgenden Befehl aus.

``` bash
metro make:route_guard dashboard
```

Fuegen Sie als Naechstes den neuen Route Guard zu Ihrer Route hinzu.

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

Sie koennen Route Guards auch mit der `addRouteGuard`-Methode setzen:

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

## NyRouteGuard-Lebenszyklus

In v7 verwenden Route Guards die Klasse `NyRouteGuard` mit drei Lebenszyklusmethoden:

- **`onBefore(RouteContext context)`** - Wird vor der Navigation aufgerufen. Geben Sie `next()` zurueck, um fortzufahren, `redirect()`, um umzuleiten, oder `abort()`, um abzubrechen.
- **`onAfter(RouteContext context)`** - Wird nach erfolgreicher Navigation zur Route aufgerufen.

### Grundlegendes Beispiel

Datei: **/routes/guards/dashboard_route_guard.dart**
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

Die Klasse `RouteContext` bietet Zugang zu Navigationsinformationen:

| Eigenschaft | Typ | Beschreibung |
|------------|------|-------------|
| `context` | `BuildContext?` | Aktueller Build-Kontext |
| `data` | `dynamic` | An die Route uebergebene Daten |
| `queryParameters` | `Map<String, String>` | URL-Query-Parameter |
| `routeName` | `String` | Routenname/-pfad |
| `originalRouteName` | `String?` | Urspruenglicher Routenname vor Transformationen |

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

## Guard-Hilfsmethoden

### next()

Zum naechsten Guard oder zur Route fortfahren:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

Zu einer anderen Route umleiten:

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

Die `redirect()`-Methode akzeptiert:

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `path` | `Object` | Routenpfad oder RouteView |
| `data` | `dynamic` | An die Route zu uebergebende Daten |
| `queryParameters` | `Map<String, dynamic>?` | Query-Parameter |
| `navigationType` | `NavigationType` | Navigationstyp (Standard: pushReplace) |
| `transitionType` | `TransitionType?` | Seitenuebergang |
| `onPop` | `Function(dynamic)?` | Callback beim Pop der Route |

### abort()

Navigation ohne Umleitung stoppen:

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

Daten aendern, die an nachfolgende Guards und die Route uebergeben werden:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Parametrisierte Guards

Verwenden Sie `ParameterizedGuard`, wenn Sie das Guard-Verhalten pro Route konfigurieren muessen:

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

## Guard Stacks

Kombinieren Sie mehrere Guards zu einem einzelnen wiederverwendbaren Guard mit `GuardStack`:

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

## Bedingte Guards

Wenden Sie Guards bedingt basierend auf einem Praedikat an:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Daten an eine andere Seite uebergeben

In diesem Abschnitt zeigen wir, wie Sie Daten von einem Widget an ein anderes uebergeben koennen.

Verwenden Sie in Ihrem Widget den `routeTo`-Helfer und uebergeben Sie die `data`, die Sie an die neue Seite senden moechten.

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

Weitere Beispiele

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

## Routen-Gruppen

Routen-Gruppen organisieren verwandte Routen und wenden gemeinsame Einstellungen an. Sie sind nuetzlich, wenn mehrere Routen dieselben Guards, URL-Praefixe oder Uebergangsstile benoetigen.

Verwenden Sie Routen-Gruppen, wenn Sie:
- Denselben Route Guard auf mehrere Seiten anwenden moechten
- Ein URL-Praefix fuer eine Gruppe von Routen hinzufuegen moechten (z.B. `/admin/...`)
- Denselben Seitenuebergang fuer verwandte Routen festlegen moechten

Sie koennen eine Routen-Gruppe wie im folgenden Beispiel definieren.

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

#### Optionale Einstellungen fuer Routen-Gruppen sind:

| Einstellung | Typ | Beschreibung |
|------------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Route Guards auf alle Routen in der Gruppe anwenden |
| `prefix` | `String` | Ein Praefix zu allen Routenpfaden in der Gruppe hinzufuegen |
| `transition_type` | `TransitionType` | Uebergang fuer alle Routen in der Gruppe festlegen |
| `transition` | `PageTransitionType` | Seitenuebergangstyp festlegen (veraltet, verwenden Sie transition_type) |
| `transition_settings` | `PageTransitionSettings` | Uebergangseinstellungen festlegen |


<div id="route-parameters"></div>

## Routen-Parameter verwenden

Wenn Sie eine neue Seite erstellen, koennen Sie die Route so aktualisieren, dass sie Parameter akzeptiert.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Wenn Sie nun zur Seite navigieren, koennen Sie die `userId` uebergeben

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Sie koennen auf die Parameter in der neuen Seite wie folgt zugreifen.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Query-Parameter

Beim Navigieren zu einer neuen Seite koennen Sie auch Query-Parameter angeben.

Schauen wir uns das an.

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

> **Hinweis:** Solange Ihr Seiten-Widget das `NyStatefulWidget` und die `NyPage`-Klasse erweitert, koennen Sie `widget.queryParameters()` aufrufen, um alle Query-Parameter aus dem Routennamen abzurufen.

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

> **Tipp:** Query-Parameter muessen dem HTTP-Protokoll folgen, z.B. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Seitenuebergaenge

Sie koennen Uebergaenge hinzufuegen, wenn Sie von einer Seite navigieren, indem Sie Ihre `router.dart`-Datei aendern.

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

### Verfuegbare Seitenuebergaenge

#### Grundlegende Uebergaenge
- **`TransitionType.fade()`** - Blendet die neue Seite ein, waehrend die alte Seite ausgeblendet wird
- **`TransitionType.theme()`** - Verwendet das Seitenuebergangs-Theme der App

#### Richtungsgebundene Slide-Uebergaenge
- **`TransitionType.rightToLeft()`** - Gleitet vom rechten Bildschirmrand
- **`TransitionType.leftToRight()`** - Gleitet vom linken Bildschirmrand
- **`TransitionType.topToBottom()`** - Gleitet vom oberen Bildschirmrand
- **`TransitionType.bottomToTop()`** - Gleitet vom unteren Bildschirmrand

#### Slide mit Fade-Uebergaenge
- **`TransitionType.rightToLeftWithFade()`** - Gleitet und blendet vom rechten Rand
- **`TransitionType.leftToRightWithFade()`** - Gleitet und blendet vom linken Rand

#### Transform-Uebergaenge
- **`TransitionType.scale(alignment: ...)`** - Skaliert vom angegebenen Ausrichtungspunkt
- **`TransitionType.rotate(alignment: ...)`** - Rotiert um den angegebenen Ausrichtungspunkt
- **`TransitionType.size(alignment: ...)`** - Waechst vom angegebenen Ausrichtungspunkt

#### Verbundene Uebergaenge (erfordern aktuelles Widget)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Aktuelle Seite geht nach rechts ab, waehrend neue Seite von links kommt
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Aktuelle Seite geht nach links ab, waehrend neue Seite von rechts kommt
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Aktuelle Seite geht nach unten ab, waehrend neue Seite von oben kommt
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Aktuelle Seite geht nach oben ab, waehrend neue Seite von unten kommt

#### Pop-Uebergaenge (erfordern aktuelles Widget)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Aktuelle Seite geht nach rechts ab, neue Seite bleibt stehen
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Aktuelle Seite geht nach links ab, neue Seite bleibt stehen
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Aktuelle Seite geht nach unten ab, neue Seite bleibt stehen
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Aktuelle Seite geht nach oben ab, neue Seite bleibt stehen

#### Material Design Shared Axis-Uebergaenge
- **`TransitionType.sharedAxisHorizontal()`** - Horizontaler Slide- und Fade-Uebergang
- **`TransitionType.sharedAxisVertical()`** - Vertikaler Slide- und Fade-Uebergang
- **`TransitionType.sharedAxisScale()`** - Skalierungs- und Fade-Uebergang

#### Anpassungsparameter
Jeder Uebergang akzeptiert die folgenden optionalen Parameter:

| Parameter | Beschreibung | Standard |
|-----------|-------------|---------|
| `curve` | Animationskurve | Plattformspezifische Kurven |
| `duration` | Animationsdauer | Plattformspezifische Dauern |
| `reverseDuration` | Umgekehrte Animationsdauer | Gleich wie duration |
| `fullscreenDialog` | Ob die Route ein Vollbilddialog ist | `false` |
| `opaque` | Ob die Route undurchsichtig ist | `false` |


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

## Navigationstypen

Beim Navigieren koennen Sie einen der folgenden Typen angeben, wenn Sie den `routeTo`-Helfer verwenden.

| Typ | Beschreibung |
|------|-------------|
| `NavigationType.push` | Eine neue Seite auf den Routen-Stack Ihrer App legen |
| `NavigationType.pushReplace` | Die aktuelle Route ersetzen, wobei die vorherige Route verworfen wird, sobald die neue Route beendet ist |
| `NavigationType.popAndPushNamed` | Die aktuelle Route vom Navigator entfernen und eine benannte Route an ihre Stelle setzen |
| `NavigationType.pushAndRemoveUntil` | Pushen und Routen entfernen, bis das Praedikat true zurueckgibt |
| `NavigationType.pushAndForgetAll` | Zu einer neuen Seite navigieren und alle anderen Seiten auf dem Routen-Stack verwerfen |

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

## Zurueck navigieren

Sobald Sie auf der neuen Seite sind, koennen Sie den `pop()`-Helfer verwenden, um zur vorherigen Seite zurueckzukehren.

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

Wenn Sie einen Wert an das vorherige Widget zurueckgeben moechten, uebergeben Sie ein `result` wie im folgenden Beispiel.

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

## Bedingte Navigation

Verwenden Sie `routeIf()`, um nur zu navigieren, wenn eine Bedingung erfuellt ist:

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

Wenn die Bedingung `false` ist, findet keine Navigation statt.


<div id="route-history"></div>

## Routen-Verlauf

In {{ config('app.name') }} koennen Sie mit den folgenden Helfern auf die Routen-Verlaufsinformationen zugreifen.

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

## Routen-Stack aktualisieren

Sie koennen den Navigationsstapel programmatisch mit `NyNavigator.updateStack()` aktualisieren:

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

| Parameter | Typ | Standard | Beschreibung |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | erforderlich | Liste der Routenpfade, zu denen navigiert werden soll |
| `replace` | `bool` | `true` | Ob der aktuelle Stack ersetzt werden soll |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Daten, die an bestimmte Routen uebergeben werden sollen |

Dies ist nuetzlich fuer:
- Deep-Linking-Szenarien
- Wiederherstellung des Navigationszustands
- Aufbau komplexer Navigationsablaeufe


<div id="deep-linking"></div>

## Deep Linking

Deep Linking ermoeglicht es Benutzern, direkt zu bestimmten Inhalten in Ihrer App ueber URLs zu navigieren. Dies ist nuetzlich fuer:
- Teilen direkter Links zu bestimmten App-Inhalten
- Marketingkampagnen, die auf bestimmte In-App-Funktionen abzielen
- Behandlung von Benachrichtigungen, die bestimmte App-Bildschirme oeffnen sollen
- Nahtlose Web-zu-App-Uebergaenge

## Einrichtung

Bevor Sie Deep Linking in Ihrer App implementieren, stellen Sie sicher, dass Ihr Projekt richtig konfiguriert ist:

### 1. Plattformkonfiguration

**iOS**: Konfigurieren Sie Universal Links in Ihrem Xcode-Projekt
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter Universal Links Konfigurationsanleitung</a>

**Android**: Richten Sie App Links in Ihrer AndroidManifest.xml ein
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter App Links Konfigurationsanleitung</a>

### 2. Definieren Sie Ihre Routen

Alle Routen, die ueber Deep Links erreichbar sein sollen, muessen in Ihrer Router-Konfiguration registriert sein:

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

## Deep Links verwenden

Sobald konfiguriert, kann Ihre App eingehende URLs in verschiedenen Formaten verarbeiten:

### Grundlegende Deep Links

Einfache Navigation zu bestimmten Seiten:

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

Um diese Navigationen programmatisch in Ihrer App auszuloesen:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Pfad-Parameter

Fuer Routen, die dynamische Daten als Teil des Pfades erfordern:

#### Routendefinition

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

#### URL-Format

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Programmatische Navigation

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Query-Parameter

Fuer optionale Parameter oder wenn mehrere dynamische Werte benoetigt werden:

#### URL-Format

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Auf Query-Parameter zugreifen

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

#### Programmatische Navigation mit Query-Parametern

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Deep Links behandeln

Sie koennen Deep-Link-Events in Ihrem `RouteProvider` behandeln:

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

### Deep Links testen

Fuer Entwicklung und Tests koennen Sie die Deep-Link-Aktivierung mit ADB (Android) oder xcrun (iOS) simulieren:

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Debugging-Tipps

- Geben Sie alle Parameter in Ihrer Init-Methode aus, um die korrekte Analyse zu ueberpruefen
- Testen Sie verschiedene URL-Formate, um sicherzustellen, dass Ihre App sie korrekt behandelt
- Denken Sie daran, dass Query-Parameter immer als Strings empfangen werden - konvertieren Sie sie bei Bedarf in den entsprechenden Typ

---

## Gaengige Muster

### Parametertypkonvertierung

Da alle URL-Parameter als Strings uebergeben werden, muessen Sie sie oft konvertieren:

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Optionale Parameter

Behandeln Sie Faelle, in denen Parameter fehlen koennten:

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

## Erweitert

### Pruefen, ob eine Route existiert

Sie koennen pruefen, ob eine Route in Ihrem Router registriert ist:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter-Methoden

Die Klasse `NyRouter` bietet mehrere nuetzliche Methoden:

| Methode | Beschreibung |
|---------|-------------|
| `getRegisteredRouteNames()` | Alle registrierten Routennamen als Liste abrufen |
| `getRegisteredRoutes()` | Alle registrierten Routen als Map abrufen |
| `containsRoutes(routes)` | Pruefen, ob der Router alle angegebenen Routen enthaelt |
| `getInitialRouteName()` | Den initialen Routennamen abrufen |
| `getAuthRouteName()` | Den authentifizierten Routennamen abrufen |
| `getUnknownRouteName()` | Den unbekannten/404-Routennamen abrufen |

### Routen-Argumente abrufen

Sie koennen Routen-Argumente mit `NyRouter.args<T>()` abrufen:

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

### NyArgument und NyQueryParameters

Zwischen Routen uebergebene Daten werden in diesen Klassen verpackt:

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
