# Panduan Upgrade

---

<a name="section-1"></a>
- [Yang Baru di v7](#whats-new "Yang Baru di v7")
- [Ikhtisar Perubahan yang Memutus Kompatibilitas](#breaking-changes "Ikhtisar Perubahan yang Memutus Kompatibilitas")
- [Pendekatan Migrasi yang Direkomendasikan](#recommended-migration "Pendekatan Migrasi yang Direkomendasikan")
- [Daftar Periksa Migrasi Cepat](#checklist "Daftar Periksa Migrasi Cepat")
- [Panduan Migrasi Langkah demi Langkah](#migration-guide "Panduan Migrasi")
  - [Langkah 1: Perbarui Dependensi](#step-1-dependencies "Perbarui Dependensi")
  - [Langkah 2: Konfigurasi Environment](#step-2-environment "Konfigurasi Environment")
  - [Langkah 3: Perbarui main.dart](#step-3-main "Perbarui main.dart")
  - [Langkah 4: Perbarui boot.dart](#step-4-boot "Perbarui boot.dart")
  - [Langkah 5: Reorganisasi File Konfigurasi](#step-5-config "Reorganisasi File Konfigurasi")
  - [Langkah 6: Perbarui AppProvider](#step-6-provider "Perbarui AppProvider")
  - [Langkah 7: Perbarui Konfigurasi Theme](#step-7-theme "Perbarui Konfigurasi Theme")
  - [Langkah 10: Migrasi Widget](#step-10-widgets "Migrasi Widget")
  - [Langkah 11: Perbarui Path Asset](#step-11-assets "Perbarui Path Asset")
- [Fitur yang Dihapus & Alternatif](#removed-features "Fitur yang Dihapus & Alternatif")
- [Referensi Class yang Dihapus](#deleted-classes "Referensi Class yang Dihapus")
- [Referensi Migrasi Widget](#widget-reference "Referensi Migrasi Widget")
- [Pemecahan Masalah](#troubleshooting "Pemecahan Masalah")


<div id="whats-new"></div>

## Yang Baru di v7

{{ config('app.name') }} v7 adalah rilis major dengan peningkatan signifikan pada pengalaman developer:

### Konfigurasi Environment Terenkripsi
- Variabel environment sekarang dienkripsi dengan XOR pada saat build untuk keamanan
- `metro make:key` baru untuk menghasilkan APP_KEY Anda
- `metro make:env` baru untuk menghasilkan `env.g.dart` yang terenkripsi
- Dukungan untuk injeksi APP_KEY via `--dart-define` untuk pipeline CI/CD

### Proses Boot yang Disederhanakan
- Pola `BootConfig` baru menggantikan callback setup/finished yang terpisah
- `Nylo.init()` yang lebih bersih dengan parameter `env` untuk environment terenkripsi
- Hook lifecycle aplikasi langsung di main.dart

### API nylo.configure() Baru
- Satu method mengkonsolidasikan semua konfigurasi aplikasi
- Sintaks yang lebih bersih menggantikan panggilan `nylo.add*()` individual
- Method lifecycle `setup()` dan `boot()` terpisah di provider

### NyPage untuk Page
- `NyPage` menggantikan `NyState` untuk widget page (sintaks lebih bersih)
- `view()` menggantikan method `build()`
- Getter `get init =>` menggantikan method `init()` dan `boot()`
- `NyState` masih tersedia untuk widget stateful non-page

### Sistem LoadingStyle
- Enum `LoadingStyle` baru untuk state loading yang konsisten
- Opsi: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Widget loading kustom via `LoadingStyle.normal(child: ...)`

### RouteView Type-Safe Routing
- `static RouteView path` menggantikan `static const path`
- Definisi route yang type-safe dengan widget factory

### Dukungan Multi-Theme
- Daftarkan beberapa theme dark dan light
- ID theme didefinisikan dalam kode, bukan file `.env`
- `NyThemeType.dark` / `NyThemeType.light` baru untuk klasifikasi theme
- API preferred theme: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Enumerasi theme: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Perintah Metro Baru
- `make:key` - Hasilkan APP_KEY untuk enkripsi
- `make:env` - Hasilkan file environment terenkripsi
- `make:bottom_sheet_modal` - Buat bottom sheet modal
- `make:button` - Buat button kustom

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Lihat semua perubahan di GitHub</a>

<div id="breaking-changes"></div>

## Ikhtisar Perubahan yang Memutus Kompatibilitas

| Perubahan | v6 | v7 |
|--------|-----|-----|
| Widget Root Aplikasi | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (menggunakan `NyApp.materialApp()`) |
| Class State Page | `NyState` | `NyPage` untuk page |
| Method View | `build()` | `view()` |
| Method Init | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Path Route | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Boot Provider | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Konfigurasi | Panggilan `nylo.add*()` individual | Panggilan `nylo.configure()` tunggal |
| ID Theme | File `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Kode (`type: NyThemeType.dark`) |
| Widget Loading | `useSkeletonizer` + `loading()` | Getter `LoadingStyle` |
| Lokasi Config | `lib/config/` | `lib/bootstrap/` (decoder, event, provider, theme) |
| Lokasi Asset | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Pendekatan Migrasi yang Direkomendasikan

Untuk proyek yang lebih besar, kami merekomendasikan membuat proyek v7 baru dan memigrasikan file:

1. Buat proyek v7 baru: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Salin page, controller, model, dan service Anda
3. Perbarui sintaks seperti yang ditunjukkan di atas
4. Uji secara menyeluruh

Ini memastikan Anda memiliki semua struktur dan konfigurasi boilerplate terbaru.

Jika Anda tertarik melihat diff perubahan antara v6 dan v7, Anda dapat melihat perbandingannya di GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Daftar Periksa Migrasi Cepat

Gunakan daftar periksa ini untuk melacak progres migrasi Anda:

- [ ] Perbarui `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Jalankan `flutter pub get`
- [ ] Jalankan `metro make:key` untuk menghasilkan APP_KEY
- [ ] Jalankan `metro make:env` untuk menghasilkan environment terenkripsi
- [ ] Perbarui `main.dart` dengan parameter env dan BootConfig
- [ ] Konversi class `Boot` untuk menggunakan pola `BootConfig`
- [ ] Pindahkan file config dari `lib/config/` ke `lib/bootstrap/`
- [ ] Buat file config baru (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Perbarui `AppProvider` untuk menggunakan `nylo.configure()`
- [ ] Hapus `LIGHT_THEME_ID` dan `DARK_THEME_ID` dari `.env`
- [ ] Tambahkan `type: NyThemeType.dark` ke konfigurasi theme dark
- [ ] Ganti nama `NyState` menjadi `NyPage` untuk semua widget page
- [ ] Ubah `build()` menjadi `view()` di semua page
- [ ] Ubah `init()/boot()` menjadi `get init =>` di semua page
- [ ] Perbarui `static const path` menjadi `static RouteView path`
- [ ] Ubah `router.route()` menjadi `router.add()` di route
- [ ] Ganti nama widget (NyListView -> CollectionView, dll.)
- [ ] Pindahkan asset dari `public/` ke `assets/`
- [ ] Perbarui path asset di `pubspec.yaml`
- [ ] Hapus import Firebase (jika menggunakan - tambahkan package secara langsung)
- [ ] Hapus penggunaan NyDevPanel (gunakan Flutter DevTools)
- [ ] Jalankan `flutter pub get` dan uji

<div id="migration-guide"></div>

## Panduan Migrasi Langkah demi Langkah

<div id="step-1-dependencies"></div>

### Langkah 1: Perbarui Dependensi

Perbarui `pubspec.yaml` Anda:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... dependensi lainnya
```

Jalankan `flutter pub get` untuk memperbarui package.

<div id="step-2-environment"></div>

### Langkah 2: Konfigurasi Environment

v7 membutuhkan variabel environment terenkripsi untuk keamanan yang lebih baik.

**1. Hasilkan APP_KEY:**

``` bash
metro make:key
```

Ini menambahkan `APP_KEY` ke file `.env` Anda.

**2. Hasilkan env.g.dart terenkripsi:**

``` bash
metro make:env
```

Ini membuat `lib/bootstrap/env.g.dart` yang berisi variabel environment terenkripsi Anda.

**3. Hapus variabel theme yang sudah tidak digunakan dari .env:**

``` bash
# Hapus baris-baris ini dari file .env Anda:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Langkah 3: Perbarui main.dart

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
      // Opsional: Tambahkan hook lifecycle aplikasi
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Perubahan Utama:**
- Import `env.g.dart` yang dihasilkan
- Teruskan `Env.get` ke parameter `env`
- `Boot.nylo` sekarang menjadi `Boot.nylo()` (mengembalikan `BootConfig`)
- `setupFinished` dihapus (ditangani dalam `BootConfig`)
- Hook `appLifecycle` opsional untuk perubahan state aplikasi

<div id="step-4-boot"></div>

### Langkah 4: Perbarui boot.dart

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

**Perubahan Utama:**
- Mengembalikan `BootConfig` bukan `Future<Nylo>`
- `setup` dan `finished` digabungkan menjadi satu objek `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Langkah 5: Reorganisasi File Konfigurasi

v7 mereorganisasi file konfigurasi untuk struktur yang lebih baik:

| Lokasi v6 | Lokasi v7 | Aksi |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Pindahkan |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Pindahkan |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Pindahkan |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Pindahkan |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Ganti Nama & Refaktor |
| (baru) | `lib/config/app.dart` | Buat |
| (baru) | `lib/config/toast_notification.dart` | Buat |

**Buat lib/config/app.dart:**

Referensi: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // Nama aplikasi.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // Versi aplikasi.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Tambahkan konfigurasi aplikasi lainnya di sini
}
```

**Buat lib/config/storage_keys.dart:**

Referensi: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Definisikan key yang ingin disinkronkan saat boot
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // berikan pengguna 10 koin secara default
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Tambahkan storage key Anda di sini...
}
```

**Buat lib/config/toast_notification.dart:**

Referensi: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Kustomisasi style toast di sini
  };
}
```

<div id="step-6-provider"></div>

### Langkah 6: Perbarui AppProvider

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

**Perubahan Utama:**
- `boot()` sekarang menjadi `setup()` untuk konfigurasi awal
- `boot()` sekarang digunakan untuk logika pasca-setup (sebelumnya `afterBoot`)
- Semua panggilan `nylo.add*()` dikonsolidasikan menjadi satu `nylo.configure()`
- Lokalisasi menggunakan objek `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Langkah 7: Perbarui Konfigurasi Theme

**v6 (file .env):**
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

**Perubahan Utama:**
- Hapus `LIGHT_THEME_ID` dan `DARK_THEME_ID` dari `.env`
- Definisikan ID theme langsung dalam kode
- Tambahkan `type: NyThemeType.dark` ke semua konfigurasi theme dark
- Theme light default-nya adalah `NyThemeType.light`

**Method API Theme Baru (v7):**
``` dart
// Set dan ingat theme yang dipilih
NyTheme.set(context, id: 'dark_theme', remember: true);

// Set theme pilihan untuk mengikuti sistem
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Dapatkan ID theme pilihan
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Enumerasi theme
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Hapus preferensi yang tersimpan
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Langkah 10: Migrasi Widget

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

// Dengan paginasi (pull to refresh):
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

### Langkah 11: Perbarui Path Asset

v7 mengubah direktori asset dari `public/` menjadi `assets/`:

**1. Pindahkan folder asset Anda:**
``` bash
# Pindahkan direktori
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Perbarui pubspec.yaml:**

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

**3. Perbarui referensi asset dalam kode:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Widget LocalizedApp - Dihapus

Referensi: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migrasi:** Gunakan `Main(nylo)` secara langsung. `NyApp.materialApp()` menangani lokalisasi secara internal.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Referensi Class yang Dihapus

| Class yang Dihapus | Alternatif |
|---------------|-------------|
| `NyTextStyle` | Gunakan `TextStyle` Flutter secara langsung |
| `NyBaseApiService` | Gunakan `DioApiService` |
| `BaseColorStyles` | Gunakan `ThemeColor` |
| `LocalizedApp` | Gunakan `Main(nylo)` secara langsung |
| `NyException` | Gunakan exception Dart standar |
| `PushNotification` | Gunakan `flutter_local_notifications` secara langsung |
| `PushNotificationAttachments` | Gunakan `flutter_local_notifications` secara langsung |

<div id="widget-reference"></div>

## Referensi Migrasi Widget

### Widget yang Diganti Nama

| Widget v6 | Widget v7 | Catatan |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | API baru dengan `builder` bukan `child` |
| `NyFutureBuilder` | `FutureWidget` | Widget async yang disederhanakan |
| `NyTextField` | `InputField` | Menggunakan `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | API sama |
| `NyRichText` | `StyledText` | API sama |
| `NyFader` | `FadeOverlay` | API sama |

### Widget yang Dihapus (Tanpa Pengganti Langsung)

| Widget yang Dihapus | Alternatif |
|----------------|-------------|
| `NyPullToRefresh` | Gunakan `CollectionView.pullable()` |

### Contoh Migrasi Widget

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

## Pemecahan Masalah

### "Env.get not found" atau "Env is not defined"

**Solusi:** Jalankan perintah pembuatan environment:
``` bash
metro make:key
metro make:env
```
Kemudian import file yang dihasilkan di `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" atau "Dark theme not working"

**Solusi:** Pastikan theme dark memiliki `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Tambahkan baris ini
),
```

### "LocalizedApp not found"

Referensi: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Solusi:** `LocalizedApp` telah dihapus. Ubah:
``` dart
// Dari:
runApp(LocalizedApp(child: Main(nylo)));

// Menjadi:
runApp(Main(nylo));
```

### "router.route is not defined"

**Solusi:** Gunakan `router.add()` sebagai gantinya:
``` dart
// Dari:
router.route(HomePage.path, (context) => HomePage());

// Menjadi:
router.add(HomePage.path);
```

### "NyListView not found"

**Solusi:** `NyListView` sekarang menjadi `CollectionView`:
``` dart
// Dari:
NyListView(...)

// Menjadi:
CollectionView<MyModel>(...)
```

### Asset tidak termuat (gambar, font)

**Solusi:** Perbarui path asset dari `public/` menjadi `assets/`:
1. Pindahkan file: `mv public/* assets/`
2. Perbarui path di `pubspec.yaml`
3. Perbarui referensi dalam kode

### "init() must return a value of type Future"

**Solusi:** Ubah ke sintaks getter:
``` dart
// Dari:
@override
init() async { ... }

// Menjadi:
@override
get init => () async { ... };
```

---

**Butuh bantuan?** Periksa [Dokumentasi Nylo](https://nylo.dev/docs/7.x) atau buka issue di [GitHub](https://github.com/nylo-core/nylo/issues).
