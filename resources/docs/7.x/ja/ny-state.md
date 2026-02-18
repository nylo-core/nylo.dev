# NyState

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [NyState の使い方](#how-to-use-nystate "NyState の使い方")
- [ローディングスタイル](#loading-style "ローディングスタイル")
- [State Actions](#state-actions "State Actions")
- [ヘルパー](#helpers "ヘルパー")


<div id="introduction"></div>

## はじめに

`NyState` は標準的な Flutter の `State` クラスを拡張したものです。ページやウィジェットの状態をより効率的に管理するための追加機能を提供します。

通常の Flutter の状態と全く同じように**操作**できますが、NyState の追加機能も利用できます。

NyState の使い方を見ていきましょう。

<div id="how-to-use-nystate"></div>

## NyState の使い方

このクラスを継承して使い始めることができます。

例

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

`init` メソッドはページの状態を初期化するために使用されます。このメソッドは async の有無にかかわらず使用でき、裏側で非同期呼び出しを処理してローダーを表示します。

`view` メソッドはページの UI を表示するために使用されます。

#### NyState を使った新しい StatefulWidget の作成

{{ config('app.name') }} で新しいページを作成するには、以下のコマンドを実行します。

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## ローディングスタイル

`loadingStyle` プロパティを使用してページのローディングスタイルを設定できます。

例

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // 3秒間のネットワーク呼び出しをシミュレート
  };
```

**デフォルト**の `loadingStyle` は、ローディング Widget（resources/widgets/loader_widget.dart）になります。
`loadingStyle` をカスタマイズしてローディングスタイルを更新できます。

利用可能なローディングスタイルの一覧：

| スタイル | 説明 |
| --- | --- |
| normal | デフォルトのローディングスタイル |
| skeletonizer | スケルトンローディングスタイル |
| none | ローディングスタイルなし |

ローディングスタイルは以下のように変更できます：

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// または
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

スタイルのローディング Widget を更新したい場合は、`LoadingStyle` に `child` を渡すことができます。

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// skeletonizer も同様
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

これにより、タブの読み込み中にテキスト「Loading...」が表示されます。

以下の例をご覧ください：

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // 3秒間のネットワーク呼び出しをシミュレート
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

## State Actions

Nylo では、ウィジェット内に小さな**アクション**を定義でき、他のクラスから呼び出すことができます。これは他のクラスからウィジェットの状態を更新したい場合に便利です。

まず、ウィジェット内にアクションを**定義**する必要があります。これは `NyState` と `NyPage` の両方で動作します。

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // 状態の初期化方法を処理
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // データ付きの例
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

次に、`stateAction` メソッドを使用して他のクラスからアクションを呼び出すことができます。

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// データ付きの別の例
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// データ付きの別の例
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

`NyPage` で stateActions を使用する場合は、ページの**パス**を使用する必要があります。

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// データ付きの別の例
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// データ付きの別の例
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

`StateAction` というクラスもあり、ウィジェットの状態を更新するためのいくつかのメソッドがあります。

- `refreshPage` - ページを更新します。
- `pop` - ページをポップします。
- `showToastSorry` - Sorry トースト通知を表示します。
- `showToastWarning` - 警告トースト通知を表示します。
- `showToastInfo` - 情報トースト通知を表示します。
- `showToastDanger` - 危険トースト通知を表示します。
- `showToastOops` - Oops トースト通知を表示します。
- `showToastSuccess` - 成功トースト通知を表示します。
- `showToastCustom` - カスタムトースト通知を表示します。
- `validate` - ウィジェットからのデータをバリデーションします。
- `changeLanguage` - アプリケーションの言語を更新します。
- `confirmAction` - 確認アクションを実行します。

例

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

ウィジェットが状態管理されている限り、`StateAction` クラスを使用してアプリケーション内の任意のページ/ウィジェットの状態を更新できます。

<div id="helpers"></div>

## ヘルパー

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

このメソッドは状態の `init` メソッドを再実行します。ページのデータを更新したい場合に便利です。

例
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
                reboot(); // データを更新
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

`pop` - 現在のページをスタックから削除します。

例

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

コンテキスト上にトースト通知を表示します。

例

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

`validate` ヘルパーはデータのバリデーションチェックを実行します。

バリデーターの詳細は<a href="/docs/{{$version}}/validation" target="_BLANK">こちら</a>をご覧ください。

例

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

`changeLanguage` を呼び出して、デバイスで使用される json **/lang** ファイルを変更できます。

ローカリゼーションの詳細は<a href="/docs/{{$version}}/localization" target="_BLANK">こちら</a>をご覧ください。

例

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

アプリケーションが特定の状態にある時に関数を実行するために `whenEnv` を使用できます。
例えば、`.env` ファイル内の **APP_ENV** 変数が 'developing' に設定されている場合、`APP_ENV=developing`。

例

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

このメソッドは関数が呼び出された後に状態をロックし、メソッドが完了するまで後続のリクエストを許可しません。このメソッドは状態も更新するため、`isLocked` を使用して確認できます。

`lockRelease` の最適な例は、ログイン画面でユーザーが「Login」をタップする場面を想像することです。ユーザーをログインさせるための非同期呼び出しを実行したいですが、望ましくない体験を生む可能性があるため、メソッドが複数回呼び出されることは避けたいです。

以下に例を示します。

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

**_login** メソッドをタップすると、元のリクエストが完了するまで後続のリクエストがブロックされます。`isLocked('login_to_app')` ヘルパーはボタンがロックされているかを確認するために使用されます。上の例では、ローディング Widget を表示するタイミングを判断するために使用しています。

<div id="is-locked"></div>

### isLocked

このメソッドは [`lockRelease`](#lock-release) ヘルパーを使用して状態がロックされているかを確認します。

例
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

`view` メソッドはページの UI を表示するために使用されます。

例
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

`confirmAction` メソッドはユーザーにアクションの確認ダイアログを表示します。
このメソッドは、処理を進める前にユーザーの確認を得たい場合に便利です。

例

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

`showToastSuccess` メソッドはユーザーに成功トースト通知を表示します。

例
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

`showToastOops` メソッドはユーザーに Oops トースト通知を表示します。

例
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

`showToastDanger` メソッドはユーザーに危険トースト通知を表示します。

例
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

`showToastInfo` メソッドはユーザーに情報トースト通知を表示します。

例
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

`showToastWarning` メソッドはユーザーに警告トースト通知を表示します。

例
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

`showToastSorry` メソッドはユーザーに Sorry トースト通知を表示します。

例
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

`isLoading` メソッドは状態がローディング中かを確認します。

例
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

`afterLoad` メソッドは、状態が「ローディング」を完了するまでローダーを表示するために使用できます。

**loadingKey** パラメータを使用して他のローディングキーも確認できます：`afterLoad(child: () {}, loadingKey: 'home_data')`。

例
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

`afterNotLocked` メソッドは状態がロックされているかを確認します。

状態がロックされている場合、[loading] ウィジェットが表示されます。

例
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

`afterNotNull` を使用して、変数が設定されるまでローディングウィジェットを表示できます。

Future 呼び出しを使用して DB からユーザーのアカウントを取得する必要があり、1〜2秒かかる場合を想像してください。データが取得されるまで、その値に対して afterNotNull を使用できます。

例

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // 例
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

`setLoading` を使用して「ローディング」状態に変更できます。

最初のパラメータはローディング中かどうかの `bool` を受け取り、次のパラメータではローディング状態の名前を設定できます。例：`setLoading(true, name: 'refreshing_content');`。

例
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
