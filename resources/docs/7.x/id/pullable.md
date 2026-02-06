# Pullable

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Konstruktor](#constructors "Konstruktor")
- [PullableConfig](#pullable-config "PullableConfig")
- [Gaya Header](#header-styles "Gaya Header")
- [Tarik Atas untuk Muat Lebih Banyak](#pull-up "Tarik Atas untuk Muat Lebih Banyak")
- [Header dan Footer Kustom](#custom-headers "Header dan Footer Kustom")
- [Controller](#controller "Controller")
- [Metode Extension](#extension-method "Metode Extension")
- [Integrasi CollectionView](#collection-view "Integrasi CollectionView")
- [Contoh](#examples "Contoh Praktis")

<div id="introduction"></div>

## Pengantar

Widget **Pullable** menambahkan fungsionalitas tarik-untuk-refresh dan muat-lebih-banyak ke konten scrollable apa pun. Widget ini membungkus child widget Anda dengan perilaku refresh dan paginasi berbasis gesture, mendukung beberapa gaya animasi header.

Dibangun di atas paket `pull_to_refresh_flutter3`, Pullable menyediakan API yang bersih dengan konstruktor bernama untuk konfigurasi umum.

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

## Penggunaan Dasar

Bungkus widget scrollable apa pun dengan `Pullable`:

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

Ketika pengguna menarik ke bawah pada daftar, callback `onRefresh` akan dipanggil. Indikator refresh secara otomatis selesai ketika callback selesai.

<div id="constructors"></div>

## Konstruktor

`Pullable` menyediakan konstruktor bernama untuk konfigurasi umum:

| Konstruktor | Gaya Header | Deskripsi |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Konstruktor default |
| `Pullable.classicHeader()` | Classic | Gaya tarik-untuk-refresh klasik |
| `Pullable.waterDropHeader()` | Water Drop | Animasi tetesan air |
| `Pullable.materialClassicHeader()` | Material Classic | Gaya klasik Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Gaya tetesan air Material |
| `Pullable.bezierHeader()` | Bezier | Animasi kurva Bezier |
| `Pullable.noBounce()` | Dapat dikonfigurasi | Pantulan berkurang dengan `ClampingScrollPhysics` |
| `Pullable.custom()` | Widget kustom | Gunakan widget header/footer Anda sendiri |
| `Pullable.builder()` | Dapat dikonfigurasi | Kontrol penuh `PullableConfig` |

### Contoh

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

Untuk kontrol yang lebih detail, gunakan `PullableConfig` dengan konstruktor `Pullable.builder()`:

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

### Semua Opsi Konfigurasi

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Mengaktifkan tarik-turun-untuk-refresh |
| `enablePullUp` | `bool` | `false` | Mengaktifkan tarik-naik-untuk-muat-lebih |
| `physics` | `ScrollPhysics?` | null | Fisika scroll kustom |
| `onRefresh` | `Future<void> Function()?` | null | Callback refresh |
| `onLoading` | `Future<void> Function()?` | null | Callback muat-lebih |
| `headerType` | `PullableHeaderType` | `waterDrop` | Gaya animasi header |
| `customHeader` | `Widget?` | null | Widget header kustom |
| `customFooter` | `Widget?` | null | Widget footer kustom |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Jeda sebelum refresh selesai |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Jeda sebelum loading selesai |
| `enableOverScroll` | `bool` | `true` | Mengizinkan efek over-scroll |
| `cacheExtent` | `double?` | null | Jangkauan cache scroll |
| `semanticChildCount` | `int?` | null | Jumlah child semantik |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Cara gesture tarik dimulai |

<div id="header-styles"></div>

## Gaya Header

Pilih dari lima animasi header bawaan:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Atur gaya melalui konstruktor atau config:

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

## Tarik Atas untuk Muat Lebih Banyak

Aktifkan paginasi dengan pemuatan tarik-atas:

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

## Header dan Footer Kustom

Sediakan widget header dan footer Anda sendiri:

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

Gunakan `RefreshController` untuk kontrol programatik:

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

### Metode Extension pada RefreshController

| Metode/Getter | Tipe Kembalian | Deskripsi |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Memicu refresh secara manual |
| `triggerLoading()` | `void` | Memicu muat-lebih secara manual |
| `isRefreshing` | `bool` | Apakah refresh sedang aktif |
| `isLoading` | `bool` | Apakah loading sedang aktif |

<div id="extension-method"></div>

## Metode Extension

Widget apa pun dapat dibungkus dengan tarik-untuk-refresh menggunakan extension `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Dengan config kustom:

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

## Integrasi CollectionView

`CollectionView` menyediakan varian pullable dengan paginasi bawaan:

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

### Parameter Khusus Pullable

| Parameter | Tipe | Deskripsi |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback data berpaginasi (iterasi dimulai dari 1) |
| `onRefresh` | `Function()?` | Callback setelah refresh |
| `beforeRefresh` | `Function()?` | Hook sebelum refresh dimulai |
| `afterRefresh` | `Function(dynamic)?` | Hook setelah refresh dengan data |
| `headerStyle` | `String?` | Nama tipe header (misalnya, `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Indikator loading kustom untuk footer |

<div id="examples"></div>

## Contoh

### Daftar Berpaginasi dengan Refresh

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

### Refresh Sederhana dengan Extension

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
