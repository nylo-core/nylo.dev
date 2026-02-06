# Spacing

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [预设尺寸](#preset-sizes "预设尺寸")
- [自定义间距](#custom-spacing "自定义间距")
- [与 Slivers 一起使用](#slivers "与 Slivers 一起使用")


<div id="introduction"></div>

## 简介

**Spacing** 组件提供了一种简洁、可读的方式来在 UI 元素之间添加一致的间距。无需在代码中手动创建 `SizedBox` 实例，您可以使用带有语义化预设或自定义值的 `Spacing`。

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## 预设尺寸

`Spacing` 附带常用间距值的内置预设。这些预设有助于在整个应用中保持一致的间距。

### 垂直间距预设

在 `Column` 组件或任何需要垂直间距的地方使用：

| 预设 | 尺寸 | 用途 |
|--------|------|-------|
| `Spacing.zero` | 0px | 条件间距 |
| `Spacing.xs` | 4px | 超小 |
| `Spacing.sm` | 8px | 小 |
| `Spacing.md` | 16px | 中 |
| `Spacing.lg` | 24px | 大 |
| `Spacing.xl` | 32px | 超大 |

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

### 水平间距预设

在 `Row` 组件或任何需要水平间距的地方使用：

| 预设 | 尺寸 | 用途 |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | 超小 |
| `Spacing.smHorizontal` | 8px | 小 |
| `Spacing.mdHorizontal` | 16px | 中 |
| `Spacing.lgHorizontal` | 24px | 大 |
| `Spacing.xlHorizontal` | 32px | 超大 |

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

## 自定义间距

当预设不满足需求时，创建自定义间距：

### 垂直间距

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### 水平间距

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### 双维度

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### 示例

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

## 与 Slivers 一起使用

在使用 `CustomScrollView` 和 slivers 时，使用 `asSliver()` 方法：

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

`asSliver()` 方法将 `Spacing` 组件包装在 `SliverToBoxAdapter` 中，使其与基于 sliver 的布局兼容。

## 条件间距

使用 `Spacing.zero` 实现条件间距：

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
