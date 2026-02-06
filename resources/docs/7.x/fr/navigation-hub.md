# Navigation Hub

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
  - [Cr&eacute;er un Navigation Hub](#creating-a-navigation-hub "Cr&eacute;er un Navigation Hub")
  - [Cr&eacute;er des onglets de navigation](#creating-navigation-tabs "Cr&eacute;er des onglets de navigation")
  - [Navigation inf&eacute;rieure](#bottom-navigation "Navigation inf&eacute;rieure")
    - [Constructeur de barre de navigation personnalis&eacute;](#custom-nav-bar-builder "Constructeur de barre de navigation personnalis&eacute;")
  - [Navigation sup&eacute;rieure](#top-navigation "Navigation sup&eacute;rieure")
  - [Navigation journey](#journey-navigation "Navigation journey")
    - [Styles de progression](#journey-progress-styles "Styles de progression")
    - [JourneyState](#journey-state "JourneyState")
    - [M&eacute;thodes d'aide JourneyState](#journey-state-helper-methods "M&eacute;thodes d'aide JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Naviguer au sein d'un onglet](#navigating-within-a-tab "Naviguer au sein d'un onglet")
- [Onglets](#tabs "Onglets")
  - [Ajouter des badges aux onglets](#adding-badges-to-tabs "Ajouter des badges aux onglets")
  - [Ajouter des alertes aux onglets](#adding-alerts-to-tabs "Ajouter des alertes aux onglets")
- [Index initial](#initial-index "Index initial")
- [Maintien de l'&eacute;tat](#maintaining-state "Maintien de l'&eacute;tat")
- [onTap](#on-tap "onTap")
- [Actions d'&eacute;tat](#state-actions "Actions d'&eacute;tat")
- [Style de chargement](#loading-style "Style de chargement")

<div id="introduction"></div>

## Introduction

Les Navigation Hubs sont un endroit central o&ugrave; vous pouvez **g&eacute;rer** la navigation de tous vos widgets.
Par d&eacute;faut, vous pouvez cr&eacute;er des mises en page de navigation inf&eacute;rieure, sup&eacute;rieure et journey en quelques secondes.

**Imaginons** que vous avez une application et que vous souhaitez ajouter une barre de navigation inf&eacute;rieure permettant aux utilisateurs de naviguer entre diff&eacute;rents onglets.

Vous pouvez utiliser un Navigation Hub pour construire cela.

Voyons comment utiliser un Navigation Hub dans votre application.

<div id="basic-usage"></div>

## Utilisation de base

Vous pouvez cr&eacute;er un Navigation Hub en utilisant la commande ci-dessous.

``` bash
metro make:navigation_hub base
```

La commande vous guidera &agrave; travers une configuration interactive :

1. **Choisir un type de mise en page** - S&eacute;lectionnez entre `navigation_tabs` (navigation inf&eacute;rieure) ou `journey_states` (flux s&eacute;quentiel).
2. **Saisir les noms des tabs/states** - Fournissez des noms s&eacute;par&eacute;s par des virgules pour vos tabs ou journey states.

Cela cr&eacute;era des fichiers dans votre r&eacute;pertoire `resources/pages/navigation_hubs/base/` :
- `base_navigation_hub.dart` - Le widget principal du hub
- `tabs/` ou `states/` - Contient les widgets enfants pour chaque tab ou journey state

Voici &agrave; quoi ressemble un Navigation Hub g&eacute;n&eacute;r&eacute; :

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

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

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Vous pouvez voir que le Navigation Hub poss&egrave;de **deux** onglets, Home et Settings.

La m&eacute;thode `layout` retourne le type de mise en page du hub. Elle re&ccedil;oit un `BuildContext` qui vous permet d'acc&eacute;der aux donn&eacute;es du th&egrave;me et aux media queries lors de la configuration de votre mise en page.

Vous pouvez cr&eacute;er davantage d'onglets en ajoutant des `NavigationTab` au Navigation Hub.

Tout d'abord, vous devez cr&eacute;er un nouveau widget en utilisant Metro.

``` bash
metro make:stateful_widget news_tab
```

Vous pouvez &eacute;galement cr&eacute;er plusieurs widgets en une seule fois.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Ensuite, vous pouvez ajouter le nouveau widget au Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Pour utiliser le Navigation Hub, ajoutez-le &agrave; votre routeur comme route initiale :

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Il y a **beaucoup plus** de choses que vous pouvez faire avec un Navigation Hub. Explorons certaines de ses fonctionnalit&eacute;s.

<div id="bottom-navigation"></div>

### Navigation inf&eacute;rieure

Vous pouvez d&eacute;finir la mise en page sur une barre de navigation inf&eacute;rieure en retournant `NavigationHubLayout.bottomNav` depuis la m&eacute;thode `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Vous pouvez personnaliser la barre de navigation inf&eacute;rieure en d&eacute;finissant des propri&eacute;t&eacute;s comme celles-ci :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

Vous pouvez appliquer un style pr&eacute;d&eacute;fini &agrave; votre barre de navigation inf&eacute;rieure en utilisant le param&egrave;tre `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Constructeur de barre de navigation personnalis&eacute;

Pour un contr&ocirc;le total sur votre barre de navigation, vous pouvez utiliser le param&egrave;tre `navBarBuilder`.

Cela vous permet de construire n'importe quel widget personnalis&eacute; tout en recevant les donn&eacute;es de navigation.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
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

| Propri&eacute;t&eacute; | Type | Description |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Les &eacute;l&eacute;ments de la barre de navigation |
| `currentIndex` | `int` | L'index actuellement s&eacute;lectionn&eacute; |
| `onTap` | `ValueChanged<int>` | Callback lorsqu'un onglet est touch&eacute; |

Voici un exemple de barre de navigation glass enti&egrave;rement personnalis&eacute;e :

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

> **Note :** Lorsque vous utilisez `navBarBuilder`, le param&egrave;tre `style` est ignor&eacute;.

<div id="top-navigation"></div>

### Navigation sup&eacute;rieure

Vous pouvez changer la mise en page pour une barre de navigation sup&eacute;rieure en retournant `NavigationHubLayout.topNav` depuis la m&eacute;thode `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Vous pouvez personnaliser la barre de navigation sup&eacute;rieure en d&eacute;finissant des propri&eacute;t&eacute;s comme celles-ci :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Navigation journey

Vous pouvez changer la mise en page pour une navigation journey en retournant `NavigationHubLayout.journey` depuis la m&eacute;thode `layout`.

C'est id&eacute;al pour les flux d'onboarding ou les formulaires multi-&eacute;tapes.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

Vous pouvez &eacute;galement d&eacute;finir un `backgroundGradient` pour la mise en page journey :

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Note :** Lorsque `backgroundGradient` est d&eacute;fini, il a priorit&eacute; sur `backgroundColor`.

Si vous souhaitez utiliser la mise en page de navigation journey, vos **widgets** devraient utiliser `JourneyState` car il contient de nombreuses m&eacute;thodes d'aide pour g&eacute;rer le parcours.

Vous pouvez cr&eacute;er l'ensemble du journey en utilisant la commande `make:navigation_hub` avec la mise en page `journey_states` :

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Cela cr&eacute;era le hub ainsi que tous les widgets de journey state dans `resources/pages/navigation_hubs/onboarding/states/`.

Vous pouvez &eacute;galement cr&eacute;er des widgets journey individuels en utilisant :

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Vous pouvez ensuite ajouter les nouveaux widgets au Navigation Hub.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Styles de progression journey

Vous pouvez personnaliser le style de l'indicateur de progression en utilisant la classe `JourneyProgressStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

Vous pouvez utiliser les indicateurs de progression suivants :

- `JourneyProgressIndicator.none()` : Ne rend rien -- utile pour masquer l'indicateur sur un onglet sp&eacute;cifique.
- `JourneyProgressIndicator.linear()` : Barre de progression lin&eacute;aire.
- `JourneyProgressIndicator.dots()` : Indicateur de progression &agrave; points.
- `JourneyProgressIndicator.numbered()` : Indicateur de progression avec &eacute;tapes num&eacute;rot&eacute;es.
- `JourneyProgressIndicator.segments()` : Style de barre de progression segment&eacute;e.
- `JourneyProgressIndicator.circular()` : Indicateur de progression circulaire.
- `JourneyProgressIndicator.timeline()` : Indicateur de progression en style timeline.
- `JourneyProgressIndicator.custom()` : Indicateur de progression personnalis&eacute; utilisant une fonction builder.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

Vous pouvez personnaliser la position et le padding de l'indicateur de progression dans le `JourneyProgressStyle` :

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

Vous pouvez utiliser les positions d'indicateur de progression suivantes :

- `ProgressIndicatorPosition.top` : Indicateur de progression en haut de l'&eacute;cran.
- `ProgressIndicatorPosition.bottom` : Indicateur de progression en bas de l'&eacute;cran.

#### Remplacement du style de progression par onglet

Vous pouvez remplacer le `progressStyle` au niveau du layout sur des onglets individuels en utilisant `NavigationTab.journey(progressStyle: ...)`. Les onglets sans leur propre `progressStyle` h&eacute;ritent du style par d&eacute;faut du layout. Les onglets sans style par d&eacute;faut du layout et sans style par onglet n'afficheront pas d'indicateur de progression.

``` dart
_MyNavigationHubState() : super(() => {
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
});
```

<div id="journey-state"></div>

### JourneyState

La classe `JourneyState` &eacute;tend `NyState` avec des fonctionnalit&eacute;s sp&eacute;cifiques au journey pour faciliter la cr&eacute;ation de flux d'onboarding et de parcours multi-&eacute;tapes.

Pour cr&eacute;er un nouveau `JourneyState`, vous pouvez utiliser la commande ci-dessous.

``` bash
metro make:journey_widget onboard_user_dob
```

Ou si vous souhaitez cr&eacute;er plusieurs widgets en une seule fois, vous pouvez utiliser la commande suivante.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Voici &agrave; quoi ressemble un widget JourneyState g&eacute;n&eacute;r&eacute; :

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

Vous remarquerez que la classe **JourneyState** utilise `nextStep` pour avancer et `onBackPressed` pour revenir en arri&egrave;re.

La m&eacute;thode `nextStep` ex&eacute;cute l'ensemble du cycle de vie de validation : `canContinue()` -> `onBeforeNext()` -> navigation (ou `onComplete()` si c'est la derni&egrave;re &eacute;tape) -> `onAfterNext()`.

Vous pouvez &eacute;galement utiliser `buildJourneyContent` pour construire une mise en page structur&eacute;e avec des boutons de navigation optionnels :

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

Voici les propri&eacute;t&eacute;s que vous pouvez utiliser dans la m&eacute;thode `buildJourneyContent`.

| Propri&eacute;t&eacute; | Type | Description |
| --- | --- | --- |
| `content` | `Widget` | Le contenu principal de la page. |
| `nextButton` | `Widget?` | Le widget du bouton suivant. |
| `backButton` | `Widget?` | Le widget du bouton retour. |
| `contentPadding` | `EdgeInsetsGeometry` | Le padding du contenu. |
| `header` | `Widget?` | Le widget d'en-t&ecirc;te. |
| `footer` | `Widget?` | Le widget de pied de page. |
| `crossAxisAlignment` | `CrossAxisAlignment` | L'alignement de l'axe transversal du contenu. |

<div id="journey-state-helper-methods"></div>

### M&eacute;thodes d'aide JourneyState

La classe `JourneyState` poss&egrave;de des m&eacute;thodes et propri&eacute;t&eacute;s d'aide que vous pouvez utiliser pour personnaliser le comportement de votre parcours.

| M&eacute;thode / Propri&eacute;t&eacute; | Description |
| --- | --- |
| [`nextStep()`](#next-step) | Navigue vers l'&eacute;tape suivante avec validation. Retourne `Future<bool>`. |
| [`previousStep()`](#previous-step) | Navigue vers l'&eacute;tape pr&eacute;c&eacute;dente. Retourne `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Helper simple pour naviguer vers l'&eacute;tape pr&eacute;c&eacute;dente. |
| [`onComplete()`](#on-complete) | Appel&eacute;e lorsque le parcours est termin&eacute; (&agrave; la derni&egrave;re &eacute;tape). |
| [`onBeforeNext()`](#on-before-next) | Appel&eacute;e avant la navigation vers l'&eacute;tape suivante. |
| [`onAfterNext()`](#on-after-next) | Appel&eacute;e apr&egrave;s la navigation vers l'&eacute;tape suivante. |
| [`canContinue()`](#can-continue) | V&eacute;rification de validation avant la navigation vers l'&eacute;tape suivante. |
| [`isFirstStep`](#is-first-step) | Retourne true si c'est la premi&egrave;re &eacute;tape du parcours. |
| [`isLastStep`](#is-last-step) | Retourne true si c'est la derni&egrave;re &eacute;tape du parcours. |
| [`currentStep`](#current-step) | Retourne l'index de l'&eacute;tape actuelle (base 0). |
| [`totalSteps`](#total-steps) | Retourne le nombre total d'&eacute;tapes. |
| [`completionPercentage`](#completion-percentage) | Retourne le pourcentage de compl&eacute;tion (0.0 &agrave; 1.0). |
| [`goToStep(int index)`](#go-to-step) | Sauter directement &agrave; une &eacute;tape sp&eacute;cifique par index. |
| [`goToNextStep()`](#go-to-next-step) | Sauter &agrave; l'&eacute;tape suivante (sans validation). |
| [`goToPreviousStep()`](#go-to-previous-step) | Sauter &agrave; l'&eacute;tape pr&eacute;c&eacute;dente (sans validation). |
| [`goToFirstStep()`](#go-to-first-step) | Sauter &agrave; la premi&egrave;re &eacute;tape. |
| [`goToLastStep()`](#go-to-last-step) | Sauter &agrave; la derni&egrave;re &eacute;tape. |
| [`exitJourney()`](#exit-journey) | Quitter le journey en faisant un pop du navigateur racine. |
| [`resetCurrentStep()`](#reset-current-step) | R&eacute;initialiser l'&eacute;tat de l'&eacute;tape actuelle. |
| [`onJourneyComplete`](#on-journey-complete) | Callback lorsque le journey est termin&eacute; (&agrave; surcharger dans la derni&egrave;re &eacute;tape). |
| [`buildJourneyPage()`](#build-journey-page) | Construire une page journey plein &eacute;cran avec Scaffold. |


<div id="next-step"></div>

#### nextStep

La m&eacute;thode `nextStep` navigue vers l'&eacute;tape suivante avec une validation compl&egrave;te. Elle ex&eacute;cute le cycle de vie : `canContinue()` -> `onBeforeNext()` -> navigation ou `onComplete()` -> `onAfterNext()`.

Vous pouvez passer `force: true` pour contourner la validation et naviguer directement.

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
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

Pour ignorer la validation :

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

La m&eacute;thode `previousStep` navigue vers l'&eacute;tape pr&eacute;c&eacute;dente. Retourne `true` en cas de succ&egrave;s, `false` si vous &ecirc;tes d&eacute;j&agrave; &agrave; la premi&egrave;re &eacute;tape.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

La m&eacute;thode `onBackPressed` est un helper simple qui appelle `previousStep()` en interne.

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
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

La m&eacute;thode `onComplete` est appel&eacute;e lorsque `nextStep()` est d&eacute;clench&eacute; &agrave; la derni&egrave;re &eacute;tape (apr&egrave;s que la validation est pass&eacute;e).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

La m&eacute;thode `onBeforeNext` est appel&eacute;e avant la navigation vers l'&eacute;tape suivante.

Par exemple, si vous souhaitez sauvegarder des donn&eacute;es avant de naviguer vers l'&eacute;tape suivante, vous pouvez le faire ici.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

La m&eacute;thode `onAfterNext` est appel&eacute;e apr&egrave;s la navigation vers l'&eacute;tape suivante.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

La m&eacute;thode `canContinue` est appel&eacute;e lorsque `nextStep()` est d&eacute;clench&eacute;. Retournez `false` pour emp&ecirc;cher la navigation.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

La propri&eacute;t&eacute; `isFirstStep` retourne true si c'est la premi&egrave;re &eacute;tape du parcours.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

La propri&eacute;t&eacute; `isLastStep` retourne true si c'est la derni&egrave;re &eacute;tape du parcours.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

La propri&eacute;t&eacute; `currentStep` retourne l'index de l'&eacute;tape actuelle (base 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

La propri&eacute;t&eacute; `totalSteps` retourne le nombre total d'&eacute;tapes du parcours.

<div id="completion-percentage"></div>

#### completionPercentage

La propri&eacute;t&eacute; `completionPercentage` retourne le pourcentage de compl&eacute;tion sous forme de valeur allant de 0.0 &agrave; 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

La m&eacute;thode `goToStep` saute directement &agrave; une &eacute;tape sp&eacute;cifique par index. Cela ne d&eacute;clenche **pas** de validation.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

La m&eacute;thode `goToNextStep` saute &agrave; l'&eacute;tape suivante sans validation. Si vous &ecirc;tes d&eacute;j&agrave; &agrave; la derni&egrave;re &eacute;tape, elle ne fait rien.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

La m&eacute;thode `goToPreviousStep` saute &agrave; l'&eacute;tape pr&eacute;c&eacute;dente sans validation. Si vous &ecirc;tes d&eacute;j&agrave; &agrave; la premi&egrave;re &eacute;tape, elle ne fait rien.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

La m&eacute;thode `goToFirstStep` saute &agrave; la premi&egrave;re &eacute;tape.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

La m&eacute;thode `goToLastStep` saute &agrave; la derni&egrave;re &eacute;tape.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

La m&eacute;thode `exitJourney` quitte le journey en faisant un pop du navigateur racine.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

La m&eacute;thode `resetCurrentStep` r&eacute;initialise l'&eacute;tat de l'&eacute;tape actuelle.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Le getter `onJourneyComplete` peut &ecirc;tre surcharg&eacute; dans la **derni&egrave;re &eacute;tape** de votre journey pour d&eacute;finir ce qui se passe lorsque l'utilisateur termine le flux.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

La m&eacute;thode `buildJourneyPage` construit une page journey plein &eacute;cran envelopp&eacute;e dans un `Scaffold` avec `SafeArea`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Propri&eacute;t&eacute; | Type | Description |
| --- | --- | --- |
| `content` | `Widget` | Le contenu principal de la page. |
| `nextButton` | `Widget?` | Le widget du bouton suivant. |
| `backButton` | `Widget?` | Le widget du bouton retour. |
| `contentPadding` | `EdgeInsetsGeometry` | Le padding du contenu. |
| `header` | `Widget?` | Le widget d'en-t&ecirc;te. |
| `footer` | `Widget?` | Le widget de pied de page. |
| `backgroundColor` | `Color?` | La couleur d'arri&egrave;re-plan du Scaffold. |
| `appBar` | `Widget?` | Un widget AppBar optionnel. |
| `crossAxisAlignment` | `CrossAxisAlignment` | L'alignement de l'axe transversal du contenu. |

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

Vous pouvez &eacute;galement passer des donn&eacute;es au widget vers lequel vous naviguez.

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

Les onglets sont les &eacute;l&eacute;ments de base d'un Navigation Hub.

Vous pouvez ajouter des onglets &agrave; un Navigation Hub en utilisant la classe `NavigationTab` et ses constructeurs nomm&eacute;s.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Dans l'exemple ci-dessus, nous avons ajout&eacute; deux onglets au Navigation Hub, Home et Settings.

Vous pouvez utiliser diff&eacute;rents types d'onglets :

- `NavigationTab.tab()` - Un onglet de navigation standard.
- `NavigationTab.badge()` - Un onglet avec un compteur de badge.
- `NavigationTab.alert()` - Un onglet avec un indicateur d'alerte.
- `NavigationTab.journey()` - Un onglet pour les mises en page de navigation journey.

<div id="adding-badges-to-tabs"></div>

## Ajouter des badges aux onglets

Nous avons facilit&eacute; l'ajout de badges &agrave; vos onglets.

Les badges sont un excellent moyen de montrer aux utilisateurs qu'il y a quelque chose de nouveau dans un onglet.

Par exemple, si vous avez une application de chat, vous pouvez afficher le nombre de messages non lus dans l'onglet de chat.

Pour ajouter un badge &agrave; un onglet, vous pouvez utiliser le constructeur `NavigationTab.badge`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Dans l'exemple ci-dessus, nous avons ajout&eacute; un badge &agrave; l'onglet Chat avec un compteur initial de 10.

Vous pouvez &eacute;galement mettre &agrave; jour le compteur du badge par programmation.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Par d&eacute;faut, le compteur du badge sera m&eacute;moris&eacute;. Si vous souhaitez **effacer** le compteur du badge &agrave; chaque session, vous pouvez d&eacute;finir `rememberCount` &agrave; `false`.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Ajouter des alertes aux onglets

Vous pouvez ajouter des alertes &agrave; vos onglets.

Parfois, vous ne souhaitez pas afficher un compteur de badge, mais vous voulez montrer un indicateur d'alerte &agrave; l'utilisateur.

Pour ajouter une alerte &agrave; un onglet, vous pouvez utiliser le constructeur `NavigationTab.alert`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Cela ajoutera une alerte &agrave; l'onglet Chat avec une couleur rouge.

Vous pouvez &eacute;galement mettre &agrave; jour l'alerte par programmation.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Index initial

Par d&eacute;faut, le Navigation Hub d&eacute;marre sur le premier onglet (index 0). Vous pouvez modifier cela en surchargeant le getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Maintien de l'&eacute;tat

Par d&eacute;faut, l'&eacute;tat du Navigation Hub est maintenu.

Cela signifie que lorsque vous naviguez vers un onglet, l'&eacute;tat de l'onglet est pr&eacute;serv&eacute;.

Si vous souhaitez effacer l'&eacute;tat de l'onglet &agrave; chaque fois que vous y naviguez, vous pouvez d&eacute;finir `maintainState` &agrave; `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

Vous pouvez surcharger la m&eacute;thode `onTap` pour ajouter une logique personnalis&eacute;e lorsqu'un onglet est touch&eacute;.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## Actions d'&eacute;tat

Les actions d'&eacute;tat sont un moyen d'interagir avec le Navigation Hub depuis n'importe o&ugrave; dans votre application.

Voici les actions d'&eacute;tat que vous pouvez utiliser :

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Pour utiliser une action d'&eacute;tat, vous pouvez faire ce qui suit :

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Style de chargement

Par d&eacute;faut, le Navigation Hub affichera votre widget de chargement **par d&eacute;faut** (resources/widgets/loader_widget.dart) lorsque l'onglet est en cours de chargement.

Vous pouvez personnaliser le `loadingStyle` pour modifier le style de chargement.

| Style | Description |
| --- | --- |
| normal | Style de chargement par d&eacute;faut |
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

D&eacute;sormais, lorsque l'onglet est en cours de chargement, le texte "Loading..." sera affich&eacute;.

Exemple ci-dessous :

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
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

## Cr&eacute;er un Navigation Hub

Pour cr&eacute;er un Navigation Hub, vous pouvez utiliser [Metro](/docs/{{$version}}/metro), utilisez la commande ci-dessous.

``` bash
metro make:navigation_hub base
```

La commande vous guidera &agrave; travers une configuration interactive o&ugrave; vous pourrez choisir le type de mise en page et d&eacute;finir vos tabs ou journey states.

Cela cr&eacute;era un fichier `base_navigation_hub.dart` dans votre r&eacute;pertoire `resources/pages/navigation_hubs/base/` avec les widgets enfants organis&eacute;s dans les sous-dossiers `tabs/` ou `states/`.
