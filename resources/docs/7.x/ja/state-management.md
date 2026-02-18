# 状態管理

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [状態管理を使うべき時](#when-to-use-state-management "状態管理を使うべき時")
- [ライフサイクル](#lifecycle "ライフサイクル")
- [State Actions](#state-actions "State Actions")
  - [NyState - State Actions](#state-actions-nystate "NyState - State Actions")
  - [NyPage - State Actions](#state-actions-nypage "NyPage - State Actions")
- [状態の更新](#updating-a-state "状態の更新")
- [最初のウィジェットを構築する](#building-your-first-widget "最初のウィジェットを構築する")

<div id="introduction"></div>

## はじめに

状態管理を使用すると、ページ全体を再構築せずに UI の特定の部分を更新できます。{{ config('app.name') }} v7 では、アプリ全体でウィジェット同士が通信し、互いに更新し合うウィジェットを構築できます。

{{ config('app.name') }} は状態管理のために 2 つのクラスを提供します:
- **`NyState`** -- 再利用可能なウィジェットの構築用（カートバッジ、通知カウンター、ステータスインジケーターなど）
- **`NyPage`** -- アプリケーションのページ構築用（`NyState` を拡張し、ページ固有の機能を追加）

以下の場合に状態管理を使用します:
- アプリの別の部分からウィジェットを更新する必要がある
- ウィジェットを共有データと同期させ続ける必要がある
- UI の一部だけが変更される時にページ全体の再構築を避ける必要がある


### まず状態管理を理解しましょう

Flutter のすべてはウィジェットです。ウィジェットは UI の小さな部品で、組み合わせて完全なアプリを作ることができます。

複雑なページを構築し始めると、ウィジェットの状態を管理する必要が出てきます。つまり、何か（例: データ）が変更された時に、ページ全体を再構築せずにそのウィジェットを更新できるということです。

これが重要な理由はたくさんありますが、主な理由はパフォーマンスです。常に変化するウィジェットがある場合、変更のたびにページ全体を再構築したくありません。

これが状態管理の出番です。アプリケーション内のウィジェットの状態を管理することができます。


<div id="when-to-use-state-management"></div>

### 状態管理を使うべき時

ページ全体を再構築せずにウィジェットを更新する必要がある場合に状態管理を使用すべきです。

例えば、EC アプリを作成したとしましょう。ユーザーのカート内のアイテム合計数を表示するウィジェットを構築しました。
このウィジェットを `Cart()` と呼びましょう。

Nylo での状態管理された `Cart` ウィジェットは次のようになります:

**ステップ 1:** 静的な状態名を持つウィジェットを定義

``` dart
/// Cart ウィジェット
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // このウィジェットの状態の一意な識別子

  @override
  _CartState createState() => _CartState();
}
```

**ステップ 2:** `NyState` を継承する状態クラスを作成

``` dart
/// Cart ウィジェットの状態クラス
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // 状態名を登録
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // 初期データを読み込み
  };

  @override
  void stateUpdated(data) {
    reboot(); // 状態更新時にウィジェットを再読み込み
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**ステップ 3:** カートの読み取りと更新用のヘルパー関数を作成

``` dart
/// ストレージからカートの値を取得
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// カートの値を設定してウィジェットに通知
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // これによりウィジェットの stateUpdated() がトリガーされます
}
```

これを分解してみましょう。

1. `Cart` ウィジェットは `StatefulWidget` です。

2. `_CartState` は `NyState<Cart>` を継承します。

3. `state` の名前を定義する必要があります。これは状態を識別するために使用されます。

4. `boot()` メソッドはウィジェットが最初に読み込まれた時に呼び出されます。

5. `stateUpdate()` メソッドは状態が更新された時の動作を処理します。

この例を {{ config('app.name') }} プロジェクトで試したい場合は、`Cart` という新しいウィジェットを作成してください。

``` bash
metro make:state_managed_widget cart
```

そして上記の例をコピーしてプロジェクトで試すことができます。

カートを更新するには、以下を呼び出します。

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## ライフサイクル

`NyState` ウィジェットのライフサイクルは以下の通りです:

1. `init()` - このメソッドは状態が初期化される時に呼び出されます。

2. `stateUpdated(data)` - このメソッドは状態が更新された時に呼び出されます。

    `updateState(MyStateName.state, data: "The Data")` を呼び出すと、**stateUpdated(data)** がトリガーされます。

状態が最初に初期化されると、状態の管理方法を実装する必要があります。


<div id="state-actions"></div>

## State Actions

State Actions を使用すると、アプリのどこからでもウィジェット上の特定のメソッドをトリガーできます。ウィジェットに送信できる名前付きコマンドと考えてください。

以下の場合に State Actions を使用します:
- ウィジェット上で特定の動作をトリガーする必要がある（単なるリフレッシュではなく）
- ウィジェットにデータを渡して特定の方法で応答させる必要がある
- 複数の場所から呼び出せる再利用可能なウィジェット動作を作成する必要がある

``` dart
// ウィジェットにアクションを送信
stateAction('hello_world_in_widget', state: MyWidget.state);

// データ付きの別の例
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

ウィジェット内で、処理したいアクションを定義できます。

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // データ付きの例
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

そして、アプリケーションのどこからでも `stateAction` メソッドを呼び出せます。

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// 'Hello world' と出力

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

`init` ゲッター内で `whenStateAction` メソッドを使用して State Actions を定義することもできます。

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // バッジカウントをリセット
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - State Actions

まず、StatefulWidget を作成します。

``` bash
metro make:stateful_widget [widget_name]
```
例: metro make:stateful_widget user_avatar

これにより `lib/resources/widgets/` ディレクトリに新しいウィジェットが作成されます。

そのファイルを開くと、State Actions を定義できます。

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // 例
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // 例
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

最後に、アプリケーションのどこからでもアクションを送信できます。

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// 'Hello from the widget' と出力

stateAction('reset_data', state: MyWidget.state);
// ウィジェット内のデータをリセット

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// メッセージ付きの成功トーストを表示
```


<div id="state-actions-nypage"></div>

### NyPage - State Actions

ページも State Actions を受け取ることができます。これはウィジェットや他のページからページレベルの動作をトリガーしたい場合に便利です。

まず、状態管理されたページを作成します。

``` bash
metro make:page my_page
```

これにより `lib/resources/pages/` ディレクトリに `MyPage` という新しい状態管理されたページが作成されます。

そのファイルを開くと、State Actions を定義できます。

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => true;

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // 例
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

最後に、アプリケーションのどこからでもアクションを送信できます。

``` dart
stateAction('test_page_action', state: MyPage.state);
// 'Hello from the page' と出力

stateAction('reset_data', state: MyPage.state);
// ページ内のデータをリセット

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// メッセージ付きの成功トーストを表示
```

`whenStateAction` メソッドを使用して State Actions を定義することもできます。

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // バッジカウントをリセット
      _count = 0;
    }
  });
}
```

そして、アプリケーションのどこからでもアクションを送信できます。

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## 状態の更新

`updateState()` メソッドを呼び出して状態を更新できます。

``` dart
updateState(MyStateName.state);

// データ付き
updateState(MyStateName.state, data: "The Data");
```

これはアプリケーションのどこからでも呼び出すことができます。

**参照:** 状態管理ヘルパーとライフサイクルメソッドの詳細については [NyState](/docs/{{ $version }}/ny-state) を参照してください。


<div id="building-your-first-widget"></div>

## 最初のウィジェットを構築する

Nylo プロジェクトで、以下のコマンドを実行して新しいウィジェットを作成します。

``` bash
metro make:stateful_widget todo_list
```

これにより `TodoList` という新しい `NyState` ウィジェットが作成されます。

> 注意: 新しいウィジェットは `lib/resources/widgets/` ディレクトリに作成されます。
