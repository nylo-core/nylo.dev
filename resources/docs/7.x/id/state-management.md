# State Management

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Kapan Menggunakan State Management](#when-to-use-state-management "Kapan Menggunakan State Management")
- [Siklus Hidup](#lifecycle "Siklus Hidup")
- [State Action](#state-actions "State Action")
  - [NyState - State Action](#state-actions-nystate "NyState - State Action")
  - [NyPage - State Action](#state-actions-nypage "NyPage - State Action")
- [Memperbarui State](#updating-a-state "Memperbarui State")
- [Membangun Widget Pertama Anda](#building-your-first-widget "Membangun Widget Pertama Anda")

<div id="introduction"></div>

## Pengantar

State management memungkinkan Anda memperbarui bagian tertentu dari UI tanpa membangun ulang seluruh halaman. Di {{ config('app.name') }} v7, Anda dapat membangun widget yang berkomunikasi dan saling memperbarui di seluruh aplikasi Anda.

{{ config('app.name') }} menyediakan dua class untuk state management:
- **`NyState`** — Untuk membangun widget yang dapat digunakan kembali (seperti badge keranjang, penghitung notifikasi, atau indikator status)
- **`NyPage`** — Untuk membangun halaman di aplikasi Anda (meng-extend `NyState` dengan fitur khusus halaman)

Gunakan state management ketika Anda perlu:
- Memperbarui widget dari bagian lain aplikasi Anda
- Menjaga widget tetap sinkron dengan data bersama
- Menghindari membangun ulang seluruh halaman ketika hanya sebagian UI yang berubah


### Mari kita pahami State Management terlebih dahulu

Semua yang ada di Flutter adalah widget, mereka hanyalah potongan kecil UI yang dapat Anda gabungkan untuk membuat aplikasi lengkap.

Ketika Anda mulai membangun halaman yang kompleks, Anda perlu mengelola state widget Anda. Ini berarti ketika sesuatu berubah, misalnya data, Anda dapat memperbarui widget tersebut tanpa harus membangun ulang seluruh halaman.

Ada banyak alasan mengapa ini penting, tetapi alasan utamanya adalah performa. Jika Anda memiliki widget yang terus berubah, Anda tidak ingin membangun ulang seluruh halaman setiap kali berubah.

Di sinilah State Management berperan, memungkinkan Anda mengelola state widget di aplikasi Anda.


<div id="when-to-use-state-management"></div>

### Kapan Menggunakan State Management

Anda harus menggunakan State Management ketika Anda memiliki widget yang perlu diperbarui tanpa membangun ulang seluruh halaman.

Misalnya, bayangkan Anda telah membuat aplikasi ecommerce. Anda telah membuat widget untuk menampilkan jumlah total item di keranjang pengguna.
Mari kita sebut widget ini `Cart()`.

Widget `Cart` yang dikelola state-nya di Nylo akan terlihat seperti ini:

**Langkah 1:** Definisikan widget dengan nama state statis

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**Langkah 2:** Buat class state yang meng-extend `NyState`

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

**Langkah 3:** Buat fungsi helper untuk membaca dan memperbarui keranjang

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

Mari kita uraikan ini.

1. Widget `Cart` adalah `StatefulWidget`.

2. `_CartState` meng-extend `NyState<Cart>`.

3. Anda perlu mendefinisikan nama untuk `state`, ini digunakan untuk mengidentifikasi state.

4. Metode `boot()` dipanggil ketika widget pertama kali dimuat.

5. Metode `stateUpdate()` menangani apa yang terjadi ketika state diperbarui.

Jika Anda ingin mencoba contoh ini di proyek {{ config('app.name') }} Anda, buat widget baru bernama `Cart`.

``` bash
metro make:state_managed_widget cart
```

Kemudian Anda dapat menyalin contoh di atas dan mencobanya di proyek Anda.

Sekarang, untuk memperbarui keranjang, Anda dapat memanggil yang berikut ini.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Siklus Hidup

Siklus hidup widget `NyState` adalah sebagai berikut:

1. `init()` - Metode ini dipanggil ketika state diinisialisasi.

2. `stateUpdated(data)` - Metode ini dipanggil ketika state diperbarui.

    Jika Anda memanggil `updateState(MyStateName.state, data: "The Data")`, itu akan memicu **stateUpdated(data)** untuk dipanggil.

Setelah state pertama kali diinisialisasi, Anda perlu mengimplementasikan cara Anda ingin mengelola state.


<div id="state-actions"></div>

## State Action

State action memungkinkan Anda memicu metode tertentu pada widget dari mana saja di aplikasi Anda. Anggap saja sebagai perintah bernama yang dapat Anda kirim ke widget.

Gunakan state action ketika Anda perlu:
- Memicu perilaku tertentu pada widget (bukan hanya me-refresh-nya)
- Mengirim data ke widget dan membuatnya merespons dengan cara tertentu
- Membuat perilaku widget yang dapat digunakan kembali yang dapat dipanggil dari beberapa tempat

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

Di widget Anda, Anda dapat mendefinisikan action yang ingin ditangani.

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

Kemudian, Anda dapat memanggil metode `stateAction` dari mana saja di aplikasi Anda.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Anda juga dapat mendefinisikan state action menggunakan metode `whenStateAction` di getter `init` Anda.

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

### NyState - State Action

Pertama, buat stateful widget.

``` bash
metro make:stateful_widget [widget_name]
```
Contoh: metro make:stateful_widget user_avatar

Ini akan membuat widget baru di direktori `lib/resources/widgets/`.

Jika Anda membuka file tersebut, Anda akan dapat mendefinisikan state action Anda.

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

Akhirnya, Anda dapat mengirim action dari mana saja di aplikasi Anda.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - State Action

Halaman juga dapat menerima state action. Ini berguna ketika Anda ingin memicu perilaku tingkat halaman dari widget atau halaman lain.

Pertama, buat halaman yang dikelola state-nya.

``` bash
metro make:page my_page
```

Ini akan membuat halaman baru yang dikelola state-nya bernama `MyPage` di direktori `lib/resources/pages/`.

Jika Anda membuka file tersebut, Anda akan dapat mendefinisikan state action Anda.

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

Akhirnya, Anda dapat mengirim action dari mana saja di aplikasi Anda.

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

Anda juga dapat mendefinisikan state action menggunakan metode `whenStateAction`.

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

Kemudian Anda dapat mengirim action dari mana saja di aplikasi Anda.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Memperbarui State

Anda dapat memperbarui state dengan memanggil metode `updateState()`.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

Ini dapat dipanggil dari mana saja di aplikasi Anda.

**Lihat juga:** [NyState](/docs/{{ $version }}/ny-state) untuk detail lebih lanjut tentang helper state management dan metode siklus hidup.


<div id="building-your-first-widget"></div>

## Membangun Widget Pertama Anda

Di proyek Nylo Anda, jalankan perintah berikut untuk membuat widget baru.

``` bash
metro make:stateful_widget todo_list
```

Ini akan membuat widget `NyState` baru bernama `TodoList`.

> Catatan: Widget baru akan dibuat di direktori `lib/resources/widgets/`.
