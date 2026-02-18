# 目录结构

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [根目录](#root-directory "根目录")
- [lib 目录](#lib-directory "lib 目录")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [资源目录](#assets-directory "资源目录")
- [资源辅助函数](#asset-helpers "资源辅助函数")


<div id="introduction"></div>

## 简介

{{ config('app.name') }} 使用受 <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a> 启发的简洁、有条理的目录结构。这种结构有助于保持项目间的一致性，并使查找文件变得容易。

<div id="root-directory"></div>

## 根目录

```
nylo_app/
├── android/          # Android platform files
├── assets/           # Images, fonts, and other assets
├── ios/              # iOS platform files
├── lang/             # Language/translation JSON files
├── lib/              # Dart application code
├── test/             # Test files
├── .env              # Environment variables
├── pubspec.yaml      # Dependencies and project config
└── ...
```

<div id="lib-directory"></div>

## lib 目录

`lib/` 文件夹包含所有 Dart 应用代码：

```
lib/
├── app/              # Application logic
├── bootstrap/        # Boot configuration
├── config/           # Configuration files
├── resources/        # UI components
├── routes/           # Route definitions
└── main.dart         # Application entry point
```

<div id="app-directory"></div>

### app/

`app/` 目录包含应用的核心逻辑：

| 目录 | 用途 |
|-----------|---------|
| `commands/` | 自定义 Metro CLI 命令 |
| `controllers/` | 业务逻辑的页面控制器 |
| `events/` | 事件系统的事件类 |
| `forms/` | 带验证的表单类 |
| `models/` | 数据模型类 |
| `networking/` | API 服务和网络配置 |
| `networking/dio/interceptors/` | Dio HTTP 拦截器 |
| `providers/` | 应用启动时引导的服务提供者 |
| `services/` | 通用服务类 |

<div id="bootstrap-directory"></div>

### bootstrap/

`bootstrap/` 目录包含配置应用启动方式的文件：

| 文件 | 用途 |
|------|---------|
| `boot.dart` | 主引导序列配置 |
| `decoders.dart` | 模型和 API 解码器注册 |
| `env.g.dart` | 生成的加密环境配置 |
| `events.dart` | 事件注册 |
| `extensions.dart` | 自定义扩展 |
| `helpers.dart` | 自定义辅助函数 |
| `providers.dart` | Provider 注册 |
| `theme.dart` | 主题配置 |

<div id="config-directory"></div>

### config/

`config/` 目录包含应用配置：

| 文件 | 用途 |
|------|---------|
| `app.dart` | 核心应用设置 |
| `design.dart` | 应用设计（字体、Logo、加载器） |
| `localization.dart` | 语言和区域设置 |
| `storage_keys.dart` | 本地存储键定义 |
| `toast_notification.dart` | Toast 通知样式 |

<div id="resources-directory"></div>

### resources/

`resources/` 目录包含 UI 组件：

| 目录 | 用途 |
|-----------|---------|
| `pages/` | 页面组件（屏幕） |
| `themes/` | 主题定义 |
| `themes/light/` | 浅色主题颜色 |
| `themes/dark/` | 深色主题颜色 |
| `widgets/` | 可复用组件 |
| `widgets/buttons/` | 自定义按钮组件 |
| `widgets/bottom_sheet_modals/` | 底部弹窗组件 |

<div id="routes-directory"></div>

### routes/

`routes/` 目录包含路由配置：

| 文件/目录 | 用途 |
|----------------|---------|
| `router.dart` | 路由定义 |
| `guards/` | 路由守卫类 |

<div id="assets-directory"></div>

## 资源目录

`assets/` 目录存储静态文件：

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### 注册资源

资源在 `pubspec.yaml` 中注册：

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## 资源辅助函数

{{ config('app.name') }} 提供了处理资源的辅助函数。

### 图片资源

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### 通用资源

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### 语言文件

语言文件存储在项目根目录的 `lang/` 中：

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

查看[本地化](/docs/7.x/localization)了解更多详情。
