# Pullable

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [构造函数](#constructors "构造函数")
- [PullableConfig](#pullable-config "PullableConfig")
- [头部样式](#header-styles "头部样式")
- [上拉加载更多](#pull-up "上拉加载更多")
- [自定义头部和底部](#custom-headers "自定义头部和底部")
- [控制器](#controller "控制器")
- [扩展方法](#extension-method "扩展方法")
- [CollectionView 集成](#collection-view "CollectionView 集成")
- [示例](#examples "示例")

<div id="introduction"></div>

## 简介

**Pullable** 组件为任何可滚动内容添加下拉刷新和加载更多功能。它使用手势驱动的刷新和分页行为包裹你的子组件，并支持多种头部动画样式。

基于 `pull_to_refresh_flutter3` 包构建，Pullable 提供了简洁的 API，并为常见配置提供了命名构造函数。

``` dart
Pullable(
  onRefresh: () async {
    // Fetch fresh data
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## 基本用法

用 `Pullable` 包裹任何可滚动组件：

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

当用户在列表上下拉时，`onRefresh` 回调将被触发。刷新指示器在回调完成后自动结束。

<div id="constructors"></div>

## 构造函数

`Pullable` 为常见配置提供了命名构造函数：

| 构造函数 | 头部样式 | 描述 |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | 默认构造函数 |
| `Pullable.classicHeader()` | Classic | 经典下拉刷新样式 |
| `Pullable.waterDropHeader()` | Water Drop | 水滴动画 |
| `Pullable.materialClassicHeader()` | Material Classic | Material Design 经典样式 |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Material 水滴样式 |
| `Pullable.bezierHeader()` | Bezier | 贝塞尔曲线动画 |
| `Pullable.noBounce()` | 可配置 | 使用 `ClampingScrollPhysics` 减少弹跳效果 |
| `Pullable.custom()` | 自定义组件 | 使用你自己的头部/底部组件 |
| `Pullable.builder()` | 可配置 | 完全控制 `PullableConfig` |

### 示例

``` dart
// Classic header
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material header
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// No bounce effect
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// Custom header widget
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

如需精细控制，请将 `PullableConfig` 与 `Pullable.builder()` 构造函数配合使用：

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

### 所有配置选项

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | 启用下拉刷新 |
| `enablePullUp` | `bool` | `false` | 启用上拉加载更多 |
| `physics` | `ScrollPhysics?` | null | 自定义滚动物理效果 |
| `onRefresh` | `Future<void> Function()?` | null | 刷新回调 |
| `onLoading` | `Future<void> Function()?` | null | 加载更多回调 |
| `headerType` | `PullableHeaderType` | `waterDrop` | 头部动画样式 |
| `customHeader` | `Widget?` | null | 自定义头部组件 |
| `customFooter` | `Widget?` | null | 自定义底部组件 |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | 刷新完成前的延迟 |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | 加载完成前的延迟 |
| `enableOverScroll` | `bool` | `true` | 允许过度滚动效果 |
| `cacheExtent` | `double?` | null | 滚动缓存范围 |
| `semanticChildCount` | `int?` | null | 语义子项数量 |
| `dragStartBehavior` | `DragStartBehavior` | `start` | 拖拽手势的开始方式 |

<div id="header-styles"></div>

## 头部样式

从五种内置头部动画中选择：

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

通过构造函数或配置设置样式：

``` dart
// Via named constructor
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Via config
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## 上拉加载更多

启用上拉加载实现分页：

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // Reset to page 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // Load next page
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

## 自定义头部和底部

提供你自己的头部和底部组件：

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

## 控制器

使用 `RefreshController` 进行编程式控制：

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Trigger refresh programmatically
_controller.triggerRefresh();

// Trigger loading programmatically
_controller.triggerLoading();

// Check state
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### RefreshController 的扩展方法

| 方法/属性 | 返回类型 | 描述 |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | 手动触发刷新 |
| `triggerLoading()` | `void` | 手动触发加载更多 |
| `isRefreshing` | `bool` | 是否正在刷新 |
| `isLoading` | `bool` | 是否正在加载 |

<div id="extension-method"></div>

## 扩展方法

任何组件都可以使用 `.pullable()` 扩展方法来包裹下拉刷新功能：

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

使用自定义配置：

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

## CollectionView 集成

`CollectionView` 提供内置分页的 pullable 变体：

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

### Pullable 专用参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | 分页数据回调（iteration 从 1 开始） |
| `onRefresh` | `Function()?` | 刷新后的回调 |
| `beforeRefresh` | `Function()?` | 刷新开始前的钩子 |
| `afterRefresh` | `Function(dynamic)?` | 刷新后带数据的钩子 |
| `headerStyle` | `String?` | 头部类型名称（例如 `'WaterDropHeader'`、`'ClassicHeader'`） |
| `footerLoadingIcon` | `Widget?` | 底部的自定义加载指示器 |

<div id="examples"></div>

## 示例

### 带刷新的分页列表

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

### 使用扩展方法的简单刷新

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
