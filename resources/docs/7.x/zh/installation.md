# 安装

---

<a name="section-1"></a>
- [安装](#install "安装")
- [运行项目](#running-the-project "运行项目")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## 安装

### 1. 全局安装 nylo_installer

``` bash
dart pub global activate nylo_installer
```

这将在您的系统上全局安装 {{ config('app.name') }} CLI 工具。

### 2. 创建新项目

``` bash
nylo new my_app
```

此命令会克隆 {{ config('app.name') }} 模板，使用您的应用名称配置项目，并自动安装所有依赖。

### 3. 设置 Metro CLI 别名

``` bash
cd my_app
nylo init
```

这将为您的项目配置 `metro` 命令，让您无需完整的 `dart run` 语法即可使用 Metro CLI 命令。

安装完成后，您将拥有一个完整的 Flutter 项目结构，包含：
- 预配置的路由和导航
- API 服务模板
- 主题和本地化设置
- 用于代码生成的 Metro CLI


<div id="running-the-project"></div>

## 运行项目

{{ config('app.name') }} 项目像任何标准 Flutter 应用一样运行。

### 使用终端

``` bash
flutter run
```

### 使用 IDE

- **Android Studio**：<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">运行和调试</a>
- **VS Code**：<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">无断点运行应用</a>

如果构建成功，应用将显示 {{ config('app.name') }} 的默认着陆页。


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} 包含一个名为 **Metro** 的 CLI 工具，用于生成项目文件。

### 运行 Metro

``` bash
metro
```

这将显示 Metro 菜单：

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
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Metro 命令参考

| 命令 | 描述 |
|---------|-------------|
| `make:page` | 创建新页面 |
| `make:stateful_widget` | 创建有状态组件 |
| `make:stateless_widget` | 创建无状态组件 |
| `make:state_managed_widget` | 创建状态管理组件 |
| `make:navigation_hub` | 创建导航中心（底部导航） |
| `make:journey_widget` | 创建导航中心的旅程组件 |
| `make:bottom_sheet_modal` | 创建底部弹窗 |
| `make:button` | 创建自定义按钮组件 |
| `make:form` | 创建带验证的表单 |
| `make:model` | 创建模型类 |
| `make:provider` | 创建 provider |
| `make:api_service` | 创建 API 服务 |
| `make:controller` | 创建控制器 |
| `make:event` | 创建事件 |
| `make:theme` | 创建主题 |
| `make:route_guard` | 创建路由守卫 |
| `make:config` | 创建配置文件 |
| `make:interceptor` | 创建网络拦截器 |
| `make:command` | 创建自定义 Metro 命令 |
| `make:env` | 从 .env 生成环境配置 |

### 使用示例

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
