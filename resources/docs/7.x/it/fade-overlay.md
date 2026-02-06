# FadeOverlay

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Costruttori Direzionali](#directional "Costruttori Direzionali")
- [Personalizzazione](#customization "Personalizzazione")
- [Parametri](#parameters "Parametri")


<div id="introduction"></div>

## Introduzione

Il widget **FadeOverlay** applica un effetto di dissolvenza con gradiente sopra il suo widget figlio. Questo e' utile per creare profondita' visiva, migliorare la leggibilita' del testo sulle immagini o aggiungere effetti stilistici alla tua interfaccia.

<div id="basic-usage"></div>

## Utilizzo Base

Avvolgi qualsiasi widget con `FadeOverlay` per applicare una dissolvenza con gradiente:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Questo crea una sottile dissolvenza da trasparente in alto a un overlay scuro in basso.

### Con Testo sopra un'Immagine

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

## Costruttori Direzionali

`FadeOverlay` fornisce costruttori nominati per le direzioni di dissolvenza piu' comuni:

### FadeOverlay.top

Dissolvenza dal basso (trasparente) verso l'alto (colore):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Dissolvenza dall'alto (trasparente) verso il basso (colore):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Dissolvenza da destra (trasparente) verso sinistra (colore):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Dissolvenza da sinistra (trasparente) verso destra (colore):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Personalizzazione

### Regolazione dell'Intensita'

Il parametro `strength` controlla l'intensita' dell'effetto di dissolvenza (da 0.0 a 1.0):

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

### Colore Personalizzato

Cambia il colore dell'overlay per adattarlo al tuo design:

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

### Direzione del Gradiente Personalizzata

Per direzioni non standard, specifica gli allineamenti `begin` e `end`:

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

## Parametri

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `child` | `Widget` | obbligatorio | Il widget su cui applicare l'effetto di dissolvenza |
| `strength` | `double` | `0.2` | Intensita' della dissolvenza (da 0.0 a 1.0) |
| `color` | `Color` | `Colors.black` | Il colore dell'overlay di dissolvenza |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Allineamento di inizio del gradiente |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Allineamento di fine del gradiente |

## Esempio: Card con Dissolvenza

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
