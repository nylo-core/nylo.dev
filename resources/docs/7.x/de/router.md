# Router

---

<a name="section-1"></a>

- [Einleitung](#introduction "Einleitung")
- Grundlagen
  - [Routen hinzufügen](#adding-routes "Routen hinzufügen")
  - [Zu Seiten navigieren](#navigating-to-pages "Zu Seiten navigieren")
  - [Initiale Route](#initial-route "Initiale Route")
  - [Vorschau-Route](#preview-route "Vorschau-Route")
  - [Authentifizierte Route](#authenticated-route "Authentifizierte Route")
  - [Unbekannte Route](#unknown-route "Unbekannte Route")
- Daten an eine andere Seite senden
  - [Daten an eine andere Seite übergeben](#passing-data-to-another-page "Daten an eine andere Seite übergeben")
- Navigation
  - [Navigationstypen](#navigation-types "Navigationstypen")
  - [Zurück navigieren](#navigating-back "Zurück navigieren")
  - [Bedingte Navigation](#conditional-navigation "Bedingte Navigation")
  - [Seitenübergänge](#page-transitions "Seitenübergänge")
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

Routen ermöglichen es Ihnen, die verschiedenen Seiten in Ihrer App zu definieren und zwischen ihnen zu navigieren.

Verwenden Sie Routen, wenn Sie:
- Die in Ihrer App verfügbaren Seiten definieren müssen
- Benutzer zwischen Bildschirmen navigieren lassen möchten
- Seiten hinter einer Authentifizierung schützen möchten
- Daten von einer Seite an eine andere übergeben müssen
- Deep Links von URLs behandeln müssen

Sie können Routen in der Datei `lib/routes/router.dart` hinzufügen.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // weitere Routen hinzufügen
  // router.add(AccountPage.path);

});
```

> **Tipp:** Sie können Ihre Routen manuell erstellen oder das <a href="/docs/{{ $version }}/metro">Metro</a> CLI-Tool verwenden, um sie für Sie zu erstellen.

Hier ist ein Beispiel für die Erstellung einer 'account'-Seite mit Metro.

``` bash
metro make:page account_page
```

``` dart
// Fügt Ihre neue Route automatisch zu /lib/routes/router.dart hinzu
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Möglicherweise müssen Sie auch Daten von einer Ansicht an eine andere übergeben. In {{ config('app.name') }} ist das mit dem `NyStatefulWidget` (einem Stateful Widget mit integriertem Zugriff auf Routendaten) möglich. Wir werden tiefer darauf eingehen, um zu erklären, wie es funktioniert.


<div id="adding-routes"></div>

## Routen hinzufügen

Dies ist der einfachste Weg, neue Routen zu Ihrem Projekt hinzuzufügen.

Führen Sie den folgenden Befehl aus, um eine neue Seite zu erstellen.

```bash
metro make:page profile_page
```

Nach der Ausführung wird ein neues Widget namens `ProfilePage` erstellt und in Ihrem Verzeichnis `resources/pages/` abgelegt.
Es wird auch die neue Route zu Ihrer Datei `lib/routes/router.dart` hinzufügen.

Datei: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Meine neue Route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Zu Seiten navigieren

Sie können mit dem `routeTo`-Helfer zu neuen Seiten navigieren.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Initiale Route

In Ihren Routern können Sie die erste Seite festlegen, die geladen werden soll, indem Sie die `.initialRoute()`-Methode verwenden.

Sobald Sie die initiale Route festgelegt haben, wird sie die erste Seite sein, die beim Öffnen der App geladen wird.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // neue initiale Route
});
```


### Bedingte initiale Route

Sie können auch eine bedingte initiale Route mit dem `when`-Parameter festlegen:

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

Während der Entwicklung möchten Sie möglicherweise schnell eine bestimmte Seite in der Vorschau anzeigen, ohne Ihre initiale Route dauerhaft zu ändern. Verwenden Sie `.previewRoute()`, um eine Route temporär zur initialen Route zu machen:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // Wird während der Entwicklung als erstes angezeigt
});
```

Die `previewRoute()`-Methode:
- Überschreibt alle bestehenden `initialRoute()`- und `authenticatedRoute()`-Einstellungen
- Macht die angegebene Route zur initialen Route
- Nützlich zum schnellen Testen bestimmter Seiten während der Entwicklung

> **Warnung:** Denken Sie daran, `.previewRoute()` vor der Veröffentlichung Ihrer App zu entfernen!

<div id="authenticated-route"></div>

## Authentifizierte Route

In Ihrer App können Sie eine Route definieren, die die initiale Route ist, wenn ein Benutzer authentifiziert ist.
Dies überschreibt automatisch die Standard-initiale Route und ist die erste Seite, die der Benutzer sieht, wenn er sich anmeldet.

Zuerst sollte Ihr Benutzer mit dem `Auth.authenticate({...})`-Helfer eingeloggt werden.

Wenn er nun die App öffnet, wird die von Ihnen definierte Route die Standardseite sein, bis er sich abmeldet.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // Auth-Seite
});
```

### Bedingte authentifizierte Route

Sie können auch eine bedingte authentifizierte Route festlegen:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Zur authentifizierten Route navigieren

Sie können mit dem `routeToAuthenticatedRoute()`-Helfer zur authentifizierten Seite navigieren:

``` dart
routeToAuthenticatedRoute();
```

**Siehe auch:** [Authentifizierung](/docs/{{ $version }}/authentication) für Details zur Benutzerauthentifizierung und Sitzungsverwaltung.


<div id="unknown-route"></div>

## Unbekannte Route

Sie können eine Route definieren, die 404/Nicht-gefunden-Szenarien mit `.unknownRoute()` behandelt:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Wenn ein Benutzer zu einer Route navigiert, die nicht existiert, wird ihm die Seite der unbekannten Route angezeigt.


<div id="route-guards"></div>

## Route Guards

Route Guards schützen Seiten vor unbefugtem Zugriff. Sie werden vor Abschluss der Navigation ausgeführt und ermöglichen es Ihnen, Benutzer umzuleiten oder den Zugriff basierend auf Bedingungen zu blockieren.

Verwenden Sie Route Guards, wenn Sie:
- Seiten vor nicht authentifizierten Benutzern schützen müssen
- Berechtigungen vor dem Zugriff prüfen müssen
- Benutzer basierend auf Bedingungen umleiten möchten (z.B. Onboarding nicht abgeschlossen)
- Seitenaufrufe protokollieren oder verfolgen möchten

Um einen neuen Route Guard zu erstellen, führen Sie den folgenden Befehl aus.

``` bash
metro make:route_guard dashboard
```

Fügen Sie als Nächstes den neuen Route Guard zu Ihrer Route hinzu.

``` dart
// Datei: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Guard hinzufügen
    ]
  ); // eingeschränkte Seite
});
```

Sie können Route Guards auch mit der `addRouteGuard`-Methode setzen:

``` dart
// Datei: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // oder mehrere Guards hinzufügen

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard-Lebenszyklus

In v7 verwenden Route Guards die Klasse `NyRouteGuard` mit drei Lebenszyklusmethoden:

- **`onBefore(RouteContext context)`** - Wird vor der Navigation aufgerufen. Geben Sie `next()` zurück, um fortzufahren, `redirect()`, um umzuleiten, oder `abort()`, um abzubrechen.
- **`onAfter(RouteContext context)`** - Wird nach erfolgreicher Navigation zur Route aufgerufen.

### Grundlegendes Beispiel

Datei: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Prüfen, ob sie auf die Seite zugreifen können
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Seitenaufruf nach erfolgreicher Navigation verfolgen
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

Die Klasse `RouteContext` bietet Zugang zu Navigationsinformationen:

| Eigenschaft | Typ | Beschreibung |
|------------|------|-------------|
| `context` | `BuildContext?` | Aktueller Build-Kontext |
| `data` | `dynamic` | An die Route übergebene Daten |
| `queryParameters` | `Map<String, String>` | URL-Query-Parameter |
| `routeName` | `String` | Routenname/-pfad |
| `originalRouteName` | `String?` | Ursprünglicher Routenname vor Transformationen |

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

Zum nächsten Guard oder zur Route fortfahren:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Navigation fortsetzen erlauben
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
| `data` | `dynamic` | An die Route zu übergebende Daten |
| `queryParameters` | `Map<String, dynamic>?` | Query-Parameter |
| `navigationType` | `NavigationType` | Navigationstyp (Standard: pushReplace) |
| `transitionType` | `TransitionType?` | Seitenübergang |
| `onPop` | `Function(dynamic)?` | Callback beim Pop der Route |

### abort()

Navigation ohne Umleitung stoppen:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // Benutzer bleibt auf der aktuellen Route
  }
  return next();
}
```

### setData()

Daten ändern, die an nachfolgende Guards und die Route übergeben werden:

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

Verwenden Sie `ParameterizedGuard`, wenn Sie das Guard-Verhalten pro Route konfigurieren müssen:

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

// Verwendung:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Guard Stacks

Kombinieren Sie mehrere Guards zu einem einzelnen wiederverwendbaren Guard mit `GuardStack`:

``` dart
// Wiederverwendbare Guard-Kombinationen erstellen
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Bedingte Guards

