# State Managed Widgets

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [เลือกรูปแบบของคุณ](#choose-your-pattern "เลือกรูปแบบของคุณ")
- [State Actions](#state-actions "State Actions")
  - [การกำหนด Handlers](#defining-handlers "การกำหนด Handlers")
  - [การเรียกใช้ Actions](#triggering-actions "การเรียกใช้ Actions")
  - [Handlers แบบมีและไม่มีข้อมูล](#handlers-with-and-without-data "Handlers แบบมีและไม่มีข้อมูล")
  - [การใช้ StateActions Instance](#using-a-state-actions-instance "การใช้ StateActions Instance")
- [รูปแบบ: State Managed Pages (NyPage)](#pattern-ny-page "รูปแบบ: State Managed Pages")
- [รูปแบบ: Stateful Widgets (NyState)](#pattern-ny-state "รูปแบบ: Stateful Widgets")
- [รูปแบบ: State Managed Widgets (NyStateManaged)](#pattern-ny-state-managed "รูปแบบ: State Managed Widgets")
  - [ขั้นสูง: หลาย Instance ที่แยกจากกัน](#advanced-multiple-isolated-instances "ขั้นสูง: หลาย Instance ที่แยกจากกัน")
- [อ้างอิง Lifecycle](#lifecycle "อ้างอิง Lifecycle")

<div id="introduction"></div>

## บทนำ

State managed widgets ช่วยให้คุณอัปเดตส่วนเฉพาะของ UI จากที่ใดก็ได้ในแอปโดยไม่ต้องสร้างหน้าทั้งหมดใหม่ ใน {{ config('app.name') }} v7 คุณสามารถเรียกใช้ **state actions** ที่มีชื่อบน widget หรือหน้าเป้าหมาย และ handler ที่ตรงกันจะทำงาน

โมเดลความคิดนั้นง่าย:

- State managed widget หรือหน้าแต่ละรายการมี **state key** ที่ไม่ซ้ำกัน (สตริง)
- มันกำหนด map ของ **named actions** ที่รู้วิธีจัดการ
- จากที่ใดก็ได้ในแอปของคุณ คุณสามารถเรียกใช้
```
stateAction("action_name", state: TargetWidget.state)
``` 

มีสามรูปแบบสำหรับการสร้าง state managed UI ทั้งหมดใช้กลไก `stateAction` เดียวกัน — ความแตกต่างเพียงอย่างเดียวคือประเภทของ UI ที่คุณจัดการ


<div id="choose-your-pattern"></div>

## เลือกรูปแบบของคุณ

| คุณต้องการ... | ใช้ | คำสั่ง Scaffold |
|---|---|---|
| จัดการ state ของทั้งหน้า | `NyPage` | `metro make:page my_page` |
| จัดการ state ของ widget ที่มี instance เดียว | `NyState` | `metro make:stateful_widget my_widget` |
| จัดการ state ของ widget ที่มีหลาย instance ที่แยกจากกัน | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

อ่านส่วน [State Actions](#state-actions) ก่อน — มันอธิบาย API ที่ทุกรูปแบบใช้ จากนั้นไปที่รูปแบบที่เหมาะกับกรณีของคุณ


<div id="state-actions"></div>

## State Actions

State actions คือคำสั่งที่มีชื่อซึ่ง widget หรือหน้ารู้วิธีจัดการ คุณกำหนด map จากชื่อ action ไปยัง handler functions และเรียกใช้ตามชื่อจากที่ใดก็ได้ในแอป

ใช้ state actions เมื่อคุณต้องการ:
- เรียกใช้พฤติกรรมเฉพาะบน widget หรือหน้า (ไม่ใช่แค่รีเฟรชทั่วไป)
- ส่งข้อมูลไปยัง widget และให้มันตอบสนองในลักษณะที่กำหนด
- สร้างพฤติกรรม widget ที่นำกลับมาใช้ซ้ำได้ซึ่งสามารถเรียกใช้จากหลายที่

<div id="defining-handlers"></div>

### การกำหนด Handlers

Handlers อยู่ใน getter `stateActions` บนคลาส `NyState` หรือ `NyPage` ของคุณ Map keys คือชื่อ action; values คือฟังก์ชันที่จะทำงานเมื่อ action นั้นถูกเรียกใช้

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

Handlers มีหน้าที่เรียก `setState` เองหากต้องการให้ widget สร้างใหม่

Handler `apply_discount` ด้านบนรับ argument `code` — ประกาศ positional parameter เดียวเมื่อ handler ต้องการ payload ที่ส่งผ่านทาง
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

ใช้รูปแบบไม่มี argument `()` เมื่อ action ไม่มี payload


<div id="triggering-actions"></div>

### การเรียกใช้ Actions

ใช้ฟังก์ชัน `stateAction` ระดับบนสุดเพื่อยิง action จากที่ใดก็ได้ — widget อื่น, controller, event handler, API callback และอื่นๆ

``` dart
// ยิง action โดยไม่มีข้อมูล
stateAction("clear_cart", state: Cart.state);

// ยิง action พร้อมข้อมูล
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

argument `state:` คือ **state key** ของเป้าหมาย:
- สำหรับ widgets — `MyWidget.state` (สตริง)
- สำหรับหน้า — `MyPage.path` (route)


<div id="handlers-with-and-without-data"></div>

### Handlers แบบมีและไม่มีข้อมูล

Handlers สามารถเป็น sync หรือ async และสามารถกำหนดได้ทั้งแบบมีหรือไม่มี argument `data`:

``` dart
@override
Map<String, Function> get stateActions => {
  // ไม่มีข้อมูล — handler ทำงานตามที่เป็น
  "reset": () {
    _value = null;
    setState(() {});
  },

  // มีข้อมูล — รับสิ่งที่ส่งผ่าน argument `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // รองรับ async — framework จะ await handler
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

หากคุณส่ง `data:` ไปยัง `stateAction` แต่ handler ไม่รับ argument ข้อมูลจะถูกละเว้นอย่างง่ายๆ


<div id="using-a-state-actions-instance"></div>

### การใช้ StateActions Instance

หาก widget เปิดเผย `StateActions` instance แบบ typed (มักผ่าน static method `stateActions(stateName)`) คุณสามารถเรียก `.action(...)` บนมันโดยตรงแทนที่จะใช้ฟังก์ชันแบบ free function วิธีนี้สะอาดกว่าเมื่อยิง action หลายรายการไปยังเป้าหมายเดียวกัน:

``` dart
// ใช้ free function
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// ใช้ StateActions instance — เทียบเท่ากัน ลดการซ้ำซ้อน
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Widget ในตัวหลายรายการ (`InputField`, `CollectionView`, `LanguageSwitcher`, ตระกูล `NyForm*`) มาพร้อมกับคลาส `StateActions` แบบ typed ที่มี methods ที่มีชื่อเช่น `.clear()`, `.setValue(...)`, `.refresh()` — ตรวจสอบเอกสารของ widget เพื่อดูสิ่งที่มีให้


<div id="pattern-ny-page"></div>

## รูปแบบ: State Managed Pages (NyPage)

ใช้เมื่อคุณต้องการเรียกใช้พฤติกรรมบนหน้าเต็มจากที่อื่นในแอป — ตัวอย่างเช่น รีเฟรชหน้าเมื่อ event ยิง หรือล้าง form state จาก child widget

**ขั้นตอนที่ 1:** สร้าง scaffold ของหน้า

``` bash
metro make:page my_page
```

สิ่งนี้จะสร้าง `NyPage` ใน `lib/resources/pages/`:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**ขั้นตอนที่ 2:** เปลี่ยน `stateManaged` เป็น `true`

โดยค่าเริ่มต้น หน้า**ไม่**สมัครรับ state events — เพื่อหลีกเลี่ยง listeners ที่ไม่จำเป็นบนหน้าที่ไม่ต้องการ หากต้องการเปิดใช้ state actions บนหน้า ให้เปลี่ยน getter:

``` dart
@override
bool get stateManaged => true;
```

**ขั้นตอนที่ 3:** เพิ่ม map `stateActions`

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**ขั้นตอนที่ 4:** เรียกใช้ action จากที่ใดก็ได้โดยใช้ `path` ของหน้า

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> หน้าใช้ `MyPage.path` เป็น key, widgets ใช้ `MyWidget.state` เป็น key นี่คือความแตกต่างเพียงอย่างเดียวที่ call site


<div id="pattern-ny-state"></div>

## รูปแบบ: Stateful Widgets (NyState)

ใช้สำหรับ widgets ที่นำกลับมาใช้ซ้ำได้ซึ่งมีอยู่เป็น instance เดียวต่อหน้า — profile card, header bar, status indicator หากคุณ render widget เพียงหนึ่ง instance ในแต่ละครั้ง นี่คือรูปแบบที่ถูกต้อง

**ขั้นตอนที่ 1:** สร้าง scaffold ของ stateful widget

``` bash
metro make:stateful_widget profile_card
```

สิ่งนี้จะสร้างสิ่งต่อไปนี้ใน `lib/resources/widgets/`:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

Scaffold ให้ `NyState` widget ที่ใช้งานได้ — แต่**ยังไม่ได้จัดการ state** หากต้องการให้มันตอบสนองต่อ state actions คุณต้องเพิ่มสี่สิ่ง:

1. `state` key บนคลาส widget — สตริงที่ไม่ซ้ำกันที่ส่วนอื่นของแอปจะใช้เป็นเป้าหมาย
2. Constructor parameter ที่รับ state key และส่งต่อไปยังคลาส state
3. Constructor บนคลาส state ที่กำหนด `stateName`
4. Map `stateActions` ที่กำหนด handlers

**ขั้นตอนที่ 2:** แปลง scaffold เป็น state managed widget

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ เรียกใช้สิ่งนี้จากที่ใดก็ได้ในแอปเพื่อ trigger handler ด้านล่าง
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

สิ่งที่เปลี่ยนแปลง:
- `static String get state => "profile_card";` กำหนด public state key
- `createState() => _ProfileCardState(state)` ส่ง key ไปยังคลาส state
- `_ProfileCardState(String? state) { this.stateName = state; }` ลงทะเบียน state instance นี้ภายใต้ key นั้น เพื่อให้ framework รู้ว่าจะส่ง action ไปยัง widget ใด
- `stateActions` ประกาศ named handlers

**ขั้นตอนที่ 3:** เรียกใช้จากที่ใดก็ได้

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

คุณสามารถเพิ่ม handlers ได้มากเท่าที่ต้องการ แบบมีหรือไม่มีข้อมูล:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## รูปแบบ: State Managed Widgets (NyStateManaged)

ใช้เมื่อคุณต้องการ **หลาย instance อิสระของ widget เดียวกัน** บนหน้าจอพร้อมกัน — ตัวอย่างเช่น cart badge ใน header และอีกอันใน sidebar ที่ควรอัปเดตอย่างอิสระ

`NyStateManaged` เพิ่ม parameter `stateName` เพื่อให้แต่ละ instance สามารถระบุได้แยกต่างหาก หากคุณ render เพียงหนึ่ง instance ของ widget ให้ใช้ `NyState` แทน — มันง่ายกว่า

**ขั้นตอนที่ 1:** สร้าง scaffold ของ state managed widget

``` bash
metro make:state_managed_widget cart
```

สิ่งนี้จะสร้างสิ่งต่อไปนี้ใน `lib/resources/widgets/`:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart";

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}

class _CartState extends NyState<Cart> {
  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () {
    // logic การ initialize ที่นี่
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // เรียกใช้ actions จากที่ใดก็ได้ในแอปของคุณ
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**ขั้นตอนที่ 2:** เติม state ให้สมบูรณ์ — โหลดข้อมูลใน `init` กำหนด handlers และ render

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**ขั้นตอนที่ 3:** เรียกใช้ actions โดยใช้ static `action()` helper ที่สร้างขึ้น

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

สิ่งนี้เทียบเท่ากับ `stateAction("reload_cart", state: Cart.state)` — static helper เพียงแค่ลด boilerplate


<div id="advanced-multiple-isolated-instances"></div>

### ขั้นสูง: หลาย Instance ที่แยกจากกัน

เหตุผลที่ `NyStateManaged` มีอยู่คือเพื่อรองรับหลาย instance อิสระของ widget เดียวกัน แต่ละ instance ได้รับ `stateName` ของตัวเอง ซึ่งสร้าง state key แบบ namespaced

Render สอง cart ที่มีชื่อต่างกัน:

``` dart
Column(
  children: [
    Cart(stateName: "header"),
    Cart(stateName: "sidebar"),
  ],
)
```

ตอนนี้คุณสามารถอัปเดตแต่ละรายการอย่างอิสระ:

``` dart
// Reload เฉพาะ header cart
Cart.action("reload_cart", stateName: "header");

// Reload เฉพาะ sidebar cart
Cart.action("reload_cart", stateName: "sidebar");

// ไม่มี stateName — เป้าหมายคือ default unnamed instance
Cart.action("reload_cart");
```

Helper `_stateFor` จัดการ namespacing: `Cart(stateName: "header")` ลงทะเบียนภายใต้ key `"cart_header"` และ `Cart.action(..., stateName: "header")` เล็งไปที่ key นั้นอย่างแม่นยำ


<div id="lifecycle"></div>

## อ้างอิง Lifecycle

State managed widgets และหน้าแชร์ lifecycle hooks สำคัญสองรายการ:

1. **`init()`** — เรียกครั้งเดียวเมื่อ state ถูกสร้างครั้งแรก ใช้เพื่อโหลดข้อมูลเริ่มต้น

2. **`stateUpdated(data)`** — เรียกทุกครั้งที่ state action ถูกยิงกับ state นี้ argument `data` คือ payload เต็ม (รวมชื่อ action และข้อมูลของ action) Override มันหากคุณต้องการตอบสนองต่อ *ทุก* state action — ส่วนใหญ่แล้ว การกำหนด handlers ใน `stateActions` คือสิ่งที่คุณต้องการแทน

**ดูเพิ่มเติม:** [NyState](/docs/{{ $version }}/ny-state) สำหรับชุดเต็มของ state helpers และ lifecycle methods
