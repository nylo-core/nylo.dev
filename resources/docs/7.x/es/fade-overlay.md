# FadeOverlay

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Constructores direccionales](#directional "Constructores direccionales")
- [Personalizacion](#customization "Personalizacion")
- [Parametros](#parameters "Parametros")


<div id="introduction"></div>

## Introduccion

El widget **FadeOverlay** aplica un efecto de desvanecimiento con gradiente sobre su widget hijo. Esto es util para crear profundidad visual, mejorar la legibilidad del texto sobre imagenes, o agregar efectos estilisticos a tu interfaz.

<div id="basic-usage"></div>

## Uso basico

Envuelve cualquier widget con `FadeOverlay` para aplicar un desvanecimiento con gradiente:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Esto crea un desvanecimiento sutil de transparente en la parte superior a una superposicion oscura en la parte inferior.

### Con texto sobre imagen

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

## Constructores direccionales

`FadeOverlay` proporciona constructores nombrados para direcciones de desvanecimiento comunes:

### FadeOverlay.top

Se desvanece desde la parte inferior (transparente) hacia la parte superior (color):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Se desvanece desde la parte superior (transparente) hacia la parte inferior (color):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Se desvanece desde la derecha (transparente) hacia la izquierda (color):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Se desvanece desde la izquierda (transparente) hacia la derecha (color):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Personalizacion

### Ajustar la intensidad

El parametro `strength` controla la intensidad del efecto de desvanecimiento (0.0 a 1.0):

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

### Color personalizado

Cambia el color de superposicion para que coincida con tu diseno:

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

### Direccion de gradiente personalizada

Para direcciones no estandar, especifica las alineaciones `begin` y `end`:

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

## Parametros

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `child` | `Widget` | requerido | El widget sobre el que se aplica el efecto de desvanecimiento |
| `strength` | `double` | `0.2` | Intensidad del desvanecimiento (0.0 a 1.0) |
| `color` | `Color` | `Colors.black` | El color de la superposicion de desvanecimiento |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Alineacion de inicio del gradiente |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Alineacion de fin del gradiente |

## Ejemplo: Tarjeta con desvanecimiento

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
