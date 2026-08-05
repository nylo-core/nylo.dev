# Installation

---

<a name="section-1"></a>
- [Installer](#install "Installer")
- [Lancer le projet](#running-the-project "Lancer le projet")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Trois commandes suffisent pour passer d'un dossier vide à une application Flutter opérationnelle, avec le routage, le réseau, les thèmes et la génération de code déjà configurés.

<x-doc-strip label="Avant de commencer" items="SDK Flutter installé, Dart 3" linkText="Configuration requise" linkHref="/fr/docs/{{ $version }}/requirements" />

## Installer

Exécutez ces commandes dans l'ordre. Chacune peut être relancée sans risque.

<x-doc-steps>
<x-doc-step number="1" title="Installer la CLI Nylo globalement">
Cela installe l'outil CLI {{ config('app.name') }} globalement sur votre systeme.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Creer un nouveau projet">
Cette commande clone le template {{ config('app.name') }}, configure le projet avec le nom de votre application et installe automatiquement toutes les dependances.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Configurer l'alias Metro">
Cela configure la commande `metro` pour votre projet, vous permettant d'utiliser les commandes Metro CLI sans la syntaxe complete `dart run`.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Ce que vous obtenez" items="Routage et navigation pre-configures
Boilerplate de service API
Configuration du theme et de la localisation
Metro CLI pour la generation de code" />


<div id="running-the-project"></div>

## Lancer le projet

Les projets {{ config('app.name') }} s'executent comme toute application Flutter standard.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Si la compilation reussit, l'application affichera l'ecran d'accueil par defaut de {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Ouvrez le dossier du projet, choisissez un appareil dans le sélecteur de cible, puis appuyez sur **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Documentation Flutter : exécution et débogage ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Ouvrez le dossier du projet, puis exécutez **Debug: Start Without Debugging** depuis la palette de commandes.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Documentation Flutter : exécuter sans point d'arrêt ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro génère les fichiers du projet pour vous. Lancez-le sans argument pour afficher le menu, ou appelez directement une commande.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Référence des commandes

Chaque commande reçoit un nom, par exemple `metro make:page settings_page`

<x-doc-commands title="Commandes de widgets" rows="
metro make:page | Creer une nouvelle page
metro make:stateful_widget | Creer un widget avec etat
metro make:stateless_widget | Creer un widget sans etat
metro make:state_managed_widget | Creer un widget a etat gere
metro make:navigation_hub | Creer un hub de navigation (navigation inferieure)
metro make:journey_widget | Creer un widget de parcours pour le hub de navigation
metro make:bottom_sheet_modal | Creer une feuille modale inferieure
metro make:button | Creer un widget bouton personnalise
metro make:form | Creer un formulaire avec validation
" />

<x-doc-commands title="Commandes utilitaires" rows="
metro make:model | Creer une classe de modele
metro make:provider | Creer un provider
metro make:api_service | Creer un service API
metro make:controller | Creer un controleur
metro make:event | Creer un evenement
metro make:route_guard | Creer un garde de route
metro make:config | Creer un fichier de configuration
metro make:interceptor | Creer un intercepteur reseau
metro make:command | Creer une commande Metro personnalisee
metro make:env | Generer la configuration d'environnement a partir de .env
" />

### Exemples d'utilisation

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
