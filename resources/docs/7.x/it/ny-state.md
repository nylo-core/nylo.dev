# NyState

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Come usare NyState](#how-to-use-nystate "Come usare NyState")
- [Stile di Caricamento](#loading-style "Stile di Caricamento")
- [Azioni di Stato](#state-actions "Azioni di Stato")
- [Helper](#helpers "Helper")


<div id="introduction"></div>

## Introduzione

`NyState` e' una versione estesa della classe `State` standard di Flutter. Fornisce funzionalita' aggiuntive per aiutare a gestire lo stato delle pagine e dei widget in modo piu' efficiente.

Puoi **interagire** con lo stato esattamente come faresti con un normale stato Flutter, ma con i vantaggi aggiuntivi di NyState.

Vediamo come usare NyState.

<div id="how-to-use-nystate"></div>

## Come usare NyState

Puoi iniziare a usare questa classe estendendola.

Esempio

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {

  };

  @override
  view(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded")
    );
  }
```

Il metodo `init` viene utilizzato per inizializzare lo stato della pagina. Puoi usare questo metodo come asincrono o senza async e dietro le quinte, gestira' la chiamata asincrona e mostrera' un loader.

Il metodo `view` viene utilizzato per visualizzare l'interfaccia utente della pagina.

#### Creare un nuovo widget stateful con NyState

Per creare un nuovo widget stateful in {{ config('app.name') }}, puoi eseguire il comando seguente.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Stile di Caricamento

Puoi usare la proprieta' `loadingStyle` per impostare lo stile di caricamento per la tua pagina.

Esempio

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

Il `loadingStyle` **predefinito** sara' il tuo Widget di caricamento (resources/widgets/loader_widget.dart).
Puoi personalizzare il `loadingStyle` per aggiornare lo stile di caricamento.

Ecco una tabella per i diversi stili di caricamento che puoi utilizzare:
// normal, skeletonizer, none

| Stile | Descrizione |
| --- | --- |
| normal | Stile di caricamento predefinito |
| skeletonizer | Stile di caricamento a scheletro |
| none | Nessuno stile di caricamento |

Puoi cambiare lo stile di caricamento in questo modo:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Se vuoi aggiornare il Widget di caricamento in uno degli stili, puoi passare un `child` al `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// same for skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Ora, quando la scheda sta caricando, il testo "Loading..." verra' visualizzato.

Esempio qui sotto:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // simulate a network call for 3 seconds
    };

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            body: Text("The page loaded")
        );
    }
    ...
}
```

<div id="state-actions"></div>

## Azioni di Stato

In Nylo, puoi definire piccole **azioni** nei tuoi Widget che possono essere chiamate da altre classi. Questo e' utile se vuoi aggiornare lo stato di un widget da un'altra classe.

Per prima cosa, devi **definire** le tue azioni nel tuo widget. Questo funziona per `NyState` e `NyPage`.

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // handle how you want to initialize the state
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // Example with data
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

Poi, puoi chiamare l'azione da un'altra classe utilizzando il metodo `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Se stai usando stateActions con un `NyPage`, devi usare il **path** della pagina.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

C'e' anche un'altra classe chiamata `StateAction`, questa ha alcuni metodi che puoi usare per aggiornare lo stato dei tuoi widget.

- `refreshPage` - Aggiorna la pagina.
- `pop` - Chiudi la pagina.
- `showToastSorry` - Visualizza una notifica toast di scuse.
- `showToastWarning` - Visualizza una notifica toast di avviso.
- `showToastInfo` - Visualizza una notifica toast informativa.
- `showToastDanger` - Visualizza una notifica toast di pericolo.
- `showToastOops` - Visualizza una notifica toast oops.
- `showToastSuccess` - Visualizza una notifica toast di successo.
- `showToastCustom` - Visualizza una notifica toast personalizzata.
- `validate` - Valida i dati dal tuo widget.
- `changeLanguage` - Aggiorna la lingua nell'applicazione.
- `confirmAction` - Esegui un'azione di conferma.

Esempio

``` dart
class _UpgradeButtonState extends NyState<UpgradeButton> {

  view(BuildContext context) {
    return Button.primary(
      onPressed: () {
        StateAction.showToastSuccess(UpgradePage.state,
          description: "You have successfully upgraded your account",
        );
      },
      text: "Upgrade",
    );
  }
}
```

Puoi usare la classe `StateAction` per aggiornare lo stato di qualsiasi pagina/widget nella tua applicazione purche' il widget sia gestito con lo stato.

<div id="helpers"></div>

## Helper

