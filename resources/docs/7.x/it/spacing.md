# Spacing

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Dimensioni Predefinite](#preset-sizes "Dimensioni Predefinite")
- [Spaziatura Personalizzata](#custom-spacing "Spaziatura Personalizzata")
- [Utilizzo con Sliver](#slivers "Utilizzo con Sliver")


<div id="introduction"></div>

## Introduzione

Il widget **Spacing** fornisce un modo pulito e leggibile per aggiungere spaziature consistenti tra gli elementi dell'interfaccia. Invece di creare manualmente istanze di `SizedBox` in tutto il codice, puoi usare `Spacing` con preset semantici o valori personalizzati.

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## Dimensioni Predefinite

`Spacing` include preset integrati per i valori di spaziatura piu' comuni. Questi aiutano a mantenere una spaziatura consistente in tutta la tua app.

### Preset di Spaziatura Verticale

Usali nei widget `Column` o ovunque sia necessaria spaziatura verticale:

| Preset | Dimensione | Utilizzo |
|--------|------|-------|
| `Spacing.zero` | 0px | Spaziatura condizionale |
| `Spacing.xs` | 4px | Extra piccola |
| `Spacing.sm` | 8px | Piccola |
| `Spacing.md` | 16px | Media |
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

### Preset di Spaziatura Orizzontale

Usali nei widget `Row` o ovunque sia necessaria spaziatura orizzontale:

| Preset | Dimensione | Utilizzo |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Extra piccola |
| `Spacing.smHorizontal` | 8px | Piccola |
| `Spacing.mdHorizontal` | 16px | Media |
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

## Spaziatura Personalizzata

Quando i preset non soddisfano le tue esigenze, crea una spaziatura personalizzata:

### Spaziatura Verticale

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### Spaziatura Orizzontale

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### Entrambe le Dimensioni

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### Esempio

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

## Utilizzo con Sliver

Quando lavori con `CustomScrollView` e sliver, usa il metodo `asSliver()`:

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

Il metodo `asSliver()` avvolge il widget `Spacing` in un `SliverToBoxAdapter`, rendendolo compatibile con i layout basati su sliver.

## Spaziatura Condizionale

Usa `Spacing.zero` per la spaziatura condizionale:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
