# Authentication

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to authentication")
- Basics
  - [Authenticating Users](#authenticating-users "Authenticating users")
  - [Retrieving Auth Data](#retrieving-auth-data "Retrieving auth data")
  - [Updating Auth Data](#updating-auth-data "Updating auth data")
  - [Logging Out](#logging-out "Logging out")
  - [Checking Authentication](#checking-authentication "Checking authentication")
- Advanced
  - [Multiple Sessions](#multiple-sessions "Multiple sessions")
  - [Device ID](#device-id "Device ID")
  - [Syncing to Backpack](#syncing-to-backpack "Syncing to backpack")
- Route Configuration
  - [Initial Route](#initial-route "Initial route")
  - [Authenticated Route](#authenticated-route "Authenticated route")
  - [Preview Route](#preview-route "Preview route")
  - [Unknown Route](#unknown-route "Unknown route")
- [Helper Functions](#helper-functions "Helper functions")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides a comprehensive authentication system through the `Auth` class. It handles secure storage of user credentials, session management, and supports multiple named sessions for different auth contexts.

Auth data is stored securely and synced to Backpack (an in-memory key-value store) for fast, synchronous access throughout your app.

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

## Authenticating Users

Use `Auth.authenticate()` to store user session data:

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

### Real-World Example

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

## Retrieving Auth Data

Get stored auth data using `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

The `Auth.data()` method reads from Backpack ({{ config('app.name') }}'s in-memory key-value store) for fast synchronous access. Data is automatically synced to Backpack when you authenticate.


<div id="updating-auth-data"></div>

## Updating Auth Data

{{ config('app.name') }} v7 introduces `Auth.set()` to update auth data:

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

## Logging Out

Remove the authenticated user with `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Logout from All Sessions

When using multiple sessions, clear them all:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Checking Authentication

Check if a user is currently authenticated:

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

## Multiple Sessions

{{ config('app.name') }} v7 supports multiple named auth sessions for different contexts. This is useful when you need to track different types of authentication separately (e.g., user login vs device registration vs admin access).

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

### Reading from Named Sessions

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

### Session-Specific Logout

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Check Authentication per Session

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Device ID

{{ config('app.name') }} v7 provides a unique device identifier that persists across app sessions:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

The device ID is:
- Generated once and stored permanently
- Unique to each device/installation
- Useful for device registration, analytics, or push notifications

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

## Syncing to Backpack

Auth data is automatically synced to Backpack when you authenticate. To manually sync (e.g., on app boot):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

This is useful in your app's boot sequence to ensure auth data is available in Backpack for fast synchronous access.


<div id="initial-route"></div>

## Initial Route

The initial route is the first page users see when they open your app. Set it using `.initialRoute()` in your router:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

You can also set a conditional initial route using the `when` parameter:

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

Navigate back to the initial route from anywhere using `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Authenticated Route

The authenticated route overrides the initial route when a user is logged in. Set it using `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

When the app boots:
- `Auth.isAuthenticated()` returns `true` → user sees the **authenticated route** (HomePage)
- `Auth.isAuthenticated()` returns `false` → user sees the **initial route** (LoginPage)

You can also set a conditional authenticated route:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Navigate to the authenticated route programmatically using `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**See also:** [Router](/docs/{{ $version }}/router) for full routing documentation including guards and deep linking.


<div id="preview-route"></div>

## Preview Route

During development, you may want to quickly preview a specific page without changing your initial or authenticated route. Use `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` overrides **both** `initialRoute()` and `authenticatedRoute()`, making the specified route the first page shown regardless of auth state.

> **Warning:** Remove `.previewRoute()` before releasing your app.


<div id="unknown-route"></div>

## Unknown Route

Define a fallback page for when a user navigates to a route that doesn't exist. Set it using `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Putting It All Together

Here's a complete router setup with all route types:

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

| Route Method | Purpose |
|--------------|---------|
| `.initialRoute()` | First page shown to unauthenticated users |
| `.authenticatedRoute()` | First page shown to authenticated users |
| `.previewRoute()` | Overrides both during development |
| `.unknownRoute()` | Shown when a route is not found |


<div id="helper-functions"></div>

## Helper Functions

{{ config('app.name') }} v7 provides helper functions that mirror the `Auth` class methods:

| Helper Function | Equivalent |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Storage key for the default session |
| `authDeviceId()` | `Auth.deviceId()` |

All helpers accept the same parameters as their `Auth` class counterparts, including the optional `session` parameter:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

