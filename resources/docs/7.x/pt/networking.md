# Networking

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- Fazendo Requisicoes HTTP
  - [Metodos de Conveniencia](#convenience-methods "Metodos de Conveniencia")
  - [Helper Network](#network-helper "Helper Network")
  - [Helper networkResponse](#network-response-helper "Helper networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Opcoes Base](#base-options "Opcoes Base")
  - [Adicionando Headers](#adding-headers "Adicionando Headers")
- Operacoes de Arquivo
  - [Upload de Arquivos](#uploading-files "Upload de Arquivos")
  - [Download de Arquivos](#downloading-files "Download de Arquivos")
- [Interceptors](#interceptors "Interceptors")
  - [Network Logger](#network-logger "Network Logger")
- [Usando um API Service](#using-an-api-service "Usando um API Service")
- [Criar um API Service](#create-an-api-service "Criar um API Service")
- [Convertendo JSON em Models](#morphing-json-payloads-to-models "Convertendo JSON em Models")
- Cache
  - [Cache de Respostas](#caching-responses "Cache de Respostas")
  - [Politicas de Cache](#cache-policies "Politicas de Cache")
- Tratamento de Erros
  - [Retentar Requisicoes com Falha](#retrying-failed-requests "Retentar Requisicoes com Falha")
  - [Verificacoes de Conectividade](#connectivity-checks "Verificacoes de Conectividade")
  - [Cancel Tokens](#cancel-tokens "Cancel Tokens")
- Autenticacao
  - [Definindo Headers de Autenticacao](#setting-auth-headers "Definindo Headers de Autenticacao")
  - [Atualizando Tokens](#refreshing-tokens "Atualizando Tokens")
- [API Service Singleton](#singleton-api-service "API Service Singleton")
- [Configuracao Avancada](#advanced-configuration "Configuracao Avancada")

<div id="introduction"></div>

## Introducao

{{ config('app.name') }} torna o networking simples. Voce define endpoints de API em classes de servico que estendem `NyApiService`, depois as chama das suas paginas. O framework cuida da decodificacao JSON, tratamento de erros, cache e conversao automatica de respostas para suas classes de modelo (chamado de "morphing").

Seus API services ficam em `lib/app/networking/`. Um projeto novo inclui um `ApiService` padrao:

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

Existem tres formas de fazer requisicoes HTTP:

| Abordagem | Retorna | Melhor Para |
|-----------|---------|-------------|
| Metodos de conveniencia (`get`, `post`, etc.) | `T?` | Operacoes CRUD simples |
| `network()` | `T?` | Requisicoes que precisam de cache, retentativas ou headers personalizados |
| `networkResponse()` | `NyResponse<T>` | Quando voce precisa de codigos de status, headers ou detalhes de erros |

Por baixo dos panos, {{ config('app.name') }} usa <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, um poderoso cliente HTTP.


<div id="convenience-methods"></div>

## Metodos de Conveniencia

`NyApiService` fornece metodos abreviados para operacoes HTTP comuns. Eles chamam `network()` internamente.

### Requisicao GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Requisicao POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Requisicao PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Requisicao DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Requisicao PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Requisicao HEAD

Use HEAD para verificar a existencia de um recurso ou obter headers sem baixar o corpo:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Helper Network

O metodo `network` oferece mais controle do que os metodos de conveniencia. Ele retorna os dados convertidos (`T?`) diretamente.

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

O callback `request` recebe uma instancia <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> com sua URL base e interceptors ja configurados.

### Parametros do network

| Parametro | Tipo | Descricao |
|-----------|------|-----------|
| `request` | `Function(Dio)` | A requisicao HTTP a ser executada (obrigatorio) |
| `bearerToken` | `String?` | Bearer token para esta requisicao |
| `baseUrl` | `String?` | Sobrescrever a URL base do servico |
| `headers` | `Map<String, dynamic>?` | Headers adicionais |
| `retry` | `int?` | Numero de tentativas de retentativa |
| `retryDelay` | `Duration?` | Atraso entre retentativas |
| `retryIf` | `bool Function(DioException)?` | Condicao para retentar |
| `connectionTimeout` | `Duration?` | Timeout de conexao |
| `receiveTimeout` | `Duration?` | Timeout de recebimento |
| `sendTimeout` | `Duration?` | Timeout de envio |
| `cacheKey` | `String?` | Chave de cache |
| `cacheDuration` | `Duration?` | Duracao do cache |
| `cachePolicy` | `CachePolicy?` | Estrategia de cache |
| `checkConnectivity` | `bool?` | Verificar conectividade antes da requisicao |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback de sucesso |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback de falha |


<div id="network-response-helper"></div>

## Helper networkResponse

Use `networkResponse` quando voce precisa de acesso a resposta completa -- codigos de status, headers, mensagens de erro -- nao apenas os dados. Ele retorna um `NyResponse<T>` em vez de `T?`.

Use `networkResponse` quando voce precisa:
- Verificar codigos de status HTTP para tratamento especifico
- Acessar headers da resposta
- Obter mensagens de erro detalhadas para feedback do usuario
- Implementar logica de tratamento de erros personalizada

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Depois use a resposta na sua pagina:

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

Ambos os metodos aceitam os mesmos parametros. Escolha `networkResponse` quando voce precisar inspecionar a resposta alem dos dados.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` encapsula a resposta do Dio com dados convertidos e helpers de status.

### Propriedades

| Propriedade | Tipo | Descricao |
|-------------|------|-----------|
| `response` | `Response?` | Resposta original do Dio |
| `data` | `T?` | Dados convertidos/decodificados |
| `rawData` | `dynamic` | Dados brutos da resposta |
| `headers` | `Headers?` | Headers da resposta |
| `statusCode` | `int?` | Codigo de status HTTP |
| `statusMessage` | `String?` | Mensagem de status HTTP |
| `contentType` | `String?` | Tipo de conteudo dos headers |
| `errorMessage` | `String?` | Mensagem de erro extraida |

### Verificacoes de Status

| Getter | Descricao |
|--------|-----------|
| `isSuccessful` | Status 200-299 |
| `isClientError` | Status 400-499 |
| `isServerError` | Status 500-599 |
| `isRedirect` | Status 300-399 |
| `hasData` | Dados nao sao null |
| `isUnauthorized` | Status 401 |
| `isForbidden` | Status 403 |
| `isNotFound` | Status 404 |
| `isTimeout` | Status 408 |
| `isConflict` | Status 409 |
| `isRateLimited` | Status 429 |

### Helpers de Dados

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

## Opcoes Base

Configure opcoes padrao do Dio para seu API service usando o parametro `baseOptions`:

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

Voce tambem pode configurar opcoes dinamicamente em uma instancia:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Clique <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">aqui</a> para ver todas as opcoes base que voce pode definir.


<div id="adding-headers"></div>

## Adicionando Headers

### Headers por Requisicao

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

### Headers no Nivel do Servico

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Extensao RequestHeaders

O tipo `RequestHeaders` (um typedef de `Map<String, dynamic>`) fornece metodos auxiliares:

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

| Metodo | Descricao |
|--------|-----------|
| `addBearerToken(token)` | Define o header `Authorization: Bearer` |
| `getBearerToken()` | Le o bearer token dos headers |
| `addHeader(key, value)` | Adiciona um header personalizado |
| `hasHeader(key)` | Verifica se um header existe |


<div id="uploading-files"></div>

## Upload de Arquivos

### Upload de Arquivo Unico

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

### Upload de Multiplos Arquivos

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

## Download de Arquivos

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

Interceptors permitem que voce modifique requisicoes antes de serem enviadas, trate respostas e gerencie erros. Eles sao executados em toda requisicao feita atraves do API service.

Use interceptors quando voce precisa:
- Adicionar headers de autenticacao a todas as requisicoes
- Registrar requisicoes e respostas para depuracao
- Transformar dados de requisicao/resposta globalmente
- Tratar codigos de erro especificos (ex.: atualizar tokens no 401)

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

### Criando um Interceptor Personalizado

```bash
metro make:interceptor logging
```

**Arquivo:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

{{ config('app.name') }} inclui um interceptor `NetworkLogger` integrado. Ele e habilitado por padrao quando `APP_DEBUG` e `true` no seu ambiente.

### Configuracao

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

Voce pode desabilita-lo definindo `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Disable logger
        );
```

### Niveis de Log

| Nivel | Descricao |
|-------|-----------|
| `LogLevelType.verbose` | Imprimir todos os detalhes de requisicao/resposta |
| `LogLevelType.minimal` | Imprimir apenas metodo, URL, status e tempo |
| `LogLevelType.none` | Sem saida de log |

### Filtrando Logs

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Usando um API Service

Existem duas formas de chamar seu API service a partir de uma pagina.

### Instanciacao Direta

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

### Usando o Helper api()

O helper `api` cria instancias usando seus `apiDecoders` de `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Com callbacks:

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

### Parametros do Helper api()

| Parametro | Tipo | Descricao |
|-----------|------|-----------|
| `request` | `Function(T)` | A funcao de requisicao da API |
| `context` | `BuildContext?` | Contexto de build |
| `headers` | `Map<String, dynamic>` | Headers adicionais |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | Sobrescrever URL base |
| `page` | `int?` | Pagina de paginacao |
| `perPage` | `int?` | Itens por pagina |
| `retry` | `int` | Tentativas de retentativa |
| `retryDelay` | `Duration?` | Atraso entre retentativas |
| `onSuccess` | `Function(Response, dynamic)?` | Callback de sucesso |
| `onError` | `Function(DioException)?` | Callback de erro |
| `cacheKey` | `String?` | Chave de cache |
| `cacheDuration` | `Duration?` | Duracao do cache |


<div id="create-an-api-service"></div>

## Criar um API Service

Para criar um novo API service:

```bash
metro make:api_service user
```

Com um modelo:

```bash
metro make:api_service user --model="User"
```

Isso cria um API service com metodos CRUD:

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

## Convertendo JSON em Models

"Morphing" e o termo do {{ config('app.name') }} para converter automaticamente respostas JSON nas suas classes de modelo Dart. Quando voce usa `network<User>(...)`, o JSON da resposta e passado pelo seu decoder para criar uma instancia de `User` -- sem necessidade de parsing manual.

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

Os decoders sao definidos em `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

O parametro de tipo que voce passa para `network<T>()` e comparado com o seu mapa `modelDecoders` para encontrar o decoder correto.

**Veja tambem:** [Decoders](/docs/{{$version}}/decoders#model-decoders) para detalhes sobre o registro de decoders de modelo.


<div id="caching-responses"></div>

## Cache de Respostas

Armazene respostas em cache para reduzir chamadas de API e melhorar o desempenho. O cache e util para dados que nao mudam frequentemente, como listas de paises, categorias ou configuracoes.

Forneca uma `cacheKey` e um `cacheDuration` opcional:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Limpando Cache

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Cache com o Helper api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Politicas de Cache

Use `CachePolicy` para controle granular sobre o comportamento de cache:

| Politica | Descricao |
|----------|-----------|
| `CachePolicy.networkOnly` | Sempre buscar da rede (padrao) |
| `CachePolicy.cacheFirst` | Tentar cache primeiro, fallback para rede |
| `CachePolicy.networkFirst` | Tentar rede primeiro, fallback para cache |
| `CachePolicy.cacheOnly` | Usar apenas cache, erro se vazio |
| `CachePolicy.staleWhileRevalidate` | Retornar cache imediatamente, atualizar em segundo plano |

### Uso

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

### Quando Usar Cada Politica

- **cacheFirst** -- Dados que raramente mudam. Retorna dados do cache instantaneamente, busca da rede apenas se o cache estiver vazio.
- **networkFirst** -- Dados que devem ser atualizados quando possivel. Tenta a rede primeiro, recorre ao cache em caso de falha.
- **staleWhileRevalidate** -- Interface que precisa de uma resposta imediata mas deve permanecer atualizada. Retorna dados do cache, depois atualiza em segundo plano.
- **cacheOnly** -- Modo offline. Lanca um erro se nao houver dados em cache.

> **Nota:** Se voce fornecer uma `cacheKey` ou `cacheDuration` sem especificar uma `cachePolicy`, a politica padrao e `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Retentar Requisicoes com Falha

Retente automaticamente requisicoes que falharam.

### Retentativa Basica

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Retentativa com Atraso

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Retentativa Condicional

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

### Retentativa no Nivel do Servico

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Verificacoes de Conectividade

Falhe rapidamente quando o dispositivo estiver offline em vez de aguardar um timeout.

### Nivel do Servico

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Por Requisicao

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

Quando habilitado e o dispositivo estiver offline:
- A politica `networkFirst` recorre ao cache
- Outras politicas lancam `DioExceptionType.connectionError` imediatamente


<div id="cancel-tokens"></div>

## Cancel Tokens

Gerencie e cancele requisicoes pendentes.

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

## Definindo Headers de Autenticacao

Sobrescreva `setAuthHeaders` para anexar headers de autenticacao a toda requisicao. Este metodo e chamado antes de cada requisicao quando `shouldSetAuthHeaders` e `true` (o padrao).

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

### Desabilitando Headers de Autenticacao

Para endpoints publicos que nao precisam de autenticacao:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Veja tambem:** [Authentication](/docs/{{ $version }}/authentication) para detalhes sobre autenticacao de usuarios e armazenamento de tokens.


<div id="refreshing-tokens"></div>

## Atualizando Tokens

Sobrescreva `shouldRefreshToken` e `refreshToken` para lidar com a expiracao de tokens. Eles sao chamados antes de cada requisicao.

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

O parametro `dio` em `refreshToken` e uma nova instancia do Dio, separada da instancia principal do servico, para evitar loops de interceptor.


<div id="singleton-api-service"></div>

## API Service Singleton

Por padrao, o helper `api` cria uma nova instancia a cada vez. Para usar um singleton, passe uma instancia em vez de uma factory em `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Configuracao Avancada

### Inicializacao Personalizada do Dio

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

### Acessando a Instancia do Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Helper de Paginacao

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callbacks de Eventos

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Propriedades Sobrescreviveis

| Propriedade | Tipo | Padrao | Descricao |
|-------------|------|--------|-----------|
| `baseUrl` | `String` | `""` | URL base para todas as requisicoes |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Interceptors do Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Decoders de modelo para conversao JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Se deve chamar `setAuthHeaders` antes das requisicoes |
| `retry` | `int` | `0` | Tentativas de retentativa padrao |
| `retryDelay` | `Duration` | `1 second` | Atraso padrao entre retentativas |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Verificar conectividade antes das requisicoes |
