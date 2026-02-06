# Providers

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [创建 Provider](#create-a-provider "创建 Provider")
- [Provider 对象](#provider-object "Provider 对象")


<div id="introduction"></div>

## Providers 简介

在 {{ config('app.name') }} 中，providers 在应用运行时从 <b>main.dart</b> 文件初始启动。您所有的 providers 位于 `/lib/app/providers/*`，您可以修改这些文件或使用 <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a> 创建您的 providers。

当您需要在应用初始加载之前初始化类、包或创建某些内容时，可以使用 Providers。例如，`route_provider.dart` 类负责将所有路由添加到 {{ config('app.name') }}。

### 深入了解

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### 生命周期

- `Boot.{{ config('app.name') }}` 将遍历 <b>config/providers.dart</b> 文件中注册的 providers 并启动它们。

- `Boot.Finished` 在 **"Boot.{{ config('app.name') }}"** 完成后立即调用，此方法将 {{ config('app.name') }} 实例绑定到 `Backpack`，值为 'nylo'。

例如 Backpack.instance.read('nylo'); // {{ config('app.name') }} 实例


<div id="create-a-provider"></div>

## 创建新 Provider

您可以通过在终端中运行以下命令来创建新的 providers。

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Provider 对象

您的 provider 将有两个方法，`setup(Nylo nylo)` 和 `boot(Nylo nylo)`。

当应用首次运行时，**setup** 方法中的任何代码将首先执行。您也可以像下面的示例一样操作 `Nylo` 对象。

示例：`lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### 生命周期

1. `setup(Nylo nylo)` - 初始化您的 provider。返回 `Nylo` 实例或 `null`。
2. `boot(Nylo nylo)` - 在所有 providers 完成 setup 后调用。用于依赖其他 providers 准备就绪的初始化。

> 在 `setup` 方法中，您必须像上面那样**返回** `Nylo` 实例或 `null`。
