# NyState

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Como usar NyState](#how-to-use-nystate "Como usar NyState")
- [Estilo de carga](#loading-style "Estilo de carga")
- [Acciones de estado](#state-actions "Acciones de estado")
- [Helpers](#helpers "Helpers")


<div id="introduction"></div>

## Introduccion

`NyState` es una version extendida de la clase estandar `State` de Flutter. Proporciona funcionalidad adicional para ayudar a gestionar el estado de tus paginas y widgets de manera mas eficiente.

Puedes **interactuar** con el estado exactamente como lo harias con un estado normal de Flutter, pero con los beneficios adicionales de NyState.

Veamos como usar NyState.

<div id="how-to-use-nystate"></div>

## Como usar NyState

Puedes comenzar a usar esta clase extendiendola.

Ejemplo

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

El metodo `init` se usa para inicializar el estado de la pagina. Puedes usar este metodo como async o sin async y, detras de escena, manejara la llamada asincrona y mostrara un cargador.

El metodo `view` se usa para mostrar la interfaz de usuario de la pagina.

#### Crear un nuevo widget con estado usando NyState

Para crear una nueva pagina en {{ config('app.name') }}, puedes ejecutar el siguiente comando.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Estilo de carga

Puedes usar la propiedad `loadingStyle` para establecer el estilo de carga de tu pagina.

Ejemplo

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simular una llamada de red por 3 segundos
  };
```

El `loadingStyle` **por defecto** sera tu Widget de carga (resources/widgets/loader_widget.dart).
Puedes personalizar el `loadingStyle` para actualizar el estilo de carga.

Aqui hay una tabla con los diferentes estilos de carga que puedes usar:
// normal, skeletonizer, none

| Estilo | Descripcion |
| --- | --- |
| normal | Estilo de carga por defecto |
| skeletonizer | Estilo de carga con esqueleto |
| none | Sin estilo de carga |

Puedes cambiar el estilo de carga asi:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// o
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Si deseas actualizar el Widget de carga en uno de los estilos, puedes pasar un `child` al `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// igual para skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Ahora, cuando la pestaña este cargando, se mostrara el texto "Loading...".

Ejemplo a continuacion:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // simular una llamada de red por 3 segundos
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

## Acciones de estado

En Nylo, puedes definir pequenas **acciones** en tus Widgets que pueden ser llamadas desde otras clases. Esto es util si deseas actualizar el estado de un widget desde otra clase.

Primero, debes **definir** tus acciones en tu widget. Esto funciona para `NyState` y `NyPage`.

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // manejar como quieres inicializar el estado
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // Ejemplo con datos
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

Luego, puedes llamar a la accion desde otra clase usando el metodo `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Otro ejemplo con datos
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Otro ejemplo con datos
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Si estas usando stateActions con un `NyPage`, debes usar la **ruta** de la pagina.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Otro ejemplo con datos
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Otro ejemplo con datos
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Tambien existe otra clase llamada `StateAction`, que tiene algunos metodos que puedes usar para actualizar el estado de tus widgets.

- `refreshPage` - Refrescar la pagina.
- `pop` - Sacar la pagina de la pila.
- `showToastSorry` - Mostrar una notificacion toast de disculpa.
- `showToastWarning` - Mostrar una notificacion toast de advertencia.
- `showToastInfo` - Mostrar una notificacion toast informativa.
- `showToastDanger` - Mostrar una notificacion toast de peligro.
- `showToastOops` - Mostrar una notificacion toast de error.
- `showToastSuccess` - Mostrar una notificacion toast de exito.
- `showToastCustom` - Mostrar una notificacion toast personalizada.
- `validate` - Validar datos de tu widget.
- `changeLanguage` - Actualizar el idioma en la aplicacion.
- `confirmAction` - Realizar una accion de confirmacion.

Ejemplo

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

Puedes usar la clase `StateAction` para actualizar el estado de cualquier pagina/widget en tu aplicacion siempre que el widget este gestionado por estado.

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

Este metodo volvera a ejecutar el metodo `init` en tu estado. Es util si deseas refrescar los datos en la pagina.

Ejemplo
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
                reboot(); // refrescar los datos
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

`pop` - Eliminar la pagina actual de la pila.

Ejemplo

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

Mostrar una notificacion toast en el contexto.

Ejemplo

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

El helper `validate` realiza una verificacion de validacion en los datos.

Puedes aprender mas sobre el validador <a href="/docs/{{$version}}/validation" target="_BLANK">aqui</a>.

Ejemplo

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

Puedes llamar a `changeLanguage` para cambiar el archivo json **/lang** utilizado en el dispositivo.

Aprende mas sobre la localizacion <a href="/docs/{{$version}}/localization" target="_BLANK">aqui</a>.

Ejemplo

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

Puedes usar `whenEnv` para ejecutar una funcion cuando tu aplicacion esta en un cierto estado.
Ej. tu variable **APP_ENV** dentro de tu archivo `.env` esta configurada como 'developing', `APP_ENV=developing`.

Ejemplo

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

Este metodo bloqueara el estado despues de que se llame una funcion, solo hasta que el metodo haya terminado permitira al usuario realizar solicitudes posteriores. Este metodo tambien actualizara el estado, usa `isLocked` para verificar.

El mejor ejemplo para mostrar `lockRelease` es imaginar que tenemos una pantalla de inicio de sesion cuando el usuario toca 'Login'. Queremos realizar una llamada asincrona para iniciar sesion del usuario pero no queremos que el metodo se llame multiples veces ya que podria crear una experiencia no deseada.

Aqui hay un ejemplo a continuacion.

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

Una vez que tocas el metodo **_login**, bloqueara cualquier solicitud posterior hasta que la solicitud original haya terminado. El helper `isLocked('login_to_app')` se usa para verificar si el boton esta bloqueado. En el ejemplo anterior, puedes ver que lo usamos para determinar cuando mostrar nuestro Widget de carga.

<div id="is-locked"></div>

### isLocked

Este metodo verificara si el estado esta bloqueado usando el helper [`lockRelease`](#lock-release).

Ejemplo
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

El metodo `view` se usa para mostrar la interfaz de usuario de la pagina.

Ejemplo
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

El metodo `confirmAction` mostrara un dialogo al usuario para confirmar una accion.
Este metodo es util si deseas que el usuario confirme una accion antes de continuar.

Ejemplo

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

El metodo `showToastSuccess` mostrara una notificacion toast de exito al usuario.

Ejemplo
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

El metodo `showToastOops` mostrara una notificacion toast de error al usuario.

Ejemplo
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

El metodo `showToastDanger` mostrara una notificacion toast de peligro al usuario.

Ejemplo
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

El metodo `showToastInfo` mostrara una notificacion toast informativa al usuario.

Ejemplo
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

El metodo `showToastWarning` mostrara una notificacion toast de advertencia al usuario.

Ejemplo
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

El metodo `showToastSorry` mostrara una notificacion toast de disculpa al usuario.

Ejemplo
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

El metodo `isLoading` verificara si el estado esta cargando.

Ejemplo
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

El metodo `afterLoad` se puede usar para mostrar un cargador hasta que el estado haya terminado de 'cargar'.

Tambien puedes verificar otras claves de carga usando el parametro **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Ejemplo
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

El metodo `afterNotLocked` verificara si el estado esta bloqueado.

Si el estado esta bloqueado, mostrara el widget de [carga].

Ejemplo
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

Puedes usar `afterNotNull` para mostrar un widget de carga hasta que una variable haya sido establecida.

Imagina que necesitas obtener la cuenta de un usuario de una base de datos usando una llamada Future que podria tomar 1-2 segundos, puedes usar afterNotNull en ese valor hasta que tengas los datos.

Ejemplo

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // ejemplo
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

Puedes cambiar a un estado de 'carga' usando `setLoading`.

El primer parametro acepta un `bool` para indicar si esta cargando o no, el siguiente parametro permite establecer un nombre para el estado de carga, ej. `setLoading(true, name: 'refreshing_content');`.

Ejemplo
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
