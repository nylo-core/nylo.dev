# Logging

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Livelli di Log](#log-levels "Livelli di log")
- [Metodi di Log](#log-methods "Metodi di log")
- [Log JSON](#json-logging "Log JSON")
- [Output Colorato](#colored-output "Output colorato")
- [Listener dei Log](#log-listeners "Listener dei log")
- [Estensioni Helper](#helper-extensions "Estensioni helper")
- [Configurazione](#configuration "Configurazione")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce un sistema di logging completo.

I log vengono stampati solo quando `APP_DEBUG=true` nel tuo file `.env`, mantenendo pulite le app in produzione.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Livelli di Log

{{ config('app.name') }} v7 supporta molteplici livelli di log con output colorato:

| Livello | Metodo | Colore | Caso d'Uso |
|---------|--------|--------|------------|
| Debug | `printDebug()` | Ciano | Informazioni di debug dettagliate |
| Info | `printInfo()` | Blu | Informazioni generali |
| Error | `printError()` | Rosso | Errori ed eccezioni |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Esempio di output:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Metodi di Log

### Logging Base

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Errore con Stack Trace

Registra errori con stack trace per un debug migliore:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Stampa Forzata Indipendentemente dalla Modalita' Debug

Usa `alwaysPrint: true` per stampare anche quando `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Mostra il Prossimo Log (Override Una Tantum)

Stampa un singolo log quando `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Log JSON

{{ config('app.name') }} v7 include un metodo di logging JSON dedicato:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// Compact JSON
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// Pretty printed JSON
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## Output Colorato

{{ config('app.name') }} v7 utilizza colori ANSI per l'output dei log in modalita' debug. Ogni livello di log ha un colore distinto per una facile identificazione.

### Disabilitare i Colori

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

I colori vengono disabilitati automaticamente:
- In modalita' release
- Quando il terminale non supporta i codici di escape ANSI

<div id="log-listeners"></div>

## Listener dei Log

{{ config('app.name') }} v7 ti permette di ascoltare tutte le voci di log in tempo reale:

``` dart
// Set up a log listener
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // Send to crash reporting service
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### Proprieta' di NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Casi d'Uso

- Inviare errori a servizi di crash reporting (Sentry, Firebase Crashlytics)
- Costruire visualizzatori di log personalizzati
- Memorizzare i log per il debug
- Monitorare il comportamento dell'app in tempo reale

``` dart
// Example: Send errors to Sentry
NyLogger.onLog = (entry) {
  if (entry.type == 'error') {
    Sentry.captureMessage(
      entry.message,
      level: SentryLevel.error,
    );
  }
};
```

<div id="helper-extensions"></div>

## Estensioni Helper

{{ config('app.name') }} fornisce metodi di estensione convenienti per il logging:

### dump()

Stampa qualsiasi valore nella console:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// Function syntax
dump("Hello World");
```

### dd() - Dump and Die

Stampa un valore e termina immediatamente l'esecuzione (utile per il debug):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Configurazione

### Variabili d'Ambiente

Controlla il comportamento del logging nel tuo file `.env`:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime nei Log

{{ config('app.name') }} puo' includere timestamp nell'output dei log. Configuralo nel setup di Nylo:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Output con timestamp:
```
[2025-01-27 10:30:45] [info] User logged in
```

Output senza timestamp:
```
[info] User logged in
```

### Buone Pratiche

1. **Usa livelli di log appropriati** - Non registrare tutto come errori
2. **Rimuovi i log verbose in produzione** - Mantieni `APP_DEBUG=false` in produzione
3. **Includi il contesto** - Registra dati rilevanti per il debug
4. **Usa il logging strutturato** - `NyLogger.json()` per dati complessi
5. **Imposta il monitoraggio degli errori** - Usa `NyLogger.onLog` per catturare gli errori

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

