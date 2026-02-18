# Alerts

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Các kiểu dựng sẵn](#built-in-styles "Các kiểu dựng sẵn")
- [Hiển thị thông báo từ trang](#from-pages "Hiển thị thông báo từ trang")
- [Hiển thị thông báo từ Controller](#from-controllers "Hiển thị thông báo từ Controller")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Vị trí hiển thị](#positioning "Vị trí hiển thị")
- [Tùy chỉnh kiểu Toast](#custom-styles "Tùy chỉnh kiểu Toast")
  - [Đăng ký kiểu](#registering-styles "Đăng ký kiểu")
  - [Tạo Style Factory](#creating-a-style-factory "Tạo Style Factory")
- [AlertTab](#alert-tab "AlertTab")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} cung cấp hệ thống thông báo toast để hiển thị cảnh báo cho người dùng. Nó đi kèm với bốn kiểu dựng sẵn -- **success**, **warning**, **info** và **danger** -- đồng thời hỗ trợ các kiểu tùy chỉnh thông qua mô hình registry.

Thông báo có thể được kích hoạt từ các trang, controller, hoặc bất cứ nơi nào bạn có `BuildContext`.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Hiển thị thông báo toast bằng các phương thức tiện ích trong bất kỳ trang `NyState` nào:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

Hoặc sử dụng hàm toàn cục với ID kiểu:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Các kiểu dựng sẵn

{{ config('app.name') }} đăng ký bốn kiểu toast mặc định:

| ID Kiểu | Biểu tượng | Màu | Tiêu đề mặc định |
|----------|------|-------|---------------|
| `success` | Dấu kiểm | Xanh lá | Success |
| `warning` | Dấu chấm than | Cam | Warning |
| `info` | Biểu tượng thông tin | Xanh ngọc | Info |
| `danger` | Biểu tượng cảnh báo | Đỏ | Error |

Các kiểu này được cấu hình trong `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Hiển thị thông báo từ trang

Trong bất kỳ trang nào kế thừa `NyState` hoặc `NyBaseState`, sử dụng các phương thức tiện ích sau:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Phương thức Toast tổng quát

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Hiển thị thông báo từ Controller

Các controller kế thừa `NyController` cũng có các phương thức tiện ích tương tự:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Các phương thức khả dụng: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

Hàm toàn cục `showToastNotification()` hiển thị toast từ bất cứ nơi nào bạn có `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### Tham số

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | bắt buộc | Build context |
| `id` | `String` | `'success'` | ID kiểu toast |
| `title` | `String?` | null | Ghi đè tiêu đề mặc định |
| `description` | `String?` | null | Nội dung mô tả |
| `duration` | `Duration?` | null | Thời gian hiển thị toast |
| `position` | `ToastNotificationPosition?` | null | Vị trí trên màn hình |
| `action` | `VoidCallback?` | null | Callback khi nhấn |
| `onDismiss` | `VoidCallback?` | null | Callback khi đóng |
| `onShow` | `VoidCallback?` | null | Callback khi hiển thị |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` đóng gói tất cả dữ liệu cho một thông báo toast:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Thuộc tính

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Widget biểu tượng |
| `title` | `String` | `''` | Nội dung tiêu đề |
| `style` | `String` | `''` | Định danh kiểu |
| `description` | `String` | `''` | Nội dung mô tả |
| `color` | `Color?` | null | Màu nền cho phần biểu tượng |
| `action` | `VoidCallback?` | null | Callback khi nhấn |
| `dismiss` | `VoidCallback?` | null | Callback nút đóng |
| `onDismiss` | `VoidCallback?` | null | Callback tự động/thủ công đóng |
| `onShow` | `VoidCallback?` | null | Callback khi hiển thị |
| `duration` | `Duration` | 5 giây | Thời gian hiển thị |
| `position` | `ToastNotificationPosition` | `top` | Vị trí trên màn hình |
| `metaData` | `Map<String, dynamic>?` | null | Metadata tùy chỉnh |

### copyWith

Tạo một bản sao đã chỉnh sửa của `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Vị trí hiển thị

Kiểm soát vị trí hiển thị toast trên màn hình:

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Tùy chỉnh kiểu Toast

<div id="registering-styles"></div>

### Đăng ký kiểu

Đăng ký các kiểu tùy chỉnh trong `AppProvider` của bạn:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

Hoặc thêm kiểu bất cứ lúc nào:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Sau đó sử dụng:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Tạo Style Factory

`ToastNotification.style()` tạo một `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `icon` | `Widget` | Widget biểu tượng cho toast |
| `color` | `Color` | Màu nền cho phần biểu tượng |
| `defaultTitle` | `String` | Tiêu đề hiển thị khi không cung cấp |
| `position` | `ToastNotificationPosition?` | Vị trí mặc định |
| `duration` | `Duration?` | Thời gian mặc định |
| `builder` | `Widget Function(ToastMeta)?` | Builder widget tùy chỉnh để kiểm soát hoàn toàn |

### Builder tùy chỉnh hoàn toàn

Để kiểm soát hoàn toàn widget toast:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` là một widget huy hiệu để thêm chỉ báo thông báo vào các tab điều hướng. Nó hiển thị một huy hiệu có thể bật/tắt và tùy chọn lưu trạng thái vào bộ nhớ.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Tham số

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `state` | `String` | bắt buộc | Tên trạng thái để theo dõi |
| `alertEnabled` | `bool?` | null | Có hiển thị huy hiệu hay không |
| `rememberAlert` | `bool?` | `true` | Lưu trạng thái huy hiệu vào bộ nhớ |
| `icon` | `Widget?` | null | Biểu tượng tab |
| `backgroundColor` | `Color?` | null | Màu nền tab |
| `textColor` | `Color?` | null | Màu chữ huy hiệu |
| `alertColor` | `Color?` | null | Màu nền huy hiệu |
| `smallSize` | `double?` | null | Kích thước huy hiệu nhỏ |
| `largeSize` | `double?` | null | Kích thước huy hiệu lớn |
| `textStyle` | `TextStyle?` | null | Kiểu chữ huy hiệu |
| `padding` | `EdgeInsetsGeometry?` | null | Padding huy hiệu |
| `alignment` | `Alignment?` | null | Căn chỉnh huy hiệu |
| `offset` | `Offset?` | null | Offset huy hiệu |
| `isLabelVisible` | `bool?` | `true` | Hiển thị nhãn huy hiệu |

### Constructor từ Factory

Tạo từ một `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Ví dụ

### Thông báo thành công sau khi lưu

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Toast tương tác với hành động

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Cảnh báo ở dưới cùng màn hình

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
