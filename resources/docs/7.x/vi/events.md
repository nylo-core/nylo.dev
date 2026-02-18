# Events

---

<a name="section-1"></a>
- [Giới thiệu](#introduction)
  - [Hiểu về Events](#understanding-events)
  - [Các ví dụ Event phổ biến](#common-event-examples)
- [Tạo một Event](#creating-an-event)
  - [Cấu trúc Event](#event-structure)
- [Phát sự kiện](#dispatching-events)
  - [Phát sự kiện cơ bản](#basic-event-dispatch)
  - [Phát kèm dữ liệu](#dispatching-with-data)
  - [Phát sóng sự kiện](#broadcasting-events)
- [Lắng nghe sự kiện](#listening-to-events)
  - [Sử dụng helper `listenOn`](#using-the-listenon-helper)
  - [Sử dụng helper `listen`](#using-the-listen-helper)
  - [Hủy đăng ký sự kiện](#unsubscribing-from-events)
- [Làm việc với Listener](#working-with-listeners)
  - [Thêm nhiều Listener](#adding-multiple-listeners)
  - [Triển khai logic Listener](#implementing-listener-logic)
- [Phát sóng sự kiện toàn cục](#global-event-broadcasting)
  - [Bật phát sóng toàn cục](#enabling-global-broadcasting)

<div id="introduction"></div>

## Giới thiệu

Events rất hữu ích khi bạn cần xử lý logic sau khi điều gì đó xảy ra trong ứng dụng. Hệ thống event của Nylo cho phép bạn tạo, phát và lắng nghe các sự kiện từ bất kỳ đâu trong ứng dụng, giúp xây dựng các ứng dụng Flutter phản hồi và hướng sự kiện dễ dàng hơn.

<div id="understanding-events"></div>

### Hiểu về Events

Lập trình hướng sự kiện là một mô hình trong đó luồng ứng dụng của bạn được xác định bởi các sự kiện như hành động của người dùng, đầu ra cảm biến, hoặc thông điệp từ các chương trình hoặc luồng khác. Cách tiếp cận này giúp tách rời các phần khác nhau của ứng dụng, làm cho mã nguồn dễ bảo trì và dễ hiểu hơn.

<div id="common-event-examples"></div>

### Các ví dụ Event phổ biến

Dưới đây là một số sự kiện điển hình mà ứng dụng của bạn có thể sử dụng:
- Hoàn thành đăng ký người dùng
- Người dùng đăng nhập/đăng xuất
- Sản phẩm được thêm vào giỏ hàng
- Thanh toán xử lý thành công
- Hoàn thành đồng bộ dữ liệu
- Nhận thông báo đẩy

<div id="creating-an-event"></div>

## Tạo một Event

Bạn có thể tạo một event mới bằng Nylo framework CLI hoặc Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

Sau khi chạy lệnh, một file event mới sẽ được tạo trong thư mục `app/events/`.

<div id="event-structure"></div>

### Cấu trúc Event

Đây là cấu trúc của một file event mới được tạo (ví dụ: `app/events/payment_successful_event.dart`):

```dart
import 'package:nylo_framework/nylo_framework.dart';

class PaymentSuccessfulEvent implements NyEvent {
  final listeners = {
    DefaultListener: DefaultListener(),
  };
}

class DefaultListener extends NyListener {
  handle(dynamic event) async {
    // Handle the payload from event
  }
}
```

<div id="dispatching-events"></div>

## Phát sự kiện

Events có thể được phát từ bất kỳ đâu trong ứng dụng bằng phương thức helper `event`.

<div id="basic-event-dispatch"></div>

### Phát sự kiện cơ bản

Để phát một sự kiện không có dữ liệu:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Phát kèm dữ liệu

Để truyền dữ liệu cùng với sự kiện:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Phát sóng sự kiện

Mặc định, các event của Nylo chỉ được xử lý bởi các listener được định nghĩa trong lớp event. Để phát sóng một sự kiện (cho phép các listener bên ngoài nhận được), sử dụng tham số `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Lắng nghe sự kiện

Nylo cung cấp nhiều cách để lắng nghe và phản hồi các sự kiện.

<div id="using-the-listenon-helper"></div>

### Sử dụng helper `listenOn`

Helper `listenOn` có thể được sử dụng ở bất kỳ đâu trong ứng dụng để lắng nghe các sự kiện được phát sóng:

```dart
NyEventSubscription subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Access event data
  final user = data['user'];
  final amount = data['amount'];

  // Handle the event
  showSuccessMessage("Payment of $amount received");
});
```

<div id="using-the-listen-helper"></div>

### Sử dụng helper `listen`

Helper `listen` có sẵn trong các lớp `NyPage` và `NyState`. Nó tự động quản lý subscription, hủy đăng ký khi widget bị dispose:

```dart
class _CheckoutPageState extends NyPage<CheckoutPage> {
  @override
  get init => () {
    listen<PaymentSuccessfulEvent>((data) {
      // Handle payment success
      routeTo(OrderConfirmationPage.path);
    });

    listen<PaymentFailedEvent>((data) {
      // Handle payment failure
      displayErrorMessage(data['error']);
    });
  };

  // Rest of your page implementation
}
```

<div id="unsubscribing-from-events"></div>

### Hủy đăng ký sự kiện

Khi sử dụng `listenOn`, bạn phải hủy đăng ký thủ công để tránh rò rỉ bộ nhớ:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

Helper `listen` tự động xử lý việc hủy đăng ký khi widget bị dispose.

<div id="working-with-listeners"></div>

## Làm việc với Listener

Listener là các lớp phản hồi sự kiện. Mỗi event có thể có nhiều listener để xử lý các khía cạnh khác nhau của sự kiện.

<div id="adding-multiple-listeners"></div>

### Thêm nhiều Listener

Bạn có thể thêm nhiều listener vào event bằng cách cập nhật thuộc tính `listeners`:

```dart
class PaymentSuccessfulEvent implements NyEvent {
  final listeners = {
    NotificationListener: NotificationListener(),
    AnalyticsListener: AnalyticsListener(),
    OrderProcessingListener: OrderProcessingListener(),
  };
}
```

<div id="implementing-listener-logic"></div>

### Triển khai logic Listener

Mỗi listener nên triển khai phương thức `handle` để xử lý sự kiện:

```dart
class NotificationListener extends NyListener {
  handle(dynamic event) async {
    // Send notification to user
    final user = event['user'];
    await NotificationService.sendNotification(
      userId: user.id,
      title: "Payment Successful",
      body: "Your payment of ${event['amount']} was processed successfully."
    );
  }
}

class AnalyticsListener extends NyListener {
  handle(dynamic event) async {
    // Log analytics event
    await AnalyticsService.logEvent(
      "payment_successful",
      parameters: {
        'amount': event['amount'],
        'userId': event['user'].id,
      }
    );
  }
}
```

<div id="global-event-broadcasting"></div>

## Phát sóng sự kiện toàn cục

Nếu bạn muốn tất cả events được phát sóng tự động mà không cần chỉ định `broadcast: true` mỗi lần, bạn có thể bật phát sóng toàn cục.

<div id="enabling-global-broadcasting"></div>

### Bật phát sóng toàn cục

Chỉnh sửa file `app/providers/app_provider.dart` và thêm phương thức `broadcastEvents()` vào instance Nylo:

```dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    // Other configuration

    // Enable broadcasting for all events
    nylo.broadcastEvents();
  }
}
```

Khi phát sóng toàn cục được bật, bạn có thể phát và lắng nghe sự kiện ngắn gọn hơn:

```dart
// Dispatch event (no need for broadcast: true)
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
});

// Listen for the event anywhere
listen<PaymentSuccessfulEvent>((data) {
  // Handle event data
});
```
