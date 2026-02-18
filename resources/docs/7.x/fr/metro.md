# Outil CLI Metro

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Installation](#install "Installation")
- Commandes Make
  - [Creer un controleur](#make-controller "Creer un controleur")
  - [Creer un modele](#make-model "Creer un modele")
  - [Creer une page](#make-page "Creer une page")
  - [Creer un widget stateless](#make-stateless-widget "Creer un widget stateless")
  - [Creer un widget stateful](#make-stateful-widget "Creer un widget stateful")
  - [Creer un widget journey](#make-journey-widget "Creer un widget journey")
  - [Creer un service API](#make-api-service "Creer un service API")
  - [Creer un evenement](#make-event "Creer un evenement")
  - [Creer un provider](#make-provider "Creer un provider")
  - [Creer un theme](#make-theme "Creer un theme")
  - [Creer des formulaires](#make-forms "Creer des formulaires")
  - [Creer un garde de route](#make-route-guard "Creer un garde de route")
  - [Creer un fichier de configuration](#make-config-file "Creer un fichier de configuration")
  - [Creer une commande](#make-command "Creer une commande")
  - [Creer un widget a etat gere](#make-state-managed-widget "Creer un widget a etat gere")
  - [Creer un Navigation Hub](#make-navigation-hub "Creer un Navigation Hub")
  - [Creer un modal de bas de page](#make-bottom-sheet-modal "Creer un modal de bas de page")
  - [Creer un bouton](#make-button "Creer un bouton")
  - [Creer un intercepteur](#make-interceptor "Creer un intercepteur")
  - [Creer un fichier Env](#make-env-file "Creer un fichier Env")
  - [Generer une cle](#make-key "Generer une cle")
- Icones d'application
  - [Generer les icones d'application](#build-app-icons "Generer les icones d'application")
- Commandes personnalisees
  - [Creer des commandes personnalisees](#creating-custom-commands "Creer des commandes personnalisees")
  - [Executer des commandes personnalisees](#running-custom-commands "Executer des commandes personnalisees")
  - [Ajouter des options aux commandes](#adding-options-to-custom-commands "Ajouter des options aux commandes")
  - [Ajouter des flags aux commandes](#adding-flags-to-custom-commands "Ajouter des flags aux commandes")
  - [Methodes d'aide](#custom-command-helper-methods "Methodes d'aide")
  - [Methodes de saisie interactive](#interactive-input-methods "Methodes de saisie interactive")
  - [Formatage de sortie](#output-formatting "Formatage de sortie")
  - [Helpers du systeme de fichiers](#file-system-helpers "Helpers du systeme de fichiers")
  - [Helpers JSON et YAML](#json-yaml-helpers "Helpers JSON et YAML")
  - [Helpers de conversion de casse](#case-conversion-helpers "Helpers de conversion de casse")
  - [Helpers de chemins projet](#project-path-helpers "Helpers de chemins projet")
  - [Helpers de plateforme](#platform-helpers "Helpers de plateforme")
  - [Commandes Dart et Flutter](#dart-flutter-commands "Commandes Dart et Flutter")
  - [Manipulation de fichiers Dart](#dart-file-manipulation "Manipulation de fichiers Dart")
  - [Helpers de repertoires](#directory-helpers "Helpers de repertoires")
  - [Helpers de validation](#validation-helpers "Helpers de validation")
  - [Scaffolding de fichiers](#file-scaffolding "Scaffolding de fichiers")
  - [Executeur de taches](#task-runner "Executeur de taches")
  - [Sortie en tableau](#table-output "Sortie en tableau")
  - [Barre de progression](#progress-bar "Barre de progression")


<div id="introduction"></div>

## Introduction

Metro est un outil CLI qui fonctionne sous le capot du framework {{ config('app.name') }}.
Il fournit de nombreux outils utiles pour accelerer le developpement.

<div id="install"></div>

## Installation

Lorsque vous creez un nouveau projet Nylo avec `nylo init`, la commande `metro` est automatiquement configuree pour votre terminal. Vous pouvez l'utiliser immediatement dans n'importe quel projet Nylo.

Executez `metro` depuis le repertoire de votre projet pour voir toutes les commandes disponibles :

``` bash
metro
```

Vous devriez voir une sortie similaire a celle ci-dessous.

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
  make:key
```

<div id="make-controller"></div>

## Creer un controleur

- [Creer un nouveau controleur](#making-a-new-controller "Creer un nouveau controleur avec Metro")
- [Forcer la creation d'un controleur](#forcefully-make-a-controller "Forcer la creation d'un controleur avec Metro")
<div id="making-a-new-controller"></div>

### Creer un nouveau controleur

Vous pouvez creer un nouveau controleur en executant la commande suivante dans le terminal.

``` bash
metro make:controller profile_controller
```

Cela creera un nouveau controleur s'il n'existe pas deja dans le repertoire `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Forcer la creation d'un controleur

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un controleur existant s'il existe deja.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Creer un modele

- [Creer un nouveau modele](#making-a-new-model "Creer un nouveau modele avec Metro")
- [Creer un modele a partir de JSON](#make-model-from-json "Creer un nouveau modele a partir de JSON avec Metro")
- [Forcer la creation d'un modele](#forcefully-make-a-model "Forcer la creation d'un modele avec Metro")
<div id="making-a-new-model"></div>

### Creer un nouveau modele

Vous pouvez creer un nouveau modele en executant la commande suivante dans le terminal.

``` bash
metro make:model product
```

Le modele nouvellement cree sera place dans `lib/app/models/`.

<div id="make-model-from-json"></div>

### Creer un modele a partir de JSON

**Arguments :**

L'utilisation du flag `--json` ou `-j` creera un nouveau modele a partir d'un payload JSON.

``` bash
metro make:model product --json
```

Ensuite, vous pouvez coller votre JSON dans le terminal et il generera un modele pour vous.

<div id="forcefully-make-a-model"></div>

### Forcer la creation d'un modele

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un modele existant s'il existe deja.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Creer une page

- [Creer une nouvelle page](#making-a-new-page "Creer une nouvelle page avec Metro")
- [Creer une page avec un controleur](#create-a-page-with-a-controller "Creer une nouvelle page avec un controleur avec Metro")
- [Creer une page d'authentification](#create-an-auth-page "Creer une nouvelle page d'authentification avec Metro")
- [Creer une page initiale](#create-an-initial-page "Creer une nouvelle page initiale avec Metro")
- [Forcer la creation d'une page](#forcefully-make-a-page "Forcer la creation d'une page avec Metro")

<div id="making-a-new-page"></div>

### Creer une nouvelle page

Vous pouvez creer une nouvelle page en executant la commande suivante dans le terminal.

``` bash
metro make:page product_page
```

Cela creera une nouvelle page si elle n'existe pas deja dans le repertoire `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Creer une page avec un controleur

Vous pouvez creer une nouvelle page avec un controleur en executant la commande suivante dans le terminal.

**Arguments :**

L'utilisation du flag `--controller` ou `-c` creera une nouvelle page avec un controleur.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Creer une page d'authentification

Vous pouvez creer une nouvelle page d'authentification en executant la commande suivante dans le terminal.

**Arguments :**

L'utilisation du flag `--auth` ou `-a` creera une nouvelle page d'authentification.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Creer une page initiale

Vous pouvez creer une nouvelle page initiale en executant la commande suivante dans le terminal.

**Arguments :**

L'utilisation du flag `--initial` ou `-i` creera une nouvelle page initiale.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Forcer la creation d'une page

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera une page existante si elle existe deja.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Creer un widget stateless

- [Creer un nouveau widget stateless](#making-a-new-stateless-widget "Creer un nouveau widget stateless avec Metro")
- [Forcer la creation d'un widget stateless](#forcefully-make-a-stateless-widget "Forcer la creation d'un widget stateless avec Metro")
<div id="making-a-new-stateless-widget"></div>

### Creer un nouveau widget stateless

Vous pouvez creer un nouveau widget stateless en executant la commande suivante dans le terminal.

``` bash
metro make:stateless_widget product_rating_widget
```

La commande ci-dessus creera un nouveau widget s'il n'existe pas deja dans le repertoire `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Forcer la creation d'un widget stateless

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un widget existant s'il existe deja.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Creer un widget stateful

- [Creer un nouveau widget stateful](#making-a-new-stateful-widget "Creer un nouveau widget stateful avec Metro")
- [Forcer la creation d'un widget stateful](#forcefully-make-a-stateful-widget "Forcer la creation d'un widget stateful avec Metro")

<div id="making-a-new-stateful-widget"></div>

### Creer un nouveau widget stateful

Vous pouvez creer un nouveau widget stateful en executant la commande suivante dans le terminal.

``` bash
metro make:stateful_widget product_rating_widget
```

La commande ci-dessus creera un nouveau widget s'il n'existe pas deja dans le repertoire `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Forcer la creation d'un widget stateful

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un widget existant s'il existe deja.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Creer un widget journey

- [Creer un nouveau widget journey](#making-a-new-journey-widget "Creer un nouveau widget journey avec Metro")
- [Forcer la creation d'un widget journey](#forcefully-make-a-journey-widget "Forcer la creation d'un widget journey avec Metro")

<div id="making-a-new-journey-widget"></div>

### Creer un nouveau widget journey

Vous pouvez creer un nouveau widget journey en executant la commande suivante dans le terminal.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

La commande ci-dessus creera un nouveau widget s'il n'existe pas deja dans le repertoire `lib/resources/widgets/`.

L'argument `--parent` est utilise pour specifier le widget parent auquel le nouveau widget journey sera ajoute.

Exemple

``` bash
metro make:navigation_hub onboarding
```

Ensuite, ajoutez les nouveaux widgets journey.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Forcer la creation d'un widget journey
**Arguments :**
L'utilisation du flag `--force` ou `-f` ecrasera un widget existant s'il existe deja.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Creer un service API

- [Creer un nouveau service API](#making-a-new-api-service "Creer un nouveau service API avec Metro")
- [Creer un nouveau service API avec un modele](#making-a-new-api-service-with-a-model "Creer un nouveau service API avec un modele avec Metro")
- [Forcer la creation d'un service API](#forcefully-make-an-api-service "Forcer la creation d'un service API avec Metro")

<div id="making-a-new-api-service"></div>

### Creer un nouveau service API

Vous pouvez creer un nouveau service API en executant la commande suivante dans le terminal.

``` bash
metro make:api_service user_api_service
```

Le service API nouvellement cree sera place dans `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Creer un nouveau service API avec un modele

Vous pouvez creer un nouveau service API avec un modele en executant la commande suivante dans le terminal.

**Arguments :**

L'utilisation de l'option `--model` ou `-m` creera un nouveau service API avec un modele.

``` bash
metro make:api_service user --model="User"
```

Le service API nouvellement cree sera place dans `lib/app/networking/`.

### Forcer la creation d'un service API

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un service API existant s'il existe deja.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Creer un evenement

- [Creer un nouvel evenement](#making-a-new-event "Creer un nouvel evenement avec Metro")
- [Forcer la creation d'un evenement](#forcefully-make-an-event "Forcer la creation d'un evenement avec Metro")

<div id="making-a-new-event"></div>

### Creer un nouvel evenement

Vous pouvez creer un nouvel evenement en executant la commande suivante dans le terminal.

``` bash
metro make:event login_event
```

Cela creera un nouvel evenement dans `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Forcer la creation d'un evenement

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un evenement existant s'il existe deja.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Creer un provider

- [Creer un nouveau provider](#making-a-new-provider "Creer un nouveau provider avec Metro")
- [Forcer la creation d'un provider](#forcefully-make-a-provider "Forcer la creation d'un provider avec Metro")

<div id="making-a-new-provider"></div>

### Creer un nouveau provider

Creez de nouveaux providers dans votre application en utilisant la commande ci-dessous.

``` bash
metro make:provider firebase_provider
```

Le provider nouvellement cree sera place dans `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Forcer la creation d'un provider

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un provider existant s'il existe deja.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Creer un theme

- [Creer un nouveau theme](#making-a-new-theme "Creer un nouveau theme avec Metro")
- [Forcer la creation d'un theme](#forcefully-make-a-theme "Forcer la creation d'un theme avec Metro")

<div id="making-a-new-theme"></div>

### Creer un nouveau theme

Vous pouvez creer des themes en executant la commande suivante dans le terminal.

``` bash
metro make:theme bright_theme
```

Cela creera un nouveau theme dans `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Forcer la creation d'un theme

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un theme existant s'il existe deja.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Creer des formulaires

- [Creer un nouveau formulaire](#making-a-new-form "Creer un nouveau formulaire avec Metro")
- [Forcer la creation d'un formulaire](#forcefully-make-a-form "Forcer la creation d'un formulaire avec Metro")

<div id="making-a-new-form"></div>

### Creer un nouveau formulaire

Vous pouvez creer un nouveau formulaire en executant la commande suivante dans le terminal.

``` bash
metro make:form car_advert_form
```

Cela creera un nouveau formulaire dans `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Forcer la creation d'un formulaire

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un formulaire existant s'il existe deja.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Creer un garde de route

- [Creer un nouveau garde de route](#making-a-new-route-guard "Creer un nouveau garde de route avec Metro")
- [Forcer la creation d'un garde de route](#forcefully-make-a-route-guard "Forcer la creation d'un garde de route avec Metro")

<div id="making-a-new-route-guard"></div>

### Creer un nouveau garde de route

Vous pouvez creer un garde de route en executant la commande suivante dans le terminal.

``` bash
metro make:route_guard premium_content
```

Cela creera un nouveau garde de route dans `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Forcer la creation d'un garde de route

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un garde de route existant s'il existe deja.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Creer un fichier de configuration

- [Creer un nouveau fichier de configuration](#making-a-new-config-file "Creer un nouveau fichier de configuration avec Metro")
- [Forcer la creation d'un fichier de configuration](#forcefully-make-a-config-file "Forcer la creation d'un fichier de configuration avec Metro")

<div id="making-a-new-config-file"></div>

### Creer un nouveau fichier de configuration

Vous pouvez creer un nouveau fichier de configuration en executant la commande suivante dans le terminal.

``` bash
metro make:config shopping_settings
```

Cela creera un nouveau fichier de configuration dans `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Forcer la creation d'un fichier de configuration

**Arguments :**

L'utilisation du flag `--force` ou `-f` ecrasera un fichier de configuration existant s'il existe deja.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Creer une commande

- [Creer une nouvelle commande](#making-a-new-command "Creer une nouvelle commande avec Metro")
- [Forcer la creation d'une commande](#forcefully-make-a-command "Forcer la creation d'une commande avec Metro")

<div id="making-a-new-command"></div>

### Creer une nouvelle commande

Vous pouvez creer une nouvelle commande en executant la commande suivante dans le terminal.

``` bash
metro make:command my_command
```

Cela creera une nouvelle commande dans `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Forcer la creation d'une commande

**Arguments :**
L'utilisation du flag `--force` ou `-f` ecrasera une commande existante si elle existe deja.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Creer un widget a etat gere

Vous pouvez creer un nouveau widget a etat gere en executant la commande suivante dans le terminal.

``` bash
metro make:state_managed_widget product_rating_widget
```

La commande ci-dessus creera un nouveau widget dans `lib/resources/widgets/`.

L'utilisation du flag `--force` ou `-f` ecrasera un widget existant s'il existe deja.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Creer un Navigation Hub

Vous pouvez creer un nouveau navigation hub en executant la commande suivante dans le terminal.

``` bash
metro make:navigation_hub dashboard
```

Cela creera un nouveau navigation hub dans `lib/resources/pages/` et ajoutera automatiquement la route.

**Arguments :**

| Flag | Court | Description |
|------|-------|-------------|
| `--auth` | `-a` | Creer comme page d'authentification |
| `--initial` | `-i` | Creer comme page initiale |
| `--force` | `-f` | Ecraser si existant |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Creer un modal de bas de page

Vous pouvez creer un nouveau modal de bas de page en executant la commande suivante dans le terminal.

``` bash
metro make:bottom_sheet_modal payment_options
```

Cela creera un nouveau modal de bas de page dans `lib/resources/widgets/`.

L'utilisation du flag `--force` ou `-f` ecrasera un modal existant s'il existe deja.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Creer un bouton

Vous pouvez creer un nouveau widget bouton en executant la commande suivante dans le terminal.

``` bash
metro make:button checkout_button
```

Cela creera un nouveau widget bouton dans `lib/resources/widgets/`.

L'utilisation du flag `--force` ou `-f` ecrasera un bouton existant s'il existe deja.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Creer un intercepteur

Vous pouvez creer un nouvel intercepteur reseau en executant la commande suivante dans le terminal.

``` bash
metro make:interceptor auth_interceptor
```

Cela creera un nouvel intercepteur dans `lib/app/networking/dio/interceptors/`.

L'utilisation du flag `--force` ou `-f` ecrasera un intercepteur existant s'il existe deja.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Creer un fichier Env

Vous pouvez creer un nouveau fichier d'environnement en executant la commande suivante dans le terminal.

``` bash
metro make:env .env.staging
```

Cela creera un nouveau fichier `.env` a la racine de votre projet.

<div id="make-key"></div>

## Generer une cle

Generez un `APP_KEY` securise pour le chiffrement de l'environnement. Celui-ci est utilise pour les fichiers `.env` chiffres dans la v7.

``` bash
metro make:key
```

**Arguments :**

| Flag / Option | Court | Description |
|---------------|-------|-------------|
| `--force` | `-f` | Ecraser l'APP_KEY existant |
| `--file` | `-e` | Fichier .env cible (par defaut : `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Generer les icones d'application

Vous pouvez generer toutes les icones d'application pour iOS et Android en executant la commande ci-dessous.

``` bash
dart run flutter_launcher_icons:main
```

Cela utilise la configuration <b>flutter_icons</b> dans votre fichier `pubspec.yaml`.

<div id="custom-commands"></div>

## Commandes personnalisees

Les commandes personnalisees vous permettent d'etendre la CLI de Nylo avec vos propres commandes specifiques au projet. Cette fonctionnalite vous permet d'automatiser les taches repetitives, d'implementer des workflows de deploiement ou d'ajouter toute fonctionnalite personnalisee directement dans les outils en ligne de commande de votre projet.

- [Creer des commandes personnalisees](#creating-custom-commands)
- [Executer des commandes personnalisees](#running-custom-commands)
- [Ajouter des options aux commandes](#adding-options-to-custom-commands)
- [Ajouter des flags aux commandes](#adding-flags-to-custom-commands)
- [Methodes d'aide](#custom-command-helper-methods)

> **Note :** Vous ne pouvez actuellement pas importer nylo_framework.dart dans vos commandes personnalisees, veuillez utiliser ny_cli.dart a la place.

<div id="creating-custom-commands"></div>

## Creer des commandes personnalisees

Pour creer une nouvelle commande personnalisee, vous pouvez utiliser la fonctionnalite `make:command` :

```bash
metro make:command current_time
```

Vous pouvez specifier une categorie pour votre commande en utilisant l'option `--category` :

```bash
# Specify a category
metro make:command current_time --category="project"
```

Cela creera un nouveau fichier de commande dans `lib/app/commands/current_time.dart` avec la structure suivante :

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

La commande sera automatiquement enregistree dans le fichier `lib/app/commands/commands.json`, qui contient une liste de toutes les commandes enregistrees :

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Executer des commandes personnalisees

Une fois creee, vous pouvez executer votre commande personnalisee en utilisant soit le raccourci Metro, soit la commande Dart complete :

```bash
metro app:current_time
```

Lorsque vous executez `metro` sans arguments, vous verrez vos commandes personnalisees listees dans le menu sous la section "Custom Commands" :

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Pour afficher les informations d'aide pour votre commande, utilisez le flag `--help` ou `-h` :

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Ajouter des options aux commandes

Les options permettent a votre commande d'accepter des entrees supplementaires des utilisateurs. Vous pouvez ajouter des options a votre commande dans la methode `builder` :

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Add an option with a default value
  command.addOption(
    'environment',     // option name
    abbr: 'e',         // short form abbreviation
    help: 'Target deployment environment', // help text
    defaultValue: 'development',  // default value
    allowed: ['development', 'staging', 'production'] // allowed values
  );

  return command;
}
```

Ensuite, accedez a la valeur de l'option dans la methode `handle` de votre commande :

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Command implementation...
}
```

Exemple d'utilisation :

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Ajouter des flags aux commandes

Les flags sont des options booleennes qui peuvent etre activees ou desactivees. Ajoutez des flags a votre commande en utilisant la methode `addFlag` :

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // flag name
    abbr: 'v',       // short form abbreviation
    help: 'Enable verbose output', // help text
    defaultValue: false  // default to off
  );

  return command;
}
```

Ensuite, verifiez l'etat du flag dans la methode `handle` de votre commande :

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Additional logging...
  }

  // Command implementation...
}
```

Exemple d'utilisation :

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Methodes d'aide

La classe de base `NyCustomCommand` fournit plusieurs methodes d'aide pour les taches courantes :

#### Affichage de messages

Voici quelques methodes pour afficher des messages dans differentes couleurs :

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Afficher un message d'information en texte bleu |
| [`error`](#custom-command-helper-formatting)     | Afficher un message d'erreur en texte rouge |
| [`success`](#custom-command-helper-formatting)   | Afficher un message de succes en texte vert |
| [`warning`](#custom-command-helper-formatting)   | Afficher un message d'avertissement en texte jaune |

#### Execution de processus

Executer des processus et afficher leur sortie dans la console :

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Ajouter un package a `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Ajouter plusieurs packages a `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Executer un processus externe et afficher la sortie dans la console |
| [`prompt`](#custom-command-helper-prompt)    | Collecter la saisie utilisateur sous forme de texte |
| [`confirm`](#custom-command-helper-confirm)   | Poser une question oui/non et retourner un resultat booleen |
| [`select`](#custom-command-helper-select)    | Presenter une liste d'options et laisser l'utilisateur en choisir une |
| [`multiSelect`](#custom-command-helper-multi-select) | Permettre a l'utilisateur de selectionner plusieurs options dans une liste |

#### Requetes reseau

Effectuer des requetes reseau via la console :

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Effectuer un appel API en utilisant le client API Nylo |


#### Indicateur de chargement

Afficher un indicateur de chargement pendant l'execution d'une fonction :

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Afficher un indicateur de chargement pendant l'execution d'une fonction |
| [`createSpinner`](#manual-spinner-control) | Creer une instance de spinner pour un controle manuel |

#### Helpers pour commandes personnalisees

Vous pouvez egalement utiliser les methodes d'aide suivantes pour gerer les arguments de commande :

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Obtenir une valeur string des arguments de commande |
| [`getBool`](#custom-command-helper-get-bool)   | Obtenir une valeur booleenne des arguments de commande |
| [`getInt`](#custom-command-helper-get-int)    | Obtenir une valeur entiere des arguments de commande |
| [`sleep`](#custom-command-helper-sleep) | Mettre en pause l'execution pour une duree specifiee |


### Execution de processus externes

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### Gestion des packages

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Add a package to pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Add a dev package to pubspec.yaml
addPackage('build_runner', dev: true);

// Add multiple packages at once
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Formatage de sortie

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## Methodes de saisie interactive

La classe de base `NyCustomCommand` fournit plusieurs methodes pour collecter les saisies utilisateur dans le terminal. Ces methodes facilitent la creation d'interfaces en ligne de commande interactives pour vos commandes personnalisees.

<div id="custom-command-helper-prompt"></div>

### Saisie de texte

```dart
String prompt(String question, {String defaultValue = ''})
```

Affiche une question a l'utilisateur et collecte sa reponse textuelle.

**Parametres :**
- `question` : La question ou l'invite a afficher
- `defaultValue` : Valeur par defaut optionnelle si l'utilisateur appuie simplement sur Entree

**Retourne :** La saisie de l'utilisateur sous forme de string, ou la valeur par defaut si aucune saisie n'a ete fournie

**Exemple :**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Confirmation

```dart
bool confirm(String question, {bool defaultValue = false})
```

Pose une question oui/non a l'utilisateur et retourne un resultat booleen.

**Parametres :**
- `question` : La question oui/non a poser
- `defaultValue` : La reponse par defaut (true pour oui, false pour non)

**Retourne :** `true` si l'utilisateur a repondu oui, `false` s'il a repondu non

**Exemple :**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // User confirmed or pressed Enter (accepting the default)
  await runProcess('flutter pub get');
} else {
  // User declined
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Selection unique

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Presente une liste d'options et laisse l'utilisateur en choisir une.

**Parametres :**
- `question` : L'invite de selection
- `options` : Liste des options disponibles
- `defaultOption` : Selection par defaut optionnelle

**Retourne :** L'option selectionnee sous forme de string

**Exemple :**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Selection multiple

```dart
List<String> multiSelect(String question, List<String> options)
```

Permet a l'utilisateur de selectionner plusieurs options dans une liste.

**Parametres :**
- `question` : L'invite de selection
- `options` : Liste des options disponibles

**Retourne :** Une liste des options selectionnees

**Exemple :**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Methode d'aide API

La methode d'aide `api` simplifie les requetes reseau depuis vos commandes personnalisees.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Exemples d'utilisation de base

### Requete GET

```dart
// Fetch data
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Requete POST

```dart
// Create a resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Requete PUT

```dart
// Update a resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Requete DELETE

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Requete PATCH

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Avec des parametres de requete

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Avec Spinner

```dart
// Using with spinner for better UI
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Process the data
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Fonctionnalite Spinner

Les spinners fournissent un retour visuel pendant les operations longues dans vos commandes personnalisees. Ils affichent un indicateur anime accompagne d'un message pendant que votre commande execute des taches asynchrones, ameliorant l'experience utilisateur en montrant la progression et le statut.

- [Utiliser avec spinner](#using-with-spinner)
- [Controle manuel du spinner](#manual-spinner-control)
- [Exemples](#spinner-examples)

<div id="using-with-spinner"></div>

## Utiliser avec spinner

La methode `withSpinner` vous permet d'envelopper une tache asynchrone avec une animation de spinner qui demarre automatiquement lorsque la tache commence et s'arrete lorsqu'elle se termine ou echoue :

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parametres :**
- `task` : La fonction asynchrone a executer
- `message` : Texte a afficher pendant que le spinner tourne
- `successMessage` : Message optionnel a afficher en cas de succes
- `errorMessage` : Message optionnel a afficher en cas d'echec

**Retourne :** Le resultat de la fonction de tache

**Exemple :**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Run a task with a spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Long-running task (e.g., analyzing project files)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Continue with the results
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Controle manuel du spinner

Pour des scenarios plus complexes ou vous devez controler manuellement l'etat du spinner, vous pouvez creer une instance de spinner :

```dart
ConsoleSpinner createSpinner(String message)
```

**Parametres :**
- `message` : Texte a afficher pendant que le spinner tourne

**Retourne :** Une instance `ConsoleSpinner` que vous pouvez controler manuellement

**Exemple avec controle manuel :**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a spinner instance
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // First task
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Second task
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Third task
    await runProcess('./deploy.sh', silent: true);

    // Complete successfully
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Handle failure
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Exemples

### Tache simple avec Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Install dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Plusieurs operations consecutives

```dart
@override
Future<void> handle(CommandResult result) async {
  // First operation with spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Second operation with spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Third operation with spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Workflow complexe avec controle manuel

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Run multiple steps with status updates
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Complete the process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

L'utilisation de spinners dans vos commandes personnalisees fournit un retour visuel clair aux utilisateurs pendant les operations longues, creant une experience en ligne de commande plus polie et professionnelle.

<div id="custom-command-helper-get-string"></div>

### Obtenir une valeur string des options

```dart
String getString(String name, {String defaultValue = ''})
```

**Parametres :**

- `name` : Le nom de l'option a recuperer
- `defaultValue` : Valeur par defaut optionnelle si l'option n'est pas fournie

**Retourne :** La valeur de l'option sous forme de string

**Exemple :**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Obtenir une valeur booleenne des options

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parametres :**
- `name` : Le nom de l'option a recuperer
- `defaultValue` : Valeur par defaut optionnelle si l'option n'est pas fournie

**Retourne :** La valeur de l'option sous forme de booleen


**Exemple :**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Obtenir une valeur entiere des options

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parametres :**
- `name` : Le nom de l'option a recuperer
- `defaultValue` : Valeur par defaut optionnelle si l'option n'est pas fournie

**Retourne :** La valeur de l'option sous forme d'entier

**Exemple :**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Mettre en pause pour une duree specifiee

```dart
void sleep(int seconds)
```

**Parametres :**
- `seconds` : Le nombre de secondes de pause

**Retourne :** Rien

**Exemple :**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Formatage de sortie

Au-dela des methodes basiques `info`, `error`, `success` et `warning`, `NyCustomCommand` fournit des helpers de sortie supplementaires :

```dart
@override
Future<void> handle(CommandResult result) async {
  // Print plain text (no color)
  line('Processing your request...');

  // Print blank lines
  newLine();       // one blank line
  newLine(3);      // three blank lines

  // Print a muted comment (gray text)
  comment('This is a background note');

  // Print a prominent alert box
  alert('Important: Please read carefully');

  // Ask is an alias for prompt
  final name = ask('What is your name?');

  // Hidden input for sensitive data (e.g., passwords, API keys)
  final apiKey = promptSecret('Enter your API key:');

  // Abort the command with an error message and exit code
  if (name.isEmpty) {
    abort('Name is required');  // exits with code 1
  }
}
```

| Methode | Description |
|--------|-------------|
| `line(String message)` | Afficher du texte brut sans couleur |
| `newLine([int count = 1])` | Afficher des lignes vides |
| `comment(String message)` | Afficher du texte attenue/gris |
| `alert(String message)` | Afficher une boite d'alerte proeminente |
| `ask(String question, {String defaultValue})` | Alias pour `prompt` |
| `promptSecret(String question)` | Saisie masquee pour les donnees sensibles |
| `abort([String? message, int exitCode = 1])` | Quitter la commande avec une erreur |

<div id="file-system-helpers"></div>

## Helpers du systeme de fichiers

`NyCustomCommand` inclut des helpers de systeme de fichiers integres pour que vous n'ayez pas a importer manuellement `dart:io` pour les operations courantes.

### Lecture et ecriture de fichiers

```dart
@override
Future<void> handle(CommandResult result) async {
  // Check if a file exists
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Check if a directory exists
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Read a file (async)
  String content = await readFile('pubspec.yaml');

  // Read a file (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // Write to a file (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Write to a file (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Append content to a file
  await appendFile('log.txt', 'New log entry\n');

  // Ensure a directory exists (creates it if missing)
  await ensureDirectory('lib/generated');

  // Delete a file
  await deleteFile('lib/generated/output.dart');

  // Copy a file
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Methode | Description |
|--------|-------------|
| `fileExists(String path)` | Retourne `true` si le fichier existe |
| `directoryExists(String path)` | Retourne `true` si le repertoire existe |
| `readFile(String path)` | Lire un fichier comme string (async) |
| `readFileSync(String path)` | Lire un fichier comme string (sync) |
| `writeFile(String path, String content)` | Ecrire du contenu dans un fichier (async) |
| `writeFileSync(String path, String content)` | Ecrire du contenu dans un fichier (sync) |
| `appendFile(String path, String content)` | Ajouter du contenu a un fichier |
| `ensureDirectory(String path)` | Creer le repertoire s'il n'existe pas |
| `deleteFile(String path)` | Supprimer un fichier |
| `copyFile(String source, String destination)` | Copier un fichier |

<div id="json-yaml-helpers"></div>

## Helpers JSON et YAML

Lire et ecrire des fichiers JSON et YAML avec les helpers integres.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Read a JSON file as a Map
  Map<String, dynamic> config = await readJson('config.json');

  // Read a JSON file as a List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Write data to a JSON file (pretty printed by default)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Write compact JSON
  await writeJson('output.json', data, pretty: false);

  // Append an item to a JSON array file
  // If the file contains [{"name": "a"}], this adds to that array
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // prevents duplicates by this key
  );

  // Read a YAML file as a Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Methode | Description |
|--------|-------------|
| `readJson(String path)` | Lire un fichier JSON comme `Map<String, dynamic>` |
| `readJsonArray(String path)` | Lire un fichier JSON comme `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Ecrire des donnees en JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Ajouter a un fichier tableau JSON |
| `readYaml(String path)` | Lire un fichier YAML comme `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Helpers de conversion de casse

Convertir des strings entre les conventions de nommage sans importer le package `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Methode | Format de sortie | Exemple |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helpers de chemins projet

Getters pour les repertoires de projet {{ config('app.name') }} standard. Ceux-ci retournent des chemins relatifs a la racine du projet.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Build a custom path relative to the project root
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Propriete | Chemin |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Resoudre un chemin relatif dans le projet |

<div id="platform-helpers"></div>

## Helpers de plateforme

Verifier la plateforme et acceder aux variables d'environnement.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Platform checks
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Current working directory
  info('Working in: $workingDirectory');

  // Read system environment variables
  String home = env('HOME', '/default/path');
}
```

| Propriete / Methode | Description |
|-------------------|-------------|
| `isWindows` | `true` si execute sur Windows |
| `isMacOS` | `true` si execute sur macOS |
| `isLinux` | `true` si execute sur Linux |
| `workingDirectory` | Chemin du repertoire de travail actuel |
| `env(String key, [String defaultValue = ''])` | Lire une variable d'environnement systeme |

<div id="dart-flutter-commands"></div>

## Commandes Dart et Flutter

Executer les commandes CLI courantes Dart et Flutter comme methodes d'aide. Chacune retourne le code de sortie du processus.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Format a Dart file or directory
  await dartFormat('lib/app/models/user.dart');

  // Run dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Run flutter pub get
  await flutterPubGet();

  // Run flutter clean
  await flutterClean();

  // Build for a target with additional args
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Run flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // specific directory
}
```

| Methode | Description |
|--------|-------------|
| `dartFormat(String path)` | Executer `dart format` sur un fichier ou repertoire |
| `dartAnalyze([String? path])` | Executer `dart analyze` |
| `flutterPubGet()` | Executer `flutter pub get` |
| `flutterClean()` | Executer `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Executer `flutter build <target>` |
| `flutterTest([String? path])` | Executer `flutter test` |

<div id="dart-file-manipulation"></div>

## Manipulation de fichiers Dart

Helpers pour editer programmatiquement des fichiers Dart, utiles lors de la creation d'outils de scaffolding.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Add an import statement to a Dart file (avoids duplicates)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Insert code before the last closing brace in a file
  // Useful for adding entries to registration maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Check if a file contains a specific string
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Check if a file matches a regex pattern
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Methode | Description |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Ajouter un import a un fichier Dart (ignore si deja present) |
| `insertBeforeClosingBrace(String filePath, String code)` | Inserer du code avant la derniere `}` dans un fichier |
| `fileContains(String filePath, String identifier)` | Verifier si un fichier contient une string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verifier si un fichier correspond a un pattern |

<div id="directory-helpers"></div>

## Helpers de repertoires

Helpers pour travailler avec des repertoires et trouver des fichiers.

```dart
@override
Future<void> handle(CommandResult result) async {
  // List directory contents
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // List recursively
  var allEntities = listDirectory('lib/', recursive: true);

  // Find files matching criteria
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Find files by name pattern
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Delete a directory recursively
  await deleteDirectory('build/');

  // Copy a directory (recursive)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Methode | Description |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Lister le contenu d'un repertoire |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Trouver des fichiers correspondant aux criteres |
| `deleteDirectory(String path)` | Supprimer un repertoire recursivement |
| `copyDirectory(String source, String destination)` | Copier un repertoire recursivement |

<div id="validation-helpers"></div>

## Helpers de validation

Helpers pour valider et nettoyer les saisies utilisateur pour la generation de code.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validate a Dart identifier
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Require a non-empty first argument
  String name = requireArgument(result, message: 'Please provide a name');

  // Clean a class name (PascalCase, remove suffixes)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Returns: 'User'

  // Clean a file name (snake_case with extension)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Returns: 'user_model.dart'
}
```

| Methode | Description |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Valider un nom d'identifiant Dart |
| `requireArgument(CommandResult result, {String? message})` | Exiger un premier argument non vide ou abandonner |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Nettoyer et mettre en PascalCase un nom de classe |
| `cleanFileName(String name, {String extension = '.dart'})` | Nettoyer et mettre en snake_case un nom de fichier |

<div id="file-scaffolding"></div>

## Scaffolding de fichiers

Creer un ou plusieurs fichiers avec du contenu en utilisant le systeme de scaffolding.

### Fichier unique

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // don't overwrite if exists
    successMessage: 'AuthService created',
  );
}
```

### Fichiers multiples

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

La classe `ScaffoldFile` accepte :

| Propriete | Type | Description |
|----------|------|-------------|
| `path` | `String` | Chemin du fichier a creer |
| `content` | `String` | Contenu du fichier |
| `successMessage` | `String?` | Message affiche en cas de succes |

<div id="task-runner"></div>

## Executeur de taches

Executer une serie de taches nommees avec une sortie de statut automatique.

### Executeur de taches basique

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // stop pipeline if this fails (default)
    ),
  ]);
}
```

### Executeur de taches avec Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

La classe `CommandTask` accepte :

| Propriete | Type | Defaut | Description |
|----------|------|---------|-------------|
| `name` | `String` | requis | Nom de la tache affiche dans la sortie |
| `action` | `Future<void> Function()` | requis | Fonction asynchrone a executer |
| `stopOnError` | `bool` | `true` | Arreter les taches restantes en cas d'echec |

<div id="table-output"></div>

## Sortie en tableau

Afficher des tableaux ASCII formates dans la console.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Sortie :

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Barre de progression

Afficher une barre de progression pour les operations avec un nombre d'elements connu.

### Barre de progression manuelle

```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a progress bar for 100 items
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // increment by 1
  }

  progress.complete('All files processed');
}
```

### Traiter des elements avec progression

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Process items with automatic progress tracking
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // process each file
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Progression synchrone

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // synchronous processing
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

La classe `ConsoleProgressBar` fournit :

| Methode | Description |
|--------|-------------|
| `start()` | Demarrer la barre de progression |
| `tick([int amount = 1])` | Incrementer la progression |
| `update(int value)` | Definir la progression a une valeur specifique |
| `updateMessage(String newMessage)` | Changer le message affiche |
| `complete([String? completionMessage])` | Terminer avec un message optionnel |
| `stop()` | Arreter sans terminer |
| `current` | Valeur de progression actuelle (getter) |
| `percentage` | Progression en pourcentage (getter) |
