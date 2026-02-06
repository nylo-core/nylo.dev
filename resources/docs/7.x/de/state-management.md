# State-Management

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Wann State-Management verwenden](#when-to-use-state-management "Wann State-Management verwenden")
- [Lebenszyklus](#lifecycle "Lebenszyklus")
- [State-Aktionen](#state-actions "State-Aktionen")
  - [NyState - State-Aktionen](#state-actions-nystate "NyState - State-Aktionen")
  - [NyPage - State-Aktionen](#state-actions-nypage "NyPage - State-Aktionen")
- [Einen State aktualisieren](#updating-a-state "Einen State aktualisieren")
- [Ihr erstes Widget erstellen](#building-your-first-widget "Ihr erstes Widget erstellen")

<div id="introduction"></div>

## Einleitung

State-Management ermoeglicht es Ihnen, bestimmte Teile Ihrer UI zu aktualisieren, ohne ganze Seiten neu aufzubauen. In {{ config('app.name') }} v7 koennen Sie Widgets erstellen, die ueber Ihre gesamte App hinweg kommunizieren und sich gegenseitig aktualisieren.

{{ config('app.name') }} bietet zwei Klassen fuer State-Management:
- **`NyState`** — Zum Erstellen wiederverwendbarer Widgets (wie ein Warenkorb-Badge, Benachrichtigungszaehler oder Statusanzeige)
- **`NyPage`** — Zum Erstellen von Seiten in Ihrer Anwendung (erweitert `NyState` mit seitenspezifischen Funktionen)

Verwenden Sie State-Management, wenn Sie:
- Ein Widget von einem anderen Teil Ihrer App aus aktualisieren muessen
- Widgets mit gemeinsamen Daten synchron halten muessen
- Vermeiden moechten, ganze Seiten neu aufzubauen, wenn sich nur ein Teil der UI aendert


### Zuerst das State-Management verstehen

Alles in Flutter ist ein Widget, sie sind nur kleine Teile der UI, die Sie kombinieren koennen, um eine vollstaendige App zu erstellen.

Wenn Sie anfangen, komplexe Seiten zu erstellen, muessen Sie den State Ihrer Widgets verwalten. Das bedeutet, wenn sich etwas aendert, z.B. Daten, koennen Sie dieses Widget aktualisieren, ohne die gesamte Seite neu aufbauen zu muessen.

Es gibt viele Gruende, warum dies wichtig ist, aber der Hauptgrund ist die Performance. Wenn Sie ein Widget haben, das sich staendig aendert, moechten Sie nicht die gesamte Seite jedes Mal neu aufbauen, wenn es sich aendert.

Hier kommt das State-Management ins Spiel, es ermoeglicht Ihnen, den State eines Widgets in Ihrer Anwendung zu verwalten.


<div id="when-to-use-state-management"></div>

### Wann State-Management verwenden

Sie sollten State-Management verwenden, wenn Sie ein Widget haben, das aktualisiert werden muss, ohne die gesamte Seite neu aufzubauen.

Stellen Sie sich beispielsweise vor, Sie haben eine E-Commerce-App erstellt. Sie haben ein Widget erstellt, um die Gesamtanzahl der Artikel im Warenkorb des Benutzers anzuzeigen.
Nennen wir dieses Widget `Cart()`.

Ein state-verwaltetes `Cart`-Widget in Nylo wuerde etwa so aussehen:

**Schritt 1:** Definieren Sie das Widget mit einem statischen State-Namen

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**Schritt 2:** Erstellen Sie die State-Klasse, die `NyState` erweitert

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

**Schritt 3:** Erstellen Sie Hilfsfunktionen zum Lesen und Aktualisieren des Warenkorbs

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

Lassen Sie uns das aufschluesseln.

1. Das `Cart`-Widget ist ein `StatefulWidget`.

2. `_CartState` erweitert `NyState<Cart>`.

3. Sie muessen einen Namen fuer den `state` definieren, dieser wird verwendet, um den State zu identifizieren.

4. Die `boot()`-Methode wird aufgerufen, wenn das Widget zum ersten Mal geladen wird.

5. Die `stateUpdate()`-Methoden behandeln, was passiert, wenn der State aktualisiert wird.

Wenn Sie dieses Beispiel in Ihrem {{ config('app.name') }}-Projekt ausprobieren moechten, erstellen Sie ein neues Widget namens `Cart`.

``` bash
metro make:state_managed_widget cart
```

Dann koennen Sie das obige Beispiel kopieren und in Ihrem Projekt ausprobieren.

Um den Warenkorb zu aktualisieren, koennen Sie Folgendes aufrufen.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Lebenszyklus

Der Lebenszyklus eines `NyState`-Widgets ist wie folgt:

1. `init()` - Diese Methode wird aufgerufen, wenn der State initialisiert wird.

2. `stateUpdated(data)` - Diese Methode wird aufgerufen, wenn der State aktualisiert wird.

    Wenn Sie `updateState(MyStateName.state, data: "The Data")` aufrufen, wird **stateUpdated(data)** ausgeloest.

Sobald der State zum ersten Mal initialisiert ist, muessen Sie implementieren, wie Sie den State verwalten moechten.


<div id="state-actions"></div>

## State-Aktionen

State-Aktionen ermoelichen es Ihnen, bestimmte Methoden auf einem Widget von ueberall in Ihrer App auszuloesen. Stellen Sie sie sich als benannte Befehle vor, die Sie an ein Widget senden koennen.

Verwenden Sie State-Aktionen, wenn Sie:
- Ein bestimmtes Verhalten auf einem Widget ausloesen moechten (nicht nur aktualisieren)
- Daten an ein Widget uebergeben moechten und es auf eine bestimmte Weise reagieren soll
- Wiederverwendbare Widget-Verhaltensweisen erstellen moechten, die von mehreren Stellen aufgerufen werden koennen

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

In Ihrem Widget koennen Sie die Aktionen definieren, die Sie behandeln moechten.

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

Dann koennen Sie die `stateAction`-Methode von ueberall in Ihrer Anwendung aufrufen.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Sie koennen Ihre State-Aktionen auch mit der `whenStateAction`-Methode in Ihrem `init`-Getter definieren.

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

### NyState - State-Aktionen

Erstellen Sie zuerst ein Stateful Widget.

``` bash
metro make:stateful_widget [widget_name]
```
Beispiel: metro make:stateful_widget user_avatar

Dies erstellt ein neues Widget im Verzeichnis `lib/resources/widgets/`.

Wenn Sie diese Datei oeffnen, koennen Sie Ihre State-Aktionen definieren.

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

Schliesslich koennen Sie die Aktion von ueberall in Ihrer Anwendung senden.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - State-Aktionen

Seiten koennen ebenfalls State-Aktionen empfangen. Dies ist nuetzlich, wenn Sie seitenbasierte Verhaltensweisen von Widgets oder anderen Seiten ausloesen moechten.

Erstellen Sie zuerst Ihre state-verwaltete Seite.

``` bash
metro make:page my_page
```

Dies erstellt eine neue state-verwaltete Seite namens `MyPage` im Verzeichnis `lib/resources/pages/`.

Wenn Sie diese Datei oeffnen, koennen Sie Ihre State-Aktionen definieren.

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

Schliesslich koennen Sie die Aktion von ueberall in Ihrer Anwendung senden.

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

Sie koennen Ihre State-Aktionen auch mit der `whenStateAction`-Methode definieren.

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

Dann koennen Sie die Aktion von ueberall in Ihrer Anwendung senden.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Einen State aktualisieren

Sie koennen einen State aktualisieren, indem Sie die `updateState()`-Methode aufrufen.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

Dies kann von ueberall in Ihrer Anwendung aufgerufen werden.

**Siehe auch:** [NyState](/docs/{{ $version }}/ny-state) fuer weitere Details zu State-Management-Helfern und Lebenszyklus-Methoden.


<div id="building-your-first-widget"></div>

## Ihr erstes Widget erstellen

Fuehren Sie in Ihrem Nylo-Projekt den folgenden Befehl aus, um ein neues Widget zu erstellen.

``` bash
metro make:stateful_widget todo_list
```

Dies erstellt ein neues `NyState`-Widget namens `TodoList`.

> Hinweis: Das neue Widget wird im Verzeichnis `lib/resources/widgets/` erstellt.
