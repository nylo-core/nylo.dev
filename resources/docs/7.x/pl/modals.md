# Modals

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Tworzenie modala](#creating-a-modal "Tworzenie modala")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Tworzenie modala](#creating-a-modal "Tworzenie modala")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parametry](#parameters "Parametry")
  - [Akcje](#actions "Akcje")
  - [Nagłówek](#header "Nagłówek")
  - [Przycisk zamykania](#close-button "Przycisk zamykania")
  - [Niestandardowa dekoracja](#custom-decoration "Niestandardowa dekoracja")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Przykłady](#examples "Praktyczne przykłady")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} udostępnia system modali oparty na **dolnych arkuszach modalnych** (bottom sheet modals).

Klasa `BottomSheetModal` zapewnia elastyczne API do wyświetlania nakładek z treścią, akcjami, nagłówkami, przyciskami zamykania oraz niestandardowym stylem.

Modale są przydatne do:
- Dialogów potwierdzenia (np. wylogowanie, usunięcie)
- Szybkich formularzy wejściowych
- Arkuszy akcji z wieloma opcjami
- Nakładek informacyjnych

<div id="creating-a-modal"></div>

## Tworzenie modala

Nowy modal możesz utworzyć za pomocą Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

To generuje dwie rzeczy:

1. **Widget treści modala** w `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

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

2. **Metoda statyczna** dodana do klasy `BottomSheetModal` w `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

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

Następnie możesz wyświetlić modal z dowolnego miejsca:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Jeśli modal o tej samej nazwie już istnieje, użyj flagi `--force`, aby go nadpisać:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Podstawowe użycie

Wyświetl modal za pomocą `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Tworzenie modala

Zalecany wzorzec to stworzenie klasy `BottomSheetModal` z metodami statycznymi dla każdego typu modala. Szablon projektu zapewnia tę strukturę:

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

Wywołaj go z dowolnego miejsca:

``` dart
BottomSheetModal.showLogout(context);

// Z niestandardowymi callbackami
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

`displayModal<T>()` to główna metoda wyświetlania modali.

<div id="parameters"></div>

### Parametry

| Parametr | Typ | Domyślnie | Opis |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | wymagany | Kontekst build dla modala |
| `child` | `Widget` | wymagany | Główny widget treści |
| `actionsRow` | `List<Widget>` | `[]` | Widgety akcji wyświetlane w wierszu poziomym |
| `actionsColumn` | `List<Widget>` | `[]` | Widgety akcji wyświetlane pionowo |
| `height` | `double?` | null | Stała wysokość modala |
| `header` | `Widget?` | null | Widget nagłówka na górze |
| `useSafeArea` | `bool` | `true` | Opakowuje treść w SafeArea |
| `isScrollControlled` | `bool` | `false` | Umożliwia przewijanie modala |
| `showCloseButton` | `bool` | `false` | Wyświetla przycisk zamykania X |
| `headerPadding` | `EdgeInsets?` | null | Padding gdy obecny jest nagłówek |
| `backgroundColor` | `Color?` | null | Kolor tła modala |
| `showHandle` | `bool` | `true` | Wyświetla uchwyt do przeciągania na górze |
| `closeButtonColor` | `Color?` | null | Kolor tła przycisku zamykania |
| `closeButtonIconColor` | `Color?` | null | Kolor ikony przycisku zamykania |
| `modalDecoration` | `BoxDecoration?` | null | Niestandardowa dekoracja kontenera modala |
| `handleColor` | `Color?` | null | Kolor uchwytu do przeciągania |

<div id="actions"></div>

### Akcje

Akcje to przyciski wyświetlane na dole modala.

**Akcje wierszowe** są umieszczane obok siebie, każda zajmuje równą przestrzeń:

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

**Akcje kolumnowe** są układane pionowo:

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

### Nagłówek

Dodaj nagłówek umieszczony nad główną treścią:

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

### Przycisk zamykania

Wyświetl przycisk zamykania w prawym górnym rogu:

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

### Niestandardowa dekoracja

Dostosuj wygląd kontenera modala:

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

`BottomModalSheetStyle` konfiguruje wygląd dolnych arkuszy modalnych używanych przez selektory formularzy i inne komponenty:

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

| Właściwość | Typ | Opis |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Kolor tła modala |
| `barrierColor` | `NyColor?` | Kolor nakładki za modalem |
| `useRootNavigator` | `bool` | Użyj głównego nawigatora (domyślnie: `false`) |
| `routeSettings` | `RouteSettings?` | Ustawienia trasy dla modala |
| `titleStyle` | `TextStyle?` | Styl tekstu tytułu |
| `itemStyle` | `TextStyle?` | Styl tekstu elementów listy |
| `clearButtonStyle` | `TextStyle?` | Styl tekstu przycisku czyszczenia |

<div id="examples"></div>

## Przykłady

### Modal potwierdzenia

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

### Modal z przewijaną treścią

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

### Arkusz akcji

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
