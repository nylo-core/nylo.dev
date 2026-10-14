# Playbook'lar
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giriş](#introduction "Playbook'lara giriş")
- [Playbook Oluşturma](#creating-a-playbook "Bir playbook oluşturma")
- [Adımlar](#steps "Bir playbook'un gerçekleştirebileceği adımlar")
- [Hedefler](#targets "Ekrandaki öğeleri bulma")
  - [Düz Metinler](#plain-strings "Düz bir metnin nasıl eşleştirildiği")
  - [Find](#find "Find ile kesin hedefler")
- [Veri ve Hazırlık](#data-and-setup "Veri, setUp ve tearDown")
- [Diğer Playbook'ları Oynatma](#playing-other-playbooks "Bir playbook'u başka bir playbook'tan çalıştırma")
- [Hız](#speed "PlaybookSpeed")
- [Parmak ve Başlık](#finger-and-caption "Parmak ve adım başlığı")
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
| `Find.icon(icon)` | Bir simge |
| `Find.type<T>()` | `T` türünde bir widget |
| `Find.key(key)` | Anahtarı olan bir widget |
| `Find.field(name)` | Ada göre bir `NyForm` alanı |
| `Find.input(text)` | İpucu, etiket veya placeholder'a göre bir metin alanı |
| `Find.tooltip(message)` | Bir tooltip |
| `Find.semantics(label)` | Bir semantics etiketi |
| `Find.where(test)` | Testinizi geçen widget'lar |

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

`metro live` içinde `playbook sign_up` yazın. Tüm seçenekler için [Metro Live](/docs/{{ $version }}/metro-live#playbooks) bölümüne bakın. Çalıştırmanın videosunu kaydetmek için `--record` ekleyin; bkz. [Kayıt](/docs/{{ $version }}/metro-live#recording-playbooks).

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

`run()`, `speed`, `data`, `clock`, `overlay` (varsayılan `true`) ve `onEvent` kabul eder. Başarısız bir adım hata fırlatmaz -- `report.passed` değerini kontrol edin. Aynı anda yalnızca bir playbook çalışabilir; birini diğerinin içinde çalıştırmak için `play()` kullanın.

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
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Entegrasyon testleri için [Playbook Testi](/docs/{{ $version }}/testing#testing-playbooks) bölümüne bakın.
