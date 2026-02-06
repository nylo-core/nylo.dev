# Spacing

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Tailles predefinies](#preset-sizes "Tailles predefinies")
- [Espacement personnalise](#custom-spacing "Espacement personnalise")
- [Utilisation avec les Slivers](#slivers "Utilisation avec les Slivers")


<div id="introduction"></div>

## Introduction

Le widget **Spacing** offre un moyen propre et lisible d'ajouter un espacement coherent entre les elements de l'interface. Au lieu de creer manuellement des instances de `SizedBox` dans votre code, vous pouvez utiliser `Spacing` avec des presets semantiques ou des valeurs personnalisees.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Tailles predefinies

`Spacing` est livre avec des presets integres pour les valeurs d'espacement courantes. Ceux-ci aident a maintenir un espacement coherent dans toute votre application.

### Presets d'espacement vertical

Utilisez-les dans les widgets `Column` ou partout ou vous avez besoin d'espace vertical :

| Preset | Taille | Utilisation |
|--------|--------|-------------|
| `Spacing.zero` | 0px | Espacement conditionnel |
| `Spacing.xs` | 4px | Tres petit |
| `Spacing.sm` | 8px | Petit |
| `Spacing.md` | 16px | Moyen |
| `Spacing.lg` | 24px | Grand |
| `Spacing.xl` | 32px | Tres grand |

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

### Presets d'espacement horizontal

Utilisez-les dans les widgets `Row` ou partout ou vous avez besoin d'espace horizontal :

| Preset | Taille | Utilisation |
|--------|--------|-------------|
| `Spacing.xsHorizontal` | 4px | Tres petit |
| `Spacing.smHorizontal` | 8px | Petit |
| `Spacing.mdHorizontal` | 16px | Moyen |
| `Spacing.lgHorizontal` | 24px | Grand |
| `Spacing.xlHorizontal` | 32px | Tres grand |

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

## Espacement personnalise

Lorsque les presets ne correspondent pas a vos besoins, creez un espacement personnalise :

### Espacement vertical

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Espacement horizontal

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Les deux dimensions

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Exemple

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

## Utilisation avec les Slivers

Lorsque vous travaillez avec `CustomScrollView` et les slivers, utilisez la methode `asSliver()` :

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

La methode `asSliver()` encapsule le widget `Spacing` dans un `SliverToBoxAdapter`, le rendant compatible avec les mises en page basees sur les slivers.

## Espacement conditionnel

Utilisez `Spacing.zero` pour l'espacement conditionnel :

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
