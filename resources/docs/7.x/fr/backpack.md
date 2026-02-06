# Backpack

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Lire des donnees](#reading-data "Lire des donnees")
- [Enregistrer des donnees](#saving-data "Enregistrer des donnees")
- [Supprimer des donnees](#deleting-data "Supprimer des donnees")
- [Sessions](#sessions "Sessions")
- [Acceder a l'instance Nylo](#nylo-instance "Acceder a l'instance Nylo")
- [Fonctions utilitaires](#helper-functions "Fonctions utilitaires")
- [Integration avec NyStorage](#integration-with-nystorage "Integration avec NyStorage")
- [Exemples](#examples "Exemples pratiques")

<div id="introduction"></div>

## Introduction

**Backpack** est un systeme de stockage singleton en memoire dans {{ config('app.name') }}. Il offre un acces rapide et synchrone aux donnees pendant l'execution de votre application. Contrairement a `NyStorage` qui persiste les donnees sur l'appareil, Backpack stocke les donnees en memoire et les efface lorsque l'application est fermee.

Backpack est utilise en interne par le framework pour stocker des instances critiques comme l'objet `Nylo`, l'`EventBus` et les donnees d'authentification. Vous pouvez egalement l'utiliser pour stocker vos propres donnees qui doivent etre accessibles rapidement sans appels asynchrones.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Utilisation de base

Backpack utilise le **patron singleton** -- accedez-y via `Backpack.instance` :

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Lire des donnees

Lisez des valeurs depuis Backpack en utilisant la methode `read<T>()`. Elle prend en charge les types generiques et une valeur par defaut optionnelle :

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack deserialise automatiquement les chaines JSON en objets modele lorsqu'un type est fourni :

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Enregistrer des donnees

Enregistrez des valeurs en utilisant la methode `save()` :

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Ajouter des donnees

Utilisez `append()` pour ajouter des valeurs a une liste stockee sous une cle :

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Supprimer des donnees

### Supprimer une cle unique

``` dart
Backpack.instance.delete("api_token");
```

### Supprimer toutes les donnees

La methode `deleteAll()` supprime toutes les valeurs **sauf** les cles reservees du framework (`nylo` et `event_bus`) :

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sessions

Backpack fournit une gestion de sessions pour organiser les donnees en groupes nommes. Cela est utile pour stocker des donnees liees ensemble.

### Mettre a jour une valeur de session

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Obtenir une valeur de session

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Supprimer une cle de session

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Vider une session entiere

``` dart
Backpack.instance.sessionFlush("cart");
```

### Obtenir toutes les donnees d'une session

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Acceder a l'instance Nylo

Backpack stocke l'instance de l'application `Nylo`. Vous pouvez la recuperer en utilisant :

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Verifiez si l'instance Nylo a ete initialisee :

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Fonctions utilitaires

{{ config('app.name') }} fournit des fonctions utilitaires globales pour les operations courantes de Backpack :

| Fonction | Description |
|----------|-------------|
| `backpackRead<T>(key)` | Lire une valeur depuis Backpack |
| `backpackSave(key, value)` | Enregistrer une valeur dans Backpack |
| `backpackDelete(key)` | Supprimer une valeur de Backpack |
| `backpackDeleteAll()` | Supprimer toutes les valeurs (preserve les cles du framework) |
| `backpackNylo()` | Obtenir l'instance Nylo depuis Backpack |

### Exemple

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integration avec NyStorage

Backpack s'integre avec `NyStorage` pour un stockage combine persistant + en memoire :

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Ce patron est utile pour des donnees comme les jetons d'authentification qui necessitent a la fois la persistance et un acces synchrone rapide (par exemple, dans les intercepteurs HTTP).

<div id="examples"></div>

## Exemples

### Stocker des jetons d'authentification pour les requetes API

``` dart
// In your auth interceptor
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Gestion de panier basee sur les sessions

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Drapeaux de fonctionnalites rapides

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
