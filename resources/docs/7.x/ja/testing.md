# テスト

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [はじめに](#getting-started "はじめに")
- [テストの記述](#writing-tests "テストの記述")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [ウィジェットテストユーティリティ](#widget-testing-utilities "ウィジェットテストユーティリティ")
  - [nyGroup](#ny-group "nyGroup")
  - [テストライフサイクル](#test-lifecycle "テストライフサイクル")
  - [スキップと CI テスト](#skipping-tests "スキップと CI テスト")
- [認証](#authentication "認証")
- [タイムトラベル](#time-travel "タイムトラベル")
- [API モッキング](#api-mocking "API モッキング")
  - [URL パターンによるモッキング](#mocking-by-url "URL パターンによるモッキング")
  - [API サービスタイプによるモッキング](#mocking-by-type "API サービスタイプによるモッキング")
  - [呼び出し履歴とアサーション](#call-history "呼び出し履歴とアサーション")
- [ファクトリ](#factories "ファクトリ")
  - [ファクトリの定義](#defining-factories "ファクトリの定義")
  - [ファクトリステート](#factory-states "ファクトリステート")
  - [インスタンスの作成](#creating-instances "インスタンスの作成")
- [NyFaker](#ny-faker "NyFaker")
- [テストキャッシュ](#test-cache "テストキャッシュ")
- [プラットフォームチャネルモッキング](#platform-channel-mocking "プラットフォームチャネルモッキング")
- [ルートガードモッキング](#route-guard-mocking "ルートガードモッキング")
- [アサーション](#assertions "アサーション")
- [カスタムマッチャー](#custom-matchers "カスタムマッチャー")
- [ステートテスト](#state-testing "ステートテスト")
- [デバッグ](#debugging "デバッグ")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 には、Laravel のテストユーティリティに触発された包括的なテストフレームワークが含まれています。以下を提供します:

- 自動セットアップ/ティアダウン付きの**テスト関数**（`nyTest`、`nyWidgetTest`、`nyGroup`）
- `NyTest.actingAs<T>()` による**認証シミュレーション**
- テストで時間を凍結または操作する**タイムトラベル**
- URL パターンマッチングと呼び出し追跡による **API モッキング**
- 組み込みフェイクデータジェネレーター（`NyFaker`）付き**ファクトリ**
- セキュアストレージ、パスプロバイダーなどの**プラットフォームチャネルモッキング**
- ルート、Backpack、認証、環境用の**カスタムアサーション**

<div id="getting-started"></div>

## はじめに

テストファイルの先頭でテストフレームワークを初期化します:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` はテスト環境をセットアップし、`autoReset: true`（デフォルト）の場合、テスト間で自動ステートリセットを有効にします。

<div id="writing-tests"></div>

## テストの記述

<div id="ny-test"></div>

### nyTest

テストを記述するための主要な関数です:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

オプション:

``` dart
nyTest('my test', () async {
  // テスト本体
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

`WidgetTester` を使用して Flutter ウィジェットをテストします:

``` dart
nyWidgetTest('renders a button', (WidgetTester tester) async {
  await tester.pumpWidget(MaterialApp(
    home: Scaffold(
      body: ElevatedButton(
        onPressed: () {},
        child: Text("Tap me"),
      ),
    ),
  ));

  expect(find.text("Tap me"), findsOneWidget);
});
```

<div id="widget-testing-utilities"></div>

### ウィジェットテストユーティリティ

`NyWidgetTest` クラスと `WidgetTester` 拡張機能は、適切なテーマサポート付きで Nylo ウィジェットをポンプしたり、`init()` の完了を待機したり、ローディングステートをテストしたりするためのヘルパーを提供します。

#### テスト環境の設定

`setUpAll` で `NyWidgetTest.configure()` を呼び出して、Google Fonts の取得を無効にし、オプションでカスタムテーマを設定します:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

`NyWidgetTest.reset()` で設定をリセットできます。

フォントフリーテスト用に 2 つの組み込みテーマが利用可能です:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Nylo ウィジェットのポンプ

`pumpNyWidget` を使用してウィジェットをテーマサポート付きの `MaterialApp` でラップします:

``` dart
nyWidgetTest('renders page', (tester) async {
  await tester.pumpNyWidget(
    HomePage(),
    theme: ThemeData.light(),
    darkTheme: ThemeData.dark(),
    themeMode: ThemeMode.light,
    settleTimeout: Duration(seconds: 5),
    useSimpleTheme: false,
  );

  expect(find.text('Welcome'), findsOneWidget);
});
```

フォントフリーテーマでの簡易ポンプ:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Init の待機

`pumpNyWidgetAndWaitForInit` はローディングインジケーターが消える（またはタイムアウトに達する）までフレームをポンプします。非同期 `init()` メソッドを持つページに便利です:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() が完了
expect(find.text('Loaded Data'), findsOneWidget);
```

#### ポンプヘルパー

``` dart
// 特定のウィジェットが表示されるまでフレームをポンプ
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// グレースフルにセトル（タイムアウト時にスローしない）
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### ライフサイクルシミュレーション

ウィジェットツリー内の任意の `NyPage` で `AppLifecycleState` の変更をシミュレートします:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// 一時停止ライフサイクルアクションの副作用をアサート
```

#### ローディングとロックのチェック

`NyPage`/`NyState` ウィジェットの名前付きローディングキーとロックをチェックします:

``` dart
// 名前付きローディングキーがアクティブかチェック
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// 名前付きロックが保持されているかチェック
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// ローディングインジケーター（CircularProgressIndicator または Skeletonizer）をチェック
bool isAnyLoading = tester.isLoading();
```

#### testNyPage ヘルパー

`NyPage` をポンプし、init を待機してから期待値を実行する便利な関数です:

``` dart
testNyPage(
  'HomePage loads correctly',
  build: () => HomePage(),
  expectations: (tester) async {
    expect(find.text('Welcome'), findsOneWidget);
  },
  useSimpleTheme: true,
  initTimeout: Duration(seconds: 10),
  skip: false,
);
```

#### testNyPageLoading ヘルパー

`init()` 中にページがローディングインジケーターを表示することをテストします:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

一般的なページテストユーティリティを提供する mixin です:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // init が呼び出されてローディングが完了したことを検証
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // init 中にローディングステートが表示されることを検証
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

関連するテストをグループ化します:

``` dart
nyGroup('Authentication', () {
  nyTest('can login', () async {
    NyTest.actingAs<User>(User(name: "Anthony"));
    expectAuthenticated<User>();
  });

  nyTest('can logout', () async {
    NyTest.actingAs<User>(User(name: "Anthony"));
    NyTest.logout();
    expectGuest();
  });
});
```

<div id="test-lifecycle"></div>

### テストライフサイクル

ライフサイクルフックを使用してセットアップとティアダウンのロジックを設定します:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // すべてのテストの前に一度実行
  });

  nySetUp(() {
    // 各テストの前に実行
  });

  nyTearDown(() {
    // 各テストの後に実行
  });

  nyTearDownAll(() {
    // すべてのテストの後に一度実行
  });
}
```

<div id="skipping-tests"></div>

### スキップと CI テスト

``` dart
// 理由付きでテストをスキップ
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// 失敗が予想されるテスト
nyFailing('known bug', () async {
  // ...
});

// CI 専用テスト（'ci' タグ付き）
nyCi('integration test', () async {
  // CI 環境でのみ実行
});
```

<div id="authentication"></div>

## 認証

テストで認証済みユーザーをシミュレートします:

``` dart
nyTest('user can access profile', () async {
  // ログインユーザーをシミュレート
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // 認証済みであることを検証
  expectAuthenticated<User>();

  // アクティングユーザーにアクセス
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // 未認証であることを検証
  expectGuest();
});
```

ユーザーをログアウトします:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## タイムトラベル

`NyTime` を使用してテストで時間を操作します:

### 特定の日付にジャンプ

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // 実時間にリセット
});
```

### 時間の前進・巻き戻し

``` dart
NyTest.travelForward(Duration(days: 30)); // 30 日先にジャンプ
NyTest.travelBackward(Duration(hours: 2)); // 2 時間戻る
```

### 時間の凍結

``` dart
NyTest.freezeTime(); // 現在の瞬間で凍結

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // 時間は進んでいない

NyTest.travelBack(); // 凍結解除
```

### 時間の境界

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 当月の 1 日
NyTime.travelToEndOfMonth();   // 当月の最終日
NyTime.travelToStartOfYear();  // 1 月 1 日
NyTime.travelToEndOfYear();    // 12 月 31 日
```

### スコープ付きタイムトラベル

凍結された時間コンテキスト内でコードを実行します:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// コールバック後に時間は自動的に復元される
```

<div id="api-mocking"></div>

## API モッキング

<div id="mocking-by-url"></div>

### URL パターンによるモッキング

ワイルドカードサポート付きの URL パターンを使用して API レスポンスをモックします:

``` dart
nyTest('mock API responses', () async {
  // 完全一致 URL マッチ
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // 単一セグメントワイルドカード (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // 複数セグメントワイルドカード (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // ステータスコードとヘッダー付き
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // シミュレートされた遅延付き
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### API サービスタイプによるモッキング

タイプで API サービス全体をモックします:

``` dart
nyTest('mock API service', () async {
  NyMockApi.register<UserApiService>((MockApiRequest request) async {
    if (request.endpoint.contains('/users')) {
      return {'users': [{'id': 1, 'name': 'Anthony'}]};
    }
    return {'error': 'not found'};
  });
});
```

<div id="call-history"></div>

### 呼び出し履歴とアサーション

API 呼び出しを追跡して検証します:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... API 呼び出しをトリガーするアクションを実行 ...

  // エンドポイントが呼び出されたことをアサート
  expectApiCalled('/users');

  // エンドポイントが呼び出されなかったことをアサート
  expectApiNotCalled('/admin');

  // 呼び出し回数をアサート
  expectApiCalled('/users', times: 2);

  // 特定のメソッドをアサート
  expectApiCalled('/users', method: 'POST');

  // 呼び出しの詳細を取得
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

### モックレスポンスの作成

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## ファクトリ

<div id="defining-factories"></div>

### ファクトリの定義

モデルのテストインスタンスを作成する方法を定義します:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

オーバーライドサポート付き:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### ファクトリステート

ファクトリのバリエーションを定義します:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### インスタンスの作成

``` dart
// 単一インスタンスを作成
User user = NyFactory.make<User>();

// オーバーライド付きで作成
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// ステートを適用して作成
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// 複数インスタンスを作成
List<User> users = NyFactory.create<User>(count: 5);

// インデックスベースのデータでシーケンスを作成
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` はテスト用のリアルなフェイクデータを生成します。ファクトリ定義内で利用可能で、直接インスタンス化することもできます。

``` dart
NyFaker faker = NyFaker();
```

### 利用可能なメソッド

| カテゴリ | メソッド | 戻り値の型 | 説明 |
|----------|--------|-------------|-------------|
| **名前** | `faker.firstName()` | `String` | ランダムなファーストネーム |
| | `faker.lastName()` | `String` | ランダムなラストネーム |
| | `faker.name()` | `String` | フルネーム（ファースト + ラスト） |
| | `faker.username()` | `String` | ユーザー名文字列 |
| **連絡先** | `faker.email()` | `String` | メールアドレス |
| | `faker.phone()` | `String` | 電話番号 |
| | `faker.company()` | `String` | 会社名 |
| **数値** | `faker.randomInt(min, max)` | `int` | 範囲内のランダム整数 |
| | `faker.randomDouble(min, max)` | `double` | 範囲内のランダム小数 |
| | `faker.randomBool()` | `bool` | ランダムなブール値 |
| **識別子** | `faker.uuid()` | `String` | UUID v4 文字列 |
| **日付** | `faker.date()` | `DateTime` | ランダムな日付 |
| | `faker.pastDate()` | `DateTime` | 過去の日付 |
| | `faker.futureDate()` | `DateTime` | 未来の日付 |
| **テキスト** | `faker.lorem()` | `String` | Lorem ipsum ワード |
| | `faker.sentences()` | `String` | 複数の文 |
| | `faker.paragraphs()` | `String` | 複数の段落 |
| | `faker.slug()` | `String` | URL スラッグ |
| **Web** | `faker.url()` | `String` | URL 文字列 |
| | `faker.imageUrl()` | `String` | 画像 URL（picsum.photos 経由） |
| | `faker.ipAddress()` | `String` | IPv4 アドレス |
| | `faker.macAddress()` | `String` | MAC アドレス |
| **場所** | `faker.address()` | `String` | 住所 |
| | `faker.city()` | `String` | 都市名 |
| | `faker.state()` | `String` | 米国の州略称 |
| | `faker.zipCode()` | `String` | 郵便番号 |
| | `faker.country()` | `String` | 国名 |
| **その他** | `faker.hexColor()` | `String` | 16 進カラーコード |
| | `faker.creditCardNumber()` | `String` | クレジットカード番号 |
| | `faker.randomElement(list)` | `T` | リストからランダムなアイテム |
| | `faker.randomElements(list, count)` | `List<T>` | リストからランダムなアイテム群 |

<div id="test-cache"></div>

## テストキャッシュ

`NyTestCache` はキャッシュ関連機能をテストするためのインメモリキャッシュを提供します:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // 値を保存
  await cache.put<String>("key", "value");

  // 有効期限付きで保存
  await cache.put<String>("temp", "data", seconds: 60);

  // 値を読み取り
  String? value = await cache.get<String>("key");

  // 存在チェック
  bool exists = await cache.has("key");

  // キーをクリア
  await cache.clear("key");

  // すべてフラッシュ
  await cache.flush();

  // キャッシュ情報を取得
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## プラットフォームチャネルモッキング

`NyMockChannels` は一般的なプラットフォームチャネルを自動的にモックして、テストがクラッシュしないようにします:

``` dart
void main() {
  NyTest.init(); // モックチャネルを自動セットアップ

  // または手動でセットアップ
  NyMockChannels.setup();
}
```

### モック済みチャネル

- **path_provider** -- ドキュメント、一時、アプリケーションサポート、ライブラリ、キャッシュディレクトリ
- **flutter_secure_storage** -- インメモリセキュアストレージ
- **flutter_timezone** -- タイムゾーンデータ
- **flutter_local_notifications** -- 通知チャネル
- **sqflite** -- データベース操作

### パスのオーバーライド

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### テストでのセキュアストレージ

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## ルートガードモッキング

`NyMockRouteGuard` を使用すると、実際の認証やネットワーク呼び出しなしでルートガードの動作をテストできます。`NyRouteGuard` を継承し、一般的なシナリオ用のファクトリコンストラクタを提供します。

### 常にパスするガード

``` dart
final guard = NyMockRouteGuard.pass();
```

### リダイレクトするガード

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// 追加データ付き
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### カスタムロジック付きガード

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // ナビゲーションを中止
  }
  return GuardResult.next; // ナビゲーションを許可
});
```

### ガード呼び出しの追跡

ガードが呼び出された後、そのステートを検査できます:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// 最後の呼び出しから RouteContext にアクセス
RouteContext? context = guard.lastContext;

// 追跡をリセット
guard.reset();
```

<div id="assertions"></div>

## アサーション

{{ config('app.name') }} はカスタムアサーション関数を提供します:

### ルートアサーション

``` dart
expectRoute('/home');           // 現在のルートをアサート
expectNotRoute('/login');       // ルートにいないことをアサート
expectRouteInHistory('/home');  // ルートが訪問済みであることをアサート
expectRouteExists('/profile');  // ルートが登録されていることをアサート
expectRoutesExist(['/home', '/profile', '/settings']);
```

### ステートアサーション

``` dart
expectBackpackContains("key");                        // キーが存在
expectBackpackContains("key", value: "expected");     // キーが値を持つ
expectBackpackNotContains("key");                     // キーが存在しない
```

### 認証アサーション

``` dart
expectAuthenticated<User>();  // ユーザーが認証済み
expectGuest();                // ユーザーが未認証
```

### 環境アサーション

``` dart
expectEnv("APP_NAME", "MyApp");  // 環境変数の値が一致
expectEnvSet("APP_KEY");          // 環境変数が設定されている
```

### モードアサーション

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API アサーション

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### ロケールアサーション

``` dart
expectLocale("en");
```

### トーストアサーション

テスト中に記録されたトースト通知をアサートします。テストの setUp で `NyToastRecorder.setup()` が必要です:

``` dart
setUp(() {
  NyToastRecorder.setup();
});

nyWidgetTest('shows success toast', (tester) async {
  await tester.pumpNyWidget(MyPage());
  // ... トーストを表示するアクションをトリガー ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** はテスト中のトースト通知を追跡します:

``` dart
// トーストを手動で記録
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// トーストが表示されたかチェック
bool shown = NyToastRecorder.wasShown(id: 'success');

// 記録されたすべてのトーストにアクセス
List<ToastRecord> toasts = NyToastRecorder.records;

// 記録されたトーストをクリア
NyToastRecorder.clear();
```

### ロックとローディングのアサーション

`NyPage`/`NyState` ウィジェットの名前付きロックとローディングステートをアサートします:

``` dart
// 名前付きロックが保持されていることをアサート
expectLocked(tester, find.byType(MyPage), 'submit');

// 名前付きロックが保持されていないことをアサート
expectNotLocked(tester, find.byType(MyPage), 'submit');

// 名前付きローディングキーがアクティブであることをアサート
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// 名前付きローディングキーがアクティブでないことをアサート
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## カスタムマッチャー

`expect()` でカスタムマッチャーを使用します:

``` dart
// 型マッチャー
expect(result, isType<User>());

// ルート名マッチャー
expect(widget, hasRouteName('/home'));

// Backpack マッチャー
expect(true, backpackHas("key", value: "expected"));

// API 呼び出しマッチャー
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## ステートテスト

ステートテストヘルパーを使用して、`NyPage` と `NyState` ウィジェットの EventBus 駆動ステート管理をテストします。

### ステート更新の発火

通常は別のウィジェットやコントローラから来るステート更新をシミュレートします:

``` dart
// UpdateState イベントを発火
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### ステートアクションの発火

ページの `whenStateAction()` で処理されるステートアクションを送信します:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// 追加データ付き
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### ステートアサーション

``` dart
// ステート更新が発火されたことをアサート
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// ステートアクションが発火されたことをアサート
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// NyPage/NyState ウィジェットの stateData をアサート
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

発火されたステート更新とアクションを追跡・検査します:

``` dart
// ステートに発火されたすべての更新を取得
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// ステートに発火されたすべてのアクションを取得
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// 追跡されたすべてのステート更新とアクションをリセット
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## デバッグ

### dump

現在のテストステート（Backpack の内容、認証ユーザー、時間、API 呼び出し、ロケール）を出力します:

``` dart
NyTest.dump();
```

### dd (Dump and Die)

テストステートを出力し、即座にテストを終了します:

``` dart
NyTest.dd();
```

### テストステートストレージ

テスト中に値を保存・取得します:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Backpack のシード

テストデータで Backpack を事前入力します:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## 使用例

### 完全なテストファイル

``` dart
import 'package:flutter_test/flutter_test.dart';
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyGroup('User Authentication', () {
    nyTest('can authenticate a user', () async {
      NyFactory.define<User>((faker) => User(
        name: faker.name(),
        email: faker.email(),
      ));

      User user = NyFactory.make<User>();
      NyTest.actingAs<User>(user);

      expectAuthenticated<User>();
    });

    nyTest('guest has no access', () async {
      expectGuest();
    });
  });

  nyGroup('API Integration', () {
    nyTest('can fetch users', () async {
      NyMockApi.setRecordCalls(true);
      NyMockApi.respond('/api/users', {
        'users': [
          {'id': 1, 'name': 'Anthony'},
          {'id': 2, 'name': 'Jane'},
        ]
      });

      // ... API 呼び出しをトリガー ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // 既知の日付でサブスクリプションロジックをテスト
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
