# FadeOverlay

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Konstruktory kierunkowe](#directional "Konstruktory kierunkowe")
- [Personalizacja](#customization "Personalizacja")
- [Parametry](#parameters "Parametry")


<div id="introduction"></div>

## Wprowadzenie

Widget **FadeOverlay** nakłada efekt gradientowego zanikania na widget potomny. Jest przydatny do tworzenia wizualnej głębi, poprawy czytelności tekstu na obrazach lub dodawania efektów stylistycznych do interfejsu użytkownika.

<div id="basic-usage"></div>

## Podstawowe użycie

Owiń dowolny widget za pomocą `FadeOverlay`, aby zastosować gradientowe zanikanie:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Tworzy to subtelne zanikanie od przezroczystego na górze do ciemnej nakładki na dole.

### Z tekstem na obrazie

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

## Konstruktory kierunkowe

`FadeOverlay` udostępnia nazwane konstruktory dla typowych kierunków zanikania:

### FadeOverlay.top

Zanika od dołu (przezroczysty) do góry (kolor):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Zanika od góry (przezroczysty) do dołu (kolor):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Zanika od prawej (przezroczysty) do lewej (kolor):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Zanika od lewej (przezroczysty) do prawej (kolor):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Personalizacja

### Regulacja intensywności

Parametr `strength` kontroluje intensywność efektu zanikania (od 0.0 do 1.0):

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

### Własny kolor

Zmień kolor nakładki, aby dopasować go do projektu:

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

### Własny kierunek gradientu

Dla niestandardowych kierunków określ wyrównanie `begin` i `end`:

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

## Parametry

| Parametr | Typ | Domyślna wartość | Opis |
|----------|-----|------------------|------|
| `child` | `Widget` | wymagany | Widget, na który nakładany jest efekt zanikania |
| `strength` | `double` | `0.2` | Intensywność zanikania (od 0.0 do 1.0) |
| `color` | `Color` | `Colors.black` | Kolor nakładki zanikania |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Wyrównanie początkowe gradientu |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Wyrównanie końcowe gradientu |

## Przykład: Karta z zanikaniem

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
