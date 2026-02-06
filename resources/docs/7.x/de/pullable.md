# Pullable

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Konstruktoren](#constructors "Konstruktoren")
- [PullableConfig](#pullable-config "PullableConfig")
- [Header-Stile](#header-styles "Header-Stile")
- [Nach oben ziehen zum Nachladen](#pull-up "Nach oben ziehen zum Nachladen")
- [Benutzerdefinierte Header und Footer](#custom-headers "Benutzerdefinierte Header und Footer")
- [Controller](#controller "Controller")
- [Erweiterungsmethode](#extension-method "Erweiterungsmethode")
- [CollectionView-Integration](#collection-view "CollectionView-Integration")
- [Beispiele](#examples "Praktische Beispiele")

<div id="introduction"></div>

## Einleitung

Das **Pullable**-Widget fuegt Pull-to-Refresh- und Nachladen-Funktionalitaet zu jedem scrollbaren Inhalt hinzu. Es umschliesst Ihr Child-Widget mit gestengesteuertem Aktualisierungs- und Paginierungsverhalten und unterstuetzt mehrere Header-Animationsstile.

Aufgebaut auf dem `pull_to_refresh_flutter3`-Paket bietet Pullable eine uebersichtliche API mit benannten Konstruktoren fuer gaengige Konfigurationen.

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

## Grundlegende Verwendung

Umschliessen Sie jedes scrollbare Widget mit `Pullable`:

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

Wenn der Benutzer die Liste nach unten zieht, wird der `onRefresh`-Callback ausgeloest. Der Aktualisierungsindikator wird automatisch abgeschlossen, wenn der Callback fertig ist.

<div id="constructors"></div>

## Konstruktoren

`Pullable` bietet benannte Konstruktoren fuer gaengige Konfigurationen:

| Konstruktor | Header-Stil | Beschreibung |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Standardkonstruktor |
| `Pullable.classicHeader()` | Classic | Klassischer Pull-to-Refresh-Stil |
| `Pullable.waterDropHeader()` | Water Drop | Wassertropfen-Animation |
| `Pullable.materialClassicHeader()` | Material Classic | Material-Design-Klassik-Stil |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Material-Wassertropfen-Stil |
| `Pullable.bezierHeader()` | Bezier | Bezier-Kurven-Animation |
| `Pullable.noBounce()` | Konfigurierbar | Reduzierter Bounce mit `ClampingScrollPhysics` |
| `Pullable.custom()` | Benutzerdefiniertes Widget | Eigene Header-/Footer-Widgets verwenden |
| `Pullable.builder()` | Konfigurierbar | Vollstaendige `PullableConfig`-Kontrolle |

### Beispiele

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

Fuer detaillierte Kontrolle verwenden Sie `PullableConfig` mit dem `Pullable.builder()`-Konstruktor:

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

### Alle Konfigurationsoptionen

| Eigenschaft | Typ | Standard | Beschreibung |
|-------------|-----|---------|-------------|
| `enablePullDown` | `bool` | `true` | Pull-Down-zum-Aktualisieren aktivieren |
| `enablePullUp` | `bool` | `false` | Pull-Up-zum-Nachladen aktivieren |
| `physics` | `ScrollPhysics?` | null | Benutzerdefinierte Scroll-Physik |
| `onRefresh` | `Future<void> Function()?` | null | Aktualisierungs-Callback |
| `onLoading` | `Future<void> Function()?` | null | Nachladen-Callback |
| `headerType` | `PullableHeaderType` | `waterDrop` | Header-Animationsstil |
| `customHeader` | `Widget?` | null | Benutzerdefiniertes Header-Widget |
| `customFooter` | `Widget?` | null | Benutzerdefiniertes Footer-Widget |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Verzoegerung vor Abschluss der Aktualisierung |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Verzoegerung vor Abschluss des Nachladens |
| `enableOverScroll` | `bool` | `true` | Ueber-Scroll-Effekt erlauben |
| `cacheExtent` | `double?` | null | Scroll-Cache-Ausdehnung |
| `semanticChildCount` | `int?` | null | Semantische Kindanzahl |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Wie Ziehgesten beginnen |

<div id="header-styles"></div>

## Header-Stile

Waehlen Sie aus fuenf integrierten Header-Animationen:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Setzen Sie den Stil ueber den Konstruktor oder die Konfiguration:

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

## Nach oben ziehen zum Nachladen

Aktivieren Sie Paginierung mit Pull-Up-Laden:

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

## Benutzerdefinierte Header und Footer

Stellen Sie eigene Header- und Footer-Widgets bereit:

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

Verwenden Sie einen `RefreshController` fuer programmatische Steuerung:

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

### Erweiterungsmethoden auf RefreshController

| Methode/Getter | Rueckgabetyp | Beschreibung |
|----------------|-------------|-------------|
| `triggerRefresh()` | `void` | Aktualisierung manuell ausloesen |
| `triggerLoading()` | `void` | Nachladen manuell ausloesen |
| `isRefreshing` | `bool` | Ob eine Aktualisierung aktiv ist |
| `isLoading` | `bool` | Ob ein Nachladevorgang aktiv ist |

<div id="extension-method"></div>

## Erweiterungsmethode

Jedes Widget kann mit Pull-to-Refresh umschlossen werden, indem die `.pullable()`-Erweiterung verwendet wird:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Mit benutzerdefinierter Konfiguration:

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

## CollectionView-Integration

`CollectionView` bietet Pullable-Varianten mit integrierter Paginierung:

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

### Pullable-spezifische Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `data` | `Function(int iteration)` | Paginierter Daten-Callback (Iteration beginnt bei 1) |
| `onRefresh` | `Function()?` | Callback nach der Aktualisierung |
| `beforeRefresh` | `Function()?` | Hook vor Beginn der Aktualisierung |
| `afterRefresh` | `Function(dynamic)?` | Hook nach der Aktualisierung mit Daten |
| `headerStyle` | `String?` | Header-Typname (z. B. `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Benutzerdefinierter Ladeindikator fuer den Footer |

<div id="examples"></div>

## Beispiele

### Paginierte Liste mit Aktualisierung

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

### Einfache Aktualisierung mit Erweiterung

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
