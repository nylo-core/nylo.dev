# Decodeurs

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Utilisation
  - [Decodeurs de modeles](#model-decoders "Decodeurs de modeles")
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
