# Authentication

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do uwierzytelniania")
- Podstawy
  - [Uwierzytelnianie uzytkownikow](#authenticating-users "Uwierzytelnianie uzytkownikow")
  - [Pobieranie danych uwierzytelniania](#retrieving-auth-data "Pobieranie danych uwierzytelniania")
  - [Aktualizacja danych uwierzytelniania](#updating-auth-data "Aktualizacja danych uwierzytelniania")
  - [Wylogowanie](#logging-out "Wylogowanie")
  - [Sprawdzanie uwierzytelniania](#checking-authentication "Sprawdzanie uwierzytelniania")
- Zaawansowane
  - [Wiele sesji](#multiple-sessions "Wiele sesji")
  - [ID urzadzenia](#device-id "ID urzadzenia")
  - [Synchronizacja z Backpack](#syncing-to-backpack "Synchronizacja z Backpack")
- Konfiguracja tras
  - [Trasa poczatkowa](#initial-route "Trasa poczatkowa")
  - [Trasa uwierzytelniona](#authenticated-route "Trasa uwierzytelniona")
  - [Trasa podgladu](#preview-route "Trasa podgladu")
  - [Trasa nieznana](#unknown-route "Trasa nieznana")
- [Funkcje pomocnicze](#helper-functions "Funkcje pomocnicze")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 udostepnia kompleksowy system uwierzytelniania poprzez klase `Auth`. Obsluguje bezpieczne przechowywanie danych uwierzytelniajacych uzytkownika, zarzadzanie sesjami oraz wspiera wiele nazwanych sesji dla roznych kontekstow uwierzytelniania.

Dane uwierzytelniania sa bezpiecznie przechowywane i synchronizowane z Backpack (magazynem klucz-wartosc w pamieci) w celu szybkiego, synchronicznego dostepu w calej aplikacji.

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

## Uwierzytelnianie uzytkownikow

Uzyj `Auth.authenticate()` do przechowywania danych sesji uzytkownika:

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

### Przyklad z zycia

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

## Pobieranie danych uwierzytelniania

Pobierz zapisane dane uwierzytelniania za pomoca `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

Metoda `Auth.data()` odczytuje dane z Backpack (magazynu klucz-wartosc w pamieci {{ config('app.name') }}) w celu szybkiego synchronicznego dostepu. Dane sa automatycznie synchronizowane z Backpack podczas uwierzytelniania.


<div id="updating-auth-data"></div>

## Aktualizacja danych uwierzytelniania

{{ config('app.name') }} v7 wprowadza `Auth.set()` do aktualizacji danych uwierzytelniania:

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

## Wylogowanie

Usun uwierzytelnionego uzytkownika za pomoca `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Wylogowanie ze wszystkich sesji

Podczas korzystania z wielu sesji, wyczysc je wszystkie:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Sprawdzanie uwierzytelniania

Sprawdz, czy uzytkownik jest aktualnie uwierzytelniony:

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

## Wiele sesji

{{ config('app.name') }} v7 obsluguje wiele nazwanych sesji uwierzytelniania dla roznych kontekstow. Jest to przydatne, gdy musisz sledzic rozne typy uwierzytelniania oddzielnie (np. logowanie uzytkownika vs rejestracja urzadzenia vs dostep administratora).

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

### Odczytywanie z nazwanych sesji

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

### Wylogowanie z konkretnej sesji

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Sprawdzanie uwierzytelniania per sesja

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## ID urzadzenia

{{ config('app.name') }} v7 udostepnia unikalny identyfikator urzadzenia, ktory jest trwaly miedzy sesjami aplikacji:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

ID urzadzenia jest:
- Generowane raz i przechowywane na stale
- Unikalne dla kazdego urzadzenia/instalacji
- Przydatne do rejestracji urzadzen, analityki lub powiadomien push

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

## Synchronizacja z Backpack

Dane uwierzytelniania sa automatycznie synchronizowane z Backpack podczas uwierzytelniania. Aby recznie zsynchronizowac (np. przy uruchamianiu aplikacji):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Jest to przydatne w sekwencji uruchamiania aplikacji, aby zapewnic dostepnosc danych uwierzytelniania w Backpack dla szybkiego synchronicznego dostepu.


<div id="initial-route"></div>

## Trasa poczatkowa

Trasa poczatkowa to pierwsza strona, ktora widza uzytkownicy po otwarciu aplikacji. Ustaw ja za pomoca `.initialRoute()` w routerze:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Mozesz rowniez ustawic warunkowa trase poczatkowa za pomoca parametru `when`:

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

Nawiguj z powrotem do trasy poczatkowej z dowolnego miejsca za pomoca `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Trasa uwierzytelniona

Trasa uwierzytelniona nadpisuje trase poczatkowa, gdy uzytkownik jest zalogowany. Ustaw ja za pomoca `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Gdy aplikacja sie uruchamia:
- `Auth.isAuthenticated()` zwraca `true` -- uzytkownik widzi **trase uwierzytelniona** (HomePage)
- `Auth.isAuthenticated()` zwraca `false` -- uzytkownik widzi **trase poczatkowa** (LoginPage)

Mozesz rowniez ustawic warunkowa trase uwierzytelniona:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Nawiguj do trasy uwierzytelnionej programowo za pomoca `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Zobacz takze:** [Router](/docs/{{ $version }}/router) po pelna dokumentacje routingu, w tym guardy i deep linking.


<div id="preview-route"></div>

## Trasa podgladu

Podczas programowania mozesz chciec szybko wyswietlic podglad konkretnej strony bez zmiany trasy poczatkowej lub uwierzytelnionej. Uzyj `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` nadpisuje **zarowno** `initialRoute()` jak i `authenticatedRoute()`, sprawiajac, ze wskazana trasa jest pierwsza wyswietlana strona niezaleznie od stanu uwierzytelniania.

> **Uwaga:** Usun `.previewRoute()` przed wydaniem aplikacji.


<div id="unknown-route"></div>

## Trasa nieznana

Zdefiniuj strone zastepna dla przypadku, gdy uzytkownik nawiguje do trasy, ktora nie istnieje. Ustaw ja za pomoca `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Wszystko razem

Oto kompletna konfiguracja routera ze wszystkimi typami tras:

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

| Metoda trasy | Cel |
|--------------|---------|
| `.initialRoute()` | Pierwsza strona wyswietlana nieuwierzytelnionym uzytkownikom |
| `.authenticatedRoute()` | Pierwsza strona wyswietlana uwierzytelnionym uzytkownikom |
| `.previewRoute()` | Nadpisuje obie podczas programowania |
| `.unknownRoute()` | Wyswietlana, gdy trasa nie zostanie znaleziona |


<div id="helper-functions"></div>

## Funkcje pomocnicze

{{ config('app.name') }} v7 udostepnia funkcje pomocnicze, ktore odzwierciedlaja metody klasy `Auth`:

| Funkcja pomocnicza | Odpowiednik |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Klucz przechowywania dla domyslnej sesji |
| `authDeviceId()` | `Auth.deviceId()` |

Wszystkie funkcje pomocnicze przyjmuja te same parametry co ich odpowiedniki w klasie `Auth`, w tym opcjonalny parametr `session`:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
