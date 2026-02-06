# Autentikasi

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar tentang autentikasi")
- Dasar
  - [Mengautentikasi Pengguna](#authenticating-users "Mengautentikasi pengguna")
  - [Mengambil Data Auth](#retrieving-auth-data "Mengambil data auth")
  - [Memperbarui Data Auth](#updating-auth-data "Memperbarui data auth")
  - [Logout](#logging-out "Logout")
  - [Memeriksa Autentikasi](#checking-authentication "Memeriksa autentikasi")
- Lanjutan
  - [Sesi Ganda](#multiple-sessions "Sesi ganda")
  - [ID Perangkat](#device-id "ID perangkat")
  - [Sinkronisasi ke Backpack](#syncing-to-backpack "Sinkronisasi ke Backpack")
- Konfigurasi Rute
  - [Rute Awal](#initial-route "Rute awal")
  - [Rute Terautentikasi](#authenticated-route "Rute terautentikasi")
  - [Rute Preview](#preview-route "Rute preview")
  - [Rute Tidak Dikenal](#unknown-route "Rute tidak dikenal")
- [Fungsi Helper](#helper-functions "Fungsi helper")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan sistem autentikasi komprehensif melalui kelas `Auth`. Sistem ini menangani penyimpanan kredensial pengguna secara aman, manajemen sesi, dan mendukung beberapa sesi bernama untuk konteks auth yang berbeda.

Data auth disimpan secara aman dan disinkronkan ke Backpack (penyimpanan key-value dalam memori) untuk akses sinkron yang cepat di seluruh aplikasi Anda.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Authenticate a user
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// Check if authenticated
bool loggedIn = await Auth.isAuthenticated(); // true

// Get auth data
dynamic token = Auth.data(field: 'token'); // "abc123"

// Logout
await Auth.logout();
```


<div id="authenticating-users"></div>

## Mengautentikasi Pengguna

Gunakan `Auth.authenticate()` untuk menyimpan data sesi pengguna:

``` dart
// With a Map
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// With a Model class
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// Without data (stores a timestamp)
await Auth.authenticate();
```

### Contoh Dunia Nyata

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. Call your API to authenticate
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. Store the authenticated user
    await Auth.authenticate(data: user);

    // 3. Navigate to home
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## Mengambil Data Auth

Ambil data auth yang tersimpan menggunakan `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

Method `Auth.data()` membaca dari Backpack (penyimpanan key-value dalam memori {{ config('app.name') }}) untuk akses sinkron yang cepat. Data secara otomatis disinkronkan ke Backpack saat Anda mengautentikasi.


<div id="updating-auth-data"></div>

## Memperbarui Data Auth

{{ config('app.name') }} v7 memperkenalkan `Auth.set()` untuk memperbarui data auth:

``` dart
// Update a specific field
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// Add new fields
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// Replace entire data
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## Logout

Hapus pengguna yang terautentikasi dengan `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Logout dari Semua Sesi

Saat menggunakan beberapa sesi, hapus semuanya:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Memeriksa Autentikasi

Periksa apakah pengguna saat ini terautentikasi:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // User is authenticated
  routeTo(HomePage.path);
} else {
  // User needs to login
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## Sesi Ganda

{{ config('app.name') }} v7 mendukung beberapa sesi auth bernama untuk konteks yang berbeda. Ini berguna ketika Anda perlu melacak berbagai jenis autentikasi secara terpisah (misalnya, login pengguna vs registrasi perangkat vs akses admin).

``` dart
// Default user session
await Auth.authenticate(data: user);

// Device authentication session
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// Admin session
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### Membaca dari Sesi Bernama

``` dart
// Default session
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// Device session
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// Admin session
dynamic adminData = Auth.data(session: 'admin');
```

### Logout Berdasarkan Sesi

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Memeriksa Autentikasi per Sesi

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## ID Perangkat

{{ config('app.name') }} v7 menyediakan identifier perangkat unik yang bertahan antar sesi aplikasi:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

ID perangkat:
- Dibuat sekali dan disimpan secara permanen
- Unik untuk setiap perangkat/instalasi
- Berguna untuk registrasi perangkat, analitik, atau push notification

``` dart
// Example: Register device with backend
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // Store device auth
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## Sinkronisasi ke Backpack

Data auth secara otomatis disinkronkan ke Backpack saat Anda mengautentikasi. Untuk sinkronisasi manual (misalnya saat boot aplikasi):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Ini berguna dalam urutan boot aplikasi Anda untuk memastikan data auth tersedia di Backpack untuk akses sinkron yang cepat.


<div id="initial-route"></div>

## Rute Awal

Rute awal adalah halaman pertama yang dilihat pengguna saat membuka aplikasi Anda. Atur menggunakan `.initialRoute()` di router Anda:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Anda juga dapat mengatur rute awal bersyarat menggunakan parameter `when`:

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

Navigasi kembali ke rute awal dari mana saja menggunakan `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Rute Terautentikasi

Rute terautentikasi menggantikan rute awal saat pengguna sudah login. Atur menggunakan `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Saat aplikasi boot:
- `Auth.isAuthenticated()` mengembalikan `true` → pengguna melihat **rute terautentikasi** (HomePage)
- `Auth.isAuthenticated()` mengembalikan `false` → pengguna melihat **rute awal** (LoginPage)

Anda juga dapat mengatur rute terautentikasi bersyarat:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Navigasi ke rute terautentikasi secara programatis menggunakan `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Lihat juga:** [Router](/docs/{{ $version }}/router) untuk dokumentasi routing lengkap termasuk guard dan deep linking.


<div id="preview-route"></div>

## Rute Preview

Selama pengembangan, Anda mungkin ingin dengan cepat melihat pratinjau halaman tertentu tanpa mengubah rute awal atau terautentikasi Anda. Gunakan `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` menggantikan **keduanya** `initialRoute()` dan `authenticatedRoute()`, membuat rute yang ditentukan menjadi halaman pertama yang ditampilkan terlepas dari status auth.

> **Peringatan:** Hapus `.previewRoute()` sebelum merilis aplikasi Anda.


<div id="unknown-route"></div>

## Rute Tidak Dikenal

Tentukan halaman fallback untuk saat pengguna menavigasi ke rute yang tidak ada. Atur menggunakan `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Menggabungkan Semuanya

Berikut pengaturan router lengkap dengan semua jenis rute:

``` dart
appRouter() => nyRoutes((router) {
  // First page for unauthenticated users
  router.add(LoginPage.path).initialRoute();

  // First page for authenticated users
  router.add(HomePage.path).authenticatedRoute();

  // 404 page
  router.add(NotFoundPage.path).unknownRoute();

  // Regular routes
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| Method Rute | Tujuan |
|-------------|--------|
| `.initialRoute()` | Halaman pertama yang ditampilkan untuk pengguna yang tidak terautentikasi |
| `.authenticatedRoute()` | Halaman pertama yang ditampilkan untuk pengguna yang terautentikasi |
| `.previewRoute()` | Menggantikan keduanya selama pengembangan |
| `.unknownRoute()` | Ditampilkan saat rute tidak ditemukan |


<div id="helper-functions"></div>

## Fungsi Helper

{{ config('app.name') }} v7 menyediakan fungsi helper yang mencerminkan method kelas `Auth`:

| Fungsi Helper | Setara Dengan |
|---------------|---------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Kunci storage untuk sesi default |
| `authDeviceId()` | `Auth.deviceId()` |

Semua helper menerima parameter yang sama dengan pasangan kelas `Auth`-nya, termasuk parameter opsional `session`:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

