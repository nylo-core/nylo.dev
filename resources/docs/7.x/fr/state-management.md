# Widgets a Etat Gere

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Choisir votre Patron](#choose-your-pattern "Choisir votre Patron")
- [Actions d'etat](#state-actions "Actions d'etat")
  - [Definir les gestionnaires](#defining-handlers "Definir les gestionnaires")
  - [Declencher des actions](#triggering-actions "Declencher des actions")
  - [Gestionnaires avec et sans donnees](#handlers-with-and-without-data "Gestionnaires avec et sans donnees")
  - [Utiliser une instance StateActions](#using-a-state-actions-instance "Utiliser une instance StateActions")
- [Patron : Pages a etat gere (NyPage)](#pattern-ny-page "Patron : Pages a etat gere")
- [Patron : Widgets avec etat (NyState)](#pattern-ny-state "Patron : Widgets avec etat")
- [Patron : Widgets a etat gere (NyStateManaged)](#pattern-ny-state-managed "Patron : Widgets a etat gere")
  - [Avance : Plusieurs instances isolees](#advanced-multiple-isolated-instances "Avance : Plusieurs instances isolees")
- [Reference du cycle de vie](#lifecycle "Reference du cycle de vie")

<div id="introduction"></div>

## Introduction

Les widgets a etat gere vous permettent de mettre a jour des parties specifiques de votre interface depuis n'importe ou dans votre app, sans reconstruire des pages entieres. Dans {{ config('app.name') }} v7, vous declenchez des **actions d'etat** nommees sur un widget ou une page cible, et le gestionnaire correspondant s'execute.

Le modele mental est simple :

- Chaque widget ou page a etat gere possede une **cle d'etat** unique (une chaine de caracteres)
- Il definit une map d'**actions nommees** qu'il sait traiter
- Depuis n'importe ou dans votre app, vous pouvez appeler
```
stateAction("action_name", state: TargetWidget.state)
``` 

Il existe trois patrons pour construire une interface a etat gere. Ils partagent tous la meme mecanique `stateAction` — la seule difference est le type d'interface que vous gerez.


<div id="choose-your-pattern"></div>

## Choisir votre Patron

| Vous voulez... | Utilisez | Commande d'echafaudage |
|---|---|---|
| Gerer l'etat d'une page complete | `NyPage` | `metro make:page my_page` |
| Gerer l'etat d'un widget a instance unique | `NyState` | `metro make:stateful_widget my_widget` |
| Gerer l'etat d'un widget avec plusieurs instances isolees | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Lisez d'abord la section [Actions d'etat](#state-actions) — elle explique l'API utilisee par chaque patron. Ensuite, passez au patron qui correspond a votre cas.


<div id="state-actions"></div>

## Actions d'etat

Les actions d'etat sont des commandes nommees qu'un widget ou une page sait traiter. Vous definissez une map de noms d'actions vers des fonctions gestionnaires, et vous les declenchez par nom depuis n'importe ou dans votre app.

Utilisez les actions d'etat lorsque vous avez besoin de :
- Declencher un comportement specifique sur un widget ou une page (pas seulement un rafraichissement generique)
- Passer des donnees a un widget et le faire reagir d'une maniere definie
- Construire des comportements de widget reutilisables pouvant etre invoques depuis plusieurs points d'appel

<div id="defining-handlers"></div>

### Definir les gestionnaires

Les gestionnaires se trouvent dans le getter `stateActions` de votre classe `NyState` ou `NyPage`. Les cles de la map sont les noms des actions ; les valeurs sont les fonctions a executer lorsque cette action est declenchee.

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

Les gestionnaires sont responsables d'appeler `setState` eux-memes s'ils souhaitent que le widget se reconstruise.

Le gestionnaire `apply_discount` ci-dessus prend un argument `code` — declarez un seul parametre positionnel lorsque votre gestionnaire a besoin de la charge utile passee via
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Utilisez la forme sans argument `()` lorsque l'action ne transporte pas de charge utile.


<div id="triggering-actions"></div>

### Declencher des actions

Utilisez la fonction globale `stateAction` pour declencher une action depuis n'importe ou — un autre widget, un controleur, un gestionnaire d'evenements, un callback d'API, etc.

``` dart
// Declencher une action sans donnees
stateAction("clear_cart", state: Cart.state);

// Declencher une action avec des donnees
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

L'argument `state:` est la **cle d'etat** de la cible :
- Pour les widgets — `MyWidget.state` (une chaine de caracteres)
- Pour les pages — `MyPage.path` (la route)


<div id="handlers-with-and-without-data"></div>

### Gestionnaires avec et sans donnees

Les gestionnaires peuvent etre synchrones ou asynchrones, et peuvent etre definis avec ou sans argument `data` :

``` dart
@override
Map<String, Function> get stateActions => {
  // Sans donnees — le gestionnaire s'execute tel quel
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Avec donnees — recoit ce qui a ete passe via l'argument `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // L'async est supporte — le framework attend le gestionnaire
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Si vous passez `data:` a `stateAction` mais que votre gestionnaire ne prend pas d'arguments, les donnees sont simplement ignorees.


<div id="using-a-state-actions-instance"></div>

### Utiliser une instance StateActions

Si un widget expose une instance `StateActions` typee (souvent via une methode statique `stateActions(stateName)`), vous pouvez appeler `.action(...)` directement dessus plutot que d'utiliser la fonction libre. C'est plus propre lorsque vous declenchez plusieurs actions vers la meme cible :

``` dart
// Utiliser la fonction libre
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Utiliser une instance StateActions — equivalent, moins de repetition
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Plusieurs widgets integres (`InputField`, `CollectionView`, `LanguageSwitcher`, la famille `NyForm*`) fournissent des classes `StateActions` typees avec des methodes nommees comme `.clear()`, `.setValue(...)`, `.refresh()` — consultez la documentation du widget pour voir ce qui est disponible.


<div id="pattern-ny-page"></div>

## Patron : Pages a etat gere (NyPage)

Utilisez ceci lorsque vous souhaitez declencher un comportement sur une page complete depuis ailleurs dans votre app — par exemple, actualiser une page lorsqu'un evenement se produit, ou effacer l'etat d'un formulaire depuis un widget enfant.

**Etape 1 :** Echafaudez une page.

``` bash
metro make:page my_page
```

Cela genere un `NyPage` dans `lib/resources/pages/` :

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

**Etape 2 :** Passez `stateManaged` a `true`.

Par defaut, les pages ne s'abonnent **pas** aux evenements d'etat — cela evite des listeners inutiles sur les pages qui n'en ont pas besoin. Pour activer les actions d'etat sur une page, modifiez le getter :

``` dart
@override
bool get stateManaged => true;
```

**Etape 3 :** Ajoutez une map `stateActions`.

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

**Etape 4 :** Declenchez l'action depuis n'importe ou en utilisant le `path` de la page.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Les pages utilisent `MyPage.path` comme cle, les widgets utilisent `MyWidget.state`. C'est la seule difference au point d'appel.


<div id="pattern-ny-state"></div>

## Patron : Widgets avec etat (NyState)

Utilisez ceci pour les widgets reutilisables qui existent en tant qu'instance unique par page — une carte de profil, une barre de titre, un indicateur de statut. Si vous ne rendez qu'une seule instance du widget a la fois, c'est le bon patron.

**Etape 1 :** Echafaudez un widget avec etat.

``` bash
metro make:stateful_widget profile_card
```

Cela genere ce qui suit dans `lib/resources/widgets/` :

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

L'echafaudage vous donne un widget `NyState` fonctionnel — mais il n'est **pas encore a etat gere**. Pour lui permettre de repondre aux actions d'etat, vous devez ajouter quatre elements :

1. Une cle `state` sur la classe du widget — la chaine unique que les autres parties de votre app cibleront
2. Un parametre de constructeur qui recoit la cle d'etat et la transmet a la classe d'etat
3. Un constructeur sur la classe d'etat qui assigne `stateName`
4. Une map `stateActions` definissant les gestionnaires

**Etape 2 :** Convertissez l'echafaudage en widget a etat gere.

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
    // ^ appelez ceci depuis n'importe ou dans votre app pour declencher le gestionnaire ci-dessous
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

Ce qui a change :
- `static String get state => "profile_card";` definit la cle d'etat publique
- `createState() => _ProfileCardState(state)` passe la cle dans la classe d'etat
- `_ProfileCardState(String? state) { this.stateName = state; }` enregistre cette instance d'etat sous cette cle, afin que le framework sache a quel widget livrer l'action
- `stateActions` declare les gestionnaires nommes

**Etape 3 :** Declenchement depuis n'importe ou.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Vous pouvez ajouter autant de gestionnaires que necessaire, avec ou sans donnees :

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

## Patron : Widgets a etat gere (NyStateManaged)

Utilisez ceci lorsque vous avez besoin de **plusieurs instances independantes du meme widget** a l'ecran en meme temps — par exemple, un badge de panier dans le titre et un autre dans une barre laterale qui doivent se mettre a jour independamment.

`NyStateManaged` ajoute un parametre `id` afin que chaque instance puisse etre adressee separement. Si vous ne rendez jamais qu'une seule instance du widget, preferez `NyState` — c'est plus simple.

**Etape 1 :** Echafaudez un widget a etat gere.

``` bash
metro make:state_managed_widget cart
```

Cela genere ce qui suit dans `lib/resources/widgets/` :

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
    // logique d'initialisation ici
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Invoquez des actions depuis n'importe ou dans votre app
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

**Etape 2 :** Developpez l'etat — chargez les donnees dans `init`, definissez les gestionnaires et effectuez le rendu.

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

**Etape 3 :** Declenchez des actions en utilisant le helper statique `action()` genere.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Ceci est equivalent a `stateAction("reload_cart", state: Cart.state)` — le helper statique supprime simplement le code repetitif.


<div id="advanced-multiple-isolated-instances"></div>

### Avance : Plusieurs instances isolees

La raison pour laquelle `NyStateManaged` existe est de prendre en charge plusieurs instances independantes du meme widget. Chaque instance obtient son propre `id`, qui produit une cle d'etat avec espace de noms.

Rendez deux paniers avec des identifiants distincts :

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Vous pouvez maintenant mettre a jour l'un ou l'autre independamment :

``` dart
// Actualiser uniquement le panier de l'en-tete
Cart.action("reload_cart", id: "header");

// Actualiser uniquement le panier de la barre laterale
Cart.action("reload_cart", id: "sidebar");

// Sans id — cible l'instance par defaut sans nom
Cart.action("reload_cart");
```

`NyStateManaged` gere l'espace de noms : `Cart(id: "header")` s'enregistre sous la cle `"cart_header"`, et `Cart.action(..., id: "header")` cible exactement cette cle.


<div id="lifecycle"></div>

## Reference du cycle de vie

Les widgets et pages a etat gere partagent deux hooks cles du cycle de vie :

1. **`init()`** — appele une fois lorsque l'etat est cree pour la premiere fois. Utilisez-le pour charger les donnees initiales.

2. **`stateUpdated(data)`** — appele chaque fois qu'une action d'etat est declenchee contre cet etat. L'argument `data` est la charge utile complete (incluant le nom de l'action et les donnees de l'action). Surchargez-le si vous avez besoin de reagir a *chaque* action d'etat — la plupart du temps, definir des gestionnaires dans `stateActions` est ce que vous voudrez faire.

**Voir aussi :** [NyState](/docs/{{ $version }}/ny-state) pour l'ensemble complet des helpers d'etat et des methodes du cycle de vie.
