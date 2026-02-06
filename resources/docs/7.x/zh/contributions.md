# 贡献 {{ config('app.name') }}

---

<a name="section-1"></a>
- [简介](#introduction "贡献简介")
- [开始使用](#getting-started "开始贡献")
- [开发环境](#development-environment "设置开发环境")
- [开发指南](#development-guidelines "开发指南")
- [提交更改](#submitting-changes "如何提交更改")
- [报告问题](#reporting-issues "如何报告问题")


<div id="introduction"></div>

## 简介

感谢您考虑为 {{ config('app.name') }} 做出贡献！

本指南将帮助您了解如何为该微框架做出贡献。无论您是修复错误、添加功能还是改进文档，您的贡献对 {{ config('app.name') }} 社区都很有价值。

{{ config('app.name') }} 分为三个仓库：

| 仓库 | 用途 |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | 样板应用 |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | 核心框架类（nylo_framework） |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | 支持库，包含组件、辅助函数、实用工具（nylo_support） |

<div id="getting-started"></div>

## 开始使用

### Fork 仓库

Fork 您想要贡献的仓库：

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo 样板</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### 克隆您的 Fork

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## 开发环境

### 要求

确保您已安装以下内容：

| 要求 | 最低版本 |
|-------------|-----------------|
| Flutter | 3.24.0 或更高 |
| Dart SDK | 3.10.7 或更高 |

### 链接本地包

在编辑器中打开 Nylo 样板，并添加依赖覆盖以使用您本地的 framework 和 support 仓库：

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

运行 `flutter pub get` 安装依赖。

现在您对 framework 或 support 仓库所做的更改将反映在 Nylo 样板中。

### 测试您的更改

运行样板应用以测试您的更改：

``` bash
flutter run
```

对于组件或辅助函数的更改，请考虑在相应的仓库中添加测试。

<div id="development-guidelines"></div>

## 开发指南

### 代码风格

- 遵循官方 <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart 风格指南</a>
- 使用有意义的变量和函数名
- 为复杂逻辑编写清晰的注释
- 为公共 API 添加文档
- 保持代码模块化和可维护

### 文档

添加新功能时：

- 为公共类和方法添加 dartdoc 注释
- 如需要，更新相关的文档文件
- 在文档中包含代码示例

### 测试

提交更改之前：

- 在 iOS 和 Android 设备/模拟器上进行测试
- 尽可能验证向后兼容性
- 清楚地记录任何破坏性更改
- 运行现有测试以确保没有破坏任何内容

<div id="submitting-changes"></div>

## 提交更改

### 先讨论

对于新功能，最好先与社区讨论：

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub 讨论</a>

### 创建分支

``` bash
git checkout -b feature/your-feature-name
```

使用描述性的分支名：
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### 提交您的更改

``` bash
git add .
git commit -m "Add: Your feature description"
```

使用清晰的提交信息：
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### 推送并创建 Pull Request

``` bash
git push origin feature/your-feature-name
```

然后在 GitHub 上创建 Pull Request。

### Pull Request 指南

- 提供清晰的更改描述
- 引用任何相关的 issue
- 如果适用，包含截图或代码示例
- 确保您的 PR 只解决一个问题
- 保持更改专注和原子化

<div id="reporting-issues"></div>

## 报告问题

### 报告之前

1. 检查该问题是否已在 GitHub 上存在
2. 确保您使用的是最新版本
3. 尝试在新项目中重现该问题

### 在哪里报告

在相应的仓库上报告问题：

- **样板问题**：<a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **框架问题**：<a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **支持库问题**：<a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Issue 模板

提供详细信息：

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### 获取版本信息

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
