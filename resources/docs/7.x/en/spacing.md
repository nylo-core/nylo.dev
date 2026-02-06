# Spacing

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Preset Sizes](#preset-sizes "Preset Sizes")
- [Custom Spacing](#custom-spacing "Custom Spacing")
- [Using with Slivers](#slivers "Using with Slivers")


<div id="introduction"></div>

## Introduction

The **Spacing** widget provides a clean, readable way to add consistent spacing between UI elements. Instead of manually creating `SizedBox` instances throughout your code, you can use `Spacing` with semantic presets or custom values.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Preset Sizes

`Spacing` comes with built-in presets for common spacing values. These help maintain consistent spacing throughout your app.

### Vertical Spacing Presets

Use these in `Column` widgets or anywhere you need vertical space:

| Preset | Size | Usage |
|--------|------|-------|
| `Spacing.zero` | 0px | Conditional spacing |
| `Spacing.xs` | 4px | Extra small |
| `Spacing.sm` | 8px | Small |
| `Spacing.md` | 16px | Medium |
| `Spacing.lg` | 24px | Large |
| `Spacing.xl` | 32px | Extra large |

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

### Horizontal Spacing Presets

Use these in `Row` widgets or anywhere you need horizontal space:

| Preset | Size | Usage |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Extra small |
| `Spacing.smHorizontal` | 8px | Small |
| `Spacing.mdHorizontal` | 16px | Medium |
| `Spacing.lgHorizontal` | 24px | Large |
| `Spacing.xlHorizontal` | 32px | Extra large |

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

## Custom Spacing

When presets don't fit your needs, create custom spacing:

### Vertical Spacing

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Horizontal Spacing

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Both Dimensions

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Example

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

## Using with Slivers

When working with `CustomScrollView` and slivers, use the `asSliver()` method:

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

The `asSliver()` method wraps the `Spacing` widget in a `SliverToBoxAdapter`, making it compatible with sliver-based layouts.

## Conditional Spacing

Use `Spacing.zero` for conditional spacing:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
