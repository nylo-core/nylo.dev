# State Management

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Khi nào sử dụng quản lý State](#when-to-use-state-management "Khi nào sử dụng quản lý State")
- [Vòng đời](#lifecycle "Vòng đời")
- [State Actions](#state-actions "State Actions")
  - [NyState - State Actions](#state-actions-nystate "NyState - State Actions")
  - [NyPage - State Actions](#state-actions-nypage "NyPage - State Actions")
- [Cập nhật State](#updating-a-state "Cập nhật State")
- [Xây dựng Widget đầu tiên](#building-your-first-widget "Xây dựng Widget đầu tiên")

<div id="introduction"></div>

## Giới thiệu

Quản lý state cho phép bạn cập nhật các phần cụ thể của UI mà không cần rebuild toàn bộ trang. Trong {{ config('app.name') }} v7, bạn có thể xây dựng các widget giao tiếp và cập nhật lẫn nhau trong ứng dụng.

{{ config('app.name') }} cung cấp hai class cho quản lý state:
- **`NyState`** -- Để xây dựng widget tái sử dụng (như huy hiệu giỏ hàng, bộ đếm thông báo, hoặc chỉ báo trạng thái)
- **`NyPage`** -- Để xây dựng các trang trong ứng dụng (mở rộng `NyState` với các tính năng dành riêng cho trang)

Sử dụng quản lý state khi bạn cần:
- Cập nhật widget từ phần khác của ứng dụng
- Giữ đồng bộ widget với dữ liệu chung
- Tránh rebuild toàn bộ trang khi chỉ một phần UI thay đổi


### Hãy hiểu quản lý State trước

Mọi thứ trong Flutter đều là widget, chúng chỉ là những phần nhỏ của UI mà bạn có thể kết hợp để tạo thành ứng dụng hoàn chỉnh.

Khi bạn bắt đầu xây dựng các trang phức tạp, bạn sẽ cần quản lý state của widget. Điều này có nghĩa là khi có thay đổi, ví dụ dữ liệu, bạn có thể cập nhật widget đó mà không cần rebuild toàn bộ trang.

Có rất nhiều lý do tại sao điều này quan trọng, nhưng lý do chính là hiệu suất. Nếu bạn có widget liên tục thay đổi, bạn không muốn rebuild toàn bộ trang mỗi khi nó thay đổi.

Đây là lúc quản lý State phát huy tác dụng, nó cho phép bạn quản lý state của widget trong ứng dụng.


<div id="when-to-use-state-management"></div>

### Khi nào sử dụng quản lý State

Bạn nên sử dụng quản lý State khi có widget cần được cập nhật mà không cần rebuild toàn bộ trang.

Ví dụ, hãy tưởng tượng bạn đã tạo ứng dụng thương mại điện tử. Bạn đã xây dựng widget hiển thị tổng số mặt hàng trong giỏ hàng của người dùng.
Hãy gọi widget này là `Cart()`.

Widget `Cart` được quản lý state trong Nylo sẽ trông như thế này:

**Bước 1:** Định nghĩa widget với tên state tĩnh

``` dart
/// Widget Cart
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Định danh duy nhất cho state của widget này

  @override
  _CartState createState() => _CartState();
}
```

**Bước 2:** Tạo class state kế thừa `NyState`

``` dart
/// Class state cho widget Cart
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Đăng ký tên state
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Tải dữ liệu ban đầu
  };

  @override
  void stateUpdated(data) {
    reboot(); // Tải lại widget khi state cập nhật
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

**Bước 3:** Tạo hàm trợ giúp để đọc và cập nhật giỏ hàng

``` dart
/// Lấy giá trị giỏ hàng từ storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Đặt giá trị giỏ hàng và thông báo widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // Kích hoạt stateUpdated() trên widget
}
```

Hãy phân tích điều này.

1. Widget `Cart` là một `StatefulWidget`.

2. `_CartState` kế thừa `NyState<Cart>`.

3. Bạn cần định nghĩa tên cho `state`, được sử dụng để xác định state.

4. Phương thức `boot()` được gọi khi widget được tải lần đầu.

5. Các phương thức `stateUpdate()` xử lý những gì xảy ra khi state được cập nhật.

Nếu bạn muốn thử ví dụ này trong dự án {{ config('app.name') }}, hãy tạo widget mới gọi là `Cart`.

``` bash
metro make:state_managed_widget cart
```

Sau đó bạn có thể sao chép ví dụ trên và thử trong dự án của mình.

Bây giờ, để cập nhật giỏ hàng, bạn có thể gọi như sau.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Vòng đời

Vòng đời của widget `NyState` như sau:

1. `init()` - Phương thức này được gọi khi state được khởi tạo.

2. `stateUpdated(data)` - Phương thức này được gọi khi state được cập nhật.

    Nếu bạn gọi `updateState(MyStateName.state, data: "The Data")`, nó sẽ kích hoạt **stateUpdated(data)** được gọi.

Khi state được khởi tạo lần đầu, bạn sẽ cần triển khai cách bạn muốn quản lý state.


<div id="state-actions"></div>

## State Actions

State actions cho phép bạn kích hoạt các phương thức cụ thể trên widget từ bất kỳ đâu trong ứng dụng. Hãy nghĩ về chúng như các lệnh được đặt tên mà bạn có thể gửi đến widget.

Sử dụng state actions khi bạn cần:
- Kích hoạt hành vi cụ thể trên widget (không chỉ làm mới nó)
- Truyền dữ liệu đến widget và khiến nó phản hồi theo cách cụ thể
- Tạo hành vi widget có thể tái sử dụng có thể được gọi từ nhiều nơi

``` dart
// Gửi action đến widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Ví dụ khác với dữ liệu
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

Trong widget, bạn có thể định nghĩa các actions muốn xử lý.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Ví dụ với dữ liệu
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Sau đó, bạn có thể gọi phương thức `stateAction` từ bất kỳ đâu trong ứng dụng.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// in ra 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Bạn cũng có thể định nghĩa state actions bằng phương thức `whenStateAction` trong getter `init`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Đặt lại số đếm huy hiệu
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - State Actions

Đầu tiên, tạo widget stateful.

``` bash
metro make:stateful_widget [widget_name]
```
Ví dụ: metro make:stateful_widget user_avatar

Lệnh này sẽ tạo widget mới trong thư mục `lib/resources/widgets/`.

Nếu bạn mở tệp đó, bạn có thể định nghĩa state actions.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Ví dụ
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Ví dụ
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Cuối cùng, bạn có thể gửi action từ bất kỳ đâu trong ứng dụng.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// in ra 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Đặt lại dữ liệu trong widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// hiển thị toast thành công với thông báo
```


<div id="state-actions-nypage"></div>

### NyPage - State Actions

Các trang cũng có thể nhận state actions. Điều này hữu ích khi bạn muốn kích hoạt hành vi cấp trang từ widget hoặc trang khác.

Đầu tiên, tạo trang được quản lý state.

``` bash
metro make:page my_page
```

Lệnh này sẽ tạo trang mới được quản lý state gọi là `MyPage` trong thư mục `lib/resources/pages/`.

Nếu bạn mở tệp đó, bạn có thể định nghĩa state actions.

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
    // Ví dụ
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Cuối cùng, bạn có thể gửi action từ bất kỳ đâu trong ứng dụng.

``` dart
stateAction('test_page_action', state: MyPage.state);
// in ra 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Đặt lại dữ liệu trong trang

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// hiển thị toast thành công với thông báo
```

Bạn cũng có thể định nghĩa state actions bằng phương thức `whenStateAction`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Đặt lại số đếm huy hiệu
      _count = 0;
    }
  });
}
```

Sau đó bạn có thể gửi action từ bất kỳ đâu trong ứng dụng.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Cập nhật State

Bạn có thể cập nhật state bằng cách gọi phương thức `updateState()`.

``` dart
updateState(MyStateName.state);

// hoặc với dữ liệu
updateState(MyStateName.state, data: "The Data");
```

Có thể gọi từ bất kỳ đâu trong ứng dụng.

**Xem thêm:** [NyState](/docs/{{ $version }}/ny-state) để biết thêm chi tiết về các phương thức trợ giúp quản lý state và vòng đời.


<div id="building-your-first-widget"></div>

## Xây dựng Widget đầu tiên

Trong dự án Nylo của bạn, chạy lệnh sau để tạo widget mới.

``` bash
metro make:stateful_widget todo_list
```

Lệnh này sẽ tạo widget `NyState` mới gọi là `TodoList`.

> Lưu ý: Widget mới sẽ được tạo trong thư mục `lib/resources/widgets/`.
