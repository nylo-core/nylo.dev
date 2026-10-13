# 剧本
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [简介](#introduction "Playbook（剧本）简介")
- [创建剧本](#creating-a-playbook "创建剧本")
- [步骤](#steps "剧本可以执行的步骤")
- [目标](#targets "在屏幕上查找元素")
  - [纯字符串](#plain-strings "纯字符串的匹配方式")
  - [Find](#find "使用 Find 精确指定目标")
- [数据与设置](#data-and-setup "数据、setUp 与 tearDown")
- [运行其他剧本](#playing-other-playbooks "在一个剧本中运行另一个剧本")
- [速度](#speed "PlaybookSpeed")
- [手指与字幕](#finger-and-caption "手指与步骤字幕")
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

**剧本**（Playbook）是一个列出用户操作流程中的点击、输入、滚动和页面切换的类。正在运行的应用会自己执行这些操作，并显示可见的手指和每一步的字幕 -- 非常适合演示、屏幕录制以及端到端地检查某个流程。

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
| `Find.icon(icon)` | 图标 |
| `Find.type<T>()` | 类型为 `T` 的组件 |
| `Find.key(key)` | 带有该 key 的组件 |
| `Find.field(name)` | 按名称指定的 `NyForm` 字段 |
| `Find.input(text)` | 按提示文字、标签或占位符指定的文本字段 |
| `Find.tooltip(message)` | 工具提示 |
| `Find.semantics(label)` | 语义标签 |
| `Find.where(test)` | 通过您的测试的组件 |

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

重写 `showFinger` 和 `caption` 以更改覆盖层的绘制内容：

``` dart
@override
bool get showFinger => false;

@override
PlaybookCaption get caption => PlaybookCaption.top;
```

`PlaybookCaption` 可以是 `top`、`bottom`（默认）或 `hidden`。即使没有手指，点击和滑动仍会发生。若要在某次运行中关闭整个覆盖层，请在 Metro 中使用 `--no-overlay`，或在 Dart 中使用 `run(overlay: false)`。

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

在 `metro live` 中，输入 `playbook sign_up`。所有选项请参阅 [Metro Live](/docs/{{ $version }}/metro-live#playbooks)。

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

`run()` 接受 `speed`、`data`、`clock`、`overlay`（默认为 `true`）和 `onEvent`。步骤失败不会抛出异常 -- 请检查 `report.passed`。同一时间只能运行一个剧本；若要在一个剧本中运行另一个，请使用 `play()`。

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
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

集成测试请参阅 [测试剧本](/docs/{{ $version }}/testing#testing-playbooks)。
