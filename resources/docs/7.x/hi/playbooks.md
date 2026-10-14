# प्लेबुक्स

---

<a name="section-1"></a>
- [परिचय](#introduction "प्लेबुक्स का परिचय")
- [प्लेबुक बनाना](#creating-a-playbook "एक प्लेबुक बनाना")
- [स्टेप्स](#steps "वे स्टेप्स जो एक प्लेबुक निष्पादित कर सकती है")
- [टार्गेट्स](#targets "स्क्रीन पर चीज़ें ढूँढना")
  - [सादी स्ट्रिंग्स](#plain-strings "सादी स्ट्रिंग कैसे मैच होती है")
  - [Find](#find "Find के साथ सटीक टार्गेट्स")
- [डेटा और सेटअप](#data-and-setup "डेटा, setUp और tearDown")
- [अन्य प्लेबुक्स चलाना](#playing-other-playbooks "एक प्लेबुक से दूसरी प्लेबुक चलाना")
- [स्पीड](#speed "PlaybookSpeed")
- [फ़िंगर और कैप्शन](#finger-and-caption "फ़िंगर और स्टेप कैप्शन")
- [प्लेबुक्स रजिस्टर करना](#registering-playbooks "Nylo के साथ प्लेबुक्स रजिस्टर करना")
- [प्लेबुक चलाना](#running-a-playbook "एक प्लेबुक चलाना")
  - [Metro से](#running-from-metro "Metro से चलाना")
  - [बूट पर](#running-on-boot "बूट पर चलाना")
  - [Dart से](#running-from-dart "Dart से चलाना")
- [रिपोर्ट्स](#reports "PlaybookReport")
- [रिलीज़ बिल्ड्स](#release-builds "रिलीज़ बिल्ड्स में प्लेबुक्स")
- [टेस्टिंग](#testing "एक प्लेबुक की टेस्टिंग")

<div id="introduction"></div>

## परिचय

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

एक **प्लेबुक** एक क्लास है जो किसी यूज़र जर्नी के टैप, टाइपिंग, स्क्रॉलिंग और पेज बदलावों की सूची बनाती है। आपका चलता हुआ ऐप उन्हें खुद पर निष्पादित करता है, और हर स्टेप के लिए एक उंगली और कैप्शन दिखा सकता है -- डेमो, स्क्रीन रिकॉर्डिंग और किसी जर्नी को शुरू से अंत तक जाँचने के लिए उपयोगी।

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

जहाँ भी आप प्लेबुक्स लिखें या रजिस्टर करें, वहाँ `package:nylo_framework/playbook.dart` इम्पोर्ट करें।

<div id="creating-a-playbook"></div>

## प्लेबुक बनाना

एक बनाने के लिए Metro का उपयोग करें:

``` bash
metro make:playbook sign_up
```

यह `lib/app/playbooks/sign_up_playbook.dart` बनाता है और उसे आपके ऐप के साथ रजिस्टर करता है। कमांड के विकल्पों के लिए [प्लेबुक बनाना](/docs/{{ $version }}/metro#make-playbook) देखें।

<div id="steps"></div>

## स्टेप्स

एक प्लेबुक के `steps` उन क्रियाओं से बनते हैं जो उसे विरासत में मिलती हैं:

| स्टेप | विवरण |
|------|-------------|
| `tap(target)` | एक टार्गेट पर टैप करें |
| `longPress(target)` | एक टार्गेट को दबाए रखें |
| `type(target, text)` | एक फ़ील्ड में टाइप करें। `clear: false` न हो तो पहले उसे साफ़ करता है; `submit: true` कीबोर्ड का एक्शन बटन दबाता है; `secret: true` कैप्शन और रिपोर्ट में टेक्स्ट छिपाता है |
| `scrollTo(target)` | टार्गेट के स्क्रीन पर आने तक स्क्रॉल करें। किसी विशेष स्क्रॉल होने वाले विजेट को स्क्रॉल करने के लिए `within:` दें |
| `see(target)` | जाँचें कि टार्गेट स्क्रीन पर है, उसके दिखने की प्रतीक्षा करते हुए |
| `dontSee(target)` | जाँचें कि टार्गेट स्क्रीन पर नहीं है, उसके हटने की प्रतीक्षा करते हुए |
| `waitFor(target)` | `timeout` तक (डिफ़ॉल्ट रूप से 10 सेकंड) टार्गेट की प्रतीक्षा करें |
| `waitForPage(path)` | किसी रूट पाथ या `RouteView` (जैसे `HomePage.path`) के पेज की प्रतीक्षा करें |
| `visit(path)` | रूट पाथ या `RouteView` से एक पेज खोलें, वैकल्पिक `data:` के साथ |
| `back()` | एक पेज पीछे जाएं |
| `pause(seconds: 1)` | रुके रहें, जैसे डेमो को सांस लेने देने के लिए |
| `step(title, action)` | `PlaybookActor` के ज़रिए उन्हीं क्रियाओं के साथ कोई भी कोड चलाएं |
| `scene(title, steps)` | स्टेप्स को एक शीर्षक के तहत समूहित करें; उनके कैप्शन उसी से शुरू होते हैं और उनके नतीजे रिपोर्ट में नेस्ट होते हैं |
| `play(playbook)` | दूसरी प्लेबुक चलाएं -- देखें [अन्य प्लेबुक्स चलाना](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

स्टेप्स क्रम से चलते हैं और पहली विफलता पर रुक जाते हैं। बाकी स्टेप्स रिपोर्ट में छोड़े गए (skipped) बताए जाते हैं।

<div id="targets"></div>

## टार्गेट्स

ज़्यादातर स्टेप्स एक टार्गेट लेते हैं: किस पर टैप करना है, कहाँ टाइप करना है या क्या ढूँढना है।

<div id="plain-strings"></div>

### सादी स्ट्रिंग्स

एक सादी स्ट्रिंग उसी तरह मैच होती है जैसे कोई व्यक्ति स्क्रीन पढ़ता है। यह इस क्रम में कोशिश करती है:

1. दिखाई देने वाला टेक्स्ट
2. फ़ील्ड का हिंट या लेबल
3. एक टूलटिप
4. एक सिमेंटिक्स लेबल
5. एक `ValueKey`
6. वह टेक्स्ट जिसमें यह स्ट्रिंग शामिल हो

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

एक `Key`, एक `Type` या एक `IconData` को भी सीधे किसी स्टेप को दिया जा सकता है।

<div id="find"></div>

### Find

जब सादी स्ट्रिंग पर्याप्त सटीक न हो तो `Find` का उपयोग करें:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| फ़ाइंडर | किससे मैच करता है |
|--------|---------|
| `Find.text(text)` | दिखाई देने वाला टेक्स्ट |
| `Find.textContaining(text)` | वह टेक्स्ट जिसमें यह स्ट्रिंग शामिल हो |
| `Find.icon(icon)` | एक आइकन |
| `Find.type<T>()` | प्रकार `T` का एक विजेट |
| `Find.key(key)` | दी गई की (key) वाला एक विजेट |
| `Find.field(name)` | नाम से एक `NyForm` फ़ील्ड |
| `Find.input(text)` | हिंट, लेबल या प्लेसहोल्डर से एक टेक्स्ट फ़ील्ड |
| `Find.tooltip(message)` | एक टूलटिप |
| `Find.semantics(label)` | एक सिमेंटिक्स लेबल |
| `Find.where(test)` | वे विजेट्स जो आपका टेस्ट पास करें |

किसी भी फ़ाइंडर को `.inside(ancestor)`, `.at(index)` (0 से गिनती), `.first` या `.last` से सीमित करें।

जब कोई असंबंधित चीज़, जैसे डायलॉग बैरियर, टार्गेट को ढक रही हो तो टैप अस्वीकार कर दिया जाता है।

<div id="data-and-setup"></div>

## डेटा और सेटअप

`defaults` वह डेटा रखता है जिसके साथ स्टेप्स शुरू होते हैं। इसे `steps` के अंदर `data` के ज़रिए पढ़ें। `setUp` स्टेप्स से पहले चलता है और `tearDown` उनके बाद चलता है, भले ही कोई स्टेप विफल हुआ हो:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` [सीडर्स](/docs/{{ $version }}/metro-live#seeders) (इंस्टैंस या रजिस्टर्ड नाम) चलाता है और उन्हें `metro live` के `seed` की तरह रिकॉर्ड करता है।

<div id="playing-other-playbooks"></div>

## अन्य प्लेबुक्स चलाना

`play()` दूसरी प्लेबुक को उसके अपने `setUp` और `tearDown` के साथ पूरी तरह चलाता है। एक इंस्टैंस या वह नाम दें जिसके तहत वह रजिस्टर है:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **डेटा** इस क्रम में मर्ज होता है: चाइल्ड के `defaults`, फिर पैरेंट का `data`, फिर `play` को दिया गया `data:`।
- **स्पीड** पैरेंट से विरासत में मिलती है, जब तक आप `speed:` न दें।
- **नतीजे** रिपोर्ट में उस स्टेप के नीचे नेस्ट होते हैं।
- **विफल होने वाला चाइल्ड** स्टेप को विफल कर देता है।
- **चक्र (Cycles)** अस्वीकार किए जाते हैं, और प्लेबुक्स अधिकतम 8 स्तर तक नेस्ट हो सकती हैं।

<div id="speed"></div>

## स्पीड

`PlaybookSpeed` हर प्रतीक्षा की गति तय करता है:

| स्पीड | विवरण |
|-------|-------------|
| `PlaybookSpeed.instant` | स्क्रॉलिंग की ज़रूरत से ज़्यादा कोई प्रतीक्षा नहीं। टेस्ट्स के लिए बनी |
| `PlaybookSpeed.fast` | तेज़, लेकिन फिर भी अनुसरण करने में आसान |
| `PlaybookSpeed.natural` | ऐप इस्तेमाल करने वाले व्यक्ति की गति। डिफ़ॉल्ट |
| `PlaybookSpeed.slow` | इतनी धीमी कि उस पर वॉयसओवर किया जा सके |

किसी भी स्पीड को `.times(n)` से स्केल करें:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // दोगुना धीमा
```

`run()` को दी गई स्पीड, Metro से `--speed`, या इस प्लेबुक को चलाने वाली प्लेबुक की स्पीड, प्लेबुक की अपनी स्पीड पर भारी पड़ती है।

<div id="finger-and-caption"></div>

## फ़िंगर और कैप्शन

उंगली और स्टेप कैप्शन डिफ़ॉल्ट रूप से बंद होते हैं। उन्हें चालू करने के लिए `showFinger` और `caption` को ओवरराइड करें:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // या .top
```

`showFinger` का डिफ़ॉल्ट `false` है और `caption` का `PlaybookCaption.hidden`; `PlaybookCaption` `top`, `bottom` या `hidden` होता है। टैप और स्वाइप दोनों स्थितियों में होते हैं। `play()` से इस प्लेबुक द्वारा चलाई गई प्लेबुक अपनी खुद की सेटिंग इस्तेमाल करती है, और खत्म होने पर इसकी सेटिंग लौट आती है। किसी रन के लिए दोनों छिपाने हेतु, Metro से `--no-overlay` या Dart से `run(overlay: false)` का उपयोग करें।

nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0) से पहले, उंगली और नीचे का कैप्शन डिफ़ॉल्ट रूप से चालू थे। वही रूप बनाए रखने के लिए ऊपर दिए ओवरराइड जोड़ें।

<div id="registering-playbooks"></div>

## प्लेबुक्स रजिस्टर करना

अपनी प्लेबुक्स रजिस्टर करें ताकि Metro और `play('name')` उन्हें ढूँढ सकें:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` वह लौटाता है जो रजिस्टर है। आप उन्हें अपने `AppProvider` में Nylo कॉन्फ़िगर करते समय भी दे सकते हैं:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` यह आपके लिए करता है। यह रजिस्ट्री को `lib/bootstrap/playbooks.dart` में रखता है, हर नई प्लेबुक को उसमें जोड़ता है, और `nylo.configure(...)` को `playbooks` पास करता है। रिलीज़ बिल्ड्स में रजिस्ट्री एक खाली मैप होती है:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

एक रजिस्टर्ड प्लेबुक अपनी की (key) के तहत चलती है। एक अनरजिस्टर्ड प्लेबुक अपने `name` के तहत चलती है, या स्नेक केस में अपने क्लास नाम के तहत (`SignUpPlaybook` का `sign_up`)।

<div id="running-a-playbook"></div>

## प्लेबुक चलाना

<div id="running-from-metro"></div>

### Metro से

अपने ऐप को डिबग मोड में चलाते हुए:

``` bash
metro live:run playbook sign_up --speed fast
```

`metro live` के अंदर, `playbook sign_up` टाइप करें। हर विकल्प के लिए [Metro Live](/docs/{{ $version }}/metro-live#playbooks) देखें। वीडियो सहेजने के लिए `--record` जोड़ें; [रिकॉर्डिंग](/docs/{{ $version }}/metro-live#recording-playbooks) देखें।

<div id="running-on-boot"></div>

### बूट पर

ऐप के स्क्रीन पर आते ही प्लेबुक्स चलाने के लिए अपनी `.env` फ़ाइल में ये कीज़ जोड़ें:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` प्लेबुक नामों की कॉमा से अलग की गई सूची है और `PLAYBOOK_SPEED` `instant`, `fast`, `natural` या `slow` में से एक है। दोनों केवल डिबग और प्रोफाइल बिल्ड्स में लागू होते हैं।

<div id="running-from-dart"></div>

### Dart से

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` `speed`, `data`, `clock`, `overlay` (डिफ़ॉल्ट `true`) और `onEvent` स्वीकार करता है। एक विफल स्टेप एक्सेप्शन नहीं फेंकता -- `report.passed` जाँचें। एक समय में केवल एक प्लेबुक चल सकती है; एक के अंदर दूसरी चलाने के लिए `play()` का उपयोग करें।

चल रही प्लेबुक को `Playbook.stop()` से रोकें। बाकी स्टेप्स छोड़ दिए जाते हैं लेकिन `tearDown` फिर भी चलता है। `Playbook.isRunning` बताता है कि कोई चल रही है या नहीं।

<div id="reports"></div>

## रिपोर्ट्स

`run()` एक `PlaybookReport` लौटाता है जिसमें प्लेबुक का `name`, `speed`, `elapsed` समय, क्या वह `stopped` हुई, और `PlaybookStepResult`s की एक `steps` सूची (हर एक में `title`, `status`, `elapsed`, `error` और नेस्टेड `steps`) होती है। `report.passed` तब true होता है जब हर स्टेप पास हुआ हो, और रिपोर्ट प्रिंट करने पर वह Metro के आउटपुट जैसी पढ़ी जाती है। `report.toJson()` वही डेटा JSON के रूप में देता है।

<div id="release-builds"></div>

## रिलीज़ बिल्ड्स

प्लेबुक्स रिलीज़ बिल्ड में कभी नहीं चलतीं, ताकि कोई जर्नी किसी यूज़र के ऐप को न चला सके:

- `Playbook.run()` एक `UnsupportedError` फेंकता है।
- `addPlaybooks` को अनदेखा किया जाता है।
- Metro की बनाई `lib/bootstrap/playbooks.dart` रजिस्ट्री खाली होती है।

`Playbook.isAvailable` बताता है कि वर्तमान बिल्ड प्लेबुक्स चला सकता है या नहीं।

<div id="testing"></div>

## टेस्टिंग

एक प्लेबुक को विजेट टेस्ट में `NyTesterClock` के साथ चलाएं, फिर उसकी रिपोर्ट पर `expectPlaybookPassed` से जाँच करें:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

इंटीग्रेशन टेस्ट्स के लिए [प्लेबुक्स की टेस्टिंग](/docs/{{ $version }}/testing#testing-playbooks) देखें।
