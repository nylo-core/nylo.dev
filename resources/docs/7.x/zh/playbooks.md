# 剧本
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [简介](#introduction "Playbook（剧本）简介")
- [创建剧本](#creating-a-playbook "创建剧本")
- [录制剧本](#recording-a-playbook "从运行中的应用录制剧本")
- [步骤](#steps "剧本可以执行的步骤")
- [目标](#targets "在屏幕上查找元素")
  - [纯字符串](#plain-strings "纯字符串的匹配方式")
  - [Find](#find "使用 Find 精确指定目标")
- [数据与设置](#data-and-setup "数据、setUp 与 tearDown")
- [运行其他剧本](#playing-other-playbooks "在一个剧本中运行另一个剧本")
- [速度](#speed "PlaybookSpeed")
- [手指与字幕](#finger-and-caption "手指与步骤字幕")
- [截图](#screenshots "在剧本运行期间截图")
  - [区域设置与主题](#screenshots-locales-and-themes "在每种区域设置和主题下截取屏幕")
  - [从代码中收集](#collecting-screenshots "使用 onScreenshot 收集截图")
- [注册剧本](#registering-playbooks "向 Nylo 注册剧本")
- [运行剧本](#running-a-playbook "运行剧本")
  - [通过 Metro](#running-from-metro "通过 Metro 运行")
  - [启动时](#running-on-boot "启动时运行")
  - [通过 Dart](#running-from-dart "通过 Dart 运行")
- [报告](#reports "PlaybookReport")
- [发布构建](#release-builds "发布构建中的剧本")
- [测试](#testing "测试剧本")

<div id="introduction"></div>

## 简介

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**剧本**（Playbook）是一个列出用户操作流程中的点击、输入、滚动和页面切换的类。正在运行的应用会自己执行这些操作，并且可以显示手指和每一步的字幕 -- 非常适合演示、屏幕录制以及端到端地检查某个流程。

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

在编写或注册剧本的任何地方，都需要导入 `package:nylo_framework/playbook.dart`。

<div id="creating-a-playbook"></div>

## 创建剧本

使用 Metro 创建：

``` bash
metro make:playbook sign_up
```

这会创建 `lib/app/playbooks/sign_up_playbook.dart` 并将其注册到您的应用中。该命令的选项请参阅 [创建剧本](/docs/{{ $version }}/metro#make-playbook)。

若要从路由器添加的每个页面开始，请传入 `--from-routes`：

``` bash
metro make:playbook tour --from-routes
```

Metro 会为每个页面写入一个 `visit()` 和一个 [`screenshot()`](#screenshots)，因此一次运行就能拍下整个应用。

<div id="recording-a-playbook"></div>

## 录制剧本

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

与其手写步骤，不如直接录制。在调试模式下运行应用时，打开 `metro live` 并输入：

``` plaintext
› record sign_up
```

然后使用应用。每一次点击、长按、输入的字段、滚动和页面切换都会在发生的瞬间被写成一个步骤，并实时打印出来：

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

按 Enter 完成。剧本会写入 `lib/app/playbooks/sign_up_playbook.dart`，其布局与 `make:playbook` 创建的相同，并完成注册，因此应用一重启就能运行。`metro make:playbook sign_up --record` 可在终端中完成同样的事情：它会打开 `metro live` 并立即开始录制。

录制时，输入一个按键并按 Enter：

| 按键 | 描述 |
|-----|-------------|
| `a` | 下一次点击会被写成 `see(...)`，而不是点击 |
| `d` | 下一次点击会被写成 `dontSee(...)` |
| `s <title>` | 以该标题开始一个 `scene`；之后的步骤嵌套在其中 |
| `u` | 删除上一步 |
| `p` | 暂停录制，例如用真实凭据登录；再按一次 `p` 恢复 |
| Enter | 完成并写入剧本 |
| Ctrl+C | 放弃录制 |

目标按照播放器读取的方式命名，优先使用可见文本，并且每个名称在写入之前都会针对运行中的应用进行检查，因此录制的步骤可以重放。当一个纯字符串匹配多个组件时，该步骤会使用 `Find` 缩小范围：`tap(Find.text('Add').at(1))`。没有文本或工具提示的图标按钮会变成 `tap(Find.icon(Icons.add))`。没有任何名称可用的组件会被写成 `tap(Find.type<GestureDetector>().at(3))`，并附带一条 `// TODO` 注释，注明其创建所在的文件和行号，Metro 会在录制结束时列出它。请为它指定一个 `Key`，然后改用该 key。

- **输入**会变成读取 `data[...]` 占位符的 `type` 步骤，输入的值放在 `defaults` 中。保留键盘操作键的字段会得到 `submit: true`。隐藏内容的字段会得到 `secret: true`，其值放在 `defaults` 中，并提示您在提交前更改。传入 `--no-data` 可按输入时的原样写入值。
- 点击之后的**页面切换**，当您的某个页面声明了该路由时，会变成 `waitForPage(SignUpPage.path)`，否则变成 `waitForPage('/sign-up')`。返回会变成 `back()`。
- **滚动**会被写成针对您接下来点击的对象的 `scrollTo(...)`，并且仅当滚动使其进入组件树时才会写入。播放器会自行显示其他所有内容。
- **非滚动的滑动** -- `Dismissible`、`PageView`、`Slider` -- 目前还没有对应的剧本动词，因此会保留为带有起点和终点的 `// TODO` 注释。

录制需要调试构建，可在模拟器、仿真器和真机上使用。剧本自身的触摸操作绝不会被录制。该命令的选项请参阅 [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey)。

<div id="steps"></div>

## 步骤

剧本的 `steps` 使用其继承的动词来构建：

| 步骤 | 描述 |
|------|-------------|
| `tap(target)` | 点击目标 |
| `longPress(target)` | 长按目标 |
| `type(target, text)` | 向字段中输入。除非指定 `clear: false`，否则会先清空；`submit: true` 会按下键盘的操作按钮；`secret: true` 会在字幕和报告中隐藏文本 |
| `scrollTo(target)` | 滚动直到目标出现在屏幕上。传入 `within:` 可滚动指定的可滚动区域 |
| `see(target)` | 检查目标在屏幕上，会等待其出现 |
| `dontSee(target)` | 检查目标不在屏幕上，会等待其消失 |
| `waitFor(target)` | 等待目标，最长为 `timeout`（默认 10 秒） |
| `waitForPage(path)` | 等待某个路由路径或 `RouteView`（例如 `HomePage.path`）对应的页面 |
| `visit(path)` | 通过路由路径或 `RouteView` 打开页面，可附带 `data:` |
| `back()` | 返回上一页 |
| `pause(seconds: 1)` | 保持静止，例如让演示有喘息的时间 |
| `step(title, action)` | 通过 `PlaybookActor` 使用相同的动词运行任意代码 |
| `scene(title, steps)` | 用标题对步骤分组；它们的字幕以该标题开头，结果在报告中嵌套显示 |
| `screenshot(name)` | 截取屏幕 -- 参阅[截图](#screenshots) |
| `play(playbook)` | 运行另一个剧本 -- 参阅 [运行其他剧本](#playing-other-playbooks) |

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

步骤按顺序运行，并在第一次失败时停止。其余步骤会被报告为已跳过。

<div id="targets"></div>

## 目标

大多数步骤都接受一个目标：要点击什么、向哪里输入，或者要查找什么。

<div id="plain-strings"></div>

### 纯字符串

纯字符串的匹配方式就像人阅读屏幕一样。它会按以下顺序尝试：

1. 可见文本
2. 字段的提示文字或标签
3. 工具提示
4. 语义标签
5. `ValueKey`
6. 包含该字符串的文本

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

也可以将 `Key`、`Type` 或 `IconData` 直接传给步骤。

<div id="find"></div>

### Find

当纯字符串不够精确时，请使用 `Find`：

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| 查找器 | 匹配 |
|--------|---------|
| `Find.text(text)` | 可见文本 |
| `Find.textContaining(text)` | 包含该字符串的文本 |
| `Find.icon(icon, label:)` | 图标。`label:` 为它在步骤字幕中命名 |
| `Find.type<T>()` | 类型为 `T` 的组件 |
| `Find.key(key)` | 带有该 key 的组件 |
| `Find.field(name)` | 按名称指定的 `NyForm` 字段 |
| `Find.input(text)` | 按提示文字、标签或占位符指定的文本字段 |
| `Find.tooltip(message)` | 工具提示 |
| `Find.semantics(label)` | 语义标签 |
| `Find.where(test)` | 通过您的测试的组件 |

为图标设置 `label` 后，其步骤字幕会显示为 `Tap the bag icon`，而不是 `Tap icon f37d`：

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

任何查找器都可以用 `.inside(ancestor)`、`.at(index)`（从 0 开始计数）、`.first` 或 `.last` 缩小范围。

如果目标被无关的内容（例如对话框遮罩）覆盖，点击会被拒绝。

<div id="data-and-setup"></div>

## 数据与设置

`defaults` 保存步骤开始时使用的数据。在 `steps` 内通过 `data` 读取。`setUp` 在步骤之前运行，`tearDown` 在步骤之后运行，即使某个步骤失败也会运行：

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

`seed([...])` 会运行[填充器](/docs/{{ $version }}/metro-live#seeders)（实例或已注册的名称），并像 `metro live` 中的 `seed` 一样记录它们。

<div id="playing-other-playbooks"></div>

## 运行其他剧本

`play()` 会完整运行另一个剧本，包括它自己的 `setUp` 和 `tearDown`。传入一个实例，或其注册时使用的名称：

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **数据**按以下顺序合并：子剧本的 `defaults`，然后是父剧本的 `data`，最后是传给 `play` 的 `data:`。
- **速度**默认继承自父剧本，除非您传入 `speed:`。
- **结果**在报告中嵌套在该步骤之下。
- **子剧本失败**会导致该步骤失败。
- **循环**会被拒绝，并且剧本最多嵌套 8 层。

<div id="speed"></div>

## 速度

`PlaybookSpeed` 设置每次等待的节奏：

| 速度 | 描述 |
|-------|-------------|
| `PlaybookSpeed.instant` | 除滚动所需之外不做任何等待。适用于测试 |
| `PlaybookSpeed.fast` | 很快，但仍然容易跟上 |
| `PlaybookSpeed.natural` | 人使用应用的节奏。默认值 |
| `PlaybookSpeed.slow` | 慢到可以配合旁白讲解 |

可以用 `.times(n)` 缩放任意速度：

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // 慢两倍
```

传给 `run()` 的速度、Metro 的 `--speed`，或运行本剧本的那个剧本的速度，都会优先于本剧本自身的速度。

<div id="finger-and-caption"></div>

## 手指与字幕

手指和步骤字幕默认是关闭的。重写 `showFinger` 和 `caption` 即可开启：

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // 或 .top
```

`showFinger` 默认为 `false`，`caption` 默认为 `PlaybookCaption.hidden`；`PlaybookCaption` 可以是 `top`、`bottom` 或 `hidden`。无论哪种情况，点击和滑动都会发生。由本剧本通过 `play()` 运行的剧本使用它自己的设置，结束后会恢复为本剧本的设置。若要在某次运行中同时隐藏两者，请在 Metro 中使用 `--no-overlay`，或在 Dart 中使用 `run(overlay: false)`。

在 nylo_support 7.33.0（{{ config('app.name') }} 7.4.0）之前，手指和底部字幕默认是开启的。添加上面的重写即可保留原来的效果。

<div id="screenshots"></div>

## 截图

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

在值得保留屏幕的地方添加一个 `screenshot` 步骤：

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

`metro live:run playbook <name>` 会将每张截图保存为 `screenshots/<playbook>/<name>.png`，无需任何标志。通过代码运行时，会使用 [`onScreenshot`](#collecting-screenshots) 收集截图，而任何其他运行方式（例如 `PLAYBOOK_ON_BOOT`）只会等待屏幕稳定。名称以字母或数字开头，可使用字母、数字、`-`、`_` 和 `.`。

| 参数 | 描述 |
|-----------|-------------|
| `name` | 截图保存时使用的名称 |
| `locales` | 在这些语言代码中各截取一次。`Screenshot.allLocales` 是应用打包的所有语言 |
| `themes` | 在这些主题 id 中各截取一次。`Screenshot.allThemes` 是所有已注册的主题 |
| `caption` | 当运行为截图加框时，显示在屏幕上方的文字，或翻译键 |
| `native` | 通过 Metro 在 iOS 模拟器和 Android 上截取（默认为 `true`） |

在 iOS 模拟器和 Android 上，Metro 会截取设备的整个屏幕，因此状态栏和键盘也会包含在内，截取期间手指和字幕会被隐藏。为运行添加 `--clean-status-bar` 可显示 9:41，并带有满格信号和满电量。在其他情况下，以及在 `--store` 和 `--frame` 运行中，应用会截取自己的屏幕。传入 `native: false` 可始终使用应用自身的截取：

``` dart
screenshot('checkout', native: false),
```

若要改为保存每一步的屏幕，请运行 `metro live:run playbook <name> --screenshots`。有关为 App Store 和 Google Play 调整截图尺寸的选项，参阅 [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots)；有关其背后的 `Screenshot` 类，参阅[截图](/docs/{{ $version }}/screenshots)。

<div id="screenshots-locales-and-themes"></div>

### 区域设置与主题

为 `screenshot` 步骤指定 `locales`、`themes` 或两者，即可在每一种下各截取一次同一屏幕：

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

应用会就地切换，保留页面及其状态，并在下一步之前切换回来。每次截取都会保存在以其区域设置和主题命名的文件夹中：

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

若要让整个剧本在每种语言和主题下运行，请向 `metro live:run playbook` 传入 `--locales` 和 `--themes`。

<div id="collecting-screenshots"></div>

### 从代码中收集

`run()` 接受 `onScreenshot`，以 `PlaybookShot` 的形式接收一次运行的截图，这对测试或您自己的工具很方便：

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

`screenshots` 决定截取哪些屏幕：

| 模式 | 截取内容 |
|------|----------|
| `PlaybookScreenshots.off` | 不截取 |
| `PlaybookScreenshots.failures` | 仅截取失败步骤处的屏幕 |
| `PlaybookScreenshots.marked` | `screenshot()` 步骤以及失败的步骤 |
| `PlaybookScreenshots.auto` | 含有 `screenshot()` 步骤的剧本截取这些步骤，没有的则截取每一步，失败的步骤同样会截取。默认值 |
| `PlaybookScreenshots.all` | 每一步、`screenshot()` 步骤以及失败的步骤 |

`pixelRatio` 设置截图相对于设备的尺寸（默认为 `1.0`）。每个 `PlaybookShot` 都有一个可以[保存](/docs/{{ $version }}/screenshots#saving-a-screenshot)或从中读取 `bytes` 的 `screenshot`，以及 `kind`，还有截取时使用的 `name`、`locale` 和 `theme`。

<div id="registering-playbooks"></div>

## 注册剧本

注册您的剧本，以便 Metro 和 `play('name')` 能够找到它们：

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` 会返回已注册的内容。您也可以在 `AppProvider` 中配置 Nylo 时传入它们：

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` 会为您完成这些工作。它将注册表保存在 `lib/bootstrap/playbooks.dart` 中，把每个新剧本添加进去，并将 `playbooks` 传给 `nylo.configure(...)`。在发布构建中，注册表是一个空的 map：

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

已注册的剧本以其 key 运行。未注册的剧本以其 `name` 运行，或以蛇形命名的类名运行（`SignUpPlaybook` 即 `sign_up`）。

<div id="running-a-playbook"></div>

## 运行剧本

<div id="running-from-metro"></div>

### 通过 Metro

在应用以调试模式运行时：

``` bash
metro live:run playbook sign_up --speed fast
```

在 `metro live` 中，输入 `playbook sign_up`。所有选项请参阅 [Metro Live](/docs/{{ $version }}/metro-live#playbooks)。添加 `--record` 可保存运行的视频；参见[录制](/docs/{{ $version }}/metro-live#recording-playbooks)。如果运行在 20 秒后停止等待，很可能是应用以 `flutter run --no-dds` 启动的；参见 [Metro Live](/docs/{{ $version }}/metro-live#playbooks)。

<div id="running-on-boot"></div>

### 启动时

将以下键添加到您的 `.env` 文件中，即可在应用显示到屏幕上后立即播放剧本：

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` 是以逗号分隔的剧本名称列表，`PLAYBOOK_SPEED` 是 `instant`、`fast`、`natural` 或 `slow` 之一。两者仅在调试和性能分析（profile）构建中生效。

<div id="running-from-dart"></div>

### 通过 Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` 接受 `speed`、`data`、`clock`、`overlay`（默认为 `true`）、`onEvent`，以及用于[收集截图](#collecting-screenshots)的 `onScreenshot`、`screenshots` 和 `pixelRatio`。步骤失败不会抛出异常 -- 请检查 `report.passed`。同一时间只能运行一个剧本；若要在一个剧本中运行另一个，请使用 `play()`。

使用 `Playbook.stop()` 可以停止正在运行的剧本。其余步骤会被跳过，但 `tearDown` 仍会运行。`Playbook.isRunning` 可以告诉您是否有剧本正在运行。

<div id="reports"></div>

## 报告

`run()` 会返回一个 `PlaybookReport`，其中包含剧本的 `name`、`speed`、耗时 `elapsed`、是否被 `stopped`，以及由 `PlaybookStepResult` 组成的 `steps` 列表（每个都有 `title`、`status`、`elapsed`、`error` 以及嵌套的 `steps`）。当所有步骤都通过时，`report.passed` 为 true，打印报告时的呈现方式与 Metro 的输出类似。`report.toJson()` 会以 JSON 形式给出相同的数据。

<div id="release-builds"></div>

## 发布构建

剧本绝不会在发布构建中运行，因此某个流程无法操控用户的应用：

- `Playbook.run()` 会抛出 `UnsupportedError`。
- `addPlaybooks` 会被忽略。
- Metro 生成的 `lib/bootstrap/playbooks.dart` 注册表为空。

`Playbook.isAvailable` 可以告诉您当前构建是否可以运行剧本。

<div id="testing"></div>

## 测试

在组件测试中使用 `NyTesterClock` 运行剧本，然后用 `expectPlaybookPassed` 对其报告进行断言：

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

集成测试请参阅 [测试剧本](/docs/{{ $version }}/testing#testing-playbooks)。
