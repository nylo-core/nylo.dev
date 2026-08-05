# Installation

---

<a name="section-1"></a>
- [Installieren](#install "Installieren")
- [Projekt ausführen](#running-the-project "Projekt ausführen")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Drei Befehle führen Sie von einem leeren Ordner zu einer laufenden Flutter-App, in der Routing, Networking, Themes und Codegenerierung bereits eingerichtet sind.

<x-doc-strip label="Bevor Sie beginnen" items="Flutter SDK installiert, Dart 3" linkText="Alle Voraussetzungen" linkHref="/de/docs/{{ $version }}/requirements" />

## Installieren

Führen Sie diese Befehle der Reihe nach aus. Jeder kann sicher erneut ausgeführt werden.

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI global installieren">
Dies installiert das {{ config('app.name') }} CLI-Tool global auf Ihrem System.

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
