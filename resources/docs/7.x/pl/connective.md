# Connective

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Niestandardowy builder](#custom-builder "Niestandardowy builder")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Rozszerzenia widgetów](#extensions "Rozszerzenia widgetów")
- [Parametry](#parameters "Parametry")


<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} udostępnia widgety i narzędzia reagujące na łączność sieciową, które pomagają budować aplikacje reagujące na zmiany sieci. Widget **Connective** automatycznie przebudowuje się przy zmianach łączności, a helper **NyConnectivity** udostępnia statyczne metody do sprawdzania stanu połączenia.

<div id="connective-widget"></div>

## Widget Connective

Widget `Connective` nasłuchuje zmian łączności i przebudowuje się na podstawie bieżącego stanu sieci.

Użyj `noInternet`, aby wyświetlić zastępczy widget, gdy urządzenie nie ma internetu (wifi, mobile ani ethernet są niedostępne):

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

### Niestandardowy builder

Użyj `Connective.builder` do pełnej kontroli nad interfejsem:

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

    // Pokaż typ połączenia
    return Text('Connected via: ${state.name}');
  },
)
```

Builder otrzymuje:
- `context` - BuildContext
- `state` - enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
- `results` - `List<ConnectivityResult>` do sprawdzania wielu połączeń

### Nasłuchiwanie zmian

Użyj `onConnectivityChanged`, aby reagować na zmiany łączności:

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

Wyświetl baner na górze ekranu, gdy nie ma internetu (wifi, mobile ani ethernet są niedostępne):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Twoja główna zawartość
      MyPageContent(),

      // Baner offline (automatycznie ukrywa się online)
      OfflineBanner(),
    ],
  ),
)
```

### Dostosowywanie banera

``` dart
OfflineBanner(
  message: 'Check your connection',
  backgroundColor: Colors.orange,
  textColor: Colors.white,
  icon: Icons.signal_wifi_off,
  height: 50,
  animate: true, // Animacja wysuwania/chowania
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

Klasa `NyConnectivity` udostępnia statyczne metody do sprawdzania łączności:

### Sprawdzanie online/offline

``` dart
if (await NyConnectivity.isOnline()) {
  // Wykonaj żądanie API
  final data = await api.fetchData();
} else {
  // Załaduj z pamięci podręcznej
  final data = await cache.getData();
}

// Lub sprawdź, czy offline
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Sprawdzanie konkretnych typów połączeń

``` dart
if (await NyConnectivity.isWifi()) {
  // Pobierz duże pliki przez WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Ostrzeż o zużyciu danych
  showDataWarning();
}

// Inne metody:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Sprawdzanie internetu

`hasInternet()` jest bardziej restrykcyjne niż `isOnline()` — zwraca `true` tylko wtedy, gdy urządzenie jest połączone przez wifi, mobile lub ethernet. Połączenia VPN, bluetooth i satelitarne są wykluczone.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Potwierdzony dostęp do internetu przez wifi, mobile lub ethernet
  await syncData();
}
```

### Pobieranie bieżącego statusu

``` dart
// Pobierz wszystkie aktywne typy połączeń
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Pobierz czytelny dla człowieka string
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", itp.
```

### Nasłuchiwanie zmian

``` dart
StreamSubscription subscription = NyConnectivity.stream().listen((results) {
  if (results.contains(ConnectivityResult.none)) {
    showOfflineUI();
  } else {
    showOnlineUI();
  }
});

// Nie zapomnij anulować po zakończeniu
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Warunkowe wykonywanie

``` dart
// Wykonaj tylko online (zwraca null, gdy offline)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Wykonaj różne callbacki w zależności od statusu
final result = await NyConnectivity.when(
  online: () async => await api.fetchData(),
  offline: () async => await cache.getData(),
);
```

<div id="extensions"></div>

## Rozszerzenia widgetów

Szybko dodaj świadomość łączności do dowolnego widgetu:

### Pokaż alternatywę offline

``` dart
// Pokaż inny widget gdy offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Pokaż tylko online

``` dart
// Ukryj całkowicie gdy offline
SyncButton().onlyOnline()
```

### Pokaż tylko offline

``` dart
// Pokaż tylko gdy offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametry

### Connective

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `noInternet` | `Widget?` | - | Widget wyświetlany gdy wifi, mobile i ethernet są niedostępne |
| `child` | `Widget?` | - | Widget wyświetlany gdy internet jest dostępny |
| `onConnectivityChanged` | `Function?` | - | Callback przy zmianie |

### OfflineBanner

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `message` | `String` | `'No internet connection'` | Tekst banera |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Tło banera |
| `textColor` | `Color?` | `Colors.white` | Kolor tekstu |
| `icon` | `IconData?` | `Icons.wifi_off` | Ikona banera |
| `height` | `double` | `40` | Wysokość banera |
| `animate` | `bool` | `true` | Włącz animację wysuwania |
| `animationDuration` | `Duration` | `300ms` | Czas trwania animacji |

### Enum NyConnectivityState

| Wartość | Opis |
|---------|------|
| `wifi` | Połączony przez WiFi |
| `mobile` | Połączony przez dane mobilne |
| `ethernet` | Połączony przez Ethernet |
| `vpn` | Połączony przez VPN |
| `bluetooth` | Połączony przez Bluetooth |
| `satellite` | Połączony przez satelitę |
| `other` | Inny typ połączenia |
| `none` | Brak połączenia |
