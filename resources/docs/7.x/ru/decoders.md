# Decoders

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Использование
  - [Декодеры моделей](#model-decoders "Декодеры моделей")
  - [Типизированные декодеры](#typed-decoders "Decode и decoders()")
  - [OneOf](#one-of "Декодирование ответа, который может иметь несколько форм")
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

<div id="typed-decoders"></div>

## Типизированные декодеры

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` строит вашу карту `modelDecoders` из списка. `Decode(User.fromJson)` декодирует `User`, а также `List<User>`, без отдельной записи:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Используйте `from`, чтобы прочитать модель изнутри обёрнутого тела, например `{"data": {...}}`. Ключ с точками, такой как `'data.user'`, читает глубже, а для списка сам список читается из `from`.

`Decode` принимает `fromJson`, который принимает `dynamic` или `Map<String, dynamic>`. Для замыкания укажите тип его параметра:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Декодеры-замыкания в карте `{...}`, например `User: (data) => User.fromJson(data)`, продолжают работать. Оба являются `NyDecoder` -- классом, который расширяют `Decode` и `OneOf`. Собственный `NyDecoder<T>` реализует `decode(json, response, decoders)` и видит весь `NyResponse`, поэтому может читать код состояния или заголовки.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` декодирует ответ, который может иметь несколько форм. Зарегистрируйте его под общим родительским типом и выбирайте форму по полю в теле, по коду состояния или с помощью собственных условий:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` также декодирует тела ответов 4xx и 5xx, а `List<T>` -- элемент за элементом. Если форма не распознана, данные ответа остаются null.

| Конструктор | Выбирает форму по |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | Значению `key` в теле. Ключ с точками, такой как `'data.type'`, читает вложенное поле |
| `OneOf.byStatus(types, otherwise:)` | Коду состояния. Ключ -- это код, например `404`, или шаблон, например `'4xx'` или `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Вашим собственным условиям, каждое принимает тело и ответ. Побеждает первое, вернувшее true |
| `OneOf(resolve)` | Функции, которая принимает тело и ответ и возвращает значение или null |

По коду состояния:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Побеждает точный код, затем шаблон с наименьшим числом `x`.

С собственными условиями:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

С функцией:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Читайте форму из ответа с помощью `dataAs<X>()` и `dataIs<X>()`; см. [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro создаёт родителя, его подтипы и запись `OneOf.byKey` за вас:

```bash
metro make:model login_result --one-of="success,two_factor"
```

См. [Make a OneOf model](/docs/{{$version}}/metro#make-a-one-of-model).

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
