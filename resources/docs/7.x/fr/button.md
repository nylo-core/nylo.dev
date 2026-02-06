# Button

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Types de boutons disponibles](#button-types "Types de boutons disponibles")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Etat de chargement asynchrone](#async-loading "Etat de chargement asynchrone")
- [Styles d'animation](#animation-styles "Styles d'animation")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Styles de splash](#splash-styles "Styles de splash")
- [Styles de chargement](#loading-styles "Styles de chargement")
- [Soumission de formulaire](#form-submission "Soumission de formulaire")
- [Personnaliser les boutons](#customizing-buttons "Personnaliser les boutons")
- [Reference des parametres](#parameters "Reference des parametres")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} fournit une classe `Button` avec huit styles de boutons preconstruits. Chaque bouton offre un support integre pour :

- **Etats de chargement asynchrones** -- retournez un `Future` depuis `onPressed` et le bouton affiche automatiquement un indicateur de chargement
- **Styles d'animation** -- choisissez parmi les effets clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph et shake
- **Styles de splash** -- ajoutez un retour tactile ripple, highlight, glow ou ink
- **Soumission de formulaire** -- connectez un bouton directement a une instance `NyFormData`

Vous trouverez les definitions des boutons de votre application dans `lib/resources/widgets/buttons/buttons.dart`. Ce fichier contient une classe `Button` avec des methodes statiques pour chaque type de bouton, facilitant la personnalisation des valeurs par defaut de votre projet.

<div id="basic-usage"></div>

## Utilisation de base

Utilisez la classe `Button` n'importe ou dans vos widgets. Voici un exemple simple dans une page :

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Chaque type de bouton suit le meme schema -- passez un label `text` et un callback `onPressed`.

<div id="button-types"></div>

## Types de boutons disponibles

Tous les boutons sont accessibles via la classe `Button` en utilisant des methodes statiques.

<div id="primary"></div>

### Primary

Un bouton rempli avec une ombre, utilisant la couleur primaire de votre theme. Ideal pour les elements d'appel a l'action principaux.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Un bouton rempli avec une couleur de surface plus douce et une ombre subtile. Adapte aux actions secondaires a cote d'un bouton primaire.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Un bouton transparent avec une bordure. Utile pour les actions moins prominentes ou les boutons d'annulation.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Vous pouvez personnaliser les couleurs de la bordure et du texte :

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Un bouton minimal sans fond ni bordure. Ideal pour les actions en ligne ou les liens.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Vous pouvez personnaliser la couleur du texte :

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Un bouton rempli qui affiche une icone a cote du texte. L'icone apparait avant le texte par defaut.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Vous pouvez personnaliser la couleur d'arriere-plan :

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Un bouton avec un fond en degrade lineaire. Utilise par defaut les couleurs primaire et tertiaire de votre theme.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Vous pouvez fournir des couleurs de degrade personnalisees :

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Un bouton en forme de pilule avec des coins entierement arrondis. Le rayon de bordure est par defaut la moitie de la hauteur du bouton.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Vous pouvez personnaliser la couleur d'arriere-plan et le rayon de bordure :

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Un bouton style verre depoli avec un effet de flou en arriere-plan. Fonctionne bien lorsqu'il est place sur des images ou des fonds colores.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Vous pouvez personnaliser la couleur du texte :

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Etat de chargement asynchrone

L'une des fonctionnalites les plus puissantes des boutons {{ config('app.name') }} est la **gestion automatique de l'etat de chargement**. Lorsque votre callback `onPressed` retourne un `Future`, le bouton affiche automatiquement un indicateur de chargement et desactive l'interaction jusqu'a ce que l'operation soit terminee.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Pendant l'operation asynchrone, le bouton affiche un effet de chargement skeleton (par defaut). Une fois le `Future` termine, le bouton revient a son etat normal.

Cela fonctionne avec toute operation asynchrone -- appels API, ecritures en base de donnees, telechargements de fichiers ou tout ce qui retourne un `Future` :

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

Pas besoin de gerer des variables d'etat `isLoading`, d'appeler `setState` ou d'envelopper quoi que ce soit dans un `StatefulWidget` -- {{ config('app.name') }} gere tout pour vous.

### Fonctionnement

Lorsque le bouton detecte que `onPressed` retourne un `Future`, il utilise le mecanisme `lockRelease` pour :

1. Afficher un indicateur de chargement (controle par `LoadingStyle`)
2. Desactiver le bouton pour empecher les taps en double
3. Attendre la completion du `Future`
4. Restaurer le bouton a son etat normal

<div id="animation-styles"></div>

## Styles d'animation

Les boutons supportent les animations de pression via `ButtonAnimationStyle`. Ces animations fournissent un retour visuel lorsqu'un utilisateur interagit avec un bouton. Vous pouvez definir le style d'animation lors de la personnalisation de vos boutons dans `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Un effet de pression 3D style Duolingo. Le bouton se translate vers le bas lors de la pression et rebondit au relachement. Ideal pour les actions principales et les UX ludiques.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Reduit l'echelle du bouton lors de la pression et rebondit au relachement. Ideal pour les boutons d'ajout au panier, like et favoris.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Une pulsation d'echelle continue et subtile pendant que le bouton est maintenu. Ideal pour les actions de pression longue ou pour attirer l'attention.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Compresse le bouton horizontalement et l'etend verticalement lors de la pression. Ideal pour les interfaces ludiques et interactives.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Un effet de deformation elastique ondulante. Ideal pour les applications ludiques, decontractees ou de divertissement.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Un reflet brillant qui balaie le bouton lors de la pression. Ideal pour les fonctionnalites premium ou les CTA que vous souhaitez mettre en avant.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Un effet de vague ameliore qui s'etend depuis le point de contact. Ideal pour l'accentuation Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

Le rayon de bordure du bouton augmente lors de la pression, creant un effet de changement de forme. Ideal pour un retour subtil et elegant.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Une animation de secousse horizontale. Ideal pour les etats d'erreur ou les actions invalides -- secouez le bouton pour signaler que quelque chose s'est mal passe.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Ajuster l'effet :

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Desactiver les animations

Pour utiliser un bouton sans animation :

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Changer l'animation par defaut

Pour changer l'animation par defaut d'un type de bouton, modifiez votre fichier `lib/resources/widgets/buttons/buttons.dart` :

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Styles de splash

Les effets de splash fournissent un retour tactile visuel sur les boutons. Configurez-les via `ButtonSplashStyle`. Les styles de splash peuvent etre combines avec les styles d'animation pour un retour en couches.

### Styles de splash disponibles

| Splash | Factory | Description |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Ripple Material standard depuis le point de contact |
| Highlight | `ButtonSplashStyle.highlight()` | Mise en surbrillance subtile sans animation de ripple |
| Glow | `ButtonSplashStyle.glow()` | Lueur douce irradiant depuis le point de contact |
| Ink | `ButtonSplashStyle.ink()` | Eclaboussure d'encre rapide, plus rapide et reactive |
| None | `ButtonSplashStyle.none()` | Aucun effet de splash |
| Custom | `ButtonSplashStyle.custom()` | Controle total sur la factory de splash |

### Exemple

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Vous pouvez personnaliser les couleurs et l'opacite du splash :

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Styles de chargement

L'indicateur de chargement affiche pendant les operations asynchrones est controle par `LoadingStyle`. Vous pouvez le definir par type de bouton dans votre fichier de boutons.

### Skeletonizer (par defaut)

Affiche un effet skeleton shimmer sur le bouton :

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Affiche un widget de chargement (par defaut le loader de l'application) :

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Garde le bouton visible mais desactive l'interaction pendant le chargement :

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Soumission de formulaire

Tous les boutons supportent le parametre `submitForm`, qui connecte le bouton a un `NyForm`. Lorsqu'il est touche, le bouton valide le formulaire et appelle votre gestionnaire de succes avec les donnees du formulaire.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

Le parametre `submitForm` accepte un record avec deux valeurs :
1. Une instance `NyFormData` (ou un nom de formulaire comme `String`)
2. Un callback qui recoit les donnees validees

Par defaut, `showToastError` est `true`, ce qui affiche une notification toast lorsque la validation du formulaire echoue. Definissez-le a `false` pour gerer les erreurs silencieusement :

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Lorsque le callback `submitForm` retourne un `Future`, le bouton affiche automatiquement un etat de chargement jusqu'a ce que l'operation asynchrone soit terminee.

<div id="customizing-buttons"></div>

## Personnaliser les boutons

Toutes les valeurs par defaut des boutons sont definies dans votre projet a `lib/resources/widgets/buttons/buttons.dart`. Chaque type de bouton a une classe widget correspondante dans `lib/resources/widgets/buttons/partials/`.

### Modifier les styles par defaut

Pour modifier l'apparence par defaut d'un bouton, editez la classe `Button` :

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Personnaliser un widget de bouton

Pour modifier l'apparence visuelle d'un type de bouton, editez le widget correspondant dans `lib/resources/widgets/buttons/partials/`. Par exemple, pour modifier le rayon de bordure ou l'ombre du bouton primaire :

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Creer un nouveau type de bouton

Pour ajouter un nouveau type de bouton :

1. Creez un nouveau fichier widget dans `lib/resources/widgets/buttons/partials/` etendant `StatefulAppButton`.
2. Implementez la methode `buildButton`.
3. Ajoutez une methode statique dans la classe `Button`.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Puis enregistrez-le dans la classe `Button` :

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Reference des parametres

### Parametres communs (tous les types de boutons)

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `text` | `String` | requis | Le texte du label du bouton |
| `onPressed` | `VoidCallback?` | `null` | Callback lorsque le bouton est touche. Retournez un `Future` pour un etat de chargement automatique |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Record de soumission de formulaire (instance de formulaire, callback de succes) |
| `onFailure` | `Function(dynamic)?` | `null` | Appele lorsque la validation du formulaire echoue |
| `showToastError` | `bool` | `true` | Afficher une notification toast lors d'une erreur de validation |
| `width` | `double?` | `null` | Largeur du bouton (pleine largeur par defaut) |

### Parametres specifiques au type

#### Button.outlined

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `borderColor` | `Color?` | Couleur de bordure du theme | Couleur de la bordure |
| `textColor` | `Color?` | Couleur primaire du theme | Couleur du texte |

#### Button.textOnly

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `textColor` | `Color?` | Couleur primaire du theme | Couleur du texte |

#### Button.icon

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `icon` | `Widget` | requis | Le widget d'icone a afficher |
| `color` | `Color?` | Couleur primaire du theme | Couleur d'arriere-plan |

#### Button.gradient

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `gradientColors` | `List<Color>?` | Couleurs primaire et tertiaire | Points d'arret du degrade |

#### Button.rounded

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `backgroundColor` | `Color?` | Couleur primary container du theme | Couleur d'arriere-plan |
| `borderRadius` | `BorderRadius?` | Forme pilule (hauteur / 2) | Rayon des coins |

#### Button.transparency

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `color` | `Color?` | Adaptatif au theme | Couleur du texte |

### Parametres ButtonAnimationStyle

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `duration` | `Duration` | Varie par type | Duree de l'animation |
| `curve` | `Curve` | Varie par type | Courbe de l'animation |
| `enableHapticFeedback` | `bool` | Varie par type | Declencher un retour haptique a la pression |
| `translateY` | `double` | `4.0` | Clickable : distance de pression verticale |
| `shadowOffset` | `double` | `4.0` | Clickable : profondeur de l'ombre |
| `scaleMin` | `double` | `0.92` | Bounce : echelle minimale a la pression |
| `pulseScale` | `double` | `1.05` | Pulse : echelle maximale pendant la pulsation |
| `squeezeX` | `double` | `0.95` | Squeeze : compression horizontale |
| `squeezeY` | `double` | `1.05` | Squeeze : expansion verticale |
| `jellyStrength` | `double` | `0.15` | Jelly : intensite de l'oscillation |
| `shineColor` | `Color` | `Colors.white` | Shine : couleur du reflet |
| `shineWidth` | `double` | `0.3` | Shine : largeur de la bande de brillance |
| `rippleScale` | `double` | `2.0` | Ripple : echelle d'expansion |
| `morphRadius` | `double` | `24.0` | Morph : rayon de bordure cible |
| `shakeOffset` | `double` | `8.0` | Shake : deplacement horizontal |
| `shakeCount` | `int` | `3` | Shake : nombre d'oscillations |

### Parametres ButtonSplashStyle

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `splashColor` | `Color?` | Couleur de surface du theme | Couleur de l'effet splash |
| `highlightColor` | `Color?` | Couleur de surface du theme | Couleur de l'effet highlight |
| `splashOpacity` | `double` | `0.12` | Opacite du splash |
| `highlightOpacity` | `double` | `0.06` | Opacite du highlight |
| `borderRadius` | `BorderRadius?` | `null` | Rayon de clip du splash |
