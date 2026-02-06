# मोडल

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [मोडल बनाना](#creating-a-modal "मोडल बनाना")
- [बुनियादी उपयोग](#basic-usage "बुनियादी उपयोग")
- [मोडल बनाना](#creating-a-modal "मोडल बनाना")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [पैरामीटर](#parameters "पैरामीटर")
  - [एक्शन](#actions "एक्शन")
  - [हेडर](#header "हेडर")
  - [बंद करने का बटन](#close-button "बंद करने का बटन")
  - [कस्टम डेकोरेशन](#custom-decoration "कस्टम डेकोरेशन")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [उदाहरण](#examples "व्यावहारिक उदाहरण")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} **बॉटम शीट मोडल** पर आधारित एक मोडल सिस्टम प्रदान करता है।

`BottomSheetModal` क्लास आपको एक्शन, हेडर, बंद करने के बटन, और कस्टम स्टाइलिंग के साथ कंटेंट ओवरले प्रदर्शित करने के लिए एक लचीला API देता है।

मोडल इनके लिए उपयोगी हैं:
- पुष्टिकरण डायलॉग (उदा., लॉगआउट, डिलीट)
- त्वरित इनपुट फ़ॉर्म
- कई विकल्पों वाली एक्शन शीट
- सूचनात्मक ओवरले

<div id="creating-a-modal"></div>

## मोडल बनाना

आप Metro CLI का उपयोग करके नया मोडल बना सकते हैं:

``` bash
metro make:bottom_sheet_modal payment_options
```

यह दो चीज़ें बनाता है:

1. **एक मोडल कंटेंट विजेट** `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart` पर:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **एक स्टैटिक मेथड** आपकी `BottomSheetModal` क्लास में `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart` में जोड़ा जाता है:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

इसके बाद आप मोडल को कहीं से भी कॉल कर सकते हैं:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

यदि समान नाम का मोडल पहले से मौजूद है, तो इसे ओवरराइट करने के लिए `--force` फ़्लैग का उपयोग करें:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## बुनियादी उपयोग

`BottomSheetModal` का उपयोग करके मोडल प्रदर्शित करें:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## मोडल बनाना

अनुशंसित पैटर्न एक `BottomSheetModal` क्लास बनाना है जिसमें प्रत्येक मोडल प्रकार के लिए स्टैटिक मेथड हों। बॉयलरप्लेट यह संरचना प्रदान करता है:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

इसे कहीं से भी कॉल करें:

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` मोडल प्रदर्शित करने का मुख्य मेथड है।

<div id="parameters"></div>

### पैरामीटर

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | आवश्यक | मोडल के लिए बिल्ड कॉन्टेक्स्ट |
| `child` | `Widget` | आवश्यक | मुख्य कंटेंट विजेट |
| `actionsRow` | `List<Widget>` | `[]` | क्षैतिज पंक्ति में प्रदर्शित एक्शन विजेट |
| `actionsColumn` | `List<Widget>` | `[]` | लंबवत रूप से प्रदर्शित एक्शन विजेट |
| `height` | `double?` | null | मोडल की निश्चित ऊँचाई |
| `header` | `Widget?` | null | शीर्ष पर हेडर विजेट |
| `useSafeArea` | `bool` | `true` | कंटेंट को SafeArea में लपेटें |
| `isScrollControlled` | `bool` | `false` | मोडल को स्क्रॉल करने योग्य बनाएँ |
| `showCloseButton` | `bool` | `false` | X बंद करने का बटन दिखाएँ |
| `headerPadding` | `EdgeInsets?` | null | हेडर मौजूद होने पर पैडिंग |
| `backgroundColor` | `Color?` | null | मोडल का बैकग्राउंड रंग |
| `showHandle` | `bool` | `true` | शीर्ष पर ड्रैग हैंडल दिखाएँ |
| `closeButtonColor` | `Color?` | null | बंद बटन का बैकग्राउंड रंग |
| `closeButtonIconColor` | `Color?` | null | बंद बटन का आइकन रंग |
| `modalDecoration` | `BoxDecoration?` | null | मोडल कंटेनर के लिए कस्टम डेकोरेशन |
| `handleColor` | `Color?` | null | ड्रैग हैंडल का रंग |

<div id="actions"></div>

### एक्शन

एक्शन मोडल के नीचे प्रदर्शित बटन हैं।

**पंक्ति एक्शन** एक-दूसरे के बगल में रखे जाते हैं, प्रत्येक को समान स्थान मिलता है:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**कॉलम एक्शन** लंबवत रूप से एक के ऊपर एक रखे जाते हैं:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### हेडर

मुख्य कंटेंट के ऊपर हेडर जोड़ें:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### बंद करने का बटन

ऊपरी-दाएँ कोने में बंद करने का बटन प्रदर्शित करें:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### कस्टम डेकोरेशन

मोडल कंटेनर की दिखावट को कस्टमाइज़ करें:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` फ़ॉर्म पिकर और अन्य कॉम्पोनेंट द्वारा उपयोग किए जाने वाले बॉटम शीट मोडल की दिखावट को कॉन्फ़िगर करता है:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | मोडल का बैकग्राउंड रंग |
| `barrierColor` | `NyColor?` | मोडल के पीछे ओवरले का रंग |
| `useRootNavigator` | `bool` | रूट नेविगेटर का उपयोग करें (डिफ़ॉल्ट: `false`) |
| `routeSettings` | `RouteSettings?` | मोडल के लिए रूट सेटिंग्स |
| `titleStyle` | `TextStyle?` | शीर्षक टेक्स्ट की स्टाइल |
| `itemStyle` | `TextStyle?` | सूची आइटम टेक्स्ट की स्टाइल |
| `clearButtonStyle` | `TextStyle?` | क्लियर बटन टेक्स्ट की स्टाइल |

<div id="examples"></div>

## उदाहरण

### पुष्टिकरण मोडल

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### स्क्रॉल करने योग्य कंटेंट मोडल

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### एक्शन शीट

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
