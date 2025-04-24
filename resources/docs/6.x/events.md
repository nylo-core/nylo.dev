# Events

---

<a name="section-1"></a>
- [Introduction](#introduction)
  - [Understanding Events](#understanding-events)
  - [Common Event Examples](#common-event-examples)
- [Creating an Event](#creating-an-event)
  - [Event Structure](#event-structure)
- [Dispatching Events](#dispatching-events)
  - [Basic Event Dispatch](#basic-event-dispatch)
  - [Dispatching with Data](#dispatching-with-data)
  - [Broadcasting Events](#broadcasting-events)
- [Listening to Events](#listening-to-events)
  - [Using the `listenOn` Helper](#using-the-listenon-helper)
  - [Using the `listen` Helper](#using-the-listen-helper)
  - [Unsubscribing from Events](#unsubscribing-from-events)
- [Working with Listeners](#working-with-listeners)
  - [Adding Multiple Listeners](#adding-multiple-listeners)
  - [Implementing Listener Logic](#implementing-listener-logic)
- [Global Event Broadcasting](#global-event-broadcasting)
  - [Enabling Global Broadcasting](#enabling-global-broadcasting)

<div id="introduction"></div>
<br>

## Introduction

Events are powerful when you need to handle logic after something occurs in your application. Nylo's event system allows you to create, dispatch, and listen to events from anywhere in your application, making it easier to build responsive, event-driven Flutter applications.

<div id="understanding-events"></div>
<br>

### Understanding Events

Event-driven programming is a paradigm where the flow of your application is determined by events such as user actions, sensor outputs, or messages from other programs or threads. This approach helps decouple different parts of your application, making your code more maintainable and easier to reason about.

<div id="common-event-examples"></div>
<br>

### Common Event Examples

Here are some typical events your application might use:
- User registration completed
- User logged in/out
- Product added to cart
- Payment processed successfully
- Data synchronization completed
- Push notification received

<div id="creating-an-event"></div>
<br>

## Creating an Event

You can create a new event using either the Nylo framework CLI or Metro:

```bash
# Using Nylo framework CLI
dart run nylo_framework:main make:event PaymentSuccessfulEvent

# Using Metro
metro make:event PaymentSuccessfulEvent
```

After running the command, a new event file will be created in the `app/events/` directory.

<div id="event-structure"></div>
<br>

### Event Structure

Here's the structure of a newly created event file (e.g., `app/events/payment_successful_event.dart`):

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
<br>

## Dispatching Events

Events can be dispatched from anywhere in your application using the `event` helper method.

<div id="basic-event-dispatch"></div>
<br>

### Basic Event Dispatch

To dispatch an event without any data:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>
<br>

### Dispatching with Data

To pass data along with your event:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>
<br>

### Broadcasting Events

By default, Nylo events are only handled by the listeners defined in the event class. To broadcast an event (making it available to external listeners), use the `broadcast` parameter:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>
<br>

## Listening to Events

Nylo provides multiple ways to listen for and respond to events.

<div id="using-the-listenon-helper"></div>
<br>

### Using the `listenOn` Helper

The `listenOn` helper can be used anywhere in your application to listen for broadcasted events:

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
<br>

### Using the `listen` Helper

The `listen` helper is available in `NyPage` and `NyState` classes. It automatically manages subscriptions, unsubscribing when the widget is disposed:

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
<br>

### Unsubscribing from Events

When using `listenOn`, you must manually unsubscribe to prevent memory leaks:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

The `listen` helper automatically handles unsubscription when the widget is disposed.

<div id="working-with-listeners"></div>
<br>

## Working with Listeners

Listeners are classes that respond to events. Each event can have multiple listeners to handle different aspects of the event.

<div id="adding-multiple-listeners"></div>
<br>

### Adding Multiple Listeners

You can add multiple listeners to your event by updating the `listeners` property:

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
<br>

### Implementing Listener Logic

Each listener should implement the `handle` method to process the event:

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
<br>

## Global Event Broadcasting

If you want all events to be broadcasted automatically without specifying `broadcast: true` each time, you can enable global broadcasting.

<div id="enabling-global-broadcasting"></div>
<br>

### Enabling Global Broadcasting

Edit your `app/providers/app_provider.dart` file and add the `broadcastEvents()` method to your Nylo instance:

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

With global broadcasting enabled, you can dispatch and listen to events more concisely:

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
