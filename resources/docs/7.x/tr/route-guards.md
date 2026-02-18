# Route Guards

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Route Guard Oluşturma](#creating-a-route-guard "Route Guard Oluşturma")
- [Guard Yaşam Döngüsü](#guard-lifecycle "Guard Yaşam Döngüsü")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Guard Eylemleri](#guard-actions "Guard Eylemleri")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Route'lara Guard Uygulama](#applying-guards "Route'lara Guard Uygulama")
- [Grup Guard'ları](#group-guards "Grup Guard'ları")
- [Guard Kompozisyonu](#guard-composition "Guard Kompozisyonu")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Örnekler](#examples "Örnekler")

<div id="introduction"></div>

## Giriş

Route guard'lar, {{ config('app.name') }} içinde **navigasyon için middleware** sağlar. Rota geçişlerini yakalar ve bir kullanıcının sayfaya erişip erişemeyeceğini, başka bir yere yönlendirilip yönlendirilmeyeceğini veya rotaya aktarılan verilerin değiştirilip değiştirilmeyeceğini kontrol etmenize olanak tanır.

Yaygın kullanım senaryoları şunlardır:
- **Kimlik doğrulama kontrolleri** -- kimliği doğrulanmamış kullanıcıları giriş sayfasına yönlendirme
- **Rol tabanlı erişim** -- sayfaları yönetici kullanıcılarla sınırlama
- **Veri doğrulama** -- navigasyondan önce gerekli verilerin mevcut olduğundan emin olma
- **Veri zenginleştirme** -- rotaya ek veri ekleme

Guard'lar navigasyon gerçekleşmeden önce **sırayla** yürütülür. Herhangi bir guard `handled` döndürürse, navigasyon durur (yönlendirme veya iptal ile).

<div id="creating-a-route-guard"></div>

## Route Guard Oluşturma

Metro CLI kullanarak bir route guard oluşturun:

``` bash
metro make:route_guard auth
```

Bu, bir guard dosyası oluşturur:

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

## Guard Yaşam Döngüsü

Her route guard'ın üç yaşam döngüsü metodu vardır:

<div id="on-before"></div>

### onBefore

Navigasyon gerçekleşmeden **önce** çağrılır. Koşulları kontrol ettiğiniz ve navigasyona izin verme, yönlendirme veya iptal etme kararını aldığınız yerdir.

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

Dönüş değerleri:
- `next()` -- zincirdeki bir sonraki guard'a devam et veya rotaya yönel
- `redirect(path)` -- farklı bir rotaya yönlendir
- `abort()` -- navigasyonu tamamen iptal et

<div id="on-after"></div>

### onAfter

Başarılı navigasyondan **sonra** çağrılır. Analitik, günlükleme veya navigasyon sonrası yan etkiler için kullanın.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Kullanıcı bir rotadan **ayrılırken** çağrılır. Kullanıcının ayrılmasını engellemek için `false` döndürün.

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

`RouteContext` nesnesi tüm guard yaşam döngüsü metotlarına iletilir ve navigasyon hakkında bilgi içerir:

| Özellik | Tür | Açıklama |
|----------|------|-------------|
| `context` | `BuildContext?` | Mevcut build context |
| `data` | `dynamic` | Rotaya aktarılan veri |
| `queryParameters` | `Map<String, String>` | URL sorgu parametreleri |
| `routeName` | `String` | Hedef rotanın adı/yolu |
| `originalRouteName` | `String?` | Dönüşümlerden önceki orijinal rota adı |

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

### Route Context'i Dönüştürme

Farklı veriyle bir kopya oluşturun:

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

## Guard Eylemleri

<div id="next"></div>

### next

Zincirdeki bir sonraki guard'a devam et veya bu son guard ise rotaya yönel:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Kullanıcıyı farklı bir rotaya yönlendir:

``` dart
return redirect(LoginPage.path);
```

Ek seçeneklerle:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `path` | `Object` | zorunlu | Rota yolu dizesi veya RouteView |
| `data` | `dynamic` | null | Yönlendirme rotasına aktarılacak veri |
| `queryParameters` | `Map<String, dynamic>?` | null | Sorgu parametreleri |
| `navigationType` | `NavigationType` | `pushReplace` | Navigasyon yöntemi |
| `result` | `dynamic` | null | Döndürülecek sonuç |
| `removeUntilPredicate` | `Function?` | null | Rota kaldırma koşulu |
| `transitionType` | `TransitionType?` | null | Sayfa geçiş türü |
| `onPop` | `Function(dynamic)?` | null | Pop'ta geri çağırma |

<div id="abort"></div>

### abort

Yönlendirme yapmadan navigasyonu iptal et. Kullanıcı mevcut sayfasında kalır:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Sonraki guard'lara ve hedef rotaya aktarılacak verileri değiştirin:

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

## Route'lara Guard Uygulama

Router dosyanızda bireysel rotalara guard ekleyin:

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

## Grup Guard'ları

Rota grupları kullanarak birden fazla rotaya aynı anda guard uygulayın:

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

## Guard Kompozisyonu

{{ config('app.name') }}, yeniden kullanılabilir kalıplar için guard'ları birleştirme araçları sağlar.

<div id="guard-stack"></div>

### GuardStack

Birden fazla guard'ı tek bir yeniden kullanılabilir guard olarak birleştirin:

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

`GuardStack` guard'ları sırayla yürütür. Herhangi bir guard `handled` döndürürse, kalan guard'lar atlanır.

<div id="conditional-guard"></div>

### ConditionalGuard

Bir guard'ı yalnızca belirli bir koşul doğru olduğunda uygulayın:

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

Koşul `false` döndürürse, guard atlanır ve navigasyon devam eder.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Yapılandırma parametreleri alan guard'lar oluşturun:

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

## Örnekler

### Kimlik Doğrulama Guard'ı

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

### Parametreli Abonelik Guard'ı

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

### Günlükleme Guard'ı

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
