# इवेंट्स

---

<a name="section-1"></a>
- [परिचय](#introduction)
  - [इवेंट्स को समझना](#understanding-events)
  - [सामान्य इवेंट उदाहरण](#common-event-examples)
- [इवेंट बनाना](#creating-an-event)
  - [इवेंट स्ट्रक्चर](#event-structure)
- [इवेंट्स डिस्पैच करना](#dispatching-events)
  - [बेसिक इवेंट डिस्पैच](#basic-event-dispatch)
  - [डेटा के साथ डिस्पैच करना](#dispatching-with-data)
  - [इवेंट्स ब्रॉडकास्ट करना](#broadcasting-events)
- [इवेंट्स सुनना](#listening-to-events)
  - [`listenOn` हेल्पर का उपयोग करना](#using-the-listenon-helper)
  - [`listen` हेल्पर का उपयोग करना](#using-the-listen-helper)
  - [इवेंट्स से अनसब्सक्राइब करना](#unsubscribing-from-events)
- [लिसनर्स के साथ काम करना](#working-with-listeners)
  - [मल्टीपल लिसनर्स जोड़ना](#adding-multiple-listeners)
  - [लिसनर लॉजिक इम्प्लीमेंट करना](#implementing-listener-logic)
- [ग्लोबल इवेंट ब्रॉडकास्टिंग](#global-event-broadcasting)
  - [ग्लोबल ब्रॉडकास्टिंग सक्षम करना](#enabling-global-broadcasting)

<div id="introduction"></div>

## परिचय

इवेंट्स तब शक्तिशाली होते हैं जब आपको अपने एप्लिकेशन में कुछ होने के बाद लॉजिक हैंडल करने की आवश्यकता होती है। Nylo का इवेंट सिस्टम आपको अपने एप्लिकेशन में कहीं से भी इवेंट्स बनाने, डिस्पैच करने और सुनने की अनुमति देता है, जिससे रिस्पॉन्सिव, इवेंट-ड्रिवन Flutter एप्लिकेशन बनाना आसान हो जाता है।

<div id="understanding-events"></div>

### इवेंट्स को समझना

इवेंट-ड्रिवन प्रोग्रामिंग एक प्रतिमान है जहाँ आपके एप्लिकेशन का प्रवाह इवेंट्स द्वारा निर्धारित होता है जैसे यूज़र एक्शन, सेंसर आउटपुट, या अन्य प्रोग्रामों या थ्रेड्स से संदेश। यह दृष्टिकोण आपके एप्लिकेशन के विभिन्न भागों को डिकपल करने में मदद करता है, जिससे आपका कोड अधिक मेंटेनेबल और समझने में आसान हो जाता है।

<div id="common-event-examples"></div>

### सामान्य इवेंट उदाहरण

यहाँ कुछ सामान्य इवेंट्स हैं जो आपका एप्लिकेशन उपयोग कर सकता है:
- यूज़र रजिस्ट्रेशन पूर्ण
- यूज़र लॉग इन/आउट
- कार्ट में प्रोडक्ट जोड़ा गया
- पेमेंट सफलतापूर्वक प्रोसेस हुआ
- डेटा सिंक्रोनाइज़ेशन पूर्ण
- पुश नोटिफ़िकेशन प्राप्त

<div id="creating-an-event"></div>

## इवेंट बनाना

आप Nylo framework CLI या Metro का उपयोग करके नया इवेंट बना सकते हैं:

```bash
metro make:event PaymentSuccessfulEvent
```

कमांड चलाने के बाद, `app/events/` डायरेक्टरी में एक नई इवेंट फ़ाइल बनाई जाएगी।

<div id="event-structure"></div>

### इवेंट स्ट्रक्चर

नई बनाई गई इवेंट फ़ाइल का स्ट्रक्चर (जैसे, `app/events/payment_successful_event.dart`):

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

## इवेंट्स डिस्पैच करना

इवेंट्स को `event` हेल्पर मेथड का उपयोग करके आपके एप्लिकेशन में कहीं से भी डिस्पैच किया जा सकता है।

<div id="basic-event-dispatch"></div>

### बेसिक इवेंट डिस्पैच

बिना किसी डेटा के इवेंट डिस्पैच करने के लिए:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### डेटा के साथ डिस्पैच करना

अपने इवेंट के साथ डेटा पास करने के लिए:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### इवेंट्स ब्रॉडकास्ट करना

डिफ़ॉल्ट रूप से, Nylo इवेंट्स केवल इवेंट क्लास में डिफ़ाइन किए गए लिसनर्स द्वारा हैंडल किए जाते हैं। इवेंट को ब्रॉडकास्ट करने (इसे बाहरी लिसनर्स के लिए उपलब्ध बनाने) के लिए, `broadcast` पैरामीटर का उपयोग करें:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## इवेंट्स सुनना

Nylo इवेंट्स को सुनने और उनका जवाब देने के कई तरीके प्रदान करता है।

<div id="using-the-listenon-helper"></div>

### `listenOn` हेल्पर का उपयोग करना

`listenOn` हेल्पर का उपयोग ब्रॉडकास्टेड इवेंट्स सुनने के लिए आपके एप्लिकेशन में कहीं भी किया जा सकता है:

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

### `listen` हेल्पर का उपयोग करना

`listen` हेल्पर `NyPage` और `NyState` क्लासेज़ में उपलब्ध है। यह स्वचालित रूप से सब्सक्रिप्शन प्रबंधित करता है, विजेट डिस्पोज़ होने पर अनसब्सक्राइब करता है:

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

### इवेंट्स से अनसब्सक्राइब करना

`listenOn` का उपयोग करते समय, मेमोरी लीक रोकने के लिए आपको मैन्युअल रूप से अनसब्सक्राइब करना होगा:

```dart
// Store the subscription
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // Handle event
});

// Later, when no longer needed
subscription.cancel();
```

`listen` हेल्पर विजेट डिस्पोज़ होने पर स्वचालित रूप से अनसब्सक्रिप्शन हैंडल करता है।

<div id="working-with-listeners"></div>

## लिसनर्स के साथ काम करना

लिसनर्स ऐसी क्लासेज़ हैं जो इवेंट्स का जवाब देती हैं। प्रत्येक इवेंट में इवेंट के विभिन्न पहलुओं को हैंडल करने के लिए मल्टीपल लिसनर्स हो सकते हैं।

<div id="adding-multiple-listeners"></div>

### मल्टीपल लिसनर्स जोड़ना

आप `listeners` प्रॉपर्टी अपडेट करके अपने इवेंट में मल्टीपल लिसनर्स जोड़ सकते हैं:

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

### लिसनर लॉजिक इम्प्लीमेंट करना

प्रत्येक लिसनर को इवेंट प्रोसेस करने के लिए `handle` मेथड इम्प्लीमेंट करना चाहिए:

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

## ग्लोबल इवेंट ब्रॉडकास्टिंग

यदि आप चाहते हैं कि सभी इवेंट्स हर बार `broadcast: true` निर्दिष्ट किए बिना स्वचालित रूप से ब्रॉडकास्ट हों, तो आप ग्लोबल ब्रॉडकास्टिंग सक्षम कर सकते हैं।

<div id="enabling-global-broadcasting"></div>

### ग्लोबल ब्रॉडकास्टिंग सक्षम करना

अपनी `app/providers/app_provider.dart` फ़ाइल एडिट करें और अपने Nylo इंस्टेंस में `broadcastEvents()` मेथड जोड़ें:

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

ग्लोबल ब्रॉडकास्टिंग सक्षम होने पर, आप इवेंट्स को अधिक संक्षिप्त रूप से डिस्पैच और सुन सकते हैं:

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
