# Logging

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Niveis de Log](#log-levels "Niveis de Log")
- [Metodos de Log](#log-methods "Metodos de Log")
- [Log JSON](#json-logging "Log JSON")
- [Saida Colorida](#colored-output "Saida Colorida")
- [Listeners de Log](#log-listeners "Listeners de Log")
- [Extensoes Auxiliares](#helper-extensions "Extensoes Auxiliares")
- [Configuracao](#configuration "Configuracao")

<div id="introduction"></div>

## Introducao

{{ config('app.name') }} v7 fornece um sistema de logging abrangente.

Os logs sao impressos apenas quando `APP_DEBUG=true` no seu arquivo `.env`, mantendo os apps em producao limpos.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Niveis de Log

{{ config('app.name') }} v7 suporta multiplos niveis de log com saida colorida:

| Nivel | Metodo | Cor | Caso de Uso |
|-------|--------|-----|-------------|
| Debug | `printDebug()` | Ciano | Informacoes detalhadas de depuracao |
| Info | `printInfo()` | Azul | Informacoes gerais |
| Error | `printError()` | Vermelho | Erros e excecoes |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Exemplo de saida:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Metodos de Log

### Log Basico

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Erro com Stack Trace

Registre erros com stack traces para melhor depuracao:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Forcar Impressao Independente do Modo Debug

Use `alwaysPrint: true` para imprimir mesmo quando `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Mostrar Proximo Log (Substituicao Unica)

Imprima um unico log quando `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Log JSON

{{ config('app.name') }} v7 inclui um metodo dedicado para logging JSON:

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

## Saida Colorida

{{ config('app.name') }} v7 usa cores ANSI para saida de log no modo debug. Cada nivel de log tem uma cor distinta para facil identificacao.

### Desabilitar Cores

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

As cores sao desabilitadas automaticamente:
- No modo release
- Quando o terminal nao suporta codigos de escape ANSI

<div id="log-listeners"></div>

## Listeners de Log

{{ config('app.name') }} v7 permite que voce escute todas as entradas de log em tempo real:

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

### Propriedades de NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Casos de Uso

- Enviar erros para servicos de relatorios de falhas (Sentry, Firebase Crashlytics)
- Construir visualizadores de log personalizados
- Armazenar logs para depuracao
- Monitorar o comportamento do app em tempo real

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

## Extensoes Auxiliares

{{ config('app.name') }} fornece metodos de extensao convenientes para logging:

### dump()

Imprima qualquer valor no console:

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

Imprima um valor e saia imediatamente (util para depuracao):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Configuracao

### Variaveis de Ambiente

Controle o comportamento de logging no seu arquivo `.env`:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime nos Logs

{{ config('app.name') }} pode incluir timestamps na saida de log. Configure isso no setup do Nylo:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Saida com timestamps:
```
[2025-01-27 10:30:45] [info] User logged in
```

Saida sem timestamps:
```
[info] User logged in
```

### Boas Praticas

1. **Use niveis de log apropriados** - Nao registre tudo como erros
2. **Remova logs verbosos em producao** - Mantenha `APP_DEBUG=false` em producao
3. **Inclua contexto** - Registre dados relevantes para depuracao
4. **Use logging estruturado** - `NyLogger.json()` para dados complexos
5. **Configure monitoramento de erros** - Use `NyLogger.onLog` para capturar erros

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
