# Decoder

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Utilizzo
  - [Model Decoder](#model-decoders "Model Decoder")
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
