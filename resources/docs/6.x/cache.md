# Cache

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Basics
    - [Save Data with Expiration Time](#save-data-with-expiration-time "Save Data with expiration time")
    - [Save Data Forever](#save-data-forever "Save Data Forever")
    - [Retrieve Data](#retrieve-data "Retrieve Data")
    - [Remove Data](#remove-data "Remove Data")
- Networking
    - [Caching API Responses](#caching-api-responses "Caching API Responses")
- API Methods
    - [Methods](#methods "Methods")

<a name="introduction"></a>
<br>
## Introduction

Nylo provides a flexible cache driver out the box. You can store and retrieve items on the fly.

Caching is most useful when you need to store data that is expensive to generate or retrieve. For example, you can cache the result of an API request to avoid making the same request multiple times.

In this section we'll dive into the basics of caching in Nylo.


<a name="save-data-with-expiration-time"></a>
<br>

## Save Data with Expiration Time

To store an item in the cache, you can use the `saveRemember` method. The method accepts three arguments: the **key**, the **expiration** time in seconds and the **callback** that returns the value to be stored.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

String key = "hello_world"; // Cache key
int seconds = 60; // 1 minute expiration

String val = await cache().saveRemember(key, seconds, () {
    printInfo("Cache miss");

    return "Hello World";
});

printInfo(val); // Hello World
```

In the example above, the `saveRemember` method will store the value "Hello World" in the cache under the key "hello_world" for 60 seconds. If the key already exists in the cache, the method will return the value stored in the cache.

<a name="save-data-forever"></a>
<br>

## Save Data Forever

To store an item in the cache indefinitely, you can use the `saveForever` method. The method accepts two arguments: the **key** and the **callback** that returns the value to be stored.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

String key = "hello_world"; // Cache key

String val = await cache().saveForever(key, () {
    printInfo("Cache miss");

    return "Hello World";
});

printInfo(val); // Hello World
```

In the example above, the `saveForever` method will store the value "Hello World" in the cache under the key "hello_world" indefinitely. If the key already exists in the cache, the method will return the value stored in the cache.


<a name="retrieve-data"></a>
<br>

## Retrieve Data

To retrieve an item from the cache, you can use the `get` method. The method accepts the **key** of the item to retrieve.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

String key = "hello_world"; // Cache key

String val = await cache().get(key);

printInfo(val); // Hello World
```

In the example above, the `get` method will return the value stored in the cache under the key "hello_world".

<a name="remove-data"></a>
<br>

## Remove Data

To remove an item from the cache, you can use the `forget` method. The method accepts the **key** of the item to remove.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

String key = "hello_world"; // Cache key

await cache().forget(key);
```

In the example above, the `forget` method will remove the item stored in the cache under the key "hello_world".

<a name="caching-api-responses"></a>
<br>

## Caching API Responses

You can use the cache driver to cache API responses. This is useful when you want to avoid making the same request multiple times.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

Map<String, dynamic>? githubResponse = await api<ApiService>(
            (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
    cacheDuration: const Duration(seconds: 60),
    cacheKey: "github_nylo_dev",
);

printInfo(githubResponse);
```

In the example above, the `api` method will make a GET request to the GitHub API to fetch the repo data for `nylo-dev`. The response will be cached for 60 seconds under the key `github_nylo_dev`.

---

You can also cache the response in an **ApiService** when using the `network` method.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

class ApiService extends NyApiService {
  ...

  Future githubInfo() async {
    return await network(
      request: (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
      cacheKey: "github_nylo_info",
      cacheDuration: const Duration(hours: 1),
    );
  }
}
```

Then, use the `githubInfo` method to fetch the GitHub user profile.

```dart
import 'package:nylo_framework/nylo_framework.dart';
...

Map<String, dynamic>? githubResponse = await api<ApiService>((request) => request.githubInfo());

printInfo(githubResponse);
```

In the example above, the `githubInfo` method will fetch the user profile of the `nylo-core` user from the GitHub API. The response will be cached for 1 hour under the key `github_nylo_info`.

<a name="methods"></a>
<br>

## API Methods and Properties

### Methods

- `saveRemember(String key, int seconds, Function callback)`: Save an item in the cache with an expiration time.
- `saveForever(String key, Function callback)`: Save an item in the cache indefinitely.
- `get(String key)`: Retrieve an item from the cache.
- `clear(String key)`: Remove an item from the cache.
- `flush()`: Remove all items from the cache.
- `documents()`: Retrieve all items from the cache.
- `has(String key)`: Check if an item exists in the cache.
- `put(String key, dynamic value, int seconds)`: Store an item in the cache with an expiration time.
- `size()`: Retrieve the number of items in the cache.

---
