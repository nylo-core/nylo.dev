# Ekran Görüntüleri

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giriş](#introduction "Ekran Görüntülerine giriş")
- [Ekranı Yakalama](#capturing-the-screen "Ekranın tamamını yakalama")
- [Bir Alanı Yakalama](#capturing-an-area "ScreenshotArea ile ekranın bir bölümünü yakalama")
- [Ekran Görüntüsünü Kaydetme](#saving-a-screenshot "Bir ekran görüntüsünü diske kaydetme")
- [Mağaza Sayfaları](#store-listings "Ekran görüntülerini App Store ve Google Play için boyutlandırma")
  - [Mağaza Boyutları](#store-sizes "ScreenshotStore boyutları")
- [İçeriği Gizleme](#hiding-content "İçeriği ekran görüntülerinden gizleme")
- [Bir Widget'ı Yakalama](#capturing-a-widget "Bir widget'ı göstermeden yakalama")
- [Diller ve Temalar](#locales-and-themes "Başka bir dilde veya temada yakalama")
- [Metro'dan](#from-metro "Metro'dan ekran görüntüsü alma")

<div id="introduction"></div>

## Giriş

<x-doc-strip label="Gereksinimler" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot`, uygulamanızın ekranının veya bir bölümünün PNG'sini alır:

``` dart
// Tüm ekran, diyaloglar ve sheet'ler dahil
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Ekranın bir bölümü
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Yakalamalar her derleme modunda çalışır, bu yüzden bir makbuzu paylaşmak veya ekranı bir hata raporuna eklemek gibi uygulamanızdaki özellikleri besleyebilir. [Metro](#from-metro) ve [playbook'lar](/docs/{{ $version }}/playbooks#screenshots) aynı kodu kullanır.

Bir `Screenshot`, PNG'yi `bytes` olarak ve fiziksel piksel cinsinden `width` ve `height` değerleriyle birlikte tutar.

<div id="capturing-the-screen"></div>

## Ekranı Yakalama

`Screenshot.capture()`, uygulamanın tüm ekranını kullanıcının gördüğü şekliyle okur:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parametre | Açıklama |
|-----------|-------------|
| `pixelRatio` | Mantıksal piksellere göre boyut. Varsayılan olarak cihazınkidir, böylece PNG ekranın yerel çözünürlüğündedir |
| `settle` | Yakalamadan önce bir sonraki kareyi bekle (varsayılan `true`). Bir kare yeni çizildiyse `false` verin |
| `locale` | Yakalama için ekranı bu dilde göster, örn. `'es'` |
| `theme` | Yakalama için ekranı bu tema kimliğinde göster, örn. `'dark'` |

`NyApp.materialApp` ile oluşturulmuş bir uygulama tam olarak sayfalarını yakalar. Kendi `MaterialApp`'inizi oluşturuyorsanız, `builder`'ına bir `ScreenshotRoot` ekleyin:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Bir Alanı Yakalama

Bir ekranın bir bölümünü `ScreenshotArea` ile sarın ve ona bir ad verin:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Ardından o adla yakalayın:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area`, `capture` ile aynı `pixelRatio`, `settle`, `locale` ve `theme` parametrelerini alır. `Screenshot.areas`, ekrandaki alanların adlarını listeler.

<div id="saving-a-screenshot"></div>

## Ekran Görüntüsünü Kaydetme

`store`, PNG'yi bir dosya depolama diskine kaydeder ve yolu döndürür:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

`disk` verilmezse varsayılan diske kaydeder. Görüntüyü paylaşmak veya yüklemek için `shot.bytes` değerini okuyun.

<div id="store-listings"></div>

## Mağaza Sayfaları

`forStore`, bir uygulama mağazasının istediği tam boyutta yeni bir ekran görüntüsü döndürür:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Neredeyse aynı şekle sahip bir ekran boyutu doldurur, diğer her ekran ise içine sığdırılır. `frame: true` ile ekran, `caption` altında bir telefon çerçevesinin içine çizilir.

| Parametre | Açıklama |
|-----------|-------------|
| `frame` | Ekranı bir telefon çerçevesinin içine çiz |
| `caption` | Telefonun üstündeki metin, `frame` ile kullanılır |
| `background` | Ekranın çevresindeki `Color` |

Çerçeveyi ekran görüntüsünün kendi boyutunda çizmek için `framed()` kullanın:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Mağaza Boyutları

| Boyut | Kimlik | Piksel |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` her boyutu listeler ve `ScreenshotStore.named('ios-6.9')` bir boyutu kimliğiyle bulur.

<div id="hiding-content"></div>

## İçeriği Gizleme

Özel olan her şeyi `ScreenshotHidden` ile sarın. Ekranda normal şekilde görünür ve her ekran görüntüsünde gri bir blok onu kapatır:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Gri blok yerine kendi yer tutucunuzu çizmek için `replacement` verin.

<div id="capturing-a-widget"></div>

## Bir Widget'ı Yakalama

`Screenshot.widget`, yalnızca görüntü için oluşturulmuş bir paylaşım kartı gibi bir widget'ı göstermeden yakalar:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG widget kadar büyüktür, bu yüzden tam bir boyut için onu bir `SizedBox` ile sarın. Widget'a o context'in temasını ve yerelleştirmelerini vermek için `context`, yerleştirildiği alanı ayarlamak için `size` ve görsellerin yüklenmesine zaman tanımak için `delay` verin.

<div id="locales-and-themes"></div>

## Diller ve Temalar

Tek bir yakalama için ekranı başka şekilde göstermek üzere `locale` veya `theme` verin:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

Uygulama sayfayı, durumunu ve gezinme yığınını koruyarak yerinde değişir ve hemen ardından geri döner. Hiçbir şey kaydedilmez, bu yüzden kullanıcının kendi seçimleri korunur. Bir dil için assets içinde bir dil dosyası, bir tema için ise kayıtlı bir tema kimliği gerekir.

<div id="from-metro"></div>

## Metro'dan

Çalışan uygulamanızın ekran görüntüsünü terminalden kaydedin:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Tüm seçenekler için [Metro Live](/docs/{{ $version }}/metro-live#screenshots) bölümüne bakın. Bir [playbook](/docs/{{ $version }}/playbooks#screenshots), tüm bir yolculuğun ekran görüntülerini her dilde ve temada alabilir.
