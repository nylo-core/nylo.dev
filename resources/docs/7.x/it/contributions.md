# Contribuire a {{ config('app.name') }}

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione ai contributi")
- [Per Iniziare](#getting-started "Per iniziare con i contributi")
- [Ambiente di Sviluppo](#development-environment "Configurazione dell'ambiente di sviluppo")
- [Linee Guida per lo Sviluppo](#development-guidelines "Linee guida per lo sviluppo")
- [Inviare Modifiche](#submitting-changes "Come inviare modifiche")
- [Segnalare Problemi](#reporting-issues "Come segnalare problemi")


<div id="introduction"></div>

## Introduzione

Grazie per aver considerato di contribuire a {{ config('app.name') }}!

Questa guida ti aiuterà a capire come contribuire al micro-framework. Che tu stia correggendo bug, aggiungendo funzionalità o migliorando la documentazione, i tuoi contributi sono preziosi per la comunità di {{ config('app.name') }}.

{{ config('app.name') }} è suddiviso in tre repository:

| Repository | Scopo |
|------------|-------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | L'applicazione boilerplate |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Classi core del framework (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Libreria di supporto con widget, helper, utilità (nylo_support) |

<div id="getting-started"></div>

## Per Iniziare

### Esegui il Fork dei Repository

Esegui il fork dei repository a cui vuoi contribuire:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Clona i Tuoi Fork

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Ambiente di Sviluppo

### Requisiti

Assicurati di avere installato quanto segue:

| Requisito | Versione Minima |
|-----------|-----------------|
| Flutter | 3.24.0 o superiore |
| Dart SDK | 3.10.7 o superiore |

### Collegare i Pacchetti Locali

Apri il boilerplate Nylo nel tuo editor e aggiungi le dipendenze sostitutive per utilizzare i tuoi repository locali di framework e support:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Esegui `flutter pub get` per installare le dipendenze.

Ora le modifiche apportate ai repository framework o support saranno riflesse nel boilerplate Nylo.

### Testare le Modifiche

Esegui l'app boilerplate per testare le tue modifiche:

``` bash
flutter run
```

Per modifiche a widget o helper, considera di aggiungere test nel repository appropriato.

<div id="development-guidelines"></div>

## Linee Guida per lo Sviluppo

### Stile del Codice

- Segui la <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">guida di stile ufficiale di Dart</a>
- Usa nomi significativi per variabili e funzioni
- Scrivi commenti chiari per la logica complessa
- Includi documentazione per le API pubbliche
- Mantieni il codice modulare e mantenibile

### Documentazione

Quando aggiungi nuove funzionalità:

- Aggiungi commenti dartdoc alle classi e ai metodi pubblici
- Aggiorna i file di documentazione pertinenti se necessario
- Includi esempi di codice nella documentazione

### Test

Prima di inviare le modifiche:

- Testa sia su dispositivi/simulatori iOS che Android
- Verifica la retrocompatibilità dove possibile
- Documenta chiaramente eventuali modifiche incompatibili
- Esegui i test esistenti per assicurarti che nulla sia compromesso

<div id="submitting-changes"></div>

## Inviare Modifiche

### Discuti Prima

Per le nuove funzionalità, è meglio discutere prima con la comunità:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Crea un Branch

``` bash
git checkout -b feature/your-feature-name
```

Usa nomi di branch descrittivi:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Esegui il Commit delle Modifiche

``` bash
git add .
git commit -m "Add: Your feature description"
```

Usa messaggi di commit chiari:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push e Crea Pull Request

``` bash
git push origin feature/your-feature-name
```

Poi crea una pull request su GitHub.

### Linee Guida per le Pull Request

- Fornisci una descrizione chiara delle tue modifiche
- Fai riferimento alle issue correlate
- Includi screenshot o esempi di codice se applicabile
- Assicurati che la tua PR affronti un solo problema
- Mantieni le modifiche focalizzate e atomiche

<div id="reporting-issues"></div>

## Segnalare Problemi

### Prima di Segnalare

1. Controlla se il problema esiste già su GitHub
2. Assicurati di utilizzare l'ultima versione
3. Prova a riprodurre il problema in un progetto nuovo

### Dove Segnalare

Segnala i problemi nel repository appropriato:

- **Problemi del boilerplate**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Problemi del framework**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Problemi della libreria di supporto**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Template per le Issue

Fornisci informazioni dettagliate:

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

### Ottenere Informazioni sulla Versione

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
