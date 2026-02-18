# Events

---

<a name="section-1"></a>
- [บทนำ](#introduction)
  - [ทำความเข้าใจ Events](#understanding-events)
  - [ตัวอย่าง Event ที่พบบ่อย](#common-event-examples)
- [การสร้าง Event](#creating-an-event)
  - [โครงสร้างของ Event](#event-structure)
- [การส่ง Events](#dispatching-events)
  - [การส่ง Event แบบพื้นฐาน](#basic-event-dispatch)
  - [การส่งพร้อมข้อมูล](#dispatching-with-data)
  - [การกระจาย Events](#broadcasting-events)
- [การฟัง Events](#listening-to-events)
  - [การใช้ตัวช่วย `listenOn`](#using-the-listenon-helper)
  - [การใช้ตัวช่วย `listen`](#using-the-listen-helper)
  - [การยกเลิกการสมัครรับ Events](#unsubscribing-from-events)
- [การทำงานกับ Listeners](#working-with-listeners)
  - [การเพิ่ม Listeners หลายตัว](#adding-multiple-listeners)
  - [การเขียนลอจิกของ Listener](#implementing-listener-logic)
- [การกระจาย Event แบบ Global](#global-event-broadcasting)
  - [การเปิดใช้งานการกระจายแบบ Global](#enabling-global-broadcasting)

<div id="introduction"></div>

## บทนำ

Events มีประโยชน์มากเมื่อคุณต้องจัดการลอจิกหลังจากมีบางสิ่งเกิดขึ้นในแอปพลิเคชันของคุณ ระบบ event ของ Nylo ช่วยให้คุณสร้าง ส่ง และฟัง events จากที่ใดก็ได้ในแอปพลิเคชัน ทำให้การสร้างแอป Flutter ที่ตอบสนองและขับเคลื่อนด้วย event ง่ายขึ้น

<div id="understanding-events"></div>

### ทำความเข้าใจ Events

การเขียนโปรแกรมแบบขับเคลื่อนด้วย event เป็นแนวคิดที่การไหลของแอปพลิเคชันถูกกำหนดโดย events เช่น การกระทำของผู้ใช้ ผลลัพธ์จากเซ็นเซอร์ หรือข้อความจากโปรแกรมหรือเธรดอื่น แนวทางนี้ช่วยแยกส่วนต่างๆ ของแอปพลิเคชันออกจากกัน ทำให้โค้ดของคุณดูแลรักษาได้ง่ายขึ้นและเข้าใจได้ง่ายขึ้น

<div id="common-event-examples"></div>

### ตัวอย่าง Event ที่พบบ่อย

นี่คือตัวอย่าง events ทั่วไปที่แอปพลิเคชันของคุณอาจใช้:
- การลงทะเบียนผู้ใช้เสร็จสมบูรณ์
- ผู้ใช้เข้าสู่ระบบ/ออกจากระบบ
- เพิ่มสินค้าลงในตะกร้า
- การชำระเงินสำเร็จ
- การซิงโครไนซ์ข้อมูลเสร็จสมบูรณ์
- ได้รับ Push notification

<div id="creating-an-event"></div>

## การสร้าง Event

คุณสามารถสร้าง event ใหม่โดยใช้ Nylo framework CLI หรือ Metro:

```bash
metro make:event PaymentSuccessfulEvent
```

หลังจากรันคำสั่ง ไฟล์ event ใหม่จะถูกสร้างในไดเรกทอรี `app/events/`

<div id="event-structure"></div>

### โครงสร้างของ Event

นี่คือโครงสร้างของไฟล์ event ที่สร้างขึ้นใหม่ (เช่น `app/events/payment_successful_event.dart`):

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

## การส่ง Events

Events สามารถส่งได้จากที่ใดก็ได้ในแอปพลิเคชันโดยใช้เมธอดตัวช่วย `event`

<div id="basic-event-dispatch"></div>

### การส่ง Event แบบพื้นฐาน

ส่ง event โดยไม่มีข้อมูลใดๆ:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### การส่งพร้อมข้อมูล

ส่งข้อมูลพร้อมกับ event ของคุณ:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### การกระจาย Events

โดยค่าเริ่มต้น Nylo events จะถูกจัดการเฉพาะโดย listeners ที่กำหนดไว้ในคลาส event เท่านั้น หากต้องการกระจาย event (ทำให้สามารถเข้าถึงได้จาก listeners ภายนอก) ให้ใช้พารามิเตอร์ `broadcast`:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## การฟัง Events

Nylo มีหลายวิธีในการฟังและตอบสนองต่อ events

<div id="using-the-listenon-helper"></div>

### การใช้ตัวช่วย `listenOn`

ตัวช่วย `listenOn` สามารถใช้ได้ทุกที่ในแอปพลิเคชันเพื่อฟัง events ที่ถูกกระจาย:

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

### การใช้ตัวช่วย `listen`

ตัวช่วย `listen` ใช้ได้ในคลาส `NyPage` และ `NyState` โดยจะจัดการการสมัครรับโดยอัตโนมัติ และยกเลิกการสมัครเมื่อ widget ถูกทำลาย:

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

### การยกเลิกการสมัครรับ Events

เมื่อใช้ `listenOn` คุณต้องยกเลิกการสมัครด้วยตนเองเพื่อป้องกันการรั่วไหลของหน่วยความจำ:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

ตัวช่วย `listen` จะจัดการการยกเลิกการสมัครโดยอัตโนมัติเมื่อ widget ถูกทำลาย

<div id="working-with-listeners"></div>

## การทำงานกับ Listeners

Listeners คือคลาสที่ตอบสนองต่อ events แต่ละ event สามารถมี listeners หลายตัวเพื่อจัดการแง่มุมต่างๆ ของ event

<div id="adding-multiple-listeners"></div>

### การเพิ่ม Listeners หลายตัว

คุณสามารถเพิ่ม listeners หลายตัวให้กับ event ได้โดยอัปเดตพร็อพเพอร์ตี้ `listeners`:

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

### การเขียนลอจิกของ Listener

แต่ละ listener ควรเขียนเมธอด `handle` เพื่อประมวลผล event:

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

## การกระจาย Event แบบ Global

หากคุณต้องการให้ events ทั้งหมดถูกกระจายโดยอัตโนมัติโดยไม่ต้องระบุ `broadcast: true` ทุกครั้ง คุณสามารถเปิดใช้งานการกระจายแบบ global ได้

<div id="enabling-global-broadcasting"></div>

### การเปิดใช้งานการกระจายแบบ Global

แก้ไขไฟล์ `app/providers/app_provider.dart` ของคุณและเพิ่มเมธอด `broadcastEvents()` ให้กับอินสแตนซ์ Nylo ของคุณ:

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

เมื่อเปิดใช้งานการกระจายแบบ global แล้ว คุณสามารถส่งและฟัง events ได้อย่างกระชับมากขึ้น:

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
