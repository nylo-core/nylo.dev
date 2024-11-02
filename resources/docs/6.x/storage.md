# Storage

---

<a name="section-1"></a>
- [Introduction to storage](#introduction "Introduction to storage")
- Saving and retrieving data
  - [Saving values](#save-values "Saving values")
  - [Retrieve values](#retrieve-values "Retrieve values")
  - [Keys](#keys "Keys")
  - [Save JSON](#save-json "Save JSON")
  - [Retrieve JSON](#retrieve-json "Retrieve JSON")
- Lightweight Storage
  - [Backpack Storage](#backpack-storage "Backpack Storage")
  - [Persist Data with Backpack](#persist-data-with-backpack "Persist Data with Backpack")
- Collections
  - [Introduction to collections](#introduction-to-collections "Introduction to collections")
  - [Add to a collection](#add-to-a-collection "Add to a collection")
  - [Retrieve a collection](#retrieve-a-collection "Retrieve to a collection")
  - [Delete a collection](#delete-a-collection "Delete a collection")
- [Sessions](#introduction-to-sessions "Introduction to sessions")

<a name="introduction"></a>
<br>

## Introduction

{{ config('app.name') }} has a built-in storage class called `NyStorage` which allows you to save and retrieve data on the user's device.

Under the hood, {{ config('app.name') }} uses the <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> package to store data securely.

Here's an example of saving and retrieving data.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

// Save a value
await storageSave("coins", '10');

// Retrieve a value
String coins = await storageRead('coins'); // "10"
```

In this guide, you'll learn about NyStorage, Backpack, Collections and Sessions.

<a name="save-values"></a>
<br>

## Save values

To save a value, you can use one of the below helpers.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...
await storageSave("coins", '10');
// or
await NyStorage.save("coins", '10');
```

> Data will persist on the user's device using NyStorage. E.g. if they exit the app, you can retrieve the same data that was stored previously.

<a name="retrieve-values"></a>
<br>

## Retrieve values

To retrieve a value, you can use the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

// Default
String coins = await storageRead('coins'); // "10" (string)

// Integer
int coins = await storageRead<int>('coins'); // 10 (int)

// double
double coins = await storageRead<double>('coins'); // 10.00 (double)

// or

// Default
String coins = await NyStorage.read('coins'); // "10" (string)

// Integer
int coins = await NyStorage.read<int>('coins'); // 10 (int)

// double
double coins = await NyStorage.read<double>('coins'); // 10.00 (double)
```


<a name="keys"></a>
<br>

## Keys

You can use the `Keys` class to organise all your shared preference keys in your project.

**File: config/keys.dart**

```dart
/* Keys
|-------------------------------------------------------------------------- */

class Keys {

  // Define the keys you want to be synced on boot
  static syncedOnBoot() => () async {
      return [
        bearerToken,
        completedOnboarding,
        coins.defaultValue(0), // give the user 0 coins by default
        themePreference.defaultValue('light'), // set the default theme to light
      ];
    };

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';
  static StorageKey completedOnboarding = 'SK_COMPLETED_ONBOARDING';
  static StorageKey coins = 'SK_COINS';
  static StorageKey themePreference = 'SK_THEME_PREFERENCE';
}
```

> **Note**: The `StorageKey` class extends `String`. It has a few helper methods to make it easier to work with local storage.

```dart
await Keys.coins.save(10); // save 10 coins

int? coins = await Keys.coins.read(); // 10

await Keys.coins.delete(); // delete the coins key

await Keys.themePreference.save('dark'); // save the theme preference

bool? themePreference = await Keys.themePreference.exists(); // true

await Keys.themePreference.delete(); // delete the theme preference key
```

You can also use the `StorageKey` class when saving or retrieving data.

```dart
import 'package:flutter_app/config/storage_keys.dart';
...

class _MyHomePageState extends NyPage<MyHomePage> {

  // Example storing values in NyStorage
  _storeValues() async {
    await Keys.favouriteColor.save('Grey');
    // or
    await storageSave(Keys.favouriteColor, 'Grey');
 }

    // Example reading values from NyStorage
    _readValues() async {
        String? userName = await Keys.favouriteColor.read(); // Grey
        // or
        String? userName = await storageRead(Keys.favouriteColor); // Grey
    }
}
```

<a name="save-json"></a>
<br>

## Save JSON

You can save JSON data using the `NyStorage.storageSave` method.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

Map<String, dynamic> user = {
  "name": "Anthony",
  "nickname": "Tone"
};

await storageSave("user", user);

await storageRead("user"); // {"name": "Anthony", "nickname": "Tone"}
```

<a name="retrieve-json"></a>
<br>

## Retrieve JSON

You can retrieve JSON data using the `NyStorage.read` method.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

Map<String, dynamic>? user = await storageRead("user");
print(user); // {"name": "Anthony", "email": "agordon@mail.com"}
```


<a name="backpack-storage"></a>
<br>

## Backpack Storage

{{ config('app.name') }} includes a lightweight storage class called `Backpack`. 
This class is designed for storing <b>small-sized</b> pieces of data during a user's session.

The Backpack class isn't asynchronous so you can <b>set/get</b> data on the fly.

Here's the Backpack class in action.

### Set data

```dart 
// saving a string
backpackSave('user_api_token', 'a secure token 12345');

// saving an object
User user = User();
backpackSave('user', user);

// saving an int
backpackSave('my_lucky_no', 7);

// or 

// storing a string
Backpack.instance.save('user_api_token', 'a secure token');

// storing an object
User user = User();
Backpack.instance.save('user', user);

// storing an int
Backpack.instance.save('my_lucky_no', 7);
```

### Read data

```dart 
backpackRead('user_api_token'); // a secure token

backpackRead('user'); // User instance

backpackRead('my_lucky_no'); // 7

// or

Backpack.instance.read('user_api_token'); // a secure token

Backpack.instance.read('user'); // User instance

Backpack.instance.read('my_lucky_no'); // 7
```

### Real world usage

A great example for when you might want to use this class over the [NyStorage](/docs/{{$version}}/storage#save-values) class is when e.g. storing a user's `api_token` for authentication.

```dart 
// login a user
LoginResponse loginResponse = await _apiService.loginUser('email': '...', 'password': '...');

String userToken = loginResponse.token;
// Save the user's token to NyStorage for persisted storage
await NyStorage.save('user_token', userToken);

// Save the token to the Backpack class to ensure the user is authenticated for subsequent API requests
backpackSave('user_token', userToken);
```

Now in our API Service, we can set the auth header from our `Backpack` class without having to wait on the async response.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) 
        : super(buildContext, decoders: modelDecoders);
  ...
  Future<dynamic> accountDetails() async {
    return await network(
        request: (request) => request.get("/account/1"),
        bearerToken: Backpack.instance.read('user_api_token')
    );
  }
}
```

<a name="persist-data-with-backpack"></a>
<br>

## Persist data with Backpack

You can use the [`NyStorage`](/docs/{{$version}}/storage#save-values) class to persist data but if you also need to save it to your App's Backpack storage, use the below parameter "**inBackpack**".

Here's an example.

```dart
// Save data in secure storage & in memory using Backpack
await NyStorage.save('user_token', 'a token 123', inBackpack: true);

// Fetch data back with Backpack
Backpack.instance.read('user_token'); // "a token 123"
```

> By default, NyStorge will not store data in Backpack unless the `inBackpack` parameter is set to `true`

<a name="introduction-to-collections"></a>
<br>

## Introduction to Collections

Collections can be used when you want to store a collection of things. E.g. a list of strings, objects or ints.
Here's an example of setting, getting and deleting values from a collection.

Here's an example.

1. We want to store a list of product ids each time a user taps 'add product'
2. Show the list of product ids on a different page
3. Delete the product id from the collection

``` dart
// 1 - Adding an item to a collection
_addProduct(int productId) async {
  await NyStorage.addToCollection("product_ids", item: productId); // adds productId to the collection
}

// 2 - Page to display the data, e.g. cart_page.dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: NyFutureBuilder(future: NyStorage.readCollection("product_ids"), child: (context, data) {
         return ListView(
           children: data.map((productId) {
             return Text(productId.toString());
           }).toList(),
         );
       },)
    ),
  );
}

// 3 - Example removing an item from the collection
_removeItemFromCollection(int index) async {
  await NyStorage.deleteFromCollection(index, key: "product_ids");
}
```

<a name="add-to-a-collection"></a>
<br>

## Add to a collection 

You can add new items to your collections by calling `NyStorage.addToCollection("a_storage_key", newItem: "1");`.

Example

``` dart 
await NyStorage.addToCollection("a_storage_key", item: "1");
await NyStorage.addToCollection("a_storage_key", item: "2");
await NyStorage.addToCollection("a_storage_key", item: "3");

await NyStorage.readCollection("a_storage_key"); // ["1", "2", "3"]
```

<a name="retrieve-a-collection"></a>
<br>

## Retrieve a collection

You can retrieve a collections by calling `NyStorage.readCollection("a_storage_key");`.

Example

``` dart 
await NyStorage.addToCollection("a_storage_key", item: "Anthony");
await NyStorage.addToCollection("a_storage_key", item: "Tim");

await NyStorage.readCollection("a_storage_key"); // ["Anthony", "Tim"]
```

<a name="delete-a-collection"></a>
<br>

## Delete a collection

You can delete a collections by calling `NyStorage.deleteCollection("a_storage_key");`.

Example

``` dart 
await NyStorage.readCollection("a_storage_key"); // ["Anthony", "Kyle"]

await NyStorage.deleteFromCollection(0, key: "a_storage_key"); // ["Kyle"]
```

<a name="introduction-to-sessions"></a>
<br>

## Introduction to Sessions

Sessions are a way to store data for a user's session. This data is stored in memory and is not persisted.

Here's an example of setting, getting and deleting values from a session.

``` dart
// 1 - Adding an item to a session
session('onboarding')
    .add('first_name', 'Anthony')
    .add('interests', ['coding', 'gaming']);

// 2 - Getting an item from a session
String firstName = session('onboarding').get('first_name');
List<String> interests = session('onboarding').get('interests');

// 3 - Deleting an item from a session
session('onboarding').delete('first_name');

// 4 - Clearing the session
session('onboarding').clear();
```

> Sessions are stored in memory and are not persisted. They are useful for storing data for a user's session.

---
