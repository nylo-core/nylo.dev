# Pullable

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Constructors](#constructors "Constructors")
- [PullableConfig](#pullable-config "PullableConfig")
- [Header Styles](#header-styles "Header Styles")
- [Pull-Up to Load More](#pull-up "Pull-Up to Load More")
- [Custom Headers and Footers](#custom-headers "Custom Headers and Footers")
- [Controller](#controller "Controller")
- [Extension Method](#extension-method "Extension Method")
- [CollectionView Integration](#collection-view "CollectionView Integration")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

The **Pullable** widget adds pull-to-refresh and load-more functionality to any scrollable content. It wraps your child widget with gesture-driven refresh and pagination behavior, supporting multiple header animation styles.

Built on top of the `pull_to_refresh_flutter3` package, Pullable provides a clean API with named constructors for common configurations.

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

## Basic Usage

Wrap any scrollable widget with `Pullable`:

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

When the user pulls down on the list, the `onRefresh` callback fires. The refresh indicator automatically completes when the callback finishes.

<div id="constructors"></div>

## Constructors

`Pullable` provides named constructors for common configurations:

| Constructor | Header Style | Description |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Default constructor |
| `Pullable.classicHeader()` | Classic | Classic pull-to-refresh style |
| `Pullable.waterDropHeader()` | Water Drop | Water drop animation |
| `Pullable.materialClassicHeader()` | Material Classic | Material Design classic style |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Material water drop style |
| `Pullable.bezierHeader()` | Bezier | Bezier curve animation |
| `Pullable.noBounce()` | Configurable | Reduced bounce with `ClampingScrollPhysics` |
| `Pullable.custom()` | Custom widget | Use your own header/footer widgets |
| `Pullable.builder()` | Configurable | Full `PullableConfig` control |

### Examples

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

For fine-grained control, use `PullableConfig` with the `Pullable.builder()` constructor:

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

### All Configuration Options

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Enable pull-down-to-refresh |
| `enablePullUp` | `bool` | `false` | Enable pull-up-to-load-more |
| `physics` | `ScrollPhysics?` | null | Custom scroll physics |
| `onRefresh` | `Future<void> Function()?` | null | Refresh callback |
| `onLoading` | `Future<void> Function()?` | null | Load-more callback |
| `headerType` | `PullableHeaderType` | `waterDrop` | Header animation style |
| `customHeader` | `Widget?` | null | Custom header widget |
| `customFooter` | `Widget?` | null | Custom footer widget |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Delay before refresh completes |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Delay before load completes |
| `enableOverScroll` | `bool` | `true` | Allow over-scroll effect |
| `cacheExtent` | `double?` | null | Scroll cache extent |
| `semanticChildCount` | `int?` | null | Semantic children count |
| `dragStartBehavior` | `DragStartBehavior` | `start` | How drag gestures begin |

<div id="header-styles"></div>

## Header Styles

Choose from five built-in header animations:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Set the style via the constructor or config:

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

## Pull-Up to Load More

Enable pagination with pull-up loading:

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

## Custom Headers and Footers

Provide your own header and footer widgets:

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

Use a `RefreshController` for programmatic control:

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

### Extension Methods on RefreshController

| Method/Getter | Return Type | Description |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Manually trigger a refresh |
| `triggerLoading()` | `void` | Manually trigger load-more |
| `isRefreshing` | `bool` | Whether refresh is active |
| `isLoading` | `bool` | Whether loading is active |

<div id="extension-method"></div>

## Extension Method

Any widget can be wrapped with pull-to-refresh using the `.pullable()` extension:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

With custom config:

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

## CollectionView Integration

`CollectionView` provides pullable variants with built-in pagination:

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

### Pullable-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Paginated data callback (iteration starts at 1) |
| `onRefresh` | `Function()?` | Callback after refresh |
| `beforeRefresh` | `Function()?` | Hook before refresh begins |
| `afterRefresh` | `Function(dynamic)?` | Hook after refresh with data |
| `headerStyle` | `String?` | Header type name (e.g., `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Custom loading indicator for footer |

<div id="examples"></div>

## Examples

### Paginated List with Refresh

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

### Simple Refresh with Extension

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
