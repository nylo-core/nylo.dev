# {{ config('app.name') }} में योगदान

---

<a name="section-1"></a>
- [परिचय](#introduction "योगदान का परिचय")
- [शुरुआत करना](#getting-started "योगदान के साथ शुरुआत करना")
- [डेवलपमेंट एनवायरनमेंट](#development-environment "डेवलपमेंट एनवायरनमेंट सेटअप करना")
- [डेवलपमेंट दिशानिर्देश](#development-guidelines "डेवलपमेंट दिशानिर्देश")
- [बदलाव सबमिट करना](#submitting-changes "बदलाव कैसे सबमिट करें")
- [समस्याएँ रिपोर्ट करना](#reporting-issues "समस्याएँ कैसे रिपोर्ट करें")


<div id="introduction"></div>

## परिचय

{{ config('app.name') }} में योगदान देने पर विचार करने के लिए धन्यवाद!

यह गाइड आपको माइक्रो-फ्रेमवर्क में योगदान करने का तरीका समझने में मदद करेगा। चाहे आप बग ठीक कर रहे हों, सुविधाएँ जोड़ रहे हों, या डॉक्यूमेंटेशन में सुधार कर रहे हों, आपका योगदान {{ config('app.name') }} समुदाय के लिए मूल्यवान है।

{{ config('app.name') }} तीन रिपॉज़िटरीज़ में विभाजित है:

| रिपॉज़िटरी | उद्देश्य |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | बॉयलरप्लेट एप्लिकेशन |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | कोर फ्रेमवर्क क्लासेज़ (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | विजेट्स, हेल्पर्स, यूटिलिटीज़ के साथ सपोर्ट लाइब्रेरी (nylo_support) |

<div id="getting-started"></div>

## शुरुआत करना

### रिपॉज़िटरीज़ फ़ोर्क करें

जिन रिपॉज़िटरीज़ में आप योगदान देना चाहते हैं उन्हें फ़ोर्क करें:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Nylo बॉयलरप्लेट फ़ोर्क करें</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Framework फ़ोर्क करें</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Support फ़ोर्क करें</a>

### अपने फ़ोर्क क्लोन करें

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## डेवलपमेंट एनवायरनमेंट

### आवश्यकताएँ

सुनिश्चित करें कि आपके पास निम्नलिखित इंस्टॉल हैं:

| आवश्यकता | न्यूनतम संस्करण |
|-------------|-----------------|
| Flutter | 3.24.0 या उच्चतर |
| Dart SDK | 3.10.7 या उच्चतर |

### लोकल पैकेज लिंक करें

अपने एडिटर में Nylo बॉयलरप्लेट खोलें और अपने लोकल framework और support रिपॉज़िटरीज़ का उपयोग करने के लिए dependency overrides जोड़ें:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

डिपेंडेंसीज़ इंस्टॉल करने के लिए `flutter pub get` चलाएँ।

अब आप framework या support रिपॉज़िटरीज़ में जो बदलाव करेंगे वे Nylo बॉयलरप्लेट में दिखाई देंगे।

### अपने बदलावों का परीक्षण करना

अपने बदलावों का परीक्षण करने के लिए बॉयलरप्लेट ऐप चलाएँ:

``` bash
flutter run
```

विजेट या हेल्पर बदलावों के लिए, उपयुक्त रिपॉज़िटरी में टेस्ट जोड़ने पर विचार करें।

<div id="development-guidelines"></div>

## डेवलपमेंट दिशानिर्देश

### कोड स्टाइल

- आधिकारिक <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart स्टाइल गाइड</a> का पालन करें
- सार्थक वेरिएबल और फ़ंक्शन नाम उपयोग करें
- जटिल लॉजिक के लिए स्पष्ट कमेंट्स लिखें
- पब्लिक API के लिए डॉक्यूमेंटेशन शामिल करें
- कोड को मॉड्यूलर और मेंटेनेबल रखें

### डॉक्यूमेंटेशन

नई सुविधाएँ जोड़ते समय:

- पब्लिक क्लासेज़ और मेथड्स में dartdoc कमेंट्स जोड़ें
- यदि आवश्यक हो तो संबंधित डॉक्यूमेंटेशन फ़ाइलों को अपडेट करें
- डॉक्यूमेंटेशन में कोड उदाहरण शामिल करें

### परीक्षण

बदलाव सबमिट करने से पहले:

- iOS और Android दोनों डिवाइसेज़/सिमुलेटर पर परीक्षण करें
- जहाँ संभव हो बैकवर्ड कम्पैटिबिलिटी सत्यापित करें
- किसी भी ब्रेकिंग चेंजेज़ को स्पष्ट रूप से डॉक्यूमेंट करें
- यह सुनिश्चित करने के लिए मौजूदा टेस्ट चलाएँ कि कुछ भी टूटा नहीं है

<div id="submitting-changes"></div>

## बदलाव सबमिट करना

### पहले चर्चा करें

नई सुविधाओं के लिए, पहले समुदाय के साथ चर्चा करना सबसे अच्छा है:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub चर्चाएँ</a>

### ब्रांच बनाएँ

``` bash
git checkout -b feature/your-feature-name
```

विवरणात्मक ब्रांच नाम उपयोग करें:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### अपने बदलाव कमिट करें

``` bash
git add .
git commit -m "Add: Your feature description"
```

स्पष्ट कमिट संदेश उपयोग करें:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### पुश करें और पुल रिक्वेस्ट बनाएँ

``` bash
git push origin feature/your-feature-name
```

फिर GitHub पर पुल रिक्वेस्ट बनाएँ।

### पुल रिक्वेस्ट दिशानिर्देश

- अपने बदलावों का स्पष्ट विवरण प्रदान करें
- किसी भी संबंधित इश्यू का संदर्भ दें
- यदि लागू हो तो स्क्रीनशॉट या कोड उदाहरण शामिल करें
- सुनिश्चित करें कि आपका PR केवल एक चिंता को संबोधित करता है
- बदलावों को केंद्रित और एटॉमिक रखें

<div id="reporting-issues"></div>

## समस्याएँ रिपोर्ट करना

### रिपोर्ट करने से पहले

1. जाँचें कि समस्या पहले से GitHub पर मौजूद तो नहीं है
2. सुनिश्चित करें कि आप नवीनतम संस्करण का उपयोग कर रहे हैं
3. एक नए प्रोजेक्ट में समस्या को पुन: उत्पन्न करने का प्रयास करें

### कहाँ रिपोर्ट करें

उपयुक्त रिपॉज़िटरी पर समस्याएँ रिपोर्ट करें:

- **बॉयलरप्लेट समस्याएँ**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Framework समस्याएँ**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Support लाइब्रेरी समस्याएँ**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### इश्यू टेम्पलेट

विस्तृत जानकारी प्रदान करें:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### संस्करण जानकारी प्राप्त करना

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
