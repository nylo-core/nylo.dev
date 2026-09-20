# 安装

---

<a name="section-1"></a>
- [安装](#install "安装")
- [运行项目](#running-the-project "运行项目")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [清理项目](#nylo-clean "清理项目")
  - [刷新 iOS Pod](#nylo-ios-pod-refresh "刷新 iOS Pod")
  - [不使用别名运行 Metro](#nylo-metro "不使用别名运行 Metro")
  - [更新 CLI](#nylo-self-update "更新 CLI")

<div id="install"></div>

只需三个命令，即可从空文件夹创建并运行一个已配置好路由、网络、主题和代码生成的 Flutter 应用。

<x-doc-strip label="开始之前" items="已安装 Flutter SDK, Dart 3" linkText="完整要求" linkHref="/zh/docs/{{ $version }}/requirements" />

## 安装

请按顺序运行这些命令。每个命令都可以安全地重复运行。

<x-doc-steps>
<x-doc-step number="1" title="全局安装 Nylo CLI">
将 `nylo` 命令安装到您的系统中。

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="创建新项目">
此命令会克隆 {{ config('app.name') }} 模板，使用您的应用名称配置项目，并自动安装所有依赖。

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="设置 Metro 别名">
这将为您的项目配置 `metro` 命令，让您无需完整的 `dart run` 语法即可使用 Metro CLI 命令。

``` bash
cd my_app
nylo init
```

之后请重启终端，或运行 `nylo init` 打印出的 `source` 命令。
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="您将获得" items="预配置的路由和导航
API 服务模板
主题和本地化设置
用于代码生成的 Metro CLI" />


<div id="running-the-project"></div>

## 运行项目

{{ config('app.name') }} 项目像任何标准 Flutter 应用一样运行。

<x-doc-tabs tabs="终端, Android Studio, VS Code">
<x-doc-tab label="终端">

``` bash
flutter run
```

如果构建成功，应用将显示 {{ config('app.name') }} 的默认着陆页。
</x-doc-tab>

<x-doc-tab label="Android Studio">
打开项目文件夹，在目标选择器中选择设备，然后按 **Run**。

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter 文档：运行和调试 ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
打开项目文件夹，然后从命令面板运行 **Debug: Start Without Debugging**。

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter 文档：无断点运行 ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro 会为您生成项目文件。不带参数运行可查看菜单，也可以直接调用命令。

``` plaintext
$ metro

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
```

### 命令参考

每个命令都接收一个名称，例如 `metro make:page settings_page`

<x-doc-commands title="Widget 命令" rows="
metro make:page | 创建新页面
metro make:stateful_widget | 创建有状态组件
metro make:stateless_widget | 创建无状态组件
metro make:state_managed_widget | 创建状态管理组件
metro make:navigation_hub | 创建导航中心（底部导航）
metro make:journey_widget | 创建导航中心的旅程组件
metro make:bottom_sheet_modal | 创建底部弹窗
metro make:button | 创建自定义按钮组件
metro make:form | 创建带验证的表单
" />

<x-doc-commands title="辅助命令" rows="
metro make:model | 创建模型类
metro make:provider | 创建 provider
metro make:api_service | 创建 API 服务
metro make:controller | 创建控制器
metro make:event | 创建事件
metro make:route_guard | 创建路由守卫
metro make:config | 创建配置文件
metro make:interceptor | 创建网络拦截器
metro make:command | 创建自定义 Metro 命令
metro make:env | 从 .env 生成环境配置
" />

### 使用示例

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: strip label "Requires" — no established translation in this locale's existing files -->
<x-doc-strip label="需要" items="nylo_installer 1.8.0+" linkText="更新 CLI" linkHref="#nylo-self-update" />

第 1 步中的 `nylo` 命令不仅仅用于创建项目。从项目目录运行它，还可以清理构建、刷新 iOS Pod、运行测试并检查您的翻译。

<x-doc-commands title="项目命令" rows="
nylo new | 创建新项目
nylo init | 在您的 shell 中设置 metro 别名
nylo clean | 运行 flutter clean 和 flutter pub get
nylo ios:pod-refresh | 重新安装您的 iOS Pod
nylo metro | 在不使用别名的情况下运行 Metro 命令
" />

<x-doc-commands title="测试与翻译" rows="
nylo test | 格式化并运行您的测试
nylo locale:find-untranslated | 查找未使用 .tr() 包装的文本
nylo locale:check-missing-keys | 查找语言文件中缺失的键
" />

<x-doc-commands title="CLI 本身" rows="
nylo self-update | 将 nylo 更新到最新版本
nylo --version | 显示已安装的版本
" />

带选项的命令可以通过 `-h` 列出这些选项，例如 `nylo test -h`。测试相关内容请参阅[运行测试](/docs/{{ $version }}/testing#running-tests)，`locale:` 相关命令请参阅[审计翻译](/docs/{{ $version }}/localization#auditing-translations)。

<div id="nylo-clean"></div>

### 清理项目

`nylo clean` 会运行 `flutter clean`，然后运行 `flutter pub get`。当常规清理不够时，添加平台标志：

``` bash
# 运行 flutter clean，然后运行 flutter pub get
nylo clean

# 同时删除 ios/Pods、ios/.symlinks 和 ios/Podfile.lock，然后运行 pod install --repo-update
nylo clean --ios

# 同时在 android/ 中运行 gradlew clean
nylo clean --android

# 以上两者同时执行
nylo clean --all
```

如果项目没有 `ios/` 或 `android/` 文件夹，该平台会被跳过并显示警告。`pod install` 仅在 macOS 上运行。

<div id="nylo-ios-pod-refresh"></div>

### 刷新 iOS Pod

当 CocoaPods 不同步时，`nylo ios:pod-refresh` 会删除 `ios/Pods`、`ios/.symlinks` 和 `ios/Podfile.lock`，然后在 `ios/` 文件夹中运行 `pod install --repo-update`：

``` bash
nylo ios:pod-refresh
```

请在 macOS 上从项目根目录运行它。与 `nylo clean --ios` 不同，它不会运行 `flutter clean` 或 `flutter pub get`。

<div id="nylo-metro"></div>

### 不使用别名运行 Metro

`nylo metro` 会将其后的所有内容传递给 Metro（`dart run nylo_framework:main`）。请在尚未运行 `nylo init` 之前，或在 Windows 上（`nylo init` 无法为您添加 `metro` 别名）使用它：

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### 更新 CLI

``` bash
nylo self-update
```

此命令会检查 pub.dev，如果有更新版本可用，则重新安装 `nylo_installer`。命令执行完成后，`nylo` 也会在有更新时提示您。当输出不是发往终端时，它会保持静默，因此该提示不会出现在 CI 日志或管道输出中。

查看您已安装的版本：

``` bash
nylo --version
```
