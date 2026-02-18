# 認証

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 基本
  - [ユーザーの認証](#authenticating-users "ユーザーの認証")
  - [認証データの取得](#retrieving-auth-data "認証データの取得")
  - [認証データの更新](#updating-auth-data "認証データの更新")
  - [ログアウト](#logging-out "ログアウト")
  - [認証状態の確認](#checking-authentication "認証状態の確認")
- 応用
  - [マルチセッション](#multiple-sessions "マルチセッション")
  - [デバイス ID](#device-id "デバイス ID")
  - [Backpack への同期](#syncing-to-backpack "Backpack への同期")
- ルート設定
  - [初期ルート](#initial-route "初期ルート")
  - [認証済みルート](#authenticated-route "認証済みルート")
  - [プレビュールート](#preview-route "プレビュールート")
  - [不明なルート](#unknown-route "不明なルート")
- [ヘルパー関数](#helper-functions "ヘルパー関数")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は `Auth` クラスを通じて包括的な認証システムを提供します。ユーザー認証情報の安全な保存、セッション管理を処理し、異なる認証コンテキスト用の複数の名前付きセッションをサポートします。

認証データは安全に保存され、アプリ全体で高速な同期アクセスを可能にするために Backpack（インメモリキーバリューストア）に同期されます。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// ユーザーを認証
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// 認証状態を確認
bool loggedIn = await Auth.isAuthenticated(); // true

// 認証データを取得
dynamic token = Auth.data(field: 'token'); // "abc123"

// ログアウト
await Auth.logout();
```


<div id="authenticating-users"></div>

## ユーザーの認証

`Auth.authenticate()` を使用してユーザーセッションデータを保存します:

``` dart
// Map を使用
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// Model クラスを使用
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// データなし（タイムスタンプを保存）
await Auth.authenticate();
```

### 実際の使用例

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. API を呼び出して認証
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. 認証済みユーザーを保存
    await Auth.authenticate(data: user);

    // 3. ホームに遷移
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## 認証データの取得

`Auth.data()` を使用して保存された認証データを取得します:

``` dart
// すべての認証データを取得
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// 特定のフィールドを取得
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

`Auth.data()` メソッドは高速な同期アクセスのために Backpack（{{ config('app.name') }} のインメモリキーバリューストア）から読み取ります。認証時にデータは自動的に Backpack に同期されます。


<div id="updating-auth-data"></div>

## 認証データの更新

{{ config('app.name') }} v7 では認証データを更新するための `Auth.set()` が導入されました:

``` dart
// 特定のフィールドを更新
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// 新しいフィールドを追加
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// データ全体を置き換え
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## ログアウト

`Auth.logout()` で認証済みユーザーを削除します:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // ログインページに遷移
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### すべてのセッションからログアウト

マルチセッション使用時にすべてをクリアします:

``` dart
// デフォルトとすべての名前付きセッションからログアウト
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## 認証状態の確認

ユーザーが現在認証されているかどうかを確認します:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // ユーザーは認証済み
  routeTo(HomePage.path);
} else {
  // ユーザーはログインが必要
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## マルチセッション

{{ config('app.name') }} v7 は異なるコンテキスト用の複数の名前付き認証セッションをサポートします。異なる種類の認証を個別に追跡する必要がある場合に便利です（例: ユーザーログイン vs デバイス登録 vs 管理者アクセス）。

``` dart
// デフォルトのユーザーセッション
await Auth.authenticate(data: user);

// デバイス認証セッション
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// 管理者セッション
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### 名前付きセッションからの読み取り

``` dart
// デフォルトセッション
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// デバイスセッション
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// 管理者セッション
dynamic adminData = Auth.data(session: 'admin');
```

### セッション固有のログアウト

``` dart
// デフォルトセッションのみからログアウト
await Auth.logout();

// デバイスセッションのみからログアウト
await Auth.logout(session: 'device');

// すべてのセッションからログアウト
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### セッションごとの認証確認

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## デバイス ID

{{ config('app.name') }} v7 はアプリセッション間で永続化される一意のデバイス識別子を提供します:

``` dart
String deviceId = await Auth.deviceId();
// 例: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

デバイス ID は:
- 一度生成され、永続的に保存されます
- 各デバイス/インストールに固有です
- デバイス登録、アナリティクス、プッシュ通知に便利です

``` dart
// 例: バックエンドへのデバイス登録
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // デバイス認証を保存
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## Backpack への同期

認証データは認証時に自動的に Backpack に同期されます。手動で同期するには（例: アプリ起動時）:

``` dart
// デフォルトセッションを同期
await Auth.syncToBackpack();

// 特定のセッションを同期
await Auth.syncToBackpack(session: 'device');

// すべてのセッションを同期
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

これはアプリのブートシーケンスで、高速な同期アクセスのために Backpack で認証データを利用可能にするのに便利です。


<div id="initial-route"></div>

## 初期ルート

初期ルートは、ユーザーがアプリを開いた時に最初に表示されるページです。ルーターで `.initialRoute()` を使用して設定します:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

`when` パラメータを使用して条件付き初期ルートも設定できます:

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

`routeToInitial()` を使用してどこからでも初期ルートに戻ります:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## 認証済みルート

認証済みルートは、ユーザーがログインしている場合に初期ルートを上書きします。`.authenticatedRoute()` を使用して設定します:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

アプリ起動時:
- `Auth.isAuthenticated()` が `true` を返す場合 → ユーザーには**認証済みルート**（HomePage）が表示されます
- `Auth.isAuthenticated()` が `false` を返す場合 → ユーザーには**初期ルート**（LoginPage）が表示されます

条件付き認証済みルートも設定できます:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

`routeToAuthenticatedRoute()` を使用してプログラムで認証済みルートに遷移します:

``` dart
// ログイン後
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**参照:** ガードやディープリンクを含む完全なルーティングドキュメントは [Router](/docs/{{ $version }}/router) を参照してください。


<div id="preview-route"></div>

## プレビュールート

開発中に、初期ルートや認証済みルートを変更せずに特定のページをすばやくプレビューしたい場合があります。`.previewRoute()` を使用します:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // 開発中に最初に表示
});
```

`previewRoute()` は `initialRoute()` と `authenticatedRoute()` の**両方を上書き**し、認証状態に関係なく指定されたルートを最初のページとして表示します。

> **警告:** アプリをリリースする前に `.previewRoute()` を削除してください。


<div id="unknown-route"></div>

## 不明なルート

ユーザーが存在しないルートに遷移した時のフォールバックページを定義します。`.unknownRoute()` を使用して設定します:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### すべてをまとめる

すべてのルートタイプを含む完全なルーター設定:

``` dart
appRouter() => nyRoutes((router) {
  // 未認証ユーザーの最初のページ
  router.add(LoginPage.path).initialRoute();

  // 認証済みユーザーの最初のページ
  router.add(HomePage.path).authenticatedRoute();

  // 404 ページ
  router.add(NotFoundPage.path).unknownRoute();

  // 通常のルート
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| ルートメソッド | 目的 |
|--------------|---------|
| `.initialRoute()` | 未認証ユーザーに表示される最初のページ |
| `.authenticatedRoute()` | 認証済みユーザーに表示される最初のページ |
| `.previewRoute()` | 開発中に両方を上書き |
| `.unknownRoute()` | ルートが見つからない場合に表示 |


<div id="helper-functions"></div>

## ヘルパー関数

{{ config('app.name') }} v7 は `Auth` クラスのメソッドに対応するヘルパー関数を提供します:

| ヘルパー関数 | 対応するメソッド |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | デフォルトセッションのストレージキー |
| `authDeviceId()` | `Auth.deviceId()` |

すべてのヘルパーは `Auth` クラスの対応するメソッドと同じパラメータを受け付け、オプションの `session` パラメータも含みます:

``` dart
// 名前付きセッションで認証
await authAuthenticate(data: device, session: 'device');

// 名前付きセッションから読み取り
dynamic deviceData = authData(session: 'device');

// 名前付きセッションを確認
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

