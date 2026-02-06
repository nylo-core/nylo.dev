# CollectionView

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Temel Kullan&#305;m](#basic-usage "Temel Kullan&#305;m")
- [CollectionItem Yard&#305;mc&#305;s&#305;](#collection-item "CollectionItem Yard&#305;mc&#305;s&#305;")
- Varyantlar
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- &#199;ekilebilir Varyantlar
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Y&#252;kleme Stilleri](#loading-styles "Y&#252;kleme Stilleri")
- [Bo&#351; Durum](#empty-state "Bo&#351; Durum")
- [Veri S&#305;ralama ve D&#246;n&#252;&#351;t&#252;rme](#sorting-transforming "Veri S&#305;ralama ve D&#246;n&#252;&#351;t&#252;rme")
- [Durumu G&#252;ncelleme](#updating-state "Durumu G&#252;ncelleme")
- [Parametre Referans&#305;](#parameters "Parametre Referans&#305;")


<div id="introduction"></div>

## Giri&#351;

**CollectionView** widget'&#305;, {{ config('app.name') }} projelerinizde veri listelerini g&#246;r&#252;nt&#252;lemek i&#231;in g&#252;&#231;l&#252;, tip g&#252;venli bir sar&#305;c&#305;d&#305;r. `ListView`, `ListView.separated` ve grid d&#252;zenleri ile &#231;al&#305;&#351;may&#305; kolayla&#351;t&#305;r&#305;rken &#351;unlar i&#231;in yerle&#351;ik destek sa&#287;lar:

- Otomatik y&#252;kleme durumlar&#305; ile asenkron veri y&#252;kleme
- &#199;ekerek yenileme ve sayfalama
- Konum yard&#305;mc&#305;lar&#305; ile tip g&#252;venli &#246;&#287;e olu&#351;turucular
- Bo&#351; durum y&#246;netimi
- Veri s&#305;ralama ve d&#246;n&#252;&#351;t&#252;rme

<div id="basic-usage"></div>

## Temel Kullan&#305;m

&#214;&#287;e listesi g&#246;r&#252;nt&#252;leyen basit bir &#246;rnek:

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

## CollectionItem Yard&#305;mc&#305;s&#305;

`builder` callback'i, verilerinizi kullan&#305;&#351;l&#305; konum yard&#305;mc&#305;lar&#305; ile saran bir `CollectionItem<T>` nesnesi al&#305;r:

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

### CollectionItem &#214;zellikleri

| &#214;zellik | T&#252;r | A&#231;&#305;klama |
|----------|------|-------------|
| `data` | `T` | Ger&#231;ek &#246;&#287;e verisi |
| `index` | `int` | Listedeki mevcut indeks |
| `totalItems` | `int` | Toplam &#246;&#287;e say&#305;s&#305; |
| `isFirst` | `bool` | &#304;lk &#246;&#287;e ise true |
| `isLast` | `bool` | Son &#246;&#287;e ise true |
| `isOdd` | `bool` | &#304;ndeks tek say&#305; ise true |
| `isEven` | `bool` | &#304;ndeks &#231;ift say&#305; ise true |
| `progress` | `double` | Liste i&#231;indeki ilerleme (0.0 - 1.0) |

### CollectionItem Metotlar&#305;

| Metot | A&#231;&#305;klama |
|--------|-------------|
| `isAt(int position)` | &#214;&#287;enin belirli bir konumda olup olmad&#305;&#287;&#305;n&#305; kontrol eder |
| `isInRange(int start, int end)` | &#304;ndeksin aral&#305;k i&#231;inde olup olmad&#305;&#287;&#305;n&#305; kontrol eder (dahil) |
| `isMultipleOf(int divisor)` | &#304;ndeksin b&#246;len&#305;n kat&#305; olup olmad&#305;&#287;&#305;n&#305; kontrol eder |

<div id="collection-view-basic"></div>

## CollectionView

Varsay&#305;lan kurucu standart bir liste g&#246;r&#252;n&#252;m&#252; olu&#351;turur:

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

&#214;&#287;eler aras&#305;nda ay&#305;r&#305;c&#305;lar&#305; olan bir liste olu&#351;turur:

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

Kademeli grid kullanarak bir grid d&#252;zeni olu&#351;turur:

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

&#199;ekerek yenileme ve sonsuz kayd&#305;rma sayfalamas&#305; ile bir liste olu&#351;turur:

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

### Ba&#351;l&#305;k Stilleri

`headerStyle` parametresi &#351;unlar&#305; kabul eder:
- `'WaterDropHeader'` (varsay&#305;lan) - Su damlas&#305; animasyonu
- `'ClassicHeader'` - Klasik &#231;ekme g&#246;stergesi
- `'MaterialClassicHeader'` - Material tasar&#305;m stili
- `'WaterDropMaterialHeader'` - Material su damlas&#305;
- `'BezierHeader'` - Bezier e&#287;ri animasyonu

### Sayfalama Callback'leri

| Callback | A&#231;&#305;klama |
|----------|-------------|
| `beforeRefresh` | Yenileme ba&#351;lamadan &#246;nce &#231;a&#287;r&#305;l&#305;r |
| `onRefresh` | Yenileme tamamland&#305;&#287;&#305;nda &#231;a&#287;r&#305;l&#305;r |
| `afterRefresh` | Veri y&#252;klendikten sonra &#231;a&#287;r&#305;l&#305;r, d&#246;n&#252;&#351;t&#252;rme i&#231;in veri al&#305;r |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

&#199;ekerek yenilemeyi ay&#305;r&#305;c&#305;l&#305; liste ile birle&#351;tirir:

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

&#199;ekerek yenilemeyi grid d&#252;zeni ile birle&#351;tirir:

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

## Y&#252;kleme Stilleri

`loadingStyle` kullanarak y&#252;kleme g&#246;stergesini &#246;zelle&#351;tirin:

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

## Bo&#351; Durum

Liste bo&#351; oldu&#287;unda &#246;zel bir widget g&#246;r&#252;nt&#252;leyin:

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

## Veri S&#305;ralama ve D&#246;n&#252;&#351;t&#252;rme

### S&#305;ralama

&#214;&#287;eleri g&#246;r&#252;nt&#252;lemeden &#246;nce s&#305;ralay&#305;n:

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

### D&#246;n&#252;&#351;t&#252;rme

Y&#252;kleme sonras&#305;nda verileri d&#246;n&#252;&#351;t&#252;r&#252;n:

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

## Durumu G&#252;ncelleme

Bir CollectionView'a `stateName` vererek g&#252;ncelleyebilir veya s&#305;f&#305;rlayabilirsiniz:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Listeyi s&#305;f&#305;rlama

``` dart
// Resets and reloads data from scratch
CollectionView.stateReset("my_todo_list");
```

### Bir &#246;&#287;eyi kald&#305;rma

``` dart
// Remove item at index 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Genel g&#252;ncelleme tetikleme

``` dart
// Using the global updateState helper
updateState("my_todo_list");
```

<div id="parameters"></div>

## Parametre Referans&#305;

### Ortak Parametreler

| Parametre | T&#252;r | A&#231;&#305;klama |
|-----------|------|-------------|
| `data` | `Function()` | `List<T>` veya `Future<List<T>>` d&#246;nd&#252;ren fonksiyon |
| `builder` | `CollectionItemBuilder<T>` | Her &#246;&#287;e i&#231;in olu&#351;turucu fonksiyon |
| `empty` | `Widget?` | Liste bo&#351; oldu&#287;unda g&#246;sterilen widget |
| `loadingStyle` | `LoadingStyle?` | Y&#252;kleme g&#246;stergesini &#246;zelle&#351;tirme |
| `header` | `Widget?` | Listenin &#252;st&#252;ndeki ba&#351;l&#305;k widget'&#305; |
| `stateName` | `String?` | Durum y&#246;netimi i&#231;in ad |
| `sort` | `Function(List<T>)?` | &#214;&#287;eler i&#231;in s&#305;ralama fonksiyonu |
| `transform` | `Function(List<T>)?` | Veri i&#231;in d&#246;n&#252;&#351;t&#252;rme fonksiyonu |
| `spacing` | `double?` | &#214;&#287;eler aras&#305; bo&#351;luk |

### &#199;ekilebilir &#214;zel Parametreler

| Parametre | T&#252;r | A&#231;&#305;klama |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Sayfalanm&#305;&#351; veri fonksiyonu |
| `onRefresh` | `Function()?` | Yenileme tamamland&#305;&#287;&#305;nda callback |
| `beforeRefresh` | `Function()?` | Yenilemeden &#246;nce callback |
| `afterRefresh` | `Function(dynamic)?` | Veri y&#252;klendikten sonra callback |
| `headerStyle` | `String?` | &#199;ekme g&#246;stergesi stili |
| `footerLoadingIcon` | `Widget?` | Sayfalama i&#231;in &#246;zel y&#252;kleme g&#246;stergesi |

### Grid &#214;zel Parametreleri

| Parametre | T&#252;r | A&#231;&#305;klama |
|-----------|------|-------------|
| `crossAxisCount` | `int` | S&#252;tun say&#305;s&#305; (varsay&#305;lan: 2) |
| `mainAxisSpacing` | `double` | &#214;&#287;eler aras&#305; dikey bo&#351;luk |
| `crossAxisSpacing` | `double` | &#214;&#287;eler aras&#305; yatay bo&#351;luk |

### ListView Parametreleri

T&#252;m standart `ListView` parametreleri de desteklenir: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` ve daha fazlas&#305;.
