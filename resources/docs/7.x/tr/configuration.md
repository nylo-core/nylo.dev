# Yap&#305;land&#305;rma

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- Ortam
  - [.env Dosyas&#305;](#env-file ".env Dosyas&#305;")
  - [Ortam Yap&#305;land&#305;rmas&#305; Olu&#351;turma](#generating-env "Ortam Yap&#305;land&#305;rmas&#305; Olu&#351;turma")
  - [De&#287;erleri Alma](#retrieving-values "De&#287;erleri Alma")
  - [Config S&#305;n&#305;flar&#305; Olu&#351;turma](#creating-config-classes "Config S&#305;n&#305;flar&#305; Olu&#351;turma")
  - [De&#287;i&#351;ken T&#252;rleri](#variable-types "De&#287;i&#351;ken T&#252;rleri")
- [Ortam &#199;e&#351;itleri](#environment-flavours "Ortam &#199;e&#351;itleri")
- [Derleme Zamanl&#305; Enjeksiyon](#build-time-injection "Derleme Zamanl&#305; Enjeksiyon")


<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }} v7, g&#252;venli bir ortam yap&#305;land&#305;rma sistemi kullan&#305;r. Ortam de&#287;i&#351;kenleriniz bir `.env` dosyas&#305;nda saklan&#305;r ve ard&#305;ndan uygulaman&#305;zda kullan&#305;lmak &#252;zere olu&#351;turulan bir Dart dosyas&#305;na (`env.g.dart`) &#351;ifrelenir.

Bu yakla&#351;&#305;m &#351;unlar&#305; sa&#287;lar:
- **G&#252;venlik**: Ortam de&#287;erleri derlenen uygulamada XOR &#351;ifrelidir
- **Tip g&#252;venli&#287;i**: De&#287;erler otomatik olarak uygun t&#252;rlere d&#246;n&#252;&#351;t&#252;r&#252;l&#252;r
- **Derleme zamanl&#305; esneklik**: Geli&#351;tirme, test ve &#252;retim i&#231;in farkl&#305; yap&#305;land&#305;rmalar

<div id="env-file"></div>

## .env Dosyas&#305;

Proje k&#246;k&#252;n&#252;zdeki `.env` dosyas&#305; yap&#305;land&#305;rma de&#287;i&#351;kenlerinizi i&#231;erir:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Mevcut De&#287;i&#351;kenler

| De&#287;i&#351;ken | A&#231;&#305;klama |
|----------|-------------|
| `APP_KEY` | **Zorunlu**. &#350;ifreleme i&#231;in 32 karakterlik gizli anahtar |
| `APP_NAME` | Uygulama ad&#305;n&#305;z |
| `APP_ENV` | Ortam: `developing` veya `production` |
| `APP_DEBUG` | Hata ay&#305;klama modunu etkinle&#351;tir (`true`/`false`) |
| `APP_URL` | Uygulaman&#305;z&#305;n URL'si |
| `API_BASE_URL` | API istekleri i&#231;in temel URL |
| `ASSET_PATH` | Varl&#305;klar klas&#246;r&#252;n&#252;n yolu |
| `DEFAULT_LOCALE` | Varsay&#305;lan dil kodu |

<div id="generating-env"></div>

## Ortam Yap&#305;land&#305;rmas&#305; Olu&#351;turma

{{ config('app.name') }} v7, uygulaman&#305;z&#305;n ortam de&#287;erlerine eri&#351;ebilmesi i&#231;in &#351;ifrelenmi&#351; bir ortam dosyas&#305; olu&#351;turman&#305;z&#305; gerektirir.

### Ad&#305;m 1: APP_KEY Olu&#351;turma

&#214;nce g&#252;venli bir APP_KEY olu&#351;turun:

``` bash
metro make:key
```

Bu, `.env` dosyan&#305;za 32 karakterlik bir `APP_KEY` ekler.

### Ad&#305;m 2: env.g.dart Olu&#351;turma

&#350;ifrelenmi&#351; ortam dosyas&#305;n&#305; olu&#351;turun:

``` bash
metro make:env
```

Bu, &#351;ifrelenmi&#351; ortam de&#287;i&#351;kenlerinizle birlikte `lib/bootstrap/env.g.dart` dosyas&#305;n&#305; olu&#351;turur.

Ortam&#305;n&#305;z uygulaman&#305;z ba&#351;lad&#305;&#287;&#305;nda otomatik olarak kaydedilir &#8212; `main.dart` dosyas&#305;ndaki `Nylo.init(env: Env.get, ...)` bunu sizin i&#231;in halleder. Ek bir kurulum gerekmez.

### De&#287;i&#351;ikliklerden Sonra Yeniden Olu&#351;turma

`.env` dosyan&#305;z&#305; de&#287;i&#351;tirdi&#287;inizde, yap&#305;land&#305;rmay&#305; yeniden olu&#351;turun:

``` bash
metro make:env --force
```

`--force` bayra&#287;&#305; mevcut `env.g.dart` dosyas&#305;n&#305;n &#252;zerine yazar.

<div id="retrieving-values"></div>

## De&#287;erleri Alma

Ortam de&#287;erlerine eri&#351;menin &#246;nerilen yolu **config s&#305;n&#305;flar&#305;** arac&#305;l&#305;&#287;&#305;ylad&#305;r. `lib/config/app.dart` dosyan&#305;z, ortam de&#287;erlerini tipli statik alanlar olarak sunmak i&#231;in `getEnv()` kullan&#305;r:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Ard&#305;ndan uygulama kodunuzda de&#287;erlere config s&#305;n&#305;f&#305; arac&#305;l&#305;&#287;&#305;yla eri&#351;in:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Bu kal&#305;p, ortam eri&#351;imini config s&#305;n&#305;flar&#305;n&#305;zda merkezile&#351;tirir. `getEnv()` yard&#305;mc&#305;s&#305;, do&#287;rudan uygulama kodunda de&#287;il, config s&#305;n&#305;flar&#305; i&#231;inde kullan&#305;lmal&#305;d&#305;r.

<div id="creating-config-classes"></div>

## Config S&#305;n&#305;flar&#305; Olu&#351;turma

&#220;&#231;&#252;nc&#252; taraf hizmetler veya &#246;zelli&#287;e &#246;zg&#252; yap&#305;land&#305;rma i&#231;in Metro kullanarak &#246;zel config s&#305;n&#305;flar&#305; olu&#351;turabilirsiniz:

``` bash
metro make:config RevenueCat
```

Bu, `lib/config/revenue_cat_config.dart` konumunda yeni bir config dosyas&#305; olu&#351;turur:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### &#214;rnek: RevenueCat Yap&#305;land&#305;rmas&#305;

**Ad&#305;m 1:** Ortam de&#287;i&#351;kenlerini `.env` dosyan&#305;za ekleyin:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Ad&#305;m 2:** Config s&#305;n&#305;f&#305;n&#305;z&#305; bu de&#287;erleri referans alacak &#351;ekilde g&#252;ncelleyin:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Ad&#305;m 3:** Ortam yap&#305;land&#305;rman&#305;z&#305; yeniden olu&#351;turun:

``` bash
metro make:env --force
```

**Ad&#305;m 4:** Config s&#305;n&#305;f&#305;n&#305; uygulaman&#305;zda kullan&#305;n:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

Bu yakla&#351;&#305;m API anahtarlar&#305;n&#305;z&#305; ve yap&#305;land&#305;rma de&#287;erlerinizi g&#252;venli ve merkezi tutar, farkl&#305; ortamlarda farkl&#305; de&#287;erleri y&#246;netmeyi kolayla&#351;t&#305;r&#305;r.

<div id="variable-types"></div>

## De&#287;i&#351;ken T&#252;rleri

`.env` dosyan&#305;zdaki de&#287;erler otomatik olarak ayr&#305;&#351;t&#305;r&#305;l&#305;r:

| .env De&#287;eri | Dart T&#252;r&#252; | &#214;rnek |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (bo&#351; metin) |


<div id="environment-flavours"></div>

## Ortam &#199;e&#351;itleri

Geli&#351;tirme, test ve &#252;retim i&#231;in farkl&#305; yap&#305;land&#305;rmalar olu&#351;turun.

### Ad&#305;m 1: Ortam Dosyalar&#305; Olu&#351;turma

Ayr&#305; `.env` dosyalar&#305; olu&#351;turun:

``` bash
.env                  # Geli&#351;tirme (varsay&#305;lan)
.env.staging          # Test
.env.production       # &#220;retim
```

`.env.production` &#246;rne&#287;i:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Ad&#305;m 2: Ortam Yap&#305;land&#305;rmas&#305; Olu&#351;turma

Belirli bir ortam dosyas&#305;ndan olu&#351;turun:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Ad&#305;m 3: Uygulaman&#305;z&#305; Derleme

Uygun yap&#305;land&#305;rma ile derleyin:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Derleme Zamanl&#305; Enjeksiyon

Daha y&#252;ksek g&#252;venlik i&#231;in, APP_KEY'i kaynak koduna g&#246;mmek yerine derleme zaman&#305;nda enjekte edebilirsiniz.

### --dart-define Modu ile Olu&#351;turma

``` bash
metro make:env --dart-define
```

Bu, APP_KEY g&#246;mmeden `env.g.dart` olu&#351;turur.

### APP_KEY Enjeksiyonu ile Derleme

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Bu yakla&#351;&#305;m APP_KEY'i kaynak kodunuzun d&#305;&#351;&#305;nda tutar ve &#351;unlar i&#231;in kullan&#305;&#351;l&#305;d&#305;r:
- Gizli anahtarlar&#305;n enjekte edildi&#287;i CI/CD hatt&#305;
- A&#231;&#305;k kaynak projeler
- Geli&#351;mi&#351; g&#252;venlik gereksinimleri

### En &#304;yi Uygulamalar

1. **`.env` dosyas&#305;n&#305; asla s&#252;r&#252;m kontrol&#252;ne eklemeyin** - `.gitignore`'a ekleyin
2. **`.env-example` kullan&#305;n** - Hassas de&#287;erler olmadan bir &#351;ablon kaydedin
3. **De&#287;i&#351;ikliklerden sonra yeniden olu&#351;turun** - `.env` de&#287;i&#351;tikten sonra her zaman `metro make:env --force` &#231;al&#305;&#351;t&#305;r&#305;n
4. **Ortam ba&#351;&#305;na farkl&#305; anahtarlar** - Geli&#351;tirme, test ve &#252;retim i&#231;in benzersiz APP_KEY'ler kullan&#305;n
