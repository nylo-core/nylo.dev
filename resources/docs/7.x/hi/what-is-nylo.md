# {{ config('app.name') }} क्या है?

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- ऐप डेवलपमेंट
    - [Flutter में नए हैं?](#new-to-flutter "Flutter में नए हैं?")
    - [रखरखाव और रिलीज़ शेड्यूल](#maintenance-and-release-schedule "रखरखाव और रिलीज़ शेड्यूल")
- क्रेडिट
    - [फ्रेमवर्क डिपेंडेंसीज़](#framework-dependencies "फ्रेमवर्क डिपेंडेंसीज़")
    - [योगदानकर्ता](#contributors "योगदानकर्ता")


<div id="introduction"></div>

## परिचय

{{ config('app.name') }} Flutter के लिए एक माइक्रो-फ्रेमवर्क है जो ऐप डेवलपमेंट को सरल बनाने में मदद करता है। यह पूर्व-कॉन्फ़िगर्ड आवश्यक चीज़ों के साथ एक संरचित बॉयलरप्लेट प्रदान करता है ताकि आप इंफ्रास्ट्रक्चर सेटअप के बजाय अपने ऐप की सुविधाओं पर ध्यान केंद्रित कर सकें।

{{ config('app.name') }} में शामिल है:

- **राउटिंग** - गार्ड्स और डीप लिंकिंग के साथ सरल, घोषणात्मक रूट प्रबंधन
- **नेटवर्किंग** - Dio, इंटरसेप्टर्स और रिस्पॉन्स मॉर्फिंग के साथ API सेवाएँ
- **स्टेट मैनेजमेंट** - NyState और ग्लोबल स्टेट अपडेट्स के साथ रिएक्टिव स्टेट
- **लोकलाइज़ेशन** - JSON ट्रांसलेशन फ़ाइलों के साथ बहु-भाषा समर्थन
- **थीम्स** - थीम स्विचिंग के साथ लाइट/डार्क मोड
- **लोकल स्टोरेज** - Backpack और NyStorage के साथ सुरक्षित स्टोरेज
- **फ़ॉर्म्स** - वैलिडेशन और फ़ील्ड टाइप्स के साथ फ़ॉर्म हैंडलिंग
- **पुश नोटिफ़िकेशन** - लोकल और रिमोट नोटिफ़िकेशन सपोर्ट
- **CLI टूल (Metro)** - पेज, कंट्रोलर, मॉडल और अन्य जेनरेट करें

<div id="new-to-flutter"></div>

## Flutter में नए हैं?

यदि आप Flutter में नए हैं, तो आधिकारिक संसाधनों से शुरुआत करें:

- <a href="https://flutter.dev" target="_BLANK">Flutter डॉक्यूमेंटेशन</a> - व्यापक गाइड और API रेफरेंस
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube चैनल</a> - ट्यूटोरियल और अपडेट्स
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter कुकबुक</a> - सामान्य कार्यों के लिए व्यावहारिक रेसिपी

एक बार जब आप Flutter की मूल बातों से परिचित हो जाएँ, {{ config('app.name') }} सहज लगेगा क्योंकि यह मानक Flutter पैटर्न पर बना है।


<div id="maintenance-and-release-schedule"></div>

## रखरखाव और रिलीज़ शेड्यूल

{{ config('app.name') }} <a href="https://semver.org" target="_BLANK">सिमेंटिक वर्शनिंग</a> का पालन करता है:

- **मेजर रिलीज़** (7.x → 8.x) - ब्रेकिंग चेंजेज़ के लिए साल में एक बार
- **माइनर रिलीज़** (7.0 → 7.1) - नई सुविधाएँ, बैकवर्ड कम्पैटिबल
- **पैच रिलीज़** (7.0.0 → 7.0.1) - बग फ़िक्स और छोटे सुधार

बग फ़िक्स और सुरक्षा पैच GitHub रिपॉज़िटरीज़ के माध्यम से तुरंत संभाले जाते हैं।


<div id="framework-dependencies"></div>

## फ्रेमवर्क डिपेंडेंसीज़

{{ config('app.name') }} v7 इन ओपन सोर्स पैकेजों पर बना है:

### कोर डिपेंडेंसीज़

| पैकेज | उद्देश्य |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | API अनुरोधों के लिए HTTP क्लाइंट |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | सुरक्षित लोकल स्टोरेज |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | अंतर्राष्ट्रीयकरण और फ़ॉर्मेटिंग |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | स्ट्रीम्स के लिए रिएक्टिव एक्सटेंशन |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | ऑब्जेक्ट्स के लिए वैल्यू इक्वालिटी |

### UI और विजेट्स

| पैकेज | उद्देश्य |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | स्केलेटन लोडिंग इफ़ेक्ट्स |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | टोस्ट नोटिफ़िकेशन |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | पुल-टू-रिफ्रेश फ़ंक्शनैलिटी |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | स्टैगर्ड ग्रिड लेआउट |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | डेट पिकर फ़ील्ड्स |

### नोटिफ़िकेशन और कनेक्टिविटी

| पैकेज | उद्देश्य |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | लोकल पुश नोटिफ़िकेशन |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | नेटवर्क कनेक्टिविटी स्टेटस |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | ऐप आइकन बैज |

### यूटिलिटीज़

| पैकेज | उद्देश्य |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | URL और ऐप्स खोलें |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | स्ट्रिंग केस कन्वर्शन |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID जेनरेशन |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | फ़ाइल सिस्टम पाथ |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | इनपुट मास्किंग |


<div id="contributors"></div>

## योगदानकर्ता

{{ config('app.name') }} में योगदान देने वाले सभी लोगों का धन्यवाद! यदि आपने योगदान दिया है, तो यहाँ जोड़े जाने के लिए <a href="mailto:support@nylo.dev">support@nylo.dev</a> पर संपर्क करें।

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (निर्माता)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
