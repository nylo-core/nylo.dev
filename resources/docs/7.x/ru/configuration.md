# Configuration

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в конфигурацию")
- Окружение
  - [Файл .env](#env-file "Файл .env")
  - [Генерация конфигурации окружения](#generating-env "Генерация конфигурации окружения")
  - [Получение значений](#retrieving-values "Получение значений окружения")
  - [Создание классов конфигурации](#creating-config-classes "Создание классов конфигурации")
  - [Типы переменных](#variable-types "Типы переменных окружения")
- [Варианты окружения](#environment-flavours "Варианты окружения")
- [Внедрение на этапе сборки](#build-time-injection "Внедрение на этапе сборки")


<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 использует безопасную систему конфигурации окружения. Ваши переменные окружения хранятся в файле `.env`, а затем шифруются в сгенерированный Dart-файл (`env.g.dart`) для использования в приложении.

Этот подход обеспечивает:
- **Безопасность**: значения окружения шифруются XOR в скомпилированном приложении
- **Типобезопасность**: значения автоматически преобразуются в соответствующие типы
- **Гибкость на этапе сборки**: различные конфигурации для разработки, тестирования и продакшена

<div id="env-file"></div>

## Файл .env

Файл `.env` в корне вашего проекта содержит переменные конфигурации:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Доступные переменные

| Переменная | Описание |
|----------|-------------|
| `APP_KEY` | **Обязательно**. 32-символьный секретный ключ для шифрования |
| `APP_NAME` | Название вашего приложения |
| `APP_ENV` | Окружение: `developing` или `production` |
| `APP_DEBUG` | Включить режим отладки (`true`/`false`) |
| `APP_URL` | URL вашего приложения |
| `API_BASE_URL` | Базовый URL для API-запросов |
| `ASSET_PATH` | Путь к папке ресурсов |
| `DEFAULT_LOCALE` | Код языка по умолчанию |

<div id="generating-env"></div>

## Генерация конфигурации окружения

{{ config('app.name') }} v7 требует генерации зашифрованного файла окружения, прежде чем ваше приложение сможет обращаться к значениям env.

### Шаг 1: Генерация APP_KEY

Сначала сгенерируйте безопасный APP_KEY:

``` bash
metro make:key
```

Это добавит 32-символьный `APP_KEY` в ваш файл `.env`.

### Шаг 2: Генерация env.g.dart

Сгенерируйте зашифрованный файл окружения:

``` bash
metro make:env
```

Это создаст `lib/bootstrap/env.g.dart` с вашими зашифрованными переменными окружения.

Ваш env автоматически регистрируется при запуске приложения -- `Nylo.init(env: Env.get, ...)` в `main.dart` сделает это за вас. Дополнительная настройка не требуется.

### Перегенерация после изменений

При изменении файла `.env` перегенерируйте конфигурацию:

``` bash
metro make:env --force
```

Флаг `--force` перезаписывает существующий `env.g.dart`.

<div id="retrieving-values"></div>

## Получение значений

Рекомендуемый способ доступа к значениям окружения -- через **классы конфигурации**. Ваш файл `lib/config/app.dart` использует `getEnv()` для предоставления значений env как типизированных статических полей:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Затем в коде приложения обращайтесь к значениям через класс конфигурации:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Этот паттерн централизует доступ к env в ваших классах конфигурации. Помощник `getEnv()` следует использовать внутри классов конфигурации, а не напрямую в коде приложения.

<div id="creating-config-classes"></div>

## Создание классов конфигурации

Вы можете создавать пользовательские классы конфигурации для сторонних сервисов или конфигурации отдельных функций с помощью Metro:

``` bash
metro make:config RevenueCat
```

Это создаст новый файл конфигурации `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Пример: конфигурация RevenueCat

**Шаг 1:** Добавьте переменные окружения в файл `.env`:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Шаг 2:** Обновите класс конфигурации для использования этих значений:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Шаг 3:** Перегенерируйте конфигурацию окружения:

``` bash
metro make:env --force
```

**Шаг 4:** Используйте класс конфигурации в приложении:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

Этот подход обеспечивает безопасность и централизацию ваших API-ключей и значений конфигурации, упрощая управление различными значениями в разных окружениях.

<div id="variable-types"></div>

## Типы переменных

Значения в файле `.env` автоматически парсятся:

| Значение .env | Тип Dart | Пример |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (пустая строка) |


<div id="environment-flavours"></div>

## Варианты окружения

Создавайте различные конфигурации для разработки, тестирования и продакшена.

### Шаг 1: Создание файлов окружения

Создайте отдельные файлы `.env`:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Пример `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Шаг 2: Генерация конфигурации окружения

Генерация из конкретного файла env:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Шаг 3: Сборка приложения

Соберите приложение с соответствующей конфигурацией:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Внедрение на этапе сборки

Для повышенной безопасности вы можете внедрять APP_KEY на этапе сборки вместо его встраивания в исходный код.

### Генерация с режимом --dart-define

``` bash
metro make:env --dart-define
```

Это генерирует `env.g.dart` без встраивания APP_KEY.

### Сборка с внедрением APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Этот подход исключает APP_KEY из исходного кода, что полезно для:
- CI/CD-конвейеров, где секреты внедряются автоматически
- Проектов с открытым исходным кодом
- Повышенных требований безопасности

### Лучшие практики

1. **Никогда не коммитьте `.env` в систему контроля версий** -- добавьте его в `.gitignore`
2. **Используйте `.env-example`** -- коммитьте шаблон без конфиденциальных значений
3. **Перегенерируйте после изменений** -- всегда запускайте `metro make:env --force` после изменения `.env`
4. **Разные ключи для разных окружений** -- используйте уникальные APP_KEY для разработки, тестирования и продакшена
