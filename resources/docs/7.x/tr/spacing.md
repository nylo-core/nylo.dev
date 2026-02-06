# Spacing

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Önceden Tanımlı Boyutlar](#preset-sizes "Önceden Tanımlı Boyutlar")
- [Özel Boşluk](#custom-spacing "Özel Boşluk")
- [Sliver'larla Kullanım](#slivers "Sliver'larla Kullanım")


<div id="introduction"></div>

## Giriş

**Spacing** widget'ı, UI elemanları arasına tutarlı boşluk eklemenin temiz ve okunabilir bir yolunu sunar. Kodunuz boyunca manuel olarak `SizedBox` örnekleri oluşturmak yerine, `Spacing`'i anlamsal ön ayarlar veya özel değerlerle kullanabilirsiniz.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Önceden Tanımlı Boyutlar

`Spacing`, yaygın boşluk değerleri için yerleşik ön ayarlarla birlikte gelir. Bunlar uygulamanız boyunca tutarlı boşluk sağlamaya yardımcı olur.

### Dikey Boşluk Ön Ayarları

Bunları `Column` widget'larında veya dikey boşluğa ihtiyaç duyduğunuz herhangi bir yerde kullanın:

| Ön Ayar | Boyut | Kullanım |
|---------|-------|---------|
| `Spacing.zero` | 0px | Koşullu boşluk |
| `Spacing.xs` | 4px | Çok küçük |
| `Spacing.sm` | 8px | Küçük |
| `Spacing.md` | 16px | Orta |
| `Spacing.lg` | 24px | Büyük |
| `Spacing.xl` | 32px | Çok büyük |

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

### Yatay Boşluk Ön Ayarları

Bunları `Row` widget'larında veya yatay boşluğa ihtiyaç duyduğunuz herhangi bir yerde kullanın:

| Ön Ayar | Boyut | Kullanım |
|---------|-------|---------|
| `Spacing.xsHorizontal` | 4px | Çok küçük |
| `Spacing.smHorizontal` | 8px | Küçük |
| `Spacing.mdHorizontal` | 16px | Orta |
| `Spacing.lgHorizontal` | 24px | Büyük |
| `Spacing.xlHorizontal` | 32px | Çok büyük |

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

## Özel Boşluk

Ön ayarlar ihtiyaçlarınıza uymadığında, özel boşluk oluşturun:

### Dikey Boşluk

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Yatay Boşluk

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Her İki Boyut

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Örnek

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

## Sliver'larla Kullanım

`CustomScrollView` ve sliver'larla çalışırken, `asSliver()` metodunu kullanın:

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

`asSliver()` metodu, `Spacing` widget'ını bir `SliverToBoxAdapter` içine sararak sliver tabanlı düzenlerle uyumlu hale getirir.

## Koşullu Boşluk

Koşullu boşluk için `Spacing.zero` kullanın:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
