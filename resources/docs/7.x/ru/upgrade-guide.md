# Upgrade Guide

---

<a name="section-1"></a>
- [Что нового в v7](#whats-new "Что нового в v7")
- [Обзор критических изменений](#breaking-changes "Обзор критических изменений")
- [Рекомендуемый подход к миграции](#recommended-migration "Рекомендуемый подход к миграции")
- [Краткий чек-лист миграции](#checklist "Краткий чек-лист миграции")
- [Пошаговое руководство по миграции](#migration-guide "Руководство по миграции")
  - [Шаг 1: Обновление зависимостей](#step-1-dependencies "Обновление зависимостей")
  - [Шаг 2: Конфигурация окружения](#step-2-environment "Конфигурация окружения")
  - [Шаг 3: Обновление main.dart](#step-3-main "Обновление main.dart")
  - [Шаг 4: Обновление boot.dart](#step-4-boot "Обновление boot.dart")
  - [Шаг 5: Реорганизация файлов конфигурации](#step-5-config "Реорганизация файлов конфигурации")
  - [Шаг 6: Обновление AppProvider](#step-6-provider "Обновление AppProvider")
  - [Шаг 7: Обновление конфигурации темы](#step-7-theme "Обновление конфигурации темы")
  - [Шаг 10: Миграция виджетов](#step-10-widgets "Миграция виджетов")
  - [Шаг 11: Обновление путей к ресурсам](#step-11-assets "Обновление путей к ресурсам")
- [Удалённые функции и альтернативы](#removed-features "Удалённые функции и альтернативы")
- [Справочник удалённых классов](#deleted-classes "Справочник удалённых классов")
- [Справочник миграции виджетов](#widget-reference "Справочник миграции виджетов")
- [Устранение неполадок](#troubleshooting "Устранение неполадок")


<div id="whats-new"></div>

## Что нового в v7

{{ config('app.name') }} v7 -- это мажорный релиз со значительными улучшениями для разработчиков:

### Зашифрованная конфигурация окружения
- Переменные окружения теперь шифруются XOR-алгоритмом во время сборки для безопасности
- Новая команда `metro make:key` генерирует APP_KEY
- Новая команда `metro make:env` генерирует зашифрованный `env.g.dart`
- Поддержка `--dart-define` для внедрения APP_KEY в CI/CD пайплайнах

### Упрощённый процесс загрузки
- Новый паттерн `BootConfig` заменяет отдельные callback-функции setup/finished
- Более чистый `Nylo.init()` с параметром `env` для зашифрованного окружения
- Хуки жизненного цикла приложения прямо в main.dart

### Новый API nylo.configure()
- Единый метод объединяет всю конфигурацию приложения
- Более чистый синтаксис заменяет отдельные вызовы `nylo.add*()`
- Раздельные методы жизненного цикла `setup()` и `boot()` в провайдерах

### NyPage для страниц
- `NyPage` заменяет `NyState` для виджетов страниц (более чистый синтаксис)
- `view()` заменяет метод `build()`
- Геттер `get init =>` заменяет методы `init()` и `boot()`
- `NyState` по-прежнему доступен для не-страничных stateful-виджетов

### Система LoadingStyle
- Новый enum `LoadingStyle` для единообразных состояний загрузки
- Варианты: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Пользовательские виджеты загрузки через `LoadingStyle.normal(child: ...)`

### Типобезопасная маршрутизация RouteView
- `static RouteView path` заменяет `static const path`
- Типобезопасные определения маршрутов с фабрикой виджетов

### Поддержка нескольких тем
- Регистрация нескольких тёмных и светлых тем
- ID тем определяются в коде вместо файла `.env`
- Новые типы `NyThemeType.dark` / `NyThemeType.light` для классификации тем
- API предпочтительных тем: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Перечисление тем: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Новые команды Metro
- `make:key` -- генерация APP_KEY для шифрования
- `make:env` -- генерация зашифрованного файла окружения
- `make:bottom_sheet_modal` -- создание модальных нижних панелей
- `make:button` -- создание пользовательских кнопок

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Просмотреть все изменения на GitHub</a>

<div id="breaking-changes"></div>

## Обзор критических изменений

| Изменение | v6 | v7 |
|--------|-----|-----|
| Корневой виджет | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (использует `NyApp.materialApp()`) |
| Класс состояния страницы | `NyState` | `NyPage` для страниц |
| Метод представления | `build()` | `view()` |
| Метод инициализации | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Путь маршрута | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Загрузка провайдера | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Конфигурация | Отдельные вызовы `nylo.add*()` | Единый вызов `nylo.configure()` |
| ID тем | Файл `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | В коде (`type: NyThemeType.dark`) |
| Виджет загрузки | `useSkeletonizer` + `loading()` | Геттер `LoadingStyle` |
| Расположение конфигурации | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Расположение ресурсов | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Рекомендуемый подход к миграции

Для крупных проектов мы рекомендуем создать новый проект v7 и перенести файлы:

1. Создайте новый проект v7: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Скопируйте ваши страницы, контроллеры, модели и сервисы
3. Обновите синтаксис, как показано выше
4. Тщательно протестируйте

Это гарантирует наличие всей последней структуры и конфигураций.

Если вы хотите увидеть diff изменений между v6 и v7, вы можете просмотреть сравнение на GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Краткий чек-лист миграции

Используйте этот чек-лист для отслеживания прогресса миграции:

- [ ] Обновите `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Выполните `flutter pub get`
- [ ] Выполните `metro make:key` для генерации APP_KEY
- [ ] Выполните `metro make:env` для генерации зашифрованного окружения
- [ ] Обновите `main.dart` с параметром env и BootConfig
- [ ] Преобразуйте класс `Boot` для использования паттерна `BootConfig`
- [ ] Переместите файлы конфигурации из `lib/config/` в `lib/bootstrap/`
- [ ] Создайте новые файлы конфигурации (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Обновите `AppProvider` для использования `nylo.configure()`
- [ ] Удалите `LIGHT_THEME_ID` и `DARK_THEME_ID` из `.env`
- [ ] Добавьте `type: NyThemeType.dark` к конфигурациям тёмных тем
- [ ] Переименуйте `NyState` в `NyPage` для всех виджетов страниц
- [ ] Замените `build()` на `view()` на всех страницах
- [ ] Замените `init()/boot()` на `get init =>` на всех страницах
- [ ] Обновите `static const path` на `static RouteView path`
- [ ] Замените `router.route()` на `router.add()` в маршрутах
- [ ] Переименуйте виджеты (NyListView -> CollectionView и т.д.)
- [ ] Переместите ресурсы из `public/` в `assets/`
- [ ] Обновите пути к ресурсам в `pubspec.yaml`
- [ ] Удалите импорты Firebase (если используются -- добавьте пакеты напрямую)
- [ ] Удалите использование NyDevPanel (используйте Flutter DevTools)
- [ ] Выполните `flutter pub get` и протестируйте

<div id="migration-guide"></div>

## Пошаговое руководство по миграции

<div id="step-1-dependencies"></div>

### Шаг 1: Обновление зависимостей

Обновите ваш `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... другие зависимости
```

Выполните `flutter pub get` для обновления пакетов.

<div id="step-2-environment"></div>

### Шаг 2: Конфигурация окружения

v7 требует зашифрованные переменные окружения для повышения безопасности.

**1. Сгенерируйте APP_KEY:**

``` bash
metro make:key
```

Это добавит `APP_KEY` в ваш файл `.env`.

**2. Сгенерируйте зашифрованный env.g.dart:**

``` bash
metro make:env
```

Это создаст файл `lib/bootstrap/env.g.dart`, содержащий ваши зашифрованные переменные окружения.

**3. Удалите устаревшие переменные тем из .env:**

``` bash
# Удалите эти строки из вашего файла .env:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Шаг 3: Обновление main.dart

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // Необязательно: добавьте хуки жизненного цикла приложения
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Ключевые изменения:**
- Импорт сгенерированного `env.g.dart`
- Передача `Env.get` в параметр `env`
- `Boot.nylo` теперь `Boot.nylo()` (возвращает `BootConfig`)
- `setupFinished` удалён (обрабатывается внутри `BootConfig`)
- Необязательные хуки `appLifecycle` для изменений состояния приложения

<div id="step-4-boot"></div>

### Шаг 4: Обновление boot.dart

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**Ключевые изменения:**
- Возвращает `BootConfig` вместо `Future<Nylo>`
- `setup` и `finished` объединены в один объект `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Шаг 5: Реорганизация файлов конфигурации

v7 реорганизует файлы конфигурации для лучшей структуры:

| Расположение v6 | Расположение v7 | Действие |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Переместить |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Переместить |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Переместить |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Переместить |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Переименовать и рефакторить |
| (новый) | `lib/config/app.dart` | Создать |
| (новый) | `lib/config/toast_notification.dart` | Создать |

**Создание lib/config/app.dart:**

Справка: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // Название приложения.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // Версия приложения.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Добавьте другие настройки приложения здесь
}
```

**Создание lib/config/storage_keys.dart:**

Справка: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Определите ключи, которые должны синхронизироваться при загрузке
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // дать пользователю 10 монет по умолчанию
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Добавьте ваши ключи хранилища здесь...
}
```

**Создание lib/config/toast_notification.dart:**

Справка: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Настройте стили toast здесь
  };
}
```

<div id="step-6-provider"></div>

### Шаг 6: Обновление AppProvider

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**Ключевые изменения:**
- `boot()` теперь `setup()` для начальной конфигурации
- `boot()` теперь используется для пост-настроечной логики (ранее `afterBoot`)
- Все вызовы `nylo.add*()` объединены в один `nylo.configure()`
- Локализация использует объект `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Шаг 7: Обновление конфигурации темы

**v6 (файл .env):**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart):**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7 (theme.dart):**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**Ключевые изменения:**
- Удалите `LIGHT_THEME_ID` и `DARK_THEME_ID` из `.env`
- Определяйте ID тем непосредственно в коде
- Добавьте `type: NyThemeType.dark` ко всем конфигурациям тёмных тем
- Светлые темы по умолчанию имеют тип `NyThemeType.light`

**Новые методы API тем (v7):**
``` dart
// Установить и запомнить предпочтительную тему
NyTheme.set(context, id: 'dark_theme', remember: true);

// Установить предпочтительные темы для следования системе
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Получить ID предпочтительных тем
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Перечисление тем
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Очистить сохранённые предпочтения
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Шаг 10: Миграция виджетов

#### NyListView -> CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// С пагинацией (pull to refresh):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField -> InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText -> StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher -> LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### Шаг 11: Обновление путей к ресурсам

v7 изменяет директорию ресурсов с `public/` на `assets/`:

**1. Переместите папки с ресурсами:**
``` bash
# Переместить директории
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Обновите pubspec.yaml:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. Обновите ссылки на ресурсы в коде:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Виджет LocalizedApp -- удалён

Справка: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Миграция:** Используйте `Main(nylo)` напрямую. `NyApp.materialApp()` обрабатывает локализацию внутри себя.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Справочник удалённых классов

| Удалённый класс | Альтернатива |
|---------------|-------------|
| `NyTextStyle` | Используйте Flutter `TextStyle` напрямую |
| `NyBaseApiService` | Используйте `DioApiService` |
| `BaseColorStyles` | Используйте `ThemeColor` |
| `LocalizedApp` | Используйте `Main(nylo)` напрямую |
| `NyException` | Используйте стандартные исключения Dart |
| `PushNotification` | Используйте `flutter_local_notifications` напрямую |
| `PushNotificationAttachments` | Используйте `flutter_local_notifications` напрямую |

<div id="widget-reference"></div>

## Справочник миграции виджетов

### Переименованные виджеты

| Виджет v6 | Виджет v7 | Примечания |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Новый API с `builder` вместо `child` |
| `NyFutureBuilder` | `FutureWidget` | Упрощённый асинхронный виджет |
| `NyTextField` | `InputField` | Использует `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Тот же API |
| `NyRichText` | `StyledText` | Тот же API |
| `NyFader` | `FadeOverlay` | Тот же API |

### Удалённые виджеты (без прямой замены)

| Удалённый виджет | Альтернатива |
|----------------|-------------|
| `NyPullToRefresh` | Используйте `CollectionView.pullable()` |

### Примеры миграции виджетов

**NyPullToRefresh -> CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader -> AnimatedOpacity:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## Устранение неполадок

### "Env.get not found" или "Env is not defined"

**Решение:** Выполните команды генерации окружения:
``` bash
metro make:key
metro make:env
```
Затем импортируйте сгенерированный файл в `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" или "Dark theme not working"

**Решение:** Убедитесь, что тёмные темы имеют `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Добавьте эту строку
),
```

### "LocalizedApp not found"

Справка: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Решение:** `LocalizedApp` был удалён. Измените:
``` dart
// Было:
runApp(LocalizedApp(child: Main(nylo)));

// Стало:
runApp(Main(nylo));
```

### "router.route is not defined"

**Решение:** Используйте `router.add()` вместо:
``` dart
// Было:
router.route(HomePage.path, (context) => HomePage());

// Стало:
router.add(HomePage.path);
```

### "NyListView not found"

**Решение:** `NyListView` теперь `CollectionView`:
``` dart
// Было:
NyListView(...)

// Стало:
CollectionView<MyModel>(...)
```

### Ресурсы не загружаются (изображения, шрифты)

**Решение:** Обновите пути к ресурсам с `public/` на `assets/`:
1. Переместите файлы: `mv public/* assets/`
2. Обновите пути в `pubspec.yaml`
3. Обновите ссылки в коде

### "init() must return a value of type Future"

**Решение:** Измените на синтаксис геттера:
``` dart
// Было:
@override
init() async { ... }

// Стало:
@override
get init => () async { ... };
```

---

**Нужна помощь?** Ознакомьтесь с [Документацией Nylo](https://nylo.dev/docs/7.x) или откройте issue на [GitHub](https://github.com/nylo-core/nylo/issues).
