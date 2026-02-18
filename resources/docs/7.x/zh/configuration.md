# 配置

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 环境
  - [.env 文件](#env-file ".env 文件")
  - [生成环境配置](#generating-env "生成环境配置")
  - [获取值](#retrieving-values "获取值")
  - [创建配置类](#creating-config-classes "创建配置类")
  - [变量类型](#variable-types "变量类型")
- [环境风味](#environment-flavours "环境风味")
- [构建时注入](#build-time-injection "构建时注入")


<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 使用安全的环境配置系统。您的环境变量存储在 `.env` 文件中，然后加密到生成的 Dart 文件（`env.g.dart`）中以供应用使用。

这种方式提供了：
- **安全性**：环境值在编译后的应用中进行 XOR 加密
- **类型安全**：值自动解析为适当的类型
- **构建时灵活性**：为开发、预发布和生产提供不同的配置

<div id="env-file"></div>

## .env 文件

项目根目录中的 `.env` 文件包含您的配置变量：

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### 可用变量

| 变量 | 描述 |
|----------|-------------|
| `APP_KEY` | **必需**。用于加密的 32 位字符密钥 |
| `APP_NAME` | 您的应用名称 |
| `APP_ENV` | 环境：`developing` 或 `production` |
| `APP_DEBUG` | 启用调试模式（`true`/`false`） |
| `APP_URL` | 您的应用 URL |
| `API_BASE_URL` | API 请求的基础 URL |
| `ASSET_PATH` | 资源文件夹路径 |
| `DEFAULT_LOCALE` | 默认语言代码 |

<div id="generating-env"></div>

## 生成环境配置

{{ config('app.name') }} v7 要求您在应用访问环境值之前生成加密的环境文件。

### 步骤 1：生成 APP_KEY

首先，生成安全的 APP_KEY：

``` bash
metro make:key
```

这会在您的 `.env` 文件中添加一个 32 位字符的 `APP_KEY`。

### 步骤 2：生成 env.g.dart

生成加密的环境文件：

``` bash
metro make:env
```

这会创建包含加密环境变量的 `lib/bootstrap/env.g.dart`。

您的环境变量在应用启动时自动注册 — `main.dart` 中的 `Nylo.init(env: Env.get, ...)` 会为您处理。无需额外设置。

### 修改后重新生成

当您修改 `.env` 文件时，重新生成配置：

``` bash
metro make:env --force
```

`--force` 标志会覆盖现有的 `env.g.dart`。

<div id="retrieving-values"></div>

## 获取值

访问环境值的推荐方式是通过**配置类**。您的 `lib/config/app.dart` 文件使用 `getEnv()` 将环境值公开为有类型的静态字段：

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

然后在您的应用代码中，通过配置类访问值：

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

这种模式将环境访问集中在您的配置类中。`getEnv()` 辅助函数应在配置类中使用，而不是直接在应用代码中使用。

<div id="creating-config-classes"></div>

## 创建配置类

您可以使用 Metro 为第三方服务或特定功能的配置创建自定义配置类：

``` bash
metro make:config RevenueCat
```

这会在 `lib/config/revenue_cat_config.dart` 创建一个新的配置文件：

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### 示例：RevenueCat 配置

**步骤 1：** 将环境变量添加到您的 `.env` 文件中：

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**步骤 2：** 更新您的配置类以引用这些值：

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**步骤 3：** 重新生成您的环境配置：

``` bash
metro make:env --force
```

**步骤 4：** 在您的应用中使用配置类：

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

这种方式使您的 API 密钥和配置值保持安全和集中，便于在不同环境中管理不同的值。

<div id="variable-types"></div>

## 变量类型

`.env` 文件中的值会自动解析：

| .env 值 | Dart 类型 | 示例 |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""`（空字符串） |


<div id="environment-flavours"></div>

## 环境风味

为开发、预发布和生产创建不同的配置。

### 步骤 1：创建环境文件

创建单独的 `.env` 文件：

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

`.env.production` 示例：

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### 步骤 2：生成环境配置

从特定的环境文件生成：

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### 步骤 3：构建您的应用

使用相应的配置进行构建：

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## 构建时注入

为了增强安全性，您可以在构建时注入 APP_KEY，而不是将其嵌入源代码中。

### 使用 --dart-define 模式生成

``` bash
metro make:env --dart-define
```

这会生成不嵌入 APP_KEY 的 `env.g.dart`。

### 使用 APP_KEY 注入构建

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

这种方式将 APP_KEY 保留在源代码之外，适用于：
- 注入密钥的 CI/CD 管道
- 开源项目
- 增强安全性要求

### 最佳实践

1. **永远不要将 `.env` 提交到版本控制** - 将其添加到 `.gitignore`
2. **使用 `.env-example`** - 提交一个不包含敏感值的模板
3. **修改后重新生成** - 修改 `.env` 后始终运行 `metro make:env --force`
4. **每个环境使用不同的密钥** - 为开发、预发布和生产使用唯一的 APP_KEY
