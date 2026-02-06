# FadeOverlay

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Constructeurs directionnels](#directional "Constructeurs directionnels")
- [Personnalisation](#customization "Personnalisation")
- [Parametres](#parameters "Parametres")


<div id="introduction"></div>

## Introduction

Le widget **FadeOverlay** applique un effet de fondu en degrade sur son widget enfant. Ceci est utile pour creer de la profondeur visuelle, ameliorer la lisibilite du texte sur les images ou ajouter des effets stylistiques a votre interface.

<div id="basic-usage"></div>

## Utilisation de base

Enveloppez n'importe quel widget avec `FadeOverlay` pour appliquer un fondu en degrade :

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Cela cree un fondu subtil du transparent en haut vers un recouvrement sombre en bas.

### Avec du texte sur une image

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

## Constructeurs directionnels

`FadeOverlay` fournit des constructeurs nommes pour les directions de fondu courantes :

### FadeOverlay.top

Fondu du bas (transparent) vers le haut (couleur) :

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Fondu du haut (transparent) vers le bas (couleur) :

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Fondu de la droite (transparent) vers la gauche (couleur) :

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Fondu de la gauche (transparent) vers la droite (couleur) :

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Personnalisation

### Ajuster l'intensite

Le parametre `strength` controle l'intensite de l'effet de fondu (0.0 a 1.0) :

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

### Couleur personnalisee

Changez la couleur du recouvrement pour correspondre a votre design :

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

### Direction de degrade personnalisee

Pour des directions non standard, specifiez les alignements `begin` et `end` :

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

## Parametres

| Parametre | Type | Par defaut | Description |
|-----------|------|---------|-------------|
| `child` | `Widget` | requis | Le widget sur lequel appliquer l'effet de fondu |
| `strength` | `double` | `0.2` | Intensite du fondu (0.0 a 1.0) |
| `color` | `Color` | `Colors.black` | La couleur du recouvrement en fondu |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Alignement de debut du degrade |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Alignement de fin du degrade |

## Exemple : Carte avec fondu

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
