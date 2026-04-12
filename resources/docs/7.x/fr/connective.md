# Connective

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Widget Connective](#connective-widget "Widget Connective")
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

Utilisez `noInternet` pour afficher un widget de secours lorsque l'appareil n'a pas d'internet (wifi, mobile et ethernet tous absents) :

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

    // Afficher le type de connexion
    return Text('Connected via: ${state.name}');
  },
)
```

Le constructeur recoit :
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Affichez une banniere en haut de l'ecran lorsqu'il n'y a pas d'internet (wifi, mobile et ethernet tous absents) :

``` dart
Scaffold(
  body: Stack(
    children: [
      // Votre contenu principal
      MyPageContent(),

      // Banniere hors ligne (se masque automatiquement en ligne)
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
  animate: true, // Animation de glissement entree/sortie
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

La classe `NyConnectivity` fournit des methodes statiques pour verifier la connectivite :

### Verifier si en ligne/hors ligne

``` dart
if (await NyConnectivity.isOnline()) {
  // Effectuer une requete API
  final data = await api.fetchData();
} else {
  // Charger depuis le cache
  final data = await cache.getData();
}

// Ou verifier si hors ligne
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Verifier des types de connexion specifiques

``` dart
if (await NyConnectivity.isWifi()) {
  // Telecharger des fichiers volumineux sur WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Avertir de la consommation de donnees
  showDataWarning();
}

// Autres methodes :
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Verifier l'acces internet

`hasInternet()` est plus strict que `isOnline()` — il ne retourne `true` que lorsque l'appareil est connecte via wifi, mobile ou ethernet. Les connexions VPN, bluetooth et satellite sont exclues.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Acces internet confirme via wifi, mobile ou ethernet
  await syncData();
}
```

### Obtenir l'etat actuel

``` dart
// Obtenir tous les types de connexion actifs
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Obtenir une chaine lisible
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

// N'oubliez pas d'annuler lorsque vous avez termine
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Execution conditionnelle

``` dart
// Executer uniquement en ligne (retourne null si hors ligne)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Executer differents callbacks selon l'etat
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
// Afficher un widget different lorsque hors ligne
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Afficher uniquement en ligne

``` dart
// Masquer completement lorsque hors ligne
SyncButton().onlyOnline()
```

### Afficher uniquement hors ligne

``` dart
// Afficher uniquement lorsque hors ligne
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametres

### Connective

| Parametre | Type | Par defaut | Description |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Widget lorsque wifi, mobile et ethernet sont tous absents |
| `child` | `Widget?` | - | Widget lorsqu'internet est disponible |
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
| `satellite` | Connecte via satellite |
| `other` | Autre type de connexion |
| `none` | Aucune connexion |
