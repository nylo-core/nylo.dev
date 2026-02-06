# Metro CLI-Tool

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Installieren](#install "Metro-Alias fuer {{ config('app.name') }} installieren")
- Make-Befehle
  - [Controller erstellen](#make-controller "Einen neuen Controller erstellen")
  - [Model erstellen](#make-model "Ein neues Model erstellen")
  - [Seite erstellen](#make-page "Eine neue Seite erstellen")
  - [Stateless Widget erstellen](#make-stateless-widget "Ein neues Stateless Widget erstellen")
  - [Stateful Widget erstellen](#make-stateful-widget "Ein neues Stateful Widget erstellen")
  - [Journey Widget erstellen](#make-journey-widget "Ein neues Journey Widget erstellen")
  - [API Service erstellen](#make-api-service "Einen neuen API Service erstellen")
  - [Event erstellen](#make-event "Ein neues Event erstellen")
  - [Provider erstellen](#make-provider "Einen neuen Provider erstellen")
  - [Theme erstellen](#make-theme "Ein neues Theme erstellen")
  - [Formulare erstellen](#make-forms "Ein neues Formular erstellen")
  - [Route Guard erstellen](#make-route-guard "Einen neuen Route Guard erstellen")
  - [Config-Datei erstellen](#make-config-file "Eine neue Config-Datei erstellen")
  - [Command erstellen](#make-command "Einen neuen Command erstellen")
  - [State Managed Widget erstellen](#make-state-managed-widget "Ein neues State Managed Widget erstellen")
  - [Navigation Hub erstellen](#make-navigation-hub "Einen neuen Navigation Hub erstellen")
  - [Bottom Sheet Modal erstellen](#make-bottom-sheet-modal "Ein neues Bottom Sheet Modal erstellen")
  - [Button erstellen](#make-button "Einen neuen Button erstellen")
  - [Interceptor erstellen](#make-interceptor "Einen neuen Interceptor erstellen")
  - [Env-Datei erstellen](#make-env-file "Eine neue Env-Datei erstellen")
  - [Key generieren](#make-key "APP_KEY generieren")
- App-Icons
  - [App-Icons erstellen](#build-app-icons "App-Icons mit Metro erstellen")
- Benutzerdefinierte Commands
  - [Benutzerdefinierte Commands erstellen](#creating-custom-commands "Benutzerdefinierte Commands erstellen")
  - [Benutzerdefinierte Commands ausfuehren](#running-custom-commands "Benutzerdefinierte Commands ausfuehren")
  - [Optionen zu Commands hinzufuegen](#adding-options-to-custom-commands "Optionen zu benutzerdefinierten Commands hinzufuegen")
  - [Flags zu Commands hinzufuegen](#adding-flags-to-custom-commands "Flags zu benutzerdefinierten Commands hinzufuegen")
  - [Hilfsmethoden](#custom-command-helper-methods "Hilfsmethoden fuer benutzerdefinierte Commands")
  - [Interaktive Eingabemethoden](#interactive-input-methods "Interaktive Eingabemethoden")
  - [Ausgabeformatierung](#output-formatting "Ausgabeformatierung")
  - [Dateisystem-Helfer](#file-system-helpers "Dateisystem-Helfer")
  - [JSON- und YAML-Helfer](#json-yaml-helpers "JSON- und YAML-Helfer")
  - [Gross-/Kleinschreibungs-Konvertierungshelfer](#case-conversion-helpers "Gross-/Kleinschreibungs-Konvertierungshelfer")
  - [Projektpfad-Helfer](#project-path-helpers "Projektpfad-Helfer")
  - [Plattform-Helfer](#platform-helpers "Plattform-Helfer")
  - [Dart- und Flutter-Befehle](#dart-flutter-commands "Dart- und Flutter-Befehle")
  - [Dart-Dateimanipulation](#dart-file-manipulation "Dart-Dateimanipulation")
  - [Verzeichnis-Helfer](#directory-helpers "Verzeichnis-Helfer")
  - [Validierungs-Helfer](#validation-helpers "Validierungs-Helfer")
  - [Datei-Scaffolding](#file-scaffolding "Datei-Scaffolding")
  - [Task-Runner](#task-runner "Task-Runner")
  - [Tabellenausgabe](#table-output "Tabellenausgabe")
  - [Fortschrittsbalken](#progress-bar "Fortschrittsbalken")


<div id="introduction"></div>

## Einleitung

Metro ist ein CLI-Tool, das unter der Haube des {{ config('app.name') }}-Frameworks arbeitet.
Es bietet viele hilfreiche Werkzeuge zur Beschleunigung der Entwicklung.

<div id="install"></div>

## Installieren

Wenn Sie ein neues Nylo-Projekt mit `nylo init` erstellen, wird der `metro`-Befehl automatisch fuer Ihr Terminal konfiguriert. Sie koennen ihn sofort in jedem Nylo-Projekt verwenden.

Fuehren Sie `metro` aus Ihrem Projektverzeichnis aus, um alle verfuegbaren Befehle zu sehen:

``` bash
metro
```

Sie sollten eine Ausgabe aehnlich der folgenden sehen.

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

## Controller erstellen

- [Einen neuen Controller erstellen](#making-a-new-controller "Einen neuen Controller mit Metro erstellen")
- [Controller erzwungen erstellen](#forcefully-make-a-controller "Einen Controller erzwungen mit Metro erstellen")
<div id="making-a-new-controller"></div>

### Einen neuen Controller erstellen

Sie koennen einen neuen Controller erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:controller profile_controller
```

Dies erstellt einen neuen Controller, falls er noch nicht im Verzeichnis `lib/app/controllers/` existiert.

<div id="forcefully-make-a-controller"></div>

### Controller erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Controller ueberschrieben, falls er bereits existiert.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Model erstellen

- [Ein neues Model erstellen](#making-a-new-model "Ein neues Model mit Metro erstellen")
- [Model aus JSON erstellen](#make-model-from-json "Ein neues Model aus JSON mit Metro erstellen")
- [Model erzwungen erstellen](#forcefully-make-a-model "Ein Model erzwungen mit Metro erstellen")
<div id="making-a-new-model"></div>

### Ein neues Model erstellen

Sie koennen ein neues Model erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:model product
```

Das neu erstellte Model wird in `lib/app/models/` abgelegt.

<div id="make-model-from-json"></div>

### Ein Model aus JSON erstellen

**Argumente:**

Mit dem `--json`- oder `-j`-Flag wird ein neues Model aus einer JSON-Nutzlast erstellt.

``` bash
metro make:model product --json
```

Dann koennen Sie Ihr JSON in das Terminal einfuegen und es wird ein Model fuer Sie generiert.

<div id="forcefully-make-a-model"></div>

### Model erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Model ueberschrieben, falls es bereits existiert.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Seite erstellen

- [Eine neue Seite erstellen](#making-a-new-page "Eine neue Seite mit Metro erstellen")
- [Eine Seite mit Controller erstellen](#create-a-page-with-a-controller "Eine neue Seite mit Controller mit Metro erstellen")
- [Eine Auth-Seite erstellen](#create-an-auth-page "Eine neue Auth-Seite mit Metro erstellen")
- [Eine initiale Seite erstellen](#create-an-initial-page "Eine neue initiale Seite mit Metro erstellen")
- [Seite erzwungen erstellen](#forcefully-make-a-page "Eine Seite erzwungen mit Metro erstellen")

<div id="making-a-new-page"></div>

### Eine neue Seite erstellen

Sie koennen eine neue Seite erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:page product_page
```

Dies erstellt eine neue Seite, falls sie noch nicht im Verzeichnis `lib/resources/pages/` existiert.

<div id="create-a-page-with-a-controller"></div>

### Eine Seite mit Controller erstellen

Sie koennen eine neue Seite mit einem Controller erstellen, indem Sie Folgendes im Terminal ausfuehren.

**Argumente:**

Mit dem `--controller`- oder `-c`-Flag wird eine neue Seite mit einem Controller erstellt.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Eine Auth-Seite erstellen

Sie koennen eine neue Auth-Seite erstellen, indem Sie Folgendes im Terminal ausfuehren.

**Argumente:**

Mit dem `--auth`- oder `-a`-Flag wird eine neue Auth-Seite erstellt.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Eine initiale Seite erstellen

Sie koennen eine neue initiale Seite erstellen, indem Sie Folgendes im Terminal ausfuehren.

**Argumente:**

Mit dem `--initial`- oder `-i`-Flag wird eine neue initiale Seite erstellt.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Seite erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird eine vorhandene Seite ueberschrieben, falls sie bereits existiert.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Stateless Widget erstellen

- [Ein neues Stateless Widget erstellen](#making-a-new-stateless-widget "Ein neues Stateless Widget mit Metro erstellen")
- [Stateless Widget erzwungen erstellen](#forcefully-make-a-stateless-widget "Ein Stateless Widget erzwungen mit Metro erstellen")
<div id="making-a-new-stateless-widget"></div>

### Ein neues Stateless Widget erstellen

Sie koennen ein neues Stateless Widget erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:stateless_widget product_rating_widget
```

Das obige erstellt ein neues Widget, falls es noch nicht im Verzeichnis `lib/resources/widgets/` existiert.

<div id="forcefully-make-a-stateless-widget"></div>

### Stateless Widget erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget ueberschrieben, falls es bereits existiert.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Stateful Widget erstellen

- [Ein neues Stateful Widget erstellen](#making-a-new-stateful-widget "Ein neues Stateful Widget mit Metro erstellen")
- [Stateful Widget erzwungen erstellen](#forcefully-make-a-stateful-widget "Ein Stateful Widget erzwungen mit Metro erstellen")

<div id="making-a-new-stateful-widget"></div>

### Ein neues Stateful Widget erstellen

Sie koennen ein neues Stateful Widget erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:stateful_widget product_rating_widget
```

Das obige erstellt ein neues Widget, falls es noch nicht im Verzeichnis `lib/resources/widgets/` existiert.

<div id="forcefully-make-a-stateful-widget"></div>

### Stateful Widget erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget ueberschrieben, falls es bereits existiert.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Journey Widget erstellen

- [Ein neues Journey Widget erstellen](#making-a-new-journey-widget "Ein neues Journey Widget mit Metro erstellen")
- [Journey Widget erzwungen erstellen](#forcefully-make-a-journey-widget "Ein Journey Widget erzwungen mit Metro erstellen")

<div id="making-a-new-journey-widget"></div>

### Ein neues Journey Widget erstellen

Sie koennen ein neues Journey Widget erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Das obige erstellt ein neues Widget, falls es noch nicht im Verzeichnis `lib/resources/widgets/` existiert.

Das `--parent`-Argument wird verwendet, um das uebergeordnete Widget anzugeben, zu dem das neue Journey Widget hinzugefuegt wird.

Beispiel

``` bash
metro make:navigation_hub onboarding
```

Fuegen Sie als Naechstes die neuen Journey Widgets hinzu.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Journey Widget erzwungen erstellen
**Argumente:**
Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget ueberschrieben, falls es bereits existiert.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## API Service erstellen

- [Einen neuen API Service erstellen](#making-a-new-api-service "Einen neuen API Service mit Metro erstellen")
- [Einen neuen API Service mit Model erstellen](#making-a-new-api-service-with-a-model "Einen neuen API Service mit Model mit Metro erstellen")
- [API Service erzwungen erstellen](#forcefully-make-an-api-service "Einen API Service erzwungen mit Metro erstellen")

<div id="making-a-new-api-service"></div>

### Einen neuen API Service erstellen

Sie koennen einen neuen API Service erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:api_service user_api_service
```

Der neu erstellte API Service wird in `lib/app/networking/` abgelegt.

<div id="making-a-new-api-service-with-a-model"></div>

### Einen neuen API Service mit Model erstellen

Sie koennen einen neuen API Service mit einem Model erstellen, indem Sie Folgendes im Terminal ausfuehren.

**Argumente:**

Mit der `--model`- oder `-m`-Option wird ein neuer API Service mit einem Model erstellt.

``` bash
metro make:api_service user --model="User"
```

Der neu erstellte API Service wird in `lib/app/networking/` abgelegt.

### API Service erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener API Service ueberschrieben, falls er bereits existiert.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Event erstellen

- [Ein neues Event erstellen](#making-a-new-event "Ein neues Event mit Metro erstellen")
- [Event erzwungen erstellen](#forcefully-make-an-event "Ein Event erzwungen mit Metro erstellen")

<div id="making-a-new-event"></div>

### Ein neues Event erstellen

Sie koennen ein neues Event erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:event login_event
```

Dies erstellt ein neues Event in `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Event erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Event ueberschrieben, falls es bereits existiert.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Provider erstellen

- [Einen neuen Provider erstellen](#making-a-new-provider "Einen neuen Provider mit Metro erstellen")
- [Provider erzwungen erstellen](#forcefully-make-a-provider "Einen Provider erzwungen mit Metro erstellen")

<div id="making-a-new-provider"></div>

### Einen neuen Provider erstellen

Erstellen Sie neue Provider in Ihrer Anwendung mit dem folgenden Befehl.

``` bash
metro make:provider firebase_provider
```

Der neu erstellte Provider wird in `lib/app/providers/` abgelegt.

<div id="forcefully-make-a-provider"></div>

### Provider erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Provider ueberschrieben, falls er bereits existiert.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Theme erstellen

- [Ein neues Theme erstellen](#making-a-new-theme "Ein neues Theme mit Metro erstellen")
- [Theme erzwungen erstellen](#forcefully-make-a-theme "Ein Theme erzwungen mit Metro erstellen")

<div id="making-a-new-theme"></div>

### Ein neues Theme erstellen

Sie koennen Themes erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:theme bright_theme
```

Dies erstellt ein neues Theme in `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Theme erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Theme ueberschrieben, falls es bereits existiert.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Formulare erstellen

- [Ein neues Formular erstellen](#making-a-new-form "Ein neues Formular mit Metro erstellen")
- [Formular erzwungen erstellen](#forcefully-make-a-form "Ein Formular erzwungen mit Metro erstellen")

<div id="making-a-new-form"></div>

### Ein neues Formular erstellen

Sie koennen ein neues Formular erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:form car_advert_form
```

Dies erstellt ein neues Formular in `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Formular erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Formular ueberschrieben, falls es bereits existiert.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Route Guard erstellen

- [Einen neuen Route Guard erstellen](#making-a-new-route-guard "Einen neuen Route Guard mit Metro erstellen")
- [Route Guard erzwungen erstellen](#forcefully-make-a-route-guard "Einen Route Guard erzwungen mit Metro erstellen")

<div id="making-a-new-route-guard"></div>

### Einen neuen Route Guard erstellen

Sie koennen einen Route Guard erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:route_guard premium_content
```

Dies erstellt einen neuen Route Guard in `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Route Guard erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Route Guard ueberschrieben, falls er bereits existiert.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Config-Datei erstellen

- [Eine neue Config-Datei erstellen](#making-a-new-config-file "Eine neue Config-Datei mit Metro erstellen")
- [Config-Datei erzwungen erstellen](#forcefully-make-a-config-file "Eine Config-Datei erzwungen mit Metro erstellen")

<div id="making-a-new-config-file"></div>

### Eine neue Config-Datei erstellen

Sie koennen eine neue Config-Datei erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:config shopping_settings
```

Dies erstellt eine neue Config-Datei in `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Config-Datei erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird eine vorhandene Config-Datei ueberschrieben, falls sie bereits existiert.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Command erstellen

- [Einen neuen Command erstellen](#making-a-new-command "Einen neuen Command mit Metro erstellen")
- [Command erzwungen erstellen](#forcefully-make-a-command "Einen Command erzwungen mit Metro erstellen")

<div id="making-a-new-command"></div>

### Einen neuen Command erstellen

Sie koennen einen neuen Command erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:command my_command
```

Dies erstellt einen neuen Command in `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Command erzwungen erstellen

**Argumente:**
Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Command ueberschrieben, falls er bereits existiert.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## State Managed Widget erstellen

Sie koennen ein neues State Managed Widget erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:state_managed_widget product_rating_widget
```

Das obige erstellt ein neues Widget in `lib/resources/widgets/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget ueberschrieben, falls es bereits existiert.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Navigation Hub erstellen

Sie koennen einen neuen Navigation Hub erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:navigation_hub dashboard
```

Dies erstellt einen neuen Navigation Hub in `lib/resources/pages/` und fuegt die Route automatisch hinzu.

**Argumente:**

| Flag | Kurz | Beschreibung |
|------|-------|-------------|
| `--auth` | `-a` | Als Auth-Seite erstellen |
| `--initial` | `-i` | Als initiale Seite erstellen |
| `--force` | `-f` | Ueberschreiben, falls vorhanden |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Bottom Sheet Modal erstellen

Sie koennen ein neues Bottom Sheet Modal erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:bottom_sheet_modal payment_options
```

Dies erstellt ein neues Bottom Sheet Modal in `lib/resources/widgets/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Modal ueberschrieben, falls es bereits existiert.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Button erstellen

Sie koennen ein neues Button-Widget erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:button checkout_button
```

Dies erstellt ein neues Button-Widget in `lib/resources/widgets/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Button ueberschrieben, falls er bereits existiert.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Interceptor erstellen

Sie koennen einen neuen Netzwerk-Interceptor erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:interceptor auth_interceptor
```

Dies erstellt einen neuen Interceptor in `lib/app/networking/dio/interceptors/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Interceptor ueberschrieben, falls er bereits existiert.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Env-Datei erstellen

Sie koennen eine neue Umgebungsdatei erstellen, indem Sie Folgendes im Terminal ausfuehren.

``` bash
metro make:env .env.staging
```

Dies erstellt eine neue `.env`-Datei in Ihrem Projektstammverzeichnis.

<div id="make-key"></div>

## Key generieren

Generieren Sie einen sicheren `APP_KEY` fuer die Umgebungsverschluesselung. Dieser wird fuer verschluesselte `.env`-Dateien in v7 verwendet.

``` bash
metro make:key
```

**Argumente:**

| Flag / Option | Kurz | Beschreibung |
|---------------|-------|-------------|
| `--force` | `-f` | Vorhandenen APP_KEY ueberschreiben |
| `--file` | `-e` | Ziel-.env-Datei (Standard: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## App-Icons erstellen

Sie koennen alle App-Icons fuer iOS und Android generieren, indem Sie den folgenden Befehl ausfuehren.

``` bash
dart run flutter_launcher_icons:main
```

Dies verwendet die <b>flutter_icons</b>-Konfiguration in Ihrer `pubspec.yaml`-Datei.

<div id="custom-commands"></div>

## Benutzerdefinierte Commands

Benutzerdefinierte Commands ermoeglichen es Ihnen, die CLI von Nylo mit Ihren eigenen projektspezifischen Befehlen zu erweitern. Diese Funktion ermoeglicht es Ihnen, wiederkehrende Aufgaben zu automatisieren, Deployment-Workflows zu implementieren oder beliebige benutzerdefinierte Funktionalitaet direkt in die Befehlszeilentools Ihres Projekts einzufuegen.

- [Benutzerdefinierte Commands erstellen](#creating-custom-commands)
- [Benutzerdefinierte Commands ausfuehren](#running-custom-commands)
- [Optionen zu Commands hinzufuegen](#adding-options-to-custom-commands)
- [Flags zu Commands hinzufuegen](#adding-flags-to-custom-commands)
- [Hilfsmethoden](#custom-command-helper-methods)

> **Hinweis:** Sie koennen derzeit nicht nylo_framework.dart in Ihren benutzerdefinierten Commands importieren, verwenden Sie stattdessen bitte ny_cli.dart.

<div id="creating-custom-commands"></div>

## Benutzerdefinierte Commands erstellen

Um einen neuen benutzerdefinierten Command zu erstellen, koennen Sie die `make:command`-Funktion verwenden:

```bash
metro make:command current_time
```

Sie koennen mit der `--category`-Option eine Kategorie fuer Ihren Command angeben:

```bash
# Specify a category
metro make:command current_time --category="project"
```

Dies erstellt eine neue Command-Datei unter `lib/app/commands/current_time.dart` mit folgender Struktur:

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

Der Command wird automatisch in der Datei `lib/app/commands/commands.json` registriert, die eine Liste aller registrierten Commands enthaelt:

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

## Benutzerdefinierte Commands ausfuehren

Nach der Erstellung koennen Sie Ihren benutzerdefinierten Command entweder mit der Metro-Kurzform oder dem vollstaendigen Dart-Befehl ausfuehren:

```bash
metro app:current_time
```

Wenn Sie `metro` ohne Argumente ausfuehren, sehen Sie Ihre benutzerdefinierten Commands im Menue unter dem Abschnitt "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Um Hilfe-Informationen fuer Ihren Command anzuzeigen, verwenden Sie das `--help`- oder `-h`-Flag:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Optionen zu Commands hinzufuegen

Optionen ermoeglichen es Ihrem Command, zusaetzliche Eingaben von Benutzern zu akzeptieren. Sie koennen Optionen in der `builder`-Methode hinzufuegen:

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

Greifen Sie dann in der `handle`-Methode Ihres Commands auf den Optionswert zu:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Command implementation...
}
```

Beispielverwendung:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Flags zu Commands hinzufuegen

Flags sind boolesche Optionen, die ein- oder ausgeschaltet werden koennen. Fuegen Sie Flags mit der `addFlag`-Methode hinzu:

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

Pruefen Sie dann den Flag-Status in der `handle`-Methode Ihres Commands:

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

Beispielverwendung:

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Hilfsmethoden

Die Basisklasse `NyCustomCommand` bietet mehrere Hilfsmethoden fuer gaengige Aufgaben:

#### Nachrichten ausgeben

Hier sind einige Methoden zum Ausgeben von Nachrichten in verschiedenen Farben:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Eine Info-Nachricht in blauem Text ausgeben |
| [`error`](#custom-command-helper-formatting)     | Eine Fehlernachricht in rotem Text ausgeben |
| [`success`](#custom-command-helper-formatting)   | Eine Erfolgsnachricht in gruenem Text ausgeben |
| [`warning`](#custom-command-helper-formatting)   | Eine Warnungsnachricht in gelbem Text ausgeben |

#### Prozesse ausfuehren

Prozesse ausfuehren und ihre Ausgabe in der Konsole anzeigen:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Ein Paket zur `pubspec.yaml` hinzufuegen |
| [`addPackages`](#custom-command-helper-add-packages) | Mehrere Pakete zur `pubspec.yaml` hinzufuegen |
| [`runProcess`](#custom-command-helper-run-process) | Einen externen Prozess ausfuehren und Ausgabe in der Konsole anzeigen |
| [`prompt`](#custom-command-helper-prompt)    | Benutzereingabe als Text erfassen |
| [`confirm`](#custom-command-helper-confirm)   | Eine Ja/Nein-Frage stellen und ein boolesches Ergebnis zurueckgeben |
| [`select`](#custom-command-helper-select)    | Eine Liste von Optionen praesentieren und den Benutzer eine auswaehlen lassen |
| [`multiSelect`](#custom-command-helper-multi-select) | Den Benutzer mehrere Optionen aus einer Liste auswaehlen lassen |

#### Netzwerkanfragen

Netzwerkanfragen ueber die Konsole stellen:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Einen API-Aufruf mit dem Nylo API-Client durchfuehren |


#### Ladeanzeige

Eine Ladeanzeige waehrend der Ausfuehrung einer Funktion anzeigen:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Eine Ladeanzeige waehrend der Ausfuehrung einer Funktion anzeigen |
| [`createSpinner`](#manual-spinner-control) | Eine Spinner-Instanz fuer manuelle Steuerung erstellen |

#### Helfer fuer benutzerdefinierte Commands

Sie koennen auch die folgenden Hilfsmethoden verwenden, um Command-Argumente zu verwalten:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Einen String-Wert aus den Command-Argumenten abrufen |
| [`getBool`](#custom-command-helper-get-bool)   | Einen booleschen Wert aus den Command-Argumenten abrufen |
| [`getInt`](#custom-command-helper-get-int)    | Einen Integer-Wert aus den Command-Argumenten abrufen |
| [`sleep`](#custom-command-helper-sleep) | Die Ausfuehrung fuer eine bestimmte Dauer pausieren |


### Externe Prozesse ausfuehren

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### Paketverwaltung

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

### Ausgabeformatierung

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## Interaktive Eingabemethoden

Die Basisklasse `NyCustomCommand` bietet mehrere Methoden zum Erfassen von Benutzereingaben im Terminal. Diese Methoden erleichtern die Erstellung interaktiver Befehlszeilen-Interfaces fuer Ihre benutzerdefinierten Commands.

<div id="custom-command-helper-prompt"></div>

### Texteingabe

```dart
String prompt(String question, {String defaultValue = ''})
```

Zeigt dem Benutzer eine Frage an und erfasst seine Textantwort.

**Parameter:**
- `question`: Die anzuzeigende Frage oder Aufforderung
- `defaultValue`: Optionaler Standardwert, wenn der Benutzer nur Enter drueckt

**Rueckgabe:** Die Eingabe des Benutzers als String oder der Standardwert, wenn keine Eingabe erfolgte

**Beispiel:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Bestaetigung

```dart
bool confirm(String question, {bool defaultValue = false})
```

Stellt dem Benutzer eine Ja/Nein-Frage und gibt ein boolesches Ergebnis zurueck.

**Parameter:**
- `question`: Die Ja/Nein-Frage
- `defaultValue`: Die Standardantwort (true fuer Ja, false fuer Nein)

**Rueckgabe:** `true`, wenn der Benutzer mit Ja geantwortet hat, `false`, wenn er mit Nein geantwortet hat

**Beispiel:**
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

### Einzelauswahl

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Praesentiert eine Liste von Optionen und laesst den Benutzer eine auswaehlen.

**Parameter:**
- `question`: Die Auswahlaufforderung
- `options`: Liste der verfuegbaren Optionen
- `defaultOption`: Optionale Standardauswahl

**Rueckgabe:** Die ausgewaehlte Option als String

**Beispiel:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Mehrfachauswahl

```dart
List<String> multiSelect(String question, List<String> options)
```

Ermoeglicht dem Benutzer, mehrere Optionen aus einer Liste auszuwaehlen.

**Parameter:**
- `question`: Die Auswahlaufforderung
- `options`: Liste der verfuegbaren Optionen

**Rueckgabe:** Eine Liste der ausgewaehlten Optionen

**Beispiel:**
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

## API-Hilfsmethode

Die `api`-Hilfsmethode vereinfacht Netzwerkanfragen aus Ihren benutzerdefinierten Commands.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Grundlegende Verwendungsbeispiele

### GET-Anfrage

```dart
// Fetch data
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST-Anfrage

```dart
// Create a resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT-Anfrage

```dart
// Update a resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE-Anfrage

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH-Anfrage

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Mit Query-Parametern

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Mit Spinner

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

## Spinner-Funktionalitaet

Spinner bieten visuelles Feedback waehrend lang laufender Operationen in Ihren benutzerdefinierten Commands. Sie zeigen einen animierten Indikator zusammen mit einer Nachricht an, waehrend Ihr Command asynchrone Aufgaben ausfuehrt, und verbessern die Benutzererfahrung durch Anzeige von Fortschritt und Status.

- [Mit Spinner verwenden](#using-with-spinner)
- [Manuelle Spinner-Steuerung](#manual-spinner-control)
- [Beispiele](#spinner-examples)

<div id="using-with-spinner"></div>

## Mit Spinner verwenden

Die `withSpinner`-Methode ermoeglicht es Ihnen, eine asynchrone Aufgabe mit einer Spinner-Animation zu umschliessen, die automatisch startet, wenn die Aufgabe beginnt, und stoppt, wenn sie abgeschlossen ist oder fehlschlaegt:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parameter:**
- `task`: Die auszufuehrende asynchrone Funktion
- `message`: Text, der waehrend des Spinner-Laufs angezeigt wird
- `successMessage`: Optionale Nachricht bei erfolgreichem Abschluss
- `errorMessage`: Optionale Nachricht bei Fehlschlag der Aufgabe

**Rueckgabe:** Das Ergebnis der Task-Funktion

**Beispiel:**
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

## Manuelle Spinner-Steuerung

Fuer komplexere Szenarien, in denen Sie den Spinner-Status manuell steuern muessen, koennen Sie eine Spinner-Instanz erstellen:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parameter:**
- `message`: Text, der waehrend des Spinner-Laufs angezeigt wird

**Rueckgabe:** Eine `ConsoleSpinner`-Instanz, die Sie manuell steuern koennen

**Beispiel mit manueller Steuerung:**
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

## Beispiele

### Einfache Aufgabe mit Spinner

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

### Mehrere aufeinanderfolgende Operationen

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

### Komplexer Workflow mit manueller Steuerung

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

Die Verwendung von Spinnern in Ihren benutzerdefinierten Commands bietet klares visuelles Feedback fuer Benutzer waehrend lang laufender Operationen und schafft ein professionelleres Befehlszeilenerlebnis.

<div id="custom-command-helper-get-string"></div>

### Einen String-Wert aus Optionen abrufen

```dart
String getString(String name, {String defaultValue = ''})
```

**Parameter:**

- `name`: Der Name der abzurufenden Option
- `defaultValue`: Optionaler Standardwert, wenn die Option nicht angegeben wurde

**Rueckgabe:** Der Wert der Option als String

**Beispiel:**
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

### Einen booleschen Wert aus Optionen abrufen

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parameter:**
- `name`: Der Name der abzurufenden Option
- `defaultValue`: Optionaler Standardwert, wenn die Option nicht angegeben wurde

**Rueckgabe:** Der Wert der Option als Boolean


**Beispiel:**
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

### Einen Integer-Wert aus Optionen abrufen

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parameter:**
- `name`: Der Name der abzurufenden Option
- `defaultValue`: Optionaler Standardwert, wenn die Option nicht angegeben wurde

**Rueckgabe:** Der Wert der Option als Integer

**Beispiel:**
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

### Fuer eine bestimmte Dauer pausieren

```dart
void sleep(int seconds)
```

**Parameter:**
- `seconds`: Die Anzahl der Sekunden zum Pausieren

**Rueckgabe:** Keine

**Beispiel:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Ausgabeformatierung

Ueber die grundlegenden `info`-, `error`-, `success`- und `warning`-Methoden hinaus bietet `NyCustomCommand` zusaetzliche Ausgabe-Helfer:

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

| Methode | Beschreibung |
|---------|-------------|
| `line(String message)` | Einfachen Text ohne Farbe ausgeben |
| `newLine([int count = 1])` | Leerzeilen ausgeben |
| `comment(String message)` | Gedaempften/grauen Text ausgeben |
| `alert(String message)` | Einen auffaelligen Alarmkasten ausgeben |
| `ask(String question, {String defaultValue})` | Alias fuer `prompt` |
| `promptSecret(String question)` | Versteckte Eingabe fuer sensible Daten |
| `abort([String? message, int exitCode = 1])` | Den Command mit einem Fehler beenden |

<div id="file-system-helpers"></div>

## Dateisystem-Helfer

`NyCustomCommand` enthaelt eingebaute Dateisystem-Helfer, sodass Sie `dart:io` nicht manuell fuer gaengige Operationen importieren muessen.

### Dateien lesen und schreiben

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

| Methode | Beschreibung |
|---------|-------------|
| `fileExists(String path)` | Gibt `true` zurueck, wenn die Datei existiert |
| `directoryExists(String path)` | Gibt `true` zurueck, wenn das Verzeichnis existiert |
| `readFile(String path)` | Datei als String lesen (async) |
| `readFileSync(String path)` | Datei als String lesen (sync) |
| `writeFile(String path, String content)` | Inhalt in Datei schreiben (async) |
| `writeFileSync(String path, String content)` | Inhalt in Datei schreiben (sync) |
| `appendFile(String path, String content)` | Inhalt an Datei anhaengen |
| `ensureDirectory(String path)` | Verzeichnis erstellen, falls nicht vorhanden |
| `deleteFile(String path)` | Eine Datei loeschen |
| `copyFile(String source, String destination)` | Eine Datei kopieren |

<div id="json-yaml-helpers"></div>

## JSON- und YAML-Helfer

JSON- und YAML-Dateien mit eingebauten Helfern lesen und schreiben.

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

| Methode | Beschreibung |
|---------|-------------|
| `readJson(String path)` | JSON-Datei als `Map<String, dynamic>` lesen |
| `readJsonArray(String path)` | JSON-Datei als `List<dynamic>` lesen |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Daten als JSON schreiben |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | An eine JSON-Array-Datei anhaengen |
| `readYaml(String path)` | YAML-Datei als `Map<String, dynamic>` lesen |

<div id="case-conversion-helpers"></div>

## Gross-/Kleinschreibungs-Konvertierungshelfer

Strings zwischen Namenskonventionen konvertieren, ohne das `recase`-Paket importieren zu muessen.

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

| Methode | Ausgabeformat | Beispiel |
|---------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Projektpfad-Helfer

Getter fuer Standard-{{ config('app.name') }}-Projektverzeichnisse. Diese geben Pfade relativ zum Projektstamm zurueck.

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

| Eigenschaft | Pfad |
|------------|------|
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
| `projectPath(String relativePath)` | Einen relativen Pfad innerhalb des Projekts aufloesen |

<div id="platform-helpers"></div>

## Plattform-Helfer

Plattform pruefen und auf Umgebungsvariablen zugreifen.

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

| Eigenschaft / Methode | Beschreibung |
|----------------------|-------------|
| `isWindows` | `true`, wenn auf Windows ausgefuehrt |
| `isMacOS` | `true`, wenn auf macOS ausgefuehrt |
| `isLinux` | `true`, wenn auf Linux ausgefuehrt |
| `workingDirectory` | Aktueller Arbeitsverzeichnispfad |
| `env(String key, [String defaultValue = ''])` | System-Umgebungsvariable lesen |

<div id="dart-flutter-commands"></div>

## Dart- und Flutter-Befehle

Gaengige Dart- und Flutter-CLI-Befehle als Hilfsmethoden ausfuehren. Jede gibt den Prozess-Exit-Code zurueck.

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

| Methode | Beschreibung |
|---------|-------------|
| `dartFormat(String path)` | `dart format` auf eine Datei oder ein Verzeichnis ausfuehren |
| `dartAnalyze([String? path])` | `dart analyze` ausfuehren |
| `flutterPubGet()` | `flutter pub get` ausfuehren |
| `flutterClean()` | `flutter clean` ausfuehren |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` ausfuehren |
| `flutterTest([String? path])` | `flutter test` ausfuehren |

<div id="dart-file-manipulation"></div>

## Dart-Dateimanipulation

Helfer zum programmatischen Bearbeiten von Dart-Dateien, nuetzlich beim Erstellen von Scaffolding-Tools.

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

| Methode | Beschreibung |
|---------|-------------|
| `addImport(String filePath, String importStatement)` | Import zu Dart-Datei hinzufuegen (ueberspringt, wenn bereits vorhanden) |
| `insertBeforeClosingBrace(String filePath, String code)` | Code vor letzter `}` in Datei einfuegen |
| `fileContains(String filePath, String identifier)` | Pruefen, ob Datei einen String enthaelt |
| `fileContainsPattern(String filePath, Pattern pattern)` | Pruefen, ob Datei einem Pattern entspricht |

<div id="directory-helpers"></div>

## Verzeichnis-Helfer

Helfer fuer die Arbeit mit Verzeichnissen und das Finden von Dateien.

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

| Methode | Beschreibung |
|---------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Verzeichnisinhalte auflisten |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Dateien nach Kriterien finden |
| `deleteDirectory(String path)` | Verzeichnis rekursiv loeschen |
| `copyDirectory(String source, String destination)` | Verzeichnis rekursiv kopieren |

<div id="validation-helpers"></div>

## Validierungs-Helfer

Helfer zum Validieren und Bereinigen von Benutzereingaben fuer die Code-Generierung.

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

| Methode | Beschreibung |
|---------|-------------|
| `isValidDartIdentifier(String name)` | Einen Dart-Bezeichnernamen validieren |
| `requireArgument(CommandResult result, {String? message})` | Nicht-leeres erstes Argument erfordern oder abbrechen |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Einen Klassennamen bereinigen und in PascalCase konvertieren |
| `cleanFileName(String name, {String extension = '.dart'})` | Einen Dateinamen bereinigen und in snake_case konvertieren |

<div id="file-scaffolding"></div>

## Datei-Scaffolding

Eine oder mehrere Dateien mit Inhalt mithilfe des Scaffolding-Systems erstellen.

### Einzelne Datei

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

### Mehrere Dateien

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

Die `ScaffoldFile`-Klasse akzeptiert:

| Eigenschaft | Typ | Beschreibung |
|------------|------|-------------|
| `path` | `String` | Zu erstellender Dateipfad |
| `content` | `String` | Dateiinhalt |
| `successMessage` | `String?` | Bei Erfolg angezeigte Nachricht |

<div id="task-runner"></div>

## Task-Runner

Eine Reihe benannter Aufgaben mit automatischer Statusausgabe ausfuehren.

### Grundlegender Task-Runner

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

### Task-Runner mit Spinner

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

Die `CommandTask`-Klasse akzeptiert:

| Eigenschaft | Typ | Standard | Beschreibung |
|------------|------|---------|-------------|
| `name` | `String` | erforderlich | In der Ausgabe angezeigter Aufgabenname |
| `action` | `Future<void> Function()` | erforderlich | Auszufuehrende asynchrone Funktion |
| `stopOnError` | `bool` | `true` | Ob verbleibende Aufgaben gestoppt werden sollen, wenn diese fehlschlaegt |

<div id="table-output"></div>

## Tabellenausgabe

Formatierte ASCII-Tabellen in der Konsole anzeigen.

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

Ausgabe:

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

## Fortschrittsbalken

Einen Fortschrittsbalken fuer Operationen mit bekannter Elementanzahl anzeigen.

### Manueller Fortschrittsbalken

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

### Elemente mit Fortschritt verarbeiten

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

### Synchroner Fortschritt

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

Die `ConsoleProgressBar`-Klasse bietet:

| Methode | Beschreibung |
|---------|-------------|
| `start()` | Den Fortschrittsbalken starten |
| `tick([int amount = 1])` | Fortschritt erhoehen |
| `update(int value)` | Fortschritt auf einen bestimmten Wert setzen |
| `updateMessage(String newMessage)` | Die angezeigte Nachricht aendern |
| `complete([String? completionMessage])` | Mit optionaler Nachricht abschliessen |
| `stop()` | Ohne Abschluss stoppen |
| `current` | Aktueller Fortschrittswert (Getter) |
| `percentage` | Fortschritt als Prozentsatz (Getter) |
