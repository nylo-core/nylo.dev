# State-verwaltete Widgets

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Muster wählen](#choose-your-pattern "Muster wählen")
- [State-Aktionen](#state-actions "State-Aktionen")
  - [Handler definieren](#defining-handlers "Handler definieren")
  - [Aktionen auslösen](#triggering-actions "Aktionen auslösen")
  - [Handler mit und ohne Daten](#handlers-with-and-without-data "Handler mit und ohne Daten")
  - [Eine StateActions-Instanz verwenden](#using-a-state-actions-instance "Eine StateActions-Instanz verwenden")
- [Muster: State-verwaltete Seiten (NyPage)](#pattern-ny-page "Muster: State-verwaltete Seiten")
- [Muster: Stateful Widgets (NyState)](#pattern-ny-state "Muster: Stateful Widgets")
- [Muster: State-verwaltete Widgets (NyStateManaged)](#pattern-ny-state-managed "Muster: State-verwaltete Widgets")
  - [Fortgeschritten: Mehrere isolierte Instanzen](#advanced-multiple-isolated-instances "Fortgeschritten: Mehrere isolierte Instanzen")
- [Lebenszyklus-Referenz](#lifecycle "Lebenszyklus-Referenz")

<div id="introduction"></div>

## Einleitung

State-verwaltete Widgets ermöglichen es Ihnen, bestimmte Teile Ihrer UI von überall in der App zu aktualisieren — ohne ganze Seiten neu aufzubauen. In {{ config('app.name') }} v7 lösen Sie benannte **State-Aktionen** auf einem Ziel-Widget oder einer Ziel-Seite aus, und der entsprechende Handler wird ausgeführt.

Das mentale Modell ist einfach:

- Jedes state-verwaltete Widget oder jede state-verwaltete Seite hat einen eindeutigen **State-Schlüssel** (eine Zeichenkette)
- Es definiert eine Map von **benannten Aktionen**, die es verarbeiten kann
- Von überall in Ihrer App können Sie aufrufen:
```
stateAction("action_name", state: TargetWidget.state)
``` 

Es gibt drei Muster für den Aufbau von state-verwalteten Oberflächen. Alle verwenden dieselbe `stateAction`-Mechanik — der einzige Unterschied ist, welche Art von Oberfläche Sie verwalten.


<div id="choose-your-pattern"></div>

## Muster wählen

| Sie möchten... | Verwenden Sie | Scaffold-Befehl |
|---|---|---|
| Den State einer vollständigen Seite verwalten | `NyPage` | `metro make:page my_page` |
| Den State eines Widgets mit einer einzelnen Instanz verwalten | `NyState` | `metro make:stateful_widget my_widget` |
| Den State eines Widgets mit mehreren isolierten Instanzen verwalten | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Lesen Sie zuerst den Abschnitt [State-Aktionen](#state-actions) — er erklärt die API, die jedes Muster verwendet. Springen Sie dann zum Muster, das Ihrem Fall entspricht.


<div id="state-actions"></div>

## State-Aktionen

State-Aktionen sind benannte Befehle, die ein Widget oder eine Seite verarbeiten kann. Sie definieren eine Map von Aktionsnamen zu Handler-Funktionen und lösen diese von überall in Ihrer App per Name aus.

Verwenden Sie State-Aktionen, wenn Sie:
- Ein spezifisches Verhalten auf einem Widget oder einer Seite auslösen müssen (nicht nur eine generische Aktualisierung)
- Daten an ein Widget übergeben und es auf eine definierte Weise reagieren lassen möchten
- Wiederverwendbare Widget-Verhaltensweisen erstellen möchten, die von mehreren Aufrufstellen aus ausgelöst werden können

<div id="defining-handlers"></div>

### Handler definieren

Handler befinden sich im `stateActions`-Getter Ihrer `NyState`- oder `NyPage`-Klasse. Die Map-Schlüssel sind Aktionsnamen; die Werte sind Funktionen, die ausgeführt werden, wenn diese Aktion ausgelöst wird.

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

Handler sind selbst dafür verantwortlich, `setState` aufzurufen, wenn sie möchten, dass das Widget neu aufgebaut wird.

Der Handler `apply_discount` oben nimmt ein `code`-Argument entgegen — deklarieren Sie einen einzelnen positionalen Parameter, wenn Ihr Handler die über
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```
übergebene Nutzlast benötigt.

Verwenden Sie die argumentlose Form `()`, wenn die Aktion keine Nutzlast trägt.


<div id="triggering-actions"></div>

### Aktionen auslösen

Verwenden Sie die globale Funktion `stateAction`, um eine Aktion von überall auszulösen — einem anderen Widget, einem Controller, einem Event-Handler, einem API-Callback usw.

``` dart
// Eine Aktion ohne Daten auslösen
stateAction("clear_cart", state: Cart.state);

// Eine Aktion mit Daten auslösen
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

Das Argument `state:` ist der **State-Schlüssel** des Ziels:
- Für Widgets — `MyWidget.state` (eine Zeichenkette)
- Für Seiten — `MyPage.path` (die Route)


<div id="handlers-with-and-without-data"></div>

### Handler mit und ohne Daten

Handler können synchron oder asynchron sein und mit oder ohne `data`-Argument definiert werden:

``` dart
@override
Map<String, Function> get stateActions => {
  // Ohne Daten — Handler wird direkt ausgeführt
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Mit Daten — empfängt, was über das `data:`-Argument übergeben wurde
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async wird unterstützt — das Framework wartet auf den Handler
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Wenn Sie `data:` an `stateAction` übergeben, aber Ihr Handler keine Argumente entgegennimmt, werden die Daten einfach ignoriert.


<div id="using-a-state-actions-instance"></div>

### Eine StateActions-Instanz verwenden

Wenn ein Widget eine typisierte `StateActions`-Instanz bereitstellt (oft über eine statische Methode `stateActions(stateName)`), können Sie `.action(...)` direkt darauf aufrufen, anstatt die freie Funktion zu verwenden. Dies ist übersichtlicher, wenn mehrere Aktionen an dasselbe Ziel gesendet werden:

``` dart
// Mit der freien Funktion
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Mit einer StateActions-Instanz — äquivalent, weniger Wiederholung
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Mehrere eingebaute Widgets (`InputField`, `CollectionView`, `LanguageSwitcher`, die `NyForm*`-Familie) werden mit typisierten `StateActions`-Klassen mit benannten Methoden wie `.clear()`, `.setValue(...)`, `.refresh()` geliefert — prüfen Sie die Widget-Dokumentation für die verfügbaren Methoden.


<div id="pattern-ny-page"></div>

## Muster: State-verwaltete Seiten (NyPage)

Verwenden Sie dies, wenn Sie von anderswo in Ihrer App ein Verhalten auf einer vollständigen Seite auslösen möchten — zum Beispiel eine Seite bei einem Ereignis aktualisieren oder den Formularstatus aus einem untergeordneten Widget heraus löschen.

**Schritt 1:** Seite erstellen (Scaffolding).

``` bash
metro make:page my_page
```

Dies generiert eine `NyPage` in `lib/resources/pages/`:

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

**Schritt 2:** `stateManaged` auf `true` setzen.

Standardmäßig abonnieren sich Seiten **nicht** für State-Ereignisse — dies vermeidet unnötige Listener auf Seiten, die diese nicht benötigen. Um State-Aktionen auf einer Seite zu aktivieren, ändern Sie den Getter:

``` dart
@override
bool get stateManaged => true;
```

**Schritt 3:** Eine `stateActions`-Map hinzufügen.

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

**Schritt 4:** Die Aktion von überall aus dem `path` der Seite auslösen.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Seiten verwenden `MyPage.path` als Schlüssel, Widgets verwenden `MyWidget.state`. Dies ist der einzige Unterschied an der Aufrufstelle.


<div id="pattern-ny-state"></div>

## Muster: Stateful Widgets (NyState)

Verwenden Sie dies für wiederverwendbare Widgets, die als einzelne Instanz pro Seite existieren — eine Profilkarte, eine Kopfzeile, eine Statusanzeige. Wenn Sie jeweils nur eine Instanz des Widgets rendern, ist dies das richtige Muster.

**Schritt 1:** Stateful Widget erstellen (Scaffolding).

``` bash
metro make:stateful_widget profile_card
```

Dies generiert folgendes in `lib/resources/widgets/`:

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

Das Scaffolding liefert Ihnen ein funktionsfähiges `NyState`-Widget — aber es ist **noch nicht state-verwaltet**. Damit es auf State-Aktionen reagiert, müssen Sie vier Dinge hinzufügen:

1. Einen `state`-Schlüssel auf der Widget-Klasse — die eindeutige Zeichenkette, auf die andere Teile Ihrer App abzielen werden
2. Einen Konstruktorparameter, der den State-Schlüssel empfängt und an die State-Klasse weiterleitet
3. Einen Konstruktor auf der State-Klasse, der `stateName` zuweist
4. Eine `stateActions`-Map, die die Handler definiert

**Schritt 2:** Das Scaffold in ein state-verwaltetes Widget umwandeln.

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
    // ^ rufen Sie dies von überall in Ihrer App auf, um den Handler unten auszulösen
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

Was sich geändert hat:
- `static String get state => "profile_card";` definiert den öffentlichen State-Schlüssel
- `createState() => _ProfileCardState(state)` übergibt den Schlüssel an die State-Klasse
- `_ProfileCardState(String? state) { this.stateName = state; }` registriert diese State-Instanz unter diesem Schlüssel, damit das Framework weiß, an welches Widget die Aktion geliefert werden soll
- `stateActions` deklariert die benannten Handler

**Schritt 3:** Von überall auslösen.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Sie können beliebig viele Handler hinzufügen, mit oder ohne Daten:

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

## Muster: State-verwaltete Widgets (NyStateManaged)

Verwenden Sie dies, wenn Sie **mehrere unabhängige Instanzen desselben Widgets** gleichzeitig auf dem Bildschirm benötigen — zum Beispiel ein Warenkorb-Badge in der Kopfzeile und ein weiteres in einer Seitenleiste, die sich unabhängig voneinander aktualisieren sollen.

`NyStateManaged` fügt einen `id`-Parameter hinzu, damit jede Instanz separat angesprochen werden kann. Wenn Sie nur jeweils eine Instanz des Widgets rendern, bevorzugen Sie stattdessen `NyState` — es ist einfacher.

**Schritt 1:** State-verwaltetes Widget erstellen (Scaffolding).

``` bash
metro make:state_managed_widget cart
```

Dies generiert folgendes in `lib/resources/widgets/`:

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
    // Initialisierungslogik hier
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Aktionen von überall in Ihrer App aufrufen
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

**Schritt 2:** Den State ausbauen — Daten in `init` laden, Handler definieren und rendern.

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

**Schritt 3:** Aktionen mit dem generierten statischen `action()`-Helper auslösen.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Dies ist äquivalent zu `stateAction("reload_cart", state: Cart.state)` — der statische Helper entfernt lediglich den Boilerplate-Code.


<div id="advanced-multiple-isolated-instances"></div>

### Fortgeschritten: Mehrere isolierte Instanzen

Der Grund für die Existenz von `NyStateManaged` ist die Unterstützung mehrerer unabhängiger Instanzen desselben Widgets. Jede Instanz erhält ihre eigene `id`, die einen Namespace-State-Schlüssel erzeugt.

Zwei Warenkörbe mit unterschiedlichen IDs rendern:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Nun können Sie jeden unabhängig aktualisieren:

``` dart
// Nur den Warenkorb in der Kopfzeile neu laden
Cart.action("reload_cart", id: "header");

// Nur den Warenkorb in der Seitenleiste neu laden
Cart.action("reload_cart", id: "sidebar");

// Keine id — zielt auf die unbenannte Standardinstanz
Cart.action("reload_cart");
```

`NyStateManaged` verwaltet die Namespaces: `Cart(id: "header")` wird unter dem Schlüssel `"cart_header"` registriert, und `Cart.action(..., id: "header")` zielt genau auf diesen Schlüssel ab.


<div id="lifecycle"></div>

## Lebenszyklus-Referenz

State-verwaltete Widgets und Seiten teilen zwei wichtige Lebenszyklus-Hooks:

1. **`init()`** — wird einmalig aufgerufen, wenn der State erstmals erstellt wird. Verwenden Sie ihn zum Laden von Anfangsdaten.

2. **`stateUpdated(data)`** — wird aufgerufen, wenn eine State-Aktion gegen diesen State ausgelöst wird. Das Argument `data` ist die vollständige Nutzlast (einschließlich des Aktionsnamens und der Aktionsdaten). Überschreiben Sie ihn, wenn Sie auf *jede* State-Aktion reagieren müssen — meistens ist die Definition von Handlern in `stateActions` die bessere Wahl.

**Siehe auch:** [NyState](/docs/{{ $version }}/ny-state) für den vollständigen Satz von State-Helfern und Lebenszyklus-Methoden.
