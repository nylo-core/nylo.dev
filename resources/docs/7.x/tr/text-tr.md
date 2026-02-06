# TextTr

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [String İnterpolasyonu](#string-interpolation "String İnterpolasyonu")
- [Stillendirilmiş Yapıcılar](#styled-constructors "Stillendirilmiş Yapıcılar")
- [Parametreler](#parameters "Parametreler")


<div id="introduction"></div>

## Giriş

**TextTr** widget'ı, {{ config('app.name') }}'un yerelleştirme sistemini kullanarak içeriğini otomatik olarak çeviren Flutter'ın `Text` widget'ının üzerine kurulmuş pratik bir sarmalayıcıdır.

Şunu yazmak yerine:

``` dart
Text("hello_world".tr())
```

Şunu yazabilirsiniz:

``` dart
TextTr("hello_world")
```

Bu, özellikle çok sayıda çevrilmiş dizeyle çalışırken kodunuzu daha temiz ve okunabilir hale getirir.

<div id="basic-usage"></div>

## Temel Kullanım

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

Widget, çeviri anahtarını dil dosyalarınızda (örn. `/lang/en.json`) arayacaktır:

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## String İnterpolasyonu

Çevirilerinize dinamik değerler eklemek için `arguments` parametresini kullanın:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

Dil dosyanızda:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Çıktı: **Hello, John!**

### Birden Fazla Argüman

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

Çıktı: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Stillendirilmiş Yapıcılar

`TextTr`, temanızdan metin stillerini otomatik olarak uygulayan adlandırılmış yapıcılar sunar:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

`Theme.of(context).textTheme.displayLarge` stilini kullanır.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

`Theme.of(context).textTheme.headlineLarge` stilini kullanır.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

`Theme.of(context).textTheme.bodyLarge` stilini kullanır.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

`Theme.of(context).textTheme.labelLarge` stilini kullanır.

### Stillendirilmiş Yapıcılar ile Örnek

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## Parametreler

`TextTr`, standart `Text` widget'ının tüm parametrelerini destekler:

| Parametre | Tür | Açıklama |
|-----------|-----|----------|
| `data` | `String` | Aranacak çeviri anahtarı |
| `arguments` | `Map<String, String>?` | String interpolasyonu için anahtar-değer çiftleri |
| `style` | `TextStyle?` | Metin stili |
| `textAlign` | `TextAlign?` | Metnin nasıl hizalanması gerektiği |
| `maxLines` | `int?` | Maksimum satır sayısı |
| `overflow` | `TextOverflow?` | Taşma nasıl yönetilecek |
| `softWrap` | `bool?` | Metnin yumuşak satır sonlarında sarılıp sarılmayacağı |
| `textDirection` | `TextDirection?` | Metnin yönü |
| `locale` | `Locale?` | Metin oluşturma için yerel ayar |
| `semanticsLabel` | `String?` | Erişilebilirlik etiketi |

## Karşılaştırma

| Yaklaşım | Kod |
|----------|-----|
| Geleneksel | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Argümanlarla | `TextTr("hello", arguments: {"name": "John"})` |
| Stillendirilmiş | `TextTr.headlineLarge("title")` |
