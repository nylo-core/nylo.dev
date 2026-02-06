# Connective

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Constructeurs bases sur l'etat](#state-builders "Constructeurs bases sur l'etat")
    - [Constructeur personnalise](#custom-builder "Constructeur personnalise")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Extensions de widgets](#extensions "Extensions de widgets")
- [Parametres](#parameters "Parametres")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} fournit des widgets et des utilitaires sensibles a la connectivite pour vous aider a construire des applications qui reagissent aux changements de reseau. Le widget **Connective** se reconstruit automatiquement lorsque la connectivite change, tandis que le helper **NyConnectivity** fournit des methodes statiques pour verifier l'etat de la connexion.

<div id="connective-widget"></div>

## Widget Connective

Le widget `Connective` ecoute les changements de connectivite et se reconstruit en fonction de l'etat actuel du reseau.

<div id="state-builders"></div>

### Constructeurs bases sur l'etat

Fournissez differents widgets pour chaque type de connexion :

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

#### Etats disponibles

| Propriete | Description |
|----------|-------------|
| `onWifi` | Widget lorsque connecte via WiFi |
| `onMobile` | Widget lorsque connecte via donnees mobiles |
| `onEthernet` | Widget lorsque connecte via Ethernet |
| `onVpn` | Widget lorsque connecte via VPN |
| `onBluetooth` | Widget lorsque connecte via Bluetooth |
| `onOther` | Widget pour les autres types de connexion |
| `onNone` | Widget lorsque hors ligne |
| `child` | Widget par defaut si aucun gestionnaire specifique n'est fourni |

<div id="custom-builder"></div>

### Constructeur personnalise

Utilisez `Connective.builder` pour un controle total sur l'interface :

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

Le constructeur recoit :
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` pour verifier plusieurs connexions

### Ecouter les changements

Utilisez `onConnectivityChanged` pour reagir lorsque la connectivite change :

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

Affichez une banniere en haut de l'ecran lorsque hors ligne :

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

### Personnaliser la banniere

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

La classe `NyConnectivity` fournit des methodes statiques pour verifier la connectivite :

### Verifier si en ligne/hors ligne

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

### Verifier des types de connexion specifiques

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

### Obtenir l'etat actuel

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

### Ecouter les changements

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

### Execution conditionnelle

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

## Extensions de widgets

Ajoutez rapidement la sensibilite a la connectivite a n'importe quel widget :

### Afficher une alternative hors ligne

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Afficher uniquement en ligne

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Afficher uniquement hors ligne

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametres

### Connective

| Parametre | Type | Par defaut | Description |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget lorsque sur WiFi |
| `onMobile` | `Widget?` | - | Widget lorsque sur donnees mobiles |
| `onEthernet` | `Widget?` | - | Widget lorsque sur Ethernet |
| `onVpn` | `Widget?` | - | Widget lorsque sur VPN |
| `onBluetooth` | `Widget?` | - | Widget lorsque sur Bluetooth |
| `onOther` | `Widget?` | - | Widget pour d'autres connexions |
| `onNone` | `Widget?` | - | Widget lorsque hors ligne |
| `child` | `Widget?` | - | Widget par defaut |
| `showLoadingOnInit` | `bool` | `false` | Afficher le chargement pendant la verification |
| `loadingWidget` | `Widget?` | - | Widget de chargement personnalise |
| `onConnectivityChanged` | `Function?` | - | Callback lors du changement |

### OfflineBanner

| Parametre | Type | Par defaut | Description |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Texte de la banniere |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Arriere-plan de la banniere |
| `textColor` | `Color?` | `Colors.white` | Couleur du texte |
| `icon` | `IconData?` | `Icons.wifi_off` | Icone de la banniere |
| `height` | `double` | `40` | Hauteur de la banniere |
| `animate` | `bool` | `true` | Activer l'animation de glissement |
| `animationDuration` | `Duration` | `300ms` | Duree de l'animation |

### Enum NyConnectivityState

| Valeur | Description |
|-------|-------------|
| `wifi` | Connecte via WiFi |
| `mobile` | Connecte via donnees mobiles |
| `ethernet` | Connecte via Ethernet |
| `vpn` | Connecte via VPN |
| `bluetooth` | Connecte via Bluetooth |
| `other` | Autre type de connexion |
| `none` | Aucune connexion |
