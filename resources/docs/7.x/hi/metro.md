# Metro CLI टूल

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [इंस्टॉल](#install "{{ config('app.name') }} के लिए Metro इंस्टॉल करना")
- Make कमांड्स
  - [कंट्रोलर बनाना](#make-controller "नया कंट्रोलर बनाएँ")
  - [मॉडल बनाना](#make-model "नया मॉडल बनाएँ")
  - [पेज बनाना](#make-page "नया पेज बनाएँ")
  - [स्टेटलेस विजेट बनाना](#make-stateless-widget "नया स्टेटलेस विजेट बनाएँ")
  - [स्टेटफुल विजेट बनाना](#make-stateful-widget "नया स्टेटफुल विजेट बनाएँ")
  - [जर्नी विजेट बनाना](#make-journey-widget "नया जर्नी विजेट बनाएँ")
  - [API सर्विस बनाना](#make-api-service "नई API सर्विस बनाएँ")
  - [इवेंट बनाना](#make-event "नया इवेंट बनाएँ")
  - [प्रोवाइडर बनाना](#make-provider "नया प्रोवाइडर बनाएँ")
  - [थीम बनाना](#make-theme "नई थीम बनाएँ")
  - [फ़ॉर्म बनाना](#make-forms "नया फ़ॉर्म बनाएँ")
  - [रूट गार्ड बनाना](#make-route-guard "नया रूट गार्ड बनाएँ")
  - [कॉन्फ़िग फ़ाइल बनाना](#make-config-file "नई कॉन्फ़िग फ़ाइल बनाएँ")
  - [कमांड बनाना](#make-command "नई कमांड बनाएँ")
  - [स्टेट मैनेज्ड विजेट बनाना](#make-state-managed-widget "नया स्टेट मैनेज्ड विजेट बनाएँ")
  - [नेविगेशन हब बनाना](#make-navigation-hub "नया नेविगेशन हब बनाएँ")
  - [बॉटम शीट मोडल बनाना](#make-bottom-sheet-modal "नया बॉटम शीट मोडल बनाएँ")
  - [बटन बनाना](#make-button "नया बटन बनाएँ")
  - [इंटरसेप्टर बनाना](#make-interceptor "नया इंटरसेप्टर बनाएँ")
  - [Env फ़ाइल बनाना](#make-env-file "नई env फ़ाइल बनाएँ")
  - [कुंजी जेनरेट करना](#make-key "APP_KEY जेनरेट करें")
- ऐप आइकन्स
  - [ऐप आइकन्स बिल्ड करना](#build-app-icons "Metro से ऐप आइकन्स बिल्ड करना")
- कस्टम कमांड्स
  - [कस्टम कमांड्स बनाना](#creating-custom-commands "कस्टम कमांड्स बनाना")
  - [कस्टम कमांड्स चलाना](#running-custom-commands "कस्टम कमांड्स चलाना")
  - [कमांड्स में ऑप्शन्स जोड़ना](#adding-options-to-custom-commands "कस्टम कमांड्स में ऑप्शन्स जोड़ना")
  - [कमांड्स में फ़्लैग्स जोड़ना](#adding-flags-to-custom-commands "कस्टम कमांड्स में फ़्लैग्स जोड़ना")
  - [हेल्पर मेथड्स](#custom-command-helper-methods "कस्टम कमांड हेल्पर मेथड्स")
  - [इंटरैक्टिव इनपुट मेथड्स](#interactive-input-methods "इंटरैक्टिव इनपुट मेथड्स")
  - [आउटपुट फ़ॉर्मेटिंग](#output-formatting "आउटपुट फ़ॉर्मेटिंग")
  - [फ़ाइल सिस्टम हेल्पर्स](#file-system-helpers "फ़ाइल सिस्टम हेल्पर्स")
  - [JSON और YAML हेल्पर्स](#json-yaml-helpers "JSON और YAML हेल्पर्स")
  - [केस कन्वर्शन हेल्पर्स](#case-conversion-helpers "केस कन्वर्शन हेल्पर्स")
  - [प्रोजेक्ट पाथ हेल्पर्स](#project-path-helpers "प्रोजेक्ट पाथ हेल्पर्स")
  - [प्लेटफ़ॉर्म हेल्पर्स](#platform-helpers "प्लेटफ़ॉर्म हेल्पर्स")
  - [Dart और Flutter कमांड्स](#dart-flutter-commands "Dart और Flutter कमांड्स")
  - [Dart फ़ाइल मैनिपुलेशन](#dart-file-manipulation "Dart फ़ाइल मैनिपुलेशन")
  - [डायरेक्टरी हेल्पर्स](#directory-helpers "डायरेक्टरी हेल्पर्स")
  - [वैलिडेशन हेल्पर्स](#validation-helpers "वैलिडेशन हेल्पर्स")
  - [फ़ाइल स्कैफ़ोल्डिंग](#file-scaffolding "फ़ाइल स्कैफ़ोल्डिंग")
  - [टास्क रनर](#task-runner "टास्क रनर")
  - [टेबल आउटपुट](#table-output "टेबल आउटपुट")
  - [प्रोग्रेस बार](#progress-bar "प्रोग्रेस बार")


<div id="introduction"></div>

## परिचय

Metro एक CLI टूल है जो {{ config('app.name') }} framework के अंदर काम करता है।
यह डेवलपमेंट को तेज़ करने के लिए बहुत सारे सहायक टूल्स प्रदान करता है।

<div id="install"></div>

## इंस्टॉल

जब आप `nylo init` का उपयोग करके एक नया Nylo प्रोजेक्ट बनाते हैं, तो `metro` कमांड आपके टर्मिनल के लिए स्वचालित रूप से कॉन्फ़िगर हो जाती है। आप इसे किसी भी Nylo प्रोजेक्ट में तुरंत उपयोग कर सकते हैं।

सभी उपलब्ध कमांड्स देखने के लिए अपनी प्रोजेक्ट डायरेक्टरी से `metro` चलाएँ:

``` bash
metro
```

आपको नीचे जैसा आउटपुट दिखना चाहिए।

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
  make:key
```

<div id="make-controller"></div>

## कंट्रोलर बनाना

- [नया कंट्रोलर बनाना](#making-a-new-controller "Metro से नया कंट्रोलर बनाएँ")
- [ज़बरदस्ती कंट्रोलर बनाना](#forcefully-make-a-controller "Metro से ज़बरदस्ती नया कंट्रोलर बनाएँ")
<div id="making-a-new-controller"></div>

### नया कंट्रोलर बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया कंट्रोलर बना सकते हैं।

``` bash
metro make:controller profile_controller
```

यह `lib/app/controllers/` डायरेक्टरी में एक नया कंट्रोलर बनाएगा अगर वह पहले से मौजूद नहीं है।

<div id="forcefully-make-a-controller"></div>

### ज़बरदस्ती कंट्रोलर बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा कंट्रोलर ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## मॉडल बनाना

- [नया मॉडल बनाना](#making-a-new-model "Metro से नया मॉडल बनाएँ")
- [JSON से मॉडल बनाना](#make-model-from-json "Metro से JSON से नया मॉडल बनाएँ")
- [ज़बरदस्ती मॉडल बनाना](#forcefully-make-a-model "Metro से ज़बरदस्ती नया मॉडल बनाएँ")
<div id="making-a-new-model"></div>

### नया मॉडल बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया मॉडल बना सकते हैं।

``` bash
metro make:model product
```

यह नवनिर्मित मॉडल को `lib/app/models/` में रखेगा।

<div id="make-model-from-json"></div>

### JSON से मॉडल बनाना

**आर्गुमेंट्स:**

`--json` या `-j` फ़्लैग का उपयोग करने से JSON पेलोड से एक नया मॉडल बनेगा।

``` bash
metro make:model product --json
```

फिर, आप अपना JSON टर्मिनल में पेस्ट कर सकते हैं और यह आपके लिए एक मॉडल जेनरेट करेगा।

<div id="forcefully-make-a-model"></div>

### ज़बरदस्ती मॉडल बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा मॉडल ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## पेज बनाना

- [नया पेज बनाना](#making-a-new-page "Metro से नया पेज बनाएँ")
- [कंट्रोलर के साथ पेज बनाना](#create-a-page-with-a-controller "Metro से कंट्रोलर के साथ नया पेज बनाएँ")
- [ऑथ पेज बनाना](#create-an-auth-page "Metro से नया ऑथ पेज बनाएँ")
- [इनिशियल पेज बनाना](#create-an-initial-page "Metro से नया इनिशियल पेज बनाएँ")
- [ज़बरदस्ती पेज बनाना](#forcefully-make-a-page "Metro से ज़बरदस्ती नया पेज बनाएँ")

<div id="making-a-new-page"></div>

### नया पेज बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया पेज बना सकते हैं।

``` bash
metro make:page product_page
```

यह `lib/resources/pages/` डायरेक्टरी में एक नया पेज बनाएगा अगर वह पहले से मौजूद नहीं है।

<div id="create-a-page-with-a-controller"></div>

### कंट्रोलर के साथ पेज बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर कंट्रोलर के साथ एक नया पेज बना सकते हैं।

**आर्गुमेंट्स:**

`--controller` या `-c` फ़्लैग का उपयोग करने से कंट्रोलर के साथ एक नया पेज बनेगा।

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### ऑथ पेज बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया ऑथ पेज बना सकते हैं।

**आर्गुमेंट्स:**

`--auth` या `-a` फ़्लैग का उपयोग करने से एक नया ऑथ पेज बनेगा।

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### इनिशियल पेज बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया इनिशियल पेज बना सकते हैं।

**आर्गुमेंट्स:**

`--initial` या `-i` फ़्लैग का उपयोग करने से एक नया इनिशियल पेज बनेगा।

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### ज़बरदस्ती पेज बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा पेज ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## स्टेटलेस विजेट बनाना

- [नया स्टेटलेस विजेट बनाना](#making-a-new-stateless-widget "Metro से नया स्टेटलेस विजेट बनाएँ")
- [ज़बरदस्ती स्टेटलेस विजेट बनाना](#forcefully-make-a-stateless-widget "Metro से ज़बरदस्ती नया स्टेटलेस विजेट बनाएँ")
<div id="making-a-new-stateless-widget"></div>

### नया स्टेटलेस विजेट बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया स्टेटलेस विजेट बना सकते हैं।

``` bash
metro make:stateless_widget product_rating_widget
```

ऊपर दी गई कमांड `lib/resources/widgets/` डायरेक्टरी में एक नया विजेट बनाएगी अगर वह पहले से मौजूद नहीं है।

<div id="forcefully-make-a-stateless-widget"></div>

### ज़बरदस्ती स्टेटलेस विजेट बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा विजेट ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## स्टेटफुल विजेट बनाना

- [नया स्टेटफुल विजेट बनाना](#making-a-new-stateful-widget "Metro से नया स्टेटफुल विजेट बनाएँ")
- [ज़बरदस्ती स्टेटफुल विजेट बनाना](#forcefully-make-a-stateful-widget "Metro से ज़बरदस्ती नया स्टेटफुल विजेट बनाएँ")

<div id="making-a-new-stateful-widget"></div>

### नया स्टेटफुल विजेट बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया स्टेटफुल विजेट बना सकते हैं।

``` bash
metro make:stateful_widget product_rating_widget
```

ऊपर दी गई कमांड `lib/resources/widgets/` डायरेक्टरी में एक नया विजेट बनाएगी अगर वह पहले से मौजूद नहीं है।

<div id="forcefully-make-a-stateful-widget"></div>

### ज़बरदस्ती स्टेटफुल विजेट बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा विजेट ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## जर्नी विजेट बनाना

- [नया जर्नी विजेट बनाना](#making-a-new-journey-widget "Metro से नया जर्नी विजेट बनाएँ")
- [ज़बरदस्ती जर्नी विजेट बनाना](#forcefully-make-a-journey-widget "Metro से ज़बरदस्ती नया जर्नी विजेट बनाएँ")

<div id="making-a-new-journey-widget"></div>

### नया जर्नी विजेट बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया जर्नी विजेट बना सकते हैं।

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

ऊपर दी गई कमांड `lib/resources/widgets/` डायरेक्टरी में एक नया विजेट बनाएगी अगर वह पहले से मौजूद नहीं है।

`--parent` आर्गुमेंट का उपयोग उस पैरेंट विजेट को निर्दिष्ट करने के लिए किया जाता है जिसमें नया जर्नी विजेट जोड़ा जाएगा।

उदाहरण

``` bash
metro make:navigation_hub onboarding
```

इसके बाद, नए जर्नी विजेट्स जोड़ें।
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### ज़बरदस्ती जर्नी विजेट बनाना
**आर्गुमेंट्स:**
`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा विजेट ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## API सर्विस बनाना

- [नई API सर्विस बनाना](#making-a-new-api-service "Metro से नई API सर्विस बनाएँ")
- [मॉडल के साथ नई API सर्विस बनाना](#making-a-new-api-service-with-a-model "Metro से मॉडल के साथ नई API सर्विस बनाएँ")
- [Postman का उपयोग करके API सर्विस बनाना](#make-api-service-using-postman "Postman से API सर्विसेज़ बनाएँ")
- [ज़बरदस्ती API सर्विस बनाना](#forcefully-make-an-api-service "Metro से ज़बरदस्ती नई API सर्विस बनाएँ")

<div id="making-a-new-api-service"></div>

### नई API सर्विस बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नई API सर्विस बना सकते हैं।

``` bash
metro make:api_service user_api_service
```

यह नवनिर्मित API सर्विस को `lib/app/networking/` में रखेगा।

<div id="making-a-new-api-service-with-a-model"></div>

### मॉडल के साथ नई API सर्विस बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर मॉडल के साथ एक नई API सर्विस बना सकते हैं।

**आर्गुमेंट्स:**

`--model` या `-m` ऑप्शन का उपयोग करने से मॉडल के साथ एक नई API सर्विस बनेगी।

``` bash
metro make:api_service user --model="User"
```

यह नवनिर्मित API सर्विस को `lib/app/networking/` में रखेगा।

### ज़बरदस्ती API सर्विस बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा API सर्विस ओवरराइट हो जाएगी अगर वह पहले से मौजूद है।

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## इवेंट बनाना

- [नया इवेंट बनाना](#making-a-new-event "Metro से नया इवेंट बनाएँ")
- [ज़बरदस्ती इवेंट बनाना](#forcefully-make-an-event "Metro से ज़बरदस्ती नया इवेंट बनाएँ")

<div id="making-a-new-event"></div>

### नया इवेंट बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया इवेंट बना सकते हैं।

``` bash
metro make:event login_event
```

यह `lib/app/events` में एक नया इवेंट बनाएगा।

<div id="forcefully-make-an-event"></div>

### ज़बरदस्ती इवेंट बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा इवेंट ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## प्रोवाइडर बनाना

- [नया प्रोवाइडर बनाना](#making-a-new-provider "Metro से नया प्रोवाइडर बनाएँ")
- [ज़बरदस्ती प्रोवाइडर बनाना](#forcefully-make-a-provider "Metro से ज़बरदस्ती नया प्रोवाइडर बनाएँ")

<div id="making-a-new-provider"></div>

### नया प्रोवाइडर बनाना

नीचे दी गई कमांड का उपयोग करके अपने एप्लिकेशन में नए प्रोवाइडर्स बनाएँ।

``` bash
metro make:provider firebase_provider
```

यह नवनिर्मित प्रोवाइडर को `lib/app/providers/` में रखेगा।

<div id="forcefully-make-a-provider"></div>

### ज़बरदस्ती प्रोवाइडर बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा प्रोवाइडर ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## थीम बनाना

- [नई थीम बनाना](#making-a-new-theme "Metro से नई थीम बनाएँ")
- [ज़बरदस्ती थीम बनाना](#forcefully-make-a-theme "Metro से ज़बरदस्ती नई थीम बनाएँ")

<div id="making-a-new-theme"></div>

### नई थीम बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर थीम बना सकते हैं।

``` bash
metro make:theme bright_theme
```

यह `lib/resources/themes/` में एक नई थीम बनाएगा।

<div id="forcefully-make-a-theme"></div>

### ज़बरदस्ती थीम बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा थीम ओवरराइट हो जाएगी अगर वह पहले से मौजूद है।

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## फ़ॉर्म बनाना

- [नया फ़ॉर्म बनाना](#making-a-new-form "Metro से नया फ़ॉर्म बनाएँ")
- [ज़बरदस्ती फ़ॉर्म बनाना](#forcefully-make-a-form "Metro से ज़बरदस्ती नया फ़ॉर्म बनाएँ")

<div id="making-a-new-form"></div>

### नया फ़ॉर्म बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया फ़ॉर्म बना सकते हैं।

``` bash
metro make:form car_advert_form
```

यह `lib/app/forms` में एक नया फ़ॉर्म बनाएगा।

<div id="forcefully-make-a-form"></div>

### ज़बरदस्ती फ़ॉर्म बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा फ़ॉर्म ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## रूट गार्ड बनाना

- [नया रूट गार्ड बनाना](#making-a-new-route-guard "Metro से नया रूट गार्ड बनाएँ")
- [ज़बरदस्ती रूट गार्ड बनाना](#forcefully-make-a-route-guard "Metro से ज़बरदस्ती नया रूट गार्ड बनाएँ")

<div id="making-a-new-route-guard"></div>

### नया रूट गार्ड बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक रूट गार्ड बना सकते हैं।

``` bash
metro make:route_guard premium_content
```

यह `lib/app/route_guards` में एक नया रूट गार्ड बनाएगा।

<div id="forcefully-make-a-route-guard"></div>

### ज़बरदस्ती रूट गार्ड बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा रूट गार्ड ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## कॉन्फ़िग फ़ाइल बनाना

- [नई कॉन्फ़िग फ़ाइल बनाना](#making-a-new-config-file "Metro से नई कॉन्फ़िग फ़ाइल बनाएँ")
- [ज़बरदस्ती कॉन्फ़िग फ़ाइल बनाना](#forcefully-make-a-config-file "Metro से ज़बरदस्ती नई कॉन्फ़िग फ़ाइल बनाएँ")

<div id="making-a-new-config-file"></div>

### नई कॉन्फ़िग फ़ाइल बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नई कॉन्फ़िग फ़ाइल बना सकते हैं।

``` bash
metro make:config shopping_settings
```

यह `lib/app/config` में एक नई कॉन्फ़िग फ़ाइल बनाएगा।

<div id="forcefully-make-a-config-file"></div>

### ज़बरदस्ती कॉन्फ़िग फ़ाइल बनाना

**आर्गुमेंट्स:**

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा कॉन्फ़िग फ़ाइल ओवरराइट हो जाएगी अगर वह पहले से मौजूद है।

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## कमांड बनाना

- [नई कमांड बनाना](#making-a-new-command "Metro से नई कमांड बनाएँ")
- [ज़बरदस्ती कमांड बनाना](#forcefully-make-a-command "Metro से ज़बरदस्ती नई कमांड बनाएँ")

<div id="making-a-new-command"></div>

### नई कमांड बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नई कमांड बना सकते हैं।

``` bash
metro make:command my_command
```

यह `lib/app/commands` में एक नई कमांड बनाएगा।

<div id="forcefully-make-a-command"></div>

### ज़बरदस्ती कमांड बनाना

**आर्गुमेंट्स:**
`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा कमांड ओवरराइट हो जाएगी अगर वह पहले से मौजूद है।

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## स्टेट मैनेज्ड विजेट बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया स्टेट मैनेज्ड विजेट बना सकते हैं।

``` bash
metro make:state_managed_widget product_rating_widget
```

ऊपर दी गई कमांड `lib/resources/widgets/` में एक नया विजेट बनाएगी।

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा विजेट ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## नेविगेशन हब बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया नेविगेशन हब बना सकते हैं।

``` bash
metro make:navigation_hub dashboard
```

यह `lib/resources/pages/` में एक नया नेविगेशन हब बनाएगा और रूट को स्वचालित रूप से जोड़ेगा।

**आर्गुमेंट्स:**

| फ़्लैग | शॉर्ट | विवरण |
|------|-------|-------------|
| `--auth` | `-a` | ऑथ पेज के रूप में बनाएँ |
| `--initial` | `-i` | इनिशियल पेज के रूप में बनाएँ |
| `--force` | `-f` | मौजूद होने पर ओवरराइट करें |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## बॉटम शीट मोडल बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया बॉटम शीट मोडल बना सकते हैं।

``` bash
metro make:bottom_sheet_modal payment_options
```

यह `lib/resources/widgets/` में एक नया बॉटम शीट मोडल बनाएगा।

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा मोडल ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## बटन बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया बटन विजेट बना सकते हैं।

``` bash
metro make:button checkout_button
```

यह `lib/resources/widgets/` में एक नया बटन विजेट बनाएगा।

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा बटन ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## इंटरसेप्टर बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नया नेटवर्क इंटरसेप्टर बना सकते हैं।

``` bash
metro make:interceptor auth_interceptor
```

यह `lib/app/networking/dio/interceptors/` में एक नया इंटरसेप्टर बनाएगा।

`--force` या `-f` फ़्लैग का उपयोग करने से मौजूदा इंटरसेप्टर ओवरराइट हो जाएगा अगर वह पहले से मौजूद है।

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Env फ़ाइल बनाना

आप टर्मिनल में नीचे दी गई कमांड चलाकर एक नई एनवायरनमेंट फ़ाइल बना सकते हैं।

``` bash
metro make:env .env.staging
```

यह आपकी प्रोजेक्ट रूट में एक नई `.env` फ़ाइल बनाएगा।

<div id="make-key"></div>

## कुंजी जेनरेट करना

एनवायरनमेंट एन्क्रिप्शन के लिए एक सुरक्षित `APP_KEY` जेनरेट करें। यह v7 में एन्क्रिप्टेड `.env` फ़ाइलों के लिए उपयोग किया जाता है।

``` bash
metro make:key
```

**आर्गुमेंट्स:**

| फ़्लैग / ऑप्शन | शॉर्ट | विवरण |
|---------------|-------|-------------|
| `--force` | `-f` | मौजूदा APP_KEY ओवरराइट करें |
| `--file` | `-e` | लक्ष्य .env फ़ाइल (डिफ़ॉल्ट: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## ऐप आइकन्स बिल्ड करना

नीचे दी गई कमांड चलाकर आप IOS और Android के लिए सभी ऐप आइकन्स जेनरेट कर सकते हैं।

``` bash
dart run flutter_launcher_icons:main
```

यह आपकी `pubspec.yaml` फ़ाइल में <b>flutter_icons</b> कॉन्फ़िगरेशन का उपयोग करता है।

<div id="custom-commands"></div>

## कस्टम कमांड्स

कस्टम कमांड्स आपको Nylo की CLI को अपने प्रोजेक्ट-विशिष्ट कमांड्स से विस्तारित करने की अनुमति देती हैं। यह सुविधा आपको दोहराए जाने वाले कार्यों को स्वचालित करने, डिप्लॉयमेंट वर्कफ़्लो लागू करने, या अपने प्रोजेक्ट के कमांड-लाइन टूल्स में कोई भी कस्टम कार्यक्षमता जोड़ने में सक्षम बनाती है।

- [कस्टम कमांड्स बनाना](#creating-custom-commands)
- [कस्टम कमांड्स चलाना](#running-custom-commands)
- [कमांड्स में ऑप्शन्स जोड़ना](#adding-options-to-custom-commands)
- [कमांड्स में फ़्लैग्स जोड़ना](#adding-flags-to-custom-commands)
- [हेल्पर मेथड्स](#custom-command-helper-methods)

> **नोट:** वर्तमान में आप अपनी कस्टम कमांड्स में nylo_framework.dart इम्पोर्ट नहीं कर सकते, कृपया इसके बजाय ny_cli.dart का उपयोग करें।

<div id="creating-custom-commands"></div>

## कस्टम कमांड्स बनाना

एक नई कस्टम कमांड बनाने के लिए, आप `make:command` फ़ीचर का उपयोग कर सकते हैं:

```bash
metro make:command current_time
```

आप `--category` ऑप्शन का उपयोग करके अपनी कमांड के लिए एक कैटेगरी निर्दिष्ट कर सकते हैं:

```bash
# Specify a category
metro make:command current_time --category="project"
```

यह `lib/app/commands/current_time.dart` पर निम्नलिखित संरचना के साथ एक नई कमांड फ़ाइल बनाएगा:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

कमांड स्वचालित रूप से `lib/app/commands/commands.json` फ़ाइल में रजिस्टर हो जाएगी, जिसमें सभी रजिस्टर्ड कमांड्स की सूची होती है:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## कस्टम कमांड्स चलाना

बनने के बाद, आप अपनी कस्टम कमांड को Metro शॉर्टहैंड या पूर्ण Dart कमांड का उपयोग करके चला सकते हैं:

```bash
metro app:current_time
```

जब आप बिना आर्गुमेंट्स के `metro` चलाते हैं, तो आपकी कस्टम कमांड्स "Custom Commands" सेक्शन के अंतर्गत मेनू में सूचीबद्ध दिखेंगी:

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

अपनी कमांड के लिए सहायता जानकारी प्रदर्शित करने के लिए, `--help` या `-h` फ़्लैग का उपयोग करें:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## कमांड्स में ऑप्शन्स जोड़ना

ऑप्शन्स आपकी कमांड को उपयोगकर्ताओं से अतिरिक्त इनपुट स्वीकार करने की अनुमति देते हैं। आप `builder` मेथड में अपनी कमांड में ऑप्शन्स जोड़ सकते हैं:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Add an option with a default value
  command.addOption(
    'environment',     // option name
    abbr: 'e',         // short form abbreviation
    help: 'Target deployment environment', // help text
    defaultValue: 'development',  // default value
    allowed: ['development', 'staging', 'production'] // allowed values
  );

  return command;
}
```

फिर अपनी कमांड के `handle` मेथड में ऑप्शन वैल्यू एक्सेस करें:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Command implementation...
}
```

उपयोग का उदाहरण:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## कमांड्स में फ़्लैग्स जोड़ना

फ़्लैग्स बूलियन ऑप्शन्स हैं जिन्हें चालू या बंद किया जा सकता है। `addFlag` मेथड का उपयोग करके अपनी कमांड में फ़्लैग्स जोड़ें:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // flag name
    abbr: 'v',       // short form abbreviation
    help: 'Enable verbose output', // help text
    defaultValue: false  // default to off
  );

  return command;
}
```

फिर अपनी कमांड के `handle` मेथड में फ़्लैग स्टेट चेक करें:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Additional logging...
  }

  // Command implementation...
}
```

उपयोग का उदाहरण:

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## हेल्पर मेथड्स

`NyCustomCommand` बेस क्लास सामान्य कार्यों में सहायता के लिए कई हेल्पर मेथड्स प्रदान करता है:

#### मैसेज प्रिंट करना

विभिन्न रंगों में मैसेज प्रिंट करने के कुछ मेथड्स यहाँ दिए गए हैं:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | नीले टेक्स्ट में इन्फ़ो मैसेज प्रिंट करें |
| [`error`](#custom-command-helper-formatting)     | लाल टेक्स्ट में एरर मैसेज प्रिंट करें |
| [`success`](#custom-command-helper-formatting)   | हरे टेक्स्ट में सक्सेस मैसेज प्रिंट करें |
| [`warning`](#custom-command-helper-formatting)   | पीले टेक्स्ट में वॉर्निंग मैसेज प्रिंट करें |

#### प्रोसेस चलाना

प्रोसेस चलाएँ और उनका आउटपुट कंसोल में प्रदर्शित करें:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | `pubspec.yaml` में एक पैकेज जोड़ें |
| [`addPackages`](#custom-command-helper-add-packages) | `pubspec.yaml` में कई पैकेज जोड़ें |
| [`runProcess`](#custom-command-helper-run-process) | एक बाहरी प्रोसेस चलाएँ और कंसोल में आउटपुट प्रदर्शित करें |
| [`prompt`](#custom-command-helper-prompt)    | उपयोगकर्ता से टेक्स्ट इनपुट एकत्र करें |
| [`confirm`](#custom-command-helper-confirm)   | हाँ/नहीं प्रश्न पूछें और बूलियन परिणाम प्राप्त करें |
| [`select`](#custom-command-helper-select)    | विकल्पों की सूची प्रस्तुत करें और उपयोगकर्ता को एक चुनने दें |
| [`multiSelect`](#custom-command-helper-multi-select) | उपयोगकर्ता को सूची से कई विकल्प चुनने दें |

#### नेटवर्क रिक्वेस्ट्स

कंसोल के माध्यम से नेटवर्क रिक्वेस्ट्स करना:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Nylo API क्लाइंट का उपयोग करके API कॉल करें |


#### लोडिंग स्पिनर

एक फ़ंक्शन निष्पादित करते समय लोडिंग स्पिनर प्रदर्शित करें:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | एक फ़ंक्शन निष्पादित करते समय लोडिंग स्पिनर दिखाएँ |
| [`createSpinner`](#manual-spinner-control) | मैनुअल कंट्रोल के लिए स्पिनर इंस्टेंस बनाएँ |

#### कस्टम कमांड हेल्पर्स

आप कमांड आर्गुमेंट्स को मैनेज करने के लिए निम्नलिखित हेल्पर मेथड्स का भी उपयोग कर सकते हैं:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | कमांड आर्गुमेंट्स से स्ट्रिंग वैल्यू प्राप्त करें |
| [`getBool`](#custom-command-helper-get-bool)   | कमांड आर्गुमेंट्स से बूलियन वैल्यू प्राप्त करें |
| [`getInt`](#custom-command-helper-get-int)    | कमांड आर्गुमेंट्स से इंटीजर वैल्यू प्राप्त करें |
| [`sleep`](#custom-command-helper-sleep) | निर्दिष्ट अवधि के लिए निष्पादन रोकें |


### बाहरी प्रोसेस चलाना

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### पैकेज प्रबंधन

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Add a package to pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Add a dev package to pubspec.yaml
addPackage('build_runner', dev: true);

// Add multiple packages at once
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### आउटपुट फ़ॉर्मेटिंग

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## इंटरैक्टिव इनपुट मेथड्स

`NyCustomCommand` बेस क्लास टर्मिनल में उपयोगकर्ता इनपुट एकत्र करने के लिए कई मेथड्स प्रदान करता है। ये मेथड्स आपकी कस्टम कमांड्स के लिए इंटरैक्टिव कमांड-लाइन इंटरफ़ेस बनाना आसान बनाते हैं।

<div id="custom-command-helper-prompt"></div>

### टेक्स्ट इनपुट

```dart
String prompt(String question, {String defaultValue = ''})
```

उपयोगकर्ता को एक प्रश्न दिखाता है और उनका टेक्स्ट रिस्पॉन्स एकत्र करता है।

**पैरामीटर्स:**
- `question`: प्रदर्शित करने के लिए प्रश्न या प्रॉम्प्ट
- `defaultValue`: वैकल्पिक डिफ़ॉल्ट वैल्यू अगर उपयोगकर्ता केवल Enter दबाता है

**रिटर्न:** उपयोगकर्ता का इनपुट एक स्ट्रिंग के रूप में, या कोई इनपुट न दिए जाने पर डिफ़ॉल्ट वैल्यू

**उदाहरण:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### पुष्टिकरण

```dart
bool confirm(String question, {bool defaultValue = false})
```

उपयोगकर्ता से हाँ/नहीं प्रश्न पूछता है और बूलियन परिणाम देता है।

**पैरामीटर्स:**
- `question`: पूछने के लिए हाँ/नहीं प्रश्न
- `defaultValue`: डिफ़ॉल्ट उत्तर (हाँ के लिए true, नहीं के लिए false)

**रिटर्न:** अगर उपयोगकर्ता ने हाँ कहा तो `true`, नहीं कहा तो `false`

**उदाहरण:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // User confirmed or pressed Enter (accepting the default)
  await runProcess('flutter pub get');
} else {
  // User declined
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### एकल चयन

```dart
String select(String question, List<String> options, {String? defaultOption})
```

विकल्पों की सूची प्रस्तुत करता है और उपयोगकर्ता को एक चुनने देता है।

**पैरामीटर्स:**
- `question`: चयन प्रॉम्प्ट
- `options`: उपलब्ध विकल्पों की सूची
- `defaultOption`: वैकल्पिक डिफ़ॉल्ट चयन

**रिटर्न:** चयनित विकल्प एक स्ट्रिंग के रूप में

**उदाहरण:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### बहु चयन

```dart
List<String> multiSelect(String question, List<String> options)
```

उपयोगकर्ता को सूची से कई विकल्प चुनने देता है।

**पैरामीटर्स:**
- `question`: चयन प्रॉम्प्ट
- `options`: उपलब्ध विकल्पों की सूची

**रिटर्न:** चयनित विकल्पों की सूची

**उदाहरण:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## API हेल्पर मेथड

`api` हेल्पर मेथड आपकी कस्टम कमांड्स से नेटवर्क रिक्वेस्ट्स करना सरल बनाता है।

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## बुनियादी उपयोग के उदाहरण

### GET रिक्वेस्ट

```dart
// Fetch data
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST रिक्वेस्ट

```dart
// Create a resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT रिक्वेस्ट

```dart
// Update a resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE रिक्वेस्ट

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH रिक्वेस्ट

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### क्वेरी पैरामीटर्स के साथ

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### स्पिनर के साथ

```dart
// Using with spinner for better UI
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Process the data
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## स्पिनर कार्यक्षमता

स्पिनर आपकी कस्टम कमांड्स में लंबे समय तक चलने वाले ऑपरेशन्स के दौरान विज़ुअल फ़ीडबैक प्रदान करते हैं। वे आपकी कमांड एसिंक्रोनस टास्क निष्पादित करते समय एक एनिमेटेड इंडिकेटर और मैसेज दिखाते हैं, जिससे प्रोग्रेस और स्टेटस दिखाकर उपयोगकर्ता अनुभव बेहतर होता है।

- [withSpinner का उपयोग](#using-with-spinner)
- [मैनुअल स्पिनर कंट्रोल](#manual-spinner-control)
- [उदाहरण](#spinner-examples)

<div id="using-with-spinner"></div>

## withSpinner का उपयोग

`withSpinner` मेथड आपको एक एसिंक्रोनस टास्क को स्पिनर एनीमेशन के साथ रैप करने देता है जो टास्क शुरू होने पर स्वचालित रूप से शुरू होता है और पूरा होने या विफल होने पर रुक जाता है:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**पैरामीटर्स:**
- `task`: निष्पादित करने के लिए एसिंक्रोनस फ़ंक्शन
- `message`: स्पिनर चलने के दौरान प्रदर्शित करने के लिए टेक्स्ट
- `successMessage`: सफल समापन पर प्रदर्शित करने के लिए वैकल्पिक मैसेज
- `errorMessage`: टास्क विफल होने पर प्रदर्शित करने के लिए वैकल्पिक मैसेज

**रिटर्न:** टास्क फ़ंक्शन का परिणाम

**उदाहरण:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Run a task with a spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Long-running task (e.g., analyzing project files)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Continue with the results
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## मैनुअल स्पिनर कंट्रोल

अधिक जटिल परिदृश्यों के लिए जहाँ आपको स्पिनर स्टेट को मैनुअली कंट्रोल करने की आवश्यकता है, आप एक स्पिनर इंस्टेंस बना सकते हैं:

```dart
ConsoleSpinner createSpinner(String message)
```

**पैरामीटर्स:**
- `message`: स्पिनर चलने के दौरान प्रदर्शित करने के लिए टेक्स्ट

**रिटर्न:** एक `ConsoleSpinner` इंस्टेंस जिसे आप मैनुअली कंट्रोल कर सकते हैं

**मैनुअल कंट्रोल के साथ उदाहरण:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a spinner instance
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // First task
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Second task
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Third task
    await runProcess('./deploy.sh', silent: true);

    // Complete successfully
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Handle failure
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## उदाहरण

### स्पिनर के साथ सरल टास्क

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Install dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### कई लगातार ऑपरेशन्स

```dart
@override
Future<void> handle(CommandResult result) async {
  // First operation with spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Second operation with spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Third operation with spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### मैनुअल कंट्रोल के साथ जटिल वर्कफ़्लो

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Run multiple steps with status updates
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Complete the process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

अपनी कस्टम कमांड्स में स्पिनर का उपयोग करने से लंबे समय तक चलने वाले ऑपरेशन्स के दौरान उपयोगकर्ताओं को स्पष्ट विज़ुअल फ़ीडबैक मिलता है, जिससे एक अधिक पॉलिश्ड और प्रोफ़ेशनल कमांड-लाइन अनुभव बनता है।

<div id="custom-command-helper-get-string"></div>

### ऑप्शन्स से स्ट्रिंग वैल्यू प्राप्त करना

```dart
String getString(String name, {String defaultValue = ''})
```

**पैरामीटर्स:**

- `name`: प्राप्त करने के लिए ऑप्शन का नाम
- `defaultValue`: वैकल्पिक डिफ़ॉल्ट वैल्यू अगर ऑप्शन प्रदान नहीं किया गया है

**रिटर्न:** ऑप्शन की वैल्यू एक स्ट्रिंग के रूप में

**उदाहरण:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### ऑप्शन्स से बूल वैल्यू प्राप्त करना

```dart
bool getBool(String name, {bool defaultValue = false})
```

**पैरामीटर्स:**
- `name`: प्राप्त करने के लिए ऑप्शन का नाम
- `defaultValue`: वैकल्पिक डिफ़ॉल्ट वैल्यू अगर ऑप्शन प्रदान नहीं किया गया है

**रिटर्न:** ऑप्शन की वैल्यू एक बूलियन के रूप में


**उदाहरण:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### ऑप्शन्स से इंट वैल्यू प्राप्त करना

```dart
int getInt(String name, {int defaultValue = 0})
```

**पैरामीटर्स:**
- `name`: प्राप्त करने के लिए ऑप्शन का नाम
- `defaultValue`: वैकल्पिक डिफ़ॉल्ट वैल्यू अगर ऑप्शन प्रदान नहीं किया गया है

**रिटर्न:** ऑप्शन की वैल्यू एक इंटीजर के रूप में

**उदाहरण:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### निर्दिष्ट अवधि के लिए स्लीप करना

```dart
void sleep(int seconds)
```

**पैरामीटर्स:**
- `seconds`: स्लीप करने के लिए सेकंड की संख्या

**रिटर्न:** कुछ नहीं

**उदाहरण:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## आउटपुट फ़ॉर्मेटिंग

बुनियादी `info`, `error`, `success`, और `warning` मेथड्स के अलावा, `NyCustomCommand` अतिरिक्त आउटपुट हेल्पर्स प्रदान करता है:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Print plain text (no color)
  line('Processing your request...');

  // Print blank lines
  newLine();       // one blank line
  newLine(3);      // three blank lines

  // Print a muted comment (gray text)
  comment('This is a background note');

  // Print a prominent alert box
  alert('Important: Please read carefully');

  // Ask is an alias for prompt
  final name = ask('What is your name?');

  // Hidden input for sensitive data (e.g., passwords, API keys)
  final apiKey = promptSecret('Enter your API key:');

  // Abort the command with an error message and exit code
  if (name.isEmpty) {
    abort('Name is required');  // exits with code 1
  }
}
```

| मेथड | विवरण |
|--------|-------------|
| `line(String message)` | बिना रंग के सादा टेक्स्ट प्रिंट करें |
| `newLine([int count = 1])` | खाली लाइनें प्रिंट करें |
| `comment(String message)` | म्यूटेड/ग्रे टेक्स्ट प्रिंट करें |
| `alert(String message)` | एक प्रमुख अलर्ट बॉक्स प्रिंट करें |
| `ask(String question, {String defaultValue})` | `prompt` का उपनाम |
| `promptSecret(String question)` | संवेदनशील डेटा के लिए छिपा इनपुट |
| `abort([String? message, int exitCode = 1])` | एरर के साथ कमांड से बाहर निकलें |

<div id="file-system-helpers"></div>

## फ़ाइल सिस्टम हेल्पर्स

`NyCustomCommand` में बिल्ट-इन फ़ाइल सिस्टम हेल्पर्स शामिल हैं ताकि आपको सामान्य ऑपरेशन्स के लिए मैनुअली `dart:io` इम्पोर्ट करने की आवश्यकता न हो।

### फ़ाइलें पढ़ना और लिखना

```dart
@override
Future<void> handle(CommandResult result) async {
  // Check if a file exists
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Check if a directory exists
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Read a file (async)
  String content = await readFile('pubspec.yaml');

  // Read a file (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // Write to a file (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Write to a file (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Append content to a file
  await appendFile('log.txt', 'New log entry\n');

  // Ensure a directory exists (creates it if missing)
  await ensureDirectory('lib/generated');

  // Delete a file
  await deleteFile('lib/generated/output.dart');

  // Copy a file
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| मेथड | विवरण |
|--------|-------------|
| `fileExists(String path)` | फ़ाइल मौजूद होने पर `true` लौटाता है |
| `directoryExists(String path)` | डायरेक्टरी मौजूद होने पर `true` लौटाता है |
| `readFile(String path)` | फ़ाइल को स्ट्रिंग के रूप में पढ़ें (async) |
| `readFileSync(String path)` | फ़ाइल को स्ट्रिंग के रूप में पढ़ें (sync) |
| `writeFile(String path, String content)` | फ़ाइल में कंटेंट लिखें (async) |
| `writeFileSync(String path, String content)` | फ़ाइल में कंटेंट लिखें (sync) |
| `appendFile(String path, String content)` | फ़ाइल में कंटेंट जोड़ें |
| `ensureDirectory(String path)` | डायरेक्टरी न होने पर बनाएँ |
| `deleteFile(String path)` | एक फ़ाइल हटाएँ |
| `copyFile(String source, String destination)` | एक फ़ाइल कॉपी करें |

<div id="json-yaml-helpers"></div>

## JSON और YAML हेल्पर्स

बिल्ट-इन हेल्पर्स के साथ JSON और YAML फ़ाइलें पढ़ें और लिखें।

```dart
@override
Future<void> handle(CommandResult result) async {
  // Read a JSON file as a Map
  Map<String, dynamic> config = await readJson('config.json');

  // Read a JSON file as a List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Write data to a JSON file (pretty printed by default)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Write compact JSON
  await writeJson('output.json', data, pretty: false);

  // Append an item to a JSON array file
  // If the file contains [{"name": "a"}], this adds to that array
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // prevents duplicates by this key
  );

  // Read a YAML file as a Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| मेथड | विवरण |
|--------|-------------|
| `readJson(String path)` | JSON फ़ाइल को `Map<String, dynamic>` के रूप में पढ़ें |
| `readJsonArray(String path)` | JSON फ़ाइल को `List<dynamic>` के रूप में पढ़ें |
| `writeJson(String path, dynamic data, {bool pretty = true})` | डेटा को JSON के रूप में लिखें |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON array फ़ाइल में जोड़ें |
| `readYaml(String path)` | YAML फ़ाइल को `Map<String, dynamic>` के रूप में पढ़ें |

<div id="case-conversion-helpers"></div>

## केस कन्वर्शन हेल्पर्स

`recase` पैकेज इम्पोर्ट किए बिना स्ट्रिंग्स को नामकरण परंपराओं के बीच कन्वर्ट करें।

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| मेथड | आउटपुट फ़ॉर्मेट | उदाहरण |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## प्रोजेक्ट पाथ हेल्पर्स

मानक {{ config('app.name') }} प्रोजेक्ट डायरेक्टरीज के लिए गेटर्स। ये प्रोजेक्ट रूट के सापेक्ष पाथ लौटाते हैं।

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Build a custom path relative to the project root
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| प्रॉपर्टी | पाथ |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | प्रोजेक्ट के भीतर एक सापेक्ष पाथ रिज़ॉल्व करता है |

<div id="platform-helpers"></div>

## प्लेटफ़ॉर्म हेल्पर्स

प्लेटफ़ॉर्म की जाँच करें और एनवायरनमेंट वेरिएबल्स एक्सेस करें।

```dart
@override
Future<void> handle(CommandResult result) async {
  // Platform checks
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Current working directory
  info('Working in: $workingDirectory');

  // Read system environment variables
  String home = env('HOME', '/default/path');
}
```

| प्रॉपर्टी / मेथड | विवरण |
|-------------------|-------------|
| `isWindows` | Windows पर चलने पर `true` |
| `isMacOS` | macOS पर चलने पर `true` |
| `isLinux` | Linux पर चलने पर `true` |
| `workingDirectory` | वर्तमान कार्यशील डायरेक्टरी पाथ |
| `env(String key, [String defaultValue = ''])` | सिस्टम एनवायरनमेंट वेरिएबल पढ़ें |

<div id="dart-flutter-commands"></div>

## Dart और Flutter कमांड्स

सामान्य Dart और Flutter CLI कमांड्स को हेल्पर मेथड्स के रूप में चलाएँ। प्रत्येक प्रोसेस एग्ज़िट कोड लौटाता है।

```dart
@override
Future<void> handle(CommandResult result) async {
  // Format a Dart file or directory
  await dartFormat('lib/app/models/user.dart');

  // Run dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Run flutter pub get
  await flutterPubGet();

  // Run flutter clean
  await flutterClean();

  // Build for a target with additional args
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Run flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // specific directory
}
```

| मेथड | विवरण |
|--------|-------------|
| `dartFormat(String path)` | फ़ाइल या डायरेक्टरी पर `dart format` चलाएँ |
| `dartAnalyze([String? path])` | `dart analyze` चलाएँ |
| `flutterPubGet()` | `flutter pub get` चलाएँ |
| `flutterClean()` | `flutter clean` चलाएँ |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` चलाएँ |
| `flutterTest([String? path])` | `flutter test` चलाएँ |

<div id="dart-file-manipulation"></div>

## Dart फ़ाइल मैनिपुलेशन

Dart फ़ाइलों को प्रोग्रामेटिक रूप से एडिट करने के लिए हेल्पर्स, स्कैफ़ोल्डिंग टूल्स बनाते समय उपयोगी।

```dart
@override
Future<void> handle(CommandResult result) async {
  // Add an import statement to a Dart file (avoids duplicates)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Insert code before the last closing brace in a file
  // Useful for adding entries to registration maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Check if a file contains a specific string
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Check if a file matches a regex pattern
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| मेथड | विवरण |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart फ़ाइल में इम्पोर्ट जोड़ें (पहले से मौजूद होने पर छोड़ें) |
| `insertBeforeClosingBrace(String filePath, String code)` | फ़ाइल में अंतिम `}` से पहले कोड डालें |
| `fileContains(String filePath, String identifier)` | जाँचें कि फ़ाइल में स्ट्रिंग है या नहीं |
| `fileContainsPattern(String filePath, Pattern pattern)` | जाँचें कि फ़ाइल पैटर्न से मेल खाती है या नहीं |

<div id="directory-helpers"></div>

## डायरेक्टरी हेल्पर्स

डायरेक्टरीज के साथ काम करने और फ़ाइलें खोजने के लिए हेल्पर्स।

```dart
@override
Future<void> handle(CommandResult result) async {
  // List directory contents
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // List recursively
  var allEntities = listDirectory('lib/', recursive: true);

  // Find files matching criteria
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Find files by name pattern
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Delete a directory recursively
  await deleteDirectory('build/');

  // Copy a directory (recursive)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| मेथड | विवरण |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | डायरेक्टरी की सामग्री सूचीबद्ध करें |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | मानदंड से मेल खाने वाली फ़ाइलें खोजें |
| `deleteDirectory(String path)` | डायरेक्टरी को रिकर्सिवली हटाएँ |
| `copyDirectory(String source, String destination)` | डायरेक्टरी को रिकर्सिवली कॉपी करें |

<div id="validation-helpers"></div>

## वैलिडेशन हेल्पर्स

कोड जेनरेशन के लिए उपयोगकर्ता इनपुट को मान्य और साफ़ करने के लिए हेल्पर्स।

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validate a Dart identifier
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Require a non-empty first argument
  String name = requireArgument(result, message: 'Please provide a name');

  // Clean a class name (PascalCase, remove suffixes)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Returns: 'User'

  // Clean a file name (snake_case with extension)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Returns: 'user_model.dart'
}
```

| मेथड | विवरण |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart आइडेंटिफ़ायर नाम मान्य करें |
| `requireArgument(CommandResult result, {String? message})` | गैर-रिक्त पहला आर्गुमेंट आवश्यक करें या एबॉर्ट करें |
| `cleanClassName(String name, {List<String> removeSuffixes})` | क्लास नाम को साफ़ और PascalCase करें |
| `cleanFileName(String name, {String extension = '.dart'})` | फ़ाइल नाम को साफ़ और snake_case करें |

<div id="file-scaffolding"></div>

## फ़ाइल स्कैफ़ोल्डिंग

स्कैफ़ोल्डिंग सिस्टम का उपयोग करके कंटेंट के साथ एक या कई फ़ाइलें बनाएँ।

### एकल फ़ाइल

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // don't overwrite if exists
    successMessage: 'AuthService created',
  );
}
```

### कई फ़ाइलें

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

`ScaffoldFile` क्लास स्वीकार करता है:

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `path` | `String` | बनाने के लिए फ़ाइल पाथ |
| `content` | `String` | फ़ाइल कंटेंट |
| `successMessage` | `String?` | सफलता पर दिखाया जाने वाला मैसेज |

<div id="task-runner"></div>

## टास्क रनर

स्वचालित स्टेटस आउटपुट के साथ नामित टास्क की एक श्रृंखला चलाएँ।

### बुनियादी टास्क रनर

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // stop pipeline if this fails (default)
    ),
  ]);
}
```

### स्पिनर के साथ टास्क रनर

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

`CommandTask` क्लास स्वीकार करता है:

| प्रॉपर्टी | टाइप | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `name` | `String` | आवश्यक | आउटपुट में दिखाया जाने वाला टास्क नाम |
| `action` | `Future<void> Function()` | आवश्यक | निष्पादित करने के लिए Async फ़ंक्शन |
| `stopOnError` | `bool` | `true` | यदि यह विफल होता है तो शेष टास्क रोकें या नहीं |

<div id="table-output"></div>

## टेबल आउटपुट

कंसोल में फ़ॉर्मेटेड ASCII टेबल्स प्रदर्शित करें।

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

आउटपुट:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## प्रोग्रेस बार

ज्ञात आइटम संख्या वाले ऑपरेशन्स के लिए प्रोग्रेस बार प्रदर्शित करें।

### मैनुअल प्रोग्रेस बार

```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a progress bar for 100 items
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // increment by 1
  }

  progress.complete('All files processed');
}
```

### प्रोग्रेस के साथ आइटम्स प्रोसेस करना

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Process items with automatic progress tracking
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // process each file
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### सिंक्रोनस प्रोग्रेस

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // synchronous processing
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

`ConsoleProgressBar` क्लास प्रदान करता है:

| मेथड | विवरण |
|--------|-------------|
| `start()` | प्रोग्रेस बार शुरू करें |
| `tick([int amount = 1])` | प्रोग्रेस बढ़ाएँ |
| `update(int value)` | प्रोग्रेस को एक विशिष्ट मान पर सेट करें |
| `updateMessage(String newMessage)` | प्रदर्शित मैसेज बदलें |
| `complete([String? completionMessage])` | वैकल्पिक मैसेज के साथ पूर्ण करें |
| `stop()` | पूर्ण किए बिना रोकें |
| `current` | वर्तमान प्रोग्रेस मान (गेटर) |
| `percentage` | प्रतिशत के रूप में प्रोग्रेस (गेटर) |