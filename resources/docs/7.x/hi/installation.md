# इंस्टॉलेशन

---

<a name="section-1"></a>
- [इंस्टॉल](#install "इंस्टॉल")
- [प्रोजेक्ट चलाना](#running-the-project "प्रोजेक्ट चलाना")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [प्रोजेक्ट क्लीन करना](#nylo-clean "प्रोजेक्ट क्लीन करना")
  - [iOS पॉड्स रीफ्रेश करना](#nylo-ios-pod-refresh "iOS पॉड्स रीफ्रेश करना")
  - [एलियास के बिना Metro चलाना](#nylo-metro "एलियास के बिना Metro चलाना")
  - [CLI अपडेट करना](#nylo-self-update "CLI अपडेट करना")

<div id="install"></div>

तीन कमांड आपको एक खाली फ़ोल्डर से चलती हुई Flutter ऐप तक ले जाते हैं, जिसमें रूटिंग, नेटवर्किंग, थीम और कोड जेनरेशन पहले से सेट होते हैं।

<x-doc-strip label="शुरू करने से पहले" items="Flutter SDK इंस्टॉल, Dart 3" linkText="पूरी आवश्यकताएँ" linkHref="/hi/docs/{{ $version }}/requirements" />

## इंस्टॉल

इन कमांड को क्रम से चलाएँ। हर कमांड को दोबारा चलाना सुरक्षित है।

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI को ग्लोबली इंस्टॉल करें">
आपके सिस्टम पर `nylo` कमांड स्थापित करता है।

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

इसके बाद अपना टर्मिनल रीस्टार्ट करें, या `nylo init` द्वारा प्रिंट किया गया `source` कमांड चलाएँ।
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


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: label "Requires" has no established translation in this locale file's existing x-doc-strip usage -->
<x-doc-strip label="आवश्यक" items="nylo_installer 1.8.0+" linkText="CLI अपडेट करें" linkHref="#nylo-self-update" />

स्टेप 1 वाला `nylo` कमांड सिर्फ़ प्रोजेक्ट बनाने से कहीं ज़्यादा कर सकता है। बिल्ड क्लीन करने, iOS पॉड्स रीफ्रेश करने, टेस्ट चलाने और अपने ट्रांसलेशन जाँचने के लिए इसे अपनी प्रोजेक्ट डायरेक्टरी से चलाएँ।

<x-doc-commands title="प्रोजेक्ट कमांड" rows="
nylo new | नया प्रोजेक्ट बनाएँ
nylo init | अपने शेल में metro एलियास सेटअप करें
nylo clean | flutter clean और flutter pub get चलाएँ
nylo ios:pod-refresh | अपने iOS पॉड्स फिर से इंस्टॉल करें
nylo metro | एलियास के बिना Metro कमांड चलाएँ
" />

<x-doc-commands title="टेस्टिंग और ट्रांसलेशन" rows="
nylo test | अपने टेस्ट फ़ॉर्मेट करें और चलाएँ
nylo locale:find-untranslated | ऐसा टेक्स्ट खोजें जो .tr() में रैप नहीं है
nylo locale:check-missing-keys | अपनी lang फ़ाइलों से मिसिंग कीज़ खोजें
" />

<x-doc-commands title="स्वयं CLI" rows="
nylo self-update | nylo को नवीनतम वर्शन में अपडेट करें
nylo --version | इंस्टॉल किया गया वर्शन दिखाएँ
" />

जिन कमांड्स में ऑप्शन होते हैं, वे `-h` के साथ लिस्ट होते हैं, उदाहरण के लिए `nylo test -h`। टेस्टिंग के बारे में [टेस्ट चलाना](/docs/{{ $version }}/testing#running-tests) में बताया गया है, और `locale:` कमांड्स के बारे में [ट्रांसलेशन ऑडिट करना](/docs/{{ $version }}/localization#auditing-translations) में।

<div id="nylo-clean"></div>

### प्रोजेक्ट क्लीन करना

`nylo clean`, `flutter clean` और फिर `flutter pub get` चलाता है। जब सामान्य क्लीन काफ़ी न हो, तो एक प्लेटफ़ॉर्म फ़्लैग जोड़ें:

``` bash
# flutter clean, फिर flutter pub get
nylo clean

# ios/Pods, ios/.symlinks और ios/Podfile.lock भी हटाएँ, फिर pod install --repo-update चलाएँ
nylo clean --ios

# android/ में gradlew clean भी चलाएँ
nylo clean --android

# ऊपर के दोनों
nylo clean --all
```

यदि किसी प्रोजेक्ट में `ios/` या `android/` फ़ोल्डर नहीं है, तो उस प्लेटफ़ॉर्म को चेतावनी के साथ स्किप कर दिया जाता है। `pod install` केवल macOS पर चलता है।

<div id="nylo-ios-pod-refresh"></div>

### iOS पॉड्स रीफ्रेश करना

जब CocoaPods सिंक से बाहर हो जाता है, तो `nylo ios:pod-refresh`, `ios/Pods`, `ios/.symlinks` और `ios/Podfile.lock` को डिलीट करता है, फिर `ios/` फ़ोल्डर में `pod install --repo-update` चलाता है:

``` bash
nylo ios:pod-refresh
```

इसे macOS पर अपने प्रोजेक्ट रूट से चलाएँ। `nylo clean --ios` के विपरीत, यह `flutter clean` या `flutter pub get` नहीं चलाता।

<div id="nylo-metro"></div>

### एलियास के बिना Metro चलाना

`nylo metro` अपने बाद आने वाली हर चीज़ को Metro (`dart run nylo_framework:main`) तक पास करता है। इसका उपयोग तब करें जब आपने अभी तक `nylo init` न चलाया हो, या Windows पर, जहाँ `nylo init` आपके लिए `metro` एलियास नहीं जोड़ सकता:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### CLI अपडेट करना

``` bash
nylo self-update
```

यह pub.dev जाँचता है और यदि कोई नया वर्शन उपलब्ध है तो `nylo_installer` को फिर से इंस्टॉल करता है। किसी कमांड के पूरा होने के बाद, `nylo` आपको यह भी बताता है कि कोई अपडेट उपलब्ध है। जब आउटपुट टर्मिनल पर नहीं जा रहा हो, तो यह चुप रहता है, ताकि यह सूचना कभी भी CI लॉग्स या पाइप्ड रिपोर्ट्स में न पहुँचे।

यह देखने के लिए कि आपने कौन-सा वर्शन इंस्टॉल किया है:

``` bash
nylo --version
```
