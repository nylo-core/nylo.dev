# Providers

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [प्रोवाइडर बनाएँ](#create-a-provider "प्रोवाइडर बनाएँ")
- [प्रोवाइडर ऑब्जेक्ट](#provider-object "प्रोवाइडर ऑब्जेक्ट")


<div id="introduction"></div>

## प्रोवाइडर्स का परिचय

{{ config('app.name') }} में, प्रोवाइडर्स आपके एप्लिकेशन चलने पर आपकी <b>main.dart</b> फ़ाइल से प्रारंभ में बूट किए जाते हैं। आपके सभी प्रोवाइडर्स `/lib/app/providers/*` में रहते हैं, आप इन फ़ाइलों को संशोधित कर सकते हैं या <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a> का उपयोग करके अपने प्रोवाइडर्स बना सकते हैं।

प्रोवाइडर्स का उपयोग तब किया जा सकता है जब आपको ऐप के शुरू में लोड होने से पहले किसी क्लास, पैकेज को इनिशियलाइज़ करने या कुछ बनाने की आवश्यकता हो। उदाहरण के लिए, `route_provider.dart` क्लास {{ config('app.name') }} में सभी रूट्स जोड़ने के लिए ज़िम्मेदार है।

### गहन अध्ययन

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### जीवनचक्र

- `Boot.{{ config('app.name') }}` आपकी <b>config/providers.dart</b> फ़ाइल के अंदर पंजीकृत प्रोवाइडर्स के माध्यम से लूप करेगा और उन्हें बूट करेगा।

- `Boot.Finished` **"Boot.{{ config('app.name') }}"** समाप्त होने के तुरंत बाद कॉल होता है, यह मेथड `Backpack` में 'nylo' वैल्यू के साथ {{ config('app.name') }} इंस्टेंस को बाइंड करेगा।

उदा. Backpack.instance.read('nylo'); // {{ config('app.name') }} instance


<div id="create-a-provider"></div>

## नया प्रोवाइडर बनाएँ

आप टर्मिनल में नीचे दिए गए कमांड को चलाकर नए प्रोवाइडर्स बना सकते हैं।

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## प्रोवाइडर ऑब्जेक्ट

आपके प्रोवाइडर में दो मेथड्स होंगे, `setup(Nylo nylo)` और `boot(Nylo nylo)`।

जब ऐप पहली बार चलता है, तो आपके **setup** मेथड के अंदर कोई भी कोड पहले निष्पादित होगा। आप नीचे दिए गए उदाहरण की तरह `Nylo` ऑब्जेक्ट को भी मैनिपुलेट कर सकते हैं।

उदाहरण: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### जीवनचक्र

1. `setup(Nylo nylo)` - अपने प्रोवाइडर को इनिशियलाइज़ करें। `Nylo` इंस्टेंस या `null` लौटाएँ।
2. `boot(Nylo nylo)` - सभी प्रोवाइडर्स का सेटअप पूरा होने के बाद कॉल होता है। इसका उपयोग उन इनिशियलाइज़ेशन के लिए करें जो अन्य प्रोवाइडर्स के तैयार होने पर निर्भर करते हैं।

> `setup` मेथड के अंदर, आपको ऊपर की तरह `Nylo` का एक इंस्टेंस या `null` **लौटाना** होगा।
