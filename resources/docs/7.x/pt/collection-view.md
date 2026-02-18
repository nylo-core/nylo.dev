# CollectionView

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Helper CollectionItem](#collection-item "Helper CollectionItem")
- Variantes
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Variantes Pullable
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Estilos de Carregamento](#loading-styles "Estilos de Carregamento")
- [Estado Vazio](#empty-state "Estado Vazio")
- [Ordenação e Transformação de Dados](#sorting-transforming "Ordenação e Transformação de Dados")
- [Atualizando o Estado](#updating-state "Atualizando o Estado")
- [Referência de Parâmetros](#parameters "Referência de Parâmetros")


<div id="introduction"></div>

## Introdução

O widget **CollectionView** é um wrapper poderoso e type-safe para exibir listas de dados nos seus projetos {{ config('app.name') }}. Ele simplifica o trabalho com `ListView`, `ListView.separated` e layouts de grid, fornecendo suporte integrado para:

- Carregamento assíncrono de dados com estados de carregamento automáticos
- Pull-to-refresh e paginação
- Builders de itens type-safe com helpers de posição
- Tratamento de estado vazio
- Ordenação e transformação de dados

<div id="basic-usage"></div>

## Uso Básico

Aqui está um exemplo simples exibindo uma lista de itens:

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

Com dados assíncronos de uma API:

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

O callback `builder` recebe um objeto `CollectionItem<T>` que envolve seus dados com helpers de posição úteis:

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

### Propriedades do CollectionItem

| Propriedade | Tipo | Descrição |
|----------|------|-------------|
| `data` | `T` | Os dados reais do item |
| `index` | `int` | Índice atual na lista |
| `totalItems` | `int` | Número total de itens |
| `isFirst` | `bool` | Verdadeiro se este é o primeiro item |
| `isLast` | `bool` | Verdadeiro se este é o último item |
| `isOdd` | `bool` | Verdadeiro se o índice é ímpar |
| `isEven` | `bool` | Verdadeiro se o índice é par |
| `progress` | `double` | Progresso na lista (0.0 a 1.0) |

### Métodos do CollectionItem

| Método | Descrição |
|--------|-------------|
| `isAt(int position)` | Verifica se o item está em uma posição específica |
| `isInRange(int start, int end)` | Verifica se o índice está dentro do intervalo (inclusivo) |
| `isMultipleOf(int divisor)` | Verifica se o índice é múltiplo do divisor |

<div id="collection-view-basic"></div>

## CollectionView

O construtor padrão cria uma list view padrão:

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

Cria uma lista com separadores entre os itens:

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

Cria um layout de grid usando staggered grid:

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

Cria uma lista com pull-to-refresh e paginação por scroll infinito:

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

### Estilos de Header

O parâmetro `headerStyle` aceita:
- `'WaterDropHeader'` (padrão) - Animação de gota d'água
- `'ClassicHeader'` - Indicador de pull clássico
- `'MaterialClassicHeader'` - Estilo Material Design
- `'WaterDropMaterialHeader'` - Gota d'água Material
- `'BezierHeader'` - Animação de curva Bezier

### Callbacks de Paginação

| Callback | Descrição |
|----------|-------------|
| `beforeRefresh` | Chamado antes do refresh iniciar |
| `onRefresh` | Chamado quando o refresh é concluído |
| `afterRefresh` | Chamado após os dados carregarem, recebe dados para transformação |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Combina pull-to-refresh com lista separada:

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

Combina pull-to-refresh com layout de grid:

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

## Estilos de Carregamento

Personalize o indicador de carregamento usando `loadingStyle`:

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

## Estado Vazio

Exiba um widget personalizado quando a lista estiver vazia:

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

## Ordenação e Transformação de Dados

### Ordenação

Ordene os itens antes de exibir:

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

### Transformação

Transforme os dados após o carregamento:

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

## Atualizando o Estado

Você pode atualizar ou resetar um CollectionView atribuindo um `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Resetar a lista

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Remover um item

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Disparar uma atualização geral

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Referência de Parâmetros

### Parâmetros Comuns

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `data` | `Function()` | Função que retorna `List<T>` ou `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Função builder para cada item |
| `empty` | `Widget?` | Widget exibido quando a lista está vazia |
| `loadingStyle` | `LoadingStyle?` | Personalizar o indicador de carregamento |
| `header` | `Widget?` | Widget de cabeçalho acima da lista |
| `stateName` | `String?` | Nome para gerenciamento de estado |
| `sort` | `Function(List<T>)?` | Função de ordenação dos itens |
| `transform` | `Function(List<T>)?` | Função de transformação dos dados |
| `spacing` | `double?` | Espaçamento entre os itens |

### Parâmetros Específicos de Pullable

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Função de dados paginados |
| `onRefresh` | `Function()?` | Callback quando o refresh é concluído |
| `beforeRefresh` | `Function()?` | Callback antes do refresh |
| `afterRefresh` | `Function(dynamic)?` | Callback após os dados carregarem |
| `headerStyle` | `String?` | Estilo do indicador de pull |
| `footerLoadingIcon` | `Widget?` | Indicador de carregamento personalizado para paginação |

### Parâmetros Específicos de Grid

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Número de colunas (padrão: 2) |
| `mainAxisSpacing` | `double` | Espaçamento vertical entre os itens |
| `crossAxisSpacing` | `double` | Espaçamento horizontal entre os itens |

### Parâmetros do ListView

Todos os parâmetros padrão do `ListView` também são suportados: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` e mais.
