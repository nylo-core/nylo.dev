# FadeOverlay

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [방향별 생성자](#directional "방향별 생성자")
- [커스터마이징](#customization "커스터마이징")
- [매개변수](#parameters "매개변수")


<div id="introduction"></div>

## 소개

**FadeOverlay** 위젯은 자식 위젯 위에 그라디언트 페이드 효과를 적용합니다. 시각적 깊이를 만들거나, 이미지 위의 텍스트 가독성을 높이거나, UI에 스타일 효과를 추가하는 데 유용합니다.

<div id="basic-usage"></div>

## 기본 사용법

`FadeOverlay`로 위젯을 감싸 그라디언트 페이드를 적용합니다:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

상단의 투명에서 하단의 어두운 오버레이로 은은한 페이드를 생성합니다.

### 이미지 위의 텍스트

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

## 방향별 생성자

`FadeOverlay`는 일반적인 페이드 방향을 위한 명명된 생성자를 제공합니다:

### FadeOverlay.top

하단(투명)에서 상단(색상)으로 페이드합니다:

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

상단(투명)에서 하단(색상)으로 페이드합니다:

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

우측(투명)에서 좌측(색상)으로 페이드합니다:

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

좌측(투명)에서 우측(색상)으로 페이드합니다:

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## 커스터마이징

### 강도 조절

`strength` 매개변수는 페이드 효과의 강도를 제어합니다 (0.0 ~ 1.0):

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

### 커스텀 색상

디자인에 맞게 오버레이 색상을 변경합니다:

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

### 커스텀 그라디언트 방향

비표준 방향의 경우 `begin`과 `end` 정렬을 지정합니다:

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

## 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `child` | `Widget` | 필수 | 페이드 효과를 적용할 위젯 |
| `strength` | `double` | `0.2` | 페이드 강도 (0.0 ~ 1.0) |
| `color` | `Color` | `Colors.black` | 페이드 오버레이 색상 |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | 그라디언트 시작 정렬 |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | 그라디언트 끝 정렬 |

## 예제: 페이드가 적용된 카드

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
