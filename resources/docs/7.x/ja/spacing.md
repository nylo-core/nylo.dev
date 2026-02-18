# Spacing

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [プリセットサイズ](#preset-sizes "プリセットサイズ")
- [カスタムスペーシング](#custom-spacing "カスタムスペーシング")
- [Sliver での使用](#slivers "Sliver での使用")


<div id="introduction"></div>

## はじめに

**Spacing** ウィジェットは、UI 要素間に一貫したスペーシングを追加するためのクリーンで読みやすい方法を提供します。コード全体で手動で `SizedBox` インスタンスを作成する代わりに、セマンティックなプリセットまたはカスタム値で `Spacing` を使用できます。

``` dart
// こう書く代わりに:
SizedBox(height: 16),

// こう書けます:
Spacing.md,
```

<div id="preset-sizes"></div>

## プリセットサイズ

`Spacing` には一般的なスペーシング値のプリセットが組み込まれています。これにより、アプリ全体で一貫したスペーシングを維持できます。

### 垂直スペーシングプリセット

`Column` ウィジェットや垂直スペースが必要な場所で使用します:

| プリセット | サイズ | 用途 |
|--------|------|-------|
| `Spacing.zero` | 0px | 条件付きスペーシング |
| `Spacing.xs` | 4px | 極小 |
| `Spacing.sm` | 8px | 小 |
| `Spacing.md` | 16px | 中 |
| `Spacing.lg` | 24px | 大 |
| `Spacing.xl` | 32px | 極大 |

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

### 水平スペーシングプリセット

`Row` ウィジェットや水平スペースが必要な場所で使用します:

| プリセット | サイズ | 用途 |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | 極小 |
| `Spacing.smHorizontal` | 8px | 小 |
| `Spacing.mdHorizontal` | 16px | 中 |
| `Spacing.lgHorizontal` | 24px | 大 |
| `Spacing.xlHorizontal` | 32px | 極大 |

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

## カスタムスペーシング

プリセットが合わない場合は、カスタムスペーシングを作成できます:

### 垂直スペーシング

``` dart
Spacing.vertical(12) // 12 論理ピクセルの垂直スペース
```

### 水平スペーシング

``` dart
Spacing.horizontal(20) // 20 論理ピクセルの水平スペース
```

### 両方の次元

``` dart
Spacing(width: 10, height: 20) // カスタム幅と高さ
```

### 例

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // カスタム 40px ギャップ
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // カスタム 12px ギャップ
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## Sliver での使用

`CustomScrollView` と Sliver を使用する場合は、`asSliver()` メソッドを使用します:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // SliverToBoxAdapter に変換
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

`asSliver()` メソッドは `Spacing` ウィジェットを `SliverToBoxAdapter` でラップし、Sliver ベースのレイアウトと互換性を持たせます。

## 条件付きスペーシング

条件付きスペーシングには `Spacing.zero` を使用します:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
