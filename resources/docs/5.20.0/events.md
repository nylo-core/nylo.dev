# Events

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
  - [Understanding Events](#understanding-events "Understanding Events")
- [Practical example](#practical-example "Practical example")
- Usage
  - [Dispatching Events](#dispatching-events "Dispatching Events")
  - [Setup Listeners](#setup-listeners "Setup Listeners")
  - [Listeners Handle Method](#listeners-handle-method "Listeners Handle Method")

<a name="introduction"></a>
<br>

## Introduction

Events are powerful when you need to handle logic after something happens in your application.
{{ config('app.name') }} provides a simple implementation of events that allows you to call listeners registered to the event. Listeners can perform logic from the event's payload.

> {{ config('app.name') }} events are managed in the `app/events/` directory.
>
> To register your events, add them into your `config/events.dart` map.

<a name="understanding-events"></a>
<br>

## Understanding Events

Events, also known as 'Event-driven programming' is a programming paradigm in which the flow of the program is determined by events such as user actions, sensor outputs, or messages passing from other programs or threads.

### Examples of events

Here are some examples of events your application might have:
- User Registers
- User login
- Product added to cart
- Successful payment

In {{ config('app.name') }}, after creating an Event, you can add your listeners to that event that should handle your desired needs.

If we use the last example "Successful payment", we might have to do the following things:

1. Clear the user's cart & any checkout sessions stored locally
2. Use a service like <a href="https://pub.dev/packages/google_tag_manager" target="_BLANK">Google Tag Manager</a> to log the purchase for analytics

In the next section, we'll show a real world example.

<a name="practical-example"></a>
<br>

## Practical Example

In this practical example, we'll imagine we own an e-commerce app selling t-shirts. 

When user's make a successful payment, we want to dispatch an event to handle the following things:

- Clear the user's cart
- Use Google Tag Manager to log the event for analytics

#### First, create an event

```dart
dart run nylo_framework:main make:event payment_successful_event
```

After the event is created, you'll be able to view it in your `lib/app/events/` directory.

If we open the file we just created <b>lib/app/events/payment_successful_event.dart</b> you should see the below class.

``` dart 
import 'package:nylo_framework/nylo_framework.dart';

class PaymentSuccessfulEvent implements NyEvent {

  final listeners = {
    DefaultListener: DefaultListener(),
  };
}

class DefaultListener extends NyListener {
  handle(dynamic event) async {
    // handle the payload from event
  }
}
```

In this file, we'll add two new listeners, `SanitizeCheckoutListener` and `GTMPurchaseListener`.

Here's the implementation for that in our event.

``` dart 
import 'package:nylo_framework/nylo_framework.dart';
import 'package:google_tag_manager/google_tag_manager.dart' as gtm;

class PaymentSuccessfulEvent implements NyEvent {

  final listeners = {
    SanitizeCheckoutListener: SanitizeCheckoutListener(),
    GTMPurchaseListener: GTMPurchaseListener(),
  };
}

class SanitizeCheckoutListener extends NyListener {
  handle(dynamic event) async {
    // clear the cart in storage
    await NyStorage.store('cart', null); // clear the cart in storage
  }
}

class GTMPurchaseListener extends NyListener {
  handle(dynamic event) async {
    // Get payload from event
    Order order = event['order'];

    // Push event to gtm (Google tag manager).
    gtm.push({
      'ecommerce': {
        'purchase': {
          'actionField': {
            'id': order.id, // Transaction ID. Required for purchases and refunds.
            'revenue': order.revenue, // Total transaction value (incl. tax and shipping)
            'tax': order.tax,
            'shipping': order.shipping
          },
          'products': order.line_items
        }
      }
    });
  }
}
```

Next, open your <b>config/events.dart</b> file and register the `PaymentSuccessfulEvent` class.

```dart 
import 'package:flutter_app/app/events/payment_successful_event.dart';

final Map<Type, NyEvent> events = {
  LoginEvent: LoginEvent(),
  LogoutEvent: LogoutEvent(),
  PaymentSuccessfulEvent: PaymentSuccessfulEvent()
};
```

Now we can dispatch the event from our application when a user makes a purchase.

```dart
stripePay(List<Product> products) async {

  // create the order
  Order? order = await api<OrderApiService>((api) => api.createOrder(products));
  if (order == null) {
    return;
  }

  // send event
  await event<PaymentSuccessfulEvent>(data: {'order': order});
...
```

This is all we need to use our new event.

<a name="dispatching-events"></a>
<br>

## Dispatching Events

You can dispatch events by calling the below method:

```dart
loginUser() async {
    User user = await _apiService.loginUser('email': '...', 'password': '...');

    event<LoginEvent>(data: {'user': user});
}
```

This will dispatch the `LoginEvent` event.

> It's important that your `LoginEvent` class is registered in your <b>config/events.dart</b> file.

<a name="setup-listeners"></a>
<br>

## Setup listeners

Listeners are added to events. Your listener must extend the NyListener class and have a `handle` method to work.

<!-- You can add/remove listeners from events. -->

Here's an example.

```dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginEvent implements NyEvent {

  final listeners = {
    DefaultListener: DefaultListener(),
    AuthUserListener: AuthUserListener(),
  };
}

class DefaultListener extends NyListener {
  handle(dynamic event) async {
    // handle the payload from event
  }
}

class AuthUserListener extends NyListener {
  handle(dynamic event) async {
    // handle the payload from event
  }
}
```

<a name="listeners-handle-method"></a>
<br>

## Listener's handle method

The `handle(dynamic event)` method is where you can add all your logic for the listener.

The `event` argument in the `handle` will contain any data from the event, below is an example.

```dart
// From your widget
loginUser() async {
    User user = await _apiService.loginUser('email': '...', 'password': '...');

    event<LoginEvent>({'user': user});
}

// Login event file
...
class LoginEvent implements NyEvent {

  final listeners = {
    DefaultListener: DefaultListener(),
  };
}

class DefaultListener extends NyListener {
  handle(dynamic event) async {
    print(event['user']); // instance of User
  }
}
```
