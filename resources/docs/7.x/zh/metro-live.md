# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [简介](#introduction "Nylo Live 简介")
- metro live Shell
  - [通用选项](#shared-options "所有 live 命令都接受的选项")
  - [Tab 补全与历史记录](#tab-completion-and-history "Tab 补全与历史记录")
  - [运行脚本](#running-a-script "运行 .live 脚本")
- [live:devices](#live-devices "列出正在运行的应用")
- [live:status](#live-status "显示应用状态")
- [live:run](#live-run "运行内置的 live 命令")
- [剧本](#playbooks "通过 Metro 运行剧本")
- 实时命令
  - [创建实时命令](#creating-a-live-command "创建实时命令")
  - [运行实时命令](#running-a-live-command "运行实时命令")
- 填充器
  - [创建填充器](#creating-a-seeder "创建填充器")
  - [填充与回滚](#seeding-and-rolling-back "填充与回滚")
- 存储快照
  - [导出快照](#exporting-a-snapshot "导出快照")
  - [加载快照](#loading-a-snapshot "加载快照")
- [页面操作](#page-actions "从外部调用页面")

<div id="introduction"></div>

## 简介

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** 让 Metro 能够通过 Dart VM 服务，深入到以调试（或 profile）模式运行的应用中。它通过 Dart Tooling Daemon 发现应用，无需任何配置，并且只会连接由当前项目构建出的应用。

Nylo Live 在调试构建和 profile 构建中默认开启，发布构建则不会注册任何内容。只读取应用状态的命令（`live:status`、`live:run data`、`live:run routes` 等）在 profile 构建中也可以使用。会更改应用的命令（`route`、`storage`、`auth`、填充器等）则需要调试构建。

您也可以在其他任何操作运行之前，自行在 provider 中将其关闭：

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

## metro live Shell

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

打开一个连接到正在运行的应用的 Shell：

``` bash
metro live
```

Nylo Live 会发现项目中正在运行的应用并连接其中一个 -- 如果运行的不止一个，会询问您要选择哪一个。连接后，提示符会显示当前设备以及屏幕上正在显示的路由：

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

在 Shell 内，实时命令会省略 `metro live:` 前缀 -- 输入 `status` 而不是 `metro live:status`，输入 `route /profile` 而不是 `metro live:run route /profile`。有几个命令只能在 Shell 内运行：`seed`、`seed:rollback`、`export`、`reload` 和 `restart`。输入 `help` 可列出所有命令，输入 `help <command>` 可查看某个命令的选项。输入 `exit`（或 Ctrl+D）可退出 Shell。

如果应用在连接期间重启，Shell 会自动重新连接。

<div id="shared-options"></div>

### 通用选项

每个实时命令 -- 无论是在 Shell 内还是作为 `metro live:*` -- 都接受相同的选项：

| 选项 | 描述 |
|--------|-------------|
| `-d, --device <#\|name>` | 通过 `metro live:devices` 中的编号或设备名称指定应用 |
| `--all` | 在该项目所有正在运行的应用上执行 |
| `--json` | 输出机器可读的 JSON |
| `--uri` | 使用此 VM 服务地址，而不进行应用发现 |
| `--timeout` | 发现应用时，等待每个应用的秒数（默认 `3`） |

写作 `@path` 形式的选项值会从该文件中读取 -- 这对较大的 `--data` 负载很有用：

``` bash
metro live:run route /profile --data @user.json
```

如果值本身就以 `@` 开头，请以 `@@` 开始书写。

<div id="tab-completion-and-history"></div>

### Tab 补全与历史记录

在 Shell 内，按 Tab 键可根据已连接的应用补全命令名称、选项、路由、存储与 Backpack 的键，填充器名称以及剧本名称。历史记录保存在 `.dart_tool/nylo/live_history` 中，并在多次会话之间保留。

<div id="running-a-script"></div>

### 运行脚本

将文件通过管道传给 `metro live`，即可将其作为脚本运行，每行一条命令，遇到第一个失败的命令即停止：

``` bash
metro live < seeds/demo.live
```

空行和以 `#` 开头的行会被跳过。

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

列出该项目正在运行的应用，并为 `-d` 编号：

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

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

显示应用、设备、构建模式、当前路由与路由栈、语言环境、主题，以及是否有用户登录：

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

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

运行一个用于检查或驱动应用的内置命令：

``` bash
metro live:run <command> [arguments]
```

| 命令 | 描述 |
|---------|-------------|
| `data` | 显示屏幕上页面的数据与字段 |
| `routes` | 列出已注册的路由 |
| `route` | 打开一个页面，可附带 `--data` |
| `back` | 返回上一页，或返回某个路由 |
| `deeplink` | 打开一个深层链接，或显示其配置方式 |
| `storage` | 列出本地存储，或显示、保存、删除某个值 |
| `storage:clear` | 清空本地存储，可附带 `--keep` |
| `backpack` | 列出 Backpack 中的值，或显示、保存、删除某一个 |
| `auth` | 显示当前登录的用户，或进行登录、退出登录 |
| `locale` | 切换应用的语言 |
| `theme` | 切换应用的主题 |
| `state` | 通过 `updateState` 向某个 state 发送数据 |
| `event` | 触发您自己定义的某个事件 |
| `toast` | 显示提示消息 |
| `playbook` | 运行您的[剧本](/docs/{{ $version }}/playbooks)，或列出它们 |

在 `metro live` 内，省略 `live:run` 部分 -- 输入 `route /profile` 而不是 `metro live:run route /profile`：

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

`data` 还会直接从正在运行的应用中，读取页面 state 所声明的字段 -- 由于 Flutter 没有反射机制，这只对当前实际显示在屏幕上的 state 有效：

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## 剧本

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

在正在运行的应用上运行一个或多个[剧本](/docs/{{ $version }}/playbooks)。每一步在发生时都会打印出来：

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

指定多个剧本名称可使其依次连续运行。不带名称，或使用 `--list` 时，该命令会列出应用的剧本。

| 选项 | 描述 |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | 以此速度运行，而不是使用各剧本自身的速度 |
| `--data <json>` | 本次运行中每个剧本都会在 `data` 中读取的 JSON 对象，优先于其默认值 |
| `--no-overlay` | 隐藏倒计时，以及显示手指和字幕的剧本中的手指和字幕 |
| `--loop` | 反复运行这些剧本，直到按下 Ctrl+C |
| `--record[=<file>]` | 在运行前后录制设备屏幕。保存到 `build/playbooks/<playbooks>-<device>-<time>.mp4`；`--record=demo.mp4`（或 `.gif`）可指定文件，路径相对于项目。需要 ffmpeg |
| `--no-captions` | 不生成录制内容的 `.vtt` 字幕文件和章节 |
| `--clean-status-bar` | 剧本运行期间将状态栏显示为 9:41、满格信号和满电量，结束后恢复原状。不带 `--record` 也可使用 |
| `--countdown <seconds>` | 在第一步之前于屏幕上倒计时 0-10 秒 |
| `--list` | 列出应用的剧本，而不是运行某个剧本 |

按下 Ctrl+C 会请求应用停止：其余步骤将被跳过，但 `tearDown` 仍会运行。再按一次 Ctrl+C 可停止等待。

在 `metro live` Shell 内，该命令为 `playbook <name>`，Tab 键可补全剧本名称：

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### 录制

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

添加 `--record` 即可保存运行的视频：

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

在 `metro live` Shell 内，输入 `playbook sign_up --record`。

录制需要 ffmpeg（例如 `brew install ffmpeg`）。Metro 会在运行任何内容之前先检查它是否存在。

- **iOS 模拟器** -- 使用 `xcrun simctl` 录制。
- **Android 模拟器或设备** -- 使用 `adb screenrecord` 录制（3 分钟后会停止），若已安装 `scrcpy` 则使用它（无时长限制，在 Android 11+ 上还会录制声音）。如果连接了多台 Android 设备，而 Metro 无法判断哪一台在运行应用，请设置 `ANDROID_SERIAL`。
- **真实 iPhone** -- 无法通过命令行录制。请使用 QuickTime 或控制中心录制，并使用 `--countdown 3` 留出开始录制的时间。

结果是一个时长与运行时间完全一致的 `.mp4`（H.264，稳定 60 fps），或一个宽 480px、15 fps 的 `.gif`。每一步都会成为 `.mp4` 旁 `.vtt` 文件中的一条字幕，以及 `.mp4` 内的一个章节。无论剧本是否在屏幕上显示字幕，它们都由运行的步骤生成。传入 `--no-captions` 可跳过它们。

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

失败或被中止的剧本同样会被保存，因此录制内容也可以直接充当错误报告。Ctrl+C 会停止剧本（`tearDown` 仍会运行），录制内容依然会被保存。

`--clean-status-bar` 会保留你在模拟器上已通过 `simctl status_bar` 设置的状态栏。在 Android 上它使用演示模式，并在结束后将其关闭。对于多个应用（`--all`），每台设备都会得到各自的文件，文件名中带有设备名称。

剧本默认隐藏手指和字幕。若要制作演示视频，请在剧本上设置 `showFinger` 和 `caption` -- 参见[手指与字幕](/docs/{{ $version }}/playbooks#finger-and-caption)。

<div id="playbook-live-commands"></div>

### 实时命令参考

其背后的命令是 `playbooks.list`、`playbooks.run` 和 `playbooks.stop`。一次运行会立即返回其 id，并将每一步作为 `nylo.playbook` 事件以流的形式推送，因此运行时间可以超过命令的超时时间。`live:status` 会列出应用的剧本。剧本在 Nylo Live 协议版本 9 中引入。录制支持 -- `playbooks.run` 中的 `countdown`（每秒发送一个 `countdown` 事件）以及 `status` 中的 `simulator`（iOS 模拟器 UDID） -- 在协议版本 10 中引入。

<div id="live-commands"></div>

## 实时命令

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

除了上面这些内置命令外，您还可以注册自己的命令，在应用内运行。

<div id="creating-a-live-command"></div>

### 创建实时命令

``` bash
metro make:command seed_cart --live
```

| 选项 | 描述 |
|--------|-------------|
| `--category`, `-c` | 命令的分类（默认：`app`） |
| `--description` | 命令 `--help` 中显示的一行描述 |
| `--force`, `-f` | 即使文件已存在也强制创建 |

这会在 `lib/app/commands/` 中创建一个 `LiveCommand`，在 `commands.json` 中将其标记为 `"type": "live"`，注册到 `lib/bootstrap/live_commands.dart`，并在 `app_provider.dart` 的 `nylo.configure(...)` 中接入 `liveCommands: liveCommands`：

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

`LiveCommand` 与 Metro 自身的命令共享 `builder`/`handle` 的结构，但 `handle` 是在**应用内部**运行的，因此可以使用 `NyStorage`、`routeTo`、`Auth` 以及您自己的模型。`package:nylo_framework/live.dart` 会将 `LiveCommand` 与 `Seeder`、`StorageSnapshot`、`LiveException`、`LiveOutput` 一起导出。

<div id="running-a-live-command"></div>

### 运行实时命令

新的实时命令需要先进行一次热重启，正在运行的应用才能执行它。然后在终端中运行：

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## 填充器

<!-- uncertain: "Seeder" is new Nylo-specific terminology -- translated as 填充器, following the common zh Laravel-docs convention for "Seeder"; the class name itself stays verbatim as `Seeder` -->

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`Seeder` 会将正在运行的应用置于一个已知状态 -- 已登录、已完成引导流程、带有示例数据等 -- 之后也能将其恢复原状。

<div id="creating-a-seeder"></div>

### 创建填充器

``` bash
metro make:seeder demo_user
```

| 选项 | 描述 |
|--------|-------------|
| `--description` | 一行描述，会在 `seed` 列出填充器时显示 |
| `--force`, `-f` | 即使文件已存在也强制创建 |

这会在 `lib/app/seeders/` 中创建一个 `Seeder`，注册到 `lib/bootstrap/seeders.dart`，并在 `nylo.configure(...)` 中接入 `seeders: seeders`：

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

`up()` 所更改的每一项存储和 Backpack 值都会被自动记录，因此默认的 `down()`（即 `restore()`）会按相反顺序将它们逐一精确还原 -- 即使中间经历过热重启也是如此。在填充器内部调用 `seed(otherSeeders)`，可以将其他填充器作为其一部分运行；它们的更改同样会被记录并可回滚。

<div id="seeding-and-rolling-back"></div>

### 填充与回滚

填充、回滚和导出只能在 `metro live` Shell 内运行：

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

单独运行 `seed` 会列出已注册的填充器。`--fresh` 会在填充之前清空存储和 Backpack，`--restart` 会在之后对应用进行热重启，使其启动时就已经是填充好的状态：

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## 存储快照

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` 会将应用当前存储和 Backpack 中保存的所有内容 -- 保存为填充器，或保存为可移植的 JSON 文件 -- 而 `seed <file>` 则会将其重新加载回来。

<div id="exporting-a-snapshot"></div>

### 导出快照

不带名称运行时，会预览将要捕获的内容：

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

指定名称后，会将其作为填充器保存到 `lib/app/seeders/`（与 `make:seeder` 相同，只是已经填入了捕获到的值）：

``` plaintext
› export pro_user
```

或者改为写入一个文件：

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| 选项 | 描述 |
|--------|-------------|
| `--to <path>` | 将快照写入此 JSON 文件，而不是生成填充器 |
| `--description` | 填充器的一行描述 |
| `--only <keys>` | 要导出的键，用逗号分隔；`*` 可匹配任意内容，例如 `--only SK_USER,onboarding_*` |
| `--except <keys>` | 要排除的键，用逗号分隔，例如 `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | 是否包含 Backpack 中的值（默认：包含） |
| `--force`, `-f` | 替换已存在的填充器或文件 |

`Model`，或任何拥有已注册解码器的类，都会以类名进行标记，因此恢复时会通过其自身的解码器，而不是变成一个普通的 map。当快照中包含看起来像凭据的键时，或者快照大到您可能需要使用 `--except` 时，`export` 会发出警告。

<div id="loading-a-snapshot"></div>

### 加载快照

``` plaintext
› seed snapshots/pro_user.json
```

加载文件的记录方式与填充器相同，因此 `seed:rollback <name>`（文件名，或 `--as` 指定的名称）同样可以撤销它。在代码中，填充器可以通过 `importSnapshot` 应用已捕获的快照：

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

`export` 写出的正是这种结构，因此用 `--to` 写出的文件可以直接粘贴到某个填充器的 `snapshot` 字段中。

<div id="page-actions"></div>

## 页面操作

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

页面上的 `path.actions` 会给您一个 `PageStateActions`，让您无需手动构造 `stateAction(...)` 调用，即可从外部调用该页面：

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

完整的内置操作列表，以及如何声明您自己的类型化操作，请参见[调用页面的操作](/docs/{{ $version }}/state-management#page-actions-shortcut)。Nylo Live 也会读取并使用相同的操作：`metro live:run data` 会列出屏幕上某个页面在 `actions` 下公开的内容，而 `metro live:run state` 会直接通过页面的 state 名称发送数据。
