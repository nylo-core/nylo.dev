# Spacing

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Tamanos Predefinidos](#preset-sizes "Tamanos Predefinidos")
- [Espaciado Personalizado](#custom-spacing "Espaciado Personalizado")
- [Uso con Slivers](#slivers "Uso con Slivers")


<div id="introduction"></div>

## Introduccion

El widget **Spacing** proporciona una forma limpia y legible de agregar espaciado consistente entre elementos de la interfaz. En lugar de crear manualmente instancias de `SizedBox` en todo tu codigo, puedes usar `Spacing` con valores predefinidos semanticos o valores personalizados.

``` dart
// En lugar de esto:
SizedBox(height: 16),

// Escribe esto:
Spacing.md,
```

<div id="preset-sizes"></div>

## Tamanos Predefinidos

`Spacing` viene con valores predefinidos integrados para valores de espaciado comunes. Estos ayudan a mantener un espaciado consistente en toda tu aplicacion.

### Espaciado Vertical Predefinido

Usa estos en widgets `Column` o en cualquier lugar donde necesites espacio vertical:

| Predefinido | Tamano | Uso |
|--------|------|-------|
| `Spacing.zero` | 0px | Espaciado condicional |
| `Spacing.xs` | 4px | Extra pequeno |
| `Spacing.sm` | 8px | Pequeno |
| `Spacing.md` | 16px | Mediano |
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

### Espaciado Horizontal Predefinido

Usa estos en widgets `Row` o en cualquier lugar donde necesites espacio horizontal:

| Predefinido | Tamano | Uso |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Extra pequeno |
| `Spacing.smHorizontal` | 8px | Pequeno |
| `Spacing.mdHorizontal` | 16px | Mediano |
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

## Espaciado Personalizado

Cuando los valores predefinidos no se ajustan a tus necesidades, crea un espaciado personalizado:

### Espaciado Vertical

``` dart
Spacing.vertical(12) // 12 pixeles logicos de espacio vertical
```

### Espaciado Horizontal

``` dart
Spacing.horizontal(20) // 20 pixeles logicos de espacio horizontal
```

### Ambas Dimensiones

``` dart
Spacing(width: 10, height: 20) // Ancho y alto personalizados
```

### Ejemplo

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // Espacio personalizado de 40px
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // Espacio personalizado de 12px
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## Uso con Slivers

Al trabajar con `CustomScrollView` y slivers, usa el metodo `asSliver()`:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // Convierte a SliverToBoxAdapter
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

El metodo `asSliver()` envuelve el widget `Spacing` en un `SliverToBoxAdapter`, haciendolo compatible con layouts basados en slivers.

## Espaciado Condicional

Usa `Spacing.zero` para espaciado condicional:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
