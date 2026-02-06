# {{ config('app.name') }} Nedir?

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- Uygulama Geli&#351;tirme
    - [Flutter'da Yeni misiniz?](#new-to-flutter "Flutter'da Yeni misiniz?")
    - [Bak&#305;m ve S&#252;r&#252;m Takvimi](#maintenance-and-release-schedule "Bak&#305;m ve S&#252;r&#252;m Takvimi")
- Katk&#305;lar
    - [Framework Ba&#287;&#305;ml&#305;l&#305;klar&#305;](#framework-dependencies "Framework Ba&#287;&#305;ml&#305;l&#305;klar&#305;")
    - [Katk&#305;da Bulunanlar](#contributors "Katk&#305;da Bulunanlar")


<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }}, uygulama geli&#351;tirmeyi kolayla&#351;t&#305;rmak i&#231;in tasarlanm&#305;&#351; bir Flutter mikro-framework'&#252;d&#252;r. &#214;nceden yap&#305;land&#305;r&#305;lm&#305;&#351; temel bile&#351;enlerle yap&#305;land&#305;r&#305;lm&#305;&#351; bir iskelet sunar, b&#246;ylece altyap&#305; kurulumu yerine uygulaman&#305;z&#305;n &#246;zelliklerini olu&#351;turmaya odaklanabilirsiniz.

{{ config('app.name') }} kutudan &#231;&#305;kan &#246;zellikler:

- **Y&#246;nlendirme** - Guard'lar ve derin ba&#287;lant&#305; deste&#287;i ile basit, deklaratif rota y&#246;netimi
- **A&#287; &#304;&#351;lemleri** - Dio, interceptor'lar ve yan&#305;t d&#246;n&#252;&#351;t&#252;rme ile API servisleri
- **Durum Y&#246;netimi** - NyState ve global durum g&#252;ncellemeleri ile reaktif durum y&#246;netimi
- **Yerellez&#351;tirme** - JSON &#231;eviri dosyalar&#305; ile &#231;oklu dil deste&#287;i
- **Temalar** - Tema de&#287;i&#351;tirme ile a&#231;&#305;k/koyu mod
- **Yerel Depolama** - Backpack ve NyStorage ile g&#252;venli depolama
- **Formlar** - Do&#287;rulama ve alan t&#252;rleri ile form y&#246;netimi
- **Push Bildirimleri** - Yerel ve uzak bildirim deste&#287;i
- **CLI Arac&#305; (Metro)** - Sayfa, controller, model ve daha fazlas&#305;n&#305; olu&#351;turma

<div id="new-to-flutter"></div>

## Flutter'da Yeni misiniz?

Flutter'da yeniyseniz, resmi kaynaklarla ba&#351;lay&#305;n:

- <a href="https://flutter.dev" target="_BLANK">Flutter Dok&#252;mantasyonu</a> - Kapsaml&#305; k&#305;lavuzlar ve API referans&#305;
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube Kanal&#305;</a> - E&#287;itimler ve g&#252;ncellemeler
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Yemek Kitab&#305;</a> - Yayg&#305;n g&#246;revler i&#231;in pratik tarifler

Flutter temelleri konusunda rahat hissetti&#287;inizde, {{ config('app.name') }} standart Flutter kal&#305;plar&#305; &#252;zerine in&#351;a edildi&#287;i i&#231;in sezgisel gelecektir.


<div id="maintenance-and-release-schedule"></div>

## Bak&#305;m ve S&#252;r&#252;m Takvimi

{{ config('app.name') }}, <a href="https://semver.org" target="_BLANK">Semantik S&#252;r&#252;mleme</a>'yi takip eder:

- **B&#252;y&#252;k s&#252;r&#252;mler** (7.x &#8594; 8.x) - Y&#305;lda bir kez, geriye d&#246;n&#252;k uyumsuz de&#287;i&#351;iklikler i&#231;in
- **K&#252;&#231;&#252;k s&#252;r&#252;mler** (7.0 &#8594; 7.1) - Yeni &#246;zellikler, geriye d&#246;n&#252;k uyumlu
- **Yama s&#252;r&#252;mleri** (7.0.0 &#8594; 7.0.1) - Hata d&#252;zeltmeleri ve k&#252;&#231;&#252;k iyile&#351;tirmeler

Hata d&#252;zeltmeleri ve g&#252;venlik yamalar&#305; GitHub depolar&#305; &#252;zerinden h&#305;zl&#305;ca ele al&#305;n&#305;r.


<div id="framework-dependencies"></div>

## Framework Ba&#287;&#305;ml&#305;l&#305;klar&#305;

{{ config('app.name') }} v7, &#351;u a&#231;&#305;k kaynak paketler &#252;zerine in&#351;a edilmi&#351;tir:

### Temel Ba&#287;&#305;ml&#305;l&#305;klar

| Paket | Ama&#231; |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | API istekleri i&#231;in HTTP istemcisi |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | G&#252;venli yerel depolama |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Uluslararas&#305;la&#351;t&#305;rma ve bi&#231;imlendirme |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Stream'ler i&#231;in reaktif uzant&#305;lar |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Nesneler i&#231;in de&#287;er e&#351;itli&#287;i |

### Aray&#252;z ve Widget'lar

| Paket | Ama&#231; |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | &#304;skelet y&#252;kleme efektleri |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Toast bildirimleri |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | &#199;ekerek yenileme i&#351;levi |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Kademeli grid d&#252;zenleri |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Tarih se&#231;ici alanlar&#305; |

### Bildirimler ve Ba&#287;lant&#305;

| Paket | Ama&#231; |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Yerel push bildirimleri |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | A&#287; ba&#287;lant&#305; durumu |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Uygulama simgesi rozetleri |

### Ara&#231;lar

| Paket | Ama&#231; |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | URL ve uygulama a&#231;ma |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Metin b&#252;y&#252;k/k&#252;&#231;&#252;k harf d&#246;n&#252;&#351;&#252;m&#252; |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID olu&#351;turma |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Dosya sistemi yollar&#305; |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Giri&#351; maskeleme |


<div id="contributors"></div>

## Katk&#305;da Bulunanlar

{{ config('app.name') }}'ya katk&#305;da bulunan herkese te&#351;ekk&#252;rler! Katk&#305;da bulunduysanz, buraya eklenmek i&#231;in <a href="mailto:support@nylo.dev">support@nylo.dev</a> adresinden ileti&#351;ime ge&#231;in.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Olu&#351;turucu)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
