# Widgets com Estado Gerenciado

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Escolha seu Padrão](#choose-your-pattern "Escolha seu Padrão")
- [Ações de Estado](#state-actions "Ações de Estado")
  - [Definindo Manipuladores](#defining-handlers "Definindo Manipuladores")
  - [Acionando Ações](#triggering-actions "Acionando Ações")
  - [Manipuladores Com e Sem Dados](#handlers-with-and-without-data "Manipuladores Com e Sem Dados")
  - [Usando uma Instância StateActions](#using-a-state-actions-instance "Usando uma Instância StateActions")
- [Padrão: Páginas com Estado Gerenciado (NyPage)](#pattern-ny-page "Padrão: Páginas com Estado Gerenciado")
- [Padrão: Widgets com Estado (NyState)](#pattern-ny-state "Padrão: Widgets com Estado")
- [Padrão: Widgets com Estado Gerenciado (NyStateManaged)](#pattern-ny-state-managed "Padrão: Widgets com Estado Gerenciado")
  - [Avançado: Múltiplas Instâncias Isoladas](#advanced-multiple-isolated-instances "Avançado: Múltiplas Instâncias Isoladas")
- [Referência do Ciclo de Vida](#lifecycle "Referência do Ciclo de Vida")

<div id="introduction"></div>

## Introdução

Widgets com estado gerenciado permitem que você atualize partes específicas da sua UI de qualquer lugar no app — sem reconstruir páginas inteiras. No {{ config('app.name') }} v7, você aciona **ações de estado** nomeadas em um widget ou página alvo, e o manipulador correspondente é executado.

O modelo mental é simples:

- Cada widget ou página com estado gerenciado tem uma **chave de estado** única (uma string)
- Ele define um mapa de **ações nomeadas** que sabe como manipular
- De qualquer lugar no app, você pode chamar
```
stateAction("action_name", state: TargetWidget.state)
``` 

Existem três padrões para construir interfaces com estado gerenciado. Todos compartilham a mesma mecânica `stateAction` — a única diferença é o tipo de interface que você está gerenciando.


<div id="choose-your-pattern"></div>

## Escolha seu Padrão

| Você quer... | Use | Comando scaffold |
|---|---|---|
| Gerenciar o estado de uma página completa | `NyPage` | `metro make:page my_page` |
| Gerenciar o estado de um widget de instância única | `NyState` | `metro make:stateful_widget my_widget` |
| Gerenciar o estado de um widget com múltiplas instâncias isoladas | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Leia primeiro a seção de [Ações de Estado](#state-actions) — ela explica a API que cada padrão usa. Então vá para o padrão que se encaixa no seu caso.


<div id="state-actions"></div>

## Ações de Estado

Ações de estado são comandos nomeados que um widget ou página sabe como manipular. Você define um mapa de nomes de ações para funções manipuladoras, e as aciona pelo nome de qualquer lugar no app.

Use ações de estado quando precisar:
- Acionar um comportamento específico em um widget ou página (não apenas uma atualização genérica)
- Passar dados para um widget e fazê-lo responder de forma definida
- Construir comportamentos de widget reutilizáveis que podem ser invocados de múltiplos pontos

<div id="defining-handlers"></div>

### Definindo Manipuladores

Os manipuladores ficam no getter `stateActions` da sua classe `NyState` ou `NyPage`. As chaves do mapa são os nomes das ações; os valores são as funções a executar quando aquela ação é acionada.

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

Os manipuladores são responsáveis por chamar `setState` eles mesmos se quiserem que o widget seja reconstruído.

O manipulador `apply_discount` acima recebe um argumento `code` — declare um único parâmetro posicional quando seu manipulador precisar do payload passado via
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Use a forma sem argumento `()` quando a ação não carrega payload.


<div id="triggering-actions"></div>

### Acionando Ações

Use a função global `stateAction` para disparar uma ação de qualquer lugar — outro widget, um controller, um manipulador de evento, um callback de API, etc.

``` dart
// Disparar uma ação sem dados
stateAction("clear_cart", state: Cart.state);

// Disparar uma ação com dados
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

O argumento `state:` é a **chave de estado** do alvo:
- Para widgets — `MyWidget.state` (uma string)
- Para páginas — `MyPage.path` (a rota)


<div id="handlers-with-and-without-data"></div>

### Manipuladores Com e Sem Dados

Os manipuladores podem ser síncronos ou assíncronos, e podem ser definidos com ou sem um argumento `data`:

``` dart
@override
Map<String, Function> get stateActions => {
  // Sem dados — o manipulador executa como está
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Com dados — recebe o que foi passado via o argumento `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async é suportado — o framework aguarda o manipulador
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Se você passar `data:` para `stateAction` mas seu manipulador não aceitar argumentos, os dados são simplesmente ignorados.


<div id="using-a-state-actions-instance"></div>

### Usando uma Instância StateActions

Se um widget expõe uma instância tipada de `StateActions` (normalmente via um método estático `stateActions(stateName)`), você pode chamar `.action(...)` diretamente nela em vez de usar a função livre. Isso é mais limpo quando se dispara várias ações para o mesmo alvo:

``` dart
// Usando a função livre
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Usando uma instância StateActions — equivalente, menos repetição
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Vários widgets integrados (`InputField`, `CollectionView`, `LanguageSwitcher`, a família `NyForm*`) incluem classes `StateActions` tipadas com métodos nomeados como `.clear()`, `.setValue(...)`, `.refresh()` — consulte a documentação do widget para ver o que está disponível.


<div id="pattern-ny-page"></div>

## Padrão: Páginas com Estado Gerenciado (NyPage)

Use isto quando quiser acionar comportamento em uma página completa a partir de outro lugar no app — por exemplo, atualizar uma página quando um evento é disparado, ou limpar o estado de um formulário a partir de um widget filho.

**Passo 1:** Crie uma página com scaffold.

``` bash
metro make:page my_page
```

Isso gera um `NyPage` em `lib/resources/pages/`:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**Passo 2:** Mude `stateManaged` para `true`.

Por padrão, páginas **não** se inscrevem em eventos de estado — isso evita listeners desnecessários em páginas que não precisam deles. Para habilitar ações de estado em uma página, altere o getter:

``` dart
@override
bool get stateManaged => true;
```

**Passo 3:** Adicione um mapa `stateActions`.

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**Passo 4:** Acione a ação de qualquer lugar usando o `path` da página.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Páginas usam `MyPage.path` como chave, widgets usam `MyWidget.state`. Essa é a única diferença no ponto de chamada.


<div id="pattern-ny-state"></div>

## Padrão: Widgets com Estado (NyState)

Use isto para widgets reutilizáveis que existem como uma única instância por página — um cartão de perfil, uma barra de título, um indicador de status. Se você renderiza apenas uma instância do widget por vez, este é o padrão correto.

**Passo 1:** Crie um widget com estado via scaffold.

``` bash
metro make:stateful_widget profile_card
```

Isso gera o seguinte em `lib/resources/widgets/`:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

O scaffold gera um widget `NyState` funcional — mas ele **ainda não tem estado gerenciado**. Para fazê-lo responder a ações de estado, você precisa adicionar quatro coisas:

1. Uma chave `state` na classe do widget — a string única que outras partes do seu app usarão como alvo
2. Um parâmetro de construtor que recebe a chave de estado e a repassa para a classe de estado
3. Um construtor na classe de estado que atribui `stateName`
4. Um mapa `stateActions` definindo os manipuladores

**Passo 2:** Converta o scaffold em um widget com estado gerenciado.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ chame isto de qualquer lugar no app para acionar o manipulador abaixo
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

O que mudou:
- `static String get state => "profile_card";` define a chave de estado pública
- `createState() => _ProfileCardState(state)` passa a chave para a classe de estado
- `_ProfileCardState(String? state) { this.stateName = state; }` registra esta instância de estado sob aquela chave, para que o framework saiba para qual widget entregar a ação
- `stateActions` declara os manipuladores nomeados

**Passo 3:** Acione de qualquer lugar.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Você pode adicionar quantos manipuladores precisar, com ou sem dados:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## Padrão: Widgets com Estado Gerenciado (NyStateManaged)

Use isto quando precisar de **múltiplas instâncias independentes do mesmo widget** na tela ao mesmo tempo — por exemplo, um badge de carrinho no cabeçalho e outro em uma barra lateral que devem se atualizar de forma independente.

`NyStateManaged` adiciona um parâmetro `id` para que cada instância possa ser endereçada separadamente. Se você renderiza apenas uma instância do widget, prefira `NyState` — é mais simples.

**Passo 1:** Crie um widget com estado gerenciado via scaffold.

``` bash
metro make:state_managed_widget cart
```

Isso gera o seguinte em `lib/resources/widgets/`:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.id})
      : super(baseState: state, child: () => _CartState());

  static const String state = "cart";

  static action(String action, {dynamic data, String? id}) =>
      stateAction(action, data: data, state: state, id: id);
}

class _CartState extends NyState<Cart> {
  @override
  get init => () {
    // lógica de inicialização aqui
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Invocar ações de qualquer lugar no app
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**Passo 2:** Desenvolva o estado — carregue dados em `init`, defina manipuladores e renderize.

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**Passo 3:** Acione ações usando o helper estático `action()` gerado.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Isso é equivalente a `stateAction("reload_cart", state: Cart.state)` — o helper estático apenas remove o código repetitivo.


<div id="advanced-multiple-isolated-instances"></div>

### Avançado: Múltiplas Instâncias Isoladas

O motivo pelo qual `NyStateManaged` existe é suportar múltiplas instâncias independentes do mesmo widget. Cada instância obtém seu próprio `id`, que produz uma chave de estado com namespace.

Renderize dois carrinhos com ids distintos:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Agora você pode atualizar cada um de forma independente:

``` dart
// Recarregar apenas o carrinho do cabeçalho
Cart.action("reload_cart", id: "header");

// Recarregar apenas o carrinho da barra lateral
Cart.action("reload_cart", id: "sidebar");

// Sem id — aponta para a instância padrão sem nome
Cart.action("reload_cart");
```

`NyStateManaged` gerencia o namespace: `Cart(id: "header")` se registra sob a chave `"cart_header"`, e `Cart.action(..., id: "header")` aponta exatamente para essa chave.


<div id="lifecycle"></div>

## Referência do Ciclo de Vida

Widgets e páginas com estado gerenciado compartilham dois hooks chave do ciclo de vida:

1. **`init()`** — chamado uma vez quando o estado é criado pela primeira vez. Use-o para carregar dados iniciais.

2. **`stateUpdated(data)`** — chamado sempre que uma ação de estado é disparada contra este estado. O argumento `data` é o payload completo (incluindo o nome da ação e os dados da ação). Sobrescreva-o se precisar reagir a *cada* ação de estado — na maioria das vezes, definir manipuladores em `stateActions` é o que você vai querer.

**Veja também:** [NyState](/docs/{{ $version }}/ny-state) para o conjunto completo de helpers de estado e métodos do ciclo de vida.
