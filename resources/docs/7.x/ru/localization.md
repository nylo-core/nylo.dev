# Localization

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в локализацию")
- [Конфигурация](#configuration "Конфигурация")
- [Добавление файлов локализации](#adding-localized-files "Добавление файлов локализации")
- Основы
  - [Локализация текста](#localizing-text "Локализация текста")
    - [Аргументы](#arguments "Аргументы")
    - [Заполнители StyledText](#styled-text-placeholders "Заполнители StyledText")
  - [Обновление локали](#updating-the-locale "Обновление локали")
  - [Установка локали по умолчанию](#setting-a-default-locale "Установка локали по умолчанию")
- Продвинутое использование
  - [Поддерживаемые локали](#supported-locales "Поддерживаемые локали")
  - [Резервный язык](#fallback-language "Резервный язык")
  - [Поддержка RTL](#rtl-support "Поддержка RTL")
  - [Отладка отсутствующих ключей](#debug-missing-keys "Отладка отсутствующих ключей")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Утилитарный класс NyLocaleHelper")
  - [Смена языка из контроллера](#changing-language-from-controller "Смена языка из контроллера")


<div id="introduction"></div>

## Введение

Локализация позволяет предоставлять ваше приложение на нескольких языках. {{ config('app.name') }} v7 упрощает локализацию текста с помощью JSON-файлов языков.

Вот краткий пример:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**В вашем виджете:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Конфигурация

Локализация настраивается в `lib/config/localization.dart`:

``` dart
final class LocalizationConfig {
  // Код языка по умолчанию (соответствует вашему JSON-файлу, например, 'en' для lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Использовать настройку языка устройства
  // LocaleType.asDefined - Использовать languageCode выше
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Директория с JSON-файлами языков
  static const String assetsDirectory = 'lang/';

  // Список поддерживаемых локалей
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Добавьте другие локали при необходимости
  ];

  // Резервный язык, когда ключ не найден в активной локали
  static const String fallbackLanguageCode = 'en';

  // Коды RTL-языков
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Логировать предупреждения об отсутствующих ключах перевода
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## Добавление файлов локализации

Добавьте JSON-файлы языков в директорию `lang/`:

```
lang/
├── en.json   # Английский
├── es.json   # Испанский
├── fr.json   # Французский
└── ...
```

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "settings": "Settings",
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

**lang/es.json**
``` json
{
  "welcome": "Bienvenido",
  "settings": "Configuración",
  "navigation": {
    "home": "Inicio",
    "profile": "Perfil"
  }
}
```

### Регистрация в pubspec.yaml

Убедитесь, что ваши языковые файлы включены в `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Локализация текста

Используйте расширение `.tr()` или помощник `trans()` для перевода строк:

``` dart
// Используя расширение .tr()
"welcome".tr()

// Используя помощник trans()
trans("welcome")
```

### Вложенные ключи

Доступ к вложенным JSON-ключам через точечную нотацию:

**lang/en.json**
``` json
{
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

``` dart
"navigation.home".tr()       // "Home"
trans("navigation.profile")  // "Profile"
```

<div id="arguments"></div>

### Аргументы

Передавайте динамические значения в переводы с помощью синтаксиса `@{{key}}`:

**lang/en.json**
``` json
{
  "greeting": "Hello @{{name}}",
  "items_count": "You have @{{count}} items"
}
```

``` dart
"greeting".tr(arguments: {"name": "Anthony"})
// "Hello Anthony"

trans("items_count", arguments: {"count": "5"})
// "You have 5 items"
```

<div id="styled-text-placeholders"></div>

### Заполнители StyledText

При использовании `StyledText.template` с локализованными строками вы можете применять синтаксис `@{{key:text}}`. Это сохраняет **ключ** стабильным во всех локалях (чтобы стили и обработчики нажатий всегда совпадали), а **текст** переводится для каждой локали.

**lang/en.json**
``` json
{
  "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} skills",
  "already_have_account": "Already have an account? @{{login:Login}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? @{{login:Iniciar sesión}}"
}
```

**В вашем виджете:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

Ключи `lang`, `read` и `speak` одинаковы в каждом файле локали, поэтому карта стилей работает для всех языков. Отображаемый текст после `:` -- это то, что видит пользователь: "Languages" на английском, "Idiomas" на испанском и т.д.

Вы также можете использовать это с `onTap`:

``` dart
StyledText.template(
  "already_have_account".tr(),
  styles: {
    "login": TextStyle(fontWeight: FontWeight.bold),
  },
  onTap: {
    "login": () => routeTo(LoginPage.path),
  },
)
```

> **Примечание:** Синтаксис `@{{key}}` (с префиксом `@`) предназначен для аргументов, заменяемых `.tr(arguments:)` во время перевода. Синтаксис `@{{key:text}}` (без `@`) предназначен для заполнителей `StyledText`, обрабатываемых при отрисовке. Не путайте их -- используйте `@{{}}` для динамических значений и `@{{}}` для стилизованных фрагментов.

<div id="updating-the-locale"></div>

## Обновление локали

Изменение языка приложения во время выполнения:

``` dart
// Используя NyLocalization напрямую
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Должен совпадать с именем вашего JSON-файла (es.json)
);
```

Если ваш виджет наследует `NyPage`, используйте помощник `changeLanguage`:

``` dart
class _SettingsPageState extends NyPage<SettingsPage> {

  @override
  Widget view(BuildContext context) {
    return ListView(
      children: [
        ListTile(
          title: Text("English"),
          onTap: () => changeLanguage('en'),
        ),
        ListTile(
          title: Text("Español"),
          onTap: () => changeLanguage('es'),
        ),
      ],
    );
  }
}
```

<div id="setting-a-default-locale"></div>

## Установка локали по умолчанию

Установите язык по умолчанию в файле `.env`:

``` bash
DEFAULT_LOCALE="en"
```

Или используйте локаль устройства:

``` bash
LOCALE_TYPE="device"
```

После изменения `.env` пересгенерируйте конфигурацию окружения:

``` bash
metro make:env
```

<div id="supported-locales"></div>

## Поддерживаемые локали

Определите, какие локали поддерживает ваше приложение, в `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Этот список используется в `MaterialApp.supportedLocales` Flutter.

<div id="fallback-language"></div>

## Резервный язык

Когда ключ перевода не найден в активной локали, {{ config('app.name') }} автоматически ищет его в резервном языке перед возвратом необработанного ключа. Резервный язык настраивается в `lib/config/localization.dart`:

``` dart
static const String fallbackLanguageCode = 'en';
```

Это двухступенчатое разрешение применяется как к ключам верхнего уровня, так и к вложенным ключам с точечной нотацией:

1. Поиск ключа в файле активной локали.
2. Если не найден, поиск в файле резервной локали.
3. Если всё ещё не найден, возврат необработанной строки ключа.

Например, если в файле французской локали отсутствует ключ `settings.privacy`, логика резервного языка ищет `settings.privacy` в файле английской локали, прежде чем вернуть `"settings.privacy"` как есть.

Это гарантирует, что ваше приложение никогда не покажет необработанные ключи, если перевод лишь частично завершён.

<div id="rtl-support"></div>

## Поддержка RTL

{{ config('app.name') }} v7 включает встроенную поддержку языков с написанием справа налево (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Проверить, является ли текущий язык RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Обработать RTL-макет
}
```

<div id="debug-missing-keys"></div>

## Отладка отсутствующих ключей

Включение предупреждений об отсутствующих ключах перевода во время разработки:

В файле `.env`:
``` bash
DEBUG_TRANSLATIONS="true"
```

Это выводит предупреждения, когда `.tr()` не может найти ключ, помогая обнаружить непереведённые строки.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` -- это синглтон, управляющий всей локализацией. Помимо базового метода `translate()`, он предоставляет несколько дополнительных методов:

### Проверка существования перевода

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true, если ключ существует в текущем файле языка

// Работает также с вложенными ключами
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Получение всех ключей перевода

Полезно для отладки, чтобы увидеть, какие ключи загружены:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Смена локали без перезапуска

Если вы хотите сменить локаль без перезапуска приложения:

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Это загружает новый языковой файл, но **не** перезапускает приложение. Полезно, когда вы хотите обработать обновление интерфейса вручную.

### Проверка направления RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Доступ к текущей локали

``` dart
// Получить текущий код языка
String code = NyLocalization.instance.languageCode;  // например, 'en'

// Получить текущий объект Locale
Locale currentLocale = NyLocalization.instance.locale;

// Получить делегаты локализации Flutter (используются в MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Полный справочник API

| Метод / Свойство | Возвращает | Описание |
|------------------|------------|----------|
| `instance` | `NyLocalization` | Экземпляр синглтона |
| `translate(key, [arguments])` | `String` | Перевод ключа с необязательными аргументами |
| `hasTranslation(key)` | `bool` | Проверка существования ключа перевода |
| `getAllKeys()` | `List<String>` | Получение всех загруженных ключей перевода |
| `setLanguage(context, {language, restart})` | `Future<void>` | Смена языка с необязательным перезапуском |
| `setLocale({locale})` | `Future<void>` | Смена локали без перезапуска |
| `setDebugMissingKeys(enabled)` | `void` | Включение/отключение логирования отсутствующих ключей |
| `isDirectionRTL(context)` | `bool` | Проверка, является ли текущее направление RTL |
| `restart(context)` | `void` | Перезапуск приложения |
| `languageCode` | `String` | Текущий код языка |
| `locale` | `Locale` | Текущий объект Locale |
| `delegates` | `Iterable<LocalizationsDelegate>` | Делегаты локализации Flutter |
| `setValuesForTesting({values, fallbackValues})` | `void` | Внедрение карт переводов напрямую для юнит-тестов |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` -- это статический утилитарный класс для операций с локалями. Он предоставляет методы для определения текущей локали, проверки поддержки RTL и создания объектов Locale.

``` dart
// Получить текущую системную локаль
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Получить коды языка и страны
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' или null

// Проверить, совпадает ли текущая локаль
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// Определение RTL
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Получить направление текста
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Создать Locale из строк
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Полный справочник API

| Метод | Возвращает | Описание |
|-------|------------|----------|
| `getCurrentLocale({context})` | `Locale` | Получение текущей системной локали |
| `getLanguageCode({context})` | `String` | Получение текущего кода языка |
| `getCountryCode({context})` | `String?` | Получение текущего кода страны |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Проверка совпадения текущей локали |
| `isRtlLanguage(languageCode)` | `bool` | Проверка, является ли код языка RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Проверка, является ли текущая локаль RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Получение TextDirection для языка |
| `getCurrentTextDirection({context})` | `TextDirection` | Получение TextDirection для текущей локали |
| `toLocale(languageCode, [countryCode])` | `Locale` | Создание Locale из строк |

Константа `rtlLanguages` содержит: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Смена языка из контроллера

Если вы используете контроллеры со страницами, вы можете сменить язык из `NyController`:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es');
  }

  void switchToEnglishNoRestart() {
    changeLanguage('en', restartState: false);
  }
}
```

Параметр `restartState` управляет тем, перезапускается ли приложение после смены языка. Установите его в `false`, если хотите обработать обновление интерфейса самостоятельно.
