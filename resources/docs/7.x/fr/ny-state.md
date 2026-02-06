# NyState

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Comment utiliser NyState](#how-to-use-nystate "Comment utiliser NyState")
- [Style de chargement](#loading-style "Style de chargement")
- [Actions d'etat](#state-actions "Actions d'etat")
- [Helpers](#helpers "Helpers")


<div id="introduction"></div>

## Introduction

`NyState` est une version etendue de la classe `State` standard de Flutter. Elle fournit des fonctionnalites supplementaires pour aider a gerer l'etat de vos pages et widgets de maniere plus efficace.

Vous pouvez **interagir** avec l'etat exactement comme vous le feriez avec un etat Flutter normal, mais avec les avantages supplementaires de NyState.

Voyons comment utiliser NyState.

<div id="how-to-use-nystate"></div>

## Comment utiliser NyState

Vous pouvez commencer a utiliser cette classe en l'etendant.

Exemple

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

La methode `init` est utilisee pour initialiser l'etat de la page. Vous pouvez utiliser cette methode en mode async ou sans async et en coulisses, elle gerera l'appel asynchrone et affichera un chargeur.

La methode `view` est utilisee pour afficher l'interface de la page.

#### Creer un nouveau widget stateful avec NyState

Pour creer un nouveau widget stateful dans {{ config('app.name') }}, vous pouvez executer la commande ci-dessous.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Style de chargement

Vous pouvez utiliser la propriete `loadingStyle` pour definir le style de chargement de votre page.

Exemple

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

Le `loadingStyle` **par defaut** sera votre Widget de chargement (resources/widgets/loader_widget.dart).
Vous pouvez personnaliser le `loadingStyle` pour modifier le style de chargement.

Voici un tableau des differents styles de chargement que vous pouvez utiliser :
// normal, skeletonizer, none

| Style | Description |
| --- | --- |
| normal | Style de chargement par defaut |
| skeletonizer | Style de chargement en squelette |
| none | Aucun style de chargement |

Vous pouvez changer le style de chargement comme ceci :

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Si vous souhaitez modifier le Widget de chargement dans l'un des styles, vous pouvez passer un `child` au `LoadingStyle`.

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

Maintenant, lorsque l'onglet est en cours de chargement, le texte "Loading..." sera affiche.

Exemple ci-dessous :

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

## Actions d'etat

Dans Nylo, vous pouvez definir de petites **actions** dans vos Widgets qui peuvent etre appelees depuis d'autres classes. C'est utile si vous souhaitez mettre a jour l'etat d'un widget depuis une autre classe.

D'abord, vous devez **definir** vos actions dans votre widget. Cela fonctionne pour `NyState` et `NyPage`.

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

Ensuite, vous pouvez appeler l'action depuis une autre classe en utilisant la methode `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Si vous utilisez stateActions avec un `NyPage`, vous devez utiliser le **path** de la page.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Il existe egalement une autre classe appelee `StateAction`, qui dispose de plusieurs methodes que vous pouvez utiliser pour mettre a jour l'etat de vos widgets.

- `refreshPage` - Rafraichir la page.
- `pop` - Retirer la page.
- `showToastSorry` - Afficher une notification toast de type sorry.
- `showToastWarning` - Afficher une notification toast d'avertissement.
- `showToastInfo` - Afficher une notification toast d'information.
- `showToastDanger` - Afficher une notification toast de danger.
- `showToastOops` - Afficher une notification toast de type oops.
- `showToastSuccess` - Afficher une notification toast de succes.
- `showToastCustom` - Afficher une notification toast personnalisee.
- `validate` - Valider les donnees de votre widget.
- `changeLanguage` - Mettre a jour la langue de l'application.
- `confirmAction` - Effectuer une action de confirmation.

Exemple

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

Vous pouvez utiliser la classe `StateAction` pour mettre a jour l'etat de n'importe quelle page/widget dans votre application tant que le widget est gere par un etat.

<div id="helpers"></div>

## Helpers

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

Cette methode re-executera la methode `init` dans votre etat. C'est utile si vous souhaitez rafraichir les donnees de la page.

Exemple
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

`pop` - Retirer la page actuelle de la pile.

Exemple

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

Afficher une notification toast sur le contexte.

Exemple

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

Le helper `validate` effectue une verification de validation sur les donnees.

Vous pouvez en savoir plus sur le validateur <a href="/docs/{{$version}}/validation" target="_BLANK">ici</a>.

Exemple

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

Vous pouvez appeler `changeLanguage` pour changer le fichier json **/lang** utilise sur l'appareil.

En savoir plus sur la localisation <a href="/docs/{{$version}}/localization" target="_BLANK">ici</a>.

Exemple

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

Vous pouvez utiliser `whenEnv` pour executer une fonction lorsque votre application est dans un certain etat.
Par exemple, votre variable **APP_ENV** dans votre fichier `.env` est definie sur 'developing', `APP_ENV=developing`.

Exemple

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

Cette methode verrouillera l'etat apres l'appel d'une fonction, jusqu'a ce que la methode soit terminee, elle permettra a l'utilisateur de faire des requetes subsequentes. Cette methode mettra egalement a jour l'etat, utilisez `isLocked` pour verifier.

Le meilleur exemple pour illustrer `lockRelease` est d'imaginer un ecran de connexion ou l'utilisateur appuie sur 'Connexion'. Nous voulons effectuer un appel asynchrone pour connecter l'utilisateur mais nous ne voulons pas que la methode soit appelee plusieurs fois car cela pourrait creer une experience indesirable.

Voici un exemple ci-dessous.

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

Une fois que vous appuyez sur la methode **_login**, elle bloquera toute requete subsequente jusqu'a ce que la requete originale soit terminee. Le helper `isLocked('login_to_app')` est utilise pour verifier si le bouton est verrouille. Dans l'exemple ci-dessus, vous pouvez voir que nous l'utilisons pour determiner quand afficher notre Widget de chargement.

<div id="is-locked"></div>

### isLocked

Cette methode verifiera si l'etat est verrouille en utilisant le helper [`lockRelease`](#lock-release).

Exemple
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

La methode `view` est utilisee pour afficher l'interface de la page.

Exemple
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

La methode `confirmAction` affichera une boite de dialogue a l'utilisateur pour confirmer une action.
Cette methode est utile si vous souhaitez que l'utilisateur confirme une action avant de continuer.

Exemple

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

La methode `showToastSuccess` affichera une notification toast de succes a l'utilisateur.

Exemple
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

La methode `showToastOops` affichera une notification toast de type oops a l'utilisateur.

Exemple
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

La methode `showToastDanger` affichera une notification toast de danger a l'utilisateur.

Exemple
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

La methode `showToastInfo` affichera une notification toast d'information a l'utilisateur.

Exemple
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

La methode `showToastWarning` affichera une notification toast d'avertissement a l'utilisateur.

Exemple
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

La methode `showToastSorry` affichera une notification toast de type sorry a l'utilisateur.

Exemple
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

La methode `isLoading` verifiera si l'etat est en cours de chargement.

Exemple
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

La methode `afterLoad` peut etre utilisee pour afficher un chargeur jusqu'a ce que l'etat ait termine le 'chargement'.

Vous pouvez egalement verifier d'autres cles de chargement en utilisant le parametre **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Exemple
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

La methode `afterNotLocked` verifiera si l'etat est verrouille.

Si l'etat est verrouille, elle affichera le widget de [chargement].

Exemple
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

Vous pouvez utiliser `afterNotNull` pour afficher un widget de chargement jusqu'a ce qu'une variable ait ete definie.

Imaginez que vous devez recuperer le compte d'un utilisateur depuis une base de donnees en utilisant un appel Future qui pourrait prendre 1 a 2 secondes, vous pouvez utiliser afterNotNull sur cette valeur jusqu'a ce que vous ayez les donnees.

Exemple

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

Vous pouvez passer a un etat de 'chargement' en utilisant `setLoading`.

Le premier parametre accepte un `bool` pour indiquer s'il est en chargement ou non, le parametre suivant vous permet de definir un nom pour l'etat de chargement, par exemple `setLoading(true, name: 'refreshing_content');`.

Exemple
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
