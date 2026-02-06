# Kimlik Dogrulama

---

<a name="section-1"></a>
- [Giris](#introduction "Kimlik dogrulamaya giris")
- Temel Bilgiler
  - [Kullanici Kimlik Dogrulamasi](#authenticating-users "Kullanici kimlik dogrulamasi")
  - [Kimlik Dogrulama Verilerini Alma](#retrieving-auth-data "Kimlik dogrulama verilerini alma")
  - [Kimlik Dogrulama Verilerini Guncelleme](#updating-auth-data "Kimlik dogrulama verilerini guncelleme")
  - [Cikis Yapma](#logging-out "Cikis yapma")
  - [Kimlik Dogrulama Kontrolu](#checking-authentication "Kimlik dogrulama kontrolu")
- Gelismis
  - [Coklu Oturum](#multiple-sessions "Coklu oturum")
  - [Cihaz Kimligi](#device-id "Cihaz kimligi")
  - [Backpack'e Senkronizasyon](#syncing-to-backpack "Backpack'e senkronizasyon")
- Rota Yapilandirmasi
  - [Baslangic Rotasi](#initial-route "Baslangic rotasi")
  - [Kimlik Dogrulanmis Rota](#authenticated-route "Kimlik dogrulanmis rota")
  - [Onizleme Rotasi](#preview-route "Onizleme rotasi")
  - [Bilinmeyen Rota](#unknown-route "Bilinmeyen rota")
- [Yardimci Fonksiyonlar](#helper-functions "Yardimci fonksiyonlar")

<div id="introduction"></div>

## Giris

{{ config('app.name') }} v7, `Auth` sinifi araciligiyla kapsamli bir kimlik dogrulama sistemi sunar. Kullanici kimlik bilgilerinin guvenli depolanmasini, oturum yonetimini ve farkli kimlik dogrulama baglamlari icin birden fazla adlandirilmis oturumu destekler.

Kimlik dogrulama verileri guvenli bir sekilde saklanir ve hizli, senkron erisim icin Backpack'e (bellek ici anahtar-deger deposu) senkronize edilir.

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

## Kullanici Kimlik Dogrulamasi

Kullanici oturum verilerini saklamak icin `Auth.authenticate()` kullanin:

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

### Gercek Dunya Ornegi

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

## Kimlik Dogrulama Verilerini Alma

Saklanan kimlik dogrulama verilerini `Auth.data()` kullanarak alin:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

`Auth.data()` metodu hizli senkron erisim icin Backpack'ten ({{ config('app.name') }}'in bellek ici anahtar-deger deposu) okur. Kimlik dogrulamasi yaptiginizda veriler otomatik olarak Backpack'e senkronize edilir.


<div id="updating-auth-data"></div>

## Kimlik Dogrulama Verilerini Guncelleme

{{ config('app.name') }} v7, kimlik dogrulama verilerini guncellemek icin `Auth.set()` sunar:

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

## Cikis Yapma

Kimlik dogrulanmis kullaniciyi `Auth.logout()` ile kaldirin:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Tum Oturumlardan Cikis

Coklu oturum kullanirken, hepsini temizleyin:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Kimlik Dogrulama Kontrolu

Kullanicinin su anda kimlik dogrulanmis olup olmadigini kontrol edin:

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

## Coklu Oturum

{{ config('app.name') }} v7, farkli baglamlar icin birden fazla adlandirilmis kimlik dogrulama oturumu destekler. Bu, farkli kimlik dogrulama turlerini ayri ayri takip etmeniz gerektiginde kullanislidir (orn., kullanici girisi vs cihaz kaydı vs yonetici erisimi).

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

### Adlandirilmis Oturumlardan Okuma

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

### Oturuma Ozel Cikis

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Oturum Basina Kimlik Dogrulama Kontrolu

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Cihaz Kimligi

{{ config('app.name') }} v7, uygulama oturumlari arasinda kalici olan benzersiz bir cihaz tanimlayicisi saglar:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

Cihaz kimligi:
- Bir kez olusturulur ve kalici olarak saklanir
- Her cihaz/kurulum icin benzersizdir
- Cihaz kaydi, analitik veya push bildirimleri icin kullanislidir

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

## Backpack'e Senkronizasyon

Kimlik dogrulama verileri, kimlik dogrulamasi yaptiginizda otomatik olarak Backpack'e senkronize edilir. Manuel senkronizasyon icin (orn., uygulama baslangicindan):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Bu, hizli senkron erisim icin kimlik dogrulama verilerinin Backpack'te mevcut olmasini saglamak adina uygulamanizin baslatma surecinde kullanislidir.


<div id="initial-route"></div>

## Baslangic Rotasi

Baslangic rotasi, kullanicilarin uygulamanizi actiklarinda gordukleri ilk sayfadir. Router'inizda `.initialRoute()` kullanarak ayarlayin:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

`when` parametresini kullanarak kosullu bir baslangic rotasi da ayarlayabilirsiniz:

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

Herhangi bir yerden baslangic rotasina `routeToInitial()` kullanarak donebilirsiniz:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Kimlik Dogrulanmis Rota

Kimlik dogrulanmis rota, kullanici giris yaptiginda baslangic rotasini gecersiz kilar. `.authenticatedRoute()` kullanarak ayarlayin:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Uygulama basladiginda:
- `Auth.isAuthenticated()` `true` dondururse → kullanici **kimlik dogrulanmis rotayi** (HomePage) gorur
- `Auth.isAuthenticated()` `false` dondururse → kullanici **baslangic rotasini** (LoginPage) gorur

Kosullu bir kimlik dogrulanmis rota da ayarlayabilirsiniz:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

`routeToAuthenticatedRoute()` kullanarak kimlik dogrulanmis rotaya programatik olarak gidin:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Ayrica bakiniz:** Guard'lar ve deep linking dahil tam yonlendirme dokumantasyonu icin [Router](/docs/{{ $version }}/router).


<div id="preview-route"></div>

## Onizleme Rotasi

Gelistirme sirasinda, baslangic veya kimlik dogrulanmis rotanizi degistirmeden belirli bir sayfayi hizlica onizlemek isteyebilirsiniz. `.previewRoute()` kullanin:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()`, kimlik dogrulama durumundan bagimsiz olarak belirtilen rotayi ilk gosterilen sayfa yaparak hem `initialRoute()` hem de `authenticatedRoute()` gecersiz kilar.

> **Uyari:** Uygulamanizi yayinlamadan once `.previewRoute()` kaldirin.


<div id="unknown-route"></div>

## Bilinmeyen Rota

Kullanici mevcut olmayan bir rotaya gittiginde gosterilecek bir yedek sayfa tanimlayin. `.unknownRoute()` kullanarak ayarlayin:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Hepsini Bir Araya Getirme

Iste tum rota turlerini iceren eksiksiz bir router kurulumu:

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

| Rota Metodu | Amac |
|--------------|---------|
| `.initialRoute()` | Kimligi dogrulanmamis kullanicilara gosterilen ilk sayfa |
| `.authenticatedRoute()` | Kimligi dogrulanmis kullanicilara gosterilen ilk sayfa |
| `.previewRoute()` | Gelistirme sirasinda her ikisini gecersiz kilar |
| `.unknownRoute()` | Rota bulunamadiginda gosterilir |


<div id="helper-functions"></div>

## Yardimci Fonksiyonlar

{{ config('app.name') }} v7, `Auth` sinifi metotlarini yansitan yardimci fonksiyonlar saglar:

| Yardimci Fonksiyon | Karsiligi |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Varsayilan oturum icin depolama anahtari |
| `authDeviceId()` | `Auth.deviceId()` |

Tum yardimcilar, istege bagli `session` parametresi dahil olmak uzere `Auth` sinifi karsiliklariyla ayni parametreleri kabul eder:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

