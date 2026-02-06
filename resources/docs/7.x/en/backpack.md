# Backpack

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Reading Data](#reading-data "Reading Data")
- [Saving Data](#saving-data "Saving Data")
- [Deleting Data](#deleting-data "Deleting Data")
- [Sessions](#sessions "Sessions")
- [Accessing the Nylo Instance](#nylo-instance "Accessing the Nylo Instance")
- [Helper Functions](#helper-functions "Helper Functions")
- [Integration with NyStorage](#integration-with-nystorage "Integration with NyStorage")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

**Backpack** is an in-memory singleton storage system in {{ config('app.name') }}. It provides fast, synchronous access to data during your app's runtime. Unlike `NyStorage` which persists data to the device, Backpack stores data in memory and is cleared when the app is closed.

Backpack is used internally by the framework to store critical instances like the `Nylo` app object, `EventBus`, and authentication data. You can also use it to store your own data that needs to be accessed quickly without async calls.

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

## Basic Usage

Backpack uses the **singleton pattern** -- access it through `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Reading Data

Read values from Backpack using the `read<T>()` method. It supports generic types and an optional default value:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack automatically deserializes JSON strings to model objects when a type is provided:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Saving Data

Save values using the `save()` method:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Appending Data

Use `append()` to add values to a list stored at a key:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Deleting Data

### Delete a Single Key

``` dart
Backpack.instance.delete("api_token");
```

### Delete All Data

The `deleteAll()` method removes all values **except** reserved framework keys (`nylo` and `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sessions

Backpack provides session management for organizing data into named groups. This is useful for storing related data together.

### Update a Session Value

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Get a Session Value

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Remove a Session Key

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Flush an Entire Session

``` dart
Backpack.instance.sessionFlush("cart");
```

### Get All Session Data

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Accessing the Nylo Instance

Backpack stores the `Nylo` application instance. You can retrieve it using:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Check if the Nylo instance has been initialized:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Helper Functions

{{ config('app.name') }} provides global helper functions for common Backpack operations:

| Function | Description |
|----------|-------------|
| `backpackRead<T>(key)` | Read a value from Backpack |
| `backpackSave(key, value)` | Save a value to Backpack |
| `backpackDelete(key)` | Delete a value from Backpack |
| `backpackDeleteAll()` | Delete all values (preserves framework keys) |
| `backpackNylo()` | Get the Nylo instance from Backpack |

### Example

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integration with NyStorage

Backpack integrates with `NyStorage` for combined persistent + in-memory storage:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

This pattern is useful for data like authentication tokens that need both persistence and fast synchronous access (e.g., in HTTP interceptors).

<div id="examples"></div>

## Examples

### Storing Auth Tokens for API Requests

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

### Session-Based Cart Management

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Quick Feature Flags

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
