# स्टेट मैनेजमेंट

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [स्टेट मैनेजमेंट कब उपयोग करें](#when-to-use-state-management "स्टेट मैनेजमेंट कब उपयोग करें")
- [लाइफ़साइकिल](#lifecycle "लाइफ़साइकिल")
- [स्टेट एक्शन्स](#state-actions "स्टेट एक्शन्स")
  - [NyState - स्टेट एक्शन्स](#state-actions-nystate "NyState - स्टेट एक्शन्स")
  - [NyPage - स्टेट एक्शन्स](#state-actions-nypage "NyPage - स्टेट एक्शन्स")
- [स्टेट अपडेट करना](#updating-a-state "स्टेट अपडेट करना")
- [अपना पहला विजेट बनाना](#building-your-first-widget "अपना पहला विजेट बनाना")

<div id="introduction"></div>

## परिचय

स्टेट मैनेजमेंट आपको पूरे पेज को फिर से बनाए बिना अपने UI के विशिष्ट भागों को अपडेट करने देता है। {{ config('app.name') }} v7 में, आप ऐसे विजेट्स बना सकते हैं जो आपके पूरे ऐप में संवाद करते हैं और एक-दूसरे को अपडेट करते हैं।

{{ config('app.name') }} स्टेट मैनेजमेंट के लिए दो क्लासेस प्रदान करता है:
- **`NyState`** — पुन: प्रयोज्य विजेट्स बनाने के लिए (जैसे कार्ट बैज, नोटिफ़िकेशन काउंटर, या स्टेटस इंडिकेटर)
- **`NyPage`** — आपके एप्लिकेशन में पेज बनाने के लिए (`NyState` को पेज-विशिष्ट सुविधाओं के साथ एक्सटेंड करता है)

स्टेट मैनेजमेंट का उपयोग करें जब आपको:
- अपने ऐप के किसी अन्य भाग से विजेट अपडेट करना हो
- साझा डेटा के साथ विजेट्स को सिंक में रखना हो
- UI का केवल एक भाग बदलने पर पूरे पेज को फिर से बनाने से बचना हो


### पहले स्टेट मैनेजमेंट को समझते हैं

Flutter में सब कुछ एक विजेट है, ये UI के छोटे-छोटे टुकड़े हैं जिन्हें आप एक पूरा ऐप बनाने के लिए जोड़ सकते हैं।

जब आप जटिल पेज बनाना शुरू करते हैं, तो आपको अपने विजेट्स के स्टेट को मैनेज करने की आवश्यकता होगी। इसका मतलब है कि जब कुछ बदलता है, जैसे डेटा, तो आप पूरे पेज को फिर से बनाए बिना उस विजेट को अपडेट कर सकते हैं।

इसके कई कारण हैं कि यह महत्वपूर्ण है, लेकिन मुख्य कारण प्रदर्शन है। यदि आपके पास एक विजेट है जो लगातार बदल रहा है, तो आप हर बार बदलने पर पूरे पेज को फिर से नहीं बनाना चाहेंगे।

यहीं स्टेट मैनेजमेंट काम आता है, यह आपको अपने एप्लिकेशन में किसी विजेट के स्टेट को मैनेज करने की अनुमति देता है।


<div id="when-to-use-state-management"></div>

### स्टेट मैनेजमेंट कब उपयोग करें

आपको स्टेट मैनेजमेंट का उपयोग तब करना चाहिए जब आपके पास कोई विजेट हो जिसे पूरे पेज को फिर से बनाए बिना अपडेट करने की आवश्यकता हो।

उदाहरण के लिए, मान लीजिए आपने एक ई-कॉमर्स ऐप बनाया है। आपने यूज़र के कार्ट में आइटम्स की कुल संख्या प्रदर्शित करने के लिए एक विजेट बनाया है।
इस विजेट को `Cart()` कहते हैं।

Nylo में एक स्टेट मैनेज्ड `Cart` विजेट कुछ इस तरह दिखेगा:

**चरण 1:** `NyStateManaged` को एक्सटेंड करते हुए विजेट परिभाषित करें

``` dart
/// Cart विजेट
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart"; // इस विजेट के state का यूनीक आइडेंटिफायर

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}
```

**चरण 2:** `NyState` को एक्सटेंड करते हुए स्टेट क्लास बनाएँ

``` dart
/// Cart विजेट की state क्लास
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // प्रारंभिक डेटा लोड करें
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": (data) async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**चरण 3:** कार्ट पढ़ने और अपडेट करने के लिए हेल्पर फ़ंक्शन बनाएँ

``` dart
/// स्टोरेज से cart की वैल्यू प्राप्त करें
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// cart की वैल्यू सेट करें और विजेट को नोटिफाई करें
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // यह विजेट पर stateUpdated() ट्रिगर करता है
}
```

इसे समझते हैं।

1. `Cart` विजेट `NyStateManaged` को एक्सटेंड करता है (सीधे `StatefulWidget` नहीं)।

2. `stateName` कंस्ट्रक्टर पैरामीटर `super(child: () => _CartState(stateName))` के माध्यम से फॉरवर्ड किया जाता है, जो एक ही विजेट के कई अलग-अलग इंस्टेंस को सक्षम करता है।

3. `_stateFor(String? state)` हेल्पर नामांकित इंस्टेंस के लिए `"cart_sidebar"` जैसी नेमस्पेस्ड स्टेट की बनाता है।

4. `_CartState` `NyState<Cart>` को एक्सटेंड करता है और सही अलग-अलग स्टेट रजिस्टर करने के लिए `stateName` प्राप्त करता है।

5. `stateActions` मैप ऐसे नामित कमांड्स परिभाषित करता है जिन्हें आप अपने ऐप में कहीं से भी विजेट पर इनवोक कर सकते हैं।

यदि आप इस उदाहरण को अपने {{ config('app.name') }} प्रोजेक्ट में आज़माना चाहते हैं, तो `Cart` नाम का एक नया विजेट बनाएँ।

``` bash
metro make:state_managed_widget cart
```

फिर आप ऊपर दिए गए उदाहरण को कॉपी करके अपने प्रोजेक्ट में आज़मा सकते हैं।

अब, कार्ट अपडेट करने के लिए, आप निम्नलिखित कॉल कर सकते हैं।

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## लाइफ़साइकिल

`NyState` विजेट का लाइफ़साइकिल इस प्रकार है:

1. `init()` - यह मेथड स्टेट के इनिशियलाइज़ होने पर कॉल होता है।

2. `stateUpdated(data)` - यह मेथड स्टेट अपडेट होने पर कॉल होता है।

    यदि आप `updateState(MyStateName.state, data: "The Data")` कॉल करते हैं, तो यह **stateUpdated(data)** को ट्रिगर करेगा।

एक बार स्टेट पहली बार इनिशियलाइज़ हो जाने के बाद, आपको यह लागू करना होगा कि आप स्टेट को कैसे मैनेज करना चाहते हैं।


<div id="state-actions"></div>

## स्टेट एक्शन्स

स्टेट एक्शन्स आपको अपने ऐप में कहीं से भी किसी विजेट पर विशिष्ट मेथड्स ट्रिगर करने देते हैं। इन्हें नामित कमांड्स समझें जो आप किसी विजेट को भेज सकते हैं।

स्टेट एक्शन्स का उपयोग करें जब आपको:
- किसी विजेट पर एक विशिष्ट व्यवहार ट्रिगर करना हो (सिर्फ रिफ्रेश नहीं)
- किसी विजेट को डेटा पास करना हो और उसे एक विशेष तरीके से प्रतिक्रिया करवानी हो
- पुन: प्रयोज्य विजेट व्यवहार बनाने हों जो कई स्थानों से इनवोक किए जा सकें

``` dart
// विजेट को एक्शन भेजना
stateAction('hello_world_in_widget', state: MyWidget.state);

// डेटा के साथ एक और उदाहरण
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

अपने विजेट में, आप उन एक्शन्स को परिभाषित कर सकते हैं जिन्हें आप हैंडल करना चाहते हैं।

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // डेटा के साथ उदाहरण
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

फिर, आप अपने एप्लिकेशन में कहीं से भी `stateAction` मेथड कॉल कर सकते हैं।

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// 'Hello world' प्रिंट करता है

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

यदि आपके पास पहले से कोई `StateActions` इंस्टेंस है (उदा. किसी विजेट के `stateActions()` स्टैटिक मेथड से), तो फ्री फंक्शन का उपयोग करने के बजाय आप उस पर सीधे `action()` कॉल कर सकते हैं:

``` dart
// फ्री फंक्शन का उपयोग करते हुए
stateAction('reset_avatar', state: UserAvatar.state);

// StateActions इंस्टेंस मेथड का उपयोग करते हुए — समकक्ष, कम पुनरावृत्ति
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action('reset_avatar');
actions.action('update_user_image', data: user);
```

आप अपने `init` गेटर में `whenStateAction` मेथड का उपयोग करके भी अपने स्टेट एक्शन्स परिभाषित कर सकते हैं।

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // बैज काउंट रीसेट करें
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - स्टेट एक्शन्स

सबसे पहले, एक stateful विजेट बनाएँ।

``` bash
metro make:stateful_widget [widget_name]
```
उदाहरण: metro make:stateful_widget user_avatar

यह `lib/resources/widgets/` डायरेक्टरी में एक नया विजेट बनाएगा।

यदि आप वह फ़ाइल खोलते हैं, तो आप अपने स्टेट एक्शन्स परिभाषित कर पाएँगे।

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // उदाहरण
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // उदाहरण
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

अंत में, आप अपने एप्लिकेशन में कहीं से भी एक्शन भेज सकते हैं।

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// 'Hello from the widget' प्रिंट करता है

stateAction('reset_data', state: MyWidget.state);
// विजेट में डेटा रीसेट करें

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// संदेश के साथ एक सफलता टोस्ट दिखाता है
```


<div id="state-actions-nypage"></div>

### NyPage - स्टेट एक्शन्स

पेज भी स्टेट एक्शन्स प्राप्त कर सकते हैं। यह तब उपयोगी है जब आप विजेट्स या अन्य पेज से पेज-स्तरीय व्यवहार ट्रिगर करना चाहते हैं।

सबसे पहले, अपना स्टेट मैनेज्ड पेज बनाएँ।

``` bash
metro make:page my_page
```

यह `lib/resources/pages/` डायरेक्टरी में `MyPage` नाम का एक नया स्टेट मैनेज्ड पेज बनाएगा।

यदि आप वह फ़ाइल खोलते हैं, तो आप अपने स्टेट एक्शन्स परिभाषित कर पाएँगे।

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => false; // set to true to enable state actions on this page

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // उदाहरण
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

अंत में, आप अपने एप्लिकेशन में कहीं से भी एक्शन भेज सकते हैं।

``` dart
stateAction('test_page_action', state: MyPage.path);
// 'Hello from the page' प्रिंट करता है

stateAction('reset_data', state: MyPage.path);
// पेज में डेटा रीसेट करें

stateAction('show_toast', state: MyPage.path, data: {
  "message": "Hello from the page"
});
// संदेश के साथ एक सफलता टोस्ट दिखाता है
```

आप `whenStateAction` मेथड का उपयोग करके भी अपने स्टेट एक्शन्स परिभाषित कर सकते हैं।

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // बैज काउंट रीसेट करें
      _count = 0;
    }
  });
}
```

फिर आप अपने एप्लिकेशन में कहीं से भी एक्शन भेज सकते हैं।

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## स्टेट अपडेट करना

आप `updateState()` मेथड कॉल करके स्टेट अपडेट कर सकते हैं।

``` dart
updateState(MyStateName.state);

// या डेटा के साथ
updateState(MyStateName.state, data: "The Data");
```

इसे आपके एप्लिकेशन में कहीं से भी कॉल किया जा सकता है।

**यह भी देखें:** [NyState](/docs/{{ $version }}/ny-state) स्टेट मैनेजमेंट हेल्पर्स और लाइफ़साइकिल मेथड्स के बारे में अधिक जानकारी के लिए।


<div id="building-your-first-widget"></div>

## अपना पहला विजेट बनाना

अपने Nylo प्रोजेक्ट में, एक नया विजेट बनाने के लिए निम्नलिखित कमांड चलाएँ।

``` bash
metro make:stateful_widget todo_list
```

यह `TodoList` नाम का एक नया `NyState` विजेट बनाएगा।

> नोट: नया विजेट `lib/resources/widgets/` डायरेक्टरी में बनाया जाएगा।