|  |  |
| --- | ----------- |
| [lockRelease](#lock-release "lockRelease") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") | [confirmAction](#confirm-action "confirmAction") |
| [showToastSuccess](#show-toast-success "showToastSuccess") | [showToastOops](#show-toast-oops "showToastOops") |
| [showToastDanger](#show-toast-danger "showToastDanger") | [showToastInfo](#show-toast-info "showToastInfo") |
| [showToastWarning](#show-toast-warning "showToastWarning") | [showToastSorry](#show-toast-sorry "showToastSorry") |


<div id="reboot"></div>

### Reboot

Questo metodo rieseguira' il metodo `init` nel tuo stato. E' utile se vuoi aggiornare i dati sulla pagina.

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  get init => () async {
    users = await api<ApiService>((request) => request.fetchUsers());
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Users"),
          actions: [
            IconButton(
              icon: Icon(Icons.refresh),
              onPressed: () {
                reboot(); // refresh the data
              },
            )
          ],
        ),
        body: ListView.builder(
          itemCount: users.length,
          itemBuilder: (context, index) {
            return Text(users[index].firstName);
          }
        ),
    );
  }
}
```

<div id="pop"></div>

### Pop

`pop` - Rimuove la pagina corrente dallo stack.

Esempio

``` dart
class _HomePageState extends NyState<HomePage> {

  popView() {
    pop();
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: popView,
        child: Text("Pop current view")
      )
    );
  }
```


<div id="showToast"></div>

### showToast

Mostra una notifica toast sul contesto.

Esempio

```dart
class _HomePageState extends NyState<HomePage> {

  displayToast() {
    showToast(
        title: "Hello",
        description: "World",
        icon: Icons.account_circle,
        duration: Duration(seconds: 2),
        style: ToastNotificationStyleType.INFO // SUCCESS, INFO, DANGER, WARNING
    );
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: displayToast,
        child: Text("Display a toast")
      )
    );
  }
```


<div id="validate"></div>

### validate

L'helper `validate` esegue un controllo di validazione sui dati.

Puoi saperne di piu' sul validatore <a href="/docs/{{$version}}/validation" target="_BLANK">qui</a>.

Esempio

``` dart
class _HomePageState extends NyState<HomePage> {
TextEditingController _textFieldControllerEmail = TextEditingController();

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    validate(rules: {
        "email address": [textEmail, "email"]
      }, onSuccess: () {
      print('passed validation')
    });
  }
```


<div id="change-language"></div>

### changeLanguage

Puoi chiamare `changeLanguage` per cambiare il file json **/lang** utilizzato sul dispositivo.

Scopri di piu' sulla localizzazione <a href="/docs/{{$version}}/localization" target="_BLANK">qui</a>.

Esempio

``` dart
class _HomePageState extends NyState<HomePage> {

  changeLanguageES() {
    await changeLanguage('es');
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: changeLanguageES,
        child: Text("Change Language".tr())
      )
    );
  }
```


<div id="when-env"></div>

### whenEnv

Puoi usare `whenEnv` per eseguire una funzione quando la tua applicazione e' in un determinato stato.
Ad esempio, la tua variabile **APP_ENV** all'interno del file `.env` e' impostata su 'developing', `APP_ENV=developing`.

Esempio

```dart
class _HomePageState extends NyState<HomePage> {

  TextEditingController _textEditingController = TextEditingController();

  @override
  get init => () {
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  };
```

<div id="lock-release"></div>

### lockRelease

Questo metodo blocchera' lo stato dopo che una funzione viene chiamata, solo fino a quando il metodo non ha terminato permettera' all'utente di fare richieste successive. Questo metodo aggiornera' anche lo stato, usa `isLocked` per controllare.

Il miglior esempio per mostrare `lockRelease` e' immaginare di avere una schermata di login quando l'utente tocca 'Login'. Vogliamo eseguire una chiamata asincrona per effettuare il login dell'utente ma non vogliamo che il metodo venga chiamato piu' volte perche' potrebbe creare un'esperienza indesiderata.

Ecco un esempio qui sotto.

```dart
class _LoginPageState extends NyState<LoginPage> {

  _login() async {
    await lockRelease('login_to_app', perform: () async {

      await Future.delayed(Duration(seconds: 4), () {
        print('Pretend to login...');
      });

    });
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
            Center(
              child: InkWell(
                onTap: _login,
                child: Text("Login"),
              ),
            )
          ],
        )
    );
  }
