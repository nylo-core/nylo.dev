# Metro CLI-Tool

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Installieren](#install "Installieren")
- Make-Befehle
  - [Controller erstellen](#make-controller "Controller erstellen")
  - [Model erstellen](#make-model "Model erstellen")
  - [Seite erstellen](#make-page "Seite erstellen")
  - [Stateless Widget erstellen](#make-stateless-widget "Stateless Widget erstellen")
  - [Stateful Widget erstellen](#make-stateful-widget "Stateful Widget erstellen")
  - [Journey Widget erstellen](#make-journey-widget "Journey Widget erstellen")
  - [API Service erstellen](#make-api-service "API Service erstellen")
  - [Event erstellen](#make-event "Event erstellen")
  - [Provider erstellen](#make-provider "Provider erstellen")

  - [Formulare erstellen](#make-forms "Formulare erstellen")
  - [Route Guard erstellen](#make-route-guard "Route Guard erstellen")
  - [Config-Datei erstellen](#make-config-file "Config-Datei erstellen")
  - [Command erstellen](#make-command "Command erstellen")
  - [State Managed Widget erstellen](#make-state-managed-widget "State Managed Widget erstellen")
  - [Navigation Hub erstellen](#make-navigation-hub "Navigation Hub erstellen")
  - [Bottom Sheet Modal erstellen](#make-bottom-sheet-modal "Bottom Sheet Modal erstellen")
  - [Button erstellen](#make-button "Button erstellen")
  - [Interceptor erstellen](#make-interceptor "Interceptor erstellen")
  - [Env-Datei erstellen](#make-env-file "Env-Datei erstellen")
  - [Key generieren](#make-key "Key generieren")
- App-Icons
  - [App-Icons erstellen](#build-app-icons "App-Icons erstellen")
- Benutzerdefinierte Commands
  - [Benutzerdefinierte Commands erstellen](#creating-custom-commands "Benutzerdefinierte Commands erstellen")
  - [Benutzerdefinierte Commands ausführen](#running-custom-commands "Benutzerdefinierte Commands ausführen")
  - [Optionen zu Commands hinzufügen](#adding-options-to-custom-commands "Optionen zu Commands hinzufügen")
  - [Flags zu Commands hinzufügen](#adding-flags-to-custom-commands "Flags zu Commands hinzufügen")
  - [Hilfsmethoden](#custom-command-helper-methods "Hilfsmethoden")
  - [Interaktive Eingabemethoden](#interactive-input-methods "Interaktive Eingabemethoden")
  - [Ausgabeformatierung](#output-formatting "Ausgabeformatierung")
  - [Dateisystem-Helfer](#file-system-helpers "Dateisystem-Helfer")
  - [JSON- und YAML-Helfer](#json-yaml-helpers "JSON- und YAML-Helfer")
  - [Groß-/Kleinschreibungs-Konvertierungshelfer](#case-conversion-helpers "Groß-/Kleinschreibungs-Konvertierungshelfer")
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

Wenn Sie ein neues Nylo-Projekt mit `nylo init` erstellen, wird der `metro`-Befehl automatisch für Ihr Terminal konfiguriert. Sie können ihn sofort in jedem Nylo-Projekt verwenden.

Führen Sie `metro` aus Ihrem Projektverzeichnis aus, um alle verfügbaren Befehle zu sehen:

``` bash
metro
```

Sie sollten eine Ausgabe ähnlich der folgenden sehen.

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

Sie können einen neuen Controller erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:controller profile_controller
```

Dies erstellt einen neuen Controller, falls er noch nicht im Verzeichnis `lib/app/controllers/` existiert.

<div id="forcefully-make-a-controller"></div>

### Controller erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Controller überschrieben, falls er bereits existiert.

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

Sie können ein neues Model erstellen, indem Sie Folgendes im Terminal ausführen.

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

Dann können Sie Ihr JSON in das Terminal einfügen und es wird ein Model für Sie generiert.

<div id="forcefully-make-a-model"></div>

### Model erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Model überschrieben, falls es bereits existiert.

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

Sie können eine neue Seite erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:page product_page
```

Dies erstellt eine neue Seite, falls sie noch nicht im Verzeichnis `lib/resources/pages/` existiert.

<div id="create-a-page-with-a-controller"></div>

### Eine Seite mit Controller erstellen

Sie können eine neue Seite mit einem Controller erstellen, indem Sie Folgendes im Terminal ausführen.

**Argumente:**

Mit dem `--controller`- oder `-c`-Flag wird eine neue Seite mit einem Controller erstellt.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Eine Auth-Seite erstellen

Sie können eine neue Auth-Seite erstellen, indem Sie Folgendes im Terminal ausführen.

**Argumente:**

Mit dem `--auth`- oder `-a`-Flag wird eine neue Auth-Seite erstellt.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Eine initiale Seite erstellen

Sie können eine neue initiale Seite erstellen, indem Sie Folgendes im Terminal ausführen.

**Argumente:**

Mit dem `--initial`- oder `-i`-Flag wird eine neue initiale Seite erstellt.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Seite erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird eine vorhandene Seite überschrieben, falls sie bereits existiert.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Stateless Widget erstellen

- [Ein neues Stateless Widget erstellen](#making-a-new-stateless-widget "Ein neues Stateless Widget mit Metro erstellen")
- [Stateless Widget erzwungen erstellen](#forcefully-make-a-stateless-widget "Ein Stateless Widget erzwungen mit Metro erstellen")
<div id="making-a-new-stateless-widget"></div>

### Ein neues Stateless Widget erstellen

Sie können ein neues Stateless Widget erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:stateless_widget product_rating_widget
```

Das obige erstellt ein neues Widget, falls es noch nicht im Verzeichnis `lib/resources/widgets/` existiert.

Alle `make:*`-Befehle akzeptieren einen Pfadtrenner im Namen, um die Datei in ein Unterverzeichnis zu legen:

``` bash
metro make:stateless_widget login/BrandPanel
```

Dadurch wird das Widget unter `lib/resources/widgets/login/brand_panel.dart` erstellt.

<div id="forcefully-make-a-stateless-widget"></div>

### Stateless Widget erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget überschrieben, falls es bereits existiert.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Stateful Widget erstellen

- [Ein neues Stateful Widget erstellen](#making-a-new-stateful-widget "Ein neues Stateful Widget mit Metro erstellen")
- [Stateful Widget erzwungen erstellen](#forcefully-make-a-stateful-widget "Ein Stateful Widget erzwungen mit Metro erstellen")

<div id="making-a-new-stateful-widget"></div>

### Ein neues Stateful Widget erstellen

Sie können ein neues Stateful Widget erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:stateful_widget product_rating_widget
```

Das obige erstellt ein neues Widget, falls es noch nicht im Verzeichnis `lib/resources/widgets/` existiert.

<div id="forcefully-make-a-stateful-widget"></div>

### Stateful Widget erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget überschrieben, falls es bereits existiert.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Journey Widget erstellen

- [Ein neues Journey Widget erstellen](#making-a-new-journey-widget "Ein neues Journey Widget mit Metro erstellen")
- [Journey Widget erzwungen erstellen](#forcefully-make-a-journey-widget "Ein Journey Widget erzwungen mit Metro erstellen")

<div id="making-a-new-journey-widget"></div>

### Ein neues Journey Widget erstellen

Sie können ein neues Journey Widget erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Das obige erstellt ein neues Widget, falls es noch nicht im Verzeichnis `lib/resources/widgets/` existiert.

Das `--parent`-Argument wird verwendet, um das übergeordnete Widget anzugeben, zu dem das neue Journey Widget hinzugefügt wird.

Beispiel

``` bash
metro make:navigation_hub onboarding
```

Fügen Sie als Nächstes die neuen Journey Widgets hinzu.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Journey Widget erzwungen erstellen
**Argumente:**
Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget überschrieben, falls es bereits existiert.

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

Sie können einen neuen API Service erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:api_service user_api_service
```

Der neu erstellte API Service wird in `lib/app/networking/` abgelegt.

<div id="making-a-new-api-service-with-a-model"></div>

### Einen neuen API Service mit Model erstellen

Sie können einen neuen API Service mit einem Model erstellen, indem Sie Folgendes im Terminal ausführen.

**Argumente:**

Mit der `--model`- oder `-m`-Option wird ein neuer API Service mit einem Model erstellt.

``` bash
metro make:api_service user --model="User"
```

Der neu erstellte API Service wird in `lib/app/networking/` abgelegt.

### API Service erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener API Service überschrieben, falls er bereits existiert.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Event erstellen

- [Ein neues Event erstellen](#making-a-new-event "Ein neues Event mit Metro erstellen")
- [Event erzwungen erstellen](#forcefully-make-an-event "Ein Event erzwungen mit Metro erstellen")

<div id="making-a-new-event"></div>

### Ein neues Event erstellen

Sie können ein neues Event erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:event login_event
```

Dies erstellt ein neues Event in `lib/app/events`.

Verwenden Sie einen Pfadtrenner, um das Event in ein Unterverzeichnis zu organisieren:

``` bash
metro make:event auth/login_event
```

Dadurch wird das Event unter `lib/app/events/auth/login_event.dart` erstellt.

<div id="forcefully-make-an-event"></div>

### Event erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Event überschrieben, falls es bereits existiert.

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

Verwenden Sie einen Pfadtrenner, um den Provider in ein Unterverzeichnis zu organisieren:

``` bash
metro make:provider integrations/firebase_provider
```

Dadurch wird der Provider unter `lib/app/providers/integrations/firebase_provider.dart` erstellt.

<div id="forcefully-make-a-provider"></div>

### Provider erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Provider überschrieben, falls er bereits existiert.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-forms"></div>

## Formulare erstellen

- [Ein neues Formular erstellen](#making-a-new-form "Ein neues Formular mit Metro erstellen")
- [Formular erzwungen erstellen](#forcefully-make-a-form "Ein Formular erzwungen mit Metro erstellen")

<div id="making-a-new-form"></div>

### Ein neues Formular erstellen

Sie können ein neues Formular erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:form car_advert_form
```

Dies erstellt ein neues Formular in `lib/app/forms`.

Verwenden Sie einen Pfadtrenner, um das Formular in ein Unterverzeichnis zu organisieren:

``` bash
metro make:form checkout/car_advert_form
```

Dadurch wird das Formular unter `lib/app/forms/checkout/car_advert_form.dart` erstellt.

<div id="forcefully-make-a-form"></div>

### Formular erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Formular überschrieben, falls es bereits existiert.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Route Guard erstellen

- [Einen neuen Route Guard erstellen](#making-a-new-route-guard "Einen neuen Route Guard mit Metro erstellen")
- [Route Guard erzwungen erstellen](#forcefully-make-a-route-guard "Einen Route Guard erzwungen mit Metro erstellen")

<div id="making-a-new-route-guard"></div>

### Einen neuen Route Guard erstellen

Sie können einen Route Guard erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:route_guard premium_content
```

Dies erstellt einen neuen Route Guard in `lib/app/route_guards`.

Verwenden Sie einen Pfadtrenner, um den Guard in ein Unterverzeichnis zu organisieren:

``` bash
metro make:route_guard subscriptions/premium_content
```

Dadurch wird der Guard unter `lib/app/route_guards/subscriptions/premium_content.dart` erstellt.

<div id="forcefully-make-a-route-guard"></div>

### Route Guard erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Route Guard überschrieben, falls er bereits existiert.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Config-Datei erstellen

- [Eine neue Config-Datei erstellen](#making-a-new-config-file "Eine neue Config-Datei mit Metro erstellen")
- [Config-Datei erzwungen erstellen](#forcefully-make-a-config-file "Eine Config-Datei erzwungen mit Metro erstellen")

<div id="making-a-new-config-file"></div>

### Eine neue Config-Datei erstellen

Sie können eine neue Config-Datei erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:config shopping_settings
```

Dies erstellt eine neue Config-Datei in `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Config-Datei erzwungen erstellen

**Argumente:**

Mit dem `--force`- oder `-f`-Flag wird eine vorhandene Config-Datei überschrieben, falls sie bereits existiert.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Command erstellen

- [Einen neuen Command erstellen](#making-a-new-command "Einen neuen Command mit Metro erstellen")
- [Command erzwungen erstellen](#forcefully-make-a-command "Einen Command erzwungen mit Metro erstellen")

<div id="making-a-new-command"></div>

### Einen neuen Command erstellen

Sie können einen neuen Command erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:command my_command
```

Dies erstellt einen neuen Command in `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Command erzwungen erstellen

**Argumente:**
Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Command überschrieben, falls er bereits existiert.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## State Managed Widget erstellen

Sie können ein neues State Managed Widget erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:state_managed_widget product_rating_widget
```

Das obige erstellt ein neues Widget in `lib/resources/widgets/`. Das generierte Widget erweitert `NyStateManaged`, das Multi-Instance-Isolation über einen `stateName`-Konstruktorparameter unterstützt.

``` dart
class ProductRatingWidget extends NyStateManaged {
  ProductRatingWidget({super.key, super.stateName})
      : super(child: () => _ProductRatingWidgetState(stateName));

  static String state = "product_rating_widget";

  static String _stateFor(String? state) =>
      state == null ? ProductRatingWidget.state : "${ProductRatingWidget.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}

class _ProductRatingWidgetState extends NyState<ProductRatingWidget> {
  _ProductRatingWidgetState(String? stateName) {
    this.stateName = ProductRatingWidget._stateFor(stateName);
  }

  @override
  get init => () {
   // Initialisierungslogik hier
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Aktionen von überall in der App aufrufen
      // ProductRatingWidget.action("my_action", data: "hello world");
      // ProductRatingWidget.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Widget überschrieben, falls es bereits existiert.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Navigation Hub erstellen

Sie können einen neuen Navigation Hub erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:navigation_hub dashboard
```

Dies erstellt einen neuen Navigation Hub in `lib/resources/pages/` und fügt die Route automatisch hinzu.

**Argumente:**

| Flag | Kurz | Beschreibung |
|------|-------|-------------|
| `--auth` | `-a` | Als Auth-Seite erstellen |
| `--initial` | `-i` | Als initiale Seite erstellen |
| `--force` | `-f` | Überschreiben, falls vorhanden |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Bottom Sheet Modal erstellen

Sie können ein neues Bottom Sheet Modal erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:bottom_sheet_modal payment_options
```

Dies erstellt ein neues Bottom Sheet Modal in `lib/resources/widgets/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandenes Modal überschrieben, falls es bereits existiert.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Button erstellen

Sie können ein neues Button-Widget erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:button checkout_button
```

Dies erstellt ein neues Button-Widget in `lib/resources/widgets/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Button überschrieben, falls er bereits existiert.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Interceptor erstellen

Sie können einen neuen Netzwerk-Interceptor erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:interceptor auth_interceptor
```

Dies erstellt einen neuen Interceptor in `lib/app/networking/dio/interceptors/`.

Mit dem `--force`- oder `-f`-Flag wird ein vorhandener Interceptor überschrieben, falls er bereits existiert.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Env-Datei erstellen

Sie können eine neue Umgebungsdatei erstellen, indem Sie Folgendes im Terminal ausführen.

``` bash
metro make:env .env.staging
```

Dies erstellt eine neue `.env`-Datei in Ihrem Projektstammverzeichnis.

<div id="make-key"></div>

## Key generieren

Generieren Sie einen sicheren `APP_KEY` für die Umgebungsverschlüsselung. Dieser wird für verschlüsselte `.env`-Dateien in v7 verwendet.

``` bash
metro make:key
```

**Argumente:**

| Flag / Option | Kurz | Beschreibung |
|---------------|-------|-------------|
| `--force` | `-f` | Vorhandenen APP_KEY überschreiben |
| `--file` | `-e` | Ziel-.env-Datei (Standard: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## App-Icons erstellen

Sie können alle App-Icons für iOS und Android generieren, indem Sie den folgenden Befehl ausführen.

``` bash
dart run flutter_launcher_icons:main
```

Dies verwendet die <b>flutter_icons</b>-Konfiguration in Ihrer `pubspec.yaml`-Datei.

<div id="custom-commands"></div>

## Benutzerdefinierte Commands

Benutzerdefinierte Commands ermöglichen es Ihnen, die CLI von Nylo mit Ihren eigenen projektspezifischen Befehlen zu erweitern. Diese Funktion ermöglicht es Ihnen, wiederkehrende Aufgaben zu automatisieren, Deployment-Workflows zu implementieren oder beliebige benutzerdefinierte Funktionalität direkt in die Befehlszeilentools Ihres Projekts einzufügen.

- [Benutzerdefinierte Commands erstellen](#creating-custom-commands)
- [Benutzerdefinierte Commands ausführen](#running-custom-commands)
- [Optionen zu Commands hinzufügen](#adding-options-to-custom-commands)
- [Flags zu Commands hinzufügen](#adding-flags-to-custom-commands)
- [Hilfsmethoden](#custom-command-helper-methods)

> **Hinweis:** Sie können derzeit nicht nylo_framework.dart in Ihren benutzerdefinierten Commands importieren, verwenden Sie stattdessen bitte ny_cli.dart.

<div id="creating-custom-commands"></div>

## Benutzerdefinierte Commands erstellen

Um einen neuen benutzerdefinierten Command zu erstellen, können Sie die `make:command`-Funktion verwenden:

```bash
metro make:command current_time
```

Sie können mit der `--category`-Option eine Kategorie für Ihren Command angeben:

```bash
# Specify a category
metro make:command current_time --category="project"
```

Dies erstellt eine neue Command-Datei unter `lib/app/commands/current_time.dart` mit folgender Struktur:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Aktuelle-Zeit-Command
///
/// Verwendung:
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

      // Aktuelle Zeit abrufen
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Aktuelle Zeit formatieren
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

Der Command wird automatisch in der Datei `lib/app/commands/commands.json` registriert, die eine Liste aller registrierten Commands enthält:

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

## Benutzerdefinierte Commands ausführen

Nach der Erstellung können Sie Ihren benutzerdefinierten Command entweder mit der Metro-Kurzform oder dem vollständigen Dart-Befehl ausführen:

```bash
metro app:current_time
```

Wenn Sie `metro` ohne Argumente ausführen, sehen Sie Ihre benutzerdefinierten Commands im Menü unter dem Abschnitt "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Um Hilfe-Informationen für Ihren Command anzuzeigen, verwenden Sie das `--help`- oder `-h`-Flag:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Optionen zu Commands hinzufügen

Optionen ermöglichen es Ihrem Command, zusätzliche Eingaben von Benutzern zu akzeptieren. Sie können Optionen in der `builder`-Methode hinzufügen:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Option mit Standardwert hinzufügen
  command.addOption(
    'environment',     // Optionsname
    abbr: 'e',         // Kurzform-Abkürzung
    help: 'Target deployment environment', // Hilfetext
    defaultValue: 'development',  // Standardwert
    allowed: ['development', 'staging', 'production'] // erlaubte Werte
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

  // Command-Implementierung...
}
```

Beispielverwendung:

```bash
metro project:deploy --environment=production
# oder Kurzform
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Flags zu Commands hinzufügen

Flags sind boolesche Optionen, die ein- oder ausgeschaltet werden können. Fügen Sie Flags mit der `addFlag`-Methode hinzu:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // Flag-Name
    abbr: 'v',       // Kurzform-Abkürzung
    help: 'Enable verbose output', // Hilfetext
    defaultValue: false  // standardmäßig deaktiviert
  );

  return command;
}
```

Prüfen Sie dann den Flag-Status in der `handle`-Methode Ihres Commands:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Zusätzliches Logging...
  }

  // Command-Implementierung...
}
```

Beispielverwendung:

```bash
metro project:deploy --verbose
# oder Kurzform
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Hilfsmethoden

Die Basisklasse `NyCustomCommand` bietet mehrere Hilfsmethoden für gängige Aufgaben:

#### Nachrichten ausgeben

Hier sind einige Methoden zum Ausgeben von Nachrichten in verschiedenen Farben:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Eine Info-Nachricht in blauem Text ausgeben |
| [`error`](#custom-command-helper-formatting)     | Eine Fehlernachricht in rotem Text ausgeben |
| [`success`](#custom-command-helper-formatting)   | Eine Erfolgsnachricht in grünem Text ausgeben |
| [`warning`](#custom-command-helper-formatting)   | Eine Warnungsnachricht in gelbem Text ausgeben |

#### Prozesse ausführen

Prozesse ausführen und ihre Ausgabe in der Konsole anzeigen:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Ein Paket zur `pubspec.yaml` hinzufügen |
| [`addPackages`](#custom-command-helper-add-packages) | Mehrere Pakete zur `pubspec.yaml` hinzufügen |
| [`runProcess`](#custom-command-helper-run-process) | Einen externen Prozess ausführen und Ausgabe in der Konsole anzeigen |
| [`prompt`](#custom-command-helper-prompt)    | Benutzereingabe als Text erfassen |
| [`confirm`](#custom-command-helper-confirm)   | Eine Ja/Nein-Frage stellen und ein boolesches Ergebnis zurückgeben |
| [`select`](#custom-command-helper-select)    | Eine Liste von Optionen präsentieren und den Benutzer eine auswählen lassen |
| [`multiSelect`](#custom-command-helper-multi-select) | Den Benutzer mehrere Optionen aus einer Liste auswählen lassen |

#### Netzwerkanfragen

Netzwerkanfragen über die Konsole stellen:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Einen API-Aufruf mit dem Nylo API-Client durchführen |


#### Ladeanzeige

Eine Ladeanzeige während der Ausführung einer Funktion anzeigen:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Eine Ladeanzeige während der Ausführung einer Funktion anzeigen |
| [`createSpinner`](#manual-spinner-control) | Eine Spinner-Instanz für manuelle Steuerung erstellen |

#### Helfer für benutzerdefinierte Commands

Sie können auch die folgenden Hilfsmethoden verwenden, um Command-Argumente zu verwalten:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Einen String-Wert aus den Command-Argumenten abrufen |
| [`getBool`](#custom-command-helper-get-bool)   | Einen booleschen Wert aus den Command-Argumenten abrufen |
| [`getInt`](#custom-command-helper-get-int)    | Einen Integer-Wert aus den Command-Argumenten abrufen |
| [`sleep`](#custom-command-helper-sleep) | Die Ausführung für eine bestimmte Dauer pausieren |


### Externe Prozesse ausführen

```dart
// Prozess mit Ausgabe in der Konsole ausführen
await runProcess('flutter build web --release');

// Prozess lautlos ausführen
await runProcess('flutter pub get', silent: true);

// Prozess in einem bestimmten Verzeichnis ausführen
await runProcess('git pull', workingDirectory: './my-project');
```

### Paketverwaltung

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Paket zu pubspec.yaml hinzufügen
addPackage('firebase_core', version: '^2.4.0');

// Entwicklungspaket zu pubspec.yaml hinzufügen
addPackage('build_runner', dev: true);

// Mehrere Pakete auf einmal hinzufügen
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Ausgabeformatierung

```dart
// Statusmeldungen mit Farbkodierung ausgeben
info('Processing files...');    // Blauer Text
error('Operation failed');      // Roter Text
success('Deployment complete'); // Grüner Text
warning('Outdated package');    // Gelber Text
```

<div id="interactive-input-methods"></div>

## Interaktive Eingabemethoden

Die Basisklasse `NyCustomCommand` bietet mehrere Methoden zum Erfassen von Benutzereingaben im Terminal. Diese Methoden erleichtern die Erstellung interaktiver Befehlszeilen-Interfaces für Ihre benutzerdefinierten Commands.

<div id="custom-command-helper-prompt"></div>

### Texteingabe

```dart
String prompt(String question, {String defaultValue = ''})
```

Zeigt dem Benutzer eine Frage an und erfasst seine Textantwort.

**Parameter:**
- `question`: Die anzuzeigende Frage oder Aufforderung
- `defaultValue`: Optionaler Standardwert, wenn der Benutzer nur Enter drückt

**Rückgabe:** Die Eingabe des Benutzers als String oder der Standardwert, wenn keine Eingabe erfolgte

**Beispiel:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Bestätigung

```dart
bool confirm(String question, {bool defaultValue = false})
```

Stellt dem Benutzer eine Ja/Nein-Frage und gibt ein boolesches Ergebnis zurück.

**Parameter:**
- `question`: Die Ja/Nein-Frage
- `defaultValue`: Die Standardantwort (true für Ja, false für Nein)

**Rückgabe:** `true`, wenn der Benutzer mit Ja geantwortet hat, `false`, wenn er mit Nein geantwortet hat

**Beispiel:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // Benutzer hat bestätigt oder Enter gedrückt (Standardwert akzeptiert)
  await runProcess('flutter pub get');
} else {
  // Benutzer hat abgelehnt
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Einzelauswahl

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Präsentiert eine Liste von Optionen und lässt den Benutzer eine auswählen.

**Parameter:**
- `question`: Die Auswahlaufforderung
- `options`: Liste der verfügbaren Optionen
- `defaultOption`: Optionale Standardauswahl

**Rückgabe:** Die ausgewählte Option als String

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

Ermöglicht dem Benutzer, mehrere Optionen aus einer Liste auszuwählen.

**Parameter:**
- `question`: Die Auswahlaufforderung
- `options`: Liste der verfügbaren Optionen

**Rückgabe:** Eine Liste der ausgewählten Optionen

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
// Daten abrufen
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST-Anfrage

```dart
// Ressource erstellen
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT-Anfrage

```dart
// Ressource aktualisieren
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE-Anfrage

```dart
// Ressource löschen
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH-Anfrage

```dart
// Ressource teilweise aktualisieren
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Mit Query-Parametern

```dart
// Query-Parameter hinzufügen
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Mit Spinner

```dart
// Mit Spinner für bessere Benutzeroberflächenerfahrung verwenden
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Daten verarbeiten
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Spinner-Funktionalität

Spinner bieten visuelles Feedback während lang laufender Operationen in Ihren benutzerdefinierten Commands. Sie zeigen einen animierten Indikator zusammen mit einer Nachricht an, während Ihr Command asynchrone Aufgaben ausführt, und verbessern die Benutzererfahrung durch Anzeige von Fortschritt und Status.

- [Mit Spinner verwenden](#using-with-spinner)
- [Manuelle Spinner-Steuerung](#manual-spinner-control)
- [Beispiele](#spinner-examples)

<div id="using-with-spinner"></div>

## Mit Spinner verwenden

Die `withSpinner`-Methode ermöglicht es Ihnen, eine asynchrone Aufgabe mit einer Spinner-Animation zu umschließen, die automatisch startet, wenn die Aufgabe beginnt, und stoppt, wenn sie abgeschlossen ist oder fehlschlägt:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parameter:**
- `task`: Die auszuführende asynchrone Funktion
- `message`: Text, der während des Spinner-Laufs angezeigt wird
- `successMessage`: Optionale Nachricht bei erfolgreichem Abschluss
- `errorMessage`: Optionale Nachricht bei Fehlschlag der Aufgabe

**Rückgabe:** Das Ergebnis der Task-Funktion

**Beispiel:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Aufgabe mit Spinner ausführen
  final projectFiles = await withSpinner(
    task: () async {
      // Lang laufende Aufgabe (z. B. Projektdateien analysieren)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Mit den Ergebnissen fortfahren
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Manuelle Spinner-Steuerung

Für komplexere Szenarien, in denen Sie den Spinner-Status manuell steuern müssen, können Sie eine Spinner-Instanz erstellen:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parameter:**
- `message`: Text, der während des Spinner-Laufs angezeigt wird

**Rückgabe:** Eine `ConsoleSpinner`-Instanz, die Sie manuell steuern können

**Beispiel mit manueller Steuerung:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Spinner-Instanz erstellen
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // Erste Aufgabe
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Zweite Aufgabe
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Dritte Aufgabe
    await runProcess('./deploy.sh', silent: true);

    // Erfolgreich abschließen
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Fehler behandeln
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
      // Abhängigkeiten installieren
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
  // Erste Operation mit Spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Zweite Operation mit Spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Dritte Operation mit Spinner
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
    // Mehrere Schritte mit Statusaktualisierungen ausführen
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Prozess abschließen
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Die Verwendung von Spinnern in Ihren benutzerdefinierten Commands bietet klares visuelles Feedback für Benutzer während lang laufender Operationen und schafft ein professionelleres Befehlszeilenerlebnis.

<div id="custom-command-helper-get-string"></div>

### Einen String-Wert aus Optionen abrufen

```dart
String getString(String name, {String defaultValue = ''})
```

**Parameter:**

- `name`: Der Name der abzurufenden Option
- `defaultValue`: Optionaler Standardwert, wenn die Option nicht angegeben wurde

**Rückgabe:** Der Wert der Option als String

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

**Rückgabe:** Der Wert der Option als Boolean


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

**Rückgabe:** Der Wert der Option als Integer

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

### Für eine bestimmte Dauer pausieren

```dart
void sleep(int seconds)
```

**Parameter:**
- `seconds`: Die Anzahl der Sekunden zum Pausieren

**Rückgabe:** Keine

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

Über die grundlegenden `info`-, `error`-, `success`- und `warning`-Methoden hinaus bietet `NyCustomCommand` zusätzliche Ausgabe-Helfer:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Einfachen Text ausgeben (keine Farbe)
  line('Processing your request...');

  // Leerzeilen ausgeben
  newLine();       // eine Leerzeile
  newLine(3);      // drei Leerzeilen

  // Gedämpften Kommentar ausgeben (grauer Text)
  comment('This is a background note');

  // Auffälligen Alarmkasten ausgeben
  alert('Important: Please read carefully');

  // Ask ist ein Alias für prompt
  final name = ask('What is your name?');

  // Versteckte Eingabe für sensible Daten (z. B. Passwörter, API-Schlüssel)
  final apiKey = promptSecret('Enter your API key:');

  // Command mit Fehlermeldung und Exit-Code abbrechen
  if (name.isEmpty) {
    abort('Name is required');  // beendet mit Code 1
  }
}
```

| Methode | Beschreibung |
|---------|-------------|
| `line(String message)` | Einfachen Text ohne Farbe ausgeben |
| `newLine([int count = 1])` | Leerzeilen ausgeben |
| `comment(String message)` | Gedämpften/grauen Text ausgeben |
| `alert(String message)` | Einen auffälligen Alarmkasten ausgeben |
| `ask(String question, {String defaultValue})` | Alias für `prompt` |
| `promptSecret(String question)` | Versteckte Eingabe für sensible Daten |
| `abort([String? message, int exitCode = 1])` | Den Command mit einem Fehler beenden |

<div id="file-system-helpers"></div>

## Dateisystem-Helfer

`NyCustomCommand` enthält eingebaute Dateisystem-Helfer, sodass Sie `dart:io` nicht manuell für gängige Operationen importieren müssen.

### Dateien lesen und schreiben

```dart
@override
Future<void> handle(CommandResult result) async {
  // Prüfen, ob eine Datei existiert
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Prüfen, ob ein Verzeichnis existiert
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Datei lesen (asynchron)
  String content = await readFile('pubspec.yaml');

  // Datei lesen (synchron)
  String contentSync = readFileSync('pubspec.yaml');

  // In eine Datei schreiben (asynchron)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // In eine Datei schreiben (synchron)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Inhalt an eine Datei anhängen
  await appendFile('log.txt', 'New log entry\n');

  // Sicherstellen, dass ein Verzeichnis existiert (erstellt es, falls nicht vorhanden)
  await ensureDirectory('lib/generated');

  // Eine Datei löschen
  await deleteFile('lib/generated/output.dart');

  // Eine Datei kopieren
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Methode | Beschreibung |
|---------|-------------|
| `fileExists(String path)` | Gibt `true` zurück, wenn die Datei existiert |
| `directoryExists(String path)` | Gibt `true` zurück, wenn das Verzeichnis existiert |
| `readFile(String path)` | Datei als String lesen (async) |
| `readFileSync(String path)` | Datei als String lesen (sync) |
| `writeFile(String path, String content)` | Inhalt in Datei schreiben (async) |
| `writeFileSync(String path, String content)` | Inhalt in Datei schreiben (sync) |
| `appendFile(String path, String content)` | Inhalt an Datei anhängen |
| `ensureDirectory(String path)` | Verzeichnis erstellen, falls nicht vorhanden |
| `deleteFile(String path)` | Eine Datei löschen |
| `copyFile(String source, String destination)` | Eine Datei kopieren |

<div id="json-yaml-helpers"></div>

## JSON- und YAML-Helfer

JSON- und YAML-Dateien mit eingebauten Helfern lesen und schreiben.

```dart
@override
Future<void> handle(CommandResult result) async {
  // JSON-Datei als Map lesen
  Map<String, dynamic> config = await readJson('config.json');

  // JSON-Datei als Liste lesen
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Daten in eine JSON-Datei schreiben (standardmäßig als pretty-print)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Kompaktes JSON schreiben
  await writeJson('output.json', data, pretty: false);

  // Ein Element an eine JSON-Array-Datei anhängen
  // Falls die Datei [{"name": "a"}] enthält, wird dies dem Array hinzugefügt
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // prevents duplicates by this key
  );

  // YAML-Datei als Map lesen
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Methode | Beschreibung |
|---------|-------------|
| `readJson(String path)` | JSON-Datei als `Map<String, dynamic>` lesen |
| `readJsonArray(String path)` | JSON-Datei als `List<dynamic>` lesen |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Daten als JSON schreiben |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | An eine JSON-Array-Datei anhängen |
| `readYaml(String path)` | YAML-Datei als `Map<String, dynamic>` lesen |

<div id="case-conversion-helpers"></div>

## Groß-/Kleinschreibungs-Konvertierungshelfer

Strings zwischen Namenskonventionen konvertieren, ohne das `recase`-Paket importieren zu müssen.

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

Getter für Standard-{{ config('app.name') }}-Projektverzeichnisse. Diese geben Pfade relativ zum Projektstamm zurück.

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

  // Benutzerdefinierten Pfad relativ zum Projektstamm erstellen
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
| `projectPath(String relativePath)` | Einen relativen Pfad innerhalb des Projekts auflösen |

<div id="platform-helpers"></div>

## Plattform-Helfer

Plattform prüfen und auf Umgebungsvariablen zugreifen.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Plattform-Prüfungen
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Aktuelles Arbeitsverzeichnis
  info('Working in: $workingDirectory');

  // System-Umgebungsvariablen lesen
  String home = env('HOME', '/default/path');
}
```

| Eigenschaft / Methode | Beschreibung |
|----------------------|-------------|
| `isWindows` | `true`, wenn auf Windows ausgeführt |
| `isMacOS` | `true`, wenn auf macOS ausgeführt |
| `isLinux` | `true`, wenn auf Linux ausgeführt |
| `workingDirectory` | Aktueller Arbeitsverzeichnispfad |
| `env(String key, [String defaultValue = ''])` | System-Umgebungsvariable lesen |

<div id="dart-flutter-commands"></div>

## Dart- und Flutter-Befehle

Gängige Dart- und Flutter-CLI-Befehle als Hilfsmethoden ausführen. Jede gibt den Prozess-Exit-Code zurück.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Eine Dart-Datei oder ein Verzeichnis formatieren
  await dartFormat('lib/app/models/user.dart');

  // dart analyze ausführen
  int analyzeResult = await dartAnalyze('lib/');

  // flutter pub get ausführen
  await flutterPubGet();

  // flutter clean ausführen
  await flutterClean();

  // Für ein Ziel mit zusätzlichen Argumenten bauen
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // flutter test ausführen
  await flutterTest();
  await flutterTest('test/unit/');  // bestimmtes Verzeichnis
}
```

| Methode | Beschreibung |
|---------|-------------|
| `dartFormat(String path)` | `dart format` auf eine Datei oder ein Verzeichnis ausführen |
| `dartAnalyze([String? path])` | `dart analyze` ausführen |
| `flutterPubGet()` | `flutter pub get` ausführen |
| `flutterClean()` | `flutter clean` ausführen |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` ausführen |
| `flutterTest([String? path])` | `flutter test` ausführen |

<div id="dart-file-manipulation"></div>

## Dart-Dateimanipulation

Helfer zum programmatischen Bearbeiten von Dart-Dateien, nützlich beim Erstellen von Scaffolding-Tools.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Import-Anweisung zu einer Dart-Datei hinzufügen (vermeidet Duplikate)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Code vor der letzten schließenden Klammer in einer Datei einfügen
  // Nützlich zum Hinzufügen von Einträgen in Registrierungs-Maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Prüfen, ob eine Datei einen bestimmten String enthält
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Prüfen, ob eine Datei einem Regex-Pattern entspricht
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Methode | Beschreibung |
|---------|-------------|
| `addImport(String filePath, String importStatement)` | Import zu Dart-Datei hinzufügen (überspringt, wenn bereits vorhanden) |
| `insertBeforeClosingBrace(String filePath, String code)` | Code vor letzter `}` in Datei einfügen |
| `fileContains(String filePath, String identifier)` | Prüfen, ob Datei einen String enthält |
| `fileContainsPattern(String filePath, Pattern pattern)` | Prüfen, ob Datei einem Pattern entspricht |

<div id="directory-helpers"></div>

## Verzeichnis-Helfer

Helfer für die Arbeit mit Verzeichnissen und das Finden von Dateien.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Verzeichnisinhalte auflisten
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // Rekursiv auflisten
  var allEntities = listDirectory('lib/', recursive: true);

  // Dateien nach Kriterien suchen
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Dateien nach Namensmuster suchen
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Verzeichnis rekursiv löschen
  await deleteDirectory('build/');

  // Verzeichnis kopieren (rekursiv)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Methode | Beschreibung |
|---------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Verzeichnisinhalte auflisten |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Dateien nach Kriterien finden |
| `deleteDirectory(String path)` | Verzeichnis rekursiv löschen |
| `copyDirectory(String source, String destination)` | Verzeichnis rekursiv kopieren |

<div id="validation-helpers"></div>

## Validierungs-Helfer

Helfer zum Validieren und Bereinigen von Benutzereingaben für die Code-Generierung.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dart-Bezeichner validieren
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Nicht-leeres erstes Argument erfordern
  String name = requireArgument(result, message: 'Please provide a name');

  // Klassenname bereinigen (PascalCase, Suffixe entfernen)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Gibt zurück: 'User'

  // Dateiname bereinigen (snake_case mit Erweiterung)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Gibt zurück: 'user_model.dart'
}
```

| Methode | Beschreibung |
|---------|-------------|
| `isValidDartIdentifier(String name)` | Einen Dart-Bezeichnernamen validieren |
| `requireArgument(CommandResult result, {String? message})` | Nicht-leeres erstes Argument erfordern oder abbrechen |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Einen Klassennamen bereinigen und in PascalCase umwandeln |
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

Eine Reihe benannter Aufgaben mit automatischer Statusausgabe ausführen.

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
| `action` | `Future<void> Function()` | erforderlich | Auszuführende asynchrone Funktion |
| `stopOnError` | `bool` | `true` | Ob verbleibende Aufgaben gestoppt werden sollen, wenn diese fehlschlägt |

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

Einen Fortschrittsbalken für Operationen mit bekannter Elementanzahl anzeigen.

### Manueller Fortschrittsbalken

```dart
@override
Future<void> handle(CommandResult result) async {
  // Fortschrittsbalken für 100 Elemente erstellen
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // um 1 erhöhen
  }

  progress.complete('All files processed');
}
```

### Elemente mit Fortschritt verarbeiten

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Elemente mit automatischer Fortschrittsverfolgung verarbeiten
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // jede Datei verarbeiten
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
      // synchrone Verarbeitung
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
| `tick([int amount = 1])` | Fortschritt erhöhen |
| `update(int value)` | Fortschritt auf einen bestimmten Wert setzen |
| `updateMessage(String newMessage)` | Die angezeigte Nachricht ändern |
| `complete([String? completionMessage])` | Mit optionaler Nachricht abschließen |
| `stop()` | Ohne Abschluss stoppen |
| `current` | Aktueller Fortschrittswert (Getter) |
| `percentage` | Fortschritt als Prozentsatz (Getter) |
