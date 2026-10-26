# Плейбуки
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в плейбуки")
- [Создание плейбука](#creating-a-playbook "Создание плейбука")
- [Запись плейбука](#recording-a-playbook "Запись плейбука из запущенного приложения")
- [Шаги](#steps "Шаги, которые может выполнять плейбук")
- [Цели](#targets "Поиск элементов на экране")
  - [Простые строки](#plain-strings "Как сопоставляется простая строка")
  - [Find](#find "Точные цели с помощью Find")
- [Данные и подготовка](#data-and-setup "Данные, setUp и tearDown")
- [Запуск других плейбуков](#playing-other-playbooks "Запуск плейбука из другого плейбука")
- [Скорость](#speed "PlaybookSpeed")
- [Палец и подпись](#finger-and-caption "Палец и подпись шага")
- [Скриншоты](#screenshots "Снимки экрана во время плейбука")
  - [Локали и темы](#screenshots-locales-and-themes "Снимок экрана в каждой локали и теме")
  - [Сбор из кода](#collecting-screenshots "Сбор скриншотов с помощью onScreenshot")
- [Регистрация плейбуков](#registering-playbooks "Регистрация плейбуков в Nylo")
- [Запуск плейбука](#running-a-playbook "Запуск плейбука")
  - [Из Metro](#running-from-metro "Запуск из Metro")
  - [При запуске приложения](#running-on-boot "Запуск при старте приложения")
  - [Из Dart](#running-from-dart "Запуск из Dart")
- [Отчёты](#reports "PlaybookReport")
- [Релизные сборки](#release-builds "Плейбуки в релизных сборках")
- [Тестирование](#testing "Тестирование плейбука")

<div id="introduction"></div>

## Введение

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**Плейбук** -- это класс, который перечисляет нажатия, ввод текста, прокрутку и смену страниц пользовательского сценария. Ваше запущенное приложение выполняет их само, и может показывать палец и подпись к каждому шагу -- удобно для демо, записи экрана и сквозной проверки сценария.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

Импортируйте `package:nylo_framework/playbook.dart` везде, где вы пишете или регистрируете плейбуки.

<div id="creating-a-playbook"></div>

## Создание плейбука

Создайте плейбук с помощью Metro:

``` bash
metro make:playbook sign_up
```

Это создаёт `lib/app/playbooks/sign_up_playbook.dart` и регистрирует его в вашем приложении. Параметры команды см. в [Make Playbook](/docs/{{ $version }}/metro#make-playbook).

Чтобы начать с каждой страницы, добавленной вашим роутером, передайте `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

Metro пишет `visit()` и [`screenshot()`](#screenshots) для каждой страницы, поэтому один запуск фотографирует всё ваше приложение.

<div id="recording-a-playbook"></div>

## Запись плейбука

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Вместо того чтобы писать шаги, запишите их. Запустив приложение в режиме отладки, откройте `metro live` и введите:

``` plaintext
› record sign_up
```

Затем пользуйтесь приложением. Каждое нажатие, долгое нажатие, введённое поле, прокрутка и смена страницы записываются как шаг в момент действия и выводятся по ходу:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Нажмите Enter, чтобы завершить. Плейбук записывается в `lib/app/playbooks/sign_up_playbook.dart`, оформленный так же, как создаваемый `make:playbook`, и регистрируется, поэтому он воспроизводится, как только приложение перезапустится. `metro make:playbook sign_up --record` делает то же самое из терминала: открывает `metro live` и сразу начинает запись.

Во время записи введите клавишу и нажмите Enter:

| Клавиша | Описание |
|---------|----------|
| `a` | Следующее нажатие записывается как `see(...)` вместо нажатия |
| `d` | Следующее нажатие записывается как `dontSee(...)` |
| `s <title>` | Начинает `scene` с этим заголовком; следующие шаги вкладываются в неё |
| `u` | Удаляет последний шаг |
| `p` | Приостанавливает запись, например чтобы войти с настоящими учётными данными; повторное `p` возобновляет |
| Enter | Завершает и записывает плейбук |
| Ctrl+C | Отбрасывает запись |

Цели называются так, как их читает проигрыватель, сначала видимый текст, и каждое имя проверяется на запущенном приложении перед записью, поэтому записанный шаг воспроизводится. Когда простая строка соответствует нескольким виджетам, шаг сужает её с помощью `Find`: `tap(Find.text('Add').at(1))`. Кнопка-значок без текста и подсказки становится `tap(Find.icon(Icons.add))`. Виджет, который ничто не называет, записывается как `tap(Find.type<GestureDetector>().at(3))` под комментарием `// TODO` с файлом и строкой, где он был создан, и Metro перечисляет его по окончании записи. Задайте ему `Key`, затем используйте ключ вместо этого.

- **Ввод текста** становится шагом `type`, читающим заполнитель `data[...]`, с введённым значением в `defaults`. Поле, оставленное с клавишей действия клавиатуры, получает `submit: true`. Скрытое поле получает `secret: true`, его значение в `defaults` и предупреждение изменить его перед коммитом. Передайте `--no-data`, чтобы записывать значения как они были введены.
- **Смены страниц** после нажатия становятся `waitForPage(SignUpPage.path)`, если одна из ваших страниц объявляет маршрут, или `waitForPage('/sign-up')` в противном случае. Возврат назад становится `back()`.
- **Прокрутка** записывается как `scrollTo(...)` для того, на что вы нажмёте следом, и только когда прокрутка вывела это в дерево. Всё остальное проигрыватель раскрывает сам.
- **Свайпы, не являющиеся прокруткой** -- `Dismissible`, `PageView`, `Slider` -- пока не имеют глагола плейбука, поэтому остаются комментарием `// TODO` с начальной и конечной точками.

Для записи нужна отладочная сборка; она работает на симуляторах, эмуляторах и устройствах. Собственные касания плейбука никогда не записываются. Опции команды см. в [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey).

<div id="steps"></div>

## Шаги

`steps` плейбука строятся из унаследованных им глаголов:

| Шаг | Описание |
|------|-------------|
| `tap(target)` | Нажать на цель |
| `longPress(target)` | Нажать и удерживать цель |
| `type(target, text)` | Ввести текст в поле. Сначала очищает его, если не указано `clear: false`; `submit: true` нажимает кнопку действия на клавиатуре; `secret: true` скрывает текст в подписи и отчёте |
| `scrollTo(target)` | Прокручивать, пока цель не появится на экране. Передайте `within:`, чтобы прокручивать конкретный scrollable |
| `see(target)` | Проверить, что цель есть на экране, дождавшись её появления |
| `dontSee(target)` | Проверить, что цели нет на экране, дождавшись её исчезновения |
| `waitFor(target)` | Ждать цель не дольше `timeout` (по умолчанию 10 секунд) |
| `waitForPage(path)` | Ждать страницу по пути маршрута или `RouteView` (например, `HomePage.path`) |
| `visit(path)` | Открыть страницу по пути маршрута или `RouteView`, с необязательным `data:` |
| `back()` | Вернуться на страницу назад |
| `pause(seconds: 1)` | Замереть, например чтобы дать демо передохнуть |
| `step(title, action)` | Выполнить любой код с теми же глаголами через `PlaybookActor` |
| `scene(title, steps)` | Сгруппировать шаги под заголовком; их подписи начинаются с него, а результаты вкладываются в отчёт |
| `screenshot(name)` | Сделать снимок экрана -- см. [Скриншоты](#screenshots) |
| `play(playbook)` | Запустить другой плейбук -- см. [Запуск других плейбуков](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

Шаги выполняются по порядку и останавливаются на первой ошибке. Остальные шаги отмечаются в отчёте как пропущенные.

<div id="targets"></div>

## Цели

Большинство шагов принимает цель: на что нажать, куда вводить текст или что искать.

<div id="plain-strings"></div>

### Простые строки

Простая строка сопоставляется так, как человек читает экран. Она пробует по порядку:

1. Видимый текст
2. Подсказку (hint) или метку поля
3. Подсказку tooltip
4. Метку semantics
5. `ValueKey`
6. Текст, содержащий строку

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

`Key`, `Type` или `IconData` тоже можно передать прямо в шаг.

<div id="find"></div>

### Find

Используйте `Find`, когда простой строки недостаточно для точности:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Находит |
|--------|---------|
| `Find.text(text)` | Видимый текст |
| `Find.textContaining(text)` | Текст, содержащий строку |
| `Find.icon(icon, label:)` | Значок. `label:` задаёт его имя в подписях шагов |
| `Find.type<T>()` | Виджет типа `T` |
| `Find.key(key)` | Виджет с заданным ключом |
| `Find.field(name)` | Поле `NyForm` по имени |
| `Find.input(text)` | Текстовое поле по подсказке, метке или placeholder |
| `Find.tooltip(message)` | Tooltip |
| `Find.semantics(label)` | Метка semantics |
| `Find.where(test)` | Виджеты, проходящие вашу проверку |

Задайте значку `label`, и подписи его шагов будут читаться `Tap the bag icon` вместо `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

Сузьте любой finder с помощью `.inside(ancestor)`, `.at(index)` (отсчёт с 0), `.first` или `.last`.

Нажатие отклоняется, если цель перекрыта чем-то посторонним, например барьером диалога.

<div id="data-and-setup"></div>

## Данные и подготовка

`defaults` содержит данные, с которыми начинаются шаги. Читайте их через `data` внутри `steps`. `setUp` выполняется перед шагами, а `tearDown` -- после них, даже если шаг завершился ошибкой:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` запускает [сидеры](/docs/{{ $version }}/metro-live#seeders) (экземпляры или зарегистрированные имена) и записывает их так же, как `seed` в `metro live`.

<div id="playing-other-playbooks"></div>

## Запуск других плейбуков

`play()` целиком запускает другой плейбук с его собственными `setUp` и `tearDown`. Передайте экземпляр или имя, под которым он зарегистрирован:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Данные** объединяются в таком порядке: `defaults` дочернего плейбука, затем `data` родителя, затем `data:`, переданные в `play`.
- **Скорость** наследуется от родителя, если вы не передали `speed:`.
- **Результаты** вкладываются в отчёте под шагом.
- **Ошибка дочернего плейбука** приводит к ошибке шага.
- **Циклы** отклоняются, а вложенность плейбуков ограничена 8 уровнями.

<div id="speed"></div>

## Скорость

`PlaybookSpeed` задаёт темп каждого ожидания:

| Скорость | Описание |
|-------|-------------|
| `PlaybookSpeed.instant` | Никаких ожиданий, кроме необходимых для прокрутки. Предназначена для тестов |
| `PlaybookSpeed.fast` | Быстро, но за происходящим всё ещё легко следить |
| `PlaybookSpeed.natural` | Темп человека, пользующегося приложением. Значение по умолчанию |
| `PlaybookSpeed.slow` | Достаточно медленно, чтобы комментировать происходящее |

Масштабируйте любую скорость с помощью `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // в два раза медленнее
```

Скорость, переданная в `run()`, `--speed` из Metro или скорость плейбука, который запускает этот, имеет приоритет над собственной скоростью плейбука.

<div id="finger-and-caption"></div>

## Палец и подпись

Палец и подписи шагов по умолчанию выключены. Переопределите `showFinger` и `caption`, чтобы включить их:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // или .top
```

По умолчанию `showFinger` равен `false`, а `caption` -- `PlaybookCaption.hidden`; `PlaybookCaption` принимает значения `top`, `bottom` или `hidden`. Нажатия и свайпы выполняются в любом случае. Плейбук, запущенный из этого через `play()`, использует собственную настройку, а настройка этого возвращается после его завершения. Чтобы скрыть и то и другое на время запуска, используйте `--no-overlay` из Metro или `run(overlay: false)` из Dart.

До nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0) палец и нижняя подпись были включены по умолчанию. Добавьте переопределения выше, чтобы сохранить прежний вид.

<div id="screenshots"></div>

## Скриншоты

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Добавьте шаг `screenshot` везде, где экран стоит сохранить:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` сохраняет каждый как `screenshots/<playbook>/<name>.png`, без каких-либо флагов. Запуск из кода собирает их через [`onScreenshot`](#collecting-screenshots), а любой другой запуск, например `PLAYBOOK_ON_BOOT`, просто ждёт, пока экран устоится. Имя начинается с буквы или цифры и состоит из букв, цифр, `-`, `_` и `.`.

| Параметр | Описание |
|----------|----------|
| `name` | Имя, под которым сохраняется скриншот |
| `locales` | Снять один раз в каждом из этих кодов языков. `Screenshot.allLocales` -- это каждый язык, входящий в приложение |
| `themes` | Снять один раз в каждой из этих id тем. `Screenshot.allThemes` -- это каждая зарегистрированная тема |
| `caption` | Текст над экраном, когда запуск помещает скриншоты в рамку, или ключ перевода |
| `native` | Снимать через Metro, на iOS Simulator и Android (по умолчанию `true`) |

На iOS Simulator и Android Metro снимает весь экран устройства, поэтому строка состояния и клавиатура попадают в кадр, а палец и подпись при этом скрыты. Добавьте `--clean-status-bar` к запуску, чтобы получить 9:41 с полным сигналом и зарядом. В остальных случаях, а также в запусках с `--store` и `--frame` приложение снимает свой экран само. Передайте `native: false`, чтобы всегда использовать снимок приложения:

``` dart
screenshot('checkout', native: false),
```

Чтобы вместо этого сохранить экран каждого шага, запустите `metro live:run playbook <name> --screenshots`. Опции, подгоняющие размер скриншотов под App Store и Google Play, см. в [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots), а класс `Screenshot` за ними -- в [Скриншоты](/docs/{{ $version }}/screenshots).

<div id="screenshots-locales-and-themes"></div>

### Локали и темы

Задайте шагу `screenshot` `locales`, `themes` или оба, чтобы снять один и тот же экран по разу в каждой комбинации:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

Приложение переключается на месте, сохраняя страницу и её состояние, и переключается обратно перед следующим шагом. Каждый снимок сохраняется в папке, названной по его локали и теме:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Чтобы вместо этого проиграть весь плейбук в каждом языке и теме, передайте `--locales` и `--themes` в `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Сбор из кода

`run()` принимает `onScreenshot`, чтобы получать скриншоты запуска как `PlaybookShot`, что удобно для теста или ваших собственных инструментов:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` выбирает, какие экраны снимаются:

| Режим | Снимает |
|-------|---------|
| `PlaybookScreenshots.off` | Ничего |
| `PlaybookScreenshots.failures` | Только экран на упавшем шаге |
| `PlaybookScreenshots.marked` | Шаги `screenshot()` и упавшие шаги |
| `PlaybookScreenshots.auto` | Шаги `screenshot()` плейбука, в котором они есть, и каждый шаг плейбука, в котором их нет. Упавшие шаги тоже. Значение по умолчанию |
| `PlaybookScreenshots.all` | Каждый шаг, шаги `screenshot()` и упавшие шаги |

`pixelRatio` задаёт размер скриншотов относительно размера устройства (по умолчанию `1.0`). У каждого `PlaybookShot` есть `screenshot`, который можно [сохранить](/docs/{{ $version }}/screenshots#saving-a-screenshot) или прочитать из него `bytes`, а также `kind` и `name`, `locale` и `theme`, с которыми он был снят.

<div id="registering-playbooks"></div>

## Регистрация плейбуков

Зарегистрируйте плейбуки, чтобы Metro и `play('name')` могли их найти:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` возвращает зарегистрированные плейбуки. Их также можно передать при настройке Nylo в вашем `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` делает это за вас. Он хранит реестр в `lib/bootstrap/playbooks.dart`, добавляет в него каждый новый плейбук и передаёт `playbooks` в `nylo.configure(...)`. В релизных сборках реестр -- пустой map:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Зарегистрированный плейбук запускается под своим ключом. Незарегистрированный запускается под своим `name` или под именем класса в snake case (`SignUpPlaybook` -- это `sign_up`).

<div id="running-a-playbook"></div>

## Запуск плейбука

<div id="running-from-metro"></div>

### Из Metro

Когда приложение запущено в режиме debug:

``` bash
metro live:run playbook sign_up --speed fast
```

Внутри `metro live` введите `playbook sign_up`. Все опции см. в [Metro Live](/docs/{{ $version }}/metro-live#playbooks). Добавьте `--record`, чтобы сохранить видео запуска; см. [Запись](/docs/{{ $version }}/metro-live#recording-playbooks). Если запуск перестаёт ждать через 20 секунд, приложение, вероятно, было запущено с `flutter run --no-dds`; см. [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

<div id="running-on-boot"></div>

### При запуске приложения

Добавьте эти ключи в файл `.env`, чтобы плейбуки запускались, как только приложение появится на экране:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` -- это список имён плейбуков через запятую, а `PLAYBOOK_SPEED` -- одно из значений `instant`, `fast`, `natural` или `slow`. Оба действуют только в debug- и profile-сборках.

<div id="running-from-dart"></div>

### Из Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` принимает `speed`, `data`, `clock`, `overlay` (по умолчанию `true`), `onEvent`, а также `onScreenshot`, `screenshots` и `pixelRatio` для [сбора скриншотов](#collecting-screenshots). Упавший шаг не выбрасывает исключение -- проверяйте `report.passed`. Одновременно может выполняться только один плейбук; используйте `play()`, чтобы запустить один внутри другого.

Остановите выполняющийся плейбук с помощью `Playbook.stop()`. Остальные шаги пропускаются, но `tearDown` всё равно выполняется. `Playbook.isRunning` сообщает, выполняется ли плейбук.

<div id="reports"></div>

## Отчёты

`run()` возвращает `PlaybookReport` с `name` плейбука, `speed`, временем `elapsed`, признаком остановки (`stopped`) и списком `steps` из `PlaybookStepResult` (у каждого есть `title`, `status`, `elapsed`, `error` и вложенные `steps`). `report.passed` равен true, когда все шаги пройдены, а вывод отчёта выглядит как вывод Metro. `report.toJson()` возвращает те же данные в виде JSON.

<div id="release-builds"></div>

## Релизные сборки

Плейбуки никогда не запускаются в релизной сборке, поэтому сценарий не может управлять приложением пользователя:

- `Playbook.run()` выбрасывает `UnsupportedError`.
- `addPlaybooks` игнорируется.
- Сгенерированный Metro реестр `lib/bootstrap/playbooks.dart` пуст.

`Playbook.isAvailable` сообщает, может ли текущая сборка запускать плейбуки.

<div id="testing"></div>

## Тестирование

Запустите плейбук в виджет-тесте с помощью `NyTesterClock`, затем проверьте его отчёт через `expectPlaybookPassed`:

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Об интеграционных тестах см. [Тестирование плейбуков](/docs/{{ $version }}/testing#testing-playbooks).
