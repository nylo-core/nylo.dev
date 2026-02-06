# Route Guards

---

<a name="section-1"></a>
- [परिचय](#introduction "Introduction")
- [रूट गार्ड बनाना](#creating-a-route-guard "Creating a Route Guard")
- [गार्ड जीवनचक्र](#guard-lifecycle "Guard Lifecycle")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [गार्ड एक्शन](#guard-actions "Guard Actions")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [रूट पर गार्ड लागू करना](#applying-guards "Applying Guards to Routes")
- [समूह गार्ड](#group-guards "Group Guards")
- [गार्ड कम्पोज़िशन](#guard-composition "Guard Composition")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [उदाहरण](#examples "Practical Examples")

<div id="introduction"></div>

## परिचय

रूट गार्ड {{ config('app.name') }} में **नेविगेशन के लिए मिडलवेयर** प्रदान करते हैं। ये रूट ट्रांज़िशन को इंटरसेप्ट करते हैं और आपको यह नियंत्रित करने की अनुमति देते हैं कि कोई उपयोगकर्ता किसी पेज तक पहुँच सकता है या नहीं, उसे कहीं और रीडायरेक्ट करना है, या रूट को भेजे गए डेटा को संशोधित करना है।

सामान्य उपयोग के मामले शामिल हैं:
- **प्रमाणीकरण जाँच** -- अप्रमाणित उपयोगकर्ताओं को लॉगिन पेज पर रीडायरेक्ट करना
- **भूमिका-आधारित एक्सेस** -- पेजों को एडमिन उपयोगकर्ताओं तक सीमित करना
- **डेटा सत्यापन** -- नेविगेशन से पहले सुनिश्चित करना कि आवश्यक डेटा मौजूद है
- **डेटा संवर्धन** -- रूट में अतिरिक्त डेटा जोड़ना

गार्ड नेविगेशन होने से पहले **क्रम में** निष्पादित होते हैं। यदि कोई गार्ड `handled` लौटाता है, तो नेविगेशन रुक जाता है (या तो रीडायरेक्ट या एबॉर्ट करके)।

<div id="creating-a-route-guard"></div>

## रूट गार्ड बनाना

Metro CLI का उपयोग करके रूट गार्ड बनाएँ:

``` bash
metro make:route_guard auth
```

यह एक गार्ड फ़ाइल जनरेट करता है:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Add your guard logic here
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## गार्ड जीवनचक्र

प्रत्येक रूट गार्ड में तीन जीवनचक्र मेथड होते हैं:

<div id="on-before"></div>

### onBefore

नेविगेशन होने **से पहले** कॉल होता है। यहाँ आप शर्तें जाँचते हैं और तय करते हैं कि नेविगेशन की अनुमति देनी है, रीडायरेक्ट करना है, या एबॉर्ट करना है।

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

रिटर्न मान:
- `next()` -- अगले गार्ड पर जाएँ या रूट पर नेविगेट करें
- `redirect(path)` -- एक अलग रूट पर रीडायरेक्ट करें
- `abort()` -- नेविगेशन पूरी तरह रद्द करें

<div id="on-after"></div>

### onAfter

सफल नेविगेशन **के बाद** कॉल होता है। इसे एनालिटिक्स, लॉगिंग, या नेविगेशन के बाद के साइड इफेक्ट्स के लिए उपयोग करें।

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

जब उपयोगकर्ता किसी रूट **से बाहर जा रहा** हो तब कॉल होता है। उपयोगकर्ता को बाहर जाने से रोकने के लिए `false` लौटाएँ।

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Show confirmation dialog
    return await showConfirmDialog();
  }
  return true; // Allow leaving
}
```

<div id="route-context"></div>

## RouteContext

`RouteContext` ऑब्जेक्ट सभी गार्ड जीवनचक्र मेथड को पास किया जाता है और इसमें नेविगेशन के बारे में जानकारी होती है:

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `context` | `BuildContext?` | वर्तमान बिल्ड कॉन्टेक्स्ट |
| `data` | `dynamic` | रूट को पास किया गया डेटा |
| `queryParameters` | `Map<String, String>` | URL क्वेरी पैरामीटर |
| `routeName` | `String` | लक्ष्य रूट का नाम/पथ |
| `originalRouteName` | `String?` | ट्रांसफ़ॉर्मेशन से पहले का मूल रूट नाम |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Access route information
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### रूट कॉन्टेक्स्ट को ट्रांसफ़ॉर्म करना

अलग डेटा के साथ एक कॉपी बनाएँ:

``` dart
// Change the data type
RouteContext<User> userContext = context.withData<User>(currentUser);

// Copy with modified fields
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## गार्ड एक्शन

<div id="next"></div>

### next

चेन में अगले गार्ड पर जाएँ, या यदि यह अंतिम गार्ड है तो रूट पर नेविगेट करें:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

उपयोगकर्ता को एक अलग रूट पर रीडायरेक्ट करें:

``` dart
return redirect(LoginPage.path);
```

अतिरिक्त विकल्पों के साथ:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `path` | `Object` | आवश्यक | रूट पथ स्ट्रिंग या RouteView |
| `data` | `dynamic` | null | रीडायरेक्ट रूट को पास करने के लिए डेटा |
| `queryParameters` | `Map<String, dynamic>?` | null | क्वेरी पैरामीटर |
| `navigationType` | `NavigationType` | `pushReplace` | नेविगेशन विधि |
| `result` | `dynamic` | null | लौटाया जाने वाला रिज़ल्ट |
| `removeUntilPredicate` | `Function?` | null | रूट हटाने का प्रेडिकेट |
| `transitionType` | `TransitionType?` | null | पेज ट्रांज़िशन टाइप |
| `onPop` | `Function(dynamic)?` | null | पॉप पर कॉलबैक |

<div id="abort"></div>

### abort

रीडायरेक्ट किए बिना नेविगेशन रद्द करें। उपयोगकर्ता अपने वर्तमान पेज पर बना रहता है:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

बाद के गार्ड और लक्ष्य रूट को पास किए जाने वाले डेटा को संशोधित करें:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Enrich the route data
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## रूट पर गार्ड लागू करना

अपनी राउटर फ़ाइल में अलग-अलग रूट में गार्ड जोड़ें:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Add a single guard
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Add multiple guards (executed in order)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## समूह गार्ड

रूट समूहों का उपयोग करके एक साथ कई रूट पर गार्ड लागू करें:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // All routes in this group require authentication
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## गार्ड कम्पोज़िशन

{{ config('app.name') }} पुन: उपयोग योग्य पैटर्न के लिए गार्ड को एक साथ कम्पोज़ करने के उपकरण प्रदान करता है।

<div id="guard-stack"></div>

### GuardStack

कई गार्ड को एक पुन: उपयोग योग्य गार्ड में जोड़ें:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Use the stack on a route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` गार्ड को क्रम में निष्पादित करता है। यदि कोई गार्ड `handled` लौटाता है, तो शेष गार्ड छोड़ दिए जाते हैं।

<div id="conditional-guard"></div>

### ConditionalGuard

गार्ड को केवल तभी लागू करें जब कोई शर्त सत्य हो:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

यदि शर्त `false` लौटाती है, तो गार्ड को छोड़ दिया जाता है और नेविगेशन जारी रहता है।

<div id="parameterized-guard"></div>

### ParameterizedGuard

कॉन्फ़िगरेशन पैरामीटर स्वीकार करने वाले गार्ड बनाएँ:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = allowed roles

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Usage
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## उदाहरण

### प्रमाणीकरण गार्ड

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### पैरामीटर के साथ सब्सक्रिप्शन गार्ड

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// Require premium or pro subscription
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### लॉगिंग गार्ड

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
