# CollectionView

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Helper CollectionItem](#collection-item "Helper CollectionItem")
- Varian
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Varian Pullable
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [Gaya Loading](#loading-styles "Gaya Loading")
- [Status Kosong](#empty-state "Status Kosong")
- [Mengurutkan dan Mentransformasi Data](#sorting-transforming "Mengurutkan dan Mentransformasi Data")
- [Memperbarui State](#updating-state "Memperbarui State")
- [Referensi Parameter](#parameters "Referensi Parameter")


<div id="introduction"></div>

## Pengantar

Widget **CollectionView** adalah pembungkus yang kuat dan type-safe untuk menampilkan daftar data di proyek {{ config('app.name') }} Anda. Widget ini menyederhanakan bekerja dengan `ListView`, `ListView.separated`, dan tata letak grid sambil menyediakan dukungan bawaan untuk:

- Pemuatan data async dengan status loading otomatis
- Pull-to-refresh dan paginasi
- Builder item type-safe dengan helper posisi
- Penanganan status kosong
- Pengurutan dan transformasi data

<div id="basic-usage"></div>

## Penggunaan Dasar

Berikut contoh sederhana menampilkan daftar item:

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

Dengan data async dari API:

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

Callback `builder` menerima objek `CollectionItem<T>` yang membungkus data Anda dengan helper posisi yang berguna:

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

### Properti CollectionItem

| Properti | Tipe | Deskripsi |
|----------|------|-----------|
| `data` | `T` | Data item yang sebenarnya |
| `index` | `int` | Indeks saat ini dalam daftar |
| `totalItems` | `int` | Jumlah total item |
| `isFirst` | `bool` | True jika ini adalah item pertama |
| `isLast` | `bool` | True jika ini adalah item terakhir |
| `isOdd` | `bool` | True jika indeks ganjil |
| `isEven` | `bool` | True jika indeks genap |
| `progress` | `double` | Progres melalui daftar (0.0 sampai 1.0) |

### Method CollectionItem

| Method | Deskripsi |
|--------|-----------|
| `isAt(int position)` | Memeriksa apakah item berada di posisi tertentu |
| `isInRange(int start, int end)` | Memeriksa apakah indeks berada dalam rentang (inklusif) |
| `isMultipleOf(int divisor)` | Memeriksa apakah indeks adalah kelipatan dari pembagi |

<div id="collection-view-basic"></div>

## CollectionView

Konstruktor default membuat tampilan daftar standar:

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
  spacing: 8.0, // Tambahkan jarak antar item
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

Membuat daftar dengan pemisah antar item:

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

Membuat tata letak grid menggunakan staggered grid:

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

Membuat daftar dengan pull-to-refresh dan paginasi scroll tak terbatas:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iteration dimulai dari 1 dan bertambah setiap pemuatan
    return await api<ApiService>((request) =>
      request.get('/posts?page=$iteration')
    );
  },
  builder: (context, item) {
    return PostCard(post: item.data);
  },
  onRefresh: () {
    print('Daftar telah di-refresh!');
  },
  headerStyle: 'WaterDropHeader', // Gaya indikator pull
)
```

### Gaya Header

Parameter `headerStyle` menerima:
- `'WaterDropHeader'` (default) - Animasi tetesan air
- `'ClassicHeader'` - Indikator pull klasik
- `'MaterialClassicHeader'` - Gaya Material Design
- `'WaterDropMaterialHeader'` - Tetesan air Material
- `'BezierHeader'` - Animasi kurva Bezier

### Callback Paginasi

| Callback | Deskripsi |
|----------|-----------|
| `beforeRefresh` | Dipanggil sebelum refresh dimulai |
| `onRefresh` | Dipanggil saat refresh selesai |
| `afterRefresh` | Dipanggil setelah data dimuat, menerima data untuk transformasi |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

Menggabungkan pull-to-refresh dengan daftar terpisah:

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

Menggabungkan pull-to-refresh dengan tata letak grid:

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

## Gaya Loading

Sesuaikan indikator loading menggunakan `loadingStyle`:

``` dart
// Loading normal dengan widget kustom
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("Memuat item..."),
  ),
)

// Efek loading skeletonizer
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

## Status Kosong

Tampilkan widget kustom saat daftar kosong:

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
        Text('Tidak ada item ditemukan'),
      ],
    ),
  ),
)
```

<div id="sorting-transforming"></div>

## Mengurutkan dan Mentransformasi Data

### Pengurutan

Urutkan item sebelum ditampilkan:

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

### Transformasi

Transformasi data setelah dimuat:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // Filter hanya pengguna aktif
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## Memperbarui State

Anda dapat memperbarui atau mereset CollectionView dengan memberikannya `stateName`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### Mereset daftar

``` dart
// Mereset dan memuat ulang data dari awal
CollectionView.stateReset("my_todo_list");
```

### Menghapus item

``` dart
// Hapus item di indeks 2
CollectionView.removeFromIndex("my_todo_list", 2);
```

### Memicu pembaruan umum

``` dart
// Menggunakan helper updateState global
updateState("my_todo_list");
```

<div id="parameters"></div>

## Referensi Parameter

### Parameter Umum

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `data` | `Function()` | Fungsi yang mengembalikan `List<T>` atau `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | Fungsi builder untuk setiap item |
| `empty` | `Widget?` | Widget yang ditampilkan saat daftar kosong |
| `loadingStyle` | `LoadingStyle?` | Sesuaikan indikator loading |
| `header` | `Widget?` | Widget header di atas daftar |
| `stateName` | `String?` | Nama untuk manajemen state |
| `sort` | `Function(List<T>)?` | Fungsi pengurutan untuk item |
| `transform` | `Function(List<T>)?` | Fungsi transformasi untuk data |
| `spacing` | `double?` | Jarak antar item |

### Parameter Khusus Pullable

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `data` | `Function(int iteration)` | Fungsi data terpaginasi |
| `onRefresh` | `Function()?` | Callback saat refresh selesai |
| `beforeRefresh` | `Function()?` | Callback sebelum refresh |
| `afterRefresh` | `Function(dynamic)?` | Callback setelah data dimuat |
| `headerStyle` | `String?` | Gaya indikator pull |
| `footerLoadingIcon` | `Widget?` | Indikator loading kustom untuk paginasi |

### Parameter Khusus Grid

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `crossAxisCount` | `int` | Jumlah kolom (default: 2) |
| `mainAxisSpacing` | `double` | Jarak vertikal antar item |
| `crossAxisSpacing` | `double` | Jarak horizontal antar item |

### Parameter ListView

Semua parameter `ListView` standar juga didukung: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent`, dan lainnya.
