# Events

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
  - [Entender los eventos](#understanding-events "Entender los eventos")
  - [Ejemplos comunes de eventos](#common-event-examples "Ejemplos comunes de eventos")
- [Crear un evento](#creating-an-event "Crear un evento")
  - [Estructura del evento](#event-structure "Estructura del evento")
- [Despachar eventos](#dispatching-events "Despachar eventos")
  - [Despacho basico de eventos](#basic-event-dispatch "Despacho basico de eventos")
  - [Despachar con datos](#dispatching-with-data "Despachar con datos")
  - [Transmitir eventos](#broadcasting-events "Transmitir eventos")
- [Escuchar eventos](#listening-to-events "Escuchar eventos")
  - [Usar el helper `listenOn`](#using-the-listenon-helper "Usar el helper listenOn")
  - [Usar el helper `listen`](#using-the-listen-helper "Usar el helper listen")
  - [Cancelar suscripcion de eventos](#unsubscribing-from-events "Cancelar suscripcion de eventos")
- [Trabajar con listeners](#working-with-listeners "Trabajar con listeners")
  - [Agregar multiples listeners](#adding-multiple-listeners "Agregar multiples listeners")
  - [Implementar logica de listeners](#implementing-listener-logic "Implementar logica de listeners")
- [Transmision global de eventos](#global-event-broadcasting "Transmision global de eventos")
  - [Habilitar la transmision global](#enabling-global-broadcasting "Habilitar la transmision global")

<div id="introduction"></div>

## Introduccion

Los eventos son poderosos cuando necesitas manejar logica despues de que algo ocurre en tu aplicacion. El sistema de eventos de Nylo te permite crear, despachar y escuchar eventos desde cualquier lugar de tu aplicacion, facilitando la construccion de aplicaciones Flutter reactivas y basadas en eventos.

<div id="understanding-events"></div>

### Entender los eventos

La programacion basada en eventos es un paradigma donde el flujo de tu aplicacion esta determinado por eventos como acciones del usuario, salidas de sensores o mensajes de otros programas o hilos. Este enfoque ayuda a desacoplar diferentes partes de tu aplicacion, haciendo tu codigo mas mantenible y facil de razonar.

<div id="common-event-examples"></div>

### Ejemplos comunes de eventos

Aqui hay algunos eventos tipicos que tu aplicacion podria usar:
- Registro de usuario completado
- Usuario inicio/cerro sesion
- Producto agregado al carrito
- Pago procesado exitosamente
- Sincronizacion de datos completada
- Notificacion push recibida

<div id="creating-an-event"></div>

## Crear un evento

Puedes crear un nuevo evento usando la CLI del framework Nylo o Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

Despues de ejecutar el comando, se creara un nuevo archivo de evento en el directorio `app/events/`.

<div id="event-structure"></div>

### Estructura del evento

Aqui esta la estructura de un archivo de evento recien creado (ej. `app/events/payment_successful_event.dart`):

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

## Despachar eventos

Los eventos se pueden despachar desde cualquier lugar de tu aplicacion usando el metodo auxiliar `event`.

<div id="basic-event-dispatch"></div>

### Despacho basico de eventos

Para despachar un evento sin datos:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Despachar con datos

Para pasar datos junto con tu evento:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Transmitir eventos

Por defecto, los eventos de Nylo solo son manejados por los listeners definidos en la clase del evento. Para transmitir un evento (haciendolo disponible para listeners externos), usa el parametro `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Escuchar eventos

Nylo proporciona multiples formas de escuchar y responder a eventos.

<div id="using-the-listenon-helper"></div>

### Usar el helper `listenOn`

El helper `listenOn` se puede usar en cualquier lugar de tu aplicacion para escuchar eventos transmitidos:

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

### Usar el helper `listen`

El helper `listen` esta disponible en clases `NyPage` y `NyState`. Gestiona automaticamente las suscripciones, cancelando la suscripcion cuando el widget se destruye:

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

### Cancelar suscripcion de eventos

Cuando usas `listenOn`, debes cancelar manualmente la suscripcion para evitar fugas de memoria:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

El helper `listen` maneja automaticamente la cancelacion de suscripcion cuando el widget se destruye.

<div id="working-with-listeners"></div>

## Trabajar con listeners

Los listeners son clases que responden a eventos. Cada evento puede tener multiples listeners para manejar diferentes aspectos del evento.

<div id="adding-multiple-listeners"></div>

### Agregar multiples listeners

Puedes agregar multiples listeners a tu evento actualizando la propiedad `listeners`:

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

### Implementar logica de listeners

Cada listener debe implementar el metodo `handle` para procesar el evento:

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

## Transmision global de eventos

Si quieres que todos los eventos se transmitan automaticamente sin especificar `broadcast: true` cada vez, puedes habilitar la transmision global.

<div id="enabling-global-broadcasting"></div>

### Habilitar la transmision global

Edita tu archivo `app/providers/app_provider.dart` y agrega el metodo `broadcastEvents()` a tu instancia de Nylo:

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

Con la transmision global habilitada, puedes despachar y escuchar eventos de forma mas concisa:

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
