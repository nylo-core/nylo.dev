# Screenshots
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về Screenshots")
- [Chụp màn hình](#capturing-the-screen "Chụp toàn bộ màn hình")
- [Chụp một vùng](#capturing-an-area "Chụp một phần màn hình bằng ScreenshotArea")
- [Lưu ảnh chụp](#saving-a-screenshot "Lưu ảnh chụp vào một disk")
- [Trang giới thiệu trên cửa hàng](#store-listings "Đặt kích thước ảnh chụp cho App Store và Google Play")
  - [Kích thước cửa hàng](#store-sizes "Các kích thước ScreenshotStore")
- [Ẩn nội dung](#hiding-content "Ẩn nội dung khỏi ảnh chụp")
- [Chụp một Widget](#capturing-a-widget "Chụp một widget mà không hiển thị nó")
- [Locale và Theme](#locales-and-themes "Chụp ở locale hoặc theme khác")
- [Từ Metro](#from-metro "Chụp ảnh màn hình từ Metro")

<div id="introduction"></div>

## Giới thiệu

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` tạo một ảnh PNG của màn hình ứng dụng, hoặc của một phần màn hình:

``` dart
// Toàn bộ màn hình, bao gồm cả dialog và sheet
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Một phần của màn hình
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Việc chụp hoạt động ở mọi chế độ build, nên chúng có thể cung cấp sức mạnh cho các tính năng trong ứng dụng của bạn, như chia sẻ biên lai hoặc đính kèm màn hình vào báo cáo lỗi. [Metro](#from-metro) và [playbook](/docs/{{ $version }}/playbooks#screenshots) dùng cùng một đoạn code.

Một `Screenshot` giữ PNG dưới dạng `bytes`, cùng `width` và `height` tính bằng pixel vật lý.

<div id="capturing-the-screen"></div>

## Chụp màn hình

`Screenshot.capture()` đọc toàn bộ màn hình ứng dụng như người dùng nhìn thấy:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Tham số | Mô tả |
|-----------|-------------|
| `pixelRatio` | Kích thước so với pixel logic. Mặc định là của thiết bị, nên PNG có độ phân giải gốc của màn hình |
| `settle` | Chờ khung hình tiếp theo trước khi chụp (mặc định `true`). Truyền `false` khi một khung hình vừa được vẽ |
| `locale` | Hiển thị màn hình bằng ngôn ngữ này cho lần chụp, ví dụ `'es'` |
| `theme` | Hiển thị màn hình bằng id theme này cho lần chụp, ví dụ `'dark'` |

Một ứng dụng được build bằng `NyApp.materialApp` chụp chính xác các trang của nó. Nếu bạn tự dựng `MaterialApp`, hãy thêm một `ScreenshotRoot` vào `builder` của nó:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Chụp một vùng

Bọc một phần của màn hình trong `ScreenshotArea` và đặt tên cho nó:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Sau đó chụp nó theo tên đó:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` nhận cùng các tham số `pixelRatio`, `settle`, `locale` và `theme` như `capture`. `Screenshot.areas` liệt kê tên của các vùng trên màn hình.

<div id="saving-a-screenshot"></div>

## Lưu ảnh chụp

`store` lưu PNG vào một disk file storage và trả về đường dẫn:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Không có `disk`, nó lưu vào disk mặc định. Để chia sẻ hoặc tải ảnh lên, hãy đọc `shot.bytes`.

<div id="store-listings"></div>

## Trang giới thiệu trên cửa hàng

`forStore` trả về một ảnh chụp mới đúng kích thước mà cửa hàng ứng dụng yêu cầu:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Một màn hình có hình dạng gần giống sẽ lấp đầy kích thước đó, còn các màn hình khác được đặt vừa bên trong. Với `frame: true`, màn hình được vẽ trong khung viền điện thoại bên dưới `caption`.

| Tham số | Mô tả |
|-----------|-------------|
| `frame` | Vẽ màn hình bên trong khung viền điện thoại |
| `caption` | Văn bản phía trên điện thoại, dùng cùng `frame` |
| `background` | `Color` xung quanh màn hình |

Dùng `framed()` để vẽ khung viền theo kích thước riêng của ảnh chụp:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Kích thước cửa hàng

| Kích thước | Id | Pixel |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` liệt kê mọi kích thước, và `ScreenshotStore.named('ios-6.9')` tìm một kích thước theo id của nó.

<div id="hiding-content"></div>

## Ẩn nội dung

Bọc bất kỳ nội dung riêng tư nào trong `ScreenshotHidden`. Nó hiển thị bình thường trên màn hình, và một khối màu xám che nó trong mọi ảnh chụp:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Truyền `replacement` để vẽ placeholder của riêng bạn thay cho khối màu xám.

<div id="capturing-a-widget"></div>

## Chụp một Widget

`Screenshot.widget` chụp một widget mà không hiển thị nó, như một thẻ chia sẻ được dựng chỉ để tạo ảnh:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG có kích thước bằng widget, nên hãy bọc nó trong `SizedBox` để có kích thước chính xác. Truyền `context` để cho widget theme và localization của context đó, `size` để đặt không gian mà nó được bố cục trong đó, và `delay` để cho hình ảnh có thời gian tải.

<div id="locales-and-themes"></div>

## Locale và Theme

Truyền `locale` hoặc `theme` để hiển thị màn hình theo cách khác cho một lần chụp:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

Ứng dụng chuyển đổi tại chỗ, giữ nguyên trang, state của nó và ngăn xếp điều hướng, rồi quay lại ngay sau đó. Không có gì được lưu, nên các lựa chọn của chính người dùng được giữ nguyên. Một locale cần một tệp ngôn ngữ trong assets của bạn, và một theme cần một id theme đã đăng ký.

<div id="from-metro"></div>

## Từ Metro

Lưu ảnh chụp màn hình của ứng dụng đang chạy từ terminal:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Xem [Metro Live](/docs/{{ $version }}/metro-live#screenshots) để biết mọi tùy chọn. Một [playbook](/docs/{{ $version }}/playbooks#screenshots) có thể chụp ảnh màn hình của cả một hành trình, ở mọi ngôn ngữ và theme.
