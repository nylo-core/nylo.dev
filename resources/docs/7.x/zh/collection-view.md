# CollectionView

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [CollectionItem 辅助类](#collection-item "CollectionItem 辅助类")
- 变体
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- 可拉动变体
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [加载样式](#loading-styles "加载样式")
- [空状态](#empty-state "空状态")
- [排序和转换数据](#sorting-transforming "排序和转换数据")
- [更新状态](#updating-state "更新状态")
- [参数参考](#parameters "参数参考")


<div id="introduction"></div>

## 简介

**CollectionView** 组件是一个强大的、类型安全的包装器，用于在您的 {{ config('app.name') }} 项目中显示数据列表。它简化了 `ListView`、`ListView.separated` 和网格布局的使用，同时提供内置支持：

- 异步数据加载与自动加载状态
- 下拉刷新和分页
- 带有位置辅助方法的类型安全项目构建器
- 空状态处理
- 数据排序和转换

<div id="basic-usage"></div>

## 基本用法

以下是显示项目列表的简单示例：

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

使用来自 API 的异步数据：

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

## CollectionItem 辅助类

`builder` 回调接收一个 `CollectionItem<T>` 对象，该对象用有用的位置辅助方法包装您的数据：

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

### CollectionItem 属性

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `data` | `T` | 实际的项目数据 |
| `index` | `int` | 在列表中的当前索引 |
| `totalItems` | `int` | 项目总数 |
| `isFirst` | `bool` | 是否为第一个项目 |
| `isLast` | `bool` | 是否为最后一个项目 |
| `isOdd` | `bool` | 索引是否为奇数 |
| `isEven` | `bool` | 索引是否为偶数 |
| `progress` | `double` | 列表进度（0.0 到 1.0） |

### CollectionItem 方法

| 方法 | 描述 |
|--------|-------------|
| `isAt(int position)` | 检查项目是否在特定位置 |
| `isInRange(int start, int end)` | 检查索引是否在范围内（包含端点） |
| `isMultipleOf(int divisor)` | 检查索引是否为除数的倍数 |

<div id="collection-view-basic"></div>

## CollectionView

默认构造函数创建标准列表视图：

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

创建带有项目之间分隔符的列表：

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

使用交错网格创建网格布局：

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

创建带有下拉刷新和无限滚动分页的列表：

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

### 头部样式

`headerStyle` 参数接受：
- `'WaterDropHeader'`（默认）- 水滴动画
- `'ClassicHeader'` - 经典下拉指示器
- `'MaterialClassicHeader'` - Material 设计风格
- `'WaterDropMaterialHeader'` - Material 水滴效果
- `'BezierHeader'` - 贝塞尔曲线动画

### 分页回调

| 回调 | 描述 |
|----------|-------------|
| `beforeRefresh` | 刷新开始前调用 |
| `onRefresh` | 刷新完成时调用 |
| `afterRefresh` | 数据加载后调用，接收数据用于转换 |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

将下拉刷新与分隔列表结合：

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

将下拉刷新与网格布局结合：

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

## 加载样式

使用 `loadingStyle` 自定义加载指示器：

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

## 空状态

当列表为空时显示自定义组件：

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

## 排序和转换数据

### 排序

在显示之前对项目排序：

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

### 转换

加载后转换数据：

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

## 更新状态

您可以通过给 CollectionView 一个 `stateName` 来更新或重置它：

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### 重置列表

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### 移除项目

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### 触发一般更新

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## 参数参考

### 通用参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `data` | `Function()` | 返回 `List<T>` 或 `Future<List<T>>` 的函数 |
| `builder` | `CollectionItemBuilder<T>` | 每个项目的构建函数 |
| `empty` | `Widget?` | 列表为空时显示的组件 |
| `loadingStyle` | `LoadingStyle?` | 自定义加载指示器 |
| `header` | `Widget?` | 列表上方的头部组件 |
| `stateName` | `String?` | 状态管理名称 |
| `sort` | `Function(List<T>)?` | 项目排序函数 |
| `transform` | `Function(List<T>)?` | 数据转换函数 |
| `spacing` | `double?` | 项目之间的间距 |

### 可拉动特有参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | 分页数据函数 |
| `onRefresh` | `Function()?` | 刷新完成时的回调 |
| `beforeRefresh` | `Function()?` | 刷新前的回调 |
| `afterRefresh` | `Function(dynamic)?` | 数据加载后的回调 |
| `headerStyle` | `String?` | 下拉指示器样式 |
| `footerLoadingIcon` | `Widget?` | 分页的自定义加载指示器 |

### 网格特有参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `crossAxisCount` | `int` | 列数（默认：2） |
| `mainAxisSpacing` | `double` | 项目之间的垂直间距 |
| `crossAxisSpacing` | `double` | 项目之间的水平间距 |

### ListView 参数

所有标准 `ListView` 参数也受支持：`scrollDirection`、`reverse`、`controller`、`physics`、`shrinkWrap`、`padding`、`cacheExtent` 等。
