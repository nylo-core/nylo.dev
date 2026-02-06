# डीकोडर्स

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- उपयोग
  - [मॉडल डीकोडर्स](#model-decoders "मॉडल डीकोडर्स")
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
