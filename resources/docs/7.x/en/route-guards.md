# Route Guards

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creating a Route Guard](#creating-a-route-guard "Creating a Route Guard")
- [Guard Lifecycle](#guard-lifecycle "Guard Lifecycle")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Guard Actions](#guard-actions "Guard Actions")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Applying Guards to Routes](#applying-guards "Applying Guards to Routes")
- [Group Guards](#group-guards "Group Guards")
- [Guard Composition](#guard-composition "Guard Composition")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

Route guards provide **middleware for navigation** in {{ config('app.name') }}. They intercept route transitions and allow you to control whether a user can access a page, redirect them elsewhere, or modify the data passed to a route.

Common use cases include:
- **Authentication checks** -- redirect unauthenticated users to a login page
- **Role-based access** -- restrict pages to admin users
- **Data validation** -- ensure required data exists before navigation
- **Data enrichment** -- attach additional data to a route

Guards are executed **in order** before navigation occurs. If any guard returns `handled`, navigation stops (either redirecting or aborting).

<div id="creating-a-route-guard"></div>

## Creating a Route Guard

Create a route guard using the Metro CLI:

``` bash
metro make:route_guard auth
```

This generates a guard file:

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

## Guard Lifecycle

Every route guard has three lifecycle methods:

<div id="on-before"></div>

### onBefore

Called **before** navigation occurs. This is where you check conditions and decide whether to allow, redirect, or abort navigation.

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

Return values:
- `next()` -- continue to the next guard or navigate to the route
- `redirect(path)` -- redirect to a different route
- `abort()` -- cancel navigation entirely

<div id="on-after"></div>

### onAfter

Called **after** successful navigation. Use this for analytics, logging, or post-navigation side effects.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Called when the user is **leaving** a route. Return `false` to prevent the user from leaving.

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

The `RouteContext` object is passed to all guard lifecycle methods and contains information about the navigation:

| Property | Type | Description |
|----------|------|-------------|
| `context` | `BuildContext?` | Current build context |
| `data` | `dynamic` | Data passed to the route |
| `queryParameters` | `Map<String, String>` | URL query parameters |
| `routeName` | `String` | Name/path of the target route |
| `originalRouteName` | `String?` | Original route name before transformations |

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

### Transforming Route Context

Create a copy with different data:

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

## Guard Actions

<div id="next"></div>

### next

Continue to the next guard in the chain, or navigate to the route if this is the last guard:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Redirect the user to a different route:

``` dart
return redirect(LoginPage.path);
```

With additional options:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `path` | `Object` | required | Route path string or RouteView |
| `data` | `dynamic` | null | Data to pass to the redirect route |
| `queryParameters` | `Map<String, dynamic>?` | null | Query parameters |
| `navigationType` | `NavigationType` | `pushReplace` | Navigation method |
| `result` | `dynamic` | null | Result to return |
| `removeUntilPredicate` | `Function?` | null | Route removal predicate |
| `transitionType` | `TransitionType?` | null | Page transition type |
| `onPop` | `Function(dynamic)?` | null | Callback on pop |

<div id="abort"></div>

### abort

Cancel navigation without redirecting. The user stays on their current page:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Modify the data that will be passed to subsequent guards and the target route:

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

## Applying Guards to Routes

Add guards to individual routes in your router file:

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

## Group Guards

Apply guards to multiple routes at once using route groups:

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

## Guard Composition

{{ config('app.name') }} provides tools to compose guards together for reusable patterns.

<div id="guard-stack"></div>

### GuardStack

Combine multiple guards into a single reusable guard:

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

`GuardStack` executes guards in order. If any guard returns `handled`, the remaining guards are skipped.

<div id="conditional-guard"></div>

### ConditionalGuard

Apply a guard only when a condition is true:

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

If the condition returns `false`, the guard is skipped and navigation continues.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Create guards that accept configuration parameters:

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

## Examples

### Authentication Guard

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

### Subscription Guard with Parameters

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

### Logging Guard

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
