# Kurulum

---

<a name="section-1"></a>
- [Kurulum](#install "Kurulum")
- [Projeyi Çalıştırma](#running-the-project "Projeyi Çalıştırma")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Üç komut sizi boş bir klasörden yönlendirme, ağ, temalar ve kod üretimi hazır olan çalışan bir Flutter uygulamasına götürür.

<x-doc-strip label="Başlamadan önce" items="Flutter SDK yüklü, Dart 3" linkText="Tüm gereksinimler" linkHref="/tr/docs/{{ $version }}/requirements" />

## Kurulum

Bu komutları sırayla çalıştırın. Her birini yeniden çalıştırmak güvenlidir.

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI'ı global olarak yükleyin">
Bu, {{ config('app.name') }} CLI aracını sisteminize global olarak yükler.

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
