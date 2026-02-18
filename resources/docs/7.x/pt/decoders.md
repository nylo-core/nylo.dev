# Decoders

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- Uso
  - [Model Decoders](#model-decoders "Model Decoders")
  - [API Decoders](#api-decoders "API Decoders")


<div id="introduction"></div>

## Introdução

Decoders são um conceito introduzido no {{ config('app.name') }} que permite decodificar dados em objetos ou classes.
Você provavelmente usará decoders ao trabalhar com a classe de [networking](/docs/7.x/networking) ou se quiser usar o helper `api` no {{ config('app.name') }}.

> Por padrão, a localização dos decoders é `lib/config/decoders.dart`

O arquivo decoders.dart conterá duas variáveis:
- [modelDecoders](#model-decoders) - Gerencia todos os seus model decoders
- [apiDecoders](#api-decoders) - Gerencia todos os seus API decoders

<div id="model-decoders"></div>

## Model decoders

Model decoders são uma novidade no {{ config('app.name') }}, eles fornecem uma maneira de transformar payloads de dados em representações de modelos.

O método helper `network()` usará a variável `modelDecoders` dentro do seu arquivo <b>config/decoders.dart</b> para determinar qual decoder usar.

Aqui está um exemplo.

Veja como o helper `network` usa os modelDecoders.

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

O método `fetchUsers` decodificará automaticamente o payload da requisição em um `User`.

Como isso funciona?

Você tem uma classe `User` como a abaixo.

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

Você pode ver acima que esta classe tem um método `fromJson` que nos fornece uma maneira de inicializar a classe.

Podemos inicializar esta classe chamando o método abaixo.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Agora, para configurar nossos decoders, precisamos fazer o seguinte.

<b>Arquivo:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

No arquivo modelDecoders, precisamos fornecer o `Type` como chave e lidar com a transformação no valor, como no exemplo acima.

O argumento `data` conterá o payload da requisição da API.

<div id="api-decoders"></div>

## API decoders

API decoders são usados ao chamar o método helper `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

O helper `api` encontrará o API Service correto usando generics, então você pode chamar o helper abaixo para acessar seu serviço.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Antes de usar o helper `api`, você precisará primeiro adicionar seu API Service em <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
