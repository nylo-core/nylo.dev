# Networking

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- Wykonywanie zapytań HTTP
  - [Metody skrócone](#convenience-methods "Metody skrócone")
  - [Helper network](#network-helper "Helper network")
  - [Helper networkResponse](#network-response-helper "Helper networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Opcje bazowe](#base-options "Opcje bazowe")
  - [Dodawanie nagłówków](#adding-headers "Dodawanie nagłówków")
- Operacje na plikach
  - [Przesyłanie plików](#uploading-files "Przesyłanie plików")
  - [Pobieranie plików](#downloading-files "Pobieranie plików")
- [Interceptory](#interceptors "Interceptory")
  - [Logger sieciowy](#network-logger "Logger sieciowy")
- [Używanie serwisu API](#using-an-api-service "Używanie serwisu API")
- [Tworzenie serwisu API](#create-an-api-service "Tworzenie serwisu API")
- [Transformacja JSON do modeli](#morphing-json-payloads-to-models "Transformacja JSON do modeli")
- Buforowanie
  - [Buforowanie odpowiedzi](#caching-responses "Buforowanie odpowiedzi")
  - [Polityki buforowania](#cache-policies "Polityki buforowania")
- Obsługa błędów
  - [Ponawianie nieudanych zapytań](#retrying-failed-requests "Ponawianie nieudanych zapytań")
  - [Sprawdzanie łączności](#connectivity-checks "Sprawdzanie łączności")
  - [Tokeny anulowania](#cancel-tokens "Tokeny anulowania")
- Uwierzytelnianie
  - [Ustawianie nagłówków uwierzytelniania](#setting-auth-headers "Ustawianie nagłówków uwierzytelniania")
  - [Odświeżanie tokenów](#refreshing-tokens "Odświeżanie tokenów")
- [Singleton serwisu API](#singleton-api-service "Singleton serwisu API")
- [Zaawansowana konfiguracja](#advanced-configuration "Zaawansowana konfiguracja")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} upraszcza obsługę sieci. Definiujesz endpointy API w klasach serwisów rozszerzających `NyApiService`, a następnie wywołujesz je ze swoich stron. Framework obsługuje dekodowanie JSON, obsługę błędów, buforowanie i automatyczną konwersję odpowiedzi do klas modeli (nazywaną "morfowaniem").

Twoje serwisy API znajdują się w `lib/app/networking/`. Świeży projekt zawiera domyślny `ApiService`:

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

Istnieją trzy sposoby wykonywania zapytań HTTP:

| Podejście | Zwraca | Najlepsze do |
|----------|---------|----------|
| Metody skrócone (`get`, `post`, itp.) | `T?` | Proste operacje CRUD |
| `network()` | `T?` | Zapytania wymagające buforowania, ponowień lub niestandardowych nagłówków |
| `networkResponse()` | `NyResponse<T>` | Gdy potrzebujesz kodów statusu, nagłówków lub szczegółów błędów |

Pod spodem {{ config('app.name') }} używa <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, potężnego klienta HTTP.


<div id="convenience-methods"></div>

## Metody skrócone

`NyApiService` udostępnia skrócone metody dla typowych operacji HTTP. Wewnętrznie wywołują `network()`.

### Zapytanie GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Zapytanie POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Zapytanie PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Zapytanie DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Zapytanie PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Zapytanie HEAD

Użyj HEAD, aby sprawdzić istnienie zasobu lub pobrać nagłówki bez pobierania treści:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Helper network

Metoda `network` daje Ci większą kontrolę niż metody skrócone. Zwraca bezpośrednio zmorfowane dane (`T?`).

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

Callback `request` otrzymuje instancję <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> z już skonfigurowanym bazowym URL i interceptorami.

### Parametry network

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `request` | `Function(Dio)` | Zapytanie HTTP do wykonania (wymagane) |
| `bearerToken` | `String?` | Token bearer dla tego zapytania |
| `baseUrl` | `String?` | Nadpisanie bazowego URL serwisu |
| `headers` | `Map<String, dynamic>?` | Dodatkowe nagłówki |
| `retry` | `int?` | Liczba prób ponowienia |
| `retryDelay` | `Duration?` | Opóźnienie między ponowieniami |
| `retryIf` | `bool Function(DioException)?` | Warunek ponowienia |
| `connectionTimeout` | `Duration?` | Timeout połączenia |
| `receiveTimeout` | `Duration?` | Timeout odbioru |
| `sendTimeout` | `Duration?` | Timeout wysyłki |
| `cacheKey` | `String?` | Klucz cache |
| `cacheDuration` | `Duration?` | Czas trwania cache |
| `cachePolicy` | `CachePolicy?` | Strategia buforowania |
| `checkConnectivity` | `bool?` | Sprawdź łączność przed zapytaniem |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback sukcesu |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback niepowodzenia |


<div id="network-response-helper"></div>

## Helper networkResponse

Użyj `networkResponse`, gdy potrzebujesz dostępu do pełnej odpowiedzi -- kodów statusu, nagłówków, komunikatów błędów -- a nie tylko danych. Zwraca `NyResponse<T>` zamiast `T?`.

Użyj `networkResponse`, gdy potrzebujesz:
- Sprawdzić kody statusu HTTP do specyficznej obsługi
- Uzyskać dostęp do nagłówków odpowiedzi
- Pobrać szczegółowe komunikaty błędów dla informacji zwrotnej użytkownika
- Zaimplementować niestandardową logikę obsługi błędów

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Następnie użyj odpowiedzi na swojej stronie:

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

Obie metody przyjmują te same parametry. Wybierz `networkResponse`, gdy potrzebujesz sprawdzić odpowiedź poza samymi danymi.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` opakowuje odpowiedź Dio ze zmorfowanymi danymi i helperami statusu.

### Właściwości

| Właściwość | Typ | Opis |
|----------|------|-------------|
| `response` | `Response?` | Oryginalna odpowiedź Dio |
| `data` | `T?` | Zmorfowane/zdekodowane dane |
| `rawData` | `dynamic` | Surowe dane odpowiedzi |
| `headers` | `Headers?` | Nagłówki odpowiedzi |
| `statusCode` | `int?` | Kod statusu HTTP |
| `statusMessage` | `String?` | Komunikat statusu HTTP |
| `contentType` | `String?` | Typ treści z nagłówków |
| `errorMessage` | `String?` | Wyodrębniony komunikat błędu |

### Sprawdzanie statusu

| Getter | Opis |
|--------|-------------|
| `isSuccessful` | Status 200-299 |
| `isClientError` | Status 400-499 |
| `isServerError` | Status 500-599 |
| `isRedirect` | Status 300-399 |
| `hasData` | Dane nie są null |
| `isUnauthorized` | Status 401 |
| `isForbidden` | Status 403 |
| `isNotFound` | Status 404 |
| `isTimeout` | Status 408 |
| `isConflict` | Status 409 |
| `isRateLimited` | Status 429 |

### Helpery danych

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

## Opcje bazowe

Skonfiguruj domyślne opcje Dio dla serwisu API za pomocą parametru `baseOptions`:

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

Możesz również konfigurować opcje dynamicznie na instancji:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Kliknij <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">tutaj</a>, aby zobaczyć wszystkie opcje bazowe, które możesz ustawić.


<div id="adding-headers"></div>

## Dodawanie nagłówków

### Nagłówki per zapytanie

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Token Bearer

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### Nagłówki na poziomie serwisu

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Rozszerzenie RequestHeaders

Typ `RequestHeaders` (typedef `Map<String, dynamic>`) udostępnia metody pomocnicze:

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

| Metoda | Opis |
|--------|-------------|
| `addBearerToken(token)` | Ustaw nagłówek `Authorization: Bearer` |
| `getBearerToken()` | Odczytaj token bearer z nagłówków |
| `addHeader(key, value)` | Dodaj niestandardowy nagłówek |
| `hasHeader(key)` | Sprawdź, czy nagłówek istnieje |


<div id="uploading-files"></div>

## Przesyłanie plików

### Przesyłanie pojedynczego pliku

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

### Przesyłanie wielu plików

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

## Pobieranie plików

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

## Interceptory

Interceptory pozwalają modyfikować zapytania przed ich wysłaniem, obsługiwać odpowiedzi i zarządzać błędami. Działają na każdym zapytaniu wykonanym przez serwis API.

Używaj interceptorów, gdy potrzebujesz:
- Dodać nagłówki uwierzytelniania do wszystkich zapytań
- Logować zapytania i odpowiedzi do debugowania
- Globalnie transformować dane zapytań/odpowiedzi
- Obsługiwać specyficzne kody błędów (np. odświeżanie tokenów przy 401)

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

### Tworzenie niestandardowego interceptora

```bash
metro make:interceptor logging
```

**Plik:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

## Logger sieciowy

{{ config('app.name') }} zawiera wbudowany interceptor `NetworkLogger`. Jest domyślnie włączony, gdy `APP_DEBUG` jest ustawione na `true` w Twoim środowisku.

### Konfiguracja

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

Możesz go wyłączyć, ustawiając `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Disable logger
        );
```

### Poziomy logowania

| Poziom | Opis |
|-------|-------------|
| `LogLevelType.verbose` | Wyświetla wszystkie szczegóły zapytań/odpowiedzi |
| `LogLevelType.minimal` | Wyświetla tylko metodę, URL, status i czas |
| `LogLevelType.none` | Brak wyjścia logowania |

### Filtrowanie logów

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Używanie serwisu API

Istnieją dwa sposoby wywoływania serwisu API ze strony.

### Bezpośrednia instancja

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

### Używanie helpera api()

Helper `api` tworzy instancje używając `apiDecoders` z `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Z callbackami:

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

### Parametry helpera api()

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `request` | `Function(T)` | Funkcja zapytania API |
| `context` | `BuildContext?` | Kontekst build |
| `headers` | `Map<String, dynamic>` | Dodatkowe nagłówki |
| `bearerToken` | `String?` | Token bearer |
| `baseUrl` | `String?` | Nadpisanie bazowego URL |
| `page` | `int?` | Strona paginacji |
| `perPage` | `int?` | Elementów na stronę |
| `retry` | `int` | Próby ponowienia |
| `retryDelay` | `Duration?` | Opóźnienie między ponowieniami |
| `onSuccess` | `Function(Response, dynamic)?` | Callback sukcesu |
| `onError` | `Function(DioException)?` | Callback błędu |
| `cacheKey` | `String?` | Klucz cache |
| `cacheDuration` | `Duration?` | Czas trwania cache |


<div id="create-an-api-service"></div>

## Tworzenie serwisu API

Aby utworzyć nowy serwis API:

```bash
metro make:api_service user
```

Z modelem:

```bash
metro make:api_service user --model="User"
```

To tworzy serwis API z metodami CRUD:

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

## Transformacja JSON do modeli

"Morfowanie" to termin {{ config('app.name') }} określający automatyczną konwersję odpowiedzi JSON na klasy modeli Dart. Gdy używasz `network<User>(...)`, odpowiedź JSON jest przepuszczana przez dekoder, aby utworzyć instancję `User` -- bez ręcznego parsowania.

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

Dekodery są zdefiniowane w `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Parametr typu, który przekazujesz do `network<T>()`, jest dopasowywany do Twojej mapy `modelDecoders`, aby znaleźć odpowiedni dekoder.

**Zobacz także:** [Decoders](/docs/{{$version}}/decoders#model-decoders) po szczegóły dotyczące rejestracji dekoderów modeli.


<div id="caching-responses"></div>

## Buforowanie odpowiedzi

Buforuj odpowiedzi, aby zmniejszyć liczbę wywołań API i poprawić wydajność. Buforowanie jest przydatne dla danych, które nie zmieniają się często, takich jak listy krajów, kategorie czy konfiguracja.

Podaj `cacheKey` i opcjonalnie `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Czyszczenie cache

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Buforowanie z helperem api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Polityki buforowania

Użyj `CachePolicy` do precyzyjnej kontroli nad zachowaniem buforowania:

| Polityka | Opis |
|--------|-------------|
| `CachePolicy.networkOnly` | Zawsze pobieraj z sieci (domyślnie) |
| `CachePolicy.cacheFirst` | Najpierw próbuj z cache, fallback do sieci |
| `CachePolicy.networkFirst` | Najpierw próbuj z sieci, fallback do cache |
| `CachePolicy.cacheOnly` | Używaj tylko cache, błąd jeśli pusty |
| `CachePolicy.staleWhileRevalidate` | Zwróć cache natychmiast, aktualizuj w tle |

### Użycie

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

### Kiedy używać poszczególnych polityk

- **cacheFirst** -- Dane, które rzadko się zmieniają. Zwraca dane z cache natychmiast, pobiera z sieci tylko gdy cache jest pusty.
- **networkFirst** -- Dane, które powinny być świeże gdy to możliwe. Najpierw próbuje z sieci, fallback do cache przy niepowodzeniu.
- **staleWhileRevalidate** -- UI, który potrzebuje natychmiastowej odpowiedzi, ale powinien być aktualizowany. Zwraca dane z cache, następnie odświeża w tle.
- **cacheOnly** -- Tryb offline. Rzuca błąd, jeśli brak danych w cache.

> **Uwaga:** Jeśli podasz `cacheKey` lub `cacheDuration` bez określania `cachePolicy`, domyślna polityka to `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Ponawianie nieudanych zapytań

Automatycznie ponawiaj zapytania, które się nie powiodły.

### Podstawowe ponowienie

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Ponowienie z opóźnieniem

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Warunkowe ponowienie

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

### Ponowienie na poziomie serwisu

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Sprawdzanie łączności

Szybko reaguj, gdy urządzenie jest offline, zamiast czekać na timeout.

### Na poziomie serwisu

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Per zapytanie

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dynamicznie

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Gdy włączone i urządzenie jest offline:
- Polityka `networkFirst` fallbackuje do cache
- Inne polityki rzucają `DioExceptionType.connectionError` natychmiast


<div id="cancel-tokens"></div>

## Tokeny anulowania

Zarządzaj i anuluj oczekujące zapytania.

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

## Ustawianie nagłówków uwierzytelniania

Nadpisz `setAuthHeaders`, aby dołączyć nagłówki uwierzytelniania do każdego zapytania. Ta metoda jest wywoływana przed każdym zapytaniem, gdy `shouldSetAuthHeaders` jest `true` (domyślnie).

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

### Wyłączanie nagłówków uwierzytelniania

Dla publicznych endpointów, które nie wymagają uwierzytelniania:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Zobacz także:** [Authentication](/docs/{{ $version }}/authentication) po szczegóły dotyczące uwierzytelniania użytkowników i przechowywania tokenów.


<div id="refreshing-tokens"></div>

## Odświeżanie tokenów

Nadpisz `shouldRefreshToken` i `refreshToken`, aby obsłużyć wygaśnięcie tokena. Są one wywoływane przed każdym zapytaniem.

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

Parametr `dio` w `refreshToken` to nowa instancja Dio, oddzielona od głównej instancji serwisu, aby uniknąć pętli interceptorów.


<div id="singleton-api-service"></div>

## Singleton serwisu API

Domyślnie helper `api` tworzy nową instancję za każdym razem. Aby użyć singletona, przekaż instancję zamiast fabryki w `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Zaawansowana konfiguracja

### Niestandardowa inicjalizacja Dio

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

### Dostęp do instancji Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Helper paginacji

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callbacki zdarzeń

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Właściwości do nadpisania

| Właściwość | Typ | Domyślnie | Opis |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Bazowy URL dla wszystkich zapytań |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Interceptory Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Dekodery modeli do morfowania JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Czy wywoływać `setAuthHeaders` przed zapytaniami |
| `retry` | `int` | `0` | Domyślna liczba prób ponowienia |
| `retryDelay` | `Duration` | `1 second` | Domyślne opóźnienie między ponowieniami |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Sprawdź łączność przed zapytaniami |
