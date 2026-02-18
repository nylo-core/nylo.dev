# Yukseltme Rehberi

---

<a name="section-1"></a>
- [v7'deki Yenilikler](#whats-new "v7'deki Yenilikler")
- [Uyumsuz Degisikliklere Genel Bakis](#breaking-changes "Uyumsuz Degisikliklere Genel Bakis")
- [Onerilen Gecis Yaklasimi](#recommended-migration "Onerilen Gecis Yaklasimi")
- [Hizli Gecis Kontrol Listesi](#checklist "Hizli Gecis Kontrol Listesi")
- [Adim Adim Gecis Rehberi](#migration-guide "Adim Adim Gecis Rehberi")
  - [Adim 1: Bagimliliklari Guncelleme](#step-1-dependencies "Adim 1: Bagimliliklari Guncelleme")
  - [Adim 2: Ortam Yapilandirmasi](#step-2-environment "Adim 2: Ortam Yapilandirmasi")
  - [Adim 3: main.dart Guncelleme](#step-3-main "Adim 3: main.dart Guncelleme")
  - [Adim 4: boot.dart Guncelleme](#step-4-boot "Adim 4: boot.dart Guncelleme")
  - [Adim 5: Yapilandirma Dosyalarini Yeniden Duzenleme](#step-5-config "Adim 5: Yapilandirma Dosyalarini Yeniden Duzenleme")
  - [Adim 6: AppProvider Guncelleme](#step-6-provider "Adim 6: AppProvider Guncelleme")
  - [Adim 7: Theme Yapilandirmasini Guncelleme](#step-7-theme "Adim 7: Theme Yapilandirmasini Guncelleme")
  - [Adim 10: Widget'lari Tasima](#step-10-widgets "Adim 10: Widget'lari Tasima")
  - [Adim 11: Varlik Yollarini Guncelleme](#step-11-assets "Adim 11: Varlik Yollarini Guncelleme")
- [Kaldirilan Ozellikler ve Alternatifler](#removed-features "Kaldirilan Ozellikler ve Alternatifler")
- [Silinen Siniflar Referansi](#deleted-classes "Silinen Siniflar Referansi")
- [Widget Gecis Referansi](#widget-reference "Widget Gecis Referansi")
- [Sorun Giderme](#troubleshooting "Sorun Giderme")


<div id="whats-new"></div>

## v7'deki Yenilikler

{{ config('app.name') }} v7, gelistirici deneyiminde onemli iyilestirmeler iceren buyuk bir surumdur:

### Sifrelenmis Ortam Yapilandirmasi
- Ortam degiskenleri artik guvenlik icin derleme zamaninda XOR ile sifreleniyor
- Yeni `metro make:key` komutu APP_KEY'inizi olusturur
- Yeni `metro make:env` komutu sifrelenmis `env.g.dart` dosyasini olusturur
- CI/CD surecleri icin `--dart-define` ile APP_KEY enjeksiyonu destegi

### Basitlestirilmis Baslatma Sureci
- Yeni `BootConfig` deseni ayri setup/finished callback'lerinin yerini alir
- Sifrelenmis ortam icin `env` parametresiyle daha temiz `Nylo.init()`
- Uygulama yasam dongusu hook'lari dogrudan main.dart'ta

### Yeni nylo.configure() API'si
- Tek bir metot tum uygulama yapilandirmasini birlestirir
- Daha temiz sozdizimi bireysel `nylo.add*()` cagrilarinin yerini alir
- Provider'larda ayri `setup()` ve `boot()` yasam dongusu metotlari

### Sayfalar icin NyPage
- `NyPage`, sayfa widget'lari icin `NyState`'in yerini alir (daha temiz sozdizimi)
- `view()`, `build()` metodunun yerini alir
- `get init =>` getter'i `init()` ve `boot()` metotlarinin yerini alir
- `NyState`, sayfa olmayan stateful widget'lar icin hala kullanilabilir

### LoadingStyle Sistemi
- Tutarli yukleme durumlari icin yeni `LoadingStyle` enum'u
- Secenekler: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- `LoadingStyle.normal(child: ...)` ile ozel yukleme widget'lari

### RouteView Tip Guvenlikli Yonlendirme
- `static RouteView path`, `static const path`'in yerini alir
- Widget factory ile tip guvenlikli route tanimlamalari

### Coklu Theme Destegi
- Birden fazla koyu ve acik theme kaydetme
- Theme ID'leri `.env` dosyasi yerine kodda tanimlanir
- Theme siniflandirmasi icin yeni `NyThemeType.dark` / `NyThemeType.light`
- Tercih edilen theme API'si: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Theme numaralandirmasi: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Yeni Metro Komutlari
- `make:key` - Sifreleme icin APP_KEY olusturur
- `make:env` - Sifrelenmis ortam dosyasi olusturur
- `make:bottom_sheet_modal` - Bottom sheet modal'lari olusturur
- `make:button` - Ozel button'lar olusturur

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Tum degisiklikleri GitHub'da goruntuleyin</a>

<div id="breaking-changes"></div>

## Uyumsuz Degisikliklere Genel Bakis

| Degisiklik | v6 | v7 |
|--------|-----|-----|
| Uygulama Kok Widget'i | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (`NyApp.materialApp()` kullanir) |
| Sayfa State Sinifi | `NyState` | Sayfalar icin `NyPage` |
| View Metodu | `build()` | `view()` |
| Init Metodu | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Route Yolu | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider Boot | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Yapilandirma | Bireysel `nylo.add*()` cagrilari | Tek `nylo.configure()` cagrisi |
| Theme ID'leri | `.env` dosyasi (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Kod (`type: NyThemeType.dark`) |
| Yukleme Widget'i | `useSkeletonizer` + `loading()` | `LoadingStyle` getter |
| Yapilandirma Konumu | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Varlik Konumu | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Onerilen Gecis Yaklasimi

Daha buyuk projeler icin, yeni bir v7 projesi olusturup dosyalari tasimanizi oneririz:

1. Yeni v7 projesi olusturun: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Sayfa, controller, model ve servislerinizi kopyalayin
3. Yukarida gosterildigi gibi sozdizimini guncelleyin
4. Kapsamli bir sekilde test edin

Bu, en son sablon yapisina ve yapilandirmalara sahip olmanizi saglar.

v6 ve v7 arasindaki degisikliklerin bir farkini gormek istiyorsaniz, GitHub'daki karsilastirmayi goruntuleyebilirsiniz: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Hizli Gecis Kontrol Listesi

Gecis ilerlemenizi takip etmek icin bu kontrol listesini kullanin:

- [ ] `pubspec.yaml` guncelle (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] `flutter pub get` calistir
- [ ] APP_KEY olusturmak icin `metro make:key` calistir
- [ ] Sifrelenmis ortam olusturmak icin `metro make:env` calistir
- [ ] `main.dart`'i env parametresi ve BootConfig ile guncelle
- [ ] `Boot` sinifini `BootConfig` desenini kullanacak sekilde donustur
- [ ] Yapilandirma dosyalarini `lib/config/`'den `lib/bootstrap/`'a tasi
- [ ] Yeni yapilandirma dosyalari olustur (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] `AppProvider`'i `nylo.configure()` kullanacak sekilde guncelle
- [ ] `.env`'den `LIGHT_THEME_ID` ve `DARK_THEME_ID`'yi kaldir
- [ ] Koyu theme yapilandirmalarina `type: NyThemeType.dark` ekle
- [ ] Tum sayfa widget'lari icin `NyState`'i `NyPage` olarak yeniden adlandir
- [ ] Tum sayfalarda `build()`'i `view()` olarak degistir
- [ ] Tum sayfalarda `init()/boot()`'u `get init =>` olarak degistir
- [ ] `static const path`'i `static RouteView path` olarak guncelle
- [ ] Route'larda `router.route()`'u `router.add()` olarak degistir
- [ ] Widget'lari yeniden adlandir (NyListView -> CollectionView, vb.)
- [ ] Varliklari `public/`'den `assets/`'e tasi
- [ ] `pubspec.yaml` varlik yollarini guncelle
- [ ] Firebase import'larini kaldir (kullaniyorsaniz - paketleri dogrudan ekleyin)
- [ ] NyDevPanel kullanimini kaldir (Flutter DevTools kullanin)
- [ ] `flutter pub get` calistir ve test et

<div id="migration-guide"></div>

## Adim Adim Gecis Rehberi

<div id="step-1-dependencies"></div>

### Adim 1: Bagimliliklari Guncelleme

`pubspec.yaml` dosyanizi guncelleyin:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... diger bagimliliklar
```

Paketleri guncellemek icin `flutter pub get` calistirin.

<div id="step-2-environment"></div>

### Adim 2: Ortam Yapilandirmasi

v7, gelistirilmis guvenlik icin sifrelenmis ortam degiskenleri gerektirir.

**1. APP_KEY Olusturma:**

``` bash
metro make:key
```

Bu, `.env` dosyaniza `APP_KEY` ekler.

**2. Sifrelenmis env.g.dart Olusturma:**

``` bash
metro make:env
```

Bu, sifrelenmis ortam degiskenlerinizi iceren `lib/bootstrap/env.g.dart` dosyasini olusturur.

**3. Kullanimdan kaldirilan theme degiskenlerini .env'den kaldirin:**

``` bash
# Bu satirlari .env dosyanizdan kaldirin:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Adim 3: main.dart Guncelleme

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // Istege bagli: Uygulama yasam dongusu hook'lari ekleyin
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Temel Degisiklikler:**
- Olusturulan `env.g.dart` dosyasini import edin
- `env` parametresine `Env.get` iletin
- `Boot.nylo` artik `Boot.nylo()` (`BootConfig` dondurur)
- `setupFinished` kaldirildi (`BootConfig` icinde ele aliniyor)
- Uygulama durum degisiklikleri icin istege bagli `appLifecycle` hook'lari

<div id="step-4-boot"></div>

### Adim 4: boot.dart Guncelleme

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**Temel Degisiklikler:**
- `Future<Nylo>` yerine `BootConfig` dondurur
- `setup` ve `finished` tek `BootConfig` nesnesinde birlestirildi
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Adim 5: Yapilandirma Dosyalarini Yeniden Duzenleme

v7, daha iyi yapi icin yapilandirma dosyalarini yeniden duzenler:

| v6 Konumu | v7 Konumu | Islem |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Tasi |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Tasi |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Tasi |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Tasi |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Yeniden Adlandir ve Refaktor Et |
| (yeni) | `lib/config/app.dart` | Olustur |
| (yeni) | `lib/config/toast_notification.dart` | Olustur |

**lib/config/app.dart Olusturma:**

Referans: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // Uygulamanin adi.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // Uygulamanin versiyonu.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Buraya diger uygulama yapilandirmalarini ekleyin
}
```

**lib/config/storage_keys.dart Olusturma:**

Referans: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Boot sirasinda senkronize edilecek anahtarlari tanimlayin
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // kullaniciya varsayilan olarak 10 jeton verin
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Storage key'lerinizi buraya ekleyin...
}
```

**lib/config/toast_notification.dart Olusturma:**

Referans: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Toast stillerini burada ozellestirin
  };
}
```

<div id="step-6-provider"></div>

### Adim 6: AppProvider Guncelleme

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**Temel Degisiklikler:**
- Ilk yapilandirma icin `boot()` artik `setup()` oldu
- `boot()` artik kurulum sonrasi mantik icin kullaniliyor (onceden `afterBoot`)
- Tum `nylo.add*()` cagrilari tek `nylo.configure()` icinde birlestirildi
- Yerellestirme `NyLocalizationConfig` nesnesi kullaniyor

<div id="step-7-theme"></div>

### Adim 7: Theme Yapilandirmasini Guncelleme

**v6 (.env dosyasi):**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart):**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7 (theme.dart):**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**Temel Degisiklikler:**
- `.env`'den `LIGHT_THEME_ID` ve `DARK_THEME_ID`'yi kaldirin
- Theme ID'lerini dogrudan kodda tanimlayin
- Tum koyu theme yapilandirmalarina `type: NyThemeType.dark` ekleyin
- Acik theme'ler varsayilan olarak `NyThemeType.light`

**Yeni Theme API Metotlari (v7):**
``` dart
// Tercih edilen theme'i ayarla ve hatirla
NyTheme.set(context, id: 'dark_theme', remember: true);

// Sistem takibi icin tercih edilen theme'leri ayarla
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Tercih edilen theme ID'lerini al
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Theme numaralandirmasi
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Kaydedilmis tercihleri temizle
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Adim 10: Widget'lari Tasima

#### NyListView -> CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// Sayfalama ile (cekerek yenileme):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField -> InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText -> StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher -> LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### Adim 11: Varlik Yollarini Guncelleme

v7, varlik dizinini `public/`'den `assets/`'e degistirir:

**1. Varlik klasorlerinizi tasiyin:**
``` bash
# Dizinleri tasi
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. pubspec.yaml'i guncelleyin:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. Koddaki varlik referanslarini guncelleyin:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget'i - Kaldirildi

Referans: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Gecis:** `Main(nylo)`'yu dogrudan kullanin. `NyApp.materialApp()` yerellestirmeyi dahili olarak isler.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Silinen Siniflar Referansi

| Silinen Sinif | Alternatif |
|---------------|-------------|
| `NyTextStyle` | Flutter'in `TextStyle`'ini dogrudan kullanin |
| `NyBaseApiService` | `DioApiService` kullanin |
| `BaseColorStyles` | `ThemeColor` kullanin |
| `LocalizedApp` | `Main(nylo)`'yu dogrudan kullanin |
| `NyException` | Standart Dart exception'larini kullanin |
| `PushNotification` | `flutter_local_notifications`'i dogrudan kullanin |
| `PushNotificationAttachments` | `flutter_local_notifications`'i dogrudan kullanin |

<div id="widget-reference"></div>

## Widget Gecis Referansi

### Yeniden Adlandirilan Widget'lar

| v6 Widget | v7 Widget | Notlar |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | `child` yerine `builder` ile yeni API |
| `NyFutureBuilder` | `FutureWidget` | Basitlestirilmis async widget |
| `NyTextField` | `InputField` | `FormValidator` kullanir |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Ayni API |
| `NyRichText` | `StyledText` | Ayni API |
| `NyFader` | `FadeOverlay` | Ayni API |

### Silinen Widget'lar (Dogrudan Alternatifi Yok)

| Silinen Widget | Alternatif |
|----------------|-------------|
| `NyPullToRefresh` | `CollectionView.pullable()` kullanin |

### Widget Gecis Ornekleri

**NyPullToRefresh -> CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader -> FadeOverlay:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## Sorun Giderme

### "Env.get not found" veya "Env is not defined"

**Cozum:** Ortam olusturma komutlarini calistirin:
``` bash
metro make:key
metro make:env
```
Ardindan olusturulan dosyayi `main.dart`'a import edin:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" veya "Dark theme not working"

**Cozum:** Koyu theme'lerin `type: NyThemeType.dark` icerdiginden emin olun:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Bu satiri ekleyin
),
```

### "LocalizedApp not found"

Referans: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Cozum:** `LocalizedApp` kaldirildi. Su sekilde degistirin:
``` dart
// Bundan:
runApp(LocalizedApp(child: Main(nylo)));

// Buna:
runApp(Main(nylo));
```

### "router.route is not defined"

**Cozum:** Bunun yerine `router.add()` kullanin:
``` dart
// Bundan:
router.route(HomePage.path, (context) => HomePage());

// Buna:
router.add(HomePage.path);
```

### "NyListView not found"

**Cozum:** `NyListView` artik `CollectionView`:
``` dart
// Bundan:
NyListView(...)

// Buna:
CollectionView<MyModel>(...)
```

### Varliklar yuklenmiyor (resimler, fontlar)

**Cozum:** Varlik yollarini `public/`'den `assets/`'e guncelleyin:
1. Dosyalari tasiyin: `mv public/* assets/`
2. `pubspec.yaml` yollarini guncelleyin
3. Kod referanslarini guncelleyin

### "init() must return a value of type Future"

**Cozum:** Getter sozdizimne gecin:
``` dart
// Bundan:
@override
init() async { ... }

// Buna:
@override
get init => () async { ... };
```

---

**Yardima mi ihtiyaciniz var?** [Nylo Dokumantasyonu](https://nylo.dev/docs/7.x)'nu kontrol edin veya [GitHub](https://github.com/nylo-core/nylo/issues)'da bir issue acin.
