# FadeOverlay

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
- [दिशात्मक कंस्ट्रक्टर्स](#directional "दिशात्मक कंस्ट्रक्टर्स")
- [कस्टमाइज़ेशन](#customization "कस्टमाइज़ेशन")
- [पैरामीटर्स](#parameters "पैरामीटर्स")


<div id="introduction"></div>

## परिचय

**FadeOverlay** विजेट अपने चाइल्ड विजेट पर एक ग्रेडिएंट फ़ेड इफेक्ट लागू करता है। यह विज़ुअल डेप्थ बनाने, इमेजेज़ पर टेक्स्ट की पठनीयता सुधारने, या अपने UI में स्टाइलिस्टिक इफेक्ट्स जोड़ने के लिए उपयोगी है।

<div id="basic-usage"></div>

## बेसिक उपयोग

ग्रेडिएंट फ़ेड लागू करने के लिए किसी भी विजेट को `FadeOverlay` से रैप करें:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

यह ऊपर से पारदर्शी से नीचे डार्क ओवरले तक एक सूक्ष्म फ़ेड बनाता है।

### इमेज पर टेक्स्ट के साथ

``` dart
Stack(
  children: [
    FadeOverlay(
      child: Image.network(
        'https://example.com/image.jpg',
        fit: BoxFit.cover,
      ),
      strength: 0.5,
    ),
    Positioned(
      bottom: 16,
      left: 16,
      child: Text(
        "Photo Title",
        style: TextStyle(color: Colors.white, fontSize: 24),
      ),
    ),
  ],
)
```

<div id="directional"></div>

## दिशात्मक कंस्ट्रक्टर्स

`FadeOverlay` सामान्य फ़ेड दिशाओं के लिए नामित कंस्ट्रक्टर्स प्रदान करता है:

### FadeOverlay.top

नीचे (पारदर्शी) से ऊपर (रंग) की ओर फ़ेड होता है:

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

ऊपर (पारदर्शी) से नीचे (रंग) की ओर फ़ेड होता है:

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

दाएँ (पारदर्शी) से बाएँ (रंग) की ओर फ़ेड होता है:

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

बाएँ (पारदर्शी) से दाएँ (रंग) की ओर फ़ेड होता है:

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## कस्टमाइज़ेशन

### तीव्रता समायोजित करना

`strength` पैरामीटर फ़ेड इफेक्ट की तीव्रता को नियंत्रित करता है (0.0 से 1.0):

``` dart
// Subtle fade
FadeOverlay(
  child: myImage,
  strength: 0.1,
)

// Medium fade
FadeOverlay(
  child: myImage,
  strength: 0.5,
)

// Strong fade
FadeOverlay(
  child: myImage,
  strength: 1.0,
)
```

### कस्टम रंग

अपने डिज़ाइन से मेल खाने के लिए ओवरले रंग बदलें:

``` dart
// Dark blue overlay
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.blue.shade900,
  strength: 0.6,
)

// White overlay for light themes
FadeOverlay.bottom(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.white,
  strength: 0.4,
)
```

### कस्टम ग्रेडिएंट दिशा

गैर-मानक दिशाओं के लिए, `begin` और `end` अलाइनमेंट निर्दिष्ट करें:

``` dart
// Diagonal fade (top-left to bottom-right)
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
  strength: 0.5,
)

// Center outward fade
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.center,
  end: Alignment.bottomCenter,
  strength: 0.4,
)
```

<div id="parameters"></div>

## पैरामीटर्स

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `child` | `Widget` | आवश्यक | जिस विजेट पर फ़ेड इफेक्ट लागू करना है |
| `strength` | `double` | `0.2` | फ़ेड तीव्रता (0.0 से 1.0) |
| `color` | `Color` | `Colors.black` | फ़ेड ओवरले का रंग |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | ग्रेडिएंट शुरुआती अलाइनमेंट |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | ग्रेडिएंट अंतिम अलाइनमेंट |

## उदाहरण: फ़ेड के साथ कार्ड

``` dart
Container(
  height: 200,
  width: double.infinity,
  child: ClipRRect(
    borderRadius: BorderRadius.circular(12),
    child: Stack(
      fit: StackFit.expand,
      children: [
        FadeOverlay.bottom(
          strength: 0.6,
          child: Image.network(
            'https://example.com/product.jpg',
            fit: BoxFit.cover,
          ),
        ),
        Positioned(
          bottom: 16,
          left: 16,
          right: 16,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "Product Name",
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                "\$29.99",
                style: TextStyle(color: Colors.white70),
              ),
            ],
          ),
        ),
      ],
    ),
  ),
)
```
