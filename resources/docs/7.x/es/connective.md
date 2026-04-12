# Connective

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Widget Connective](#connective-widget "Widget Connective")
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

Usa `noInternet` para mostrar un widget alternativo cuando el dispositivo no tiene internet (wifi, movil y ethernet todos ausentes):

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

    // Mostrar tipo de conexion
    return Text('Connected via: ${state.name}');
  },
)
```

El constructor recibe:
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Muestra un banner en la parte superior de la pantalla cuando no hay internet (wifi, movil y ethernet todos ausentes):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Tu contenido principal
      MyPageContent(),

      // Banner sin conexion (se oculta automaticamente cuando hay conexion)
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
  animate: true, // Animacion de entrada/salida con deslizamiento
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

La clase `NyConnectivity` proporciona metodos estaticos para verificar la conectividad:

### Verificar si esta en linea/fuera de linea

``` dart
if (await NyConnectivity.isOnline()) {
  // Realizar solicitud API
  final data = await api.fetchData();
} else {
  // Cargar desde cache
  final data = await cache.getData();
}

// O verificar si esta sin conexion
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Verificar tipos de conexion especificos

``` dart
if (await NyConnectivity.isWifi()) {
  // Descargar archivos grandes por WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Advertir sobre el uso de datos
  showDataWarning();
}

// Otros metodos:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Verificar acceso a internet

`hasInternet()` es mas estricto que `isOnline()` — solo retorna `true` cuando el dispositivo esta conectado via wifi, movil o ethernet. Las conexiones VPN, bluetooth y satelite estan excluidas.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Acceso a internet confirmado via wifi, movil o ethernet
  await syncData();
}
```

### Obtener estado actual

``` dart
// Obtener todos los tipos de conexion activos
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Obtener cadena legible
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

// No olvides cancelar cuando hayas terminado
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Ejecucion condicional

``` dart
// Ejecutar solo cuando esta en linea (retorna null si esta sin conexion)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Ejecutar diferentes callbacks segun el estado
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
// Mostrar un widget diferente cuando esta sin conexion
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Mostrar solo cuando esta en linea

``` dart
// Ocultar completamente cuando esta sin conexion
SyncButton().onlyOnline()
```

### Mostrar solo cuando esta sin conexion

``` dart
// Mostrar solo cuando esta sin conexion
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametros

### Connective

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Widget cuando wifi, movil y ethernet estan todos ausentes |
| `child` | `Widget?` | - | Widget cuando internet esta disponible |
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
| `satellite` | Conectado via satelite |
| `other` | Otro tipo de conexion |
| `none` | Sin conexion |
