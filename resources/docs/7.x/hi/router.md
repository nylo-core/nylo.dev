# राउटर

---

<a name="section-1"></a>

- [परिचय](#introduction "परिचय")
- बेसिक्स
  - [रूट्स जोड़ना](#adding-routes "रूट्स जोड़ना")
  - [पेजों पर नेविगेट करना](#navigating-to-pages "पेजों पर नेविगेट करना")
  - [इनिशियल रूट](#initial-route "इनिशियल रूट")
  - [प्रीव्यू रूट](#preview-route "प्रीव्यू रूट")
  - [ऑथेंटिकेटेड रूट](#authenticated-route "ऑथेंटिकेटेड रूट")
  - [अज्ञात रूट](#unknown-route "अज्ञात रूट")
- दूसरे पेज पर डेटा भेजना
  - [दूसरे पेज पर डेटा पास करना](#passing-data-to-another-page "दूसरे पेज पर डेटा पास करना")
- नेविगेशन
  - [नेविगेशन प्रकार](#navigation-types "नेविगेशन प्रकार")
  - [वापस नेविगेट करना](#navigating-back "वापस नेविगेट करना")
  - [कंडीशनल नेविगेशन](#conditional-navigation "कंडीशनल नेविगेशन")
  - [पेज ट्रांज़िशन](#page-transitions "पेज ट्रांज़िशन")
  - [रूट हिस्ट्री](#route-history "रूट हिस्ट्री")
  - [रूट स्टैक अपडेट करें](#update-route-stack "रूट स्टैक अपडेट करें")
- रूट पैरामीटर्स
  - [रूट पैरामीटर्स का उपयोग](#route-parameters "रूट पैरामीटर्स")
  - [क्वेरी पैरामीटर्स](#query-parameters "क्वेरी पैरामीटर्स")
- रूट गार्ड्स
  - [रूट गार्ड्स बनाना](#route-guards "रूट गार्ड्स")
  - [NyRouteGuard लाइफ़साइकिल](#nyroute-guard-lifecycle "NyRouteGuard लाइफ़साइकिल")
  - [गार्ड हेल्पर मेथड्स](#guard-helper-methods "गार्ड हेल्पर मेथड्स")
  - [पैरामीटराइज़्ड गार्ड्स](#parameterized-guards "पैरामीटराइज़्ड गार्ड्स")
  - [गार्ड स्टैक्स](#guard-stacks "गार्ड स्टैक्स")
  - [कंडीशनल गार्ड्स](#conditional-guards "कंडीशनल गार्ड्स")
- रूट ग्रुप्स
  - [रूट ग्रुप्स](#route-groups "रूट ग्रुप्स")
- [डीप लिंकिंग](#deep-linking "डीप लिंकिंग")
- [एडवांस्ड](#advanced "एडवांस्ड")



<div id="introduction"></div>

## परिचय

रूट्स आपको अपने ऐप में विभिन्न पेज परिभाषित करने और उनके बीच नेविगेट करने की अनुमति देते हैं।

रूट्स का उपयोग तब करें जब आपको:
- अपने ऐप में उपलब्ध पेज परिभाषित करने हों
- यूज़र्स को स्क्रीनों के बीच नेविगेट करना हो
- पेजों को ऑथेंटिकेशन के पीछे सुरक्षित करना हो
- एक पेज से दूसरे पेज पर डेटा पास करना हो
- URL से डीप लिंक्स हैंडल करने हों

आप `lib/routes/router.dart` फ़ाइल के अंदर रूट्स जोड़ सकते हैं।

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **सुझाव:** आप अपने रूट्स मैन्युअली बना सकते हैं या उन्हें स्वचालित रूप से बनाने के लिए <a href="/docs/{{ $version }}/metro">Metro</a> CLI टूल का उपयोग कर सकते हैं।

यहाँ Metro का उपयोग करके एक 'account' पेज बनाने का उदाहरण है।

``` bash
metro make:page account_page
```

``` dart
// Adds your new route automatically to /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

आपको एक व्यू से दूसरे में डेटा पास करने की भी आवश्यकता हो सकती है। {{ config('app.name') }} में, यह `NyStatefulWidget` (रूट डेटा एक्सेस के साथ एक स्टेटफुल विजेट) का उपयोग करके संभव है। हम इसे विस्तार से समझाने के लिए गहराई में जाएँगे।


<div id="adding-routes"></div>

## रूट्स जोड़ना

यह आपके प्रोजेक्ट में नए रूट्स जोड़ने का सबसे आसान तरीका है।

नया पेज बनाने के लिए नीचे दिया गया कमांड चलाएँ।

```bash
metro make:page profile_page
```

ऊपर दिया गया कमांड चलाने के बाद, यह `ProfilePage` नाम का एक नया Widget बनाएगा और इसे आपकी `resources/pages/` डायरेक्टरी में जोड़ देगा।
यह नए रूट को आपकी `lib/routes/router.dart` फ़ाइल में भी जोड़ देगा।

फ़ाइल: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## पेजों पर नेविगेट करना

आप `routeTo` हेल्पर का उपयोग करके नए पेजों पर नेविगेट कर सकते हैं।

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## इनिशियल रूट

अपने राउटर्स में, आप `.initialRoute()` मेथड का उपयोग करके पहले लोड होने वाले पेज को परिभाषित कर सकते हैं।

एक बार जब आपने इनिशियल रूट सेट कर दिया, तो ऐप खोलने पर यह पहला पेज होगा जो लोड होगा।

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### कंडीशनल इनिशियल रूट

आप `when` पैरामीटर का उपयोग करके कंडीशनल इनिशियल रूट भी सेट कर सकते हैं:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### इनिशियल रूट पर नेविगेट करें

ऐप के इनिशियल रूट पर नेविगेट करने के लिए `routeToInitial()` का उपयोग करें:

``` dart
void _goHome() {
    routeToInitial();
}
```

यह `.initialRoute()` से चिह्नित रूट पर नेविगेट करेगा और नेविगेशन स्टैक को साफ़ कर देगा।

<div id="preview-route"></div>

## प्रीव्यू रूट

डेवलपमेंट के दौरान, आप अपने इनिशियल रूट को स्थायी रूप से बदले बिना किसी विशिष्ट पेज का तुरंत प्रीव्यू करना चाह सकते हैं। किसी भी रूट को अस्थायी रूप से इनिशियल रूट बनाने के लिए `.previewRoute()` का उपयोग करें:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

`previewRoute()` मेथड:
- किसी भी मौजूदा `initialRoute()` और `authenticatedRoute()` सेटिंग्स को ओवरराइड करता है
- निर्दिष्ट रूट को इनिशियल रूट बनाता है
- डेवलपमेंट के दौरान विशिष्ट पेजों को तुरंत टेस्ट करने के लिए उपयोगी

> **चेतावनी:** अपना ऐप रिलीज़ करने से पहले `.previewRoute()` हटाना याद रखें!

<div id="authenticated-route"></div>

## ऑथेंटिकेटेड रूट

अपने ऐप में, आप एक रूट को परिभाषित कर सकते हैं जो यूज़र के ऑथेंटिकेटेड होने पर इनिशियल रूट बन जाए।
यह स्वचालित रूप से डिफ़ॉल्ट इनिशियल रूट को ओवरराइड करेगा और लॉग इन करने पर यूज़र को दिखाई देने वाला पहला पेज होगा।

सबसे पहले, आपके यूज़र को `Auth.authenticate({...})` हेल्पर का उपयोग करके लॉग इन होना चाहिए।

अब, जब वे ऐप खोलेंगे तो आपने जो रूट परिभाषित किया है वह लॉग आउट होने तक डिफ़ॉल्ट पेज होगा।

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### कंडीशनल ऑथेंटिकेटेड रूट

आप कंडीशनल ऑथेंटिकेटेड रूट भी सेट कर सकते हैं:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### ऑथेंटिकेटेड रूट पर नेविगेट करें

आप `routeToAuthenticatedRoute()` हेल्पर का उपयोग करके ऑथेंटिकेटेड पेज पर नेविगेट कर सकते हैं:

``` dart
routeToAuthenticatedRoute();
```

**यह भी देखें:** [ऑथेंटिकेशन](/docs/{{ $version }}/authentication) यूज़र्स की ऑथेंटिकेशन और सेशन प्रबंधन के विवरण के लिए।


<div id="unknown-route"></div>

## अज्ञात रूट

आप `.unknownRoute()` का उपयोग करके 404/नॉट फ़ाउंड परिदृश्यों को संभालने के लिए एक रूट परिभाषित कर सकते हैं:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

जब कोई यूज़र ऐसे रूट पर नेविगेट करता है जो मौजूद नहीं है, तो उसे अज्ञात रूट पेज दिखाया जाएगा।


<div id="route-guards"></div>

## रूट गार्ड्स

रूट गार्ड्स पेजों को अनधिकृत एक्सेस से सुरक्षित करते हैं। ये नेविगेशन पूरा होने से पहले चलते हैं, जिससे आप शर्तों के आधार पर यूज़र्स को रीडायरेक्ट कर सकते हैं या एक्सेस ब्लॉक कर सकते हैं।

रूट गार्ड्स का उपयोग तब करें जब आपको:
- पेजों को अनऑथेंटिकेटेड यूज़र्स से सुरक्षित करना हो
- एक्सेस की अनुमति देने से पहले परमिशन जाँचनी हो
- शर्तों के आधार पर यूज़र्स को रीडायरेक्ट करना हो (जैसे, ऑनबोर्डिंग पूरा नहीं हुआ)
- पेज व्यू लॉग या ट्रैक करने हों

नया रूट गार्ड बनाने के लिए, नीचे दिया गया कमांड चलाएँ।

``` bash
metro make:route_guard dashboard
```

इसके बाद, नए रूट गार्ड को अपने रूट में जोड़ें।

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Add your guard
    ]
  ); // restricted page
});
```

आप `addRouteGuard` मेथड का उपयोग करके भी रूट गार्ड्स सेट कर सकते हैं:

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // or add multiple guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard लाइफ़साइकिल

v7 में, रूट गार्ड्स `NyRouteGuard` क्लास का उपयोग करते हैं जिसमें तीन लाइफ़साइकिल मेथड्स हैं:

- **`onBefore(RouteContext context)`** - नेविगेशन से पहले कॉल होता है। जारी रखने के लिए `next()`, कहीं और जाने के लिए `redirect()`, या रोकने के लिए `abort()` रिटर्न करें।
- **`onAfter(RouteContext context)`** - रूट पर सफल नेविगेशन के बाद कॉल होता है।

### बेसिक उदाहरण

फ़ाइल: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Perform a check if they can access the page
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Track page view after successful navigation
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

`RouteContext` क्लास नेविगेशन जानकारी तक पहुँच प्रदान करती है:

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `context` | `BuildContext?` | वर्तमान बिल्ड कॉन्टेक्स्ट |
| `data` | `dynamic` | रूट को पास किया गया डेटा |
| `queryParameters` | `Map<String, String>` | URL क्वेरी पैरामीटर्स |
| `routeName` | `String` | रूट का नाम/पाथ |
| `originalRouteName` | `String?` | ट्रांसफ़ॉर्म से पहले का मूल रूट नाम |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## गार्ड हेल्पर मेथड्स

### next()

अगले गार्ड या रूट पर जारी रखें:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

किसी अलग रूट पर रीडायरेक्ट करें:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

`redirect()` मेथड ये पैरामीटर्स स्वीकार करता है:

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `path` | `Object` | रूट पाथ या RouteView |
| `data` | `dynamic` | रूट को पास करने के लिए डेटा |
| `queryParameters` | `Map<String, dynamic>?` | क्वेरी पैरामीटर्स |
| `navigationType` | `NavigationType` | नेविगेशन प्रकार (डिफ़ॉल्ट: pushReplace) |
| `transitionType` | `TransitionType?` | पेज ट्रांज़िशन |
| `onPop` | `Function(dynamic)?` | रूट पॉप होने पर कॉलबैक |

### abort()

रीडायरेक्ट किए बिना नेविगेशन रोकें:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // User stays on current route
  }
  return next();
}
```

### setData()

बाद के गार्ड्स और रूट को पास किया गया डेटा संशोधित करें:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## पैरामीटराइज़्ड गार्ड्स

जब आपको प्रति-रूट गार्ड व्यवहार कॉन्फ़िगर करने की आवश्यकता हो तो `ParameterizedGuard` का उपयोग करें:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Usage:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## गार्ड स्टैक्स

`GuardStack` का उपयोग करके एकाधिक गार्ड्स को एक पुन: प्रयोज्य गार्ड में संयोजित करें:

``` dart
// Create reusable guard combinations
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## कंडीशनल गार्ड्स

किसी प्रेडिकेट के आधार पर सशर्त रूप से गार्ड्स लागू करें:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## दूसरे पेज पर डेटा पास करना

इस सेक्शन में, हम दिखाएँगे कि आप एक विजेट से दूसरे में डेटा कैसे पास कर सकते हैं।

अपने Widget से, `routeTo` हेल्पर का उपयोग करें और वह `data` पास करें जिसे आप नए पेज पर भेजना चाहते हैं।

``` dart
// HomePage Widget
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage Widget (other page)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // or
    print(data()); // Hello World
  };
```

और उदाहरण

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Profile page widget (other page)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## रूट ग्रुप्स

रूट ग्रुप्स संबंधित रूट्स को व्यवस्थित करते हैं और साझा सेटिंग्स लागू करते हैं। ये तब उपयोगी होते हैं जब कई रूट्स को समान गार्ड्स, URL प्रीफ़िक्स, या ट्रांज़िशन स्टाइल की आवश्यकता हो।

रूट ग्रुप्स का उपयोग तब करें जब आपको:
- कई पेजों पर एक ही रूट गार्ड लागू करना हो
- रूट्स के सेट में URL प्रीफ़िक्स जोड़ना हो (जैसे, `/admin/...`)
- संबंधित रूट्स के लिए समान पेज ट्रांज़िशन सेट करना हो

आप नीचे दिए गए उदाहरण की तरह रूट ग्रुप परिभाषित कर सकते हैं।

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### रूट ग्रुप्स के लिए वैकल्पिक सेटिंग्स:

| सेटिंग | टाइप | विवरण |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | ग्रुप के सभी रूट्स पर रूट गार्ड्स लागू करें |
| `prefix` | `String` | ग्रुप के सभी रूट पाथ में प्रीफ़िक्स जोड़ें |
| `transition_type` | `TransitionType` | ग्रुप के सभी रूट्स के लिए ट्रांज़िशन सेट करें |
| `transition` | `PageTransitionType` | पेज ट्रांज़िशन टाइप सेट करें (डिप्रिकेटेड, transition_type का उपयोग करें) |
| `transition_settings` | `PageTransitionSettings` | ट्रांज़िशन सेटिंग्स सेट करें |


<div id="route-parameters"></div>

## रूट पैरामीटर्स का उपयोग

जब आप एक नया पेज बनाते हैं, तो आप पैरामीटर्स स्वीकार करने के लिए रूट को अपडेट कर सकते हैं।

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

अब, जब आप पेज पर नेविगेट करते हैं, तो आप `userId` पास कर सकते हैं

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

आप नए पेज में पैरामीटर्स को इस प्रकार एक्सेस कर सकते हैं।

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## क्वेरी पैरामीटर्स

नए पेज पर नेविगेट करते समय, आप क्वेरी पैरामीटर्स भी प्रदान कर सकते हैं।

आइए देखते हैं।

```dart
  // Home page
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // navigate to profile page

  ...

  // Profile Page
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // or
    print(queryParameters()); // {"user": 7}
  };
```

> **नोट:** जब तक आपका पेज विजेट `NyStatefulWidget` और `NyPage` क्लास को एक्सटेंड करता है, तब तक आप रूट नाम से सभी क्वेरी पैरामीटर्स प्राप्त करने के लिए `widget.queryParameters()` कॉल कर सकते हैं।

```dart
// Example page
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Home page
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // or
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **सुझाव:** क्वेरी पैरामीटर्स को HTTP प्रोटोकॉल का पालन करना चाहिए, उदा. /account?userId=1&tab=2


<div id="page-transitions"></div>

## पेज ट्रांज़िशन

आप अपनी `router.dart` फ़ाइल को संशोधित करके एक पेज से दूसरे पेज पर नेविगेट करते समय ट्रांज़िशन जोड़ सकते हैं।

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### उपलब्ध पेज ट्रांज़िशन

#### बेसिक ट्रांज़िशन
- **`TransitionType.fade()`** - पुराने पेज को फ़ेड आउट करते हुए नए पेज को फ़ेड इन करता है
- **`TransitionType.theme()`** - ऐप थीम के पेज ट्रांज़िशन थीम का उपयोग करता है

#### दिशात्मक स्लाइड ट्रांज़िशन
- **`TransitionType.rightToLeft()`** - स्क्रीन के दाएँ किनारे से स्लाइड करता है
- **`TransitionType.leftToRight()`** - स्क्रीन के बाएँ किनारे से स्लाइड करता है
- **`TransitionType.topToBottom()`** - स्क्रीन के ऊपरी किनारे से स्लाइड करता है
- **`TransitionType.bottomToTop()`** - स्क्रीन के निचले किनारे से स्लाइड करता है

#### फ़ेड के साथ स्लाइड ट्रांज़िशन
- **`TransitionType.rightToLeftWithFade()`** - दाएँ किनारे से स्लाइड और फ़ेड करता है
- **`TransitionType.leftToRightWithFade()`** - बाएँ किनारे से स्लाइड और फ़ेड करता है

#### ट्रांसफ़ॉर्म ट्रांज़िशन
- **`TransitionType.scale(alignment: ...)`** - निर्दिष्ट अलाइनमेंट पॉइंट से स्केल करता है
- **`TransitionType.rotate(alignment: ...)`** - निर्दिष्ट अलाइनमेंट पॉइंट के चारों ओर रोटेट करता है
- **`TransitionType.size(alignment: ...)`** - निर्दिष्ट अलाइनमेंट पॉइंट से बढ़ता है

#### ज्वॉइंड ट्रांज़िशन (वर्तमान विजेट आवश्यक)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - वर्तमान पेज दाईं ओर निकलता है जबकि नया पेज बाईं ओर से प्रवेश करता है
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - वर्तमान पेज बाईं ओर निकलता है जबकि नया पेज दाईं ओर से प्रवेश करता है
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - वर्तमान पेज नीचे निकलता है जबकि नया पेज ऊपर से प्रवेश करता है
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - वर्तमान पेज ऊपर निकलता है जबकि नया पेज नीचे से प्रवेश करता है

#### पॉप ट्रांज़िशन (वर्तमान विजेट आवश्यक)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - वर्तमान पेज दाईं ओर निकलता है, नया पेज यथावत रहता है
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - वर्तमान पेज बाईं ओर निकलता है, नया पेज यथावत रहता है
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - वर्तमान पेज नीचे निकलता है, नया पेज यथावत रहता है
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - वर्तमान पेज ऊपर निकलता है, नया पेज यथावत रहता है

#### मटीरियल डिज़ाइन शेयर्ड एक्सिस ट्रांज़िशन
- **`TransitionType.sharedAxisHorizontal()`** - क्षैतिज स्लाइड और फ़ेड ट्रांज़िशन
- **`TransitionType.sharedAxisVertical()`** - ऊर्ध्वाधर स्लाइड और फ़ेड ट्रांज़िशन
- **`TransitionType.sharedAxisScale()`** - स्केल और फ़ेड ट्रांज़िशन

#### कस्टमाइज़ेशन पैरामीटर्स
प्रत्येक ट्रांज़िशन निम्नलिखित वैकल्पिक पैरामीटर्स स्वीकार करता है:

| पैरामीटर | विवरण | डिफ़ॉल्ट |
|-----------|-------------|---------|
| `curve` | एनिमेशन कर्व | प्लेटफ़ॉर्म-विशिष्ट कर्व |
| `duration` | एनिमेशन अवधि | प्लेटफ़ॉर्म-विशिष्ट अवधि |
| `reverseDuration` | रिवर्स एनिमेशन अवधि | अवधि के समान |
| `fullscreenDialog` | क्या रूट फ़ुलस्क्रीन डायलॉग है | `false` |
| `opaque` | क्या रूट अपारदर्शी है | `false` |


``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## नेविगेशन प्रकार

नेविगेट करते समय, यदि आप `routeTo` हेल्पर का उपयोग कर रहे हैं तो आप निम्नलिखित में से एक निर्दिष्ट कर सकते हैं।

| प्रकार | विवरण |
|------|-------------|
| `NavigationType.push` | अपने ऐप के रूट स्टैक में एक नया पेज पुश करें |
| `NavigationType.pushReplace` | वर्तमान रूट को बदलें, नया रूट पूरा होने पर पिछले रूट को डिस्पोज़ करें |
| `NavigationType.popAndPushNamed` | नेविगेटर से वर्तमान रूट को पॉप करें और उसके स्थान पर एक नेम्ड रूट पुश करें |
| `NavigationType.pushAndRemoveUntil` | पुश करें और रूट्स हटाएँ जब तक प्रेडिकेट true नहीं रिटर्न करता |
| `NavigationType.pushAndForgetAll` | एक नए पेज पर पुश करें और रूट स्टैक पर अन्य सभी पेज डिस्पोज़ करें |

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## वापस नेविगेट करना

एक बार जब आप नए पेज पर हैं, तो आप मौजूदा पेज पर वापस जाने के लिए `pop()` हेल्पर का उपयोग कर सकते हैं।

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // or
    Navigator.pop(context);
  }
...
```

यदि आप पिछले विजेट को कोई वैल्यू रिटर्न करना चाहते हैं, तो नीचे दिए गए उदाहरण की तरह एक `result` प्रदान करें।

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Get the value from the previous widget using the `onPop` parameter
// HomePage Widget
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## कंडीशनल नेविगेशन

केवल तभी नेविगेट करने के लिए जब कोई शर्त पूरी हो, `routeIf()` का उपयोग करें:

``` dart
// Only navigate if the user is logged in
routeIf(isLoggedIn, DashboardPage.path);

// With additional options
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

यदि शर्त `false` है, तो कोई नेविगेशन नहीं होता।


<div id="route-history"></div>

## रूट हिस्ट्री

{{ config('app.name') }} में, आप नीचे दिए गए हेल्पर्स का उपयोग करके रूट हिस्ट्री की जानकारी प्राप्त कर सकते हैं।

``` dart
// Get route history
Nylo.getRouteHistory(); // List<dynamic>

// Get the current route
Nylo.getCurrentRoute(); // Route<dynamic>?

// Get the previous route
Nylo.getPreviousRoute(); // Route<dynamic>?

// Get the current route name
Nylo.getCurrentRouteName(); // String?

// Get the previous route name
Nylo.getPreviousRouteName(); // String?

// Get the current route arguments
Nylo.getCurrentRouteArguments(); // dynamic

// Get the previous route arguments
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## रूट स्टैक अपडेट करें

आप `NyNavigator.updateStack()` का उपयोग करके प्रोग्रामैटिक रूप से नेविगेशन स्टैक अपडेट कर सकते हैं:

``` dart
// Update the stack with a list of routes
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Pass data to specific routes
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | आवश्यक | नेविगेट करने के लिए रूट पाथ की सूची |
| `replace` | `bool` | `true` | वर्तमान स्टैक को बदलना है या नहीं |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | विशिष्ट रूट्स को पास करने के लिए डेटा |

यह इनके लिए उपयोगी है:
- डीप लिंकिंग परिदृश्य
- नेविगेशन स्टेट को रिस्टोर करना
- जटिल नेविगेशन फ़्लो बनाना


<div id="deep-linking"></div>

## डीप लिंकिंग

डीप लिंकिंग यूज़र्स को URL का उपयोग करके आपके ऐप के भीतर सीधे विशिष्ट कंटेंट पर नेविगेट करने की अनुमति देती है। यह इनके लिए उपयोगी है:
- विशिष्ट ऐप कंटेंट के सीधे लिंक साझा करना
- मार्केटिंग अभियान जो विशिष्ट इन-ऐप फ़ीचर्स को लक्षित करते हैं
- नोटिफ़िकेशन हैंडल करना जो विशिष्ट ऐप स्क्रीन खोलनी चाहिए
- सहज वेब-से-ऐप ट्रांज़िशन

## सेटअप

अपने ऐप में डीप लिंकिंग लागू करने से पहले, सुनिश्चित करें कि आपका प्रोजेक्ट सही तरह से कॉन्फ़िगर किया गया है:

### 1. प्लेटफ़ॉर्म कॉन्फ़िगरेशन

**iOS**: अपने Xcode प्रोजेक्ट में यूनिवर्सल लिंक्स कॉन्फ़िगर करें
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter यूनिवर्सल लिंक्स कॉन्फ़िगरेशन गाइड</a>

**Android**: अपनी AndroidManifest.xml में ऐप लिंक्स सेट अप करें
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter ऐप लिंक्स कॉन्फ़िगरेशन गाइड</a>

### 2. अपने रूट्स परिभाषित करें

डीप लिंक्स के माध्यम से एक्सेसिबल होने वाले सभी रूट्स आपके राउटर कॉन्फ़िगरेशन में रजिस्टर्ड होने चाहिए:

```dart
// File: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Basic routes
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route with parameters
  router.add(HotelBookingPage.path);
});
```

## डीप लिंक्स का उपयोग

कॉन्फ़िगर होने के बाद, आपका ऐप विभिन्न फ़ॉर्मेट में आने वाले URL को हैंडल कर सकता है:

### बेसिक डीप लिंक्स

विशिष्ट पेजों पर सरल नेविगेशन:

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

अपने ऐप के भीतर प्रोग्रामैटिक रूप से ये नेविगेशन ट्रिगर करने के लिए:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### पाथ पैरामीटर्स

उन रूट्स के लिए जिन्हें पाथ के हिस्से के रूप में डायनामिक डेटा की आवश्यकता है:

#### रूट डेफ़िनिशन

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Define a route with a parameter placeholder {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Access the path parameter
    final hotelId = queryParameters()["id"]; // Returns "87" for URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Use the ID to fetch hotel data or perform operations
  };

  // Rest of your page implementation
}
```

#### URL फ़ॉर्मेट

``` bash
https://yourdomain.com/hotel/87/booking
```

#### प्रोग्रामैटिक नेविगेशन

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### क्वेरी पैरामीटर्स

वैकल्पिक पैरामीटर्स या जब कई डायनामिक वैल्यू की आवश्यकता हो:

#### URL फ़ॉर्मेट

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### क्वेरी पैरामीटर्स एक्सेस करना

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Get all query parameters
    final params = queryParameters();

    // Access specific parameters
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Alternative access method
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### क्वेरी पैरामीटर्स के साथ प्रोग्रामैटिक नेविगेशन

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## डीप लिंक्स हैंडल करना

आप अपने `RouteProvider` में डीप लिंक इवेंट्स हैंडल कर सकते हैं:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Handle deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Update the route stack for deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### डीप लिंक्स टेस्ट करना

डेवलपमेंट और टेस्टिंग के लिए, आप ADB (Android) या xcrun (iOS) का उपयोग करके डीप लिंक एक्टिवेशन सिमुलेट कर सकते हैं:

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### डिबगिंग टिप्स

- सही पार्सिंग सत्यापित करने के लिए अपने init मेथड में सभी पैरामीटर्स प्रिंट करें
- यह सुनिश्चित करने के लिए विभिन्न URL फ़ॉर्मेट टेस्ट करें कि आपका ऐप उन्हें सही ढंग से हैंडल करता है
- याद रखें कि क्वेरी पैरामीटर्स हमेशा स्ट्रिंग के रूप में प्राप्त होते हैं, आवश्यकतानुसार उन्हें उचित टाइप में कन्वर्ट करें

---

## सामान्य पैटर्न

### पैरामीटर टाइप कन्वर्शन

चूँकि सभी URL पैरामीटर्स स्ट्रिंग के रूप में पास किए जाते हैं, आपको अक्सर उन्हें कन्वर्ट करना होगा:

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### वैकल्पिक पैरामीटर्स

उन मामलों को हैंडल करें जहाँ पैरामीटर्स अनुपस्थित हो सकते हैं:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Load specific user profile
} else {
  // Load current user profile
}

// Or check hasQueryParameter
if (hasQueryParameter('status')) {
  // Do something with the status parameter
} else {
  // Handle absence of the parameter
}
```


<div id="advanced"></div>

## एडवांस्ड

### रूट मौजूद है या नहीं जाँचना

आप जाँच सकते हैं कि कोई रूट आपके राउटर में रजिस्टर्ड है या नहीं:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter मेथड्स

`NyRouter` क्लास कई उपयोगी मेथड्स प्रदान करती है:

| मेथड | विवरण |
|--------|-------------|
| `getRegisteredRouteNames()` | सभी रजिस्टर्ड रूट नामों की सूची प्राप्त करें |
| `getRegisteredRoutes()` | सभी रजिस्टर्ड रूट्स एक मैप के रूप में प्राप्त करें |
| `containsRoutes(routes)` | जाँचें कि राउटर में सभी निर्दिष्ट रूट्स हैं या नहीं |
| `getInitialRouteName()` | इनिशियल रूट का नाम प्राप्त करें |
| `getAuthRouteName()` | ऑथेंटिकेटेड रूट का नाम प्राप्त करें |
| `getUnknownRouteName()` | अज्ञात/404 रूट का नाम प्राप्त करें |

### रूट आर्ग्यूमेंट्स प्राप्त करना

आप `NyRouter.args<T>()` का उपयोग करके रूट आर्ग्यूमेंट्स प्राप्त कर सकते हैं:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Get typed arguments
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument और NyQueryParameters

रूट्स के बीच पास किया गया डेटा इन क्लासेस में रैप किया जाता है:

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
