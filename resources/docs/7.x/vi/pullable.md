# Pullable

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Các constructor](#constructors "Các constructor")
- [PullableConfig](#pullable-config "PullableConfig")
- [Kiểu header](#header-styles "Kiểu header")
- [Kéo lên để tải thêm](#pull-up "Kéo lên để tải thêm")
- [Header và Footer tùy chỉnh](#custom-headers "Header và Footer tùy chỉnh")
- [Controller](#controller "Controller")
- [Phương thức mở rộng](#extension-method "Phương thức mở rộng")
- [Tích hợp CollectionView](#collection-view "Tích hợp CollectionView")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

Widget **Pullable** thêm chức năng kéo-để-làm-mới và tải-thêm vào bất kỳ nội dung cuộn nào. Nó bọc widget con của bạn với hành vi làm mới và phân trang dựa trên cử chỉ, hỗ trợ nhiều kiểu hoạt ảnh header.

Được xây dựng trên gói `pull_to_refresh_flutter3`, Pullable cung cấp API gọn gàng với các constructor được đặt tên cho các cấu hình phổ biến.

``` dart
Pullable(
  onRefresh: () async {
    // Lấy dữ liệu mới
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Bọc bất kỳ widget cuộn nào với `Pullable`:

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

Khi người dùng kéo xuống trên danh sách, callback `onRefresh` sẽ được kích hoạt. Chỉ báo làm mới tự động hoàn thành khi callback kết thúc.

<div id="constructors"></div>

## Các constructor

`Pullable` cung cấp các constructor được đặt tên cho các cấu hình phổ biến:

| Constructor | Kiểu Header | Mô tả |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Constructor mặc định |
| `Pullable.classicHeader()` | Classic | Kiểu kéo-để-làm-mới cổ điển |
| `Pullable.waterDropHeader()` | Water Drop | Hoạt ảnh giọt nước |
| `Pullable.materialClassicHeader()` | Material Classic | Kiểu cổ điển Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Kiểu giọt nước Material |
| `Pullable.bezierHeader()` | Bezier | Hoạt ảnh đường cong Bezier |
| `Pullable.noBounce()` | Có thể cấu hình | Giảm nảy với `ClampingScrollPhysics` |
| `Pullable.custom()` | Widget tùy chỉnh | Sử dụng widget header/footer của riêng bạn |
| `Pullable.builder()` | Có thể cấu hình | Điều khiển đầy đủ `PullableConfig` |

### Ví dụ

``` dart
// Header cổ điển
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Header Material
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Không có hiệu ứng nảy
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// Widget header tùy chỉnh
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

Để kiểm soát chi tiết, sử dụng `PullableConfig` với constructor `Pullable.builder()`:

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

### Tất cả tùy chọn cấu hình

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Bật kéo-xuống-để-làm-mới |
| `enablePullUp` | `bool` | `false` | Bật kéo-lên-để-tải-thêm |
| `physics` | `ScrollPhysics?` | null | Vật lý cuộn tùy chỉnh |
| `onRefresh` | `Future<void> Function()?` | null | Callback làm mới |
| `onLoading` | `Future<void> Function()?` | null | Callback tải thêm |
| `headerType` | `PullableHeaderType` | `waterDrop` | Kiểu hoạt ảnh header |
| `customHeader` | `Widget?` | null | Widget header tùy chỉnh |
| `customFooter` | `Widget?` | null | Widget footer tùy chỉnh |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Độ trễ trước khi hoàn thành làm mới |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Độ trễ trước khi hoàn thành tải |
| `enableOverScroll` | `bool` | `true` | Cho phép hiệu ứng cuộn quá |
| `cacheExtent` | `double?` | null | Phạm vi cache cuộn |
| `semanticChildCount` | `int?` | null | Số lượng con ngữ nghĩa |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Cách bắt đầu cử chỉ kéo |

<div id="header-styles"></div>

## Kiểu header

Chọn từ năm hoạt ảnh header tích hợp sẵn:

``` dart
enum PullableHeaderType {
  classic,           // Chỉ báo kéo cổ điển
  waterDrop,         // Hoạt ảnh giọt nước (mặc định)
  materialClassic,   // Material Design cổ điển
  waterDropMaterial,  // Giọt nước Material
  bezier,            // Hoạt ảnh đường cong Bezier
}
```

Đặt kiểu thông qua constructor hoặc cấu hình:

``` dart
// Qua constructor được đặt tên
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Qua cấu hình
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## Kéo lên để tải thêm

Bật phân trang với tải kéo lên:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // Đặt lại về trang 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // Tải trang tiếp theo
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

## Header và Footer tùy chỉnh

Cung cấp widget header và footer của riêng bạn:

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

Sử dụng `RefreshController` để điều khiển theo chương trình:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Kích hoạt làm mới theo chương trình
_controller.triggerRefresh();

// Kích hoạt tải theo chương trình
_controller.triggerLoading();

// Kiểm tra trạng thái
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### Phương thức mở rộng trên RefreshController

| Phương thức/Getter | Kiểu trả về | Mô tả |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Kích hoạt làm mới thủ công |
| `triggerLoading()` | `void` | Kích hoạt tải thêm thủ công |
| `isRefreshing` | `bool` | Liệu làm mới có đang hoạt động không |
| `isLoading` | `bool` | Liệu tải có đang hoạt động không |

<div id="extension-method"></div>

## Phương thức mở rộng

Bất kỳ widget nào cũng có thể được bọc với kéo-để-làm-mới bằng extension `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Với cấu hình tùy chỉnh:

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

## Tích hợp CollectionView

`CollectionView` cung cấp các biến thể pullable với phân trang tích hợp:

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

### Tham số dành riêng cho Pullable

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback dữ liệu phân trang (iteration bắt đầu từ 1) |
| `onRefresh` | `Function()?` | Callback sau khi làm mới |
| `beforeRefresh` | `Function()?` | Hook trước khi bắt đầu làm mới |
| `afterRefresh` | `Function(dynamic)?` | Hook sau khi làm mới với dữ liệu |
| `headerStyle` | `String?` | Tên kiểu header (ví dụ: `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Chỉ báo tải tùy chỉnh cho footer |

<div id="examples"></div>

## Ví dụ

### Danh sách phân trang với làm mới

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

### Làm mới đơn giản với Extension

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
