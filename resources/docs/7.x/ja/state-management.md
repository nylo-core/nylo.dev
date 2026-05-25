# 状態管理ウィジェット

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [パターンの選択](#choose-your-pattern "パターンの選択")
- [State Actions](#state-actions "State Actions")
  - [ハンドラーの定義](#defining-handlers "ハンドラーの定義")
  - [アクションのトリガー](#triggering-actions "アクションのトリガー")
  - [データあり・なしのハンドラー](#handlers-with-and-without-data "データあり・なしのハンドラー")
  - [StateActionsインスタンスの使用](#using-a-state-actions-instance "StateActionsインスタンスの使用")
- [パターン: 状態管理ページ (NyPage)](#pattern-ny-page "パターン: 状態管理ページ")
- [パターン: ステートフルウィジェット (NyState)](#pattern-ny-state "パターン: ステートフルウィジェット")
- [パターン: 状態管理ウィジェット (NyStateManaged)](#pattern-ny-state-managed "パターン: 状態管理ウィジェット")
  - [応用: 複数の独立したインスタンス](#advanced-multiple-isolated-instances "応用: 複数の独立したインスタンス")
- [ライフサイクルリファレンス](#lifecycle "ライフサイクルリファレンス")

<div id="introduction"></div>

## はじめに

状態管理ウィジェットを使用すると、ページ全体を再構築せずにアプリのどこからでも UI の特定の部分を更新できます。{{ config('app.name') }} v7 では、対象のウィジェットまたはページに対して名前付きの **state actions** をトリガーし、対応するハンドラーが実行されます。

考え方はシンプルです:

- 各状態管理ウィジェットまたはページは一意の **状態キー**（文字列）を持つ
- 処理できる**名前付きアクション**のマップを定義する
- アプリのどこからでも以下を呼び出せる
```
stateAction("action_name", state: TargetWidget.state)
``` 

状態管理 UI を構築するためのパターンは 3 つあります。すべてが同じ `stateAction` の仕組みを共有しており、違いは管理する UI の種類だけです。


<div id="choose-your-pattern"></div>

## パターンの選択

| やりたいこと | 使用するもの | スキャフォールドコマンド |
|---|---|---|
| ページ全体を状態管理する | `NyPage` | `metro make:page my_page` |
| 単一インスタンスのウィジェットを状態管理する | `NyState` | `metro make:stateful_widget my_widget` |
| 複数の独立したインスタンスを持つウィジェットを状態管理する | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

すべてのパターンが使用する API を説明している [State Actions](#state-actions) セクションを先にお読みください。その後、ご自身のケースに合ったパターンに進んでください。


<div id="state-actions"></div>

## State Actions

State Actions は、ウィジェットまたはページが処理方法を知っている名前付きコマンドです。アクション名からハンドラー関数へのマップを定義し、アプリのどこからでも名前で呼び出せます。

以下の場合に State Actions を使用します:
- ウィジェットまたはページで特定の動作をトリガーする必要がある（単なる汎用的なリフレッシュではなく）
- ウィジェットにデータを渡して特定の方法で応答させる必要がある
- 複数の呼び出し元から呼び出せる再利用可能なウィジェット動作を構築する必要がある

<div id="defining-handlers"></div>

### ハンドラーの定義

ハンドラーは `NyState` または `NyPage` クラスの `stateActions` ゲッターに記述します。マップのキーはアクション名で、値はそのアクションがトリガーされたときに実行する関数です。

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

ウィジェットを再構築したい場合は、ハンドラー自身が `setState` を呼び出す責任を持ちます。

上記の `apply_discount` ハンドラーは `code` 引数を取ります — ハンドラーが以下を通じて渡されるペイロードを必要とする場合は、単一の位置パラメータを宣言してください
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

アクションがペイロードを持たない場合は、引数なしの形式 `()` を使用してください。


<div id="triggering-actions"></div>

### アクションのトリガー

トップレベルの `stateAction` 関数を使用して、別のウィジェット、コントローラー、イベントハンドラー、API コールバックなど、どこからでもアクションを発火できます。

``` dart
// データなしでアクションを発火
stateAction("clear_cart", state: Cart.state);

// データありでアクションを発火
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

`state:` 引数は対象の **状態キー** です:
- ウィジェットの場合 — `MyWidget.state`（文字列）
- ページの場合 — `MyPage.path`（ルート）


<div id="handlers-with-and-without-data"></div>

### データあり・なしのハンドラー

ハンドラーは同期または非同期にでき、`data` 引数あり・なしで定義できます:

``` dart
@override
Map<String, Function> get stateActions => {
  // データなし — ハンドラーをそのまま実行
  "reset": () {
    _value = null;
    setState(() {});
  },

  // データあり — `data:` 引数で渡されたものを受け取る
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // 非同期もサポート — フレームワークがハンドラーを await する
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

`stateAction` に `data:` を渡しても、ハンドラーが引数を取らない場合、データは単純に無視されます。


<div id="using-a-state-actions-instance"></div>

### StateActionsインスタンスの使用

ウィジェットが型付きの `StateActions` インスタンス（多くの場合 `stateActions(stateName)` 静的メソッド経由）を公開している場合、フリー関数の代わりにインスタンスに対して直接 `.action(...)` を呼び出せます。これは同じ対象に複数のアクションを発火する際にすっきりします:

``` dart
// フリー関数を使用
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// StateActions インスタンスを使用 — 同等だが繰り返しが少ない
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

いくつかの組み込みウィジェット（`InputField`、`CollectionView`、`LanguageSwitcher`、`NyForm*` ファミリー）は、`.clear()`、`.setValue(...)`、`.refresh()` などの名前付きメソッドを持つ型付き `StateActions` クラスを同梱しています — 利用可能なものについてはウィジェットのドキュメントを確認してください。


<div id="pattern-ny-page"></div>

## パターン: 状態管理ページ (NyPage)

イベント発火時にページを更新したり、子ウィジェットからフォーム状態をクリアしたりするなど、アプリの別の場所からページ全体に動作をトリガーしたい場合に使用します。

**ステップ 1:** ページをスキャフォールドします。

``` bash
metro make:page my_page
```

これにより `lib/resources/pages/` に `NyPage` が生成されます:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**ステップ 2:** `stateManaged` を `true` に切り替えます。

デフォルトでは、ページは状態イベントをサブスクライブしません — これにより、必要のないページへの不要なリスナーを回避できます。ページで state actions を有効にするには、ゲッターを変更してください:

``` dart
@override
bool get stateManaged => true;
```

**ステップ 3:** `stateActions` マップを追加します。

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**ステップ 4:** ページの `path` を使用してどこからでもアクションをトリガーします。

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> ページは `MyPage.path` をキーとし、ウィジェットは `MyWidget.state` をキーとします。これが呼び出し側での唯一の違いです。


<div id="pattern-ny-state"></div>

## パターン: ステートフルウィジェット (NyState)

ページごとに単一のインスタンスとして存在する再利用可能なウィジェット — プロフィールカード、ヘッダーバー、ステータスインジケーターなど — に使用します。ウィジェットを同時に 1 つのインスタンスしかレンダリングしない場合、これが正しいパターンです。

**ステップ 1:** ステートフルウィジェットをスキャフォールドします。

``` bash
metro make:stateful_widget profile_card
```

これにより `lib/resources/widgets/` に以下が生成されます:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

スキャフォールドにより動作する `NyState` ウィジェットが生成されますが、**まだ状態管理されていません**。state actions に応答させるには、4 つのことを追加する必要があります:

1. ウィジェットクラスに `state` キー — アプリの他の部分がターゲットとする一意の文字列
2. 状態キーを受け取り状態クラスに転送するコンストラクタパラメータ
3. `stateName` を割り当てる状態クラスのコンストラクタ
4. ハンドラーを定義する `stateActions` マップ

**ステップ 2:** スキャフォールドを状態管理ウィジェットに変換します。

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ アプリのどこからでもこれを呼び出して、以下のハンドラーをトリガーできます
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

変更点:
- `static String get state => "profile_card";` で公開状態キーを定義
- `createState() => _ProfileCardState(state)` でキーを状態クラスに渡す
- `_ProfileCardState(String? state) { this.stateName = state; }` でその状態インスタンスをキーの下に登録し、フレームワークがどのウィジェットにアクションを届けるかを知る
- `stateActions` で名前付きハンドラーを宣言する

**ステップ 3:** どこからでもトリガーします。

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

データあり・なしで必要なだけハンドラーを追加できます:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## パターン: 状態管理ウィジェット (NyStateManaged)

画面上に**同じウィジェットの複数の独立したインスタンス**が必要な場合に使用します — たとえば、ヘッダーのカートバッジとサイドバーのカートバッジがそれぞれ独立して更新される必要がある場合などです。

`NyStateManaged` は各インスタンスを個別にアドレス指定できるように `id` パラメータを追加します。ウィジェットのインスタンスを 1 つしかレンダリングしない場合は、よりシンプルな `NyState` を優先してください。

**ステップ 1:** 状態管理ウィジェットをスキャフォールドします。

``` bash
metro make:state_managed_widget cart
```

これにより `lib/resources/widgets/` に以下が生成されます:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.id})
      : super(baseState: state, child: () => _CartState());

  static const String state = "cart";

  static action(String action, {dynamic data, String? id}) =>
      stateAction(action, data: data, state: state, id: id);
}

class _CartState extends NyState<Cart> {
  @override
  get init => () {
    // 初期化ロジックをここに記述
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // アプリのどこからでもアクションを呼び出す
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**ステップ 2:** 状態を実装 — `init` でデータを読み込み、ハンドラーを定義し、レンダリングします。

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**ステップ 3:** 生成された静的 `action()` ヘルパーを使用してアクションをトリガーします。

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

これは `stateAction("reload_cart", state: Cart.state)` と同等です — 静的ヘルパーはボイラープレートを省くだけです。


<div id="advanced-multiple-isolated-instances"></div>

### 応用: 複数の独立したインスタンス

`NyStateManaged` が存在する理由は、同じウィジェットの複数の独立したインスタンスをサポートするためです。各インスタンスは独自の `id` を取得し、名前空間付きの状態キーを生成します。

異なる id で 2 つのカートをレンダリングします:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

これで、どちらかを独立して更新できます:

``` dart
// ヘッダーのカートのみをリロード
Cart.action("reload_cart", id: "header");

// サイドバーのカートのみをリロード
Cart.action("reload_cart", id: "sidebar");

// id なし — デフォルトの無名インスタンスをターゲット
Cart.action("reload_cart");
```

`NyStateManaged` が名前空間を処理します: `Cart(id: "header")` はキー `"cart_header"` の下に登録され、`Cart.action(..., id: "header")` はその正確なキーをターゲットとします。


<div id="lifecycle"></div>

## ライフサイクルリファレンス

状態管理ウィジェットとページは 2 つの主要なライフサイクルフックを共有します:

1. **`init()`** — 状態が最初に作成されたときに一度呼び出されます。初期データの読み込みに使用します。

2. **`stateUpdated(data)`** — この状態に対して state action が発火するたびに呼び出されます。`data` 引数はフルペイロード（アクション名とアクションのデータを含む）です。*すべての* state action に反応する必要がある場合にオーバーライドしてください — ほとんどの場合、`stateActions` でハンドラーを定義する方が適切です。

**参照:** 状態ヘルパーとライフサイクルメソッドの全セットについては [NyState](/docs/{{ $version }}/ny-state) を参照してください。
