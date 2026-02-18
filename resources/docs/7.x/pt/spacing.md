# Spacing

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Tamanhos Predefinidos](#preset-sizes "Tamanhos Predefinidos")
- [Espaçamento Personalizado](#custom-spacing "Espaçamento Personalizado")
- [Usando com Slivers](#slivers "Usando com Slivers")


<div id="introduction"></div>

## Introdução

O widget **Spacing** fornece uma maneira limpa e legível de adicionar espaçamento consistente entre elementos da UI. Em vez de criar manualmente instâncias de `SizedBox` em todo o seu código, você pode usar `Spacing` com presets semânticos ou valores personalizados.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Tamanhos Predefinidos

O `Spacing` vem com presets integrados para valores de espaçamento comuns. Eles ajudam a manter um espaçamento consistente em todo o seu app.

### Presets de Espaçamento Vertical

Use estes em widgets `Column` ou em qualquer lugar que precise de espaço vertical:

| Preset | Tamanho | Uso |
|--------|------|-------|
| `Spacing.zero` | 0px | Espaçamento condicional |
| `Spacing.xs` | 4px | Extra pequeno |
| `Spacing.sm` | 8px | Pequeno |
| `Spacing.md` | 16px | Médio |
| `Spacing.lg` | 24px | Grande |
| `Spacing.xl` | 32px | Extra grande |

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

### Presets de Espaçamento Horizontal

Use estes em widgets `Row` ou em qualquer lugar que precise de espaço horizontal:

| Preset | Tamanho | Uso |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Extra pequeno |
| `Spacing.smHorizontal` | 8px | Pequeno |
| `Spacing.mdHorizontal` | 16px | Médio |
| `Spacing.lgHorizontal` | 24px | Grande |
| `Spacing.xlHorizontal` | 32px | Extra grande |

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

## Espaçamento Personalizado

Quando os presets não atendem às suas necessidades, crie espaçamentos personalizados:

### Espaçamento Vertical

``` dart
Spacing.vertical(12) // 12 pixels lógicos de espaço vertical
```

### Espaçamento Horizontal

``` dart
Spacing.horizontal(20) // 20 pixels lógicos de espaço horizontal
```

### Ambas as Dimensões

``` dart
Spacing(width: 10, height: 20) // Largura e altura personalizadas
```

### Exemplo

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

## Usando com Slivers

Ao trabalhar com `CustomScrollView` e slivers, use o método `asSliver()`:

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

O método `asSliver()` envolve o widget `Spacing` em um `SliverToBoxAdapter`, tornando-o compatível com layouts baseados em slivers.

## Espaçamento Condicional

Use `Spacing.zero` para espaçamento condicional:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
