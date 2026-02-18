# State Management

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Kiedy uzywac zarzadzania stanem](#when-to-use-state-management "Kiedy uzywac zarzadzania stanem")
- [Cykl zycia](#lifecycle "Cykl zycia")
- [Akcje stanu](#state-actions "Akcje stanu")
  - [NyState - Akcje stanu](#state-actions-nystate "NyState - Akcje stanu")
  - [NyPage - Akcje stanu](#state-actions-nypage "NyPage - Akcje stanu")
- [Aktualizacja stanu](#updating-a-state "Aktualizacja stanu")
- [Budowanie pierwszego widgetu](#building-your-first-widget "Budowanie pierwszego widgetu")

<div id="introduction"></div>

## Wprowadzenie

Zarzadzanie stanem pozwala aktualizowac okreslone czesci interfejsu uzytkownika bez przebudowywania calych stron. W {{ config('app.name') }} v7 mozesz budowac widgety, ktore komunikuja sie i aktualizuja nawzajem w calej aplikacji.

{{ config('app.name') }} udostepnia dwie klasy do zarzadzania stanem:
- **`NyState`** -- Do budowania widgetow wielokrotnego uzytku (takich jak znacznik koszyka, licznik powiadomien czy wskaznik statusu)
- **`NyPage`** -- Do budowania stron w aplikacji (rozszerza `NyState` o funkcje specyficzne dla stron)

Uzywaj zarzadzania stanem, gdy potrzebujesz:
- Aktualizowac widget z innej czesci aplikacji
- Utrzymywac widgety w synchronizacji ze wspoldzielonymi danymi
- Unikac przebudowywania calych stron, gdy zmienia sie tylko czesc interfejsu


### Najpierw zrozummy zarzadzanie stanem

Wszystko we Flutter jest widgetem -- to male kawalki interfejsu, ktore mozna laczyc, aby stworzyc pelna aplikacje.

Gdy zaczniesz budowac zlozone strony, bedziesz musial zarzadzac stanem swoich widgetow. Oznacza to, ze gdy cos sie zmieni, np. dane, mozesz zaktualizowac ten widget bez koniecznosci przebudowywania calej strony.

Jest wiele powodow, dla ktorych jest to wazne, ale glownym powodem jest wydajnosc. Jesli masz widget, ktory stale sie zmienia, nie chcesz przebudowywac calej strony za kazdym razem, gdy sie zmieni.

Tutaj wlasnie wchodzi zarzadzanie stanem -- pozwala ono zarzadzac stanem widgetu w aplikacji.


<div id="when-to-use-state-management"></div>

### Kiedy uzywac zarzadzania stanem

Powinienes uzywac zarzadzania stanem, gdy masz widget, ktory musi byc aktualizowany bez przebudowywania calej strony.

Na przyklad, wyobraz sobie, ze stworzylas aplikacje e-commerce. Zbudowales widget wyswietlajacy calkowita liczbe przedmiotow w koszyku uzytkownika.
Nazwijmy ten widget `Cart()`.

Widget `Cart` zarzadzany stanem w Nylo wygladalby mniej wiecej tak:

**Krok 1:** Zdefiniuj widget ze statyczna nazwa stanu

``` dart
/// Widget Cart
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unikalny identyfikator stanu tego widgetu

  @override
  _CartState createState() => _CartState();
}
```

**Krok 2:** Utworz klase stanu rozszerzajaca `NyState`

``` dart
/// Klasa stanu dla widgetu Cart
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Zarejestruj nazwe stanu
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Zaladuj poczatkowe dane
  };

  @override
  void stateUpdated(data) {
    reboot(); // Przeladuj widget po aktualizacji stanu
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

**Krok 3:** Utworz funkcje pomocnicze do odczytu i aktualizacji koszyka

``` dart
/// Pobierz wartosc koszyka z pamieci
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Ustaw wartosc koszyka i powiadom widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // To uruchamia stateUpdated() na widgecie
}
```

Omowmy to.

1. Widget `Cart` jest `StatefulWidget`.

2. `_CartState` rozszerza `NyState<Cart>`.

3. Musisz zdefiniowac nazwe dla `state`, jest ona uzywana do identyfikacji stanu.

4. Metoda `boot()` jest wywolywana, gdy widget jest ladowany po raz pierwszy.

5. Metody `stateUpdate()` obsluguja to, co dzieje sie po aktualizacji stanu.

Jesli chcesz wyprobowac ten przyklad w swoim projekcie {{ config('app.name') }}, utworz nowy widget o nazwie `Cart`.

``` bash
metro make:state_managed_widget cart
```

Nastepnie mozesz skopiowac powyzszy przyklad i wyprobowac go w swoim projekcie.

Teraz, aby zaktualizowac koszyk, mozesz wywolac nastepujace polecenie.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Cykl zycia

Cykl zycia widgetu `NyState` jest nastepujacy:

1. `init()` - Ta metoda jest wywolywana, gdy stan jest inicjalizowany.

2. `stateUpdated(data)` - Ta metoda jest wywolywana, gdy stan jest aktualizowany.

    Jesli wywolasz `updateState(MyStateName.state, data: "The Data")`, uruchomi to **stateUpdated(data)**.

Po pierwszej inicjalizacji stanu musisz zaimplementowac sposob zarzadzania stanem.


<div id="state-actions"></div>

## Akcje stanu

Akcje stanu pozwalaja uruchamiac okreslone metody na widgecie z dowolnego miejsca w aplikacji. Pomysl o nich jak o nazwanych poleceniach, ktore mozesz wyslac do widgetu.

Uzywaj akcji stanu, gdy potrzebujesz:
- Uruchomic okreslone zachowanie na widgecie (nie tylko go odswiczyc)
- Przekazac dane do widgetu i sprawic, by reagowal w okreslony sposob
- Tworzyc zachowania widgetow wielokrotnego uzytku, ktore moga byc wywolywane z wielu miejsc

``` dart
// Wysylanie akcji do widgetu
stateAction('hello_world_in_widget', state: MyWidget.state);

// Kolejny przyklad z danymi
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

W widgecie mozesz zdefiniowac akcje, ktore chcesz obslugiwac.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Przyklad z danymi
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Nastepnie mozesz wywolac metode `stateAction` z dowolnego miejsca w aplikacji.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// wypisuje 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Mozesz rowniez definiowac akcje stanu za pomoca metody `whenStateAction` w getterze `init`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Resetuj licznik znacznika
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - Akcje stanu

Najpierw utworz widget stanowy.

``` bash
metro make:stateful_widget [widget_name]
```
Przyklad: metro make:stateful_widget user_avatar

To utworzy nowy widget w katalogu `lib/resources/widgets/`.

Jesli otworzysz ten plik, bedziesz mogl zdefiniowac swoje akcje stanu.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Przyklad
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Przyklad
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Na koniec mozesz wyslac akcje z dowolnego miejsca w aplikacji.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// wypisuje 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Resetuje dane w widgecie

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// wyswietla toast sukcesu z wiadomoscia
```


<div id="state-actions-nypage"></div>

### NyPage - Akcje stanu

Strony rowniez moga otrzymywac akcje stanu. Jest to przydatne, gdy chcesz uruchamiac zachowania na poziomie strony z widgetow lub innych stron.

Najpierw utworz strone zarzadzana stanem.

``` bash
metro make:page my_page
```

To utworzy nowa strone zarzadzana stanem o nazwie `MyPage` w katalogu `lib/resources/pages/`.

Jesli otworzysz ten plik, bedziesz mogl zdefiniowac swoje akcje stanu.

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
    // Przyklad
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Na koniec mozesz wyslac akcje z dowolnego miejsca w aplikacji.

``` dart
stateAction('test_page_action', state: MyPage.state);
// wypisuje 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Resetuje dane na stronie

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// wyswietla toast sukcesu z wiadomoscia
```

Mozesz rowniez definiowac akcje stanu za pomoca metody `whenStateAction`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Resetuj licznik znacznika
      _count = 0;
    }
  });
}
```

Nastepnie mozesz wyslac akcje z dowolnego miejsca w aplikacji.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Aktualizacja stanu

Mozesz zaktualizowac stan, wywolujac metode `updateState()`.

``` dart
updateState(MyStateName.state);

// lub z danymi
updateState(MyStateName.state, data: "The Data");
```

Mozna to wywolac z dowolnego miejsca w aplikacji.

**Zobacz rowniez:** [NyState](/docs/{{ $version }}/ny-state) aby uzyskac wiecej szczegolow na temat pomocnikow zarzadzania stanem i metod cyklu zycia.


<div id="building-your-first-widget"></div>

## Budowanie pierwszego widgetu

W swoim projekcie Nylo uruchom nastepujace polecenie, aby utworzyc nowy widget.

``` bash
metro make:stateful_widget todo_list
```

To utworzy nowy widget `NyState` o nazwie `TodoList`.

> Uwaga: Nowy widget zostanie utworzony w katalogu `lib/resources/widgets/`.