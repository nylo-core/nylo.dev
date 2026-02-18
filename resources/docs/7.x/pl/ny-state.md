# NyState

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Jak używać NyState](#how-to-use-nystate "Jak używać NyState")
- [Styl ładowania](#loading-style "Styl ładowania")
- [Akcje stanu](#state-actions "Akcje stanu")
- [Helpery](#helpers "Helpery")


<div id="introduction"></div>

## Wprowadzenie

`NyState` to rozszerzona wersja standardowej klasy Flutter `State`. Zapewnia dodatkową funkcjonalność pomagającą zarządzać stanem stron i widgetów w bardziej efektywny sposób.

Możesz **wchodzić w interakcję** ze stanem dokładnie tak, jak ze zwykłym stanem Flutter, ale z dodatkowymi zaletami NyState.

Omówmy, jak używać NyState.

<div id="how-to-use-nystate"></div>

## Jak używać NyState

Możesz zacząć używać tej klasy, rozszerzając ją.

Przykład

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {

  };

  @override
  view(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded")
    );
  }
```

Metoda `init` służy do inicjalizacji stanu strony. Możesz użyć tej metody jako async lub bez async, a za kulisami obsłuży ona wywołanie asynchroniczne i wyświetli loader.

Metoda `view` służy do wyświetlania interfejsu użytkownika strony.

#### Tworzenie nowego widgetu stanowego z NyState

Aby utworzyć nową stronę w {{ config('app.name') }}, możesz uruchomić poniższe polecenie.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Styl ładowania

Możesz użyć właściwości `loadingStyle`, aby ustawić styl ładowania dla swojej strony.

Przykład

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**Domyślny** `loadingStyle` to Twój widget ładowania (resources/widgets/loader_widget.dart).
Możesz dostosować `loadingStyle`, aby zmienić styl ładowania.

Oto tabela różnych stylów ładowania, których możesz użyć:

| Styl | Opis |
| --- | --- |
| normal | Domyślny styl ładowania |
| skeletonizer | Styl ładowania szkieletowego |
| none | Brak stylu ładowania |

Możesz zmienić styl ładowania w ten sposób:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// lub
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Jeśli chcesz zaktualizować widget ładowania w jednym ze stylów, możesz przekazać `child` do `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// to samo dla skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Teraz, gdy karta się ładuje, wyświetlany będzie tekst "Loading...".

Przykład poniżej:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // simulate a network call for 3 seconds
    };

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            body: Text("The page loaded")
        );
    }
    ...
}
```

<div id="state-actions"></div>

## Akcje stanu

W Nylo możesz definiować małe **akcje** w swoich widgetach, które mogą być wywoływane z innych klas. Jest to przydatne, gdy chcesz zaktualizować stan widgetu z innej klasy.

Najpierw musisz **zdefiniować** swoje akcje w widgecie. Działa to zarówno dla `NyState`, jak i `NyPage`.

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // handle how you want to initialize the state
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // Example with data
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

Następnie możesz wywołać akcję z innej klasy za pomocą metody `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Jeśli używasz stateActions z `NyPage`, musisz użyć **ścieżki** strony.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Istnieje również klasa `StateAction`, która posiada kilka metod do aktualizacji stanu widgetów.

- `refreshPage` - Odśwież stronę.
- `pop` - Zamknij stronę.
- `showToastSorry` - Wyświetl powiadomienie toast z przeprosinami.
- `showToastWarning` - Wyświetl ostrzegawcze powiadomienie toast.
- `showToastInfo` - Wyświetl informacyjne powiadomienie toast.
- `showToastDanger` - Wyświetl niebezpieczne powiadomienie toast.
- `showToastOops` - Wyświetl powiadomienie toast "oops".
- `showToastSuccess` - Wyświetl powiadomienie toast sukcesu.
- `showToastCustom` - Wyświetl niestandardowe powiadomienie toast.
- `validate` - Waliduj dane z widgetu.
- `changeLanguage` - Zmień język w aplikacji.
- `confirmAction` - Wykonaj akcję potwierdzenia.

Przykład

``` dart
class _UpgradeButtonState extends NyState<UpgradeButton> {

  view(BuildContext context) {
    return Button.primary(
      onPressed: () {
        StateAction.showToastSuccess(UpgradePage.state,
          description: "You have successfully upgraded your account",
        );
      },
      text: "Upgrade",
    );
  }
}
```

Możesz użyć klasy `StateAction` do aktualizacji stanu dowolnej strony/widgetu w aplikacji, o ile widget jest zarządzany stanem.

<div id="helpers"></div>

## Helpery

