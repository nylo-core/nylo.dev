# Decoders

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- Sử dụng
  - [Model Decoders](#model-decoders "Model Decoders")
  - [Typed Decoders](#typed-decoders "Decode và decoders()")
  - [OneOf](#one-of "Giải mã phản hồi có thể có nhiều hình dạng")
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

<div id="typed-decoders"></div>

## Typed Decoders

<!-- uncertain: new Nylo-specific terms "Typed Decoders", "Decode" — not seen in existing locale file -->
<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` xây dựng map `modelDecoders` của bạn từ một danh sách. `Decode(User.fromJson)` giải mã một `User`, và cả `List<User>`, mà không cần mục riêng:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Dùng `from` để đọc model từ bên trong một body được bọc, như `{"data": {...}}`. Một khóa có dấu chấm như `'data.user'` đọc sâu hơn, và với danh sách, chính danh sách được đọc từ `from`.

`Decode` nhận một `fromJson` chấp nhận `dynamic` hoặc `Map<String, dynamic>`. Với closure, hãy khai báo kiểu cho tham số của nó:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Các closure decoder trong map `{...}`, như `User: (data) => User.fromJson(data)`, vẫn hoạt động. Cả hai đều là một `NyDecoder`, lớp mà `Decode` và `OneOf` kế thừa. Một `NyDecoder<T>` tùy chỉnh triển khai `decode(json, response, decoders)` và thấy toàn bộ `NyResponse`, nên nó có thể đọc mã trạng thái hoặc header.

<div id="one-of"></div>

## OneOf

<!-- uncertain: new Nylo-specific term "OneOf" — not seen in existing locale file -->
<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` giải mã một phản hồi có thể có nhiều hình dạng. Đăng ký nó dưới kiểu cha dùng chung, và chọn hình dạng theo một trường trong body, theo mã trạng thái, hoặc bằng các điều kiện của riêng bạn:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

Một `OneOf` cũng giải mã body 4xx và 5xx, và từng phần tử của `List<T>`. Một hình dạng mà nó không nhận ra sẽ để dữ liệu phản hồi là null.

| Constructor | Chọn hình dạng theo |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | Giá trị của `key` trong body. Một khóa có dấu chấm như `'data.type'` đọc một trường lồng nhau |
| `OneOf.byStatus(types, otherwise:)` | Mã trạng thái. Một khóa là một mã như `404`, hoặc một mẫu như `'4xx'` hoặc `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Các điều kiện của riêng bạn, mỗi điều kiện nhận body và response. Điều kiện đầu tiên trả về true sẽ thắng |
| `OneOf(resolve)` | Một hàm nhận body và response rồi trả về giá trị, hoặc null |

Theo mã trạng thái:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Mã chính xác thắng, sau đó đến mẫu có ít chữ `x` nhất.

Với các điều kiện của riêng bạn:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Với một hàm:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Đọc hình dạng từ một response bằng `dataAs<X>()` và `dataIs<X>()`; xem [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro tạo kiểu cha, các kiểu con của nó và mục `OneOf.byKey` cho bạn:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Xem [Tạo model OneOf](/docs/{{$version}}/metro#make-a-one-of-model).

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
