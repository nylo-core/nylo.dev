# State Management

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [เมื่อใดควรใช้ State Management](#when-to-use-state-management "เมื่อใดควรใช้ State Management")
- [วงจรชีวิต](#lifecycle "วงจรชีวิต")
- [State Actions](#state-actions "State Actions")
  - [NyState - State Actions](#state-actions-nystate "NyState - State Actions")
  - [NyPage - State Actions](#state-actions-nypage "NyPage - State Actions")
- [การอัปเดต State](#updating-a-state "การอัปเดต State")
- [การสร้าง Widget แรกของคุณ](#building-your-first-widget "การสร้าง Widget แรกของคุณ")

<div id="introduction"></div>

## บทนำ

State management ช่วยให้คุณอัปเดตส่วนเฉพาะของ UI โดยไม่ต้องสร้างหน้าทั้งหมดใหม่ ใน {{ config('app.name') }} v7 คุณสามารถสร้าง widget ที่สื่อสารและอัปเดตกันข้ามแอปของคุณได้

{{ config('app.name') }} มีสองคลาสสำหรับ state management:
- **`NyState`** — สำหรับสร้าง widget ที่ใช้ซ้ำได้ (เช่น badge ตะกร้าสินค้า ตัวนับการแจ้งเตือน หรือตัวแสดงสถานะ)
- **`NyPage`** — สำหรับสร้างหน้าในแอปพลิเคชันของคุณ (ขยายจาก `NyState` พร้อมคุณสมบัติเฉพาะหน้า)

ใช้ state management เมื่อคุณต้องการ:
- อัปเดต widget จากส่วนอื่นของแอป
- รักษา widget ให้ซิงค์กับข้อมูลที่ใช้ร่วมกัน
- หลีกเลี่ยงการสร้างหน้าทั้งหมดใหม่เมื่อเปลี่ยนแปลงเพียงส่วนหนึ่งของ UI


### มาทำความเข้าใจ State Management กันก่อน

ทุกอย่างใน Flutter คือ widget พวกมันเป็นเพียงส่วนเล็กๆ ของ UI ที่คุณสามารถรวมกันเพื่อสร้างแอปที่สมบูรณ์

เมื่อคุณเริ่มสร้างหน้าที่ซับซ้อน คุณจะต้องจัดการ state ของ widget ของคุณ ซึ่งหมายความว่าเมื่อมีบางอย่างเปลี่ยนแปลง เช่น ข้อมูล คุณสามารถอัปเดต widget นั้นโดยไม่ต้องสร้างหน้าทั้งหมดใหม่

มีเหตุผลมากมายที่สิ่งนี้สำคัญ แต่เหตุผลหลักคือประสิทธิภาพ หากคุณมี widget ที่เปลี่ยนแปลงอยู่ตลอดเวลา คุณไม่ต้องการสร้างหน้าทั้งหมดใหม่ทุกครั้งที่มันเปลี่ยนแปลง

นี่คือจุดที่ State Management เข้ามา มันช่วยให้คุณจัดการ state ของ widget ในแอปพลิเคชันของคุณ


<div id="when-to-use-state-management"></div>

### เมื่อใดควรใช้ State Management

คุณควรใช้ State Management เมื่อคุณมี widget ที่ต้องอัปเดตโดยไม่ต้องสร้างหน้าทั้งหมดใหม่

ตัวอย่างเช่น ลองจินตนาการว่าคุณได้สร้างแอป ecommerce คุณได้สร้าง widget เพื่อแสดงจำนวนรายการทั้งหมดในตะกร้าของผู้ใช้
เรียก widget นี้ว่า `Cart()`

Widget `Cart` ที่จัดการ state ใน Nylo จะมีลักษณะดังนี้:

**ขั้นตอนที่ 1:** กำหนด widget พร้อมชื่อ state แบบ static

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**ขั้นตอนที่ 2:** สร้างคลาส state ที่ extend จาก `NyState`

``` dart
/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Register the state name
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Load initial data
  };

  @override
  void stateUpdated(data) {
    reboot(); // Reload the widget when state updates
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**ขั้นตอนที่ 3:** สร้างฟังก์ชันตัวช่วยเพื่ออ่านและอัปเดตตะกร้า

``` dart
/// Get the cart value from storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Set the cart value and notify the widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // This triggers stateUpdated() on the widget
}
```

มาวิเคราะห์กัน

1. Widget `Cart` เป็น `StatefulWidget`

2. `_CartState` extend จาก `NyState<Cart>`

3. คุณต้องกำหนดชื่อสำหรับ `state` ซึ่งใช้เพื่อระบุ state

4. เมธอด `boot()` จะถูกเรียกเมื่อ widget ถูกโหลดครั้งแรก

5. เมธอด `stateUpdate()` จัดการสิ่งที่เกิดขึ้นเมื่อ state ถูกอัปเดต

หากคุณต้องการลองตัวอย่างนี้ในโปรเจกต์ {{ config('app.name') }} ของคุณ ให้สร้าง widget ใหม่ชื่อ `Cart`

``` bash
metro make:state_managed_widget cart
```

จากนั้นคุณสามารถคัดลอกตัวอย่างด้านบนและลองใช้ในโปรเจกต์ของคุณ

ตอนนี้ เพื่ออัปเดตตะกร้า คุณสามารถเรียกใช้ดังนี้

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## วงจรชีวิต

วงจรชีวิตของ widget `NyState` มีดังนี้:

1. `init()` - เมธอดนี้จะถูกเรียกเมื่อ state ถูกเริ่มต้น

2. `stateUpdated(data)` - เมธอดนี้จะถูกเรียกเมื่อ state ถูกอัปเดต

    หากคุณเรียก `updateState(MyStateName.state, data: "The Data")` มันจะทริกเกอร์ **stateUpdated(data)** ให้ถูกเรียก

เมื่อ state ถูกเริ่มต้นครั้งแรก คุณจะต้องกำหนดวิธีที่คุณต้องการจัดการ state


<div id="state-actions"></div>

## State Actions

State actions ช่วยให้คุณทริกเกอร์เมธอดเฉพาะบน widget จากที่ใดก็ได้ในแอปของคุณ คิดว่าพวกมันเป็นคำสั่งที่มีชื่อที่คุณสามารถส่งไปยัง widget ได้

ใช้ state actions เมื่อคุณต้องการ:
- ทริกเกอร์พฤติกรรมเฉพาะบน widget (ไม่ใช่แค่รีเฟรช)
- ส่งข้อมูลไปยัง widget และให้มันตอบสนองในลักษณะเฉพาะ
- สร้างพฤติกรรม widget ที่ใช้ซ้ำได้ซึ่งสามารถเรียกใช้จากหลายที่

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

ใน widget ของคุณ คุณสามารถกำหนด action ที่ต้องการจัดการได้

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Example with data
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

จากนั้น คุณสามารถเรียกเมธอด `stateAction` จากที่ใดก็ได้ในแอปพลิเคชันของคุณ

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

คุณยังสามารถกำหนด state actions โดยใช้เมธอด `whenStateAction` ใน getter `init` ของคุณ

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - State Actions

ขั้นแรก สร้าง stateful widget

``` bash
metro make:stateful_widget [widget_name]
```
ตัวอย่าง: metro make:stateful_widget user_avatar

สิ่งนี้จะสร้าง widget ใหม่ในไดเรกทอรี `lib/resources/widgets/`

หากคุณเปิดไฟล์นั้น คุณจะสามารถกำหนด state actions ของคุณได้

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Example
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Example
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

สุดท้าย คุณสามารถส่ง action จากที่ใดก็ได้ในแอปพลิเคชันของคุณ

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - State Actions

หน้าสามารถรับ state actions ได้เช่นกัน สิ่งนี้มีประโยชน์เมื่อคุณต้องการทริกเกอร์พฤติกรรมระดับหน้าจาก widget หรือหน้าอื่น

ขั้นแรก สร้าง state managed page ของคุณ

``` bash
metro make:page my_page
```

สิ่งนี้จะสร้าง state managed page ใหม่ชื่อ `MyPage` ในไดเรกทอรี `lib/resources/pages/`

หากคุณเปิดไฟล์นั้น คุณจะสามารถกำหนด state actions ของคุณได้

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => true;

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

สุดท้าย คุณสามารถส่ง action จากที่ใดก็ได้ในแอปพลิเคชันของคุณ

``` dart
stateAction('test_page_action', state: MyPage.state);
// prints 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Reset data in page

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// shows a success toast with the message
```

คุณยังสามารถกำหนด state actions โดยใช้เมธอด `whenStateAction`

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```

จากนั้นคุณสามารถส่ง action จากที่ใดก็ได้ในแอปพลิเคชันของคุณ

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## การอัปเดต State

คุณสามารถอัปเดต state โดยเรียกเมธอด `updateState()`

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

สามารถเรียกได้จากที่ใดก็ได้ในแอปพลิเคชันของคุณ

**ดูเพิ่มเติม:** [NyState](/docs/{{ $version }}/ny-state) สำหรับรายละเอียดเพิ่มเติมเกี่ยวกับตัวช่วย state management และเมธอดวงจรชีวิต


<div id="building-your-first-widget"></div>

## การสร้าง Widget แรกของคุณ

ในโปรเจกต์ Nylo ของคุณ ให้รันคำสั่งต่อไปนี้เพื่อสร้าง widget ใหม่

``` bash
metro make:stateful_widget todo_list
```

สิ่งนี้จะสร้าง `NyState` widget ใหม่ชื่อ `TodoList`

> หมายเหตุ: widget ใหม่จะถูกสร้างในไดเรกทอรี `lib/resources/widgets/`
