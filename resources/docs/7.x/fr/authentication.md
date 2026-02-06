# Authentification

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction a l'authentification")
- Bases
  - [Authentifier les utilisateurs](#authenticating-users "Authentifier les utilisateurs")
  - [Recuperer les donnees d'authentification](#retrieving-auth-data "Recuperer les donnees d'authentification")
  - [Mettre a jour les donnees d'authentification](#updating-auth-data "Mettre a jour les donnees d'authentification")
  - [Deconnexion](#logging-out "Deconnexion")
  - [Verifier l'authentification](#checking-authentication "Verifier l'authentification")
- Avance
  - [Sessions multiples](#multiple-sessions "Sessions multiples")
  - [Identifiant d'appareil](#device-id "Identifiant d'appareil")
  - [Synchronisation avec Backpack](#syncing-to-backpack "Synchronisation avec Backpack")
- Configuration des routes
  - [Route initiale](#initial-route "Route initiale")
  - [Route authentifiee](#authenticated-route "Route authentifiee")
  - [Route de previsualisation](#preview-route "Route de previsualisation")
  - [Route inconnue](#unknown-route "Route inconnue")
- [Fonctions d'aide](#helper-functions "Fonctions d'aide")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit un systeme d'authentification complet via la classe `Auth`. Il gere le stockage securise des identifiants utilisateur, la gestion des sessions et prend en charge plusieurs sessions nommees pour differents contextes d'authentification.

Les donnees d'authentification sont stockees de maniere securisee et synchronisees avec Backpack (un magasin cle-valeur en memoire) pour un acces synchrone rapide dans toute votre application.

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

## Authentifier les utilisateurs

Utilisez `Auth.authenticate()` pour stocker les donnees de session utilisateur :

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

### Exemple concret

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

## Recuperer les donnees d'authentification

Recuperez les donnees d'authentification stockees avec `Auth.data()` :

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

La methode `Auth.data()` lit depuis Backpack (le magasin cle-valeur en memoire de {{ config('app.name') }}) pour un acces synchrone rapide. Les donnees sont automatiquement synchronisees avec Backpack lors de l'authentification.


<div id="updating-auth-data"></div>

## Mettre a jour les donnees d'authentification

{{ config('app.name') }} v7 introduit `Auth.set()` pour mettre a jour les donnees d'authentification :

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

## Deconnexion

Supprimez l'utilisateur authentifie avec `Auth.logout()` :

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Deconnexion de toutes les sessions

Lorsque vous utilisez plusieurs sessions, effacez-les toutes :

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Verifier l'authentification

Verifiez si un utilisateur est actuellement authentifie :

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

## Sessions multiples

{{ config('app.name') }} v7 prend en charge plusieurs sessions d'authentification nommees pour differents contextes. Ceci est utile lorsque vous devez suivre differents types d'authentification separement (par ex., connexion utilisateur vs enregistrement d'appareil vs acces administrateur).

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

### Lecture depuis les sessions nommees

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

### Deconnexion specifique a une session

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Verifier l'authentification par session

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Identifiant d'appareil

{{ config('app.name') }} v7 fournit un identifiant d'appareil unique qui persiste entre les sessions de l'application :

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

L'identifiant d'appareil est :
- Genere une seule fois et stocke de maniere permanente
- Unique a chaque appareil/installation
- Utile pour l'enregistrement d'appareils, l'analytique ou les notifications push

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

## Synchronisation avec Backpack

Les donnees d'authentification sont automatiquement synchronisees avec Backpack lors de l'authentification. Pour synchroniser manuellement (par exemple, au demarrage de l'application) :

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Ceci est utile dans la sequence de demarrage de votre application pour garantir que les donnees d'authentification sont disponibles dans Backpack pour un acces synchrone rapide.


<div id="initial-route"></div>

## Route initiale

La route initiale est la premiere page que les utilisateurs voient lorsqu'ils ouvrent votre application. Definissez-la avec `.initialRoute()` dans votre routeur :

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Vous pouvez egalement definir une route initiale conditionnelle avec le parametre `when` :

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

Naviguez vers la route initiale depuis n'importe ou avec `routeToInitial()` :

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Route authentifiee

La route authentifiee remplace la route initiale lorsqu'un utilisateur est connecte. Definissez-la avec `.authenticatedRoute()` :

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Au demarrage de l'application :
- `Auth.isAuthenticated()` retourne `true` → l'utilisateur voit la **route authentifiee** (HomePage)
- `Auth.isAuthenticated()` retourne `false` → l'utilisateur voit la **route initiale** (LoginPage)

Vous pouvez egalement definir une route authentifiee conditionnelle :

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Naviguez vers la route authentifiee par programmation avec `routeToAuthenticatedRoute()` :

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Voir aussi :** [Router](/docs/{{ $version }}/router) pour la documentation complete du routage, y compris les guards et le deep linking.


<div id="preview-route"></div>

## Route de previsualisation

Pendant le developpement, vous pouvez vouloir previsualiser rapidement une page specifique sans changer votre route initiale ou authentifiee. Utilisez `.previewRoute()` :

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` remplace **a la fois** `initialRoute()` et `authenticatedRoute()`, faisant de la route specifiee la premiere page affichee quel que soit l'etat d'authentification.

> **Avertissement :** Supprimez `.previewRoute()` avant de publier votre application.


<div id="unknown-route"></div>

## Route inconnue

Definissez une page de secours lorsqu'un utilisateur navigue vers une route qui n'existe pas. Definissez-la avec `.unknownRoute()` :

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Vue d'ensemble

Voici une configuration de routeur complete avec tous les types de routes :

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

| Methode de route | Objectif |
|--------------|---------|
| `.initialRoute()` | Premiere page affichee aux utilisateurs non authentifies |
| `.authenticatedRoute()` | Premiere page affichee aux utilisateurs authentifies |
| `.previewRoute()` | Remplace les deux pendant le developpement |
| `.unknownRoute()` | Affichee lorsqu'une route n'est pas trouvee |


<div id="helper-functions"></div>

## Fonctions d'aide

{{ config('app.name') }} v7 fournit des fonctions d'aide qui reproduisent les methodes de la classe `Auth` :

| Fonction d'aide | Equivalent |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Cle de stockage pour la session par defaut |
| `authDeviceId()` | `Auth.deviceId()` |

Tous les helpers acceptent les memes parametres que leurs equivalents de la classe `Auth`, y compris le parametre optionnel `session` :

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

