# Connective

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Connective-Widget](#connective-widget "Connective-Widget")
    - [Zustandsbasierte Builder](#state-builders "Zustandsbasierte Builder")
    - [Benutzerdefinierter Builder](#custom-builder "Benutzerdefinierter Builder")
- [OfflineBanner-Widget](#offline-banner "OfflineBanner-Widget")
- [NyConnectivity-Helfer](#connectivity-helper "NyConnectivity-Helfer")
- [Widget-Erweiterungen](#extensions "Widget-Erweiterungen")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} bietet konnektivitätsbewusste Widgets und Hilfsmittel, um Apps zu erstellen, die auf Netzwerkänderungen reagieren. Das **Connective**-Widget baut sich automatisch neu auf, wenn sich die Konnektivität ändert, während der **NyConnectivity**-Helfer statische Methoden zur Prüfung des Verbindungsstatus bereitstellt.

<div id="connective-widget"></div>

## Connective-Widget

Das `Connective`-Widget lauscht auf Konnektivitätsänderungen und baut sich basierend auf dem aktuellen Netzwerkzustand neu auf.

<div id="state-builders"></div>

### Zustandsbasierte Builder

Stellen Sie verschiedene Widgets für jeden Verbindungstyp bereit:

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

#### Verfügbare Zustände

| Eigenschaft | Beschreibung |
|-------------|-------------|
| `onWifi` | Widget bei WiFi-Verbindung |
| `onMobile` | Widget bei Mobilfunkverbindung |
| `onEthernet` | Widget bei Ethernet-Verbindung |
| `onVpn` | Widget bei VPN-Verbindung |
| `onBluetooth` | Widget bei Bluetooth-Verbindung |
| `onOther` | Widget für andere Verbindungstypen |
| `onNone` | Widget wenn offline |
| `child` | Standard-Widget, wenn kein spezifischer Handler bereitgestellt wird |

<div id="custom-builder"></div>

### Benutzerdefinierter Builder

Verwenden Sie `Connective.builder` für volle Kontrolle über die UI:

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

Der Builder empfängt:
- `context` - BuildContext
- `state` - `NyConnectivityState`-Enum (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` zur Prüfung mehrerer Verbindungen

### Auf Änderungen lauschen

Verwenden Sie `onConnectivityChanged`, um auf Konnektivitätsänderungen zu reagieren:

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

## OfflineBanner-Widget

Zeigen Sie ein Banner am oberen Bildschirmrand an, wenn offline:

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

### Banner anpassen

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

## NyConnectivity-Helfer

Die Klasse `NyConnectivity` bietet statische Methoden zur Prüfung der Konnektivität:

### Online/Offline prüfen

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

### Bestimmte Verbindungstypen prüfen

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

### Aktuellen Status abrufen

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

### Auf Änderungen lauschen

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

### Bedingte Ausführung

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

## Widget-Erweiterungen

Fügen Sie jedem Widget schnell Konnektivitätsbewusstsein hinzu:

### Offline-Alternative anzeigen

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Nur online anzeigen

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Nur offline anzeigen

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parameter

### Connective

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `onWifi` | `Widget?` | - | Widget bei WiFi |
| `onMobile` | `Widget?` | - | Widget bei Mobilfunk |
| `onEthernet` | `Widget?` | - | Widget bei Ethernet |
| `onVpn` | `Widget?` | - | Widget bei VPN |
| `onBluetooth` | `Widget?` | - | Widget bei Bluetooth |
| `onOther` | `Widget?` | - | Widget für andere Verbindungen |
| `onNone` | `Widget?` | - | Widget wenn offline |
| `child` | `Widget?` | - | Standard-Widget |
| `showLoadingOnInit` | `bool` | `false` | Laden beim Prüfen anzeigen |
| `loadingWidget` | `Widget?` | - | Benutzerdefiniertes Lade-Widget |
| `onConnectivityChanged` | `Function?` | - | Callback bei Änderung |

### OfflineBanner

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `message` | `String` | `'No internet connection'` | Bannertext |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Banner-Hintergrund |
| `textColor` | `Color?` | `Colors.white` | Textfarbe |
| `icon` | `IconData?` | `Icons.wifi_off` | Banner-Icon |
| `height` | `double` | `40` | Banner-Höhe |
| `animate` | `bool` | `true` | Slide-Animation aktivieren |
| `animationDuration` | `Duration` | `300ms` | Animationsdauer |

### NyConnectivityState-Enum

| Wert | Beschreibung |
|------|-------------|
| `wifi` | Verbunden über WiFi |
| `mobile` | Verbunden über Mobilfunk |
| `ethernet` | Verbunden über Ethernet |
| `vpn` | Verbunden über VPN |
| `bluetooth` | Verbunden über Bluetooth |
| `other` | Anderer Verbindungstyp |
| `none` | Keine Verbindung |
