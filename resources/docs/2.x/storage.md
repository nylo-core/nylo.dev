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


<div id="introduction"></div>
<br>
## Introduction

In Nylo you can save data to the users device using the `NyStorage` class. 

This uses the [flutter\_secure\_storage](https://pub.dev/packages/flutter_secure_storage) package to save and retrieve data.

<div id="store-values"></div>
<br>

## Store values

To store values, you can use the below helper.

``` dart
import 'package:nylo_support/helpers/helper.dart';
...

NyStorage.store("com.company.myapp.coins", "10");
```


<div id="retrieve-values"></div>
<br>

## Retrieve values

To retrieve values, you can use the below helper.

``` dart
import 'package:nylo_support/helpers/helper.dart';
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
<br>
## Introduction to storable models

Storable models are useful for storing small sized data to the users devices. 
It can be helpful way to store the users progress in a game, coins or even their api token.

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
<br>

## Saving a Storable model

To save a Storable model, you can use the below helper.

``` dart
import 'package:nylo_support/helpers/helper.dart';
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
<br>

## Retrieve a Storable model

To retrieve a Storable model, you can use the below helper.

``` dart
import 'package:nylo_support/helpers/helper.dart';
...
String key = "com.company.myapp.auth_user";

User user = await NyStorage.read(key, model: new User());
print(user.username); // Anthony
```