Wenden Sie Guards bedingt basierend auf einem Prädikat an:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Daten an eine andere Seite übergeben

In diesem Abschnitt zeigen wir, wie Sie Daten von einem Widget an ein anderes übergeben können.

Verwenden Sie in Ihrem Widget den `routeTo`-Helfer und übergeben Sie die `data`, die Sie an die neue Seite senden möchten.

``` dart
// HomePage-Widget
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage-Widget (andere Seite)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // oder
    print(data()); // Hello World
  };
```

Weitere Beispiele

``` dart
// Home-Seite-Widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Profil-Seite-Widget (andere Seite)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Routen-Gruppen

Routen-Gruppen organisieren verwandte Routen und wenden gemeinsame Einstellungen an. Sie sind nützlich, wenn mehrere Routen dieselben Guards, URL-Präfixe oder Übergangsstile benötigen.

Verwenden Sie Routen-Gruppen, wenn Sie:
- Denselben Route Guard auf mehrere Seiten anwenden möchten
- Ein URL-Präfix für eine Gruppe von Routen hinzufügen möchten (z.B. `/admin/...`)
- Denselben Seitenübergang für verwandte Routen festlegen möchten

Sie können eine Routen-Gruppe wie im folgenden Beispiel definieren.

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

#### Optionale Einstellungen für Routen-Gruppen sind:

| Einstellung | Typ | Beschreibung |
|------------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Route Guards auf alle Routen in der Gruppe anwenden |
| `prefix` | `String` | Ein Präfix zu allen Routenpfaden in der Gruppe hinzufügen |
| `transition_type` | `TransitionType` | Übergang für alle Routen in der Gruppe festlegen |
| `transition` | `PageTransitionType` | Seitenübergangstyp festlegen (veraltet, verwenden Sie transition_type) |
| `transition_settings` | `PageTransitionSettings` | Übergangseinstellungen festlegen |


<div id="route-parameters"></div>

## Routen-Parameter verwenden

Wenn Sie eine neue Seite erstellen, können Sie die Route so aktualisieren, dass sie Parameter akzeptiert.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Wenn Sie nun zur Seite navigieren, können Sie die `userId` übergeben

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Sie können auf die Parameter in der neuen Seite wie folgt zugreifen.

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

Beim Navigieren zu einer neuen Seite können Sie auch Query-Parameter angeben.

Schauen wir uns das an.

```dart
  // Startseite
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // zur Profilseite navigieren

  ...

  // Profilseite
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // oder
    print(queryParameters()); // {"user": 7}
  };
