# Decoders

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- Uso
  - [Decoders de modelos](#model-decoders "Decoders de modelos")
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
