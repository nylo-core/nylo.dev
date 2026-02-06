# Decoder

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Penggunaan
  - [Decoder Model](#model-decoders "Decoder Model")
  - [Decoder API](#api-decoders "Decoder API")


<div id="introduction"></div>

## Pengantar

Decoder adalah konsep baru yang diperkenalkan di {{ config('app.name') }} yang memungkinkan Anda mendekode data menjadi objek atau kelas.
Anda kemungkinan akan menggunakan decoder saat berurusan dengan kelas [networking](/docs/7.x/networking) atau jika Anda ingin menggunakan helper `api` di {{ config('app.name') }}.

> Secara default, lokasi untuk decoder ada di `lib/config/decoders.dart`

File decoders.dart akan berisi dua variabel:
- [modelDecoders](#model-decoders) - Menangani semua decoder model Anda
- [apiDecoders](#api-decoders) - Menangani semua decoder API Anda

<div id="model-decoders"></div>

## Decoder Model

Decoder model adalah fitur baru di {{ config('app.name') }}, mereka menyediakan cara bagi Anda untuk mengubah payload data menjadi representasi model.

Method helper `network()` akan menggunakan variabel `modelDecoders` di dalam file <b>config/decoders.dart</b> Anda untuk menentukan decoder mana yang akan digunakan.

Berikut contohnya.

Berikut cara helper `network` menggunakan modelDecoders.

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

Method `fetchUsers` akan secara otomatis mendekode payload dari permintaan menjadi `User`.

Bagaimana cara kerjanya?

Anda memiliki kelas `User` seperti di bawah ini.

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

Anda dapat melihat dari kelas di atas bahwa kelas ini memiliki method `fromJson` yang menyediakan cara untuk menginisialisasi kelas.

Kita dapat menginisialisasi kelas ini dengan memanggil method di bawah ini.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Sekarang, untuk mengatur decoder kita, kita harus melakukan hal berikut.

<b>File:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

Di file modelDecoders, kita perlu menyediakan `Type` sebagai kunci dan menangani transformasi di nilai seperti contoh di atas.

Argumen `data` akan berisi payload dari permintaan API.

<div id="api-decoders"></div>

## Decoder API

Decoder API digunakan saat memanggil method helper `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

Helper `api` akan mencocokkan API Service yang benar menggunakan generics, sehingga Anda dapat memanggil helper di bawah ini untuk mengakses service Anda.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Sebelum menggunakan helper `api`, Anda perlu terlebih dahulu menambahkan API Service Anda ke <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
