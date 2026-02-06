# FadeOverlay

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Yönlü Kurucular](#directional "Yönlü Kurucular")
- [Özelleştirme](#customization "Özelleştirme")
- [Parametreler](#parameters "Parametreler")


<div id="introduction"></div>

## Giriş

**FadeOverlay** widget'ı, alt widget'ına bir gradyan solma efekti uygular. Bu, görsel derinlik oluşturmak, görseller üzerinde metin okunabilirliğini artırmak veya arayüzünüze stilistik efektler eklemek için kullanışlıdır.

<div id="basic-usage"></div>

## Temel Kullanım

Gradyan solma uygulamak için herhangi bir widget'ı `FadeOverlay` ile sarın:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Bu, üstte şeffaftan alta doğru koyu bir kaplama oluşturur.

### Görsel Üzerinde Metin

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

## Yönlü Kurucular

`FadeOverlay`, yaygın solma yönleri için adlandırılmış kurucular sağlar:

### FadeOverlay.top

Alttan (şeffaf) üste (renk) doğru solar:

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Üstten (şeffaf) alta (renk) doğru solar:

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Sağdan (şeffaf) sola (renk) doğru solar:

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Soldan (şeffaf) sağa (renk) doğru solar:

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Özelleştirme

### Güç Ayarı

`strength` parametresi solma efektinin yoğunluğunu kontrol eder (0.0 ile 1.0 arası):

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

### Özel Renk

Tasarımınıza uygun kaplama rengini değiştirin:

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

### Özel Gradyan Yönü

Standart olmayan yönler için `begin` ve `end` hizalamalarını belirtin:

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

## Parametreler

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `child` | `Widget` | zorunlu | Solma efektinin uygulanacağı widget |
| `strength` | `double` | `0.2` | Solma yoğunluğu (0.0 ile 1.0 arası) |
| `color` | `Color` | `Colors.black` | Solma kaplamasının rengi |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Gradyan başlangıç hizalaması |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Gradyan bitiş hizalaması |

## Örnek: Solmalı Kart

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
