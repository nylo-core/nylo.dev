# नेटवर्किंग

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- HTTP रिक्वेस्ट्स बनाना
  - [कन्वीनिएंस मेथड्स](#convenience-methods "कन्वीनिएंस मेथड्स")
  - [Network हेल्पर](#network-helper "Network हेल्पर")
  - [networkResponse हेल्पर](#network-response-helper "networkResponse हेल्पर")
  - [NyResponse](#ny-response "NyResponse")
  - [बेस ऑप्शन्स](#base-options "बेस ऑप्शन्स")
  - [हेडर्स जोड़ना](#adding-headers "हेडर्स जोड़ना")
- फ़ाइल ऑपरेशन्स
  - [फ़ाइलें अपलोड करना](#uploading-files "फ़ाइलें अपलोड करना")
  - [फ़ाइलें डाउनलोड करना](#downloading-files "फ़ाइलें डाउनलोड करना")
- [इंटरसेप्टर्स](#interceptors "इंटरसेप्टर्स")
  - [नेटवर्क लॉगर](#network-logger "नेटवर्क लॉगर")
- [API सर्विस का उपयोग करना](#using-an-api-service "API सर्विस का उपयोग करना")
- [API सर्विस बनाना](#create-an-api-service "API सर्विस बनाना")
- [JSON को मॉडल्स में मॉर्फ़ करना](#morphing-json-payloads-to-models "JSON को मॉडल्स में मॉर्फ़ करना")
- कैशिंग
  - [रिस्पॉन्स कैश करना](#caching-responses "रिस्पॉन्स कैश करना")
  - [कैश पॉलिसीज़](#cache-policies "कैश पॉलिसीज़")
- एरर हैंडलिंग
  - [विफल रिक्वेस्ट्स रीट्राई करना](#retrying-failed-requests "विफल रिक्वेस्ट्स रीट्राई करना")
  - [कनेक्टिविटी जाँच](#connectivity-checks "कनेक्टिविटी जाँच")
  - [कैंसल टोकन्स](#cancel-tokens "कैंसल टोकन्स")
- ऑथेंटिकेशन
  - [ऑथ हेडर्स सेट करना](#setting-auth-headers "ऑथ हेडर्स सेट करना")
  - [टोकन्स रिफ्रेश करना](#refreshing-tokens "टोकन्स रिफ्रेश करना")
- [सिंगलटन API सर्विस](#singleton-api-service "सिंगलटन API सर्विस")
- [एडवांस्ड कॉन्फ़िगरेशन](#advanced-configuration "एडवांस्ड कॉन्फ़िगरेशन")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} नेटवर्किंग को सरल बनाता है। आप `NyApiService` को एक्सटेंड करने वाली सर्विस क्लासेज़ में API एंडपॉइंट्स डिफ़ाइन करते हैं, फिर उन्हें अपने पेजेज़ से कॉल करते हैं। फ्रेमवर्क JSON डिकोडिंग, एरर हैंडलिंग, कैशिंग, और रिस्पॉन्सेज़ को आपकी मॉडल क्लासेज़ में स्वचालित रूपांतरण (जिसे "मॉर्फ़िंग" कहा जाता है) संभालता है।

आपकी API सर्विसेज़ `lib/app/networking/` में रहती हैं। एक नए प्रोजेक्ट में डिफ़ॉल्ट `ApiService` शामिल होती है:

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
        );

  @override
  String get baseUrl => getEnv('API_BASE_URL');

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
  };

  Future fetchUsers() async {
    return await network(
      request: (request) => request.get("/users"),
    );
  }
}
```

HTTP रिक्वेस्ट्स बनाने के तीन तरीके हैं:

| दृष्टिकोण | रिटर्न | सबसे अच्छा |
|----------|---------|----------|
| कन्वीनिएंस मेथड्स (`get`, `post`, आदि) | `T?` | सिंपल CRUD ऑपरेशन्स |
| `network()` | `T?` | कैशिंग, रीट्राई, या कस्टम हेडर्स की आवश्यकता वाली रिक्वेस्ट्स |
| `networkResponse()` | `NyResponse<T>` | जब आपको स्टेटस कोड्स, हेडर्स, या एरर विवरण चाहिए |

अंदर से, {{ config('app.name') }} एक शक्तिशाली HTTP क्लाइंट <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> का उपयोग करता है।


<div id="convenience-methods"></div>

## कन्वीनिएंस मेथड्स

`NyApiService` सामान्य HTTP ऑपरेशन्स के लिए शॉर्टहैंड मेथड्स प्रदान करता है। ये आंतरिक रूप से `network()` कॉल करते हैं।

### GET रिक्वेस्ट

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST रिक्वेस्ट

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT रिक्वेस्ट

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE रिक्वेस्ट

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH रिक्वेस्ट

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD रिक्वेस्ट

रिसोर्स अस्तित्व जाँचने या बॉडी डाउनलोड किए बिना हेडर्स प्राप्त करने के लिए HEAD का उपयोग करें:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network हेल्पर

`network` मेथड आपको कन्वीनिएंस मेथड्स से अधिक नियंत्रण देता है। यह मॉर्फ़ किया गया डेटा (`T?`) सीधे रिटर्न करता है।

```dart
class ApiService extends NyApiService {
  ...

  Future<User?> fetchUser(int id) async {
    return await network<User>(
      request: (request) => request.get("/users/$id"),
    );
  }

  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }

  Future<User?> createUser(Map<String, dynamic> data) async {
    return await network<User>(
      request: (request) => request.post("/users", data: data),
    );
  }
}
```

`request` कॉलबैक एक <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> इंस्टेंस प्राप्त करता है जिसमें आपका बेस URL और इंटरसेप्टर्स पहले से कॉन्फ़िगर होते हैं।

### network पैरामीटर्स

| पैरामीटर | प्रकार | विवरण |
|-----------|------|-------------|
| `request` | `Function(Dio)` | निष्पादित करने के लिए HTTP रिक्वेस्ट (आवश्यक) |
| `bearerToken` | `String?` | इस रिक्वेस्ट के लिए Bearer टोकन |
| `baseUrl` | `String?` | सर्विस बेस URL ओवरराइड करें |
| `headers` | `Map<String, dynamic>?` | अतिरिक्त हेडर्स |
| `retry` | `int?` | रीट्राई प्रयासों की संख्या |
| `retryDelay` | `Duration?` | रीट्राई के बीच देरी |
| `retryIf` | `bool Function(DioException)?` | रीट्राई करने की शर्त |
| `connectionTimeout` | `Duration?` | कनेक्शन टाइमआउट |
| `receiveTimeout` | `Duration?` | रिसीव टाइमआउट |
| `sendTimeout` | `Duration?` | सेंड टाइमआउट |
| `cacheKey` | `String?` | कैश कुंजी |
| `cacheDuration` | `Duration?` | कैश अवधि |
| `cachePolicy` | `CachePolicy?` | कैश रणनीति |
| `checkConnectivity` | `bool?` | रिक्वेस्ट से पहले कनेक्टिविटी जाँचें |
| `handleSuccess` | `Function(NyResponse<T>)?` | सफलता कॉलबैक |
| `handleFailure` | `Function(NyResponse<T>)?` | विफलता कॉलबैक |


<div id="network-response-helper"></div>

## networkResponse हेल्पर

जब आपको पूर्ण रिस्पॉन्स -- स्टेटस कोड्स, हेडर्स, एरर संदेश -- तक पहुँच चाहिए, न कि केवल डेटा, तो `networkResponse` का उपयोग करें। यह `T?` के बजाय `NyResponse<T>` रिटर्न करता है।

`networkResponse` का उपयोग करें जब आपको:
- विशिष्ट हैंडलिंग के लिए HTTP स्टेटस कोड्स जाँचने हों
- रिस्पॉन्स हेडर्स एक्सेस करने हों
- यूज़र फ़ीडबैक के लिए विस्तृत एरर संदेश चाहिए
- कस्टम एरर हैंडलिंग लॉजिक लागू करना हो

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

फिर अपने पेज में रिस्पॉन्स का उपयोग करें:

```dart
NyResponse<User> response = await _apiService.fetchUser(1);

if (response.isSuccessful) {
  User? user = response.data;
  print('Status: ${response.statusCode}');
} else {
  print('Error: ${response.errorMessage}');
  print('Status: ${response.statusCode}');
}
```

### network बनाम networkResponse

```dart
// network() — returns the data directly
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — returns the full response
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

दोनों मेथड्स समान पैरामीटर्स स्वीकार करते हैं। जब आपको डेटा के अलावा रिस्पॉन्स की जाँच करनी हो तो `networkResponse` चुनें।


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` मॉर्फ़ किए गए डेटा और स्टेटस हेल्पर्स के साथ Dio रिस्पॉन्स को रैप करता है।

### प्रॉपर्टीज़

| प्रॉपर्टी | प्रकार | विवरण |
|----------|------|-------------|
| `response` | `Response?` | ओरिजिनल Dio Response |
| `data` | `T?` | मॉर्फ़/डिकोडेड डेटा |
| `rawData` | `dynamic` | रॉ रिस्पॉन्स डेटा |
| `headers` | `Headers?` | रिस्पॉन्स हेडर्स |
| `statusCode` | `int?` | HTTP स्टेटस कोड |
| `statusMessage` | `String?` | HTTP स्टेटस संदेश |
| `contentType` | `String?` | हेडर्स से कंटेंट टाइप |
| `errorMessage` | `String?` | एक्सट्रैक्टेड एरर संदेश |

### स्टेटस जाँच

| गेटर | विवरण |
|--------|-------------|
| `isSuccessful` | स्टेटस 200-299 |
| `isClientError` | स्टेटस 400-499 |
| `isServerError` | स्टेटस 500-599 |
| `isRedirect` | स्टेटस 300-399 |
| `hasData` | डेटा null नहीं है |
| `isUnauthorized` | स्टेटस 401 |
| `isForbidden` | स्टेटस 403 |
| `isNotFound` | स्टेटस 404 |
| `isTimeout` | स्टेटस 408 |
| `isConflict` | स्टेटस 409 |
| `isRateLimited` | स्टेटस 429 |

### डेटा हेल्पर्स

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Get data or throw if null
User user = response.dataOrThrow('User not found');

// Get data or use a fallback
User user = response.dataOr(User.guest());

// Run callback only if successful
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Pattern match on success/failure
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Get a specific header
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## बेस ऑप्शन्स

`baseOptions` पैरामीटर का उपयोग करके अपनी API सर्विस के लिए डिफ़ॉल्ट Dio ऑप्शन्स कॉन्फ़िगर करें:

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    baseOptions: (BaseOptions baseOptions) {
      return baseOptions
        ..connectTimeout = Duration(seconds: 5)
        ..sendTimeout = Duration(seconds: 5)
        ..receiveTimeout = Duration(seconds: 5);
    },
  );
  ...
}
```

आप इंस्टेंस पर डायनामिक रूप से भी ऑप्शन्स कॉन्फ़िगर कर सकते हैं:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

सभी बेस ऑप्शन्स देखने के लिए <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">यहाँ</a> क्लिक करें।


<div id="adding-headers"></div>

## हेडर्स जोड़ना

### प्रति-रिक्वेस्ट हेडर्स

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer टोकन

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### सर्विस-लेवल हेडर्स

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders एक्सटेंशन

`RequestHeaders` टाइप (एक `Map<String, dynamic>` typedef) हेल्पर मेथड्स प्रदान करता है:

```dart
@override
Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
  String? token = Auth.data(field: 'token');
  if (token != null) {
    headers.addBearerToken(token);
  }
  headers.addHeader('X-App-Version', '1.0.0');
  return headers;
}
```

| मेथड | विवरण |
|--------|-------------|
| `addBearerToken(token)` | `Authorization: Bearer` हेडर सेट करें |
| `getBearerToken()` | हेडर्स से bearer टोकन पढ़ें |
| `addHeader(key, value)` | कस्टम हेडर जोड़ें |
| `hasHeader(key)` | जाँचें कि हेडर मौजूद है |


<div id="uploading-files"></div>

## फ़ाइलें अपलोड करना

### सिंगल फ़ाइल अपलोड

```dart
Future<UploadResponse?> uploadAvatar(String filePath) async {
  return await upload<UploadResponse>(
    '/upload',
    filePath: filePath,
    fieldName: 'avatar',
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      double progress = sent / total * 100;
      print('Progress: ${progress.toStringAsFixed(0)}%');
    },
  );
}
```

### मल्टीपल फ़ाइल अपलोड

```dart
Future<UploadResponse?> uploadDocuments() async {
  return await uploadMultiple<UploadResponse>(
    '/upload',
    files: {
      'avatar': '/path/to/avatar.jpg',
      'document': '/path/to/doc.pdf',
    },
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      print('Progress: ${(sent / total * 100).toStringAsFixed(0)}%');
    },
  );
}
```


<div id="downloading-files"></div>

## फ़ाइलें डाउनलोड करना

```dart
Future<void> downloadFile(String url, String savePath) async {
  await download(
    url,
    savePath: savePath,
    onProgress: (received, total) {
      if (total != -1) {
        print('Progress: ${(received / total * 100).toStringAsFixed(0)}%');
      }
    },
    deleteOnError: true,
  );
}
```


<div id="interceptors"></div>

## इंटरसेप्टर्स

इंटरसेप्टर्स आपको रिक्वेस्ट्स भेजने से पहले मॉडिफ़ाई करने, रिस्पॉन्सेज़ हैंडल करने और एरर्स प्रबंधित करने देते हैं। ये API सर्विस के माध्यम से की गई प्रत्येक रिक्वेस्ट पर चलते हैं।

इंटरसेप्टर्स का उपयोग करें जब आपको:
- सभी रिक्वेस्ट्स में ऑथेंटिकेशन हेडर्स जोड़ने हों
- डिबगिंग के लिए रिक्वेस्ट्स और रिस्पॉन्सेज़ लॉग करने हों
- रिक्वेस्ट/रिस्पॉन्स डेटा को ग्लोबली ट्रांसफ़ॉर्म करना हो
- विशिष्ट एरर कोड्स हैंडल करने हों (जैसे 401 पर टोकन्स रिफ्रेश करना)

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
    BearerAuthInterceptor: BearerAuthInterceptor(),
    LoggingInterceptor: LoggingInterceptor(),
  };
  ...
}
```

### कस्टम इंटरसेप्टर बनाना

```bash
metro make:interceptor logging
```

**फ़ाइल:** `app/networking/dio/interceptors/logging_interceptor.dart`

```dart
import 'package:nylo_framework/nylo_framework.dart';

class LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    print('REQUEST[${options.method}] => PATH: ${options.path}');
    return super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    print('RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions.path}');
    handler.next(response);
  }

  @override
  void onError(DioException dioException, ErrorInterceptorHandler handler) {
    print('ERROR[${dioException.response?.statusCode}] => PATH: ${dioException.requestOptions.path}');
    handler.next(dioException);
  }
}
```


<div id="network-logger"></div>

## नेटवर्क लॉगर

{{ config('app.name') }} में बिल्ट-इन `NetworkLogger` इंटरसेप्टर शामिल है। जब आपके एनवायरनमेंट में `APP_DEBUG` `true` होता है तो यह डिफ़ॉल्ट रूप से सक्षम होता है।

### कॉन्फ़िगरेशन

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    useNetworkLogger: true,
    networkLogger: NetworkLogger(
      logLevel: LogLevelType.verbose,
      request: true,
      requestHeader: true,
      requestBody: true,
      responseBody: true,
      responseHeader: false,
      error: true,
    ),
  );
}
```

आप `useNetworkLogger: false` सेट करके इसे अक्षम कर सकते हैं।

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- लॉगर अक्षम करें
        );
```

### लॉग लेवल्स

| लेवल | विवरण |
|-------|-------------|
| `LogLevelType.verbose` | सभी रिक्वेस्ट/रिस्पॉन्स विवरण प्रिंट करें |
| `LogLevelType.minimal` | केवल मेथड, URL, स्टेटस, और समय प्रिंट करें |
| `LogLevelType.none` | कोई लॉगिंग आउटपुट नहीं |

### लॉग्स फ़िल्टर करना

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## API सर्विस का उपयोग करना

पेज से अपनी API सर्विस कॉल करने के दो तरीके हैं।

### डायरेक्ट इंस्टैन्शिएशन

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  ApiService _apiService = ApiService();

  @override
  get init => () async {
    List<User>? users = await _apiService.fetchUsers();
    print(users);
  };
}
```

### api() हेल्पर का उपयोग करना

`api` हेल्पर `config/decoders.dart` से आपके `apiDecoders` का उपयोग करके इंस्टेंसेज़ बनाता है:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

कॉलबैक्स के साथ:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data is the morphed User? instance
  },
  onError: (DioException dioException) {
    // Handle the error
  },
);
```

### api() हेल्पर पैरामीटर्स

| पैरामीटर | प्रकार | विवरण |
|-----------|------|-------------|
| `request` | `Function(T)` | API रिक्वेस्ट फ़ंक्शन |
| `context` | `BuildContext?` | बिल्ड कॉन्टेक्स्ट |
| `headers` | `Map<String, dynamic>` | अतिरिक्त हेडर्स |
| `bearerToken` | `String?` | Bearer टोकन |
| `baseUrl` | `String?` | बेस URL ओवरराइड करें |
| `page` | `int?` | पेजिनेशन पेज |
| `perPage` | `int?` | प्रति पेज आइटम |
| `retry` | `int` | रीट्राई प्रयास |
| `retryDelay` | `Duration?` | रीट्राई के बीच देरी |
| `onSuccess` | `Function(Response, dynamic)?` | सफलता कॉलबैक |
| `onError` | `Function(DioException)?` | एरर कॉलबैक |
| `cacheKey` | `String?` | कैश कुंजी |
| `cacheDuration` | `Duration?` | कैश अवधि |


<div id="create-an-api-service"></div>

## API सर्विस बनाना

नई API सर्विस बनाने के लिए:

```bash
metro make:api_service user
```

मॉडल के साथ:

```bash
metro make:api_service user --model="User"
```

यह CRUD मेथड्स के साथ API सर्विस बनाता है:

```dart
class UserApiService extends NyApiService {
  ...

  Future<List<User>?> fetchAll({dynamic query}) async {
    return await network<List<User>>(
      request: (request) => request.get("/endpoint-path", queryParameters: query),
    );
  }

  Future<User?> find({required int id}) async {
    return await network<User>(
      request: (request) => request.get("/endpoint-path/$id"),
    );
  }

  Future<User?> create({required dynamic data}) async {
    return await network<User>(
      request: (request) => request.post("/endpoint-path", data: data),
    );
  }

  Future<User?> update({dynamic query}) async {
    return await network<User>(
      request: (request) => request.put("/endpoint-path", queryParameters: query),
    );
  }

  Future<bool?> delete({required int id}) async {
    return await network<bool>(
      request: (request) => request.delete("/endpoint-path/$id"),
    );
  }
}
```


<div id="morphing-json-payloads-to-models"></div>

## JSON को मॉडल्स में मॉर्फ़ करना

"मॉर्फ़िंग" {{ config('app.name') }} का JSON रिस्पॉन्सेज़ को आपकी Dart मॉडल क्लासेज़ में स्वचालित रूप से कन्वर्ट करने का शब्द है। जब आप `network<User>(...)` का उपयोग करते हैं, तो रिस्पॉन्स JSON आपके डीकोडर से गुज़रकर `User` इंस्टेंस बनाता है -- कोई मैनुअल पार्सिंग नहीं।

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Returns a single User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Returns a List of Users
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

डीकोडर्स `lib/bootstrap/decoders.dart` में डिफ़ाइन किए जाते हैं:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

`network<T>()` को पास किया गया टाइप पैरामीटर सही डीकोडर खोजने के लिए आपके `modelDecoders` मैप से मैच किया जाता है।

**यह भी देखें:** [Decoders](/docs/{{$version}}/decoders#model-decoders) मॉडल डीकोडर्स रजिस्टर करने के विवरण के लिए।


<div id="caching-responses"></div>

## रिस्पॉन्स कैश करना

API कॉल्स कम करने और प्रदर्शन सुधारने के लिए रिस्पॉन्सेज़ कैश करें। कैशिंग उन डेटा के लिए उपयोगी है जो बार-बार नहीं बदलते, जैसे देशों की सूचियाँ, श्रेणियाँ, या कॉन्फ़िगरेशन।

`cacheKey` और वैकल्पिक `cacheDuration` प्रदान करें:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### कैश क्लियर करना

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### api() हेल्पर के साथ कैशिंग

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## कैश पॉलिसीज़

कैशिंग व्यवहार पर बारीक नियंत्रण के लिए `CachePolicy` का उपयोग करें:

| पॉलिसी | विवरण |
|--------|-------------|
| `CachePolicy.networkOnly` | हमेशा नेटवर्क से फ़ेच करें (डिफ़ॉल्ट) |
| `CachePolicy.cacheFirst` | पहले कैश आज़माएँ, नेटवर्क पर फ़ॉलबैक |
| `CachePolicy.networkFirst` | पहले नेटवर्क आज़माएँ, कैश पर फ़ॉलबैक |
| `CachePolicy.cacheOnly` | केवल कैश उपयोग करें, खाली होने पर एरर |
| `CachePolicy.staleWhileRevalidate` | कैश तुरंत रिटर्न करें, बैकग्राउंड में अपडेट करें |

### उपयोग

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
    cachePolicy: CachePolicy.staleWhileRevalidate,
  ) ?? [];
}
```

### प्रत्येक पॉलिसी का उपयोग कब करें

- **cacheFirst** -- डेटा जो शायद ही कभी बदलता है। कैश्ड डेटा तुरंत रिटर्न करता है, कैश खाली होने पर ही नेटवर्क से फ़ेच करता है।
- **networkFirst** -- डेटा जो संभव होने पर ताज़ा होना चाहिए। पहले नेटवर्क आज़माता है, विफलता पर कैश पर फ़ॉलबैक करता है।
- **staleWhileRevalidate** -- UI जिसे तत्काल रिस्पॉन्स चाहिए लेकिन अपडेटेड भी रहना चाहिए। कैश्ड डेटा रिटर्न करता है, फिर बैकग्राउंड में रिफ्रेश करता है।
- **cacheOnly** -- ऑफ़लाइन मोड। कोई कैश्ड डेटा न होने पर एरर थ्रो करता है।

> **नोट:** यदि आप `cachePolicy` निर्दिष्ट किए बिना `cacheKey` या `cacheDuration` प्रदान करते हैं, तो डिफ़ॉल्ट पॉलिसी `cacheFirst` है।


<div id="retrying-failed-requests"></div>

## विफल रिक्वेस्ट्स रीट्राई करना

विफल होने वाली रिक्वेस्ट्स को स्वचालित रूप से रीट्राई करें।

### बेसिक रीट्राई

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### देरी के साथ रीट्राई

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### कंडीशनल रीट्राई

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // Only retry on server errors
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### सर्विस-लेवल रीट्राई

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## कनेक्टिविटी जाँच

टाइमआउट की प्रतीक्षा करने के बजाय डिवाइस ऑफ़लाइन होने पर तेज़ी से विफल हों।

### सर्विस-लेवल

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### प्रति-रिक्वेस्ट

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### डायनामिक

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

सक्षम होने और डिवाइस ऑफ़लाइन होने पर:
- `networkFirst` पॉलिसी कैश पर फ़ॉलबैक करती है
- अन्य पॉलिसीज़ तुरंत `DioExceptionType.connectionError` थ्रो करती हैं


<div id="cancel-tokens"></div>

## कैंसल टोकन्स

पेंडिंग रिक्वेस्ट्स प्रबंधित और कैंसल करें।

```dart
// Create a managed cancel token
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Cancel all pending requests (e.g., on logout)
apiService.cancelAllRequests('User logged out');

// Check active request count
int count = apiService.activeRequestCount;

// Clean up a specific token when done
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## ऑथ हेडर्स सेट करना

प्रत्येक रिक्वेस्ट में ऑथेंटिकेशन हेडर्स अटैच करने के लिए `setAuthHeaders` ओवरराइड करें। यह मेथड प्रत्येक रिक्वेस्ट से पहले कॉल होता है जब `shouldSetAuthHeaders` `true` होता है (डिफ़ॉल्ट)।

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
    String? myAuthToken = Auth.data(field: 'token');
    if (myAuthToken != null) {
      headers.addBearerToken(myAuthToken);
    }
    return headers;
  }
}
```

### ऑथ हेडर्स अक्षम करना

पब्लिक एंडपॉइंट्स के लिए जिन्हें ऑथेंटिकेशन की आवश्यकता नहीं:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**यह भी देखें:** [Authentication](/docs/{{ $version }}/authentication) यूज़र्स को ऑथेंटिकेट करने और टोकन्स स्टोर करने के विवरण के लिए।


<div id="refreshing-tokens"></div>

## टोकन्स रिफ्रेश करना

टोकन एक्सपायरी हैंडल करने के लिए `shouldRefreshToken` और `refreshToken` ओवरराइड करें। ये प्रत्येक रिक्वेस्ट से पहले कॉल होते हैं।

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Check if the token needs refreshing
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Use the fresh Dio instance (no interceptors) to refresh the token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Save the new token to storage
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

`refreshToken` में `dio` पैरामीटर सर्विस के मुख्य इंस्टेंस से अलग एक नया Dio इंस्टेंस है, इंटरसेप्टर लूप से बचने के लिए।


<div id="singleton-api-service"></div>

## सिंगलटन API सर्विस

डिफ़ॉल्ट रूप से, `api` हेल्पर हर बार नया इंस्टेंस बनाता है। सिंगलटन उपयोग करने के लिए, `config/decoders.dart` में फ़ैक्टरी के बजाय इंस्टेंस पास करें:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## एडवांस्ड कॉन्फ़िगरेशन

### कस्टम Dio इनिशियलाइज़ेशन

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    initDio: (Dio dio) {
      dio.options.validateStatus = (status) => status! < 500;
      return dio;
    },
  );
}
```

### Dio इंस्टेंस एक्सेस करना

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### पेजिनेशन हेल्पर

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### इवेंट कॉलबैक्स

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### ओवरराइड करने योग्य प्रॉपर्टीज़

| प्रॉपर्टी | प्रकार | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | सभी रिक्वेस्ट्स के लिए बेस URL |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio इंटरसेप्टर्स |
| `decoders` | `Map<Type, dynamic>?` | `{}` | JSON मॉर्फ़िंग के लिए मॉडल डीकोडर्स |
| `shouldSetAuthHeaders` | `bool` | `true` | रिक्वेस्ट्स से पहले `setAuthHeaders` कॉल करना है या नहीं |
| `retry` | `int` | `0` | डिफ़ॉल्ट रीट्राई प्रयास |
| `retryDelay` | `Duration` | `1 second` | रीट्राई के बीच डिफ़ॉल्ट देरी |
| `checkConnectivityBeforeRequest` | `bool` | `false` | रिक्वेस्ट्स से पहले कनेक्टिविटी जाँचें |
