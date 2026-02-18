# CollectionView

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Helper CollectionItem](#collection-item "Helper CollectionItem")
- Variantes
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Variantes Pullable
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Estilos de carga](#loading-styles "Estilos de carga")
- [Estado vacio](#empty-state "Estado vacio")
- [Ordenar y transformar datos](#sorting-transforming "Ordenar y transformar datos")
- [Actualizar el estado](#updating-state "Actualizar el estado")
- [Referencia de parametros](#parameters "Referencia de parametros")


<div id="introduction"></div>

## Introduccion

El widget **CollectionView** es un envoltorio potente y con tipado seguro para mostrar listas de datos en tus proyectos de {{ config('app.name') }}. Simplifica el trabajo con `ListView`, `ListView.separated` y diseños de cuadricula mientras proporciona soporte integrado para:

- Carga asincrona de datos con estados de carga automaticos
- Pull-to-refresh y paginacion
- Constructores de elementos con tipado seguro y helpers de posicion
- Manejo de estado vacio
- Ordenamiento y transformacion de datos

<div id="basic-usage"></div>

## Uso basico

Aqui tienes un ejemplo simple mostrando una lista de elementos:

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

Con datos asincronos desde una API:

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

El callback `builder` recibe un objeto `CollectionItem<T>` que envuelve tus datos con helpers de posicion utiles:

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

### Propiedades de CollectionItem

| Propiedad | Tipo | Descripcion |
|----------|------|-------------|
| `data` | `T` | Los datos reales del elemento |
| `index` | `int` | Indice actual en la lista |
| `totalItems` | `int` | Numero total de elementos |
| `isFirst` | `bool` | Verdadero si es el primer elemento |
| `isLast` | `bool` | Verdadero si es el ultimo elemento |
| `isOdd` | `bool` | Verdadero si el indice es impar |
| `isEven` | `bool` | Verdadero si el indice es par |
| `progress` | `double` | Progreso a traves de la lista (0.0 a 1.0) |

### Metodos de CollectionItem

| Metodo | Descripcion |
|--------|-------------|
| `isAt(int position)` | Verifica si el elemento esta en una posicion especifica |
| `isInRange(int start, int end)` | Verifica si el indice esta dentro del rango (inclusivo) |
| `isMultipleOf(int divisor)` | Verifica si el indice es multiplo del divisor |

<div id="collection-view-basic"></div>

## CollectionView

El constructor por defecto crea una vista de lista estandar:

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

Crea una lista con separadores entre elementos:

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

Crea un diseño de cuadricula usando cuadricula escalonada:

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

Crea una lista con pull-to-refresh y paginacion con scroll infinito:

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

### Estilos de encabezado

El parametro `headerStyle` acepta:
- `'WaterDropHeader'` (por defecto) - Animacion de gota de agua
- `'ClassicHeader'` - Indicador de pull clasico
- `'MaterialClassicHeader'` - Estilo Material Design
- `'WaterDropMaterialHeader'` - Gota de agua Material
- `'BezierHeader'` - Animacion de curva Bezier

### Callbacks de paginacion

| Callback | Descripcion |
|----------|-------------|
| `beforeRefresh` | Se llama antes de que comience el refresco |
| `onRefresh` | Se llama cuando el refresco se completa |
| `afterRefresh` | Se llama despues de cargar los datos, recibe datos para transformacion |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Combina pull-to-refresh con lista separada:

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

Combina pull-to-refresh con diseño de cuadricula:

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

## Estilos de carga

Personaliza el indicador de carga usando `loadingStyle`:

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

## Estado vacio

Muestra un widget personalizado cuando la lista esta vacia:

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

## Ordenar y transformar datos

### Ordenar

Ordena los elementos antes de mostrarlos:

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

### Transformar

Transforma los datos despues de cargarlos:

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

## Actualizar el estado

Puedes actualizar o reiniciar un CollectionView asignandole un `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Reiniciar la lista

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Eliminar un elemento

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Activar una actualizacion general

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Referencia de parametros

### Parametros comunes

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `data` | `Function()` | Funcion que devuelve `List<T>` o `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Funcion constructora para cada elemento |
| `empty` | `Widget?` | Widget mostrado cuando la lista esta vacia |
| `loadingStyle` | `LoadingStyle?` | Personalizar indicador de carga |
| `header` | `Widget?` | Widget de encabezado sobre la lista |
| `stateName` | `String?` | Nombre para gestion de estado |
| `sort` | `Function(List<T>)?` | Funcion de ordenamiento para elementos |
| `transform` | `Function(List<T>)?` | Funcion de transformacion para datos |
| `spacing` | `double?` | Espaciado entre elementos |

### Parametros especificos de Pullable

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Funcion de datos paginados |
| `onRefresh` | `Function()?` | Callback cuando el refresco se completa |
| `beforeRefresh` | `Function()?` | Callback antes del refresco |
| `afterRefresh` | `Function(dynamic)?` | Callback despues de cargar datos |
| `headerStyle` | `String?` | Estilo del indicador de pull |
| `footerLoadingIcon` | `Widget?` | Indicador de carga personalizado para paginacion |

### Parametros especificos de Grid

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Numero de columnas (por defecto: 2) |
| `mainAxisSpacing` | `double` | Espaciado vertical entre elementos |
| `crossAxisSpacing` | `double` | Espaciado horizontal entre elementos |

### Parametros de ListView

Todos los parametros estandar de `ListView` tambien son compatibles: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent`, y mas.
