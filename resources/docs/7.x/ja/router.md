# ルーター

---

<a name="section-1"></a>

- [はじめに](#introduction "はじめに")
- 基本
  - [ルートの追加](#adding-routes "ルートの追加")
  - [ページへのナビゲーション](#navigating-to-pages "ページへのナビゲーション")
  - [初期ルート](#initial-route "初期ルート")
  - [プレビュールート](#preview-route "プレビュールート")
  - [認証済みルート](#authenticated-route "認証済みルート")
  - [不明なルート](#unknown-route "不明なルート")
- 別のページへのデータ送信
  - [別のページへのデータ渡し](#passing-data-to-another-page "別のページへのデータ渡し")
- ナビゲーション
  - [ナビゲーションタイプ](#navigation-types "ナビゲーションタイプ")
  - [前のページに戻る](#navigating-back "前のページに戻る")
  - [条件付きナビゲーション](#conditional-navigation "条件付きナビゲーション")
  - [ページトランジション](#page-transitions "ページトランジション")
  - [ルート履歴](#route-history "ルート履歴")
  - [ルートスタックの更新](#update-route-stack "ルートスタックの更新")
- ルートパラメータ
  - [ルートパラメータの使用](#route-parameters "ルートパラメータの使用")
  - [クエリパラメータ](#query-parameters "クエリパラメータ")
- ルートガード
  - [ルートガードの作成](#route-guards "ルートガードの作成")
  - [NyRouteGuard ライフサイクル](#nyroute-guard-lifecycle "NyRouteGuard ライフサイクル")
  - [ガードヘルパーメソッド](#guard-helper-methods "ガードヘルパーメソッド")
  - [パラメータ付きガード](#parameterized-guards "パラメータ付きガード")
  - [ガードスタック](#guard-stacks "ガードスタック")
  - [条件付きガード](#conditional-guards "条件付きガード")
- ルートグループ
  - [ルートグループ](#route-groups "ルートグループ")
- [ディープリンク](#deep-linking "ディープリンク")
- [高度な使い方](#advanced "高度な使い方")



<div id="introduction"></div>

## はじめに

ルートを使用すると、アプリ内のさまざまなページを定義し、ページ間をナビゲーションできます。

ルートは以下の場合に使用します:
- アプリで利用可能なページを定義する
- 画面間をナビゲーションする
- 認証でページを保護する
- あるページから別のページへデータを渡す
- URL からのディープリンクを処理する

`lib/routes/router.dart` ファイル内にルートを追加できます。

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // ルートを追加する
  // router.add(AccountPage.path);

});
```

> **ヒント:** ルートは手動で作成するか、<a href="/docs/{{ $version }}/metro">Metro</a> CLI ツールを使用して自動作成できます。

以下は Metro を使用して 'account' ページを作成する例です。

``` bash
metro make:page account_page
```

``` dart
// 新しいルートが自動的に /lib/routes/router.dart に追加されます
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

あるビューから別のビューへデータを渡す必要がある場合もあります。{{ config('app.name') }} では、`NyStatefulWidget`（ルートデータアクセスが組み込まれたステートフルウィジェット）を使用してこれが可能です。この仕組みについて詳しく説明します。


<div id="adding-routes"></div>

## ルートの追加

プロジェクトに新しいルートを追加する最も簡単な方法です。

以下のコマンドを実行して新しいページを作成します。

```bash
metro make:page profile_page
```

上記を実行すると、`ProfilePage` という名前の新しい Widget が作成され、`resources/pages/` ディレクトリに追加されます。
また、新しいルートが `lib/routes/router.dart` ファイルに自動的に追加されます。

ファイル: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // 新しいルート
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## ページへのナビゲーション

`routeTo` ヘルパーを使用して新しいページにナビゲーションできます。

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## 初期ルート

ルーターで `.initialRoute()` メソッドを使用して、最初に読み込まれるページを定義できます。

初期ルートを設定すると、アプリを開いたときに最初に読み込まれるページになります。

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // 新しい初期ルート
});
```


### 条件付き初期ルート

`when` パラメータを使用して条件付き初期ルートを設定することもできます:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### 初期ルートへのナビゲーション

`routeToInitial()` を使用してアプリの初期ルートにナビゲーションします:

``` dart
void _goHome() {
    routeToInitial();
}
```

これは `.initialRoute()` でマークされたルートにナビゲーションし、ナビゲーションスタックをクリアします。

<div id="preview-route"></div>

## プレビュールート

開発中に、初期ルートを永続的に変更せずに特定のページをすばやくプレビューしたい場合があります。`.previewRoute()` を使用して、任意のルートを一時的に初期ルートにします:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // 開発中にこのページが最初に表示されます
});
```

`previewRoute()` メソッド:
- 既存の `initialRoute()` と `authenticatedRoute()` の設定をオーバーライドします
- 指定されたルートを初期ルートにします
- 開発中に特定のページをすばやくテストするのに便利です

> **警告:** アプリのリリース前に `.previewRoute()` を削除することを忘れないでください!

<div id="authenticated-route"></div>

## 認証済みルート

アプリで、ユーザーが認証済みの場合に初期ルートとなるルートを定義できます。
これにより、デフォルトの初期ルートが自動的にオーバーライドされ、ログイン時にユーザーが最初に見るページになります。

まず、`Auth.authenticate({...})` ヘルパーを使用してユーザーをログインさせる必要があります。

ログインすると、定義したルートがログアウトするまでデフォルトページになります。

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // 認証済みページ
});
```

### 条件付き認証済みルート

条件付き認証済みルートを設定することもできます:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### 認証済みルートへのナビゲーション

`routeToAuthenticatedRoute()` ヘルパーを使用して認証済みページにナビゲーションできます:

``` dart
routeToAuthenticatedRoute();
```

**参照:** ユーザーの認証とセッション管理の詳細については [Authentication](/docs/{{ $version }}/authentication) をご覧ください。


<div id="unknown-route"></div>

## 不明なルート

`.unknownRoute()` を使用して 404/未検出シナリオを処理するルートを定義できます:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

存在しないルートにナビゲーションした場合、不明なルートページが表示されます。


<div id="route-guards"></div>

## ルートガード

ルートガードは、不正なアクセスからページを保護します。ナビゲーションが完了する前に実行され、条件に基づいてユーザーをリダイレクトしたりアクセスをブロックしたりできます。

ルートガードは以下の場合に使用します:
- 未認証のユーザーからページを保護する
- アクセスを許可する前に権限を確認する
- 条件に基づいてユーザーをリダイレクトする（例: オンボーディング未完了）
- ページビューをログまたはトラッキングする

新しいルートガードを作成するには、以下のコマンドを実行します。

``` bash
metro make:route_guard dashboard
```

次に、新しいルートガードをルートに追加します。

``` dart
// ファイル: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // ガードを追加
    ]
  ); // 制限されたページ
});
```

`addRouteGuard` メソッドを使用してルートガードを設定することもできます:

``` dart
// ファイル: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // または複数のガードを追加する

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard ライフサイクル

v7 では、ルートガードは 3 つのライフサイクルメソッドを持つ `NyRouteGuard` クラスを使用します:

- **`onBefore(RouteContext context)`** - ナビゲーション前に呼び出されます。`next()` で続行、`redirect()` で別のページへ移動、`abort()` で停止を返します。
- **`onAfter(RouteContext context)`** - ルートへのナビゲーションが成功した後に呼び出されます。

### 基本的な例

ファイル: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // ページにアクセスできるか確認する
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // ナビゲーション成功後にページビューをトラッキングする
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

`RouteContext` クラスはナビゲーション情報へのアクセスを提供します:

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `context` | `BuildContext?` | 現在のビルドコンテキスト |
| `data` | `dynamic` | ルートに渡されたデータ |
| `queryParameters` | `Map<String, String>` | URL クエリパラメータ |
| `routeName` | `String` | ルート名/パス |
| `originalRouteName` | `String?` | 変換前の元のルート名 |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## ガードヘルパーメソッド

### next()

次のガードまたはルートに続行します:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // ナビゲーションを続行する
}
```

### redirect()

別のルートにリダイレクトします:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

`redirect()` メソッドが受け付けるパラメータ:

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `path` | `Object` | ルートパスまたは RouteView |
| `data` | `dynamic` | ルートに渡すデータ |
| `queryParameters` | `Map<String, dynamic>?` | クエリパラメータ |
| `navigationType` | `NavigationType` | ナビゲーションタイプ（デフォルト: pushReplace） |
| `transitionType` | `TransitionType?` | ページトランジション |
| `onPop` | `Function(dynamic)?` | ルートが pop された時のコールバック |

### abort()

リダイレクトせずにナビゲーションを停止します:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // ユーザーは現在のルートに留まる
  }
  return next();
}
```

### setData()

後続のガードとルートに渡すデータを変更します:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## パラメータ付きガード

ルートごとにガードの動作を設定する必要がある場合は `ParameterizedGuard` を使用します:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// 使い方:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## ガードスタック

`GuardStack` を使用して複数のガードを再利用可能な単一のガードに組み合わせます:

``` dart
// 再利用可能なガードの組み合わせを作成する
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## 条件付きガード

条件に基づいてガードを適用します:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## 別のページへのデータ渡し

このセクションでは、あるウィジェットから別のウィジェットにデータを渡す方法を説明します。

ウィジェットから `routeTo` ヘルパーを使用し、新しいページに送信したい `data` を渡します。

``` dart
// HomePage ウィジェット
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage ウィジェット（別のページ）
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // または
    print(data()); // Hello World
  };
```

その他の例

``` dart
// ホームページウィジェット
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// プロフィールページウィジェット（別のページ）
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## ルートグループ

ルートグループは関連するルートを整理し、共通の設定を適用します。複数のルートが同じガード、URL プレフィックス、またはトランジションスタイルを必要とする場合に便利です。

ルートグループは以下の場合に使用します:
- 複数のページに同じルートガードを適用する
- ルートのセットに URL プレフィックスを追加する（例: `/admin/...`）
- 関連するルートに同じページトランジションを設定する

以下の例のようにルートグループを定義できます。

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### ルートグループのオプション設定:

| 設定 | 型 | 説明 |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | グループ内のすべてのルートにルートガードを適用 |
| `prefix` | `String` | グループ内のすべてのルートパスにプレフィックスを追加 |
| `transition_type` | `TransitionType` | グループ内のすべてのルートにトランジションを設定 |
| `transition` | `PageTransitionType` | ページトランジションタイプを設定（非推奨、transition_type を使用してください） |
| `transition_settings` | `PageTransitionSettings` | トランジション設定を設定 |


<div id="route-parameters"></div>

## ルートパラメータの使用

新しいページを作成する際に、ルートがパラメータを受け付けるように更新できます。

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

ページにナビゲーションする際に `userId` を渡すことができます。

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

新しいページでパラメータにアクセスするには、以下のようにします。

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## クエリパラメータ

新しいページにナビゲーションする際に、クエリパラメータを提供することもできます。

見てみましょう。

```dart
  // ホームページ
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // プロフィールページにナビゲーション

  ...

  // プロフィールページ
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // または
    print(queryParameters()); // {"user": 7}
  };
```

> **注:** ページウィジェットが `NyStatefulWidget` と `NyPage` クラスを継承している限り、`widget.queryParameters()` を呼び出してルート名からすべてのクエリパラメータを取得できます。

```dart
// ページの例
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// ホームページ
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // または
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **ヒント:** クエリパラメータは HTTP プロトコルに従う必要があります。例: /account?userId=1&tab=2


<div id="page-transitions"></div>

## ページトランジション

`router.dart` ファイルを変更して、ページ間のナビゲーション時にトランジションを追加できます。

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### 利用可能なページトランジション

#### 基本トランジション
- **`TransitionType.fade()`** - 古いページをフェードアウトしながら新しいページをフェードイン
- **`TransitionType.theme()`** - アプリテーマのページトランジションテーマを使用

#### 方向スライドトランジション
- **`TransitionType.rightToLeft()`** - 画面の右端からスライド
- **`TransitionType.leftToRight()`** - 画面の左端からスライド
- **`TransitionType.topToBottom()`** - 画面の上端からスライド
- **`TransitionType.bottomToTop()`** - 画面の下端からスライド

#### フェード付きスライドトランジション
- **`TransitionType.rightToLeftWithFade()`** - 右端からスライドしながらフェード
- **`TransitionType.leftToRightWithFade()`** - 左端からスライドしながらフェード

#### 変形トランジション
- **`TransitionType.scale(alignment: ...)`** - 指定されたアライメントポイントからスケール
- **`TransitionType.rotate(alignment: ...)`** - 指定されたアライメントポイントを中心に回転
- **`TransitionType.size(alignment: ...)`** - 指定されたアライメントポイントから拡大

#### 連動トランジション（現在のウィジェットが必要）
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - 現在のページが右に退出し、新しいページが左から入場
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - 現在のページが左に退出し、新しいページが右から入場
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - 現在のページが下に退出し、新しいページが上から入場
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - 現在のページが上に退出し、新しいページが下から入場

#### Pop トランジション（現在のウィジェットが必要）
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - 現在のページが右に退出し、新しいページはそのまま
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - 現在のページが左に退出し、新しいページはそのまま
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - 現在のページが下に退出し、新しいページはそのまま
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - 現在のページが上に退出し、新しいページはそのまま

#### Material Design 共有軸トランジション
- **`TransitionType.sharedAxisHorizontal()`** - 水平スライドとフェードのトランジション
- **`TransitionType.sharedAxisVertical()`** - 垂直スライドとフェードのトランジション
- **`TransitionType.sharedAxisScale()`** - スケールとフェードのトランジション

#### カスタマイズパラメータ
各トランジションは以下のオプションパラメータを受け付けます:

| パラメータ | 説明 | デフォルト |
|-----------|-------------|---------|
| `curve` | アニメーションカーブ | プラットフォーム固有のカーブ |
| `duration` | アニメーション時間 | プラットフォーム固有の時間 |
| `reverseDuration` | 逆アニメーション時間 | duration と同じ |
| `fullscreenDialog` | ルートがフルスクリーンダイアログかどうか | `false` |
| `opaque` | ルートが不透明かどうか | `false` |


``` dart
// ホームページウィジェット
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## ナビゲーションタイプ

`routeTo` ヘルパーを使用する際に、以下のいずれかを指定できます。

| タイプ | 説明 |
|------|-------------|
| `NavigationType.push` | アプリのルートスタックに新しいページをプッシュ |
| `NavigationType.pushReplace` | 現在のルートを置き換え、新しいルートが完了したら前のルートを破棄 |
| `NavigationType.popAndPushNamed` | ナビゲータから現在のルートを pop し、その場所に名前付きルートをプッシュ |
| `NavigationType.pushAndRemoveUntil` | プッシュし、述語が true を返すまでルートを削除 |
| `NavigationType.pushAndForgetAll` | 新しいページにプッシュし、ルートスタック上の他のすべてのページを破棄 |

``` dart
// ホームページウィジェット
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## 前のページに戻る

新しいページに移動した後、`pop()` ヘルパーを使用して前のページに戻ることができます。

``` dart
// SettingsPage ウィジェット
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // または
    Navigator.pop(context);
  }
...
```

前のウィジェットに値を返したい場合は、以下の例のように `result` を指定します。

``` dart
// SettingsPage ウィジェット
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// onPop パラメータを使用して前のウィジェットから値を取得する
// HomePage ウィジェット
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## 条件付きナビゲーション

`routeIf()` を使用して、条件が満たされた場合にのみナビゲーションします:

``` dart
// ユーザーがログインしている場合のみナビゲーションする
routeIf(isLoggedIn, DashboardPage.path);

// 追加オプション付き
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

条件が `false` の場合、ナビゲーションは発生しません。


<div id="route-history"></div>

## ルート履歴

{{ config('app.name') }} では、以下のヘルパーを使用してルート履歴情報にアクセスできます。

``` dart
// ルート履歴を取得する
Nylo.getRouteHistory(); // List<dynamic>

// 現在のルートを取得する
Nylo.getCurrentRoute(); // Route<dynamic>?

// 前のルートを取得する
Nylo.getPreviousRoute(); // Route<dynamic>?

// 現在のルート名を取得する
Nylo.getCurrentRouteName(); // String?

// 前のルート名を取得する
Nylo.getPreviousRouteName(); // String?

// 現在のルートの引数を取得する
Nylo.getCurrentRouteArguments(); // dynamic

// 前のルートの引数を取得する
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## ルートスタックの更新

`NyNavigator.updateStack()` を使用してプログラム的にナビゲーションスタックを更新できます:

``` dart
// ルートのリストでスタックを更新する
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// 特定のルートにデータを渡す
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | 必須 | ナビゲーションするルートパスのリスト |
| `replace` | `bool` | `true` | 現在のスタックを置き換えるかどうか |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | 特定のルートに渡すデータ |

これは以下の場合に便利です:
- ディープリンクのシナリオ
- ナビゲーション状態の復元
- 複雑なナビゲーションフローの構築


<div id="deep-linking"></div>

## ディープリンク

ディープリンクを使用すると、URL を使用してアプリ内の特定のコンテンツに直接ナビゲーションできます。以下の場合に便利です:
- アプリ内の特定のコンテンツへの直接リンクを共有する
- アプリ内の特定の機能をターゲットにしたマーケティングキャンペーン
- 特定のアプリ画面を開く通知の処理
- Web からアプリへのシームレスな移行

## セットアップ

アプリでディープリンクを実装する前に、プロジェクトが正しく設定されていることを確認してください:

### 1. プラットフォーム設定

**iOS**: Xcode プロジェクトで Universal Links を設定します
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter Universal Links 設定ガイド</a>

**Android**: AndroidManifest.xml で App Links を設定します
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter App Links 設定ガイド</a>

### 2. ルートの定義

ディープリンクでアクセス可能なすべてのルートは、ルーター設定に登録する必要があります:

```dart
// ファイル: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // 基本ルート
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // パラメータ付きルート
  router.add(HotelBookingPage.path);
});
```

## ディープリンクの使用

設定が完了すると、アプリはさまざまな形式の受信 URL を処理できます:

### 基本的なディープリンク

特定のページへのシンプルなナビゲーション:

``` bash
https://yourdomain.com/profile       // プロフィールページを開く
https://yourdomain.com/settings      // 設定ページを開く
```

アプリ内でプログラム的にこれらのナビゲーションをトリガーするには:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### パスパラメータ

パスの一部として動的データが必要なルートの場合:

#### ルート定義

```dart
class HotelBookingPage extends NyStatefulWidget {
  // パラメータプレースホルダー {id} を持つルートを定義する
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // パスパラメータにアクセスする
    final hotelId = queryParameters()["id"]; // URL ../hotel/87/booking の場合 "87" を返す
    print("Loading hotel ID: $hotelId");

    // ID を使用してホテルデータを取得するか操作を実行する
  };

  // ページの残りの実装
}
```

#### URL 形式

``` bash
https://yourdomain.com/hotel/87/booking
```

#### プログラム的なナビゲーション

```dart
// パラメータ付きでナビゲーションする
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### クエリパラメータ

オプションのパラメータや複数の動的な値が必要な場合:

#### URL 形式

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### クエリパラメータへのアクセス

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // すべてのクエリパラメータを取得する
    final params = queryParameters();

    // 特定のパラメータにアクセスする
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // 別のアクセス方法
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### クエリパラメータ付きのプログラム的なナビゲーション

```dart
// クエリパラメータ付きでナビゲーションする
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// パスパラメータとクエリパラメータを組み合わせる
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## ディープリンクの処理

`RouteProvider` でディープリンクイベントを処理できます:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // ディープリンクを処理する
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // ディープリンク用にルートスタックを更新する
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### ディープリンクのテスト

開発とテストでは、ADB（Android）または xcrun（iOS）を使用してディープリンクの起動をシミュレートできます:

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### デバッグのヒント

- init メソッドですべてのパラメータを出力して、正しくパースされていることを確認する
- さまざまな URL 形式をテストして、アプリが正しく処理することを確認する
- クエリパラメータは常に文字列として受信されるため、必要に応じて適切な型に変換する

---

## よくあるパターン

### パラメータの型変換

すべての URL パラメータは文字列として渡されるため、変換が必要になることが多いです:

```dart
// 文字列パラメータを適切な型に変換する
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### オプションパラメータ

パラメータが存在しない場合の処理:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // 特定のユーザープロフィールを読み込む
} else {
  // 現在のユーザープロフィールを読み込む
}

// または hasQueryParameter で確認する
if (hasQueryParameter('status')) {
  // status パラメータで何かをする
} else {
  // パラメータがない場合を処理する
}
```


<div id="advanced"></div>

## 高度な使い方

### ルートの存在確認

ルーターにルートが登録されているか確認できます:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter メソッド

`NyRouter` クラスにはいくつかの便利なメソッドがあります:

| メソッド | 説明 |
|--------|-------------|
| `getRegisteredRouteNames()` | 登録されたすべてのルート名をリストとして取得 |
| `getRegisteredRoutes()` | 登録されたすべてのルートをマップとして取得 |
| `containsRoutes(routes)` | ルーターに指定されたすべてのルートが含まれているか確認 |
| `getInitialRouteName()` | 初期ルート名を取得 |
| `getAuthRouteName()` | 認証済みルート名を取得 |
| `getUnknownRouteName()` | 不明/404 ルート名を取得 |

### ルート引数の取得

`NyRouter.args<T>()` を使用してルート引数を取得できます:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // 型付き引数を取得する
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument と NyQueryParameters

ルート間で渡されるデータはこれらのクラスでラップされます:

``` dart
// NyArgument にはルートデータが含まれる
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters には URL クエリパラメータが含まれる
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
