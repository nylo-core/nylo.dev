# Decoders

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- Sử dụng
  - [Model Decoders](#model-decoders "Model Decoders")
  - [API Decoders](#api-decoders "API Decoders")


<div id="introduction"></div>

## Giới thiệu

Decoders là một khái niệm mới được giới thiệu trong {{ config('app.name') }} cho phép bạn giải mã dữ liệu thành các đối tượng hoặc class.
Bạn có thể sẽ sử dụng decoders khi làm việc với class [networking](/docs/7.x/networking) hoặc nếu bạn muốn sử dụng helper `api` trong {{ config('app.name') }}.

> Theo mặc định, vị trí của decoders là `lib/config/decoders.dart`

File decoders.dart sẽ chứa hai biến:
- [modelDecoders](#model-decoders) - Xử lý tất cả model decoders của bạn
- [apiDecoders](#api-decoders) - Xử lý tất cả API decoders của bạn

<div id="model-decoders"></div>

## Model decoders

Model decoders là tính năng mới trong {{ config('app.name') }}, chúng cung cấp cách để bạn chuyển đổi dữ liệu payload thành biểu diễn model.

Phương thức helper `network()` sẽ sử dụng biến `modelDecoders` bên trong file <b>config/decoders.dart</b> để xác định decoder nào sẽ dùng.

Đây là một ví dụ.

Đây là cách helper `network` sử dụng modelDecoders.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
        : super(buildContext, decoders: modelDecoders);

  @override
  String get baseUrl => "https://jsonplaceholder.typicode.com";

  Future<User?> fetchUsers() async {
    return await network<User>(
        request: (request) => request.get("/users"),
    );
  }
...
```

Phương thức `fetchUsers` sẽ tự động giải mã payload từ yêu cầu thành `User`.

Điều này hoạt động như thế nào?

Bạn có class `User` như bên dưới.

```dart
class User {
  String? name;
  String? email;

  User.fromJson(dynamic data) {
    this.name = data['name'];
    this.email = data['email'];
  }

  toJson()  => {
    "name": this.name,
    "email": this.email
  };
}
```

Bạn có thể thấy từ ví dụ trên rằng class này có phương thức `fromJson` cung cấp cho chúng ta cách khởi tạo class.

Chúng ta có thể khởi tạo class này bằng cách gọi phương thức bên dưới.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Bây giờ, để thiết lập decoders, chúng ta cần làm như sau.

<b>File:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

Trong file modelDecoders, chúng ta cần cung cấp `Type` làm khóa và xử lý chuyển đổi trong giá trị như ví dụ trên.

Tham số `data` sẽ chứa payload từ yêu cầu API.

<div id="api-decoders"></div>

## API decoders

API decoders được sử dụng khi gọi phương thức helper `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

Helper `api` sẽ khớp đúng API Service sử dụng generics, vì vậy bạn có thể gọi helper bên dưới để truy cập service của mình.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Trước khi sử dụng helper `api`, bạn cần thêm API Service vào <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
