# FadeOverlay

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [方向构造函数](#directional "方向构造函数")
- [自定义](#customization "自定义")
- [参数](#parameters "参数")


<div id="introduction"></div>

## 简介

**FadeOverlay** 组件在其子组件上应用渐变淡化效果。这对于创建视觉深度、提高图片上文字的可读性或为 UI 添加风格效果非常有用。

<div id="basic-usage"></div>

## 基本用法

用 `FadeOverlay` 包裹任何组件以应用渐变淡化：

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

这会创建一个从顶部透明到底部深色覆盖层的微妙淡化效果。

### 在图片上叠加文字

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

## 方向构造函数

`FadeOverlay` 为常见的淡化方向提供命名构造函数：

### FadeOverlay.top

从底部（透明）到顶部（颜色）淡化：

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

从顶部（透明）到底部（颜色）淡化：

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

从右侧（透明）到左侧（颜色）淡化：

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

从左侧（透明）到右侧（颜色）淡化：

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## 自定义

### 调整强度

`strength` 参数控制淡化效果的强度（0.0 到 1.0）：

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

### 自定义颜色

更改覆盖层颜色以匹配您的设计：

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

### 自定义渐变方向

对于非标准方向，指定 `begin` 和 `end` 对齐方式：

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

## 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `child` | `Widget` | 必需 | 应用淡化效果的组件 |
| `strength` | `double` | `0.2` | 淡化强度（0.0 到 1.0） |
| `color` | `Color` | `Colors.black` | 淡化覆盖层的颜色 |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | 渐变起始对齐 |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | 渐变结束对齐 |

## 示例：带淡化效果的卡片

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
