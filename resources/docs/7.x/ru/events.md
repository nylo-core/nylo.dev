# Events

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
  - [Понимание событий](#understanding-events "Понимание событий")
  - [Типичные примеры событий](#common-event-examples "Типичные примеры событий")
- [Создание события](#creating-an-event "Создание события")
  - [Структура события](#event-structure "Структура события")
- [Отправка событий](#dispatching-events "Отправка событий")
  - [Базовая отправка события](#basic-event-dispatch "Базовая отправка события")
  - [Отправка с данными](#dispatching-with-data "Отправка с данными")
  - [Трансляция событий](#broadcasting-events "Трансляция событий")
- [Прослушивание событий](#listening-to-events "Прослушивание событий")
  - [Использование помощника `listenOn`](#using-the-listenon-helper "Использование помощника listenOn")
  - [Использование помощника `listen`](#using-the-listen-helper "Использование помощника listen")
  - [Отписка от событий](#unsubscribing-from-events "Отписка от событий")
- [Работа со слушателями](#working-with-listeners "Работа со слушателями")
  - [Добавление нескольких слушателей](#adding-multiple-listeners "Добавление нескольких слушателей")
  - [Реализация логики слушателей](#implementing-listener-logic "Реализация логики слушателей")
- [Глобальная трансляция событий](#global-event-broadcasting "Глобальная трансляция событий")
  - [Включение глобальной трансляции](#enabling-global-broadcasting "Включение глобальной трансляции")

<div id="introduction"></div>

## Введение

События полезны, когда вам нужно обработать логику после того, как что-то произошло в вашем приложении. Система событий Nylo позволяет создавать, отправлять и прослушивать события из любого места приложения, что упрощает создание отзывчивых, событийно-ориентированных Flutter-приложений.

<div id="understanding-events"></div>

### Понимание событий

Событийно-ориентированное программирование -- это парадигма, в которой поток вашего приложения определяется событиями, такими как действия пользователя, данные датчиков или сообщения от других программ или потоков. Такой подход помогает разделить различные части приложения, делая код более поддерживаемым и понятным.

<div id="common-event-examples"></div>

### Типичные примеры событий

Вот некоторые типичные события, которые может использовать ваше приложение:
- Регистрация пользователя завершена
- Пользователь вошёл/вышел из системы
- Товар добавлен в корзину
- Платёж успешно обработан
- Синхронизация данных завершена
- Push-уведомление получено

<div id="creating-an-event"></div>

## Создание события

Вы можете создать новое событие с помощью CLI фреймворка Nylo или Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

После выполнения команды новый файл события будет создан в директории `app/events/`.

<div id="event-structure"></div>

### Структура события

Вот структура вновь созданного файла события (например, `app/events/payment_successful_event.dart`):

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

## Отправка событий

События можно отправлять из любого места приложения с помощью вспомогательного метода `event`.

<div id="basic-event-dispatch"></div>

### Базовая отправка события

Для отправки события без данных:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Отправка с данными

Для передачи данных вместе с событием:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Трансляция событий

По умолчанию события Nylo обрабатываются только слушателями, определёнными в классе события. Чтобы транслировать событие (сделать его доступным для внешних слушателей), используйте параметр `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Прослушивание событий

Nylo предоставляет несколько способов прослушивания и реагирования на события.

<div id="using-the-listenon-helper"></div>

### Использование помощника `listenOn`

Помощник `listenOn` можно использовать в любом месте приложения для прослушивания транслируемых событий:

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

### Использование помощника `listen`

Помощник `listen` доступен в классах `NyPage` и `NyState`. Он автоматически управляет подписками, отписываясь при уничтожении виджета:

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

### Отписка от событий

При использовании `listenOn` необходимо вручную отписываться, чтобы избежать утечек памяти:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

Помощник `listen` автоматически обрабатывает отписку при уничтожении виджета.

<div id="working-with-listeners"></div>

## Работа со слушателями

Слушатели -- это классы, которые реагируют на события. Каждое событие может иметь несколько слушателей для обработки различных аспектов события.

<div id="adding-multiple-listeners"></div>

### Добавление нескольких слушателей

Вы можете добавить несколько слушателей к событию, обновив свойство `listeners`:

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

### Реализация логики слушателей

Каждый слушатель должен реализовать метод `handle` для обработки события:

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

## Глобальная трансляция событий

Если вы хотите, чтобы все события автоматически транслировались без указания `broadcast: true` каждый раз, можно включить глобальную трансляцию.

<div id="enabling-global-broadcasting"></div>

### Включение глобальной трансляции

Отредактируйте файл `app/providers/app_provider.dart` и добавьте метод `broadcastEvents()` к вашему экземпляру Nylo:

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

При включённой глобальной трансляции вы можете отправлять и прослушивать события более лаконично:

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
