# NyState

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [NyState कैसे उपयोग करें](#how-to-use-nystate "NyState कैसे उपयोग करें")
- [लोडिंग स्टाइल](#loading-style "लोडिंग स्टाइल")
- [स्टेट एक्शन्स](#state-actions "स्टेट एक्शन्स")
- [हेल्पर्स](#helpers "हेल्पर्स")


<div id="introduction"></div>

## परिचय

`NyState` स्टैंडर्ड Flutter `State` क्लास का एक विस्तारित संस्करण है। यह आपके पेजों और विजेट्स के स्टेट को अधिक कुशलता से प्रबंधित करने में मदद करने के लिए अतिरिक्त कार्यक्षमता प्रदान करता है।

आप स्टेट के साथ बिल्कुल वैसे ही **इंटरैक्ट** कर सकते हैं जैसे आप एक सामान्य Flutter स्टेट के साथ करते हैं, लेकिन NyState के अतिरिक्त लाभों के साथ।

आइए जानें कि NyState कैसे उपयोग करें।

<div id="how-to-use-nystate"></div>

## NyState कैसे उपयोग करें

आप इस क्लास को एक्सटेंड करके इसका उपयोग शुरू कर सकते हैं।

उदाहरण

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {

  };

  @override
  view(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded")
    );
  }
```

`init` मेथड का उपयोग पेज के स्टेट को इनिशियलाइज़ करने के लिए किया जाता है। आप इस मेथड को async या बिना async के उपयोग कर सकते हैं और पर्दे के पीछे, यह async कॉल को हैंडल करेगा और एक लोडर दिखाएगा।

`view` मेथड का उपयोग पेज के लिए UI दिखाने के लिए किया जाता है।

#### NyState के साथ नया stateful widget बनाना

{{ config('app.name') }} में नया stateful widget बनाने के लिए, आप नीचे दिया गया कमांड चला सकते हैं।

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## लोडिंग स्टाइल

आप अपने पेज के लिए लोडिंग स्टाइल सेट करने के लिए `loadingStyle` प्रॉपर्टी का उपयोग कर सकते हैं।

उदाहरण

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**डिफ़ॉल्ट** `loadingStyle` आपका लोडिंग विजेट (resources/widgets/loader_widget.dart) होगा।
आप लोडिंग स्टाइल को अपडेट करने के लिए `loadingStyle` को कस्टमाइज़ कर सकते हैं।

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
// same for skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

अब, जब टैब लोड हो रहा होगा, तो "Loading..." टेक्स्ट दिखाया जाएगा।

नीचे उदाहरण:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // simulate a network call for 3 seconds
    };

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            body: Text("The page loaded")
        );
    }
    ...
}
```

<div id="state-actions"></div>

## स्टेट एक्शन्स

Nylo में, आप अपने विजेट्स में छोटे **एक्शन्स** परिभाषित कर सकते हैं जिन्हें अन्य क्लासेज़ से कॉल किया जा सकता है। यह तब उपयोगी है जब आप किसी विजेट के स्टेट को दूसरी क्लास से अपडेट करना चाहते हैं।

सबसे पहले, आपको अपने विजेट में अपने एक्शन्स **परिभाषित** करने होंगे। यह `NyState` और `NyPage` के लिए काम करता है।

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // handle how you want to initialize the state
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // Example with data
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

फिर, आप `stateAction` मेथड का उपयोग करके दूसरी क्लास से एक्शन कॉल कर सकते हैं।

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

यदि आप `NyPage` के साथ stateActions का उपयोग कर रहे हैं, तो आपको पेज का **path** उपयोग करना होगा।

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

`StateAction` नामक एक और क्लास भी है, इसमें कुछ मेथड्स हैं जिनका उपयोग आप अपने विजेट्स के स्टेट को अपडेट करने के लिए कर सकते हैं।

- `refreshPage` - पेज रिफ्रेश करें।
- `pop` - पेज पॉप करें।
- `showToastSorry` - एक सॉरी टोस्ट नोटिफ़िकेशन दिखाएँ।
- `showToastWarning` - एक वॉर्निंग टोस्ट नोटिफ़िकेशन दिखाएँ।
- `showToastInfo` - एक इन्फो टोस्ट नोटिफ़िकेशन दिखाएँ।
- `showToastDanger` - एक डेंजर टोस्ट नोटिफ़िकेशन दिखाएँ।
- `showToastOops` - एक ऊप्स टोस्ट नोटिफ़िकेशन दिखाएँ।
- `showToastSuccess` - एक सक्सेस टोस्ट नोटिफ़िकेशन दिखाएँ।
- `showToastCustom` - एक कस्टम टोस्ट नोटिफ़िकेशन दिखाएँ।
- `validate` - अपने विजेट से डेटा वैलिडेट करें।
- `changeLanguage` - एप्लिकेशन में भाषा अपडेट करें।
- `confirmAction` - एक कन्फर्म एक्शन करें।

उदाहरण

``` dart
class _UpgradeButtonState extends NyState<UpgradeButton> {

  view(BuildContext context) {
    return Button.primary(
      onPressed: () {
        StateAction.showToastSuccess(UpgradePage.state,
          description: "You have successfully upgraded your account",
        );
      },
      text: "Upgrade",
    );
  }
}
```

आप `StateAction` क्लास का उपयोग अपने एप्लिकेशन में किसी भी पेज/विजेट के स्टेट को अपडेट करने के लिए कर सकते हैं, जब तक विजेट स्टेट मैनेज्ड हो।

<div id="helpers"></div>

## हेल्पर्स

|  |  |
| --- | ----------- |
| [lockRelease](#lock-release "lockRelease") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") | [confirmAction](#confirm-action "confirmAction") |
| [showToastSuccess](#show-toast-success "showToastSuccess") | [showToastOops](#show-toast-oops "showToastOops") |
| [showToastDanger](#show-toast-danger "showToastDanger") | [showToastInfo](#show-toast-info "showToastInfo") |
| [showToastWarning](#show-toast-warning "showToastWarning") | [showToastSorry](#show-toast-sorry "showToastSorry") |


<div id="reboot"></div>

### Reboot

यह मेथड आपके स्टेट में `init` मेथड को फिर से चलाएगा। यह तब उपयोगी है जब आप पेज पर डेटा रिफ्रेश करना चाहते हैं।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  get init => () async {
    users = await api<ApiService>((request) => request.fetchUsers());
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Users"),
          actions: [
            IconButton(
              icon: Icon(Icons.refresh),
              onPressed: () {
                reboot(); // refresh the data
              },
            )
          ],
        ),
        body: ListView.builder(
          itemCount: users.length,
          itemBuilder: (context, index) {
            return Text(users[index].firstName);
          }
        ),
    );
  }
}
```

<div id="pop"></div>

### Pop

`pop` - स्टैक से वर्तमान पेज हटाएँ।

उदाहरण

``` dart
class _HomePageState extends NyState<HomePage> {

  popView() {
    pop();
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: popView,
        child: Text("Pop current view")
      )
    );
  }
```


<div id="showToast"></div>

### showToast

कॉन्टेक्स्ट पर एक टोस्ट नोटिफ़िकेशन दिखाएँ।

उदाहरण

```dart
class _HomePageState extends NyState<HomePage> {

  displayToast() {
    showToast(
        title: "Hello",
        description: "World",
        icon: Icons.account_circle,
        duration: Duration(seconds: 2),
        style: ToastNotificationStyleType.INFO // SUCCESS, INFO, DANGER, WARNING
    );
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: displayToast,
        child: Text("Display a toast")
      )
    );
  }
```


<div id="validate"></div>

### validate

`validate` हेल्पर डेटा पर वैलिडेशन चेक करता है।

आप वैलिडेटर के बारे में और जान सकते हैं <a href="/docs/{{$version}}/validation" target="_BLANK">यहाँ</a>।

उदाहरण

``` dart
class _HomePageState extends NyState<HomePage> {
TextEditingController _textFieldControllerEmail = TextEditingController();

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    validate(rules: {
        "email address": [textEmail, "email"]
      }, onSuccess: () {
      print('passed validation')
    });
  }
```


<div id="change-language"></div>

### changeLanguage

आप डिवाइस पर उपयोग की जाने वाली json **/lang** फ़ाइल को बदलने के लिए `changeLanguage` कॉल कर सकते हैं।

लोकलाइज़ेशन के बारे में और जानें <a href="/docs/{{$version}}/localization" target="_BLANK">यहाँ</a>।

उदाहरण

``` dart
class _HomePageState extends NyState<HomePage> {

  changeLanguageES() {
    await changeLanguage('es');
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: changeLanguageES,
        child: Text("Change Language".tr())
      )
    );
  }
```


<div id="when-env"></div>

### whenEnv

जब आपका एप्लिकेशन किसी विशेष स्थिति में हो तो फ़ंक्शन चलाने के लिए आप `whenEnv` का उपयोग कर सकते हैं।
उदा. आपकी `.env` फ़ाइल के अंदर आपका **APP_ENV** वेरिएबल 'developing' पर सेट है, `APP_ENV=developing`।

उदाहरण

```dart
class _HomePageState extends NyState<HomePage> {

  TextEditingController _textEditingController = TextEditingController();

  @override
  get init => () {
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  };
```

<div id="lock-release"></div>

### lockRelease

यह मेथड किसी फ़ंक्शन के कॉल होने के बाद स्टेट को लॉक कर देगा, केवल मेथड पूरा होने के बाद ही यह यूज़र को आगे रिक्वेस्ट करने की अनुमति देगा। यह मेथड स्टेट को भी अपडेट करेगा, जाँचने के लिए `isLocked` का उपयोग करें।

`lockRelease` प्रदर्शित करने का सबसे अच्छा उदाहरण एक लॉगिन स्क्रीन की कल्पना करना है जब यूज़र 'Login' पर टैप करता है। हम यूज़र को लॉगिन करने के लिए एक async कॉल करना चाहते हैं लेकिन हम नहीं चाहते कि मेथड कई बार कॉल हो क्योंकि इससे अवांछित अनुभव हो सकता है।

नीचे एक उदाहरण है।

```dart
class _LoginPageState extends NyState<LoginPage> {

  _login() async {
    await lockRelease('login_to_app', perform: () async {

      await Future.delayed(Duration(seconds: 4), () {
        print('Pretend to login...');
      });

    });
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
            Center(
              child: InkWell(
                onTap: _login,
                child: Text("Login"),
              ),
            )
          ],
        )
    );
  }