```

Una volta toccato il metodo **_login**, blocchera' qualsiasi richiesta successiva fino a quando la richiesta originale non e' terminata. L'helper `isLocked('login_to_app')` viene usato per controllare se il pulsante e' bloccato. Nell'esempio sopra, puoi vedere che lo usiamo per determinare quando visualizzare il nostro Widget di caricamento.

<div id="is-locked"></div>

### isLocked

Questo metodo controllera' se lo stato e' bloccato utilizzando l'helper [`lockRelease`](#lock-release).

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
          ],
        )
    );
  }
```

<div id="view"></div>

### view

Il metodo `view` viene utilizzato per visualizzare l'interfaccia utente della pagina.

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
      return Scaffold(
          body: Center(
              child: Text("My Page")
          )
      );
  }
}
```

<div id="confirm-action"></div>

### confirmAction

Il metodo `confirmAction` visualizzera' un dialogo all'utente per confermare un'azione.
Questo metodo e' utile se vuoi che l'utente confermi un'azione prima di procedere.

Esempio

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

Il metodo `showToastSuccess` visualizzera' una notifica toast di successo all'utente.

Esempio
``` dart
_login() {
    ...
    showToastSuccess(
        description: "You have successfully logged in"
    );
}
```

<div id="show-toast-oops"></div>

### showToastOops

Il metodo `showToastOops` visualizzera' una notifica toast oops all'utente.

Esempio
``` dart
_error() {
    ...
    showToastOops(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-danger"></div>

### showToastDanger

Il metodo `showToastDanger` visualizzera' una notifica toast di pericolo all'utente.

Esempio
``` dart
_error() {
    ...
    showToastDanger(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-info"></div>

### showToastInfo

Il metodo `showToastInfo` visualizzera' una notifica toast informativa all'utente.

Esempio
``` dart
_info() {
    ...
    showToastInfo(
        description: "Your account has been updated"
    );
}
```

<div id="show-toast-warning"></div>

### showToastWarning

Il metodo `showToastWarning` visualizzera' una notifica toast di avviso all'utente.

Esempio
``` dart
_warning() {
    ...
    showToastWarning(
        description: "Your account is about to expire"
    );
}
```

<div id="show-toast-sorry"></div>

### showToastSorry

Il metodo `showToastSorry` visualizzera' una notifica toast di scuse all'utente.

Esempio
``` dart
_sorry() {
    ...
    showToastSorry(
        description: "Your account has been suspended"
    );
}
```

<div id="is-loading"></div>

### isLoading

Il metodo `isLoading` controllera' se lo stato sta caricando.

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    if (isLoading()) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: colors().primaryContent
        )
      )
    );
  }
```

<div id="after-load"></div>

### afterLoad

Il metodo `afterLoad` puo' essere usato per visualizzare un loader fino a quando lo stato non ha finito di 'caricare'.

Puoi anche controllare altre chiavi di caricamento utilizzando il parametro **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () {
    awaitData(perform: () async {
        await sleep(4);
        print('4 seconds after...');
    });
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterLoad(child: () {
          return Text("Loaded");
        })
    );
  }
```

<div id="after-not-locked"></div>

### afterNotLocked

Il metodo `afterNotLocked` controllera' se lo stato e' bloccato.

Se lo stato e' bloccato visualizzera' il widget [loading].

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          alignment: Alignment.center,
          child: afterNotLocked('login', child: () {
            return MaterialButton(
              onPressed: () {
                login();
              },
              child: Text("Login"),
            );
          }),
        )
    );
  }

  login() async {
    await lockRelease('login', perform: () async {
      await sleep(4);
      print('4 seconds after...');
    });
  }
}
```

<div id="after-not-null"></div>

### afterNotNull

Puoi usare `afterNotNull` per mostrare un widget di caricamento fino a quando una variabile non e' stata impostata.

Immagina di dover recuperare l'account di un utente da un DB usando una chiamata Future che potrebbe richiedere 1-2 secondi, puoi usare afterNotNull su quel valore fino a quando hai i dati.

Esempio

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // example
    setState(() {});
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterNotNull(_user, child: () {
          return Text(_user!.firstName);
        })
    );
  }
```

<div id="set-loading"></div>

### setLoading

Puoi passare a uno stato di 'caricamento' utilizzando `setLoading`.

Il primo parametro accetta un `bool` per indicare se sta caricando o meno, il parametro successivo ti permette di impostare un nome per lo stato di caricamento, ad es. `setLoading(true, name: 'refreshing_content');`.

Esempio
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    setLoading(true, name: 'refreshing_content');

    await sleep(4);

    setLoading(false, name: 'refreshing_content');
  };

  @override
  Widget build(BuildContext context) {
    if (isLoading(name: 'refreshing_content')) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded")
    );
  }
```
