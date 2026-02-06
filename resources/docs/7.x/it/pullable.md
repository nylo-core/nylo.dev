# Pullable

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Costruttori](#constructors "Costruttori")
- [PullableConfig](#pullable-config "PullableConfig")
- [Stili dell'Header](#header-styles "Stili dell'Header")
- [Pull-Up per Caricare Altro](#pull-up "Pull-Up per Caricare Altro")
- [Header e Footer Personalizzati](#custom-headers "Header e Footer Personalizzati")
- [Controller](#controller "Controller")
- [Metodo di Estensione](#extension-method "Metodo di Estensione")
- [Integrazione con CollectionView](#collection-view "Integrazione con CollectionView")
- [Esempi](#examples "Esempi Pratici")

<div id="introduction"></div>

## Introduzione

Il widget **Pullable** aggiunge funzionalita' di pull-to-refresh e caricamento aggiuntivo a qualsiasi contenuto scrollabile. Avvolge il tuo widget figlio con un comportamento di refresh e paginazione guidato da gesture, supportando diversi stili di animazione dell'header.

Costruito sopra il pacchetto `pull_to_refresh_flutter3`, Pullable fornisce un'API pulita con costruttori con nome per le configurazioni comuni.

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

## Utilizzo Base

Avvolgi qualsiasi widget scrollabile con `Pullable`:

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

Quando l'utente trascina verso il basso sulla lista, viene eseguito il callback `onRefresh`. L'indicatore di refresh si completa automaticamente quando il callback termina.

<div id="constructors"></div>

## Costruttori

`Pullable` fornisce costruttori con nome per le configurazioni comuni:

| Costruttore | Stile Header | Descrizione |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Costruttore predefinito |
| `Pullable.classicHeader()` | Classic | Stile pull-to-refresh classico |
| `Pullable.waterDropHeader()` | Water Drop | Animazione goccia d'acqua |
| `Pullable.materialClassicHeader()` | Material Classic | Stile classico Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Stile goccia d'acqua Material |
| `Pullable.bezierHeader()` | Bezier | Animazione curva di Bezier |
| `Pullable.noBounce()` | Configurabile | Rimbalzo ridotto con `ClampingScrollPhysics` |
| `Pullable.custom()` | Widget personalizzato | Usa i tuoi widget header/footer |
| `Pullable.builder()` | Configurabile | Controllo completo tramite `PullableConfig` |

### Esempi

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

Per un controllo dettagliato, usa `PullableConfig` con il costruttore `Pullable.builder()`:

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

### Tutte le Opzioni di Configurazione

| Proprieta' | Tipo | Predefinito | Descrizione |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Abilita pull-down-to-refresh |
| `enablePullUp` | `bool` | `false` | Abilita pull-up-to-load-more |
| `physics` | `ScrollPhysics?` | null | Fisica di scroll personalizzata |
| `onRefresh` | `Future<void> Function()?` | null | Callback di refresh |
| `onLoading` | `Future<void> Function()?` | null | Callback di caricamento aggiuntivo |
| `headerType` | `PullableHeaderType` | `waterDrop` | Stile di animazione dell'header |
| `customHeader` | `Widget?` | null | Widget header personalizzato |
| `customFooter` | `Widget?` | null | Widget footer personalizzato |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Ritardo prima del completamento del refresh |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Ritardo prima del completamento del caricamento |
| `enableOverScroll` | `bool` | `true` | Permette l'effetto over-scroll |
| `cacheExtent` | `double?` | null | Estensione della cache di scroll |
| `semanticChildCount` | `int?` | null | Conteggio figli semantici |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Come iniziano le gesture di trascinamento |

<div id="header-styles"></div>

## Stili dell'Header

Scegli tra cinque animazioni dell'header integrate:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Imposta lo stile tramite il costruttore o la configurazione:

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

## Pull-Up per Caricare Altro

Abilita la paginazione con il caricamento pull-up:

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

## Header e Footer Personalizzati

Fornisci i tuoi widget header e footer:

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

## Controller

Usa un `RefreshController` per il controllo programmatico:

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

### Metodi di Estensione su RefreshController

| Metodo/Getter | Tipo di Ritorno | Descrizione |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Attiva manualmente un refresh |
| `triggerLoading()` | `void` | Attiva manualmente il caricamento aggiuntivo |
| `isRefreshing` | `bool` | Se il refresh e' attivo |
| `isLoading` | `bool` | Se il caricamento e' attivo |

<div id="extension-method"></div>

## Metodo di Estensione

Qualsiasi widget puo' essere avvolto con pull-to-refresh usando l'estensione `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Con configurazione personalizzata:

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

## Integrazione con CollectionView

`CollectionView` fornisce varianti pullable con paginazione integrata:

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

### Parametri Specifici di Pullable

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback per dati paginati (l'iterazione parte da 1) |
| `onRefresh` | `Function()?` | Callback dopo il refresh |
| `beforeRefresh` | `Function()?` | Hook prima dell'inizio del refresh |
| `afterRefresh` | `Function(dynamic)?` | Hook dopo il refresh con i dati |
| `headerStyle` | `String?` | Nome del tipo di header (es. `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Indicatore di caricamento personalizzato per il footer |

<div id="examples"></div>

## Esempi

### Lista Paginata con Refresh

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

### Refresh Semplice con Estensione

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
