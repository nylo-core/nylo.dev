# Metro CLI Araci

---

<a name="section-1"></a>
- [Giris](#introduction "Giris")
- [Kurulum](#install "Kurulum")
- Make Komutlari
  - [Controller olusturma](#make-controller "Controller olusturma")
  - [Model olusturma](#make-model "Model olusturma")
  - [Sayfa olusturma](#make-page "Sayfa olusturma")
  - [Stateless widget olusturma](#make-stateless-widget "Stateless widget olusturma")
  - [Stateful widget olusturma](#make-stateful-widget "Stateful widget olusturma")
  - [Journey widget olusturma](#make-journey-widget "Journey widget olusturma")
  - [API Servisi olusturma](#make-api-service "API Servisi olusturma")
  - [Event olusturma](#make-event "Event olusturma")
  - [Provider olusturma](#make-provider "Provider olusturma")
  - [Tema olusturma](#make-theme "Tema olusturma")
  - [Form olusturma](#make-forms "Form olusturma")
  - [Route Guard olusturma](#make-route-guard "Route Guard olusturma")
  - [Config dosyasi olusturma](#make-config-file "Config dosyasi olusturma")
  - [Komut olusturma](#make-command "Komut olusturma")
  - [State Managed Widget olusturma](#make-state-managed-widget "State Managed Widget olusturma")
  - [Navigation Hub olusturma](#make-navigation-hub "Navigation Hub olusturma")
  - [Bottom Sheet Modal olusturma](#make-bottom-sheet-modal "Bottom Sheet Modal olusturma")
  - [Button olusturma](#make-button "Button olusturma")
  - [Interceptor olusturma](#make-interceptor "Interceptor olusturma")
  - [Env dosyasi olusturma](#make-env-file "Env dosyasi olusturma")
  - [Anahtar olusturma](#make-key "Anahtar olusturma")
- Uygulama Simgeleri
  - [Uygulama Simgeleri Olusturma](#build-app-icons "Uygulama Simgeleri Olusturma")
- Ozel Komutlar
  - [Ozel komutlar olusturma](#creating-custom-commands "Ozel komutlar olusturma")
  - [Ozel komutlari calistirma](#running-custom-commands "Ozel komutlari calistirma")
  - [Komutlara secenek ekleme](#adding-options-to-custom-commands "Komutlara secenek ekleme")
  - [Komutlara bayrak ekleme](#adding-flags-to-custom-commands "Komutlara bayrak ekleme")
  - [Yardimci metotlar](#custom-command-helper-methods "Yardimci metotlar")
  - [Etkilesimli giris metotlari](#interactive-input-methods "Etkilesimli giris metotlari")
  - [Cikti bicimlendirme](#output-formatting "Cikti bicimlendirme")
  - [Dosya sistemi yardimlari](#file-system-helpers "Dosya sistemi yardimlari")
  - [JSON ve YAML yardimlari](#json-yaml-helpers "JSON ve YAML yardimlari")
  - [Buyuk-kucuk harf donusturme yardimlari](#case-conversion-helpers "Buyuk-kucuk harf donusturme yardimlari")
  - [Proje yol yardimlari](#project-path-helpers "Proje yol yardimlari")
  - [Platform yardimlari](#platform-helpers "Platform yardimlari")
  - [Dart ve Flutter komutlari](#dart-flutter-commands "Dart ve Flutter komutlari")
  - [Dart dosya manipulasyonu](#dart-file-manipulation "Dart dosya manipulasyonu")
  - [Dizin yardimlari](#directory-helpers "Dizin yardimlari")
  - [Dogrulama yardimlari](#validation-helpers "Dogrulama yardimlari")
  - [Dosya iskele sistemi](#file-scaffolding "Dosya iskele sistemi")
  - [Gorev calistiricisi](#task-runner "Gorev calistiricisi")
  - [Tablo ciktisi](#table-output "Tablo ciktisi")
  - [Ilerleme cubugu](#progress-bar "Ilerleme cubugu")


<div id="introduction"></div>

## Giris

Metro, {{ config('app.name') }} framework'unun arka planinda calisan bir CLI aracidir.
Gelistirmeyi hizlandirmak icin bircok yararli arac saglar.

<div id="install"></div>

## Kurulum

`nylo init` kullanarak yeni bir Nylo projesi olusturdugunuzda, `metro` komutu terminaliniz icin otomatik olarak yapilandirilir. Herhangi bir Nylo projesinde hemen kullanmaya baslayabilirsiniz.

Tum kullanilabilir komutlari gormek icin proje dizininizden `metro` komutunu calistirin:

``` bash
metro
```

Asagidakine benzer bir cikti gormelisiniz.

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
  make:key
```

<div id="make-controller"></div>

## Controller olusturma

- [Yeni bir controller olusturma](#making-a-new-controller "Metro ile yeni bir controller olusturma")
- [Zorunlu controller olusturma](#forcefully-make-a-controller "Metro ile zorunlu controller olusturma")
<div id="making-a-new-controller"></div>

### Yeni bir controller olusturma

Terminalde asagidaki komutu calistirarak yeni bir controller olusturabilirsiniz.

``` bash
metro make:controller profile_controller
```

Bu, `lib/app/controllers/` dizininde mevcut degilse yeni bir controller olusturacaktir.

<div id="forcefully-make-a-controller"></div>

### Zorunlu controller olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir controller varsa uzerine yazilacaktir.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Model olusturma

- [Yeni bir model olusturma](#making-a-new-model "Metro ile yeni bir model olusturma")
- [JSON'dan model olusturma](#make-model-from-json "Metro ile JSON'dan yeni bir model olusturma")
- [Zorunlu model olusturma](#forcefully-make-a-model "Metro ile zorunlu model olusturma")
<div id="making-a-new-model"></div>

### Yeni bir model olusturma

Terminalde asagidaki komutu calistirarak yeni bir model olusturabilirsiniz.

``` bash
metro make:model product
```

Yeni olusturulan model `lib/app/models/` dizinine yerlestirilecektir.

<div id="make-model-from-json"></div>

### JSON'dan model olusturma

**Argumenler:**

`--json` veya `-j` bayragi kullanildiginda, bir JSON verisi uzerinden yeni bir model olusturulacaktir.

``` bash
metro make:model product --json
```

Ardindan JSON verinizi terminale yapistirabilirsiniz ve sizin icin bir model olusturulacaktir.

<div id="forcefully-make-a-model"></div>

### Zorunlu model olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir model varsa uzerine yazilacaktir.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Sayfa olusturma

- [Yeni bir sayfa olusturma](#making-a-new-page "Metro ile yeni bir sayfa olusturma")
- [Controller ile sayfa olusturma](#create-a-page-with-a-controller "Metro ile controller'li yeni bir sayfa olusturma")
- [Auth sayfasi olusturma](#create-an-auth-page "Metro ile yeni bir auth sayfasi olusturma")
- [Baslangic sayfasi olusturma](#create-an-initial-page "Metro ile yeni bir baslangic sayfasi olusturma")
- [Zorunlu sayfa olusturma](#forcefully-make-a-page "Metro ile zorunlu sayfa olusturma")

<div id="making-a-new-page"></div>

### Yeni bir sayfa olusturma

Terminalde asagidaki komutu calistirarak yeni bir sayfa olusturabilirsiniz.

``` bash
metro make:page product_page
```

Bu, `lib/resources/pages/` dizininde mevcut degilse yeni bir sayfa olusturacaktir.

<div id="create-a-page-with-a-controller"></div>

### Controller ile sayfa olusturma

Terminalde asagidaki komutu calistirarak controller'li yeni bir sayfa olusturabilirsiniz.

**Argumenler:**

`--controller` veya `-c` bayragi kullanildiginda, controller'li yeni bir sayfa olusturulacaktir.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Auth sayfasi olusturma

Terminalde asagidaki komutu calistirarak yeni bir auth sayfasi olusturabilirsiniz.

**Argumenler:**

`--auth` veya `-a` bayragi kullanildiginda, yeni bir auth sayfasi olusturulacaktir.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Baslangic sayfasi olusturma

Terminalde asagidaki komutu calistirarak yeni bir baslangic sayfasi olusturabilirsiniz.

**Argumenler:**

`--initial` veya `-i` bayragi kullanildiginda, yeni bir baslangic sayfasi olusturulacaktir.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Zorunlu sayfa olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir sayfa varsa uzerine yazilacaktir.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Stateless widget olusturma

- [Yeni bir stateless widget olusturma](#making-a-new-stateless-widget "Metro ile yeni bir stateless widget olusturma")
- [Zorunlu stateless widget olusturma](#forcefully-make-a-stateless-widget "Metro ile zorunlu stateless widget olusturma")
<div id="making-a-new-stateless-widget"></div>

### Yeni bir stateless widget olusturma

Terminalde asagidaki komutu calistirarak yeni bir stateless widget olusturabilirsiniz.

``` bash
metro make:stateless_widget product_rating_widget
```

Yukaridaki komut, `lib/resources/widgets/` dizininde mevcut degilse yeni bir widget olusturacaktir.

<div id="forcefully-make-a-stateless-widget"></div>

### Zorunlu stateless widget olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir widget varsa uzerine yazilacaktir.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Stateful widget olusturma

- [Yeni bir stateful widget olusturma](#making-a-new-stateful-widget "Metro ile yeni bir stateful widget olusturma")
- [Zorunlu stateful widget olusturma](#forcefully-make-a-stateful-widget "Metro ile zorunlu stateful widget olusturma")

<div id="making-a-new-stateful-widget"></div>

### Yeni bir stateful widget olusturma

Terminalde asagidaki komutu calistirarak yeni bir stateful widget olusturabilirsiniz.

``` bash
metro make:stateful_widget product_rating_widget
```

Yukaridaki komut, `lib/resources/widgets/` dizininde mevcut degilse yeni bir widget olusturacaktir.

<div id="forcefully-make-a-stateful-widget"></div>

### Zorunlu stateful widget olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir widget varsa uzerine yazilacaktir.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Journey widget olusturma

- [Yeni bir journey widget olusturma](#making-a-new-journey-widget "Metro ile yeni bir journey widget olusturma")
- [Zorunlu journey widget olusturma](#forcefully-make-a-journey-widget "Metro ile zorunlu journey widget olusturma")

<div id="making-a-new-journey-widget"></div>

### Yeni bir journey widget olusturma

Terminalde asagidaki komutu calistirarak yeni bir journey widget olusturabilirsiniz.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Yukaridaki komut, `lib/resources/widgets/` dizininde mevcut degilse yeni bir widget olusturacaktir.

`--parent` argumani, yeni journey widget'in eklenmesi gereken ust widget'i belirtmek icin kullanilir.

Ornek

``` bash
metro make:navigation_hub onboarding
```

Ardindan, yeni journey widget'leri ekleyin.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Zorunlu journey widget olusturma
**Argumenler:**
`--force` veya `-f` bayragi kullanildiginda, mevcut bir widget varsa uzerine yazilacaktir.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## API Servisi olusturma

- [Yeni bir API Servisi olusturma](#making-a-new-api-service "Metro ile yeni bir API Servisi olusturma")
- [Model ile yeni bir API Servisi olusturma](#making-a-new-api-service-with-a-model "Metro ile model'li yeni bir API Servisi olusturma")
- [Postman ile API Servisi olusturma](#make-api-service-using-postman "Postman ile API servisleri olusturma")
- [Zorunlu API Servisi olusturma](#forcefully-make-an-api-service "Metro ile zorunlu API Servisi olusturma")

<div id="making-a-new-api-service"></div>

### Yeni bir API Servisi olusturma

Terminalde asagidaki komutu calistirarak yeni bir API servisi olusturabilirsiniz.

``` bash
metro make:api_service user_api_service
```

Yeni olusturulan API servisi `lib/app/networking/` dizinine yerlestirilecektir.

<div id="making-a-new-api-service-with-a-model"></div>

### Model ile yeni bir API Servisi olusturma

Terminalde asagidaki komutu calistirarak model'li yeni bir API servisi olusturabilirsiniz.

**Argumenler:**

`--model` veya `-m` secenegi kullanildiginda, model'li yeni bir API servisi olusturulacaktir.

``` bash
metro make:api_service user --model="User"
```

Yeni olusturulan API servisi `lib/app/networking/` dizinine yerlestirilecektir.

### Zorunlu API Servisi olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir API Servisi varsa uzerine yazilacaktir.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Event olusturma

- [Yeni bir event olusturma](#making-a-new-event "Metro ile yeni bir event olusturma")
- [Zorunlu event olusturma](#forcefully-make-an-event "Metro ile zorunlu event olusturma")

<div id="making-a-new-event"></div>

### Yeni bir event olusturma

Terminalde asagidaki komutu calistirarak yeni bir event olusturabilirsiniz.

``` bash
metro make:event login_event
```

Bu, `lib/app/events` dizininde yeni bir event olusturacaktir.

<div id="forcefully-make-an-event"></div>

### Zorunlu event olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir event varsa uzerine yazilacaktir.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Provider olusturma

- [Yeni bir provider olusturma](#making-a-new-provider "Metro ile yeni bir provider olusturma")
- [Zorunlu provider olusturma](#forcefully-make-a-provider "Metro ile zorunlu provider olusturma")

<div id="making-a-new-provider"></div>

### Yeni bir provider olusturma

Asagidaki komutu kullanarak uygulamanizda yeni provider'lar olusturun.

``` bash
metro make:provider firebase_provider
```

Yeni olusturulan provider `lib/app/providers/` dizinine yerlestirilecektir.

<div id="forcefully-make-a-provider"></div>

### Zorunlu provider olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir provider varsa uzerine yazilacaktir.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Tema olusturma

- [Yeni bir tema olusturma](#making-a-new-theme "Metro ile yeni bir tema olusturma")
- [Zorunlu tema olusturma](#forcefully-make-a-theme "Metro ile zorunlu tema olusturma")

<div id="making-a-new-theme"></div>

### Yeni bir tema olusturma

Terminalde asagidaki komutu calistirarak tema olusturabilirsiniz.

``` bash
metro make:theme bright_theme
```

Bu, `lib/resources/themes/` dizininde yeni bir tema olusturacaktir.

<div id="forcefully-make-a-theme"></div>

### Zorunlu tema olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir tema varsa uzerine yazilacaktir.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Form olusturma

- [Yeni bir form olusturma](#making-a-new-form "Metro ile yeni bir form olusturma")
- [Zorunlu form olusturma](#forcefully-make-a-form "Metro ile zorunlu form olusturma")

<div id="making-a-new-form"></div>

### Yeni bir form olusturma

Terminalde asagidaki komutu calistirarak yeni bir form olusturabilirsiniz.

``` bash
metro make:form car_advert_form
```

Bu, `lib/app/forms` dizininde yeni bir form olusturacaktir.

<div id="forcefully-make-a-form"></div>

### Zorunlu form olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir form varsa uzerine yazilacaktir.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Route Guard olusturma

- [Yeni bir route guard olusturma](#making-a-new-route-guard "Metro ile yeni bir route guard olusturma")
- [Zorunlu route guard olusturma](#forcefully-make-a-route-guard "Metro ile zorunlu route guard olusturma")

<div id="making-a-new-route-guard"></div>

### Yeni bir route guard olusturma

Terminalde asagidaki komutu calistirarak bir route guard olusturabilirsiniz.

``` bash
metro make:route_guard premium_content
```

Bu, `lib/app/route_guards` dizininde yeni bir route guard olusturacaktir.

<div id="forcefully-make-a-route-guard"></div>

### Zorunlu route guard olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir route guard varsa uzerine yazilacaktir.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Config dosyasi olusturma

- [Yeni bir config dosyasi olusturma](#making-a-new-config-file "Metro ile yeni bir config dosyasi olusturma")
- [Zorunlu config dosyasi olusturma](#forcefully-make-a-config-file "Metro ile zorunlu config dosyasi olusturma")

<div id="making-a-new-config-file"></div>

### Yeni bir config dosyasi olusturma

Terminalde asagidaki komutu calistirarak yeni bir config dosyasi olusturabilirsiniz.

``` bash
metro make:config shopping_settings
```

Bu, `lib/app/config` dizininde yeni bir config dosyasi olusturacaktir.

<div id="forcefully-make-a-config-file"></div>

### Zorunlu config dosyasi olusturma

**Argumenler:**

`--force` veya `-f` bayragi kullanildiginda, mevcut bir config dosyasi varsa uzerine yazilacaktir.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Komut olusturma

- [Yeni bir komut olusturma](#making-a-new-command "Metro ile yeni bir komut olusturma")
- [Zorunlu komut olusturma](#forcefully-make-a-command "Metro ile zorunlu komut olusturma")

<div id="making-a-new-command"></div>

### Yeni bir komut olusturma

Terminalde asagidaki komutu calistirarak yeni bir komut olusturabilirsiniz.

``` bash
metro make:command my_command
```

Bu, `lib/app/commands` dizininde yeni bir komut olusturacaktir.

<div id="forcefully-make-a-command"></div>

### Zorunlu komut olusturma

**Argumenler:**
`--force` veya `-f` bayragi kullanildiginda, mevcut bir komut varsa uzerine yazilacaktir.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## State Managed Widget olusturma

Terminalde asagidaki komutu calistirarak yeni bir state managed widget olusturabilirsiniz.

``` bash
metro make:state_managed_widget product_rating_widget
```

Yukaridaki komut `lib/resources/widgets/` dizininde yeni bir widget olusturacaktir.

`--force` veya `-f` bayragi kullanildiginda, mevcut bir widget varsa uzerine yazilacaktir.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Navigation Hub olusturma

Terminalde asagidaki komutu calistirarak yeni bir navigation hub olusturabilirsiniz.

``` bash
metro make:navigation_hub dashboard
```

Bu, `lib/resources/pages/` dizininde yeni bir navigation hub olusturacak ve route'u otomatik olarak ekleyecektir.

**Argumenler:**

| Bayrak | Kisaltma | Aciklama |
|------|-------|-------------|
| `--auth` | `-a` | Auth sayfasi olarak olustur |
| `--initial` | `-i` | Baslangic sayfasi olarak olustur |
| `--force` | `-f` | Mevcutsa uzerine yaz |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Bottom Sheet Modal olusturma

Terminalde asagidaki komutu calistirarak yeni bir bottom sheet modal olusturabilirsiniz.

``` bash
metro make:bottom_sheet_modal payment_options
```

Bu, `lib/resources/widgets/` dizininde yeni bir bottom sheet modal olusturacaktir.

`--force` veya `-f` bayragi kullanildiginda, mevcut bir modal varsa uzerine yazilacaktir.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Button olusturma

Terminalde asagidaki komutu calistirarak yeni bir button widget'i olusturabilirsiniz.

``` bash
metro make:button checkout_button
```

Bu, `lib/resources/widgets/` dizininde yeni bir button widget'i olusturacaktir.

`--force` veya `-f` bayragi kullanildiginda, mevcut bir button varsa uzerine yazilacaktir.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Interceptor olusturma

Terminalde asagidaki komutu calistirarak yeni bir ag interceptor'u olusturabilirsiniz.

``` bash
metro make:interceptor auth_interceptor
```

Bu, `lib/app/networking/dio/interceptors/` dizininde yeni bir interceptor olusturacaktir.

`--force` veya `-f` bayragi kullanildiginda, mevcut bir interceptor varsa uzerine yazilacaktir.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Env dosyasi olusturma

Terminalde asagidaki komutu calistirarak yeni bir ortam dosyasi olusturabilirsiniz.

``` bash
metro make:env .env.staging
```

Bu, proje kokunde yeni bir `.env` dosyasi olusturacaktir.

<div id="make-key"></div>

## Anahtar olusturma

Ortam sifreleme icin guvenli bir `APP_KEY` olusturun. Bu, v7'deki sifrelenmis `.env` dosyalari icin kullanilir.

``` bash
metro make:key
```

**Argumenler:**

| Bayrak / Secenek | Kisaltma | Aciklama |
|---------------|-------|-------------|
| `--force` | `-f` | Mevcut APP_KEY'in uzerine yaz |
| `--file` | `-e` | Hedef .env dosyasi (varsayilan: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Uygulama Simgeleri Olusturma

Asagidaki komutu calistirarak IOS ve Android icin tum uygulama simgelerini olusturabilirsiniz.

``` bash
dart run flutter_launcher_icons:main
```

Bu, `pubspec.yaml` dosyanizdaki <b>flutter_icons</b> yapilandirmasini kullanir.

<div id="custom-commands"></div>

## Ozel Komutlar

Ozel komutlar, Nylo'nun CLI'sini kendi projenize ozel komutlarla genisletmenize olanak tanir. Bu ozellik, tekrarlayan gorevleri otomatiklestirmenizi, dagitim is akislarini uygulamanizi veya projenizin komut satiri araclariniza dogrudan herhangi bir ozel islevsellik eklemenizi saglar.

- [Ozel komutlar olusturma](#creating-custom-commands)
- [Ozel Komutlari Calistirma](#running-custom-commands)
- [Komutlara secenek ekleme](#adding-options-to-custom-commands)
- [Komutlara bayrak ekleme](#adding-flags-to-custom-commands)
- [Yardimci metotlar](#custom-command-helper-methods)

> **Not:** Su anda ozel komutlarinizda nylo_framework.dart'i ice aktaramazsiniz, bunun yerine ny_cli.dart kullanin.

<div id="creating-custom-commands"></div>

## Ozel Komutlar Olusturma

Yeni bir ozel komut olusturmak icin `make:command` ozelligini kullanabilirsiniz:

```bash
metro make:command current_time
```

Komutunuz icin `--category` secenegini kullanarak bir kategori belirtebilirsiniz:

```bash
# Specify a category
metro make:command current_time --category="project"
```

Bu, `lib/app/commands/current_time.dart` yolunda asagidaki yapiyla yeni bir komut dosyasi olusturacaktir:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

Komut, tum kayitli komutlarin listesini iceren `lib/app/commands/commands.json` dosyasina otomatik olarak kaydedilecektir:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Ozel Komutlari Calistirma

Olusturulduktan sonra, ozel komutunuzu Metro kisayolunu veya tam Dart komutunu kullanarak calistirabilirsiniz:

```bash
metro app:current_time
```

`metro`'yu arguman olmadan calistirdiginizda, ozel komutlarinizi "Custom Commands" bolumu altinda menude listelendigini goreceksiniz:

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Komutunuz icin yardim bilgilerini goruntulemek icin `--help` veya `-h` bayragini kullanin:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Komutlara Secenek Ekleme

Secenekler, komutunuzun kullanicilardan ek girdi almasini saglar. `builder` metodunda komutunuza secenek ekleyebilirsiniz:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Add an option with a default value
  command.addOption(
    'environment',     // option name
    abbr: 'e',         // short form abbreviation
    help: 'Target deployment environment', // help text
    defaultValue: 'development',  // default value
    allowed: ['development', 'staging', 'production'] // allowed values
  );

  return command;
}
```

Ardindan komutunuzun `handle` metodunda secenek degerine erisin:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Command implementation...
}
```

Ornek kullanim:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Komutlara Bayrak Ekleme

Bayraklar, acilip kapanabilen boole secenekleridir. `addFlag` metodunu kullanarak komutunuza bayrak ekleyin:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // flag name
    abbr: 'v',       // short form abbreviation
    help: 'Enable verbose output', // help text
    defaultValue: false  // default to off
  );

  return command;
}
```

Ardindan komutunuzun `handle` metodunda bayrak durumunu kontrol edin:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Additional logging...
  }

  // Command implementation...
}
```

Ornek kullanim:

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Yardimci Metotlar

`NyCustomCommand` temel sinifi, yaygin gorevlere yardimci olmak icin cesitli yardimci metotlar saglar:

#### Mesaj Yazdirma

Farkli renklerde mesaj yazdirmak icin bazi metotlar:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Mavi metin ile bilgi mesaji yazdir |
| [`error`](#custom-command-helper-formatting)     | Kirmizi metin ile hata mesaji yazdir |
| [`success`](#custom-command-helper-formatting)   | Yesil metin ile basari mesaji yazdir |
| [`warning`](#custom-command-helper-formatting)   | Sari metin ile uyari mesaji yazdir |

#### Islem Calistirma

Islemleri calistirin ve ciktilarini konsolda goruntulen:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | `pubspec.yaml`'a paket ekle |
| [`addPackages`](#custom-command-helper-add-packages) | `pubspec.yaml`'a birden fazla paket ekle |
| [`runProcess`](#custom-command-helper-run-process) | Harici bir islem calistir ve ciktiyi konsolda goruntule |
| [`prompt`](#custom-command-helper-prompt)    | Kullanici girdisini metin olarak topla |
| [`confirm`](#custom-command-helper-confirm)   | Evet/hayir sorusu sor ve boole sonuc dondur |
| [`select`](#custom-command-helper-select)    | Secenekler listesi sun ve kullanicinin birini secmesine izin ver |
| [`multiSelect`](#custom-command-helper-multi-select) | Kullanicinin listeden birden fazla secenek secmesine izin ver |

#### Ag Istekleri

Konsol uzerinden ag istekleri yapma:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Nylo API istemcisini kullanarak API cagrisi yap |


#### Yukleme Spinner'i

Bir fonksiyon calistirilirken yukleme spinner'i goruntuler:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Bir fonksiyon calistirilirken yukleme spinner'i goster |
| [`createSpinner`](#manual-spinner-control) | Manuel kontrol icin spinner ornegi olustur |

#### Ozel Komut Yardimlari

Komut argumanlarini yonetmek icin asagidaki yardimci metotlari da kullanabilirsiniz:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Komut argumanlarindan string deger al |
| [`getBool`](#custom-command-helper-get-bool)   | Komut argumanlarindan boole deger al |
| [`getInt`](#custom-command-helper-get-int)    | Komut argumanlarindan tamsayi deger al |
| [`sleep`](#custom-command-helper-sleep) | Belirtilen sure boyunca calismayi duraklat |


### Harici Islemleri Calistirma

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### Paket Yonetimi

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Add a package to pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Add a dev package to pubspec.yaml
addPackage('build_runner', dev: true);

// Add multiple packages at once
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Cikti Bicimlendirme

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## Etkilesimli Giris Metotlari

`NyCustomCommand` temel sinifi, terminalde kullanici girdisi toplamak icin cesitli metotlar saglar. Bu metotlar, ozel komutlariniz icin etkilesimli komut satiri arayuzleri olusturmayi kolaylastirir.

<div id="custom-command-helper-prompt"></div>

### Metin Girdisi

```dart
String prompt(String question, {String defaultValue = ''})
```

Kullaniciya bir soru goruntuleyen ve metin yanitini toplayan metot.

**Parametreler:**
- `question`: Goruntulenmesi gereken soru veya istem
- `defaultValue`: Kullanici sadece Enter'a bastiginda opsiyonel varsayilan deger

**Dondurur:** Kullanicinin girdisini string olarak veya girdi saglanmadiysa varsayilan degeri

**Ornek:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Onay

```dart
bool confirm(String question, {bool defaultValue = false})
```

Kullaniciya evet/hayir sorusu sorar ve boole sonuc dondurur.

**Parametreler:**
- `question`: Sorulacak evet/hayir sorusu
- `defaultValue`: Varsayilan cevap (evet icin true, hayir icin false)

**Dondurur:** Kullanici evet yanit verdiyse `true`, hayir yanit verdiyse `false`

**Ornek:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // User confirmed or pressed Enter (accepting the default)
  await runProcess('flutter pub get');
} else {
  // User declined
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Tekli Secim

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Secenekler listesi sunar ve kullanicinin birini secmesine izin verir.

**Parametreler:**
- `question`: Secim istemi
- `options`: Kullanilabilir secenekler listesi
- `defaultOption`: Opsiyonel varsayilan secim

**Dondurur:** Secilen secenegi string olarak

**Ornek:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Coklu Secim

```dart
List<String> multiSelect(String question, List<String> options)
```

Kullanicinin listeden birden fazla secenek secmesine izin verir.

**Parametreler:**
- `question`: Secim istemi
- `options`: Kullanilabilir secenekler listesi

**Dondurur:** Secilen seceneklerin listesi

**Ornek:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## API Yardimci Metodu

`api` yardimci metodu, ozel komutlarinizdan ag istekleri yapmayi kolaylastirir.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Temel Kullanim Ornekleri

### GET Istegi

```dart
// Fetch data
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST Istegi

```dart
// Create a resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT Istegi

```dart
// Update a resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE Istegi

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH Istegi

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Sorgu Parametreleri ile

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Spinner ile

```dart
// Using with spinner for better UI
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Process the data
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Spinner Islevi

Spinner'lar, ozel komutlarinizdaki uzun sureli islemler sirasinda gorsel geri bildirim saglar. Komutunuz asenkron gorevleri yuruturken bir mesajla birlikte animasyonlu bir gosterge goruntuleyerek ilerleme ve durum gostererek kullanici deneyimini iyilestirir.

- [Spinner ile kullanim](#using-with-spinner)
- [Manuel spinner kontrolu](#manual-spinner-control)
- [Ornekler](#spinner-examples)

<div id="using-with-spinner"></div>

## Spinner ile kullanim

`withSpinner` metodu, gorev basladiginda otomatik olarak baslayan ve tamamlandiginda veya basarisiz oldugunda duran bir spinner animasyonu ile asenkron bir gorevi sarmalamaniza olanak tanir:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parametreler:**
- `task`: Calistirilacak asenkron fonksiyon
- `message`: Spinner calisirken goruntulenmesi gereken metin
- `successMessage`: Basarili tamamlama durumunda goruntulenmesi gereken opsiyonel mesaj
- `errorMessage`: Gorev basarisiz oldugunda goruntulenmesi gereken opsiyonel mesaj

**Dondurur:** Gorev fonksiyonunun sonucu

**Ornek:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Run a task with a spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Long-running task (e.g., analyzing project files)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Continue with the results
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Manuel Spinner Kontrolu

Spinner durumunu manuel olarak kontrol etmeniz gereken daha karmasik senaryolar icin bir spinner ornegi olusturabilirsiniz:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parametreler:**
- `message`: Spinner calisirken goruntulenmesi gereken metin

**Dondurur:** Manuel olarak kontrol edebileceginiz bir `ConsoleSpinner` ornegi

**Manuel kontrollu ornek:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a spinner instance
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // First task
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Second task
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Third task
    await runProcess('./deploy.sh', silent: true);

    // Complete successfully
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Handle failure
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Ornekler

### Spinner ile Basit Gorev

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Install dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Birden Fazla Ardisik Islem

```dart
@override
Future<void> handle(CommandResult result) async {
  // First operation with spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Second operation with spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Third operation with spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Manuel Kontrollu Karmasik Is Akisi

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Run multiple steps with status updates
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Complete the process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Ozel komutlarinizda spinner kullanimi, uzun sureli islemler sirasinda kullanicilara net gorsel geri bildirim saglayarak daha cilali ve profesyonel bir komut satiri deneyimi olusturur.

<div id="custom-command-helper-get-string"></div>

### Seceneklerden string deger alma

```dart
String getString(String name, {String defaultValue = ''})
```

**Parametreler:**

- `name`: Alinacak secenegin adi
- `defaultValue`: Secenek saglanmadiysa opsiyonel varsayilan deger

**Dondurur:** Secenegin degerini string olarak

**Ornek:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Seceneklerden boole deger alma

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parametreler:**
- `name`: Alinacak secenegin adi
- `defaultValue`: Secenek saglanmadiysa opsiyonel varsayilan deger

**Dondurur:** Secenegin degerini boole olarak


**Ornek:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Seceneklerden tamsayi deger alma

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parametreler:**
- `name`: Alinacak secenegin adi
- `defaultValue`: Secenek saglanmadiysa opsiyonel varsayilan deger

**Dondurur:** Secenegin degerini tamsayi olarak

**Ornek:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Belirtilen sure boyunca bekleme

```dart
void sleep(int seconds)
```

**Parametreler:**
- `seconds`: Beklenecek saniye sayisi

**Dondurur:** Yok

**Ornek:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Cikti Bicimlendirme

Temel `info`, `error`, `success` ve `warning` metotlarinin otesinde, `NyCustomCommand` ek cikti yardimlari saglar:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Print plain text (no color)
  line('Processing your request...');

  // Print blank lines
  newLine();       // one blank line
  newLine(3);      // three blank lines

  // Print a muted comment (gray text)
  comment('This is a background note');

  // Print a prominent alert box
  alert('Important: Please read carefully');

  // Ask is an alias for prompt
  final name = ask('What is your name?');

  // Hidden input for sensitive data (e.g., passwords, API keys)
  final apiKey = promptSecret('Enter your API key:');

  // Abort the command with an error message and exit code
  if (name.isEmpty) {
    abort('Name is required');  // exits with code 1
  }
}
```

| Metot | Aciklama |
|--------|-------------|
| `line(String message)` | Renksiz duz metin yazdir |
| `newLine([int count = 1])` | Bos satirlar yazdir |
| `comment(String message)` | Soluk/gri metin yazdir |
| `alert(String message)` | Belirgin bir uyari kutusu yazdir |
| `ask(String question, {String defaultValue})` | `prompt` icin takma ad |
| `promptSecret(String question)` | Hassas veriler icin gizli girdi |
| `abort([String? message, int exitCode = 1])` | Komutu hata ile sonlandir |

<div id="file-system-helpers"></div>

## Dosya Sistemi Yardimlari

`NyCustomCommand`, yaygin islemler icin `dart:io`'yu manuel olarak ice aktarmaniz gerekmemesi icin yerlesik dosya sistemi yardimlari icerir.

### Dosya Okuma ve Yazma

```dart
@override
Future<void> handle(CommandResult result) async {
  // Check if a file exists
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Check if a directory exists
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Read a file (async)
  String content = await readFile('pubspec.yaml');

  // Read a file (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // Write to a file (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Write to a file (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Append content to a file
  await appendFile('log.txt', 'New log entry\n');

  // Ensure a directory exists (creates it if missing)
  await ensureDirectory('lib/generated');

  // Delete a file
  await deleteFile('lib/generated/output.dart');

  // Copy a file
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Metot | Aciklama |
|--------|-------------|
| `fileExists(String path)` | Dosya mevcutsa `true` dondurur |
| `directoryExists(String path)` | Dizin mevcutsa `true` dondurur |
| `readFile(String path)` | Dosyayi string olarak okur (asenkron) |
| `readFileSync(String path)` | Dosyayi string olarak okur (senkron) |
| `writeFile(String path, String content)` | Dosyaya icerik yazar (asenkron) |
| `writeFileSync(String path, String content)` | Dosyaya icerik yazar (senkron) |
| `appendFile(String path, String content)` | Dosyaya icerik ekler |
| `ensureDirectory(String path)` | Yoksa dizin olusturur |
| `deleteFile(String path)` | Dosya siler |
| `copyFile(String source, String destination)` | Dosya kopyalar |

<div id="json-yaml-helpers"></div>

## JSON ve YAML Yardimlari

Yerlesik yardimlarla JSON ve YAML dosyalarini okuyun ve yazin.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Read a JSON file as a Map
  Map<String, dynamic> config = await readJson('config.json');

  // Read a JSON file as a List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Write data to a JSON file (pretty printed by default)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Write compact JSON
  await writeJson('output.json', data, pretty: false);

  // Append an item to a JSON array file
  // If the file contains [{"name": "a"}], this adds to that array
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // prevents duplicates by this key
  );

  // Read a YAML file as a Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Metot | Aciklama |
|--------|-------------|
| `readJson(String path)` | JSON dosyasini `Map<String, dynamic>` olarak okur |
| `readJsonArray(String path)` | JSON dosyasini `List<dynamic>` olarak okur |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Veriyi JSON olarak yazar |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON dizi dosyasina ekler |
| `readYaml(String path)` | YAML dosyasini `Map<String, dynamic>` olarak okur |

<div id="case-conversion-helpers"></div>

## Buyuk-Kucuk Harf Donusturme Yardimlari

`recase` paketini ice aktarmadan stringleri adlandirma kurallari arasinda donusturun.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Metot | Cikti Formati | Ornek |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Proje Yol Yardimlari

Standart {{ config('app.name') }} proje dizinleri icin getter'lar. Bunlar proje kokunue gore goreceli yollar dondurur.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Build a custom path relative to the project root
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Ozellik | Yol |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Proje icinde goreceli bir yolu cozumler |

<div id="platform-helpers"></div>

## Platform Yardimlari

Platformu kontrol edin ve ortam degiskenlerine erisin.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Platform checks
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Current working directory
  info('Working in: $workingDirectory');

  // Read system environment variables
  String home = env('HOME', '/default/path');
}
```

| Ozellik / Metot | Aciklama |
|-------------------|-------------|
| `isWindows` | Windows uzerinde calisiyorsa `true` |
| `isMacOS` | macOS uzerinde calisiyorsa `true` |
| `isLinux` | Linux uzerinde calisiyorsa `true` |
| `workingDirectory` | Mevcut calisma dizini yolu |
| `env(String key, [String defaultValue = ''])` | Sistem ortam degiskenini okur |

<div id="dart-flutter-commands"></div>

## Dart ve Flutter Komutlari

Yaygin Dart ve Flutter CLI komutlarini yardimci metotlar olarak calistirin. Her biri islem cikis kodunu dondurur.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Format a Dart file or directory
  await dartFormat('lib/app/models/user.dart');

  // Run dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Run flutter pub get
  await flutterPubGet();

  // Run flutter clean
  await flutterClean();

  // Build for a target with additional args
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Run flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // specific directory
}
```

| Metot | Aciklama |
|--------|-------------|
| `dartFormat(String path)` | Dosya veya dizinde `dart format` calistirir |
| `dartAnalyze([String? path])` | `dart analyze` calistirir |
| `flutterPubGet()` | `flutter pub get` calistirir |
| `flutterClean()` | `flutter clean` calistirir |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` calistirir |
| `flutterTest([String? path])` | `flutter test` calistirir |

<div id="dart-file-manipulation"></div>

## Dart Dosya Manipulasyonu

Iskele araclari olustururken kullanisli olan Dart dosyalarini programatik olarak duzenleme yardimlari.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Add an import statement to a Dart file (avoids duplicates)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Insert code before the last closing brace in a file
  // Useful for adding entries to registration maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Check if a file contains a specific string
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Check if a file matches a regex pattern
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Metot | Aciklama |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart dosyasina import ekler (zaten mevcutsa atlar) |
| `insertBeforeClosingBrace(String filePath, String code)` | Dosyadaki son `}` oncesine kod ekler |
| `fileContains(String filePath, String identifier)` | Dosyanin bir string icerip icermedigini kontrol eder |
| `fileContainsPattern(String filePath, Pattern pattern)` | Dosyanin bir kalipla eslesip eslesmedigini kontrol eder |

<div id="directory-helpers"></div>

## Dizin Yardimlari

Dizinlerle calisma ve dosya bulma yardimlari.

```dart
@override
Future<void> handle(CommandResult result) async {
  // List directory contents
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // List recursively
  var allEntities = listDirectory('lib/', recursive: true);

  // Find files matching criteria
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Find files by name pattern
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Delete a directory recursively
  await deleteDirectory('build/');

  // Copy a directory (recursive)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Metot | Aciklama |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Dizin iceriklerini listeler |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Kriterlere uyan dosyalari bulur |
| `deleteDirectory(String path)` | Dizini tekrarli olarak siler |
| `copyDirectory(String source, String destination)` | Dizini tekrarli olarak kopyalar |

<div id="validation-helpers"></div>

## Dogrulama Yardimlari

Kod uretimi icin kullanici girdisini dogrulama ve temizleme yardimlari.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validate a Dart identifier
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Require a non-empty first argument
  String name = requireArgument(result, message: 'Please provide a name');

  // Clean a class name (PascalCase, remove suffixes)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Returns: 'User'

  // Clean a file name (snake_case with extension)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Returns: 'user_model.dart'
}
```

| Metot | Aciklama |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart tanimlayici adini dogrular |
| `requireArgument(CommandResult result, {String? message})` | Bos olmayan ilk argumani gerektirir veya iptal eder |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Sinif adini temizler ve PascalCase'e donusturur |
| `cleanFileName(String name, {String extension = '.dart'})` | Dosya adini temizler ve snake_case'e donusturur |

<div id="file-scaffolding"></div>

## Dosya Iskele Sistemi

Iskele sistemini kullanarak icerikli bir veya birden fazla dosya olusturun.

### Tekli Dosya

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // don't overwrite if exists
    successMessage: 'AuthService created',
  );
}
```

### Birden Fazla Dosya

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

`ScaffoldFile` sinifi su degerleri kabul eder:

| Ozellik | Tip | Aciklama |
|----------|------|-------------|
| `path` | `String` | Olusturulacak dosya yolu |
| `content` | `String` | Dosya icerigi |
| `successMessage` | `String?` | Basari durumunda gosterilen mesaj |

<div id="task-runner"></div>

## Gorev Calistiricisi

Otomatik durum ciktisi ile adlandirilmis bir dizi gorev calistirin.

### Temel Gorev Calistiricisi

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // stop pipeline if this fails (default)
    ),
  ]);
}
```

### Spinner'li Gorev Calistiricisi

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

`CommandTask` sinifi su degerleri kabul eder:

| Ozellik | Tip | Varsayilan | Aciklama |
|----------|------|---------|-------------|
| `name` | `String` | zorunlu | Ciktida gosterilen gorev adi |
| `action` | `Future<void> Function()` | zorunlu | Calistirilacak asenkron fonksiyon |
| `stopOnError` | `bool` | `true` | Bu basarisiz olursa kalan gorevlerin durdurulup durdurulmayacagi |

<div id="table-output"></div>

## Tablo Ciktisi

Konsolda bicimlendirilmis ASCII tabloları goruntuleyin.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Cikti:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Ilerleme Cubugu

Bilinen oge sayisina sahip islemler icin ilerleme cubugu goruntuleyin.

### Manuel Ilerleme Cubugu

```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a progress bar for 100 items
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // increment by 1
  }

  progress.complete('All files processed');
}
```

### Ilerleme ile Ogeleri Isleme

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Process items with automatic progress tracking
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // process each file
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Senkron Ilerleme

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // synchronous processing
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

`ConsoleProgressBar` sinifi su metodlari saglar:

| Metot | Aciklama |
|--------|-------------|
| `start()` | Ilerleme cubugunu baslatir |
| `tick([int amount = 1])` | Ilerlemeyi arttirir |
| `update(int value)` | Ilerlemeyi belirli bir degere ayarlar |
| `updateMessage(String newMessage)` | Goruntulunen mesaji degistirir |
| `complete([String? completionMessage])` | Opsiyonel mesajla tamamlar |
| `stop()` | Tamamlamadan durdurur |
| `current` | Mevcut ilerleme degeri (getter) |
| `percentage` | Yuzde olarak ilerleme (getter) |