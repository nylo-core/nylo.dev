# 系统要求

---

<a name="section-1"></a>
- [系统要求](#system-requirements "系统要求")
- [安装 Flutter](#installing-flutter "安装 Flutter")
- [验证安装](#verifying-installation "验证安装")
- [设置编辑器](#set-up-an-editor "设置编辑器")


<div id="system-requirements"></div>

## 系统要求

{{ config('app.name') }} v7 需要以下最低版本：

| 要求 | 最低版本 |
|-------------|-----------------|
| **Flutter** | 3.24.0 或更高 |
| **Dart SDK** | 3.10.7 或更高 |

### 平台支持

{{ config('app.name') }} 支持 Flutter 支持的所有平台：

| 平台 | 支持 |
|----------|---------|
| iOS | ✓ 完全支持 |
| Android | ✓ 完全支持 |
| Web | ✓ 完全支持 |
| macOS | ✓ 完全支持 |
| Windows | ✓ 完全支持 |
| Linux | ✓ 完全支持 |

<div id="installing-flutter"></div>

## 安装 Flutter

如果您尚未安装 Flutter，请按照您操作系统的官方安装指南操作：

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter 安装指南</a>

<div id="verifying-installation"></div>

## 验证安装

安装 Flutter 后，验证您的设置：

### 检查 Flutter 版本

``` bash
flutter --version
```

您应该看到类似以下的输出：

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### 更新 Flutter（如需要）

如果您的 Flutter 版本低于 3.24.0，请升级到最新的稳定版本：

``` bash
flutter channel stable
flutter upgrade
```

### 运行 Flutter Doctor

验证您的开发环境是否正确配置：

``` bash
flutter doctor -v
```

此命令检查：
- Flutter SDK 安装
- Android 工具链（用于 Android 开发）
- Xcode（用于 iOS/macOS 开发）
- 已连接的设备
- IDE 插件

在继续 {{ config('app.name') }} 安装之前，修复报告的所有问题。

<div id="set-up-an-editor"></div>

## 设置编辑器

选择一个支持 Flutter 的 IDE：

### Visual Studio Code（推荐）

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> 轻量且拥有出色的 Flutter 支持。

1. 安装 <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. 安装 <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter 扩展</a>
3. 安装 <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart 扩展</a>

设置指南：<a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter 设置</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> 提供了功能齐全的 IDE，内置模拟器支持。

1. 安装 <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. 安装 Flutter 插件（Preferences → Plugins → Flutter）
3. 安装 Dart 插件

设置指南：<a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter 设置</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a>（Community 或 Ultimate）也支持 Flutter 开发。

1. 安装 IntelliJ IDEA
2. 安装 Flutter 插件（Preferences → Plugins → Flutter）
3. 安装 Dart 插件

编辑器配置完成后，您就可以[安装 {{ config('app.name') }}](/docs/7.x/installation)了。
