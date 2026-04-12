# Connective

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [Connective 组件](#connective-widget "Connective 组件")
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

使用 `noInternet` 在设备没有网络（wifi、mobile、ethernet 均不存在）时显示回退组件：

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

    // 显示连接类型
    return Text('Connected via: ${state.name}');
  },
)
```

构建器接收：
- `context` - BuildContext
- `state` - `NyConnectivityState` 枚举（wifi、mobile、ethernet、vpn、bluetooth、satellite、other、none）
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

当没有网络（wifi、mobile、ethernet 均不存在）时，在屏幕顶部显示横幅：

``` dart
Scaffold(
  body: Stack(
    children: [
      // 主要内容
      MyPageContent(),

      // 离线横幅（在线时自动隐藏）
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
  animate: true, // 滑入/滑出动画
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity 辅助类

`NyConnectivity` 类提供了检查连接状态的静态方法：

### 检查在线/离线状态

``` dart
if (await NyConnectivity.isOnline()) {
  // 发起 API 请求
  final data = await api.fetchData();
} else {
  // 从缓存加载
  final data = await cache.getData();
}

// 或检查是否离线
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### 检查特定连接类型

``` dart
if (await NyConnectivity.isWifi()) {
  // 在 WiFi 上下载大文件
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // 警告流量使用
  showDataWarning();
}

// 其他方法：
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### 检查网络连接

`hasInternet()` 比 `isOnline()` 更严格——仅在通过 wifi、mobile 或 ethernet 连接时返回 `true`。VPN、bluetooth 和卫星连接被排除在外。

``` dart
if (await NyConnectivity.hasInternet()) {
  // 已确认通过 wifi、mobile 或 ethernet 访问互联网
  await syncData();
}
```

### 获取当前状态

``` dart
// 获取所有活跃连接类型
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// 获取可读字符串
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

// 完成后记得取消
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### 条件执行

``` dart
// 仅在线时执行（离线时返回 null）
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// 根据状态执行不同的回调
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
// 离线时显示不同的组件
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### 仅在线时显示

``` dart
// 离线时完全隐藏
SyncButton().onlyOnline()
```

### 仅在离线时显示

``` dart
// 仅在离线时显示
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## 参数

### Connective

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | wifi、mobile 和 ethernet 均不存在时显示的组件 |
| `child` | `Widget?` | - | 有网络时显示的组件 |
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
| `satellite` | 通过卫星连接 |
| `other` | 其他连接类型 |
| `none` | 无连接 |
