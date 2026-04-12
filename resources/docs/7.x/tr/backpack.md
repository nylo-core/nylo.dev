# Backpack

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Veri Okuma](#reading-data "Veri Okuma")
- [Veri Kaydetme](#saving-data "Veri Kaydetme")
- [Veri Silme](#deleting-data "Veri Silme")
- [Oturumlar](#sessions "Oturumlar")
- [Nylo Örneğine Erişim](#nylo-instance "Nylo Örneğine Erişim")
- [Yardımcı Fonksiyonlar](#helper-functions "Yardımcı Fonksiyonlar")
- [NyStorage ile Entegrasyon](#integration-with-nystorage "NyStorage ile Entegrasyon")
- [Örnekler](#examples "Örnekler")

<div id="introduction"></div>

## Giriş

**Backpack**, {{ config('app.name') }} içinde bellek içi singleton depolama sistemidir. Uygulamanızın çalışma süresi boyunca verilere hızlı ve senkron erişim sağlar. Verileri cihaza kalıcı olarak kaydeden `NyStorage`'ın aksine, Backpack verileri bellekte tutar ve uygulama kapatıldığında temizlenir.

Backpack, framework tarafından dahili olarak `Nylo` uygulama nesnesi, `EventBus` ve kimlik doğrulama verileri gibi kritik örnekleri saklamak için kullanılır. Asenkron çağrılar olmadan hızlı erişim gerektiren kendi verilerinizi saklamak için de kullanabilirsiniz.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Bir değer kaydet
Backpack.instance.save("user_name", "Anthony");

// Bir değer oku (senkron)
String? name = Backpack.instance.read("user_name");

// Bir değer sil
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Temel Kullanım

Backpack **singleton deseni** kullanır -- `Backpack.instance` üzerinden erişilir:

``` dart
// Veri kaydet
Backpack.instance.save("theme", "dark");

// Veri oku
String? theme = Backpack.instance.read("theme"); // "dark"

// Verinin var olup olmadığını kontrol et
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Veri Okuma

`read<T>()` metodunu kullanarak Backpack'ten değerler okuyun. Jenerik türleri ve isteğe bağlı varsayılan değeri destekler:

``` dart
// String oku
String? name = Backpack.instance.read<String>("name");

// Varsayılan değer ile oku
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// int oku
int? score = Backpack.instance.read<int>("score");
```

Backpack, bir tür sağlandığında saklanan değerleri otomatik olarak model nesnelerine dönüştürür. Bu hem JSON dizeleri hem de ham `Map<String, dynamic>` değerleri için çalışır:

``` dart
// Bir User modeli JSON dizesi olarak saklanmışsa, dönüştürülür
User? user = Backpack.instance.read<User>("current_user");

// Ham bir Map saklanmışsa (örn. NyStorage'dan syncKeys ile), aynı zamanda
// okuma sırasında otomatik olarak tipli modele dönüştürülür
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // User döndürür
```

<div id="saving-data"></div>

## Veri Kaydetme

`save()` metodunu kullanarak değerleri kaydedin:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Veri Ekleme

Bir anahtarda saklanan listeye değer eklemek için `append()` kullanın:

``` dart
// Listeye ekle
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Sınır ile ekle (yalnızca son N öğeyi tutar)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Veri Silme

### Tek Bir Anahtarı Silme

``` dart
Backpack.instance.delete("api_token");
```

### Tüm Verileri Silme

`deleteAll()` metodu, ayrılmış framework anahtarları (`nylo` ve `event_bus`) **hariç** tüm değerleri kaldırır:

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Oturumlar

Backpack, verileri adlandırılmış gruplar halinde düzenlemek için oturum yönetimi sağlar. Bu, ilgili verileri bir arada saklamak için kullanışlıdır.

### Oturum Değerini Güncelleme

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Oturum Değerini Alma

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Oturum Anahtarını Kaldırma

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Tüm Oturumu Temizleme

``` dart
Backpack.instance.sessionFlush("cart");
```

### Tüm Oturum Verilerini Alma

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Nylo Örneğine Erişim

Backpack, `Nylo` uygulama örneğini saklar. Aşağıdaki şekilde alabilirsiniz:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Nylo örneğinin başlatılıp başlatılmadığını kontrol edin:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Yardımcı Fonksiyonlar

{{ config('app.name') }}, yaygın Backpack işlemleri için global yardımcı fonksiyonlar sağlar:

| Fonksiyon | Açıklama |
|----------|-------------|
| `backpackRead<T>(key)` | Backpack'ten bir değer okur |
| `backpackSave(key, value)` | Backpack'e bir değer kaydeder |
| `backpackDelete(key)` | Backpack'ten bir değer siler |
| `backpackDeleteAll()` | Tüm değerleri siler (framework anahtarlarını korur) |
| `backpackNylo()` | Backpack'ten Nylo örneğini alır |

### Örnek

``` dart
// Yardımcı fonksiyonları kullanma
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Nylo örneğine erişim
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## NyStorage ile Entegrasyon

Backpack, kalıcı + bellek içi depolama kombinasyonu için `NyStorage` ile entegre çalışır:

``` dart
// Hem NyStorage'a (kalıcı) hem de Backpack'e (bellek içi) kaydet
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Artık Backpack üzerinden senkron erişilebilir
String? token = Backpack.instance.read("auth_token");

// NyStorage'dan silerken, Backpack'ten de temizle
await NyStorage.deleteAll(andFromBackpack: true);
```

Bu desen, hem kalıcılık hem de hızlı senkron erişim gerektiren kimlik doğrulama tokenları gibi veriler için kullanışlıdır (örneğin, HTTP interceptor'larında).

<div id="examples"></div>

## Örnekler

### API İstekleri için Auth Token Saklama

``` dart
// Kimlik doğrulama interceptor'ınızda
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Oturum Tabanlı Sepet Yönetimi

``` dart
// Sepet oturumuna öğeler ekle
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Sepet verilerini oku
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Sepeti temizle
Backpack.instance.sessionFlush("cart");
```

### Hızlı Özellik Bayrakları

``` dart
// Hızlı erişim için özellik bayraklarını Backpack'te sakla
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Özellik bayrağını kontrol et
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
