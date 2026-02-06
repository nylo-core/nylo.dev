# Evenements

---

<a name="section-1"></a>
- [Introduction](#introduction)
  - [Comprendre les evenements](#understanding-events)
  - [Exemples courants d'evenements](#common-event-examples)
- [Creer un evenement](#creating-an-event)
  - [Structure d'un evenement](#event-structure)
- [Dispatcher des evenements](#dispatching-events)
  - [Dispatch basique d'un evenement](#basic-event-dispatch)
  - [Dispatcher avec des donnees](#dispatching-with-data)
  - [Diffuser des evenements](#broadcasting-events)
- [Ecouter les evenements](#listening-to-events)
  - [Utiliser le helper `listenOn`](#using-the-listenon-helper)
  - [Utiliser le helper `listen`](#using-the-listen-helper)
  - [Se desabonner des evenements](#unsubscribing-from-events)
- [Travailler avec les ecouteurs](#working-with-listeners)
  - [Ajouter plusieurs ecouteurs](#adding-multiple-listeners)
  - [Implementer la logique d'un ecouteur](#implementing-listener-logic)
- [Diffusion globale d'evenements](#global-event-broadcasting)
  - [Activer la diffusion globale](#enabling-global-broadcasting)

<div id="introduction"></div>

## Introduction

Les evenements sont puissants lorsque vous avez besoin de gerer une logique apres qu'un evenement se produit dans votre application. Le systeme d'evenements de Nylo vous permet de creer, dispatcher et ecouter des evenements depuis n'importe ou dans votre application, facilitant ainsi la construction d'applications Flutter reactives et pilotees par evenements.

<div id="understanding-events"></div>

### Comprendre les evenements

La programmation evenementielle est un paradigme ou le flux de votre application est determine par des evenements tels que les actions de l'utilisateur, les sorties de capteurs ou les messages d'autres programmes ou threads. Cette approche aide a decoupler les differentes parties de votre application, rendant votre code plus maintenable et plus facile a raisonner.

<div id="common-event-examples"></div>

### Exemples courants d'evenements

Voici quelques evenements typiques que votre application pourrait utiliser :
- Inscription d'un utilisateur terminee
- Connexion/deconnexion de l'utilisateur
- Produit ajoute au panier
- Paiement traite avec succes
- Synchronisation des donnees terminee
- Notification push recue

<div id="creating-an-event"></div>

## Creer un evenement

Vous pouvez creer un nouvel evenement en utilisant soit le CLI du framework Nylo, soit Metro :

```bash
metro make:event PaymentSuccessfulEvent
```

Apres avoir execute la commande, un nouveau fichier d'evenement sera cree dans le repertoire `app/events/`.

<div id="event-structure"></div>

### Structure d'un evenement

Voici la structure d'un fichier d'evenement nouvellement cree (par exemple, `app/events/payment_successful_event.dart`) :

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

## Dispatcher des evenements

Les evenements peuvent etre dispatches depuis n'importe ou dans votre application en utilisant la methode helper `event`.

<div id="basic-event-dispatch"></div>

### Dispatch basique d'un evenement

Pour dispatcher un evenement sans donnees :

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Dispatcher avec des donnees

Pour passer des donnees avec votre evenement :

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Diffuser des evenements

Par defaut, les evenements Nylo ne sont geres que par les ecouteurs definis dans la classe d'evenement. Pour diffuser un evenement (le rendre disponible aux ecouteurs externes), utilisez le parametre `broadcast` :

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Ecouter les evenements

Nylo fournit plusieurs facons d'ecouter et de reagir aux evenements.

<div id="using-the-listenon-helper"></div>

### Utiliser le helper `listenOn`

Le helper `listenOn` peut etre utilise n'importe ou dans votre application pour ecouter les evenements diffuses :

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

### Utiliser le helper `listen`

Le helper `listen` est disponible dans les classes `NyPage` et `NyState`. Il gere automatiquement les abonnements, se desabonnant lorsque le widget est dispose :

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

### Se desabonner des evenements

Lorsque vous utilisez `listenOn`, vous devez vous desabonner manuellement pour eviter les fuites de memoire :

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

Le helper `listen` gere automatiquement le desabonnement lorsque le widget est dispose.

<div id="working-with-listeners"></div>

## Travailler avec les ecouteurs

Les ecouteurs sont des classes qui reagissent aux evenements. Chaque evenement peut avoir plusieurs ecouteurs pour gerer differents aspects de l'evenement.

<div id="adding-multiple-listeners"></div>

### Ajouter plusieurs ecouteurs

Vous pouvez ajouter plusieurs ecouteurs a votre evenement en mettant a jour la propriete `listeners` :

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

### Implementer la logique d'un ecouteur

Chaque ecouteur doit implementer la methode `handle` pour traiter l'evenement :

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

## Diffusion globale d'evenements

Si vous souhaitez que tous les evenements soient diffuses automatiquement sans specifier `broadcast: true` a chaque fois, vous pouvez activer la diffusion globale.

<div id="enabling-global-broadcasting"></div>

### Activer la diffusion globale

Editez votre fichier `app/providers/app_provider.dart` et ajoutez la methode `broadcastEvents()` a votre instance Nylo :

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

Avec la diffusion globale activee, vous pouvez dispatcher et ecouter les evenements de maniere plus concise :

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
