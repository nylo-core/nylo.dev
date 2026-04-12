# Backpack

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Đọc dữ liệu](#reading-data "Đọc dữ liệu")
- [Lưu dữ liệu](#saving-data "Lưu dữ liệu")
- [Xóa dữ liệu](#deleting-data "Xóa dữ liệu")
- [Phiên](#sessions "Phiên")
- [Truy cập instance Nylo](#nylo-instance "Truy cập instance Nylo")
- [Hàm trợ giúp](#helper-functions "Hàm trợ giúp")
- [Tích hợp với NyStorage](#integration-with-nystorage "Tích hợp với NyStorage")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

**Backpack** là hệ thống lưu trữ singleton trong bộ nhớ của {{ config('app.name') }}. Nó cung cấp truy cập đồng bộ, nhanh chóng đến dữ liệu trong thời gian chạy ứng dụng. Không giống như `NyStorage` lưu trữ dữ liệu vĩnh viễn trên thiết bị, Backpack lưu trữ dữ liệu trong bộ nhớ và được xóa khi ứng dụng đóng.

Backpack được framework sử dụng nội bộ để lưu trữ các instance quan trọng như đối tượng ứng dụng `Nylo`, `EventBus` và dữ liệu xác thực. Bạn cũng có thể sử dụng nó để lưu trữ dữ liệu của riêng mình cần được truy cập nhanh chóng mà không cần các lời gọi bất đồng bộ.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Lưu một giá trị
Backpack.instance.save("user_name", "Anthony");

// Đọc một giá trị (đồng bộ)
String? name = Backpack.instance.read("user_name");

// Xóa một giá trị
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Backpack sử dụng **mẫu singleton** -- truy cập thông qua `Backpack.instance`:

``` dart
// Lưu dữ liệu
Backpack.instance.save("theme", "dark");

// Đọc dữ liệu
String? theme = Backpack.instance.read("theme"); // "dark"

// Kiểm tra xem dữ liệu có tồn tại không
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Đọc dữ liệu

Đọc giá trị từ Backpack bằng phương thức `read<T>()`. Nó hỗ trợ kiểu generic và giá trị mặc định tùy chọn:

``` dart
// Đọc một String
String? name = Backpack.instance.read<String>("name");

// Đọc với giá trị mặc định
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Đọc một int
int? score = Backpack.instance.read<int>("score");
```

Backpack tự động giải tuần tự các giá trị được lưu thành đối tượng model khi cung cấp kiểu. Điều này hoạt động cho cả chuỗi JSON và giá trị `Map<String, dynamic>` thô:

``` dart
// Nếu model User được lưu dưới dạng chuỗi JSON, nó sẽ được giải tuần tự
User? user = Backpack.instance.read<User>("current_user");

// Nếu một Map thô được lưu (ví dụ: qua syncKeys từ NyStorage), nó cũng
// tự động được giải tuần tự thành model có kiểu khi đọc
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // trả về một User
```

<div id="saving-data"></div>

## Lưu dữ liệu

Lưu giá trị bằng phương thức `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Thêm dữ liệu vào danh sách

Sử dụng `append()` để thêm giá trị vào danh sách được lưu tại một khóa:

``` dart
// Thêm vào danh sách
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Thêm với giới hạn (chỉ giữ N mục cuối cùng)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Xóa dữ liệu

### Xóa một khóa

``` dart
Backpack.instance.delete("api_token");
```

### Xóa tất cả dữ liệu

Phương thức `deleteAll()` xóa tất cả giá trị **ngoại trừ** các khóa framework dành riêng (`nylo` và `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Phiên

Backpack cung cấp quản lý phiên để tổ chức dữ liệu thành các nhóm được đặt tên. Điều này hữu ích để lưu trữ dữ liệu liên quan cùng nhau.

### Cập nhật giá trị phiên

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Lấy giá trị phiên

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Xóa khóa phiên

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Xóa toàn bộ phiên

``` dart
Backpack.instance.sessionFlush("cart");
```

### Lấy tất cả dữ liệu phiên

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Truy cập instance Nylo

Backpack lưu trữ instance ứng dụng `Nylo`. Bạn có thể lấy nó bằng:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Kiểm tra xem instance Nylo đã được khởi tạo chưa:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Hàm trợ giúp

{{ config('app.name') }} cung cấp các hàm trợ giúp toàn cục cho các thao tác Backpack phổ biến:

| Hàm | Mô tả |
|----------|-------------|
| `backpackRead<T>(key)` | Đọc giá trị từ Backpack |
| `backpackSave(key, value)` | Lưu giá trị vào Backpack |
| `backpackDelete(key)` | Xóa giá trị khỏi Backpack |
| `backpackDeleteAll()` | Xóa tất cả giá trị (giữ lại các khóa framework) |
| `backpackNylo()` | Lấy instance Nylo từ Backpack |

### Ví dụ

``` dart
// Sử dụng các hàm trợ giúp
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Truy cập instance Nylo
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Tích hợp với NyStorage

Backpack tích hợp với `NyStorage` cho lưu trữ kết hợp vĩnh viễn + trong bộ nhớ:

``` dart
// Lưu vào cả NyStorage (vĩnh viễn) và Backpack (trong bộ nhớ)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Giờ có thể truy cập đồng bộ qua Backpack
String? token = Backpack.instance.read("auth_token");

// Khi xóa khỏi NyStorage, cũng xóa khỏi Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Mẫu này hữu ích cho dữ liệu như token xác thực cần cả lưu trữ vĩnh viễn và truy cập đồng bộ nhanh (ví dụ: trong HTTP interceptors).

<div id="examples"></div>

## Ví dụ

### Lưu trữ token xác thực cho các yêu cầu API

``` dart
// Trong auth interceptor của bạn
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Quản lý giỏ hàng dựa trên phiên

``` dart
// Thêm mặt hàng vào phiên giỏ hàng
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Đọc dữ liệu giỏ hàng
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Xóa giỏ hàng
Backpack.instance.sessionFlush("cart");
```

### Cờ tính năng nhanh

``` dart
// Lưu cờ tính năng vào Backpack để truy cập nhanh
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Kiểm tra một cờ tính năng
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
