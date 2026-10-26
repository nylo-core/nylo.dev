# Decoders

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Usage
  - [Model Decoders](#model-decoders "Model Decoders")
  - [Typed Decoders](#typed-decoders "Decode and decoders()")
  - [OneOf](#one-of "Decoding a response that can have more than one shape")
  - [API Decoders](#api-decoders "API Decoders")


<div id="introduction"></div>

## Introduction

Decoders are a new concept introduced in {{ config('app.name') }} which allows you to decode data into objects or classes.
You'll likely use decoders when dealing with the [networking](/docs/7.x/networking) class or if you want to use the `api` helper in {{ config('app.name') }}.

> By default, the location for decoders is `lib/config/decoders.dart`

The decoders.dart file will contain two variables:
- [modelDecoders](#model-decoders) - Handles all your model decoders 
- [apiDecoders](#api-decoders) - Handles all your API decoders 

<div id="model-decoders"></div>

## Model decoders

Model decoders are new in {{ config('app.name') }}, they provide a way for you to morph data payloads into model representations.

The `network()` helper method will use the `modelDecoders` variable inside your <b>config/decoders.dart</b> file to determine which decoder to use.

Here's an example.

Here's how the `network` helper uses modelDecoders.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) 
        : super(buildContext, decoders: modelDecoders);

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

Now, to set up our decoders, we have to do the following.

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

<div id="typed-decoders"></div>

## Typed Decoders

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` builds your `modelDecoders` map from a list. `Decode(User.fromJson)` decodes a `User`, and a `List<User>` too, with no entry of its own:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Use `from` to read the model from inside a wrapped body, like `{"data": {...}}`. A dotted key such as `'data.user'` reads deeper, and for a list, the list itself is read from `from`.

`Decode` takes a `fromJson` that accepts `dynamic` or `Map<String, dynamic>`. For a closure, type its parameter:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Closure decoders in a `{...}` map, like `User: (data) => User.fromJson(data)`, keep working. Both are a `NyDecoder`, the class `Decode` and `OneOf` extend. A custom `NyDecoder<T>` implements `decode(json, response, decoders)` and sees the whole `NyResponse`, so it can read the status code or headers.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` decodes a response that can take more than one shape. Register it under the shared parent type, and pick the shape by a field in the body, by status code, or with your own conditions:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

A `OneOf` also decodes 4xx and 5xx bodies, and a `List<T>` item by item. A shape it doesn't recognise leaves the response data null.

| Constructor | Picks the shape by |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | The value of `key` in the body. A dotted key such as `'data.type'` reads a nested field |
| `OneOf.byStatus(types, otherwise:)` | The status code. A key is a code such as `404`, or a pattern such as `'4xx'` or `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Your own conditions, each taking the body and the response. The first that returns true wins |
| `OneOf(resolve)` | A function that takes the body and the response and returns the value, or null |

By status code:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

An exact code wins, then the pattern with the fewest `x`s.

With your own conditions:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

With a function:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Read the shape from a response with `dataAs<X>()` and `dataIs<X>()`; see [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro creates the parent, its subtypes and the `OneOf.byKey` entry for you:

```bash
metro make:model login_result --one-of="success,two_factor"
```

See [Make a OneOf model](/docs/{{$version}}/metro#make-a-one-of-model).

<div id="api-decoders"></div>

## API decoders

API decoders are used when calling the `api` helper method.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

The `api` helper will match the correct API Service using generics, so you can call the below helper to access your service. 

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
