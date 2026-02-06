# {{ config('app.name') }}'ya Katk&#305;da Bulunma

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Katk&#305;da bulunmaya giri&#351;")
- [Ba&#351;lang&#305;&#231;](#getting-started "Katk&#305;lara ba&#351;lang&#305;&#231;")
- [Geli&#351;tirme Ortam&#305;](#development-environment "Geli&#351;tirme ortam&#305;n&#305; kurma")
- [Geli&#351;tirme K&#305;lavuzlar&#305;](#development-guidelines "Geli&#351;tirme k&#305;lavuzlar&#305;")
- [De&#287;i&#351;iklikleri G&#246;nderme](#submitting-changes "De&#287;i&#351;iklikleri nas&#305;l g&#246;nderilir")
- [Sorun Bildirme](#reporting-issues "Sorunlar&#305; nas&#305;l bildirilir")


<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }}'ya katk&#305;da bulunmay&#305; d&#252;&#351;&#252;nd&#252;&#287;&#252;n&#252;z i&#231;in te&#351;ekk&#252;rler!

Bu k&#305;lavuz, mikro-framework'e nas&#305;l katk&#305;da bulunaca&#287;&#305;n&#305;z&#305; anlaman&#305;za yard&#305;mc&#305; olacakt&#305;r. Hata d&#252;zeltme, &#246;zellik ekleme veya dok&#252;mantasyonu iyile&#351;tirme fark etmeksizin, katk&#305;lar&#305;n&#305;z {{ config('app.name') }} toplulu&#287;u i&#231;in de&#287;erlidir.

{{ config('app.name') }} &#252;&#231; depoya ayr&#305;lm&#305;&#351;t&#305;r:

| Depo | Ama&#231; |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | &#304;skelet uygulama |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | &#199;ekirdek framework s&#305;n&#305;flar&#305; (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Widget'lar, yard&#305;mc&#305;lar, ara&#231;lar i&#231;eren destek k&#252;t&#252;phanesi (nylo_support) |

<div id="getting-started"></div>

## Ba&#351;lang&#305;&#231;

### Depolar&#305; Fork'lay&#305;n

Katk&#305;da bulunmak istedi&#287;iniz depolar&#305; fork'lay&#305;n:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Nylo &#304;skelet'i Fork'la</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Framework'&#252; Fork'la</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Support'u Fork'la</a>

### Fork'lar&#305;n&#305;z&#305; Klonlay&#305;n

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Geli&#351;tirme Ortam&#305;

### Gereksinimler

A&#351;a&#287;&#305;dakilerin kurulu oldu&#287;undan emin olun:

| Gereksinim | Minimum S&#252;r&#252;m |
|-------------|-----------------|
| Flutter | 3.24.0 veya &#252;zeri |
| Dart SDK | 3.10.7 veya &#252;zeri |

### Yerel Paketleri Ba&#287;lama

Nylo iskeletini edit&#246;r&#252;n&#252;zde a&#231;&#305;n ve yerel framework ve support depolar&#305;n&#305;z&#305; kullanmak i&#231;in ba&#287;&#305;ml&#305;l&#305;k ge&#231;ersiz k&#305;lmalar&#305; ekleyin:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Ba&#287;&#305;ml&#305;l&#305;klar&#305; y&#252;klemek i&#231;in `flutter pub get` komutunu &#231;al&#305;&#351;t&#305;r&#305;n.

Art&#305;k framework veya support depolar&#305;nda yapt&#305;&#287;&#305;n&#305;z de&#287;i&#351;iklikler Nylo iskeletine yans&#305;yacakt&#305;r.

### De&#287;i&#351;ikliklerinizi Test Etme

De&#287;i&#351;ikliklerinizi test etmek i&#231;in iskelet uygulamay&#305; &#231;al&#305;&#351;t&#305;r&#305;n:

``` bash
flutter run
```

Widget veya yard&#305;mc&#305; de&#287;i&#351;iklikleri i&#231;in, ilgili depoya testler eklemeyi d&#252;&#351;&#252;n&#252;n.

<div id="development-guidelines"></div>

## Geli&#351;tirme K&#305;lavuzlar&#305;

### Kod Stili

- Resmi <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart stil k&#305;lavuzunu</a> takip edin
- Anlaml&#305; de&#287;i&#351;ken ve fonksiyon adlar&#305; kullan&#305;n
- Karma&#351;&#305;k mant&#305;k i&#231;in a&#231;&#305;k yorumlar yaz&#305;n
- Genel API'ler i&#231;in dok&#252;mantasyon ekleyin
- Kodu mod&#252;ler ve s&#252;rd&#252;r&#252;lebilir tutun

### Dok&#252;mantasyon

Yeni &#246;zellikler eklerken:

- Genel s&#305;n&#305;flara ve metotlara dartdoc yorumlar&#305; ekleyin
- Gerekirse ilgili dok&#252;mantasyon dosyalar&#305;n&#305; g&#252;ncelleyin
- Dok&#252;mantasyona kod &#246;rnekleri ekleyin

### Test

De&#287;i&#351;iklikleri g&#246;ndermeden &#246;nce:

- Hem iOS hem de Android cihazlarda/sim&#252;lat&#246;rlerde test edin
- M&#252;mk&#252;n oldu&#287;unda geriye d&#246;n&#252;k uyumlulu&#287;u do&#287;rulay&#305;n
- Geriye d&#246;n&#252;k uyumsuz de&#287;i&#351;iklikleri a&#231;&#305;k&#231;a belgelendirin
- Mevcut testleri &#231;al&#305;&#351;t&#305;rarak hi&#231;bir &#351;eyin bozulmad&#305;&#287;&#305;ndan emin olun

<div id="submitting-changes"></div>

## De&#287;i&#351;iklikleri G&#246;nderme

### &#214;nce Tart&#305;&#351;&#305;n

Yeni &#246;zellikler i&#231;in, &#246;nce toplulukla tart&#305;&#351;mak en iyisidir:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Tart&#305;&#351;malar&#305;</a>

### Bir Dal Olu&#351;turun

``` bash
git checkout -b feature/your-feature-name
```

A&#231;&#305;klay&#305;c&#305; dal adlar&#305; kullan&#305;n:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### De&#287;i&#351;ikliklerinizi Kaydedin

``` bash
git add .
git commit -m "Add: Your feature description"
```

A&#231;&#305;k commit mesajlar&#305; kullan&#305;n:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push ve Pull Request Olu&#351;turma

``` bash
git push origin feature/your-feature-name
```

Ard&#305;ndan GitHub'da bir pull request olu&#351;turun.

### Pull Request K&#305;lavuzlar&#305;

- De&#287;i&#351;ikliklerinizin a&#231;&#305;k bir tan&#305;m&#305;n&#305; sa&#287;lay&#305;n
- &#304;lgili sorunlara referans verin
- Varsa ekran g&#246;r&#252;nt&#252;leri veya kod &#246;rnekleri ekleyin
- PR'nizin yaln&#305;zca bir konuya y&#246;nelik oldu&#287;undan emin olun
- De&#287;i&#351;iklikleri odakl&#305; ve atomik tutun

<div id="reporting-issues"></div>

## Sorun Bildirme

### Bildirmeden &#214;nce

1. Sorunun GitHub'da zaten mevcut olup olmad&#305;&#287;&#305;n&#305; kontrol edin
2. En son s&#252;r&#252;m&#252; kulland&#305;&#287;&#305;n&#305;zdan emin olun
3. Sorunu temiz bir projede yeniden &#252;retmeyi deneyin

### Nereye Bildirilir

Sorunlar&#305; uygun depoya bildirin:

- **&#304;skelet sorunlar&#305;**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Framework sorunlar&#305;**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Destek k&#252;t&#252;phanesi sorunlar&#305;**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Sorun &#350;ablonu

Ayr&#305;nt&#305;l&#305; bilgi sa&#287;lay&#305;n:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### S&#252;r&#252;m Bilgisini Alma

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
