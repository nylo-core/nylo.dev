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
- [Плейбуки](#playbooks "Запуск плейбуков из Metro")
- [Запись сценария](#recording-a-journey "Запись ваших действий как плейбука")
- [Скриншоты](#screenshots "Сохранение скриншотов запущенного приложения")
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

Live-команды опускают свой префикс `metro live:` внутри оболочки -- `status` вместо `metro live:status`, `route /profile` вместо `metro live:run route /profile`. Несколько команд работают только внутри оболочки: `seed`, `seed:rollback`, `export`, `record`, `reload` и `restart`. Введите `help`, чтобы увидеть все команды, или `help <command>`, чтобы посмотреть опции одной команды. Введите `exit` (или Ctrl+D), чтобы выйти.

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

Внутри оболочки Tab дополняет имена команд, опции, маршруты, ключи хранилища и Backpack, имена сидеров и имена плейбуков для подключённого приложения. История хранится в `.dart_tool/nylo/live_history` и сохраняется между сессиями.

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
Playbooks      sign_up
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
| `playbook` | Запускает ваши [плейбуки](/docs/{{ $version }}/playbooks) или выводит их список |
| `screenshot` | Сохраняет [скриншот](#screenshots) приложения или одной `ScreenshotArea` |

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

<div id="playbooks"></div>

<!-- uncertain: new Nylo term "Playbook" rendered as "плейбук" -- not seen in existing ru files -->
## Плейбуки

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Запустите один или несколько [плейбуков](/docs/{{ $version }}/playbooks) на работающем приложении. Каждый шаг выводится по мере выполнения:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Укажите несколько плейбуков, чтобы запустить их один за другим. Без имён или с `--list` команда выводит список плейбуков приложения.

| Опция | Описание |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Играть в этом темпе вместо собственного темпа каждого плейбука |
| `--data <json>` | JSON-объект, который каждый плейбук запуска читает в `data` поверх своих значений по умолчанию |
| `--no-overlay` | Скрыть обратный отсчёт, а также палец и подписи у плейбуков, которые их показывают |
| `--loop` | Запускать плейбуки снова и снова до нажатия Ctrl+C |
| `--record[=<file>]` | Записать экран устройства во время запуска. Сохраняется в `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (или `.gif`) выбирает файл относительно проекта. Требуется ffmpeg |
| `--no-captions` | Не создавать файл подписей `.vtt` и главы записи |
| `--clean-status-bar` | Показывать 9:41 с полным сигналом и зарядом, пока идут плейбуки, а затем вернуть как было. Работает и без `--record` |
| `--countdown <seconds>` | Показать на экране обратный отсчёт 0-10 секунд перед первым шагом |
| `--screenshots[=<mode>]` | Сохранять также экран каждого шага. `<mode>` -- `all`, `auto`, `marked`, `failures` или `off`; см. [Скриншоты плейбука](#playbook-screenshots) |
| `--scale <n>` | Размер скриншотов относительно собственного размера устройства, до 4, например `0.5` для половины |
| `--locales <list>` | Запустить один раз в каждой из этих локалей, например `en,es`, или `all` -- во всех локалях приложения |
| `--themes <list>` | Запустить один раз в каждой из этих тем, например `light,dark`, или `all` -- во всех темах приложения |
| `--store <id>` | Рисовать скриншоты в размере магазина приложений, например `ios-6.9` |
| `--frame` | Рисовать каждый экран внутри контура телефона |
| `--background <hex>` | Цвет вокруг экрана, например `"#1B2027"` |
| `--list` | Вывести список плейбуков приложения вместо запуска |

Ctrl+C просит приложение остановиться: оставшиеся шаги пропускаются, но `tearDown` всё равно выполняется. Нажмите Ctrl+C второй раз, чтобы прекратить ожидание.

Если ни один шаг не достигает Metro в течение 20 секунд, Metro прекращает ожидание и завершает запуск с ошибкой. Приложение выполняет плейбук, но его события не поступают, что происходит, когда приложение запущено с `flutter run --no-dds`. Запустите его обычным `flutter run` и повторите попытку.

Внутри оболочки `metro live` команда выглядит как `playbook <name>`, а Tab дополняет имена плейбуков:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

<!-- uncertain: newly added Recording section; no prior locale context for register or terminology (e.g. chapters, status bar) -->

### Запись

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Добавьте `--record`, чтобы сохранить видео запуска:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Внутри оболочки `metro live` введите `playbook sign_up --record`.

Для записи нужен ffmpeg (например, `brew install ffmpeg`). Metro проверяет его наличие до начала воспроизведения.

- **iOS Simulator** -- запись через `xcrun simctl`.
- **Эмулятор или устройство Android** -- запись через `adb screenrecord`, который останавливается через 3 минуты, или через `scrcpy`, если он установлен (без ограничения, и записывает звук на Android 11+). Если подключено несколько устройств Android и Metro не может определить, на каком запущено приложение, задайте `ANDROID_SERIAL`.
- **Физический iPhone** -- нельзя записать из командной строки. Записывайте через QuickTime или Пункт управления и используйте `--countdown 3`, чтобы успеть начать.

Результат -- файл `.mp4` (H.264, стабильные 60 fps) ровно той же длительности, что и запуск, или `.gif` шириной 480px с 15 fps. Каждый шаг становится подписью в файле `.vtt` рядом с `.mp4` и главой внутри него. Они создаются по шагам запуска независимо от того, показывает ли плейбук подписи на экране. Передайте `--no-captions`, чтобы их не создавать.

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

Плейбук, который завершился ошибкой или был остановлен, всё равно сохраняется, так что запись служит ещё и отчётом об ошибке. Ctrl+C останавливает плейбук (`tearDown` всё равно выполняется), и запись всё равно сохраняется.

`--clean-status-bar` сохраняет строку состояния, которую вы уже задали через `simctl status_bar` на Simulator. На Android используется демо-режим, который потом отключается. При нескольких приложениях (`--all`) каждое устройство получает свой файл с именем устройства.

По умолчанию плейбук скрывает палец и подписи. Для демо-видео задайте `showFinger` и `caption` в плейбуке -- см. [Палец и подпись](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Скриншоты

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

[Шаги `screenshot()`](/docs/{{ $version }}/playbooks#screenshots) плейбука сохраняются в `screenshots/<playbook>/` без каких-либо флагов. Добавьте опции, чтобы снимать их на каждом языке и в каждой теме или подгонять размер под страницу в магазине:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` также сохраняет экран после каждого шага плейбука, в котором нет шагов `screenshot()`, а `--screenshots=all` делает это для каждого плейбука. Экран упавшего шага сохраняется в `build/playbooks/failures`; `--screenshots=off` отключает это.

`--locales` и `--themes` запускают плейбук по одному разу в каждой, после чего собственные локаль и тема приложения возвращаются на место. `--store` без значения выводит список размеров, а `--frame` рисует каждый экран в контуре телефона с `caption:` шага над ним. `--clean-status-bar` показывает 9:41 с полным сигналом и зарядом на снимках, которые Metro делает через iOS Simulator или `adb`.

<div id="playbook-live-commands"></div>

### Справочник live-команд

За этим стоят команды `playbooks.list`, `playbooks.run` и `playbooks.stop`. Запуск сразу возвращает свой id и передаёт каждый шаг событием `nylo.playbook`, поэтому может длиться дольше таймаута команды. `live:status` выводит список плейбуков приложения. Плейбуки появились в версии 9 протокола Nylo Live. Поддержка записи -- `countdown` в `playbooks.run` (с событием `countdown` каждую секунду) и `simulator` (UDID iOS Simulator) в `status` -- появилась в версии 10 протокола.

<div id="recording-a-journey"></div>

## Запись сценария

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` записывает ваши действия в запущенном приложении как [плейбук](/docs/{{ $version }}/playbooks#recording-a-playbook). Он работает только внутри оболочки `metro live`:

``` plaintext
› record sign_up
```

Пользуйтесь приложением и нажмите Enter, когда сценарий завершён. Плейбук появится в `lib/app/playbooks/sign_up_playbook.dart`, зарегистрированный в `lib/bootstrap/playbooks.dart`, а Metro предложит выполнить hot restart приложения и воспроизвести его на скорости `fast`.

| Опция | Описание |
|-------|----------|
| `--description <text>` | Однострочное описание, отображаемое, когда Metro выводит список плейбуков |
| `--no-data` | Записывать введённые значения как они были введены, вместо заполнителей `data[...]` с их значениями по умолчанию |
| `--no-replay` | Пропустить вопрос о воспроизведении |
| `--force`, `-f` | Заменить уже существующий файл плейбука |

Во время записи введите `a` и Enter, чтобы следующее нажатие стало `see()`, `d` -- для `dontSee()`, `s <title>`, чтобы начать сцену, `u`, чтобы отменить последний шаг, и `p` для паузы. Ctrl+C отбрасывает запись. `metro make:playbook sign_up --record` открывает оболочку и начинает запись за один шаг.

За этим стоят команды `record.start`, `record.stop`, `record.undo`, `record.mark` и `record.status`. Запись передаёт каждый шаг событием `nylo.record`. Запись появилась в версии 11 протокола Nylo Live.

<div id="screenshots"></div>

## Скриншоты

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Сохраните PNG вашего запущенного приложения:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Каждый файл сохраняется в `screenshots/` в вашем проекте под именем `<mode>-<device>-<date>-<time>.png`. На iOS Simulator и Android Metro снимает весь экран устройства через Simulator или `adb`, поэтому строка состояния и клавиатура попадают в кадр. Любое другое устройство получает собственный снимок приложения -- страницы приложения такими, какими их видит пользователь.

| Опция | Описание |
|-------|----------|
| `--area <name>` | Снять только `ScreenshotArea` с этим именем |
| `--scale <n>` | Размер относительно собственного размера устройства, до 4, например `0.5` для половины |
| `--locales <list>` | Снять экран в каждой из этих локалей, например `en,es`, или `all` -- во всех локалях приложения |
| `--themes <list>` | Снять экран в каждой из этих тем, например `light,dark`, или `all` -- во всех темах приложения |
| `--store <id>` | Рисовать в размере скриншота магазина приложений, например `ios-6.9`. Сам по себе `--store` выводит список размеров |
| `--frame` | Нарисовать экран внутри контура телефона на фоне |
| `--caption <text>` | Текст над `--frame` или ключ перевода |
| `--background <hex>` | Цвет вокруг экрана, за `--frame` или рядом с размером `--store` другой формы, например `"#1B2027"` |
| `--out <path>` | Сохранить в этот `.png` или в папку, заканчивающуюся на `/` (по умолчанию: `screenshots/`) |
| `--open` | Открыть скриншот после сохранения |
| `--copy` | Скопировать скриншот в буфер обмена |
| `--native` | Снимать через iOS Simulator или `adb`. Это значение по умолчанию для снимка всего экрана; `--no-native` использует собственный снимок приложения |
| `--clean-status-bar` | Показывать 9:41 с полным сигналом и зарядом в снимке `--native` |

С `--locales` или `--themes` Metro сохраняет по одному PNG для каждой пары в папке с именами `es.png`, `dark.png` или `es-dark.png`, переключая приложение на месте и возвращая его потом назад. `--area`, `--scale`, `--store` и `--frame` используют собственный снимок приложения.

Внутри оболочки `metro live` введите `screenshot --open`. Чтобы делать скриншоты из кода вашего приложения, см. [Скриншоты](/docs/{{ $version }}/screenshots).

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
