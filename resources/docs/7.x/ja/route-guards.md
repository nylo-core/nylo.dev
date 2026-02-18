# ルートガード

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [ルートガードの作成](#creating-a-route-guard "ルートガードの作成")
- [ガードのライフサイクル](#guard-lifecycle "ガードのライフサイクル")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [ガードアクション](#guard-actions "ガードアクション")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [ルートへのガードの適用](#applying-guards "ルートへのガードの適用")
- [グループガード](#group-guards "グループガード")
- [ガードの合成](#guard-composition "ガードの合成")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

ルートガードは {{ config('app.name') }} における **ナビゲーションのミドルウェア** を提供します。ルート遷移をインターセプトし、ユーザーがページにアクセスできるかどうかの制御、別のページへのリダイレクト、またはルートに渡されるデータの変更が可能です。

一般的なユースケース:
- **認証チェック** -- 未認証ユーザーをログインページにリダイレクト
- **ロールベースのアクセス** -- 管理者ユーザーにページを制限
- **データバリデーション** -- ナビゲーション前に必要なデータの存在を確認
- **データエンリッチメント** -- ルートに追加データを付与

ガードはナビゲーション前に **順番に** 実行されます。いずれかのガードが `handled` を返すと、ナビゲーションは停止します（リダイレクトまたは中止）。

<div id="creating-a-route-guard"></div>

## ルートガードの作成

Metro CLI を使用してルートガードを作成します:

``` bash
metro make:route_guard auth
```

これによりガードファイルが生成されます:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // ガードロジックをここに追加
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## ガードのライフサイクル

すべてのルートガードには 3 つのライフサイクルメソッドがあります:

<div id="on-before"></div>

### onBefore

ナビゲーションが発生する **前に** 呼び出されます。ここで条件を確認し、ナビゲーションを許可、リダイレクト、または中止するかを決定します。

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

戻り値:
- `next()` -- 次のガードに進むか、最後のガードの場合はルートにナビゲート
- `redirect(path)` -- 別のルートにリダイレクト
- `abort()` -- ナビゲーションを完全にキャンセル

<div id="on-after"></div>

### onAfter

ナビゲーションが成功した **後に** 呼び出されます。アナリティクス、ロギング、またはナビゲーション後の副作用に使用します。

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // ページビューを記録
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

ユーザーがルートを **離れる** ときに呼び出されます。`false` を返すとユーザーの離脱を防止できます。

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // 確認ダイアログを表示
    return await showConfirmDialog();
  }
  return true; // 離脱を許可
}
```

<div id="route-context"></div>

## RouteContext

`RouteContext` オブジェクトはすべてのガードライフサイクルメソッドに渡され、ナビゲーションに関する情報を含みます:

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `context` | `BuildContext?` | 現在のビルドコンテキスト |
| `data` | `dynamic` | ルートに渡されたデータ |
| `queryParameters` | `Map<String, String>` | URL クエリパラメータ |
| `routeName` | `String` | ターゲットルートの名前/パス |
| `originalRouteName` | `String?` | 変換前の元のルート名 |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // ルート情報へのアクセス
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### RouteContext の変換

異なるデータでコピーを作成します:

``` dart
// データ型を変更
RouteContext<User> userContext = context.withData<User>(currentUser);

// フィールドを変更してコピー
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## ガードアクション

<div id="next"></div>

### next

チェーンの次のガードに進むか、最後のガードの場合はルートにナビゲートします:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

ユーザーを別のルートにリダイレクトします:

``` dart
return redirect(LoginPage.path);
```

追加オプション付き:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `path` | `Object` | 必須 | ルートパス文字列または RouteView |
| `data` | `dynamic` | null | リダイレクト先に渡すデータ |
| `queryParameters` | `Map<String, dynamic>?` | null | クエリパラメータ |
| `navigationType` | `NavigationType` | `pushReplace` | ナビゲーション方法 |
| `result` | `dynamic` | null | 返す結果 |
| `removeUntilPredicate` | `Function?` | null | ルート削除の述語 |
| `transitionType` | `TransitionType?` | null | ページ遷移タイプ |
| `onPop` | `Function(dynamic)?` | null | ポップ時のコールバック |

<div id="abort"></div>

### abort

リダイレクトせずにナビゲーションをキャンセルします。ユーザーは現在のページに留まります:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

後続のガードとターゲットルートに渡されるデータを変更します:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // ルートデータをエンリッチ
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## ルートへのガードの適用

ルーターファイルで個々のルートにガードを追加します:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // 単一のガードを追加
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // 複数のガードを追加（順番に実行）
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## グループガード

ルートグループを使用して複数のルートに一度にガードを適用します:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // このグループのすべてのルートは認証が必要
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## ガードの合成

{{ config('app.name') }} は再利用可能なパターンのためにガードを合成するツールを提供します。

<div id="guard-stack"></div>

### GuardStack

複数のガードを単一の再利用可能なガードに統合します:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// ルートでスタックを使用
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` はガードを順番に実行します。いずれかのガードが `handled` を返すと、残りのガードはスキップされます。

<div id="conditional-guard"></div>

### ConditionalGuard

条件が true の場合にのみガードを適用します:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

条件が `false` を返す場合、ガードはスキップされナビゲーションが続行します。

<div id="parameterized-guard"></div>

### ParameterizedGuard

設定パラメータを受け取るガードを作成します:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = 許可されたロール

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// 使用方法
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## 使用例

### 認証ガード

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### パラメータ付きサブスクリプションガード

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// プレミアムまたはプロのサブスクリプションが必要
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### ロギングガード

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
