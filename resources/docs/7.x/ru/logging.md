# Logging

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Уровни логирования](#log-levels "Уровни логирования")
- [Методы логирования](#log-methods "Методы логирования")
- [Логирование JSON](#json-logging "Логирование JSON")
- [Цветной вывод](#colored-output "Цветной вывод")
- [Слушатели логов](#log-listeners "Слушатели логов")
- [Вспомогательные расширения](#helper-extensions "Вспомогательные расширения")
- [Конфигурация](#configuration "Конфигурация")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет комплексную систему логирования.

Логи выводятся только когда `APP_DEBUG=true` в вашем файле `.env`, сохраняя чистоту продакшен-приложений.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Базовое логирование
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Уровни логирования

{{ config('app.name') }} v7 поддерживает несколько уровней логирования с цветным выводом:

| Уровень | Метод | Цвет | Применение |
|---------|-------|------|------------|
| Debug | `printDebug()` | Голубой | Подробная отладочная информация |
| Info | `printInfo()` | Синий | Общая информация |
| Error | `printError()` | Красный | Ошибки и исключения |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Пример вывода:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Методы логирования

### Базовое логирование

``` dart
// Методы класса
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Ошибка со стеком вызовов

Логирование ошибок со стеком вызовов для лучшей отладки:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Принудительный вывод независимо от режима отладки

Используйте `alwaysPrint: true` для вывода даже когда `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Показать следующий лог (одноразовое переопределение)

Вывести один лог когда `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Выведется один раз

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Логирование JSON

{{ config('app.name') }} v7 включает специальный метод логирования JSON:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// Компактный JSON
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// Форматированный JSON
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## Цветной вывод

{{ config('app.name') }} v7 использует цвета ANSI для вывода логов в режиме отладки. Каждый уровень логирования имеет свой цвет для удобной идентификации.

### Отключение цветов

``` dart
// Глобальное отключение цветного вывода
NyLogger.useColors = false;
```

Цвета автоматически отключаются:
- В режиме релиза
- Когда терминал не поддерживает escape-коды ANSI

<div id="log-listeners"></div>

## Слушатели логов

{{ config('app.name') }} v7 позволяет прослушивать все записи логов в реальном времени:

``` dart
// Настройка слушателя логов
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // Отправка в сервис отслеживания сбоев
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### Свойства NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // Сообщение лога
  entry.type;       // Уровень лога (debug, info, warning, error, success, verbose)
  entry.dateTime;   // Когда лог был создан
  entry.stackTrace; // Стек вызовов (для ошибок)
};
```

### Варианты использования

- Отправка ошибок в сервисы отслеживания сбоев (Sentry, Firebase Crashlytics)
- Создание пользовательских просмотрщиков логов
- Сохранение логов для отладки
- Мониторинг поведения приложения в реальном времени

``` dart
// Пример: Отправка ошибок в Sentry
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

## Вспомогательные расширения

{{ config('app.name') }} предоставляет удобные методы-расширения для логирования:

### dump()

Вывод любого значения в консоль:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// Синтаксис функции
dump("Hello World");
```

### dd() — Вывести и остановить

Вывод значения и немедленная остановка выполнения (полезно для отладки):

``` dart
String code = 'Dart';
code.dd(); // Выводит 'Dart' и останавливает выполнение

// Синтаксис функции
dd("Debug point reached");
```

<div id="configuration"></div>

## Конфигурация

### Переменные окружения

Управление поведением логирования в вашем файле `.env`:

``` bash
# Включить/отключить всё логирование
APP_DEBUG=true
```

### Дата и время в логах

{{ config('app.name') }} может включать временные метки в вывод логов. Настройте это в конфигурации Nylo:

``` dart
// В вашем boot-провайдере
Nylo.instance.showDateTimeInLogs(true);
```

Вывод с временными метками:
```
[2025-01-27 10:30:45] [info] User logged in
```

Вывод без временных меток:
```
[info] User logged in
```

### Лучшие практики

1. **Используйте подходящие уровни логирования** — Не логируйте всё как ошибки
2. **Убирайте подробные логи в продакшене** — Держите `APP_DEBUG=false` в продакшене
3. **Добавляйте контекст** — Логируйте релевантные данные для отладки
4. **Используйте структурированное логирование** — `NyLogger.json()` для сложных данных
5. **Настройте мониторинг ошибок** — Используйте `NyLogger.onLog` для отслеживания ошибок

``` dart
// Хорошая практика логирования
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
