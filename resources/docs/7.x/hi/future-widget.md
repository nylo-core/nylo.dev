# FutureWidget

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
- [लोडिंग स्टेट कस्टमाइज़ करना](#customizing-loading "लोडिंग स्टेट कस्टमाइज़ करना")
    - [सामान्य लोडिंग स्टाइल](#normal-loading "सामान्य लोडिंग स्टाइल")
    - [स्केलेटनाइज़र लोडिंग स्टाइल](#skeletonizer-loading "स्केलेटनाइज़र लोडिंग स्टाइल")
    - [कोई लोडिंग स्टाइल नहीं](#no-loading "कोई लोडिंग स्टाइल नहीं")
- [एरर हैंडलिंग](#error-handling "एरर हैंडलिंग")


<div id="introduction"></div>

## परिचय

**FutureWidget** आपके {{ config('app.name') }} प्रोजेक्ट्स में `Future`'s को रेंडर करने का एक सरल तरीका है। यह Flutter के `FutureBuilder` को रैप करता है और बिल्ट-इन लोडिंग स्टेट्स के साथ एक क्लीनर API प्रदान करता है।

जब आपका Future प्रगति में होता है, तो यह एक लोडर दिखाएगा। Future पूरा होने पर, डेटा `child` कॉलबैक के माध्यम से लौटाया जाता है।

<div id="basic-usage"></div>

## बेसिक उपयोग

यहाँ `FutureWidget` उपयोग करने का एक सरल उदाहरण है:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

विजेट Future पूरा होने तक आपके यूज़र्स के लिए लोडिंग स्टेट को स्वचालित रूप से हैंडल करेगा।

<div id="customizing-loading"></div>

## लोडिंग स्टेट कस्टमाइज़ करना

आप `loadingStyle` पैरामीटर का उपयोग करके लोडिंग स्टेट की दिखावट को कस्टमाइज़ कर सकते हैं।

<div id="normal-loading"></div>

### सामान्य लोडिंग स्टाइल

एक स्टैंडर्ड लोडिंग विजेट दिखाने के लिए `LoadingStyle.normal()` का उपयोग करें। आप वैकल्पिक रूप से एक कस्टम चाइल्ड विजेट प्रदान कर सकते हैं:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

यदि कोई चाइल्ड प्रदान नहीं किया जाता है, तो डिफ़ॉल्ट {{ config('app.name') }} ऐप लोडर दिखाया जाएगा।

<div id="skeletonizer-loading"></div>

### स्केलेटनाइज़र लोडिंग स्टाइल

स्केलेटन लोडिंग इफेक्ट दिखाने के लिए `LoadingStyle.skeletonizer()` का उपयोग करें। यह प्लेसहोल्डर UI दिखाने के लिए बहुत अच्छा है जो आपके कंटेंट लेआउट से मेल खाता है:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

उपलब्ध स्केलेटन इफेक्ट्स:
- `SkeletonizerEffect.shimmer` - एनिमेटेड शिमर इफेक्ट (डिफ़ॉल्ट)
- `SkeletonizerEffect.pulse` - पल्सिंग एनिमेशन इफेक्ट
- `SkeletonizerEffect.solid` - सॉलिड कलर इफेक्ट

<div id="no-loading"></div>

### कोई लोडिंग स्टाइल नहीं

लोडिंग इंडिकेटर को पूरी तरह से छिपाने के लिए `LoadingStyle.none()` का उपयोग करें:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## एरर हैंडलिंग

आप `onError` कॉलबैक का उपयोग करके अपने Future से एरर्स को हैंडल कर सकते हैं:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

यदि कोई `onError` कॉलबैक प्रदान नहीं किया गया है और कोई एरर होती है, तो एक खाली `SizedBox.shrink()` दिखाया जाएगा।

### पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `future` | `Future<T>?` | प्रतीक्षा करने वाला Future |
| `child` | `Widget Function(BuildContext, T?)` | Future पूरा होने पर कॉल होने वाला बिल्डर फ़ंक्शन |
| `loadingStyle` | `LoadingStyle?` | लोडिंग इंडिकेटर कस्टमाइज़ करें |
| `onError` | `Widget Function(AsyncSnapshot)?` | Future में एरर होने पर कॉल होने वाला बिल्डर फ़ंक्शन |
