# Decoder

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Utilizzo
  - [Model Decoder](#model-decoders "Model Decoder")
  - [Decoder Tipizzati](#typed-decoders "Decode e decoders()")
  - [OneOf](#one-of "Decodificare una risposta che puo' avere piu' di una forma")
  - [API Decoder](#api-decoders "API Decoder")


<div id="introduction"></div>

## Introduzione

I decoder sono un concetto introdotto in {{ config('app.name') }} che ti permette di decodificare dati in oggetti o classi.
Probabilmente userai i decoder quando lavori con la classe [networking](/docs/7.x/networking) o se vuoi utilizzare l'helper `api` in {{ config('app.name') }}.

> Per impostazione predefinita, la posizione dei decoder e' `lib/config/decoders.dart`

Il file decoders.dart conterra' due variabili:
- [modelDecoders](#model-decoders) - Gestisce tutti i decoder dei tuoi modelli
- [apiDecoders](#api-decoders) - Gestisce tutti i decoder delle tue API

<div id="model-decoders"></div>

## Model decoder

I model decoder sono una novita' in {{ config('app.name') }}, forniscono un modo per trasformare i payload di dati in rappresentazioni di modelli.

Il metodo helper `network()` utilizzera' la variabile `modelDecoders` all'interno del tuo file <b>config/decoders.dart</b> per determinare quale decoder utilizzare.

Ecco un esempio.

Ecco come l'helper `network` utilizza modelDecoders.

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

Il metodo `fetchUsers` decodifichera' automaticamente il payload dalla richiesta in un `User`.

Come funziona?

Hai una classe `User` come quella qui sotto.

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

Come puoi vedere, questa classe ha un metodo `fromJson` che ci fornisce un modo per inizializzare la classe.

Possiamo inizializzare questa classe chiamando il metodo seguente.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

Ora, per configurare i nostri decoder, dobbiamo fare quanto segue.

<b>File:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

Nel file modelDecoders, dobbiamo fornire il `Type` come chiave e gestire la trasformazione nel valore come nell'esempio sopra.

L'argomento `data` conterra' il payload dalla richiesta API.

<div id="typed-decoders"></div>

## Decoder Tipizzati

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` costruisce la tua mappa `modelDecoders` a partire da una lista. `Decode(User.fromJson)` decodifica un `User`, e anche una `List<User>`, senza una voce propria:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

Usa `from` per leggere il modello dall'interno di un corpo incapsulato, come `{"data": {...}}`. Una chiave con punti come `'data.user'` legge piu' in profondita', e per una lista, la lista stessa viene letta da `from`.

`Decode` accetta un `fromJson` che riceve `dynamic` o `Map<String, dynamic>`. Per una closure, tipizza il suo parametro:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

I decoder closure in una mappa `{...}`, come `User: (data) => User.fromJson(data)`, continuano a funzionare. Entrambi sono un `NyDecoder`, la classe che `Decode` e `OneOf` estendono. Un `NyDecoder<T>` personalizzato implementa `decode(json, response, decoders)` e vede l'intera `NyResponse`, quindi puo' leggere il codice di stato o gli header.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

<!-- uncertain: new Nylo-specific term "OneOf" and "sealed" usage -->
`OneOf` decodifica una risposta che puo' avere piu' di una forma. Registralo sotto il tipo padre condiviso e scegli la forma tramite un campo del corpo, tramite il codice di stato o con le tue condizioni:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

Un `OneOf` decodifica anche i corpi 4xx e 5xx, e una `List<T>` elemento per elemento. Una forma che non riconosce lascia i dati della risposta a null.

| Costruttore | Sceglie la forma tramite |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | Il valore di `key` nel corpo. Una chiave con punti come `'data.type'` legge un campo annidato |
| `OneOf.byStatus(types, otherwise:)` | Il codice di stato. Una chiave e' un codice come `404`, o un pattern come `'4xx'` o `'40x'` |
| `OneOf.byJson(types, otherwise:)` | Le tue condizioni, ciascuna delle quali riceve il corpo e la risposta. Vince la prima che restituisce true |
| `OneOf(resolve)` | Una funzione che riceve il corpo e la risposta e restituisce il valore, o null |

Per codice di stato:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

Vince un codice esatto, poi il pattern con meno `x`.

Con le tue condizioni:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

Con una funzione:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

Leggi la forma da una risposta con `dataAs<X>()` e `dataIs<X>()`; vedi [NyResponse](/docs/{{$version}}/networking#ny-response).

Metro crea per te il padre, i suoi sottotipi e la voce `OneOf.byKey`:

```bash
metro make:model login_result --one-of="success,two_factor"
```

Vedi [Creare un modello OneOf](/docs/{{$version}}/metro#make-a-one-of-model).

<div id="api-decoders"></div>

## API decoder

I decoder API vengono utilizzati quando si chiama il metodo helper `api`.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

L'helper `api` abbinera' il servizio API corretto utilizzando i generics, quindi puoi chiamare l'helper qui sotto per accedere al tuo servizio.

```dart
await api<MyService>((request) => request.callMyMethod());
```

Prima di utilizzare l'helper `api`, dovrai prima aggiungere il tuo servizio API in <b>lib/config/decoders.dart > apiDecoders</b>.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
