# Spacing

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Predefiniowane rozmiary](#preset-sizes "Predefiniowane rozmiary")
- [Niestandardowe odstepy](#custom-spacing "Niestandardowe odstepy")
- [Uzycie ze Sliverami](#slivers "Uzycie ze Sliverami")


<div id="introduction"></div>

## Wprowadzenie

Widget **Spacing** zapewnia czysty, czytelny sposob dodawania spojnych odstepow miedzy elementami UI. Zamiast recznego tworzenia instancji `SizedBox` w calym kodzie, mozesz uzyc `Spacing` z semantycznymi predefiniowanymi wartosciami lub wartosciami niestandardowymi.

``` dart
// Zamiast tego:
SizedBox(height: 16),

// Napisz to:
Spacing.md,
```

<div id="preset-sizes"></div>

## Predefiniowane rozmiary

`Spacing` posiada wbudowane predefiniowane wartosci dla typowych odstepow. Pomagaja one utrzymac spojne odstepy w calej aplikacji.

### Predefiniowane odstepy pionowe

Uzywaj ich w widgetach `Column` lub wszedzie tam, gdzie potrzebujesz pionowej przestrzeni:

| Predefiniowany | Rozmiar | Uzycie |
|--------|------|-------|
| `Spacing.zero` | 0px | Warunkowe odstepy |
| `Spacing.xs` | 4px | Bardzo maly |
| `Spacing.sm` | 8px | Maly |
| `Spacing.md` | 16px | Sredni |
| `Spacing.lg` | 24px | Duzy |
| `Spacing.xl` | 32px | Bardzo duzy |

``` dart
Column(
  children: [
    Text("Tytul"),
    Spacing.sm,
    Text("Podtytul"),
    Spacing.lg,
    Text("Tresc"),
    Spacing.xl,
    ElevatedButton(
      onPressed: () {},
      child: Text("Akcja"),
    ),
  ],
)
```

### Predefiniowane odstepy poziome

Uzywaj ich w widgetach `Row` lub wszedzie tam, gdzie potrzebujesz poziomej przestrzeni:

| Predefiniowany | Rozmiar | Uzycie |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Bardzo maly |
| `Spacing.smHorizontal` | 8px | Maly |
| `Spacing.mdHorizontal` | 16px | Sredni |
| `Spacing.lgHorizontal` | 24px | Duzy |
| `Spacing.xlHorizontal` | 32px | Bardzo duzy |

``` dart
Row(
  children: [
    Icon(Icons.star),
    Spacing.smHorizontal,
    Text("Ocena"),
    Spacing.lgHorizontal,
    Text("5.0"),
  ],
)
```

<div id="custom-spacing"></div>

## Niestandardowe odstepy

Gdy predefiniowane wartosci nie odpowiadaja Twoim potrzebom, utworz niestandardowe odstepy:

### Odstep pionowy

``` dart
Spacing.vertical(12) // 12 pikseli logicznych przestrzeni pionowej
```

### Odstep poziomy

``` dart
Spacing.horizontal(20) // 20 pikseli logicznych przestrzeni poziomej
```

### Oba wymiary

``` dart
Spacing(width: 10, height: 20) // Niestandardowa szerokosc i wysokosc
```

### Przyklad

``` dart
Column(
  children: [
    Text("Naglowek"),
    Spacing.vertical(40), // Niestandardowy odstep 40px
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // Niestandardowy odstep 12px
            Expanded(child: Text("Tekst informacyjny")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## Uzycie ze Sliverami

Podczas pracy z `CustomScrollView` i sliverami uzywaj metody `asSliver()`:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("Moja aplikacja"),
    ),
    Spacing.lg.asSliver(), // Konwertuje na SliverToBoxAdapter
    SliverList(
      delegate: SliverChildBuilderDelegate(
        (context, index) => ListTile(title: Text("Element $index")),
        childCount: 10,
      ),
    ),
    Spacing.xl.asSliver(),
    SliverToBoxAdapter(
      child: Text("Stopka"),
    ),
  ],
)
```

Metoda `asSliver()` opakowuje widget `Spacing` w `SliverToBoxAdapter`, czyniąc go kompatybilnym z ukladami opartymi na sliverach.

## Warunkowe odstepy

Uzyj `Spacing.zero` do warunkowych odstepow:

``` dart
Column(
  children: [
    Text("Zawsze widoczne"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Zawartosc ponizej"),
  ],
)
```