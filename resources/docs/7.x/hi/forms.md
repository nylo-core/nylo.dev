# फ़ॉर्म्स

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- शुरुआत करना
  - [फ़ॉर्म बनाना](#creating-forms "फ़ॉर्म बनाना")
  - [फ़ॉर्म प्रदर्शित करना](#displaying-a-form "फ़ॉर्म प्रदर्शित करना")
  - [फ़ॉर्म सबमिट करना](#submitting-a-form "फ़ॉर्म सबमिट करना")
- फ़ील्ड प्रकार
  - [टेक्स्ट फ़ील्ड्स](#text-fields "टेक्स्ट फ़ील्ड्स")
  - [नंबर फ़ील्ड्स](#number-fields "नंबर फ़ील्ड्स")
  - [पासवर्ड फ़ील्ड्स](#password-fields "पासवर्ड फ़ील्ड्स")
  - [ईमेल फ़ील्ड्स](#email-fields "ईमेल फ़ील्ड्स")
  - [URL फ़ील्ड्स](#url-fields "URL फ़ील्ड्स")
  - [टेक्स्ट एरिया फ़ील्ड्स](#text-area-fields "टेक्स्ट एरिया फ़ील्ड्स")
  - [फ़ोन नंबर फ़ील्ड्स](#phone-number-fields "फ़ोन नंबर फ़ील्ड्स")
  - [शब्द कैपिटलाइज़](#capitalize-words-fields "शब्द कैपिटलाइज़")
  - [वाक्य कैपिटलाइज़](#capitalize-sentences-fields "वाक्य कैपिटलाइज़")
  - [डेट फ़ील्ड्स](#date-fields "डेट फ़ील्ड्स")
  - [DateTime फ़ील्ड्स](#datetime-fields "DateTime फ़ील्ड्स")
  - [मास्क्ड इनपुट फ़ील्ड्स](#masked-input-fields "मास्क्ड इनपुट फ़ील्ड्स")
  - [करेंसी फ़ील्ड्स](#currency-fields "करेंसी फ़ील्ड्स")
  - [चेकबॉक्स फ़ील्ड्स](#checkbox-fields "चेकबॉक्स फ़ील्ड्स")
  - [स्विच बॉक्स फ़ील्ड्स](#switch-box-fields "स्विच बॉक्स फ़ील्ड्स")
  - [पिकर फ़ील्ड्स](#picker-fields "पिकर फ़ील्ड्स")
  - [रेडियो फ़ील्ड्स](#radio-fields "रेडियो फ़ील्ड्स")
  - [चिप फ़ील्ड्स](#chip-fields "चिप फ़ील्ड्स")
  - [स्लाइडर फ़ील्ड्स](#slider-fields "स्लाइडर फ़ील्ड्स")
  - [रेंज स्लाइडर फ़ील्ड्स](#range-slider-fields "रेंज स्लाइडर फ़ील्ड्स")
  - [कस्टम फ़ील्ड्स](#custom-fields "कस्टम फ़ील्ड्स")
  - [विजेट फ़ील्ड्स](#widget-fields "विजेट फ़ील्ड्स")
- [FormCollection](#form-collection "FormCollection")
- [फ़ॉर्म वैलिडेशन](#form-validation "फ़ॉर्म वैलिडेशन")
- [फ़ॉर्म डेटा प्रबंधन](#managing-form-data "फ़ॉर्म डेटा प्रबंधन")
  - [इनिशियल डेटा](#initial-data "इनिशियल डेटा")
  - [फ़ील्ड वैल्यू सेट करना](#setting-field-values "फ़ील्ड वैल्यू सेट करना")
  - [फ़ील्ड ऑप्शन्स सेट करना](#setting-field-options "फ़ील्ड ऑप्शन्स सेट करना")
  - [फ़ॉर्म डेटा पढ़ना](#reading-form-data "फ़ॉर्म डेटा पढ़ना")
  - [डेटा क्लियर करना](#clearing-data "डेटा क्लियर करना")
  - [फ़ील्ड्स अपडेट करना](#finding-and-updating-fields "फ़ील्ड्स अपडेट करना")
- [सबमिट बटन](#submit-button "सबमिट बटन")
- [फ़ॉर्म लेआउट](#form-layout "फ़ॉर्म लेआउट")
- [फ़ील्ड विज़िबिलिटी](#field-visibility "फ़ील्ड विज़िबिलिटी")
- [फ़ील्ड स्टाइलिंग](#field-styling "फ़ील्ड स्टाइलिंग")
- [NyFormWidget स्टैटिक मेथड्स](#ny-form-widget-static-methods "NyFormWidget स्टैटिक मेथड्स")
- [NyFormWidget कंस्ट्रक्टर रेफरेंस](#ny-form-widget-constructor-reference "NyFormWidget कंस्ट्रक्टर रेफरेंस")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [सभी फ़ील्ड प्रकार रेफरेंस](#all-field-types-reference "सभी फ़ील्ड प्रकार रेफरेंस")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 `NyFormWidget` के इर्द-गिर्द बना एक फ़ॉर्म सिस्टम प्रदान करता है। आपकी फ़ॉर्म क्लास `NyFormWidget` को एक्सटेंड करती है और **स्वयं** विजेट है -- किसी अलग रैपर की आवश्यकता नहीं। फ़ॉर्म्स बिल्ट-इन वैलिडेशन, कई फ़ील्ड प्रकार, स्टाइलिंग और डेटा प्रबंधन को सपोर्ट करते हैं।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## फ़ॉर्म बनाना

नया फ़ॉर्म बनाने के लिए Metro CLI का उपयोग करें:

``` bash
metro make:form LoginForm
```

यह `lib/app/forms/login_form.dart` बनाता है:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

फ़ॉर्म्स `NyFormWidget` को एक्सटेंड करते हैं और फ़ॉर्म फ़ील्ड्स डिफ़ाइन करने के लिए `fields()` मेथड को ओवरराइड करते हैं। प्रत्येक फ़ील्ड `Field.text()`, `Field.email()`, या `Field.password()` जैसे नेम्ड कंस्ट्रक्टर का उपयोग करता है। `static NyFormActions get actions` गेटर आपके ऐप में कहीं से भी फ़ॉर्म के साथ इंटरैक्ट करने का एक सुविधाजनक तरीका प्रदान करता है।


<div id="displaying-a-form"></div>

## फ़ॉर्म प्रदर्शित करना

चूँकि आपकी फ़ॉर्म क्लास `NyFormWidget` को एक्सटेंड करती है, यह **स्वयं** विजेट है। इसे सीधे अपने विजेट ट्री में उपयोग करें:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## फ़ॉर्म सबमिट करना

फ़ॉर्म सबमिट करने के तीन तरीके हैं:

### onSubmit और submitButton का उपयोग करना

फ़ॉर्म बनाते समय `onSubmit` और एक `submitButton` पास करें। {{ config('app.name') }} प्री-बिल्ट बटन प्रदान करता है जो सबमिट बटन के रूप में काम करते हैं:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

उपलब्ध बटन स्टाइल: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`।

### NyFormActions का उपयोग करना

कहीं से भी सबमिट करने के लिए `actions` गेटर का उपयोग करें:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### NyFormWidget.submit() स्टैटिक मेथड का उपयोग करना

कहीं से भी नाम से फ़ॉर्म सबमिट करें:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

सबमिट करने पर, फ़ॉर्म सभी फ़ील्ड्स को वैलिडेट करता है। यदि मान्य है, तो `onSuccess` को फ़ील्ड डेटा के `Map<String, dynamic>` के साथ कॉल किया जाता है (कीज़ फ़ील्ड नामों के snake_case वर्ज़न हैं)। यदि अमान्य है, तो डिफ़ॉल्ट रूप से टोस्ट एरर दिखाया जाता है और यदि प्रदान किया गया हो तो `onFailure` कॉल किया जाता है।


<div id="field-types"></div>

## फ़ील्ड प्रकार

{{ config('app.name') }} v7 `Field` क्लास पर नेम्ड कंस्ट्रक्टर्स के माध्यम से 22 फ़ील्ड प्रकार प्रदान करता है। सभी फ़ील्ड कंस्ट्रक्टर्स ये सामान्य पैरामीटर्स साझा करते हैं:

| पैरामीटर | प्रकार | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `key` | `String` | आवश्यक | फ़ील्ड पहचानकर्ता (पोज़िशनल) |
| `label` | `String?` | `null` | कस्टम डिस्प्ले लेबल (डिफ़ॉल्ट रूप से टाइटल केस में कुंजी) |
| `value` | `dynamic` | `null` | इनिशियल वैल्यू |
| `validator` | `FormValidator?` | `null` | वैलिडेशन नियम |
| `autofocus` | `bool` | `false` | लोड पर ऑटो-फ़ोकस |
| `dummyData` | `String?` | `null` | टेस्ट/डेवलपमेंट डेटा |
| `header` | `Widget?` | `null` | फ़ील्ड के ऊपर प्रदर्शित विजेट |
| `footer` | `Widget?` | `null` | फ़ील्ड के नीचे प्रदर्शित विजेट |
| `titleStyle` | `TextStyle?` | `null` | कस्टम लेबल टेक्स्ट स्टाइल |
| `hidden` | `bool` | `false` | फ़ील्ड छुपाएँ |
| `readOnly` | `bool?` | `null` | फ़ील्ड को रीड-ओनली बनाएँ |
| `style` | `FieldStyle?` | भिन्न | फ़ील्ड-विशिष्ट स्टाइल कॉन्फ़िगरेशन |
| `onChanged` | `Function(dynamic)?` | `null` | वैल्यू बदलने पर कॉलबैक |

<div id="text-fields"></div>

### टेक्स्ट फ़ील्ड्स

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

स्टाइल प्रकार: `FieldStyleTextField`

<div id="number-fields"></div>

### नंबर फ़ील्ड्स

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

`decimal` पैरामीटर यह नियंत्रित करता है कि दशमलव इनपुट की अनुमति है या नहीं। स्टाइल प्रकार: `FieldStyleTextField`

<div id="password-fields"></div>

### पासवर्ड फ़ील्ड्स

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

`viewable` पैरामीटर दिखाने/छुपाने का टॉगल जोड़ता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="email-fields"></div>

### ईमेल फ़ील्ड्स

``` dart
Field.email("Email", validator: FormValidator.email())
```

स्वचालित रूप से ईमेल कीबोर्ड प्रकार सेट करता है और व्हाइटस्पेस फ़िल्टर करता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="url-fields"></div>

### URL फ़ील्ड्स

``` dart
Field.url("Website", validator: FormValidator.url())
```

URL कीबोर्ड प्रकार सेट करता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="text-area-fields"></div>

### टेक्स्ट एरिया फ़ील्ड्स

``` dart
Field.textArea("Description")
```

मल्टी-लाइन टेक्स्ट इनपुट। स्टाइल प्रकार: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### फ़ोन नंबर फ़ील्ड्स

``` dart
Field.phoneNumber("Mobile Phone")
```

स्वचालित रूप से फ़ोन नंबर इनपुट को फ़ॉर्मेट करता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### शब्द कैपिटलाइज़

``` dart
Field.capitalizeWords("Full Name")
```

प्रत्येक शब्द के पहले अक्षर को कैपिटलाइज़ करता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### वाक्य कैपिटलाइज़

``` dart
Field.capitalizeSentences("Bio")
```

प्रत्येक वाक्य के पहले अक्षर को कैपिटलाइज़ करता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="date-fields"></div>

### डेट फ़ील्ड्स

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Disable the clear button
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Custom clear icon
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

डेट पिकर खोलता है। डिफ़ॉल्ट रूप से, फ़ील्ड एक क्लियर बटन दिखाता है जो उपयोगकर्ताओं को मान रीसेट करने देता है। इसे छिपाने के लिए `canClear: false` सेट करें, या आइकन बदलने के लिए `clearIconData` का उपयोग करें। स्टाइल प्रकार: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### DateTime फ़ील्ड्स

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

डेट और टाइम पिकर खोलता है। आप `firstDate`, `lastDate`, `dateFormat` और `initialPickerDateTime` को सीधे टॉप-लेवल पैरामीटर के रूप में सेट कर सकते हैं। स्टाइल प्रकार: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### मास्क्ड इनपुट फ़ील्ड्स

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

मास्क में `#` कैरेक्टर यूज़र इनपुट द्वारा बदल दिया जाता है। अनुमत कैरेक्टर्स को नियंत्रित करने के लिए `match` का उपयोग करें। जब `maskReturnValue` `true` है, तो रिटर्न की गई वैल्यू में मास्क फ़ॉर्मेटिंग शामिल होती है।

<div id="currency-fields"></div>

### करेंसी फ़ील्ड्स

``` dart
Field.currency("Price", currency: "usd")
```

`currency` पैरामीटर आवश्यक है और करेंसी फ़ॉर्मेट निर्धारित करता है। स्टाइल प्रकार: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### चेकबॉक्स फ़ील्ड्स

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

स्टाइल प्रकार: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### स्विच बॉक्स फ़ील्ड्स

``` dart
Field.switchBox("Enable Notifications")
```

स्टाइल प्रकार: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### पिकर फ़ील्ड्स

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

`options` पैरामीटर के लिए `FormCollection` आवश्यक है (रॉ लिस्ट नहीं)। विवरण के लिए [FormCollection](#form-collection) देखें। स्टाइल प्रकार: `FieldStylePicker`

#### लिस्ट टाइल स्टाइल

आप `PickerListTileStyle` का उपयोग करके पिकर के बॉटम शीट में आइटम्स की दिखावट को कस्टमाइज़ कर सकते हैं। डिफ़ॉल्ट रूप से, बॉटम शीट सादे टेक्स्ट टाइल्स दिखाता है। सिलेक्शन इंडिकेटर जोड़ने के लिए बिल्ट-इन प्रीसेट्स का उपयोग करें, या पूरी तरह से कस्टम बिल्डर प्रदान करें।

**रेडियो स्टाइल** — लीडिंग विजेट के रूप में रेडियो बटन आइकन दिखाता है:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// With a custom active color
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**चेकमार्क स्टाइल** — सिलेक्ट होने पर ट्रेलिंग विजेट के रूप में चेक आइकन दिखाता है:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**कस्टम बिल्डर** — प्रत्येक टाइल के विजेट पर पूर्ण नियंत्रण:

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

दोनों प्रीसेट स्टाइल `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` और `selectedTileColor` को भी सपोर्ट करते हैं:

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### रेडियो फ़ील्ड्स

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

`options` पैरामीटर के लिए `FormCollection` आवश्यक है। स्टाइल प्रकार: `FieldStyleRadio`

<div id="chip-fields"></div>

### चिप फ़ील्ड्स

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

चिप विजेट्स के माध्यम से मल्टी-सिलेक्शन की अनुमति देता है। `options` पैरामीटर के लिए `FormCollection` आवश्यक है। स्टाइल प्रकार: `FieldStyleChip`

<div id="slider-fields"></div>

### स्लाइडर फ़ील्ड्स

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

स्टाइल प्रकार: `FieldStyleSlider` -- `min`, `max`, `divisions`, रंग, वैल्यू डिस्प्ले, और अधिक कॉन्फ़िगर करें।

<div id="range-slider-fields"></div>

### रेंज स्लाइडर फ़ील्ड्स

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

`RangeValues` ऑब्जेक्ट रिटर्न करता है। स्टाइल प्रकार: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### कस्टम फ़ील्ड्स

अपना स्वयं का स्टेटफुल विजेट प्रदान करने के लिए `Field.custom()` का उपयोग करें:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

`child` पैरामीटर के लिए `NyFieldStatefulWidget` को एक्सटेंड करने वाला विजेट आवश्यक है। यह आपको फ़ील्ड की रेंडरिंग और व्यवहार पर पूर्ण नियंत्रण देता है।

<div id="widget-fields"></div>

### विजेट फ़ील्ड्स

फ़ॉर्म के अंदर कोई भी विजेट एम्बेड करने के लिए `Field.widget()` का उपयोग करें बिना इसे फ़ॉर्म फ़ील्ड बनाए:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

विजेट फ़ील्ड्स वैलिडेशन या डेटा कलेक्शन में भाग नहीं लेते। वे पूरी तरह से लेआउट के लिए हैं।


<div id="form-collection"></div>

## FormCollection

पिकर, रेडियो, और चिप फ़ील्ड्स को अपने ऑप्शन्स के लिए `FormCollection` की आवश्यकता होती है। `FormCollection` विभिन्न ऑप्शन फ़ॉर्मेट्स को हैंडल करने के लिए एक एकीकृत इंटरफ़ेस प्रदान करता है।

### FormCollection बनाना

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` डेटा फ़ॉर्मेट को स्वचालित रूप से पहचानता है और उचित कंस्ट्रक्टर को डेलीगेट करता है।

### FormOption

`FormCollection` में प्रत्येक ऑप्शन एक `FormOption` है जिसमें `value` और `label` प्रॉपर्टीज़ होती हैं:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### ऑप्शन्स क्वेरी करना

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## फ़ॉर्म वैलिडेशन

`FormValidator` के साथ `validator` पैरामीटर का उपयोग करके किसी भी फ़ील्ड में वैलिडेशन जोड़ें:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

जब फ़ॉर्म सबमिट किया जाता है, तो सभी वैलिडेटर्स जाँचे जाते हैं। यदि कोई विफल होता है, तो पहले एरर संदेश के साथ टोस्ट एरर दिखाया जाता है और `onFailure` कॉलबैक कॉल किया जाता है।

**यह भी देखें:** उपलब्ध वैलिडेटर्स की पूरी सूची के लिए [वैलिडेशन](/docs/7.x/validation#validation-rules) पेज देखें।


<div id="managing-form-data"></div>

## फ़ॉर्म डेटा प्रबंधन

<div id="initial-data"></div>

### इनिशियल डेटा

फ़ॉर्म पर इनिशियल डेटा सेट करने के दो तरीके हैं।

**ऑप्शन 1: अपनी फ़ॉर्म क्लास में `init` गेटर को ओवरराइड करें**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

`init` गेटर सिंक्रोनस `Map` या एसिंक `Future<Map>` रिटर्न कर सकता है। कीज़ को snake_case नॉर्मलाइज़ेशन का उपयोग करके फ़ील्ड नामों से मैच किया जाता है, इसलिए `"First Name"` कुंजी `"First Name"` वाले फ़ील्ड से मैप होती है।

#### init में `define()` का उपयोग

`define()` हेल्पर का उपयोग तब करें जब आपको `init` में किसी फ़ील्ड के लिए **ऑप्शंस** (या वैल्यू और ऑप्शंस दोनों) सेट करने की आवश्यकता हो। यह पिकर, चिप और रेडियो फ़ील्ड्स के लिए उपयोगी है जहां ऑप्शंस API या अन्य एसिंक्रोनस स्रोत से आते हैं।

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` दो नामित पैरामीटर स्वीकार करता है:

| पैरामीटर | विवरण |
|-----------|-------------|
| `value` | फ़ील्ड का प्रारंभिक मान |
| `options` | पिकर, चिप या रेडियो फ़ील्ड्स के लिए ऑप्शंस |

``` dart
// Set only options (no initial value)
"Category": define(options: categories),

// Set only an initial value
"Price": define(value: "100"),

// Set both a value and options
"Country": define(value: "us", options: countries),

// Plain values still work for simple fields
"Name": "John",
```

`define()` को पास किए गए ऑप्शंस `List`, `Map` या `FormCollection` हो सकते हैं। अप्लाई करते समय ये स्वचालित रूप से `FormCollection` में परिवर्तित हो जाते हैं।

**ऑप्शन 2: फ़ॉर्म विजेट में `initialData` पास करें**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### फ़ील्ड वैल्यू सेट करना

कहीं से भी फ़ील्ड वैल्यू सेट करने के लिए `NyFormActions` का उपयोग करें:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### फ़ील्ड ऑप्शन्स सेट करना

पिकर, चिप, या रेडियो फ़ील्ड्स पर ऑप्शन्स को डायनामिक रूप से अपडेट करें:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### फ़ॉर्म डेटा पढ़ना

फ़ॉर्म डेटा `onSubmit` कॉलबैक के माध्यम से फ़ॉर्म सबमिट होने पर एक्सेस किया जाता है, या रियल-टाइम अपडेट के लिए `onChanged` कॉलबैक के माध्यम से:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### डेटा क्लियर करना

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### फ़ील्ड्स अपडेट करना

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## सबमिट बटन

फ़ॉर्म बनाते समय एक `submitButton` और `onSubmit` कॉलबैक पास करें:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

`submitButton` स्वचालित रूप से फ़ॉर्म फ़ील्ड्स के नीचे प्रदर्शित होता है। आप किसी भी बिल्ट-इन बटन स्टाइल या कस्टम विजेट का उपयोग कर सकते हैं।

आप किसी भी विजेट को सबमिट बटन के रूप में भी उपयोग कर सकते हैं, इसे `footer` के रूप में पास करके:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## फ़ॉर्म लेआउट

फ़ील्ड्स को एक `List` में रैप करके साइड-बाय-साइड रखें:

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

`List` में फ़ील्ड्स को समान `Expanded` चौड़ाई के साथ `Row` में रेंडर किया जाता है। फ़ील्ड्स के बीच की स्पेसिंग `NyFormWidget` पर `crossAxisSpacing` पैरामीटर द्वारा नियंत्रित होती है।


<div id="field-visibility"></div>

## फ़ील्ड विज़िबिलिटी

`Field` पर `hide()` और `show()` मेथड्स का उपयोग करके प्रोग्रामेटिक रूप से फ़ील्ड्स दिखाएँ या छुपाएँ। आप अपनी फ़ॉर्म क्लास के अंदर या `onChanged` कॉलबैक के माध्यम से फ़ील्ड्स तक पहुँच सकते हैं:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

छुपे हुए फ़ील्ड्स UI में रेंडर नहीं होते लेकिन फ़ॉर्म की फ़ील्ड सूची में अभी भी मौजूद रहते हैं।


<div id="field-styling"></div>

## फ़ील्ड स्टाइलिंग

प्रत्येक फ़ील्ड प्रकार के लिए स्टाइलिंग हेतु एक संबंधित `FieldStyle` सबक्लास है:

| फ़ील्ड प्रकार | स्टाइल क्लास |
|------------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

किसी भी फ़ील्ड के `style` पैरामीटर में स्टाइल ऑब्जेक्ट पास करें:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## NyFormWidget स्टैटिक मेथड्स

`NyFormWidget` आपके ऐप में कहीं से भी नाम से फ़ॉर्म्स के साथ इंटरैक्ट करने के लिए स्टैटिक मेथड्स प्रदान करता है:

| मेथड | विवरण |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | नाम से फ़ॉर्म सबमिट करें |
| `NyFormWidget.stateRefresh(name)` | फ़ॉर्म की UI स्टेट रिफ्रेश करें |
| `NyFormWidget.stateSetValue(name, key, value)` | फ़ॉर्म नाम से फ़ील्ड वैल्यू सेट करें |
| `NyFormWidget.stateSetOptions(name, key, options)` | फ़ॉर्म नाम से फ़ील्ड ऑप्शन्स सेट करें |
| `NyFormWidget.stateClearData(name)` | फ़ॉर्म नाम से सभी फ़ील्ड्स क्लियर करें |
| `NyFormWidget.stateRefreshForm(name)` | फ़ॉर्म फ़ील्ड्स रिफ्रेश करें (`fields()` को पुनः कॉल करता है) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **सुझाव:** इन स्टैटिक मेथड्स को सीधे कॉल करने के बजाय `NyFormActions` (नीचे देखें) का उपयोग करें -- यह अधिक संक्षिप्त और कम त्रुटि-प्रवण है।


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget कंस्ट्रक्टर रेफरेंस

`NyFormWidget` को एक्सटेंड करते समय, ये कंस्ट्रक्टर पैरामीटर्स हैं जो आप पास कर सकते हैं:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

`onChanged` कॉलबैक बदले गए `Field` और उसकी नई वैल्यू प्राप्त करता है:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` आपके ऐप में कहीं से भी फ़ॉर्म के साथ इंटरैक्ट करने का एक सुविधाजनक तरीका प्रदान करता है। इसे अपनी फ़ॉर्म क्लास में स्टैटिक गेटर के रूप में डिफ़ाइन करें:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### उपलब्ध एक्शन्स

| मेथड | विवरण |
|--------|-------------|
| `actions.updateField(key, value)` | फ़ील्ड की वैल्यू सेट करें |
| `actions.clearField(key)` | विशिष्ट फ़ील्ड क्लियर करें |
| `actions.clear()` | सभी फ़ील्ड्स क्लियर करें |
| `actions.refresh()` | फ़ॉर्म की UI स्टेट रिफ्रेश करें |
| `actions.refreshForm()` | `fields()` को पुनः कॉल करें और पुनर्निर्माण करें |
| `actions.setOptions(key, options)` | पिकर/चिप/रेडियो फ़ील्ड्स पर ऑप्शन्स सेट करें |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | वैलिडेशन के साथ सबमिट करें |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget ओवरराइड्स

आपकी `NyFormWidget` सबक्लास में ओवरराइड किए जा सकने वाले मेथड्स:

| ओवरराइड | विवरण |
|----------|-------------|
| `fields()` | फ़ॉर्म फ़ील्ड्स डिफ़ाइन करें (आवश्यक) |
| `init` | इनिशियल डेटा प्रदान करें (सिंक या एसिंक) |
| `onChange(field, data)` | आंतरिक रूप से फ़ील्ड परिवर्तनों को हैंडल करें |


<div id="all-field-types-reference"></div>

## सभी फ़ील्ड प्रकार रेफरेंस

| कंस्ट्रक्टर | मुख्य पैरामीटर्स | विवरण |
|-------------|----------------|-------------|
| `Field.text()` | -- | स्टैंडर्ड टेक्स्ट इनपुट |
| `Field.email()` | -- | कीबोर्ड प्रकार के साथ ईमेल इनपुट |
| `Field.password()` | `viewable` | वैकल्पिक विज़िबिलिटी टॉगल के साथ पासवर्ड |
| `Field.number()` | `decimal` | न्यूमेरिक इनपुट, वैकल्पिक दशमलव |
| `Field.currency()` | `currency` (आवश्यक) | करेंसी-फ़ॉर्मेटेड इनपुट |
| `Field.capitalizeWords()` | -- | टाइटल केस टेक्स्ट इनपुट |
| `Field.capitalizeSentences()` | -- | सेंटेंस केस टेक्स्ट इनपुट |
| `Field.textArea()` | -- | मल्टी-लाइन टेक्स्ट इनपुट |
| `Field.phoneNumber()` | -- | ऑटो-फ़ॉर्मेटेड फ़ोन नंबर |
| `Field.url()` | -- | कीबोर्ड प्रकार के साथ URL इनपुट |
| `Field.mask()` | `mask` (आवश्यक), `match`, `maskReturnValue` | मास्क्ड टेक्स्ट इनपुट |
| `Field.date()` | -- | डेट पिकर |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | डेट और टाइम पिकर |
| `Field.checkbox()` | -- | बूलियन चेकबॉक्स |
| `Field.switchBox()` | -- | बूलियन टॉगल स्विच |
| `Field.picker()` | `options` (आवश्यक `FormCollection`) | सूची से सिंगल सिलेक्शन |
| `Field.radio()` | `options` (आवश्यक `FormCollection`) | रेडियो बटन ग्रुप |
| `Field.chips()` | `options` (आवश्यक `FormCollection`) | मल्टी-सिलेक्ट चिप्स |
| `Field.slider()` | -- | सिंगल वैल्यू स्लाइडर |
| `Field.rangeSlider()` | -- | रेंज वैल्यू स्लाइडर |
| `Field.custom()` | `child` (आवश्यक `NyFieldStatefulWidget`) | कस्टम स्टेटफुल विजेट |
| `Field.widget()` | `child` (आवश्यक `Widget`) | कोई भी विजेट एम्बेड करें (नॉन-फ़ील्ड) |

