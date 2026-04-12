# Connective

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [Connective विजेट](#connective-widget "Connective विजेट")
    - [कस्टम बिल्डर](#custom-builder "कस्टम बिल्डर")
- [OfflineBanner विजेट](#offline-banner "OfflineBanner विजेट")
- [NyConnectivity हेल्पर](#connectivity-helper "NyConnectivity हेल्पर")
- [विजेट एक्सटेंशन्स](#extensions "विजेट एक्सटेंशन्स")
- [पैरामीटर्स](#parameters "पैरामीटर्स")


<div id="introduction"></div>

## परिचय

{{ config('app.name') }} कनेक्टिविटी-अवेयर विजेट्स और यूटिलिटीज़ प्रदान करता है जो आपको नेटवर्क बदलावों पर प्रतिक्रिया करने वाले ऐप्स बनाने में मदद करते हैं। **Connective** विजेट कनेक्टिविटी बदलने पर स्वचालित रूप से रीबिल्ड होता है, जबकि **NyConnectivity** हेल्पर कनेक्शन स्थिति जाँचने के लिए स्टैटिक मेथड्स प्रदान करता है।

<div id="connective-widget"></div>

## Connective विजेट

`Connective` विजेट कनेक्टिविटी बदलावों को सुनता है और वर्तमान नेटवर्क स्थिति के आधार पर रीबिल्ड होता है।

जब डिवाइस में इंटरनेट नहीं है (wifi, mobile, या ethernet सभी अनुपस्थित हों) तो फॉलबैक विजेट दिखाने के लिए `noInternet` का उपयोग करें:

``` dart
Connective(
  noInternet: Center(
    child: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.wifi_off, size: 64),
        Text('No internet connection'),
      ],
    ),
  ),
  child: MyContent(),
)
```

<div id="custom-builder"></div>

### कस्टम बिल्डर

UI पर पूर्ण नियंत्रण के लिए `Connective.builder` का उपयोग करें:

``` dart
Connective.builder(
  builder: (context, state, results) {
    if (state == NyConnectivityState.none) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.cloud_off, size: 64, color: Colors.grey),
            SizedBox(height: 16),
            Text('You are offline'),
            ElevatedButton(
              onPressed: () => Navigator.pop(context),
              child: Text('Go Back'),
            ),
          ],
        ),
      );
    }

    // कनेक्शन प्रकार दिखाएँ
    return Text('Connected via: ${state.name}');
  },
)
```

बिल्डर को प्राप्त होता है:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
- `results` - `List<ConnectivityResult>` एकाधिक कनेक्शन जाँचने के लिए

### बदलावों को सुनना

कनेक्टिविटी बदलने पर प्रतिक्रिया करने के लिए `onConnectivityChanged` का उपयोग करें:

``` dart
Connective(
  onConnectivityChanged: (state, results) {
    if (state == NyConnectivityState.none) {
      showSnackbar('You went offline');
    } else {
      showSnackbar('Back online!');
    }
  },
  child: MyContent(),
)
```

<div id="offline-banner"></div>

## OfflineBanner विजेट

जब इंटरनेट नहीं है (wifi, mobile, या ethernet सभी अनुपस्थित हों) तो स्क्रीन के शीर्ष पर एक बैनर दिखाएँ:

``` dart
Scaffold(
  body: Stack(
    children: [
      // आपका मुख्य कंटेंट
      MyPageContent(),

      // ऑफ़लाइन बैनर (ऑनलाइन होने पर अपने आप छुपता है)
      OfflineBanner(),
    ],
  ),
)
```

### बैनर कस्टमाइज़ करना

``` dart
OfflineBanner(
  message: 'Check your connection',
  backgroundColor: Colors.orange,
  textColor: Colors.white,
  icon: Icons.signal_wifi_off,
  height: 50,
  animate: true, // स्लाइड इन/आउट एनिमेशन
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity हेल्पर

`NyConnectivity` क्लास कनेक्टिविटी जाँचने के लिए स्टैटिक मेथड्स प्रदान करता है:

### ऑनलाइन/ऑफ़लाइन जाँचें

``` dart
if (await NyConnectivity.isOnline()) {
  // API अनुरोध करें
  final data = await api.fetchData();
} else {
  // कैश से लोड करें
  final data = await cache.getData();
}

// या ऑफ़लाइन जाँचें
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### विशिष्ट कनेक्शन प्रकार जाँचें

``` dart
if (await NyConnectivity.isWifi()) {
  // WiFi पर बड़ी फ़ाइलें डाउनलोड करें
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // डेटा उपयोग के बारे में चेतावनी दें
  showDataWarning();
}

// अन्य मेथड्स:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### इंटरनेट जाँचें

`hasInternet()` `isOnline()` से अधिक सख्त है — यह केवल `true` रिटर्न करता है जब डिवाइस wifi, mobile, या ethernet के माध्यम से कनेक्ट हो। VPN, bluetooth, और satellite कनेक्शन शामिल नहीं हैं।

``` dart
if (await NyConnectivity.hasInternet()) {
  // wifi, mobile, या ethernet के माध्यम से इंटरनेट की पुष्टि हुई
  await syncData();
}
```

### वर्तमान स्थिति प्राप्त करें

``` dart
// सभी सक्रिय कनेक्शन प्रकार प्राप्त करें
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// मानव-पठनीय स्ट्रिंग प्राप्त करें
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", आदि
```

### बदलावों को सुनें

``` dart
StreamSubscription subscription = NyConnectivity.stream().listen((results) {
  if (results.contains(ConnectivityResult.none)) {
    showOfflineUI();
  } else {
    showOnlineUI();
  }
});

// समाप्त होने पर कैंसिल करना न भूलें
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### सशर्त निष्पादन

``` dart
// केवल ऑनलाइन होने पर निष्पादित करें (ऑफ़लाइन होने पर null रिटर्न करता है)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// स्थिति के आधार पर अलग-अलग कॉलबैक निष्पादित करें
final result = await NyConnectivity.when(
  online: () async => await api.fetchData(),
  offline: () async => await cache.getData(),
);
```

<div id="extensions"></div>

## विजेट एक्सटेंशन्स

किसी भी विजेट में जल्दी से कनेक्टिविटी अवेयरनेस जोड़ें:

### ऑफ़लाइन विकल्प दिखाएँ

``` dart
// ऑफ़लाइन होने पर अलग विजेट दिखाएँ
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### केवल ऑनलाइन होने पर दिखाएँ

``` dart
// ऑफ़लाइन होने पर पूरी तरह छुपाएँ
SyncButton().onlyOnline()
```

### केवल ऑफ़लाइन होने पर दिखाएँ

``` dart
// केवल ऑफ़लाइन होने पर दिखाएँ
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## पैरामीटर्स

### Connective

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | विजेट जब wifi, mobile और ethernet सभी अनुपस्थित हों |
| `child` | `Widget?` | - | इंटरनेट उपलब्ध होने पर दिखाया जाने वाला विजेट |
| `onConnectivityChanged` | `Function?` | - | बदलाव पर कॉलबैक |

### OfflineBanner

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | बैनर टेक्स्ट |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | बैनर बैकग्राउंड |
| `textColor` | `Color?` | `Colors.white` | टेक्स्ट रंग |
| `icon` | `IconData?` | `Icons.wifi_off` | बैनर आइकन |
| `height` | `double` | `40` | बैनर ऊँचाई |
| `animate` | `bool` | `true` | स्लाइड एनिमेशन सक्षम करें |
| `animationDuration` | `Duration` | `300ms` | एनिमेशन अवधि |

### NyConnectivityState Enum

| मान | विवरण |
|-------|-------------|
| `wifi` | WiFi के माध्यम से कनेक्ट |
| `mobile` | मोबाइल डेटा के माध्यम से कनेक्ट |
| `ethernet` | ईथरनेट के माध्यम से कनेक्ट |
| `vpn` | VPN के माध्यम से कनेक्ट |
| `bluetooth` | ब्लूटूथ के माध्यम से कनेक्ट |
| `satellite` | सैटेलाइट के माध्यम से कनेक्ट |
| `other` | अन्य कनेक्शन प्रकार |
| `none` | कोई कनेक्शन नहीं |
