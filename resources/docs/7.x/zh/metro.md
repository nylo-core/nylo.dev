# Metro CLI 工具

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [安装](#install "安装")
- 生成命令
  - [生成控制器](#make-controller "生成控制器")
  - [生成模型](#make-model "生成模型")
  - [生成页面](#make-page "生成页面")
  - [生成无状态组件](#make-stateless-widget "生成无状态组件")
  - [生成有状态组件](#make-stateful-widget "生成有状态组件")
  - [生成旅程组件](#make-journey-widget "生成旅程组件")
  - [生成 API 服务](#make-api-service "生成 API 服务")
  - [生成事件](#make-event "生成事件")
  - [生成 Provider](#make-provider "生成 Provider")
  - [生成表单](#make-forms "生成表单")
  - [生成路由守卫](#make-route-guard "生成路由守卫")
  - [生成配置文件](#make-config-file "生成配置文件")
  - [生成命令](#make-command "生成命令")
  - [生成状态管理组件](#make-state-managed-widget "生成状态管理组件")
  - [生成导航中心](#make-navigation-hub "生成导航中心")
  - [生成底部弹窗](#make-bottom-sheet-modal "生成底部弹窗")
  - [生成按钮](#make-button "生成按钮")
  - [生成拦截器](#make-interceptor "生成拦截器")
  - [生成环境文件](#make-env-file "生成环境文件")
  - [生成密钥](#make-key "生成密钥")
- 应用图标
  - [构建应用图标](#build-app-icons "构建应用图标")
- 自定义命令
  - [创建自定义命令](#creating-custom-commands "创建自定义命令")
  - [运行自定义命令](#running-custom-commands "运行自定义命令")
  - [为命令添加选项](#adding-options-to-custom-commands "为命令添加选项")
  - [为命令添加标志](#adding-flags-to-custom-commands "为命令添加标志")
  - [辅助方法](#custom-command-helper-methods "辅助方法")
  - [交互式输入方法](#interactive-input-methods "交互式输入方法")
  - [输出格式化](#output-formatting "输出格式化")
  - [文件系统辅助方法](#file-system-helpers "文件系统辅助方法")
  - [JSON 和 YAML 辅助方法](#json-yaml-helpers "JSON 和 YAML 辅助方法")
  - [大小写转换辅助方法](#case-conversion-helpers "大小写转换辅助方法")
  - [项目路径辅助方法](#project-path-helpers "项目路径辅助方法")
  - [平台辅助方法](#platform-helpers "平台辅助方法")
  - [Dart 和 Flutter 命令](#dart-flutter-commands "Dart 和 Flutter 命令")
  - [Dart 文件操作](#dart-file-manipulation "Dart 文件操作")
  - [目录辅助方法](#directory-helpers "目录辅助方法")
  - [验证辅助方法](#validation-helpers "验证辅助方法")
  - [文件脚手架](#file-scaffolding "文件脚手架")
  - [任务运行器](#task-runner "任务运行器")
  - [表格输出](#table-output "表格输出")
  - [进度条](#progress-bar "进度条")


<div id="introduction"></div>

## 简介

Metro 是一个在 {{ config('app.name') }} 框架底层运行的 CLI 工具。
它提供了许多有用的工具来加速开发。

<div id="install"></div>

## 安装

当您使用 `nylo init` 创建新的 Nylo 项目时，`metro` 命令会自动为您的终端配置。您可以在任何 Nylo 项目中立即开始使用它。

从项目目录运行 `metro` 来查看所有可用命令：

``` bash
metro
```

您应该看到类似以下的输出。

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
  make:key
```

<div id="make-controller"></div>

## 生成控制器

- [创建新控制器](#making-a-new-controller "使用 Metro 创建新控制器")
- [强制创建控制器](#forcefully-make-a-controller "使用 Metro 强制创建新控制器")
<div id="making-a-new-controller"></div>

### 创建新控制器

您可以通过在终端中运行以下命令来创建新控制器。

``` bash
metro make:controller profile_controller
```

如果 `lib/app/controllers/` 目录中不存在该控制器，将会创建一个新的。

<div id="forcefully-make-a-controller"></div>

### 强制创建控制器

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的控制器。

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## 生成模型

- [创建新模型](#making-a-new-model "使用 Metro 创建新模型")
- [从 JSON 创建模型](#make-model-from-json "使用 Metro 从 JSON 创建新模型")
- [强制创建模型](#forcefully-make-a-model "使用 Metro 强制创建新模型")
<div id="making-a-new-model"></div>

### 创建新模型

您可以通过在终端中运行以下命令来创建新模型。

``` bash
metro make:model product
```

新创建的模型将放置在 `lib/app/models/` 中。

<div id="make-model-from-json"></div>

### 从 JSON 创建模型

**参数：**

使用 `--json` 或 `-j` 标志将从 JSON 数据创建新模型。

``` bash
metro make:model product --json
```

然后，您可以将 JSON 粘贴到终端中，它将为您生成模型。

<div id="forcefully-make-a-model"></div>

### 强制创建模型

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的模型。

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## 生成页面

- [创建新页面](#making-a-new-page "使用 Metro 创建新页面")
- [创建带控制器的页面](#create-a-page-with-a-controller "使用 Metro 创建带控制器的新页面")
- [创建认证页面](#create-an-auth-page "使用 Metro 创建新认证页面")
- [创建初始页面](#create-an-initial-page "使用 Metro 创建新初始页面")
- [强制创建页面](#forcefully-make-a-page "使用 Metro 强制创建新页面")

<div id="making-a-new-page"></div>

### 创建新页面

您可以通过在终端中运行以下命令来创建新页面。

``` bash
metro make:page product_page
```

如果 `lib/resources/pages/` 目录中不存在该页面，将会创建一个新的。

<div id="create-a-page-with-a-controller"></div>

### 创建带控制器的页面

您可以通过在终端中运行以下命令来创建带控制器的新页面。

**参数：**

使用 `--controller` 或 `-c` 标志将创建带控制器的新页面。

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### 创建认证页面

您可以通过在终端中运行以下命令来创建新认证页面。

**参数：**

使用 `--auth` 或 `-a` 标志将创建新认证页面。

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### 创建初始页面

您可以通过在终端中运行以下命令来创建新初始页面。

**参数：**

使用 `--initial` 或 `-i` 标志将创建新初始页面。

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### 强制创建页面

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的页面。

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## 生成无状态组件

- [创建新无状态组件](#making-a-new-stateless-widget "使用 Metro 创建新无状态组件")
- [强制创建无状态组件](#forcefully-make-a-stateless-widget "使用 Metro 强制创建新无状态组件")
<div id="making-a-new-stateless-widget"></div>

### 创建新无状态组件

您可以通过在终端中运行以下命令来创建新无状态组件。

``` bash
metro make:stateless_widget product_rating_widget
```

上述命令将在 `lib/resources/widgets/` 目录中创建一个新组件（如果不存在）。

所有 `make:*` 命令支持在名称中使用路径分隔符，将文件放入子目录：

``` bash
metro make:stateless_widget login/BrandPanel
```

这将在 `lib/resources/widgets/login/brand_panel.dart` 创建组件。

<div id="forcefully-make-a-stateless-widget"></div>

### 强制创建无状态组件

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的组件。

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## 生成有状态组件

- [创建新有状态组件](#making-a-new-stateful-widget "使用 Metro 创建新有状态组件")
- [强制创建有状态组件](#forcefully-make-a-stateful-widget "使用 Metro 强制创建新有状态组件")

<div id="making-a-new-stateful-widget"></div>

### 创建新有状态组件

您可以通过在终端中运行以下命令来创建新有状态组件。

``` bash
metro make:stateful_widget product_rating_widget
```

上述命令将在 `lib/resources/widgets/` 目录中创建一个新组件（如果不存在）。

<div id="forcefully-make-a-stateful-widget"></div>

### 强制创建有状态组件

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的组件。

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## 生成旅程组件

- [创建新旅程组件](#making-a-new-journey-widget "使用 Metro 创建新旅程组件")
- [强制创建旅程组件](#forcefully-make-a-journey-widget "使用 Metro 强制创建新旅程组件")

<div id="making-a-new-journey-widget"></div>

### 创建新旅程组件

您可以通过在终端中运行以下命令来创建新旅程组件。

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# 如果您有 BaseNavigationHub 的完整示例
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

上述命令将在 `lib/resources/widgets/` 目录中创建一个新组件（如果不存在）。

`--parent` 参数用于指定新旅程组件将添加到的父组件。

示例

``` bash
metro make:navigation_hub onboarding
```

接下来，添加新的旅程组件。
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### 强制创建旅程组件
**参数：**
使用 `--force` 或 `-f` 标志将覆盖已存在的组件。

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## 生成 API 服务

- [创建新 API 服务](#making-a-new-api-service "使用 Metro 创建新 API 服务")
- [创建带模型的新 API 服务](#making-a-new-api-service-with-a-model "使用 Metro 创建带模型的新 API 服务")
- [使用 Postman 创建 API 服务](#make-api-service-using-postman "使用 Postman 创建 API 服务")
- [强制创建 API 服务](#forcefully-make-an-api-service "使用 Metro 强制创建新 API 服务")

<div id="making-a-new-api-service"></div>

### 创建新 API 服务

您可以通过在终端中运行以下命令来创建新 API 服务。

``` bash
metro make:api_service user_api_service
```

新创建的 API 服务将放置在 `lib/app/networking/` 中。

<div id="making-a-new-api-service-with-a-model"></div>

### 创建带模型的新 API 服务

您可以通过在终端中运行以下命令来创建带模型的新 API 服务。

**参数：**

使用 `--model` 或 `-m` 选项将创建带模型的新 API 服务。

``` bash
metro make:api_service user --model="User"
```

新创建的 API 服务将放置在 `lib/app/networking/` 中。

### 强制创建 API 服务

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的 API 服务。

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## 生成事件

- [创建新事件](#making-a-new-event "使用 Metro 创建新事件")
- [强制创建事件](#forcefully-make-an-event "使用 Metro 强制创建新事件")

<div id="making-a-new-event"></div>

### 创建新事件

您可以通过在终端中运行以下命令来创建新事件。

``` bash
metro make:event login_event
```

这将在 `lib/app/events` 中创建一个新事件。

使用路径分隔符将事件整理到子目录中：

``` bash
metro make:event auth/login_event
```

这将在 `lib/app/events/auth/login_event.dart` 创建事件。

<div id="forcefully-make-an-event"></div>

### 强制创建事件

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的事件。

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## 生成 Provider

- [创建新 provider](#making-a-new-provider "使用 Metro 创建新 provider")
- [强制创建 provider](#forcefully-make-a-provider "使用 Metro 强制创建新 provider")

<div id="making-a-new-provider"></div>

### 创建新 provider

使用以下命令在您的应用程序中创建新 provider。

``` bash
metro make:provider firebase_provider
```

新创建的 provider 将放置在 `lib/app/providers/` 中。

使用路径分隔符将 provider 整理到子目录中：

``` bash
metro make:provider integrations/firebase_provider
```

这将在 `lib/app/providers/integrations/firebase_provider.dart` 创建 provider。

<div id="forcefully-make-a-provider"></div>

### 强制创建 provider

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的 provider。

``` bash
metro make:provider firebase_provider --force
```

<div id="make-forms"></div>

## 生成表单

- [创建新表单](#making-a-new-form "使用 Metro 创建新表单")
- [强制创建表单](#forcefully-make-a-form "使用 Metro 强制创建新表单")

<div id="making-a-new-form"></div>

### 创建新表单

您可以通过在终端中运行以下命令来创建新表单。

``` bash
metro make:form car_advert_form
```

这将在 `lib/app/forms` 中创建一个新表单。

使用路径分隔符将表单整理到子目录中：

``` bash
metro make:form checkout/car_advert_form
```

这将在 `lib/app/forms/checkout/car_advert_form.dart` 创建表单。

<div id="forcefully-make-a-form"></div>

### 强制创建表单

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的表单。

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## 生成路由守卫

- [创建新路由守卫](#making-a-new-route-guard "使用 Metro 创建新路由守卫")
- [强制创建路由守卫](#forcefully-make-a-route-guard "使用 Metro 强制创建新路由守卫")

<div id="making-a-new-route-guard"></div>

### 创建新路由守卫

您可以通过在终端中运行以下命令来创建路由守卫。

``` bash
metro make:route_guard premium_content
```

这将在 `lib/app/route_guards` 中创建一个新路由守卫。

使用路径分隔符将路由守卫整理到子目录中：

``` bash
metro make:route_guard subscriptions/premium_content
```

这将在 `lib/app/route_guards/subscriptions/premium_content.dart` 创建路由守卫。

<div id="forcefully-make-a-route-guard"></div>

### 强制创建路由守卫

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的路由守卫。

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## 生成配置文件

- [创建新配置文件](#making-a-new-config-file "使用 Metro 创建新配置文件")
- [强制创建配置文件](#forcefully-make-a-config-file "使用 Metro 强制创建新配置文件")

<div id="making-a-new-config-file"></div>

### 创建新配置文件

您可以通过在终端中运行以下命令来创建新配置文件。

``` bash
metro make:config shopping_settings
```

这将在 `lib/app/config` 中创建一个新配置文件。

<div id="forcefully-make-a-config-file"></div>

### 强制创建配置文件

**参数：**

使用 `--force` 或 `-f` 标志将覆盖已存在的配置文件。

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## 生成命令

- [创建新命令](#making-a-new-command "使用 Metro 创建新命令")
- [强制创建命令](#forcefully-make-a-command "使用 Metro 强制创建新命令")

<div id="making-a-new-command"></div>

### 创建新命令

您可以通过在终端中运行以下命令来创建新命令。

``` bash
metro make:command my_command
```

这将在 `lib/app/commands` 中创建一个新命令。

<div id="forcefully-make-a-command"></div>

### 强制创建命令

**参数：**
使用 `--force` 或 `-f` 标志将覆盖已存在的命令。

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## 生成状态管理组件

您可以通过在终端中运行以下命令来创建新的状态管理组件。

``` bash
metro make:state_managed_widget product_rating_widget
```

上述命令将在 `lib/resources/widgets/` 中创建一个新组件。生成的组件继承 `NyStateManaged`，通过 `stateName` 构造函数参数支持多实例隔离。

``` dart
class ProductRatingWidget extends NyStateManaged {
  ProductRatingWidget({super.key, super.stateName})
      : super(child: () => _ProductRatingWidgetState(stateName));

  static String state = "product_rating_widget";

  static String _stateFor(String? state) =>
      state == null ? ProductRatingWidget.state : "${ProductRatingWidget.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}

class _ProductRatingWidgetState extends NyState<ProductRatingWidget> {
  _ProductRatingWidgetState(String? stateName) {
    this.stateName = ProductRatingWidget._stateFor(stateName);
  }

  @override
  get init => () {
   // 在此处编写初始化逻辑
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // 可从应用的任何位置调用此动作
      // ProductRatingWidget.action("my_action", data: "hello world");
      // ProductRatingWidget.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

使用 `--force` 或 `-f` 标志将覆盖已存在的组件。

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## 生成导航中心

您可以通过在终端中运行以下命令来创建新的导航中心。

``` bash
metro make:navigation_hub dashboard
```

这将在 `lib/resources/pages/` 中创建一个新的导航中心并自动添加路由。

**参数：**

| 标志 | 简写 | 描述 |
|------|-------|-------------|
| `--auth` | `-a` | 创建为认证页面 |
| `--initial` | `-i` | 创建为初始页面 |
| `--force` | `-f` | 如果存在则覆盖 |

``` bash
# 创建为初始页面
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## 生成底部弹窗

您可以通过在终端中运行以下命令来创建新的底部弹窗。

``` bash
metro make:bottom_sheet_modal payment_options
```

这将在 `lib/resources/widgets/` 中创建一个新的底部弹窗。

使用 `--force` 或 `-f` 标志将覆盖已存在的弹窗。

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## 生成按钮

您可以通过在终端中运行以下命令来创建新的按钮组件。

``` bash
metro make:button checkout_button
```

这将在 `lib/resources/widgets/` 中创建一个新的按钮组件。

使用 `--force` 或 `-f` 标志将覆盖已存在的按钮。

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## 生成拦截器

您可以通过在终端中运行以下命令来创建新的网络拦截器。

``` bash
metro make:interceptor auth_interceptor
```

这将在 `lib/app/networking/dio/interceptors/` 中创建一个新的拦截器。

使用 `--force` 或 `-f` 标志将覆盖已存在的拦截器。

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## 生成环境文件

您可以通过在终端中运行以下命令来创建新的环境文件。

``` bash
metro make:env .env.staging
```

这将在项目根目录中创建一个新的 `.env` 文件。

<div id="make-key"></div>

## 生成密钥

为环境加密生成安全的 `APP_KEY`。这用于 v7 中的加密 `.env` 文件。

``` bash
metro make:key
```

**参数：**

| 标志 / 选项 | 简写 | 描述 |
|---------------|-------|-------------|
| `--file` | `-e` | 目标 .env 文件（默认：`.env`） |

``` bash
# 为特定 env 文件生成密钥
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## 构建应用图标

您可以通过运行以下命令来生成 IOS 和 Android 的所有应用图标。

``` bash
dart run flutter_launcher_icons:main
```

这使用 `pubspec.yaml` 文件中的 <b>flutter_icons</b> 配置。

<div id="custom-commands"></div>

## 自定义命令

自定义命令允许您使用项目特定的命令扩展 Nylo 的 CLI。此功能使您能够自动化重复性任务、实现部署工作流程，或直接将任何自定义功能添加到项目的命令行工具中。

- [创建自定义命令](#creating-custom-commands)
- [运行自定义命令](#running-custom-commands)
- [为命令添加选项](#adding-options-to-custom-commands)
- [为命令添加标志](#adding-flags-to-custom-commands)
- [辅助方法](#custom-command-helper-methods)

> **注意：** 目前您不能在自定义命令中导入 nylo_framework.dart，请改用 ny_cli.dart。

<div id="creating-custom-commands"></div>

## 创建自定义命令

要创建新的自定义命令，您可以使用 `make:command` 功能：

```bash
metro make:command current_time
```

您可以使用 `--category` 选项为命令指定类别：

```bash
# 指定类别
metro make:command current_time --category="project"
```

这将在 `lib/app/commands/current_time.dart` 创建一个新的命令文件，结构如下：

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// 当前时间命令
///
/// 用法：
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

      // 获取当前时间
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // 格式化当前时间
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

该命令将自动注册在 `lib/app/commands/commands.json` 文件中，该文件包含所有已注册命令的列表：

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

## 运行自定义命令

创建后，您可以使用 Metro 快捷方式或完整的 Dart 命令运行自定义命令：

```bash
metro app:current_time
```

当您不带参数运行 `metro` 时，您将在菜单中的 "Custom Commands" 部分下看到您的自定义命令：

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

要显示命令的帮助信息，请使用 `--help` 或 `-h` 标志：

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## 为命令添加选项

选项允许您的命令接受用户的额外输入。您可以在 `builder` 方法中为命令添加选项：

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // 添加带默认值的选项
  command.addOption(
    'environment',     // 选项名称
    abbr: 'e',         // 缩写形式
    help: 'Target deployment environment', // 帮助文本
    defaultValue: 'development',  // 默认值
    allowed: ['development', 'staging', 'production'] // 允许的值
  );

  return command;
}
```

然后在命令的 `handle` 方法中访问选项值：

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // 命令实现...
}
```

使用示例：

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## 为命令添加标志

标志是可以开关的布尔选项。使用 `addFlag` 方法为命令添加标志：

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // 标志名称
    abbr: 'v',       // 缩写形式
    help: 'Enable verbose output', // 帮助文本
    defaultValue: false  // 默认关闭
  );

  return command;
}
```

然后在命令的 `handle` 方法中检查标志状态：

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // 其他日志...
  }

  // 命令实现...
}
```

使用示例：

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## 辅助方法

`NyCustomCommand` 基类提供了多个辅助方法来协助常见任务：

#### 打印消息

以下是一些用不同颜色打印消息的方法：

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | 以蓝色文本打印信息消息 |
| [`error`](#custom-command-helper-formatting)     | 以红色文本打印错误消息 |
| [`success`](#custom-command-helper-formatting)   | 以绿色文本打印成功消息 |
| [`warning`](#custom-command-helper-formatting)   | 以黄色文本打印警告消息 |

#### 运行进程

运行进程并在控制台中显示其输出：

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | 向 `pubspec.yaml` 添加包 |
| [`addPackages`](#custom-command-helper-add-packages) | 向 `pubspec.yaml` 添加多个包 |
| [`runProcess`](#custom-command-helper-run-process) | 运行外部进程并在控制台中显示输出 |
| [`prompt`](#custom-command-helper-prompt)    | 以文本形式收集用户输入 |
| [`confirm`](#custom-command-helper-confirm)   | 提出是/否问题并返回布尔结果 |
| [`select`](#custom-command-helper-select)    | 展示选项列表并让用户选择一个 |
| [`multiSelect`](#custom-command-helper-multi-select) | 允许用户从列表中选择多个选项 |

#### 网络请求

通过控制台发出网络请求：

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | 使用 Nylo API 客户端发出 API 调用 |


#### 加载动画

在执行函数时显示加载动画：

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | 在执行函数时显示加载动画 |
| [`createSpinner`](#manual-spinner-control) | 创建动画实例以进行手动控制 |

#### 自定义命令辅助方法

您还可以使用以下辅助方法来管理命令参数：

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | 从命令参数获取字符串值 |
| [`getBool`](#custom-command-helper-get-bool)   | 从命令参数获取布尔值 |
| [`getInt`](#custom-command-helper-get-int)    | 从命令参数获取整数值 |
| [`sleep`](#custom-command-helper-sleep) | 暂停执行指定时长 |


### 运行外部进程

```dart
// 运行进程并在控制台显示输出
await runProcess('flutter build web --release');

// 静默运行进程
await runProcess('flutter pub get', silent: true);

// 在特定目录中运行进程
await runProcess('git pull', workingDirectory: './my-project');
```

### 包管理

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// 向 pubspec.yaml 添加包
addPackage('firebase_core', version: '^2.4.0');

// 向 pubspec.yaml 添加 dev 包
addPackage('build_runner', dev: true);

// 一次添加多个包
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### 输出格式化

```dart
// 打印带颜色编码的状态消息
info('Processing files...');    // 蓝色文本
error('Operation failed');      // 红色文本
success('Deployment complete'); // 绿色文本
warning('Outdated package');    // 黄色文本
```

<div id="interactive-input-methods"></div>

## 交互式输入方法

`NyCustomCommand` 基类提供了多种方法来在终端中收集用户输入。这些方法使为您的自定义命令创建交互式命令行界面变得简单。

<div id="custom-command-helper-prompt"></div>

### 文本输入

```dart
String prompt(String question, {String defaultValue = ''})
```

向用户显示问题并收集文本响应。

**参数：**
- `question`：要显示的问题或提示
- `defaultValue`：如果用户只按回车键时的可选默认值

**返回：** 用户输入的字符串，或如果未提供输入则返回默认值

**示例：**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### 确认

```dart
bool confirm(String question, {bool defaultValue = false})
```

向用户提出是/否问题并返回布尔结果。

**参数：**
- `question`：要提出的是/否问题
- `defaultValue`：默认答案（true 为是，false 为否）

**返回：** 如果用户回答是则返回 `true`，回答否则返回 `false`

**示例：**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // 用户确认或按下回车键（接受默认值）
  await runProcess('flutter pub get');
} else {
  // 用户拒绝
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### 单选

```dart
String select(String question, List<String> options, {String? defaultOption})
```

展示选项列表并让用户选择一个。

**参数：**
- `question`：选择提示
- `options`：可用选项列表
- `defaultOption`：可选的默认选择

**返回：** 所选选项的字符串

**示例：**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### 多选

```dart
List<String> multiSelect(String question, List<String> options)
```

允许用户从列表中选择多个选项。

**参数：**
- `question`：选择提示
- `options`：可用选项列表

**返回：** 所选选项的列表

**示例：**
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

## API 辅助方法

`api` 辅助方法简化了从自定义命令发出网络请求的过程。

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## 基本使用示例

### GET 请求

```dart
// 获取数据
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST 请求

```dart
// 创建资源
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT 请求

```dart
// 更新资源
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE 请求

```dart
// 删除资源
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH 请求

```dart
// 部分更新资源
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### 带查询参数

```dart
// 添加查询参数
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### 带 Spinner

```dart
// 配合 spinner 使用以获得更好的 UI
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // 处理数据
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Spinner 功能

Spinner 在自定义命令中的长时间运行操作期间提供视觉反馈。它们在命令执行异步任务时显示带有消息的动画指示器，通过显示进度和状态来增强用户体验。

- [使用 spinner](#using-with-spinner)
- [手动 spinner 控制](#manual-spinner-control)
- [示例](#spinner-examples)

<div id="using-with-spinner"></div>

## 使用 spinner

`withSpinner` 方法允许您使用 spinner 动画包装异步任务，该动画在任务开始时自动启动，在完成或失败时停止：

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**参数：**
- `task`：要执行的异步函数
- `message`：spinner 运行时显示的文本
- `successMessage`：成功完成时显示的可选消息
- `errorMessage`：任务失败时显示的可选消息

**返回：** 任务函数的结果

**示例：**
```dart
@override
Future<void> handle(CommandResult result) async {
  // 使用 spinner 运行任务
  final projectFiles = await withSpinner(
    task: () async {
      // 耗时任务（例如，分析项目文件）
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // 继续处理结果
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## 手动 Spinner 控制

对于需要手动控制 spinner 状态的更复杂场景，您可以创建 spinner 实例：

```dart
ConsoleSpinner createSpinner(String message)
```

**参数：**
- `message`：spinner 运行时显示的文本

**返回：** 一个您可以手动控制的 `ConsoleSpinner` 实例

**手动控制示例：**
```dart
@override
Future<void> handle(CommandResult result) async {
  // 创建 spinner 实例
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // 第一个任务
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // 第二个任务
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // 第三个任务
    await runProcess('./deploy.sh', silent: true);

    // 成功完成
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // 处理失败
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## 示例

### 简单任务带 Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // 安装依赖
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### 多个连续操作

```dart
@override
Future<void> handle(CommandResult result) async {
  // 第一个带 spinner 的操作
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // 第二个带 spinner 的操作
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // 第三个带 spinner 的操作
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

### 手动控制的复杂工作流

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // 运行多个步骤并更新状态
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // 完成流程
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

在自定义命令中使用 spinner 可在长时间运行操作期间为用户提供清晰的视觉反馈，创造更精致和专业的命令行体验。

<div id="custom-command-helper-get-string"></div>

### 从选项获取字符串值

```dart
String getString(String name, {String defaultValue = ''})
```

**参数：**

- `name`：要获取的选项名称
- `defaultValue`：如果未提供选项时的可选默认值

**返回：** 选项的字符串值

**示例：**
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

### 从选项获取布尔值

```dart
bool getBool(String name, {bool defaultValue = false})
```

**参数：**
- `name`：要获取的选项名称
- `defaultValue`：如果未提供选项时的可选默认值

**返回：** 选项的布尔值


**示例：**
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

### 从选项获取整数值

```dart
int getInt(String name, {int defaultValue = 0})
```

**参数：**
- `name`：要获取的选项名称
- `defaultValue`：如果未提供选项时的可选默认值

**返回：** 选项的整数值

**示例：**
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

### 暂停指定时长

```dart
void sleep(int seconds)
```

**参数：**
- `seconds`：暂停的秒数

**返回：** 无

**示例：**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## 输出格式化

除了基本的 `info`、`error`、`success` 和 `warning` 方法外，`NyCustomCommand` 还提供额外的输出辅助方法：

```dart
@override
Future<void> handle(CommandResult result) async {
  // 打印纯文本（无颜色）
  line('Processing your request...');

  // 打印空行
  newLine();       // 一个空行
  newLine(3);      // 三个空行

  // 打印静音注释（灰色文本）
  comment('This is a background note');

  // 打印醒目的警告框
  alert('Important: Please read carefully');

  // ask 是 prompt 的别名
  final name = ask('What is your name?');

  // 敏感数据的隐藏输入（例如密码、API 密钥）
  final apiKey = promptSecret('Enter your API key:');

  // 以错误消息和退出码中止命令
  if (name.isEmpty) {
    abort('Name is required');  // 以代码 1 退出
  }
}
```

| 方法 | 描述 |
|--------|-------------|
| `line(String message)` | 打印无颜色的纯文本 |
| `newLine([int count = 1])` | 打印空行 |
| `comment(String message)` | 打印淡化/灰色文本 |
| `alert(String message)` | 打印醒目的警告框 |
| `ask(String question, {String defaultValue})` | `prompt` 的别名 |
| `promptSecret(String question)` | 敏感数据的隐藏输入 |
| `abort([String? message, int exitCode = 1])` | 以错误退出命令 |

<div id="file-system-helpers"></div>

## 文件系统辅助方法

`NyCustomCommand` 包含内置的文件系统辅助方法，因此您无需手动导入 `dart:io` 即可进行常见操作。

### 读写文件

```dart
@override
Future<void> handle(CommandResult result) async {
  // 检查文件是否存在
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // 检查目录是否存在
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // 读取文件（异步）
  String content = await readFile('pubspec.yaml');

  // 读取文件（同步）
  String contentSync = readFileSync('pubspec.yaml');

  // 写入文件（异步）
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // 写入文件（同步）
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // 追加内容到文件
  await appendFile('log.txt', 'New log entry\n');

  // 确保目录存在（如果不存在则创建）
  await ensureDirectory('lib/generated');

  // 删除文件
  await deleteFile('lib/generated/output.dart');

  // 复制文件
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| 方法 | 描述 |
|--------|-------------|
| `fileExists(String path)` | 如果文件存在则返回 `true` |
| `directoryExists(String path)` | 如果目录存在则返回 `true` |
| `readFile(String path)` | 以字符串形式读取文件（异步） |
| `readFileSync(String path)` | 以字符串形式读取文件（同步） |
| `writeFile(String path, String content)` | 写入文件内容（异步） |
| `writeFileSync(String path, String content)` | 写入文件内容（同步） |
| `appendFile(String path, String content)` | 追加文件内容 |
| `ensureDirectory(String path)` | 如果不存在则创建目录 |
| `deleteFile(String path)` | 删除文件 |
| `copyFile(String source, String destination)` | 复制文件 |

<div id="json-yaml-helpers"></div>

## JSON 和 YAML 辅助方法

使用内置辅助方法读写 JSON 和 YAML 文件。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 将 JSON 文件读取为 Map
  Map<String, dynamic> config = await readJson('config.json');

  // 将 JSON 文件读取为 List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // 将数据写入 JSON 文件（默认美化输出）
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // 写入紧凑 JSON
  await writeJson('output.json', data, pretty: false);

  // 向 JSON 数组文件追加项
  // 如果文件包含 [{"name": "a"}]，则追加到该数组
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // 通过此键防止重复
  );

  // 将 YAML 文件读取为 Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| 方法 | 描述 |
|--------|-------------|
| `readJson(String path)` | 将 JSON 文件读取为 `Map<String, dynamic>` |
| `readJsonArray(String path)` | 将 JSON 文件读取为 `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | 将数据写入为 JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | 追加到 JSON 数组文件 |
| `readYaml(String path)` | 将 YAML 文件读取为 `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## 大小写转换辅助方法

无需导入 `recase` 包即可在命名约定之间转换字符串。

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

| 方法 | 输出格式 | 示例 |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## 项目路径辅助方法

标准 {{ config('app.name') }} 项目目录的 getter。这些返回相对于项目根目录的路径。

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

  // 构建相对于项目根目录的自定义路径
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| 属性 | 路径 |
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
| `projectPath(String relativePath)` | 解析项目内的相对路径 |

<div id="platform-helpers"></div>

## 平台辅助方法

检查平台并访问环境变量。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 平台检查
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // 当前工作目录
  info('Working in: $workingDirectory');

  // 读取系统环境变量
  String home = env('HOME', '/default/path');
}
```

| 属性 / 方法 | 描述 |
|-------------------|-------------|
| `isWindows` | 在 Windows 上运行时为 `true` |
| `isMacOS` | 在 macOS 上运行时为 `true` |
| `isLinux` | 在 Linux 上运行时为 `true` |
| `workingDirectory` | 当前工作目录路径 |
| `env(String key, [String defaultValue = ''])` | 读取系统环境变量 |

<div id="dart-flutter-commands"></div>

## Dart 和 Flutter 命令

将常见的 Dart 和 Flutter CLI 命令作为辅助方法运行。每个方法返回进程退出代码。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 格式化 Dart 文件或目录
  await dartFormat('lib/app/models/user.dart');

  // 运行 dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // 运行 flutter pub get
  await flutterPubGet();

  // 运行 flutter clean
  await flutterClean();

  // 为目标构建并附加额外参数
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // 运行 flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // 特定目录
}
```

| 方法 | 描述 |
|--------|-------------|
| `dartFormat(String path)` | 对文件或目录运行 `dart format` |
| `dartAnalyze([String? path])` | 运行 `dart analyze` |
| `flutterPubGet()` | 运行 `flutter pub get` |
| `flutterClean()` | 运行 `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | 运行 `flutter build <target>` |
| `flutterTest([String? path])` | 运行 `flutter test` |

<div id="dart-file-manipulation"></div>

## Dart 文件操作

用于以编程方式编辑 Dart 文件的辅助方法，在构建脚手架工具时很有用。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 向 Dart 文件添加导入语句（避免重复）
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // 在文件最后一个闭合大括号之前插入代码
  // 用于向注册映射添加条目
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // 检查文件是否包含特定字符串
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // 检查文件是否匹配正则表达式模式
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| 方法 | 描述 |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | 向 Dart 文件添加导入（如果已存在则跳过） |
| `insertBeforeClosingBrace(String filePath, String code)` | 在文件最后一个 `}` 之前插入代码 |
| `fileContains(String filePath, String identifier)` | 检查文件是否包含字符串 |
| `fileContainsPattern(String filePath, Pattern pattern)` | 检查文件是否匹配模式 |

<div id="directory-helpers"></div>

## 目录辅助方法

用于处理目录和查找文件的辅助方法。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 列出目录内容
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // 递归列出
  var allEntities = listDirectory('lib/', recursive: true);

  // 查找匹配条件的文件
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // 按名称模式查找文件
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // 递归删除目录
  await deleteDirectory('build/');

  // 复制目录（递归）
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| 方法 | 描述 |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | 列出目录内容 |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | 查找匹配条件的文件 |
| `deleteDirectory(String path)` | 递归删除目录 |
| `copyDirectory(String source, String destination)` | 递归复制目录 |

<div id="validation-helpers"></div>

## 验证辅助方法

用于代码生成时验证和清理用户输入的辅助方法。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 验证 Dart 标识符
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // 要求非空的第一个参数
  String name = requireArgument(result, message: 'Please provide a name');

  // 清理类名（PascalCase，移除后缀）
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // 返回：'User'

  // 清理文件名（snake_case 加扩展名）
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // 返回：'user_model.dart'
}
```

| 方法 | 描述 |
|--------|-------------|
| `isValidDartIdentifier(String name)` | 验证 Dart 标识符名称 |
| `requireArgument(CommandResult result, {String? message})` | 要求非空的第一个参数或中止 |
| `cleanClassName(String name, {List<String> removeSuffixes})` | 清理并转换为 PascalCase 类名 |
| `cleanFileName(String name, {String extension = '.dart'})` | 清理并转换为 snake_case 文件名 |

<div id="file-scaffolding"></div>

## 文件脚手架

使用脚手架系统创建一个或多个带内容的文件。

### 单个文件

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: 实现登录
    return false;
  }
}
''',
    force: false,  // 如果已存在则不覆盖
    successMessage: 'AuthService created',
  );
}
```

### 多个文件

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

`ScaffoldFile` 类接受：

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `path` | `String` | 要创建的文件路径 |
| `content` | `String` | 文件内容 |
| `successMessage` | `String?` | 成功时显示的消息 |

<div id="task-runner"></div>

## 任务运行器

运行一系列命名任务并自动输出状态。

### 基本任务运行器

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
      stopOnError: true,  // 如果此任务失败则停止流水线（默认）
    ),
  ]);
}
```

### 带 Spinner 的任务运行器

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

`CommandTask` 类接受：

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `name` | `String` | 必填 | 输出中显示的任务名称 |
| `action` | `Future<void> Function()` | 必填 | 要执行的异步函数 |
| `stopOnError` | `bool` | `true` | 如果此任务失败是否停止剩余任务 |

<div id="table-output"></div>

## 表格输出

在控制台中显示格式化的 ASCII 表格。

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

输出：

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

## 进度条

为已知项目数量的操作显示进度条。

### 手动进度条

```dart
@override
Future<void> handle(CommandResult result) async {
  // 为 100 个项目创建进度条
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // 增加 1
  }

  progress.complete('All files processed');
}
```

### 带进度处理项目

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // 自动跟踪进度处理项目
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // 处理每个文件
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### 同步进度

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // 同步处理
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

`ConsoleProgressBar` 类提供：

| 方法 | 描述 |
|--------|-------------|
| `start()` | 启动进度条 |
| `tick([int amount = 1])` | 增加进度 |
| `update(int value)` | 将进度设置为特定值 |
| `updateMessage(String newMessage)` | 更改显示的消息 |
| `complete([String? completionMessage])` | 以可选消息完成 |
| `stop()` | 停止但不完成 |
| `current` | 当前进度值（getter） |
| `percentage` | 进度百分比（getter） |