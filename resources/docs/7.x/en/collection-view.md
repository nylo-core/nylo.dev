# CollectionView

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [CollectionItem Helper](#collection-item "CollectionItem Helper")
- Variants
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Pullable Variants
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Loading Styles](#loading-styles "Loading Styles")
- [Empty State](#empty-state "Empty State")
- [Sorting and Transforming Data](#sorting-transforming "Sorting and Transforming Data")
- [Updating the State](#updating-state "Updating the State")
- [Parameters Reference](#parameters "Parameters Reference")


<div id="introduction"></div>

## Introduction

The **CollectionView** widget is a powerful, type-safe wrapper for displaying lists of data in your {{ config('app.name') }} projects. It simplifies working with `ListView`, `ListView.separated`, and grid layouts while providing built-in support for:

- Async data loading with automatic loading states
- Pull-to-refresh and pagination
- Type-safe item builders with position helpers
- Empty state handling
- Data sorting and transformation

<div id="basic-usage"></div>

## Basic Usage

Here's a simple example displaying a list of items:

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

With async data from an API:

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

The `builder` callback receives a `CollectionItem<T>` object that wraps your data with useful position helpers:

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

### CollectionItem Properties

| Property | Type | Description |
|----------|------|-------------|
| `data` | `T` | The actual item data |
| `index` | `int` | Current index in the list |
| `totalItems` | `int` | Total number of items |
| `isFirst` | `bool` | True if this is the first item |
| `isLast` | `bool` | True if this is the last item |
| `isOdd` | `bool` | True if index is odd |
| `isEven` | `bool` | True if index is even |
| `progress` | `double` | Progress through list (0.0 to 1.0) |

### CollectionItem Methods

| Method | Description |
|--------|-------------|
| `isAt(int position)` | Check if item is at specific position |
| `isInRange(int start, int end)` | Check if index is within range (inclusive) |
| `isMultipleOf(int divisor)` | Check if index is multiple of divisor |

<div id="collection-view-basic"></div>

## CollectionView

The default constructor creates a standard list view:

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

Creates a list with separators between items:

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

Creates a grid layout using staggered grid:

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

Creates a list with pull-to-refresh and infinite scroll pagination:

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

### Header Styles

The `headerStyle` parameter accepts:
- `'WaterDropHeader'` (default) - Water drop animation
- `'ClassicHeader'` - Classic pull indicator
- `'MaterialClassicHeader'` - Material design style
- `'WaterDropMaterialHeader'` - Material water drop
- `'BezierHeader'` - Bezier curve animation

### Pagination Callbacks

| Callback | Description |
|----------|-------------|
| `beforeRefresh` | Called before refresh starts |
| `onRefresh` | Called when refresh completes |
| `afterRefresh` | Called after data loads, receives data for transformation |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Combines pull-to-refresh with separated list:

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

Combines pull-to-refresh with grid layout:

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

## Loading Styles

Customize the loading indicator using `loadingStyle`:

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

## Empty State

Display a custom widget when the list is empty:

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

## Sorting and Transforming Data

### Sorting

Sort items before display:

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

### Transform

Transform the data after loading:

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

## Updating the State

You can update or reset a CollectionView by giving it a `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Reset the list

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Remove an item

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Trigger a general update

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Parameters Reference

### Common Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | `Function()` | Function returning `List<T>` or `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Builder function for each item |
| `empty` | `Widget?` | Widget shown when list is empty |
| `loadingStyle` | `LoadingStyle?` | Customize loading indicator |
| `header` | `Widget?` | Header widget above the list |
| `stateName` | `String?` | Name for state management |
| `sort` | `Function(List<T>)?` | Sort function for items |
| `transform` | `Function(List<T>)?` | Transform function for data |
| `spacing` | `double?` | Spacing between items |

### Pullable-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Paginated data function |
| `onRefresh` | `Function()?` | Callback when refresh completes |
| `beforeRefresh` | `Function()?` | Callback before refresh |
| `afterRefresh` | `Function(dynamic)?` | Callback after data loads |
| `headerStyle` | `String?` | Pull indicator style |
| `footerLoadingIcon` | `Widget?` | Custom loading indicator for pagination |

### Grid-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Number of columns (default: 2) |
| `mainAxisSpacing` | `double` | Vertical spacing between items |
| `crossAxisSpacing` | `double` | Horizontal spacing between items |

### ListView Parameters

All standard `ListView` parameters are also supported: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent`, and more.
