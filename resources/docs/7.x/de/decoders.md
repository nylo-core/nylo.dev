# Decoders

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Verwendung
  - [Model-Decoders](#model-decoders "Model-Decoders")
  - [API-Decoders](#api-decoders "API-Decoders")


<div id="introduction"></div>

## Einleitung

Decoders sind ein in {{ config('app.name') }} eingeführtes Konzept, das es Ihnen ermöglicht, Daten in Objekte oder Klassen zu dekodieren.
Sie werden Decoders wahrscheinlich verwenden, wenn Sie mit der [Networking](/docs/7.x/networking)-Klasse arbeiten oder wenn Sie den `api`-Helfer in {{ config('app.name') }} verwenden möchten.

> Standardmäßig befindet sich die Datei für Decoders in `lib/config/decoders.dart`

Die Datei decoders.dart enthält zwei Variablen:
- [modelDecoders](#model-decoders) - Verarbeitet alle Ihre Model-Decoders
- [apiDecoders](#api-decoders) - Verarbeitet alle Ihre API-Decoders

<div id="model-decoders"></div>

## Model-Decoders

Model-Decoders sind neu in {{ config('app.name') }} und bieten eine Möglichkeit, Daten-Payloads in Model-Darstellungen umzuwandeln.

Die Hilfsmethode `network()` verwendet die Variable `modelDecoders` in Ihrer <b>config/decoders.dart</b>-Datei, um den zu verwendenden Decoder zu bestimmen.

Hier ist ein Beispiel.

So verwendet der `network`-Helfer modelDecoders.

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

Die Methode `fetchUsers` dekodiert automatisch den Payload der Anfrage in einen `User`.

Wie funktioniert das?

Sie haben eine `User`-Klasse wie die folgende.

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

Sie können sehen, dass diese Klasse eine `fromJson`-Methode hat, die uns eine Möglichkeit bietet, die Klasse zu initialisieren.

Wir können diese Klasse initialisieren, indem wir die folgende Methode aufrufen.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Um nun unsere Decoders einzurichten, müssen wir Folgendes tun.

<b>Datei:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

In der modelDecoders-Datei müssen wir den `Type` als Schlüssel angeben und die Umwandlung im Wert behandeln, wie im obigen Beispiel.

Das Argument `data` enthält den Payload der API-Anfrage.

<div id="api-decoders"></div>

## API-Decoders

API-Decoders werden beim Aufruf der `api`-Hilfsmethode verwendet.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

Der `api`-Helfer ordnet den richtigen API-Service mithilfe von Generics zu, sodass Sie den folgenden Helfer aufrufen können, um auf Ihren Service zuzugreifen.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Bevor Sie den `api`-Helfer verwenden, müssen Sie zunächst Ihren API-Service in <b>lib/config/decoders.dart > apiDecoders</b> hinzufügen.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
