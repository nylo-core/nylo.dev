# Decoders

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Verwendung
  - [Model-Decoders](#model-decoders "Model-Decoders")
  - [Typisierte Decoders](#typed-decoders "Decode und decoders()")
  - [OneOf](#one-of "Eine Antwort dekodieren, die mehr als eine Form haben kann")
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

<div id="typed-decoders"></div>

## Typisierte Decoders

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` erstellt Ihre `modelDecoders`-Map aus einer Liste. `Decode(User.fromJson)` dekodiert einen `User` und auch eine `List<User>`, ohne einen eigenen Eintrag:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Verwenden Sie `from`, um das Model aus einem umschlossenen Body zu lesen, etwa `{"data": {...}}`. Ein Schlüssel mit Punkt wie `'data.user'` liest tiefer, und bei einer Liste wird die Liste selbst aus `from` gelesen.

`Decode` erwartet ein `fromJson`, das `dynamic` oder `Map<String, dynamic>` akzeptiert. Geben Sie bei einer Closure den Typ ihres Parameters an:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

Closure-Decoders in einer `{...}`-Map, etwa `User: (data) => User.fromJson(data)`, funktionieren weiterhin. Beide sind ein `NyDecoder`, die Klasse, von der `Decode` und `OneOf` erben. Ein eigener `NyDecoder<T>` implementiert `decode(json, response, decoders)` und sieht die gesamte `NyResponse`, sodass er den Statuscode oder die Header lesen kann.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` dekodiert eine Antwort, die mehr als eine Form annehmen kann. Registrieren Sie es unter dem gemeinsamen Elterntyp und wählen Sie die Form über ein Feld im Body, über den Statuscode oder mit eigenen Bedingungen:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

Ein `OneOf` dekodiert auch 4xx- und 5xx-Bodys sowie eine `List<T>` Element für Element. Eine Form, die es nicht erkennt, lässt die Antwortdaten null.

| Konstruktor | Wählt die Form über |
|-------------|---------------------|
| `OneOf.byKey(key, types, otherwise:)` | Den Wert von `key` im Body. Ein Schlüssel mit Punkt wie `'data.type'` liest ein verschachteltes Feld |
| `OneOf.byStatus(types, otherwise:)` | Den Statuscode. Ein Schlüssel ist ein Code wie `404` oder ein Muster wie `'4xx'` oder `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Ihre eigenen Bedingungen, die jeweils den Body und die Antwort erhalten. Die erste, die true zurückgibt, gewinnt |
| `OneOf(resolve)` | Eine Funktion, die den Body und die Antwort erhält und den Wert oder null zurückgibt |

Nach Statuscode:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Ein exakter Code gewinnt, danach das Muster mit den wenigsten `x`.

Mit eigenen Bedingungen:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Mit einer Funktion:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Lesen Sie die Form aus einer Antwort mit `dataAs<X>()` und `dataIs<X>()`; siehe [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro erstellt den Elterntyp, seine Untertypen und den `OneOf.byKey`-Eintrag für Sie:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Siehe [Ein OneOf-Model erstellen](/docs/{{$version}}/metro#make-a-one-of-model).

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
