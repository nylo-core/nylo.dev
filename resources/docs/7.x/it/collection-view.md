# CollectionView

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Helper CollectionItem](#collection-item "Helper CollectionItem")
- Varianti
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Varianti Pullable
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Stili di Caricamento](#loading-styles "Stili di Caricamento")
- [Stato Vuoto](#empty-state "Stato Vuoto")
- [Ordinamento e Trasformazione dei Dati](#sorting-transforming "Ordinamento e Trasformazione dei Dati")
- [Aggiornamento dello Stato](#updating-state "Aggiornamento dello Stato")
- [Riferimento Parametri](#parameters "Riferimento Parametri")


<div id="introduction"></div>

## Introduzione

Il widget **CollectionView** e' un wrapper potente e type-safe per visualizzare elenchi di dati nei tuoi progetti {{ config('app.name') }}. Semplifica il lavoro con `ListView`, `ListView.separated` e layout a griglia, fornendo supporto integrato per:

- Caricamento asincrono dei dati con stati di caricamento automatici
- Pull-to-refresh e paginazione
- Builder di elementi type-safe con helper di posizione
- Gestione dello stato vuoto
- Ordinamento e trasformazione dei dati

<div id="basic-usage"></div>

## Utilizzo Base

Ecco un semplice esempio che mostra un elenco di elementi:

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

Con dati asincroni da un'API:

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

Il callback `builder` riceve un oggetto `CollectionItem<T>` che avvolge i tuoi dati con utili helper di posizione:

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

### Proprieta' di CollectionItem

| Proprieta' | Tipo | Descrizione |
|----------|------|-------------|
| `data` | `T` | I dati effettivi dell'elemento |
| `index` | `int` | Indice corrente nell'elenco |
| `totalItems` | `int` | Numero totale di elementi |
| `isFirst` | `bool` | True se questo e' il primo elemento |
| `isLast` | `bool` | True se questo e' l'ultimo elemento |
| `isOdd` | `bool` | True se l'indice e' dispari |
| `isEven` | `bool` | True se l'indice e' pari |
| `progress` | `double` | Progresso nell'elenco (da 0.0 a 1.0) |

### Metodi di CollectionItem

| Metodo | Descrizione |
|--------|-------------|
| `isAt(int position)` | Verifica se l'elemento si trova in una posizione specifica |
| `isInRange(int start, int end)` | Verifica se l'indice e' all'interno di un intervallo (incluso) |
| `isMultipleOf(int divisor)` | Verifica se l'indice e' un multiplo del divisore |

<div id="collection-view-basic"></div>

## CollectionView

Il costruttore predefinito crea una lista standard:

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

Crea una lista con separatori tra gli elementi:

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

Crea un layout a griglia usando una griglia sfalsata:

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

Crea una lista con pull-to-refresh e paginazione a scorrimento infinito:

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

### Stili di Intestazione

Il parametro `headerStyle` accetta:
- `'WaterDropHeader'` (predefinito) - Animazione a goccia d'acqua
- `'ClassicHeader'` - Indicatore pull classico
- `'MaterialClassicHeader'` - Stile Material Design
- `'WaterDropMaterialHeader'` - Goccia d'acqua Material
- `'BezierHeader'` - Animazione curva di Bezier

### Callback di Paginazione

| Callback | Descrizione |
|----------|-------------|
| `beforeRefresh` | Chiamato prima dell'inizio dell'aggiornamento |
| `onRefresh` | Chiamato quando l'aggiornamento e' completato |
| `afterRefresh` | Chiamato dopo il caricamento dei dati, riceve i dati per la trasformazione |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Combina pull-to-refresh con lista separata:

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

Combina pull-to-refresh con layout a griglia:

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

## Stili di Caricamento

Personalizza l'indicatore di caricamento usando `loadingStyle`:

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

## Stato Vuoto

Mostra un widget personalizzato quando la lista e' vuota:

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

## Ordinamento e Trasformazione dei Dati

### Ordinamento

Ordina gli elementi prima della visualizzazione:

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

### Trasformazione

Trasforma i dati dopo il caricamento:

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

## Aggiornamento dello Stato

Puoi aggiornare o reimpostare un CollectionView assegnandogli un `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Reimpostare la lista

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Rimuovere un elemento

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Attivare un aggiornamento generale

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Riferimento Parametri

### Parametri Comuni

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `data` | `Function()` | Funzione che restituisce `List<T>` o `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Funzione builder per ogni elemento |
| `empty` | `Widget?` | Widget mostrato quando la lista e' vuota |
| `loadingStyle` | `LoadingStyle?` | Personalizza l'indicatore di caricamento |
| `header` | `Widget?` | Widget intestazione sopra la lista |
| `stateName` | `String?` | Nome per la gestione dello stato |
| `sort` | `Function(List<T>)?` | Funzione di ordinamento per gli elementi |
| `transform` | `Function(List<T>)?` | Funzione di trasformazione per i dati |
| `spacing` | `double?` | Spaziatura tra gli elementi |

### Parametri Specifici per Pullable

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Funzione dati paginata |
| `onRefresh` | `Function()?` | Callback quando l'aggiornamento e' completato |
| `beforeRefresh` | `Function()?` | Callback prima dell'aggiornamento |
| `afterRefresh` | `Function(dynamic)?` | Callback dopo il caricamento dei dati |
| `headerStyle` | `String?` | Stile dell'indicatore pull |
| `footerLoadingIcon` | `Widget?` | Indicatore di caricamento personalizzato per la paginazione |

### Parametri Specifici per la Griglia

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Numero di colonne (predefinito: 2) |
| `mainAxisSpacing` | `double` | Spaziatura verticale tra gli elementi |
| `crossAxisSpacing` | `double` | Spaziatura orizzontale tra gli elementi |

### Parametri ListView

Tutti i parametri standard di `ListView` sono supportati: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` e altri.
