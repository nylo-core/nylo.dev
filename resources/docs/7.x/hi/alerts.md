# Alerts

---

<a name="section-1"></a>
- [परिचय](#introduction "Introduction")
- [बुनियादी उपयोग](#basic-usage "Basic Usage")
- [बिल्ट-इन स्टाइल](#built-in-styles "Built-in Styles")
- [पेज से अलर्ट दिखाना](#from-pages "Showing Alerts from Pages")
- [कंट्रोलर से अलर्ट दिखाना](#from-controllers "Showing Alerts from Controllers")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [पोज़िशनिंग](#positioning "Positioning")
- [कस्टम टोस्ट स्टाइल](#custom-styles "Custom Toast Styles")
  - [स्टाइल रजिस्टर करना](#registering-styles "Registering Styles")
  - [स्टाइल फैक्ट्री बनाना](#creating-a-style-factory "Creating a Style Factory")
- [AlertTab](#alert-tab "AlertTab")
- [उदाहरण](#examples "Practical Examples")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} उपयोगकर्ताओं को अलर्ट प्रदर्शित करने के लिए एक टोस्ट नोटिफ़िकेशन सिस्टम प्रदान करता है। यह चार बिल्ट-इन स्टाइल के साथ आता है -- **success**, **warning**, **info**, और **danger** -- और रजिस्ट्री पैटर्न के माध्यम से कस्टम स्टाइल का समर्थन करता है।

अलर्ट पेज, कंट्रोलर, या कहीं से भी ट्रिगर किए जा सकते हैं जहाँ आपके पास `BuildContext` है।

<div id="basic-usage"></div>

## बुनियादी उपयोग

किसी भी `NyState` पेज में कन्वीनिएंस मेथड का उपयोग करके टोस्ट नोटिफ़िकेशन दिखाएँ:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

या स्टाइल ID के साथ ग्लोबल फंक्शन का उपयोग करें:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## बिल्ट-इन स्टाइल

{{ config('app.name') }} चार डिफ़ॉल्ट टोस्ट स्टाइल रजिस्टर करता है:

| स्टाइल ID | आइकन | रंग | डिफ़ॉल्ट शीर्षक |
|----------|------|-------|---------------|
| `success` | चेकमार्क | हरा | Success |
| `warning` | विस्मयादिबोधक | नारंगी | Warning |
| `info` | इंफो आइकन | टील | Info |
| `danger` | चेतावनी आइकन | लाल | Error |

ये `lib/config/toast_notification.dart` में कॉन्फ़िगर किए गए हैं:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## पेज से अलर्ट दिखाना

`NyState` या `NyBaseState` को एक्सटेंड करने वाले किसी भी पेज में, इन कन्वीनिएंस मेथड का उपयोग करें:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### सामान्य टोस्ट मेथड

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## कंट्रोलर से अलर्ट दिखाना

`NyController` को एक्सटेंड करने वाले कंट्रोलर में समान कन्वीनिएंस मेथड उपलब्ध हैं:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

उपलब्ध मेथड: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

ग्लोबल `showToastNotification()` फंक्शन कहीं से भी टोस्ट प्रदर्शित करता है जहाँ आपके पास `BuildContext` है:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### पैरामीटर

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | आवश्यक | बिल्ड कॉन्टेक्स्ट |
| `id` | `String` | `'success'` | टोस्ट स्टाइल ID |
| `title` | `String?` | null | डिफ़ॉल्ट शीर्षक को ओवरराइड करें |
| `description` | `String?` | null | विवरण टेक्स्ट |
| `duration` | `Duration?` | null | टोस्ट कितनी देर प्रदर्शित हो |
| `position` | `ToastNotificationPosition?` | null | स्क्रीन पर कहाँ दिखे |
| `action` | `VoidCallback?` | null | टैप कॉलबैक |
| `onDismiss` | `VoidCallback?` | null | डिसमिस कॉलबैक |
| `onShow` | `VoidCallback?` | null | शो कॉलबैक |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` टोस्ट नोटिफ़िकेशन के सभी डेटा को समाहित करता है:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### प्रॉपर्टीज़

| प्रॉपर्टी | टाइप | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | आइकन विजेट |
| `title` | `String` | `''` | शीर्षक टेक्स्ट |
| `style` | `String` | `''` | स्टाइल आइडेंटिफ़ायर |
| `description` | `String` | `''` | विवरण टेक्स्ट |
| `color` | `Color?` | null | आइकन सेक्शन का बैकग्राउंड रंग |
| `action` | `VoidCallback?` | null | टैप कॉलबैक |
| `dismiss` | `VoidCallback?` | null | डिसमिस बटन कॉलबैक |
| `onDismiss` | `VoidCallback?` | null | ऑटो/मैन्युअल डिसमिस कॉलबैक |
| `onShow` | `VoidCallback?` | null | दृश्यमान होने का कॉलबैक |
| `duration` | `Duration` | 5 सेकंड | प्रदर्शन अवधि |
| `position` | `ToastNotificationPosition` | `top` | स्क्रीन पोज़िशन |
| `metaData` | `Map<String, dynamic>?` | null | कस्टम मेटाडेटा |

### copyWith

`ToastMeta` की संशोधित कॉपी बनाएँ:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## पोज़िशनिंग

टोस्ट स्क्रीन पर कहाँ दिखे यह नियंत्रित करें:

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## कस्टम टोस्ट स्टाइल

<div id="registering-styles"></div>

### स्टाइल रजिस्टर करना

अपने `AppProvider` में कस्टम स्टाइल रजिस्टर करें:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

या किसी भी समय स्टाइल जोड़ें:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

फिर इसका उपयोग करें:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### स्टाइल फैक्ट्री बनाना

`ToastNotification.style()` एक `ToastStyleFactory` बनाता है:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `icon` | `Widget` | टोस्ट के लिए आइकन विजेट |
| `color` | `Color` | आइकन सेक्शन का बैकग्राउंड रंग |
| `defaultTitle` | `String` | कोई शीर्षक न होने पर दिखाया जाने वाला शीर्षक |
| `position` | `ToastNotificationPosition?` | डिफ़ॉल्ट पोज़िशन |
| `duration` | `Duration?` | डिफ़ॉल्ट अवधि |
| `builder` | `Widget Function(ToastMeta)?` | पूर्ण नियंत्रण के लिए कस्टम विजेट बिल्डर |

### पूर्ण कस्टम बिल्डर

टोस्ट विजेट पर पूर्ण नियंत्रण के लिए:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` नेविगेशन टैब में नोटिफ़िकेशन इंडिकेटर जोड़ने के लिए एक बैज विजेट है। यह एक बैज प्रदर्शित करता है जिसे टॉगल किया जा सकता है और वैकल्पिक रूप से स्टोरेज में सहेजा जा सकता है।

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### पैरामीटर

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `state` | `String` | आवश्यक | ट्रैकिंग के लिए स्टेट का नाम |
| `alertEnabled` | `bool?` | null | बैज दिखे या नहीं |
| `rememberAlert` | `bool?` | `true` | बैज स्टेट को स्टोरेज में सहेजें |
| `icon` | `Widget?` | null | टैब आइकन |
| `backgroundColor` | `Color?` | null | टैब बैकग्राउंड |
| `textColor` | `Color?` | null | बैज टेक्स्ट रंग |
| `alertColor` | `Color?` | null | बैज बैकग्राउंड रंग |
| `smallSize` | `double?` | null | छोटे बैज का आकार |
| `largeSize` | `double?` | null | बड़े बैज का आकार |
| `textStyle` | `TextStyle?` | null | बैज टेक्स्ट स्टाइल |
| `padding` | `EdgeInsetsGeometry?` | null | बैज पैडिंग |
| `alignment` | `Alignment?` | null | बैज अलाइनमेंट |
| `offset` | `Offset?` | null | बैज ऑफ़सेट |
| `isLabelVisible` | `bool?` | `true` | बैज लेबल दिखाएँ |

### फैक्ट्री कंस्ट्रक्टर

`NavigationTab` से बनाएँ:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## उदाहरण

### सहेजने के बाद सफलता अलर्ट

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### एक्शन के साथ इंटरएक्टिव टोस्ट

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### नीचे स्थित चेतावनी

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
