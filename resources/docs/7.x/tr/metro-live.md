# Metro Live

---

<a name="section-1"></a>
- [Giriş](#introduction "Nylo Live'a giriş")
- metro live Kabuğu
  - [Ortak Seçenekler](#shared-options "Her live komutunun kabul ettiği seçenekler")
  - [Sekme Tamamlama ve Geçmiş](#tab-completion-and-history "Sekme tamamlama ve komut geçmişi")
  - [Bir Betik Çalıştırma](#running-a-script "Bir .live betiği çalıştırma")
- [live:devices](#live-devices "Çalışan uygulamaları listele")
- [live:status](#live-status "Uygulama durumunu göster")
- [live:run](#live-run "Yerleşik bir live komutu çalıştır")
- [Playbook'lar](#playbooks "Playbook'ları Metro'dan oynatma")
- Live Komutlar
  - [Live Komut Oluşturma](#creating-a-live-command "Bir live komutu oluşturma")
  - [Live Komutu Çalıştırma](#running-a-live-command "Bir live komutu çalıştırma")
- Seeder'lar
  - [Seeder Oluşturma](#creating-a-seeder "Bir seeder oluşturma")
  - [Seed Etme ve Geri Alma](#seeding-and-rolling-back "Seed etme ve geri alma")
- Depolama Anlık Görüntüleri
  - [Anlık Görüntü Dışa Aktarma](#exporting-a-snapshot "Bir anlık görüntü dışa aktarma")
  - [Anlık Görüntü Yükleme](#loading-a-snapshot "Bir anlık görüntü yükleme")
- [Sayfa Aksiyonları](#page-actions "Bir sayfayı dışarıdan çağırma")

<div id="introduction"></div>

## Giriş

<!-- uncertain: newly created translation; needs full human review -->

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: "Nylo Live" bu dosyada "Backpack"/"Metro" gibi bir özellik özel adı olarak kabul edilip çevrilmeden bırakıldı; dosyanın devamında "Live" sözcüğü de aynı şekilde ele alındı -->

**Nylo Live**, Metro'nun uygulamanız debug (veya profile) modunda çalışırken Dart VM servisi üzerinden ona erişmesini sağlar. Uygulamayı hiçbir kuruluma gerek kalmadan Dart Tooling Daemon üzerinden bulur ve yalnızca mevcut projeden derlenmiş uygulamalara bağlanır.

Nylo Live, debug ve profile derlemelerinde varsayılan olarak açıktır; bir release derlemesi hiçbir şey kaydetmez. Yalnızca uygulamayı okuyan komutlar (`live:status`, `live:run data`, `live:run routes`, ...) profile derlemelerinde de çalışır. Uygulamayı değiştiren komutlar (`route`, `storage`, `auth`, seeder'lar, ...) bir debug derlemesi gerektirir.

Başka her şey çalışmadan önce, bunu bir provider'dan kendiniz kapatın:

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

## metro live Kabuğu

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Çalışan uygulamanıza bağlı bir kabuk açın:

``` bash
metro live
```

Nylo Live, projenin çalışan uygulamalarını bulur ve birine bağlanır -- birden fazla çalışıyorsa seçmenizi ister. Bağlandıktan sonra, prompt ekrandaki cihazı ve o an açık olan rotayı gösterir:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Live komutları kabuğun içinde `metro live:` önekini kaybeder -- `metro live:status` yerine `status`, `metro live:run route /profile` yerine `route /profile`. Birkaç komut yalnızca kabuğun içinde çalışır: `seed`, `seed:rollback`, `export`, `reload` ve `restart`. Tüm komutları listelemek için `help`, bir komutun seçeneklerini görmek için `help <command>` yazın. Çıkmak için `exit` (veya Ctrl+D) yazın.

Bağlıyken uygulama yeniden başlarsa, kabuk kendiliğinden yeniden bağlanır.

<div id="shared-options"></div>

### Ortak Seçenekler

Her live komutu -- kabuğun içinde veya `metro live:*` olarak -- aynı seçenekleri kabul eder:

| Seçenek | Açıklama |
|--------|-------------|
| `-d, --device <#\|name>` | Uygulamayı `metro live:devices` içindeki numarasıyla veya cihaz adıyla hedefleyin |
| `--all` | Bu projenin çalışan her uygulamasında çalıştırın |
| `--json` | Makine tarafından okunabilir JSON yazdırın |
| `--uri` | Uygulamaları bulmak yerine bu VM servis adresini kullanın |
| `--timeout` | Bulma sırasında her uygulama için beklenecek saniye (varsayılan `3`) |

`@path` olarak yazılan bir seçenek değeri o dosyadan okunur -- büyük bir `--data` yükü için kullanışlıdır:

``` bash
metro live:run route /profile --data @user.json
```

Değer gerçekten `@` ile başlıyorsa, `@@` ile başlatın.

<div id="tab-completion-and-history"></div>

### Sekme Tamamlama ve Geçmiş

Kabuğun içinde Tab tuşu, bağlı uygulamaya göre komut adlarını, seçenekleri, rotaları, storage ve Backpack anahtarlarını, seeder adlarını ve playbook adlarını tamamlar. Geçmiş `.dart_tool/nylo/live_history` içinde tutulur ve oturumlar arasında korunur.

<div id="running-a-script"></div>

### Bir Betik Çalıştırma

Bir dosyayı `metro live`'a yönlendirerek, satır başına bir komut olacak şekilde betik olarak çalıştırın; başarısız olan ilk komutta durur:

``` bash
metro live < seeds/demo.live
```

Boş satırlar ve `#` ile başlayan satırlar atlanır.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Bu projenin çalışan uygulamalarını, `-d` için numaralandırılmış şekilde listeler:

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

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Uygulamayı, cihazı, derleme modunu, mevcut rotayı ve yığını, dili, temayı ve bir kullanıcının oturum açıp açmadığını gösterir:

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

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Uygulamayı inceleyen veya yöneten yerleşik bir komut çalıştırır:

``` bash
metro live:run <command> [arguments]
```

| Komut | Açıklama |
|---------|-------------|
| `data` | Ekrandaki sayfanın verilerini ve alanlarını göster |
| `routes` | Kayıtlı rotaları listele |
| `route` | İsteğe bağlı `--data` ile bir sayfa aç |
| `back` | Bir sayfa geri git, veya bir rotaya geri dön |
| `deeplink` | Bir deep link aç, veya nasıl kurulduklarını göster |
| `storage` | Yerel storage'ı listele, veya bir değeri göster, kaydet ya da sil |
| `storage:clear` | İsteğe bağlı `--keep` ile yerel storage'ı temizle |
| `backpack` | Backpack değerlerini listele, veya birini göster, kaydet ya da sil |
| `auth` | Kimin oturum açtığını göster, veya oturum aç ya da kapat |
| `locale` | Uygulamanın dilini değiştir |
| `theme` | Uygulamanın temasını değiştir |
| `state` | `updateState` ile bir duruma veri gönder |
| `event` | Kendi event'lerinizden birini tetikle |
| `toast` | Bir toast bildirimi göster |
| `playbook` | [Playbook'larınızı](/docs/{{ $version }}/playbooks) oynat veya listele |

`metro live` içinde, `live:run` kısmını atlayın -- `metro live:run route /profile` yerine `route /profile`:

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

`data`, sayfanızın durumunun bildirdiği alanları da çalışan uygulamadan doğrudan okur -- Flutter'da reflection olmadığından, bu yalnızca fiilen ekranda olan bir durum için çalışır:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbook'lar

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Çalışan uygulamanızda bir veya daha fazla [playbook](/docs/{{ $version }}/playbooks) oynatın. Her adım gerçekleştikçe yazdırılır:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Birkaç playbook adı vererek bunları art arda oynatın. Hiç ad verilmezse veya `--list` ile komut, uygulamanın playbook'larını listeler.

| Seçenek | Açıklama |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Her playbook'un kendi hızı yerine bu hızda oynat |
| `--data <json>` | Çalıştırmadaki her playbook'un varsayılanlarının üzerine `data` içinde okuduğu bir JSON nesnesi |
| `--no-overlay` | Geri sayımı ve bunları gösteren playbook'ların parmağını ve başlıklarını gizle |
| `--loop` | Ctrl+C'ye basılana kadar playbook'ları tekrar tekrar oynat |
| `--record[=<file>]` | Çalıştırma boyunca cihazın ekranını kaydet. `build/playbooks/<playbooks>-<device>-<time>.mp4` konumuna kaydedilir; `--record=demo.mp4` (veya `.gif`) projeye göre dosyayı seçer. ffmpeg gerekir |
| `--no-captions` | Kaydın `.vtt` başlık dosyasını ve bölümlerini dışarıda bırak |
| `--clean-status-bar` | Playbook'lar oynarken tam sinyal ve pille 9:41 göster, sonra eski haline getir. `--record` olmadan da çalışır |
| `--countdown <seconds>` | İlk adımdan önce ekranda 0-10 saniye geri say |
| `--list` | Birini oynatmak yerine uygulamanın playbook'larını listele |

Ctrl+C uygulamadan durmasını ister: kalan adımlar atlanır, ancak `tearDown` yine de çalışır. Beklemeyi bırakmak için Ctrl+C'ye ikinci kez basın.

`metro live` kabuğunun içinde komut `playbook <name>`'dir ve Tab playbook adlarını tamamlar:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### Kayıt

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Çalıştırmanın videosunu kaydetmek için `--record` ekleyin:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

`metro live` kabuğunun içinde `playbook sign_up --record` yazın.

Kayıt için ffmpeg gerekir (örneğin `brew install ffmpeg`). Metro, herhangi bir şey oynatılmadan önce bunu kontrol eder.

- **iOS Simulator** -- `xcrun simctl` ile kaydedilir.
- **Android emülatörü veya cihazı** -- 3 dakika sonra duran `adb screenrecord` ile ya da kuruluysa `scrcpy` ile kaydedilir (sınırsızdır ve Android 11+ üzerinde sesi de kaydeder). Birden fazla Android cihazı bağlıysa ve Metro uygulamayı hangisinin çalıştırdığını anlayamıyorsa `ANDROID_SERIAL` değerini ayarlayın.
- **Fiziksel iPhone** -- komut satırından kaydedilemez. QuickTime veya Denetim Merkezi ile kaydedin ve başlamak için süre kazanmak üzere `--countdown 3` kullanın.

Sonuç, çalıştırmayla tam aynı süren bir `.mp4` (H.264, sabit 60 fps) veya 480 piksel genişliğinde 15 fps bir `.gif` dosyasıdır. Her adım, `.mp4` dosyasının yanındaki bir `.vtt` dosyasında başlık, içinde ise bölüm olur. Playbook ekranda başlık göstersin ya da göstermesin, bunlar çalıştırmanın adımlarından üretilir. Atlamak için `--no-captions` verin.

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

Başarısız olan veya durdurulan bir playbook yine de kaydedilir; böylece kayıt aynı zamanda bir hata raporu işlevi görür. Ctrl+C playbook'u durdurur (`tearDown` yine çalışır) ve kayıt yine de kaydedilir.

`--clean-status-bar`, Simulator'da `simctl status_bar` ile zaten ayarladığınız durum çubuğunu korur. Android'de demo modunu kullanır ve sonra kapatır. Birden fazla uygulamada (`--all`) her cihaz, adında cihaz adı bulunan kendi dosyasını alır.

Bir playbook varsayılan olarak parmağını ve başlıklarını gizler. Demo videosu için playbook'ta `showFinger` ve `caption` değerlerini ayarlayın -- bkz. [Parmak ve Başlık](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-live-commands"></div>

### Live Komut Başvurusu

Bunun arkasındaki komutlar `playbooks.list`, `playbooks.run` ve `playbooks.stop`'tur. Bir çalıştırma kimliğini hemen döndürür ve her adımı bir `nylo.playbook` event'i olarak akıtır; böylece bir komutun zaman aşımından uzun sürebilir. `live:status` uygulamanın playbook'larını listeler. Playbook'lar Nylo Live protokolünün 9. sürümünde geldi. Kayıt desteği -- `playbooks.run` içindeki `countdown` (her saniye bir `countdown` event'iyle) ve `status` içindeki `simulator` (iOS Simulator UDID'si) -- protokolün 10. sürümünde geldi.

<div id="live-commands"></div>

## Live Komutlar

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Yukarıdaki yerleşik komutların yanında, uygulamanın içinde çalışacak kendi komutlarınızı kaydedin.

<div id="creating-a-live-command"></div>

### Live Komut Oluşturma

``` bash
metro make:command seed_cart --live
```

| Seçenek | Açıklama |
|--------|-------------|
| `--category`, `-c` | Komutun kategorisi (varsayılan: `app`) |
| `--description` | Komutun `--help`'inde gösterilen tek satırlık açıklama |
| `--force`, `-f` | Dosya zaten mevcut olsa bile oluşturur |

Bu, `lib/app/commands/` içinde bir `LiveCommand` oluşturur, onu `commands.json` içinde `"type": "live"` olarak işaretler, `lib/bootstrap/live_commands.dart` içinde kaydeder ve `app_provider.dart` içindeki `nylo.configure(...)`'a `liveCommands: liveCommands` bağlar:

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

`LiveCommand`, Metro'nun kendi komutlarıyla aynı `builder`/`handle` şeklini paylaşır, ancak `handle` **uygulamanın içinde** çalışır, bu yüzden `NyStorage`, `routeTo`, `Auth` ve modellerinizi kullanabilir. `package:nylo_framework/live.dart`, `LiveCommand`'ı `Seeder`, `StorageSnapshot`, `LiveException` ve `LiveOutput` ile birlikte dışa aktarır.

<div id="running-a-live-command"></div>

### Live Komutu Çalıştırma

Yeni bir live komutunun, çalışan uygulama onu çalıştırabilmeden önce bir hot restart'a ihtiyacı vardır. Ardından, terminalden:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeder'lar

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: yeni Nylo terimi "Seeder" bu dosyada daha önce gecmiyordu; Türkçe karşılığı olmadığından ödünç sözcük olarak, kesme işaretiyle çekimlenerek bırakıldı -->

Bir `Seeder`, çalışan uygulamanızı bilinen bir duruma sokar -- oturum açılmış, onboarding tamamlanmış, örnek kayıtlar -- ve onu tekrar geri çıkarır.

<div id="creating-a-seeder"></div>

### Seeder Oluşturma

``` bash
metro make:seeder demo_user
```

| Seçenek | Açıklama |
|--------|-------------|
| `--description` | `seed` seeder'ları listelerken gösterilen tek satırlık açıklama |
| `--force`, `-f` | Dosya zaten mevcut olsa bile oluşturur |

Bu, `lib/app/seeders/` içinde bir `Seeder` oluşturur, `lib/bootstrap/seeders.dart` içinde kaydeder ve `nylo.configure(...)`'a `seeders: seeders` bağlar:

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

`up()`'ın değiştirdiği her storage ve Backpack değeri otomatik olarak kaydedilir, böylece varsayılan `down()` (`restore()`) her birini -- hot restart'ı bile aşarak -- tam olarak eskisi gibi, ters sırayla geri getirir. Bir seeder'ın içinden başkalarını da onun parçası olarak çalıştırmak için `seed(otherSeeders)` çağırın; onların değişiklikleri de kaydedilir ve geri alınır.

<div id="seeding-and-rolling-back"></div>

### Seed Etme ve Geri Alma

Seed etme, geri alma ve dışa aktarma yalnızca `metro live` kabuğunun içinde çalışır:

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

Tek başına `seed`, kayıtlı seeder'ları listeler. `--fresh`, seed etmeden önce storage ve Backpack'i temizler; `--restart` ise ardından uygulamayı hot restart yapar, böylece uygulama zaten seed edilmiş olarak açılır:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Depolama Anlık Görüntüleri

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: yeni terim "Storage Snapshots", "Depolama Anlık Görüntüleri" olarak çevrildi (anlık görüntü = snapshot); bu dosyada daha önce bir karşılığı yoktu -->

`export`, uygulamanın o an storage ve Backpack'te tuttuğu her şeyi -- bir seeder olarak, veya taşınabilir bir JSON dosyası olarak -- kaydeder; `seed <file>` ise bunu geri yükler.

<div id="exporting-a-snapshot"></div>

### Anlık Görüntü Dışa Aktarma

Neyin yakalanacağının önizlemesini görmek için adsız çalıştırın:

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

`lib/app/seeders/` içinde bir seeder olarak kaydetmek için ona bir ad verin (`make:seeder` ile aynı, ama zaten yakalanan değerlerle doldurulmuş olarak):

``` plaintext
› export pro_user
```

Ya da bunun yerine bir dosyaya yazın:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Seçenek | Açıklama |
|--------|-------------|
| `--to <path>` | Anlık görüntüyü bir seeder yerine bu JSON dosyasına yaz |
| `--description` | Seeder için tek satırlık açıklama |
| `--only <keys>` | Dışa aktarılacak, virgülle ayrılmış anahtarlar; `*` her şeyle eşleşir, örn. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Dışarıda bırakılacak, virgülle ayrılmış anahtarlar, örn. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Backpack değerlerini dahil et (varsayılan: dahil) |
| `--force`, `-f` | Zaten var olan bir seeder'ın veya dosyanın yerini al |

Bir `Model`, veya kayıtlı bir decoder'a sahip herhangi bir sınıf, sınıf adıyla etiketlenir; böylece düz bir map yerine kendi decoder'ından geri gelir. `export`, anlık görüntü kimlik bilgilerine benzeyen anahtarlar içerdiğinde ve muhtemelen `--except` kullanmanız gerekecek kadar büyük olduğunda uyarır.

<div id="loading-a-snapshot"></div>

### Anlık Görüntü Yükleme

``` plaintext
› seed snapshots/pro_user.json
```

Bir dosyayı yüklemek, bir seeder ile aynı şekilde kaydedilir, böylece `seed:rollback <name>` (dosya adı, veya `--as` ile verilen ad) bunu da geri alır. Kod tarafında, bir seeder yakalanmış bir anlık görüntüyü `importSnapshot` ile uygulayabilir:

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

`export` tam olarak bu şekli yazar, böylece `--to` ile yazılan bir dosya doğrudan bir seeder'ın `snapshot` alanına yapıştırılabilir.

<div id="page-actions"></div>

## Sayfa Aksiyonları

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Bir sayfadaki `path.actions`, `stateAction(...)` çağrısını elle oluşturmadan o sayfayı dışarıdan çağırmak için size bir `PageStateActions` verir:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Yerleşik aksiyonların tam listesini ve kendi tipli aksiyonlarınızı nasıl tanımlayacağınızı görmek için [Sayfa Aksiyonlarını Çağırma](/docs/{{ $version }}/state-management#page-actions-shortcut) sayfasına bakın. Nylo Live aynı aksiyonları okur ve kullanır: `metro live:run data`, ekrandaki bir sayfanın `actions` altında neler sunduğunu listeler; `metro live:run state` ise bir sayfaya verileri doğrudan durumunun adıyla gönderir.
