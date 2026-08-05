# Installazione

---

<a name="section-1"></a>
- [Installa](#install "Installa")
- [Esecuzione del Progetto](#running-the-project "Esecuzione del Progetto")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Tre comandi ti portano da una cartella vuota a un'app Flutter funzionante, con routing, rete, temi e generazione del codice già configurati.

<x-doc-strip label="Prima di iniziare" items="Flutter SDK installato, Dart 3" linkText="Requisiti completi" linkHref="/it/docs/{{ $version }}/requirements" />

## Installa

Esegui questi comandi nell'ordine indicato. Ognuno può essere eseguito di nuovo in sicurezza.

<x-doc-steps>
<x-doc-step number="1" title="Installa la CLI Nylo globalmente">
Questo installa lo strumento CLI {{ config('app.name') }} globalmente sul tuo sistema.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Crea un nuovo progetto">
Questo comando clona il template {{ config('app.name') }}, configura il progetto con il nome della tua app e installa automaticamente tutte le dipendenze.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Configura l'alias Metro">
Questo configura il comando `metro` per il tuo progetto, permettendoti di usare i comandi Metro CLI senza la sintassi completa `dart run`.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Cosa ottieni" items="Routing e navigazione pre-configurati
Boilerplate del servizio API
Configurazione di tema e localizzazione
Metro CLI per la generazione del codice" />


<div id="running-the-project"></div>

## Esecuzione del Progetto

I progetti {{ config('app.name') }} si eseguono come qualsiasi app Flutter standard.

<x-doc-tabs tabs="Terminale, Android Studio, VS Code">
<x-doc-tab label="Terminale">

``` bash
flutter run
```

Se la build ha successo, l'app mostrera' la schermata di benvenuto predefinita di {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Apri la cartella del progetto, scegli un dispositivo dal selettore di destinazione, quindi premi **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Documentazione Flutter: esecuzione e debug ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Apri la cartella del progetto, quindi esegui **Debug: Start Without Debugging** dalla palette dei comandi.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Documentazione Flutter: esegui senza breakpoint ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro genera i file del progetto per te. Avvialo senza argomenti per visualizzare il menu oppure richiama direttamente un comando.

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

### Riferimento comandi

Ogni comando accetta un nome, ad esempio `metro make:page settings_page`

<x-doc-commands title="Comandi per widget" rows="
metro make:page | Crea una nuova pagina
metro make:stateful_widget | Crea un widget stateful
metro make:stateless_widget | Crea un widget stateless
metro make:state_managed_widget | Crea un widget con gestione dello stato
metro make:navigation_hub | Crea un navigation hub (navigazione inferiore)
metro make:journey_widget | Crea un widget journey per il navigation hub
metro make:bottom_sheet_modal | Crea un modal bottom sheet
metro make:button | Crea un widget pulsante personalizzato
metro make:form | Crea un form con validazione
" />

<x-doc-commands title="Comandi di supporto" rows="
metro make:model | Crea una classe modello
metro make:provider | Crea un provider
metro make:api_service | Crea un servizio API
metro make:controller | Crea un controller
metro make:event | Crea un evento
metro make:route_guard | Crea un route guard
metro make:config | Crea un file di configurazione
metro make:interceptor | Crea un interceptor di rete
metro make:command | Crea un comando Metro personalizzato
metro make:env | Genera la configurazione dell'ambiente dal .env
" />

### Esempio di Utilizzo

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
