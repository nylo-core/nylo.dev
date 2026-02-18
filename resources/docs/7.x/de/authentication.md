# Authentifizierung

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Grundlagen
  - [Benutzer authentifizieren](#authenticating-users "Benutzer authentifizieren")
  - [Auth-Daten abrufen](#retrieving-auth-data "Auth-Daten abrufen")
  - [Auth-Daten aktualisieren](#updating-auth-data "Auth-Daten aktualisieren")
  - [Abmelden](#logging-out "Abmelden")
  - [Authentifizierung prüfen](#checking-authentication "Authentifizierung prüfen")
- Erweitert
  - [Mehrere Sitzungen](#multiple-sessions "Mehrere Sitzungen")
  - [Geräte-ID](#device-id "Geräte-ID")
  - [Synchronisierung mit Backpack](#syncing-to-backpack "Synchronisierung mit Backpack")
- Routen-Konfiguration
  - [Initiale Route](#initial-route "Initiale Route")
  - [Authentifizierte Route](#authenticated-route "Authentifizierte Route")
  - [Vorschau-Route](#preview-route "Vorschau-Route")
  - [Unbekannte Route](#unknown-route "Unbekannte Route")
- [Hilfsfunktionen](#helper-functions "Hilfsfunktionen")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet ein umfassendes Authentifizierungssystem über die `Auth`-Klasse. Es verwaltet die sichere Speicherung von Benutzeranmeldeinformationen, Sitzungsverwaltung und unterstützt mehrere benannte Sitzungen für verschiedene Authentifizierungskontexte.

Auth-Daten werden sicher gespeichert und mit Backpack (einem In-Memory Key-Value-Store) synchronisiert, um schnellen, synchronen Zugriff in Ihrer gesamten App zu ermöglichen.

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

## Benutzer authentifizieren

Verwenden Sie `Auth.authenticate()`, um Benutzersitzungsdaten zu speichern:

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

### Praxisbeispiel

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

## Auth-Daten abrufen

Gespeicherte Auth-Daten mit `Auth.data()` abrufen:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

Die Methode `Auth.data()` liest aus dem Backpack ({{ config('app.name') }}'s In-Memory Key-Value-Store) für schnellen synchronen Zugriff. Daten werden automatisch mit Backpack synchronisiert, wenn Sie sich authentifizieren.


<div id="updating-auth-data"></div>

## Auth-Daten aktualisieren

{{ config('app.name') }} v7 führt `Auth.set()` ein, um Auth-Daten zu aktualisieren:

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

## Abmelden

Entfernen Sie den authentifizierten Benutzer mit `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Von allen Sitzungen abmelden

Bei Verwendung mehrerer Sitzungen alle löschen:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Authentifizierung prüfen

Prüfen Sie, ob ein Benutzer aktuell authentifiziert ist:

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

## Mehrere Sitzungen

{{ config('app.name') }} v7 unterstützt mehrere benannte Auth-Sitzungen für verschiedene Kontexte. Dies ist nützlich, wenn Sie verschiedene Arten der Authentifizierung separat verfolgen müssen (z.B. Benutzeranmeldung vs. Geräteregistrierung vs. Admin-Zugang).

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

### Aus benannten Sitzungen lesen

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

### Sitzungsspezifisches Abmelden

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Authentifizierung pro Sitzung prüfen

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Geräte-ID

{{ config('app.name') }} v7 stellt einen eindeutigen Gerätebezeichner bereit, der über App-Sitzungen hinweg bestehen bleibt:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

Die Geräte-ID ist:
- Einmalig generiert und dauerhaft gespeichert
- Einzigartig für jedes Gerät/jede Installation
- Nützlich für Geräteregistrierung, Analysen oder Push-Benachrichtigungen

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

## Synchronisierung mit Backpack

Auth-Daten werden automatisch mit Backpack synchronisiert, wenn Sie sich authentifizieren. Für manuelle Synchronisierung (z.B. beim App-Start):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Dies ist nützlich in der Boot-Sequenz Ihrer App, um sicherzustellen, dass Auth-Daten in Backpack für schnellen synchronen Zugriff verfügbar sind.


<div id="initial-route"></div>

## Initiale Route

Die initiale Route ist die erste Seite, die Benutzer sehen, wenn sie Ihre App öffnen. Setzen Sie sie mit `.initialRoute()` in Ihrem Router:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Sie können auch eine bedingte initiale Route mit dem Parameter `when` setzen:

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

Navigieren Sie von überall zur initialen Route zurück mit `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Authentifizierte Route

Die authentifizierte Route überschreibt die initiale Route, wenn ein Benutzer angemeldet ist. Setzen Sie sie mit `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Beim App-Start:
- `Auth.isAuthenticated()` gibt `true` zurück → Benutzer sieht die **authentifizierte Route** (HomePage)
- `Auth.isAuthenticated()` gibt `false` zurück → Benutzer sieht die **initiale Route** (LoginPage)

Sie können auch eine bedingte authentifizierte Route setzen:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Navigieren Sie programmatisch zur authentifizierten Route mit `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Siehe auch:** [Router](/docs/{{ $version }}/router) für die vollständige Routing-Dokumentation einschließlich Guards und Deep Linking.


<div id="preview-route"></div>

## Vorschau-Route

Während der Entwicklung möchten Sie möglicherweise schnell eine bestimmte Seite in der Vorschau anzeigen, ohne Ihre initiale oder authentifizierte Route zu ändern. Verwenden Sie `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` überschreibt **sowohl** `initialRoute()` als auch `authenticatedRoute()` und macht die angegebene Route zur ersten angezeigten Seite, unabhängig vom Auth-Status.

> **Warnung:** Entfernen Sie `.previewRoute()` vor der Veröffentlichung Ihrer App.


<div id="unknown-route"></div>

## Unbekannte Route

Definieren Sie eine Fallback-Seite für den Fall, dass ein Benutzer zu einer nicht existierenden Route navigiert. Setzen Sie sie mit `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Alles zusammen

Hier ist ein vollständiges Router-Setup mit allen Routentypen:

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

| Routenmethode | Zweck |
|---------------|-------|
| `.initialRoute()` | Erste Seite für nicht authentifizierte Benutzer |
| `.authenticatedRoute()` | Erste Seite für authentifizierte Benutzer |
| `.previewRoute()` | Überschreibt beide während der Entwicklung |
| `.unknownRoute()` | Wird angezeigt, wenn eine Route nicht gefunden wird |


<div id="helper-functions"></div>

## Hilfsfunktionen

{{ config('app.name') }} v7 bietet Hilfsfunktionen, die die Methoden der `Auth`-Klasse widerspiegeln:

| Hilfsfunktion | Entsprechung |
|---------------|-------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Speicherschlüssel für die Standardsitzung |
| `authDeviceId()` | `Auth.deviceId()` |

Alle Hilfsfunktionen akzeptieren dieselben Parameter wie ihre `Auth`-Klassen-Gegenstücke, einschließlich des optionalen `session`-Parameters:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

