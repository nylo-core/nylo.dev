# Storage

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về lưu trữ")
- NyStorage
  - [Lưu giá trị](#save-values "Lưu giá trị")
  - [Đọc giá trị](#read-values "Đọc giá trị")
  - [Xóa giá trị](#delete-values "Xóa giá trị")
  - [Khóa lưu trữ](#storage-keys "Khóa lưu trữ")
  - [Lưu/Đọc JSON](#save-json "Lưu và đọc JSON")
  - [TTL (Thời gian sống)](#ttl-storage "TTL Storage")
  - [Thao tác hàng loạt](#batch-operations "Thao tác hàng loạt")
- Collections
  - [Giới thiệu](#introduction-to-collections "Giới thiệu về collections")
  - [Thêm vào Collection](#add-to-a-collection "Thêm vào collection")
  - [Đọc Collection](#read-a-collection "Đọc collection")
  - [Cập nhật Collection](#update-collection "Cập nhật collection")
  - [Xóa từ Collection](#delete-from-collection "Xóa từ collection")
- Backpack
  - [Giới thiệu](#backpack-storage "Lưu trữ Backpack")
  - [Lưu trữ bền vững với Backpack](#persist-data-with-backpack "Lưu trữ bền vững với Backpack")
- [Sessions](#introduction-to-sessions "Sessions")
- Lưu trữ Model
  - [Lưu Model](#model-save "Lưu Model")
  - [Model Collections](#model-collections "Model Collections")
- [Tham chiếu Extension StorageKey](#storage-key-extension-reference "Tham chiếu Extension StorageKey")
- [Ngoại lệ Storage](#storage-exceptions "Ngoại lệ Storage")
- [Di chuyển cũ](#legacy-migration "Di chuyển cũ")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp hệ thống lưu trữ mạnh mẽ thông qua class `NyStorage`. Nó sử dụng <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> bên dưới để lưu trữ dữ liệu an toàn trên thiết bị người dùng.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Lưu giá trị
await NyStorage.save("coins", 100);

// Đọc giá trị
int? coins = await NyStorage.read<int>('coins'); // 100

// Xóa giá trị
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## Lưu giá trị

Lưu giá trị bằng `NyStorage.save()` hoặc helper `storageSave()`:

``` dart
// Sử dụng class NyStorage
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Sử dụng hàm helper
await storageSave("username", "Anthony");
```

### Lưu đồng thời vào Backpack

Lưu trữ cả trong bộ nhớ bền vững và Backpack trong bộ nhớ:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Giờ có thể truy cập đồng bộ qua Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Đọc giá trị

Đọc giá trị với chuyển đổi kiểu tự động:

``` dart
// Đọc dạng String (mặc định)
String? username = await NyStorage.read('username');

// Đọc với chuyển đổi kiểu
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// Với giá trị mặc định
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// Sử dụng hàm helper
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## Xóa giá trị

``` dart
// Xóa một khóa
await NyStorage.delete('username');

// Xóa và cũng xóa khỏi Backpack
await NyStorage.delete('auth_token', andFromBackpack: true);

// Xóa nhiều khóa
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// Xóa tất cả (với tùy chọn loại trừ)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## Khóa lưu trữ

Tổ chức khóa lưu trữ trong `lib/config/storage_keys.dart`:

``` dart
final class StorageKeysConfig {
  // Khóa xác thực cho người dùng
  static StorageKey auth = 'SK_AUTH';

  // Các khóa đồng bộ khi khởi động ứng dụng
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

### Sử dụng Extensions StorageKey

`StorageKey` là typedef cho `String`, và đi kèm với bộ extension methods mạnh mẽ:

``` dart
// Lưu
await StorageKeysConfig.coins.save(100);

// Lưu với Backpack
await StorageKeysConfig.coins.save(100, inBackpack: true);

// Đọc
int? coins = await StorageKeysConfig.coins.read<int>();

// Đọc với giá trị mặc định
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// Lưu/Đọc JSON
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// Xóa
await StorageKeysConfig.coins.deleteFromStorage();

// Xóa (bí danh)
await StorageKeysConfig.coins.flush();

// Đọc từ Backpack (đồng bộ)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Thao tác collection
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## Lưu/Đọc JSON

Lưu trữ và truy xuất dữ liệu JSON:

``` dart
// Lưu JSON
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// Đọc JSON
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL (Thời gian sống)

{{ config('app.name') }} v7 hỗ trợ lưu trữ giá trị với hết hạn tự động:

``` dart
// Lưu với hết hạn 1 giờ
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// Đọc (trả về null nếu hết hạn)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// Kiểm tra thời gian còn lại
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Hết hạn sau ${remaining.inMinutes} phút');
}

// Dọn dẹp tất cả khóa hết hạn
int removed = await NyStorage.removeExpired();
print('Đã xóa $removed khóa hết hạn');
```

<div id="batch-operations"></div>

## Thao tác hàng loạt

Xử lý hiệu quả nhiều thao tác lưu trữ:

``` dart
// Lưu nhiều giá trị
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// Đọc nhiều giá trị
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// Xóa nhiều khóa
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## Giới thiệu về Collections

Collections cho phép bạn lưu trữ danh sách các mục dưới một khóa duy nhất:

``` dart
// Thêm mục vào collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Đọc collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Thêm vào Collection

``` dart
// Thêm mục (cho phép trùng lặp mặc định)
await NyStorage.addToCollection("cart_ids", item: 123);

// Ngăn trùng lặp
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// Lưu toàn bộ collection cùng lúc
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## Đọc Collection

``` dart
// Đọc collection với kiểu
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Kiểm tra collection có trống không
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Cập nhật Collection

``` dart
// Cập nhật mục theo chỉ mục
await NyStorage.updateCollectionByIndex<int>(
  0, // chỉ mục
  (item) => item + 10, // hàm chuyển đổi
  key: "scores",
);

// Cập nhật các mục khớp điều kiện
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // điều kiện where
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## Xóa từ Collection

``` dart
// Xóa theo chỉ mục
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// Xóa theo giá trị
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// Xóa các mục khớp điều kiện
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// Xóa toàn bộ collection
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Lưu trữ Backpack

`Backpack` là class lưu trữ nhẹ trong bộ nhớ để truy cập đồng bộ nhanh trong phiên người dùng. Dữ liệu **không được lưu trữ bền vững** khi ứng dụng đóng.

### Lưu vào Backpack

``` dart
// Sử dụng helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Sử dụng Backpack trực tiếp
Backpack.instance.save('settings', {'darkMode': true});
```

### Đọc từ Backpack

``` dart
// Sử dụng helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Sử dụng Backpack trực tiếp
var settings = Backpack.instance.read('settings');
```

### Xóa từ Backpack

``` dart
backpackDelete('user_token');

// Xóa tất cả
backpackDeleteAll();
```

### Ví dụ thực tế

``` dart
// Sau khi đăng nhập, lưu token cả trong bộ nhớ bền vững và phiên
Future<void> handleLogin(String token) async {
  // Lưu bền vững cho khởi động lại ứng dụng
  await NyStorage.save('auth_token', token);

  // Cũng lưu trong Backpack để truy cập nhanh
  backpackSave('auth_token', token);
}

// Trong API service, truy cập đồng bộ
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // Không cần await
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Lưu trữ bền vững với Backpack

Lưu trữ cả trong bộ nhớ bền vững và Backpack trong một lần gọi:

``` dart
// Lưu vào cả hai
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Giờ có thể truy cập qua Backpack (đồng bộ) và NyStorage (bất đồng bộ)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Đồng bộ Storage vào Backpack

Tải toàn bộ bộ nhớ bền vững vào Backpack khi khởi động ứng dụng:

``` dart
// Trong app provider
await NyStorage.syncToBackpack();

// Với tùy chọn ghi đè
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessions

Sessions cung cấp lưu trữ có tên trong bộ nhớ để nhóm dữ liệu liên quan (không lưu bền vững):

``` dart
// Tạo/truy cập session và thêm dữ liệu
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// Hoặc khởi tạo với dữ liệu
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// Đọc dữ liệu session
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// Lấy tất cả dữ liệu session
Map<String, dynamic>? checkoutData = session('checkout').data();

// Xóa một giá trị
session('checkout').delete('coupon');

// Xóa toàn bộ session
session('checkout').clear();
// hoặc
session('checkout').flush();
```

### Lưu bền vững Sessions

Sessions có thể đồng bộ với bộ nhớ bền vững:

``` dart
// Lưu session vào storage
await session('checkout').syncToStorage();

// Khôi phục session từ storage
await session('checkout').syncFromStorage();
```

### Trường hợp sử dụng Session

Sessions lý tưởng cho:
- Form nhiều bước (onboarding, thanh toán)
- Tùy chọn người dùng tạm thời
- Luồng wizard/hành trình
- Dữ liệu giỏ hàng

<div id="model-save"></div>

## Lưu Model

Class cơ sở `Model` cung cấp các phương thức lưu trữ tích hợp. Khi bạn định nghĩa `key` trong constructor, model có thể tự lưu:

``` dart
class User extends Model {
  String? name;
  String? email;

  // Định nghĩa khóa lưu trữ
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

### Lưu Model

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Lưu vào bộ nhớ bền vững
await user.save();

// Lưu vào cả storage và Backpack
await user.save(inBackpack: true);
```

### Đọc lại Model

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Đồng bộ vào Backpack

Tải model từ storage vào Backpack để truy cập đồng bộ:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model Collections

Lưu models vào collection:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// Đọc lại
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## Tham chiếu Extension StorageKey

`StorageKey` là typedef cho `String`. Extension `NyStorageKeyExt` cung cấp các phương thức sau:

| Phương thức | Trả về | Mô tả |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | Lưu giá trị vào storage |
| `saveJson(value, {inBackpack})` | `Future` | Lưu giá trị JSON vào storage |
| `read<T>({defaultValue})` | `Future<T?>` | Đọc giá trị từ storage |
| `readJson<T>({defaultValue})` | `Future<T?>` | Đọc giá trị JSON từ storage |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Bí danh cho read |
| `fromBackpack<T>({defaultValue})` | `T?` | Đọc từ Backpack (đồng bộ) |
| `toModel<T>()` | `T` | Chuyển đổi chuỗi JSON thành model |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Thêm mục vào collection |
| `readCollection<T>()` | `Future<List<T>>` | Đọc collection |
| `deleteFromStorage({andFromBackpack})` | `Future` | Xóa từ storage |
| `flush({andFromBackpack})` | `Future` | Bí danh cho deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Đặt mặc định nếu khóa trống (dùng trong syncedOnBoot) |

<div id="storage-exceptions"></div>

## Ngoại lệ Storage

{{ config('app.name') }} v7 cung cấp các ngoại lệ storage có kiểu:

| Ngoại lệ | Mô tả |
|-----------|-------------|
| `StorageException` | Ngoại lệ cơ sở với thông báo và khóa tùy chọn |
| `StorageSerializationException` | Không thể tuần tự hóa đối tượng cho storage |
| `StorageDeserializationException` | Không thể giải tuần tự hóa dữ liệu đã lưu |
| `StorageKeyNotFoundException` | Không tìm thấy khóa storage |
| `StorageTimeoutException` | Thao tác storage hết thời gian chờ |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Không thể tải user: ${e.message}');
  print('Kiểu mong đợi: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Di chuyển cũ

{{ config('app.name') }} v7 sử dụng định dạng lưu trữ envelope mới. Nếu bạn đang nâng cấp từ v6, bạn có thể di chuyển dữ liệu cũ:

``` dart
// Gọi trong quá trình khởi tạo ứng dụng
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Đã di chuyển $migrated khóa sang định dạng mới');
```

Điều này chuyển đổi định dạng cũ (các khóa `_runtime_type` riêng biệt) sang định dạng envelope mới. Quá trình di chuyển an toàn để chạy nhiều lần -- các khóa đã di chuyển sẽ được bỏ qua.
