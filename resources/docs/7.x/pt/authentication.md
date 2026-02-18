# Authentication

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução à autenticação")
- Básico
  - [Autenticando Usuários](#authenticating-users "Autenticando usuários")
  - [Recuperando Dados de Auth](#retrieving-auth-data "Recuperando dados de auth")
  - [Atualizando Dados de Auth](#updating-auth-data "Atualizando dados de auth")
  - [Fazendo Logout](#logging-out "Fazendo logout")
  - [Verificando Autenticação](#checking-authentication "Verificando autenticação")
- Avançado
  - [Múltiplas Sessões](#multiple-sessions "Múltiplas sessões")
  - [Device ID](#device-id "Device ID")
  - [Sincronizando com o Backpack](#syncing-to-backpack "Sincronizando com o Backpack")
- Configuração de Rotas
  - [Rota Inicial](#initial-route "Rota inicial")
  - [Rota Autenticada](#authenticated-route "Rota autenticada")
  - [Rota de Preview](#preview-route "Rota de preview")
  - [Rota Desconhecida](#unknown-route "Rota desconhecida")
- [Funções Auxiliares](#helper-functions "Funções auxiliares")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 fornece um sistema de autenticação abrangente através da classe `Auth`. Ele lida com armazenamento seguro de credenciais do usuário, gerenciamento de sessão e suporta múltiplas sessões nomeadas para diferentes contextos de autenticação.

Os dados de autenticação são armazenados de forma segura e sincronizados com o Backpack (um armazenamento chave-valor em memória) para acesso rápido e síncrono em todo o seu app.

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

## Autenticando Usuários

Use `Auth.authenticate()` para armazenar dados de sessão do usuário:

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

### Exemplo do Mundo Real

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

## Recuperando Dados de Auth

Obtenha dados de autenticação armazenados usando `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

O método `Auth.data()` lê do Backpack (o armazenamento chave-valor em memória do {{ config('app.name') }}) para acesso síncrono rápido. Os dados são automaticamente sincronizados com o Backpack quando você autentica.


<div id="updating-auth-data"></div>

## Atualizando Dados de Auth

{{ config('app.name') }} v7 introduz `Auth.set()` para atualizar dados de autenticação:

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

## Fazendo Logout

Remova o usuário autenticado com `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Logout de Todas as Sessões

Ao usar múltiplas sessões, limpe todas:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Verificando Autenticação

Verifique se um usuário está atualmente autenticado:

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

## Múltiplas Sessões

{{ config('app.name') }} v7 suporta múltiplas sessões de autenticação nomeadas para diferentes contextos. Isso é útil quando você precisa rastrear diferentes tipos de autenticação separadamente (ex: login de usuário vs registro de dispositivo vs acesso de administrador).

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

### Lendo de Sessões Nomeadas

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

### Logout por Sessão Específica

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Verificar Autenticação por Sessão

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Device ID

{{ config('app.name') }} v7 fornece um identificador único de dispositivo que persiste entre sessões do app:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

O Device ID é:
- Gerado uma vez e armazenado permanentemente
- Único para cada dispositivo/instalação
- Útil para registro de dispositivo, analytics ou notificações push

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

## Sincronizando com o Backpack

Os dados de autenticação são automaticamente sincronizados com o Backpack quando você autentica. Para sincronizar manualmente (ex: na inicialização do app):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Isso é útil na sequência de inicialização do seu app para garantir que os dados de autenticação estejam disponíveis no Backpack para acesso síncrono rápido.


<div id="initial-route"></div>

## Rota Inicial

A rota inicial é a primeira página que os usuários veem quando abrem seu app. Defina-a usando `.initialRoute()` no seu router:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Você também pode definir uma rota inicial condicional usando o parâmetro `when`:

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

Navegue de volta para a rota inicial de qualquer lugar usando `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Rota Autenticada

A rota autenticada substitui a rota inicial quando um usuário está logado. Defina-a usando `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Quando o app inicia:
- `Auth.isAuthenticated()` retorna `true` → o usuário vê a **rota autenticada** (HomePage)
- `Auth.isAuthenticated()` retorna `false` → o usuário vê a **rota inicial** (LoginPage)

Você também pode definir uma rota autenticada condicional:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Navegue para a rota autenticada programaticamente usando `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Veja também:** [Router](/docs/{{ $version }}/router) para documentação completa de roteamento incluindo guards e deep linking.


<div id="preview-route"></div>

## Rota de Preview

Durante o desenvolvimento, você pode querer visualizar rapidamente uma página específica sem alterar sua rota inicial ou autenticada. Use `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` substitui **ambas** `initialRoute()` e `authenticatedRoute()`, fazendo com que a rota especificada seja a primeira página exibida independentemente do estado de autenticação.

> **Aviso:** Remova `.previewRoute()` antes de publicar seu app.


<div id="unknown-route"></div>

## Rota Desconhecida

Defina uma página de fallback para quando um usuário navega para uma rota que não existe. Defina-a usando `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Juntando Tudo

Aqui está uma configuração completa do router com todos os tipos de rota:

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

| Método de Rota | Propósito |
|--------------|---------|
| `.initialRoute()` | Primeira página exibida para usuários não autenticados |
| `.authenticatedRoute()` | Primeira página exibida para usuários autenticados |
| `.previewRoute()` | Substitui ambas durante o desenvolvimento |
| `.unknownRoute()` | Exibida quando uma rota não é encontrada |


<div id="helper-functions"></div>

## Funções Auxiliares

{{ config('app.name') }} v7 fornece funções auxiliares que espelham os métodos da classe `Auth`:

| Função Auxiliar | Equivalente |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Chave de armazenamento para a sessão padrão |
| `authDeviceId()` | `Auth.deviceId()` |

Todas as funções auxiliares aceitam os mesmos parâmetros que seus equivalentes na classe `Auth`, incluindo o parâmetro opcional `session`:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
