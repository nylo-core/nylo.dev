# Connective

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [Connective ウィジェット](#connective-widget "Connective ウィジェット")
    - [状態ベースのビルダー](#state-builders "状態ベースのビルダー")
    - [カスタムビルダー](#custom-builder "カスタムビルダー")
- [OfflineBanner ウィジェット](#offline-banner "OfflineBanner ウィジェット")
- [NyConnectivity ヘルパー](#connectivity-helper "NyConnectivity ヘルパー")
- [ウィジェットエクステンション](#extensions "ウィジェットエクステンション")
- [パラメータ](#parameters "パラメータ")


<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は、ネットワーク変更に応答するアプリを構築するための接続性対応ウィジェットとユーティリティを提供します。**Connective** ウィジェットは接続状態の変更時に自動的に再構築し、**NyConnectivity** ヘルパーは接続状態を確認するための静的メソッドを提供します。

<div id="connective-widget"></div>

## Connective ウィジェット

`Connective` ウィジェットは接続状態の変更を監視し、現在のネットワーク状態に基づいて再構築します。

<div id="state-builders"></div>

### 状態ベースのビルダー

各接続タイプに異なるウィジェットを提供します:

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
  child: Text('Connected'), // 未指定の状態のデフォルト
)
```

#### 利用可能な状態

| プロパティ | 説明 |
|----------|-------------|
| `onWifi` | WiFi 接続時のウィジェット |
| `onMobile` | モバイルデータ接続時のウィジェット |
| `onEthernet` | イーサネット接続時のウィジェット |
| `onVpn` | VPN 接続時のウィジェット |
| `onBluetooth` | Bluetooth 接続時のウィジェット |
| `onOther` | その他の接続タイプのウィジェット |
| `onNone` | オフライン時のウィジェット |
| `child` | 特定のハンドラーがない場合のデフォルトウィジェット |

<div id="custom-builder"></div>

### カスタムビルダー

`Connective.builder` を使用して UI を完全に制御します:

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

    // 接続タイプを表示
    return Text('Connected via: ${state.name}');
  },
)
```

ビルダーは以下を受け取ります:
- `context` - BuildContext
- `state` - `NyConnectivityState` 列挙型（wifi、mobile、ethernet、vpn、bluetooth、other、none）
- `results` - 複数の接続を確認するための `List<ConnectivityResult>`

### 変更の監視

`onConnectivityChanged` を使用して接続状態の変更に対応します:

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

## OfflineBanner ウィジェット

オフライン時に画面上部にバナーを表示します:

``` dart
Scaffold(
  body: Stack(
    children: [
      // メインコンテンツ
      MyPageContent(),

      // オフラインバナー（オンライン時に自動的に非表示）
      OfflineBanner(),
    ],
  ),
)
```

### バナーのカスタマイズ

``` dart
OfflineBanner(
  message: 'Check your connection',
  backgroundColor: Colors.orange,
  textColor: Colors.white,
  icon: Icons.signal_wifi_off,
  height: 50,
  animate: true, // スライドイン/アウトアニメーション
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity ヘルパー

`NyConnectivity` クラスは接続状態を確認するための静的メソッドを提供します:

### オンライン/オフラインの確認

``` dart
if (await NyConnectivity.isOnline()) {
  // API リクエストを実行
  final data = await api.fetchData();
} else {
  // キャッシュから読み込み
  final data = await cache.getData();
}

// オフラインかどうかを確認
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### 特定の接続タイプの確認

``` dart
if (await NyConnectivity.isWifi()) {
  // WiFi 接続時に大きなファイルをダウンロード
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // データ使用量について警告
  showDataWarning();
}

// その他のメソッド:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### 現在のステータスを取得

``` dart
// すべてのアクティブな接続タイプを取得
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// 人間が読める文字列を取得
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None" など
```

### 変更の監視

``` dart
StreamSubscription subscription = NyConnectivity.stream().listen((results) {
  if (results.contains(ConnectivityResult.none)) {
    showOfflineUI();
  } else {
    showOnlineUI();
  }
});

// 完了時にキャンセルすることを忘れずに
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### 条件付き実行

``` dart
// オンライン時のみ実行（オフラインの場合は null を返す）
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// ステータスに基づいて異なるコールバックを実行
final result = await NyConnectivity.when(
  online: () async => await api.fetchData(),
  offline: () async => await cache.getData(),
);
```

<div id="extensions"></div>

## ウィジェットエクステンション

任意のウィジェットに接続性対応を素早く追加します:

### オフライン時の代替表示

``` dart
// オフライン時に別のウィジェットを表示
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### オンライン時のみ表示

``` dart
// オフライン時に完全に非表示
SyncButton().onlyOnline()
```

### オフライン時のみ表示

``` dart
// オフライン時のみ表示
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## パラメータ

### Connective

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | WiFi 接続時のウィジェット |
| `onMobile` | `Widget?` | - | モバイルデータ接続時のウィジェット |
| `onEthernet` | `Widget?` | - | イーサネット接続時のウィジェット |
| `onVpn` | `Widget?` | - | VPN 接続時のウィジェット |
| `onBluetooth` | `Widget?` | - | Bluetooth 接続時のウィジェット |
| `onOther` | `Widget?` | - | その他の接続のウィジェット |
| `onNone` | `Widget?` | - | オフライン時のウィジェット |
| `child` | `Widget?` | - | デフォルトウィジェット |
| `showLoadingOnInit` | `bool` | `false` | 確認中にローディングを表示 |
| `loadingWidget` | `Widget?` | - | カスタムローディングウィジェット |
| `onConnectivityChanged` | `Function?` | - | 変更時のコールバック |

### OfflineBanner

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | バナーテキスト |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | バナーの背景色 |
| `textColor` | `Color?` | `Colors.white` | テキスト色 |
| `icon` | `IconData?` | `Icons.wifi_off` | バナーアイコン |
| `height` | `double` | `40` | バナーの高さ |
| `animate` | `bool` | `true` | スライドアニメーションを有効化 |
| `animationDuration` | `Duration` | `300ms` | アニメーション時間 |

### NyConnectivityState 列挙型

| 値 | 説明 |
|-------|-------------|
| `wifi` | WiFi 接続 |
| `mobile` | モバイルデータ接続 |
| `ethernet` | イーサネット接続 |
| `vpn` | VPN 接続 |
| `bluetooth` | Bluetooth 接続 |
| `other` | その他の接続タイプ |
| `none` | 接続なし |
