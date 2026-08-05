# इंस्टॉलेशन

---

<a name="section-1"></a>
- [इंस्टॉल](#install "इंस्टॉल")
- [प्रोजेक्ट चलाना](#running-the-project "प्रोजेक्ट चलाना")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

तीन कमांड आपको एक खाली फ़ोल्डर से चलती हुई Flutter ऐप तक ले जाते हैं, जिसमें रूटिंग, नेटवर्किंग, थीम और कोड जेनरेशन पहले से सेट होते हैं।

<x-doc-strip label="शुरू करने से पहले" items="Flutter SDK इंस्टॉल, Dart 3" linkText="पूरी आवश्यकताएँ" linkHref="/hi/docs/{{ $version }}/requirements" />

## इंस्टॉल

इन कमांड को क्रम से चलाएँ। हर कमांड को दोबारा चलाना सुरक्षित है।

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI को ग्लोबली इंस्टॉल करें">
यह आपके सिस्टम पर {{ config('app.name') }} CLI टूल को ग्लोबली इंस्टॉल करता है।

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="एक नया प्रोजेक्ट बनाएँ">
यह कमांड {{ config('app.name') }} टेम्पलेट को क्लोन करता है, प्रोजेक्ट को आपके ऐप नाम के साथ कॉन्फ़िगर करता है, और स्वचालित रूप से सभी डिपेंडेंसीज़ इंस्टॉल करता है।

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Metro एलियास सेटअप करें">
यह आपके प्रोजेक्ट के लिए `metro` कमांड को कॉन्फ़िगर करता है, जिससे आप पूर्ण `dart run` सिंटैक्स के बिना Metro CLI कमांड उपयोग कर सकते हैं।

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="आपको क्या मिलता है" items="पूर्व-कॉन्फ़िगर्ड रूटिंग और नेविगेशन
API सर्विस बॉयलरप्लेट
थीम और लोकलाइज़ेशन सेटअप
कोड जेनरेशन के लिए Metro CLI" />


<div id="running-the-project"></div>

## प्रोजेक्ट चलाना

{{ config('app.name') }} प्रोजेक्ट किसी भी मानक Flutter ऐप की तरह चलते हैं।

<x-doc-tabs tabs="टर्मिनल, Android Studio, VS Code">
<x-doc-tab label="टर्मिनल">

``` bash
flutter run
```

यदि बिल्ड सफल होता है, तो ऐप {{ config('app.name') }} की डिफ़ॉल्ट लैंडिंग स्क्रीन प्रदर्शित करेगा।
</x-doc-tab>

<x-doc-tab label="Android Studio">
प्रोजेक्ट फ़ोल्डर खोलें, टारगेट सेलेक्टर से डिवाइस चुनें, फिर **Run** दबाएँ।

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter दस्तावेज़: चलाना और डिबगिंग ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
प्रोजेक्ट फ़ोल्डर खोलें, फिर कमांड पैलेट से **Debug: Start Without Debugging** चलाएँ।

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter दस्तावेज़: बिना ब्रेकपॉइंट के चलाएँ ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro आपके लिए प्रोजेक्ट फ़ाइलें जेनरेट करता है। मेनू देखने के लिए इसे बिना आर्ग्युमेंट चलाएँ, या सीधे कोई कमांड चलाएँ।

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### कमांड रेफरेंस

हर कमांड एक नाम लेता है, जैसे `metro make:page settings_page`

<x-doc-commands title="विजेट कमांड" rows="
metro make:page | नया पेज बनाएँ
metro make:stateful_widget | स्टेटफुल विजेट बनाएँ
metro make:stateless_widget | स्टेटलेस विजेट बनाएँ
metro make:state_managed_widget | स्टेट-मैनेज्ड विजेट बनाएँ
metro make:navigation_hub | नेविगेशन हब (बॉटम नेव) बनाएँ
metro make:journey_widget | नेविगेशन हब के लिए जर्नी विजेट बनाएँ
metro make:bottom_sheet_modal | बॉटम शीट मोडल बनाएँ
metro make:button | कस्टम बटन विजेट बनाएँ
metro make:form | वैलिडेशन के साथ फ़ॉर्म बनाएँ
" />

<x-doc-commands title="हेल्पर कमांड" rows="
metro make:model | मॉडल क्लास बनाएँ
metro make:provider | प्रोवाइडर बनाएँ
metro make:api_service | API सर्विस बनाएँ
metro make:controller | कंट्रोलर बनाएँ
metro make:event | इवेंट बनाएँ
metro make:route_guard | रूट गार्ड बनाएँ
metro make:config | कॉन्फ़िग फ़ाइल बनाएँ
metro make:interceptor | नेटवर्क इंटरसेप्टर बनाएँ
metro make:command | कस्टम Metro कमांड बनाएँ
metro make:env | .env से एनवायरनमेंट कॉन्फ़िग जेनरेट करें
" />

### उदाहरण उपयोग

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
