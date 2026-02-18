# Spacing

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Kích thước có sẵn](#preset-sizes "Kích thước có sẵn")
- [Khoảng cách tùy chỉnh](#custom-spacing "Khoảng cách tùy chỉnh")
- [Sử dụng với Slivers](#slivers "Sử dụng với Slivers")


<div id="introduction"></div>

## Giới thiệu

Widget **Spacing** cung cấp cách dễ đọc và gọn gàng để thêm khoảng cách nhất quán giữa các phần tử UI. Thay vì tạo thủ công các instance `SizedBox` trong mã của bạn, bạn có thể sử dụng `Spacing` với các giá trị có sẵn hoặc giá trị tùy chỉnh.

``` dart
// Thay vì viết:
SizedBox(height: 16),

// Hãy viết:
Spacing.md,
```

<div id="preset-sizes"></div>

## Kích thước có sẵn

`Spacing` đi kèm với các giá trị có sẵn cho các khoảng cách phổ biến. Chúng giúp duy trì khoảng cách nhất quán trong toàn bộ ứng dụng của bạn.

### Khoảng cách dọc có sẵn

Sử dụng trong widget `Column` hoặc bất cứ nơi nào bạn cần khoảng cách dọc:

| Giá trị | Kích thước | Cách dùng |
|--------|------|-------|
| `Spacing.zero` | 0px | Khoảng cách có điều kiện |
| `Spacing.xs` | 4px | Rất nhỏ |
| `Spacing.sm` | 8px | Nhỏ |
| `Spacing.md` | 16px | Trung bình |
| `Spacing.lg` | 24px | Lớn |
| `Spacing.xl` | 32px | Rất lớn |

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

### Khoảng cách ngang có sẵn

Sử dụng trong widget `Row` hoặc bất cứ nơi nào bạn cần khoảng cách ngang:

| Giá trị | Kích thước | Cách dùng |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | Rất nhỏ |
| `Spacing.smHorizontal` | 8px | Nhỏ |
| `Spacing.mdHorizontal` | 16px | Trung bình |
| `Spacing.lgHorizontal` | 24px | Lớn |
| `Spacing.xlHorizontal` | 32px | Rất lớn |

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

## Khoảng cách tùy chỉnh

Khi các giá trị có sẵn không phù hợp, hãy tạo khoảng cách tùy chỉnh:

### Khoảng cách dọc

``` dart
Spacing.vertical(12) // 12 pixel logic khoảng cách dọc
```

### Khoảng cách ngang

``` dart
Spacing.horizontal(20) // 20 pixel logic khoảng cách ngang
```

### Cả hai chiều

``` dart
Spacing(width: 10, height: 20) // Chiều rộng và chiều cao tùy chỉnh
```

### Ví dụ

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // Khoảng cách tùy chỉnh 40px
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // Khoảng cách tùy chỉnh 12px
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## Sử dụng với Slivers

Khi làm việc với `CustomScrollView` và slivers, sử dụng phương thức `asSliver()`:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // Chuyển đổi thành SliverToBoxAdapter
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

Phương thức `asSliver()` bọc widget `Spacing` trong một `SliverToBoxAdapter`, giúp nó tương thích với bố cục dựa trên sliver.

## Khoảng cách có điều kiện

Sử dụng `Spacing.zero` cho khoảng cách có điều kiện:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
