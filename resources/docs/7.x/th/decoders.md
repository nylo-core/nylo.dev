# Decoders

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- การใช้งาน
  - [Model Decoders](#model-decoders "Model Decoders")
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
