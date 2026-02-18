# Connective

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Builders Baseados em Estado](#state-builders "Builders Baseados em Estado")
    - [Builder Personalizado](#custom-builder "Builder Personalizado")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Extensões de Widget](#extensions "Extensões de Widget")
- [Parâmetros](#parameters "Parâmetros")


<div id="introduction"></div>

## Introdução

{{ config('app.name') }} fornece widgets e utilitários com reconhecimento de conectividade para ajudar você a construir apps que respondem a mudanças de rede. O widget **Connective** reconstrói automaticamente quando a conectividade muda, enquanto o helper **NyConnectivity** fornece métodos estáticos para verificar o status da conexão.

<div id="connective-widget"></div>

## Widget Connective

O widget `Connective` escuta mudanças de conectividade e reconstrói com base no estado atual da rede.

<div id="state-builders"></div>

### Builders Baseados em Estado

Forneça widgets diferentes para cada tipo de conexão:

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

#### Estados Disponíveis

| Propriedade | Descrição |
|----------|-------------|
| `onWifi` | Widget quando conectado via WiFi |
| `onMobile` | Widget quando conectado via dados móveis |
| `onEthernet` | Widget quando conectado via Ethernet |
| `onVpn` | Widget quando conectado via VPN |
| `onBluetooth` | Widget quando conectado via Bluetooth |
| `onOther` | Widget para outros tipos de conexão |
| `onNone` | Widget quando offline |
| `child` | Widget padrão se nenhum handler específico for fornecido |

<div id="custom-builder"></div>

### Builder Personalizado

Use `Connective.builder` para controle total sobre a UI:

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

O builder recebe:
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` para verificar múltiplas conexões

### Escutando Mudanças

Use `onConnectivityChanged` para reagir quando a conectividade mudar:

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

Exiba um banner no topo da tela quando estiver offline:

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

### Personalizando o Banner

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

A classe `NyConnectivity` fornece métodos estáticos para verificar a conectividade:

### Verificar se Online/Offline

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

### Verificar Tipos Específicos de Conexão

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

### Obter Status Atual

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

### Escutar Mudanças

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

### Execução Condicional

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

## Extensões de Widget

Adicione rapidamente reconhecimento de conectividade a qualquer widget:

### Mostrar Alternativa Offline

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Mostrar Apenas Quando Online

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Mostrar Apenas Quando Offline

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parâmetros

### Connective

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget quando em WiFi |
| `onMobile` | `Widget?` | - | Widget quando em dados móveis |
| `onEthernet` | `Widget?` | - | Widget quando em Ethernet |
| `onVpn` | `Widget?` | - | Widget quando em VPN |
| `onBluetooth` | `Widget?` | - | Widget quando em Bluetooth |
| `onOther` | `Widget?` | - | Widget para outras conexões |
| `onNone` | `Widget?` | - | Widget quando offline |
| `child` | `Widget?` | - | Widget padrão |
| `showLoadingOnInit` | `bool` | `false` | Mostrar carregamento durante verificação |
| `loadingWidget` | `Widget?` | - | Widget de carregamento personalizado |
| `onConnectivityChanged` | `Function?` | - | Callback ao mudar |

### OfflineBanner

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Texto do banner |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Fundo do banner |
| `textColor` | `Color?` | `Colors.white` | Cor do texto |
| `icon` | `IconData?` | `Icons.wifi_off` | Ícone do banner |
| `height` | `double` | `40` | Altura do banner |
| `animate` | `bool` | `true` | Ativar animação de deslizar |
| `animationDuration` | `Duration` | `300ms` | Duração da animação |

### Enum NyConnectivityState

| Valor | Descrição |
|-------|-------------|
| `wifi` | Conectado via WiFi |
| `mobile` | Conectado via dados móveis |
| `ethernet` | Conectado via Ethernet |
| `vpn` | Conectado via VPN |
| `bluetooth` | Conectado via Bluetooth |
| `other` | Outro tipo de conexão |
| `none` | Sem conexão |
