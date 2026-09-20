# Installazione

---

<a name="section-1"></a>
- [Installa](#install "Installa")
- [Esecuzione del Progetto](#running-the-project "Esecuzione del Progetto")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [Pulizia di un progetto](#nylo-clean "Pulizia di un progetto")
  - [Aggiornamento dei pod iOS](#nylo-ios-pod-refresh "Aggiornamento dei pod iOS")
  - [Esecuzione di Metro senza l'alias](#nylo-metro "Esecuzione di Metro senza l'alias")
  - [Aggiornamento della CLI](#nylo-self-update "Aggiornamento della CLI")

<div id="install"></div>

Tre comandi ti portano da una cartella vuota a un'app Flutter funzionante, con routing, rete, temi e generazione del codice già configurati.

<x-doc-strip label="Prima di iniziare" items="Flutter SDK installato, Dart 3" linkText="Requisiti completi" linkHref="/it/docs/{{ $version }}/requirements" />

## Installa

Esegui questi comandi nell'ordine indicato. Ognuno può essere eseguito di nuovo in sicurezza.

<x-doc-steps>
<x-doc-step number="1" title="Installa la CLI Nylo globalmente">
Installa il comando `nylo` sul tuo sistema.

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

Al termine, riavvia il terminale oppure esegui il comando `source` stampato da `nylo init`.
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


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: no established it rendering yet for the x-doc-strip label "Requires" -->
<x-doc-strip label="Richiede" items="nylo_installer 1.8.0+" linkText="Aggiorna la CLI" linkHref="#nylo-self-update" />

Il comando `nylo` del passaggio 1 fa molto più che creare progetti. Eseguilo dalla directory del tuo progetto per pulire le build, aggiornare i pod iOS, eseguire i test e controllare le tue traduzioni.

<x-doc-commands title="Comandi del progetto" rows="
nylo new | Crea un nuovo progetto
nylo init | Configura l'alias metro nella tua shell
nylo clean | Esegui flutter clean e flutter pub get
nylo ios:pod-refresh | Reinstalla i tuoi pod iOS
nylo metro | Esegui un comando Metro senza l'alias
" />

<x-doc-commands title="Test e traduzioni" rows="
nylo test | Formatta ed esegui i tuoi test
nylo locale:find-untranslated | Trova il testo non racchiuso in .tr()
nylo locale:check-missing-keys | Trova le chiavi mancanti nei tuoi file di lingua
" />

<x-doc-commands title="La CLI stessa" rows="
nylo self-update | Aggiorna nylo all'ultima versione
nylo --version | Mostra la versione installata
" />

I comandi che accettano opzioni le elencano con `-h`, ad esempio `nylo test -h`. Il testing è trattato in [Esecuzione dei Test](/docs/{{ $version }}/testing#running-tests), mentre i comandi `locale:` in [Audit delle Traduzioni](/docs/{{ $version }}/localization#auditing-translations).

<div id="nylo-clean"></div>

### Pulizia di un progetto

`nylo clean` esegue `flutter clean` e poi `flutter pub get`. Aggiungi un flag di piattaforma quando una pulizia normale non basta:

``` bash
# flutter clean, poi flutter pub get
nylo clean

# Rimuove anche ios/Pods, ios/.symlinks e ios/Podfile.lock, poi esegue pod install --repo-update
nylo clean --ios

# Esegue anche gradlew clean in android/
nylo clean --android

# Entrambe le opzioni sopra
nylo clean --all
```

Se un progetto non ha una cartella `ios/` o `android/`, quella piattaforma viene saltata con un avviso. `pod install` viene eseguito solo su macOS.

<div id="nylo-ios-pod-refresh"></div>

### Aggiornamento dei pod iOS

Quando CocoaPods si desincronizza, `nylo ios:pod-refresh` elimina `ios/Pods`, `ios/.symlinks` e `ios/Podfile.lock`, poi esegue `pod install --repo-update` nella cartella `ios/`:

``` bash
nylo ios:pod-refresh
```

Eseguilo dalla radice del tuo progetto su macOS. A differenza di `nylo clean --ios`, non esegue `flutter clean` né `flutter pub get`.

<div id="nylo-metro"></div>

### Esecuzione di Metro senza l'alias

`nylo metro` passa tutto ciò che segue a Metro (`dart run nylo_framework:main`). Usalo prima di aver eseguito `nylo init`, oppure su Windows, dove `nylo init` non può aggiungere l'alias `metro` per te:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### Aggiornamento della CLI

``` bash
nylo self-update
```

Questo controlla pub.dev e reinstalla `nylo_installer` se è disponibile una versione più recente. Al termine di un comando, `nylo` ti avvisa anche quando è uscito un aggiornamento. Resta silenzioso quando l'output non è diretto a un terminale, così l'avviso non finisce mai nei log CI o negli output reindirizzati.

Per vedere quale versione hai installato:

``` bash
nylo --version
```
