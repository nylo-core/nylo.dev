# Alerts

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Stili Integrati](#built-in-styles "Stili Integrati")
- [Mostrare Avvisi dalle Pagine](#from-pages "Mostrare Avvisi dalle Pagine")
- [Mostrare Avvisi dai Controller](#from-controllers "Mostrare Avvisi dai Controller")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Posizionamento](#positioning "Posizionamento")
- [Stili Toast Personalizzati](#custom-styles "Stili Toast Personalizzati")
  - [Registrare gli Stili](#registering-styles "Registrare gli Stili")
  - [Creare una Style Factory](#creating-a-style-factory "Creare una Style Factory")
  - [Stili Toast con Dati](#data-aware-toast-styles "Stili Toast con Dati")
- [AlertTab](#alert-tab "AlertTab")
- [Esempi](#examples "Esempi")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} fornisce un sistema di notifiche toast per mostrare avvisi agli utenti. Include quattro stili integrati -- **success**, **warning**, **info** e **danger** -- e supporta stili personalizzati tramite un pattern di registro.

Gli avvisi possono essere attivati dalle pagine, dai controller o ovunque si abbia un `BuildContext`.

<div id="basic-usage"></div>

## Utilizzo Base

Mostra una notifica toast usando i metodi di convenienza in qualsiasi pagina `NyState`:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast — la description e' opzionale
showToastInfo();

// Danger toast con durata personalizzata
showToastDanger(description: "Failed to save item", duration: Duration(seconds: 5));
```

Oppure usa la funzione globale con un ID di stile:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Stili Integrati

{{ config('app.name') }} registra quattro stili toast predefiniti:

| ID Stile | Icona | Colore | Titolo Predefinito |
|----------|------|-------|---------------|
| `success` | Segno di spunta | Verde | Success |
| `warning` | Punto esclamativo | Arancione | Warning |
| `info` | Icona info | Teal | Info |
| `danger` | Icona avviso | Rosso | Error |

Questi sono configurati in `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Mostrare Avvisi dalle Pagine

In qualsiasi pagina che estende `NyState` o `NyBaseState`, usa questi metodi di convenienza:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success — la description e' opzionale
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning con durata personalizzata
    showToastWarning(description: "Check your input", duration: Duration(seconds: 4));

    // Info — nessuna description richiesta
    showToastInfo();

    // Danger con payload di dati inoltrato agli stili con dati
    showToastDanger(description: "Something went wrong", data: {"code": "ERR_500"});

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Metodo Toast Generico

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Mostrare Avvisi dai Controller

I controller che estendono `NyController` hanno gli stessi metodi di convenienza:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Metodi disponibili: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`. Tutti accettano i parametri opzionali `description`, `duration` (`Duration?`) e `data` (`Map<String, dynamic>?`).

<div id="show-toast-notification"></div>

## showToastNotification

La funzione globale `showToastNotification()` mostra un toast da qualsiasi punto in cui hai un `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Chiamato quando il toast viene toccato
    routeTo("/details");
  },
  onDismiss: () {
    // Chiamato quando il toast viene chiuso
  },
  onShow: () {
    // Chiamato quando il toast diventa visibile
  },
);
```

### Parametri

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | obbligatorio | Build context |
| `id` | `String` | `'success'` | ID dello stile toast |
| `title` | `String?` | null | Testo del titolo; viene passato così com'è al widget toast |
| `description` | `String?` | null | Testo descrittivo |
| `data` | `Map<String, dynamic>?` | null | Coppie chiave-valore passate agli stili toast con dati; `title` e `description` hanno la precedenza sulle chiavi corrispondenti in `data` |
| `duration` | `Duration?` | null | Durata di visualizzazione del toast |
| `position` | `ToastNotificationPosition?` | null | Posizione sullo schermo |
| `action` | `VoidCallback?` | null | Callback al tap |
| `onDismiss` | `VoidCallback?` | null | Callback alla chiusura |
| `onShow` | `VoidCallback?` | null | Callback alla visualizzazione |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` incapsula tutti i dati per una notifica toast:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Proprieta'

| Proprieta' | Tipo | Predefinito | Descrizione |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Widget icona |
| `title` | `String` | `''` | Testo del titolo |
| `style` | `String` | `''` | Identificatore dello stile |
| `description` | `String` | `''` | Testo descrittivo |
| `color` | `Color?` | null | Colore di sfondo per la sezione icona |
| `action` | `VoidCallback?` | null | Callback al tap |
| `dismiss` | `VoidCallback?` | null | Callback del pulsante di chiusura |
| `onDismiss` | `VoidCallback?` | null | Callback chiusura automatica/manuale |
| `onShow` | `VoidCallback?` | null | Callback alla visualizzazione |
| `duration` | `Duration` | 5 secondi | Durata di visualizzazione |
| `position` | `ToastNotificationPosition` | `top` | Posizione sullo schermo |
| `metaData` | `Map<String, dynamic>?` | null | Metadati personalizzati |

### copyWith

Crea una copia modificata di `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Posizionamento

Controlla dove appaiono i toast sullo schermo:

``` dart
// In cima allo schermo (predefinito)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// In fondo allo schermo
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Al centro dello schermo
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Stili Toast Personalizzati

<div id="registering-styles"></div>

### Registrare gli Stili

Registra stili personalizzati nel tuo `AppProvider`:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

Oppure aggiungi stili in qualsiasi momento:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Poi usalo:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Creare una Style Factory

`ToastNotification.style()` crea una `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `icon` | `Widget` | Widget icona per il toast |
| `color` | `Color` | Colore di sfondo per la sezione icona |
| `defaultTitle` | `String` | Titolo mostrato quando non ne viene fornito uno |
| `position` | `ToastNotificationPosition?` | Posizione predefinita |
| `duration` | `Duration?` | Durata predefinita |
| `builder` | `Widget Function(ToastMeta)?` | Builder di widget personalizzato per il controllo completo |

### Builder Completamente Personalizzato

Per il controllo completo sul widget toast:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="data-aware-toast-styles"></div>

### Stili Toast con Dati

Usa `ToastStyleDataFactory` per registrare stili toast che ricevono dati di runtime al momento della chiamata. Questo è utile quando il contenuto del toast — come il nome o l'avatar di un utente — non è noto al momento della registrazione.

``` dart
typedef ToastStyleDataFactory =
    ToastStyleFactory Function(Map<String, dynamic> data);
```

Registra uno stile con dati usando `registerWithData()`:

``` dart
ToastNotificationRegistry.instance.registerWithData(
  'new_follower',
  (data) => (meta, updateMeta) {
    return Container(
      padding: EdgeInsets.all(16),
      child: Row(
        children: [
          CircleAvatar(backgroundImage: NetworkImage(data['avatar'])),
          SizedBox(width: 12),
          Text("${data['name']} followed you"),
        ],
      ),
    );
  },
);
```

Oppure registralo insieme agli stili statici nel tuo `AppProvider`:

``` dart
nylo.addToastNotifications({
  ...ToastNotificationConfig.styles,
  'new_follower': (Map<String, dynamic> data) => (meta, updateMeta) {
    return Container(
      padding: EdgeInsets.all(16),
      child: Row(
        children: [
          CircleAvatar(backgroundImage: NetworkImage(data['avatar'])),
          SizedBox(width: 12),
          Text("${data['name']} followed you"),
        ],
      ),
    );
  },
});
```

Chiamalo con una mappa `data` a runtime:

``` dart
showToastNotification(
  context,
  id: 'new_follower',
  data: {'name': 'Alice', 'avatar': 'https://example.com/alice.jpg'},
);
```

Se passi anche `title` o `description`, hanno la precedenza sulle chiavi corrispondenti in `data`.

Usa `ToastNotificationRegistry.resolve(id, data)` direttamente se hai bisogno di costruire il widget tu stesso:

``` dart
final factory = ToastNotificationRegistry.instance.resolve('new_follower', data);
if (factory != null) {
  final widget = factory(toastMeta, (updated) {});
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` e' un widget badge per aggiungere indicatori di notifica alle tab di navigazione. Mostra un badge che puo' essere attivato/disattivato e opzionalmente persistito nello storage.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parametri

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `state` | `String` | obbligatorio | Nome dello stato per il tracciamento |
| `alertEnabled` | `bool?` | null | Se il badge viene mostrato |
| `rememberAlert` | `bool?` | `true` | Persiste lo stato del badge nello storage |
| `icon` | `Widget?` | null | Icona della tab |
| `backgroundColor` | `Color?` | null | Sfondo della tab |
| `textColor` | `Color?` | null | Colore del testo del badge |
| `alertColor` | `Color?` | null | Colore di sfondo del badge |
| `smallSize` | `double?` | null | Dimensione badge piccolo |
| `largeSize` | `double?` | null | Dimensione badge grande |
| `textStyle` | `TextStyle?` | null | Stile del testo del badge |
| `padding` | `EdgeInsetsGeometry?` | null | Padding del badge |
| `alignment` | `Alignment?` | null | Allineamento del badge |
| `offset` | `Offset?` | null | Offset del badge |
| `isLabelVisible` | `bool?` | `true` | Mostra l'etichetta del badge |

### Costruttore Factory

Crea da un `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Esempi

### Avviso di Successo Dopo il Salvataggio

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Toast Interattivo con Azione

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Avviso di Warning in Basso

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
