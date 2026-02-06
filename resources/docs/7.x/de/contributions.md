# Beitragen zu {{ config('app.name') }}

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung zum Beitragen")
- [Erste Schritte](#getting-started "Erste Schritte mit Beiträgen")
- [Entwicklungsumgebung](#development-environment "Entwicklungsumgebung einrichten")
- [Entwicklungsrichtlinien](#development-guidelines "Entwicklungsrichtlinien")
- [Änderungen einreichen](#submitting-changes "Änderungen einreichen")
- [Probleme melden](#reporting-issues "Probleme melden")


<div id="introduction"></div>

## Einleitung

Vielen Dank, dass Sie einen Beitrag zu {{ config('app.name') }} in Betracht ziehen!

Dieser Leitfaden hilft Ihnen zu verstehen, wie Sie zum Micro-Framework beitragen können. Ob Sie Fehler beheben, Funktionen hinzufügen oder die Dokumentation verbessern -- Ihre Beiträge sind wertvoll für die {{ config('app.name') }}-Community.

{{ config('app.name') }} ist in drei Repositories aufgeteilt:

| Repository | Zweck |
|------------|-------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | Die Boilerplate-Anwendung |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Kern-Framework-Klassen (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Support-Bibliothek mit Widgets, Helfern, Hilfsmitteln (nylo_support) |

<div id="getting-started"></div>

## Erste Schritte

### Repositories forken

Forken Sie die Repositories, zu denen Sie beitragen möchten:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Nylo Boilerplate forken</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Framework forken</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Support forken</a>

### Forks klonen

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Entwicklungsumgebung

### Voraussetzungen

Stellen Sie sicher, dass Folgendes installiert ist:

| Voraussetzung | Mindestversion |
|---------------|----------------|
| Flutter | 3.24.0 oder höher |
| Dart SDK | 3.10.7 oder höher |

### Lokale Pakete verknüpfen

Öffnen Sie das Nylo-Boilerplate in Ihrem Editor und fügen Sie Dependency-Overrides hinzu, um Ihre lokalen Framework- und Support-Repositories zu verwenden:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Führen Sie `flutter pub get` aus, um die Abhängigkeiten zu installieren.

Jetzt werden Änderungen, die Sie an den Framework- oder Support-Repositories vornehmen, im Nylo-Boilerplate widergespiegelt.

### Änderungen testen

Führen Sie die Boilerplate-App aus, um Ihre Änderungen zu testen:

``` bash
flutter run
```

Für Widget- oder Helfer-Änderungen sollten Sie Tests im entsprechenden Repository hinzufügen.

<div id="development-guidelines"></div>

## Entwicklungsrichtlinien

### Code-Stil

- Befolgen Sie den offiziellen <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart-Stilrichtlinien</a>
- Verwenden Sie aussagekräftige Variablen- und Funktionsnamen
- Schreiben Sie klare Kommentare für komplexe Logik
- Fügen Sie Dokumentation für öffentliche APIs hinzu
- Halten Sie den Code modular und wartbar

### Dokumentation

Beim Hinzufügen neuer Funktionen:

- Fügen Sie Dartdoc-Kommentare zu öffentlichen Klassen und Methoden hinzu
- Aktualisieren Sie bei Bedarf die relevanten Dokumentationsdateien
- Fügen Sie Codebeispiele in die Dokumentation ein

### Testen

Vor dem Einreichen von Änderungen:

- Testen Sie auf iOS- und Android-Geräten/Simulatoren
- Überprüfen Sie nach Möglichkeit die Abwärtskompatibilität
- Dokumentieren Sie Breaking Changes deutlich
- Führen Sie vorhandene Tests aus, um sicherzustellen, dass nichts kaputt ist

<div id="submitting-changes"></div>

## Änderungen einreichen

### Zuerst diskutieren

Bei neuen Funktionen ist es am besten, zuerst mit der Community zu diskutieren:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Branch erstellen

``` bash
git checkout -b feature/your-feature-name
```

Verwenden Sie beschreibende Branch-Namen:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Änderungen committen

``` bash
git add .
git commit -m "Add: Your feature description"
```

Verwenden Sie klare Commit-Nachrichten:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Pushen und Pull Request erstellen

``` bash
git push origin feature/your-feature-name
```

Erstellen Sie dann einen Pull Request auf GitHub.

### Pull-Request-Richtlinien

- Geben Sie eine klare Beschreibung Ihrer Änderungen an
- Verweisen Sie auf zugehörige Issues
- Fügen Sie Screenshots oder Codebeispiele bei, falls zutreffend
- Stellen Sie sicher, dass Ihr PR nur ein Anliegen behandelt
- Halten Sie Änderungen fokussiert und atomar

<div id="reporting-issues"></div>

## Probleme melden

### Vor dem Melden

1. Prüfen Sie, ob das Problem bereits auf GitHub existiert
2. Stellen Sie sicher, dass Sie die neueste Version verwenden
3. Versuchen Sie, das Problem in einem frischen Projekt zu reproduzieren

### Wo melden

Melden Sie Probleme im entsprechenden Repository:

- **Boilerplate-Probleme**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Framework-Probleme**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Support-Bibliothek-Probleme**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Issue-Vorlage

Geben Sie detaillierte Informationen an:

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

### Versionsinformationen erhalten

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
