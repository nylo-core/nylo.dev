# Controllers

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos controllers")
- [Criando Controllers](#creating-controllers "Criando controllers")
- [Usando Controllers](#using-controllers "Usando controllers")
- Funcionalidades dos Controllers
  - [Acessando Dados da Rota](#accessing-route-data "Acessando dados da rota")
  - [Parâmetros de Query](#query-parameters "Parâmetros de query")
  - [Gerenciamento de Estado da Página](#page-state-management "Gerenciamento de estado da página")
  - [Notificações Toast](#toast-notifications "Notificações toast")
  - [Validação de Formulário](#form-validation "Validação de formulário")
  - [Troca de Idioma](#language-switching "Troca de idioma")
  - [Lock Release](#lock-release "Lock release")
  - [Confirmar Ações](#confirm-actions "Confirmar ações")
- [Controllers Singleton](#singleton-controllers "Controllers singleton")
- [Decoders de Controllers](#controller-decoders "Decoders de controllers")
- [Route Guards](#route-guards "Route guards")

<div id="introduction"></div>

## Introdução

Controllers no {{ config('app.name') }} v7 atuam como coordenadores entre suas views (páginas) e a lógica de negócios. Eles lidam com a entrada do usuário, gerenciam atualizações de estado e proporcionam uma separação limpa de responsabilidades.

{{ config('app.name') }} v7 introduz a classe `NyController` com métodos integrados poderosos para notificações toast, validação de formulário, gerenciamento de estado e muito mais.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Inicializar serviços ou buscar dados
  }

  void onTapProfile() {
    routeTo(ProfilePage.path);
  }

  void submitForm() {
    validate(
      rules: {"email": "email"},
      onSuccess: () => showToastSuccess(description: "Form submitted!"),
    );
  }
}
```

<div id="creating-controllers"></div>

## Criando Controllers

Use o Metro CLI para gerar controllers:

``` bash
# Criar uma página com um controller
metro make:page dashboard --controller
# ou abreviado
metro make:page dashboard -c

# Criar apenas um controller
metro make:controller profile_controller
```

Isso cria:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Página**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Usando Controllers

Conecte um controller à sua página especificando-o como tipo genérico no `NyStatefulWidget`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';

class HomePage extends NyStatefulWidget<HomeController> {

  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {

  @override
  get init => () async {
    // Acessar métodos do controller
    widget.controller.fetchData();
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Home")),
      body: Column(
        children: [
          ElevatedButton(
            onPressed: widget.controller.onTapProfile,
            child: Text("View Profile"),
          ),
          TextField(
            controller: widget.controller.nameController,
          ),
        ],
      ),
    );
  }
}
```

<div id="accessing-route-data"></div>

## Acessando Dados da Rota

Passe dados entre páginas e acesse-os no seu controller:

``` dart
// Navegar com dados
routeTo(ProfilePage.path, data: {"userId": 123});

// No seu controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Obter os dados passados
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

Ou acesse os dados diretamente no estado da sua página:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // A partir do controller
    var userData = widget.controller.data();

    // Ou diretamente do widget
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Parâmetros de Query

Acesse parâmetros de query da URL no seu controller:

``` dart
// Navegar para: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// No seu controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Obter todos os parâmetros de query como Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Obter um parâmetro específico
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Verificar se um parâmetro de query existe:

``` dart
// Na sua página
if (widget.hasQueryParameter("tab")) {
  // Tratar o parâmetro tab
}
```

<div id="page-state-management"></div>

## Gerenciamento de Estado da Página

Controllers podem gerenciar o estado da página diretamente:

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // Acionar um setState na página
    setState(setState: () {});
  }

  void refresh() {
    // Atualizar a página inteira
    refreshPage();
  }

  void goBack() {
    // Fechar a página com resultado opcional
    pop(result: {"updated": true});
  }

  void updateCustomState() {
    // Enviar ação personalizada para a página
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## Notificações Toast

Controllers incluem métodos integrados de notificação toast:

``` dart
class FormController extends NyController {

  void showNotifications() {
    // Toast de sucesso
    showToastSuccess(description: "Profile updated!");

    // Toast de aviso
    showToastWarning(description: "Please check your input");

    // Toast de erro/perigo
    showToastDanger(description: "Failed to save changes");

    // Toast informativo
    showToastInfo(description: "New features available");

    // Toast de desculpa
    showToastSorry(description: "We couldn't process your request");

    // Toast de oops
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Toast personalizado com título
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Usar estilo de toast personalizado (registrado no Nylo)
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## Validação de Formulário

Valide dados de formulário diretamente do seu controller:

``` dart
class RegisterController extends NyController {

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  void submitRegistration() {
    validate(
      rules: {
        "email": "email|max:50",
        "password": "min:8|max:64",
      },
      data: {
        "email": emailController.text,
        "password": passwordController.text,
      },
      messages: {
        "email.email": "Please enter a valid email",
        "password.min": "Password must be at least 8 characters",
      },
      showAlert: true,
      alertStyle: 'warning',
      onSuccess: () {
        // Validação passou
        _performRegistration();
      },
      onFailure: (exception) {
        // Validação falhou
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Lógica de registro
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## Troca de Idioma

Altere o idioma do aplicativo a partir do seu controller:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es', restartState: true);
  }

  void switchToEnglish() {
    changeLanguage('en', restartState: true);
  }
}
```

<div id="lock-release"></div>

## Lock Release

Previna múltiplos toques rápidos em botões:

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // Este código só executa uma vez até o lock ser liberado
      await processPayment();
      showToastSuccess(description: "Payment complete!");
    });
  }

  void onTapWithoutSetState() {
    lockRelease(
      "my_lock",
      perform: () async {
        await someAsyncOperation();
      },
      shouldSetState: false, // Não acionar setState depois
    );
  }
}
```

<div id="confirm-actions"></div>

## Confirmar Ações

Exiba um diálogo de confirmação antes de executar ações destrutivas:

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // Usuário confirmou - executar exclusão
        await deleteAccount();
        showToastSuccess(description: "Account deleted");
      },
      title: "Delete Account?",
      dismissText: "Cancel",
    );
  }
}
```

<div id="singleton-controllers"></div>

## Controllers Singleton

Faça um controller persistir no aplicativo como singleton:

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // Lógica de login
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

Controllers singleton são criados uma vez e reutilizados durante todo o ciclo de vida do aplicativo.

<div id="controller-decoders"></div>

## Decoders de Controllers

Registre seus controllers em `lib/config/decoders.dart`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';
import '/app/controllers/profile_controller.dart';
import '/app/controllers/auth_controller.dart';

final Map<Type, BaseController Function()> controllers = {
  HomeController: () => HomeController(),
  ProfileController: () => ProfileController(),
  AuthController: () => AuthController(),
};
```

Este mapa permite que o {{ config('app.name') }} resolva controllers quando as páginas são carregadas.

<div id="route-guards"></div>

## Route Guards

Controllers podem definir route guards que são executados antes da página carregar:

``` dart
class AdminController extends NyController {

  @override
  List<RouteGuard> get routeGuards => [
    AuthRouteGuard(),
    AdminRoleGuard(),
  ];

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Só executa se todos os guards passarem
  }
}
```

Consulte a [documentação do Router](/docs/7.x/router) para mais detalhes sobre route guards.
