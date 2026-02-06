# Routeur

---

<a name="section-1"></a>

- [Introduction](#introduction "Introduction")
- Bases
  - [Ajouter des routes](#adding-routes "Ajouter des routes")
  - [Naviguer vers des pages](#navigating-to-pages "Naviguer vers des pages")
  - [Route initiale](#initial-route "Route initiale")
  - [Route de previsualisation](#preview-route "Route de previsualisation")
  - [Route authentifiee](#authenticated-route "Route authentifiee")
  - [Route inconnue](#unknown-route "Route inconnue")
- Envoyer des donnees a une autre page
  - [Passer des donnees a une autre page](#passing-data-to-another-page "Passer des donnees a une autre page")
- Navigation
  - [Types de navigation](#navigation-types "Types de navigation")
  - [Naviguer en arriere](#navigating-back "Naviguer en arriere")
  - [Navigation conditionnelle](#conditional-navigation "Navigation conditionnelle")
  - [Transitions de page](#page-transitions "Transitions de page")
  - [Historique des routes](#route-history "Historique des routes")
  - [Mettre a jour la pile de routes](#update-route-stack "Mettre a jour la pile de routes")
- Parametres de route
  - [Utiliser les parametres de route](#route-parameters "Parametres de route")
  - [Parametres de requete](#query-parameters "Parametres de requete")
- Gardes de route
  - [Creer des gardes de route](#route-guards "Gardes de route")
  - [Cycle de vie NyRouteGuard](#nyroute-guard-lifecycle "Cycle de vie NyRouteGuard")
  - [Methodes d'aide des gardes](#guard-helper-methods "Methodes d'aide des gardes")
  - [Gardes parameterises](#parameterized-guards "Gardes parameterises")
  - [Piles de gardes](#guard-stacks "Piles de gardes")
  - [Gardes conditionnels](#conditional-guards "Gardes conditionnels")
- Groupes de routes
  - [Groupes de routes](#route-groups "Groupes de routes")
- [Liens profonds](#deep-linking "Liens profonds")
- [Avance](#advanced "Avance")



<div id="introduction"></div>

## Introduction

Les routes vous permettent de definir les differentes pages de votre application et de naviguer entre elles.

Utilisez les routes lorsque vous avez besoin de :
- Definir les pages disponibles dans votre application
- Naviguer les utilisateurs entre les ecrans
- Proteger des pages derriere une authentification
- Passer des donnees d'une page a une autre
- Gerer les liens profonds a partir d'URLs

Vous pouvez ajouter des routes dans le fichier `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **Astuce :** Vous pouvez creer vos routes manuellement ou utiliser l'outil CLI <a href="/docs/{{ $version }}/metro">Metro</a> pour les creer pour vous.

Voici un exemple de creation d'une page 'account' avec Metro.

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

Vous pouvez aussi avoir besoin de passer des donnees d'une vue a une autre. Dans {{ config('app.name') }}, c'est possible en utilisant le `NyStatefulWidget` (un widget avec etat integrant l'acces aux donnees de route). Nous approfondirons cela pour expliquer comment cela fonctionne.


<div id="adding-routes"></div>

## Ajouter des routes

C'est la maniere la plus simple d'ajouter de nouvelles routes a votre projet.

Executez la commande ci-dessous pour creer une nouvelle page.

```bash
metro make:page profile_page
```

Apres l'execution de la commande ci-dessus, un nouveau Widget nomme `ProfilePage` sera cree et ajoute a votre repertoire `resources/pages/`.
Il ajoutera egalement la nouvelle route a votre fichier `lib/routes/router.dart`.

Fichier : <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Naviguer vers des pages

Vous pouvez naviguer vers de nouvelles pages en utilisant le helper `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Route initiale

Dans vos routeurs, vous pouvez definir la premiere page qui doit se charger en utilisant la methode `.initialRoute()`.

Une fois que vous avez defini la route initiale, ce sera la premiere page chargee a l'ouverture de l'application.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### Route initiale conditionnelle

Vous pouvez egalement definir une route initiale conditionnelle en utilisant le parametre `when` :

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

### Naviguer vers la route initiale

Utilisez `routeToInitial()` pour naviguer vers la route initiale de l'application :

``` dart
void _goHome() {
    routeToInitial();
}
```

Cela naviguera vers la route marquee avec `.initialRoute()` et effacera la pile de navigation.

<div id="preview-route"></div>

## Route de previsualisation

Pendant le developpement, vous pouvez vouloir previsualiser rapidement une page specifique sans changer votre route initiale de maniere permanente. Utilisez `.previewRoute()` pour faire temporairement de n'importe quelle route la route initiale :

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

La methode `previewRoute()` :
- Remplace tout parametre `initialRoute()` et `authenticatedRoute()` existant
- Fait de la route specifiee la route initiale
- Utile pour tester rapidement des pages specifiques pendant le developpement

> **Attention :** N'oubliez pas de supprimer `.previewRoute()` avant de publier votre application !

<div id="authenticated-route"></div>

## Route authentifiee

Dans votre application, vous pouvez definir une route comme route initiale lorsqu'un utilisateur est authentifie.
Cela remplacera automatiquement la route initiale par defaut et sera la premiere page que l'utilisateur verra lorsqu'il se connectera.

Tout d'abord, votre utilisateur doit etre connecte en utilisant le helper `Auth.authenticate({...})`.

Maintenant, lorsqu'il ouvre l'application, la route que vous avez definie sera la page par defaut jusqu'a sa deconnexion.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### Route authentifiee conditionnelle

Vous pouvez egalement definir une route authentifiee conditionnelle :

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Naviguer vers la route authentifiee

Vous pouvez naviguer vers la page authentifiee en utilisant le helper `routeToAuthenticatedRoute()` :

``` dart
routeToAuthenticatedRoute();
```

**Voir aussi :** [Authentification](/docs/{{ $version }}/authentication) pour les details sur l'authentification des utilisateurs et la gestion des sessions.


<div id="unknown-route"></div>

## Route inconnue

Vous pouvez definir une route pour gerer les scenarios 404/page non trouvee en utilisant `.unknownRoute()` :

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Lorsqu'un utilisateur navigue vers une route qui n'existe pas, la page de route inconnue sera affichee.


<div id="route-guards"></div>

## Gardes de route

Les gardes de route protegent les pages contre les acces non autorises. Elles s'executent avant que la navigation ne se termine, vous permettant de rediriger les utilisateurs ou de bloquer l'acces selon des conditions.

Utilisez les gardes de route lorsque vous avez besoin de :
- Proteger des pages contre les utilisateurs non authentifies
- Verifier les permissions avant d'autoriser l'acces
- Rediriger les utilisateurs selon des conditions (ex. onboarding non termine)
- Enregistrer ou suivre les vues de pages

Pour creer une nouvelle garde de route, executez la commande ci-dessous.

``` bash
metro make:route_guard dashboard
```

Ensuite, ajoutez la nouvelle garde de route a votre route.

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

Vous pouvez egalement definir des gardes de route en utilisant la methode `addRouteGuard` :

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

## Cycle de vie NyRouteGuard

Dans la v7, les gardes de route utilisent la classe `NyRouteGuard` avec trois methodes de cycle de vie :

- **`onBefore(RouteContext context)`** - Appelee avant la navigation. Retournez `next()` pour continuer, `redirect()` pour aller ailleurs, ou `abort()` pour arreter.
- **`onAfter(RouteContext context)`** - Appelee apres une navigation reussie vers la route.

### Exemple de base

Fichier : **/routes/guards/dashboard_route_guard.dart**
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

La classe `RouteContext` donne acces aux informations de navigation :

| Propriete | Type | Description |
|-----------|------|-------------|
| `context` | `BuildContext?` | Le contexte de construction actuel |
| `data` | `dynamic` | Les donnees passees a la route |
| `queryParameters` | `Map<String, String>` | Les parametres de requete URL |
| `routeName` | `String` | Le nom/chemin de la route |
| `originalRouteName` | `String?` | Le nom original de la route avant les transformations |

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

## Methodes d'aide des gardes

### next()

Continuer vers la garde suivante ou vers la route :

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

Rediriger vers une route differente :

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

La methode `redirect()` accepte :

| Parametre | Type | Description |
|-----------|------|-------------|
| `path` | `Object` | Chemin de la route ou RouteView |
| `data` | `dynamic` | Donnees a passer a la route |
| `queryParameters` | `Map<String, dynamic>?` | Parametres de requete |
| `navigationType` | `NavigationType` | Type de navigation (par defaut : pushReplace) |
| `transitionType` | `TransitionType?` | Transition de page |
| `onPop` | `Function(dynamic)?` | Callback lorsque la route est depilee |

### abort()

Arreter la navigation sans rediriger :

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

Modifier les donnees passees aux gardes suivantes et a la route :

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Gardes parameterises

Utilisez `ParameterizedGuard` lorsque vous devez configurer le comportement de la garde par route :

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

## Piles de gardes

Composez plusieurs gardes en une seule garde reutilisable en utilisant `GuardStack` :

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

## Gardes conditionnels

Appliquez des gardes conditionnellement en fonction d'un predicat :

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Passer des donnees a une autre page

Dans cette section, nous montrerons comment vous pouvez passer des donnees d'un widget a un autre.

Depuis votre Widget, utilisez le helper `routeTo` et passez les `data` que vous souhaitez envoyer a la nouvelle page.

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

Plus d'exemples

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

## Groupes de routes

Les groupes de routes organisent les routes liees et appliquent des parametres partages. Ils sont utiles lorsque plusieurs routes necessitent les memes gardes, prefixe d'URL ou style de transition.

Utilisez les groupes de routes lorsque vous avez besoin de :
- Appliquer la meme garde de route a plusieurs pages
- Ajouter un prefixe d'URL a un ensemble de routes (ex. `/admin/...`)
- Definir la meme transition de page pour des routes liees

Vous pouvez definir un groupe de routes comme dans l'exemple ci-dessous.

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

#### Options disponibles pour les groupes de routes :

| Option | Type | Description |
|--------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Appliquer des gardes de route a toutes les routes du groupe |
| `prefix` | `String` | Ajouter un prefixe a tous les chemins de route du groupe |
| `transition_type` | `TransitionType` | Definir la transition pour toutes les routes du groupe |
| `transition` | `PageTransitionType` | Definir le type de transition de page (obsolete, utilisez transition_type) |
| `transition_settings` | `PageTransitionSettings` | Definir les parametres de transition |


<div id="route-parameters"></div>

## Utiliser les parametres de route

Lorsque vous creez une nouvelle page, vous pouvez mettre a jour la route pour accepter des parametres.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Maintenant, lorsque vous naviguez vers la page, vous pouvez passer le `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Vous pouvez acceder aux parametres dans la nouvelle page comme ceci.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Parametres de requete

Lors de la navigation vers une nouvelle page, vous pouvez egalement fournir des parametres de requete.

Voyons cela.

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

> **Note :** Tant que votre widget de page etend `NyStatefulWidget` et la classe `NyPage`, vous pouvez appeler `widget.queryParameters()` pour recuperer tous les parametres de requete du nom de la route.

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

> **Astuce :** Les parametres de requete doivent suivre le protocole HTTP, par ex. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Transitions de page

Vous pouvez ajouter des transitions lorsque vous naviguez d'une page en modifiant votre fichier `router.dart`.

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

### Transitions de page disponibles

#### Transitions de base
- **`TransitionType.fade()`** - Fait apparaitre la nouvelle page en fondu tout en faisant disparaitre l'ancienne
- **`TransitionType.theme()`** - Utilise le theme de transitions de page de l'application

#### Transitions directionnelles par glissement
- **`TransitionType.rightToLeft()`** - Glisse depuis le bord droit de l'ecran
- **`TransitionType.leftToRight()`** - Glisse depuis le bord gauche de l'ecran
- **`TransitionType.topToBottom()`** - Glisse depuis le bord superieur de l'ecran
- **`TransitionType.bottomToTop()`** - Glisse depuis le bord inferieur de l'ecran

#### Transitions de glissement avec fondu
- **`TransitionType.rightToLeftWithFade()`** - Glisse et apparait en fondu depuis le bord droit
- **`TransitionType.leftToRightWithFade()`** - Glisse et apparait en fondu depuis le bord gauche

#### Transitions de transformation
- **`TransitionType.scale(alignment: ...)`** - Mise a l'echelle depuis un point d'alignement specifie
- **`TransitionType.rotate(alignment: ...)`** - Rotation autour d'un point d'alignement specifie
- **`TransitionType.size(alignment: ...)`** - Agrandit depuis un point d'alignement specifie

#### Transitions jointes (necessite le widget actuel)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - La page actuelle sort a droite tandis que la nouvelle entre par la gauche
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - La page actuelle sort a gauche tandis que la nouvelle entre par la droite
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - La page actuelle sort vers le bas tandis que la nouvelle entre par le haut
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - La page actuelle sort vers le haut tandis que la nouvelle entre par le bas

#### Transitions Pop (necessite le widget actuel)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - La page actuelle sort a droite, la nouvelle reste en place
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - La page actuelle sort a gauche, la nouvelle reste en place
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - La page actuelle sort vers le bas, la nouvelle reste en place
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - La page actuelle sort vers le haut, la nouvelle reste en place

#### Transitions Material Design a axe partage
- **`TransitionType.sharedAxisHorizontal()`** - Transition de glissement horizontal et fondu
- **`TransitionType.sharedAxisVertical()`** - Transition de glissement vertical et fondu
- **`TransitionType.sharedAxisScale()`** - Transition de mise a l'echelle et fondu

#### Parametres de personnalisation
Chaque transition accepte les parametres optionnels suivants :

| Parametre | Description | Defaut |
|-----------|-------------|--------|
| `curve` | Courbe d'animation | Courbes specifiques a la plateforme |
| `duration` | Duree de l'animation | Durees specifiques a la plateforme |
| `reverseDuration` | Duree de l'animation inverse | Identique a la duree |
| `fullscreenDialog` | Si la route est un dialogue plein ecran | `false` |
| `opaque` | Si la route est opaque | `false` |


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

## Types de navigation

Lors de la navigation, vous pouvez specifier l'un des types suivants si vous utilisez le helper `routeTo`.

| Type | Description |
|------|-------------|
| `NavigationType.push` | Empiler une nouvelle page sur la pile de routes de votre application |
| `NavigationType.pushReplace` | Remplacer la route actuelle, en supprimant la route precedente une fois que la nouvelle est terminee |
| `NavigationType.popAndPushNamed` | Depiler la route actuelle du navigateur et empiler une route nommee a sa place |
| `NavigationType.pushAndRemoveUntil` | Empiler et supprimer les routes jusqu'a ce que le predicat retourne true |
| `NavigationType.pushAndForgetAll` | Naviguer vers une nouvelle page et supprimer toutes les autres pages de la pile de routes |

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

## Naviguer en arriere

Une fois sur la nouvelle page, vous pouvez utiliser le helper `pop()` pour revenir a la page existante.

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

Si vous souhaitez retourner une valeur au widget precedent, fournissez un `result` comme dans l'exemple ci-dessous.

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

## Navigation conditionnelle

Utilisez `routeIf()` pour naviguer uniquement lorsqu'une condition est remplie :

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

Si la condition est `false`, aucune navigation ne se produit.


<div id="route-history"></div>

## Historique des routes

Dans {{ config('app.name') }}, vous pouvez acceder aux informations de l'historique des routes en utilisant les helpers ci-dessous.

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

## Mettre a jour la pile de routes

Vous pouvez mettre a jour la pile de navigation par programmation en utilisant `NyNavigator.updateStack()` :

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

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `routes` | `List<String>` | requis | Liste des chemins de route vers lesquels naviguer |
| `replace` | `bool` | `true` | Indique s'il faut remplacer la pile actuelle |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Donnees a passer a des routes specifiques |

C'est utile pour :
- Les scenarios de liens profonds
- La restauration de l'etat de navigation
- La construction de flux de navigation complexes


<div id="deep-linking"></div>

## Liens profonds

Les liens profonds permettent aux utilisateurs de naviguer directement vers un contenu specifique dans votre application en utilisant des URLs. C'est utile pour :
- Partager des liens directs vers un contenu specifique de l'application
- Les campagnes marketing ciblant des fonctionnalites specifiques de l'application
- Gerer les notifications qui doivent ouvrir des ecrans specifiques
- Les transitions transparentes du web vers l'application

## Configuration

Avant d'implementer les liens profonds dans votre application, assurez-vous que votre projet est correctement configure :

### 1. Configuration de la plateforme

**iOS** : Configurez les liens universels dans votre projet Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Guide de configuration des liens universels Flutter</a>

**Android** : Configurez les liens d'application dans votre AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Guide de configuration des liens d'application Flutter</a>

### 2. Definir vos routes

Toutes les routes qui doivent etre accessibles via les liens profonds doivent etre enregistrees dans la configuration de votre routeur :

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

## Utiliser les liens profonds

Une fois configure, votre application peut gerer les URLs entrantes dans differents formats :

### Liens profonds de base

Navigation simple vers des pages specifiques :

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

Pour declencher ces navigations par programmation dans votre application :

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Parametres de chemin

Pour les routes qui necessitent des donnees dynamiques dans le chemin :

#### Definition de la route

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

#### Format d'URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Navigation programmatique

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Parametres de requete

Pour les parametres optionnels ou lorsque plusieurs valeurs dynamiques sont necessaires :

#### Format d'URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Acceder aux parametres de requete

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

#### Navigation programmatique avec parametres de requete

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Gerer les liens profonds

Vous pouvez gerer les evenements de liens profonds dans votre `RouteProvider` :

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

### Tester les liens profonds

Pour le developpement et les tests, vous pouvez simuler l'activation des liens profonds en utilisant ADB (Android) ou xcrun (iOS) :

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Conseils de debogage

- Affichez tous les parametres dans votre methode init pour verifier l'analyse correcte
- Testez differents formats d'URL pour vous assurer que votre application les gere correctement
- N'oubliez pas que les parametres de requete sont toujours recus sous forme de strings, convertissez-les au type approprie si necessaire

---

## Patterns courants

### Conversion de type des parametres

Comme tous les parametres d'URL sont passes sous forme de strings, vous devrez souvent les convertir :

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Parametres optionnels

Gerez les cas ou les parametres peuvent etre manquants :

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

## Avance

### Verifier si une route existe

Vous pouvez verifier si une route est enregistree dans votre routeur :

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Methodes NyRouter

La classe `NyRouter` fournit plusieurs methodes utiles :

| Methode | Description |
|---------|-------------|
| `getRegisteredRouteNames()` | Obtenir tous les noms de routes enregistrees sous forme de liste |
| `getRegisteredRoutes()` | Obtenir toutes les routes enregistrees sous forme de map |
| `containsRoutes(routes)` | Verifier si le routeur contient toutes les routes specifiees |
| `getInitialRouteName()` | Obtenir le nom de la route initiale |
| `getAuthRouteName()` | Obtenir le nom de la route authentifiee |
| `getUnknownRouteName()` | Obtenir le nom de la route inconnue/404 |

### Obtenir les arguments de route

Vous pouvez obtenir les arguments de route en utilisant `NyRouter.args<T>()` :

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

### NyArgument et NyQueryParameters

Les donnees passees entre les routes sont encapsulees dans ces classes :

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
