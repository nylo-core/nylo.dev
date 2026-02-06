# CollectionView

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Helper CollectionItem](#collection-item "Helper CollectionItem")
- Variantes
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Variantes avec rafraichissement
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Styles de chargement](#loading-styles "Styles de chargement")
- [Etat vide](#empty-state "Etat vide")
- [Tri et transformation des donnees](#sorting-transforming "Tri et transformation des donnees")
- [Mise a jour de l'etat](#updating-state "Mise a jour de l'etat")
- [Reference des parametres](#parameters "Reference des parametres")


<div id="introduction"></div>

## Introduction

Le widget **CollectionView** est un wrapper puissant et type pour afficher des listes de donnees dans vos projets {{ config('app.name') }}. Il simplifie le travail avec `ListView`, `ListView.separated` et les dispositions en grille tout en offrant un support integre pour :

- Le chargement asynchrone de donnees avec etats de chargement automatiques
- Le rafraichissement par glissement et la pagination
- Des constructeurs d'elements types avec des helpers de position
- La gestion de l'etat vide
- Le tri et la transformation des donnees

<div id="basic-usage"></div>

## Utilisation de base

Voici un exemple simple affichant une liste d'elements :

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

Avec des donnees asynchrones provenant d'une API :

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

Le callback `builder` recoit un objet `CollectionItem<T>` qui enveloppe vos donnees avec des helpers de position utiles :

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

### Proprietes de CollectionItem

| Propriete | Type | Description |
|----------|------|-------------|
| `data` | `T` | Les donnees reelles de l'element |
| `index` | `int` | Index actuel dans la liste |
| `totalItems` | `int` | Nombre total d'elements |
| `isFirst` | `bool` | Vrai si c'est le premier element |
| `isLast` | `bool` | Vrai si c'est le dernier element |
| `isOdd` | `bool` | Vrai si l'index est impair |
| `isEven` | `bool` | Vrai si l'index est pair |
| `progress` | `double` | Progression dans la liste (0.0 a 1.0) |

### Methodes de CollectionItem

| Methode | Description |
|--------|-------------|
| `isAt(int position)` | Verifie si l'element est a une position specifique |
| `isInRange(int start, int end)` | Verifie si l'index est dans une plage (inclusive) |
| `isMultipleOf(int divisor)` | Verifie si l'index est un multiple du diviseur |

<div id="collection-view-basic"></div>

## CollectionView

Le constructeur par defaut cree une vue liste standard :

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

Cree une liste avec des separateurs entre les elements :

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

Cree une disposition en grille utilisant une grille decalee :

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

Cree une liste avec rafraichissement par glissement et pagination par defilement infini :

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

### Styles d'en-tete

Le parametre `headerStyle` accepte :
- `'WaterDropHeader'` (par defaut) - Animation de goutte d'eau
- `'ClassicHeader'` - Indicateur de tirage classique
- `'MaterialClassicHeader'` - Style Material Design
- `'WaterDropMaterialHeader'` - Goutte d'eau Material
- `'BezierHeader'` - Animation de courbe de Bezier

### Callbacks de pagination

| Callback | Description |
|----------|-------------|
| `beforeRefresh` | Appele avant le debut du rafraichissement |
| `onRefresh` | Appele lorsque le rafraichissement est termine |
| `afterRefresh` | Appele apres le chargement des donnees, recoit les donnees pour transformation |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Combine le rafraichissement par glissement avec une liste separee :

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

Combine le rafraichissement par glissement avec une disposition en grille :

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

## Styles de chargement

Personnalisez l'indicateur de chargement avec `loadingStyle` :

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

## Etat vide

Affichez un widget personnalise lorsque la liste est vide :

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

## Tri et transformation des donnees

### Tri

Triez les elements avant l'affichage :

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

### Transformation

Transformez les donnees apres le chargement :

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

## Mise a jour de l'etat

Vous pouvez mettre a jour ou reinitialiser un CollectionView en lui donnant un `stateName` :

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Reinitialiser la liste

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Supprimer un element

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Declencher une mise a jour generale

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Reference des parametres

### Parametres communs

| Parametre | Type | Description |
|-----------|------|-------------|
| `data` | `Function()` | Fonction retournant `List<T>` ou `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Fonction de construction pour chaque element |
| `empty` | `Widget?` | Widget affiche lorsque la liste est vide |
| `loadingStyle` | `LoadingStyle?` | Personnaliser l'indicateur de chargement |
| `header` | `Widget?` | Widget d'en-tete au-dessus de la liste |
| `stateName` | `String?` | Nom pour la gestion d'etat |
| `sort` | `Function(List<T>)?` | Fonction de tri pour les elements |
| `transform` | `Function(List<T>)?` | Fonction de transformation pour les donnees |
| `spacing` | `double?` | Espacement entre les elements |

### Parametres specifiques aux variantes avec rafraichissement

| Parametre | Type | Description |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Fonction de donnees paginee |
| `onRefresh` | `Function()?` | Callback lorsque le rafraichissement est termine |
| `beforeRefresh` | `Function()?` | Callback avant le rafraichissement |
| `afterRefresh` | `Function(dynamic)?` | Callback apres le chargement des donnees |
| `headerStyle` | `String?` | Style de l'indicateur de tirage |
| `footerLoadingIcon` | `Widget?` | Indicateur de chargement personnalise pour la pagination |

### Parametres specifiques a la grille

| Parametre | Type | Description |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Nombre de colonnes (par defaut : 2) |
| `mainAxisSpacing` | `double` | Espacement vertical entre les elements |
| `crossAxisSpacing` | `double` | Espacement horizontal entre les elements |

### Parametres ListView

Tous les parametres standard de `ListView` sont egalement pris en charge : `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent`, et plus encore.
