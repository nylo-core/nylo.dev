# Installation

---

<a name="section-1"></a>
- [Installer](#install "Installer")
- [Lancer le projet](#running-the-project "Lancer le projet")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Installer

### 1. Installer nylo_installer globalement

``` bash
dart pub global activate nylo_installer
```

Cela installe l'outil CLI {{ config('app.name') }} globalement sur votre systeme.

### 2. Creer un nouveau projet

``` bash
nylo new my_app
```

Cette commande clone le template {{ config('app.name') }}, configure le projet avec le nom de votre application et installe automatiquement toutes les dependances.

### 3. Configurer l'alias Metro CLI

``` bash
cd my_app
nylo init
```

Cela configure la commande `metro` pour votre projet, vous permettant d'utiliser les commandes Metro CLI sans la syntaxe complete `dart run`.

Apres l'installation, vous disposerez d'une structure de projet Flutter complete avec :
- Routage et navigation pre-configures
- Boilerplate de service API
- Configuration du theme et de la localisation
- Metro CLI pour la generation de code


<div id="running-the-project"></div>

## Lancer le projet

Les projets {{ config('app.name') }} s'executent comme toute application Flutter standard.

### En utilisant le terminal

``` bash
flutter run
```

### En utilisant un IDE

- **Android Studio** : <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Execution et debogage</a>
- **VS Code** : <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Lancer l'application sans points d'arret</a>

Si la compilation reussit, l'application affichera l'ecran d'accueil par defaut de {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} inclut un outil CLI appele **Metro** pour generer les fichiers du projet.

### Executer Metro

``` bash
metro
```

Cela affiche le menu Metro :

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
```

### Reference des commandes Metro

| Commande | Description |
|---------|-------------|
| `make:page` | Creer une nouvelle page |
| `make:stateful_widget` | Creer un widget avec etat |
| `make:stateless_widget` | Creer un widget sans etat |
| `make:state_managed_widget` | Creer un widget a etat gere |
| `make:navigation_hub` | Creer un hub de navigation (navigation inferieure) |
| `make:journey_widget` | Creer un widget de parcours pour le hub de navigation |
| `make:bottom_sheet_modal` | Creer une feuille modale inferieure |
| `make:button` | Creer un widget bouton personnalise |
| `make:form` | Creer un formulaire avec validation |
| `make:model` | Creer une classe de modele |
| `make:provider` | Creer un provider |
| `make:api_service` | Creer un service API |
| `make:controller` | Creer un controleur |
| `make:event` | Creer un evenement |
| `make:theme` | Creer un theme |
| `make:route_guard` | Creer un garde de route |
| `make:config` | Creer un fichier de configuration |
| `make:interceptor` | Creer un intercepteur reseau |
| `make:command` | Creer une commande Metro personnalisee |
| `make:env` | Generer la configuration d'environnement a partir de .env |

### Exemples d'utilisation

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
