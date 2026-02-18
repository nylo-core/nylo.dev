# State Management

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Quando Usar Gerenciamento de Estado](#when-to-use-state-management "Quando Usar Gerenciamento de Estado")
- [Ciclo de Vida](#lifecycle "Ciclo de Vida")
- [Ações de Estado](#state-actions "Ações de Estado")
  - [NyState - Ações de Estado](#state-actions-nystate "NyState - Ações de Estado")
  - [NyPage - Ações de Estado](#state-actions-nypage "NyPage - Ações de Estado")
- [Atualizando um Estado](#updating-a-state "Atualizando um Estado")
- [Construindo seu Primeiro Widget](#building-your-first-widget "Construindo seu Primeiro Widget")

<div id="introduction"></div>

## Introdução

O gerenciamento de estado permite que você atualize partes específicas da sua UI sem reconstruir páginas inteiras. No {{ config('app.name') }} v7, você pode construir widgets que se comunicam e atualizam uns aos outros em todo o seu app.

{{ config('app.name') }} fornece duas classes para gerenciamento de estado:
- **`NyState`** -- Para construir widgets reutilizáveis (como um badge de carrinho, contador de notificações ou indicador de status)
- **`NyPage`** -- Para construir páginas na sua aplicação (estende `NyState` com recursos específicos de página)

Use gerenciamento de estado quando você precisar:
- Atualizar um widget de uma parte diferente do seu app
- Manter widgets sincronizados com dados compartilhados
- Evitar reconstruir páginas inteiras quando apenas parte da UI muda


### Vamos primeiro entender o Gerenciamento de Estado

Tudo no Flutter é um widget, são apenas pequenos pedaços de UI que você pode combinar para fazer um app completo.

Quando você começa a construir páginas complexas, precisará gerenciar o estado dos seus widgets. Isso significa que quando algo muda, ex.: dados, você pode atualizar aquele widget sem ter que reconstruir a página inteira.

Existem muitas razões pelas quais isso é importante, mas a razão principal é desempenho. Se você tem um widget que está mudando constantemente, você não quer reconstruir a página inteira toda vez que ele muda.

É aqui que o Gerenciamento de Estado entra, ele permite que você gerencie o estado de um widget na sua aplicação.


<div id="when-to-use-state-management"></div>

### Quando Usar Gerenciamento de Estado

Você deve usar Gerenciamento de Estado quando tiver um widget que precisa ser atualizado sem reconstruir a página inteira.

Por exemplo, vamos imaginar que você criou um app de ecommerce. Você construiu um widget para exibir a quantidade total de itens no carrinho do usuário.
Vamos chamar este widget de `Cart()`.

Um widget `Cart` gerenciado por estado no Nylo ficaria assim:

**Passo 1:** Defina o widget com um nome de estado estático

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**Passo 2:** Crie a classe de estado estendendo `NyState`

``` dart
/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Register the state name
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Load initial data
  };

  @override
  void stateUpdated(data) {
    reboot(); // Reload the widget when state updates
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**Passo 3:** Crie funções auxiliares para ler e atualizar o carrinho

``` dart
/// Get the cart value from storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Set the cart value and notify the widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // This triggers stateUpdated() on the widget
}
```

Vamos detalhar isso.

1. O widget `Cart` é um `StatefulWidget`.

2. `_CartState` estende `NyState<Cart>`.

3. Você precisa definir um nome para o `state`, que é usado para identificar o estado.

4. O método `boot()` é chamado quando o widget é carregado pela primeira vez.

5. Os métodos `stateUpdate()` lidam com o que acontece quando o estado é atualizado.

Se você quiser experimentar este exemplo no seu projeto {{ config('app.name') }}, crie um novo widget chamado `Cart`.

``` bash
metro make:state_managed_widget cart
```

Então você pode copiar o exemplo acima e testá-lo no seu projeto.

Agora, para atualizar o carrinho, você pode chamar o seguinte.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Ciclo de Vida

O ciclo de vida de um widget `NyState` é o seguinte:

1. `init()` - Este método é chamado quando o estado é inicializado.

2. `stateUpdated(data)` - Este método é chamado quando o estado é atualizado.

    Se você chamar `updateState(MyStateName.state, data: "The Data")`, isso disparará **stateUpdated(data)** para ser chamado.

Uma vez que o estado é inicializado pela primeira vez, você precisará implementar como deseja gerenciar o estado.


<div id="state-actions"></div>

## Ações de Estado

Ações de estado permitem que você dispare métodos específicos em um widget de qualquer lugar no seu app. Pense nelas como comandos nomeados que você pode enviar para um widget.

Use ações de estado quando precisar:
- Disparar um comportamento específico em um widget (não apenas atualizá-lo)
- Passar dados para um widget e fazê-lo responder de uma maneira particular
- Criar comportamentos de widget reutilizáveis que podem ser invocados de múltiplos lugares

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

No seu widget, você pode definir as ações que deseja tratar.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Example with data
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Então, você pode chamar o método `stateAction` de qualquer lugar na sua aplicação.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Você também pode definir suas ações de estado usando o método `whenStateAction` no seu getter `init`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - Ações de Estado

Primeiro, crie um widget stateful.

``` bash
metro make:stateful_widget [widget_name]
```
Exemplo: metro make:stateful_widget user_avatar

Isso criará um novo widget no diretório `lib/resources/widgets/`.

Se você abrir esse arquivo, poderá definir suas ações de estado.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Example
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Example
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Finalmente, você pode enviar a ação de qualquer lugar na sua aplicação.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - Ações de Estado

Páginas também podem receber ações de estado. Isso é útil quando você deseja disparar comportamentos no nível da página a partir de widgets ou outras páginas.

Primeiro, crie sua página gerenciada por estado.

``` bash
metro make:page my_page
```

Isso criará uma nova página gerenciada por estado chamada `MyPage` no diretório `lib/resources/pages/`.

Se você abrir esse arquivo, poderá definir suas ações de estado.

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => true;

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Finalmente, você pode enviar a ação de qualquer lugar na sua aplicação.

``` dart
stateAction('test_page_action', state: MyPage.state);
// prints 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Reset data in page

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// shows a success toast with the message
```

Você também pode definir suas ações de estado usando o método `whenStateAction`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```

Então você pode enviar a ação de qualquer lugar na sua aplicação.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Atualizando um Estado

Você pode atualizar um estado chamando o método `updateState()`.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

Isso pode ser chamado de qualquer lugar na sua aplicação.

**Veja também:** [NyState](/docs/{{ $version }}/ny-state) para mais detalhes sobre helpers de gerenciamento de estado e métodos de ciclo de vida.


<div id="building-your-first-widget"></div>

## Construindo seu Primeiro Widget

No seu projeto Nylo, execute o seguinte comando para criar um novo widget.

``` bash
metro make:stateful_widget todo_list
```

Isso criará um novo widget `NyState` chamado `TodoList`.

> Nota: O novo widget será criado no diretório `lib/resources/widgets/`.
