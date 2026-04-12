# Connective

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Виджет Connective](#connective-widget "Виджет Connective")
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

Используйте `noInternet` для отображения альтернативного виджета, когда устройство не имеет интернета (wifi, mobile или ethernet — все отсутствуют):

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

    // Показать тип подключения
    return Text('Connected via: ${state.name}');
  },
)
```

Построитель получает:
- `context` -- BuildContext
- `state` -- перечисление `NyConnectivityState` (wifi, mobile, ethernet, vpn, bluetooth, satellite, other, none)
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

Отображайте баннер в верхней части экрана при отсутствии интернета (wifi, mobile или ethernet — все отсутствуют):

``` dart
Scaffold(
  body: Stack(
    children: [
      // Ваш основной контент
      MyPageContent(),

      // Офлайн-баннер (скрывается автоматически при наличии подключения)
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
  animate: true, // Анимация скольжения при появлении/скрытии
  animationDuration: Duration(milliseconds: 200),
)
```

<div id="connectivity-helper"></div>

## Помощник NyConnectivity

Класс `NyConnectivity` предоставляет статические методы для проверки подключения:

### Проверка онлайн/офлайн

``` dart
if (await NyConnectivity.isOnline()) {
  // Выполнить API-запрос
  final data = await api.fetchData();
} else {
  // Загрузить из кэша
  final data = await cache.getData();
}

// Или проверить офлайн
if (await NyConnectivity.isOffline()) {
  showOfflineMessage();
}
```

### Проверка конкретных типов подключения

``` dart
if (await NyConnectivity.isWifi()) {
  // Загружать большие файлы по WiFi
  await downloadLargeFile();
}

if (await NyConnectivity.isMobile()) {
  // Предупредить об использовании данных
  showDataWarning();
}

// Другие методы:
await NyConnectivity.isEthernet();
await NyConnectivity.isVpn();
await NyConnectivity.isBluetooth();
```

### Проверка наличия интернета

`hasInternet()` строже, чем `isOnline()` — возвращает `true` только когда устройство подключено через wifi, mobile или ethernet. Подключения через VPN, bluetooth и спутник исключены.

``` dart
if (await NyConnectivity.hasInternet()) {
  // Доступ в интернет подтверждён через wifi, mobile или ethernet
  await syncData();
}
```

### Получение текущего состояния

``` dart
// Получить все активные типы подключения
List<ConnectivityResult> results = await NyConnectivity.status();

if (results.contains(ConnectivityResult.wifi)) {
  print('WiFi is active');
}

// Получить читаемую строку
String type = await NyConnectivity.connectionTypeString();
print('Connected via: $type'); // "WiFi", "Mobile", "None", и т.д.
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

// Не забудьте отменить подписку по завершении
@override
void dispose() {
  subscription.cancel();
  super.dispose();
}
```

### Условное выполнение

``` dart
// Выполнить только при наличии подключения (возвращает null при офлайне)
final data = await NyConnectivity.whenOnline(() async {
  return await api.fetchData();
});

if (data == null) {
  showOfflineMessage();
}

// Выполнить разные обратные вызовы в зависимости от статуса
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
// Показать другой виджет при офлайне
MyContent().connectiveOr(
  offline: Text('Content unavailable offline'),
)
```

### Показ только при онлайне

``` dart
// Полностью скрыть при офлайне
SyncButton().onlyOnline()
```

### Показ только при офлайне

``` dart
// Показывать только при офлайне
OfflineMessage().onlyOffline()
```

<div id="parameters"></div>

## Параметры

### Connective

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `noInternet` | `Widget?` | - | Виджет, отображаемый когда wifi, mobile и ethernet все отсутствуют |
| `child` | `Widget?` | - | Виджет, отображаемый при наличии интернета |
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
| `satellite` | Подключение через спутник |
| `other` | Другой тип подключения |
| `none` | Нет подключения |