```

जब आप **_login** मेथड पर टैप करते हैं, तो यह मूल रिक्वेस्ट पूरी होने तक किसी भी आगे की रिक्वेस्ट को ब्लॉक कर देगा। `isLocked('login_to_app')` हेल्पर का उपयोग यह जाँचने के लिए किया जाता है कि बटन लॉक है या नहीं। ऊपर के उदाहरण में, आप देख सकते हैं कि हम इसका उपयोग यह निर्धारित करने के लिए करते हैं कि हमारे लोडिंग विजेट को कब दिखाना है।

<div id="is-locked"></div>

### isLocked

यह मेथड जाँचेगा कि [`lockRelease`](#lock-release) हेल्पर का उपयोग करके स्टेट लॉक है या नहीं।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
          ],
        )
    );
  }
```

<div id="view"></div>

### view

`view` मेथड का उपयोग पेज के लिए UI दिखाने के लिए किया जाता है।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
      return Scaffold(
          body: Center(
              child: Text("My Page")
          )
      );
  }
}
```

<div id="confirm-action"></div>

### confirmAction

`confirmAction` मेथड यूज़र को एक एक्शन कन्फर्म करने के लिए एक डायलॉग दिखाएगा।
यह मेथड तब उपयोगी है जब आप चाहते हैं कि यूज़र आगे बढ़ने से पहले एक एक्शन कन्फर्म करे।

उदाहरण

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

`showToastSuccess` मेथड यूज़र को एक सक्सेस टोस्ट नोटिफ़िकेशन दिखाएगा।

उदाहरण
``` dart
_login() {
    ...
    showToastSuccess(
        description: "You have successfully logged in"
    );
}
```

<div id="show-toast-oops"></div>

### showToastOops

`showToastOops` मेथड यूज़र को एक ऊप्स टोस्ट नोटिफ़िकेशन दिखाएगा।

उदाहरण
``` dart
_error() {
    ...
    showToastOops(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-danger"></div>

### showToastDanger

`showToastDanger` मेथड यूज़र को एक डेंजर टोस्ट नोटिफ़िकेशन दिखाएगा।

उदाहरण
``` dart
_error() {
    ...
    showToastDanger(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-info"></div>

### showToastInfo

`showToastInfo` मेथड यूज़र को एक इन्फो टोस्ट नोटिफ़िकेशन दिखाएगा।

उदाहरण
``` dart
_info() {
    ...
    showToastInfo(
        description: "Your account has been updated"
    );
}
```

<div id="show-toast-warning"></div>

### showToastWarning

`showToastWarning` मेथड यूज़र को एक वॉर्निंग टोस्ट नोटिफ़िकेशन दिखाएगा।

उदाहरण
``` dart
_warning() {
    ...
    showToastWarning(
        description: "Your account is about to expire"
    );
}
```

<div id="show-toast-sorry"></div>

### showToastSorry

`showToastSorry` मेथड यूज़र को एक सॉरी टोस्ट नोटिफ़िकेशन दिखाएगा।

उदाहरण
``` dart
_sorry() {
    ...
    showToastSorry(
        description: "Your account has been suspended"
    );
}
```

<div id="is-loading"></div>

### isLoading

`isLoading` मेथड जाँचेगा कि स्टेट लोड हो रहा है या नहीं।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    if (isLoading()) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: colors().primaryContent
        )
      )
    );
  }
```

<div id="after-load"></div>

### afterLoad

`afterLoad` मेथड का उपयोग स्टेट का 'लोडिंग' समाप्त होने तक एक लोडर दिखाने के लिए किया जा सकता है।

आप **loadingKey** पैरामीटर `afterLoad(child: () {}, loadingKey: 'home_data')` का उपयोग करके अन्य लोडिंग कुंजियाँ भी जाँच सकते हैं।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () {
    awaitData(perform: () async {
        await sleep(4);
        print('4 seconds after...');
    });
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterLoad(child: () {
          return Text("Loaded");
        })
    );
  }
```

<div id="after-not-locked"></div>

### afterNotLocked

`afterNotLocked` मेथड जाँचेगा कि स्टेट लॉक है या नहीं।

यदि स्टेट लॉक है तो यह [loading] विजेट दिखाएगा।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          alignment: Alignment.center,
          child: afterNotLocked('login', child: () {
            return MaterialButton(
              onPressed: () {
                login();
              },
              child: Text("Login"),
            );
          }),
        )
    );
  }

  login() async {
    await lockRelease('login', perform: () async {
      await sleep(4);
      print('4 seconds after...');
    });
  }
}
```

<div id="after-not-null"></div>

### afterNotNull

आप किसी वेरिएबल सेट होने तक लोडिंग विजेट दिखाने के लिए `afterNotNull` का उपयोग कर सकते हैं।

कल्पना करें कि आपको एक Future कॉल का उपयोग करके DB से यूज़र का अकाउंट फ़ेच करना है जिसमें 1-2 सेकंड लग सकते हैं, आप उस वैल्यू पर afterNotNull का उपयोग कर सकते हैं जब तक आपके पास डेटा न हो।

उदाहरण

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // example
    setState(() {});
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterNotNull(_user, child: () {
          return Text(_user!.firstName);
        })
    );
  }
```

<div id="set-loading"></div>

### setLoading

आप `setLoading` का उपयोग करके 'loading' स्टेट में बदल सकते हैं।

पहला पैरामीटर यह स्वीकार करता है कि यह लोड हो रहा है या नहीं इसके लिए एक `bool`, अगला पैरामीटर आपको लोडिंग स्टेट के लिए एक नाम सेट करने की अनुमति देता है, उदा. `setLoading(true, name: 'refreshing_content');`।

उदाहरण
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    setLoading(true, name: 'refreshing_content');

    await sleep(4);

    setLoading(false, name: 'refreshing_content');
  };

  @override
  Widget build(BuildContext context) {
    if (isLoading(name: 'refreshing_content')) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded")
    );
  }
```
