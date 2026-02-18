# Router

---

<a name="section-1"></a>

- [Pendahuluan](#introduction "Pendahuluan")
- Dasar-Dasar
  - [Menambahkan rute](#adding-routes "Menambahkan rute")
  - [Navigasi ke halaman](#navigating-to-pages "Navigasi ke halaman")
  - [Rute awal](#initial-route "Rute awal")
  - [Rute Pratinjau](#preview-route "Rute Pratinjau")
  - [Rute terautentikasi](#authenticated-route "Rute terautentikasi")
  - [Rute Tidak Dikenal](#unknown-route "Rute Tidak Dikenal")
- Mengirim data ke halaman lain
  - [Mengirim data ke halaman lain](#passing-data-to-another-page "Mengirim data ke halaman lain")
- Navigasi
  - [Tipe navigasi](#navigation-types "Tipe navigasi")
  - [Navigasi kembali](#navigating-back "Navigasi kembali")
  - [Navigasi Bersyarat](#conditional-navigation "Navigasi Bersyarat")
  - [Transisi halaman](#page-transitions "Transisi halaman")
  - [Riwayat Rute](#route-history "Riwayat Rute")
  - [Memperbarui Route Stack](#update-route-stack "Memperbarui Route Stack")
- Parameter rute
  - [Menggunakan Parameter Rute](#route-parameters "Menggunakan Parameter Rute")
  - [Query Parameters](#query-parameters "Query Parameters")
- Route Guards
  - [Membuat Route Guards](#route-guards "Membuat Route Guards")
  - [Siklus Hidup NyRouteGuard](#nyroute-guard-lifecycle "Siklus Hidup NyRouteGuard")
  - [Metode Bantuan Guard](#guard-helper-methods "Metode Bantuan Guard")
  - [Guard Berparameter](#parameterized-guards "Guard Berparameter")
  - [Guard Stacks](#guard-stacks "Guard Stacks")
  - [Guard Bersyarat](#conditional-guards "Guard Bersyarat")
- Grup Rute
  - [Grup Rute](#route-groups "Grup Rute")
- [Deep linking](#deep-linking "Deep linking")
- [Lanjutan](#advanced "Lanjutan")



<div id="introduction"></div>

## Pendahuluan

Rute memungkinkan Anda mendefinisikan halaman-halaman berbeda di aplikasi Anda dan menavigasi di antaranya.

Gunakan rute ketika Anda perlu:
- Mendefinisikan halaman yang tersedia di aplikasi Anda
- Menavigasi pengguna antar layar
- Melindungi halaman di balik autentikasi
- Mengirim data dari satu halaman ke halaman lain
- Menangani deep link dari URL

Anda dapat menambahkan rute di dalam file `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **Tip:** Anda dapat membuat rute secara manual atau menggunakan alat CLI <a href="/docs/{{ $version }}/metro">Metro</a> untuk membuatnya secara otomatis.

Berikut adalah contoh membuat halaman 'account' menggunakan Metro.

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

Anda mungkin juga perlu mengirim data dari satu tampilan ke tampilan lainnya. Di {{ config('app.name') }}, hal ini dimungkinkan menggunakan `NyStatefulWidget` (widget stateful dengan akses data rute bawaan). Kita akan membahas lebih dalam untuk menjelaskan cara kerjanya.


<div id="adding-routes"></div>

## Menambahkan rute

Ini adalah cara termudah untuk menambahkan rute baru ke proyek Anda.

Jalankan perintah di bawah ini untuk membuat halaman baru.

```bash
metro make:page profile_page
```

Setelah menjalankan perintah di atas, akan dibuat Widget baru bernama `ProfilePage` dan ditambahkan ke direktori `resources/pages/` Anda.
Perintah ini juga akan menambahkan rute baru ke file `lib/routes/router.dart` Anda.

File: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Navigasi ke halaman

Anda dapat menavigasi ke halaman baru menggunakan helper `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Rute awal

Di router Anda, Anda dapat mendefinisikan halaman pertama yang harus dimuat menggunakan metode `.initialRoute()`.

Setelah Anda menetapkan rute awal, halaman tersebut akan menjadi halaman pertama yang dimuat saat Anda membuka aplikasi.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### Rute Awal Bersyarat

Anda juga dapat menetapkan rute awal bersyarat menggunakan parameter `when`:

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

### Navigasi ke Rute Awal

Gunakan `routeToInitial()` untuk menavigasi ke rute awal aplikasi:

``` dart
void _goHome() {
    routeToInitial();
}
```

Ini akan menavigasi ke rute yang ditandai dengan `.initialRoute()` dan menghapus tumpukan navigasi.

<div id="preview-route"></div>

## Rute Pratinjau

Selama pengembangan, Anda mungkin ingin melihat pratinjau halaman tertentu dengan cepat tanpa mengubah rute awal secara permanen. Gunakan `.previewRoute()` untuk sementara menjadikan rute mana pun sebagai rute awal:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

Metode `previewRoute()`:
- Menggantikan pengaturan `initialRoute()` dan `authenticatedRoute()` yang ada
- Menjadikan rute yang ditentukan sebagai rute awal
- Berguna untuk menguji halaman tertentu dengan cepat selama pengembangan

> **Peringatan:** Ingat untuk menghapus `.previewRoute()` sebelum merilis aplikasi Anda!

<div id="authenticated-route"></div>

## Rute Terautentikasi

Di aplikasi Anda, Anda dapat mendefinisikan rute untuk menjadi rute awal ketika pengguna sudah terautentikasi.
Ini akan secara otomatis menggantikan rute awal default dan menjadi halaman pertama yang dilihat pengguna saat mereka masuk.

Pertama, pengguna Anda harus masuk menggunakan helper `Auth.authenticate({...})`.

Sekarang, saat mereka membuka aplikasi, rute yang Anda definisikan akan menjadi halaman default sampai mereka keluar.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### Rute Terautentikasi Bersyarat

Anda juga dapat menetapkan rute terautentikasi bersyarat:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Navigasi ke Rute Terautentikasi

Anda dapat menavigasi ke halaman terautentikasi menggunakan helper `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Lihat juga:** [Autentikasi](/docs/{{ $version }}/authentication) untuk detail tentang mengautentikasi pengguna dan mengelola sesi.


<div id="unknown-route"></div>

## Rute Tidak Dikenal

Anda dapat mendefinisikan rute untuk menangani skenario 404/tidak ditemukan menggunakan `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Ketika pengguna menavigasi ke rute yang tidak ada, mereka akan ditampilkan halaman rute tidak dikenal.


<div id="route-guards"></div>

## Route guards

Route guards melindungi halaman dari akses yang tidak sah. Mereka berjalan sebelum navigasi selesai, memungkinkan Anda mengalihkan pengguna atau memblokir akses berdasarkan kondisi.

Gunakan route guards ketika Anda perlu:
- Melindungi halaman dari pengguna yang belum terautentikasi
- Memeriksa izin sebelum mengizinkan akses
- Mengalihkan pengguna berdasarkan kondisi (misalnya, onboarding belum selesai)
- Mencatat atau melacak tampilan halaman

Untuk membuat Route Guard baru, jalankan perintah di bawah ini.

``` bash
metro make:route_guard dashboard
```

Selanjutnya, tambahkan Route Guard baru ke rute Anda.

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

Anda juga dapat mengatur route guards menggunakan metode `addRouteGuard`:

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

## Siklus Hidup NyRouteGuard

Di v7, route guards menggunakan kelas `NyRouteGuard` dengan tiga metode siklus hidup:

- **`onBefore(RouteContext context)`** - Dipanggil sebelum navigasi. Kembalikan `next()` untuk melanjutkan, `redirect()` untuk pergi ke tempat lain, atau `abort()` untuk menghentikan.
- **`onAfter(RouteContext context)`** - Dipanggil setelah navigasi berhasil ke rute.

### Contoh Dasar

File: **/routes/guards/dashboard_route_guard.dart**
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

Kelas `RouteContext` menyediakan akses ke informasi navigasi:

| Properti | Tipe | Deskripsi |
|----------|------|-----------|
| `context` | `BuildContext?` | Build context saat ini |
| `data` | `dynamic` | Data yang dikirim ke rute |
| `queryParameters` | `Map<String, String>` | Parameter query URL |
| `routeName` | `String` | Nama/path rute |
| `originalRouteName` | `String?` | Nama rute asli sebelum transformasi |

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

## Metode Bantuan Guard

### next()

Lanjutkan ke guard berikutnya atau ke rute:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

Arahkan ulang ke rute yang berbeda:

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

Metode `redirect()` menerima:

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `path` | `Object` | Path rute atau RouteView |
| `data` | `dynamic` | Data yang dikirim ke rute |
| `queryParameters` | `Map<String, dynamic>?` | Parameter query |
| `navigationType` | `NavigationType` | Tipe navigasi (default: pushReplace) |
| `transitionType` | `TransitionType?` | Transisi halaman |
| `onPop` | `Function(dynamic)?` | Callback saat rute di-pop |

### abort()

Hentikan navigasi tanpa pengalihan:

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

Modifikasi data yang dikirim ke guard berikutnya dan rute:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Guard Berparameter

Gunakan `ParameterizedGuard` ketika Anda perlu mengonfigurasi perilaku guard per rute:

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

Gabungkan beberapa guard menjadi satu guard yang dapat digunakan kembali menggunakan `GuardStack`:

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

## Guard Bersyarat

Terapkan guard secara bersyarat berdasarkan predikat:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Mengirim data ke halaman lain

Di bagian ini, kami akan menunjukkan bagaimana Anda dapat mengirim data dari satu widget ke widget lainnya.

Dari Widget Anda, gunakan helper `routeTo` dan kirim `data` yang ingin Anda kirim ke halaman baru.

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

Contoh lainnya

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

## Grup Rute

Grup rute mengorganisir rute-rute terkait dan menerapkan pengaturan bersama. Mereka berguna ketika beberapa rute membutuhkan guard, prefix URL, atau gaya transisi yang sama.

Gunakan grup rute ketika Anda perlu:
- Menerapkan route guard yang sama ke beberapa halaman
- Menambahkan prefix URL ke sekumpulan rute (misalnya, `/admin/...`)
- Mengatur transisi halaman yang sama untuk rute-rute terkait

Anda dapat mendefinisikan grup rute seperti pada contoh di bawah ini.

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

#### Pengaturan opsional untuk grup rute adalah:

| Pengaturan | Tipe | Deskripsi |
|---------|------|-----------|
| `route_guards` | `List<RouteGuard>` | Terapkan route guards ke semua rute dalam grup |
| `prefix` | `String` | Tambahkan prefix ke semua path rute dalam grup |
| `transition_type` | `TransitionType` | Atur transisi untuk semua rute dalam grup |
| `transition` | `PageTransitionType` | Atur tipe transisi halaman (tidak digunakan lagi, gunakan transition_type) |
| `transition_settings` | `PageTransitionSettings` | Atur pengaturan transisi |


<div id="route-parameters"></div>

## Menggunakan Parameter Rute

Ketika Anda membuat halaman baru, Anda dapat memperbarui rute untuk menerima parameter.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Sekarang, saat Anda menavigasi ke halaman tersebut, Anda dapat mengirim `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Anda dapat mengakses parameter di halaman baru seperti ini.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Query Parameters

Saat menavigasi ke halaman baru, Anda juga dapat memberikan query parameters.

Mari kita lihat.

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

> **Catatan:** Selama widget halaman Anda meng-extend `NyStatefulWidget` dan kelas `NyPage`, maka Anda dapat memanggil `widget.queryParameters()` untuk mengambil semua query parameters dari nama rute.

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

> **Tip:** Query parameters harus mengikuti protokol HTTP, Contoh: /account?userId=1&tab=2


<div id="page-transitions"></div>

## Transisi Halaman

Anda dapat menambahkan transisi saat menavigasi dari satu halaman dengan memodifikasi file `router.dart` Anda.

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

### Transisi Halaman yang Tersedia

#### Transisi Dasar
- **`TransitionType.fade()`** - Memfadekan halaman baru masuk sambil memfadekan halaman lama keluar
- **`TransitionType.theme()`** - Menggunakan tema transisi halaman dari tema aplikasi

#### Transisi Geser Arah
- **`TransitionType.rightToLeft()`** - Menggeser dari tepi kanan layar
- **`TransitionType.leftToRight()`** - Menggeser dari tepi kiri layar
- **`TransitionType.topToBottom()`** - Menggeser dari tepi atas layar
- **`TransitionType.bottomToTop()`** - Menggeser dari tepi bawah layar

#### Transisi Geser dengan Fade
- **`TransitionType.rightToLeftWithFade()`** - Menggeser dan memfadekan dari tepi kanan
- **`TransitionType.leftToRightWithFade()`** - Menggeser dan memfadekan dari tepi kiri

#### Transisi Transformasi
- **`TransitionType.scale(alignment: ...)`** - Menskalakan dari titik alignment yang ditentukan
- **`TransitionType.rotate(alignment: ...)`** - Memutar di sekitar titik alignment yang ditentukan
- **`TransitionType.size(alignment: ...)`** - Membesar dari titik alignment yang ditentukan

#### Transisi Joined (Memerlukan widget saat ini)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Halaman saat ini keluar ke kanan sementara halaman baru masuk dari kiri
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Halaman saat ini keluar ke kiri sementara halaman baru masuk dari kanan
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Halaman saat ini keluar ke bawah sementara halaman baru masuk dari atas
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Halaman saat ini keluar ke atas sementara halaman baru masuk dari bawah

#### Transisi Pop (Memerlukan widget saat ini)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Halaman saat ini keluar ke kanan, halaman baru tetap di tempat
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Halaman saat ini keluar ke kiri, halaman baru tetap di tempat
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Halaman saat ini keluar ke bawah, halaman baru tetap di tempat
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Halaman saat ini keluar ke atas, halaman baru tetap di tempat

#### Transisi Material Design Shared Axis
- **`TransitionType.sharedAxisHorizontal()`** - Transisi geser dan fade horizontal
- **`TransitionType.sharedAxisVertical()`** - Transisi geser dan fade vertikal
- **`TransitionType.sharedAxisScale()`** - Transisi skala dan fade

#### Parameter Kustomisasi
Setiap transisi menerima parameter opsional berikut:

| Parameter | Deskripsi | Default |
|-----------|-----------|---------|
| `curve` | Kurva animasi | Kurva spesifik platform |
| `duration` | Durasi animasi | Durasi spesifik platform |
| `reverseDuration` | Durasi animasi mundur | Sama dengan duration |
| `fullscreenDialog` | Apakah rute merupakan dialog fullscreen | `false` |
| `opaque` | Apakah rute bersifat opaque | `false` |


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

## Tipe Navigasi

Saat menavigasi, Anda dapat menentukan salah satu dari berikut ini jika Anda menggunakan helper `routeTo`.

| Tipe | Deskripsi |
|------|-----------|
| `NavigationType.push` | Mendorong halaman baru ke tumpukan rute aplikasi Anda |
| `NavigationType.pushReplace` | Mengganti rute saat ini, menghapus rute sebelumnya setelah rute baru selesai |
| `NavigationType.popAndPushNamed` | Menghapus rute saat ini dari navigator dan mendorong rute bernama sebagai gantinya |
| `NavigationType.pushAndRemoveUntil` | Mendorong dan menghapus rute sampai predikat bernilai true |
| `NavigationType.pushAndForgetAll` | Mendorong ke halaman baru dan menghapus semua halaman lain pada tumpukan rute |

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

## Navigasi kembali

Setelah Anda berada di halaman baru, Anda dapat menggunakan helper `pop()` untuk kembali ke halaman sebelumnya.

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

Jika Anda ingin mengembalikan nilai ke widget sebelumnya, berikan `result` seperti pada contoh di bawah ini.

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

## Navigasi Bersyarat

Gunakan `routeIf()` untuk menavigasi hanya ketika suatu kondisi terpenuhi:

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

Jika kondisinya bernilai `false`, tidak ada navigasi yang terjadi.


<div id="route-history"></div>

## Riwayat Rute

Di {{ config('app.name') }}, Anda dapat mengakses informasi riwayat rute menggunakan helper di bawah ini.

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

## Memperbarui Route Stack

Anda dapat memperbarui tumpukan navigasi secara programatis menggunakan `NyNavigator.updateStack()`:

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

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `routes` | `List<String>` | wajib | Daftar path rute untuk dinavigasi |
| `replace` | `bool` | `true` | Apakah mengganti tumpukan saat ini |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Data yang dikirim ke rute tertentu |

Ini berguna untuk:
- Skenario deep linking
- Memulihkan state navigasi
- Membangun alur navigasi yang kompleks


<div id="deep-linking"></div>

## Deep Linking

Deep linking memungkinkan pengguna menavigasi langsung ke konten tertentu dalam aplikasi Anda menggunakan URL. Ini berguna untuk:
- Berbagi tautan langsung ke konten aplikasi tertentu
- Kampanye pemasaran yang menargetkan fitur dalam aplikasi tertentu
- Menangani notifikasi yang harus membuka layar aplikasi tertentu
- Transisi web-ke-aplikasi yang mulus

## Setup

Sebelum mengimplementasikan deep linking di aplikasi Anda, pastikan proyek Anda dikonfigurasi dengan benar:

### 1. Konfigurasi Platform

**iOS**: Konfigurasikan universal links di proyek Xcode Anda
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Panduan Konfigurasi Flutter Universal Links</a>

**Android**: Atur app links di AndroidManifest.xml Anda
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Panduan Konfigurasi Flutter App Links</a>

### 2. Definisikan Rute Anda

Semua rute yang harus dapat diakses melalui deep links harus didaftarkan di konfigurasi router Anda:

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

## Menggunakan Deep Links

Setelah dikonfigurasi, aplikasi Anda dapat menangani URL yang masuk dalam berbagai format:

### Deep Links Dasar

Navigasi sederhana ke halaman tertentu:

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

Untuk memicu navigasi ini secara programatis di dalam aplikasi Anda:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Path Parameters

Untuk rute yang memerlukan data dinamis sebagai bagian dari path:

#### Definisi Rute

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

#### Navigasi Programatis

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Query Parameters

Untuk parameter opsional atau ketika dibutuhkan beberapa nilai dinamis:

#### Format URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Mengakses Query Parameters

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

#### Navigasi Programatis dengan Query Parameters

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Menangani Deep Links

Anda dapat menangani event deep link di `RouteProvider` Anda:

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

### Menguji Deep Links

Untuk pengembangan dan pengujian, Anda dapat mensimulasikan aktivasi deep link menggunakan ADB (Android) atau xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Tips Debugging

- Cetak semua parameter di metode init Anda untuk memverifikasi parsing yang benar
- Uji berbagai format URL untuk memastikan aplikasi Anda menanganinya dengan benar
- Ingat bahwa query parameters selalu diterima sebagai string, konversikan ke tipe yang sesuai sesuai kebutuhan

---

## Pola Umum

### Konversi Tipe Parameter

Karena semua parameter URL dikirim sebagai string, Anda sering perlu mengonversinya:

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Parameter Opsional

Menangani kasus di mana parameter mungkin tidak ada:

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

## Lanjutan

### Memeriksa Apakah Rute Ada

Anda dapat memeriksa apakah rute terdaftar di router Anda:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Metode NyRouter

Kelas `NyRouter` menyediakan beberapa metode yang berguna:

| Metode | Deskripsi |
|--------|-----------|
| `getRegisteredRouteNames()` | Mendapatkan semua nama rute terdaftar sebagai daftar |
| `getRegisteredRoutes()` | Mendapatkan semua rute terdaftar sebagai map |
| `containsRoutes(routes)` | Memeriksa apakah router berisi semua rute yang ditentukan |
| `getInitialRouteName()` | Mendapatkan nama rute awal |
| `getAuthRouteName()` | Mendapatkan nama rute terautentikasi |
| `getUnknownRouteName()` | Mendapatkan nama rute tidak dikenal/404 |

### Mendapatkan Argumen Rute

Anda dapat mendapatkan argumen rute menggunakan `NyRouter.args<T>()`:

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

### NyArgument dan NyQueryParameters

Data yang dikirim antar rute dibungkus dalam kelas-kelas ini:

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
