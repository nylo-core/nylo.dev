# Router

---

<a name="section-1"></a>

- [Introdução](#introduction "Introdução")
- Básico
  - [Adicionando rotas](#adding-routes "Adicionando rotas")
  - [Navegando para páginas](#navigating-to-pages "Navegando para páginas")
  - [Rota inicial](#initial-route "Rota inicial")
  - [Preview Route](#preview-route "Preview Route")
  - [Rota autenticada](#authenticated-route "Rota autenticada")
  - [Rota Desconhecida](#unknown-route "Rota desconhecida")
- Enviando dados para outra página
  - [Passando dados para outra página](#passing-data-to-another-page "Passando dados para outra página")
- Navegação
  - [Tipos de navegação](#navigation-types "Tipos de navegação")
  - [Navegando de volta](#navigating-back "Navegando de volta")
  - [Navegação Condicional](#conditional-navigation "Navegação condicional")
  - [Transições de página](#page-transitions "Transições de página")
  - [Histórico de Rotas](#route-history "Histórico de rotas")
  - [Atualizar Pilha de Rotas](#update-route-stack "Atualizar pilha de rotas")
- Parâmetros de rota
  - [Usando Parâmetros de Rota](#route-parameters "Parâmetros de rota")
  - [Parâmetros de Query](#query-parameters "Parâmetros de query")
- Route Guards
  - [Criando Route Guards](#route-guards "Route Guards")
  - [Ciclo de Vida do NyRouteGuard](#nyroute-guard-lifecycle "Ciclo de vida do NyRouteGuard")
  - [Métodos Auxiliares de Guards](#guard-helper-methods "Métodos auxiliares de guards")
  - [Guards Parametrizados](#parameterized-guards "Guards parametrizados")
  - [Pilhas de Guards](#guard-stacks "Pilhas de guards")
  - [Guards Condicionais](#conditional-guards "Guards condicionais")
- Grupos de Rotas
  - [Grupos de Rotas](#route-groups "Grupos de rotas")
- [Deep linking](#deep-linking "Deep linking")
- [Avançado](#advanced "Avançado")



<div id="introduction"></div>

## Introdução

Rotas permitem que você defina as diferentes páginas no seu app e navegue entre elas.

Use rotas quando você precisar:
- Definir as páginas disponíveis no seu app
- Navegar usuários entre telas
- Proteger páginas com autenticação
- Passar dados de uma página para outra
- Tratar deep links de URLs

Você pode adicionar rotas dentro do arquivo `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // adicionar mais rotas
  // router.add(AccountPage.path);

});
```

> **Dica:** Você pode criar suas rotas manualmente ou usar a ferramenta CLI <a href="/docs/{{ $version }}/metro">Metro</a> para criá-las automaticamente.

Aqui está um exemplo de criação de uma página 'account' usando Metro.

``` bash
metro make:page account_page
```

``` dart
// Adiciona sua nova rota automaticamente em /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Você também pode precisar passar dados de uma view para outra. No {{ config('app.name') }}, isso é possível usando o `NyStatefulWidget` (um stateful widget com acesso integrado aos dados da rota). Vamos nos aprofundar nisso para explicar como funciona.


<div id="adding-routes"></div>

## Adicionando rotas

Esta é a maneira mais fácil de adicionar novas rotas ao seu projeto.

Execute o comando abaixo para criar uma nova página.

```bash
metro make:page profile_page
```

Após executar o comando acima, ele criará um novo Widget chamado `ProfilePage` e o adicionará ao diretório `resources/pages/`.
Ele também adicionará a nova rota ao arquivo `lib/routes/router.dart`.

Arquivo: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Minha nova rota
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Navegando para páginas

Você pode navegar para novas páginas usando o helper `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Rota inicial

Nos seus routers, você pode definir a primeira página que deve carregar usando o método `.initialRoute()`.

Uma vez que você definiu a rota inicial, ela será a primeira página a carregar quando você abrir o app.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // nova rota inicial
});
```


### Rota Inicial Condicional

Você também pode definir uma rota inicial condicional usando o parâmetro `when`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### Navegar para a Rota Inicial

Use `routeToInitial()` para navegar para a rota inicial do app:

``` dart
void _goHome() {
    routeToInitial();
}
```

Isso navegará para a rota marcada com `.initialRoute()` e limpará a pilha de navegação.

<div id="preview-route"></div>

## Preview Route

Durante o desenvolvimento, você pode querer visualizar rapidamente uma página específica sem alterar sua rota inicial permanentemente. Use `.previewRoute()` para temporariamente tornar qualquer rota a rota inicial:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // Esta será mostrada primeiro durante o desenvolvimento
});
```

O método `previewRoute()`:
- Sobrescreve qualquer configuração existente de `initialRoute()` e `authenticatedRoute()`
- Torna a rota especificada a rota inicial
- Útil para testar rapidamente páginas específicas durante o desenvolvimento

> **Aviso:** Lembre-se de remover `.previewRoute()` antes de lançar seu app!

<div id="authenticated-route"></div>

## Rota Autenticada

No seu app, você pode definir uma rota para ser a rota inicial quando um usuário está autenticado.
Isso automaticamente sobrescreverá a rota inicial padrão e será a primeira página que o usuário vê quando faz login.

Primeiro, seu usuário deve estar logado usando o helper `Auth.authenticate({...})`.

Agora, quando eles abrirem o app, a rota que você definiu será a página padrão até que eles façam logout.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // página de autenticação
});
```

### Rota Autenticada Condicional

Você também pode definir uma rota autenticada condicional:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Navegar para a Rota Autenticada

Você pode navegar para a página autenticada usando o helper `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Veja também:** [Autenticação](/docs/{{ $version }}/authentication) para detalhes sobre autenticação de usuários e gerenciamento de sessões.


<div id="unknown-route"></div>

## Rota Desconhecida

Você pode definir uma rota para tratar cenários 404/não encontrado usando `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Quando um usuário navega para uma rota que não existe, ele será direcionado para a página de rota desconhecida.


<div id="route-guards"></div>

## Route guards

Route guards protegem páginas de acesso não autorizado. Eles são executados antes da navegação ser concluída, permitindo que você redirecione usuários ou bloqueie acesso com base em condições.

Use route guards quando você precisar:
- Proteger páginas de usuários não autenticados
- Verificar permissões antes de permitir acesso
- Redirecionar usuários com base em condições (ex: onboarding não concluído)
- Registrar ou rastrear visualizações de páginas

Para criar um novo Route Guard, execute o comando abaixo.

``` bash
metro make:route_guard dashboard
```

Em seguida, adicione o novo Route Guard à sua rota.

``` dart
// Arquivo: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Adicione seu guard
    ]
  ); // página restrita
});
```

Você também pode definir route guards usando o método `addRouteGuard`:

``` dart
// Arquivo: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // ou adicionar múltiplos guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## Ciclo de Vida do NyRouteGuard

Na v7, route guards usam a classe `NyRouteGuard` com três métodos de ciclo de vida:

- **`onBefore(RouteContext context)`** - Chamado antes da navegação. Retorne `next()` para continuar, `redirect()` para ir para outro lugar, ou `abort()` para parar.
- **`onAfter(RouteContext context)`** - Chamado após navegação bem-sucedida para a rota.

### Exemplo Básico

Arquivo: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Realizar verificação se podem acessar a página
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Rastrear visualização de página após navegação bem-sucedida
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

A classe `RouteContext` fornece acesso a informações de navegação:

| Propriedade | Tipo | Descrição |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context atual |
| `data` | `dynamic` | Dados passados para a rota |
| `queryParameters` | `Map<String, String>` | Parâmetros de query da URL |
| `routeName` | `String` | Nome/caminho da rota |
| `originalRouteName` | `String?` | Nome original da rota antes de transformações |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## Métodos Auxiliares de Guards

### next()

Continuar para o próximo guard ou para a rota:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Permitir que a navegação continue
}
```

### redirect()

Redirecionar para uma rota diferente:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

O método `redirect()` aceita:

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `path` | `Object` | Caminho da rota ou RouteView |
| `data` | `dynamic` | Dados a serem passados para a rota |
| `queryParameters` | `Map<String, dynamic>?` | Parâmetros de query |
| `navigationType` | `NavigationType` | Tipo de navegação (padrão: pushReplace) |
| `transitionType` | `TransitionType?` | Transição de página |
| `onPop` | `Function(dynamic)?` | Callback quando a rota faz pop |

### abort()

Parar a navegação sem redirecionar:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // Usuário permanece na rota atual
  }
  return next();
}
```

### setData()

Modificar dados passados para guards subsequentes e para a rota:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Guards Parametrizados

Use `ParameterizedGuard` quando precisar configurar o comportamento do guard por rota:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Uso:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Pilhas de Guards

Componha múltiplos guards em um único guard reutilizável usando `GuardStack`:

``` dart
// Criar combinações reutilizáveis de guards
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Guards Condicionais

Aplique guards condicionalmente com base em um predicado:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Passando dados para outra página

Nesta seção, mostraremos como você pode passar dados de um widget para outro.

A partir do seu Widget, use o helper `routeTo` e passe os `data` que deseja enviar para a nova página.

``` dart
// Widget HomePage
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// Widget SettingsPage (outra página)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // ou
    print(data()); // Hello World
  };
```

Mais exemplos

``` dart
// Widget da página Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Widget da página Profile (outra página)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Grupos de Rotas

Grupos de rotas organizam rotas relacionadas e aplicam configurações compartilhadas. Eles são úteis quando múltiplas rotas precisam dos mesmos guards, prefixo de URL ou estilo de transição.

Use grupos de rotas quando você precisar:
- Aplicar o mesmo route guard a múltiplas páginas
- Adicionar um prefixo de URL a um conjunto de rotas (ex: `/admin/...`)
- Definir a mesma transição de página para rotas relacionadas

Você pode definir um grupo de rotas como no exemplo abaixo.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### Configurações opcionais para grupos de rotas são:

| Configuração | Tipo | Descrição |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Aplicar route guards a todas as rotas do grupo |
| `prefix` | `String` | Adicionar um prefixo a todos os caminhos de rota do grupo |
| `transition_type` | `TransitionType` | Definir transição para todas as rotas do grupo |
| `transition` | `PageTransitionType` | Definir tipo de transição de página (descontinuado, use transition_type) |
| `transition_settings` | `PageTransitionSettings` | Definir configurações de transição |


<div id="route-parameters"></div>

## Usando Parâmetros de Rota

Quando você cria uma nova página, pode atualizar a rota para aceitar parâmetros.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Agora, quando você navegar para a página, pode passar o `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Você pode acessar os parâmetros na nova página assim.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Parâmetros de Query

Ao navegar para uma nova página, você também pode fornecer parâmetros de query.

Vamos ver.

```dart
  // Página Home
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // navegar para a página de perfil

  ...

  // Página Profile
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // ou
    print(queryParameters()); // {"user": 7}
  };
```

> **Nota:** Desde que o widget da sua página estenda `NyStatefulWidget` e a classe `NyPage`, você pode chamar `widget.queryParameters()` para buscar todos os parâmetros de query do nome da rota.

```dart
// Exemplo de página
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Página Home
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // ou
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Dica:** Parâmetros de query devem seguir o protocolo HTTP, ex: /account?userId=1&tab=2


<div id="page-transitions"></div>

## Transições de Página

Você pode adicionar transições ao navegar de uma página modificando seu arquivo `router.dart`.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### Transições de Página Disponíveis

#### Transições Básicas
- **`TransitionType.fade()`** - Faz fade in da nova página enquanto faz fade out da página antiga
- **`TransitionType.theme()`** - Usa o tema de transição de página do app

#### Transições de Deslizamento Direcional
- **`TransitionType.rightToLeft()`** - Desliza da borda direita da tela
- **`TransitionType.leftToRight()`** - Desliza da borda esquerda da tela
- **`TransitionType.topToBottom()`** - Desliza da borda superior da tela
- **`TransitionType.bottomToTop()`** - Desliza da borda inferior da tela

#### Transições de Deslizamento com Fade
- **`TransitionType.rightToLeftWithFade()`** - Desliza e faz fade da borda direita
- **`TransitionType.leftToRightWithFade()`** - Desliza e faz fade da borda esquerda

#### Transições de Transformação
- **`TransitionType.scale(alignment: ...)`** - Escala a partir de um ponto de alinhamento especificado
- **`TransitionType.rotate(alignment: ...)`** - Rotaciona ao redor de um ponto de alinhamento especificado
- **`TransitionType.size(alignment: ...)`** - Cresce a partir de um ponto de alinhamento especificado

#### Transições Unidas (Requer widget atual)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Página atual sai pela direita enquanto nova página entra pela esquerda
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Página atual sai pela esquerda enquanto nova página entra pela direita
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Página atual sai para baixo enquanto nova página entra por cima
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Página atual sai para cima enquanto nova página entra por baixo

#### Transições Pop (Requer widget atual)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Página atual sai para a direita, nova página fica no lugar
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Página atual sai para a esquerda, nova página fica no lugar
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Página atual sai para baixo, nova página fica no lugar
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Página atual sai para cima, nova página fica no lugar

#### Transições de Eixo Compartilhado do Material Design
- **`TransitionType.sharedAxisHorizontal()`** - Transição de deslizamento e fade horizontal
- **`TransitionType.sharedAxisVertical()`** - Transição de deslizamento e fade vertical
- **`TransitionType.sharedAxisScale()`** - Transição de escala e fade

#### Parâmetros de Personalização
Cada transição aceita os seguintes parâmetros opcionais:

| Parâmetro | Descrição | Padrão |
|-----------|-------------|---------|
| `curve` | Curva de animação | Curvas específicas da plataforma |
| `duration` | Duração da animação | Durações específicas da plataforma |
| `reverseDuration` | Duração da animação reversa | Mesma que duration |
| `fullscreenDialog` | Se a rota é um diálogo em tela cheia | `false` |
| `opaque` | Se a rota é opaca | `false` |


``` dart
// Widget da página Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Tipos de Navegação

Ao navegar, você pode especificar um dos seguintes se estiver usando o helper `routeTo`.

| Tipo | Descrição |
|------|-------------|
| `NavigationType.push` | Empurrar uma nova página para a pilha de rotas do seu app |
| `NavigationType.pushReplace` | Substituir a rota atual, descartando a rota anterior quando a nova rota terminar |
| `NavigationType.popAndPushNamed` | Fazer pop da rota atual do navigator e empurrar uma rota nomeada em seu lugar |
| `NavigationType.pushAndRemoveUntil` | Empurrar e remover rotas até que o predicado retorne true |
| `NavigationType.pushAndForgetAll` | Empurrar para uma nova página e descartar quaisquer outras páginas na pilha de rotas |

``` dart
// Widget da página Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## Navegando de volta

Quando você estiver na nova página, pode usar o helper `pop()` para voltar para a página existente.

``` dart
// Widget SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // ou
    Navigator.pop(context);
  }
...
```

Se você quiser retornar um valor para o widget anterior, forneça um `result` como no exemplo abaixo.

``` dart
// Widget SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Obter o valor do widget anterior usando o parâmetro `onPop`
// Widget HomePage
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Navegação Condicional

Use `routeIf()` para navegar apenas quando uma condição for atendida:

``` dart
// Navegar apenas se o usuário estiver logado
routeIf(isLoggedIn, DashboardPage.path);

// Com opções adicionais
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

Se a condição for `false`, nenhuma navegação ocorre.


<div id="route-history"></div>

## Histórico de Rotas

No {{ config('app.name') }}, você pode acessar informações do histórico de rotas usando os helpers abaixo.

``` dart
// Obter histórico de rotas
Nylo.getRouteHistory(); // List<dynamic>

// Obter a rota atual
Nylo.getCurrentRoute(); // Route<dynamic>?

// Obter a rota anterior
Nylo.getPreviousRoute(); // Route<dynamic>?

// Obter o nome da rota atual
Nylo.getCurrentRouteName(); // String?

// Obter o nome da rota anterior
Nylo.getPreviousRouteName(); // String?

// Obter os argumentos da rota atual
Nylo.getCurrentRouteArguments(); // dynamic

// Obter os argumentos da rota anterior
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Atualizar Pilha de Rotas

Você pode atualizar a pilha de navegação programaticamente usando `NyNavigator.updateStack()`:

``` dart
// Atualizar a pilha com uma lista de rotas
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Passar dados para rotas específicas
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | obrigatório | Lista de caminhos de rota para navegar |
| `replace` | `bool` | `true` | Se deve substituir a pilha atual |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Dados a serem passados para rotas específicas |

Isso é útil para:
- Cenários de deep linking
- Restaurar estado de navegação
- Construir fluxos de navegação complexos


<div id="deep-linking"></div>

## Deep Linking

Deep linking permite que usuários naveguem diretamente para conteúdo específico dentro do seu app usando URLs. Isso é útil para:
- Compartilhar links diretos para conteúdo específico do app
- Campanhas de marketing que direcionam funcionalidades específicas no app
- Tratar notificações que devem abrir telas específicas do app
- Transições seamless de web para app

## Configuração

Antes de implementar deep linking no seu app, certifique-se de que seu projeto está configurado corretamente:

### 1. Configuração da Plataforma

**iOS**: Configure universal links no seu projeto Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Guia de Configuração de Universal Links do Flutter</a>

**Android**: Configure app links no seu AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Guia de Configuração de App Links do Flutter</a>

### 2. Defina Suas Rotas

Todas as rotas que devem ser acessíveis via deep links devem estar registradas na configuração do seu router:

```dart
// Arquivo: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Rotas básicas
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Rota com parâmetros
  router.add(HotelBookingPage.path);
});
```

## Usando Deep Links

Uma vez configurado, seu app pode tratar URLs recebidas em vários formatos:

### Deep Links Básicos

Navegação simples para páginas específicas:

``` bash
https://yourdomain.com/profile       // Abre a página de perfil
https://yourdomain.com/settings      // Abre a página de configurações
```

Para acionar essas navegações programaticamente dentro do seu app:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Parâmetros de Caminho

Para rotas que requerem dados dinâmicos como parte do caminho:

#### Definição de Rota

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Definir uma rota com um placeholder de parâmetro {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Acessar o parâmetro do caminho
    final hotelId = queryParameters()["id"]; // Retorna "87" para URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Usar o ID para buscar dados do hotel ou executar operações
  };

  // Restante da implementação da página
}
```

#### Formato da URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Navegação Programática

```dart
// Navegar com parâmetros
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Parâmetros de Query

Para parâmetros opcionais ou quando múltiplos valores dinâmicos são necessários:

#### Formato da URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Acessando Parâmetros de Query

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Obter todos os parâmetros de query
    final params = queryParameters();

    // Acessar parâmetros específicos
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Método de acesso alternativo
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Navegação Programática com Parâmetros de Query

```dart
// Navegar com parâmetros de query
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combinar parâmetros de caminho e query
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Tratando Deep Links

Você pode tratar eventos de deep link no seu `RouteProvider`:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Tratar deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Atualizar a pilha de rotas para deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### Testando Deep Links

Para desenvolvimento e testes, você pode simular a ativação de deep links usando ADB (Android) ou xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulador)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Dicas de Depuração

- Imprima todos os parâmetros no seu método init para verificar a análise correta
- Teste diferentes formatos de URL para garantir que seu app os trate corretamente
- Lembre-se de que parâmetros de query são sempre recebidos como strings, converta-os para o tipo apropriado conforme necessário

---

## Padrões Comuns

### Conversão de Tipo de Parâmetro

Como todos os parâmetros de URL são passados como strings, você frequentemente precisará convertê-los:

```dart
// Convertendo parâmetros string para tipos apropriados
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Parâmetros Opcionais

Trate casos onde parâmetros podem estar ausentes:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Carregar perfil de usuário específico
} else {
  // Carregar perfil do usuário atual
}

// Ou verificar hasQueryParameter
if (hasQueryParameter('status')) {
  // Fazer algo com o parâmetro status
} else {
  // Tratar ausência do parâmetro
}
```


<div id="advanced"></div>

## Avançado

### Verificando se a Rota Existe

Você pode verificar se uma rota está registrada no seu router:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Métodos do NyRouter

A classe `NyRouter` fornece vários métodos úteis:

| Método | Descrição |
|--------|-------------|
| `getRegisteredRouteNames()` | Obter todos os nomes de rotas registradas como lista |
| `getRegisteredRoutes()` | Obter todas as rotas registradas como mapa |
| `containsRoutes(routes)` | Verificar se o router contém todas as rotas especificadas |
| `getInitialRouteName()` | Obter o nome da rota inicial |
| `getAuthRouteName()` | Obter o nome da rota autenticada |
| `getUnknownRouteName()` | Obter o nome da rota desconhecida/404 |

### Obtendo Argumentos de Rota

Você pode obter argumentos de rota usando `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Obter argumentos tipados
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument e NyQueryParameters

Dados passados entre rotas são encapsulados nessas classes:

``` dart
// NyArgument contém dados da rota
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contém parâmetros de query da URL
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