```

> **Hinweis:** Solange Ihr Seiten-Widget das `NyStatefulWidget` und die `NyPage`-Klasse erweitert, können Sie `widget.queryParameters()` aufrufen, um alle Query-Parameter aus dem Routennamen abzurufen.

```dart
// Beispielseite
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Startseite
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // oder
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Tipp:** Query-Parameter müssen dem HTTP-Protokoll folgen, z.B. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Seitenübergänge

Sie können Übergänge hinzufügen, wenn Sie von einer Seite navigieren, indem Sie Ihre `router.dart`-Datei ändern.

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

### Verfügbare Seitenübergänge

#### Grundlegende Übergänge
- **`TransitionType.fade()`** - Blendet die neue Seite ein, während die alte Seite ausgeblendet wird
- **`TransitionType.theme()`** - Verwendet das Seitenübergangs-Theme der App

#### Richtungsgebundene Slide-Übergänge
- **`TransitionType.rightToLeft()`** - Gleitet vom rechten Bildschirmrand
- **`TransitionType.leftToRight()`** - Gleitet vom linken Bildschirmrand
- **`TransitionType.topToBottom()`** - Gleitet vom oberen Bildschirmrand
- **`TransitionType.bottomToTop()`** - Gleitet vom unteren Bildschirmrand

