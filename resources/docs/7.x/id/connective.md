# Connective

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Widget Connective](#connective-widget "Widget Connective")
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

Gunakan `noInternet` untuk menampilkan widget fallback saat perangkat tidak memiliki internet (wifi, mobile, atau ethernet semua tidak ada):

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

    // Tampilkan jenis koneksi
    return Text('Connected via: ${state.name}');
  },
)
```

Builder menerima:
- `context` - BuildContext
- `state` - Enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Tampilkan banner di bagian atas layar saat tidak ada internet (wifi, mobile, atau ethernet semua tidak ada):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Konten utama Anda
      MyPageContent(),

      // Banner offline (disembunyikan otomatis saat online)
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
  animate: true, // Animasi geser masuk/keluar
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

Kelas `NyConnectivity` menyediakan method statis untuk memeriksa konektivitas:

### Memeriksa Online/Offline

``` dart
if (await NyConnectivity.isOnline()) {
  // Buat permintaan API
  final data = await api.fetchData();
} else {
  // Muat dari cache
  final data = await cache.getData();
}

// Atau periksa jika offline
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Memeriksa Jenis Koneksi Tertentu

``` dart
if (await NyConnectivity.isWifi()) {
  // Unduh file besar melalui WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Peringatkan tentang penggunaan data
  showDataWarning();
}

// Method lainnya:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Memeriksa Internet

`hasInternet()` lebih ketat daripada `isOnline()` — hanya mengembalikan `true` saat perangkat terhubung melalui wifi, mobile, atau ethernet. Koneksi VPN, bluetooth, dan satellite dikecualikan.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Akses internet terkonfirmasi melalui wifi, mobile, atau ethernet
  await syncData();
}
```

### Mendapatkan Status Saat Ini

``` dart
// Dapatkan semua jenis koneksi yang aktif
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Dapatkan string yang mudah dibaca
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", dll.
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

// Jangan lupa batalkan saat selesai
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Eksekusi Bersyarat

``` dart
// Jalankan hanya saat online (mengembalikan null jika offline)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Jalankan callback berbeda berdasarkan status
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
// Tampilkan widget berbeda saat offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Hanya Tampilkan Saat Online

``` dart
// Sembunyikan sepenuhnya saat offline
SyncButton().onlyOnline()
```

### Hanya Tampilkan Saat Offline

``` dart
// Tampilkan hanya saat offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parameter

### Connective

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `noInternet` | `Widget?` | - | Widget yang ditampilkan saat wifi, mobile, dan ethernet semua tidak ada |
| `child` | `Widget?` | - | Widget yang ditampilkan saat internet tersedia |
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
| `satellite` | Terhubung melalui satelit |
| `other` | Jenis koneksi lainnya |
| `none` | Tidak ada koneksi |
