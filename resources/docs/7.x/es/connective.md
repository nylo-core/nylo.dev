# Connective

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Constructores basados en estado](#state-builders "Constructores basados en estado")
    - [Constructor personalizado](#custom-builder "Constructor personalizado")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Extensiones de widgets](#extensions "Extensiones de widgets")
- [Parametros](#parameters "Parametros")


<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} proporciona widgets y utilidades conscientes de la conectividad para ayudarte a construir aplicaciones que respondan a cambios de red. El widget **Connective** se reconstruye automaticamente cuando cambia la conectividad, mientras que el helper **NyConnectivity** proporciona metodos estaticos para verificar el estado de la conexion.

<div id="connective-widget"></div>

## Widget Connective

El widget `Connective` escucha los cambios de conectividad y se reconstruye basandose en el estado actual de la red.

<div id="state-builders"></div>

### Constructores basados en estado

Proporciona diferentes widgets para cada tipo de conexion:

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

#### Estados disponibles

| Propiedad | Descripcion |
|----------|-------------|
| `onWifi` | Widget cuando esta conectado via WiFi |
| `onMobile` | Widget cuando esta conectado via datos moviles |
| `onEthernet` | Widget cuando esta conectado via Ethernet |
| `onVpn` | Widget cuando esta conectado via VPN |
| `onBluetooth` | Widget cuando esta conectado via Bluetooth |
| `onOther` | Widget para otros tipos de conexion |
| `onNone` | Widget cuando esta sin conexion |
| `child` | Widget predeterminado si no se proporciona un manejador especifico |

<div id="custom-builder"></div>

### Constructor personalizado

Usa `Connective.builder` para control total sobre la interfaz:

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

El constructor recibe:
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` para verificar multiples conexiones

### Escuchar cambios

Usa `onConnectivityChanged` para reaccionar cuando cambia la conectividad:

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

Muestra un banner en la parte superior de la pantalla cuando estas sin conexion:

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

### Personalizar el banner

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

La clase `NyConnectivity` proporciona metodos estaticos para verificar la conectividad:

### Verificar si esta en linea/fuera de linea

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

### Verificar tipos de conexion especificos

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

### Obtener estado actual

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

### Escuchar cambios

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

### Ejecucion condicional

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

## Extensiones de widgets

Agrega rapidamente consciencia de conectividad a cualquier widget:

### Mostrar alternativa sin conexion

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Mostrar solo cuando esta en linea

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Mostrar solo cuando esta sin conexion

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametros

### Connective

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget cuando esta en WiFi |
| `onMobile` | `Widget?` | - | Widget cuando esta en datos moviles |
| `onEthernet` | `Widget?` | - | Widget cuando esta en Ethernet |
| `onVpn` | `Widget?` | - | Widget cuando esta en VPN |
| `onBluetooth` | `Widget?` | - | Widget cuando esta en Bluetooth |
| `onOther` | `Widget?` | - | Widget para otras conexiones |
| `onNone` | `Widget?` | - | Widget cuando esta sin conexion |
| `child` | `Widget?` | - | Widget predeterminado |
| `showLoadingOnInit` | `bool` | `false` | Mostrar carga mientras verifica |
| `loadingWidget` | `Widget?` | - | Widget de carga personalizado |
| `onConnectivityChanged` | `Function?` | - | Callback al cambiar |

### OfflineBanner

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Texto del banner |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Fondo del banner |
| `textColor` | `Color?` | `Colors.white` | Color del texto |
| `icon` | `IconData?` | `Icons.wifi_off` | Icono del banner |
| `height` | `double` | `40` | Altura del banner |
| `animate` | `bool` | `true` | Habilitar animacion de deslizamiento |
| `animationDuration` | `Duration` | `300ms` | Duracion de la animacion |

### Enum NyConnectivityState

| Valor | Descripcion |
|-------|-------------|
| `wifi` | Conectado via WiFi |
| `mobile` | Conectado via datos moviles |
| `ethernet` | Conectado via Ethernet |
| `vpn` | Conectado via VPN |
| `bluetooth` | Conectado via Bluetooth |
| `other` | Otro tipo de conexion |
| `none` | Sin conexion |
