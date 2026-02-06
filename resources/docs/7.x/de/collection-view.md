# CollectionView

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [CollectionItem-Helfer](#collection-item "CollectionItem-Helfer")
- Varianten
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Pullable-Varianten
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Ladestile](#loading-styles "Ladestile")
- [Leerer Zustand](#empty-state "Leerer Zustand")
- [Sortieren und Transformieren von Daten](#sorting-transforming "Sortieren und Transformieren von Daten")
- [Status aktualisieren](#updating-state "Status aktualisieren")
- [Parameter-Referenz](#parameters "Parameter-Referenz")


<div id="introduction"></div>

## Einleitung

Das **CollectionView**-Widget ist ein leistungsstarker, typsicherer Wrapper zur Anzeige von Datenlisten in Ihren {{ config('app.name') }}-Projekten. Es vereinfacht die Arbeit mit `ListView`, `ListView.separated` und Grid-Layouts und bietet integrierte Unterstützung für:

- Asynchrones Laden von Daten mit automatischen Ladezuständen
- Pull-to-Refresh und Paginierung
- Typsichere Element-Builder mit Positionshelfern
- Behandlung leerer Zustände
- Datensortierung und -transformation

<div id="basic-usage"></div>

## Grundlegende Verwendung

Hier ist ein einfaches Beispiel zur Anzeige einer Liste von Elementen:

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

Mit asynchronen Daten von einer API:

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

## CollectionItem-Helfer

Der `builder`-Callback erhält ein `CollectionItem<T>`-Objekt, das Ihre Daten mit nützlichen Positionshelfern umschließt:

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

### CollectionItem-Eigenschaften

| Eigenschaft | Typ | Beschreibung |
|-------------|-----|-------------|
| `data` | `T` | Die eigentlichen Elementdaten |
| `index` | `int` | Aktueller Index in der Liste |
| `totalItems` | `int` | Gesamtanzahl der Elemente |
| `isFirst` | `bool` | Wahr, wenn dies das erste Element ist |
| `isLast` | `bool` | Wahr, wenn dies das letzte Element ist |
| `isOdd` | `bool` | Wahr, wenn der Index ungerade ist |
| `isEven` | `bool` | Wahr, wenn der Index gerade ist |
| `progress` | `double` | Fortschritt durch die Liste (0.0 bis 1.0) |

### CollectionItem-Methoden

| Methode | Beschreibung |
|---------|-------------|
| `isAt(int position)` | Prüft, ob sich das Element an einer bestimmten Position befindet |
| `isInRange(int start, int end)` | Prüft, ob der Index innerhalb eines Bereichs liegt (inklusiv) |
| `isMultipleOf(int divisor)` | Prüft, ob der Index ein Vielfaches des Divisors ist |

<div id="collection-view-basic"></div>

## CollectionView

Der Standardkonstruktor erstellt eine Standard-Listenansicht:

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

Erstellt eine Liste mit Trennlinien zwischen Elementen:

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

Erstellt ein Grid-Layout mit gestaffeltem Raster:

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

Erstellt eine Liste mit Pull-to-Refresh und unendlicher Scroll-Paginierung:

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

### Header-Stile

Der Parameter `headerStyle` akzeptiert:
- `'WaterDropHeader'` (Standard) - Wassertropfen-Animation
- `'ClassicHeader'` - Klassischer Pull-Indikator
- `'MaterialClassicHeader'` - Material-Design-Stil
- `'WaterDropMaterialHeader'` - Material-Wassertropfen
- `'BezierHeader'` - Bezier-Kurven-Animation

### Paginierungs-Callbacks

| Callback | Beschreibung |
|----------|-------------|
| `beforeRefresh` | Wird vor Beginn der Aktualisierung aufgerufen |
| `onRefresh` | Wird aufgerufen, wenn die Aktualisierung abgeschlossen ist |
| `afterRefresh` | Wird nach dem Laden der Daten aufgerufen, empfängt Daten zur Transformation |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Kombiniert Pull-to-Refresh mit einer getrennten Liste:

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

Kombiniert Pull-to-Refresh mit Grid-Layout:

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

## Ladestile

Passen Sie den Ladeindikator mit `loadingStyle` an:

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

## Leerer Zustand

Zeigen Sie ein benutzerdefiniertes Widget an, wenn die Liste leer ist:

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

## Sortieren und Transformieren von Daten

### Sortieren

Elemente vor der Anzeige sortieren:

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

### Transformieren

Die Daten nach dem Laden transformieren:

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

## Status aktualisieren

Sie können eine CollectionView aktualisieren oder zurücksetzen, indem Sie ihr einen `stateName` geben:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Liste zurücksetzen

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Element entfernen

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Allgemeine Aktualisierung auslösen

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Parameter-Referenz

### Allgemeine Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `data` | `Function()` | Funktion, die `List<T>` oder `Future<List<T>>` zurückgibt |
| `builder` | `CollectionItemBuilder<T>` | Builder-Funktion für jedes Element |
| `empty` | `Widget?` | Widget, das angezeigt wird, wenn die Liste leer ist |
| `loadingStyle` | `LoadingStyle?` | Ladeindikator anpassen |
| `header` | `Widget?` | Header-Widget oberhalb der Liste |
| `stateName` | `String?` | Name für die Statusverwaltung |
| `sort` | `Function(List<T>)?` | Sortierfunktion für Elemente |
| `transform` | `Function(List<T>)?` | Transformationsfunktion für Daten |
| `spacing` | `double?` | Abstand zwischen Elementen |

### Pullable-spezifische Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `data` | `Function(int iteration)` | Paginierte Datenfunktion |
| `onRefresh` | `Function()?` | Callback wenn Aktualisierung abgeschlossen |
| `beforeRefresh` | `Function()?` | Callback vor der Aktualisierung |
| `afterRefresh` | `Function(dynamic)?` | Callback nach dem Laden der Daten |
| `headerStyle` | `String?` | Pull-Indikator-Stil |
| `footerLoadingIcon` | `Widget?` | Benutzerdefinierter Ladeindikator für Paginierung |

### Grid-spezifische Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `crossAxisCount` | `int` | Anzahl der Spalten (Standard: 2) |
| `mainAxisSpacing` | `double` | Vertikaler Abstand zwischen Elementen |
| `crossAxisSpacing` | `double` | Horizontaler Abstand zwischen Elementen |

### ListView-Parameter

Alle Standard-`ListView`-Parameter werden ebenfalls unterstützt: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` und mehr.
