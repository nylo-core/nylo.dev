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

Les commandes live perdent leur prefixe `metro live:` a l'interieur du shell -- `status` au lieu de `metro live:status`, `route /profile` au lieu de `metro live:run route /profile`. Quelques commandes ne fonctionnent qu'a l'interieur du shell : `seed`, `seed:rollback`, `export`, `reload` et `restart`. Tapez `help` pour lister toutes les commandes, ou `help <command>` pour voir les options d'une commande. Tapez `exit` (ou Ctrl+D) pour quitter.

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

A l'interieur du shell, Tab complete les noms de commandes, les options, les routes, les cles de stockage et de Backpack, et les noms de seeders en fonction de l'application connectee. L'historique est conserve dans `.dart_tool/nylo/live_history` et persiste entre les sessions.

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
