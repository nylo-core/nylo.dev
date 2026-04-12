# वैलिडेशन

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- बेसिक्स
  - [check() के साथ डेटा वैलिडेट करना](#validating-data "check() के साथ डेटा वैलिडेट करना")
  - [वैलिडेशन रिज़ल्ट्स](#validation-results "वैलिडेशन रिज़ल्ट्स")
- FormValidator
  - [FormValidator का उपयोग](#using-form-validator "FormValidator का उपयोग")
  - [FormValidator नेम्ड कंस्ट्रक्टर्स](#form-validator-named-constructors "FormValidator नेम्ड कंस्ट्रक्टर्स")
  - [वैलिडेशन नियम चेन करना](#chaining-validation-rules "वैलिडेशन नियम चेन करना")
  - [नलेबल फ़ील्ड्स](#nullable-fields "नलेबल फ़ील्ड्स")
  - [कस्टम वैलिडेशन](#custom-validation "कस्टम वैलिडेशन")
  - [फ़ील्ड्स के साथ FormValidator का उपयोग](#form-validator-with-fields "फ़ील्ड्स के साथ FormValidator का उपयोग")
- [उपलब्ध वैलिडेशन नियम](#validation-rules "उपलब्ध वैलिडेशन नियम")
- [कस्टम वैलिडेशन नियम बनाना](#creating-custom-validation-rules "कस्टम वैलिडेशन नियम बनाना")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 `FormValidator` क्लास के आधार पर एक वैलिडेशन सिस्टम प्रदान करता है। आप `check()` मेथड का उपयोग करके पेजों के अंदर डेटा वैलिडेट कर सकते हैं, या स्टैंडअलोन और फ़ील्ड-लेवल वैलिडेशन के लिए सीधे `FormValidator` का उपयोग कर सकते हैं।

``` dart
// NyPage/NyState में check() का उपयोग करके डेटा वैलिडेट करें
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// फ़ॉर्म फ़ील्ड्स के साथ FormValidator
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## check() के साथ डेटा वैलिडेट करना

किसी भी `NyPage` के अंदर, डेटा वैलिडेट करने के लिए `check()` मेथड का उपयोग करें। यह एक कॉलबैक स्वीकार करता है जो वैलिडेटर्स की सूची प्राप्त करता है। डेटा जोड़ने और नियम चेन करने के लिए `.that()` का उपयोग करें:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // सभी वैलिडेशन पास हुए
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // वैलिडेशन विफल
      print(bag.firstErrorMessage);
    });
  }
}
```

### check() कैसे काम करता है

1. `check()` एक खाली `List<FormValidator>` बनाता है
2. आपका कॉलबैक `.that(data)` का उपयोग करके सूची में डेटा के साथ एक नया `FormValidator` जोड़ता है
3. प्रत्येक `.that()` एक `FormValidator` रिटर्न करता है जिस पर आप नियम चेन कर सकते हैं
4. कॉलबैक के बाद, सूची में प्रत्येक वैलिडेटर की जाँच की जाती है
5. परिणाम एक `FormValidationResponseBag` में एकत्र किए जाते हैं

### एकाधिक फ़ील्ड्स वैलिडेट करना

``` dart
check((validate) {
  validate.that(_nameController.text, label: "Name").notEmpty().capitalized();
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_phoneController.text, label: "Phone").phoneNumberUs();
  validate.that(_ageController.text, label: "Age").numeric().minValue(18);
}, onSuccess: () {
  _submitForm();
});
```

वैकल्पिक `label` पैरामीटर एक मानव-पठनीय नाम सेट करता है जो एरर मैसेज में उपयोग किया जाता है (उदा. "The Email must be a valid email address.")।

<div id="validation-results"></div>

## वैलिडेशन रिज़ल्ट्स

`check()` मेथड एक `FormValidationResponseBag` (`List<FormValidationResult>` की एक सूची) रिटर्न करता है, जिसे आप सीधे भी निरीक्षण कर सकते हैं:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // पहला एरर मैसेज प्राप्त करें
  String? error = bag.firstErrorMessage;
  print(error);

  // सभी विफल परिणाम प्राप्त करें
  List<FormValidationResult> errors = bag.validationErrors;

  // सभी सफल परिणाम प्राप्त करें
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

प्रत्येक `FormValidationResult` एक सिंगल वैल्यू के वैलिडेशन का परिणाम दर्शाता है:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // पहला एरर मैसेज
  String? message = result.getFirstErrorMessage();

  // सभी एरर मैसेज
  List<String> messages = result.errorMessages();

  // एरर रिस्पॉन्स
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## FormValidator का उपयोग

`FormValidator` का उपयोग स्टैंडअलोन या फ़ॉर्म फ़ील्ड्स के साथ किया जा सकता है।

### स्टैंडअलोन उपयोग

``` dart
// नेम्ड कंस्ट्रक्टर का उपयोग करके
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### कंस्ट्रक्टर में डेटा के साथ

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator नेम्ड कंस्ट्रक्टर्स

{{ config('app.name') }} v7 सामान्य वैलिडेशन के लिए नेम्ड कंस्ट्रक्टर्स प्रदान करता है:

``` dart
// ईमेल वैलिडेशन
FormValidator.email(message: "Please enter a valid email")

// पासवर्ड वैलिडेशन (स्ट्रेंथ 1 या 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// फ़ोन नंबर
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL वैलिडेशन
FormValidator.url()

// लंबाई की सीमाएँ
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// वैल्यू की सीमाएँ
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// साइज़ की सीमाएँ (सूचियों/स्ट्रिंग्स के लिए)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// खाली नहीं
FormValidator.notEmpty(message: "This field is required")

// वैल्यूज़ शामिल हैं
FormValidator.contains(['option1', 'option2'])

// से शुरू/समाप्त होता है
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// बूलियन जाँच
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// संख्यात्मक
FormValidator.numeric()

// तिथि वैलिडेशन
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// टेक्स्ट केस
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// लोकेशन फ़ॉर्मेट
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// रीजेक्स पैटर्न
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// कस्टम वैलिडेशन
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## वैलिडेशन नियम चेन करना

इंस्टेंस मेथड्स का उपयोग करके कई नियमों को धाराप्रवाह रूप से चेन करें। प्रत्येक मेथड `FormValidator` रिटर्न करता है, जिससे आप नियम बना सकते हैं:

``` dart
FormValidator validator = FormValidator()
    .notEmpty()
    .email()
    .maxLength(50);

FormValidationResult result = validator.check("user@example.com");

if (!result.isValid) {
  List<String> errors = result.errorMessages();
  print(errors);
}
```

सभी नेम्ड कंस्ट्रक्टर्स के संबंधित चेन करने योग्य इंस्टेंस मेथड्स हैं:

``` dart
FormValidator()
    .notEmpty(message: "Required")
    .email(message: "Invalid email")
    .minLength(5, message: "Too short")
    .maxLength(100, message: "Too long")
    .beginsWith("user", message: "Must start with 'user'")
    .lowercase(message: "Must be lowercase")
```

<div id="nullable-fields"></div>

## नलेबल फ़ील्ड्स

किसी वैलिडेटर को वैकल्पिक चिह्नित करने के लिए `.nullable()` का उपयोग करें। नलेबल होने पर, एक null या खाली वैल्यू स्वचालित रूप से वैलिडेशन पास करती है — अन्य सभी नियम केवल तभी लागू होते हैं जब कोई वैल्यू मौजूद हो।

``` dart
// Nickname वैकल्पिक है, लेकिन यदि दिया गया तो 3–20 कैरेक्टर होना चाहिए
Field.text(
  "Nickname",
  validator: FormValidator()
      .minLength(3)
      .maxLength(20)
      .nullable(),
)
```

`.nullable()` के बिना, एक खाली फ़ील्ड `minLength` नियम को विफल कर देगी। `.nullable()` के साथ, फ़ील्ड खाली छोड़ने पर वैलिडेशन पास हो जाती है।

यह वैकल्पिक प्रोफ़ाइल फ़ील्ड्स, सेकेंडरी संपर्क जानकारी, या किसी भी फ़ील्ड के लिए उपयोगी है जो केवल तब वैलिडेट होती है जब उपयोगकर्ता उसे भरता है। `Field` विजेट्स के साथ `nullable()` के इंटीग्रेशन के लिए [Forms डॉक्यूमेंटेशन](/docs/{{ $version }}/forms) देखें।

<div id="custom-validation"></div>

## कस्टम वैलिडेशन

### कस्टम नियम (इनलाइन)

इनलाइन वैलिडेशन लॉजिक जोड़ने के लिए `.custom()` का उपयोग करें:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // वैलिड होने पर true, अवैलिड होने पर false रिटर्न करें
    return !_takenUsernames.contains(data);
  },
)
```

या इसे अन्य नियमों के साथ चेन करें:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### इक्वल्स वैलिडेशन

जाँचें कि कोई वैल्यू दूसरी से मेल खाती है:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## फ़ील्ड्स के साथ FormValidator का उपयोग

`FormValidator` फ़ॉर्म्स में `Field` विजेट्स के साथ इंटीग्रेट होता है। `validator` पैरामीटर में एक वैलिडेटर पास करें:

``` dart
class RegisterForm extends NyFormData {
  RegisterForm({String? name}) : super(name ?? "register");

  @override
  fields() => [
        Field.text(
          "Name",
          autofocus: true,
          validator: FormValidator.notEmpty(),
        ),
        Field.email("Email", validator: FormValidator.email()),
        Field.password(
          "Password",
          validator: FormValidator.password(strength: 1),
        ),
      ];
}
```

आप फ़ील्ड्स के साथ चेन किए गए वैलिडेटर्स का भी उपयोग कर सकते हैं:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Username is required")
      .minLength(3, message: "At least 3 characters")
      .maxLength(20, message: "At most 20 characters"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
)
```

<div id="validation-rules"></div>

## उपलब्ध वैलिडेशन नियम

`FormValidator` के लिए सभी उपलब्ध नियम, नेम्ड कंस्ट्रक्टर्स और चेन करने योग्य मेथड्स दोनों के रूप में:

| नियम | नेम्ड कंस्ट्रक्टर | चेन करने योग्य मेथड | विवरण |
|------|-------------------|------------------|-------------|
| ईमेल | `FormValidator.email()` | `.email()` | ईमेल फ़ॉर्मेट वैलिडेट करता है |
| पासवर्ड | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | स्ट्रेंथ 1: 8+ कैरेक्टर, 1 अपरकेस, 1 अंक। स्ट्रेंथ 2: + 1 स्पेशल कैरेक्टर |
| खाली नहीं | `FormValidator.notEmpty()` | `.notEmpty()` | खाली नहीं हो सकता |
| न्यूनतम लंबाई | `FormValidator.minLength(5)` | `.minLength(5)` | न्यूनतम स्ट्रिंग लंबाई |
| अधिकतम लंबाई | `FormValidator.maxLength(100)` | `.maxLength(100)` | अधिकतम स्ट्रिंग लंबाई |
| न्यूनतम वैल्यू | `FormValidator.minValue(18)` | `.minValue(18)` | न्यूनतम संख्यात्मक वैल्यू (स्ट्रिंग लंबाई, सूची लंबाई, मैप लंबाई पर भी काम करता है) |
| अधिकतम वैल्यू | `FormValidator.maxValue(100)` | `.maxValue(100)` | अधिकतम संख्यात्मक वैल्यू |
| न्यूनतम साइज़ | `FormValidator.minSize(2)` | `.minSize(2)` | सूचियों/स्ट्रिंग्स के लिए न्यूनतम साइज़ |
| अधिकतम साइज़ | `FormValidator.maxSize(5)` | `.maxSize(5)` | सूचियों/स्ट्रिंग्स के लिए अधिकतम साइज़ |
| शामिल है | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | वैल्यू में दी गई वैल्यूज़ में से एक होनी चाहिए |
| से शुरू होता है | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | स्ट्रिंग प्रीफ़िक्स से शुरू होनी चाहिए |
| पर समाप्त होता है | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | स्ट्रिंग सफ़िक्स पर समाप्त होनी चाहिए |
| URL | `FormValidator.url()` | `.url()` | URL फ़ॉर्मेट वैलिडेट करता है |
| संख्यात्मक | `FormValidator.numeric()` | `.numeric()` | एक संख्या होनी चाहिए |
| बूलियन True | `FormValidator.booleanTrue()` | `.booleanTrue()` | `true` होना चाहिए |
| बूलियन False | `FormValidator.booleanFalse()` | `.booleanFalse()` | `false` होना चाहिए |
| तिथि | `FormValidator.date()` | `.date()` | एक वैध तिथि होनी चाहिए |
| बीती तिथि | `FormValidator.dateInPast()` | `.dateInPast()` | तिथि अतीत में होनी चाहिए |
| भविष्य की तिथि | `FormValidator.dateInFuture()` | `.dateInFuture()` | तिथि भविष्य में होनी चाहिए |
| आयु बड़ी है | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | आयु N से अधिक होनी चाहिए |
| आयु छोटी है | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | आयु N से कम होनी चाहिए |
| कैपिटलाइज़्ड | `FormValidator.capitalized()` | `.capitalized()` | पहला अक्षर अपरकेस होना चाहिए |
| लोअरकेस | `FormValidator.lowercase()` | `.lowercase()` | सभी कैरेक्टर लोअरकेस होने चाहिए |
| अपरकेस | `FormValidator.uppercase()` | `.uppercase()` | सभी कैरेक्टर अपरकेस होने चाहिए |
| फ़ोन US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | US फ़ोन नंबर फ़ॉर्मेट |
| फ़ोन UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | UK फ़ोन नंबर फ़ॉर्मेट |
| ज़िपकोड US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | US ज़िपकोड फ़ॉर्मेट |
| पोस्टकोड UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | UK पोस्टकोड फ़ॉर्मेट |
| रीजेक्स | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | रीजेक्स पैटर्न से मेल खाना चाहिए |
| इक्वल्स | — | `.equals(otherValue)` | दूसरी वैल्यू के बराबर होना चाहिए |
| कस्टम | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | कस्टम वैलिडेशन फ़ंक्शन |
| नलेबल | — | `.nullable()` | null या खाली वैल्यू स्वचालित पास; नियम केवल वैल्यू मौजूद होने पर लागू होते हैं |

सभी नियम एरर मैसेज कस्टमाइज़ करने के लिए एक वैकल्पिक `message` पैरामीटर स्वीकार करते हैं।

<div id="creating-custom-validation-rules"></div>

## कस्टम वैलिडेशन नियम बनाना

पुन: प्रयोज्य वैलिडेशन नियम बनाने के लिए, `FormRule` क्लास को एक्सटेंड करें:

``` dart
class FormRuleUsername extends FormRule {
  @override
  String? rule = "username";

  @override
  String? message = "The @{{attribute}} must be a valid username.";

  FormRuleUsername({String? message}) {
    if (message != null) {
      this.message = message;
    }
  }

  @override
  bool validate(data) {
    if (data is! String) return false;
    // यूज़रनेम: अल्फ़ान्यूमेरिक, अंडरस्कोर, 3-20 कैरेक्टर
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

`message` में प्लेसहोल्डर के रूप में `@{{attribute}}` का उपयोग करें -- रनटाइम पर इसे फ़ील्ड के लेबल से बदल दिया जाएगा।

### कस्टम FormRule का उपयोग

`FormValidator.rule()` का उपयोग करके अपना कस्टम नियम `FormValidator` में जोड़ें:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

या क्लास बनाए बिना एक-बार के नियमों के लिए `.custom()` मेथड का उपयोग करें:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