|  |  |
| --- | ----------- |
| [lockRelease](#lock-release "lockRelease") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") | [confirmAction](#confirm-action "confirmAction") |
| [showToastSuccess](#show-toast-success "showToastSuccess") | [showToastOops](#show-toast-oops "showToastOops") |
| [showToastDanger](#show-toast-danger "showToastDanger") | [showToastInfo](#show-toast-info "showToastInfo") |
| [showToastWarning](#show-toast-warning "showToastWarning") | [showToastSorry](#show-toast-sorry "showToastSorry") |


<div id="reboot"></div>

### Reboot

Ta metoda ponownie uruchomi metodę `init` w Twoim stanie. Jest przydatna, gdy chcesz odświeżyć dane na stronie.

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  get init => () async {
    users = await api<ApiService>((request) => request.fetchUsers());
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Users"),
          actions: [
            IconButton(
              icon: Icon(Icons.refresh),
              onPressed: () {
                reboot(); // refresh the data
              },
            )
          ],
        ),
        body: ListView.builder(
          itemCount: users.length,
          itemBuilder: (context, index) {
            return Text(users[index].firstName);
          }
        ),
    );
  }
}
```

<div id="pop"></div>

### Pop

`pop` - Usuń bieżącą stronę ze stosu.

Przykład

``` dart
class _HomePageState extends NyState<HomePage> {

  popView() {
    pop();
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: popView,
        child: Text("Pop current view")
      )
    );
  }
```


<div id="showToast"></div>

### showToast

Wyświetl powiadomienie toast w kontekście.

Przykład

```dart
class _HomePageState extends NyState<HomePage> {

  displayToast() {
    showToast(
        title: "Hello",
        description: "World",
        icon: Icons.account_circle,
        duration: Duration(seconds: 2),
        style: ToastNotificationStyleType.INFO // SUCCESS, INFO, DANGER, WARNING
    );
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: displayToast,
        child: Text("Display a toast")
      )
    );
  }
```


<div id="validate"></div>

### validate

Helper `validate` wykonuje sprawdzenie walidacyjne na danych.

Więcej o walidatorze dowiesz się <a href="/docs/{{$version}}/validation" target="_BLANK">tutaj</a>.

Przykład

``` dart
class _HomePageState extends NyState<HomePage> {
TextEditingController _textFieldControllerEmail = TextEditingController();

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    validate(rules: {
        "email address": [textEmail, "email"]
      }, onSuccess: () {
      print('passed validation')
    });
  }
```


<div id="change-language"></div>

### changeLanguage

Możesz wywołać `changeLanguage`, aby zmienić plik json **/lang** używany na urządzeniu.

Więcej o lokalizacji dowiesz się <a href="/docs/{{$version}}/localization" target="_BLANK">tutaj</a>.

Przykład

``` dart
class _HomePageState extends NyState<HomePage> {

  changeLanguageES() {
    await changeLanguage('es');
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: changeLanguageES,
        child: Text("Change Language".tr())
      )
    );
  }
```


<div id="when-env"></div>

### whenEnv

Możesz użyć `whenEnv` do uruchomienia funkcji, gdy aplikacja jest w określonym stanie.
Np. zmienna **APP_ENV** w pliku `.env` jest ustawiona na 'developing', `APP_ENV=developing`.

Przykład

```dart
class _HomePageState extends NyState<HomePage> {

  TextEditingController _textEditingController = TextEditingController();

  @override
  get init => () {
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  };
```

<div id="lock-release"></div>

### lockRelease

Ta metoda zablokuje stan po wywołaniu funkcji i dopiero po zakończeniu metody pozwoli użytkownikowi na kolejne żądania. Ta metoda zaktualizuje również stan - użyj `isLocked` do sprawdzenia.

Najlepszym przykładem ilustrującym `lockRelease` jest wyobrażenie sobie ekranu logowania, gdzie użytkownik naciska "Login". Chcemy wykonać asynchroniczne wywołanie logowania, ale nie chcemy, aby metoda była wywoływana wielokrotnie, ponieważ mogłoby to stworzyć niepożądane doświadczenie.

Oto przykład poniżej.

```dart
class _LoginPageState extends NyState<LoginPage> {

  _login() async {
    await lockRelease('login_to_app', perform: () async {

      await Future.delayed(Duration(seconds: 4), () {
        print('Pretend to login...');
      });

    });
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
            Center(
              child: InkWell(
                onTap: _login,
                child: Text("Login"),
              ),
            )
          ],
        )
    );
  }
