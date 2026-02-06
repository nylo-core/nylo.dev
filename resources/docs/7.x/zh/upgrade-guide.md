# 升级指南

---

<a name="section-1"></a>
- [v7 新特性](#whats-new "v7 新特性")
- [破坏性变更概述](#breaking-changes "破坏性变更概述")
- [推荐的迁移方法](#recommended-migration "推荐的迁移方法")
- [快速迁移清单](#checklist "快速迁移清单")
- [分步迁移指南](#migration-guide "迁移指南")
  - [步骤 1：更新依赖](#step-1-dependencies "更新依赖")
  - [步骤 2：环境配置](#step-2-environment "环境配置")
  - [步骤 3：更新 main.dart](#step-3-main "更新 main.dart")
  - [步骤 4：更新 boot.dart](#step-4-boot "更新 boot.dart")
  - [步骤 5：重组配置文件](#step-5-config "重组配置文件")
  - [步骤 6：更新 AppProvider](#step-6-provider "更新 AppProvider")
  - [步骤 7：更新 Theme 配置](#step-7-theme "更新 Theme 配置")
  - [步骤 10：迁移 Widgets](#step-10-widgets "迁移 Widgets")
  - [步骤 11：更新资源路径](#step-11-assets "更新资源路径")
- [已移除的功能及替代方案](#removed-features "已移除的功能及替代方案")
- [已删除类参考](#deleted-classes "已删除类参考")
- [Widget 迁移参考](#widget-reference "Widget 迁移参考")
- [故障排除](#troubleshooting "故障排除")


<div id="whats-new"></div>

## v7 新特性

{{ config('app.name') }} v7 是一个重大版本更新，为开发者体验带来了显著改进：

### 加密环境配置
- 环境变量现在在构建时进行 XOR 加密以提高安全性
- 新增 `metro make:key` 命令生成您的 APP_KEY
- 新增 `metro make:env` 命令生成加密的 `env.g.dart`
- 支持通过 `--dart-define` 注入 APP_KEY 以适配 CI/CD 流水线

### 简化的启动流程
- 新的 `BootConfig` 模式取代了分离的 setup/finished 回调
- 更简洁的 `Nylo.init()` 带有 `env` 参数用于加密环境
- 应用生命周期钩子直接在 main.dart 中配置

### 新的 nylo.configure() API
- 单一方法整合所有应用配置
- 更简洁的语法取代单独的 `nylo.add*()` 调用
- provider 中分离的 `setup()` 和 `boot()` 生命周期方法

### 用于 Pages 的 NyPage
- `NyPage` 取代 `NyState` 用于页面 widget（语法更简洁）
- `view()` 取代 `build()` 方法
- `get init =>` getter 取代 `init()` 和 `boot()` 方法
- `NyState` 仍可用于非页面的 stateful widget

### LoadingStyle 系统
- 新的 `LoadingStyle` 枚举用于一致的加载状态
- 选项：`LoadingStyle.normal()`、`LoadingStyle.skeletonizer()`、`LoadingStyle.none()`
- 通过 `LoadingStyle.normal(child: ...)` 自定义加载 widget

### RouteView 类型安全路由
- `static RouteView path` 取代 `static const path`
- 带有 widget 工厂的类型安全路由定义

### 多 Theme 支持
- 注册多个深色和浅色 theme
- Theme ID 在代码中定义而非 `.env` 文件
- 新增 `NyThemeType.dark` / `NyThemeType.light` 用于 theme 分类
- 首选 theme API：`NyTheme.setPreferredDark()`、`NyTheme.setPreferredLight()`
- Theme 枚举：`NyTheme.lightThemes()`、`NyTheme.darkThemes()`、`NyTheme.all()`

### 新的 Metro 命令
- `make:key` - 生成用于加密的 APP_KEY
- `make:env` - 生成加密的环境文件
- `make:bottom_sheet_modal` - 创建底部弹出模态框
- `make:button` - 创建自定义按钮

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">在 GitHub 上查看所有变更</a>

<div id="breaking-changes"></div>

## 破坏性变更概述

| 变更 | v6 | v7 |
|--------|-----|-----|
| 应用根 Widget | `LocalizedApp(child: Main(nylo))` | `Main(nylo)`（使用 `NyApp.materialApp()`） |
| 页面 State 类 | `NyState` | `NyPage` 用于页面 |
| 视图方法 | `build()` | `view()` |
| 初始化方法 | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Route 路径 | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider 启动 | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| 配置方式 | 单独的 `nylo.add*()` 调用 | 单一 `nylo.configure()` 调用 |
| Theme ID | `.env` 文件（`LIGHT_THEME_ID`、`DARK_THEME_ID`） | 代码中（`type: NyThemeType.dark`） |
| 加载 Widget | `useSkeletonizer` + `loading()` | `LoadingStyle` getter |
| 配置位置 | `lib/config/` | `lib/bootstrap/`（decoders、events、providers、theme） |
| 资源位置 | `public/` | `assets/` |

<div id="recommended-migration"></div>

## 推荐的迁移方法

对于较大的项目，我们建议创建一个全新的 v7 项目并迁移文件：

1. 创建新的 v7 项目：`git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. 复制您的 pages、controllers、models 和 services
3. 按上述说明更新语法
4. 彻底测试

这确保您拥有所有最新的样板结构和配置。

如果您想查看 v6 和 v7 之间的变更差异，可以在 GitHub 上查看对比：<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## 快速迁移清单

使用此清单跟踪您的迁移进度：

- [ ] 更新 `pubspec.yaml`（Dart >=3.10.7、Flutter >=3.24.0、nylo_framework: ^7.0.0）
- [ ] 运行 `flutter pub get`
- [ ] 运行 `metro make:key` 生成 APP_KEY
- [ ] 运行 `metro make:env` 生成加密的环境配置
- [ ] 使用 env 参数和 BootConfig 更新 `main.dart`
- [ ] 将 `Boot` 类转换为使用 `BootConfig` 模式
- [ ] 将配置文件从 `lib/config/` 移动到 `lib/bootstrap/`
- [ ] 创建新的配置文件（`lib/config/app.dart`、`lib/config/storage_keys.dart`、`lib/config/toast_notification.dart`）
- [ ] 更新 `AppProvider` 以使用 `nylo.configure()`
- [ ] 从 `.env` 中移除 `LIGHT_THEME_ID` 和 `DARK_THEME_ID`
- [ ] 为深色 theme 配置添加 `type: NyThemeType.dark`
- [ ] 将所有页面 widget 的 `NyState` 重命名为 `NyPage`
- [ ] 将所有页面中的 `build()` 改为 `view()`
- [ ] 将所有页面中的 `init()/boot()` 改为 `get init =>`
- [ ] 将 `static const path` 更新为 `static RouteView path`
- [ ] 在路由中将 `router.route()` 改为 `router.add()`
- [ ] 重命名 widget（NyListView -> CollectionView 等）
- [ ] 将资源从 `public/` 移动到 `assets/`
- [ ] 更新 `pubspec.yaml` 中的资源路径
- [ ] 移除 Firebase 导入（如果使用 - 直接添加包）
- [ ] 移除 NyDevPanel 使用（改用 Flutter DevTools）
- [ ] 运行 `flutter pub get` 并测试

<div id="migration-guide"></div>

## 分步迁移指南

<div id="step-1-dependencies"></div>

### 步骤 1：更新依赖

更新您的 `pubspec.yaml`：

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... 其他依赖
```

运行 `flutter pub get` 更新包。

<div id="step-2-environment"></div>

### 步骤 2：环境配置

v7 需要加密的环境变量以提高安全性。

**1. 生成 APP_KEY：**

``` bash
metro make:key
```

这会将 `APP_KEY` 添加到您的 `.env` 文件。

**2. 生成加密的 env.g.dart：**

``` bash
metro make:env
```

这会创建 `lib/bootstrap/env.g.dart`，包含您的加密环境变量。

**3. 从 .env 中移除已弃用的 theme 变量：**

``` bash
# 从您的 .env 文件中移除这些行：
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### 步骤 3：更新 main.dart

**v6：**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7：**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // 可选：添加应用生命周期钩子
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**主要变更：**
- 导入生成的 `env.g.dart`
- 将 `Env.get` 传递给 `env` 参数
- `Boot.nylo` 现在是 `Boot.nylo()`（返回 `BootConfig`）
- `setupFinished` 已移除（在 `BootConfig` 内部处理）
- 可选的 `appLifecycle` 钩子用于应用状态变化

<div id="step-4-boot"></div>

### 步骤 4：更新 boot.dart

**v6：**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7：**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**主要变更：**
- 返回 `BootConfig` 而非 `Future<Nylo>`
- `setup` 和 `finished` 合并为单一 `BootConfig` 对象
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### 步骤 5：重组配置文件

v7 重组了配置文件以获得更好的结构：

| v6 位置 | v7 位置 | 操作 |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | 移动 |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | 移动 |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | 移动 |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | 移动 |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | 重命名并重构 |
| （新建） | `lib/config/app.dart` | 创建 |
| （新建） | `lib/config/toast_notification.dart` | 创建 |

**创建 lib/config/app.dart：**

参考：<a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // 应用名称。
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // 应用版本。
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // 在此添加其他应用配置
}
```

**创建 lib/config/storage_keys.dart：**

参考：<a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // 定义需要在启动时同步的键
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // 默认给用户 10 个金币
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// 在此添加您的 storage key...
}
```

**创建 lib/config/toast_notification.dart：**

参考：<a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // 在此自定义 toast 样式
  };
}
```

<div id="step-6-provider"></div>

### 步骤 6：更新 AppProvider

**v6：**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7：**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**主要变更：**
- `boot()` 现在是 `setup()` 用于初始配置
- `boot()` 现在用于 setup 后的逻辑（之前是 `afterBoot`）
- 所有 `nylo.add*()` 调用整合为单一 `nylo.configure()`
- 本地化使用 `NyLocalizationConfig` 对象

<div id="step-7-theme"></div>

### 步骤 7：更新 Theme 配置

**v6（.env 文件）：**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6（theme.dart）：**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7（theme.dart）：**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**主要变更：**
- 从 `.env` 中移除 `LIGHT_THEME_ID` 和 `DARK_THEME_ID`
- 直接在代码中定义 theme ID
- 为所有深色 theme 配置添加 `type: NyThemeType.dark`
- 浅色 theme 默认为 `NyThemeType.light`

**新的 Theme API 方法（v7）：**
``` dart
// 设置并记住首选 theme
NyTheme.set(context, id: 'dark_theme', remember: true);

// 设置系统跟随的首选 theme
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// 获取首选 theme ID
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Theme 枚举
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// 清除保存的偏好设置
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### 步骤 10：迁移 Widgets

#### NyListView -> CollectionView

**v6：**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7：**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// 带分页（下拉刷新）：
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

**v6：**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7：**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField -> InputField

**v6：**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7：**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText -> StyledText

**v6：**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7：**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher -> LanguageSwitcher

**v6：**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7：**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### 步骤 11：更新资源路径

v7 将资源目录从 `public/` 更改为 `assets/`：

**1. 移动您的资源文件夹：**
``` bash
# 移动目录
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. 更新 pubspec.yaml：**

**v6：**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7：**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. 更新代码中的资源引用：**

**v6：**
``` dart
Image.asset('public/images/logo.png')
```

**v7：**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget - 已移除

参考：<a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**迁移方法：** 直接使用 `Main(nylo)`。`NyApp.materialApp()` 在内部处理本地化。

**v6：**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7：**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## 已删除类参考

| 已删除的类 | 替代方案 |
|---------------|-------------|
| `NyTextStyle` | 直接使用 Flutter 的 `TextStyle` |
| `NyBaseApiService` | 使用 `DioApiService` |
| `BaseColorStyles` | 使用 `ThemeColor` |
| `LocalizedApp` | 直接使用 `Main(nylo)` |
| `NyException` | 使用标准 Dart 异常 |
| `PushNotification` | 直接使用 `flutter_local_notifications` |
| `PushNotificationAttachments` | 直接使用 `flutter_local_notifications` |

<div id="widget-reference"></div>

## Widget 迁移参考

### 重命名的 Widgets

| v6 Widget | v7 Widget | 备注 |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | 新 API 使用 `builder` 而非 `child` |
| `NyFutureBuilder` | `FutureWidget` | 简化的异步 widget |
| `NyTextField` | `InputField` | 使用 `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | API 相同 |
| `NyRichText` | `StyledText` | API 相同 |
| `NyFader` | `FadeOverlay` | API 相同 |

### 已删除的 Widgets（无直接替代）

| 已删除的 Widget | 替代方案 |
|----------------|-------------|
| `NyPullToRefresh` | 使用 `CollectionView.pullable()` |

### Widget 迁移示例

**NyPullToRefresh -> CollectionView.pullable()：**

**v6：**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7：**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader -> FadeOverlay：**

**v6：**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7：**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## 故障排除

### "Env.get not found" 或 "Env is not defined"

**解决方案：** 运行环境生成命令：
``` bash
metro make:key
metro make:env
```
然后在 `main.dart` 中导入生成的文件：
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" 或 "Dark theme not working"

**解决方案：** 确保深色 theme 有 `type: NyThemeType.dark`：
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // 添加这一行
),
```

### "LocalizedApp not found"

参考：<a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**解决方案：** `LocalizedApp` 已被移除。更改：
``` dart
// 从：
runApp(LocalizedApp(child: Main(nylo)));

// 改为：
runApp(Main(nylo));
```

### "router.route is not defined"

**解决方案：** 使用 `router.add()` 代替：
``` dart
// 从：
router.route(HomePage.path, (context) => HomePage());

// 改为：
router.add(HomePage.path);
```

### "NyListView not found"

**解决方案：** `NyListView` 现在是 `CollectionView`：
``` dart
// 从：
NyListView(...)

// 改为：
CollectionView<MyModel>(...)
```

### 资源无法加载（图片、字体）

**解决方案：** 将资源路径从 `public/` 更新为 `assets/`：
1. 移动文件：`mv public/* assets/`
2. 更新 `pubspec.yaml` 路径
3. 更新代码引用

### "init() must return a value of type Future"

**解决方案：** 改为 getter 语法：
``` dart
// 从：
@override
init() async { ... }

// 改为：
@override
get init => () async { ... };
```

---

**需要帮助？** 查看 [Nylo 文档](https://nylo.dev/docs/7.x) 或在 [GitHub](https://github.com/nylo-core/nylo/issues) 上提交 issue。
