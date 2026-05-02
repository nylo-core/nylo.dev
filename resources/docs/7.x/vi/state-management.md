# Widget Quản lý State

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Chọn mẫu của bạn](#choose-your-pattern "Chọn mẫu của bạn")
- [State Actions](#state-actions "State Actions")
  - [Định nghĩa Handler](#defining-handlers "Định nghĩa Handler")
  - [Kích hoạt Actions](#triggering-actions "Kích hoạt Actions")
  - [Handler có và không có dữ liệu](#handlers-with-and-without-data "Handler có và không có dữ liệu")
  - [Sử dụng Instance StateActions](#using-a-state-actions-instance "Sử dụng Instance StateActions")
- [Mẫu: Trang quản lý State (NyPage)](#pattern-ny-page "Mẫu: Trang quản lý State")
- [Mẫu: Stateful Widgets (NyState)](#pattern-ny-state "Mẫu: Stateful Widgets")
- [Mẫu: Widget quản lý State (NyStateManaged)](#pattern-ny-state-managed "Mẫu: Widget quản lý State")
  - [Nâng cao: Nhiều instance độc lập](#advanced-multiple-isolated-instances "Nâng cao: Nhiều instance độc lập")
- [Tham chiếu Vòng đời](#lifecycle "Tham chiếu Vòng đời")

<div id="introduction"></div>

## Giới thiệu

Widget quản lý state cho phép bạn cập nhật các phần cụ thể của UI từ bất kỳ đâu trong ứng dụng — mà không cần rebuild toàn bộ trang. Trong {{ config('app.name') }} v7, bạn kích hoạt các **state action** được đặt tên trên widget hoặc trang đích, và handler tương ứng sẽ chạy.

Mô hình tư duy rất đơn giản:

- Mỗi widget hoặc trang được quản lý state có một **state key** duy nhất (một string)
- Nó định nghĩa một map các **action được đặt tên** mà nó biết cách xử lý
- Từ bất kỳ đâu trong ứng dụng, bạn có thể gọi
```
stateAction("action_name", state: TargetWidget.state)
``` 

Có ba mẫu để xây dựng UI được quản lý state. Tất cả đều dùng chung cơ chế `stateAction` — sự khác biệt duy nhất là loại UI bạn đang quản lý.


<div id="choose-your-pattern"></div>

## Chọn mẫu của bạn

| Bạn muốn... | Sử dụng | Lệnh scaffold |
|---|---|---|
| Quản lý state một trang đầy đủ | `NyPage` | `metro make:page my_page` |
| Quản lý state widget một instance | `NyState` | `metro make:stateful_widget my_widget` |
| Quản lý state widget với nhiều instance độc lập | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Đọc phần [State Actions](#state-actions) trước — phần này giải thích API mà mọi mẫu đều sử dụng. Sau đó chuyển đến mẫu phù hợp với trường hợp của bạn.


<div id="state-actions"></div>

## State Actions

State action là các lệnh được đặt tên mà một widget hoặc trang biết cách xử lý. Bạn định nghĩa một map từ tên action đến các hàm handler, và kích hoạt chúng theo tên từ bất kỳ đâu trong ứng dụng.

Sử dụng state action khi bạn cần:
- Kích hoạt một hành vi cụ thể trên widget hoặc trang (không chỉ là refresh chung)
- Truyền dữ liệu đến widget và để nó phản hồi theo cách đã định nghĩa
- Xây dựng hành vi widget có thể tái sử dụng có thể được gọi từ nhiều điểm

<div id="defining-handlers"></div>

### Định nghĩa Handler

Handler nằm trong getter `stateActions` trên class `NyState` hoặc `NyPage` của bạn. Các khóa map là tên action; các giá trị là hàm chạy khi action đó được kích hoạt.

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

Handler tự chịu trách nhiệm gọi `setState` nếu muốn widget rebuild.

Handler `apply_discount` ở trên nhận một đối số `code` — khai báo một tham số vị trí duy nhất khi handler cần payload được truyền qua
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Dùng dạng không có đối số `()` khi action không mang payload.


<div id="triggering-actions"></div>

### Kích hoạt Actions

Dùng hàm cấp cao nhất `stateAction` để kích hoạt một action từ bất kỳ đâu — widget khác, controller, event handler, API callback, v.v.

``` dart
// Kích hoạt action không có dữ liệu
stateAction("clear_cart", state: Cart.state);

// Kích hoạt action có dữ liệu
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

Đối số `state:` là **state key** của mục tiêu:
- Đối với widget — `MyWidget.state` (một string)
- Đối với trang — `MyPage.path` (route)


<div id="handlers-with-and-without-data"></div>

### Handler có và không có dữ liệu

Handler có thể đồng bộ hoặc bất đồng bộ, và có thể được định nghĩa có hoặc không có đối số `data`:

``` dart
@override
Map<String, Function> get stateActions => {
  // Không có dữ liệu — handler chạy như bình thường
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Có dữ liệu — nhận bất cứ thứ gì được truyền qua đối số `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async được hỗ trợ — framework chờ handler
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Nếu bạn truyền `data:` vào `stateAction` nhưng handler không nhận đối số, dữ liệu sẽ đơn giản bị bỏ qua.


<div id="using-a-state-actions-instance"></div>

### Sử dụng Instance StateActions

Nếu một widget cung cấp instance `StateActions` có kiểu (thường qua phương thức static `stateActions(stateName)`), bạn có thể gọi `.action(...)` trực tiếp trên nó thay vì dùng hàm tự do. Điều này gọn hơn khi gửi nhiều action đến cùng một mục tiêu:

``` dart
// Sử dụng hàm tự do
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Sử dụng instance StateActions — tương đương, ít lặp lại hơn
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Một số widget tích hợp sẵn (`InputField`, `CollectionView`, `LanguageSwitcher`, họ `NyForm*`) đi kèm với các class `StateActions` có kiểu và các phương thức được đặt tên như `.clear()`, `.setValue(...)`, `.refresh()` — kiểm tra tài liệu của widget để biết những gì có sẵn.


<div id="pattern-ny-page"></div>

## Mẫu: Trang quản lý State (NyPage)

Sử dụng mẫu này khi bạn muốn kích hoạt hành vi trên toàn bộ trang từ nơi khác trong ứng dụng — ví dụ: refresh trang khi một sự kiện xảy ra, hoặc xóa state form từ widget con.

**Bước 1:** Scaffold một trang.

``` bash
metro make:page my_page
```

Lệnh này tạo một `NyPage` trong `lib/resources/pages/`:

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

**Bước 2:** Đặt `stateManaged` thành `true`.

Theo mặc định, các trang **không** đăng ký nhận state event — điều này tránh các listener không cần thiết trên các trang không cần chúng. Để bật state action trên một trang, thay đổi getter:

``` dart
@override
bool get stateManaged => true;
```

**Bước 3:** Thêm map `stateActions`.

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

**Bước 4:** Kích hoạt action từ bất kỳ đâu bằng `path` của trang.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Trang dùng `MyPage.path` làm key, widget dùng `MyWidget.state`. Đây là sự khác biệt duy nhất ở phía gọi.


<div id="pattern-ny-state"></div>

## Mẫu: Stateful Widgets (NyState)

Sử dụng mẫu này cho các widget có thể tái sử dụng tồn tại như một instance duy nhất trên trang — thẻ hồ sơ, thanh tiêu đề, chỉ báo trạng thái. Nếu bạn chỉ render một instance của widget tại một thời điểm, đây là mẫu phù hợp.

**Bước 1:** Scaffold một stateful widget.

``` bash
metro make:stateful_widget profile_card
```

Lệnh này tạo ra trong `lib/resources/widgets/`:

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

Scaffold cho bạn một widget `NyState` hoạt động được — nhưng **chưa được quản lý state**. Để nó phản hồi với state action, bạn cần thêm bốn thứ:

1. Khóa `state` trên class widget — string duy nhất mà các phần khác của ứng dụng sẽ nhắm đến
2. Tham số constructor nhận state key và chuyển tiếp nó đến class state
3. Constructor trên class state gán `stateName`
4. Map `stateActions` định nghĩa các handler

**Bước 2:** Chuyển đổi scaffold thành widget được quản lý state.

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
    // ^ gọi điều này từ bất kỳ đâu trong ứng dụng để kích hoạt handler bên dưới
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

Những gì đã thay đổi:
- `static String get state => "profile_card";` định nghĩa state key công khai
- `createState() => _ProfileCardState(state)` truyền key vào class state
- `_ProfileCardState(String? state) { this.stateName = state; }` đăng ký instance state này dưới key đó, để framework biết widget nào sẽ nhận action
- `stateActions` khai báo các handler được đặt tên

**Bước 3:** Kích hoạt từ bất kỳ đâu.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Bạn có thể thêm bao nhiêu handler tùy thích, có hoặc không có dữ liệu:

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

## Mẫu: Widget quản lý State (NyStateManaged)

Sử dụng mẫu này khi bạn cần **nhiều instance độc lập của cùng một widget** trên màn hình cùng một lúc — ví dụ, badge giỏ hàng ở header và một cái khác ở sidebar cần cập nhật độc lập.

`NyStateManaged` thêm tham số `stateName` để mỗi instance có thể được địa chỉ hóa riêng. Nếu bạn chỉ render một instance của widget, hãy ưu tiên `NyState` — đơn giản hơn.

**Bước 1:** Scaffold một widget được quản lý state.

``` bash
metro make:state_managed_widget cart
```

Lệnh này tạo ra trong `lib/resources/widgets/`:

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
    // logic khởi tạo ở đây
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Gọi action từ bất kỳ đâu trong ứng dụng
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

**Bước 2:** Hoàn thiện state — tải dữ liệu trong `init`, định nghĩa handler, và render.

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

**Bước 3:** Kích hoạt action bằng helper static `action()` được tạo sẵn.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Điều này tương đương với `stateAction("reload_cart", state: Cart.state)` — helper static chỉ loại bỏ boilerplate.


<div id="advanced-multiple-isolated-instances"></div>

### Nâng cao: Nhiều instance độc lập

Lý do `NyStateManaged` tồn tại là để hỗ trợ nhiều instance độc lập của cùng một widget. Mỗi instance nhận `stateName` riêng, tạo ra state key có namespace.

Render hai giỏ hàng với tên khác nhau:

``` dart
Column(
  children: [
    Cart(stateName: "header"),
    Cart(stateName: "sidebar"),
  ],
)
```

Giờ bạn có thể cập nhật từng cái độc lập:

``` dart
// Chỉ reload giỏ hàng header
Cart.action("reload_cart", stateName: "header");

// Chỉ reload giỏ hàng sidebar
Cart.action("reload_cart", stateName: "sidebar");

// Không có stateName — nhắm đến instance mặc định không tên
Cart.action("reload_cart");
```

Helper `_stateFor` xử lý namespace: `Cart(stateName: "header")` đăng ký dưới key `"cart_header"`, và `Cart.action(..., stateName: "header")` nhắm đến đúng key đó.


<div id="lifecycle"></div>

## Tham chiếu Vòng đời

Widget và trang được quản lý state chia sẻ hai hook vòng đời quan trọng:

1. **`init()`** — được gọi một lần khi state được tạo lần đầu. Dùng để tải dữ liệu ban đầu.

2. **`stateUpdated(data)`** — được gọi mỗi khi một state action được kích hoạt đối với state này. Đối số `data` là toàn bộ payload (bao gồm tên action và dữ liệu của action). Ghi đè nó nếu bạn cần phản hồi với *mọi* state action — hầu hết thời gian, định nghĩa handler trong `stateActions` là điều bạn muốn.

**Xem thêm:** [NyState](/docs/{{ $version }}/ny-state) để biết toàn bộ bộ trợ giúp state và các phương thức vòng đời.
