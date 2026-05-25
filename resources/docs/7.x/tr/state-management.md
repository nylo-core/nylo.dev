# Durumu Yönetilen Widget'lar

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Deseninizi Seçin](#choose-your-pattern "Deseninizi Seçin")
- [Durum Aksiyonları](#state-actions "Durum Aksiyonları")
  - [Handler Tanımlama](#defining-handlers "Handler Tanımlama")
  - [Aksiyonları Tetikleme](#triggering-actions "Aksiyonları Tetikleme")
  - [Verisiz ve Verili Handler'lar](#handlers-with-and-without-data "Verisiz ve Verili Handler'lar")
  - [StateActions Örneği Kullanma](#using-a-state-actions-instance "StateActions Örneği Kullanma")
- [Desen: Durumu Yönetilen Sayfalar (NyPage)](#pattern-ny-page "Desen: Durumu Yönetilen Sayfalar")
- [Desen: Stateful Widget'lar (NyState)](#pattern-ny-state "Desen: Stateful Widget'lar")
- [Desen: Durumu Yönetilen Widget'lar (NyStateManaged)](#pattern-ny-state-managed "Desen: Durumu Yönetilen Widget'lar")
  - [Gelişmiş: Birden Fazla İzole Örnek](#advanced-multiple-isolated-instances "Gelişmiş: Birden Fazla İzole Örnek")
- [Yaşam Döngüsü Referansı](#lifecycle "Yaşam Döngüsü Referansı")

<div id="introduction"></div>

## Giriş

Durumu yönetilen widget'lar, uygulamanızın herhangi bir yerinden tüm sayfaları yeniden oluşturmadan UI'nizin belirli bölümlerini güncellemenizi sağlar. {{ config('app.name') }} v7'de, hedef widget veya sayfa üzerinde adlandırılmış **durum aksiyonları** tetiklersiniz ve eşleşen handler çalışır.

Zihinsel model basittir:

- Her durumu yönetilen widget veya sayfanın benzersiz bir **durum anahtarı** (string) vardır
- İşleyebileceği **adlandırılmış aksiyonlar** haritasını tanımlar
- Uygulamanızın herhangi bir yerinden şunu çağırabilirsiniz
```
stateAction("action_name", state: TargetWidget.state)
``` 

Durumu yönetilen UI oluşturmak için üç desen vardır. Hepsi aynı `stateAction` mekanizmasını paylaşır — tek fark yönettiğiniz UI türüdür.


<div id="choose-your-pattern"></div>

## Deseninizi Seçin

| Ne yapmak istiyorsunuz... | Kullanın | Scaffold komutu |
|---|---|---|
| Tam bir sayfayı state ile yönetin | `NyPage` | `metro make:page my_page` |
| Tek örnekli bir widget'ı state ile yönetin | `NyState` | `metro make:stateful_widget my_widget` |
| Birden fazla izole örnekli bir widget'ı state ile yönetin | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Önce [Durum Aksiyonları](#state-actions) bölümünü okuyun — her desenin kullandığı API'yi açıklar. Ardından durumunuza uyan desene geçin.


<div id="state-actions"></div>

## Durum Aksiyonları

Durum aksiyonları, bir widget veya sayfanın nasıl işleyeceğini bildiği adlandırılmış komutlardır. Aksiyon adlarını handler fonksiyonlara eşleyen bir harita tanımlarsınız ve bunları uygulamanızın herhangi bir yerinden ada göre tetiklersiniz.

Durum aksiyonlarını şu durumlarda kullanın:
- Bir widget veya sayfada belirli bir davranışı tetiklemeniz gerektiğinde (yalnızca genel yenileme değil)
- Bir widget'a veri gönderip tanımlanmış bir şekilde yanıt vermesini sağlamak istediğinizde
- Birden fazla çağrı noktasından çağrılabilen yeniden kullanılabilir widget davranışları oluşturmak istediğinizde

<div id="defining-handlers"></div>

### Handler Tanımlama

Handler'lar, `NyState` veya `NyPage` sınıfınızdaki `stateActions` getter'ında bulunur. Harita anahtarları aksiyon adlarıdır; değerler, o aksiyon tetiklendiğinde çalışacak fonksiyonlardır.

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

Widget'ı yeniden oluşturmak istiyorlarsa handler'lar `setState`'i kendileri çağırmaktan sorumludur.

Yukarıdaki `apply_discount` handler'ı bir `code` argümanı alır — handler'ınızın şu şekilde iletilen yükü alması gerektiğinde tek bir konumsal parametre tanımlayın:
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Aksiyon yük taşımıyorsa `()` argümansız formu kullanın.


<div id="triggering-actions"></div>

### Aksiyonları Tetikleme

Herhangi bir yerden — başka bir widget, kontrolör, olay işleyici, API callback'i vb. — aksiyon tetiklemek için üst düzey `stateAction` fonksiyonunu kullanın.

``` dart
// Veri olmadan aksiyon tetikleme
stateAction("clear_cart", state: Cart.state);

// Veriyle aksiyon tetikleme
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

`state:` argümanı hedefin **durum anahtarıdır**:
- Widget'lar için — `MyWidget.state` (string)
- Sayfalar için — `MyPage.path` (rota)


<div id="handlers-with-and-without-data"></div>

### Verisiz ve Verili Handler'lar

Handler'lar senkron veya asenkron olabilir ve `data` argümanıyla veya onsuz tanımlanabilir:

``` dart
@override
Map<String, Function> get stateActions => {
  // Veri yok — handler olduğu gibi çalışır
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Veriyle — `data:` argümanı aracılığıyla iletilen her şeyi alır
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async desteklenir — framework handler'ı bekler
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

`stateAction`'a `data:` iletirseniz ancak handler'ınız argüman almıyorsa, veri yalnızca yok sayılır.


<div id="using-a-state-actions-instance"></div>

### StateActions Örneği Kullanma

Bir widget tiplenmiş bir `StateActions` örneği sunuyorsa (genellikle statik bir `stateActions(stateName)` metodu aracılığıyla), serbest fonksiyon yerine doğrudan üzerinde `.action(...)` çağırabilirsiniz. Bu, aynı hedefe birkaç aksiyon gönderirken daha temizdir:

``` dart
// Serbest fonksiyon kullanarak
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// StateActions örneği kullanarak — eşdeğer, daha az tekrar
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Birçok yerleşik widget (`InputField`, `CollectionView`, `LanguageSwitcher`, `NyForm*` ailesi) `.clear()`, `.setValue(...)`, `.refresh()` gibi adlandırılmış metodlara sahip tiplenmiş `StateActions` sınıflarıyla birlikte gelir — nelerin mevcut olduğunu öğrenmek için widget belgelerine bakın.


<div id="pattern-ny-page"></div>

## Desen: Durumu Yönetilen Sayfalar (NyPage)

Uygulamanızın başka bir yerinden tam bir sayfada davranış tetiklemek istediğinizde kullanın — örneğin bir olay tetiklendiğinde sayfayı yenilemek veya bir alt widget'tan form durumunu temizlemek için.

**Adım 1:** Scaffold ile bir sayfa oluşturun.

``` bash
metro make:page my_page
```

Bu, `lib/resources/pages/` konumunda bir `NyPage` oluşturur:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**Adım 2:** `stateManaged`'ı `true` olarak değiştirin.

Varsayılan olarak, sayfalar durum olaylarına **abone olmaz** — bu, bunlara ihtiyaç duymayan sayfalardaki gereksiz dinleyicilerden kaçınır. Bir sayfada durum aksiyonlarını etkinleştirmek için getter'ı değiştirin:

``` dart
@override
bool get stateManaged => true;
```

**Adım 3:** `stateActions` haritası ekleyin.

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**Adım 4:** Sayfanın `path`'ini kullanarak herhangi bir yerden aksiyonu tetikleyin.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Sayfalar `MyPage.path`'e göre, widget'lar ise `MyWidget.state`'e göre tanımlanır. Bu, çağrı tarafındaki tek farktır.


<div id="pattern-ny-state"></div>

## Desen: Stateful Widget'lar (NyState)

Sayfa başına tek örnek olarak var olan yeniden kullanılabilir widget'lar için kullanın — profil kartı, başlık çubuğu, durum göstergesi. Widget'ın aynı anda yalnızca bir örneğini render ediyorsanız, bu doğru desendir.

**Adım 1:** Scaffold ile bir stateful widget oluşturun.

``` bash
metro make:stateful_widget profile_card
```

Bu, `lib/resources/widgets/` konumunda aşağıdakini oluşturur:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

Scaffold size çalışan bir `NyState` widget'ı verir — ancak **henüz durum yönetimine sahip değildir**. Durum aksiyonlarına yanıt vermesi için dört şey eklemeniz gerekir:

1. Widget sınıfında bir `state` anahtarı — uygulamanızın diğer bölümlerinin hedefleyeceği benzersiz string
2. Durum anahtarını alan ve durum sınıfına ileten bir constructor parametresi
3. `stateName` atayan durum sınıfında bir constructor
4. Handler'ları tanımlayan bir `stateActions` haritası

**Adım 2:** Scaffold'u durumu yönetilen widget'a dönüştürün.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ bunu uygulamanızın herhangi bir yerinden çağırın — aşağıdaki handler'ı tetikler
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

Ne değişti:
- `static String get state => "profile_card";` genel durum anahtarını tanımlar
- `createState() => _ProfileCardState(state)` anahtarı durum sınıfına aktarır
- `_ProfileCardState(String? state) { this.stateName = state; }` bu durum örneğini o anahtar altında kaydeder, böylece framework hangi widget'a aksiyonu iletmesi gerektiğini bilir
- `stateActions` adlandırılmış handler'ları bildirir

**Adım 3:** Herhangi bir yerden tetikleyin.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Veriyle veya veri olmaksızın istediğiniz kadar handler ekleyebilirsiniz:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## Desen: Durumu Yönetilen Widget'lar (NyStateManaged)

Ekranda aynı anda **aynı widget'ın birden fazla bağımsız örneğine** ihtiyaç duyduğunuzda kullanın — örneğin, başlıktaki ve bağımsız olarak güncellenebilmesi gereken kenar çubuğundaki sepet rozeti.

`NyStateManaged`, her örneğin ayrı ayrı adreslenebildiği bir `id` parametresi ekler. Widget'ın yalnızca bir örneğini render ediyorsanız, `NyState`'i tercih edin — daha basittir.

**Adım 1:** Scaffold ile durumu yönetilen bir widget oluşturun.

``` bash
metro make:state_managed_widget cart
```

Bu, `lib/resources/widgets/` konumunda aşağıdakini oluşturur:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.id})
      : super(baseState: state, child: () => _CartState());

  static const String state = "cart";

  static action(String action, {dynamic data, String? id}) =>
      stateAction(action, data: data, state: state, id: id);
}

class _CartState extends NyState<Cart> {
  @override
  get init => () {
    // başlatma mantığı burada
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Uygulamanızın herhangi bir yerinden aksiyonları çağırın
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**Adım 2:** Durumu doldurun — `init`'te veri yükleyin, handler'ları tanımlayın ve render edin.

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**Adım 3:** Oluşturulan statik `action()` yardımcısını kullanarak aksiyonları tetikleyin.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Bu, `stateAction("reload_cart", state: Cart.state)` ile eşdeğerdir — statik yardımcı yalnızca standart kodu kaldırır.


<div id="advanced-multiple-isolated-instances"></div>

### Gelişmiş: Birden Fazla İzole Örnek

`NyStateManaged`'ın var olma nedeni, aynı widget'ın birden fazla bağımsız örneğini desteklemektir. Her örnek kendi `id`'sini alır ve bu, ad alanı içeren bir durum anahtarı üretir.

Farklı id'lerle iki sepet render edin:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Artık her birini bağımsız olarak güncelleyebilirsiniz:

``` dart
// Yalnızca başlık sepetini yenile
Cart.action("reload_cart", id: "header");

// Yalnızca kenar çubuğu sepetini yenile
Cart.action("reload_cart", id: "sidebar");

// id yok — varsayılan adsız örneği hedefler
Cart.action("reload_cart");
```

`NyStateManaged` ad alanını işler: `Cart(id: "header")` `"cart_header"` anahtarı altında kaydedilir ve `Cart.action(..., id: "header")` tam olarak o anahtarı hedefler.


<div id="lifecycle"></div>

## Yaşam Döngüsü Referansı

Durumu yönetilen widget'lar ve sayfalar iki temel yaşam döngüsü hook'unu paylaşır:

1. **`init()`** — durum ilk oluşturulduğunda bir kez çağrılır. Başlangıç verilerini yüklemek için kullanın.

2. **`stateUpdated(data)`** — bu duruma karşı bir durum aksiyonu tetiklendiğinde çağrılır. `data` argümanı tam yüktür (aksiyon adı ve aksiyonun verisi dahil). *Her* durum aksiyonuna tepki vermeniz gerekiyorsa geçersiz kılın — çoğu zaman `stateActions`'da handler tanımlamak istediğiniz şeydir.

**Ayrıca bakınız:** [NyState](/docs/{{ $version }}/ny-state) tam durum yardımcıları ve yaşam döngüsü metodları için.
