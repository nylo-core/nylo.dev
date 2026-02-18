# Spacing

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [ขนาดที่กำหนดไว้ล่วงหน้า](#preset-sizes "ขนาดที่กำหนดไว้ล่วงหน้า")
- [การกำหนดระยะห่างเอง](#custom-spacing "การกำหนดระยะห่างเอง")
- [การใช้กับ Slivers](#slivers "การใช้กับ Slivers")


<div id="introduction"></div>

## บทนำ

Widget **Spacing** มอบวิธีที่สะอาดและอ่านง่ายในการเพิ่มระยะห่างที่สม่ำเสมอระหว่าง element ของ UI แทนที่จะสร้าง `SizedBox` ด้วยตนเองตลอดทั้งโค้ดของคุณ คุณสามารถใช้ `Spacing` ด้วยค่าที่กำหนดไว้ล่วงหน้าหรือค่าที่กำหนดเอง

``` dart
// แทนที่จะเขียนแบบนี้:
SizedBox(height: 16),

// เขียนแบบนี้:
Spacing.md,
```

<div id="preset-sizes"></div>

## ขนาดที่กำหนดไว้ล่วงหน้า

`Spacing` มาพร้อมกับค่าที่กำหนดไว้ล่วงหน้าสำหรับค่าระยะห่างที่ใช้บ่อย ช่วยรักษาระยะห่างที่สม่ำเสมอตลอดทั้งแอปของคุณ

### ระยะห่างแนวตั้งที่กำหนดไว้ล่วงหน้า

ใช้สิ่งเหล่านี้ใน `Column` widget หรือทุกที่ที่คุณต้องการพื้นที่แนวตั้ง:

| ค่าที่กำหนด | ขนาด | การใช้งาน |
|--------|------|-------|
| `Spacing.zero` | 0px | ระยะห่างแบบมีเงื่อนไข |
| `Spacing.xs` | 4px | เล็กพิเศษ |
| `Spacing.sm` | 8px | เล็ก |
| `Spacing.md` | 16px | กลาง |
| `Spacing.lg` | 24px | ใหญ่ |
| `Spacing.xl` | 32px | ใหญ่พิเศษ |

``` dart
Column(
  children: [
    Text("Title"),
    Spacing.sm,
    Text("Subtitle"),
    Spacing.lg,
    Text("Body content"),
    Spacing.xl,
    ElevatedButton(
      onPressed: () {},
      child: Text("Action"),
    ),
  ],
)
```

### ระยะห่างแนวนอนที่กำหนดไว้ล่วงหน้า

ใช้สิ่งเหล่านี้ใน `Row` widget หรือทุกที่ที่คุณต้องการพื้นที่แนวนอน:

| ค่าที่กำหนด | ขนาด | การใช้งาน |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | เล็กพิเศษ |
| `Spacing.smHorizontal` | 8px | เล็ก |
| `Spacing.mdHorizontal` | 16px | กลาง |
| `Spacing.lgHorizontal` | 24px | ใหญ่ |
| `Spacing.xlHorizontal` | 32px | ใหญ่พิเศษ |

``` dart
Row(
  children: [
    Icon(Icons.star),
    Spacing.smHorizontal,
    Text("Rating"),
    Spacing.lgHorizontal,
    Text("5.0"),
  ],
)
```

<div id="custom-spacing"></div>

## การกำหนดระยะห่างเอง

เมื่อค่าที่กำหนดไว้ล่วงหน้าไม่ตรงกับความต้องการของคุณ สร้างระยะห่างแบบกำหนดเอง:

### ระยะห่างแนวตั้ง

``` dart
Spacing.vertical(12) // พื้นที่แนวตั้ง 12 logical pixel
```

### ระยะห่างแนวนอน

``` dart
Spacing.horizontal(20) // พื้นที่แนวนอน 20 logical pixel
```

### ทั้งสองมิติ

``` dart
Spacing(width: 10, height: 20) // ความกว้างและความสูงแบบกำหนดเอง
```

### ตัวอย่าง

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // ช่องว่าง 40px แบบกำหนดเอง
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // ช่องว่าง 12px แบบกำหนดเอง
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## การใช้กับ Slivers

เมื่อทำงานกับ `CustomScrollView` และ slivers ให้ใช้เมธอด `asSliver()`:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // แปลงเป็น SliverToBoxAdapter
    SliverList(
      delegate: SliverChildBuilderDelegate(
        (context, index) => ListTile(title: Text("Item $index")),
        childCount: 10,
      ),
    ),
    Spacing.xl.asSliver(),
    SliverToBoxAdapter(
      child: Text("Footer"),
    ),
  ],
)
```

เมธอด `asSliver()` จะครอบ widget `Spacing` ด้วย `SliverToBoxAdapter` ทำให้สามารถใช้งานร่วมกับ layout ที่ใช้ sliver ได้

## ระยะห่างแบบมีเงื่อนไข

ใช้ `Spacing.zero` สำหรับระยะห่างแบบมีเงื่อนไข:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
