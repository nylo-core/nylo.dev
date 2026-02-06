# Spacing

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Voreingestellte Groessen](#preset-sizes "Voreingestellte Groessen")
- [Benutzerdefinierter Abstand](#custom-spacing "Benutzerdefinierter Abstand")
- [Verwendung mit Slivers](#slivers "Verwendung mit Slivers")


<div id="introduction"></div>

## Einleitung

Das **Spacing**-Widget bietet eine saubere, lesbare Moeglichkeit, konsistente Abstaende zwischen UI-Elementen hinzuzufuegen. Anstatt manuell `SizedBox`-Instanzen in Ihrem gesamten Code zu erstellen, koennen Sie `Spacing` mit semantischen Voreinstellungen oder benutzerdefinierten Werten verwenden.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Voreingestellte Groessen

`Spacing` verfuegt ueber integrierte Voreinstellungen fuer gaengige Abstandswerte. Diese helfen, konsistente Abstaende in Ihrer gesamten App beizubehalten.

### Vertikale Abstands-Voreinstellungen

Verwenden Sie diese in `Column`-Widgets oder ueberall, wo Sie vertikalen Abstand benoetigen:

| Voreinstellung | Groesse | Verwendung |
|----------------|---------|------------|
| `Spacing.zero` | 0px | Bedingter Abstand |
| `Spacing.xs` | 4px | Extra klein |
| `Spacing.sm` | 8px | Klein |
| `Spacing.md` | 16px | Mittel |
| `Spacing.lg` | 24px | Gross |
| `Spacing.xl` | 32px | Extra gross |

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

### Horizontale Abstands-Voreinstellungen

Verwenden Sie diese in `Row`-Widgets oder ueberall, wo Sie horizontalen Abstand benoetigen:

| Voreinstellung | Groesse | Verwendung |
|----------------|---------|------------|
| `Spacing.xsHorizontal` | 4px | Extra klein |
| `Spacing.smHorizontal` | 8px | Klein |
| `Spacing.mdHorizontal` | 16px | Mittel |
| `Spacing.lgHorizontal` | 24px | Gross |
| `Spacing.xlHorizontal` | 32px | Extra gross |

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

## Benutzerdefinierter Abstand

Wenn die Voreinstellungen nicht Ihren Beduerfnissen entsprechen, erstellen Sie benutzerdefinierte Abstaende:

### Vertikaler Abstand

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Horizontaler Abstand

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Beide Dimensionen

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Beispiel

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

## Verwendung mit Slivers

Bei der Arbeit mit `CustomScrollView` und Slivers verwenden Sie die `asSliver()`-Methode:

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

Die `asSliver()`-Methode umschliesst das `Spacing`-Widget in einem `SliverToBoxAdapter`, wodurch es mit Sliver-basierten Layouts kompatibel wird.

## Bedingter Abstand

Verwenden Sie `Spacing.zero` fuer bedingten Abstand:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
