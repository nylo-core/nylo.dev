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
  - [कलर स्टाइल्स विस्तारित करें](#extending-color-styles "कलर स्टाइल्स विस्तारित करें")
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

प्रत्येक थीम `lib/resources/themes/` के अंतर्गत अपनी उपडायरेक्टरी में रहती है:

- लाइट थीम – `lib/resources/themes/light/light_theme.dart`
- लाइट कलर्स – `lib/resources/themes/light/light_theme_colors.dart`
- डार्क थीम – `lib/resources/themes/dark/dark_theme.dart`
- डार्क कलर्स – `lib/resources/themes/dark/dark_theme_colors.dart`

दोनों थीम्स `lib/resources/themes/base_theme.dart` पर एक साझा बिल्डर और `lib/resources/themes/color_styles.dart` पर `ColorStyles` इंटरफ़ेस साझा करती हैं।



<div id="creating-a-theme"></div>

## थीम बनाना

यदि आप अपने ऐप के लिए एकाधिक थीम्स रखना चाहते हैं, तो थीम फ़ाइलें `lib/resources/themes/` के अंतर्गत मैन्युअल रूप से बनाएं। नीचे दिए गए चरण `bright` को उदाहरण के रूप में उपयोग करते हैं — इसे अपने थीम नाम से बदलें।

**चरण 1:** `lib/resources/themes/bright/bright_theme.dart` पर थीम फ़ाइल बनाएं:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**चरण 2:** `lib/resources/themes/bright/bright_theme_colors.dart` पर कलर्स फ़ाइल बनाएं:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/color_styles.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BrightThemeColors extends ColorStyles {
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFDE7),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFFFBC02D),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  @override
  AppBarColors get appBar => const AppBarColors(
        background: Color(0xFFFBC02D),
        content: Colors.white,
      );

  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Color(0xFFFBC02D),
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

