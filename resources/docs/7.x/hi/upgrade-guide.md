# अपग्रेड गाइड

---

<a name="section-1"></a>
- [v7 में नया क्या है](#whats-new "v7 में नया क्या है")
- [ब्रेकिंग चेंजेस का अवलोकन](#breaking-changes "ब्रेकिंग चेंजेस का अवलोकन")
- [अनुशंसित माइग्रेशन दृष्टिकोण](#recommended-migration "अनुशंसित माइग्रेशन दृष्टिकोण")
- [त्वरित माइग्रेशन चेकलिस्ट](#checklist "त्वरित माइग्रेशन चेकलिस्ट")
- [चरण-दर-चरण माइग्रेशन गाइड](#migration-guide "माइग्रेशन गाइड")
  - [चरण 1: Dependencies अपडेट करें](#step-1-dependencies "Dependencies अपडेट करें")
  - [चरण 2: Environment कॉन्फ़िगरेशन](#step-2-environment "Environment कॉन्फ़िगरेशन")
  - [चरण 3: main.dart अपडेट करें](#step-3-main "main.dart अपडेट करें")
  - [चरण 4: boot.dart अपडेट करें](#step-4-boot "boot.dart अपडेट करें")
  - [चरण 5: कॉन्फ़िगरेशन फाइलों को पुनर्गठित करें](#step-5-config "कॉन्फ़िगरेशन फाइलों को पुनर्गठित करें")
  - [चरण 6: AppProvider अपडेट करें](#step-6-provider "AppProvider अपडेट करें")
  - [चरण 7: Theme कॉन्फ़िगरेशन अपडेट करें](#step-7-theme "Theme कॉन्फ़िगरेशन अपडेट करें")
  - [चरण 10: Widgets माइग्रेट करें](#step-10-widgets "Widgets माइग्रेट करें")
  - [चरण 11: Asset पथ अपडेट करें](#step-11-assets "Asset पथ अपडेट करें")
- [हटाई गई सुविधाएं और विकल्प](#removed-features "हटाई गई सुविधाएं और विकल्प")
- [हटाई गई Classes का संदर्भ](#deleted-classes "हटाई गई Classes का संदर्भ")
- [Widget माइग्रेशन संदर्भ](#widget-reference "Widget माइग्रेशन संदर्भ")
- [समस्या निवारण](#troubleshooting "समस्या निवारण")


<div id="whats-new"></div>

## v7 में नया क्या है

{{ config('app.name') }} v7 डेवलपर अनुभव में महत्वपूर्ण सुधारों के साथ एक प्रमुख रिलीज़ है:

### एन्क्रिप्टेड Environment कॉन्फ़िगरेशन
- Environment variables अब सुरक्षा के लिए build time पर XOR-encrypted होते हैं
- नया `metro make:key` आपका APP_KEY जेनरेट करता है
- नया `metro make:env` एन्क्रिप्टेड `env.g.dart` जेनरेट करता है
- CI/CD pipelines के लिए `--dart-define` APP_KEY इंजेक्शन का समर्थन

### सरलीकृत Boot प्रक्रिया
- नया `BootConfig` पैटर्न अलग setup/finished callbacks को बदलता है
- एन्क्रिप्टेड environment के लिए `env` पैरामीटर के साथ साफ-सुथरा `Nylo.init()`
- main.dart में सीधे App lifecycle hooks

### नया nylo.configure() API
- एकल method सभी app कॉन्फ़िगरेशन को समेकित करता है
- साफ-सुथरा syntax अलग-अलग `nylo.add*()` calls को बदलता है
- providers में अलग `setup()` और `boot()` lifecycle methods

### Pages के लिए NyPage
- `NyPage` page widgets के लिए `NyState` को बदलता है (साफ-सुथरा syntax)
- `view()` `build()` method को बदलता है
- `get init =>` getter `init()` और `boot()` methods को बदलता है
- `NyState` अभी भी non-page stateful widgets के लिए उपलब्ध है

### LoadingStyle सिस्टम
- सुसंगत loading states के लिए नया `LoadingStyle` enum
- विकल्प: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- `LoadingStyle.normal(child: ...)` के माध्यम से custom loading widgets

### RouteView Type-Safe Routing
- `static RouteView path` `static const path` को बदलता है
- widget factory के साथ Type-safe route definitions

### Multi-Theme समर्थन
- कई dark और light themes रजिस्टर करें
- Theme IDs `.env` फाइल के बजाय code में परिभाषित
- Theme वर्गीकरण के लिए नया `NyThemeType.dark` / `NyThemeType.light`
- Preferred theme API: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Theme enumeration: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### नए Metro Commands
- `make:key` - एन्क्रिप्शन के लिए APP_KEY जेनरेट करें
- `make:env` - एन्क्रिप्टेड environment फाइल जेनरेट करें
- `make:bottom_sheet_modal` - bottom sheet modals बनाएं
- `make:button` - custom buttons बनाएं

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">GitHub पर सभी परिवर्तन देखें</a>

<div id="breaking-changes"></div>

## ब्रेकिंग चेंजेस का अवलोकन

| परिवर्तन | v6 | v7 |
|--------|-----|-----|
| App Root Widget | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (`NyApp.materialApp()` का उपयोग करता है) |
| Page State Class | `NyState` | Pages के लिए `NyPage` |
| View Method | `build()` | `view()` |
| Init Method | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Route Path | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider Boot | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configuration | अलग-अलग `nylo.add*()` calls | एकल `nylo.configure()` call |
| Theme IDs | `.env` फाइल (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Code (`type: NyThemeType.dark`) |
| Loading Widget | `useSkeletonizer` + `loading()` | `LoadingStyle` getter |
| Config Location | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Asset Location | `public/` | `assets/` |

<div id="recommended-migration"></div>

## अनुशंसित माइग्रेशन दृष्टिकोण

बड़े projects के लिए, हम एक ताज़ा v7 project बनाने और फाइलों को माइग्रेट करने की सलाह देते हैं:

1. नया v7 project बनाएं: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. अपने pages, controllers, models, और services कॉपी करें
3. ऊपर दिखाए अनुसार syntax अपडेट करें
4. अच्छी तरह से टेस्ट करें

यह सुनिश्चित करता है कि आपके पास सभी नवीनतम boilerplate structure और configurations हैं।

यदि आप v6 और v7 के बीच परिवर्तनों का diff देखना चाहते हैं, तो आप GitHub पर तुलना देख सकते हैं: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## त्वरित माइग्रेशन चेकलिस्ट

अपनी माइग्रेशन प्रगति को ट्रैक करने के लिए इस चेकलिस्ट का उपयोग करें:

- [ ] `pubspec.yaml` अपडेट करें (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] `flutter pub get` चलाएं
- [ ] APP_KEY जेनरेट करने के लिए `metro make:key` चलाएं
- [ ] एन्क्रिप्टेड environment जेनरेट करने के लिए `metro make:env` चलाएं
- [ ] `main.dart` को env पैरामीटर और BootConfig के साथ अपडेट करें
- [ ] `Boot` class को `BootConfig` पैटर्न उपयोग करने के लिए कनवर्ट करें
- [ ] config फाइलों को `lib/config/` से `lib/bootstrap/` में ले जाएं
- [ ] नई config फाइलें बनाएं (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] `AppProvider` को `nylo.configure()` उपयोग करने के लिए अपडेट करें
- [ ] `.env` से `LIGHT_THEME_ID` और `DARK_THEME_ID` हटाएं
- [ ] Dark theme configurations में `type: NyThemeType.dark` जोड़ें
- [ ] सभी page widgets के लिए `NyState` को `NyPage` में रीनेम करें
- [ ] सभी pages में `build()` को `view()` में बदलें
- [ ] सभी pages में `init()/boot()` को `get init =>` में बदलें
- [ ] `static const path` को `static RouteView path` में अपडेट करें
- [ ] routes में `router.route()` को `router.add()` में बदलें
- [ ] widgets को रीनेम करें (NyListView → CollectionView, आदि)
- [ ] assets को `public/` से `assets/` में ले जाएं
- [ ] `pubspec.yaml` asset paths अपडेट करें
- [ ] Firebase imports हटाएं (यदि उपयोग कर रहे हैं - packages सीधे जोड़ें)
- [ ] NyDevPanel उपयोग हटाएं (Flutter DevTools उपयोग करें)
- [ ] `flutter pub get` चलाएं और टेस्ट करें

<div id="migration-guide"></div>

## चरण-दर-चरण माइग्रेशन गाइड

<div id="step-1-dependencies"></div>

### चरण 1: Dependencies अपडेट करें

अपना `pubspec.yaml` अपडेट करें:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... अन्य dependencies
```

packages अपडेट करने के लिए `flutter pub get` चलाएं।

<div id="step-2-environment"></div>

### चरण 2: Environment कॉन्फ़िगरेशन

v7 को बेहतर सुरक्षा के लिए एन्क्रिप्टेड environment variables की आवश्यकता है।

**1. APP_KEY जेनरेट करें:**

``` bash
metro make:key
```

यह आपकी `.env` फाइल में `APP_KEY` जोड़ता है।

**2. एन्क्रिप्टेड env.g.dart जेनरेट करें:**

``` bash
metro make:env
```

यह आपके एन्क्रिप्टेड environment variables वाला `lib/bootstrap/env.g.dart` बनाता है।

**3. .env से deprecated theme variables हटाएं:**

``` bash
# अपनी .env फाइल से ये लाइनें हटाएं:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### चरण 3: main.dart अपडेट करें

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
      // वैकल्पिक: App lifecycle hooks जोड़ें
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**मुख्य परिवर्तन:**
- जेनरेट किया गया `env.g.dart` import करें
- `env` पैरामीटर को `Env.get` पास करें
- `Boot.nylo` अब `Boot.nylo()` है (`BootConfig` लौटाता है)
- `setupFinished` हटा दिया गया (`BootConfig` के भीतर संभाला जाता है)
- App state परिवर्तनों के लिए वैकल्पिक `appLifecycle` hooks

<div id="step-4-boot"></div>

### चरण 4: boot.dart अपडेट करें

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

**मुख्य परिवर्तन:**
- `Future<Nylo>` के बजाय `BootConfig` लौटाता है
- `setup` और `finished` एकल `BootConfig` object में संयुक्त
- `getEnv('SHOW_SPLASH_SCREEN')` → `AppConfig.showSplashScreen`
- `bootApplication` → `setupApplication`

<div id="step-5-config"></div>

### चरण 5: कॉन्फ़िगरेशन फाइलों को पुनर्गठित करें

v7 बेहतर संरचना के लिए कॉन्फ़िगरेशन फाइलों को पुनर्गठित करता है:

| v6 स्थान | v7 स्थान | कार्रवाई |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | स्थानांतरित करें |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | स्थानांतरित करें |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | स्थानांतरित करें |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | स्थानांतरित करें |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | रीनेम और रीफैक्टर करें |
| (नया) | `lib/config/app.dart` | बनाएं |
| (नया) | `lib/config/toast_notification.dart` | बनाएं |

**lib/config/app.dart बनाएं:**

संदर्भ: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // एप्लिकेशन का नाम।
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // एप्लिकेशन का संस्करण।
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // अन्य app कॉन्फ़िगरेशन यहां जोड़ें
}
```

**lib/config/storage_keys.dart बनाएं:**

संदर्भ: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // बूट पर सिंक होने वाली keys को परिभाषित करें
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // उपयोगकर्ता को डिफ़ॉल्ट रूप से 10 coins दें
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// अपनी storage keys यहां जोड़ें...
}
```

**lib/config/toast_notification.dart बनाएं:**

संदर्भ: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // toast styles यहां customize करें
  };
}
```

<div id="step-6-provider"></div>

### चरण 6: AppProvider अपडेट करें

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

**मुख्य परिवर्तन:**
- `boot()` अब प्रारंभिक कॉन्फ़िगरेशन के लिए `setup()` है
- `boot()` अब post-setup logic के लिए उपयोग होता है (पहले `afterBoot`)
- सभी `nylo.add*()` calls एकल `nylo.configure()` में समेकित
- Localization `NyLocalizationConfig` object का उपयोग करता है

<div id="step-7-theme"></div>

### चरण 7: Theme कॉन्फ़िगरेशन अपडेट करें

**v6 (.env फाइल):**
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

**मुख्य परिवर्तन:**
- `.env` से `LIGHT_THEME_ID` और `DARK_THEME_ID` हटाएं
- Theme IDs सीधे code में परिभाषित करें
- सभी dark theme configurations में `type: NyThemeType.dark` जोड़ें
- Light themes डिफ़ॉल्ट रूप से `NyThemeType.light` होते हैं

**नए Theme API Methods (v7):**
``` dart
// Preferred theme सेट करें और याद रखें
NyTheme.set(context, id: 'dark_theme', remember: true);

// System following के लिए preferred themes सेट करें
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Preferred theme IDs प्राप्त करें
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Theme enumeration
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// सहेजी गई preferences साफ़ करें
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### चरण 10: Widgets माइग्रेट करें

#### NyListView → CollectionView

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

// Pagination के साथ (pull to refresh):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder → FutureWidget

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

#### NyTextField → InputField

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

#### NyRichText → StyledText

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

#### NyLanguageSwitcher → LanguageSwitcher

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

### चरण 11: Asset पथ अपडेट करें

v7 asset directory को `public/` से `assets/` में बदलता है:

**1. अपने asset folders स्थानांतरित करें:**
``` bash
# directories स्थानांतरित करें
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. pubspec.yaml अपडेट करें:**

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

**3. code में किसी भी asset references को अपडेट करें:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget - हटाया गया

संदर्भ: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**माइग्रेशन:** सीधे `Main(nylo)` का उपयोग करें। `NyApp.materialApp()` आंतरिक रूप से localization को संभालता है।

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## हटाई गई Classes का संदर्भ

| हटाई गई Class | विकल्प |
|---------------|-------------|
| `NyTextStyle` | सीधे Flutter के `TextStyle` का उपयोग करें |
| `NyBaseApiService` | `DioApiService` उपयोग करें |
| `BaseColorStyles` | `ThemeColor` उपयोग करें |
| `LocalizedApp` | सीधे `Main(nylo)` उपयोग करें |
| `NyException` | मानक Dart exceptions उपयोग करें |
| `PushNotification` | सीधे `flutter_local_notifications` उपयोग करें |
| `PushNotificationAttachments` | सीधे `flutter_local_notifications` उपयोग करें |

<div id="widget-reference"></div>

## Widget माइग्रेशन संदर्भ

### रीनेम किए गए Widgets

| v6 Widget | v7 Widget | नोट्स |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | `child` के बजाय `builder` के साथ नया API |
| `NyFutureBuilder` | `FutureWidget` | सरलीकृत async widget |
| `NyTextField` | `InputField` | `FormValidator` उपयोग करता है |
| `NyLanguageSwitcher` | `LanguageSwitcher` | समान API |
| `NyRichText` | `StyledText` | समान API |
| `NyFader` | `FadeOverlay` | समान API |

### हटाए गए Widgets (कोई सीधा प्रतिस्थापन नहीं)

| हटाया गया Widget | विकल्प |
|----------------|-------------|
| `NyPullToRefresh` | `CollectionView.pullable()` उपयोग करें |

### Widget माइग्रेशन उदाहरण

**NyPullToRefresh → CollectionView.pullable():**

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

**NyFader → FadeOverlay:**

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

## समस्या निवारण

### "Env.get not found" या "Env is not defined"

**समाधान:** environment generation commands चलाएं:
``` bash
metro make:key
metro make:env
```
फिर जेनरेट की गई फाइल को `main.dart` में import करें:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" या "Dark theme not working"

**समाधान:** सुनिश्चित करें कि dark themes में `type: NyThemeType.dark` है:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // यह लाइन जोड़ें
),
```

### "LocalizedApp not found"

संदर्भ: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**समाधान:** `LocalizedApp` को हटा दिया गया है। बदलें:
``` dart
// इससे:
runApp(LocalizedApp(child: Main(nylo)));

// इसमें:
runApp(Main(nylo));
```

### "router.route is not defined"

**समाधान:** इसके बजाय `router.add()` उपयोग करें:
``` dart
// इससे:
router.route(HomePage.path, (context) => HomePage());

// इसमें:
router.add(HomePage.path);
```

### "NyListView not found"

**समाधान:** `NyListView` अब `CollectionView` है:
``` dart
// इससे:
NyListView(...)

// इसमें:
CollectionView<MyModel>(...)
```

### Assets लोड नहीं हो रहे (images, fonts)

**समाधान:** asset paths को `public/` से `assets/` में अपडेट करें:
1. फाइलें स्थानांतरित करें: `mv public/* assets/`
2. `pubspec.yaml` paths अपडेट करें
3. code references अपडेट करें

### "init() must return a value of type Future"

**समाधान:** getter syntax में बदलें:
``` dart
// इससे:
@override
init() async { ... }

// इसमें:
@override
get init => () async { ... };
```

---

**मदद चाहिए?** [Nylo Documentation](https://nylo.dev/docs/7.x) देखें या [GitHub](https://github.com/nylo-core/nylo/issues) पर एक issue खोलें।
