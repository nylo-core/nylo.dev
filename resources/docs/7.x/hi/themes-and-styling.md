# थीम्स और स्टाइलिंग

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- थीम्स
  - [लाइट और डार्क थीम्स](#light-and-dark-themes "लाइट और डार्क थीम्स")
  - [थीम बनाना](#creating-a-theme "थीम बनाना")
- कॉन्फ़िगरेशन
  - [थीम कलर्स](#theme-colors "थीम कलर्स")
  - [कलर्स का उपयोग](#using-colors "कलर्स का उपयोग")
  - [बेस स्टाइल्स](#base-styles "बेस स्टाइल्स")
  - [थीम स्विच करना](#switching-theme "थीम स्विच करना")
  - [फ़ॉन्ट्स](#fonts "फ़ॉन्ट्स")
  - [डिज़ाइन](#design "डिज़ाइन")
- [टेक्स्ट एक्सटेंशन्स](#text-extensions "टेक्स्ट एक्सटेंशन्स")


<div id="introduction"></div>

## परिचय

आप थीम्स का उपयोग करके अपने एप्लिकेशन की UI स्टाइल्स मैनेज कर सकते हैं। थीम्स हमें बदलने की अनुमति देते हैं जैसे टेक्स्ट का फ़ॉन्ट साइज़, बटन कैसे दिखते हैं और हमारे एप्लिकेशन की सामान्य उपस्थिति।

यदि आप थीम्स में नए हैं, तो Flutter वेबसाइट पर उदाहरण आपको शुरू करने में मदद करेंगे <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">यहाँ</a>।

{{ config('app.name') }} में `Light mode` और `Dark mode` के लिए पहले से कॉन्फ़िगर्ड थीम्स शामिल हैं।

यदि डिवाइस <b>'लाइट/डार्क'</b> मोड में प्रवेश करता है तो थीम भी अपडेट होगी।

<div id="light-and-dark-themes"></div>

## लाइट और डार्क थीम्स

- लाइट थीम - `lib/resources/themes/light_theme.dart`
- डार्क थीम - `lib/resources/themes/dark_theme.dart`

इन फ़ाइलों के अंदर, आपको ThemeData और ThemeStyle पहले से परिभाषित मिलेंगे।



<div id="creating-a-theme"></div>

## थीम बनाना

यदि आप अपने ऐप के लिए एकाधिक थीम्स रखना चाहते हैं, तो हमारे पास इसके लिए एक आसान तरीका है। यदि आप थीम्स में नए हैं, तो साथ चलें।

सबसे पहले, टर्मिनल से नीचे दिया गया कमांड चलाएँ

``` bash
metro make:theme bright_theme
```

<b>नोट:</b> **bright_theme** को अपनी नई थीम के नाम से बदलें।

यह आपकी `/resources/themes/` डायरेक्टरी में एक नई थीम बनाता है और `/resources/themes/styles/` में एक थीम कलर्स फ़ाइल भी बनाता है।

``` dart
// App Themes
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

आप **/resources/themes/styles/bright_theme_colors.dart** फ़ाइल में अपनी नई थीम के कलर्स संशोधित कर सकते हैं।

<div id="theme-colors"></div>

## थीम कलर्स

अपने प्रोजेक्ट में थीम कलर्स मैनेज करने के लिए, `lib/resources/themes/styles` डायरेक्टरी देखें।
इस डायरेक्टरी में light_theme_colors.dart और dark_theme_colors.dart के लिए स्टाइल कलर्स हैं।

इस फ़ाइल में, आपके पास नीचे दिए गए जैसा कुछ होना चाहिए।

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // app bar
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // bottom tab bar
  @override
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // toast notification
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## विजेट्स में कलर्स का उपयोग

``` dart
import 'package:flutter_app/config/theme.dart';
...

// gets the light/dark background colour depending on the theme
ThemeColor.get(context).background

// e.g. of using the "ThemeColor" class
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// or

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>

## बेस स्टाइल्स

बेस स्टाइल्स आपको अपने कोड में एक जगह से विभिन्न विजेट कलर्स को कस्टमाइज़ करने की अनुमति देती हैं।

{{ config('app.name') }} आपके प्रोजेक्ट के लिए `lib/resources/themes/styles/color_styles.dart` में पहले से कॉन्फ़िगर्ड बेस स्टाइल्स के साथ आता है।

ये स्टाइल्स `light_theme_colors.dart` और `dart_theme_colors.dart` में आपके थीम कलर्स के लिए एक इंटरफ़ेस प्रदान करती हैं।

<br>

फ़ाइल `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // general
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // app bar
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // bottom tab bar
  @override
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // toast notification
  Color get toastNotificationBackground;
}
```

आप यहाँ अतिरिक्त स्टाइल्स जोड़ सकते हैं और फिर अपनी थीम में कलर्स लागू कर सकते हैं।

<div id="switching-theme"></div>

## थीम स्विच करना

{{ config('app.name') }} तुरंत थीम स्विच करने की क्षमता का समर्थन करता है।

उदा. यदि आपको "डार्क थीम" सक्रिय करने के लिए यूज़र द्वारा बटन टैप करने पर थीम स्विच करने की आवश्यकता है।

आप नीचे दिए गए तरीके से इसे सपोर्ट कर सकते हैं:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## फ़ॉन्ट्स

{{ config('app.name') }} में पूरे ऐप में अपना प्राइमरी फ़ॉन्ट अपडेट करना आसान है। `lib/config/design.dart` फ़ाइल खोलें और नीचे दिए गए को अपडेट करें।

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

हम रिपॉज़िटरी में <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> लाइब्रेरी शामिल करते हैं, ताकि आप कम मेहनत में सभी फ़ॉन्ट्स का उपयोग शुरू कर सकें।
फ़ॉन्ट को किसी और में अपडेट करने के लिए, आप निम्नलिखित कर सकते हैं:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

अधिक समझने के लिए आधिकारिक <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> लाइब्रेरी पर फ़ॉन्ट्स देखें

कस्टम फ़ॉन्ट उपयोग करना चाहते हैं? यह गाइड देखें - https://flutter.dev/docs/cookbook/design/fonts

अपना फ़ॉन्ट जोड़ने के बाद, नीचे दिए गए उदाहरण की तरह वेरिएबल बदलें।

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## डिज़ाइन

**config/design.dart** फ़ाइल का उपयोग आपके ऐप के डिज़ाइन एलिमेंट्स मैनेज करने के लिए किया जाता है।

`appFont` वेरिएबल में आपके ऐप का फ़ॉन्ट होता है।

`logo` वेरिएबल का उपयोग आपके ऐप का लोगो प्रदर्शित करने के लिए किया जाता है।

आप **resources/widgets/logo_widget.dart** को संशोधित करके अपना लोगो कैसे प्रदर्शित करना चाहते हैं इसे कस्टमाइज़ कर सकते हैं।

`loader` वेरिएबल का उपयोग लोडर प्रदर्शित करने के लिए किया जाता है। {{ config('app.name') }} इस वेरिएबल का उपयोग कुछ हेल्पर मेथड्स में डिफ़ॉल्ट लोडर विजेट के रूप में करेगा।

आप **resources/widgets/loader_widget.dart** को संशोधित करके अपना लोडर कैसे प्रदर्शित करना चाहते हैं इसे कस्टमाइज़ कर सकते हैं।

<div id="text-extensions"></div>

## टेक्स्ट एक्सटेंशन्स

यहाँ उपलब्ध टेक्स्ट एक्सटेंशन्स हैं जिनका आप {{ config('app.name') }} में उपयोग कर सकते हैं।

| नियम का नाम   | उपयोग | जानकारी |
|---|---|---|
| <a href="#text-extension-display-large">डिस्प्ले लार्ज</a> | displayLarge()  | **displayLarge** textTheme लागू करता है |
| <a href="#text-extension-display-medium">डिस्प्ले मीडियम</a> | displayMedium()  | **displayMedium** textTheme लागू करता है |
| <a href="#text-extension-display-small">डिस्प्ले स्मॉल</a> | displaySmall()  | **displaySmall** textTheme लागू करता है |
| <a href="#text-extension-heading-large">हेडिंग लार्ज</a> | headingLarge()  | **headingLarge** textTheme लागू करता है |
| <a href="#text-extension-heading-medium">हेडिंग मीडियम</a> | headingMedium()  | **headingMedium** textTheme लागू करता है |
| <a href="#text-extension-heading-small">हेडिंग स्मॉल</a> | headingSmall()  | **headingSmall** textTheme लागू करता है |
| <a href="#text-extension-title-large">टाइटल लार्ज</a> | titleLarge()  | **titleLarge** textTheme लागू करता है |
| <a href="#text-extension-title-medium">टाइटल मीडियम</a> | titleMedium()  | **titleMedium** textTheme लागू करता है |
| <a href="#text-extension-title-small">टाइटल स्मॉल</a> | titleSmall()  | **titleSmall** textTheme लागू करता है |
| <a href="#text-extension-body-large">बॉडी लार्ज</a> | bodyLarge()  | **bodyLarge** textTheme लागू करता है |
| <a href="#text-extension-body-medium">बॉडी मीडियम</a> | bodyMedium()  | **bodyMedium** textTheme लागू करता है |
| <a href="#text-extension-body-small">बॉडी स्मॉल</a> | bodySmall()  | **bodySmall** textTheme लागू करता है |
| <a href="#text-extension-label-large">लेबल लार्ज</a> | labelLarge()  | **labelLarge** textTheme लागू करता है |
| <a href="#text-extension-label-medium">लेबल मीडियम</a> | labelMedium()  | **labelMedium** textTheme लागू करता है |
| <a href="#text-extension-label-small">लेबल स्मॉल</a> | labelSmall()  | **labelSmall** textTheme लागू करता है |
| <a href="#text-extension-font-weight-bold">फ़ॉन्ट वेट बोल्ड</a> | fontWeightBold  | Text विजेट पर फ़ॉन्ट वेट बोल्ड लागू करता है |
| <a href="#text-extension-font-weight-bold">फ़ॉन्ट वेट लाइट</a> | fontWeightLight  | Text विजेट पर फ़ॉन्ट वेट लाइट लागू करता है |
| <a href="#text-extension-set-color">कलर सेट करें</a> | setColor(context, (color) => colors.primaryAccent)  | Text विजेट पर अलग टेक्स्ट कलर सेट करें |
| <a href="#text-extension-align-left">बाएँ संरेखित</a> | alignLeft  | फ़ॉन्ट को बाईं ओर संरेखित करें |
| <a href="#text-extension-align-right">दाएँ संरेखित</a> | alignRight  | फ़ॉन्ट को दाईं ओर संरेखित करें |
| <a href="#text-extension-align-center">केंद्र संरेखित</a> | alignCenter  | फ़ॉन्ट को केंद्र में संरेखित करें |
| <a href="#text-extension-set-max-lines">अधिकतम पंक्तियाँ सेट करें</a> | setMaxLines(int maxLines)  | टेक्स्ट विजेट के लिए अधिकतम पंक्तियाँ सेट करें |

<br>


<div id="text-extension-display-large"></div>

#### डिस्प्ले लार्ज

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### डिस्प्ले मीडियम

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### डिस्प्ले स्मॉल

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### हेडिंग लार्ज

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### हेडिंग मीडियम

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### हेडिंग स्मॉल

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### टाइटल लार्ज

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### टाइटल मीडियम

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### टाइटल स्मॉल

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### बॉडी लार्ज

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### बॉडी मीडियम

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### बॉडी स्मॉल

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### लेबल लार्ज

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### लेबल मीडियम

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### लेबल स्मॉल

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### फ़ॉन्ट वेट बोल्ड

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### फ़ॉन्ट वेट लाइट

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### कलर सेट करें

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>

#### बाएँ संरेखित

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### दाएँ संरेखित

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### केंद्र संरेखित

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### अधिकतम पंक्तियाँ सेट करें

``` dart
Text("Hello World").setMaxLines(5)
```
