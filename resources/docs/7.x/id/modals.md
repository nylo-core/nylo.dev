# Modal

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Membuat Modal](#creating-a-modal "Membuat Modal")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Membuat Modal](#creating-a-modal "Membuat Modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parameter](#parameters "Parameter")
  - [Aksi](#actions "Aksi")
  - [Header](#header "Header")
  - [Tombol Tutup](#close-button "Tombol Tutup")
  - [Dekorasi Kustom](#custom-decoration "Dekorasi Kustom")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Contoh](#examples "Contoh Praktis")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} menyediakan sistem modal yang dibangun di atas **bottom sheet modal**.

Kelas `BottomSheetModal` memberi Anda API yang fleksibel untuk menampilkan overlay konten dengan aksi, header, tombol tutup, dan styling kustom.

Modal berguna untuk:
- Dialog konfirmasi (misalnya, logout, hapus)
- Form input cepat
- Action sheet dengan beberapa opsi
- Overlay informasi

<div id="creating-a-modal"></div>

## Membuat Modal

Anda dapat membuat modal baru menggunakan Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

Ini menghasilkan dua hal:

1. **Widget konten modal** di `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **Metode statis** yang ditambahkan ke kelas `BottomSheetModal` Anda di `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

Anda kemudian dapat menampilkan modal dari mana saja:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Jika modal dengan nama yang sama sudah ada, gunakan flag `--force` untuk menimpanya:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Penggunaan Dasar

Tampilkan modal menggunakan `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Membuat Modal

Pola yang direkomendasikan adalah membuat kelas `BottomSheetModal` dengan metode statis untuk setiap tipe modal. Boilerplate menyediakan struktur ini:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

Panggil dari mana saja:

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` adalah metode inti untuk menampilkan modal.

<div id="parameters"></div>

### Parameter

| Parameter | Tipe | Default | Deskripsi |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | required | Build context untuk modal |
| `child` | `Widget` | required | Widget konten utama |
| `actionsRow` | `List<Widget>` | `[]` | Widget aksi ditampilkan dalam baris horizontal |
| `actionsColumn` | `List<Widget>` | `[]` | Widget aksi ditampilkan secara vertikal |
| `height` | `double?` | null | Tinggi tetap untuk modal |
| `header` | `Widget?` | null | Widget header di bagian atas |
| `useSafeArea` | `bool` | `true` | Membungkus konten dalam SafeArea |
| `isScrollControlled` | `bool` | `false` | Mengizinkan modal dapat di-scroll |
| `showCloseButton` | `bool` | `false` | Menampilkan tombol tutup X |
| `headerPadding` | `EdgeInsets?` | null | Padding saat header ada |
| `backgroundColor` | `Color?` | null | Warna latar belakang modal |
| `showHandle` | `bool` | `true` | Menampilkan handle tarik di bagian atas |
| `closeButtonColor` | `Color?` | null | Warna latar belakang tombol tutup |
| `closeButtonIconColor` | `Color?` | null | Warna ikon tombol tutup |
| `modalDecoration` | `BoxDecoration?` | null | Dekorasi kustom untuk container modal |
| `handleColor` | `Color?` | null | Warna handle tarik |

<div id="actions"></div>

### Aksi

Aksi adalah tombol yang ditampilkan di bagian bawah modal.

**Aksi baris** ditempatkan berdampingan, masing-masing mengambil ruang yang sama:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**Aksi kolom** ditumpuk secara vertikal:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### Header

Tambahkan header yang berada di atas konten utama:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### Tombol Tutup

Tampilkan tombol tutup di pojok kanan atas:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### Dekorasi Kustom

Kustomisasi tampilan container modal:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` mengkonfigurasi tampilan bottom sheet modal yang digunakan oleh form picker dan komponen lainnya:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| Properti | Tipe | Deskripsi |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Warna latar belakang modal |
| `barrierColor` | `NyColor?` | Warna overlay di belakang modal |
| `useRootNavigator` | `bool` | Menggunakan root navigator (default: `false`) |
| `routeSettings` | `RouteSettings?` | Pengaturan rute untuk modal |
| `titleStyle` | `TextStyle?` | Style untuk teks judul |
| `itemStyle` | `TextStyle?` | Style untuk teks item daftar |
| `clearButtonStyle` | `TextStyle?` | Style untuk teks tombol hapus |

<div id="examples"></div>

## Contoh

### Modal Konfirmasi

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### Modal Konten yang Dapat Di-scroll

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### Action Sheet

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