#### Slide mit Fade-Übergänge
- **`TransitionType.rightToLeftWithFade()`** - Gleitet und blendet vom rechten Rand
- **`TransitionType.leftToRightWithFade()`** - Gleitet und blendet vom linken Rand

#### Transform-Übergänge
- **`TransitionType.scale(alignment: ...)`** - Skaliert vom angegebenen Ausrichtungspunkt
- **`TransitionType.rotate(alignment: ...)`** - Rotiert um den angegebenen Ausrichtungspunkt
- **`TransitionType.size(alignment: ...)`** - Wächst vom angegebenen Ausrichtungspunkt

#### Verbundene Übergänge (erfordern aktuelles Widget)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Aktuelle Seite geht nach rechts ab, während neue Seite von links kommt
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Aktuelle Seite geht nach links ab, während neue Seite von rechts kommt
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Aktuelle Seite geht nach unten ab, während neue Seite von oben kommt
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Aktuelle Seite geht nach oben ab, während neue Seite von unten kommt

#### Pop-Übergänge (erfordern aktuelles Widget)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Aktuelle Seite geht nach rechts ab, neue Seite bleibt stehen
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Aktuelle Seite geht nach links ab, neue Seite bleibt stehen
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Aktuelle Seite geht nach unten ab, neue Seite bleibt stehen
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Aktuelle Seite geht nach oben ab, neue Seite bleibt stehen

#### Material Design Shared Axis-Übergänge
- **`TransitionType.sharedAxisHorizontal()`** - Horizontaler Slide- und Fade-Übergang
- **`TransitionType.sharedAxisVertical()`** - Vertikaler Slide- und Fade-Übergang
- **`TransitionType.sharedAxisScale()`** - Skalierungs- und Fade-Übergang

#### Anpassungsparameter
Jeder Übergang akzeptiert die folgenden optionalen Parameter:

| Parameter | Beschreibung | Standard |
|-----------|-------------|---------|
| `curve` | Animationskurve | Plattformspezifische Kurven |
| `duration` | Animationsdauer | Plattformspezifische Dauern |
| `reverseDuration` | Umgekehrte Animationsdauer | Gleich wie duration |
| `fullscreenDialog` | Ob die Route ein Vollbilddialog ist | `false` |
| `opaque` | Ob die Route undurchsichtig ist | `false` |


``` dart
// Startseite-Widget
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

Beim Navigieren können Sie einen der folgenden Typen angeben, wenn Sie den `routeTo`-Helfer verwenden.

| Typ | Beschreibung |
|------|-------------|
| `NavigationType.push` | Eine neue Seite auf den Routen-Stack Ihrer App legen |
| `NavigationType.pushReplace` | Die aktuelle Route ersetzen, wobei die vorherige Route verworfen wird, sobald die neue Route beendet ist |
| `NavigationType.popAndPushNamed` | Die aktuelle Route vom Navigator entfernen und eine benannte Route an ihre Stelle setzen |
| `NavigationType.pushAndRemoveUntil` | Pushen und Routen entfernen, bis das Prädikat true zurückgibt |
| `NavigationType.pushAndForgetAll` | Zu einer neuen Seite navigieren und alle anderen Seiten auf dem Routen-Stack verwerfen |

``` dart
// Startseite-Widget
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

