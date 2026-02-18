# Connective

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [Connective Widget](#connective-widget "Connective Widget")
    - [Builder ตามสถานะ](#state-builders "Builder ตามสถานะ")
    - [Builder แบบกำหนดเอง](#custom-builder "Builder แบบกำหนดเอง")
- [OfflineBanner Widget](#offline-banner "OfflineBanner Widget")
- [ตัวช่วย NyConnectivity](#connectivity-helper "ตัวช่วย NyConnectivity")
- [Widget Extensions](#extensions "Widget Extensions")
- [พารามิเตอร์](#parameters "พารามิเตอร์")


<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มี widget และยูทิลิตี้สำหรับตรวจจับการเชื่อมต่อเพื่อช่วยให้คุณสร้างแอปที่ตอบสนองต่อการเปลี่ยนแปลงของเครือข่าย widget **Connective** จะ rebuild อัตโนมัติเมื่อการเชื่อมต่อเปลี่ยนแปลง ในขณะที่ตัวช่วย **NyConnectivity** มีเมธอดแบบ static สำหรับตรวจสอบสถานะการเชื่อมต่อ

<div id="connective-widget"></div>

## Connective Widget

widget `Connective` จะ listen การเปลี่ยนแปลงการเชื่อมต่อและ rebuild ตามสถานะเครือข่ายปัจจุบัน

<div id="state-builders"></div>

### Builder ตามสถานะ

ให้ widget ที่แตกต่างกันสำหรับแต่ละประเภทการเชื่อมต่อ:

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
  child: Text('Connected'), // ค่าเริ่มต้นสำหรับสถานะที่ไม่ได้ระบุ
)
```

#### สถานะที่ใช้ได้

| คุณสมบัติ | คำอธิบาย |
|----------|-------------|
| `onWifi` | Widget เมื่อเชื่อมต่อผ่าน WiFi |
| `onMobile` | Widget เมื่อเชื่อมต่อผ่านข้อมูลมือถือ |
| `onEthernet` | Widget เมื่อเชื่อมต่อผ่าน Ethernet |
| `onVpn` | Widget เมื่อเชื่อมต่อผ่าน VPN |
| `onBluetooth` | Widget เมื่อเชื่อมต่อผ่าน Bluetooth |
| `onOther` | Widget สำหรับการเชื่อมต่อประเภทอื่น |
| `onNone` | Widget เมื่อออฟไลน์ |
| `child` | Widget เริ่มต้นหากไม่มี handler เฉพาะ |

<div id="custom-builder"></div>

### Builder แบบกำหนดเอง

ใช้ `Connective.builder` เพื่อควบคุม UI อย่างเต็มที่:

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

builder จะรับ:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` - `List<ConnectivityResult>` สำหรับตรวจสอบการเชื่อมต่อหลายรายการ

### การ Listen การเปลี่ยนแปลง

ใช้ `onConnectivityChanged` เพื่อตอบสนองเมื่อการเชื่อมต่อเปลี่ยนแปลง:

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

แสดงแบนเนอร์ที่ด้านบนของหน้าจอเมื่อออฟไลน์:

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

### การปรับแต่งแบนเนอร์

``` dart
OfflineBanner(
  message: 'Check your connection',
  backgroundColor: Colors.orange,
  textColor: Colors.white,
  icon: Icons.signal_wifi_off,
  height: 50,
  animate: true, // แอนิเมชันเลื่อนเข้า/ออก
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## ตัวช่วย NyConnectivity

คลาส `NyConnectivity` มีเมธอดแบบ static สำหรับตรวจสอบการเชื่อมต่อ:

### ตรวจสอบออนไลน์/ออฟไลน์

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

### ตรวจสอบประเภทการเชื่อมต่อเฉพาะ

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

### รับสถานะปัจจุบัน

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

### Listen การเปลี่ยนแปลง

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

### การดำเนินการแบบมีเงื่อนไข

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

## Widget Extensions

เพิ่มความสามารถในการตรวจจับการเชื่อมต่อให้กับ widget ใดก็ได้อย่างรวดเร็ว:

### แสดงทางเลือกเมื่อออฟไลน์

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### แสดงเฉพาะเมื่อออนไลน์

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### แสดงเฉพาะเมื่อออฟไลน์

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## พารามิเตอร์

### Connective

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Widget เมื่อเชื่อมต่อ WiFi |
| `onMobile` | `Widget?` | - | Widget เมื่อเชื่อมต่อข้อมูลมือถือ |
| `onEthernet` | `Widget?` | - | Widget เมื่อเชื่อมต่อ Ethernet |
| `onVpn` | `Widget?` | - | Widget เมื่อเชื่อมต่อ VPN |
| `onBluetooth` | `Widget?` | - | Widget เมื่อเชื่อมต่อ Bluetooth |
| `onOther` | `Widget?` | - | Widget สำหรับการเชื่อมต่ออื่น |
| `onNone` | `Widget?` | - | Widget เมื่อออฟไลน์ |
| `child` | `Widget?` | - | Widget เริ่มต้น |
| `showLoadingOnInit` | `bool` | `false` | แสดง loading ขณะตรวจสอบ |
| `loadingWidget` | `Widget?` | - | Widget loading แบบกำหนดเอง |
| `onConnectivityChanged` | `Function?` | - | Callback เมื่อเปลี่ยนแปลง |

### OfflineBanner

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | ข้อความบนแบนเนอร์ |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | สีพื้นหลังแบนเนอร์ |
| `textColor` | `Color?` | `Colors.white` | สีข้อความ |
| `icon` | `IconData?` | `Icons.wifi_off` | ไอคอนบนแบนเนอร์ |
| `height` | `double` | `40` | ความสูงแบนเนอร์ |
| `animate` | `bool` | `true` | เปิดใช้แอนิเมชันเลื่อน |
| `animationDuration` | `Duration` | `300ms` | ระยะเวลาแอนิเมชัน |

### NyConnectivityState Enum

| ค่า | คำอธิบาย |
|-------|-------------|
| `wifi` | เชื่อมต่อผ่าน WiFi |
| `mobile` | เชื่อมต่อผ่านข้อมูลมือถือ |
| `ethernet` | เชื่อมต่อผ่าน Ethernet |
| `vpn` | เชื่อมต่อผ่าน VPN |
| `bluetooth` | เชื่อมต่อผ่าน Bluetooth |
| `other` | การเชื่อมต่อประเภทอื่น |
| `none` | ไม่มีการเชื่อมต่อ |
