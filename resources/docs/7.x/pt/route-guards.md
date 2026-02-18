# Route Guards

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Criando um Route Guard](#creating-a-route-guard "Criando um Route Guard")
- [Ciclo de Vida do Guard](#guard-lifecycle "Ciclo de Vida do Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Ações do Guard](#guard-actions "Ações do Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Aplicando Guards às Rotas](#applying-guards "Aplicando Guards às Rotas")
- [Guards de Grupo](#group-guards "Guards de Grupo")
- [Composição de Guards](#guard-composition "Composição de Guards")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Exemplos](#examples "Exemplos Práticos")

<div id="introduction"></div>

## Introdução

Os route guards fornecem **middleware para navegação** no {{ config('app.name') }}. Eles interceptam transições de rotas e permitem que você controle se um usuário pode acessar uma página, redirecioná-lo para outro lugar ou modificar os dados passados para uma rota.

Casos de uso comuns incluem:
- **Verificações de autenticação** -- redirecionar usuários não autenticados para uma página de login
- **Acesso baseado em papéis** -- restringir páginas a usuários administradores
- **Validação de dados** -- garantir que dados necessários existam antes da navegação
- **Enriquecimento de dados** -- anexar dados adicionais a uma rota

Os guards são executados **em ordem** antes da navegação ocorrer. Se qualquer guard retornar `handled`, a navegação para (redirecionando ou abortando).

<div id="creating-a-route-guard"></div>

## Criando um Route Guard

Crie um route guard usando o Metro CLI:

``` bash
metro make:route_guard auth
```

Isso gera um arquivo de guard:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Add your guard logic here
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Ciclo de Vida do Guard

Todo route guard possui três métodos de ciclo de vida:

<div id="on-before"></div>

### onBefore

Chamado **antes** da navegação ocorrer. É aqui que você verifica condições e decide se permite, redireciona ou aborta a navegação.

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

Valores de retorno:
- `next()` -- continuar para o próximo guard ou navegar para a rota
- `redirect(path)` -- redirecionar para uma rota diferente
- `abort()` -- cancelar a navegação completamente

<div id="on-after"></div>

### onAfter

Chamado **após** a navegação bem-sucedida. Use para analytics, logging ou efeitos colaterais pós-navegação.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Chamado quando o usuário está **saindo** de uma rota. Retorne `false` para impedir que o usuário saia.

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Show confirmation dialog
    return await showConfirmDialog();
  }
  return true; // Allow leaving
}
```

<div id="route-context"></div>

## RouteContext

O objeto `RouteContext` é passado para todos os métodos de ciclo de vida do guard e contém informações sobre a navegação:

| Propriedade | Tipo | Descrição |
|----------|------|-------------|
| `context` | `BuildContext?` | Contexto de construção atual |
| `data` | `dynamic` | Dados passados para a rota |
| `queryParameters` | `Map<String, String>` | Parâmetros de consulta da URL |
| `routeName` | `String` | Nome/caminho da rota de destino |
| `originalRouteName` | `String?` | Nome original da rota antes de transformações |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Access route information
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Transformando o RouteContext

Crie uma cópia com dados diferentes:

``` dart
// Change the data type
RouteContext<User> userContext = context.withData<User>(currentUser);

// Copy with modified fields
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Ações do Guard

<div id="next"></div>

### next

Continuar para o próximo guard na cadeia, ou navegar para a rota se este for o último guard:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Redirecionar o usuário para uma rota diferente:

``` dart
return redirect(LoginPage.path);
```

Com opções adicionais:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `path` | `Object` | obrigatório | String do caminho da rota ou RouteView |
| `data` | `dynamic` | null | Dados para passar à rota de redirecionamento |
| `queryParameters` | `Map<String, dynamic>?` | null | Parâmetros de consulta |
| `navigationType` | `NavigationType` | `pushReplace` | Método de navegação |
| `result` | `dynamic` | null | Resultado a retornar |
| `removeUntilPredicate` | `Function?` | null | Predicado de remoção de rota |
| `transitionType` | `TransitionType?` | null | Tipo de transição de página |
| `onPop` | `Function(dynamic)?` | null | Callback ao voltar |

<div id="abort"></div>

### abort

Cancelar a navegação sem redirecionar. O usuário permanece na página atual:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Modificar os dados que serão passados para os guards subsequentes e a rota de destino:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Enrich the route data
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## Aplicando Guards às Rotas

Adicione guards a rotas individuais no seu arquivo de rotas:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Add a single guard
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Add multiple guards (executed in order)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Guards de Grupo

Aplique guards a múltiplas rotas de uma vez usando grupos de rotas:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // All routes in this group require authentication
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## Composição de Guards

{{ config('app.name') }} fornece ferramentas para compor guards juntos em padrões reutilizáveis.

<div id="guard-stack"></div>

### GuardStack

Combine múltiplos guards em um único guard reutilizável:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Use the stack on a route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

O `GuardStack` executa guards em ordem. Se qualquer guard retornar `handled`, os guards restantes são ignorados.

<div id="conditional-guard"></div>

### ConditionalGuard

Aplique um guard apenas quando uma condição for verdadeira:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

Se a condição retornar `false`, o guard é ignorado e a navegação continua.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Crie guards que aceitam parâmetros de configuração:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = allowed roles

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Usage
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## Exemplos

### Guard de Autenticação

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### Guard de Assinatura com Parâmetros

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// Require premium or pro subscription
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Guard de Logging

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
