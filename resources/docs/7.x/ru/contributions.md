# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в участие в проекте")
- [Начало работы](#getting-started "Начало работы с вкладами")
- [Среда разработки](#development-environment "Настройка среды разработки")
- [Рекомендации по разработке](#development-guidelines "Рекомендации по разработке")
- [Отправка изменений](#submitting-changes "Как отправлять изменения")
- [Сообщение о проблемах](#reporting-issues "Как сообщать о проблемах")


<div id="introduction"></div>

## Введение

Благодарим вас за рассмотрение возможности внести вклад в {{ config('app.name') }}!

Это руководство поможет вам понять, как вносить вклад в микро-фреймворк. Будь то исправление ошибок, добавление функций или улучшение документации, ваш вклад ценен для сообщества {{ config('app.name') }}.

{{ config('app.name') }} разделён на три репозитория:

| Репозиторий | Назначение |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | Шаблонное приложение |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Основные классы фреймворка (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Библиотека поддержки с виджетами, помощниками, утилитами (nylo_support) |

<div id="getting-started"></div>

## Начало работы

### Форк репозиториев

Сделайте форк репозиториев, в которые хотите внести вклад:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Форк шаблона Nylo</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Форк Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Форк Support</a>

### Клонирование ваших форков

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Среда разработки

### Требования

Убедитесь, что у вас установлено следующее:

| Требование | Минимальная версия |
|-------------|-----------------|
| Flutter | 3.24.0 или выше |
| Dart SDK | 3.10.7 или выше |

### Подключение локальных пакетов

Откройте шаблон Nylo в вашем редакторе и добавьте переопределения зависимостей для использования локальных репозиториев framework и support:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Выполните `flutter pub get` для установки зависимостей.

Теперь изменения, внесённые в репозитории framework или support, будут отражены в шаблоне Nylo.

### Тестирование ваших изменений

Запустите шаблонное приложение для тестирования изменений:

``` bash
flutter run
```

Для изменений в виджетах или помощниках рассмотрите добавление тестов в соответствующем репозитории.

<div id="development-guidelines"></div>

## Рекомендации по разработке

### Стиль кода

- Следуйте официальному <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">руководству по стилю Dart</a>
- Используйте осмысленные имена переменных и функций
- Пишите понятные комментарии для сложной логики
- Добавляйте документацию для публичных API
- Поддерживайте модульность и сопровождаемость кода

### Документация

При добавлении новых функций:

- Добавляйте dartdoc-комментарии к публичным классам и методам
- При необходимости обновляйте соответствующие файлы документации
- Включайте примеры кода в документацию

### Тестирование

Перед отправкой изменений:

- Тестируйте на устройствах/симуляторах iOS и Android
- По возможности проверяйте обратную совместимость
- Чётко документируйте любые критические изменения
- Запускайте существующие тесты для проверки работоспособности

<div id="submitting-changes"></div>

## Отправка изменений

### Сначала обсудите

Для новых функций лучше сначала обсудить с сообществом:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Создание ветки

``` bash
git checkout -b feature/your-feature-name
```

Используйте описательные названия веток:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Коммит изменений

``` bash
git add .
git commit -m "Add: Your feature description"
```

Используйте понятные сообщения коммитов:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Отправка и создание Pull Request

``` bash
git push origin feature/your-feature-name
```

Затем создайте pull request на GitHub.

### Рекомендации для Pull Request

- Предоставьте чёткое описание ваших изменений
- Укажите связанные issues
- Приложите скриншоты или примеры кода, если применимо
- Убедитесь, что ваш PR затрагивает только одну задачу
- Держите изменения сфокусированными и атомарными

<div id="reporting-issues"></div>

## Сообщение о проблемах

### Перед подачей отчёта

1. Проверьте, не существует ли уже такая проблема на GitHub
2. Убедитесь, что используете последнюю версию
3. Попробуйте воспроизвести проблему в чистом проекте

### Куда сообщать

Сообщайте о проблемах в соответствующий репозиторий:

- **Проблемы шаблона**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Проблемы фреймворка**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Проблемы библиотеки поддержки**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Шаблон отчёта

Предоставьте подробную информацию:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### Получение информации о версии

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
