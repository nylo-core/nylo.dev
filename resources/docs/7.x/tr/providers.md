# Provider'lar

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Provider oluşturma](#create-a-provider "Provider oluşturma")
- [Provider nesnesi](#provider-object "Provider nesnesi")


<div id="introduction"></div>

## Provider'lara Giriş

{{ config('app.name') }}'da provider'lar, uygulamanız çalıştığında <b>main.dart</b> dosyanızdan başlatılır. Tüm provider'larınız `/lib/app/providers/*` dizininde bulunur, bu dosyaları değiştirebilir veya <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a> kullanarak provider'larınızı oluşturabilirsiniz.

Provider'lar, uygulama ilk yüklenmeden önce bir sınıfı, paketi başlatmanız veya bir şey oluşturmanız gerektiğinde kullanılabilir. Örneğin, `route_provider.dart` sınıfı tüm rotaları {{ config('app.name') }}'ya eklemekten sorumludur.

### Derinlemesine İnceleme

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Yaşam Döngüsü

- `Boot.{{ config('app.name') }}`, <b>config/providers.dart</b> dosyanızdaki kayıtlı provider'larınız arasında döngü yapacak ve bunları başlatacaktır.

- `Boot.Finished`, **"Boot.{{ config('app.name') }}"** bittikten hemen sonra çağrılır; bu metot {{ config('app.name') }} örneğini 'nylo' değeriyle `Backpack`'e bağlayacaktır.

Örn. Backpack.instance.read('nylo'); // {{ config('app.name') }} örneği


<div id="create-a-provider"></div>

## Yeni bir Provider Oluşturma

Terminalde aşağıdaki komutu çalıştırarak yeni provider'lar oluşturabilirsiniz.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Provider Nesnesi

Provider'ınız iki metoda sahip olacaktır: `setup(Nylo nylo)` ve `boot(Nylo nylo)`.

Uygulama ilk kez çalıştığında, **setup** metodunuzdaki herhangi bir kod ilk olarak yürütülecektir. Aşağıdaki örnekteki gibi `Nylo` nesnesini de manipüle edebilirsiniz.

Örnek: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Yaşam Döngüsü

1. `setup(Nylo nylo)` - Provider'ınızı başlatın. `Nylo` örneğini veya `null` döndürün.
2. `boot(Nylo nylo)` - Tüm provider'lar kurulumu tamamladıktan sonra çağrılır. Diğer provider'ların hazır olmasına bağlı olan başlatma işlemleri için bunu kullanın.

> `setup` metodu içinde, yukarıdaki gibi bir `Nylo` örneği veya `null` **döndürmelisiniz**.
