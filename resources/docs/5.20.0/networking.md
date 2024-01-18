# Networking

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Making HTTP requests](#making-http-requests "Making HTTP requests")
  - [Base Options](#base-options "Base Options")
  - [Adding Headers](#adding-headers "Adding Headers")
  - [Interceptors](#interceptors "Interceptors")
  - [Understanding the network helper](#understanding-the-network-helper "Understanding the network helper")
- [Using an API Service](#using-an-api-service "Using an API Service")
- [Create an API Service](#create-an-api-service "Create an API Service")
- [Morphing JSON payloads to models](#morphing-json-payloads-to-models "Morphing JSON payloads to models")
- [Retrying failed requests](#retrying-failed-requests "retrying failed requests")
- [Refreshing tokens](#refreshing-tokens "Refreshing tokens")
- [Singleton API Service](#singleton-api-service "Singleton API Service")

<a name="introduction"></a>
<br>

## Introduction

{{ config('app.name') }} makes networking in modern mobile applications simple. You can do GET, PUT, POST and DELETE requests via the base networking class.

Your <b>API Services</b> directory is located here `app/networking/*`

Fresh copies of {{ config('app.name') }} will include a default API Service `app/networking/api_service.dart`.

```dart
class ApiService extends BaseApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext);

  @override
  String get baseUrl => "https://jsonplaceholder.typicode.com";

  @override
  final interceptors = {
    LoggingInterceptor: LoggingInterceptor()
  };

  Future<User?> fetchUsers() async {
    return await network<User>(
        request: (request) => request.get("/users"),
    );
}
```

Variables you can override using the <b>BaseApiService</b> class.

- `baseUrl` - This is the base URL for the API, e.g. "https://jsonplaceholder.typicode.com".
- `interceptors` - Here, you can add Dio interceptors. Learn more about interceptors <a href="https://pub.dev/packages/dio#interceptors" target="_BLANK">here</a>.
- `useInterceptors` - You can set this to true or false. It will let the API Service know whether to use your interceptors.

Under the hood, the base networking class uses <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, a powerful HTTP client.

<a name="making-http-requests"></a>
<br>

## Making HTTP requests

In your API Service, use the `network` method to build your API request.

```dart
class ApiService extends BaseApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext);

  @override
  String get baseUrl => "https://jsonplaceholder.typicode.com";

  Future<dynamic> fetchUsers() async {
    return await network(
        request: (request) {
          // return request.get("/users"); // GET request
          // return request.put("/users", data: {"user": "data"}); // PUT request
          // return request.post("/users", data: {"user": "data"}); // POST request
          // return request.delete("/users/1"); // DELETE request

          return request.get("/users");
        },
    );
  }
```

The `request` argument is a <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> instance, so you can call all the methods from that object.

<a name="base-options"></a>
<br>

### Base Options

The `BaseOptions` variable is highly configurable to allow you to modify how your Api Service should send your requests.

Inside your API Service, you can override your constructor.

```dart 
class ApiService extends BaseApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext) {
    baseOptions = BaseOptions(
      receiveTimeout: 10000, // Timeout in milliseconds (10 seconds)
      connectTimeout: 5000 // Timeout in milliseconds (5 seconds)
    );
  }
...
```

Click <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">here</a> to view all the base options you can set.

<a name="adding-headers"></a>
<br>

### Adding Headers

You can add headers to your requests either via your baseOptions variable, on each request or an interceptor.

Here's the simplest way to add headers to a request.

```dart
Future fetchWithHeaders() async => await network(
    request: (request) => request.get("/test"),
    headers: {
      "Authorization": "Bearer aToken123",
      "Device": "iPhone"
    }
);
```

You can also add **Bearer Token's** like in the below example.

```dart 
Future fetchUserWithBearer() async => await network(
    request: (request) => request.get("/user"),
    bearerToken: "hello-world-123",
);
```

Or lastly, like the below.

```dart
...
Future fetchUsers() async {
    return await network(
        request: (request) {
          request.options.headers = {
            "Authorization": "Bearer $token"
          };

          return request.get("/users");
        },
    );
}

```

<a name="interceptors"></a>
<br>

### Interceptors

If you're new to interceptors, don't worry. They're a new concept for managing how your HTTP requests are sent.

Put in simple terms. An 'interceptor' will intercept the request, allowing you to modify the request before it's sent, handle the response after it completes and also what happens if there's an error.

{{ config('app.name') }} allows you to add new interceptors to your API Services like in the below example.

```dart 
class ApiService extends BaseApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext);
  ...

  @override
  final interceptors = {
    LoggingInterceptor: LoggingInterceptor(),

    // Add more interceptors for the API Service
    // BearerAuthInterceptor: BearerAuthInterceptor(),
  };

...
```

Let's take a look at an interceptor.

Example of a custom interceptor.

```dart
import 'package:nylo_framework/nylo_framework.dart';

class CustomInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    // options - modify the RequestOptions before the request

    return super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    // response - handle/manipulate the Response object

    handler.next(response);
  }

  @override
  void onError(DioError error, ErrorInterceptorHandler handler) {
    // error - handle the DioError object
    handler.next(err);
  }
}
```

Fresh copies on {{ config('app.name') }} will include a `app/networking/dio/intecetors/*` directory.

### Creating a new interceptor

You can create a new interceptor using the command below.

```bash
# Run this command in your terminal
dart run nylo_framework:main make:interceptor logging_interceptor
```


<b>File: </b> `app/networking/dio/intecetors/logging_interceptor.dart`
```dart
import 'dart:developer';
import 'package:nylo_framework/nylo_framework.dart';

class LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    
    print('REQUEST[${options.method}] => PATH: ${options.path}');
    
    return super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    
      print(
          'RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions
              .path}');

      print('DATA: ${response.requestOptions.path}');
      
      log(response.data.toString());
    
    handler.next(response);
  }

  @override
  void onError(DioError err, ErrorInterceptorHandler handler) {
    
      print('ERROR[${err.response?.statusCode}] => PATH: ${err.requestOptions
          .path}');

    handler.next(err);
  }
}
```

<a name="understanding-the-network-helper"></a>
<br>

## Understanding the network helper

The `network` helper provides us with a way to make HTTP requests from our application.
The helper method can be accessed when using an API Service in {{ config('app.name') }}.

```dart
class ApiService extends BaseApiService {
  ...

  Future<dynamic> fetchUsers() async {
    return await network(
        request: (request) {
          // return request.get("/users"); // GET request
          // return request.put("/users", data: {"user": "data"}); // PUT request
          // return request.post("/users", data: {"user": "data"}); // POST request
          // return request.delete("/users/1"); // DELETE request

          return request.get("/users");
        },
    );
  }
```

### Return Types

There are two ways to handle the response from an HTTP request. 
Let's take a look at both in action, there's no right or wrong way to do this.

#### Using Model Decoders

Model Decoders are a new concept introduced in {{ config('app.name') }} v3.x. 

They make it easy to return your objects, like in the below example.

```dart
class ApiService extends BaseApiService {
  ...

  Future<User?> fetchUser() async {
    return await network<User>(
        request: (request) => request.get("/users/1"),
    );
  }
```

<b>File: config/decoders.dart</b>
```dart 
final modelDecoders = {
  User: (data) => User.fromJson(data), // add your model and handle the return of the object

  // ...
};
```

The `data` parameter will contain the <b>HTTP</b> response body.

Learn more about decoders <a href="/docs/{{$version}}/decoders#model-decoders">here</a>


#### Using handleSuccess

The `handleSuccess: (Response response) {}` argument can be used to return a value from the HTTP body.

This method is only called if the <b>HTTP</b> response has a status code equal to 200.

Here's an example below.

```dart
class ApiService extends BaseApiService {
  ...
  // Example: returning an Object
  Future<User?> findUser() async {
    return await network(
        request: (request) => request.get("/users/1"),
        handleSuccess: (Response response) { // response - Dio Response object
          dynamic data = response.data;
          return User.fromJson(data);
        }
    );
  }
  // Example: returning a String
  Future<String?> findMessage() async {
    return await network(
        request: (request) => request.get("/message/1"),
        handleSuccess: (Response response) { // response - Dio Response object
          dynamic data = response.data;
          if (data['name'] == 'Anthony') {
            return "It's Anthony";
          }
          return "Hello world"; 
        }
    );
  }
  // Example: returning a bool
  Future<bool?> updateUser() async {
    return await network(
        request: (request) => request.put("/user/1", data: {"name": "Anthony"}),
        handleSuccess: (Response response) { // response - Dio Response object
          dynamic data = response.data;
          if (data['status'] == 'OK') {
            return true;
          }
          return false;
        }
    );
  }
```

#### Using handleFailure

The `handleFailure` method will be called if the <b>HTTP</b> response returns a status code not equal to 200.

You can provide the <b>network</b> helper with the `handleFailure: (DioError dioError) {}` argument and then handle the response in the function.

Here's an example of how it works.

```dart
class ApiService extends BaseApiService {
  ...
  // Example: returning an Object
  Future<User?> findUser() async {
    return await network(
        request: (request) => request.get("/users/1"),
        handleFailure: (DioError dioError) { // response - DioError object
          dynamic data = response.data;
          // Handle the response

          return null;
        }
    );
  }
}
```

<a name="using-an-api-service"></a>
<br>

## Using an API Service

When you need to call an API from a widget, there are two different approaches in {{ config('app.name') }}.

1. You can create a new instance of the API Service and then call the method you want to use, like in the below example.

```dart
class _MyHomePageState extends NyState<MyHomePage> {

  ApiService _apiService = ApiService();

  @override
  init() async {
    List<User>? users = await _apiService.fetchUsers();
    print(users); // List<User>? instance
...
```

2. Use the `api` helper, this method is shorter and works by using your `apiDecoders` variable inside <b>config/decoders.dart</b>. 
Learn more about decoders <a href="/docs/{{$version}}/decoders">here</a>.

```dart
class _MyHomePageState extends NyState<MyHomePage> {

  @override
  init() async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user); // User? instance
...
```

Using the <b>api</b> helper also allows you to handle UI feedback to your users if the request isn't successful.
To do this, add the `context` parameter to the `api` helper, example below.

```dart
// Your Widget
...
  User _user = User();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: MaterialButton(
        onPressed: () {
          _sendFriendRequest(_user); 
        }, 
        child: Text("Send Friend Request"),
      );
    );
  }

  _sendFriendRequest(User user) async {
    bool? successful = await api<ApiService>(
      (request) => request.sendFriendRequest(user), 
      context: context
    );
  }
...

// Your API Service
class ApiService extends BaseApiService {
  ...

  // Add this
  displayError(DioException dioException, BuildContext context) {
    showToastNotification(context, title: 'Oops!', description: dioError.message);
    // or display the error however you want
  }
}
```

`displayError` - If an error occurs with the request (e.g. 500 status code), you can instantly give your users feedback via a toast notification.

<a name="create-an-api-service"></a>
<br>

## Create an API Service

To create more api_services, e.g. a `user_api_service`, use the command below.

``` bash
dart run nylo_framework:main make:api_service user
```

You can also create an API Service for a model with the `--model="User"` option.

``` bash
dart run nylo_framework:main make:api_service user --model="User"
```

This will create an API Service with the following methods.

```dart
class UserApiService extends BaseApiService {
  ...

  /// Return a list of users
  Future<List<User>?> fetchAll({dynamic query}) async {
    return await network<List<User>>(
        request: (request) => request.get("/endpoint-path", queryParameters: query),
    );
  }

  /// Find a User
  Future<User?> find({required int id}) async {
    return await network<User>(
      request: (request) => request.get("/endpoint-path/$id"),
    );
  }

  /// Create a User
  Future<User?> create({required dynamic data}) async {
    return await network<User>(
      request: (request) => request.post("/endpoint-path", data: data),
    );
  }

  /// Update a User
  Future<User?> update({dynamic query}) async {
    return await network<User>(
      request: (request) => request.put("/endpoint-path", queryParameters: query),
    );
  }

  /// Delete a User
  Future<bool?> delete({required int id}) async {
    return await network<bool>(
      request: (request) => request.delete("/endpoint-path/$id"),
    );
  }
}

```

<a name="morphing-json-payloads-to-models"></a>
<br>

## Morphing JSON payloads to models

You can automatically decode JSON payloads from your API requests to models using decoders.

Here is an API request that uses {{ config('app.name') }}'s implementation of decoders.

```dart
class ApiService extends BaseApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext);

  Future<User?> fetchUsers() async {
    return await network<User>(
        request: (request) => request.get("/users"),
    );
  }
}
```

The `fetchUsers` method will handle the JSON conversion to the model representation using generics.

You will first need to add your model to your `config/decoders.dart` file like the below.

```dart 
/// file 'config/decoders.dart'

final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

Here you can provide the different representations of the Model, e.g. to object or list&lt;Model&gt; like the above.

The data argument in the decoder will contain the body payload from the API request.

To get started with decoders, check out this section of the [documentation](/docs/{{$version}}/decoders#model-decoders).

<a name="retrying-failed-requests"></a>
<br>

## Retrying failed requests

In {{ config('app.name') }} v{{ $version }}, you can retry failed requests.

If the response from the API request is an error status code (e.g. status code 500), the request will be retried.

### Retrying an API request
You can retry failed requests using the `retry` method.

```dart
Future fetchUsers() async {
    return await network(
        request: (request) => request.get("/users"),
        retry: 3, // retry 3 times
    );
}
```

### Retry delay

You can also set a **delay** between each retry attempt.

```dart
Future fetchUsers() async {
    return await network(
        request: (request) => request.get("/users"),
        retry: 3, // retry 3 times
        retryDelay: Duration(seconds: 2), // retry after 2 second
    );
}
```

### Retry if

You can also set a **condition** for when to retry the request.

```dart
Future fetchUsers() async {
    return await network(
        request: (request) => request.get("/users"),
        retry: 3, // retry 3 times
        retryIf: (DioException dioException) {
          // retry if the status code is 500
          return dioException.response?.statusCode == 500;
        },
    );
}
```

<a name="refreshing-tokens"></a>
<br>

## Refreshing tokens

If your application needs to refresh tokens, you can handle this in your API Service.

In {{ config('app.name') }}, you can override 3 methods in your API Service to handle token refreshing.

- `refreshToken` - This method will be called when the API Service needs to refresh the token.
- `setAuthHeaders` - This method will be called before every request. You can add your auth headers here.
- `shouldRefreshToken` - This method will be called before every request. You can return true or false to determine whether to refresh the token.

Let's take a look at all three in action.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) :
        super(buildContext, decoders: modelDecoders);

  Future getUser() async {
    return await network(
        request: (request) => request.get("/user")
    );
  }
  
  @override
  refreshToken(Dio dio) async {
    dynamic response = (await dio.get("https://example.com/refresh-token")).data();
    // Save the new token to local storage
    User user = Auth.user();
    user.token = Token.fromJson(response['token']);
    await user.save();
  }

  @override
  Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
    User? user = Auth.user();
    if (user != null) {
      headers.addBearerToken( user.token );
    }
    return headers;
  }

  @override
  Future<bool> shouldRefreshToken() async {
    User? user = Auth.user();

    if (user.token.expiredAt.isPast()) {
        // Check if the token is expired
        // This will trigger the refreshToken method
        return true;
    }
    return false;
  }
```

Now when you call the `getUser` method, the API Service will check if the token is expired and then refresh it if needed.

<a name="singleton-api-service"></a>
<br>

## Singleton API Service

You can create an API Service as a singleton by updating the **apiDecoders** variable in your `config/decoders.dart` file.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // from this

  ApiService: ApiService(), // to this
  ...
};
```

Now when you call the `api` helper, it will return the same instance of the API Service.

```dart
api<ApiService>((request) => request.fetchUsers());
```

You can switch between singleton and non-singleton API Services if you need to.
