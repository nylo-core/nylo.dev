# Modales

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creer une modale](#creating-a-modal "Creer une modale")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Creer une modale](#creating-a-modal "Creer une modale")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parametres](#parameters "Parametres")
  - [Actions](#actions "Actions")
  - [En-tete](#header "En-tete")
  - [Bouton de fermeture](#close-button "Bouton de fermeture")
  - [Decoration personnalisee](#custom-decoration "Decoration personnalisee")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Exemples](#examples "Exemples pratiques")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} fournit un systeme de modales base sur les **modales en feuille inferieure (bottom sheet)**.

La classe `BottomSheetModal` vous offre une API flexible pour afficher des superpositions de contenu avec des actions, des en-tetes, des boutons de fermeture et un style personnalise.

Les modales sont utiles pour :
- Les dialogues de confirmation (par exemple, deconnexion, suppression)
- Les formulaires de saisie rapide
- Les feuilles d'actions avec plusieurs options
- Les superpositions informatives

<div id="creating-a-modal"></div>

## Creer une modale

Vous pouvez creer une nouvelle modale en utilisant le CLI Metro :

``` bash
metro make:bottom_sheet_modal payment_options
```

Cela genere deux choses :

1. **Un widget de contenu de modale** dans `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart` :

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

2. **Une methode statique** ajoutee a votre classe `BottomSheetModal` dans `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart` :

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

Vous pouvez ensuite afficher la modale depuis n'importe ou :

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Si une modale avec le meme nom existe deja, utilisez le flag `--force` pour l'ecraser :

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Utilisation de base

Affichez une modale en utilisant `BottomSheetModal` :

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Creer une modale

Le patron recommande est de creer une classe `BottomSheetModal` avec des methodes statiques pour chaque type de modale. Le code standard fournit cette structure :

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

Appelez-la depuis n'importe ou :

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

`displayModal<T>()` est la methode principale pour afficher des modales.

<div id="parameters"></div>

### Parametres

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `context` | `BuildContext` | requis | Contexte de construction pour la modale |
| `child` | `Widget` | requis | Widget de contenu principal |
| `actionsRow` | `List<Widget>` | `[]` | Widgets d'action affiches en ligne horizontale |
| `actionsColumn` | `List<Widget>` | `[]` | Widgets d'action affiches verticalement |
| `height` | `double?` | null | Hauteur fixe de la modale |
| `header` | `Widget?` | null | Widget d'en-tete en haut |
| `useSafeArea` | `bool` | `true` | Envelopper le contenu dans SafeArea |
| `isScrollControlled` | `bool` | `false` | Permettre le defilement de la modale |
| `showCloseButton` | `bool` | `false` | Afficher un bouton de fermeture X |
| `headerPadding` | `EdgeInsets?` | null | Espacement lorsqu'un en-tete est present |
| `backgroundColor` | `Color?` | null | Couleur de fond de la modale |
| `showHandle` | `bool` | `true` | Afficher la poignee de glissement en haut |
| `closeButtonColor` | `Color?` | null | Couleur de fond du bouton de fermeture |
| `closeButtonIconColor` | `Color?` | null | Couleur de l'icone du bouton de fermeture |
| `modalDecoration` | `BoxDecoration?` | null | Decoration personnalisee du conteneur de la modale |
| `handleColor` | `Color?` | null | Couleur de la poignee de glissement |

<div id="actions"></div>

### Actions

Les actions sont des boutons affiches en bas de la modale.

Les **actions en ligne** sont placees cote a cote, chacune occupant un espace egal :

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

Les **actions en colonne** sont empilees verticalement :

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

### En-tete

Ajoutez un en-tete au-dessus du contenu principal :

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

### Bouton de fermeture

Affichez un bouton de fermeture dans le coin superieur droit :

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

### Decoration personnalisee

Personnalisez l'apparence du conteneur de la modale :

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

`BottomModalSheetStyle` configure l'apparence des modales en feuille inferieure utilisees par les selecteurs de formulaire et d'autres composants :

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

| Propriete | Type | Description |
|-----------|------|-------------|
| `backgroundColor` | `NyColor?` | Couleur de fond de la modale |
| `barrierColor` | `NyColor?` | Couleur de la superposition derriere la modale |
| `useRootNavigator` | `bool` | Utiliser le navigateur racine (defaut : `false`) |
| `routeSettings` | `RouteSettings?` | Parametres de route pour la modale |
| `titleStyle` | `TextStyle?` | Style du texte de titre |
| `itemStyle` | `TextStyle?` | Style du texte des elements de liste |
| `clearButtonStyle` | `TextStyle?` | Style du texte du bouton effacer |

<div id="examples"></div>

## Exemples

### Modale de confirmation

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

### Modale avec contenu defilable

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

### Feuille d'actions

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
