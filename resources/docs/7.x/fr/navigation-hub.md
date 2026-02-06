# Navigation Hub

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
  - [Creer un Navigation Hub](#creating-a-navigation-hub "Creer un Navigation Hub")
  - [Creer des onglets de navigation](#creating-navigation-tabs "Creer des onglets de navigation")
  - [Navigation inferieure](#bottom-navigation "Navigation inferieure")
    - [Styles de navigation inferieure](#bottom-nav-styles "Styles de navigation inferieure")
    - [Constructeur de barre de navigation personnalise](#custom-nav-bar-builder "Constructeur de barre de navigation personnalise")
  - [Navigation superieure](#top-navigation "Navigation superieure")
  - [Navigation journey](#journey-navigation "Navigation journey")
    - [Styles de progression](#journey-progress-styles "Styles de progression journey")
    - [Styles de boutons](#journey-button-styles "Styles de boutons")
    - [JourneyState](#journey-state "JourneyState")
    - [Methodes d'aide JourneyState](#journey-state-helper-methods "Methodes d'aide JourneyState")
- [Naviguer au sein d'un onglet](#navigating-within-a-tab "Naviguer au sein d'un onglet")
- [Onglets](#tabs "Onglets")
  - [Ajouter des badges aux onglets](#adding-badges-to-tabs "Ajouter des badges aux onglets")
  - [Ajouter des alertes aux onglets](#adding-alerts-to-tabs "Ajouter des alertes aux onglets")
- [Maintien de l'etat](#maintaining-state "Maintien de l'etat")
- [Actions d'etat](#state-actions "Actions d'etat")
- [Style de chargement](#loading-style "Style de chargement")
- [Creer un Navigation Hub](#creating-a-navigation-hub "Creer un Navigation Hub")

<div id="introduction"></div>

## Introduction

Les Navigation Hubs sont un endroit central ou vous pouvez **gerer** la navigation de tous vos widgets.
Par defaut, vous pouvez creer des mises en page de navigation inferieure, superieure et journey en quelques secondes.

**Imaginons** que vous avez une application et que vous souhaitez ajouter une barre de navigation inferieure permettant aux utilisateurs de naviguer entre differents onglets dans votre application.

Vous pouvez utiliser un Navigation Hub pour construire cela.

Voyons comment vous pouvez utiliser un Navigation Hub dans votre application.

<div id="basic-usage"></div>

## Utilisation de base

Vous pouvez creer un Navigation Hub en utilisant la commande ci-dessous.

``` bash
metro make:navigation_hub base
```

Cela creera un fichier **base_navigation_hub.dart** dans votre repertoire `resources/pages/`.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layouts:
  /// - [NavigationHubLayout.bottomNav] Bottom navigation
  /// - [NavigationHubLayout.topNav] Top navigation
  /// - [NavigationHubLayout.journey] Journey navigation
  NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
    // backgroundColor: Colors.white,
  );

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// Navigation pages
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
    };
  });
}
```

Vous pouvez voir que le Navigation Hub a **deux** onglets, Home et Settings.

Vous pouvez creer plus d'onglets en ajoutant des NavigationTab au Navigation Hub.

Tout d'abord, vous devez creer un nouveau widget en utilisant Metro.

``` bash
metro make:stateful_widget create_advert_tab
```

Vous pouvez egalement creer plusieurs widgets en une seule fois.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Ensuite, vous pouvez ajouter le nouveau widget au Navigation Hub.

``` dart
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
      2: NavigationTab(
         title: "News",
         page: NewsTab(),
         icon: Icon(Icons.newspaper),
         activeIcon: Icon(Icons.newspaper),
      ),
    };
  });

import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Il y a **beaucoup plus** que vous pouvez faire avec un Navigation Hub, explorons certaines des fonctionnalites.

<div id="bottom-navigation"></div>

### Navigation inferieure

Vous pouvez changer la mise en page en une barre de navigation inferieure en definissant le **layout** pour utiliser `NavigationHubLayout.bottomNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

Vous pouvez personnaliser la barre de navigation inferieure en definissant des proprietes comme suit :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // customize the bottomNav layout properties
    );
```

<div id="bottom-nav-styles"></div>

### Styles de navigation inferieure

Vous pouvez appliquer des styles predefinis a votre barre de navigation inferieure en utilisant le parametre `style`.

Nylo fournit plusieurs styles integres :

- `BottomNavStyle.material()` - Style material Flutter par defaut
- `BottomNavStyle.glass()` - Effet verre depoli style iOS 26 avec flou
- `BottomNavStyle.floating()` - Barre de navigation flottante en forme de pilule avec coins arrondis

#### Style Glass

Le style glass cree un magnifique effet de verre depoli, parfait pour les designs modernes inspires d'iOS 26.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

Vous pouvez personnaliser l'effet glass :

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.glass(
        blur: 15.0,                              // Blur intensity
        opacity: 0.7,                            // Background opacity
        borderRadius: BorderRadius.circular(20), // Rounded corners
        margin: EdgeInsets.all(16),              // Float above the edge
        backgroundColor: Colors.white.withValues(alpha: 0.8),
    ),
)
```

#### Style Floating

Le style floating cree une barre de navigation en forme de pilule qui flotte au-dessus du bord inferieur.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

Vous pouvez personnaliser le style floating :

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.floating(
        borderRadius: BorderRadius.circular(30),
        margin: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shadow: BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
        ),
        backgroundColor: Colors.white,
    ),
)
```

<div id="custom-nav-bar-builder"></div>

### Constructeur de barre de navigation personnalise

Pour un controle total sur votre barre de navigation, vous pouvez utiliser le parametre `navBarBuilder`.

Cela vous permet de construire n'importe quel widget personnalise tout en recevant les donnees de navigation.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

L'objet `NavBarData` contient :

| Propriete | Type | Description |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Les elements de la barre de navigation |
| `currentIndex` | `int` | L'index actuellement selectionne |
| `onTap` | `ValueChanged<int>` | Callback lorsqu'un onglet est touche |

Voici un exemple de barre de navigation glass entierement personnalisee :

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Note :** Lors de l'utilisation de `navBarBuilder`, le parametre `style` est ignore.

<div id="top-navigation"></div>

### Navigation superieure

Vous pouvez changer la mise en page en une barre de navigation superieure en definissant le **layout** pour utiliser `NavigationHubLayout.topNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

Vous pouvez personnaliser la barre de navigation superieure en definissant des proprietes comme suit :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### Navigation journey

Vous pouvez changer la mise en page en une navigation journey en definissant le **layout** pour utiliser `NavigationHubLayout.journey`.

C'est ideal pour les flux d'onboarding ou les formulaires multi-etapes.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

Si vous souhaitez utiliser la mise en page de navigation journey, vos **widgets** devraient utiliser `JourneyState` car il contient de nombreuses methodes d'aide pour gerer le parcours.

Vous pouvez creer un JourneyState en utilisant la commande ci-dessous.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
Cela creera les fichiers suivants dans votre repertoire **resources/widgets/** : `welcome.dart`, `phone_number_step.dart` et `add_photos_step.dart`.

Vous pouvez ensuite ajouter les nouveaux widgets au Navigation Hub.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

La mise en page de navigation journey gerera automatiquement les boutons precedent et suivant si vous definissez un `buttonStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

Vous pouvez egalement personnaliser la logique dans vos widgets.

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class WelcomeStep extends StatefulWidget {
  const WelcomeStep({super.key});

  @override
  createState() => _WelcomeStepState();
}

class _WelcomeStepState extends JourneyState<WelcomeStep> {
  _WelcomeStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeStep', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: onNextPressed,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
  }

  /// Check if the journey can continue to the next step
  /// Override this method to add validation logic
  Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
  }

  /// Called when unable to continue (canContinue returns false)
  /// Override this method to handle validation failures
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// Called before navigating to the next step
  /// Override this method to perform actions before continuing
  Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
  }

  /// Called after navigating to the next step
  /// Override this method to perform actions after continuing
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// Called when the journey is complete (at the last step)
  /// Override this method to perform completion actions
  Future<void> onComplete() async {}
}
```

Vous pouvez surcharger n'importe quelle methode de la classe `JourneyState`.

<div id="journey-progress-styles"></div>

### Styles de progression journey

Vous pouvez personnaliser le style de l'indicateur de progression en utilisant la classe `JourneyProgressStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(
            activeColor: Colors.blue,
            inactiveColor: Colors.grey,
            thickness: 4.0,
        ),
    );
```

Vous pouvez utiliser les styles de progression suivants :

- `JourneyProgressIndicator.none` : Ne rend rien — utile pour masquer l'indicateur sur un onglet specifique.
- `JourneyProgressIndicator.linear` : Indicateur de progression lineaire.
- `JourneyProgressIndicator.dots` : Indicateur de progression a points.
- `JourneyProgressIndicator.numbered` : Indicateur de progression avec etapes numerotees.
- `JourneyProgressIndicator.segments` : Style de barre de progression segmentee.
- `JourneyProgressIndicator.circular` : Indicateur de progression circulaire.
- `JourneyProgressIndicator.timeline` : Indicateur de progression en style timeline.
- `JourneyProgressIndicator.custom` : Indicateur de progression personnalise utilisant une fonction builder.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    );
```

Vous pouvez personnaliser la position et le padding de l'indicateur de progression dans le `JourneyProgressStyle` :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.dots(),
            position: ProgressIndicatorPosition.bottom,
            padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        ),
    );
```

Vous pouvez utiliser les positions d'indicateur de progression suivantes :

- `ProgressIndicatorPosition.top` : Indicateur de progression en haut de l'ecran.
- `ProgressIndicatorPosition.bottom` : Indicateur de progression en bas de l'ecran.

#### Remplacement du style de progression par onglet

Vous pouvez remplacer le `progressStyle` au niveau du layout sur des onglets individuels en utilisant `NavigationTab.journey(progressStyle: ...)`. Les onglets sans leur propre `progressStyle` heritent du style par defaut du layout. Les onglets sans style par defaut du layout et sans style par onglet n'afficheront pas d'indicateur de progression.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
            progressStyle: JourneyProgressStyle(
                indicator: JourneyProgressIndicator.numbered(),
            ), // overrides the layout default for this tab only
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

<div id="journey-button-styles">
<br>

### Styles de boutons journey

Si vous souhaitez construire un flux d'onboarding, vous pouvez definir la propriete `buttonStyle` dans la classe `NavigationHubLayout.journey`.

Par defaut, vous pouvez utiliser les styles de boutons suivants :

- `JourneyButtonStyle.standard` : Style de bouton standard avec des proprietes personnalisables.
- `JourneyButtonStyle.minimal` : Style de bouton minimal avec uniquement des icones.
- `JourneyButtonStyle.outlined` : Style de bouton avec contour.
- `JourneyButtonStyle.contained` : Style de bouton plein.
- `JourneyButtonStyle.custom` : Style de bouton personnalise utilisant des fonctions builder.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

La classe `JourneyState` contient de nombreuses methodes d'aide pour gerer le parcours.

Pour creer un nouveau `JourneyState`, vous pouvez utiliser la commande ci-dessous.

``` bash
metro make:journey_widget onboard_user_dob
```

Ou si vous souhaitez creer plusieurs widgets en une seule fois, vous pouvez utiliser la commande suivante.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Cela creera les fichiers suivants dans votre repertoire **resources/widgets/** : `welcome.dart`, `phone_number_step.dart` et `add_photos_step.dart`.

Vous pouvez ensuite ajouter les nouveaux widgets au Navigation Hub.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

Si nous regardons la classe `WelcomeStep`, nous pouvons voir qu'elle etend la classe `JourneyState`.

``` dart
...
class _WelcomeTabState extends JourneyState<WelcomeTab> {
  _WelcomeTabState() : super(
      navigationHubState: BaseNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeTab', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
    );
  }
```

Vous remarquerez que la classe **JourneyState** utilise `buildJourneyContent` pour construire le contenu de la page.

Voici la liste des proprietes que vous pouvez utiliser dans la methode `buildJourneyContent`.

| Propriete | Type | Description |
| --- | --- | --- |
| `content` | `Widget` | Le contenu principal de la page. |
| `nextButton` | `Widget?` | Le widget du bouton suivant. |
| `backButton` | `Widget?` | Le widget du bouton retour. |
| `contentPadding` | `EdgeInsetsGeometry` | Le padding du contenu. |
| `header` | `Widget?` | Le widget d'en-tete. |
| `footer` | `Widget?` | Le widget de pied de page. |
| `crossAxisAlignment` | `CrossAxisAlignment` | L'alignement de l'axe transversal du contenu. |


<div id="journey-state-helper-methods"></div>

### Methodes d'aide JourneyState

La classe `JourneyState` possede des methodes d'aide que vous pouvez utiliser pour personnaliser le comportement de votre parcours.

| Methode | Description |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | Appelee lorsque le bouton suivant est presse. |
| [`onBackPressed()`](#on-back-pressed) | Appelee lorsque le bouton retour est presse. |
| [`onComplete()`](#on-complete) | Appelee lorsque le parcours est termine (a la derniere etape). |
| [`onBeforeNext()`](#on-before-next) | Appelee avant la navigation vers l'etape suivante. |
| [`onAfterNext()`](#on-after-next) | Appelee apres la navigation vers l'etape suivante. |
| [`onCannotContinue()`](#on-cannot-continue) | Appelee lorsque le parcours ne peut pas continuer (canContinue retourne false). |
| [`canContinue()`](#can-continue) | Appelee lorsque l'utilisateur essaie de naviguer vers l'etape suivante. |
| [`isFirstStep`](#is-first-step) | Retourne true si c'est la premiere etape du parcours. |
| [`isLastStep`](#is-last-step) | Retourne true si c'est la derniere etape du parcours. |
| [`goToStep(int index)`](#go-to-step) | Naviguer vers l'index de l'etape suivante. |
| [`goToNextStep()`](#go-to-next-step) | Naviguer vers l'etape suivante. |
| [`goToPreviousStep()`](#go-to-previous-step) | Naviguer vers l'etape precedente. |
| [`goToFirstStep()`](#go-to-first-step) | Naviguer vers la premiere etape. |
| [`goToLastStep()`](#go-to-last-step) | Naviguer vers la derniere etape. |


<div id="on-next-pressed"></div>

#### onNextPressed

La methode `onNextPressed` est appelee lorsque le bouton suivant est presse.

Ex. Vous pouvez utiliser cette methode pour declencher l'etape suivante du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: onNextPressed, // this will attempt to navigate to the next step
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

La methode `onBackPressed` est appelee lorsque le bouton retour est presse.

Ex. Vous pouvez utiliser cette methode pour declencher l'etape precedente du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed, // this will attempt to navigate to the previous step
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

La methode `onComplete` est appelee lorsque le parcours est termine (a la derniere etape).

Ex. Si ce widget est la derniere etape du parcours, cette methode sera appelee.

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

La methode `onBeforeNext` est appelee avant la navigation vers l'etape suivante.

Ex. Si vous souhaitez sauvegarder des donnees avant de naviguer vers l'etape suivante, vous pouvez le faire ici.

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

La methode `isFirstStep` retourne true si c'est la premiere etape du parcours.

Ex. Vous pouvez utiliser cette methode pour desactiver le bouton retour si c'est la premiere etape.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly( // Example of disabling the back button
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

La methode `isLastStep` retourne true si c'est la derniere etape du parcours.

Ex. Vous pouvez utiliser cette methode pour desactiver le bouton suivant si c'est la derniere etape.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue", // Example updating the next button text
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

La methode `goToStep` est utilisee pour naviguer vers une etape specifique du parcours.

Ex. Vous pouvez utiliser cette methode pour naviguer vers une etape specifique du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Add photos"
            onPressed: () {
                goToStep(2); // this will navigate to the step with index 2
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

La methode `goToNextStep` est utilisee pour naviguer vers l'etape suivante du parcours.

Ex. Vous pouvez utiliser cette methode pour naviguer vers l'etape suivante du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToNextStep(); // this will navigate to the next step
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

La methode `goToPreviousStep` est utilisee pour naviguer vers l'etape precedente du parcours.

Ex. Vous pouvez utiliser cette methode pour naviguer vers l'etape precedente du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: () {
                goToPreviousStep(); // this will navigate to the previous step
            },
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

La methode `onAfterNext` est appelee apres la navigation vers l'etape suivante.


Ex. Si vous souhaitez effectuer une action apres la navigation vers l'etape suivante, vous pouvez le faire ici.

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

La methode `onCannotContinue` est appelee lorsque le parcours ne peut pas continuer (canContinue retourne false).

Ex. Si vous souhaitez afficher un message d'erreur lorsque l'utilisateur essaie de naviguer vers l'etape suivante sans remplir les champs requis, vous pouvez le faire ici.

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

La methode `canContinue` est appelee lorsque l'utilisateur essaie de naviguer vers l'etape suivante.

Ex. Si vous souhaitez effectuer une validation avant de naviguer vers l'etape suivante, vous pouvez le faire ici.

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

La methode `goToFirstStep` est utilisee pour naviguer vers la premiere etape du parcours.


Ex. Vous pouvez utiliser cette methode pour naviguer vers la premiere etape du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToFirstStep(); // this will navigate to the first step
            },
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

La methode `goToLastStep` est utilisee pour naviguer vers la derniere etape du parcours.

Ex. Vous pouvez utiliser cette methode pour naviguer vers la derniere etape du parcours.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToLastStep(); // this will navigate to the last step
            },
        ),
    );
}
```

<div id="navigating-within-a-tab"></div>

## Naviguer vers des widgets au sein d'un onglet

Vous pouvez naviguer vers des widgets au sein d'un onglet en utilisant le helper `pushTo`.

Dans votre onglet, vous pouvez utiliser le helper `pushTo` pour naviguer vers un autre widget.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Vous pouvez egalement passer des donnees au widget vers lequel vous naviguez.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## Onglets

Les onglets sont les elements de base d'un Navigation Hub.

Vous pouvez ajouter des onglets a un Navigation Hub en utilisant la classe `NavigationTab`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab(
                title: "Home",
                page: HomeTab(),
                icon: Icon(Icons.home),
                activeIcon: Icon(Icons.home),
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Dans l'exemple ci-dessus, nous avons ajoute deux onglets au Navigation Hub, Home et Settings.

Vous pouvez utiliser differents types d'onglets comme `NavigationTab`, `NavigationTab.badge` et `NavigationTab.alert`.

- La classe `NavigationTab.badge` est utilisee pour ajouter des badges aux onglets.
- La classe `NavigationTab.alert` est utilisee pour ajouter des alertes aux onglets.
- La classe `NavigationTab` est utilisee pour ajouter un onglet normal.

<div id="adding-badges-to-tabs"></div>

## Ajouter des badges aux onglets

Nous avons facilite l'ajout de badges a vos onglets.

Les badges sont un excellent moyen de montrer aux utilisateurs qu'il y a quelque chose de nouveau dans un onglet.

Par exemple, si vous avez une application de chat, vous pouvez afficher le nombre de messages non lus dans l'onglet de chat.

Pour ajouter un badge a un onglet, vous pouvez utiliser la classe `NavigationTab.badge`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Dans l'exemple ci-dessus, nous avons ajoute un badge a l'onglet Chat avec un compteur initial de 10.

Vous pouvez egalement mettre a jour le compteur du badge par programmation.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Par defaut, le compteur du badge sera memorise. Si vous souhaitez **effacer** le compteur du badge a chaque session, vous pouvez definir `rememberCount` a `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
                rememberCount: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

<div id="adding-alerts-to-tabs"></div>

## Ajouter des alertes aux onglets

Vous pouvez ajouter des alertes a vos onglets.

Parfois, vous ne souhaitez pas afficher un compteur de badge, mais vous voulez montrer une alerte a l'utilisateur.

Pour ajouter une alerte a un onglet, vous pouvez utiliser la classe `NavigationTab.alert`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.alert(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                alertColor: Colors.red,
                alertEnabled: true,
                rememberAlert: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Cela ajoutera une alerte a l'onglet Chat avec une couleur rouge.

Vous pouvez egalement mettre a jour l'alerte par programmation.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="maintaining-state"></div>

## Maintien de l'etat

Par defaut, l'etat du Navigation Hub est maintenu.

Cela signifie que lorsque vous naviguez vers un onglet, l'etat de l'onglet est preserve.

Si vous souhaitez effacer l'etat de l'onglet a chaque fois que vous y naviguez, vous pouvez definir `maintainState` a `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## Actions d'etat

Les actions d'etat sont un moyen d'interagir avec le Navigation Hub depuis n'importe ou dans votre application.

Voici certaines des actions d'etat que vous pouvez utiliser :

``` dart
  /// Reset the tab
  /// E.g. MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Update the badge count
  /// E.g. MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Increment the badge count
  /// E.g. MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Clear the badge count
  /// E.g. MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

Pour utiliser une action d'etat, vous pouvez faire ce qui suit :

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## Style de chargement

Par defaut, le Navigation Hub affichera votre widget de chargement **par defaut** (resources/widgets/loader_widget.dart) lorsque l'onglet est en cours de chargement.

Vous pouvez personnaliser le `loadingStyle` pour modifier le style de chargement.

Voici un tableau des differents styles de chargement disponibles :
// normal, skeletonizer, none

| Style | Description |
| --- | --- |
| normal | Style de chargement par defaut |
| skeletonizer | Style de chargement squelette |
| none | Pas de style de chargement |

Vous pouvez changer le style de chargement comme ceci :

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Si vous souhaitez modifier le widget de chargement dans l'un des styles, vous pouvez passer un `child` au `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Maintenant, lorsque l'onglet est en cours de chargement, le texte "Loading..." sera affiche.

Exemple ci-dessous :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
     _BaseNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab(
          title: "Home",
          page: HomeTab(),
          icon: Icon(Icons.home),
          activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab(
          title: "Settings",
          page: SettingsTab(),
          icon: Icon(Icons.settings),
          activeIcon: Icon(Icons.settings),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Creer un Navigation Hub

Pour creer un Navigation Hub, vous pouvez utiliser [Metro](/docs/{{$version}}/metro), utilisez la commande ci-dessous.

``` bash
metro make:navigation_hub base
```

Cela creera un fichier base_navigation_hub.dart dans votre repertoire `resources/pages/` et ajoutera le Navigation Hub a votre fichier `routes/router.dart`.
