# Connective

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [Connective 组件](#connective-widget "Connective 组件")
    - [基于状态的构建器](#state-builders "基于状态的构建器")
    - [自定义构建器](#custom-builder "自定义构建器")
- [OfflineBanner 组件](#offline-banner "OfflineBanner 组件")
- [NyConnectivity 辅助类](#connectivity-helper "NyConnectivity 辅助类")
- [组件扩展](#extensions "组件扩展")
- [参数](#parameters "参数")


<div id="introduction"></div>

## 简介

{{ config('app.name') }} 提供了网络感知组件和实用工具，帮助您构建响应网络变化的应用。**Connective** 组件在连接状态改变时自动重建，而 **NyConnectivity** 辅助类提供了检查连接状态的静态方法。

<div id="connective-widget"></div>

## Connective 组件

`Connective` 组件监听连接变化，并根据当前网络状态重建。

<div id="state-builders"></div>

### 基于状态的构建器

为每种连接类型提供不同的组件：

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

#### 可用状态

| 属性 | 描述 |
|----------|-------------|
| `onWifi` | 通过 WiFi 连接时的组件 |
| `onMobile` | 通过移动数据连接时的组件 |
| `onEthernet` | 通过以太网连接时的组件 |
| `onVpn` | 通过 VPN 连接时的组件 |
| `onBluetooth` | 通过蓝牙连接时的组件 |
| `onOther` | 其他连接类型的组件 |
| `onNone` | 离线时的组件 |
| `child` | 未提供特定处理器时的默认组件 |

<div id="custom-builder"></div>

### 自定义构建器

使用 `Connective.builder` 完全控制 UI：

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

构建器接收：
- `context` - BuildContext
- `state` - `NyConnectivityState` 枚举（wifi、mobile、ethernet、vpn、bluetooth、other、none）
- `results` - `List<ConnectivityResult>`，用于检查多个连接

### 监听变化

使用 `onConnectivityChanged` 在连接状态变化时作出响应：

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

## OfflineBanner 组件

在离线时在屏幕顶部显示横幅：

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

### 自定义横幅

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

## NyConnectivity 辅助类

`NyConnectivity` 类提供了检查连接状态的静态方法：

### 检查在线/离线状态

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

### 检查特定连接类型

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

### 获取当前状态

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

### 监听变化

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

### 条件执行

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

## 组件扩展

快速为任何组件添加网络感知功能：

### 显示离线替代方案

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### 仅在线时显示

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### 仅在离线时显示

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## 参数

### Connective

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | WiFi 连接时的组件 |
| `onMobile` | `Widget?` | - | 移动数据连接时的组件 |
| `onEthernet` | `Widget?` | - | 以太网连接时的组件 |
| `onVpn` | `Widget?` | - | VPN 连接时的组件 |
| `onBluetooth` | `Widget?` | - | 蓝牙连接时的组件 |
| `onOther` | `Widget?` | - | 其他连接时的组件 |
| `onNone` | `Widget?` | - | 离线时的组件 |
| `child` | `Widget?` | - | 默认组件 |
| `showLoadingOnInit` | `bool` | `false` | 检查时显示加载状态 |
| `loadingWidget` | `Widget?` | - | 自定义加载组件 |
| `onConnectivityChanged` | `Function?` | - | 变化时的回调 |

### OfflineBanner

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | 横幅文字 |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | 横幅背景色 |
| `textColor` | `Color?` | `Colors.white` | 文字颜色 |
| `icon` | `IconData?` | `Icons.wifi_off` | 横幅图标 |
| `height` | `double` | `40` | 横幅高度 |
| `animate` | `bool` | `true` | 启用滑入/滑出动画 |
| `animationDuration` | `Duration` | `300ms` | 动画持续时间 |

### NyConnectivityState 枚举

| 值 | 描述 |
|-------|-------------|
| `wifi` | 通过 WiFi 连接 |
| `mobile` | 通过移动数据连接 |
| `ethernet` | 通过以太网连接 |
| `vpn` | 通过 VPN 连接 |
| `bluetooth` | 通过蓝牙连接 |
| `other` | 其他连接类型 |
| `none` | 无连接 |
