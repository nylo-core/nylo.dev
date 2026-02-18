# Decoders

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- Uzycie
  - [Dekodery modeli](#model-decoders "Dekodery modeli")
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
