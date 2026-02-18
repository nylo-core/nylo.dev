# FadeOverlay

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Направленные конструкторы](#directional "Направленные конструкторы")
- [Настройка](#customization "Настройка")
- [Параметры](#parameters "Параметры")


<div id="introduction"></div>

## Введение

Виджет **FadeOverlay** применяет эффект градиентного затухания поверх дочернего виджета. Это полезно для создания визуальной глубины, улучшения читаемости текста поверх изображений или добавления стилистических эффектов в ваш пользовательский интерфейс.

<div id="basic-usage"></div>

## Базовое использование

Оберните любой виджет в `FadeOverlay`, чтобы применить градиентное затухание:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Это создаёт плавное затухание от прозрачного вверху до тёмного наложения внизу.

### С текстом поверх изображения

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

## Направленные конструкторы

`FadeOverlay` предоставляет именованные конструкторы для распространённых направлений затухания:

### FadeOverlay.top

Затухание снизу (прозрачное) вверх (цвет):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Затухание сверху (прозрачное) вниз (цвет):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Затухание справа (прозрачное) влево (цвет):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Затухание слева (прозрачное) вправо (цвет):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Настройка

### Регулировка интенсивности

Параметр `strength` управляет интенсивностью эффекта затухания (от 0.0 до 1.0):

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

### Пользовательский цвет

Измените цвет наложения в соответствии с вашим дизайном:

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

### Пользовательское направление градиента

Для нестандартных направлений укажите выравнивание `begin` и `end`:

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

## Параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `child` | `Widget` | обязательный | Виджет, к которому применяется эффект затухания |
| `strength` | `double` | `0.2` | Интенсивность затухания (от 0.0 до 1.0) |
| `color` | `Color` | `Colors.black` | Цвет наложения затухания |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Начальное выравнивание градиента |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Конечное выравнивание градиента |

## Пример: Карточка с затуханием

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
