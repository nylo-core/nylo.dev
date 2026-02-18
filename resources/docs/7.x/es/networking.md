# Networking

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- Realizar solicitudes HTTP
  - [Metodos de conveniencia](#convenience-methods "Metodos de conveniencia")
  - [Helper Network](#network-helper "Helper Network")
  - [Helper networkResponse](#network-response-helper "Helper networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Opciones base](#base-options "Opciones base")
  - [Agregar encabezados](#adding-headers "Agregar encabezados")
- Operaciones de archivos
  - [Subir archivos](#uploading-files "Subir archivos")
  - [Descargar archivos](#downloading-files "Descargar archivos")
- [Interceptores](#interceptors "Interceptores")
  - [Network Logger](#network-logger "Network Logger")
- [Usar un servicio API](#using-an-api-service "Usar un servicio API")
- [Crear un servicio API](#create-an-api-service "Crear un servicio API")
- [Transformar JSON a modelos](#morphing-json-payloads-to-models "Transformar JSON a modelos")
- Cache
  - [Almacenar respuestas en cache](#caching-responses "Almacenar respuestas en cache")
  - [Politicas de cache](#cache-policies "Politicas de cache")
- Manejo de errores
  - [Reintentar solicitudes fallidas](#retrying-failed-requests "Reintentar solicitudes fallidas")
  - [Verificaciones de conectividad](#connectivity-checks "Verificaciones de conectividad")
  - [Tokens de cancelacion](#cancel-tokens "Tokens de cancelacion")
- Autenticacion
  - [Configurar encabezados de autenticacion](#setting-auth-headers "Configurar encabezados de autenticacion")
  - [Refrescar tokens](#refreshing-tokens "Refrescar tokens")
- [Servicio API singleton](#singleton-api-service "Servicio API singleton")
- [Configuracion avanzada](#advanced-configuration "Configuracion avanzada")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} hace que la conexion en red sea sencilla. Defines los endpoints de la API en clases de servicio que extienden `NyApiService`, y luego los llamas desde tus paginas. El framework se encarga de la decodificacion JSON, el manejo de errores, el almacenamiento en cache y la conversion automatica de respuestas a tus clases de modelo (llamada "morphing").

Tus servicios API se encuentran en `lib/app/networking/`. Un proyecto nuevo incluye un `ApiService` por defecto:

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

Hay tres formas de realizar solicitudes HTTP:

| Enfoque | Devuelve | Ideal para |
|---------|----------|------------|
| Metodos de conveniencia (`get`, `post`, etc.) | `T?` | Operaciones CRUD simples |
| `network()` | `T?` | Solicitudes que necesitan cache, reintentos o encabezados personalizados |
| `networkResponse()` | `NyResponse<T>` | Cuando necesitas codigos de estado, encabezados o detalles de errores |

Internamente, {{ config('app.name') }} usa <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, un potente cliente HTTP.


<div id="convenience-methods"></div>

## Metodos de conveniencia

`NyApiService` proporciona metodos abreviados para operaciones HTTP comunes. Estos llaman a `network()` internamente.

### Solicitud GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Solicitud POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Solicitud PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Solicitud DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Solicitud PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Solicitud HEAD

Usa HEAD para verificar la existencia de un recurso u obtener encabezados sin descargar el cuerpo:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Helper Network

El metodo `network` te da mas control que los metodos de conveniencia. Devuelve los datos transformados (`T?`) directamente.

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

El callback `request` recibe una instancia de <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> con tu URL base e interceptores ya configurados.

### Parametros de network

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `request` | `Function(Dio)` | La solicitud HTTP a realizar (requerido) |
| `bearerToken` | `String?` | Token Bearer para esta solicitud |
| `baseUrl` | `String?` | Sobreescribir la URL base del servicio |
| `headers` | `Map<String, dynamic>?` | Encabezados adicionales |
| `retry` | `int?` | Numero de intentos de reintento |
| `retryDelay` | `Duration?` | Retraso entre reintentos |
| `retryIf` | `bool Function(DioException)?` | Condicion para reintentar |
| `connectionTimeout` | `Duration?` | Tiempo de espera de conexion |
| `receiveTimeout` | `Duration?` | Tiempo de espera de recepcion |
| `sendTimeout` | `Duration?` | Tiempo de espera de envio |
| `cacheKey` | `String?` | Clave de cache |
| `cacheDuration` | `Duration?` | Duracion del cache |
| `cachePolicy` | `CachePolicy?` | Estrategia de cache |
| `checkConnectivity` | `bool?` | Verificar conectividad antes de la solicitud |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback de exito |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback de error |


<div id="network-response-helper"></div>

## Helper networkResponse

Usa `networkResponse` cuando necesites acceso a la respuesta completa -- codigos de estado, encabezados, mensajes de error -- no solo los datos. Devuelve un `NyResponse<T>` en lugar de `T?`.

Usa `networkResponse` cuando necesites:
- Verificar codigos de estado HTTP para un manejo especifico
- Acceder a los encabezados de respuesta
- Obtener mensajes de error detallados para retroalimentacion al usuario
- Implementar logica de manejo de errores personalizada

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Luego usa la respuesta en tu pagina:

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
// network() — devuelve los datos directamente
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — devuelve la respuesta completa
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Ambos metodos aceptan los mismos parametros. Elige `networkResponse` cuando necesites inspeccionar la respuesta mas alla de solo los datos.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` envuelve la respuesta de Dio con datos transformados y helpers de estado.

### Propiedades

| Propiedad | Tipo | Descripcion |
|-----------|------|-------------|
| `response` | `Response?` | Respuesta original de Dio |
| `data` | `T?` | Datos transformados/decodificados |
| `rawData` | `dynamic` | Datos crudos de la respuesta |
| `headers` | `Headers?` | Encabezados de respuesta |
| `statusCode` | `int?` | Codigo de estado HTTP |
| `statusMessage` | `String?` | Mensaje de estado HTTP |
| `contentType` | `String?` | Tipo de contenido de los encabezados |
| `errorMessage` | `String?` | Mensaje de error extraido |

### Verificaciones de estado

| Getter | Descripcion |
|--------|-------------|
| `isSuccessful` | Estado 200-299 |
| `isClientError` | Estado 400-499 |
| `isServerError` | Estado 500-599 |
| `isRedirect` | Estado 300-399 |
| `hasData` | Los datos no son nulos |
| `isUnauthorized` | Estado 401 |
| `isForbidden` | Estado 403 |
| `isNotFound` | Estado 404 |
| `isTimeout` | Estado 408 |
| `isConflict` | Estado 409 |
| `isRateLimited` | Estado 429 |

### Helpers de datos

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Obtener datos o lanzar excepcion si es nulo
User user = response.dataOrThrow('User not found');

// Obtener datos o usar un valor por defecto
User user = response.dataOr(User.guest());

// Ejecutar callback solo si es exitoso
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Coincidencia de patrones en exito/fallo
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Obtener un encabezado especifico
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Opciones base

Configura las opciones por defecto de Dio para tu servicio API usando el parametro `baseOptions`:

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

Tambien puedes configurar opciones dinamicamente en una instancia:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Haz clic <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">aqui</a> para ver todas las opciones base que puedes configurar.


<div id="adding-headers"></div>

## Agregar encabezados

### Encabezados por solicitud

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

### Encabezados a nivel de servicio

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Extension RequestHeaders

El tipo `RequestHeaders` (un typedef de `Map<String, dynamic>`) proporciona metodos auxiliares:

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

| Metodo | Descripcion |
|--------|-------------|
| `addBearerToken(token)` | Establecer el encabezado `Authorization: Bearer` |
| `getBearerToken()` | Leer el token bearer de los encabezados |
| `addHeader(key, value)` | Agregar un encabezado personalizado |
| `hasHeader(key)` | Verificar si un encabezado existe |


<div id="uploading-files"></div>

## Subir archivos

### Subida de un solo archivo

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

### Subida de multiples archivos

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

## Descargar archivos

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

## Interceptores

Los interceptores te permiten modificar solicitudes antes de enviarlas, manejar respuestas y gestionar errores. Se ejecutan en cada solicitud realizada a traves del servicio API.

Usa interceptores cuando necesites:
- Agregar encabezados de autenticacion a todas las solicitudes
- Registrar solicitudes y respuestas para depuracion
- Transformar datos de solicitud/respuesta globalmente
- Manejar codigos de error especificos (ej., refrescar tokens en 401)

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

### Crear un interceptor personalizado

```bash
metro make:interceptor logging
```

**Archivo:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

{{ config('app.name') }} incluye un interceptor `NetworkLogger` integrado. Esta habilitado por defecto cuando `APP_DEBUG` es `true` en tu entorno.

### Configuracion

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

Puedes deshabilitarlo configurando `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Deshabilitar logger
        );
```

### Niveles de log

| Nivel | Descripcion |
|-------|-------------|
| `LogLevelType.verbose` | Imprimir todos los detalles de solicitud/respuesta |
| `LogLevelType.minimal` | Imprimir solo metodo, URL, estado y tiempo |
| `LogLevelType.none` | Sin salida de log |

### Filtrar logs

```dart
NetworkLogger(
  filter: (options, args) {
    // Solo registrar solicitudes a endpoints especificos
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Usar un servicio API

Hay dos formas de llamar a tu servicio API desde una pagina.

### Instanciacion directa

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

### Usando el helper api()

El helper `api` crea instancias usando tus `apiDecoders` de `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Con callbacks:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data es la instancia User? transformada
  },
  onError: (DioException dioException) {
    // Manejar el error
  },
);
```

### Parametros del helper api()

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `request` | `Function(T)` | La funcion de solicitud API |
| `context` | `BuildContext?` | Contexto de construccion |
| `headers` | `Map<String, dynamic>` | Encabezados adicionales |
| `bearerToken` | `String?` | Token Bearer |
| `baseUrl` | `String?` | Sobreescribir URL base |
| `page` | `int?` | Pagina de paginacion |
| `perPage` | `int?` | Elementos por pagina |
| `retry` | `int` | Intentos de reintento |
| `retryDelay` | `Duration?` | Retraso entre reintentos |
| `onSuccess` | `Function(Response, dynamic)?` | Callback de exito |
| `onError` | `Function(DioException)?` | Callback de error |
| `cacheKey` | `String?` | Clave de cache |
| `cacheDuration` | `Duration?` | Duracion del cache |


<div id="create-an-api-service"></div>

## Crear un servicio API

Para crear un nuevo servicio API:

```bash
metro make:api_service user
```

Con un modelo:

```bash
metro make:api_service user --model="User"
```

Esto crea un servicio API con metodos CRUD:

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

## Transformar JSON a modelos

"Morphing" es el termino de {{ config('app.name') }} para convertir automaticamente respuestas JSON en tus clases de modelo Dart. Cuando usas `network<User>(...)`, el JSON de la respuesta se pasa a traves de tu decodificador para crear una instancia de `User` -- sin necesidad de parseo manual.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Devuelve un solo User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Devuelve una lista de Users
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Los decodificadores se definen en `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

El parametro de tipo que pasas a `network<T>()` se compara con tu mapa `modelDecoders` para encontrar el decodificador correcto.

**Ver tambien:** [Decoders](/docs/{{$version}}/decoders#model-decoders) para detalles sobre el registro de decodificadores de modelo.


<div id="caching-responses"></div>

## Almacenar respuestas en cache

Almacena respuestas en cache para reducir las llamadas a la API y mejorar el rendimiento. El almacenamiento en cache es util para datos que no cambian frecuentemente, como listas de paises, categorias o configuracion.

Proporciona un `cacheKey` y opcionalmente un `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Limpiar cache

```dart
// Limpiar una clave de cache especifica
await apiService.clearCache("app_countries");

// Limpiar todo el cache de la API
await apiService.clearAllCache();
```

### Cache con el helper api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Politicas de cache

Usa `CachePolicy` para un control detallado sobre el comportamiento del cache:

| Politica | Descripcion |
|----------|-------------|
| `CachePolicy.networkOnly` | Siempre obtener de la red (por defecto) |
| `CachePolicy.cacheFirst` | Intentar cache primero, recurrir a la red si falla |
| `CachePolicy.networkFirst` | Intentar red primero, recurrir al cache si falla |
| `CachePolicy.cacheOnly` | Solo usar cache, error si esta vacio |
| `CachePolicy.staleWhileRevalidate` | Devolver cache inmediatamente, actualizar en segundo plano |

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

### Cuando usar cada politica

- **cacheFirst** -- Datos que raramente cambian. Devuelve datos en cache instantaneamente, solo obtiene de la red si el cache esta vacio.
- **networkFirst** -- Datos que deben estar frescos cuando sea posible. Intenta la red primero, recurre al cache si falla.
- **staleWhileRevalidate** -- UI que necesita una respuesta inmediata pero debe mantenerse actualizada. Devuelve datos en cache, luego actualiza en segundo plano.
- **cacheOnly** -- Modo sin conexion. Lanza un error si no existen datos en cache.

> **Nota:** Si proporcionas un `cacheKey` o `cacheDuration` sin especificar un `cachePolicy`, la politica por defecto es `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Reintentar solicitudes fallidas

Reintenta automaticamente las solicitudes que fallan.

### Reintento basico

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Reintento con retraso

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Reintento condicional

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // Solo reintentar en errores del servidor
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Reintento a nivel de servicio

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Verificaciones de conectividad

Falla rapidamente cuando el dispositivo esta sin conexion en lugar de esperar un timeout.

### A nivel de servicio

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Por solicitud

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

Cuando esta habilitado y el dispositivo esta sin conexion:
- La politica `networkFirst` recurre al cache
- Otras politicas lanzan `DioExceptionType.connectionError` inmediatamente


<div id="cancel-tokens"></div>

## Tokens de cancelacion

Gestiona y cancela solicitudes pendientes.

```dart
// Crear un token de cancelacion gestionado
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Cancelar todas las solicitudes pendientes (ej., al cerrar sesion)
apiService.cancelAllRequests('User logged out');

// Verificar el conteo de solicitudes activas
int count = apiService.activeRequestCount;

// Limpiar un token especifico cuando termines
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## Configurar encabezados de autenticacion

Sobreescribe `setAuthHeaders` para adjuntar encabezados de autenticacion a cada solicitud. Este metodo se llama antes de cada solicitud cuando `shouldSetAuthHeaders` es `true` (el valor por defecto).

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

### Deshabilitar encabezados de autenticacion

Para endpoints publicos que no necesitan autenticacion:

```dart
// Por solicitud
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// A nivel de servicio
apiService.setShouldSetAuthHeaders(false);
```

**Ver tambien:** [Authentication](/docs/{{ $version }}/authentication) para detalles sobre la autenticacion de usuarios y el almacenamiento de tokens.


<div id="refreshing-tokens"></div>

## Refrescar tokens

Sobreescribe `shouldRefreshToken` y `refreshToken` para manejar la expiracion de tokens. Estos se llaman antes de cada solicitud.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Verificar si el token necesita ser refrescado
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Usar la instancia fresca de Dio (sin interceptores) para refrescar el token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Guardar el nuevo token en el almacenamiento
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

El parametro `dio` en `refreshToken` es una nueva instancia de Dio, separada de la instancia principal del servicio, para evitar bucles de interceptores.


<div id="singleton-api-service"></div>

## Servicio API singleton

Por defecto, el helper `api` crea una nueva instancia cada vez. Para usar un singleton, pasa una instancia en lugar de una fabrica en `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // Nueva instancia cada vez

  ApiService: ApiService(), // Singleton — siempre la misma instancia
};
```


<div id="advanced-configuration"></div>

## Configuracion avanzada

### Inicializacion personalizada de Dio

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

### Acceder a la instancia de Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Helper de paginacion

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callbacks de eventos

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Propiedades sobreescribibles

| Propiedad | Tipo | Por defecto | Descripcion |
|-----------|------|-------------|-------------|
| `baseUrl` | `String` | `""` | URL base para todas las solicitudes |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Interceptores de Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Decodificadores de modelo para transformacion JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Si se debe llamar a `setAuthHeaders` antes de las solicitudes |
| `retry` | `int` | `0` | Intentos de reintento por defecto |
| `retryDelay` | `Duration` | `1 second` | Retraso por defecto entre reintentos |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Verificar conectividad antes de las solicitudes |
