# Qu'est-ce que {{ config('app.name') }} ?

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Developpement d'applications
    - [Nouveau sur Flutter ?](#new-to-flutter "Nouveau sur Flutter ?")
    - [Maintenance et calendrier de publication](#maintenance-and-release-schedule "Maintenance et calendrier de publication")
- Credits
    - [Dependances du framework](#framework-dependencies "Dependances du framework")
    - [Contributeurs](#contributors "Contributeurs")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} est un micro-framework pour Flutter concu pour simplifier le developpement d'applications. Il fournit un modele structure avec des elements essentiels preconfigures afin que vous puissiez vous concentrer sur la creation des fonctionnalites de votre application plutot que sur la mise en place de l'infrastructure.

{{ config('app.name') }} inclut nativement :

- **Routage** - Gestion de routes simple et declarative avec gardes et liens profonds
- **Reseau** - Services API avec Dio, intercepteurs et transformation de reponses
- **Gestion d'etat** - Etat reactif avec NyState et mises a jour globales de l'etat
- **Localisation** - Support multilingue avec fichiers de traduction JSON
- **Themes** - Mode clair/sombre avec changement de theme
- **Stockage local** - Stockage securise avec Backpack et NyStorage
- **Formulaires** - Gestion de formulaires avec validation et types de champs
- **Notifications push** - Support des notifications locales et distantes
- **Outil CLI (Metro)** - Generation de pages, controleurs, modeles et plus encore

<div id="new-to-flutter"></div>

## Nouveau sur Flutter ?

Si vous debutez avec Flutter, commencez par les ressources officielles :

- <a href="https://flutter.dev" target="_BLANK">Documentation Flutter</a> - Guides complets et reference API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Chaine YouTube Flutter</a> - Tutoriels et mises a jour
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Recettes pratiques pour les taches courantes

Une fois que vous maitrisez les bases de Flutter, {{ config('app.name') }} vous semblera intuitif car il s'appuie sur les modeles standard de Flutter.


<div id="maintenance-and-release-schedule"></div>

## Maintenance et calendrier de publication

{{ config('app.name') }} suit le <a href="https://semver.org" target="_BLANK">Versionnage Semantique</a> :

- **Versions majeures** (7.x → 8.x) - Une fois par an pour les changements incompatibles
- **Versions mineures** (7.0 → 7.1) - Nouvelles fonctionnalites, retrocompatibles
- **Versions correctives** (7.0.0 → 7.0.1) - Corrections de bugs et ameliorations mineures

Les corrections de bugs et les correctifs de securite sont traites rapidement via les depots GitHub.


<div id="framework-dependencies"></div>

## Dependances du framework

{{ config('app.name') }} v7 est construit sur ces paquets open source :

### Dependances principales

| Paquet | Objectif |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | Client HTTP pour les requetes API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Stockage local securise |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internationalisation et formatage |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Extensions reactives pour les flux |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Egalite par valeur pour les objets |

### Interface utilisateur et widgets

| Paquet | Objectif |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Effets de chargement en squelette |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Notifications toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Fonctionnalite de rafraichissement par glissement |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Dispositions en grille decalees |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Champs de selection de date |

### Notifications et connectivite

| Paquet | Objectif |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Notifications push locales |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Etat de la connectivite reseau |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Badges d'icone d'application |

### Utilitaires

| Paquet | Objectif |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Ouvrir des URL et des applications |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Conversion de casse de chaines |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Generation d'UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Chemins du systeme de fichiers |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Masquage de saisie |


<div id="contributors"></div>

## Contributeurs

Merci a tous ceux qui ont contribue a {{ config('app.name') }} ! Si vous avez contribue, contactez-nous via <a href="mailto:support@nylo.dev">support@nylo.dev</a> pour etre ajoute ici.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Createur)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
