# Decoder

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Penggunaan
  - [Decoder Model](#model-decoders "Decoder Model")
  - [Decoder Bertipe](#typed-decoders "Decode dan decoders()")
  - [OneOf](#one-of "Mendekode respons yang bisa memiliki lebih dari satu bentuk")
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

<div id="typed-decoders"></div>

## Decoder Bertipe

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` membangun map `modelDecoders` Anda dari sebuah list. `Decode(User.fromJson)` mendekode sebuah `User`, dan juga `List<User>`, tanpa entri tersendiri:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Gunakan `from` untuk membaca model dari dalam body yang dibungkus, seperti `{"data": {...}}`. Kunci bertitik seperti `'data.user'` membaca lebih dalam, dan untuk list, list itu sendiri dibaca dari `from`.

`Decode` menerima `fromJson` yang menerima `dynamic` atau `Map<String, dynamic>`. Untuk closure, tentukan tipe parameternya:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Decoder closure dalam map `{...}`, seperti `User: (data) => User.fromJson(data)`, tetap berfungsi. Keduanya adalah `NyDecoder`, kelas yang di-extend oleh `Decode` dan `OneOf`. Sebuah `NyDecoder<T>` kustom mengimplementasikan `decode(json, response, decoders)` dan melihat seluruh `NyResponse`, sehingga dapat membaca kode status atau header.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Persyaratan" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` mendekode respons yang bisa memiliki lebih dari satu bentuk. Daftarkan di bawah tipe induk yang sama, lalu pilih bentuknya berdasarkan sebuah field di body, kode status, atau kondisi Anda sendiri:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` juga mendekode body 4xx dan 5xx, serta item `List<T>` satu per satu. Bentuk yang tidak dikenali membuat data respons bernilai null.

| Constructor | Memilih bentuk berdasarkan |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | Nilai `key` di body. Kunci bertitik seperti `'data.type'` membaca field bersarang |
| `OneOf.byStatus(types, otherwise:)` | Kode status. Kuncinya berupa kode seperti `404`, atau pola seperti `'4xx'` atau `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Kondisi Anda sendiri, masing-masing menerima body dan respons. Yang pertama mengembalikan true akan dipakai |
| `OneOf(resolve)` | Fungsi yang menerima body dan respons lalu mengembalikan nilai, atau null |

Berdasarkan kode status:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Kode yang persis sama diutamakan, lalu pola dengan `x` paling sedikit.

Dengan kondisi Anda sendiri:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Dengan sebuah fungsi:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Baca bentuk dari sebuah respons dengan `dataAs<X>()` dan `dataIs<X>()`; lihat [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro membuatkan induk, subtipenya, dan entri `OneOf.byKey` untuk Anda:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Lihat [Membuat model OneOf](/docs/{{$version}}/metro#make-a-one-of-model).

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
