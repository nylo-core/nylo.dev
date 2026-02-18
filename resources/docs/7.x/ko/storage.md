# 스토리지

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- NyStorage
  - [값 저장](#save-values "값 저장")
  - [값 읽기](#read-values "값 읽기")
  - [값 삭제](#delete-values "값 삭제")
  - [Storage Key](#storage-keys "Storage Key")
  - [JSON 저장/읽기](#save-json "JSON 저장/읽기")
  - [TTL (Time-to-Live)](#ttl-storage "TTL (Time-to-Live)")
  - [배치 작업](#batch-operations "배치 작업")
- Collection
  - [소개](#introduction-to-collections "소개")
  - [Collection에 추가](#add-to-a-collection "Collection에 추가")
  - [Collection 읽기](#read-a-collection "Collection 읽기")
  - [Collection 업데이트](#update-collection "Collection 업데이트")
  - [Collection에서 삭제](#delete-from-collection "Collection에서 삭제")
- Backpack
  - [소개](#backpack-storage "소개")
  - [Backpack으로 데이터 영속화](#persist-data-with-backpack "Backpack으로 데이터 영속화")
- [세션](#introduction-to-sessions "세션")
- Model 스토리지
  - [Model 저장](#model-save "Model 저장")
  - [Model Collection](#model-collections "Model Collection")
- [StorageKey Extension 참조](#storage-key-extension-reference "StorageKey Extension 참조")
- [스토리지 예외](#storage-exceptions "스토리지 예외")
- [레거시 마이그레이션](#legacy-migration "레거시 마이그레이션")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 `NyStorage` 클래스를 통해 강력한 스토리지 시스템을 제공합니다. 내부적으로 <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a>를 사용하여 사용자의 기기에 데이터를 안전하게 저장합니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 값 저장
await NyStorage.save("coins", 100);

// 값 읽기
int? coins = await NyStorage.read<int>('coins'); // 100

// 값 삭제
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## 값 저장

`NyStorage.save()` 또는 `storageSave()` 헬퍼를 사용하여 값을 저장합니다:

``` dart
// NyStorage 클래스 사용
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// 헬퍼 함수 사용
await storageSave("username", "Anthony");
```

### Backpack에 동시 저장

영구 스토리지와 인메모리 Backpack 모두에 저장합니다:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// 이제 Backpack을 통해 동기적으로 접근 가능
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## 값 읽기

자동 타입 캐스팅으로 값을 읽습니다:

``` dart
// String으로 읽기 (기본값)
String? username = await NyStorage.read('username');

// 타입 캐스팅으로 읽기
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// 기본값 사용
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// 헬퍼 함수 사용
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## 값 삭제

``` dart
// 단일 키 삭제
await NyStorage.delete('username');

// Backpack에서도 삭제
await NyStorage.delete('auth_token', andFromBackpack: true);

// 여러 키 삭제
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// 전체 삭제 (선택적 제외 포함)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## Storage Key

`lib/config/storage_keys.dart`에서 스토리지 키를 관리합니다:

``` dart
final class StorageKeysConfig {
  // 사용자 인증용 Auth 키
  static StorageKey auth = 'SK_AUTH';

  // 앱 부팅 시 동기화되는 키
  static syncedOnBoot() => () async {
    return [
      coins.defaultValue(0),
      themePreference.defaultValue('light'),
    ];
  };

  static StorageKey coins = 'SK_COINS';
  static StorageKey themePreference = 'SK_THEME_PREFERENCE';
  static StorageKey onboardingComplete = 'SK_ONBOARDING_COMPLETE';
}
```

### StorageKey Extension 사용

`StorageKey`는 `String`의 typedef이며, 강력한 확장 메서드 세트가 함께 제공됩니다:

``` dart
// 저장
await StorageKeysConfig.coins.save(100);

// Backpack과 함께 저장
await StorageKeysConfig.coins.save(100, inBackpack: true);

// 읽기
int? coins = await StorageKeysConfig.coins.read<int>();

// 기본값으로 읽기
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// JSON 저장/읽기
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// 삭제
await StorageKeysConfig.coins.deleteFromStorage();

// 삭제 (별칭)
await StorageKeysConfig.coins.flush();

// Backpack에서 읽기 (동기)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Collection 작업
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## JSON 저장/읽기

JSON 데이터를 저장하고 가져옵니다:

``` dart
// JSON 저장
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// JSON 읽기
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL (Time-to-Live)

{{ config('app.name') }} v7은 자동 만료가 있는 값 저장을 지원합니다:

``` dart
// 1시간 만료로 저장
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// 읽기 (만료된 경우 null 반환)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// 남은 시간 확인
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('${remaining.inMinutes}분 후 만료');
}

// 만료된 모든 키 정리
int removed = await NyStorage.removeExpired();
print('만료된 키 $removed개 제거됨');
```

<div id="batch-operations"></div>

## 배치 작업

여러 스토리지 작업을 효율적으로 처리합니다:

``` dart
// 여러 값 저장
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// 여러 값 읽기
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// 여러 키 삭제
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## Collection 소개

Collection을 사용하면 단일 키 아래에 항목 목록을 저장할 수 있습니다:

``` dart
// Collection에 항목 추가
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Collection 읽기
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Collection에 추가

``` dart
// 항목 추가 (기본적으로 중복 허용)
await NyStorage.addToCollection("cart_ids", item: 123);

// 중복 방지
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// 전체 Collection을 한 번에 저장
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## Collection 읽기

``` dart
// 타입과 함께 Collection 읽기
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Collection이 비어 있는지 확인
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Collection 업데이트

``` dart
// 인덱스로 항목 업데이트
await NyStorage.updateCollectionByIndex<int>(
  0, // 인덱스
  (item) => item + 10, // 변환 함수
  key: "scores",
);

// 조건에 맞는 항목 업데이트
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // where 조건
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## Collection에서 삭제

``` dart
// 인덱스로 삭제
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// 값으로 삭제
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// 조건에 맞는 항목 삭제
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// 전체 Collection 삭제
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Backpack 스토리지

`Backpack`은 사용자 세션 중 빠른 동기 접근을 위한 경량 인메모리 스토리지 클래스입니다. 앱이 종료되면 데이터는 **유지되지 않습니다**.

### Backpack에 저장

``` dart
// 헬퍼 사용
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Backpack 직접 사용
Backpack.instance.save('settings', {'darkMode': true});
```

### Backpack에서 읽기

``` dart
// 헬퍼 사용
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Backpack 직접 사용
var settings = Backpack.instance.read('settings');
```

### Backpack에서 삭제

``` dart
backpackDelete('user_token');

// 전체 삭제
backpackDeleteAll();
```

### 실용적인 예시

``` dart
// 로그인 후 토큰을 영구 스토리지와 세션 스토리지 모두에 저장
Future<void> handleLogin(String token) async {
  // 앱 재시작을 위해 영구 저장
  await NyStorage.save('auth_token', token);

  // 빠른 접근을 위해 Backpack에도 저장
  backpackSave('auth_token', token);
}

// API 서비스에서 동기적으로 접근
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // await 불필요
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Backpack으로 데이터 영속화

한 번의 호출로 영구 스토리지와 Backpack 모두에 저장합니다:

``` dart
// 둘 다에 저장
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// 이제 Backpack(동기)과 NyStorage(비동기) 모두로 접근 가능
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### 스토리지를 Backpack에 동기화

앱 부팅 시 모든 영구 스토리지를 Backpack에 로드합니다:

``` dart
// 앱 Provider에서
await NyStorage.syncToBackpack();

// 덮어쓰기 옵션 사용
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## 세션

세션은 관련 데이터를 그룹화하기 위한 명명된 인메모리 스토리지를 제공합니다 (영속되지 않음):

``` dart
// 세션 생성/접근 및 데이터 추가
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// 또는 데이터로 초기화
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// 세션 데이터 읽기
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// 모든 세션 데이터 가져오기
Map<String, dynamic>? checkoutData = session('checkout').data();

// 단일 값 삭제
session('checkout').delete('coupon');

// 전체 세션 초기화
session('checkout').clear();
// 또는
session('checkout').flush();
```

### 세션 영속화

세션을 영구 스토리지에 동기화할 수 있습니다:

``` dart
// 세션을 스토리지에 저장
await session('checkout').syncToStorage();

// 스토리지에서 세션 복원
await session('checkout').syncFromStorage();
```

### 세션 사용 사례

세션은 다음과 같은 경우에 이상적입니다:
- 다단계 폼 (온보딩, 결제)
- 임시 사용자 환경설정
- 마법사/여정 흐름
- 장바구니 데이터

<div id="model-save"></div>

## Model 저장

`Model` 기본 클래스는 내장 스토리지 메서드를 제공합니다. 생성자에서 `key`를 정의하면 Model이 자체적으로 저장할 수 있습니다:

``` dart
class User extends Model {
  String? name;
  String? email;

  // 스토리지 키 정의
  static StorageKey key = 'user';
  User() : super(key: key);

  User.fromJson(dynamic data) : super(key: key) {
    name = data['name'];
    email = data['email'];
  }

  @override
  Map<String, dynamic> toJson() => {
    "name": name,
    "email": email,
  };
}
```

### Model 저장하기

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// 영구 스토리지에 저장
await user.save();

// 스토리지와 Backpack 모두에 저장
await user.save(inBackpack: true);
```

### Model 다시 읽기

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Backpack에 동기화

동기 접근을 위해 스토리지에서 Model을 Backpack으로 로드합니다:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model Collection

Model을 Collection에 저장합니다:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// 다시 읽기
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## StorageKey Extension 참조

`StorageKey`는 `String`의 typedef입니다. `NyStorageKeyExt` Extension은 다음 메서드를 제공합니다:

| 메서드 | 반환 타입 | 설명 |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | 스토리지에 값 저장 |
| `saveJson(value, {inBackpack})` | `Future` | 스토리지에 JSON 값 저장 |
| `read<T>({defaultValue})` | `Future<T?>` | 스토리지에서 값 읽기 |
| `readJson<T>({defaultValue})` | `Future<T?>` | 스토리지에서 JSON 값 읽기 |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | read의 별칭 |
| `fromBackpack<T>({defaultValue})` | `T?` | Backpack에서 읽기 (동기) |
| `toModel<T>()` | `T` | JSON 문자열을 Model로 변환 |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Collection에 항목 추가 |
| `readCollection<T>()` | `Future<List<T>>` | Collection 읽기 |
| `deleteFromStorage({andFromBackpack})` | `Future` | 스토리지에서 삭제 |
| `flush({andFromBackpack})` | `Future` | deleteFromStorage의 별칭 |
| `defaultValue<T>(value)` | `Future Function(bool)?` | 키가 비어있을 때 기본값 설정 (syncedOnBoot에서 사용) |

<div id="storage-exceptions"></div>

## 스토리지 예외

{{ config('app.name') }} v7은 타입이 지정된 스토리지 예외를 제공합니다:

| 예외 | 설명 |
|-----------|-------------|
| `StorageException` | 메시지와 선택적 키가 있는 기본 예외 |
| `StorageSerializationException` | 스토리지를 위한 객체 직렬화 실패 |
| `StorageDeserializationException` | 저장된 데이터 역직렬화 실패 |
| `StorageKeyNotFoundException` | 스토리지 키를 찾을 수 없음 |
| `StorageTimeoutException` | 스토리지 작업 시간 초과 |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('사용자 로드 실패: ${e.message}');
  print('예상 타입: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## 레거시 마이그레이션

{{ config('app.name') }} v7은 새로운 envelope 스토리지 형식을 사용합니다. v6에서 업그레이드하는 경우 이전 데이터를 마이그레이션할 수 있습니다:

``` dart
// 앱 초기화 중에 호출
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('$migrated개의 키를 새 형식으로 마이그레이션함');
```

이것은 레거시 형식(별도의 `_runtime_type` 키)을 새로운 envelope 형식으로 변환합니다. 마이그레이션은 여러 번 실행해도 안전합니다 - 이미 마이그레이션된 키는 건너뜁니다.
