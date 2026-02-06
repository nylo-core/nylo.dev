# FadeOverlay

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Richtungskonstruktoren](#directional "Richtungskonstruktoren")
- [Anpassung](#customization "Anpassung")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Einleitung

Das **FadeOverlay**-Widget wendet einen Verlaufs-Fade-Effekt über sein Kind-Widget an. Dies ist nützlich, um visuelle Tiefe zu erzeugen, die Lesbarkeit von Text über Bildern zu verbessern oder stilistische Effekte zu Ihrer UI hinzuzufügen.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Umschließen Sie jedes Widget mit `FadeOverlay`, um einen Verlaufs-Fade anzuwenden:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Dies erzeugt einen subtilen Fade von transparent oben zu einem dunklen Overlay unten.

### Mit Text über Bild

``` dart
Stack(
  children: [
    FadeOverlay(
      child: Image.network(
        'https://example.com/image.jpg',
        fit: BoxFit.cover,
      ),
      strength: 0.5,
    ),
    Positioned(
      bottom: 16,
      left: 16,
      child: Text(
        "Photo Title",
        style: TextStyle(color: Colors.white, fontSize: 24),
      ),
    ),
  ],
)
```

<div id="directional"></div>

## Richtungskonstruktoren

`FadeOverlay` bietet benannte Konstruktoren für gängige Fade-Richtungen:

### FadeOverlay.top

Fadet von unten (transparent) nach oben (Farbe):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Fadet von oben (transparent) nach unten (Farbe):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Fadet von rechts (transparent) nach links (Farbe):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Fadet von links (transparent) nach rechts (Farbe):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Anpassung

### Stärke anpassen

Der Parameter `strength` steuert die Intensität des Fade-Effekts (0.0 bis 1.0):

``` dart
// Subtle fade
FadeOverlay(
  child: myImage,
  strength: 0.1,
)

// Medium fade
FadeOverlay(
  child: myImage,
  strength: 0.5,
)

// Strong fade
FadeOverlay(
  child: myImage,
  strength: 1.0,
)
```

### Benutzerdefinierte Farbe

Ändern Sie die Overlay-Farbe passend zu Ihrem Design:

``` dart
// Dark blue overlay
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.blue.shade900,
  strength: 0.6,
)

// White overlay for light themes
FadeOverlay.bottom(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.white,
  strength: 0.4,
)
```

### Benutzerdefinierte Verlaufsrichtung

Für nicht-standardmäßige Richtungen geben Sie die `begin`- und `end`-Ausrichtungen an:

``` dart
// Diagonal fade (top-left to bottom-right)
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
  strength: 0.5,
)

// Center outward fade
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.center,
  end: Alignment.bottomCenter,
  strength: 0.4,
)
```

<div id="parameters"></div>

## Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `child` | `Widget` | erforderlich | Das Widget, auf das der Fade-Effekt angewendet wird |
| `strength` | `double` | `0.2` | Fade-Intensität (0.0 bis 1.0) |
| `color` | `Color` | `Colors.black` | Die Farbe des Fade-Overlays |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Startausrichtung des Verlaufs |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Endausrichtung des Verlaufs |

## Beispiel: Karte mit Fade

``` dart
Container(
  height: 200,
  width: double.infinity,
  child: ClipRRect(
    borderRadius: BorderRadius.circular(12),
    child: Stack(
      fit: StackFit.expand,
      children: [
        FadeOverlay.bottom(
          strength: 0.6,
          child: Image.network(
            'https://example.com/product.jpg',
            fit: BoxFit.cover,
          ),
        ),
        Positioned(
          bottom: 16,
          left: 16,
          right: 16,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "Product Name",
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                "\$29.99",
                style: TextStyle(color: Colors.white70),
              ),
            ],
          ),
        ),
      ],
    ),
  ),
)
```
