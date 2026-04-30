# Alerts

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe uzycie](#basic-usage "Podstawowe uzycie")
- [Wbudowane style](#built-in-styles "Wbudowane style")
- [Wyswietlanie alertow ze stron](#from-pages "Wyswietlanie alertow ze stron")
- [Wyswietlanie alertow z kontrolerow](#from-controllers "Wyswietlanie alertow z kontrolerow")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Pozycjonowanie](#positioning "Pozycjonowanie")
- [Niestandardowe style powiadomien](#custom-styles "Niestandardowe style powiadomien")
  - [Rejestrowanie stylow](#registering-styles "Rejestrowanie stylow")
  - [Tworzenie fabryki stylow](#creating-a-style-factory "Tworzenie fabryki stylow")
  - [Style toasty uwzgledniajace dane](#data-aware-toast-styles "Style toasty uwzgledniajace dane")
- [AlertTab](#alert-tab "AlertTab")
- [Przyklady](#examples "Praktyczne przyklady")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} udostepnia system powiadomien toast do wyswietlania alertow uzytkownikowi. Zawiera cztery wbudowane style -- **success**, **warning**, **info** i **danger** -- oraz obsluguje niestandardowe style poprzez wzorzec rejestru.

Alerty moga byc wywoływane ze stron, kontrolerow lub z dowolnego miejsca, w ktorym masz dostep do `BuildContext`.

<div id="basic-usage"></div>

## Podstawowe uzycie

Wyswietl powiadomienie toast za pomoca metod pomocniczych na dowolnej stronie `NyState`:

``` dart
// Toast sukcesu
showToastSuccess(description: "Item saved successfully");

// Toast ostrzezenia
showToastWarning(description: "Your session is about to expire");

// Toast informacyjny -- opis jest opcjonalny
showToastInfo();

// Toast niebezpieczenstwa z niestandardowym czasem trwania
showToastDanger(description: "Failed to save item", duration: Duration(seconds: 5));
```

Lub uzyj globalnej funkcji z identyfikatorem stylu:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Wbudowane style

{{ config('app.name') }} rejestruje cztery domyslne style powiadomien toast:

| ID stylu | Ikona | Kolor | Domyslny tytul |
|----------|------|-------|---------------|
| `success` | Znacznik | Zielony | Success |
| `warning` | Wykrzyknik | Pomaranczowy | Warning |
| `info` | Ikona informacji | Morski | Info |
| `danger` | Ikona ostrzezenia | Czerwony | Error |

Sa one skonfigurowane w `lib/config/toast_notification.dart`:

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

## Wyswietlanie alertow ze stron

Na dowolnej stronie rozszerzajacej `NyState` lub `NyBaseState` uzyj tych metod pomocniczych:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Sukces -- opis jest opcjonalny
    showToastSuccess(description: "Saved!");

    // Z niestandardowym tytulem
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Ostrzezenie z niestandardowym czasem trwania
    showToastWarning(description: "Check your input", duration: Duration(seconds: 4));

    // Informacja -- opis nie jest wymagany
    showToastInfo();

    // Niebezpieczenstwo z danymi przekazanymi do stylow uwzgledniajacych dane
    showToastDanger(description: "Something went wrong", data: {"code": "ERR_500"});

    // Ups (uzywa stylu niebezpieczenstwa)
    showToastOops(description: "That didn't work");

    // Przepraszam (uzywa stylu niebezpieczenstwa)
    showToastSorry(description: "We couldn't process your request");

    // Niestandardowy styl po ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Ogolna metoda Toast

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Wyswietlanie alertow z kontrolerow

Kontrolery rozszerzajace `NyController` maja te same metody pomocnicze:

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

Dostepne metody: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`. Wszystkie akceptuja opcjonalne parametry `description`, `duration` (`Duration?`) i `data` (`Map<String, dynamic>?`).

<div id="show-toast-notification"></div>

## showToastNotification

Globalna funkcja `showToastNotification()` wyswietla powiadomienie toast z dowolnego miejsca, w ktorym masz `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Wywolywane gdy toast zostanie dotkniety
    routeTo("/details");
  },
  onDismiss: () {
    // Wywolywane gdy toast zostanie odrzucony
  },
  onShow: () {
    // Wywolywane gdy toast stanie sie widoczny
  },
);
```

### Parametry

| Parametr | Typ | Domyslnie | Opis |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | wymagany | Kontekst budowania |
| `id` | `String` | `'success'` | ID stylu powiadomienia toast |
| `title` | `String?` | null | Tekst tytulu; przekazywany bez zmian do widgetu toast |
| `description` | `String?` | null | Tekst opisu |
| `data` | `Map<String, dynamic>?` | null | Pary klucz-wartosc przekazywane do stylow uwzgledniajacych dane; `title` i `description` maja priorytet nad pasujacymi kluczami |
| `duration` | `Duration?` | null | Jak dlugo wyswietlane jest powiadomienie |
| `position` | `ToastNotificationPosition?` | null | Pozycja na ekranie |
| `action` | `VoidCallback?` | null | Callback po dotknięciu |
| `onDismiss` | `VoidCallback?` | null | Callback po odrzuceniu |
| `onShow` | `VoidCallback?` | null | Callback po wyswietleniu |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` enkapsuluje wszystkie dane powiadomienia toast:

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

### Wlasciwosci

| Wlasciwosc | Typ | Domyslnie | Opis |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Widget ikony |
| `title` | `String` | `''` | Tekst tytulu |
| `style` | `String` | `''` | Identyfikator stylu |
| `description` | `String` | `''` | Tekst opisu |
| `color` | `Color?` | null | Kolor tla sekcji ikony |
| `action` | `VoidCallback?` | null | Callback po dotknięciu |
| `dismiss` | `VoidCallback?` | null | Callback przycisku odrzucenia |
| `onDismiss` | `VoidCallback?` | null | Callback automatycznego/recznego odrzucenia |
| `onShow` | `VoidCallback?` | null | Callback po wyswietleniu |
| `duration` | `Duration` | 5 sekund | Czas wyswietlania |
| `position` | `ToastNotificationPosition` | `top` | Pozycja na ekranie |
| `metaData` | `Map<String, dynamic>?` | null | Niestandardowe metadane |

### copyWith

Tworzenie zmodyfikowanej kopii `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Pozycjonowanie

Kontroluj pozycje powiadomien toast na ekranie:

``` dart
// Gora ekranu (domyslnie)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Dol ekranu
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Srodek ekranu
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Niestandardowe style powiadomien

<div id="registering-styles"></div>

### Rejestrowanie stylow

Zarejestruj niestandardowe style w swoim `AppProvider`:

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

Lub dodaj style w dowolnym momencie:

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

Nastepnie uzyj go:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Tworzenie fabryki stylow

`ToastNotification.style()` tworzy `ToastStyleFactory`:

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

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `icon` | `Widget` | Widget ikony powiadomienia |
| `color` | `Color` | Kolor tla sekcji ikony |
| `defaultTitle` | `String` | Tytul wyswietlany, gdy nie podano zadnego |
| `position` | `ToastNotificationPosition?` | Domyslna pozycja |
| `duration` | `Duration?` | Domyslny czas trwania |
| `builder` | `Widget Function(ToastMeta)?` | Niestandardowy builder widgetu dla pelnej kontroli |

### W pelni niestandardowy builder

Dla pelnej kontroli nad widgetem powiadomienia:

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

### Style toasty uwzgledniajace dane

Uzyj `ToastStyleDataFactory` do rejestrowania stylow toast, ktore otrzymuja dane czasu wykonania w momencie wywolania. Jest to przydatne, gdy zawartosc powiadomienia — na przyklad imie uzytkownika lub awatar — nie jest znana w czasie rejestracji.

``` dart
typedef ToastStyleDataFactory =
    ToastStyleFactory Function(Map<String, dynamic> data);
```

<!-- uncertain: new Nylo-specific term "registerWithData" — not seen in existing locale file -->
Zarejestruj styl uwzgledniajacy dane za pomoca `registerWithData()`:

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

Lub zarejestruj go razem ze statycznymi stylami w swoim `AppProvider`:

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

Wywolaj go z mapa `data` w czasie wykonania:

``` dart
showToastNotification(
  context,
  id: 'new_follower',
  data: {'name': 'Alice', 'avatar': 'https://example.com/alice.jpg'},
);
```

Jesli rowniez przekazesz `title` lub `description`, maja one priorytet nad pasujacymi kluczami w `data`.

<!-- uncertain: new Nylo-specific method "ToastNotificationRegistry.resolve" — not seen in existing locale file -->
Uzyj `ToastNotificationRegistry.resolve(id, data)` bezposrednio, jesli musisz samodzielnie zbudowac widget:

``` dart
final factory = ToastNotificationRegistry.instance.resolve('new_follower', data);
if (factory != null) {
  final widget = factory(toastMeta, (updated) {});
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` to widget plakietki do dodawania wskaznikow powiadomien w zakladkach nawigacji. Wyswietla plakietke, ktora moze byc przelaczana i opcjonalnie zapisywana w pamieci.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parametry

| Parametr | Typ | Domyslnie | Opis |
|-----------|------|---------|-------------|
| `state` | `String` | wymagany | Nazwa stanu do sledzenia |
| `alertEnabled` | `bool?` | null | Czy plakietka jest widoczna |
| `rememberAlert` | `bool?` | `true` | Zachowaj stan plakietki w pamieci |
| `icon` | `Widget?` | null | Ikona zakladki |
| `backgroundColor` | `Color?` | null | Tlo zakladki |
| `textColor` | `Color?` | null | Kolor tekstu plakietki |
| `alertColor` | `Color?` | null | Kolor tla plakietki |
| `smallSize` | `double?` | null | Maly rozmiar plakietki |
| `largeSize` | `double?` | null | Duzy rozmiar plakietki |
| `textStyle` | `TextStyle?` | null | Styl tekstu plakietki |
| `padding` | `EdgeInsetsGeometry?` | null | Wypelnienie plakietki |
| `alignment` | `Alignment?` | null | Wyrownanie plakietki |
| `offset` | `Offset?` | null | Przesuniecie plakietki |
| `isLabelVisible` | `bool?` | `true` | Pokaz etykiete plakietki |

### Konstruktor fabryczny

Tworzenie z `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Przyklady

### Alert sukcesu po zapisie

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

### Interaktywne powiadomienie z akcja

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

### Ostrzezenie na dole ekranu

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
