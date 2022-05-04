# Events

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
  - [Understanding Events](#understanding-events "Understanding Events")
- [Practical-example](#practical-example "Practical example")
- Usage
  - [Dispatching Events](#dispatching-events "Dispatching Events")
  - [Setup Listeners](#setup-listeners "Setup Listeners")
  - [Listeners Handle Method](#listeners-handle-method "Listeners Handle Method")

<a name="introduction"></a>
<br>

## Introduction

Events are powerful when you need your application to handle tasks, jobs or logic. Events can be dispatched when something happens in your application.
Nylo provides a simple implementation of events that allows you to call listeners registered to the event after it's been dispatched. 

> Nylo events are managed in `app/events/` directory.

<a name="understanding-events"></a>
<br>

## Understanding Events

Events, also known as 'Event-driven programming' is a programming paradigm in which the flow of the program is determined by events such as user actions, sensor outputs, or messages passing from other programs or threads.

### Examples of events

Here are some examples of events your application might have:
- User Registers
- User login
- Creates a new chat
- Sends a friend request
- A user makes a payment

In Nylo, after creating an Event, you can add your listeners to that event that should handle your desired needs.

If we use the last example "A user makes a payment", we might have to do the following things:

1. Create the order via an API
2. Use a service like [Google tag manager](https://pub.dev/packages/google_tag_manager) to log the event's data

In the next section, we'll do a practical example for how to do this.

<a name="practical-example"></a>
<br>

## Practical Example

In this practical example, we'll use a senario when a user creates a new order 

```dart
flutter pub run nylo_framework:main make:event process_order_event
```

<a name="dispatching-events"></a>
<br>

## Dispatching Events

You can dispatch events by calling the below method:

```dart
loginUser() async {
    User user = await _apiService.loginUser('email': '...', 'password': '...');

    event<LoginEvent>({'user': user});
}
```

This will dispatch the LoginEvent event.

<a name="setup-listeners"></a>
<br>

## Setup listeners

Inside your event, you can add/remove listeners that you want the event to fire.

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

## Listeners handle method

The `handle(dynamic event)` method is where you can add all your logic for the listener.

The `event` argument in `handle()` will contain any data from the event, below is an example.

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
