# InputField

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
- [वैलिडेशन](#validation "वैलिडेशन")
- वेरिएंट्स
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [इनपुट मास्किंग](#masking "इनपुट मास्किंग")
- [हेडर और फुटर](#header-footer "हेडर और फुटर")
- [क्लियरेबल इनपुट](#clearable "क्लियरेबल इनपुट")
- [स्टेट मैनेजमेंट](#state-management "स्टेट मैनेजमेंट")
- [पैरामीटर्स](#parameters "पैरामीटर्स")


<div id="introduction"></div>

## परिचय

**InputField** विजेट {{ config('app.name') }} का एन्हांस्ड टेक्स्ट फ़ील्ड है जिसमें निम्नलिखित के लिए बिल्ट-इन सपोर्ट है:

- कस्टमाइज़ेबल एरर मैसेज के साथ वैलिडेशन
- पासवर्ड विज़िबिलिटी टॉगल
- इनपुट मास्किंग (फ़ोन नंबर, क्रेडिट कार्ड, आदि)
- हेडर और फुटर विजेट्स
- क्लियरेबल इनपुट
- स्टेट मैनेजमेंट इंटीग्रेशन
- डेवलपमेंट के लिए डमी डेटा

<div id="basic-usage"></div>

## बेसिक उपयोग

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## वैलिडेशन

वैलिडेशन नियम जोड़ने के लिए `formValidator` पैरामीटर का उपयोग करें:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

जब यूज़र फ़ोकस को फ़ील्ड से हटाता है तो यह वैलिडेट करेगा।

### कस्टम वैलिडेशन हैंडलर

वैलिडेशन एरर्स को प्रोग्रामैटिक रूप से हैंडल करें:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

सभी उपलब्ध वैलिडेशन नियमों के लिए [Validation](/docs/7.x/validation) डॉक्यूमेंटेशन देखें।

<div id="password"></div>

## InputField.password

ऑब्स्क्योर्ड टेक्स्ट और विज़िबिलिटी टॉगल के साथ पहले से कॉन्फ़िगर्ड पासवर्ड फ़ील्ड:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### पासवर्ड विज़िबिलिटी कस्टमाइज़ करना

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

ईमेल कीबोर्ड और ऑटोफोकस के साथ पहले से कॉन्फ़िगर्ड ईमेल फ़ील्ड:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

हर शब्द के पहले अक्षर को ऑटो-कैपिटलाइज़ करता है:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## इनपुट मास्किंग

फ़ॉर्मेटेड डेटा जैसे फ़ोन नंबर या क्रेडिट कार्ड के लिए इनपुट मास्क लागू करें:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| पैरामीटर | विवरण |
|-----------|-------------|
| `mask` | `#` को प्लेसहोल्डर के रूप में उपयोग करने वाला मास्क पैटर्न |
| `maskMatch` | मान्य इनपुट कैरेक्टर्स के लिए रीजेक्स पैटर्न |
| `maskedReturnValue` | यदि true है, तो फ़ॉर्मेटेड वैल्यू लौटाता है; यदि false है, तो रॉ इनपुट लौटाता है |

<div id="header-footer"></div>

## हेडर और फुटर

इनपुट फ़ील्ड के ऊपर या नीचे विजेट्स जोड़ें:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## क्लियरेबल इनपुट

फ़ील्ड को जल्दी खाली करने के लिए एक क्लियर बटन जोड़ें:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## स्टेट मैनेजमेंट

अपने इनपुट फ़ील्ड को प्रोग्रामैटिक रूप से नियंत्रित करने के लिए एक स्टेट नाम दें:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### स्टेट एक्शन्स

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## पैरामीटर्स

### सामान्य पैरामीटर्स

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | आवश्यक | एडिट किए जा रहे टेक्स्ट को नियंत्रित करता है |
| `labelText` | `String?` | - | फ़ील्ड के ऊपर दिखाया जाने वाला लेबल |
| `hintText` | `String?` | - | प्लेसहोल्डर टेक्स्ट |
| `formValidator` | `FormValidator?` | - | वैलिडेशन नियम |
| `validateOnFocusChange` | `bool` | `true` | फोकस बदलने पर वैलिडेट करें |
| `obscureText` | `bool` | `false` | इनपुट छिपाएँ (पासवर्ड के लिए) |
| `keyboardType` | `TextInputType` | `text` | कीबोर्ड प्रकार |
| `autoFocus` | `bool` | `false` | बिल्ड पर ऑटो-फोकस |
| `readOnly` | `bool` | `false` | फ़ील्ड को रीड-ओनली बनाएँ |
| `enabled` | `bool?` | - | फ़ील्ड सक्षम/अक्षम करें |
| `maxLines` | `int?` | `1` | अधिकतम लाइनें |
| `maxLength` | `int?` | - | अधिकतम कैरेक्टर्स |

### स्टाइलिंग पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | फ़ील्ड बैकग्राउंड रंग |
| `borderRadius` | `BorderRadius?` | बॉर्डर रेडियस |
| `border` | `InputBorder?` | डिफ़ॉल्ट बॉर्डर |
| `focusedBorder` | `InputBorder?` | फोकस होने पर बॉर्डर |
| `enabledBorder` | `InputBorder?` | सक्षम होने पर बॉर्डर |
| `contentPadding` | `EdgeInsetsGeometry?` | आंतरिक पैडिंग |
| `style` | `TextStyle?` | टेक्स्ट स्टाइल |
| `labelStyle` | `TextStyle?` | लेबल टेक्स्ट स्टाइल |
| `hintStyle` | `TextStyle?` | हिंट टेक्स्ट स्टाइल |
| `prefixIcon` | `Widget?` | इनपुट से पहले आइकन |

### मास्किंग पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `mask` | `String?` | मास्क पैटर्न (जैसे "###-####") |
| `maskMatch` | `String?` | मान्य कैरेक्टर्स के लिए रीजेक्स |
| `maskedReturnValue` | `bool?` | मास्क्ड या रॉ वैल्यू लौटाएँ |

### फ़ीचर पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `header` | `Widget?` | फ़ील्ड के ऊपर विजेट |
| `footer` | `Widget?` | फ़ील्ड के नीचे विजेट |
| `clearable` | `bool?` | क्लियर बटन दिखाएँ |
| `clearIcon` | `Widget?` | कस्टम क्लियर आइकन |
| `passwordVisible` | `bool?` | पासवर्ड टॉगल दिखाएँ |
| `passwordViewable` | `bool?` | पासवर्ड विज़िबिलिटी टॉगल की अनुमति दें |
| `dummyData` | `String?` | डेवलपमेंट के लिए फ़ेक डेटा |
| `stateName` | `String?` | स्टेट मैनेजमेंट के लिए नाम |
| `onChanged` | `Function(String)?` | वैल्यू बदलने पर कॉल होता है |
