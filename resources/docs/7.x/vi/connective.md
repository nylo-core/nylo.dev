# Connective

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Widget Connective](#connective-widget "Widget Connective")
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

Dùng `noInternet` để hiển thị widget dự phòng khi thiết bị không có internet (wifi, mobile, hay ethernet đều vắng mặt):

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

    // Hiển thị loại kết nối
    return Text('Connected via: ${state.name}');
  },
)
```

Builder nhận:
- `context` - BuildContext
- `state` - Enum `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Hiển thị banner ở đầu màn hình khi không có internet (wifi, mobile, hay ethernet đều vắng mặt):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Nội dung chính của bạn
      MyPageContent(),

      // Banner ngoại tuyến (tự ẩn khi trực tuyến)
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
  animate: true, // Hiệu ứng trượt vào/ra
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Helper NyConnectivity

Class `NyConnectivity` cung cấp các phương thức static để kiểm tra kết nối:

### Kiểm tra trực tuyến/ngoại tuyến

``` dart
if (await NyConnectivity.isOnline()) {
  // Thực hiện yêu cầu API
  final data = await api.fetchData();
} else {
  // Tải từ bộ nhớ đệm
  final data = await cache.getData();
}

// Hoặc kiểm tra ngoại tuyến
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Kiểm tra loại kết nối cụ thể

``` dart
if (await NyConnectivity.isWifi()) {
  // Tải file lớn trên WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Cảnh báo về việc sử dụng dữ liệu
  showDataWarning();
}

// Các phương thức khác:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Kiểm tra internet

`hasInternet()` nghiêm ngặt hơn `isOnline()` — nó chỉ trả về `true` khi thiết bị kết nối qua wifi, mobile, hoặc ethernet. Kết nối VPN, bluetooth, và satellite bị loại trừ.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Đã xác nhận truy cập internet qua wifi, mobile, hoặc ethernet
  await syncData();
}
```

### Lấy trạng thái hiện tại

``` dart
// Lấy tất cả loại kết nối đang hoạt động
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Lấy chuỗi dễ đọc
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", v.v.
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

// Đừng quên hủy khi hoàn thành
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Thực thi có điều kiện

``` dart
// Chỉ thực thi khi trực tuyến (trả về null nếu ngoại tuyến)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Thực thi callback khác nhau dựa trên trạng thái
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
// Hiển thị widget khác khi ngoại tuyến
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Chỉ hiển thị khi trực tuyến

``` dart
// Ẩn hoàn toàn khi ngoại tuyến
SyncButton().onlyOnline()
```

### Chỉ hiển thị khi ngoại tuyến

``` dart
// Chỉ hiển thị khi ngoại tuyến
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Tham số

### Connective

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Widget hiển thị khi wifi, mobile, và ethernet đều vắng mặt |
| `child` | `Widget?` | - | Widget hiển thị khi có internet |
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
| `satellite` | Kết nối qua vệ tinh |
| `other` | Loại kết nối khác |
| `none` | Không có kết nối |
