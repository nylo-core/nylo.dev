# Storage

---

<a name="section-1"></a>
- [Introduction to storage](#introduction "Introduction to storage")
- Storing and retrieving data
  - [Store values](#store-values "Store values")
  - [Retrieve values](#retrieve-values "Retrieve values")
- Storable models
  - [Introduction to storable models](#introduction-to-storable-models "Introduction to storable models")
  - [Saving a Storable model](#saving-a-storable-model "Saving a Storable Model")
  - [Retrieve a Storable model](#retrieve-a-storable-model "Retrieving a Storable Model")
- Lightweight Storage
  - [Backpack Storage](#backpack-storage "Backpack Storage")
  - [Persist Data with Backpack](#persist-data-with-backpack "Persist Data with Backpack")
- [Storage keys](#storage-keys "Storage keys")

<div id="introduction"></div>

## Introduction

In {{ config('app.name') }} you can save data to the users device using the `NyStorage` class. 

Under the hood, {{ config('app.name') }} uses the <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> package to save and retrieve data.

<div id="store-values"></div>

## Store values

To store values, you can use the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

NyStorage.store("com.company.myapp.coins", "10");
```

Data will persist on the user's device using NyStorage. E.g. if they exit the app, you can retrieve the same data that was stored previously.

<div id="retrieve-values"></div>

## Retrieve values

To retrieve values, you can use the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

// Default
String coins = await NyStorage.read("com.company.myapp.coins"); // 10

// String
String coins = await NyStorage.read<String>("com.company.myapp.coins"); // 10

// Integer
int coins = await NyStorage.read<int>("com.company.myapp.coins"); // 10

// double
double coins = await NyStorage.read<double>("com.company.myapp.coins"); // 10.00
```

<div id="introduction-to-storable-models"></div>

## Introduction to storable models

Storable models are handy for storing small-sized pieces of data on the user's device. 

It's useful for storing information such as:
- Progress in a game
- API Token
- Locale preference

Here's a model that extends the `Storable` class.

``` dart
class User extends Storable {
  String token;
  String username;
  String favouriteCity; 

  User({this.token, this.username, this.favouriteCity});

  @override
  toStorage() => {
      "token": this.token, 
      "username": this.username, 
      "favourite_city": this.favouriteCity
    };
  

  @override
  fromStorage(dynamic data) {
    this.token = data['token'];
    this.username = data['username'];
    this.favouriteCity = data['favourite_city'];
  }
}

```

After extending the `Storable` class, you then need to override the `toStorage()` and `fromStorage` methods.

- toStorage() - Creates the payload to be stored.

- fromStorage(dynamic data) - This will create the model from the `data` payload, the keys should match the toStorage() method.

<div id="saving-a-storable-model"></div>

## Saving a Storable model

To save a Storable model, you can use the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

User user = new User();
user.username = "Anthony";

String key = "com.company.myapp.auth_user";

// saves to storage
user.save(key);

// or 

NyStorage.store(key, user);
```

<div id="retrieve-a-storable-model"></div>

## Retrieve a Storable model

To retrieve a Storable model, you can use the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...
String key = "com.company.myapp.auth_user";

User user = await NyStorage.read(key, model: new User());
print(user.username); // Anthony
```

<div id="backpack-storage"></div>

## Backpack Storage

{{ config('app.name') }} includes a lightweight storage class called `Backpack`. 
This class is designed for storing <b>small-sized</b> pieces of data during a user's session.

The Backpack class isn't asynchronous so you can <b>set/get</b> data on the fly.

Here's the Backpack class in action.

### Set data

```dart 
// storing a string
Backpack.instance.set('user_api_token', 'a secure token');

// storing an object
User user = User();
Backpack.instance.set('user', user);

// storing an int
Backpack.instance.set('my_lucky_no', 7);
```

### Read data

```dart 
Backpack.instance.read('user_api_token'); // a secure token

Backpack.instance.read('user'); // User instance

Backpack.instance.read('my_lucky_no'); // 7
```

### Real world usage

A great example for when you might want to use this class over the [NyStorage](/docs/{{$version}}/storage#store-values) class is when e.g. storing a user's `api_token` for authentication.

```dart 
// login a user
LoginResponse loginResponse = await _apiService.loginUser('email': '...', 'password': '...');

String userToken = loginResponse.token;
// Store the user's token to NyStorage for persisted storage
await NyStorage.store('user_token', userToken);

// Store the token to the Backpack class to ensure the user is authenticated for subsequent API requests
Backpack.instance.set('user_token', userToken);
```

Now in our API Service, we can set the auth header from our Backpack class without having to wait on the async response.

```dart
class ApiService extends BaseApiService {
  ...
  Future<dynamic> accountDetails() async {
    return await network(
        request: (request) {
          String userToken = Backpack.instance.read('user_api_token');

          // Set auth header
          request.options.headers = {
            'Authorization': "Bearer " + userToken
          };
          
          return request.get("/account/1");
        },
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Persist data with Backpack

You can use the [`NyStorage`](/docs/{{$version}}/storage#store-values) class to persist data but if you also need to save it to your App's Backpack storage, use the below parameter "**inBackpack**".

Here's an example.

```dart
// Store data in secure storage & in memory using Backpack
await NyStorage.store('user_token', 'a token 123', inBackpack: true);

// Fetch data back with Backpack
Backpack.instance.read('user_token'); // "a token 123"
```

> By default, NyStorge will not store data in Backpack unless the `inBackpack` parameter is set to `true`

<div id="storage-keys"></div>

## Storage Keys

This class is useful to reference **Strings** which you can later use in your `NyStorage` or `Backpack` class.
You can use the `StorageKey` class to organise all the shared preference Strings in your project.

Open your {{ config('app.name') }} project and open the **"config/storage_keys.dart"** file.
> Please note. This file was added in {{ config('app.name') }} v3.2.0, if it's missing, you can manually add it.

```dart
/*
|--------------------------------------------------------------------------
| Storage Keys
| Add your storage keys here and then use them later to retrieve data.
| E.g. static String userCoins = "USER_COINS";
| String coins = NyStorage.read( StorageKey.userCoins );
|
| Learn more: https://nylo.dev/docs/{{$version}}/storage#storage-keys
|--------------------------------------------------------------------------
*/

class StorageKey {
  static String userToken = "USER_TOKEN";

  /// Add your storage keys here...

}
```

How to use in your project.

```dart 
import 'package:flutter_app/config/storage_keys.dart';
...

class _MyHomePageState extends NyState<MyHomePage> {

  @override
  init() async {
    // Store
    await NyStorage.store( StorageKey.userToken , 'Anthony');

    // Retrieve
    String userName = await NyStorage.read( StorageKey.userToken );
  }
```

This simple class helps organise all your String keys for your Storage variables.