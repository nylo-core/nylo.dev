# CollectionView

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [CollectionItem Helper](#collection-item "CollectionItem Helper")
- Các biến thể
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Biến thể kéo để tải lại
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Kiểu tải dữ liệu](#loading-styles "Kiểu tải dữ liệu")
- [Trạng thái rỗng](#empty-state "Trạng thái rỗng")
- [Sắp xếp và biến đổi dữ liệu](#sorting-transforming "Sắp xếp và biến đổi dữ liệu")
- [Cập nhật trạng thái](#updating-state "Cập nhật trạng thái")
- [Tham chiếu tham số](#parameters "Tham chiếu tham số")


<div id="introduction"></div>

## Giới thiệu

Widget **CollectionView** là một trình bao bọc mạnh mẽ, an toàn kiểu dữ liệu để hiển thị danh sách dữ liệu trong các dự án {{ config('app.name') }} của bạn. Nó đơn giản hóa việc làm việc với `ListView`, `ListView.separated` và bố cục lưới đồng thời cung cấp hỗ trợ sẵn cho:

- Tải dữ liệu bất đồng bộ với trạng thái tải tự động
- Kéo để tải lại và phân trang
- Trình xây dựng item an toàn kiểu dữ liệu với các helper vị trí
- Xử lý trạng thái rỗng
- Sắp xếp và biến đổi dữ liệu

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Đây là một ví dụ đơn giản hiển thị danh sách các item:

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

Với dữ liệu bất đồng bộ từ API:

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

## CollectionItem Helper

Callback `builder` nhận một đối tượng `CollectionItem<T>` bao bọc dữ liệu của bạn với các helper vị trí hữu ích:

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

### Thuộc tính CollectionItem

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `data` | `T` | Dữ liệu thực tế của item |
| `index` | `int` | Chỉ mục hiện tại trong danh sách |
| `totalItems` | `int` | Tổng số item |
| `isFirst` | `bool` | True nếu đây là item đầu tiên |
| `isLast` | `bool` | True nếu đây là item cuối cùng |
| `isOdd` | `bool` | True nếu chỉ mục là số lẻ |
| `isEven` | `bool` | True nếu chỉ mục là số chẵn |
| `progress` | `double` | Tiến trình trong danh sách (0.0 đến 1.0) |

### Phương thức CollectionItem

| Phương thức | Mô tả |
|--------|-------------|
| `isAt(int position)` | Kiểm tra xem item có ở vị trí cụ thể không |
| `isInRange(int start, int end)` | Kiểm tra xem chỉ mục có nằm trong phạm vi không (bao gồm) |
| `isMultipleOf(int divisor)` | Kiểm tra xem chỉ mục có phải là bội số của số chia không |

<div id="collection-view-basic"></div>

## CollectionView

Constructor mặc định tạo một list view tiêu chuẩn:

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
  spacing: 8.0, // Add spacing between items
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

Tạo một danh sách có phân cách giữa các item:

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

Tạo bố cục lưới sử dụng staggered grid:

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

Tạo danh sách với tính năng kéo để tải lại và phân trang cuộn vô hạn:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iteration starts at 1 and increments on each load
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
  headerStyle: 'WaterDropHeader', // Pull indicator style
)
```

### Kiểu Header

Tham số `headerStyle` chấp nhận:
- `'WaterDropHeader'` (mặc định) - Hiệu ứng giọt nước
- `'ClassicHeader'` - Chỉ báo kéo cổ điển
- `'MaterialClassicHeader'` - Kiểu Material Design
- `'WaterDropMaterialHeader'` - Giọt nước Material
- `'BezierHeader'` - Hiệu ứng đường cong Bezier

### Callback phân trang

| Callback | Mô tả |
|----------|-------------|
| `beforeRefresh` | Được gọi trước khi bắt đầu tải lại |
| `onRefresh` | Được gọi khi tải lại hoàn tất |
| `afterRefresh` | Được gọi sau khi dữ liệu tải xong, nhận dữ liệu để biến đổi |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Kết hợp kéo để tải lại với danh sách có phân cách:

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

Kết hợp kéo để tải lại với bố cục lưới:

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

## Kiểu tải dữ liệu

Tùy chỉnh chỉ báo tải bằng `loadingStyle`:

``` dart
// Normal loading with custom widget
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading items..."),
  ),
)

// Skeletonizer loading effect
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

## Trạng thái rỗng

Hiển thị widget tùy chỉnh khi danh sách rỗng:

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

## Sắp xếp và biến đổi dữ liệu

### Sắp xếp

Sắp xếp các item trước khi hiển thị:

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

### Biến đổi

Biến đổi dữ liệu sau khi tải:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // Filter to only active users
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## Cập nhật trạng thái

Bạn có thể cập nhật hoặc đặt lại CollectionView bằng cách gán cho nó một `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Đặt lại danh sách

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Xóa một item

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Kích hoạt cập nhật chung

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Tham chiếu tham số

### Tham số chung

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `data` | `Function()` | Hàm trả về `List<T>` hoặc `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Hàm builder cho mỗi item |
| `empty` | `Widget?` | Widget hiển thị khi danh sách rỗng |
| `loadingStyle` | `LoadingStyle?` | Tùy chỉnh chỉ báo tải |
| `header` | `Widget?` | Widget header phía trên danh sách |
| `stateName` | `String?` | Tên cho quản lý trạng thái |
| `sort` | `Function(List<T>)?` | Hàm sắp xếp cho các item |
| `transform` | `Function(List<T>)?` | Hàm biến đổi cho dữ liệu |
| `spacing` | `double?` | Khoảng cách giữa các item |

### Tham số riêng cho Pullable

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Hàm dữ liệu phân trang |
| `onRefresh` | `Function()?` | Callback khi tải lại hoàn tất |
| `beforeRefresh` | `Function()?` | Callback trước khi tải lại |
| `afterRefresh` | `Function(dynamic)?` | Callback sau khi dữ liệu tải xong |
| `headerStyle` | `String?` | Kiểu chỉ báo kéo |
| `footerLoadingIcon` | `Widget?` | Chỉ báo tải tùy chỉnh cho phân trang |

### Tham số riêng cho Grid

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Số cột (mặc định: 2) |
| `mainAxisSpacing` | `double` | Khoảng cách dọc giữa các item |
| `crossAxisSpacing` | `double` | Khoảng cách ngang giữa các item |

### Tham số ListView

Tất cả các tham số tiêu chuẩn của `ListView` cũng được hỗ trợ: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent`, và nhiều hơn nữa.
