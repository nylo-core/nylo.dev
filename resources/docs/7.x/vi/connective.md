# Connective

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Widget Connective](#connective-widget "Widget Connective")
    - [Builder theo trạng thái](#state-builders "Builder theo trạng thái")
    - [Builder tùy chỉnh](#custom-builder "Builder tùy chỉnh")
- [Widget OfflineBanner](#offline-banner "Widget OfflineBanner")
- [Helper NyConnectivity](#connectivity-helper "Helper NyConnectivity")
- [Extension Widget](#extensions "Extension Widget")
- [Tham số](#parameters "Tham số")


<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} cung cấp các widget và tiện ích nhận biết kết nối mạng để giúp bạn xây dựng ứng dụng phản hồi thay đổi mạng. Widget **Connective** tự động rebuild khi kết nối thay đổi, trong khi helper **NyConnectivity** cung cấp các phương thức static để kiểm tra trạng thái kết nối.

<div id="connective-widget"></div>

## Widget Connective

Widget `Connective` lắng nghe thay đổi kết nối và rebuild dựa trên trạng thái mạng hiện tại.

<div id="state-builders"></div>

### Builder theo trạng thái

Cung cấp các widget khác nhau cho mỗi loại kết nối:

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

#### Các trạng thái có sẵn

| Thuộc tính | Mô tả |
|----------|-------------|
| `onWifi` | Widget khi kết nối qua WiFi |
| `onMobile` | Widget khi kết nối qua dữ liệu di động |
| `onEthernet` | Widget khi kết nối qua Ethernet |
| `onVpn` | Widget khi kết nối qua VPN |
| `onBluetooth` | Widget khi kết nối qua Bluetooth |
| `onOther` | Widget cho các loại kết nối khác |
| `onNone` | Widget khi ngoại tuyến |
| `child` | Widget mặc định nếu không có handler cụ thể |

<div id="custom-builder"></div>

### Builder tùy chỉnh

Sử dụng `Connective.builder` để kiểm soát hoàn toàn UI:

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

Builder nhận:
- `context` - BuildContext
- `state` - Enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` để kiểm tra nhiều kết nối

### Lắng nghe thay đổi

Sử dụng `onConnectivityChanged` để phản ứng khi kết nối thay đổi:

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

Hiển thị banner ở đầu màn hình khi ngoại tuyến:

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

### Tùy chỉnh banner

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

Class `NyConnectivity` cung cấp các phương thức static để kiểm tra kết nối:

### Kiểm tra trực tuyến/ngoại tuyến

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

### Kiểm tra loại kết nối cụ thể

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

### Lấy trạng thái hiện tại

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

### Lắng nghe thay đổi

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

### Thực thi có điều kiện

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

Nhanh chóng thêm nhận biết kết nối cho bất kỳ widget nào:

### Hiển thị giao diện thay thế khi ngoại tuyến

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Chỉ hiển thị khi trực tuyến

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Chỉ hiển thị khi ngoại tuyến

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Tham số

### Connective

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget khi kết nối WiFi |
| `onMobile` | `Widget?` | - | Widget khi kết nối dữ liệu di động |
| `onEthernet` | `Widget?` | - | Widget khi kết nối Ethernet |
| `onVpn` | `Widget?` | - | Widget khi kết nối VPN |
| `onBluetooth` | `Widget?` | - | Widget khi kết nối Bluetooth |
| `onOther` | `Widget?` | - | Widget cho kết nối khác |
| `onNone` | `Widget?` | - | Widget khi ngoại tuyến |
| `child` | `Widget?` | - | Widget mặc định |
| `showLoadingOnInit` | `bool` | `false` | Hiển thị tải khi đang kiểm tra |
| `loadingWidget` | `Widget?` | - | Widget tải tùy chỉnh |
| `onConnectivityChanged` | `Function?` | - | Callback khi thay đổi |

### OfflineBanner

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Văn bản banner |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Nền banner |
| `textColor` | `Color?` | `Colors.white` | Màu chữ |
| `icon` | `IconData?` | `Icons.wifi_off` | Icon banner |
| `height` | `double` | `40` | Chiều cao banner |
| `animate` | `bool` | `true` | Bật hoạt ảnh trượt |
| `animationDuration` | `Duration` | `300ms` | Thời gian hoạt ảnh |

### Enum NyConnectivityState

| Giá trị | Mô tả |
|-------|-------------|
| `wifi` | Kết nối qua WiFi |
| `mobile` | Kết nối qua dữ liệu di động |
| `ethernet` | Kết nối qua Ethernet |
| `vpn` | Kết nối qua VPN |
| `bluetooth` | Kết nối qua Bluetooth |
| `other` | Loại kết nối khác |
| `none` | Không có kết nối |
