# Connective

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Widget Connective](#connective-widget "Widget Connective")
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

Usa `noInternet` per mostrare un widget alternativo quando il dispositivo non ha internet (wifi, mobile o ethernet tutti assenti):

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

    // Mostra il tipo di connessione
    return Text('Connected via: ${state.name}');
  },
)
```

Il builder riceve:
- `context` - BuildContext
- `state` - Enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Mostra un banner nella parte superiore dello schermo quando non c'e' internet (wifi, mobile o ethernet tutti assenti):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Il tuo contenuto principale
      MyPageContent(),

      // Banner offline (si nasconde automaticamente quando online)
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
  animate: true, // Animazione di entrata/uscita a scorrimento
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

La classe `NyConnectivity` fornisce metodi statici per verificare la connettivita':

### Verificare se Online/Offline

``` dart
if (await NyConnectivity.isOnline()) {
  // Esegui richiesta API
  final data = await api.fetchData();
} else {
  // Carica dalla cache
  final data = await cache.getData();
}

// Oppure controlla se offline
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Verificare Tipi di Connessione Specifici

``` dart
if (await NyConnectivity.isWifi()) {
  // Scarica file di grandi dimensioni su WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Avvisa sull'utilizzo dei dati
  showDataWarning();
}

// Altri metodi:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Verificare la Presenza di Internet

`hasInternet()` e' piu' restrittivo di `isOnline()` — restituisce `true` solo quando il dispositivo e' connesso tramite wifi, mobile o ethernet. Le connessioni VPN, bluetooth e satellite sono escluse.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Accesso internet confermato tramite wifi, mobile o ethernet
  await syncData();
}
```

### Ottenere lo Stato Corrente

``` dart
// Ottieni tutti i tipi di connessione attivi
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Ottieni una stringa leggibile
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", ecc.
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

// Non dimenticare di annullare quando hai finito
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Esecuzione Condizionale

``` dart
// Esegui solo quando online (restituisce null se offline)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Esegui callback diversi in base allo stato
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
// Mostra un widget diverso quando offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Mostrare Solo Quando Online

``` dart
// Nasconde completamente quando offline
SyncButton().onlyOnline()
```

### Mostrare Solo Quando Offline

``` dart
// Mostra solo quando offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametri

### Connective

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Widget mostrato quando wifi, mobile ed ethernet sono tutti assenti |
| `child` | `Widget?` | - | Widget mostrato quando internet e' disponibile |
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
| `satellite` | Connesso tramite satellite |
| `other` | Altro tipo di connessione |
| `none` | Nessuna connessione |
