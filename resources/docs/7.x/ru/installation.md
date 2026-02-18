# Installation

---

<a name="section-1"></a>
- [Установка](#install "Установка")
- [Запуск проекта](#running-the-project "Запуск проекта")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Установка

### 1. Установите nylo_installer глобально

``` bash
dart pub global activate nylo_installer
```

Это устанавливает инструмент CLI {{ config('app.name') }} глобально в вашу систему.

### 2. Создайте новый проект

``` bash
nylo new my_app
```

Эта команда клонирует шаблон {{ config('app.name') }}, настраивает проект с вашим именем приложения и автоматически устанавливает все зависимости.

### 3. Настройте алиас Metro CLI

``` bash
cd my_app
nylo init
```

Это настраивает команду `metro` для вашего проекта, позволяя использовать команды Metro CLI без полного синтаксиса `dart run`.

После установки у вас будет полноценная структура Flutter-проекта с:
- Предварительно настроенной маршрутизацией и навигацией
- Шаблоном API-сервиса
- Настройкой тем и локализации
- Metro CLI для генерации кода


<div id="running-the-project"></div>

## Запуск проекта

Проекты {{ config('app.name') }} запускаются как любое стандартное Flutter-приложение.

### Через терминал

``` bash
flutter run
```

### Через IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Запуск и отладка</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Запуск приложения без точек останова</a>

Если сборка прошла успешно, приложение отобразит стартовый экран {{ config('app.name') }} по умолчанию.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} включает инструмент CLI под названием **Metro** для генерации файлов проекта.

### Запуск Metro

``` bash
metro
```

Это отображает меню Metro:

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Справочник команд Metro

| Команда | Описание |
|---------|----------|
| `make:page` | Создать новую страницу |
| `make:stateful_widget` | Создать stateful-виджет |
| `make:stateless_widget` | Создать stateless-виджет |
| `make:state_managed_widget` | Создать виджет с управлением состоянием |
| `make:navigation_hub` | Создать навигационный хаб (нижняя навигация) |
| `make:journey_widget` | Создать journey-виджет для навигационного хаба |
| `make:bottom_sheet_modal` | Создать модальное нижнее окно |
| `make:button` | Создать пользовательский виджет кнопки |
| `make:form` | Создать форму с валидацией |
| `make:model` | Создать класс модели |
| `make:provider` | Создать провайдер |
| `make:api_service` | Создать API-сервис |
| `make:controller` | Создать контроллер |
| `make:event` | Создать событие |
| `make:theme` | Создать тему |
| `make:route_guard` | Создать защитник маршрута |
| `make:config` | Создать файл конфигурации |
| `make:interceptor` | Создать сетевой перехватчик |
| `make:command` | Создать пользовательскую команду Metro |
| `make:env` | Сгенерировать конфигурацию окружения из .env |

### Примеры использования

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
