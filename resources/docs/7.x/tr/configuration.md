# Yapılandırma

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- Ortam
  - [.env Dosyası](#env-file ".env Dosyası")
  - [Ortam Yapılandırması Oluşturma](#generating-env "Ortam Yapılandırması Oluşturma")
  - [Değerleri Alma](#retrieving-values "Değerleri Alma")
  - [Config Sınıfları Oluşturma](#creating-config-classes "Config Sınıfları Oluşturma")
  - [Değişken Türleri](#variable-types "Değişken Türleri")
  - [Değişken İçi Değişken](#variable-interpolation "Değişken İçi Değişken")
- [Ortam Çeşitleri](#environment-flavours "Ortam Çeşitleri")
- [Derleme Zamanlı Enjeksiyon](#build-time-injection "Derleme Zamanlı Enjeksiyon")


<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7, güvenli bir ortam yapılandırma sistemi kullanır. Ortam değişkenleriniz bir `.env` dosyasında saklanır ve ardından uygulamanızda kullanılmak üzere oluşturulan bir Dart dosyasına (`env.g.dart`) şifrelenir.

Bu yaklaşım şunları sağlar:
- **Güvenlik**: Ortam değerleri derlenen uygulamada XOR şifrelidir
- **Tip güvenliği**: Değerler otomatik olarak uygun türlere dönüştürülür
- **Derleme zamanlı esneklik**: Geliştirme, test ve üretim için farklı yapılandırmalar

<div id="env-file"></div>

## .env Dosyası

Proje kökünüzdeki `.env` dosyası yapılandırma değişkenlerinizi içerir:

``` bash
# Ortam yapılandırması
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Mevcut Değişkenler

| Değişken | Açıklama |
|----------|-------------|
| `APP_KEY` | **Zorunlu**. Şifreleme için 32 karakterlik gizli anahtar |
| `APP_NAME` | Uygulama adınız |
| `APP_ENV` | Ortam: `developing` veya `production` |
| `APP_DEBUG` | Hata ayıklama modunu etkinleştir (`true`/`false`) |
| `APP_URL` | Uygulamanızın URL'si |
| `API_BASE_URL` | API istekleri için temel URL |
| `ASSET_PATH` | Varlıklar klasörünün yolu |
| `DEFAULT_LOCALE` | Varsayılan dil kodu |

<div id="generating-env"></div>

## Ortam Yapılandırması Oluşturma

{{ config('app.name') }} v7, uygulamanızın ortam değerlerine erişebilmesi için şifrelenmiş bir ortam dosyası oluşturmanızı gerektirir.

### Adım 1: APP_KEY Oluşturma

Önce güvenli bir APP_KEY oluşturun:

``` bash
metro make:key
```

Bu, `.env` dosyanıza 32 karakterlik bir `APP_KEY` ekler.

### Adım 2: env.g.dart Oluşturma

Şifrelenmiş ortam dosyasını oluşturun:

``` bash
metro make:env
```

Bu, şifrelenmiş ortam değişkenlerinizle birlikte `lib/bootstrap/env.g.dart` dosyasını oluşturur.

Ortamınız uygulamanız başladığında otomatik olarak kaydedilir — `main.dart` dosyasındaki `Nylo.init(env: Env.get, ...)` bunu sizin için halleder. Ek bir kurulum gerekmez.

### Değişikliklerden Sonra Yeniden Oluşturma

`.env` dosyanızı değiştirdiğinizde, yapılandırmayı yeniden oluşturun:

``` bash
metro make:env
```

Bu her zaman mevcut `env.g.dart` dosyasının üzerine yazar.

<div id="retrieving-values"></div>

## Değerleri Alma

Ortam değerlerine erişmenin önerilen yolu **config sınıfları** aracılığıyladır. `lib/config/app.dart` dosyanız, ortam değerlerini tipli statik alanlar olarak sunmak için `getEnv()` kullanır:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Ardından uygulama kodunuzda değerlere config sınıfı aracılığıyla erişin:

``` dart
// Uygulamanızın herhangi bir yerinde
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Bu kalıp, ortam erişimini config sınıflarınızda merkezileştirir. `getEnv()` yardımcısı, doğrudan uygulama kodunda değil, config sınıfları içinde kullanılmalıdır.

<div id="creating-config-classes"></div>

## Config Sınıfları Oluşturma

Üçüncü taraf hizmetler veya özelliğe özgü yapılandırma için Metro kullanarak özel config sınıfları oluşturabilirsiniz:

``` bash
metro make:config RevenueCat
```

Bu, `lib/config/revenue_cat_config.dart` konumunda yeni bir config dosyası oluşturur:

``` dart
final class RevenueCatConfig {
  // Yapılandırma değerlerinizi buraya ekleyin
}
```

### Örnek: RevenueCat Yapılandırması

**Adım 1:** Ortam değişkenlerini `.env` dosyanıza ekleyin:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Adım 2:** Config sınıfınızı bu değerleri referans alacak şekilde güncelleyin:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Adım 3:** Ortam yapılandırmanızı yeniden oluşturun:

``` bash
metro make:env
```

**Adım 4:** Config sınıfını uygulamanızda kullanın:

``` dart
import '/config/revenue_cat_config.dart';

// RevenueCat'i başlat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Yetkilendirmeleri kontrol et
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Premium erişim ver
}
```

Bu yaklaşım API anahtarlarınızı ve yapılandırma değerlerinizi güvenli ve merkezi tutar, farklı ortamlarda farklı değerleri yönetmeyi kolaylaştırır.

<div id="variable-types"></div>

## Değişken Türleri

`.env` dosyanızdaki değerler otomatik olarak ayrıştırılır:

| .env Değeri | Dart Türü | Örnek |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (boş metin) |


<div id="variable-interpolation"></div>

## Değişken İçi Değişken

<!-- uncertain: "Variable Interpolation" — rendered as "Değişken İçi Değişken" (literal: variable within variable); established Turkish convention in similar docs may differ -->
`.env` dosyanızdaki string değerler, `${DEĞİŞKEN_ADI}` sözdizimini kullanarak diğer değişkenlere başvurabilir:

``` bash
APP_DOMAIN="myapp.com"
APP_URL="https://${APP_DOMAIN}"
API_BASE_URL="https://api.${APP_DOMAIN}/v1"
```

Kodunuz `getEnv('APP_URL')` çağırdığında, döndürülen değer `https://myapp.com` olur. Başvurular özyinelemeli olarak çözülür, bu nedenle zincirleme başvurular beklendiği gibi çalışır:

``` bash
HOST="example.com"
BASE="https://${HOST}"
UPLOADS="${BASE}/uploads"
```

`getEnv('UPLOADS')`, `https://example.com/uploads` döndürür.

Döngüsel başvurular korunur — bir değişken kendisine başvuruyorsa (doğrudan veya bir zincir aracılığıyla), çözümlenmemiş `${DEĞİŞKEN_ADI}` yer tutucu, sonsuz döngüye neden olmak yerine çıktıda korunur.

<div id="environment-flavours"></div>

## Ortam Çeşitleri

Geliştirme, test ve üretim için farklı yapılandırmalar oluşturun.

### Adım 1: Ortam Dosyaları Oluşturma

Ayrı `.env` dosyaları oluşturun:

``` bash
.env                  # Geliştirme (varsayılan)
.env.staging          # Test
.env.production       # Üretim
```

`.env.production` örneği:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Adım 2: Ortam Yapılandırması Oluşturma

Belirli bir ortam dosyasından oluşturun:

``` bash
# Üretim için
metro make:env --file=".env.production"

# Test için
metro make:env --file=".env.staging"
```

### Adım 3: Uygulamanızı Derleme

Uygun yapılandırma ile derleyin:

``` bash
# Geliştirme
flutter run

# Üretim derlemesi
metro make:env --file=".env.production"
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Derleme Zamanlı Enjeksiyon

Daha yüksek güvenlik için, APP_KEY'i kaynak koduna gömmek yerine derleme zamanında enjekte edebilirsiniz.

### --dart-define Modu ile Oluşturma

``` bash
metro make:env --dart-define
```

Bu, APP_KEY gömmeden `env.g.dart` oluşturur.

### APP_KEY Enjeksiyonu ile Derleme

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Çalıştır
flutter run --dart-define=APP_KEY=your-secret-key
```

Bu yaklaşım APP_KEY'i kaynak kodunuzun dışında tutar ve şunlar için kullanışlıdır:
- Gizli anahtarların enjekte edildiği CI/CD hattı
- Açık kaynak projeler
- Gelişmiş güvenlik gereksinimleri

### En İyi Uygulamalar

1. **`.env` dosyasını asla sürüm kontrolüne eklemeyin** - `.gitignore`'a ekleyin
2. **`.env-example` kullanın** - Hassas değerler olmadan bir şablon kaydedin
3. **Değişikliklerden sonra yeniden oluşturun** - `.env` değiştikten sonra her zaman `metro make:env` çalıştırın
4. **Ortam başına farklı anahtarlar** - Geliştirme, test ve üretim için benzersiz APP_KEY'ler kullanın
