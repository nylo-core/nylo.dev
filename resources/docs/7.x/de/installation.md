# Installation

---

<a name="section-1"></a>
- [Installieren](#install "Installieren")
- [Projekt ausführen](#running-the-project "Projekt ausführen")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Installieren

### 1. nylo_installer global installieren

``` bash
dart pub global activate nylo_installer
```

Dies installiert das {{ config('app.name') }} CLI-Tool global auf Ihrem System.

### 2. Ein neues Projekt erstellen

``` bash
nylo new my_app
```

Dieser Befehl klont das {{ config('app.name') }}-Template, konfiguriert das Projekt mit Ihrem App-Namen und installiert automatisch alle Abhängigkeiten.

### 3. Metro CLI-Alias einrichten

``` bash
cd my_app
nylo init
```

Dies konfiguriert den `metro`-Befehl für Ihr Projekt, sodass Sie Metro CLI-Befehle ohne die vollständige `dart run`-Syntax verwenden können.

Nach der Installation haben Sie eine vollständige Flutter-Projektstruktur mit:
- Vorkonfiguriertem Routing und Navigation
- API-Service-Boilerplate
- Theme- und Lokalisierungseinrichtung
- Metro CLI für Code-Generierung


<div id="running-the-project"></div>

## Projekt ausführen

{{ config('app.name') }}-Projekte laufen wie jede Standard-Flutter-App.

### Über das Terminal

``` bash
flutter run
```

### Über eine IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Running and debugging</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Run app without breakpoints</a>

Wenn der Build erfolgreich ist, zeigt die App den Standard-Startbildschirm von {{ config('app.name') }} an.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} enthält ein CLI-Tool namens **Metro** zum Generieren von Projektdateien.

### Metro ausführen

``` bash
metro
```

Dies zeigt das Metro-Menü an:

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

### Metro-Befehlsreferenz

| Befehl | Beschreibung |
|---------|-------------|
| `make:page` | Neue Seite erstellen |
| `make:stateful_widget` | Stateful Widget erstellen |
| `make:stateless_widget` | Stateless Widget erstellen |
| `make:state_managed_widget` | State-Managed Widget erstellen |
| `make:navigation_hub` | Navigation Hub erstellen (untere Navigation) |
| `make:journey_widget` | Journey Widget für Navigation Hub erstellen |
| `make:bottom_sheet_modal` | Bottom Sheet Modal erstellen |
| `make:button` | Benutzerdefinierten Button-Widget erstellen |
| `make:form` | Formular mit Validierung erstellen |
| `make:model` | Model-Klasse erstellen |
| `make:provider` | Provider erstellen |
| `make:api_service` | API-Service erstellen |
| `make:controller` | Controller erstellen |
| `make:event` | Event erstellen |
| `make:theme` | Theme erstellen |
| `make:route_guard` | Route Guard erstellen |
| `make:config` | Konfigurationsdatei erstellen |
| `make:interceptor` | Netzwerk-Interceptor erstellen |
| `make:command` | Benutzerdefinierten Metro-Befehl erstellen |
| `make:env` | Umgebungskonfiguration aus .env generieren |

### Beispielverwendung

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
