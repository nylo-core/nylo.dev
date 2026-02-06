# Navigation Hub

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
  - [Navigation Hub बनाना](#creating-a-navigation-hub "Navigation Hub बनाना")
  - [नेविगेशन टैब्स बनाना](#creating-navigation-tabs "नेविगेशन टैब्स बनाना")
  - [बॉटम नेविगेशन](#bottom-navigation "बॉटम नेविगेशन")
    - [कस्टम Nav Bar बिल्डर](#custom-nav-bar-builder "कस्टम Nav Bar बिल्डर")
  - [टॉप नेविगेशन](#top-navigation "टॉप नेविगेशन")
  - [Journey नेविगेशन](#journey-navigation "Journey नेविगेशन")
    - [प्रोग्रेस स्टाइल्स](#journey-progress-styles "प्रोग्रेस स्टाइल्स")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState हेल्पर मेथड्स](#journey-state-helper-methods "JourneyState हेल्पर मेथड्स")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [टैब के भीतर नेविगेट करना](#navigating-within-a-tab "टैब के भीतर नेविगेट करना")
- [टैब्स](#tabs "टैब्स")
  - [टैब्स में बैज जोड़ना](#adding-badges-to-tabs "टैब्स में बैज जोड़ना")
  - [टैब्स में अलर्ट जोड़ना](#adding-alerts-to-tabs "टैब्स में अलर्ट जोड़ना")
- [प्रारंभिक इंडेक्स](#initial-index "प्रारंभिक इंडेक्स")
- [स्टेट बनाए रखना](#maintaining-state "स्टेट बनाए रखना")
- [onTap](#on-tap "onTap")
- [स्टेट एक्शन्स](#state-actions "स्टेट एक्शन्स")
- [लोडिंग स्टाइल](#loading-style "लोडिंग स्टाइल")

<div id="introduction"></div>

## परिचय

Navigation Hubs एक केंद्रीय स्थान हैं जहाँ आप अपने सभी विजेट्स के लिए नेविगेशन **प्रबंधित** कर सकते हैं।
बिल्ट-इन सुविधा के साथ आप सेकंडों में bottom, top और journey नेविगेशन लेआउट बना सकते हैं।

आइए **कल्पना** करें कि आपके पास एक ऐप है और आप एक bottom navigation bar जोड़ना चाहते हैं ताकि यूज़र्स आपके ऐप में विभिन्न टैब्स के बीच नेविगेट कर सकें।

इसे बनाने के लिए आप एक Navigation Hub का उपयोग कर सकते हैं।

आइए जानें कि आप अपने ऐप में Navigation Hub कैसे उपयोग कर सकते हैं।

<div id="basic-usage"></div>

## बेसिक उपयोग

आप नीचे दिए गए कमांड का उपयोग करके एक Navigation Hub बना सकते हैं।

``` bash
metro make:navigation_hub base
```

यह कमांड आपको एक interactive setup से गुज़ारेगा:

1. **लेआउट टाइप चुनें** - `navigation_tabs` (bottom navigation) या `journey_states` (sequential flow) में से चुनें।
2. **टैब/स्टेट के नाम दर्ज करें** - अपने टैब्स या journey states के लिए कॉमा-सेपरेटेड नाम दें।

यह आपकी `resources/pages/navigation_hubs/base/` डायरेक्टरी में फ़ाइलें बनाएगा:
- `base_navigation_hub.dart` - मुख्य hub विजेट
- `tabs/` या `states/` - प्रत्येक टैब या journey state के लिए child विजेट्स

एक जनरेट किया हुआ Navigation Hub कैसा दिखता है:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

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

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

आप देख सकते हैं कि इस Navigation Hub में **दो** टैब हैं, Home और Settings।

`layout` मेथड hub के लिए लेआउट टाइप रिटर्न करता है। इसे एक `BuildContext` मिलता है ताकि आप अपना लेआउट कॉन्फ़िगर करते समय theme data और media queries एक्सेस कर सकें।

आप Navigation Hub में `NavigationTab` जोड़कर और टैब्स बना सकते हैं।

सबसे पहले, आपको Metro का उपयोग करके एक नया विजेट बनाना होगा।

``` bash
metro make:stateful_widget news_tab
```

आप एक साथ कई विजेट्स भी बना सकते हैं।

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

फिर, आप नए विजेट को Navigation Hub में जोड़ सकते हैं।

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Navigation Hub को उपयोग करने के लिए, इसे अपने राउटर में initial route के रूप में जोड़ें:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Navigation Hub के साथ और भी **बहुत कुछ** किया जा सकता है, आइए कुछ फ़ीचर्स को विस्तार से जानें।

<div id="bottom-navigation"></div>

### बॉटम नेविगेशन

आप `layout` मेथड से `NavigationHubLayout.bottomNav` रिटर्न करके लेआउट को bottom navigation bar में सेट कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

आप निम्नलिखित प्रॉपर्टीज़ सेट करके bottom navigation bar को कस्टमाइज़ कर सकते हैं:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

आप `style` पैरामीटर का उपयोग करके अपनी bottom navigation bar पर प्रीसेट स्टाइल लागू कर सकते हैं।

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### कस्टम Nav Bar बिल्डर

अपनी navigation bar पर पूर्ण नियंत्रण के लिए, आप `navBarBuilder` पैरामीटर का उपयोग कर सकते हैं।

यह आपको नेविगेशन डेटा प्राप्त करते हुए कोई भी कस्टम विजेट बनाने की अनुमति देता है।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` ऑब्जेक्ट में निम्नलिखित शामिल हैं:

| प्रॉपर्टी | टाइप | विवरण |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | नेविगेशन बार आइटम्स |
| `currentIndex` | `int` | वर्तमान में चयनित इंडेक्स |
| `onTap` | `ValueChanged<int>` | टैब टैप होने पर कॉलबैक |

यहाँ एक पूरी तरह कस्टम glass nav bar का उदाहरण है:

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

आप `layout` मेथड से `NavigationHubLayout.topNav` रिटर्न करके लेआउट को top navigation bar में बदल सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

आप निम्नलिखित प्रॉपर्टीज़ सेट करके top navigation bar को कस्टमाइज़ कर सकते हैं:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Journey नेविगेशन

आप `layout` मेथड से `NavigationHubLayout.journey` रिटर्न करके लेआउट को journey navigation में बदल सकते हैं।

यह onboarding flows या multi-step forms के लिए बहुत उपयुक्त है।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

आप journey लेआउट के लिए `backgroundGradient` भी सेट कर सकते हैं:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **नोट:** जब `backgroundGradient` सेट होता है, तो यह `backgroundColor` से ऊपर प्राथमिकता लेता है।

यदि आप journey navigation लेआउट का उपयोग करना चाहते हैं, तो आपके **विजेट्स** को `JourneyState` का उपयोग करना चाहिए क्योंकि इसमें journey प्रबंधित करने के लिए बहुत से हेल्पर मेथड्स हैं।

आप `make:navigation_hub` कमांड के साथ `journey_states` लेआउट चुनकर पूरी journey बना सकते हैं:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

यह hub और सभी journey state विजेट्स `resources/pages/navigation_hubs/onboarding/states/` के अंदर बनाएगा।

या आप अलग-अलग journey विजेट्स इस तरह बना सकते हैं:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

फिर आप नए विजेट्स को Navigation Hub में जोड़ सकते हैं।

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Journey प्रोग्रेस स्टाइल्स

आप `JourneyProgressStyle` क्लास का उपयोग करके प्रोग्रेस इंडिकेटर स्टाइल कस्टमाइज़ कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

आप निम्नलिखित प्रोग्रेस इंडिकेटर्स का उपयोग कर सकते हैं:

- `JourneyProgressIndicator.none()`: कुछ भी रेंडर नहीं करता - किसी विशिष्ट टैब पर इंडिकेटर छिपाने के लिए उपयोगी।
- `JourneyProgressIndicator.linear()`: लिनियर प्रोग्रेस बार।
- `JourneyProgressIndicator.dots()`: डॉट्स-आधारित प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.numbered()`: नंबर्ड स्टेप प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.segments()`: सेगमेंटेड प्रोग्रेस बार स्टाइल।
- `JourneyProgressIndicator.circular()`: सर्कुलर प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.timeline()`: टाइमलाइन-स्टाइल प्रोग्रेस इंडिकेटर।
- `JourneyProgressIndicator.custom()`: बिल्डर फ़ंक्शन का उपयोग करके कस्टम प्रोग्रेस इंडिकेटर।

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

आप `JourneyProgressStyle` के भीतर प्रोग्रेस इंडिकेटर की पोज़िशन और पैडिंग को कस्टमाइज़ कर सकते हैं:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
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

आप `NavigationTab.journey(progressStyle: ...)` का उपयोग करके अलग-अलग टैब्स पर लेआउट-स्तरीय `progressStyle` को ओवरराइड कर सकते हैं। जिन टैब्स का अपना `progressStyle` नहीं है, वे लेआउट डिफ़ॉल्ट इनहेरिट करते हैं। बिना लेआउट डिफ़ॉल्ट और बिना प्रति-टैब स्टाइल वाले टैब्स प्रोग्रेस इंडिकेटर नहीं दिखाएंगे।

``` dart
_MyNavigationHubState() : super(() => {
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
});
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` क्लास `NyState` को journey-विशिष्ट कार्यक्षमता के साथ extend करती है ताकि onboarding flows और multi-step journeys बनाना आसान हो।

नया `JourneyState` बनाने के लिए, आप नीचे दिए गए कमांड का उपयोग कर सकते हैं।

``` bash
metro make:journey_widget onboard_user_dob
```

या यदि आप एक साथ कई विजेट्स बनाना चाहते हैं, तो आप निम्नलिखित कमांड का उपयोग कर सकते हैं।

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

एक जनरेट किया हुआ JourneyState विजेट कैसा दिखता है:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

आप देखेंगे कि **JourneyState** क्लास आगे नेविगेट करने के लिए `nextStep` और पीछे जाने के लिए `onBackPressed` का उपयोग करती है।

`nextStep` मेथड पूरे validation lifecycle से गुज़रता है: `canContinue()` -> `onBeforeNext()` -> नेविगेट (या अंतिम स्टेप पर `onComplete()`) -> `onAfterNext()`।

आप `buildJourneyContent` का उपयोग करके optional navigation buttons के साथ एक structured layout भी बना सकते हैं:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

यहाँ `buildJourneyContent` मेथड में उपयोग की जा सकने वाली प्रॉपर्टीज़ हैं।

| प्रॉपर्टी | टाइप | विवरण |
| --- | --- | --- |
| `content` | `Widget` | पेज की मुख्य सामग्री। |
| `nextButton` | `Widget?` | नेक्स्ट बटन विजेट। |
| `backButton` | `Widget?` | बैक बटन विजेट। |
| `contentPadding` | `EdgeInsetsGeometry` | कंटेंट के लिए पैडिंग। |
| `header` | `Widget?` | हेडर विजेट। |
| `footer` | `Widget?` | फुटर विजेट। |
| `crossAxisAlignment` | `CrossAxisAlignment` | कंटेंट का cross axis alignment। |

<div id="journey-state-helper-methods"></div>

### JourneyState हेल्पर मेथड्स

`JourneyState` क्लास में हेल्पर मेथड्स और प्रॉपर्टीज़ हैं जिनका उपयोग आप अपनी journey के व्यवहार को कस्टमाइज़ करने के लिए कर सकते हैं।

| मेथड / प्रॉपर्टी | विवरण |
| --- | --- |
| [`nextStep()`](#next-step) | validation के साथ अगले स्टेप पर नेविगेट करें। `Future<bool>` रिटर्न करता है। |
| [`previousStep()`](#previous-step) | पिछले स्टेप पर नेविगेट करें। `Future<bool>` रिटर्न करता है। |
| [`onBackPressed()`](#on-back-pressed) | पिछले स्टेप पर नेविगेट करने के लिए सरल हेल्पर। |
| [`onComplete()`](#on-complete) | journey पूरी होने पर कॉल होता है (अंतिम स्टेप पर)। |
| [`onBeforeNext()`](#on-before-next) | अगले स्टेप पर नेविगेट करने से पहले कॉल होता है। |
| [`onAfterNext()`](#on-after-next) | अगले स्टेप पर नेविगेट करने के बाद कॉल होता है। |
| [`canContinue()`](#can-continue) | अगले स्टेप पर नेविगेट करने से पहले validation चेक। |
| [`isFirstStep`](#is-first-step) | यदि यह journey का पहला स्टेप है तो true रिटर्न करता है। |
| [`isLastStep`](#is-last-step) | यदि यह journey का अंतिम स्टेप है तो true रिटर्न करता है। |
| [`currentStep`](#current-step) | वर्तमान स्टेप इंडेक्स (0-based) रिटर्न करता है। |
| [`totalSteps`](#total-steps) | कुल स्टेप्स की संख्या रिटर्न करता है। |
| [`completionPercentage`](#completion-percentage) | पूर्णता प्रतिशत (0.0 से 1.0) रिटर्न करता है। |
| [`goToStep(int index)`](#go-to-step) | इंडेक्स द्वारा किसी विशिष्ट स्टेप पर सीधे जाएं। |
| [`goToNextStep()`](#go-to-next-step) | अगले स्टेप पर जाएं (बिना validation)। |
| [`goToPreviousStep()`](#go-to-previous-step) | पिछले स्टेप पर जाएं (बिना validation)। |
| [`goToFirstStep()`](#go-to-first-step) | पहले स्टेप पर जाएं। |
| [`goToLastStep()`](#go-to-last-step) | अंतिम स्टेप पर जाएं। |
| [`exitJourney()`](#exit-journey) | root navigator को pop करके journey से बाहर निकलें। |
| [`resetCurrentStep()`](#reset-current-step) | वर्तमान स्टेप की state रीसेट करें। |
| [`onJourneyComplete`](#on-journey-complete) | journey पूरी होने पर कॉलबैक (अंतिम स्टेप में ओवरराइड करें)। |
| [`buildJourneyPage()`](#build-journey-page) | Scaffold के साथ फुल-स्क्रीन journey पेज बनाएं। |


<div id="next-step"></div>

#### nextStep

`nextStep` मेथड पूर्ण validation के साथ अगले स्टेप पर नेविगेट करता है। यह lifecycle से गुज़रता है: `canContinue()` -> `onBeforeNext()` -> नेविगेट या `onComplete()` -> `onAfterNext()`।

आप `force: true` पास करके validation बायपास करके सीधे नेविगेट कर सकते हैं।

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
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

validation स्किप करने के लिए:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

`previousStep` मेथड पिछले स्टेप पर नेविगेट करता है। सफल होने पर `true` रिटर्न करता है, पहले स्टेप पर पहले से होने पर `false`।

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` मेथड एक सरल हेल्पर है जो internally `previousStep()` कॉल करता है।

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
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` मेथड तब कॉल होता है जब अंतिम स्टेप पर `nextStep()` ट्रिगर होता है (validation पास होने के बाद)।

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` मेथड अगले स्टेप पर नेविगेट करने से पहले कॉल होता है।

उदा. यदि आप अगले स्टेप पर जाने से पहले डेटा सेव करना चाहते हैं, तो आप यहाँ कर सकते हैं।

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` मेथड अगले स्टेप पर नेविगेट करने के बाद कॉल होता है।

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` मेथड तब कॉल होता है जब `nextStep()` ट्रिगर होता है। नेविगेशन रोकने के लिए `false` रिटर्न करें।

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` प्रॉपर्टी true रिटर्न करती है यदि यह journey का पहला स्टेप है।

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` प्रॉपर्टी true रिटर्न करती है यदि यह journey का अंतिम स्टेप है।

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

`currentStep` प्रॉपर्टी वर्तमान स्टेप इंडेक्स (0-based) रिटर्न करती है।

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

`totalSteps` प्रॉपर्टी journey में कुल स्टेप्स की संख्या रिटर्न करती है।

<div id="completion-percentage"></div>

#### completionPercentage

`completionPercentage` प्रॉपर्टी पूर्णता प्रतिशत 0.0 से 1.0 के बीच एक वैल्यू के रूप में रिटर्न करती है।

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` मेथड इंडेक्स द्वारा सीधे किसी विशिष्ट स्टेप पर ले जाता है। यह validation ट्रिगर **नहीं** करता।

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` मेथड बिना validation के अगले स्टेप पर ले जाता है। यदि पहले से अंतिम स्टेप पर हैं, तो कुछ नहीं करता।

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` मेथड बिना validation के पिछले स्टेप पर ले जाता है। यदि पहले से पहले स्टेप पर हैं, तो कुछ नहीं करता।

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` मेथड पहले स्टेप पर ले जाता है।

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` मेथड अंतिम स्टेप पर ले जाता है।

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

`exitJourney` मेथड root navigator को pop करके journey से बाहर निकालता है।

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

`resetCurrentStep` मेथड वर्तमान स्टेप की state रीसेट करता है।

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

`onJourneyComplete` getter को आपकी journey के **अंतिम स्टेप** में ओवरराइड किया जा सकता है ताकि यह परिभाषित किया जा सके कि यूज़र flow पूरा करने पर क्या होगा।

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

`buildJourneyPage` मेथड `Scaffold` और `SafeArea` में wrapped एक फुल-स्क्रीन journey पेज बनाता है।

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| प्रॉपर्टी | टाइप | विवरण |
| --- | --- | --- |
| `content` | `Widget` | पेज की मुख्य सामग्री। |
| `nextButton` | `Widget?` | नेक्स्ट बटन विजेट। |
| `backButton` | `Widget?` | बैक बटन विजेट। |
| `contentPadding` | `EdgeInsetsGeometry` | कंटेंट के लिए पैडिंग। |
| `header` | `Widget?` | हेडर विजेट। |
| `footer` | `Widget?` | फुटर विजेट। |
| `backgroundColor` | `Color?` | Scaffold का बैकग्राउंड कलर। |
| `appBar` | `Widget?` | एक optional AppBar विजेट। |
| `crossAxisAlignment` | `CrossAxisAlignment` | कंटेंट का cross axis alignment। |

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

टैब्स एक Navigation Hub के मुख्य building blocks हैं।

आप `NavigationTab` क्लास और इसके named constructors का उपयोग करके Navigation Hub में टैब्स जोड़ सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

ऊपर के उदाहरण में, हमने Navigation Hub में दो टैब्स जोड़े हैं, Home और Settings।

आप विभिन्न प्रकार के टैब्स का उपयोग कर सकते हैं:

- `NavigationTab.tab()` - एक स्टैंडर्ड नेविगेशन टैब।
- `NavigationTab.badge()` - badge count वाला टैब।
- `NavigationTab.alert()` - alert indicator वाला टैब।
- `NavigationTab.journey()` - journey navigation लेआउट के लिए टैब।

<div id="adding-badges-to-tabs"></div>

## टैब्स में बैज जोड़ना

हमने आपके टैब्स में badges जोड़ना बहुत आसान बना दिया है।

Badges यूज़र्स को यह दिखाने का एक शानदार तरीका है कि किसी टैब में कुछ नया है।

उदाहरण, यदि आपके पास एक चैट ऐप है, तो आप चैट टैब में अपठित संदेशों की संख्या दिखा सकते हैं।

किसी टैब में badge जोड़ने के लिए, आप `NavigationTab.badge` constructor का उपयोग कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

ऊपर के उदाहरण में, हमने 10 की प्रारंभिक गणना के साथ Chat टैब में एक badge जोड़ा है।

आप प्रोग्रामैटिक रूप से badge count भी अपडेट कर सकते हैं।

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

डिफ़ॉल्ट रूप से, badge count याद रखा जाएगा। यदि आप प्रत्येक session में badge count **क्लियर** करना चाहते हैं, तो आप `rememberCount` को `false` सेट कर सकते हैं।

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## टैब्स में अलर्ट जोड़ना

आप अपने टैब्स में alerts जोड़ सकते हैं।

कभी-कभी आप badge count नहीं दिखाना चाहते, लेकिन आप यूज़र को एक alert indicator दिखाना चाहते हैं।

किसी टैब में alert जोड़ने के लिए, आप `NavigationTab.alert` constructor का उपयोग कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

यह लाल रंग के साथ Chat टैब में एक alert जोड़ देगा।

आप प्रोग्रामैटिक रूप से alert भी अपडेट कर सकते हैं।

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## प्रारंभिक इंडेक्स

डिफ़ॉल्ट रूप से, Navigation Hub पहले टैब (index 0) से शुरू होता है। आप `initialIndex` getter को ओवरराइड करके इसे बदल सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## स्टेट बनाए रखना

डिफ़ॉल्ट रूप से, Navigation Hub की state बनाए रखी जाती है।

इसका मतलब है कि जब आप किसी टैब पर नेविगेट करते हैं, तो उस टैब की state संरक्षित रहती है।

यदि आप हर बार टैब पर जाने पर उसकी state क्लियर करना चाहते हैं, तो आप `maintainState` को `false` सेट कर सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

आप `onTap` मेथड को ओवरराइड करके टैब टैप होने पर कस्टम लॉजिक जोड़ सकते हैं।

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## स्टेट एक्शन्स

State actions आपके ऐप में कहीं से भी Navigation Hub के साथ interact करने का एक तरीका है।

यहाँ उपलब्ध state actions हैं:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

State action का उपयोग करने के लिए:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## लोडिंग स्टाइल

बिल्ट-इन सुविधा के साथ, Navigation Hub टैब लोड होते समय आपका **डिफ़ॉल्ट** loading Widget (resources/widgets/loader_widget.dart) दिखाएगा।

आप loading style अपडेट करने के लिए `loadingStyle` कस्टमाइज़ कर सकते हैं।

| स्टाइल | विवरण |
| --- | --- |
| normal | डिफ़ॉल्ट loading स्टाइल |
| skeletonizer | Skeleton loading स्टाइल |
| none | कोई loading स्टाइल नहीं |

आप loading style इस तरह बदल सकते हैं:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

यदि आप किसी style में loading Widget अपडेट करना चाहते हैं, तो आप `LoadingStyle` में एक `child` पास कर सकते हैं।

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
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
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

यह कमांड आपको एक interactive setup से गुज़ारेगा जहाँ आप लेआउट टाइप चुन सकते हैं और अपने टैब्स या journey states परिभाषित कर सकते हैं।

यह आपकी `resources/pages/navigation_hubs/base/` डायरेक्टरी में एक `base_navigation_hub.dart` फ़ाइल बनाएगा, जिसमें child विजेट्स `tabs/` या `states/` subfolder में व्यवस्थित होंगे।