```

Po naciśnięciu metody **_login** zablokuje ona kolejne żądania aż do zakończenia oryginalnego. Helper `isLocked('login_to_app')` służy do sprawdzenia, czy przycisk jest zablokowany. W powyższym przykładzie używamy tego do określenia, kiedy wyświetlić widget ładowania.

<div id="is-locked"></div>

### isLocked

Ta metoda sprawdzi, czy stan jest zablokowany za pomocą helpera [`lockRelease`](#lock-release).

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
          ],
        )
    );
  }
```

<div id="view"></div>

### view

Metoda `view` służy do wyświetlania interfejsu użytkownika strony.

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
      return Scaffold(
          body: Center(
              child: Text("My Page")
          )
      );
  }
}
```

<div id="confirm-action"></div>

### confirmAction

Metoda `confirmAction` wyświetli dialog dla użytkownika w celu potwierdzenia akcji.
Ta metoda jest przydatna, gdy chcesz, aby użytkownik potwierdził akcję przed kontynuowaniem.

Przykład

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

Metoda `showToastSuccess` wyświetli powiadomienie toast sukcesu dla użytkownika.

Przykład
``` dart
_login() {
    ...
    showToastSuccess(
        description: "You have successfully logged in"
    );
}
```

<div id="show-toast-oops"></div>

### showToastOops

Metoda `showToastOops` wyświetli powiadomienie toast "oops" dla użytkownika.

Przykład
``` dart
_error() {
    ...
    showToastOops(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-danger"></div>

### showToastDanger

Metoda `showToastDanger` wyświetli niebezpieczne powiadomienie toast dla użytkownika.

Przykład
``` dart
_error() {
    ...
    showToastDanger(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-info"></div>

### showToastInfo

Metoda `showToastInfo` wyświetli informacyjne powiadomienie toast dla użytkownika.

Przykład
``` dart
_info() {
    ...
    showToastInfo(
        description: "Your account has been updated"
    );
}
```

<div id="show-toast-warning"></div>

### showToastWarning

Metoda `showToastWarning` wyświetli ostrzegawcze powiadomienie toast dla użytkownika.

Przykład
``` dart
_warning() {
    ...
    showToastWarning(
        description: "Your account is about to expire"
    );
}
```

<div id="show-toast-sorry"></div>

### showToastSorry

Metoda `showToastSorry` wyświetli powiadomienie toast z przeprosinami dla użytkownika.

Przykład
``` dart
_sorry() {
    ...
    showToastSorry(
        description: "Your account has been suspended"
    );
}
```

<div id="is-loading"></div>

### isLoading

Metoda `isLoading` sprawdzi, czy stan jest w trakcie ładowania.

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    if (isLoading()) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: colors().primaryContent
        )
      )
    );
  }
```

<div id="after-load"></div>

### afterLoad

Metoda `afterLoad` może być używana do wyświetlania loadera do momentu zakończenia 'ładowania' stanu.

Możesz również sprawdzić inne klucze ładowania za pomocą parametru **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () {
    awaitData(perform: () async {
        await sleep(4);
        print('4 seconds after...');
    });
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterLoad(child: () {
          return Text("Loaded");
        })
    );
  }
```

<div id="after-not-locked"></div>

### afterNotLocked

Metoda `afterNotLocked` sprawdzi, czy stan jest zablokowany.

Jeśli stan jest zablokowany, wyświetli widget [loading].

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          alignment: Alignment.center,
          child: afterNotLocked('login', child: () {
            return MaterialButton(
              onPressed: () {
                login();
              },
              child: Text("Login"),
            );
          }),
        )
    );
  }

  login() async {
    await lockRelease('login', perform: () async {
      await sleep(4);
      print('4 seconds after...');
    });
  }
}
```

<div id="after-not-null"></div>

### afterNotNull

Możesz użyć `afterNotNull`, aby wyświetlić widget ładowania do momentu ustawienia zmiennej.

Wyobraź sobie, że musisz pobrać konto użytkownika z bazy danych za pomocą wywołania Future, które może zająć 1-2 sekundy - możesz użyć afterNotNull na tej wartości, aż uzyskasz dane.

Przykład

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // example
    setState(() {});
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterNotNull(_user, child: () {
          return Text(_user!.firstName);
        })
    );
  }
```

<div id="set-loading"></div>

### setLoading

Możesz zmienić na stan 'ładowania' za pomocą `setLoading`.

Pierwszy parametr przyjmuje `bool` określający, czy trwa ładowanie, następny parametr pozwala ustawić nazwę stanu ładowania, np. `setLoading(true, name: 'refreshing_content');`.

Przykład
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    setLoading(true, name: 'refreshing_content');

    await sleep(4);

    setLoading(false, name: 'refreshing_content');
  };

  @override
  Widget build(BuildContext context) {
    if (isLoading(name: 'refreshing_content')) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded")
    );
  }
```
