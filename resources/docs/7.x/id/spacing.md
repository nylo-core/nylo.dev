# Spacing

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Ukuran Preset](#preset-sizes "Ukuran Preset")
- [Spacing Kustom](#custom-spacing "Spacing Kustom")
- [Menggunakan dengan Sliver](#slivers "Menggunakan dengan Sliver")


<div id="introduction"></div>

## Pengantar

Widget **Spacing** menyediakan cara yang bersih dan mudah dibaca untuk menambahkan jarak yang konsisten antar elemen UI. Alih-alih membuat instance `SizedBox` secara manual di seluruh kode Anda, Anda dapat menggunakan `Spacing` dengan preset semantik atau nilai kustom.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Ukuran Preset

`Spacing` dilengkapi dengan preset bawaan untuk nilai jarak umum. Ini membantu menjaga jarak yang konsisten di seluruh aplikasi Anda.

### Preset Spacing Vertikal

Gunakan ini di widget `Column` atau di mana pun Anda membutuhkan ruang vertikal:

| Preset | Ukuran | Penggunaan |
|--------|------|-------|
| `Spacing.zero` | 0px | Spacing kondisional |
| `Spacing.xs` | 4px | Ekstra kecil |
| `Spacing.sm` | 8px | Kecil |
| `Spacing.md` | 16px | Sedang |
| `Spacing.lg` | 24px | Besar |
| `Spacing.xl` | 32px | Ekstra besar |

``` dart
Column(
  children: [
    Text("Title"),
    Spacing.sm,
    Text("Subtitle"),
    Spacing.lg,
    Text("Body content"),
    Spacing.xl,
    ElevatedButton(
      onPressed: () {},
      child: Text("Action"),
    ),
  ],
)
```

### Preset Spacing Horizontal

Gunakan ini di widget `Row` atau di mana pun Anda membutuhkan ruang horizontal:

| Preset | Ukuran | Penggunaan |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Ekstra kecil |
| `Spacing.smHorizontal` | 8px | Kecil |
| `Spacing.mdHorizontal` | 16px | Sedang |
| `Spacing.lgHorizontal` | 24px | Besar |
| `Spacing.xlHorizontal` | 32px | Ekstra besar |

``` dart
Row(
  children: [
    Icon(Icons.star),
    Spacing.smHorizontal,
    Text("Rating"),
    Spacing.lgHorizontal,
    Text("5.0"),
  ],
)
```

<div id="custom-spacing"></div>

## Spacing Kustom

Ketika preset tidak sesuai dengan kebutuhan Anda, buat spacing kustom:

### Spacing Vertikal

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Spacing Horizontal

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Kedua Dimensi

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Contoh

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // Custom 40px gap
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // Custom 12px gap
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## Menggunakan dengan Sliver

Saat bekerja dengan `CustomScrollView` dan sliver, gunakan metode `asSliver()`:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // Converts to SliverToBoxAdapter
    SliverList(
      delegate: SliverChildBuilderDelegate(
        (context, index) => ListTile(title: Text("Item $index")),
        childCount: 10,
      ),
    ),
    Spacing.xl.asSliver(),
    SliverToBoxAdapter(
      child: Text("Footer"),
    ),
  ],
)
```

Metode `asSliver()` membungkus widget `Spacing` dalam `SliverToBoxAdapter`, membuatnya kompatibel dengan layout berbasis sliver.

## Spacing Kondisional

Gunakan `Spacing.zero` untuk spacing kondisional:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
