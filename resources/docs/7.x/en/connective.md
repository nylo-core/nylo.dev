# Connective

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Connective Widget](#connective-widget "Connective Widget")
    - [State-Based Builders](#state-builders "State-Based Builders")
    - [Custom Builder](#custom-builder "Custom Builder")
- [OfflineBanner Widget](#offline-banner "OfflineBanner Widget")
- [NyConnectivity Helper](#connectivity-helper "NyConnectivity Helper")
- [Widget Extensions](#extensions "Widget Extensions")
- [Parameters](#parameters "Parameters")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} provides connectivity-aware widgets and utilities to help you build apps that respond to network changes. The **Connective** widget automatically rebuilds when connectivity changes, while the **NyConnectivity** helper provides static methods for checking connection status.

<div id="connective-widget"></div>

## Connective Widget

The `Connective` widget listens to connectivity changes and rebuilds based on the current network state.

<div id="state-builders"></div>

### State-Based Builders

Provide different widgets for each connection type:

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

#### Available States

| Property | Description |
|----------|-------------|
| `onWifi` | Widget when connected via WiFi |
| `onMobile` | Widget when connected via mobile data |
| `onEthernet` | Widget when connected via Ethernet |
| `onVpn` | Widget when connected via VPN |
| `onBluetooth` | Widget when connected via Bluetooth |
| `onOther` | Widget for other connection types |
| `onNone` | Widget when offline |
| `child` | Default widget if no specific handler is provided |

<div id="custom-builder"></div>

### Custom Builder

Use `Connective.builder` for full control over the UI:

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

The builder receives:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` for checking multiple connections

### Listening to Changes

Use `onConnectivityChanged` to react when connectivity changes:

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

Display a banner at the top of the screen when offline:

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

### Customizing the Banner

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

## NyConnectivity Helper

The `NyConnectivity` class provides static methods for checking connectivity:

### Check if Online/Offline

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

### Check Specific Connection Types

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

### Get Current Status

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

### Listen to Changes

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

### Conditional Execution

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

## Widget Extensions

Quickly add connectivity awareness to any widget:

### Show Offline Alternative

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Only Show When Online

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Only Show When Offline

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parameters

### Connective

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget when on WiFi |
| `onMobile` | `Widget?` | - | Widget when on mobile data |
| `onEthernet` | `Widget?` | - | Widget when on Ethernet |
| `onVpn` | `Widget?` | - | Widget when on VPN |
| `onBluetooth` | `Widget?` | - | Widget when on Bluetooth |
| `onOther` | `Widget?` | - | Widget for other connections |
| `onNone` | `Widget?` | - | Widget when offline |
| `child` | `Widget?` | - | Default widget |
| `showLoadingOnInit` | `bool` | `false` | Show loading while checking |
| `loadingWidget` | `Widget?` | - | Custom loading widget |
| `onConnectivityChanged` | `Function?` | - | Callback on change |

### OfflineBanner

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Banner text |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Banner background |
| `textColor` | `Color?` | `Colors.white` | Text color |
| `icon` | `IconData?` | `Icons.wifi_off` | Banner icon |
| `height` | `double` | `40` | Banner height |
| `animate` | `bool` | `true` | Enable slide animation |
| `animationDuration` | `Duration` | `300ms` | Animation duration |

### NyConnectivityState Enum

| Value | Description |
|-------|-------------|
| `wifi` | Connected via WiFi |
| `mobile` | Connected via mobile data |
| `ethernet` | Connected via Ethernet |
| `vpn` | Connected via VPN |
| `bluetooth` | Connected via Bluetooth |
| `other` | Other connection type |
| `none` | No connection |
