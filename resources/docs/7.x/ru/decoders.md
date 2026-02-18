# Decoders

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Использование
  - [Декодеры моделей](#model-decoders "Декодеры моделей")
  - [Декодеры API](#api-decoders "Декодеры API")


<div id="introduction"></div>

## Введение

Декодеры -- это концепция, представленная в {{ config('app.name') }}, которая позволяет декодировать данные в объекты или классы.
Вы, скорее всего, будете использовать декодеры при работе с классом [networking](/docs/7.x/networking) или если хотите использовать помощник `api` в {{ config('app.name') }}.

> По умолчанию файл декодеров расположен в `lib/config/decoders.dart`

Файл decoders.dart содержит две переменные:
- [modelDecoders](#model-decoders) - Обрабатывает все декодеры моделей
- [apiDecoders](#api-decoders) - Обрабатывает все декодеры API

<div id="model-decoders"></div>

## Декодеры моделей

Декодеры моделей -- это нововведение в {{ config('app.name') }}, они предоставляют способ преобразования данных из ответов в представления моделей.

Вспомогательный метод `network()` использует переменную `modelDecoders` внутри файла <b>config/decoders.dart</b> для определения используемого декодера.

Вот пример.

Вот как помощник `network` использует modelDecoders.

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

Метод `fetchUsers` автоматически декодирует данные из запроса в объект `User`.

Как это работает?

У вас есть класс `User`, как показано ниже.

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

Из приведённого выше примера видно, что этот класс имеет метод `fromJson`, который предоставляет способ инициализации класса.

Мы можем инициализировать этот класс, вызвав следующий метод.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Теперь, чтобы настроить декодеры, необходимо сделать следующее.

<b>Файл:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

В файле modelDecoders нужно указать `Type` в качестве ключа и обработать преобразование в значении, как в примере выше.

Аргумент `data` будет содержать данные из ответа API-запроса.

<div id="api-decoders"></div>

## Декодеры API

Декодеры API используются при вызове вспомогательного метода `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

Помощник `api` сопоставляет нужный API-сервис с помощью generics, поэтому вы можете вызвать следующий помощник для доступа к вашему сервису.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Перед использованием помощника `api` необходимо сначала добавить ваш API-сервис в <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
