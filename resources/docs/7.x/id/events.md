# Event

---

<a name="section-1"></a>
- [Pengantar](#introduction)
  - [Memahami Event](#understanding-events)
  - [Contoh Event Umum](#common-event-examples)
- [Membuat Event](#creating-an-event)
  - [Struktur Event](#event-structure)
- [Mengirim Event](#dispatching-events)
  - [Pengiriman Event Dasar](#basic-event-dispatch)
  - [Mengirim dengan Data](#dispatching-with-data)
  - [Broadcasting Event](#broadcasting-events)
- [Mendengarkan Event](#listening-to-events)
  - [Menggunakan Helper `listenOn`](#using-the-listenon-helper)
  - [Menggunakan Helper `listen`](#using-the-listen-helper)
  - [Berhenti Berlangganan Event](#unsubscribing-from-events)
- [Bekerja dengan Listener](#working-with-listeners)
  - [Menambahkan Beberapa Listener](#adding-multiple-listeners)
  - [Mengimplementasikan Logika Listener](#implementing-listener-logic)
- [Broadcasting Event Global](#global-event-broadcasting)
  - [Mengaktifkan Broadcasting Global](#enabling-global-broadcasting)

<div id="introduction"></div>

## Pengantar

Event sangat berguna ketika Anda perlu menangani logika setelah sesuatu terjadi di aplikasi Anda. Sistem event Nylo memungkinkan Anda membuat, mengirim, dan mendengarkan event dari mana saja di aplikasi Anda, memudahkan pembangunan aplikasi Flutter yang responsif dan berbasis event.

<div id="understanding-events"></div>

### Memahami Event

Pemrograman berbasis event adalah paradigma di mana alur aplikasi Anda ditentukan oleh event seperti aksi pengguna, output sensor, atau pesan dari program atau thread lain. Pendekatan ini membantu memisahkan berbagai bagian aplikasi Anda, membuat kode Anda lebih mudah dipelihara dan dipahami.

<div id="common-event-examples"></div>

### Contoh Event Umum

Berikut beberapa event tipikal yang mungkin digunakan aplikasi Anda:
- Registrasi pengguna selesai
- Pengguna login/logout
- Produk ditambahkan ke keranjang
- Pembayaran berhasil diproses
- Sinkronisasi data selesai
- Push notification diterima

<div id="creating-an-event"></div>

## Membuat Event

Anda dapat membuat event baru menggunakan CLI framework Nylo atau Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

Setelah menjalankan perintah, file event baru akan dibuat di direktori `app/events/`.

<div id="event-structure"></div>

### Struktur Event

Berikut struktur file event yang baru dibuat (contoh: `app/events/payment_successful_event.dart`):

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

## Mengirim Event

Event dapat dikirim dari mana saja di aplikasi Anda menggunakan method helper `event`.

<div id="basic-event-dispatch"></div>

### Pengiriman Event Dasar

Untuk mengirim event tanpa data:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### Mengirim dengan Data

Untuk mengirim data bersama event Anda:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### Broadcasting Event

Secara default, event Nylo hanya ditangani oleh listener yang didefinisikan di kelas event. Untuk mem-broadcast event (membuatnya tersedia untuk listener eksternal), gunakan parameter `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## Mendengarkan Event

Nylo menyediakan beberapa cara untuk mendengarkan dan merespons event.

<div id="using-the-listenon-helper"></div>

### Menggunakan Helper `listenOn`

Helper `listenOn` dapat digunakan di mana saja di aplikasi Anda untuk mendengarkan event yang di-broadcast:

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

### Menggunakan Helper `listen`

Helper `listen` tersedia di kelas `NyPage` dan `NyState`. Helper ini secara otomatis mengelola subscription, berhenti berlangganan saat widget di-dispose:

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

### Berhenti Berlangganan Event

Saat menggunakan `listenOn`, Anda harus berhenti berlangganan secara manual untuk mencegah kebocoran memori:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

Helper `listen` secara otomatis menangani pembatalan langganan saat widget di-dispose.

<div id="working-with-listeners"></div>

## Bekerja dengan Listener

Listener adalah kelas yang merespons event. Setiap event dapat memiliki beberapa listener untuk menangani aspek berbeda dari event tersebut.

<div id="adding-multiple-listeners"></div>

### Menambahkan Beberapa Listener

Anda dapat menambahkan beberapa listener ke event Anda dengan memperbarui properti `listeners`:

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

### Mengimplementasikan Logika Listener

Setiap listener harus mengimplementasikan method `handle` untuk memproses event:

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

## Broadcasting Event Global

Jika Anda ingin semua event di-broadcast secara otomatis tanpa menentukan `broadcast: true` setiap kali, Anda dapat mengaktifkan broadcasting global.

<div id="enabling-global-broadcasting"></div>

### Mengaktifkan Broadcasting Global

Edit file `app/providers/app_provider.dart` Anda dan tambahkan method `broadcastEvents()` ke instance Nylo Anda:

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

Dengan broadcasting global diaktifkan, Anda dapat mengirim dan mendengarkan event dengan lebih ringkas:

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
