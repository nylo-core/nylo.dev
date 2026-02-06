# LanguageSwitcher

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- Kullanım
    - [Açılır Menü Widget'ı](#usage-dropdown "Açılır Menü Widget'ı")
    - [Alt Sayfa Modalı](#usage-bottom-modal "Alt Sayfa Modalı")
- [Özel Açılır Menü Oluşturucu](#custom-builder "Özel Açılır Menü Oluşturucu")
- [Parametreler](#parameters "Parametreler")
- [Statik Metotlar](#methods "Statik Metotlar")


<div id="introduction"></div>

## Giriş

**LanguageSwitcher** widget'ı, {{ config('app.name') }} projelerinizde dil değiştirmeyi kolayca yönetmenizi sağlar. `/lang` dizininizdeki mevcut dilleri otomatik olarak algılar ve kullanıcıya gösterir.

**LanguageSwitcher ne yapar?**

- `/lang` dizininizdeki mevcut dilleri görüntüler
- Kullanıcı bir dil seçtiğinde uygulama dilini değiştirir
- Seçilen dili uygulama yeniden başlatmalarında korur
- Dil değiştiğinde arayüzü otomatik olarak günceller

> **Not**: Uygulamanız henüz yerelleştirilmediyse, bu widget'ı kullanmadan önce [Yerelleştirme](/docs/7.x/localization) dokümantasyonunda nasıl yapılacağını öğrenin.

<div id="usage-dropdown"></div>

## Açılır Menü Widget'ı

`LanguageSwitcher` kullanmanın en basit yolu, uygulama çubuğunuzda bir açılır menü olarak kullanmaktır:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Kullanıcı açılır menüye dokunduğunda, mevcut dillerin listesini görecektir. Bir dil seçtikten sonra uygulama otomatik olarak değişecek ve arayüz güncellenecektir.

<div id="usage-bottom-modal"></div>

## Alt Sayfa Modalı

Dilleri bir alt sayfa modalında da görüntüleyebilirsiniz:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

Alt modal, şu anda seçili dilin yanında bir onay işareti ile dillerin listesini görüntüler.

### Modal Yüksekliğini Özelleştirme

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## Özel Açılır Menü Oluşturucu

Her dil seçeneğinin açılır menüde nasıl göründüğünü özelleştirin:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### Dil Değişikliklerini İşleme

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## Parametreler

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | Açılır menü düğmesi için özel simge |
| `iconEnabledColor` | `Color?` | - | Açılır menü simgesinin rengi |
| `iconSize` | `double` | `24` | Açılır menü simgesinin boyutu |
| `dropdownBgColor` | `Color?` | - | Açılır menünün arka plan rengi |
| `hint` | `Widget?` | - | Dil seçilmediğinde ipucu widget'ı |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Her açılır menü öğesinin yüksekliği |
| `elevation` | `int` | `8` | Açılır menünün yükseltisi |
| `padding` | `EdgeInsetsGeometry?` | - | Açılır menü çevresindeki dolgu |
| `borderRadius` | `BorderRadius?` | - | Açılır menünün kenarlık yarıçapı |
| `textStyle` | `TextStyle?` | - | Açılır menü öğeleri için metin stili |
| `langPath` | `String` | `'lang'` | Varlıklardaki dil dosyalarının yolu |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Açılır menü öğeleri için özel oluşturucu |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Açılır menü öğelerinin hizalaması |
| `dropdownOnTap` | `Function()?` | - | Açılır menü öğesine dokunulduğunda callback |
| `onTap` | `Function()?` | - | Açılır menü düğmesine dokunulduğunda callback |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Dil değiştirildiğinde callback |

<div id="methods"></div>

## Statik Metotlar

### Mevcut Dili Alma

Şu anda seçili dili alın:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Dil Kaydetme

Bir dil tercihini manuel olarak kaydedin:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Dili Temizleme

Kaydedilen dil tercihini kaldırın:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Dil Verilerini Alma

Bir yerel ayar kodundan dil bilgisi alın:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Dil Listesini Alma

`/lang` dizinindeki tüm mevcut dilleri alın:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Alt Modalı Gösterme

Dil seçim modalını görüntüleyin:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Desteklenen Yerel Ayarlar

`LanguageSwitcher` widget'ı, okunabilir adlarla yüzlerce yerel ayar kodunu destekler. Bazı örnekler:

| Yerel Ayar Kodu | Dil Adı |
|-------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

Tam liste, çoğu dil için bölgesel varyantları içerir.
