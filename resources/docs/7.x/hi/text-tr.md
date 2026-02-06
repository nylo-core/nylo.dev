# TextTr

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
- [स्ट्रिंग इंटरपोलेशन](#string-interpolation "स्ट्रिंग इंटरपोलेशन")
- [स्टाइल्ड कंस्ट्रक्टर्स](#styled-constructors "स्टाइल्ड कंस्ट्रक्टर्स")
- [पैरामीटर्स](#parameters "पैरामीटर्स")


<div id="introduction"></div>

## परिचय

**TextTr** विजेट Flutter के `Text` विजेट के चारों ओर एक सुविधाजनक रैपर है जो {{ config('app.name') }} के लोकलाइज़ेशन सिस्टम का उपयोग करके अपनी सामग्री को स्वचालित रूप से अनुवाद करता है।

इसके बजाय लिखने के:

``` dart
Text("hello_world".tr())
```

आप यह लिख सकते हैं:

``` dart
TextTr("hello_world")
```

यह आपके कोड को साफ और अधिक पठनीय बनाता है, विशेष रूप से जब कई अनुवादित स्ट्रिंग्स के साथ काम करना हो।

<div id="basic-usage"></div>

## बेसिक उपयोग

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

विजेट आपकी भाषा फ़ाइलों (जैसे `/lang/en.json`) में अनुवाद की खोज करेगा:

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## स्ट्रिंग इंटरपोलेशन

अपने अनुवादों में डायनामिक वैल्यू इंजेक्ट करने के लिए `arguments` पैरामीटर का उपयोग करें:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

आपकी भाषा फ़ाइल में:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

आउटपुट: **Hello, John!**

### एकाधिक आर्गुमेंट्स

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

आउटपुट: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## स्टाइल्ड कंस्ट्रक्टर्स

`TextTr` नामित कंस्ट्रक्टर्स प्रदान करता है जो स्वचालित रूप से आपकी थीम से टेक्स्ट स्टाइल लागू करते हैं:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

`Theme.of(context).textTheme.displayLarge` स्टाइल का उपयोग करता है।

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

`Theme.of(context).textTheme.headlineLarge` स्टाइल का उपयोग करता है।

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

`Theme.of(context).textTheme.bodyLarge` स्टाइल का उपयोग करता है।

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

`Theme.of(context).textTheme.labelLarge` स्टाइल का उपयोग करता है।

### स्टाइल्ड कंस्ट्रक्टर्स के साथ उदाहरण

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## पैरामीटर्स

`TextTr` सभी स्टैंडर्ड `Text` विजेट पैरामीटर्स को सपोर्ट करता है:

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `data` | `String` | खोजने के लिए अनुवाद कुंजी |
| `arguments` | `Map<String, String>?` | स्ट्रिंग इंटरपोलेशन के लिए की-वैल्यू पेयर्स |
| `style` | `TextStyle?` | टेक्स्ट स्टाइलिंग |
| `textAlign` | `TextAlign?` | टेक्स्ट को कैसे संरेखित करना चाहिए |
| `maxLines` | `int?` | अधिकतम लाइनों की संख्या |
| `overflow` | `TextOverflow?` | ओवरफ्लो को कैसे हैंडल करें |
| `softWrap` | `bool?` | सॉफ्ट ब्रेक पर टेक्स्ट रैप करें या नहीं |
| `textDirection` | `TextDirection?` | टेक्स्ट की दिशा |
| `locale` | `Locale?` | टेक्स्ट रेंडरिंग के लिए लोकेल |
| `semanticsLabel` | `String?` | एक्सेसिबिलिटी लेबल |

## तुलना

| दृष्टिकोण | कोड |
|----------|------|
| पारंपरिक | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| आर्गुमेंट्स के साथ | `TextTr("hello", arguments: {"name": "John"})` |
| स्टाइल्ड | `TextTr.headlineLarge("title")` |
