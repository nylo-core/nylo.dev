# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [परिचय](#introduction "Nylo Live का परिचय")
- metro live शेल
  - [साझा विकल्प](#shared-options "हर लाइव कमांड द्वारा स्वीकृत विकल्प")
  - [टैब पूर्णता और इतिहास](#tab-completion-and-history "टैब पूर्णता और इतिहास")
  - [स्क्रिप्ट चलाना](#running-a-script "एक .live स्क्रिप्ट चलाना")
- [live:devices](#live-devices "चल रहे ऐप्स की सूची")
- [live:status](#live-status "ऐप की स्थिति दिखाएं")
- [live:run](#live-run "एक बिल्ट-इन लाइव कमांड चलाएं")
- [प्लेबुक्स](#playbooks "Metro से प्लेबुक्स चलाना")
- लाइव कमांड
  - [एक लाइव कमांड बनाना](#creating-a-live-command "एक लाइव कमांड बनाना")
  - [एक लाइव कमांड चलाना](#running-a-live-command "एक लाइव कमांड चलाना")
- सीडर्स
  - [एक सीडर बनाना](#creating-a-seeder "एक सीडर बनाना")
  - [सीडिंग और रोलिंग बैक](#seeding-and-rolling-back "सीडिंग और रोलिंग बैक")
- स्टोरेज स्नैपशॉट
  - [स्नैपशॉट एक्सपोर्ट करना](#exporting-a-snapshot "एक स्नैपशॉट एक्सपोर्ट करना")
  - [स्नैपशॉट लोड करना](#loading-a-snapshot "एक स्नैपशॉट लोड करना")
- [पेज एक्शन्स](#page-actions "इसके बाहर से एक पेज को कॉल करना")

<div id="introduction"></div>

## परिचय

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** Metro को Dart VM सर्विस के माध्यम से आपके ऐप में पहुंचने देता है, जब वह डिबग (या प्रोफाइल) मोड में चल रहा हो। यह बिना किसी सेटअप के Dart Tooling Daemon के माध्यम से ऐप को ढूंढता है, और केवल वर्तमान प्रोजेक्ट से बने ऐप्स से ही कनेक्ट होता है।

Nylo Live डिबग और प्रोफाइल बिल्ड में डिफ़ॉल्ट रूप से चालू रहता है; एक रिलीज़ बिल्ड कुछ भी रजिस्टर नहीं करता। जो कमांड केवल ऐप को पढ़ते हैं (`live:status`, `live:run data`, `live:run routes`, ...) वे प्रोफाइल बिल्ड में भी काम करते हैं। जो कमांड ऐप को बदलते हैं (`route`, `storage`, `auth`, सीडर्स, ...) उन्हें डिबग बिल्ड की आवश्यकता होती है।

इसे किसी भी अन्य चीज़ के चलने से पहले, एक प्रोवाइडर से खुद बंद करें:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## metro live शेल

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

अपने चल रहे ऐप से जुड़ा एक शेल खोलें:

``` bash
metro live
```

Nylo Live प्रोजेक्ट के चल रहे ऐप्स को खोजता है और किसी एक से कनेक्ट होता है -- जब एक से अधिक चल रहे हों तो यह आपसे चुनने के लिए कहता है। कनेक्ट होने के बाद, प्रॉम्प्ट डिवाइस और स्क्रीन पर मौजूदा रूट दिखाता है:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

लाइव कमांड शेल के अंदर अपना `metro live:` प्रीफिक्स छोड़ देते हैं -- `metro live:status` की बजाय `status`, `metro live:run route /profile` की बजाय `route /profile`। कुछ कमांड केवल शेल के अंदर ही चलते हैं: `seed`, `seed:rollback`, `export`, `reload` और `restart`। हर कमांड को सूचीबद्ध करने के लिए `help` टाइप करें, या किसी एक कमांड के विकल्प देखने के लिए `help <command>` टाइप करें। बाहर निकलने के लिए `exit` (या Ctrl+D) टाइप करें।

अगर कनेक्टेड रहते हुए ऐप रीस्टार्ट होता है, तो शेल अपने आप फिर से कनेक्ट हो जाता है।

<div id="shared-options"></div>

### साझा विकल्प

हर लाइव कमांड -- चाहे शेल के अंदर हो या `metro live:*` के रूप में -- एक जैसे विकल्प स्वीकार करता है:

| विकल्प | विवरण |
|--------|-------------|
| `-d, --device <#\|name>` | `metro live:devices` में उसकी संख्या या डिवाइस नाम से एक ऐप को टारगेट करें |
| `--all` | इस प्रोजेक्ट के हर चल रहे ऐप पर चलाएं |
| `--json` | मशीन-रीडेबल JSON प्रिंट करें |
| `--uri` | ऐप्स खोजने की बजाय इस VM सर्विस एड्रेस का उपयोग करें |
| `--timeout` | खोजते समय हर ऐप के लिए प्रतीक्षा करने के सेकंड (डिफ़ॉल्ट `3`) |

`@path` के रूप में लिखा गया एक विकल्प मान उस फ़ाइल से पढ़ा जाता है -- एक बड़े `--data` पेलोड के लिए उपयोगी:

``` bash
metro live:run route /profile --data @user.json
```

अगर मान वास्तव में `@` से शुरू होता है, तो उसे `@@` से शुरू करें।

<div id="tab-completion-and-history"></div>

### टैब पूर्णता और इतिहास

शेल के अंदर, Tab कनेक्टेड ऐप के विरुद्ध कमांड नाम, विकल्प, रूट, स्टोरेज और Backpack कीज़, सीडर नामों और प्लेबुक नामों को पूरा करता है। इतिहास `.dart_tool/nylo/live_history` में रखा जाता है और सत्रों के बीच बना रहता है।

<div id="running-a-script"></div>

### स्क्रिप्ट चलाना

एक फ़ाइल को `metro live` में पाइप करके उसे एक स्क्रिप्ट के रूप में चलाएं, प्रति लाइन एक कमांड, पहले असफल कमांड पर रुकते हुए:

``` bash
metro live < seeds/demo.live
```

खाली लाइनें और `#` से शुरू होने वाली लाइनें छोड़ दी जाती हैं।

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

इस प्रोजेक्ट के चल रहे ऐप्स को सूचीबद्ध करता है, `-d` के लिए क्रमांकित:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

ऐप, डिवाइस, बिल्ड मोड, वर्तमान रूट और स्टैक, लोकेल, थीम, और क्या कोई यूज़र साइन इन है, यह दिखाता है:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

एक बिल्ट-इन कमांड चलाता है जो ऐप का निरीक्षण करता है या उसे संचालित करता है:

``` bash
metro live:run <command> [arguments]
```

| कमांड | विवरण |
|---------|-------------|
| `data` | स्क्रीन पर मौजूद पेज का डेटा और फ़ील्ड दिखाएं |
| `routes` | रजिस्टर्ड रूट्स की सूची दिखाएं |
| `route` | वैकल्पिक `--data` के साथ एक पेज खोलें |
| `back` | एक पेज पीछे जाएं, या किसी रूट पर वापस जाएं |
| `deeplink` | एक डीप लिंक खोलें, या दिखाएं कि वे कैसे सेट अप किए गए हैं |
| `storage` | लोकल स्टोरेज सूचीबद्ध करें, या एक वैल्यू दिखाएं, सेव करें या डिलीट करें |
| `storage:clear` | वैकल्पिक `--keep` के साथ लोकल स्टोरेज साफ़ करें |
| `backpack` | Backpack वैल्यूज़ सूचीबद्ध करें, या एक दिखाएं, सेव करें या डिलीट करें |
| `auth` | दिखाएं कौन साइन इन है, या साइन इन और आउट करें |
| `locale` | ऐप की भाषा बदलें |
| `theme` | ऐप की थीम बदलें |
| `state` | `updateState` के साथ एक स्टेट को डेटा भेजें |
| `event` | अपने किसी इवेंट को फायर करें |
| `toast` | एक टोस्ट नोटिफिकेशन दिखाएं |
| `playbook` | अपनी [प्लेबुक्स](/docs/{{ $version }}/playbooks) चलाएं, या उनकी सूची दिखाएं |

`metro live` के अंदर, `live:run` वाला हिस्सा छोड़ दें -- `metro live:run route /profile` की बजाय `route /profile`:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` आपके पेज के स्टेट द्वारा घोषित फ़ील्ड को भी सीधे चल रहे ऐप से पढ़ता है -- Flutter में कोई रिफ्लेक्शन नहीं है, इसलिए यह केवल उस स्टेट के लिए काम करता है जो वास्तव में स्क्रीन पर है:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## प्लेबुक्स

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

अपने चल रहे ऐप पर एक या अधिक [प्लेबुक्स](/docs/{{ $version }}/playbooks) चलाएं। हर स्टेप होते ही प्रिंट होता है:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

कई प्लेबुक्स के नाम दें तो वे एक के बाद एक चलती हैं। कोई नाम न देने पर, या `--list` के साथ, कमांड ऐप की प्लेबुक्स की सूची दिखाता है।

| विकल्प | विवरण |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | हर प्लेबुक की अपनी गति के बजाय इस गति से चलाएं |
| `--data <json>` | एक JSON ऑब्जेक्ट जिसे रन की हर प्लेबुक अपने डिफ़ॉल्ट्स के ऊपर `data` में पढ़ती है |
| `--no-overlay` | काउंटडाउन छिपाएं, और जो प्लेबुक्स उन्हें दिखाती हैं उनकी उंगली और कैप्शन भी |
| `--loop` | Ctrl+C तक प्लेबुक्स को बार-बार चलाएं |
| `--record[=<file>]` | रन के दौरान डिवाइस की स्क्रीन रिकॉर्ड करें। `build/playbooks/<playbooks>-<device>-<time>.mp4` में सहेजी जाती है; `--record=demo.mp4` (या `.gif`) प्रोजेक्ट के सापेक्ष फ़ाइल चुनता है। ffmpeg चाहिए |
| `--no-captions` | रिकॉर्डिंग की `.vtt` कैप्शन फ़ाइल और चैप्टर्स छोड़ दें |
| `--clean-status-bar` | प्लेबुक्स चलते समय पूरे सिग्नल और बैटरी के साथ 9:41 दिखाएं, फिर पहले जैसा कर दें। `--record` के बिना भी काम करता है |
| `--countdown <seconds>` | पहले स्टेप से पहले स्क्रीन पर 0-10 सेकंड की उलटी गिनती करें |
| `--list` | किसी को चलाने के बजाय ऐप की प्लेबुक्स की सूची दिखाएं |

Ctrl+C ऐप से रुकने को कहता है: बाकी स्टेप्स छोड़ दिए जाते हैं, लेकिन `tearDown` फिर भी चलता है। प्रतीक्षा बंद करने के लिए Ctrl+C दूसरी बार दबाएं।

`metro live` शेल के अंदर कमांड `playbook <name>` है, और Tab प्लेबुक नाम पूरे करता है:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### रिकॉर्डिंग

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

<!-- uncertain: new Recording section; no prior locale context for recording terms -->
रन का वीडियो सहेजने के लिए `--record` जोड़ें:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

`metro live` शेल के अंदर `playbook sign_up --record` टाइप करें।

रिकॉर्डिंग के लिए ffmpeg चाहिए (उदाहरण के लिए `brew install ffmpeg`)। कुछ भी चलने से पहले Metro इसकी जाँच करता है।

- **iOS Simulator** -- `xcrun simctl` से रिकॉर्ड होता है।
- **Android एमुलेटर या डिवाइस** -- `adb screenrecord` से रिकॉर्ड होता है, जो 3 मिनट बाद रुक जाता है, या `scrcpy` इंस्टॉल होने पर उससे (कोई सीमा नहीं, और Android 11+ पर आवाज़ भी रिकॉर्ड होती है)। अगर कई Android डिवाइस जुड़े हैं और Metro नहीं बता पाता कि ऐप किस पर चल रहा है, तो `ANDROID_SERIAL` सेट करें।
- **असली iPhone** -- कमांड लाइन से रिकॉर्ड नहीं किया जा सकता। QuickTime या Control Center से रिकॉर्ड करें और शुरू करने का समय पाने के लिए `--countdown 3` का उपयोग करें।

नतीजा एक `.mp4` (H.264, स्थिर 60 fps) होता है जो ठीक रन जितनी देर का होता है, या 480px चौड़ा 15 fps `.gif`। हर स्टेप `.mp4` के बगल की `.vtt` फ़ाइल में एक कैप्शन और उसके अंदर एक चैप्टर बन जाता है। ये रन के स्टेप्स से बनते हैं, चाहे प्लेबुक ऑन-स्क्रीन कैप्शन दिखाती हो या नहीं। इन्हें छोड़ने के लिए `--no-captions` दें।

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

जो प्लेबुक फ़ेल होती है या रोकी जाती है वह भी सहेजी जाती है, इसलिए रिकॉर्डिंग बग रिपोर्ट का काम भी करती है। Ctrl+C प्लेबुक को रोकता है (`tearDown` फिर भी चलता है) और रिकॉर्डिंग फिर भी सहेजी जाती है।

`--clean-status-bar` Simulator पर `simctl status_bar` से पहले से सेट की गई स्टेटस बार को बनाए रखता है। Android पर यह डेमो मोड इस्तेमाल करता है और बाद में उसे बंद कर देता है। कई ऐप्स (`--all`) के साथ, हर डिवाइस को अपनी फ़ाइल मिलती है जिसके नाम में डिवाइस का नाम होता है।

प्लेबुक डिफ़ॉल्ट रूप से अपनी उंगली और कैप्शन छिपाती है। डेमो वीडियो के लिए प्लेबुक पर `showFinger` और `caption` सेट करें -- [फ़िंगर और कैप्शन](/docs/{{ $version }}/playbooks#finger-and-caption) देखें।

<div id="playbook-live-commands"></div>

### लाइव कमांड रेफ़रेंस

इसके पीछे के कमांड `playbooks.list`, `playbooks.run` और `playbooks.stop` हैं। एक रन तुरंत अपना id लौटाता है और हर स्टेप को `nylo.playbook` इवेंट के रूप में स्ट्रीम करता है, इसलिए वह किसी कमांड के टाइमआउट से अधिक समय तक चल सकता है। `live:status` ऐप की प्लेबुक्स की सूची दिखाता है। प्लेबुक्स Nylo Live प्रोटोकॉल संस्करण 9 में आईं। रिकॉर्डिंग सपोर्ट -- `playbooks.run` में `countdown` (हर सेकंड एक `countdown` इवेंट के साथ) और `status` में `simulator` (iOS Simulator का UDID) -- प्रोटोकॉल संस्करण 10 में आया।

<div id="live-commands"></div>

## लाइव कमांड

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

ऊपर दिए गए बिल्ट-इन कमांड्स के साथ-साथ, ऐप के अंदर चलाने के लिए अपने खुद के कमांड रजिस्टर करें।

<div id="creating-a-live-command"></div>

### एक लाइव कमांड बनाना

``` bash
metro make:command seed_cart --live
```

| विकल्प | विवरण |
|--------|-------------|
| `--category`, `-c` | कमांड के लिए श्रेणी (डिफ़ॉल्ट: `app`) |
| `--description` | कमांड के `--help` द्वारा दिखाया गया एक-पंक्ति विवरण |
| `--force`, `-f` | पहले से मौजूद होने पर भी फ़ाइल बनाता है |

यह `lib/app/commands/` में एक `LiveCommand` बनाता है, इसे `commands.json` में `"type": "live"` के रूप में चिह्नित करता है, इसे `lib/bootstrap/live_commands.dart` में रजिस्टर करता है, और `app_provider.dart` में `nylo.configure(...)` में `liveCommands: liveCommands` को जोड़ता है:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand`, Metro के अपने कमांड्स के साथ `builder`/`handle` संरचना साझा करता है, लेकिन `handle` **ऐप के अंदर** चलता है, इसलिए यह `NyStorage`, `routeTo`, `Auth` और आपके मॉडल्स का उपयोग कर सकता है। `package:nylo_framework/live.dart`, `Seeder`, `StorageSnapshot`, `LiveException` और `LiveOutput` के साथ-साथ `LiveCommand` को एक्सपोर्ट करता है।

<div id="running-a-live-command"></div>

### एक लाइव कमांड चलाना

एक नए लाइव कमांड को चल रहे ऐप द्वारा चलाए जाने से पहले एक हॉट रीस्टार्ट की आवश्यकता होती है। फिर, टर्मिनल से:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## सीडर्स

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

एक `Seeder` आपके चल रहे ऐप को एक ज्ञात स्थिति में डालता है -- साइन इन, ऑनबोर्डिंग पूरी, नमूना रिकॉर्ड्स -- और फिर उसे वापस बाहर निकालता है।

<div id="creating-a-seeder"></div>

### एक सीडर बनाना

``` bash
metro make:seeder demo_user
```

| विकल्प | विवरण |
|--------|-------------|
| `--description` | एक-पंक्ति विवरण, जो `seed` द्वारा सीडर्स सूचीबद्ध करते समय दिखाया जाता है |
| `--force`, `-f` | पहले से मौजूद होने पर भी फ़ाइल बनाता है |

यह `lib/app/seeders/` में एक `Seeder` बनाता है, इसे `lib/bootstrap/seeders.dart` में रजिस्टर करता है, और `nylo.configure(...)` में `seeders: seeders` को जोड़ता है:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

हर स्टोरेज और Backpack वैल्यू जिसे `up()` बदलता है, स्वचालित रूप से रिकॉर्ड की जाती है, इसलिए डिफ़ॉल्ट `down()` (`restore()`) हर एक को बिल्कुल वैसे ही वापस रख देता है जैसे वह पहले था, उल्टे क्रम में -- यहां तक कि एक हॉट रीस्टार्ट के पार भी। किसी सीडर के अंदर से दूसरों को उसके हिस्से के रूप में चलाने के लिए `seed(otherSeeders)` को कॉल करें; उनके बदलाव भी रिकॉर्ड और रोलबैक किए जाते हैं।

<div id="seeding-and-rolling-back"></div>

### सीडिंग और रोलिंग बैक

सीडिंग, रोलिंग बैक और एक्सपोर्टिंग केवल `metro live` शेल के अंदर चलते हैं:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

अकेला `seed` रजिस्टर्ड सीडर्स को सूचीबद्ध करता है। `--fresh` सीडिंग से पहले स्टोरेज और Backpack को साफ़ करता है, और `--restart` उसके बाद ऐप को हॉट रीस्टार्ट करता है, ताकि वह पहले से सीडेड होकर शुरू हो:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## स्टोरेज स्नैपशॉट

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export`, ऐप वर्तमान में स्टोरेज और Backpack में जो कुछ भी रखता है, उसे सेव करता है -- एक सीडर के रूप में, या एक पोर्टेबल JSON फ़ाइल के रूप में -- और `seed <file>` इसे वापस लोड करता है।

<div id="exporting-a-snapshot"></div>

### स्नैपशॉट एक्सपोर्ट करना

यह देखने के लिए कि क्या कैप्चर किया जाएगा, इसे बिना नाम के चलाएं:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

इसे `export <name>` के साथ `lib/app/seeders/` में एक सीडर के रूप में सेव करने के लिए नाम दें (`make:seeder` जैसा ही, लेकिन पहले से कैप्चर की गई वैल्यूज़ के साथ भरा हुआ):

``` plaintext
› export pro_user
```

या इसके बजाय इसे एक फ़ाइल में लिखें:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| विकल्प | विवरण |
|--------|-------------|
| `--to <path>` | सीडर की बजाय स्नैपशॉट को इस JSON फ़ाइल में लिखें |
| `--description` | सीडर के लिए एक-पंक्ति विवरण |
| `--only <keys>` | एक्सपोर्ट करने के लिए कॉमा-सेपरेटेड कीज़; `*` किसी भी चीज़ से मेल खाता है, जैसे `--only SK_USER,onboarding_*` |
| `--except <keys>` | छोड़ने के लिए कॉमा-सेपरेटेड कीज़, जैसे `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Backpack वैल्यूज़ शामिल करें (डिफ़ॉल्ट: शामिल) |
| `--force`, `-f` | पहले से मौजूद सीडर या फ़ाइल को बदलें |

एक `Model`, या रजिस्टर्ड डिकोडर वाली कोई भी क्लास, क्लास नाम से टैग की जाती है ताकि वह एक साधारण मैप की बजाय अपने खुद के डिकोडर के माध्यम से वापस आए। `export` तब चेतावनी देता है जब स्नैपशॉट में ऐसी कीज़ होती हैं जो क्रेडेंशियल्स जैसी दिखती हैं, और जब यह इतना बड़ा हो कि आपको संभवतः `--except` की आवश्यकता हो।

<div id="loading-a-snapshot"></div>

### स्नैपशॉट लोड करना

``` plaintext
› seed snapshots/pro_user.json
```

एक फ़ाइल लोड करना उसी तरह रिकॉर्ड की जाती है जैसे एक सीडर होती है, इसलिए `seed:rollback <name>` (फ़ाइल का नाम, या जो भी `--as` ने दिया हो) इसे भी पूर्ववत करता है। कोड से, एक सीडर `importSnapshot` के साथ एक कैप्चर किए गए स्नैपशॉट को लागू कर सकता है:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` बिल्कुल इसी संरचना में लिखता है, इसलिए `--to` के साथ लिखी गई फ़ाइल को सीधे किसी सीडर के `snapshot` फ़ील्ड में पेस्ट किया जा सकता है।

<div id="page-actions"></div>

## पेज एक्शन्स

<x-doc-strip label="आवश्यक" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

किसी पेज पर `path.actions`, इसके बाहर से उस पेज को कॉल करने के लिए एक `PageStateActions` देता है, बिना खुद हाथ से एक `stateAction(...)` कॉल बनाए:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

बिल्ट-इन एक्शन्स के पूरे सेट और अपने खुद के टाइप्ड एक्शन्स कैसे घोषित करें, इसके लिए [एक पेज के एक्शन्स को कॉल करना](/docs/{{ $version }}/state-management#page-actions-shortcut) देखें। Nylo Live उन्हीं एक्शन्स को पढ़ता और उपयोग करता है: `metro live:run data`, स्क्रीन पर मौजूद किसी पेज द्वारा `actions` के अंतर्गत एक्सपोज़ किए गए को सूचीबद्ध करता है, और `metro live:run state`, पेज को सीधे उसके स्टेट नाम से डेटा भेजता है।
