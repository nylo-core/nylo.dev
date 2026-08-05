# Installation

---

<a name="section-1"></a>
- [Установка](#install "Установка")
- [Запуск проекта](#running-the-project "Запуск проекта")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Три команды превращают пустую папку в работающее Flutter-приложение с уже настроенными маршрутизацией, сетью, темами и генерацией кода.

<x-doc-strip label="Перед началом" items="Flutter SDK установлен, Dart 3" linkText="Полные требования" linkHref="/ru/docs/{{ $version }}/requirements" />

## Установка

Выполните эти команды по порядку. Каждую из них можно безопасно запустить повторно.

<x-doc-steps>
<x-doc-step number="1" title="Установите Nylo CLI глобально">
Это устанавливает инструмент CLI {{ config('app.name') }} глобально в вашу систему.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Создайте новый проект">
Эта команда клонирует шаблон {{ config('app.name') }}, настраивает проект с вашим именем приложения и автоматически устанавливает все зависимости.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Настройте псевдоним Metro">
Это настраивает команду `metro` для вашего проекта, позволяя использовать команды Metro CLI без полного синтаксиса `dart run`.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Что вы получите" items="Предварительно настроенные маршрутизация и навигация
Шаблон API-сервиса
Настройка тем и локализации
Metro CLI для генерации кода" />


<div id="running-the-project"></div>

## Запуск проекта

Проекты {{ config('app.name') }} запускаются как любое стандартное Flutter-приложение.

<x-doc-tabs tabs="Терминал, Android Studio, VS Code">
<x-doc-tab label="Терминал">

``` bash
flutter run
```

Если сборка прошла успешно, приложение отобразит стартовый экран {{ config('app.name') }} по умолчанию.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Откройте папку проекта, выберите устройство в селекторе цели и нажмите **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Документация Flutter: запуск и отладка ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Откройте папку проекта и выполните **Debug: Start Without Debugging** из палитры команд.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Документация Flutter: запуск без точек останова ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro генерирует файлы проекта. Запустите его без аргументов, чтобы увидеть меню, или вызовите команду напрямую.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Справочник команд

Каждая команда принимает имя, например `metro make:page settings_page`

<x-doc-commands title="Команды виджетов" rows="
metro make:page | Создать новую страницу
metro make:stateful_widget | Создать stateful-виджет
metro make:stateless_widget | Создать stateless-виджет
metro make:state_managed_widget | Создать виджет с управлением состоянием
metro make:navigation_hub | Создать навигационный хаб (нижняя навигация)
metro make:journey_widget | Создать journey-виджет для навигационного хаба
metro make:bottom_sheet_modal | Создать модальное нижнее окно
metro make:button | Создать пользовательский виджет кнопки
metro make:form | Создать форму с валидацией
" />

<x-doc-commands title="Вспомогательные команды" rows="
metro make:model | Создать класс модели
metro make:provider | Создать провайдер
metro make:api_service | Создать API-сервис
metro make:controller | Создать контроллер
metro make:event | Создать событие
metro make:route_guard | Создать защитник маршрута
metro make:config | Создать файл конфигурации
metro make:interceptor | Создать сетевой перехватчик
metro make:command | Создать пользовательскую команду Metro
metro make:env | Сгенерировать конфигурацию окружения из .env
" />

### Примеры использования

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
