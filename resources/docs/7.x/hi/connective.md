# Connective

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [Connective विजेट](#connective-widget "Connective विजेट")
    - [स्टेट-आधारित बिल्डर्स](#state-builders "स्टेट-आधारित बिल्डर्स")
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

<div id="state-builders"></div>

### स्टेट-आधारित बिल्डर्स

प्रत्येक कनेक्शन प्रकार के लिए अलग-अलग विजेट्स प्रदान करें:

``` dart
Connective(
  onWifi: Text('Connected via WiFi'),
  onMobile: Text('Connected via Mobile Data'),
  onNone: Column(
    mainAxisAlignment: MainAxisAlignment.center,
    children: [
      Icon(Icons.wifi_off, size: 64),
      Text('No internet connection'),
    ],
  ),
  child: Text('Connected'), // Default for unspecified states
)
```

#### उपलब्ध स्टेट्स

| प्रॉपर्टी | विवरण |
|----------|-------------|
| `onWifi` | WiFi से कनेक्ट होने पर विजेट |
| `onMobile` | मोबाइल डेटा से कनेक्ट होने पर विजेट |
| `onEthernet` | ईथरनेट से कनेक्ट होने पर विजेट |
| `onVpn` | VPN से कनेक्ट होने पर विजेट |
| `onBluetooth` | ब्लूटूथ से कनेक्ट होने पर विजेट |
| `onOther` | अन्य कनेक्शन प्रकारों के लिए विजेट |
| `onNone` | ऑफ़लाइन होने पर विजेट |
| `child` | कोई विशिष्ट हैंडलर न होने पर डिफ़ॉल्ट विजेट |

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

    // Show connection type
    return Text('Connected via: ${state.name}');
  },
)
```

बिल्डर को प्राप्त होता है:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, other, none)
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

ऑफ़लाइन होने पर स्क्रीन के शीर्ष पर एक बैनर दिखाएँ:

``` dart
Scaffold(
  body: Stack(
    children: [
      // Your main content
      MyPageContent(),

      // Offline banner (auto-hides when online)
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
  animate: true, // Slide in/out animation
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity हेल्पर

`NyConnectivity` क्लास कनेक्टिविटी जाँचने के लिए स्टैटिक मेथड्स प्रदान करता है:

### ऑनलाइन/ऑफ़लाइन जाँचें

``` dart
if (await NyConnectivity.isOnline()) {
  // Make API request
  final data = await api.fetchData();
} else {
  // Load from cache
  final data = await cache.getData();
}

// Or check if offline
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### विशिष्ट कनेक्शन प्रकार जाँचें

``` dart
if (await NyConnectivity.isWifi()) {
  // Download large files on WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Warn about data usage
  showDataWarning();
}

// Other methods:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### वर्तमान स्थिति प्राप्त करें

``` dart
// Get all active connection types
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Get human-readable string
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", etc.
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

// Don't forget to cancel when done
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### सशर्त निष्पादन

``` dart
// Execute only when online (returns null if offline)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Execute different callbacks based on status
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
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### केवल ऑनलाइन होने पर दिखाएँ

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### केवल ऑफ़लाइन होने पर दिखाएँ

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## पैरामीटर्स

### Connective

| पैरामीटर | टाइप | डिफ़ॉल्ट | विवरण |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | WiFi पर होने पर विजेट |
| `onMobile` | `Widget?` | - | मोबाइल डेटा पर होने पर विजेट |
| `onEthernet` | `Widget?` | - | ईथरनेट पर होने पर विजेट |
| `onVpn` | `Widget?` | - | VPN पर होने पर विजेट |
| `onBluetooth` | `Widget?` | - | ब्लूटूथ पर होने पर विजेट |
| `onOther` | `Widget?` | - | अन्य कनेक्शन के लिए विजेट |
| `onNone` | `Widget?` | - | ऑफ़लाइन होने पर विजेट |
| `child` | `Widget?` | - | डिफ़ॉल्ट विजेट |
| `showLoadingOnInit` | `bool` | `false` | जाँच करते समय लोडिंग दिखाएँ |
| `loadingWidget` | `Widget?` | - | कस्टम लोडिंग विजेट |
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
| `other` | अन्य कनेक्शन प्रकार |
| `none` | कोई कनेक्शन नहीं |
