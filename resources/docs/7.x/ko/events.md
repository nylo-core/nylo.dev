# Events

---

<a name="section-1"></a>
- [소개](#introduction)
  - [이벤트 이해하기](#understanding-events)
  - [일반적인 이벤트 예시](#common-event-examples)
- [이벤트 생성](#creating-an-event)
  - [이벤트 구조](#event-structure)
- [이벤트 디스패치](#dispatching-events)
  - [기본 이벤트 디스패치](#basic-event-dispatch)
  - [데이터와 함께 디스패치](#dispatching-with-data)
  - [이벤트 브로드캐스팅](#broadcasting-events)
- [이벤트 수신](#listening-to-events)
  - [`listenOn` 헬퍼 사용](#using-the-listenon-helper)
  - [`listen` 헬퍼 사용](#using-the-listen-helper)
  - [이벤트 구독 해제](#unsubscribing-from-events)
- [리스너 작업](#working-with-listeners)
  - [여러 리스너 추가](#adding-multiple-listeners)
  - [리스너 로직 구현](#implementing-listener-logic)
- [글로벌 이벤트 브로드캐스팅](#global-event-broadcasting)
  - [글로벌 브로드캐스팅 활성화](#enabling-global-broadcasting)

<div id="introduction"></div>

## 소개

이벤트는 애플리케이션에서 어떤 일이 발생한 후 로직을 처리해야 할 때 강력합니다. Nylo의 이벤트 시스템을 사용하면 애플리케이션 어디에서나 이벤트를 생성, 디스패치, 수신할 수 있어 반응형 이벤트 기반 Flutter 애플리케이션을 더 쉽게 구축할 수 있습니다.

<div id="understanding-events"></div>

### 이벤트 이해하기

이벤트 기반 프로그래밍은 사용자 동작, 센서 출력 또는 다른 프로그램이나 스레드의 메시지와 같은 이벤트에 의해 애플리케이션의 흐름이 결정되는 패러다임입니다. 이 접근 방식은 애플리케이션의 다양한 부분을 분리하여 코드를 더 유지보수하기 쉽고 이해하기 쉽게 만듭니다.

<div id="common-event-examples"></div>

### 일반적인 이벤트 예시

애플리케이션에서 사용할 수 있는 일반적인 이벤트 예시입니다:
- 사용자 가입 완료
- 사용자 로그인/로그아웃
- 장바구니에 상품 추가
- 결제 성공 처리
- 데이터 동기화 완료
- 푸시 알림 수신

<div id="creating-an-event"></div>

## 이벤트 생성

Nylo 프레임워크 CLI 또는 Metro를 사용하여 새 이벤트를 생성할 수 있습니다:

```bash
metro make:event PaymentSuccessfulEvent
```

명령을 실행하면 `app/events/` 디렉토리에 새 이벤트 파일이 생성됩니다.

<div id="event-structure"></div>

### 이벤트 구조

새로 생성된 이벤트 파일의 구조입니다 (예: `app/events/payment_successful_event.dart`):

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

## 이벤트 디스패치

`event` 헬퍼 메서드를 사용하여 애플리케이션 어디에서나 이벤트를 디스패치할 수 있습니다.

<div id="basic-event-dispatch"></div>

### 기본 이벤트 디스패치

데이터 없이 이벤트를 디스패치합니다:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### 데이터와 함께 디스패치

이벤트와 함께 데이터를 전달합니다:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### 이벤트 브로드캐스팅

기본적으로 Nylo 이벤트는 이벤트 클래스에 정의된 리스너에서만 처리됩니다. 이벤트를 브로드캐스트(외부 리스너에서 사용 가능하게)하려면 `broadcast` 매개변수를 사용합니다:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## 이벤트 수신

Nylo는 이벤트를 수신하고 응답하는 여러 방법을 제공합니다.

<div id="using-the-listenon-helper"></div>

### `listenOn` 헬퍼 사용

`listenOn` 헬퍼는 애플리케이션 어디에서나 브로드캐스트된 이벤트를 수신하는 데 사용할 수 있습니다:

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

### `listen` 헬퍼 사용

`listen` 헬퍼는 `NyPage` 및 `NyState` 클래스에서 사용할 수 있습니다. 위젯이 dispose될 때 자동으로 구독을 관리하고 해제합니다:

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

### 이벤트 구독 해제

`listenOn`을 사용할 때는 메모리 누수를 방지하기 위해 수동으로 구독을 해제해야 합니다:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

`listen` 헬퍼는 위젯이 dispose될 때 자동으로 구독 해제를 처리합니다.

<div id="working-with-listeners"></div>

## 리스너 작업

리스너는 이벤트에 응답하는 클래스입니다. 각 이벤트는 이벤트의 다양한 측면을 처리하기 위해 여러 리스너를 가질 수 있습니다.

<div id="adding-multiple-listeners"></div>

### 여러 리스너 추가

`listeners` 속성을 업데이트하여 이벤트에 여러 리스너를 추가할 수 있습니다:

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

### 리스너 로직 구현

각 리스너는 이벤트를 처리하기 위해 `handle` 메서드를 구현해야 합니다:

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

## 글로벌 이벤트 브로드캐스팅

매번 `broadcast: true`를 지정하지 않고 모든 이벤트가 자동으로 브로드캐스트되게 하려면 글로벌 브로드캐스팅을 활성화할 수 있습니다.

<div id="enabling-global-broadcasting"></div>

### 글로벌 브로드캐스팅 활성화

`app/providers/app_provider.dart` 파일을 수정하고 Nylo 인스턴스에 `broadcastEvents()` 메서드를 추가합니다:

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

글로벌 브로드캐스팅이 활성화되면 이벤트를 더 간결하게 디스패치하고 수신할 수 있습니다:

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
