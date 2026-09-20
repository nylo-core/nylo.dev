# Kurulum

---

<a name="section-1"></a>
- [Kurulum](#install "Kurulum")
- [Projeyi Çalıştırma](#running-the-project "Projeyi Çalıştırma")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [Projeyi Temizleme](#nylo-clean "Projeyi Temizleme")
  - [iOS Pod'larını Yenileme](#nylo-ios-pod-refresh "iOS Pod'larını Yenileme")
  - [Metro'yu Alias Olmadan Çalıştırma](#nylo-metro "Metro'yu Alias Olmadan Çalıştırma")
  - [CLI'ı Güncelleme](#nylo-self-update "CLI'ı Güncelleme")

<div id="install"></div>

Üç komut sizi boş bir klasörden yönlendirme, ağ, temalar ve kod üretimi hazır olan çalışan bir Flutter uygulamasına götürür.

<x-doc-strip label="Başlamadan önce" items="Flutter SDK yüklü, Dart 3" linkText="Tüm gereksinimler" linkHref="/tr/docs/{{ $version }}/requirements" />

## Kurulum

Bu komutları sırayla çalıştırın. Her birini yeniden çalıştırmak güvenlidir.

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI'ı global olarak yükleyin">
`nylo` komutunu sisteminize ekler.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Yeni bir proje oluşturun">
Bu komut {{ config('app.name') }} şablonunu klonlar, projeyi uygulama adınızla yapılandırır ve tüm bağımlılıkları otomatik olarak yükler.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Metro alias'ını ayarlayın">
Bu, projeniz için `metro` komutunu yapılandırır ve tam `dart run` sözdizimi olmadan Metro CLI komutlarını kullanmanıza olanak tanır.

``` bash
cd my_app
nylo init
```

Ardından terminalinizi yeniden başlatın ya da `nylo init` tarafından yazdırılan `source` komutunu çalıştırın.
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Elde edecekleriniz" items="Önceden yapılandırılmış yönlendirme ve navigasyon
API servisi şablonu
Tema ve yerelleştirme kurulumu
Kod oluşturma için Metro CLI" />


<div id="running-the-project"></div>

## Projeyi Çalıştırma

{{ config('app.name') }} projeleri standart bir Flutter uygulaması gibi çalışır.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Derleme başarılı olursa, uygulama {{ config('app.name') }}'nun varsayılan karşılama ekranını gösterecektir.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Proje klasörünü açın, hedef seçiciden bir cihaz seçin ve **Run** düğmesine basın.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter belgeleri: çalıştırma ve hata ayıklama ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Proje klasörünü açın ve komut paletinden **Debug: Start Without Debugging** komutunu çalıştırın.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter belgeleri: kesme noktası olmadan çalıştırma ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro proje dosyalarını sizin için oluşturur. Menüyü görmek için argümansız çalıştırın veya doğrudan bir komut çağırın.

``` plaintext
$ metro

Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Komut referansı

Her komut bir ad alır, ör. `metro make:page settings_page`

<x-doc-commands title="Widget komutları" rows="
metro make:page | Yeni bir sayfa oluşturun
metro make:stateful_widget | Durum bilgili bir widget oluşturun
metro make:stateless_widget | Durumsuz bir widget oluşturun
metro make:state_managed_widget | Durum yönetimli bir widget oluşturun
metro make:navigation_hub | Bir navigasyon merkezi (alt navigasyon) oluşturun
metro make:journey_widget | Navigasyon merkezi için bir yolculuk widget'ı oluşturun
metro make:bottom_sheet_modal | Bir alt sayfa modalı oluşturun
metro make:button | Özel bir buton widget'ı oluşturun
metro make:form | Doğrulamalı bir form oluşturun
" />

<x-doc-commands title="Yardımcı komutlar" rows="
metro make:model | Bir model sınıfı oluşturun
metro make:provider | Bir provider oluşturun
metro make:api_service | Bir API servisi oluşturun
metro make:controller | Bir controller oluşturun
metro make:event | Bir olay oluşturun
metro make:route_guard | Bir rota koruması oluşturun
metro make:config | Bir yapılandırma dosyası oluşturun
metro make:interceptor | Bir ağ interceptor'ı oluşturun
metro make:command | Özel bir Metro komutu oluşturun
metro make:env | .env dosyasından ortam yapılandırması oluşturun
" />

### Kullanım Örnekleri

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: strip label "Requires" — no established Turkish translation seen in existing locale files -->
<x-doc-strip label="Gereksinimler" items="nylo_installer 1.8.0+" linkText="CLI'ı güncelle" linkHref="#nylo-self-update" />

1. adımdaki `nylo` komutu, projeler oluşturmaktan daha fazlasını yapar. Derlemeleri temizlemek, iOS pod'larını yenilemek, testleri çalıştırmak ve çevirilerinizi kontrol etmek için onu proje dizininizden çalıştırın.

<x-doc-commands title="Proje komutları" rows="
nylo new | Yeni bir proje oluşturun
nylo init | Kabuğunuzda metro alias'ını ayarlayın
nylo clean | flutter clean ve flutter pub get çalıştırın
nylo ios:pod-refresh | iOS pod'larınızı yeniden yükleyin
nylo metro | Alias olmadan bir Metro komutu çalıştırın
" />

<x-doc-commands title="Test ve çeviriler" rows="
nylo test | Testlerinizi biçimlendirin ve çalıştırın
nylo locale:find-untranslated | .tr() içine alınmamış metni bulun
nylo locale:check-missing-keys | Dil dosyalarınızda eksik olan anahtarları bulun
" />

<x-doc-commands title="CLI'ın kendisi" rows="
nylo self-update | nylo'yu en son sürüme güncelleyin
nylo --version | Yüklü sürümü gösterin
" />

Seçenek alan komutlar bunları `-h` ile listeler, örneğin `nylo test -h`. Test etme [Testleri Çalıştırma](/docs/{{ $version }}/testing#running-tests) bölümünde, `locale:` komutları ise [Çevirileri Denetleme](/docs/{{ $version }}/localization#auditing-translations) bölümünde ele alınmıştır.

<div id="nylo-clean"></div>

### Projeyi Temizleme

`nylo clean`, `flutter clean` ve ardından `flutter pub get` çalıştırır. Normal bir temizleme yeterli olmadığında bir platform bayrağı ekleyin:

``` bash
# flutter clean, ardından flutter pub get
nylo clean

# ios/Pods, ios/.symlinks ve ios/Podfile.lock dosyalarını da kaldırır, ardından pod install --repo-update çalıştırır
nylo clean --ios

# android/ içinde gradlew clean komutunu da çalıştırır
nylo clean --android

# Yukarıdakilerin ikisi de
nylo clean --all
```

Bir projede `ios/` veya `android/` klasörü yoksa, o platform bir uyarıyla atlanır. `pod install` yalnızca macOS'ta çalışır.

<div id="nylo-ios-pod-refresh"></div>

### iOS Pod'larını Yenileme

CocoaPods senkronizasyondan çıktığında, `nylo ios:pod-refresh` `ios/Pods`, `ios/.symlinks` ve `ios/Podfile.lock` dosyalarını siler, ardından `ios/` klasöründe `pod install --repo-update` çalıştırır:

``` bash
nylo ios:pod-refresh
```

Bunu macOS'ta proje kök dizininizden çalıştırın. `nylo clean --ios`'un aksine, `flutter clean` veya `flutter pub get` çalıştırmaz.

<div id="nylo-metro"></div>

### Metro'yu Alias Olmadan Çalıştırma

`nylo metro`, kendisinden sonra gelen her şeyi Metro'ya (`dart run nylo_framework:main`) iletir. Bunu, henüz `nylo init` çalıştırmadıysanız ya da `nylo init`'in `metro` alias'ını sizin için ekleyemediği Windows'ta kullanın:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### CLI'ı Güncelleme

``` bash
nylo self-update
```

Bu, pub.dev'i kontrol eder ve daha yeni bir sürüm varsa `nylo_installer`'ı yeniden yükler. Bir komut tamamlandıktan sonra `nylo`, bir güncelleme olduğunda da size bunu bildirir.

<!-- uncertain: "piped reports" (shell pipe redirection of CLI output) has no established Turkish equivalent in this corpus -->
Çıktı bir terminale gitmediğinde sessiz kalır, böylece bildirim CI günlüklerinde veya boru hattıyla aktarılan raporlarda asla görünmez.

Hangi sürümün yüklü olduğunu görmek için:

``` bash
nylo --version
```
