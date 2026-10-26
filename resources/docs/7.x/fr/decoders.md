# Decodeurs

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Utilisation
  - [Decodeurs de modeles](#model-decoders "Decodeurs de modeles")
  - [Decodeurs types](#typed-decoders "Decode et decoders()")
  - [OneOf](#one-of "Decoder une reponse pouvant avoir plusieurs formes")
  - [Decodeurs d'API](#api-decoders "Decodeurs d'API")


<div id="introduction"></div>

## Introduction

Les decodeurs sont un concept introduit dans {{ config('app.name') }} qui vous permet de decoder des donnees en objets ou classes.
Vous utiliserez probablement les decodeurs lorsque vous travaillez avec la classe de [mise en reseau](/docs/7.x/networking) ou si vous souhaitez utiliser le helper `api` dans {{ config('app.name') }}.

> Par defaut, l'emplacement des decodeurs est `lib/config/decoders.dart`

Le fichier decoders.dart contiendra deux variables :
- [modelDecoders](#model-decoders) - Gere tous vos decodeurs de modeles
- [apiDecoders](#api-decoders) - Gere tous vos decodeurs d'API

<div id="model-decoders"></div>

## Decodeurs de modeles

Les decodeurs de modeles sont nouveaux dans {{ config('app.name') }}, ils fournissent un moyen de transformer les donnees de reponse en representations de modeles.

La methode helper `network()` utilisera la variable `modelDecoders` dans votre fichier <b>config/decoders.dart</b> pour determiner quel decodeur utiliser.

Voici un exemple.

Voici comment le helper `network` utilise les modelDecoders.

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

La methode `fetchUsers` decodera automatiquement la charge utile de la requete en un `User`.

Comment cela fonctionne-t-il ?

Vous avez une classe `User` comme ci-dessous.

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

Vous pouvez voir ci-dessus que cette classe a une methode `fromJson` qui nous fournit un moyen d'initialiser la classe.

Nous pouvons initialiser cette classe en appelant la methode suivante.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Maintenant, pour configurer nos decodeurs, nous devons faire ce qui suit.

<b>Fichier :</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

Dans le fichier modelDecoders, nous devons fournir le `Type` comme cle et gerer la transformation dans la valeur comme dans l'exemple ci-dessus.

L'argument `data` contiendra la charge utile de la requete API.

<div id="typed-decoders"></div>

## Decodeurs types

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` construit votre map `modelDecoders` a partir d'une liste. `Decode(User.fromJson)` decode un `User`, et aussi une `List<User>`, sans entree dediee :

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Utilisez `from` pour lire le modele a l'interieur d'un corps enveloppe, comme `{"data": {...}}`. Une cle avec des points telle que `'data.user'` lit plus en profondeur, et pour une liste, la liste elle-meme est lue depuis `from`.

`Decode` prend un `fromJson` qui accepte `dynamic` ou `Map<String, dynamic>`. Pour une closure, typez son parametre :

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Les decodeurs closure dans une map `{...}`, comme `User: (data) => User.fromJson(data)`, continuent de fonctionner. Les deux sont un `NyDecoder`, la classe dont `Decode` et `OneOf` heritent. Un `NyDecoder<T>` personnalise implemente `decode(json, response, decoders)` et voit tout le `NyResponse`, il peut donc lire le code de statut ou les en-tetes.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` decode une reponse pouvant prendre plusieurs formes. Enregistrez-le sous le type parent commun, et choisissez la forme selon un champ du corps, selon le code de statut, ou avec vos propres conditions :

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

Un `OneOf` decode aussi les corps 4xx et 5xx, ainsi qu'une `List<T>` element par element. Une forme qu'il ne reconnait pas laisse les donnees de la reponse a null.

| Constructeur | Choisit la forme selon |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | La valeur de `key` dans le corps. Une cle avec des points telle que `'data.type'` lit un champ imbrique |
| `OneOf.byStatus(types, otherwise:)` | Le code de statut. Une cle est un code comme `404`, ou un motif comme `'4xx'` ou `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Vos propres conditions, chacune recevant le corps et la reponse. La premiere qui retourne true l'emporte |
| `OneOf(resolve)` | Une fonction qui recoit le corps et la reponse et retourne la valeur, ou null |

Par code de statut :

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Un code exact l'emporte, puis le motif avec le moins de `x`.

Avec vos propres conditions :

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Avec une fonction :

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Lisez la forme depuis une reponse avec `dataAs<X>()` et `dataIs<X>()` ; consultez [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro cree le parent, ses sous-types et l'entree `OneOf.byKey` pour vous :

```bash
metro make:model login_result --one-of="success,two_factor"
```

Consultez [Make a OneOf model](/docs/{{$version}}/metro#make-a-one-of-model).

<div id="api-decoders"></div>

## Decodeurs d'API

Les decodeurs d'API sont utilises lors de l'appel de la methode helper `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

Le helper `api` associera le bon service API en utilisant les generiques, vous pouvez donc appeler le helper ci-dessous pour acceder a votre service.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Avant d'utiliser le helper `api`, vous devrez d'abord ajouter votre service API dans <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
