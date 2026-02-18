# Connective

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Виджет Connective](#connective-widget "Виджет Connective")
    - [Построители на основе состояния](#state-builders "Построители на основе состояния")
    - [Пользовательский построитель](#custom-builder "Пользовательский построитель")
- [Виджет OfflineBanner](#offline-banner "Виджет OfflineBanner")
- [Помощник NyConnectivity](#connectivity-helper "Помощник NyConnectivity")
- [Расширения виджетов](#extensions "Расширения виджетов")
- [Параметры](#parameters "Параметры")


<div id="introduction"></div>

## Введение

{{ config('app.name') }} предоставляет виджеты и утилиты, учитывающие состояние подключения, для создания приложений, реагирующих на изменения сети. Виджет **Connective** автоматически перестраивается при изменении подключения, а помощник **NyConnectivity** предоставляет статические методы для проверки состояния соединения.

<div id="connective-widget"></div>

## Виджет Connective

Виджет `Connective` отслеживает изменения подключения и перестраивается в зависимости от текущего состояния сети.

<div id="state-builders"></div>

### Построители на основе состояния

Предоставляйте различные виджеты для каждого типа подключения:

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

#### Доступные состояния

| Свойство | Описание |
|----------|-------------|
| `onWifi` | Виджет при подключении через WiFi |
| `onMobile` | Виджет при подключении через мобильные данные |
| `onEthernet` | Виджет при подключении через Ethernet |
| `onVpn` | Виджет при подключении через VPN |
| `onBluetooth` | Виджет при подключении через Bluetooth |
| `onOther` | Виджет для других типов подключения |
| `onNone` | Виджет при отсутствии подключения |
| `child` | Виджет по умолчанию, если конкретный обработчик не указан |

<div id="custom-builder"></div>

### Пользовательский построитель

Используйте `Connective.builder` для полного контроля над интерфейсом:

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

Построитель получает:
- `context` -- BuildContext
- `state` -- перечисление `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, other, none)
- `results` -- `List<ConnectivityResult>` для проверки нескольких подключений

### Отслеживание изменений

Используйте `onConnectivityChanged` для реагирования на изменения подключения:

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

## Виджет OfflineBanner

Отображайте баннер в верхней части экрана при отсутствии подключения:

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

### Настройка баннера

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

## Помощник NyConnectivity

Класс `NyConnectivity` предоставляет статические методы для проверки подключения:

### Проверка онлайн/офлайн

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

### Проверка конкретных типов подключения

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

### Получение текущего состояния

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

### Отслеживание изменений

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

### Условное выполнение

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

## Расширения виджетов

Быстро добавляйте учёт подключения к любому виджету:

### Показ альтернативы при офлайне

``` dart
// Show a different widget when offline
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Показ только при онлайне

``` dart
// Hide completely when offline
SyncButton().onlyOnline()
```

### Показ только при офлайне

``` dart
// Show only when offline
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Параметры

### Connective

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `onWifi` | `Widget?` | - | Виджет при подключении через WiFi |
| `onMobile` | `Widget?` | - | Виджет при подключении через мобильные данные |
| `onEthernet` | `Widget?` | - | Виджет при подключении через Ethernet |
| `onVpn` | `Widget?` | - | Виджет при подключении через VPN |
| `onBluetooth` | `Widget?` | - | Виджет при подключении через Bluetooth |
| `onOther` | `Widget?` | - | Виджет для других подключений |
| `onNone` | `Widget?` | - | Виджет при отсутствии подключения |
| `child` | `Widget?` | - | Виджет по умолчанию |
| `showLoadingOnInit` | `bool` | `false` | Показывать загрузку при проверке |
| `loadingWidget` | `Widget?` | - | Пользовательский виджет загрузки |
| `onConnectivityChanged` | `Function?` | - | Обратный вызов при изменении |

### OfflineBanner

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `message` | `String` | `'No internet connection'` | Текст баннера |
| `backgroundColor` | `Color?` | `Colors.red.shade700` | Фон баннера |
| `textColor` | `Color?` | `Colors.white` | Цвет текста |
| `icon` | `IconData?` | `Icons.wifi_off` | Иконка баннера |
| `height` | `double` | `40` | Высота баннера |
| `animate` | `bool` | `true` | Включить анимацию скольжения |
| `animationDuration` | `Duration` | `300ms` | Длительность анимации |

### Перечисление NyConnectivityState

| Значение | Описание |
|-------|-------------|
| `wifi` | Подключение через WiFi |
| `mobile` | Подключение через мобильные данные |
| `ethernet` | Подключение через Ethernet |
| `vpn` | Подключение через VPN |
| `bluetooth` | Подключение через Bluetooth |
| `other` | Другой тип подключения |
| `none` | Нет подключения |
