# NyState

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [NyState verwenden](#how-to-use-nystate "NyState verwenden")
- [Ladestil](#loading-style "Ladestil")
- [State-Aktionen](#state-actions "State-Aktionen")
- [Helfer](#helpers "Helfer")


<div id="introduction"></div>

## Einleitung

`NyState` ist eine erweiterte Version der Standard-Flutter-`State`-Klasse. Sie bietet zusätzliche Funktionen, um den State Ihrer Seiten und Widgets effizienter zu verwalten.

Sie können **mit dem State interagieren** genau wie mit einem normalen Flutter-State, aber mit den zusätzlichen Vorteilen von NyState.

Schauen wir uns an, wie man NyState verwendet.

<div id="how-to-use-nystate"></div>

## NyState verwenden

Sie können diese Klasse verwenden, indem Sie sie erweitern.

Beispiel

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

Die `init`-Methode wird verwendet, um den State der Seite zu initialisieren. Sie können diese Methode mit oder ohne async verwenden, und im Hintergrund wird der asynchrone Aufruf behandelt und ein Loader angezeigt.

Die `view`-Methode wird verwendet, um die UI der Seite anzuzeigen.

#### Ein neues Stateful Widget mit NyState erstellen

Um eine neue Seite in {{ config('app.name') }} zu erstellen, können Sie den folgenden Befehl ausführen.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Ladestil

Sie können die `loadingStyle`-Eigenschaft verwenden, um den Ladestil für Ihre Seite festzulegen.

Beispiel

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

Der **Standard**-`loadingStyle` wird Ihr Lade-Widget (resources/widgets/loader_widget.dart) sein.
Sie können den `loadingStyle` anpassen, um den Ladestil zu aktualisieren.

Hier ist eine Tabelle für die verschiedenen Ladestile, die Sie verwenden können:
// normal, skeletonizer, none

| Stil | Beschreibung |
| --- | --- |
| normal | Standard-Ladestil |
| skeletonizer | Skeleton-Ladestil |
| none | Kein Ladestil |

Sie können den Ladestil wie folgt ändern:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Wenn Sie das Lade-Widget in einem der Stile aktualisieren möchten, können Sie ein `child` an den `LoadingStyle` übergeben.

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

Wenn der Tab lädt, wird der Text "Loading..." angezeigt.

Beispiel unten:

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

## State-Aktionen

In Nylo können Sie kleine **Aktionen** in Ihren Widgets definieren, die von anderen Klassen aufgerufen werden können. Dies ist nützlich, wenn Sie den State eines Widgets von einer anderen Klasse aktualisieren möchten.

Zuerst müssen Sie Ihre Aktionen in Ihrem Widget **definieren**. Dies funktioniert für `NyState` und `NyPage`.

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

Dann können Sie die Aktion von einer anderen Klasse mit der `stateAction`-Methode aufrufen.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Wenn Sie stateActions mit einem `NyPage` verwenden, müssen Sie den **Pfad** der Seite verwenden.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Es gibt auch eine weitere Klasse namens `StateAction`, die einige Methoden hat, mit denen Sie den State Ihrer Widgets aktualisieren können.

- `refreshPage` - Die Seite aktualisieren.
- `pop` - Die Seite entfernen.
- `showToastSorry` - Eine Sorry-Toast-Benachrichtigung anzeigen.
- `showToastWarning` - Eine Warnung-Toast-Benachrichtigung anzeigen.
- `showToastInfo` - Eine Info-Toast-Benachrichtigung anzeigen.
- `showToastDanger` - Eine Gefahr-Toast-Benachrichtigung anzeigen.
- `showToastOops` - Eine Oops-Toast-Benachrichtigung anzeigen.
- `showToastSuccess` - Eine Erfolgs-Toast-Benachrichtigung anzeigen.
- `showToastCustom` - Eine benutzerdefinierte Toast-Benachrichtigung anzeigen.
- `validate` - Daten aus Ihrem Widget validieren.
- `changeLanguage` - Die Sprache in der Anwendung aktualisieren.
- `confirmAction` - Eine Bestätigungsaktion ausführen.

Beispiel

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

Sie können die `StateAction`-Klasse verwenden, um den State jeder Seite/jedes Widgets in Ihrer Anwendung zu aktualisieren, solange das Widget state-verwaltet ist.

<div id="helpers"></div>

## Helfer

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

Diese Methode führt die `init`-Methode in Ihrem State erneut aus. Dies ist nützlich, wenn Sie die Daten auf der Seite aktualisieren möchten.

Beispiel
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

`pop` - Die aktuelle Seite vom Stack entfernen.

Beispiel

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

Eine Toast-Benachrichtigung im Kontext anzeigen.

Beispiel

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

Der `validate`-Helfer führt eine Validierungsprüfung auf Daten durch.

Sie können mehr über den Validator <a href="/docs/{{$version}}/validation" target="_BLANK">hier</a> erfahren.

Beispiel

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

Sie können `changeLanguage` aufrufen, um die json-**/lang**-Datei zu ändern, die auf dem Gerät verwendet wird.

Erfahren Sie mehr über Lokalisierung <a href="/docs/{{$version}}/localization" target="_BLANK">hier</a>.

Beispiel

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

Sie können `whenEnv` verwenden, um eine Funktion auszuführen, wenn sich Ihre Anwendung in einem bestimmten Zustand befindet.
Z.B. Ihre **APP_ENV**-Variable in Ihrer `.env`-Datei ist auf 'developing' gesetzt, `APP_ENV=developing`.

Beispiel

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

Diese Methode sperrt den State, nachdem eine Funktion aufgerufen wurde, und erlaubt dem Benutzer erst dann nachfolgende Anfragen, wenn die Methode beendet ist. Diese Methode aktualisiert auch den State. Verwenden Sie `isLocked`, um den Status zu prüfen.

Das beste Beispiel, um `lockRelease` zu demonstrieren, ist sich vorzustellen, dass wir einen Login-Bildschirm haben und wenn der Benutzer auf 'Login' tippt. Wir möchten einen asynchronen Aufruf durchführen, um den Benutzer anzumelden, aber wir möchten nicht, dass die Methode mehrfach aufgerufen wird, da dies zu einem unerwünschten Erlebnis führen könnte.

Hier ist ein Beispiel unten.

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

Sobald Sie die **_login**-Methode aufrufen, blockiert sie alle nachfolgenden Anfragen, bis die ursprüngliche Anfrage beendet ist. Der `isLocked('login_to_app')`-Helfer wird verwendet, um zu prüfen, ob der Button gesperrt ist. Im obigen Beispiel können Sie sehen, dass wir damit bestimmen, wann unser Lade-Widget angezeigt wird.

<div id="is-locked"></div>

### isLocked

Diese Methode prüft, ob der State mit dem [`lockRelease`](#lock-release)-Helfer gesperrt ist.

Beispiel
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

Die `view`-Methode wird verwendet, um die UI der Seite anzuzeigen.

Beispiel
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

Die `confirmAction`-Methode zeigt dem Benutzer einen Dialog an, um eine Aktion zu bestätigen.
Diese Methode ist nützlich, wenn Sie möchten, dass der Benutzer eine Aktion bestätigt, bevor er fortfährt.

Beispiel

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

Die `showToastSuccess`-Methode zeigt dem Benutzer eine Erfolgs-Toast-Benachrichtigung an.

Beispiel
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

Die `showToastOops`-Methode zeigt dem Benutzer eine Oops-Toast-Benachrichtigung an.

Beispiel
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

Die `showToastDanger`-Methode zeigt dem Benutzer eine Gefahr-Toast-Benachrichtigung an.

Beispiel
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

Die `showToastInfo`-Methode zeigt dem Benutzer eine Info-Toast-Benachrichtigung an.

Beispiel
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

Die `showToastWarning`-Methode zeigt dem Benutzer eine Warnung-Toast-Benachrichtigung an.

Beispiel
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

Die `showToastSorry`-Methode zeigt dem Benutzer eine Sorry-Toast-Benachrichtigung an.

Beispiel
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

Die `isLoading`-Methode prüft, ob der State lädt.

Beispiel
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

Die `afterLoad`-Methode kann verwendet werden, um einen Loader anzuzeigen, bis der State das 'Laden' beendet hat.

Sie können auch andere Ladeschlüssel mit dem Parameter **loadingKey** prüfen: `afterLoad(child: () {}, loadingKey: 'home_data')`.

Beispiel
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

Die `afterNotLocked`-Methode prüft, ob der State gesperrt ist.

Wenn der State gesperrt ist, wird das [loading]-Widget angezeigt.

Beispiel
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

Sie können `afterNotNull` verwenden, um ein Lade-Widget anzuzeigen, bis eine Variable gesetzt wurde.

Stellen Sie sich vor, Sie müssen das Konto eines Benutzers aus einer DB mit einem Future-Aufruf abrufen, der 1-2 Sekunden dauern könnte. Sie können afterNotNull auf diesen Wert anwenden, bis Sie die Daten haben.

Beispiel

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

Sie können mit `setLoading` in einen 'Lade'-Zustand wechseln.

Der erste Parameter akzeptiert einen `bool` für den Ladezustand, der nächste Parameter ermöglicht es Ihnen, einen Namen für den Ladezustand festzulegen, z.B. `setLoading(true, name: 'refreshing_content');`.

Beispiel
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
