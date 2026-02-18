# コントローラー

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [コントローラーの作成](#creating-controllers "コントローラーの作成")
- [コントローラーの使用](#using-controllers "コントローラーの使用")
- コントローラーの機能
  - [ルートデータへのアクセス](#accessing-route-data "ルートデータへのアクセス")
  - [クエリパラメータ](#query-parameters "クエリパラメータ")
  - [ページ状態管理](#page-state-management "ページ状態管理")
  - [トースト通知](#toast-notifications "トースト通知")
  - [フォームバリデーション](#form-validation "フォームバリデーション")
  - [言語切り替え](#language-switching "言語切り替え")
  - [ロックリリース](#lock-release "ロックリリース")
  - [アクション確認](#confirm-actions "アクション確認")
- [シングルトンコントローラー](#singleton-controllers "シングルトンコントローラー")
- [コントローラーデコーダー](#controller-decoders "コントローラーデコーダー")
- [ルートガード](#route-guards "ルートガード")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 のコントローラーは、ビュー（ページ）とビジネスロジックの間のコーディネーターとして機能します。ユーザー入力の処理、状態更新の管理を行い、関心の分離をクリーンに保ちます。

{{ config('app.name') }} v7 では、トースト通知、フォームバリデーション、状態管理などの強力な組み込みメソッドを持つ `NyController` クラスが導入されています。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // サービスの初期化やデータの取得
  }

  void onTapProfile() {
    routeTo(ProfilePage.path);
  }

  void submitForm() {
    validate(
      rules: {"email": "email"},
      onSuccess: () => showToastSuccess(description: "Form submitted!"),
    );
  }
}
```

<div id="creating-controllers"></div>

## コントローラーの作成

Metro CLI を使用してコントローラーを生成します：

``` bash
# コントローラー付きのページを作成
metro make:page dashboard --controller
# または省略形
metro make:page dashboard -c

# コントローラーのみを作成
metro make:controller profile_controller
```

これにより以下が作成されます：
- **コントローラー**: `lib/app/controllers/dashboard_controller.dart`
- **ページ**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## コントローラーの使用

`NyStatefulWidget` のジェネリック型としてコントローラーを指定して、ページに接続します：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';

class HomePage extends NyStatefulWidget<HomeController> {

  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {

  @override
  get init => () async {
    // コントローラーのメソッドにアクセス
    widget.controller.fetchData();
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Home")),
      body: Column(
        children: [
          ElevatedButton(
            onPressed: widget.controller.onTapProfile,
            child: Text("View Profile"),
          ),
          TextField(
            controller: widget.controller.nameController,
          ),
        ],
      ),
    );
  }
}
```

<div id="accessing-route-data"></div>

## ルートデータへのアクセス

ページ間でデータを渡し、コントローラーでアクセスします：

``` dart
// データ付きでナビゲーション
routeTo(ProfilePage.path, data: {"userId": 123});

// コントローラー内で
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // 渡されたデータを取得
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

または、ページの状態から直接データにアクセスします：

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // コントローラーから
    var userData = widget.controller.data();

    // またはウィジェットから直接
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## クエリパラメータ

コントローラーで URL クエリパラメータにアクセスします：

``` dart
// /profile?tab=settings&highlight=true に遷移
routeTo("/profile?tab=settings&highlight=true");

// コントローラー内で
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // すべてのクエリパラメータを Map として取得
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // 特定のパラメータを取得
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

クエリパラメータの存在を確認します：

``` dart
// ページ内で
if (widget.hasQueryParameter("tab")) {
  // tab パラメータを処理
}
```

<div id="page-state-management"></div>

## ページ状態管理

コントローラーはページの状態を直接管理できます：

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // ページの setState をトリガー
    setState(setState: () {});
  }

  void refresh() {
    // ページ全体を更新
    refreshPage();
  }

  void goBack() {
    // オプションの結果と共にページをポップ
    pop(result: {"updated": true});
  }

  void updateCustomState() {
    // カスタムアクションをページに送信
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## トースト通知

コントローラーには組み込みのトースト通知メソッドが含まれています：

``` dart
class FormController extends NyController {

  void showNotifications() {
    // 成功トースト
    showToastSuccess(description: "Profile updated!");

    // 警告トースト
    showToastWarning(description: "Please check your input");

    // エラー/危険トースト
    showToastDanger(description: "Failed to save changes");

    // 情報トースト
    showToastInfo(description: "New features available");

    // Sorry トースト
    showToastSorry(description: "We couldn't process your request");

    // Oops トースト
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // タイトル付きカスタムトースト
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // カスタムトーストスタイル（Nylo に登録済み）を使用
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## フォームバリデーション

コントローラーから直接フォームデータをバリデーションします：

``` dart
class RegisterController extends NyController {

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  void submitRegistration() {
    validate(
      rules: {
        "email": "email|max:50",
        "password": "min:8|max:64",
      },
      data: {
        "email": emailController.text,
        "password": passwordController.text,
      },
      messages: {
        "email.email": "Please enter a valid email",
        "password.min": "Password must be at least 8 characters",
      },
      showAlert: true,
      alertStyle: 'warning',
      onSuccess: () {
        // バリデーション成功
        _performRegistration();
      },
      onFailure: (exception) {
        // バリデーション失敗
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // 登録ロジックを処理
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## 言語切り替え

コントローラーからアプリの言語を変更します：

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es', restartState: true);
  }

  void switchToEnglish() {
    changeLanguage('en', restartState: true);
  }
}
```

<div id="lock-release"></div>

## ロックリリース

ボタンの連続タップを防止します：

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // ロックが解除されるまで、このコードは一度だけ実行されます
      await processPayment();
      showToastSuccess(description: "Payment complete!");
    });
  }

  void onTapWithoutSetState() {
    lockRelease(
      "my_lock",
      perform: () async {
        await someAsyncOperation();
      },
      shouldSetState: false, // 完了後に setState をトリガーしない
    );
  }
}
```

<div id="confirm-actions"></div>

## アクション確認

破壊的なアクションの前に確認ダイアログを表示します：

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // ユーザーが確認 - 削除を実行
        await deleteAccount();
        showToastSuccess(description: "Account deleted");
      },
      title: "Delete Account?",
      dismissText: "Cancel",
    );
  }
}
```

<div id="singleton-controllers"></div>

## シングルトンコントローラー

コントローラーをアプリ全体でシングルトンとして永続化します：

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // ログインロジック
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

シングルトンコントローラーは一度作成され、アプリのライフサイクル全体で再利用されます。

<div id="controller-decoders"></div>

## コントローラーデコーダー

`lib/config/decoders.dart` にコントローラーを登録します：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';
import '/app/controllers/profile_controller.dart';
import '/app/controllers/auth_controller.dart';

final Map<Type, BaseController Function()> controllers = {
  HomeController: () => HomeController(),
  ProfileController: () => ProfileController(),
  AuthController: () => AuthController(),
};
```

このマップにより、{{ config('app.name') }} はページが読み込まれた時にコントローラーを解決できます。

<div id="route-guards"></div>

## ルートガード

コントローラーでは、ページが読み込まれる前に実行されるルートガードを定義できます：

``` dart
class AdminController extends NyController {

  @override
  List<RouteGuard> get routeGuards => [
    AuthRouteGuard(),
    AdminRoleGuard(),
  ];

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // すべてのガードが通過した場合のみ実行されます
  }
}
```

ルートガードの詳細については、[ルーター ドキュメント](/docs/7.x/router)をご覧ください。
