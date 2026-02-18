# Connective

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Buildery oparte na stanie](#state-builders "Buildery oparte na stanie")
    - [Niestandardowy builder](#custom-builder "Niestandardowy builder")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Rozszerzenia widgetów](#extensions "Rozszerzenia widgetów")
- [Parametry](#parameters "Parametry")


<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} udostępnia widgety i narzędzia reagujące na łączność sieciową, które pomagają budować aplikacje reagujące na zmiany sieci. Widget **Connective** automatycznie przebudowuje się przy zmianach łączności, a helper **NyConnectivity** udostępnia statyczne metody do sprawdzania stanu połączenia.

<div id="connective-widget"></div>

## Widget Connective

Widget `Connective` nasłuchuje zmian łączności i przebudowuje się na podstawie bieżącego stanu sieci.

<div id="state-builders"></div>

### Buildery oparte na stanie

Dostarcz różne widgety dla każdego typu połączenia:

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

#### Dostępne stany

| Właściwość | Opis |
|------------|------|
| `onWifi` | Widget przy połączeniu WiFi |
| `onMobile` | Widget przy połączeniu danych mobilnych |
| `onEthernet` | Widget przy połączeniu Ethernet |
| `onVpn` | Widget przy połączeniu VPN |
| `onBluetooth` | Widget przy połączeniu Bluetooth |
| `onOther` | Widget dla innych typów połączeń |
| `onNone` | Widget w trybie offline |
| `child` | Domyślny widget, gdy nie podano konkretnego handlera |

<div id="custom-builder"></div>

### Niestandardowy builder

Użyj `Connective.builder` do pełnej kontroli nad interfejsem:

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

Builder otrzymuje:
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` do sprawdzania wielu połączeń

### Nasłuchiwanie zmian

Użyj `onConnectivityChanged`, aby reagować na zmiany łączności:

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

## Widget OfflineBanner

Wyświetl baner na górze ekranu, gdy urządzenie jest offline:

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

### Dostosowywanie banera

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

## Helper NyConnectivity

Klasa `NyConnectivity` udostępnia statyczne metody do sprawdzania łączności:

### Sprawdzanie online/offline

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

### Sprawdzanie konkretnych typów połączeń

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

### Pobieranie bieżącego statusu

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

### Nasłuchiwanie zmian

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

### Warunkowe wykonywanie

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

## Rozszerzenia widgetów

Szybko dodaj świadomość łączności do dowolnego widgetu:

### Pokaż alternatywę offline

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Pokaż tylko online

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Pokaż tylko offline

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametry

### Connective

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `onWifi` | `Widget?` | - | Widget przy WiFi |
| `onMobile` | `Widget?` | - | Widget przy danych mobilnych |
| `onEthernet` | `Widget?` | - | Widget przy Ethernet |
| `onVpn` | `Widget?` | - | Widget przy VPN |
| `onBluetooth` | `Widget?` | - | Widget przy Bluetooth |
| `onOther` | `Widget?` | - | Widget dla innych połączeń |
| `onNone` | `Widget?` | - | Widget w trybie offline |
| `child` | `Widget?` | - | Domyślny widget |
| `showLoadingOnInit` | `bool` | `false` | Pokaż ładowanie podczas sprawdzania |
| `loadingWidget` | `Widget?` | - | Niestandardowy widget ładowania |
| `onConnectivityChanged` | `Function?` | - | Callback przy zmianie |

### OfflineBanner

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `message` | `String` | `'No internet connection'` | Tekst banera |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Tło banera |
| `textColor` | `Color?` | `Colors.white` | Kolor tekstu |
| `icon` | `IconData?` | `Icons.wifi_off` | Ikona banera |
| `height` | `double` | `40` | Wysokość banera |
| `animate` | `bool` | `true` | Włącz animację wysuwania |
| `animationDuration` | `Duration` | `300ms` | Czas trwania animacji |

### Enum NyConnectivityState

| Wartość | Opis |
|---------|------|
| `wifi` | Połączony przez WiFi |
| `mobile` | Połączony przez dane mobilne |
| `ethernet` | Połączony przez Ethernet |
| `vpn` | Połączony przez VPN |
| `bluetooth` | Połączony przez Bluetooth |
| `other` | Inny typ połączenia |
| `none` | Brak połączenia |
