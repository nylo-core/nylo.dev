# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Разработка приложений
    - [Новичок в Flutter?](#new-to-flutter "Новичок в Flutter?")
    - [График обслуживания и выпусков](#maintenance-and-release-schedule "График обслуживания и выпусков")
- Благодарности
    - [Зависимости фреймворка](#framework-dependencies "Зависимости фреймворка")
    - [Участники](#contributors "Участники")


<div id="introduction"></div>

## Введение

{{ config('app.name') }} --- это микрофреймворк для Flutter, созданный для упрощения разработки приложений. Он предоставляет структурированный шаблон с предварительно настроенными основными компонентами, чтобы вы могли сосредоточиться на создании функций вашего приложения, а не на настройке инфраструктуры.

Из коробки {{ config('app.name') }} включает:

- **Маршрутизация** --- простое декларативное управление маршрутами с защитниками и глубокими ссылками
- **Сеть** --- API-сервисы с Dio, перехватчиками и преобразованием ответов
- **Управление состоянием** --- реактивное состояние с NyState и глобальными обновлениями состояния
- **Локализация** --- поддержка нескольких языков с JSON-файлами переводов
- **Темы** --- светлый/тёмный режим с переключением тем
- **Локальное хранилище** --- безопасное хранилище с Backpack и NyStorage
- **Формы** --- обработка форм с валидацией и типами полей
- **Push-уведомления** --- поддержка локальных и удалённых уведомлений
- **CLI-инструмент (Metro)** --- генерация страниц, контроллеров, моделей и многого другого

<div id="new-to-flutter"></div>

## Новичок в Flutter?

Если вы новичок в Flutter, начните с официальных ресурсов:

- <a href="https://flutter.dev" target="_BLANK">Документация Flutter</a> --- подробные руководства и справочник API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">YouTube-канал Flutter</a> --- уроки и обновления
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> --- практические рецепты для типовых задач

Когда вы освоитесь с основами Flutter, {{ config('app.name') }} покажется интуитивно понятным, так как он построен на стандартных паттернах Flutter.


<div id="maintenance-and-release-schedule"></div>

## График обслуживания и выпусков

{{ config('app.name') }} следует <a href="https://semver.org" target="_BLANK">семантическому версионированию</a>:

- **Мажорные релизы** (7.x -> 8.x) --- раз в год для критических изменений
- **Минорные релизы** (7.0 -> 7.1) --- новые функции, обратная совместимость
- **Патч-релизы** (7.0.0 -> 7.0.1) --- исправления ошибок и незначительные улучшения

Исправления ошибок и патчи безопасности обрабатываются оперативно через репозитории на GitHub.


<div id="framework-dependencies"></div>

## Зависимости фреймворка

{{ config('app.name') }} v7 построен на этих пакетах с открытым исходным кодом:

### Основные зависимости

| Пакет | Назначение |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | HTTP-клиент для API-запросов |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Безопасное локальное хранилище |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Интернационализация и форматирование |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Реактивные расширения для потоков |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Сравнение объектов по значению |

### UI и виджеты

| Пакет | Назначение |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Эффекты скелетонной загрузки |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Toast-уведомления |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Функция «потяните для обновления» |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Каскадные сеточные макеты |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Поля выбора даты |

### Уведомления и подключение

| Пакет | Назначение |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Локальные push-уведомления |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Статус сетевого подключения |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Значки на иконке приложения |

### Утилиты

| Пакет | Назначение |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Открытие URL и приложений |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Преобразование регистра строк |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Генерация UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Пути файловой системы |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Маскирование ввода |


<div id="contributors"></div>

## Участники

Спасибо всем, кто внёс вклад в {{ config('app.name') }}! Если вы участвовали в разработке, свяжитесь с нами по адресу <a href="mailto:support@nylo.dev">support@nylo.dev</a>, чтобы быть добавленным сюда.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Создатель)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
