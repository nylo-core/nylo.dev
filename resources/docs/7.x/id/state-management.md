# Widget Berstate Terkelola

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Pilih Pola Anda](#choose-your-pattern "Pilih Pola Anda")
- [State Action](#state-actions "State Action")
  - [Mendefinisikan Handler](#defining-handlers "Mendefinisikan Handler")
  - [Memicu Aksi](#triggering-actions "Memicu Aksi")
  - [Handler Dengan dan Tanpa Data](#handlers-with-and-without-data "Handler Dengan dan Tanpa Data")
  - [Menggunakan Instance StateActions](#using-a-state-actions-instance "Menggunakan Instance StateActions")
- [Pola: Halaman Berstate Terkelola (NyPage)](#pattern-ny-page "Pola: Halaman Berstate Terkelola")
- [Pola: Stateful Widgets (NyState)](#pattern-ny-state "Pola: Stateful Widgets")
- [Pola: Widget Berstate Terkelola (NyStateManaged)](#pattern-ny-state-managed "Pola: Widget Berstate Terkelola")
  - [Lanjutan: Beberapa Instance Terisolasi](#advanced-multiple-isolated-instances "Lanjutan: Beberapa Instance Terisolasi")
- [Referensi Siklus Hidup](#lifecycle "Referensi Siklus Hidup")

<div id="introduction"></div>

## Pengantar

Widget berstate terkelola memungkinkan Anda memperbarui bagian tertentu dari UI dari mana saja di aplikasi Anda — tanpa membangun ulang seluruh halaman. Di {{ config('app.name') }} v7, Anda memicu **state action** bernama pada widget atau halaman target, dan handler yang cocok akan dijalankan.

Model mentalnya sederhana:

- Setiap widget atau halaman berstate terkelola memiliki **state key** unik (sebuah string)
- Widget/halaman tersebut mendefinisikan map **aksi bernama** yang dapat dihandlenya
- Dari mana saja di aplikasi Anda, Anda dapat memanggil
```
stateAction("action_name", state: TargetWidget.state)
``` 

Ada tiga pola untuk membangun UI berstate terkelola. Semuanya berbagi mekanisme `stateAction` yang sama — satu-satunya perbedaan adalah jenis UI yang Anda kelola.


<div id="choose-your-pattern"></div>

## Pilih Pola Anda

| Anda ingin... | Gunakan | Perintah scaffold |
|---|---|---|
| Mengelola state halaman penuh | `NyPage` | `metro make:page my_page` |
| Mengelola state widget dengan satu instance | `NyState` | `metro make:stateful_widget my_widget` |
| Mengelola state widget dengan beberapa instance terisolasi | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Baca bagian [State Action](#state-actions) terlebih dahulu — ini menjelaskan API yang digunakan setiap pola. Kemudian lompat ke pola yang sesuai dengan kasus Anda.


<div id="state-actions"></div>

## State Action

State action adalah perintah bernama yang diketahui cara handlenya oleh sebuah widget atau halaman. Anda mendefinisikan map nama aksi ke fungsi handler, dan memicunya berdasarkan nama dari mana saja di aplikasi Anda.

Gunakan state action ketika Anda perlu:
- Memicu perilaku spesifik pada widget atau halaman (bukan hanya refresh umum)
- Meneruskan data ke widget dan membuatnya merespons dengan cara yang telah ditentukan
- Membangun perilaku widget yang dapat digunakan kembali yang dapat dipanggil dari beberapa titik

<div id="defining-handlers"></div>

### Mendefinisikan Handler

Handler berada di getter `stateActions` pada class `NyState` atau `NyPage` Anda. Kunci map adalah nama aksi; nilainya adalah fungsi yang dijalankan ketika aksi tersebut dipicu.

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

Handler bertanggung jawab memanggil `setState` sendiri jika ingin widget melakukan rebuild.

Handler `apply_discount` di atas menerima argumen `code` — deklarasikan satu parameter posisional ketika handler Anda membutuhkan payload yang diteruskan melalui
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Gunakan bentuk tanpa argumen `()` ketika aksi tidak membawa payload.


<div id="triggering-actions"></div>

### Memicu Aksi

Gunakan fungsi tingkat atas `stateAction` untuk memicu aksi dari mana saja — widget lain, controller, event handler, API callback, dll.

``` dart
// Memicu aksi tanpa data
stateAction("clear_cart", state: Cart.state);

// Memicu aksi dengan data
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

Argumen `state:` adalah **state key** dari target:
- Untuk widget — `MyWidget.state` (sebuah string)
- Untuk halaman — `MyPage.path` (route)


<div id="handlers-with-and-without-data"></div>

### Handler Dengan dan Tanpa Data

Handler bisa sinkron atau asinkron, dan bisa didefinisikan dengan atau tanpa argumen `data`:

``` dart
@override
Map<String, Function> get stateActions => {
  // Tanpa data — handler berjalan apa adanya
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Dengan data — menerima apapun yang diteruskan melalui argumen `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async didukung — framework menunggu handler selesai
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Jika Anda meneruskan `data:` ke `stateAction` tetapi handler Anda tidak menerima argumen, data tersebut akan diabaikan begitu saja.


<div id="using-a-state-actions-instance"></div>

### Menggunakan Instance StateActions

Jika sebuah widget mengekspos instance `StateActions` bertipe (seringkali melalui metode static `stateActions(stateName)`), Anda dapat memanggil `.action(...)` langsung padanya alih-alih menggunakan fungsi bebas. Ini lebih bersih ketika memicu beberapa aksi pada target yang sama:

``` dart
// Menggunakan fungsi bebas
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Menggunakan instance StateActions — setara, lebih sedikit pengulangan
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Beberapa widget bawaan (`InputField`, `CollectionView`, `LanguageSwitcher`, keluarga `NyForm*`) dilengkapi dengan class `StateActions` bertipe dengan metode bernama seperti `.clear()`, `.setValue(...)`, `.refresh()` — periksa dokumentasi widget untuk mengetahui apa yang tersedia.


<div id="pattern-ny-page"></div>

## Pola: Halaman Berstate Terkelola (NyPage)

Gunakan ini ketika Anda ingin memicu perilaku pada halaman penuh dari tempat lain di aplikasi Anda — misalnya, me-refresh halaman ketika sebuah event dipicu, atau menghapus state form dari widget anak.

**Langkah 1:** Scaffold sebuah halaman.

``` bash
metro make:page my_page
```

Ini menghasilkan `NyPage` di `lib/resources/pages/`:

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

**Langkah 2:** Ubah `stateManaged` menjadi `true`.

Secara default, halaman **tidak** berlangganan state event — ini menghindari listener yang tidak perlu pada halaman yang tidak membutuhkannya. Untuk mengaktifkan state action pada sebuah halaman, ubah getter-nya:

``` dart
@override
bool get stateManaged => true;
```

**Langkah 3:** Tambahkan map `stateActions`.

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

**Langkah 4:** Picu aksi dari mana saja menggunakan `path` halaman.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Halaman menggunakan `MyPage.path` sebagai kunci, widget menggunakan `MyWidget.state`. Inilah satu-satunya perbedaan di sisi pemanggil.


<div id="pattern-ny-state"></div>

## Pola: Stateful Widgets (NyState)

Gunakan ini untuk widget yang dapat digunakan kembali yang ada sebagai satu instance per halaman — kartu profil, bilah header, indikator status. Jika Anda hanya merender satu instance widget pada satu waktu, inilah pola yang tepat.

**Langkah 1:** Scaffold sebuah stateful widget.

``` bash
metro make:stateful_widget profile_card
```

Ini menghasilkan berikut di `lib/resources/widgets/`:

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

Scaffold memberi Anda widget `NyState` yang berfungsi — tetapi **belum dikelola statenya**. Untuk membuatnya merespons state action, Anda perlu menambahkan empat hal:

1. Kunci `state` pada class widget — string unik yang akan ditarget oleh bagian lain aplikasi Anda
2. Parameter constructor yang menerima state key dan meneruskannya ke class state
3. Constructor pada class state yang menetapkan `stateName`
4. Map `stateActions` yang mendefinisikan handler

**Langkah 2:** Konversi scaffold menjadi widget berstate terkelola.

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
    // ^ panggil ini dari mana saja di aplikasi Anda untuk memicu handler di bawah
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

Yang berubah:
- `static String get state => "profile_card";` mendefinisikan state key publik
- `createState() => _ProfileCardState(state)` meneruskan kunci ke class state
- `_ProfileCardState(String? state) { this.stateName = state; }` mendaftarkan instance state ini di bawah kunci tersebut, sehingga framework tahu widget mana yang akan menerima aksi
- `stateActions` mendeklarasikan handler bernama

**Langkah 3:** Picu dari mana saja.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Anda dapat menambahkan handler sebanyak yang Anda butuhkan, dengan atau tanpa data:

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

## Pola: Widget Berstate Terkelola (NyStateManaged)

Gunakan ini ketika Anda membutuhkan **beberapa instance independen dari widget yang sama** di layar sekaligus — misalnya, badge keranjang di header dan satu lagi di sidebar yang keduanya harus diperbarui secara independen.

`NyStateManaged` menambahkan parameter `id` sehingga setiap instance dapat dialamatkan secara terpisah. Jika Anda hanya merender satu instance widget, gunakan `NyState` — lebih sederhana.

**Langkah 1:** Scaffold widget berstate terkelola.

``` bash
metro make:state_managed_widget cart
```

Ini menghasilkan berikut di `lib/resources/widgets/`:

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
    // logika inisialisasi di sini
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Panggil aksi dari mana saja di aplikasi Anda
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

**Langkah 2:** Lengkapi state — muat data di `init`, definisikan handler, dan render.

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

**Langkah 3:** Picu aksi menggunakan helper static `action()` yang dihasilkan.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Ini setara dengan `stateAction("reload_cart", state: Cart.state)` — helper static hanya menghilangkan boilerplate.


<div id="advanced-multiple-isolated-instances"></div>

### Lanjutan: Beberapa Instance Terisolasi

Alasan `NyStateManaged` ada adalah untuk mendukung beberapa instance independen dari widget yang sama. Setiap instance mendapatkan `id` sendiri, yang menghasilkan state key bernamespace.

Render dua keranjang dengan id berbeda:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Sekarang Anda dapat memperbarui keduanya secara independen:

``` dart
// Reload hanya keranjang header
Cart.action("reload_cart", id: "header");

// Reload hanya keranjang sidebar
Cart.action("reload_cart", id: "sidebar");

// Tanpa id — menarget instance default yang tidak bernama
Cart.action("reload_cart");
```

`NyStateManaged` menangani namespace: `Cart(id: "header")` mendaftar di bawah kunci `"cart_header"`, dan `Cart.action(..., id: "header")` menarget kunci tersebut secara tepat.


<div id="lifecycle"></div>

## Referensi Siklus Hidup

Widget dan halaman berstate terkelola berbagi dua hook siklus hidup utama:

1. **`init()`** — dipanggil sekali ketika state pertama kali dibuat. Gunakan untuk memuat data awal.

2. **`stateUpdated(data)`** — dipanggil setiap kali state action dipicu terhadap state ini. Argumen `data` adalah payload lengkap (termasuk nama aksi dan data aksi). Override ini jika Anda perlu bereaksi terhadap *setiap* state action — sebagian besar waktu, mendefinisikan handler di `stateActions` adalah yang Anda inginkan.

**Lihat juga:** [NyState](/docs/{{ $version }}/ny-state) untuk set lengkap helper state dan metode siklus hidup.
