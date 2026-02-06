# ऑथेंटिकेशन

---

<a name="section-1"></a>
- [परिचय](#introduction "ऑथेंटिकेशन का परिचय")
- बेसिक्स
  - [यूज़र्स को ऑथेंटिकेट करना](#authenticating-users "यूज़र्स को ऑथेंटिकेट करना")
  - [ऑथ डेटा प्राप्त करना](#retrieving-auth-data "ऑथ डेटा प्राप्त करना")
  - [ऑथ डेटा अपडेट करना](#updating-auth-data "ऑथ डेटा अपडेट करना")
  - [लॉगआउट करना](#logging-out "लॉगआउट करना")
  - [ऑथेंटिकेशन जाँचना](#checking-authentication "ऑथेंटिकेशन जाँचना")
- एडवांस्ड
  - [मल्टीपल सेशन](#multiple-sessions "मल्टीपल सेशन")
  - [डिवाइस ID](#device-id "डिवाइस ID")
  - [Backpack में सिंक करना](#syncing-to-backpack "Backpack में सिंक करना")
- रूट कॉन्फ़िगरेशन
  - [इनिशियल रूट](#initial-route "इनिशियल रूट")
  - [ऑथेंटिकेटेड रूट](#authenticated-route "ऑथेंटिकेटेड रूट")
  - [प्रीव्यू रूट](#preview-route "प्रीव्यू रूट")
  - [अज्ञात रूट](#unknown-route "अज्ञात रूट")
- [हेल्पर फ़ंक्शन](#helper-functions "हेल्पर फ़ंक्शन")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 `Auth` क्लास के माध्यम से एक व्यापक ऑथेंटिकेशन सिस्टम प्रदान करता है। यह यूज़र क्रेडेंशियल्स का सुरक्षित स्टोरेज, सेशन मैनेजमेंट संभालता है, और विभिन्न ऑथ कॉन्टेक्स्ट के लिए मल्टीपल नेम्ड सेशन का समर्थन करता है।

ऑथ डेटा सुरक्षित रूप से संग्रहीत होता है और Backpack (एक इन-मेमोरी की-वैल्यू स्टोर) में सिंक होता है, जिससे आपके पूरे ऐप में तेज़, सिंक्रोनस एक्सेस मिलता है।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Authenticate a user
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// Check if authenticated
bool loggedIn = await Auth.isAuthenticated(); // true

// Get auth data
dynamic token = Auth.data(field: 'token'); // "abc123"

// Logout
await Auth.logout();
```


<div id="authenticating-users"></div>

## यूज़र्स को ऑथेंटिकेट करना

यूज़र सेशन डेटा स्टोर करने के लिए `Auth.authenticate()` का उपयोग करें:

``` dart
// With a Map
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// With a Model class
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// Without data (stores a timestamp)
await Auth.authenticate();
```

### वास्तविक उदाहरण

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. Call your API to authenticate
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. Store the authenticated user
    await Auth.authenticate(data: user);

    // 3. Navigate to home
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## ऑथ डेटा प्राप्त करना

`Auth.data()` का उपयोग करके संग्रहीत ऑथ डेटा प्राप्त करें:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

`Auth.data()` मेथड तेज़ सिंक्रोनस एक्सेस के लिए Backpack ({{ config('app.name') }} का इन-मेमोरी की-वैल्यू स्टोर) से पढ़ता है। जब आप ऑथेंटिकेट करते हैं तो डेटा स्वचालित रूप से Backpack में सिंक हो जाता है।


<div id="updating-auth-data"></div>

## ऑथ डेटा अपडेट करना

{{ config('app.name') }} v7 ऑथ डेटा अपडेट करने के लिए `Auth.set()` पेश करता है:

``` dart
// Update a specific field
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// Add new fields
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// Replace entire data
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## लॉगआउट करना

`Auth.logout()` के साथ ऑथेंटिकेटेड यूज़र को हटाएँ:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### सभी सेशन से लॉगआउट

मल्टीपल सेशन का उपयोग करते समय, सभी को क्लियर करें:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## ऑथेंटिकेशन जाँचना

जाँचें कि कोई यूज़र वर्तमान में ऑथेंटिकेटेड है या नहीं:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // User is authenticated
  routeTo(HomePage.path);
} else {
  // User needs to login
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## मल्टीपल सेशन

{{ config('app.name') }} v7 विभिन्न कॉन्टेक्स्ट के लिए मल्टीपल नेम्ड ऑथ सेशन का समर्थन करता है। यह तब उपयोगी है जब आपको विभिन्न प्रकार के ऑथेंटिकेशन को अलग-अलग ट्रैक करने की आवश्यकता हो (जैसे, यूज़र लॉगिन vs डिवाइस रजिस्ट्रेशन vs एडमिन एक्सेस)।

``` dart
// Default user session
await Auth.authenticate(data: user);

// Device authentication session
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// Admin session
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### नेम्ड सेशन से पढ़ना

``` dart
// Default session
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// Device session
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// Admin session
dynamic adminData = Auth.data(session: 'admin');
```

### सेशन-विशिष्ट लॉगआउट

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### प्रति सेशन ऑथेंटिकेशन जाँचें

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## डिवाइस ID

{{ config('app.name') }} v7 एक यूनिक डिवाइस आइडेंटिफ़ायर प्रदान करता है जो ऐप सेशन के बीच बना रहता है:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

डिवाइस ID:
- एक बार जेनरेट होता है और स्थायी रूप से संग्रहीत रहता है
- प्रत्येक डिवाइस/इंस्टॉलेशन के लिए यूनिक है
- डिवाइस रजिस्ट्रेशन, एनालिटिक्स या पुश नोटिफ़िकेशन के लिए उपयोगी है

``` dart
// Example: Register device with backend
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // Store device auth
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## Backpack में सिंक करना

ऑथ डेटा ऑथेंटिकेट करने पर स्वचालित रूप से Backpack में सिंक हो जाता है। मैन्युअल रूप से सिंक करने के लिए (जैसे, ऐप बूट पर):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

यह आपके ऐप की बूट सीक्वेंस में उपयोगी है ताकि तेज़ सिंक्रोनस एक्सेस के लिए ऑथ डेटा Backpack में उपलब्ध रहे।


<div id="initial-route"></div>

## इनिशियल रूट

इनिशियल रूट वह पहला पेज है जो यूज़र्स आपका ऐप खोलते समय देखते हैं। इसे अपने राउटर में `.initialRoute()` का उपयोग करके सेट करें:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

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

कहीं से भी `routeToInitial()` का उपयोग करके इनिशियल रूट पर वापस नेविगेट करें:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## ऑथेंटिकेटेड रूट

ऑथेंटिकेटेड रूट यूज़र लॉग इन होने पर इनिशियल रूट को ओवरराइड करता है। इसे `.authenticatedRoute()` का उपयोग करके सेट करें:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

जब ऐप बूट होता है:
- `Auth.isAuthenticated()` `true` रिटर्न करता है → यूज़र **ऑथेंटिकेटेड रूट** (HomePage) देखता है
- `Auth.isAuthenticated()` `false` रिटर्न करता है → यूज़र **इनिशियल रूट** (LoginPage) देखता है

आप कंडीशनल ऑथेंटिकेटेड रूट भी सेट कर सकते हैं:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

`routeToAuthenticatedRoute()` का उपयोग करके प्रोग्रामेटिक रूप से ऑथेंटिकेटेड रूट पर नेविगेट करें:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**यह भी देखें:** रूटिंग की पूर्ण डॉक्यूमेंटेशन के लिए [Router](/docs/{{ $version }}/router), जिसमें गार्ड्स और डीप लिंकिंग शामिल हैं।


<div id="preview-route"></div>

## प्रीव्यू रूट

डेवलपमेंट के दौरान, आप अपने इनिशियल या ऑथेंटिकेटेड रूट को बदले बिना किसी विशिष्ट पेज को तुरंत प्रीव्यू करना चाह सकते हैं। `.previewRoute()` का उपयोग करें:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` **दोनों** `initialRoute()` और `authenticatedRoute()` को ओवरराइड करता है, जिससे निर्दिष्ट रूट ऑथ स्टेट की परवाह किए बिना पहला दिखाया जाने वाला पेज बन जाता है।

> **चेतावनी:** अपना ऐप रिलीज़ करने से पहले `.previewRoute()` हटा दें।


<div id="unknown-route"></div>

## अज्ञात रूट

जब कोई यूज़र ऐसे रूट पर नेविगेट करता है जो मौजूद नहीं है तो फ़ॉलबैक पेज डिफ़ाइन करें। इसे `.unknownRoute()` का उपयोग करके सेट करें:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### सब एक साथ

सभी रूट टाइप्स के साथ एक पूर्ण राउटर सेटअप:

``` dart
appRouter() => nyRoutes((router) {
  // First page for unauthenticated users
  router.add(LoginPage.path).initialRoute();

  // First page for authenticated users
  router.add(HomePage.path).authenticatedRoute();

  // 404 page
  router.add(NotFoundPage.path).unknownRoute();

  // Regular routes
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| रूट मेथड | उद्देश्य |
|--------------|---------|
| `.initialRoute()` | अनऑथेंटिकेटेड यूज़र्स को दिखाया जाने वाला पहला पेज |
| `.authenticatedRoute()` | ऑथेंटिकेटेड यूज़र्स को दिखाया जाने वाला पहला पेज |
| `.previewRoute()` | डेवलपमेंट के दौरान दोनों को ओवरराइड करता है |
| `.unknownRoute()` | रूट न मिलने पर दिखाया जाता है |


<div id="helper-functions"></div>

## हेल्पर फ़ंक्शन

{{ config('app.name') }} v7 हेल्पर फ़ंक्शन प्रदान करता है जो `Auth` क्लास मेथड्स को मिरर करते हैं:

| हेल्पर फ़ंक्शन | समतुल्य |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | डिफ़ॉल्ट सेशन के लिए स्टोरेज की |
| `authDeviceId()` | `Auth.deviceId()` |

सभी हेल्पर्स अपने `Auth` क्लास समकक्षों के समान पैरामीटर स्वीकार करते हैं, जिसमें वैकल्पिक `session` पैरामीटर शामिल है:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

