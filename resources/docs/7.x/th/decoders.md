# Decoders

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- การใช้งาน
  - [Model Decoders](#model-decoders "Model Decoders")
  - [Typed Decoders](#typed-decoders "Decode และ decoders()")
  - [OneOf](#one-of "การถอดรหัส response ที่มีได้หลายรูปแบบ")
  - [API Decoders](#api-decoders "API Decoders")


<div id="introduction"></div>

## บทนำ

Decoder เป็นแนวคิดใหม่ที่นำเสนอใน {{ config('app.name') }} ซึ่งช่วยให้คุณถอดรหัสข้อมูลเป็นอ็อบเจกต์หรือคลาสได้
คุณจะใช้ decoder เมื่อทำงานกับคลาส [networking](/docs/7.x/networking) หรือถ้าคุณต้องการใช้ตัวช่วย `api` ใน {{ config('app.name') }}

> โดยค่าเริ่มต้น ตำแหน่งของ decoder อยู่ที่ `lib/config/decoders.dart`

ไฟล์ decoders.dart จะมีตัวแปรสองตัว:
- [modelDecoders](#model-decoders) - จัดการ model decoder ทั้งหมดของคุณ
- [apiDecoders](#api-decoders) - จัดการ API decoder ทั้งหมดของคุณ

<div id="model-decoders"></div>

## Model decoders

Model decoder เป็นสิ่งใหม่ใน {{ config('app.name') }} ซึ่งให้วิธีสำหรับคุณในการแปลงข้อมูล payload เป็นรูปแบบ model

เมธอดตัวช่วย `network()` จะใช้ตัวแปร `modelDecoders` ในไฟล์ <b>config/decoders.dart</b> ของคุณเพื่อกำหนดว่าจะใช้ decoder ตัวไหน

นี่คือตัวอย่าง

นี่คือวิธีที่ตัวช่วย `network` ใช้ modelDecoders

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

เมธอด `fetchUsers` จะถอดรหัส payload จาก request เป็น `User` โดยอัตโนมัติ

วิธีนี้ทำงานอย่างไร?

คุณมีคลาส `User` ดังนี้

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

จะเห็นว่าคลาสนี้มีเมธอด `fromJson` ซึ่งให้วิธีในการเริ่มต้นคลาส

เราสามารถเริ่มต้นคลาสนี้ได้โดยเรียกเมธอดด้านล่าง

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

ตอนนี้ เพื่อตั้งค่า decoder ของเรา เราต้องทำดังต่อไปนี้

<b>File:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

ในไฟล์ modelDecoders เราต้องให้ `Type` เป็น key และจัดการการแปลงใน value ดังตัวอย่างด้านบน

อาร์กิวเมนต์ `data` จะมี payload จาก API request

<div id="typed-decoders"></div>

## Typed Decoders

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` สร้างแมป `modelDecoders` ของคุณจากลิสต์ `Decode(User.fromJson)` จะถอดรหัส `User` และ `List<User>` ด้วย โดยไม่ต้องมีรายการของตัวเอง:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

ใช้ `from` เพื่ออ่านโมเดลจากภายใน body ที่ห่อไว้ เช่น `{"data": {...}}` คีย์ที่คั่นด้วยจุด เช่น `'data.user'` จะอ่านลึกลงไปอีก และสำหรับลิสต์ ตัวลิสต์เองจะถูกอ่านจาก `from`

`Decode` รับ `fromJson` ที่ยอมรับ `dynamic` หรือ `Map<String, dynamic>` สำหรับ closure ให้ระบุชนิดของพารามิเตอร์:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

closure decoder ในแมป `{...}` เช่น `User: (data) => User.fromJson(data)` ยังคงใช้งานได้ ทั้งสองแบบเป็น `NyDecoder` ซึ่งเป็นคลาสที่ `Decode` และ `OneOf` สืบทอดมา `NyDecoder<T>` แบบกำหนดเองจะ implement `decode(json, response, decoders)` และเห็น `NyResponse` ทั้งหมด จึงอ่านรหัสสถานะหรือ header ได้

<div id="one-of"></div>

## OneOf

<x-doc-strip label="ข้อกำหนด" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` ถอดรหัส response ที่มีได้หลายรูปแบบ ลงทะเบียนไว้ภายใต้ชนิดแม่ที่ใช้ร่วมกัน แล้วเลือกรูปแบบจากฟิลด์ใน body จากรหัสสถานะ หรือจากเงื่อนไขของคุณเอง:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` ถอดรหัส body ของ 4xx และ 5xx ได้ด้วย และถอดรหัส `List<T>` ทีละรายการ รูปแบบที่ไม่รู้จักจะทำให้ข้อมูลของ response เป็น null

| Constructor | เลือกรูปแบบโดย |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | ค่าของ `key` ใน body คีย์ที่คั่นด้วยจุด เช่น `'data.type'` จะอ่านฟิลด์ที่ซ้อนอยู่ |
| `OneOf.byStatus(types, otherwise:)` | รหัสสถานะ คีย์คือรหัส เช่น `404` หรือรูปแบบ เช่น `'4xx'` หรือ `'40x'` |
| `OneOf.byJson(types, otherwise:)` | เงื่อนไขของคุณเอง แต่ละเงื่อนไขรับ body และ response เงื่อนไขแรกที่คืนค่า true จะถูกใช้ |
| `OneOf(resolve)` | ฟังก์ชันที่รับ body และ response แล้วคืนค่า หรือ null |

ตามรหัสสถานะ:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

รหัสที่ตรงเป๊ะมาก่อน จากนั้นเป็นรูปแบบที่มี `x` น้อยที่สุด

ด้วยเงื่อนไขของคุณเอง:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

ด้วยฟังก์ชัน:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

อ่านรูปแบบจาก response ด้วย `dataAs<X>()` และ `dataIs<X>()` ดู [NyResponse](/docs/{{$version}}/networking#ny-response)

Metro สร้างชนิดแม่ ชนิดย่อย และรายการ `OneOf.byKey` ให้คุณ:

```bash
metro make:model login_result --one-of="success,two_factor"
```

ดู [สร้างโมเดล OneOf](/docs/{{$version}}/metro#make-a-one-of-model)

<div id="api-decoders"></div>

## API decoders

API decoder ใช้เมื่อเรียกเมธอดตัวช่วย `api`

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

ตัวช่วย `api` จะจับคู่ API Service ที่ถูกต้องโดยใช้ generics ดังนั้นคุณสามารถเรียกตัวช่วยด้านล่างเพื่อเข้าถึงบริการของคุณ

```dart
await api<MyService>((request) => request.callMyMethod());
```

ก่อนใช้ตัวช่วย `api` คุณจะต้องเพิ่ม API Service ของคุณลงใน <b>lib/config/decoders.dart > apiDecoders</b> ก่อน

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
