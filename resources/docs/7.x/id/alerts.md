# Alert

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Gaya Bawaan](#built-in-styles "Gaya Bawaan")
- [Menampilkan Alert dari Halaman](#from-pages "Menampilkan Alert dari Halaman")
- [Menampilkan Alert dari Controller](#from-controllers "Menampilkan Alert dari Controller")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Penempatan](#positioning "Penempatan")
- [Gaya Toast Kustom](#custom-styles "Gaya Toast Kustom")
  - [Mendaftarkan Gaya](#registering-styles "Mendaftarkan Gaya")
  - [Membuat Style Factory](#creating-a-style-factory "Membuat Style Factory")
  - [Gaya Toast Berbasis Data](#data-aware-toast-styles "Gaya Toast Berbasis Data")
- [AlertTab](#alert-tab "AlertTab")
- [Contoh](#examples "Contoh")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} menyediakan sistem notifikasi toast untuk menampilkan alert kepada pengguna. Sistem ini dilengkapi dengan empat gaya bawaan -- **success**, **warning**, **info**, dan **danger** -- serta mendukung gaya kustom melalui pola registry.

Alert dapat dipicu dari halaman, controller, atau di mana saja Anda memiliki `BuildContext`.

<div id="basic-usage"></div>

## Penggunaan Dasar

Tampilkan notifikasi toast menggunakan metode praktis di halaman `NyState` mana pun:

``` dart
// Toast sukses
showToastSuccess(description: "Item saved successfully");

// Toast peringatan
showToastWarning(description: "Your session is about to expire");

// Toast info — description bersifat opsional
showToastInfo();

// Toast bahaya dengan durasi kustom
showToastDanger(description: "Failed to save item", duration: Duration(seconds: 5));
```

Atau gunakan fungsi global dengan ID gaya:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Gaya Bawaan

{{ config('app.name') }} mendaftarkan empat gaya toast default:

| ID Gaya | Ikon | Warna | Judul Default |
|----------|------|-------|---------------|
| `success` | Centang | Hijau | Success |
| `warning` | Tanda seru | Oranye | Warning |
| `info` | Ikon info | Teal | Info |
| `danger` | Ikon peringatan | Merah | Error |

Gaya ini dikonfigurasi di `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Menampilkan Alert dari Halaman

Di halaman mana pun yang meng-extend `NyState` atau `NyBaseState`, gunakan metode praktis berikut:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Sukses — description bersifat opsional
    showToastSuccess(description: "Saved!");

    // Dengan judul kustom
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Peringatan dengan durasi kustom
    showToastWarning(description: "Check your input", duration: Duration(seconds: 4));

    // Info — tidak memerlukan description
    showToastInfo();

    // Bahaya dengan payload data yang diteruskan ke gaya toast berbasis data
    showToastDanger(description: "Something went wrong", data: {"code": "ERR_500"});

    // Oops (menggunakan gaya danger)
    showToastOops(description: "That didn't work");

    // Maaf (menggunakan gaya danger)
    showToastSorry(description: "We couldn't process your request");

    // Gaya kustom berdasarkan ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Metode Toast Umum

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Menampilkan Alert dari Controller

Controller yang meng-extend `NyController` memiliki metode praktis yang sama:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Metode yang tersedia: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`. Semua menerima parameter opsional `description`, `duration` (`Duration?`), dan `data` (`Map<String, dynamic>?`).

<div id="show-toast-notification"></div>

## showToastNotification

Fungsi global `showToastNotification()` menampilkan toast dari mana saja yang memiliki `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Dipanggil ketika toast diketuk
    routeTo("/details");
  },
  onDismiss: () {
    // Dipanggil ketika toast dihilangkan
  },
  onShow: () {
    // Dipanggil ketika toast menjadi terlihat
  },
);
```

### Parameter

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | required | Build context |
| `id` | `String` | `'success'` | ID gaya toast |
| `title` | `String?` | null | Teks judul; diteruskan apa adanya ke widget toast |
| `description` | `String?` | null | Teks deskripsi |
| `data` | `Map<String, dynamic>?` | null | Pasangan key-value yang diteruskan ke gaya toast berbasis data; `title` dan `description` diutamakan atas key yang cocok |
| `duration` | `Duration?` | null | Berapa lama toast ditampilkan |
| `position` | `ToastNotificationPosition?` | null | Posisi di layar |
| `action` | `VoidCallback?` | null | Callback saat diketuk |
| `onDismiss` | `VoidCallback?` | null | Callback saat dihilangkan |
| `onShow` | `VoidCallback?` | null | Callback saat ditampilkan |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` mengenkapsulasi semua data untuk notifikasi toast:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Properti

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Widget ikon |
| `title` | `String` | `''` | Teks judul |
| `style` | `String` | `''` | Identifier gaya |
| `description` | `String` | `''` | Teks deskripsi |
| `color` | `Color?` | null | Warna latar belakang untuk bagian ikon |
| `action` | `VoidCallback?` | null | Callback saat diketuk |
| `dismiss` | `VoidCallback?` | null | Callback tombol dismiss |
| `onDismiss` | `VoidCallback?` | null | Callback dismiss otomatis/manual |
| `onShow` | `VoidCallback?` | null | Callback saat terlihat |
| `duration` | `Duration` | 5 detik | Durasi tampil |
| `position` | `ToastNotificationPosition` | `top` | Posisi di layar |
| `metaData` | `Map<String, dynamic>?` | null | Metadata kustom |

### copyWith

Membuat salinan modifikasi dari `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Penempatan

Kontrol di mana toast muncul di layar:

``` dart
// Bagian atas layar (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bagian bawah layar
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Tengah layar
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Gaya Toast Kustom

<div id="registering-styles"></div>

### Mendaftarkan Gaya

Daftarkan gaya kustom di `AppProvider` Anda:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

Atau tambahkan gaya kapan saja:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Kemudian gunakan:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Membuat Style Factory

`ToastNotification.style()` membuat `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Parameter | Tipe | Deskripsi |
|-----------|------|-------------|
| `icon` | `Widget` | Widget ikon untuk toast |
| `color` | `Color` | Warna latar belakang untuk bagian ikon |
| `defaultTitle` | `String` | Judul yang ditampilkan saat tidak ada yang disediakan |
| `position` | `ToastNotificationPosition?` | Posisi default |
| `duration` | `Duration?` | Durasi default |
| `builder` | `Widget Function(ToastMeta)?` | Builder widget kustom untuk kontrol penuh |

### Builder Kustom Penuh

Untuk kontrol penuh atas widget toast:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="data-aware-toast-styles"></div>

### Gaya Toast Berbasis Data

<!-- uncertain: new Nylo-specific term "ToastStyleDataFactory" — not seen in existing locale file -->
Gunakan `ToastStyleDataFactory` untuk mendaftarkan gaya toast yang menerima data saat dipanggil. Ini berguna ketika konten toast — seperti nama atau avatar pengguna — belum diketahui saat pendaftaran.

``` dart
typedef ToastStyleDataFactory =
    ToastStyleFactory Function(Map<String, dynamic> data);
```

Daftarkan gaya berbasis data dengan `registerWithData()`:

``` dart
ToastNotificationRegistry.instance.registerWithData(
  'new_follower',
  (data) => (meta, updateMeta) {
    return Container(
      padding: EdgeInsets.all(16),
      child: Row(
        children: [
          CircleAvatar(backgroundImage: NetworkImage(data['avatar'])),
          SizedBox(width: 12),
          Text("${data['name']} followed you"),
        ],
      ),
    );
  },
);
```

Atau daftarkan bersama gaya statis di `AppProvider` Anda:

``` dart
nylo.addToastNotifications({
  ...ToastNotificationConfig.styles,
  'new_follower': (Map<String, dynamic> data) => (meta, updateMeta) {
    return Container(
      padding: EdgeInsets.all(16),
      child: Row(
        children: [
          CircleAvatar(backgroundImage: NetworkImage(data['avatar'])),
          SizedBox(width: 12),
          Text("${data['name']} followed you"),
        ],
      ),
    );
  },
});
```

Panggil dengan map `data` saat runtime:

``` dart
showToastNotification(
  context,
  id: 'new_follower',
  data: {'name': 'Alice', 'avatar': 'https://example.com/alice.jpg'},
);
```

Jika Anda juga meneruskan `title` atau `description`, keduanya diutamakan atas key yang cocok di `data`.

Gunakan `ToastNotificationRegistry.resolve(id, data)` langsung jika Anda perlu membuat widget sendiri:

``` dart
final factory = ToastNotificationRegistry.instance.resolve('new_follower', data);
if (factory != null) {
  final widget = factory(toastMeta, (updated) {});
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` adalah widget badge untuk menambahkan indikator notifikasi ke tab navigasi. Widget ini menampilkan badge yang dapat di-toggle dan opsional disimpan secara persisten ke storage.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parameter

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `state` | `String` | required | Nama state untuk pelacakan |
| `alertEnabled` | `bool?` | null | Apakah badge ditampilkan |
| `rememberAlert` | `bool?` | `true` | Menyimpan state badge ke storage secara persisten |
| `icon` | `Widget?` | null | Ikon tab |
| `backgroundColor` | `Color?` | null | Latar belakang tab |
| `textColor` | `Color?` | null | Warna teks badge |
| `alertColor` | `Color?` | null | Warna latar belakang badge |
| `smallSize` | `double?` | null | Ukuran badge kecil |
| `largeSize` | `double?` | null | Ukuran badge besar |
| `textStyle` | `TextStyle?` | null | Gaya teks badge |
| `padding` | `EdgeInsetsGeometry?` | null | Padding badge |
| `alignment` | `Alignment?` | null | Perataan badge |
| `offset` | `Offset?` | null | Offset badge |
| `isLabelVisible` | `bool?` | `true` | Menampilkan label badge |

### Factory Constructor

Membuat dari `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Contoh

### Alert Sukses Setelah Menyimpan

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Toast Interaktif dengan Aksi

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Peringatan di Posisi Bawah

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
