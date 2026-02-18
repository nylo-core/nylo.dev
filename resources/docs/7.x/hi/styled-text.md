# Styled Text

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बुनियादी उपयोग](#basic-usage "बुनियादी उपयोग")
- [चिल्ड्रन मोड](#children-mode "चिल्ड्रन मोड")
- [टेम्पलेट मोड](#template-mode "टेम्पलेट मोड")
  - [प्लेसहोल्डर स्टाइलिंग](#styling-placeholders "प्लेसहोल्डर स्टाइलिंग")
  - [टैप कॉलबैक](#tap-callbacks "टैप कॉलबैक")
  - [पाइप-सेपरेटेड कीज़](#pipe-keys "पाइप-सेपरेटेड कीज़")
- [पैरामीटर](#parameters "पैरामीटर")
- [टेक्स्ट एक्सटेंशन](#text-extensions "टेक्स्ट एक्सटेंशन")
  - [टाइपोग्राफी स्टाइल](#typography-styles "टाइपोग्राफी स्टाइल")
  - [यूटिलिटी मेथड](#utility-methods "यूटिलिटी मेथड")
- [उदाहरण](#examples "उदाहरण")

<div id="introduction"></div>

## परिचय

**StyledText** मिश्रित स्टाइल, टैप कॉलबैक, और पॉइंटर इवेंट के साथ रिच टेक्स्ट प्रदर्शित करने के लिए एक विजेट है। यह कई `TextSpan` चिल्ड्रन के साथ `RichText` विजेट के रूप में रेंडर होता है, जो आपको टेक्स्ट के प्रत्येक खंड पर बारीक नियंत्रण देता है।

StyledText दो मोड का समर्थन करता है:

1. **चिल्ड्रन मोड** -- `Text` विजेट की सूची पास करें, प्रत्येक की अपनी स्टाइल के साथ
2. **टेम्पलेट मोड** -- स्ट्रिंग में `@{{placeholder}}` सिंटैक्स का उपयोग करें और प्लेसहोल्डर को स्टाइल और एक्शन से मैप करें

<div id="basic-usage"></div>

## बुनियादी उपयोग

``` dart
// Children mode - list of Text widgets
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template mode - placeholder syntax
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## चिल्ड्रन मोड

स्टाइल्ड टेक्स्ट बनाने के लिए `Text` विजेट की सूची पास करें:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

बेस `style` किसी भी ऐसे चाइल्ड पर लागू होती है जिसकी अपनी स्टाइल नहीं है।

### पॉइंटर इवेंट

पता लगाएँ कि पॉइंटर किसी टेक्स्ट खंड पर कब प्रवेश करता है या बाहर निकलता है:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## टेम्पलेट मोड

`@{{placeholder}}` सिंटैक्स के साथ `StyledText.template()` का उपयोग करें:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

`@{{ }}` के बीच का टेक्स्ट **प्रदर्शन टेक्स्ट** और स्टाइल और टैप कॉलबैक खोजने के लिए उपयोग की जाने वाली **कुंजी** दोनों है।

<div id="styling-placeholders"></div>

### प्लेसहोल्डर स्टाइलिंग

प्लेसहोल्डर नामों को `TextStyle` ऑब्जेक्ट से मैप करें:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### टैप कॉलबैक

प्लेसहोल्डर नामों को टैप हैंडलर से मैप करें:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### पाइप-सेपरेटेड कीज़

पाइप `|` से अलग की गई कीज़ का उपयोग करके कई प्लेसहोल्डर पर एक ही स्टाइल या कॉलबैक लागू करें:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

यह तीनों प्लेसहोल्डर पर एक ही स्टाइल और कॉलबैक मैप करता है।

<div id="parameters"></div>

## पैरामीटर

### StyledText (चिल्ड्रन मोड)

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | आवश्यक | Text विजेट की सूची |
| `style` | `TextStyle?` | null | सभी चिल्ड्रन के लिए बेस स्टाइल |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | पॉइंटर एंटर कॉलबैक |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | पॉइंटर एग्ज़िट कॉलबैक |
| `spellOut` | `bool?` | null | अक्षर दर अक्षर टेक्स्ट प्रदर्शित करें |
| `softWrap` | `bool` | `true` | सॉफ्ट रैपिंग सक्षम करें |
| `textAlign` | `TextAlign` | `TextAlign.start` | टेक्स्ट अलाइनमेंट |
| `textDirection` | `TextDirection?` | null | टेक्स्ट दिशा |
| `maxLines` | `int?` | null | अधिकतम पंक्तियाँ |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | ओवरफ्लो व्यवहार |
| `locale` | `Locale?` | null | टेक्स्ट लोकेल |
| `strutStyle` | `StrutStyle?` | null | स्ट्रट स्टाइल |
| `textScaler` | `TextScaler?` | null | टेक्स्ट स्केलर |
| `selectionColor` | `Color?` | null | चयन हाइलाइट रंग |

### StyledText.template (टेम्पलेट मोड)

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `text` | `String` | आवश्यक | `@{{placeholder}}` सिंटैक्स वाला टेम्पलेट टेक्स्ट |
| `styles` | `Map<String, TextStyle>?` | null | प्लेसहोल्डर नामों से स्टाइल का मैप |
| `onTap` | `Map<String, VoidCallback>?` | null | प्लेसहोल्डर नामों से टैप कॉलबैक का मैप |
| `style` | `TextStyle?` | null | गैर-प्लेसहोल्डर टेक्स्ट के लिए बेस स्टाइल |

अन्य सभी पैरामीटर (`softWrap`, `textAlign`, `maxLines`, आदि) चिल्ड्रन कंस्ट्रक्टर के समान हैं।

<div id="text-extensions"></div>

## टेक्स्ट एक्सटेंशन

{{ config('app.name') }} Flutter के `Text` विजेट को टाइपोग्राफी और यूटिलिटी मेथड से विस्तारित करता है।

<div id="typography-styles"></div>

### टाइपोग्राफी स्टाइल

किसी भी `Text` विजेट पर मटीरियल डिज़ाइन टाइपोग्राफी स्टाइल लागू करें:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

प्रत्येक वैकल्पिक ओवरराइड स्वीकार करता है:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**उपलब्ध ओवरराइड** (सभी टाइपोग्राफी मेथड के लिए समान):

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `color` | `Color?` | टेक्स्ट रंग |
| `fontSize` | `double?` | फ़ॉन्ट आकार |
| `fontWeight` | `FontWeight?` | फ़ॉन्ट वज़न |
| `fontStyle` | `FontStyle?` | इटैलिक/नॉर्मल |
| `letterSpacing` | `double?` | अक्षर अंतर |
| `wordSpacing` | `double?` | शब्द अंतर |
| `height` | `double?` | पंक्ति की ऊँचाई |
| `decoration` | `TextDecoration?` | टेक्स्ट डेकोरेशन |
| `decorationColor` | `Color?` | डेकोरेशन रंग |
| `decorationStyle` | `TextDecorationStyle?` | डेकोरेशन स्टाइल |
| `decorationThickness` | `double?` | डेकोरेशन मोटाई |
| `fontFamily` | `String?` | फ़ॉन्ट फैमिली |
| `shadows` | `List<Shadow>?` | टेक्स्ट शैडो |
| `overflow` | `TextOverflow?` | ओवरफ्लो व्यवहार |

<div id="utility-methods"></div>

### यूटिलिटी मेथड

``` dart
// Font weight
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alignment
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Max lines
Text("Long text...").setMaxLines(2)

// Font family
Text("Custom font").setFontFamily("Roboto")

// Font size
Text("Big text").setFontSize(24)

// Custom style
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copy with modifications
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## उदाहरण

### नियम और शर्तें लिंक

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### वर्शन प्रदर्शन

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### मिश्रित स्टाइल पैराग्राफ

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### टाइपोग्राफी चेन

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
