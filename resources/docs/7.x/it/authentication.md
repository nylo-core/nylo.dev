# Autenticazione

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione all'autenticazione")
- Fondamenti
  - [Autenticazione degli Utenti](#authenticating-users "Autenticazione degli utenti")
  - [Recupero dei Dati di Autenticazione](#retrieving-auth-data "Recupero dei dati di autenticazione")
  - [Aggiornamento dei Dati di Autenticazione](#updating-auth-data "Aggiornamento dei dati di autenticazione")
  - [Logout](#logging-out "Logout")
  - [Verifica dell'Autenticazione](#checking-authentication "Verifica dell'autenticazione")
- Avanzato
  - [Sessioni Multiple](#multiple-sessions "Sessioni multiple")
  - [ID Dispositivo](#device-id "ID Dispositivo")
  - [Sincronizzazione con Backpack](#syncing-to-backpack "Sincronizzazione con Backpack")
- Configurazione delle Rotte
  - [Rotta Iniziale](#initial-route "Rotta iniziale")
  - [Rotta Autenticata](#authenticated-route "Rotta autenticata")
  - [Rotta di Anteprima](#preview-route "Rotta di anteprima")
  - [Rotta Sconosciuta](#unknown-route "Rotta sconosciuta")
- [Funzioni Helper](#helper-functions "Funzioni helper")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce un sistema di autenticazione completo attraverso la classe `Auth`. Gestisce lo storage sicuro delle credenziali utente, la gestione delle sessioni e supporta sessioni nominate multiple per diversi contesti di autenticazione.

I dati di autenticazione vengono memorizzati in modo sicuro e sincronizzati con Backpack (un archivio chiave-valore in memoria) per un accesso sincrono rapido in tutta la tua app.

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

## Autenticazione degli Utenti

Usa `Auth.authenticate()` per memorizzare i dati della sessione utente:

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

### Esempio Reale

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

## Recupero dei Dati di Autenticazione

Ottieni i dati di autenticazione memorizzati utilizzando `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

Il metodo `Auth.data()` legge dal Backpack (l'archivio chiave-valore in memoria di {{ config('app.name') }}) per un accesso sincrono rapido. I dati vengono sincronizzati automaticamente nel Backpack quando effettui l'autenticazione.


<div id="updating-auth-data"></div>

## Aggiornamento dei Dati di Autenticazione

{{ config('app.name') }} v7 introduce `Auth.set()` per aggiornare i dati di autenticazione:

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

Rimuovi l'utente autenticato con `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Logout da Tutte le Sessioni

Quando utilizzi sessioni multiple, cancellale tutte:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Verifica dell'Autenticazione

Verifica se un utente e' attualmente autenticato:

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

## Sessioni Multiple

{{ config('app.name') }} v7 supporta sessioni di autenticazione nominate multiple per diversi contesti. Questo e' utile quando devi tracciare diversi tipi di autenticazione separatamente (es., login utente vs registrazione dispositivo vs accesso admin).

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

### Lettura da Sessioni Nominate

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

### Logout Specifico per Sessione

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Verifica Autenticazione per Sessione

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## ID Dispositivo

{{ config('app.name') }} v7 fornisce un identificativo univoco del dispositivo che persiste tra le sessioni dell'app:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

L'ID dispositivo:
- Viene generato una volta e memorizzato permanentemente
- E' univoco per ogni dispositivo/installazione
- E' utile per la registrazione del dispositivo, analytics o notifiche push

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

## Sincronizzazione con Backpack

I dati di autenticazione vengono sincronizzati automaticamente nel Backpack quando effettui l'autenticazione. Per sincronizzare manualmente (es. all'avvio dell'app):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Questo e' utile nella sequenza di avvio della tua app per assicurare che i dati di autenticazione siano disponibili nel Backpack per un accesso sincrono rapido.


<div id="initial-route"></div>

## Rotta Iniziale

La rotta iniziale e' la prima pagina che gli utenti vedono quando aprono la tua app. Impostala utilizzando `.initialRoute()` nel tuo router:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Puoi anche impostare una rotta iniziale condizionale utilizzando il parametro `when`:

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

Naviga alla rotta iniziale da qualsiasi punto utilizzando `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Rotta Autenticata

La rotta autenticata sovrascrive la rotta iniziale quando un utente e' autenticato. Impostala utilizzando `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Quando l'app si avvia:
- `Auth.isAuthenticated()` restituisce `true` → l'utente vede la **rotta autenticata** (HomePage)
- `Auth.isAuthenticated()` restituisce `false` → l'utente vede la **rotta iniziale** (LoginPage)

Puoi anche impostare una rotta autenticata condizionale:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Naviga alla rotta autenticata programmaticamente utilizzando `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Vedi anche:** [Router](/docs/{{ $version }}/router) per la documentazione completa sul routing, inclusi guard e deep linking.


<div id="preview-route"></div>

## Rotta di Anteprima

Durante lo sviluppo, potresti voler visualizzare rapidamente una pagina specifica senza cambiare la tua rotta iniziale o autenticata. Usa `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` sovrascrive **sia** `initialRoute()` che `authenticatedRoute()`, rendendo la rotta specificata la prima pagina mostrata indipendentemente dallo stato di autenticazione.

> **Attenzione:** Rimuovi `.previewRoute()` prima di rilasciare la tua app.


<div id="unknown-route"></div>

## Rotta Sconosciuta

Definisci una pagina di fallback per quando un utente naviga verso una rotta che non esiste. Impostala utilizzando `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Tutto Insieme

Ecco una configurazione completa del router con tutti i tipi di rotta:

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

| Metodo della Rotta | Scopo |
|--------------------|-------|
| `.initialRoute()` | Prima pagina mostrata agli utenti non autenticati |
| `.authenticatedRoute()` | Prima pagina mostrata agli utenti autenticati |
| `.previewRoute()` | Sovrascrive entrambe durante lo sviluppo |
| `.unknownRoute()` | Mostrata quando una rotta non viene trovata |


<div id="helper-functions"></div>

## Funzioni Helper

{{ config('app.name') }} v7 fornisce funzioni helper che rispecchiano i metodi della classe `Auth`:

| Funzione Helper | Equivalente |
|-----------------|-------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Chiave di storage per la sessione predefinita |
| `authDeviceId()` | `Auth.deviceId()` |

Tutti gli helper accettano gli stessi parametri delle loro controparti nella classe `Auth`, incluso il parametro opzionale `session`:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

