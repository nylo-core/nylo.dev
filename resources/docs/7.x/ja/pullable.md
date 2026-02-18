# Pullable

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [コンストラクタ](#constructors "コンストラクタ")
- [PullableConfig](#pullable-config "PullableConfig")
- [ヘッダースタイル](#header-styles "ヘッダースタイル")
- [プルアップで更に読み込み](#pull-up "プルアップで更に読み込み")
- [カスタムヘッダーとフッター](#custom-headers "カスタムヘッダーとフッター")
- [Controller](#controller "Controller")
- [エクステンションメソッド](#extension-method "エクステンションメソッド")
- [CollectionView との統合](#collection-view "CollectionView との統合")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

**Pullable** ウィジェットは、任意のスクロール可能なコンテンツにプルリフレッシュとロードモア機能を追加します。子ウィジェットをジェスチャー駆動のリフレッシュとページネーション動作でラップし、複数のヘッダーアニメーションスタイルをサポートします。

`pull_to_refresh_flutter3` パッケージの上に構築された Pullable は、一般的な設定のための名前付きコンストラクタを備えたクリーンな API を提供します。

``` dart
Pullable(
  onRefresh: () async {
    // 最新のデータを取得
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## 基本的な使い方

任意のスクロール可能なウィジェットを `Pullable` でラップします:

``` dart
Pullable(
  onRefresh: () async {
    await loadLatestPosts();
  },
  child: ListView.builder(
    itemCount: posts.length,
    itemBuilder: (context, index) => PostCard(post: posts[index]),
  ),
)
```

ユーザーがリストを下に引くと、`onRefresh` コールバックが発火します。リフレッシュインジケーターはコールバックの完了時に自動的に終了します。

<div id="constructors"></div>

## コンストラクタ

`Pullable` は一般的な設定のための名前付きコンストラクタを提供します:

| コンストラクタ | ヘッダースタイル | 説明 |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | デフォルトコンストラクタ |
| `Pullable.classicHeader()` | Classic | クラシックなプルリフレッシュスタイル |
| `Pullable.waterDropHeader()` | Water Drop | 水滴アニメーション |
| `Pullable.materialClassicHeader()` | Material Classic | Material Design クラシックスタイル |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Material 水滴スタイル |
| `Pullable.bezierHeader()` | Bezier | ベジェ曲線アニメーション |
| `Pullable.noBounce()` | 設定可能 | `ClampingScrollPhysics` でバウンスを軽減 |
| `Pullable.custom()` | カスタムウィジェット | 独自のヘッダー/フッターウィジェットを使用 |
| `Pullable.builder()` | 設定可能 | 完全な `PullableConfig` 制御 |

### 例

``` dart
// クラシックヘッダー
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material ヘッダー
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// バウンスエフェクトなし
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// カスタムヘッダーウィジェット
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

きめ細かい制御には、`Pullable.builder()` コンストラクタで `PullableConfig` を使用します:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    headerType: PullableHeaderType.materialClassic,
    onRefresh: () async => await refreshData(),
    onLoading: () async => await loadMoreData(),
    refreshCompleteDelay: Duration(milliseconds: 500),
    loadCompleteDelay: Duration(milliseconds: 300),
    physics: BouncingScrollPhysics(),
  ),
  child: myListView,
)
```

### すべての設定オプション

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | プルダウンリフレッシュを有効化 |
| `enablePullUp` | `bool` | `false` | プルアップロードモアを有効化 |
| `physics` | `ScrollPhysics?` | null | カスタムスクロール物理 |
| `onRefresh` | `Future<void> Function()?` | null | リフレッシュコールバック |
| `onLoading` | `Future<void> Function()?` | null | ロードモアコールバック |
| `headerType` | `PullableHeaderType` | `waterDrop` | ヘッダーアニメーションスタイル |
| `customHeader` | `Widget?` | null | カスタムヘッダーウィジェット |
| `customFooter` | `Widget?` | null | カスタムフッターウィジェット |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | リフレッシュ完了前の遅延 |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | ロード完了前の遅延 |
| `enableOverScroll` | `bool` | `true` | オーバースクロールエフェクトを許可 |
| `cacheExtent` | `double?` | null | スクロールキャッシュ範囲 |
| `semanticChildCount` | `int?` | null | セマンティック子要素数 |
| `dragStartBehavior` | `DragStartBehavior` | `start` | ドラッグジェスチャーの開始方法 |

<div id="header-styles"></div>

## ヘッダースタイル

5 つの組み込みヘッダーアニメーションから選択します:

``` dart
enum PullableHeaderType {
  classic,           // クラシックプルインジケーター
  waterDrop,         // 水滴アニメーション（デフォルト）
  materialClassic,   // Material Design クラシック
  waterDropMaterial,  // Material 水滴
  bezier,            // ベジェ曲線アニメーション
}
```

コンストラクタまたは config でスタイルを設定します:

``` dart
// 名前付きコンストラクタ経由
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// config 経由
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## プルアップで更に読み込み

プルアップローディングでページネーションを有効にします:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // ページ 1 にリセット
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // 次のページを読み込み
      page++;
      List<Item> more = await fetchItems(page: page);
      items.addAll(more);
      setState(() {});
    },
  ),
  child: ListView.builder(
    itemCount: items.length,
    itemBuilder: (context, index) => ItemTile(item: items[index]),
  ),
)
```

<div id="custom-headers"></div>

## カスタムヘッダーとフッター

独自のヘッダーとフッターウィジェットを提供します:

``` dart
Pullable.custom(
  customHeader: Container(
    height: 60,
    alignment: Alignment.center,
    child: CircularProgressIndicator(),
  ),
  customFooter: Container(
    height: 40,
    alignment: Alignment.center,
    child: Text("Loading more..."),
  ),
  enablePullUp: true,
  onRefresh: () async => await refreshData(),
  onLoading: () async => await loadMore(),
  child: myListView,
)
```

<div id="controller"></div>

## Controller

`RefreshController` を使用してプログラムで制御します:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// プログラムでリフレッシュをトリガー
_controller.triggerRefresh();

// プログラムでローディングをトリガー
_controller.triggerLoading();

// 状態を確認
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### RefreshController のエクステンションメソッド

| メソッド/ゲッター | 戻り値の型 | 説明 |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | リフレッシュを手動でトリガー |
| `triggerLoading()` | `void` | ロードモアを手動でトリガー |
| `isRefreshing` | `bool` | リフレッシュがアクティブかどうか |
| `isLoading` | `bool` | ローディングがアクティブかどうか |

<div id="extension-method"></div>

## エクステンションメソッド

`.pullable()` エクステンションを使用して任意のウィジェットをプルリフレッシュでラップできます:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

カスタム config 付き:

``` dart
myListView.pullable(
  onRefresh: () async => await refreshData(),
  pullableConfig: PullableConfig(
    headerType: PullableHeaderType.classic,
    enablePullUp: true,
    onLoading: () async => await loadMore(),
  ),
)
```

<div id="collection-view"></div>

## CollectionView との統合

`CollectionView` は組み込みページネーション付きの Pullable バリアントを提供します:

### CollectionView.pullable

``` dart
CollectionView<User>.pullable(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  onRefresh: () => print('Refreshed!'),
  headerStyle: 'WaterDropHeader',
)
```

### CollectionView.pullableSeparated

``` dart
CollectionView<User>.pullableSeparated(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  separatorBuilder: (context, index) => Divider(),
)
```

### CollectionView.pullableGrid

``` dart
CollectionView<Product>.pullableGrid(
  data: (iteration) async => api.getProducts(page: iteration),
  builder: (context, item) => ProductCard(product: item.data),
  crossAxisCount: 2,
  mainAxisSpacing: 8,
  crossAxisSpacing: 8,
)
```

### Pullable 固有のパラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | ページネーションデータコールバック（iteration は 1 から開始） |
| `onRefresh` | `Function()?` | リフレッシュ後のコールバック |
| `beforeRefresh` | `Function()?` | リフレッシュ開始前のフック |
| `afterRefresh` | `Function(dynamic)?` | データ付きリフレッシュ後のフック |
| `headerStyle` | `String?` | ヘッダータイプ名（例: `'WaterDropHeader'`、`'ClassicHeader'`） |
| `footerLoadingIcon` | `Widget?` | フッター用カスタムローディングインジケーター |

<div id="examples"></div>

## 使用例

### リフレッシュ付きページネーションリスト

``` dart
class _PostListState extends NyState<PostListPage> {
  List<Post> posts = [];
  int page = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Pullable.builder(
        config: PullableConfig(
          enablePullDown: true,
          enablePullUp: true,
          headerType: PullableHeaderType.materialClassic,
          onRefresh: () async {
            page = 1;
            posts = await api<PostApiService>((request) => request.getPosts(page: page));
            setState(() {});
          },
          onLoading: () async {
            page++;
            List<Post> more = await api<PostApiService>((request) => request.getPosts(page: page));
            posts.addAll(more);
            setState(() {});
          },
        ),
        child: ListView.builder(
          itemCount: posts.length,
          itemBuilder: (context, index) => PostCard(post: posts[index]),
        ),
      ),
    );
  }
}
```

### エクステンションによるシンプルなリフレッシュ

``` dart
ListView(
  children: notifications
    .map((n) => ListTile(
      title: Text(n.title),
      subtitle: Text(n.body),
    ))
    .toList(),
).pullable(
  onRefresh: () async {
    notifications = await fetchNotifications();
    setState(() {});
  },
)
```
