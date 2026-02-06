# Reseau

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Effectuer des requetes HTTP
  - [Methodes pratiques](#convenience-methods "Methodes pratiques")
  - [Helper Network](#network-helper "Helper Network")
  - [Helper networkResponse](#network-response-helper "Helper networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Options de base](#base-options "Options de base")
  - [Ajouter des en-tetes](#adding-headers "Ajouter des en-tetes")
- Operations sur les fichiers
  - [Envoi de fichiers](#uploading-files "Envoi de fichiers")
  - [Telechargement de fichiers](#downloading-files "Telechargement de fichiers")
- [Intercepteurs](#interceptors "Intercepteurs")
  - [Journal reseau](#network-logger "Journal reseau")
- [Utiliser un service API](#using-an-api-service "Utiliser un service API")
- [Creer un service API](#create-an-api-service "Creer un service API")
- [Transformation du JSON en modeles](#morphing-json-payloads-to-models "Transformation du JSON en modeles")
- Mise en cache
  - [Mise en cache des reponses](#caching-responses "Mise en cache des reponses")
  - [Politiques de cache](#cache-policies "Politiques de cache")
- Gestion des erreurs
  - [Relancer les requetes echouees](#retrying-failed-requests "Relancer les requetes echouees")
  - [Verification de la connectivite](#connectivity-checks "Verification de la connectivite")
  - [Jetons d'annulation](#cancel-tokens "Jetons d'annulation")
- Authentification
  - [Definir les en-tetes d'authentification](#setting-auth-headers "Definir les en-tetes d'authentification")
  - [Rafraichir les jetons](#refreshing-tokens "Rafraichir les jetons")
- [Service API singleton](#singleton-api-service "Service API singleton")
- [Configuration avancee](#advanced-configuration "Configuration avancee")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} rend le reseau simple. Vous definissez les points d'acces API dans des classes de service qui etendent `NyApiService`, puis vous les appelez depuis vos pages. Le framework gere le decodage JSON, la gestion des erreurs, la mise en cache et la conversion automatique des reponses en classes de modeles (appelee "morphing").

Vos services API se trouvent dans `lib/app/networking/`. Un nouveau projet inclut un `ApiService` par defaut :

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
        );

  @override
  String get baseUrl => getEnv('API_BASE_URL');

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
  };

  Future fetchUsers() async {
    return await network(
      request: (request) => request.get("/users"),
    );
  }
}
```

Il existe trois facons d'effectuer des requetes HTTP :

| Approche | Retourne | Ideal pour |
|----------|---------|----------|
| Methodes pratiques (`get`, `post`, etc.) | `T?` | Operations CRUD simples |
| `network()` | `T?` | Requetes necessitant mise en cache, relances ou en-tetes personnalises |
| `networkResponse()` | `NyResponse<T>` | Quand vous avez besoin des codes de statut, en-tetes ou details d'erreur |

En arriere-plan, {{ config('app.name') }} utilise <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, un client HTTP puissant.


<div id="convenience-methods"></div>

## Methodes pratiques

`NyApiService` fournit des methodes raccourcies pour les operations HTTP courantes. Elles appellent `network()` en interne.

### Requete GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Requete POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Requete PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Requete DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Requete PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Requete HEAD

Utilisez HEAD pour verifier l'existence d'une ressource ou obtenir les en-tetes sans telecharger le corps :

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Helper Network

La methode `network` vous donne plus de controle que les methodes pratiques. Elle retourne directement les donnees transformees (`T?`).

```dart
class ApiService extends NyApiService {
  ...

  Future<User?> fetchUser(int id) async {
    return await network<User>(
      request: (request) => request.get("/users/$id"),
    );
  }

  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }

  Future<User?> createUser(Map<String, dynamic> data) async {
    return await network<User>(
      request: (request) => request.post("/users", data: data),
    );
  }
}
```

Le callback `request` recoit une instance <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> avec votre URL de base et vos intercepteurs deja configures.

### Parametres de network

| Parametre | Type | Description |
|-----------|------|-------------|
| `request` | `Function(Dio)` | La requete HTTP a effectuer (requis) |
| `bearerToken` | `String?` | Jeton Bearer pour cette requete |
| `baseUrl` | `String?` | Remplacer l'URL de base du service |
| `headers` | `Map<String, dynamic>?` | En-tetes supplementaires |
| `retry` | `int?` | Nombre de tentatives de relance |
| `retryDelay` | `Duration?` | Delai entre les relances |
| `retryIf` | `bool Function(DioException)?` | Condition pour la relance |
| `connectionTimeout` | `Duration?` | Delai de connexion |
| `receiveTimeout` | `Duration?` | Delai de reception |
| `sendTimeout` | `Duration?` | Delai d'envoi |
| `cacheKey` | `String?` | Cle de cache |
| `cacheDuration` | `Duration?` | Duree du cache |
| `cachePolicy` | `CachePolicy?` | Strategie de cache |
| `checkConnectivity` | `bool?` | Verifier la connectivite avant la requete |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback de succes |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback d'echec |


<div id="network-response-helper"></div>

## Helper networkResponse

Utilisez `networkResponse` lorsque vous avez besoin d'acceder a la reponse complete — codes de statut, en-tetes, messages d'erreur — pas seulement les donnees. Il retourne un `NyResponse<T>` au lieu de `T?`.

Utilisez `networkResponse` lorsque vous devez :
- Verifier les codes de statut HTTP pour un traitement specifique
- Acceder aux en-tetes de la reponse
- Obtenir des messages d'erreur detailles pour le retour utilisateur
- Implementer une logique de gestion d'erreur personnalisee

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Puis utilisez la reponse dans votre page :

```dart
NyResponse<User> response = await _apiService.fetchUser(1);

if (response.isSuccessful) {
  User? user = response.data;
  print('Status: ${response.statusCode}');
} else {
  print('Error: ${response.errorMessage}');
  print('Status: ${response.statusCode}');
}
```

### network vs networkResponse

```dart
// network() — returns the data directly
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — returns the full response
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Les deux methodes acceptent les memes parametres. Choisissez `networkResponse` lorsque vous devez inspecter la reponse au-dela des donnees seules.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` encapsule la reponse Dio avec les donnees transformees et des aides de statut.

### Proprietes

| Propriete | Type | Description |
|----------|------|-------------|
| `response` | `Response?` | Reponse Dio originale |
| `data` | `T?` | Donnees transformees/decodees |
| `rawData` | `dynamic` | Donnees brutes de la reponse |
| `headers` | `Headers?` | En-tetes de la reponse |
| `statusCode` | `int?` | Code de statut HTTP |
| `statusMessage` | `String?` | Message de statut HTTP |
| `contentType` | `String?` | Type de contenu depuis les en-tetes |
| `errorMessage` | `String?` | Message d'erreur extrait |

### Verifications de statut

| Getter | Description |
|--------|-------------|
| `isSuccessful` | Statut 200-299 |
| `isClientError` | Statut 400-499 |
| `isServerError` | Statut 500-599 |
| `isRedirect` | Statut 300-399 |
| `hasData` | Les donnees ne sont pas null |
| `isUnauthorized` | Statut 401 |
| `isForbidden` | Statut 403 |
| `isNotFound` | Statut 404 |
| `isTimeout` | Statut 408 |
| `isConflict` | Statut 409 |
| `isRateLimited` | Statut 429 |

### Aides pour les donnees

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Get data or throw if null
User user = response.dataOrThrow('User not found');

// Get data or use a fallback
User user = response.dataOr(User.guest());

// Run callback only if successful
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Pattern match on success/failure
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Get a specific header
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Options de base

Configurez les options Dio par defaut pour votre service API en utilisant le parametre `baseOptions` :

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    baseOptions: (BaseOptions baseOptions) {
      return baseOptions
        ..connectTimeout = Duration(seconds: 5)
        ..sendTimeout = Duration(seconds: 5)
        ..receiveTimeout = Duration(seconds: 5);
    },
  );
  ...
}
```

Vous pouvez egalement configurer les options dynamiquement sur une instance :

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Cliquez <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">ici</a> pour voir toutes les options de base que vous pouvez definir.


<div id="adding-headers"></div>

## Ajouter des en-tetes

### En-tetes par requete

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Jeton Bearer

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### En-tetes au niveau du service

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Extension RequestHeaders

Le type `RequestHeaders` (un typedef `Map<String, dynamic>`) fournit des methodes d'aide :

```dart
@override
Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
  String? token = Auth.data(field: 'token');
  if (token != null) {
    headers.addBearerToken(token);
  }
  headers.addHeader('X-App-Version', '1.0.0');
  return headers;
}
```

| Methode | Description |
|---------|-------------|
| `addBearerToken(token)` | Definir l'en-tete `Authorization: Bearer` |
| `getBearerToken()` | Lire le jeton Bearer depuis les en-tetes |
| `addHeader(key, value)` | Ajouter un en-tete personnalise |
| `hasHeader(key)` | Verifier si un en-tete existe |


<div id="uploading-files"></div>

## Envoi de fichiers

### Envoi d'un seul fichier

```dart
Future<UploadResponse?> uploadAvatar(String filePath) async {
  return await upload<UploadResponse>(
    '/upload',
    filePath: filePath,
    fieldName: 'avatar',
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      double progress = sent / total * 100;
      print('Progress: ${progress.toStringAsFixed(0)}%');
    },
  );
}
```

### Envoi de plusieurs fichiers

```dart
Future<UploadResponse?> uploadDocuments() async {
  return await uploadMultiple<UploadResponse>(
    '/upload',
    files: {
      'avatar': '/path/to/avatar.jpg',
      'document': '/path/to/doc.pdf',
    },
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      print('Progress: ${(sent / total * 100).toStringAsFixed(0)}%');
    },
  );
}
```


<div id="downloading-files"></div>

## Telechargement de fichiers

```dart
Future<void> downloadFile(String url, String savePath) async {
  await download(
    url,
    savePath: savePath,
    onProgress: (received, total) {
      if (total != -1) {
        print('Progress: ${(received / total * 100).toStringAsFixed(0)}%');
      }
    },
    deleteOnError: true,
  );
}
```


<div id="interceptors"></div>

## Intercepteurs

Les intercepteurs vous permettent de modifier les requetes avant leur envoi, de gerer les reponses et de gerer les erreurs. Ils s'executent sur chaque requete effectuee via le service API.

Utilisez les intercepteurs lorsque vous devez :
- Ajouter des en-tetes d'authentification a toutes les requetes
- Journaliser les requetes et reponses pour le debogage
- Transformer les donnees de requete/reponse globalement
- Gerer des codes d'erreur specifiques (ex. rafraichir les jetons sur 401)

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
    BearerAuthInterceptor: BearerAuthInterceptor(),
    LoggingInterceptor: LoggingInterceptor(),
  };
  ...
}
```

### Creer un intercepteur personnalise

```bash
metro make:interceptor logging
```

**Fichier :** `app/networking/dio/interceptors/logging_interceptor.dart`

```dart
import 'package:nylo_framework/nylo_framework.dart';

class LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    print('REQUEST[${options.method}] => PATH: ${options.path}');
    return super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    print('RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions.path}');
    handler.next(response);
  }

  @override
  void onError(DioException dioException, ErrorInterceptorHandler handler) {
    print('ERROR[${dioException.response?.statusCode}] => PATH: ${dioException.requestOptions.path}');
    handler.next(dioException);
  }
}
```


<div id="network-logger"></div>

## Journal reseau

{{ config('app.name') }} inclut un intercepteur `NetworkLogger` integre. Il est active par defaut lorsque `APP_DEBUG` est `true` dans votre environnement.

### Configuration

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    useNetworkLogger: true,
    networkLogger: NetworkLogger(
      logLevel: LogLevelType.verbose,
      request: true,
      requestHeader: true,
      requestBody: true,
      responseBody: true,
      responseHeader: false,
      error: true,
    ),
  );
}
```

Vous pouvez le desactiver en definissant `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Desactiver le journal
        );
```

### Niveaux de journalisation

| Niveau | Description |
|--------|-------------|
| `LogLevelType.verbose` | Afficher tous les details de la requete/reponse |
| `LogLevelType.minimal` | Afficher uniquement la methode, l'URL, le statut et le temps |
| `LogLevelType.none` | Aucune sortie de journalisation |

### Filtrage des journaux

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Utiliser un service API

Il existe deux facons d'appeler votre service API depuis une page.

### Instanciation directe

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  ApiService _apiService = ApiService();

  @override
  get init => () async {
    List<User>? users = await _apiService.fetchUsers();
    print(users);
  };
}
```

### Utiliser le helper api()

Le helper `api` cree des instances en utilisant vos `apiDecoders` depuis `config/decoders.dart` :

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Avec des callbacks :

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data is the morphed User? instance
  },
  onError: (DioException dioException) {
    // Handle the error
  },
);
```

### Parametres du helper api()

| Parametre | Type | Description |
|-----------|------|-------------|
| `request` | `Function(T)` | La fonction de requete API |
| `context` | `BuildContext?` | Contexte de construction |
| `headers` | `Map<String, dynamic>` | En-tetes supplementaires |
| `bearerToken` | `String?` | Jeton Bearer |
| `baseUrl` | `String?` | Remplacer l'URL de base |
| `page` | `int?` | Page de pagination |
| `perPage` | `int?` | Elements par page |
| `retry` | `int` | Tentatives de relance |
| `retryDelay` | `Duration?` | Delai entre les relances |
| `onSuccess` | `Function(Response, dynamic)?` | Callback de succes |
| `onError` | `Function(DioException)?` | Callback d'erreur |
| `cacheKey` | `String?` | Cle de cache |
| `cacheDuration` | `Duration?` | Duree du cache |


<div id="create-an-api-service"></div>

## Creer un service API

Pour creer un nouveau service API :

```bash
metro make:api_service user
```

Avec un modele :

```bash
metro make:api_service user --model="User"
```

Cela cree un service API avec des methodes CRUD :

```dart
class UserApiService extends NyApiService {
  ...

  Future<List<User>?> fetchAll({dynamic query}) async {
    return await network<List<User>>(
      request: (request) => request.get("/endpoint-path", queryParameters: query),
    );
  }

  Future<User?> find({required int id}) async {
    return await network<User>(
      request: (request) => request.get("/endpoint-path/$id"),
    );
  }

  Future<User?> create({required dynamic data}) async {
    return await network<User>(
      request: (request) => request.post("/endpoint-path", data: data),
    );
  }

  Future<User?> update({dynamic query}) async {
    return await network<User>(
      request: (request) => request.put("/endpoint-path", queryParameters: query),
    );
  }

  Future<bool?> delete({required int id}) async {
    return await network<bool>(
      request: (request) => request.delete("/endpoint-path/$id"),
    );
  }
}
```


<div id="morphing-json-payloads-to-models"></div>

## Transformation du JSON en modeles

Le "morphing" est le terme de {{ config('app.name') }} pour la conversion automatique des reponses JSON en classes de modeles Dart. Lorsque vous utilisez `network<User>(...)`, le JSON de la reponse est passe a travers votre decodeur pour creer une instance `User` — aucun parsing manuel necessaire.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Returns a single User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Returns a List of Users
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Les decodeurs sont definis dans `lib/bootstrap/decoders.dart` :

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Le parametre de type que vous passez a `network<T>()` est compare a votre map `modelDecoders` pour trouver le bon decodeur.

**Voir aussi :** [Decoders](/docs/{{$version}}/decoders#model-decoders) pour les details sur l'enregistrement des decodeurs de modeles.


<div id="caching-responses"></div>

## Mise en cache des reponses

Mettez en cache les reponses pour reduire les appels API et ameliorer les performances. La mise en cache est utile pour les donnees qui changent rarement, comme les listes de pays, les categories ou la configuration.

Fournissez une `cacheKey` et une `cacheDuration` optionnelle :

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Vider le cache

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Mise en cache avec le helper api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Politiques de cache

Utilisez `CachePolicy` pour un controle fin du comportement de mise en cache :

| Politique | Description |
|-----------|-------------|
| `CachePolicy.networkOnly` | Toujours recuperer depuis le reseau (par defaut) |
| `CachePolicy.cacheFirst` | Essayer le cache d'abord, se rabattre sur le reseau |
| `CachePolicy.networkFirst` | Essayer le reseau d'abord, se rabattre sur le cache |
| `CachePolicy.cacheOnly` | Utiliser uniquement le cache, erreur si vide |
| `CachePolicy.staleWhileRevalidate` | Retourner le cache immediatement, mettre a jour en arriere-plan |

### Utilisation

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
    cachePolicy: CachePolicy.staleWhileRevalidate,
  ) ?? [];
}
```

### Quand utiliser chaque politique

- **cacheFirst** — Donnees qui changent rarement. Retourne les donnees en cache instantanement, ne recupere depuis le reseau que si le cache est vide.
- **networkFirst** — Donnees qui doivent etre fraiches quand c'est possible. Essaie le reseau d'abord, se rabat sur le cache en cas d'echec.
- **staleWhileRevalidate** — Interface qui necessite une reponse immediate mais doit rester a jour. Retourne les donnees en cache, puis les rafraichit en arriere-plan.
- **cacheOnly** — Mode hors ligne. Lance une erreur si aucune donnee en cache n'existe.

> **Remarque :** Si vous fournissez une `cacheKey` ou `cacheDuration` sans specifier de `cachePolicy`, la politique par defaut est `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Relancer les requetes echouees

Relancez automatiquement les requetes qui echouent.

### Relance basique

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Relance avec delai

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Relance conditionnelle

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // Only retry on server errors
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Relance au niveau du service

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Verification de la connectivite

Echouez rapidement lorsque l'appareil est hors ligne au lieu d'attendre un delai d'expiration.

### Au niveau du service

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Par requete

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dynamique

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Lorsque c'est active et que l'appareil est hors ligne :
- La politique `networkFirst` se rabat sur le cache
- Les autres politiques lancent immediatement `DioExceptionType.connectionError`


<div id="cancel-tokens"></div>

## Jetons d'annulation

Gerez et annulez les requetes en attente.

```dart
// Create a managed cancel token
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Cancel all pending requests (e.g., on logout)
apiService.cancelAllRequests('User logged out');

// Check active request count
int count = apiService.activeRequestCount;

// Clean up a specific token when done
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## Definir les en-tetes d'authentification

Remplacez `setAuthHeaders` pour attacher des en-tetes d'authentification a chaque requete. Cette methode est appelee avant chaque requete lorsque `shouldSetAuthHeaders` est `true` (la valeur par defaut).

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
    String? myAuthToken = Auth.data(field: 'token');
    if (myAuthToken != null) {
      headers.addBearerToken(myAuthToken);
    }
    return headers;
  }
}
```

### Desactiver les en-tetes d'authentification

Pour les points d'acces publics qui n'ont pas besoin d'authentification :

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Voir aussi :** [Authentification](/docs/{{ $version }}/authentication) pour les details sur l'authentification des utilisateurs et le stockage des jetons.


<div id="refreshing-tokens"></div>

## Rafraichir les jetons

Remplacez `shouldRefreshToken` et `refreshToken` pour gerer l'expiration des jetons. Ils sont appeles avant chaque requete.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Check if the token needs refreshing
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Use the fresh Dio instance (no interceptors) to refresh the token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Save the new token to storage
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

Le parametre `dio` dans `refreshToken` est une nouvelle instance Dio, separee de l'instance principale du service, pour eviter les boucles d'intercepteurs.


<div id="singleton-api-service"></div>

## Service API singleton

Par defaut, le helper `api` cree une nouvelle instance a chaque fois. Pour utiliser un singleton, passez une instance au lieu d'une factory dans `config/decoders.dart` :

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Configuration avancee

### Initialisation Dio personnalisee

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    initDio: (Dio dio) {
      dio.options.validateStatus = (status) => status! < 500;
      return dio;
    },
  );
}
```

### Acceder a l'instance Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Aide a la pagination

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callbacks d'evenements

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Proprietes remplacables

| Propriete | Type | Par defaut | Description |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | URL de base pour toutes les requetes |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Intercepteurs Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Decodeurs de modeles pour la transformation JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Si `setAuthHeaders` doit etre appele avant les requetes |
| `retry` | `int` | `0` | Tentatives de relance par defaut |
| `retryDelay` | `Duration` | `1 second` | Delai par defaut entre les relances |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Verifier la connectivite avant les requetes |
