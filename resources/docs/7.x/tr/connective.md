# Connective

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Connective Widget](#connective-widget "Connective Widget")
    - [Özel Oluşturucu](#custom-builder "Özel Oluşturucu")
- [OfflineBanner Widget](#offline-banner "OfflineBanner Widget")
- [NyConnectivity Yardımcısı](#connectivity-helper "NyConnectivity Yardımcısı")
- [Widget Uzantıları](#extensions "Widget Uzantıları")
- [Parametreler](#parameters "Parametreler")


<div id="introduction"></div>

## Giriş

{{ config('app.name') }}, ağ değişikliklerine yanıt veren uygulamalar oluşturmanıza yardımcı olmak için bağlantı farkındalığına sahip widget'lar ve yardımcı araçlar sağlar. **Connective** widget'ı, bağlantı değiştiğinde otomatik olarak yeniden oluşturulurken, **NyConnectivity** yardımcısı bağlantı durumunu kontrol etmek için statik metotlar sağlar.

<div id="connective-widget"></div>

## Connective Widget

`Connective` widget'ı, bağlantı değişikliklerini dinler ve mevcut ağ durumuna göre yeniden oluşturulur.

Cihazın interneti olmadığında (wifi, mobile veya ethernet hiçbiri mevcut değil) bir yedek widget göstermek için `noInternet` kullanın:

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

### Özel Oluşturucu

Arayüz üzerinde tam kontrol için `Connective.builder` kullanın:

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

    // Bağlantı türünü göster
    return Text('Connected via: ${state.name}');
  },
)
```

Oluşturucu şunları alır:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
- `results` - Birden fazla bağlantıyı kontrol etmek için `List<ConnectivityResult>`

### Değişiklikleri Dinleme

Bağlantı değiştiğinde tepki vermek için `onConnectivityChanged` kullanın:

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

## OfflineBanner Widget

İnternet olmadığında (wifi, mobile veya ethernet hiçbiri mevcut değil) ekranın üstünde bir banner görüntüleyin:

``` dart
Scaffold(
  body: Stack(
    children: [
      // Ana içeriğiniz
      MyPageContent(),

      // Çevrimdışı banner (çevrimiçiyken otomatik gizlenir)
      OfflineBanner(),
    ],
  ),
)
```

### Banner'ı Özelleştirme

``` dart
OfflineBanner(
  message: 'Check your connection',
  backgroundColor: Colors.orange,
  textColor: Colors.white,
  icon: Icons.signal_wifi_off,
  height: 50,
  animate: true, // Kaydırma animasyonu
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity Yardımcısı

`NyConnectivity` sınıfı, bağlantıyı kontrol etmek için statik metotlar sağlar:

### Çevrimiçi/Çevrimdışı Kontrolü

``` dart
if (await NyConnectivity.isOnline()) {
  // API isteği yap
  final data = await api.fetchData();
} else {
  // Önbellekten yükle
  final data = await cache.getData();
}

// Veya çevrimdışı olup olmadığını kontrol et
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Belirli Bağlantı Türlerini Kontrol Etme

``` dart
if (await NyConnectivity.isWifi()) {
  // WiFi'da büyük dosyaları indir
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Veri kullanımı hakkında uyar
  showDataWarning();
}

// Diğer metotlar:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### İnternet Kontrolü

`hasInternet()`, `isOnline()`'dan daha kısıtlayıcıdır — yalnızca cihaz wifi, mobile veya ethernet üzerinden bağlı olduğunda `true` döndürür. VPN, bluetooth ve uydu bağlantıları hariç tutulur.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Wifi, mobile veya ethernet üzerinden onaylanmış internet erişimi
  await syncData();
}
```

### Mevcut Durumu Alma

``` dart
// Tüm aktif bağlantı türlerini al
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Okunabilir string al
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", vb.
```

### Değişiklikleri Dinleme

``` dart
StreamSubscription subscription = NyConnectivity.stream().listen((results) {
  if (results.contains(ConnectivityResult.none)) {
    showOfflineUI();
  } else {
    showOnlineUI();
  }
});

// Bittiğinde iptal etmeyi unutmayın
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Koşullu Yürütme

``` dart
// Yalnızca çevrimiçiyken çalıştır (çevrimdışıysa null döndürür)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Duruma göre farklı callback'ler çalıştır
final result = await NyConnectivity.when(
  online: () async => await api.fetchData(),
  offline: () async => await cache.getData(),
);
```

<div id="extensions"></div>

## Widget Uzantıları

Herhangi bir widget'a hızla bağlantı farkındalığı ekleyin:

### Çevrimdışı Alternatif Gösterme

``` dart
// Çevrimdışıyken farklı bir widget göster
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Yalnızca Çevrimiçinde Gösterme

``` dart
// Çevrimdışıyken tamamen gizle
SyncButton().onlyOnline()
```

### Yalnızca Çevrimdışında Gösterme

``` dart
// Yalnızca çevrimdışıyken göster
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Parametreler

### Connective

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Wifi, mobile ve ethernet hepsi yokken gösterilen widget |
| `child` | `Widget?` | - | İnternet mevcutken gösterilen widget |
| `onConnectivityChanged` | `Function?` | - | Değişiklikte callback |

### OfflineBanner

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Banner metni |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Banner arka planı |
| `textColor` | `Color?` | `Colors.white` | Metin rengi |
| `icon` | `IconData?` | `Icons.wifi_off` | Banner simgesi |
| `height` | `double` | `40` | Banner yüksekliği |
| `animate` | `bool` | `true` | Kaydırma animasyonu etkinleştir |
| `animationDuration` | `Duration` | `300ms` | Animasyon süresi |

### NyConnectivityState Enum

| Değer | Açıklama |
|-------|-------------|
| `wifi` | WiFi ile bağlı |
| `mobile` | Mobil veri ile bağlı |
| `ethernet` | Ethernet ile bağlı |
| `vpn` | VPN ile bağlı |
| `bluetooth` | Bluetooth ile bağlı |
| `satellite` | Uydu ile bağlı |
| `other` | Diğer bağlantı türü |
| `none` | Bağlantı yok |
