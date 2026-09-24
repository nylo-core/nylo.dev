# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в Nylo Live")
- Оболочка metro live
  - [Общие опции](#shared-options "Опции, которые принимает любая live-команда")
  - [Автодополнение и история](#tab-completion-and-history "Автодополнение и история команд")
  - [Запуск скрипта](#running-a-script "Запуск .live-скрипта")
- [live:devices](#live-devices "Список запущенных приложений")
- [live:status](#live-status "Показать статус приложения")
- [live:run](#live-run "Запуск встроенной live-команды")
- Live-команды
  - [Создание Live-команды](#creating-a-live-command "Создание live-команды")
  - [Запуск Live-команды](#running-a-live-command "Запуск live-команды")
- Сидеры
  - [Создание сидера](#creating-a-seeder "Создание сидера")
  - [Заполнение и откат](#seeding-and-rolling-back "Заполнение и откат")
- Снимки хранилища
  - [Экспорт снимка](#exporting-a-snapshot "Экспорт снимка")
  - [Загрузка снимка](#loading-a-snapshot "Загрузка снимка")
- [Действия страницы](#page-actions "Вызов страницы извне")

<div id="introduction"></div>

## Введение

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** позволяет Metro обращаться к вашему приложению, пока оно работает в debug-режиме (или profile-режиме), через Dart VM service. Он находит приложение через Dart Tooling Daemon без какой-либо настройки и подключается только к приложениям, собранным из текущего проекта.

Nylo Live включён по умолчанию в debug- и profile-сборках; release-сборка ничего не регистрирует. Команды, которые только читают данные приложения (`live:status`, `live:run data`, `live:run routes`, ...), работают и в profile-сборках. Команды, которые изменяют приложение (`route`, `storage`, `auth`, сидеры, ...), требуют debug-сборку.

Отключите его самостоятельно из провайдера, до того как запустится что-либо ещё:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## Оболочка metro live

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Откройте оболочку, подключённую к вашему запущенному приложению:

``` bash
metro live
```

Nylo Live обнаруживает запущенные приложения проекта и подключается к одному из них -- если запущено несколько, он предложит выбрать. После подключения приглашение показывает устройство и маршрут, отображаемый на экране прямо сейчас:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Live-команды опускают свой префикс `metro live:` внутри оболочки -- `status` вместо `metro live:status`, `route /profile` вместо `metro live:run route /profile`. Несколько команд работают только внутри оболочки: `seed`, `seed:rollback`, `export`, `reload` и `restart`. Введите `help`, чтобы увидеть все команды, или `help <command>`, чтобы посмотреть опции одной команды. Введите `exit` (или Ctrl+D), чтобы выйти.

Если приложение перезапускается, пока вы подключены, оболочка переподключается автоматически.

<div id="shared-options"></div>

### Общие опции

Каждая live-команда -- внутри оболочки или как `metro live:*` -- принимает одни и те же опции:

| Опция | Описание |
|--------|-------------|
| `-d, --device <#\|name>` | Указать приложение по его номеру в `metro live:devices` или по имени устройства |
| `--all` | Выполнить на каждом запущенном приложении этого проекта |
| `--json` | Вывести машиночитаемый JSON |
| `--uri` | Использовать этот адрес VM service вместо обнаружения приложений |
| `--timeout` | Секунды ожидания каждого приложения при обнаружении (по умолчанию `3`) |

Значение опции, записанное как `@path`, читается из этого файла -- удобно для большой полезной нагрузки `--data`:

``` bash
metro live:run route /profile --data @user.json
```

Начните значение с `@@`, если оно на самом деле начинается с `@`.

<div id="tab-completion-and-history"></div>

### Автодополнение и история

Внутри оболочки Tab дополняет имена команд, опции, маршруты, ключи хранилища и Backpack, а также имена сидеров для подключённого приложения. История хранится в `.dart_tool/nylo/live_history` и сохраняется между сессиями.

<div id="running-a-script"></div>

### Запуск скрипта

Передайте файл на вход `metro live`, чтобы выполнить его как скрипт, по одной команде на строку, останавливаясь на первой неудачной команде:

``` bash
metro live < seeds/demo.live
```

Пустые строки и строки, начинающиеся с `#`, пропускаются.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Выводит список запущенных приложений этого проекта, пронумерованных для `-d`:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Показывает приложение, устройство, режим сборки, текущий маршрут и стек, локаль, тему и то, вошёл ли пользователь в систему:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Запускает встроенную команду, которая изучает или управляет приложением:

``` bash
metro live:run <command> [arguments]
```

| Команда | Описание |
|---------|-------------|
| `data` | Показать данные и поля страницы, отображаемой на экране |
| `routes` | Вывести список зарегистрированных маршрутов |
| `route` | Открыть страницу, с опциональным `--data` |
| `back` | Вернуться на предыдущую страницу или перейти к определённому маршруту |
| `deeplink` | Открыть deep link или показать, как они настроены |
| `storage` | Вывести список локального хранилища, или показать, сохранить или удалить одно значение |
| `storage:clear` | Очистить локальное хранилище, с опциональным `--keep` |
| `backpack` | Вывести список значений Backpack, или показать, сохранить или удалить одно |
| `auth` | Показать, кто вошёл в систему, или войти и выйти |
| `locale` | Переключить язык приложения |
| `theme` | Переключить тему приложения |
| `state` | Отправить данные в состояние с помощью `updateState` |
| `event` | Вызвать одно из ваших событий |
| `toast` | Показать toast-уведомление |

Внутри `metro live` опустите часть `live:run` -- `route /profile` вместо `metro live:run route /profile`:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` также читает поля, объявленные состоянием вашей страницы, прямо из запущенного приложения -- во Flutter нет рефлексии, поэтому это работает только для состояния, которое действительно отображается на экране:

``` bash
metro live:run data HomePage
```

<div id="live-commands"></div>

## Live-команды

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Регистрируйте собственные команды для запуска внутри приложения, наряду со встроенными командами выше.

<div id="creating-a-live-command"></div>

### Создание Live-команды

``` bash
metro make:command seed_cart --live
```

| Опция | Описание |
|--------|-------------|
| `--category`, `-c` | Категория команды (по умолчанию: `app`) |
| `--description` | Однострочное описание, показываемое в `--help` команды |
| `--force`, `-f` | Создаёт файл, даже если он уже существует |

Это создаёт `LiveCommand` в `lib/app/commands/`, помечает её `"type": "live"` в `commands.json`, регистрирует её в `lib/bootstrap/live_commands.dart` и подключает `liveCommands: liveCommands` в `nylo.configure(...)` в `app_provider.dart`:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` использует ту же форму `builder`/`handle`, что и собственные команды Metro, но `handle` выполняется **внутри приложения**, поэтому может использовать `NyStorage`, `routeTo`, `Auth` и ваши модели. `package:nylo_framework/live.dart` экспортирует `LiveCommand` вместе с `Seeder`, `StorageSnapshot`, `LiveException` и `LiveOutput`.

<div id="running-a-live-command"></div>

### Запуск Live-команды

Новой live-команде нужен hot restart, прежде чем запущенное приложение сможет её выполнить. Затем, из терминала:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Сидеры

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

<!-- uncertain: new Nylo-specific term "Seeder" -- translated as "сидер"/"сидеры" (Laravel-style DB seeder convention); not seen in existing locale file -->
`Seeder` приводит запущенное приложение в известное состояние -- вход в систему выполнен, онбординг пройден, тестовые записи созданы -- а затем возвращает его обратно.

<div id="creating-a-seeder"></div>

### Создание сидера

``` bash
metro make:seeder demo_user
```

| Опция | Описание |
|--------|-------------|
| `--description` | Однострочное описание, показываемое, когда `seed` выводит список сидеров |
| `--force`, `-f` | Создаёт файл, даже если он уже существует |

Это создаёт `Seeder` в `lib/app/seeders/`, регистрирует его в `lib/bootstrap/seeders.dart` и подключает `seeders: seeders` в `nylo.configure(...)`:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

Каждое значение хранилища и Backpack, изменённое в `up()`, записывается автоматически, поэтому стандартный `down()` (`restore()`) возвращает каждое из них точно в исходное состояние, в обратном порядке -- даже после hot restart. Вызовите `seed(otherSeeders)` внутри сидера, чтобы запустить другие сидеры как часть него; их изменения также записываются и откатываются.

<div id="seeding-and-rolling-back"></div>

### Заполнение и откат

Заполнение, откат и экспорт работают только внутри оболочки `metro live`:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` без аргументов выводит список зарегистрированных сидеров. `--fresh` очищает хранилище и Backpack перед заполнением, а `--restart` выполняет hot restart приложения после -- так оно запускается уже заполненным:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Снимки хранилища

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` сохраняет всё, что приложение сейчас хранит в хранилище и Backpack -- как сидер, или как переносимый JSON-файл -- а `seed <file>` загружает это обратно.

<div id="exporting-a-snapshot"></div>

### Экспорт снимка

Запустите без имени, чтобы предварительно посмотреть, что будет захвачено:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

Назовите его, чтобы сохранить как сидер в `lib/app/seeders/` (так же, как `make:seeder`, но уже заполненный захваченными значениями):

``` plaintext
› export pro_user
```

Или запишите его в файл:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Опция | Описание |
|--------|-------------|
| `--to <path>` | Записать снимок в этот JSON-файл вместо сидера |
| `--description` | Однострочное описание для сидера |
| `--only <keys>` | Ключи для экспорта через запятую; `*` соответствует чему угодно, например `--only SK_USER,onboarding_*` |
| `--except <keys>` | Ключи для исключения через запятую, например `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Включить значения Backpack (по умолчанию: включены) |
| `--force`, `-f` | Заменить сидер или файл, который уже существует |

`Model` или любой класс с зарегистрированным декодером помечается именем класса, поэтому он возвращается обратно через собственный декодер, а не как обычная map. `export` предупреждает, когда снимок содержит ключи, похожие на учётные данные, и когда он достаточно большой, чтобы вам, вероятно, понадобился `--except`.

<div id="loading-a-snapshot"></div>

### Загрузка снимка

``` plaintext
› seed snapshots/pro_user.json
```

Загрузка файла записывается так же, как и сидер, поэтому `seed:rollback <name>` (имя файла или то, что дал ему `--as`) также отменяет её. Из кода сидер может применить захваченный снимок с помощью `importSnapshot`:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` записывает именно такую структуру, поэтому файл, записанный с `--to`, можно вставить прямо в поле `snapshot` сидера.

<div id="page-actions"></div>

## Действия страницы

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` на странице даёт вам `PageStateActions` для вызова этой страницы извне, без необходимости вручную создавать вызов `stateAction(...)`:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Смотрите [Вызов действий страницы](/docs/{{ $version }}/state-management#page-actions-shortcut) для полного набора встроенных действий и того, как объявлять собственные типизированные действия. Nylo Live читает и использует те же самые действия: `metro live:run data` выводит список того, что страница на экране предоставляет под `actions`, а `metro live:run state` отправляет странице данные напрямую по имени её состояния.
