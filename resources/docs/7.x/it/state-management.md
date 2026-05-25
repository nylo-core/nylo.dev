# Widget con Stato Gestito

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Scegli il tuo Pattern](#choose-your-pattern "Scegli il tuo Pattern")
- [Azioni sullo Stato](#state-actions "Azioni sullo Stato")
  - [Definire i Gestori](#defining-handlers "Definire i Gestori")
  - [Attivare le Azioni](#triggering-actions "Attivare le Azioni")
  - [Gestori Con e Senza Dati](#handlers-with-and-without-data "Gestori Con e Senza Dati")
  - [Usare un'Istanza StateActions](#using-a-state-actions-instance "Usare un'Istanza StateActions")
- [Pattern: Pagine con Stato Gestito (NyPage)](#pattern-ny-page "Pattern: Pagine con Stato Gestito")
- [Pattern: Widget con Stato (NyState)](#pattern-ny-state "Pattern: Widget con Stato")
- [Pattern: Widget con Stato Gestito (NyStateManaged)](#pattern-ny-state-managed "Pattern: Widget con Stato Gestito")
  - [Avanzato: Piu' Istanze Isolate](#advanced-multiple-isolated-instances "Avanzato: Piu' Istanze Isolate")
- [Riferimento al Ciclo di Vita](#lifecycle "Riferimento al Ciclo di Vita")

<div id="introduction"></div>

## Introduzione

I widget con stato gestito ti permettono di aggiornare parti specifiche della tua UI da qualsiasi punto della tua app, senza ricostruire intere pagine. In {{ config('app.name') }} v7, attivi **azioni di stato** nominate su un widget o una pagina target, e il gestore corrispondente viene eseguito.

Il modello mentale e' semplice:

- Ogni widget o pagina con stato gestito ha una **chiave di stato** univoca (una stringa)
- Definisce una mappa di **azioni nominate** che sa come gestire
- Da qualsiasi punto della tua app, puoi chiamare
```
stateAction("action_name", state: TargetWidget.state)
``` 

Esistono tre pattern per costruire interfacce con stato gestito. Condividono tutti la stessa meccanica `stateAction` — l'unica differenza e' il tipo di interfaccia che stai gestendo.


<div id="choose-your-pattern"></div>

## Scegli il tuo Pattern

| Vuoi... | Usa | Comando scaffold |
|---|---|---|
| Gestire lo stato di una pagina intera | `NyPage` | `metro make:page my_page` |
| Gestire lo stato di un widget a istanza singola | `NyState` | `metro make:stateful_widget my_widget` |
| Gestire lo stato di un widget con piu' istanze isolate | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Leggi prima la sezione [Azioni sullo Stato](#state-actions) — spiega le API che ogni pattern utilizza. Poi salta al pattern che si adatta al tuo caso.


<div id="state-actions"></div>

## Azioni sullo Stato

Le azioni di stato sono comandi nominati che un widget o una pagina sa come gestire. Definisci una mappa di nomi di azioni verso funzioni gestore, e le attivi per nome da qualsiasi punto della tua app.

Usa le azioni di stato quando hai bisogno di:
- Attivare un comportamento specifico su un widget o una pagina (non solo un aggiornamento generico)
- Passare dati a un widget e farlo rispondere in modo definito
- Costruire comportamenti di widget riutilizzabili che possono essere invocati da piu' punti

<div id="defining-handlers"></div>

### Definire i Gestori

I gestori si trovano nel getter `stateActions` della tua classe `NyState` o `NyPage`. Le chiavi della mappa sono i nomi delle azioni; i valori sono le funzioni da eseguire quando quell'azione viene attivata.

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

I gestori sono responsabili di chiamare `setState` da soli se vogliono che il widget si ricostruisca.

Il gestore `apply_discount` sopra accetta un argomento `code` — dichiara un singolo parametro posizionale quando il tuo gestore ha bisogno del payload passato tramite
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Usa la forma senza argomenti `()` quando l'azione non trasporta payload.


<div id="triggering-actions"></div>

### Attivare le Azioni

Usa la funzione globale `stateAction` per attivare un'azione da qualsiasi posto — un altro widget, un controller, un gestore di eventi, un callback API, ecc.

``` dart
// Attivare un'azione senza dati
stateAction("clear_cart", state: Cart.state);

// Attivare un'azione con dati
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

L'argomento `state:` e' la **chiave di stato** del target:
- Per i widget — `MyWidget.state` (una stringa)
- Per le pagine — `MyPage.path` (la route)


<div id="handlers-with-and-without-data"></div>

### Gestori Con e Senza Dati

I gestori possono essere sincroni o asincroni, e possono essere definiti con o senza un argomento `data`:

``` dart
@override
Map<String, Function> get stateActions => {
  // Senza dati — il gestore viene eseguito cosi' com'e'
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Con dati — riceve cio' che e' stato passato tramite l'argomento `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async e' supportato — il framework attende il gestore
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Se passi `data:` a `stateAction` ma il tuo gestore non accetta argomenti, i dati vengono semplicemente ignorati.


<div id="using-a-state-actions-instance"></div>

### Usare un'Istanza StateActions

Se un widget espone un'istanza tipizzata di `StateActions` (spesso tramite un metodo statico `stateActions(stateName)`), puoi chiamare `.action(...)` direttamente su di essa invece di usare la funzione libera. E' piu' pulito quando si attivano piu' azioni verso lo stesso target:

``` dart
// Usando la funzione libera
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Usando un'istanza StateActions — equivalente, meno ripetizione
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Diversi widget integrati (`InputField`, `CollectionView`, `LanguageSwitcher`, la famiglia `NyForm*`) forniscono classi `StateActions` tipizzate con metodi nominati come `.clear()`, `.setValue(...)`, `.refresh()` — consulta la documentazione del widget per sapere cosa e' disponibile.


<div id="pattern-ny-page"></div>

## Pattern: Pagine con Stato Gestito (NyPage)

Usalo quando vuoi attivare un comportamento su una pagina intera da un altro punto della tua app — ad esempio, aggiornare una pagina quando si verifica un evento, o cancellare lo stato di un form da un widget figlio.

**Passo 1:** Crea una pagina con scaffold.

``` bash
metro make:page my_page
```

Questo genera un `NyPage` in `lib/resources/pages/`:

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

**Passo 2:** Imposta `stateManaged` a `true`.

Per impostazione predefinita, le pagine **non** si iscrivono agli eventi di stato — questo evita listener non necessari sulle pagine che non ne hanno bisogno. Per abilitare le azioni di stato su una pagina, modifica il getter:

``` dart
@override
bool get stateManaged => true;
```

**Passo 3:** Aggiungi una mappa `stateActions`.

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

**Passo 4:** Attiva l'azione da qualsiasi punto usando il `path` della pagina.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Le pagine usano `MyPage.path` come chiave, i widget usano `MyWidget.state`. Questa e' l'unica differenza nel punto di chiamata.


<div id="pattern-ny-state"></div>

## Pattern: Widget con Stato (NyState)

Usalo per widget riutilizzabili che esistono come singola istanza per pagina — una scheda profilo, una barra del titolo, un indicatore di stato. Se rendi solo un'istanza del widget alla volta, questo e' il pattern corretto.

**Passo 1:** Crea un widget con stato tramite scaffold.

``` bash
metro make:stateful_widget profile_card
```

Questo genera quanto segue in `lib/resources/widgets/`:

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

Lo scaffold ti fornisce un widget `NyState` funzionante — ma **non e' ancora con stato gestito**. Per farlo rispondere alle azioni di stato, devi aggiungere quattro elementi:

1. Una chiave `state` sulla classe del widget — la stringa univoca che le altre parti della tua app utilizzeranno come target
2. Un parametro del costruttore che riceve la chiave di stato e la passa alla classe di stato
3. Un costruttore sulla classe di stato che assegna `stateName`
4. Una mappa `stateActions` che definisce i gestori

**Passo 2:** Converti lo scaffold in un widget con stato gestito.

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
    // ^ chiama questo da qualsiasi punto della tua app per attivare il gestore sottostante
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

Cosa e' cambiato:
- `static String get state => "profile_card";` definisce la chiave di stato pubblica
- `createState() => _ProfileCardState(state)` passa la chiave nella classe di stato
- `_ProfileCardState(String? state) { this.stateName = state; }` registra questa istanza di stato sotto quella chiave, in modo che il framework sappia a quale widget consegnare l'azione
- `stateActions` dichiara i gestori nominati

**Passo 3:** Attiva da qualsiasi punto.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Puoi aggiungere quanti gestori vuoi, con o senza dati:

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

## Pattern: Widget con Stato Gestito (NyStateManaged)

Usalo quando hai bisogno di **piu' istanze indipendenti dello stesso widget** sullo schermo contemporaneamente — ad esempio, un badge carrello nell'intestazione e un altro in una barra laterale che devono aggiornarsi indipendentemente.

`NyStateManaged` aggiunge un parametro `id` in modo che ogni istanza possa essere indirizzata separatamente. Se rendi sempre solo un'istanza del widget, preferisci `NyState` — e' piu' semplice.

**Passo 1:** Crea un widget con stato gestito tramite scaffold.

``` bash
metro make:state_managed_widget cart
```

Questo genera quanto segue in `lib/resources/widgets/`:

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
    // logica di inizializzazione qui
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Invoca azioni da qualsiasi punto della tua app
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

**Passo 2:** Sviluppa lo stato — carica i dati in `init`, definisci i gestori ed esegui il rendering.

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

**Passo 3:** Attiva le azioni usando l'helper statico `action()` generato.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Questo e' equivalente a `stateAction("reload_cart", state: Cart.state)` — l'helper statico rimuove semplicemente il codice ripetitivo.


<div id="advanced-multiple-isolated-instances"></div>

### Avanzato: Piu' Istanze Isolate

Il motivo per cui esiste `NyStateManaged` e' supportare piu' istanze indipendenti dello stesso widget. Ogni istanza ottiene il proprio `id`, che produce una chiave di stato con namespace.

Renderizza due carrelli con id distinti:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Ora puoi aggiornare ciascuno indipendentemente:

``` dart
// Ricaricare solo il carrello dell'intestazione
Cart.action("reload_cart", id: "header");

// Ricaricare solo il carrello della barra laterale
Cart.action("reload_cart", id: "sidebar");

// Senza id — punta all'istanza predefinita senza nome
Cart.action("reload_cart");
```

`NyStateManaged` gestisce il namespace: `Cart(id: "header")` si registra sotto la chiave `"cart_header"`, e `Cart.action(..., id: "header")` punta esattamente a quella chiave.


<div id="lifecycle"></div>

## Riferimento al Ciclo di Vita

I widget e le pagine con stato gestito condividono due hook chiave del ciclo di vita:

1. **`init()`** — chiamato una volta quando lo stato viene creato per la prima volta. Usalo per caricare i dati iniziali.

2. **`stateUpdated(data)`** — chiamato ogni volta che un'azione di stato viene attivata contro questo stato. L'argomento `data` e' il payload completo (incluso il nome dell'azione e i dati dell'azione). Sovrascrivilo se hai bisogno di reagire a *ogni* azione di stato — la maggior parte delle volte, definire i gestori in `stateActions` e' cio' che vorrai fare.

**Vedi anche:** [NyState](/docs/{{ $version }}/ny-state) per il set completo di helper di stato e metodi del ciclo di vita.
