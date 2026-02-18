# Networking

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Effettuare Richieste HTTP
  - [Metodi di Convenienza](#convenience-methods "Metodi di Convenienza")
  - [Helper Network](#network-helper "Helper Network")
  - [Helper networkResponse](#network-response-helper "Helper networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Opzioni Base](#base-options "Opzioni Base")
  - [Aggiungere Header](#adding-headers "Aggiungere Header")
- Operazioni sui File
  - [Caricamento File](#uploading-files "Caricamento File")
  - [Download File](#downloading-files "Download File")
- [Interceptor](#interceptors "Interceptor")
  - [Logger di Rete](#network-logger "Logger di Rete")
- [Utilizzare un API Service](#using-an-api-service "Utilizzare un API Service")
- [Creare un API Service](#create-an-api-service "Creare un API Service")
- [Conversione JSON in Modelli](#morphing-json-payloads-to-models "Conversione JSON in Modelli")
- Caching
  - [Cache delle Risposte](#caching-responses "Cache delle Risposte")
  - [Politiche di Cache](#cache-policies "Politiche di Cache")
- Gestione degli Errori
  - [Ripetizione delle Richieste Fallite](#retrying-failed-requests "Ripetizione delle Richieste Fallite")
  - [Controlli di Connettivit&agrave;](#connectivity-checks "Controlli di Connettivit&agrave;")
  - [Token di Cancellazione](#cancel-tokens "Token di Cancellazione")
- Autenticazione
  - [Impostare Header di Autenticazione](#setting-auth-headers "Impostare Header di Autenticazione")
  - [Aggiornamento dei Token](#refreshing-tokens "Aggiornamento dei Token")
- [API Service Singleton](#singleton-api-service "API Service Singleton")
- [Configurazione Avanzata](#advanced-configuration "Configurazione Avanzata")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} rende il networking semplice. Definisci gli endpoint API in classi di servizio che estendono `NyApiService`, poi li chiami dalle tue pagine. Il framework gestisce la decodifica JSON, la gestione degli errori, il caching e la conversione automatica delle risposte nelle tue classi modello (chiamata "morphing").

I tuoi API service risiedono in `lib/app/networking/`. Un progetto nuovo include un `ApiService` predefinito:

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

Ci sono tre modi per effettuare richieste HTTP:

| Approccio | Restituisce | Ideale Per |
|-----------|-------------|------------|
| Metodi di convenienza (`get`, `post`, ecc.) | `T?` | Operazioni CRUD semplici |
| `network()` | `T?` | Richieste che necessitano di caching, ripetizione o header personalizzati |
| `networkResponse()` | `NyResponse<T>` | Quando servono codici di stato, header o dettagli degli errori |

Sotto il cofano, {{ config('app.name') }} utilizza <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, un potente client HTTP.


<div id="convenience-methods"></div>

## Metodi di Convenienza

`NyApiService` fornisce metodi abbreviati per le operazioni HTTP comuni. Questi chiamano internamente `network()`.

### Richiesta GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Richiesta POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Richiesta PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Richiesta DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Richiesta PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Richiesta HEAD

Usa HEAD per verificare l'esistenza di una risorsa o ottenere gli header senza scaricare il corpo:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Helper Network

Il metodo `network` offre maggiore controllo rispetto ai metodi di convenienza. Restituisce direttamente i dati convertiti (`T?`).

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

La callback `request` riceve un'istanza <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> con il tuo URL base e gli interceptor gi&agrave; configurati.

### Parametri di network

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `request` | `Function(Dio)` | La richiesta HTTP da eseguire (obbligatorio) |
| `bearerToken` | `String?` | Bearer token per questa richiesta |
| `baseUrl` | `String?` | Sovrascrive l'URL base del servizio |
| `headers` | `Map<String, dynamic>?` | Header aggiuntivi |
| `retry` | `int?` | Numero di tentativi di ripetizione |
| `retryDelay` | `Duration?` | Ritardo tra i tentativi |
| `retryIf` | `bool Function(DioException)?` | Condizione per la ripetizione |
| `connectionTimeout` | `Duration?` | Timeout di connessione |
| `receiveTimeout` | `Duration?` | Timeout di ricezione |
| `sendTimeout` | `Duration?` | Timeout di invio |
| `cacheKey` | `String?` | Chiave di cache |
| `cacheDuration` | `Duration?` | Durata della cache |
| `cachePolicy` | `CachePolicy?` | Strategia di cache |
| `checkConnectivity` | `bool?` | Controlla la connettivit&agrave; prima della richiesta |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback di successo |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback di fallimento |


<div id="network-response-helper"></div>

## Helper networkResponse

Usa `networkResponse` quando hai bisogno di accedere alla risposta completa - codici di stato, header, messaggi di errore - non solo ai dati. Restituisce un `NyResponse<T>` invece di `T?`.

Usa `networkResponse` quando hai bisogno di:
- Controllare i codici di stato HTTP per una gestione specifica
- Accedere agli header della risposta
- Ottenere messaggi di errore dettagliati per il feedback dell'utente
- Implementare una logica di gestione errori personalizzata

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Poi usa la risposta nella tua pagina:

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
// network() — restituisce i dati direttamente
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — restituisce la risposta completa
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Entrambi i metodi accettano gli stessi parametri. Scegli `networkResponse` quando hai bisogno di ispezionare la risposta oltre ai semplici dati.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` avvolge la risposta Dio con dati convertiti e helper per lo stato.

### Propriet&agrave;

| Propriet&agrave; | Tipo | Descrizione |
|----------|------|-------------|
| `response` | `Response?` | Risposta Dio originale |
| `data` | `T?` | Dati convertiti/decodificati |
| `rawData` | `dynamic` | Dati grezzi della risposta |
| `headers` | `Headers?` | Header della risposta |
| `statusCode` | `int?` | Codice di stato HTTP |
| `statusMessage` | `String?` | Messaggio di stato HTTP |
| `contentType` | `String?` | Tipo di contenuto dagli header |
| `errorMessage` | `String?` | Messaggio di errore estratto |

### Controlli di Stato

| Getter | Descrizione |
|--------|-------------|
| `isSuccessful` | Stato 200-299 |
| `isClientError` | Stato 400-499 |
| `isServerError` | Stato 500-599 |
| `isRedirect` | Stato 300-399 |
| `hasData` | I dati non sono null |
| `isUnauthorized` | Stato 401 |
| `isForbidden` | Stato 403 |
| `isNotFound` | Stato 404 |
| `isTimeout` | Stato 408 |
| `isConflict` | Stato 409 |
| `isRateLimited` | Stato 429 |

### Helper per i Dati

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Ottieni i dati o lancia un'eccezione se null
User user = response.dataOrThrow('User not found');

// Ottieni i dati o usa un fallback
User user = response.dataOr(User.guest());

// Esegui callback solo se riuscito
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Pattern match su successo/fallimento
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Ottieni un header specifico
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Opzioni Base

Configura le opzioni Dio predefinite per il tuo API service utilizzando il parametro `baseOptions`:

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

Puoi anche configurare le opzioni dinamicamente su un'istanza:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Clicca <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">qui</a> per visualizzare tutte le opzioni base che puoi impostare.


<div id="adding-headers"></div>

## Aggiungere Header

### Header Per Richiesta

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer Token

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### Header a Livello di Servizio

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Estensione RequestHeaders

Il tipo `RequestHeaders` (un typedef `Map<String, dynamic>`) fornisce metodi helper:

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

| Metodo | Descrizione |
|--------|-------------|
| `addBearerToken(token)` | Imposta l'header `Authorization: Bearer` |
| `getBearerToken()` | Legge il bearer token dagli header |
| `addHeader(key, value)` | Aggiunge un header personalizzato |
| `hasHeader(key)` | Verifica se un header esiste |


<div id="uploading-files"></div>

## Caricamento File

### Caricamento File Singolo

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

### Caricamento File Multipli

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

## Download File

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

## Interceptor

Gli interceptor ti permettono di modificare le richieste prima dell'invio, gestire le risposte e gestire gli errori. Vengono eseguiti su ogni richiesta effettuata tramite l'API service.

Usa gli interceptor quando hai bisogno di:
- Aggiungere header di autenticazione a tutte le richieste
- Registrare richieste e risposte per il debug
- Trasformare i dati di richiesta/risposta globalmente
- Gestire codici di errore specifici (es. aggiornare i token su 401)

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

### Creare un Interceptor Personalizzato

```bash
metro make:interceptor logging
```

**File:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

## Logger di Rete

{{ config('app.name') }} include un interceptor `NetworkLogger` integrato. &Egrave; abilitato per impostazione predefinita quando `APP_DEBUG` &egrave; `true` nel tuo ambiente.

### Configurazione

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

Puoi disabilitarlo impostando `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Disabilita il logger
        );
```

### Livelli di Log

| Livello | Descrizione |
|---------|-------------|
| `LogLevelType.verbose` | Stampa tutti i dettagli di richiesta/risposta |
| `LogLevelType.minimal` | Stampa solo metodo, URL, stato e tempo |
| `LogLevelType.none` | Nessun output di log |

### Filtrare i Log

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Utilizzare un API Service

Ci sono due modi per chiamare il tuo API service da una pagina.

### Istanziazione Diretta

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

### Utilizzare l'Helper api()

L'helper `api` crea istanze utilizzando i tuoi `apiDecoders` da `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Con callback:

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

### Parametri dell'Helper api()

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `request` | `Function(T)` | La funzione di richiesta API |
| `context` | `BuildContext?` | Build context |
| `headers` | `Map<String, dynamic>` | Header aggiuntivi |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | Sovrascrive l'URL base |
| `page` | `int?` | Pagina di paginazione |
| `perPage` | `int?` | Elementi per pagina |
| `retry` | `int` | Tentativi di ripetizione |
| `retryDelay` | `Duration?` | Ritardo tra i tentativi |
| `onSuccess` | `Function(Response, dynamic)?` | Callback di successo |
| `onError` | `Function(DioException)?` | Callback di errore |
| `cacheKey` | `String?` | Chiave di cache |
| `cacheDuration` | `Duration?` | Durata della cache |


<div id="create-an-api-service"></div>

## Creare un API Service

Per creare un nuovo API service:

```bash
metro make:api_service user
```

Con un modello:

```bash
metro make:api_service user --model="User"
```

Questo crea un API service con metodi CRUD:

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

## Conversione JSON in Modelli

"Morphing" &egrave; il termine di {{ config('app.name') }} per la conversione automatica delle risposte JSON nelle tue classi modello Dart. Quando usi `network<User>(...)`, il JSON della risposta viene passato attraverso il tuo decoder per creare un'istanza `User` -- nessun parsing manuale necessario.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Restituisce un singolo User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Restituisce una Lista di User
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

I decoder sono definiti in `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Il parametro di tipo che passi a `network<T>()` viene confrontato con la tua mappa `modelDecoders` per trovare il decoder corretto.

**Vedi anche:** [Decoders](/docs/{{$version}}/decoders#model-decoders) per dettagli sulla registrazione dei model decoder.


<div id="caching-responses"></div>

## Cache delle Risposte

Metti in cache le risposte per ridurre le chiamate API e migliorare le prestazioni. Il caching &egrave; utile per dati che non cambiano frequentemente, come elenchi di paesi, categorie o configurazioni.

Fornisci una `cacheKey` e opzionalmente una `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Svuotare la Cache

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Caching con l'Helper api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Politiche di Cache

Usa `CachePolicy` per un controllo granulare sul comportamento della cache:

| Politica | Descrizione |
|----------|-------------|
| `CachePolicy.networkOnly` | Recupera sempre dalla rete (predefinito) |
| `CachePolicy.cacheFirst` | Prova prima la cache, poi la rete come fallback |
| `CachePolicy.networkFirst` | Prova prima la rete, poi la cache come fallback |
| `CachePolicy.cacheOnly` | Usa solo la cache, errore se vuota |
| `CachePolicy.staleWhileRevalidate` | Restituisce la cache immediatamente, aggiorna in background |

### Utilizzo

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

### Quando Usare Ogni Politica

- **cacheFirst** -- Dati che cambiano raramente. Restituisce i dati in cache istantaneamente, recupera dalla rete solo se la cache &egrave; vuota.
- **networkFirst** -- Dati che dovrebbero essere freschi quando possibile. Prova prima la rete, poi la cache come fallback in caso di errore.
- **staleWhileRevalidate** -- UI che necessita di una risposta immediata ma che deve rimanere aggiornata. Restituisce i dati in cache, poi aggiorna in background.
- **cacheOnly** -- Modalit&agrave; offline. Lancia un errore se non esistono dati in cache.

> **Nota:** Se fornisci una `cacheKey` o `cacheDuration` senza specificare una `cachePolicy`, la politica predefinita &egrave; `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Ripetizione delle Richieste Fallite

Ripeti automaticamente le richieste che falliscono.

### Ripetizione Base

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Ripetizione con Ritardo

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Ripetizione Condizionale

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

### Ripetizione a Livello di Servizio

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Controlli di Connettivit&agrave;

Fallisci rapidamente quando il dispositivo &egrave; offline invece di attendere un timeout.

### A Livello di Servizio

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Per Richiesta

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dinamico

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Quando abilitato e il dispositivo &egrave; offline:
- La politica `networkFirst` ricorre alla cache
- Le altre politiche lanciano `DioExceptionType.connectionError` immediatamente


<div id="cancel-tokens"></div>

## Token di Cancellazione

Gestisci e cancella le richieste in sospeso.

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

## Impostare Header di Autenticazione

Sovrascrivi `setAuthHeaders` per allegare header di autenticazione a ogni richiesta. Questo metodo viene chiamato prima di ogni richiesta quando `shouldSetAuthHeaders` &egrave; `true` (impostazione predefinita).

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

### Disabilitare gli Header di Autenticazione

Per endpoint pubblici che non necessitano di autenticazione:

```dart
// Per richiesta
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// A livello di servizio
apiService.setShouldSetAuthHeaders(false);
```

**Vedi anche:** [Autenticazione](/docs/{{ $version }}/authentication) per dettagli sull'autenticazione degli utenti e la memorizzazione dei token.


<div id="refreshing-tokens"></div>

## Aggiornamento dei Token

Sovrascrivi `shouldRefreshToken` e `refreshToken` per gestire la scadenza dei token. Questi vengono chiamati prima di ogni richiesta.

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

Il parametro `dio` in `refreshToken` &egrave; una nuova istanza Dio, separata dall'istanza principale del servizio, per evitare loop di interceptor.


<div id="singleton-api-service"></div>

## API Service Singleton

Per impostazione predefinita, l'helper `api` crea una nuova istanza ogni volta. Per usare un singleton, passa un'istanza invece di una factory in `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Configurazione Avanzata

### Inizializzazione Dio Personalizzata

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

### Accesso all'Istanza Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Helper per la Paginazione

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callback degli Eventi

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Propriet&agrave; Sovrascrivibili

| Propriet&agrave; | Tipo | Predefinito | Descrizione |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | URL base per tutte le richieste |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Interceptor Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Decoder dei modelli per la conversione JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Se chiamare `setAuthHeaders` prima delle richieste |
| `retry` | `int` | `0` | Tentativi di ripetizione predefiniti |
| `retryDelay` | `Duration` | `1 second` | Ritardo predefinito tra i tentativi |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Controlla la connettivit&agrave; prima delle richieste |