## Zurück navigieren

Sobald Sie auf der neuen Seite sind, können Sie den `pop()`-Helfer verwenden, um zur vorherigen Seite zurückzukehren.

``` dart
// SettingsPage-Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // oder
    Navigator.pop(context);
  }
...
```

Wenn Sie einen Wert an das vorherige Widget zurückgeben möchten, übergeben Sie ein `result` wie im folgenden Beispiel.

``` dart
// SettingsPage-Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Den Wert vom vorherigen Widget mit dem `onPop`-Parameter abrufen
// HomePage-Widget
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

Verwenden Sie `routeIf()`, um nur zu navigieren, wenn eine Bedingung erfüllt ist:

``` dart
// Nur navigieren, wenn der Benutzer eingeloggt ist
routeIf(isLoggedIn, DashboardPage.path);

// Mit zusätzlichen Optionen
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

In {{ config('app.name') }} können Sie mit den folgenden Helfern auf die Routen-Verlaufsinformationen zugreifen.

``` dart
// Routen-Verlauf abrufen
Nylo.getRouteHistory(); // List<dynamic>

// Aktuelle Route abrufen
Nylo.getCurrentRoute(); // Route<dynamic>?

// Vorherige Route abrufen
Nylo.getPreviousRoute(); // Route<dynamic>?

// Aktuellen Routennamen abrufen
Nylo.getCurrentRouteName(); // String?

// Vorherigen Routennamen abrufen
Nylo.getPreviousRouteName(); // String?

// Aktuelle Routen-Argumente abrufen
Nylo.getCurrentRouteArguments(); // dynamic

// Vorherige Routen-Argumente abrufen
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Routen-Stack aktualisieren

Sie können den Navigationsstapel programmatisch mit `NyNavigator.updateStack()` aktualisieren:

``` dart
// Stack mit einer Liste von Routen aktualisieren
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Daten an bestimmte Routen übergeben
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
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Daten, die an bestimmte Routen übergeben werden sollen |

Dies ist nützlich für:
- Deep-Linking-Szenarien
- Wiederherstellung des Navigationszustands
- Aufbau komplexer Navigationsabläufe


<div id="deep-linking"></div>

## Deep Linking

Deep Linking ermöglicht es Benutzern, direkt zu bestimmten Inhalten in Ihrer App über URLs zu navigieren. Dies ist nützlich für:
- Teilen direkter Links zu bestimmten App-Inhalten
- Marketingkampagnen, die auf bestimmte In-App-Funktionen abzielen
- Behandlung von Benachrichtigungen, die bestimmte App-Bildschirme öffnen sollen
- Nahtlose Web-zu-App-Übergänge

## Einrichtung

Bevor Sie Deep Linking in Ihrer App implementieren, stellen Sie sicher, dass Ihr Projekt richtig konfiguriert ist:

<x-doc-steps>
<x-doc-step number="1" title="Plattformkonfiguration">

**iOS**: Konfigurieren Sie Universal Links in Ihrem Xcode-Projekt
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter Universal Links Konfigurationsanleitung</a>

**Android**: Richten Sie App Links in Ihrer AndroidManifest.xml ein
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter App Links Konfigurationsanleitung</a>

</x-doc-step>

<x-doc-step number="2" title="Definieren Sie Ihre Routen">

Alle Routen, die über Deep Links erreichbar sein sollen, müssen in Ihrer Router-Konfiguration registriert sein:

```dart
// Datei: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Grundlegende Routen
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route mit Parametern
  router.add(HotelBookingPage.path);
});
```
</x-doc-step>
</x-doc-steps>

