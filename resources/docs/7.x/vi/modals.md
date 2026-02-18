# Modals

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Tạo Modal](#creating-a-modal "Tạo Modal")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
- [Tạo Modal](#creating-a-modal "Tạo Modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Tham số](#parameters "Tham số")
  - [Hành động](#actions "Hành động")
  - [Header](#header "Header")
  - [Nút đóng](#close-button "Nút đóng")
  - [Tùy chỉnh giao diện](#custom-decoration "Tùy chỉnh giao diện")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} cung cấp hệ thống modal được xây dựng xung quanh **bottom sheet modal**.

Class `BottomSheetModal` cung cấp API linh hoạt để hiển thị lớp phủ nội dung với hành động, header, nút đóng, và kiểu dáng tùy chỉnh.

Modal hữu ích cho:
- Hộp thoại xác nhận (ví dụ: đăng xuất, xóa)
- Form nhập liệu nhanh
- Bảng hành động với nhiều tùy chọn
- Lớp phủ thông tin

<div id="creating-a-modal"></div>

## Tạo Modal

Bạn có thể tạo modal mới bằng Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

Lệnh này tạo hai thứ:

1. **Widget nội dung modal** tại `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **Phương thức static** được thêm vào class `BottomSheetModal` trong `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

Sau đó bạn có thể hiển thị modal từ bất kỳ đâu:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Nếu modal cùng tên đã tồn tại, sử dụng cờ `--force` để ghi đè:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Hiển thị modal bằng `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Tạo Modal

Mẫu được khuyến nghị là tạo class `BottomSheetModal` với các phương thức static cho mỗi loại modal. Boilerplate cung cấp cấu trúc này:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

Gọi từ bất kỳ đâu:

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` là phương thức cốt lõi để hiển thị modal.

<div id="parameters"></div>

### Tham số

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | bắt buộc | Build context cho modal |
| `child` | `Widget` | bắt buộc | Widget nội dung chính |
| `actionsRow` | `List<Widget>` | `[]` | Widget hành động hiển thị theo hàng ngang |
| `actionsColumn` | `List<Widget>` | `[]` | Widget hành động hiển thị theo cột dọc |
| `height` | `double?` | null | Chiều cao cố định cho modal |
| `header` | `Widget?` | null | Widget header ở trên cùng |
| `useSafeArea` | `bool` | `true` | Bọc nội dung trong SafeArea |
| `isScrollControlled` | `bool` | `false` | Cho phép modal cuộn được |
| `showCloseButton` | `bool` | `false` | Hiển thị nút đóng X |
| `headerPadding` | `EdgeInsets?` | null | Padding khi có header |
| `backgroundColor` | `Color?` | null | Màu nền modal |
| `showHandle` | `bool` | `true` | Hiển thị thanh kéo ở trên cùng |
| `closeButtonColor` | `Color?` | null | Màu nền nút đóng |
| `closeButtonIconColor` | `Color?` | null | Màu biểu tượng nút đóng |
| `modalDecoration` | `BoxDecoration?` | null | Tùy chỉnh giao diện cho container modal |
| `handleColor` | `Color?` | null | Màu thanh kéo |

<div id="actions"></div>

### Hành động

Hành động là các nút hiển thị ở phần dưới modal.

**Hành động theo hàng** được đặt cạnh nhau, mỗi nút chiếm không gian bằng nhau:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**Hành động theo cột** được xếp chồng theo chiều dọc:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### Header

Thêm header nằm phía trên nội dung chính:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### Nút đóng

Hiển thị nút đóng ở góc trên bên phải:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### Tùy chỉnh giao diện

Tùy chỉnh giao diện container modal:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` cấu hình giao diện cho bottom sheet modal được sử dụng bởi form picker và các thành phần khác:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Màu nền của modal |
| `barrierColor` | `NyColor?` | Màu lớp phủ phía sau modal |
| `useRootNavigator` | `bool` | Sử dụng root navigator (mặc định: `false`) |
| `routeSettings` | `RouteSettings?` | Cài đặt route cho modal |
| `titleStyle` | `TextStyle?` | Kiểu chữ cho tiêu đề |
| `itemStyle` | `TextStyle?` | Kiểu chữ cho item danh sách |
| `clearButtonStyle` | `TextStyle?` | Kiểu chữ cho nút xóa |

<div id="examples"></div>

## Ví dụ

### Modal xác nhận

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### Modal nội dung cuộn được

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### Bảng hành động

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
