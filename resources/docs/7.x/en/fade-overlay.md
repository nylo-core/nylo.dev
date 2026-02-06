# FadeOverlay

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Directional Constructors](#directional "Directional Constructors")
- [Customization](#customization "Customization")
- [Parameters](#parameters "Parameters")


<div id="introduction"></div>

## Introduction

The **FadeOverlay** widget applies a gradient fade effect over its child widget. This is useful for creating visual depth, improving text readability over images, or adding stylistic effects to your UI.

<div id="basic-usage"></div>

## Basic Usage

Wrap any widget with `FadeOverlay` to apply a gradient fade:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

This creates a subtle fade from transparent at the top to a dark overlay at the bottom.

### With Text Over Image

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

## Directional Constructors

`FadeOverlay` provides named constructors for common fade directions:

### FadeOverlay.top

Fades from bottom (transparent) to top (color):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Fades from top (transparent) to bottom (color):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Fades from right (transparent) to left (color):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Fades from left (transparent) to right (color):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Customization

### Adjusting Strength

The `strength` parameter controls the intensity of the fade effect (0.0 to 1.0):

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

### Custom Color

Change the overlay color to match your design:

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

### Custom Gradient Direction

For non-standard directions, specify `begin` and `end` alignments:

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

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `child` | `Widget` | required | The widget to apply the fade effect over |
| `strength` | `double` | `0.2` | Fade intensity (0.0 to 1.0) |
| `color` | `Color` | `Colors.black` | The color of the fade overlay |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Gradient start alignment |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Gradient end alignment |

## Example: Card with Fade

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
