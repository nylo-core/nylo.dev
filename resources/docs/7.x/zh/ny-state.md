# NyState

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [如何使用 NyState](#how-to-use-nystate "如何使用 NyState")
- [加载样式](#loading-style "加载样式")
- [状态操作](#state-actions "状态操作")
- [辅助方法](#helpers "辅助方法")


<div id="introduction"></div>

## 简介

`NyState` 是标准 Flutter `State` 类的扩展版本。它提供了额外的功能，帮助您更高效地管理页面和组件的状态。

您可以像使用普通 Flutter state 一样**与之交互**，同时享受 NyState 带来的额外好处。

让我们来了解如何使用 NyState。

<div id="how-to-use-nystate"></div>

## 如何使用 NyState

您可以通过继承此类来开始使用。

示例

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

`init` 方法用于初始化页面的状态。您可以使用带有 async 或不带 async 的方式使用此方法，框架会在后台处理异步调用并显示加载器。

`view` 方法用于显示页面的 UI。

#### 使用 NyState 创建新的有状态组件

要在 {{ config('app.name') }} 中创建新的有状态组件，您可以运行以下命令。

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## 加载样式

您可以使用 `loadingStyle` 属性为页面设置加载样式。

示例

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**默认的** `loadingStyle` 将使用您的加载组件（resources/widgets/loader_widget.dart）。
您可以自定义 `loadingStyle` 来更新加载样式。

以下是您可以使用的不同加载样式表：

| 样式 | 描述 |
| --- | --- |
| normal | 默认加载样式 |
| skeletonizer | 骨架屏加载样式 |
| none | 无加载样式 |

您可以这样更改加载样式：

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

如果您想更新某个样式中的加载组件，可以传递一个 `child` 给 `LoadingStyle`。

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

现在，当标签页加载时，将显示文本"Loading..."。

以下是示例：

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

## 状态操作

在 Nylo 中，您可以在组件中定义小型**操作**，这些操作可以从其他类中调用。当您需要从另一个类更新组件的状态时，这非常有用。

首先，您必须在组件中**定义**您的操作。这适用于 `NyState` 和 `NyPage`。

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

然后，您可以使用 `stateAction` 方法从另一个类调用该操作。

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

如果您将 stateActions 与 `NyPage` 一起使用，则必须使用页面的 **path**。

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

还有另一个名为 `StateAction` 的类，它有一些方法可以用来更新组件的状态。

- `refreshPage` - 刷新页面。
- `pop` - 弹出页面。
- `showToastSorry` - 显示抱歉提示通知。
- `showToastWarning` - 显示警告提示通知。
- `showToastInfo` - 显示信息提示通知。
- `showToastDanger` - 显示危险提示通知。
- `showToastOops` - 显示糟糕提示通知。
- `showToastSuccess` - 显示成功提示通知。
- `showToastCustom` - 显示自定义提示通知。
- `validate` - 验证组件中的数据。
- `changeLanguage` - 更新应用中的语言。
- `confirmAction` - 执行确认操作。

示例

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

只要组件是状态管理的，您可以使用 `StateAction` 类来更新应用中任何页面/组件的状态。

<div id="helpers"></div>

## 辅助方法

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

此方法将重新运行状态中的 `init` 方法。当您想刷新页面上的数据时很有用。

示例
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

`pop` - 从堆栈中移除当前页面。

示例

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

在上下文中显示提示通知。

示例

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

`validate` 辅助方法对数据执行验证检查。

您可以在<a href="/docs/{{$version}}/validation" target="_BLANK">此处</a>了解更多关于验证器的信息。

示例

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

您可以调用 `changeLanguage` 来更改设备上使用的 json **/lang** 文件。

在<a href="/docs/{{$version}}/localization" target="_BLANK">此处</a>了解更多关于本地化的信息。

示例

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

您可以使用 `whenEnv` 在应用处于特定状态时运行函数。
例如，您的 `.env` 文件中的 **APP_ENV** 变量设置为 'developing'，`APP_ENV=developing`。

示例

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

此方法将在函数被调用后锁定状态，只有当方法完成后才允许用户发出后续请求。此方法还会更新状态，使用 `isLocked` 来检查。

展示 `lockRelease` 的最佳示例是想象我们有一个登录屏幕，当用户点击"登录"时，我们想执行异步调用来登录用户，但我们不希望该方法被多次调用，因为这可能导致不好的体验。

以下是示例。

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

一旦您点击 **_login** 方法，它将阻止任何后续请求，直到原始请求完成。`isLocked('login_to_app')` 辅助方法用于检查按钮是否被锁定。在上面的示例中，您可以看到我们用它来确定何时显示加载组件。

<div id="is-locked"></div>

### isLocked

此方法将使用 [`lockRelease`](#lock-release) 辅助方法检查状态是否被锁定。

示例
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

`view` 方法用于显示页面的 UI。

示例
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

`confirmAction` 方法将向用户显示一个对话框以确认操作。
当您希望用户在继续之前确认操作时，此方法很有用。

示例

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

`showToastSuccess` 方法将向用户显示成功提示通知。

示例
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

`showToastOops` 方法将向用户显示糟糕提示通知。

示例
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

`showToastDanger` 方法将向用户显示危险提示通知。

示例
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

`showToastInfo` 方法将向用户显示信息提示通知。

示例
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

`showToastWarning` 方法将向用户显示警告提示通知。

示例
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

`showToastSorry` 方法将向用户显示抱歉提示通知。

示例
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

`isLoading` 方法将检查状态是否正在加载。

示例
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

`afterLoad` 方法可用于在状态完成"加载"之前显示加载器。

您还可以使用 **loadingKey** 参数检查其他加载键 `afterLoad(child: () {}, loadingKey: 'home_data')`。

示例
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

`afterNotLocked` 方法将检查状态是否被锁定。

如果状态被锁定，它将显示 [loading] 组件。

示例
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

您可以使用 `afterNotNull` 在变量被设置之前显示加载组件。

假设您需要使用 Future 调用从数据库获取用户账户，这可能需要 1-2 秒，您可以在该值上使用 afterNotNull，直到获取到数据。

示例

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

您可以使用 `setLoading` 切换到"加载中"状态。

第一个参数接受一个 `bool` 表示是否正在加载，下一个参数允许您为加载状态设置名称，例如 `setLoading(true, name: 'refreshing_content');`。

示例
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

