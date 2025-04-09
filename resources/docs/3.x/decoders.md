# Decoders

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Usage
  - [Model Decoders](#model-decoders "Model Decoders")
  - [API Decoders](#api-decoders "API Decoders")


<div id="introduction"></div>
<br>
## Introduction

Decoders are a new concept introduced in Nylo which allows you to decode data into objects or classes.
You'll likely use decoders when dealing with the [networking](/docs/3.x/networking) class or if want to use the `api` helper in Nylo.

> By default, the location for decoders is `lib/config/decoders.dart`

The decoders.dart file will contain two variables:
- [modelDecoders](#model-decoders) - Handles all your model decoders 
- [apiDecoders](#api-decoders) - Handles all your API decoders 

<div id="model-decoders"></div>
<br>

## Model decoders

Model decoders are new in Nylo, they provide a way for you to morph data payloads into model representations.

The `network()` helper method will use the `modelDecoders` variable inside your <b>config/decoders.dart</b> file to determine which decoder to use.

Here's an example.

Here's how the `network` helper uses modelDecoders.

```dart
class ApiService extends BaseApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext);

  @override
  String get baseUrl => "https://jsonplaceholder.typicode.com";

  Future<User?> fetchUsers() async {
    return await network<User>(
        request: (request) => request.get("/users"),
    );
  }
...
```

The `fetchUsers` method will automatically decode the payload from the request into a `User`.

How does this work?

You have a `User` class like the below.

```dart
class User {
  String? name;
  String? email;

  User.fromJson(dynamic data) {
    this.name = data['name'];
    this.email = data['email'];
  }

  toJson()  => {
    "name": this.name,
    "email": this.email
  };
}
```

You can see from the above that this class has a `fromJson` method which provides us with a way to initialize the class.

We can initialize this class by calling the below method.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Now, to set up our decoders, we have do the following.

<b>File:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

In the modelDecoders file, we need to provide the `Type` as the key and handle the morph in the value like the above example.

The `data` argument will contain the payload from the API request.

<div id="api-decoders"></div>
<br>

## API decoders

API decoders are used when calling the `api` helper method.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

The `api` helper will match the correct API Service using generics so you can call the below helper to access your service. 

```dart
await api<MyService>((request) => request.callMyMethod());
```

Before using the `api` helper, you will need to first add your API Service into <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```