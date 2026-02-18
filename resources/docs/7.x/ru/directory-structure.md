# Directory Structure

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в структуру директорий")
- [Корневая директория](#root-directory "Корневая директория")
- [Директория lib](#lib-directory "Директория lib")
  - [app](#app-directory "Директория app")
  - [bootstrap](#bootstrap-directory "Директория bootstrap")
  - [config](#config-directory "Директория config")
  - [resources](#resources-directory "Директория resources")
  - [routes](#routes-directory "Директория routes")
- [Директория ресурсов](#assets-directory "Директория ресурсов")
- [Помощники для ресурсов](#asset-helpers "Помощники для ресурсов")


<div id="introduction"></div>

## Введение

{{ config('app.name') }} использует чистую, организованную структуру директорий, вдохновлённую <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Эта структура помогает поддерживать согласованность между проектами и упрощает поиск файлов.

<div id="root-directory"></div>

## Корневая директория

```
nylo_app/
├── android/          # Файлы платформы Android
├── assets/           # Изображения, шрифты и другие ресурсы
├── ios/              # Файлы платформы iOS
├── lang/             # JSON-файлы языков/переводов
├── lib/              # Код приложения на Dart
├── test/             # Тестовые файлы
├── .env              # Переменные окружения
├── pubspec.yaml      # Зависимости и конфигурация проекта
└── ...
```

<div id="lib-directory"></div>

## Директория lib

Папка `lib/` содержит весь код вашего приложения на Dart:

```
lib/
├── app/              # Логика приложения
├── bootstrap/        # Конфигурация загрузки
├── config/           # Файлы конфигурации
├── resources/        # Компоненты пользовательского интерфейса
├── routes/           # Определения маршрутов
└── main.dart         # Точка входа приложения
```

<div id="app-directory"></div>

### app/

Директория `app/` содержит основную логику вашего приложения:

| Директория | Назначение |
|------------|-----------|
| `commands/` | Пользовательские команды Metro CLI |
| `controllers/` | Контроллеры страниц для бизнес-логики |
| `events/` | Классы событий для системы событий |
| `forms/` | Классы форм с валидацией |
| `models/` | Классы моделей данных |
| `networking/` | API-сервисы и конфигурация сети |
| `networking/dio/interceptors/` | HTTP-перехватчики Dio |
| `providers/` | Сервис-провайдеры, загружаемые при старте приложения |
| `services/` | Классы общих сервисов |

<div id="bootstrap-directory"></div>

### bootstrap/

Директория `bootstrap/` содержит файлы, настраивающие процесс загрузки приложения:

| Файл | Назначение |
|------|-----------|
| `boot.dart` | Конфигурация основной последовательности загрузки |
| `decoders.dart` | Регистрация декодеров моделей и API |
| `env.g.dart` | Сгенерированная зашифрованная конфигурация окружения |
| `events.dart` | Регистрация событий |
| `extensions.dart` | Пользовательские расширения |
| `helpers.dart` | Пользовательские вспомогательные функции |
| `providers.dart` | Регистрация провайдеров |
| `theme.dart` | Конфигурация темы |

<div id="config-directory"></div>

### config/

Директория `config/` содержит конфигурацию приложения:

| Файл | Назначение |
|------|-----------|
| `app.dart` | Основные настройки приложения |
| `design.dart` | Дизайн приложения (шрифт, логотип, загрузчик) |
| `localization.dart` | Настройки языка и локали |
| `storage_keys.dart` | Определения ключей локального хранилища |
| `toast_notification.dart` | Стили всплывающих уведомлений |

<div id="resources-directory"></div>

### resources/

Директория `resources/` содержит компоненты пользовательского интерфейса:

| Директория | Назначение |
|------------|-----------|
| `pages/` | Виджеты страниц (экраны) |
| `themes/` | Определения тем |
| `themes/light/` | Цвета светлой темы |
| `themes/dark/` | Цвета тёмной темы |
| `widgets/` | Переиспользуемые компоненты виджетов |
| `widgets/buttons/` | Пользовательские виджеты кнопок |
| `widgets/bottom_sheet_modals/` | Виджеты модальных нижних панелей |

<div id="routes-directory"></div>

### routes/

Директория `routes/` содержит конфигурацию маршрутизации:

| Файл/Директория | Назначение |
|-----------------|-----------|
| `router.dart` | Определения маршрутов |
| `guards/` | Классы защитников маршрутов |

<div id="assets-directory"></div>

## Директория ресурсов

Директория `assets/` хранит статические файлы:

```
assets/
├── app_icon/         # Исходная иконка приложения
├── fonts/            # Пользовательские шрифты
└── images/           # Графические ресурсы
```

### Регистрация ресурсов

Ресурсы регистрируются в `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Помощники для ресурсов

{{ config('app.name') }} предоставляет помощники для работы с ресурсами.

### Графические ресурсы

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Общие ресурсы

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Языковые файлы

Языковые файлы хранятся в `lang/` в корне проекта:

```
lang/
├── en.json           # Английский
├── es.json           # Испанский
├── fr.json           # Французский
└── ...
```

Подробнее смотрите в [Локализация](/docs/7.x/localization).
