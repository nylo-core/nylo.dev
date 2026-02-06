# Eventi

---

<a name="section-1"></a>
- [Introduzione](#introduction)
  - [Capire gli Eventi](#understanding-events)
  - [Esempi Comuni di Eventi](#common-event-examples)
- [Creare un Evento](#creating-an-event)
  - [Struttura dell'Evento](#event-structure)
- [Inviare Eventi](#dispatching-events)
  - [Invio Base di un Evento](#basic-event-dispatch)
  - [Invio con Dati](#dispatching-with-data)
  - [Broadcasting degli Eventi](#broadcasting-events)
- [Ascoltare gli Eventi](#listening-to-events)
  - [Utilizzare l'Helper `listenOn`](#using-the-listenon-helper)
  - [Utilizzare l'Helper `listen`](#using-the-listen-helper)
  - [Annullare l'Iscrizione dagli Eventi](#unsubscribing-from-events)
- [Lavorare con i Listener](#working-with-listeners)
  - [Aggiungere Listener Multipli](#adding-multiple-listeners)
  - [Implementare la Logica del Listener](#implementing-listener-logic)
- [Broadcasting Globale degli Eventi](#global-event-broadcasting)
  - [Abilitare il Broadcasting Globale](#enabling-global-broadcasting)

<div id="introduction"></div>

## Introduzione

Gli eventi sono potenti quando hai bisogno di gestire la logica dopo che qualcosa accade nella tua applicazione. Il sistema di eventi di Nylo ti permette di creare, inviare e ascoltare eventi da qualsiasi punto della tua applicazione, rendendo piu' facile costruire applicazioni Flutter reattive e guidate dagli eventi.

<div id="understanding-events"></div>

### Capire gli Eventi

La programmazione guidata dagli eventi e' un paradigma in cui il flusso della tua applicazione e' determinato da eventi come azioni dell'utente, output dei sensori o messaggi da altri programmi o thread. Questo approccio aiuta a disaccoppiare le diverse parti della tua applicazione, rendendo il codice piu' manutenibile e facile da comprendere.

<div id="common-event-examples"></div>

### Esempi Comuni di Eventi

Ecco alcuni eventi tipici che la tua applicazione potrebbe utilizzare:
- Registrazione utente completata
- Utente connesso/disconnesso
- Prodotto aggiunto al carrello
- Pagamento elaborato con successo
- Sincronizzazione dati completata
- Notifica push ricevuta

<div id="creating-an-event"></div>

## Creare un Evento

Puoi creare un nuovo evento utilizzando la CLI del framework Nylo o Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

Dopo aver eseguito il comando, un nuovo file evento verra' creato nella directory `app/events/`.

<div id="event-structure"></div>

### Struttura dell'Evento

Ecco la struttura di un file evento appena creato (es. `app/events/payment_successful_event.dart`):

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

## Inviare Eventi

Gli eventi possono essere inviati da qualsiasi punto della tua applicazione utilizzando il metodo helper `event`.

<div id="basic-event-dispatch"></div>

### Invio Base di un Evento

Per inviare un evento senza dati:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Invio con Dati

Per passare dati insieme al tuo evento:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Broadcasting degli Eventi

Per impostazione predefinita, gli eventi Nylo vengono gestiti solo dai listener definiti nella classe evento. Per fare il broadcast di un evento (rendendolo disponibile ai listener esterni), usa il parametro `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Ascoltare gli Eventi

Nylo fornisce diversi modi per ascoltare e rispondere agli eventi.

<div id="using-the-listenon-helper"></div>

### Utilizzare l'Helper `listenOn`

L'helper `listenOn` puo' essere usato ovunque nella tua applicazione per ascoltare eventi in broadcast:

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

### Utilizzare l'Helper `listen`

L'helper `listen` e' disponibile nelle classi `NyPage` e `NyState`. Gestisce automaticamente le sottoscrizioni, annullando l'iscrizione quando il widget viene eliminato:

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

### Annullare l'Iscrizione dagli Eventi

Quando usi `listenOn`, devi annullare manualmente l'iscrizione per prevenire perdite di memoria:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

L'helper `listen` gestisce automaticamente l'annullamento dell'iscrizione quando il widget viene eliminato.

<div id="working-with-listeners"></div>

## Lavorare con i Listener

I listener sono classi che rispondono agli eventi. Ogni evento puo' avere listener multipli per gestire diversi aspetti dell'evento.

<div id="adding-multiple-listeners"></div>

### Aggiungere Listener Multipli

Puoi aggiungere listener multipli al tuo evento aggiornando la proprieta' `listeners`:

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

### Implementare la Logica del Listener

Ogni listener dovrebbe implementare il metodo `handle` per elaborare l'evento:

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

## Broadcasting Globale degli Eventi

Se vuoi che tutti gli eventi vengano trasmessi automaticamente senza specificare `broadcast: true` ogni volta, puoi abilitare il broadcasting globale.

<div id="enabling-global-broadcasting"></div>

### Abilitare il Broadcasting Globale

Modifica il tuo file `app/providers/app_provider.dart` e aggiungi il metodo `broadcastEvents()` alla tua istanza Nylo:

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

Con il broadcasting globale abilitato, puoi inviare e ascoltare eventi in modo piu' conciso:

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
