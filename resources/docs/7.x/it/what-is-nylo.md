# Cos'è {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Sviluppo App
    - [Sei nuovo in Flutter?](#new-to-flutter "Sei nuovo in Flutter?")
    - [Manutenzione e Calendario dei Rilasci](#maintenance-and-release-schedule "Manutenzione e Calendario dei Rilasci")
- Crediti
    - [Dipendenze del Framework](#framework-dependencies "Dipendenze del Framework")
    - [Contributori](#contributors "Contributori")


<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} è un micro-framework per Flutter progettato per semplificare lo sviluppo di app. Fornisce un boilerplate strutturato con elementi essenziali preconfigurati, così puoi concentrarti sulla costruzione delle funzionalità della tua app invece di configurare l'infrastruttura.

Pronto all'uso, {{ config('app.name') }} include:

- **Routing** - Gestione delle rotte semplice e dichiarativa con guard e deep linking
- **Networking** - Servizi API con Dio, interceptor e morphing delle risposte
- **State Management** - Stato reattivo con NyState e aggiornamenti globali dello stato
- **Localizzazione** - Supporto multilingua con file di traduzione JSON
- **Temi** - Modalità chiara/scura con cambio tema
- **Storage Locale** - Storage sicuro con Backpack e NyStorage
- **Form** - Gestione form con validazione e tipi di campo
- **Notifiche Push** - Supporto notifiche locali e remote
- **Strumento CLI (Metro)** - Genera pagine, controller, modelli e altro

<div id="new-to-flutter"></div>

## Sei nuovo in Flutter?

Se sei nuovo in Flutter, inizia con le risorse ufficiali:

- <a href="https://flutter.dev" target="_BLANK">Documentazione Flutter</a> - Guide complete e riferimento API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Canale YouTube Flutter</a> - Tutorial e aggiornamenti
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Ricette pratiche per attività comuni

Una volta acquisita familiarità con le basi di Flutter, {{ config('app.name') }} risulterà intuitivo poiché si basa su pattern standard di Flutter.


<div id="maintenance-and-release-schedule"></div>

## Manutenzione e Calendario dei Rilasci

{{ config('app.name') }} segue il <a href="https://semver.org" target="_BLANK">Versionamento Semantico</a>:

- **Rilasci major** (7.x → 8.x) - Una volta l'anno per modifiche incompatibili
- **Rilasci minor** (7.0 → 7.1) - Nuove funzionalità, retrocompatibili
- **Rilasci patch** (7.0.0 → 7.0.1) - Correzioni bug e miglioramenti minori

Le correzioni di bug e le patch di sicurezza vengono gestite tempestivamente tramite i repository GitHub.


<div id="framework-dependencies"></div>

## Dipendenze del Framework

{{ config('app.name') }} v7 è costruito su questi pacchetti open source:

### Dipendenze Principali

| Pacchetto | Scopo |
|-----------|-------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | Client HTTP per richieste API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Storage locale sicuro |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internazionalizzazione e formattazione |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Estensioni reattive per stream |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Uguaglianza di valore per oggetti |

### UI e Widget

| Pacchetto | Scopo |
|-----------|-------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Effetti di caricamento skeleton |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Notifiche toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Funzionalità pull-to-refresh |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Layout griglia sfalsata |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Campi di selezione data |

### Notifiche e Connettività

| Pacchetto | Scopo |
|-----------|-------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Notifiche push locali |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Stato della connettività di rete |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Badge icona app |

### Utilità

| Pacchetto | Scopo |
|-----------|-------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Apertura URL e app |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Conversione case delle stringhe |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Generazione UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Percorsi del file system |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Mascheramento input |


<div id="contributors"></div>

## Contributori

Grazie a tutti coloro che hanno contribuito a {{ config('app.name') }}! Se hai contribuito, contattaci tramite <a href="mailto:support@nylo.dev">support@nylo.dev</a> per essere aggiunto qui.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Creatore)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
