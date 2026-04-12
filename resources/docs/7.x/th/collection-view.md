# CollectionView

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
- [ตัวช่วย CollectionItem](#collection-item "ตัวช่วย CollectionItem")
- รูปแบบต่างๆ
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- รูปแบบ Pullable
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [สไตล์การโหลด](#loading-styles "สไตล์การโหลด")
- [สถานะว่างเปล่า](#empty-state "สถานะว่างเปล่า")
- [การเรียงลำดับและแปลงข้อมูล](#sorting-transforming "การเรียงลำดับและแปลงข้อมูล")
- [การอัปเดตสถานะ](#updating-state "การอัปเดตสถานะ")
- [พารามิเตอร์อ้างอิง](#parameters "พารามิเตอร์อ้างอิง")


<div id="introduction"></div>

## บทนำ

widget **CollectionView** เป็น wrapper ที่ทรงพลังและ type-safe สำหรับแสดงรายการข้อมูลในโปรเจกต์ {{ config('app.name') }} ของคุณ ช่วยลดความซับซ้อนในการทำงานกับ `ListView`, `ListView.separated` และเลย์เอาต์แบบ grid พร้อมการรองรับในตัวสำหรับ:

- การโหลดข้อมูลแบบ async พร้อมสถานะโหลดอัตโนมัติ
- Pull-to-refresh และการแบ่งหน้า
- item builder ที่ type-safe พร้อมตัวช่วยตำแหน่ง
- การจัดการสถานะว่างเปล่า
- การเรียงลำดับและแปลงข้อมูล

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

นี่คือตัวอย่างง่ายๆ ในการแสดงรายการ:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: CollectionView<String>(
      data: () => ['Item 1', 'Item 2', 'Item 3'],
      builder: (context, item) {
        return ListTile(
          title: Text(item.data),
        );
      },
    ),
  );
}
```

โหลดข้อมูลแบบ async จาก API:

``` dart
CollectionView<Todo>(
  data: () async {
    return await api<ApiService>((request) =>
      request.get('https://jsonplaceholder.typicode.com/todos')
    );
  },
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.title),
      subtitle: Text(item.data.completed ? 'Done' : 'Pending'),
    );
  },
)
```

<div id="collection-item"></div>

## ตัวช่วย CollectionItem

callback `builder` จะรับอ็อบเจกต์ `CollectionItem<T>` ที่ครอบข้อมูลของคุณพร้อมตัวช่วยตำแหน่งที่มีประโยชน์:

``` dart
CollectionView<String>(
  data: () => ['First', 'Second', 'Third', 'Fourth'],
  builder: (context, item) {
    return Container(
      color: item.isEven ? Colors.grey[100] : Colors.white,
      child: ListTile(
        title: Text('${item.data} (index: ${item.index})'),
        subtitle: Text('Progress: ${(item.progress * 100).toInt()}%'),
      ),
    );
  },
)
```

### คุณสมบัติ CollectionItem

| คุณสมบัติ | ชนิด | คำอธิบาย |
|----------|------|-------------|
| `data` | `T` | ข้อมูลรายการจริง |
| `index` | `int` | ดัชนีปัจจุบันในรายการ |
| `totalItems` | `int` | จำนวนรายการทั้งหมด |
| `isFirst` | `bool` | true ถ้าเป็นรายการแรก |
| `isLast` | `bool` | true ถ้าเป็นรายการสุดท้าย |
| `isOdd` | `bool` | true ถ้าดัชนีเป็นเลขคี่ |
| `isEven` | `bool` | true ถ้าดัชนีเป็นเลขคู่ |
| `progress` | `double` | ความคืบหน้าผ่านรายการ (0.0 ถึง 1.0) |

### เมธอด CollectionItem

| เมธอด | คำอธิบาย |
|--------|-------------|
| `isAt(int position)` | ตรวจสอบว่ารายการอยู่ที่ตำแหน่งที่ระบุหรือไม่ |
| `isInRange(int start, int end)` | ตรวจสอบว่าดัชนีอยู่ในช่วงที่กำหนดหรือไม่ (รวมขอบเขต) |
| `isMultipleOf(int divisor)` | ตรวจสอบว่าดัชนีเป็นจำนวนเท่าของตัวหารหรือไม่ |

<div id="collection-view-basic"></div>

## CollectionView

constructor เริ่มต้นสร้าง list view มาตรฐาน:

``` dart
CollectionView<Map<String, dynamic>>(
  data: () async {
    return [
      {"title": "Clean Room"},
      {"title": "Go shopping"},
      {"title": "Buy groceries"},
    ];
  },
  builder: (context, item) {
    return ListTile(title: Text(item.data['title']));
  },
  spacing: 8.0, // เพิ่มระยะห่างระหว่างรายการ
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

สร้างรายการพร้อมตัวคั่นระหว่างรายการ:

``` dart
CollectionView<User>.separated(
  data: () async => await fetchUsers(),
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.name),
      subtitle: Text(item.data.email),
    );
  },
  separatorBuilder: (context, index) {
    return Divider(height: 1);
  },
)
```

<div id="collection-view-grid"></div>

## CollectionView.grid

สร้างเลย์เอาต์แบบ grid โดยใช้ staggered grid:

``` dart
CollectionView<Product>.grid(
  data: () async => await fetchProducts(),
  builder: (context, item) {
    return ProductCard(product: item.data);
  },
  crossAxisCount: 2,
  mainAxisSpacing: 8.0,
  crossAxisSpacing: 8.0,
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-pullable"></div>

## CollectionView.pullable

สร้างรายการพร้อม pull-to-refresh และการแบ่งหน้าแบบ infinite scroll:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iteration เริ่มที่ 1 และเพิ่มขึ้นในแต่ละการโหลด
    return await api<ApiService>((request) =>
      request.get('/posts?page=$iteration')
    );
  },
  builder: (context, item) {
    return PostCard(post: item.data);
  },
  onRefresh: () {
    print('รายการถูกรีเฟรชแล้ว!');
  },
  headerStyle: 'WaterDropHeader', // สไตล์ตัวบ่งชี้ pull
)
```

### สไตล์ Header

พารามิเตอร์ `headerStyle` รับค่า:
- `'WaterDropHeader'` (ค่าเริ่มต้น) - แอนิเมชันหยดน้ำ
- `'ClassicHeader'` - ตัวบ่งชี้ pull แบบคลาสสิก
- `'MaterialClassicHeader'` - สไตล์ Material design
- `'WaterDropMaterialHeader'` - หยดน้ำแบบ Material
- `'BezierHeader'` - แอนิเมชันเส้นโค้ง Bezier

### Callback การแบ่งหน้า

| Callback | คำอธิบาย |
|----------|-------------|
| `beforeRefresh` | เรียกก่อนเริ่มรีเฟรช |
| `onRefresh` | เรียกเมื่อรีเฟรชเสร็จสิ้น |
| `afterRefresh` | เรียกหลังโหลดข้อมูล รับข้อมูลสำหรับการแปลง |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

รวม pull-to-refresh กับรายการที่มีตัวคั่น:

``` dart
CollectionView<Message>.pullableSeparated(
  data: (iteration) async => await fetchMessages(page: iteration),
  builder: (context, item) {
    return MessageTile(message: item.data);
  },
  separatorBuilder: (context, index) => Divider(),
)
```

<div id="collection-view-pullable-grid"></div>

## CollectionView.pullableGrid

รวม pull-to-refresh กับเลย์เอาต์แบบ grid:

``` dart
CollectionView<Photo>.pullableGrid(
  data: (iteration) async => await fetchPhotos(page: iteration),
  builder: (context, item) {
    return Image.network(item.data.url);
  },
  crossAxisCount: 3,
  mainAxisSpacing: 4,
  crossAxisSpacing: 4,
)
```

<div id="loading-styles"></div>

## สไตล์การโหลด

ปรับแต่งตัวบ่งชี้การโหลดโดยใช้ `loadingStyle`:

``` dart
// การโหลดปกติพร้อม widget แบบกำหนดเอง
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("กำลังโหลดรายการ..."),
  ),
)

// เอฟเฟกต์การโหลดแบบ skeletonizer
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserCard(user: item.data),
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()),
    effect: SkeletonizerEffect.shimmer,
  ),
)
```

<div id="empty-state"></div>

## สถานะว่างเปล่า

แสดง widget แบบกำหนดเองเมื่อรายการว่างเปล่า:

``` dart
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  empty: Center(
    child: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.inbox, size: 64, color: Colors.grey),
        SizedBox(height: 16),
        Text('No items found'),
      ],
    ),
  ),
)
```

<div id="sorting-transforming"></div>

## การเรียงลำดับและแปลงข้อมูล

### การเรียงลำดับ

เรียงลำดับรายการก่อนแสดงผล:

``` dart
CollectionView<Task>(
  data: () async => await fetchTasks(),
  builder: (context, item) => TaskTile(task: item.data),
  sort: (List<Task> items) {
    items.sort((a, b) => a.dueDate.compareTo(b.dueDate));
    return items;
  },
)
```

### การแปลงข้อมูล

แปลงข้อมูลหลังโหลด:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // กรองเฉพาะผู้ใช้ที่ใช้งานอยู่
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## การอัปเดตสถานะ

คุณสามารถอัปเดต CollectionView ผ่านโค้ดได้โดยกำหนด `stateName` และเรียก `CollectionView.stateActions()`:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

ใช้ `stateActions` เพื่อจัดการรายการในขณะรันไทม์:

``` dart
final actions = CollectionView.stateActions("my_todo_list");

// รีเซ็ตและโหลดข้อมูลใหม่ตั้งแต่ต้น
actions.reset();

// รีเฟรชข้อมูล (ดึงข้อมูลใหม่และรีเซ็ตการแบ่งหน้า)
actions.refreshData();

// เพิ่มรายการไปที่ท้ายรายการ
actions.addItem(newTodo);

// แทรกรายการที่ตำแหน่งที่ระบุ
actions.insertItem(0, newTodo);

// ลบรายการตามดัชนี
actions.removeFromIndex(2);

// แทนที่รายการที่ดัชนีที่ระบุ
actions.updateItemAtIndex(0, updatedTodo);
```

เมธอด `stateActions` ทั้งหมดทำงานได้ถูกต้องในทุก rebuild สำหรับทั้งข้อมูลแบบ synchronous และ asynchronous `refreshData()` ยังรีเซ็ตตัวนับ iteration การแบ่งหน้าด้วย เพื่อให้รายการ pullable เริ่มต้นจากหน้า 1 ใหม่

<div id="parameters"></div>

## พารามิเตอร์อ้างอิง

### พารามิเตอร์ทั่วไป

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `data` | `Function()` | ฟังก์ชันที่คืนค่า `List<T>` หรือ `Future<List<T>>` |
| `builder` | `CollectionItemBuilder<T>` | ฟังก์ชัน builder สำหรับแต่ละรายการ |
| `empty` | `Widget?` | Widget ที่แสดงเมื่อรายการว่างเปล่า |
| `loadingStyle` | `LoadingStyle?` | ปรับแต่งตัวบ่งชี้การโหลด |
| `header` | `Widget?` | widget header เหนือรายการ |
| `stateName` | `String?` | ชื่อสำหรับการจัดการสถานะ |
| `sort` | `Function(List<T>)?` | ฟังก์ชันเรียงลำดับรายการ |
| `transform` | `Function(List<T>)?` | ฟังก์ชันแปลงข้อมูล |
| `spacing` | `double?` | ระยะห่างระหว่างรายการ |

### พารามิเตอร์เฉพาะ Pullable

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | ฟังก์ชันข้อมูลแบบแบ่งหน้า |
| `enablePullDown` | `bool` | เปิดใช้งานท่าทาง pull-to-refresh (ค่าเริ่มต้น: `true`) |
| `onRefresh` | `Function()?` | Callback เมื่อรีเฟรชเสร็จสิ้น |
| `beforeRefresh` | `Function()?` | Callback ก่อนรีเฟรช |
| `afterRefresh` | `Function(dynamic)?` | Callback หลังโหลดข้อมูล |
| `headerStyle` | `String?` | สไตล์ตัวบ่งชี้ pull |
| `footerLoadingIcon` | `Widget?` | ตัวบ่งชี้การโหลดแบบกำหนดเองสำหรับการแบ่งหน้า |

### พารามิเตอร์เฉพาะ Grid

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `crossAxisCount` | `int` | จำนวนคอลัมน์ (ค่าเริ่มต้น: 2) |
| `mainAxisSpacing` | `double` | ระยะห่างแนวตั้งระหว่างรายการ |
| `crossAxisSpacing` | `double` | ระยะห่างแนวนอนระหว่างรายการ |

### พารามิเตอร์ ListView

พารามิเตอร์ `ListView` มาตรฐานทั้งหมดรองรับเช่นกัน: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` และอื่นๆ
