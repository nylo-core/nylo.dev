# डीकोडर्स

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- उपयोग
  - [मॉडल डीकोडर्स](#model-decoders "मॉडल डीकोडर्स")
  - [Typed डीकोडर्स](#typed-decoders "Decode और decoders()")
  - [OneOf](#one-of "ऐसे रिस्पॉन्स को डिकोड करना जिसके एक से अधिक रूप हो सकते हैं")
  - [API डीकोडर्स](#api-decoders "API डीकोडर्स")


<div id="introduction"></div>

## परिचय

डीकोडर्स {{ config('app.name') }} में पेश की गई एक अवधारणा है जो आपको डेटा को ऑब्जेक्ट्स या क्लासेज़ में डिकोड करने की अनुमति देती है।
आप संभवतः [नेटवर्किंग](/docs/7.x/networking) क्लास के साथ काम करते समय या {{ config('app.name') }} में `api` हेल्पर का उपयोग करना चाहते हैं तो डीकोडर्स का उपयोग करेंगे।

> डिफ़ॉल्ट रूप से, डीकोडर्स का स्थान `lib/config/decoders.dart` है

decoders.dart फ़ाइल में दो वेरिएबल होंगे:
- [modelDecoders](#model-decoders) - आपके सभी मॉडल डीकोडर्स को हैंडल करता है
- [apiDecoders](#api-decoders) - आपके सभी API डीकोडर्स को हैंडल करता है

<div id="model-decoders"></div>

## मॉडल डीकोडर्स

मॉडल डीकोडर्स {{ config('app.name') }} में नए हैं, वे डेटा पेलोड को मॉडल प्रतिनिधित्व में बदलने का एक तरीका प्रदान करते हैं।

`network()` हेल्पर मेथड यह निर्धारित करने के लिए आपकी <b>config/decoders.dart</b> फ़ाइल के अंदर `modelDecoders` वेरिएबल का उपयोग करेगा कि कौन सा डीकोडर उपयोग करना है।

यहाँ एक उदाहरण है।

यहाँ बताया गया है कि `network` हेल्पर modelDecoders का उपयोग कैसे करता है।

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

`fetchUsers` मेथड स्वचालित रूप से अनुरोध से पेलोड को `User` में डिकोड करेगा।

यह कैसे काम करता है?

आपके पास नीचे जैसी एक `User` क्लास है।

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

आप ऊपर से देख सकते हैं कि इस क्लास में एक `fromJson` मेथड है जो हमें क्लास को इनिशियलाइज़ करने का एक तरीका प्रदान करता है।

हम नीचे दिए गए मेथड को कॉल करके इस क्लास को इनिशियलाइज़ कर सकते हैं।

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

अब, अपने डीकोडर्स सेटअप करने के लिए, हमें निम्नलिखित करना होगा।

<b>फ़ाइल:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

modelDecoders फ़ाइल में, हमें `Type` को की के रूप में प्रदान करना होगा और ऊपर के उदाहरण की तरह वैल्यू में मॉर्फ को हैंडल करना होगा।

`data` आर्ग्युमेंट में API अनुरोध से पेलोड होगा।

<div id="typed-decoders"></div>

## Typed डीकोडर्स

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` एक सूची से आपका `modelDecoders` मैप बनाता है। `Decode(User.fromJson)` एक `User` को डिकोड करता है, और `List<User>` को भी, बिना किसी अलग एंट्री के:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

मॉडल को `{"data": {...}}` जैसी रैप्ड बॉडी के अंदर से पढ़ने के लिए `from` का उपयोग करें। `'data.user'` जैसी डॉटेड की और गहराई से पढ़ती है, और लिस्ट के लिए, लिस्ट स्वयं `from` से पढ़ी जाती है।

`Decode` एक `fromJson` लेता है जो `dynamic` या `Map<String, dynamic>` स्वीकार करता है। क्लोज़र के लिए, उसके पैरामीटर का टाइप दें:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

`{...}` मैप में क्लोज़र डीकोडर्स, जैसे `User: (data) => User.fromJson(data)`, पहले की तरह काम करते रहते हैं। दोनों एक `NyDecoder` हैं, वह क्लास जिसे `Decode` और `OneOf` एक्सटेंड करते हैं। कस्टम `NyDecoder<T>` `decode(json, response, decoders)` को इम्प्लीमेंट करता है और पूरा `NyResponse` देखता है, इसलिए वह स्टेटस कोड या हेडर्स पढ़ सकता है।

<div id="one-of"></div>

## OneOf

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` ऐसे रिस्पॉन्स को डिकोड करता है जिसके एक से अधिक रूप हो सकते हैं। इसे साझा पैरेंट टाइप के अंतर्गत रजिस्टर करें, और रूप को बॉडी के किसी फ़ील्ड, स्टेटस कोड, या अपनी शर्तों से चुनें:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` 4xx और 5xx बॉडी को भी डिकोड करता है, और `List<T>` को आइटम-दर-आइटम। जिस रूप को वह पहचान नहीं पाता, उसमें रिस्पॉन्स डेटा null रहता है।

| कंस्ट्रक्टर | रूप कैसे चुनता है |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | बॉडी में `key` का मान। `'data.type'` जैसी डॉटेड की नेस्टेड फ़ील्ड पढ़ती है |
| `OneOf.byStatus(types, otherwise:)` | स्टेटस कोड। की `404` जैसा कोड, या `'4xx'` या `'40x'` जैसा पैटर्न होती है |
| `OneOf.byJson(types, otherwise:)` | आपकी अपनी शर्तें, हर एक बॉडी और रिस्पॉन्स लेती है। जो पहली true लौटाए वह चुनी जाती है |
| `OneOf(resolve)` | एक फ़ंक्शन जो बॉडी और रिस्पॉन्स लेता है और मान लौटाता है, या null |

स्टेटस कोड द्वारा:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

सटीक कोड जीतता है, फिर सबसे कम `x` वाला पैटर्न।

अपनी शर्तों के साथ:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

फ़ंक्शन के साथ:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

रिस्पॉन्स से रूप पढ़ने के लिए `dataAs<X>()` और `dataIs<X>()` का उपयोग करें; देखें [NyResponse](/docs/{{$version}}/networking#ny-response)।

Metro पैरेंट, उसके सबटाइप्स और `OneOf.byKey` एंट्री आपके लिए बनाता है:

```bash
metro make:model login_result --one-of="success,two_factor"
```

देखें [OneOf मॉडल बनाएँ](/docs/{{$version}}/metro#make-a-one-of-model)।
<div id="api-decoders"></div>

## API डीकोडर्स

API डीकोडर्स `api` हेल्पर मेथड को कॉल करते समय उपयोग किए जाते हैं।

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

`api` हेल्पर जेनेरिक्स का उपयोग करके सही API सर्विस से मैच करेगा, इसलिए आप अपनी सर्विस एक्सेस करने के लिए नीचे दिया गया हेल्पर कॉल कर सकते हैं।

```dart
await api<MyService>((request) => request.callMyMethod());
```

`api` हेल्पर का उपयोग करने से पहले, आपको पहले अपनी API सर्विस को <b>lib/config/decoders.dart > apiDecoders</b> में जोड़ना होगा।

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
