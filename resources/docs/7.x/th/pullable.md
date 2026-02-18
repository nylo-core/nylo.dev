# Pullable

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
- [Constructors](#constructors "Constructors")
- [PullableConfig](#pullable-config "PullableConfig")
- [สไตล์ Header](#header-styles "สไตล์ Header")
- [ดึงขึ้นเพื่อโหลดเพิ่มเติม](#pull-up "ดึงขึ้นเพื่อโหลดเพิ่มเติม")
- [Header และ Footer แบบกำหนดเอง](#custom-headers "Header และ Footer แบบกำหนดเอง")
- [Controller](#controller "Controller")
- [Extension Method](#extension-method "Extension Method")
- [การผสานกับ CollectionView](#collection-view "การผสานกับ CollectionView")
- [ตัวอย่าง](#examples "ตัวอย่างจริง")

<div id="introduction"></div>

## บทนำ

วิดเจ็ต **Pullable** เพิ่มฟังก์ชันการดึงเพื่อรีเฟรชและโหลดเพิ่มเติมให้กับเนื้อหาที่เลื่อนได้ทุกชนิด มันห่อหุ้มวิดเจ็ตลูกของคุณด้วยพฤติกรรมการรีเฟรชและการแบ่งหน้าที่ขับเคลื่อนด้วยท่าทาง รองรับสไตล์แอนิเมชัน header หลายแบบ

สร้างขึ้นบนแพ็คเกจ `pull_to_refresh_flutter3` โดย Pullable ให้ API ที่สะอาดพร้อม named constructors สำหรับการตั้งค่าทั่วไป

``` dart
Pullable(
  onRefresh: () async {
    // ดึงข้อมูลใหม่
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

ห่อหุ้มวิดเจ็ตที่เลื่อนได้ด้วย `Pullable`:

``` dart
Pullable(
  onRefresh: () async {
    await loadLatestPosts();
  },
  child: ListView.builder(
    itemCount: posts.length,
    itemBuilder: (context, index) => PostCard(post: posts[index]),
  ),
)
```

เมื่อผู้ใช้ดึงลงบนรายการ callback `onRefresh` จะถูกเรียกใช้งาน ตัวบ่งชี้การรีเฟรชจะเสร็จสิ้นโดยอัตโนมัติเมื่อ callback ทำงานเสร็จ

<div id="constructors"></div>

## Constructors

`Pullable` มี named constructors สำหรับการตั้งค่าทั่วไป:

| Constructor | สไตล์ Header | คำอธิบาย |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Constructor เริ่มต้น |
| `Pullable.classicHeader()` | Classic | สไตล์ pull-to-refresh แบบคลาสสิก |
| `Pullable.waterDropHeader()` | Water Drop | แอนิเมชันหยดน้ำ |
| `Pullable.materialClassicHeader()` | Material Classic | สไตล์คลาสสิกแบบ Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | สไตล์หยดน้ำแบบ Material |
| `Pullable.bezierHeader()` | Bezier | แอนิเมชันเส้นโค้ง Bezier |
| `Pullable.noBounce()` | กำหนดเองได้ | ลดการเด้งด้วย `ClampingScrollPhysics` |
| `Pullable.custom()` | วิดเจ็ตกำหนดเอง | ใช้วิดเจ็ต header/footer ของคุณเอง |
| `Pullable.builder()` | กำหนดเองได้ | ควบคุม `PullableConfig` ได้ทั้งหมด |

### ตัวอย่าง

``` dart
// Classic header
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material header
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// ไม่มีเอฟเฟกต์การเด้ง
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// วิดเจ็ต header กำหนดเอง
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

สำหรับการควบคุมอย่างละเอียด ใช้ `PullableConfig` กับ constructor `Pullable.builder()`:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    headerType: PullableHeaderType.materialClassic,
    onRefresh: () async => await refreshData(),
    onLoading: () async => await loadMoreData(),
    refreshCompleteDelay: Duration(milliseconds: 500),
    loadCompleteDelay: Duration(milliseconds: 300),
    physics: BouncingScrollPhysics(),
  ),
  child: myListView,
)
```

### ตัวเลือกการตั้งค่าทั้งหมด

| คุณสมบัติ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | เปิดใช้งานการดึงลงเพื่อรีเฟรช |
| `enablePullUp` | `bool` | `false` | เปิดใช้งานการดึงขึ้นเพื่อโหลดเพิ่ม |
| `physics` | `ScrollPhysics?` | null | ฟิสิกส์การเลื่อนแบบกำหนดเอง |
| `onRefresh` | `Future<void> Function()?` | null | Callback การรีเฟรช |
| `onLoading` | `Future<void> Function()?` | null | Callback การโหลดเพิ่ม |
| `headerType` | `PullableHeaderType` | `waterDrop` | สไตล์แอนิเมชัน header |
| `customHeader` | `Widget?` | null | วิดเจ็ต header กำหนดเอง |
| `customFooter` | `Widget?` | null | วิดเจ็ต footer กำหนดเอง |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | ดีเลย์ก่อนการรีเฟรชเสร็จสิ้น |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | ดีเลย์ก่อนการโหลดเสร็จสิ้น |
| `enableOverScroll` | `bool` | `true` | อนุญาตเอฟเฟกต์การเลื่อนเกิน |
| `cacheExtent` | `double?` | null | ขอบเขตแคชการเลื่อน |
| `semanticChildCount` | `int?` | null | จำนวน semantic children |
| `dragStartBehavior` | `DragStartBehavior` | `start` | วิธีเริ่มต้นท่าทางการลาก |

<div id="header-styles"></div>

## สไตล์ Header

เลือกจากแอนิเมชัน header ในตัว 5 แบบ:

``` dart
enum PullableHeaderType {
  classic,           // ตัวบ่งชี้การดึงแบบคลาสสิก
  waterDrop,         // แอนิเมชันหยดน้ำ (ค่าเริ่มต้น)
  materialClassic,   // Material Design แบบคลาสสิก
  waterDropMaterial,  // หยดน้ำแบบ Material
  bezier,            // แอนิเมชันเส้นโค้ง Bezier
}
```

ตั้งค่าสไตล์ผ่าน constructor หรือ config:

``` dart
// ผ่าน named constructor
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// ผ่าน config
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## ดึงขึ้นเพื่อโหลดเพิ่มเติม

เปิดใช้งานการแบ่งหน้าด้วยการโหลดแบบดึงขึ้น:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // รีเซ็ตเป็นหน้า 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // โหลดหน้าถัดไป
      page++;
      List<Item> more = await fetchItems(page: page);
      items.addAll(more);
      setState(() {});
    },
  ),
  child: ListView.builder(
    itemCount: items.length,
    itemBuilder: (context, index) => ItemTile(item: items[index]),
  ),
)
```

<div id="custom-headers"></div>

## Header และ Footer แบบกำหนดเอง

ใส่วิดเจ็ต header และ footer ของคุณเอง:

``` dart
Pullable.custom(
  customHeader: Container(
    height: 60,
    alignment: Alignment.center,
    child: CircularProgressIndicator(),
  ),
  customFooter: Container(
    height: 40,
    alignment: Alignment.center,
    child: Text("Loading more..."),
  ),
  enablePullUp: true,
  onRefresh: () async => await refreshData(),
  onLoading: () async => await loadMore(),
  child: myListView,
)
```

<div id="controller"></div>

## Controller

ใช้ `RefreshController` สำหรับการควบคุมด้วยโปรแกรม:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// ทริกเกอร์การรีเฟรชด้วยโปรแกรม
_controller.triggerRefresh();

// ทริกเกอร์การโหลดด้วยโปรแกรม
_controller.triggerLoading();

// ตรวจสอบสถานะ
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### Extension Methods บน RefreshController

| เมธอด/Getter | ประเภทที่ส่งคืน | คำอธิบาย |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | ทริกเกอร์การรีเฟรชด้วยตนเอง |
| `triggerLoading()` | `void` | ทริกเกอร์การโหลดเพิ่มด้วยตนเอง |
| `isRefreshing` | `bool` | กำลังรีเฟรชอยู่หรือไม่ |
| `isLoading` | `bool` | กำลังโหลดอยู่หรือไม่ |

<div id="extension-method"></div>

## Extension Method

วิดเจ็ตใดๆ สามารถห่อด้วย pull-to-refresh โดยใช้ extension `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

พร้อม config กำหนดเอง:

``` dart
myListView.pullable(
  onRefresh: () async => await refreshData(),
  pullableConfig: PullableConfig(
    headerType: PullableHeaderType.classic,
    enablePullUp: true,
    onLoading: () async => await loadMore(),
  ),
)
```

<div id="collection-view"></div>

## การผสานกับ CollectionView

`CollectionView` มีตัวแปร pullable พร้อมการแบ่งหน้าในตัว:

### CollectionView.pullable

``` dart
CollectionView<User>.pullable(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  onRefresh: () => print('Refreshed!'),
  headerStyle: 'WaterDropHeader',
)
```

### CollectionView.pullableSeparated

``` dart
CollectionView<User>.pullableSeparated(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  separatorBuilder: (context, index) => Divider(),
)
```

### CollectionView.pullableGrid

``` dart
CollectionView<Product>.pullableGrid(
  data: (iteration) async => api.getProducts(page: iteration),
  builder: (context, item) => ProductCard(product: item.data),
  crossAxisCount: 2,
  mainAxisSpacing: 8,
  crossAxisSpacing: 8,
)
```

### พารามิเตอร์เฉพาะสำหรับ Pullable

| พารามิเตอร์ | ประเภท | คำอธิบาย |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback ข้อมูลแบบแบ่งหน้า (iteration เริ่มจาก 1) |
| `onRefresh` | `Function()?` | Callback หลังการรีเฟรช |
| `beforeRefresh` | `Function()?` | Hook ก่อนเริ่มรีเฟรช |
| `afterRefresh` | `Function(dynamic)?` | Hook หลังรีเฟรชพร้อมข้อมูล |
| `headerStyle` | `String?` | ชื่อประเภท header (เช่น `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | ตัวบ่งชี้การโหลดกำหนดเองสำหรับ footer |

<div id="examples"></div>

## ตัวอย่าง

### รายการแบบแบ่งหน้าพร้อมการรีเฟรช

``` dart
class _PostListState extends NyState<PostListPage> {
  List<Post> posts = [];
  int page = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Pullable.builder(
        config: PullableConfig(
          enablePullDown: true,
          enablePullUp: true,
          headerType: PullableHeaderType.materialClassic,
          onRefresh: () async {
            page = 1;
            posts = await api<PostApiService>((request) => request.getPosts(page: page));
            setState(() {});
          },
          onLoading: () async {
            page++;
            List<Post> more = await api<PostApiService>((request) => request.getPosts(page: page));
            posts.addAll(more);
            setState(() {});
          },
        ),
        child: ListView.builder(
          itemCount: posts.length,
          itemBuilder: (context, index) => PostCard(post: posts[index]),
        ),
      ),
    );
  }
}
```

### การรีเฟรชอย่างง่ายด้วย Extension

``` dart
ListView(
  children: notifications
    .map((n) => ListTile(
      title: Text(n.title),
      subtitle: Text(n.body),
    ))
    .toList(),
).pullable(
  onRefresh: () async {
    notifications = await fetchNotifications();
    setState(() {});
  },
)
```
