# Modal'lar

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Modal Olu&#351;turma](#creating-a-modal "Modal Olu&#351;turma")
- [Temel Kullan&#305;m](#basic-usage "Temel Kullan&#305;m")
- [Modal Olu&#351;turma](#creating-a-modal "Modal Olu&#351;turma")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parametreler](#parameters "Parametreler")
  - [Eylemler](#actions "Eylemler")
  - [Ba&#351;l&#305;k](#header "Ba&#351;l&#305;k")
  - [Kapatma D&#252;&#287;mesi](#close-button "Kapatma D&#252;&#287;mesi")
  - [&#214;zel Dekorasyon](#custom-decoration "&#214;zel Dekorasyon")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [&#214;rnekler](#examples "Pratik &#214;rnekler")

<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }}, **alt sayfa modal'lar&#305;** etraf&#305;nda in&#351;a edilmi&#351; bir modal sistemi sa&#287;lar.

`BottomSheetModal` s&#305;n&#305;f&#305;, eylemler, ba&#351;l&#305;klar, kapatma d&#252;&#287;meleri ve &#246;zel stil ile i&#231;erik katmanlar&#305; g&#246;r&#252;nt&#252;lemek i&#231;in esnek bir API sunar.

Modal'lar &#351;unlar i&#231;in kullan&#305;&#351;l&#305;d&#305;r:
- Onay diyaloglar&#305; (&#246;rn. &#231;&#305;k&#305;&#351;, silme)
- H&#305;zl&#305; giri&#351; formlar&#305;
- Birden fazla se&#231;enekli eylem sayfalar&#305;
- Bilgilendirme katmanlar&#305;

<div id="creating-a-modal"></div>

## Modal Olu&#351;turma

Metro CLI kullanarak yeni bir modal olu&#351;turabilirsiniz:

``` bash
metro make:bottom_sheet_modal payment_options
```

Bu iki &#351;ey olu&#351;turur:

1. `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart` konumunda **bir modal i&#231;erik widget'&#305;**:

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

2. `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart` dosyas&#305;ndaki `BottomSheetModal` s&#305;n&#305;f&#305;n&#305;za eklenen **statik bir metot**:

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

Ard&#305;ndan modal'&#305; herhangi bir yerden g&#246;r&#252;nt&#252;leyebilirsiniz:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Ayn&#305; isimde bir modal zaten varsa, &#252;zerine yazmak i&#231;in `--force` bayra&#287;&#305;n&#305; kullan&#305;n:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Temel Kullan&#305;m

`BottomSheetModal` kullanarak bir modal g&#246;r&#252;nt&#252;leyin:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Modal Olu&#351;turma

&#214;nerilen yakla&#351;&#305;m, her modal t&#252;r&#252; i&#231;in statik metotlar&#305; olan bir `BottomSheetModal` s&#305;n&#305;f&#305; olu&#351;turmakt&#305;r. &#350;ablon bu yap&#305;y&#305; sa&#287;lar:

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

Herhangi bir yerden &#231;a&#287;&#305;r&#305;n:

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

`displayModal<T>()`, modal'lar&#305; g&#246;r&#252;nt&#252;lemek i&#231;in temel metottur.

<div id="parameters"></div>

### Parametreler

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | zorunlu | Modal i&#231;in build context |
| `child` | `Widget` | zorunlu | Ana i&#231;erik widget'&#305; |
| `actionsRow` | `List<Widget>` | `[]` | Yatay s&#305;rada g&#246;r&#252;nt&#252;lenen eylem widget'lar&#305; |
| `actionsColumn` | `List<Widget>` | `[]` | Dikey olarak g&#246;r&#252;nt&#252;lenen eylem widget'lar&#305; |
| `height` | `double?` | null | Modal i&#231;in sabit y&#252;kseklik |
| `header` | `Widget?` | null | &#220;stteki ba&#351;l&#305;k widget'&#305; |
| `useSafeArea` | `bool` | `true` | &#304;&#231;eri&#287;i SafeArea ile sar |
| `isScrollControlled` | `bool` | `false` | Modal'&#305;n kayd&#305;r&#305;labilir olmas&#305;na izin ver |
| `showCloseButton` | `bool` | `false` | X kapatma d&#252;&#287;mesini g&#246;ster |
| `headerPadding` | `EdgeInsets?` | null | Ba&#351;l&#305;k mevcut oldu&#287;unda dolgu |
| `backgroundColor` | `Color?` | null | Modal arka plan rengi |
| `showHandle` | `bool` | `true` | &#220;stteki s&#252;r&#252;kleme tutamac&#305;n&#305; g&#246;ster |
| `closeButtonColor` | `Color?` | null | Kapatma d&#252;&#287;mesi arka plan rengi |
| `closeButtonIconColor` | `Color?` | null | Kapatma d&#252;&#287;mesi ikon rengi |
| `modalDecoration` | `BoxDecoration?` | null | Modal kapsay&#305;c&#305; i&#231;in &#246;zel dekorasyon |
| `handleColor` | `Color?` | null | S&#252;r&#252;kleme tutamac&#305;n&#305;n rengi |

<div id="actions"></div>

### Eylemler

Eylemler, modal'&#305;n alt&#305;nda g&#246;r&#252;nt&#252;lenen d&#252;&#287;melerdir.

**Sat&#305;r eylemleri** yan yana yerle&#351;tirilir ve her biri e&#351;it alan kaplar:

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

**S&#252;tun eylemleri** dikey olarak istiflenir:

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

### Ba&#351;l&#305;k

Ana i&#231;eri&#287;in &#252;st&#252;ne bir ba&#351;l&#305;k ekleyin:

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

### Kapatma D&#252;&#287;mesi

Sa&#287; &#252;st k&#246;&#351;ede bir kapatma d&#252;&#287;mesi g&#246;r&#252;nt&#252;leyin:

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

### &#214;zel Dekorasyon

Modal kapsay&#305;c&#305;s&#305;n&#305;n g&#246;r&#252;n&#252;m&#252;n&#252; &#246;zelle&#351;tirin:

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

`BottomModalSheetStyle`, form se&#231;icileri ve di&#287;er bile&#351;enler taraf&#305;ndan kullan&#305;lan alt sayfa modal'lar&#305;n&#305;n g&#246;r&#252;n&#252;m&#252;n&#252; yap&#305;land&#305;r&#305;r:

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

| &#214;zellik | T&#252;r | A&#231;&#305;klama |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Modal'&#305;n arka plan rengi |
| `barrierColor` | `NyColor?` | Modal'&#305;n arkas&#305;ndaki katman rengi |
| `useRootNavigator` | `bool` | K&#246;k navigat&#246;r&#252; kullan (varsay&#305;lan: `false`) |
| `routeSettings` | `RouteSettings?` | Modal i&#231;in rota ayarlar&#305; |
| `titleStyle` | `TextStyle?` | Ba&#351;l&#305;k metni stili |
| `itemStyle` | `TextStyle?` | Liste &#246;&#287;esi metni stili |
| `clearButtonStyle` | `TextStyle?` | Temizle d&#252;&#287;mesi metni stili |

<div id="examples"></div>

## &#214;rnekler

### Onay Modal'&#305;

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

### Kayd&#305;r&#305;labilir &#304;&#231;erik Modal'&#305;

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

### Eylem Sayfas&#305;

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
