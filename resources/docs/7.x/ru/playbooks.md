# Плейбуки
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в плейбуки")
- [Создание плейбука](#creating-a-playbook "Создание плейбука")
- [Шаги](#steps "Шаги, которые может выполнять плейбук")
- [Цели](#targets "Поиск элементов на экране")
  - [Простые строки](#plain-strings "Как сопоставляется простая строка")
  - [Find](#find "Точные цели с помощью Find")
- [Данные и подготовка](#data-and-setup "Данные, setUp и tearDown")
- [Запуск других плейбуков](#playing-other-playbooks "Запуск плейбука из другого плейбука")
- [Скорость](#speed "PlaybookSpeed")
- [Палец и подпись](#finger-and-caption "Палец и подпись шага")
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
| `Find.icon(icon)` | Значок |
| `Find.type<T>()` | Виджет типа `T` |
| `Find.key(key)` | Виджет с заданным ключом |
| `Find.field(name)` | Поле `NyForm` по имени |
| `Find.input(text)` | Текстовое поле по подсказке, метке или placeholder |
| `Find.tooltip(message)` | Tooltip |
| `Find.semantics(label)` | Метка semantics |
| `Find.where(test)` | Виджеты, проходящие вашу проверку |

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

Внутри `metro live` введите `playbook sign_up`. Все опции см. в [Metro Live](/docs/{{ $version }}/metro-live#playbooks). Добавьте `--record`, чтобы сохранить видео запуска; см. [Запись](/docs/{{ $version }}/metro-live#recording-playbooks).

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

`run()` принимает `speed`, `data`, `clock`, `overlay` (по умолчанию `true`) и `onEvent`. Упавший шаг не выбрасывает исключение -- проверяйте `report.passed`. Одновременно может выполняться только один плейбук; используйте `play()`, чтобы запустить один внутри другого.

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
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Об интеграционных тестах см. [Тестирование плейбуков](/docs/{{ $version }}/testing#testing-playbooks).
