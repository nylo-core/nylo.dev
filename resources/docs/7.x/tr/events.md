# Olaylar

---

<a name="section-1"></a>
- [Giris](#introduction)
  - [Olaylari Anlamak](#understanding-events)
  - [Yaygin Olay Örnekleri](#common-event-examples)
- [Olay Olusturma](#creating-an-event)
  - [Olay Yapisi](#event-structure)
- [Olaylari Tetikleme](#dispatching-events)
  - [Temel Olay Tetikleme](#basic-event-dispatch)
  - [Veri ile Tetikleme](#dispatching-with-data)
  - [Olaylari Yayinlama](#broadcasting-events)
- [Olaylari Dinleme](#listening-to-events)
  - [`listenOn` Yardimcisini Kullanma](#using-the-listenon-helper)
  - [`listen` Yardimcisini Kullanma](#using-the-listen-helper)
  - [Olay Aboneligini Iptal Etme](#unsubscribing-from-events)
- [Dinleyicilerle Çalisma](#working-with-listeners)
  - [Birden Fazla Dinleyici Ekleme](#adding-multiple-listeners)
  - [Dinleyici Mantigini Uygulama](#implementing-listener-logic)
- [Global Olay Yayini](#global-event-broadcasting)
  - [Global Yayini Etkinlestirme](#enabling-global-broadcasting)

<div id="introduction"></div>

## Giris

Olaylar, uygulamanizda bir sey gerçeklestikten sonra mantik islemeniz gerektiginde güçlüdür. Nylo'nun olay sistemi, uygulamanizin herhangi bir yerinden olaylar olusturmaniza, tetiklemenize ve dinlemenize olanak taniyarak, duyarli ve olay odakli Flutter uygulamalari olusturmayi kolaylastirir.

<div id="understanding-events"></div>

### Olaylari Anlamak

Olay odakli programlama, uygulamanizin akisinin kullanici eylemleri, sensör çiktilari veya diger programlardan ya da is parçaciklarindan gelen mesajlar gibi olaylar tarafindan belirlendigi bir paradigmadir. Bu yaklasim, uygulamanizin farkli bölümlerini birbirinden ayirmaya yardimci olarak kodunuzu daha sürdürülebilir ve anlasilir hale getirir.

<div id="common-event-examples"></div>

### Yaygin Olay Örnekleri

Uygulamanizda kullanabileceginiz bazi tipik olaylar:
- Kullanici kaydi tamamlandi
- Kullanici giris yapti/çikis yapti
- Ürün sepete eklendi
- Ödeme basariyla islendi
- Veri senkronizasyonu tamamlandi
- Push bildirimi alindi

<div id="creating-an-event"></div>

## Olay Olusturma

Nylo framework CLI veya Metro kullanarak yeni bir olay olusturabilirsiniz:

```bash
metro make:event PaymentSuccessfulEvent
```

Komutu çalistirdiktan sonra, `app/events/` dizininde yeni bir olay dosyasi olusturulacaktir.

<div id="event-structure"></div>

### Olay Yapisi

Yeni olusturulan bir olay dosyasinin yapisi söyledir (örn. `app/events/payment_successful_event.dart`):

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

## Olaylari Tetikleme

Olaylar, `event` yardimci metodu kullanilarak uygulamanizin herhangi bir yerinden tetiklenebilir.

<div id="basic-event-dispatch"></div>

### Temel Olay Tetikleme

Veri olmadan bir olay tetiklemek için:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Veri ile Tetikleme

Olayinizla birlikte veri göndermek için:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Olaylari Yayinlama

Varsayilan olarak, Nylo olaylari yalnizca olay sinifinda tanimlanan dinleyiciler tarafindan islenir. Bir olayi yayinlamak (harici dinleyicilere açik hale getirmek) için `broadcast` parametresini kullanin:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Olaylari Dinleme

Nylo, olaylari dinlemek ve yanit vermek için birden fazla yol sunar.

<div id="using-the-listenon-helper"></div>

### `listenOn` Yardimcisini Kullanma

`listenOn` yardimcisi, yayinlanan olaylari dinlemek için uygulamanizin herhangi bir yerinde kullanilabilir:

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

### `listen` Yardimcisini Kullanma

`listen` yardimcisi `NyPage` ve `NyState` siniflarinda kullanilabilir. Widget kaldirildiginda abonelikleri otomatik olarak yönetir ve iptal eder:

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

### Olay Aboneligini Iptal Etme

`listenOn` kullanirken, bellek sizintilarini önlemek için aboneligi manuel olarak iptal etmeniz gerekir:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

`listen` yardimcisi, widget kaldirildiginda abonelik iptalini otomatik olarak yönetir.

<div id="working-with-listeners"></div>

## Dinleyicilerle Çalisma

Dinleyiciler, olaylara yanit veren siniflardir. Her olay, olayin farkli yönlerini islemek için birden fazla dinleyiciye sahip olabilir.

<div id="adding-multiple-listeners"></div>

### Birden Fazla Dinleyici Ekleme

`listeners` özelligini güncelleyerek olayiniza birden fazla dinleyici ekleyebilirsiniz:

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

### Dinleyici Mantigini Uygulama

Her dinleyici, olayi islemek için `handle` metodunu uygulamalidir:

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

## Global Olay Yayini

Tüm olaylarin her seferinde `broadcast: true` belirtmeden otomatik olarak yayinlanmasini istiyorsaniz, global yayini etkinlestirebilirsiniz.

<div id="enabling-global-broadcasting"></div>

### Global Yayini Etkinlestirme

`app/providers/app_provider.dart` dosyanizi düzenleyin ve Nylo örneginize `broadcastEvents()` metodunu ekleyin:

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

Global yayin etkinlestirildiginde, olaylari daha kisa bir sekilde tetikleyebilir ve dinleyebilirsiniz:

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
