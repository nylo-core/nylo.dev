# Logging

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- [Niveles de registro](#log-levels "Niveles de registro")
- [Métodos de registro](#log-methods "Métodos de registro")
- [Registro JSON](#json-logging "Registro JSON")
- [Salida con color](#colored-output "Salida con color")
- [Oyentes de registro](#log-listeners "Oyentes de registro")
- [Extensiones helper](#helper-extensions "Extensiones helper")
- [Configuración](#configuration "Configuración")

<div id="introduction"></div>

## Introducción

{{ config('app.name') }} v7 proporciona un sistema de registro completo.

Los registros solo se imprimen cuando `APP_DEBUG=true` en tu archivo `.env`, manteniendo las aplicaciones en producción limpias.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Registro básico
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Niveles de registro

{{ config('app.name') }} v7 soporta múltiples niveles de registro con salida coloreada:

| Nivel | Método | Color | Caso de uso |
|-------|--------|-------|-------------|
| Debug | `printDebug()` | Cian | Información detallada de depuración |
| Info | `printInfo()` | Azul | Información general |
| Error | `printError()` | Rojo | Errores y excepciones |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Ejemplo de salida:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Métodos de registro

### Registro básico

``` dart
// Métodos de clase
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Error con traza de pila

Registra errores con trazas de pila para mejor depuración:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Forzar impresión sin importar el modo de depuración

Usa `alwaysPrint: true` para imprimir incluso cuando `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Mostrar siguiente registro (anulación única)

Imprime un solo registro cuando `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Se imprime una vez

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Registro JSON

{{ config('app.name') }} v7 incluye un método dedicado de registro JSON:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// JSON compacto
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// JSON con formato legible
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## Salida con color

{{ config('app.name') }} v7 usa colores ANSI para la salida de registro en modo de depuración. Cada nivel de registro tiene un color distinto para fácil identificación.

### Deshabilitar colores

``` dart
// Deshabilitar salida coloreada globalmente
NyLogger.useColors = false;
```

Los colores se deshabilitan automáticamente:
- En modo release
- Cuando la terminal no soporta códigos de escape ANSI

<div id="log-listeners"></div>

## Oyentes de registro

{{ config('app.name') }} v7 te permite escuchar todas las entradas de registro en tiempo real:

``` dart
// Configurar un oyente de registro
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // Enviar al servicio de reportes de errores
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### Propiedades de NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // El mensaje del registro
  entry.type;       // Nivel del registro (debug, info, warning, error, success, verbose)
  entry.dateTime;   // Cuándo se creó el registro
  entry.stackTrace; // Traza de pila (para errores)
};
```

### Casos de uso

- Enviar errores a servicios de reportes de errores (Sentry, Firebase Crashlytics)
- Construir visualizadores de registros personalizados
- Almacenar registros para depuración
- Monitorear el comportamiento de la aplicación en tiempo real

``` dart
// Ejemplo: Enviar errores a Sentry
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

## Extensiones helper

{{ config('app.name') }} proporciona métodos de extensión convenientes para registro:

### dump()

Imprime cualquier valor en la consola:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// Sintaxis de función
dump("Hello World");
```

### dd() - Dump and Die

Imprime un valor e inmediatamente termina la ejecución (útil para depuración):

``` dart
String code = 'Dart';
code.dd(); // Imprime 'Dart' y detiene la ejecución

// Sintaxis de función
dd("Debug point reached");
```

<div id="configuration"></div>

## Configuración

### Variables de entorno

Controla el comportamiento del registro en tu archivo `.env`:

``` bash
# Habilitar/deshabilitar todo el registro
APP_DEBUG=true
```

### Fecha y hora en registros

{{ config('app.name') }} puede incluir marcas de tiempo en la salida de registro. Configura esto en tu configuración de Nylo:

``` dart
// En tu proveedor de arranque
Nylo.instance.showDateTimeInLogs(true);
```

Salida con marcas de tiempo:
```
[2025-01-27 10:30:45] [info] User logged in
```

Salida sin marcas de tiempo:
```
[info] User logged in
```

### Mejores prácticas

1. **Usa niveles de registro apropiados** - No registres todo como errores
2. **Elimina registros verbosos en producción** - Mantén `APP_DEBUG=false` en producción
3. **Incluye contexto** - Registra datos relevantes para depuración
4. **Usa registro estructurado** - `NyLogger.json()` para datos complejos
5. **Configura monitoreo de errores** - Usa `NyLogger.onLog` para capturar errores

``` dart
// Buena práctica de registro
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
