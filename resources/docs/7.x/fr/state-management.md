# Gestion d'etat

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Quand utiliser la gestion d'etat](#when-to-use-state-management "Quand utiliser la gestion d'etat")
- [Cycle de vie](#lifecycle "Cycle de vie")
- [Actions d'etat](#state-actions "Actions d'etat")
  - [NyState - Actions d'etat](#state-actions-nystate "NyState - Actions d'etat")
  - [NyPage - Actions d'etat](#state-actions-nypage "NyPage - Actions d'etat")
- [Mettre a jour un etat](#updating-a-state "Mettre a jour un etat")
- [Construire votre premier widget](#building-your-first-widget "Construire votre premier widget")

<div id="introduction"></div>

## Introduction

La gestion d'etat vous permet de mettre a jour des parties specifiques de votre interface sans reconstruire des pages entieres. Dans {{ config('app.name') }} v7, vous pouvez construire des widgets qui communiquent et se mettent a jour mutuellement a travers votre application.

{{ config('app.name') }} fournit deux classes pour la gestion d'etat :
- **`NyState`** — Pour construire des widgets reutilisables (comme un badge de panier, un compteur de notifications ou un indicateur de statut)
- **`NyPage`** — Pour construire des pages dans votre application (etend `NyState` avec des fonctionnalites specifiques aux pages)

Utilisez la gestion d'etat lorsque vous avez besoin de :
- Mettre a jour un widget depuis une autre partie de votre application
- Garder les widgets synchronises avec des donnees partagees
- Eviter de reconstruire des pages entieres lorsque seule une partie de l'interface change


### Comprenons d'abord la gestion d'etat

Tout dans Flutter est un widget, ce sont juste de petits morceaux d'interface que vous pouvez combiner pour creer une application complete.

Lorsque vous commencez a construire des pages complexes, vous devrez gerer l'etat de vos widgets. Cela signifie que lorsque quelque chose change, par exemple des donnees, vous pouvez mettre a jour ce widget sans avoir a reconstruire la page entiere.

Il y a de nombreuses raisons pour lesquelles c'est important, mais la principale est la performance. Si vous avez un widget qui change constamment, vous ne voulez pas reconstruire la page entiere a chaque fois qu'il change.

C'est la que la gestion d'etat intervient, elle vous permet de gerer l'etat d'un widget dans votre application.


<div id="when-to-use-state-management"></div>

### Quand utiliser la gestion d'etat

Vous devriez utiliser la gestion d'etat lorsque vous avez un widget qui doit etre mis a jour sans reconstruire la page entiere.

Par exemple, imaginons que vous avez cree une application e-commerce. Vous avez construit un widget pour afficher le nombre total d'articles dans le panier de l'utilisateur.
Appelons ce widget `Cart()`.

Un widget `Cart` gere par etat dans Nylo ressemblerait a quelque chose comme ceci :

**Etape 1 :** Definir le widget etendant `NyStateManaged`

``` dart
/// Le widget Cart
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart"; // Identifiant unique pour l'etat de ce widget

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}
```

**Etape 2 :** Creer la classe d'etat etendant `NyState`

``` dart
/// La classe d'etat pour le widget Cart
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Charger les donnees initiales
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": (data) async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**Etape 3 :** Creer des fonctions d'aide pour lire et mettre a jour le panier

``` dart
/// Obtenir la valeur du panier depuis le stockage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Definir la valeur du panier et notifier le widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // Cela declenche stateUpdated() sur le widget
}
```

Decomposons cela.

1. Le widget `Cart` etend `NyStateManaged` (pas directement `StatefulWidget`).

2. Le parametre de constructeur `stateName` est transmis via `super(child: () => _CartState(stateName))`, permettant plusieurs instances isolees du meme widget.

<!-- uncertain: new Nylo-specific term "_stateFor" helper method — describes multi-instance isolation pattern new in v7.1.13 -->
3. L'assistant `_stateFor(String? state)` produit une cle d'etat avec namespace comme `"cart_sidebar"` pour les instances nommees.

4. `_CartState` etend `NyState<Cart>` et recoit `stateName` pour enregistrer l'etat isole correct.

5. La map `stateActions` definit des commandes nommees que vous pouvez invoquer sur le widget depuis n'importe ou dans votre application.

Si vous voulez essayer cet exemple dans votre projet {{ config('app.name') }}, creez un nouveau widget appele `Cart`.

``` bash
metro make:state_managed_widget cart
```

Ensuite, vous pouvez copier l'exemple ci-dessus et l'essayer dans votre projet.

Maintenant, pour mettre a jour le panier, vous pouvez appeler ce qui suit.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Cycle de vie

Le cycle de vie d'un widget `NyState` est le suivant :

1. `init()` - Cette methode est appelee lorsque l'etat est initialise.

2. `stateUpdated(data)` - Cette methode est appelee lorsque l'etat est mis a jour.

    Si vous appelez `updateState(MyStateName.state, data: "The Data")`, cela declenchera l'appel de **stateUpdated(data)**.

Une fois l'etat initialise pour la premiere fois, vous devrez implementer la facon dont vous souhaitez gerer l'etat.


<div id="state-actions"></div>

## Actions d'etat

Les actions d'etat vous permettent de declencher des methodes specifiques sur un widget depuis n'importe ou dans votre application. Considerez-les comme des commandes nommees que vous pouvez envoyer a un widget.

Utilisez les actions d'etat lorsque vous avez besoin de :
- Declencher un comportement specifique sur un widget (pas seulement le rafraichir)
- Passer des donnees a un widget et le faire reagir d'une maniere particuliere
- Creer des comportements de widget reutilisables pouvant etre invoques depuis plusieurs endroits

``` dart
// Envoyer une action au widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Un autre exemple avec des donnees
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

Dans votre widget, vous pouvez definir les actions que vous souhaitez gerer.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Exemple avec des donnees
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Ensuite, vous pouvez appeler la methode `stateAction` depuis n'importe ou dans votre application.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Si vous disposez deja d'une instance `StateActions` (par exemple depuis la methode statique `stateActions()` d'un widget), vous pouvez appeler `action()` directement dessus plutot que d'utiliser la fonction libre :

``` dart
// Utiliser la fonction libre
stateAction('reset_avatar', state: UserAvatar.state);

// Utiliser la methode d'instance StateActions — equivalent, moins de repetition
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action('reset_avatar');
actions.action('update_user_image', data: user);
```

Vous pouvez egalement definir vos actions d'etat en utilisant la methode `whenStateAction` dans votre getter `init`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reinitialiser le compteur du badge
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - Actions d'etat

Tout d'abord, creez un widget stateful.

``` bash
metro make:stateful_widget [widget_name]
```
Exemple : metro make:stateful_widget user_avatar

Cela creera un nouveau widget dans le repertoire `lib/resources/widgets/`.

Si vous ouvrez ce fichier, vous pourrez definir vos actions d'etat.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Exemple
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Exemple
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Enfin, vous pouvez envoyer l'action depuis n'importe ou dans votre application.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// affiche 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reinitialiser les donnees dans le widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// affiche un toast de succes avec le message
```


<div id="state-actions-nypage"></div>

### NyPage - Actions d'etat

Les pages peuvent egalement recevoir des actions d'etat. Ceci est utile lorsque vous souhaitez declencher des comportements au niveau de la page depuis des widgets ou d'autres pages.

Tout d'abord, creez votre page geree par etat.

``` bash
metro make:page my_page
```

Cela creera une nouvelle page geree par etat appelee `MyPage` dans le repertoire `lib/resources/pages/`.

Si vous ouvrez ce fichier, vous pourrez definir vos actions d'etat.

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => false; // mettre a true pour activer les actions d'etat sur cette page

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // Exemple
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Enfin, vous pouvez envoyer l'action depuis n'importe ou dans votre application.

``` dart
stateAction('test_page_action', state: MyPage.path);
// affiche 'Hello from the page'

stateAction('reset_data', state: MyPage.path);
// Reinitialiser les donnees dans la page

stateAction('show_toast', state: MyPage.path, data: {
  "message": "Hello from the page"
});
// affiche un toast de succes avec le message
```

Vous pouvez egalement definir vos actions d'etat en utilisant la methode `whenStateAction`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reinitialiser le compteur du badge
      _count = 0;
    }
  });
}
```

Ensuite, vous pouvez envoyer l'action depuis n'importe ou dans votre application.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Mettre a jour un etat

Vous pouvez mettre a jour un etat en appelant la methode `updateState()`.

``` dart
updateState(MyStateName.state);

// ou avec des donnees
updateState(MyStateName.state, data: "The Data");
```

Cela peut etre appele depuis n'importe ou dans votre application.

**Voir aussi :** [NyState](/docs/{{ $version }}/ny-state) pour plus de details sur les helpers de gestion d'etat et les methodes de cycle de vie.


<div id="building-your-first-widget"></div>

## Construire votre premier widget

Dans votre projet Nylo, executez la commande suivante pour creer un nouveau widget.

``` bash
metro make:stateful_widget todo_list
```

Cela creera un nouveau widget `NyState` appele `TodoList`.

> Note : Le nouveau widget sera cree dans le repertoire `lib/resources/widgets/`.
