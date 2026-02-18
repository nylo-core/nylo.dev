# Dizin Yap&#305;s&#305;

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [K&#246;k Dizin](#root-directory "K&#246;k Dizin")
- [lib Dizini](#lib-directory "lib Dizini")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [Assets Dizini](#assets-directory "Assets Dizini")
- [Asset Yard&#305;mc&#305;lar&#305;](#asset-helpers "Asset Yard&#305;mc&#305;lar&#305;")


<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }}, <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>'den ilham alan temiz ve d&#252;zenli bir dizin yap&#305;s&#305; kullan&#305;r. Bu yap&#305;, projeler aras&#305;nda tutarl&#305;l&#305;&#287;&#305; korumaya yard&#305;mc&#305; olur ve dosyalar&#305; bulmay&#305; kolayla&#351;t&#305;r&#305;r.

<div id="root-directory"></div>

## K&#246;k Dizin

```
nylo_app/
├── android/          # Android platform files
├── assets/           # Images, fonts, and other assets
├── ios/              # iOS platform files
├── lang/             # Language/translation JSON files
├── lib/              # Dart application code
├── test/             # Test files
├── .env              # Environment variables
├── pubspec.yaml      # Dependencies and project config
└── ...
```

<div id="lib-directory"></div>

## lib Dizini

`lib/` klas&#246;r&#252; t&#252;m Dart uygulama kodunuzu i&#231;erir:

```
lib/
├── app/              # Application logic
├── bootstrap/        # Boot configuration
├── config/           # Configuration files
├── resources/        # UI components
├── routes/           # Route definitions
└── main.dart         # Application entry point
```

<div id="app-directory"></div>

### app/

`app/` dizini uygulaman&#305;z&#305;n &#231;ekirdek mant&#305;&#287;&#305;n&#305; i&#231;erir:

| Dizin | Ama&#231; |
|-----------|---------|
| `commands/` | &#214;zel Metro CLI komutlar&#305; |
| `controllers/` | &#304;&#351; mant&#305;&#287;&#305; i&#231;in sayfa controller'lar&#305; |
| `events/` | Olay sistemi i&#231;in olay s&#305;n&#305;flar&#305; |
| `forms/` | Do&#287;rulamal&#305; form s&#305;n&#305;flar&#305; |
| `models/` | Veri model s&#305;n&#305;flar&#305; |
| `networking/` | API servisleri ve a&#287; yap&#305;land&#305;rmas&#305; |
| `networking/dio/interceptors/` | Dio HTTP interceptor'lar&#305; |
| `providers/` | Uygulama ba&#351;lang&#305;c&#305;nda y&#252;klenen servis sa&#287;lay&#305;c&#305;lar |
| `services/` | Genel servis s&#305;n&#305;flar&#305; |

<div id="bootstrap-directory"></div>

### bootstrap/

`bootstrap/` dizini uygulaman&#305;z&#305;n nas&#305;l ba&#351;lad&#305;&#287;&#305;n&#305; yap&#305;land&#305;ran dosyalar&#305; i&#231;erir:

| Dosya | Ama&#231; |
|------|---------|
| `boot.dart` | Ana ba&#351;latma s&#305;ras&#305; yap&#305;land&#305;rmas&#305; |
| `decoders.dart` | Model ve API kod &#231;&#246;z&#252;c&#252; kayd&#305; |
| `env.g.dart` | Olu&#351;turulan &#351;ifreli ortam yap&#305;land&#305;rmas&#305; |
| `events.dart` | Olay kayd&#305; |
| `extensions.dart` | &#214;zel uzant&#305;lar |
| `helpers.dart` | &#214;zel yard&#305;mc&#305; fonksiyonlar |
| `providers.dart` | Sa&#287;lay&#305;c&#305; kayd&#305; |
| `theme.dart` | Tema yap&#305;land&#305;rmas&#305; |

<div id="config-directory"></div>

### config/

`config/` dizini uygulama yap&#305;land&#305;rmas&#305;n&#305; i&#231;erir:

| Dosya | Ama&#231; |
|------|---------|
| `app.dart` | Temel uygulama ayarlar&#305; |
| `design.dart` | Uygulama tasar&#305;m&#305; (font, logo, y&#252;kleyici) |
| `localization.dart` | Dil ve yerel ayarlar |
| `storage_keys.dart` | Yerel depolama anahtar tan&#305;mlar&#305; |
| `toast_notification.dart` | Toast bildirim stilleri |

<div id="resources-directory"></div>

### resources/

`resources/` dizini aray&#252;z bile&#351;enlerini i&#231;erir:

| Dizin | Ama&#231; |
|-----------|---------|
| `pages/` | Sayfa widget'lar&#305; (ekranlar) |
| `themes/` | Tema tan&#305;mlar&#305; |
| `themes/light/` | A&#231;&#305;k tema renkleri |
| `themes/dark/` | Koyu tema renkleri |
| `widgets/` | Yeniden kullan&#305;labilir widget bile&#351;enleri |
| `widgets/buttons/` | &#214;zel buton widget'lar&#305; |
| `widgets/bottom_sheet_modals/` | Alt sayfa modal widget'lar&#305; |

<div id="routes-directory"></div>

### routes/

`routes/` dizini y&#246;nlendirme yap&#305;land&#305;rmas&#305;n&#305; i&#231;erir:

| Dosya/Dizin | Ama&#231; |
|----------------|---------|
| `router.dart` | Rota tan&#305;mlar&#305; |
| `guards/` | Rota koruma s&#305;n&#305;flar&#305; |

<div id="assets-directory"></div>

## Assets Dizini

`assets/` dizini statik dosyalar&#305; depolar:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Varl&#305;klar&#305; Kaydetme

Varl&#305;klar `pubspec.yaml` dosyas&#305;nda kaydedilir:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Asset Yard&#305;mc&#305;lar&#305;

{{ config('app.name') }}, varl&#305;klarla &#231;al&#305;&#351;mak i&#231;in yard&#305;mc&#305;lar sa&#287;lar.

### G&#246;rsel Varl&#305;klar

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Genel Varl&#305;klar

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Dil Dosyalar&#305;

Dil dosyalar&#305; proje k&#246;k&#252;ndeki `lang/` dizininde saklan&#305;r:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Daha fazla ayr&#305;nt&#305; i&#231;in [Yerelile&#351;tirme](/docs/7.x/localization) sayfas&#305;na bak&#305;n.
