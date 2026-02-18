# Modals

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Creare un Modal](#creating-a-modal "Creare un Modal")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Creare un Modal](#creating-a-modal "Creare un Modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parametri](#parameters "Parametri")
  - [Azioni](#actions "Azioni")
  - [Header](#header "Header")
  - [Pulsante di Chiusura](#close-button "Pulsante di Chiusura")
  - [Decorazione Personalizzata](#custom-decoration "Decorazione Personalizzata")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Esempi](#examples "Esempi")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} fornisce un sistema di modal basato su **bottom sheet modal**.

La classe `BottomSheetModal` offre un'API flessibile per visualizzare overlay di contenuto con azioni, header, pulsanti di chiusura e stili personalizzati.

I modal sono utili per:
- Dialoghi di conferma (es. logout, eliminazione)
- Form di input rapidi
- Fogli di azioni con opzioni multiple
- Overlay informativi

<div id="creating-a-modal"></div>

## Creare un Modal

Puoi creare un nuovo modal usando la CLI Metro:

``` bash
metro make:bottom_sheet_modal payment_options
```

Questo genera due cose:

1. **Un widget per il contenuto del modal** in `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

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

2. **Un metodo statico** aggiunto alla tua classe `BottomSheetModal` in `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

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

Puoi quindi visualizzare il modal da qualsiasi punto:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Se un modal con lo stesso nome esiste gia', usa il flag `--force` per sovrascriverlo:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Utilizzo Base

Mostra un modal usando `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Creare un Modal

Il pattern consigliato e' creare una classe `BottomSheetModal` con metodi statici per ogni tipo di modal. Il boilerplate fornisce questa struttura:

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

Chiamalo da qualsiasi punto:

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

`displayModal<T>()` e' il metodo principale per visualizzare i modal.

<div id="parameters"></div>

### Parametri

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | obbligatorio | Build context per il modal |
| `child` | `Widget` | obbligatorio | Widget del contenuto principale |
| `actionsRow` | `List<Widget>` | `[]` | Widget azione visualizzati in una riga orizzontale |
| `actionsColumn` | `List<Widget>` | `[]` | Widget azione visualizzati verticalmente |
| `height` | `double?` | null | Altezza fissa per il modal |
| `header` | `Widget?` | null | Widget header in cima |
| `useSafeArea` | `bool` | `true` | Avvolge il contenuto in SafeArea |
| `isScrollControlled` | `bool` | `false` | Permette al modal di essere scrollabile |
| `showCloseButton` | `bool` | `false` | Mostra un pulsante X di chiusura |
| `headerPadding` | `EdgeInsets?` | null | Padding quando l'header e' presente |
| `backgroundColor` | `Color?` | null | Colore di sfondo del modal |
| `showHandle` | `bool` | `true` | Mostra la maniglia di trascinamento in cima |
| `closeButtonColor` | `Color?` | null | Colore di sfondo del pulsante di chiusura |
| `closeButtonIconColor` | `Color?` | null | Colore dell'icona del pulsante di chiusura |
| `modalDecoration` | `BoxDecoration?` | null | Decorazione personalizzata per il contenitore del modal |
| `handleColor` | `Color?` | null | Colore della maniglia di trascinamento |

<div id="actions"></div>

### Azioni

Le azioni sono pulsanti visualizzati in fondo al modal.

Le **azioni in riga** sono posizionate una accanto all'altra, ciascuna occupa lo stesso spazio:

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

Le **azioni in colonna** sono impilate verticalmente:

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

Aggiungi un header che si posiziona sopra il contenuto principale:

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

### Pulsante di Chiusura

Mostra un pulsante di chiusura nell'angolo in alto a destra:

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

### Decorazione Personalizzata

Personalizza l'aspetto del contenitore del modal:

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

`BottomModalSheetStyle` configura l'aspetto dei bottom sheet modal utilizzati dai picker dei form e da altri componenti:

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

| Proprieta' | Tipo | Descrizione |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Colore di sfondo del modal |
| `barrierColor` | `NyColor?` | Colore dell'overlay dietro il modal |
| `useRootNavigator` | `bool` | Usa il navigator root (predefinito: `false`) |
| `routeSettings` | `RouteSettings?` | Impostazioni della route per il modal |
| `titleStyle` | `TextStyle?` | Stile per il testo del titolo |
| `itemStyle` | `TextStyle?` | Stile per il testo degli elementi della lista |
| `clearButtonStyle` | `TextStyle?` | Stile per il testo del pulsante di cancellazione |

<div id="examples"></div>

## Esempi

### Modal di Conferma

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

### Modal con Contenuto Scrollabile

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

### Foglio di Azioni

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
