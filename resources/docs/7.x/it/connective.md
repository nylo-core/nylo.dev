# Connective

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Builder Basati sullo Stato](#state-builders "Builder Basati sullo Stato")
    - [Builder Personalizzato](#custom-builder "Builder Personalizzato")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Estensioni Widget](#extensions "Estensioni Widget")
- [Parametri](#parameters "Parametri")


<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} fornisce widget e utilita' sensibili alla connettivita' per aiutarti a costruire app che rispondono ai cambiamenti di rete. Il widget **Connective** si ricostruisce automaticamente quando la connettivita' cambia, mentre l'helper **NyConnectivity** fornisce metodi statici per controllare lo stato della connessione.

<div id="connective-widget"></div>

## Widget Connective

Il widget `Connective` ascolta i cambiamenti di connettivita' e si ricostruisce in base allo stato di rete corrente.

<div id="state-builders"></div>

### Builder Basati sullo Stato

Fornisci widget diversi per ogni tipo di connessione:

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

#### Stati Disponibili

| Proprieta' | Descrizione |
|----------|-------------|
| `onWifi` | Widget quando connesso tramite WiFi |
| `onMobile` | Widget quando connesso tramite dati mobili |
| `onEthernet` | Widget quando connesso tramite Ethernet |
| `onVpn` | Widget quando connesso tramite VPN |
| `onBluetooth` | Widget quando connesso tramite Bluetooth |
| `onOther` | Widget per altri tipi di connessione |
| `onNone` | Widget quando offline |
| `child` | Widget predefinito se non viene fornito un gestore specifico |

<div id="custom-builder"></div>

### Builder Personalizzato

Usa `Connective.builder` per il controllo completo dell'interfaccia:

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

Il builder riceve:
- `context` - BuildContext
- `state` - Enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` per verificare connessioni multiple

### Ascolto dei Cambiamenti

Usa `onConnectivityChanged` per reagire quando la connettivita' cambia:

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

Mostra un banner nella parte superiore dello schermo quando sei offline:

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

### Personalizzazione del Banner

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

La classe `NyConnectivity` fornisce metodi statici per verificare la connettivita':

### Verificare se Online/Offline

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

### Verificare Tipi di Connessione Specifici

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

### Ottenere lo Stato Corrente

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

### Ascoltare i Cambiamenti

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

### Esecuzione Condizionale

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

## Estensioni Widget

Aggiungi rapidamente la consapevolezza della connettivita' a qualsiasi widget:

### Mostrare Alternativa Offline

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Mostrare Solo Quando Online

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Mostrare Solo Quando Offline

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametri

### Connective

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget quando connesso tramite WiFi |
| `onMobile` | `Widget?` | - | Widget quando connesso tramite dati mobili |
| `onEthernet` | `Widget?` | - | Widget quando connesso tramite Ethernet |
| `onVpn` | `Widget?` | - | Widget quando connesso tramite VPN |
| `onBluetooth` | `Widget?` | - | Widget quando connesso tramite Bluetooth |
| `onOther` | `Widget?` | - | Widget per altre connessioni |
| `onNone` | `Widget?` | - | Widget quando offline |
| `child` | `Widget?` | - | Widget predefinito |
| `showLoadingOnInit` | `bool` | `false` | Mostra caricamento durante la verifica |
| `loadingWidget` | `Widget?` | - | Widget di caricamento personalizzato |
| `onConnectivityChanged` | `Function?` | - | Callback al cambiamento |

### OfflineBanner

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Testo del banner |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Sfondo del banner |
| `textColor` | `Color?` | `Colors.white` | Colore del testo |
| `icon` | `IconData?` | `Icons.wifi_off` | Icona del banner |
| `height` | `double` | `40` | Altezza del banner |
| `animate` | `bool` | `true` | Abilita animazione di scorrimento |
| `animationDuration` | `Duration` | `300ms` | Durata dell'animazione |

### Enum NyConnectivityState

| Valore | Descrizione |
|-------|-------------|
| `wifi` | Connesso tramite WiFi |
| `mobile` | Connesso tramite dati mobili |
| `ethernet` | Connesso tramite Ethernet |
| `vpn` | Connesso tramite VPN |
| `bluetooth` | Connesso tramite Bluetooth |
| `other` | Altro tipo di connessione |
| `none` | Nessuna connessione |
