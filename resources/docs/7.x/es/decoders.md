# Decoders

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- Uso
  - [Decoders de modelos](#model-decoders "Decoders de modelos")
  - [Decoders tipados](#typed-decoders "Decode y decoders()")
  - [OneOf](#one-of "Decodificar una respuesta que puede tener mas de una forma")
  - [Decoders de API](#api-decoders "Decoders de API")


<div id="introduction"></div>

## Introduccion

Los decoders son un concepto introducido en {{ config('app.name') }} que te permite decodificar datos en objetos o clases.
Probablemente usaras decoders cuando trabajes con la clase de [networking](/docs/7.x/networking) o si quieres usar el helper `api` en {{ config('app.name') }}.

> Por defecto, la ubicacion de los decoders es `lib/config/decoders.dart`

El archivo decoders.dart contendra dos variables:
- [modelDecoders](#model-decoders) - Maneja todos tus decoders de modelos
- [apiDecoders](#api-decoders) - Maneja todos tus decoders de API

<div id="model-decoders"></div>

## Decoders de modelos

Los decoders de modelos son nuevos en {{ config('app.name') }}, proporcionan una forma de transformar los datos de respuesta en representaciones de modelos.

El metodo helper `network()` usara la variable `modelDecoders` dentro de tu archivo <b>config/decoders.dart</b> para determinar que decoder usar.

Aqui tienes un ejemplo.

Asi es como el helper `network` usa modelDecoders.

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

El metodo `fetchUsers` decodificara automaticamente la respuesta de la solicitud en un `User`.

Como funciona esto?

Tienes una clase `User` como la siguiente.

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

Puedes ver que esta clase tiene un metodo `fromJson` que nos proporciona una forma de inicializar la clase.

Podemos inicializar esta clase llamando al siguiente metodo.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Ahora, para configurar nuestros decoders, debemos hacer lo siguiente.

<b>Archivo:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

En el archivo modelDecoders, necesitamos proporcionar el `Type` como clave y manejar la transformacion en el valor como en el ejemplo anterior.

El argumento `data` contendra la respuesta de la solicitud API.

<div id="typed-decoders"></div>

## Decoders tipados

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` construye tu mapa `modelDecoders` a partir de una lista. `Decode(User.fromJson)` decodifica un `User`, y tambien un `List<User>`, sin una entrada propia:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Usa `from` para leer el modelo desde dentro de un cuerpo envuelto, como `{"data": {...}}`. Una clave con puntos como `'data.user'` lee a mayor profundidad, y para una lista, la propia lista se lee desde `from`.

`Decode` recibe un `fromJson` que acepta `dynamic` o `Map<String, dynamic>`. Para una closure, tipa su parametro:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Los decoders con closure en un mapa `{...}`, como `User: (data) => User.fromJson(data)`, siguen funcionando. Ambos son un `NyDecoder`, la clase de la que extienden `Decode` y `OneOf`. Un `NyDecoder<T>` personalizado implementa `decode(json, response, decoders)` y ve el `NyResponse` completo, por lo que puede leer el codigo de estado o los encabezados.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` decodifica una respuesta que puede tomar mas de una forma. Registralo bajo el tipo padre compartido y elige la forma segun un campo del cuerpo, segun el codigo de estado, o con tus propias condiciones:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

Un `OneOf` tambien decodifica cuerpos 4xx y 5xx, y un `List<T>` elemento por elemento. Una forma que no reconoce deja los datos de la respuesta en null.

| Constructor | Elige la forma segun |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | El valor de `key` en el cuerpo. Una clave con puntos como `'data.type'` lee un campo anidado |
| `OneOf.byStatus(types, otherwise:)` | El codigo de estado. Una clave es un codigo como `404`, o un patron como `'4xx'` o `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Tus propias condiciones, cada una recibe el cuerpo y la respuesta. Gana la primera que devuelve true |
| `OneOf(resolve)` | Una funcion que recibe el cuerpo y la respuesta y devuelve el valor, o null |

Por codigo de estado:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Gana un codigo exacto, luego el patron con menos `x`.

Con tus propias condiciones:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Con una funcion:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Lee la forma de una respuesta con `dataAs<X>()` y `dataIs<X>()`; consulta [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro crea el padre, sus subtipos y la entrada `OneOf.byKey` por ti:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Consulta [Crear un modelo OneOf](/docs/{{$version}}/metro#make-a-one-of-model).

<div id="api-decoders"></div>

## Decoders de API

Los decoders de API se usan al llamar al metodo helper `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

El helper `api` hara coincidir el servicio API correcto usando genericos, por lo que puedes llamar al siguiente helper para acceder a tu servicio.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Antes de usar el helper `api`, primero necesitaras agregar tu servicio API en <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