**चरण 3:** नई थीम को `lib/bootstrap/theme.dart` में पंजीकृत करें।

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>(
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

आप `bright_theme_colors.dart` में कलर्स को अपने डिज़ाइन के अनुसार समायोजित कर सकते हैं।

<div id="theme-colors"></div>

## थीम कलर्स

अपने प्रोजेक्ट में थीम कलर्स मैनेज करने के लिए, `lib/resources/themes/light/` और `lib/resources/themes/dark/` डायरेक्टरी देखें। प्रत्येक में अपनी थीम के लिए कलर्स फ़ाइल होती है — `light_theme_colors.dart` और `dark_theme_colors.dart`।

कलर मान समूहों (`general`, `appBar`, `bottomTabBar`) में व्यवस्थित हैं जो फ्रेमवर्क द्वारा परिभाषित हैं। आपकी थीम की कलर्स क्लास `ColorStyles` को विस्तारित करती है और प्रत्येक समूह का एक इंस्टेंस प्रदान करती है:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// सामान्य उपयोग के लिए कलर्स।
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// ऐप बार के लिए कलर्स।
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// बॉटम टैब बार के लिए कलर्स।
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## विजेट्स में कलर्स का उपयोग

सक्रिय थीम के कलर्स पढ़ने के लिए `nyColorStyle<T>(context)` हेल्पर का उपयोग करें। अपने प्रोजेक्ट का `ColorStyles` टाइप पास करें ताकि कॉल पूरी तरह से टाइप्ड हो:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// एक विजेट बिल्ड के अंदर:
final colors = nyColorStyle<ColorStyles>(context);

// सक्रिय थीम का बैकग्राउंड कलर
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// किसी विशेष थीम से कलर्स पढ़ें (चाहे कोई भी सक्रिय हो):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## बेस स्टाइल्स

बेस स्टाइल्स आपको एक ही इंटरफ़ेस के माध्यम से प्रत्येक थीम का वर्णन करने देती हैं। {{ config('app.name') }} `lib/resources/themes/color_styles.dart` के साथ शिप होता है, जो वह अनुबंध है जिसे `light_theme_colors.dart` और `dark_theme_colors.dart` दोनों लागू करते हैं।

`ColorStyles` फ्रेमवर्क से `ThemeColor` को विस्तारित करता है, जो तीन पूर्व-परिभाषित कलर समूहों को एक्सपोज़ करता है: `GeneralColors`, `AppBarColors`, और `BottomTabBarColors`। बेस थीम बिल्डर (`lib/resources/themes/base_theme.dart`) `ThemeData` का निर्माण करते समय इन समूहों को पढ़ता है, इसलिए आप उनमें जो भी डालते हैं वह मिलान करने वाले विजेट्स में स्वचालित रूप से वायर हो जाता है।

<br>

फ़ाइल `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// सामान्य उपयोग के लिए कलर्स।
  @override
  GeneralColors get general;

  /// ऐप बार के लिए कलर्स।
  @override
  AppBarColors get appBar;

  /// बॉटम टैब बार के लिए कलर्स।
  @override
  BottomTabBarColors get bottomTabBar;
}
```

तीन समूह निम्नलिखित फ़ील्ड्स एक्सपोज़ करते हैं:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

इन डिफ़ॉल्ट्स से परे फ़ील्ड्स जोड़ने के लिए — अपने बटन, आइकन, बैज, आदि — [कलर स्टाइल्स विस्तारित करें](#extending-color-styles) देखें।

<div id="extending-color-styles"></div>

## कलर स्टाइल्स विस्तारित करें

<!-- uncertain: new section "Extending color styles" — not present in existing hi locale file -->
तीन डिफ़ॉल्ट समूह (`general`, `appBar`, `bottomTabBar`) एक शुरुआती बिंदु हैं, कोई कठोर सीमा नहीं। `lib/resources/themes/color_styles.dart` आपका है संशोधित करने के लिए — डिफ़ॉल्ट के ऊपर नए कलर समूह (या एकल फ़ील्ड्स) जोड़ें, फिर प्रत्येक थीम की कलर्स क्लास में उन्हें लागू करें।

**1. एक कस्टम कलर समूह परिभाषित करें**

संबंधित कलर्स को एक छोटी अपरिवर्तनीय क्लास में समूहित करें:

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. इसे `ColorStyles` में जोड़ें**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // कस्टम समूह
  IconColors get icons;
}
```

**3. प्रत्येक थीम की कलर्स क्लास में इसे लागू करें**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...मौजूदा ओवरराइड्स...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

`dark_theme_colors.dart` में भी डार्क-मोड मानों के साथ वही `icons` ओवरराइड दोहराएं।

**4. इसे अपने विजेट्स में उपयोग करें**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## थीम स्विच करना

{{ config('app.name') }} तुरंत थीम स्विच करने की क्षमता का समर्थन करता है।

उदा. यदि आपको "डार्क थीम" सक्रिय करने के लिए यूज़र द्वारा बटन टैप करने पर थीम स्विच करने की आवश्यकता है।

आप नीचे दिए गए तरीके से इसे सपोर्ट कर सकते हैं:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // थीम को "dark theme" उपयोग करने के लिए सेट करें
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // थीम को "light theme" उपयोग करने के लिए सेट करें
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## फ़ॉन्ट्स

{{ config('app.name') }} में पूरे ऐप में अपना प्राइमरी फ़ॉन्ट अपडेट करना आसान है। `lib/config/design.dart` खोलें और `DesignConfig.appFont` अपडेट करें।

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

हम रिपॉज़िटरी में <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> लाइब्रेरी शामिल करते हैं, ताकि आप कम मेहनत में सभी फ़ॉन्ट्स का उपयोग शुरू कर सकें। किसी अलग Google Font पर स्विच करने के लिए, बस कॉल बदलें:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

अधिक समझने के लिए आधिकारिक <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> लाइब्रेरी पर फ़ॉन्ट्स देखें।

कस्टम फ़ॉन्ट उपयोग करना चाहते हैं? यह गाइड देखें - https://flutter.dev/docs/cookbook/design/fonts

अपना फ़ॉन्ट जोड़ने के बाद, नीचे दिए गए उदाहरण की तरह वेरिएबल बदलें।

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## डिज़ाइन

**lib/config/design.dart** फ़ाइल का उपयोग आपके ऐप के डिज़ाइन एलिमेंट्स मैनेज करने के लिए किया जाता है। सब कुछ `DesignConfig` क्लास के माध्यम से एक्सपोज़ किया गया है:

`DesignConfig.appFont` में आपके ऐप का फ़ॉन्ट होता है।

`DesignConfig.logo` का उपयोग आपके ऐप का लोगो प्रदर्शित करने के लिए किया जाता है।

आप **lib/resources/widgets/logo_widget.dart** को संशोधित करके अपना लोगो कैसे प्रदर्शित करना चाहते हैं इसे कस्टमाइज़ कर सकते हैं।

`DesignConfig.loader` का उपयोग लोडर प्रदर्शित करने के लिए किया जाता है। {{ config('app.name') }} इस वेरिएबल का उपयोग कुछ हेल्पर मेथड्स में डिफ़ॉल्ट लोडर विजेट के रूप में करेगा।

आप **lib/resources/widgets/loader_widget.dart** को संशोधित करके अपना लोडर कैसे प्रदर्शित करना चाहते हैं इसे कस्टमाइज़ कर सकते हैं।

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
