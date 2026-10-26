# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction a Nylo Live")
- Le shell metro live
  - [Options partagees](#shared-options "Options acceptees par chaque commande live")
  - [Completion et historique](#tab-completion-and-history "Completion des tabulations et historique")
  - [Executer un script](#running-a-script "Executer un script .live")
- [live:devices](#live-devices "Lister les applications en cours d'execution")
- [live:status](#live-status "Afficher le statut de l'application")
- [live:run](#live-run "Executer une commande live integree")
- [Playbooks](#playbooks "Jouer des playbooks depuis Metro")
- [Enregistrer un parcours](#recording-a-journey "Enregistrer ce que vous faites sous forme de playbook")
- [Captures d'ecran](#screenshots "Enregistrer des captures d'ecran de l'application en cours d'execution")
- Commandes Live
  - [Creer une commande live](#creating-a-live-command "Creer une commande live")
  - [Executer une commande live](#running-a-live-command "Executer une commande live")
- Seeders
  - [Creer un seeder](#creating-a-seeder "Creer un seeder")
  - [Seeding et restauration](#seeding-and-rolling-back "Seeding et restauration")
- Snapshots de stockage
  - [Exporter un snapshot](#exporting-a-snapshot "Exporter un snapshot")
  - [Charger un snapshot](#loading-a-snapshot "Charger un snapshot")
- [Actions de page](#page-actions "Appeler une page depuis l'exterieur")

<div id="introduction"></div>

## Introduction

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** permet a Metro d'acceder a votre application pendant qu'elle s'execute en mode debug (ou profile), via le Dart VM service. Il trouve l'application grace au Dart Tooling Daemon sans aucune configuration, et ne se connecte jamais qu'aux applications construites a partir du projet courant.

Nylo Live est actif par defaut dans les builds debug et profile ; un build release n'enregistre rien. Les commandes qui se contentent de lire l'application (`live:status`, `live:run data`, `live:run routes`, ...) fonctionnent aussi dans les builds profile. Les commandes qui modifient l'application (`route`, `storage`, `auth`, les seeders, ...) necessitent un build debug.

Desactivez-le vous-meme, avant que quoi que ce soit d'autre ne s'execute, depuis un provider :

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## Le shell metro live

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Ouvrez un shell connecte a votre application en cours d'execution :

``` bash
metro live
```

Nylo Live decouvre les applications en cours d'execution du projet et se connecte a l'une d'elles -- il vous demande de choisir lorsque plusieurs sont en cours d'execution. Une fois connecte, le prompt affiche l'appareil et la route actuellement a l'ecran :

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Les commandes live perdent leur prefixe `metro live:` a l'interieur du shell -- `status` au lieu de `metro live:status`, `route /profile` au lieu de `metro live:run route /profile`. Quelques commandes ne fonctionnent qu'a l'interieur du shell : `seed`, `seed:rollback`, `export`, `record`, `reload` et `restart`. Tapez `help` pour lister toutes les commandes, ou `help <command>` pour voir les options d'une commande. Tapez `exit` (ou Ctrl+D) pour quitter.

Si l'application redemarre pendant que vous etes connecte, le shell se reconnecte automatiquement.

<div id="shared-options"></div>

### Options partagees

Chaque commande live -- dans le shell ou sous la forme `metro live:*` -- accepte les memes options :

| Option | Description |
|--------|-------------|
| `-d, --device <#\|name>` | Cibler une application par son numero dans `metro live:devices` ou par le nom de son appareil |
| `--all` | Executer sur toutes les applications en cours d'execution de ce projet |
| `--json` | Afficher du JSON exploitable par une machine |
| `--uri` | Utiliser cette adresse de VM service au lieu de decouvrir les applications |
| `--timeout` | Secondes a attendre pour chaque application lors de la decouverte (par defaut `3`) |

Une valeur d'option ecrite `@path` est lue depuis ce fichier -- pratique pour un gros payload `--data` :

``` bash
metro live:run route /profile --data @user.json
```

Commencez la valeur par `@@` lorsqu'elle commence vraiment par `@`.

<div id="tab-completion-and-history"></div>

### Completion et historique

A l'interieur du shell, Tab complete les noms de commandes, les options, les routes, les cles de stockage et de Backpack, les noms de seeders et les noms de playbooks en fonction de l'application connectee. L'historique est conserve dans `.dart_tool/nylo/live_history` et persiste entre les sessions.

<div id="running-a-script"></div>

### Executer un script

Redirigez un fichier vers `metro live` pour l'executer comme un script, une commande par ligne, en s'arretant a la premiere commande qui echoue :

``` bash
metro live < seeds/demo.live
```

Les lignes vides et les lignes commencant par `#` sont ignorees.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Liste les applications en cours d'execution de ce projet, numerotees pour `-d` :

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Affiche l'application, l'appareil, le build mode, la route et la pile actuelles, la locale, le theme, et si un utilisateur est connecte :

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Execute une commande integree qui inspecte ou pilote l'application :

``` bash
metro live:run <command> [arguments]
```

| Commande | Description |
|---------|-------------|
| `data` | Afficher les donnees et les champs de la page a l'ecran |
| `routes` | Lister les routes enregistrees |
| `route` | Ouvrir une page, avec un `--data` optionnel |
| `back` | Revenir a la page precedente, ou a une route |
| `deeplink` | Ouvrir un lien profond, ou montrer comment ils sont configures |
| `storage` | Lister le stockage local, ou afficher, sauvegarder ou supprimer une valeur |
| `storage:clear` | Vider le stockage local, avec un `--keep` optionnel |
| `backpack` | Lister les valeurs de Backpack, ou en afficher, sauvegarder ou supprimer une |
| `auth` | Afficher qui est connecte, ou se connecter et se deconnecter |
| `locale` | Changer la langue de l'application |
| `theme` | Changer le theme de l'application |
| `state` | Envoyer des donnees a un etat avec `updateState` |
| `event` | Declencher un de vos evenements |
| `toast` | Afficher une notification toast |
| `playbook` | Jouer vos [playbooks](/docs/{{ $version }}/playbooks), ou les lister |
| `screenshot` | Enregistrer une [capture d'ecran](#screenshots) de l'application, ou d'une seule `ScreenshotArea` |

A l'interieur de `metro live`, omettez la partie `live:run` -- `route /profile` au lieu de `metro live:run route /profile` :

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` lit egalement les champs declares par l'etat de votre page, directement depuis l'application en cours d'execution -- il n'y a pas de reflection en Flutter, donc cela ne fonctionne que pour un etat qui est reellement a l'ecran :

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbooks

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Jouez un ou plusieurs [playbooks](/docs/{{ $version }}/playbooks) sur votre application en cours d'execution. Chaque etape s'affiche au fur et a mesure :

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Indiquez plusieurs playbooks pour les jouer a la suite. Sans nom, ou avec `--list`, la commande liste les playbooks de l'application.

| Option | Description |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Jouer a ce rythme plutot qu'a celui propre a chaque playbook |
| `--data <json>` | Un objet JSON que chaque playbook de l'execution lit dans `data`, par-dessus ses valeurs par defaut |
| `--no-overlay` | Masquer le compte a rebours, ainsi que le doigt et les legendes des playbooks qui les affichent |
| `--loop` | Rejouer les playbooks en boucle jusqu'a Ctrl+C |
| `--record[=<file>]` | Enregistrer l'ecran de l'appareil autour de l'execution. Sauvegarde dans `build/playbooks/<playbooks>-<device>-<time>.mp4` ; `--record=demo.mp4` (ou `.gif`) choisit le fichier, relatif au projet. Necessite ffmpeg |
| `--no-captions` | Omettre le fichier de legendes `.vtt` et les chapitres d'un enregistrement |
| `--clean-status-bar` | Afficher 9:41 avec le signal et la batterie pleins pendant la lecture des playbooks, puis la remettre en l'etat. Fonctionne aussi sans `--record` |
| `--countdown <seconds>` | Compter a rebours de 0 a 10 secondes a l'ecran avant la premiere etape |
| `--screenshots[=<mode>]` | Enregistrer aussi l'ecran de chaque etape. `<mode>` vaut `all`, `auto`, `marked`, `failures` ou `off` ; voir [Captures d'ecran des playbooks](#playbook-screenshots) |
| `--scale <n>` | La taille des captures par rapport a celle de l'appareil, jusqu'a 4, par ex. `0.5` pour la moitie |
| `--locales <list>` | Jouer une fois dans chacune de ces locales, par ex. `en,es`, ou `all` pour toutes celles de l'application |
| `--themes <list>` | Jouer une fois dans chacun de ces themes, par ex. `light,dark`, ou `all` pour tous ceux de l'application |
| `--store <id>` | Dessiner les captures a la taille d'un app store, par ex. `ios-6.9` |
| `--frame` | Dessiner chaque ecran dans un contour de telephone |
| `--background <hex>` | La couleur autour de l'ecran, par ex. `"#1B2027"` |
| `--list` | Lister les playbooks de l'application au lieu d'en jouer un |

Ctrl+C demande a l'application de s'arreter : les etapes restantes sont ignorees, mais `tearDown` s'execute quand meme. Appuyez une seconde fois sur Ctrl+C pour arreter d'attendre.

Si aucune etape n'atteint Metro en 20 secondes, Metro arrete d'attendre et fait echouer l'execution. L'application joue le playbook, mais ses evenements n'arrivent pas, ce qui se produit lorsque l'application a ete lancee avec `flutter run --no-dds`. Lancez-la avec un simple `flutter run` et reessayez.

Dans le shell `metro live`, la commande est `playbook <name>`, et Tab complete les noms de playbooks :

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### Enregistrement

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Ajoutez `--record` pour sauvegarder une video de l'execution :

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Dans le shell `metro live`, tapez `playbook sign_up --record`.

L'enregistrement necessite ffmpeg (par exemple `brew install ffmpeg`). Metro le verifie avant que quoi que ce soit ne soit joue.

- **iOS Simulator** -- enregistre avec `xcrun simctl`.
- **Emulateur ou appareil Android** -- enregistre avec `adb screenrecord`, qui s'arrete au bout de 3 minutes, ou avec `scrcpy` s'il est installe (sans limite, et il enregistre le son sur Android 11+). Si plusieurs appareils Android sont connectes et que Metro ne peut pas determiner lequel execute l'application, definissez `ANDROID_SERIAL`.
- **iPhone physique** -- ne peut pas etre enregistre depuis la ligne de commande. Enregistrez avec QuickTime ou le Centre de controle et utilisez `--countdown 3` pour avoir le temps de demarrer.

Le resultat est un `.mp4` (H.264, 60 fps constants) dont la duree correspond exactement a celle de l'execution, ou un `.gif` de 480px de large a 15 fps. Chaque etape devient une legende dans un fichier `.vtt` a cote du `.mp4` et un chapitre a l'interieur. Ils sont generes a partir des etapes de l'execution, que le playbook affiche ou non des legendes a l'ecran. Passez `--no-captions` pour les ignorer.

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

Un playbook qui echoue ou qui est interrompu est quand meme sauvegarde, si bien qu'un enregistrement fait aussi office de rapport de bug. Ctrl+C arrete le playbook (`tearDown` s'execute quand meme) et l'enregistrement est tout de meme sauvegarde.

`--clean-status-bar` conserve une barre d'etat que vous avez deja definie avec `simctl status_bar` sur le Simulator. Sur Android, il utilise le mode demo et le desactive ensuite. Avec plusieurs applications (`--all`), chaque appareil obtient son propre fichier contenant le nom de l'appareil.

Un playbook masque par defaut son doigt et ses legendes. Pour une video de demo, definissez `showFinger` et `caption` sur le playbook -- voir [Doigt et legende](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Captures d'ecran

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Les [etapes `screenshot()`](/docs/{{ $version }}/playbooks#screenshots) d'un playbook sont enregistrees dans `screenshots/<playbook>/` sans aucun flag. Ajoutez des options pour les capturer dans chaque langue et chaque theme, ou pour les dimensionner pour la fiche d'un store :

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` enregistre aussi l'ecran apres chaque etape d'un playbook qui n'a aucune etape `screenshot()`, et `--screenshots=all` le fait pour chaque playbook. L'ecran d'une etape en echec est enregistre dans `build/playbooks/failures` ; `--screenshots=off` desactive cela.

`--locales` et `--themes` jouent le playbook une fois dans chacun, et la locale et le theme propres a l'application sont restaures ensuite. `--store` liste les tailles lorsqu'il n'a pas de valeur, et `--frame` dessine chaque ecran dans un contour de telephone avec le `caption:` de l'etape au-dessus. `--clean-status-bar` affiche 9:41 avec le signal et la batterie au maximum dans les captures que Metro prend avec l'iOS Simulator ou `adb`.

<div id="playbook-live-commands"></div>

### Reference des commandes live

Les commandes sous-jacentes sont `playbooks.list`, `playbooks.run` et `playbooks.stop`. Une execution renvoie immediatement son identifiant et diffuse chaque etape sous forme d'evenement `nylo.playbook`, ce qui lui permet de depasser le delai d'expiration d'une commande. `live:status` liste les playbooks de l'application. Les playbooks sont apparus avec la version 9 du protocole Nylo Live. La prise en charge de l'enregistrement -- `countdown` dans `playbooks.run` (avec un evenement `countdown` chaque seconde) et `simulator` (l'UDID de l'iOS Simulator) dans `status` -- est apparue avec la version 10 du protocole.

<div id="recording-a-journey"></div>

## Enregistrer un parcours

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` ecrit ce que vous faites dans l'application en cours d'execution sous forme de [playbook](/docs/{{ $version }}/playbooks#recording-a-playbook). Il ne fonctionne qu'a l'interieur du shell `metro live` :

``` plaintext
› record sign_up
```

Utilisez l'application, puis appuyez sur Entree lorsque le parcours est termine. Le playbook est cree dans `lib/app/playbooks/sign_up_playbook.dart`, enregistre dans `lib/bootstrap/playbooks.dart`, et Metro propose de faire un hot restart de l'application et de le rejouer a la vitesse `fast`.

| Option | Description |
|--------|-------------|
| `--description <text>` | Une description d'une ligne, affichee quand Metro liste les playbooks |
| `--no-data` | Ecrire les valeurs saisies telles qu'elles ont ete tapees, au lieu de placeholders `data[...]` avec leurs valeurs par defaut |
| `--no-replay` | Ignorer la question sur la relecture |
| `--force`, `-f` | Remplacer un fichier de playbook qui existe deja |

Pendant l'enregistrement, tapez `a` puis Entree pour que le prochain appui devienne un `see()`, `d` pour un `dontSee()`, `s <title>` pour demarrer une scene, `u` pour annuler la derniere etape et `p` pour mettre en pause. Ctrl+C abandonne l'enregistrement. `metro make:playbook sign_up --record` ouvre le shell et demarre l'enregistrement en une seule fois.

Les commandes sous-jacentes sont `record.start`, `record.stop`, `record.undo`, `record.mark` et `record.status`. Un enregistrement diffuse chaque etape sous forme d'evenement `nylo.record`. L'enregistrement est apparu avec la version 11 du protocole Nylo Live.

<div id="screenshots"></div>

## Captures d'ecran

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Enregistrez un PNG de votre application en cours d'execution :

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Chaque fichier est enregistre dans `screenshots/` dans votre projet, nomme `<mode>-<device>-<date>-<time>.png`. Sur l'iOS Simulator et sur Android, Metro capture tout l'ecran de l'appareil avec le Simulator ou `adb`, de sorte que la barre d'etat et le clavier y figurent. Tout autre appareil obtient la capture propre a l'application, c'est-a-dire les pages de l'application telles que l'utilisateur les voit.

| Option | Description |
|--------|-------------|
| `--area <name>` | Capturer uniquement la `ScreenshotArea` portant ce nom |
| `--scale <n>` | La taille par rapport a celle de l'appareil, jusqu'a 4, par ex. `0.5` pour la moitie |
| `--locales <list>` | Capturer l'ecran dans chacune de ces locales, par ex. `en,es`, ou `all` pour toutes celles de l'application |
| `--themes <list>` | Capturer l'ecran dans chacun de ces themes, par ex. `light,dark`, ou `all` pour tous ceux de l'application |
| `--store <id>` | Dessiner a la taille de capture d'un app store, par ex. `ios-6.9`. Seul, `--store` liste les tailles |
| `--frame` | Dessiner l'ecran dans un contour de telephone, sur un arriere-plan |
| `--caption <text>` | Texte au-dessus du `--frame`, ou une cle de traduction |
| `--background <hex>` | La couleur autour de l'ecran, derriere un `--frame` ou a cote d'une taille `--store` de forme differente, par ex. `"#1B2027"` |
| `--out <path>` | Enregistrer dans ce `.png`, ou dans un dossier se terminant par `/` (par defaut : `screenshots/`) |
| `--open` | Ouvrir la capture une fois enregistree |
| `--copy` | Copier la capture dans le presse-papiers |
| `--native` | Capturer avec l'iOS Simulator ou `adb`. C'est le comportement par defaut pour une capture d'ecran entier ; `--no-native` utilise la capture propre a l'application |
| `--clean-status-bar` | Afficher 9:41 avec le signal et la batterie au maximum dans une capture `--native` |

Avec `--locales` ou `--themes`, Metro enregistre un PNG par paire dans un dossier, nomme `es.png`, `dark.png` ou `es-dark.png`, en basculant l'application sur place puis en la restaurant ensuite. `--area`, `--scale`, `--store` et `--frame` utilisent la capture propre a l'application.

Dans le shell `metro live`, tapez `screenshot --open`. Pour prendre des captures depuis le code de votre application, consultez [Screenshots](/docs/{{ $version }}/screenshots).

<div id="live-commands"></div>

## Commandes Live

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Enregistrez vos propres commandes a executer a l'interieur de l'application, aux cotes de celles integrees ci-dessus.

<div id="creating-a-live-command"></div>

### Creer une commande live

``` bash
metro make:command seed_cart --live
```

| Option | Description |
|--------|-------------|
| `--category`, `-c` | La categorie de la commande (par defaut : `app`) |
| `--description` | Une description en une ligne affichee par le `--help` de la commande |
| `--force`, `-f` | Cree le fichier meme s'il existe deja |

Cela cree une `LiveCommand` dans `lib/app/commands/`, la marque `"type": "live"` dans `commands.json`, l'enregistre dans `lib/bootstrap/live_commands.dart`, et connecte `liveCommands: liveCommands` dans `nylo.configure(...)` de `app_provider.dart` :

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` partage la forme `builder`/`handle` avec les commandes propres a Metro, mais `handle` s'execute **a l'interieur de l'application**, et peut donc utiliser `NyStorage`, `routeTo`, `Auth` et vos modeles. `package:nylo_framework/live.dart` exporte `LiveCommand` aux cotes de `Seeder`, `StorageSnapshot`, `LiveException` et `LiveOutput`.

<div id="running-a-live-command"></div>

### Executer une commande live

Une nouvelle commande live necessite un hot restart avant que l'application en cours d'execution puisse l'executer. Ensuite, depuis le terminal :

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeders

<!-- uncertain: terme Nylo Live "Seeder" absent du fichier de locale existant -- conserve comme emprunt technique, a l'image de "Backpack" -->

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Un `Seeder` place votre application en cours d'execution dans un etat connu -- connecte, onboarding termine, donnees d'exemple -- puis l'en fait ressortir.

<div id="creating-a-seeder"></div>

### Creer un seeder

``` bash
metro make:seeder demo_user
```

| Option | Description |
|--------|-------------|
| `--description` | Une description en une ligne, affichee quand `seed` liste les seeders |
| `--force`, `-f` | Cree le fichier meme s'il existe deja |

Cela cree un `Seeder` dans `lib/app/seeders/`, l'enregistre dans `lib/bootstrap/seeders.dart`, et connecte `seeders: seeders` dans `nylo.configure(...)` :

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

Chaque valeur de stockage et de Backpack modifiee par `up()` est enregistree automatiquement, si bien que le `down()` par defaut (`restore()`) remet chacune exactement comme avant, dans l'ordre inverse -- meme apres un hot restart. Appelez `seed(otherSeeders)` depuis un seeder pour en executer d'autres dans le cadre de celui-ci ; leurs changements sont eux aussi enregistres et annules au rollback.

<div id="seeding-and-rolling-back"></div>

### Seeding et restauration

<!-- uncertain: choix de traduction pour "Rolling Back" -- rendu par "restauration" par coherence avec restore()/restaurer utilises ailleurs dans les docs -->

Le seeding, la restauration et l'export ne fonctionnent qu'a l'interieur du shell `metro live` :

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` seul liste les seeders enregistres. `--fresh` vide le stockage et Backpack avant le seeding, et `--restart` relance l'application avec un hot restart juste apres, afin qu'elle demarre deja dans l'etat du seeder :

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Snapshots de stockage

<!-- uncertain: terme Nylo Live "snapshot" absent du fichier de locale existant -- conserve comme emprunt technique courant en francais -->

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` sauvegarde tout ce que l'application detient actuellement en stockage et dans Backpack -- sous forme de seeder, ou de fichier JSON portable -- et `seed <file>` le recharge ensuite.

<div id="exporting-a-snapshot"></div>

### Exporter un snapshot

Executez-la sans nom pour previsualiser ce qui serait capture :

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

Nommez-la pour la sauvegarder comme seeder dans `lib/app/seeders/` (comme `make:seeder`, mais deja rempli avec les valeurs capturees) :

``` plaintext
› export pro_user
```

Ou ecrivez-la plutot dans un fichier :

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Option | Description |
|--------|-------------|
| `--to <path>` | Ecrire le snapshot dans ce fichier JSON au lieu d'un seeder |
| `--description` | Une description en une ligne pour le seeder |
| `--only <keys>` | Cles separees par des virgules a exporter ; `*` correspond a n'importe quoi, ex. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Cles separees par des virgules a exclure, ex. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Inclure les valeurs de Backpack (par defaut : incluses) |
| `--force`, `-f` | Remplacer un seeder ou un fichier qui existe deja |

Un `Model`, ou toute classe avec un decoder enregistre, est marque par son nom de classe afin de revenir via son propre decoder plutot que comme une simple map. `export` avertit quand le snapshot contient des cles qui ressemblent a des identifiants sensibles, et quand il est assez volumineux pour que vous ayez probablement besoin de `--except`.

<div id="loading-a-snapshot"></div>

### Charger un snapshot

``` plaintext
› seed snapshots/pro_user.json
```

Le chargement d'un fichier est enregistre de la meme maniere qu'un seeder, donc `seed:rollback <name>` (le nom du fichier, ou ce que `--as` lui a donne) l'annule egalement. Depuis le code, un seeder peut appliquer un snapshot capture avec `importSnapshot` :

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` ecrit exactement cette forme, donc un fichier ecrit avec `--to` peut etre colle directement dans le champ `snapshot` d'un seeder.

<div id="page-actions"></div>

## Actions de page

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` sur une page vous donne un `PageStateActions` pour appeler cette page depuis l'exterieur, sans construire manuellement un appel `stateAction(...)`.

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Voir [Appeler les actions d'une page](/docs/{{ $version }}/state-management#page-actions-shortcut) pour l'ensemble des actions integrees et comment declarer vos propres actions typees. Nylo Live lit et utilise ces memes actions : `metro live:run data` liste ce qu'une page a l'ecran expose sous `actions`, et `metro live:run state` envoie des donnees a une page directement par le nom de son etat.
