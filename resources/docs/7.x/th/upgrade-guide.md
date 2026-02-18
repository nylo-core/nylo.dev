# Upgrade Guide

---

<a name="section-1"></a>
- [มีอะไรใหม่ใน v7](#whats-new "มีอะไรใหม่ใน v7")
- [ภาพรวม Breaking Change](#breaking-changes "ภาพรวม Breaking Change")
- [แนวทางการย้ายระบบที่แนะนำ](#recommended-migration "แนวทางการย้ายระบบที่แนะนำ")
- [รายการตรวจสอบการย้ายระบบอย่างรวดเร็ว](#checklist "รายการตรวจสอบการย้ายระบบอย่างรวดเร็ว")
- [คู่มือการย้ายระบบทีละขั้นตอน](#migration-guide "คู่มือการย้ายระบบ")
  - [ขั้นตอนที่ 1: อัปเดต Dependency](#step-1-dependencies "อัปเดต Dependency")
  - [ขั้นตอนที่ 2: การกำหนดค่า Environment](#step-2-environment "การกำหนดค่า Environment")
  - [ขั้นตอนที่ 3: อัปเดต main.dart](#step-3-main "อัปเดต main.dart")
  - [ขั้นตอนที่ 4: อัปเดต boot.dart](#step-4-boot "อัปเดต boot.dart")
  - [ขั้นตอนที่ 5: จัดระเบียบไฟล์กำหนดค่า](#step-5-config "จัดระเบียบไฟล์กำหนดค่า")
  - [ขั้นตอนที่ 6: อัปเดต AppProvider](#step-6-provider "อัปเดต AppProvider")
  - [ขั้นตอนที่ 7: อัปเดตการกำหนดค่าธีม](#step-7-theme "อัปเดตการกำหนดค่าธีม")
  - [ขั้นตอนที่ 10: ย้าย Widget](#step-10-widgets "ย้าย Widget")
  - [ขั้นตอนที่ 11: อัปเดตเส้นทาง Asset](#step-11-assets "อัปเดตเส้นทาง Asset")
- [ฟีเจอร์ที่ถูกลบและทางเลือก](#removed-features "ฟีเจอร์ที่ถูกลบและทางเลือก")
- [อ้างอิงคลาสที่ถูกลบ](#deleted-classes "อ้างอิงคลาสที่ถูกลบ")
- [อ้างอิงการย้าย Widget](#widget-reference "อ้างอิงการย้าย Widget")
- [การแก้ไขปัญหา](#troubleshooting "การแก้ไขปัญหา")


<div id="whats-new"></div>

## มีอะไรใหม่ใน v7

{{ config('app.name') }} v7 เป็น major release ที่มีการปรับปรุงประสบการณ์นักพัฒนาอย่างมาก:

### การกำหนดค่า Environment แบบเข้ารหัส
- ตัวแปร environment ถูกเข้ารหัส XOR ในขั้นตอน build เพื่อความปลอดภัย
- `metro make:key` ใหม่ สร้าง APP_KEY ของคุณ
- `metro make:env` ใหม่ สร้าง `env.g.dart` ที่เข้ารหัส
- รองรับการ inject APP_KEY ด้วย `--dart-define` สำหรับ CI/CD pipeline

### กระบวนการ Boot ที่ง่ายขึ้น
- รูปแบบ `BootConfig` ใหม่แทนที่ callback setup/finished แยกกัน
- `Nylo.init()` ที่สะอาดขึ้นพร้อมพารามิเตอร์ `env` สำหรับ environment ที่เข้ารหัส
- App lifecycle hook โดยตรงใน main.dart

### API nylo.configure() ใหม่
- เมธอดเดียวรวบรวมการกำหนดค่าแอปทั้งหมด
- ไวยากรณ์ที่สะอาดขึ้นแทนที่การเรียก `nylo.add*()` แต่ละรายการ
- เมธอด lifecycle `setup()` และ `boot()` แยกกันใน provider

### NyPage สำหรับหน้าเพจ
- `NyPage` แทนที่ `NyState` สำหรับ page widget (ไวยากรณ์ที่สะอาดขึ้น)
- `view()` แทนที่เมธอด `build()`
- getter `get init =>` แทนที่เมธอด `init()` และ `boot()`
- `NyState` ยังคงใช้ได้สำหรับ stateful widget ที่ไม่ใช่หน้าเพจ

### ระบบ LoadingStyle
- enum `LoadingStyle` ใหม่สำหรับ loading state ที่สม่ำเสมอ
- ตัวเลือก: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- loading widget กำหนดเองผ่าน `LoadingStyle.normal(child: ...)`

### RouteView สำหรับ Type-Safe Routing
- `static RouteView path` แทนที่ `static const path`
- การกำหนดเส้นทางแบบ type-safe พร้อม widget factory

### รองรับหลายธีม
- ลงทะเบียน dark theme และ light theme หลายแบบ
- กำหนด Theme ID ในโค้ดแทนไฟล์ `.env`
- `NyThemeType.dark` / `NyThemeType.light` ใหม่ สำหรับจำแนกธีม
- API ธีมที่ต้องการ: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- การแจกแจงธีม: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### คำสั่ง Metro ใหม่
- `make:key` - สร้าง APP_KEY สำหรับการเข้ารหัส
- `make:env` - สร้างไฟล์ environment ที่เข้ารหัส
- `make:bottom_sheet_modal` - สร้าง bottom sheet modal
- `make:button` - สร้างปุ่มกำหนดเอง

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">ดูการเปลี่ยนแปลงทั้งหมดบน GitHub</a>

<div id="breaking-changes"></div>

## ภาพรวม Breaking Change

| การเปลี่ยนแปลง | v6 | v7 |
|--------|-----|-----|
| App Root Widget | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (ใช้ `NyApp.materialApp()`) |
| Page State Class | `NyState` | `NyPage` สำหรับหน้าเพจ |
| View Method | `build()` | `view()` |
| Init Method | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Route Path | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider Boot | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configuration | การเรียก `nylo.add*()` แต่ละรายการ | การเรียก `nylo.configure()` เดียว |
| Theme ID | ไฟล์ `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | โค้ด (`type: NyThemeType.dark`) |
| Loading Widget | `useSkeletonizer` + `loading()` | getter `LoadingStyle` |
| Config Location | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Asset Location | `public/` | `assets/` |

<div id="recommended-migration"></div>

## แนวทางการย้ายระบบที่แนะนำ

สำหรับโปรเจกต์ขนาดใหญ่ เราแนะนำให้สร้างโปรเจกต์ v7 ใหม่และย้ายไฟล์:

1. สร้างโปรเจกต์ v7 ใหม่: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. คัดลอกหน้าเพจ, controller, model และ service ของคุณ
3. อัปเดตไวยากรณ์ตามที่แสดงด้านบน
4. ทดสอบอย่างละเอียด

สิ่งนี้ทำให้แน่ใจว่าคุณมีโครงสร้าง boilerplate และการกำหนดค่าล่าสุดทั้งหมด

หากคุณสนใจดู diff ของการเปลี่ยนแปลงระหว่าง v6 และ v7 คุณสามารถดูการเปรียบเทียบบน GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## รายการตรวจสอบการย้ายระบบอย่างรวดเร็ว

ใช้รายการตรวจสอบนี้เพื่อติดตามความคืบหน้าการย้ายระบบ:

- [ ] อัปเดต `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] รัน `flutter pub get`
- [ ] รัน `metro make:key` เพื่อสร้าง APP_KEY
- [ ] รัน `metro make:env` เพื่อสร้าง environment ที่เข้ารหัส
- [ ] อัปเดต `main.dart` พร้อมพารามิเตอร์ env และ BootConfig
- [ ] แปลงคลาส `Boot` ให้ใช้รูปแบบ `BootConfig`
- [ ] ย้ายไฟล์ config จาก `lib/config/` ไปยัง `lib/bootstrap/`
- [ ] สร้างไฟล์ config ใหม่ (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] อัปเดต `AppProvider` ให้ใช้ `nylo.configure()`
- [ ] ลบ `LIGHT_THEME_ID` และ `DARK_THEME_ID` จาก `.env`
- [ ] เพิ่ม `type: NyThemeType.dark` ในการกำหนดค่า dark theme
- [ ] เปลี่ยนชื่อ `NyState` เป็น `NyPage` สำหรับ page widget ทั้งหมด
- [ ] เปลี่ยน `build()` เป็น `view()` ในทุกหน้าเพจ
- [ ] เปลี่ยน `init()/boot()` เป็น `get init =>` ในทุกหน้าเพจ
- [ ] อัปเดต `static const path` เป็น `static RouteView path`
- [ ] เปลี่ยน `router.route()` เป็น `router.add()` ใน route
- [ ] เปลี่ยนชื่อ widget (NyListView -> CollectionView ฯลฯ)
- [ ] ย้าย asset จาก `public/` ไปยัง `assets/`
- [ ] อัปเดตเส้นทาง asset ใน `pubspec.yaml`
- [ ] ลบ Firebase import (หากใช้ - เพิ่มแพ็คเกจโดยตรง)
- [ ] ลบการใช้ NyDevPanel (ใช้ Flutter DevTools)
- [ ] รัน `flutter pub get` และทดสอบ

<div id="migration-guide"></div>

## คู่มือการย้ายระบบทีละขั้นตอน

<div id="step-1-dependencies"></div>

### ขั้นตอนที่ 1: อัปเดต Dependency

อัปเดต `pubspec.yaml` ของคุณ:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... dependency อื่นๆ
```

รัน `flutter pub get` เพื่ออัปเดตแพ็คเกจ

<div id="step-2-environment"></div>

### ขั้นตอนที่ 2: การกำหนดค่า Environment

v7 ต้องการตัวแปร environment ที่เข้ารหัสเพื่อปรับปรุงความปลอดภัย

**1. สร้าง APP_KEY:**

``` bash
metro make:key
```

สิ่งนี้จะเพิ่ม `APP_KEY` ลงในไฟล์ `.env` ของคุณ

**2. สร้าง env.g.dart ที่เข้ารหัส:**

``` bash
metro make:env
```

สิ่งนี้จะสร้าง `lib/bootstrap/env.g.dart` ที่มีตัวแปร environment ที่เข้ารหัสของคุณ

**3. ลบตัวแปรธีมที่เลิกใช้จาก .env:**

``` bash
# ลบบรรทัดเหล่านี้จากไฟล์ .env:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### ขั้นตอนที่ 3: อัปเดต main.dart

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
      // ตัวเลือก: เพิ่ม app lifecycle hook
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**การเปลี่ยนแปลงสำคัญ:**
- Import `env.g.dart` ที่สร้างขึ้น
- ส่ง `Env.get` ไปยังพารามิเตอร์ `env`
- `Boot.nylo` ตอนนี้เป็น `Boot.nylo()` (ส่งกลับ `BootConfig`)
- `setupFinished` ถูกลบออก (จัดการภายใน `BootConfig`)
- `appLifecycle` hook ที่เป็นตัวเลือกสำหรับการเปลี่ยนแปลง state ของแอป

<div id="step-4-boot"></div>

### ขั้นตอนที่ 4: อัปเดต boot.dart

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

**การเปลี่ยนแปลงสำคัญ:**
- ส่งกลับ `BootConfig` แทน `Future<Nylo>`
- `setup` และ `finished` รวมเข้าเป็นออบเจ็กต์ `BootConfig` เดียว
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### ขั้นตอนที่ 5: จัดระเบียบไฟล์กำหนดค่า

v7 จัดระเบียบไฟล์กำหนดค่าเพื่อโครงสร้างที่ดีขึ้น:

| ตำแหน่ง v6 | ตำแหน่ง v7 | การดำเนินการ |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | ย้าย |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | ย้าย |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | ย้าย |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | ย้าย |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | เปลี่ยนชื่อ & ปรับโครงสร้าง |
| (ใหม่) | `lib/config/app.dart` | สร้าง |
| (ใหม่) | `lib/config/toast_notification.dart` | สร้าง |

**สร้าง lib/config/app.dart:**

อ้างอิง: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // ชื่อของแอปพลิเคชัน
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // เวอร์ชันของแอปพลิเคชัน
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // เพิ่มการกำหนดค่าแอปอื่นๆ ที่นี่
}
```

**สร้าง lib/config/storage_keys.dart:**

อ้างอิง: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // กำหนดคีย์ที่คุณต้องการซิงค์เมื่อ boot
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // ให้ผู้ใช้มี 10 coins เป็นค่าเริ่มต้น
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// เพิ่ม storage key ของคุณที่นี่...
}
```

**สร้าง lib/config/toast_notification.dart:**

อ้างอิง: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // กำหนดสไตล์ toast ที่นี่
  };
}
```

<div id="step-6-provider"></div>

### ขั้นตอนที่ 6: อัปเดต AppProvider

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

**การเปลี่ยนแปลงสำคัญ:**
- `boot()` ตอนนี้เป็น `setup()` สำหรับการกำหนดค่าเริ่มต้น
- `boot()` ตอนนี้ใช้สำหรับลอจิกหลังการ setup (เดิมคือ `afterBoot`)
- การเรียก `nylo.add*()` ทั้งหมดรวมเป็น `nylo.configure()` เดียว
- Localization ใช้ออบเจ็กต์ `NyLocalizationConfig`

<div id="step-7-theme"></div>

### ขั้นตอนที่ 7: อัปเดตการกำหนดค่าธีม

**v6 (ไฟล์ .env):**
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

**การเปลี่ยนแปลงสำคัญ:**
- ลบ `LIGHT_THEME_ID` และ `DARK_THEME_ID` จาก `.env`
- กำหนด theme ID โดยตรงในโค้ด
- เพิ่ม `type: NyThemeType.dark` ในการกำหนดค่า dark theme ทั้งหมด
- Light theme มีค่าเริ่มต้นเป็น `NyThemeType.light`

**เมธอด Theme API ใหม่ (v7):**
``` dart
// ตั้งค่าและจำธีมที่ต้องการ
NyTheme.set(context, id: 'dark_theme', remember: true);

// ตั้งค่าธีมที่ต้องการสำหรับการตามระบบ
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// ดึง ID ธีมที่ต้องการ
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// การแจกแจงธีม
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// ล้างการตั้งค่าที่บันทึก
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### ขั้นตอนที่ 10: ย้าย Widget

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

// พร้อม pagination (ดึงเพื่อรีเฟรช):
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

### ขั้นตอนที่ 11: อัปเดตเส้นทาง Asset

v7 เปลี่ยนไดเรกทอรี asset จาก `public/` เป็น `assets/`:

**1. ย้ายโฟลเดอร์ asset ของคุณ:**
``` bash
# ย้ายไดเรกทอรี
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. อัปเดต pubspec.yaml:**

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

**3. อัปเดตการอ้างอิง asset ในโค้ด:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget - ถูกลบ

อ้างอิง: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**การย้าย:** ใช้ `Main(nylo)` โดยตรง `NyApp.materialApp()` จัดการ localization ภายใน

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## อ้างอิงคลาสที่ถูกลบ

| คลาสที่ถูกลบ | ทางเลือก |
|---------------|-------------|
| `NyTextStyle` | ใช้ `TextStyle` ของ Flutter โดยตรง |
| `NyBaseApiService` | ใช้ `DioApiService` |
| `BaseColorStyles` | ใช้ `ThemeColor` |
| `LocalizedApp` | ใช้ `Main(nylo)` โดยตรง |
| `NyException` | ใช้ exception มาตรฐานของ Dart |
| `PushNotification` | ใช้ `flutter_local_notifications` โดยตรง |
| `PushNotificationAttachments` | ใช้ `flutter_local_notifications` โดยตรง |

<div id="widget-reference"></div>

## อ้างอิงการย้าย Widget

### Widget ที่เปลี่ยนชื่อ

| Widget v6 | Widget v7 | หมายเหตุ |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | API ใหม่พร้อม `builder` แทน `child` |
| `NyFutureBuilder` | `FutureWidget` | async widget ที่ง่ายขึ้น |
| `NyTextField` | `InputField` | ใช้ `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | API เดิม |
| `NyRichText` | `StyledText` | API เดิม |
| `NyFader` | `FadeOverlay` | API เดิม |

### Widget ที่ถูกลบ (ไม่มีตัวแทนโดยตรง)

| Widget ที่ถูกลบ | ทางเลือก |
|----------------|-------------|
| `NyPullToRefresh` | ใช้ `CollectionView.pullable()` |

### ตัวอย่างการย้าย Widget

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

**NyFader -> AnimatedOpacity:**

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

## การแก้ไขปัญหา

### "Env.get not found" หรือ "Env is not defined"

**วิธีแก้ไข:** รันคำสั่งสร้าง environment:
``` bash
metro make:key
metro make:env
```
จากนั้น import ไฟล์ที่สร้างขึ้นใน `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" หรือ "Dark theme not working"

**วิธีแก้ไข:** ตรวจสอบให้แน่ใจว่า dark theme มี `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // เพิ่มบรรทัดนี้
),
```

### "LocalizedApp not found"

อ้างอิง: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**วิธีแก้ไข:** `LocalizedApp` ถูกลบแล้ว เปลี่ยน:
``` dart
// จาก:
runApp(LocalizedApp(child: Main(nylo)));

// เป็น:
runApp(Main(nylo));
```

### "router.route is not defined"

**วิธีแก้ไข:** ใช้ `router.add()` แทน:
``` dart
// จาก:
router.route(HomePage.path, (context) => HomePage());

// เป็น:
router.add(HomePage.path);
```

### "NyListView not found"

**วิธีแก้ไข:** `NyListView` ตอนนี้เป็น `CollectionView`:
``` dart
// จาก:
NyListView(...)

// เป็น:
CollectionView<MyModel>(...)
```

### Asset ไม่โหลด (รูปภาพ, ฟอนต์)

**วิธีแก้ไข:** อัปเดตเส้นทาง asset จาก `public/` เป็น `assets/`:
1. ย้ายไฟล์: `mv public/* assets/`
2. อัปเดตเส้นทางใน `pubspec.yaml`
3. อัปเดตการอ้างอิงในโค้ด

### "init() must return a value of type Future"

**วิธีแก้ไข:** เปลี่ยนเป็นไวยากรณ์ getter:
``` dart
// จาก:
@override
init() async { ... }

// เป็น:
@override
get init => () async { ... };
```

---

**ต้องการความช่วยเหลือ?** ดู [เอกสาร Nylo](https://nylo.dev/docs/7.x) หรือเปิด issue บน [GitHub](https://github.com/nylo-core/nylo/issues)
