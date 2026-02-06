# Kod &#199;&#246;z&#252;c&#252;ler

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- Kullan&#305;m
  - [Model Kod &#199;&#246;z&#252;c&#252;ler](#model-decoders "Model Kod &#199;&#246;z&#252;c&#252;ler")
  - [API Kod &#199;&#246;z&#252;c&#252;ler](#api-decoders "API Kod &#199;&#246;z&#252;c&#252;ler")


<div id="introduction"></div>

## Giri&#351;

Kod &#231;&#246;z&#252;c&#252;ler, {{ config('app.name') }} taraf&#305;ndan tan&#305;t&#305;lan ve verileri nesnelere veya s&#305;n&#305;flara d&#246;n&#252;&#351;t&#252;rmenize olanak sa&#287;layan bir kavramd&#305;r.
Kod &#231;&#246;z&#252;c&#252;leri b&#252;y&#252;k olas&#305;l&#305;kla [a&#287; i&#351;lemleri](/docs/7.x/networking) s&#305;n&#305;f&#305;yla &#231;al&#305;&#351;&#305;rken veya {{ config('app.name') }}'daki `api` yard&#305;mc&#305;s&#305;n&#305; kullanmak istedi&#287;inizde kullanacaks&#305;n&#305;z.

> Varsay&#305;lan olarak, kod &#231;&#246;z&#252;c&#252;lerin konumu `lib/config/decoders.dart` dosyas&#305;d&#305;r

decoders.dart dosyas&#305; iki de&#287;i&#351;ken i&#231;erecektir:
- [modelDecoders](#model-decoders) - T&#252;m model kod &#231;&#246;z&#252;c&#252;lerinizi y&#246;netir
- [apiDecoders](#api-decoders) - T&#252;m API kod &#231;&#246;z&#252;c&#252;lerinizi y&#246;netir

<div id="model-decoders"></div>

## Model Kod &#199;&#246;z&#252;c&#252;ler

Model kod &#231;&#246;z&#252;c&#252;ler {{ config('app.name') }}'da yenidir, veri y&#252;klerini model temsillerine d&#246;n&#252;&#351;t&#252;rmenin bir yolunu sa&#287;larlar.

`network()` yard&#305;mc&#305; metodu, hangi kod &#231;&#246;z&#252;c&#252;n&#252;n kullan&#305;laca&#287;&#305;n&#305; belirlemek i&#231;in <b>config/decoders.dart</b> dosyan&#305;zdaki `modelDecoders` de&#287;i&#351;kenini kullanacakt&#305;r.

&#304;&#351;te bir &#246;rnek.

`network` yard&#305;mc&#305;s&#305;n&#305;n modelDecoders'&#305; nas&#305;l kulland&#305;&#287;&#305; a&#351;a&#287;&#305;dad&#305;r.

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

`fetchUsers` metodu, istekten gelen y&#252;k&#252; otomatik olarak bir `User` nesnesine d&#246;n&#252;&#351;t&#252;recektir.

Bu nas&#305;l &#231;al&#305;&#351;&#305;r?

A&#351;a&#287;&#305;daki gibi bir `User` s&#305;n&#305;f&#305;n&#305;z var.

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

Yukar&#305;dan g&#246;rebilece&#287;iniz gibi bu s&#305;n&#305;f&#305;n s&#305;n&#305;f&#305; ba&#351;latmam&#305;z&#305; sa&#287;layan bir `fromJson` metodu vard&#305;r.

Bu s&#305;n&#305;f&#305; a&#351;a&#287;&#305;daki metodu &#231;a&#287;&#305;rarak ba&#351;latabiliriz.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

&#350;imdi, kod &#231;&#246;z&#252;c&#252;lerimizi kurmak i&#231;in a&#351;a&#287;&#305;dakini yapmam&#305;z gerekiyor.

<b>Dosya:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

modelDecoders dosyas&#305;nda, anahtar olarak `Type` sa&#287;lamam&#305;z ve de&#287;erde yukar&#305;daki &#246;rnekteki gibi d&#246;n&#252;&#351;&#252;m&#252; yapmam&#305;z gerekir.

`data` arg&#252;man&#305;, API iste&#287;inden gelen y&#252;k&#252; i&#231;erecektir.

<div id="api-decoders"></div>

## API Kod &#199;&#246;z&#252;c&#252;ler

API kod &#231;&#246;z&#252;c&#252;ler, `api` yard&#305;mc&#305; metodu &#231;a&#287;r&#305;l&#305;rken kullan&#305;l&#305;r.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

`api` yard&#305;mc&#305;s&#305;, generic'ler kullanarak do&#287;ru API Servisini e&#351;le&#351;tirecektir, b&#246;ylece servisinize eri&#351;mek i&#231;in a&#351;a&#287;&#305;daki yard&#305;mc&#305;y&#305; &#231;a&#287;&#305;rabilirsiniz.

```dart
await api<MyService>((request) => request.callMyMethod());
```

`api` yard&#305;mc&#305;s&#305;n&#305; kullanmadan &#246;nce, API Servisinizi <b>lib/config/decoders.dart > apiDecoders</b> i&#231;ine eklemeniz gerekecektir.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
