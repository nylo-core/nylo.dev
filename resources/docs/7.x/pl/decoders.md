# Decoders

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- Uzycie
  - [Dekodery modeli](#model-decoders "Dekodery modeli")
  - [Dekodery typowane](#typed-decoders "Decode i decoders()")
  - [OneOf](#one-of "Dekodowanie odpowiedzi, ktora moze miec wiecej niz jeden ksztalt")
  - [Dekodery API](#api-decoders "Dekodery API")


<div id="introduction"></div>

## Wprowadzenie

Dekodery to nowa koncepcja wprowadzona w {{ config('app.name') }}, ktora umozliwia dekodowanie danych na obiekty lub klasy.
Prawdopodobnie bedziesz uzywac dekoderow podczas pracy z klasa [networking](/docs/7.x/networking) lub jesli chcesz uzywac helpera `api` w {{ config('app.name') }}.

> Domyslnie dekodery znajduja sie w `lib/config/decoders.dart`

Plik decoders.dart zawiera dwie zmienne:
- [modelDecoders](#model-decoders) - Obsluguje wszystkie dekodery modeli
- [apiDecoders](#api-decoders) - Obsluguje wszystkie dekodery API

<div id="model-decoders"></div>

## Dekodery modeli

Dekodery modeli sa nowoscia w {{ config('app.name') }}, zapewniaja sposob na przeksztalcanie danych z odpowiedzi w reprezentacje modeli.

Metoda pomocnicza `network()` uzywa zmiennej `modelDecoders` z pliku <b>config/decoders.dart</b> do okreslenia, ktorego dekodera uzyc.

Oto przyklad.

Oto jak helper `network` uzywa modelDecoders.

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

Metoda `fetchUsers` automatycznie zdekoduje dane z zapytania na obiekt `User`.

Jak to dziala?

Masz klase `User` jak ponizej.

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

Jak widac powyzej, ta klasa posiada metode `fromJson`, ktora umozliwia inicjalizacje klasy.

Mozemy zainicjalizowac te klase, wywolujac ponizszy kod.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Teraz, aby skonfigurowac nasze dekodery, musimy wykonac nastepujace kroki.

<b>Plik:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

W pliku modelDecoders musimy podac `Type` jako klucz i obsluzyc przeksztalcenie w wartosci, jak w powyzszym przykladzie.

Argument `data` bedzie zawieral dane z zapytania API.

<div id="typed-decoders"></div>

## Dekodery typowane

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` buduje mape `modelDecoders` z listy. `Decode(User.fromJson)` dekoduje `User`, a takze `List<User>`, bez osobnego wpisu:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Uzyj `from`, aby odczytac model z wnetrza opakowanego body, np. `{"data": {...}}`. Klucz z kropka, taki jak `'data.user'`, czyta glebiej, a w przypadku listy sama lista jest odczytywana z `from`.

`Decode` przyjmuje `fromJson`, ktory akceptuje `dynamic` lub `Map<String, dynamic>`. W przypadku closure okresl typ jego parametru:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Dekodery w postaci closure w mapie `{...}`, takie jak `User: (data) => User.fromJson(data)`, nadal dzialaja. Oba sa `NyDecoder`, klasa, ktora rozszerzaja `Decode` i `OneOf`. Wlasny `NyDecoder<T>` implementuje `decode(json, response, decoders)` i widzi caly `NyResponse`, wiec moze odczytac kod statusu lub naglowki.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` dekoduje odpowiedz, ktora moze miec wiecej niz jeden ksztalt. Zarejestruj go pod wspolnym typem nadrzednym i wybierz ksztalt na podstawie pola w body, kodu statusu lub wlasnych warunkow:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` dekoduje rowniez body odpowiedzi 4xx i 5xx oraz `List<T>` element po elemencie. Nierozpoznany ksztalt pozostawia dane odpowiedzi jako null.

| Konstruktor | Wybiera ksztalt na podstawie |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | Wartosci `key` w body. Klucz z kropka, taki jak `'data.type'`, czyta zagniezdzone pole |
| `OneOf.byStatus(types, otherwise:)` | Kodu statusu. Kluczem jest kod, np. `404`, lub wzorzec, np. `'4xx'` lub `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Wlasnych warunkow, z ktorych kazdy przyjmuje body i odpowiedz. Wygrywa pierwszy, ktory zwroci true |
| `OneOf(resolve)` | Funkcji, ktora przyjmuje body i odpowiedz oraz zwraca wartosc lub null |

Wedlug kodu statusu:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Wygrywa dokladny kod, a nastepnie wzorzec z najmniejsza liczba `x`.

Z wlasnymi warunkami:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Z funkcja:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Odczytaj ksztalt z odpowiedzi za pomoca `dataAs<X>()` i `dataIs<X>()`; zobacz [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro tworzy dla Ciebie typ nadrzedny, jego podtypy oraz wpis `OneOf.byKey`:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Zobacz [Utworz model OneOf](/docs/{{$version}}/metro#make-a-one-of-model).

<div id="api-decoders"></div>

## Dekodery API

Dekodery API sa uzywane podczas wywolywania metody pomocniczej `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

Helper `api` dopasuje odpowiedni serwis API za pomoca generyków, wiec mozesz wywolac ponizszy helper, aby uzyskac dostep do serwisu.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Przed uzyciem helpera `api` musisz najpierw dodac swoj serwis API do <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
