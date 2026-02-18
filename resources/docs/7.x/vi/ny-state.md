# NyState

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng NyState](#how-to-use-nystate "Cách sử dụng NyState")
- [Kiểu tải](#loading-style "Kiểu tải")
- [State Actions](#state-actions "State Actions")
- [Các helper](#helpers "Các helper")


<div id="introduction"></div>

## Giới thiệu

`NyState` là phiên bản mở rộng của class `State` tiêu chuẩn trong Flutter. Nó cung cấp chức năng bổ sung để giúp quản lý trạng thái của các trang và widget hiệu quả hơn.

Bạn có thể **tương tác** với trạng thái giống hệt như bạn làm với state Flutter thông thường, nhưng với những lợi ích bổ sung của NyState.

Hãy cùng tìm hiểu cách sử dụng NyState.

<div id="how-to-use-nystate"></div>

## Cách sử dụng NyState

Bạn có thể bắt đầu sử dụng class này bằng cách kế thừa nó.

Ví dụ

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {

  };

  @override
  view(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded")
    );
  }
```

Phương thức `init` được sử dụng để khởi tạo trạng thái của trang. Bạn có thể sử dụng phương thức này dạng async hoặc không async và bên dưới, nó sẽ xử lý lệnh gọi async và hiển thị trình tải.

Phương thức `view` được sử dụng để hiển thị giao diện cho trang.

#### Tạo stateful widget mới với NyState

Để tạo một trang mới trong {{ config('app.name') }}, bạn có thể chạy lệnh dưới đây.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Kiểu tải

Bạn có thể sử dụng thuộc tính `loadingStyle` để thiết lập kiểu tải cho trang của bạn.

Ví dụ

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // mô phỏng lệnh gọi mạng trong 3 giây
  };
```

`loadingStyle` **mặc định** sẽ là Widget tải của bạn (resources/widgets/loader_widget.dart).
Bạn có thể tùy chỉnh `loadingStyle` để cập nhật kiểu tải.

Dưới đây là bảng các kiểu tải bạn có thể sử dụng:
// normal, skeletonizer, none

| Kiểu | Mô tả |
| --- | --- |
| normal | Kiểu tải mặc định |
| skeletonizer | Kiểu tải skeleton |
| none | Không có kiểu tải |

Bạn có thể thay đổi kiểu tải như sau:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// hoặc
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Nếu bạn muốn cập nhật Widget tải trong một trong các kiểu, bạn có thể truyền `child` vào `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// tương tự cho skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Bây giờ, khi tab đang tải, văn bản "Loading..." sẽ được hiển thị.

Ví dụ dưới đây:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // mô phỏng lệnh gọi mạng trong 3 giây
    };

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            body: Text("The page loaded")
        );
    }
    ...
}
```

<div id="state-actions"></div>

## State Actions

Trong Nylo, bạn có thể định nghĩa các **actions** nhỏ trong Widget có thể được gọi từ các class khác. Điều này hữu ích khi bạn muốn cập nhật trạng thái của một widget từ class khác.

Đầu tiên, bạn phải **định nghĩa** các actions trong widget. Điều này hoạt động cho cả `NyState` và `NyPage`.

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // xử lý cách bạn muốn khởi tạo trạng thái
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // Ví dụ với dữ liệu
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

Sau đó, bạn có thể gọi action từ class khác sử dụng phương thức `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Ví dụ khác với dữ liệu
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Ví dụ khác với dữ liệu
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Nếu bạn sử dụng stateActions với `NyPage`, bạn phải sử dụng **path** của trang.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Ví dụ khác với dữ liệu
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Ví dụ khác với dữ liệu
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Ngoài ra còn có class `StateAction`, class này có một vài phương thức bạn có thể sử dụng để cập nhật trạng thái widget.

- `refreshPage` - Làm mới trang.
- `pop` - Đóng trang.
- `showToastSorry` - Hiển thị thông báo toast xin lỗi.
- `showToastWarning` - Hiển thị thông báo toast cảnh báo.
- `showToastInfo` - Hiển thị thông báo toast thông tin.
- `showToastDanger` - Hiển thị thông báo toast nguy hiểm.
- `showToastOops` - Hiển thị thông báo toast oops.
- `showToastSuccess` - Hiển thị thông báo toast thành công.
- `showToastCustom` - Hiển thị thông báo toast tùy chỉnh.
- `validate` - Xác thực dữ liệu từ widget.
- `changeLanguage` - Cập nhật ngôn ngữ trong ứng dụng.
- `confirmAction` - Thực hiện hành động xác nhận.

Ví dụ

``` dart
class _UpgradeButtonState extends NyState<UpgradeButton> {

  view(BuildContext context) {
    return Button.primary(
      onPressed: () {
        StateAction.showToastSuccess(UpgradePage.state,
          description: "You have successfully upgraded your account",
        );
      },
      text: "Upgrade",
    );
  }
}
```

Bạn có thể sử dụng class `StateAction` để cập nhật trạng thái của bất kỳ trang/widget nào trong ứng dụng miễn là widget đó được quản lý trạng thái.

<div id="helpers"></div>

## Các helper

|  |  |
| --- | ----------- |
| [lockRelease](#lock-release "lockRelease") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") | [confirmAction](#confirm-action "confirmAction") |
| [showToastSuccess](#show-toast-success "showToastSuccess") | [showToastOops](#show-toast-oops "showToastOops") |
| [showToastDanger](#show-toast-danger "showToastDanger") | [showToastInfo](#show-toast-info "showToastInfo") |
| [showToastWarning](#show-toast-warning "showToastWarning") | [showToastSorry](#show-toast-sorry "showToastSorry") |


<div id="reboot"></div>

### Reboot

Phương thức này sẽ chạy lại phương thức `init` trong state. Hữu ích khi bạn muốn làm mới dữ liệu trên trang.

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  get init => () async {
    users = await api<ApiService>((request) => request.fetchUsers());
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Users"),
          actions: [
            IconButton(
              icon: Icon(Icons.refresh),
              onPressed: () {
                reboot(); // làm mới dữ liệu
              },
            )
          ],
        ),
        body: ListView.builder(
          itemCount: users.length,
          itemBuilder: (context, index) {
            return Text(users[index].firstName);
          }
        ),
    );
  }
}
```

<div id="pop"></div>

### Pop

`pop` - Xóa trang hiện tại khỏi ngăn xếp.

Ví dụ

``` dart
class _HomePageState extends NyState<HomePage> {

  popView() {
    pop();
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: popView,
        child: Text("Pop current view")
      )
    );
  }
```


<div id="showToast"></div>

### showToast

Hiển thị thông báo toast trên context.

Ví dụ

```dart
class _HomePageState extends NyState<HomePage> {

  displayToast() {
    showToast(
        title: "Hello",
        description: "World",
        icon: Icons.account_circle,
        duration: Duration(seconds: 2),
        style: ToastNotificationStyleType.INFO // SUCCESS, INFO, DANGER, WARNING
    );
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: displayToast,
        child: Text("Display a toast")
      )
    );
  }
```


<div id="validate"></div>

### validate

Helper `validate` thực hiện kiểm tra xác thực trên dữ liệu.

Bạn có thể tìm hiểu thêm về trình xác thực <a href="/docs/{{$version}}/validation" target="_BLANK">tại đây</a>.

Ví dụ

``` dart
class _HomePageState extends NyState<HomePage> {
TextEditingController _textFieldControllerEmail = TextEditingController();

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    validate(rules: {
        "email address": [textEmail, "email"]
      }, onSuccess: () {
      print('passed validation')
    });
  }
```


<div id="change-language"></div>

### changeLanguage

Bạn có thể gọi `changeLanguage` để thay đổi tệp json **/lang** được sử dụng trên thiết bị.

Tìm hiểu thêm về bản địa hóa <a href="/docs/{{$version}}/localization" target="_BLANK">tại đây</a>.

Ví dụ

``` dart
class _HomePageState extends NyState<HomePage> {

  changeLanguageES() {
    await changeLanguage('es');
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: changeLanguageES,
        child: Text("Change Language".tr())
      )
    );
  }
```


<div id="when-env"></div>

### whenEnv

Bạn có thể sử dụng `whenEnv` để chạy một hàm khi ứng dụng đang ở một trạng thái nhất định.
Ví dụ: biến **APP_ENV** trong tệp `.env` được đặt là 'developing', `APP_ENV=developing`.

Ví dụ

```dart
class _HomePageState extends NyState<HomePage> {

  TextEditingController _textEditingController = TextEditingController();

  @override
  get init => () {
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  };
```

<div id="lock-release"></div>

### lockRelease

Phương thức này sẽ khóa trạng thái sau khi một hàm được gọi, chỉ khi phương thức hoàn thành mới cho phép người dùng thực hiện các yêu cầu tiếp theo. Phương thức này cũng cập nhật trạng thái, sử dụng `isLocked` để kiểm tra.

Ví dụ tốt nhất để minh họa `lockRelease` là tưởng tượng rằng chúng ta có màn hình đăng nhập khi người dùng nhấn 'Login'. Chúng ta muốn thực hiện lệnh gọi async để đăng nhập người dùng nhưng không muốn phương thức được gọi nhiều lần vì có thể tạo trải nghiệm không mong muốn.

Dưới đây là ví dụ.

```dart
class _LoginPageState extends NyState<LoginPage> {

  _login() async {
    await lockRelease('login_to_app', perform: () async {

      await Future.delayed(Duration(seconds: 4), () {
        print('Pretend to login...');
      });

    });
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
            Center(
              child: InkWell(
                onTap: _login,
                child: Text("Login"),
              ),
            )
          ],
        )
    );
  }
```

Sau khi bạn nhấn phương thức **_login**, nó sẽ chặn mọi yêu cầu tiếp theo cho đến khi yêu cầu ban đầu hoàn thành. Helper `isLocked('login_to_app')` được sử dụng để kiểm tra xem nút có bị khóa hay không. Trong ví dụ trên, bạn có thể thấy chúng ta sử dụng nó để xác định khi nào hiển thị Widget tải.

<div id="is-locked"></div>

### isLocked

Phương thức này kiểm tra xem trạng thái có bị khóa hay không sử dụng helper [`lockRelease`](#lock-release).

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
          ],
        )
    );
  }
