# स्क्रीनशॉट

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [परिचय](#introduction "स्क्रीनशॉट का परिचय")
- [स्क्रीन कैप्चर करना](#capturing-the-screen "पूरी स्क्रीन कैप्चर करना")
- [एक क्षेत्र कैप्चर करना](#capturing-an-area "ScreenshotArea से स्क्रीन का एक हिस्सा कैप्चर करना")
- [स्क्रीनशॉट सहेजना](#saving-a-screenshot "स्क्रीनशॉट को डिस्क पर सहेजना")
- [स्टोर लिस्टिंग](#store-listings "App Store और Google Play के लिए स्क्रीनशॉट का आकार तय करना")
  - [स्टोर साइज़](#store-sizes "ScreenshotStore साइज़")
- [कंटेंट छिपाना](#hiding-content "स्क्रीनशॉट से कंटेंट छिपाना")
- [विजेट कैप्चर करना](#capturing-a-widget "किसी विजेट को दिखाए बिना कैप्चर करना")
- [लोकेल और थीम](#locales-and-themes "दूसरी लोकेल या थीम में कैप्चर करना")
- [Metro से](#from-metro "Metro से स्क्रीनशॉट लेना")

<div id="introduction"></div>

## परिचय

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` आपके ऐप की स्क्रीन का, या उसके एक हिस्से का PNG लेता है:

``` dart
// पूरी स्क्रीन, डायलॉग और शीट्स समेत
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// स्क्रीन का एक हिस्सा
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

कैप्चर हर बिल्ड मोड में काम करते हैं, इसलिए वे आपके ऐप की सुविधाओं को चला सकते हैं, जैसे रसीद शेयर करना या बग रिपोर्ट के साथ स्क्रीन संलग्न करना। [Metro](#from-metro) और [प्लेबुक्स](/docs/{{ $version }}/playbooks#screenshots) वही कोड उपयोग करते हैं।

एक `Screenshot` PNG को `bytes` के रूप में रखता है, साथ में भौतिक पिक्सेल में उसकी `width` और `height`।

<div id="capturing-the-screen"></div>

## स्क्रीन कैप्चर करना

`Screenshot.capture()` ऐप की पूरी स्क्रीन वैसे ही पढ़ता है जैसे यूज़र उसे देखता है:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| पैरामीटर | विवरण |
|-----------|-------------|
| `pixelRatio` | लॉजिकल पिक्सेल के सापेक्ष आकार। डिफ़ॉल्ट रूप से डिवाइस का, इसलिए PNG में स्क्रीन का नेटिव रेज़ोल्यूशन होता है |
| `settle` | कैप्चर से पहले अगले फ़्रेम की प्रतीक्षा करें (डिफ़ॉल्ट `true`)। जब अभी-अभी फ़्रेम बना हो तब `false` पास करें |
| `locale` | कैप्चर के लिए स्क्रीन को इस भाषा में दिखाएं, जैसे `'es'` |
| `theme` | कैप्चर के लिए स्क्रीन को इस थीम id में दिखाएं, जैसे `'dark'` |

`NyApp.materialApp` से बना ऐप ठीक अपने पेज कैप्चर करता है। यदि आप अपना `MaterialApp` बनाते हैं, तो उसके `builder` में एक `ScreenshotRoot` जोड़ें:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## एक क्षेत्र कैप्चर करना

स्क्रीन के एक हिस्से को `ScreenshotArea` में लपेटें और उसे एक नाम दें:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

फिर उसे उसी नाम से कैप्चर करें:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` वही `pixelRatio`, `settle`, `locale` और `theme` पैरामीटर लेता है जो `capture` लेता है। `Screenshot.areas` स्क्रीन पर मौजूद क्षेत्रों के नामों की सूची देता है।

<div id="saving-a-screenshot"></div>

## स्क्रीनशॉट सहेजना

`store` PNG को फ़ाइल स्टोरेज डिस्क पर सहेजता है और पाथ लौटाता है:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

`disk` के बिना, यह डिफ़ॉल्ट डिस्क पर सहेजता है। इमेज को शेयर या अपलोड करने के लिए, `shot.bytes` पढ़ें।

<div id="store-listings"></div>

## स्टोर लिस्टिंग

`forStore` ऐप स्टोर द्वारा मांगे गए सटीक आकार में एक नया स्क्रीनशॉट लौटाता है:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

लगभग उसी आकार की स्क्रीन पूरे साइज़ को भर देती है, और कोई अन्य उसके अंदर फ़िट की जाती है। `frame: true` के साथ, स्क्रीन `caption` के नीचे फ़ोन की रूपरेखा में बनाई जाती है।

| पैरामीटर | विवरण |
|-----------|-------------|
| `frame` | स्क्रीन को फ़ोन की रूपरेखा के अंदर बनाएं |
| `caption` | फ़ोन के ऊपर का टेक्स्ट, `frame` के साथ उपयोग होता है |
| `background` | स्क्रीन के आसपास का `Color` |

रूपरेखा को स्क्रीनशॉट के अपने आकार में बनाने के लिए `framed()` का उपयोग करें:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### स्टोर साइज़

| साइज़ | Id | पिक्सेल |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` हर साइज़ की सूची देता है, और `ScreenshotStore.named('ios-6.9')` उसके id से एक साइज़ ढूँढता है।

<div id="hiding-content"></div>

## कंटेंट छिपाना

किसी भी निजी चीज़ को `ScreenshotHidden` में लपेटें। वह स्क्रीन पर सामान्य रूप से दिखता है, और हर स्क्रीनशॉट में एक ग्रे ब्लॉक उसे ढक देता है:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

ग्रे ब्लॉक की जगह अपना प्लेसहोल्डर बनाने के लिए `replacement` पास करें।

<div id="capturing-a-widget"></div>

## विजेट कैप्चर करना

`Screenshot.widget` किसी विजेट को दिखाए बिना कैप्चर करता है, जैसे केवल इमेज के लिए बनाया गया शेयर कार्ड:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG उतना ही बड़ा होता है जितना विजेट, इसलिए सटीक आकार के लिए उसे `SizedBox` में लपेटें। विजेट को उस context की थीम और लोकलाइज़ेशन देने के लिए `context`, जिस स्पेस में वह लेआउट होता है उसे सेट करने के लिए `size`, और इमेजेज़ को लोड होने का समय देने के लिए `delay` पास करें।

<div id="locales-and-themes"></div>

## लोकेल और थीम

एक कैप्चर के लिए स्क्रीन को दूसरे तरीके से दिखाने के लिए `locale` या `theme` पास करें:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

ऐप उसी जगह बदलता है, पेज, उसकी स्टेट और नेविगेशन स्टैक को बनाए रखते हुए, और तुरंत बाद वापस लौट जाता है। कुछ भी सहेजा नहीं जाता, इसलिए यूज़र की अपनी पसंद बनी रहती है। लोकेल के लिए आपके असेट्स में एक भाषा फ़ाइल चाहिए, और थीम के लिए एक रजिस्टर्ड थीम id।

<div id="from-metro"></div>

## Metro से

टर्मिनल से अपने चल रहे ऐप का स्क्रीनशॉट सहेजें:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

हर विकल्प के लिए [Metro Live](/docs/{{ $version }}/metro-live#screenshots) देखें। एक [प्लेबुक](/docs/{{ $version }}/playbooks#screenshots) हर भाषा और थीम में पूरी यात्रा के स्क्रीनशॉट ले सकती है।
