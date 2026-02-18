# Metro CLI tool

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Установка](#install "Установка Metro для {{ config('app.name') }}")
- Команды создания
  - [Создание контроллера](#make-controller "Создание нового контроллера")
  - [Создание модели](#make-model "Создание новой модели")
  - [Создание страницы](#make-page "Создание новой страницы")
  - [Создание stateless-виджета](#make-stateless-widget "Создание нового stateless-виджета")
  - [Создание stateful-виджета](#make-stateful-widget "Создание нового stateful-виджета")
  - [Создание journey-виджета](#make-journey-widget "Создание нового journey-виджета")
  - [Создание API-сервиса](#make-api-service "Создание нового API-сервиса")
  - [Создание события](#make-event "Создание нового события")
  - [Создание провайдера](#make-provider "Создание нового провайдера")
  - [Создание темы](#make-theme "Создание новой темы")
  - [Создание формы](#make-forms "Создание новой формы")
  - [Создание защиты маршрута](#make-route-guard "Создание новой защиты маршрута")
  - [Создание файла конфигурации](#make-config-file "Создание нового файла конфигурации")
  - [Создание команды](#make-command "Создание новой команды")
  - [Создание виджета с управлением состоянием](#make-state-managed-widget "Создание нового виджета с управлением состоянием")
  - [Создание Navigation Hub](#make-navigation-hub "Создание нового Navigation Hub")
  - [Создание нижнего модального окна](#make-bottom-sheet-modal "Создание нового нижнего модального окна")
  - [Создание кнопки](#make-button "Создание новой кнопки")
  - [Создание перехватчика](#make-interceptor "Создание нового перехватчика")
  - [Создание файла окружения](#make-env-file "Создание нового файла окружения")
  - [Генерация ключа](#make-key "Генерация APP_KEY")
- Иконки приложения
  - [Генерация иконок приложения](#build-app-icons "Генерация иконок приложения с Metro")
- Пользовательские команды
  - [Создание пользовательских команд](#creating-custom-commands "Создание пользовательских команд")
  - [Запуск пользовательских команд](#running-custom-commands "Запуск пользовательских команд")
  - [Добавление опций к командам](#adding-options-to-custom-commands "Добавление опций к пользовательским командам")
  - [Добавление флагов к командам](#adding-flags-to-custom-commands "Добавление флагов к пользовательским командам")
  - [Вспомогательные методы](#custom-command-helper-methods "Вспомогательные методы команд")
  - [Интерактивные методы ввода](#interactive-input-methods "Интерактивные методы ввода")
  - [Форматирование вывода](#output-formatting "Форматирование вывода")
  - [Вспомогательные методы файловой системы](#file-system-helpers "Вспомогательные методы файловой системы")
  - [Помощники JSON и YAML](#json-yaml-helpers "Помощники JSON и YAML")
  - [Помощники преобразования регистра](#case-conversion-helpers "Помощники преобразования регистра")
  - [Помощники путей проекта](#project-path-helpers "Помощники путей проекта")
  - [Помощники платформы](#platform-helpers "Помощники платформы")
  - [Команды Dart и Flutter](#dart-flutter-commands "Команды Dart и Flutter")
  - [Манипуляции с файлами Dart](#dart-file-manipulation "Манипуляции с файлами Dart")
  - [Помощники каталогов](#directory-helpers "Помощники каталогов")
  - [Помощники валидации](#validation-helpers "Помощники валидации")
  - [Шаблонирование файлов](#file-scaffolding "Шаблонирование файлов")
  - [Запуск задач](#task-runner "Запуск задач")
  - [Табличный вывод](#table-output "Табличный вывод")
  - [Индикатор прогресса](#progress-bar "Индикатор прогресса")


<div id="introduction"></div>

## Введение

Metro — это инструмент командной строки, работающий под капотом фреймворка {{ config('app.name') }}.
Он предоставляет множество полезных инструментов для ускорения разработки.

<div id="install"></div>

## Установка

Когда вы создаёте новый проект Nylo с помощью `nylo init`, команда `metro` автоматически настраивается для вашего терминала. Вы можете сразу начать использовать её в любом проекте Nylo.

Запустите `metro` из каталога вашего проекта, чтобы увидеть все доступные команды:

``` bash
metro
```

Вы должны увидеть вывод, похожий на следующий.

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
  make:key
```

<div id="make-controller"></div>

## Создание контроллера

- [Создание нового контроллера](#making-a-new-controller "Создание нового контроллера с Metro")
- [Принудительное создание контроллера](#forcefully-make-a-controller "Принудительное создание контроллера с Metro")
<div id="making-a-new-controller"></div>

### Создание нового контроллера

Вы можете создать новый контроллер, выполнив следующую команду в терминале.

``` bash
metro make:controller profile_controller
```

Это создаст новый контроллер, если он ещё не существует, в каталоге `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Принудительное создание контроллера

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующий контроллер, если он уже существует.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Создание модели

- [Создание новой модели](#making-a-new-model "Создание новой модели с Metro")
- [Создание модели из JSON](#make-model-from-json "Создание модели из JSON с Metro")
- [Принудительное создание модели](#forcefully-make-a-model "Принудительное создание модели с Metro")
<div id="making-a-new-model"></div>

### Создание новой модели

Вы можете создать новую модель, выполнив следующую команду в терминале.

``` bash
metro make:model product
```

Новая модель будет размещена в `lib/app/models/`.

<div id="make-model-from-json"></div>

### Создание модели из JSON

**Аргументы:**

Использование флага `--json` или `-j` создаст новую модель из JSON-данных.

``` bash
metro make:model product --json
```

Затем вы можете вставить JSON в терминал, и модель будет сгенерирована автоматически.

<div id="forcefully-make-a-model"></div>

### Принудительное создание модели

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующую модель, если она уже существует.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Создание страницы

- [Создание новой страницы](#making-a-new-page "Создание новой страницы с Metro")
- [Создание страницы с контроллером](#create-a-page-with-a-controller "Создание страницы с контроллером с Metro")
- [Создание страницы аутентификации](#create-an-auth-page "Создание страницы аутентификации с Metro")
- [Создание начальной страницы](#create-an-initial-page "Создание начальной страницы с Metro")
- [Принудительное создание страницы](#forcefully-make-a-page "Принудительное создание страницы с Metro")

<div id="making-a-new-page"></div>

### Создание новой страницы

Вы можете создать новую страницу, выполнив следующую команду в терминале.

``` bash
metro make:page product_page
```

Это создаст новую страницу, если она ещё не существует, в каталоге `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Создание страницы с контроллером

Вы можете создать новую страницу с контроллером, выполнив следующую команду в терминале.

**Аргументы:**

Использование флага `--controller` или `-c` создаст новую страницу с контроллером.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Создание страницы аутентификации

Вы можете создать новую страницу аутентификации, выполнив следующую команду в терминале.

**Аргументы:**

Использование флага `--auth` или `-a` создаст новую страницу аутентификации.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Создание начальной страницы

Вы можете создать новую начальную страницу, выполнив следующую команду в терминале.

**Аргументы:**

Использование флага `--initial` или `-i` создаст новую начальную страницу.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Принудительное создание страницы

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующую страницу, если она уже существует.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Создание stateless-виджета

- [Создание нового stateless-виджета](#making-a-new-stateless-widget "Создание нового stateless-виджета с Metro")
- [Принудительное создание stateless-виджета](#forcefully-make-a-stateless-widget "Принудительное создание stateless-виджета с Metro")
<div id="making-a-new-stateless-widget"></div>

### Создание нового stateless-виджета

Вы можете создать новый stateless-виджет, выполнив следующую команду в терминале.

``` bash
metro make:stateless_widget product_rating_widget
```

Это создаст новый виджет, если он ещё не существует, в каталоге `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Принудительное создание stateless-виджета

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующий виджет, если он уже существует.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Создание stateful-виджета

- [Создание нового stateful-виджета](#making-a-new-stateful-widget "Создание нового stateful-виджета с Metro")
- [Принудительное создание stateful-виджета](#forcefully-make-a-stateful-widget "Принудительное создание stateful-виджета с Metro")

<div id="making-a-new-stateful-widget"></div>

### Создание нового stateful-виджета

Вы можете создать новый stateful-виджет, выполнив следующую команду в терминале.

``` bash
metro make:stateful_widget product_rating_widget
```

Это создаст новый виджет, если он ещё не существует, в каталоге `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Принудительное создание stateful-виджета

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующий виджет, если он уже существует.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Создание journey-виджета

- [Создание нового journey-виджета](#making-a-new-journey-widget "Создание нового journey-виджета с Metro")
- [Принудительное создание journey-виджета](#forcefully-make-a-journey-widget "Принудительное создание journey-виджета с Metro")

<div id="making-a-new-journey-widget"></div>

### Создание нового journey-виджета

Вы можете создать новый journey-виджет, выполнив следующую команду в терминале.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Полный пример, если у вас есть BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Это создаст новый виджет, если он ещё не существует, в каталоге `lib/resources/widgets/`.

Аргумент `--parent` используется для указания родительского виджета, к которому будет добавлен новый journey-виджет.

Пример

``` bash
metro make:navigation_hub onboarding
```

Затем добавьте новые journey-виджеты.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Принудительное создание journey-виджета
**Аргументы:**
Использование флага `--force` или `-f` перезапишет существующий виджет, если он уже существует.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Создание API-сервиса

- [Создание нового API-сервиса](#making-a-new-api-service "Создание нового API-сервиса с Metro")
- [Создание API-сервиса с моделью](#making-a-new-api-service-with-a-model "Создание API-сервиса с моделью с Metro")
- [Создание API-сервиса из Postman](#make-api-service-using-postman "Создание API-сервисов из Postman")
- [Принудительное создание API-сервиса](#forcefully-make-an-api-service "Принудительное создание API-сервиса с Metro")

<div id="making-a-new-api-service"></div>

### Создание нового API-сервиса

Вы можете создать новый API-сервис, выполнив следующую команду в терминале.

``` bash
metro make:api_service user_api_service
```

Новый API-сервис будет размещён в `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Создание API-сервиса с моделью

Вы можете создать новый API-сервис с моделью, выполнив следующую команду в терминале.

**Аргументы:**

Использование опции `--model` или `-m` создаст новый API-сервис с моделью.

``` bash
metro make:api_service user --model="User"
```

Новый API-сервис будет размещён в `lib/app/networking/`.

### Принудительное создание API-сервиса

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующий API-сервис, если он уже существует.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Создание события

- [Создание нового события](#making-a-new-event "Создание нового события с Metro")
- [Принудительное создание события](#forcefully-make-an-event "Принудительное создание события с Metro")

<div id="making-a-new-event"></div>

### Создание нового события

Вы можете создать новое событие, выполнив следующую команду в терминале.

``` bash
metro make:event login_event
```

Это создаст новое событие в `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Принудительное создание события

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующее событие, если оно уже существует.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Создание провайдера

- [Создание нового провайдера](#making-a-new-provider "Создание нового провайдера с Metro")
- [Принудительное создание провайдера](#forcefully-make-a-provider "Принудительное создание провайдера с Metro")

<div id="making-a-new-provider"></div>

### Создание нового провайдера

Создайте новые провайдеры в вашем приложении с помощью следующей команды.

``` bash
metro make:provider firebase_provider
```

Новый провайдер будет размещён в `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Принудительное создание провайдера

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующий провайдер, если он уже существует.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Создание темы

- [Создание новой темы](#making-a-new-theme "Создание новой темы с Metro")
- [Принудительное создание темы](#forcefully-make-a-theme "Принудительное создание темы с Metro")

<div id="making-a-new-theme"></div>

### Создание новой темы

Вы можете создавать темы, выполнив следующую команду в терминале.

``` bash
metro make:theme bright_theme
```

Это создаст новую тему в `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Принудительное создание темы

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующую тему, если она уже существует.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Создание формы

- [Создание новой формы](#making-a-new-form "Создание новой формы с Metro")
- [Принудительное создание формы](#forcefully-make-a-form "Принудительное создание формы с Metro")

<div id="making-a-new-form"></div>

### Создание новой формы

Вы можете создать новую форму, выполнив следующую команду в терминале.

``` bash
metro make:form car_advert_form
```

Это создаст новую форму в `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Принудительное создание формы

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующую форму, если она уже существует.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Создание защиты маршрута

- [Создание новой защиты маршрута](#making-a-new-route-guard "Создание новой защиты маршрута с Metro")
- [Принудительное создание защиты маршрута](#forcefully-make-a-route-guard "Принудительное создание защиты маршрута с Metro")

<div id="making-a-new-route-guard"></div>

### Создание новой защиты маршрута

Вы можете создать защиту маршрута, выполнив следующую команду в терминале.

``` bash
metro make:route_guard premium_content
```

Это создаст новую защиту маршрута в `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Принудительное создание защиты маршрута

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующую защиту маршрута, если она уже существует.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Создание файла конфигурации

- [Создание нового файла конфигурации](#making-a-new-config-file "Создание нового файла конфигурации с Metro")
- [Принудительное создание файла конфигурации](#forcefully-make-a-config-file "Принудительное создание файла конфигурации с Metro")

<div id="making-a-new-config-file"></div>

### Создание нового файла конфигурации

Вы можете создать новый файл конфигурации, выполнив следующую команду в терминале.

``` bash
metro make:config shopping_settings
```

Это создаст новый файл конфигурации в `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Принудительное создание файла конфигурации

**Аргументы:**

Использование флага `--force` или `-f` перезапишет существующий файл конфигурации, если он уже существует.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Создание команды

- [Создание новой команды](#making-a-new-command "Создание новой команды с Metro")
- [Принудительное создание команды](#forcefully-make-a-command "Принудительное создание команды с Metro")

<div id="making-a-new-command"></div>

### Создание новой команды

Вы можете создать новую команду, выполнив следующую команду в терминале.

``` bash
metro make:command my_command
```

Это создаст новую команду в `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Принудительное создание команды

**Аргументы:**
Использование флага `--force` или `-f` перезапишет существующую команду, если она уже существует.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Создание виджета с управлением состоянием

Вы можете создать новый виджет с управлением состоянием, выполнив следующую команду в терминале.

``` bash
metro make:state_managed_widget product_rating_widget
```

Это создаст новый виджет в `lib/resources/widgets/`.

Использование флага `--force` или `-f` перезапишет существующий виджет, если он уже существует.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Создание Navigation Hub

Вы можете создать новый Navigation Hub, выполнив следующую команду в терминале.

``` bash
metro make:navigation_hub dashboard
```

Это создаст новый Navigation Hub в `lib/resources/pages/` и автоматически добавит маршрут.

**Аргументы:**

| Флаг | Сокращение | Описание |
|------|------------|----------|
| `--auth` | `-a` | Создать как страницу аутентификации |
| `--initial` | `-i` | Создать как начальную страницу |
| `--force` | `-f` | Перезаписать, если существует |

``` bash
# Создать как начальную страницу
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Создание нижнего модального окна

Вы можете создать новое нижнее модальное окно, выполнив следующую команду в терминале.

``` bash
metro make:bottom_sheet_modal payment_options
```

Это создаст новое нижнее модальное окно в `lib/resources/widgets/`.

Использование флага `--force` или `-f` перезапишет существующее модальное окно, если оно уже существует.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Создание кнопки

Вы можете создать новый виджет кнопки, выполнив следующую команду в терминале.

``` bash
metro make:button checkout_button
```

Это создаст новый виджет кнопки в `lib/resources/widgets/`.

Использование флага `--force` или `-f` перезапишет существующую кнопку, если она уже существует.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Создание перехватчика

Вы можете создать новый сетевой перехватчик, выполнив следующую команду в терминале.

``` bash
metro make:interceptor auth_interceptor
```

Это создаст новый перехватчик в `lib/app/networking/dio/interceptors/`.

Использование флага `--force` или `-f` перезапишет существующий перехватчик, если он уже существует.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Создание файла окружения

Вы можете создать новый файл окружения, выполнив следующую команду в терминале.

``` bash
metro make:env .env.staging
```

Это создаст новый файл `.env` в корне вашего проекта.

<div id="make-key"></div>

## Генерация ключа

Генерация безопасного `APP_KEY` для шифрования окружения. Используется для зашифрованных файлов `.env` в v7.

``` bash
metro make:key
```

**Аргументы:**

| Флаг / Опция | Сокращение | Описание |
|--------------|------------|----------|
| `--force` | `-f` | Перезаписать существующий APP_KEY |
| `--file` | `-e` | Целевой файл .env (по умолчанию: `.env`) |

``` bash
# Сгенерировать ключ и перезаписать существующий
metro make:key --force

# Сгенерировать ключ для определённого файла окружения
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Генерация иконок приложения

Вы можете сгенерировать все иконки приложения для iOS и Android, выполнив следующую команду.

``` bash
dart run flutter_launcher_icons:main
```

Она использует конфигурацию <b>flutter_icons</b> из вашего файла `pubspec.yaml`.

<div id="custom-commands"></div>

## Пользовательские команды

Пользовательские команды позволяют расширить CLI Nylo собственными командами, специфичными для проекта. Эта функция позволяет автоматизировать повторяющиеся задачи, реализовывать рабочие процессы развёртывания или добавлять любую пользовательскую функциональность непосредственно в инструменты командной строки вашего проекта.

- [Создание пользовательских команд](#creating-custom-commands)
- [Запуск пользовательских команд](#running-custom-commands)
- [Добавление опций к командам](#adding-options-to-custom-commands)
- [Добавление флагов к командам](#adding-flags-to-custom-commands)
- [Вспомогательные методы](#custom-command-helper-methods)

> **Примечание:** В настоящее время вы не можете импортировать nylo_framework.dart в пользовательских командах, пожалуйста, используйте ny_cli.dart вместо этого.

<div id="creating-custom-commands"></div>

## Создание пользовательских команд

Для создания новой пользовательской команды вы можете использовать функцию `make:command`:

```bash
metro make:command current_time
```

Вы можете указать категорию для вашей команды с помощью опции `--category`:

```bash
# Указать категорию
metro make:command current_time --category="project"
```

Это создаст новый файл команды в `lib/app/commands/current_time.dart` со следующей структурой:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

Команда автоматически регистрируется в файле `lib/app/commands/commands.json`, который содержит список всех зарегистрированных команд:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Запуск пользовательских команд

После создания вы можете запустить пользовательскую команду, используя сокращение Metro или полную команду Dart:

```bash
metro app:current_time
```

Когда вы запускаете `metro` без аргументов, вы увидите свои пользовательские команды в меню в разделе "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Для отображения справочной информации по вашей команде используйте флаг `--help` или `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Добавление опций к командам

Опции позволяют вашей команде принимать дополнительный ввод от пользователей. Вы можете добавить опции к команде в методе `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Добавление опции со значением по умолчанию
  command.addOption(
    'environment',     // имя опции
    abbr: 'e',         // сокращение
    help: 'Target deployment environment', // текст справки
    defaultValue: 'development',  // значение по умолчанию
    allowed: ['development', 'staging', 'production'] // допустимые значения
  );

  return command;
}
```

Затем получите значение опции в методе `handle` вашей команды:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Реализация команды...
}
```

Пример использования:

```bash
metro project:deploy --environment=production
# или с использованием сокращения
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Добавление флагов к командам

Флаги — это булевы опции, которые можно включить или выключить. Добавьте флаги к команде с помощью метода `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // имя флага
    abbr: 'v',       // сокращение
    help: 'Enable verbose output', // текст справки
    defaultValue: false  // по умолчанию выключен
  );

  return command;
}
```

Затем проверьте состояние флага в методе `handle` вашей команды:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Дополнительное логирование...
  }

  // Реализация команды...
}
```

Пример использования:

```bash
metro project:deploy --verbose
# или с использованием сокращения
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Вспомогательные методы

Базовый класс `NyCustomCommand` предоставляет несколько вспомогательных методов для выполнения общих задач:

#### Вывод сообщений

Методы для вывода сообщений различными цветами:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Вывод информационного сообщения синим цветом |
| [`error`](#custom-command-helper-formatting)     | Вывод сообщения об ошибке красным цветом |
| [`success`](#custom-command-helper-formatting)   | Вывод сообщения об успехе зелёным цветом |
| [`warning`](#custom-command-helper-formatting)   | Вывод предупреждения жёлтым цветом |

#### Запуск процессов

Запуск процессов и отображение их вывода в консоли:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Добавить пакет в `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Добавить несколько пакетов в `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Запустить внешний процесс и отобразить вывод в консоли |
| [`prompt`](#custom-command-helper-prompt)    | Собрать текстовый ввод пользователя |
| [`confirm`](#custom-command-helper-confirm)   | Задать вопрос да/нет и получить булев результат |
| [`select`](#custom-command-helper-select)    | Показать список вариантов для выбора одного |
| [`multiSelect`](#custom-command-helper-multi-select) | Позволить пользователю выбрать несколько вариантов из списка |

#### Сетевые запросы

Выполнение сетевых запросов через консоль:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Выполнить API-вызов с помощью клиента Nylo API |


#### Индикатор загрузки

Отображение индикатора загрузки во время выполнения функции:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Показать индикатор загрузки при выполнении функции |
| [`createSpinner`](#manual-spinner-control) | Создать экземпляр индикатора для ручного управления |

#### Помощники пользовательских команд

Вы также можете использовать следующие вспомогательные методы для управления аргументами команд:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Получить строковое значение из аргументов команды |
| [`getBool`](#custom-command-helper-get-bool)   | Получить булево значение из аргументов команды |
| [`getInt`](#custom-command-helper-get-int)    | Получить целочисленное значение из аргументов команды |
| [`sleep`](#custom-command-helper-sleep) | Приостановить выполнение на указанное время |


### Запуск внешних процессов

```dart
// Запуск процесса с выводом в консоль
await runProcess('flutter build web --release');

// Тихий запуск процесса
await runProcess('flutter pub get', silent: true);

// Запуск процесса в определённом каталоге
await runProcess('git pull', workingDirectory: './my-project');
```

### Управление пакетами

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Добавить пакет в pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Добавить dev-пакет в pubspec.yaml
addPackage('build_runner', dev: true);

// Добавить несколько пакетов сразу
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Форматирование вывода

```dart
// Вывод статусных сообщений с цветовой кодировкой
info('Processing files...');    // Синий текст
error('Operation failed');      // Красный текст
success('Deployment complete'); // Зелёный текст
warning('Outdated package');    // Жёлтый текст
```

<div id="interactive-input-methods"></div>

## Интерактивные методы ввода

Базовый класс `NyCustomCommand` предоставляет несколько методов для сбора пользовательского ввода в терминале. Эти методы упрощают создание интерактивных интерфейсов командной строки для ваших пользовательских команд.

<div id="custom-command-helper-prompt"></div>

### Текстовый ввод

```dart
String prompt(String question, {String defaultValue = ''})
```

Отображает вопрос пользователю и собирает текстовый ответ.

**Параметры:**
- `question`: Вопрос или приглашение для отображения
- `defaultValue`: Необязательное значение по умолчанию, если пользователь просто нажмёт Enter

**Возвращает:** Ввод пользователя в виде строки или значение по умолчанию, если ввод не предоставлен

**Пример:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Подтверждение

```dart
bool confirm(String question, {bool defaultValue = false})
```

Задаёт пользователю вопрос да/нет и возвращает булев результат.

**Параметры:**
- `question`: Вопрос да/нет
- `defaultValue`: Ответ по умолчанию (true для да, false для нет)

**Возвращает:** `true`, если пользователь ответил да, `false`, если нет

**Пример:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // Пользователь подтвердил или нажал Enter (принял значение по умолчанию)
  await runProcess('flutter pub get');
} else {
  // Пользователь отказался
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Одиночный выбор

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Показывает список вариантов и позволяет пользователю выбрать один.

**Параметры:**
- `question`: Приглашение для выбора
- `options`: Список доступных вариантов
- `defaultOption`: Необязательный выбор по умолчанию

**Возвращает:** Выбранный вариант в виде строки

**Пример:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Множественный выбор

```dart
List<String> multiSelect(String question, List<String> options)
```

Позволяет пользователю выбрать несколько вариантов из списка.

**Параметры:**
- `question`: Приглашение для выбора
- `options`: Список доступных вариантов

**Возвращает:** Список выбранных вариантов

**Пример:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Вспомогательный метод API

Вспомогательный метод `api` упрощает выполнение сетевых запросов из ваших пользовательских команд.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Базовые примеры использования

### GET-запрос

```dart
// Получение данных
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST-запрос

```dart
// Создание ресурса
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT-запрос

```dart
// Обновление ресурса
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE-запрос

```dart
// Удаление ресурса
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH-запрос

```dart
// Частичное обновление ресурса
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### С параметрами запроса

```dart
// Добавление параметров запроса
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### С индикатором загрузки

```dart
// Использование с индикатором для лучшего UX
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Обработка данных
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Функциональность индикатора загрузки

Индикаторы загрузки обеспечивают визуальную обратную связь во время длительных операций в пользовательских командах. Они отображают анимированный индикатор вместе с сообщением, пока команда выполняет асинхронные задачи.

- [Использование withSpinner](#using-with-spinner)
- [Ручное управление индикатором](#manual-spinner-control)
- [Примеры](#spinner-examples)

<div id="using-with-spinner"></div>

## Использование withSpinner

Метод `withSpinner` позволяет обернуть асинхронную задачу анимацией индикатора, который автоматически запускается при начале задачи и останавливается при её завершении или ошибке:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Параметры:**
- `task`: Асинхронная функция для выполнения
- `message`: Текст для отображения во время работы индикатора
- `successMessage`: Необязательное сообщение при успешном завершении
- `errorMessage`: Необязательное сообщение при ошибке

**Возвращает:** Результат выполнения функции task

**Пример:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Запуск задачи с индикатором
  final projectFiles = await withSpinner(
    task: () async {
      // Длительная задача (например, анализ файлов проекта)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Продолжение работы с результатами
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Ручное управление индикатором

Для более сложных сценариев, когда необходимо вручную управлять состоянием индикатора, вы можете создать экземпляр индикатора:

```dart
ConsoleSpinner createSpinner(String message)
```

**Параметры:**
- `message`: Текст для отображения во время работы индикатора

**Возвращает:** Экземпляр `ConsoleSpinner`, которым можно управлять вручную

**Пример с ручным управлением:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Создание экземпляра индикатора
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // Первая задача
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Вторая задача
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Третья задача
    await runProcess('./deploy.sh', silent: true);

    // Успешное завершение
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Обработка ошибки
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Примеры

### Простая задача с индикатором

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Установка зависимостей
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Несколько последовательных операций

```dart
@override
Future<void> handle(CommandResult result) async {
  // Первая операция с индикатором
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Вторая операция с индикатором
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Третья операция с индикатором
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Сложный рабочий процесс с ручным управлением

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Выполнение нескольких шагов с обновлением статуса
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Завершение процесса
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Использование индикаторов загрузки в пользовательских командах обеспечивает чёткую визуальную обратную связь для пользователей во время длительных операций, создавая более отполированный и профессиональный опыт работы с командной строкой.

<div id="custom-command-helper-get-string"></div>

### Получение строкового значения из опций

```dart
String getString(String name, {String defaultValue = ''})
```

**Параметры:**

- `name`: Имя опции для получения
- `defaultValue`: Необязательное значение по умолчанию, если опция не указана

**Возвращает:** Значение опции в виде строки

**Пример:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Получение булева значения из опций

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Параметры:**
- `name`: Имя опции для получения
- `defaultValue`: Необязательное значение по умолчанию, если опция не указана

**Возвращает:** Значение опции в виде булевого значения


**Пример:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Получение целочисленного значения из опций

```dart
int getInt(String name, {int defaultValue = 0})
```

**Параметры:**
- `name`: Имя опции для получения
- `defaultValue`: Необязательное значение по умолчанию, если опция не указана

**Возвращает:** Значение опции в виде целого числа

**Пример:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Пауза на указанное время

```dart
void sleep(int seconds)
```

**Параметры:**
- `seconds`: Количество секунд для паузы

**Возвращает:** Ничего

**Пример:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Форматирование вывода

Помимо базовых методов `info`, `error`, `success` и `warning`, `NyCustomCommand` предоставляет дополнительные помощники вывода:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Вывод простого текста (без цвета)
  line('Processing your request...');

  // Вывод пустых строк
  newLine();       // одна пустая строка
  newLine(3);      // три пустые строки

  // Вывод приглушённого комментария (серый текст)
  comment('This is a background note');

  // Вывод заметного оповещения
  alert('Important: Please read carefully');

  // Ask — псевдоним для prompt
  final name = ask('What is your name?');

  // Скрытый ввод для конфиденциальных данных (паролей, API-ключей)
  final apiKey = promptSecret('Enter your API key:');

  // Прерывание команды с сообщением об ошибке и кодом выхода
  if (name.isEmpty) {
    abort('Name is required');  // завершение с кодом 1
  }
}
```

| Метод | Описание |
|-------|----------|
| `line(String message)` | Вывод простого текста без цвета |
| `newLine([int count = 1])` | Вывод пустых строк |
| `comment(String message)` | Вывод приглушённого/серого текста |
| `alert(String message)` | Вывод заметного оповещения |
| `ask(String question, {String defaultValue})` | Псевдоним для `prompt` |
| `promptSecret(String question)` | Скрытый ввод для конфиденциальных данных |
| `abort([String? message, int exitCode = 1])` | Завершение команды с ошибкой |

<div id="file-system-helpers"></div>

## Вспомогательные методы файловой системы

`NyCustomCommand` включает встроенные помощники файловой системы, поэтому вам не нужно вручную импортировать `dart:io` для общих операций.

### Чтение и запись файлов

```dart
@override
Future<void> handle(CommandResult result) async {
  // Проверка существования файла
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Проверка существования каталога
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Чтение файла (асинхронно)
  String content = await readFile('pubspec.yaml');

  // Чтение файла (синхронно)
  String contentSync = readFileSync('pubspec.yaml');

  // Запись в файл (асинхронно)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Запись в файл (синхронно)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Дополнение файла
  await appendFile('log.txt', 'New log entry\n');

  // Обеспечение существования каталога (создаёт, если отсутствует)
  await ensureDirectory('lib/generated');

  // Удаление файла
  await deleteFile('lib/generated/output.dart');

  // Копирование файла
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Метод | Описание |
|-------|----------|
| `fileExists(String path)` | Возвращает `true`, если файл существует |
| `directoryExists(String path)` | Возвращает `true`, если каталог существует |
| `readFile(String path)` | Чтение файла как строки (асинхронно) |
| `readFileSync(String path)` | Чтение файла как строки (синхронно) |
| `writeFile(String path, String content)` | Запись содержимого в файл (асинхронно) |
| `writeFileSync(String path, String content)` | Запись содержимого в файл (синхронно) |
| `appendFile(String path, String content)` | Дополнение содержимого файла |
| `ensureDirectory(String path)` | Создание каталога, если он не существует |
| `deleteFile(String path)` | Удаление файла |
| `copyFile(String source, String destination)` | Копирование файла |

<div id="json-yaml-helpers"></div>

## Помощники JSON и YAML

Чтение и запись файлов JSON и YAML с помощью встроенных помощников.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Чтение JSON-файла как Map
  Map<String, dynamic> config = await readJson('config.json');

  // Чтение JSON-файла как List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Запись данных в JSON-файл (по умолчанию форматированный)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Запись компактного JSON
  await writeJson('output.json', data, pretty: false);

  // Добавление элемента в JSON-массив файла
  // Если файл содержит [{"name": "a"}], это добавит в этот массив
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // предотвращает дубликаты по этому ключу
  );

  // Чтение YAML-файла как Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Метод | Описание |
|-------|----------|
| `readJson(String path)` | Чтение JSON-файла как `Map<String, dynamic>` |
| `readJsonArray(String path)` | Чтение JSON-файла как `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Запись данных как JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Добавление в JSON-массив файла |
| `readYaml(String path)` | Чтение YAML-файла как `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Помощники преобразования регистра

Преобразование строк между соглашениями об именовании без импорта пакета `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Метод | Формат вывода | Пример |
|-------|--------------|--------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Помощники путей проекта

Геттеры для стандартных каталогов проекта {{ config('app.name') }}. Возвращают пути относительно корня проекта.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Построение пользовательского пути относительно корня проекта
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Свойство | Путь |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Разрешает относительный путь внутри проекта |

<div id="platform-helpers"></div>

## Помощники платформы

Проверка платформы и доступ к переменным окружения.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Проверки платформы
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Текущий рабочий каталог
  info('Working in: $workingDirectory');

  // Чтение системных переменных окружения
  String home = env('HOME', '/default/path');
}
```

| Свойство / Метод | Описание |
|------------------|----------|
| `isWindows` | `true`, если работает на Windows |
| `isMacOS` | `true`, если работает на macOS |
| `isLinux` | `true`, если работает на Linux |
| `workingDirectory` | Путь текущего рабочего каталога |
| `env(String key, [String defaultValue = ''])` | Чтение системной переменной окружения |

<div id="dart-flutter-commands"></div>

## Команды Dart и Flutter

Запуск стандартных команд CLI Dart и Flutter как вспомогательных методов. Каждый возвращает код выхода процесса.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Форматирование файла или каталога Dart
  await dartFormat('lib/app/models/user.dart');

  // Запуск dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Запуск flutter pub get
  await flutterPubGet();

  // Запуск flutter clean
  await flutterClean();

  // Сборка для целевой платформы с дополнительными аргументами
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Запуск flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // конкретный каталог
}
```

| Метод | Описание |
|-------|----------|
| `dartFormat(String path)` | Запуск `dart format` для файла или каталога |
| `dartAnalyze([String? path])` | Запуск `dart analyze` |
| `flutterPubGet()` | Запуск `flutter pub get` |
| `flutterClean()` | Запуск `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Запуск `flutter build <target>` |
| `flutterTest([String? path])` | Запуск `flutter test` |

<div id="dart-file-manipulation"></div>

## Манипуляции с файлами Dart

Помощники для программного редактирования файлов Dart, полезные при создании инструментов шаблонирования.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Добавление оператора import в файл Dart (избегает дубликатов)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Вставка кода перед последней закрывающей фигурной скобкой в файле
  // Полезно для добавления записей в регистрационные карты
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Проверка наличия определённой строки в файле
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Проверка совпадения файла с регулярным выражением
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Метод | Описание |
|-------|----------|
| `addImport(String filePath, String importStatement)` | Добавление import в файл Dart (пропускает, если уже присутствует) |
| `insertBeforeClosingBrace(String filePath, String code)` | Вставка кода перед последней `}` в файле |
| `fileContains(String filePath, String identifier)` | Проверка наличия строки в файле |
| `fileContainsPattern(String filePath, Pattern pattern)` | Проверка совпадения файла с паттерном |

<div id="directory-helpers"></div>

## Помощники каталогов

Помощники для работы с каталогами и поиска файлов.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Список содержимого каталога
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // Рекурсивный список
  var allEntities = listDirectory('lib/', recursive: true);

  // Поиск файлов по критериям
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Поиск файлов по паттерну имени
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Удаление каталога рекурсивно
  await deleteDirectory('build/');

  // Копирование каталога (рекурсивно)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Метод | Описание |
|-------|----------|
| `listDirectory(String path, {bool recursive = false})` | Список содержимого каталога |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Поиск файлов по критериям |
| `deleteDirectory(String path)` | Рекурсивное удаление каталога |
| `copyDirectory(String source, String destination)` | Рекурсивное копирование каталога |

<div id="validation-helpers"></div>

## Помощники валидации

Помощники для проверки и очистки пользовательского ввода для генерации кода.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Валидация идентификатора Dart
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Требование непустого первого аргумента
  String name = requireArgument(result, message: 'Please provide a name');

  // Очистка имени класса (PascalCase, удаление суффиксов)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Возвращает: 'User'

  // Очистка имени файла (snake_case с расширением)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Возвращает: 'user_model.dart'
}
```

| Метод | Описание |
|-------|----------|
| `isValidDartIdentifier(String name)` | Валидация имени идентификатора Dart |
| `requireArgument(CommandResult result, {String? message})` | Требование непустого первого аргумента или прерывание |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Очистка и преобразование имени класса в PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | Очистка и преобразование имени файла в snake_case |

<div id="file-scaffolding"></div>

## Шаблонирование файлов

Создание одного или нескольких файлов с содержимым с помощью системы шаблонирования.

### Один файл

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // не перезаписывать, если существует
    successMessage: 'AuthService created',
  );
}
```

### Несколько файлов

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

Класс `ScaffoldFile` принимает:

| Свойство | Тип | Описание |
|----------|-----|----------|
| `path` | `String` | Путь создаваемого файла |
| `content` | `String` | Содержимое файла |
| `successMessage` | `String?` | Сообщение при успехе |

<div id="task-runner"></div>

## Запуск задач

Запуск серии именованных задач с автоматическим выводом статуса.

### Базовый запуск задач

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // остановить конвейер при ошибке (по умолчанию)
    ),
  ]);
}
```

### Запуск задач с индикатором

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

Класс `CommandTask` принимает:

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `name` | `String` | обязательный | Имя задачи, отображаемое в выводе |
| `action` | `Future<void> Function()` | обязательный | Асинхронная функция для выполнения |
| `stopOnError` | `bool` | `true` | Остановить ли оставшиеся задачи при ошибке |

<div id="table-output"></div>

## Табличный вывод

Отображение форматированных ASCII-таблиц в консоли.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Вывод:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Индикатор прогресса

Отображение индикатора прогресса для операций с известным количеством элементов.

### Ручной индикатор прогресса

```dart
@override
Future<void> handle(CommandResult result) async {
  // Создание индикатора прогресса для 100 элементов
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // увеличение на 1
  }

  progress.complete('All files processed');
}
```

### Обработка элементов с прогрессом

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Обработка элементов с автоматическим отслеживанием прогресса
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // обработка каждого файла
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Синхронный прогресс

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // синхронная обработка
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

Класс `ConsoleProgressBar` предоставляет:

| Метод | Описание |
|-------|----------|
| `start()` | Запуск индикатора прогресса |
| `tick([int amount = 1])` | Увеличение прогресса |
| `update(int value)` | Установка прогресса на определённое значение |
| `updateMessage(String newMessage)` | Изменение отображаемого сообщения |
| `complete([String? completionMessage])` | Завершение с необязательным сообщением |
| `stop()` | Остановка без завершения |
| `current` | Текущее значение прогресса (геттер) |
| `percentage` | Прогресс в процентах (геттер) |
