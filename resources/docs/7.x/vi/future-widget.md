# FutureWidget

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Tùy chỉnh trạng thái tải](#customizing-loading "Tùy chỉnh trạng thái tải")
    - [Kiểu tải bình thường](#normal-loading "Kiểu tải bình thường")
    - [Kiểu tải Skeletonizer](#skeletonizer-loading "Kiểu tải Skeletonizer")
    - [Không có kiểu tải](#no-loading "Không có kiểu tải")
- [Xử lý lỗi](#error-handling "Xử lý lỗi")


<div id="introduction"></div>

## Giới thiệu

**FutureWidget** là cách đơn giản để hiển thị `Future` trong các dự án {{ config('app.name') }} của bạn. Nó bọc `FutureBuilder` của Flutter và cung cấp API gọn gàng hơn với các trạng thái tải tích hợp sẵn.

Khi Future đang xử lý, nó sẽ hiển thị một loader. Khi Future hoàn thành, dữ liệu được trả về qua callback `child`.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Đây là một ví dụ đơn giản về cách sử dụng `FutureWidget`:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

Widget sẽ tự động xử lý trạng thái tải cho người dùng cho đến khi Future hoàn thành.

<div id="customizing-loading"></div>

## Tùy chỉnh trạng thái tải

Bạn có thể tùy chỉnh cách trạng thái tải hiển thị bằng tham số `loadingStyle`.

<div id="normal-loading"></div>

### Kiểu tải bình thường

Sử dụng `LoadingStyle.normal()` để hiển thị widget tải chuẩn. Bạn có thể tùy chọn cung cấp một widget con tùy chỉnh:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

Nếu không cung cấp widget con, loader mặc định của ứng dụng {{ config('app.name') }} sẽ được hiển thị.

<div id="skeletonizer-loading"></div>

### Kiểu tải Skeletonizer

Sử dụng `LoadingStyle.skeletonizer()` để hiển thị hiệu ứng tải skeleton. Điều này rất phù hợp để hiển thị giao diện giữ chỗ phù hợp với bố cục nội dung của bạn:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

Các hiệu ứng skeleton có sẵn:
- `SkeletonizerEffect.shimmer` - Hiệu ứng lấp lánh hoạt hình (mặc định)
- `SkeletonizerEffect.pulse` - Hiệu ứng hoạt hình nhấp nháy
- `SkeletonizerEffect.solid` - Hiệu ứng màu đặc

<div id="no-loading"></div>

### Không có kiểu tải

Sử dụng `LoadingStyle.none()` để ẩn hoàn toàn indicator tải:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## Xử lý lỗi

Bạn có thể xử lý lỗi từ Future bằng callback `onError`:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

Nếu không cung cấp callback `onError` và xảy ra lỗi, một `SizedBox.shrink()` trống sẽ được hiển thị.

### Tham số

| Tham số | Kiểu | Mô tả |
|---------|------|-------|
| `future` | `Future<T>?` | Future cần chờ |
| `child` | `Widget Function(BuildContext, T?)` | Hàm builder được gọi khi Future hoàn thành |
| `loadingStyle` | `LoadingStyle?` | Tùy chỉnh indicator tải |
| `onError` | `Widget Function(AsyncSnapshot)?` | Hàm builder được gọi khi Future có lỗi |
