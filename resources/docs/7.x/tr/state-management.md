# Durum Yönetimi

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Durum Yönetimi Ne Zaman Kullanılmalı](#when-to-use-state-management "Durum Yönetimi Ne Zaman Kullanılmalı")
- [Yaşam Döngüsü](#lifecycle "Yaşam Döngüsü")
- [Durum Aksiyonları](#state-actions "Durum Aksiyonları")
  - [NyState - Durum Aksiyonları](#state-actions-nystate "NyState - Durum Aksiyonları")
  - [NyPage - Durum Aksiyonları](#state-actions-nypage "NyPage - Durum Aksiyonları")
- [Bir Durumu Güncelleme](#updating-a-state "Bir Durumu Güncelleme")
- [İlk Widget'ınızı Oluşturma](#building-your-first-widget "İlk Widget'ınızı Oluşturma")

<div id="introduction"></div>

## Giriş

Durum yönetimi, tüm sayfaları yeniden oluşturmadan UI'nizin belirli bölümlerini güncellemenize olanak tanır. {{ config('app.name') }} v7'de, uygulamanız genelinde iletişim kuran ve birbirini güncelleyen widget'lar oluşturabilirsiniz.

{{ config('app.name') }} durum yönetimi için iki sınıf sunar:
- **`NyState`** — Yeniden kullanılabilir widget'lar oluşturmak için (sepet rozeti, bildirim sayacı veya durum göstergesi gibi)
- **`NyPage`** — Uygulamanızda sayfalar oluşturmak için (`NyState`'i sayfaya özel özelliklerle genişletir)

Durum yönetimini şu durumlarda kullanın:
- Uygulamanızın farklı bir yerinden bir widget'ı güncellemeniz gerektiğinde
- Widget'ları paylaşılan verilerle senkronize tutmanız gerektiğinde
- UI'nin yalnızca bir kısmı değiştiğinde tüm sayfaları yeniden oluşturmaktan kaçınmak istediğinizde


### Önce Durum Yönetimini Anlayalım

Flutter'da her şey bir widget'tır, bunlar tam bir uygulama oluşturmak için birleştirebileceğiniz küçük UI parçalarıdır.

Karmaşık sayfalar oluşturmaya başladığınızda, widget'larınızın durumunu yönetmeniz gerekecektir. Bu, bir şey değiştiğinde, örneğin veri, tüm sayfayı yeniden oluşturmak zorunda kalmadan o widget'ı güncelleyebileceğiniz anlamına gelir.

Bunun önemli olmasının birçok nedeni vardır, ancak ana neden performanstır. Sürekli değişen bir widget'ınız varsa, her değişikliğinde tüm sayfayı yeniden oluşturmak istemezsiniz.

İşte Durum Yönetimi burada devreye girer, uygulamanızdaki bir widget'ın durumunu yönetmenize olanak tanır.


<div id="when-to-use-state-management"></div>

### Durum Yönetimi Ne Zaman Kullanılmalı

Tüm sayfayı yeniden oluşturmadan güncellenmesi gereken bir widget'ınız olduğunda Durum Yönetimi kullanmalısınız.

Örneğin, bir e-ticaret uygulaması oluşturduğunuzu hayal edin. Kullanıcıların sepetindeki toplam ürün sayısını gösteren bir widget oluşturdunuz.
Bu widget'a `Cart()` diyelim.

Nylo'da durum yönetimli bir `Cart` widget'ı şöyle görünecektir:

**Adım 1:** Widget'ı statik bir durum adıyla tanımlayın

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**Adım 2:** `NyState`'i genişleten durum sınıfını oluşturun

``` dart
/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Register the state name
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Load initial data
  };

  @override
  void stateUpdated(data) {
    reboot(); // Reload the widget when state updates
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**Adım 3:** Sepeti okumak ve güncellemek için yardımcı fonksiyonlar oluşturun

``` dart
/// Get the cart value from storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Set the cart value and notify the widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // This triggers stateUpdated() on the widget
}
```

Bunu inceleyelim.

1. `Cart` widget'ı bir `StatefulWidget`'tır.

2. `_CartState`, `NyState<Cart>` sınıfını genişletir.

3. `state` için bir ad tanımlamanız gerekir, bu durumu tanımlamak için kullanılır.

4. `boot()` metodu, widget ilk yüklendiğinde çağrılır.

5. `stateUpdate()` metotları, durum güncellendiğinde ne olacağını yönetir.

Bu örneği {{ config('app.name') }} projenizde denemek isterseniz, `Cart` adında yeni bir widget oluşturun.

``` bash
metro make:state_managed_widget cart
```

Ardından yukarıdaki örneği kopyalayıp projenizde deneyebilirsiniz.

Şimdi, sepeti güncellemek için aşağıdakini çağırabilirsiniz.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Yaşam Döngüsü

Bir `NyState` widget'ının yaşam döngüsü şu şekildedir:

1. `init()` - Bu metot, durum başlatıldığında çağrılır.

2. `stateUpdated(data)` - Bu metot, durum güncellendiğinde çağrılır.

    `updateState(MyStateName.state, data: "The Data")` çağırırsanız, **stateUpdated(data)** çağrılmasını tetikler.

Durum ilk kez başlatıldıktan sonra, durumu nasıl yönetmek istediğinizi uygulamanız gerekecektir.


<div id="state-actions"></div>

## Durum Aksiyonları

Durum aksiyonları, uygulamanızın herhangi bir yerinden bir widget üzerinde belirli metotları tetiklemenize olanak tanır. Bunları bir widget'a gönderebileceğiniz adlandırılmış komutlar olarak düşünün.

Durum aksiyonlarını şu durumlarda kullanın:
- Bir widget üzerinde belirli bir davranışı tetiklemek istediğinizde (sadece yenilemek değil)
- Bir widget'a veri göndermek ve belirli bir şekilde yanıt vermesini sağlamak istediğinizde
- Birden fazla yerden çağrılabilecek yeniden kullanılabilir widget davranışları oluşturmak istediğinizde

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

Widget'ınızda, işlemek istediğiniz aksiyonları tanımlayabilirsiniz.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Example with data
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Ardından, uygulamanızın herhangi bir yerinden `stateAction` metodunu çağırabilirsiniz.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Durum aksiyonlarınızı `init` getter'ınızda `whenStateAction` metodunu kullanarak da tanımlayabilirsiniz.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - Durum Aksiyonları

İlk olarak, bir stateful widget oluşturun.

``` bash
metro make:stateful_widget [widget_name]
```
Örnek: metro make:stateful_widget user_avatar

Bu, `lib/resources/widgets/` dizininde yeni bir widget oluşturur.

O dosyayı açarsanız, durum aksiyonlarınızı tanımlayabileceksiniz.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Example
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Example
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Son olarak, uygulamanızın herhangi bir yerinden aksiyonu gönderebilirsiniz.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - Durum Aksiyonları

Sayfalar da durum aksiyonları alabilir. Bu, widget'lardan veya diğer sayfalardan sayfa düzeyinde davranışları tetiklemek istediğinizde kullanışlıdır.

İlk olarak, durum yönetimli sayfanızı oluşturun.

``` bash
metro make:page my_page
```

Bu, `lib/resources/pages/` dizininde `MyPage` adında yeni bir durum yönetimli sayfa oluşturur.

O dosyayı açarsanız, durum aksiyonlarınızı tanımlayabileceksiniz.

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => true;

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Son olarak, uygulamanızın herhangi bir yerinden aksiyonu gönderebilirsiniz.

``` dart
stateAction('test_page_action', state: MyPage.state);
// prints 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Reset data in page

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// shows a success toast with the message
```

Durum aksiyonlarınızı `whenStateAction` metodunu kullanarak da tanımlayabilirsiniz.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```

Ardından uygulamanızın herhangi bir yerinden aksiyonu gönderebilirsiniz.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Bir Durumu Güncelleme

`updateState()` metodunu çağırarak bir durumu güncelleyebilirsiniz.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

Bu, uygulamanızın herhangi bir yerinden çağrılabilir.

**Ayrıca bakınız:** [NyState](/docs/{{ $version }}/ny-state) durum yönetimi yardımcıları ve yaşam döngüsü metotları hakkında daha fazla bilgi için.


<div id="building-your-first-widget"></div>

## İlk Widget'ınızı Oluşturma

Nylo projenizde, yeni bir widget oluşturmak için aşağıdaki komutu çalıştırın.

``` bash
metro make:stateful_widget todo_list
```

Bu, `TodoList` adında yeni bir `NyState` widget'ı oluşturur.

> Not: Yeni widget, `lib/resources/widgets/` dizininde oluşturulacaktır.
