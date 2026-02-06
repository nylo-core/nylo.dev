# Alertes

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Styles integres](#built-in-styles "Styles integres")
- [Afficher des alertes depuis les pages](#from-pages "Afficher des alertes depuis les pages")
- [Afficher des alertes depuis les controleurs](#from-controllers "Afficher des alertes depuis les controleurs")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Positionnement](#positioning "Positionnement")
- [Styles de toast personnalises](#custom-styles "Styles de toast personnalises")
  - [Enregistrer des styles](#registering-styles "Enregistrer des styles")
  - [Creer une fabrique de styles](#creating-a-style-factory "Creer une fabrique de styles")
- [AlertTab](#alert-tab "AlertTab")
- [Exemples](#examples "Exemples pratiques")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} fournit un systeme de notifications toast pour afficher des alertes aux utilisateurs. Il est livre avec quatre styles integres -- **success**, **warning**, **info** et **danger** -- et prend en charge les styles personnalises via un patron de registre.

Les alertes peuvent etre declenchees depuis les pages, les controleurs ou partout ou vous disposez d'un `BuildContext`.

<div id="basic-usage"></div>

## Utilisation de base

Affichez une notification toast en utilisant les methodes pratiques dans n'importe quelle page `NyState` :

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

Ou utilisez la fonction globale avec un identifiant de style :

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Styles integres

{{ config('app.name') }} enregistre quatre styles de toast par defaut :

| Identifiant de style | Icone | Couleur | Titre par defaut |
|----------------------|-------|---------|-----------------|
| `success` | Coche | Vert | Success |
| `warning` | Point d'exclamation | Orange | Warning |
| `info` | Icone info | Bleu-vert | Info |
| `danger` | Icone avertissement | Rouge | Error |

Ceux-ci sont configures dans `lib/config/toast_notification.dart` :

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

## Afficher des alertes depuis les pages

Dans toute page etendant `NyState` ou `NyBaseState`, utilisez ces methodes pratiques :

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Methode toast generale

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Afficher des alertes depuis les controleurs

Les controleurs etendant `NyController` disposent des memes methodes pratiques :

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

Methodes disponibles : `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

La fonction globale `showToastNotification()` affiche un toast depuis n'importe ou si vous disposez d'un `BuildContext` :

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### Parametres

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `context` | `BuildContext` | requis | Contexte de construction |
| `id` | `String` | `'success'` | Identifiant du style de toast |
| `title` | `String?` | null | Remplacer le titre par defaut |
| `description` | `String?` | null | Texte de description |
| `duration` | `Duration?` | null | Duree d'affichage du toast |
| `position` | `ToastNotificationPosition?` | null | Position sur l'ecran |
| `action` | `VoidCallback?` | null | Callback au toucher |
| `onDismiss` | `VoidCallback?` | null | Callback a la fermeture |
| `onShow` | `VoidCallback?` | null | Callback a l'affichage |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` encapsule toutes les donnees d'une notification toast :

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

### Proprietes

| Propriete | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `icon` | `Widget?` | null | Widget d'icone |
| `title` | `String` | `''` | Texte du titre |
| `style` | `String` | `''` | Identifiant de style |
| `description` | `String` | `''` | Texte de description |
| `color` | `Color?` | null | Couleur de fond de la section icone |
| `action` | `VoidCallback?` | null | Callback au toucher |
| `dismiss` | `VoidCallback?` | null | Callback du bouton de fermeture |
| `onDismiss` | `VoidCallback?` | null | Callback de fermeture auto/manuelle |
| `onShow` | `VoidCallback?` | null | Callback de visibilite |
| `duration` | `Duration` | 5 secondes | Duree d'affichage |
| `position` | `ToastNotificationPosition` | `top` | Position sur l'ecran |
| `metaData` | `Map<String, dynamic>?` | null | Metadonnees personnalisees |

### copyWith

Creez une copie modifiee de `ToastMeta` :

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Positionnement

Controlez ou les toasts apparaissent sur l'ecran :

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Styles de toast personnalises

<div id="registering-styles"></div>

### Enregistrer des styles

Enregistrez des styles personnalises dans votre `AppProvider` :

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

Ou ajoutez des styles a tout moment :

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

Puis utilisez-le :

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Creer une fabrique de styles

`ToastNotification.style()` cree une `ToastStyleFactory` :

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

| Parametre | Type | Description |
|-----------|------|-------------|
| `icon` | `Widget` | Widget d'icone pour le toast |
| `color` | `Color` | Couleur de fond de la section icone |
| `defaultTitle` | `String` | Titre affiche lorsqu'aucun n'est fourni |
| `position` | `ToastNotificationPosition?` | Position par defaut |
| `duration` | `Duration?` | Duree par defaut |
| `builder` | `Widget Function(ToastMeta)?` | Constructeur de widget personnalise pour un controle total |

### Constructeur entierement personnalise

Pour un controle total sur le widget toast :

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

<div id="alert-tab"></div>

## AlertTab

`AlertTab` est un widget de badge pour ajouter des indicateurs de notification aux onglets de navigation. Il affiche un badge qui peut etre active/desactive et optionnellement persiste dans le stockage.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parametres

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `state` | `String` | requis | Nom d'etat pour le suivi |
| `alertEnabled` | `bool?` | null | Indique si le badge s'affiche |
| `rememberAlert` | `bool?` | `true` | Persister l'etat du badge dans le stockage |
| `icon` | `Widget?` | null | Icone de l'onglet |
| `backgroundColor` | `Color?` | null | Fond de l'onglet |
| `textColor` | `Color?` | null | Couleur du texte du badge |
| `alertColor` | `Color?` | null | Couleur de fond du badge |
| `smallSize` | `double?` | null | Taille du petit badge |
| `largeSize` | `double?` | null | Taille du grand badge |
| `textStyle` | `TextStyle?` | null | Style du texte du badge |
| `padding` | `EdgeInsetsGeometry?` | null | Espacement du badge |
| `alignment` | `Alignment?` | null | Alignement du badge |
| `offset` | `Offset?` | null | Decalage du badge |
| `isLabelVisible` | `bool?` | `true` | Afficher le libelle du badge |

### Constructeur factory

Creez a partir d'un `NavigationTab` :

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Exemples

### Alerte de succes apres enregistrement

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

### Toast interactif avec action

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

### Avertissement positionne en bas

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
