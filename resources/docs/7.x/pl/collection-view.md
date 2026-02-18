# CollectionView

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Helper CollectionItem](#collection-item "Helper CollectionItem")
- Warianty
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Warianty z odświeżaniem
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Style ładowania](#loading-styles "Style ładowania")
- [Pusty stan](#empty-state "Pusty stan")
- [Sortowanie i transformacja danych](#sorting-transforming "Sortowanie i transformacja danych")
- [Aktualizowanie stanu](#updating-state "Aktualizowanie stanu")
- [Referencja parametrów](#parameters "Referencja parametrów")


<div id="introduction"></div>

## Wprowadzenie

Widget **CollectionView** to potężny, bezpieczny typowo wrapper do wyświetlania list danych w projektach {{ config('app.name') }}. Upraszcza pracę z `ListView`, `ListView.separated` i układami siatkowymi, zapewniając jednocześnie wbudowaną obsługę:

- Asynchronicznego ładowania danych z automatycznymi stanami ładowania
- Pociągnij, aby odświeżyć i paginacja
- Bezpieczne typowo buildery elementów z helperami pozycji
- Obsługa pustego stanu
- Sortowanie i transformacja danych

<div id="basic-usage"></div>

## Podstawowe użycie

Oto prosty przykład wyświetlania listy elementów:

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

Z asynchronicznymi danymi z API:

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

## Helper CollectionItem

Callback `builder` otrzymuje obiekt `CollectionItem<T>`, który opakowuje dane przydatnymi helperami pozycji:

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

### Właściwości CollectionItem

| Właściwość | Typ | Opis |
|------------|-----|------|
| `data` | `T` | Właściwe dane elementu |
| `index` | `int` | Bieżący indeks na liście |
| `totalItems` | `int` | Całkowita liczba elementów |
| `isFirst` | `bool` | True jeśli to pierwszy element |
| `isLast` | `bool` | True jeśli to ostatni element |
| `isOdd` | `bool` | True jeśli indeks jest nieparzysty |
| `isEven` | `bool` | True jeśli indeks jest parzysty |
| `progress` | `double` | Postęp na liście (0.0 do 1.0) |

### Metody CollectionItem

| Metoda | Opis |
|--------|------|
| `isAt(int position)` | Sprawdź, czy element jest na określonej pozycji |
| `isInRange(int start, int end)` | Sprawdź, czy indeks jest w zakresie (włącznie) |
| `isMultipleOf(int divisor)` | Sprawdź, czy indeks jest wielokrotnością dzielnika |

<div id="collection-view-basic"></div>

## CollectionView

Domyślny konstruktor tworzy standardowy widok listy:

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

Tworzy listę z separatorami między elementami:

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

Tworzy układ siatki przy użyciu staggered grid:

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

Tworzy listę z funkcją pociągnij, aby odświeżyć i nieskończonym przewijaniem z paginacją:

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

### Style nagłówka

Parametr `headerStyle` akceptuje:
- `'WaterDropHeader'` (domyślny) - Animacja kropli wody
- `'ClassicHeader'` - Klasyczny wskaźnik pull
- `'MaterialClassicHeader'` - Styl Material Design
- `'WaterDropMaterialHeader'` - Materiałowa kropla wody
- `'BezierHeader'` - Animacja krzywej Beziera

### Callbacki paginacji

| Callback | Opis |
|----------|------|
| `beforeRefresh` | Wywoływany przed rozpoczęciem odświeżania |
| `onRefresh` | Wywoływany po zakończeniu odświeżania |
| `afterRefresh` | Wywoływany po załadowaniu danych, otrzymuje dane do transformacji |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Łączy pociągnij, aby odświeżyć z listą z separatorami:

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

Łączy pociągnij, aby odświeżyć z układem siatki:

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

## Style ładowania

Dostosuj wskaźnik ładowania za pomocą `loadingStyle`:

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

## Pusty stan

Wyświetl niestandardowy widget, gdy lista jest pusta:

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

## Sortowanie i transformacja danych

### Sortowanie

Sortuj elementy przed wyświetleniem:

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

### Transformacja

Transformuj dane po załadowaniu:

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

## Aktualizowanie stanu

Możesz zaktualizować lub zresetować CollectionView, nadając mu `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Resetowanie listy

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Usuwanie elementu

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Wywołanie ogólnej aktualizacji

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Referencja parametrów

### Wspólne parametry

| Parametr | Typ | Opis |
|----------|-----|------|
| `data` | `Function()` | Funkcja zwracająca `List<T>` lub `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Funkcja buildera dla każdego elementu |
| `empty` | `Widget?` | Widget wyświetlany, gdy lista jest pusta |
| `loadingStyle` | `LoadingStyle?` | Dostosuj wskaźnik ładowania |
| `header` | `Widget?` | Widget nagłówka nad listą |
| `stateName` | `String?` | Nazwa do zarządzania stanem |
| `sort` | `Function(List<T>)?` | Funkcja sortowania elementów |
| `transform` | `Function(List<T>)?` | Funkcja transformacji danych |
| `spacing` | `double?` | Odstępy między elementami |

### Parametry specyficzne dla pullable

| Parametr | Typ | Opis |
|----------|-----|------|
| `data` | `Function(int iteration)` | Funkcja danych z paginacją |
| `onRefresh` | `Function()?` | Callback po zakończeniu odświeżania |
| `beforeRefresh` | `Function()?` | Callback przed odświeżaniem |
| `afterRefresh` | `Function(dynamic)?` | Callback po załadowaniu danych |
| `headerStyle` | `String?` | Styl wskaźnika pull |
| `footerLoadingIcon` | `Widget?` | Niestandardowy wskaźnik ładowania dla paginacji |

### Parametry specyficzne dla siatki

| Parametr | Typ | Opis |
|----------|-----|------|
| `crossAxisCount` | `int` | Liczba kolumn (domyślnie: 2) |
| `mainAxisSpacing` | `double` | Pionowe odstępy między elementami |
| `crossAxisSpacing` | `double` | Poziome odstępy między elementami |

### Parametry ListView

Wszystkie standardowe parametry `ListView` są również obsługiwane: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` i inne.
