# Button

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Các loại nút có sẵn](#button-types "Các loại nút có sẵn")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Chỉ văn bản")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Bo tròn")
    - [Transparency](#transparency "Trong suốt")
- [Trạng thái tải bất đồng bộ](#async-loading "Trạng thái tải bất đồng bộ")
- [Kiểu hoạt ảnh](#animation-styles "Kiểu hoạt ảnh")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Kiểu Splash](#splash-styles "Kiểu Splash")
- [Kiểu tải](#loading-styles "Kiểu tải")
- [Gửi biểu mẫu](#form-submission "Gửi biểu mẫu")
- [Tùy chỉnh nút](#customizing-buttons "Tùy chỉnh nút")
- [Tham chiếu tham số](#parameters "Tham chiếu tham số")


<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} cung cấp class `Button` với tám kiểu nút được xây dựng sẵn. Mỗi nút đi kèm hỗ trợ tích hợp cho:

- **Trạng thái tải bất đồng bộ** — trả về `Future` từ `onPressed` và nút tự động hiển thị chỉ báo tải
- **Kiểu hoạt ảnh** — chọn từ các hiệu ứng clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph và shake
- **Kiểu splash** — thêm phản hồi chạm ripple, highlight, glow hoặc ink
- **Gửi biểu mẫu** — kết nối nút trực tiếp với instance `NyFormData`

Bạn có thể tìm thấy định nghĩa nút của ứng dụng trong `lib/resources/widgets/buttons/buttons.dart`. File này chứa class `Button` với các phương thức static cho mỗi loại nút, giúp dễ dàng tùy chỉnh các giá trị mặc định cho dự án của bạn.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Sử dụng class `Button` ở bất cứ đâu trong widget của bạn. Đây là ví dụ đơn giản bên trong một trang:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Mọi loại nút đều tuân theo cùng một mẫu — truyền nhãn `text` và callback `onPressed`.

<div id="button-types"></div>

## Các loại nút có sẵn

Tất cả các nút được truy cập thông qua class `Button` sử dụng các phương thức static.

<div id="primary"></div>

### Primary

Nút có nền đầy với bóng đổ, sử dụng màu chính của theme. Phù hợp nhất cho các phần tử kêu gọi hành động chính.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Nút có nền đầy với màu bề mặt nhẹ hơn và bóng đổ tinh tế. Phù hợp cho các hành động phụ bên cạnh nút primary.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Nút trong suốt với viền. Hữu ích cho các hành động ít nổi bật hoặc nút hủy.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Bạn có thể tùy chỉnh màu viền và màu chữ:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Nút tối giản không có nền hoặc viền. Lý tưởng cho các hành động nội tuyến hoặc liên kết.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Bạn có thể tùy chỉnh màu chữ:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Nút có nền đầy hiển thị icon bên cạnh văn bản. Icon xuất hiện trước văn bản theo mặc định.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Bạn có thể tùy chỉnh màu nền:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Nút với nền gradient tuyến tính. Sử dụng màu chính và màu thứ ba của theme theo mặc định.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Bạn có thể cung cấp các màu gradient tùy chỉnh:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Nút hình viên thuốc với các góc bo tròn hoàn toàn. Bán kính viền mặc định bằng một nửa chiều cao nút.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Bạn có thể tùy chỉnh màu nền và bán kính viền:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Nút kiểu kính mờ với hiệu ứng backdrop blur. Hoạt động tốt khi đặt trên hình ảnh hoặc nền có màu.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Bạn có thể tùy chỉnh màu chữ:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Trạng thái tải bất đồng bộ

Một trong những tính năng mạnh mẽ nhất của nút {{ config('app.name') }} là **quản lý trạng thái tải tự động**. Khi callback `onPressed` của bạn trả về `Future`, nút sẽ tự động hiển thị chỉ báo tải và vô hiệu hóa tương tác cho đến khi thao tác hoàn thành.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Trong khi thao tác bất đồng bộ đang chạy, nút sẽ hiển thị hiệu ứng skeleton loading (theo mặc định). Khi `Future` hoàn thành, nút trở về trạng thái bình thường.

Điều này hoạt động với bất kỳ thao tác bất đồng bộ nào — gọi API, ghi cơ sở dữ liệu, tải file lên hoặc bất cứ thứ gì trả về `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

Không cần quản lý biến trạng thái `isLoading`, gọi `setState` hoặc bọc bất cứ thứ gì trong `StatefulWidget` — {{ config('app.name') }} xử lý tất cả cho bạn.

### Cách hoạt động

Khi nút phát hiện `onPressed` trả về `Future`, nó sử dụng cơ chế `lockRelease` để:

1. Hiển thị chỉ báo tải (được điều khiển bởi `LoadingStyle`)
2. Vô hiệu hóa nút để ngăn nhấn trùng lặp
3. Đợi `Future` hoàn thành
4. Khôi phục nút về trạng thái bình thường

<div id="animation-styles"></div>

## Kiểu hoạt ảnh

Nút hỗ trợ hoạt ảnh nhấn thông qua `ButtonAnimationStyle`. Các hoạt ảnh này cung cấp phản hồi trực quan khi người dùng tương tác với nút. Bạn có thể đặt kiểu hoạt ảnh khi tùy chỉnh nút trong `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Hiệu ứng nhấn 3D kiểu Duolingo. Nút dịch chuyển xuống khi nhấn và bật lại khi thả. Phù hợp nhất cho các hành động chính và UX kiểu trò chơi.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Thu nhỏ nút khi nhấn và bật lại khi thả. Phù hợp nhất cho nút thêm vào giỏ hàng, thích và yêu thích.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Hiệu ứng co giãn nhẹ liên tục khi giữ nút. Phù hợp nhất cho các hành động nhấn giữ hoặc thu hút sự chú ý.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Nén nút theo chiều ngang và mở rộng theo chiều dọc khi nhấn. Phù hợp nhất cho UI vui nhộn và tương tác.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Hiệu ứng biến dạng đàn hồi lắc lư. Phù hợp nhất cho các ứng dụng vui vẻ, bình thường hoặc giải trí.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Điểm sáng bóng quét qua nút khi nhấn. Phù hợp nhất cho tính năng cao cấp hoặc CTA bạn muốn thu hút sự chú ý.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Hiệu ứng ripple nâng cao mở rộng từ điểm chạm. Phù hợp nhất cho nhấn mạnh Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

Bán kính viền của nút tăng lên khi nhấn, tạo hiệu ứng thay đổi hình dạng. Phù hợp nhất cho phản hồi tinh tế, thanh lịch.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Hoạt ảnh rung ngang. Phù hợp nhất cho trạng thái lỗi hoặc hành động không hợp lệ — rung nút để báo hiệu có gì đó sai.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Tinh chỉnh hiệu ứng:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Tắt hoạt ảnh

Để sử dụng nút không có hoạt ảnh:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Thay đổi hoạt ảnh mặc định

Để thay đổi hoạt ảnh mặc định cho một loại nút, chỉnh sửa file `lib/resources/widgets/buttons/buttons.dart`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Kiểu Splash

Hiệu ứng splash cung cấp phản hồi chạm trực quan trên nút. Cấu hình chúng thông qua `ButtonSplashStyle`. Kiểu splash có thể kết hợp với kiểu hoạt ảnh để tạo phản hồi nhiều lớp.

### Các kiểu Splash có sẵn

| Splash | Factory | Mô tả |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Ripple Material tiêu chuẩn từ điểm chạm |
| Highlight | `ButtonSplashStyle.highlight()` | Highlight tinh tế không có hoạt ảnh ripple |
| Glow | `ButtonSplashStyle.glow()` | Ánh sáng mềm tỏa ra từ điểm chạm |
| Ink | `ButtonSplashStyle.ink()` | Splash mực nhanh, phản hồi nhanh hơn |
| None | `ButtonSplashStyle.none()` | Không có hiệu ứng splash |
| Custom | `ButtonSplashStyle.custom()` | Toàn quyền kiểm soát splash factory |

### Ví dụ

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Bạn có thể tùy chỉnh màu splash và độ mờ:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Kiểu tải

Chỉ báo tải hiển thị trong các thao tác bất đồng bộ được điều khiển bởi `LoadingStyle`. Bạn có thể đặt nó cho mỗi loại nút trong file buttons.

### Skeletonizer (Mặc định)

Hiển thị hiệu ứng skeleton shimmer trên nút:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Hiển thị widget tải (mặc định là app loader):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Giữ nút hiển thị nhưng vô hiệu hóa tương tác trong khi tải:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Gửi biểu mẫu

Tất cả các nút hỗ trợ tham số `submitForm`, kết nối nút với `NyForm`. Khi nhấn, nút sẽ xác thực biểu mẫu và gọi handler thành công với dữ liệu biểu mẫu.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

Tham số `submitForm` chấp nhận một record với hai giá trị:
1. Instance `NyFormData` (hoặc tên biểu mẫu dạng `String`)
2. Callback nhận dữ liệu đã xác thực

Theo mặc định, `showToastError` là `true`, hiển thị thông báo toast khi xác thực biểu mẫu thất bại. Đặt thành `false` để xử lý lỗi im lặng:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Khi callback `submitForm` trả về `Future`, nút sẽ tự động hiển thị trạng thái tải cho đến khi thao tác bất đồng bộ hoàn thành.

<div id="customizing-buttons"></div>

## Tùy chỉnh nút

Tất cả giá trị mặc định của nút được định nghĩa trong dự án tại `lib/resources/widgets/buttons/buttons.dart`. Mỗi loại nút có class widget tương ứng trong `lib/resources/widgets/buttons/partials/`.

### Thay đổi kiểu mặc định

Để chỉnh sửa giao diện mặc định của nút, sửa class `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Tùy chỉnh widget nút

Để thay đổi giao diện trực quan của một loại nút, sửa widget tương ứng trong `lib/resources/widgets/buttons/partials/`. Ví dụ, để thay đổi bán kính viền hoặc bóng đổ của nút primary:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Tạo loại nút mới

Để thêm loại nút mới:

1. Tạo file widget mới trong `lib/resources/widgets/buttons/partials/` kế thừa `StatefulAppButton`.
2. Triển khai phương thức `buildButton`.
3. Thêm phương thức static trong class `Button`.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Sau đó đăng ký trong class `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Tham chiếu tham số

### Tham số chung (Tất cả loại nút)

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `text` | `String` | bắt buộc | Nhãn văn bản của nút |
| `onPressed` | `VoidCallback?` | `null` | Callback khi nút được nhấn. Trả về `Future` cho trạng thái tải tự động |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Record gửi biểu mẫu (instance biểu mẫu, callback thành công) |
| `onFailure` | `Function(dynamic)?` | `null` | Được gọi khi xác thực biểu mẫu thất bại |
| `showToastError` | `bool` | `true` | Hiển thị thông báo toast khi lỗi xác thực biểu mẫu |
| `width` | `double?` | `null` | Chiều rộng nút (mặc định toàn chiều rộng) |

### Tham số theo loại

#### Button.outlined

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Màu outline của theme | Màu viền |
| `textColor` | `Color?` | Màu chính của theme | Màu chữ |

#### Button.textOnly

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Màu chính của theme | Màu chữ |

#### Button.icon

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `icon` | `Widget` | bắt buộc | Widget icon để hiển thị |
| `color` | `Color?` | Màu chính của theme | Màu nền |

#### Button.gradient

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Màu chính và màu thứ ba | Các điểm dừng màu gradient |

#### Button.rounded

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Màu primary container của theme | Màu nền |
| `borderRadius` | `BorderRadius?` | Hình viên thuốc (height / 2) | Bán kính góc |

#### Button.transparency

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `color` | `Color?` | Tự thích ứng theo theme | Màu chữ |

### Tham số ButtonAnimationStyle

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Thay đổi theo loại | Thời gian hoạt ảnh |
| `curve` | `Curve` | Thay đổi theo loại | Đường cong hoạt ảnh |
| `enableHapticFeedback` | `bool` | Thay đổi theo loại | Kích hoạt phản hồi haptic khi nhấn |
| `translateY` | `double` | `4.0` | Clickable: khoảng cách nhấn dọc |
| `shadowOffset` | `double` | `4.0` | Clickable: độ sâu bóng đổ |
| `scaleMin` | `double` | `0.92` | Bounce: tỷ lệ thu nhỏ tối thiểu khi nhấn |
| `pulseScale` | `double` | `1.05` | Pulse: tỷ lệ phóng to tối đa trong pulse |
| `squeezeX` | `double` | `0.95` | Squeeze: nén ngang |
| `squeezeY` | `double` | `1.05` | Squeeze: giãn dọc |
| `jellyStrength` | `double` | `0.15` | Jelly: cường độ lắc lư |
| `shineColor` | `Color` | `Colors.white` | Shine: màu highlight |
| `shineWidth` | `double` | `0.3` | Shine: chiều rộng dải sáng |
| `rippleScale` | `double` | `2.0` | Ripple: tỷ lệ mở rộng |
| `morphRadius` | `double` | `24.0` | Morph: bán kính viền mục tiêu |
| `shakeOffset` | `double` | `8.0` | Shake: độ dịch chuyển ngang |
| `shakeCount` | `int` | `3` | Shake: số lần dao động |

### Tham số ButtonSplashStyle

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Màu surface của theme | Màu hiệu ứng splash |
| `highlightColor` | `Color?` | Màu surface của theme | Màu hiệu ứng highlight |
| `splashOpacity` | `double` | `0.12` | Độ mờ của splash |
| `highlightOpacity` | `double` | `0.06` | Độ mờ của highlight |
| `borderRadius` | `BorderRadius?` | `null` | Bán kính cắt splash |
