# Route Guards

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Membuat Route Guard](#creating-a-route-guard "Membuat Route Guard")
- [Siklus Hidup Guard](#guard-lifecycle "Siklus Hidup Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Aksi Guard](#guard-actions "Aksi Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Menerapkan Guard ke Route](#applying-guards "Menerapkan Guard ke Route")
- [Guard Grup](#group-guards "Guard Grup")
- [Komposisi Guard](#guard-composition "Komposisi Guard")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Contoh](#examples "Contoh")

<div id="introduction"></div>

## Pengantar

Route guard menyediakan **middleware untuk navigasi** di {{ config('app.name') }}. Route guard mengintersepsi transisi rute dan memungkinkan Anda mengontrol apakah pengguna dapat mengakses halaman, mengarahkan mereka ke tempat lain, atau memodifikasi data yang diteruskan ke rute.

Kasus penggunaan umum meliputi:
- **Pemeriksaan autentikasi** -- mengarahkan pengguna yang belum terautentikasi ke halaman login
- **Akses berbasis peran** -- membatasi halaman hanya untuk pengguna admin
- **Validasi data** -- memastikan data yang diperlukan ada sebelum navigasi
- **Pengayaan data** -- melampirkan data tambahan ke rute

Guard dieksekusi **secara berurutan** sebelum navigasi terjadi. Jika guard mana pun mengembalikan `handled`, navigasi berhenti (baik dengan mengarahkan ulang atau membatalkan).

<div id="creating-a-route-guard"></div>

## Membuat Route Guard

Buat route guard menggunakan Metro CLI:

``` bash
metro make:route_guard auth
```

Ini menghasilkan file guard:

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

## Siklus Hidup Guard

Setiap route guard memiliki tiga metode siklus hidup:

<div id="on-before"></div>

### onBefore

Dipanggil **sebelum** navigasi terjadi. Di sinilah Anda memeriksa kondisi dan memutuskan apakah akan mengizinkan, mengarahkan ulang, atau membatalkan navigasi.

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

Nilai kembalian:
- `next()` -- lanjutkan ke guard berikutnya atau navigasi ke rute
- `redirect(path)` -- arahkan ulang ke rute yang berbeda
- `abort()` -- batalkan navigasi sepenuhnya

<div id="on-after"></div>

### onAfter

Dipanggil **setelah** navigasi berhasil. Gunakan ini untuk analitik, pencatatan, atau efek samping pasca-navigasi.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Dipanggil ketika pengguna **meninggalkan** rute. Kembalikan `false` untuk mencegah pengguna meninggalkan halaman.

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

Objek `RouteContext` diteruskan ke semua metode siklus hidup guard dan berisi informasi tentang navigasi:

| Properti | Tipe | Deskripsi |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context saat ini |
| `data` | `dynamic` | Data yang diteruskan ke rute |
| `queryParameters` | `Map<String, String>` | Parameter query URL |
| `routeName` | `String` | Nama/path rute tujuan |
| `originalRouteName` | `String?` | Nama rute asli sebelum transformasi |

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

### Mentransformasi Route Context

Membuat salinan dengan data yang berbeda:

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

## Aksi Guard

<div id="next"></div>

### next

Lanjutkan ke guard berikutnya dalam rantai, atau navigasi ke rute jika ini adalah guard terakhir:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Arahkan ulang pengguna ke rute yang berbeda:

``` dart
return redirect(LoginPage.path);
```

Dengan opsi tambahan:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `path` | `Object` | required | String path rute atau RouteView |
| `data` | `dynamic` | null | Data yang diteruskan ke rute redirect |
| `queryParameters` | `Map<String, dynamic>?` | null | Parameter query |
| `navigationType` | `NavigationType` | `pushReplace` | Metode navigasi |
| `result` | `dynamic` | null | Hasil yang dikembalikan |
| `removeUntilPredicate` | `Function?` | null | Predikat penghapusan rute |
| `transitionType` | `TransitionType?` | null | Tipe transisi halaman |
| `onPop` | `Function(dynamic)?` | null | Callback saat pop |

<div id="abort"></div>

### abort

Batalkan navigasi tanpa mengarahkan ulang. Pengguna tetap di halaman saat ini:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Memodifikasi data yang akan diteruskan ke guard berikutnya dan rute tujuan:

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

## Menerapkan Guard ke Route

Tambahkan guard ke rute individual di file router Anda:

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

## Guard Grup

Terapkan guard ke beberapa rute sekaligus menggunakan grup rute:

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

## Komposisi Guard

{{ config('app.name') }} menyediakan alat untuk menyusun guard bersama-sama menjadi pola yang dapat digunakan kembali.

<div id="guard-stack"></div>

### GuardStack

Gabungkan beberapa guard menjadi satu guard yang dapat digunakan kembali:

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

`GuardStack` mengeksekusi guard secara berurutan. Jika ada guard yang mengembalikan `handled`, guard yang tersisa akan dilewati.

<div id="conditional-guard"></div>

### ConditionalGuard

Terapkan guard hanya ketika kondisi bernilai true:

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

Jika kondisi mengembalikan `false`, guard akan dilewati dan navigasi berlanjut.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Buat guard yang menerima parameter konfigurasi:

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

## Contoh

### Guard Autentikasi

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

### Guard Langganan dengan Parameter

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

### Guard Pencatatan

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
