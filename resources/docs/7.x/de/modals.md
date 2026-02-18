# Modale Dialoge

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Ein Modal erstellen](#creating-a-modal "Ein Modal erstellen")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Ein Modal erstellen](#creating-a-modal "Ein Modal erstellen")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parameter](#parameters "Parameter")
  - [Aktionen](#actions "Aktionen")
  - [Kopfzeile](#header "Kopfzeile")
  - [Schliessen-Schaltflaeche](#close-button "Schliessen-Schaltflaeche")
  - [Benutzerdefinierte Dekoration](#custom-decoration "Benutzerdefinierte Dekoration")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Beispiele](#examples "Beispiele")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} bietet ein Modal-System, das auf **Bottom-Sheet-Modalen** basiert.

Die Klasse `BottomSheetModal` bietet eine flexible API zur Anzeige von Inhalts-Overlays mit Aktionen, Kopfzeilen, Schliessen-Schaltflaechen und benutzerdefiniertem Styling.

Modale sind nuetzlich fuer:
- Bestaetigungsdialoge (z. B. Abmelden, Loeschen)
- Schnelle Eingabeformulare
- Aktionslisten mit mehreren Optionen
- Informations-Overlays

<div id="creating-a-modal"></div>

## Ein Modal erstellen

Sie koennen ein neues Modal mit der Metro-CLI erstellen:

``` bash
metro make:bottom_sheet_modal payment_options
```

Dies erstellt zwei Dinge:

1. **Ein Modal-Inhalts-Widget** unter `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

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

2. **Eine statische Methode**, die Ihrer `BottomSheetModal`-Klasse in `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart` hinzugefuegt wird:

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

Anschliessend koennen Sie das Modal von ueberall aufrufen:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Wenn ein Modal mit demselben Namen bereits existiert, verwenden Sie das `--force`-Flag zum Ueberschreiben:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Grundlegende Verwendung

Zeigen Sie ein Modal mit `BottomSheetModal` an:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Ein Modal erstellen

Das empfohlene Muster ist die Erstellung einer `BottomSheetModal`-Klasse mit statischen Methoden fuer jeden Modal-Typ. Die Projektvorlage bietet diese Struktur:

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

Rufen Sie es von ueberall auf:

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

`displayModal<T>()` ist die Kernmethode zur Anzeige von Modalen.

<div id="parameters"></div>

### Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | erforderlich | Build-Kontext fuer das Modal |
| `child` | `Widget` | erforderlich | Haupt-Inhalts-Widget |
| `actionsRow` | `List<Widget>` | `[]` | Aktions-Widgets in einer horizontalen Reihe |
| `actionsColumn` | `List<Widget>` | `[]` | Aktions-Widgets vertikal angeordnet |
| `height` | `double?` | null | Feste Hoehe fuer das Modal |
| `header` | `Widget?` | null | Kopfzeilen-Widget oben |
| `useSafeArea` | `bool` | `true` | Inhalt in SafeArea einbetten |
| `isScrollControlled` | `bool` | `false` | Modal scrollbar machen |
| `showCloseButton` | `bool` | `false` | X-Schliessen-Schaltflaeche anzeigen |
| `headerPadding` | `EdgeInsets?` | null | Abstand bei vorhandener Kopfzeile |
| `backgroundColor` | `Color?` | null | Hintergrundfarbe des Modals |
| `showHandle` | `bool` | `true` | Ziehgriff oben anzeigen |
| `closeButtonColor` | `Color?` | null | Hintergrundfarbe der Schliessen-Schaltflaeche |
| `closeButtonIconColor` | `Color?` | null | Symbolfarbe der Schliessen-Schaltflaeche |
| `modalDecoration` | `BoxDecoration?` | null | Benutzerdefinierte Dekoration fuer den Modal-Container |
| `handleColor` | `Color?` | null | Farbe des Ziehgriffs |

<div id="actions"></div>

### Aktionen

Aktionen sind Schaltflaechen, die am unteren Rand des Modals angezeigt werden.

**Zeilenaktionen** werden nebeneinander platziert, wobei jede den gleichen Platz einnimmt:

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

**Spaltenaktionen** werden vertikal gestapelt:

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

### Kopfzeile

Fuegen Sie eine Kopfzeile hinzu, die ueber dem Hauptinhalt steht:

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

### Schliessen-Schaltflaeche

Zeigen Sie eine Schliessen-Schaltflaeche in der oberen rechten Ecke an:

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

### Benutzerdefinierte Dekoration

Passen Sie das Erscheinungsbild des Modal-Containers an:

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

`BottomModalSheetStyle` konfiguriert das Erscheinungsbild von Bottom-Sheet-Modalen, die von Formular-Pickern und anderen Komponenten verwendet werden:

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

| Eigenschaft | Typ | Beschreibung |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Hintergrundfarbe des Modals |
| `barrierColor` | `NyColor?` | Farbe des Overlays hinter dem Modal |
| `useRootNavigator` | `bool` | Root-Navigator verwenden (Standard: `false`) |
| `routeSettings` | `RouteSettings?` | Routeneinstellungen fuer das Modal |
| `titleStyle` | `TextStyle?` | Stil fuer den Titeltext |
| `itemStyle` | `TextStyle?` | Stil fuer Listenelementtext |
| `clearButtonStyle` | `TextStyle?` | Stil fuer den Loeschen-Schaltflaechentext |

<div id="examples"></div>

## Beispiele

### Bestaetigungsmodal

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

### Scrollbares Inhaltsmodal

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

### Aktionsliste

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
