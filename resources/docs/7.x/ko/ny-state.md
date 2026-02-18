# NyState

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [NyState 사용 방법](#how-to-use-nystate "NyState 사용 방법")
- [로딩 스타일](#loading-style "로딩 스타일")
- [State 액션](#state-actions "State 액션")
- [헬퍼](#helpers "헬퍼")


<div id="introduction"></div>

## 소개

`NyState`는 표준 Flutter `State` 클래스의 확장 버전입니다. 페이지와 위젯의 상태를 보다 효율적으로 관리할 수 있는 추가 기능을 제공합니다.

일반 Flutter state와 정확히 동일한 방식으로 state와 **상호작용**할 수 있으며, NyState의 추가 이점을 활용할 수 있습니다.

NyState 사용 방법을 살펴보겠습니다.

<div id="how-to-use-nystate"></div>

## NyState 사용 방법

이 클래스를 확장하여 사용할 수 있습니다.

예시

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

`init` 메서드는 페이지의 상태를 초기화하는 데 사용됩니다. 이 메서드를 async 또는 async 없이 사용할 수 있으며, 내부적으로 비동기 호출을 처리하고 로더를 표시합니다.

`view` 메서드는 페이지의 UI를 표시하는 데 사용됩니다.

#### NyState로 새 Stateful 위젯 생성하기

{{ config('app.name') }}에서 새 페이지를 생성하려면 아래 명령어를 실행하면 됩니다.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## 로딩 스타일

`loadingStyle` 속성을 사용하여 페이지의 로딩 스타일을 설정할 수 있습니다.

예시

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**기본** `loadingStyle`은 로딩 Widget (resources/widgets/loader_widget.dart)입니다.
`loadingStyle`을 커스터마이징하여 로딩 스타일을 변경할 수 있습니다.

사용 가능한 로딩 스타일 표입니다:

| 스타일 | 설명 |
| --- | --- |
| normal | 기본 로딩 스타일 |
| skeletonizer | 스켈레톤 로딩 스타일 |
| none | 로딩 스타일 없음 |

로딩 스타일은 다음과 같이 변경할 수 있습니다:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

스타일 중 하나의 로딩 Widget을 변경하려면 `LoadingStyle`에 `child`를 전달할 수 있습니다.

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

이제 탭이 로딩 중일 때 "Loading..." 텍스트가 표시됩니다.

아래 예시:

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

## State 액션

Nylo에서는 위젯에 작은 **액션**을 정의하여 다른 클래스에서 호출할 수 있습니다. 이는 다른 클래스에서 위젯의 상태를 업데이트하려는 경우에 유용합니다.

먼저 위젯에서 액션을 **정의**해야 합니다. 이는 `NyState`와 `NyPage` 모두에서 작동합니다.

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

그런 다음 `stateAction` 메서드를 사용하여 다른 클래스에서 액션을 호출할 수 있습니다.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

`NyPage`에서 stateActions를 사용하는 경우 페이지의 **path**를 사용해야 합니다.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

`StateAction`이라는 또 다른 클래스가 있으며, 위젯의 상태를 업데이트하는 데 사용할 수 있는 여러 메서드가 있습니다.

- `refreshPage` - 페이지를 새로고침합니다.
- `pop` - 페이지를 팝합니다.
- `showToastSorry` - 사과 토스트 알림을 표시합니다.
- `showToastWarning` - 경고 토스트 알림을 표시합니다.
- `showToastInfo` - 정보 토스트 알림을 표시합니다.
- `showToastDanger` - 위험 토스트 알림을 표시합니다.
- `showToastOops` - 실수 토스트 알림을 표시합니다.
- `showToastSuccess` - 성공 토스트 알림을 표시합니다.
- `showToastCustom` - 커스텀 토스트 알림을 표시합니다.
- `validate` - 위젯에서 데이터를 유효성 검사합니다.
- `changeLanguage` - 애플리케이션의 언어를 변경합니다.
- `confirmAction` - 확인 액션을 수행합니다.

예시

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

`StateAction` 클래스를 사용하여 애플리케이션의 모든 페이지/위젯의 상태를 업데이트할 수 있습니다. 단, 해당 위젯이 상태 관리되고 있어야 합니다.

<div id="helpers"></div>

## 헬퍼

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

이 메서드는 state에서 `init` 메서드를 다시 실행합니다. 페이지의 데이터를 새로고침하려는 경우에 유용합니다.

예시
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

`pop` - 스택에서 현재 페이지를 제거합니다.

예시

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

컨텍스트에 토스트 알림을 표시합니다.

예시

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

`validate` 헬퍼는 데이터에 대한 유효성 검사를 수행합니다.

유효성 검사기에 대해 더 알아보려면 <a href="/docs/{{$version}}/validation" target="_BLANK">여기</a>를 참조하세요.

예시

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

`changeLanguage`를 호출하여 디바이스에서 사용하는 json **/lang** 파일을 변경할 수 있습니다.

현지화에 대해 더 알아보려면 <a href="/docs/{{$version}}/localization" target="_BLANK">여기</a>를 참조하세요.

예시

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

애플리케이션이 특정 상태에 있을 때 함수를 실행하기 위해 `whenEnv`를 사용할 수 있습니다.
예를 들어, `.env` 파일 내의 **APP_ENV** 변수가 'developing'으로 설정된 경우, `APP_ENV=developing`.

예시

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

이 메서드는 함수가 호출된 후 상태를 잠그며, 메서드가 완료될 때까지 사용자가 추가 요청을 할 수 없습니다. 이 메서드는 또한 상태를 업데이트하며, `isLocked`를 사용하여 확인할 수 있습니다.

`lockRelease`를 보여주는 가장 좋은 예시는 로그인 화면에서 사용자가 'Login'을 탭하는 상황을 상상하는 것입니다. 사용자를 로그인하기 위해 비동기 호출을 수행하려 하지만, 원하지 않는 경험을 만들 수 있으므로 메서드가 여러 번 호출되는 것을 원하지 않습니다.

아래 예시입니다.

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

**_login** 메서드를 탭하면 원래 요청이 완료될 때까지 추가 요청을 차단합니다. `isLocked('login_to_app')` 헬퍼는 버튼이 잠겨 있는지 확인하는 데 사용됩니다. 위의 예시에서는 로딩 Widget을 언제 표시할지 결정하는 데 이를 사용하는 것을 볼 수 있습니다.

<div id="is-locked"></div>

### isLocked

이 메서드는 [`lockRelease`](#lock-release) 헬퍼를 사용하여 상태가 잠겨 있는지 확인합니다.

예시
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

`view` 메서드는 페이지의 UI를 표시하는 데 사용됩니다.

예시
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

`confirmAction` 메서드는 사용자에게 액션을 확인하는 대화상자를 표시합니다.
이 메서드는 진행하기 전에 사용자가 액션을 확인하도록 하려는 경우에 유용합니다.

예시

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

`showToastSuccess` 메서드는 사용자에게 성공 토스트 알림을 표시합니다.

예시
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

`showToastOops` 메서드는 사용자에게 실수 토스트 알림을 표시합니다.

예시
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

`showToastDanger` 메서드는 사용자에게 위험 토스트 알림을 표시합니다.

예시
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

`showToastInfo` 메서드는 사용자에게 정보 토스트 알림을 표시합니다.

예시
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

`showToastWarning` 메서드는 사용자에게 경고 토스트 알림을 표시합니다.

예시
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

`showToastSorry` 메서드는 사용자에게 사과 토스트 알림을 표시합니다.

예시
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

`isLoading` 메서드는 상태가 로딩 중인지 확인합니다.

예시
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

`afterLoad` 메서드는 상태가 '로딩'을 완료할 때까지 로더를 표시하는 데 사용할 수 있습니다.

**loadingKey** 매개변수를 사용하여 다른 로딩 키도 확인할 수 있습니다 `afterLoad(child: () {}, loadingKey: 'home_data')`.

예시
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

`afterNotLocked` 메서드는 상태가 잠겨 있는지 확인합니다.

상태가 잠겨 있으면 [loading] 위젯을 표시합니다.

예시
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

`afterNotNull`을 사용하여 변수가 설정될 때까지 로딩 위젯을 표시할 수 있습니다.

Future 호출을 사용하여 DB에서 사용자 계정을 가져와야 하는데 1-2초가 걸릴 수 있는 상황을 상상해 보세요. 데이터를 받을 때까지 해당 값에 afterNotNull을 사용할 수 있습니다.

예시

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

`setLoading`을 사용하여 '로딩' 상태로 변경할 수 있습니다.

첫 번째 매개변수는 로딩 중인지 여부의 `bool`을 받고, 다음 매개변수는 로딩 상태의 이름을 설정할 수 있습니다. 예: `setLoading(true, name: 'refreshing_content');`.

예시
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
