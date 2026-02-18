# Events

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
  - [Zrozumienie zdarzeń](#understanding-events "Zrozumienie zdarzeń")
  - [Typowe przykłady zdarzeń](#common-event-examples "Typowe przykłady zdarzeń")
- [Tworzenie zdarzenia](#creating-an-event "Tworzenie zdarzenia")
  - [Struktura zdarzenia](#event-structure "Struktura zdarzenia")
- [Wysyłanie zdarzeń](#dispatching-events "Wysyłanie zdarzeń")
  - [Podstawowe wysyłanie zdarzenia](#basic-event-dispatch "Podstawowe wysyłanie zdarzenia")
  - [Wysyłanie z danymi](#dispatching-with-data "Wysyłanie z danymi")
  - [Rozgłaszanie zdarzeń](#broadcasting-events "Rozgłaszanie zdarzeń")
- [Nasłuchiwanie zdarzeń](#listening-to-events "Nasłuchiwanie zdarzeń")
  - [Użycie helpera `listenOn`](#using-the-listenon-helper "Użycie helpera listenOn")
  - [Użycie helpera `listen`](#using-the-listen-helper "Użycie helpera listen")
  - [Anulowanie subskrypcji zdarzeń](#unsubscribing-from-events "Anulowanie subskrypcji zdarzeń")
- [Praca z listenerami](#working-with-listeners "Praca z listenerami")
  - [Dodawanie wielu listenerów](#adding-multiple-listeners "Dodawanie wielu listenerów")
  - [Implementacja logiki listenera](#implementing-listener-logic "Implementacja logiki listenera")
- [Globalne rozgłaszanie zdarzeń](#global-event-broadcasting "Globalne rozgłaszanie zdarzeń")
  - [Włączanie globalnego rozgłaszania](#enabling-global-broadcasting "Włączanie globalnego rozgłaszania")

<div id="introduction"></div>

## Wprowadzenie

Zdarzenia są przydatne, gdy musisz obsłużyć logikę po tym, jak coś wystąpi w Twojej aplikacji. System zdarzeń Nylo pozwala tworzyć, wysyłać i nasłuchiwać zdarzeń z dowolnego miejsca w aplikacji, co ułatwia budowanie responsywnych, sterowanych zdarzeniami aplikacji Flutter.

<div id="understanding-events"></div>

### Zrozumienie zdarzeń

Programowanie sterowane zdarzeniami to paradygmat, w którym przepływ aplikacji jest określany przez zdarzenia, takie jak akcje użytkownika, dane z czujników lub wiadomości z innych programów lub wątków. Takie podejście pomaga oddzielić różne części aplikacji, czyniąc kod łatwiejszym w utrzymaniu i zrozumieniu.

<div id="common-event-examples"></div>

### Typowe przykłady zdarzeń

Oto kilka typowych zdarzeń, które Twoja aplikacja może wykorzystywać:
- Zakończenie rejestracji użytkownika
- Logowanie/wylogowanie użytkownika
- Dodanie produktu do koszyka
- Pomyślne przetworzenie płatności
- Zakończenie synchronizacji danych
- Otrzymanie powiadomienia push

<div id="creating-an-event"></div>

## Tworzenie zdarzenia

Możesz utworzyć nowe zdarzenie za pomocą CLI frameworka Nylo lub Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

Po uruchomieniu polecenia nowy plik zdarzenia zostanie utworzony w katalogu `app/events/`.

<div id="event-structure"></div>

### Struktura zdarzenia

Oto struktura nowo utworzonego pliku zdarzenia (np. `app/events/payment_successful_event.dart`):

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

## Wysyłanie zdarzeń

Zdarzenia mogą być wysyłane z dowolnego miejsca w aplikacji za pomocą metody pomocniczej `event`.

<div id="basic-event-dispatch"></div>

### Podstawowe wysyłanie zdarzenia

Aby wysłać zdarzenie bez danych:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Wysyłanie z danymi

Aby przekazać dane wraz ze zdarzeniem:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Rozgłaszanie zdarzeń

Domyślnie zdarzenia Nylo są obsługiwane tylko przez listenery zdefiniowane w klasie zdarzenia. Aby rozgłosić zdarzenie (udostępniając je zewnętrznym listenerom), użyj parametru `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Nasłuchiwanie zdarzeń

Nylo oferuje wiele sposobów nasłuchiwania i reagowania na zdarzenia.

<div id="using-the-listenon-helper"></div>

### Użycie helpera `listenOn`

Helper `listenOn` może być używany w dowolnym miejscu aplikacji do nasłuchiwania rozgłaszanych zdarzeń:

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

### Użycie helpera `listen`

Helper `listen` jest dostępny w klasach `NyPage` i `NyState`. Automatycznie zarządza subskrypcjami, anulując je po usunięciu widgetu:

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

### Anulowanie subskrypcji zdarzeń

Przy użyciu `listenOn` musisz ręcznie anulować subskrypcję, aby zapobiec wyciekom pamięci:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

Helper `listen` automatycznie obsługuje anulowanie subskrypcji po usunięciu widgetu.

<div id="working-with-listeners"></div>

## Praca z listenerami

Listenery to klasy, które reagują na zdarzenia. Każde zdarzenie może mieć wiele listenerów do obsługi różnych aspektów zdarzenia.

<div id="adding-multiple-listeners"></div>

### Dodawanie wielu listenerów

Możesz dodać wiele listenerów do zdarzenia, aktualizując właściwość `listeners`:

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

### Implementacja logiki listenera

Każdy listener powinien implementować metodę `handle` do przetwarzania zdarzenia:

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

## Globalne rozgłaszanie zdarzeń

Jeśli chcesz, aby wszystkie zdarzenia były automatycznie rozgłaszane bez konieczności określania `broadcast: true` za każdym razem, możesz włączyć globalne rozgłaszanie.

<div id="enabling-global-broadcasting"></div>

### Włączanie globalnego rozgłaszania

Edytuj plik `app/providers/app_provider.dart` i dodaj metodę `broadcastEvents()` do instancji Nylo:

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

Po włączeniu globalnego rozgłaszania możesz wysyłać i nasłuchiwać zdarzeń bardziej zwięźle:

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
