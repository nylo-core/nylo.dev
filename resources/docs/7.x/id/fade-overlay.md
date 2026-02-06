# FadeOverlay

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Konstruktor Arah](#directional "Konstruktor Arah")
- [Kustomisasi](#customization "Kustomisasi")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Pengantar

Widget **FadeOverlay** menerapkan efek fade gradient di atas widget anak-nya. Ini berguna untuk membuat kedalaman visual, meningkatkan keterbacaan teks di atas gambar, atau menambahkan efek stilistik pada UI Anda.

<div id="basic-usage"></div>

## Penggunaan Dasar

Bungkus widget apa pun dengan `FadeOverlay` untuk menerapkan fade gradient:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Ini membuat fade halus dari transparan di bagian atas ke overlay gelap di bagian bawah.

### Dengan Teks di Atas Gambar

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

## Konstruktor Arah

`FadeOverlay` menyediakan konstruktor bernama untuk arah fade umum:

### FadeOverlay.top

Fade dari bawah (transparan) ke atas (warna):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Fade dari atas (transparan) ke bawah (warna):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Fade dari kanan (transparan) ke kiri (warna):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Fade dari kiri (transparan) ke kanan (warna):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Kustomisasi

### Mengatur Kekuatan

Parameter `strength` mengontrol intensitas efek fade (0.0 sampai 1.0):

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

### Warna Kustom

Ubah warna overlay agar sesuai dengan desain Anda:

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

### Arah Gradient Kustom

Untuk arah non-standar, tentukan alignment `begin` dan `end`:

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

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-----------|
| `child` | `Widget` | wajib | Widget tempat efek fade diterapkan |
| `strength` | `double` | `0.2` | Intensitas fade (0.0 sampai 1.0) |
| `color` | `Color` | `Colors.black` | Warna overlay fade |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Alignment awal gradient |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Alignment akhir gradient |

## Contoh: Card dengan Fade

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
