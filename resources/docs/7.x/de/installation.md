# Installation

---

<a name="section-1"></a>
- [Installieren](#install "Installieren")
- [Projekt ausführen](#running-the-project "Projekt ausführen")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [Projekt bereinigen](#nylo-clean "Projekt bereinigen")
  - [iOS-Pods auffrischen](#nylo-ios-pod-refresh "iOS-Pods auffrischen")
  - [Metro ohne Alias ausführen](#nylo-metro "Metro ohne Alias ausführen")
  - [CLI aktualisieren](#nylo-self-update "CLI aktualisieren")

<div id="install"></div>

Drei Befehle führen Sie von einem leeren Ordner zu einer laufenden Flutter-App, in der Routing, Networking, Themes und Codegenerierung bereits eingerichtet sind.

<x-doc-strip label="Bevor Sie beginnen" items="Flutter SDK installiert, Dart 3" linkText="Alle Voraussetzungen" linkHref="/de/docs/{{ $version }}/requirements" />

## Installieren

Führen Sie diese Befehle der Reihe nach aus. Jeder kann sicher erneut ausgeführt werden.

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI global installieren">
Stellt den Befehl `nylo` auf Ihrem System bereit.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Ein neues Projekt erstellen">
Dieser Befehl klont das {{ config('app.name') }}-Template, konfiguriert das Projekt mit Ihrem App-Namen und installiert automatisch alle Abhängigkeiten.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Metro-Alias einrichten">
Dies konfiguriert den `metro`-Befehl für Ihr Projekt, sodass Sie Metro CLI-Befehle ohne die vollständige `dart run`-Syntax verwenden können.

``` bash
cd my_app
nylo init
```

Starten Sie anschließend Ihr Terminal neu, oder führen Sie den `source`-Befehl aus, den `nylo init` ausgibt.
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Das erhalten Sie" items="Vorkonfiguriertes Routing und Navigation
API-Service-Boilerplate
Theme- und Lokalisierungseinrichtung
Metro CLI für Code-Generierung" />


<div id="running-the-project"></div>

## Projekt ausführen

{{ config('app.name') }}-Projekte laufen wie jede Standard-Flutter-App.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Wenn der Build erfolgreich ist, zeigt die App den Standard-Startbildschirm von {{ config('app.name') }} an.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Öffnen Sie den Projektordner, wählen Sie im Zielgerät-Selektor ein Gerät aus und klicken Sie dann auf **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter-Dokumentation: Ausführen und Debuggen ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Öffnen Sie den Projektordner und führen Sie dann **Debug: Start Without Debugging** über die Befehlspalette aus.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter-Dokumentation: Ohne Haltepunkte ausführen ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro generiert Projektdateien für Sie. Starten Sie es ohne Argumente, um das Menü anzuzeigen, oder rufen Sie direkt einen Befehl auf.

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

### Befehlsreferenz

Jeder Befehl erwartet einen Namen, z. B. `metro make:page settings_page`

<x-doc-commands title="Widget-Befehle" rows="
metro make:page | Neue Seite erstellen
metro make:stateful_widget | Stateful Widget erstellen
metro make:stateless_widget | Stateless Widget erstellen
metro make:state_managed_widget | State-Managed Widget erstellen
metro make:navigation_hub | Navigation Hub erstellen (untere Navigation)
metro make:journey_widget | Journey Widget für Navigation Hub erstellen
metro make:bottom_sheet_modal | Bottom Sheet Modal erstellen
metro make:button | Benutzerdefinierten Button-Widget erstellen
metro make:form | Formular mit Validierung erstellen
" />

<x-doc-commands title="Hilfsbefehle" rows="
metro make:model | Model-Klasse erstellen
metro make:provider | Provider erstellen
metro make:api_service | API-Service erstellen
metro make:controller | Controller erstellen
metro make:event | Event erstellen
metro make:route_guard | Route Guard erstellen
metro make:config | Konfigurationsdatei erstellen
metro make:interceptor | Netzwerk-Interceptor erstellen
metro make:command | Benutzerdefinierten Metro-Befehl erstellen
metro make:env | Umgebungskonfiguration aus .env generieren
" />

### Beispielverwendung

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: strip label "Requires" has no established translation in this locale -->
<x-doc-strip label="Voraussetzung" items="nylo_installer 1.8.0+" linkText="CLI aktualisieren" linkHref="#nylo-self-update" />

Der Befehl `nylo` aus Schritt 1 kann mehr, als nur Projekte zu erstellen. Führen Sie ihn aus Ihrem Projektverzeichnis aus, um Builds zu bereinigen, iOS-Pods aufzufrischen, Tests auszuführen und Ihre Übersetzungen zu überprüfen.

<x-doc-commands title="Projektbefehle" rows="
nylo new | Ein neues Projekt erstellen
nylo init | Den Metro-Alias in Ihrer Shell einrichten
nylo clean | flutter clean und flutter pub get ausführen
nylo ios:pod-refresh | Ihre iOS-Pods neu installieren
nylo metro | Einen Metro-Befehl ohne den Alias ausführen
" />

<x-doc-commands title="Tests und Übersetzungen" rows="
nylo test | Ihre Tests formatieren und ausführen
nylo locale:find-untranslated | Text finden, der nicht in .tr() eingeschlossen ist
nylo locale:check-missing-keys | Fehlende Schlüssel in Ihren Sprachdateien finden
" />

<x-doc-commands title="Die CLI selbst" rows="
nylo self-update | nylo auf die neueste Version aktualisieren
nylo --version | Die installierte Version anzeigen
" />

Befehle mit Optionen listen diese mit `-h` auf, zum Beispiel `nylo test -h`. Tests werden unter [Tests ausführen](/docs/{{ $version }}/testing#running-tests) behandelt, die `locale:`-Befehle unter [Übersetzungen prüfen](/docs/{{ $version }}/localization#auditing-translations).

<div id="nylo-clean"></div>

### Projekt bereinigen

`nylo clean` führt `flutter clean` und anschließend `flutter pub get` aus. Fügen Sie ein Plattform-Flag hinzu, wenn eine normale Bereinigung nicht ausreicht:

``` bash
# flutter clean, dann flutter pub get
nylo clean

# Entfernt zusätzlich ios/Pods, ios/.symlinks und ios/Podfile.lock und führt dann pod install --repo-update aus
nylo clean --ios

# Führt zusätzlich gradlew clean in android/ aus
nylo clean --android

# Beides zusammen
nylo clean --all
```

Wenn ein Projekt keinen Ordner `ios/` oder `android/` hat, wird diese Plattform mit einer Warnung übersprungen. `pod install` wird nur unter macOS ausgeführt.

<div id="nylo-ios-pod-refresh"></div>

### iOS-Pods auffrischen

Wenn CocoaPods nicht mehr synchron ist, löscht `nylo ios:pod-refresh` `ios/Pods`, `ios/.symlinks` und `ios/Podfile.lock` und führt dann `pod install --repo-update` im Ordner `ios/` aus:

``` bash
nylo ios:pod-refresh
```

Führen Sie ihn unter macOS im Stammverzeichnis Ihres Projekts aus. Im Gegensatz zu `nylo clean --ios` führt er weder `flutter clean` noch `flutter pub get` aus.

<div id="nylo-metro"></div>

### Metro ohne Alias ausführen

`nylo metro` übergibt alles, was danach folgt, an Metro (`dart run nylo_framework:main`). Verwenden Sie es, bevor Sie `nylo init` ausgeführt haben, oder unter Windows, wo `nylo init` den Alias `metro` nicht für Sie einrichten kann:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### CLI aktualisieren

``` bash
nylo self-update
```

Dies prüft pub.dev und installiert `nylo_installer` neu, falls eine neuere Version verfügbar ist. Nachdem ein Befehl abgeschlossen ist, teilt Ihnen `nylo` außerdem mit, wenn ein Update verfügbar ist. Es bleibt still, wenn die Ausgabe nicht an ein Terminal geht, sodass der Hinweis nie in CI-Logs oder weitergeleiteten Berichten landet.

Um zu sehen, welche Version Sie installiert haben:

``` bash
nylo --version
```
