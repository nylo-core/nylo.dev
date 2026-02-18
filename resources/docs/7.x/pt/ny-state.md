# NyState

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Como usar o NyState](#how-to-use-nystate "Como usar o NyState")
- [Estilo de Carregamento](#loading-style "Estilo de Carregamento")
- [Acoes de Estado](#state-actions "Acoes de Estado")
- [Helpers](#helpers "Helpers")


<div id="introduction"></div>

## Introducao

`NyState` e uma versao estendida da classe padrao `State` do Flutter. Ela fornece funcionalidades adicionais para ajudar a gerenciar o estado das suas paginas e widgets de forma mais eficiente.

Voce pode **interagir** com o estado exatamente como faria com um state normal do Flutter, mas com os beneficios adicionais do NyState.

Vamos ver como usar o NyState.

<div id="how-to-use-nystate"></div>

## Como usar o NyState

Voce pode comecar a usar esta classe estendendo-a.

Exemplo

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

O metodo `init` e usado para inicializar o estado da pagina. Voce pode usar este metodo como async ou sem async e, nos bastidores, ele ira lidar com a chamada assincrona e exibir um loader.

O metodo `view` e usado para exibir a interface da pagina.

#### Criando um novo stateful widget com NyState

Para criar uma nova pagina no {{ config('app.name') }}, voce pode executar o comando abaixo.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Estilo de Carregamento

Voce pode usar a propriedade `loadingStyle` para definir o estilo de carregamento da sua pagina.

Exemplo

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

O `loadingStyle` **padrao** sera o seu Widget de carregamento (resources/widgets/loader_widget.dart).
Voce pode personalizar o `loadingStyle` para atualizar o estilo de carregamento.

Aqui esta uma tabela com os diferentes estilos de carregamento que voce pode usar:

| Estilo | Descricao |
| --- | --- |
| normal | Estilo de carregamento padrao |
| skeletonizer | Estilo de carregamento skeleton |
| none | Sem estilo de carregamento |

Voce pode alterar o estilo de carregamento assim:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Se voce quiser atualizar o Widget de carregamento em um dos estilos, voce pode passar um `child` para o `LoadingStyle`.

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

Agora, quando a aba estiver carregando, o texto "Loading..." sera exibido.

Exemplo abaixo:

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

## Acoes de Estado

No Nylo, voce pode definir pequenas **acoes** nos seus Widgets que podem ser chamadas de outras classes. Isso e util se voce quiser atualizar o estado de um widget a partir de outra classe.

Primeiro, voce deve **definir** suas acoes no seu widget. Isso funciona para `NyState` e `NyPage`.

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

Depois, voce pode chamar a acao de outra classe usando o metodo `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Se voce estiver usando stateActions com um `NyPage`, voce deve usar o **path** da pagina.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Existe tambem outra classe chamada `StateAction`, que possui alguns metodos que voce pode usar para atualizar o estado dos seus widgets.

- `refreshPage` - Atualizar a pagina.
- `pop` - Remover a pagina.
- `showToastSorry` - Exibir uma notificacao toast de desculpa.
- `showToastWarning` - Exibir uma notificacao toast de aviso.
- `showToastInfo` - Exibir uma notificacao toast informativa.
- `showToastDanger` - Exibir uma notificacao toast de perigo.
- `showToastOops` - Exibir uma notificacao toast de erro.
- `showToastSuccess` - Exibir uma notificacao toast de sucesso.
- `showToastCustom` - Exibir uma notificacao toast personalizada.
- `validate` - Validar dados do seu widget.
- `changeLanguage` - Atualizar o idioma no aplicativo.
- `confirmAction` - Executar uma acao de confirmacao.

Exemplo

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

Voce pode usar a classe `StateAction` para atualizar o estado de qualquer pagina/widget no seu aplicativo, desde que o widget seja gerenciado por estado.

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

Este metodo ira re-executar o metodo `init` no seu state. E util se voce quiser atualizar os dados na pagina.

Exemplo
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

`pop` - Remove a pagina atual da pilha.

Exemplo

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

Exibe uma notificacao toast no contexto.

Exemplo

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

O helper `validate` realiza uma verificacao de validacao nos dados.

Voce pode aprender mais sobre o validador <a href="/docs/{{$version}}/validation" target="_BLANK">aqui</a>.

Exemplo

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

Voce pode chamar `changeLanguage` para alterar o arquivo JSON **/lang** usado no dispositivo.

Saiba mais sobre localizacao <a href="/docs/{{$version}}/localization" target="_BLANK">aqui</a>.

Exemplo

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

Voce pode usar `whenEnv` para executar uma funcao quando seu aplicativo estiver em um determinado estado.
Ex.: sua variavel **APP_ENV** dentro do seu arquivo `.env` esta definida como 'developing', `APP_ENV=developing`.

Exemplo

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

Este metodo ira bloquear o estado apos uma funcao ser chamada, somente quando o metodo tiver terminado ira permitir que o usuario faca requisicoes subsequentes. Este metodo tambem atualizara o estado, use `isLocked` para verificar.

O melhor exemplo para demonstrar `lockRelease` e imaginar que temos uma tela de login quando o usuario toca em 'Login'. Queremos realizar uma chamada assincrona para fazer login do usuario, mas nao queremos que o metodo seja chamado multiplas vezes, pois isso poderia criar uma experiencia indesejada.

Aqui esta um exemplo abaixo.

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

Ao tocar no metodo **_login**, ele bloqueara quaisquer requisicoes subsequentes ate que a requisicao original tenha terminado. O helper `isLocked('login_to_app')` e usado para verificar se o botao esta bloqueado. No exemplo acima, voce pode ver que usamos isso para determinar quando exibir nosso Widget de carregamento.

<div id="is-locked"></div>

### isLocked

Este metodo verifica se o estado esta bloqueado usando o helper [`lockRelease`](#lock-release).

Exemplo
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

O metodo `view` e usado para exibir a interface da pagina.

Exemplo
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

O metodo `confirmAction` exibira um dialogo para o usuario confirmar uma acao.
Este metodo e util se voce quiser que o usuario confirme uma acao antes de prosseguir.

Exemplo

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

O metodo `showToastSuccess` exibira uma notificacao toast de sucesso para o usuario.

Exemplo
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

O metodo `showToastOops` exibira uma notificacao toast de erro para o usuario.

Exemplo
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

O metodo `showToastDanger` exibira uma notificacao toast de perigo para o usuario.

Exemplo
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

O metodo `showToastInfo` exibira uma notificacao toast informativa para o usuario.

Exemplo
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

O metodo `showToastWarning` exibira uma notificacao toast de aviso para o usuario.

Exemplo
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

O metodo `showToastSorry` exibira uma notificacao toast de desculpa para o usuario.

Exemplo
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

O metodo `isLoading` verifica se o estado esta carregando.

Exemplo
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

O metodo `afterLoad` pode ser usado para exibir um loader ate que o estado tenha terminado de 'carregar'.

Voce tambem pode verificar outras chaves de carregamento usando o parametro **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Exemplo
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

O metodo `afterNotLocked` verifica se o estado esta bloqueado.

Se o estado estiver bloqueado, ele exibira o widget de [carregamento].

Exemplo
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

Voce pode usar `afterNotNull` para exibir um widget de carregamento ate que uma variavel tenha sido definida.

Imagine que voce precisa buscar a conta de um usuario de um banco de dados usando uma chamada Future que pode levar 1-2 segundos, voce pode usar afterNotNull nesse valor ate ter os dados.

Exemplo

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

Voce pode mudar para um estado de 'carregamento' usando `setLoading`.

O primeiro parametro aceita um `bool` para indicar se esta carregando ou nao, o proximo parametro permite definir um nome para o estado de carregamento, ex.: `setLoading(true, name: 'refreshing_content');`.

Exemplo
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
