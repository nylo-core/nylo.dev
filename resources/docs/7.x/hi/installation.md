# इंस्टॉलेशन

---

<a name="section-1"></a>
- [इंस्टॉल](#install "इंस्टॉल")
- [प्रोजेक्ट चलाना](#running-the-project "प्रोजेक्ट चलाना")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## इंस्टॉल

### 1. nylo_installer को ग्लोबली इंस्टॉल करें

``` bash
dart pub global activate nylo_installer
```

यह आपके सिस्टम पर {{ config('app.name') }} CLI टूल को ग्लोबली इंस्टॉल करता है।

### 2. एक नया प्रोजेक्ट बनाएँ

``` bash
nylo new my_app
```

यह कमांड {{ config('app.name') }} टेम्पलेट को क्लोन करता है, प्रोजेक्ट को आपके ऐप नाम के साथ कॉन्फ़िगर करता है, और स्वचालित रूप से सभी डिपेंडेंसीज़ इंस्टॉल करता है।

### 3. Metro CLI एलियास सेटअप करें

``` bash
cd my_app
nylo init
```

यह आपके प्रोजेक्ट के लिए `metro` कमांड को कॉन्फ़िगर करता है, जिससे आप पूर्ण `dart run` सिंटैक्स के बिना Metro CLI कमांड उपयोग कर सकते हैं।

इंस्टॉलेशन के बाद, आपके पास एक पूर्ण Flutter प्रोजेक्ट स्ट्रक्चर होगा जिसमें:
- पूर्व-कॉन्फ़िगर्ड रूटिंग और नेविगेशन
- API सर्विस बॉयलरप्लेट
- थीम और लोकलाइज़ेशन सेटअप
- कोड जेनरेशन के लिए Metro CLI


<div id="running-the-project"></div>

## प्रोजेक्ट चलाना

{{ config('app.name') }} प्रोजेक्ट किसी भी मानक Flutter ऐप की तरह चलते हैं।

### टर्मिनल का उपयोग करके

``` bash
flutter run
```

### IDE का उपयोग करके

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">चलाना और डिबगिंग</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">बिना ब्रेकपॉइंट के ऐप चलाएँ</a>

यदि बिल्ड सफल होता है, तो ऐप {{ config('app.name') }} की डिफ़ॉल्ट लैंडिंग स्क्रीन प्रदर्शित करेगा।


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} में प्रोजेक्ट फ़ाइलें जेनरेट करने के लिए **Metro** नामक एक CLI टूल शामिल है।

### Metro चलाना

``` bash
metro
```

यह Metro मेनू प्रदर्शित करता है:

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Metro कमांड रेफरेंस

| कमांड | विवरण |
|---------|-------------|
| `make:page` | नया पेज बनाएँ |
| `make:stateful_widget` | स्टेटफुल विजेट बनाएँ |
| `make:stateless_widget` | स्टेटलेस विजेट बनाएँ |
| `make:state_managed_widget` | स्टेट-मैनेज्ड विजेट बनाएँ |
| `make:navigation_hub` | नेविगेशन हब (बॉटम नेव) बनाएँ |
| `make:journey_widget` | नेविगेशन हब के लिए जर्नी विजेट बनाएँ |
| `make:bottom_sheet_modal` | बॉटम शीट मोडल बनाएँ |
| `make:button` | कस्टम बटन विजेट बनाएँ |
| `make:form` | वैलिडेशन के साथ फ़ॉर्म बनाएँ |
| `make:model` | मॉडल क्लास बनाएँ |
| `make:provider` | प्रोवाइडर बनाएँ |
| `make:api_service` | API सर्विस बनाएँ |
| `make:controller` | कंट्रोलर बनाएँ |
| `make:event` | इवेंट बनाएँ |
| `make:theme` | थीम बनाएँ |
| `make:route_guard` | रूट गार्ड बनाएँ |
| `make:config` | कॉन्फ़िग फ़ाइल बनाएँ |
| `make:interceptor` | नेटवर्क इंटरसेप्टर बनाएँ |
| `make:command` | कस्टम Metro कमांड बनाएँ |
| `make:env` | .env से एनवायरनमेंट कॉन्फ़िग जेनरेट करें |

### उदाहरण उपयोग

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
