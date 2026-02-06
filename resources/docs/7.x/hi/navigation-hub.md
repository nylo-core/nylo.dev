# Navigation Hub

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
  - [Navigation Hub बनाना](#creating-a-navigation-hub "Navigation Hub बनाना")
  - [नेविगेशन टैब बनाना](#creating-navigation-tabs "नेविगेशन टैब बनाना")
  - [बॉटम नेविगेशन](#bottom-navigation "बॉटम नेविगेशन")
    - [बॉटम नेव स्टाइल्स](#bottom-nav-styles "बॉटम नेव स्टाइल्स")
    - [कस्टम नेव बार बिल्डर](#custom-nav-bar-builder "कस्टम नेव बार बिल्डर")
  - [टॉप नेविगेशन](#top-navigation "टॉप नेविगेशन")
  - [जर्नी नेविगेशन](#journey-navigation "जर्नी नेविगेशन")
    - [प्रोग्रेस स्टाइल्स](#journey-progress-styles "प्रोग्रेस स्टाइल्स")
    - [बटन स्टाइल्स](#journey-button-styles "बटन स्टाइल्स")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState हेल्पर मेथड्स](#journey-state-helper-methods "JourneyState हेल्पर मेथड्स")
- [टैब के भीतर नेविगेट करना](#navigating-within-a-tab "टैब के भीतर नेविगेट करना")
- [टैब्स](#tabs "टैब्स")
  - [टैब्स में बैज जोड़ना](#adding-badges-to-tabs "टैब्स में बैज जोड़ना")
  - [टैब्स में अलर्ट जोड़ना](#adding-alerts-to-tabs "टैब्स में अलर्ट जोड़ना")
- [स्टेट बनाए रखना](#maintaining-state "स्टेट बनाए रखना")
- [स्टेट एक्शन्स](#state-actions "स्टेट एक्शन्स")
- [लोडिंग स्टाइल](#loading-style "लोडिंग स्टाइल")
- [Navigation Hub बनाना](#creating-a-navigation-hub "Navigation Hub बनाना")

<div id="introduction"></div>

## परिचय

Navigation Hubs एक केंद्रीय स्थान हैं जहाँ आप अपने सभी विजेट्स के लिए नेविगेशन **प्रबंधित** कर सकते हैं।
बॉक्स से बाहर आप सेकंडों में बॉटम, टॉप और जर्नी नेविगेशन लेआउट बना सकते हैं।

आइए **कल्पना** करें कि आपके पास एक ऐप है और आप एक बॉटम नेविगेशन बार जोड़ना चाहते हैं और यूज़र्स को आपके ऐप में विभिन्न टैब्स के बीच नेविगेट करने की अनुमति देना चाहते हैं।

इसे बनाने के लिए आप एक Navigation Hub का उपयोग कर सकते हैं।

आइए जानें कि आप अपने ऐप में Navigation Hub कैसे उपयोग कर सकते हैं।

<div id="basic-usage"></div>

## बेसिक उपयोग

आप नीचे दिए गए कमांड का उपयोग करके एक Navigation Hub बना सकते हैं।

``` bash
metro make:navigation_hub base
```

यह आपकी `resources/pages/` डायरेक्टरी में एक **base_navigation_hub.dart** फ़ाइल बनाएगा।

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layouts:
  /// - [NavigationHubLayout.bottomNav] Bottom navigation
  /// - [NavigationHubLayout.topNav] Top navigation
  /// - [NavigationHubLayout.journey] Journey navigation
  NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
    // backgroundColor: Colors.white,
  );

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// Navigation pages
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
    };
  });
}
```

आप देख सकते हैं कि Navigation Hub में **दो** टैब हैं, Home और Settings।

आप Navigation Hub में NavigationTab's जोड़कर और टैब बना सकते हैं।

सबसे पहले, आपको Metro का उपयोग करके एक नया विजेट बनाना होगा।

``` bash
metro make:stateful_widget create_advert_tab
```

आप एक साथ कई विजेट्स भी बना सकते हैं।

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

फिर, आप नए विजेट को Navigation Hub में जोड़ सकते हैं।

``` dart
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
      2: NavigationTab(
         title: "News",
         page: NewsTab(),
         icon: Icon(Icons.newspaper),
         activeIcon: Icon(Icons.newspaper),
      ),
    };
  });

import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Navigation Hub के साथ और भी **बहुत कुछ** आप कर सकते हैं, आइए कुछ फ़ीचर्स में गहराई से जानें।

<div id="bottom-navigation"></div>

### बॉटम नेविगेशन

आप **layout** को `NavigationHubLayout.bottomNav` पर सेट करके लेआउट को बॉटम नेविगेशन बार में बदल सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

आप निम्नलिखित जैसी प्रॉपर्टीज़ सेट करके बॉटम नेविगेशन बार को कस्टमाइज़ कर सकते हैं:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // bottomNav लेआउट प्रॉपर्टीज़ कस्टमाइज़ करें
    );
```

<div id="bottom-nav-styles"></div>

### बॉटम नेव स्टाइल्स

आप `style` पैरामीटर का उपयोग करके अपनी बॉटम नेविगेशन बार पर प्रीसेट स्टाइल लागू कर सकते हैं।

Nylo कई बिल्ट-इन स्टाइल प्रदान करता है:

- `BottomNavStyle.material()` - डिफ़ॉल्ट Flutter मटीरियल स्टाइल
- `BottomNavStyle.glass()` - iOS 26-स्टाइल फ्रॉस्टेड ग्लास इफ़ेक्ट ब्लर के साथ
- `BottomNavStyle.floating()` - राउंडेड कॉर्नर्स वाला फ्लोटिंग पिल-स्टाइल नेव बार

#### Glass स्टाइल

Glass स्टाइल एक सुंदर फ्रॉस्टेड ग्लास इफ़ेक्ट बनाता है, आधुनिक iOS 26-प्रेरित डिज़ाइन के लिए बिल्कुल सही।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

आप Glass इफ़ेक्ट को कस्टमाइज़ कर सकते हैं:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.glass(
        blur: 15.0,                              // Blur intensity
        opacity: 0.7,                            // Background opacity
        borderRadius: BorderRadius.circular(20), // Rounded corners
        margin: EdgeInsets.all(16),              // Float above the edge
        backgroundColor: Colors.white.withValues(alpha: 0.8),
    ),
)
```

#### Floating स्टाइल

Floating स्टाइल एक पिल-शेप्ड नेव बार बनाता है जो बॉटम एज के ऊपर फ्लोट करता है।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

आप Floating स्टाइल को कस्टमाइज़ कर सकते हैं:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.floating(
        borderRadius: BorderRadius.circular(30),
        margin: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shadow: BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
        ),
        backgroundColor: Colors.white,
    ),
)
```

<div id="custom-nav-bar-builder"></div>

### कस्टम नेव बार बिल्डर

अपनी नेविगेशन बार पर पूर्ण नियंत्रण के लिए, आप `navBarBuilder` पैरामीटर का उपयोग कर सकते हैं।

यह आपको नेविगेशन डेटा प्राप्त करते हुए कोई भी कस्टम विजेट बनाने की अनुमति देता है।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` ऑब्जेक्ट में शामिल हैं:

| प्रॉपर्टी | टाइप | विवरण |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | नेविगेशन बार आइटम्स |
| `currentIndex` | `int` | वर्तमान में चयनित इंडेक्स |
| `onTap` | `ValueChanged<int>` | टैब टैप होने पर कॉलबैक |

यहाँ एक पूरी तरह कस्टम glass नेव बार का उदाहरण है:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **नोट:** `navBarBuilder` का उपयोग करते समय, `style` पैरामीटर को अनदेखा किया जाता है।

<div id="top-navigation"></div>

### टॉप नेविगेशन

आप **layout** को `NavigationHubLayout.topNav` पर सेट करके लेआउट को टॉप नेविगेशन बार में बदल सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

आप निम्नलिखित जैसी प्रॉपर्टीज़ सेट करके टॉप नेविगेशन बार को कस्टमाइज़ कर सकते हैं:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### जर्नी नेविगेशन

आप **layout** को `NavigationHubLayout.journey` पर सेट करके लेआउट को जर्नी नेविगेशन में बदल सकते हैं।

यह ऑनबोर्डिंग फ्लो या मल्टी-स्टेप फॉर्म के लिए बहुत अच्छा है।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

यदि आप जर्नी नेविगेशन लेआउट का उपयोग करना चाहते हैं, तो आपके **विजेट्स** को `JourenyState` का उपयोग करना चाहिए क्योंकि इसमें जर्नी को प्रबंधित करने में मदद के लिए बहुत सारे हेल्पर मेथड्स हैं।

आप नीचे दिए गए कमांड का उपयोग करके एक JourneyState बना सकते हैं।

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
यह आपकी **resources/widgets/** डायरेक्टरी में निम्नलिखित फ़ाइलें बनाएगा `welcome.dart`, `phone_number_step.dart` और `add_photos_step.dart`।

फिर आप नए विजेट्स को Navigation Hub में जोड़ सकते हैं।

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

जर्नी नेविगेशन लेआउट स्वचालित रूप से बैक और नेक्स्ट बटन हैंडल करेगा यदि आप एक `buttonStyle` परिभाषित करते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

आप अपने विजेट्स में लॉजिक को भी कस्टमाइज़ कर सकते हैं।

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class WelcomeStep extends StatefulWidget {
  const WelcomeStep({super.key});

  @override
  createState() => _WelcomeStepState();
}

class _WelcomeStepState extends JourneyState<WelcomeStep> {
  _WelcomeStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeStep', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: onNextPressed,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
  }

  /// जाँचें कि क्या जर्नी अगले स्टेप पर जारी रह सकती है
  /// वैलिडेशन लॉजिक जोड़ने के लिए इस मेथड को ओवरराइड करें
  Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
  }

  /// जारी न रह पाने पर कॉल होता है (canContinue false लौटाता है)
  /// वैलिडेशन विफलताओं को हैंडल करने के लिए इस मेथड को ओवरराइड करें
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// अगले स्टेप पर नेविगेट करने से पहले कॉल होता है
  /// जारी रखने से पहले एक्शन करने के लिए इस मेथड को ओवरराइड करें
  Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
  }

  /// अगले स्टेप पर नेविगेट करने के बाद कॉल होता है
  /// जारी रखने के बाद एक्शन करने के लिए इस मेथड को ओवरराइड करें
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// जर्नी पूरी होने पर कॉल होता है (अंतिम स्टेप पर)
  /// पूर्णता एक्शन करने के लिए इस मेथड को ओवरराइड करें
  Future<void> onComplete() async {}
}
```

आप `JourneyState` क्लास में किसी भी मेथड को ओवरराइड कर सकते हैं।

<div id="journey-progress-styles"></div>

### जर्नी प्रोग्रेस स्टाइल्स

आप `JourneyProgressStyle` क्लास का उपयोग करके प्रोग्रेस इंडिकेटर स्टाइल कस्टमाइज़ कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(
            activeColor: Colors.blue,
            inactiveColor: Colors.grey,
            thickness: 4.0,
        ),
    );
```

आप निम्नलिखित प्रोग्रेस स्टाइल्स का उपयोग कर सकते हैं:

- `JourneyProgressIndicator.none`: कुछ भी रेंडर नहीं करता — किसी विशिष्ट टैब पर इंडिकेटर छिपाने के लिए उपयोगी।
- `JourneyProgressIndicator.linear`: लिनियर प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.dots`: डॉट्स-आधारित प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.numbered`: नंबर्ड स्टेप प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.segments`: सेगमेंटेड प्रोग्रेस बार स्टाइल।
- `JourneyProgressIndicator.circular`: सर्कुलर प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.timeline`: टाइमलाइन-स्टाइल प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.custom`: बिल्डर फ़ंक्शन का उपयोग करके कस्टम प्रोग्रेस इंडिकेटर।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    );
```

आप `JourneyProgressStyle` के भीतर प्रोग्रेस इंडिकेटर की पोज़िशन और पैडिंग को कस्टमाइज़ कर सकते हैं:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.dots(),
            position: ProgressIndicatorPosition.bottom,
            padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        ),
    );
```

आप निम्नलिखित प्रोग्रेस इंडिकेटर पोज़िशन का उपयोग कर सकते हैं:

- `ProgressIndicatorPosition.top`: स्क्रीन के शीर्ष पर प्रोग्रेस इंडिकेटर।
- `ProgressIndicatorPosition.bottom`: स्क्रीन के निचले भाग पर प्रोग्रेस इंडिकेटर।

#### प्रति-टैब प्रोग्रेस स्टाइल ओवरराइड

आप `NavigationTab.journey(progressStyle: ...)` का उपयोग करके अलग-अलग टैब्स पर लेआउट-स्तरीय `progressStyle` को ओवरराइड कर सकते हैं। जिन टैब्स का अपना `progressStyle` नहीं है वे लेआउट डिफ़ॉल्ट इनहेरिट करते हैं। बिना लेआउट डिफ़ॉल्ट और बिना प्रति-टैब स्टाइल वाले टैब प्रोग्रेस इंडिकेटर नहीं दिखाएंगे।

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
            progressStyle: JourneyProgressStyle(
                indicator: JourneyProgressIndicator.numbered(),
            ), // overrides the layout default for this tab only
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

<div id="journey-button-styles">
<br>

### जर्नी बटन स्टाइल्स

यदि आप एक ऑनबोर्डिंग फ्लो बनाना चाहते हैं, तो आप `NavigationHubLayout.journey` क्लास में `buttonStyle` प्रॉपर्टी सेट कर सकते हैं।

बॉक्स से बाहर, आप निम्नलिखित बटन स्टाइल्स का उपयोग कर सकते हैं:

- `JourneyButtonStyle.standard`: कस्टमाइज़ेबल प्रॉपर्टीज़ के साथ स्टैंडर्ड बटन स्टाइल।
- `JourneyButtonStyle.minimal`: केवल आइकन के साथ मिनिमल बटन स्टाइल।
- `JourneyButtonStyle.outlined`: आउटलाइन्ड बटन स्टाइल।
- `JourneyButtonStyle.contained`: कंटेन्ड बटन स्टाइल।
- `JourneyButtonStyle.custom`: बिल्डर फ़ंक्शन का उपयोग करके कस्टम बटन स्टाइल।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` क्लास में जर्नी प्रबंधित करने में मदद के लिए बहुत सारे हेल्पर मेथड्स हैं।

नया `JourneyState` बनाने के लिए, आप नीचे दिए गए कमांड का उपयोग कर सकते हैं।

``` bash
metro make:journey_widget onboard_user_dob
```

या यदि आप एक साथ कई विजेट्स बनाना चाहते हैं, तो आप निम्नलिखित कमांड का उपयोग कर सकते हैं।

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

यह आपकी **resources/widgets/** डायरेक्टरी में निम्नलिखित फ़ाइलें बनाएगा `welcome.dart`, `phone_number_step.dart` और `add_photos_step.dart`।

फिर आप नए विजेट्स को Navigation Hub में जोड़ सकते हैं।

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

यदि हम `WelcomeStep` क्लास को देखें, तो हम देख सकते हैं कि यह `JourneyState` क्लास को एक्सटेंड करती है।

``` dart
...
class _WelcomeTabState extends JourneyState<WelcomeTab> {
  _WelcomeTabState() : super(
      navigationHubState: BaseNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeTab', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
    );
  }
```

आप देखेंगे कि **JourneyState** क्लास पेज की सामग्री बनाने के लिए `buildJourneyContent` का उपयोग करेगी।

यहाँ `buildJourneyContent` मेथड में उपयोग की जा सकने वाली प्रॉपर्टीज़ की सूची है।

| प्रॉपर्टी | टाइप | विवरण |
| --- | --- | --- |
| `content` | `Widget` | पेज की मुख्य सामग्री। |
| `nextButton` | `Widget?` | नेक्स्ट बटन विजेट। |
| `backButton` | `Widget?` | बैक बटन विजेट। |
| `contentPadding` | `EdgeInsetsGeometry` | कंटेंट के लिए पैडिंग। |
| `header` | `Widget?` | हेडर विजेट। |
| `footer` | `Widget?` | फुटर विजेट। |
| `crossAxisAlignment` | `CrossAxisAlignment` | कंटेंट का क्रॉस एक्सिस अलाइनमेंट। |


<div id="journey-state-helper-methods"></div>

### JourneyState हेल्पर मेथड्स

`JourneyState` क्लास में कुछ हेल्पर मेथड्स हैं जिनका उपयोग आप अपनी जर्नी के व्यवहार को कस्टमाइज़ करने के लिए कर सकते हैं।

| मेथड | विवरण |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | नेक्स्ट बटन दबाने पर कॉल होता है। |
| [`onBackPressed()`](#on-back-pressed) | बैक बटन दबाने पर कॉल होता है। |
| [`onComplete()`](#on-complete) | जर्नी पूरी होने पर कॉल होता है (अंतिम स्टेप पर)। |
| [`onBeforeNext()`](#on-before-next) | अगले स्टेप पर नेविगेट करने से पहले कॉल होता है। |
| [`onAfterNext()`](#on-after-next) | अगले स्टेप पर नेविगेट करने के बाद कॉल होता है। |
| [`onCannotContinue()`](#on-cannot-continue) | जर्नी जारी न रह पाने पर कॉल होता है (canContinue false लौटाता है)। |
| [`canContinue()`](#can-continue) | जब यूज़र अगले स्टेप पर नेविगेट करने का प्रयास करता है तब कॉल होता है। |
| [`isFirstStep`](#is-first-step) | यदि यह जर्नी का पहला स्टेप है तो true लौटाता है। |
| [`isLastStep`](#is-last-step) | यदि यह जर्नी का अंतिम स्टेप है तो true लौटाता है। |
| [`goToStep(int index)`](#go-to-step) | अगले स्टेप इंडेक्स पर नेविगेट करें। |
| [`goToNextStep()`](#go-to-next-step) | अगले स्टेप पर नेविगेट करें। |
| [`goToPreviousStep()`](#go-to-previous-step) | पिछले स्टेप पर नेविगेट करें। |
| [`goToFirstStep()`](#go-to-first-step) | पहले स्टेप पर नेविगेट करें। |
| [`goToLastStep()`](#go-to-last-step) | अंतिम स्टेप पर नेविगेट करें। |


<div id="on-next-pressed"></div>

#### onNextPressed

`onNextPressed` मेथड नेक्स्ट बटन दबाने पर कॉल होता है।

उदा. आप इस मेथड का उपयोग जर्नी में अगला स्टेप ट्रिगर करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: onNextPressed, // this will attempt to navigate to the next step
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` मेथड बैक बटन दबाने पर कॉल होता है।

उदा. आप इस मेथड का उपयोग जर्नी में पिछला स्टेप ट्रिगर करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed, // this will attempt to navigate to the previous step
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` मेथड जर्नी पूरी होने पर कॉल होता है (अंतिम स्टेप पर)।

उदा. यदि यह विजेट जर्नी का अंतिम स्टेप है, तो यह मेथड कॉल होगा।

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` मेथड अगले स्टेप पर नेविगेट करने से पहले कॉल होता है।

उदा. यदि आप अगले स्टेप पर नेविगेट करने से पहले डेटा सेव करना चाहते हैं, तो आप यहाँ कर सकते हैं।

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` मेथड true लौटाता है यदि यह जर्नी का पहला स्टेप है।

उदा. यदि यह पहला स्टेप है तो आप इस मेथड का उपयोग बैक बटन को अक्षम करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly( // Example of disabling the back button
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` मेथड true लौटाता है यदि यह जर्नी का अंतिम स्टेप है।

उदा. यदि यह अंतिम स्टेप है तो आप इस मेथड का उपयोग नेक्स्ट बटन को अक्षम करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue", // Example updating the next button text
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` मेथड का उपयोग जर्नी में किसी विशिष्ट स्टेप पर नेविगेट करने के लिए किया जाता है।

उदा. आप इस मेथड का उपयोग जर्नी में किसी विशिष्ट स्टेप पर नेविगेट करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Add photos"
            onPressed: () {
                goToStep(2); // this will navigate to the step with index 2
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` मेथड का उपयोग जर्नी में अगले स्टेप पर नेविगेट करने के लिए किया जाता है।

उदा. आप इस मेथड का उपयोग जर्नी में अगले स्टेप पर नेविगेट करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToNextStep(); // this will navigate to the next step
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` मेथड का उपयोग जर्नी में पिछले स्टेप पर नेविगेट करने के लिए किया जाता है।

उदा. आप इस मेथड का उपयोग जर्नी में पिछले स्टेप पर नेविगेट करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: () {
                goToPreviousStep(); // this will navigate to the previous step
            },
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` मेथड अगले स्टेप पर नेविगेट करने के बाद कॉल होता है।


उदा. यदि आप अगले स्टेप पर नेविगेट करने के बाद कोई एक्शन करना चाहते हैं, तो आप यहाँ कर सकते हैं।

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

`onCannotContinue` मेथड तब कॉल होता है जब जर्नी जारी नहीं रह सकती (canContinue false लौटाता है)।

उदा. यदि आप यूज़र को एरर मैसेज दिखाना चाहते हैं जब वह आवश्यक फ़ील्ड भरे बिना अगले स्टेप पर नेविगेट करने का प्रयास करता है, तो आप यहाँ कर सकते हैं।

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` मेथड तब कॉल होता है जब यूज़र अगले स्टेप पर नेविगेट करने का प्रयास करता है।

उदा. यदि आप अगले स्टेप पर नेविगेट करने से पहले कोई वैलिडेशन करना चाहते हैं, तो आप यहाँ कर सकते हैं।

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` मेथड का उपयोग जर्नी में पहले स्टेप पर नेविगेट करने के लिए किया जाता है।


उदा. आप इस मेथड का उपयोग जर्नी में पहले स्टेप पर नेविगेट करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToFirstStep(); // this will navigate to the first step
            },
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` मेथड का उपयोग जर्नी में अंतिम स्टेप पर नेविगेट करने के लिए किया जाता है।

उदा. आप इस मेथड का उपयोग जर्नी में अंतिम स्टेप पर नेविगेट करने के लिए कर सकते हैं।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToLastStep(); // this will navigate to the last step
            },
        ),
    );
}
```

<div id="navigating-within-a-tab"></div>

## टैब के भीतर विजेट्स पर नेविगेट करना

आप `pushTo` हेल्पर का उपयोग करके टैब के भीतर विजेट्स पर नेविगेट कर सकते हैं।

अपने टैब के अंदर, आप दूसरे विजेट पर नेविगेट करने के लिए `pushTo` हेल्पर का उपयोग कर सकते हैं।

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

आप जिस विजेट पर नेविगेट कर रहे हैं उसे डेटा भी पास कर सकते हैं।

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## टैब्स

टैब्स एक Navigation Hub के मुख्य बिल्डिंग ब्लॉक्स हैं।

आप `NavigationTab` क्लास का उपयोग करके Navigation Hub में टैब्स जोड़ सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab(
                title: "Home",
                page: HomeTab(),
                icon: Icon(Icons.home),
                activeIcon: Icon(Icons.home),
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

ऊपर के उदाहरण में, हमने Navigation Hub में दो टैब्स जोड़े हैं, Home और Settings।

आप `NavigationTab`, `NavigationTab.badge`, और `NavigationTab.alert` जैसे विभिन्न प्रकार के टैब्स उपयोग कर सकते हैं।

- `NavigationTab.badge` क्लास का उपयोग टैब्स में बैज जोड़ने के लिए किया जाता है।
- `NavigationTab.alert` क्लास का उपयोग टैब्स में अलर्ट जोड़ने के लिए किया जाता है।
- `NavigationTab` क्लास का उपयोग सामान्य टैब जोड़ने के लिए किया जाता है।

<div id="adding-badges-to-tabs"></div>

## टैब्स में बैज जोड़ना

हमने आपके टैब्स में बैज जोड़ना आसान बना दिया है।

बैज यूज़र्स को यह दिखाने का एक शानदार तरीका है कि किसी टैब में कुछ नया है।

उदाहरण, यदि आपके पास एक चैट ऐप है, तो आप चैट टैब में अपठित संदेशों की संख्या दिखा सकते हैं।

किसी टैब में बैज जोड़ने के लिए, आप `NavigationTab.badge` क्लास का उपयोग कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

ऊपर के उदाहरण में, हमने 10 की प्रारंभिक गणना के साथ Chat टैब में एक बैज जोड़ा है।

आप प्रोग्रामैटिक रूप से बैज काउंट भी अपडेट कर सकते हैं।

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

डिफ़ॉल्ट रूप से, बैज काउंट याद रखा जाएगा। यदि आप प्रत्येक सेशन में बैज काउंट **क्लियर** करना चाहते हैं, तो आप `rememberCount` को `false` पर सेट कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
                rememberCount: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

<div id="adding-alerts-to-tabs"></div>

## टैब्स में अलर्ट जोड़ना

आप अपने टैब्स में अलर्ट जोड़ सकते हैं।

कभी-कभी आप बैज काउंट नहीं दिखाना चाहते, लेकिन आप यूज़र को एक अलर्ट दिखाना चाहते हैं।

किसी टैब में अलर्ट जोड़ने के लिए, आप `NavigationTab.alert` क्लास का उपयोग कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.alert(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                alertColor: Colors.red,
                alertEnabled: true,
                rememberAlert: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

यह लाल रंग के साथ Chat टैब में एक अलर्ट जोड़ देगा।

आप प्रोग्रामैटिक रूप से अलर्ट भी अपडेट कर सकते हैं।

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="maintaining-state"></div>

## स्टेट बनाए रखना

डिफ़ॉल्ट रूप से, Navigation Hub का स्टेट बनाए रखा जाता है।

इसका मतलब है कि जब आप किसी टैब पर नेविगेट करते हैं, तो टैब का स्टेट संरक्षित रहता है।

यदि आप हर बार जब आप उस पर नेविगेट करें तो टैब का स्टेट क्लियर करना चाहते हैं, तो आप `maintainState` को `false` पर सेट कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## स्टेट एक्शन्स

स्टेट एक्शन्स आपके ऐप में कहीं से भी Navigation Hub के साथ इंटरैक्ट करने का एक तरीका है।

यहाँ कुछ स्टेट एक्शन्स हैं जिनका आप उपयोग कर सकते हैं:

``` dart
  /// Reset the tab
  /// E.g. MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Update the badge count
  /// E.g. MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Increment the badge count
  /// E.g. MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Clear the badge count
  /// E.g. MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

स्टेट एक्शन का उपयोग करने के लिए, आप निम्नलिखित कर सकते हैं:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## लोडिंग स्टाइल

बॉक्स से बाहर, Navigation Hub टैब लोड होते समय आपका **डिफ़ॉल्ट** लोडिंग विजेट (resources/widgets/loader_widget.dart) दिखाएगा।

आप लोडिंग स्टाइल अपडेट करने के लिए `loadingStyle` कस्टमाइज़ कर सकते हैं।

यहाँ विभिन्न लोडिंग स्टाइल्स की तालिका है जिनका आप उपयोग कर सकते हैं:
// normal, skeletonizer, none

| स्टाइल | विवरण |
| --- | --- |
| normal | डिफ़ॉल्ट लोडिंग स्टाइल |
| skeletonizer | स्केलेटन लोडिंग स्टाइल |
| none | कोई लोडिंग स्टाइल नहीं |

आप लोडिंग स्टाइल इस तरह बदल सकते हैं:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

यदि आप किसी स्टाइल में लोडिंग विजेट अपडेट करना चाहते हैं, तो आप `LoadingStyle` में एक `child` पास कर सकते हैं।

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

अब, जब टैब लोड हो रहा होगा, तो "Loading..." टेक्स्ट दिखाया जाएगा।

नीचे उदाहरण:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
     _BaseNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab(
          title: "Home",
          page: HomeTab(),
          icon: Icon(Icons.home),
          activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab(
          title: "Settings",
          page: SettingsTab(),
          icon: Icon(Icons.settings),
          activeIcon: Icon(Icons.settings),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Navigation Hub बनाना

Navigation Hub बनाने के लिए, आप [Metro](/docs/{{$version}}/metro) का उपयोग कर सकते हैं, नीचे दिए गए कमांड का उपयोग करें।

``` bash
metro make:navigation_hub base
```

यह आपकी `resources/pages/` डायरेक्टरी में एक base_navigation_hub.dart फ़ाइल बनाएगा और Navigation Hub को आपकी `routes/router.dart` फ़ाइल में जोड़ देगा।
