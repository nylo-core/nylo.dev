# स्टेट मैनेज्ड विजेट्स

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [अपना पैटर्न चुनें](#choose-your-pattern "अपना पैटर्न चुनें")
- [State Actions](#state-actions "State Actions")
  - [हैंडलर्स परिभाषित करना](#defining-handlers "हैंडलर्स परिभाषित करना")
  - [एक्शन ट्रिगर करना](#triggering-actions "एक्शन ट्रिगर करना")
  - [डेटा के साथ और बिना हैंडलर्स](#handlers-with-and-without-data "डेटा के साथ और बिना हैंडलर्स")
  - [StateActions इंस्टेंस का उपयोग](#using-a-state-actions-instance "StateActions इंस्टेंस का उपयोग")
- [पैटर्न: स्टेट मैनेज्ड पेजेज (NyPage)](#pattern-ny-page "पैटर्न: स्टेट मैनेज्ड पेजेज")
- [पैटर्न: Stateful विजेट्स (NyState)](#pattern-ny-state "पैटर्न: Stateful विजेट्स")
- [पैटर्न: स्टेट मैनेज्ड विजेट्स (NyStateManaged)](#pattern-ny-state-managed "पैटर्न: स्टेट मैनेज्ड विजेट्स")
  - [एडवांस्ड: एकाधिक अलग-अलग इंस्टेंसेज](#advanced-multiple-isolated-instances "एडवांस्ड: एकाधिक अलग-अलग इंस्टेंसेज")
- [लाइफ़साइकिल रेफरेंस](#lifecycle "लाइफ़साइकिल रेफरेंस")

<div id="introduction"></div>

## परिचय

स्टेट मैनेज्ड विजेट्स आपको पूरे पेज को फिर से बनाए बिना अपने ऐप में कहीं से भी UI के विशिष्ट भागों को अपडेट करने देते हैं। {{ config('app.name') }} v7 में, आप किसी लक्ष्य विजेट या पेज पर नामित **state actions** ट्रिगर करते हैं, और मिलान करने वाला हैंडलर चलता है।

मॉडल सरल है:

- प्रत्येक स्टेट मैनेज्ड विजेट या पेज का एक अद्वितीय **स्टेट की** (एक स्ट्रिंग) होती है
- यह **नामित एक्शन्स** का एक मैप परिभाषित करता है जिन्हें वह हैंडल करना जानता है
- आपके ऐप में कहीं से भी, आप कॉल कर सकते हैं
```
stateAction("action_name", state: TargetWidget.state)
``` 

स्टेट मैनेज्ड UI बनाने के तीन पैटर्न हैं। वे सभी एक ही `stateAction` मैकेनिक साझा करते हैं — केवल अंतर यह है कि आप किस प्रकार की UI मैनेज कर रहे हैं।


<div id="choose-your-pattern"></div>

## अपना पैटर्न चुनें

| आप चाहते हैं... | उपयोग करें | स्कैफोल्ड कमांड |
|---|---|---|
| एक पूरे पेज को स्टेट-मैनेज करना | `NyPage` | `metro make:page my_page` |
| एक सिंगल-इंस्टेंस विजेट को स्टेट-मैनेज करना | `NyState` | `metro make:stateful_widget my_widget` |
| एकाधिक अलग-अलग इंस्टेंसेज वाले विजेट को स्टेट-मैनेज करना | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

पहले [State Actions](#state-actions) सेक्शन पढ़ें — यह उस API को समझाता है जिसे प्रत्येक पैटर्न उपयोग करता है। फिर अपने केस के लिए उचित पैटर्न पर जाएं।


<div id="state-actions"></div>

## State Actions

State Actions वे नामित कमांड हैं जिन्हें एक विजेट या पेज हैंडल करना जानता है। आप एक्शन नामों से हैंडलर फंक्शन्स का मैप परिभाषित करते हैं, और उन्हें अपने ऐप में कहीं से भी नाम से ट्रिगर करते हैं।

State Actions तब उपयोग करें जब आपको:
- किसी विजेट या पेज पर एक विशिष्ट व्यवहार ट्रिगर करना हो (सिर्फ एक जेनेरिक रिफ्रेश नहीं)
- किसी विजेट को डेटा पास करना हो और उसे एक परिभाषित तरीके से प्रतिक्रिया करवानी हो
- पुन: प्रयोज्य विजेट व्यवहार बनाने हों जो कई कॉल साइट्स से इनवोक किए जा सकें

<div id="defining-handlers"></div>

### हैंडलर्स परिभाषित करना

हैंडलर्स आपके `NyState` या `NyPage` क्लास पर `stateActions` गेटर में रहते हैं। मैप की keys एक्शन नाम हैं; values वे फंक्शन हैं जो उस एक्शन के ट्रिगर होने पर चलते हैं।

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

हैंडलर्स खुद `setState` कॉल करने के लिए जिम्मेदार हैं यदि वे विजेट को फिर से बनाना चाहते हैं।

ऊपर का `apply_discount` हैंडलर एक `code` आर्ग्यूमेंट लेता है — एक सिंगल पोजिशनल पैरामीटर घोषित करें जब आपके हैंडलर को निम्नलिखित के माध्यम से पास किया गया पेलोड चाहिए
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

नो-आर्ग फॉर्म `()` तब उपयोग करें जब एक्शन कोई पेलोड नहीं ले जाता।


<div id="triggering-actions"></div>

### एक्शन ट्रिगर करना

कहीं से भी एक्शन फायर करने के लिए टॉप-लेवल `stateAction` फंक्शन का उपयोग करें — दूसरे विजेट, कंट्रोलर, इवेंट हैंडलर, API कॉलबैक आदि से।

``` dart
// बिना डेटा के एक्शन फायर करें
stateAction("clear_cart", state: Cart.state);

// डेटा के साथ एक्शन फायर करें
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

`state:` आर्ग्यूमेंट टार्गेट की **स्टेट की** है:
- विजेट्स के लिए — `MyWidget.state` (एक स्ट्रिंग)
- पेजेज के लिए — `MyPage.path` (रूट)


<div id="handlers-with-and-without-data"></div>

### डेटा के साथ और बिना हैंडलर्स

हैंडलर्स sync या async हो सकते हैं, और `data` आर्ग्यूमेंट के साथ या बिना परिभाषित किए जा सकते हैं:

``` dart
@override
Map<String, Function> get stateActions => {
  // डेटा नहीं — हैंडलर ज्यों का त्यों चलता है
  "reset": () {
    _value = null;
    setState(() {});
  },

  // डेटा के साथ — `data:` आर्ग्यूमेंट के माध्यम से जो पास किया गया वह प्राप्त करता है
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async समर्थित है — फ्रेमवर्क हैंडलर को await करता है
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

यदि आप `stateAction` को `data:` पास करते हैं लेकिन आपका हैंडलर कोई आर्ग्यूमेंट नहीं लेता, तो डेटा बस अनदेखा कर दिया जाता है।


<div id="using-a-state-actions-instance"></div>

### StateActions इंस्टेंस का उपयोग

यदि कोई विजेट एक टाइप्ड `StateActions` इंस्टेंस (अक्सर `stateActions(stateName)` स्टैटिक मेथड के माध्यम से) एक्सपोज करता है, तो आप फ्री फंक्शन के बजाय उस पर सीधे `.action(...)` कॉल कर सकते हैं। यह तब साफ होता है जब एक ही टार्गेट पर कई एक्शन्स फायर की जाती हैं:

``` dart
// फ्री फंक्शन का उपयोग करना
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// StateActions इंस्टेंस का उपयोग करना — समकक्ष, कम दोहराव
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

कई बिल्ट-इन विजेट्स (`InputField`, `CollectionView`, `LanguageSwitcher`, `NyForm*` फैमिली) `.clear()`, `.setValue(...)`, `.refresh()` जैसी नामित मेथड्स के साथ टाइप्ड `StateActions` क्लासेज के साथ आते हैं — उपलब्ध चीज़ों के लिए विजेट के डॉक्स चेक करें।


<div id="pattern-ny-page"></div>

## पैटर्न: स्टेट मैनेज्ड पेजेज (NyPage)

जब आप कहीं से किसी पूरे पेज पर व्यवहार ट्रिगर करना चाहते हों तो इसका उपयोग करें — उदाहरण के लिए, कोई इवेंट फायर होने पर पेज रिफ्रेश करना, या किसी चाइल्ड विजेट से फॉर्म स्टेट क्लियर करना।

**स्टेप 1:** एक पेज स्कैफोल्ड करें।

``` bash
metro make:page my_page
```

यह `lib/resources/pages/` में एक `NyPage` जनरेट करता है:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**स्टेप 2:** `stateManaged` को `true` पर स्विच करें।

डिफ़ॉल्ट रूप से, पेजेज स्टेट इवेंट्स को सब्सक्राइब **नहीं** करते — यह उन पेजेज पर अनावश्यक लिस्नर्स से बचाता है जिन्हें उनकी जरूरत नहीं है। किसी पेज पर state actions सक्षम करने के लिए, गेटर बदलें:

``` dart
@override
bool get stateManaged => true;
```

**स्टेप 3:** एक `stateActions` मैप जोड़ें।

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**स्टेप 4:** पेज के `path` का उपयोग करके कहीं से भी एक्शन ट्रिगर करें।

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> पेजेज `MyPage.path` से की ऑफ करते हैं, विजेट्स `MyWidget.state` से की ऑफ करते हैं। कॉल साइट पर यही एकमात्र अंतर है।


<div id="pattern-ny-state"></div>

## पैटर्न: Stateful विजेट्स (NyState)

ऐसे पुन: प्रयोज्य विजेट्स के लिए उपयोग करें जो प्रति पेज एक ही इंस्टेंस के रूप में मौजूद हों — एक प्रोफाइल कार्ड, हेडर बार, स्टेटस इंडिकेटर। यदि आप एक समय में विजेट का केवल एक इंस्टेंस रेंडर करते हैं, तो यह सही पैटर्न है।

**स्टेप 1:** एक stateful विजेट स्कैफोल्ड करें।

``` bash
metro make:stateful_widget profile_card
```

यह `lib/resources/widgets/` में निम्नलिखित जनरेट करता है:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

स्कैफोल्ड आपको एक काम करने वाला `NyState` विजेट देता है — लेकिन यह **अभी तक स्टेट मैनेज्ड नहीं है**। इसे state actions पर प्रतिक्रिया देने के लिए, आपको चार चीजें जोड़नी होंगी:

1. विजेट क्लास पर एक `state` की — वह अद्वितीय स्ट्रिंग जिसे आपके ऐप के अन्य भाग टार्गेट करेंगे
2. एक कंस्ट्रक्टर पैरामीटर जो स्टेट की प्राप्त करता है और उसे स्टेट क्लास को फॉरवर्ड करता है
3. स्टेट क्लास पर एक कंस्ट्रक्टर जो `stateName` असाइन करता है
4. हैंडलर्स परिभाषित करने वाला एक `stateActions` मैप

**स्टेप 2:** स्कैफोल्ड को स्टेट मैनेज्ड विजेट में बदलें।

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ इसे अपने ऐप में कहीं से भी कॉल करें नीचे के हैंडलर को ट्रिगर करने के लिए
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

क्या बदला:
- `static String get state => "profile_card";` पब्लिक स्टेट की परिभाषित करता है
- `createState() => _ProfileCardState(state)` की को स्टेट क्लास में पास करता है
- `_ProfileCardState(String? state) { this.stateName = state; }` उस की के नीचे इस स्टेट इंस्टेंस को रजिस्टर करता है, ताकि फ्रेमवर्क जान सके कि एक्शन किस विजेट को डिलीवर करनी है
- `stateActions` नामित हैंडलर्स घोषित करता है

**स्टेप 3:** कहीं से भी ट्रिगर करें।

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

आप डेटा के साथ या बिना जितने चाहें उतने हैंडलर जोड़ सकते हैं:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## पैटर्न: स्टेट मैनेज्ड विजेट्स (NyStateManaged)

जब आपको स्क्रीन पर **एक ही विजेट के एकाधिक स्वतंत्र इंस्टेंसेज** की जरूरत हो तो इसका उपयोग करें — उदाहरण के लिए, हेडर में कार्ट बैज और साइडबार में एक और बैज जो दोनों स्वतंत्र रूप से अपडेट होने चाहिए।

`NyStateManaged` एक `stateName` पैरामीटर जोड़ता है ताकि प्रत्येक इंस्टेंस को अलग से एड्रेस किया जा सके। यदि आप विजेट का केवल एक इंस्टेंस रेंडर करते हैं, तो `NyState` को प्राथमिकता दें — यह सरल है।

**स्टेप 1:** एक स्टेट मैनेज्ड विजेट स्कैफोल्ड करें।

``` bash
metro make:state_managed_widget cart
```

यह `lib/resources/widgets/` में निम्नलिखित जनरेट करता है:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart";

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}

class _CartState extends NyState<Cart> {
  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () {
    // यहाँ इनिशियलाइज़ेशन लॉजिक है
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // अपने ऐप में कहीं से भी एक्शन्स इनवोक करें
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**स्टेप 2:** स्टेट को पूरा करें — `init` में डेटा लोड करें, हैंडलर्स परिभाषित करें, और रेंडर करें।

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**स्टेप 3:** जनरेट किए गए स्टैटिक `action()` हेल्पर का उपयोग करके एक्शन्स ट्रिगर करें।

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

यह `stateAction("reload_cart", state: Cart.state)` के समकक्ष है — स्टैटिक हेल्पर बस बॉयलरप्लेट हटाता है।


<div id="advanced-multiple-isolated-instances"></div>

### एडवांस्ड: एकाधिक अलग-अलग इंस्टेंसेज

`NyStateManaged` के अस्तित्व का कारण एक ही विजेट के एकाधिक स्वतंत्र इंस्टेंसेज को सपोर्ट करना है। प्रत्येक इंस्टेंस को अपना `stateName` मिलता है, जो एक नेमस्पेस्ड स्टेट की बनाता है।

अलग-अलग नामों से दो कार्ट रेंडर करें:

``` dart
Column(
  children: [
    Cart(stateName: "header"),
    Cart(stateName: "sidebar"),
  ],
)
```

अब आप किसी भी एक को स्वतंत्र रूप से अपडेट कर सकते हैं:

``` dart
// केवल हेडर कार्ट रीलोड करें
Cart.action("reload_cart", stateName: "header");

// केवल साइडबार कार्ट रीलोड करें
Cart.action("reload_cart", stateName: "sidebar");

// stateName नहीं — डिफ़ॉल्ट अनाम इंस्टेंस को टार्गेट करता है
Cart.action("reload_cart");
```

`_stateFor` हेल्पर नेमस्पेसिंग को हैंडल करता है: `Cart(stateName: "header")` की `"cart_header"` के नीचे रजिस्टर होता है, और `Cart.action(..., stateName: "header")` उस सटीक की को टार्गेट करता है।


<div id="lifecycle"></div>

## लाइफ़साइकिल रेफरेंस

स्टेट मैनेज्ड विजेट्स और पेजेज दो प्रमुख लाइफ़साइकिल हुक्स साझा करते हैं:

1. **`init()`** — एक बार जब स्टेट पहली बार बनाई जाती है तब कॉल होती है। प्रारंभिक डेटा लोड करने के लिए इसका उपयोग करें।

2. **`stateUpdated(data)`** — जब भी इस स्टेट के विरुद्ध कोई state action फायर होती है तब कॉल होती है। `data` आर्ग्यूमेंट पूरा पेलोड है (जिसमें एक्शन नाम और एक्शन का डेटा शामिल है)। इसे ओवरराइड करें यदि आपको *हर* state action पर प्रतिक्रिया देनी है — अधिकांश समय, `stateActions` में हैंडलर्स परिभाषित करना ही सही तरीका है।

**यह भी देखें:** स्टेट हेल्पर्स और लाइफ़साइकिल मेथड्स के पूरे सेट के लिए [NyState](/docs/{{ $version }}/ny-state)।
