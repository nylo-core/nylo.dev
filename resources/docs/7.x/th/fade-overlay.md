# FadeOverlay

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานพื้นฐาน](#basic-usage "การใช้งานพื้นฐาน")
- [ตัวสร้างตามทิศทาง](#directional "ตัวสร้างตามทิศทาง")
- [การปรับแต่ง](#customization "การปรับแต่ง")
- [พารามิเตอร์](#parameters "พารามิเตอร์")


<div id="introduction"></div>

## บทนำ

widget **FadeOverlay** ใช้เอฟเฟกต์การไล่ระดับสีแบบจางลงบน widget ลูก สิ่งนี้มีประโยชน์สำหรับการสร้างความลึกทางภาพ ปรับปรุงความสามารถในการอ่านข้อความบนรูปภาพ หรือเพิ่มเอฟเฟกต์สไตล์ให้กับ UI ของคุณ

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

ครอบ widget ใดก็ได้ด้วย `FadeOverlay` เพื่อใช้เอฟเฟกต์การไล่ระดับสีแบบจาง:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

สิ่งนี้จะสร้างเอฟเฟกต์จางแบบละเอียดจากโปร่งใสด้านบนไปเป็นสีเข้มที่ด้านล่าง

### พร้อมข้อความบนรูปภาพ

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

## ตัวสร้างตามทิศทาง

`FadeOverlay` มีตัวสร้างแบบมีชื่อสำหรับทิศทางการจางทั่วไป:

### FadeOverlay.top

จางจากล่าง (โปร่งใส) ไปบน (สี):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

จางจากบน (โปร่งใส) ไปล่าง (สี):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

จางจากขวา (โปร่งใส) ไปซ้าย (สี):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

จางจากซ้าย (โปร่งใส) ไปขวา (สี):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## การปรับแต่ง

### การปรับความเข้ม

พารามิเตอร์ `strength` ควบคุมความเข้มของเอฟเฟกต์จาง (0.0 ถึง 1.0):

``` dart
// จางแบบละเอียด
FadeOverlay(
  child: myImage,
  strength: 0.1,
)

// จางแบบปานกลาง
FadeOverlay(
  child: myImage,
  strength: 0.5,
)

// จางแบบเข้ม
FadeOverlay(
  child: myImage,
  strength: 1.0,
)
```

### สีที่กำหนดเอง

เปลี่ยนสีของการซ้อนทับให้ตรงกับการออกแบบของคุณ:

``` dart
// การซ้อนทับสีน้ำเงินเข้ม
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.blue.shade900,
  strength: 0.6,
)

// การซ้อนทับสีขาวสำหรับธีมสว่าง
FadeOverlay.bottom(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.white,
  strength: 0.4,
)
```

### ทิศทางการไล่ระดับสีแบบกำหนดเอง

สำหรับทิศทางที่ไม่เป็นมาตรฐาน ให้ระบุตำแหน่ง `begin` และ `end`:

``` dart
// จางแบบทแยงมุม (ซ้ายบนไปขวาล่าง)
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
  strength: 0.5,
)

// จางจากกลางออกด้านนอก
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.center,
  end: Alignment.bottomCenter,
  strength: 0.4,
)
```

<div id="parameters"></div>

## พารามิเตอร์

| พารามิเตอร์ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `child` | `Widget` | จำเป็น | widget ที่จะใช้เอฟเฟกต์จางทับ |
| `strength` | `double` | `0.2` | ความเข้มของการจาง (0.0 ถึง 1.0) |
| `color` | `Color` | `Colors.black` | สีของการซ้อนทับจาง |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | ตำแหน่งเริ่มต้นของการไล่ระดับสี |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | ตำแหน่งสิ้นสุดของการไล่ระดับสี |

## ตัวอย่าง: การ์ดพร้อมเอฟเฟกต์จาง

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
