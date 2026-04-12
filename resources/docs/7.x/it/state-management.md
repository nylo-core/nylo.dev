# Gestione dello Stato

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Quando Usare la Gestione dello Stato](#when-to-use-state-management "Quando Usare la Gestione dello Stato")
- [Ciclo di Vita](#lifecycle "Ciclo di Vita")
- [Azioni sullo Stato](#state-actions "Azioni sullo Stato")
  - [NyState - Azioni sullo Stato](#state-actions-nystate "NyState - Azioni sullo Stato")
  - [NyPage - Azioni sullo Stato](#state-actions-nypage "NyPage - Azioni sullo Stato")
- [Aggiornamento di uno Stato](#updating-a-state "Aggiornamento di uno Stato")
- [Costruire il Tuo Primo Widget](#building-your-first-widget "Costruire il Tuo Primo Widget")

<div id="introduction"></div>

## Introduzione

La gestione dello stato ti permette di aggiornare parti specifiche della tua UI senza ricostruire intere pagine. In {{ config('app.name') }} v7, puoi costruire widget che comunicano e si aggiornano a vicenda in tutta la tua app.

{{ config('app.name') }} fornisce due classi per la gestione dello stato:
- **`NyState`** — Per costruire widget riutilizzabili (come un badge del carrello, un contatore di notifiche o un indicatore di stato)
- **`NyPage`** — Per costruire pagine nella tua applicazione (estende `NyState` con funzionalita' specifiche per le pagine)

Usa la gestione dello stato quando hai bisogno di:
- Aggiornare un widget da una parte diversa della tua app
- Mantenere i widget sincronizzati con dati condivisi
- Evitare di ricostruire intere pagine quando cambia solo una parte della UI


### Comprendiamo prima la Gestione dello Stato

In Flutter tutto e' un widget, sono piccole porzioni di interfaccia che puoi combinare per creare un'app completa.

Quando inizi a costruire pagine complesse, avrai bisogno di gestire lo stato dei tuoi widget. Questo significa che quando qualcosa cambia, ad esempio i dati, puoi aggiornare quel widget senza dover ricostruire l'intera pagina.

Ci sono molte ragioni per cui questo e' importante, ma la ragione principale e' la performance. Se hai un widget che cambia costantemente, non vuoi ricostruire l'intera pagina ogni volta che cambia.

Qui entra in gioco la Gestione dello Stato, ti permette di gestire lo stato di un widget nella tua applicazione.


<div id="when-to-use-state-management"></div>

### Quando Usare la Gestione dello Stato

Dovresti usare la Gestione dello Stato quando hai un widget che deve essere aggiornato senza ricostruire l'intera pagina.

Ad esempio, immaginiamo di aver creato un'app di e-commerce. Hai costruito un widget per mostrare il numero totale di articoli nel carrello dell'utente.
Chiamiamo questo widget `Cart()`.

Un widget `Cart` con gestione dello stato in Nylo apparirebbe cosi':

**Passo 1:** Definisci il widget con un nome di stato statico

``` dart
/// Il widget Cart
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Identificatore univoco per lo stato di questo widget

  @override
  _CartState createState() => _CartState();
}
```

**Passo 2:** Crea la classe di stato che estende `NyState`

``` dart
/// La classe di stato per il widget Cart
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Registra il nome dello stato
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Carica i dati iniziali
  };

  @override
  void stateUpdated(data) {
    reboot(); // Ricarica il widget quando lo stato viene aggiornato
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

**Passo 3:** Crea funzioni helper per leggere e aggiornare il carrello

``` dart
/// Ottieni il valore del carrello dallo storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Imposta il valore del carrello e notifica il widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // Questo attiva stateUpdated() sul widget
}
```

Analizziamo questo codice.

1. Il widget `Cart` e' uno `StatefulWidget`.

2. `_CartState` estende `NyState<Cart>`.

3. Devi definire un nome per lo `state`, questo viene usato per identificare lo stato.

4. Il metodo `boot()` viene chiamato quando il widget viene caricato per la prima volta.

5. I metodi `stateUpdate()` gestiscono cosa succede quando lo stato viene aggiornato.

Se vuoi provare questo esempio nel tuo progetto {{ config('app.name') }}, crea un nuovo widget chiamato `Cart`.

``` bash
metro make:state_managed_widget cart
```

Poi puoi copiare l'esempio sopra e provarlo nel tuo progetto.

Ora, per aggiornare il carrello, puoi chiamare quanto segue.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Ciclo di Vita

Il ciclo di vita di un widget `NyState` e' il seguente:

1. `init()` - Questo metodo viene chiamato quando lo stato viene inizializzato.

2. `stateUpdated(data)` - Questo metodo viene chiamato quando lo stato viene aggiornato.

    Se chiami `updateState(MyStateName.state, data: "The Data")`, attivera' la chiamata di **stateUpdated(data)**.

Una volta che lo stato e' stato inizializzato per la prima volta, dovrai implementare come vuoi gestire lo stato.


<div id="state-actions"></div>

## Azioni sullo Stato

Le azioni sullo stato ti permettono di attivare metodi specifici su un widget da qualsiasi punto della tua app. Pensale come comandi con un nome che puoi inviare a un widget.

Usa le azioni sullo stato quando hai bisogno di:
- Attivare un comportamento specifico su un widget (non solo aggiornarlo)
- Passare dati a un widget e farlo rispondere in un modo particolare
- Creare comportamenti riutilizzabili per i widget che possono essere invocati da piu' punti

``` dart
// Invia un'azione al widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Un altro esempio con dati
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

Nel tuo widget, puoi definire le azioni che vuoi gestire.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Esempio con dati
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Poi, puoi chiamare il metodo `stateAction` da qualsiasi punto della tua applicazione.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Se hai gia' un'istanza di `StateActions` (ad esempio dal metodo statico `stateActions()` di un widget), puoi chiamare `action()` direttamente su di essa invece di usare la funzione libera:

``` dart
// Usando la funzione libera
stateAction('reset_avatar', state: UserAvatar.state);

// Usando il metodo dell'istanza StateActions — equivalente, meno ripetizioni
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action('reset_avatar');
actions.action('update_user_image', data: user);
```

Puoi anche definire le tue azioni sullo stato usando il metodo `whenStateAction` nel tuo getter `init`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reimposta il conteggio del badge
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - Azioni sullo Stato

Per prima cosa, crea un widget stateful.

``` bash
metro make:stateful_widget [widget_name]
```
Esempio: metro make:stateful_widget user_avatar

Questo creera' un nuovo widget nella directory `lib/resources/widgets/`.

Se apri quel file, potrai definire le tue azioni sullo stato.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Esempio
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Esempio
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Infine, puoi inviare l'azione da qualsiasi punto della tua applicazione.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// stampa 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reimposta i dati nel widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// mostra un toast di successo con il messaggio
```


<div id="state-actions-nypage"></div>

### NyPage - Azioni sullo Stato

Anche le pagine possono ricevere azioni sullo stato. Questo e' utile quando vuoi attivare comportamenti a livello di pagina da widget o altre pagine.

Per prima cosa, crea la tua pagina con gestione dello stato.

``` bash
metro make:page my_page
```

Questo creera' una nuova pagina con gestione dello stato chiamata `MyPage` nella directory `lib/resources/pages/`.

Se apri quel file, potrai definire le tue azioni sullo stato.

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => false; // impostare a true per abilitare le azioni sullo stato su questa pagina

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // Esempio
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Infine, puoi inviare l'azione da qualsiasi punto della tua applicazione.

``` dart
stateAction('test_page_action', state: MyPage.path);
// stampa 'Hello from the page'

stateAction('reset_data', state: MyPage.path);
// Reimposta i dati nella pagina

stateAction('show_toast', state: MyPage.path, data: {
  "message": "Hello from the page"
});
// mostra un toast di successo con il messaggio
```

Puoi anche definire le tue azioni sullo stato usando il metodo `whenStateAction`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reimposta il conteggio del badge
      _count = 0;
    }
  });
}
```

Poi puoi inviare l'azione da qualsiasi punto della tua applicazione.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Aggiornamento di uno Stato

Puoi aggiornare uno stato chiamando il metodo `updateState()`.

``` dart
updateState(MyStateName.state);

// o con dati
updateState(MyStateName.state, data: "The Data");
```

Questo puo' essere chiamato da qualsiasi punto della tua applicazione.

**Vedi anche:** [NyState](/docs/{{ $version }}/ny-state) per maggiori dettagli sugli helper di gestione dello stato e i metodi del ciclo di vita.


<div id="building-your-first-widget"></div>

## Costruire il Tuo Primo Widget

Nel tuo progetto Nylo, esegui il comando seguente per creare un nuovo widget.

``` bash
metro make:stateful_widget todo_list
```

Questo creera' un nuovo widget `NyState` chiamato `TodoList`.

> Nota: Il nuovo widget verra' creato nella directory `lib/resources/widgets/`.
