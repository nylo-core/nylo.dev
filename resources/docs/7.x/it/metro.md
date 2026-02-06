# Strumento CLI Metro

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Installazione](#install "Installazione dell'alias Metro per {{ config('app.name') }}")
- Comandi Make
  - [Make controller](#make-controller "Creare un nuovo controller")
  - [Make model](#make-model "Creare un nuovo modello")
  - [Make page](#make-page "Creare una nuova pagina")
  - [Make stateless widget](#make-stateless-widget "Creare un nuovo widget stateless")
  - [Make stateful widget](#make-stateful-widget "Creare un nuovo widget stateful")
  - [Make journey widget](#make-journey-widget "Creare un nuovo journey widget")
  - [Make API Service](#make-api-service "Creare un nuovo API Service")
  - [Make Event](#make-event "Creare un nuovo evento")
  - [Make Provider](#make-provider "Creare un nuovo provider")
  - [Make Theme](#make-theme "Creare un nuovo tema")
  - [Make Forms](#make-forms "Creare un nuovo form")
  - [Make Route Guard](#make-route-guard "Creare un nuovo route guard")
  - [Make Config File](#make-config-file "Creare un nuovo file di configurazione")
  - [Make Command](#make-command "Creare un nuovo comando")
  - [Make State Managed Widget](#make-state-managed-widget "Creare un nuovo widget con gestione dello stato")
  - [Make Navigation Hub](#make-navigation-hub "Creare un nuovo navigation hub")
  - [Make Bottom Sheet Modal](#make-bottom-sheet-modal "Creare un nuovo bottom sheet modal")
  - [Make Button](#make-button "Creare un nuovo pulsante")
  - [Make Interceptor](#make-interceptor "Creare un nuovo interceptor")
  - [Make Env File](#make-env-file "Creare un nuovo file env")
  - [Make Key](#make-key "Generare APP_KEY")
- Icone dell'App
  - [Generare Icone dell'App](#build-app-icons "Generare icone dell'app con Metro")
- Comandi Personalizzati
  - [Creare comandi personalizzati](#creating-custom-commands "Creare comandi personalizzati")
  - [Eseguire Comandi Personalizzati](#running-custom-commands "Eseguire comandi personalizzati")
  - [Aggiungere opzioni ai comandi](#adding-options-to-custom-commands "Aggiungere opzioni ai comandi personalizzati")
  - [Aggiungere flag ai comandi](#adding-flags-to-custom-commands "Aggiungere flag ai comandi personalizzati")
  - [Metodi helper](#custom-command-helper-methods "Metodi helper per comandi personalizzati")
  - [Metodi di input interattivo](#interactive-input-methods "Metodi di input interattivo")
  - [Formattazione dell'output](#output-formatting "Formattazione dell'output")
  - [Helper per il file system](#file-system-helpers "Helper per il file system")
  - [Helper JSON e YAML](#json-yaml-helpers "Helper JSON e YAML")
  - [Helper per la conversione del case](#case-conversion-helpers "Helper per la conversione del case")
  - [Helper per i percorsi del progetto](#project-path-helpers "Helper per i percorsi del progetto")
  - [Helper per la piattaforma](#platform-helpers "Helper per la piattaforma")
  - [Comandi Dart e Flutter](#dart-flutter-commands "Comandi Dart e Flutter")
  - [Manipolazione file Dart](#dart-file-manipulation "Manipolazione file Dart")
  - [Helper per le directory](#directory-helpers "Helper per le directory")
  - [Helper per la validazione](#validation-helpers "Helper per la validazione")
  - [Scaffolding file](#file-scaffolding "Scaffolding file")
  - [Task runner](#task-runner "Task runner")
  - [Output tabellare](#table-output "Output tabellare")
  - [Barra di avanzamento](#progress-bar "Barra di avanzamento")


<div id="introduction"></div>

## Introduzione

Metro e' uno strumento CLI che funziona sotto il cofano del framework {{ config('app.name') }}.
Fornisce molti strumenti utili per velocizzare lo sviluppo.

<div id="install"></div>

## Installazione

Quando crei un nuovo progetto Nylo utilizzando `nylo init`, il comando `metro` viene configurato automaticamente per il tuo terminale. Puoi iniziare a usarlo immediatamente in qualsiasi progetto Nylo.

Esegui `metro` dalla directory del tuo progetto per vedere tutti i comandi disponibili:

``` bash
metro
```

Dovresti vedere un output simile al seguente.

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

## Make controller

- [Creare un nuovo controller](#making-a-new-controller "Creare un nuovo controller con Metro")
- [Forzare la creazione di un controller](#forcefully-make-a-controller "Forzare la creazione di un nuovo controller con Metro")
<div id="making-a-new-controller"></div>

### Creare un nuovo controller

Puoi creare un nuovo controller eseguendo il seguente comando nel terminale.

``` bash
metro make:controller profile_controller
```

Questo creera' un nuovo controller se non esiste gia' nella directory `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Forzare la creazione di un controller

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un controller esistente se gia' presente.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Make model

- [Creare un nuovo modello](#making-a-new-model "Creare un nuovo modello con Metro")
- [Creare un modello da JSON](#make-model-from-json "Creare un nuovo modello da JSON con Metro")
- [Forzare la creazione di un modello](#forcefully-make-a-model "Forzare la creazione di un nuovo modello con Metro")
<div id="making-a-new-model"></div>

### Creare un nuovo modello

Puoi creare un nuovo modello eseguendo il seguente comando nel terminale.

``` bash
metro make:model product
```

Il modello appena creato verra' posizionato in `lib/app/models/`.

<div id="make-model-from-json"></div>

### Creare un modello da JSON

**Argomenti:**

Usando il flag `--json` o `-j` creera' un nuovo modello da un payload JSON.

``` bash
metro make:model product --json
```

Poi puoi incollare il tuo JSON nel terminale e verra' generato un modello per te.

<div id="forcefully-make-a-model"></div>

### Forzare la creazione di un modello

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un modello esistente se gia' presente.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Make page

- [Creare una nuova pagina](#making-a-new-page "Creare una nuova pagina con Metro")
- [Creare una pagina con un controller](#create-a-page-with-a-controller "Creare una nuova pagina con un controller con Metro")
- [Creare una pagina auth](#create-an-auth-page "Creare una nuova pagina auth con Metro")
- [Creare una pagina iniziale](#create-an-initial-page "Creare una nuova pagina iniziale con Metro")
- [Forzare la creazione di una pagina](#forcefully-make-a-page "Forzare la creazione di una nuova pagina con Metro")

<div id="making-a-new-page"></div>

### Creare una nuova pagina

Puoi creare una nuova pagina eseguendo il seguente comando nel terminale.

``` bash
metro make:page product_page
```

Questo creera' una nuova pagina se non esiste gia' nella directory `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Creare una pagina con un controller

Puoi creare una nuova pagina con un controller eseguendo il seguente comando nel terminale.

**Argomenti:**

Usando il flag `--controller` o `-c` creera' una nuova pagina con un controller.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Creare una pagina auth

Puoi creare una nuova pagina auth eseguendo il seguente comando nel terminale.

**Argomenti:**

Usando il flag `--auth` o `-a` creera' una nuova pagina auth.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Creare una pagina iniziale

Puoi creare una nuova pagina iniziale eseguendo il seguente comando nel terminale.

**Argomenti:**

Usando il flag `--initial` o `-i` creera' una nuova pagina iniziale.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Forzare la creazione di una pagina

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' una pagina esistente se gia' presente.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Make stateless widget

- [Creare un nuovo widget stateless](#making-a-new-stateless-widget "Creare un nuovo widget stateless con Metro")
- [Forzare la creazione di un widget stateless](#forcefully-make-a-stateless-widget "Forzare la creazione di un nuovo widget stateless con Metro")
<div id="making-a-new-stateless-widget"></div>

### Creare un nuovo widget stateless

Puoi creare un nuovo widget stateless eseguendo il seguente comando nel terminale.

``` bash
metro make:stateless_widget product_rating_widget
```

Il comando precedente creera' un nuovo widget se non esiste gia' nella directory `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Forzare la creazione di un widget stateless

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un widget esistente se gia' presente.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Make stateful widget

- [Creare un nuovo widget stateful](#making-a-new-stateful-widget "Creare un nuovo widget stateful con Metro")
- [Forzare la creazione di un widget stateful](#forcefully-make-a-stateful-widget "Forzare la creazione di un nuovo widget stateful con Metro")

<div id="making-a-new-stateful-widget"></div>

### Creare un nuovo widget stateful

Puoi creare un nuovo widget stateful eseguendo il seguente comando nel terminale.

``` bash
metro make:stateful_widget product_rating_widget
```

Il comando precedente creera' un nuovo widget se non esiste gia' nella directory `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Forzare la creazione di un widget stateful

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un widget esistente se gia' presente.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Make journey widget

- [Creare un nuovo journey widget](#making-a-new-journey-widget "Creare un nuovo journey widget con Metro")
- [Forzare la creazione di un journey widget](#forcefully-make-a-journey-widget "Forzare la creazione di un nuovo journey widget con Metro")

<div id="making-a-new-journey-widget"></div>

### Creare un nuovo journey widget

Puoi creare un nuovo journey widget eseguendo il seguente comando nel terminale.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Il comando precedente creera' un nuovo widget se non esiste gia' nella directory `lib/resources/widgets/`.

L'argomento `--parent` viene utilizzato per specificare il widget genitore a cui il nuovo journey widget verra' aggiunto.

Esempio

``` bash
metro make:navigation_hub onboarding
```

Successivamente, aggiungi i nuovi journey widget.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Forzare la creazione di un journey widget
**Argomenti:**
Usando il flag `--force` o `-f` sovrascrivera' un widget esistente se gia' presente.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Make API Service

- [Creare un nuovo API Service](#making-a-new-api-service "Creare un nuovo API Service con Metro")
- [Creare un nuovo API Service con un modello](#making-a-new-api-service-with-a-model "Creare un nuovo API Service con un modello con Metro")
- [Creare API Service con Postman](#make-api-service-using-postman "Creare API service con Postman")
- [Forzare la creazione di un API Service](#forcefully-make-an-api-service "Forzare la creazione di un nuovo API Service con Metro")

<div id="making-a-new-api-service"></div>

### Creare un nuovo API Service

Puoi creare un nuovo API service eseguendo il seguente comando nel terminale.

``` bash
metro make:api_service user_api_service
```

Il servizio API appena creato verra' posizionato in `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Creare un nuovo API Service con un modello

Puoi creare un nuovo API service con un modello eseguendo il seguente comando nel terminale.

**Argomenti:**

Usando l'opzione `--model` o `-m` creera' un nuovo API service con un modello.

``` bash
metro make:api_service user --model="User"
```

Il servizio API appena creato verra' posizionato in `lib/app/networking/`.

### Forzare la creazione di un API Service

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un API Service esistente se gia' presente.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Make event

- [Creare un nuovo evento](#making-a-new-event "Creare un nuovo evento con Metro")
- [Forzare la creazione di un evento](#forcefully-make-an-event "Forzare la creazione di un nuovo evento con Metro")

<div id="making-a-new-event"></div>

### Creare un nuovo evento

Puoi creare un nuovo evento eseguendo il seguente comando nel terminale.

``` bash
metro make:event login_event
```

Questo creera' un nuovo evento in `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Forzare la creazione di un evento

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un evento esistente se gia' presente.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Make provider

- [Creare un nuovo provider](#making-a-new-provider "Creare un nuovo provider con Metro")
- [Forzare la creazione di un provider](#forcefully-make-a-provider "Forzare la creazione di un nuovo provider con Metro")

<div id="making-a-new-provider"></div>

### Creare un nuovo provider

Crea nuovi provider nella tua applicazione usando il comando seguente.

``` bash
metro make:provider firebase_provider
```

Il provider appena creato verra' posizionato in `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Forzare la creazione di un provider

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un provider esistente se gia' presente.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Make theme

- [Creare un nuovo tema](#making-a-new-theme "Creare un nuovo tema con Metro")
- [Forzare la creazione di un tema](#forcefully-make-a-theme "Forzare la creazione di un nuovo tema con Metro")

<div id="making-a-new-theme"></div>

### Creare un nuovo tema

Puoi creare temi eseguendo il seguente comando nel terminale.

``` bash
metro make:theme bright_theme
```

Questo creera' un nuovo tema in `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Forzare la creazione di un tema

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un tema esistente se gia' presente.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Make Forms

- [Creare un nuovo form](#making-a-new-form "Creare un nuovo form con Metro")
- [Forzare la creazione di un form](#forcefully-make-a-form "Forzare la creazione di un nuovo form con Metro")

<div id="making-a-new-form"></div>

### Creare un nuovo form

Puoi creare un nuovo form eseguendo il seguente comando nel terminale.

``` bash
metro make:form car_advert_form
```

Questo creera' un nuovo form in `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Forzare la creazione di un form

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un form esistente se gia' presente.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Make Route Guard

- [Creare un nuovo route guard](#making-a-new-route-guard "Creare un nuovo route guard con Metro")
- [Forzare la creazione di un route guard](#forcefully-make-a-route-guard "Forzare la creazione di un nuovo route guard con Metro")

<div id="making-a-new-route-guard"></div>

### Creare un nuovo route guard

Puoi creare un route guard eseguendo il seguente comando nel terminale.

``` bash
metro make:route_guard premium_content
```

Questo creera' un nuovo route guard in `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Forzare la creazione di un route guard

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un route guard esistente se gia' presente.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Make Config File

- [Creare un nuovo file di configurazione](#making-a-new-config-file "Creare un nuovo file di configurazione con Metro")
- [Forzare la creazione di un file di configurazione](#forcefully-make-a-config-file "Forzare la creazione di un nuovo file di configurazione con Metro")

<div id="making-a-new-config-file"></div>

### Creare un nuovo file di configurazione

Puoi creare un nuovo file di configurazione eseguendo il seguente comando nel terminale.

``` bash
metro make:config shopping_settings
```

Questo creera' un nuovo file di configurazione in `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Forzare la creazione di un file di configurazione

**Argomenti:**

Usando il flag `--force` o `-f` sovrascrivera' un file di configurazione esistente se gia' presente.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Make Command

- [Creare un nuovo comando](#making-a-new-command "Creare un nuovo comando con Metro")
- [Forzare la creazione di un comando](#forcefully-make-a-command "Forzare la creazione di un nuovo comando con Metro")

<div id="making-a-new-command"></div>

### Creare un nuovo comando

Puoi creare un nuovo comando eseguendo il seguente comando nel terminale.

``` bash
metro make:command my_command
```

Questo creera' un nuovo comando in `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Forzare la creazione di un comando

**Argomenti:**
Usando il flag `--force` o `-f` sovrascrivera' un comando esistente se gia' presente.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Make State Managed Widget

Puoi creare un nuovo widget con gestione dello stato eseguendo il seguente comando nel terminale.

``` bash
metro make:state_managed_widget product_rating_widget
```

Il comando precedente creera' un nuovo widget in `lib/resources/widgets/`.

Usando il flag `--force` o `-f` sovrascrivera' un widget esistente se gia' presente.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Make Navigation Hub

Puoi creare un nuovo navigation hub eseguendo il seguente comando nel terminale.

``` bash
metro make:navigation_hub dashboard
```

Questo creera' un nuovo navigation hub in `lib/resources/pages/` e aggiungera' automaticamente la route.

**Argomenti:**

| Flag | Abbreviazione | Descrizione |
|------|-------|-------------|
| `--auth` | `-a` | Creare come pagina auth |
| `--initial` | `-i` | Creare come pagina iniziale |
| `--force` | `-f` | Sovrascrivere se esiste |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Make Bottom Sheet Modal

Puoi creare un nuovo bottom sheet modal eseguendo il seguente comando nel terminale.

``` bash
metro make:bottom_sheet_modal payment_options
```

Questo creera' un nuovo bottom sheet modal in `lib/resources/widgets/`.

Usando il flag `--force` o `-f` sovrascrivera' un modal esistente se gia' presente.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Make Button

Puoi creare un nuovo widget pulsante eseguendo il seguente comando nel terminale.

``` bash
metro make:button checkout_button
```

Questo creera' un nuovo widget pulsante in `lib/resources/widgets/`.

Usando il flag `--force` o `-f` sovrascrivera' un pulsante esistente se gia' presente.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Make Interceptor

Puoi creare un nuovo interceptor di rete eseguendo il seguente comando nel terminale.

``` bash
metro make:interceptor auth_interceptor
```

Questo creera' un nuovo interceptor in `lib/app/networking/dio/interceptors/`.

Usando il flag `--force` o `-f` sovrascrivera' un interceptor esistente se gia' presente.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Make Env File

Puoi creare un nuovo file di ambiente eseguendo il seguente comando nel terminale.

``` bash
metro make:env .env.staging
```

Questo creera' un nuovo file `.env` nella root del tuo progetto.

<div id="make-key"></div>

## Make Key

Genera una `APP_KEY` sicura per la crittografia dell'ambiente. Questa viene utilizzata per i file `.env` crittografati nella v7.

``` bash
metro make:key
```

**Argomenti:**

| Flag / Opzione | Abbreviazione | Descrizione |
|---------------|-------|-------------|
| `--force` | `-f` | Sovrascrivere APP_KEY esistente |
| `--file` | `-e` | File .env di destinazione (predefinito: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Generare Icone dell'App

Puoi generare tutte le icone dell'app per IOS e Android eseguendo il seguente comando.

``` bash
dart run flutter_launcher_icons:main
```

Questo utilizza la configurazione <b>flutter_icons</b> nel tuo file `pubspec.yaml`.

<div id="custom-commands"></div>

## Comandi Personalizzati

I comandi personalizzati ti permettono di estendere la CLI di Nylo con i tuoi comandi specifici per il progetto. Questa funzionalita' ti consente di automatizzare attivita' ripetitive, implementare workflow di deployment o aggiungere qualsiasi funzionalita' personalizzata direttamente negli strumenti da riga di comando del tuo progetto.

- [Creare comandi personalizzati](#creating-custom-commands)
- [Eseguire Comandi Personalizzati](#running-custom-commands)
- [Aggiungere opzioni ai comandi](#adding-options-to-custom-commands)
- [Aggiungere flag ai comandi](#adding-flags-to-custom-commands)
- [Metodi helper](#custom-command-helper-methods)

> **Nota:** Attualmente non puoi importare nylo_framework.dart nei tuoi comandi personalizzati, usa invece ny_cli.dart.

<div id="creating-custom-commands"></div>

## Creare Comandi Personalizzati

Per creare un nuovo comando personalizzato, puoi usare la funzionalita' `make:command`:

```bash
metro make:command current_time
```

Puoi specificare una categoria per il tuo comando usando l'opzione `--category`:

```bash
# Specify a category
metro make:command current_time --category="project"
```

Questo creera' un nuovo file di comando in `lib/app/commands/current_time.dart` con la seguente struttura:

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

Il comando verra' automaticamente registrato nel file `lib/app/commands/commands.json`, che contiene un elenco di tutti i comandi registrati:

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

## Eseguire Comandi Personalizzati

Una volta creato, puoi eseguire il tuo comando personalizzato usando la scorciatoia Metro o il comando Dart completo:

```bash
metro app:current_time
```

Quando esegui `metro` senza argomenti, vedrai i tuoi comandi personalizzati elencati nel menu sotto la sezione "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Per visualizzare le informazioni di aiuto per il tuo comando, usa il flag `--help` o `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Aggiungere Opzioni ai Comandi

Le opzioni permettono al tuo comando di accettare input aggiuntivi dagli utenti. Puoi aggiungere opzioni al tuo comando nel metodo `builder`:

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

Poi accedi al valore dell'opzione nel metodo `handle` del tuo comando:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Command implementation...
}
```

Esempio di utilizzo:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Aggiungere Flag ai Comandi

I flag sono opzioni booleane che possono essere attivate o disattivate. Aggiungi flag al tuo comando usando il metodo `addFlag`:

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

Poi controlla lo stato del flag nel metodo `handle` del tuo comando:

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

Esempio di utilizzo:

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Metodi Helper

La classe base `NyCustomCommand` fornisce diversi metodi helper per assistere con le attivita' comuni:

#### Stampa Messaggi

Ecco alcuni metodi per stampare messaggi in diversi colori:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Stampa un messaggio informativo in testo blu |
| [`error`](#custom-command-helper-formatting)     | Stampa un messaggio di errore in testo rosso |
| [`success`](#custom-command-helper-formatting)   | Stampa un messaggio di successo in testo verde |
| [`warning`](#custom-command-helper-formatting)   | Stampa un messaggio di avviso in testo giallo |

#### Esecuzione Processi

Esegui processi e visualizza il loro output nella console:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Aggiungere un pacchetto a `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Aggiungere piu' pacchetti a `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Eseguire un processo esterno e visualizzare l'output nella console |
| [`prompt`](#custom-command-helper-prompt)    | Raccogliere input dell'utente come testo |
| [`confirm`](#custom-command-helper-confirm)   | Fare una domanda si'/no e restituire un risultato booleano |
| [`select`](#custom-command-helper-select)    | Presentare un elenco di opzioni e permettere all'utente di selezionarne una |
| [`multiSelect`](#custom-command-helper-multi-select) | Permettere all'utente di selezionare piu' opzioni da un elenco |

#### Richieste di Rete

Effettuare richieste di rete tramite la console:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Effettuare una chiamata API usando il client API di Nylo |


#### Spinner di Caricamento

Visualizzare uno spinner di caricamento durante l'esecuzione di una funzione:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Mostrare uno spinner di caricamento durante l'esecuzione di una funzione |
| [`createSpinner`](#manual-spinner-control) | Creare un'istanza di spinner per il controllo manuale |

#### Helper per Comandi Personalizzati

Puoi anche usare i seguenti metodi helper per gestire gli argomenti dei comandi:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Ottenere un valore stringa dagli argomenti del comando |
| [`getBool`](#custom-command-helper-get-bool)   | Ottenere un valore booleano dagli argomenti del comando |
| [`getInt`](#custom-command-helper-get-int)    | Ottenere un valore intero dagli argomenti del comando |
| [`sleep`](#custom-command-helper-sleep) | Mettere in pausa l'esecuzione per una durata specificata |


### Esecuzione di Processi Esterni

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### Gestione Pacchetti

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

### Formattazione dell'Output

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## Metodi di Input Interattivo

La classe base `NyCustomCommand` fornisce diversi metodi per raccogliere input dall'utente nel terminale. Questi metodi facilitano la creazione di interfacce a riga di comando interattive per i tuoi comandi personalizzati.

<div id="custom-command-helper-prompt"></div>

### Input di Testo

```dart
String prompt(String question, {String defaultValue = ''})
```

Visualizza una domanda all'utente e raccoglie la sua risposta testuale.

**Parametri:**
- `question`: La domanda o il prompt da visualizzare
- `defaultValue`: Valore predefinito opzionale se l'utente preme semplicemente Invio

**Restituisce:** L'input dell'utente come stringa, o il valore predefinito se non e' stato fornito alcun input

**Esempio:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Conferma

```dart
bool confirm(String question, {bool defaultValue = false})
```

Pone all'utente una domanda si'/no e restituisce un risultato booleano.

**Parametri:**
- `question`: La domanda si'/no da porre
- `defaultValue`: La risposta predefinita (true per si', false per no)

**Restituisce:** `true` se l'utente ha risposto si', `false` se ha risposto no

**Esempio:**
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

### Selezione Singola

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Presenta un elenco di opzioni e permette all'utente di selezionarne una.

**Parametri:**
- `question`: Il prompt di selezione
- `options`: Elenco delle opzioni disponibili
- `defaultOption`: Selezione predefinita opzionale

**Restituisce:** L'opzione selezionata come stringa

**Esempio:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Selezione Multipla

```dart
List<String> multiSelect(String question, List<String> options)
```

Permette all'utente di selezionare piu' opzioni da un elenco.

**Parametri:**
- `question`: Il prompt di selezione
- `options`: Elenco delle opzioni disponibili

**Restituisce:** Un elenco delle opzioni selezionate

**Esempio:**
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

## Metodo Helper API

Il metodo helper `api` semplifica l'esecuzione di richieste di rete dai tuoi comandi personalizzati.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Esempi di Utilizzo Base

### Richiesta GET

```dart
// Fetch data
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Richiesta POST

```dart
// Create a resource
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Richiesta PUT

```dart
// Update a resource
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Richiesta DELETE

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Richiesta PATCH

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Con Parametri Query

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Con Spinner

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

## Funzionalita' Spinner

Gli spinner forniscono un feedback visivo durante le operazioni di lunga durata nei tuoi comandi personalizzati. Visualizzano un indicatore animato insieme a un messaggio mentre il tuo comando esegue attivita' asincrone, migliorando l'esperienza utente mostrando progresso e stato.

- [Utilizzo con spinner](#using-with-spinner)
- [Controllo manuale dello spinner](#manual-spinner-control)
- [Esempi](#spinner-examples)

<div id="using-with-spinner"></div>

## Utilizzo con spinner

Il metodo `withSpinner` ti permette di avvolgere un'attivita' asincrona con un'animazione spinner che si avvia automaticamente quando l'attivita' inizia e si ferma quando si completa o fallisce:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parametri:**
- `task`: La funzione asincrona da eseguire
- `message`: Testo da visualizzare mentre lo spinner e' in esecuzione
- `successMessage`: Messaggio opzionale da visualizzare al completamento con successo
- `errorMessage`: Messaggio opzionale da visualizzare se l'attivita' fallisce

**Restituisce:** Il risultato della funzione task

**Esempio:**
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

## Controllo Manuale dello Spinner

Per scenari piu' complessi in cui hai bisogno di controllare lo stato dello spinner manualmente, puoi creare un'istanza di spinner:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parametri:**
- `message`: Testo da visualizzare mentre lo spinner e' in esecuzione

**Restituisce:** Un'istanza `ConsoleSpinner` che puoi controllare manualmente

**Esempio con controllo manuale:**
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

## Esempi

### Attivita' Semplice con Spinner

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

### Operazioni Consecutive Multiple

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

### Workflow Complesso con Controllo Manuale

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

L'utilizzo degli spinner nei tuoi comandi personalizzati fornisce un feedback visivo chiaro agli utenti durante le operazioni di lunga durata, creando un'esperienza a riga di comando piu' raffinata e professionale.

<div id="custom-command-helper-get-string"></div>

### Ottenere un valore stringa dalle opzioni

```dart
String getString(String name, {String defaultValue = ''})
```

**Parametri:**

- `name`: Il nome dell'opzione da recuperare
- `defaultValue`: Valore predefinito opzionale se l'opzione non viene fornita

**Restituisce:** Il valore dell'opzione come stringa

**Esempio:**
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

### Ottenere un valore booleano dalle opzioni

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parametri:**
- `name`: Il nome dell'opzione da recuperare
- `defaultValue`: Valore predefinito opzionale se l'opzione non viene fornita

**Restituisce:** Il valore dell'opzione come booleano


**Esempio:**
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

### Ottenere un valore intero dalle opzioni

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parametri:**
- `name`: Il nome dell'opzione da recuperare
- `defaultValue`: Valore predefinito opzionale se l'opzione non viene fornita

**Restituisce:** Il valore dell'opzione come intero

**Esempio:**
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

### Pausa per una durata specificata

```dart
void sleep(int seconds)
```

**Parametri:**
- `seconds`: Il numero di secondi di pausa

**Restituisce:** Nessuno

**Esempio:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Formattazione dell'Output

Oltre ai metodi base `info`, `error`, `success` e `warning`, `NyCustomCommand` fornisce helper di output aggiuntivi:

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

| Metodo | Descrizione |
|--------|-------------|
| `line(String message)` | Stampa testo semplice senza colore |
| `newLine([int count = 1])` | Stampa righe vuote |
| `comment(String message)` | Stampa testo attenuato/grigio |
| `alert(String message)` | Stampa un riquadro di avviso prominente |
| `ask(String question, {String defaultValue})` | Alias per `prompt` |
| `promptSecret(String question)` | Input nascosto per dati sensibili |
| `abort([String? message, int exitCode = 1])` | Uscire dal comando con un errore |

<div id="file-system-helpers"></div>

## Helper per il File System

`NyCustomCommand` include helper per il file system integrati cosi' non devi importare manualmente `dart:io` per le operazioni comuni.

### Lettura e Scrittura File

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

| Metodo | Descrizione |
|--------|-------------|
| `fileExists(String path)` | Restituisce `true` se il file esiste |
| `directoryExists(String path)` | Restituisce `true` se la directory esiste |
| `readFile(String path)` | Legge il file come stringa (asincrono) |
| `readFileSync(String path)` | Legge il file come stringa (sincrono) |
| `writeFile(String path, String content)` | Scrive contenuto nel file (asincrono) |
| `writeFileSync(String path, String content)` | Scrive contenuto nel file (sincrono) |
| `appendFile(String path, String content)` | Aggiunge contenuto al file |
| `ensureDirectory(String path)` | Crea la directory se non esiste |
| `deleteFile(String path)` | Elimina un file |
| `copyFile(String source, String destination)` | Copia un file |

<div id="json-yaml-helpers"></div>

## Helper JSON e YAML

Leggi e scrivi file JSON e YAML con helper integrati.

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

| Metodo | Descrizione |
|--------|-------------|
| `readJson(String path)` | Legge un file JSON come `Map<String, dynamic>` |
| `readJsonArray(String path)` | Legge un file JSON come `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Scrive dati come JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Aggiunge a un file array JSON |
| `readYaml(String path)` | Legge un file YAML come `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Helper per la Conversione del Case

Converti stringhe tra convenzioni di denominazione senza importare il pacchetto `recase`.

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

| Metodo | Formato Output | Esempio |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helper per i Percorsi del Progetto

Getter per le directory standard del progetto {{ config('app.name') }}. Questi restituiscono percorsi relativi alla root del progetto.

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

| Proprieta' | Percorso |
|----------|------|
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
| `projectPath(String relativePath)` | Risolve un percorso relativo all'interno del progetto |

<div id="platform-helpers"></div>

## Helper per la Piattaforma

Controlla la piattaforma e accedi alle variabili d'ambiente.

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

| Proprieta' / Metodo | Descrizione |
|-------------------|-------------|
| `isWindows` | `true` se in esecuzione su Windows |
| `isMacOS` | `true` se in esecuzione su macOS |
| `isLinux` | `true` se in esecuzione su Linux |
| `workingDirectory` | Percorso della directory di lavoro corrente |
| `env(String key, [String defaultValue = ''])` | Legge una variabile d'ambiente di sistema |

<div id="dart-flutter-commands"></div>

## Comandi Dart e Flutter

Esegui comandi CLI comuni di Dart e Flutter come metodi helper. Ciascuno restituisce il codice di uscita del processo.

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

| Metodo | Descrizione |
|--------|-------------|
| `dartFormat(String path)` | Esegue `dart format` su un file o directory |
| `dartAnalyze([String? path])` | Esegue `dart analyze` |
| `flutterPubGet()` | Esegue `flutter pub get` |
| `flutterClean()` | Esegue `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Esegue `flutter build <target>` |
| `flutterTest([String? path])` | Esegue `flutter test` |

<div id="dart-file-manipulation"></div>

## Manipolazione File Dart

Helper per la modifica programmatica di file Dart, utili quando si costruiscono strumenti di scaffolding.

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

| Metodo | Descrizione |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Aggiunge un import al file Dart (salta se gia' presente) |
| `insertBeforeClosingBrace(String filePath, String code)` | Inserisce codice prima dell'ultima `}` nel file |
| `fileContains(String filePath, String identifier)` | Controlla se il file contiene una stringa |
| `fileContainsPattern(String filePath, Pattern pattern)` | Controlla se il file corrisponde a un pattern |

<div id="directory-helpers"></div>

## Helper per le Directory

Helper per lavorare con le directory e trovare file.

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

| Metodo | Descrizione |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Elenca i contenuti della directory |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Trova file corrispondenti ai criteri |
| `deleteDirectory(String path)` | Elimina la directory ricorsivamente |
| `copyDirectory(String source, String destination)` | Copia la directory ricorsivamente |

<div id="validation-helpers"></div>

## Helper per la Validazione

Helper per la validazione e la pulizia dell'input utente per la generazione di codice.

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

| Metodo | Descrizione |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Valida un nome di identificatore Dart |
| `requireArgument(CommandResult result, {String? message})` | Richiede un primo argomento non vuoto o interrompe |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Pulisce e converte in PascalCase un nome di classe |
| `cleanFileName(String name, {String extension = '.dart'})` | Pulisce e converte in snake_case un nome di file |

<div id="file-scaffolding"></div>

## Scaffolding File

Crea uno o piu' file con contenuto usando il sistema di scaffolding.

### File Singolo

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

### File Multipli

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

La classe `ScaffoldFile` accetta:

| Proprieta' | Tipo | Descrizione |
|----------|------|-------------|
| `path` | `String` | Percorso del file da creare |
| `content` | `String` | Contenuto del file |
| `successMessage` | `String?` | Messaggio mostrato in caso di successo |

<div id="task-runner"></div>

## Task Runner

Esegui una serie di attivita' denominate con output di stato automatico.

### Task Runner Base

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

### Task Runner con Spinner

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

La classe `CommandTask` accetta:

| Proprieta' | Tipo | Predefinito | Descrizione |
|----------|------|---------|-------------|
| `name` | `String` | obbligatorio | Nome dell'attivita' mostrato nell'output |
| `action` | `Future<void> Function()` | obbligatorio | Funzione asincrona da eseguire |
| `stopOnError` | `bool` | `true` | Se interrompere le attivita' rimanenti se questa fallisce |

<div id="table-output"></div>

## Output Tabellare

Visualizza tabelle ASCII formattate nella console.

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

Output:

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

## Barra di Avanzamento

Visualizza una barra di avanzamento per operazioni con un numero noto di elementi.

### Barra di Avanzamento Manuale

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

### Elaborare Elementi con Progresso

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

### Progresso Sincrono

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

La classe `ConsoleProgressBar` fornisce:

| Metodo | Descrizione |
|--------|-------------|
| `start()` | Avvia la barra di avanzamento |
| `tick([int amount = 1])` | Incrementa il progresso |
| `update(int value)` | Imposta il progresso a un valore specifico |
| `updateMessage(String newMessage)` | Cambia il messaggio visualizzato |
| `complete([String? completionMessage])` | Completa con messaggio opzionale |
| `stop()` | Ferma senza completare |
| `current` | Valore di progresso corrente (getter) |
| `percentage` | Progresso come percentuale (getter) |