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
  - [Header hinzufügen](#adding-headers "Header hinzufügen")
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
  - [Konnektivitätsprüfungen](#connectivity-checks "Konnektivitätsprüfungen")
  - [Cancel Tokens](#cancel-tokens "Cancel Tokens")
- Authentifizierung
  - [Auth-Header setzen](#setting-auth-headers "Auth-Header setzen")
  - [Tokens aktualisieren](#refreshing-tokens "Tokens aktualisieren")
- [Singleton API-Service](#singleton-api-service "Singleton API-Service")
- [Erweiterte Konfiguration](#advanced-configuration "Erweiterte Konfiguration")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} macht Networking einfach. Sie definieren API-Endpunkte in Service-Klassen, die `NyApiService` erweitern, und rufen sie dann von Ihren Seiten aus auf. Das Framework übernimmt JSON-Dekodierung, Fehlerbehandlung, Caching und die automatische Konvertierung von Antworten in Ihre Model-Klassen (genannt "Morphing").

Ihre API-Services befinden sich in `lib/app/networking/`. Ein neues Projekt enthält einen Standard-`ApiService`:

```dart
class ApiService extends NyApiService {
  ApiService() : super(decoders: modelDecoders);

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

Es gibt drei Möglichkeiten, HTTP-Anfragen zu stellen:

| Ansatz | Rückgabe | Am besten geeignet für |
|--------|---------|----------|
| Komfortmethoden (`get`, `post`, etc.) | `T?` | Einfache CRUD-Operationen |
| `network()` | `T?` | Anfragen, die Caching, Wiederholungen oder benutzerdefinierte Header benötigen |
| `networkResponse()` | `NyResponse<T>` | Wenn Sie Statuscodes, Header oder Fehlerdetails benötigen |

Unter der Haube verwendet {{ config('app.name') }} <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, einen leistungsstarken HTTP-Client.


<div id="convenience-methods"></div>

## Komfortmethoden

`NyApiService` bietet Kurzschreibmethoden für gängige HTTP-Operationen. Diese rufen intern `network()` auf.

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

Verwenden Sie HEAD, um die Existenz einer Ressource zu prüfen oder Header zu erhalten, ohne den Body herunterzuladen:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network-Helfer

Die `network`-Methode gibt Ihnen mehr Kontrolle als die Komfortmethoden. Sie gibt die umgewandelten Daten (`T?`) direkt zurück.

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

Der `request`-Callback erhält eine <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>-Instanz mit Ihrer Basis-URL und Interceptors bereits konfiguriert.

### network-Parameter

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `request` | `Function(Dio)` | Die auszuführende HTTP-Anfrage (erforderlich) |
| `bearerToken` | `String?` | Bearer-Token für diese Anfrage |
| `baseUrl` | `String?` | Basis-URL des Services überschreiben |
| `headers` | `Map<String, dynamic>?` | Zusätzliche Header |
| `retry` | `int?` | Anzahl der Wiederholungsversuche |
| `retryDelay` | `Duration?` | Verzögerung zwischen Wiederholungen |
| `retryIf` | `bool Function(DioException)?` | Bedingung für Wiederholung |
| `connectionTimeout` | `Duration?` | Verbindungs-Timeout |
| `receiveTimeout` | `Duration?` | Empfangs-Timeout |
| `sendTimeout` | `Duration?` | Sende-Timeout |
| `cacheKey` | `String?` | Cache-Schlüssel |
| `cacheDuration` | `Duration?` | Cache-Dauer |
| `cachePolicy` | `CachePolicy?` | Cache-Strategie |
| `checkConnectivity` | `bool?` | Konnektivität vor Anfrage prüfen |
| `handleSuccess` | `Function(NyResponse<T>)?` | Erfolgs-Callback |
| `handleFailure` | `Function(NyResponse<T>)?` | Fehler-Callback |


<div id="network-response-helper"></div>

## networkResponse-Helfer

Verwenden Sie `networkResponse`, wenn Sie Zugriff auf die vollständige Antwort benötigen -- Statuscodes, Header, Fehlermeldungen -- nicht nur die Daten. Es gibt ein `NyResponse<T>` anstelle von `T?` zurück.

Verwenden Sie `networkResponse`, wenn Sie:
- HTTP-Statuscodes für spezifische Behandlung prüfen müssen
- Auf Antwort-Header zugreifen müssen
- Detaillierte Fehlermeldungen für Benutzer-Feedback benötigen
- Benutzerdefinierte Fehlerbehandlungslogik implementieren müssen

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

Beide Methoden akzeptieren die gleichen Parameter. Wählen Sie `networkResponse`, wenn Sie die Antwort über die reinen Daten hinaus inspizieren müssen.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` umhüllt die Dio-Antwort mit umgewandelten Daten und Status-Helfern.

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

### Status-Prüfungen

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

// Daten abrufen oder Fehler werfen, wenn null
User user = response.dataOrThrow('User not found');

// Daten abrufen oder Fallback verwenden
User user = response.dataOr(User.guest());

// Callback nur bei Erfolg ausführen
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Erfolg/Fehler per Pattern-Matching behandeln
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Einen bestimmten Header abrufen
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Basis-Optionen

Konfigurieren Sie Standard-Dio-Optionen für Ihren API-Service mit dem `baseOptions`-Parameter:

```dart
class ApiService extends NyApiService {
  ApiService() : super(
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

Sie können Optionen auch dynamisch auf einer Instanz konfigurieren:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Klicken Sie <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">hier</a>, um alle Basis-Optionen zu sehen, die Sie festlegen können.


<div id="adding-headers"></div>

## Header hinzufügen

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
| `addHeader(key, value)` | Einen benutzerdefinierten Header hinzufügen |
| `hasHeader(key)` | Prüfen, ob ein Header existiert |


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

Interceptors ermöglichen es Ihnen, Anfragen vor dem Senden zu modifizieren, Antworten zu verarbeiten und Fehler zu behandeln. Sie werden bei jeder Anfrage ausgeführt, die über den API-Service gemacht wird.

Verwenden Sie Interceptors, wenn Sie:
- Authentifizierungs-Header zu allen Anfragen hinzufügen müssen
- Anfragen und Antworten für das Debugging loggen müssen
- Anfrage-/Antwortdaten global transformieren müssen
- Bestimmte Fehlercodes behandeln müssen (z.B. Tokens bei 401 erneuern)

```dart
class ApiService extends NyApiService {
  ApiService() : super(decoders: modelDecoders);

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

{{ config('app.name') }} enthält einen eingebauten `NetworkLogger`-Interceptor. Er ist standardmäßig aktiviert, wenn `APP_DEBUG` in Ihrer Umgebung auf `true` gesetzt ist.

### Konfiguration

```dart
class ApiService extends NyApiService {
  ApiService() : super(
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

Sie können ihn deaktivieren, indem Sie `useNetworkLogger: false` setzen.

```
class ApiService extends NyApiService {
  ApiService()
      : super(
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
    // Nur Anfragen an bestimmte Endpunkte loggen
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Einen API-Service verwenden

Es gibt zwei Möglichkeiten, Ihren API-Service von einer Seite aus aufzurufen.

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
    // data ist die umgewandelte User?-Instanz
  },
  onError: (DioException dioException) {
    // Fehler behandeln
  },
);
```

### api()-Helfer-Parameter

| Parameter | Typ | Beschreibung |
|-----------|------|-------------|
| `request` | `Function(T)` | Die API-Anfragefunktion |
| `headers` | `Map<String, dynamic>` | Zusätzliche Header |
| `bearerToken` | `String?` | Bearer-Token |
| `baseUrl` | `String?` | Basis-URL überschreiben |
| `page` | `int?` | Paginierungsseite |
| `perPage` | `int?` | Elemente pro Seite |
| `retry` | `int` | Wiederholungsversuche |
| `retryDelay` | `Duration?` | Verzögerung zwischen Wiederholungen |
| `onSuccess` | `Function(Response, dynamic)?` | Erfolgs-Callback |
| `onError` | `Function(DioException)?` | Fehler-Callback |
| `cacheKey` | `String?` | Cache-Schlüssel |
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

"Morphing" ist der Begriff von {{ config('app.name') }} für die automatische Konvertierung von JSON-Antworten in Ihre Dart-Model-Klassen. Wenn Sie `network<User>(...)` verwenden, wird das JSON der Antwort durch Ihren Decoder geleitet, um eine `User`-Instanz zu erstellen -- kein manuelles Parsen nötig.

```dart
class ApiService extends NyApiService {
  ApiService() : super(decoders: modelDecoders);

  // Gibt einen einzelnen User zurück
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Gibt eine Liste von Users zurück
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

Der Typparameter, den Sie an `network<T>()` übergeben, wird mit Ihrer `modelDecoders`-Map abgeglichen, um den richtigen Decoder zu finden.

**Siehe auch:** [Decoders](/docs/{{$version}}/decoders#model-decoders) für Details zur Registrierung von Model-Decodern.


<div id="caching-responses"></div>

## Antworten cachen

Cachen Sie Antworten, um API-Aufrufe zu reduzieren und die Leistung zu verbessern. Caching ist nützlich für Daten, die sich nicht häufig ändern, wie Länderlisten, Kategorien oder Konfigurationen.

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
// Einen bestimmten Cache-Schlüssel leeren
await apiService.clearCache("app_countries");

// Gesamten API-Cache leeren
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

Verwenden Sie `CachePolicy` für feinkörnige Kontrolle über das Caching-Verhalten:

| Richtlinie | Beschreibung |
|------------|-------------|
| `CachePolicy.networkOnly` | Immer vom Netzwerk abrufen (Standard) |
| `CachePolicy.cacheFirst` | Zuerst Cache versuchen, bei Fehlen Netzwerk verwenden |
| `CachePolicy.networkFirst` | Zuerst Netzwerk versuchen, bei Fehler Cache verwenden |
| `CachePolicy.cacheOnly` | Nur Cache verwenden, Fehler wenn leer |
| `CachePolicy.staleWhileRevalidate` | Cache sofort zurückgeben, im Hintergrund aktualisieren |

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

- **cacheFirst** -- Daten, die sich selten ändern. Gibt gecachte Daten sofort zurück, ruft nur vom Netzwerk ab, wenn der Cache leer ist.
- **networkFirst** -- Daten, die möglichst aktuell sein sollten. Versucht zuerst das Netzwerk, fällt bei Fehler auf Cache zurück.
- **staleWhileRevalidate** -- UI, die eine sofortige Antwort benötigt, aber aktuell bleiben sollte. Gibt gecachte Daten zurück und aktualisiert dann im Hintergrund.
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

### Wiederholung mit Verzögerung

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
      // Nur bei Server-Fehlern wiederholen
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

## Konnektivitätsprüfungen

Schnell fehlschlagen, wenn das Gerät offline ist, anstatt auf ein Timeout zu warten.

### Service-Level

```dart
class ApiService extends NyApiService {
  ApiService() : super(decoders: modelDecoders);

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

Wenn aktiviert und das Gerät offline ist:
- `networkFirst`-Richtlinie fällt auf Cache zurück
- Andere Richtlinien werfen sofort `DioExceptionType.connectionError`


<div id="cancel-tokens"></div>

## Cancel Tokens

Verwalten und stornieren Sie ausstehende Anfragen.

```dart
// Einen verwalteten Cancel Token erstellen
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Alle ausstehenden Anfragen abbrechen (z.B. bei Abmeldung)
apiService.cancelAllRequests('User logged out');

// Anzahl aktiver Anfragen prüfen
int count = apiService.activeRequestCount;

// Einen bestimmten Token bereinigen, wenn fertig
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## Auth-Header setzen

Überschreiben Sie `setAuthHeaders`, um Authentifizierungs-Header an jede Anfrage anzuhängen. Diese Methode wird vor jeder Anfrage aufgerufen, wenn `shouldSetAuthHeaders` auf `true` steht (Standard).

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

Für öffentliche Endpunkte, die keine Authentifizierung benötigen:

```dart
// Pro Anfrage
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-Level
apiService.setShouldSetAuthHeaders(false);
```

**Siehe auch:** [Authentifizierung](/docs/{{ $version }}/authentication) für Details zur Benutzerauthentifizierung und Token-Speicherung.


<div id="refreshing-tokens"></div>

## Tokens aktualisieren

Überschreiben Sie `shouldRefreshToken` und `refreshToken`, um den Token-Ablauf zu behandeln. Diese werden vor jeder Anfrage aufgerufen.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Prüfen, ob der Token erneuert werden muss
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Neue Dio-Instanz (ohne Interceptors) zum Erneuern des Tokens verwenden
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Neuen Token im Speicher sichern
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

Standardmäßig erstellt der `api`-Helfer jedes Mal eine neue Instanz. Um ein Singleton zu verwenden, übergeben Sie eine Instanz anstelle einer Factory in `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // Neue Instanz jedes Mal

  ApiService: ApiService(), // Singleton — immer dieselbe Instanz
};
```


<div id="advanced-configuration"></div>

## Erweiterte Konfiguration

### Benutzerdefinierte Dio-Initialisierung

```dart
class ApiService extends NyApiService {
  ApiService() : super(
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

### Überschreibbare Eigenschaften

| Eigenschaft | Typ | Standard | Beschreibung |
|------------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Basis-URL für alle Anfragen |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio-Interceptors |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Model-Decoder für JSON-Umwandlung |
| `shouldSetAuthHeaders` | `bool` | `true` | Ob `setAuthHeaders` vor Anfragen aufgerufen werden soll |
| `retry` | `int` | `0` | Standard-Wiederholungsversuche |
| `retryDelay` | `Duration` | `1 second` | Standard-Verzögerung zwischen Wiederholungen |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Konnektivität vor Anfragen prüfen |
