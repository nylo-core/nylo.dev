# Cache

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Bases
  - [Enregistrer avec expiration](#save-with-expiration "Enregistrer avec expiration")
  - [Enregistrer indefiniment](#save-forever "Enregistrer indefiniment")
  - [Recuperer des donnees](#retrieve-data "Recuperer des donnees")
  - [Stocker des donnees directement](#store-data-directly "Stocker des donnees directement")
  - [Supprimer des donnees](#remove-data "Supprimer des donnees")
  - [Verifier le cache](#check-cache "Verifier le cache")
- Reseau
  - [Mise en cache des reponses API](#caching-api-responses "Mise en cache des reponses API")
- [Support des plateformes](#platform-support "Support des plateformes")
- [Reference API](#api-reference "Reference API")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit un systeme de cache base sur des fichiers pour stocker et recuperer des donnees efficacement. La mise en cache est utile pour stocker des donnees couteuses comme les reponses API ou les resultats calcules.

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

## Enregistrer avec expiration

Utilisez `saveRemember` pour mettre en cache une valeur avec un temps d'expiration :

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Lors des appels suivants dans la fenetre d'expiration, la valeur mise en cache est retournee sans executer le callback.

<div id="save-forever"></div>

## Enregistrer indefiniment

Utilisez `saveForever` pour mettre en cache des donnees indefiniment :

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Les donnees restent en cache jusqu'a leur suppression explicite ou le vidage du cache de l'application.

<div id="retrieve-data"></div>

## Recuperer des donnees

Obtenez directement une valeur mise en cache :

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

Si l'element mis en cache a expire, `get()` le supprime automatiquement et retourne `null`.

<div id="store-data-directly"></div>

## Stocker des donnees directement

Utilisez `put` pour stocker une valeur directement sans callback :

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Supprimer des donnees

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Verifier le cache

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

## Mise en cache des reponses API

### Utilisation du helper api()

Mettez en cache les reponses API directement :

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Utilisation de NyApiService

Definissez la mise en cache dans les methodes de votre service API :

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

Appelez ensuite la methode :

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Support des plateformes

Le cache de {{ config('app.name') }} utilise un stockage base sur des fichiers et dispose du support de plateformes suivant :

| Plateforme | Support |
|----------|---------|
| iOS | Support complet |
| Android | Support complet |
| macOS | Support complet |
| Windows | Support complet |
| Linux | Support complet |
| Web | Non disponible |

Sur la plateforme web, le cache se degrade gracieusement - les callbacks sont toujours executes et la mise en cache est contournee.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Reference API

### Methodes

| Methode | Description |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Met en cache une valeur avec expiration. Retourne la valeur en cache ou le resultat du callback. |
| `saveForever<T>(key, callback)` | Met en cache une valeur indefiniment. Retourne la valeur en cache ou le resultat du callback. |
| `get<T>(key)` | Recupere une valeur en cache. Retourne `null` si non trouvee ou expiree. |
| `put<T>(key, value, {seconds})` | Stocke une valeur directement. Expiration optionnelle en secondes. |
| `clear(key)` | Supprime un element specifique du cache. |
| `flush()` | Supprime tous les elements en cache. |
| `has(key)` | Verifie si une cle existe dans le cache. Retourne `bool`. |
| `documents()` | Obtient la liste de toutes les cles du cache. Retourne `List<String>`. |
| `size()` | Obtient la taille totale du cache en octets. Retourne `int`. |

### Proprietes

| Propriete | Type | Description |
|----------|------|-------------|
| `isAvailable` | `bool` | Indique si la mise en cache est disponible sur la plateforme actuelle. |

