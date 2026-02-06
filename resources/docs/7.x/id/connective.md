# Connective

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Builder Berbasis State](#state-builders "Builder Berbasis State")
    - [Builder Kustom](#custom-builder "Builder Kustom")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Extension Widget](#extensions "Extension Widget")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} menyediakan widget dan utilitas yang sadar konektivitas untuk membantu Anda membangun aplikasi yang merespons perubahan jaringan. Widget **Connective** secara otomatis membangun ulang saat konektivitas berubah, sementara helper **NyConnectivity** menyediakan method statis untuk memeriksa status koneksi.

<div id="connective-widget"></div>

## Widget Connective

Widget `Connective` mendengarkan perubahan konektivitas dan membangun ulang berdasarkan status jaringan saat ini.

<div id="state-builders"></div>

### Builder Berbasis State

Berikan widget berbeda untuk setiap jenis koneksi:

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

#### State yang Tersedia

| Properti | Deskripsi |
|----------|-----------|
| `onWifi` | Widget saat terhubung melalui WiFi |
| `onMobile` | Widget saat terhubung melalui data seluler |
| `onEthernet` | Widget saat terhubung melalui Ethernet |
| `onVpn` | Widget saat terhubung melalui VPN |
| `onBluetooth` | Widget saat terhubung melalui Bluetooth |
| `onOther` | Widget untuk jenis koneksi lainnya |
| `onNone` | Widget saat offline |
| `child` | Widget default jika tidak ada handler spesifik yang disediakan |

<div id="custom-builder"></div>

### Builder Kustom

Gunakan `Connective.builder` untuk kontrol penuh atas UI:

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

Builder menerima:
- `context` - BuildContext
- `state` - Enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` untuk memeriksa beberapa koneksi

### Mendengarkan Perubahan

Gunakan `onConnectivityChanged` untuk bereaksi saat konektivitas berubah:

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

Tampilkan banner di bagian atas layar saat offline:

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

### Menyesuaikan Banner

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

Kelas `NyConnectivity` menyediakan method statis untuk memeriksa konektivitas:

### Memeriksa Online/Offline

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

### Memeriksa Jenis Koneksi Tertentu

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

### Mendapatkan Status Saat Ini

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

### Mendengarkan Perubahan

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

### Eksekusi Bersyarat

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

## Extension Widget

Tambahkan kesadaran konektivitas ke widget apa pun dengan cepat:

### Tampilkan Alternatif Offline

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Hanya Tampilkan Saat Online

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Hanya Tampilkan Saat Offline

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parameter

### Connective

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `onWifi` | `Widget?` | - | Widget saat di WiFi |
| `onMobile` | `Widget?` | - | Widget saat di data seluler |
| `onEthernet` | `Widget?` | - | Widget saat di Ethernet |
| `onVpn` | `Widget?` | - | Widget saat di VPN |
| `onBluetooth` | `Widget?` | - | Widget saat di Bluetooth |
| `onOther` | `Widget?` | - | Widget untuk koneksi lainnya |
| `onNone` | `Widget?` | - | Widget saat offline |
| `child` | `Widget?` | - | Widget default |
| `showLoadingOnInit` | `bool` | `false` | Tampilkan loading saat memeriksa |
| `loadingWidget` | `Widget?` | - | Widget loading kustom |
| `onConnectivityChanged` | `Function?` | - | Callback saat berubah |

### OfflineBanner

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `message` | `String` | `'No internet connection'` | Teks banner |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Warna latar banner |
| `textColor` | `Color?` | `Colors.white` | Warna teks |
| `icon` | `IconData?` | `Icons.wifi_off` | Ikon banner |
| `height` | `double` | `40` | Tinggi banner |
| `animate` | `bool` | `true` | Aktifkan animasi slide |
| `animationDuration` | `Duration` | `300ms` | Durasi animasi |

### Enum NyConnectivityState

| Nilai | Deskripsi |
|-------|-----------|
| `wifi` | Terhubung melalui WiFi |
| `mobile` | Terhubung melalui data seluler |
| `ethernet` | Terhubung melalui Ethernet |
| `vpn` | Terhubung melalui VPN |
| `bluetooth` | Terhubung melalui Bluetooth |
| `other` | Jenis koneksi lainnya |
| `none` | Tidak ada koneksi |