```

<div id="view"></div>

### view

Phương thức `view` được sử dụng để hiển thị giao diện cho trang.

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
      return Scaffold(
          body: Center(
              child: Text("My Page")
          )
      );
  }
}
```

<div id="confirm-action"></div>

### confirmAction

Phương thức `confirmAction` sẽ hiển thị dialog cho người dùng để xác nhận một hành động.
Phương thức này hữu ích khi bạn muốn người dùng xác nhận hành động trước khi tiếp tục.

Ví dụ

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

Phương thức `showToastSuccess` hiển thị thông báo toast thành công cho người dùng.

Ví dụ
``` dart
_login() {
    ...
    showToastSuccess(
        description: "You have successfully logged in"
    );
}
```

<div id="show-toast-oops"></div>

### showToastOops

Phương thức `showToastOops` hiển thị thông báo toast oops cho người dùng.

Ví dụ
``` dart
_error() {
    ...
    showToastOops(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-danger"></div>

### showToastDanger

Phương thức `showToastDanger` hiển thị thông báo toast nguy hiểm cho người dùng.

Ví dụ
``` dart
_error() {
    ...
    showToastDanger(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-info"></div>

### showToastInfo

Phương thức `showToastInfo` hiển thị thông báo toast thông tin cho người dùng.

Ví dụ
``` dart
_info() {
    ...
    showToastInfo(
        description: "Your account has been updated"
    );
}
```

<div id="show-toast-warning"></div>

### showToastWarning

Phương thức `showToastWarning` hiển thị thông báo toast cảnh báo cho người dùng.

Ví dụ
``` dart
_warning() {
    ...
    showToastWarning(
        description: "Your account is about to expire"
    );
}
```

<div id="show-toast-sorry"></div>

### showToastSorry

Phương thức `showToastSorry` hiển thị thông báo toast xin lỗi cho người dùng.

Ví dụ
``` dart
_sorry() {
    ...
    showToastSorry(
        description: "Your account has been suspended"
    );
}
```

<div id="is-loading"></div>

### isLoading

Phương thức `isLoading` kiểm tra xem trạng thái có đang tải hay không.

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    if (isLoading()) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: colors().primaryContent
        )
      )
    );
  }
