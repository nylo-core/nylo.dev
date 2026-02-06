# 事件

---

<a name="section-1"></a>
- [简介](#introduction)
  - [理解事件](#understanding-events)
  - [常见事件示例](#common-event-examples)
- [创建事件](#creating-an-event)
  - [事件结构](#event-structure)
- [派发事件](#dispatching-events)
  - [基本事件派发](#basic-event-dispatch)
  - [带数据派发](#dispatching-with-data)
  - [广播事件](#broadcasting-events)
- [监听事件](#listening-to-events)
  - [使用 `listenOn` 辅助函数](#using-the-listenon-helper)
  - [使用 `listen` 辅助函数](#using-the-listen-helper)
  - [取消订阅事件](#unsubscribing-from-events)
- [使用监听器](#working-with-listeners)
  - [添加多个监听器](#adding-multiple-listeners)
  - [实现监听器逻辑](#implementing-listener-logic)
- [全局事件广播](#global-event-broadcasting)
  - [启用全局广播](#enabling-global-broadcasting)

<div id="introduction"></div>

## 简介

当您需要在应用中某些事情发生后处理逻辑时，事件非常强大。Nylo 的事件系统允许您从应用的任何地方创建、派发和监听事件，使构建响应式、事件驱动的 Flutter 应用更加容易。

<div id="understanding-events"></div>

### 理解事件

事件驱动编程是一种编程范式，其中应用的流程由事件决定，例如用户操作、传感器输出或来自其他程序或线程的消息。这种方式有助于解耦应用的不同部分，使代码更易于维护和理解。

<div id="common-event-examples"></div>

### 常见事件示例

以下是您的应用可能使用的一些典型事件：
- 用户注册完成
- 用户登录/退出
- 商品添加到购物车
- 支付处理成功
- 数据同步完成
- 收到推送通知

<div id="creating-an-event"></div>

## 创建事件

您可以使用 Nylo 框架 CLI 或 Metro 创建新事件：

```bash
metro make:event PaymentSuccessfulEvent
```

运行命令后，将在 `app/events/` 目录中创建一个新的事件文件。

<div id="event-structure"></div>

### 事件结构

以下是新创建的事件文件结构（例如 `app/events/payment_successful_event.dart`）：

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

## 派发事件

可以使用 `event` 辅助方法从应用的任何位置派发事件。

<div id="basic-event-dispatch"></div>

### 基本事件派发

不带数据派发事件：

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### 带数据派发

与事件一起传递数据：

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### 广播事件

默认情况下，Nylo 事件仅由事件类中定义的监听器处理。要广播事件（使其对外部监听器可用），请使用 `broadcast` 参数：

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## 监听事件

Nylo 提供了多种监听和响应事件的方式。

<div id="using-the-listenon-helper"></div>

### 使用 `listenOn` 辅助函数

`listenOn` 辅助函数可以在应用的任何位置使用，用于监听广播的事件：

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

### 使用 `listen` 辅助函数

`listen` 辅助函数可在 `NyPage` 和 `NyState` 类中使用。它自动管理订阅，在组件被销毁时取消订阅：

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

### 取消订阅事件

使用 `listenOn` 时，您必须手动取消订阅以防止内存泄漏：

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

`listen` 辅助函数在组件被销毁时会自动处理取消订阅。

<div id="working-with-listeners"></div>

## 使用监听器

监听器是响应事件的类。每个事件可以有多个监听器来处理事件的不同方面。

<div id="adding-multiple-listeners"></div>

### 添加多个监听器

您可以通过更新 `listeners` 属性向事件添加多个监听器：

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

### 实现监听器逻辑

每个监听器应实现 `handle` 方法来处理事件：

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

## 全局事件广播

如果您希望所有事件自动广播，而无需每次指定 `broadcast: true`，可以启用全局广播。

<div id="enabling-global-broadcasting"></div>

### 启用全局广播

编辑您的 `app/providers/app_provider.dart` 文件，并在 Nylo 实例上添加 `broadcastEvents()` 方法：

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

启用全局广播后，您可以更简洁地派发和监听事件：

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
