# Kurulum

---

<a name="section-1"></a>
- [Kurulum](#install "Kurulum")
- [Projeyi Çalıştırma](#running-the-project "Projeyi Çalıştırma")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Kurulum

### 1. nylo_installer'ı global olarak yükleyin

``` bash
dart pub global activate nylo_installer
```

Bu, {{ config('app.name') }} CLI aracını sisteminize global olarak yükler.

### 2. Yeni bir proje oluşturun

``` bash
nylo new my_app
```

Bu komut {{ config('app.name') }} şablonunu klonlar, projeyi uygulama adınızla yapılandırır ve tüm bağımlılıkları otomatik olarak yükler.

### 3. Metro CLI alias'ını ayarlayın

``` bash
cd my_app
nylo init
```

Bu, projeniz için `metro` komutunu yapılandırır ve tam `dart run` sözdizimi olmadan Metro CLI komutlarını kullanmanıza olanak tanır.

Kurulumdan sonra, aşağıdakileri içeren eksiksiz bir Flutter proje yapısına sahip olacaksınız:
- Önceden yapılandırılmış yönlendirme ve navigasyon
- API servisi şablonu
- Tema ve yerelleştirme kurulumu
- Kod oluşturma için Metro CLI


<div id="running-the-project"></div>

## Projeyi Çalıştırma

{{ config('app.name') }} projeleri standart bir Flutter uygulaması gibi çalışır.

### Terminal Kullanarak

``` bash
flutter run
```

### IDE Kullanarak

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Çalıştırma ve hata ayıklama</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Kesme noktaları olmadan uygulama çalıştırma</a>

Derleme başarılı olursa, uygulama {{ config('app.name') }}'nun varsayılan karşılama ekranını gösterecektir.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }}, proje dosyaları oluşturmak için **Metro** adında bir CLI aracı içerir.

### Metro'yu Çalıştırma

``` bash
metro
```

Bu, Metro menüsünü görüntüler:

``` bash
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
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Metro Komutları Referansı

| Komut | Açıklama |
|---------|-------------|
| `make:page` | Yeni bir sayfa oluşturun |
| `make:stateful_widget` | Durum bilgili bir widget oluşturun |
| `make:stateless_widget` | Durumsuz bir widget oluşturun |
| `make:state_managed_widget` | Durum yönetimli bir widget oluşturun |
| `make:navigation_hub` | Bir navigasyon merkezi (alt navigasyon) oluşturun |
| `make:journey_widget` | Navigasyon merkezi için bir yolculuk widget'ı oluşturun |
| `make:bottom_sheet_modal` | Bir alt sayfa modalı oluşturun |
| `make:button` | Özel bir buton widget'ı oluşturun |
| `make:form` | Doğrulamalı bir form oluşturun |
| `make:model` | Bir model sınıfı oluşturun |
| `make:provider` | Bir provider oluşturun |
| `make:api_service` | Bir API servisi oluşturun |
| `make:controller` | Bir controller oluşturun |
| `make:event` | Bir olay oluşturun |
| `make:theme` | Bir tema oluşturun |
| `make:route_guard` | Bir rota koruması oluşturun |
| `make:config` | Bir yapılandırma dosyası oluşturun |
| `make:interceptor` | Bir ağ interceptor'ı oluşturun |
| `make:command` | Özel bir Metro komutu oluşturun |
| `make:env` | .env dosyasından ortam yapılandırması oluşturun |

### Kullanım Örnekleri

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
