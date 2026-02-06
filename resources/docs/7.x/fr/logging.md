# Journalisation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Niveaux de log](#log-levels "Niveaux de log")
- [Methodes de log](#log-methods "Methodes de log")
- [Journalisation JSON](#json-logging "Journalisation JSON")
- [Sortie coloree](#colored-output "Sortie coloree")
- [Ecouteurs de logs](#log-listeners "Ecouteurs de logs")
- [Extensions d'aide](#helper-extensions "Extensions d'aide")
- [Configuration](#configuration "Configuration")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit un systeme de journalisation complet.

Les logs ne sont affiches que lorsque `APP_DEBUG=true` dans votre fichier `.env`, gardant les applications en production propres.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Niveaux de log

{{ config('app.name') }} v7 prend en charge plusieurs niveaux de log avec une sortie coloree :

| Niveau | Methode | Couleur | Cas d'utilisation |
|--------|---------|---------|-------------------|
| Debug | `printDebug()` | Cyan | Informations de debogage detaillees |
| Info | `printInfo()` | Bleu | Informations generales |
| Error | `printError()` | Rouge | Erreurs et exceptions |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Exemple de sortie :
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Methodes de log

### Journalisation de base

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Erreur avec trace de pile

Journalisez les erreurs avec les traces de pile pour un meilleur debogage :

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Forcer l'affichage independamment du mode debug

Utilisez `alwaysPrint: true` pour afficher meme lorsque `APP_DEBUG=false` :

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Afficher le prochain log (remplacement ponctuel)

Afficher un seul log lorsque `APP_DEBUG=false` :

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Journalisation JSON

{{ config('app.name') }} v7 inclut une methode de journalisation JSON dediee :

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

## Sortie coloree

{{ config('app.name') }} v7 utilise les couleurs ANSI pour la sortie des logs en mode debug. Chaque niveau de log a une couleur distincte pour une identification facile.

### Desactiver les couleurs

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Les couleurs sont automatiquement desactivees :
- En mode release
- Lorsque le terminal ne prend pas en charge les codes d'echappement ANSI

<div id="log-listeners"></div>

## Ecouteurs de logs

{{ config('app.name') }} v7 vous permet d'ecouter toutes les entrees de log en temps reel :

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

### Proprietes de NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Cas d'utilisation

- Envoyer les erreurs aux services de rapport de crash (Sentry, Firebase Crashlytics)
- Construire des visualiseurs de logs personnalises
- Stocker les logs pour le debogage
- Surveiller le comportement de l'application en temps reel

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

## Extensions d'aide

{{ config('app.name') }} fournit des methodes d'extension pratiques pour la journalisation :

### dump()

Afficher n'importe quelle valeur dans la console :

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

Afficher une valeur et quitter immediatement (utile pour le debogage) :

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Configuration

### Variables d'environnement

Controlez le comportement de la journalisation dans votre fichier `.env` :

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime dans les logs

{{ config('app.name') }} peut inclure des horodatages dans la sortie des logs. Configurez cela dans votre configuration Nylo :

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Sortie avec horodatages :
```
[2025-01-27 10:30:45] [info] User logged in
```

Sortie sans horodatages :
```
[info] User logged in
```

### Bonnes pratiques

1. **Utilisez les niveaux de log appropries** - Ne journalisez pas tout comme des erreurs
2. **Supprimez les logs verbeux en production** - Gardez `APP_DEBUG=false` en production
3. **Incluez le contexte** - Journalisez les donnees pertinentes pour le debogage
4. **Utilisez la journalisation structuree** - `NyLogger.json()` pour les donnees complexes
5. **Configurez la surveillance des erreurs** - Utilisez `NyLogger.onLog` pour capturer les erreurs

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

