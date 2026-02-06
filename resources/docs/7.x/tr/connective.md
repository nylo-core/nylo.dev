# Connective

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Connective Widget](#connective-widget "Connective Widget")
    - [Duruma Dayal&#305; Olu&#351;turucular](#state-builders "Duruma Dayal&#305; Olu&#351;turucular")
    - [&#214;zel Olu&#351;turucu](#custom-builder "&#214;zel Olu&#351;turucu")
- [OfflineBanner Widget](#offline-banner "OfflineBanner Widget")
- [NyConnectivity Yard&#305;mc&#305;s&#305;](#connectivity-helper "NyConnectivity Yard&#305;mc&#305;s&#305;")
- [Widget Uzant&#305;lar&#305;](#extensions "Widget Uzant&#305;lar&#305;")
- [Parametreler](#parameters "Parametreler")


<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }}, a&#287; de&#287;i&#351;ikliklerine yan&#305;t veren uygulamalar olu&#351;turman&#305;za yard&#305;mc&#305; olmak i&#231;in ba&#287;lant&#305; fark&#305;ndal&#305;&#287;&#305;na sahip widget'lar ve yard&#305;mc&#305; ara&#231;lar sa&#287;lar. **Connective** widget'&#305;, ba&#287;lant&#305; de&#287;i&#351;ti&#287;inde otomatik olarak yeniden olu&#351;turulurken, **NyConnectivity** yard&#305;mc&#305;s&#305; ba&#287;lant&#305; durumunu kontrol etmek i&#231;in statik metotlar sa&#287;lar.

<div id="connective-widget"></div>

## Connective Widget

`Connective` widget'&#305;, ba&#287;lant&#305; de&#287;i&#351;ikliklerini dinler ve mevcut a&#287; durumuna g&#246;re yeniden olu&#351;turulur.

<div id="state-builders"></div>

### Duruma Dayal&#305; Olu&#351;turucular

Her ba&#287;lant&#305; t&#252;r&#252; i&#231;in farkl&#305; widget'lar sa&#287;lay&#305;n:

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

#### Mevcut Durumlar

| &#214;zellik | A&#231;&#305;klama |
|----------|-------------|
| `onWifi` | WiFi ile ba&#287;land&#305;&#287;&#305;nda g&#246;sterilen widget |
| `onMobile` | Mobil veri ile ba&#287;land&#305;&#287;&#305;nda g&#246;sterilen widget |
| `onEthernet` | Ethernet ile ba&#287;land&#305;&#287;&#305;nda g&#246;sterilen widget |
| `onVpn` | VPN ile ba&#287;land&#305;&#287;&#305;nda g&#246;sterilen widget |
| `onBluetooth` | Bluetooth ile ba&#287;land&#305;&#287;&#305;nda g&#246;sterilen widget |
| `onOther` | Di&#287;er ba&#287;lant&#305; t&#252;rleri i&#231;in widget |
| `onNone` | &#199;evrimd&#305;&#351;&#305;yken g&#246;sterilen widget |
| `child` | Belirli bir i&#351;leyici yoksa varsay&#305;lan widget |

<div id="custom-builder"></div>

### &#214;zel Olu&#351;turucu

Aray&#252;z &#252;zerinde tam kontrol i&#231;in `Connective.builder` kullan&#305;n:

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

Olu&#351;turucu &#351;unlar&#305; al&#305;r:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - Birden fazla ba&#287;lant&#305;y&#305; kontrol etmek i&#231;in `List<ConnectivityResult>`

### De&#287;i&#351;iklikleri Dinleme

Ba&#287;lant&#305; de&#287;i&#351;ti&#287;inde tepki vermek i&#231;in `onConnectivityChanged` kullan&#305;n:

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

## OfflineBanner Widget

&#199;evrimd&#305;&#351;&#305;yken ekran&#305;n &#252;st&#252;nde bir banner g&#246;r&#252;nt&#252;leyin:

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

### Banner'&#305; &#214;zelle&#351;tirme

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

## NyConnectivity Yard&#305;mc&#305;s&#305;

`NyConnectivity` s&#305;n&#305;f&#305;, ba&#287;lant&#305;y&#305; kontrol etmek i&#231;in statik metotlar sa&#287;lar:

### &#199;evrim&#304;&#231;i/&#199;evrimd&#305;&#351;&#305; Kontrol&#252;

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

### Belirli Ba&#287;lant&#305; T&#252;rlerini Kontrol Etme

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

### Mevcut Durumu Alma

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

### De&#287;i&#351;iklikleri Dinleme

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

### Ko&#351;ullu Y&#252;r&#252;tme

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

## Widget Uzant&#305;lar&#305;

Herhangi bir widget'a h&#305;zla ba&#287;lant&#305; fark&#305;ndal&#305;&#287;&#305; ekleyin:

### &#199;evrimd&#305;&#351;&#305; Alternatif G&#246;sterme

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Yaln&#305;zca &#199;evrimi&#231;inde G&#246;sterme

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Yaln&#305;zca &#199;evrimd&#305;&#351;&#305;nda G&#246;sterme

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametreler

### Connective

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | WiFi'deyken widget |
| `onMobile` | `Widget?` | - | Mobil verideyken widget |
| `onEthernet` | `Widget?` | - | Ethernet'teyken widget |
| `onVpn` | `Widget?` | - | VPN'deyken widget |
| `onBluetooth` | `Widget?` | - | Bluetooth'tayken widget |
| `onOther` | `Widget?` | - | Di&#287;er ba&#287;lant&#305;lar i&#231;in widget |
| `onNone` | `Widget?` | - | &#199;evrimd&#305;&#351;&#305;yken widget |
| `child` | `Widget?` | - | Varsay&#305;lan widget |
| `showLoadingOnInit` | `bool` | `false` | Kontrol s&#305;ras&#305;nda y&#252;kleme g&#246;ster |
| `loadingWidget` | `Widget?` | - | &#214;zel y&#252;kleme widget'&#305; |
| `onConnectivityChanged` | `Function?` | - | De&#287;i&#351;iklikte callback |

### OfflineBanner

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Banner metni |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Banner arka plan&#305; |
| `textColor` | `Color?` | `Colors.white` | Metin rengi |
| `icon` | `IconData?` | `Icons.wifi_off` | Banner simgesi |
| `height` | `double` | `40` | Banner y&#252;ksekli&#287;i |
| `animate` | `bool` | `true` | Kayd&#305;rma animasyonu etkinle&#351;tir |
| `animationDuration` | `Duration` | `300ms` | Animasyon s&#252;resi |

### NyConnectivityState Enum

| De&#287;er | A&#231;&#305;klama |
|-------|-------------|
| `wifi` | WiFi ile ba&#287;l&#305; |
| `mobile` | Mobil veri ile ba&#287;l&#305; |
| `ethernet` | Ethernet ile ba&#287;l&#305; |
| `vpn` | VPN ile ba&#287;l&#305; |
| `bluetooth` | Bluetooth ile ba&#287;l&#305; |
| `other` | Di&#287;er ba&#287;lant&#305; t&#252;r&#252; |
| `none` | Ba&#287;lant&#305; yok |
