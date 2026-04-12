# Connective

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Connective-Widget](#connective-widget "Connective-Widget")
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

Verwenden Sie `noInternet`, um ein Fallback-Widget anzuzeigen, wenn das Gerät kein Internet hat (WLAN, Mobilfunk und Ethernet alle nicht vorhanden):

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

    // Verbindungstyp anzeigen
    return Text('Connected via: ${state.name}');
  },
)
```

Der Builder empfängt:
- `context` - BuildContext
- `state` - `NyConnectivityState`-Enum (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Zeigen Sie ein Banner am oberen Bildschirmrand an, wenn kein Internet vorhanden ist (WLAN, Mobilfunk und Ethernet alle nicht vorhanden):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Ihr Hauptinhalt
      MyPageContent(),

      // Offline-Banner (wird bei Online-Verbindung automatisch ausgeblendet)
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
  animate: true, // Ein-/Ausblend-Animation
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity-Helfer

Die Klasse `NyConnectivity` bietet statische Methoden zur Prüfung der Konnektivität:

### Online/Offline prüfen

``` dart
if (await NyConnectivity.isOnline()) {
  // API-Anfrage stellen
  final data = await api.fetchData();
} else {
  // Aus Cache laden
  final data = await cache.getData();
}

// Oder prüfen, ob offline
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Bestimmte Verbindungstypen prüfen

``` dart
if (await NyConnectivity.isWifi()) {
  // Grosse Dateien ueber WLAN herunterladen
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Vor Datenverbrauch warnen
  showDataWarning();
}

// Weitere Methoden:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Auf Internet prüfen

`hasInternet()` ist strenger als `isOnline()` -- es gibt nur `true` zurueck, wenn das Gerät über WLAN, Mobilfunk oder Ethernet verbunden ist. VPN-, Bluetooth- und Satellitenverbindungen sind ausgeschlossen.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Bestaetigter Internetzugang ueber WLAN, Mobilfunk oder Ethernet
  await syncData();
}
```

### Aktuellen Status abrufen

``` dart
// Alle aktiven Verbindungstypen abrufen
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Lesbaren String abrufen
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

// Vergessen Sie nicht, die Subscription beim Beenden abzubrechen
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Bedingte Ausführung

``` dart
// Nur bei Online-Verbindung ausfuehren (gibt null zurueck, wenn offline)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Verschiedene Callbacks je nach Status ausfuehren
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
// Ein anderes Widget anzeigen, wenn offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Nur online anzeigen

``` dart
// Vollstaendig ausblenden, wenn offline
SyncButton().onlyOnline()
```

### Nur offline anzeigen

``` dart
// Nur anzeigen, wenn offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parameter

### Connective

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `noInternet` | `Widget?` | - | Widget, wenn WLAN, Mobilfunk und Ethernet alle nicht vorhanden |
| `child` | `Widget?` | - | Widget bei verfügbarem Internet |
| `onConnectivityChanged` | `Function?` | - | Callback bei Änderung |

### OfflineBanner

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `message` | `String` | `'No internet connection'` | Bannertext |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Banner-Hintergrund |
| `textColor` | `Color?` | `Colors.white` | Textfarbe |
| `icon` | `IconData?` | `Icons.wifi_off` | Banner-Symbol |
| `height` | `double` | `40` | Banner-Höhe |
| `animate` | `bool` | `true` | Slide-Animation aktivieren |
| `animationDuration` | `Duration` | `300ms` | Animationsdauer |

### NyConnectivityState-Enum

| Wert | Beschreibung |
|------|-------------|
| `wifi` | Verbunden über WLAN |
| `mobile` | Verbunden über Mobilfunk |
| `ethernet` | Verbunden über Ethernet |
| `vpn` | Verbunden über VPN |
| `bluetooth` | Verbunden über Bluetooth |
| `satellite` | Verbunden über Satellit |
| `other` | Anderer Verbindungstyp |
| `none` | Keine Verbindung |
