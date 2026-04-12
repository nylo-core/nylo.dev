# Connective

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [Connective Widget](#connective-widget "Connective Widget")
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

ใช้ `noInternet` เพื่อแสดง widget สำรองเมื่ออุปกรณ์ไม่มีอินเทอร์เน็ต (ไม่มี wifi, mobile, หรือ ethernet เลย):

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

    // แสดงประเภทการเชื่อมต่อ
    return Text('Connected via: ${state.name}');
  },
)
```

builder จะรับ:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

แสดงแบนเนอร์ที่ด้านบนของหน้าจอเมื่อไม่มีอินเทอร์เน็ต (ไม่มี wifi, mobile, หรือ ethernet เลย):

``` dart
Scaffold(
  body: Stack(
    children: [
      // เนื้อหาหลักของคุณ
      MyPageContent(),

      // แบนเนอร์ออฟไลน์ (ซ่อนอัตโนมัติเมื่อออนไลน์)
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
  // ส่ง API request
  final data = await api.fetchData();
} else {
  // โหลดจาก cache
  final data = await cache.getData();
}

// หรือตรวจสอบว่าออฟไลน์
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### ตรวจสอบประเภทการเชื่อมต่อเฉพาะ

``` dart
if (await NyConnectivity.isWifi()) {
  // ดาวน์โหลดไฟล์ขนาดใหญ่บน WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // แจ้งเตือนเกี่ยวกับการใช้ข้อมูล
  showDataWarning();
}

// เมธอดอื่นๆ:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### ตรวจสอบอินเทอร์เน็ต

`hasInternet()` เข้มงวดกว่า `isOnline()` — จะคืนค่า `true` เฉพาะเมื่ออุปกรณ์เชื่อมต่อผ่าน wifi, mobile, หรือ ethernet เท่านั้น การเชื่อมต่อ VPN, bluetooth, และ satellite ถูกยกเว้น

``` dart
if (await NyConnectivity.hasInternet()) {
  // ยืนยันการเข้าถึงอินเทอร์เน็ตผ่าน wifi, mobile, หรือ ethernet
  await syncData();
}
```

### รับสถานะปัจจุบัน

``` dart
// รับประเภทการเชื่อมต่อที่ใช้งานอยู่ทั้งหมด
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// รับสตริงที่อ่านได้
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None" เป็นต้น
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

// อย่าลืม cancel เมื่อเสร็จสิ้น
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### การดำเนินการแบบมีเงื่อนไข

``` dart
// ดำเนินการเฉพาะเมื่อออนไลน์ (คืนค่า null หากออฟไลน์)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// ดำเนินการ callback ต่างกันตามสถานะ
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
// แสดง widget ที่แตกต่างกันเมื่อออฟไลน์
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### แสดงเฉพาะเมื่อออนไลน์

``` dart
// ซ่อนทั้งหมดเมื่อออฟไลน์
SyncButton().onlyOnline()
```

### แสดงเฉพาะเมื่อออฟไลน์

``` dart
// แสดงเฉพาะเมื่อออฟไลน์
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## พารามิเตอร์

### Connective

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Widget ที่แสดงเมื่อไม่มี wifi, mobile, และ ethernet เลย |
| `child` | `Widget?` | - | Widget ที่แสดงเมื่อมีอินเทอร์เน็ต |
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
| `satellite` | เชื่อมต่อผ่านดาวเทียม |
| `other` | การเชื่อมต่อประเภทอื่น |
| `none` | ไม่มีการเชื่อมต่อ |
