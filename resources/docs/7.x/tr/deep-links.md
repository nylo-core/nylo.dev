# Derin Baglantilar

---

<a name="section-1"></a>

- [Giris](#introduction "Giris")
- [Baslarken](#getting-started "Baslarken")
  - [Derin Baglantilari Etkinlestirme](#enable-deep-links "Derin Baglantilari Etkinlestirme")
  - [Provider Olusturma](#generate-a-provider "Provider Olusturma")
- [Platform Yapilandirmasi](#platform-configuration "Platform Yapilandirmasi")
  - [Android Yapilandirmasi](#android-configuration "Android Yapilandirmasi")
  - [iOS Yapilandirmasi](#ios-configuration "iOS Yapilandirmasi")
- [Baglantilari Yakalama](#intercepting-links "Baglantilari Yakalama")
- [Yedek Rota](#fallback-route "Yedek Rota")
- [Test Etme](#testing "Test Etme")
- [API Referansi](#api-reference "API Referansi")

<div id="introduction"></div>

## Giris

{{ config('app.name') }}, [`app_links`](https://pub.dev/packages/app_links) tarafindan desteklenen birinci sinif derin baglanti destegi ile gelir. Android App Links, iOS Universal Links, ozel URL semalari ve web URL'lerinden gelen URI'lar otomatik olarak yakalanir ve kayitli rotalariniz araciligiyla yonlendirilir.

Tek bir satir tam isleme hattini etkinlestirir:

```dart
nylo.useDeepLinks();
```

Bir URI ulastiginda, {{ config('app.name') }} yolu ve sorgu parametrelerini cikarir ve bunlarla `routeTo()` cagrisini yapar. Soguk baslatma (uygulamanin bir baglanti tarafindan acilmasi) navigator hazir oldugunda yeniden oynatilir; sicak baslatma baglantilari (uygulama calismaktayken alinanlar) ayni sekilde islenir.

<div id="getting-started"></div>

## Baslarken

<div id="enable-deep-links"></div>

### Derin Baglantilari Etkinlestirme

Herhangi bir provider'in `setup` metodu icinde `useDeepLinks()` cagirin:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

Uygulama baslatilirken calisacak sekilde provider'i `config/providers.dart` dosyasina kaydedin.

<div id="generate-a-provider"></div>

### Provider Olusturma

Metro provider'i sizin icin iskele kurulumunu yapar:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Bu, `useDeepLinks()` onceden bagli ve yorumlu bir `onIncomingLink` ornegi ile `lib/app/providers/deep_link_provider.dart` olusturur. Provider ayrica `config/providers.dart` dosyasina otomatik olarak eklenir.

<div id="platform-configuration"></div>

## Platform Yapilandirmasi

<div id="android-configuration"></div>

### Android Yapilandirmasi

`android/app/src/main/AndroidManifest.xml` dosyanizda ana `<activity>` etiketine bir `<intent-filter>` ekleyin. Asagidaki ornek hem Android App Links (`https://example.com/...`) hem de ozel bir semayi (`myapp://...`) kabul eder:

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

Dogrulanmis App Links icin `https://example.com/.well-known/assetlinks.json` adresinde bir `assetlinks.json` dosyasi barindirun. Tam detaylar icin <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links kilavuzuna</a> bakin.

<div id="ios-configuration"></div>

### iOS Yapilandirmasi

**Ozel URL semalari** icin `ios/Runner/Info.plist` dosyasina bir `CFBundleURLTypes` girisi ekleyin:

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

**Universal Links** icin Xcode'da Associated Domains ozelligini etkinlestirin ve alan adinizda bir `apple-app-site-association` dosyasi barindirun. Tam adim adim kilavuz icin <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links kilavuzuna</a> bakin.

<div id="intercepting-links"></div>

## Baglantilari Yakalama

{{ config('app.name') }}'in URI'yi yonlendirmesinden once mantik calistirmak icin `onIncomingLink` kullanin. {{ config('app.name') }}'in otomatik olarak yonlendirmesi icin `true`, URI'yi kendiniz islemek icin `false` dondurun:

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

Callback, sema, host, yol ve sorgu parametreleri dahil tam `Uri`'yi alir.

<div id="fallback-route"></div>

## Yedek Rota

Gelen bir URI'nin yolu kayitli degilse kullanicinin anlamli bir yere yonlendirilmesi icin `useDeepLinks()` metoduna `fallbackRoute` parametresini gecin:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

URI'nin yolu kayitli bir rota ile eslesiyor ise {{ config('app.name') }} o rotaya yonlendirir. Aksi takdirde kullanici yedek rotaya ulasir.

<div id="testing"></div>

## Test Etme

Komut satirindan gelen derin baglantilari simule edin:

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

Soguk baslatma davranisini dogrulamak icin testler arasinda uygulamayi kapatin; sicak baslatmayi dogrulamak icin uygulama on planda iken URL'yi tetikleyin.

<div id="api-reference"></div>

## API Referansi

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Platform duzeyinde derin baglanti yakalamayı etkinlestirir. Bir provider'in `setup` metodu icinde cagirin.

| Parametre | Tur | Aciklama |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Gelen bir URI'nin yolu kayitli degilse gidilecek rota. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Her gelen URI icin cagrilan bir callback kaydeder. {{ config('app.name') }}'in otomatik yonlendirmesi icin `true`, otomatik yonlendirmeyi engellemek icin `false` dondurun.

| Parametre | Tur | Aciklama |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Gelen URI'yi alir. |