```

<div id="after-load"></div>

### afterLoad

Phương thức `afterLoad` có thể được sử dụng để hiển thị trình tải cho đến khi trạng thái hoàn thành 'tải'.

Bạn cũng có thể kiểm tra các khóa tải khác sử dụng tham số **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () {
    awaitData(perform: () async {
        await sleep(4);
        print('4 seconds after...');
    });
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterLoad(child: () {
          return Text("Loaded");
        })
    );
  }
```

<div id="after-not-locked"></div>

### afterNotLocked

Phương thức `afterNotLocked` kiểm tra xem trạng thái có bị khóa hay không.

Nếu trạng thái bị khóa, nó sẽ hiển thị widget [loading].

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          alignment: Alignment.center,
          child: afterNotLocked('login', child: () {
            return MaterialButton(
              onPressed: () {
                login();
              },
              child: Text("Login"),
            );
          }),
        )
    );
  }

  login() async {
    await lockRelease('login', perform: () async {
      await sleep(4);
      print('4 seconds after...');
    });
  }
}
```

<div id="after-not-null"></div>

### afterNotNull

Bạn có thể sử dụng `afterNotNull` để hiển thị widget tải cho đến khi một biến được gán giá trị.

Hãy tưởng tượng bạn cần lấy tài khoản người dùng từ DB bằng lệnh gọi Future có thể mất 1-2 giây, bạn có thể sử dụng afterNotNull trên giá trị đó cho đến khi bạn có dữ liệu.

Ví dụ

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // ví dụ
    setState(() {});
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterNotNull(_user, child: () {
          return Text(_user!.firstName);
        })
    );
  }
```

<div id="set-loading"></div>

### setLoading

Bạn có thể chuyển sang trạng thái 'đang tải' bằng `setLoading`.

Tham số đầu tiên nhận `bool` cho biết có đang tải hay không, tham số tiếp theo cho phép bạn đặt tên cho trạng thái tải, ví dụ `setLoading(true, name: 'refreshing_content');`.

Ví dụ
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    setLoading(true, name: 'refreshing_content');

    await sleep(4);

    setLoading(false, name: 'refreshing_content');
  };

  @override
  Widget build(BuildContext context) {
    if (isLoading(name: 'refreshing_content')) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded")
    );
  }
```
