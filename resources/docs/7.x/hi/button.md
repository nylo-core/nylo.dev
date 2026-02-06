# Button

---

<a name="section-1"></a>
- [परिचय](#introduction "Introduction")
- [बुनियादी उपयोग](#basic-usage "Basic Usage")
- [उपलब्ध बटन प्रकार](#button-types "Available Button Types")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [एसिंक लोडिंग स्टेट](#async-loading "Async Loading State")
- [एनिमेशन स्टाइल](#animation-styles "Animation Styles")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [स्प्लैश स्टाइल](#splash-styles "Splash Styles")
- [लोडिंग स्टाइल](#loading-styles "Loading Styles")
- [फॉर्म सबमिशन](#form-submission "Form Submission")
- [बटन कस्टमाइज़ करना](#customizing-buttons "Customizing Buttons")
- [पैरामीटर रेफरेंस](#parameters "Parameters Reference")


<div id="introduction"></div>

## परिचय

{{ config('app.name') }} आठ पूर्व-निर्मित बटन स्टाइल के साथ एक `Button` क्लास प्रदान करता है। प्रत्येक बटन में निम्नलिखित के लिए बिल्ट-इन सपोर्ट है:

- **एसिंक लोडिंग स्टेट** -- `onPressed` से एक `Future` लौटाएँ और बटन स्वचालित रूप से लोडिंग इंडिकेटर दिखाता है
- **एनिमेशन स्टाइल** -- clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph, और shake इफेक्ट में से चुनें
- **स्प्लैश स्टाइल** -- ripple, highlight, glow, या ink टच फीडबैक जोड़ें
- **फॉर्म सबमिशन** -- एक बटन को सीधे `NyFormData` इंस्टेंस से जोड़ें

आप अपने ऐप की बटन परिभाषाएँ `lib/resources/widgets/buttons/buttons.dart` में पा सकते हैं। इस फ़ाइल में प्रत्येक बटन प्रकार के लिए स्टैटिक मेथड के साथ एक `Button` क्लास है, जो आपके प्रोजेक्ट के डिफ़ॉल्ट को कस्टमाइज़ करना आसान बनाती है।

<div id="basic-usage"></div>

## बुनियादी उपयोग

अपने विजेट्स में कहीं भी `Button` क्लास का उपयोग करें। यहाँ एक पेज के अंदर एक सरल उदाहरण है:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

प्रत्येक बटन प्रकार एक ही पैटर्न का पालन करता है -- एक `text` लेबल और एक `onPressed` कॉलबैक पास करें।

<div id="button-types"></div>

## उपलब्ध बटन प्रकार

सभी बटन स्टैटिक मेथड का उपयोग करके `Button` क्लास के माध्यम से एक्सेस किए जाते हैं।

<div id="primary"></div>

### Primary

आपके थीम की प्राइमरी कलर का उपयोग करने वाला शैडो के साथ एक भरा हुआ बटन। मुख्य कॉल-टू-एक्शन तत्वों के लिए सर्वोत्तम।

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

एक नरम सतह रंग और सूक्ष्म शैडो के साथ एक भरा हुआ बटन। प्राइमरी बटन के साथ सेकेंडरी एक्शन के लिए अच्छा।

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

बॉर्डर स्ट्रोक के साथ एक पारदर्शी बटन। कम प्रमुख एक्शन या कैंसल बटन के लिए उपयोगी।

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

आप बॉर्डर और टेक्स्ट रंगों को कस्टमाइज़ कर सकते हैं:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

बिना बैकग्राउंड या बॉर्डर के एक न्यूनतम बटन। इनलाइन एक्शन या लिंक के लिए आदर्श।

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

आप टेक्स्ट रंग को कस्टमाइज़ कर सकते हैं:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

एक भरा हुआ बटन जो टेक्स्ट के साथ एक आइकन प्रदर्शित करता है। आइकन डिफ़ॉल्ट रूप से टेक्स्ट से पहले दिखाई देता है।

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

आप बैकग्राउंड रंग को कस्टमाइज़ कर सकते हैं:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

लीनियर ग्रेडिएंट बैकग्राउंड वाला एक बटन। डिफ़ॉल्ट रूप से आपके थीम की प्राइमरी और टर्शियरी कलर का उपयोग करता है।

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

आप कस्टम ग्रेडिएंट रंग प्रदान कर सकते हैं:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

पूरी तरह गोल कोनों वाला एक गोली के आकार का बटन। बॉर्डर रेडियस डिफ़ॉल्ट रूप से बटन की ऊँचाई का आधा होता है।

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

आप बैकग्राउंड रंग और बॉर्डर रेडियस को कस्टमाइज़ कर सकते हैं:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

बैकड्रॉप ब्लर इफेक्ट के साथ फ्रॉस्टेड ग्लास स्टाइल बटन। इमेज या रंगीन बैकग्राउंड पर रखने पर अच्छा काम करता है।

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

आप टेक्स्ट रंग को कस्टमाइज़ कर सकते हैं:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## एसिंक लोडिंग स्टेट

{{ config('app.name') }} बटन की सबसे शक्तिशाली सुविधाओं में से एक है **स्वचालित लोडिंग स्टेट प्रबंधन**। जब आपका `onPressed` कॉलबैक एक `Future` लौटाता है, तो बटन स्वचालित रूप से एक लोडिंग इंडिकेटर दिखाता है और ऑपरेशन पूरा होने तक इंटरेक्शन अक्षम कर देता है।

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

एसिंक ऑपरेशन चलने के दौरान, बटन एक स्केलेटन लोडिंग इफेक्ट दिखाएगा (डिफ़ॉल्ट रूप से)। `Future` पूरा होने पर, बटन अपनी सामान्य स्थिति में लौट आता है।

यह किसी भी एसिंक ऑपरेशन के साथ काम करता है -- API कॉल, डेटाबेस राइट, फ़ाइल अपलोड, या कुछ भी जो `Future` लौटाता है:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

`isLoading` स्टेट वेरिएबल प्रबंधित करने, `setState` कॉल करने, या किसी चीज़ को `StatefulWidget` में रैप करने की आवश्यकता नहीं -- {{ config('app.name') }} यह सब आपके लिए संभालता है।

### यह कैसे काम करता है

जब बटन पता लगाता है कि `onPressed` एक `Future` लौटाता है, तो वह `lockRelease` मैकेनिज़्म का उपयोग करता है:

1. एक लोडिंग इंडिकेटर दिखाएँ (`LoadingStyle` द्वारा नियंत्रित)
2. डुप्लिकेट टैप रोकने के लिए बटन अक्षम करें
3. `Future` पूरा होने की प्रतीक्षा करें
4. बटन को उसकी सामान्य स्थिति में पुनर्स्थापित करें

<div id="animation-styles"></div>

## एनिमेशन स्टाइल

बटन `ButtonAnimationStyle` के माध्यम से प्रेस एनिमेशन का समर्थन करते हैं। ये एनिमेशन दृश्य फीडबैक प्रदान करते हैं जब कोई उपयोगकर्ता बटन के साथ इंटरेक्ट करता है। आप `lib/resources/widgets/buttons/buttons.dart` में अपने बटन कस्टमाइज़ करते समय एनिमेशन स्टाइल सेट कर सकते हैं।

<div id="anim-clickable"></div>

### Clickable

एक Duolingo-स्टाइल 3D प्रेस इफेक्ट। बटन प्रेस पर नीचे ट्रांसलेट होता है और रिलीज़ पर वापस स्प्रिंग करता है। प्राइमरी एक्शन और गेम जैसे UX के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

प्रेस पर बटन को नीचे स्केल करता है और रिलीज़ पर वापस स्प्रिंग करता है। कार्ट में जोड़ें, लाइक, और फेवरिट बटन के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

बटन को दबाए रखने पर एक सूक्ष्म निरंतर स्केल पल्स। लॉन्ग-प्रेस एक्शन या ध्यान आकर्षित करने के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

प्रेस पर बटन को क्षैतिज रूप से संपीड़ित करता है और लंबवत रूप से विस्तारित करता है। चंचल और इंटरेक्टिव UI के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

एक लहराता लचीला विरूपण इफेक्ट। मज़ेदार, कैज़ुअल, या मनोरंजन ऐप्स के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

प्रेस पर बटन के ऊपर से गुजरने वाला एक चमकदार हाइलाइट। प्रीमियम सुविधाओं या ध्यान आकर्षित करने वाले CTA के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

टच पॉइंट से फैलने वाला एक उन्नत रिपल इफेक्ट। Material Design एम्फेसिस के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

प्रेस पर बटन का बॉर्डर रेडियस बढ़ जाता है, जिससे आकार बदलने का इफेक्ट बनता है। सूक्ष्म, सुंदर फीडबैक के लिए सर्वोत्तम।

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

एक क्षैतिज शेक एनिमेशन। एरर स्टेट या अमान्य एक्शन के लिए सर्वोत्तम -- कुछ गलत होने का संकेत देने के लिए बटन को शेक करें।

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

इफेक्ट को फाइन-ट्यून करें:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### एनिमेशन अक्षम करना

बिना एनिमेशन के बटन उपयोग करने के लिए:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### डिफ़ॉल्ट एनिमेशन बदलना

किसी बटन प्रकार की डिफ़ॉल्ट एनिमेशन बदलने के लिए, अपनी `lib/resources/widgets/buttons/buttons.dart` फ़ाइल संशोधित करें:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## स्प्लैश स्टाइल

स्प्लैश इफेक्ट बटन पर दृश्य टच फीडबैक प्रदान करते हैं। उन्हें `ButtonSplashStyle` के माध्यम से कॉन्फ़िगर करें। स्प्लैश स्टाइल को लेयर्ड फीडबैक के लिए एनिमेशन स्टाइल के साथ जोड़ा जा सकता है।

### उपलब्ध स्प्लैश स्टाइल

| स्प्लैश | फैक्ट्री | विवरण |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | टच पॉइंट से स्टैंडर्ड Material रिपल |
| Highlight | `ButtonSplashStyle.highlight()` | रिपल एनिमेशन के बिना सूक्ष्म हाइलाइट |
| Glow | `ButtonSplashStyle.glow()` | टच पॉइंट से नरम चमक |
| Ink | `ButtonSplashStyle.ink()` | तेज़ इंक स्प्लैश, अधिक तेज़ और रिस्पॉन्सिव |
| None | `ButtonSplashStyle.none()` | कोई स्प्लैश इफेक्ट नहीं |
| Custom | `ButtonSplashStyle.custom()` | स्प्लैश फैक्ट्री पर पूर्ण नियंत्रण |

### उदाहरण

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

आप स्प्लैश रंगों और अपारदर्शिता को कस्टमाइज़ कर सकते हैं:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## लोडिंग स्टाइल

एसिंक ऑपरेशन के दौरान दिखाया जाने वाला लोडिंग इंडिकेटर `LoadingStyle` द्वारा नियंत्रित होता है। आप इसे अपनी बटन फ़ाइल में प्रति बटन प्रकार सेट कर सकते हैं।

### Skeletonizer (डिफ़ॉल्ट)

बटन पर एक शिमर स्केलेटन इफेक्ट दिखाता है:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

एक लोडिंग विजेट दिखाता है (डिफ़ॉल्ट रूप से ऐप लोडर):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

लोडिंग के दौरान बटन को दृश्यमान रखता है लेकिन इंटरेक्शन अक्षम करता है:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## फॉर्म सबमिशन

सभी बटन `submitForm` पैरामीटर का समर्थन करते हैं, जो बटन को `NyForm` से जोड़ता है। टैप करने पर, बटन फॉर्म को वैलिडेट करेगा और आपके सफलता हैंडलर को फॉर्म डेटा के साथ कॉल करेगा।

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

`submitForm` पैरामीटर दो मानों के साथ एक रिकॉर्ड स्वीकार करता है:
1. एक `NyFormData` इंस्टेंस (या `String` के रूप में फॉर्म नाम)
2. एक कॉलबैक जो वैलिडेटेड डेटा प्राप्त करता है

डिफ़ॉल्ट रूप से, `showToastError` `true` है, जो फॉर्म वैलिडेशन विफल होने पर एक टोस्ट नोटिफ़िकेशन दिखाता है। एरर को चुपचाप हैंडल करने के लिए इसे `false` पर सेट करें:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

जब `submitForm` कॉलबैक एक `Future` लौटाता है, तो बटन स्वचालित रूप से एसिंक ऑपरेशन पूरा होने तक लोडिंग स्टेट दिखाएगा।

<div id="customizing-buttons"></div>

## बटन कस्टमाइज़ करना

सभी बटन डिफ़ॉल्ट आपके प्रोजेक्ट में `lib/resources/widgets/buttons/buttons.dart` में परिभाषित हैं। प्रत्येक बटन प्रकार की `lib/resources/widgets/buttons/partials/` में एक संबंधित विजेट क्लास है।

### डिफ़ॉल्ट स्टाइल बदलना

बटन की डिफ़ॉल्ट उपस्थिति को संशोधित करने के लिए, `Button` क्लास को संपादित करें:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### बटन विजेट कस्टमाइज़ करना

बटन प्रकार की दृश्य उपस्थिति बदलने के लिए, `lib/resources/widgets/buttons/partials/` में संबंधित विजेट संपादित करें। उदाहरण के लिए, प्राइमरी बटन का बॉर्डर रेडियस या शैडो बदलने के लिए:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### नया बटन प्रकार बनाना

नया बटन प्रकार जोड़ने के लिए:

1. `lib/resources/widgets/buttons/partials/` में `StatefulAppButton` को एक्सटेंड करने वाली एक नई विजेट फ़ाइल बनाएँ।
2. `buildButton` मेथड को इम्प्लीमेंट करें।
3. `Button` क्लास में एक स्टैटिक मेथड जोड़ें।

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

फिर इसे `Button` क्लास में रजिस्टर करें:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## पैरामीटर रेफरेंस

### सामान्य पैरामीटर (सभी बटन प्रकार)

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `text` | `String` | आवश्यक | बटन लेबल टेक्स्ट |
| `onPressed` | `VoidCallback?` | `null` | बटन टैप होने पर कॉलबैक। स्वचालित लोडिंग स्टेट के लिए `Future` लौटाएँ |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | फॉर्म सबमिशन रिकॉर्ड (फॉर्म इंस्टेंस, सफलता कॉलबैक) |
| `onFailure` | `Function(dynamic)?` | `null` | फॉर्म वैलिडेशन विफल होने पर कॉल होता है |
| `showToastError` | `bool` | `true` | फॉर्म वैलिडेशन एरर पर टोस्ट नोटिफ़िकेशन दिखाएँ |
| `width` | `double?` | `null` | बटन चौड़ाई (डिफ़ॉल्ट रूप से पूरी चौड़ाई) |

### प्रकार-विशिष्ट पैरामीटर

#### Button.outlined

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | थीम आउटलाइन रंग | बॉर्डर स्ट्रोक रंग |
| `textColor` | `Color?` | थीम प्राइमरी रंग | टेक्स्ट रंग |

#### Button.textOnly

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | थीम प्राइमरी रंग | टेक्स्ट रंग |

#### Button.icon

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `icon` | `Widget` | आवश्यक | प्रदर्शित करने के लिए आइकन विजेट |
| `color` | `Color?` | थीम प्राइमरी रंग | बैकग्राउंड रंग |

#### Button.gradient

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | प्राइमरी और टर्शियरी रंग | ग्रेडिएंट कलर स्टॉप |

#### Button.rounded

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | थीम प्राइमरी कंटेनर रंग | बैकग्राउंड रंग |
| `borderRadius` | `BorderRadius?` | गोली का आकार (ऊँचाई / 2) | कोने का रेडियस |

#### Button.transparency

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `color` | `Color?` | थीम-अनुकूली | टेक्स्ट रंग |

### ButtonAnimationStyle पैरामीटर

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `duration` | `Duration` | प्रकार अनुसार भिन्न | एनिमेशन अवधि |
| `curve` | `Curve` | प्रकार अनुसार भिन्न | एनिमेशन कर्व |
| `enableHapticFeedback` | `bool` | प्रकार अनुसार भिन्न | प्रेस पर हैप्टिक फीडबैक ट्रिगर करें |
| `translateY` | `double` | `4.0` | Clickable: वर्टिकल प्रेस दूरी |
| `shadowOffset` | `double` | `4.0` | Clickable: शैडो गहराई |
| `scaleMin` | `double` | `0.92` | Bounce: प्रेस पर न्यूनतम स्केल |
| `pulseScale` | `double` | `1.05` | Pulse: पल्स के दौरान अधिकतम स्केल |
| `squeezeX` | `double` | `0.95` | Squeeze: क्षैतिज संपीड़न |
| `squeezeY` | `double` | `1.05` | Squeeze: लंबवत विस्तार |
| `jellyStrength` | `double` | `0.15` | Jelly: लहर तीव्रता |
| `shineColor` | `Color` | `Colors.white` | Shine: हाइलाइट रंग |
| `shineWidth` | `double` | `0.3` | Shine: चमक पट्टी की चौड़ाई |
| `rippleScale` | `double` | `2.0` | Ripple: विस्तार स्केल |
| `morphRadius` | `double` | `24.0` | Morph: लक्ष्य बॉर्डर रेडियस |
| `shakeOffset` | `double` | `8.0` | Shake: क्षैतिज विस्थापन |
| `shakeCount` | `int` | `3` | Shake: दोलनों की संख्या |

### ButtonSplashStyle पैरामीटर

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | थीम सरफेस रंग | स्प्लैश इफेक्ट रंग |
| `highlightColor` | `Color?` | थीम सरफेस रंग | हाइलाइट इफेक्ट रंग |
| `splashOpacity` | `double` | `0.12` | स्प्लैश की अपारदर्शिता |
| `highlightOpacity` | `double` | `0.06` | हाइलाइट की अपारदर्शिता |
| `borderRadius` | `BorderRadius?` | `null` | स्प्लैश क्लिप रेडियस |
