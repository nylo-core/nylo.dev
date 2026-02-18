# NyState

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Как использовать NyState](#how-to-use-nystate "Как использовать NyState")
- [Стиль загрузки](#loading-style "Стиль загрузки")
- [Действия состояния](#state-actions "Действия состояния")
- [Вспомогательные методы](#helpers "Вспомогательные методы")


<div id="introduction"></div>

## Введение

`NyState` -- это расширенная версия стандартного класса Flutter `State`. Он предоставляет дополнительную функциональность для более эффективного управления состоянием ваших страниц и виджетов.

Вы можете **взаимодействовать** с состоянием точно так же, как с обычным состоянием Flutter, но с дополнительными преимуществами NyState.

Давайте рассмотрим, как использовать NyState.

<div id="how-to-use-nystate"></div>

## Как использовать NyState

Вы можете начать использовать этот класс, наследуясь от него.

Пример

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

Метод `init` используется для инициализации состояния страницы. Вы можете использовать этот метод как асинхронный или без async -- за кулисами он обработает асинхронный вызов и отобразит загрузчик.

Метод `view` используется для отображения пользовательского интерфейса страницы.

#### Создание нового виджета с состоянием на основе NyState

Чтобы создать новую страницу в {{ config('app.name') }}, вы можете выполнить следующую команду.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Стиль загрузки

Вы можете использовать свойство `loadingStyle` для установки стиля загрузки вашей страницы.

Пример

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**По умолчанию** `loadingStyle` будет вашим виджетом загрузки (resources/widgets/loader_widget.dart).
Вы можете настроить `loadingStyle` для изменения стиля загрузки.

Вот таблица с различными стилями загрузки, которые вы можете использовать:
// normal, skeletonizer, none

| Стиль | Описание |
| --- | --- |
| normal | Стиль загрузки по умолчанию |
| skeletonizer | Скелетонный стиль загрузки |
| none | Без стиля загрузки |

Вы можете изменить стиль загрузки следующим образом:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Если вы хотите обновить виджет загрузки в одном из стилей, вы можете передать `child` в `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// same for skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Теперь при загрузке вкладки будет отображаться текст "Loading...".

Пример ниже:

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

## Действия состояния

В Nylo вы можете определить небольшие **действия** в ваших виджетах, которые можно вызывать из других классов. Это полезно, если вы хотите обновить состояние виджета из другого класса.

Сначала необходимо **определить** ваши действия в виджете. Это работает для `NyState` и `NyPage`.

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

Затем вы можете вызвать действие из другого класса с помощью метода `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Если вы используете stateActions с `NyPage`, вы должны использовать **путь** страницы.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Также существует класс `StateAction`, который содержит несколько методов для обновления состояния ваших виджетов.

- `refreshPage` -- Обновить страницу.
- `pop` -- Закрыть страницу.
- `showToastSorry` -- Показать уведомление «Извините».
- `showToastWarning` -- Показать предупреждающее уведомление.
- `showToastInfo` -- Показать информационное уведомление.
- `showToastDanger` -- Показать уведомление об опасности.
- `showToastOops` -- Показать уведомление «Ой».
- `showToastSuccess` -- Показать уведомление об успехе.
- `showToastCustom` -- Показать пользовательское уведомление.
- `validate` -- Валидировать данные из вашего виджета.
- `changeLanguage` -- Обновить язык в приложении.
- `confirmAction` -- Выполнить действие с подтверждением.

Пример

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

Вы можете использовать класс `StateAction` для обновления состояния любой страницы/виджета в вашем приложении, при условии что виджет управляется состоянием.

<div id="helpers"></div>

## Вспомогательные методы

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

Этот метод повторно запустит метод `init` в вашем состоянии. Полезен, если вы хотите обновить данные на странице.

Пример
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

`pop` -- удаляет текущую страницу из стека.

Пример

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

Показать всплывающее уведомление (toast) в текущем контексте.

Пример

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

Хелпер `validate` выполняет проверку валидации данных.

Подробнее о валидаторе вы можете узнать <a href="/docs/{{$version}}/validation" target="_BLANK">здесь</a>.

Пример

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

Вы можете вызвать `changeLanguage` для смены JSON-файла **/lang**, используемого на устройстве.

Подробнее о локализации вы можете узнать <a href="/docs/{{$version}}/localization" target="_BLANK">здесь</a>.

Пример

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

Вы можете использовать `whenEnv` для запуска функции, когда ваше приложение находится в определённом состоянии.
Например, ваша переменная **APP_ENV** в файле `.env` установлена в 'developing', `APP_ENV=developing`.

Пример

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

Этот метод заблокирует состояние после вызова функции и разрешит повторные запросы только после завершения метода. Также обновляет состояние -- используйте `isLocked` для проверки.

Лучший пример для демонстрации `lockRelease` -- представьте экран входа, когда пользователь нажимает «Войти». Мы хотим выполнить асинхронный вызов для авторизации пользователя, но не хотим, чтобы метод вызывался несколько раз, так как это может привести к нежелательному поведению.

Вот пример ниже.

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

После нажатия метода **_login** он заблокирует любые последующие запросы до завершения оригинального. Хелпер `isLocked('login_to_app')` используется для проверки, заблокирована ли кнопка. В примере выше мы используем это для определения, когда показывать виджет загрузки.

<div id="is-locked"></div>

### isLocked

Этот метод проверяет, заблокировано ли состояние с помощью хелпера [`lockRelease`](#lock-release).

Пример
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

Метод `view` используется для отображения пользовательского интерфейса страницы.

Пример
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

Метод `confirmAction` отобразит диалоговое окно для подтверждения действия пользователем.
Этот метод полезен, когда вы хотите, чтобы пользователь подтвердил действие перед его выполнением.

Пример

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

Метод `showToastSuccess` отобразит уведомление об успехе для пользователя.

Пример
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

Метод `showToastOops` отобразит уведомление «Ой» для пользователя.

Пример
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

Метод `showToastDanger` отобразит уведомление об опасности для пользователя.

Пример
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

Метод `showToastInfo` отобразит информационное уведомление для пользователя.

Пример
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

Метод `showToastWarning` отобразит предупреждающее уведомление для пользователя.

Пример
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

Метод `showToastSorry` отобразит уведомление «Извините» для пользователя.

Пример
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

Метод `isLoading` проверяет, находится ли состояние в процессе загрузки.

Пример
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

Метод `afterLoad` может использоваться для отображения загрузчика до завершения загрузки состояния.

Вы также можете проверять другие ключи загрузки с помощью параметра **loadingKey**: `afterLoad(child: () {}, loadingKey: 'home_data')`.

Пример
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

Метод `afterNotLocked` проверяет, заблокировано ли состояние.

Если состояние заблокировано, будет отображён виджет [loading].

Пример
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

Вы можете использовать `afterNotNull` для отображения виджета загрузки до тех пор, пока переменная не будет установлена.

Представьте, что вам нужно получить аккаунт пользователя из базы данных с помощью Future-вызова, который может занять 1-2 секунды -- вы можете использовать afterNotNull для этого значения, пока данные не будут получены.

Пример

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

Вы можете перейти в состояние загрузки с помощью `setLoading`.

Первый параметр принимает `bool` для указания, идёт ли загрузка, следующий параметр позволяет задать имя для состояния загрузки, например: `setLoading(true, name: 'refreshing_content');`.

Пример
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
