# Pullable

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe uzycie](#basic-usage "Podstawowe uzycie")
- [Konstruktory](#constructors "Konstruktory")
- [PullableConfig](#pullable-config "PullableConfig")
- [Style naglowkow](#header-styles "Style naglowkow")
- [Ciagniecie w gore, aby zaladowac wiecej](#pull-up "Ciagniecie w gore, aby zaladowac wiecej")
- [Niestandardowe naglowki i stopki](#custom-headers "Niestandardowe naglowki i stopki")
- [Kontroler](#controller "Kontroler")
- [Metoda rozszerzenia](#extension-method "Metoda rozszerzenia")
- [Integracja z CollectionView](#collection-view "Integracja z CollectionView")
- [Przyklady](#examples "Przyklady praktyczne")

<div id="introduction"></div>

## Wprowadzenie

Widget **Pullable** dodaje funkcjonalnosc odswiezania przez pociagniecie w dol (pull-to-refresh) oraz ladowania wiekszej ilosci elementow do dowolnej przewijalnej zawartosci. Opakowuje widget potomny zachowaniem odswiezania i paginacji sterowanym gestami, obslugujac wiele stylow animacji naglowkow.

Zbudowany na bazie pakietu `pull_to_refresh_flutter3`, Pullable zapewnia czyste API z nazwanymi konstruktorami dla typowych konfiguracji.

``` dart
Pullable(
  onRefresh: () async {
    // Pobierz swieze dane
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## Podstawowe uzycie

Opakuj dowolny przewijalny widget za pomoca `Pullable`:

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

Gdy uzytkownik pociagnie liste w dol, wywolanie zwrotne `onRefresh` zostanie uruchomione. Wskaznik odswiezania automatycznie zakonczy sie, gdy wywolanie zwrotne sie zakonczy.

<div id="constructors"></div>

## Konstruktory

`Pullable` udostepnia nazwane konstruktory dla typowych konfiguracji:

| Konstruktor | Styl naglowka | Opis |
|-------------|-------------|-------------|
| `Pullable()` | Kropla wody | Domyslny konstruktor |
| `Pullable.classicHeader()` | Klasyczny | Klasyczny styl pull-to-refresh |
| `Pullable.waterDropHeader()` | Kropla wody | Animacja kropli wody |
| `Pullable.materialClassicHeader()` | Material Classic | Klasyczny styl Material Design |
| `Pullable.waterDropMaterialHeader()` | Kropla wody Material | Styl kropli wody Material |
| `Pullable.bezierHeader()` | Bezier | Animacja krzywej Beziera |
| `Pullable.noBounce()` | Konfigurowalny | Zmniejszone odbicie z `ClampingScrollPhysics` |
| `Pullable.custom()` | Niestandardowy widget | Uzyj wlasnych widgetow naglowka/stopki |
| `Pullable.builder()` | Konfigurowalny | Pelna kontrola `PullableConfig` |

### Przyklady

``` dart
// Klasyczny naglowek
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Naglowek Material
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Bez efektu odbicia
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// Niestandardowy widget naglowka
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

Dla precyzyjnej kontroli uzyj `PullableConfig` z konstruktorem `Pullable.builder()`:

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

### Wszystkie opcje konfiguracji

| Wlasciwosc | Typ | Domyslnie | Opis |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Wlacz odswiezanie przez pociagniecie w dol |
| `enablePullUp` | `bool` | `false` | Wlacz ladowanie wiecej przez pociagniecie w gore |
| `physics` | `ScrollPhysics?` | null | Niestandardowa fizyka przewijania |
| `onRefresh` | `Future<void> Function()?` | null | Wywolanie zwrotne odswiezania |
| `onLoading` | `Future<void> Function()?` | null | Wywolanie zwrotne ladowania wiecej |
| `headerType` | `PullableHeaderType` | `waterDrop` | Styl animacji naglowka |
| `customHeader` | `Widget?` | null | Niestandardowy widget naglowka |
| `customFooter` | `Widget?` | null | Niestandardowy widget stopki |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Opoznienie przed zakonczeniem odswiezania |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Opoznienie przed zakonczeniem ladowania |
| `enableOverScroll` | `bool` | `true` | Zezwol na efekt nadmiernego przewijania |
| `cacheExtent` | `double?` | null | Zakres cache przewijania |
| `semanticChildCount` | `int?` | null | Liczba dzieci semantycznych |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Sposob rozpoczynania gestow przeciagania |

<div id="header-styles"></div>

## Style naglowkow

Wybierz sposrod pieciu wbudowanych animacji naglowkow:

``` dart
enum PullableHeaderType {
  classic,           // Klasyczny wskaznik pociagania
  waterDrop,         // Animacja kropli wody (domyslny)
  materialClassic,   // Material Design klasyczny
  waterDropMaterial,  // Kropla wody Material
  bezier,            // Animacja krzywej Beziera
}
```

Ustaw styl przez konstruktor lub konfiguracje:

``` dart
// Przez nazwany konstruktor
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Przez konfiguracje
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## Ciagniecie w gore, aby zaladowac wiecej

Wlacz paginacje z ladowaniem przez pociagniecie w gore:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // Resetuj do strony 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // Zaladuj nastepna strone
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

## Niestandardowe naglowki i stopki

Dostarcz wlasne widgety naglowka i stopki:

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

## Kontroler

Uzyj `RefreshController` do programistycznej kontroli:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Uruchom odswiezanie programistycznie
_controller.triggerRefresh();

// Uruchom ladowanie programistycznie
_controller.triggerLoading();

// Sprawdz stan
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### Metody rozszerzenia na RefreshController

| Metoda/Getter | Typ zwracany | Opis |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Reczne uruchomienie odswiezania |
| `triggerLoading()` | `void` | Reczne uruchomienie ladowania wiecej |
| `isRefreshing` | `bool` | Czy odswiezanie jest aktywne |
| `isLoading` | `bool` | Czy ladowanie jest aktywne |

<div id="extension-method"></div>

## Metoda rozszerzenia

Dowolny widget mozna opakowac funkcja pull-to-refresh za pomoca rozszerzenia `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Z niestandardowa konfiguracja:

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

## Integracja z CollectionView

`CollectionView` udostepnia warianty pullable z wbudowana paginacja:

### CollectionView.pullable

``` dart
CollectionView<User>.pullable(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  onRefresh: () => print('Odswiezono!'),
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

### Parametry specyficzne dla Pullable

| Parametr | Typ | Opis |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Wywolanie zwrotne danych z paginacja (iteracja zaczyna sie od 1) |
| `onRefresh` | `Function()?` | Wywolanie zwrotne po odswiezeniu |
| `beforeRefresh` | `Function()?` | Hak przed rozpoczeciem odswiezania |
| `afterRefresh` | `Function(dynamic)?` | Hak po odswiezeniu z danymi |
| `headerStyle` | `String?` | Nazwa typu naglowka (np. `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Niestandardowy wskaznik ladowania dla stopki |

<div id="examples"></div>

## Przyklady

### Lista z paginacja i odswiezaniem

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

### Proste odswiezanie z rozszerzeniem

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