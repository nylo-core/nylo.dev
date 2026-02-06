# Events

---

<a name="section-1"></a>
- [Einleitung](#introduction)
  - [Events verstehen](#understanding-events)
  - [Gängige Event-Beispiele](#common-event-examples)
- [Ein Event erstellen](#creating-an-event)
  - [Event-Struktur](#event-structure)
- [Events auslösen](#dispatching-events)
  - [Einfaches Event auslösen](#basic-event-dispatch)
  - [Auslösen mit Daten](#dispatching-with-data)
  - [Events broadcasten](#broadcasting-events)
- [Auf Events lauschen](#listening-to-events)
  - [Den `listenOn`-Helfer verwenden](#using-the-listenon-helper)
  - [Den `listen`-Helfer verwenden](#using-the-listen-helper)
  - [Events abbestellen](#unsubscribing-from-events)
- [Mit Listenern arbeiten](#working-with-listeners)
  - [Mehrere Listener hinzufügen](#adding-multiple-listeners)
  - [Listener-Logik implementieren](#implementing-listener-logic)
- [Globales Event-Broadcasting](#global-event-broadcasting)
  - [Globales Broadcasting aktivieren](#enabling-global-broadcasting)

<div id="introduction"></div>

## Einleitung

Events sind leistungsstark, wenn Sie Logik verarbeiten müssen, nachdem etwas in Ihrer Anwendung passiert ist. Das Event-System von Nylo ermöglicht es Ihnen, Events von überall in Ihrer Anwendung zu erstellen, auszulösen und auf sie zu lauschen, was den Aufbau reaktiver, eventgesteuerter Flutter-Anwendungen erleichtert.

<div id="understanding-events"></div>

### Events verstehen

Eventgesteuerte Programmierung ist ein Paradigma, bei dem der Ablauf Ihrer Anwendung durch Events wie Benutzeraktionen, Sensorausgaben oder Nachrichten von anderen Programmen oder Threads bestimmt wird. Dieser Ansatz hilft, verschiedene Teile Ihrer Anwendung zu entkoppeln, wodurch Ihr Code wartbarer und leichter nachvollziehbar wird.

<div id="common-event-examples"></div>

### Gängige Event-Beispiele

Hier sind einige typische Events, die Ihre Anwendung verwenden könnte:
- Benutzerregistrierung abgeschlossen
- Benutzer angemeldet/abgemeldet
- Produkt zum Warenkorb hinzugefügt
- Zahlung erfolgreich verarbeitet
- Datensynchronisierung abgeschlossen
- Push-Benachrichtigung empfangen

<div id="creating-an-event"></div>

## Ein Event erstellen

Sie können ein neues Event entweder mit der Nylo-Framework-CLI oder Metro erstellen:

```bash
metro make:event PaymentSuccessfulEvent
```

Nach Ausführung des Befehls wird eine neue Event-Datei im Verzeichnis `app/events/` erstellt.

<div id="event-structure"></div>

### Event-Struktur

Hier ist die Struktur einer neu erstellten Event-Datei (z.B. `app/events/payment_successful_event.dart`):

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

## Events auslösen

Events können von überall in Ihrer Anwendung mit der `event`-Hilfsmethode ausgelöst werden.

<div id="basic-event-dispatch"></div>

### Einfaches Event auslösen

Um ein Event ohne Daten auszulösen:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Auslösen mit Daten

Um Daten zusammen mit Ihrem Event zu übergeben:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Events broadcasten

Standardmäßig werden Nylo-Events nur von den in der Event-Klasse definierten Listenern verarbeitet. Um ein Event zu broadcasten (es für externe Listener verfügbar zu machen), verwenden Sie den Parameter `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Auf Events lauschen

Nylo bietet mehrere Möglichkeiten, auf Events zu lauschen und darauf zu reagieren.

<div id="using-the-listenon-helper"></div>

### Den `listenOn`-Helfer verwenden

Der `listenOn`-Helfer kann überall in Ihrer Anwendung verwendet werden, um auf gebroadcastete Events zu lauschen:

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

### Den `listen`-Helfer verwenden

Der `listen`-Helfer ist in `NyPage`- und `NyState`-Klassen verfügbar. Er verwaltet Abonnements automatisch und meldet sich ab, wenn das Widget disposed wird:

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

### Events abbestellen

Bei Verwendung von `listenOn` müssen Sie sich manuell abmelden, um Speicherlecks zu vermeiden:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

Der `listen`-Helfer behandelt die Abmeldung automatisch, wenn das Widget disposed wird.

<div id="working-with-listeners"></div>

## Mit Listenern arbeiten

Listener sind Klassen, die auf Events reagieren. Jedes Event kann mehrere Listener haben, um verschiedene Aspekte des Events zu verarbeiten.

<div id="adding-multiple-listeners"></div>

### Mehrere Listener hinzufügen

Sie können mehrere Listener zu Ihrem Event hinzufügen, indem Sie die `listeners`-Eigenschaft aktualisieren:

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

### Listener-Logik implementieren

Jeder Listener sollte die `handle`-Methode implementieren, um das Event zu verarbeiten:

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

## Globales Event-Broadcasting

Wenn Sie möchten, dass alle Events automatisch gebroadcastet werden, ohne jedes Mal `broadcast: true` angeben zu müssen, können Sie globales Broadcasting aktivieren.

<div id="enabling-global-broadcasting"></div>

### Globales Broadcasting aktivieren

Bearbeiten Sie Ihre Datei `app/providers/app_provider.dart` und fügen Sie die Methode `broadcastEvents()` zu Ihrer Nylo-Instanz hinzu:

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

Mit aktiviertem globalem Broadcasting können Sie Events kompakter auslösen und auf sie lauschen:

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
