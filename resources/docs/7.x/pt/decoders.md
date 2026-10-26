# Decoders

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- Uso
  - [Model Decoders](#model-decoders "Model Decoders")
  - [Decoders Tipados](#typed-decoders "Decode e decoders()")
  - [OneOf](#one-of "Decodificando uma resposta que pode ter mais de um formato")
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

<div id="typed-decoders"></div>

## Decoders Tipados

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` constrói seu mapa `modelDecoders` a partir de uma lista. `Decode(User.fromJson)` decodifica um `User`, e também uma `List<User>`, sem precisar de uma entrada própria:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Use `from` para ler o modelo de dentro de um corpo encapsulado, como `{"data": {...}}`. Uma chave com pontos, como `'data.user'`, lê mais fundo e, para uma lista, a própria lista é lida de `from`.

`Decode` recebe um `fromJson` que aceita `dynamic` ou `Map<String, dynamic>`. Para uma closure, tipifique seu parâmetro:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Decoders em closure dentro de um mapa `{...}`, como `User: (data) => User.fromJson(data)`, continuam funcionando. Ambos são um `NyDecoder`, a classe que `Decode` e `OneOf` estendem. Um `NyDecoder<T>` personalizado implementa `decode(json, response, decoders)` e enxerga o `NyResponse` inteiro, então pode ler o código de status ou os headers.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` decodifica uma resposta que pode ter mais de um formato. Registre-o sob o tipo pai compartilhado e escolha o formato por um campo do corpo, pelo código de status ou com suas próprias condições:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

Um `OneOf` também decodifica corpos 4xx e 5xx, e uma `List<T>` item por item. Um formato que ele não reconhece deixa os dados da resposta nulos.

| Construtor | Escolhe o formato por |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | O valor de `key` no corpo. Uma chave com pontos, como `'data.type'`, lê um campo aninhado |
| `OneOf.byStatus(types, otherwise:)` | O código de status. Uma chave é um código como `404`, ou um padrão como `'4xx'` ou `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Suas próprias condições, cada uma recebendo o corpo e a resposta. A primeira que retornar true vence |
| `OneOf(resolve)` | Uma função que recebe o corpo e a resposta e retorna o valor, ou null |

Por código de status:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Um código exato vence, depois o padrão com menos `x`.

Com suas próprias condições:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Com uma função:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Leia o formato de uma resposta com `dataAs<X>()` e `dataIs<X>()`; veja [NyResponse](/docs/{{$version}}/networking#ny-response).

O Metro cria o pai, seus subtipos e a entrada `OneOf.byKey` para você:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Veja [Make a OneOf model](/docs/{{$version}}/metro#make-a-one-of-model).

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
