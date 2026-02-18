# Contribuer a {{ config('app.name') }}

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Pour commencer](#getting-started "Pour commencer")
- [Environnement de developpement](#development-environment "Environnement de developpement")
- [Directives de developpement](#development-guidelines "Directives de developpement")
- [Soumettre des modifications](#submitting-changes "Soumettre des modifications")
- [Signaler des problemes](#reporting-issues "Signaler des problemes")


<div id="introduction"></div>

## Introduction

Merci d'envisager de contribuer a {{ config('app.name') }} !

Ce guide vous aidera a comprendre comment contribuer au micro-framework. Que vous corrigiez des bugs, ajoutiez des fonctionnalites ou amelioriez la documentation, vos contributions sont precieuses pour la communaute {{ config('app.name') }}.

{{ config('app.name') }} est divise en trois depots :

| Depot | Objectif |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | L'application modele |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Classes principales du framework (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Bibliotheque de support avec widgets, helpers, utilitaires (nylo_support) |

<div id="getting-started"></div>

## Pour commencer

### Forker les depots

Forkez les depots auxquels vous souhaitez contribuer :

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Forker le modele Nylo</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Forker le Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Forker le Support</a>

### Cloner vos forks

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Environnement de developpement

### Prerequis

Assurez-vous d'avoir les elements suivants installes :

| Prerequis | Version minimale |
|-------------|-----------------|
| Flutter | 3.24.0 ou superieur |
| Dart SDK | 3.10.7 ou superieur |

### Lier les paquets locaux

Ouvrez le modele Nylo dans votre editeur et ajoutez des surcharges de dependances pour utiliser vos depots locaux du framework et du support :

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Executez `flutter pub get` pour installer les dependances.

Les modifications que vous apportez aux depots du framework ou du support seront maintenant refletees dans le modele Nylo.

### Tester vos modifications

Lancez l'application modele pour tester vos modifications :

``` bash
flutter run
```

Pour les modifications de widgets ou de helpers, envisagez d'ajouter des tests dans le depot approprie.

<div id="development-guidelines"></div>

## Directives de developpement

### Style de code

- Suivez le <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">guide de style Dart</a> officiel
- Utilisez des noms de variables et de fonctions significatifs
- Ecrivez des commentaires clairs pour la logique complexe
- Incluez de la documentation pour les API publiques
- Gardez le code modulaire et maintenable

### Documentation

Lors de l'ajout de nouvelles fonctionnalites :

- Ajoutez des commentaires dartdoc aux classes et methodes publiques
- Mettez a jour les fichiers de documentation pertinents si necessaire
- Incluez des exemples de code dans la documentation

### Tests

Avant de soumettre des modifications :

- Testez sur les appareils/simulateurs iOS et Android
- Verifiez la retrocompatibilite dans la mesure du possible
- Documentez clairement tout changement incompatible
- Executez les tests existants pour vous assurer que rien n'est casse

<div id="submitting-changes"></div>

## Soumettre des modifications

### Discuter d'abord

Pour les nouvelles fonctionnalites, il est preferable d'en discuter d'abord avec la communaute :

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">Discussions GitHub</a>

### Creer une branche

``` bash
git checkout -b feature/your-feature-name
```

Utilisez des noms de branches descriptifs :
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Valider vos modifications

``` bash
git add .
git commit -m "Add: Your feature description"
```

Utilisez des messages de commit clairs :
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Pousser et creer une Pull Request

``` bash
git push origin feature/your-feature-name
```

Creez ensuite une pull request sur GitHub.

### Directives pour les Pull Requests

- Fournissez une description claire de vos modifications
- Referencez les issues associees
- Incluez des captures d'ecran ou des exemples de code le cas echeant
- Assurez-vous que votre PR ne traite qu'un seul sujet
- Gardez les modifications concentrees et atomiques

<div id="reporting-issues"></div>

## Signaler des problemes

### Avant de signaler

1. Verifiez si le probleme existe deja sur GitHub
2. Assurez-vous d'utiliser la derniere version
3. Essayez de reproduire le probleme dans un projet vierge

### Ou signaler

Signalez les problemes sur le depot approprie :

- **Problemes du modele** : <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Problemes du framework** : <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Problemes de la bibliotheque de support** : <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Modele de signalement

Fournissez des informations detaillees :

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### Obtenir les informations de version

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
