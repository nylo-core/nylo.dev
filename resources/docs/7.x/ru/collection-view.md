# CollectionView

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Помощник CollectionItem](#collection-item "Помощник CollectionItem")
- Варианты
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Варианты с подтягиванием
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Стили загрузки](#loading-styles "Стили загрузки")
- [Пустое состояние](#empty-state "Пустое состояние")
- [Сортировка и преобразование данных](#sorting-transforming "Сортировка и преобразование данных")
- [Обновление состояния](#updating-state "Обновление состояния")
- [Справочник параметров](#parameters "Справочник параметров")


<div id="introduction"></div>

## Введение

Виджет **CollectionView** -- это мощная типобезопасная обёртка для отображения списков данных в ваших проектах на {{ config('app.name') }}. Он упрощает работу с `ListView`, `ListView.separated` и сеточными макетами, предоставляя встроенную поддержку для:

- Асинхронной загрузки данных с автоматическими состояниями загрузки
- Обновления по подтягиванию и пагинации
- Типобезопасных построителей элементов с помощниками позиции
- Обработки пустого состояния
- Сортировки и преобразования данных

<div id="basic-usage"></div>

## Базовое использование

Вот простой пример отображения списка элементов:

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

С асинхронными данными из API:

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

## Помощник CollectionItem

Обратный вызов `builder` получает объект `CollectionItem<T>`, который оборачивает ваши данные полезными помощниками позиции:

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

### Свойства CollectionItem

| Свойство | Тип | Описание |
|----------|------|-------------|
| `data` | `T` | Фактические данные элемента |
| `index` | `int` | Текущий индекс в списке |
| `totalItems` | `int` | Общее количество элементов |
| `isFirst` | `bool` | True, если это первый элемент |
| `isLast` | `bool` | True, если это последний элемент |
| `isOdd` | `bool` | True, если индекс нечётный |
| `isEven` | `bool` | True, если индекс чётный |
| `progress` | `double` | Прогресс по списку (от 0.0 до 1.0) |

### Методы CollectionItem

| Метод | Описание |
|--------|-------------|
| `isAt(int position)` | Проверяет, находится ли элемент на определённой позиции |
| `isInRange(int start, int end)` | Проверяет, находится ли индекс в диапазоне (включительно) |
| `isMultipleOf(int divisor)` | Проверяет, кратен ли индекс делителю |

<div id="collection-view-basic"></div>

## CollectionView

Конструктор по умолчанию создаёт стандартный список:

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

Создаёт список с разделителями между элементами:

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

Создаёт сеточный макет с использованием ступенчатой сетки:

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

Создаёт список с обновлением по подтягиванию и бесконечной прокруткой с пагинацией:

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

### Стили заголовков

Параметр `headerStyle` принимает:
- `'WaterDropHeader'` (по умолчанию) -- анимация капли воды
- `'ClassicHeader'` -- классический индикатор подтягивания
- `'MaterialClassicHeader'` -- стиль Material Design
- `'WaterDropMaterialHeader'` -- материальная капля воды
- `'BezierHeader'` -- анимация кривой Безье

### Обратные вызовы пагинации

| Обратный вызов | Описание |
|----------|-------------|
| `beforeRefresh` | Вызывается перед началом обновления |
| `onRefresh` | Вызывается при завершении обновления |
| `afterRefresh` | Вызывается после загрузки данных, получает данные для преобразования |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Объединяет обновление по подтягиванию со списком с разделителями:

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

Объединяет обновление по подтягиванию с сеточным макетом:

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

## Стили загрузки

Настраивайте индикатор загрузки с помощью `loadingStyle`:

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

## Пустое состояние

Отображайте пользовательский виджет, когда список пуст:

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

## Сортировка и преобразование данных

### Сортировка

Сортируйте элементы перед отображением:

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

### Преобразование

Преобразуйте данные после загрузки:

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

## Обновление состояния

Вы можете обновить или сбросить CollectionView, задав ему `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Сброс списка

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Удаление элемента

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Запуск общего обновления

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Справочник параметров

### Общие параметры

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `data` | `Function()` | Функция, возвращающая `List<T>` или `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Функция-построитель для каждого элемента |
| `empty` | `Widget?` | Виджет, отображаемый при пустом списке |
| `loadingStyle` | `LoadingStyle?` | Настройка индикатора загрузки |
| `header` | `Widget?` | Виджет заголовка над списком |
| `stateName` | `String?` | Имя для управления состоянием |
| `sort` | `Function(List<T>)?` | Функция сортировки элементов |
| `transform` | `Function(List<T>)?` | Функция преобразования данных |
| `spacing` | `double?` | Расстояние между элементами |

### Параметры для подтягиваемых вариантов

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Функция постраничной загрузки данных |
| `onRefresh` | `Function()?` | Обратный вызов при завершении обновления |
| `beforeRefresh` | `Function()?` | Обратный вызов перед обновлением |
| `afterRefresh` | `Function(dynamic)?` | Обратный вызов после загрузки данных |
| `headerStyle` | `String?` | Стиль индикатора подтягивания |
| `footerLoadingIcon` | `Widget?` | Пользовательский индикатор загрузки для пагинации |

### Параметры сетки

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Количество колонок (по умолчанию: 2) |
| `mainAxisSpacing` | `double` | Вертикальное расстояние между элементами |
| `crossAxisSpacing` | `double` | Горизонтальное расстояние между элементами |

### Параметры ListView

Также поддерживаются все стандартные параметры `ListView`: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` и другие.
