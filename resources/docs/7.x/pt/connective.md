# Connective

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Widget Connective](#connective-widget "Widget Connective")
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

Use `noInternet` para exibir um widget alternativo quando o dispositivo não tem internet (wifi, mobile ou ethernet todos ausentes):

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

    // Exibir tipo de conexão
    return Text('Connected via: ${state.name}');
  },
)
```

O builder recebe:
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Exiba um banner no topo da tela quando não houver internet (wifi, mobile ou ethernet todos ausentes):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Seu conteúdo principal
      MyPageContent(),

      // Banner offline (se esconde automaticamente quando online)
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
  animate: true, // Animação de deslizar para entrar/sair
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

A classe `NyConnectivity` fornece métodos estáticos para verificar a conectividade:

### Verificar se Online/Offline

``` dart
if (await NyConnectivity.isOnline()) {
  // Fazer requisição de API
  final data = await api.fetchData();
} else {
  // Carregar do cache
  final data = await cache.getData();
}

// Ou verificar se offline
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Verificar Tipos Específicos de Conexão

``` dart
if (await NyConnectivity.isWifi()) {
  // Baixar arquivos grandes no WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Avisar sobre uso de dados
  showDataWarning();
}

// Outros métodos:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Verificar Presença de Internet

`hasInternet()` é mais restritivo que `isOnline()` — retorna `true` apenas quando o dispositivo está conectado via wifi, mobile ou ethernet. Conexões via VPN, bluetooth e satélite são excluídas.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Acesso à internet confirmado via wifi, mobile ou ethernet
  await syncData();
}
```

### Obter Status Atual

``` dart
// Obter todos os tipos de conexão ativos
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Obter string legível por humanos
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

// Não esqueça de cancelar quando terminar
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Execução Condicional

``` dart
// Executar apenas quando online (retorna null se offline)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Executar callbacks diferentes com base no status
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
// Mostrar um widget diferente quando offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Mostrar Apenas Quando Online

``` dart
// Ocultar completamente quando offline
SyncButton().onlyOnline()
```

### Mostrar Apenas Quando Offline

``` dart
// Mostrar apenas quando offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parâmetros

### Connective

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Widget exibido quando wifi, mobile e ethernet estão todos ausentes |
| `child` | `Widget?` | - | Widget exibido quando a internet está disponível |
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
| `satellite` | Conectado via satélite |
| `other` | Outro tipo de conexão |
| `none` | Sem conexão |
