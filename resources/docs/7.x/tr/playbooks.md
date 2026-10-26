# Playbook'lar
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giriş](#introduction "Playbook'lara giriş")
- [Playbook Oluşturma](#creating-a-playbook "Bir playbook oluşturma")
- [Playbook Kaydetme](#recording-a-playbook "Çalışan uygulamadan bir playbook kaydetme")
- [Adımlar](#steps "Bir playbook'un gerçekleştirebileceği adımlar")
- [Hedefler](#targets "Ekrandaki öğeleri bulma")
  - [Düz Metinler](#plain-strings "Düz bir metnin nasıl eşleştirildiği")
  - [Find](#find "Find ile kesin hedefler")
- [Veri ve Hazırlık](#data-and-setup "Veri, setUp ve tearDown")
- [Diğer Playbook'ları Oynatma](#playing-other-playbooks "Bir playbook'u başka bir playbook'tan çalıştırma")
- [Hız](#speed "PlaybookSpeed")
- [Parmak ve Başlık](#finger-and-caption "Parmak ve adım başlığı")
- [Ekran Görüntüleri](#screenshots "Bir playbook sırasında ekran görüntüsü yakalama")
  - [Diller ve Temalar](#screenshots-locales-and-themes "Bir ekranı her dilde ve temada yakalama")
  - [Koddan Toplama](#collecting-screenshots "onScreenshot ile ekran görüntüleri toplama")
- [Playbook'ları Kaydetme](#registering-playbooks "Playbook'ları Nylo'ya kaydetme")
- [Bir Playbook Çalıştırma](#running-a-playbook "Bir playbook çalıştırma")
  - [Metro'dan](#running-from-metro "Metro'dan çalıştırma")
  - [Açılışta](#running-on-boot "Açılışta çalıştırma")
  - [Dart'tan](#running-from-dart "Dart'tan çalıştırma")
- [Raporlar](#reports "PlaybookReport")
- [Release Derlemeleri](#release-builds "Release derlemelerinde playbook'lar")
- [Test Etme](#testing "Bir playbook'u test etme")

<div id="introduction"></div>

## Giriş

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**Playbook**, bir kullanıcı yolculuğunun dokunuşlarını, yazmalarını, kaydırmalarını ve sayfa değişikliklerini listeleyen bir sınıftır. Çalışan uygulamanız bunları kendi üzerinde, bir parmak ve her adım için bir başlık gösterebilir gerçekleştirir -- demolar, ekran kayıtları ve bir yolculuğu baştan sona kontrol etmek için kullanışlıdır.

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

Playbook yazdığınız veya kaydettiğiniz her yerde `package:nylo_framework/playbook.dart` dosyasını içe aktarın.

<div id="creating-a-playbook"></div>

## Playbook Oluşturma

Bir tane oluşturmak için Metro'yu kullanın:

``` bash
metro make:playbook sign_up
```

Bu, `lib/app/playbooks/sign_up_playbook.dart` dosyasını oluşturur ve uygulamanıza kaydeder. Komutun seçenekleri için [Playbook olusturma](/docs/{{ $version }}/metro#make-playbook) bölümüne bakın.

Router'ınızın eklediği her sayfadan başlamak için `--from-routes` verin:

``` bash
metro make:playbook tour --from-routes
```

Metro her sayfa için bir `visit()` ve bir [`screenshot()`](#screenshots) yazar, böylece tek bir çalıştırma tüm uygulamanızı fotoğraflar.

<div id="recording-a-playbook"></div>

## Playbook Kaydetme

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Adımları yazmak yerine kaydedin. Uygulamanız debug modunda çalışırken `metro live` açın ve şunu yazın:

``` plaintext
› record sign_up
```

Ardından uygulamayı kullanın. Her dokunuş, uzun basış, yazılan alan, kaydırma ve sayfa değişikliği olduğu anda bir adım olarak yazılır ve ilerledikçe yazdırılır:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Bitirmek için Enter'a basın. Playbook, `make:playbook`'un oluşturduğu gibi düzenlenmiş olarak `lib/app/playbooks/sign_up_playbook.dart` dosyasına yazılır ve kaydedilir; böylece uygulama yeniden başlar başlamaz oynar. `metro make:playbook sign_up --record` aynısını terminalden yapar: `metro live` açar ve kaydı hemen başlatır.

Kayıt sırasında bir tuşa yazıp Enter'a basın:

| Tuş | Açıklama |
|-----|-------------|
| `a` | Bir sonraki dokunuş dokunuş yerine `see(...)` olarak yazılır |
| `d` | Bir sonraki dokunuş `dontSee(...)` olarak yazılır |
| `s <title>` | Bu başlıkla bir `scene` başlatır; sonraki adımlar onun içine yerleşir |
| `u` | Son adımı kaldırır |
| `p` | Kaydı duraklatır, örn. gerçek kimlik bilgileriyle oturum açmak için; tekrar `p` devam ettirir |
| Enter | Bitirir ve playbook'u yazar |
| Ctrl+C | Kaydı iptal eder |

Hedefler, oynatıcının okuduğu şekilde adlandırılır, önce görünür metin gelir ve her ad yazılmadan önce çalışan uygulamaya karşı kontrol edilir; böylece kaydedilen bir adım yeniden oynatılır. Düz bir metin birden fazla widget ile eşleştiğinde adım bunu `Find` ile daraltır: `tap(Find.text('Add').at(1))`. Metni veya tooltip'i olmayan bir simge düğmesi `tap(Find.icon(Icons.add))` olur. Hiçbir şeyin adlandırmadığı bir widget, oluşturulduğu dosya ve satırı içeren bir `// TODO` yorumunun altında `tap(Find.type<GestureDetector>().at(3))` olarak yazılır ve Metro kayıt bittiğinde bunu listeler. Ona bir `Key` verin, ardından anahtarı kullanın.

- **Yazma**, `defaults` içinde yazılan değerle birlikte bir `data[...]` yer tutucusunu okuyan bir `type` adımı olur. Klavyenin eylem tuşuyla bırakılan bir alan `submit: true` alır. Gizlenmiş bir alan `secret: true`, değeri `defaults` içinde ve commit etmeden önce değiştirmeniz için bir uyarı alır. Değerleri yazıldığı gibi yazmak için `--no-data` verin.
- Bir dokunuştan sonraki **sayfa değişiklikleri**, sayfalarınızdan biri route'u tanımlıyorsa `waitForPage(SignUpPage.path)`, aksi halde `waitForPage('/sign-up')` olur. Geri gitmek `back()` olur.
- **Kaydırma**, bir sonraki dokunduğunuz şey üzerinde `scrollTo(...)` olarak yazılır ve yalnızca kaydırma onu ağaca getirdiyse yazılır. Oynatıcı geri kalan her şeyi kendisi ortaya çıkarır.
- Kaydırma olmayan **sürüklemeler** -- bir `Dismissible`, bir `PageView`, bir `Slider` -- için henüz playbook fiili yoktur, bu yüzden başlangıç ve bitiş noktalarıyla bir `// TODO` yorumu olarak bırakılır.

Kayıt bir debug derlemesi gerektirir ve simülatörlerde, emülatörlerde ve cihazlarda aynı şekilde çalışır. Bir playbook'un kendi dokunuşları asla kaydedilmez. Komutun seçenekleri için bkz. [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey).

<div id="steps"></div>

## Adımlar

Bir playbook'un `steps` listesi, miras aldığı fiillerle oluşturulur:

| Adım | Açıklama |
|------|-------------|
| `tap(target)` | Bir hedefe dokun |
| `longPress(target)` | Bir hedefe basılı tut |
| `type(target, text)` | Bir alana yaz. `clear: false` değilse önce alanı temizler; `submit: true` klavyenin eylem düğmesine basar; `secret: true` metni başlıkta ve raporda gizler |
| `scrollTo(target)` | Bir hedef ekrana gelene kadar kaydır. Belirli bir kaydırılabilir alanı kaydırmak için `within:` verin |
| `see(target)` | Bir hedefin ekranda olduğunu kontrol et, görünmesini bekleyerek |
| `dontSee(target)` | Bir hedefin ekranda olmadığını kontrol et, kaybolmasını bekleyerek |
| `waitFor(target)` | Bir hedefi `timeout` süresine kadar bekle (varsayılan 10 saniye) |
| `waitForPage(path)` | Bir rota yolundaki veya `RouteView` olan sayfayı bekle (örn. `HomePage.path`) |
| `visit(path)` | Bir sayfayı rota yolu veya `RouteView` ile aç, isteğe bağlı `data:` ile |
| `back()` | Bir sayfa geri git |
| `pause(seconds: 1)` | Hareketsiz bekle, örn. demonun nefes alması için |
| `step(title, action)` | Aynı fiillerle herhangi bir kodu bir `PlaybookActor` aracılığıyla çalıştır |
| `scene(title, steps)` | Adımları bir başlık altında grupla; başlıkları onunla başlar ve sonuçları raporda iç içe görünür |
| `screenshot(name)` | Ekranı yakala -- bkz. [Ekran Görüntüleri](#screenshots) |
| `play(playbook)` | Başka bir playbook çalıştır -- bkz. [Diğer Playbook'ları Oynatma](#playing-other-playbooks) |

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

Adımlar sırayla çalışır ve ilk başarısızlıkta durur. Kalan adımlar atlandı olarak raporlanır.

<div id="targets"></div>

## Hedefler

Çoğu adım bir hedef alır: neye dokunulacağı, nereye yazılacağı veya neyin aranacağı.

<div id="plain-strings"></div>

### Düz Metinler

Düz bir metin, bir insanın ekranı okuduğu şekilde eşleştirilir. Sırayla şunları dener:

1. Görünür metin
2. Bir alanın ipucu veya etiketi
3. Bir tooltip
4. Bir semantics etiketi
5. Bir `ValueKey`
6. Metni içeren metin

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

Bir `Key`, bir `Type` veya bir `IconData` da doğrudan bir adıma verilebilir.

<div id="find"></div>

### Find

Düz bir metin yeterince kesin olmadığında `Find` kullanın:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Bulucu | Eşleşir |
|--------|---------|
| `Find.text(text)` | Görünür metin |
| `Find.textContaining(text)` | Metni içeren metin |
| `Find.icon(icon, label:)` | Bir simge. `label:` adım başlıklarında ona bir ad verir |
| `Find.type<T>()` | `T` türünde bir widget |
| `Find.key(key)` | Anahtarı olan bir widget |
| `Find.field(name)` | Ada göre bir `NyForm` alanı |
| `Find.input(text)` | İpucu, etiket veya placeholder'a göre bir metin alanı |
| `Find.tooltip(message)` | Bir tooltip |
| `Find.semantics(label)` | Bir semantics etiketi |
| `Find.where(test)` | Testinizi geçen widget'lar |

Bir simgeye `label` verin; adım başlıkları `Tap icon f37d` yerine `Tap the bag icon` olarak okunur:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

Herhangi bir bulucuyu `.inside(ancestor)`, `.at(index)` (0'dan başlayarak sayılır), `.first` veya `.last` ile daraltın.

Bir diyalog bariyeri gibi ilgisiz bir şey hedefi kapattığında dokunuş reddedilir.

<div id="data-and-setup"></div>

## Veri ve Hazırlık

`defaults`, adımların başladığı veriyi tutar. Bunu `steps` içinde `data` üzerinden okuyun. `setUp` adımlardan önce, `tearDown` ise bir adım başarısız olsa bile adımlardan sonra çalışır:

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

`seed([...])`, [Seeder'ları](/docs/{{ $version }}/metro-live#seeders) (örnekler veya kayıtlı adlar) çalıştırır ve `metro live` içindeki `seed` gibi kaydeder.

<div id="playing-other-playbooks"></div>

## Diğer Playbook'ları Oynatma

`play()`, başka bir playbook'u kendi `setUp` ve `tearDown`'ı ile tamamen çalıştırır. Bir örnek veya kayıtlı olduğu adı verin:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Veri** şu sırayla birleşir: çocuğun `defaults` değerleri, sonra ebeveynin `data` değeri, sonra `play`'e verilen `data:`.
- **Hız**, `speed:` vermediğiniz sürece ebeveynden miras alınır.
- **Sonuçlar** raporda adımın altında iç içe görünür.
- **Başarısız bir çocuk** adımı başarısız kılar.
- **Döngüler** reddedilir ve playbook'lar en fazla 8 seviye iç içe geçebilir.

<div id="speed"></div>

## Hız

`PlaybookSpeed`, her beklemenin temposunu belirler:

| Hız | Açıklama |
|-------|-------------|
| `PlaybookSpeed.instant` | Kaydırmanın gerektirdiği dışında bekleme yok. Testler için tasarlanmıştır |
| `PlaybookSpeed.fast` | Hızlı, ama yine de takip etmesi kolay |
| `PlaybookSpeed.natural` | Uygulamayı kullanan bir kişinin temposu. Varsayılan |
| `PlaybookSpeed.slow` | Üzerine anlatım yapmaya yetecek kadar yavaş |

Herhangi bir hızı `.times(n)` ile ölçekleyin:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // iki kat daha yavaş
```

`run()`'a verilen bir hız, Metro'dan `--speed` veya bu playbook'u oynatan bir playbook'un hızı, playbook'un kendi hızından önceliklidir.

<div id="finger-and-caption"></div>

## Parmak ve Başlık

Parmak ve adım başlıkları varsayılan olarak kapalıdır. Bunları açmak için `showFinger` ve `caption` değerlerini geçersiz kılın:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // veya .top
```

`showFinger` varsayılan olarak `false`, `caption` ise `PlaybookCaption.hidden` değerindedir; `PlaybookCaption`, `top`, `bottom` veya `hidden` olabilir. Dokunuşlar ve kaydırmalar her iki durumda da gerçekleşir. Bu playbook'un `play()` ile oynattığı bir playbook kendi ayarını kullanır ve bu playbook'unki, o bitince geri gelir. Bir çalıştırma için ikisini de gizlemek üzere Metro'dan `--no-overlay` veya Dart'tan `run(overlay: false)` kullanın.

nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0) öncesinde parmak ve alt başlık varsayılan olarak açıktı. Bu görünümü korumak için yukarıdaki geçersiz kılmaları ekleyin.

<div id="screenshots"></div>

## Ekran Görüntüleri

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Ekranın saklamaya değer olduğu her yere bir `screenshot` adımı ekleyin:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` her birini, hiçbir bayrak gerekmeden `screenshots/<playbook>/<name>.png` olarak kaydeder. Koddan yapılan bir çalıştırma bunları [`onScreenshot`](#collecting-screenshots) ile toplar ve `PLAYBOOK_ON_BOOT` gibi diğer her çalıştırma yalnızca ekranın oturmasını bekler. Bir ad bir harf veya rakamla başlar ve harfler, rakamlar, `-`, `_` ve `.` kullanır.

| Parametre | Açıklama |
|-----------|-------------|
| `name` | Ekran görüntüsünün kaydedildiği ad |
| `locales` | Bu dil kodlarının her birinde bir kez yakala. `Screenshot.allLocales` uygulamanın paketlediği her dildir |
| `themes` | Bu tema kimliklerinin her birinde bir kez yakala. `Screenshot.allThemes` kayıtlı her temadır |
| `caption` | Çalıştırma ekran görüntülerini çerçevelediğinde ekranın üstündeki metin veya bir çeviri anahtarı |
| `native` | iOS Simulator ve Android'de Metro ile yakala (varsayılan `true`) |

iOS Simulator ve Android'de Metro cihazın tüm ekranını yakalar, dolayısıyla durum çubuğu ve klavye dahildir; bunu yaparken parmak ve başlık gizlenir. Tam sinyal ve bataryayla 9:41 için çalıştırmaya `--clean-status-bar` ekleyin. Başka yerlerde ve `--store` ile `--frame` çalıştırmalarında uygulama kendi ekranını yakalar. Her zaman uygulamanın yakalamasını kullanmak için `native: false` verin:

``` dart
screenshot('checkout', native: false),
```

Her adımın ekranını kaydetmek için bunun yerine `metro live:run playbook <name> --screenshots` çalıştırın. Ekran görüntülerini App Store ve Google Play için boyutlandıran seçenekler için [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) bölümüne, arkalarındaki `Screenshot` sınıfı için [Ekran Görüntüleri](/docs/{{ $version }}/screenshots) bölümüne bakın.

<div id="screenshots-locales-and-themes"></div>

### Diller ve Temalar

Aynı ekranı her birinde bir kez yakalamak için bir `screenshot` adımına `locales`, `themes` veya ikisini birden verin:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

Uygulama sayfayı ve durumunu koruyarak yerinde değişir ve sonraki adımdan önce geri döner. Her yakalama, dilinin ve temasının adını taşıyan bir klasöre kaydedilir:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Bunun yerine bir playbook'un tamamını her dilde ve temada oynatmak için `metro live:run playbook` komutuna `--locales` ve `--themes` verin.

<div id="collecting-screenshots"></div>

### Koddan Toplama

`run()`, bir çalıştırmanın ekran görüntülerini `PlaybookShot` olarak almak için `onScreenshot` kabul eder; bu bir test veya kendi araçlarınız için kullanışlıdır:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` hangi ekranların yakalanacağını seçer:

| Mod | Yakaladığı |
|------|----------|
| `PlaybookScreenshots.off` | Hiçbir şey |
| `PlaybookScreenshots.failures` | Yalnızca başarısız bir adımdaki ekran |
| `PlaybookScreenshots.marked` | `screenshot()` adımları ve başarısız adımlar |
| `PlaybookScreenshots.auto` | Herhangi biri olan bir playbook'un `screenshot()` adımları ve hiçbiri olmayanın her adımı. Başarısız adımlar da. Varsayılan |
| `PlaybookScreenshots.all` | Her adım, `screenshot()` adımları ve başarısız adımlar |

`pixelRatio`, ekran görüntülerini cihazınkine göre boyutlandırır (varsayılan `1.0`). Her `PlaybookShot`, [kaydedilecek](/docs/{{ $version }}/screenshots#saving-a-screenshot) veya `bytes` okunacak bir `screenshot`'a, bir `kind`'a ve alındığı `name`, `locale` ve `theme` değerlerine sahiptir.

<div id="registering-playbooks"></div>

## Playbook'ları Kaydetme

Metro ve `play('name')` bulabilsin diye playbook'larınızı kaydedin:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` kayıtlı olanları döndürür. Bunları `AppProvider`'ınızda Nylo'yu yapılandırırken de verebilirsiniz:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` bunu sizin için yapar. Kayıt defterini `lib/bootstrap/playbooks.dart` içinde tutar, her yeni playbook'u ona ekler ve `playbooks` değerini `nylo.configure(...)` çağrısına iletir. Release derlemelerinde kayıt defteri boş bir map'tir:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Kayıtlı bir playbook kendi anahtarı altında çalışır. Kayıtsız olan ise `name` değeri veya snake case sınıf adı altında çalışır (`SignUpPlaybook`, `sign_up` olur).

<div id="running-a-playbook"></div>

## Bir Playbook Çalıştırma

<div id="running-from-metro"></div>

### Metro'dan

Uygulamanız debug modunda çalışırken:

``` bash
metro live:run playbook sign_up --speed fast
```

`metro live` içinde `playbook sign_up` yazın. Tüm seçenekler için [Metro Live](/docs/{{ $version }}/metro-live#playbooks) bölümüne bakın. Çalıştırmanın videosunu kaydetmek için `--record` ekleyin; bkz. [Kayıt](/docs/{{ $version }}/metro-live#recording-playbooks). Çalıştırma 20 saniye sonra beklemeyi bırakırsa uygulama büyük olasılıkla `flutter run --no-dds` ile başlatılmıştır; bkz. [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

<div id="running-on-boot"></div>

### Açılışta

Uygulama ekrana gelir gelmez playbook'ları oynatmak için bu anahtarları `.env` dosyanıza ekleyin:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` virgülle ayrılmış bir playbook adı listesidir ve `PLAYBOOK_SPEED`, `instant`, `fast`, `natural` veya `slow` değerlerinden biridir. İkisi de yalnızca debug ve profile derlemelerinde geçerlidir.

<div id="running-from-dart"></div>

### Dart'tan

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()`, `speed`, `data`, `clock`, `overlay` (varsayılan `true`), `onEvent` ve [ekran görüntüsü toplamak](#collecting-screenshots) için `onScreenshot`, `screenshots` ve `pixelRatio` kabul eder. Başarısız bir adım hata fırlatmaz -- `report.passed` değerini kontrol edin. Aynı anda yalnızca bir playbook çalışabilir; birini diğerinin içinde çalıştırmak için `play()` kullanın.

Çalışan bir playbook'u `Playbook.stop()` ile durdurun. Kalan adımlar atlanır ama `tearDown` yine de çalışır. `Playbook.isRunning`, birinin çalışıp çalışmadığını söyler.

<div id="reports"></div>

## Raporlar

`run()`, playbook'un `name`, `speed`, geçen süre olan `elapsed`, `stopped` olup olmadığı ve `PlaybookStepResult` öğelerinden oluşan bir `steps` listesi (her birinde `title`, `status`, `elapsed`, `error` ve iç içe `steps` bulunur) ile bir `PlaybookReport` döndürür. `report.passed`, her adım geçtiğinde true olur ve bir raporu yazdırmak Metro'nun çıktısı gibi okunur. `report.toJson()` aynı veriyi JSON olarak verir.

<div id="release-builds"></div>

## Release Derlemeleri

Playbook'lar bir release derlemesinde asla çalışmaz, böylece bir yolculuk bir kullanıcının uygulamasını yönetemez:

- `Playbook.run()` bir `UnsupportedError` fırlatır.
- `addPlaybooks` yok sayılır.
- Metro'nun oluşturduğu `lib/bootstrap/playbooks.dart` kayıt defteri boştur.

`Playbook.isAvailable`, geçerli derlemenin playbook çalıştırıp çalıştıramayacağını söyler.

<div id="testing"></div>

## Test Etme

Bir playbook'u widget testinde `NyTesterClock` ile çalıştırın, ardından raporunu `expectPlaybookPassed` ile doğrulayın:

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Entegrasyon testleri için [Playbook Testi](/docs/{{ $version }}/testing#testing-playbooks) bölümüne bakın.
