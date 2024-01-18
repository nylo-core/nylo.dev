# Storage

---

<a name="section-1"></a>
- [Introduction to storage](#introduction "Introduction to storage")
- Storing and retrieving data
  - [Store values](#store-values "Store values")
  - [Retrieve values](#retrieve-values "Retrieve values")
  - [Storage keys](#storage-keys "Storage keys")
- Lightweight Storage
  - [Backpack Storage](#backpack-storage "Backpack Storage")
  - [Persist Data with Backpack](#persist-data-with-backpack "Persist Data with Backpack")
- Collections
  - [Introduction to collections](#introduction-to-collections "Introduction to collections")
  - [Add to a collection](#add-to-a-collection "Add to a collection")
  - [Retrieve a collection](#retrieve-a-collection "Retrieve to a collection")
  - [Delete a collection](#delete-a-collection "Delete a collection")

<a name="introduction"></a>
<br>

## Introduction

In {{ config('app.name') }} you can save data to the users device using the `NyStorage` class. 

Under the hood, {{ config('app.name') }} uses the <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> package to save and retrieve data.

<a name="store-values"></a>
<br>

## Store values

To store values, you can use the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

NyStorage.store("com.company.myapp.coins", "10");
```

Data will persist on the user's device using NyStorage. E.g. if they exit the app, you can retrieve the same data that was stored previously.

<a name="retrieve-values"></a>
<br>

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


<a name="storage-keys"></a>
<br>

## Storage Keys

This class is useful to reference **Strings** which you can later use in your `NyStorage` or `Backpack` class.
You can use the `StorageKey` class to organise all the shared preference Strings in your project.

Open your {{ config('app.name') }} project and open the **"config/storage_keys.dart"** file.

```dart
/* Storage Keys
|-------------------------------------------------------------------------- */

class StorageKey {
  static String userToken = "USER_TOKEN";

  /// Add your storage keys here...

}
```

This class helps organise all your String keys for your Storage variables.

#### How to use Storage Keys in your project

```dart 
import 'package:flutter_app/config/storage_keys.dart';
...

class _MyHomePageState extends NyState<MyHomePage> {

  // Example storing values in NyStorage
  _storeValues() async {
    await NyStorage.store(StorageKey.userToken , 'Anthony');
    // or
    await StorageKey.userToken.store('Anthony');
  }

  // Example reading values from NyStorage
  _readValues() async {
    String? userName = await NyStorage.read(StorageKey.userToken); // Anthony
    // or
    String? userName = await StorageKey.userToken.read(); // Anthony
  }
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

<a name="persist-data-with-backpack"></a>
<br>

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
  await NyStorage.addToCollection("product_ids", newItem: productId); // adds productId to the collection
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
await NyStorage.addToCollection("a_storage_key", newItem: "1");
await NyStorage.addToCollection("a_storage_key", newItem: "2");
await NyStorage.addToCollection("a_storage_key", newItem: "3");

await NyStorage.readCollection("a_storage_key"); // ["1", "2", "3"]
```

<a name="retrieve-a-collection"></a>
<br>

## Retrieve a collection

You can retrieve a collections by calling `NyStorage.readCollection("a_storage_key");`.

Example

``` dart 
await NyStorage.addToCollection("a_storage_key", newItem: "Anthony");
await NyStorage.addToCollection("a_storage_key", newItem: "Kyle");

await NyStorage.readCollection("a_storage_key"); // ["Anthony", "Kyle"]
```

<a name="delete-a-collection"></a>
<br>

## Delete a collection

You can delete a collections by calling `NyStorage.deleteCollection("a_storage_key");`.

Example

``` dart 
await NyStorage.readCollection("a_storage_key"); // ["Anthony", "Kyle"]

await NyStorage.deleteFromCollection(0, "a_storage_key"); // ["Kyle"]
```
