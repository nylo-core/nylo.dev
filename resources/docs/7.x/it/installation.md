# Installazione

---

<a name="section-1"></a>
- [Installa](#install "Installa")
- [Esecuzione del Progetto](#running-the-project "Esecuzione del Progetto")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Installa

### 1. Installa nylo_installer globalmente

``` bash
dart pub global activate nylo_installer
```

Questo installa lo strumento CLI {{ config('app.name') }} globalmente sul tuo sistema.

### 2. Crea un nuovo progetto

``` bash
nylo new my_app
```

Questo comando clona il template {{ config('app.name') }}, configura il progetto con il nome della tua app e installa automaticamente tutte le dipendenze.

### 3. Configura l'alias Metro CLI

``` bash
cd my_app
nylo init
```

Questo configura il comando `metro` per il tuo progetto, permettendoti di usare i comandi Metro CLI senza la sintassi completa `dart run`.

Dopo l'installazione, avrai una struttura di progetto Flutter completa con:
- Routing e navigazione pre-configurati
- Boilerplate del servizio API
- Configurazione di tema e localizzazione
- Metro CLI per la generazione del codice


<div id="running-the-project"></div>

## Esecuzione del Progetto

I progetti {{ config('app.name') }} si eseguono come qualsiasi app Flutter standard.

### Usando il Terminale

``` bash
flutter run
```

### Usando un IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Esecuzione e debug</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Esegui app senza breakpoint</a>

Se la build ha successo, l'app mostrera' la schermata di benvenuto predefinita di {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} include uno strumento CLI chiamato **Metro** per generare file di progetto.

### Esecuzione di Metro

``` bash
metro
```

Questo mostra il menu di Metro:

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

### Riferimento Comandi Metro

| Comando | Descrizione |
|---------|-------------|
| `make:page` | Crea una nuova pagina |
| `make:stateful_widget` | Crea un widget stateful |
| `make:stateless_widget` | Crea un widget stateless |
| `make:state_managed_widget` | Crea un widget con gestione dello stato |
| `make:navigation_hub` | Crea un navigation hub (navigazione inferiore) |
| `make:journey_widget` | Crea un widget journey per il navigation hub |
| `make:bottom_sheet_modal` | Crea un modal bottom sheet |
| `make:button` | Crea un widget pulsante personalizzato |
| `make:form` | Crea un form con validazione |
| `make:model` | Crea una classe modello |
| `make:provider` | Crea un provider |
| `make:api_service` | Crea un servizio API |
| `make:controller` | Crea un controller |
| `make:event` | Crea un evento |
| `make:theme` | Crea un tema |
| `make:route_guard` | Crea un route guard |
| `make:config` | Crea un file di configurazione |
| `make:interceptor` | Crea un interceptor di rete |
| `make:command` | Crea un comando Metro personalizzato |
| `make:env` | Genera la configurazione dell'ambiente dal .env |

### Esempio di Utilizzo

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
