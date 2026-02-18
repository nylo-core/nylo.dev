# Logging

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Poziomy logowania](#log-levels "Poziomy logowania")
- [Metody logowania](#log-methods "Metody logowania")
- [Logowanie JSON](#json-logging "Logowanie JSON")
- [Kolorowe wyjście](#colored-output "Kolorowe wyjście")
- [Listenery logów](#log-listeners "Listenery logów")
- [Rozszerzenia pomocnicze](#helper-extensions "Rozszerzenia pomocnicze")
- [Konfiguracja](#configuration "Konfiguracja")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 udostępnia kompleksowy system logowania.

Logi są wyświetlane tylko, gdy `APP_DEBUG=true` w pliku `.env`, utrzymując aplikacje produkcyjne w czystości.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Poziomy logowania

{{ config('app.name') }} v7 obsługuje wiele poziomów logowania z kolorowym wyjściem:

| Poziom | Metoda | Kolor | Zastosowanie |
|--------|--------|-------|--------------|
| Debug | `printDebug()` | Cyjanowy | Szczegółowe informacje debugowania |
| Info | `printInfo()` | Niebieski | Ogólne informacje |
| Error | `printError()` | Czerwony | Błędy i wyjątki |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Przykład wyjścia:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Metody logowania

### Podstawowe logowanie

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Błąd ze stosem wywołań

Loguj błędy ze stosami wywołań dla lepszego debugowania:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Wymuszanie wyświetlania niezależnie od trybu debugowania

Użyj `alwaysPrint: true`, aby wyświetlać nawet gdy `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Pokaż następny log (jednorazowe nadpisanie)

Wyświetl pojedynczy log, gdy `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Logowanie JSON

{{ config('app.name') }} v7 zawiera dedykowaną metodę logowania JSON:

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

## Kolorowe wyjście

{{ config('app.name') }} v7 używa kolorów ANSI dla wyjścia logów w trybie debugowania. Każdy poziom logowania ma odrębny kolor dla łatwej identyfikacji.

### Wyłączanie kolorów

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Kolory są automatycznie wyłączane:
- W trybie release
- Gdy terminal nie obsługuje kodów ucieczki ANSI

<div id="log-listeners"></div>

## Listenery logów

{{ config('app.name') }} v7 pozwala nasłuchiwać wszystkich wpisów logów w czasie rzeczywistym:

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

### Właściwości NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Przypadki użycia

- Wysyłanie błędów do serwisów raportowania awarii (Sentry, Firebase Crashlytics)
- Budowanie niestandardowych przeglądarek logów
- Przechowywanie logów do debugowania
- Monitorowanie zachowania aplikacji w czasie rzeczywistym

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

## Rozszerzenia pomocnicze

{{ config('app.name') }} udostępnia wygodne metody rozszerzeń do logowania:

### dump()

Wyświetl dowolną wartość w konsoli:

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

Wyświetl wartość i natychmiast zakończ działanie (przydatne do debugowania):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Konfiguracja

### Zmienne środowiskowe

Kontroluj zachowanie logowania w pliku `.env`:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### Data i czas w logach

{{ config('app.name') }} może dołączać znaczniki czasu do wyjścia logów. Skonfiguruj to w konfiguracji Nylo:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Wyjście ze znacznikami czasu:
```
[2025-01-27 10:30:45] [info] User logged in
```

Wyjście bez znaczników czasu:
```
[info] User logged in
```

### Najlepsze praktyki

1. **Używaj odpowiednich poziomów logowania** - Nie loguj wszystkiego jako błędy
2. **Usuń szczegółowe logi w produkcji** - Utrzymuj `APP_DEBUG=false` w produkcji
3. **Dołączaj kontekst** - Loguj istotne dane do debugowania
4. **Używaj strukturalnego logowania** - `NyLogger.json()` dla złożonych danych
5. **Skonfiguruj monitorowanie błędów** - Użyj `NyLogger.onLog` do przechwytywania błędów

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
