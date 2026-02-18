# FadeOverlay

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Constructor theo hướng](#directional "Constructor theo hướng")
- [Tùy chỉnh](#customization "Tùy chỉnh")
- [Tham số](#parameters "Tham số")


<div id="introduction"></div>

## Giới thiệu

Widget **FadeOverlay** áp dụng hiệu ứng mờ dần gradient lên widget con của nó. Điều này hữu ích để tạo chiều sâu trực quan, cải thiện khả năng đọc văn bản trên hình ảnh, hoặc thêm hiệu ứng phong cách cho giao diện của bạn.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Bọc bất kỳ widget nào bằng `FadeOverlay` để áp dụng hiệu ứng mờ dần gradient:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Điều này tạo ra một hiệu ứng mờ nhẹ từ trong suốt ở trên cùng đến lớp phủ tối ở dưới cùng.

### Với văn bản trên hình ảnh

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

## Constructor theo hướng

`FadeOverlay` cung cấp các constructor được đặt tên cho các hướng mờ phổ biến:

### FadeOverlay.top

Mờ dần từ dưới (trong suốt) lên trên (màu):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Mờ dần từ trên (trong suốt) xuống dưới (màu):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Mờ dần từ phải (trong suốt) sang trái (màu):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Mờ dần từ trái (trong suốt) sang phải (màu):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Tùy chỉnh

### Điều chỉnh cường độ

Tham số `strength` kiểm soát cường độ của hiệu ứng mờ (0.0 đến 1.0):

``` dart
// Mờ nhẹ
FadeOverlay(
  child: myImage,
  strength: 0.1,
)

// Mờ trung bình
FadeOverlay(
  child: myImage,
  strength: 0.5,
)

// Mờ mạnh
FadeOverlay(
  child: myImage,
  strength: 1.0,
)
```

### Màu tùy chỉnh

Thay đổi màu lớp phủ cho phù hợp với thiết kế của bạn:

``` dart
// Lớp phủ xanh đậm
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.blue.shade900,
  strength: 0.6,
)

// Lớp phủ trắng cho theme sáng
FadeOverlay.bottom(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.white,
  strength: 0.4,
)
```

### Hướng Gradient tùy chỉnh

Đối với các hướng không chuẩn, chỉ định alignment `begin` và `end`:

``` dart
// Mờ chéo (trên-trái đến dưới-phải)
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
  strength: 0.5,
)

// Mờ từ trung tâm ra ngoài
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.center,
  end: Alignment.bottomCenter,
  strength: 0.4,
)
```

<div id="parameters"></div>

## Tham số

| Tham số | Kiểu | Mặc định | Mô tả |
|---------|------|----------|-------|
| `child` | `Widget` | bắt buộc | Widget để áp dụng hiệu ứng mờ lên |
| `strength` | `double` | `0.2` | Cường độ mờ (0.0 đến 1.0) |
| `color` | `Color` | `Colors.black` | Màu của lớp phủ mờ |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Alignment bắt đầu gradient |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Alignment kết thúc gradient |

## Ví dụ: Card với hiệu ứng mờ

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
