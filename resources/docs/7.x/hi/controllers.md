# कंट्रोलर्स

---

<a name="section-1"></a>
- [परिचय](#introduction "कंट्रोलर्स का परिचय")
- [कंट्रोलर बनाना](#creating-controllers "कंट्रोलर बनाना")
- [कंट्रोलर का उपयोग करना](#using-controllers "कंट्रोलर का उपयोग करना")
- कंट्रोलर फ़ीचर्स
  - [रूट डेटा एक्सेस करना](#accessing-route-data "रूट डेटा एक्सेस करना")
  - [क्वेरी पैरामीटर्स](#query-parameters "क्वेरी पैरामीटर्स")
  - [पेज स्टेट मैनेजमेंट](#page-state-management "पेज स्टेट मैनेजमेंट")
  - [टोस्ट नोटिफ़िकेशन](#toast-notifications "टोस्ट नोटिफ़िकेशन")
  - [फ़ॉर्म वैलिडेशन](#form-validation "फ़ॉर्म वैलिडेशन")
  - [भाषा स्विचिंग](#language-switching "भाषा स्विचिंग")
  - [लॉक रिलीज़](#lock-release "लॉक रिलीज़")
  - [एक्शन कन्फ़र्म करें](#confirm-actions "एक्शन कन्फ़र्म करें")
- [सिंगलटन कंट्रोलर्स](#singleton-controllers "सिंगलटन कंट्रोलर्स")
- [कंट्रोलर डीकोडर्स](#controller-decoders "कंट्रोलर डीकोडर्स")
- [रूट गार्ड्स](#route-guards "रूट गार्ड्स")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 में कंट्रोलर्स आपके व्यूज़ (पेजेज़) और बिज़नेस लॉजिक के बीच समन्वयक के रूप में कार्य करते हैं। वे यूज़र इनपुट संभालते हैं, स्टेट अपडेट प्रबंधित करते हैं, और चिंताओं का स्वच्छ पृथक्करण प्रदान करते हैं।

{{ config('app.name') }} v7 टोस्ट नोटिफ़िकेशन, फ़ॉर्म वैलिडेशन, स्टेट मैनेजमेंट और अन्य के लिए शक्तिशाली बिल्ट-इन मेथड्स के साथ `NyController` क्लास पेश करता है।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Initialize services or fetch data
  }

  void onTapProfile() {
    routeTo(ProfilePage.path);
  }

  void submitForm() {
    validate(
      rules: {"email": "email"},
      onSuccess: () => showToastSuccess(description: "Form submitted!"),
    );
  }
}
```

<div id="creating-controllers"></div>

## कंट्रोलर बनाना

कंट्रोलर जेनरेट करने के लिए Metro CLI का उपयोग करें:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

यह बनाता है:
- **कंट्रोलर**: `lib/app/controllers/dashboard_controller.dart`
- **पेज**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## कंट्रोलर का उपयोग करना

`NyStatefulWidget` पर जेनेरिक टाइप के रूप में निर्दिष्ट करके कंट्रोलर को अपने पेज से जोड़ें:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';

class HomePage extends NyStatefulWidget<HomeController> {

  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {

  @override
  get init => () async {
    // Access controller methods
    widget.controller.fetchData();
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Home")),
      body: Column(
        children: [
          ElevatedButton(
            onPressed: widget.controller.onTapProfile,
            child: Text("View Profile"),
          ),
          TextField(
            controller: widget.controller.nameController,
          ),
        ],
      ),
    );
  }
}
```

<div id="accessing-route-data"></div>

## रूट डेटा एक्सेस करना

पेजों के बीच डेटा पास करें और अपने कंट्रोलर में इसे एक्सेस करें:

``` dart
// Navigate with data
routeTo(ProfilePage.path, data: {"userId": 123});

// In your controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Get the passed data
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

या अपने पेज स्टेट में सीधे डेटा एक्सेस करें:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // From controller
    var userData = widget.controller.data();

    // Or from widget directly
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## क्वेरी पैरामीटर्स

अपने कंट्रोलर में URL क्वेरी पैरामीटर्स एक्सेस करें:

``` dart
// Navigate to: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// In your controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Get all query parameters as Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Get a specific parameter
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

जाँचें कि क्वेरी पैरामीटर मौजूद है या नहीं:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## पेज स्टेट मैनेजमेंट

कंट्रोलर सीधे पेज स्टेट प्रबंधित कर सकते हैं:

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // Trigger a setState on the page
    setState(setState: () {});
  }

  void refresh() {
    // Refresh the entire page
    refreshPage();
  }

  void goBack() {
    // Pop the page with optional result
    pop(result: {"updated": true});
  }

  void updateCustomState() {
    // Send custom action to page
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## टोस्ट नोटिफ़िकेशन

कंट्रोलर में बिल्ट-इन टोस्ट नोटिफ़िकेशन मेथड्स शामिल हैं:

``` dart
class FormController extends NyController {

  void showNotifications() {
    // Success toast
    showToastSuccess(description: "Profile updated!");

    // Warning toast
    showToastWarning(description: "Please check your input");

    // Error/Danger toast
    showToastDanger(description: "Failed to save changes");

    // Info toast
    showToastInfo(description: "New features available");

    // Sorry toast
    showToastSorry(description: "We couldn't process your request");

    // Oops toast
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Custom toast with title
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Use custom toast style (registered in Nylo)
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## फ़ॉर्म वैलिडेशन

अपने कंट्रोलर से सीधे फ़ॉर्म डेटा वैलिडेट करें:

``` dart
class RegisterController extends NyController {

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  void submitRegistration() {
    validate(
      rules: {
        "email": "email|max:50",
        "password": "min:8|max:64",
      },
      data: {
        "email": emailController.text,
        "password": passwordController.text,
      },
      messages: {
        "email.email": "Please enter a valid email",
        "password.min": "Password must be at least 8 characters",
      },
      showAlert: true,
      alertStyle: 'warning',
      onSuccess: () {
        // Validation passed
        _performRegistration();
      },
      onFailure: (exception) {
        // Validation failed
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Handle registration logic
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## भाषा स्विचिंग

अपने कंट्रोलर से ऐप की भाषा बदलें:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es', restartState: true);
  }

  void switchToEnglish() {
    changeLanguage('en', restartState: true);
  }
}
```

<div id="lock-release"></div>

## लॉक रिलीज़

बटनों पर एकाधिक तीव्र टैप रोकें:

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // This code only runs once until the lock is released
      await processPayment();
      showToastSuccess(description: "Payment complete!");
    });
  }

  void onTapWithoutSetState() {
    lockRelease(
      "my_lock",
      perform: () async {
        await someAsyncOperation();
      },
      shouldSetState: false, // Don't trigger setState after
    );
  }
}
```

<div id="confirm-actions"></div>

## एक्शन कन्फ़र्म करें

विनाशकारी क्रियाएँ करने से पहले एक कन्फ़र्मेशन डायलॉग दिखाएँ:

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // User confirmed - perform deletion
        await deleteAccount();
        showToastSuccess(description: "Account deleted");
      },
      title: "Delete Account?",
      dismissText: "Cancel",
    );
  }
}
```

<div id="singleton-controllers"></div>

## सिंगलटन कंट्रोलर्स

कंट्रोलर को ऐप में सिंगलटन के रूप में बनाए रखें:

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // Login logic
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

सिंगलटन कंट्रोलर एक बार बनाए जाते हैं और पूरे ऐप लाइफसाइकल में पुन: उपयोग किए जाते हैं।

<div id="controller-decoders"></div>

## कंट्रोलर डीकोडर्स

अपने कंट्रोलर्स को `lib/config/decoders.dart` में रजिस्टर करें:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';
import '/app/controllers/profile_controller.dart';
import '/app/controllers/auth_controller.dart';

final Map<Type, BaseController Function()> controllers = {
  HomeController: () => HomeController(),
  ProfileController: () => ProfileController(),
  AuthController: () => AuthController(),
};
```

यह मैप {{ config('app.name') }} को पेज लोड होने पर कंट्रोलर्स रिज़ॉल्व करने की अनुमति देता है।

<div id="route-guards"></div>

## रूट गार्ड्स

कंट्रोलर रूट गार्ड्स डिफ़ाइन कर सकते हैं जो पेज लोड होने से पहले चलते हैं:

``` dart
class AdminController extends NyController {

  @override
  List<RouteGuard> get routeGuards => [
    AuthRouteGuard(),
    AdminRoleGuard(),
  ];

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Only runs if all guards pass
  }
}
```

रूट गार्ड्स के बारे में अधिक विवरण के लिए [राउटर डॉक्यूमेंटेशन](/docs/7.x/router) देखें।