## Deep Links verwenden

Sobald konfiguriert, kann Ihre App eingehende URLs in verschiedenen Formaten verarbeiten:

### Grundlegende Deep Links

Einfache Navigation zu bestimmten Seiten:

``` bash
https://yourdomain.com/profile       // Öffnet die Profilseite
https://yourdomain.com/settings      // Öffnet die Einstellungsseite
```

Um diese Navigationen programmatisch in Ihrer App auszulösen:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Pfad-Parameter

Für Routen, die dynamische Daten als Teil des Pfades erfordern:

#### Routendefinition

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Route mit einem Parameter-Platzhalter {id} definieren
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Auf den Pfad-Parameter zugreifen
    final hotelId = queryParameters()["id"]; // Gibt "87" zurück für URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // ID verwenden, um Hoteldaten abzurufen oder Operationen durchzuführen
  };

  // Rest der Seitenimplementierung
}
```

#### URL-Format

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Programmatische Navigation

```dart
// Mit Parametern navigieren
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Query-Parameter

Für optionale Parameter oder wenn mehrere dynamische Werte benötigt werden:

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
    // Alle Query-Parameter abrufen
    final params = queryParameters();

    // Auf bestimmte Parameter zugreifen
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Alternativer Zugriffsweg
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Programmatische Navigation mit Query-Parametern

```dart
// Mit Query-Parametern navigieren
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Pfad- und Query-Parameter kombinieren
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Deep Links behandeln

Sie können Deep-Link-Events in Ihrem `RouteProvider` behandeln:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Deep Links behandeln
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Routen-Stack für Deep Links aktualisieren
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

Für Entwicklung und Tests können Sie die Deep-Link-Aktivierung mit ADB (Android) oder xcrun (iOS) simulieren:

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Debugging-Tipps

- Geben Sie alle Parameter in Ihrer Init-Methode aus, um die korrekte Analyse zu überprüfen
- Testen Sie verschiedene URL-Formate, um sicherzustellen, dass Ihre App sie korrekt behandelt
- Denken Sie daran, dass Query-Parameter immer als Strings empfangen werden - konvertieren Sie sie bei Bedarf in den entsprechenden Typ

---

## Gängige Muster

### Parametertypkonvertierung

Da alle URL-Parameter als Strings übergeben werden, müssen Sie sie oft konvertieren:

```dart
// String-Parameter in geeignete Typen konvertieren
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Optionale Parameter

Behandeln Sie Fälle, in denen Parameter fehlen könnten:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Bestimmtes Benutzerprofil laden
} else {
  // Aktuelles Benutzerprofil laden
}

// Oder hasQueryParameter prüfen
if (hasQueryParameter('status')) {
  // Etwas mit dem Status-Parameter tun
} else {
  // Fehlen des Parameters behandeln
}
```


<div id="advanced"></div>

## Erweitert

### Prüfen, ob eine Route existiert

Sie können prüfen, ob eine Route in Ihrem Router registriert ist:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter-Methoden

Die Klasse `NyRouter` bietet mehrere nützliche Methoden:

| Methode | Beschreibung |
|---------|-------------|
| `getRegisteredRouteNames()` | Alle registrierten Routennamen als Liste abrufen |
| `getRegisteredRoutes()` | Alle registrierten Routen als Map abrufen |
| `containsRoutes(routes)` | Prüfen, ob der Router alle angegebenen Routen enthält |
| `getInitialRouteName()` | Den initialen Routennamen abrufen |
| `getAuthRouteName()` | Den authentifizierten Routennamen abrufen |
| `getUnknownRouteName()` | Den unbekannten/404-Routennamen abrufen |

### Routen-Argumente abrufen

Sie können Routen-Argumente mit `NyRouter.args<T>()` abrufen:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Typisierte Argumente abrufen
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument und NyQueryParameters

Zwischen Routen übergebene Daten werden in diesen Klassen verpackt:

``` dart
// NyArgument enthält Routendaten
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters enthält URL-Query-Parameter
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
