# शेड्यूलर

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [एक बार शेड्यूल करें](#schedule-once "एक बार चलने के लिए टास्क शेड्यूल करें")
- [किसी तिथि के बाद एक बार शेड्यूल करें](#schedule-once-after-date "किसी विशिष्ट तिथि के बाद एक बार चलने के लिए टास्क शेड्यूल करें")
- [प्रतिदिन एक बार शेड्यूल करें](#schedule-once-daily "प्रतिदिन एक बार चलने के लिए टास्क शेड्यूल करें")

<div id="introduction"></div>

## परिचय

Nylo आपको अपने ऐप में एक बार, प्रतिदिन, या किसी विशिष्ट तिथि के बाद होने वाले टास्क शेड्यूल करने की अनुमति देता है।

इस डॉक्यूमेंटेशन को पढ़ने के बाद, आप सीखेंगे कि अपने ऐप में टास्क कैसे शेड्यूल करें।

<div id="schedule-once"></div>

## एक बार शेड्यूल करें

आप `Nylo.scheduleOnce` मेथड का उपयोग करके एक बार चलने के लिए टास्क शेड्यूल कर सकते हैं।

इस मेथड का उपयोग करने का एक सरल उदाहरण:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## किसी तिथि के बाद एक बार शेड्यूल करें

आप `Nylo.scheduleOnceAfterDate` मेथड का उपयोग करके किसी विशिष्ट तिथि के बाद एक बार चलने के लिए टास्क शेड्यूल कर सकते हैं।

इस मेथड का उपयोग करने का एक सरल उदाहरण:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## प्रतिदिन एक बार शेड्यूल करें

आप `Nylo.scheduleOnceDaily` मेथड का उपयोग करके प्रतिदिन एक बार चलने के लिए टास्क शेड्यूल कर सकते हैं।

इस मेथड का उपयोग करने का एक सरल उदाहरण:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
