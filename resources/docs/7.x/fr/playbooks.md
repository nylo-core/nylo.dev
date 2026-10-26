# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction aux playbooks")
- [Creer un playbook](#creating-a-playbook "Creer un playbook")
- [Enregistrer un playbook](#recording-a-playbook "Enregistrer un playbook depuis l'application en cours d'execution")
- [Etapes](#steps "Les etapes qu'un playbook peut effectuer")
- [Cibles](#targets "Trouver des elements a l'ecran")
  - [Chaines simples](#plain-strings "Comment une chaine simple est reconnue")
  - [Find](#find "Des cibles precises avec Find")
- [Donnees et configuration](#data-and-setup "Donnees, setUp et tearDown")
- [Jouer d'autres playbooks](#playing-other-playbooks "Executer un playbook depuis un autre playbook")
- [Vitesse](#speed "PlaybookSpeed")
- [Doigt et legende](#finger-and-caption "Le doigt et la legende de l'etape")
- [Captures d'ecran](#screenshots "Capturer des captures d'ecran pendant un playbook")
  - [Locales et themes](#screenshots-locales-and-themes "Capturer un ecran dans chaque locale et chaque theme")
  - [Collecter depuis le code](#collecting-screenshots "Collecter des captures d'ecran avec onScreenshot")
- [Enregistrer les playbooks](#registering-playbooks "Enregistrer les playbooks aupres de Nylo")
- [Executer un playbook](#running-a-playbook "Executer un playbook")
  - [Depuis Metro](#running-from-metro "Executer depuis Metro")
  - [Au demarrage](#running-on-boot "Executer au demarrage")
  - [Depuis Dart](#running-from-dart "Executer depuis Dart")
- [Rapports](#reports "PlaybookReport")
- [Builds release](#release-builds "Les playbooks dans les builds release")
- [Tests](#testing "Tester un playbook")

<div id="introduction"></div>

## Introduction

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Un **playbook** est une classe qui liste les appuis, saisies, defilements et changements de page d'un parcours utilisateur. Votre application en cours d'execution les effectue elle-meme, et peut afficher un doigt et une legende pour chaque etape -- pratique pour les demos, les enregistrements d'ecran et la verification d'un parcours de bout en bout.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

Importez `package:nylo_framework/playbook.dart` partout ou vous ecrivez ou enregistrez des playbooks.

<div id="creating-a-playbook"></div>

## Creer un playbook

Utilisez Metro pour en creer un :

``` bash
metro make:playbook sign_up
```

Cela cree `lib/app/playbooks/sign_up_playbook.dart` et l'enregistre dans votre application. Consultez [Creer un playbook](/docs/{{ $version }}/metro#make-playbook) pour les options de la commande.

Pour partir de chaque page que votre routeur ajoute, passez `--from-routes` :

``` bash
metro make:playbook tour --from-routes
```

Metro ecrit un `visit()` et un [`screenshot()`](#screenshots) pour chaque page, si bien qu'une seule execution photographie toute votre application.

<div id="recording-a-playbook"></div>

## Enregistrer un playbook

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Au lieu d'ecrire les etapes, enregistrez-les. Avec votre application lancee en mode debug, ouvrez `metro live` et tapez :

``` plaintext
› record sign_up
```

Utilisez ensuite l'application. Chaque appui, appui long, champ saisi, defilement et changement de page est ecrit comme une etape au moment ou il se produit, et affiche au fur et a mesure :

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Appuyez sur Entree pour terminer. Le playbook est ecrit dans `lib/app/playbooks/sign_up_playbook.dart`, structure comme celui que cree `make:playbook`, et enregistre, de sorte qu'il se joue des que l'application redemarre. `metro make:playbook sign_up --record` fait la meme chose depuis le terminal : il ouvre `metro live` et demarre l'enregistrement immediatement.

Pendant l'enregistrement, tapez une touche puis appuyez sur Entree :

| Touche | Description |
|-----|-------------|
| `a` | Le prochain appui est ecrit comme `see(...)` au lieu d'un appui |
| `d` | Le prochain appui est ecrit comme `dontSee(...)` |
| `s <title>` | Demarre une `scene` avec ce titre ; les etapes suivantes y sont imbriquees |
| `u` | Supprime la derniere etape |
| `p` | Met l'enregistrement en pause, par ex. pour se connecter avec de vrais identifiants ; `p` de nouveau le reprend |
| Entree | Termine et ecrit le playbook |
| Ctrl+C | Abandonne l'enregistrement |

Les cibles sont nommees comme le lecteur les lit, le texte visible en premier, et chaque nom est verifie sur l'application en cours d'execution avant d'etre ecrit, de sorte qu'une etape enregistree se rejoue. Lorsqu'une chaine simple correspond a plusieurs widgets, l'etape l'affine avec `Find` : `tap(Find.text('Add').at(1))`. Un bouton d'icone sans texte ni infobulle devient `tap(Find.icon(Icons.add))`. Un widget que rien ne nomme est ecrit `tap(Find.type<GestureDetector>().at(3))` sous un commentaire `// TODO` avec le fichier et la ligne ou il a ete cree, et Metro le liste a la fin de l'enregistrement. Donnez-lui une `Key`, puis utilisez la cle a la place.

- **La saisie** devient une etape `type` qui lit un placeholder `data[...]`, avec la valeur saisie dans `defaults`. Un champ laisse avec la touche d'action du clavier recoit `submit: true`. Un champ masque recoit `secret: true`, sa valeur dans `defaults` et un avertissement pour la changer avant de faire un commit. Passez `--no-data` pour ecrire les valeurs telles qu'elles ont ete saisies.
- **Les changements de page** apres un appui deviennent `waitForPage(SignUpPage.path)` lorsqu'une de vos pages declare la route, ou `waitForPage('/sign-up')` sinon. Revenir en arriere devient `back()`.
- **Le defilement** est ecrit comme `scrollTo(...)` sur l'element sur lequel vous appuyez ensuite, et seulement lorsque le defilement l'a fait entrer dans l'arbre. Le lecteur revele tout le reste de lui-meme.
- **Les glissements qui ne sont pas des defilements** -- un `Dismissible`, un `PageView`, un `Slider` -- n'ont pas encore de verbe de playbook, ils sont donc laisses sous forme de commentaire `// TODO` avec les points de depart et d'arrivee.

L'enregistrement necessite un build debug et fonctionne aussi bien sur les simulateurs, les emulateurs que les appareils. Les propres appuis d'un playbook ne sont jamais enregistres. Consultez [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) pour les options de la commande.

<div id="steps"></div>

## Etapes

Les `steps` d'un playbook sont construites avec les verbes qu'il herite :

| Etape | Description |
|------|-------------|
| `tap(target)` | Appuyer sur une cible |
| `longPress(target)` | Maintenir l'appui sur une cible |
| `type(target, text)` | Saisir dans un champ. Le vide d'abord sauf avec `clear: false` ; `submit: true` appuie sur le bouton d'action du clavier ; `secret: true` masque le texte dans la legende et le rapport |
| `scrollTo(target)` | Defiler jusqu'a ce qu'une cible soit a l'ecran. Passez `within:` pour defiler un element defilable precis |
| `see(target)` | Verifier qu'une cible est a l'ecran, en attendant qu'elle apparaisse |
| `dontSee(target)` | Verifier qu'une cible n'est pas a l'ecran, en attendant qu'elle disparaisse |
| `waitFor(target)` | Attendre une cible, jusqu'a `timeout` (10 secondes par defaut) |
| `waitForPage(path)` | Attendre la page d'un chemin de route ou d'une `RouteView` (par ex. `HomePage.path`) |
| `visit(path)` | Ouvrir une page par chemin de route ou `RouteView`, avec `data:` optionnel |
| `back()` | Revenir a la page precedente |
| `pause(seconds: 1)` | Rester immobile, par ex. pour laisser respirer une demo |
| `step(title, action)` | Executer du code avec les memes verbes, via un `PlaybookActor` |
| `scene(title, steps)` | Regrouper des etapes sous un titre ; leurs legendes commencent par celui-ci et leurs resultats sont imbriques dans le rapport |
| `screenshot(name)` | Capturer l'ecran -- voir [Captures d'ecran](#screenshots) |
| `play(playbook)` | Executer un autre playbook -- voir [Jouer d'autres playbooks](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

Les etapes s'executent dans l'ordre et s'arretent a la premiere erreur. Les etapes restantes sont signalees comme ignorees.

<div id="targets"></div>

## Cibles

La plupart des etapes prennent une cible : ce sur quoi appuyer, ou saisir, ou ce qu'il faut chercher.

<div id="plain-strings"></div>

### Chaines simples

Une chaine simple est reconnue comme une personne lit l'ecran. Elle essaie dans l'ordre :

1. Le texte visible
2. L'indice ou le label d'un champ
3. Une infobulle
4. Un label de semantique
5. Une `ValueKey`
6. Un texte qui contient la chaine

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

Une `Key`, un `Type` ou un `IconData` peut aussi etre passe directement a une etape.

<div id="find"></div>

### Find

Utilisez `Find` quand une chaine simple n'est pas assez precise :

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Correspond a |
|--------|---------|
| `Find.text(text)` | Du texte visible |
| `Find.textContaining(text)` | Un texte qui contient la chaine |
| `Find.icon(icon, label:)` | Une icone. `label:` la nomme dans les legendes d'etape |
| `Find.type<T>()` | Un widget de type `T` |
| `Find.key(key)` | Un widget avec la cle |
| `Find.field(name)` | Un champ `NyForm` par son nom |
| `Find.input(text)` | Un champ de texte par indice, label ou placeholder |
| `Find.tooltip(message)` | Une infobulle |
| `Find.semantics(label)` | Un label de semantique |
| `Find.where(test)` | Les widgets qui passent votre test |

Donnez un `label` a une icone et les legendes de ses etapes affichent `Tap the bag icon` au lieu de `Tap icon f37d` :

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

Affinez n'importe quel finder avec `.inside(ancestor)`, `.at(index)` (en comptant a partir de 0), `.first` ou `.last`.

Un appui est refuse lorsqu'un element sans rapport, comme la barriere d'une boite de dialogue, recouvre sa cible.

<div id="data-and-setup"></div>

## Donnees et configuration

`defaults` contient les donnees avec lesquelles les etapes demarrent. Lisez-les via `data` dans `steps`. `setUp` s'execute avant les etapes et `tearDown` apres, meme si une etape a echoue :

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` execute des [Seeders](/docs/{{ $version }}/metro-live#seeders) (instances ou noms enregistres) et les enregistre comme `seed` dans `metro live`.

<div id="playing-other-playbooks"></div>

## Jouer d'autres playbooks

`play()` execute un autre playbook en entier, avec ses propres `setUp` et `tearDown`. Passez une instance ou le nom sous lequel il est enregistre :

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Les donnees** sont fusionnees dans cet ordre : les `defaults` de l'enfant, puis les `data` du parent, puis le `data:` passe a `play`.
- **La vitesse** est heritee du parent, sauf si vous passez `speed:`.
- **Les resultats** sont imbriques sous l'etape dans le rapport.
- **Un enfant en echec** fait echouer l'etape.
- **Les cycles** sont refuses, et les playbooks s'imbriquent sur 8 niveaux au maximum.

<div id="speed"></div>

## Vitesse

`PlaybookSpeed` regle le rythme de chaque attente :

| Vitesse | Description |
|-------|-------------|
| `PlaybookSpeed.instant` | Aucune attente au-dela de ce que necessite le defilement. Pense pour les tests |
| `PlaybookSpeed.fast` | Rapide, mais toujours facile a suivre |
| `PlaybookSpeed.natural` | Le rythme d'une personne qui utilise l'application. La valeur par defaut |
| `PlaybookSpeed.slow` | Assez lent pour commenter par-dessus |

Modifiez n'importe quelle vitesse avec `.times(n)` :

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // deux fois plus lent
```

Une vitesse passee a `run()`, `--speed` depuis Metro, ou la vitesse d'un playbook qui joue celui-ci l'emporte sur celle du playbook lui-meme.

<div id="finger-and-caption"></div>

## Doigt et legende

Le doigt et les legendes d'etape sont desactives par defaut. Redefinissez `showFinger` et `caption` pour les activer :

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // ou .top
```

`showFinger` vaut `false` par defaut et `caption` vaut `PlaybookCaption.hidden` ; `PlaybookCaption` vaut `top`, `bottom` ou `hidden`. Les appuis et les glissements ont lieu dans les deux cas. Un playbook joue par celui-ci avec `play()` utilise son propre reglage, et celui de ce playbook revient a la fin. Pour masquer les deux pendant une execution, utilisez `--no-overlay` depuis Metro ou `run(overlay: false)` depuis Dart.

Avant nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0), le doigt et la legende du bas etaient actives par defaut. Ajoutez les redefinitions ci-dessus pour conserver ce rendu.

<div id="screenshots"></div>

## Captures d'ecran

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Ajoutez une etape `screenshot` partout ou l'ecran vaut la peine d'etre conserve :

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` enregistre chacune sous `screenshots/<playbook>/<name>.png`, sans aucun flag. Une execution depuis le code les collecte avec [`onScreenshot`](#collecting-screenshots), et toute autre execution, comme `PLAYBOOK_ON_BOOT`, attend simplement que l'ecran se stabilise. Un nom commence par une lettre ou un chiffre et utilise des lettres, des chiffres, `-`, `_` et `.`.

| Parametre | Description |
|-----------|-------------|
| `name` | Le nom sous lequel la capture est enregistree |
| `locales` | Capturer une fois dans chacun de ces codes de langue. `Screenshot.allLocales` correspond a toutes les langues que l'application embarque |
| `themes` | Capturer une fois dans chacun de ces identifiants de theme. `Screenshot.allThemes` correspond a tous les themes enregistres |
| `caption` | Le texte au-dessus de l'ecran lorsque l'execution encadre ses captures, ou une cle de traduction |
| `native` | Capturer avec Metro, sur l'iOS Simulator et Android (`true` par defaut) |

Sur l'iOS Simulator et Android, Metro capture tout l'ecran de l'appareil, de sorte que la barre d'etat et le clavier y figurent, et le doigt et la legende sont masques pendant ce temps. Ajoutez `--clean-status-bar` a l'execution pour afficher 9:41 avec le signal et la batterie au maximum. Ailleurs, et dans les executions `--store` et `--frame`, l'application capture son propre ecran. Passez `native: false` pour toujours utiliser la capture de l'application :

``` dart
screenshot('checkout', native: false),
```

Pour enregistrer l'ecran de chaque etape a la place, lancez `metro live:run playbook <name> --screenshots`. Consultez [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) pour les options qui dimensionnent les captures pour l'App Store et Google Play, et [Screenshots](/docs/{{ $version }}/screenshots) pour la classe `Screenshot` qui les sous-tend.

<div id="screenshots-locales-and-themes"></div>

### Locales et themes

Donnez a une etape `screenshot` `locales`, `themes` ou les deux pour capturer le meme ecran une fois dans chacun :

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

L'application bascule sur place, en conservant la page et son etat, et revient a l'etat initial avant l'etape suivante. Chaque capture est enregistree dans un dossier nomme d'apres sa locale et son theme :

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Pour jouer plutot un playbook entier dans chaque langue et chaque theme, passez `--locales` et `--themes` a `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Collecter depuis le code

`run()` accepte `onScreenshot` pour recevoir les captures d'une execution sous forme de `PlaybookShot`, ce qui est pratique pour un test ou votre propre outillage :

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` choisit les ecrans qui sont captures :

| Mode | Capture |
|------|----------|
| `PlaybookScreenshots.off` | Rien |
| `PlaybookScreenshots.failures` | Uniquement l'ecran d'une etape en echec |
| `PlaybookScreenshots.marked` | Les etapes `screenshot()`, et les etapes en echec |
| `PlaybookScreenshots.auto` | Les etapes `screenshot()` d'un playbook qui en a, et chaque etape d'un playbook qui n'en a aucune. Les etapes en echec aussi. Le mode par defaut |
| `PlaybookScreenshots.all` | Chaque etape, les etapes `screenshot()`, et les etapes en echec |

`pixelRatio` dimensionne les captures par rapport a celles de l'appareil (`1.0` par defaut). Chaque `PlaybookShot` a un `screenshot` a [enregistrer](/docs/{{ $version }}/screenshots#saving-a-screenshot) ou dont lire `bytes`, un `kind`, ainsi que le `name`, la `locale` et le `theme` avec lesquels elle a ete prise.

<div id="registering-playbooks"></div>

## Enregistrer les playbooks

Enregistrez vos playbooks pour que Metro et `play('name')` puissent les trouver :

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` renvoie ce qui est enregistre. Vous pouvez aussi les passer lors de la configuration de Nylo dans votre `AppProvider` :

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` s'en charge pour vous. Il conserve le registre dans `lib/bootstrap/playbooks.dart`, y ajoute chaque nouveau playbook et passe `playbooks` a `nylo.configure(...)`. Le registre est une map vide dans les builds release :

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Un playbook enregistre s'execute sous sa cle. Un playbook non enregistre s'execute sous son `name`, ou sous le nom de sa classe en snake case (`SignUpPlaybook` devient `sign_up`).

<div id="running-a-playbook"></div>

## Executer un playbook

<div id="running-from-metro"></div>

### Depuis Metro

Avec votre application lancee en mode debug :

``` bash
metro live:run playbook sign_up --speed fast
```

Dans `metro live`, tapez `playbook sign_up`. Voir [Metro Live](/docs/{{ $version }}/metro-live#playbooks) pour toutes les options. Ajoutez `--record` pour sauvegarder une video de l'execution ; voir [Enregistrement](/docs/{{ $version }}/metro-live#recording-playbooks). Si l'execution cesse d'attendre apres 20 secondes, l'application a probablement ete lancee avec `flutter run --no-dds` ; voir [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

<div id="running-on-boot"></div>

### Au demarrage

Ajoutez ces cles a votre fichier `.env` pour jouer des playbooks des que l'application est a l'ecran :

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` est une liste de noms de playbooks separes par des virgules et `PLAYBOOK_SPEED` vaut `instant`, `fast`, `natural` ou `slow`. Les deux s'appliquent uniquement aux builds debug et profile.

<div id="running-from-dart"></div>

### Depuis Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` accepte `speed`, `data`, `clock`, `overlay` (`true` par defaut), `onEvent`, ainsi que `onScreenshot`, `screenshots` et `pixelRatio` pour [collecter des captures d'ecran](#collecting-screenshots). Une etape en echec ne leve pas d'exception -- verifiez `report.passed`. Un seul playbook peut s'executer a la fois ; utilisez `play()` pour en executer un dans un autre.

Arretez un playbook en cours avec `Playbook.stop()`. Les etapes restantes sont ignorees mais `tearDown` s'execute quand meme. `Playbook.isRunning` indique si un playbook est en cours.

<div id="reports"></div>

## Rapports

`run()` renvoie un `PlaybookReport` avec le `name` du playbook, sa `speed`, le temps `elapsed`, l'indication qu'il a ete `stopped`, et une liste `steps` de `PlaybookStepResult` (chacun avec un `title`, un `status`, un `elapsed`, une `error` et des `steps` imbriquees). `report.passed` vaut true lorsque toutes les etapes ont reussi, et l'affichage d'un rapport ressemble a la sortie de Metro. `report.toJson()` fournit les memes donnees en JSON.

<div id="release-builds"></div>

## Builds release

Les playbooks ne s'executent jamais dans un build release, afin qu'un parcours ne puisse pas piloter l'application d'un utilisateur :

- `Playbook.run()` leve une `UnsupportedError`.
- `addPlaybooks` est ignore.
- Le registre `lib/bootstrap/playbooks.dart` genere par Metro est vide.

`Playbook.isAvailable` indique si le build actuel peut executer des playbooks.

<div id="testing"></div>

## Tests

Executez un playbook dans un test de widget avec `NyTesterClock`, puis verifiez son rapport avec `expectPlaybookPassed` :

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Voir [Tester les playbooks](/docs/{{ $version }}/testing#testing-playbooks) pour les tests d'integration.
