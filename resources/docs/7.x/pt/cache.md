# Cache

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- Básico
  - [Salvar com Expiração](#save-with-expiration "Salvar com expiração")
  - [Salvar Permanentemente](#save-forever "Salvar permanentemente")
  - [Recuperar Dados](#retrieve-data "Recuperar dados")
  - [Armazenar Dados Diretamente](#store-data-directly "Armazenar dados diretamente")
  - [Remover Dados](#remove-data "Remover dados")
  - [Verificar Cache](#check-cache "Verificar cache")
- Networking
  - [Cache de Respostas de API](#caching-api-responses "Cache de respostas de API")
- [Suporte por Plataforma](#platform-support "Suporte por plataforma")
- [Referência da API](#api-reference "Referência da API")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 fornece um sistema de cache baseado em arquivos para armazenar e recuperar dados de forma eficiente. O cache é útil para armazenar dados custosos como respostas de API ou resultados computados.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Cache a value for 60 seconds
String? value = await cache().saveRemember("my_key", 60, () {
  return "Hello World";
});

// Retrieve the cached value
String? cached = await cache().get("my_key");

// Remove from cache
await cache().clear("my_key");
```

<div id="save-with-expiration"></div>

## Salvar com Expiração

Use `saveRemember` para armazenar um valor em cache com um tempo de expiração:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Em chamadas subsequentes dentro da janela de expiração, o valor em cache é retornado sem executar o callback.

<div id="save-forever"></div>

## Salvar Permanentemente

Use `saveForever` para armazenar dados em cache indefinidamente:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Os dados permanecem em cache até serem explicitamente removidos ou o cache do app ser limpo.

<div id="retrieve-data"></div>

## Recuperar Dados

Obtenha um valor em cache diretamente:

``` dart
// Retrieve cached value
String? value = await cache().get<String>("my_key");

// With type casting
Map<String, dynamic>? data = await cache().get<Map<String, dynamic>>("user_data");

// Returns null if not found or expired
if (value == null) {
  print("Cache miss or expired");
}
```

Se o item em cache expirou, `get()` o remove automaticamente e retorna `null`.

<div id="store-data-directly"></div>

## Armazenar Dados Diretamente

Use `put` para armazenar um valor diretamente sem um callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Remover Dados

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Verificar Cache

``` dart
// Check if a key exists
bool exists = await cache().has("my_key");

// Get all cached keys
List<String> keys = await cache().documents();

// Get total cache size in bytes
int sizeInBytes = await cache().size();
print("Cache size: ${sizeInBytes / 1024} KB");
```

<div id="caching-api-responses"></div>

## Cache de Respostas de API

### Usando o Helper api()

Armazene respostas de API em cache diretamente:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Usando NyApiService

Defina o cache nos métodos do seu serviço de API:

``` dart
class ApiService extends NyApiService {

  Future<Map<String, dynamic>?> getRepoInfo() async {
    return await network(
      request: (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
      cacheKey: "github_repo_info",
      cacheDuration: const Duration(hours: 1),
    );
  }

  Future<List<User>?> getUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
      cacheKey: "users_list",
      cacheDuration: const Duration(minutes: 10),
    );
  }
}
```

Depois chame o método:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Suporte por Plataforma

O cache do {{ config('app.name') }} usa armazenamento baseado em arquivos e possui o seguinte suporte por plataforma:

| Plataforma | Suporte |
|----------|---------|
| iOS | Suporte completo |
| Android | Suporte completo |
| macOS | Suporte completo |
| Windows | Suporte completo |
| Linux | Suporte completo |
| Web | Não disponível |

Na plataforma web, o cache degrada graciosamente - callbacks são sempre executados e o cache é ignorado.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Referência da API

### Métodos

| Método | Descrição |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Armazena um valor em cache com expiração. Retorna o valor em cache ou o resultado do callback. |
| `saveForever<T>(key, callback)` | Armazena um valor em cache indefinidamente. Retorna o valor em cache ou o resultado do callback. |
| `get<T>(key)` | Recupera um valor em cache. Retorna `null` se não encontrado ou expirado. |
| `put<T>(key, value, {seconds})` | Armazena um valor diretamente. Expiração opcional em segundos. |
| `clear(key)` | Remove um item específico do cache. |
| `flush()` | Remove todos os itens do cache. |
| `has(key)` | Verifica se uma chave existe no cache. Retorna `bool`. |
| `documents()` | Obtém lista de todas as chaves do cache. Retorna `List<String>`. |
| `size()` | Obtém o tamanho total do cache em bytes. Retorna `int`. |

### Propriedades

| Propriedade | Tipo | Descrição |
|----------|------|-------------|
| `isAvailable` | `bool` | Se o cache está disponível na plataforma atual. |
