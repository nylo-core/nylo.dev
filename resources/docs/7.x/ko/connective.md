# Connective

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [Connective 위젯](#connective-widget "Connective 위젯")
    - [커스텀 빌더](#custom-builder "커스텀 빌더")
- [OfflineBanner 위젯](#offline-banner "OfflineBanner 위젯")
- [NyConnectivity 헬퍼](#connectivity-helper "NyConnectivity 헬퍼")
- [위젯 확장](#extensions "위젯 확장")
- [매개변수](#parameters "매개변수")


<div id="introduction"></div>

## 소개

{{ config('app.name') }}은 네트워크 변경에 반응하는 앱을 구축하는 데 도움이 되는 연결 인식 위젯과 유틸리티를 제공합니다. **Connective** 위젯은 연결 상태가 변경되면 자동으로 다시 빌드되며, **NyConnectivity** 헬퍼는 연결 상태를 확인하기 위한 정적 메서드를 제공합니다.

<div id="connective-widget"></div>

## Connective 위젯

`Connective` 위젯은 연결 변경을 수신하고 현재 네트워크 상태에 따라 다시 빌드합니다.

`noInternet`을 사용하여 디바이스에 인터넷이 없을 때(wifi, mobile, ethernet 모두 없을 때) 대체 위젯을 표시합니다:

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

### 커스텀 빌더

UI를 완전히 제어하려면 `Connective.builder`를 사용합니다:

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

    // 연결 유형 표시
    return Text('Connected via: ${state.name}');
  },
)
```

빌더는 다음을 받습니다:
- `context` - BuildContext
- `state` - `NyConnectivityState` enum (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
- `results` - 여러 연결을 확인하기 위한 `List<ConnectivityResult>`

### 변경 사항 수신

연결 상태가 변경될 때 반응하려면 `onConnectivityChanged`를 사용합니다:

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

## OfflineBanner 위젯

인터넷이 없을 때(wifi, mobile, ethernet 모두 없을 때) 화면 상단에 배너를 표시합니다:

``` dart
Scaffold(
  body: Stack(
    children: [
      // 메인 콘텐츠
      MyPageContent(),

      // 오프라인 배너 (온라인일 때 자동 숨김)
      OfflineBanner(),
    ],
  ),
)
```

### 배너 커스터마이징

``` dart
OfflineBanner(
  message: 'Check your connection',
  backgroundColor: Colors.orange,
  textColor: Colors.white,
  icon: Icons.signal_wifi_off,
  height: 50,
  animate: true, // 슬라이드 인/아웃 애니메이션
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## NyConnectivity 헬퍼

`NyConnectivity` 클래스는 연결 상태를 확인하기 위한 정적 메서드를 제공합니다:

### 온라인/오프라인 확인

``` dart
if (await NyConnectivity.isOnline()) {
  // API 요청 수행
  final data = await api.fetchData();
} else {
  // 캐시에서 로드
  final data = await cache.getData();
}

// 또는 오프라인 확인
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### 특정 연결 유형 확인

``` dart
if (await NyConnectivity.isWifi()) {
  // WiFi에서 대용량 파일 다운로드
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // 데이터 사용량 경고
  showDataWarning();
}

// 기타 메서드:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### 인터넷 확인

`hasInternet()`은 `isOnline()`보다 엄격합니다 — wifi, mobile, 또는 ethernet을 통해 연결된 경우에만 `true`를 반환합니다. VPN, bluetooth, satellite 연결은 제외됩니다.

``` dart
if (await NyConnectivity.hasInternet()) {
  // wifi, mobile, 또는 ethernet을 통한 인터넷 접근 확인됨
  await syncData();
}
```

### 현재 상태 가져오기

``` dart
// 모든 활성 연결 유형 가져오기
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// 사람이 읽을 수 있는 문자열 가져오기
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", etc.
```

### 변경 사항 수신

``` dart
StreamSubscription subscription = NyConnectivity.stream().listen((results) {
  if (results.contains(ConnectivityResult.none)) {
    showOfflineUI();
  } else {
    showOnlineUI();
  }
});

// 완료 시 취소를 잊지 마세요
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### 조건부 실행

``` dart
// 온라인일 때만 실행 (오프라인이면 null 반환)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// 상태에 따라 다른 콜백 실행
final result = await NyConnectivity.when(
  online: () async => await api.fetchData(),
  offline: () async => await cache.getData(),
);
```

<div id="extensions"></div>

## 위젯 확장

모든 위젯에 연결 인식을 빠르게 추가합니다:

### 오프라인 대안 표시

``` dart
// 오프라인일 때 다른 위젯 표시
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### 온라인일 때만 표시

``` dart
// 오프라인일 때 완전히 숨김
SyncButton().onlyOnline()
```

### 오프라인일 때만 표시

``` dart
// 오프라인일 때만 표시
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## 매개변수

### Connective

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | wifi, mobile, ethernet 모두 없을 때 표시되는 위젯 |
| `child` | `Widget?` | - | 인터넷이 사용 가능할 때 표시되는 위젯 |
| `onConnectivityChanged` | `Function?` | - | 변경 시 콜백 |

### OfflineBanner

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | 배너 텍스트 |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | 배너 배경색 |
| `textColor` | `Color?` | `Colors.white` | 텍스트 색상 |
| `icon` | `IconData?` | `Icons.wifi_off` | 배너 아이콘 |
| `height` | `double` | `40` | 배너 높이 |
| `animate` | `bool` | `true` | 슬라이드 애니메이션 활성화 |
| `animationDuration` | `Duration` | `300ms` | 애니메이션 지속 시간 |

### NyConnectivityState Enum

| 값 | 설명 |
|-------|-------------|
| `wifi` | WiFi로 연결됨 |
| `mobile` | 모바일 데이터로 연결됨 |
| `ethernet` | 이더넷으로 연결됨 |
| `vpn` | VPN으로 연결됨 |
| `bluetooth` | Bluetooth로 연결됨 |
| `satellite` | 위성으로 연결됨 |
| `other` | 기타 연결 유형 |
| `none` | 연결 없음 |
