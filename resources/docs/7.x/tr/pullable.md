# Pullable

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Constructor'lar](#constructors "Constructor'lar")
- [PullableConfig](#pullable-config "PullableConfig")
- [Başlık Stilleri](#header-styles "Başlık Stilleri")
- [Daha Fazla Yüklemek İçin Yukarı Çekme](#pull-up "Daha Fazla Yüklemek İçin Yukarı Çekme")
- [Özel Başlıklar ve Altlıklar](#custom-headers "Özel Başlıklar ve Altlıklar")
- [Controller](#controller "Controller")
- [Extension Metodu](#extension-method "Extension Metodu")
- [CollectionView Entegrasyonu](#collection-view "CollectionView Entegrasyonu")
- [Örnekler](#examples "Örnekler")

<div id="introduction"></div>

## Giriş

**Pullable** widget'ı, herhangi bir kaydırılabilir içeriğe çekerek yenileme ve daha fazla yükleme işlevselliği ekler. Alt widget'ınızı, birden fazla başlık animasyon stili destekleyen hareketle tetiklenen yenileme ve sayfalama davranışıyla sarar.

`pull_to_refresh_flutter3` paketi üzerine inşa edilen Pullable, yaygın yapılandırmalar için adlandırılmış constructor'larla temiz bir API sunar.

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

## Temel Kullanım

Herhangi bir kaydırılabilir widget'ı `Pullable` ile sarın:

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

Kullanıcı listeyi aşağı çektiğinde, `onRefresh` geri çağırması tetiklenir. Yenileme göstergesi, geri çağırma tamamlandığında otomatik olarak kapanır.

<div id="constructors"></div>

## Constructor'lar

`Pullable`, yaygın yapılandırmalar için adlandırılmış constructor'lar sağlar:

| Constructor | Başlık Stili | Açıklama |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Varsayılan constructor |
| `Pullable.classicHeader()` | Classic | Klasik çekerek yenileme stili |
| `Pullable.waterDropHeader()` | Water Drop | Su damlası animasyonu |
| `Pullable.materialClassicHeader()` | Material Classic | Material Design klasik stil |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Material su damlası stili |
| `Pullable.bezierHeader()` | Bezier | Bezier eğrisi animasyonu |
| `Pullable.noBounce()` | Yapılandırılabilir | `ClampingScrollPhysics` ile azaltılmış sıçrama |
| `Pullable.custom()` | Özel widget | Kendi başlık/altlık widget'larınızı kullanın |
| `Pullable.builder()` | Yapılandırılabilir | Tam `PullableConfig` kontrolü |

### Örnekler

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

Ayrıntılı kontrol için `Pullable.builder()` constructor'ı ile `PullableConfig` kullanın:

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

### Tüm Yapılandırma Seçenekleri

| Özellik | Tür | Varsayılan | Açıklama |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Aşağı çekerek yenilemeyi etkinleştir |
| `enablePullUp` | `bool` | `false` | Yukarı çekerek daha fazla yüklemeyi etkinleştir |
| `physics` | `ScrollPhysics?` | null | Özel kaydırma fiziği |
| `onRefresh` | `Future<void> Function()?` | null | Yenileme geri çağırması |
| `onLoading` | `Future<void> Function()?` | null | Daha fazla yükleme geri çağırması |
| `headerType` | `PullableHeaderType` | `waterDrop` | Başlık animasyon stili |
| `customHeader` | `Widget?` | null | Özel başlık widget'ı |
| `customFooter` | `Widget?` | null | Özel altlık widget'ı |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Yenileme tamamlanmadan önceki gecikme |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Yükleme tamamlanmadan önceki gecikme |
| `enableOverScroll` | `bool` | `true` | Aşırı kaydırma efektine izin ver |
| `cacheExtent` | `double?` | null | Kaydırma önbellek alanı |
| `semanticChildCount` | `int?` | null | Semantik alt öğe sayısı |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Sürükleme hareketlerinin nasıl başlayacağı |

<div id="header-styles"></div>

## Başlık Stilleri

Beş yerleşik başlık animasyonundan birini seçin:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Stili constructor veya yapılandırma üzerinden ayarlayın:

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

## Daha Fazla Yüklemek İçin Yukarı Çekme

Yukarı çekerek yükleme ile sayfalamayı etkinleştirin:

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

## Özel Başlıklar ve Altlıklar

Kendi başlık ve altlık widget'larınızı sağlayın:

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

Programatik kontrol için bir `RefreshController` kullanın:

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

### RefreshController Üzerindeki Extension Metotları

| Metot/Getter | Dönüş Türü | Açıklama |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Manuel olarak yenilemeyi tetikle |
| `triggerLoading()` | `void` | Manuel olarak daha fazla yüklemeyi tetikle |
| `isRefreshing` | `bool` | Yenileme aktif mi |
| `isLoading` | `bool` | Yükleme aktif mi |

<div id="extension-method"></div>

## Extension Metodu

Herhangi bir widget `.pullable()` extension'ı ile çekerek yenileme özelliğiyle sarılabilir:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Özel yapılandırma ile:

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

## CollectionView Entegrasyonu

`CollectionView`, yerleşik sayfalama ile pullable varyantları sağlar:

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

### Pullable'a Özel Parametreler

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Sayfalanmış veri geri çağırması (iteration 1'den başlar) |
| `onRefresh` | `Function()?` | Yenilemeden sonra geri çağırma |
| `beforeRefresh` | `Function()?` | Yenileme başlamadan önceki kanca |
| `afterRefresh` | `Function(dynamic)?` | Verilerle birlikte yenilemeden sonraki kanca |
| `headerStyle` | `String?` | Başlık türü adı (örn. `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Altlık için özel yükleme göstergesi |

<div id="examples"></div>

## Örnekler

### Yenilemeli Sayfalanmış Liste

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

### Extension ile Basit Yenileme

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
