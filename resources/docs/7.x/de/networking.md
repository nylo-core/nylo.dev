# Networking

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- HTTP-Anfragen stellen
  - [Komfortmethoden](#convenience-methods "Komfortmethoden")
  - [Network-Helfer](#network-helper "Network-Helfer")
  - [networkResponse-Helfer](#network-response-helper "networkResponse-Helfer")
  - [NyResponse](#ny-response "NyResponse")
  - [Basis-Optionen](#base-options "Basis-Optionen")
  - [Header hinzufuegen](#adding-headers "Header hinzufuegen")
- Dateioperationen
  - [Dateien hochladen](#uploading-files "Dateien hochladen")
  - [Dateien herunterladen](#downloading-files "Dateien herunterladen")
- [Interceptors](#interceptors "Interceptors")
  - [Network Logger](#network-logger "Network Logger")
- [Einen API-Service verwenden](#using-an-api-service "Einen API-Service verwenden")
- [Einen API-Service erstellen](#create-an-api-service "Einen API-Service erstellen")
- [JSON in Models umwandeln](#morphing-json-payloads-to-models "JSON in Models umwandeln")
- Caching
  - [Antworten cachen](#caching-responses "Antworten cachen")
  - [Cache-Richtlinien](#cache-policies "Cache-Richtlinien")
- Fehlerbehandlung
  - [Fehlgeschlagene Anfragen wiederholen](#retrying-failed-requests "Fehlgeschlagene Anfragen wiederholen")
  - [Konnektivitaetspruefungen](#connectivity-checks "Konnektivitaetspruefungen")
  - [Cancel Tokens](#cancel-tokens "Cancel Tokens")
- Authentifizierung
  - [Auth-Header setzen](#setting-auth-headers "Auth-Header setzen")
  - [Tokens aktualisieren](#refreshing-tokens "Tokens aktualisieren")
- [Singleton API-Service](#singleton-api-service "Singleton API-Service")
- [Erweiterte Konfiguration](#advanced-configuration "Erweiterte Konfiguration")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} macht Networking einfach. Sie definieren API-Endpunkte in Service-Klassen, die `NyApiService` erweitern, und rufen sie dann von Ihren Seiten aus auf. Das Framework uebernimmt JSON-Dekodierung, Fehlerbehandlung, Caching und die automatische Konvertierung von Antworten in Ihre Model-Klassen (genannt "Morphing").

Ihre API-Services befinden sich in `lib/app/networking/`. Ein neues Projekt enthaelt einen Standard-`ApiService`:

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

Es gibt drei Moeglichkeiten, HTTP-Anfragen zu stellen:

| Ansatz | Rueckgabe | Am besten geeignet fuer |
|--------|---------|----------|
| Komfortmethoden (`get`, `post`, etc.) | `T?` | Einfache CRUD-Operationen |
| `network()` | `T?` | Anfragen, die Caching, Wiederholungen oder benutzerdefinierte Header benoetigen |
| `networkResponse()` | `NyResponse<T>` | Wenn Sie Statuscodes, Header oder Fehlerdetails benoetigen |

Unter der Haube verwendet {{ config('app.name') }} <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, einen leistungsstarken HTTP-Client.


<div id="convenience-methods"></div>

## Komfortmethoden

`NyApiService` bietet Kurzschreibmethoden fuer gaengige HTTP-Operationen. Diese rufen intern `network()` auf.

### GET-Anfrage

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST-Anfrage

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT-Anfrage

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE-Anfrage

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH-Anfrage

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD-Anfrage

Verwenden Sie HEAD, um die Existenz einer Ressource zu pruefen oder Header zu erhalten, ohne den Body herunterzuladen:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network-Helfer

Die `network`-Methode gibt Ihnen mehr Kontrolle als die Komfortmethoden. Sie gibt die umgewandelten Daten (`T?`) direkt zurueck.

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

Der `request`-Callback erhaelt eine <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>-Instanz mit Ihrer Basis-URL und Interceptors bereits konfiguriert.

### network-Parameter

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `request` | `Function(Dio)` | Die auszufuehrende HTTP-Anfrage (erforderlich) |
| `bearerToken` | `String?` | Bearer-Token fuer diese Anfrage |
| `baseUrl` | `String?` | Basis-URL des Services ueberschreiben |
| `headers` | `Map<String, dynamic>?` | Zusaetzliche Header |
| `retry` | `int?` | Anzahl der Wiederholungsversuche |
| `retryDelay` | `Duration?` | Verzoegerung zwischen Wiederholungen |
| `retryIf` | `bool Function(DioException)?` | Bedingung fuer Wiederholung |
| `connectionTimeout` | `Duration?` | Verbindungs-Timeout |
| `receiveTimeout` | `Duration?` | Empfangs-Timeout |
| `sendTimeout` | `Duration?` | Sende-Timeout |
| `cacheKey` | `String?` | Cache-Schluessel |
| `cacheDuration` | `Duration?` | Cache-Dauer |
| `cachePolicy` | `CachePolicy?` | Cache-Strategie |
| `checkConnectivity` | `bool?` | Konnektivitaet vor Anfrage pruefen |
| `handleSuccess` | `Function(NyResponse<T>)?` | Erfolgs-Callback |
| `handleFailure` | `Function(NyResponse<T>)?` | Fehler-Callback |


<div id="network-response-helper"></div>

## networkResponse-Helfer

Verwenden Sie `networkResponse`, wenn Sie Zugriff auf die vollstaendige Antwort benoetigen -- Statuscodes, Header, Fehlermeldungen -- nicht nur die Daten. Es gibt ein `NyResponse<T>` anstelle von `T?` zurueck.

Verwenden Sie `networkResponse`, wenn Sie:
- HTTP-Statuscodes fuer spezifische Behandlung pruefen muessen
- Auf Antwort-Header zugreifen muessen
- Detaillierte Fehlermeldungen fuer Benutzer-Feedback benoetigen
- Benutzerdefinierte Fehlerbehandlungslogik implementieren muessen

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Verwenden Sie dann die Antwort in Ihrer Seite:

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

Beide Methoden akzeptieren die gleichen Parameter. Waehlen Sie `networkResponse`, wenn Sie die Antwort ueber die reinen Daten hinaus inspizieren muessen.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` umhuellt die Dio-Antwort mit umgewandelten Daten und Status-Helfern.

### Eigenschaften

| Eigenschaft | Typ | Beschreibung |
|------------|------|-------------|
| `response` | `Response?` | Originale Dio-Antwort |
| `data` | `T?` | Umgewandelte/dekodierte Daten |
| `rawData` | `dynamic` | Rohe Antwortdaten |
| `headers` | `Headers?` | Antwort-Header |
| `statusCode` | `int?` | HTTP-Statuscode |
| `statusMessage` | `String?` | HTTP-Statusnachricht |
| `contentType` | `String?` | Content-Type aus den Headern |
| `errorMessage` | `String?` | Extrahierte Fehlermeldung |

### Status-Pruefungen

| Getter | Beschreibung |
|--------|-------------|
| `isSuccessful` | Status 200-299 |
| `isClientError` | Status 400-499 |
| `isServerError` | Status 500-599 |
| `isRedirect` | Status 300-399 |
| `hasData` | Daten sind nicht null |
| `isUnauthorized` | Status 401 |
| `isForbidden` | Status 403 |
| `isNotFound` | Status 404 |
| `isTimeout` | Status 408 |
| `isConflict` | Status 409 |
| `isRateLimited` | Status 429 |

### Daten-Helfer

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

## Basis-Optionen

Konfigurieren Sie Standard-Dio-Optionen fuer Ihren API-Service mit dem `baseOptions`-Parameter:

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

Sie koennen Optionen auch dynamisch auf einer Instanz konfigurieren:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Klicken Sie <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">hier</a>, um alle Basis-Optionen zu sehen, die Sie festlegen koennen.


<div id="adding-headers"></div>

## Header hinzufuegen

### Header pro Anfrage

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer-Token

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### Service-Level-Header

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders-Extension

Der `RequestHeaders`-Typ (ein `Map<String, dynamic>` Typedef) bietet Hilfsmethoden:

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

| Methode | Beschreibung |
|---------|-------------|
| `addBearerToken(token)` | Den `Authorization: Bearer`-Header setzen |
| `getBearerToken()` | Das Bearer-Token aus den Headern lesen |
| `addHeader(key, value)` | Einen benutzerdefinierten Header hinzufuegen |
| `hasHeader(key)` | Pruefen, ob ein Header existiert |


<div id="uploading-files"></div>

## Dateien hochladen

### Einzeldatei-Upload

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

### Mehrdatei-Upload

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

## Dateien herunterladen

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

## Interceptors

Interceptors ermoeglichen es Ihnen, Anfragen vor dem Senden zu modifizieren, Antworten zu verarbeiten und Fehler zu behandeln. Sie werden bei jeder Anfrage ausgefuehrt, die ueber den API-Service gemacht wird.

Verwenden Sie Interceptors, wenn Sie:
- Authentifizierungs-Header zu allen Anfragen hinzufuegen muessen
- Anfragen und Antworten fuer das Debugging loggen muessen
- Anfrage-/Antwortdaten global transformieren muessen
- Bestimmte Fehlercodes behandeln muessen (z.B. Tokens bei 401 erneuern)

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

### Einen benutzerdefinierten Interceptor erstellen

```bash
metro make:interceptor logging
```

**Datei:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

## Network Logger

{{ config('app.name') }} enthaelt einen eingebauten `NetworkLogger`-Interceptor. Er ist standardmaessig aktiviert, wenn `APP_DEBUG` in Ihrer Umgebung auf `true` gesetzt ist.

### Konfiguration

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

Sie koennen ihn deaktivieren, indem Sie `useNetworkLogger: false` setzen.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Logger deaktivieren
        );
```

### Log-Level

| Level | Beschreibung |
|-------|-------------|
| `LogLevelType.verbose` | Alle Anfrage-/Antwortdetails ausgeben |
| `LogLevelType.minimal` | Nur Methode, URL, Status und Zeit ausgeben |
| `LogLevelType.none` | Keine Logging-Ausgabe |

### Logs filtern

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Einen API-Service verwenden

Es gibt zwei Moeglichkeiten, Ihren API-Service von einer Seite aus aufzurufen.

### Direkte Instanziierung

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

### Den api()-Helfer verwenden

Der `api`-Helfer erstellt Instanzen unter Verwendung Ihrer `apiDecoders` aus `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Mit Callbacks:

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

### api()-Helfer-Parameter

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `request` | `Function(T)` | Die API-Anfragefunktion |
| `context` | `BuildContext?` | Build-Kontext |
| `headers` | `Map<String, dynamic>` | Zusaetzliche Header |
| `bearerToken` | `String?` | Bearer-Token |
| `baseUrl` | `String?` | Basis-URL ueberschreiben |
| `page` | `int?` | Paginierungsseite |
| `perPage` | `int?` | Elemente pro Seite |
| `retry` | `int` | Wiederholungsversuche |
| `retryDelay` | `Duration?` | Verzoegerung zwischen Wiederholungen |
| `onSuccess` | `Function(Response, dynamic)?` | Erfolgs-Callback |
| `onError` | `Function(DioException)?` | Fehler-Callback |
| `cacheKey` | `String?` | Cache-Schluessel |
| `cacheDuration` | `Duration?` | Cache-Dauer |


<div id="create-an-api-service"></div>

## Einen API-Service erstellen

Um einen neuen API-Service zu erstellen:

```bash
metro make:api_service user
```

Mit einem Model:

```bash
metro make:api_service user --model="User"
```

Dies erstellt einen API-Service mit CRUD-Methoden:

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

## JSON in Models umwandeln

"Morphing" ist der Begriff von {{ config('app.name') }} fuer die automatische Konvertierung von JSON-Antworten in Ihre Dart-Model-Klassen. Wenn Sie `network<User>(...)` verwenden, wird das JSON der Antwort durch Ihren Decoder geleitet, um eine `User`-Instanz zu erstellen -- kein manuelles Parsen noetig.

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

Die Decoder werden in `lib/bootstrap/decoders.dart` definiert:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Der Typparameter, den Sie an `network<T>()` uebergeben, wird mit Ihrer `modelDecoders`-Map abgeglichen, um den richtigen Decoder zu finden.

**Siehe auch:** [Decoders](/docs/{{$version}}/decoders#model-decoders) fuer Details zur Registrierung von Model-Decodern.


<div id="caching-responses"></div>

## Antworten cachen

Cachen Sie Antworten, um API-Aufrufe zu reduzieren und die Leistung zu verbessern. Caching ist nuetzlich fuer Daten, die sich nicht haeufig aendern, wie Laenderlisten, Kategorien oder Konfigurationen.

Geben Sie einen `cacheKey` und optional eine `cacheDuration` an:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Cache leeren

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Caching mit dem api()-Helfer

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Cache-Richtlinien

Verwenden Sie `CachePolicy` fuer feinkoernige Kontrolle ueber das Caching-Verhalten:

| Richtlinie | Beschreibung |
|------------|-------------|
| `CachePolicy.networkOnly` | Immer vom Netzwerk abrufen (Standard) |
| `CachePolicy.cacheFirst` | Zuerst Cache versuchen, bei Fehlen Netzwerk verwenden |
| `CachePolicy.networkFirst` | Zuerst Netzwerk versuchen, bei Fehler Cache verwenden |
| `CachePolicy.cacheOnly` | Nur Cache verwenden, Fehler wenn leer |
| `CachePolicy.staleWhileRevalidate` | Cache sofort zurueckgeben, im Hintergrund aktualisieren |

### Verwendung

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

### Wann welche Richtlinie verwenden

- **cacheFirst** -- Daten, die sich selten aendern. Gibt gecachte Daten sofort zurueck, ruft nur vom Netzwerk ab, wenn der Cache leer ist.
- **networkFirst** -- Daten, die moeglichst aktuell sein sollten. Versucht zuerst das Netzwerk, faellt bei Fehler auf Cache zurueck.
- **staleWhileRevalidate** -- UI, die eine sofortige Antwort benoetigt, aber aktuell bleiben sollte. Gibt gecachte Daten zurueck und aktualisiert dann im Hintergrund.
- **cacheOnly** -- Offline-Modus. Wirft einen Fehler, wenn keine gecachten Daten vorhanden sind.

> **Hinweis:** Wenn Sie einen `cacheKey` oder eine `cacheDuration` ohne Angabe einer `cachePolicy` angeben, ist die Standardrichtlinie `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Fehlgeschlagene Anfragen wiederholen

Wiederholen Sie automatisch fehlgeschlagene Anfragen.

### Einfache Wiederholung

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Wiederholung mit Verzoegerung

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Bedingte Wiederholung

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

### Service-Level-Wiederholung

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Konnektivitaetspruefungen

Schnell fehlschlagen, wenn das Geraet offline ist, anstatt auf ein Timeout zu warten.

### Service-Level

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Pro Anfrage

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dynamisch

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Wenn aktiviert und das Geraet offline ist:
- `networkFirst`-Richtlinie faellt auf Cache zurueck
- Andere Richtlinien werfen sofort `DioExceptionType.connectionError`


<div id="cancel-tokens"></div>

## Cancel Tokens

Verwalten und stornieren Sie ausstehende Anfragen.

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

## Auth-Header setzen

Ueberschreiben Sie `setAuthHeaders`, um Authentifizierungs-Header an jede Anfrage anzuhaengen. Diese Methode wird vor jeder Anfrage aufgerufen, wenn `shouldSetAuthHeaders` auf `true` steht (Standard).

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

### Auth-Header deaktivieren

Fuer oeffentliche Endpunkte, die keine Authentifizierung benoetigen:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Siehe auch:** [Authentifizierung](/docs/{{ $version }}/authentication) fuer Details zur Benutzerauthentifizierung und Token-Speicherung.


<div id="refreshing-tokens"></div>

## Tokens aktualisieren

Ueberschreiben Sie `shouldRefreshToken` und `refreshToken`, um den Token-Ablauf zu behandeln. Diese werden vor jeder Anfrage aufgerufen.

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

Der `dio`-Parameter in `refreshToken` ist eine neue Dio-Instanz, getrennt von der Hauptinstanz des Services, um Interceptor-Schleifen zu vermeiden.


<div id="singleton-api-service"></div>

## Singleton API-Service

Standardmaessig erstellt der `api`-Helfer jedes Mal eine neue Instanz. Um ein Singleton zu verwenden, uebergeben Sie eine Instanz anstelle einer Factory in `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Erweiterte Konfiguration

### Benutzerdefinierte Dio-Initialisierung

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

### Zugriff auf die Dio-Instanz

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Paginierungs-Helfer

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Event-Callbacks

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Ueberschreibbare Eigenschaften

| Eigenschaft | Typ | Standard | Beschreibung |
|------------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Basis-URL fuer alle Anfragen |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio-Interceptors |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Model-Decoder fuer JSON-Umwandlung |
| `shouldSetAuthHeaders` | `bool` | `true` | Ob `setAuthHeaders` vor Anfragen aufgerufen werden soll |
| `retry` | `int` | `0` | Standard-Wiederholungsversuche |
| `retryDelay` | `Duration` | `1 second` | Standard-Verzoegerung zwischen Wiederholungen |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Konnektivitaet vor Anfragen pruefen |
