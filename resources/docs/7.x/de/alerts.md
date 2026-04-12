# Benachrichtigungen

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Integrierte Stile](#built-in-styles "Integrierte Stile")
- [Benachrichtigungen von Seiten anzeigen](#from-pages "Benachrichtigungen von Seiten anzeigen")
- [Benachrichtigungen von Controllern anzeigen](#from-controllers "Benachrichtigungen von Controllern anzeigen")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Positionierung](#positioning "Positionierung")
- [Benutzerdefinierte Toast-Stile](#custom-styles "Benutzerdefinierte Toast-Stile")
  - [Stile registrieren](#registering-styles "Stile registrieren")
  - [Eine Style-Factory erstellen](#creating-a-style-factory "Eine Style-Factory erstellen")
  - [Datenbewusste Toast-Stile](#data-aware-toast-styles "Datenbewusste Toast-Stile")
- [AlertTab](#alert-tab "AlertTab")
- [Beispiele](#examples "Beispiele")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} bietet ein Toast-Benachrichtigungssystem zur Anzeige von Meldungen an Benutzer. Es wird mit vier integrierten Stilen ausgeliefert -- **success**, **warning**, **info** und **danger** -- und unterstuetzt benutzerdefinierte Stile ueber ein Registry-Muster.

Benachrichtigungen koennen von Seiten, Controllern oder ueberall dort ausgeloest werden, wo Sie einen `BuildContext` haben.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Zeigen Sie eine Toast-Benachrichtigung mit Komfortmethoden in jeder `NyState`-Seite an:

``` dart
// Erfolgs-Toast
showToastSuccess(description: "Item saved successfully");

// Warnungs-Toast
showToastWarning(description: "Your session is about to expire");

// Info-Toast
showToastInfo(description: "New version available");

// Fehler-Toast
showToastDanger(description: "Failed to save item");
```

Oder verwenden Sie die globale Funktion mit einer Stil-ID:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Integrierte Stile

{{ config('app.name') }} registriert vier Standard-Toast-Stile:

| Stil-ID | Symbol | Farbe | Standardtitel |
|---------|--------|-------|---------------|
| `success` | Haekchen | Gruen | Success |
| `warning` | Ausrufezeichen | Orange | Warning |
| `info` | Info-Symbol | Tuerkis | Info |
| `danger` | Warnsymbol | Rot | Error |

Diese werden in `lib/config/toast_notification.dart` konfiguriert:

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

## Benachrichtigungen von Seiten anzeigen

In jeder Seite, die `NyState` oder `NyBaseState` erweitert, verwenden Sie diese Komfortmethoden:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Erfolg
    showToastSuccess(description: "Saved!");

    // Mit benutzerdefiniertem Titel
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warnung
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Fehler
    showToastDanger(description: "Something went wrong");

    // Oops (verwendet Fehler-Stil)
    showToastOops(description: "That didn't work");

    // Sorry (verwendet Fehler-Stil)
    showToastSorry(description: "We couldn't process your request");

    // Benutzerdefinierter Stil per ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Allgemeine Toast-Methode

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Benachrichtigungen von Controllern anzeigen

Controller, die `NyController` erweitern, verfuegen ueber dieselben Komfortmethoden:

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

Verfuegbare Methoden: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

Die globale Funktion `showToastNotification()` zeigt einen Toast ueberall dort an, wo Sie einen `BuildContext` haben:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Wird aufgerufen, wenn der Toast angetippt wird
    routeTo("/details");
  },
  onDismiss: () {
    // Wird aufgerufen, wenn der Toast geschlossen wird
  },
  onShow: () {
    // Wird aufgerufen, wenn der Toast sichtbar wird
  },
);
```

### Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `context` | `BuildContext` | erforderlich | Build-Kontext |
| `id` | `String` | `'success'` | Toast-Stil-ID |
| `title` | `String?` | null | Titeltext; wird unveraendert an das Toast-Widget weitergegeben |
| `description` | `String?` | null | Beschreibungstext |
| `data` | `Map<String, dynamic>?` | null | Schluessel-Wert-Paare, die an datenbewusste Toast-Stile weitergegeben werden; `title` und `description` haben Vorrang vor uebereinstimmenden Schluesseln in `data` |
| `duration` | `Duration?` | null | Wie lange der Toast angezeigt wird |
| `position` | `ToastNotificationPosition?` | null | Position auf dem Bildschirm |
| `action` | `VoidCallback?` | null | Tipp-Callback |
| `onDismiss` | `VoidCallback?` | null | Schliessen-Callback |
| `onShow` | `VoidCallback?` | null | Anzeige-Callback |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` kapselt alle Daten fuer eine Toast-Benachrichtigung:

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

### Eigenschaften

| Eigenschaft | Typ | Standard | Beschreibung |
|-------------|-----|---------|-------------|
| `icon` | `Widget?` | null | Symbol-Widget |
| `title` | `String` | `''` | Titeltext |
| `style` | `String` | `''` | Stilbezeichner |
| `description` | `String` | `''` | Beschreibungstext |
| `color` | `Color?` | null | Hintergrundfarbe fuer den Symbolbereich |
| `action` | `VoidCallback?` | null | Tipp-Callback |
| `dismiss` | `VoidCallback?` | null | Schliessen-Schaltflaechen-Callback |
| `onDismiss` | `VoidCallback?` | null | Automatisches/manuelles Schliessen-Callback |
| `onShow` | `VoidCallback?` | null | Sichtbarkeits-Callback |
| `duration` | `Duration` | 5 Sekunden | Anzeigedauer |
| `position` | `ToastNotificationPosition` | `top` | Bildschirmposition |
| `metaData` | `Map<String, dynamic>?` | null | Benutzerdefinierte Metadaten |

### copyWith

Erstellen Sie eine modifizierte Kopie von `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Positionierung

Steuern Sie, wo Toasts auf dem Bildschirm erscheinen:

``` dart
// Oben auf dem Bildschirm (Standard)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Unten auf dem Bildschirm
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Mitte des Bildschirms
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Benutzerdefinierte Toast-Stile

<div id="registering-styles"></div>

### Stile registrieren

Registrieren Sie benutzerdefinierte Stile in Ihrem `AppProvider`:

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

Oder fuegen Sie Stile jederzeit hinzu:

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

Dann verwenden Sie es:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Eine Style-Factory erstellen

`ToastNotification.style()` erstellt eine `ToastStyleFactory`:

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

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `icon` | `Widget` | Symbol-Widget fuer den Toast |
| `color` | `Color` | Hintergrundfarbe fuer den Symbolbereich |
| `defaultTitle` | `String` | Titel, der angezeigt wird, wenn keiner angegeben ist |
| `position` | `ToastNotificationPosition?` | Standardposition |
| `duration` | `Duration?` | Standarddauer |
| `builder` | `Widget Function(ToastMeta)?` | Benutzerdefinierter Widget-Builder fuer vollstaendige Kontrolle |

### Vollstaendig benutzerdefinierter Builder

Fuer vollstaendige Kontrolle ueber das Toast-Widget:

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

### Datenbewusste Toast-Stile

<!-- uncertain: new Nylo-specific term "ToastStyleDataFactory" — not seen in existing locale file -->
Verwenden Sie `ToastStyleDataFactory`, um Toast-Stile zu registrieren, die zur Laufzeit Daten erhalten. Dies ist nuetzlich, wenn der Toast-Inhalt -- z. B. Name oder Avatar eines Benutzers -- zum Registrierungszeitpunkt noch nicht bekannt ist.

``` dart
typedef ToastStyleDataFactory =
    ToastStyleFactory Function(Map<String, dynamic> data);
```

<!-- uncertain: new method "registerWithData()" — not seen in existing locale file -->
Registrieren Sie einen datenbewussten Stil mit `registerWithData()`:

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

Oder registrieren Sie ihn zusammen mit statischen Stilen in Ihrem `AppProvider`:

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

Rufen Sie es zur Laufzeit mit einer `data`-Map auf:

``` dart
showToastNotification(
  context,
  id: 'new_follower',
  data: {'name': 'Alice', 'avatar': 'https://example.com/alice.jpg'},
);
```

Wenn Sie zusaetzlich `title` oder `description` uebergeben, haben diese Vorrang vor uebereinstimmenden Schluesseln in `data`.

Verwenden Sie `ToastNotificationRegistry.resolve(id, data)` direkt, wenn Sie das Widget selbst erstellen moechten:

``` dart
final factory = ToastNotificationRegistry.instance.resolve('new_follower', data);
if (factory != null) {
  final widget = factory(toastMeta, (updated) {});
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` ist ein Badge-Widget zum Hinzufuegen von Benachrichtigungsindikatoren zu Navigations-Tabs. Es zeigt ein Badge an, das umgeschaltet und optional im Speicher persistiert werden kann.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `state` | `String` | erforderlich | Zustandsname zur Verfolgung |
| `alertEnabled` | `bool?` | null | Ob das Badge angezeigt wird |
| `rememberAlert` | `bool?` | `true` | Badge-Zustand im Speicher persistieren |
| `icon` | `Widget?` | null | Tab-Symbol |
| `backgroundColor` | `Color?` | null | Tab-Hintergrund |
| `textColor` | `Color?` | null | Badge-Textfarbe |
| `alertColor` | `Color?` | null | Badge-Hintergrundfarbe |
| `smallSize` | `double?` | null | Kleine Badge-Groesse |
| `largeSize` | `double?` | null | Grosse Badge-Groesse |
| `textStyle` | `TextStyle?` | null | Badge-Textstil |
| `padding` | `EdgeInsetsGeometry?` | null | Badge-Abstand |
| `alignment` | `Alignment?` | null | Badge-Ausrichtung |
| `offset` | `Offset?` | null | Badge-Versatz |
| `isLabelVisible` | `bool?` | `true` | Badge-Beschriftung anzeigen |

### Factory-Konstruktor

Erstellen Sie aus einem `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Beispiele

### Erfolgsbenachrichtigung nach dem Speichern

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

### Interaktiver Toast mit Aktion

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

### Warnung am unteren Bildschirmrand

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
