# Events

---

<a name="section-1"></a>
- [Introducao](#introduction)
  - [Entendendo Eventos](#understanding-events)
  - [Exemplos Comuns de Eventos](#common-event-examples)
- [Criando um Evento](#creating-an-event)
  - [Estrutura do Evento](#event-structure)
- [Disparando Eventos](#dispatching-events)
  - [Disparo Basico de Evento](#basic-event-dispatch)
  - [Disparando com Dados](#dispatching-with-data)
  - [Transmitindo Eventos](#broadcasting-events)
- [Ouvindo Eventos](#listening-to-events)
  - [Usando o Helper `listenOn`](#using-the-listenon-helper)
  - [Usando o Helper `listen`](#using-the-listen-helper)
  - [Cancelando a Inscricao em Eventos](#unsubscribing-from-events)
- [Trabalhando com Listeners](#working-with-listeners)
  - [Adicionando Multiplos Listeners](#adding-multiple-listeners)
  - [Implementando Logica do Listener](#implementing-listener-logic)
- [Transmissao Global de Eventos](#global-event-broadcasting)
  - [Habilitando a Transmissao Global](#enabling-global-broadcasting)

<div id="introduction"></div>

## Introducao

Eventos sao poderosos quando voce precisa lidar com logica apos algo ocorrer na sua aplicacao. O sistema de eventos do Nylo permite criar, disparar e ouvir eventos de qualquer lugar da sua aplicacao, facilitando a construcao de aplicacoes Flutter responsivas e orientadas a eventos.

<div id="understanding-events"></div>

### Entendendo Eventos

Programacao orientada a eventos e um paradigma onde o fluxo da sua aplicacao e determinado por eventos como acoes do usuario, saidas de sensores ou mensagens de outros programas ou threads. Essa abordagem ajuda a desacoplar diferentes partes da sua aplicacao, tornando seu codigo mais sustentavel e facil de entender.

<div id="common-event-examples"></div>

### Exemplos Comuns de Eventos

Aqui estao alguns eventos tipicos que sua aplicacao pode usar:
- Registro de usuario concluido
- Usuario fez login/logout
- Produto adicionado ao carrinho
- Pagamento processado com sucesso
- Sincronizacao de dados concluida
- Notificacao push recebida

<div id="creating-an-event"></div>

## Criando um Evento

Voce pode criar um novo evento usando o CLI do framework Nylo ou o Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

Apos executar o comando, um novo arquivo de evento sera criado no diretorio `app/events/`.

<div id="event-structure"></div>

### Estrutura do Evento

Aqui esta a estrutura de um arquivo de evento recem-criado (ex: `app/events/payment_successful_event.dart`):

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

## Disparando Eventos

Eventos podem ser disparados de qualquer lugar da sua aplicacao usando o metodo helper `event`.

<div id="basic-event-dispatch"></div>

### Disparo Basico de Evento

Para disparar um evento sem dados:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Disparando com Dados

Para passar dados junto com seu evento:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Transmitindo Eventos

Por padrao, eventos do Nylo sao tratados apenas pelos listeners definidos na classe do evento. Para transmitir um evento (tornando-o disponivel para listeners externos), use o parametro `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Ouvindo Eventos

O Nylo fornece multiplas formas de ouvir e responder a eventos.

<div id="using-the-listenon-helper"></div>

### Usando o Helper `listenOn`

O helper `listenOn` pode ser usado em qualquer lugar da sua aplicacao para ouvir eventos transmitidos:

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

### Usando o Helper `listen`

O helper `listen` esta disponivel nas classes `NyPage` e `NyState`. Ele gerencia automaticamente as inscricoes, cancelando quando o widget e descartado:

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

### Cancelando a Inscricao em Eventos

Ao usar `listenOn`, voce deve cancelar manualmente a inscricao para evitar vazamentos de memoria:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

O helper `listen` lida automaticamente com o cancelamento da inscricao quando o widget e descartado.

<div id="working-with-listeners"></div>

## Trabalhando com Listeners

Listeners sao classes que respondem a eventos. Cada evento pode ter multiplos listeners para lidar com diferentes aspectos do evento.

<div id="adding-multiple-listeners"></div>

### Adicionando Multiplos Listeners

Voce pode adicionar multiplos listeners ao seu evento atualizando a propriedade `listeners`:

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

### Implementando Logica do Listener

Cada listener deve implementar o metodo `handle` para processar o evento:

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

## Transmissao Global de Eventos

Se voce quiser que todos os eventos sejam transmitidos automaticamente sem especificar `broadcast: true` a cada vez, voce pode habilitar a transmissao global.

<div id="enabling-global-broadcasting"></div>

### Habilitando a Transmissao Global

Edite seu arquivo `app/providers/app_provider.dart` e adicione o metodo `broadcastEvents()` a sua instancia do Nylo:

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

Com a transmissao global habilitada, voce pode disparar e ouvir eventos de forma mais concisa:

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
