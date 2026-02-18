# Route Guards

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creer un Route Guard](#creating-a-route-guard "Creer un Route Guard")
- [Cycle de vie du Guard](#guard-lifecycle "Cycle de vie du Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Actions du Guard](#guard-actions "Actions du Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Appliquer des Guards aux routes](#applying-guards "Appliquer des Guards aux routes")
- [Guards de groupe](#group-guards "Guards de groupe")
- [Composition de Guards](#guard-composition "Composition de Guards")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Exemples](#examples "Exemples")

<div id="introduction"></div>

## Introduction

Les route guards fournissent un **middleware de navigation** dans {{ config('app.name') }}. Ils interceptent les transitions de route et vous permettent de controler si un utilisateur peut acceder a une page, de le rediriger ailleurs ou de modifier les donnees transmises a une route.

Les cas d'utilisation courants incluent :
- **Verification d'authentification** -- rediriger les utilisateurs non authentifies vers une page de connexion
- **Acces base sur les roles** -- restreindre les pages aux utilisateurs administrateurs
- **Validation des donnees** -- s'assurer que les donnees requises existent avant la navigation
- **Enrichissement des donnees** -- attacher des donnees supplementaires a une route

Les guards sont executes **dans l'ordre** avant que la navigation ne se produise. Si un guard retourne `handled`, la navigation s'arrete (soit par redirection, soit par abandon).

<div id="creating-a-route-guard"></div>

## Creer un Route Guard

Creez un route guard en utilisant le CLI Metro :

``` bash
metro make:route_guard auth
```

Cela genere un fichier guard :

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

## Cycle de vie du Guard

Chaque route guard possede trois methodes de cycle de vie :

<div id="on-before"></div>

### onBefore

Appelee **avant** que la navigation ne se produise. C'est ici que vous verifiez les conditions et decidez d'autoriser, de rediriger ou d'abandonner la navigation.

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

Valeurs de retour :
- `next()` -- continuer vers le guard suivant ou naviguer vers la route
- `redirect(path)` -- rediriger vers une route differente
- `abort()` -- annuler completement la navigation

<div id="on-after"></div>

### onAfter

Appelee **apres** une navigation reussie. Utilisez-la pour les analyses, la journalisation ou les effets secondaires post-navigation.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Appelee lorsque l'utilisateur **quitte** une route. Retournez `false` pour empecher l'utilisateur de partir.

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

L'objet `RouteContext` est transmis a toutes les methodes de cycle de vie du guard et contient des informations sur la navigation :

| Propriete | Type | Description |
|-----------|------|-------------|
| `context` | `BuildContext?` | Contexte de construction actuel |
| `data` | `dynamic` | Donnees transmises a la route |
| `queryParameters` | `Map<String, String>` | Parametres de requete de l'URL |
| `routeName` | `String` | Nom/chemin de la route cible |
| `originalRouteName` | `String?` | Nom de route original avant transformations |

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

### Transformer le RouteContext

Creez une copie avec des donnees differentes :

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

## Actions du Guard

<div id="next"></div>

### next

Continuer vers le guard suivant dans la chaine, ou naviguer vers la route si c'est le dernier guard :

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Rediriger l'utilisateur vers une route differente :

``` dart
return redirect(LoginPage.path);
```

Avec des options supplementaires :

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `path` | `Object` | requis | Chemin de route ou RouteView |
| `data` | `dynamic` | null | Donnees a transmettre a la route de redirection |
| `queryParameters` | `Map<String, dynamic>?` | null | Parametres de requete |
| `navigationType` | `NavigationType` | `pushReplace` | Methode de navigation |
| `result` | `dynamic` | null | Resultat a retourner |
| `removeUntilPredicate` | `Function?` | null | Predicat de suppression de route |
| `transitionType` | `TransitionType?` | null | Type de transition de page |
| `onPop` | `Function(dynamic)?` | null | Callback au retour |

<div id="abort"></div>

### abort

Annuler la navigation sans redirection. L'utilisateur reste sur sa page actuelle :

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Modifier les donnees qui seront transmises aux guards suivants et a la route cible :

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

## Appliquer des Guards aux routes

Ajoutez des guards a des routes individuelles dans votre fichier de routeur :

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

## Guards de groupe

Appliquez des guards a plusieurs routes simultanement en utilisant les groupes de routes :

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

## Composition de Guards

{{ config('app.name') }} fournit des outils pour composer des guards ensemble afin de creer des patrons reutilisables.

<div id="guard-stack"></div>

### GuardStack

Combinez plusieurs guards en un seul guard reutilisable :

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

`GuardStack` execute les guards dans l'ordre. Si un guard retourne `handled`, les guards restants sont ignores.

<div id="conditional-guard"></div>

### ConditionalGuard

Appliquer un guard uniquement lorsqu'une condition est vraie :

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

Si la condition retourne `false`, le guard est ignore et la navigation continue.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Creez des guards qui acceptent des parametres de configuration :

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

## Exemples

### Guard d'authentification

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

### Guard d'abonnement avec parametres

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

### Guard de journalisation

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
