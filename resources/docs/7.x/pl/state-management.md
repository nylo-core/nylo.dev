# Widgety z zarządzaniem stanem

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Wybierz swój wzorzec](#choose-your-pattern "Wybierz swój wzorzec")
- [Akcje stanu](#state-actions "Akcje stanu")
  - [Definiowanie obsługi](#defining-handlers "Definiowanie obsługi")
  - [Wyzwalanie akcji](#triggering-actions "Wyzwalanie akcji")
  - [Obsługa z danymi i bez danych](#handlers-with-and-without-data "Obsługa z danymi i bez danych")
  - [Używanie instancji StateActions](#using-a-state-actions-instance "Używanie instancji StateActions")
- [Wzorzec: Strony z zarządzaniem stanem (NyPage)](#pattern-ny-page "Wzorzec: Strony z zarządzaniem stanem")
- [Wzorzec: Stateful Widgets (NyState)](#pattern-ny-state "Wzorzec: Stateful Widgets")
- [Wzorzec: Widgety z zarządzaniem stanem (NyStateManaged)](#pattern-ny-state-managed "Wzorzec: Widgety z zarządzaniem stanem")
  - [Zaawansowane: Wiele izolowanych instancji](#advanced-multiple-isolated-instances "Zaawansowane: Wiele izolowanych instancji")
- [Informacje o cyklu życia](#lifecycle "Informacje o cyklu życia")

<div id="introduction"></div>

## Wprowadzenie

Widgety z zarządzaniem stanem pozwalają aktualizować określone części interfejsu z dowolnego miejsca w aplikacji — bez przebudowywania całych stron. W {{ config('app.name') }} v7 wywołujesz nazwane **akcje stanu** na docelowym widgecie lub stronie, a odpowiedni obsługujący jest uruchamiany.

Model mentalny jest prosty:

- Każdy widget lub strona z zarządzaniem stanem ma unikalny **klucz stanu** (ciąg znaków)
- Definiuje mapę **nazwanych akcji**, które potrafi obsługiwać
- Z dowolnego miejsca w aplikacji możesz wywołać
```
stateAction("action_name", state: TargetWidget.state)
``` 

Istnieją trzy wzorce budowania interfejsu z zarządzaniem stanem. Wszystkie dzielą ten sam mechanizm `stateAction` — jedyną różnicą jest rodzaj zarządzanego interfejsu.


<div id="choose-your-pattern"></div>

## Wybierz swój wzorzec

| Co chcesz zrobić... | Użyj | Polecenie scaffold |
|---|---|---|
| Zarządzać stanem całej strony | `NyPage` | `metro make:page my_page` |
| Zarządzać stanem widgetu z jedną instancją | `NyState` | `metro make:stateful_widget my_widget` |
| Zarządzać stanem widgetu z wieloma izolowanymi instancjami | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Najpierw przeczytaj sekcję [Akcje stanu](#state-actions) — wyjaśnia API używane przez każdy wzorzec. Następnie przejdź do wzorca pasującego do Twojego przypadku.


<div id="state-actions"></div>

## Akcje stanu

Akcje stanu to nazwane polecenia, które widget lub strona potrafi obsługiwać. Definiujesz mapę nazw akcji do funkcji obsługi i wywołujesz je po nazwie z dowolnego miejsca w aplikacji.

Używaj akcji stanu, gdy potrzebujesz:
- Wywołać określone zachowanie na widgecie lub stronie (nie tylko ogólne odświeżenie)
- Przekazać dane do widgetu i otrzymać określoną odpowiedź
- Budować wielokrotnie używalne zachowania widgetów wywoływalne z wielu miejsc

<div id="defining-handlers"></div>

### Definiowanie obsługi

Obsługa (handlery) znajduje się w getterze `stateActions` klasy `NyState` lub `NyPage`. Klucze mapy to nazwy akcji; wartości to funkcje uruchamiane po wywołaniu danej akcji.

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

Handlery są odpowiedzialne za samodzielne wywoływanie `setState`, jeśli chcą przebudować widget.

Handler `apply_discount` przyjmuje argument `code` — zadeklaruj jeden parametr pozycyjny, gdy handler potrzebuje ładunku przekazanego przez
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Użyj formy bez argumentów `()`, gdy akcja nie niesie ładunku.


<div id="triggering-actions"></div>

### Wyzwalanie akcji

Użyj funkcji globalnej `stateAction`, aby wywołać akcję z dowolnego miejsca — innego widgetu, kontrolera, obsługi zdarzeń, callbacku API itp.

``` dart
// Wywołanie akcji bez danych
stateAction("clear_cart", state: Cart.state);

// Wywołanie akcji z danymi
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

Argument `state:` to **klucz stanu** celu:
- Dla widgetów — `MyWidget.state` (ciąg znaków)
- Dla stron — `MyPage.path` (trasa)


<div id="handlers-with-and-without-data"></div>

### Obsługa z danymi i bez danych

Handlery mogą być synchroniczne lub asynchroniczne i mogą być zdefiniowane z argumentem `data` lub bez:

``` dart
@override
Map<String, Function> get stateActions => {
  // Bez danych — handler uruchamia się bezpośrednio
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Z danymi — otrzymuje cokolwiek zostało przekazane przez argument `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async jest obsługiwane — framework czeka na handler
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Jeśli przekażesz `data:` do `stateAction`, ale Twój handler nie przyjmuje argumentów, dane są po prostu ignorowane.


<div id="using-a-state-actions-instance"></div>

### Używanie instancji StateActions

Jeśli widget udostępnia typowaną instancję `StateActions` (często przez statyczną metodę `stateActions(stateName)`), możesz wywołać `.action(...)` bezpośrednio na niej zamiast używać wolnej funkcji. Jest to wygodniejsze, gdy wywołujesz kilka akcji na tym samym celu:

``` dart
// Używanie wolnej funkcji
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Używanie instancji StateActions — równoważne, mniej powtórzeń
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Kilka wbudowanych widgetów (`InputField`, `CollectionView`, `LanguageSwitcher`, rodzina `NyForm*`) dostarcza typowane klasy `StateActions` z nazwanymi metodami takimi jak `.clear()`, `.setValue(...)`, `.refresh()` — sprawdź dokumentację widgetu, co jest dostępne.


<div id="pattern-ny-page"></div>

## Wzorzec: Strony z zarządzaniem stanem (NyPage)

Używaj tego wzorca, gdy chcesz wywołać zachowanie na całej stronie z innego miejsca w aplikacji — na przykład odświeżyć stronę po zdarzeniu lub wyczyścić stan formularza z widgetu potomnego.

**Krok 1:** Utwórz stronę przez scaffold.

``` bash
metro make:page my_page
```

Generuje to `NyPage` w `lib/resources/pages/`:

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

**Krok 2:** Zmień `stateManaged` na `true`.

Domyślnie strony **nie** subskrybują zdarzeń stanu — pozwala to uniknąć zbędnych listenerów na stronach, które ich nie potrzebują. Aby włączyć akcje stanu na stronie, zmień getter:

``` dart
@override
bool get stateManaged => true;
```

**Krok 3:** Dodaj mapę `stateActions`.

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

**Krok 4:** Wywołaj akcję z dowolnego miejsca używając `path` strony.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Strony są identyfikowane przez `MyPage.path`, widgety przez `MyWidget.state`. To jedyna różnica po stronie wywołania.


<div id="pattern-ny-state"></div>

## Wzorzec: Stateful Widgets (NyState)

Używaj tego wzorca dla wielokrotnie używalnych widgetów, które istnieją jako pojedyncza instancja na stronie — karta profilu, pasek nagłówka, wskaźnik statusu. Jeśli renderujesz tylko jedną instancję widgetu naraz, to jest właściwy wzorzec.

**Krok 1:** Utwórz stateful widget przez scaffold.

``` bash
metro make:stateful_widget profile_card
```

Generuje to następujący kod w `lib/resources/widgets/`:

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

Scaffold daje działający widget `NyState` — ale **nie jest jeszcze zarządzany stanem**. Aby reagował na akcje stanu, musisz dodać cztery rzeczy:

1. Klucz `state` na klasie widgetu — unikalny ciąg, który inne części aplikacji będą wskazywać
2. Parametr konstruktora przyjmujący klucz stanu i przekazujący go do klasy stanu
3. Konstruktor na klasie stanu przypisujący `stateName`
4. Mapę `stateActions` definiującą handlery

**Krok 2:** Przekształć scaffold w widget z zarządzaniem stanem.

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
    // ^ wywołaj to z dowolnego miejsca w aplikacji, aby uruchomić handler poniżej
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

Co się zmieniło:
- `static String get state => "profile_card";` definiuje publiczny klucz stanu
- `createState() => _ProfileCardState(state)` przekazuje klucz do klasy stanu
- `_ProfileCardState(String? state) { this.stateName = state; }` rejestruje tę instancję stanu pod tym kluczem, dzięki czemu framework wie, któremu widgetowi dostarczyć akcję
- `stateActions` deklaruje nazwane handlery

**Krok 3:** Wywołaj z dowolnego miejsca.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Możesz dodać tyle handlerów ile potrzebujesz, z danymi lub bez:

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

## Wzorzec: Widgety z zarządzaniem stanem (NyStateManaged)

Używaj tego wzorca, gdy potrzebujesz **wielu niezależnych instancji tego samego widgetu** jednocześnie na ekranie — na przykład odznaka koszyka w nagłówku i inna na pasku bocznym, które powinny aktualizować się niezależnie.

`NyStateManaged` dodaje parametr `id`, dzięki czemu każda instancja może być adresowana osobno. Jeśli zawsze renderujesz tylko jedną instancję widgetu, preferuj `NyState` — jest prostszy.

**Krok 1:** Utwórz widget z zarządzaniem stanem przez scaffold.

``` bash
metro make:state_managed_widget cart
```

Generuje to następujący kod w `lib/resources/widgets/`:

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
    // logika inicjalizacji tutaj
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Wywołaj akcje z dowolnego miejsca w aplikacji
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

**Krok 2:** Wypełnij stan — załaduj dane w `init`, zdefiniuj handlery i zaimplementuj widok.

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

**Krok 3:** Wywołuj akcje używając wygenerowanego statycznego helpera `action()`.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Jest to równoważne `stateAction("reload_cart", state: Cart.state)` — statyczny helper tylko usuwa boilerplate.


<div id="advanced-multiple-isolated-instances"></div>

### Zaawansowane: Wiele izolowanych instancji

Powód istnienia `NyStateManaged` to obsługa wielu niezależnych instancji tego samego widgetu. Każda instancja otrzymuje własny `id`, co tworzy klucz stanu z przestrzenią nazw.

Wyrenderuj dwa koszyki z odrębnymi identyfikatorami:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Teraz możesz aktualizować każdy z nich niezależnie:

``` dart
// Odśwież tylko koszyk w nagłówku
Cart.action("reload_cart", id: "header");

// Odśwież tylko koszyk na pasku bocznym
Cart.action("reload_cart", id: "sidebar");

// Bez id — celuje w domyślną nienazwaną instancję
Cart.action("reload_cart");
```

`NyStateManaged` obsługuje przestrzeń nazw: `Cart(id: "header")` rejestruje się pod kluczem `"cart_header"`, a `Cart.action(..., id: "header")` celuje w dokładnie ten klucz.


<div id="lifecycle"></div>

## Informacje o cyklu życia

Widgety i strony z zarządzaniem stanem mają dwa kluczowe hooki cyklu życia:

1. **`init()`** — wywoływany raz przy pierwszym tworzeniu stanu. Używaj do ładowania danych początkowych.

2. **`stateUpdated(data)`** — wywoływany za każdym razem, gdy akcja stanu jest wywołana na tym stanie. Argument `data` to pełny ładunek (zawierający nazwę akcji i dane akcji). Nadpisz go, jeśli potrzebujesz reagować na *każdą* akcję stanu — w większości przypadków definiowanie handlerów w `stateActions` jest tym, czego chcesz.

**Zobacz także:** [NyState](/docs/{{ $version }}/ny-state) dla pełnego zestawu helperów stanu i metod cyklu życia.
