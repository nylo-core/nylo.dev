# Pullable

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Constructeurs](#constructors "Constructeurs")
- [PullableConfig](#pullable-config "PullableConfig")
- [Styles d'en-tete](#header-styles "Styles d'en-tete")
- [Tirer vers le haut pour charger plus](#pull-up "Tirer vers le haut pour charger plus")
- [En-tetes et pieds de page personnalises](#custom-headers "En-tetes et pieds de page personnalises")
- [Controleur](#controller "Controleur")
- [Methode d'extension](#extension-method "Methode d'extension")
- [Integration avec CollectionView](#collection-view "Integration avec CollectionView")
- [Exemples](#examples "Exemples pratiques")

<div id="introduction"></div>

## Introduction

Le widget **Pullable** ajoute la fonctionnalite de rafraichissement par tirage et de chargement supplementaire a tout contenu defilable. Il enveloppe votre widget enfant avec un comportement de rafraichissement et de pagination pilote par les gestes, prenant en charge plusieurs styles d'animation d'en-tete.

Construit sur le package `pull_to_refresh_flutter3`, Pullable fournit une API claire avec des constructeurs nommes pour les configurations courantes.

``` dart
Pullable(
  onRefresh: () async {
    // Fetch fresh data
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## Utilisation de base

Enveloppez n'importe quel widget defilable avec `Pullable` :

``` dart
Pullable(
  onRefresh: () async {
    await loadLatestPosts();
  },
  child: ListView.builder(
    itemCount: posts.length,
    itemBuilder: (context, index) => PostCard(post: posts[index]),
  ),
)
```

Lorsque l'utilisateur tire la liste vers le bas, le callback `onRefresh` se declenche. L'indicateur de rafraichissement se termine automatiquement lorsque le callback est acheve.

<div id="constructors"></div>

## Constructeurs

`Pullable` fournit des constructeurs nommes pour les configurations courantes :

| Constructeur | Style d'en-tete | Description |
|--------------|----------------|-------------|
| `Pullable()` | Water Drop | Constructeur par defaut |
| `Pullable.classicHeader()` | Classic | Style classique de tirage pour rafraichir |
| `Pullable.waterDropHeader()` | Water Drop | Animation goutte d'eau |
| `Pullable.materialClassicHeader()` | Material Classic | Style classique Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Style goutte d'eau Material |
| `Pullable.bezierHeader()` | Bezier | Animation courbe de Bezier |
| `Pullable.noBounce()` | Configurable | Rebond reduit avec `ClampingScrollPhysics` |
| `Pullable.custom()` | Widget personnalise | Utilisez vos propres widgets d'en-tete/pied de page |
| `Pullable.builder()` | Configurable | Controle complet via `PullableConfig` |

### Exemples

``` dart
// Classic header
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material header
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// No bounce effect
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// Custom header widget
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

Pour un controle precis, utilisez `PullableConfig` avec le constructeur `Pullable.builder()` :

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    headerType: PullableHeaderType.materialClassic,
    onRefresh: () async => await refreshData(),
    onLoading: () async => await loadMoreData(),
    refreshCompleteDelay: Duration(milliseconds: 500),
    loadCompleteDelay: Duration(milliseconds: 300),
    physics: BouncingScrollPhysics(),
  ),
  child: myListView,
)
```

### Toutes les options de configuration

| Propriete | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `enablePullDown` | `bool` | `true` | Activer le tirage vers le bas pour rafraichir |
| `enablePullUp` | `bool` | `false` | Activer le tirage vers le haut pour charger plus |
| `physics` | `ScrollPhysics?` | null | Physique de defilement personnalisee |
| `onRefresh` | `Future<void> Function()?` | null | Callback de rafraichissement |
| `onLoading` | `Future<void> Function()?` | null | Callback de chargement supplementaire |
| `headerType` | `PullableHeaderType` | `waterDrop` | Style d'animation de l'en-tete |
| `customHeader` | `Widget?` | null | Widget d'en-tete personnalise |
| `customFooter` | `Widget?` | null | Widget de pied de page personnalise |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Delai avant la fin du rafraichissement |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Delai avant la fin du chargement |
| `enableOverScroll` | `bool` | `true` | Permettre l'effet de sur-defilement |
| `cacheExtent` | `double?` | null | Etendue du cache de defilement |
| `semanticChildCount` | `int?` | null | Nombre d'enfants semantiques |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Comment les gestes de glissement commencent |

<div id="header-styles"></div>

## Styles d'en-tete

Choisissez parmi cinq animations d'en-tete integrees :

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Definissez le style via le constructeur ou la configuration :

``` dart
// Via named constructor
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Via config
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## Tirer vers le haut pour charger plus

Activez la pagination avec le chargement par tirage vers le haut :

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // Reset to page 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // Load next page
      page++;
      List<Item> more = await fetchItems(page: page);
      items.addAll(more);
      setState(() {});
    },
  ),
  child: ListView.builder(
    itemCount: items.length,
    itemBuilder: (context, index) => ItemTile(item: items[index]),
  ),
)
```

<div id="custom-headers"></div>

## En-tetes et pieds de page personnalises

Fournissez vos propres widgets d'en-tete et de pied de page :

``` dart
Pullable.custom(
  customHeader: Container(
    height: 60,
    alignment: Alignment.center,
    child: CircularProgressIndicator(),
  ),
  customFooter: Container(
    height: 40,
    alignment: Alignment.center,
    child: Text("Loading more..."),
  ),
  enablePullUp: true,
  onRefresh: () async => await refreshData(),
  onLoading: () async => await loadMore(),
  child: myListView,
)
```

<div id="controller"></div>

## Controleur

Utilisez un `RefreshController` pour un controle programmatique :

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Trigger refresh programmatically
_controller.triggerRefresh();

// Trigger loading programmatically
_controller.triggerLoading();

// Check state
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### Methodes d'extension sur RefreshController

| Methode/Accesseur | Type de retour | Description |
|-------------------|----------------|-------------|
| `triggerRefresh()` | `void` | Declencher manuellement un rafraichissement |
| `triggerLoading()` | `void` | Declencher manuellement le chargement supplementaire |
| `isRefreshing` | `bool` | Indique si le rafraichissement est actif |
| `isLoading` | `bool` | Indique si le chargement est actif |

<div id="extension-method"></div>

## Methode d'extension

N'importe quel widget peut etre enveloppe avec le tirage pour rafraichir en utilisant l'extension `.pullable()` :

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Avec une configuration personnalisee :

``` dart
myListView.pullable(
  onRefresh: () async => await refreshData(),
  pullableConfig: PullableConfig(
    headerType: PullableHeaderType.classic,
    enablePullUp: true,
    onLoading: () async => await loadMore(),
  ),
)
```

<div id="collection-view"></div>

## Integration avec CollectionView

`CollectionView` fournit des variantes pullable avec pagination integree :

### CollectionView.pullable

``` dart
CollectionView<User>.pullable(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  onRefresh: () => print('Refreshed!'),
  headerStyle: 'WaterDropHeader',
)
```

### CollectionView.pullableSeparated

``` dart
CollectionView<User>.pullableSeparated(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  separatorBuilder: (context, index) => Divider(),
)
```

### CollectionView.pullableGrid

``` dart
CollectionView<Product>.pullableGrid(
  data: (iteration) async => api.getProducts(page: iteration),
  builder: (context, item) => ProductCard(product: item.data),
  crossAxisCount: 2,
  mainAxisSpacing: 8,
  crossAxisSpacing: 8,
)
```

### Parametres specifiques a Pullable

| Parametre | Type | Description |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback de donnees paginee (l'iteration commence a 1) |
| `onRefresh` | `Function()?` | Callback apres le rafraichissement |
| `beforeRefresh` | `Function()?` | Hook avant le debut du rafraichissement |
| `afterRefresh` | `Function(dynamic)?` | Hook apres le rafraichissement avec les donnees |
| `headerStyle` | `String?` | Nom du type d'en-tete (par exemple, `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Indicateur de chargement personnalise pour le pied de page |

<div id="examples"></div>

## Exemples

### Liste paginee avec rafraichissement

``` dart
class _PostListState extends NyState<PostListPage> {
  List<Post> posts = [];
  int page = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Pullable.builder(
        config: PullableConfig(
          enablePullDown: true,
          enablePullUp: true,
          headerType: PullableHeaderType.materialClassic,
          onRefresh: () async {
            page = 1;
            posts = await api<PostApiService>((request) => request.getPosts(page: page));
            setState(() {});
          },
          onLoading: () async {
            page++;
            List<Post> more = await api<PostApiService>((request) => request.getPosts(page: page));
            posts.addAll(more);
            setState(() {});
          },
        ),
        child: ListView.builder(
          itemCount: posts.length,
          itemBuilder: (context, index) => PostCard(post: posts[index]),
        ),
      ),
    );
  }
}
```

### Rafraichissement simple avec extension

``` dart
ListView(
  children: notifications
    .map((n) => ListTile(
      title: Text(n.title),
      subtitle: Text(n.body),
    ))
    .toList(),
).pullable(
  onRefresh: () async {
    notifications = await fetchNotifications();
    setState(() {});
  },
)
```
