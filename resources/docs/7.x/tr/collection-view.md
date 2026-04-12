# CollectionView

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [CollectionItem Yardımcısı](#collection-item "CollectionItem Yardımcısı")
- Varyantlar
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Çekilebilir Varyantlar
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Yükleme Stilleri](#loading-styles "Yükleme Stilleri")
- [Boş Durum](#empty-state "Boş Durum")
- [Veri Sıralama ve Dönüştürme](#sorting-transforming "Veri Sıralama ve Dönüştürme")
- [Durumu Güncelleme](#updating-state "Durumu Güncelleme")
- [Parametre Referansı](#parameters "Parametre Referansı")


<div id="introduction"></div>

## Giriş

**CollectionView** widget'ı, {{ config('app.name') }} projelerinizde veri listelerini görüntülemek için güçlü, tip güvenli bir sarıcıdır. `ListView`, `ListView.separated` ve grid düzenleri ile çalışmayı kolaylaştırırken şunlar için yerleşik destek sağlar:

- Otomatik yükleme durumları ile asenkron veri yükleme
- Çekerek yenileme ve sayfalama
- Konum yardımcıları ile tip güvenli öğe oluşturucular
- Boş durum yönetimi
- Veri sıralama ve dönüştürme

<div id="basic-usage"></div>

## Temel Kullanım

Öğe listesi görüntüleyen basit bir örnek:

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

API'den asenkron veri ile:

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

## CollectionItem Yardımcısı

`builder` callback'i, verilerinizi kullanışlı konum yardımcıları ile saran bir `CollectionItem<T>` nesnesi alır:

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

### CollectionItem Özellikleri

| Özellik | Tür | Açıklama |
|----------|------|-------------|
| `data` | `T` | Gerçek öğe verisi |
| `index` | `int` | Listedeki mevcut indeks |
| `totalItems` | `int` | Toplam öğe sayısı |
| `isFirst` | `bool` | İlk öğe ise true |
| `isLast` | `bool` | Son öğe ise true |
| `isOdd` | `bool` | İndeks tek sayı ise true |
| `isEven` | `bool` | İndeks çift sayı ise true |
| `progress` | `double` | Liste içindeki ilerleme (0.0 - 1.0) |

### CollectionItem Metotları

| Metot | Açıklama |
|--------|-------------|
| `isAt(int position)` | Öğenin belirli bir konumda olup olmadığını kontrol eder |
| `isInRange(int start, int end)` | İndeksin aralık içinde olup olmadığını kontrol eder (dahil) |
| `isMultipleOf(int divisor)` | İndeksin bölenin katı olup olmadığını kontrol eder |

<div id="collection-view-basic"></div>

## CollectionView

Varsayılan kurucu standart bir liste görünümü oluşturur:

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
  spacing: 8.0, // Öğeler arasına boşluk ekle
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

Öğeler arasında ayırıcıları olan bir liste oluşturur:

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

Kademeli grid kullanarak bir grid düzeni oluşturur:

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

Çekerek yenileme ve sonsuz kaydırma sayfalamsı ile bir liste oluşturur:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iterasyon 1'den başlar ve her yüklemede artar
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
  headerStyle: 'WaterDropHeader', // Çekme göstergesi stili
)
```

### Başlık Stilleri

`headerStyle` parametresi şunları kabul eder:
- `'WaterDropHeader'` (varsayılan) - Su damlası animasyonu
- `'ClassicHeader'` - Klasik çekme göstergesi
- `'MaterialClassicHeader'` - Material tasarım stili
- `'WaterDropMaterialHeader'` - Material su damlası
- `'BezierHeader'` - Bezier eğri animasyonu

### Sayfalama Callback'leri

| Callback | Açıklama |
|----------|-------------|
| `beforeRefresh` | Yenileme başlamadan önce çağrılır |
| `onRefresh` | Yenileme tamamlandığında çağrılır |
| `afterRefresh` | Veri yüklendikten sonra çağrılır, dönüştürme için veri alır |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Çekerek yenilemeyi ayırıcılı liste ile birleştirir:

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

Çekerek yenilemeyi grid düzeni ile birleştirir:

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

## Yükleme Stilleri

`loadingStyle` kullanarak yükleme göstergesini özelleştirin:

``` dart
// Özel widget ile normal yükleme
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading items..."),
  ),
)

// Skeletonizer yükleme efekti
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

## Boş Durum

Liste boş olduğunda özel bir widget görüntüleyin:

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

## Veri Sıralama ve Dönüştürme

### Sıralama

Öğeleri görüntülemeden önce sıralayın:

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

### Dönüştürme

Yükleme sonrasında verileri dönüştürün:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // Yalnızca aktif kullanıcıları filtrele
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## Durumu Güncelleme

Bir CollectionView'a `stateName` vererek ve `CollectionView.stateActions()` çağırarak programatik olarak güncelleyebilirsiniz:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

Çalışma zamanında listeyi yönetmek için `stateActions` kullanın:

``` dart
final actions = CollectionView.stateActions("my_todo_list");

// Sıfırla ve verileri baştan yükle
actions.reset();

// Verileri yenile (yeniden çeker ve sayfalamayı sıfırlar)
actions.refreshData();

// Listenin sonuna bir öğe ekle
actions.addItem(newTodo);

// Belirli bir indekse öğe ekle
actions.insertItem(0, newTodo);

// İndekse göre bir öğeyi kaldır
actions.removeFromIndex(2);

// Belirli bir indeksteki öğeyi değiştir
actions.updateItemAtIndex(0, updatedTodo);
```

Tüm `stateActions` metotları hem senkron hem de asenkron veriler için yeniden oluşturmalar arasında doğru şekilde çalışır. `refreshData()` ayrıca sayfalama yineleme sayacını sıfırlar, böylece çekilebilir listeler 1. sayfadan yeniden başlar.

<div id="parameters"></div>

## Parametre Referansı

### Ortak Parametreler

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `data` | `Function()` | `List<T>` veya `Future<List<T>>` döndüren fonksiyon |
| `builder` | `CollectionItemBuilder<T>` | Her öğe için oluşturucu fonksiyon |
| `empty` | `Widget?` | Liste boş olduğunda gösterilen widget |
| `loadingStyle` | `LoadingStyle?` | Yükleme göstergesini özelleştirme |
| `header` | `Widget?` | Listenin üstündeki başlık widget'ı |
| `stateName` | `String?` | Durum yönetimi için ad |
| `sort` | `Function(List<T>)?` | Öğeler için sıralama fonksiyonu |
| `transform` | `Function(List<T>)?` | Veri için dönüştürme fonksiyonu |
| `spacing` | `double?` | Öğeler arası boşluk |

### Çekilebilir Özel Parametreler

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Sayfalanmış veri fonksiyonu |
| `enablePullDown` | `bool` | Çekerek yenileme hareketini etkinleştir (varsayılan: `true`) |
| `onRefresh` | `Function()?` | Yenileme tamamlandığında callback |
| `beforeRefresh` | `Function()?` | Yenilemeden önce callback |
| `afterRefresh` | `Function(dynamic)?` | Veri yüklendikten sonra callback |
| `headerStyle` | `String?` | Çekme göstergesi stili |
| `footerLoadingIcon` | `Widget?` | Sayfalama için özel yükleme göstergesi |

### Grid Özel Parametreleri

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `crossAxisCount` | `int` | Sütun sayısı (varsayılan: 2) |
| `mainAxisSpacing` | `double` | Öğeler arası dikey boşluk |
| `crossAxisSpacing` | `double` | Öğeler arası yatay boşluk |

### ListView Parametreleri

Tüm standart `ListView` parametreleri de desteklenir: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` ve daha fazlası.
