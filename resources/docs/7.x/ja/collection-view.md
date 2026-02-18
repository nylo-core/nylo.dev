# CollectionView

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [CollectionItem ヘルパー](#collection-item "CollectionItem ヘルパー")
- バリアント
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Pullable バリアント
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [ローディングスタイル](#loading-styles "ローディングスタイル")
- [空の状態](#empty-state "空の状態")
- [データのソートと変換](#sorting-transforming "データのソートと変換")
- [状態の更新](#updating-state "状態の更新")
- [パラメータリファレンス](#parameters "パラメータリファレンス")


<div id="introduction"></div>

## はじめに

**CollectionView** ウィジェットは、{{ config('app.name') }} プロジェクトでデータのリストを表示するための、強力で型安全なラッパーです。`ListView`、`ListView.separated`、グリッドレイアウトでの作業を簡素化し、以下の機能を組み込みでサポートしています:

- 自動ローディング状態付きの非同期データ読み込み
- プルリフレッシュとページネーション
- 位置ヘルパー付きの型安全なアイテムビルダー
- 空の状態のハンドリング
- データのソートと変換

<div id="basic-usage"></div>

## 基本的な使い方

アイテムのリストを表示するシンプルな例:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: CollectionView<String>(
      data: () => ['Item 1', 'Item 2', 'Item 3'],
      builder: (context, item) {
        return ListTile(
          title: Text(item.data),
        );
      },
    ),
  );
}
```

API からの非同期データの場合:

``` dart
CollectionView<Todo>(
  data: () async {
    return await api<ApiService>((request) =>
      request.get('https://jsonplaceholder.typicode.com/todos')
    );
  },
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.title),
      subtitle: Text(item.data.completed ? 'Done' : 'Pending'),
    );
  },
)
```

<div id="collection-item"></div>

## CollectionItem ヘルパー

`builder` コールバックは、データを便利な位置ヘルパーでラップする `CollectionItem<T>` オブジェクトを受け取ります:

``` dart
CollectionView<String>(
  data: () => ['First', 'Second', 'Third', 'Fourth'],
  builder: (context, item) {
    return Container(
      color: item.isEven ? Colors.grey[100] : Colors.white,
      child: ListTile(
        title: Text('${item.data} (index: ${item.index})'),
        subtitle: Text('Progress: ${(item.progress * 100).toInt()}%'),
      ),
    );
  },
)
```

### CollectionItem のプロパティ

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `data` | `T` | 実際のアイテムデータ |
| `index` | `int` | リスト内の現在のインデックス |
| `totalItems` | `int` | アイテムの総数 |
| `isFirst` | `bool` | 最初のアイテムの場合 true |
| `isLast` | `bool` | 最後のアイテムの場合 true |
| `isOdd` | `bool` | インデックスが奇数の場合 true |
| `isEven` | `bool` | インデックスが偶数の場合 true |
| `progress` | `double` | リスト内の進捗（0.0 から 1.0） |

### CollectionItem のメソッド

| メソッド | 説明 |
|--------|-------------|
| `isAt(int position)` | アイテムが特定の位置にあるかチェック |
| `isInRange(int start, int end)` | インデックスが範囲内にあるかチェック（両端含む） |
| `isMultipleOf(int divisor)` | インデックスが除数の倍数かチェック |

<div id="collection-view-basic"></div>

## CollectionView

デフォルトコンストラクタは標準のリストビューを作成します:

``` dart
CollectionView<Map<String, dynamic>>(
  data: () async {
    return [
      {"title": "Clean Room"},
      {"title": "Go shopping"},
      {"title": "Buy groceries"},
    ];
  },
  builder: (context, item) {
    return ListTile(title: Text(item.data['title']));
  },
  spacing: 8.0, // アイテム間にスペースを追加
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

アイテム間にセパレーターを持つリストを作成します:

``` dart
CollectionView<User>.separated(
  data: () async => await fetchUsers(),
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.name),
      subtitle: Text(item.data.email),
    );
  },
  separatorBuilder: (context, index) {
    return Divider(height: 1);
  },
)
```

<div id="collection-view-grid"></div>

## CollectionView.grid

スタッガードグリッドを使用したグリッドレイアウトを作成します:

``` dart
CollectionView<Product>.grid(
  data: () async => await fetchProducts(),
  builder: (context, item) {
    return ProductCard(product: item.data);
  },
  crossAxisCount: 2,
  mainAxisSpacing: 8.0,
  crossAxisSpacing: 8.0,
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-pullable"></div>

## CollectionView.pullable

プルリフレッシュと無限スクロールページネーション付きのリストを作成します:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iteration は 1 から始まり、読み込みごとに増加します
    return await api<ApiService>((request) =>
      request.get('/posts?page=$iteration')
    );
  },
  builder: (context, item) {
    return PostCard(post: item.data);
  },
  onRefresh: () {
    print('List was refreshed!');
  },
  headerStyle: 'WaterDropHeader', // プルインジケーターのスタイル
)
```

### ヘッダースタイル

`headerStyle` パラメータは以下を受け付けます:
- `'WaterDropHeader'`（デフォルト）- 水滴アニメーション
- `'ClassicHeader'` - クラシックなプルインジケーター
- `'MaterialClassicHeader'` - Material Design スタイル
- `'WaterDropMaterialHeader'` - Material 水滴
- `'BezierHeader'` - ベジェ曲線アニメーション

### ページネーションコールバック

| コールバック | 説明 |
|----------|-------------|
| `beforeRefresh` | リフレッシュ開始前に呼び出される |
| `onRefresh` | リフレッシュ完了時に呼び出される |
| `afterRefresh` | データ読み込み後に呼び出され、データを受け取り変換可能 |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

プルリフレッシュとセパレーター付きリストを組み合わせます:

``` dart
CollectionView<Message>.pullableSeparated(
  data: (iteration) async => await fetchMessages(page: iteration),
  builder: (context, item) {
    return MessageTile(message: item.data);
  },
  separatorBuilder: (context, index) => Divider(),
)
```

<div id="collection-view-pullable-grid"></div>

## CollectionView.pullableGrid

プルリフレッシュとグリッドレイアウトを組み合わせます:

``` dart
CollectionView<Photo>.pullableGrid(
  data: (iteration) async => await fetchPhotos(page: iteration),
  builder: (context, item) {
    return Image.network(item.data.url);
  },
  crossAxisCount: 3,
  mainAxisSpacing: 4,
  crossAxisSpacing: 4,
)
```

<div id="loading-styles"></div>

## ローディングスタイル

`loadingStyle` を使用してローディングインジケーターをカスタマイズします:

``` dart
// カスタムウィジェット付きの通常ローディング
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading items..."),
  ),
)

// スケルトナイザーローディングエフェクト
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserCard(user: item.data),
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()),
    effect: SkeletonizerEffect.shimmer,
  ),
)
```

<div id="empty-state"></div>

## 空の状態

リストが空の場合にカスタムウィジェットを表示します:

``` dart
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  empty: Center(
    child: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.inbox, size: 64, color: Colors.grey),
        SizedBox(height: 16),
        Text('No items found'),
      ],
    ),
  ),
)
```

<div id="sorting-transforming"></div>

## データのソートと変換

### ソート

表示前にアイテムをソートします:

``` dart
CollectionView<Task>(
  data: () async => await fetchTasks(),
  builder: (context, item) => TaskTile(task: item.data),
  sort: (List<Task> items) {
    items.sort((a, b) => a.dueDate.compareTo(b.dueDate));
    return items;
  },
)
```

### 変換

読み込み後にデータを変換します:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // アクティブなユーザーのみにフィルタリング
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## 状態の更新

`stateName` を指定することで、CollectionView を更新またはリセットできます:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### リストをリセット

``` dart
// データをリセットして最初から再読み込み
CollectionView.stateReset("my_todo_list");
```

### アイテムを削除

``` dart
// インデックス 2 のアイテムを削除
CollectionView.removeFromIndex("my_todo_list", 2);
```

### 汎用的な更新をトリガー

``` dart
// グローバル updateState ヘルパーを使用
updateState("my_todo_list");
```

<div id="parameters"></div>

## パラメータリファレンス

### 共通パラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `data` | `Function()` | `List<T>` または `Future<List<T>>` を返す関数 |
| `builder` | `CollectionItemBuilder<T>` | 各アイテムのビルダー関数 |
| `empty` | `Widget?` | リストが空の時に表示されるウィジェット |
| `loadingStyle` | `LoadingStyle?` | ローディングインジケーターのカスタマイズ |
| `header` | `Widget?` | リスト上部のヘッダーウィジェット |
| `stateName` | `String?` | 状態管理用の名前 |
| `sort` | `Function(List<T>)?` | アイテムのソート関数 |
| `transform` | `Function(List<T>)?` | データの変換関数 |
| `spacing` | `double?` | アイテム間のスペース |

### Pullable 固有のパラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | ページネーションデータ関数 |
| `onRefresh` | `Function()?` | リフレッシュ完了時のコールバック |
| `beforeRefresh` | `Function()?` | リフレッシュ前のコールバック |
| `afterRefresh` | `Function(dynamic)?` | データ読み込み後のコールバック |
| `headerStyle` | `String?` | プルインジケーターのスタイル |
| `footerLoadingIcon` | `Widget?` | ページネーション用のカスタムローディングインジケーター |

### Grid 固有のパラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `crossAxisCount` | `int` | 列数（デフォルト: 2） |
| `mainAxisSpacing` | `double` | アイテム間の縦方向のスペース |
| `crossAxisSpacing` | `double` | アイテム間の横方向のスペース |

### ListView パラメータ

標準の `ListView` パラメータもすべてサポートされています: `scrollDirection`、`reverse`、`controller`、`physics`、`shrinkWrap`、`padding`、`cacheExtent` など。
