# Spacing

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Предустановленные размеры](#preset-sizes "Предустановленные размеры")
- [Пользовательские отступы](#custom-spacing "Пользовательские отступы")
- [Использование со Sliver](#slivers "Использование со Sliver")


<div id="introduction"></div>

## Введение

Виджет **Spacing** предоставляет чистый и читаемый способ добавления единообразных отступов между элементами UI. Вместо ручного создания экземпляров `SizedBox` по всему коду, вы можете использовать `Spacing` с семантическими предустановками или пользовательскими значениями.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Предустановленные размеры

`Spacing` поставляется со встроенными предустановками для типовых значений отступов. Они помогают поддерживать единообразные отступы по всему приложению.

### Предустановки вертикальных отступов

Используйте их в виджетах `Column` или везде, где нужно вертикальное пространство:

| Предустановка | Размер | Использование |
|--------|------|-------|
| `Spacing.zero` | 0px | Условные отступы |
| `Spacing.xs` | 4px | Сверхмалый |
| `Spacing.sm` | 8px | Малый |
| `Spacing.md` | 16px | Средний |
| `Spacing.lg` | 24px | Большой |
| `Spacing.xl` | 32px | Сверхбольшой |

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

### Предустановки горизонтальных отступов

Используйте их в виджетах `Row` или везде, где нужно горизонтальное пространство:

| Предустановка | Размер | Использование |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Сверхмалый |
| `Spacing.smHorizontal` | 8px | Малый |
| `Spacing.mdHorizontal` | 16px | Средний |
| `Spacing.lgHorizontal` | 24px | Большой |
| `Spacing.xlHorizontal` | 32px | Сверхбольшой |

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

## Пользовательские отступы

Когда предустановки не подходят, создавайте пользовательские отступы:

### Вертикальный отступ

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Горизонтальный отступ

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Оба измерения

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Пример

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

## Использование со Sliver

При работе с `CustomScrollView` и sliver используйте метод `asSliver()`:

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

Метод `asSliver()` оборачивает виджет `Spacing` в `SliverToBoxAdapter`, делая его совместимым с макетами на основе sliver.

## Условные отступы

Используйте `Spacing.zero` для условных отступов:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
