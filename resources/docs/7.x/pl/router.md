# Router

---

<a name="section-1"></a>

- [Wprowadzenie](#introduction "Wprowadzenie")
- Podstawy
  - [Dodawanie tras](#adding-routes "Dodawanie tras")
  - [Nawigacja do stron](#navigating-to-pages "Nawigacja do stron")
  - [Trasa początkowa](#initial-route "Trasa początkowa")
  - [Trasa podglądu](#preview-route "Trasa podglądu")
  - [Trasa uwierzytelniona](#authenticated-route "Trasa uwierzytelniona")
  - [Nieznana trasa](#unknown-route "Nieznana trasa")
- Przesyłanie danych do innej strony
  - [Przekazywanie danych do innej strony](#passing-data-to-another-page "Przekazywanie danych do innej strony")
- Nawigacja
  - [Typy nawigacji](#navigation-types "Typy nawigacji")
  - [Nawigacja wstecz](#navigating-back "Nawigacja wstecz")
  - [Nawigacja warunkowa](#conditional-navigation "Nawigacja warunkowa")
  - [Przejścia stron](#page-transitions "Przejścia stron")
  - [Historia tras](#route-history "Historia tras")
  - [Aktualizacja stosu tras](#update-route-stack "Aktualizacja stosu tras")
- Parametry tras
  - [Używanie parametrów tras](#route-parameters "Parametry tras")
  - [Parametry zapytania](#query-parameters "Parametry zapytania")
- Strażnicy tras
  - [Tworzenie strażników tras](#route-guards "Strażnicy tras")
  - [Cykl życia NyRouteGuard](#nyroute-guard-lifecycle "Cykl życia NyRouteGuard")
  - [Metody pomocnicze strażników](#guard-helper-methods "Metody pomocnicze strażników")
  - [Strażnicy z parametrami](#parameterized-guards "Strażnicy z parametrami")
  - [Stosy strażników](#guard-stacks "Stosy strażników")
  - [Strażnicy warunkowi](#conditional-guards "Strażnicy warunkowi")
- Grupy tras
  - [Grupy tras](#route-groups "Grupy tras")
- [Deep linking](#deep-linking "Deep linking")
- [Zaawansowane](#advanced "Zaawansowane")



<div id="introduction"></div>

## Wprowadzenie

Trasy pozwalają definiować różne strony w aplikacji i nawigować między nimi.

Używaj tras, gdy potrzebujesz:
- Zdefiniować strony dostępne w aplikacji
- Nawigować użytkowników między ekranami
- Chronić strony za uwierzytelnianiem
- Przekazywać dane z jednej strony do drugiej
- Obsługiwać deep linki z adresów URL

Możesz dodawać trasy w pliku `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **Wskazówka:** Możesz tworzyć trasy ręcznie lub użyć narzędzia CLI <a href="/docs/{{ $version }}/metro">Metro</a>, aby je utworzyć automatycznie.

Oto przykład tworzenia strony 'account' za pomocą Metro.

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

Możesz też potrzebować przekazać dane z jednego widoku do drugiego. W {{ config('app.name') }} jest to możliwe za pomocą `NyStatefulWidget` (stateful widget z wbudowanym dostępem do danych trasy). Zagłębimy się w to, aby wyjaśnić, jak to działa.


<div id="adding-routes"></div>

## Dodawanie tras

To najprostszy sposób dodawania nowych tras do projektu.

Uruchom poniższe polecenie, aby utworzyć nową stronę.

```bash
metro make:page profile_page
```

Po uruchomieniu powyższego polecenia zostanie utworzony nowy Widget o nazwie `ProfilePage` i dodany do katalogu `resources/pages/`.
Nowa trasa zostanie również dodana do pliku `lib/routes/router.dart`.

Plik: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Nawigacja do stron

Możesz nawigować do nowych stron za pomocą helpera `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Trasa początkowa

W routerach możesz zdefiniować pierwszą stronę, która powinna się załadować, używając metody `.initialRoute()`.

Po ustawieniu trasy początkowej będzie to pierwsza strona, która się załaduje po otwarciu aplikacji.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### Warunkowa trasa początkowa

Możesz również ustawić warunkową trasę początkową za pomocą parametru `when`:

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

### Nawigacja do trasy początkowej

Użyj `routeToInitial()`, aby nawigować do trasy początkowej aplikacji:

``` dart
void _goHome() {
    routeToInitial();
}
```

Spowoduje to nawigację do trasy oznaczonej `.initialRoute()` i wyczyszczenie stosu nawigacji.

<div id="preview-route"></div>

## Trasa podglądu

Podczas rozwoju możesz chcieć szybko wyświetlić podgląd konkretnej strony bez trwałej zmiany trasy początkowej. Użyj `.previewRoute()`, aby tymczasowo ustawić dowolną trasę jako początkową:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

Metoda `previewRoute()`:
- Nadpisuje istniejące ustawienia `initialRoute()` i `authenticatedRoute()`
- Ustawia określoną trasę jako początkową
- Przydatna do szybkiego testowania konkretnych stron podczas rozwoju

> **Ostrzeżenie:** Pamiętaj, aby usunąć `.previewRoute()` przed wydaniem aplikacji!

<div id="authenticated-route"></div>

## Trasa uwierzytelniona

W aplikacji możesz zdefiniować trasę, która będzie trasą początkową, gdy użytkownik jest uwierzytelniony.
Automatycznie nadpisze domyślną trasę początkową i będzie pierwszą stroną, którą użytkownik zobaczy po zalogowaniu.

Najpierw użytkownik powinien być zalogowany za pomocą helpera `Auth.authenticate({...})`.

Teraz, gdy otworzy aplikację, zdefiniowana trasa będzie domyślną stroną do momentu wylogowania.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### Warunkowa trasa uwierzytelniona

Możesz również ustawić warunkową trasę uwierzytelnioną:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Nawigacja do trasy uwierzytelnionej

Możesz nawigować do strony uwierzytelnionej za pomocą helpera `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Zobacz także:** [Authentication](/docs/{{ $version }}/authentication) aby poznać szczegóły uwierzytelniania użytkowników i zarządzania sesjami.


<div id="unknown-route"></div>

## Nieznana trasa

Możesz zdefiniować trasę do obsługi scenariuszy 404/nie znaleziono za pomocą `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Gdy użytkownik nawiguje do trasy, która nie istnieje, zostanie mu wyświetlona strona nieznanej trasy.


<div id="route-guards"></div>

## Strażnicy tras

Strażnicy tras chronią strony przed nieautoryzowanym dostępem. Uruchamiają się przed zakończeniem nawigacji, pozwalając przekierować użytkowników lub zablokować dostęp na podstawie warunków.

Używaj strażników tras, gdy potrzebujesz:
- Chronić strony przed nieuwierzytelnionymi użytkownikami
- Sprawdzać uprawnienia przed zezwoleniem na dostęp
- Przekierowywać użytkowników na podstawie warunków (np. niezakończony onboarding)
- Rejestrować lub śledzić wyświetlenia stron

Aby utworzyć nowego strażnika tras, uruchom poniższe polecenie.

``` bash
metro make:route_guard dashboard
```

Następnie dodaj nowego strażnika tras do swojej trasy.

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

Możesz również ustawić strażników tras za pomocą metody `addRouteGuard`:

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

## Cykl życia NyRouteGuard

W v7 strażnicy tras używają klasy `NyRouteGuard` z trzema metodami cyklu życia:

- **`onBefore(RouteContext context)`** - Wywoływany przed nawigacją. Zwróć `next()`, aby kontynuować, `redirect()`, aby przejść gdzie indziej, lub `abort()`, aby zatrzymać.
- **`onAfter(RouteContext context)`** - Wywoływany po pomyślnej nawigacji do trasy.

### Podstawowy przykład

Plik: **/routes/guards/dashboard_route_guard.dart**
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

Klasa `RouteContext` zapewnia dostęp do informacji nawigacyjnych:

| Właściwość | Typ | Opis |
|----------|------|-------------|
| `context` | `BuildContext?` | Bieżący kontekst build |
| `data` | `dynamic` | Dane przekazane do trasy |
| `queryParameters` | `Map<String, String>` | Parametry zapytania URL |
| `routeName` | `String` | Nazwa/ścieżka trasy |
| `originalRouteName` | `String?` | Oryginalna nazwa trasy przed transformacjami |

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

## Metody pomocnicze strażników

### next()

Kontynuuj do następnego strażnika lub do trasy:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

Przekieruj do innej trasy:

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

Metoda `redirect()` przyjmuje:

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `path` | `Object` | Ścieżka trasy lub RouteView |
| `data` | `dynamic` | Dane do przekazania do trasy |
| `queryParameters` | `Map<String, dynamic>?` | Parametry zapytania |
| `navigationType` | `NavigationType` | Typ nawigacji (domyślnie: pushReplace) |
| `transitionType` | `TransitionType?` | Przejście strony |
| `onPop` | `Function(dynamic)?` | Callback po opuszczeniu trasy |

### abort()

Zatrzymaj nawigację bez przekierowywania:

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

Modyfikuj dane przekazywane do kolejnych strażników i trasy:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Strażnicy z parametrami

Użyj `ParameterizedGuard`, gdy potrzebujesz skonfigurować zachowanie strażnika per trasa:

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

## Stosy strażników

Komponuj wiele strażników w jednego wielokrotnego użytku za pomocą `GuardStack`:

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

## Strażnicy warunkowi

Stosuj strażników warunkowo na podstawie predykatu:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Przekazywanie danych do innej strony

W tej sekcji pokażemy, jak przekazać dane z jednego widgetu do drugiego.

Z Twojego widgetu użyj helpera `routeTo` i przekaż `data`, które chcesz wysłać do nowej strony.

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

Więcej przykładów

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

## Grupy tras

Grupy tras organizują powiązane trasy i stosują wspólne ustawienia. Są przydatne, gdy wiele tras potrzebuje tych samych strażników, prefiksu URL lub stylu przejścia.

Używaj grup tras, gdy potrzebujesz:
- Zastosować tego samego strażnika tras do wielu stron
- Dodać prefiks URL do zestawu tras (np. `/admin/...`)
- Ustawić to samo przejście strony dla powiązanych tras

Możesz zdefiniować grupę tras jak w poniższym przykładzie.

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

#### Opcjonalne ustawienia dla grup tras:

| Ustawienie | Typ | Opis |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Stosuj strażników tras do wszystkich tras w grupie |
| `prefix` | `String` | Dodaj prefiks do wszystkich ścieżek tras w grupie |
| `transition_type` | `TransitionType` | Ustaw przejście dla wszystkich tras w grupie |
| `transition` | `PageTransitionType` | Ustaw typ przejścia strony (przestarzałe, użyj transition_type) |
| `transition_settings` | `PageTransitionSettings` | Ustaw ustawienia przejścia |


<div id="route-parameters"></div>

## Używanie parametrów tras

Gdy tworzysz nową stronę, możesz zaktualizować trasę, aby akceptowała parametry.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Teraz, gdy nawigujesz do strony, możesz przekazać `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Możesz uzyskać dostęp do parametrów na nowej stronie w ten sposób.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Parametry zapytania

Podczas nawigacji do nowej strony możesz też podać parametry zapytania.

Przyjrzyjmy się temu.

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

> **Uwaga:** Dopóki widget Twojej strony rozszerza klasy `NyStatefulWidget` i `NyPage`, możesz wywoływać `widget.queryParameters()`, aby pobrać wszystkie parametry zapytania z nazwy trasy.

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

> **Wskazówka:** Parametry zapytania muszą być zgodne z protokołem HTTP, np. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Przejścia stron

Możesz dodać przejścia podczas nawigacji z jednej strony, modyfikując plik `router.dart`.

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

### Dostępne przejścia stron

#### Podstawowe przejścia
- **`TransitionType.fade()`** - Zanikanie nowej strony z jednoczesnym zanikaniem starej
- **`TransitionType.theme()`** - Używa motywu przejść stron aplikacji

#### Kierunkowe przejścia przesuwania
- **`TransitionType.rightToLeft()`** - Przesuwa z prawej krawędzi ekranu
- **`TransitionType.leftToRight()`** - Przesuwa z lewej krawędzi ekranu
- **`TransitionType.topToBottom()`** - Przesuwa z górnej krawędzi ekranu
- **`TransitionType.bottomToTop()`** - Przesuwa z dolnej krawędzi ekranu

#### Przesuwanie z zanikaniem
- **`TransitionType.rightToLeftWithFade()`** - Przesuwa i zanika z prawej krawędzi
- **`TransitionType.leftToRightWithFade()`** - Przesuwa i zanika z lewej krawędzi

#### Przejścia transformacji
- **`TransitionType.scale(alignment: ...)`** - Skaluje z określonego punktu wyrównania
- **`TransitionType.rotate(alignment: ...)`** - Obraca wokół określonego punktu wyrównania
- **`TransitionType.size(alignment: ...)`** - Rośnie z określonego punktu wyrównania

#### Przejścia łączone (wymaga bieżącego widgetu)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Bieżąca strona wychodzi w prawo, nowa wchodzi z lewej
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Bieżąca strona wychodzi w lewo, nowa wchodzi z prawej
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Bieżąca strona wychodzi w dół, nowa wchodzi z góry
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Bieżąca strona wychodzi w górę, nowa wchodzi z dołu

#### Przejścia pop (wymaga bieżącego widgetu)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Bieżąca strona wychodzi w prawo, nowa pozostaje na miejscu
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Bieżąca strona wychodzi w lewo, nowa pozostaje na miejscu
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Bieżąca strona wychodzi w dół, nowa pozostaje na miejscu
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Bieżąca strona wychodzi w górę, nowa pozostaje na miejscu

#### Przejścia Material Design ze wspólną osią
- **`TransitionType.sharedAxisHorizontal()`** - Poziome przesuwanie z zanikaniem
- **`TransitionType.sharedAxisVertical()`** - Pionowe przesuwanie z zanikaniem
- **`TransitionType.sharedAxisScale()`** - Skalowanie z zanikaniem

#### Parametry dostosowania
Każde przejście przyjmuje następujące opcjonalne parametry:

| Parametr | Opis | Domyślnie |
|-----------|-------------|---------|
| `curve` | Krzywa animacji | Krzywe specyficzne dla platformy |
| `duration` | Czas trwania animacji | Czasy specyficzne dla platformy |
| `reverseDuration` | Czas trwania animacji odwrotnej | Taki sam jak duration |
| `fullscreenDialog` | Czy trasa jest pełnoekranowym dialogiem | `false` |
| `opaque` | Czy trasa jest nieprzezroczysta | `false` |


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

## Typy nawigacji

Podczas nawigacji możesz określić jeden z poniższych typów, jeśli używasz helpera `routeTo`.

| Typ | Opis |
|------|-------------|
| `NavigationType.push` | Dodaj nową stronę na stos tras aplikacji |
| `NavigationType.pushReplace` | Zastąp bieżącą trasę, usuwając poprzednią trasę po zakończeniu nowej |
| `NavigationType.popAndPushNamed` | Zdejmij bieżącą trasę z nawigatora i dodaj nazwaną trasę na jej miejsce |
| `NavigationType.pushAndRemoveUntil` | Dodaj i usuwaj trasy, aż predykat zwróci true |
| `NavigationType.pushAndForgetAll` | Przejdź do nowej strony i usuń wszystkie inne strony ze stosu tras |

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

## Nawigacja wstecz

Gdy jesteś na nowej stronie, możesz użyć helpera `pop()`, aby wrócić do poprzedniej strony.

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

Jeśli chcesz zwrócić wartość do poprzedniego widgetu, podaj `result` jak w poniższym przykładzie.

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

## Nawigacja warunkowa

Użyj `routeIf()`, aby nawigować tylko wtedy, gdy warunek jest spełniony:

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

Jeśli warunek jest `false`, nawigacja nie następuje.


<div id="route-history"></div>

## Historia tras

W {{ config('app.name') }} możesz uzyskać dostęp do informacji o historii tras za pomocą poniższych helperów.

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

## Aktualizacja stosu tras

Możesz programowo aktualizować stos nawigacji za pomocą `NyNavigator.updateStack()`:

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

| Parametr | Typ | Domyślnie | Opis |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | wymagany | Lista ścieżek tras do nawigacji |
| `replace` | `bool` | `true` | Czy zastąpić bieżący stos |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Dane do przekazania do konkretnych tras |

Jest to przydatne dla:
- Scenariuszy deep linkingu
- Przywracania stanu nawigacji
- Budowania złożonych przepływów nawigacji


<div id="deep-linking"></div>

## Deep Linking

Deep linking pozwala użytkownikom nawigować bezpośrednio do konkretnej treści w aplikacji za pomocą adresów URL. Jest to przydatne dla:
- Udostępniania bezpośrednich linków do konkretnej treści aplikacji
- Kampanii marketingowych kierujących do konkretnych funkcji w aplikacji
- Obsługi powiadomień, które powinny otwierać konkretne ekrany aplikacji
- Płynnych przejść z sieci do aplikacji

## Konfiguracja

Przed implementacją deep linkingu w aplikacji upewnij się, że projekt jest poprawnie skonfigurowany:

### 1. Konfiguracja platformy

**iOS**: Skonfiguruj universal links w projekcie Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Przewodnik konfiguracji Flutter Universal Links</a>

**Android**: Skonfiguruj app links w AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Przewodnik konfiguracji Flutter App Links</a>

### 2. Zdefiniuj swoje trasy

Wszystkie trasy dostępne przez deep linki muszą być zarejestrowane w konfiguracji routera:

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

## Używanie deep linków

Po skonfigurowaniu aplikacja może obsługiwać przychodzące adresy URL w różnych formatach:

### Podstawowe deep linki

Prosta nawigacja do konkretnych stron:

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

Aby programowo wywołać te nawigacje w aplikacji:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Parametry ścieżki

Dla tras wymagających dynamicznych danych jako części ścieżki:

#### Definicja trasy

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

#### Format URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Nawigacja programowa

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Parametry zapytania

Dla opcjonalnych parametrów lub gdy potrzeba wielu dynamicznych wartości:

#### Format URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Dostęp do parametrów zapytania

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

#### Nawigacja programowa z parametrami zapytania

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Obsługa deep linków

Możesz obsługiwać zdarzenia deep linków w swoim `RouteProvider`:

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

### Testowanie deep linków

Do celów rozwoju i testowania możesz symulować aktywację deep linków za pomocą ADB (Android) lub xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Wskazówki debugowania

- Wypisz wszystkie parametry w metodzie init, aby zweryfikować poprawne parsowanie
- Testuj różne formaty URL, aby upewnić się, że aplikacja je poprawnie obsługuje
- Pamiętaj, że parametry zapytania są zawsze otrzymywane jako ciągi znaków, konwertuj je na odpowiedni typ w razie potrzeby

---

## Typowe wzorce

### Konwersja typów parametrów

Ponieważ wszystkie parametry URL są przekazywane jako ciągi znaków, często będziesz musiał je konwertować:

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Opcjonalne parametry

Obsługa przypadków, gdy parametry mogą być brakujące:

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

## Zaawansowane

### Sprawdzanie czy trasa istnieje

Możesz sprawdzić, czy trasa jest zarejestrowana w routerze:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Metody NyRouter

Klasa `NyRouter` udostępnia kilka przydatnych metod:

| Metoda | Opis |
|--------|-------------|
| `getRegisteredRouteNames()` | Pobierz wszystkie zarejestrowane nazwy tras jako listę |
| `getRegisteredRoutes()` | Pobierz wszystkie zarejestrowane trasy jako mapę |
| `containsRoutes(routes)` | Sprawdź czy router zawiera wszystkie określone trasy |
| `getInitialRouteName()` | Pobierz nazwę trasy początkowej |
| `getAuthRouteName()` | Pobierz nazwę trasy uwierzytelnionej |
| `getUnknownRouteName()` | Pobierz nazwę nieznanej trasy/404 |

### Pobieranie argumentów trasy

Możesz pobrać argumenty trasy za pomocą `NyRouter.args<T>()`:

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

### NyArgument i NyQueryParameters

Dane przekazywane między trasami są opakowywane w te klasy:

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
