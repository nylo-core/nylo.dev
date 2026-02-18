# Route Guards

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Tworzenie strazy trasy](#creating-a-route-guard "Tworzenie strazy trasy")
- [Cykl zycia strazy](#guard-lifecycle "Cykl zycia strazy")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Akcje strazy](#guard-actions "Akcje strazy")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Stosowanie strazy do tras](#applying-guards "Stosowanie strazy do tras")
- [Straze grupowe](#group-guards "Straze grupowe")
- [Kompozycja strazy](#guard-composition "Kompozycja strazy")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Przyklady](#examples "Przyklady praktyczne")

<div id="introduction"></div>

## Wprowadzenie

Straze tras zapewniaja **middleware dla nawigacji** w {{ config('app.name') }}. Przechwytuja przejscia miedzy trasami i pozwalaja kontrolowac, czy uzytkownik moze uzyskac dostep do strony, przekierowac go w inne miejsce lub zmodyfikowac dane przekazywane do trasy.

Typowe przypadki uzycia obejmuja:
- **Sprawdzanie uwierzytelnienia** -- przekierowanie nieuwierzytelnionych uzytkownikow na strone logowania
- **Dostep oparty na rolach** -- ograniczenie stron do uzytkownikow administratorow
- **Walidacja danych** -- upewnienie sie, ze wymagane dane istnieja przed nawigacja
- **Wzbogacanie danych** -- dolaczanie dodatkowych danych do trasy

Straze sa wykonywane **w kolejnosci** przed nawigacja. Jesli ktorakolwiek straz zwroci `handled`, nawigacja zostaje zatrzymana (przez przekierowanie lub przerwanie).

<div id="creating-a-route-guard"></div>

## Tworzenie strazy trasy

Utworz straz trasy za pomoca Metro CLI:

``` bash
metro make:route_guard auth
```

To generuje plik strazy:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Dodaj logike strazy tutaj
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Cykl zycia strazy

Kazda straz trasy posiada trzy metody cyklu zycia:

<div id="on-before"></div>

### onBefore

Wywolywana **przed** nawigacja. Tutaj sprawdzasz warunki i decydujesz, czy zezwolic, przekierowac, czy przerwac nawigacje.

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

Wartosci zwracane:
- `next()` -- kontynuuj do nastepnej strazy lub przejdz do trasy
- `redirect(path)` -- przekieruj do innej trasy
- `abort()` -- calkowicie anuluj nawigacje

<div id="on-after"></div>

### onAfter

Wywolywana **po** udanej nawigacji. Uzywaj do analityki, logowania lub efektow ubocznych po nawigacji.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Zaloguj wyswietlenie strony
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Wywolywana gdy uzytkownik **opuszcza** trase. Zwroc `false`, aby zapobiec opuszczeniu strony przez uzytkownika.

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Pokaz dialog potwierdzenia
    return await showConfirmDialog();
  }
  return true; // Zezwol na opuszczenie
}
```

<div id="route-context"></div>

## RouteContext

Obiekt `RouteContext` jest przekazywany do wszystkich metod cyklu zycia strazy i zawiera informacje o nawigacji:

| Wlasciwosc | Typ | Opis |
|----------|------|-------------|
| `context` | `BuildContext?` | Aktualny kontekst budowania |
| `data` | `dynamic` | Dane przekazane do trasy |
| `queryParameters` | `Map<String, String>` | Parametry zapytania URL |
| `routeName` | `String` | Nazwa/sciezka trasy docelowej |
| `originalRouteName` | `String?` | Oryginalna nazwa trasy przed transformacjami |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Dostep do informacji o trasie
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Transformacja kontekstu trasy

Utworz kopie z innymi danymi:

``` dart
// Zmien typ danych
RouteContext<User> userContext = context.withData<User>(currentUser);

// Kopiuj ze zmodyfikowanymi polami
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Akcje strazy

<div id="next"></div>

### next

Kontynuuj do nastepnej strazy w lancuchu lub przejdz do trasy, jesli to ostatnia straz:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Przekieruj uzytkownika do innej trasy:

``` dart
return redirect(LoginPage.path);
```

Z dodatkowymi opcjami:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parametr | Typ | Domyslnie | Opis |
|-----------|------|---------|-------------|
| `path` | `Object` | wymagany | Sciezka trasy lub RouteView |
| `data` | `dynamic` | null | Dane do przekazania do trasy przekierowania |
| `queryParameters` | `Map<String, dynamic>?` | null | Parametry zapytania |
| `navigationType` | `NavigationType` | `pushReplace` | Metoda nawigacji |
| `result` | `dynamic` | null | Wynik do zwrocenia |
| `removeUntilPredicate` | `Function?` | null | Predykat usuwania tras |
| `transitionType` | `TransitionType?` | null | Typ przejscia strony |
| `onPop` | `Function(dynamic)?` | null | Wywolanie zwrotne przy cofnieciu |

<div id="abort"></div>

### abort

Anuluj nawigacje bez przekierowania. Uzytkownik pozostaje na biezacej stronie:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Zmodyfikuj dane, ktore beda przekazane do kolejnych strazy i trasy docelowej:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Wzbogac dane trasy
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## Stosowanie strazy do tras

Dodaj straze do poszczegolnych tras w pliku routera:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Dodaj pojedyncza straz
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Dodaj wiele strazy (wykonywane w kolejnosci)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Straze grupowe

Zastosuj straze do wielu tras jednoczesnie za pomoca grup tras:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // Wszystkie trasy w tej grupie wymagaja uwierzytelnienia
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

## Kompozycja strazy

{{ config('app.name') }} udostepnia narzedzia do komponowania strazy razem w celu tworzenia wzorcow wielokrotnego uzytku.

<div id="guard-stack"></div>

### GuardStack

Polacz wiele strazy w jedna straz wielokrotnego uzytku:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Uzyj stosu na trasie
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` wykonuje straze w kolejnosci. Jesli ktorakolwiek straz zwroci `handled`, pozostale straze sa pomijane.

<div id="conditional-guard"></div>

### ConditionalGuard

Zastosuj straz tylko gdy warunek jest spelniony:

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

Jesli warunek zwroci `false`, straz jest pomijana i nawigacja jest kontynuowana.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Tworzenie strazy przyjmujacych parametry konfiguracyjne:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = dozwolone role

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Uzycie
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## Przyklady

### Straz uwierzytelnienia

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

### Straz subskrypcji z parametrami

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

// Wymagaj subskrypcji premium lub pro
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Straz logowania zdarzen

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Nawigacja do: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Dotarto do: ${context.routeName}");
  }
}
```